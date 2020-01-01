<?php
/**
 * Bit.ly API
 *
 * Connects to Bit.ly API
 */

/**
 * Returns an associative array containing bitly access token data
 */
function request_bitly_access_token() {

	$generic_token = '9067c44fdad46dbbbf6bfbc11f268796d5e71cbd';

	$username = 'brands@shopandshout.com';
	$password = 'ShopShout!4';
	
	$url = 'https://api-ssl.bitly.com/oauth/access_token';

	$headers = array(
		'Host: api-ssl.bitly.com',
		'Content-Type: application/x-www-form-urlencoded',
		'Authorization: Basic ' . base64_encode($username . ':' . $password ),
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec($ch);
	$result = json_decode($response, true);

	curl_close($ch);

	return $result;
}

/**
 * Creates a bitlink from a long url
 */
function create_bitlink( $referral_link ) {

	$token = request_bitly_access_token();

	$generic = '9067c44fdad46dbbbf6bfbc11f268796d5e71cbd';
	
	$url = 'https://api-ssl.bitly.com/v4/bitlinks';

	$headers = array(
		'Host: api-ssl.bitly.com',
		'Content-Type: application/json',
		'Authorization: Bearer ' . $generic,
	);

	$params = array(
		'long_url' 	=> $referral_link,
	);
	$json_string = json_encode($params);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json_string);

	$response = curl_exec($ch);
	$result = json_decode($response, true);

	curl_close($ch);

	return $response;
}
?>