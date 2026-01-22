<?php
    session_start();
    include '../koneksi.php';

    if (! isset($_SESSION['username']) || $_SESSION['level'] != 'admin') {
    header("Location: ../index.php");
    exit();
    }

    if (isset($_POST['generate_mahasiswa'])) {
    $nim = mysqli_real_escape_string($koneksi, $_POST['nim']);

    $data_mhs = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nim, nama FROM mahasiswa WHERE nim='$nim'"));

    if (! $data_mhs) {
        $error_msg = "NIM tidak ditemukan!";
    } else {
        $username = substr($nim, 0, 4);
        $password = md5($nim);
        $level    = 'mahasiswa';

        $cek = mysqli_query($koneksi, "SELECT id FROM user WHERE username='$username'");
        if (mysqli_num_rows($cek) > 0) {
            $error_msg = "Username sudah terdaftar! (Username: $username)";
        } else {
            $query = "INSERT INTO user (username, password, level)
                     VALUES ('$username', '$password', '$level')";
            if (mysqli_query($koneksi, $query)) {
                $success_msg = "User mahasiswa berhasil dibuat! Username: $username, Password: $nim";
            } else {
                $error_msg = "Error: " . mysqli_error($koneksi);
            }
        }
    }
    }

    if (isset($_POST['generate_dosen'])) {
    $nidn = mysqli_real_escape_string($koneksi, $_POST['nidn']);

    $data_dosen = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nidn, nama FROM dosen WHERE nidn='$nidn'"));

    if (! $data_dosen) {
        $error_msg = "NIDN tidak ditemukan!";
    } else {
        $username = substr($nidn, 0, 4);
        $password = md5($nidn);
        $level    = 'dosen';

        $cek = mysqli_query($koneksi, "SELECT id FROM user WHERE username='$username'");
        if (mysqli_num_rows($cek) > 0) {
            $error_msg = "Username sudah terdaftar! (Username: $username)";
        } else {
            $query = "INSERT INTO user (username, password, level)
                     VALUES ('$username', '$password', '$level')";
            if (mysqli_query($koneksi, $query)) {
                $success_msg = "User dosen berhasil dibuat! Username: $username, Password: $nidn";
            } else {
                $error_msg = "Error: " . mysqli_error($koneksi);
            }
        }
    }
    }

    if (isset($_POST['reset_password'])) {
    $user_id       = mysqli_real_escape_string($koneksi, $_POST['user_id']);
    $password_baru = mysqli_real_escape_string($koneksi, $_POST['password_baru']);

    $query = "UPDATE user SET password='" . md5($password_baru) . "' WHERE id='$user_id'";
    if (mysqli_query($koneksi, $query)) {
        $success_msg = "Password berhasil direset menjadi: $password_baru";
    } else {
        $error_msg = "Error: " . mysqli_error($koneksi);
    }
    }

    if (isset($_GET['hapus'])) {
    $user_id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    if (mysqli_query($koneksi, "DELETE FROM user WHERE id='$user_id'")) {
        $success_msg = "User berhasil dihapus!";
    } else {
        $error_msg = "Error: " . mysqli_error($koneksi);
    }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User | SIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f6f9fc;
        }

        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.5);
        }

        .navbar {
            border-radius: 0 0 15px 15px;
        }

        .btn-add {
            margin-bottom: 15px
        }

        .nav-tabs .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
        }

        .nav-tabs .nav-link {
            color: #0d6efd;
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand">SIA</a>
            <div class="d-flex">
                <a href="index.php" class="btn btn-light btn-sm me-2">Dashboard</a>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="table-container">
            <h3><i class="fas fa-users-cog"></i> Kelola User</h3>

            <?php if (isset($error_msg)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($error_msg); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($success_msg)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($success_msg); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="daftar-tab" data-bs-toggle="tab" data-bs-target="#daftar" type="button" role="tab">
                        <i class="fas fa-list"></i> Daftar User
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="gen-mhs-tab" data-bs-toggle="tab" data-bs-target="#gen-mhs" type="button" role="tab">
                        <i class="fas fa-user-plus"></i> Buat User Mahasiswa
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="gen-dosen-tab" data-bs-toggle="tab" data-bs-target="#gen-dosen" type="button" role="tab">
                        <i class="fas fa-user-plus"></i> Buat User Dosen
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Tab 1: Daftar User -->
                <div class="tab-pane fade show active" id="daftar" role="tabpanel">
                    <h5 class="mb-3">Daftar User Terdaftar</h5>
                    <table class="table table-striped table-bordered">
                        <thead class="table-primary">
                            <tr class="text-center">
                                <td>ID</td>
                                <td>Username</td>

                                <td></td>Level</td>
                                <td>Aksi</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $data = mysqli_query($koneksi, "SELECT * FROM user ORDER BY id ASC");
                                if (mysqli_num_rows($data) == 0) {
                                    echo "<tr><td colspan='5' class='text-center'>Tidak ada user</td></tr>";
                                } else {
                                    while ($row = mysqli_fetch_assoc($data)) {
                                        $badge = '';
                                        if ($row['level'] == 'admin') {
                                            $badge = '<span class="badge bg-danger">Admin</span>';
                                        } elseif ($row['level'] == 'dosen') {
                                            $badge = '<span class="badge bg-warning">Dosen</span>';
                                        } else {
                                            $badge = '<span class="badge bg-info">Mahasiswa</span>';
                                        }
                                        echo "
                <tr>
                    <td class='text-center'>{$row['id']}</td>
                    <td>{$row['username']}</td>
                    <td class='text-center'>{$badge}</td>
                    <td class='text-center'>
                        <button class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#modalReset{$row['id']}'>
                            Reset Pass
                        </button>
                        <a href='?hapus={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin hapus user?')\">Hapus</a>
                    </td>
                </tr>";

                                        echo "
<div class='modal fade' id='modalReset{$row['id']}'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header bg-info'>
        <h5 class='modal-title'>Reset Password - {$row['username']}</h5>
        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
      </div>
      <form method='POST'>
        <div class='modal-body'>
          <input type='hidden' name='user_id' value='{$row['id']}'>
          <div class='mb-3'>
            <label>Password Baru</label>
            <input type='text' name='password_baru' class='form-control' placeholder='Masukkan password baru' required>
          </div>
        </div>
        <div class='modal-footer'>
          <button type='submit' name='reset_password' class='btn btn-primary'>Ubah Password</button>
        </div>
      </form>
    </div>
  </div>
</div>
";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="gen-mhs" role="tabpanel">
                    <h5 class="mb-3">Buat User dari Data Mahasiswa</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <form method="POST">
                                <div class="mb-3">
                                    <label>Pilih Mahasiswa</label>
                                    <select name="nim" class="form-control" required>
                                        <option value="">-- Pilih Mahasiswa --</option>
                                        <?php
                                            $mhs = mysqli_query($koneksi, "SELECT nim, nama FROM mahasiswa ORDER BY nim ASC");
                                            while ($m = mysqli_fetch_assoc($mhs)) {
                                                $cek_user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id FROM user WHERE username='" . substr($m['nim'], 0, 4) . "'"));
                                                $disabled = $cek_user ? ' (Sudah ada user)' : '';
                                                echo "<option value='{$m['nim']}'>{$m['nim']} - {$m['nama']}{$disabled}</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" name="generate_mahasiswa" class="btn btn-success w-100">
                                    <i class="fas fa-user-plus"></i> Buat User Mahasiswa
                                </button>
                            </form>

                            <div class="alert alert-info mt-3" role="alert">
                                <strong>Informasi:</strong><br>
                                - Username: 4 digit depan dari NIM<br>
                                - Password: NIM lengkap<br>
                                - Level: Mahasiswa
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="gen-dosen" role="tabpanel">
                    <h5 class="mb-3">Buat User dari Data Dosen</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <form method="POST">
                                <div class="mb-3">
                                    <label>Pilih Dosen</label>
                                    <select name="nidn" class="form-control" required>
                                        <option value="">-- Pilih Dosen --</option>
                                        <?php
                                            $dosen = mysqli_query($koneksi, "SELECT nidn, nama FROM dosen ORDER BY nidn ASC");
                                            while ($d = mysqli_fetch_assoc($dosen)) {
                                                $cek_user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id FROM user WHERE username='" . substr($d['nidn'], 0, 4) . "'"));
                                                $disabled = $cek_user ? ' (Sudah ada user)' : '';
                                                echo "<option value='{$d['nidn']}'>{$d['nidn']} - {$d['nama']}{$disabled}</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" name="generate_dosen" class="btn btn-success w-100">
                                    <i class="fas fa-user-plus"></i> Buat User Dosen
                                </button>
                            </form>

                            <div class="alert alert-info mt-3" role="alert">
                                <strong>Informasi:</strong><br>
                                - Username: 4 digit depan dari NIDN<br>
                                - Password: NIDN lengkap<br>
                                - Level: Dosen
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>
