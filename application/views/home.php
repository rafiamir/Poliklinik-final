<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poliklinik - Welcome</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?= base_url('assets/dist/img/bg-home.jpg'); ?>') no-repeat center center/cover;
            color: white;
            flex-direction: column;
            /* padding: 20px; */
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        .btn-custom {
            margin: 0 10px;
            padding: 10px 20px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 30px;
            transition: transform 0.3s, background-color 0.3s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            display: flex;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }

        .btn-success {
            background-color: #28a745;
            color: #fff;
            border: none;
        }

        .btn-success:hover {
            background-color: #1e7e34;
            transform: scale(1.1);
        }

        .features {
            display: flex;
            justify-content: center;
            margin-top: 40px;
            text-align: center;
            gap: 30px;
        }

        .feature-item {
            max-width: 200px;
        }

        .feature-icon {
    font-size: 3rem;
    color: white; /* Mengubah warna ikon menjadi putih */
    margin-bottom: 15px;
}

        .btn-container {
             display: flex;
            justify-content: center;
            gap: 15px; /* Jarak antar tombol */
            margin-top: 20px;
        }

    </style>
</head>
<body>
    <div class="hero">
        <h1>Welcome to Poliklinik</h1>
        <p>Your health is our priority. Experience quality healthcare with us.</p>
        <div class="btn-container">
             <a href="<?= site_url('login'); ?>" class="btn btn-primary btn-custom">Login</a>
            <a href="<?= site_url('pasien/register'); ?>" class="btn btn-success btn-custom">Register</a>
        </div>
        <div class="features">
            <div class="feature-item">
                <i class="fa fa-user-md feature-icon"></i>
                <p>Professional Doctors</p>
            </div>
            <div class="feature-item">
                <i class="fa fa-hospital feature-icon"></i>
                <p>Modern Facilities</p>
            </div>
            <div class="feature-item">
                <i class="fa fa-heartbeat feature-icon"></i>
                <p>Comprehensive Care</p>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
</body>
</html>
