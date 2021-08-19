<?php

    require_once "function.php";
    $menu = "Barang";
    $title = "Barang";
    include_once "../header.php";

    // <object>
    $barang = new Barang;

    // <variabel>
    $data_barang = $barang->show();

?>

    <div class="container content">
        <div class="card rounded shadow-lg mb-5">
            <div class="card-header">
                <h3 class="text-primary fw-bold">Daftar Barang</h3>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Tambah</button>
                <!-- <a href="restock.php" class="btn btn-outline-success mb-3">Restock</a>
                <button type="button" class="btn btn-outline-danger mb-3">Barang rusak</button> -->
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



    <!-- Modal tambah barang -->
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
    <?php include_once "../footer.php" ?>



    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="../asset/datatables/jQuery-3.3.1/jquery-3.3.1.min.js"></script>
    <script src="../asset/datatables/DataTables-1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="../js/barang.js"></script>
    <script>
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

        // datatables structure
        $(document).ready(function() {
            $('#example').DataTable({
                "info": false,
                "lengthChange": false
            });
        });
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    -->
  </body>
</html>