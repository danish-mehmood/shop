<?php
$redirect_uri = get_site_url() . '/influencer-signup/'; // URL of page/file that processes a request

$redirect_uri_orders = get_site_url() . '/my-account/orders/';
$redirect_uri_signup = get_site_url() . '/influencer-signup/'; // URL of page/file that processes a request
$redirect_uri_connect = get_site_url() . '/my-account/edit-account/';

// Facebook
include_once("facebook/facebook-connect.php");
include_once("facebook/facebook-page-connect.php");
include_once("facebook/facebook-page-data.php");

// Instagram
include_once("instagram/instagram-connect.php");

// Twitter
include_once("twitter/twitter-connect.php");

// Youtube & Google
// include_once("youtube/youtube-connect.php");
// include_once("youtube/youtube-connect-js.php");
// include_once("youtube/_youtube-api.php");

// Pinterest
// include_once("pinterest/pinterest-connect.php");

// Snapchat
// include_once("snapchat/snapchat-connect.php");
