<?php

$conn = new mysqli ("localhost", "root", "", "school_admission_system");

if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

$email = $_POST['email'];
$password = md5($_POST['password']);

$sql = "SELECT * FROM users WHERE email=? AND password=?";
$stmt = $conn->prepare($sql);
$stmt->blind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $role = $user['role'];

    //Redirect pages based on roles 
    if ($role == '1'){
        header("Location: parent_dashboard.php");
    } elseif ($role == '2') {
        header("Location: admin_dashboard.php");
    } elseif ($role == '3') {
        header("Location: super_admin_dashboard.php");
    }
} else {
    echo ("<script>alert('Invalid Login Credentials!'); window.location.href='index.html'</script>");
}

$conn->close();

?>