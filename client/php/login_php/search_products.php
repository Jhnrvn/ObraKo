<?php
require '../../../vendor/autoload.php';


$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$productsCollection = $database->products;

if (isset($_GET['query'])) {
    $query = $_GET['query'];


    $filter = [
        '$or' => [
            ['name' => new MongoDB\BSON\Regex($query, 'i')],
            ['description' => new MongoDB\BSON\Regex($query, 'i')]
        ]
    ];

    $results = $productsCollection->find($filter);

    $productArray = iterator_to_array($results);

    header('Content-Type: application/json');
    echo json_encode($productArray); // Send JSON response
}
