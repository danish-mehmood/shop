<?php

	//-----------------------------//
	//--- Facebook Connect ---//
	//-----------------------------//
	require_once  __DIR__ . '/config.php';

	// Construct URL Variables
	$redirect_url = get_site_url() . '/my-account/edit-account/';
	$permissions = array(
		'public_profile',
		'email',
		'user_gender',
		// 'age_range',
		'user_birthday',
		'user_location',
		'user_hometown',
		'user_friends',
		'pages_show_list',
		// 'user_posts',
		'manage_pages',
		// 'publish_pages',
		// 'read_insights',
		'instagram_basic', // Next
		// 'instagram_content_publish',
		// 'instagram_manage_insights'
	);


	$loginURL = $helper->getLoginUrl($redirect_url, $permissions);

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
		if(isset($_GET['code'])){
			header('Location: ./');
		}

		try {
	    // Getting Facebook user profile
	    $graphResponse = $fb->get('/me?fields=name,first_name,last_name,email,hometown,gender,languages,sports,birthday,location,likes,favorite_teams,permissions');
	    $fbUser = $graphResponse->getGraphUser();

		// Getting Facebook pages
		$graphResponsePages = $fb->get('/me/accounts?fields=name,id,general_info,picture,fan_count,food_styles,hours,price_range,payment_options,overall_star_rating,location,link,products,access_token,tasks');
		$fbPages = $graphResponsePages->getGraphEdge();

	    // Getting Facebook user friends
	    $graphResponseFriends = $fb->get('/me/friends?fields=total_count');
	    $fbFriends = $graphResponseFriends->getGraphEdge();
	    $fbTotalFriends = $fbFriends->getTotalCount();

    } catch(FacebookResponseException $e) {
      echo 'Graph returned an error: ' . $e->getMessage();
      session_destroy();
      // Redirect user back to app login page
      header("Location: ./");
    } catch(FacebookSDKException $e) {
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
    }

		// Getting user's profile data
		$fbUserData = array();
		$fbUserDataOauthUid   = !empty($fbUser['id'])?$fbUser['id']:'';
		$fbUserDataFirstName  = !empty($fbUser['first_name'])?$fbUser['first_name']:'';
		$fbUserDataLastName   = !empty($fbUser['last_name'])?$fbUser['last_name']:'';
		$fbUserDataEmail      = !empty($fbUser['email'])?$fbUser['email']:'';
		$fbUserDataGender     = !empty($fbUser['gender'])?$fbUser['gender']:'';
		// $fbUserAgeRange				= !empty($fbUser['age_range'])?$fbUser['age_range']:'';
		// $fbUserSocialContext	= !empty($fbUser['context'])?$fbUser['context']:'';
		$fbUserLocation				= !empty($fbUser['location'])?$fbUser['location']['name']:'';
		$fbUserHometown				= !empty($fbUser['hometown'])?$fbUser['hometown']['name']:'';
		$fbUserDataPicture    = !empty($fbUser['picture']['url'])?$fbUser['picture']['url']:'';
		$fbUserDataLink       = !empty($fbUser['link'])?$fbUser['link']:'';

		$fbUserData['oauth_uid'] = !empty($fbUser['id'])?$fbUser['id']:''; // Check for reach instead?

		$fbCurrentPage = !empty($fbUser['link'])?$fbUser['link']:'';

		// Date of Birth
		$dobObj = $fbUser->getProperty('birthday');
		get_object_vars($dobObj);

		$dobDateTime = strtotime($dobObj->date);
		$fbDobDate = date('Y-m-d', $dobDateTime);

		$fbUserBirthdate			= !empty($fbDobDate)?$fbDobDate:'';

		// Insert or update user data to the database
		$fbUserDataOauthProvider = 'facebook';
		$userData = $fbUserData;

		if( !empty($fbUserData) ) { // If facebook id has been found
			// Check if the Facebook id returned by the API matches an existing user in the db
			$fb_id = $fbUserDataOauthUid;
			$fb_fname = $fbUserDataFirstName;
			$fb_lname = $fbUserDataLastName;
			$fb_email = $fbUserDataEmail;

			if ( isset( $fb_user->email ) ) {
				$fb_email = $fb_user->email;
			}

			// Query the usermeta table for a Facebook ID
			$user = get_users(array(
				'meta_key' => 'inf_facebook_id',
				'meta_value' => $fb_id,
				'number' => -1,
				'count_total' => false
			));

			if( !empty($user) ) { // If facebook id has been found
				if ( is_user_logged_in() && get_current_user_id() == $user[0]->ID ) {
					$uid = get_current_user_id();
						// update_user_meta( $uid, 'inf_facebook_id', $fb_id );
						update_user_meta( $uid, 'social_prism_user_facebook', $fb_fname );
						update_user_meta( $uid, 'social_prism_user_facebook_reach', $fbTotalFriends );
						// update_user_meta( $uid, 'facebook_age_range', $fbUserAgeRange );
						update_user_meta( $uid, 'facebook_gender', $fbUserDataGender );
						// update_user_meta( $uid, 'facebook_context', $fbUserSocialContext );
						update_user_meta( $uid, 'facebook_hometown', $fbUserHometown );
						update_user_meta( $uid, 'facebook_location', $fbUserLocation );
						// update_user_meta( $uid, 'facebook-bio', $facebook_bio );
						update_user_meta( $uid, 'facebook_url', $fbUserDataLink );
						update_user_meta( $uid, 'facebook_birthdate', $fbUserBirthdate );

						// wp_redirect('/my-account/edit-account/');
						// exit;

				} else {

					// Log user into account associated with facebook id
					wp_set_current_user( $user[0]->ID );
					wp_set_auth_cookie( $user[0]->ID );
					// wp_redirect('/my-account/edit-account/');

					// echo 'Logging in as user: ' . $user[0]->ID;

				}
			} else { // If facebook id not found
				// echo '<p style="text-align: center; max-width: 600px; margin: 0 auto; color: red; font-size: 15px;">Sorry that Facebook account isn\'t associated with a user!<br>Please <a href="/influencer-signup/">signup with Facebook</a> instead.</a><br><br>Have an existing account? Please log in and hit connect with Facebook in your profile.</p>';

				$uid = get_current_user_id();

				update_user_meta( $uid, 'inf_facebook_id', $fb_id );
				update_user_meta( $uid, 'social_prism_user_facebook_reach', $fbTotalFriends );

			}
		}
	} else if ( isset( $_GET['error_code'] ) ) { // something went wrong
		echo '<p style="color: red; text-align: center;">Something went wrong, please try again.</p>';
	}

	// echo 'User data: ' . print_r($fbUser);

?>
