<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Dokter</title>
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
</head>
<body>
    <div class="container">
        <h3>Profil Dokter</h3>
        <form id="formProfil" onsubmit="event.preventDefault(); updateProfil();">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" class="form-control" value="<?= $dokter->nama; ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat" class="form-control" rows="3" required><?= $dokter->alamat; ?></textarea>
            </div>
            <div class="form-group">
                <label for="no_hp">Nomor Telepon</label>
                <input type="text" id="no_hp" name="no_hp" class="form-control" value="<?= $dokter->no_hp; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>

    <script>
        function updateProfil() {
            const formData = {
                nama: $('#nama').val(),
                alamat: $('#alamat').val(),
                no_hp: $('#no_hp').val()
            };

            $.ajax({
                url: '<?= site_url("dashboard_dokter/update_profil"); ?>',
                method: 'POST',
                data: formData,
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
                    alert('Gagal memperbarui profil. Silakan coba lagi.');
                }
            });
        }
    </script>
</body>
</html>
