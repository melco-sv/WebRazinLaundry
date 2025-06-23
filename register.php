<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Laundry App</title>
     <link rel="stylesheet" href="style/register.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Tambahan spesifik untuk register page */
        .password-strength {
            margin-top: 5px;
            font-size: 13px;
            color: #666;
        }
        
        .password-hint {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <h1 class="auth-title">Buat Akun Baru</h1>
        
        <form action="proses.php" method="post" autocomplete="off" id="registerForm">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>
            
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="contoh@email.com" required>
            </div>
            
            <div class="form-group">
                <label for="nohp">Nomor Handphone</label>
                <input type="tel" name="nohp" id="nohp" class="form-control" 
                       placeholder="08123456789"
                       required>
                <div class="error-message" id="phoneError">Nomor handphone harus 10-15 digit angka</div>
                <div class="password-hint">Contoh: 08123456789 (tanpa spasi atau tanda hubung)</div>
            </div>
            
            <div class="form-group">
                <label for="pw">Password</label>
                <input type="password" name="pw" id="pw" class="form-control" 
                       placeholder="Minimal 8 karakter" 
                       autocomplete="new-password"
                       oninput="checkPasswordStrength(this.value)"
                       required>
                <div class="password-strength" id="passwordStrength"></div>
                <div class="password-hint">Gunakan kombinasi huruf, angka, dan simbol</div>
            </div>
            
            <button type="submit" name="register" class="btn" id="submitBtn">Daftar Sekarang</button>
            
            <div class="auth-link">
                Sudah memiliki akun? <a href="login.php">Masuk disini</a>
            </div>
        </form>
    </div>

    <script>
        // Validasi nomor handphone
        document.getElementById('nohp').addEventListener('input', function() {
            const phoneInput = this.value;
            const phoneError = document.getElementById('phoneError');
            
            // Hanya boleh angka
            this.value = phoneInput.replace(/[^0-9]/g, '');
            
            // Validasi panjang nomor
            if(phoneInput.length > 0 && (phoneInput.length < 10 || phoneInput.length > 15)) {
                phoneError.style.display = 'block';
                document.getElementById('submitBtn').disabled = true;
            } else {
                phoneError.style.display = 'none';
                document.getElementById('submitBtn').disabled = false;
            }
        });

        // Indikator kekuatan password
        function checkPasswordStrength(password) {
            const strengthText = document.getElementById('passwordStrength');
            let strength = 0;
            
            // Kriteria password
            if(password.length >= 8) strength++;
            if(password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if(password.match(/[0-9]/)) strength++;
            if(password.match(/[^a-zA-Z0-9]/)) strength++;
            
            // Update tampilan
            switch(strength) {
                case 0:
                case 1:
                    strengthText.textContent = "Kekuatan: Lemah";
                    strengthText.style.color = "#e74c3c";
                    break;
                case 2:
                    strengthText.textContent = "Kekuatan: Sedang";
                    strengthText.style.color = "#f39c12";
                    break;
                case 3:
                    strengthText.textContent = "Kekuatan: Kuat";
                    strengthText.style.color = "#3498db";
                    break;
                case 4:
                    strengthText.textContent = "Kekuatan: Sangat Kuat";
                    strengthText.style.color = "#2ecc71";
                    break;
            }
        }

        // Validasi form sebelum submit
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const phoneInput = document.getElementById('nohp').value;
            
            if(phoneInput.length < 10 || phoneInput.length > 15) {
                e.preventDefault();
                document.getElementById('phoneError').style.display = 'block';
                document.getElementById('nohp').focus();
            }
        });
    </script>
</body>
</html>
