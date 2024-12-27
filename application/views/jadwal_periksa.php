<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jadwal Periksa</title>
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css'); ?>">
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script>
        function tambahJadwal() {
    var id_dokter = $('#id_dokter').val();
    var hari = $('#hari').val();
    var jam_mulai = $('#jam_mulai').val();
    var jam_selesai = $('#jam_selesai').val();
    var status = $('#status').val() || 'tidak aktif'; // Default ke 'tidak aktif' jika kosong

    if (!id_dokter || !hari || !jam_mulai || !jam_selesai) {
        alert('Semua field harus diisi');
        return;
    }

    $.ajax({
        url: '<?php echo site_url('jadwal_periksa/tambah'); ?>',
        type: 'POST',
        data: { id_dokter, hari, jam_mulai, jam_selesai, status },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert('Jadwal berhasil ditambahkan');
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        }
    });
}


        function updateJadwal(id) {
            var hari = $('#hari').val();
            var jam_mulai = $('#jam_mulai').val();
            var jam_selesai = $('#jam_selesai').val();
            var status = $('#status').val();

            if (!hari || !jam_mulai || !jam_selesai || !status) {
                alert('Semua field harus diisi');
                return;
            }

            $.ajax({
                url: '<?php echo site_url('jadwal_periksa/edit'); ?>/' + id,
                type: 'POST',
                data: { hari, jam_mulai, jam_selesai, status },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        alert('Jadwal berhasil diperbarui');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        }

        function setFormMode(mode, id = null) {
            if (mode === 'tambah') {
                $('#formTitle').text('Tambah Jadwal');
                $('#btnSubmit').text('Tambah');
                $('#btnSubmit').off('click').on('click', function() {
                    tambahJadwal();
                });
            } else if (mode === 'edit') {
                $('#formTitle').text('Edit Jadwal');
                $('#btnSubmit').text('Update');
                $('#btnSubmit').off('click').on('click', function() {
                    updateJadwal(id);
                });
            }
        }

        function tambahJadwalModal() {
            $('#formJadwal')[0].reset();
            setFormMode('tambah'); // Set mode ke Tambah
            $('#modalForm').modal('show');
        }

        function editJadwal(id) {
            $.ajax({
                url: '<?php echo site_url('jadwal_periksa/get'); ?>/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        var jadwal = response.data;
                        $('#hari').val(jadwal.hari);
                        $('#jam_mulai').val(jadwal.jam_mulai);
                        $('#jam_selesai').val(jadwal.jam_selesai);
                        $('#status').val(jadwal.status);

                        setFormMode('edit', id); // Set mode ke Edit
                        $('#modalForm').modal('show');
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Kelola Jadwal Periksa</h3>
            </div>
            <div class="card-body">
                <button class="btn btn-success" onclick="tambahJadwalModal()">Tambah Jadwal</button>
            </div>
        </div>

        <!-- Daftar Jadwal -->
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Daftar Jadwal Periksa</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Dokter</th>
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php if (!empty($jadwal)): ?>
        <?php foreach ($jadwal as $key => $item): ?>
            <tr>
                <td><?= $key + 1; ?></td>
                <td><?= $item['nama_dokter']; ?></td>
                <td><?= $item['hari']; ?></td>
                <td><?= $item['jam_mulai']; ?></td>
                <td><?= $item['jam_selesai']; ?></td>
                <td>
    <?php if ($item['status'] === 'aktif'): ?>
        <span class="badge badge-success">Aktif</span>
    <?php elseif ($item['status'] === 'tidak aktif'): ?>
        <span class="badge badge-danger">Tidak Aktif</span>
    <?php else: ?>
        <span class="badge badge-secondary">Tidak Diketahui</span>
    <?php endif; ?>
</td>


                <td>
                    <button class="btn btn-warning btn-sm" onclick="editJadwal(<?= $item['id']; ?>)">Edit</button>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="7">Tidak ada jadwal periksa untuk Anda.</td>
        </tr>
    <?php endif; ?>
</tbody>


                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formTitle">Tambah Jadwal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formJadwal" onsubmit="event.preventDefault();">
                        <div class="form-group">
                            <label for="nama_dokter">Nama Dokter</label>
                            <input type="text" class="form-control" id="nama_dokter" value="<?= $this->session->userdata('nama'); ?>" readonly>
                            <input type="hidden" id="id_dokter" value="<?= $this->session->userdata('id'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="hari">Hari</label>
                            <select class="form-control" id="hari" required>
                                <option value="">-- Pilih Hari --</option>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                                <option value="Minggu">Minggu</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jam_mulai">Jam Mulai</label>
                            <input type="time" class="form-control" id="jam_mulai" required>
                        </div>
                        <div class="form-group">
                            <label for="jam_selesai">Jam Selesai</label>
                            <input type="time" class="form-control" id="jam_selesai" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="tidak aktif">Tidak Aktif</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btnSubmit">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
