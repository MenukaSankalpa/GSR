<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email= '$email'";
    $result = $conn->query($sql);

    if ($result-> num_rows == 1) {
        $row = $result->fetch_assoc();
        if(password_verify($password, $row['password'])) {
            echo "Login Successful! welcome, " .$row['username'];
        } else {
            echo "Invalid Password";
        }
    } else {
        echo "No usr found this with email.";
    }
}
?>