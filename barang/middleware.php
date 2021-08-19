<?php

    session_start();
    require_once "function.php";

    // <object>
    $barang = new Barang;

    if(isset($_GET["action"])){
        if(isset($_POST) && $_GET["action"] === "store"){
            $barang->store($_POST);
        }
        elseif(isset($_GET["id"])){
            if($_GET["action"] === "destroy"){
                $barang->destroy($_GET["id"]);
            }
            elseif($_GET["action"] === "update"){
                $barang->update($_POST, $_GET["id"]);
            }
        }
    }