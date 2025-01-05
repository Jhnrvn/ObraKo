<?php
require '../../../vendor/autoload.php';

session_start();  // start the session

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$collection = $database->users;

$user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

$action = $_POST['action'];

if ($action === "addAddress") {
    global $collection;

    $fullName = $_POST['fullName'];
    $contactNumber = $_POST['mobileNumber'];
    $unitNumber = $_POST['unitNumber'];
    $provinceName = $_POST['provinceName'];
    $province = $_POST['province'];
    $cityName = $_POST['cityName'];
    $city = $_POST['city'];
    $barangayName = $_POST['barangayName'];
    $barangay = $_POST['barangay'];

    $userAddress = [
        'fullName' => $fullName,
        'mobileNumber' => $contactNumber,
        'unitNumber' => $unitNumber,
        'province_name' => $provinceName,
        'province' => $province,
        'city_name' => $cityName,
        'city' => $city,
        'barangay_name' => $barangayName,
        'barangay' => $barangay,
        'is_default' => false
    ];


    $collection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])],
        [
            '$push' => [
                'address' => $userAddress,
            ]
        ]
    );

    $response = ["success" => true, "message" => "added address successfully."];
    echo json_encode($response);
    exit();

} else {
    $response = ["success" => false, "message" => "Invalid action."];
    echo json_encode($response);
    exit();
}






