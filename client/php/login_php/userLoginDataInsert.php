<?php
// connect to mongoDB
require_once '../../../vendor/autoload.php';

session_start();

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$collection = $database->users;

function login(string $email, string $password)
{

    global $collection;

    $user = $collection->findOne(['email' => $email]);

    if ($user && password_verify($password, $user['password'])) {

        echo json_encode(["success" => true, "message" => "Login successful"]);

        $_SESSION['user_id'] = (string) $user['_id'];

        $lastLogin = new MongoDB\BSON\UTCDateTime();

        $collection->updateOne(
            ['_id' => $user['_id']],
            ['$set' => ['last_login' => $lastLogin, 'login_methods.standard' => true, 'login_methods.google' => false]]
        );
        exit();

    } else {
        $response = null;
        echo json_encode(["success" => false, "message" => "wrong Email or password."]);
    }

}

$action = $_POST['action'] ?? null;

$response = null;
if ($action === "login") {
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? '';
    $response = login($email, $password);
} else {
    $response = json_encode(['status' => 'error', 'message' => 'Invalid action.']);
}

header('Content-Type: application/json');
echo $response;
