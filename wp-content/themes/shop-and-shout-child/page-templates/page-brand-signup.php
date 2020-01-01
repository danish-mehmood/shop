<?php

get_header();

?>
<div id="main-content">
	<div id="brand-registration-form-wrapper">
		<?php if(!isset($_GET['invite'])) : ?>
			<div id="type-select">
				<h1>What type of account are you creating?</h1>
				<div class="featured-cards-wrapper cards-2">
					<div class="featured-card-container">
						<div class="featured-card button" id="brand">
							<div class="icon-container">
								<!-- <img src="<?php //echo get_stylesheet_directory_uri() . '/images/pages/young-stars/shop.svg'; ?>"> -->
							</div>
							<div class="body-container">
								<span>BRAND</span>
								<p>Select this if you are creating an account for an organization or company.</p>
							</div>
						</div>
					</div>
					<div class="featured-card-container">
						<div class="featured-card button" id="employee">
							<div class="icon-container">
								<!-- <img src="<?php //echo get_stylesheet_directory_uri() . '/images/pages/young-stars/shop.svg'; ?>"> -->
							</div>
							<div class="body-container">
								<span>EMPLOYEE</span>
								<p>Select this if you are joining an organization or business as a brand representative.</p>
							</div>
						</div>
					</div>
				</div>
			</div>

			<form id="brand-registration-form" class="sas-form" style="display: none;">
				<h2>Please enter your Brand's information below.</h2>

				<label><span class="form-required">*</span> = <b>Required</b></label>

				<br><br>

				<div class="form-errors"></div>

				<h3>Brand Info</h3>
				<div class="form-section">
					<div class="form-row">
						<div class="form-item">
							<label>Brand Name <span class="form-required">*</span></label>
							<input type="text" id="brand-name" required>
						</div>
						<div class="form-item">
							<label>Brand Website <span class="form-required">*</span></label>
							<input type="text" name="brand_website" id="brand-website" required>
						</div>
					</div>

					<div class="form-row">
						<div class="form-item">
				            <label for="story">Brand Story<br>(Tell us and our Influencers what you're all about.)<span class="form-required">*</span></label>
				            <textarea id="brand-story" rows=5 required></textarea>
						</div>	
					</div>
				</div>

				<h3>Contact Info</h3>
				<div class="form-section">
					<div class="form-row">
						<div class="form-item">
							<label>Contact First Name <span class="form-required">*</span></label>
							<input type="text" id="first-name" required>
						</div>
						<div class="form-item">
							<label>Contact Last Name <span class="form-required">*</span></label>
							<input type="text" id="last-name" required>
						</div>
					</div>

					<div class="form-row">
						<div class="form-item">
							<label>Contact Email <span class="form-required">*</span></label>
							<input type="text" name="email" id="email" required>
						</div>
						<div class="form-item">
							<label>Contact Phone <span class="form-required">*</span></label>
							<input type="text" name="phone" id="phone" required>
						</div>
					</div>

					<div class="form-row">
						<div class="form-item">
							<label>Password <span class="form-required">*</span></label>
							<input type="password" name="password_1" id="password" required>
						</div>
						<div class="form-item">
							<label>Confirm Password <span class="form-required">*</span></label>
							<input type="password" name="password_2" required>
						</div>
					</div>
				</div>
				<?php wp_nonce_field('brand_registration', 'brand_registration_nonce'); ?>
				<button class="sas-round-button-primary" type="submit">Submit</button>
			</form>

			<div id="brand-registration-success" style="display: none;">
				<h2>Thanks for signing up!</h2>
				<p>You can now go to <a href="<?php echo get_site_url() . '/brand-account' ?>">your brand dashboard</a> and get started by Designing your own ShoutOut Campaign</p>
			</div>

			<div id="employee-message" style="display: none;">
				<h2>Thanks for your interest!</h2>
				<p>In order to join as an employee, please request an invite from an admin of your organization. If your organization isn't registered yet, please go back and select "Brand ".</p>
				<span class="sas-text-button block" id="employee-message-close">Back</span>
			</div>
		<?php else : 
			$brand_user_entry = get_post(array(
				'post_type' => 'brand_user',
				'meta_key' => 'invite_string',
				'meta_value' => $_GET['invite'],
			));

		?>
			<?php if($brand_user_entry) : 

				$buid = $brand_user_entry->ID;
				$uid = get_post_meta($buid, 'user', true );
			?>
				<?php if(!$uid) : ?>
					<form id="brand-invite-registration-form" class="sas-form">
						<h2>Please enter your Contact information below.</h2>

						<label><span class="form-required">*</span> <b>Required</b></label>

						<br><br>

						<div class="form-errors"></div>

						<h3>Contact Info</h3>
						<div class="form-section">
							<div class="form-row">
								<div class="form-item">
									<label>Contact First Name <span class="form-required">*</span></label>
									<input type="text" id="first-name" required>
								</div>
								<div class="form-item">
									<label>Contact Last Name <span class="form-required">*</span></label>
									<input type="text" id="last-name" required>
								</div>
							</div>

							<div class="form-row">
								<div class="form-item">
									<label>Contact Email <span class="form-required">*</span></label>
									<input type="text" name="email" id="email" required>
								</div>
								<div class="form-item">
									<label>Contact Phone <span class="form-required">*</span></label>
									<input type="text" name="phone" id="phone" required>
								</div>
							</div>

							<div class="form-row">
								<div class="form-item">
									<label>Password <span class="form-required">*</span></label>
									<input type="password" name="password_1" id="password" required>
								</div>
								<div class="form-item">
									<label>Confirm Password <span class="form-required">*</span></label>
									<input type="password" name="password_2" required>
								</div>
							</div>
						</div>
						<?php wp_nonce_field('brand_invite_registration', 'brand_invite_registration_nonce'); ?>
						<button class="sas-round-button-primary" type="submit">Submit</button>
						<input type="hidden" id="invite-string" value="<?php echo $_GET['invite'] ?>">
					</form>

					<div id="brand-registration-success" style="display: none;">
						<h2>Thanks for signing up!</h2>
						<p>You can head over to <a href="<?php echo get_site_url() . '/brand-account' ?>">your brand dashboard</a> now.</p>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div> <!-- #main-content -->

<?php

get_footer();