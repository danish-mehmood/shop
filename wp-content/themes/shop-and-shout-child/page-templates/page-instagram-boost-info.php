<?php

get_header();

$boost_url = get_site_url();

if( is_user_logged_in() ) {

	$user = wp_get_current_user();

	if ( in_array( 'brand', (array)$user->roles ) || in_array( 'administrator', (array)$user->roles ) ) {

		$boost_url = get_site_url() . '/instagram-assistant/';

	}

} else {

	$boost_url = get_site_url() . '/brand-signup?instagram-boost';
}

?>

<div id="main-content">
	<div class="instagram-boost-info-wrapper landing-page">
		<section id="hero">
			<div>
				<div class="hero-shape-1">
					<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/shapes/hero-shape-1.svg'; ?>">
				</div>
				<div class="hero-shape-2">
					<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/shapes/hero-shape-2.svg'; ?>">
				</div>
				<h1>Instagram Assistant</h1>
				<div class="ig-rocket">
					<img class="ig" src="<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/ig-assistant2.svg'; ?>">
					
				</div>
				<p>Leverage our team of Instagram professionals to elevate your brand's online presence. Instagram Assistant works by engaging your ideal followers to automatically grow your audience organically over time.</p>
			</div>
		</section>
		<section id="how-it-works">
			<div>
				<h2>How It Works</h2>
				<h5>Get an Instagram following<br>that's all grown up</h5>
				 <div class="featured-cards-wrapper cards-3">
                    <div class="featured-card-container">
                        <div class="featured-card">
                            <div class="icon-container">
                                <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/audience-targeting.svg'; ?>">
                            </div>
                            <div class="body-container">
                                <span>Advanced Audience Targeting</span>
                                <p>Your assistant targets specific demographics, tailored to your ideal Instagram follower.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="featured-card-container">
                        <div class="featured-card">
                            <div class="icon-container">
                                <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/personalized-engagement.svg'; ?>">
                            </div>
                            <div class="body-container">
                                <span>Personalized Engagement</span>
                                <p>Build community engagement with personalized comments, likes and direct messages.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="featured-card-container">
                        <div class="featured-card">
                            <div class="icon-container">
                                <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/results.svg'; ?>">
                            </div>
                            <div class="body-container">
                                <span>Human Powered Results</span>
                                <p>Bots on Instagram are dead. Our team drives results using real people for authentic growth.</p>
                            </div>
                        </div>
                    </div>
                </div>
				<a href="<?php echo $boost_url; ?>" class="sas-round-button-primary">Boost Me</a>
			</div>
		</section>
		<section id="graphs" style="background-image: url(<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/shapes/how-it-works-shape.svg' ?>);">
			<div>
				<h2>Real Followers.<br>Real Results.</h2>
				<h5>Save time and money without<br>sacrificing authenticity</h5>
				<div class="real-followers-percentage-increase">
					<div class="increase-following">
						<div class="numerator"><span><span class="number">0.0</span>%</span></div>
						<div class="seperator"></div>
						<div class="denominator"><span>increase in<br>following</span></div>
					</div>
					<div class="arrow">
						<div class="head"></div>
						<div class="stem"></div>
						<div class="fletching"><span>* on average over 3 months</span></div>
					</div>
					<div class="increase-engagement">
						<div class="numerator"><span><span class="number">0.00</span>%</span></div>
						<div class="seperator"></div>
						<div class="denominator"><span>increase in<br>engagement</span></div>
					</div>
				</div>

				<div class="follower-line-graph">
					<div class="graph-outer">
						<span class="counter">1,640</span>
						<div class="y-axis">
							<div>
								<span>10,000</span>
							</div>
							<div>
								<span>9,000</span>
							</div>
							<div>
								<span>8,000</span>
							</div>
							<div>
								<span>7,000</span>
							</div>
						</div>
					</div>
					<div class="graph-inner-desktop" style="background-image: url(<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/graph-fill-desktop.svg' ?>);"></div>
					<div class="graph-inner-mobile" style="background-image: url(<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/graph-fill-mobile.svg' ?>);"></div>
				</div>
			</div>
		</section>
		<section id="testimonials" style="background-image:url(<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/shapes/testimonials-shape.png' ?>);">
			<div>
				<div class="testimonials-hearts">
					<img class="desktop" src="<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/hearts-1-desktop.png' ?>">
					<img class="mobile" src="<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/hearts-1-mobile.svg' ?>">
				</div>
				<h2>Spread the Love</h2>
				<h5>All of the growth, none of the<br>growing pains</h5>
				<div class="testimonials">
					<div class="testimonial-card-wrapper">
						<div class="testimonial-card-left">
							<div class="desktop">
								<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/naked-snacks-testimonial-desktop.svg' ?>">
							</div>
							<div class="mobile">
								<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/naked-snacks-testimonial-mobile.svg' ?>">
							</div>
						</div>
					</div>
					<div class="testimonial-card-wrapper" style="background-image:url(<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/hearts-2.svg' ?>);">
						<div class="testimonial-card-right">
							<div class="desktop">
								<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/well-daily-testimonial-desktop.svg' ?>">
							</div>
							<div class="mobile">
								<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/well-daily-testimonial-mobile.svg' ?>">
							</div>
						</div>
					</div>
					<div class="testimonial-card-wrapper" style="background-image:url(<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/hearts-3.svg' ?>);">
						<div class="testimonial-card-left">
							<div class="desktop">
								<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/freeyum-testimonial-desktop.svg' ?>">
							</div>
							<div class="mobile">
								<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/freeyum-testimonial-mobile.svg' ?>">
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section id="bottom-cta">
			<div>
				<div class="bottom-cta-shape-1">
					<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/shapes/bottom-cta-shape-1.svg' ?>">
				</div>
				<div class="bottom-cta-shape-2">
					<img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/ig-boost-info/shapes/bottom-cta-shape-2.svg' ?>">
				</div>
				<h2>What are you<br> waiting for?</h2>
				<h5>Expand your community,<br>grow your brand.</h5>
				<a href="<?php echo $boost_url; ?>" class="sas-round-button-primary">Boost Me</a>
			</div>
		</section>
	</div>
</div> <!-- #main-content -->

<?php

get_footer();
