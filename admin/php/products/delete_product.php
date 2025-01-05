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

$deleteResult = $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($product_id)]);

if ($deleteResult->getDeletedCount() > 0) {
    echo json_encode(["success" => true, "message" => "Product deleted successfully."]);
    exit();
} else {
    echo json_encode(["success" => false, "message" => "Failed to delete product."]);
    exit();
}
