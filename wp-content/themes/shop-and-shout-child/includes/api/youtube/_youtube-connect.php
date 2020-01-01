<?php
  // require_once __DIR__ . '/vendor/autoload.php';

  $redirect_uri_connect = get_site_url() . '/my-account/edit-account/';

  $client = new Google_Client();
  $client->setAuthConfig('client_secret.json');
  $client->addScope(GOOGLE_SERVICE_YOUTUBE::YOUTUBE_FORCE_SSL);
  $client->setRedirectUri($redirect_uri_connect);
  $client->setAccessType('offline');        // offline access
  $client->setIncludeGrantedScopes(true);   // incremental auth
  $client->setApprovalPrompt('consent');

  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));

  // Check if Access Token exists
  $access_token = $client->getAccessToken();

  // Build YouTube object
  $youtube = new Google_Service_YouTube($client);
  $channel = $youtube->channels->listChannels('snippet', array('mine' => $mine));

  // $youtube_redirect_uri = get_site_url() . '/google-api/';

  $redirect_url = get_site_url() . '/my-account/edit-account/';

  $youtube_client_id = '196707225696-dree7unq0gf7500eno8ad9vle3edq2bf.apps.googleusercontent.com';
  // $youtube_login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode($redirect_url) . '&response_type=code&client_id=' . $youtube_client_id . '&access_type=online';
  $youtube_login_url = '';
  // $youtube_login_url = 'https://www.googleapis.com/youtube/v3/channels?part=statistics&id=channel_id&key=your_key';

  $yt_data = 'https://developers.google.com/apis-explorer/#p/youtube/v3/youtube.channels.list?
      part=snippet,contentDetails,brandingSettings
      &mine=true';

  $out =  '<a href="' . $youtube_login_url . '" class="social-connect-button" id="inf_google">';
  $out .= '<img src="' . get_stylesheet_directory_uri() . '/images/icons/youtube-coral.svg">';
  $out .= '<span>CONNECT WITH YOUTUBE</span>';
  $out .= '</a><br>';
  $out .= $yt_data;

  // echo $out;
?>
