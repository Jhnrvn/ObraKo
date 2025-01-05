<?php
require '../../../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$collection = $database->products;

// Get the page number from the request
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 18; // Number of products per page
$skip = ($page - 1) * $limit;

$products = $collection->find([], ['limit' => $limit, 'skip' => $skip]);

$productsArray = iterator_to_array($products);

echo json_encode($productsArray);
?>