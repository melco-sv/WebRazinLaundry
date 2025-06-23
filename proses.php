<?php
$koneksi = mysqli_connect("localhost", "root", "", "phpmailer");

<<<<<<< HEAD
// Pastikan koneksi berhasil
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil data dari form
$nama = $_POST['nama'];
$email = $_POST['email'];
$nohp = $_POST['nohp']; // Menambahkan input nomor handphone
=======
$nama = $_POST['nama'];
$email = $_POST['email'];
$nohp = $_POST['nohp'];  // Ambil data no HP dari form
>>>>>>> 9a76c148320d064457fafa17857a99e35e29b321
$pw = $_POST['pw'];
$code = md5($email . date('Y-m-d H:i:s'));

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
<<<<<<< HEAD
    // Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'laundryrazin@gmail.com';                     // SMTP username
    $mail->Password   = 'vaeidkgrevqnajpd';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Enable implicit TLS encryption
    $mail->Port       = 465;                                    // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    // Recipients
    $mail->setFrom('from@etestWebsite.com', 'Mailer');
    $mail->addAddress($email, $nama);     // Add a recipient

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Verification Account';
    $mail->Body    = 'Hi! ' . $nama . ' Terimakasih sudah mendaftar di website ini, <br> Mohon Verifikasi akun kamu! <a href="http://localhost/webrazinlaundry/verif.php?code=' . $code . '">Verifikasi</a>';

    if ($mail->send()) {
        // Menyimpan data ke database
        $hashed_password = password_hash($pw, PASSWORD_DEFAULT); // Hash password sebelum disimpan
        $koneksi->query("INSERT INTO data(nama, email, nohp, password, verifikasi_code) VALUES('$nama', '$email', '$nohp', '$hashed_password', '$code')");
        echo "<script>alert('Registrasi Berhasil, silahkan cek email untuk verifikasi akun');window.location='login.php'</script>";
    }

} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
=======
    // Konfigurasi email (sama seperti sebelumnya)
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'laundryrazin@gmail.com';
    $mail->Password   = 'vaeidkgrevqnajpd';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('from@etestWebsite.com', 'Razin Laundry');
    $mail->addAddress($email, $nama);

    $mail->isHTML(true);
    $mail->Subject = 'Verification Account';
    $mail->Body = '
    Halo ' . $nama . ',<br><br>
    Terima kasih telah mendaftar di <strong>Razin Laundry</strong>!<br><br>
    
    Untuk menyelesaikan proses pendaftaran dan mulai menggunakan layanan kami, silakan verifikasi alamat email Anda dengan mengklik tautan di bawah ini:<br><br>
    
    <a href="http://localhost/webrazinlaundry/verif.php?code=' . $code . '">Verifikasi Akun</a><br><br>
    
    Jika Anda tidak merasa mendaftar di Razin Laundry, abaikan saja email ini.<br><br>
    
    Apabila Anda memiliki pertanyaan atau membutuhkan bantuan, silakan hubungi kami di <a href="mailto:support@razinlaundry.com">support@razinlaundry.com</a>.<br><br>
    
    Salam hangat,<br>
    Tim Razin Laundry<br>
    <a href="http://www.razinlaundry.com">www.razinlaundry.com</a>';

    if ($mail->send()) {
        // Tambahkan nohp ke query INSERT
        $koneksi->query("INSERT INTO data(nama, email, nohp, password, verifikasi_code) 
                        VALUES('$nama', '$email', '$nohp', '$pw', '$code')");
        echo "<script>alert('Registrasi Berhasil, silahkan cek email untuk verifikasi akun');window.location='login.php'</script>";
    }
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
>>>>>>> 9a76c148320d064457fafa17857a99e35e29b321
