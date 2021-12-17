<?php

    $url = "connection.php";
    require_once "keuangan/function.php";

    $money = new Keuangan;

    $income = $money->currentMonth();
    $denda = $money->penalties();

    $menu = "Home";
    $title = "Dashboard";
    include_once "header.php";

    $month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

?>

    <div class="container text-center">
        <img src="img/undraw_Coding_re_iv62.svg" class="bg-secondary rounded" alt="admin" style="width: 300px; height: auto; margin-top: 120px;">
        <h5 style="margin-bottom: -70px;"><?= $_SESSION["login"] ?></h5>
        <h1 class="content text-center">Selamat datang di aplikasi Oreivasia</h1>
        <a href="pinjam/peminjaman.php" class="btn btn-primary mb-5">Periksa log hari ini <i class="fas fa-arrow-circle-right"></i></a>

        <div class="keuangan mb-5">
            <p><strong>Bulan <?=$month[date("m")-1]?></strong></p>
            <div class="row justify-content-center">
                <div class="col-md-2 col-sm-2 text-center">
                    Rp. <?= number_format($income["income"], 0, ",", ".")?>
                    <i class="fas fa-arrow-circle-down text-success"></i>    
                </div>
                <div class="col-md-2 col-sm-2 text-center">
                    Rp. <?= number_format($denda["denda"], 0, ",", ".")?>
                    <i class="fas fa-clock text-danger"></i>
                </div>
            </div>
        </div>

        <!-- footer -->
        <?php include_once "footer.php" ?>
    </div>
    

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    -->
  </body>
</html>