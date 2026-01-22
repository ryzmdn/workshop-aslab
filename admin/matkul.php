<?php
    session_start();
    include '../koneksi.php';

    if (! isset($_SESSION['username']) || $_SESSION['level'] != 'admin') {
    header("Location: ../index.php");
    exit();
    }

    if (isset($_POST['tambah'])) {
    $kode_matkul = mysqli_real_escape_string($koneksi, $_POST['kode_matkul']);
    $nama_matkul = mysqli_real_escape_string($koneksi, $_POST['nama_matkul']);
    $sks         = mysqli_real_escape_string($koneksi, $_POST['sks']);

    $cek = mysqli_query($koneksi, "SELECT kode_matkul FROM matkul WHERE kode_matkul='$kode_matkul'");
    if (mysqli_num_rows($cek) > 0) {
        $error_msg = "Kode Mata Kuliah sudah terdaftar!";
    } else {
        $query = "INSERT INTO matkul (kode_matkul, nama_matkul, sks)
                         VALUES ('$kode_matkul', '$nama_matkul', '$sks')";
        if (mysqli_query($koneksi, $query)) {
            $success_msg = "Data mata kuliah berhasil ditambahkan!";
        } else {
            $error_msg = "Error: " . mysqli_error($koneksi);
        }
    }
    }

    if (isset($_GET['hapus'])) {
    $kode_matkul = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM matkul WHERE kode_matkul = '$kode_matkul'");
    header("Location: matkul.php");
    exit();
    }

    if (isset($_POST['update'])) {
    $kode_matkul = mysqli_real_escape_string($koneksi, $_POST['kode_matkul']);
    $nama_matkul = mysqli_real_escape_string($koneksi, $_POST['nama_matkul']);
    $sks         = mysqli_real_escape_string($koneksi, $_POST['sks']);

    mysqli_query(
        $koneksi,
        "UPDATE matkul SET
            nama_matkul='$nama_matkul',
            sks='$sks'
         WHERE kode_matkul='$kode_matkul'"
    );

    header("Location: matkul.php");
    exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mata Kuliah | SIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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
            <h3>Data Mata Kuliah</h3>

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

            <button class="btn btn-success btn-add"
                data-bs-toggle="modal"
                data-bs-target="#modalTambah">
                + Tambah Mata Kuliah
            </button>

            <table class="table table-striped table-bordered">
                <thead class="table-primary">
                    <tr class="text-center">
                        <td>Kode Mata Kuliah</td>
                        <td>Nama Mata Kuliah</td>
                        <td>SKS</td>
                        <td>Aksi</td>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        $data = mysqli_query($koneksi, "SELECT * FROM matkul ORDER BY kode_matkul ASC");
                        while ($row = mysqli_fetch_assoc($data)) {
                            echo "
    <tr>
        <td>{$row['kode_matkul']}</td>
        <td>{$row['nama_matkul']}</td>
        <td class='text-center'>{$row['sks']}</td>
        <td class='text-center'>
            <button class='btn btn-warning btn-sm'
        data-bs-toggle='modal'
        data-bs-target='#modalEdit{$row['kode_matkul']}'>
    Edit
</button>

            <a href='?hapus={$row['kode_matkul']}' class='btn btn-danger btn-sm'
               onclick=\"return confirm('Yakin hapus data?')\">Hapus</a>
        </td>
    </tr>";

                            echo "
<div class='modal fade' id='modalEdit{$row['kode_matkul']}'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header bg-warning'>
        <h5 class='modal-title'>Edit Data Mata Kuliah</h5>
        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
      </div>

      <form method='POST'>
        <div class='modal-body'>
          <input type='hidden' name='kode_matkul' value='{$row['kode_matkul']}'>

          <div class='mb-3'>
            <label>Nama Mata Kuliah</label>
            <input type='text' name='nama_matkul' class='form-control' value='{$row['nama_matkul']}' required>
          </div>

          <div class='mb-3'>
            <label>SKS</label>
            <input type='number' name='sks' class='form-control' value='{$row['sks']}' min='1' max='6' required>
          </div>
        </div>

        <div class='modal-footer'>
          <button type='submit' name='update' class='btn btn-primary'>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
";
                        }

                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalTambah">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Tambah Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST">
                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Kode Mata Kuliah</label>
                            <input type="text" name="kode_matkul" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Nama Mata Kuliah</label>
                            <input type="text" name="nama_matkul" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>SKS</label>
                            <input type="number" name="sks" class="form-control" min="1" max="6" required>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="tambah" class="btn btn-success">
                            Simpan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
