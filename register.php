<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $child_name = $_POST['child_name'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT );


    $sql = "INSERT INTO users (username, email, child_name, role, password) VALUES ('$username', '$$child_name', '$role', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registation Successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>