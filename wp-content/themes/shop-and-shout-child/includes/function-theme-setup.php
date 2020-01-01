<?php

// Disable site health fatal error email
add_filter( 'recovery_mode_email_rate_limit', 'recovery_mail_infinite_rate_limit' );
function recovery_mail_infinite_rate_limit( $rate ) {
    return 100 * YEAR_IN_SECONDS;
}

//Add scripts to head
add_action( 'wp_head', 'add_scripts_to_head' );
function add_scripts_to_head() {
?>
<!-- Hotjar Tracking Code for www.shopandshout.com -->
<script async>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:1058390,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>
<?php
}

function pm_theme_name_scripts() {
	wp_localize_script( 'pm_reg_script', 'vb_reg_vars', array(
		'vb_ajax_url' => admin_url( 'admin-ajax.php' ),
	));
}
add_action( 'wp_enqueue_scripts', 'pm_theme_name_scripts' );

/* rediect */
function wc_custom_user_redirect( $redirect, $user ) {
	/* Get the first of all the roles assigned to the user */
	$role = $user->roles[0];
	$dashboard = admin_url();
	$myaccount = get_permalink( wc_get_page_id( 'myaccount' ) );
	$brand_account = get_site_url() . '/brand-account';
	if ( $role == 'customer' || $role == 'subscriber' ) {
		/* Redirect customers and subscribers to the "My Account" page */
		$redirect = $myaccount;
	} else if ( $role == 'brand' ) {
		$redirect = $brand_account;
	} else {
		/* Redirect any other role to the previous visited page or, if not available, to the home */
		$redirect = wp_get_referer() ? wp_get_referer() : home_url();
	}
	return $redirect;
}
add_filter( 'woocommerce_login_redirect', 'wc_custom_user_redirect', 10, 2 );

// Add svg support
function add_file_types_to_uploads($file_types){
	$new_filetypes = array();
	$new_filetypes['svg'] = 'image/svg+xml';
	$file_types = array_merge($file_types, $new_filetypes );
	return $file_types;
}
add_action('upload_mimes', 'add_file_types_to_uploads');

// Brand User Role
add_role(
    'brand',
    __( 'Brand' ),
    array(
      'read' => true
    )
);

// Disabling default wordpress reset password requirements
add_action( 'wp_print_scripts', 'DisableStrongPW', 100 );
function DisableStrongPW() {
    if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
        wp_dequeue_script( 'wc-password-strength-meter' );
    }
}

// Additional query vars
add_filter( 'query_vars', 'wpd_query_vars' );
function wpd_query_vars( $query_vars ) {

    $query_vars[] = 'inf';
    $query_vars[] = 'ref';
    $query_vars[] = 'section';
    return $query_vars;
}

// Rewrite rules for influencer profile
add_action( 'init', 'wpd_influencer_rewrite_rule' );
function wpd_influencer_rewrite_rule(){

    add_rewrite_rule(
        '^influencer/([^/]*)/?',
        'index.php?pagename=influencer&inf=$matches[1]',
        'top'
    );
}

//Rewrite rules for affiliate program
add_action( 'init', 'affiliate_rewrite_rule' );
function affiliate_rewrite_rule(){

    add_rewrite_rule(
        '^recommend/([^/]*)/?',
        'index.php?ref=$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^influencer-signup/([^/]*)/?',
        'index.php?pagename=influencer-signup&ref=$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^young-stars/([^/]*)/?',
        'index.php?pagename=young-stars&ref=$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^brand-signup/([^/]*)/?',
        'index.php?pagename=brand-signup&ref=$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^brands/([^/]*)/?',
        'index.php?pagename=brands&ref=$matches[1]',
        'top'
    );
}

// Rewrite rules for brand account
add_action( 'init', 'wpd_brand_account_rewrite_rule' );
function wpd_brand_account_rewrite_rule(){

    add_rewrite_rule(
        '^brand-account/([^/]*)/?',
        'index.php?pagename=brand-account&section=$matches[1]',
        'top'
    );
}


// ----------------------------- //
// ---- Custom Product Tabs ---- //
// ----------------------------- //

//ShoutOut Requirements
add_filter( 'woocommerce_product_tabs', 'woo_shoutout_requirements_tab' );
function woo_shoutout_requirements_tab( $tabs ) {

	// Adds the new tab
	global $product;

	$tabs;

	if( !$product->has_attributes() ) {
		$tabs['shoutout_requirements_tab'] = array(
			'title' 	=> __( 'ShoutOut Requirements', 'woocommerce' ),
			'priority' 	=> 11,
			'callback' 	=> 'woo_shoutout_requirements_tab_content'
		);
	}
	return $tabs;

}

function woo_shoutout_requirements_tab_content() {
	global $product;
	$campaign_id = $product->get_id();

    echo do_shortcode('[shoutout-requirements campaign_id="'.$campaign_id.'"]');
}

//Instructions
add_filter( 'woocommerce_product_tabs', 'woo_instructions_tab' );
function woo_instructions_tab( $tabs ) {

	global $product;

	$pid = $product->get_id();

	$tabs;

	if( get_post_meta( $pid, 'instructions', true ) !== '' ) {
		$tabs['instructions_tab'] = array(
			'title' 	=> __( 'Experience Now', 'woocommerce' ),
			'priority' 	=> 12,
			'callback' 	=> 'woo_instructions_tab_content'
		);
	}
	return $tabs;

}
function woo_instructions_tab_content() {

	global $product;

	$pid = $product->get_id();
	$brand_name = get_post_meta($pid, 'brand_name', true);
	$instagram_url = get_post_meta( $pid, 'brand_instagram_url', true );
	$facebook_url = get_post_meta( $pid, 'brand_facebook_url', true );
	$fulfillment_type = get_post_meta( $pid, 'fulfillment_type', true );

	if( $instagram_url !== '' ) {

		$instagram = '<a href="' . $instagram_url . '"><img style="vertical-align: middle;" src="' .  get_stylesheet_directory_uri() . '/images/icons/instagram-coral.svg"></a>';

	} else {

		$instagram = '<img style="vertical-align: middle;" src="' .  get_stylesheet_directory_uri() . '/images/icons/instagram-coral.svg">';
	}

	if( $facebook_url !== '' ) {

		$facebook = '<a href="' . $facebook_url . '"><img style="vertical-align: middle;" src="' .  get_stylesheet_directory_uri() . '/images/icons/facebook-coral.svg"></a>';

	} else {

		$facebook = '<img style="vertical-align: middle;" src="' .  get_stylesheet_directory_uri() . '/images/icons/facebook-coral.svg">';
	}

	switch( $fulfillment_type ) {

		case 'shipping':
			$step_3 = $brand_name . ' will ship direct, check your profile for tracking details';
			break;

		case 'code':
			$step_3 = $brand_name . ' will send a unique web redemption code to your profile';
			break;

		case 'schedule':
			$step_3 = 'Check your profile for details on making your VIP reservation';
			break;

		case 'email':
			$step_3 = 'Check your profile for instructions on getting your tickets';
			break;
	}
	?>
	<h2>Follow these steps if you want to participate in a ShoutOut for <?php echo esc_attr($brand_name); ?></h2><br>

	<p>
		Step 1. Follow <?php echo esc_attr($brand_name); ?> on <?php echo $instagram; ?> and <?php echo $facebook; ?><br><br>

		Step 2. Click ShoutOut to add to your cart and then check out<br><br>

		Step 3. <?php echo $step_3 ?><br><br>

		Step 4. Have fun with your ShoutOut!
	</p>

	<?php

}

//Brand Story
add_filter( 'woocommerce_product_tabs', 'woo_brand_story_tab' );
function woo_brand_story_tab( $tabs ) {

	global $product;
	$pid = $product->get_id();
    $brand_id = get_post_meta($pid, 'brand', true);
    if($brand_id) {
        $brand_story = get_post($brand_id)->post_content;

        if( $brand_story ) {
            $tabs['brand_story_tab'] = array(
                'title'     => __( 'Brand Story', 'woocommerce' ),
                'priority'  => 13,
                'callback'  => 'woo_brand_story_tab_content'
            );
        }
    }
	return $tabs;

}
function woo_brand_story_tab_content() {
	global $product;
	$pid = $product->get_id();
    $brand_id = get_post_meta($pid, 'brand', true);
    $brand_story = get_post($brand_id)->post_content;
	// The new tab content

	echo '<h2>Brand Story</h2>';
	echo '<p>' . $brand_story . '</p>';

}
function custom_password_form() {
    $pwbox_id = rand();

    $form_output = sprintf(
        '<div class="et_password_protected_form" style="max-width: 500px; margin: 0 auto;">
            <h1>%1$s</h1>
            <br>
            <p style="line-height: 1.8em;">We\'re putting the final touches on this ShoutOut campaign, follow our <a href="https://www.instagram.com/ishopandshout/" target="_blank">Instagram stories</a> to get notified as new brands become available!%2$s</p>
            <form action="%3$s" method="post">
                <p><label style="display: block;" for="%4$s">%5$s: </label><input name="post_password" id="%4$s" type="password" size="20" maxlength="20" /></p>
                <p><button type="submit" class="et_submit_button et_pb_button">%6$s</button></p>
            </form>
        </div>',
        esc_html__( 'Coming Soon!', 'Divi' ),
        esc_html__( '', 'Divi' ),
        esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ),
        esc_attr( 'pwbox-' . $pwbox_id ),
        esc_html__( 'Campaign Password', 'Divi' ),
        esc_html__( 'Submit', 'Divi' )
    );

    $output = sprintf(
        '<div class="et_pb_section et_section_regular">
            <div class="et_pb_row">
                <div class="et_pb_column et_pb_column_4_4">
                    %1$s
                </div>
            </div>
        </div>',
        $form_output
    );

    return $output;
}
function custom_password_form_filter_override() {
    add_filter( 'the_password_form', 'custom_password_form' );
}
add_action( 'after_setup_theme', 'custom_password_form_filter_override');

// Customizing woocommerce my-account nav menu
add_filter ( 'woocommerce_account_menu_items', 'remove_my_account_links' );
function remove_my_account_links( $menu_links ){

	$menu_links = array(
		'edit-account' => 'My Profile',
		'personal' => 'Personal',
		'interests' => 'Interests',
		'orders' => 'My ShoutOuts',
        'ambassador' => 'Ambassador',
		// 'exclusive-content' => 'Exclusive Content',
	);

	return $menu_links;
}

// Register new endpoint to use for My Account page
// Note: Resave Permalinks or it will give 404 error
add_action( 'init', 'add_account_endpoints' );
function add_account_endpoints() {
    add_rewrite_endpoint( 'interests', EP_ROOT | EP_PAGES );
    add_rewrite_endpoint( 'personal', EP_ROOT | EP_PAGES );
    add_rewrite_endpoint( 'exclusive-content', EP_ROOT | EP_PAGES );
    add_rewrite_endpoint( 'ambassador', EP_ROOT | EP_PAGES );
}

// Add new query var
add_filter( 'query_vars', 'account_extra_query_vars', 0 );
function account_extra_query_vars( $vars ) {
    $vars[] = 'interests';
    $vars[] = 'personal';
    $vars[] = 'exclusive-content';
    $vars[] = 'ambassador';
    return $vars;
}

// Add content to the new endpoint
add_action( 'woocommerce_account_interests_endpoint', 'woocommerce_account_interests_content' );
function woocommerce_account_interests_content() {
ob_start();
?>
<form id="inf-interests-select-form" class="sas-form" method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
	<h1>Interests</h1>
	<div class="result-message"></div>
	<br>
	<?php echo do_shortcode('[inf-interests-boxes]'); ?>
	<br>
	<button class="sas-form-submit">Save</button>
  <input type="hidden" name="action" value="account_update_interests" />
</form>
<?php
echo ob_get_clean();
}

// Add content to the new endpoint
add_action( 'woocommerce_account_personal_endpoint', 'woocommerce_account_personal_content' );
function woocommerce_account_personal_content() {
ob_start();
wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/styles/css/bootstrap.min.css' );
// Get all user info
// Personal
$user = wp_get_current_user();
$first_name = get_user_meta( $user->ID, 'first_name', true );
$last_name = get_user_meta( $user->ID, 'last_name', true );
$gender = get_user_meta( $user->ID, 'inf_gender', true );
$birthdate = strtotime( get_user_meta( $user->ID, 'social_prism_user_birthday', true ) );
$birthdate_day = date( 'd', $birthdate );
$birthdate_month = date( 'm', $birthdate );
$birthdate_year = date( 'Y', $birthdate );
$email = $user->user_email;
$phone = get_user_meta( $user->ID, 'inf_phone', true );
$country = get_user_meta( $user->ID, 'inf_country', true );
$region = get_user_meta( $user->ID, 'inf_region', true );

// Shipping
$shipping_address_1 = get_user_meta( $user->ID, 'shipping_address_1', true );
$shipping_address_2 = get_user_meta( $user->ID, 'shipping_address_2', true );
$shipping_country = get_user_meta( $user->ID, 'shipping_country', true );
$shipping_city = get_user_meta( $user->ID, 'shipping_city', true );
$shipping_state = get_user_meta( $user->ID, 'shipping_state', true );
$shipping_postcode = get_user_meta( $user->ID, 'shipping_postcode', true );
?>
<h1>Personal</h1>
<div class="inf-personal-info-form-container">
    <div class="result-message alert-danger"><?php echo isset($_GET['errors'])&&$_GET['errors']=='email_exists'?'Sorry, that email is already in use.':''; ?></div>
    <form id="inf-personal-info-form" class="sas-form edit-account-form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
    	<br><br>
		<h2>Personal</h2>
        <div class="row">
	    	<div class="col-sm-6">
		        <div class="form-group">
		            <label for="inf_first_name">First name</label>
		            <input type="text" name="inf_first_name" id="inf_first_name" placeholder="First name" value="<?php echo $first_name ?>" class="form-control" required />
		        </div>
	    	</div>
	    	<div class="col-sm-6">
		        <div class="form-group">
		            <label for="inf_last_name">Last name</label>
		            <input type="text" name="inf_last_name" id="inf_last_name" placeholder="Last name" value="<?php echo $last_name ?>" class="form-control" required />
		        </div>
	    	</div>
        </div>
        <div class="row">
	    	<div class="col-sm-6">
		        <div class="form-group">
		            <label for="inf_email">Email</label>
		            <input type="email" name="inf_email" id="inf_email" placeholder="Email" value="<?php echo $email ?>" class="form-control" required />
		        </div>
	    	</div>
	    	<div class="col-sm-6">
		        <div class="form-group">
		            <label for="inf_phone_number">Phone number</label>
		            <input type="text" name="inf_phone_number" id="inf_phone_number" placeholder="Phone number" value="<?php echo esc_attr( $phone ); ?>" class="form-control" />
		        </div>
	    	</div>
        </div>
        <div class="row">
	    	<div class="col-sm-6">
	    		<div class="form-group">
					<label for="inf_country">Country</label>
					<select name="inf_country" id="inf_country">
						<option value="">Select a country</option>
						<option value="US" <?php echo ( $country == 'US' ? 'selected="selected"' : '' ); ?>>USA</option>
						<option value="CA" <?php echo ( $country == 'CA' ? 'selected="selected"' : '' ); ?> >Canada</option>
						<option value="GB" <?php echo ( $country == 'UK' ? 'selected="selected"' : '' ); ?> >United Kingdom</option>
					</select>
				</div>
				<div class="form-group" id="region-response"></div>
	    	</div>
	    	<div class="col-sm-6">
		        <div class="form-group">
		            <label for="inf_gender">Gender</label>
		            <select id="inf_gender" name="inf_gender">
		            	<option value="">Gender</option>
		            	<option value="female" <?php echo ( $gender == 'female' ? 'selected' : '' ); ?>>Female</option>
		            	<option value="male" <?php echo ( $gender == 'male' ? 'selected' : '' ); ?>>Male</option>
		            	<option value="non-binary" <?php echo ( $gender == 'non-binary' ? 'selected' : '' ); ?>>Non-binary</option>
		            </select>
		        </div>
	    	</div>
        </div>
        <div class="row">
	    	<div class="col-md-6">
        		<label for="inf_birthday">Date of birth</label>
        		<div class="row">
        			<div class="col-sm-6">
			            <span>Month</span>
			            <select id="inf_birthdate_month" name="inf_birthdate_month">
			            	<?php
			            		$months_array = array(
			            			'01' => 'January',
			            			'02' => 'February',
			            			'03' => 'March',
			            			'04' => 'April',
			            			'05' => 'May',
			            			'06' => 'June',
			            			'07' => 'July',
			            			'08' => 'August',
			            			'09' => 'September',
			            			'10' => 'October',
			            			'11' => 'November',
			            			'12' => 'December',
			            		);
			            	?>
			            	<option>Select a month</option>
			            	<?php foreach( $months_array as $month => $name ) : ?>
			            		<option value="<?php echo $month ?>" <?php echo ( $birthdate_month == $month ? 'selected' : '' ); ?>><?php echo $name ?></option>
			            	<?php endforeach; ?>
			            </select>
		            </div>
		            <div class="col-sm-3">
			            <span>Day</span>
			            <input type="text" id="inf_birthdate_day" name="inf_birthdate_day" value="<?php echo esc_attr($birthdate_day); ?>" placeholder="Day">
		            </div>
		            <div class="col-sm-3">
		            	<span>Year</span>
		            	<input type="text" id="inf_birthdate_year" name="inf_birthdate_year" value="<?php echo esc_attr($birthdate_year); ?>" placeholder="Year">
		            </div>
        		</div>
        	</div>
        </div>
        <br>
        <input type="hidden" name="action" value="inf_account_personal_info">
        <button type="submit" class="sas-form-submit"/>Save</button>
        <br>
        <br>
    </form>

    <form id="inf-shipping-info-form" class="sas-form edit-account-form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
		<h2>Shipping Address</h2>
    	<div class="result-message"></div>
        <div class="row">
	    	<div class="col-sm-6">
		        <div class="form-group">
		            <label for="inf_shipping_address_1">Address line 1</label>
		            <input type="text" name="inf_shipping_address_1" id="inf_shipping_address_1" placeholder="Address line 1" value="<?php echo $shipping_address_1 ?>" class="form-control" />
		        </div>
	    	</div>
	    	<div class="col-sm-6">
		        <div class="form-group">
		            <label for="inf_shipping_address_2">Address line 2</label>
		            <input type="text" name="inf_shipping_address_2" id="inf_shipping_address_2" placeholder="Address line 2" value="<?php echo $shipping_address_2 ?>" class="form-control" />
		        </div>
	    	</div>
        </div>
        <div class="row">
	    	<div class="col-sm-6">
		        <div class="form-group">
		            <label for="inf_shipping_country">Country</label>
		            <input type="text" name="inf_shipping_country" id="inf_shipping_country" placeholder="Country" value="<?php echo $shipping_country ?>" class="form-control" />
		        </div>
	    	</div>
	    	<div class="col-sm-6">
		        <div class="form-group">
		            <label for="inf_shipping_city">City</label>
		            <input type="text" name="inf_shipping_city" id="inf_shipping_city" placeholder="City" value="<?php echo $shipping_city ?>" class="form-control"/>
		        </div>
	    	</div>
        </div>
        <div class="row">
	    	<div class="col-sm-6">
		        <div class="form-group">
		            <label for="inf_shipping_region">State/Province</label>
		            <input type="text" name="inf_shipping_region" id="inf_shipping_region" placeholder="State/Province" value="<?php echo $shipping_state ?>" class="form-control"/>
		        </div>
	    	</div>
	    	<div class="col-sm-6">
		        <div class="form-group">
		            <label for="inf_shipping_postcode">Zip/Postal Code</label>
		            <input type="text" name="inf_shipping_postcode" id="inf_shipping_postcode" placeholder="Zip/Postal code" value="<?php echo $shipping_postcode ?>" class="form-control"/>
		        </div>
	    	</div>
        </div>
        <br>
        <input type="hidden" name="action" value="inf_account_shipping_info">
        <button type="submit" class="sas-form-submit"/>Save</button>
        <br>
        <br>
    </form>
    <form id="inf-change-password-form" class="sas-form edit-account-form" method="post">
		<h2>Password</h2>
    	<div class="result-message"></div>
        <div class="row">
	    	<div class="col-sm-6">
		        <div class="form-group">
		            <label for="inf_old_password">Old password</label>
		            <input type="password" name="inf_old_password" id="inf_old_password" placeholder="Old password" class="form-control" required/>
		            <span class="toggle-password">Show</span>
		        </div>
	    	</div>
        </div>
        <div class="row">
	    	<div class="col-sm-6">
		        <div class="password-info-container">
		            <div id="password-info">
		                <ul>
		                    <li id="uppercase" class="invalid"><span>One uppercase letter</span></li>
		                    <li id="number" class="invalid"><span>One number</span></li>
		                    <li id="length" class="invalid"><span>6 characters minimum</span></li>
		                </ul>
		            </div>
		        </div>
		        <div class="form-group">
		            <label for="inf_password">New password</label>
		            <input type="password" name="inf_password" id="inf_password" placeholder="New password" class="form-control" required/>
		            <span class="toggle-password">Show</span>
		        </div>
	    	</div>
	    	<div class="col-sm-6">
		        <div class="form-group">
		            <label for="inf_confirm_password">Confirm password</label>
		            <input type="password" name="inf_confirm_password" id="inf_confirm_password" placeholder="Confirm password" class="form-control" required/>
		            <span class="toggle-password">Show</span>
		        </div>
	    	</div>
        </div>
        <br>
        <button type="submit" class="sas-form-submit"/>Save</button>
    </form>
</div>
<?php
echo ob_get_clean();
}

// Add content to the new endpoint
add_action( 'woocommerce_account_exclusive-content_endpoint', 'woocommerce_account_exclusive_content_content' );
function woocommerce_account_exclusive_content_content() {

$posts = get_posts(array('post_type' => 'exclusive_content'));

ob_start();
?>
<div class="exclusive-content-wrapper">
	<h1>Influencer Exclusive Content</h1>
	<div class="exclusive-content-grid">

		<?php foreach( $posts as $post ) : ?>

		<?php
			$featured_image_url = get_the_post_thumbnail_url($post->ID);
			$file = get_field( 'file', $post->ID );
		?>

			<div class="exclusive-content-card-wrapper">
				<div class="exclusive-content-card">
					<div class="card-hero" style="background-image: url(<?php echo esc_url($featured_image_url); ?>) ;">
						<div class="card-hero-inner">
							<h2><?php echo esc_html($post->post_title); ?></h2>
						</div>
					</div>
					<div class="card-info">
						<p><?php echo esc_html($post->post_content); ?></p>
						<a href="<?php echo esc_url($file); ?>" download>Download</a>
					</div>
				</div>
			</div>

		<?php endforeach; ?>
	</div>
</div>
<?php
echo ob_get_clean();
}

// Add content to the new endpoint
add_action( 'woocommerce_account_ambassador_endpoint', 'woocommerce_account_ambassador_content' );
function woocommerce_account_ambassador_content() {

$uid = get_current_user_id();

$affiliate_info_completed = get_user_meta($uid, 'affiliate_info_completed', true);
$instagram_id = get_user_meta($uid, 'inf_instagram_id', true);

$args = array(
	'post_type' => 'affiliate_link',
	'meta_key' => 'owner_id',
	'meta_value' => $uid,
);

$links = get_posts($args);

if( !empty($links) ) {

	$selected_link = ( get_user_meta($uid, 'selected_affiliate_link', true ) ? get_user_meta($uid, 'selected_affiliate_link', true ) : $links[0]->ID );
}

ob_start();
?>
<div class="affiliate-dashboard-wrapper">
	<?php if($affiliate_info_completed) : ?>
		<h1>Ambassador Dashboard</h1>

		<!-- <a class="sas-round-button-primary" href="<?php //echo get_site_url() . '/affiliate-campaign/'; ?>">Create New Referral Link</a> -->
		<br>
		<?php if(!empty($links)) : ?>
		<div class="affiliate-data-wrapper">
			<select id="affiliate-link-select" style="
            <?php echo count($links) > 1 ? '' : 'display: none;' ?>">

			<?php foreach( $links as $link ) : ?>

				<?php $param_string = get_post_meta( $link->ID, 'param_string', true ); ?>

				<option value="<?php echo $link->ID; ?>" <?php echo ( $link->ID == $selected_link ? 'selected="selected"' : '' ); ?>>
					<?php echo $param_string ?>
				</option>

			<?php endforeach; ?>

			</select>

			<div class="affiliate-data-placeholder ajax-info-placeholder">
				<span>Loading <i class="fa fa-spinner fa-spin"></i></span>
			</div>
			<div id="affiliate-data-container"></div>
		</div>
	    <?php endif; ?>
	<?php else : ?>
		<h1>Ambassador Program</h1>
		<!-- <form id="affiliate-opt-in" method="post" action="<?php //echo admin_url('admin-post.php') ?>">
			<button class='sas-round-button-primary'>Sign me up!</button>
			<input type="hidden" name="action" value="affiliate_opt_in">
		</form> -->

        <p>
            Welcome to the ShopandShout Ambassador program! If you'd like to opt in:<br>
            <ul>
                <?php echo $instagram_id ? '' : '<li>Go to <a href="' . get_site_url() . '/my-account/edit-account/">your account dashboard</a> and connect your Instagram account.</li>' ?>
                <?php echo $affiliate_info_completed ? '' : '<li>Fill out the <a href="' . get_site_url() . '/affiliate-payout-information">Ambassador Payout Information form</a></li>' ?>
            </ul>
        </p>
	<?php endif; ?>
</div>
<?php
echo ob_get_clean();
}
?>
