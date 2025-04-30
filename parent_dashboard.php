<?php

session_start();
if(!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit();
}
//$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Parent';
$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqQzvjFRQDcaDNfu4OBIfj9lmQhTSkcLA&libraries=places"></script>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }
        #map {
            height: 40px;
            width: 100%;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <div class="dashboard">
        <h2>Welcome, <?php echo $user['username']; ?>!</h2>
        <form action="" class="" id="locationForm">
            <input type="text" value="<?php echo $user['national_id']; ?>" readonly placeholder="Parent NIC Number">
            <input type="text" value="<?php echo $user['child_name']; ?>" readonly>

        </form>
    </div>


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