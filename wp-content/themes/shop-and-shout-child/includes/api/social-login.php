<?php
$redirect_uri = get_site_url() . '/my-account/edit-account/'; // URL of page/file that processes a request

//-----------------------//
//--- Instagram Login ---//
//-----------------------//
class InstagramApi
{
	public function GetAccessToken($client_id, $redirect_uri, $client_secret, $code) {
		$url = 'https://api.instagram.com/oauth/access_token';

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

	public function GetUserProfileInfo($access_token) {
		$url = 'https://api.instagram.com/v1/users/self/?access_token=' . $access_token;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$data = json_decode(curl_exec($ch), true);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if($data['meta']['code'] != 200 || $http_code != 200)
			throw new Exception('Error : Failed to get user information');

		return $data['data'];
	}
}

$insta_client_id='569c9a5389f041c7adef9c41ef499ed4';
$insta_client_secret='4983db61872c40bd8ab2191bed4afefd';

if( isset( $_GET['code'] ) && strlen( $_GET['code'] ) < 200) {

	try {
		$instagram_ob = new InstagramApi();

		// Get the access token
		$access_token = $instagram_ob->GetAccessToken( $insta_client_id, $redirect_uri, $insta_client_secret, $_GET['code'] );

		// Get user information
		$user_info = $instagram_ob->GetUserProfileInfo($access_token);

		function fetchData($url){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 20);
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
		}

		$result = fetchData("https://api.instagram.com/v1/users/".$user_info['id']."/media/recent/?access_token=".$access_token."&count=20");
		$result = json_decode($result);

		$total_insta_likes_count = 0;
		$total_insta_commt_count = 0;
		foreach ($result->data as $post) {
			$total_insta_likes_count+=$post->likes->count;
			$total_insta_commt_count+=$post->comments->count;
		}

		$totalLikesandComments=($total_insta_likes_count+$total_insta_commt_count)/20;
		$insta_followed_by = $user_info['counts']['followed_by'];
		$engagementrate = round((($totalLikesandComments/$insta_followed_by)*100),2);
		$insta_username = $user_info['username'];
		$instagram_full_name = $user_info['full_name'];
		$instagram_bio = $user_info['bio'];
		$instagram_following = $user_info['counts']['follows'];
		$instagram_url = 'https://www.instagram.com/' . $insta_username;

		$ig_id = $user_info['id'];

		// Authentique audit
		$authentique_audit_id = '';
		$authentique_audit_connected = '';

		include_once('authentique.php');
		$audit_result = create_influencer_audit( $insta_username );

		if( $audit_result['success'] ) {

			$authentique_audit_connected = 'true';
		}

		$user = get_users(array(
			'meta_key' => 'inf_instagram_id',
			'meta_value' => $ig_id,
			'number' => -1,
			'count_total' => false
		));

		if( !empty($user) ) { // If instagram id has been found
			// Log user into account associated with instagram id
			if ( is_user_logged_in() && get_current_user_id() !== $user[0]->ID ) {
				echo '<p style="color: red; text-align: center;">Another account is already connected with that Instagram account. Please log out and sign in with Instagram or contact influencers@shopandshout.com</p>';
			} else {
				$uid = $user[0]->ID;
				foreach ( $user as $user_data ) {
					if( $user_data->data->user_email != '' ) {
						$uid == $user_data->ID;
					}
				}
				wp_set_current_user( $uid );
				wp_set_auth_cookie( $uid );
				update_user_meta( $uid, 'inf_instagram_id', $ig_id );
				update_user_meta( $uid, 'social_prism_user_instagram', $insta_username );
				update_user_meta( $uid, 'social_prism_user_instagram_reach', $insta_followed_by );
				update_user_meta( $uid, 'instagram-full-name', $instagram_full_name );
				update_user_meta( $uid, 'instagram-bio', $instagram_bio );
				update_user_meta( $uid, 'instagram-following', $instagram_following );
				update_user_meta( $uid, 'instagram-url', $instagram_url );
				update_user_meta( $uid, 'instagram-engagement-rate', $engagementrate );
				update_user_meta( $uid, 'authentique_audit_connected', $authentique_audit_connected );

				wp_redirect( get_site_url() . '/my-account/edit-account/' );
			}
		} else { //If instagram id not found
			if ( is_user_logged_in() ) { // If user is logged in
				// Add instagram id and instagram info to account
				$uid = get_current_user_id();
				update_user_meta( $uid, 'inf_instagram_id', $ig_id );
				update_user_meta( $uid, 'social_prism_user_instagram', $insta_username );
				update_user_meta( $uid, 'social_prism_user_instagram_reach', $insta_followed_by );
				update_user_meta( $uid, 'instagram-full-name', $instagram_full_name );
				update_user_meta( $uid, 'instagram-bio', $instagram_bio );
				update_user_meta( $uid, 'instagram-following', $instagram_following );
				update_user_meta( $uid, 'instagram-url', $instagram_url );
				update_user_meta( $uid, 'instagram-engagement-rate', $engagementrate );

				update_user_meta( $uid, 'authentique_audit_connected', $authentique_audit_connected );

				wp_redirect( get_site_url() . '/my-account/edit-account/' );
				exit;
			} else { // If user not logged in
				echo '<p style="text-align: center; max-width: 600px; margin: 0 auto; color: red; font-size: 15px;">Sorry that Instagram account isn\'t associated with a user.<br>Please <a href="' . get_site_url() . '/influencer-signup/">signup then connect with Instagram</a> instead.</a><br><br>Have an existing account? Please log in and hit connect with Instagram in your profile.</p>';
			}
		}
	}
	catch(Exception $e) {
		echo $e->getMessage();
		exit;
	}
} else if ( isset( $_GET['error_reason'] ) ) { // something went wrong
	echo '<p style="color: red; text-align: center;">Something went wrong, please try again. (' . $_GET['error_reason'] . ': ' . $_GET['error_description'] . ')</p>';
}

//----------------------//
//--- Facebook Login ---//
//----------------------//
if( SAS_TEST_MODE ) {
	$client_id = '243901762927524'; // Facebook Test APP Client ID
	$client_secret = 'a96dad3294a43a1345bb471b186d969c'; // Facebook Test APP Client secret
} else {
	$client_id = '1816071558669484'; // Facebook APP Client ID
	$client_secret = '415335c62da2c7822fcf052470d6a985'; // Facebook APP Client secret
}

$params_fab = array(
	'client_id'     => $client_id,
	'redirect_uri'  => $redirect_uri,
	'response_type' => 'code',
	'scope'         => 'email, public_profile',
);

$facebook_login_url = 'https://www.facebook.com/dialog/oauth?' . urldecode( http_build_query( $params_fab ) );

if( isset( $_GET['code'] ) && strlen($_GET['code']) > 200 ) {
	$params = array(
		'client_id'     => $client_id,
		'redirect_uri'  => $redirect_uri,
		'client_secret' => $client_secret,
		'code'          => $_GET['code']
	);
	$tokenresponse = wp_remote_get( 'https://graph.facebook.com/v3.0/oauth/access_token?' . http_build_query( $params ) );
	$token = json_decode( wp_remote_retrieve_body( $tokenresponse ) );

	if ( isset( $token->access_token )) {
		// now using the access token we can receive informarion about user
		$params = array(
			'access_token'	=> $token->access_token,
			'fields'		=> 'id,email,first_name,last_name' // info to get
		);
		// connect Facebook Grapth API using WordPress HTTP API
		$useresponse = wp_remote_get('https://graph.facebook.com/v3.0/me' . '?' . urldecode( http_build_query( $params ) ) );
		$fb_user = json_decode( wp_remote_retrieve_body( $useresponse ) );

		$fb_id = $fb_user->id;
		$fb_fname = $fb_user->first_name;
		$fb_lname = $fb_user->last_name;
		$fb_email = '';

		if ( isset( $fb_user->email ) ) {
			$fb_email = $fb_user->email;
		}

		$user = get_users(array(
			'meta_key' => 'inf_facebook_id',
			'meta_value' => $fb_id,
			'number' => 1,
			'count_total' => false
		));

		if( !empty($user) ) { // If facebook id has been found
			if ( is_user_logged_in() && get_current_user_id() !== $user[0]->ID ) {
				echo '<p style="color: red; text-align: center;">Another account is already connected with that Facebook account. Please log out and sign in with Facebook or contact influencers@shopandshout.com</p>';
			} else {
				// Log user into account associated with facebook id
				wp_set_current_user( $user[0]->ID );
				wp_set_auth_cookie( $user[0]->ID );
				wp_redirect( get_site_url() . '/my-account/edit-account/' );
			}
		} else { //If facebook id not found
			echo '<p style="text-align: center; max-width: 600px; margin: 0 auto; color: red; font-size: 15px;">Sorry that Facebook account isn\'t associated with a user.<br>Please <a href="' . get_site_url() . '/influencer-signup/">signup with Facebook</a> instead.</a><br><br>Have an existing account? Please log in and hit connect with Facebook in your profile.</p>';
		}
	}
} else if ( isset( $_GET['error_code'] ) ) { // something went wrong
	echo '<p style="color: red; text-align: center;">Something went wrong, please try again. (' . $_GET['error_reason'] . ')</p>';
}
