<?php
require '../../../vendor/autoload.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You need to login first.']);
    exit();
} else {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    $databaseName = "ObraKo_E-commerce";
    $database = $mongoClient->$databaseName;
    $productsCollection = $database->products;
    $usersCollection = $database->users;

    // Fetch user ID from session
    $userId = $_SESSION['user_id'] ?? null;

    // Fetch input data
    $productId = $_POST['productId'] ?? null;
    $quantity = $_POST['quantity'] ?? null;
    $shippingFee = (float) $_POST['shippingFee'] ?? null;

    // Validate inputs
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit;
    }

    if (!$productId || !$quantity || $quantity < 1 || !$shippingFee) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
        exit;
    }

    try {
        // Find product in the database
        $product = $productsCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($productId)]);

        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Product not found.']);
            exit;
        }

        // Check if the product already exists in the user's cart
        $user = $usersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($userId)]);

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'User not found.']);
            exit;
        }

        $cart = $user['cart'] ?? [];
        $productExists = false;

        foreach ($cart as &$cartItem) {
            if ((string) $cartItem['product_id'] === $productId) {
                // If product exists, update its quantity
                $cartItem['quantity'] += (int) $quantity;
                $productExists = true;
                break;
            }
        }

        if ($productExists) {
            // Update the cart with the modified item
            $usersCollection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($userId)],
                ['$set' => ['cart' => $cart]]
            );
        } else {
            // If product does not exist, add it to the cart
            $usersCollection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($userId)],
                [
                    '$push' => [
                        'cart' => [
                            'product_id' => new MongoDB\BSON\ObjectId($productId),
                            'product_name' => $product['name'],
                            'product_image' => $product['image'],
                            'product_price' => $product['price'],
                            'product_shipping_fee' => $shippingFee,
                            'product_total_price' => ($product['price'] * $quantity) + $shippingFee,
                            'quantity' => (int) $quantity,
                            'product_category' => $product['category'],
                        ],
                    ]
                ]
            );
        }

        echo json_encode(['success' => true, 'message' => 'Product added/updated in cart successfully.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
}
