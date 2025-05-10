<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit();
}

$email = $_SESSION['email'];
$nic = $_POST['nic'];
$gender = $_POST['gender'];
$address = $_POST['address'];
$latitude = floatval($_POST['latitude']);
$longitude = floatval($_POST['longitude']);

// Update user info
$update = $conn->prepare("UPDATE users SET national_id = ?, address = ?, child_gender = ?, latitude = ?, longitude = ? WHERE email = ?");
$update->bind_param("sssdds", $nic, $address, $gender, $latitude, $longitude, $email);

if ($update->execute()) {
    echo "<h3>Details Updated Successfully. Proceeding to find nearby schools...</h3><br>";
} else {
    die("Update Error: " . $update->error);
}

// Gender filter
$gender_filter = $gender === 'boy' ? "'boys','mixed'" : "'girls','mixed'";

// School search query using Haversine Formula (within 20km)
$sql = "
    SELECT *, 
        (6371 * ACOS(
            COS(RADIANS($latitude)) * COS(RADIANS(latitude)) *
            COS(RADIANS(longitude) - RADIANS($longitude)) +
            SIN(RADIANS($latitude)) * SIN(RADIANS(latitude))
        )) AS distance
    FROM schools
    WHERE type IN ($gender_filter)
    HAVING distance <= 20
    ORDER BY distance ASC
    LIMIT 10
";

$result = $conn->query($sql);
if (!$result) {
    die("Query Error: " . $conn->error);
}

// Display Info
echo "<h2>Nearby Schools Within 20km</h2>";
echo "<p><strong>NIC:</strong> " . htmlspecialchars($nic) . "</p>";
echo "<p><strong>Child Gender:</strong> " . htmlspecialchars($gender) . "</p>";
echo "<p><strong>Address:</strong> " . htmlspecialchars($address) . "</p><br>";

$schools = [];

if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li><strong>" . htmlspecialchars($row['name']) . "</strong> – " 
           . round($row['distance'], 2) . " km away (" 
           . ucfirst($row['type']) . " school)</li>";

        $schools[] = $row;
    }
    echo "</ul>";
} else {
    echo "<p>No schools found within 20km matching your criteria.</p>";
}

$conn->close();
?>

<!-- Display Google Map -->
<div id="map" style="height: 500px; width: 100%; margin-top: 20px;"></div>
<script>
function initMap() {
    const userLocation = { lat: <?= $latitude ?>, lng: <?= $longitude ?> };
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 13,
        center: userLocation
    });

    const userMarker = new google.maps.Marker({
        position: userLocation,
        map: map,
        label: "You",
        icon: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
    });

    <?php foreach ($schools as $school): ?>
        new google.maps.Marker({
            position: { lat: <?= $school['latitude'] ?>, lng: <?= $school['longitude'] ?> },
            map: map,
            title: "<?= htmlspecialchars($school['name']) ?> (<?= ucfirst($school['type']) ?>)"
        });
    <?php endforeach; ?>
}
</script>

<!-- Replace YOUR_API_KEY with your actual Google Maps JavaScript API key -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqQzvjFRQDcaDNfu4OBIfj9lmQhTSkcLA&libraries=places" async defer></script>
