<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $new_password = md5($_POST['new_password']); // You can replace md5 with password_hash() for better security

    // Validate token
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Token is valid → update password
        $update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE reset_token = ?");
        $update->bind_param("ss", $new_password, $token);
        if ($update->execute()) {
            echo "<script>alert('Password updated successfully. Please login.'); window.location.href='index.html';</script>";
        } else {
            echo "<script>alert('Failed to update password.'); window.location.href='reset_password.php?token=$token';</script>";
        }
    } else {
        echo "<script>alert('Invalid or expired token.'); window.location.href='forgot_password.php';</script>";
    }
}
?>
