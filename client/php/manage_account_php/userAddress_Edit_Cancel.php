<?php

require '../../../vendor/autoload.php';

session_start();  // start the session

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$collection = $database->users;


$user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
$userCurrentAddress = $user['address'][$index];

if ($user) {
    $userAddress = [
        'fullName' => $userCurrentAddress['fullName'],
        'unitNumber' => $userCurrentAddress['unitNumber'],
        'province' => $userCurrentAddress['province'],
        'city' => $userCurrentAddress['city'],
        'barangay' => $userCurrentAddress['barangay'],
        'mobileNumber' => $userCurrentAddress['mobileNumber'],
    ];
}



