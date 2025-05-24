<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != '1') {
    header("Location: login.php");
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
    <link rel="stylesheet" href="css/pdashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
</head>
<body>
    <header class="dashboard-header">
        <nav>
            <ul>
                <li><a href="#" id="showFormBtn">APPLY SCHOOLS</a></li>
                <li><a href="view_applications.php">VIEW APPLICATIONS</a></li>
                <li><a href="submit_complaint.php">SUBMIT COMPLAINT</a></li>
            </ul>
        </nav>
    </header>

    <div class="header-text" id="header-text"  >
        <h1>
            <span style="--i:1">H</span>
            <span style="--i:2">E</span>
            <span style="--i:3">L</span>
            <span style="--i:4">L</span>
            <span style="--i:5">O</span>
            <span style="--i:6">!</span>
        </h1>
    </div>

    <div class="container" id="registrationForm" style="display: none;">
        <h2>Apply For Schools</h2>
        <form id="applyForm" method="POST">

            <div class="input-group">
                <input type="text" name="nin" placeholder="Parent NIC Number" required>
                <i class="ri-info-card-fill"></i>
            </div>

            <div class="input-group">
                <input type="text" name="child_name"  value="<?php echo htmlspecialchars($user['child_name']); ?>" readonly>
                <i class="ri-account-box-fill"></i>
            </div>

            <div class="input-group">
                <select id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="boy">Boy</option>
                    <option value="girl">Girl</option>
                </select>
            </div>

            <div class="input-group">
                <input id="address" type="text" name="address" placeholder="Address" required>
                <i class="ri-user-location-fill"></i>
            </div>
            
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

            <div id="schoolResults"></div>
        
            <button type="button" id="findSchoolsBtn">Find Nearby Schools</button>
        </form>
    </div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    
<script>
    document.getElementById('showFormBtn').addEventListener('click', function(event) {
        event.preventDefault();

        const form = document.getElementById('registrationForm');
        const headerText = document.getElementById('header-text');

        const isFormVisible = form.style.display === 'block';

        form.style.display = isFormVisible ? 'none' : 'block';
        headerText.style.display = isFormVisible ? 'flex' : 'none';
    });
</script>
<script>
    document.getElementById('findSchoolsBtn').addEventListener('click', async function () {
        const gender = document.getElementById('gender').value;
        const address = document.getElementById('address').value;

        if(!gender || !address) {
            alert("Please select gender and enter an address");
            return;
        }

        // get coordinates from nominatim API 
        const query = encodeURIComponent(address);
        const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`);
        const data = await response.json();

        if(data.length === 0) {
            alert("Location not found.");
            return;
        }

        // fetch school list 
        const schoolRes = await fetch('assets/schools.php');
        const schools = await schoolRes.json();

        const filteredSchools = schools.filter(school => {
            const distance = getDistanceFromLatLonInKm(lat, lng, school.lat, school.lng);
            if (distance <= 10){
                if (gender === 'boy') return ['boy', 'mixed'].includes(school.type);
                if (gender === 'girl') return ['girl', 'mixed'].includes(school.type);
            }
            return false;
        }).slice(0, 5)
    })
</script>
</body>
</html>