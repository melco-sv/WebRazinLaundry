<php
    sessions_start();
    if(!$SESSIOM['user']){
    header('location:login.php');
    }

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Landing Page">
    <meta name="author" content="Nama Anda">
    <title>Landing Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <header>
        <div class="container">
            <h1 class="logo">Razin Laundry</h1>
            <nav>
                <ul>
                    <li><a href="#tracking">Tracking Pesanan Anda</a></li>
                    <li><a href="#about">Tentang Kami</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h2>Selamat Datang di Razin Laundry</h2>
            <p>Mencuci Cepat dan Bersih uhuy</p>
            <a href="#about" class="btn">Tentang Kami</a>
        </div>
    </section>

    <section id="tracking" class="tracking">
        <div class="container">
            <h2>Tracking Pesanan Anda</h2>
            <p>Masukkan nomor invoice atau kode pesanan Anda untuk melacak status pesanan.</p>

            <div class="tracking-form">
                <input type="text" id="invoice-number" placeholder="Masukkan Nomor Invoice" required>
                <button id="track-button" class="btn">Lacak Pesanan</button>
            </div>

            <!-- Menampilkan hasil tracking -->
            <div id="tracking-status" style="display:none;">
                <h3>Status Pesanan: <span id="status"></span></h3>
                <div id="status-details">
                    <p><strong>Waktu Proses:</strong> <span id="process-time"></span></p>
                    <p><strong>Alamat Pengiriman:</strong> <span id="delivery-address"></span></p>
                </div>
            </div>
        </div>
    </section>
    
    <section id="features" class="features">
        <div class="container">
            <h2>Fitur Kami</h2>
            <div class="feature-item">
                <h3>Fitur 1</h3>
                <p>Deskripsi singkat tentang fitur pertama yang ditawarkan.</p>
            </div>
            <div class="feature-item">
                <h3>Fitur 2</h3>
                <p>Deskripsi singkat tentang fitur kedua yang ditawarkan.</p>
            </div>
            <div class="feature-item">
                <h3>Fitur 3</h3>
                <p>Deskripsi singkat tentang fitur ketiga yang ditawarkan.</p>
            </div>
        </div>
    </section>

    <section id="about" class="about">
        <div class="container">
            <h2>Tentang Kami</h2>
            <p>Selamat datang di Razin Laundry! Kami hadir untuk memberikan layanan laundry terbaik dengan kualitas tinggi, cepat, dan terpercaya. Dengan teknologi canggih dan bahan ramah lingkungan, kami menjaga kualitas pakaian Anda.
Kami menawarkan berbagai layanan, mulai dari laundry pakaian, sepatu, hingga cuci karpet, serta layanan antar jemput untuk kenyamanan Anda. Di [Nama Laundry], pelanggan selalu menjadi prioritas, dan kami berkomitmen memberikan pengalaman terbaik setiap saat.
</p>
        </div>
    </section>

    <footer>
    <div class="footer-container">
        <!-- Bagian Kiri: Hak Cipta -->
        <div class="footer-left">
            <p>&copy; Copyright 2025 All rights Reserved with Razin Laundry.</p>
        </div>

        <!-- Bagian Kanan: Kontak -->
        <div class="footer-right">
            <h3>Kontak Kami</h3>
            <ul class="social-links">
                <li><a href="https://wa.me/62878708675313" target="_blank">📞 Hub. WhatsApp</a></li>
                <li><a href="https://twitter.com" target="_blank">🐦 Twitter</a></li>
                <li><a href="https://facebook.com" target="_blank">📘 Facebook</a></li>
                <li><a href="https://instagram.com" target="_blank">📸 Instagram</a></li>
            </ul>
            <p><strong>Alamat:</strong> Gg. Swadaya No.69 Bakti Jaya Kec. Setu Kota Tangerang Selatan, Banten 15315</p>
        </div>
    </div>
</footer>

<script>
        document.getElementById('track-button').addEventListener('click', function () {
            let invoiceNumber = document.getElementById('invoice-number').value;
            trackOrder(invoiceNumber);
        });

        function trackOrder(invoiceNumber) {
            // Simulasi data untuk status pelacakan
            let orderStatus = "Sedang Diproses";
            let processTime = "2 jam lagi";
            let deliveryAddress = "Gg. Swadaya No.69 Bakti Jaya Kec. Setu Kota Tangerang Selatan, Banten 15315";

            // Menampilkan status pelacakan
            document.getElementById('status').textContent = orderStatus;
            document.getElementById('process-time').textContent = processTime;
            document.getElementById('delivery-address').textContent = deliveryAddress;

            // Menampilkan status tracking
            document.getElementById('tracking-status').style.display = 'block';
        }
    </script>

</body>
</html>

