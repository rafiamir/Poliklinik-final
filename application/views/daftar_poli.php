<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Poli</title>
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css'); ?>">
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function loadJadwal(id_poli) {
            if (!id_poli) {
                $('#jadwal').html('<option value="">-- Pilih Jadwal --</option>');
                return;
            }

            $.ajax({
                url: '<?= site_url("daftar_poli/get_jadwal_by_poli"); ?>',
                method: 'POST',
                data: { id_poli },
                dataType: 'json',
                success: function(response) {
                    var options = '<option value="">-- Pilih Jadwal --</option>';
                    if (response.length > 0) {
                        response.forEach(function(item) {
                            options += `<option value="${item.id}">${item.hari} - ${item.jam_mulai} (${item.nama_dokter})</option>`;
                        });
                    } else {
                        options += '<option value="">Jadwal Tidak Tersedia</option>';
                    }
                    $('#jadwal').html(options);
                },
                error: function() {
                    alert('Gagal memuat jadwal. Silakan coba lagi.');
                }
            });
        }

        function daftarPoli() {
            var id_jadwal = $('#jadwal').val();
            var keluhan = $('#keluhan').val();

            if (!id_jadwal || !keluhan) {
                alert('Semua field harus diisi!');
                return;
            }

            $.ajax({
                url: '<?= site_url("daftar_poli/tambah"); ?>',
                method: 'POST',
                data: { id_jadwal, keluhan },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Gagal mendaftar. Silakan coba lagi.');
                }
            });
        }

        function showDetail(id) {
            $.ajax({
                url: '<?= site_url("daftar_poli/get_detail"); ?>',
                method: 'POST',
                data: { id },
                dataType: 'json',
                success: function(response) {
                    if (response) {
                        $('#detailNamaPoli').text(response.nama_poli);
                        $('#detailNamaDokter').text(response.nama_dokter);
                        $('#detailHari').text(response.hari);
                        $('#detailJamMulai').text(response.jam_mulai);
                        $('#detailJamSelesai').text(response.jam_selesai);
                        $('#detailNoAntrian').text(response.no_antrian);

                        $('#detailModal').modal('show');
                    } else {
                        alert('Data detail tidak ditemukan.');
                    }
                },
                error: function() {
                    alert('Gagal mengambil data detail. Silakan coba lagi.');
                }
            });
        }

        // Fungsi untuk memformat tanggal dengan nama hari
function formatTanggal(tanggalString) {
    const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    const tanggal = new Date(tanggalString);
    const namaHari = hari[tanggal.getDay()];
    const namaBulan = bulan[tanggal.getMonth()];
    const tahun = tanggal.getFullYear();
    const tanggalHari = tanggal.getDate();

    return `${namaHari}, ${tanggalHari} ${namaBulan} ${tahun}`;
}

function showRiwayat(id) {
    $.ajax({
        url: '<?= site_url("daftar_poli/get_detail"); ?>',
        method: 'POST',
        data: { id },
        dataType: 'json',
        success: function(detailResponse) {
            if (detailResponse) {
                var detailHtml = `<p><strong>Nama Poli:</strong> ${detailResponse.nama_poli}</p>`;
                detailHtml += `<p><strong>Nama Dokter:</strong> ${detailResponse.nama_dokter}</p>`;
                detailHtml += `<p><strong>Hari:</strong> ${detailResponse.hari}</p>`;
                detailHtml += `<p><strong>Jam Mulai:</strong> ${detailResponse.jam_mulai}</p>`;
                detailHtml += `<p><strong>Jam Selesai:</strong> ${detailResponse.jam_selesai}</p>`;
                detailHtml += `<p><strong>Nomor Antrian:</strong> ${detailResponse.no_antrian}</p>`;
                detailHtml += '<hr>';

                // Fetch Riwayat Data
                $.ajax({
                    url: '<?= site_url("daftar_poli/get_riwayat_detail"); ?>',
                    method: 'POST',
                    data: { id },
                    dataType: 'json',
                    success: function(riwayatResponse) {
                        if (riwayatResponse) {
                            // Mengelompokkan data berdasarkan tanggal periksa
                            const groupedRiwayat = {};
                            riwayatResponse.forEach(function(item) {
                                const key = item.tgl_periksa; // Gunakan tanggal sebagai kunci
                                if (!groupedRiwayat[key]) {
                                    groupedRiwayat[key] = {
                                        tgl_periksa: item.tgl_periksa,
                                        catatan: item.catatan,
                                        biaya_periksa: item.biaya_periksa,
                                        nama_obat: []
                                    };
                                }
                                groupedRiwayat[key].nama_obat.push(item.nama_obat);
                            });

                            // Membuat HTML dari data yang telah dikelompokkan
                            var riwayatHtml = '<h5>Riwayat Pemeriksaan</h5>';
                            Object.values(groupedRiwayat).forEach(function(item) {
                                const formattedTanggal = formatTanggal(item.tgl_periksa);
                                const obatList = item.nama_obat.join(', ');

                                riwayatHtml += `<p><strong>Tanggal Periksa:</strong> ${formattedTanggal}</p>`;
                                riwayatHtml += `<p><strong>Catatan Dokter:</strong> ${item.catatan}</p>`;
                                riwayatHtml += `<p><strong>Biaya Periksa:</strong> ${item.biaya_periksa}</p>`;
                                riwayatHtml += `<p><strong>Obat:</strong> ${obatList}</p>`;
                                riwayatHtml += '<hr>';
                            });

                            $('#riwayatModalContent').html(detailHtml + riwayatHtml);
                            $('#riwayatModal').modal('show');
                        } else {
                            alert('Riwayat tidak ditemukan.');
                        }
                    },
                    error: function() {
                        alert('Gagal mengambil data riwayat.');
                    }
                });
            } else {
                alert('Detail tidak ditemukan.');
            }
        },
        error: function() {
            alert('Gagal mengambil data detail.');
        }
    });
}



    </script>
</head>
<body>
    <div class="container">
        <h3>Form Daftar Poli</h3>
        <form onsubmit="event.preventDefault(); daftarPoli();">
            <div class="form-group">
                <label for="no_rm">Nomor Rekam Medis</label>
                <input type="text" id="no_rm" class="form-control" value="<?= $no_rm; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="poli">Pilih Poli</label>
                <select id="poli" class="form-control" onchange="loadJadwal(this.value);">
                    <option value="">-- Pilih Poli --</option>
                    <?php foreach ($poli as $item): ?>
                        <option value="<?= $item['id']; ?>"><?= $item['nama_poli']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="jadwal">Pilih Jadwal</label>
                <select id="jadwal" class="form-control">
                    <option value="">-- Pilih Jadwal --</option>
                </select>
            </div>
            <div class="form-group">
                <label for="keluhan">Keluhan</label>
                <textarea id="keluhan" class="form-control" rows="4"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Daftar</button>
        </form>

        <h3 class="mt-5">Riwayat Daftar Poli</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Poli</th>
                    <th>Dokter</th>
                    <th>Hari</th>
                    <th>Mulai</th>
                    <th>Antrian</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($riwayat)): ?>
                    <?php foreach ($riwayat as $key => $item): ?>
                        <tr>
                            <td><?= $key + 1; ?></td>
                            <td><?= $item['nama_poli']; ?></td>
                            <td><?= $item['nama_dokter']; ?></td>
                            <td><?= $item['hari']; ?></td>
                            <td><?= $item['jam_mulai']; ?></td>
                            <td><?= $item['no_antrian'] ?: '-'; ?></td>
                            <td><?= ucfirst($item['status']); ?></td>
                            <td>
                                <?php if ($item['status'] == 'sudah diperiksa'): ?>
                                    <button class="btn btn-warning btn-sm" onclick="showRiwayat(<?= $item['id']; ?>)">Riwayat</button>
                                <?php else: ?>
                                    <button class="btn btn-info btn-sm" onclick="showDetail(<?= $item['id']; ?>)">Detail</button>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Belum ada riwayat pendaftaran poli.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal untuk Menampilkan Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Pendaftaran Poli</h5>
                </div>
                <div class="modal-body">
                    <p><strong>Nama Poli:</strong> <span id="detailNamaPoli"></span></p>
                    <p><strong>Nama Dokter:</strong> <span id="detailNamaDokter"></span></p>
                    <p><strong>Hari:</strong> <span id="detailHari"></span></p>
                    <p><strong>Jam Mulai:</strong> <span id="detailJamMulai"></span></p>
                    <p><strong>Jam Selesai:</strong> <span id="detailJamSelesai"></span></p>
                    <p><strong>Nomor Antrian:</strong> <span id="detailNoAntrian"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Menampilkan Riwayat -->
<div class="modal fade" id="riwayatModal" tabindex="-1" aria-labelledby="riwayatModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="riwayatModalLabel">Detail dan Riwayat</h5>
            </div>
            <div class="modal-body" id="riwayatModalContent">
                <!-- Konten detail daftar dan riwayat akan diisi dengan JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
