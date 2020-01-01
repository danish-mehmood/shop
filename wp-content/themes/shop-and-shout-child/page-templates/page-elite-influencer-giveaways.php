<?php

get_header();

global $post;
$pid = $post->ID;

$influencers = array();

for($i=1;$i<4;$i++) {
	$influencers[$i-1] = array(
		'name' => get_post_meta($pid, 'influencer_' . $i . '_name', true ),
		'handle' => get_post_meta($pid, 'influencer_' . $i . '_handle', true ),
		'preview_url' => get_field( 'influencer_' . $i . '_preview_photo', $pid ),
		'primary_url' => get_field( 'influencer_' . $i . '_primary_photo', $pid ),
		'icon_url' => get_field( 'influencer_' . $i . '_icon_photo', $pid ),
		'interests' => array(),
		'description' => get_post_meta($pid, 'influencer_' . $i . '_description', true ),
		'average_score' => get_post_meta($pid, 'influencer_' . $i . '_average_score', true ),
		'engagement' => get_post_meta($pid, 'influencer_' . $i . '_engagement_rate', true ),
		'authentic_followers' => get_post_meta($pid, 'influencer_' . $i . '_authentic_followers', true ),
		'reach' => get_post_meta($pid, 'influencer_' . $i . '_reach', true ),
	); 

	$interests_count = get_post_meta($pid, 'influencer_' . $i . '_interests', true );
	for($n=0;$n<$interests_count;$n++) {
		$interest = get_post_meta($pid, 'influencer_' . $i . '_interests_' . $n . '_interest', true);
		$influencers[$i-1]['interests'][$n] = $interest;
	}
}

$success_stories_count = get_post_meta( $pid, 'success_stories', true );
$success_stories = array();

for($i=0;$i<$success_stories_count;$i++) {
	$success_stories[$i] = array(
		'title' => get_post_meta( $pid, 'success_stories_' . $i . '_title', true ),
		'handle' => get_post_meta( $pid, 'success_stories_' . $i . '_handle', true ),
		'icon_url' => get_field( 'success_stories_' . $i . '_icon_image', $pid ),
		'preview_url' => get_field( 'success_stories_' . $i . '_preview_image', $pid ),
		'description' => get_post_meta( $pid, 'success_stories_' . $i . '_description', true ),
		'total_likes' => get_post_meta( $pid, 'success_stories_' . $i . '_total_likes', true ),
		'total_comments' => get_post_meta( $pid, 'success_stories_' . $i . '_total_comments', true ),
		'engagement_rate' => get_post_meta( $pid, 'success_stories_' . $i . '_engagement_rate', true ),
	);
}

?>

<div id="main-content">
	<div class="elite-influencer-giveaways-wrapper landing-page">
		<section id="hero" style="background-image: url(<?php echo get_stylesheet_directory_uri() . '/images/pages/elite-giveaways/shapes/hero-2.svg'?>);">
			<div>
				<div class="hero-shape-1">
					<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/elite-giveaways/shapes/hero-1-desktop.svg' ?>">
				</div>
				<h1>Elite Influencer<br>Giveaways</h1>
				<div class="mobile-collage">
					<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/elite-giveaways/mobile-collage.png' ?>">
				</div>
				<div class="desktop-spacer-shape">
					<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/elite-giveaways/shapes/desktop-spacer.svg' ?>">
				</div>
				<div class="desktop-collage-left">
					<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/elite-giveaways/desktop-collage-left.png' ?>">
				</div>
				<div class="desktop-collage-right">
					<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/elite-giveaways/desktop-collage-right.png' ?>">
				</div>
			</div>
		</section>
		<section id="description">
			<div>
				<img class="description-shape-mobile" src="<?php echo get_stylesheet_directory_uri() . '/images/pages/elite-giveaways/shapes/description-shape-mobile.svg'; ?>"/>
				<h2>Give a Little.<br>Get a Lot.</h2>
				<h5>Professional Influencer marketing<br>campaigns, with impact</h5>
				<p>Elite Influencers know how to increase sales by telling the branded stories that consumers want to hear. Verified by other brands in our community to be the best at what they do, Elite Influencers are experts at creating high-quality content that converts. Launch a giveaway campaign with our Elite Influencers and 10x your brand's exposure.</p>
				<a class="sas-round-button-primary" href="<?php echo get_site_url() . '/brand-signup?elite_giveaway'; ?>">Get Started</a>
			</div>
		</section>
		<section id="how-it-works">
			<div>
				<h2>What Makes an<br>Elite Influencer?</h2>
				<h5>The best of the best, handpicked<br>from our community</h5>
				<div class="cards">
					<div>
						<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/elite-giveaways/how-it-works-proven.svg'; ?>">
					</div>
					<div>
						<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/elite-giveaways/how-it-works-engaging.svg'; ?>">
					</div>
					<div>
						<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/elite-giveaways/how-it-works-authentic.svg'; ?>">
					</div>
				</div>
			</div>
		</section>
		<section id="elite-influencers" style="background-image: url(<?php echo get_stylesheet_directory_uri() . '/images/pages/elite-giveaways/shapes/elite-giveaways.svg'?>);">
			<div>
				<h2>Meet Our Elite</h2>
				<h5>Featured content creators<br>who know how to inspire</h5>
				<div class="meet-elite-desktop">
					<div class="elite-preview-cards">

					<?php 

					$count = 0;
					
					foreach( $influencers as $influencer ) :
						
						$selected = ($count > 0 ? false : true);
						$count++;
					?>

						<div class="preview-card <?php echo $selected ? 'selected' : '' ?>" data-influencer="<?php echo $count; ?>">
							<div class="overlay"></div>
							<div class="card-inner">
								<div class="preview-image" style="background-image: url(<?php echo esc_url($influencer['preview_url']); ?>);"></div>
								<span class="name"><?php echo strtoupper(esc_html($influencer['name'])); ?></span>
							</div>
						</div>

					<?php endforeach; ?>

					</div>

					<div class="elite-info-trays">

					<?php $count = 0; ?>
					<?php foreach( $influencers as $influencer ) : ?>
					<?php
						$selected = ($count > 0 ? false : true);
						$count++;
					?>
						<div class="info-tray <?php echo $selected ? 'selected' : '' ?>" data-influencer="<?php echo $count; ?>">
							<div class="info-wrapper">
								<div class="info">
									<div class="icon-image" style="background-image:url(<?php echo esc_url($influencer['icon_url']); ?>);"></div>
									<div class="name"><?php echo strtoupper(esc_html($influencer['name'])); ?></div>
									<div class="handle">@<?php echo esc_html($influencer['handle']) ?></div>

									<div class="info-inner">
										<div class="interests">

										<?php foreach($influencer['interests'] as $interest ) : ?>

											<span><?php echo strtoupper(esc_html($interest)); ?></span>

										<?php endforeach; ?>

										</div>

										<p class="description"><?php echo esc_html($influencer['description']); ?></p>

										<div class="stats">
											<div class="score">
												<span>SHOUTOUT<br>SCORE</span>
												<div class="hearts">
												<?php for( $x = 0; $x < $influencer['average_score']; $x++ ) : ?>
													<img class="heart-icon" width="15" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>"/>
												<?php endfor; ?> 
												</div>
											</div>

											<div class="engagement">
												<span>ENGAGEMENT<br>RATE</span>
												<div class="val"><?php echo esc_html($influencer['engagement']); ?>%</div>
											</div>

											<div class="authentic-followers">
												<span>AUTHENTIC<br>FOLLOWERS</span>
												<div class="val"><?php echo esc_html($influencer['authentic_followers']); ?>%</div>
											</div>

											<div class="total-reach">
												<span>TOTAL<br>REACH</span>
												<div class="val">
													<?php echo ( $influencer['reach'] >= 1000 ? number_format($influencer['reach']) : $influencer['reach'] ); ?>K
												</div>
											</div>
										</div>

										<div class="cta">
											<a class="sas-hollow-button-primary popmake-brands-demo-form">Get in touch</a>
										</div>
									</div>
								</div>
							</div>
							<div class="photo" style="background-image: url(<?php echo esc_url($influencer['primary_url']); ?>);"></div>
						</div>

					<?php endforeach; ?>

					</div>
				</div>

				<div class="meet-elite-mobile">

				<?php $count = 0; ?>
				<?php foreach( $influencers as $influencer ) : ?>
				<?php
					$count++;
				?>

					<div class="preview-card" data-influencer="<?php echo $count; ?>">
						<div class="overlay"></div>
						<div class="card-inner">
							<div class="preview-image" style="background-image: url(<?php echo esc_url($influencer['preview_url']); ?>);"></div>
							<span class="name"><?php echo strtoupper(esc_html($influencer['name'])); ?></span>
						</div>
					</div>

					<div class="info-tray" data-influencer="<?php echo $count; ?>">
						<div class="info-wrapper">
							<div class="info">
								<div class="icon-image" style="background-image:url(<?php echo esc_url($influencer['icon_url']); ?>);"></div>
								<div class="name"><?php echo strtoupper(esc_html($influencer['name'])); ?></div>
								<div class="handle">@<?php echo esc_html($influencer['handle']) ?></div>

								<div class="info-inner">
									<div class="interests">

									<?php foreach($influencer['interests'] as $interest ) : ?>

										<span><?php echo strtoupper(esc_html($interest)); ?></span>

									<?php endforeach; ?>

									</div>

									<p class="description"><?php echo esc_html($influencer['description']); ?></p>

									<div class="stats">
										<div class="score">
											<span>SHOUTOUT<br>SCORE</span>
											<div class="hearts">
											<?php for( $x = 0; $x < $influencer['average_score']; $x++ ) : ?>
												<img class="heart-icon" width="15" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>"/>
											<?php endfor; ?> 
											</div>
										</div>

										<div class="engagement">
											<span>ENGAGEMENT<br>RATE</span>
											<div class="val">
												<?php echo ( $influencer['reach'] >= 1000 ? number_format($influencer['reach']) : $influencer['reach'] ); ?>K
											</div>
										</div>

										<div class="authentic-followers">
											<span>AUTHENTIC<br>FOLLOWERS</span>
											<div class="val"><?php echo esc_html($influencer['authentic_followers']); ?>%</div>
										</div>

										<div class="total-reach">
											<span>TOTAL<br>REACH</span>
											<div class="val"><?php echo esc_html(number_format($influencer['reach'])); ?>K</div>
										</div>
									</div>

									<div class="cta">
										<a class="sas-hollow-button-primary popmake-brands-demo-form">Get in touch</a>
									</div>
								</div>
							</div>
						</div>
						<div class="photo" style="background-image: url(<?php echo esc_url($influencer['primary_url']); ?>);"></div>
					</div>

				<?php endforeach; ?>
				
				</div>
			</div>
		</section>
		<section id="success-stories" style="background-image: url(<?php echo get_stylesheet_directory_uri() . '/images/pages/elite-giveaways/shapes/success-stories-1.svg'?>);">
			<div>
				<div class="success-stories-shape-2">
					<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/elite-giveaways/shapes/success-stories-2.svg' ?>">
				</div>
				<h2>Customer<br>Success Stories</h2>
				<h5>Branded narratives<br>with a happy ending</h5>
				<div class="success-stories-wrapper">

				<?php foreach( $success_stories as $data ) : ?>

					<div class="success-story">
						<div class="preview-image" style="background-image: url(<?php echo esc_url($data['preview_url']); ?>);"></div>
						<div class="story-container">
							<div class="story-inner">
								<div class="title"><?php echo esc_html($data['title']); ?></div>
								<div class="center">
									<div class="handle">@<?php echo esc_html($data['handle']); ?></div>
									<span>for</span>
									<img class="icon-image" height="56" width="56" src="<?php echo esc_url($data['icon_url']); ?>"/>
									<div class="description"><?php echo esc_html($data['description']); ?></div>
								</div>
								<div class="stats">
									<div class="likes">
										<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/elite-giveaways/likes-icon.svg' ?>" height="30" width="30">
										<span><b><?php echo esc_html(number_format($data['total_likes'])); ?></b> consumer likes</span>
									</div>
									<div class="comments">
										<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/elite-giveaways/comments-icon.svg' ?>" height="30" width="30">
										<span><b><?php echo esc_html($data['total_comments']); ?></b> consumer comments</span>
									</div>
									<div class="engagement">
										<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/elite-giveaways/engagement-icon.svg' ?>" height="30" width="30">
										<span><b><?php echo esc_html($data['engagement_rate']); ?>%</b> engagement rate</span>
									</div>
								</div>
							</div>
						</div>
					</div>

				<?php endforeach; ?>

				</div>
			</div>
		</section>
		<section id="bottom-cta">
			<div>
				<div class="bottom-cta-shape-1">
					<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/elite-giveaways/shapes/bottom-cta-1.svg' ?>">
				</div>
				<div class="bottom-cta-shape-2">
					<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/elite-giveaways/shapes/bottom-cta-2.svg' ?>">
				</div>
				<h2>What are you<br>waiting for?</h2>
				<h5>Expand your community,<br>grow your brand.</h5>
				<a class="sas-round-button-primary" href="<?php echo get_site_url() . '/brand-signup?elite_giveaway' ?>">Get Started</a>
			</div>
		</section>
	</div>
</div> <!-- #main-content -->

<?php

get_footer();
