<?php

session_start();
require_once "function.php";

$auth = new Auth;

if(isset($_POST["login"])){
    if($auth->login($_POST) != 1){
        header("Location: login.php");
    }
    else{
        header("Location: ../index.php");
    }
}
else if(isset($_POST["logout"])){
    $auth->logout();
}
else if(isset($_POST["register"])){
    $auth->register($_POST);
}