<?php
require '../../../vendor/autoload.php';

session_start();

// Initialize MongoDB client
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$userCollection = $database->users;

// Verify if the user is logged in
if (isset($_SESSION['user_id'])) {
    try {
        // Retrieve the user based on their session ID
        $user = $userCollection->findOne([
            '_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])
        ]);

        if ($user && isset($user['order_history'])) {
            // Fetch order history
            $orderHistory = $user['order_history'];

            // Convert BSON dates to formatted strings
            $formattedOrders = [];
            foreach ($orderHistory as $order) {
                $order['order_date'] = $order['order_date']->toDateTime()->format('d-m-Y');
                $formattedOrders[] = $order;
            }

            header('Content-Type: application/json');
            // Respond with JSON
            echo json_encode($formattedOrders);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No order history found.',
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in.',
    ]);
}
?>