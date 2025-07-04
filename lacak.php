<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .status-indicator {
            display: inline-block;
            width: 20px; /* Lebar oval */
            height: 10px; /* Tinggi oval */
            border-radius: 10px; /* Membuat oval */
            margin-right: 5px; /* Jarak antara oval dan teks */
        }
        .status-on-progress {
            background-color: red;
        }
        .status-dicuci {
            background-color: yellow;
        }
        .status-selesai {
            background-color: green;
        }
        .status-belum-dibayar {
            background-color: red;
        }
        .status-sudah-dibayar {
            background-color: green;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Order Tracking</h2>
        <form id="trackingForm" method="POST" action="">
            <div class="form-group">
                <label for="id_tracking">Masukkan ID Tracking:</label>
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
</body>
</html>
