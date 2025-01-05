<?php
// Connect to MongoDB
require '../../../vendor/autoload.php';

// mongodb database
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$collection = $database->users;

// ObraKo_E-commerce collection users schema

// Get form data sent via AJAX
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$middleName = $_POST['middleName'];
$email = $_POST['email'];
$phoneNumber = $_POST['phoneNumber'];
$birthday = $_POST['birthday'];
$gender = $_POST['gender'];
$userProfile = $_POST['userProfile'];
$password = $_POST['password'];

// Check if the user already exists by phone number (or email if preferred)
$existingUser = $collection->findOne(['email' => $email]);

if ($existingUser) {
    echo json_encode(["success" => false, "message" => "User already exists!"]);
    exit;
}

// Hash the password 
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Prepare data to be inserted into MongoDB
$userData = [
    // user information
    'firstName' => $firstName,
    'lastName' => $lastName,
    'middleName' => $middleName,
    'contact_number' => $phoneNumber,
    'birthday' => $birthday,
    'gender' => $gender,
    'user_profile' => $userProfile,
    'password' => $hashedPassword,
    // google login information
    'google_id' => null,
    'email' => $email,
    'google_full_name' => null,
    'profile_picture' => null,
    // user address
    'address' => [],
    'role' => 'user',
    'is_verified' => false,
    'registered_at' => new MongoDB\BSON\UTCDateTime(),
    'last_login' => null,
    'cart' => [],
    'orders' => [],
    'order_history' => [],
    'wishlist' => [],
    'login_methods' => [
        'standard' => true,
        'google' => false,
    ],
];

// Insert data into MongoDB
$result = $collection->insertOne($userData);
if ($result->getInsertedCount() === 1) {
    // send success registration response to client
    echo json_encode(["success" => true, "message" => "User registered successfully!"]);
} else {
    // send error registration response to client
    echo json_encode(["success" => false, "message" => "Error registering user."]);
}

