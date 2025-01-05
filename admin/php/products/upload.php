<?php

require '../../../vendor/autoload.php'; // MongoDB PHP Library

// MongoDB Connection
$client = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$collection = $client->$databaseName->products;

use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '../../../../');
$dotenv->load();

// ImgBB API Key
$apiKey = $_ENV['imgBB_API_KEY'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect product details from POST request
    $name = $_POST['product-name'] ?? '';
    $origin = $_POST['product-origin'] ?? '';
    $category = $_POST['product-category'] ?? '';
    $stocks = (int) $_POST['product-stocks'] ?? 0;
    $price = (float) $_POST['product-price'] ?? 0;
    $description = $_POST['product-description'] ?? '';
    $productImages = $_FILES['additionalImages'] ?? null;

    // Check if all required fields are provided
    if (!$name || !$price || !$productImages) {
        echo json_encode(['error' => 'Name, price, and image are required.']);
        exit;
    }

    // Function to upload image to ImgBB
    function uploadImage($image)
    {
        global $apiKey;
        $imageData = file_get_contents($image['tmp_name']);
        $base64Image = base64_encode($imageData);

        $url = "https://api.imgbb.com/1/upload?key=$apiKey";
        $postData = [
            'key' => $apiKey,
            'image' => $base64Image
        ];

        // Initialize cURL for the API call
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
            curl_close($ch);
            return null;
        }
        curl_close($ch);

        $responseData = json_decode($response, true);
        return isset($responseData['data']['url']) ? $responseData['data']['url'] : null;
    }


    // Upload additional images
    $additionalImageUrls = [];
    if ($productImages) {
        foreach ($productImages['tmp_name'] as $index => $tmpName) {
            if ($tmpName) {
                $additionalImageUrl = uploadImage([
                    'tmp_name' => $tmpName,
                    'name' => $productImages['name'][$index]
                ]);
                if ($additionalImageUrl) {
                    $additionalImageUrls[] = $additionalImageUrl;
                }
            }
        }
    }

    // Create product document for MongoDB
    $product = [
        'name' => $name,
        'origin' => $origin,
        'image' => $additionalImageUrls[0],
        'additionalImages' => $additionalImageUrls,
        'price' => (float) $price,
        'description' => $description,
        'category' => $category,
        'stocks' => (int) $stocks,
        'createdAt' => new MongoDB\BSON\UTCDateTime()
    ];

    // Insert product into MongoDB collection
    $result = $collection->insertOne($product);

    // Check if the product was added successfully
    if ($result->getInsertedCount() > 0) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Product added successfully!']);
    } else {
        echo json_encode(['error' => 'Failed to add product.']);
    }
}
