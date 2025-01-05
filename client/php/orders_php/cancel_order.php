<?php
require '../../../vendor/autoload.php';

session_start();  // start the session

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$usersCollection = $database->users;
$productsCollection = $database->products;
$adminCollection = $database->admin;

$user = $usersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

if (!isset($_POST['index'])) {
    echo json_encode(["success" => false, "message" => "Order index not provided."]);
    exit;
}

$index = (int) $_POST['index']; // index of the order to cancel 

$order = $user['orders'][$index]; // get the order to cancel
$productQuantity = $order['product'][0]['quantity']; // get the quantity of the product in the order

// ! get the admin id
$adminId = $adminCollection->find([])->toArray()[0]['_id'];

$order_id = $order['order_id'];

// ! Push the canceled order to the 'canceled' field in admin's collection
$addToCanceled = $adminCollection->updateOne(
    [
        '_id' => new MongoDB\BSON\ObjectId($adminId),
        'orders.order_id' => $order_id
    ],
    [
        '$push' => ['canceled' => $order]  // Push the canceled order to the 'canceled' field
    ]
);

// ! delete the order from the admin's orders
$deleteOrderResult = $adminCollection->updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($adminId)],
    ['$pull' => ['orders' => ['order_id' => $order_id]]]
);

$product = $productsCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($order['product'][0]['product_id'])]); // get the product from the database

// ! update the quantity of the product in the database
$productsCollection->updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($order['product'][0]['product_id'])],
    ['$inc' => ['stocks' => $productQuantity]]
);

$userId = $_SESSION['user_id'];
// ! delete the order from the user's orders
$unsetResult = $usersCollection->updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])],
    ['$unset' => ["orders.$index" => ""]]
);

// ! clean up null values from the user's orders array
if ($unsetResult->getModifiedCount() > 0) {
    $pullResult = $usersCollection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])],
        ['$pull' => ['orders' => null]]
    );
}

// ! send a success response
echo json_encode(["success" => true, "message" => "The order has been cancelled."]);
exit();
