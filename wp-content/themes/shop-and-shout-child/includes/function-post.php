<?php
/**
 * This file is used for all form post data processing
 */

// ------------------ //
// ------ SHOP ------ //
// ------------------ //
/**
* Filter Shop (this is a fallback for when javascript fails or is disabled)
*/
add_action( 'admin_post_shop_filter_form', 'shop_filter_form' );
add_action( 'admin_post_nopriv_shop_filter_form', 'shop_filter_form' );

function shop_filter_form() {

  // Verify nonce
  if( !isset( $_POST['shop_filter_form_nonce'] ) || !wp_verify_nonce( $_POST['shop_filter_form_nonce'], 'shop_filter_form' ) ) {
    wp_redirect(get_site_url() . '/shop/');
  }

  // Post values
  //TODO: selected shoutout channel
  $selected_channel = 'instagram';
  $current_page = isset($_POST['current_page'])?$_POST['current_page']:1;
  $current_params_str = isset($_POST['current_params'])?$_POST['current_params']:'';
  parse_str($current_params_str, $current_params);
  
  $params_array = array();

  // Filters
  if(isset($_POST['shop-filter-reach']))
    $params_array[$selected_channel.'_reach'] = $_POST['shop-filter-reach'];
  if(isset($_POST['shop-filter-engagement']))
    $params_array[$selected_channel.'_engagement_rate'] = $_POST['shop-filter-engagement'];
  if(isset($_POST['interests']))
    $params_array['interests'] = implode(',',$_POST['interests']);
  if(isset($_POST['campaign-types']))
    $params_array['campaign_strategies'] = implode(',',$_POST['campaign-types']);
  if(isset($_POST['countries']))
    $params_array['country'] = implode(',',$_POST['countries']);

  // Sorting
  if(isset($current_params['sortby']))
    $params_array['sortby'] = $current_params['sortby'];
  if(isset($current_params['order']))
    $params_array['order'] = $current_params['order'];

  $redirect = get_site_url() . '/shop/' . ($page>1?$page.'/':'') . (count($params_array)?'?'.http_build_query($params_array):'');

  wp_redirect($redirect);
  exit;
}
/**
* Sort Shop Select (this is a fallback for when javascript fails or is disabled)
*/
add_action( 'admin_post_shop_sort_form', 'shop_sort_form' );
add_action( 'admin_post_nopriv_shop_sort_form', 'shop_sort_form' );

function shop_sort_form() {

  // Verify nonce
  if( !isset( $_POST['shop_sort_form_nonce'] ) || !wp_verify_nonce( $_POST['shop_sort_form_nonce'], 'shop_sort_form' ) ) {
    wp_redirect(get_site_url() . '/shop/');
  }

  // Post values
  //TODO: selected shoutout channel
  $selected_channel = 'instagram';
  $current_page = isset($_POST['current_page'])?$_POST['current_page']:1;
  $current_params_str = isset($_POST['current_params'])?$_POST['current_params']:'';
  parse_str($current_params_str, $current_params);
  
  $params_array = array();

  // Filters
  if(isset($current_params[$selected_channel.'_reach']))
    $params_array[$selected_channel.'_reach'] = $current_params[$selected_channel.'_reach'];
  if(isset($current_params[$selected_channel.'_engagement_rate']))
    $params_array[$selected_channel.'_engagement_rate'] = $current_params[$selected_channel.'_engagement_rate'];
  if(isset($current_params['interests']))
    $params_array['interests'] = $current_params['interests'];
  if(isset($current_params['campaign_strategies']))
    $params_array['campaign_strategies'] = $current_params['campaign_strategies'];
  if(isset($current_params['country']))
    $params_array['country'] = $current_params['country'];

  // Sorting
  $sorting = '';
  if(isset($_POST['shop-sort'])) {
    if(is_array($_POST['shop-sort'])) {
      $sorting = $_POST['shop-sort'][0];
    } else {
      $sorting = $_POST['shop-sort'];
    }
  }

  switch($sorting) {
    case 'reach_asc':
      $params_array['sortby'] = $selected_channel.'_reach';
      $params_array['order'] = 'asc';
      break;
    case 'reach_desc':
      $params_array['sortby'] = $selected_channel.'_reach';
      $params_array['order'] = 'desc';
      break;
    case 'engagement_asc':
      $params_array['sortby'] = $selected_channel.'_engagement_rate';
      $params_array['order'] = 'asc';
      break;
    case 'engagement_desc':
      $params_array['sortby'] = $selected_channel.'_engagement_rate';
      $params_array['order'] = 'desc';
      break;
  }  

  $redirect = get_site_url() . '/shop/' . ($page>1?$page.'/':'') . (count($params_array)?'?'.http_build_query($params_array):'');

  wp_redirect($redirect);
  exit;
}


// --------------------- //
// ---- INFLUENCERS ---- //
// --------------------- //

 /**
  * Registers a new influencer through the influencer-signup form (this is a fallback in case javascript isn't functioning properly or is disabled)
  */
add_action( 'admin_post_register_influencer', 'register_influencer' );
add_action( 'admin_post_nopriv_register_influencer', 'register_influencer' );

function register_influencer() {

  // Verify nonce
  if( !isset( $_POST['register_influencer_nonce'] ) || !wp_verify_nonce( $_POST['register_influencer_nonce'], 'register_influencer' ) ) {
    wp_redirect(get_site_url() . '/influencer-signup/?errors=nonce_failed');
    exit;
  }

  // Post values
  $first_name = $_POST['inf_first_name'];
  $last_name = $_POST['inf_last_name'];
  $email = $_POST['inf_email'];
  $password = $_POST['inf_password'];
  $referral = $_POST['referral'];

  // check that the email address does not belong to a registered user
  if ( username_exists($email) != '' || email_exists($email) ) {
    wp_redirect(get_site_url() . '/influencer-signup/?errors=user_exists');
    exit;
  }

  $userdata = array(
    'user_email'    => $email,
    'user_login'    => $email,
    'first_name'    => ucfirst(strtolower($first_name)),
    'last_name'     => ucfirst(strtolower($last_name)),
    'user_pass'     => $password,
  );
  $user_id = wp_insert_user( $userdata );

  if( !is_wp_error( $user_id ) ) {

    update_user_meta( $user_id, 'referralcode', $referral );
    update_user_meta( $user_id, 'persona', 'persona_2' );
    update_user_meta( $user_id, 'social_prism_user_type', 'influencer' );
    update_user_meta( $user_id, 'wp_user', 'true' );

    // wp_set_auth_cookie( $user_id );
    wp_signon(array(
      'user_login' => $email,
      'user_password' => $password,
      'remember' => true,
    ), true);
    wp_set_current_user( $user_id );
    wp_redirect(get_site_url() . '/welcome');
    exit();
  } else {
    wp_redirect(get_site_url() . '/influencer-signup/?user_error=1');
    exit();
  }
}


/**
 * Registers a new influencer throught the influencer-signup form
 */
add_action( 'wp_ajax_register_influencer', 'register_new_influencer' );
add_action( 'wp_ajax_nopriv_register_influencer', 'register_new_influencer' );

function register_new_influencer() {

  $response = array(
    'error' => array(),
  );

    // Verify nonce
    if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'register_influencer' ) ) {
      $response['error'] = 'Ooops, something went wrong, please try again later.';
      exit(json_encode($response));
    }

  // Post values
  $first_name      = $_POST['name'];
  $last_name       = $_POST['last_name'];
  $email           = $_POST['mail'];
  $password        = $_POST['password'];
  $referral		 = $_POST['referral'];
  $referred_by 	 = $_POST['referred_by'];

	// check that the email address does not belong to a registered user
	if ( username_exists($email) != '' || email_exists($email) ) {
    $response['error'] = 'Sorry, that email is already in use.';
		exit(json_encode($response));
	}

  $userdata = array(
      'user_email'    => $email,
      'user_login'    => $email,
      'first_name'    => ucfirst(strtolower($first_name)),
      'last_name'     => ucfirst(strtolower($last_name)),
      'user_pass'     => $password,
  );
  $user_id = wp_insert_user( $userdata );

  if( !is_wp_error( $user_id ) ) {

  	update_user_meta( $user_id, 'referralcode', $referral );
  	update_user_meta( $user_id, 'referred_by', $referred_by );
  	update_user_meta( $user_id, 'persona', 'persona_2' );
  	update_user_meta( $user_id, 'social_prism_user_type', 'influencer' );
  	update_user_meta( $user_id, 'wp_user', 'true' );

    $user_creds = array(
      'user_login' => $email,
      'user_password' => $password,
      'remember' => true,
    );
    wp_signon($user_creds);

    wp_set_current_user( $user_id );
    wp_set_auth_cookie( $user_id );

    $response['url'] = get_site_url() . '/welcome';

    exit(json_encode($response));

  } else {
    $response['error'] = $user_id->get_error_message();
    exit(json_encode($response));
  }

  exit;
}

/**
 * register influencer throught google login
 */
add_action( 'wp_ajax_google_sign_in', 'google_sign_in' );
add_action( 'wp_ajax_nopriv_google_sign_in', 'google_sign_in' );
function google_sign_in() {

	// get post data
	$google_id = $_POST['google_id'];

	$user = get_users(array(
		'meta_key' => 'inf_google_id',
		'meta_value' => $google_id,
		'number' => 1,
		'count_total' => false
	));

	if( !empty($user) ) {

		wp_set_current_user( $user[0]->ID );
		wp_set_auth_cookie( $user[0]->ID );
		exit( '<script>window.location="' . get_site_url() . '/my-account/edit-account/"</script>' );

	} else {

		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$email = $_POST['email'];

		if ( is_user_logged_in() ) { // If user is logged in

			$uid = get_current_user_id();

			update_user_meta( $uid, 'inf_google_id', $google_id );

			exit( '<script>window.location="' . get_site_url() . '/my-account/edit-account/"</script>' );
		} elseif(email_exists($email)) {
      $uid = email_exists($email);
      update_user_meta($uid, 'inf_google_id', $google_id);

      wp_set_current_user( $uid );
      wp_set_auth_cookie( $uid );

      ob_start();
      ?>
      <script type="text/javascript">
          jQuery(document).ready(function($) {
              window.location="<?php echo get_site_url() . '/my-account/edit-account' ?>"
          });
      </script>
      <?php
      exit(ob_get_clean());

    } else {

      // Create new user
      $user_args = array(
          'user_email' => $email,
          'user_login' => $email,
          'first_name' => ucfirst(strtolower($first_name)),
          'last_name' => ucfirst(strtolower($last_name)),
          'user_pass' => wp_generate_password( 8, false ),
      );

      $uid = wp_insert_user( $user_args );

      if( !is_wp_error($uid) ) {

        update_user_meta( $uid, 'inf_google_id', $google_id );
        update_user_meta( $uid, 'persona', 'persona_2' ); // for hubspot
        update_user_meta( $uid, 'social_prism_user_type', 'influencer' ); // for hubspot
        update_user_meta( $uid, 'wp_user', 'true' ); // for hubspot

        wp_set_current_user( $uid );
        wp_set_auth_cookie( $uid );

        ob_start();
        ?>
        <script type="text/javascript">
          jQuery(document).ready(function($) {
            window.location="<?php echo get_site_url() . '/welcome/' ?>"
          });
        </script>
        <?php
        exit(ob_get_clean());

      } else {
        exit( $uid->get_error_message() );
      }
		}
	}

	return;
}

// Update influencer account audience demographics
add_action( 'wp_ajax_inf_account_audience_demographics', 'inf_account_audience_demographics' );
function inf_account_audience_demographics() {
    $uid = get_current_user_id();

    // Post data
	$top_country_1 = $_POST['top_country_1'];
	$top_country_2 = $_POST['top_country_2'];
	$top_country_3 = $_POST['top_country_3'];
	$top_country_percentage_1 = $_POST['top_country_percentage_1'];
	$top_country_percentage_2 = $_POST['top_country_percentage_2'];
	$top_country_percentage_3 = $_POST['top_country_percentage_3'];
	$female_percentage = $_POST['female_percentage'];
	$male_percentage = $_POST['male_percentage'];
	$non_binary_percentage = $_POST['non_binary_percentage'];

	update_user_meta( $uid, 'inf_top_country_1', $top_country_1 );
	update_user_meta( $uid, 'inf_top_country_2', $top_country_2 );
	update_user_meta( $uid, 'inf_top_country_3', $top_country_3 );
	update_user_meta( $uid, 'inf_top_country_percentage_1', $top_country_percentage_1 );
	update_user_meta( $uid, 'inf_top_country_percentage_2', $top_country_percentage_2 );
	update_user_meta( $uid, 'inf_top_country_percentage_3', $top_country_percentage_3 );
	update_user_meta( $uid, 'inf_female_percentage', $female_percentage );
	update_user_meta( $uid, 'inf_male_percentage', $male_percentage );
	update_user_meta( $uid, 'inf_non_binary_percentage', $non_binary_percentage );

    exit;
}


add_action( 'admin_post_inf_account_personal_info', 'inf_account_personal_info' );
function inf_account_personal_info() {

    $uid = get_current_user_id();
    $current_email = get_userdata($uid)->user_email;
    $message = '';

    // Post data
    $first_name = sanitize_text_field(ucfirst(strtolower($_POST['inf_first_name'])));
    $last_name = sanitize_text_field(ucfirst(strtolower($_POST['inf_last_name'])));
    $email = sanitize_email($_POST['inf_email']);
    $phone = sanitize_text_field($_POST['inf_phone_number']);
    $country = sanitize_text_field($_POST['inf_country']);
    $region = sanitize_text_field($_POST['inf_region']);
    $gender = sanitize_text_field($_POST['inf_gender']);
    $birthdate_month = sanitize_text_field($_POST['inf_birthdate_month']);
    $birthdate_day = sanitize_text_field($_POST['inf_birthdate_day']);
    $birthdate_year = sanitize_text_field($_POST['inf_birthdate_year']);

    if( $first_name != '' && $last_name != '' ) {
    	update_user_meta( $uid, 'first_name', $first_name );
    	update_user_meta( $uid, 'last_name', $last_name );
    	wp_update_user( array( 'ID' => $uid, 'display_name' => $first_name . ' ' . $last_name ) );
    }

    if( email_exists($email) && $email != $current_email ) {

    	wp_redirect(get_site_url() . '/my-account/personal/?errors=email_exists');
      exit;

    } else {

    	wp_update_user( array( 'ID' => $uid, 'user_email' => $email ) );
    }

    if( $phone != '' ) {

    	update_user_meta( $uid, 'inf_phone', $phone );
    }

    if( $country != '' ) {

    	update_user_meta( $uid, 'inf_country', $country );
    }

    if( $region != '' ) {

    	update_user_meta( $uid, 'inf_region', $region );
    }

    if( $gender != '' ) {

    	update_user_meta( $uid, 'inf_gender', $gender );
    }

    if( $birthdate_month != '' && $birthdate_year != '' && $birthdate_day != '' ) {

    	$birthdate_string = $birthdate_month . '/' . $birthdate_day . '/' . $birthdate_year;
    	$age = calculate_user_age($birthdate_string);

    	update_user_meta( $uid, 'social_prism_user_birthday', $birthdate_string );
    	update_user_meta( $uid, 'inf_age', $age );
    }

    wp_redirect(get_site_url() . '/my-account/personal/');

    exit;
}

/**
 * Sends test email
 */
add_action( 'wp_ajax_admin_test_email', 'admin_test_email' );

function admin_test_email() {

  $response = array('errors'=>'');

  // Verify nonce
  if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'email_check' ) ) {

    $response['errors'] .= 'Ooops, something went wrong, please try again later.';
    $response = json_encode($response);
    exit($response);
  }

  $user_email = $_POST['user_email'];
  $from_email = $_POST['from_email'];
  $email_subject = $_POST['email_subject'];
  $email_body = $_POST['email_body'];

  email_user_test($user_email, $from_email, $email_subject, $email_body);

  $response = json_encode($response);

  exit($response);
}

// Update influencer personal info
// add_action( 'wp_ajax_inf_account_personal_info', 'inf_account_personal_info' );
// function inf_account_personal_info() {
//
//     $uid = get_current_user_id();
//     $current_email = get_userdata($uid)->user_email;
//     $message = '';
//
//     // Post data
//     $first_name = sanitize_text_field(ucfirst(strtolower($_POST['first_name'])));
//     $last_name = sanitize_text_field(ucfirst(strtolower($_POST['last_name'])));
//     $email = sanitize_email($_POST['email']);
//     $phone = sanitize_text_field($_POST['phone']);
//     $country = sanitize_text_field($_POST['country']);
//     $region = sanitize_text_field($_POST['region']);
//     $gender = sanitize_text_field($_POST['gender']);
//     $birthdate_month = sanitize_text_field($_POST['birthdate_month']);
//     $birthdate_day = sanitize_text_field($_POST['birthdate_day']);
//     $birthdate_year = sanitize_text_field($_POST['birthdate_year']);
//
//     if( $first_name != '' && $last_name != '' ) {
//
//     	update_user_meta( $uid, 'first_name', $first_name );
//     	update_user_meta( $uid, 'last_name', $last_name );
//     	wp_update_user( array( 'ID' => $uid, 'display_name' => $first_name . ' ' . $last_name ) );
//     }
//
//     if( email_exists($email) && $email != $current_email ) {
//
//     	$message .= 'Sorry, that email is already in use<br>';
//
//     } else {
//
//     	wp_update_user( array( 'ID' => $uid, 'user_email' => $email ) );
//     }
//
//     if( $phone != '' ) {
//
//     	update_user_meta( $uid, 'inf_phone', $phone );
//     }
//
//     if( $country != '' ) {
//
//     	update_user_meta( $uid, 'inf_country', $country );
//     }
//
//     if( $region != '' ) {
//
//     	update_user_meta( $uid, 'inf_region', $region );
//     }
//
//     if( $gender != '' ) {
//
//     	update_user_meta( $uid, 'inf_gender', $gender );
//     }
//
//     if( $birthdate_month != '' && $birthdate_year != '' && $birthdate_day != '' ) {
//
//     	$birthdate_string = $birthdate_month . '/' . $birthdate_day . '/' . $birthdate_year;
//     	$age = calculate_user_age($birthdate_string);
//
//     	update_user_meta( $uid, 'social_prism_user_birthday', $birthdate_string );
//     	update_user_meta( $uid, 'inf_age', $age );
//
//     }
//
//     echo $message;
//
//     exit;
// }

// Affiliate Payout Info
add_action( 'wp_ajax_affiliate_payout_info', 'affiliate_payout_info_ajax' );
function affiliate_payout_info_ajax() {

	$response = array('errors'=>'');

    $uid = get_current_user_id();

    // Post data
    $first_name = sanitize_text_field(ucfirst(strtolower($_POST['first_name'])));
    $last_name = sanitize_text_field(ucfirst(strtolower($_POST['last_name'])));
    $phone = sanitize_text_field($_POST['phone']);
    $birthdate_month = sanitize_text_field($_POST['birthdate_month']);
    $birthdate_day = sanitize_text_field($_POST['birthdate_day']);
    $birthdate_year = sanitize_text_field($_POST['birthdate_year']);
    $address_1 = sanitize_text_field($_POST['address_1']);
    $address_2 = sanitize_text_field($_POST['address_2']);
    $country = sanitize_text_field($_POST['country']);
    $region = sanitize_text_field($_POST['region']);
    $city = sanitize_text_field($_POST['city']);
    $postcode = sanitize_text_field($_POST['postcode']);

    if( $first_name != '' && $last_name != '' ) {

    	update_user_meta( $uid, 'first_name', $first_name );
    	update_user_meta( $uid, 'last_name', $last_name );
    	wp_update_user( array( 'ID' => $uid, 'display_name' => $first_name . ' ' . $last_name ) );
    }

    update_user_meta( $uid, 'inf_phone', $phone );

    if( $birthdate_month != '' && $birthdate_year != '' && $birthdate_day != '' ) {

    	$birthdate_string = $birthdate_month . '/' . $birthdate_day . '/' . $birthdate_year;
    	$age = calculate_user_age($birthdate_string);

    	update_user_meta( $uid, 'social_prism_user_birthday', $birthdate_string );
    	update_user_meta( $uid, 'inf_age', $age );

    }

	update_user_meta( $uid, 'shipping_address_1', $address_1 );
	update_user_meta( $uid, 'shipping_address_2', $address_2 );
	update_user_meta( $uid, 'shipping_country', $country );
	update_user_meta( $uid, 'shipping_city', $city );
	update_user_meta( $uid, 'shipping_state', $region );
	update_user_meta( $uid, 'shipping_postcode', $postcode );

	update_user_meta($uid, 'affiliate_info_completed', true);

    $response = json_encode($response);

  	exit($response);
}

// Affiliate Payout Info (no js backup)
add_action( 'admin_post_affiliate_payout_information', 'affiliate_payout_info_post' );
function affiliate_payout_info_post() {

  $response = array('errors'=>'');

  $uid = get_current_user_id();

  // Post data
  $first_name = sanitize_text_field(ucfirst(strtolower($_POST['first_name'])));
  $last_name = sanitize_text_field(ucfirst(strtolower($_POST['last_name'])));
  $phone = sanitize_text_field($_POST['phone']);
  $birthdate_month = sanitize_text_field($_POST['birthdate_month']);
  $birthdate_day = sanitize_text_field($_POST['birthdate_day']);
  $birthdate_year = sanitize_text_field($_POST['birthdate_year']);
  $address_1 = sanitize_text_field($_POST['shipping_address_1']);
  $address_2 = sanitize_text_field($_POST['shipping_address_2']);
  $country = sanitize_text_field($_POST['shipping_country']);
  $region = sanitize_text_field($_POST['shipping_state']);
  $city = sanitize_text_field($_POST['shipping_city']);
  $postcode = sanitize_text_field($_POST['shipping_postcode']);

  if( $first_name != '' && $last_name != '' ) {

    update_user_meta( $uid, 'first_name', $first_name );
    update_user_meta( $uid, 'last_name', $last_name );
    wp_update_user( array( 'ID' => $uid, 'display_name' => $first_name . ' ' . $last_name ) );
  }

  update_user_meta( $uid, 'inf_phone', $phone );

  if( $birthdate_month != '' && $birthdate_year != '' && $birthdate_day != '' ) {

    $birthdate_string = $birthdate_month . '/' . $birthdate_day . '/' . $birthdate_year;
    $age = calculate_user_age($birthdate_string);

    update_user_meta( $uid, 'social_prism_user_birthday', $birthdate_string );
    update_user_meta( $uid, 'inf_age', $age );

  }

  update_user_meta( $uid, 'shipping_address_1', $address_1 );
  update_user_meta( $uid, 'shipping_address_2', $address_2 );
  update_user_meta( $uid, 'shipping_country', $country );
  update_user_meta( $uid, 'shipping_city', $city );
  update_user_meta( $uid, 'shipping_state', $region );
  update_user_meta( $uid, 'shipping_postcode', $postcode );

  update_user_meta($uid, 'affiliate_info_completed', true);

  wp_redirect(get_site_url() . '/my-account/ambassador/');
}

add_action( 'wp_ajax_get_region_from_country', 'get_region_from_country' );
function get_region_from_country() {
	$country = $_POST['country'];

	if( $country !== '' ) :

		global $wpdb;

		$selected_region = get_user_meta( get_current_user_id(), 'inf_region', true );

		$query = '
			SELECT country_code, state_code, state_name
			FROM states
			WHERE country_code = "' . $country . '"
			ORDER BY state_name
		';

		$regions = $wpdb->get_results($query);

		?>
			<label>Region</label>
			<select name='inf_region' id='inf_region' >
			<option value="">Select your region</option>
		<?php

		foreach($regions as $region) :
		?>
			<option value="<?php echo $region->country_code . $region->state_code ?>" <?php echo ( $region->country_code.$region->state_code == $selected_region ? 'selected="selected"' : '' ); ?>>
				<?php echo $region->state_name; ?>
			</option>
		<?php endforeach; ?>

		</select>

	<?php endif;

	exit;
}

// Update influencer account shipping info (Post)
add_action( 'admin_post_inf_account_shipping_info', 'inf_account_shipping_info_post' );
function inf_account_shipping_info_post() {

    $uid = get_current_user_id();

    // Post data
    $address_1 = sanitize_text_field($_POST['inf_shipping_address_1']);
    $address_2 = sanitize_text_field($_POST['inf_shipping_address_2']);
    $country = sanitize_text_field($_POST['inf_shipping_country']);
    $city = sanitize_text_field($_POST['inf_shipping_city']);
    $region = sanitize_text_field($_POST['inf_shipping_region']);
    $postcode = sanitize_text_field($_POST['inf_shipping_postcode']);

	// Update usermeta
	update_user_meta( $uid, 'shipping_address_1', $address_1 );
	update_user_meta( $uid, 'shipping_address_2', $address_2 );
	update_user_meta( $uid, 'shipping_country', $country );
	update_user_meta( $uid, 'shipping_city', $city );
	update_user_meta( $uid, 'shipping_state', $region );
	update_user_meta( $uid, 'shipping_postcode', $postcode );

    wp_redirect(get_site_url() . '/my-account/personal');
    exit;
}

// Update influencer account shipping info (Ajax)
add_action( 'wp_ajax_inf_account_shipping_info', 'inf_account_shipping_info' );
function inf_account_shipping_info() {

    $uid = get_current_user_id();

    // Post data
    $address_1 = sanitize_text_field($_POST['shipping_address_1']);
    $address_2 = sanitize_text_field($_POST['shipping_address_2']);
    $country = sanitize_text_field($_POST['shipping_country']);
    $city = sanitize_text_field($_POST['shipping_city']);
    $region = sanitize_text_field($_POST['shipping_region']);
    $postcode = sanitize_text_field($_POST['shipping_postcode']);

    // Update usermeta
    update_user_meta( $uid, 'shipping_address_1', $address_1 );
    update_user_meta( $uid, 'shipping_address_2', $address_2 );
    update_user_meta( $uid, 'shipping_country', $country );
    update_user_meta( $uid, 'shipping_city', $city );
    update_user_meta( $uid, 'shipping_state', $region );
    update_user_meta( $uid, 'shipping_postcode', $postcode );

    exit;
}

// Update influencer account password
add_action( 'wp_ajax_inf_account_change_password', 'inf_account_change_password' );
function inf_account_change_password() {

    $uid = get_current_user_id();

    // Post data
	$old_password = $_POST['old_password'];
	$new_password = $_POST['new_password'];

	if( !wp_check_password( $old_password, get_userdata($uid)->user_pass, $uid ) ) {

		echo 'Sorry you did not enter the correct password';

	} else {

		wp_set_password( $new_password, $uid );
		wp_set_current_user( $uid );
		wp_set_auth_cookie( $uid );
	}
    exit;
}

// Update Influencer interests (AJAX)
add_action( 'wp_ajax_inf_account_update_interests', 'inf_account_update_interests_ajax' );
function inf_account_update_interests_ajax() {

    $uid = get_current_user_id();

    $interests = $_POST['interests'];

    if( count( $interests ) > 3 ) {

        exit( 'Please only select up to 3 interests' );
    }

    update_user_meta( $uid, 'inf_interests', $interests );

    exit;

}

// Update Influencer Interests (Admin Post)
add_action( 'admin_post_account_update_interests', 'inf_account_update_interests_post' );
function inf_account_update_interests_post() {

    $uid = get_current_user_id();

    // Post values
    $interests = $_POST['interests'];

    if( count( $interests ) > 3 ) {
        wp_redirect(get_site_url() . '/my-account/interests/');
        exit;
    }

    update_user_meta( $uid, 'inf_interests', $interests );

    wp_redirect(get_site_url() . '/my-account/interests');
    exit;
}


//Influencer interests processing from welcome page
add_action( 'admin_post_select_interests', 'update_inf_interests' );
function update_inf_interests() {

	$uid = get_current_user_id();

  $interests = $_POST['interests'];

  if( count( $interests ) > 3 ) {

    wp_redirect( get_site_url() . '/welcome/?err=1' );

    exit;
  }

  update_user_meta( $uid, 'inf_interests', $interests );
  
  $category_string = implode(',',$interests);

	wp_redirect( get_site_url() . '/product-category/' . $category_string);
}

// Influencer nudge brand
add_action( 'wp_ajax_inf_nudge_brand', 'inf_nudge_brand' );
function inf_nudge_brand() {

  $type = $_POST['type'];
  $order_id = $_POST['order'];

  if( $type == 'shipping' ) {
  	email_brand_nudge_shipping( $order_id );
  } else if( $type == 'scoring' ) {
  	email_brand_nudge_scoring( $order_id );
  }

  return;
}

/**
 * Influencer add to cart agreement
 */
add_action( 'admin_post_add_to_cart_agreement', 'add_to_cart_agreement' );
function add_to_cart_agreement() {

	wp_redirect( get_site_url() . '/checkout' );
}

//ShoutOut link submission
add_action( 'admin_post_shoutout_links', 'shoutout_links' );
function shoutout_links() {

	$uid = get_current_user_id();
	$order_id = $_POST['shoutout'];
	$products = wc_get_order( $order_id )->get_items();
	$product_id = reset( $products )->get_data()['product_id'];
	$shoutout_channels = get_post_meta( $product_id, 'shoutout_channels', true );

	// Make sure current user is influencer that submitted this shoutout
	$influencer_id = wc_get_order( $order_id )->get_data()['customer_id'];

	if ( $uid != $influencer_id ) {

		wp_redirect( '/my-account/view-order/' . $order_id );
	}

	$url_empty = false;

	foreach ( $shoutout_channels as $channel ) {
		if ( isset( $_POST[ $channel . '_url'] ) ) {

			$channel_url = $_POST[ $channel . '_url'];

			if ( $channel_url === '' ) {

				$url_empty = true;
				echo $channel . ' empty';
			}

			if ( strpos( $channel_url, 'https' ) !== false || strpos( $channel_url, 'http' ) !== false || strpos( $channel_url, '//' ) !== false || $channel_url == '' ) {

			} else {

				$channel_url = '//' . $channel_url;
			}

			update_post_meta( $order_id, $channel . '_url', $channel_url );
		}
	}

	if( $url_empty === false ) {

		// If all required links have been submitted then update the ShoutOut status and email the brand
		update_post_meta( $order_id, 'shoutout_status', 'review' );

		email_brand_shoutout_links( $order_id );

		wp_redirect( get_site_url() . '/my-account/orders/?message=success-all' );

	} else {

		update_post_meta( $order_id, 'shoutout_status', 'post' );
		wp_redirect( get_site_url() . '/my-account/orders/?message=success' );
	}
}

// Giveaway Winner Submission
add_action( 'admin_post_giveaway_winner', 'giveaway_winner' );
function giveaway_winner() {

    $uid = get_current_user_id();
    $order_id = $_POST['shoutout'];
    $winner = $_POST['giveaway_winner'];

    // Make sure current user is influencer that submitted this shoutout
    $influencer_id = wc_get_order( $order_id )->get_data()['customer_id'];
    if ( $uid != $influencer_id ) {
        wp_redirect( '/my-account/view-order/' . $order_id );
    }

    update_post_meta( $order_id, 'giveaway_winner', $winner );
    email_brand_giveaway_winner( $order_id );
    wp_redirect( get_site_url() . '/my-account/orders/' );
}

/**
 * Influencer Create/Edit Affiliate Campaign
 */
add_action( 'admin_post_affiliate_campaign', 'affiliate_campaign_add_edit' );
function affiliate_campaign_add_edit() {

	// Verify nonce
	if( !isset($_POST['affiliate_campaign_nonce']) || !wp_verify_nonce($_POST['affiliate_campaign_nonce'], 'affiliate_campaign') ) {

		wp_redirect( get_site_url() . '/affiliate-campaign');
		exit;
	}

	$uid = get_current_user_id();

	$edit = $_POST['edit'];

	$param_string = sanitize_title($_POST['param_string']);

	if($edit) {

		$owner_id = get_post_meta( $edit, 'owner_id', true );

		if( $owner_id != $uid ) {
			wp_redirect(get_site_url() . '/affiliate-campaign');
			exit;
		}

		update_post_meta( $edit, 'param_string', $param_string);

	} else {

		$links = get_posts(array(
			'post_type' => 'affiliate_link',
			'meta_key' => 'param_string',
			'meta_value' => $param_string,
		));

		if( count($links) ) {
			wp_redirect(get_site_url() . '/affiliate-campaign/?e=exists');
			exit;
		}

		$pid = wp_insert_post(array(
			'post_type' => 'affiliate_link',
			'post_status' => 'publish',
		));

		update_post_meta( $pid, 'owner_id', $uid );
		update_post_meta( $pid, 'param_string', $param_string );
	}

	wp_redirect(get_site_url() . '/my-account/affiliate');
	exit;
}

// ---------------- //
// ---- BRANDS ---- //
// ---------------- //

// Brand Registration
add_action('wp_ajax_brand_registration', 'brand_registration');
add_action('wp_ajax_nopriv_brand_registration', 'brand_registration');
function brand_registration() {

	$response = array('errors'=>array());

	if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'brand_registration')) {

		$response['errors'][] = 'Something went wrong, please try again.';

		exit(json_encode($response));
	}

	$brand_name = sanitize_text_field($_POST['brand_name']);
	$brand_website = esc_url($_POST['brand_website']);
    $brand_story = sanitize_textarea_field($_POST['brand_story']);
	$first_name = ucfirst(strtolower(sanitize_text_field($_POST['first_name'])));
	$last_name = ucfirst(strtolower(sanitize_text_field($_POST['last_name'])));
	$email = sanitize_email($_POST['email']);
	$phone = sanitize_text_field($_POST['phone']);
	$password = sanitize_text_field($_POST['password']);

	if(email_exists($email)) {
		$response['errors'][] = 'Sorry, that email is already taken, please try a different one.';
		exit(json_encode($response));
	}

	// Insert New User
	$uid = wp_insert_user(array(
		'user_login' => $email,
		'user_email' => $email,
		'role' => 'brand',
		'user_pass' => $password,
		'first_name' => $first_name,
		'last_name' => $last_name,
		'display_name' => $first_name . ' ' . $last_name,
	));

	if( !is_wp_error($uid) ) {

        $response['uid'] = $uid;

		wp_set_current_user($uid);
		wp_set_auth_cookie($uid);

		update_user_meta( $uid, 'contact_phone', $phone );

		// Insert New Brand CPT
		$bid = wp_insert_post(array(
			'post_type' => 'brand',
			'post_title' => $brand_name,
            'post_content' => $brand_story,
			'post_status' => 'publish',
		));
		update_post_meta( $bid, 'brand_website', $brand_website );
		update_post_meta( $bid, 'primary_contact', $uid );

		// Insert New Brand User CPT
		$buid = wp_insert_post(array('post_type' => 'brand_user'));
		update_post_meta( $buid, 'brand', $bid );
		update_post_meta( $buid, 'user', $uid );
		update_post_meta( $buid, 'role', 'administrator' );

        email_brand_brand_signup($bid);
        email_admin_brand_signup($bid);


	} else {
		foreach( get_error_messages($uid) as $error ) {
			$response['errors'][] = $error;
		}
	}

	exit(json_encode($response));
}

// Brand Invite Registration
add_action('wp_ajax_brand_invite_registration', 'brand_invite_registration');
add_action('wp_ajax_nopriv_brand_invite_registration', 'brand_invite_registration');
function brand_invite_registration() {

    $response = array('errors'=>array());

    if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'brand_invite_registration')) {

        $response['errors'][] = 'Something went wrong, please try again.';

        exit(json_encode($response));
    }

    $first_name = ucfirst(strtolower(sanitize_text_field($_POST['first_name'])));
    $last_name = ucfirst(strtolower(sanitize_text_field($_POST['last_name'])));
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $password = sanitize_text_field($_POST['password']);
    $invite_string = $_POST['invite_string'];

    if(email_exists($email)) {
        $response['errors'][] = 'Sorry, that email is already taken, please try a different one.';
        exit(json_encode($response));
    }

    // Insert New User
    $uid = wp_insert_user(array(
        'user_login' => $email,
        'user_email' => $email,
        'role' => 'brand',
        'user_pass' => $password,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'display_name' => $first_name . ' ' . $last_name,
    ));


    if( !is_wp_error($uid) ) {

        wp_set_current_user($uid);
        wp_set_auth_cookie($uid);

        update_user_meta( $uid, 'contact_phone', $phone );

        // Find brand user associated with invite string
        $brand_users = get_posts(array(
            'post_type' => 'brand_user',
            'meta_key' => 'invite_string',
            'meta_value' => $invite_string,
            'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
        ));
        update_post_meta( $brand_users[0]->ID, 'user', $uid );

    } else {
        foreach( get_error_messages($uid) as $error ) {
            $response['errors'][] = $error;
        }
    }

    exit(json_encode($response));
}

// Brand User Invite
add_action('wp_ajax_brand_user_invite', 'brand_user_invite');
function brand_user_invite() {

    $response = array('errors'=>array());

    $user_id = get_current_user_id();

    $brand_id = sanitize_key($_POST['brand_id']);
    $brand_role = check_brand_user_role($brand_id, $user_id);

    if($brand_role != 'administrator') {
        $response['errors'][] = 'Only admins can invite new users';
        exit(json_encode($response));
    }

    $email = sanitize_email($_POST['email']);
    $role = sanitize_text_field($_POST['role']);

    // Check if email is valid
    if(!is_email($email)) {
        $response['errors'][] = 'Email not valid.';
        exit(json_encode($response));
    }

    // Generate random unique string
    $invite_string = md5(uniqid(rand(), true));

    // Insert brand_user cpt
    $buid = wp_insert_post(array('post_type' => 'brand_user'));
    update_post_meta( $buid, 'brand', $brand_id );
    update_post_meta( $buid, 'role', $role );
    update_post_meta( $buid, 'invite_string', $invite_string );

    // send invite email
    email_brand_user_invite($email, $invite_string, $brand_id);

    exit(json_encode($response));
}

// Brand Registration
add_action('wp_ajax_add_brand', 'add_brand');
function add_brand() {

    $response = array('errors'=>array());

    if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'add_brand')) {

        $response['errors'][] = 'Something went wrong, please try again.';

        exit(json_encode($response));
    }

    $uid = get_current_user_id();

    $brand_name = sanitize_text_field($_POST['brand_name']);
    $brand_website = esc_url($_POST['brand_website']);
    $brand_story = sanitize_textarea_field($_POST['brand_story']);

    // Insert New Brand CPT
    $bid = wp_insert_post(array(
        'post_type' => 'brand',
        'post_title' => $brand_name,
        'post_content' => $brand_story,
        'post_status' => 'publish',
    ));
    update_post_meta( $bid, 'brand_website', $brand_website );
    update_post_meta( $bid, 'primary_contact', $uid );

    // Insert New Brand User CPT
    $buid = wp_insert_post(array('post_type' => 'brand_user'));
    update_post_meta( $buid, 'brand', $bid );
    update_post_meta( $buid, 'user', $uid );
    update_post_meta( $buid, 'role', 'administrator' );

    exit(json_encode($response));
}

// Edit Brand Info
add_action('wp_ajax_edit_brand', 'edit_brand');
function edit_brand() {

    $response = array('errors'=>array());

    $user_id = get_current_user_id();

    $brand_id = sanitize_key($_POST['brand_id']);
    $brand_name = sanitize_text_field($_POST['brand_name']);
    $brand_story = sanitize_textarea_field($_POST['brand_story']);
    $brand_website = esc_url($_POST['brand_website']);
    $brand_user_role = check_brand_user_role($brand_id, $user_id);

    if($brand_user_role == 'administrator') {
        wp_update_post(array(
            'ID' => $brand_id,
            'post_title' => $brand_name,
            'post_content' => $brand_story,
        ));
        update_post_meta($brand_id, 'brand_website', $brand_website);
    } else {
        $response['errors'][] = 'Only admins can edit brand info';
    }

    exit(json_encode($response));
}

// Brand User Edit Account Info
add_action('wp_ajax_brand_account_info', 'brand_account_info');
function brand_account_info() {

    $response = array('errors'=>array());

    if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'brand_account_info')) {

        $response['errors'][] = 'Something went wrong, please try again.';

        exit(json_encode($response));
    }

    $current_user = wp_get_current_user();
    $uid = $current_user->ID;
    $current_email = $current_user->user_email;

    $first_name = ucfirst(strtolower(sanitize_text_field($_POST['first_name'])));
    $last_name = ucfirst(strtolower(sanitize_text_field($_POST['last_name'])));
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $email_opt_out = $_POST['email_opt_out']?true:false;

    if(email_exists($email) && $current_email != $email) {
        $response['errors'][] = 'Sorry, that email is already taken, please try a different one.';
        exit(json_encode($response));
    }

    $user_args = array('ID' => $uid);

    if($first_name) {
        $user_args['first_name'] = $first_name;
    }
    if($last_name) {
        $user_args['last_name'] = $last_name;
    }
    if($email) {
        $user_args['user_email'] = $email;
    }
    if($phone) {
        update_user_meta($uid, 'contact_phone', $phone);
    }

    update_user_meta($uid, 'email_opt_out', $email_opt_out);

    wp_update_user($user_args);

    exit(json_encode($response));
}

/**
 * Processes brands schedule demo form
 */
add_action( 'wp_ajax_brands_schedule_demo', 'brands_schedule_demo' );
add_action( 'wp_ajax_nopriv_brands_schedule_demo', 'brands_schedule_demo' );

function brands_schedule_demo() {

    // Verify nonce
    if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'brands_schedule_demo' ) ) {

        exit( 'Ooops, something went wrong, please try again later.' );
    }

    // Post values
    $info = $_POST['info'];
    $contact_name = sanitize_text_field( $_POST['contact_name'] );
    $company_name = sanitize_text_field( $_POST['company_name'] );
    $contact_email = sanitize_email( $_POST['contact_email'] );
    $contact_phone = sanitize_text_field( $_POST['contact_phone'] );

    if( !is_email( $contact_email ) ) {

    	exit( 'Please enter a valid email address' );
    }

	email_admin_brand_demo( $info, $contact_name, $company_name, $contact_email, $contact_phone );

    exit('1');

} // brands_schedule_demo

/**
 * Processes brands simple schedule demo form
 */
add_action( 'wp_ajax_brands_schedule_demo_simple', 'brands_schedule_demo_simple' );
add_action( 'wp_ajax_nopriv_brands_schedule_demo_simple', 'brands_schedule_demo_simple' );

function brands_schedule_demo_simple() {

    // Verify nonce
    if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'brands_schedule_demo' ) ) {

        exit( 'Ooops, something went wrong, please try again later.' );
    }

    // Post values
    $contact_email = sanitize_email( $_POST['contact_email'] );

    if( !is_email( $contact_email ) ) {

    	exit( 'Please enter a valid email address' );
    }

	email_admin_brand_demo_simple( $contact_email );

    exit('1');

} // brands_schedule_demo

add_action('wp_ajax_design_campaign_update', 'design_campaign_update');
function design_campaign_update() {
    $response = array(
        'newCampaign' => false,
        'formErrors' => array(),
    );

    $uid = get_current_user_id();
    $campaign_id = isset($_POST['campaign_id'])?$_POST['campaign_id']:'';
    $brand_id = isset($_POST['brand_id'])?$_POST['brand_id']:'';
    $form_part = isset($_POST['form_part'])?$_POST['form_part']:'';
    $form_completion = isset($_POST['form_completion'])?$_POST['form_completion']:array();

    // Check if user is member of brand
    if(check_brand_user_role($brand_id, $uid)) {
        if($campaign_id) {
            // Update Existing Campaign
            update_post_meta($campaign_id, 'form_completion', $form_completion);
            // Check which form is being updated
            switch($form_part) {
                case 0: // Update campaign strategy
                    $campaign_strategy = isset($_POST['campaign_strategy'])?$_POST['campaign_strategy']:'';
                    update_post_meta($campaign_id, 'campaign_strategy', $campaign_strategy);
                    break;
                case 1: // Update Influencer Info
                    $min_age = isset($_POST['min_age'])?sanitize_text_field($_POST['min_age']):'';
                    $max_age = isset($_POST['max_age'])?sanitize_text_field($_POST['max_age']):'';
                    $gender = isset($_POST['gender'])?$_POST['gender']:'';
                    $countries = isset($_POST['countries'])?$_POST['countries']:array();
                    $country_codes = array();
                    foreach($countries as $country) {
                        $country_codes[] = $country['id'];
                    }
                    $regions = isset($_POST['regions'])?$_POST['regions']:array();
                    $region_codes = array();
                    foreach($regions as $region) {
                        $region_codes[] = $region['id'];
                    }
                    $interests = isset($_POST['interests'])?$_POST['interests']:'';
                    $acquisition_timeline = isset($_POST['acquisition_timeline'])?sanitize_text_field($_POST['acquisition_timeline']):'';
                    $quantity = 0;
                    switch($acquisition_timeline) {
                        case '3' :
                            $quantity = 40;
                            break;
                        case '6' :
                            $quantity = 90;
                            break;
                        case '9' :
                            $quantity = 145;
                            break;
                        case '12' :
                            $quantity = 205;
                            break;
                    }
                    $instagram_reach = isset($_POST['instagram_reach'])?sanitize_text_field($_POST['instagram_reach']):'';
                    $instagram_engagement = isset($_POST['instagram_engagement'])?sanitize_text_field($_POST['instagram_engagement']):'';
                    $authenticity = isset($_POST['authenticity'])?sanitize_text_field($_POST['authenticity']):'';

                    update_post_meta($campaign_id, 'min_age', $min_age);
                    update_post_meta($campaign_id, 'max_age', $max_age);
                    update_post_meta($campaign_id, 'gender', $gender);\
                    update_post_meta($campaign_id, 'countries', $country_codes);
                    update_post_meta($campaign_id, 'regions', $region_codes);
                    wp_set_post_terms($campaign_id, $interests, 'product_cat');
                    update_post_meta($campaign_id, 'acquisition_timeline', $acquisition_timeline);
                    update_post_meta($campaign_id, 'projected_stock', $quantity);
                    update_post_meta($campaign_id, 'instagram_reach', $instagram_reach);
                    update_post_meta($campaign_id, 'instagram_engagement_rate', $instagram_engagement);
                    update_post_meta($campaign_id, 'authenticity', $authenticity);
                    break;
                case 2:
                    $variation_title = isset($_POST['variation_title'])?sanitize_text_field($_POST['variation_title']):'';
                    $variation_options = isset($_POST['variation_options'])?$_POST['variation_options']:array();
                    $title = isset($_POST['title'])?sanitize_text_field($_POST['title']):'';
                    $description = isset($_POST['description'])?sanitize_textarea_field($_POST['description']):'';
                    $prize_description = isset($_POST['prize_description'])?sanitize_textarea_field($_POST['prize_description']):'';
                    $value = isset($_POST['value'])?(float)str_replace(',','',sanitize_text_field($_POST['value'])):'';
                    $campaign_type = isset($_POST['campaign_type'])?sanitize_text_field($_POST['campaign_type']):'';
                    $program_goal = isset($_POST['program_goal'])?sanitize_textarea_field($_POST['program_goal']):'';
                    $payment_structure = isset($_POST['payment_structure'])?sanitize_textarea_field($_POST['payment_structure']):'';
                    $fulfillment_type = isset($_POST['fulfillment_type'])?sanitize_text_field($_POST['fulfillment_type']):'';
                    $fulfillment_type_other = isset($_POST['fulfillment_type_other'])?sanitize_text_field($_POST['fulfillment_type_other']):'';

                    wp_update_post(array(
                        'ID' => $campaign_id,
                        'post_title' => $title,
                        'post_content' => $description,
                    ));
                    update_post_meta($campaign_id, 'variations_0_title', $variation_title);
                    update_post_meta($campaign_id, 'variations_0_options', count($variation_options));
                    for($i=0;$i<count($variation_options);$i++) {
                        update_post_meta($campaign_id, 'variations_0_options_'.$i.'_option', $variation_options[$i]);
                    }
                    update_post_meta($campaign_id, '_regular_price', $value);
                    update_post_meta($campaign_id, '_price', $value);
                    update_post_meta($campaign_id, 'prize_description', $prize_description);
                    update_post_meta($campaign_id, '_sku', rand(100000000,999999999));
                    update_post_meta($campaign_id, '_manage_stock', "yes");
                    update_post_meta($campaign_id, 'campaign_type', $campaign_type);
                    update_post_meta($campaign_id, 'program_goal', $program_goal);
                    update_post_meta($campaign_id, 'payment_structure', $payment_structure);
                    update_post_meta($campaign_id, 'fulfillment_type', $fulfillment_type);
                    update_post_meta($campaign_id, 'fulfillment_type_other', $fulfillment_type_other);
                    break;
                case 3:
                    $visuals = isset($_POST['visuals'])?sanitize_textarea_field($_POST['visuals']):'';
                    $photo_tags = isset($_POST['photo_tags'])?$_POST['photo_tags']:array();
                    $caption = isset($_POST['caption'])?sanitize_textarea_field($_POST['caption']):'';
                    $hashtags = isset($_POST['hashtags'])?$_POST['hashtags']:array();
                    $post_timeline = isset($_POST['post_timeline'])?sanitize_textarea_field($_POST['post_timeline']):'';
                    $post_timeline_custom = isset($_POST['post_timeline_custom'])?sanitize_text_field($_POST['post_timeline_custom']):'';

                    update_post_meta($campaign_id, 'visuals', $visuals);
                    update_post_meta($campaign_id, 'photo_tags', count($photo_tags));
                    for($i=0;$i<count($photo_tags);$i++) {
                        update_post_meta($campaign_id, 'photo_tags_'.$i.'_tag', $photo_tags[$i]);
                    }
                    update_post_meta($campaign_id, 'caption', $caption);
                    update_post_meta($campaign_id, 'hashtags', count($hashtags));
                    for($i=0;$i<count($hashtags);$i++) {
                        update_post_meta($campaign_id, 'hashtags_'.$i.'_tag', $hashtags[$i]);
                    }
                    update_post_meta($campaign_id, 'post_timeline', $post_timeline);
                    update_post_meta($campaign_id, 'post_timeline_custom', $post_timeline_custom);
                    break;
                case 4:
                    wp_update_post(array(
                        'ID' => $campaign_id,
                        'post_status' => 'pending',
                    ));
                    email_admin_new_campaign($campaign_id);
                    break;
            }
        } else {
            $campaign_strategy = isset($_POST['campaign_strategy'])?$_POST['campaign_strategy']:'';
            // Create New Campaign and return ID
            if($campaign_strategy) {
                // add product
                $new_campaign = array(
                    'post_status' => 'draft',
                    'post_type' => 'product',
                    'post_title' => '',
                    'post_content' => ' ',
                    'meta_input' => array(
                      'campaign_strategy' => $campaign_strategy,
                      'brand' => $brand_id,
                      'shoutout_channels' => array('instagram'),
                    ),
                );

                $campaign_id = wp_insert_post($new_campaign);

                if(!is_wp_error($campaign_id)) {
                    wp_update_post(array(
                        'ID' => $campaign_id,
                        'post_content' => '',
                    ));
                    update_post_meta($campaign_id, 'campaign_strategy', $campaign_strategy);
                    // update_post_meta($campaign_id, 'brand', $brand_id);
                    $response['newCampaign'] = $campaign_id;

                    // Create Campaign Goal
                    wp_insert_post(array(
                        'post_type' => 'campaign_goal',
                        'meta_input' => array(
                            'campaign' => $campaign_id,
                            'goal' => .5,
                        )
                    ));
                } else {
                    $response['formErrors'][] = $campaign_id->get_error_message();
                }
            }
        }
    } else {
        $response['formErrors'][] = 'You can\'t manage campaigns for this brand';
    }

    exit(json_encode($response));
}

// Brand ShoutOut Code submission
add_action( 'wp_ajax_submit_shoutout_tracking_link', 'brand_submit_shoutout_tracking_link' );
function brand_submit_shoutout_tracking_link() {
	$user = wp_get_current_user();
    $uid = $user->ID;

	// Post values
	$tracking_link =  sanitize_text_field( $_POST['tracking_link'] );
	$order_id = sanitize_key( $_POST['shoutout'] );
    $campaign_id = get_order_campaign($order_id);
    $brand_id = get_post_meta($campaign_id, 'brand', true );
	$user_role = check_brand_user_role($brand_id, $uid);

	if(!$user_role && !in_array('administrator', $user->roles, true)) {
		exit('Looks like you aren\'t a member of this Brand');
	}

	update_post_meta( $order_id, 'shoutout_shipment_tracking_link', $tracking_link );
	update_post_meta( $order_id, 'shoutout_status', 'post' );
	update_post_meta( $order_id, 'product_shipped', true );
	email_inf_brand_tracking_link( $order_id, $campaign_id, $brand_id, $tracking_link );
	exit;
}

// Brand ShoutOut Code submission
add_action( 'wp_ajax_submit_shoutout_web_redemption_code', 'brand_submit_shoutout_web_redemption_code' );
function brand_submit_shoutout_web_redemption_code() {
	$uid = get_current_user_id();

	// Post values
	$code =  sanitize_text_field( $_POST['code'] );
	$order_id = sanitize_key( $_POST['shoutout'] );
    $campaign_id = get_order_campaign($order_id);
    $brand_id = get_post_meta($campaign_id, 'brand', true );
    $user_role = check_brand_user_role($brand_id, $uid);

    if(!$user_role && !in_array('administrator', $user->roles, true)) {
        exit('Looks like you aren\'t a member of this Brand');
    }

	if( $code !== '' ) {
		update_post_meta( $order_id, 'shoutout_web_redemption_unique_code', $tracking_link );
		update_post_meta( $order_id, 'shoutout_status', 'post' );
		email_inf_brand_web_redemption_code( $order_id, $first_item_id, $brand_id, $code );
	}
	exit;
}

// Brand ShoutOut Code submission
add_action( 'wp_ajax_reject_influencer', 'brand_reject_influencer' );
function brand_reject_influencer() {
	$uid = get_current_user_id();

	// Post values
	$order_id = $_POST['order'];
	$order = wc_get_order($order_id);
	$campaign_id = get_order_campaign($order_id);
	$influencer_id = get_order_influencer($order_id);
	$inf_rejection_count = get_user_meta( $influencer_id, 'inf_rejection_count', true );
	$brand_id = get_post_meta($campaign_id, 'brand', true);
	$boxes = $_POST['boxes'];
	$other = $_POST['other'];
    $user_role = check_brand_user_role($brand_id, $uid);

    if(!$user_role && get_userdata($uid)->roles[0] !== 'administrator') {
        echo 'Sorry, you aren\'t a member of this brand';
    }

	if( empty($boxes) ) {
		echo 'Please select an option below';
		exit;
	}

	if( $inf_rejection_count == '' ) {
		$inf_rejection_count = 0;
	}
	$inf_rejection_count++;

	email_inf_brand_reject_shoutout( $order_id, $campaign_id, $boxes, $other );
	$order->update_status( 'cancelled' );
	update_user_meta( $influencer_id, 'inf_rejection_count', $inf_rejection_count );

	return;
}

// Brand ShoutOut Code submission
add_action( 'wp_ajax_shoutout_error', 'brand_shoutout_error' );
function brand_shoutout_error() {
	$uid = get_current_user_id();

	// Post values
	$order_id = $_POST['order'];
	$campaign_id = get_order_campaign( $order_id );
	$brand_id = get_post_meta( $campaign_id, 'brand', true );
	$channel = $_POST['channel'];
	$report = $_POST['report'];
    $user_role = check_brand_user_role($brand_id, $uid);

	if( !$user_role && get_userdata($uid)->roles[0] !== 'administrator' ) {
		echo 'Looks like you aren\'t a member of this brand';
		exit;
	}

	if( $report == '' ) {
		echo 'Please fill in the error report below.';
		exit;
	}

	email_admin_brand_shoutout_error( $order_id, $campaign_id, $channel, $report );

	return;
}

// Brand ShoutOut Rating
add_action( 'wp_ajax_rate_shoutout', 'brand_rate_shoutout' );
function brand_rate_shoutout() {

	$uid = get_current_user_id();

	// Post values
	$rating = absint( $_POST['rating'] );
	$order_id = sanitize_key( $_POST['shoutout'] );
	$order = wc_get_order( $order_id );
	$products = $order->get_items();
	$product = reset( $products );
	$product_id = $product->get_data()['product_id'];
	$shoutout_channels = get_post_meta( $product_id, 'shoutout_channels', true );
	$channel = $_POST['channel'];
	$notes = $_POST['notes'];
    $campaign_id = get_order_campaign($order_id);
	$brand_id = get_post_meta( $campaign_id, 'brand', true );
    $user_role = check_brand_user_role($brand_id, $uid);

	if( !$user_role && get_userdata($uid)->roles[0] !== 'administrator' ) {
		echo 'Looks like you aren\'t a member of this Brand';
		exit;
	}

	// Make sure rating is between 1 and 5
	if ( $rating > 5 ) {
		$rating = 5;
	} else if ( $rating < 1 ) {
		$rating = 1;
	}

	$waiting_for_rating = false;
	foreach ( $shoutout_channels as $shoutout_channel ) {
		if ( get_post_meta( $order_id, $shoutout_channel . '_shoutout_rating', true ) === '' && $shoutout_channel != $channel ) {
			$waiting_for_rating = true;
		}
	}

	if( $waiting_for_rating === false ) {
		update_post_meta( $order_id, 'shoutout_status', 'complete' );
		$order->update_status( 'completed' );
	}

	update_post_meta( $order_id, $channel . '_shoutout_rating', $rating );
	update_post_meta( $order_id, $channel . '_shoutout_notes', $notes );
	email_admin_shoutout_rating( $order_id, $channel, $campaign_id, $rating, $notes, $brand_id );
	exit;
}
