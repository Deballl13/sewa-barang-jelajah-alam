<?php

    session_start();
    require_once "function.php";

    // <object>
    $pinjam = new Pinjam;

    if(isset($_GET["action"])){
        if(isset($_POST) && $_GET["action"]=="store"){
            $pinjam->store($_POST);
        }
        elseif(isset($_GET["id"])){
            if($_GET["action"] == "restore"){
                $pinjam->restore($_GET["id"]);
            }
            elseif(isset($_GET["brg"]) && $_GET["action"] == "destroy_barang"){
                $pinjam->destroy_barang($_GET["id"], $_GET["brg"]);
            }
            elseif($_GET["action"] == "destroy"){
                $pinjam->destroy($_GET["id"]);
            }
            elseif($_GET["action"] == "cancel"){
                $pinjam->cancel($_GET["id"]);
            }
            elseif($_GET["action"] == "put"){
                $pinjam->ambil_barang($_GET["id"]);
            }
        }
    }
