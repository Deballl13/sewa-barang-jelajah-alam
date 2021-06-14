<?php

    require_once "function.php";

    // <object>
    $barang = new Barang;

    if(isset($_GET["id"])){
        // <variabel>
        $kode_barang = $_GET["id"];
        $brg = $barang->getDataByKode($kode_barang);
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
    <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <title>Edit Barang | Oreivasia</title>
  </head>
  <body>
    
    <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-primary nav">
        <div class="container-fluid container">
            <a class="navbar-brand text-white brand-navbar" href="../index.php">Oreivasia</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-white" aria-current="page" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../pinjam/peminjaman.php">Pinjam</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white link-active rounded" href="barang.php">Barang</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <button class="btn btn-outline-dark" type="submit" name="logout">Logout</button>
                </form>
            </div>
        </div>
    </nav>


    <div class="container content">
        <div class="card rounded shadow-lg">
            <div class="card-header">
                <h3 class="text-primary fw-bold">Edit Barang</h3>
            </div>
            <div class="card-body">
                <form id="barang" action="middleware.php?id=<?= $brg["kode_barang"] ?>&action=update" method="post" onsubmit="return validation('update')">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang<sup class="text-danger">*</sup></label>
                            <input type="text" name="nama_barang" id="nama_barang" class="form-control" value="<?= $brg["nama_barang"] ?>" maxlength="30">
                            <p class="invalid-feedback"><!--message--></p>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="harga" class="form-label">Harga Barang<sup class="text-danger">*</sup></label>
                            <input type="text" name="harga" id="harga" class="form-control" value="<?= $brg["harga"] ?>" onkeypress="return event.charCode >= 48 && event.charCode <=57">
                            <p class="invalid-feedback"><!--message--></p>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="stock" class="form-label">Stock Barang<sup class="text-danger">*</sup></label>
                            <input type="text" name="stock" id="stock" class="form-control" value="<?= $brg["stock"] ?>" onkeypress="return event.charCode >= 48 && event.charCode <=57">
                            <p class="invalid-feedback"><!--message--></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" name="keterangan" id="keterangan" cols="30" style="resize: none;"><?= $brg["keterangan"] ?></textarea>
                        </div>
                    </div>
                    <a class="btn btn-outline-primary" href="barang.php">Kembali</a>
                    <button class="btn btn-outline-success" name="update" type="submit">Ubah</button>
                </form>
            </div>
        </div>
    </div>

    <!-- footer -->
    <div class="footer">
        <p class="text-center">©develop by Ade Iqbal</p>
        <p class="text-center">@2021</p>
    </div>
    <!-- akhir footer -->



    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <!-- sweet alert javascript -->
    <script src="../node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="../js/barang.js"></script>

    <!-- kotak dialog -->
    <?php if(!isset($_GET["id"])) : ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
                showConfirmButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: '<a href="barang.php" style="text-decoration:none" class="text-white">Back</>'
            })
        </script>
    <?php endif ?>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    -->
  </body>
</html>