<?php
require '../../../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$collection = $database->products;

if (!isset($_POST['product_id'])) {
    echo json_encode(["success" => false, "message" => "Product ID not provided."]);
    exit();
}

$product_id = $_POST['product_id'];

$product = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($product_id)]);

$product['name'] = $_POST['product_name'];
$product['origin'] = $_POST['product_origin'];
$product['category'] = $_POST['product_category'];
$product['price'] = (float) $_POST['product_price'];
$product['stocks'] = (int) $_POST['product_stocks'];
$product['description'] = $_POST['product_description'];

$collection->updateOne(['_id' => new MongoDB\BSON\ObjectId($product_id)], ['$set' => $product]);

echo json_encode(["success" => true, "message" => "Product updated successfully."]);
exit();
