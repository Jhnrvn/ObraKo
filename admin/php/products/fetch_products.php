<?php
require '../../../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$collection = $database->products;

$products = $collection->find();

$result = [];

foreach ($products as $product) {
    $result[] = [
        'id' => (string) $product['_id'],
        'name' => $product['name'],
        'image' => $product['image'],
        'origin' => $product['origin'],
        'price' => $product['price'],
        'description' => $product['description'],
        'category' => $product['category'],
        'stocks' => $product['stocks'],
    ];
}

header('Content-Type: application/json');
echo json_encode($result);
