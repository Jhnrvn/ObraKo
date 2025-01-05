<?php
require '../../../vendor/autoload.php';

session_start();

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$userCollection = $database->users;




$user = $userCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

if ($user) {
    $index = (int) $_POST['index'];
    $quantity = (int) $_POST['quantity'];
    $shippingFee = $user['cart'][$index]['product_shipping_fee'];

    $user['cart'][$index]['quantity'] = $quantity;
    $user['cart'][$index]['product_total_price'] = ($user['cart'][$index]['product_price'] * $quantity) + $shippingFee;

    $updateResult = $userCollection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])],
        ['$set' => ['cart' => $user['cart']]]
    );


    if ($updateResult->getModifiedCount() > 0) {
        $response = ["success" => true, "message" => "Product quantity updated successfully."];
        echo json_encode($response);
        exit();
    } else {
        $response = ["success" => false, "message" => "Failed to update product quantity."];
        echo json_encode($response);
        exit();
    }

}

