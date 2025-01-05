<?php
require '../../../vendor/autoload.php';

session_start();  // Start the session

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$collection = $database->users;

if (isset($_POST['index'])) {
    $index = (int) $_POST['index'];
} else {
    echo json_encode(['error' => 'Index not provided']);
    exit;
}

$user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

if ($user && isset($user['address'][$index])) {
    $userAddress = $user['address'][$index];

    $response = [
        'success' => true,
        'index' => $index,
        'fullName' => $userAddress['fullName'] ?? '',
        'mobileNumber' => $userAddress['mobileNumber'] ?? '',
        'unitNumber' => $userAddress['unitNumber'] ?? '',
        'province_name' => $userAddress['province_name'] ?? '',
        'province' => $userAddress['province'] ?? '',
        'city_name' => $userAddress['city_name'] ?? '',
        'city' => $userAddress['city'] ?? '',
        'barangay_name' => $userAddress['barangay_name'] ?? '',
        'barangay' => $userAddress['barangay'] ?? '',
    ];
    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Address not found']);
}


