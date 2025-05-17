<?php
include 'db.php';

$lat = $_POST['latitude'];
$lng = $_POST['longitude'];
$gender = $_POST['gender'];

// Gender logic: boys -> boys + mixed, girls -> girls + mixed
$types = ($gender === 'boy') ? "'boys','mixed'" : "'girls','mixed'";

// SQL with Haversine formula to find schools within 10km
$sql = "
    SELECT *, (
        6371 * acos(
            cos(radians(?)) *
            cos(radians(latitude)) *
            cos(radians(longitude) - radians(?)) +
            sin(radians(?)) *
            sin(radians(latitude))
        )
    ) AS distance
    FROM schools
    WHERE type IN ($types)
    HAVING distance <= 10
    ORDER BY distance ASC
    LIMIT 5
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ddd", $lat, $lng, $lat);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h3>Nearby Schools (within 10km):</h3><ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row['name']) . " (" . htmlspecialchars($row['type']) . ")</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No nearby schools found.</p>";
}
?>
