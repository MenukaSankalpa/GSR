<?php
$schools = include 'assets/schools.php';

$lat = $_POST['latitude'];
$lon = $_POST['longitude'];
$gender = $_POST['gender'];

function haversine($lat1, $lon1, $lat2, $lon2) {
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

// calculate distance 
foreach ($filtered as &$school) {
    $school['distance'] = haversine($lat, $lon, $school['lat'], $school['lon']);
}

// sort by distance 
usort($filtered, function($a, $b){
    return $a['distance'] <=> $b['distance'];
});

//limit 5 schools to show 
$nearby = array_slice(array_filter($filtered, fn($s) => $s['distance'] <= 10), 0, 5);

if(empty($nearby)){
    echo "<p>No Schools Found</p>";
} else {
    echo "<h3>Nearby Schools:</h3><ul>";
    foreach ($nearby as $school) {
        $distance = number_format($school['distance'], 2);
        echo "<li><strong>{$school['name']}</strong> - {$school['address']} ({$distance}&nbsp;km)</li>";
    }
    echo "</ul>";
}
?>