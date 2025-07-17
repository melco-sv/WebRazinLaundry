<?php
session_start();
// Pastikan pengguna sudah login, jika tidak, arahkan ke halaman login
if (!isset($_SESSION['user'])) {
    header('location:login.php');
    exit();
}

include 'koneksi.php'; // Sertakan file koneksi database

// Inisialisasi variabel tanggal
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

$transactions = [];
$total_revenue = 0;
$total_transactions_count = 0;

// Query untuk mengambil data transaksi berdasarkan filter tanggal
$sql = "SELECT id_transaksi, layanan, berat, bed_cover, sprei, selimut, karpet, total_harga, tanggal, nama, no_hp, status_proses, status_pembayaran
        FROM transaksi
        WHERE tanggal BETWEEN ? AND ?
        ORDER BY tanggal DESC";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
            $total_revenue += $row['total_harga'];
            $total_transactions_count++;
        }
    }
    $stmt->close();
} else {
    // Handle error jika prepare statement gagal
    echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
}

// Jika action adalah download CSV
if (isset($_GET['action']) && $_GET['action'] == 'download') {
    // Set header untuk download CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="laporan_transaksi_' . $start_date . '_sampai_' . $end_date . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Buka output stream
    $output = fopen('php://output', 'w');

    // Tulis header CSV
    fputcsv($output, [
        'ID Transaksi',
        'Tanggal',
        'Nama Pelanggan',
        'No. HP',
        'Layanan',
        'Berat (kg)',
        'Bed Cover',
        'Sprei',
        'Selimut',
        'Karpet',
        'Total Harga',
        'Status Proses',
        'Status Pembayaran'
    ]);

    // Tulis data transaksi
    foreach ($transactions as $transaction) {
        $status_proses_text = '';
        switch ($transaction['status_proses']) {
            case 0: $status_proses_text = 'On Progress'; break;
            case 1: $status_proses_text = 'Dicuci'; break;
            case 2: $status_proses_text = 'Selesai'; break;
            default: $status_proses_text = 'Tidak Diketahui'; break;
        }

        $status_pembayaran_text = '';
        switch ($transaction['status_pembayaran']) {
            case 0: $status_pembayaran_text = 'Belum Dibayar'; break;
            case 1: $status_pembayaran_text = 'Sudah Dibayar'; break;
            default: $status_pembayaran_text = 'Tidak Diketahui'; break;
        }

        fputcsv($output, [
            $transaction['id_transaksi'],
            $transaction['tanggal'],
            $transaction['nama'],
            $transaction['no_hp'],
            $transaction['layanan'],
            $transaction['berat'],
            $transaction['bed_cover'],
            $transaction['sprei'],
            $transaction['selimut'],
            $transaction['karpet'],
            $transaction['total_harga'],
            $status_proses_text,
            $status_pembayaran_text
        ]);
    }

// Tulis baris total
fputcsv($output, []); // Baris kosong sebagai pemisah
fputcsv($output, ['TOTAL PENDAPATAN (RIBUAN):', number_format($total_revenue, 0, ',', '.' )]);
fputcsv($output, ['TOTAL TRANSAKSI:', $total_transactions_count]);
    // Tutup output stream
    fclose($output);
    exit(); // Penting untuk menghentikan eksekusi script setelah download
}

$conn->close();

// Fungsi untuk mendapatkan status proses dengan class CSS (untuk tampilan HTML)
function getStatusProses($status_code) {
    switch ($status_code) {
        case 0: return '<span class="status-on-progress">On Progress</span>';
        case 1: return '<span class="status-dicuci">Dicuci</span>';
        case 2: return '<span class="status-selesai">Selesai</span>';
        default: return '<span class="status-unknown">Tidak Diketahui</span>';
    }
}

// Fungsi untuk mendapatkan status pembayaran dengan class CSS (untuk tampilan HTML)
function getStatusPembayaran($status_code) {
    switch ($status_code) {
        case 0: return '<span class="status-belum-bayar">Belum Dibayar</span>';
        case 1: return '<span class="status-sudah-bayar">Sudah Dibayar</span>';
        default: return '<span class="status-unknown">Tidak Diketahui</span>';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Razin Laundry</title>
    <link rel="stylesheet" href="style/sidebar.css">
    <link rel="stylesheet" href="style/laporan.css"> <!-- CSS khusus untuk laporan -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* CSS untuk memperpendek lebar input tanggal */
        input[type="date"] {
            width: 150px; /* Atur lebar sesuai kebutuhan */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Responsif untuk input tanggal */
        @media (max-width: 768px) {
            input[type="date"] {
                width: 100%; /* Lebar penuh pada layar kecil */
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <?php include 'sidebar.php'; // Sertakan sidebar ?>

        <div class="main-content-column">
            <div class="header">
                <h1>Laporan Transaksi</h1>
            </div>

            <div class="container">
                <div class="report-filter-section">
                    <h2>Filter Laporan Berdasarkan Tanggal</h2>
                    <form method="GET" action="laporan.php" class="filter-form">
                        <div class="form-group">
                            <label for="start_date">Dari Tanggal:</label>
                            <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date">Sampai Tanggal:</label>
                            <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" required>
                        </div>
                        <div class="btn-group">
                            <button type="submit" name="action" value="view" class="btn-filter"><i class="fas fa-eye"></i> Tampilkan Laporan</button>
                            <button type="submit" name="action" value="download" class="btn-download"><i class="fas fa-download"></i> Download CSV</button>
                        </div>
                    </form>
                </div>

              <div class="report-summary-section">
    <h2>Ringkasan Laporan (<?php echo htmlspecialchars($start_date); ?> s/d <?php echo htmlspecialchars($end_date); ?>)</h2>
    <div class="summary-cards">
        <div class="summary-card total-transactions">
            <h3>Total Transaksi</h3>
            <p><?php echo $total_transactions_count; ?></p>
        </div>
        <div class="summary-card total-revenue">
            <h3>Total Pendapatan</h3>
            <p>Rp <?php echo number_format($total_revenue, 0, ',', '.'); ?></p> <!-- Format ribuan -->
        </div>
    </div>
</div>

                <div class="report-detail-section">
                    <h2>Detail Transaksi</h2>
                    <?php if (!empty($transactions)): ?>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Nama Pelanggan</th>
                                        <th>No. HP</th>
                                        <th>Layanan</th>
                                        <th>Berat (kg)</th>
                                        <th>Bed Cover</th>
                                        <th>Sprei</th>
                                        <th>Selimut</th>
                                        <th>Karpet</th>
                                        <th>Total Harga</th>
                                        <th>Status Proses</th>
                                        <th>Status Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transactions as $transaction): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($transaction['id_transaksi']); ?></td>
                                            <td><?php echo htmlspecialchars($transaction['tanggal']); ?></td>
                                            <td><?php echo htmlspecialchars($transaction['nama']); ?></td>
                                            <td><?php echo htmlspecialchars($transaction['no_hp']); ?></td>
                                            <td><?php echo htmlspecialchars($transaction['layanan']); ?></td>
                                            <td><?php echo htmlspecialchars($transaction['berat']); ?></td>
                                            <td><?php echo htmlspecialchars($transaction['bed_cover']); ?></td>
                                            <td><?php echo htmlspecialchars($transaction['sprei']); ?></td>
                                            <td><?php echo htmlspecialchars($transaction['selimut']); ?></td>
                                            <td><?php echo htmlspecialchars($transaction['karpet']); ?></td>
                                            <td>Rp <?php echo number_format($transaction['total_harga'], 0, ',', '.'); ?></td>
                                            <td><?php echo getStatusProses($transaction['status_proses']); ?></td>
                                            <td><?php echo getStatusPembayaran($transaction['status_pembayaran']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="no-data-message">
                            <p>Tidak ada transaksi dalam rentang tanggal yang dipilih.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
