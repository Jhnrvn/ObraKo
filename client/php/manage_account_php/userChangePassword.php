<?php
require '../../../vendor/autoload.php';

session_start();

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$collection = $database->users;

$action = $_POST['action'];
if ($action === "tryChangePassword") {
    global $collection;
    $user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

    $currentPassword = $user['password'];

    if ($currentPassword == null) {
        $response = ["success" => false, "message" => "You don't have a password set on this account."];
        echo json_encode($response);
        exit();
    } else {
        $response = ["success" => true, "message" => "You have a password set on this account."];
        echo json_encode($response);
        exit();
    }

} else if ($action === "setPassword") {
    global $collection;
    $user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $collection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])],
        [
            '$set' => [
                'password' => $hashedPassword,
            ]
        ]
    );

    $response = ["success" => true, "message" => "Password changed successfully."];
    echo json_encode($response);
    exit();

} else if ($action === "changePassword") {
    global $collection;
    $user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    if (password_verify($currentPassword, $user['password'])) {
        $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])],
            [
                '$set' => [
                    'password' => $hashedNewPassword,
                ]
            ]
        );

        $response = ["success" => true, "message" => "Password changed successfully."];
        echo json_encode($response);
        exit();
    } else {
        $response = ["success" => false, "message" => "Current password is incorrect."];
        echo json_encode($response);
        exit();
    }
}






