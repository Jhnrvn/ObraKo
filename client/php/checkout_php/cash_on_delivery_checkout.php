<?php
require "../../../vendor/autoload.php";

session_start(); // start the session

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$usersCollection = $database->users;
$productsCollection = $database->products;
$adminCollection = $database->admin;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedProducts = json_decode($_POST['product_data'], true);
    $userAddress = json_decode($_POST['user_address'], true);

    // Fetch user ID from session
    $userId = $_SESSION['user_id'] ?? null;

    // Proceed with database operations
    $selectedProductIds = array_map(fn($product) => new MongoDB\BSON\ObjectId($product['product_id']), $selectedProducts);
    $updateCartResult = $usersCollection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($userId)],
        ['$pull' => ['cart' => ['product_id' => ['$in' => $selectedProductIds]]]]
    );

    foreach ($selectedProducts as $product) {
        $productId = new MongoDB\BSON\ObjectId($product['product_id']);
        $quantity = (int) $product['quantity'];

        $productsCollection->updateOne(
            ['_id' => $productId],
            ['$inc' => ['stocks' => -$quantity]] // Subtract quantity from stocks
        );
    }

    // Add selected products to user's order
    $orderData = [
        'order_id' => uniqid("order_", true),
        'user_id' => new MongoDB\BSON\ObjectId($userId),
        'mode_of_payment' => $_POST['mode_of_payment'],
        'transaction_id' => "N/A",
        'user_address' => $userAddress,
        'product' => $selectedProducts,
        'order_date' => new MongoDB\BSON\UTCDateTime(),
        'status' => 'pending',
    ];

    $addOrderResult = $usersCollection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($userId)],
        ['$push' => ['orders' => $orderData]]
    );

    $adminId = $adminCollection->find([])->toArray()[0]['_id'];

    $addOrderResultAdmin = $adminCollection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($adminId)],
        ['$push' => ['orders' => $orderData]]
    );


    // ! remove $updateCartResult->getModifiedCount() from if condition below
    if ($addOrderResult->getModifiedCount() > 0) {
        echo json_encode(["status" => 1, "message" => "Transaction successful"]);
    } else {
        echo json_encode(["error" => "Failed to update records."]);
    }
} else {
    echo json_encode(["error" => "Invalid request method."]);
}

