<?php 
//----------------------------------//
//--- Instagram Business Account ---//
//----------------------------------//

$ig_permissions = [
	// 'public_profile',
	// 'email',
	// 'pages_show_list',
	'manage_pages',
	// 'publish_pages',
	// 'read_insights',
	'instagram_basic', // Next
	// 'instagram_content_publish',
	// 'instagram_manage_insights'
];

require_once  __DIR__ . '/../facebook/config.php';

$igLoginURL = $helper->getLoginUrl($redirect_url, $ig_permissions);

if (!empty($accessToken)) {

	// $ig_connect = !empty($_GET['connect'])?$_GET['connect']:'';

	$facebook_pages = $fb->get('/me/accounts/');
	$graphResponseFbPages = $facebook_pages->getGraphEdge();

	$fbpid = isset($graphResponseFbPages[0]['id']) ? $graphResponseFbPages[0]['id'] : '';

	$facebook_user_page = get_user_meta( $uid, 'facebook_user_page', true );

	if ($facebook_user_page) {
		$pid = $facebook_user_page;
	} else {
		$pid = $fbpid;
	}

	if($pid) {
		$ig_business_acount =  $fb->get( '/' . $pid . '?fields=instagram_business_account');
		$ig_account = $ig_business_acount->getDecodedBody();
		// print_r($pid);
		// echo '<br><br>';

		$igpid = $ig_account['instagram_business_account']['id'];

		$ig_account_info =  $fb->get( '/' . $igpid . '?fields=biography,followers_count,follows_count,media_count,name,profile_picture_url,username,website');
		$ig_account_object = $ig_account_info->getDecodedBody();

		echo 'Instagram Business info (via Facebook API)';
		echo ' <img src="' . $ig_account_object['profile_picture_url'] .'" style="height: 100px; width: 100px; border-radius: 50%; float: left; margin-right: 15px; margin-bottom: 15px;" alt="' . $ig_account_object['name'] . '" />';
		echo ' <h3>  ' . $ig_account_object['name'] . '</h3>';
		echo ' Website: ' . $ig_account_object['website'];
		echo ' <p><strong>Follower Count ' . $ig_account_object['followers_count'] . '</strong></p>';
		echo ' <p>Bio: ' . $ig_account_object['biography'] . '</p>';
	}
	
}