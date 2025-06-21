<?php
$koneksi = mysqli_connect("localhost", "root", "", "phpmailer");

$nama =$_POST['nama'];
$email = $_POST['email'];
$pw = $_POST['pw'];
$code = md5($email.date('Y-m-d H:i:s'));

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// require 'path/to/PHPMailer/src/Exception.php';
// require 'path/to/PHPMailer/src/PHPMailer.php';
// require 'path/to/PHPMailer/src/SMTP.php';

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'laundryrazin@gmail.com';                     //SMTP username
    $mail->Password   = 'vaeidkgrevqnajpd';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('from@etestWebsite.com', 'Mailer');
    $mail->addAddress($email, $nama);     //Add a recipient
    // $mail->addAddress('ellen@example.com');               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Verification Account';
    $mail->Body    = 'Hi! '.$nama.' Terimakasih sudah mendaftar di website ini, <br> Mohon Verifikasi akun akun kamu ! <a
    href="http://localhost/webrazinlaundry/verif.php?code= '.$code.'">Verifikasi</a>';
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

 if($mail->send()){
    $koneksi->query("INSERT INTO data(nama, email, password, verifikasi_code) VALUES('$nama', '$email', '$pw', '$code')");
        echo "<script>alert('Registrasi Berhasil, silahkan cek email untuk verifikasi akun');window.location='login.php'</script>";
 }

} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

