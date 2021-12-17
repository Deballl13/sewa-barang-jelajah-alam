<?php

session_start();
require_once "connection.php";

if(!isset($_SESSION["login"])){
    header("Location: /prakweb/tb/auth/login.php");
}

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="/prakweb/tb/asset/datatables/DataTables-1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="/prakweb/tb/asset/datepicker/css/jquery-ui.css">
    <link rel="stylesheet" href="/prakweb/tb/node_modules/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="/prakweb/tb/font/fontawesome/css/all.css">
    <link rel="stylesheet" href="/prakweb/tb/css/style.css">

    <title><?= $title ?> | Oreivasia</title>
  </head>
  <body>
    
    <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-primary">
        <div class="container-fluid container">
            <a class="navbar-brand text-white brand-navbar" href="/prakweb/tb/index.php">Oreivasia</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-white <?php if($menu=="Home"): ?> link-active <?php endif ?> rounded" aria-current="page" href="/prakweb/tb/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white <?php if($menu=="Pinjam"): ?> link-active <?php endif ?>" href="/prakweb/tb/pinjam/peminjaman.php">Pinjam</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white <?php if($menu=="Barang"): ?> link-active <?php endif ?>" href="/prakweb/tb/barang/barang.php">Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white <?php if($menu=="Restock"): ?> link-active <?php endif ?>" href="/prakweb/tb/barang/restock.php">Restock</a>
                    </li>
                </ul>
                <div class="row">
                    <form action="/prakweb/tb/auth/middleware.php" method="POST" class="d-flex">
                        <button class="btn btn-outline-dark col-sm-12 col-12" type="submit" name="logout">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>