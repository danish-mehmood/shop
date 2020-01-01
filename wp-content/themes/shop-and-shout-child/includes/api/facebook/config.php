<?php

// Facebook API Credentials
if ( SAS_TEST_MODE ) {
  $facebook_app_id = '243901762927524';
  $facebook_app_secret = 'a96dad3294a43a1345bb471b186d969c';
} else {
  $facebook_app_id = '1816071558669484';
  $facebook_app_secret = '415335c62da2c7822fcf052470d6a985';
}

// Include the autoloader provided in the SDK
require_once __DIR__ . '/facebook-php-graph-sdk/autoload.php';

// Include required libraries
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

// Call Facebook API
if(!session_id()) {
  session_start();
}

$fb = new Facebook(array(
    'app_id' => $facebook_app_id,
    'app_secret' => $facebook_app_secret,
    'default_graph_version' => 'v3.2',
));

// Get redirect login helper
$helper = $fb->getRedirectLoginHelper();

// Try to get access token
try {
    if(isset($_SESSION['facebook_access_token'])){
        $accessToken = $_SESSION['facebook_access_token'];
    }else{
        $accessToken = $helper->getAccessToken();
    }
} catch(FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

?>
