<?php

/**
* Sample PHP code for youtube.channels.list
* See instructions for running these code samples locally:
* https://developers.google.com/explorer-help/guides/code_samples#php
*/

define('STDIN',fopen("php://stdin","r"));
$client_json_path = __DIR__ . '/client_secret.json';
// $redirect_uri = get_site_url() . '/my-account/edit-account/';
$redirect_uri = 'https://shopshoutdev.wpengine.com/my-account/edit-account/';
// 
// if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
// throw new Exception(sprintf('Please run "composer require google/apiclient:~2.0" in "%s"', __DIR__));
// }
// require_once __DIR__ . '/vendor/autoload.php';

// session_start();

$client = new Google_Client();
$client->setApplicationName('Shop Shout Test');
$client->setClientId('47280234566-k75ett60hg2db2jpp5vh51ku3o1knkod.apps.googleusercontent.com');
$client->setClientSecret($client_json_path);
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
$client->setScopes([
  'https://www.googleapis.com/auth/youtube.readonly',
]);

// echo 'Data: ' . print_r($client);

// Request authorization from the user.
$youtube_login_url = $client->createAuthUrl();
// printf("Open this link in your browser:\n%s\n", $authUrl);

return $client;

// TODO: For this request to work, you must replace
//       "YOUR_CLIENT_SECRET_FILE.json" with a pointer to your
//       client_secret.json file. For more information, see
//       https://cloud.google.com/iam/docs/creating-managing-service-account-keys


// $client->setAuthConfig($client_json_path);
// $client->setAccessType('offline');


// print('Enter verification code: ');
// $authCode = trim(fgets(STDIN));
//
// // Exchange authorization code for an access token.
// $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
// $client->setAccessToken($accessToken);
//
// // Define service object for making API requests.
// $service = new Google_Service_YouTube($client);
//
// $queryParams = [
//   'mine' => true
// ];
//
// $response = $service->channels->listChannels('snippet,contentDetails,statistics', $queryParams);
// print_r($response);


?>
