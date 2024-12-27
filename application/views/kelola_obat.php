<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Obat</title>
    <!-- Import CSS dari template sebelumnya -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css'); ?>">
    <!-- jQuery -->
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
</head>
<body>
<div class="container">
    <!-- Form Kelola Obat -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title" id="formTitle">Tambah Obat</h3>
        </div>
        <form id="formObat" onsubmit="event.preventDefault();">
            <div class="card-body">
                <input type="hidden" id="obatId">
                <div class="form-group">
                    <label for="nama_obat">Nama Obat</label>
                    <input type="text" class="form-control" id="nama_obat" placeholder="Masukkan Nama Obat" required>
                </div>
                <div class="form-group">
                    <label for="kemasan">Kemasan</label>
                    <input type="text" class="form-control" id="kemasan" placeholder="Masukkan Kemasan">
                </div>
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" id="harga" placeholder="Masukkan Harga" required>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary" id="btnSubmit" onclick="simpanObat()">Simpan</button>
                <button type="button" class="btn btn-secondary" id="btnTutupForm" onclick="resetForm()">Tutup</button>
            </div>
        </form>
    </div>

    <!-- List Obat -->
<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Daftar Obat</h3>
    </div>
    <div class="card-body table-responsive p-0" style="height: 300px;">
        <table class="table table-head-fixed text-nowrap">
            <thead>
                <tr>
                    <th>No</th> <!-- Kolom nomor urut -->
                    <th>Nama Obat</th>
                    <th>Kemasan</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tbodyObat">
                <?php if (empty($obat)): ?>
                    <tr><td colspan="6">Tidak ada data obat.</td></tr>
                <?php else: ?>
                    <?php foreach ($obat as $key => $o): ?>
                        <tr id="obat_<?php echo $o->id; ?>">
                            <td><?php echo $key + 1; ?></td> <!-- Menambahkan nomor urut -->
                            <td><?php echo $o->nama_obat; ?></td>
                            <td><?php echo $o->kemasan; ?></td>
                            <td><?php echo $o->harga; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editObat(<?php echo $o->id; ?>)">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="hapusObat(<?php echo $o->id; ?>)">Hapus</button>
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
function simpanObat() {
    var data = {
        id: $('#obatId').val(),
        nama_obat: $('#nama_obat').val(),
        kemasan: $('#kemasan').val(),
        harga: $('#harga').val()
    };
    var url = data.id ? '<?= site_url('obat/edit'); ?>' : '<?= site_url('obat/tambah'); ?>';

    $.ajax({
        url: url,
        type: 'POST',
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

function editObat(id) {
    $.ajax({
        url: '<?= site_url('obat/get'); ?>/' + id,
        type: 'GET',
        success: function(response) {
            var res = JSON.parse(response);
            if (res.status === 'success') {
                var obat = res.data;
                $('#obatId').val(obat.id);
                $('#nama_obat').val(obat.nama_obat);
                $('#kemasan').val(obat.kemasan);
                $('#harga').val(obat.harga);
                $('#formTitle').text('Edit Obat');
                $('#btnSubmit').text('Update');
            }
        }
    });
}

function hapusObat(id) {
    if (confirm('Apakah Anda yakin ingin menghapus obat ini?')) {
        $.ajax({
            url: '<?= site_url('obat/hapus'); ?>/' + id,
            type: 'POST',
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

function resetForm() {
    $('#obatId').val('');
    $('#nama_obat').val('');
    $('#kemasan').val('');
    $('#harga').val('');
    $('#formTitle').text('Tambah Obat');
    $('#btnSubmit').text('Simpan');
}
</script>

</body>
</html>
