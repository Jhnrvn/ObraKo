<?php
require '../../../vendor/autoload.php';

session_start();

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$usersCollection = $database->users;

$user = $usersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

// count how many products user put to the cart
$homepageCountCounter = $_GET['action'];

if ($homepageCountCounter === "updateCartCounter") {
    $cart = $user['cart'] ?? [];
    $cartCount = count($cart);

    // Return the product count
    echo json_encode(['success' => true, 'cartCount' => $cartCount]);
}
