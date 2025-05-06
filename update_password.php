<?php
include 'db.php';

$token = $_POST['token'];
$newPassword = md5($_POST['password']); // or use password_hash()

$sql = "SELECT * FROM users WHERE reset_token = ? AND token_expiry > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE reset_token = ?");
    $update->bind_param("ss", $newPassword, $token);
    $update->execute();

    echo "<script>alert('Password updated successfully. You can now login.'); window.location.href='index.html'</script>";
} else {
    echo "<script>alert('Invalid or expired token.'); window.location.href='forgot_password.php'</script>";
}

$conn->close();
?>
