<?php

require_once "../barang/function.php";
require_once "../connection.php";

// <class dan implements interface>
class Pinjam implements CRUD{

    // <properti>
    private $db;
    private $dbh;

    // <constructor>
    public function __construct(){
        
        // <koneksi database>
        $this->dbh = new Connection;
        $this->db = $this->dbh->getConn();

        date_default_timezone_set('Asia/Jakarta');

    }

    // <method/function>
    public function store($data){
        
        // <variabel, properti, dan manipulasi string>
        // data customer
        $nik = $data["nik"];
        $arr_nama = explode(" ", htmlspecialchars(trim($data["nama"])));
        $arr_nama_2 = [];
        foreach($arr_nama as $nama){
            array_push($arr_nama_2, ucwords(strtolower($nama)));
        }
        $nama = implode(" ", $arr_nama_2);
        $no_hp = $data["no_hp"];
        $alamat = htmlspecialchars(trim($data["alamat"]));

        // data pinjam
        $tanggal_pinjam = date("Y-m-d", strtotime($data["tanggal_pinjam"]));
        $durasi = $data["durasi"];

        // data barang yg dipinjam
        $kode_barang = $data["kode_barang"];
        $qty = $data["qty"];

        // <crud>
        // cek data customer
        $statement = $this->db->prepare("SELECT * FROM customer WHERE nik = ?");
        $statement->bind_param("s", $nik);
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();

        // jika belum terdaftar
        // <operator perbandingan>
        if($result->num_rows == 0){
            $statement = $this->db->prepare("INSERT INTO customer VALUES(?, ?, ?, ?)");
            $statement->bind_param("ssss", $nik, $nama, $no_hp, $alamat);
            $statement->execute();
            $statement->close();
        }

        // insert data ke tabel pinjam
        $statement = $this->db->prepare("INSERT INTO pinjam VALUES('', ?, ?, ?, 0)");
        $statement->bind_param("ssi", $nik, $tanggal_pinjam, $durasi);
        $statement->execute();
        $id_pinjam = $statement->insert_id;
        $statement->close();

        // ambil dan insert data
        for($i=0;$i<count($kode_barang);$i++){
            $stock = 0;
            $statement = $this->db->prepare("SELECT stock FROM barang WHERE kode_barang = ?");
            $statement->bind_param("i", $kode_barang[$i]);
            $statement->execute();
            $resultset = $statement->get_result();
            $result = $resultset->fetch_assoc();

            $stock = $result["stock"]-$qty[$i];

            $statement = $this->db->prepare("INSERT INTO detail_pinjam VALUES(?, ?, ?)");
            $statement->bind_param("iii", $id_pinjam, $kode_barang[$i], $qty[$i]);
            $statement->execute();

            $statement = $this->db->prepare("UPDATE barang SET stock = ? WHERE kode_barang = ?");
            $statement->bind_param("ii", $stock, $kode_barang[$i]);
            $statement->execute();
        }

        if($this->db->affected_rows > 0){
            $_SESSION["success"]="Data peminjaman berhasil ditambahkan";
        }
        else{
            $_SESSION["fail"]="Data peminjaman gagal ditambahkan";
        }

        $statement->close();
        header("Location: peminjaman.php");

    }

    public function destroy($id_pinjam){
        
        // <crud>
        $statement = $this->db->prepare("DELETE FROM pinjam WHERE id_pinjam = ?");
        $statement->bind_param("i", $id_pinjam);
        $statement->execute();

        if($this->db->affected_rows > 0){
            $_SESSION["success"]="Data berhasil dihapus";
        }
        else{
            $_SESSION["fail"]="Data gagal dihapus";
        }

        $statement->close();
        header("Location: peminjaman.php");

    }

    public function destroy_barang($id_pinjam, $kode_barang){
        
        try{
            // <crud>
            $this->db->begin_transaction();
            $statement = $this->db->prepare("SELECT barang.stock, detail_pinjam.qty FROM barang INNER JOIN detail_pinjam ON barang.kode_barang = detail_pinjam.kode_barang WHERE id_pinjam = ? AND detail_pinjam.kode_barang = ?");
            $statement->bind_param("ii", $id_pinjam, $kode_barang);
            $statement->execute();
            $resultset = $statement->get_result();
            $result = $resultset->fetch_assoc();
            $stock = $result["stock"] + $result["qty"];

            $statement = $this->db->prepare("DELETE FROM detail_pinjam WHERE id_pinjam = ? AND kode_barang = ?");
            $statement->bind_param("ii", $id_pinjam, $kode_barang);
            $statement->execute();

            $statement = $this->db->prepare("UPDATE barang SET stock = ? WHERE kode_barang = ?");
            $statement->bind_param("ii", $stock, $kode_barang);
            $statement->execute();

            if($this->db->commit() == true){
                $_SESSION["success"]="Barang berhasil dihapus";
            }
            else{
                $_SESSION["fail"]="Barang gagal dihapus";
            }

            $statement->close();
        }
        catch(Exception $e){
            $this->db->rollback();
            $_SESSION["fail"]="Proses gagal";
        }
        header("Location: detail_pinjam.php?id=".$id_pinjam);

    }

    public function update($data, $kode_barang){
        
    }

    public function show(){

        try{
            // <crud>
            $this->db->begin_transaction();
            $pinjam = $this->db->query("SELECT * FROM pinjam WHERE CURDATE() > tanggal_pinjam AND status = 0");

            if($pinjam->num_rows > 0){
                foreach($pinjam as $pjm){
                    $statement = $this->db->prepare("UPDATE pinjam SET status = 4 WHERE id_pinjam = ?");
                    $statement->bind_param("i", $pjm["id_pinjam"]);
                    $statement->execute();

                    $statement = $this->db->prepare("SELECT * FROM detail_pinjam INNER JOIN barang ON detail_pinjam.kode_barang = barang.kode_barang WHERE detail_pinjam.id_pinjam = ?");
                    $statement->bind_param("i", $pjm["id_pinjam"]);
                    $statement->execute();

                    foreach($statement->get_result() as $brg){
                        $stock = $brg["stock"]+$brg["qty"];
                        $statement = $this->db->prepare("UPDATE barang SET stock = ? WHERE kode_barang = ?");
                        $statement->bind_param("ii", $stock, $brg["kode_barang"]);
                        $statement->execute();
                    }
                }
                $statement->close();
            }
            
            $daftar_pinjam = $this->db->query("SELECT customer.nama, pinjam.*, kembali.tanggal_kembali, COUNT(detail_pinjam.kode_barang) AS jumlah FROM customer INNER JOIN pinjam ON customer.nik = pinjam.nik LEFT JOIN kembali ON pinjam.id_pinjam = kembali.id_pinjam LEFT JOIN detail_pinjam ON pinjam.id_pinjam = detail_pinjam.id_pinjam GROUP BY pinjam. id_pinjam ORDER BY pinjam.tanggal_pinjam, pinjam.durasi ASC");
            
            $this->db->commit();
            return $daftar_pinjam;
        }
        catch(Exception $e){
            $this->db->rollback();
            $_SESSION["fail"]="Proses gagal";
        }

    }

    public function detail_customer($id_pinjam){
        
        // <crud>
        $statement = $this->db->prepare("SELECT customer.*, pinjam.* FROM customer INNER JOIN pinjam ON customer.nik = pinjam.nik WHERE id_pinjam = ?");
        $statement->bind_param("s", $id_pinjam);
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();
        return $result->fetch_assoc();

    }

    public function denda($id_pinjam){

        // <crud>
        $statement = $this->db->prepare("SELECT kembali.tanggal_kembali, kembali.denda FROM pinjam LEFT JOIN kembali ON pinjam.id_pinjam = kembali.id_pinjam WHERE pinjam.id_pinjam = ?");
        $statement->bind_param("i", $id_pinjam);
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();
        return $result->fetch_assoc();

    }

    public function detail_barang($id_pinjam){
        
        // <variabel dan crud>
        $result = [];
        $statement = $this->db->prepare("SELECT barang.*, detail_pinjam.qty FROM pinjam INNER JOIN detail_pinjam ON pinjam.id_pinjam = detail_pinjam.id_pinjam INNER JOIN barang ON detail_pinjam.kode_barang = barang.kode_barang WHERE detail_pinjam.id_pinjam = ? ORDER BY barang.nama_barang ASC");
        $statement->bind_param("i", $id_pinjam);
        $statement->execute();
        array_push($result, $statement->get_result());

        $statement = $this->db->prepare("SELECT COUNT(kode_barang) AS jumlah FROM detail_pinjam WHERE id_pinjam = ?");
        $statement->bind_param("i", $id_pinjam);
        $statement->execute();
        $resultset = $statement->get_result();
        array_push($result, $resultset->fetch_assoc());
        $statement->close();

        return $result;

    }

    public function total($id_pinjam){

        // <crud>
        $statement = $this->db->prepare("SELECT SUM(barang.harga*detail_pinjam.qty*pinjam.durasi) AS total FROM pinjam INNER JOIN detail_pinjam ON pinjam.id_pinjam = detail_pinjam.id_pinjam INNER JOIN barang ON detail_pinjam.kode_barang = barang.kode_barang WHERE detail_pinjam.id_pinjam = ?");
        $statement->bind_param("i", $id_pinjam);
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();
        return $result->fetch_assoc();

    }

    public function restore($id_pinjam){

        try{
            // <crud>
            // ambil data barang yang dipinjam
            $this->db->begin_transaction();
            $statement = $this->db->prepare("SELECT DATE_ADD(tanggal_pinjam, INTERVAL durasi-1 DAY) AS tanggal_kembali, durasi FROM pinjam WHERE id_pinjam = ?");
            $statement->bind_param("i", $id_pinjam);
            $statement->execute();
            $result = $statement->get_result();
            $barang = $result->fetch_assoc();
            $statement->close();
            $result->close();

            $statement = $this->db->prepare("SELECT barang.*, detail_pinjam.qty FROM barang INNER JOIN detail_pinjam ON barang.kode_barang = detail_pinjam.kode_barang WHERE id_pinjam = ?");
            $statement->bind_param("i", $id_pinjam);
            $statement->execute();
            $detail = $statement->get_result();
            $statement->close();

            // <variabel, properti>
            // hitung jumlah hari telat
            $today = new DateTime();
            $tgl_kembali = new DateTime($barang["tanggal_kembali"]);
            $telat = $today->diff($tgl_kembali)->d;
            $now = $today->format("Y-m-d");

            // <operator perbandingan>
            // jika telat
            if($telat > 0 && $tgl_kembali->format("Y-m-d") < $now){
                $denda = 0;
                // <aritmatika>
                foreach($detail as $brg){
                    if($brg["harga"] <= 50000){
                        $denda += 5000*$brg["qty"]*$telat;
                    }
                    elseif($brg["harga"] > 50000 && $brg["harga"] <= 100000){
                        $denda += 10000*$brg["qty"]*$telat;
                    }
                    elseif($brg["harga"] > 100000 && $brg["harga"] <= 200000){
                        $denda += 20000*$brg["qty"]*$telat;
                    }
                    elseif($brg["harga"] > 200000 && $brg["harga"] <= 300000){
                        $denda += 30000*$brg["qty"]*$telat;
                    }
                    elseif($brg["harga"] > 300000 && $brg["harga"] <= 400000){
                        $denda += 40000*$brg["qty"]*$telat;
                    }
                    elseif($brg["harga"] > 400000 && $brg["harga"] <= 500000){
                        $denda += 50000*$brg["qty"]*$telat;
                    }
                }

                $statement = $this->db->prepare("INSERT INTO kembali VALUES(?, ?, ?)");
                $statement->bind_param("isi", $id_pinjam, $now, $denda);
                $statement->execute();

                $statement = $this->db->prepare("UPDATE pinjam SET status = 3 WHERE id_pinjam = ?");
                $statement->bind_param("i", $id_pinjam);
                $statement->execute();
            }
            // jika tidak telat
            else{
                $statement = $this->db->prepare("INSERT INTO kembali VALUES(?, ?, NULL)");
                $statement->bind_param("is", $id_pinjam, $now);
                $statement->execute();

                $statement = $this->db->prepare("UPDATE pinjam SET status = 2 WHERE id_pinjam = ?");
                $statement->bind_param("i", $id_pinjam);
                $statement->execute();
            }

            foreach($detail as $brg){
                $restock = $brg["stock"] + $brg["qty"];
                $statement = $this->db->prepare("UPDATE barang SET stock = ? WHERE kode_barang = ?");
                $statement->bind_param("ii", $restock, $brg["kode_barang"]);
                $statement->execute();
            }
            
            if($this->db->commit() == true){
                $_SESSION["success"]="Barang berhasil dikembalikan";
            }
            else{
                $_SESSION["fail"]="Barang gagal dikembalikan";
            }
            
            $statement->close();
        }
        catch(Exception $e){
            $this->db->rollback();
            $_SESSION["fail"]="Proses gagal";
        }

        header("Location: detail_pinjam.php?id=".$id_pinjam);

    }

    public function cancel($id_pinjam){
        
        try{
            // <crud>
            // ambil data barang yang dipinjam
            $this->db->begin_transaction();
            $statement = $this->db->prepare("SELECT barang.*, detail_pinjam.qty FROM barang INNER JOIN detail_pinjam ON barang.kode_barang = detail_pinjam.kode_barang WHERE id_pinjam = ?");
            $statement->bind_param("i", $id_pinjam);
            $statement->execute();
            $detail = $statement->get_result();
            $statement->close();

            $statement = $this->db->prepare("DELETE FROM kembali WHERE id_pinjam = ?");
            $statement->bind_param("i", $id_pinjam);
            $statement->execute();

            foreach($detail as $brg){
                $restock = $brg["stock"] - $brg["qty"];
                $statement = $this->db->prepare("UPDATE barang SET stock = ? WHERE kode_barang = ?");
                $statement->bind_param("ii", $restock, $brg["kode_barang"]);
                $statement->execute();
            }

            $statement = $this->db->prepare("UPDATE pinjam SET status = 1 WHERE id_pinjam = ?");
            $statement->bind_param("i", $id_pinjam);
            $statement->execute();

            if($this->db->commit() == true){
                $_SESSION["success"]="Pengembalian berhasil dibatalkan";
            }
            else{
                $_SESSION["fail"]="Pengembalian gagal dibatalkan";
            }

            $statement->close();
        }
        catch(Exception $e){
            $this->db->rollback();
            $_SESSION["fail"]="Proses gagal";
        }

        header("Location: detail_pinjam.php?id=".$id_pinjam);

    }

    public function ambil_barang($id_pinjam){

        $statement = $this->db->prepare("UPDATE pinjam SET status = 1 WHERE id_pinjam = ?");
        $statement->bind_param("i", $id_pinjam);
        $statement->execute();

        if($this->db->affected_rows > 0){
            $_SESSION["success"]="Barang sudah diambil";
        }
        else{
            $_SESSION["fail"]="Barang gagal diambil";
        }

        header("Location: detail_pinjam.php?id=".$id_pinjam);

    }

}
