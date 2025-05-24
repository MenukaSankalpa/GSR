<?php
// sample schools list(Kalutara)
header('Content-Type: application/json');
$schools = [
    [
        'name' => 'Kalutara Balika Vidyalaya',
        'address' => 'Galle Road, Kalutara',
        'lat' => 6.5830,
        'lon' => 79.9603,
        'type' => 'girls',
    ],
    [
        'name' => 'Kalutara Vidyalaya',
        'address' => 'Galle Road, Kalutara North',
        'lat' => 6.5892,
        'lon' => 79.9609,
        'type' => 'boys',
    ],
    [
        'name' => 'Kalutara Gnanodaya Vidyalaya',
        'address' => 'Nagoda Road, Kalutara',
        'lat' => 6.5928,
        'lon' => 79.9645,
        'type' => 'mixed',
    ],
    [
        'name' => 'Holy Family Convent, Kalutara',
        'address' => 'Galle Road, Kalutara South',
        'lat' => 6.5825,
        'lon' => 79.9590,
        'type' => 'girls',
    ],
    [
        'name' => 'Dharmapala Maha Vidyalaya, Kalutara',
        'address' => 'Station Road, Kalutara South',
        'lat' => 6.5817,
        'lon' => 79.9641,
        'type' => 'mixed',
    ],
    [
        'name' => 'St. John’s College, Kalutara',
        'address' => 'St. John’s Mawatha, Kalutara North',
        'lat' => 6.5910,
        'lon' => 79.9617,
        'type' => 'boys',
    ],
    [
        'name' => 'Pothuwila Maha Vidyalaya',
        'address' => 'Pothuwila Road, Payagala, Kalutara District',
        'lat' => 6.5250,
        'lon' => 79.9614,
        'type' => 'mixed',
    ],
    [
        'name' => 'Tissa Central College',
        'address' => 'Galle Road, Kalutara South',
        'lat' => 6.5834,
        'lon' => 79.9595,
        'type' => 'mixed',
    ],   
];

echo json_encode($schools);
?>