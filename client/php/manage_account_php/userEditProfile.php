<?php
require '../../../vendor/autoload.php';

session_start();

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$collection = $database->users;

$action = $_POST['action'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$middleName = $_POST['middleName'];
$birthday = $_POST['birthday'];
$gender = $_POST['gender'];
$mobileNumber = $_POST['mobileNumber'];

$changePassword = $_POST['action'];

// Update user profile information
if ($action === "editProfile") {
    global $collection;

    $user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

    $collection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])],
        [
            '$set' => [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'middleName' => $middleName,
                'birthday' => $birthday,
                'gender' => $gender,
                'contact_number' => $mobileNumber
            ]
        ]
    );

    $response = ["success" => true, "message" => "Profile updated successfully."];
    echo json_encode($response);
    exit();

} else {
    $response = ["success" => false, "message" => "Invalid action."];
    echo json_encode($response);
    exit();
}
