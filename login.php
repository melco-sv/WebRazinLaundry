<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "phpmailer");

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['pw'];

    $qry = $koneksi->query("SELECT * FROM data WHERE email='$email' AND password='$password'");
    $cek = $qry->num_rows;

    if ($cek > 0) {
        $verif = $qry->fetch_assoc();
        if ($verif['is_verif'] == 1) {
            $_SESSION['user'] = $verif;
            echo "<script>alert('Login Berhasil!');window.location='index.php';</script>";
        } else {
            echo "<script>alert('Harap Verifikasi Akun Anda!');window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('Email atau password salah!');window.location='login.php';</script>";
    }
}
?>

<form action="" method="post" autocomplete="off">
  <div>
    <label>Email</label>
    <input type="email" name="email" autocomplete="off">
  </div>
  <div>
    <label>Password</label>
    <input type="password" name="pw" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly')">
  </div>
  <button type="submit" name="login">Login</button>
  <p>Belum Memiliki akun? <a href="register.php">Daftar!</a></p>
</form>

<script>
document.querySelector('input[type="password"]').addEventListener('focus', function() {
    this.removeAttribute('readonly');
});
</script>