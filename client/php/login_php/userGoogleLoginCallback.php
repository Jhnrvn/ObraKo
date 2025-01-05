<?php

require '../../../vendor/autoload.php';

// start a session
session_start();

use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '../../../../');
$dotenv->load();


$clientID = $_ENV['google_client_id'];
$clientSecret = $_ENV['google_client_secret'];
$redirectURI = $_ENV['google_redirect_uri'];

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectURI);

if (isset($_GET["code"])) {

  $token = $client->fetchAccessTokenWithAuthCode($_GET["code"]);
  $client->setAccessToken($token);
  $service = new Google_Service_Oauth2($client);
  $user = $service->userinfo->get();
  $email = $user->email;
  $googleId = $user->id;
  $username = $user->name;
  $profile = $user->picture;

  $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
  $databaseName = "ObraKo_E-commerce";
  $database = $mongoClient->$databaseName;
  $collection = $database->users;

  // check if the user already exists
  $existingUserEmail = $collection->findOne(['email' => $email]);
  if (!$existingUserEmail) {

    global $collection;

    // ObraKo_E-commerce collection users schema
    $userData = [
      'firstName' => null,
      'lastName' => null,
      'middleName' => null,
      'contact_number' => null,
      'birthday' => null,
      'gender' => null,
      'user_profile' => null,
      'password' => null,
      // google login information
      'google_id' => $googleId,
      'email' => $email,
      'google_full_name' => $username,
      'profile_picture' => $profile,
      // user address
      'address' => [],
      'role' => 'user',
      'is_verified' => false,
      'registered_at' => new MongoDB\BSON\UTCDateTime(),
      'last_login' => new MongoDB\BSON\UTCDateTime(),
      'cart' => [],
      'orders' => [],
      'order_history' => [],
      'wishlist' => [],
      'login_methods' => [
        'standard' => false,
        'google' => true,
      ],
    ];

    // if the user is a new user then insert the user data into the collection
    $collection->insertOne($userData);
    // find the user by email to get user id and store it in session
    $user = $collection->findOne(['email' => $email]);
    // user id session  
    $_SESSION['user_id'] = (string) $user['_id'];

    header("Location: /client/index.php");
    exit();

  } else {

    $existingUser = $collection->findOne(['google_id' => $googleId]);
    if ($existingUser) {

      global $collection;
      $collection->updateOne(
        ['email' => $email],
        ['$set' => ['google_full_name' => $username, 'profile_picture' => $profile, 'login_methods.google' => true, 'last_login' => new MongoDB\BSON\UTCDateTime(), 'login_methods.standard' => false]],
      );

      $_SESSION['user_id'] = (string) $existingUser['_id'];
      header("Location: /client/index.php");
      exit();
    }
  }
}







