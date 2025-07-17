<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "phpmailer");

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['pw'];

    // Ambil data pengguna berdasarkan email
    $qry = $koneksi->query("SELECT * FROM data WHERE email='$email'");
    $verif = $qry->fetch_assoc();

    if ($verif) {
        // Verifikasi password
        if (password_verify($password, $verif['password'])) {
            if ($verif['is_verif'] == 1) {
                $_SESSION['user'] = $verif;
                echo "<script>alert('Login Berhasil!');window.location='invoice.php';</script>";
            } else {
                echo "<script>alert('Harap Verifikasi Akun Anda!');window.location='login.php';</script>";
            }
        } else {
            echo "<script>alert('Email atau password salah!');window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('Email atau password salah!');window.location='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Laundry App</title>
    <link rel="stylesheet" type="text/css" href="style/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-card">
        <h1 class="auth-title">Masuk ke Akun Anda</h1>
        
        <form action="" method="post" autocomplete="off">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" autocomplete="off" required>
            </div>
            
            <div class="form-group">
                <label for="pw">Password</label>
                <input type="password" name="pw" id="pw" class="form-control" autocomplete="new-password" readonly 
                       onfocus="this.removeAttribute('readonly')" required>
            </div>
            
            <button type="submit" name="login" class="btn">Login</button>
            
        </form>
    </div>

    <script>
        document.getElementById('pw').addEventListener('focus', function() {
            this.removeAttribute('readonly');
        });
    </script>
</body>
</html>
