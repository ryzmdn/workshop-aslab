<?php
    session_start();
    include '../koneksi.php';

    if (! isset($_SESSION['username']) || $_SESSION['level'] != 'admin') {
    header("Location: ../index.php");
    exit();
    }

    if (isset($_POST['tambah'])) {
    $nim         = mysqli_real_escape_string($koneksi, $_POST['nim']);
    $kode_matkul = mysqli_real_escape_string($koneksi, $_POST['kode_matkul']);
    $nilai       = mysqli_real_escape_string($koneksi, $_POST['nilai']);

    if (empty($nim) || empty($kode_matkul) || empty($nilai)) {
        $error_msg = "Semua field harus diisi!";
    } else {
        $cek_nim = mysqli_query($koneksi, "SELECT nim FROM mahasiswa WHERE nim='$nim'");
        if (mysqli_num_rows($cek_nim) == 0) {
            $error_msg = "NIM mahasiswa tidak ditemukan!";
        } else {
            $cek_matkul = mysqli_query($koneksi, "SELECT kode_matkul FROM matkul WHERE kode_matkul='$kode_matkul'");
            if (mysqli_num_rows($cek_matkul) == 0) {
                $error_msg = "Kode mata kuliah tidak ditemukan!";
            } else {
                $query = "INSERT INTO nilai (nim, kode_matkul, nilai)
                                 VALUES ('$nim', '$kode_matkul', '$nilai')";
                if (mysqli_query($koneksi, $query)) {
                    $success_msg = "Data nilai berhasil ditambahkan!";
                } else {
                    $error_msg = "Error: " . mysqli_error($koneksi);
                }
            }
        }
    }
    }

    if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM nilai WHERE id = '$id'");
    header("Location: nilai.php");
    exit();
    }

    if (isset($_POST['update'])) {
    $id          = mysqli_real_escape_string($koneksi, $_POST['id']);
    $nim         = mysqli_real_escape_string($koneksi, $_POST['nim']);
    $kode_matkul = mysqli_real_escape_string($koneksi, $_POST['kode_matkul']);
    $nilai       = mysqli_real_escape_string($koneksi, $_POST['nilai']);

    mysqli_query(
        $koneksi,
        "UPDATE nilai SET
            nim='$nim',
            kode_matkul='$kode_matkul',
            nilai='$nilai'
         WHERE id='$id'"
    );

    header("Location: nilai.php");
    exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Nilai | SIA</title>
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
            <h3>Data Nilai</h3>

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
                + Tambah Nilai
            </button>

            <table class="table table-striped table-bordered">
                <thead class="table-primary">
                    <tr class="text-center">
                        <td>No.</td>
                        <td>NIM</td>
                        <td>Nama Mahasiswa</td>
                        <td>Kode Mata Kuliah</td>
                        <td>Nama Mata Kuliah</td>
                        <td>Nilai</td>
                        <td>Aksi</td>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        $data = mysqli_query($koneksi, "SELECT n.id, n.nim, n.kode_matkul, n.nilai, m.nama, mk.nama_matkul
                                                        FROM nilai n
                                                        LEFT JOIN mahasiswa m ON n.nim = m.nim
                                                        LEFT JOIN matkul mk ON n.kode_matkul = mk.kode_matkul
                                                        ORDER BY n.id ASC");
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($data)) {
                            echo "
    <tr>
        <td class='text-center'>{$no}</td>
        <td>{$row['nim']}</td>
        <td>{$row['nama']}</td>
        <td>{$row['kode_matkul']}</td>
        <td>{$row['nama_matkul']}</td>
        <td class='text-center'>{$row['nilai']}</td>
        <td class='text-center'>
            <button class='btn btn-warning btn-sm'
        data-bs-toggle='modal'
        data-bs-target='#modalEdit{$row['id']}'>
    Edit
</button>

            <a href='?hapus={$row['id']}' class='btn btn-danger btn-sm'
               onclick=\"return confirm('Yakin hapus data?')\">Hapus</a>
        </td>
    </tr>";

                            echo "
<div class='modal fade' id='modalEdit{$row['id']}'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header bg-warning'>
        <h5 class='modal-title'>Edit Data Nilai</h5>
        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
      </div>

      <form method='POST'>
        <div class='modal-body'>
          <input type='hidden' name='id' value='{$row['id']}'>

          <div class='mb-3'>
            <label>NIM</label>
            <select name='nim' class='form-control' required>
              <option value='{$row['nim']}'>{$row['nim']} - {$row['nama']}</option>";
                            $mahasiswa = mysqli_query($koneksi, "SELECT nim, nama FROM mahasiswa ORDER BY nim ASC");
                            while ($m = mysqli_fetch_assoc($mahasiswa)) {
                                if ($m['nim'] != $row['nim']) {
                                    echo "<option value='{$m['nim']}'>{$m['nim']} - {$m['nama']}</option>";
                                }
                            }
                            echo "</select>
          </div>

          <div class='mb-3'>
            <label>Kode Mata Kuliah</label>
            <select name='kode_matkul' class='form-control' required>
              <option value='{$row['kode_matkul']}'>{$row['kode_matkul']} - {$row['nama_matkul']}</option>";
                            $matkul = mysqli_query($koneksi, "SELECT kode_matkul, nama_matkul FROM matkul ORDER BY kode_matkul ASC");
                            while ($mk = mysqli_fetch_assoc($matkul)) {
                                if ($mk['kode_matkul'] != $row['kode_matkul']) {
                                    echo "<option value='{$mk['kode_matkul']}'>{$mk['kode_matkul']} - {$mk['nama_matkul']}</option>";
                                }
                            }
                            echo "</select>
          </div>

          <div class='mb-3'>
            <label>Nilai</label>
            <input type='text' name='nilai' class='form-control' value='{$row['nilai']}' required>
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
                            $no++;
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
                    <h5 class="modal-title">Tambah Nilai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST">
                    <div class="modal-body">

                        <div class="mb-3">
                            <label>NIM Mahasiswa</label>
                            <select name="nim" class="form-control" required>
                                <option value="">Pilih Mahasiswa</option>
                                <?php
                                    $mahasiswa = mysqli_query($koneksi, "SELECT nim, nama FROM mahasiswa ORDER BY nim ASC");
                                    while ($m = mysqli_fetch_assoc($mahasiswa)) {
                                        echo "<option value='{$m['nim']}'>{$m['nim']} - {$m['nama']}</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Kode Mata Kuliah</label>
                            <select name="kode_matkul" class="form-control" required>
                                <option value="">Pilih Mata Kuliah</option>
                                <?php
                                    $matkul = mysqli_query($koneksi, "SELECT kode_matkul, nama_matkul FROM matkul ORDER BY kode_matkul ASC");
                                    while ($mk = mysqli_fetch_assoc($matkul)) {
                                        echo "<option value='{$mk['kode_matkul']}'>{$mk['kode_matkul']} - {$mk['nama_matkul']}</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Nilai</label>
                            <input type="text" name="nilai" class="form-control" placeholder="Contoh: A, B, C, D, E" required>
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
