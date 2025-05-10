<?php
include 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    //Validate  token and show reset form
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows ===1){
        ?>
            <form action="update_password.php" method="POST">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <input type="password" name="password" placeholder="New Password" required>
                <button type="submit">Update Password</button>
            </form>
        <?php
    } else {
        echo "Invalid or expired token.";
    }
}  
?>  
