<?php

    session_start();
    require_once "function.php";

    // <object>
    $barang = new Barang;

    // <variabel>
    $data_barang = $barang->show();

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="../asset/datatables/DataTables-1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <title>Barang | Oreivasia</title>
  </head>
  <body>
    
    <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-primary">
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
        <div class="card rounded shadow-lg mb-5">
            <div class="card-header">
                <h3 class="text-primary fw-bold">Daftar Barang</h3>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Tambah</button>
                <table id="example" class="display" style="width:100%">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Nama Barang</th>
                            <th>Harga Sewa (per hari)</th>
                            <th>Stock</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $nomor=1; foreach($data_barang as $brg):?>
                        <tr class="text-center">
                            <td><?= $nomor++ ?>.</td>
                            <td><?= $brg["nama_barang"] ?></td>
                            <td>Rp. <?= number_format($brg["harga"], 0, ",", ".") ?></td>
                            <td><?= $brg["stock"] ?></td>
                            <td>
                                <?php 
                                    if($brg["keterangan"] == NULL) echo "-";
                                    else echo $brg["keterangan"];

                                ?>
                            </td>
                            <td>
                                <a class="btn btn-outline-warning" href="edit_barang.php?id=<?= $brg["kode_barang"] ?>">Ubah</a>
                                <a href="#" class="btn btn-outline-danger" onclick="hapus(<?= $brg['kode_barang'] ?>)">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="staticBackdropLabel">Tambah data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="barang" action="middleware.php?action=store" method="post" onsubmit="return validation()">
                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang<sup class="text-danger">*</sup></label>
                            <input type="text" name="nama_barang" id="nama_barang" class="form-control" maxlength="30">
                            <p class="invalid-feedback"><!--message--></p>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga Barang<sup class="text-danger">*</sup></label>
                            <input type="text" name="harga" id="harga" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <=57">
                            <p class="invalid-feedback"><!--message--></p>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock Barang<sup class="text-danger">*</sup></label>
                            <input type="text" name="stock" id="stock" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <=57">
                            <p class="invalid-feedback"><!--message--></p>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" name="keterangan" id="keterangan" cols="30" style="resize: none;"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-outline-danger">Hapus</button>
                    <button type="submit" name="store" class="btn btn-outline-success">Tambah</button>
                    </form>
                </div>
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
    <script src="../asset/datatables/jQuery-3.3.1/jquery-3.3.1.min.js"></script>
    <script src="../asset/datatables/DataTables-1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="../node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="../js/barang.js"></script>
    <script>
    
        // <function, args, return, kotak dialog>
        // sweet alert delete
        function hapus(id) {

            Swal.fire({
                title: 'Yakin untuk menghapus?',
                text: "klik 'Ya' untuk melanjutkan",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = "middleware.php?id="+id+"&action=destroy";
                }
            });

        }

        // <manipulasi object>
        // datatables structure
        $(document).ready(function() {
            $('#example').DataTable({
                "iDisplayLength": 5,
                info: false,
                lengthChange: false
            });
        });
    </script>

    <!-- <kotak dialog> -->
    <!-- sweet alert success -->
    <?php if(isset($_SESSION["success"])): ?>
    <script>
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: '<?php echo $_SESSION["success"] ?>',
            showConfirmButton: false,
            timer: 1500
        })
    </script>
    <?php session_unset(); endif; ?>

    <!-- sweet alert fail -->
    <?php if(isset($_SESSION["fail"])): ?> 
    <script>
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Oops...',
            text: '<?php echo $_SESSION["fail"] ?>',
            showConfirmButton: false,
            timer: 1500
        })
    </script>
    <?php session_unset(); endif; ?>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    -->
  </body>
</html>