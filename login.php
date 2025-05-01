<?php

include 'db.php';

$userInput = $_POST['email'];
$password = md5($_POST['password']);

$sql = "SELECT * FROM users WHERE (email=? OR username=? ) AND password=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $userInput, $userInput, $password);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    //$role = $user['role'];

    $_SESSION['email'] = $user['email'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['child_name'] = $user['child_name'];
    $_SESSION['role'] = $user['role'];

    //session_start();
    //$_SESSION['email'] = $user['email'];

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