<?php
session_start();
include 'db.php';

if(!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit();
}

// Retrieve form inputs
$email = $_Session['email'];
$nic = $_POST['nic'];
$gender = $_POST['gender'];
$address = $_POST['address'];
$latitude = floatval($_POST['latitude']);
$longitude = floatval($_POST['longitude']);

//user data update in database 
$sql = "UPDATE users SET national_id = ?, address = ?, child_gender = ?, latitude = ?, longitude = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssdds", $nic, $address, $gender, $latitude, $longitude, $email);

if($stmt->execute()) {
    echo "Details Updated Successfully. <a href='select_schools.php'>Proceed to select schools </a>";
} else {
    echo "Error Updating Details: " . $stmt->error;
}

$stmt->close();
$conn->close();

// Gender filter logic
$gender_filter = $gender === 'boy' ? "'boys','mixed'" : "'girls','mixed'";

// Haversine formula to find schools within 10km
$sql = "
    SELECT *, 
        (6371 * ACOS(
            COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * 
            COS(RADIANS(longitude) - RADIANS($longitude)) + 
            SIN(RADIANS($latitude)) * SIN(RADIANS(latitude))
        )) AS distance
    FROM schools
    WHERE type IN ($gender_filter)
    HAVING distance <= 10
    ORDER BY distance ASC
    LIMIT 5
";

$result = $conn->query($sql);

echo "<h2>Nearby Schools Within 10km</h2>";
echo "<p><strong>NIC:</strong> " . htmlspecialchars($nic) . "</p>";
echo "<p><strong>Child Gender:</strong> " . htmlspecialchars($gender) . "</p>";
echo "<p><strong>Address:</strong> " . htmlspecialchars($address) . "</p><br>";

if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li><strong>" . htmlspecialchars($row['name']) . "</strong> – " 
           . round($row['distance'], 2) . " km away (" 
           . ucfirst($row['type']) . " school)</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No schools found within 10km matching your criteria.</p>";
}

$conn->close();
?>
