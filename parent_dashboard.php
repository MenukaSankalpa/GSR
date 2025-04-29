<?php

session_start();
if(!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit();
}
$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Parent';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>
    <link rel="stylesheet" href="style1.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqQzvjFRQDcaDNfu4OBIfj9lmQhTSkcLA&libraries=places"></script>
</head>
<body>
    <h2>Welcome, Parent</h2>
    <div class="container">

    <form id="locationForm">
        <input type="text" name="national_id" placeholder="NIC Number" required><br>

        
        <select name="" id="child_gender" required placeholder="Child Gender">
            <option value="boy">Boy</option>
            <option value="girl">Girl</option>
        </select><br>

        <input type="text" id="autocomplete" placeholder="Enter Your Address" required>

        <div id="map"></div>
        <input type="hidden" id="latitude">
        <input type="hidden" id="longitude">

    </form>

    <div id="schoolList"></div>
    <script src="script.js"></script>

    </div>

</body>
</html>