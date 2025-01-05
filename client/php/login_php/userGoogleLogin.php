<?php

// connect to mongoDB
require '../../../vendor/autoload.php';

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
$client->addScope("email");
$client->addScope("profile");

$authUrl = $client->createAuthUrl();
header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
exit();
