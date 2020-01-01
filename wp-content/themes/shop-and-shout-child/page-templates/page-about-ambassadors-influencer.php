<?php

get_header();

$blue_heart_filled = get_stylesheet_directory_uri() . '/images/icons/hearts/blue-heart-filled.svg';
$grey_heart_filled = get_stylesheet_directory_uri() . '/images/icons/hearts/grey-heart-filled.svg'

?>

<div id="main-content">
    <div class="home-wrapper landing-page">
        <section id="hero" class="full-width">
            <div>
                <div class="hero-wrapper">
                    <div class="hero-collage-desktop">
                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/campaigncollage1.png'; ?>">
                    </div>
                    <div class="hero-collage-mobile" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/hero-collage-mobile.png'; ?>')"></div>
                    <div class="hero-body">
                        <div class="full-width-inner">
                            <h1 class ="hero-title-mobile">
                                Ambassador
                                <br> Program
                            </h1>
                            <h1 class="hero-title-desktop">
                                 Ambassador <br>
                                 Program
                            </h1>
                            <br><img class="hero-image" src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/ambassador-big.png'; ?>"></img>
                            <p>Become an exclusive ambassador for top brands, and create amazing<br> branded content that shows how amazing of an Influencer you truly are!
                            </p>
                        </div>
                        <div class="hero-buttons" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/shapes/hero-shape.svg' ?>');">
                            <div class="hero-buttons-inner">
                                <a href="<?php echo get_site_url() . '/influencer-signup' ?>" class="hero-button sas-round-button secondary">Sign Up</a>
                                <a href="<?php echo get_site_url() . '/influencer-signup' ?>" class="hero-button sas-round-button secondary blue">View Marketplace</a>
                            </div>
                        </div>
                        <img src="<?php echo $grey_heart_filled; ?>">
                    </div>
                </div>
            </div>
        </section>
        <section id="about" class="full-width">
            <div>
                <div class="about-wrapper">
                    <div class="about-body">
                        <div class="about-body-inner">
                            <h2>How the Ambassador Program Works</h2>
                            <h5>Lorem Ipsum, other Words</h5>
                            <div class="about-info">
                                <p>Ambassador programs allow Influencers to create stronger relationships with brands, and be rewarded for their efforts. Typically paid campaigns where an Influencer regulalry promotes a brand on Instagram, Facebook and more for 3-12months.<br> To get a feel for things, join the ShopandShout (LINK HERE) Ambassador program from your dashboard, where we pay ambassadors 5$ for every influencer they recruit!</p>
                                
                            </div>
                            <p><b>Branded storytelling with a happy ending.<br>Every time</b> <img class="about-heart-desktop" src="<?php echo $blue_heart_filled; ?>"></p> 
                             
                            <img class="about-heart-mobile" src="<?php echo $blue_heart_filled; ?>">
                        </div>
                    </div>
                    <div class="about-collage">
                        <br>
                        <br>
                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/campaigncollage2.png'; ?>">
                    </div>
                </div>
            </div>

        </section>
        

           <section id="middle-cta">
            <div>
                <div class="middle-cta-wrapper">
                    <div class="middle-cta-body">
                        <h6><b><br><br><br><br><br>Like what you hear?</b></h6>
                        <p>Join thousands of Influencers who get to<br>experience cool new brands for free!</p>
                        <a href="<?php echo get_site_url() . '/influencer-signup/' ?>" class="sas-round-button secondary">Join Now</a>
                        <br><br>
                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/hearts/orange-heart-filled.svg'; ?>">
                    </div>
                </div>
            </div>
        </section>
       
        <section id="featured">
            <div>
                <h2>Featured Campaigns</h2>
                <h5>The best marketplace campaigns are going fast!</h5>
                <?php echo do_shortcode('[featured-products]'); ?>
                
        </section>
        <div>
           <center> <a href="<?php echo get_site_url() . '/influencer-signup' ?>" class="button sas-round-button secondary">Sign Up</a></center><br><br>
        </div>
    </div>
</div> <!-- #main-content -->

<?php

get_footer();
