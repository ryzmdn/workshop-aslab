<?php
    session_start();
    include '../koneksi.php';

    if (! isset($_SESSION['username']) || $_SESSION['level'] != 'mahasiswa') {
    header("Location: ../index.php");
    exit();
    }

    $username = $_SESSION['username'];

    $nilai_count  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM nilai"))['total'];
    $matkul_count = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM matkul"))['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Mahasiswa | SIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        .card-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #fff;
        }
        .card-title-custom {
            font-size: 1.1rem;
            font-weight: 500;
            color: #fff;
        }
        .dashboard-header {
            margin-bottom: 30px;
        }
        .stat-card {
            padding: 20px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">SIA - Mahasiswa</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">User: <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="dashboard-header">
        <h1 class="mb-2">Dashboard Mahasiswa</h1>
        <p class="text-muted">Sistem Informasi Akademik</p>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body">
                    <div class="card-title-custom mb-3">
                        <i class="fas fa-book"></i> Mata Kuliah
                    </div>
                    <div class="card-number"><?php echo $matkul_count; ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="card-body">
                    <div class="card-title-custom mb-3">
                        <i class="fas fa-star"></i> Nilai
                    </div>
                    <div class="card-number"><?php echo $nilai_count; ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body">
                    <div class="card-title-custom mb-3">
                        <i class="fas fa-graduation-cap"></i> Status
                    </div>
                    <div class="card-number">Aktif</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Sistem</h5>
                </div>
                <div class="card-body">
                    <p><strong>Aplikasi:</strong> Sistem Informasi Akademik (SIA)</p>
                    <p><strong>Role:</strong> Mahasiswa</p>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                    <p><strong>Fitur Tersedia:</strong></p>
                    <ul>
                        <li>Lihat Data Mata Kuliah</li>
                        <li>Lihat Nilai Akademik</li>
                        <li>Lihat Jadwal Kuliah</li>
                        <li>Dan fitur lainnya...</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>