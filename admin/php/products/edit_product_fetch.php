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

if ($product) {
    $product['_id'] = (string) $product['_id'];
    header('Content-Type: application/json');
    echo json_encode(["success" => true, "product" => $product]);   
    exit();
} else {
    echo json_encode(["success" => false, "message" => "Product not found."]);
    exit();
}
?>