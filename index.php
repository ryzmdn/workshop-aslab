<?php
    include './koneksi.php';
    session_start();

    $alert_gagal_login = '';

    if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']);

    $query      = "SELECT * FROM user WHERE username='$username' AND password='$password'";
    $result     = mysqli_query($koneksi, $query);
    $lihat_user = mysqli_fetch_assoc($result);

    if ($lihat_user) {
        $_SESSION['username'] = $lihat_user['username'];
        $_SESSION['level']    = $lihat_user['level'];

        if ($lihat_user['level'] == 'admin') {
            header("Location: admin/index.php");
            exit();
        } elseif ($lihat_user['level'] == 'dosen') {
            header("Location: dosen/index.php");
            exit();
        } elseif ($lihat_user['level'] == 'mahasiswa') {
            header("Location: mahasiswa/index.php");
            exit();
        }
    } else {
        $alert_gagal_login = "Username atau Password salah!";
    }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | Sistem Informasi Akademik</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <style>
        body {
            background: linear-gradient(135deg, #3B82F6, #60A5FA);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            width: 350px;
        }
    </style>
</head>

<body>
    <div class="card p-4">
        <h4 class="text-center mb-3 text-primary">
            Login Sistem
        </h4>

        <?php if (isset($alert_gagal_login)): ?>
            <div class="alert alert-danger">
                <?php echo $alert_gagal_login; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" name="login" class="btn btn-primary w-100">
                Login
            </button>
        </form>
    </div>
</body>


</html>
