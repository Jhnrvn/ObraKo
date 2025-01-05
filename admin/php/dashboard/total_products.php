<?php
require '../../../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$productsCollection = $database->products;


$productsCount = $productsCollection->countDocuments([]);

header('Content-Type: application/json');
echo json_encode(["productsCount" => $productsCount]);

?>