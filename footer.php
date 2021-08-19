<div class="footer">
    <p class="text-center">©build by Ade Iqbal</p>
    <p class="text-center">@2021</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
<script src="../node_modules/sweetalert2/dist/sweetalert2.min.js"></script>

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
<?php unset($_SESSION["success"]); endif; ?>

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
<?php unset($_SESSION["fail"]); endif; ?>