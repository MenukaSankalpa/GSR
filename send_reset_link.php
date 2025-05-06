<?php
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
    $subject = "Password Reset";
    $message = "Click this link to rest your password: $reset_link";
    $headers = "From: no-reply@yourdomain.com";

    mail($email,$subject,$message,$headers);

    echo "<script>alert('Reset link has been sent to your email.'); window.location.href='index.html'<script/>";
} else {
    echo "<script>alert('Email not found.') window.location.href='forgot_password.php'<script/>"
}

$conn->close();
?>