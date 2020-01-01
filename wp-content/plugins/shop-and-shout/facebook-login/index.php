<?php

add_shortcode('ss_facebook_login', 'register_sas_facebook_login_button_shortcode');
function register_sas_facebook_login_button_shortcode() {
  // Configure Facebook SDK
  require_once 'config.php';

  // We are not really using this, doing updates through WordPress
  // Might be able to use this class to group some of the code
  // require_once 'User.class.php';

  // Construct URL Variables
  $permissions = ['public_profile','email','user_friends','pages_show_list','manage_pages','publish_pages']; // Optional permissions

  $redirect_path = '/my-account/edit-account/';
  $redirect_url = get_site_url() . $redirect_path ; // URL of page/file that processes a request

  $loginURL = $helper->getLoginUrl($redirect_url, $permissions);
  $logoutURL = $helper->getLogoutUrl($accessToken, $redirect_url . 'logout.php');

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
      $graphResponse = $fb->get('/me?fields=name,first_name,last_name,email,link,gender,picture,permissions');
      $fbUser = $graphResponse->getGraphUser();

      // Getting Facebook user friends
      $graphResponseFriends = $fb->get('/me/friends?fields=total_count');
      $fbFriends = $graphResponseFriends->getGraphEdge();
      $fbTotalFriends = $fbFriends->getTotalCount();

      // Getting Facebook user pages
      $graphResponsePages = $fb->get('/me/accounts?fields=name,picture.type(square),access_token,tasks');
      $fbPages = $graphResponsePages->getGraphEdge();

      // Display login buttons for user's Facebook pages
      if ( !empty($fbPages) ) {
          echo '<ul class="login-button-container">';
          foreach ($fbPages as $page) {
            $pic = $page['picture'];
            // $graphResponse = $fb->get('/me/accounts?fields=manage_pages,publish_pages');

            $page_button = '<li><div class="fb-page-title-container">';
            $page_button .= '<img src="' . $pic['url'] . '" alt="' . $page['name'] . '" />';
            $page_button .= '<h5>' . $page['name'] . '</h5>';
            $page_button .= '</div>';
            // $page_button .= '<a href="'. '#' . '"class="social-connect-button" id="inf_facebook">';
            // $page_button .= '<img src="' . plugin_dir_url( __FILE__ ) . '/images/Facebook-icon-white.svg"><span>CONNECT FACEBOOK PAGE<span>';
            // $page_button .= '</a>';
            $page_button .= '</li>';

            echo $page_button;
          }
        echo '</ul>';
      }

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

    // Initialize User class
    // $user = new User();

    // Getting user's profile data
    $fbUserData = array();
    $fbUserDataOauthUid   = !empty($fbUser['id'])?$fbUser['id']:'';
    $fbUserDataFirstName  = !empty($fbUser['first_name'])?$fbUser['first_name']:'';
    $fbUserDataLastName   = !empty($fbUser['last_name'])?$fbUser['last_name']:'';
    $fbUserDataEmail      = !empty($fbUser['email'])?$fbUser['email']:'';
    $fbUserDataGender     = !empty($fbUser['gender'])?$fbUser['gender']:'';
    $fbUserDataPicture    = !empty($fbUser['picture']['url'])?$fbUser['picture']['url']:'';
    $fbUserDataLink       = !empty($fbUser['link'])?$fbUser['link']:'';
    $fbUserFriendCount    = !empty($fbFriends['total_count'])?$fbFriends['total_count']:'';
    $fb_email = '';

    $fbUserData['oauth_uid'] = !empty($fbUser['id'])?$fbUser['id']:''; // Check for reach instead?
    // Insert or update user data to the database
    $fbUserDataOauthProvider = 'facebook';
    $userData = $fbUserData;

    if( !empty($fbUserData) ) { // If instagram id has been found
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
  			if ( is_user_logged_in() && get_current_user_id() !== $user[0]->ID ) {
  				echo '<p style="color: red; text-align: center;">Another account is already connected with that Facebook account. Please log out and sign in with Facebook or contact influencers@shopandshout.com</p>';
  			} else {

  				// Log user into account associated with facebook id
  				wp_set_current_user( $user[0]->ID );
  				wp_set_auth_cookie( $user[0]->ID );
  				// wp_redirect('/my-account/edit-account/');

  			}
  		} else { //If facebook id not found
  			// $button = '<p style="text-align: center; max-width: 600px; margin: 0 auto; color: red; font-size: 15px;">Sorry that Facebook account isn\'t associated with a user.<br>Please <a href="/influencer-signup/">signup with Facebook</a> instead.</a><br><br>Have an existing account? Please log in and hit connect with Facebook in your profile.</p>';
  		}
  	}
  } else if ( isset( $_GET['error_code'] ) ) { // something went wrong
  	$button = '<p style="color: red; text-align: center;">Something went wrong, please try again.</p>';

  } else {
    //  Show The Button
    $button = '<a href="'.htmlspecialchars($loginURL).'" class="social-connect-button" id="inf_facebook">
        <img src="' . plugin_dir_url( __FILE__ ) . '/images/Facebook-icon-white.svg"><span>CONNECT TO FACEBOOK</span></a>';
  }

  return $button;
}

?>
