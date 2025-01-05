<?php

require '../../../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$adminCollection = $database->admin;

// Find the first admin document (assuming the admin is the only document)
$adminDocument = $adminCollection->findOne([]);

// Retrieve all orders from the orders field
$allOrders = $adminDocument['orders'];


$result = [];

foreach ($allOrders as $order) {
    $result[] = [
        'order_id' => $order['order_id'], // Order ID
        'user_id' => (string) $order['user_id'], // User ID (converted to string from ObjectId)
        'mode_of_payment' => $order['mode_of_payment'], // Mode of payment
        'user_address' => $order['user_address'], // Address details
        'order_date' => $order['order_date']->toDateTime()->format('d-m-Y'), // Format the order date
        'product' => $order['product'], // Products in the order
        'status' => $order['status'], // Order status
        'quantity' => $order['product'][0]['quantity'], // Quantity of the first product (if only one product)
    ];
}

header('Content-Type: application/json');
echo json_encode($result);
