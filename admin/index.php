<?php
    session_start();
    include '../koneksi.php';

    if (! isset($_SESSION['username']) || $_SESSION['level'] != 'admin') {
    header("Location: ../index.php");
    exit();
    }

    $mahasiswa_count = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM mahasiswa"))['total'];
    $dosen_count     = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM dosen"))['total'];
    $matkul_count    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM matkul"))['total'];
    $nilai_count     = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM nilai"))['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link href="bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #3B82F6, #60A5FA);
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
        <a class="navbar-brand" href="#">SIA - Admin</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">User: <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="mahasiswa.php" class="btn btn-light btn-sm me-2">Mahasiswa</a>
            <a href="dosen.php" class="btn btn-light btn-sm me-2">Dosen</a>
            <a href="matkul.php" class="btn btn-light btn-sm me-2">Mata Kuliah</a>
            <a href="nilai.php" class="btn btn-light btn-sm me-2">Nilai</a>
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="dashboard-header">
        <h1 class="mb-2">Dashboard Admin</h1>
        <p class="text-muted">Sistem Informasi Akademik</p>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body">
                    <div class="card-title-custom mb-3">
                        <i class="fas fa-users"></i> Mahasiswa
                    </div>
                    <div class="card-number"><?php echo $mahasiswa_count; ?></div>
                    <a href="mahasiswa.php" class="btn btn-light btn-sm mt-3 w-100">Lihat Detail</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body">
                    <div class="card-title-custom mb-3">
                        <i class="fas fa-chalkboard-user"></i> Dosen
                    </div>
                    <div class="card-number"><?php echo $dosen_count; ?></div>
                    <a href="dosen.php" class="btn btn-light btn-sm mt-3 w-100">Lihat Detail</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body">
                    <div class="card-title-custom mb-3">
                        <i class="fas fa-book"></i> Mata Kuliah
                    </div>
                    <div class="card-number"><?php echo $matkul_count; ?></div>
                    <a href="matkul.php" class="btn btn-light btn-sm mt-3 w-100">Lihat Detail</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="card-body">
                    <div class="card-title-custom mb-3">
                        <i class="fas fa-star"></i> Nilai
                    </div>
                    <div class="card-number"><?php echo $nilai_count; ?></div>
                    <a href="nilai.php" class="btn btn-light btn-sm mt-3 w-100">Lihat Detail</a>
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
                    <p><strong>Role:</strong> Administrator</p>
                    <p><strong>Fitur Tersedia:</strong></p>
                    <ul>
                        <li>Manajemen Data Mahasiswa</li>
                        <li>Manajemen Data Dosen</li>
                        <li>Manajemen Mata Kuliah</li>
                        <li>Manajemen Nilai</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>