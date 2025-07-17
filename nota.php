<?php
session_start();
// Pastikan pengguna sudah login
if (!isset($_SESSION['user'])) {
    header('location:login.php');
    exit();
}

include 'koneksi.php'; // Pastikan file koneksi.php ada dan berfungsi

$id_transaksi = $_GET['id_transaksi'] ?? null;
$transaksi = null;
$error = '';

if ($id_transaksi) {
    $stmt = $conn->prepare("SELECT * FROM transaksi WHERE id_transaksi = ?");
    $stmt->bind_param("s", $id_transaksi); // id_transaksi adalah string karena mt_rand menghasilkan angka besar
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $transaksi = $result->fetch_assoc();
    } else {
        $error = "Nota tidak ditemukan untuk ID Transaksi ini.";
    }
    $stmt->close();
} else {
    $error = "ID Transaksi tidak diberikan.";
}

// Fungsi helper untuk format mata uang
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Harga item tambahan (harus konsisten dengan invoice.php)
$harga_bed_cover = 30000;
$harga_sprei = 8000;
$harga_selimut = 8000;
$harga_karpet = 45000;

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi #<?= htmlspecialchars($id_transaksi) ?></title>
    <link rel="stylesheet" href="style/sidebar.css"> <!-- Jika ingin menggunakan sidebar -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }
        .nota-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            padding: 30px;
            box-sizing: border-box;
            position: relative; /* Untuk tombol print */
        }
        .nota-header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
        }
        .nota-header h1 {
            font-size: 28px;
            color: #6a11cb;
            margin-bottom: 5px;
        }
        .nota-header p {
            font-size: 14px;
            color: #666;
        }
        .nota-details {
            margin-bottom: 25px;
            line-height: 1.8;
            font-size: 15px;
        }
        .nota-details div {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px dashed #eee;
        }
        .nota-details div:last-child {
            border-bottom: none;
        }
        .nota-details strong {
            color: #555;
        }
        .nota-items table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .nota-items th, .nota-items td {
            border: 1px solid #eee;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }
        .nota-items th {
            background-color: #f8f8f8;
            color: #555;
        }
        .nota-items .total-row td {
            font-weight: bold;
            background-color: #e6f7ff;
            color: #007bff;
            font-size: 16px;
        }
        .nota-footer {
            text-align: center;
            font-size: 13px;
            color: #888;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        .btn-print, .btn-back {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn-print {
            background-color: #28a745;
            color: white;
            border: none;
        }
        .btn-print:hover {
            background-color: #218838;
        }
        .btn-back {
            background-color: #007bff;
            color: white;
            border: none;
            margin-left: 10px;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Print specific styles */
        @media print {
            body {
                background-color: #fff;
                padding: 0;
                margin: 0;
            }
            .nota-container {
                box-shadow: none;
                border: none;
                width: auto;
                max-width: none;
                padding: 0;
            }
            .btn-print, .btn-back {
                display: none; /* Hide buttons when printing */
            }
            .main-content-column {
                margin-left: 0 !important; /* Override sidebar margin */
            }
            .app-container {
                display: block;
            }
            .sidebar {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <?php // include 'sidebar.php'; // Anda bisa menyertakan sidebar jika ingin nota muncul di dalam layout aplikasi ?>
        
        <div class="main-content-column">
            <?php if ($error): ?>
                <div class="error-message">
                    <?= $error ?>
                    <br><a href="invoice.php" class="btn-back" style="margin-top:10px;">Kembali ke Form Transaksi</a>
                    <a href="transaksi.php" class="btn-back" style="margin-top:10px;">Lihat Daftar Transaksi</a>
                </div>
            <?php elseif ($transaksi): ?>
                <div class="nota-container">
                    <div class="nota-header">
                        <h1>Razin Laundry</h1>
                        <p>Jl. Contoh No. 123, Kota Anda</p>
                        <p>Telp: 0812-3456-7890 | Email: info@razinlaundry.com</p>
                    </div>

                    <div class="nota-details">
                        <div><strong>ID Transaksi:</strong> <span><?= htmlspecialchars($transaksi['id_transaksi']) ?></span></div>
                        <div><strong>Tanggal:</strong> <span><?= htmlspecialchars(date('d M Y', strtotime($transaksi['tanggal']))) ?></span></div>
                        <div><strong>Nama Pelanggan:</strong> <span><?= htmlspecialchars($transaksi['nama']) ?></span></div>
                        <div><strong>No. HP:</strong> <span><?= htmlspecialchars($transaksi['no_hp']) ?></span></div>
                        <div><strong>Layanan:</strong> <span><?= htmlspecialchars($transaksi['layanan']) ?></span></div>
                    </div>

                    <div class="nota-items">
                        <table>
                            <thead>
                                <tr>
                                    <th>Deskripsi</th>
                                    <th>Qty/Berat</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $subtotal_layanan = 0;
                                // Ambil harga_per_kg dari database lagi untuk akurasi nota
                                $stmt_harga = $conn->prepare("SELECT harga_per_kg FROM harga WHERE layanan = ?");
                                $stmt_harga->bind_param("s", $transaksi['layanan']);
                                $stmt_harga->execute();
                                $result_harga = $stmt_harga->get_result();
                                $harga_layanan_per_kg = 0;
                                if ($result_harga->num_rows > 0) {
                                    $harga_layanan_per_kg = $result_harga->fetch_assoc()['harga_per_kg'];
                                }
                                $stmt_harga->close();

                                if ($transaksi['berat'] > 0): 
                                    $subtotal_layanan = $transaksi['berat'] * $harga_layanan_per_kg;
                                ?>
                                <tr>
                                    <td>Layanan <?= htmlspecialchars($transaksi['layanan']) ?></td>
                                    <td><?= htmlspecialchars($transaksi['berat']) ?> kg</td>
                                    <td><?= formatRupiah($harga_layanan_per_kg) ?></td>
                                    <td><?= formatRupiah($subtotal_layanan) ?></td>
                                </tr>
                                <?php endif; ?>

                                <?php if ($transaksi['bed_cover'] > 0): ?>
                                <tr>
                                    <td>Bed Cover</td>
                                    <td><?= htmlspecialchars($transaksi['bed_cover']) ?></td>
                                    <td><?= formatRupiah($harga_bed_cover) ?></td>
                                    <td><?= formatRupiah($transaksi['bed_cover'] * $harga_bed_cover) ?></td>
                                </tr>
                                <?php endif; ?>

                                <?php if ($transaksi['sprei'] > 0): ?>
                                <tr>
                                    <td>Sprei</td>
                                    <td><?= htmlspecialchars($transaksi['sprei']) ?></td>
                                    <td><?= formatRupiah($harga_sprei) ?></td>
                                    <td><?= formatRupiah($transaksi['sprei'] * $harga_sprei) ?></td>
                                </tr>
                                <?php endif; ?>

                                <?php if ($transaksi['selimut'] > 0): ?>
                                <tr>
                                    <td>Selimut</td>
                                    <td><?= htmlspecialchars($transaksi['selimut']) ?></td>
                                    <td><?= formatRupiah($harga_selimut) ?></td>
                                    <td><?= formatRupiah($transaksi['selimut'] * $harga_selimut) ?></td>
                                </tr>
                                <?php endif; ?>

                                <?php if ($transaksi['karpet'] > 0): ?>
                                <tr>
                                    <td>Karpet</td>
                                    <td><?= htmlspecialchars($transaksi['karpet']) ?></td>
                                    <td><?= formatRupiah($harga_karpet) ?></td>
                                    <td><?= formatRupiah($transaksi['karpet'] * $harga_karpet) ?></td>
                                </tr>
                                <?php endif; ?>

                                <tr class="total-row">
                                    <td colspan="3" style="text-align:right;">Total Pembayaran:</td>
                                    <td><?= formatRupiah($transaksi['total_harga']) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="nota-footer">
                        <p>Terima kasih telah menggunakan jasa Razin Laundry.</p>
                        <p>Mohon simpan nota ini sebagai bukti transaksi.</p>
                    </div>

                    <div style="text-align: center; margin-top: 20px;">
                        <button class="btn-print" onclick="window.print()">Cetak Nota</button>
                        <a href="invoice.php" class="btn-back">Buat Transaksi Baru</a>
                        <a href="transaksi.php" class="btn-back">Lihat Daftar Transaksi</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php
if ($conn) {
    $conn->close();
}
?>
