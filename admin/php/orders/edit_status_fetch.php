<?php
require '../../../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$adminCollection = $database->admin;

$adminDocument = $adminCollection->findOne([]);

$order_id = $_POST['order_id'];
// Find the specific order using $elemMatch
$result = $adminCollection->findOne(
    ['orders.order_id' => $order_id],
    ['projection' => ['orders.$' => 1]]
);

header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'order' => $result['orders'][0]
]);


