<?php

require_once "../connection.php";

class Auth{

    // <properti>
    private $db;
    private $dbh;

    // <constructor>
    public function __construct(){
        
        // <koneksi database>
        $this->dbh = new Connection;
        $this->db = $this->dbh->getConn();

    }

    public function login($data){

        $username = htmlspecialchars($data["username"]);
        $password = htmlspecialchars($data["password"]);

        $statement = $this->db->prepare("SELECT * FROM admin WHERE username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();
        $resultset = $statement->get_result();
        $statement->close();

        if($resultset->num_rows > 0){
            $result = $resultset->fetch_assoc();
            if(password_verify($password, $result["password"])){
                $_SESSION["login"] = $result["username"];
                return 1;
            }
            else{
                $_SESSION["fail"] = "Password anda salah";
                return 0;
            }
        }
        else{
            return 0;
            $_SESSION["fail"] = "Username tidak ditemukan";
        }

    }

    public function logout(){
        session_reset();
        session_unset();
        session_destroy();
        header("Location: login.php");
    }

    public function register($data){
        
        $username = htmlspecialchars($_POST["username"]);
        $password = password_hash(htmlspecialchars($_POST["password"]), PASSWORD_BCRYPT);

        try{
            $this->db->begin_transaction();

            $statement = $this->db->prepare("SELECT username FROM admin WHERE username = ?");
            $statement->bind_param("s", $username);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();

            if($result->num_rows > 0){
                $_SESSION["fail"] = "Username sudah terdaftar";
            }
            else{
                $statement = $this->db->prepare("INSERT INTO admin VALUES('', ?, ?)");
                $statement->bind_param("ss", $username, $password);
                $statement->execute();

                if($this->db->commit() == true){
                    $_SESSION["success"] = "Akun berhasil dibuat";
                }
                else{
                    $_SESSION["fail"] = "Akun gagal dibuat";
                }
            }

        }
        catch(Exception $e){
            $this->db->rollback();
            $_SESSION["fail"]="Proses gagal";
        }

        header("Location: register.php");

    }

    public function getCookie(){
        // $this->db->prepare();
    }

}