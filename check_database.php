<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Koneksi database
$koneksi = mysqli_connect("localhost", "root", "", "phpmailer");
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

echo "<h2>Informasi Database</h2>";

// Cek apakah tabel data ada
$result = $koneksi->query("SHOW TABLES LIKE 'data'");
if ($result->num_rows > 0) {
    echo "✓ Tabel 'data' ditemukan<br>";

    // Cek struktur tabel
    $result = $koneksi->query("DESCRIBE data");
    echo "<h3>Struktur Tabel 'data':</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Cek data yang ada
    $result = $koneksi->query("SELECT id, nama, email, verifikasi_code, is_verified FROM data LIMIT 5");
    echo "<h3>Data Terbaru (maksimal 5):</h3>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nama</th><th>Email</th><th>Kode Verifikasi</th><th>Status Verifikasi</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['nama'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . substr($row['verifikasi_code'], 0, 20) . "...</td>";
        echo "<td>" . ($row['is_verified'] ? 'Sudah' : 'Belum') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "✗ Tabel 'data' tidak ditemukan<br>";
    echo "<h3>Membuat tabel 'data':</h3>";

    $sql = "CREATE TABLE data (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        verifikasi_code VARCHAR(255) NOT NULL,
        is_verified TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($koneksi->query($sql) === TRUE) {
        echo "✓ Tabel 'data' berhasil dibuat<br>";
    } else {
        echo "✗ Error membuat tabel: " . $koneksi->error . "<br>";
    }
}

mysqli_close($koneksi);
