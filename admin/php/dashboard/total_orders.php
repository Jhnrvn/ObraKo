<?php

require '../../../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$adminCollection = $database->admin;


$document = $adminCollection->findOne(['_id' => new MongoDB\BSON\ObjectId('675507ad4c6d76c01a53e18f')]);

$orderCount = count($document['orders']);
$approvedCount = count($document['approved']);
$canceledCount = count($document['canceled']);

header('Content-Type: application/json');
echo json_encode(['orderCount' => $orderCount, 'approvedCount' => $approvedCount, 'canceledCount' => $canceledCount]);


?>