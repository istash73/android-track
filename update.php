<?php
// Firebase Realtime Database URL (replace with your actual database URL)
$firebaseUrl = "https://tracking-device-c1d82-default-rtdb.europe-west1.firebaseio.com/devices";

// Read data from POST request
$imei  = isset($_POST['imei'])  ? $_POST['imei']  : '';
$lat   = isset($_POST['lat'])   ? $_POST['lat']   : '';
$lon   = isset($_POST['lon'])   ? $_POST['lon']   : '';
$fall  = isset($_POST['fall'])  ? $_POST['fall']  : '';
$sos   = isset($_POST['sos'])   ? $_POST['sos']   : '';
$rad   = isset($_POST['radius'])? $_POST['radius']: '';
$gfLat = isset($_POST['gf_lat'])? $_POST['gf_lat']: '';
$gfLon = isset($_POST['gf_lon'])? $_POST['gf_lon']: '';

// Validate IMEI
if (empty($imei)) {
    echo "IMEI is required.";
    exit;
}

// Create the full Firebase path for this IMEI
$url = "$firebaseUrl/$imei.json";

// Prepare the data
$data = array(
    "location" => array(
        "lat" => $lat,
        "lon" => $lon
    ),
    "fall" => $fall,
    "sos" => $sos,
    "geofence" => array(
        "radius" => $rad,
        "lat" => $gfLat,
        "lon" => $gfLon
    )
);

// Encode data to JSON
$jsonData = json_encode($data);

// Use cURL to send HTTP POST to Firebase
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

$response = curl_exec($ch);
curl_close($ch);

// Output response
echo "Firebase Response: " . $response;