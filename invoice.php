<?php
// Aktifkan error reporting untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Jika form disubmit (metode POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $layanan = $_POST['layanan'] ?? '';
    $berat = (float)($_POST['berat'] ?? 0);
    $bed_cover = (int)($_POST['bed_cover'] ?? 0);
    $sprei = (int)($_POST['sprei'] ?? 0);
    $selimut = (int)($_POST['selimut'] ?? 0);
    $karpet = (int)($_POST['karpet'] ?? 0);
    $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
    $nama = $_POST['nama'] ?? '';
    $no_hp = $_POST['no_hp'] ?? '';

    // Validasi
    if (empty($layanan) || empty($nama)) {
        $error = "Layanan dan Nama harus diisi!";
    } else {
        $id_transaksi = mt_rand(1000000, 9999999);
        $conn = new mysqli('localhost', 'root', '', 'phpmailer'); // Pastikan koneksi ke database yang benar
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Ambil harga layanan
        // Asumsi tabel 'harga' memiliki kolom 'layanan' dan 'harga_per_kg'
        $stmt = $conn->prepare("SELECT harga_per_kg FROM harga WHERE layanan = ?");
        $stmt->bind_param("s", $layanan);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $error = "Layanan tidak ditemukan! Pastikan layanan 'Cuci Biasa' atau 'Cuci Express' ada di tabel harga.";
        } else {
            $harga_per_kg = $result->fetch_assoc()['harga_per_kg'];
            
            // Harga item tambahan (hardcoded seperti di JS)
            $harga_bed_cover = 30000;
            $harga_sprei = 8000;
            $harga_selimut = 8000;
            $harga_karpet = 45000;

            $total_harga = ($berat * $harga_per_kg) + 
                           ($bed_cover * $harga_bed_cover) + 
                           ($sprei * $harga_sprei) + 
                           ($selimut * $harga_selimut) + 
                           ($karpet * $harga_karpet);

            $stmt = $conn->prepare("INSERT INTO transaksi 
                (id_transaksi, layanan, berat, bed_cover, sprei, selimut, karpet, total_harga, tanggal, nama, no_hp, status_proses, status_pembayaran) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0)");
            
            if ($stmt->bind_param("ssdiiidssss", $id_transaksi, $layanan, $berat, $bed_cover, $sprei, $selimut, $karpet, $total_harga, $tanggal, $nama, $no_hp) && $stmt->execute()) {
                // Redirect ke halaman nota setelah berhasil menyimpan
                header("Location: nota.php?id_transaksi=" . $id_transaksi);
                exit(); // Penting untuk menghentikan eksekusi script setelah redirect
            } else {
                $error = "Gagal menyimpan transaksi! Error: " . $stmt->error;
            }
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Transaksi Laundry</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/sidebar.css">
    <style>
        /* CSS yang sudah ada */
        :root {
            --sidebar-width: 10px;
            --primary-color: #6a11cb;
            --secondary-color: #2575fc;
            --success-color: #38ef7d;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
        }
        
        .app-container {
            display: flex;
            min-height: 100vh;
        }
        
        .main-content-column {
            flex: 1;
            padding: 10px 15px;
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
        }
        
        .header {
            padding: 12px 15px;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #a18cd1, #fbc2eb);
            color: white;
            border-radius: 6px;
        }
        
        .container {
            display: flex;
            background: white;
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .input-section, .price-section {
            padding: 18px;
            flex: 1;
        }
        
        .input-section {
            border-right: 1px solid #eee;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #555;
        }
        
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        
        input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary-color);
        }
        
        input[type="text"],
        input[type="number"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
        }
        
        .item-quantity {
            display: none;
            margin: 5px 0 5px 26px;
        }
        
        button {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 10px;
            font-size: 14px;
            border-radius: 4px;
            margin-top: 5px;
            cursor: pointer;
        }
        
        .price-section {
            background: #fafbff;
            display: flex;
            flex-direction: column;
        }
        
        .price-item {
            background: white;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 8px;
            font-size: 13px;
            display: none; /* Hide all price items by default */
        }
        
        .price-item.show {
            display: flex;
            justify-content: space-between;
        }
        
        .total-price {
            background: var(--success-color);
            color: white;
            padding: 15px;
            border-radius: 4px;
            margin-top: auto;
        }
        
        @media (max-width: 768px) {
            .main-content-column {
                margin-left: 0;
                padding: 8px;
            }
            
            .container {
                flex-direction: column;
            }
            
            .input-section {
                border-right: none;
                border-bottom: 1px solid #eee;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <?php include 'sidebar.php'; ?>
        
        <div class="main-content-column">
            <div class="header">
                <h1 style="font-size:18px;">Form Transaksi Laundry</h1>
            </div>

            <?php if (isset($error)): ?>
                <div style="background:#f8d7da; color:#721c24; padding:10px; border-radius:4px; margin-bottom:10px; font-size:13px;">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div style="background:#d4edda; color:#155724; padding:10px; border-radius:4px; margin-bottom:10px; font-size:13px;">
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <div class="container">
                <div class="input-section">
                    <form method="POST">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" id="nama" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="no_hp">No HP</label>
                            <input type="text" name="no_hp" id="no_hp" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="layanan">Layanan</label>
                            <select name="layanan" id="layanan" required onchange="updateHarga()">
                                <option value="Cuci Biasa">Cuci Biasa</option>
                                <option value="Cuci Express">Cuci Express</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="berat">Berat (kg)</label>
                            <input type="number" name="berat" id="berat" min="0" step="0.1" onchange="updateHarga()" required>
                        </div>

                        <!-- Dynamic Items -->
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" id="bed_cover_checkbox" onchange="toggleInput('bed_cover')">
                                Bed Cover
                            </label>
                            <div id="bed_cover_input" class="item-quantity">
                                <input type="number" name="bed_cover" id="bed_cover" min="0" value="0" onchange="updateHarga()" placeholder="Qty">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" id="sprei_checkbox" onchange="toggleInput('sprei')">
                                Sprei
                            </label>
                            <div id="sprei_input" class="item-quantity">
                                <input type="number" name="sprei" id="sprei" min="0" value="0" onchange="updateHarga()" placeholder="Qty">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" id="selimut_checkbox" onchange="toggleInput('selimut')">
                                Selimut
                            </label>
                            <div id="selimut_input" class="item-quantity">
                                <input type="number" name="selimut" id="selimut" min="0" value="0" onchange="updateHarga()" placeholder="Qty">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" id="karpet_checkbox" onchange="toggleInput('karpet')">
                                Karpet
                            </label>
                            <div id="karpet_input" class="item-quantity">
                                <input type="number" name="karpet" id="karpet" min="0" value="0" onchange="updateHarga()" placeholder="Qty">
                            </div>
                        </div>
                        
                        <input type="hidden" name="total_harga" id="total_harga_input">
                        <button type="submit">Simpan</button>
                    </form>
                </div>
                
                <div class="price-section">
                    <h2 style="font-size:16px; margin-bottom:12px;">Detail Harga</h2>
                    
                    <div id="harga_layanan_item" class="price-item show">
                        <div>Layanan</div>
                        <div id="harga_layanan">Rp 0</div>
                    </div>
                    
                    <div id="harga_bed_cover_item" class="price-item">
                        <div>Bed Cover</div>
                        <div id="harga_bed_cover">Rp 0</div>
                    </div>
                    
                    <div id="harga_sprei_item" class="price-item">
                        <div>Sprei</div>
                        <div id="harga_sprei">Rp 0</div>
                    </div>
                    
                    <div id="harga_selimut_item" class="price-item">
                        <div>Selimut</div>
                        <div id="harga_selimut">Rp 0</div>
                    </div>
                    
                    <div id="harga_karpet_item" class="price-item">
                        <div>Karpet</div>
                        <div id="harga_karpet">Rp 0</div>
                    </div>
                    
                    <div class="total-price">
                        <div>Total</div>
                        <div style="font-size:18px;" id="total_harga">Rp 0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleInput(item) {
            const checkbox = document.getElementById(`${item}_checkbox`);
            const inputDiv = document.getElementById(`${item}_input`);
            const priceItem = document.getElementById(`harga_${item}_item`);
            
            if (checkbox.checked) {
                inputDiv.style.display = 'block';
                priceItem.classList.add('show');
            } else {
                inputDiv.style.display = 'none';
                priceItem.classList.remove('show');
                document.getElementById(item).value = 0;
            }
            updateHarga();
        }

        function updateHarga() {
            const service = document.getElementById('layanan').value;
            const weight = parseFloat(document.getElementById('berat').value) || 0;
            
            // Harga ini harus sesuai dengan yang ada di PHP atau diambil dari database
            const prices = {
                service: service === 'Cuci Biasa' ? 7000 : 35000,
                bed_cover: 30000,
                sprei: 8000,
                selimut: 8000,
                karpet: 45000
            };

            const quantities = {};
            ['bed_cover', 'sprei', 'selimut', 'karpet'].forEach(item => {
                quantities[item] = document.getElementById(`${item}_checkbox`).checked 
                    ? (parseInt(document.getElementById(item).value) || 0)
                    : 0;
            });

            const totals = {
                service: weight * prices.service,
                bed_cover: quantities.bed_cover * prices.bed_cover,
                sprei: quantities.sprei * prices.sprei,
                selimut: quantities.selimut * prices.selimut,
                karpet: quantities.karpet * prices.karpet
            };

            const grandTotal = Object.values(totals).reduce((a, b) => a + b, 0);

            // Update display
            document.getElementById('harga_layanan').textContent = `Rp ${totals.service.toLocaleString('id-ID')}`;
            document.getElementById('harga_bed_cover').textContent = `Rp ${totals.bed_cover.toLocaleString('id-ID')}`;
            document.getElementById('harga_sprei').textContent = `Rp ${totals.sprei.toLocaleString('id-ID')}`;
            document.getElementById('harga_selimut').textContent = `Rp ${totals.selimut.toLocaleString('id-ID')}`;
            document.getElementById('harga_karpet').textContent = `Rp ${totals.karpet.toLocaleString('id-ID')}`;
            document.getElementById('total_harga').textContent = `Rp ${grandTotal.toLocaleString('id-ID')}`;
            document.getElementById('total_harga_input').value = grandTotal;
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', updateHarga);
    </script>
</body>
</html>
