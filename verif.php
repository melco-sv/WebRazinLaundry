<?php
// Tambahkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Koneksi database dengan penanganan error
$koneksi = mysqli_connect("localhost", "root", "", "phpmailer");
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Ambil kode verifikasi dari URL
$code = isset($_GET['code']) ? trim($_GET['code']) : '';

// Debug: tampilkan kode yang diterima
echo "Kode verifikasi yang diterima: " . htmlspecialchars($code) . "<br>";

if (!empty($code)) {
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $koneksi->prepare("SELECT * FROM data WHERE verifikasi_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // Update status verifikasi
        $update_stmt = $koneksi->prepare("UPDATE data SET is_verif = 1 WHERE id = ?");
        $update_stmt->bind_param("i", $data['id']);

        if ($update_stmt->execute()) {
            echo "<script>alert('Verifikasi Berhasil, Silahkan Login!');window.location='login.php'</script>";
        } else {
            echo "<script>alert('Gagal mengupdate status verifikasi: " . $koneksi->error . "');window.location='login.php'</script>";
        }
        $update_stmt->close();
    } else {
        echo "<script>alert('Kode verifikasi tidak valid atau sudah digunakan!');window.location='login.php'</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('Kode verifikasi tidak ditemukan!');window.location='login.php'</script>";
}

mysqli_close($koneksi);
