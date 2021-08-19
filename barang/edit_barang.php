<?php

    require_once "function.php";
    $menu = "Barang";
    $title = "Edit Barang";
    include_once "../header.php";

    // <object>
    $barang = new Barang;

    if(isset($_GET["id"])){
        // <variabel>
        $kode_barang = $_GET["id"];
        $brg = $barang->getDataByKode($kode_barang);
    }

?>


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
                            <input type="text" name="stock" id="stock" class="form-control" disabled value="<?= $brg["stock"] ?>" onkeypress="return event.charCode >= 48 && event.charCode <=57">
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
    <?php include_once "../footer.php" ?>



    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="../js/barang.js"></script>

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