<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = $_POST['address'];
    $gender = $_POST['gender'];

    if (empty($address) || empty($gender)) {
        die("Address and gender are required.");
    }

    // Use OpenStreetMap Nominatim API to get coordinates
    $geo_url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($address);
    $geo_data = json_decode(file_get_contents($geo_url), true);

    if (!$geo_data || count($geo_data) === 0) {
        die("Location not found.");
    }

    $userLat = (float) $geo_data[0]['lat'];
    $userLon = (float) $geo_data[0]['lon'];

    // School array
    $schools = [
        ["Kalutara Boys' School", "boy", "Galle Road, Kalutara", 6.5836, 79.9602],
        ["Kalutara Balika Vidyalaya", "girl", "Main Street, Kalutara", 6.5823, 79.9609],
        ["Holy Cross College", "mixed", "Nagoda Road, Kalutara", 6.5810, 79.9631],
        ["Tissa Central College", "mixed", "Panadura Road, Kalutara", 6.5861, 79.9605],
        ["St. John's College", "boy", "Kuda Waskaduwa, Kalutara", 6.5887, 79.9600],
        ["Kalutara Muslim Girls School", "girl", "Beruwala Road, Kalutara", 6.5820, 79.9620],
        ["Al-Hambra Maha Vidyalaya", "mixed", "Katukurunda, Kalutara", 6.5782, 79.9635],
        ["St. Thomas' Boys School", "boy", "Wadduwa, Kalutara", 6.6345, 79.9281],
        ["Sagara Balika Vidyalaya", "girl", "Payagala, Kalutara", 6.5334, 79.9622],
        ["Royal Central College", "mixed", "Nagoda, Kalutara", 6.5801, 79.9520],
        ["Vijaya National School", "mixed", "Maggona, Kalutara", 6.5588, 79.9780],
        ["St. Mary's Girls' School", "girl", "Kalutara North", 6.5900, 79.9580],
        ["Vidyaloka Maha Vidyalaya", "mixed", "Bombuwala, Kalutara", 6.6050, 79.9450],
        ["Panadura Royal College", "boy", "Panadura, Kalutara District", 6.7143, 79.9040],
        ["Sethubandhan Girls' College", "girl", "Beruwala, Kalutara District", 6.4750, 79.9820]
    ];

    // Haversine function
    function haversine($lat1, $lon1, $lat2, $lon2) {
        $R = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $R * $c;
    }

    $allowedTypes = ($gender === 'boy') ? ['boy', 'mixed'] : ['girl', 'mixed'];
    $nearbySchools = [];

    foreach ($schools as $school) {
        [$name, $type, $location, $lat, $lon] = $school;
        if (in_array($type, $allowedTypes)) {
            $distance = haversine($userLat, $userLon, $lat, $lon);
            if ($distance <= 10) {
                $nearbySchools[] = [
                    'name' => $name,
                    'type' => ucfirst($type),
                    'address' => $location,
                    'distance' => round($distance, 2)
                ];
            }
        }
    }

    usort($nearbySchools, fn($a, $b) => $a['distance'] <=> $b['distance']);
    $nearbySchools = array_slice($nearbySchools, 0, 5);
} else {
    die("Invalid access.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nearby Schools</title>
    <link rel="stylesheet" href="css/pdashboard.css">
</head>
<body>
    <div class="container">
        <h2>Nearby Schools within 10km</h2>
        <?php if (count($nearbySchools) === 0): ?>
            <p>No nearby schools found.</p>
        <?php else: ?>
            <form method="POST" action="submit_schools.php">
                <table class="school-table">
                    <thead>
                        <tr>
                            <th>School Name</th>
                            <th>Type</th>
                            <th>Address</th>
                            <th>Distance (km)</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($nearbySchools as $school): ?>
                            <tr>
                                <td><?= htmlspecialchars($school['name']) ?></td>
                                <td><?= htmlspecialchars($school['type']) ?></td>
                                <td><?= htmlspecialchars($school['address']) ?></td>
                                <td><?= $school['distance'] ?></td>
                                <td>
                                    <input type="checkbox" name="selected_schools[]" value="<?= htmlspecialchars($school['name']) ?>" class="school-checkbox">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <br>
                <button type="submit" id="submitSelected" class="submit-btn" disabled>Submit</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
        const checkboxes = document.querySelectorAll('.school-checkbox');
        const submitBtn = document.getElementById('submitSelected');

        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                const selected = document.querySelectorAll('.school-checkbox:checked');
                if (selected.length > 3) {
                    cb.checked = false;
                    alert("You can select only 3 schools.");
                }
                submitBtn.disabled = selected.length === 0;
            });
        });
    </script>
</body>
</html>
