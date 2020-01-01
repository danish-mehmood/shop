<?php

get_header();

$uid = get_current_user_id();

// Personal
$first_name = get_user_meta($uid ,'first_name', true);
$last_name = get_user_meta($uid, 'last_name', true);
$birthdate = strtotime( get_user_meta( $uid, 'social_prism_user_birthday', true ) );
$birthdate_day = date( 'd', $birthdate );
$birthdate_month = date( 'm', $birthdate );
$birthdate_year = date( 'Y', $birthdate );
$phone = get_user_meta($uid, 'inf_phone', true);

// Shipping
$shipping_address_1 = get_user_meta($uid, 'shipping_address_1', true);
$shipping_address_2 = get_user_meta($uid, 'shipping_address_2', true);
$shipping_country = get_user_meta($uid, 'shipping_country', true);
$shipping_city = get_user_meta($uid, 'shipping_city', true);
$shipping_state = get_user_meta($uid, 'shipping_state', true);
$shipping_postcode = get_user_meta($uid, 'shipping_postcode', true);

?>
<div id="main-content" class="affiliate-payout-info-form-wrapper">
	<form id="affiliate-payout-info-form" class="sas-form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
		<div class="form-content">
			<h4>Please confirm that the information below is correct and fill out any missing information.</h4>
			<div class="error-message"></div>
			
			<div class="form-row">
				<div class="form-item">
					<label>First Name</label>
					<input type="text" id="first_name" name="first_name" value="<?php echo esc_attr($first_name); ?>" required>
				</div>
				<div class="form-item">
					<label>Last Name</label>
					<input type="text" id="last_name" name="last_name" value="<?php echo esc_attr($last_name); ?>" required>
				</div>
			</div>
			
			<div class="form-row">
				<div class="form-item">
					<label>Phone Number</label>
					<input type="text" id="phone" name="phone" name="phone" value="<?php echo esc_attr($phone); ?>" required>
				</div>
			</div>

			<div class="form-row">
		    	<div class="form-item">
	        		<label for="inf_birthday">Date of birth</label>
	        		<div class="form-row">
	        			<div class="form-item span-2">
				            <span>Month</span>
				            <select id="birthdate_month" name="birthdate_month">
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
			            <div class="form-item">
				            <span>Day</span>
				            <input type="text" id="birthdate_day" name="birthdate_day" value="<?php echo esc_attr($birthdate_day); ?>" placeholder="Day" required>
			            </div>
			            <div class="form-item">
			            	<span>Year</span>
			            	<input type="text" id="birthdate_year" name="birthdate_year" value="<?php echo esc_attr($birthdate_year); ?>" placeholder="Year" required>
			            </div>
	        		</div>
	        	</div>
	        </div>
			
			<div class="form-row">
				<div class="form-item">
					<label>Street Name & Number</label>
					<input type="text" id="shipping_address_1" name="shipping_address_1" value="<?php echo esc_attr($shipping_address_1); ?>" required>
				</div>
				<div class="form-item">
					<label>Suite #</label>
					<input type="text" id="shipping_address_2" name="shipping_address_2" value="<?php echo esc_attr($shipping_address_2); ?>">
				</div>
			</div>
			
			<div class="form-row">
				<div class="form-item">
					<label>Country</label>
					<input type="text" id="shipping_country" name="shipping_country" value="<?php echo esc_attr($shipping_country); ?>" required>
				</div>
				<div class="form-item">
					<label>State/Province</label>
					<input type="text" id="shipping_state" name="shipping_state" value="<?php echo esc_attr($shipping_state); ?>" required>
				</div>
			</div>
			
			<div class="form-row">
				<div class="form-item">
					<label>City</label>
					<input type="text" id="shipping_city" name="shipping_city" value="<?php echo esc_attr($shipping_city); ?>" required>
				</div>
				<div class="form-item">
					<label>Postal/Zip Code</label>
					<input type="text" id="shipping_postcode" name="shipping_postcode" value="<?php echo esc_attr($shipping_postcode); ?>" required>
				</div>
			</div>

			<button type="submit" class="sas-round-button-primary">Confirm</button>

			<?php wp_nonce_field( 'affiliate_campaign', 'affiliate_campaign_nonce' ); ?>

			<input type="hidden" name="action" value="affiliate_payout_information">

		</div>
		
		<div class="success-content">
			<h2>Information confirmed, thank you!</h2>
			<a href="<?php echo get_site_url() . '/my-account/ambassador' ?>">Back to dashboard</a>
		</div>
	</form>
</div> <!-- #main-content -->

<?php

get_footer();