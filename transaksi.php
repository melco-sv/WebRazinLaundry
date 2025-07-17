<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location:login.php');
    exit();
}
include 'koneksi.php';

// Fungsi untuk mendapatkan status proses dengan class CSS
function getStatusProses($status_code) {
    switch ($status_code) {
        case 0: return '<span class="status-on-progress">On Progress</span>';
        case 1: return '<span class="status-dicuci">Dicuci</span>';
        case 2: return '<span class="status-selesai">Selesai</span>';
        default: return '<span class="status-unknown">Tidak Diketahui</span>';
    }
}

// Fungsi untuk mendapatkan status pembayaran dengan class CSS
function getStatusPembayaran($status_code) {
    switch ($status_code) {
        case 0: return '<span class="status-belum-bayar">Belum Dibayar</span>';
        case 1: return '<span class="status-sudah-bayar">Sudah Dibayar</span>';
        default: return '<span class="status-unknown">Tidak Diketahui</span>';
    }
}

// --- Handle Update Status Proses ---
if (isset($_POST['update_proses'])) {
    $id_transaksi = $_POST['id_transaksi'];
    $status_proses = $_POST['status_proses'];

    $stmt = $conn->prepare("UPDATE transaksi SET status_proses = ? WHERE id_transaksi = ?");
    $stmt->bind_param("is", $status_proses, $id_transaksi); // 's' karena id_transaksi adalah string
    if ($stmt->execute()) {
        echo "<script>alert('Status proses berhasil diperbarui!');window.location='transaksi.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui status proses: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// --- Handle Update Status Pembayaran ---
if (isset($_POST['update_pembayaran'])) {
    $id_transaksi = $_POST['id_transaksi'];
    $status_pembayaran = $_POST['status_pembayaran'];

    $stmt = $conn->prepare("UPDATE transaksi SET status_pembayaran = ? WHERE id_transaksi = ?");
    $stmt->bind_param("is", $status_pembayaran, $id_transaksi); // 's' karena id_transaksi adalah string
    if ($stmt->execute()) {
        echo "<script>alert('Status pembayaran berhasil diperbarui!');window.location='transaksi.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui status pembayaran: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// --- Ambil Statistik Proses ---
$stats_query = "SELECT 
    SUM(CASE WHEN status_proses = 0 THEN 1 ELSE 0 END) as on_progress,
    SUM(CASE WHEN status_proses = 1 THEN 1 ELSE 0 END) as dicuci,
    SUM(CASE WHEN status_proses = 2 THEN 1 ELSE 0 END) as selesai,
    SUM(CASE WHEN status_pembayaran = 0 THEN 1 ELSE 0 END) as belum_bayar,
    SUM(CASE WHEN status_pembayaran = 1 THEN 1 ELSE 0 END) as sudah_bayar,
    COUNT(*) as total_transaksi
FROM transaksi";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// --- Pagination Logic ---
$records_per_page = 15;
$count_query = "SELECT COUNT(*) as total FROM transaksi";
$count_result = mysqli_query($conn, $count_query);
$total_records = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_records / $records_per_page);

$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
if ($current_page > $total_pages) $current_page = $total_pages;

$offset = ($current_page - 1) * $records_per_page;

$query = "SELECT id_transaksi, nama, no_hp, status_proses, status_pembayaran 
          FROM transaksi 
          ORDER BY tanggal DESC 
          LIMIT $offset, $records_per_page";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi - Razin Laundry</title>
    <link rel="stylesheet" href="style/transaksi-style.css">
    <link rel="stylesheet" href="style/sidebar.css">
    <style>
        /* Tambahan CSS untuk tombol aksi */
        .action-button {
            display: inline-block;
            padding: 6px 10px;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            transition: background-color 0.2s ease;
        }
        .action-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <?php include 'sidebar.php'; // Sertakan sidebar di sini ?>
        
        <div class="main-content-column"> 
            <div class="header">
                <h1>Daftar Transaksi Razin Laundry</h1>
            </div>

            <div class="container">
                <!-- Dashboard Stats -->
                <div class="dashboard-stats">
                    <div class="stat-card">
                        <h3>On Progress</h3>
                        <div class="stat-value stat-progress"><?php echo $stats['on_progress']; ?></div>
                        <div class="stat-percent"><?php echo ($stats['total_transaksi'] > 0) ? round(($stats['on_progress']/$stats['total_transaksi'])*100, 1) : 0; ?>% dari total</div>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Sedang Dicuci</h3>
                        <div class="stat-value stat-dicuci"><?php echo $stats['dicuci']; ?></div>
                        <div class="stat-percent"><?php echo ($stats['total_transaksi'] > 0) ? round(($stats['dicuci']/$stats['total_transaksi'])*100, 1) : 0; ?>% dari total</div>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Selesai</h3>
                        <div class="stat-value stat-selesai"><?php echo $stats['selesai']; ?></div>
                        <div class="stat-percent"><?php echo ($stats['total_transaksi'] > 0) ? round(($stats['selesai']/$stats['total_transaksi'])*100, 1) : 0; ?>% dari total</div>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Belum Dibayar</h3>
                        <div class="stat-value stat-belum-bayar"><?php echo $stats['belum_bayar']; ?></div>
                        <div class="stat-percent"><?php echo ($stats['total_transaksi'] > 0) ? round(($stats['belum_bayar']/$stats['total_transaksi'])*100, 1) : 0; ?>% dari total</div>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Sudah Dibayar</h3>
                        <div class="stat-value stat-sudah-bayar"><?php echo $stats['sudah_bayar']; ?></div>
                        <div class="stat-percent"><?php echo ($stats['total_transaksi'] > 0) ? round(($stats['sudah_bayar']/$stats['total_transaksi'])*100, 1) : 0; ?>% dari total</div>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Total Transaksi</h3>
                        <div class="stat-value stat-total"><?php echo $stats['total_transaksi']; ?></div>
                        <div class="stat-percent">100%</div>
                    </div>
                </div>

                <!-- Tabel Transaksi -->
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Nama Pelanggan</th>
                                <th>No. HP</th>
                                <th>Status Proses</th>
                                <th>Aksi Proses</th>
                                <th>Status Pembayaran</th>
                                <th>Aksi Pembayaran</th>
                                <th>Nota</th> <!-- Kolom baru untuk nota -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id_transaksi']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                        <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
                                        <td><?php echo getStatusProses($row['status_proses']); ?></td>
                                        <td>
                                            <form action="transaksi.php" method="POST" class="form-inline">
                                                <input type="hidden" name="id_transaksi" value="<?php echo htmlspecialchars($row['id_transaksi']); ?>">
                                                <select name="status_proses">
                                                    <option value="0" <?php echo ($row['status_proses'] == 0) ? 'selected' : ''; ?>>On Progress</option>
                                                    <option value="1" <?php echo ($row['status_proses'] == 1) ? 'selected' : ''; ?>>Dicuci</option>
                                                    <option value="2" <?php echo ($row['status_proses'] == 2) ? 'selected' : ''; ?>>Selesai</option>
                                                </select>
                                                <button type="submit" name="update_proses">Update</button>
                                            </form>
                                        </td>
                                        <td><?php echo getStatusPembayaran($row['status_pembayaran']); ?></td>
                                        <td>
                                            <form action="transaksi.php" method="POST" class="form-inline">
                                                <input type="hidden" name="id_transaksi" value="<?php echo htmlspecialchars($row['id_transaksi']); ?>">
                                                <select name="status_pembayaran">
                                                    <option value="0" <?php echo ($row['status_pembayaran'] == 0) ? 'selected' : ''; ?>>Belum Dibayar</option>
                                                    <option value="1" <?php echo ($row['status_pembayaran'] == 1) ? 'selected' : ''; ?>>Sudah Dibayar</option>
                                                </select>
                                                <button type="submit" name="update_pembayaran">Update</button>
                                            </form>
                                        </td>
                                        <td>
                                            <a href="nota.php?id_transaksi=<?php echo htmlspecialchars($row['id_transaksi']); ?>" class="action-button">Lihat Nota</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8">Tidak ada data transaksi.</td> <!-- Sesuaikan colspan -->
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($current_page > 1): ?>
                        <a href="transaksi.php?page=<?php echo $current_page - 1; ?>">&laquo; Previous</a>
                    <?php else: ?>
                        <span class="disabled">&laquo; Previous</span>
                    <?php endif; ?>

                    <?php 
                    $start_page = max(1, $current_page - 2);
                    $end_page = min($total_pages, $current_page + 2);
                    
                    if ($start_page > 1) {
                        echo '<a href="transaksi.php?page=1">1</a>';
                        if ($start_page > 2) echo '<span>...</span>';
                    }
                    
                    for ($i = $start_page; $i <= $end_page; $i++): ?>
                        <?php if ($i == $current_page): ?>
                            <span class="active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="transaksi.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($end_page < $total_pages) {
                        if ($end_page < $total_pages - 1) echo '<span>...</span>';
                        echo '<a href="transaksi.php?page='.$total_pages.'">'.$total_pages.'</a>';
                    }
                    ?>

                    <?php if ($current_page < $total_pages): ?>
                        <a href="transaksi.php?page=<?php echo $current_page + 1; ?>">Next &raquo;</a>
                    <?php else: ?>
                        <span class="disabled">Next &raquo;</span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div> <!-- Tutup main-content-column -->
    </div> <!-- Tutup app-container -->
</body>
</html>
