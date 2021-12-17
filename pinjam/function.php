<?php

require_once "../barang/function.php";
require_once "../connection.php";

class Pinjam implements CRUD{

    private $db;
    private $dbh;

    public function __construct(){
        
        $this->dbh = new Connection;
        $this->db = $this->dbh->getConn();

        date_default_timezone_set('Asia/Jakarta');

    }

    public function store($data){
        
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

        try{
            $this->db->begin_transaction();

            // cek data customer
            $statement = $this->db->prepare("SELECT * FROM customer WHERE nik = ?");
            $statement->bind_param("s", $nik);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();

            // jika belum terdaftar
            if($result->num_rows === 0){
                $statement = $this->db->prepare("INSERT INTO customer VALUES(?, ?, ?, ?)");
                $statement->bind_param("ssss", $nik, $nama, $no_hp, $alamat);
                $statement->execute();
                $statement->close();
            }

            // total pinjaman
            $total = 0;
            for($i=0;$i<count($kode_barang);$i++){
                $statement = $this->db->prepare("SELECT harga FROM barang WHERE kode_barang = ?");
                $statement->bind_param("i", $kode_barang[$i]);
                $statement->execute();
                $resultset = $statement->get_result();
                $result = $resultset->fetch_assoc();

                $total += ($result["harga"]*$durasi*(int)$qty[$i]);
            }

            // insert data ke tabel pinjam
            $statement = $this->db->prepare("INSERT INTO pinjam VALUES('', ?, ?, NULL, ?, ?, 0)");
            $statement->bind_param("ssii", $nik, $tanggal_pinjam, $durasi, $total);
            $statement->execute();
            $id_pinjam = $statement->insert_id;

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

            if($this->db->commit() === true){
                $_SESSION["success"]="Data peminjaman berhasil ditambahkan";
            }
            else{
                $_SESSION["fail"]="Data peminjaman gagal ditambahkan";
            }

            $statement->close();
        }
        catch(Exception $e){
            $this->db->rollback();
            $_SESSION["fail"]="Proses gagal";
        }

        header("Location: detail_pinjam.php?id=".$id_pinjam);

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

    public function update($data, $kode_barang){
        
    }

    public function show(){

        try{
            $this->db->begin_transaction();
            
            // update otomatis jika barang belum diambil setelah lewat tanggal peminjaman
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
            
            $daftar_pinjam = $this->db->query("SELECT customer.nama, pinjam.* FROM customer INNER JOIN pinjam ON customer.nik = pinjam.nik INNER JOIN detail_pinjam ON pinjam.id_pinjam = detail_pinjam.id_pinjam GROUP BY pinjam. id_pinjam ORDER BY pinjam.tanggal_pinjam DESC, pinjam.durasi ASC");
            
            $this->db->commit();
            return $daftar_pinjam;
        }
        catch(Exception $e){
            $this->db->rollback();
            $_SESSION["fail"]="Proses gagal";
        }

    }

    public function detail_pinjam($id_pinjam){
        
        $statement = $this->db->prepare("SELECT *, DATE_ADD(tanggal_pinjam, INTERVAL durasi-1 DAY) AS estimasi_tanggal_kembali FROM customer INNER JOIN pinjam ON customer.nik = pinjam.nik WHERE id_pinjam = ?");
        $statement->bind_param("s", $id_pinjam);
        $statement->execute();
        return $statement->get_result()->fetch_assoc();

    }

    public function detail_pembayaran($id_pinjam){

        $statement = $this->db->prepare("SELECT id_transaksi, SUM(nominal) AS nominal FROM pembayaran WHERE id_pinjam = ? AND keterangan NOT IN ('Denda')");
        $statement->bind_param("i", $id_pinjam);
        $statement->execute();
        return $statement->get_result()->fetch_assoc();

    }

    public function detail_denda($id_pinjam){

        $statement = $this->db->prepare("SELECT nominal FROM pembayaran WHERE id_pinjam = ? AND keterangan = 'Denda'");
        $statement->bind_param("i", $id_pinjam);
        $statement->execute();
        return $statement->get_result()->fetch_assoc();

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
        array_push($result, $statement->get_result()->fetch_assoc());
        $statement->close();

        return $result;

    }

    public function restore($id_pinjam){

        try{
            // ambil data barang yang dipinjam
            $this->db->begin_transaction();
            $statement = $this->db->prepare("SELECT DATE_ADD(tanggal_pinjam, INTERVAL durasi-1 DAY) AS tanggal_kembali, durasi, total FROM pinjam WHERE id_pinjam = ?");
            $statement->bind_param("i", $id_pinjam);
            $statement->execute();
            $estimasi_pengembalian = $statement->get_result()->fetch_assoc();

            // ambil detail barang yang dipinjam
            $statement = $this->db->prepare("SELECT barang.*, detail_pinjam.qty FROM barang INNER JOIN detail_pinjam ON barang.kode_barang = detail_pinjam.kode_barang WHERE id_pinjam = ?");
            $statement->bind_param("i", $id_pinjam);
            $statement->execute();
            $detail = $statement->get_result();

            // hitung sisa pembayaran yang kurang
            $statement = $this->db->prepare("SELECT nominal FROM pembayaran WHERE id_pinjam = ?");
            $statement->bind_param("i", $id_pinjam);
            $statement->execute();
            $bayar = $statement->get_result()->fetch_assoc();
            $lunas = $estimasi_pengembalian["total"]-$bayar["nominal"];

            // hitung jumlah hari telat
            $today = new DateTime();
            $tgl_kembali = new DateTime($estimasi_pengembalian["tanggal_kembali"]);
            $telat = $today->diff($tgl_kembali)->d;
            $now = $today->format("Y-m-d");

            // jika telat
            if($telat > 0 && $tgl_kembali->format("Y-m-d") < $now){
                $denda = 0;
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

                // tambahkan tanggal kembali
                $statement = $this->db->prepare("UPDATE pinjam SET tanggal_kembali = ? WHERE id_pinjam = ?");
                $statement->bind_param("si", $now, $id_pinjam);
                $statement->execute();

                // pelunasan pembayaran
                $statement = $this->db->prepare("INSERT INTO pembayaran VALUES('', ?, ?, ?, 'Sisa Pembayaran')");
                $statement->bind_param("isi", $id_pinjam, $now, $lunas);
                $statement->execute();

                // pembayaran denda
                $statement = $this->db->prepare("INSERT INTO pembayaran VALUES('', ?, ?, ?, 'Denda')");
                $statement->bind_param("isi", $id_pinjam, $now, $denda);
                $statement->execute();

                // update status peminjaman
                $statement = $this->db->prepare("UPDATE pinjam SET status = 3 WHERE id_pinjam = ?");
                $statement->bind_param("i", $id_pinjam);
                $statement->execute();
            }
            // jika tidak telat
            else{
                // tambahkan tanggal kembali
                $statement = $this->db->prepare("UPDATE pinjam SET tanggal_kembali = ? WHERE id_pinjam = ?");
                $statement->bind_param("si", $now, $id_pinjam);
                $statement->execute();

                // pelunasan pembayaran
                $statement = $this->db->prepare("INSERT INTO pembayaran VALUES('', ?, ?, ?, 'Sisa Pembayaran')");
                $statement->bind_param("isi", $id_pinjam, $now, $lunas);
                $statement->execute();

                // update status peminjaman
                $statement = $this->db->prepare("UPDATE pinjam SET status = 2 WHERE id_pinjam = ?");
                $statement->bind_param("i", $id_pinjam);
                $statement->execute();
            }

            // update stock barang
            foreach($detail as $brg){
                $restock = $brg["stock"] + $brg["qty"];
                $statement = $this->db->prepare("UPDATE barang SET stock = ? WHERE kode_barang = ?");
                $statement->bind_param("ii", $restock, $brg["kode_barang"]);
                $statement->execute();
            }
            
            if($this->db->commit() === true){
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
            $this->db->begin_transaction();
            $statement = $this->db->prepare("UPDATE pinjam SET tanggal_kembali = NULL WHERE id_pinjam = ?");
            $statement->bind_param("i", $id_pinjam);
            $statement->execute();  

            // ambil detail barang yang dipinjam
            $statement = $this->db->prepare("SELECT barang.*, detail_pinjam.qty FROM barang INNER JOIN detail_pinjam ON barang.kode_barang = detail_pinjam.kode_barang WHERE id_pinjam = ?");
            $statement->bind_param("i", $id_pinjam);
            $statement->execute();
            $detail = $statement->get_result();

            // mengurangi stock barang
            foreach($detail as $brg){
                $restock = $brg["stock"] - $brg["qty"];
                $statement = $this->db->prepare("UPDATE barang SET stock = ? WHERE kode_barang = ?");
                $statement->bind_param("ii", $restock, $brg["kode_barang"]);
                $statement->execute();
            }

            // menghapus transaksi pelunasan dan denda
            $statement = $this->db->prepare("DELETE FROM pembayaran WHERE id_pinjam = ? AND keterangan NOT IN ('Pembayaran DP')");
            $statement->bind_param("i", $id_pinjam);
            $statement->execute();

            // mengupdate status peminjaman
            $statement = $this->db->prepare("UPDATE pinjam SET status = 1 WHERE id_pinjam = ?");
            $statement->bind_param("i", $id_pinjam);
            $statement->execute();

            if($this->db->commit() === true){
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

    public function bayarDP($id_pinjam, $data){

        $nominal = (int)htmlspecialchars(trim($data["bayarDP"]));
        $today = date("Y-m-d");

        $statement = $this->db->prepare("INSERT INTO pembayaran VALUES('', ?, ?, ?, 'Pembayaran DP')");
        $statement->bind_param("isi", $id_pinjam, $today, $nominal);
        $statement->execute();

        if($this->db->affected_rows > 0){
            $_SESSION["success"]="Pembayaran DP berhasil";
        }
        else{
            $_SESSION["fail"]="Pembayaran DP gagal";
        }

        header("Location: detail_pinjam.php?id=".$id_pinjam);

    }

    public function perpanjang_pinjam($id_pinjam, $data){

        $durasi = (int)htmlspecialchars(trim($data["durasi"]));
        
        try {
            $this->db->begin_transaction();

            // ambil data durasi sebelumnya
            $statement = $this->db->prepare("SELECT durasi FROM pinjam WHERE id_pinjam = ?");
            $statement->bind_param("i", $id_pinjam);
            $statement->execute();
            $old_durasi = $statement->get_result()->fetch_assoc()["durasi"];
            $new_durasi = $old_durasi+$durasi;

            // tambahkan durasi
            $statement = $this->db->prepare("UPDATE pinjam SET durasi = ? WHERE id_pinjam = ?");
            $statement->bind_param("ii", $new_durasi, $id_pinjam);
            $statement->execute();

            if($this->db->commit() === true){
                $_SESSION["success"]="Durasi berhasil ditambah";
            }
            else{
                $_SESSION["fail"]="Durasi gagal ditambah";
            }

            $statement->close();
            $new_durasi->delete;
        } 
        catch(Exception $e){
            $this->db->rollback();
            $_SESSION["fail"]="Proses gagal";
        }

        header("Location: detail_pinjam.php?id=".$id_pinjam);

    }

}
