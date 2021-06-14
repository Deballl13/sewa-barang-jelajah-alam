<?php

    session_start();
    require_once "function.php";

    // <object>
    $pinjam = new Pinjam;

    // <variabel>
    $peminjaman = $pinjam->show();

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

    <title>Pinjam | Oreivasia</title>
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
                        <a class="nav-link text-white link-active rounded" href="peminjaman.php">Pinjam</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../barang/barang.php">Barang</a>
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
                <h3 class="text-primary fw-bold">Daftar Peminjaman</h3>
            </div>
            <div class="card-body">
                <a class="btn btn-outline-primary mb-3" href="tambah_pinjam.php">Tambah</a>
                <table id="example" class="display" style="width:100%">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Nama</th>
                            <th>Durasi Pinjam</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $nomor=1; foreach($peminjaman as $pjm):?>
                        <tr class="text-center">
                            <td><?= $nomor++ ?>.</td>
                            <td class="nama"><?= $pjm["nama"] ?></td>
                            <td><?= $pjm["durasi"] ?> hari</td>
                            <td><?= date("d-m-Y", strtotime($pjm["tanggal_pinjam"])) ?></td>
                            <td>
                                <?php 
                                    if($pjm["tanggal_kembali"] != NULL){
                                        echo date("d-m-Y", strtotime($pjm["tanggal_kembali"])); 
                                    }
                                    else{
                                        echo "-";
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    if($pjm["status"] == 0){
                                        echo "Belum diambil";
                                    }
                                    elseif($pjm["status"] == 1){
                                        echo "Dipinjam"; 
                                    }
                                    elseif($pjm["status"] == 2){
                                        echo "Dikembalikan";
                                    }
                                    elseif($pjm["status"] == 3){
                                        echo "Terlambat";
                                    } 
                                    else{
                                        echo "Batal";
                                    }
                                ?>
                            </td>
                            <td>
                                <a href="detail_pinjam.php?id=<?= $pjm["id_pinjam"]
                                    ?>" class="btn btn-outline-primary">Detail</a>
                                <a class="btn btn-outline-danger <?php if(($pjm["tanggal_kembali"] == NULL) && ($pjm["jumlah"] > 0) && ($pjm["status"] != 4)): ?> disabled <?php endif ?>" href="#" onclick="hapus(<?= $pjm['id_pinjam'] ?>)">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
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