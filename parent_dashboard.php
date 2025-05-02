<?php

include 'db.php';

session_start();
if(!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit();
}
//get user details 
//$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Parent';
$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$_SESSION['username'] = $user['username'];
$_SESSION['child_name'] = $user['child_name'];

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
            font-family: 'Poppins', sans-serif;
            padding-top: 30px;
            padding-left: 500px;
            display: inline-block;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(90deg, var(--primary-second-color), var(--primary-color));
            flex-direction: column;
            text-align: center;
        }
        h2{
            font-size: 25px;
        }
        #map {
            height: 40px;
            width: 100%;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<h2>Welcome, <span class="parent-name"><?php echo htmlspecialchars ($_SESSION['username'] ?? 'Parent'); ?></span>!</h2>
<p>Child Name: <?php echo htmlspecialchars ($_SESSION['child_name'] ?? ''); ?></p>


<div class="dashboard">
    <form action="find_schools.php" method="POST" id="locationForm">
        <input type="text" name="nic" placeholder="NIC Number" required>
        <input type="text" name="child_name" value="<?php echo htmlspecialchars($user['child_name']); ?>" readonly>

        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="boy">Boy</option>
            <option value="girl">Girl</option>
        </select>

        <input type="text" id="address" name="address" placeholder="Type your address..." required> 
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">

        <div id="map"></div>
        <button type="submit">Find Nearby Schools</button>
    </form>
</div>
<script>
    let map;
    let marker;

    function initMap(){
        const defaultLocation = { lat: 6.9271, lng: 79.8612};

        map = new google.maps.Map(document.getElementById("map"), {
            center: defaultLocation,
            zoom: 12
        });

        const input = document.getElementById("address");
        cost autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo("bounds", map);

        autocomplete.addListener("place_changed", () => {
            const place = autocomplete.getPlace();
            if(!place.geometry || !place.geometry.location){
                alert("No details available for the input");
                return,
            }

            if(marker) marker.setMap(null);
            marker = new google.maps.Marker({
                position: place.geometry.location,
                map: map 
            });

            map.setCenter(place.geometry.location);
            map.setZoom(15);

            document.getElementById("lat").value = place.geometry.location.lat();
            document.getElementById("lng").value = place.geometry.location.lng();
        });
    }

    window.initMap = initMap;
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqQzvjFRQDcaDNfu4OBIfj9lmQhTSkcLA&libraries=places"></script>

</body>
</html>