<?php
require '../../../vendor/autoload.php';

header('Content-Type: application/json');

// Initialize MongoDB client
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$adminCollection = $database->admin;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'] ?? null;

    if ($orderId) {
        try {
            // Log the received order ID for debugging
            error_log("Received order_id: " . $orderId);

            // Check if the order exists in the canceled field
            $orderExists = $adminCollection->findOne(
                ['canceled.order_id' => $orderId]
            );

            // Log the result of the find operation
            if ($orderExists) {
                error_log("Order found: " . json_encode($orderExists));
            } else {
                error_log("Order with ID '$orderId' not found in canceled list.");
            }

            if ($orderExists) {
                // Use $pull to remove the specific order from the canceled array
                $result = $adminCollection->updateOne(
                    ['canceled.order_id' => $orderId],
                    ['$pull' => ['canceled' => ['order_id' => $orderId]]]
                );

                // Log the update result
                error_log("Update result: " . json_encode($result));

                // Check if the update was successful
                if ($result->getModifiedCount() > 0) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Order deleted successfully.',
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Order not found in canceled list or deletion failed.',
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Order not found in canceled list.',
                ]);
            }
        } catch (Exception $e) {
            // Log and display the exception message for debugging
            error_log("Error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid order ID.',
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.',
    ]);
}
?>
