<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pasien</title>
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .table td {
            word-wrap: break-word;
            white-space: normal;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Daftar Pasien -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Pasien</h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 300px;">
                                <input type="text" id="searchPasien" class="form-control float-right" placeholder="Cari pasien...">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0" style="height: 300px;">
                        <table class="table table-head-fixed text-nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pasien</th>
                                    <th>Alamat</th>
                                    <th>No. KTP</th>
                                    <th>No. Telepon</th>
                                    <th>No. RM</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="pasienTableBody">
                            <?php if (!empty($pasien)): ?>
    <?php foreach ($pasien as $key => $item): ?>
        <tr>
            <td><?= $key + 1; ?></td>
            <td><?= isset($item['nama']) ? $item['nama'] : '-'; ?></td>
            <td><?= isset($item['alamat']) ? $item['alamat'] : '-'; ?></td>
            <td><?= isset($item['no_ktp']) ? $item['no_ktp'] : '-'; ?></td>
            <td><?= isset($item['no_hp']) ? $item['no_hp'] : '-'; ?></td>
            <td><?= isset($item['no_rm']) ? $item['no_rm'] : '-'; ?></td>
            <td>
                <button class="btn btn-info btn-sm" onclick="filterRiwayat(<?= isset($item['id_pasien']) ? $item['id_pasien'] : 'null'; ?>)">Lihat Riwayat</button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7">Tidak ada data pasien.</td>
    </tr>
<?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Pasien -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Riwayat Pasien</h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 300px;">
                                <input type="text" id="searchRiwayat" class="form-control float-right" placeholder="Cari riwayat pasien..." disabled>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0" style="height: 300px;">
                        <table class="table table-head-fixed text-nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Periksa</th>
                                    <th>Nama Pasien</th>
                                    <th>Nama Dokter</th>
                                    <th>Keluhan</th>
                                    <th>Catatan</th>
                                    <th>Obat</th>
                                    <th>Biaya Periksa</th>
                                </tr>
                            </thead>
                            <tbody id="riwayatTableBody">
                                <tr>
                                    <td colspan="8" class="text-center">Silahkan Pilih Salah Satu Pasien</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Filter Daftar Pasien
        $('#searchPasien').on('keyup', function() {
            const searchText = $(this).val().toLowerCase();
            $('#pasienTableBody tr').filter(function() {
                const columnsToSearch = $(this).children(':not(:nth-child(1))'); // Kecuali kolom 1
                let match = false;
                columnsToSearch.each(function() {
                    if ($(this).text().toLowerCase().indexOf(searchText) > -1) {
                        match = true;
                        return false; // Break loop jika ada kecocokan
                    }
                });
                $(this).toggle(match);
            });
        });

        // Filter Riwayat Pasien
        function filterRiwayat(id_pasien) {
            $.ajax({
                url: '<?= site_url("riwayat_pasien/get_detail_riwayat"); ?>',
                method: 'POST',
                data: { id_pasien },
                dataType: 'json',
                success: function(response) {
                    let html = '';
                    if (response && response.length > 0) {
                        response.forEach((item, index) => {
                            html += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.tgl_periksa}</td>
                                    <td>${item.nama_pasien}</td>
                                    <td>${item.nama_dokter}</td>
                                    <td>${item.keluhan}</td>
                                    <td>${item.catatan}</td>
                                    <td>${item.nama_obat}</td>
                                    <td>Rp. ${item.biaya_periksa}</td>
                                </tr>`;
                        });
                        $('#searchRiwayat').prop('disabled', false); // Aktifkan pencarian
                    } else {
                        html = '<tr><td colspan="8" class="text-center">Tidak ada data riwayat untuk pasien ini.</td></tr>';
                        $('#searchRiwayat').prop('disabled', true); // Nonaktifkan pencarian
                    }
                    $('#riwayatTableBody').html(html);
                },
                error: function() {
                    alert('Gagal memuat data riwayat.');
                }
            });
        }

        // Pencarian di Tabel Riwayat
        $('#searchRiwayat').on('keyup', function() {
            const searchText = $(this).val().toLowerCase();
            $('#riwayatTableBody tr').filter(function() {
                const columnsToSearch = $(this).children(':not(:nth-child(1), :nth-child(8))'); // Kecuali kolom 1 dan 8
                let match = false;
                columnsToSearch.each(function() {
                    if ($(this).text().toLowerCase().indexOf(searchText) > -1) {
                        match = true;
                        return false; // Break loop jika ada kecocokan
                    }
                });
                $(this).toggle(match);
            });
        });
    </script>
</body>
</html>
