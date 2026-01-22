<?php
    session_start();
    include '../koneksi.php';

    if (! isset($_SESSION['username']) || $_SESSION['level'] != 'dosen') {
    header("Location: ../index.php");
    exit();
    }

    if (isset($_POST['tambah'])) {
    $nidn = mysqli_real_escape_string($koneksi, $_POST['nidn']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);

    $query = "INSERT INTO dosen (nidn, nama) VALUES ('$nidn', '$nama')";
    mysqli_query($koneksi, $query);
    header("Location: dosen.php");
    exit();
    }

    if (isset($_GET['hapus'])) {
    $nidn = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM dosen WHERE nidn='$nidn'");
    header("Location: dosen.php");
    exit();
    }

    if (isset($_POST['update'])) {
    $nidn = mysqli_real_escape_string($koneksi, $_POST['nidn']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);

    mysqli_query(
        $koneksi,
        "UPDATE dosen SET
            nama='$nama'
         WHERE nidn='$nidn'"
    );

    header("Location: dosen.php");
    exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Dosen | SIA</title>
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
            <h3>Data Dosen</h3>
            <button class="btn btn-success btn-add"
                data-bs-toggle="modal"
                data-bs-target="#modalTambah">
                + Tambah Dosen
            </button>

            <table class="table table-striped table-bordered">
                <thead class="table-primary">
                    <tr class="text-center">
                        <td>NIDN</td>
                        <td>Nama</td>
                        <td>Aksi</td>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        $data = mysqli_query($koneksi, "SELECT * FROM dosen ORDER BY nidn ASC");
                        while ($row = mysqli_fetch_assoc($data)) {
                            echo "
    <tr>
        <td>{$row['nidn']}</td>
        <td>{$row['nama']}</td>
        <td class='text-center'>
            <button class='btn btn-warning btn-sm'
        data-bs-toggle='modal'
        data-bs-target='#modalEdit{$row['nidn']}'>
    Edit
</button>

            <a href='?hapus={$row['nidn']}' class='btn btn-danger btn-sm'
               onclick=\"return confirm('Yakin hapus data?')\">Hapus</a>
        </td>
    </tr>";

                            echo "
<div class='modal fade' id='modalEdit{$row['nidn']}'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header bg-warning'>
        <h5 class='modal-title'>Edit Data Dosen</h5>
        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
      </div>

      <form method='POST'>
        <div class='modal-body'>
          <input type='hidden' name='nidn' value='{$row['nidn']}'>

          <div class='mb-3'>
            <label>Nama</label>
            <input type='text' name='nama' class='form-control' value='{$row['nama']}' required>
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
                    <h5 class="modal-title">Tambah Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST">
                    <div class="modal-body">

                        <div class="mb-3">
                            <label>NIDN</label>
                            <input type="text" name="nidn" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" required>
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
