<?php
$GLOBALS['authentique_token'] = '63f5216a98f876e39ea244f1291173dcf76a5100f664bc8a48d4cd0e168d62f7983f5f543d378e47';
define( 'AUTHENTIQUE_AUDIT_KEY', 'a2d658b6d80ee686b83161e0c3670465d453c31ec606b26d11c79be1191c99af3ae52c44f7eba312' );

function authentique_score_request( $username ) {
	global $authentique_token;
	$url = 'https://agency-api.authentique.app/influencer?username=' . $username;
	$headers = array(
		'Content-Type: application/json',
		'Authorization: Key ' . $authentique_token,
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$result = json_decode( curl_exec($ch), true );

	curl_close($ch);

	return $result;
}

function create_influencer_audit( $username ) {

	$url = 'https://audit-api.authentique.app/audit/' . $username;
	$headers = array(
		'Content-Type: application/json',
		'Authorization: Key ' . AUTHENTIQUE_AUDIT_KEY,
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$result = json_decode(curl_exec($ch), true);

	curl_close($ch);

	return $result;
}

function get_audit_progress_or_result( $username ) {

	$url = 'https://audit-api.authentique.app/audit/username/' . $username;
	$headers = array(
		'Content-Type: application/json',
		'Authorization: Key ' . AUTHENTIQUE_AUDIT_KEY,
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$result = json_decode( curl_exec($ch), true );

	curl_close($ch);

	return $result;
}

?>