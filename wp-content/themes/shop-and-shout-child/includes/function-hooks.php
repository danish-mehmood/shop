<?php

if ( ! defined( 'ABSPATH' ) ) {

	exit;
}

// Woocommerce no variation selected notice
add_filter( 'woocommerce_get_script_data', 'change_alert_text', 10, 2 );
function change_alert_text( $params, $handle ) {
    if ( $handle === 'wc-add-to-cart-variation' )
        $params['i18n_make_a_selection_text'] = __( 'Please select your desired Campaign options', 'domain' );
    return $params;
}

/* WooCommerce Cart Validation */
add_filter( 'woocommerce_add_to_cart_validation', 'so_validate_add_cart_item', 10, 5 );
function so_validate_add_cart_item( $passed, $product_id, $quantity, $variation_id = '', $variations= '' ) {

	$passed = false;

	if (!is_user_logged_in()) {
		ob_start();
		?>
			<div class="body">
				<span>You must be <a href="<?php echo get_site_url() . '/my-account/edit-account/' ?>">logged in</a></span>
			</div>
		<?php
		$wc_notice = ob_get_clean();
		wc_add_notice($wc_notice);
		return $passed;
	} else {
		/* Get cart quantity */
		$WC = WC();

		$WC->cart->empty_cart();

		$user_id = get_current_user_id();

		/*order hrs and brand match*/
		$order_brand_name=array();
		$hours='';

		// Brand Info
		$brand_id = get_post_meta($product_id, 'brand', true);
		$brand_name = $brand_id ? get_the_title($brand_id) : '';

		// Getting current customer orders
		$customer_orders = get_posts( array(
			'numberposts' => -1,
			'meta_key'    => '_customer_user',
			'orderby'     => 'date',
			'order'       => 'DESC',
			'meta_value'  => get_current_user_id(),
			'post_type'   => wc_get_order_types(),
			'post_status' => array_keys( wc_get_order_statuses() ),
		) );

		if ( $customer_orders ) {
			
			// Loop through each customer WC_Order objects
			foreach($customer_orders as $order ){
				$order_campaign = get_order_campaign($order->ID);
				// Check if user has already ordered this campaign
				if($order_campaign == $product_id) { 
					ob_start();
					?>
						<div class="body">
							<span>You've already opted into this campaign.</span>
						</div>
					<?php
					$wc_notice = ob_get_clean();
					wc_add_notice($wc_notice);
					return $passed;
				}
			}
		}
		
		if(count($customer_orders) > 1) {
			$second_last_order = wc_get_order($customer_orders[1]->ID);
			$order_time = strtotime($second_last_order->get_date_created());
			$seconds_since = time() - $order_time;
			$hours_since = $seconds_since/60/60;

			if( ( $hours_since < 1 ) && ( $hours_since != '' ) ) {
				ob_start();
				?>
				<div class="body">
					<span>Slow down! You can only opt into up to 2 campaigns every hour. You have <?php echo 60-round($hours_since*60); ?> minutes left.</span>
				</div>
				<?php
				$wc_notice = ob_get_clean();
				wc_add_notice($wc_notice);
				return $passed;
			}
		}

		// user data
		$user_facebook_reach = get_user_meta($user_id, 'social_prism_user_facebook_reach', true);
		$user_twitter_reach = get_user_meta($user_id, 'social_prism_user_twitter_reach', true);
		$user_instagram_reach = get_user_meta($user_id, 'social_prism_user_instagram_reach', true);
		$user_instagram_engagement = get_user_meta($user_id, 'instagram-engagement-rate', true);
		$social_prism_user_age = get_user_meta( $user_id, 'social_prism_user_birthday', true );
		$social_prism_user_age = calculate_user_age( $social_prism_user_age );
		$inf_country = get_user_meta( $user_id, 'inf_country', true );
		$inf_region = get_user_meta( $user_id, 'inf_region', true );
		$user_interests = get_user_meta( $user_id, 'inf_interests', true );

		// Creating a string of user interests to display in error
		if( $user_interests ) {

			$interests_array = array();

			foreach( $user_interests as $interest ) {

				$category = get_term_by( 'slug', $interest, 'product_cat' );
				$interests_array[] = $category->name;
			}

			$interests_string = implode( ', ', $interests_array );
		}

		//shoutout data
		$product_facebook_reach = get_post_meta( $product_id, 'facebook_reach', true );
		$product_twitter_reach = get_post_meta( $product_id, 'twitter_reach', true );
		$product_instagram_reach = get_post_meta( $product_id, 'instagram_reach', true );
		$product_instagram_engagement_rate = get_field( "instagram_engagement_rate", $product_id );
		$product_min_age = get_post_meta( $product_id, "min_age", true );
		$product_max_age = get_post_meta( $product_id, "max_age", true );
		$product_countries_include_exclude = get_post_meta( $product_id, 'countries_include_exclude', true );
		$product_countries = get_post_meta( $product_id, 'countries', true );
		$product_regions_include_exclude = get_post_meta( $product_id, 'regions_include_exclude', true );
		$product_regions = get_post_meta( $product_id, 'regions', true );
		$product_categories = get_the_terms( $product_id, 'product_cat' );
		$categories_string = '';

		if( $product_categories ) {

			$categories_array = array();

			foreach( $product_categories as $cat ) {

				$categories_array[] = $cat->name;
			}

			$categories_string = implode( ', ', $categories_array );
		}

		// Checking Age
		$age_check = false;

		if( $product_min_age == '' && $product_max_age == '' ) {

			if( $social_prism_user_age < 13 ) {

				$age_error = 'Minimum age: 13';

			} else {

				$age_check = true;
			}

		} else if ( $product_min_age != '' && $product_max_age != '' ) {

			if( $social_prism_user_age >= $product_min_age && $social_prism_user_age <= $product_max_age ) {

				$age_check = true;

			} else {

				$age_error = 'Age range: ' . $product_min_age . '-' . $product_max_age;
			}

		} else {

			if( $product_min_age != '' ) {

				if( $social_prism_user_age >= $product_min_age ) {

					$age_check = true;

				} else {

					$age_error = 'Minimum age: ' . $product_min_age;
				}

			}	else {

				if( $social_prism_user_age <= $product_max_age ) {

					$age_check = true;

				} else {

					$age_error = 'Maximum age: ' . $product_max_age;
				}
			}

		}

		// Checking Location
		$country_found = true;
		$region_found = true;

		if(!empty($product_countries)) {

			if($product_countries_include_exclude == 'include') {

				if( !in_array($inf_country, $product_countries) ) {
					$country_found = false;

					$campaign_country_error = 'This campaign is only available in the following countries: ' . implode(', ', get_country_names($product_countries));
				}

			} else {

				if( in_array($inf_country, $product_countries) ) {
					$country_found = false;

					$campaign_country_error = 'This campaign is not available in the following countries: ' . implode(', ', get_country_names($product_countries));
				}
			}

			if(!empty($product_regions) ) {

				if($product_regions_include_exclude == 'include') {

					if( !in_array($inf_region, $product_regions) ) {
						$region_found = false;

						$campaign_region_error = 'This campaign is only available in the following regions: ' . implode(', ', get_region_names($product_regions));
					}
				} else {

					if( in_array($inf_region, $product_regions) ) {

						$region_found = false;

						$campaign_region_error = 'This campaign is not available in the following regions: ' . implode(', ', get_region_names($product_regions));
					} else if( !$inf_region ) {

						$region_found = false;
					}
				}
			}
		}

		// Checking Interests/Categories
		$interests_found = false;

		if( empty( $product_categories ) ){

			$interests_found = true;

		} else {

			if( $user_interests ) {

				foreach( $product_categories as $cat ) {

					if( in_array( $cat->slug, $user_interests ) ) {

						$interests_found = true;
					}
				}
			}
		}

		if (
			$user_facebook_reach >= $product_facebook_reach &&
			$user_twitter_reach >= $product_twitter_reach &&
			$user_instagram_reach >= $product_instagram_reach &&
			$user_instagram_engagement >= $product_instagram_engagement_rate &&
			$age_check &&
			$country_found &&
			$interests_found &&
			$region_found
		) {
			$passed = true;
			return $passed;
		} else {
			ob_start();
			?>
			<div class="header-message">
				<span>Uh oh! seems like you don't meet the following opt-in requirement(s) :(</span>
			</div>
			<ul class="body">
				<?php if($user_facebook_reach < $product_facebook_reach): ?>
					<li>Reach too small</li>
				<?php endif; ?>

				<?php if($user_twitter_reach < $product_twitter_reach): ?>
					<li>Reach too small</li>
				<?php endif; ?>

				<?php if($user_instagram_reach < $product_instagram_reach): ?>
					<li>Reach too small</li>
				<?php endif; ?>

				<?php if($user_instagram_engagement < $product_instagram_engagement_rate): ?>
					<li>Engagement not high enough</li>
				<?php endif; ?>

				<?php if(!$interests_found): ?>
					<li>Your interests don't match</li>
				<?php endif; ?>

				<?php if(!$age_check): ?>
					<li>You aren't in this campaign's age range</li>
				<?php endif; ?>

				<?php if(!$country_found): ?>
					<li>This campaign isn't available in your country</li>
				<?php endif; ?>

				<?php if(!$region_found): ?>
					<li>This campaign isn't available in your region</li>
				<?php endif; ?>
			</ul>

			<div class="footer-message">
				<span>Explore the marketplace for other campaigns you might qualify for.</span>
			</div>

			<div class="close-message">
				<span class="close">Back to Page</span>
			</div>
			<?php
			$wc_notice = ob_get_clean();
			wc_add_notice($wc_notice);
		}
	}
	return $passed;
}


/*
 * Filter to redirect to add to cart agreement after adding to cart button
 */
add_action( 'woocommerce_add_to_cart', 'action_woocommerce_add_to_cart', 11, 2 );
function action_woocommerce_add_to_cart( $cart_item_data, $product_id ) {

	if ($product_id != 448 && $product_id != 590 && $product_id != 592) {

		$url = get_site_url() . '/add-to-cart-agreement/?p=p'.$product_id;

		echo "<script>document.location = '".$url."';</script>";
	}
}

/* Logout url redirect for customer */
add_action( 'template_redirect', 'iconic_bypass_logout_confirmation' );
function iconic_bypass_logout_confirmation() {

	global $wp;

	if ( isset( $wp->query_vars['customer-logout'] ) ) {

		wp_redirect( str_replace( '&amp;', '&', wp_logout_url( wc_get_page_permalink( 'myaccount' ) ) ) );

		exit;
	}
}


// FROM PREVIOUS DEV unsure what this functionality is for
add_action('init', 'app_output_buffer');
function app_output_buffer() {

	ob_start();
} // soi_output_buffer


//add user profile social fields
add_filter( 'manage_users_columns', 'new_modify_user_table' );
function new_modify_user_table( $column ) {
	$column['social_prism_user_facebook_reach'] = 'Facebook Reach';
	$column['social_prism_user_twitter'] = 'Twitter Handle';
	$column['social_prism_user_twitter_reach'] = 'Twitter Reach';
	$column['social_prism_user_instagram'] = 'Instagram Handle';
	$column['social_prism_user_instagram_reach'] = 'Instagram Reach';
	return $column;
}


add_action('manage_users_custom_column', 'show_custom_column_values', 10, 3);
function show_custom_column_values($value, $column_name, $user_id) {
	if ( 'social_prism_user_facebook_reach' == $column_name )
		return get_user_meta( $user_id, 'social_prism_user_facebook_reach', true );
	if ( 'social_prism_user_twitter' == $column_name )
		return get_user_meta( $user_id, 'social_prism_user_twitter', true );
	if ( 'social_prism_user_twitter_reach' == $column_name )
		return get_user_meta( $user_id, 'social_prism_user_twitter_reach', true );
	if ( 'social_prism_user_instagram' == $column_name )
		return get_user_meta( $user_id, 'social_prism_user_instagram', true );
	if ( 'social_prism_user_instagram_reach' == $column_name )
		return get_user_meta( $user_id, 'social_prism_user_instagram_reach', true );
	return $value;
}


add_action( 'show_user_profile', 'extra_profile_fields', 10 );
add_action( 'edit_user_profile', 'extra_profile_fields', 10 );
function extra_profile_fields( $user ) {
	$affiliate_status = get_user_meta($user->ID, 'affiliate_status', true);
	$affiliate_links = get_posts(array(
		'post_type' => 'affiliate_link',
		'meta_key' => 'owner_id',
		'meta_value' => $user->ID,
	));
?>

<h3>Affiliate</h3>
<table class="form-table">
<tr>
<th><label>Affiliate Opt-In</label></th>
	<td>
		<input type="checkbox" name="affiliate_opt_in" value=1 <?php echo ($affiliate_status == 'opted_in' || $affiliate_status == 'complete' ? 'checked="checked"' : ''); ?>>
	</td>
</tr>
<tr>
	<th><label>Affiliate Links</label></th>
	<td>
		<table>
			<tr>
				<th>Edit Link</th>
				<th>Link String</th>
			</tr>
			<?php foreach($affiliate_links as $link) :

				$link_string = get_post_meta($link->ID, 'param_string', true);
			?>
				<tr>
					<td><a href="<?php echo get_site_url() . '/wp-admin/post.php?post=' . $link->ID . '&action=edit'; ?>"><?php echo $link->ID; ?></a></td>
					<td><?php echo $link_string; ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</td>
</tr>
</table>
<h3><?php _e('Extra Profile Fields', 'frontendprofile'); ?></h3>
<table class="form-table">
<tr>
	<th><label for="social_prism_user_facebook_reach">Facebook Reach</label></th>
	<td>
		<input type="text" name="social_prism_user_facebook_reach" id="social_prism_user_facebook_reach" value="<?php echo esc_attr( get_the_author_meta( 'social_prism_user_facebook_reach', $user->ID ) ); ?>" class="regular-text" />
	</td>
</tr>
<tr>
	<th><label for="referralcode">Referral Code</label></th>
	<td>
		<input type="text" name="referralcode" id="referralcode" value="<?php echo esc_attr( get_the_author_meta( 'referralcode', $user->ID ) ); ?>" class="regular-text" />
	</td>
</tr>
<tr>
	<th><label for="social_prism_user_gender">Gender</label></th>
	<td>
		<input type="text" name="social_prism_user_gender" id="social_prism_user_gender" value="<?php echo esc_attr( get_the_author_meta( 'social_prism_user_gender', $user->ID ) ); ?>" class="regular-text" />
	</td>
</tr>
<tr>
	<th><label for="social_prism_user_birthday">Birthday</label></th>
	<td>
		<input type="text" name="social_prism_user_birthday" id="social_prism_user_birthday" value="<?php if( get_the_author_meta( 'social_prism_user_birthday', $user->ID )!='' ){ echo esc_attr( get_the_author_meta( 'social_prism_user_birthday', $user->ID ) ); } ?>" class="regular-text" />
	</td>
</tr>
<tr>
<tr>
	<th><label for="social_prism_user_age">Age</label></th>
	<td>
		<input type="text" name="social_prism_user_age" id="social_prism_user_age" value="<?php if( get_the_author_meta( 'social_prism_user_age', $user->ID )!='' ){ echo esc_attr( get_the_author_meta( 'inf_age', $user->ID ) );} ?>" class="regular-text" />
	</td>
</tr>
<tr>
	<th><label for="social_prism_user_location">Location</label></th>
	<td>
		<input type="text" name="social_prism_user_location" id="social_prism_user_location" value="<?php echo esc_attr( get_the_author_meta( 'social_prism_user_location', $user->ID ) ); ?>" class="regular-text" />
	</td>
</tr>
<tr>
	<th><label for="social_prism_user_state_province">Location</label></th>
	<td>
		<input type="text" name="social_prism_user_state_province" id="social_prism_user_state_province" value="<?php echo esc_attr( get_the_author_meta( 'social_prism_user_state_province', $user->ID ) ); ?>" class="regular-text" />
	</td>
</tr>
<tr>
	<th><label for="social_prism_user_twitter">Twitter Handle</label></th>
	<td>
		<input type="text" name="social_prism_user_twitter" id="social_prism_user_twitter" value="<?php echo esc_attr( get_the_author_meta( 'social_prism_user_twitter', $user->ID ) ); ?>" class="regular-text" />
	</td>
</tr>
<tr>
	<th><label for="social_prism_user_twitter_reach">Twitter Reach</label></th>
	<td>
		<input type="text" name="social_prism_user_twitter_reach" id="social_prism_user_twitter_reach" value="<?php echo esc_attr( get_the_author_meta( 'social_prism_user_twitter_reach', $user->ID ) ); ?>" class="regular-text" />
	</td>
</tr>
<tr>
	<th><label for="twitter-full-name">Twitter Full Name</label></th>
	<td>
		<input type="text" name="twitter-full-name" id="twitter-full-name" value="<?php echo esc_attr( get_the_author_meta( 'twitter-full-name', $user->ID ) ); ?>" class="regular-text" />
	</td>
</tr>
<tr>
	<th><label for="twitter-following">Twitter Following</label></th>
	<td>
		<input type="text" name="twitter-following" id="twitter-following" value="<?php echo esc_attr( get_the_author_meta( 'twitter-following', $user->ID ) ); ?>" class="regular-text" />
	</td>
</tr>
<tr>
	<th><label for="twitter-bio">Twitter Bio</label></th>
	<td>
		<textarea style="height: 150px;" name="twitter-bio" id="twitter-bio" value="" class="regular-text" ><?php echo esc_attr( get_the_author_meta( 'twitter-bio', $user->ID ) ); ?></textarea>
	</td>
</tr>
<tr>
	<th><label for="twitter-count">Twitter Count</label></th>
	<td>
		<input type="text" name="twitter-count" id="twitter-count" value="<?php echo esc_attr( get_the_author_meta( 'twitter-count', $user->ID ) ); ?>" class="regular-text" />
	</td>
</tr>
<tr>
	<th><label for="twitter-url">Twitter URL</label></th>
	<td>
		<input type="text" name="twitter-url" id="twitter-url" value="<?php echo esc_attr( get_the_author_meta( 'twitter-url', $user->ID ) ); ?>" class="regular-text" />
	</td>
</tr>
<tr>
	<th><label for="social_prism_user_instagram">Instagram Handle</label></th>
	<td>
		<input type="text" name="social_prism_user_instagram" id="social_prism_user_instagram" value="<?php echo esc_attr( get_the_author_meta( 'social_prism_user_instagram', $user->ID ) ); ?>" class="regular-text" />
	</td>
</tr>
<tr>
	<th><label for="social_prism_user_instagram_reach">Instagram Reach</label></th>
	<td>
		<input type="text" name="social_prism_user_instagram_reach" id="social_prism_user_instagram_reach" value="<?php echo esc_attr( get_the_author_meta( 'social_prism_user_instagram_reach', $user->ID ) ); ?>" class="regular-text" />
	</td>
</tr>
<tr>
	<th><label for="instagram-full-name">Instagram Full Name</label></th>
	<td>
		<input type="text" name="instagram-full-name" id="instagram-full-name" value="<?php echo esc_attr( get_the_author_meta( 'instagram-full-name', $user->ID ) ); ?>" class="regular-text" />
	</td>
</tr>
<tr>
	<th><label for="instagram-bio">Instagram Bio</label></th>
	<td>
		<textarea style="height: 150px;" name="instagram-bio" id="instagram-bio" class="regular-text" ><?php echo esc_attr( get_the_author_meta( 'instagram-bio', $user->ID ) ); ?></textarea>
	</td>
</tr>
<tr>
	<th><label for="instagram-following">Instagram Following</label></th>
	<td>
		<input type="text" name="instagram-following" id="instagram-following" value="<?php echo esc_attr( get_the_author_meta( 'instagram-following', $user->ID ) ); ?>" class="regular-text" />
	</td>
</tr>
<tr>
	<th><label for="instagram-url">Instagram URL</label></th>
	<td>
		<input type="text" name="instagram-url" id="instagram-url" value="<?php echo esc_attr( get_the_author_meta( 'instagram-url', $user->ID ) ); ?>" class="regular-text" />
	</td>
</tr>
<tr>
	<th><label for="instagram-url">Engagement Rate %</label></th>
	<td>
		<input type="text" name="instagram-engagement-rate" id="instagram-engagement-rate" value="<?php echo esc_attr( get_the_author_meta( 'instagram-engagement-rate', $user->ID ) ); ?>" class="regular-text" />
	</td>
</tr>



</table>
<?php
	$ig_assistant_opted_in = get_user_meta( $user->ID, 'ig_assistant_opted_in', true );
?>
<?php if( $ig_assistant_opted_in == 'true' ) : ?>
	<?php
		$ig_assistant_info = get_user_meta( $user->ID, 'ig_assistant_info', true );
		if($ig_assistant_info !== '' ) {

			$ig_assistant_hashtags = $ig_assistant_info['hashtags'];
			$ig_assistant_accounts = $ig_assistant_info['accounts'];
			$ig_assistant_reason = $ig_assistant_info['reason'];
			$ig_assistant_reach = $ig_assistant_info['reach'];
			$ig_assistant_engagement = $ig_assistant_info['engagement'];

			$decrypt_pass = \Defuse\Crypto\Crypto::decrypt( $ig_assistant_info['pass'], \Defuse\Crypto\Key::loadFromAsciiSafeString(SAS_ENCRYPT_KEY) );

		} else {

			$ig_assistant_hashtags = '';
			$ig_assistant_accounts = '';
			$ig_assistant_reason = '';
			$ig_assistant_reach = '';
			$ig_assistant_engagement = '';
			$decrypt_pass = '';
		}
	?>
	<h2 id="ig-assistant">Instagram Assistant</h2>
	<table class="form-table">
	<tr>
		<th><label for="ig-assistant-hashtags">10 hashtags</label></th>
		<td>
			<input type="text" name="ig-assistant-hashtags" id="ig-assistant-hashtags" value="<?php echo esc_attr($ig_assistant_hashtags); ?>" class="regular-text" />
		</td>
	</tr>
	<tr>
		<th><label for="ig-assistant-accounts">10 accounts</label></th>
		<td>
			<input type="text" name="ig-assistant-accounts" id="ig-assistant-accounts" value="<?php echo esc_attr($ig_assistant_accounts); ?>" class="regular-text" />
		</td>
	</tr>
	<tr>
		<th><label for="ig-assistant-reason">Reason for opting in</label></th>
		<td>
			<textarea name="ig-assistant-reason" id="ig-assistant-reason"><?php echo esc_attr($ig_assistant_reason); ?></textarea>
		</td>
	</tr>
	<tr>
		<th><label for="ig-assistant-reach">Instagram Reach (at time of opting in)</label></th>
		<td>
			<input type="text" name="ig-assistant-reach" id="ig-assistant-reach" value="<?php echo esc_attr($ig_assistant_reach); ?>" class="regular-text" />
		</td>
	</tr>
	<tr>
		<th><label for="ig-assistant-engagement">Instagram Engagement Rate % (at time of opting in)</label></th>
		<td>
			<input type="text" name="ig-assistant-engagement" id="ig-assistant-engagement" value="<?php echo esc_attr($ig_assistant_engagement); ?>" class="regular-text" />
		</td>
	</tr>
	<tr>
		<th><label for="ig-assistant-password">Password</label></th>
		<td>
			<input type="text" name="ig-assistant-password" id="ig-assistant-password" value="<?php echo esc_attr($decrypt_pass); ?>" class="regular-text" />
		</td>
	</tr>
	</table>
<?php endif; ?>

<?php
	$ig_boost_opted_in = get_user_meta( $user->ID, 'ig_boost_opted_in', true );
?>

<?php if( $ig_boost_opted_in == 'true' ) : ?>
	<?php
		$ig_boost_info = get_user_meta( $user->ID, 'ig_boost_info', true );

		if($ig_boost_info !== '' ) {

			$ig_boost_handle = $ig_boost_info['handle'];
			$ig_boost_hashtags = $ig_boost_info['hashtags'];
			$ig_boost_accounts = $ig_boost_info['accounts'];
			$ig_boost_reason = $ig_boost_info['reason'];
			$ig_boost_reach = $ig_boost_info['reach'];
			$ig_boost_engagement = $ig_boost_info['engagement'];

			$decrypt_pass = \Defuse\Crypto\Crypto::decrypt( $ig_boost_info['pass'], \Defuse\Crypto\Key::loadFromAsciiSafeString(SAS_ENCRYPT_KEY) );

		} else {

			$ig_boost_handle = '';
			$ig_boost_hashtags = '';
			$ig_boost_accounts = '';
			$ig_boost_reason = '';
			$ig_boost_reach = '';
			$ig_boost_engagement = '';
			$decrypt_pass = '';
		}
	?>
	<h2 id="ig-boost">Instagram Boost</h2>
	<table class="form-table">

	<tr>
		<th><label for="ig-boost-handle">Handle</label></th>
		<td>
			<input type="text" name="ig-boost-handle" id="ig-boost-handle" value="<?php echo esc_attr($ig_boost_handle); ?>" class="regular-text" />
		</td>
	</tr>
	<tr>
		<th><label for="ig-boost-hashtags">10 hashtags</label></th>
		<td>
			<input type="text" name="ig-boost-hashtags" id="ig-boost-hashtags" value="<?php echo esc_attr($ig_boost_hashtags); ?>" class="regular-text" />
		</td>
	</tr>
	<tr>
		<th><label for="ig-boost-accounts">10 accounts</label></th>
		<td>
			<input type="text" name="ig-boost-accounts" id="ig-boost-accounts" value="<?php echo esc_attr($ig_boost_accounts); ?>" class="regular-text" />
		</td>
	</tr>
	<tr>
		<th><label for="ig-boost-reason">Reason for opting in</label></th>
		<td>
			<textarea name="ig-boost-reason" id="ig-boost-reason"><?php echo esc_attr($ig_boost_reason); ?></textarea>
		</td>
	</tr>
	<tr>
		<th><label for="ig-boost-reach">Instagram Reach (at time of opting in)</label></th>
		<td>
			<input type="text" name="ig-boost-reach" id="ig-boost-reach" value="<?php echo esc_attr($ig_boost_reach); ?>" class="regular-text" />
		</td>
	</tr>
	<tr>
		<th><label for="ig-boost-engagement">Instagram Engagement Rate % (at time of opting in)</label></th>
		<td>
			<input type="text" name="ig-boost-engagement" id="ig-boost-engagement" value="<?php echo esc_attr($ig_boost_engagement); ?>" class="regular-text" />
		</td>
	</tr>
	<tr>
		<th><label for="ig-boost-password">Password</label></th>
		<td>
			<input type="text" name="ig-boost-password" id="ig-boost-password" value="<?php echo esc_attr($decrypt_pass); ?>" class="regular-text" />
		</td>
	</tr>
	</table>
<?php endif; ?>
<?php } // Function body ends

// Adding actions to show and edit the field
add_action( 'personal_options_update', 'save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_profile_fields' );
function save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	if($_POST['affiliate_opt_in']) {
		update_user_meta( $user_id, 'affiliate_status', 'opted_in');
	}

	update_usermeta( $user_id, 'facebook', $_POST['facebook'] );
	update_usermeta( $user_id, 'social_prism_user_facebook_reach', $_POST['social_prism_user_facebook_reach'] );
	update_usermeta( $user_id, 'referralcode', $_POST['referralcode'] );
	update_usermeta( $user_id, 'social_prism_user_gender', $_POST['social_prism_user_gender'] );
	update_usermeta( $user_id, 'social_prism_user_birthday', $_POST['social_prism_user_birthday'] );
	update_usermeta( $user_id, 'social_prism_user_age', $_POST['social_prism_user_age'] );
	update_usermeta( $user_id, 'social_prism_user_likes', $_POST['social_prism_user_likes'] );
	update_usermeta( $user_id, 'social_prism_user_location', $_POST['social_prism_user_location'] );
	update_usermeta( $user_id, 'social_prism_user_state_province', $_POST['social_prism_user_state_province'] );
	update_usermeta( $user_id, 'facebook-url', $_POST['facebook-url'] );
	update_usermeta( $user_id, 'social_prism_user_twitter', $_POST['social_prism_user_twitter'] );
	update_usermeta( $user_id, 'social_prism_user_twitter_reach', $_POST['social_prism_user_twitter_reach'] );
	update_usermeta( $user_id, 'twitter-full-name', $_POST['twitter-full-name'] );
	update_usermeta( $user_id, 'twitter-following', $_POST['twitter-following'] );
	update_usermeta( $user_id, 'twitter-bio', $_POST['twitter-bio'] );
	update_usermeta( $user_id, 'twitter-count', $_POST['twitter-count'] );
	update_usermeta( $user_id, 'twitter-url', $_POST['twitter-url'] );
	update_usermeta( $user_id, 'social_prism_user_instagram', $_POST['social_prism_user_instagram'] );
	update_usermeta( $user_id, 'social_prism_user_instagram_reach', $_POST['social_prism_user_instagram_reach'] );
	update_usermeta( $user_id, 'instagram-full-name', $_POST['instagram-full-name'] );
	update_usermeta( $user_id, 'instagram-bio', $_POST['instagram-bio'] );
	update_usermeta( $user_id, 'instagram-following', $_POST['instagram-following'] );
	update_usermeta( $user_id, 'instagram-url', $_POST['instagram-url'] );
	update_usermeta( $user_id, 'instagram-engagement-rate', $_POST['instagram-engagement-rate'] );
}


//login error override
add_filter('authenticate', 'custom_authenticate_username_password', 30, 3);
function custom_authenticate_username_password($user, $username, $password) {
	if ( is_a($user, 'WP_User') ) { return $user; }
	if ( empty($username) || empty($password) ) {
		$error = new WP_Error();
		if ( empty($username) )
			$error->add('empty_username', __('<strong>ERROR</strong>: The username field is empty.'));
		if ( empty($password) )
			$error->add('empty_password', __('<strong>ERROR</strong>: The password field is empty.'));
		return $error;
	}
	$userdata = get_user_by('login', $username);
	if ( !$userdata )
		return new WP_Error('invalid_username', sprintf(__('<strong>ERROR</strong>: We\'re sorry, that username does not exist. Try a different email. <a href="%s" title="Password Lost and Found">Lost your password?</a>'), wp_lostpassword_url()));
	if ( is_multisite() ) {
		// Is user marked as spam?
		if ( 1 == $userdata->spam)
			return new WP_Error('invalid_username', __('<strong>ERROR</strong>: Your account has been marked as a spammer.'));
		// Is a user's blog marked as spam?
		if ( !is_super_admin( $userdata->ID ) && isset($userdata->primary_blog) ) {
			$details = get_blog_details( $userdata->primary_blog );
			if ( is_object( $details ) && $details->spam == 1 )
				return new WP_Error('blog_suspended', __('Site Suspended.'));
		}
	}
	$userdata = apply_filters('wp_authenticate_user', $userdata, $password);
	if ( is_wp_error($userdata) )
		return $userdata;
	if ( !wp_check_password($password, $userdata->user_pass, $userdata->ID) )
		return new WP_Error( 'incorrect_password', sprintf( __( '<strong>ERROR</strong>: We found the username but the password is incorrect, please try again or reset your password here. <a href="%2$s" title="Password Lost and Found">Lost your password?</a>' ),
		$username, wp_lostpassword_url() ) );
	$user =  new WP_User($userdata->ID);
	return $user;
}
add_filter( 'woocommerce_order_button_text', 'woo_custom_order_button_text' );
function woo_custom_order_button_text() {
	return __( 'Opt In', 'woocommerce' );
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_single_add_to_cart_text' );  // 2.1 +

function woo_custom_single_add_to_cart_text() {

	return 'Opt In';

}

// Adding custom fields meta data for each new column
add_action( 'manage_shop_order_posts_custom_column' , 'custom_orders_list_column_content', 20, 2 );
function custom_orders_list_column_content( $column, $post_id )
{
	$order = wc_get_order( $post_id );
	$items = $order->get_items();
	$product_id='';
	$product_name='';
	$brand_name='';

	foreach ( $items as $item ) {

		$product_id= $item->get_product_id();
		$product_name=$item->get_name();
	}

	$terms = get_the_terms( $product_id, 'product_brand' );

	if ( $terms && ! is_wp_error( $terms ) ) :

		$prdcount=1;

		foreach ( $terms as $term ) {

			if( $prdcount>1) {

				$brand_name.=', ';
			}

			$brand_name.= $term->name;
			$prdcount++;
		}
	endif;

	switch ( $column ) {

		case 'brand-name' :
			// Get custom post meta data
			echo $brand_name;
			break;
		case 'product-name' :
			// Get custom post meta data
			echo $product_name;
			break;
	}
}


//filter for product on shop order
add_action( 'restrict_manage_posts', 'foa_product_filter_in_order', 10,2 );
add_filter( 'posts_where' , 'foa_product_filter_where' );
// Display dropdown
function foa_product_filter_in_order(){
	global $typenow, $wpdb;
	if ( 'shop_order' != $typenow ) {
		return;
	}
	$sql="SELECT ID,post_title FROM $wpdb->posts WHERE post_type = 'product' AND post_status = 'publish'";
	$all_posts = $wpdb->get_results($sql, ARRAY_A);
	$values = array();
	foreach ($all_posts as $all_post) {
		$values[$all_post['post_title']] = $all_post['ID'];
	}
	?>
	<select name="foa_order_product_filter">
	<option value=""><?php _e('All products', 'foa'); ?></option>
	<?php
		$current_v = isset($_GET['foa_order_product_filter'])? $_GET['foa_order_product_filter']:'';
		foreach ($values as $label => $value) {
			printf
				(
					'<option value="%s"%s>%s</option>',
					$value,
					$value == $current_v? ' selected="selected"':'',
					$label
				);
			}
	?>
	</select>
	<?php
}
// modify where clause in query
function foa_product_filter_where( $where ) {
	if( is_search() ) {
		global $wpdb;
		$t_posts = $wpdb->posts;
		$t_order_items = $wpdb->prefix . "woocommerce_order_items";
		$t_order_itemmeta = $wpdb->prefix . "woocommerce_order_itemmeta";
		if ( isset( $_GET['foa_order_product_filter'] ) && !empty( $_GET['foa_order_product_filter'] ) ) {
			$product = $_GET['foa_order_product_filter'];
			$where .= " AND $product = (SELECT $t_order_itemmeta.meta_value FROM $t_order_items LEFT JOIN $t_order_itemmeta on $t_order_itemmeta.order_item_id=$t_order_items.order_item_id WHERE $t_order_items.order_item_type='line_item' AND $t_order_itemmeta.meta_key='_product_id' AND $t_posts.ID=$t_order_items.order_id)";
		}
	}
	return $where;
}
//daily report xls
function order_daily_report_func( $pre_day , $subject , $file_name){
$attachment  = "<table border='1'>
				<tr>
				    <th>First Name</th>
				    <th>Last Name</th>
				    <th>Email</th>
				    <th>Birthday</th>
				    <th>Shipping Address</th>
				    <th>City</th>
				    <th>State/Province</th>
				    <th>Country</th>
				    <th>Zip / Postal Code</th>
				    <th>Notes</th>
				    <th>Instagram Handle</th>
				    <th>Instagram Followers</th>
				    <th>Instagram Engagement %</th>
				    <th>Facebook Handle</th>
				    <th>Facebook Followers/Likes</th>
				    <th>Twitter Handle</th>
				    <th>Twitter Followers</th>
				    <th>Last Product Bought</th>
				    <th>Brand</th>
				    <th>Last Order #</th>
				    <th>Last Order Date</th>
				    <th>Last Order FulFillment Status</th>
				</tr>";
				$customers = get_users();
//print_r($customers);
foreach( $customers as $customer )
{
	$customer_last_order= wc_get_customer_last_order( $customer->ID );

	if( $customer_last_order->id!='' && strtotime($customer_last_order->date_created)>=strtotime($pre_day) ){
		$product_name='';
		$product_order_id='';
		$order = wc_get_order( $customer_last_order->id );
		$items = $order->get_items();
		foreach ( $items as $item ) {
			$product_name = $item->get_name();
			$product_order_id = $item->get_product_id();
		}

		if( get_user_meta($customer->ID,'shipping_address_1',true) ){
			$shipping_address=get_user_meta($customer->ID,'shipping_address_1',true);
		}
		else{
			$shipping_address=get_user_meta($customer->ID,'billing_address_1',true);
		}
		if( get_user_meta($customer->ID,'shipping_city',true) ){
			$shipping_city=get_user_meta($customer->ID,'shipping_city',true);
		}
		else{
			$shipping_city=get_user_meta($customer->ID,'billing_city',true);
		}
		if( get_user_meta($customer->ID,'shipping_state',true) ){
			$shipping_state=get_user_meta($customer->ID,'shipping_state',true);
		}
		else{
			$shipping_state=get_user_meta($customer->ID,'billing_state',true);
		}
		if( get_user_meta($customer->ID,'shipping_country',true) ){
			$shipping_country=get_user_meta($customer->ID,'shipping_country',true);
		}
		else{
			$shipping_country=get_user_meta($customer->ID,'billing_country',true);
		}
		if( get_user_meta($customer->ID,'shipping_postcode',true) ){
			$shipping_postcode=get_user_meta($customer->ID,'shipping_postcode',true);
		}
		else{
			$shipping_postcode=get_user_meta($customer->ID,'billing_postcode',true);
		}
		$user_birthday_final='';
		$user_birthday =get_user_meta($customer->ID,'social_prism_user_birthday',true);
				if(  is_scalar($user_birthday) && ($user_birthday!='[object Object]') ){
							$user_birthday_final=$user_birthday ;
					}
		$terms_brand = get_the_terms( $product_order_id, 'product_brand' );
			//get_brands
			$brands_name ='';
			if ( $terms_brand ) :
				$b_count=0;
				foreach ( $terms_brand as $b_name ) {
					if( $b_count > 0 ){
							$brands_name .= ", ";
						}
					$brands_name .= $b_name->name;
					$b_count++;
				}
				endif;
		$attachment.='<tr>
						<td>'.get_user_meta($customer->ID,'first_name',true).'</td>
						<td>'.get_user_meta($customer->ID,'last_name',true).'</td>
						<td>'.get_user_by('id', $customer->ID)->user_email.'</td>
						<td>'.$user_birthday_final.'</td>
						<td>'.$shipping_address.'</td>
						<td>'.$shipping_city.'</td>
						<td>'.$shipping_state.'</td>
						<td>'.$shipping_country.'</td>
						<td>'.$shipping_postcode.'</td>
						<td>'.$customer_last_order->customer_note .'</td>
						<td>'.get_user_meta($customer->ID,'social_prism_user_instagram',true).'</td>
						<td>'.get_user_meta($customer->ID,'social_prism_user_instagram_reach',true).'</td>
						<td>'.get_user_meta($customer->ID,'instagram-engagement-rate',true).'</td>
						<td>'.get_user_meta($customer->ID,'facebook',true).'</td>
						<td>'.get_user_meta($customer->ID,'social_prism_user_facebook_reach',true).'</td>
						<td>'.get_user_meta($customer->ID,'social_prism_user_twitter',true).'</td>
						<td>'.get_user_meta($customer->ID,'social_prism_user_twitter_reach',true).'</td>
						<td>'.$product_name.'</td>
						<td>'.$brands_name.'</td>
						<td>'.$customer_last_order->id.'</td>
						<td>'.$customer_last_order->date_created.'</td>
						<td>'.$customer_last_order->status.'</td>

			</tr>';

		}
}
$attachment.="</table>";

$to = 'influencers@shopandshout.com';

//create a boundary string. It must be unique
//so we use the MD5 algorithm to generate a random hash
$random_hash = md5(date('r', time()));
//add boundary string and mime type specification
$headers = "From: influencers@shopandshout.com";

$headers.="cc: dev@shopandshout.com";

$headers.= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";

//define the body of the message.
ob_start(); //Turn on output buffering
?>
--PHP-mixed-<?php echo $random_hash; ?>
Content-Type: multipart/alternative; boundary="PHP-alt-<?php echo $random_hash; ?>"

--PHP-alt-<?php echo $random_hash; ?>
Content-Type: text/plain; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

--PHP-mixed-<?php echo $random_hash; ?>
Content-Type: application/ms-excel; name="<?php echo $file_name;?>-<?php echo date('Y-m-d'); ?>.xls"
Content-Disposition: attachment

<?php echo $attachment;
//copy current buffer contents into $message variable and delete current output buffer
$message = ob_get_clean();

//send the email
$mail_sent = @mail( $to, $subject, $message, $headers );

}
function daily_influencer_function() {
	$pre_day= date('Y-m-d H:i:s', strtotime(' -1 day'));
	$subject = 'Daily Influencer Report';
	$file_name='Daily-Influencer-Report';
	order_daily_report_func( $pre_day , $subject , $file_name );
}
 add_filter( 'cron_schedules', 'influencer_add_weekly' );

 function influencer_add_weekly( $schedules ) {
 	$schedules['weekly'] = array(
 		'interval' => 604800, // 604800 seconds = 1 week
 		'display' => __( 'Once Weekly' )
 	);
 	return $schedules;
 }

//going to use the strtotime function, so a good habit to get into is to set the PHP timezone to UTC
//date_default_timezone_set('UTC');
//define a timestamp to run this the first time.  4:20 is as good a time as any.
$timestamp = strtotime( '14:00:00' );
$timestamp_weekly = strtotime( '2018-07-27 19:00:00' );
//set a recurrence interval - WP has three default intervals: hourly, daily & twicedaily
$recurrence = 'daily';
$recurrence_weekly = 'weekly';
//define the hook to run
$hook = 'daily_influencer';
$hook_weekly = 'weekly_influencer';
//you can pass arguments to the hooked function if need be.  this parameter is optional:
$args1 = NULL;
//the following will run your function starting at the time defined by timestamp and recurring every $recurrence
if (! wp_next_scheduled ( 'daily_influencer' )) {
	wp_schedule_event( $timestamp, $recurrence, $hook );
}

add_action( 'daily_influencer', 'daily_influencer_function' );

function weekly_influencer_function() {
	$pre_day= date('Y-m-d H:i:s', strtotime(' -7 day'));
	$subject = 'Weekly Influencer Report';
	$file_name='Weekly-Influencer-Report';
	order_daily_report_func( $pre_day , $subject , $file_name );
}

if (! wp_next_scheduled ( 'weekly_influencer' )) {
	wp_schedule_event( $timestamp_weekly, $recurrence_weekly, $hook_weekly );
}
add_action( 'weekly_influencer', 'weekly_influencer_function' );
//download report form settings
add_action( 'admin_menu', 'my_plugin_menu' );

function my_plugin_menu() {
	add_options_page(
		'Download Report',
		'Download Report',
		'manage_options',
		'download_report.php',
		'download_report'
	);
}
function download_report(){
	?>
	<h1>Downlaod Report</h1>
	<form action="" method="post">
		<h3>Master Sheet Report</h3>
		<label>Email</label>
		<input type="email" name="email_for_report">
		<input type="submit" id="daily_report" name="daily_report" value="Send Email">
	</form><br>
	<?php
		if( isset($_POST['daily_report']) ){
		     $subject = 'Master Sheet Influencer Report';
		     $file_name='Master-Sheet-Influencer-Report';
		     $email_for_report=$_POST['email_for_report'];
			download_report_xls( $subject , $file_name, $email_for_report);
		}
}

function download_report_xls( $subject , $file_name , $email_for_report ){
$attachment  = "<table border='1'>
				<tr>
				    <th>First Name</th>
				    <th>Last Name</th>
				    <th>Email</th>
				    <th>Birthday</th>
				    <th>Shipping Address</th>
				    <th>City</th>
				    <th>State/Province</th>
				    <th>Country</th>
				    <th>Zip / Postal Code</th>
				    <th>Notes</th>
				    <th>Instagram Handle</th>
				    <th>Instagram Followers</th>
				    <th>Instagram Engagement %</th>
				    <th>Facebook Handle</th>
				    <th>Facebook Followers/Likes</th>
				    <th>Twitter Handle</th>
				    <th>Twitter Followers</th>
				    <th>Last Product Bought</th>
				    <th>Brand</th>
				    <th>Last Order #</th>
				    <th>Last Order Date</th>
				    <th>Last Order FulFillment Status</th>
				</tr>";
$customers = get_users();
$args = array(
	'post_type'         => 'shop_order',
	'posts_per_page'    => -1,
	'post_status'    => 'any'
	);

$customers_order = get_posts($args);

foreach( $customers_order as $customer_order )
{
		$product_name='';
		$product_order_id='';
		$order = wc_get_order( $customer_order->ID );
		$customer_id=  $user_id = get_post_meta($customer_order->ID, '_customer_user', true);
		$items = $order->get_items();
		foreach ( $items as $item ) {
			$product_name = $item->get_name();
			$product_order_id = $item->get_product_id();
		}

		if( get_user_meta($customer_id,'shipping_address_1',true) ){
			$shipping_address=get_user_meta($customer_id,'shipping_address_1',true);
		}
		else{
			$shipping_address=get_user_meta($customer_id,'billing_address_1',true);
		}
		if( get_user_meta($customer_id,'shipping_city',true) ){
			$shipping_city=get_user_meta($customer_id,'shipping_city',true);
		}
		else{
			$shipping_city=get_user_meta($customer_id,'billing_city',true);
		}
		if( get_user_meta($customer_id,'shipping_state',true) ){
			$shipping_state=get_user_meta($customer_id,'shipping_state',true);
		}
		else{
			$shipping_state=get_user_meta($customer_id,'billing_state',true);
		}
		if( get_user_meta($customer_id,'shipping_country',true) ){
			$shipping_country=get_user_meta($customer_id,'shipping_country',true);
		}
		else{
			$shipping_country=get_user_meta($customer_id,'billing_country',true);
		}
		if( get_user_meta($customer_id,'shipping_postcode',true) ){
			$shipping_postcode=get_user_meta($customer_id,'shipping_postcode',true);
		}
		else{
			$shipping_postcode=get_user_meta($customer_id,'billing_postcode',true);
		}
		$user_birthday_final='';
		$user_birthday =get_user_meta($customer_id,'social_prism_user_birthday',true);
				if(  is_scalar($user_birthday) && ($user_birthday!='[object Object]') ){
							$user_birthday_final=$user_birthday ;
					}
		$terms_brand = get_the_terms( $product_order_id, 'product_brand' );
			//get_brands
			$brands_name ='';
			if ( $terms_brand ) :
				$b_count=0;
				foreach ( $terms_brand as $b_name ) {
					if( $b_count > 0 ){
							$brands_name .= ", ";
						}
					$brands_name .= $b_name->name;
					$b_count++;
				}
				endif;
				$order_stauts=str_replace('wc-','',$customer_order->post_status);
				$order_stauts=str_replace('-',' ',$order_stauts);
		$attachment.='<tr>
						<td>'.get_user_meta($customer_id,'first_name',true).'</td>
						<td>'.get_user_meta($customer_id,'last_name',true).'</td>
						<td>'.get_user_by('id', $customer_id)->user_email.'</td>
						<td>'.$user_birthday_final.'</td>
						<td>'.$shipping_address.'</td>
						<td>'.$shipping_city.'</td>
						<td>'.$shipping_state.'</td>
						<td>'.$shipping_country.'</td>
						<td>'.$shipping_postcode.'</td>
						<td>'.$order->customer_note.'</td>
						<td>'.get_user_meta($customer_id,'social_prism_user_instagram',true).'</td>
						<td>'.get_user_meta($customer_id,'social_prism_user_instagram_reach',true).'</td>
						<td>'.get_user_meta($customer_id,'instagram-engagement-rate',true).'</td>
						<td>'.get_user_meta($customer_id,'facebook',true).'</td>
						<td>'.get_user_meta($customer_id,'social_prism_user_facebook_reach',true).'</td>
						<td>'.get_user_meta($customer_id,'social_prism_user_twitter',true).'</td>
						<td>'.get_user_meta($customer_id,'social_prism_user_twitter_reach',true).'</td>
						<td>'.$product_name.'</td>
						<td>'.$brands_name.'</td>
						<td>'.$customer_order->ID.'</td>
						<td>'.$customer_order->post_date.'</td>
						<td>'.$order_stauts.'</td>
			</tr>';
	}
	$attachment.="</table>";
$to = $email_for_report;//'influencers@shopandshout.com';
$random_hash = md5(date('r', time()));
//add boundary string and mime type specification
$headers = "From: influencers@shopandshout.com";
$headers .= "cc: dev@shopandshout.com";
$headers.= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";

//define the body of the message.
ob_start(); //Turn on output buffering
?>
--PHP-mixed-<?php echo $random_hash; ?>
Content-Type: multipart/alternative; boundary="PHP-alt-<?php echo $random_hash; ?>"

--PHP-alt-<?php echo $random_hash; ?>
Content-Type: text/plain; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

--PHP-mixed-<?php echo $random_hash; ?>
Content-Type: application/ms-excel; name="<?php echo $file_name;?>-<?php echo date('Y-m-d'); ?>.xls"
Content-Disposition: attachment

<?php echo $attachment;
//copy current buffer contents into $message variable and delete current output buffer
$message = ob_get_clean();

//send the email
$mail_sent = @mail( $to, $subject, $message, $headers );
	if( $mail_sent ){
		echo "Email sent successfully";
	}
}
/**
 *	Authentique daily score request
 */
add_action( 'authentique_score_request', 'authentique_audit_request_daily' );
function authentique_audit_request_daily() {

    include_once('api/authentique.php');

    $args = array(
        'meta_key' => 'social_prism_user_instagram',
        'meta_compare' => 'EXISTS',
    );

    $users = get_users($args);

	$count = 0;

    foreach( $users as $user ) {

    	$count++;

    	if( $count % 50 == 0 ){
    		error_log('Authentique: made it to ' . $count);
    	}

    	$uid = $user->ID;
    	$username = get_user_meta( $uid, 'social_prism_user_instagram', true );
    	$authentique_account_not_found = get_user_meta( $uid, 'authentique_account_not_found', true );

    	if( $username && !$authentique_account_not_found ) {

	    	$authentique_audit_connected = get_user_meta( $uid, 'authentique_audit_connected', true );
	    	$authentique_audit_completed = get_user_meta( $uid, 'authentique_audit_completed', true );

	    	if( $authentique_audit_completed ) {

	    	} else if( $authentique_audit_connected ) {

		    	$authentique_data = get_audit_progress_or_result($username);

		    	if( isset($authentique_data['error_code']) ) {

		    		if( $authentique_data['error_code'] == 'audit_not_found_username' ) {

				    	$result = create_influencer_audit( $username );

				    	if( $result['success'] ) {

				    		update_user_meta( $uid, 'authentique_audit_connected', 'true' );
				    		update_user_meta( $uid, 'authentique_account_private', false );
				    		update_user_meta( $uid, 'authentique_account_no_followers', false );

				    	} else {

				    		if( $result['error_code'] == 'account_private' ) {

				    			update_user_meta( $uid, 'authentique_account_private', true );

				    		} else if ( $result['error_code'] == 'account_not_found' ) {

				    			update_user_meta( $uid, 'authentique_account_not_found', true );

				    		} else if( $result['error_code'] == 'account_no_followers' ) {

				    			update_user_meta( $uid, 'authentique_account_no_followers', true );

				    		} else {

		    					update_user_meta( $uid, 'authentique_unknown_error', implode(', ', $result) );
				    		}
				    	}

		    		} else if( $authentique_data['error_code'] == 'account_private' ) {

		    			update_user_meta( $uid, 'authentique_account_private', true );

		    		} else {

    					update_user_meta( $uid, 'authentique_unknown_error', implode(', ', (array)$authentique_data['error']) );
		    		}

		    	} else if( isset($authentique_data['success']) && !$authentique_data['result']['is_cancelled'] ) {

		    		$estimated_real_follower_percentage = $authentique_data['result']['estimated_real_follower_percentage'];
		    		$media_posts = $authentique_data['result']['media_posts'];
		    		$average_likes = $authentique_data['result']['average_likes'];
		    		$average_comments = $authentique_data['result']['average_comments'];
		    		$expected_likes = $authentique_data['result']['expected_likes'];
		    		$expected_comments = $authentique_data['result']['expected_comments'];
		    		$estimated_follower_average_posts = $authentique_data['result']['estimated_follower_average_posts'];
		    		$estimated_follower_average_followers = $authentique_data['result']['estimated_follower_average_followers'];
		    		$estimated_follower_average_following = $authentique_data['result']['estimated_follower_average_following'];
		    		$estimated_private_follower_percentage = $authentique_data['result']['estimated_follower_average_following'];
		    		$estimated_follower_engagement_rate_likes = $authentique_data['result']['estimated_follower_engagement_rate_likes'];
		    		$estimated_follower_engagement_rate_comments = $authentique_data['result']['estimated_follower_engagement_rate_comments'];
		    		$estimated_engagement_rate_percentage = $authentique_data['result']['estimated_engagement_rate_percentage'];
		    		$audit_progress_percentage = $authentique_data['result']['audit_progress_percentage'];

		    		if( $authentique_data['result']['is_completed'] ) {

		    			update_user_meta( $uid, 'authentique_audit_completed', 'true' );

		    		} else {

		    			update_user_meta( $uid, 'authentique_audit_completed', false );
		    		}

		    		update_user_meta( $uid, 'authentique_estimated_real_follower_percentage', $estimated_real_follower_percentage );
		    		update_user_meta( $uid, 'authentique_media_posts', $media_posts );
		    		update_user_meta( $uid, 'authentique_average_likes', $average_likes );
		    		update_user_meta( $uid, 'authentique_average_comments', $average_comments );
		    		update_user_meta( $uid, 'authentique_expected_likes', $expected_likes );
		    		update_user_meta( $uid, 'authentique_expected_comments', $expected_comments );
		    		update_user_meta( $uid, 'authentique_estimated_follower_average_posts', $estimated_follower_average_posts );
		    		update_user_meta( $uid, 'authentique_estimated_follower_average_followers', $estimated_follower_average_followers );
		    		update_user_meta( $uid, 'authentique_estimated_follower_average_following', $estimated_follower_average_following );
		    		update_user_meta( $uid, 'authentique_estimated_private_follower_percentage', $estimated_private_follower_percentage );
		    		update_user_meta( $uid, 'authentique_estimated_follower_engagement_rate_likes', $estimated_follower_engagement_rate_likes );
		    		update_user_meta( $uid, 'authentique_estimated_follower_engagement_rate_comments', $estimated_follower_engagement_rate_comments );
		    		update_user_meta( $uid, 'authentique_estimated_engagement_rate_percentage', $estimated_engagement_rate_percentage );
		    		update_user_meta( $uid, 'authentique_audit_progress_percentage', $audit_progress_percentage );
		    	}
		    } else {

		    	$result = create_influencer_audit( $username );

		    	if( $result['success'] ) {

		    		update_user_meta( $uid, 'authentique_audit_connected', 'true' );
		    		update_user_meta( $uid, 'authentique_account_private', false );
		    		update_user_meta( $uid, 'authentique_account_no_followers', false );

		    	} else {

		    		if( $result['error_code'] == 'account_private' ) {

		    			update_user_meta( $uid, 'authentique_account_private', true );

		    		} else if ( $result['error_code'] == 'account_not_found' ) {

		    			update_user_meta( $uid, 'authentique_account_not_found', true );

		    		} else if( $result['error_code'] == 'account_no_followers' ) {

		    			update_user_meta( $uid, 'authentique_account_no_followers', true );

		    		} else {

		    			update_user_meta( $uid, 'authentique_unknown_error', implode(', ', $result) );
		    		}
		    	}
		    }
		}
    }
}

/**
 *	Hubspot sync extra properties
 */
add_filter( 'hubwoo_map_new_properties', 'hubspot_extra_properties', 10, 2 );
function hubspot_extra_properties( $properties, $ID ) {

	// Collecting Data
	// Facebook
	$facebook_id = get_user_meta($ID, 'inf_facebook_id', true);

	// Affiliate/Ambassador
	$referred_by = get_user_meta($ID, 'referred_by', true);
	$referred_by_email = '';
	$referred_by_link = '';
	$referred_by_user = '';
	if($referred_by) {
		$link_id = get_post($referred_by)->ID;
		$owner_id = get_post_meta($link_id, 'owner_id', true);
		$referred_by_link = get_post_meta($link_id, 'param_string', true);
		$referred_by_user = get_userdata($owner_id)->user_email;
	}

	$affiliate_info_completed = get_user_meta($ID, 'affiliate_info_completed', true);
	$first_affiliate_signup = 'false';
	$first_affiliate_shoutout = 'false';

	if($affiliate_info_completed) {
		$links = get_posts(array(
			'post_type' => 'affiliate_link',
			'meta_key' => 'owner_id',
			'meta_value' => $ID,
		));

		$link = is_array($links) ? end($links) : '';

		if($link) {
			$link_data = get_affiliate_link_data($link->ID, 0, date('Y-m-d H:i:s'));

			$first_ambassador_signup = count($link_data['signups']) > 0 ? 'true' : 'false';
			$first_ambassador_shoutout = count($link_data['shoutouts']) > 0 ? 'true' : 'false';
		}
	}

	// Setting User Data
	// Facebook
	$properties[] = array(
		'property' => 'facebook_id',
		'value' => $facebook_id,
	);

	// Affiliate/Ambassador
	$properties[] = array(
		'property' => 'referred_by_link',
		'value' => $referred_by_link,
	);
	$properties[] = array(
		'property' => 'referred_by_user',
		'value' => $referred_by_user,
	);
	$properties[] = array(
		'property' => 'ambassador_info_completed',
		'value' => $affiliate_info_completed ? 'true' : 'false',
	);
	$properties[] = array(
		'property' => 'first_ambassador_signup',
		'value' => $first_affiliate_signup,
	);
	$properties[] = array(
		'property' => 'first_ambassador_shoutout',
		'value' => $first_affiliate_shoutout,
	);

	// Order Data
  $last_order = wc_get_customer_last_order($ID);

	if( $last_order ) {

		$items = $last_order->get_items();
		$first_item = reset($items);
		$item_data = $first_item->get_data();
		$product_id = $item_data['product_id'];

		$campaign_categories = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'slugs' ) );
		$campaign_strategy = get_post_meta($product_id, 'campaign_strategy', true);
		$prize_description = get_post_meta($product_id, 'prize_description', true);

		$influencer_id = $last_order->get_data()['customer_id'];
		$score_data = influencer_get_score_data( $influencer_id );
		$elite_qualification = influencer_elite_qualification_check( $influencer_id );

		$brand_name = get_post_meta( $product_id, 'brand_name', true );
		$channels = get_post_meta( $product_id, 'shoutout_channels', true );
		$visuals = get_post_meta( $product_id, 'visuals', true );
		$photo_tags = get_post_meta( $product_id, 'brand_name', true );
		$caption = get_post_meta( $product_id, 'caption', true );
		$hashtags = get_post_meta( $product_id, 'hashtags', true );
		$timeline = get_post_meta( $product_id, 'timeline', true );
		$instructions = array();
		$instructions_count = get_post_meta( $product_id, 'instructions', true );

		if( $instructions_count ) {

			for( $i=0; $i<$instructions_count; $i++ ) {

				$meta_key = 'instructions_' . $i . '_steps';
				$step = get_post_meta( $product_id, $meta_key, true);

				if( $step != '' && $i > 1 ) {

					$instructions[] = 'Step ' . ($i - 1) . '. ' . $step;
				}
			}
		}

		// Extra Campaign Data
		$properties[] = array(
			'property' => 'last_product_bought_brand_name',
			'value' => $brand_name,
		);
		$properties[] = array(
			'property' => 'last_product_bought_channels',
			'value' => implode(';',$channels),
		);
		$properties[] = array(
			'property' => 'last_product_bought_campaign_strategy',
			'value' => $campaign_strategy,
		);
		$properties[] = array(
			'property' => 'last_product_bought_prize_description',
			'value' => $prize_description,
		);
		$properties[] = array(
			'property' => 'last_product_bought_visuals',
			'value' => $visuals,
		);
		$properties[] = array(
			'property' => 'last_product_bought_photo_tags',
			'value' => $photo_tags,
		);
		$properties[] = array(
			'property' => 'last_product_bought_caption',
			'value' => $caption,
		);
		$properties[] = array(
			'property' => 'last_product_bought_hashtags',
			'value' => $hashtags,
		);
		$properties[] = array(
			'property' => 'last_product_bought_timeline',
			'value' => $timeline,
		);
		$properties[] = array(
			'property' => 'last_product_bought_instructions',
			'value' => implode('</br>',$instructions),
		);
		$properties[] = array(
			'property' => 'last_shoutout_order_categories',
			'value' => $campaign_categories,
		);

		// Extra Influencer Data
		$properties[] = array(
			'property' => 'elite_qualification',
			'value' => $elite_qualification,
		);
		$properties[] = array(
			'property' => 'average_shoutout_score',
			'value' => $score_data['average_score'],
		);
	}

	return $properties;
}

add_filter('acf/load_field/name=region', 'acf_load_region_field_choices');
function acf_load_region_field_choices( $field ) {

	if(isset($_GET['post'])) {

		// get post id
		$pid = $_GET['post'];

    // reset choices
    $field['choices'] = array();

    $countries_include_exclude = get_field('countries_include_exclude', $pid);
    $countries = get_field('country', $pid);

    $field['choices']['value_1'] = 'Label 1';
 	}

    return $field;
}

// Products Columns
add_filter( 'manage_edit-product_columns', 'additional_product_columns',15 );
function additional_product_columns($columns){
   $columns['brand'] = __( 'Brand'); 
   $columns['author'] = __('Author');
   return $columns;
}
add_action( 'manage_product_posts_custom_column', 'manage_products_columns', 10, 2 );
function manage_products_columns( $column, $campaign_id ) {
  if ($column == 'brand') {
  	$brand = get_campaign_brand($campaign_id);
    echo $brand->brand_name;
  }
}