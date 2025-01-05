<?php
require '../../../vendor/autoload.php';

session_start();  // start the session

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$collection = $database->users;


$user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

if (isset($_POST['index'])) {
    $index = (int) $_POST['index'];
} else {
    echo json_encode(['error' => 'Index not provided']);
    exit;
}



if ($user && isset($user['address'][$index])) {
    global $collection;

    $collection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])],
        ['$set' => ['address.$[].is_default' => false]]
    );

    // Update the specific address in MongoDB using the provided index
    $collection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])],
        [
            '$set' => [
                "address.$index" => [
                    'fullName' => $_POST['fullName'],
                    'mobileNumber' => $_POST['mobileNumber'],
                    'unitNumber' => $_POST['unitNumber'],
                    'province_name' => $_POST['province_name'],
                    'province' => $_POST['province'],
                    'city_name' => $_POST['city_name'],
                    'city' => $_POST['city'],
                    'barangay_name' => $_POST['barangay_name'],
                    'barangay' => $_POST['barangay'],
                    'is_default' => true
                ]
            ]
        ]
    );

    echo json_encode(['success' => true, 'message' => 'Address updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Address not found at the given index']);
}


?>