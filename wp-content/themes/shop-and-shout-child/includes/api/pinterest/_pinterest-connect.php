<?php

// Settings
$redirect_uri = get_site_url() . '/my-account/edit-account/';

/* Pinterest App Id */
define('PINTEREST_APPLICATION_ID', '5024881479826581224');

/* Pinterest App Secret */
define('PINTEREST_APPLICATION_SECRET', '10fc8e6476ed7d246ccde86f5d3b9c806dd812844525e586b6b9804a9a5c33fe');

/* Pinterest App Redirect Url */
define('PINTEREST_REDIRECT_URI', $redirect_uri);


// URL
$pinterest_login_url = 'https://api.pinterest.com/oauth/?client_id=' . PINTEREST_APPLICATION_ID . '&redirect_uri=' . urlencode(PINTEREST_REDIRECT_URI) . '&response_type=code&scope=read_public';


// Access Token

session_start();

// Pinterest passes a parameter 'code' in the Redirect Url
if(isset($_GET['code'])) {
	try {
		$pinterest_ob = new PinterestApi();

		// Get the access token
		$access_token = $pinterest_ob->GetAccessToken(PINTEREST_APPLICATION_ID, PINTEREST_REDIRECT_URI, PINTEREST_APPLICATION_SECRET, $_GET['code']);

		// Get user information
		$user_info = $pinterest_ob->GetUserProfileInfo($access_token);

		echo '<pre>';print_r($user_info); echo '</pre>';

		// Now that the user is logged in you may want to start some session variables
		$_SESSION['logged_in'] = 1;

		// You may now want to redirect the user to the home page of your website
		// header('Location: home.php');
	}
	catch(Exception $e) {
		echo $e->getMessage();
		exit;
	}
}

// Get Token

function GetAccessToken($client_id, $redirect_uri, $client_secret, $code) {
	$url = 'https://api.pinterest.com/v1/oauth/token';

	$curlPost = 'client_id='. $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code='. $code . '&grant_type=authorization_code';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
	$data = json_decode(curl_exec($ch), true);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	if($http_code != '200')
		throw new Exception('Error : Failed to receieve access token');

	return $data['access_token'];
}

// User Data

function GetUserProfileInfo($access_token) {
	$url = 'https://api.pinterest.com/v1/me/?access_token=' . $access_token . '&fields=id,username,first_name,last_name,image';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	$data = json_decode(curl_exec($ch), true);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	if($http_code != 200)
		throw new Exception('Error : Failed to get user information');

	return $data['data'];
}

?>
