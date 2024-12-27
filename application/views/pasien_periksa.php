<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memeriksa Pasien</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h3>Daftar Pasien untuk Pemeriksaan</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No. Antrian</th>
                    <th>Nama Pasien</th>
                    <th>Keluhan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pasien)): ?>
                    <?php foreach ($pasien as $item): ?>
                        <tr>
                            <td><?= $item['no_antrian']; ?></td>
                            <td><?= $item['nama_pasien']; ?></td>
                            <td><?= $item['keluhan']; ?></td>
                            <td>
    <?php if ($item['status'] === 'belum diperiksa'): ?>
        <button class="btn btn-primary btn-sm periksa-btn" 
                data-id="<?= $item['id']; ?>" 
                data-nama-pasien="<?= $item['nama_pasien']; ?>">Periksa</button>
    <?php else: ?>
        <button class="btn btn-warning btn-sm edit-btn" 
                data-id="<?= $item['id']; ?>" 
                data-nama-pasien="<?= $item['nama_pasien']; ?>">Edit</button>
    <?php endif; ?>
</td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Belum ada pasien yang terdaftar untuk diperiksa.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Pemeriksaan -->
    <div class="modal fade" id="modalPeriksa" tabindex="-1" aria-labelledby="modalPeriksaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPeriksaLabel">Form Pemeriksaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formPeriksa" onsubmit="event.preventDefault(); submitPeriksa();">
                    <div class="modal-body">
                        <input type="hidden" id="id_daftar_poli" name="id_daftar_poli">
                        <div class="mb-3">
                            <label for="nama_pasien" class="form-label">Nama Pasien</label>
                            <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="tgl_periksa" class="form-label">Tanggal Periksa</label>
                            <input type="date" class="form-control" id="tgl_periksa" name="tgl_periksa" required>
                        </div>
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="obat" class="form-label">Obat</label>
                            <select class="form-select" id="obat">
                                <option value="">-- Pilih Obat --</option>
                            </select>
                            <button type="button" class="btn btn-secondary mt-2" id="add-obat">Tambah Obat</button>
                            <ul id="obat-list" class="list-group mt-2"></ul>
                        </div>
                        <div class="mb-3">
                            <label for="total_harga" class="form-label">Total Harga</label>
                            <input type="number" class="form-control" id="total_harga" name="total_harga" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
let totalHarga = 50000; // Biaya dokter
let obatDipilih = []; // Array untuk menyimpan ID obat yang dipilih

// Fungsi untuk mereset form
function resetForm() {
    $('#id_daftar_poli').val('');
    $('#nama_pasien').val('');
    $('#tgl_periksa').val('');
    $('#catatan').val('');
    $('#obat-list').empty(); // Kosongkan daftar obat
    $('#total_harga').val(50000); // Reset total harga ke biaya dokter
    totalHarga = 50000; // Reset variabel global totalHarga
    obatDipilih = []; // Kosongkan array obat yang dipilih
    $('#obat').html('<option value="">-- Pilih Obat --</option>'); // Reset dropdown obat
}

$(document).ready(function () {
    // Event untuk tombol Periksa
    $(document).on('click', '.periksa-btn', function () {
        resetForm(); // Reset form sebelum menampilkan modal

        const idDaftarPoli = $(this).data('id');
        const namaPasien = $(this).data('nama-pasien');
        $('#id_daftar_poli').val(idDaftarPoli);
        $('#nama_pasien').val(namaPasien);

        // Load data obat ke dropdown
        $.ajax({
            url: '<?= site_url("dashboard_dokter/get_obat"); ?>',
            method: 'GET',
            success: function (response) {
                const data = JSON.parse(response);
                data.forEach(item => {
                    $('#obat').append(`<option value="${item.id}" data-harga="${item.harga}">${item.nama_obat} (${item.kemasan})</option>`);
                });
            },
            error: function () {
                alert('Gagal memuat data obat.');
            }
        });

        const modalElement = document.getElementById('modalPeriksa');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    });

    // Event untuk tombol Edit
    $(document).on('click', '.edit-btn', function () {
        resetForm(); // Reset form sebelum menampilkan modal

        const idDaftarPoli = $(this).data('id');
        const namaPasien = $(this).data('nama-pasien');
        $('#id_daftar_poli').val(idDaftarPoli);
        $('#nama_pasien').val(namaPasien);

        // Load data pemeriksaan sebelumnya
        $.ajax({
            url: '<?= site_url("dashboard_dokter/get_detail_periksa"); ?>',
            method: 'POST',
            data: { id_daftar_poli: idDaftarPoli },
            success: function (response) {
                const data = JSON.parse(response);

                // Autofill data pemeriksaan
                $('#tgl_periksa').val(data.tgl_periksa);
                $('#catatan').val(data.catatan);

                // Reset total harga
                totalHarga = 50000; // Harga dasar adalah jasa dokter
                $('#obat-list').empty();
                obatDipilih = [];
                data.obat.forEach(item => {
                    $('#obat-list').append(`<li class="list-group-item" data-id="${item.id}" data-harga="${item.harga}">
                        ${item.nama_obat}
                        <button class="btn btn-sm btn-danger float-end remove-obat">Hapus</button>
                    </li>`);
                    obatDipilih.push(item.id);
                    totalHarga += parseInt(item.harga); // Tambahkan harga obat ke totalHarga
                });
                $('#total_harga').val(totalHarga); // Update total harga di input
            },
            error: function () {
                alert('Gagal memuat data pemeriksaan.');
            }
        });

        // Load data obat ke dropdown
        $.ajax({
            url: '<?= site_url("dashboard_dokter/get_obat"); ?>',
            method: 'GET',
            success: function (response) {
                const data = JSON.parse(response);
                data.forEach(item => {
                    $('#obat').append(`<option value="${item.id}" data-harga="${item.harga}">${item.nama_obat} (${item.kemasan})</option>`);
                });
            },
            error: function () {
                alert('Gagal memuat data obat.');
            }
        });

        const modalElement = document.getElementById('modalPeriksa');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    });

    // Event untuk menambahkan obat ke daftar
    $('#add-obat').click(function () {
        const obatId = $('#obat').val();
        const obatText = $('#obat option:selected').text();
        const obatHarga = parseInt($('#obat option:selected').data('harga'));

        if (obatId && !obatDipilih.includes(obatId)) {
            obatDipilih.push(obatId);
            totalHarga += obatHarga;

            $('#obat-list').append(`<li class="list-group-item" data-id="${obatId}" data-harga="${obatHarga}">
                ${obatText}
                <button class="btn btn-sm btn-danger float-end remove-obat">Hapus</button>
            </li>`);

            $('#total_harga').val(totalHarga);
        } else if (!obatId) {
            alert('Silakan pilih obat terlebih dahulu.');
        } else {
            alert('Obat sudah ditambahkan.');
        }
    });

    // Event untuk menghapus obat dari daftar
    $('#obat-list').on('click', '.remove-obat', function () {
        const harga = $(this).parent().data('harga');
        const id = $(this).parent().data('id');
        totalHarga -= harga;
        obatDipilih = obatDipilih.filter(item => item != id);
        $(this).parent().remove();
        $('#total_harga').val(totalHarga);
    });

    // Submit pemeriksaan
    function submitPeriksa() {
        const data = {
            id_daftar_poli: $('#id_daftar_poli').val(),
            tgl_periksa: $('#tgl_periksa').val(),
            catatan: $('#catatan').val(),
            total_harga: totalHarga,
            obat: obatDipilih
        };

        // Validasi input sebelum submit
        if (!data.tgl_periksa) {
            alert('Tanggal periksa harus diisi.');
            return;
        }
        if (data.obat.length === 0) {
            alert('Minimal satu obat harus dipilih.');
            return;
        }

        $.ajax({
            url: '<?= site_url("dashboard_dokter/simpan_periksa"); ?>',
            method: 'POST',
            data: data,
            success: function (response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    alert('Pemeriksaan berhasil disimpan.');
                    location.reload();
                } else {
                    alert(res.message);
                }
            },
            error: function () {
                alert('Gagal menyimpan pemeriksaan.');
            }
        });
    }

    // Assign submit function to form
    $('#formPeriksa').on('submit', function (e) {
        e.preventDefault();
        submitPeriksa();
    });
});




</script>

</body>
</html>
