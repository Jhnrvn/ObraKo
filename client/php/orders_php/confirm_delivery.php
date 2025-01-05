<?php
require '../../../vendor/autoload.php';

header('Content-Type: application/json');

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$userCollection = $database->users;
$adminCollection = $database->admin;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderIndex = $_POST['index'] ?? null;

    if ($orderIndex !== null) {
        try {
            // Find the user document
            $user = $userCollection->findOne(['orders' => ['$exists' => true]]);

            if ($user) {

                if (isset($user['orders'][$orderIndex])) {

                    $orderToMove = $user['orders'][$orderIndex];


                    $userResult = $userCollection->updateOne(
                        ['_id' => $user['_id']], // Match the user by ID
                        [
                            '$pull' => ['orders' => ['order_id' => $orderToMove['order_id']]],
                            '$push' => ['order_history' => $orderToMove],
                        ]
                    );

                    if ($userResult->getModifiedCount() > 0) {
                        $adminResult = $adminCollection->updateOne(
                            ['orders.order_id' => $orderToMove['order_id']], // 
                            [
                                '$pull' => ['orders' => ['order_id' => $orderToMove['order_id']]], // Remove from orders
                                '$push' => ['approved' => $orderToMove], // Add to 
                            ]
                        );

                        if ($adminResult->getModifiedCount() > 0) {
                            echo json_encode([
                                'success' => true,
                                'message' => 'The delivery for the user has been confirmed.',
                            ]);
                        } else {
                            echo json_encode([
                                'success' => false,
                                'message' => 'Failed to update admin records.',
                            ]);
                        }
                    } else {
                        echo json_encode([
                            'success' => false,
                            'message' => 'Failed to confirm the delivery for the user.',
                        ]);
                    }
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Invalid order index for the user.',
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'User not found or no orders available.',
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
            'message' => 'Invalid order index.',
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.',
    ]);
}
