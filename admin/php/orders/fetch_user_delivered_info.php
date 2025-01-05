<?php
require '../../../vendor/autoload.php';

#mongodb database connection
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$adminCollection = $database->admin;

$adminDocument = $adminCollection->findOne([]);

$order_id = $_POST['order_id'];
// Find the specific order using $elemMatch
$result = $adminCollection->findOne(
    ['approved.order_id' => $order_id],
    ['projection' => ['approved.$' => 1]]
);

header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'order' => $result['approved'][0]
]);



?>