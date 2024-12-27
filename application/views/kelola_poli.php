<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Poli</title>
    <!-- Import CSS dari template AdminLTE -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css'); ?>">
    <!-- jQuery -->
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <script>
    // Fungsi untuk menambah poli
    function tambahPoli() {
        var nama_poli = $('#nama_poli').val();
        var deskripsi = $('#deskripsi').val();

        $.ajax({
            url: '<?php echo site_url('poli/tambah'); ?>',
            type: 'POST',
            data: {nama_poli: nama_poli, deskripsi: deskripsi},
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    alert('Poli berhasil ditambahkan');
                    location.reload(); // Reload halaman untuk menampilkan data terbaru
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    }

    // Fungsi untuk mengedit poli
    function editPoli(id) {
        $.ajax({
            url: '<?php echo site_url('poli/get'); ?>/' + id, // Menggunakan URL yang sesuai untuk mengambil data berdasarkan ID
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    var poli = response.data;
                    // Isi form dengan data poli
                    $('#poliId').val(poli.id);
                    $('#nama_poli').val(poli.nama_poli); // Isi form input dengan nama poli
                    $('#deskripsi').val(poli.deskripsi); // Isi form input dengan deskripsi poli
                    $('#formTitle').text('Edit Poli');
                    $('#btnSubmit').text('Update'); // Ubah tombol menjadi Update
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    }

    // Fungsi untuk memperbarui data poli
    function updatePoli() {
        var poliId = $('#poliId').val();
        var nama_poli = $('#nama_poli').val();
        var deskripsi = $('#deskripsi').val();

        $.ajax({
            url: '<?php echo site_url('poli/edit'); ?>/' + poliId, // Menambahkan ID poli untuk melakukan update
            type: 'POST',
            data: {id: poliId, nama_poli: nama_poli, deskripsi: deskripsi},
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    alert('Poli berhasil diperbarui');
                    location.reload(); // Reload halaman untuk menampilkan data terbaru
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    }

    // Fungsi untuk menghapus poli
function hapusPoli(id) {
    if (confirm('Apakah Anda yakin ingin menghapus poli ini?')) {
        $.ajax({
            url: '<?php echo site_url('poli/hapus'); ?>/' + id, // Menggunakan URL untuk menghapus data poli berdasarkan ID
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    alert('Poli berhasil dihapus');
                    $('#poli-' + id).remove(); // Menghapus baris tabel yang sesuai dengan ID poli
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    }
}


    // Fungsi untuk menutup form
    $('#btnTutupForm').click(function() {
        $('#poliId').val('');
        $('#nama_poli').val('');
        $('#deskripsi').val('');
        $('#formTitle').text('Tambah Poli'); // Reset form title ke "Tambah Poli"
        $('#btnSubmit').text('Simpan'); // Reset button ke "Simpan"
    });

    // Menangani submit untuk update atau tambah
    $('#btnSubmit').click(function() {
        var poliId = $('#poliId').val();
        if (poliId) {
            updatePoli(); // Jika ID poli ada, maka lakukan update
        } else {
            tambahPoli(); // Jika ID poli kosong, berarti tambah poli
        }
    });
</script>

</head>
<body>

<div class="container">
    <!-- Form Kelola Poli -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title" id="formTitle">Tambah Poli</h3>
        </div>
        <form id="formTambahPoli" onsubmit="event.preventDefault();">
            <div class="card-body">
                <input type="hidden" id="poliId">
                <div class="form-group">
                    <label for="nama_poli">Nama Poli</label>
                    <input type="text" class="form-control" id="nama_poli" placeholder="Masukkan Nama Poli" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" placeholder="Masukkan Deskripsi Poli" rows="3" required></textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary" id="btnSubmit">Simpan</button>
                <button type="button" class="btn btn-secondary" id="btnTutupForm">Tutup</button>
            </div>
        </form>
    </div>
<!-- Daftar Poli -->
<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Daftar Poli</h3>
    </div>
    <div class="card-body table-responsive p-0" style="height: 400px;">
        <table class="table table-head-fixed text-nowrap">
            <thead>
                <tr>
                    <th>No</th> <!-- Kolom nomor urut -->
                    <th>Nama Poli</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($poli)): ?>
                    <?php foreach ($poli as $key => $datapoli): ?>
                        <tr id="poli-<?php echo $datapoli['id']; ?>">
                            <td><?php echo $key + 1; ?></td> <!-- Menampilkan nomor urut -->
                            <td><?php echo $datapoli['nama_poli']; ?></td>
                            <td><?php echo $datapoli['deskripsi']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editPoli(<?php echo $datapoli['id']; ?>)">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="hapusPoli(<?php echo $datapoli['id']; ?>)">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Tidak ada data poli.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</table>

</body>
</html>
