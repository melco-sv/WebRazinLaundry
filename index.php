<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Landing Page">
    <title>Landing Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Header -->
    <header>
        <div class="container">
            <h1 class="logo">Razin Laundry</h1>
            <nav>
                <ul>
                    <li><a href="#features">Tracking Pesanan</a></li>
                    <li><a href="#about">Tentang Kami</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h2>Selamat Datang di Razin Laundry!</h2>
            <p>Waktu Anda Berharga, Kami Cuci dengan Cepat dan Bersih!</p>
            <a href="#about" class="btn">Kenali Kami Lebih Dekat</a>
        </div>
    </section>

    <!-- Order Tracking Section -->
    <section id="features" class="features">
    <div class="container"></div>
        <h2>Order Tracking</h2>
        <form id="trackingForm" method="POST" action="">
            <div class="form-group">
                <h3><label for="id_tracking">Masukkan ID Tracking:</label></h3>
                <input type="text" id="id_tracking" name="id_tracking" class="form-control" required>
            </div>
            <input type="submit" value="Cari" class="btn btn-primary">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id_tracking = $_POST['id_tracking'];

            // Koneksi ke database
            $host = "localhost";
            $user = "root";
            $pass = "";
            $dbname = "phpmailer"; // Gantilah nama database sesuai kebutuhan Anda

            $conn = mysqli_connect($host, $user, $pass, $dbname);

            // Cek koneksi
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Query untuk mengambil data berdasarkan ID tracking
            $sql = "SELECT id_transaksi, nama, no_hp, status_proses, status_pembayaran, total_harga 
                    FROM transaksi WHERE id_transaksi = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $id_tracking); // Ganti "s" dengan tipe data yang sesuai
            $stmt->execute();
            $result = $stmt->get_result();

            // Tampilkan data jika ditemukan
            if ($result->num_rows > 0) {
                echo "<table class='table mt-4'>
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Nama Pelanggan</th>
                                <th>No HP</th>
                                <th>Status Progres</th>
                                <th>Status Pembayaran</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>";
                $row = $result->fetch_assoc();

                // Mengubah status_proses menjadi deskripsi dan kelas CSS
                switch ($row['status_proses']) {
                    case 0:
                        $status_proses = "On Progress";
                        $status_proses_class = "status-on-progress";
                        break;
                    case 1:
                        $status_proses = "Dicuci";
                        $status_proses_class = "status-dicuci";
                        break;
                    case 2:
                        $status_proses = "Selesai";
                        $status_proses_class = "status-selesai";
                        break;
                    default:
                        $status_proses = "Tidak Diketahui";
                        $status_proses_class = "";
                }

                // Mengubah status_pembayaran menjadi deskripsi dan kelas CSS
                switch ($row['status_pembayaran']) {
                    case 0:
                        $status_pembayaran = "Belum Dibayar";
                        $status_pembayaran_class = "status-belum-dibayar";
                        break;
                    case 1:
                        $status_pembayaran = "Sudah Dibayar";
                        $status_pembayaran_class = "status-sudah-dibayar";
                        break;
                    default:
                        $status_pembayaran = "Tidak Diketahui";
                        $status_pembayaran_class = "";
                }

                echo "<tr>
                        <td>{$row['id_transaksi']}</td>
                        <td>{$row['nama']}</td>
                        <td>{$row['no_hp']}</td>
                        <td><span class='status-indicator {$status_proses_class}'></span>{$status_proses}</td>
                        <td><span class='status-indicator {$status_pembayaran_class}'></span>{$status_pembayaran}</td>
                        <td>{$row['total_harga']}</td>
                      </tr>";
                echo "</tbody></table>";
            } else {
                echo "<div class='alert alert-warning mt-4'>Data tidak ditemukan untuk ID Tracking: $id_tracking</div>";
            }

            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <h2>Nikmati Layanan Kami</h2>
        <!-- Express Laundry Feature -->
            <div class="feature-item">
                <h3>Laundry Express</h3>
                <p>Melakukan pencucian dengan waktu yang cepat, ideal untuk Anda yang membutuhkan layanan cepat dan efisien.</p>
            </div>
        <!-- Same Day Laundry Feature -->
            <div class="feature-item">
                <h3>Laundry Same Day</h3>
                <p>Proses pencucian dan pengantaran pada hari yang sama, sangat cocok untuk Anda yang membutuhkan layanan segera.</p>
            </div>
        <!-- Regular Laundry Feature -->
            <div class="feature-item">
                <h3>Laundry Reguler</h3>
                <p>Layanan pencucian dengan waktu lebih panjang dan harga lebih terjangkau. Hasil cuci tetap berkualitas tinggi.</p>
            </div>
        </div>
    </section>



    <!-- About Us Section -->
<section id="about" class="about">
    <div class="container">
        <h2>Tentang Kami</h2>
        <p>Selamat datang di <strong>Razin Laundry</strong> tempat di mana kebersihan dan kenyamanan pakaian Anda adalah prioritas kami. Kami menyediakan layanan laundry cepat, berkualitas, dan terpercaya, dengan perhatian penuh pada setiap detail. Dari cuci biasa hingga cuci kering, kami pastikan pakaian Anda kembali segar dan terawat tanpa harus menghabiskan waktu Anda.</p>
        <p>Razin Laundry adalah solusi praktis untuk kebutuhan laundry Anda. Percayakan pakaian Anda kepada kami dan rasakan kenyamanan layanan yang efisien dan ramah. Hubungi kami hari ini dan nikmati kebersihan yang sempurna!</p>
    </div>
</section>


   <!-- Footer -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="footer">
    <div class="footer-item">
        <i class="fas fa-map-marker-alt footer-icon"></i>
        <h4>Our Location</h4>
        <p>Jl. bandung, Bandung, Indonesia</p>
    </div>
    <div class="footer-item">
        <i class="fas fa-envelope footer-icon"></i>
        <h4>Email Us</h4>
        <p>laundryrazin@gmail.com</p>
    </div>
    <div class="footer-item">
        <i class="fas fa-phone-alt footer-icon"></i>
        <h4>Call Us</h4>
        <p>+628 2345 6789</p>
    </div>
</div>


</body>
</html>
