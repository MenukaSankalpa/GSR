<?php
include 'schools.php';

$lat = $_POST['latitude'];
$lon = $_POST['longitude'];
$gender = $_POST['gender'];

function haversine($lat1, $lat1, $lat2, $lat2) {
    $earth_radius = 6371;

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $earth_radius * $c;
}

// school filter using gender

$filtered = array_filter($school, function($school) use ($gender) {
    if ($gender == 'boy') {
        return $school['gender'] == 'boy' || $school['gender'] == 'mixed';
    } elseif ($gender == 'girl') {
        return $school['gender'] == 'girl' || $school['gender'] == 'mixed';
    }
    return false;
});



?>