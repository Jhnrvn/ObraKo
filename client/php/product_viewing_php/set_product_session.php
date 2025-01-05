<?php
require '../../../vendor/autoload.php';

session_start();

if (isset($_POST['product_id'])) {

    $productId = $_POST['product_id'];
    $_SESSION['product_id'] = $productId;
    echo json_encode(["success" => true, "message" => "Product ID set in session"]);
} else {
    echo json_encode(['success' => false]);
}
?>