<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css'); ?>">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css'); ?>">
</head>
<body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-logo">
            <a href="<?= base_url('index2.html'); ?>"><b>Register</b>POLI</a>
        </div>

        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg">Register a new membership</p>

                <!-- Menampilkan error jika ada -->
                <?php if($this->session->flashdata('error')): ?>
                    <p style="color: red;"><?php echo $this->session->flashdata('error'); ?></p>
                <?php endif; ?>

                <form id="formTambahPasien" onsubmit="event.preventDefault();">
    <div class="input-group mb-3">
        <input type="text" class="form-control" id="nama" placeholder="Masukkan Nama" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-user"></span>
            </div>
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="text" class="form-control" id="alamat" placeholder="Masukkan Alamat" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="password" class="form-control" id="password" placeholder="Masukkan Password">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="text" class="form-control" id="no_ktp" placeholder="Masukkan No. KTP" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-id-card"></span>
            </div>
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="text" class="form-control" id="no_hp" placeholder="Masukkan No. HP" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-phone"></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-4">
            <button type="button" class="btn btn-primary btn-block" id="btnSubmit">Simpan</button>
        </div>
    </div>
</form>

                    <br>
                <a href="<?= site_url('login'); ?>" class="text-center">I already have a membership, Login!</a>
            </div>
            <!-- /.register-card-body -->
        </div>
    </div>
    <!-- /.register-box -->

    <!-- jQuery -->
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url('assets/dist/js/adminlte.min.js'); ?>"></script>
<script>
    // Fungsi untuk menambah pasien
    function tambahPasien() {
        var nama = $('#nama').val();
        var alamat = $('#alamat').val();
        var password = $('#password').val();
        var no_ktp = $('#no_ktp').val();
        var no_hp = $('#no_hp').val();

        $.ajax({
            url: '<?php echo site_url('pasien/tambah'); ?>', // URL untuk menambahkan pasien
            type: 'POST',
            data: {nama: nama, alamat: alamat, password: password, no_ktp: no_ktp, no_hp: no_hp},
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    alert('Pasien berhasil ditambahkan');
                    location.reload(); // Reload halaman untuk menampilkan data terbaru
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    }
    // Menangani submit untuk update atau tambah
    $('#btnSubmit').click(function() {
        var pasienId = $('#pasienId').val();
        if (pasienId) {
            updatePasien(); // Jika ID pasien ada, maka lakukan update
        } else {
            tambahPasien(); // Jika ID pasien kosong, berarti tambah pasien
        }
    });

</script>

</body>
</html>
