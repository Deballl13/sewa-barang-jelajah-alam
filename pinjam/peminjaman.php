<?php

    require_once "function.php";
    $menu = "Pinjam";
    $title = "Pinjam";
    include_once "../header.php";

    // <object>
    $pinjam = new Pinjam;

    // <variabel>
    $peminjaman = $pinjam->show();

?>


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
                                <a class="btn btn-outline-danger <?php if(($pjm["tanggal_kembali"] == NULL)): ?> disabled <?php endif ?>" href="#" onclick="hapus(<?= $pjm['id_pinjam'] ?>)">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php include_once "../footer.php" ?>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="../asset/datatables/jQuery-3.3.1/jquery-3.3.1.min.js"></script>
    <script src="../asset/datatables/DataTables-1.10.25/js/jquery.dataTables.min.js"></script>
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
                "info": false
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