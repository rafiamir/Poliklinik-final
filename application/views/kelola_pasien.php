<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pasien</title>
    <!-- Import CSS dari template AdminLTE -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css'); ?>">
    <!-- jQuery -->
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
</head>
<body>
<div class="container">
    <!-- Form Kelola Pasien -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title" id="formTitle">Tambah Pasien</h3>
        </div>
        <form id="formTambahPasien" onsubmit="event.preventDefault();">
            <div class="card-body">
                <input type="hidden" id="pasienId">
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" placeholder="Masukkan Nama" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" class="form-control" id="alamat" placeholder="Masukkan Alamat" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Masukkan Password">
                </div>
                <div class="form-group">
                    <label for="no_ktp">No. KTP</label>
                    <input type="text" class="form-control" id="no_ktp" placeholder="Masukkan No. KTP" required>
                </div>
                <div class="form-group">
                    <label for="no_hp">No. HP</label>
                    <input type="text" class="form-control" id="no_hp" placeholder="Masukkan No. HP" required>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary" id="btnSubmit">Simpan</button>
                <button type="button" class="btn btn-secondary" id="btnTutupForm">Tutup</button>
            </div>
        </form>
    </div>

    <!-- Daftar Pasien -->
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Daftar Pasien</h3>
        </div>
        <div class="card-body table-responsive p-0" style="height: 400px;">
            <table class="table table-head-fixed text-nowrap">
            <thead>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Alamat</th>
        <th>No. KTP</th>
        <th>No. HP</th>
        <th>No. RM</th>
        <th>Aksi</th>
    </tr>
</thead>
<tbody id="tbodyPasien">
    <?php if (empty($pasien)): ?>
        <tr><td colspan="7" class="text-center">Tidak ada data pasien.</td></tr>
    <?php else: ?>
        <?php foreach ($pasien as $p): ?>
            <tr id="pasien_<?php echo $p['id']; ?>">
                <td><?php echo $p['no']; ?></td> <!-- Menampilkan nomor urut -->
                <td><?php echo $p['nama']; ?></td>
                <td><?php echo $p['alamat']; ?></td>
                <td><?php echo $p['no_ktp']; ?></td>
                <td><?php echo $p['no_hp']; ?></td>
                <td><?php echo $p['no_rm']; ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="editPasien(<?php echo $p['id']; ?>)">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="hapusPasien(<?php echo $p['id']; ?>)">Hapus</button>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>

            </table>
        </div>
    </div>
</div>

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

    // Fungsi untuk mengedit pasien
    function editPasien(id) {
        $.ajax({
            url: '<?php echo site_url('pasien/get'); ?>/' + id, // Mengambil data pasien berdasarkan ID
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    var pasien = response.data;
                    // Isi form dengan data pasien
                    $('#pasienId').val(pasien.id);
                    $('#nama').val(pasien.nama);
                    $('#alamat').val(pasien.alamat);
                    $('#no_ktp').val(pasien.no_ktp);
                    $('#no_hp').val(pasien.no_hp);
                    $('#formTitle').text('Edit Pasien'); // Ubah judul form menjadi "Edit Pasien"
                    $('#btnSubmit').text('Update'); // Ubah tombol menjadi "Update"
                    
                    // Tampilkan form jika sebelumnya tersembunyi
                    $('#formTambahPasien').show();
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    }

    // Fungsi untuk memperbarui data pasien
    function updatePasien() {
        var pasienId = $('#pasienId').val();
        var nama = $('#nama').val();
        var alamat = $('#alamat').val();
        var no_ktp = $('#no_ktp').val();
        var no_hp = $('#no_hp').val();

        $.ajax({
            url: '<?php echo site_url('pasien/edit'); ?>', // URL untuk memperbarui pasien
            type: 'POST',
            data: {id: pasienId, nama: nama, alamat: alamat, no_ktp: no_ktp, no_hp: no_hp},
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    alert('Pasien berhasil diperbarui');
                    location.reload(); // Reload halaman untuk menampilkan data terbaru
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    }

    // Fungsi untuk menghapus pasien
    function hapusPasien(id) {
        if (confirm("Apakah Anda yakin ingin menghapus pasien ini?")) {
            $.ajax({
                url: '<?php echo site_url('pasien/hapus/'); ?>' + id, // URL untuk menghapus pasien
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        alert('Pasien berhasil dihapus');
                        location.reload(); // Reload halaman untuk menampilkan data terbaru
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        }
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
