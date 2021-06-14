<?php

    require_once "../barang/function.php";

    // <object>
    $barang = new Barang;

    // <variabel>
    $list_barang = $barang->list();

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
    <link rel="stylesheet" href="../asset/datepicker/css/jquery-ui.css">
    <link rel="stylesheet" href="../css/style.css">

    <title>Tambah Pinjam | Oreivasia</title>
  </head>
  <body class="position-relative">
    
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
            <form onsubmit="return validation02()" id="storePinjam" action="middleware.php?action=store" method="post">
                <div class="card-header">
                    <h3 class="text-primary fw-bold">Tambah Peminjaman</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="nik" class="form-label">NIK<sup class="text-danger">*</sup></label>
                            <input type="text" maxlength="16" name="nik" id="nik" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <=57">
                            <p class="invalid-feedback"><!--message--></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nama" class="form-label">Nama<sup class="text-danger">*</sup></label>
                            <input type="text" name="nama" id="nama" class="form-control" maxlength="30">
                            <p class="invalid-feedback"><!--message--></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="no_hp" class="form-label">No. Hp<sup class="text-danger">*</sup></label>
                            <input type="text" name="no_hp" id="no_hp" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <=57" maxlength="13">
                            <p class="invalid-feedback"><!--message--></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="alamat" class="form-label">Alamat<sup class="text-danger">*</sup></label>
                            <textarea class="form-control" name="alamat" id="alamat" cols="30" style="resize: none;"></textarea>
                            <p class="invalid-feedback"><!--message--></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="datepicker" class="form-label">Tanggal Pinjam<sup class="text-danger">*</sup></label>
                            <input type="text" name="tanggal_pinjam" id="datepicker" class="form-control" readonly>
                            <p class="invalid-feedback"><!--message--></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="durasi" class="form-label">Lama Peminjaman<sup class="text-danger">*</sup></label>
                            <input type="text" maxlength="2" name="durasi" id="durasi" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <=57">
                            <p class="invalid-feedback"><!--message--></p>
                        </div>
                    </div>
                    <a class="btn btn-outline-primary" href="peminjaman.php">Kembali</a>
                    <button type="button" class="btn btn-outline-success" data-bs-target="#staticBackdrop" onmousedown="showModal(1)">Lanjut</button>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold" id="staticBackdropLabel">Daftar Barang</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="showModal(-1)"></button>
                            </div>
                            <div class="modal-body">
                                <!-- <div class="alert alert-danger d-flex align-items-center px-auto d-none" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                    <div class="invalid-feedback">message</div>
                                </div> -->
                                <table id="example" class="display" style="width:100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th>#</th>
                                            <th>Nama Barang</th>
                                            <th>Stock</th>
                                            <th>Keterangan</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($list_barang as $brg): ?>
                                        <tr class="text-center">
                                            <td><input class="form-check-input" type="checkbox" name="kode_barang[]" id="kode_barang" value="<?= $brg["kode_barang"] ?>"></td>
                                            <td><?= $brg["nama_barang"] ?></td>
                                            <td><?= $brg["stock"] ?></td>
                                            <td>
                                                <?php 
                                                    if($brg["keterangan"] == NULL) echo "-";
                                                    else echo $brg["keterangan"];
                                                ?>
                                            </td>
                                            <td><input class="form-control" type="text" name="qty[]" id="qty" stock="<?= $brg["stock"] ?>" nama_barang="<?= $brg["nama_barang"] ?>" disabled onkeypress="return event.charCode >= 48 && event.charCode <=57" maxlength="2"></td>
                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-outline-success">Tambah</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- toast -->
                <!-- <div aria-live="polite" aria-atomic="true" class="bg-dark">
                    <div class="toast-container position-absolute top-0 end-0 p-3" id="toastPlacement">
                        <div class="toast text-white bg-danger border-0">
                            <div class="toast-body invalid-feedback">message</div>
                        </div>
                    </div>
                </div> -->
            </form>
        </div>
    </div>

    <div class="bg-danger text-white px-3 py-1 rounded toast-view position-absolute top-0 end-0 mx-3">message</div>

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
    <script src="../js/pinjam.js"></script>

    <script>
        // <manipulasi object>
        // datatables structure
        $(document).ready(function() {
            $('#example').DataTable({
                "iDisplayLength": 5,
                info: false,
                paging: false,
                lengthChange: false
            });
        });
    </script>

    <script src="../asset/datepicker/js/moment.js"></script>
    <script src="../asset/datepicker/js/jquery-ui.js"></script>
    <script>
        // <function, manipulasi object>
        $( function() {
            $( "#datepicker" ).datepicker({
                dateFormat: "dd-mm-yy",
                minDate: moment().add('d', 1).toDate(),
                maxDate: moment().add('d', 5).toDate(),
            });
        } );
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    -->
  </body>
</html>