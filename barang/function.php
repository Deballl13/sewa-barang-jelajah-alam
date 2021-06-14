<?php

require_once "../connection.php";

// <interface>
interface CRUD{

    public function store($data);
    public function destroy($kode_barang);
    public function update($data, $kode_barang);
    public function show();

}

// <class dan implements interface>
class Barang implements CRUD{

    // <properti>
    private $db;
    private $dbh;

    // <constructor>
    public function __construct(){
        
        // <koneksi database>
        $this->dbh = new Connection;
        $this->db = $this->dbh->getConn();

    }

    // <method/function>
    public function store($data){
        
        // <variabel, properti, dan manipulasi string>
        // atur format nama
        $arr_nama = explode(" ", htmlspecialchars(trim($data["nama_barang"])));
        $arr_nama_2 = [];
        foreach($arr_nama as $arr){
            array_push($arr_nama_2, ucwords(strtolower($arr)));
        }
        $nama = implode(" ", $arr_nama_2);

        $harga = $data["harga"];
        $stock = $data["stock"];

        // <operator perbandingan>
        // atur format keterangan
        $keterangan = NULL;
        if($data["keterangan"] != NULL){
            $arr_ket = explode(" ", htmlspecialchars(trim($data["keterangan"])));
            $arr_ket_2 = [];
            for($i=0; $i<count($arr_ket); $i++){
                if($i==0){
                    array_push($arr_ket_2, ucwords(strtolower($arr_ket[0])));
                }
                else{
                    array_push($arr_ket_2, strtolower($arr_ket[$i]));
                }
            }
            $keterangan = implode(" ", $arr_ket_2);
        }

        // <crud>
        try{
            $this->db->begin_transaction();
            if($keterangan == NULL){
                $statement = $this->db->prepare("SELECT * FROM barang WHERE nama_barang = ? AND keterangan IS NULL");
                $statement->bind_param("s", $nama);
                $statement->execute();
                $result = $statement->get_result();
                $statement->close();
            }
            else{
                $statement = $this->db->prepare("SELECT * FROM barang WHERE nama_barang = ? AND keterangan = ?");
                $statement->bind_param("ss", $nama, $keterangan);
                $statement->execute();
                $result = $statement->get_result();
                $statement->close();
            }

            if($result->num_rows > 0){
                $_SESSION["fail"]="Barang sudah terdaftar";
            } 
            else{
                if($keterangan == NULL){
                    $statement = $this->db->prepare("INSERT INTO barang VALUES('', ?, ?, ?, NULL)");
                    $statement->bind_param("sii", $nama, $harga, $stock);
                    $statement->execute();
                }
                else{
                    $statement = $this->db->prepare("INSERT INTO barang VALUES('', ?, ?, ?, ?)");
                    $statement->bind_param("siis", $nama, $harga, $stock, $keterangan);
                    $statement->execute();
                }

                if($this->db->commit() == true){
                    $_SESSION["success"]="Barang berhasil ditambahkan";
                }
                else{
                    $_SESSION["fail"]="Barang gagal ditambahkan";
                }
                $statement->close();
            }
        }
        catch(Exception $e){
            $this->db->rollback();
            $_SESSION["fail"]="Proses gagal";
        }

        header("Location: barang.php");

    }

    public function destroy($kode_barang){
        
        // <crud>
        $statement = $this->db->prepare("DELETE FROM barang WHERE kode_barang = ?");
        $statement->bind_param("i", $kode_barang);
        $statement->execute();

        if($this->db->affected_rows > 0){
            $_SESSION["success"]="Barang berhasil dihapus";
        }
        else{
            $_SESSION["fail"]="Barang gagal dihapus";
        }

        $statement->close();
        header("Location: barang.php");

    }

    public function getDataByKode($kode_barang){

        // <crud>
        $statement = $this->db->prepare("SELECT * FROM barang WHERE kode_barang = ?");
        $statement->bind_param("i", $kode_barang);
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();
        
        return $result->fetch_assoc();

    }

    public function update($data, $kode_barang){
        
        // <variabel, properti, manipulasi string>
        // atur format nama
        $arr_nama = explode(" ", htmlspecialchars(trim($data["nama_barang"])));
        $arr_nama_2 = [];
        foreach($arr_nama as $arr){
            array_push($arr_nama_2, ucwords(strtolower($arr)));
        }
        $nama = implode(" ", $arr_nama_2);

        $harga = $data["harga"];
        $stock = $data["stock"];

        // atur format keterangan
        $keterangan = NULL;
        // <operator perbandingan>
        if($data["keterangan"] != NULL){
            $arr_ket = explode(" ", htmlspecialchars(trim($data["keterangan"])));
            $arr_ket_2 = [];
            for($i=0; $i<count($arr_ket); $i++){
                if($i==0){
                    array_push($arr_ket_2, ucwords(strtolower($arr_ket[0])));
                }
                else{
                    array_push($arr_ket_2, strtolower($arr_ket[$i]));
                }
            }
            $keterangan = implode(" ", $arr_ket_2);
        }
        
        // <crud>
        try{
            $this->db->begin_transaction();
            if($keterangan == NULL){
                $statement = $this->db->prepare("SELECT * FROM barang WHERE nama_barang = ? AND keterangan IS NULL");
                $statement->bind_param("s", $nama);
                $statement->execute();
                $resultset = $statement->get_result();
                $statement->close();
            }
            else{
                $statement = $this->db->prepare("SELECT * FROM barang WHERE nama_barang = ? AND keterangan = ?");
                $statement->bind_param("ss", $nama, $keterangan);
                $statement->execute();
                $resultset = $statement->get_result();
                $statement->close();
            }

            $row = false;
            if($resultset->num_rows > 0){
                $result = $resultset->fetch_assoc();
                if($kode_barang != $result["kode_barang"]){
                    $row=true;
                    $_SESSION["fail"]="Data barang sudah terdaftar";
                    header("Location: barang.php");
                }
            }
            
            if($row == false){
                if($keterangan == NULL){
                    $statement = $this->db->prepare("UPDATE barang SET nama_barang=?, harga=?, stock=?, keterangan = NULL WHERE kode_barang=?");
                    $statement->bind_param("siii", $nama, $harga, $stock, $kode_barang);
                    $statement->execute();
                }
                else{
                    $statement = $this->db->prepare("UPDATE barang SET nama_barang=?, harga=?, stock=?, keterangan = ? WHERE kode_barang=?");
                    $statement->bind_param("siisi", $nama, $harga, $stock, $keterangan, $kode_barang);
                    $statement->execute();
                }

                if($this->db->commit() == true){
                    $_SESSION["success"]="Barang berhasil diupdate";
                }
                else{
                    $_SESSION["fail"]="Barang gagal diupdate";
                }
                $statement->close();
            }
        }
        catch(Exception $e){
            $this->db->rollback();
            $_SESSION["fail"]="Operasi gagal";
        }
        
        header("Location: barang.php");

    }

    public function show(){
        
        // <crud>
        $barang = $this->db->query("SELECT * FROM barang ORDER BY nama_barang ASC");
        return $barang;

    }

    public function list(){

        // <crud>
        $list = $this->db->query("SELECT * FROM barang WHERE stock > 0 ORDER BY nama_barang ASC");
        return $list;

    }

}
