<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; //this is for if i used composer

include 'db.php';

$email = $_POST['email'];
$token = bin2hex(random_bytes(50)); #(generate secure token) 
$expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));


// checking already have a mail 
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0) {
    //save token and expiry in DB
    $update = $conn->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
    $update->bind_param("sss", $token, $expiry, $email);
    $update->execute();

    //prepare email
    $mail = new PHPMailer(true);

    try {
        // Mailtrap SMTP settings
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth= true;
        $mail->Username ='b861e012eb86dc';
        $mail->Password ='2977e5c0dbcde2';
        $mail->Port =2525;

        $mail->setFrom('alone.monster13@gmail.com', 'School Admission');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Link';
        $reset_link = "http://localhost/GSR/reset_password.php?token=$token";
        $mail->Body = "Click <a href='$reset_link'>here</a> to reset your password. This link will expire in 1 hour.";


        $mail->send();
        echo "<script>alert('Reset link sent to your email.'); window.location.href='index.html';</script>";

    } catch (Exception $e) {
        echo "<script>alert('Message could not be sent. Mailer Error: {$email->ErrorInfo}'); window.location.href='forgot_password.php';</script>";
    }
} else {
    echo "<script>alert('Email not found'); window.location.href='forgot_password.php';</script>";
}    

$conn->close();
?>