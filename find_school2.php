<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userLat = floatval($_POST['latitude']);
    $userLon = floatval($_POST['longitude']);
    $gender = $_POST['gender'];

    $schools = include 'asset/schools.php'; // corrected path and include

    // Haversine formula
    function haversine($lat1, $lon1, $lat2, $lon2) {
        $earth_radius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earth_radius * $c;
    }

    $matchingSchools = [];

    foreach ($schools as $school) {
        if (
            $gender === "boy" && ($school['gender'] === 'boys' || $school['gender'] === 'mixed') ||
            $gender === "girl" && ($school['gender'] === 'girls' || $school['gender'] === 'mixed')
        ) {
            $distance = haversine($userLat, $userLon, $school['lat'], $school['lon']);
            if ($distance <= 10) {
                $school['distance'] = round($distance, 2);
                $matchingSchools[] = $school;
            }
        }
    }

    // Sort by distance
    usort($matchingSchools, function ($a, $b) {
        return $a['distance'] <=> $b['distance'];
    });

    $matchingSchools = array_slice($matchingSchools, 0, 5);

    // Display results
    if (!empty($matchingSchools)) {
        echo "<ul>";
        foreach ($matchingSchools as $school) {
            echo "<li>{$school['name']} ({$school['distance']} km)</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No schools found within 10km for selected gender.</p>";
    }
}
?>
