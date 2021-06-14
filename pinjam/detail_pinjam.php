<?php

    session_start();
    require_once "function.php";

    date_default_timezone_set('Asia/Jakarta');

    if(isset($_GET["id"])){
        // <object>
        $detail = new Pinjam;

        // <variabel>
        $id_pinjam = $_GET["id"];

        // ambil data customer
        $customer = $detail->detail_customer($id_pinjam);

        // ambil detail denda
        $kembali = $detail->denda($id_pinjam);

        // ambil data barang yang dipinjam
        $barang = $detail->detail_barang($id_pinjam);

        // ambil total pinjam
        $total = $detail->total($id_pinjam);
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

    <title>Detail Pinjam | Oreivasia</title>
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
                <h3 class="text-primary fw-bold">Detail Peminjaman</h3>
                <div class="row">
                    <div class="col-md-6">
                        <p><span class="fw-bold">Nama</span> <span style="margin-left: 43px;">: <?= $customer["nama"] ?></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><span class="fw-bold">Nik</span> <span style="margin-left: 62px;">: <?= $customer["nik"] ?></span></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p><span class="fw-bold">No. Hp</span> <span style="margin-left: 36px;">: <?= $customer["no_hp"] ?></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><span class="fw-bold">Alamat</span> <span style="margin-left: 33px;">: <?= $customer["alamat"] ?></span></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p><span class="fw-bold">Total</span> <span style="margin-left: 50px;">: Rp. <?= number_format($total["total"], 0, ",", ".") ?></span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><span class="fw-bold text-danger">Denda</span> <span style="margin-left: 38px;">
                        <?php 
                            if($kembali["denda"] > 0){
                                echo ": Rp. ".number_format($kembali["denda"], 0, ",", ".");
                            }
                            else{
                                echo ": -";
                            }
                        ?></span></p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <a class="btn btn-outline-primary mb-3" href="peminjaman.php">Kembali</a>

                <?php if(($customer["status"] == 0) && (date("Y-m-d") == $customer["tanggal_pinjam"]) && ($barang[1]["jumlah"] != 0)): ?>
                <a href="middleware.php?id=<?= $id_pinjam ?>&action=put" class="btn btn-outline-success mb-3">Ambil barang</a>
                <?php endif ?>

                <?php if(($kembali["tanggal_kembali"] == NULL) && ($customer["status"] == 1)): ?>
                <a href="middleware.php?id=<?= $id_pinjam ?>&action=restore" class="btn btn-outline-success mb-3">Kembalikan Barang</a>
                <?php endif ?>

                <?php if(($kembali["tanggal_kembali"] != NULL)): ?>
                <a href="#" class="btn btn-outline-danger mb-3" onclick="cancel(<?= $id_pinjam ?>)">Batalkan pengembalian</a>
                <?php endif ?>
                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Nama Barang</th>
                            <th>Harga (per hari)</th>
                            <th>Qty</th>
                            <th>Sub Total</th>
                            <th>Keterangan</th>
                            <?php if(date('Y-m-d') < $customer["tanggal_pinjam"]): ?>
                            <th>Aksi</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $nomor=1; foreach($barang[0] as $brg): ?>
                        <tr class="text-center">
                            <td><?= $nomor++ ?>.</td>
                            <td><?= $brg["nama_barang"] ?></td>
                            <td>Rp. <?= number_format($brg["harga"], 0, ",", ".")?></td>
                            <td><?= $brg["qty"] ?></td>
                            <td>Rp. <?= number_format($brg["harga"]*$brg["qty"]*$customer["durasi"], 0, ",", ".")?></td>
                            <td>
                                <?php 
                                    if($brg["keterangan"] == NULL) echo "-";
                                    else echo $brg["keterangan"];

                                ?>
                            </td>
                            <?php if((date('Y-m-d') < $customer["tanggal_pinjam"])): ?>
                            <td>
                                <a href="#" class="btn btn-outline-danger" onclick="hapus(<?= $id_pinjam ?>, <?= $brg['kode_barang'] ?>)">Hapus</a>
                            </td>
                            <?php endif ?>
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
    <script src="../node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <script>

        // <function, args, return, kotak dialog>
        // sweet alert cancel
        function cancel(id) {

            Swal.fire({
                title: 'Yakin untuk membatalkan?',
                text: "klik 'Ya' untuk melanjutkan",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = "middleware.php?id="+id+"&action=cancel";
                }
            });

        }

        // <function, args, return, kotak dialog>
        // sweet alert delete
        function hapus(id, kode_brg) {

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
                    window.location = "middleware.php?id="+id+"&brg="+kode_brg+"&action=destroy_barang";
                }
            });

        }
    </script>

    <!-- <kota dialog> -->
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