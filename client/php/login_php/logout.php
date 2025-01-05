<?php
require '../../../vendor/autoload.php';

session_start();

if (isset($_SESSION['user_id'])) {

    session_unset();
    session_destroy();

    echo json_encode(["success" => true, "message" => "Logout successful"]);
}
;