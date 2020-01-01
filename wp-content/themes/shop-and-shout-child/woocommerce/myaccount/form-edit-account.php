<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */
wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/styles/css/bootstrap.min.css' );

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' );

// User info
$user_id = get_current_user_id();
// Get the average of all influencer ratings.
$score_data = influencer_get_score_data( $user_id );
$avg_rating = $score_data['average_score'];
// Instagram
$instagram_id = get_user_meta( $user_id, 'inf_instagram_id', true );
$instagram_handle = get_user_meta( $user_id, 'social_prism_user_instagram', true );
$instagram_full_name = get_user_meta( $user_id, 'instagram-full-name', true );
$instagram_following = get_user_meta( $user_id, 'instagram-following', true );
$instagram_bio = get_user_meta( $user_id, 'instagram-bio', true );
$instagram_reach = get_user_meta( $user_id, 'social_prism_user_instagram_reach', true );
$instagram_engagement = get_user_meta( $user_id, 'instagram-engagement-rate', true );
$authentique_audit_completed = get_user_meta( $user_id, 'authentique_audit_completed', true );
$authentique_estimated_real_follower_percentage = get_user_meta( $user_id, 'authentique_estimated_real_follower_percentage', true );
// Audience demographics
global $wpdb;
$countries = $wpdb->get_results(
	"SELECT country_code, country_name FROM country ORDER BY country_name", OBJECT
);
$top_country_1 = get_user_meta( $user_id, 'inf_top_country_1', true );
$top_country_2 = get_user_meta( $user_id, 'inf_top_country_2', true );
$top_country_3 = get_user_meta( $user_id, 'inf_top_country_3', true );
$top_country_percentage_1 = get_user_meta( $user_id, 'inf_top_country_percentage_1', true );
$top_country_percentage_2 = get_user_meta( $user_id, 'inf_top_country_percentage_2', true );
$top_country_percentage_3 = get_user_meta( $user_id, 'inf_top_country_percentage_3', true );
$female_percentage = get_user_meta( $user_id, 'inf_female_percentage', true );
$male_percentage = get_user_meta( $user_id, 'inf_male_percentage', true );
$non_binary_percentage = get_user_meta( $user_id, 'inf_non_binary_percentage', true );
// Facebook
$facebook_id = get_user_meta( $user_id, 'inf_facebook_id', true );
$facebook_reach = get_user_meta( $user_id, 'social_prism_user_facebook_reach', true );
$facebook_hometown = get_user_meta( $user_id, 'facebook_hometown', true );
$facebook_location = get_user_meta( $user_id, 'facebook_location', true );
$facebook_gender = get_user_meta( $user_id, 'facebook_gender', true );
$facebook_age_range = get_user_meta( $user_id, 'facebook_age_range', true );
$facebook_birthdate = get_user_meta( $user_id, 'facebook_birthdate', true );
$facebook_user_page = get_user_meta( $user_id, 'facebook_user_page', true );
$fb_page_id = !empty($_GET['fbpid'])?$_GET['fbpid']:'';
// Twitter
$twitter_reach = get_user_meta( $user_id, 'social_prism_user_twitter_reach', true );
$twitter_handle = get_user_meta( $user_id,'social_prism_user_twitter',true );
$twitter_fullname = get_user_meta( $user_id, 'twitter-full-name', true );
$twitter_following = get_user_meta( $user_id, 'twitter-following', true );
$twitter_bio = get_user_meta( $user_id, 'twitter-bio', true );
$twitter_tweet_count = get_user_meta( $user_id, 'twitter-count', true );
// YouTube
// $youtube_reach = get_user_meta( $user_id, 'youtube_reach', true );
// $youtube_handle = get_user_meta( $user_id,'youtube',true );
// $youtube_fullname = get_user_meta( $user_id, 'youtube-full-name', true );
// $youtube_following = get_user_meta( $user_id, 'youtube-following', true );
// $youtube_bio = get_user_meta( $user_id, 'youtube-bio', true );
// $youtube_video_count = get_user_meta( $user_id, 'youtube-count', true );
// // Pinterest
// $pinterest_reach = get_user_meta( $user_id, 'pinterest_reach', true );
// $pinterest_handle = get_user_meta( $user_id,'pinterest',true );
// // SnapChat
// $snapchat_reach = get_user_meta( $user_id, 'snapchat_reach', true );
// $snapchat_handle = get_user_meta( $user_id,'snapchat',true );

// Social Connects
include_once(get_stylesheet_directory().'/includes/api/social-connects.php');
?>

<div class="my-account-wrapper">
	<div class="row">
		<div class="col-lg-6 sas-form">	
			<?php echo influencer_profile_card( $user_id, $is_pblic = false, $viewer_is_brand = false ); ?>
		</div>
		<div class="col-lg-6">
			<h5>Social Accounts</h5>
			<table class='inf-social-accounts-table'>
				<tr>
					<td><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/instagram-coral.svg' ?>"></td>
					<td>Instagram</td>
					<td><?php echo ( $instagram_reach != '' ? esc_html($instagram_reach) : 'Not Connected' ); ?></td>
				</tr>
				<tr>
					<td><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/twitter-coral.svg' ?>"></td>
					<td>Twitter</td>
					<td><?php echo ($twitter_reach != '' ? esc_html($twitter_reach) : 'Not Connected' ); ?></td>
				</tr>
				<tr>
					<td><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/facebook-coral.svg' ?>"></td>
					<td>Facebook</td>
					<td><?php echo ( $facebook_reach != '' ? esc_attr($facebook_reach) : 'Not Connected' ); ?></td>
				</tr>
				<tr>
					<td><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/youtube-coral.svg' ?>"></td>
					<td>YouTube</td>
					<td>Coming Soon!</td>
				<tr>
					<td><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/snapchat-coral.svg' ?>"></td>
					<td>Snapchat</td>
					<td>Coming Soon!</td>
				</tr>
			</table>
			<?php if($instagram_id) : ?>
				<br><br>
				<h5>Instagram Authenticity Monitor <i data-toggle="tooltip" title="Based on an algorithm that determines what % of followers are real people" class="fa fa-question-circle grey-icon" ></i></h5>

				<?php if( !$authentique_audit_completed ) : ?>

					<span>Calculating... (this may take a few days)</span>

				<?php else : ?>

					<div class="inf-authentique-real-followers-container">
						<div class="real-followers-number"><?php echo round(esc_html($authentique_estimated_real_follower_percentage),1); ?></div>
						<div class="real-followers-percent">%</div>
						<div class="real-followers-label">percentage of<br>authentic followers</div>
					</div>

				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>

	<div class="account-social-data">
		<div class="sas-accordion-group">
			<!-- Instagram Accordion -->
			<div class="sas-accordion-container title-accordion">
				<div class="accordion-head <?php echo (!$instagram_id ? 'disabled' : ''); ?>">
					<span>Instagram <?php echo (!$instagram_id ? '* Required' : ' - @' . $instagram_handle); ?></span>
					<div class="accordion-toggle"></div>
				</div>

				<div class="accordion-between">
					<?php if( !$instagram_id ) : ?>

						<div class="account-social-connects-container">
							<a href="<?php echo 'https://api.instagram.com/oauth/authorize/?client_id=' . $insta_client_id . '&redirect_uri=' . urlencode($redirect_uri) . '&response_type=code&scope=basic' ?>" class="social-connect-button" id="inf_instagram"><img src="<?php echo content_url() . '/uploads/2018/11/Insta-icon-white.svg'; ?>"><span>CONNECT WITH INSTAGRAM</span></a>
						</div>

					<?php else : ?>

						<div class="account-social-connects-container">
							<a href="<?php echo 'https://api.instagram.com/oauth/authorize/?client_id=' . $insta_client_id . '&redirect_uri=' . urlencode($redirect_uri) . '&response_type=code&scope=basic' ?>" class="social-connect-button" id="inf_instagram"><img src="<?php echo content_url() . '/uploads/2018/11/Insta-icon-white.svg' ?>"><span>RECONNECT</span></a>
						</div>

						<br>
						
						<!-- <div class="instagram-assistant-container">
							<a class="sas-round-button-primary" href="<?php echo get_site_url() . '/instagram-assistant/'; ?>">Instagram Assistant</a> <i data-toggle="tooltip" title="We've created an Instagram Assistant just for you! If you'd like to organically grow your Instagram following and engagement rates, this is for you!" class="fa fa-question-circle grey-icon" ></i>
						</div> -->

					<?php endif; ?>
				</div>

				<div class="accordion-body">

				<?php if( $instagram_id != '' ) : ?>

					<div class="account-social-channel-data sas-form">
						
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Instagram handle</label>
									<input type="text" value="@<?php echo esc_attr( $instagram_handle ); ?>" disabled>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Instagram full name</label>
									<input type="text" value="<?php echo esc_attr( $instagram_full_name ); ?>" disabled>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Instagram following</label>
									<input type="text" value="<?php echo esc_attr( $instagram_following ); ?>" disabled>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Instagram reach</label>
									<input type="text" value="<?php echo esc_attr( $instagram_reach ); ?>" disabled>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Engagement %</label>
									<input type="text" value="<?php echo esc_attr( $instagram_engagement ); ?>%" disabled>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Instagram URL</label>
								<input type="text" value="<?php echo 'https://www.instagram.com/' . esc_attr( $instagram_handle ); ?>" disabled>
							</div>
						</div>

					</div>

					<form id="inf-audience-demographics-form" class="sas-form edit-account-form" method="post">
						<h2>Audience demographics</h2>
			        	<label>Enter the top 3 countries and the percentages of your audience from those countries</label>
				    	<div class="result-message"></div>
				        <div class="row">
					    	<div class="col-6">
						        <div class="form-group">
						            <label for="inf_top_country_1">Country 1</label>
						            <select id="inf_top_country_1" name="inf_top_country_1">
						            	<option value="">Select a country</option>
							            <?php foreach( $countries as $country ) : ?>
							            	<option <?php echo ( $top_country_1 == $country->country_name ? 'selected' : '' ) ?>><?php echo esc_attr($country->country_name); ?></option>
							        	<?php endforeach; ?>
						            </select>
						        </div>
					    	</div>
					    	<div class="col-6">
						        <div class="form-group">
						            <label for="inf_percentage_1">Percentage 1</label>
						            <input type="number" name="inf_percentage_1" id="inf_percentage_1" placeholder="Percentage 1" value="<?php echo $top_country_percentage_1 ?>" class="form-control" />
						        </div>
					    	</div>
				        </div>
				        <div class="row">
					    	<div class="col-6">
						        <div class="form-group">
						            <label for="inf_top_country_2">Country 2</label>
						            <select id="inf_top_country_2" name="inf_top_country_2">
						            	<option value="">Select a country</option>
							            <?php foreach( $countries as $country ) : ?>
							            	<option <?php echo ( $top_country_2 == $country->country_name ? 'selected' : '' ) ?>><?php echo esc_attr($country->country_name); ?></option>
							        	<?php endforeach; ?>
						            </select>
						        </div>
					    	</div>
					    	<div class="col-6">
						        <div class="form-group">
						            <label for="inf_percentage_2">Percentage 2</label>
						            <input type="number" name="inf_percentage_2" id="inf_percentage_2" placeholder="Percentage 2" value="<?php echo $top_country_percentage_2 ?>" class="form-control" />
						        </div>
					    	</div>
				        </div>
				        <div class="row">
					    	<div class="col-6">
						        <div class="form-group">
						            <label for="inf_top_country_3">Country 3</label>
						            <select id="inf_top_country_3" name="inf_top_country_3">
						            	<option value="">Select a country</option>
							            <?php foreach( $countries as $country ) : ?>
							            	<option <?php echo ( $top_country_3 == $country->country_name ? 'selected' : '' ) ?>><?php echo esc_attr($country->country_name); ?></option>
							        	<?php endforeach; ?>
						            </select>
						        </div>
					    	</div>
					    	<div class="col-6">
						        <div class="form-group">
						            <label for="inf_percentage_3">Percentage 3</label>
						            <input type="number" name="inf_percentage_3" id="inf_percentage_3" placeholder="Percentage 3" value="<?php echo $top_country_percentage_3 ?>" class="form-control" />
						        </div>
					    	</div>
				        </div>
				        <label>Enter the percentage of your audience for each gender</label>
				        <div class="row">
				        	<div class="col-sm-4">
						        <div class="form-group">
						            <label for="inf_female_percentage">Female</label>
						            <input type="number" name="inf_female_percentage" id="inf_female_percentage" placeholder="Female percentage" value="<?php echo $female_percentage ?>" class="form-control" />
						        </div>
				        	</div>
				        	<div class="col-sm-4">
						        <div class="form-group">
						            <label for="inf_male_percentage">Male</label>
						            <input type="number" name="inf_male_percentage" id="inf_male_percentage" placeholder="Male percentage" value="<?php echo $male_percentage ?>" class="form-control" />
						        </div>
				        	</div>
				        	<div class="col-sm-4">
						        <div class="form-group">
						            <label for="inf_non_binary_percentage">Non-binary</label>
						            <input type="number" name="inf_non_binary_percentage" id="inf_non_binary_percentage" placeholder="Non-binary percentage" value="<?php echo $non_binary_percentage ?>" class="form-control" />
						        </div>
				        	</div>
				        </div>
				        <br>
				        <button type="submit" class="sas-form-submit"/>Save</button>
				        <br>
				        <br>
				    </form>

				<?php endif; ?>

				</div>
			</div>
			<!-- End Instagram Accordion -->

			<!-- Facebook User Accordion -->
			<div class="sas-accordion-container title-accordion">
				<div class="accordion-head <?php echo (!$facebook_id ? 'disabled' : ''); ?>">
					<span>Facebook<?php echo (!$facebook_id ? '' : ' - Connected'); ?></span>
					<div class="accordion-toggle"></div>
				</div>

				<div class="accordion-between">
					<?php if(!$facebook_id) : ?>
						<div class="account-social-connects-container">
							<a href="<?php echo htmlspecialchars($loginURL) ?>" class="social-connect-button" id="inf_facebook">
								<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/facebook-icon.svg'?>">
								<span>CONNECT WITH FACEBOOK</span>
							</a>
						</div>
					<?php else : ?>
						<div class="account-social-connects-container">
							<a href="<?php echo htmlspecialchars($loginURL) ?>" class="social-connect-button" id="inf_facebook">
								<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/facebook-icon.svg'?>">
								<span>RECONNECT WITH FACEBOOK</span>
							</a>
						</div>
					<?php endif; ?>
				</div>

				<div class="accordion-body">
					<?php if( $facebook_reach !== '' ) : ?>
						<div class="row sas-form" id="facebook-user-data">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Facebook Reach</label>
									<input type="text" value="<?php echo esc_attr( $facebook_reach ); ?>" disabled>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Gender</label>
									<input type="text" value="<?php echo esc_attr( $facebook_gender ); ?>" disabled>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>DOB</label>
									<input type="text" value="<?php echo esc_attr( $facebook_birthdate ); ?>" disabled>
								</div>
							</div>

							<div class="col-sm-12">
								<div class="form-group">
									<label>Location</label>
									<input type="text" value="<?php echo esc_attr( $facebook_location ); ?>" disabled>
								</div>
							</div>

							<div class="col-sm-12">
								<div class="form-group">
									<label>Hometown</label>
									<input type="text" value="<?php echo esc_attr( $facebook_hometown ); ?>" disabled>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div> 
			<!-- End Facebook User Accordion -->

			<?php if($facebook_id) : // if user has connected to facebook ?>
				<!-- Facebook Pages -->
				<div class="sas-accordion-container title-accordion">
					<div class="accordion-head <?php echo (!$facebook_user_page ? 'disabled' : ''); ?>">
						<span>Facebook Page<?php echo (!empty($fbPages) && !empty(json_decode($fbPages->asJson())) ? ' - Connected' : ''); ?></span>
						<div class="accordion-toggle"></div>
					</div>

					<div class="accordion-between">
						<?php if(!empty($fbPages) && !empty(json_decode($fbPages->asJson()))) : // if pages have been connected ?>
							
							<?php if($fb_page_id) : // if user has selected a page ?>

								<div class="account-social-connects-container">
									<a href="<?php echo htmlspecialchars($loginURL) ?>" class="social-connect-button account-social-connects-container" id="inf_facebook">
										<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/facebook-icon.svg'?>">
										<span>RECONNECT PAGES</span>
									</a>
								</div>

							<?php else :  // User has not selected a page ?>

								<div class="fb-page-select-container">
									<?php foreach($fbPages as $page) : ?>
									<?php
										$pid = $page['id'];
										$name = $page['name'];
										$image_url = $page['picture']['url'];
									?>
										<div class="fb-page-select">
											<a href="<?php echo get_site_url() . '/my-account/edit-account/?fbpid=' . $pid ?>">
												<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($name); ?>"><span>Use <?php echo esc_html($name); ?></span>
											</a>
										</div>

									<?php endforeach; ?>
								</div>

							<?php endif; ?>

						<?php else : // if pages haven't been connected ?>

							<div class="account-social-connects-container">
								<a href="<?php echo htmlspecialchars($loginURL) ?>" class="social-connect-button" id="inf_facebook">
									<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/facebook-icon.svg'?>">
									<span>CONNECT PAGES</span>
								</a>
							</div>

						<?php endif; ?>
					</div>

					<div class="accordion-body">
						<?php do_shortcode('[facebook_page_data]'); ?>
					</div>
				</div> 
				<!-- End Facebook Pages -->
			<?php endif; ?>

			<!-- Twitter Data -->
			<div class="sas-accordion-container title-accordion">
				<div class="accordion-head <?php echo (!$twitter_reach ? 'disabled' : ''); ?>">
					<span>Twitter <?php echo (!$twitter_reach ? '' : ' - Connected'); ?></span>
					<div class="accordion-toggle"></div>
				</div>

				<div class="accordion-between">
					<?php if(!$twitter_reach) : // if twitter not connected ?>

						<div class="account-social-connects-container">
						    <a href="<?php echo $twitter_redirect_uri; ?>?request=twitter"class="social-connect-button" id="inf_twitter" scope="public_profile,email" onlogin="checkLoginState();"><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/twitter-icon.svg'; ?>"><span>CONNECT WITH TWITTER</span></a>
						</div>

					<?php else : // twitter connected ?>

						<div class="account-social-connects-container">
							<a href="<?php echo $twitter_redirect_uri; ?>?request=twitter"class="social-connect-button" id="inf_twitter" scope="public_profile,email" onlogin="checkLoginState();"><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/twitter-icon.svg'; ?>"><span>RECONNECT</span></a>
						</div>

					<?php endif; ?>
				</div>

				<div class="accordion-body">

					<?php if($twitter_reach) : ?>

						<div class="row">
							<div class="col-xs-6">
								<div class="form-group">
									<label>Twitter handle</label>
									<input type="text" value="<?php echo esc_attr( $twitter_handle ); ?>" disabled>
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<label>Twitter full name</label>
									<input type="text" value="<?php echo esc_attr( $twitter_fullname ); ?>" disabled>
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<label>Twitter reach</label>
									<input type="text" value="<?php echo esc_attr( $twitter_reach ); ?>" disabled>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Tweet count</label>
									<input type="text" value="<?php echo esc_attr( $twitter_tweet_count ); ?>" disabled>
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<label>Twitter following</label>
									<input type="text" value="<?php echo esc_attr( $twitter_following ); ?>" disabled>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label>Twitter bio</label>
									<textarea disabled><?php echo esc_attr( $twitter_bio ); ?></textarea>
								</div>
							</div>
						</div>

					<?php endif; ?>

				</div>
			</div> 
			<!-- End Twitter Data -->

		</div><!-- Accordions Container -->
	</div><!-- .social-data -->
</div><!-- .my-account-wrapper -->

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
