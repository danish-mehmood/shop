<?php
$redirect_uri = get_site_url() . '/influencer-signup/'; // URL of page/file that processes a request
//-----------------------//
//--- Facebook Signup ---//
//-----------------------//

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

$facebook_signup_url = 'https://www.facebook.com/dialog/oauth?' . urldecode( http_build_query( $params_fab ) );

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

		if(!isset($fb_user->email)) {
			echo 'Sorry, looks like there isn\'t an email associated with that account. Please set your email and try again.'; 
		} elseif(email_exists($fb_user->email)) {

			$uid = email_exists($fb_user->email);
			update_user_meta($uid, 'inf_facebook_id', $fb_user->id);
			wp_set_current_user( $uid );
			wp_set_auth_cookie( $uid );
			wp_redirect( get_site_url() . '/my-account/edit-account/' );

		} else {

			$fb_id = $fb_user->id;
			$fb_fname = $fb_user->first_name;
			$fb_lname = $fb_user->last_name;
			$fb_email = $fb_user->email;

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
			} else {
				if ( is_user_logged_in() ) { // If user is logged in
					// Add facebook id to account
					update_user_meta( get_current_user_id(), 'inf_facebook_id', $fb_id );
					wp_redirect( get_site_url() . '/my-account/edit-account/' );
				} else{ // If user not logged in

					// Create new unregistered user
					$user_args = array(
						'user_login' => $fb_email,
						'user_email' => $fb_email,
						'first_name' => ucfirst(strtolower($fb_fname)),
						'last_name' => ucfirst(strtolower($fb_lname)),
						'user_pass' => wp_generate_password( 8, false ),
					);

					$uid = wp_insert_user($user_args);

					if( !is_wp_error($uid) ) {

						update_user_meta( $uid, 'inf_facebook_id', $fb_id );
						update_user_meta( $uid, 'persona', 'persona_2' );
						update_user_meta( $uid, 'social_prism_user_type', 'influencer' );
						update_user_meta( $uid, 'wp_user', 'true' );

						wp_set_current_user( $uid );
						wp_set_auth_cookie( $uid );

						// checking user cookies
						ob_start();
						?>
						<script type="text/javascript">
							jQuery(document).ready(function($) {
								infSignupCheckCookies(<?php echo $uid; ?>);
							});
						</script>
						<?php
						echo ob_get_clean();

						exit;

					} else {

						echo $uid->get_error_messag();
					}
				}
			}
		}
	}
} else if ( isset( $_GET['error_code'] ) ) { // something went wrong
	echo '<p style="color: red; text-align: center;">Something went wrong, please try again. (' . $_GET['error_reason'] . ')</p>';
}
?>