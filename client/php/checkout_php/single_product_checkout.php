<?php
require '../../../vendor/autoload.php';

session_start(); // Ensure the session is started

header('Content-Type: application/json');


if (isset($_SESSION['user_id'])) {


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product'])) {
        // Decode and process the product data
        $productData = json_decode($_POST['product'], true);

        // Store the product data in a session variable or database for later retrieval

        $_SESSION['product_data'] = $productData;

        // Return success
        echo json_encode([
            "success" => true,
            "message" => "Product data received successfully.",
            "data" => $productData
        ]);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Retrieve the stored product data (from session or database)
        $productData = $_SESSION['product_data'] ?? null; // Return null if no data

        // Return the product data as a JSON response
        header('Content-Type: application/json');
        echo json_encode([
            "success" => true,
            "message" => "Product data retrieved successfully.",
            "data" => $productData
        ]);
        unset($_SESSION['product_data']);
    }

    die();


} else {
    echo json_encode(['success' => false, 'message' => 'You need to login first.']);
}
