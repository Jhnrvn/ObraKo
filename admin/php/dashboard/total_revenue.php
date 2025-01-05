<?php
require '../../../vendor/autoload.php';

try {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    $databaseName = "ObraKo_E-commerce";
    $database = $mongoClient->$databaseName;
    $adminCollection = $database->admin;


    $document = $adminCollection->findOne([]);

    if ($document) {
        // Initialize total price variable
        $totalRevenue = 0;

        // Loop through the approved orders and add up product prices
        foreach ($document['approved'] as $order) {
            foreach ($order['product'] as $product) {
                $totalRevenue += $product['product_price'];
            }
        }

        echo json_encode(['totalRevenue' => $totalRevenue]);
    } else {
        echo "Document not found.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
