<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Dokter</title>
    <!-- Import CSS dari template sebelumnya -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css'); ?>">
    <!-- jQuery -->
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
</head>
<body>
<div class="container">
    <!-- Form Kelola Dokter -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Form Kelola Dokter</h3>
        </div>
        <form id="formDokter">
            <div class="card-body">
                <input type="hidden" id="dokterId">
                <div class="form-group">
                    <label for="nama">Nama Dokter</label>
                    <input type="text" class="form-control" id="nama" placeholder="Masukkan Nama Dokter" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" class="form-control" id="alamat" placeholder="Masukkan Alamat" required>
                </div>
                <div class="form-group">
                    <label for="no_hp">No HP</label>
                    <input type="text" class="form-control" id="no_hp" placeholder="Masukkan No HP" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Isi jika ingin mengubah password">
                </div>
                <div class="form-group">
                    <label for="id_poli">Poli</label>
                    <select class="form-control" id="id_poli" required>
                        <option value="">Pilih Poli</option>
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary" onclick="simpanDokter()">Simpan</button>
            </div>
        </form>
    </div>

    <!-- List Dokter -->
    <div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Daftar Dokter</h3>
    </div>
    <div class="card-body table-responsive p-0" style="height: 300px;">
        <table class="table table-head-fixed text-nowrap">
            <thead>
                <tr>
                    <th>No</th> <!-- Mengganti ID dengan No -->
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                    <th>Poli</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dokter as $d) : ?>
                    <tr>
                        <td><?= $d->no ?></td> <!-- Menampilkan nomor urut -->
                        <td><?= $d->nama ?></td>
                        <td><?= $d->alamat ?></td>
                        <td><?= $d->no_hp ?></td>
                        <td><?= $d->nama_poli ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editDokter(<?= $d->id ?>)">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="hapusDokter(<?= $d->id ?>)">Hapus</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<script>
$(document).ready(function() {
    // Memuat daftar Poli ke dalam dropdown
    $.ajax({
        url: '<?php echo site_url('dokter/get_poli'); ?>',
        method: 'GET',
        success: function(response) {
            var data = JSON.parse(response);
            var poliSelect = $('#id_poli');
            poliSelect.empty();
            poliSelect.append('<option value="">Pilih Poli</option>');
            data.forEach(function(poli) {
                poliSelect.append('<option value="' + poli.id + '">' + poli.nama_poli + '</option>');
            });
        }
    });
});

function simpanDokter() {
    var data = {
        id: $('#dokterId').val(),
        nama: $('#nama').val(),
        alamat: $('#alamat').val(),
        no_hp: $('#no_hp').val(),
        password: $('#password').val(),
        id_poli: $('#id_poli').val()
    };
    var url = data.id ? '<?php echo site_url('dokter/edit'); ?>' : '<?php echo site_url('dokter/tambah'); ?>';
    
    $.ajax({
        url: url,
        method: 'POST',
        data: data,
        success: function(response) {
            var res = JSON.parse(response);
            alert(res.message);
            if (res.status === 'success') {
                window.location.reload();
            }
        }
    });
}

function editDokter(id) {
    $.ajax({
        url: '<?php echo site_url('dokter/get'); ?>/' + id,
        method: 'GET',
        success: function(response) {
            var res = JSON.parse(response);
            if (res.status === 'success') {
                var dokter = res.data;
                $('#dokterId').val(dokter.id);
                $('#nama').val(dokter.nama);
                $('#alamat').val(dokter.alamat);
                $('#no_hp').val(dokter.no_hp);
                $('#id_poli').val(dokter.id_poli);
            }
        }
    });
}

function hapusDokter(id) {
    if (confirm('Apakah anda yakin ingin menghapus dokter ini?')) {
        $.ajax({
            url: '<?php echo site_url('dokter/hapus'); ?>/' + id,
            method: 'POST',
            success: function(response) {
                var res = JSON.parse(response);
                alert(res.message);
                if (res.status === 'success') {
                    window.location.reload();
                }
            }
        });
    }
}
</script>

</body>
</html>
