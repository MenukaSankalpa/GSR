<?php

session_start();
if(!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqQzvjFRQDcaDNfu4OBIfj9lmQhTSkcLA&libraries=places"></script>
</head>
<body>
    
</body>
</html>