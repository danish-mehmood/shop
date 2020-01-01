<?php

require_once  __DIR__ . '/config.php';

if (isset($accessToken)) {
  if (isset($_SESSION['facebook_access_token'])) {
      $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
  } else {
    // Put short-lived access token in session
    $_SESSION['facebook_access_token'] = (string) $accessToken;

    // OAuth 2.0 client handler helps to manage access tokens
    $oAuth2Client = $fb->getOAuth2Client();

    // Exchanges a short-lived access token for a long-lived one
    $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
    $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

    // Set default access token to be used in script
    $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
  }

  // Redirect the user back to the same page if url has "code" parameter in query string
  // if(isset($_GET['code'])){
  //     header('Location: ./');
  // }

  try {

    // Getting Facebook user pages
    $graphResponsePages = $fb->get('/me/accounts?fields=name,access_token,tasks');
    $fbPages = $graphResponsePages->getGraphEdge();

  } catch(FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    session_destroy();
    // Redirect user back to app login page
    header("Location: ./");
    exit;
  } catch(FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }
}

function facebook_share_url( $product_id, $quote, $page_id) {
  $page_url = get_permalink( $product_id );
  $q = $quote;

  if ( isset( $_POST['shoutout'] )) {
    $redirect = get_site_url() . '/my-account/view-order/' . $_POST['shoutout'] . '/?message=success';
  } else {
    $redirect = get_site_url() . '/my-account/orders/';
  }

  if ( isset( $_POST['facebook_text']) ) {
    $q = $_POST['facebook_text'];
  } else {
    $q = $quote;
  }

  $params = array(
    'app_id' => '243901762927524',
    'from' => $page_id,
    'display' => 'popup',
    'link' => $page_url,
    'redirect_uri' => $redirect,
    'to' => $page_id
  );

  $url = 'http://www.facebook.com/dialog/feed?' . http_build_query( $params );

  return $url;
}

function get_facebook_meta_url($order_id) {
  $metas = get_post_meta( $order_id, 'facebook_url', true );
  print_r($metas);
  if (is_array($metas) || is_object($metas)) {
    foreach( $metas as $meta ) {
      return $meta['facebook_url'];
    }
  }
}
?>
