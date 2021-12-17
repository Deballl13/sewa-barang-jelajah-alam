<?php

    require_once "function.php";
    $menu = "Pinjam";
    $title = "Detail Pinjam";
    include_once "../header.php";

    date_default_timezone_set('Asia/Jakarta');

    if(isset($_GET["id"])){
        $detail = new Pinjam;

        $id_pinjam = $_GET["id"];

        // ambil data customer
        $customer_pinjam = $detail->detail_pinjam($id_pinjam);

        // ambil detail denda
        $denda = $detail->detail_denda($id_pinjam);

        // ambil data barang yang dipinjam
        $barang = $detail->detail_barang($id_pinjam);

        // ambil data jumlah pembayaran
        $bayar = $detail->detail_pembayaran($id_pinjam);
    }

    if($denda["nominal"] === NULL) $denda["nominal"]=0;

?>


    <div class="container content">
        <div class="card rounded shadow-lg mb-5">
            <div class="card-header">
                <h3 class="text-primary fw-bold">Detail Peminjaman</h3>
                <div class="row">
                    <div class="col-md-6">
                        <p><span class="fw-bold">Nama</span> <span style="margin-left: 43px;">: <?= $customer_pinjam["nama"] ?></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><span class="fw-bold">Nik</span> <span style="margin-left: 62px;">: <?= $customer_pinjam["nik"] ?></span></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p><span class="fw-bold">No. Hp</span> <span style="margin-left: 36px;">: <?= $customer_pinjam["no_hp"] ?></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><span class="fw-bold">Alamat</span> <span style="margin-left: 33px;">: <?= $customer_pinjam["alamat"] ?></span></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p><span class="fw-bold text-danger">Denda</span> <span style="margin-left: 38px;">
                        <?= $denda !== NULL ? ": Rp. ".number_format($denda["nominal"], 0, ",", ".") : ": Rp. 0"; ?></span></p>
                    </div>
                    <?php if(($bayar["nominal"] === NULL)): ?>
                    <div class="col-md-2">
                        <div class="alert alert-danger text-center" role="alert">
                        <strong>Belum bayar DP</strong>
                        </div>
                    </div>
                    <?php endif ?>
                </div>
            </div>
            <div class="card-body">
                <a class="btn btn-outline-primary mb-3" href="peminjaman.php">Kembali</a>

                <!-- tombol ambil barang -->
                <?php if(($customer_pinjam["status"] === 0) && (date("Y-m-d") === $customer_pinjam["tanggal_pinjam"]) && ($bayar["nominal"] > 0)): ?>
                <a href="middleware.php?id=<?= $id_pinjam ?>&action=put" class="btn btn-outline-success mb-3">
                    Ambil barang
                </a>
                <?php endif ?>

                <!-- tombol kembalikan barang -->
                <?php if(($customer_pinjam["status"] === 1)): ?>
                <a href="middleware.php?id=<?= $id_pinjam ?>&action=restore" class="btn btn-outline-success mb-3">
                    Kembalikan Barang
                </a>
                <?php endif ?>

                <!-- tombol batalkan pengembalian barang -->
                <?php if(($customer_pinjam["tanggal_kembali"] !== NULL)): ?>
                <a href="#" class="btn btn-outline-danger mb-3" onclick="cancel(<?= $id_pinjam ?>)">
                    Batalkan pengembalian
                </a>
                <?php endif ?>
                
                <!-- tombol memperpanjang peminjaman -->
                <?php if(($customer_pinjam["status"] === 1) && (date("Y-m-d") === $customer_pinjam["estimasi_tanggal_kembali"])): ?>
                <button class="btn btn-success mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePerpanjangPinjam" aria-expanded="false" aria-controls="collapsePerpanjangPinjam">
                    Perpanjang
                </button>
                <?php endif ?>

                <!-- form perpanjang peminjaman -->
                <div class="collapse" id="collapsePerpanjangPinjam">
                    <form action="middleware.php?id=<?= $id_pinjam ?>&action=perpanjang" method="post" id="add_duration" onsubmit="return add_duration_validation()">
                        <div class="col-md-2 col-sm-2"> 
                            <label for="durasi" class="form-label">Durasi Tambahan (hari)<sup class="text-danger">*</sup></label>
                            <input type="text" maxlength="2" name="durasi" id="durasi" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <=57">
                            <p class="invalid-feedback"><!--message--></p>
                        </div>
                        <button type="submit" class="btn btn-outline-success mt-3">Tambah</button>
                    </form>
                </div>

                <!-- tombol pembayaran dp -->
                <?php if(($customer_pinjam["status"] === 0) && (date("Y-m-d") <= $customer_pinjam["tanggal_pinjam"]) && ($bayar["nominal"] === NULL)): ?>
                <button class="btn btn-success mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePembayaran" aria-expanded="false" aria-controls="collapsePembayaran">
                    Bayar DP
                </button>
                <?php endif ?>

                <!-- form pembayaran dp -->
                <div class="collapse" id="collapsePembayaran">
                    <form action="middleware.php?id=<?= $id_pinjam ?>&action=pembayaranDP" method="post" id="pembayaranDP" onsubmit="return payment_validation(<?= $customer_pinjam['total'] ?>)">
                        <div class="col-md-2 col-sm-2"> 
                            <label for="bayarDP" class="form-label">Nominal<sup class="text-danger">*</sup></label>
                            <input type="text" name="bayarDP" id="bayarDP" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <=57">
                            <p class="invalid-feedback"><!--message--></p>
                        </div>
                        <button type="submit" class="btn btn-outline-success mt-3">Bayar</button>
                    </form>
                </div>

                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Nama Barang</th>
                            <th>Harga (per hari)</th>
                            <th>Qty</th>
                            <th>Sub Total</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $nomor=1; foreach($barang[0] as $brg): ?>
                        <tr class="text-center">
                            <td><?= $nomor++ ?>.</td>
                            <td><?= $brg["nama_barang"] ?></td>
                            <td>Rp. <?= number_format($brg["harga"], 0, ",", ".")?></td>
                            <td><?= $brg["qty"] ?></td>
                            <td>Rp. <?= number_format($brg["harga"]*$brg["qty"]*$customer_pinjam["durasi"], 0, ",", ".")?></td>
                            <td>
                                <?php 
                                    if($brg["keterangan"] == NULL) echo "-";
                                    else echo $brg["keterangan"];

                                ?>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>

                <div id="payment" class="px-5 float-end">
                    <p class="px-md-5"><strong>Total <span style="margin-left: 7rem;">: Rp. <?= number_format($customer_pinjam["total"], 0, ",", ".") ?></span></strong></p>
                    <p class="px-md-5"><strong>Pembayaran <span style="margin-left: 3.6rem;">: Rp. <?= number_format($bayar["nominal"], 0, ",", ".") ?></span></strong></p>

                    <?php if($denda["nominal"] !== 0): ?>
                    <p class="px-md-5"><strong>Denda <span style="margin-left: 6.2rem;">: Rp. <?= number_format($denda["nominal"], 0, ",", ".") ?></span></strong></p>
                    <?php endif ?>

                    <p class="px-md-5"><strong>Sisa <span style="margin-left: 7.3rem;">: Rp. <?= number_format($customer_pinjam["total"]-$bayar["nominal"], 0, ",", ".") ?></span></strong></p>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php include_once "../footer.php" ?>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script>
        // sweet alert cancel
        function cancel(id_pinjam) {

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
                    window.location = `middleware.php?id=${id_pinjam}&action=cancel`;
                }
            });

        }
    </script>
    
    <script src="../js/pembayaran.js"></script>
    <script src="../js/perpanjangan.js"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    -->
  </body>
</html>