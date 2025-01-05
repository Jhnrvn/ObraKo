<?php
require '../../../vendor/autoload.php'; 
use MongoDB\Client;

header('Content-Type: application/json');

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$adminCollection = $database->admin;
$userCollection = $database->users;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    try {
       
        // Update order status in the admin collection
        $adminUpdateResult = $adminCollection->updateOne(
            ['orders.order_id' => $order_id],
            ['$set' => ['orders.$.status' => $new_status]]
        );

        // Update order status in the user collection
        $userUpdateResult = $userCollection->updateOne(
            ['orders.order_id' => $order_id],
            ['$set' => ['orders.$.status' => $new_status]]
        );

        if ($adminUpdateResult->getMatchedCount() > 0 && $userUpdateResult->getMatchedCount() > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Order status updated successfully!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Order not found in one or both collections.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}
