<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking</title>
    <link rel="stylesheet" href="style/sidebar.css">
    <link rel="stylesheet" href="style/lacak.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="app-container">
        <?php include 'sidebar.php'; ?>
        
        <div class="main-content-column">
            <div class="container mt-5">
                <h2>Order Tracking</h2>
                <form id="trackingForm" method="POST" action="">
                    <div class="form-group">
                        <label for="id_tracking">Masukkan ID Tracking:</label>
                        <input type="text" id="id_tracking" name="id_tracking" class="form-control" required
                               value="<?php echo isset($_POST['id_tracking']) ? htmlspecialchars($_POST['id_tracking']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Cari" class="btn btn-primary">
                        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_tracking'])): ?>
                            <button type="button" id="clearSearch" class="btn btn-danger ml-2">Hapus Hasil</button>
                        <?php endif; ?>
                    </div>
                </form>

                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_tracking'])) {
                    $id_tracking = $_POST['id_tracking'];
                    include 'koneksi.php';

                    $sql = "SELECT id_transaksi, nama, no_hp, status_proses, status_pembayaran, total_harga 
                            FROM transaksi WHERE id_transaksi = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $id_tracking);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        echo "<div class='search-results'>";
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
                        echo "</div>";
                    } else {
                        echo "<div class='alert alert-warning mt-4'>Data tidak ditemukan untuk ID Tracking: $id_tracking</div>";
                    }

                    $stmt->close();
                    $conn->close();
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('clearSearch')?.addEventListener('click', function() {
            document.getElementById('id_tracking').value = '';
            document.querySelector('.search-results')?.remove();
            document.querySelector('.alert')?.remove();
            this.remove();
        });
    </script>
</body>
</html>
