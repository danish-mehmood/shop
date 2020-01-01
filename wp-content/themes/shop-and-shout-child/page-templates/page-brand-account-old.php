<?php

get_header();

$uid = '';
$first_name = '';
$last_name = '';
$email = '';
$phone = '';

if( is_user_logged_in() ) {

	$user = wp_get_current_user();

	if( in_array('administrator',$user->roles) || in_array('brand',$user->roles) ) {
		// Account Info
		$uid = $user->ID;
		$first_name = $user->first_name;
		$last_name = $user->last_name;
		$email = $user->user_email;
		$phone = get_user_meta($uid, 'contact_phone', true);

	} else {
		wp_redirect(get_site_url() . '/my-account');
	}

} else {
	wp_redirect(get_site_url());
}

$brand_user_entries = get_brand_user_entries_by_user_id($uid);

$brands = array();

foreach( $brand_user_entries as $entry ) {

	$brand_status = get_post_status($entry['brand']);

	if ($brand_status == 'publish') {

		$brand_name = get_the_title($entry['brand']);

		$brands[] = array(
			'brand_id' => $entry['brand'],
			'brand_name' => $brand_name
		);
	}
}
?>
<div id="main-content">
	<div class="brand-account-wrapper">
		<h1>Brand Dashboard</h1>

		<div id="brand-account-tab-wrapper" class="sas-tabs-container">
			<ul class="tab-nav">
				<li><a class="tab-anchor" data-tab="campaigns" selected="selected">Campaigns</a></li>
				<li><a class="tab-anchor" data-tab="brand">Brand</a></li>
				<li><a class="tab-anchor" data-tab="account">Account</a></li>
			</ul>
			<div class="tab-pane">
				<div id="campaigns" class="tab">

					<?php if(count($brands) > 1) :?>

						<h2>Your Campaigns</h2>
	       				<a class="sas-round-button-primary" href="<?php echo get_site_url() . '/design-campaign/?b=' . $brands[0]['brand_id']; ?>">Design a new ShoutOut Campaign</a><br><br>

						<label>Brand</label><br>
						<select id="brand-account-campaign-brands-select" class="ajax-select">
							<?php foreach($brands as $brand) : ?>

							<option value="<?php echo esc_attr($brand['brand_id']) ?>">
								<?php echo $brand['brand_name']; ?>
							</option>

							<?php endforeach; ?>
						</select>

						<br><br>

					<?php else : ?>

						<select id="brand-account-campaign-brands-select" class="ajax-select" style="display: none;">
							<option value="<?php echo esc_attr($brands[0]['brand_id']) ?>" selected></option>
						</select>

						<h2><?php echo esc_html($brands[0]['brand_name']); ?> Campaigns</h2>

	       				<a class="sas-round-button-primary" href="<?php echo get_site_url() . '/design-campaign/?b=' . $brands[0]['brand_id']; ?>">Design a new ShoutOut Campaign</a><br><br><br>

					<?php endif; ?>

					<label>Campaigns</label><br>
					<select id="brand-account-campaign-select" class="ajax-select">
						<option style="color: #aaa;">Loading...</option>
					</select>

      				<div class="edit-campaign-container">
      					<button style="display: none;" class="sas-mini-form-button" data-campaign-status="<?php echo $campaign->post_status ?>" data-brand="" data-campaign="" data-edit-campaign-url="<?php echo get_site_url() . '/design-campaign/' ?>">Edit Campaign</button>
      				</div>

					<div id="brand-account-campaign-info-container" class="ajax-info-container" style="display: none;"></div>


					<div class="brand-account-campaign-info-placeholder ajax-info-placeholder">
						<span>Loading <i class="fa fa-spinner fa-spin"></i></span>
					</div>
				</div>

				<div id="brand" class="tab" style="display: none;">

					<?php if(count($brands) > 1) : ?>

						<h2>Brands</h2>

						<select id="brand-account-brands-select" class="ajax-select">
							<?php foreach($brands as $brand) : ?>
							<option value="<?php echo esc_attr($brand['brand_id']) ?>"><?php echo $brand['brand_name']; ?></option>
							<?php endforeach; ?>
						</select>

					<?php else : ?>

						<select id="brand-account-brands-select" class="ajax-select" style="display: none;">
							<option value="<?php echo esc_attr($brands[0]['brand_id']) ?>" selected></option>
						</select>

					<?php endif; ?>

					<br><br><a class="sas-mini-form-button" href="<?php echo get_site_url() . '/add-brand/' ?>"><i class="fas fa-plus"></i> Create a new brand</a>

					<div id="brand-account-brand-info-container" class="ajax-info-container" style="display: none;"></div>

					<div class="brand-account-brand-info-placeholder ajax-info-placeholder">
						<span>Loading <i class="fa fa-spinner fa-spin"></i></span>
					</div>
				</div>

				<div id="account" class="tab" style="display: none;">
					<h2>Account</h2>
					<form id="brand-account-info-form" class="sas-form">

						<div class="form-errors"></div>

						<h3>Contact Info</h3>
						<div class="form-section">
							<div class="form-row">
								<div class="form-item">
									<label>Contact First Name</label>
									<input type="text" id="contact-first-name" value="<?php echo esc_attr($first_name); ?>">
								</div>
								<div class="form-item">
									<label>Contact Last Name</label>
									<input type="text" id="contact-last-name" value="<?php echo esc_attr($last_name); ?>">
								</div>
							</div>

							<div class="form-row">
								<div class="form-item">
									<label>Contact Email</label>
									<input type="text" name="contact_email" id="contact-email" value="<?php echo esc_attr($email); ?>">
								</div>
								<div class="form-item">
									<label>Contact Phone</label>
									<input type="text" name="contact_phone" id="contact-phone" value="<?php echo esc_attr($phone); ?>">
								</div>
							</div>
						</div>
						<?php wp_nonce_field('brand_account_info', 'brand_account_info_nonce'); ?>
						<button class="sas-round-button-primary" type="submit">Save</button>
					</form>
				</div>
			</div>
		</div>
	</div> <!-- .brand-account-container -->
</div> <!-- #main-content -->

<?php

get_footer();
