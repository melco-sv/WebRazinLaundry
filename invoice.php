<?php
// Aktifkan error reporting untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Jika form disubmit (metode POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Memeriksa apakah data sudah ada dalam array POST
    $layanan = isset($_POST['layanan']) ? $_POST['layanan'] : '';
    $berat = isset($_POST['berat']) ? (float) $_POST['berat'] : 0;
    $bed_cover = isset($_POST['bed_cover']) ? (int) $_POST['bed_cover'] : 0;
    $sprei = isset($_POST['sprei']) ? (int) $_POST['sprei'] : 0;
    $selimut = isset($_POST['selimut']) ? (int) $_POST['selimut'] : 0;
    $karpet = isset($_POST['karpet']) ? (int) $_POST['karpet'] : 0;
    $total_harga = isset($_POST['total_harga']) ? (float) $_POST['total_harga'] : 0;

    // Data tambahan: Nama, No HP, Tanggal
    $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $no_hp = isset($_POST['no_hp']) ? $_POST['no_hp'] : '';

    // Validasi jika layanan dan nama tidak kosong
    if ($layanan == '' || $nama == '') {
        echo "Layanan dan Nama harus diisi!";
        exit;
    }

    // Membuat ID transaksi acak (7 digit)
    $id_transaksi = mt_rand(1000000, 9999999); // Menghasilkan angka acak 7 digit

    // Koneksi ke database
    $host = 'localhost';  // Ganti dengan host Anda
    $user = 'root';       // Ganti dengan username database Anda
    $pass = '';           // Ganti dengan password database Anda
    $dbname = 'phpmailer';  // Ganti dengan nama database Anda

    // Buat koneksi ke database
    $conn = new mysqli($host, $user, $pass, $dbname);

    // Periksa koneksi
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Ambil harga layanan dari database
    $sql_layanan = "SELECT harga_per_kg FROM harga WHERE layanan = '$layanan'";
    $result_layanan = $conn->query($sql_layanan);
    if ($result_layanan->num_rows > 0) {
        $row_layanan = $result_layanan->fetch_assoc();
        $harga_per_kg = $row_layanan['harga_per_kg'];
    } else {
        echo "Layanan tidak ditemukan!";
        exit;
    }

    // Ambil harga item tambahan
    $sql_bed_cover = "SELECT harga FROM harga_item WHERE item = 'Bed Cover'";
    $result_bed_cover = $conn->query($sql_bed_cover);
    $row_bed_cover = $result_bed_cover->fetch_assoc();
    $harga_bed_cover = $row_bed_cover['harga'];

    $sql_sprei = "SELECT harga FROM harga_item WHERE item = 'Sprei'";
    $result_sprei = $conn->query($sql_sprei);
    $row_sprei = $result_sprei->fetch_assoc();
    $harga_sprei = $row_sprei['harga'];

    $sql_selimut = "SELECT harga FROM harga_item WHERE item = 'Selimut'";
    $result_selimut = $conn->query($sql_selimut);
    $row_selimut = $result_selimut->fetch_assoc();
    $harga_selimut = $row_selimut['harga'];

    $sql_karpet = "SELECT harga FROM harga_item WHERE item = 'Karpet'";
    $result_karpet = $conn->query($sql_karpet);
    $row_karpet = $result_karpet->fetch_assoc();
    $harga_karpet = $row_karpet['harga'];

    // Hitung total harga
    $total_harga = ($berat * $harga_per_kg) + 
                   ($bed_cover * $harga_bed_cover) + 
                   ($sprei * $harga_sprei) + 
                   ($selimut * $harga_selimut) + 
                   ($karpet * $harga_karpet);

    // Simpan transaksi ke dalam database
    $sql = "INSERT INTO transaksi (id_transaksi, layanan, berat, bed_cover, sprei, selimut, karpet, total_harga, tanggal, nama, no_hp) 
            VALUES ('$id_transaksi', '$layanan', '$berat', '$bed_cover', '$sprei', '$selimut', '$karpet', '$total_harga', '$tanggal', '$nama', '$no_hp')";

    if ($conn->query($sql) === TRUE) {
        echo '<script type="text/javascript">alert("Transaksi berhasil disimpan!");</script>';
    } else {
        echo "Error: " . $conn->error;
    }

    // Tutup koneksi
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Laundry</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="header">
        <h1>Form Transaksi Laundry</h1>
    </div>

    <div class="container">
        <!-- Form Input Section (Left) -->
        <div class="input-section">
            <form action="invoice.php" method="POST">
                <div class="form-group">
                    <label for="tanggal">Tanggal:</label>
                    <input type="date" name="tanggal" id="tanggal" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="nama">Nama:</label>
                    <input type="text" name="nama" id="nama" required>
                </div>
                
                <div class="form-group">
                    <label for="no_hp">No HP:</label>
                    <input type="text" name="no_hp" id="no_hp" required>
                </div>
                
                <div class="form-group">
                    <label for="layanan">Layanan:</label>
                    <select name="layanan" id="layanan" required onchange="updateHarga()">
                        <option value="Cuci Biasa">Cuci Biasa</option>
                        <option value="Cuci Express">Cuci Express</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="berat">Berat (kg):</label>
                    <input type="number" name="berat" id="berat" onchange="updateHarga()" min="0" step="0.1">
                </div>
                
                <div class="form-group">
                    <label for="bed_cover">Bed Cover (Qty):</label>
                    <input type="number" name="bed_cover" id="bed_cover" min="0" value="0" onchange="updateHarga()">
                </div>
                
                <div class="form-group">
                    <label for="sprei">Sprei (Qty):</label>
                    <input type="number" name="sprei" id="sprei" min="0" value="0" onchange="updateHarga()">
                </div>
                
                <div class="form-group">
                    <label for="selimut">Selimut (Qty):</label>
                    <input type="number" name="selimut" id="selimut" min="0" value="0" onchange="updateHarga()">
                </div>
                
                <div class="form-group">
                    <label for="karpet">Karpet (Qty):</label>
                    <input type="number" name="karpet" id="karpet" min="0" value="0" onchange="updateHarga()">
                </div>
                
                <!-- Menyimpan Total Harga ke dalam form -->
                <input type="hidden" name="total_harga" id="total_harga_input">
                
                <button type="submit">Simpan Transaksi</button>
            </form>
        </div>
        
        <!-- Price Calculation Section (Right) -->
        <div class="price-section">
            <h2>Rincian Harga</h2>
            
            <div class="price-item">
                <div class="price-item-title">Harga Layanan</div>
                <div class="price-item-value" id="harga_layanan">Rp 0</div>
            </div>
            
            <div class="price-item">
                <div class="price-item-title">Harga Bed Cover</div>
                <div class="price-item-value" id="harga_bed_cover">Rp 0</div>
            </div>
            
            <div class="price-item">
                <div class="price-item-title">Harga Sprei</div>
                <div class="price-item-value" id="harga_sprei">Rp 0</div>
            </div>
            
            <div class="price-item">
                <div class="price-item-title">Harga Selimut</div>
                <div class="price-item-value" id="harga_selimut">Rp 0</div>
            </div>
            
            <div class="price-item">
                <div class="price-item-title">Harga Karpet</div>
                <div class="price-item-value" id="harga_karpet">Rp 0</div>
            </div>
            
            <div class="total-price">
                <div class="total-price-title">Total Harga</div>
                <div class="total-price-value" id="total_harga">Rp 0</div>
            </div>
        </div>
    </div>

    <script>
        function updateHarga() {
            var layanan = document.getElementById('layanan').value;
            
            // Pastikan input angka valid atau set default ke 0 jika kosong
            var berat = parseFloat(document.getElementById('berat').value) || 0;
            var bedCoverQty = parseInt(document.getElementById('bed_cover').value) || 0;
            var spreiQty = parseInt(document.getElementById('sprei').value) || 0;
            var selimutQty = parseInt(document.getElementById('selimut').value) || 0;
            var karpetQty = parseInt(document.getElementById('karpet').value) || 0;

            // Harga layanan
            var hargaPerKg = layanan == 'Cuci Biasa' ? 7000 : 35000;

            // Harga item tambahan
            var hargaBedCover = 30000;
            var hargaSprei = 8000;
            var hargaSelimut = 8000;
            var hargaKarpet = 45000;

            // Hitung harga total
            var totalHarga = (berat * hargaPerKg) +
                             (bedCoverQty * hargaBedCover) +
                             (spreiQty * hargaSprei) +
                             (selimutQty * hargaSelimut) +
                             (karpetQty * hargaKarpet);

            // Update harga yang ditampilkan
            document.getElementById('harga_layanan').innerText = "Rp " + (berat * hargaPerKg).toLocaleString('id-ID');
            document.getElementById('harga_bed_cover').innerText = "Rp " + (bedCoverQty * hargaBedCover).toLocaleString('id-ID');
            document.getElementById('harga_sprei').innerText = "Rp " + (spreiQty * hargaSprei).toLocaleString('id-ID');
            document.getElementById('harga_selimut').innerText = "Rp " + (selimutQty * hargaSelimut).toLocaleString('id-ID');
            document.getElementById('harga_karpet').innerText = "Rp " + (karpetQty * hargaKarpet).toLocaleString('id-ID');
            document.getElementById('total_harga').innerText = "Rp " + totalHarga.toLocaleString('id-ID');

            // Set total harga ke dalam form
            document.getElementById('total_harga_input').value = totalHarga;
        }
    </script>
</body>
</html>
