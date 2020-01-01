<?php

$google_redirect_uri = get_site_url() . '/google-api/';
$google_client_id = '196707225696-dree7unq0gf7500eno8ad9vle3edq2bf.apps.googleusercontent.com';
$google_login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode($google_redirect_uri) . '&response_type=code&client_id=' . $google_client_id . '&access_type=online';



?>
