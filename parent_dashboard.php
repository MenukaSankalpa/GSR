<?php

session_start();
if(!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit();
}
/*$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Parent';
$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();*/
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

<h2>Welcome, <?php echo htmlspecialchars ($_SESSION['username']); ?>!</h2>
<p>Child Name: <?php echo htmlspecialchars ($_SESSION['child_name']); ?></p>


<form method="POST" action="find_schools.php">

    <input type="text" name="nic" placeholder="NIC Number" required>

</form>
    <div class="dashboard">       
        <form action="" class="" id="locationForm">
            <input type="text" value="<?php echo $user['national_id']; ?>" readonly placeholder="Parent NIC Number">
            <input type="text" value="<?php echo $user['child_name']; ?>" readonly>

            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="boy">Boy</option>
                <option value="girl">Girl</option>
            </select>

            <input type="test" id="address" name="address" placeholder="Type your address..." required> 

            <input type="hidden" id="lat" name="latitude">
            <input type="hidden" id="lng" name="longitude">

            <div id="map"></div>

            <button type="submit">Find Nearby Schools</button>
        </form>
    </div>

</body>
</html>