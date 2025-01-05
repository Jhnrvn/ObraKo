<?php

require "../../../vendor/autoload.php";

session_start(); // session start

// MongoDB connection
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$productsCollection = $database->products; // product collection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $products = $productsCollection->find(['category' => $category]);
    $productsArray = iterator_to_array($products);

    header('Content-Type: application/json');
    echo json_encode($productsArray);
}

