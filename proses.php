<?php
$koneksi = mysqli_connect("localhost", "root", "", "phpmailer");

$nama = $_POST['nama'];
$email = $_POST['email'];
$nohp = $_POST['nohp'];  // Ambil data no HP dari form
$pw = $_POST['pw'];
$code = md5($email . date('Y-m-d H:i:s'));

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
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
