<?php
require '../../../vendor/autoload.php';

session_start();  // start the session

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$collection = $database->users;

$user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

$index = (int) $_POST['index'];

$unsetResult = $collection->updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])],
    ['$unset' => ["address.$index" => ""]]
);

if ($unsetResult->getModifiedCount() > 0) {
    $pullResult = $collection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])],
        ['$pull' => ['address' => null]]
    );
}

$response = ["success" => true, "message" => "Address deleted successfully."];
echo json_encode($response);
exit();
