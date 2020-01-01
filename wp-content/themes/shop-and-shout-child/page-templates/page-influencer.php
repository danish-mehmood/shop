<?php

get_header();

wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/styles/css/bootstrap.min.css' );
?>
<div id="main-content" class="influencer-profile-page-container">
<?php
if( get_query_var( 'inf' ) !== '' ):

	$is_brand = false;

	if( is_user_logged_in() && ( in_array( 'brand', wp_get_current_user()->roles ) || in_array( 'administrator', wp_get_current_user()->roles ) ) ) {

		$is_brand = true;
	}

    // $user = get_user_by( 'slug', get_query_var( 'inf' ) );

    $user = get_users(array(
    	'meta_key' => 'social_prism_user_instagram',
    	'meta_value' => get_query_var('inf'),
    ));
    if(empty($user)) {
    	$user = get_user_by( 'slug', get_query_var( 'inf' ) );
    } else {
			$user = end($user);
		}

    if(!empty($user)) :

	    $influencer_id = $user->ID;

	    // Orders
	    $args = array(
	    	'limit' => -1,
	    	'customer_id' => $influencer_id,
	    	'order_by' => 'date_created',
	    	'return' => 'ids',
	    	'meta_key' => 'shoutout_status',
	    	'meta_value' => 'complete',
	    );

	    $influencer_order_ids = wc_get_orders($args);

	    // Instagram info
		$instagram_handle = get_user_meta( $influencer_id, 'social_prism_user_instagram', true );
		$instagram_full_name = get_user_meta( $influencer_id, 'instagram-full-name', true );
		$instagram_following = get_user_meta( $influencer_id, 'instagram-following', true );
		$instagram_bio = get_user_meta( $influencer_id, 'instagram-bio', true );
		$instagram_reach = get_user_meta( $influencer_id, 'social_prism_user_instagram_reach', true );
		$instagram_engagement = get_user_meta( $influencer_id, 'instagram-engagement-rate', true );

	    // Facebook info
		$facebook_reach = get_user_meta( $influencer_id, 'social_prism_user_facebook_reach', true );

		// Twitter info
		$twitter_reach = get_user_meta( $influencer_id, 'social_prism_user_twitter_reach', true );
		$twitter_handle = get_user_meta( $influencer_id,'social_prism_user_twitter',true );
		$twitter_fullname = get_user_meta( $influencer_id, 'twitter-full-name', true );
		$twitter_following = get_user_meta( $influencer_id, 'twitter-following', true );
		$twitter_bio = get_user_meta( $influencer_id, 'twitter-bio', true );
		$twitter_tweet_count = get_user_meta( $influencer_id, 'twitter-count', true );

	    // Authentique
		$authentique_audit_completed = get_user_meta( $influencer_id, 'authentique_audit_completed', true );
		$authentique_estimated_real_follower_percentage = get_user_meta( $influencer_id, 'authentique_estimated_real_follower_percentage', true );
?>

<?php echo ( SAS_TEST_MODE ? '<h1 style="text-align: center;">Influencer ID: ' . esc_html($user->ID) . '</h1>' : '' ); ?>

<div class="row">
	<div class="col-md-6"><?php echo influencer_profile_card( $user->ID, $is_public = true, $is_brand  ); ?></div>

	<div class="col-md-6 sas-form">
		<?php if( $instagram_bio !== '' ) : ?>
			<div class="influencer-profile-section-card">
				<p>Bio</p>
				<p><?php echo esc_html( $instagram_bio ); ?></p>
			</div>
		<?php endif; ?>
		<br><br>

		<h4>Social Accounts</h4>

		<table class='inf-social-accounts-table'>
			<tr>
				<td><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/instagram-coral.svg' ?>"></td>
				<td><a target="_blank" <?php echo ( $instagram_handle != '' ? 'href="https://www.instagram.com/' . esc_attr($instagram_handle) . '"' : '' ); ?>>Instagram</a></td>
				<td><?php echo esc_attr($instagram_reach) ?></td>
			</tr>
			<tr>
				<td><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/twitter-coral.svg' ?>"></td>
				<td>Twitter</td>
				<td><?php echo ($twitter_reach != '' ? esc_html($twitter_reach) : 'Not Connected' ); ?></td>
			</tr>
			<tr>
				<td><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/facebook-coral.svg' ?>"></td>
				<td>Facebook</td>
				<td><?php echo ( $facebook_reach != '' ? esc_html($facebook_reach) : 'Coming Soon!' ); ?></td>
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

		<br><br>

		<?php if( $authentique_audit_completed ) : ?>

			<h5>Instagram Authenticity Monitor <i data-toggle="tooltip" title="Based on an algorithm that determines what % of followers are real people" class="fa fa-question-circle grey-icon" ></i></h5>

			<div class="inf-authentique-real-followers-container">
				<div class="real-followers-number"><?php echo round(esc_html($authentique_estimated_real_follower_percentage),1); ?></div>
				<div class="real-followers-percent">%</div>
				<div class="real-followers-label">percentage of<br>authentic followers</div>
			</div>

			<br><br>

		<?php endif; ?>

		<?php if( count($influencer_order_ids) ) : ?>

			<h4>ShoutOut History</h4>
			<div class="inf-shoutout-history-table-container">
				<table class="inf-shoutout-history-table">
					<tr>
						<th>Campaign</th>
						<th>ShoutOut</th>
						<th>Score</th>
					</tr>

				<?php foreach( $influencer_order_ids as $order_id ) : ?>
				<?php
					$campaign_id = get_order_campaign( $order_id );
					if($campaign_id) :
						$campaign_url = get_permalink( $campaign_id );
						$campaign_title = get_the_title( $campaign_id );
						$shoutout_channels = get_post_meta( $campaign_id, 'shoutout_channels', true );
					?>
						<tr>
							<td>
								<a href="<?php echo esc_url($campaign_url); ?>"><?php echo esc_html($campaign_title); ?></a>
							</td>
							<td>
							<?php foreach( $shoutout_channels as $channel ) : ?>
							<?php
								$channel_url = get_post_meta( $order_id, $channel . '_url', true );
							?>
								<div>
									<a target="_blank" href="<?php echo esc_url($channel_url); ?>"><?php echo ucfirst(esc_html($channel)); ?></a>
								</div>
							<?php endforeach; ?>
							</td>

							<td>
							<?php foreach( $shoutout_channels as $channel ) : ?>
							<?php
								$channel_score = get_post_meta( $order_id, $channel . '_shoutout_rating', true );
							?>
								<div class="hearts-rating-container">
									<?php  for ( $x = 0; $x < $channel_score; $x++ ) : ?>

									<img class="heart-icon" width="15" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">

									<?php endfor; ?>

									<?php  for ( $x = 5; $x > $channel_score; $x-- ) : ?>

									<img class="heart-icon" width="15" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">

									<?php endfor; ?>
								</div>

							<?php endforeach; ?><!-- foreach $shoutout_channels -->

							</td>
						</tr>
					<?php endif; ?> <!-- if($campaign_id) -->
				<?php endforeach; ?><!-- foreach influencer_order_ids -->


				</table>
			</div>

		<?php endif; ?><!-- if count(influencer_order_ids) -->

		<br><br>

		<h4>Instagram <?php echo ( $instagram_handle != '' ? ' (<a href="https://www.instagram.com/' . esc_attr($instagram_handle) . '" target="_blank">View Profile</a>)' : '' ) ?></h4>

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

		<br>

		<?php if( $facebook_reach != '' ) : ?>

			<h4>Facebook</h4>

			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label>Facebook Reach</label>
						<input type="text" value="<?php echo esc_attr( $facebook_reach ); ?>" disabled>
					</div>
				</div>
			</div>

			<br>

		<?php endif; ?>

		<?php if( $twitter_reach != '' ) : ?>

			<h4>Twitter</h4>

			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label>Twitter handle</label>
						<input type="text" value="<?php echo esc_attr( $twitter_handle ); ?>" disabled>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label>Twitter full name</label>
						<input type="text" value="<?php echo esc_attr( $twitter_fullname ); ?>" disabled>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
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
			</div>
			<div class="row">
				<div class="col">
					<div class="form-group">
						<label>Twitter bio</label>
						<textarea disabled><?php echo esc_attr( $twitter_bio ); ?></textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label>Twitter following</label>
						<input type="text" value="<?php echo esc_attr( $twitter_following ); ?>" disabled>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
	<?php else : ?><!-- if (!empty($user)) -->
<div>
	<h1>User not found</h1>
</div>
	<?php endif; ?><!-- endif (!empty($user)) -->
<?php else :// if (get_query_var( 'inf' ) !== '')
	wp_redirect(get_site_url());
endif; ?><!-- endif (get_query_var( 'inf' ) !== '') -->
</div> <!-- #main-content -->

<?php

get_footer();
