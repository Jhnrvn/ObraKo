<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_ids'])) {
    $_SESSION['selected_product_ids'] = $_POST['product_ids'];
    echo json_encode(['success' => true, 'message' => 'Product ids added to session.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}