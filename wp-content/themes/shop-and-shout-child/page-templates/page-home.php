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
                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/hero-collage-desktop.png'; ?>">
                    </div>
                    <div class="hero-collage-mobile" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/hero-collage-mobile.png'; ?>')"></div>
                    <div class="hero-body">
                        <div class="full-width-inner">
                            <h1 class="hero-title-desktop">
                                Get 
                                <span id="hero-typed-desktop">
                                    <div class="typed">
                                        <span></span>
                                    </div>
                                    <span class="placeholder">Experiences </span>
                                </span><br>for Free.<br> Tell Stories<br>that Inspire.
                            </h1>
                            <h1 class="hero-title-mobile">
                                Get 
                                <span id="hero-typed-mobile">
                                    <div class="typed">
                                        <span></span>
                                    </div>
                                    <span class="placeholder">Experiences </span>
                                </span> for Free.<br> Tell Stories that Inspire.
                            </h1>
                        </div>
                        <div class="hero-buttons" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/shapes/hero-shape.svg' ?>');">
                            <div class="hero-buttons-inner">
                                <a href="<?php echo get_site_url() . '/influencer-signup' ?>" class="hero-button sas-round-button secondary">For Influencers</a>
                                <a href="<?php echo get_site_url() . '/brands' ?>" class="sas-round-button secondary blue hero-button">For Brands</a>
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
                            <h2>What We're About</h2>
                            <h5>Trust, Authenticity, Simplicity</h5>
                            <div class="about-info">
                                <p>We believe in the power of micro-Influencers to make a big impact. So we created a marketplace that gives everyday Influencers direct access to amazing brands that their followers love to hear about.</p>
                                <p>At ShopandShout we like to keep it real...simple. We cut out all the back and forth of working with brands so that Influencers can get back to what they're good at: creating relatable content that inspires.</p>
                            </div>
                            <p><b>Branded storytelling with a happy ending.<br>Every time</b> <img class="about-heart-desktop" src="<?php echo $blue_heart_filled; ?>"></p>
                            <img class="about-heart-mobile" src="<?php echo $blue_heart_filled; ?>">
                        </div>
                    </div>
                    <div class="about-collage">
                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/about-collage.png'; ?>">
                    </div>
                </div>
            </div>
        </section>
        <section id="middle-cta">
            <div>
                <div class="middle-cta-wrapper">
                    <div class="middle-cta-body">
                        <h6><b>Like what you hear?</b></h6>
                        <p>Join thousands of Influencers who get to<br>experience cool new brands for free!</p>
                        <a href="<?php echo get_site_url() . '/influencer-signup/' ?>" class="sas-round-button secondary">Join Now</a>
                        <br><br>
                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/hearts/orange-heart-filled.svg'; ?>">
                    </div>
                </div>
            </div>
        </section>
        <section id="marketplace">
            <div>
                <h2>The Influencer Marketplace</h2>
                <h5>Four ways to collaborate, thousands of stories to tell</h5>
                <div class="flip-cards-wrapper">
                    <div class="flip-card">
                        <div class="flip-card-inner">
                            <div class="card-front">
                                <div class="card-title">
                                    <span class="title">ShoutOut Campaigns</span>
                                    <span class="subtitle">Experience cool new products, give an inspiring ShoutOut</span>
                                </div>
                                <div class="card-icon">
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/shoutout-icon.svg'; ?>">
                                </div>
                                <div class="more">
                                    <span>Read More</span>
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/flip-arrow.svg'; ?>">
                                </div>
                            </div>
                            <div class="card-back blue">
                                <div class="card-top">
                                    <span class="dot"></span>
                                    <span class="line"></span>
                                </div>
                                <div class="card-info">
                                    Head to the marketplace, find ShoutOut campaigns that you qualify for, and want to experience. Once you opt in, you'll get the product for $0. Just follow the ShoutOut requirements when it's time to share the love!
                                </div>
                                <div class="less">
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/flip-arrow.svg'; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flip-card">
                        <div class="flip-card-inner">
                            <div class="card-front">
                                <div class="card-title">
                                    <span class="title">Giveaway Campaigns</span>
                                    <span class="subtitle">A little giving goes a long way</span>
                                </div>
                                <div class="card-icon">
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/giveaway-icon.svg'; ?>">
                                </div>
                                <div class="more">
                                    <span>Read More</span>
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/flip-arrow.svg'; ?>">
                                </div>
                            </div>
                            <div class="card-back grey">
                                <div class="card-top">
                                    <span class="dot"></span>
                                    <span class="line"></span>
                                </div>
                                <div class="card-info">
                                    Generate excitement for new and interesting products with custom giveaway campaigns. Look for giveaways in the Marketplace to gift your followers with items they'll love.
                                </div>
                                <div class="less">
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/flip-arrow.svg'; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flip-card">
                        <div class="flip-card-inner">
                            <div class="card-front">
                                <div class="card-title">
                                    <span class="title">Influencer Missions</span>
                                    <span class="subtitle">Get deployed to active duty</span>
                                </div>
                                <div class="card-icon">
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/mission-icon.svg'; ?>">
                                </div>
                                <div class="more">
                                    <span>Read More</span>
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/flip-arrow.svg'; ?>">
                                </div>
                            </div>
                            <div class="card-back orange">
                                <div class="card-top">
                                    <span class="dot"></span>
                                    <span class="line"></span>
                                </div>
                                <div class="card-info">
                                    Big campaigns need big engagement. If you're up for a challenge and think you can take your followers on a journey - we've got some new and exciting campaigns that are sure to prove your clout!
                                </div>
                                <div class="less">
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/flip-arrow.svg'; ?>">
                                </div>
                            </div>
                        </div>
                    </div><div class="flip-card">
                        <div class="flip-card-inner">
                            <div class="card-front">
                                <div class="card-title">
                                    <span class="title">Ambassador Programs</span>
                                    <span class="subtitle">Take the relationship<br>to the next level</span>
                                </div>
                                <div class="card-icon">
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/ambassador-icon.svg'; ?>">
                                </div>
                                <div class="more">
                                    <span>Read More</span>
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/flip-arrow.svg'; ?>">
                                </div>
                            </div>
                            <div class="card-back light-blue">
                                <div class="card-top">
                                    <span class="dot"></span>
                                    <span class="line"></span>
                                </div>
                                <div class="card-info">
                                    Become an extension of a brand and an inspiration to your followers. As a Brand Ambassador you'll form close partnerships and be the first to try out exclusive products and experiences.
                                </div>
                                <div class="less">
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/flip-arrow.svg'; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="how-it-works">
            <div>
                <h2>How it works</h2>
                <h5>There are four main steps required for each campaign</h5>

                <div class="featured-cards-wrapper cards-4">
                    <div class="featured-card-container">
                        <div class="featured-card">
                            <div class="icon-container">
                                <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/shop.svg'; ?>">
                            </div>
                            <div class="body-container">
                                <span>SHOP</span>
                                <p>Browse the marketplace for campaigns you qualify for, and want to opt-in to.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="featured-card-container">
                        <div class="featured-card">
                            <div class="icon-container">
                                <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/receive.svg'; ?>">
                            </div>
                            <div class="body-container">
                                <span>EXPERIENCE</span>
                                <p>The brand will send you the product directly. Try it and enjoy - it's yours to keep!</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="featured-card-container">
                        <div class="featured-card">
                            <div class="icon-container">
                                <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/shoutout-icon.svg'; ?>">
                            </div>
                            <div class="body-container">
                                <span>SHARE</span>
                                <p>Follow the campaign requirements and share your experience with your followers.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="featured-card-container">
                        <div class="featured-card">
                            <div class="icon-container">
                                <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/score.svg'; ?>">
                            </div>
                            <div class="body-container">
                                <span>GET SCORED</span>
                                <p>The brand will review your post and score it. Posts are scored out of five hearts. A ShoutOut score of four hearts or more is great!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <h6><b>Ready?</b></h6>
                <p>New and exciting brands are added to the<br>marketplace every day!</p>
                <a href="<?php echo get_site_url() . '/influencer-signup/' ?>" class="sas-round-button secondary">Join Now</a>
            </div>
        </section>
        <section id="ambassador">
            <div>
                <div class="ambassador-wrapper">
                    <div class="ambassador-collage-left">
                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/ambassador-collage-left.png' ?>">
                    </div>
                    <div class="ambassador-collage-right">
                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/ambassador-collage-right.png' ?>">
                    </div>
                    <div class="ambassador-body">
                        <img class="ambassador-hearts" src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/ambassador-hearts.svg'; ?>">
                        <h2>Share the Love.<br>Get <span>Rewarded.</span></h2>
                        <p>Join our Ambassador Program and <b>get paid $5</b> each time a friend or follower gives a ShoutOut for one of our campaigns</p>
                        <div class="cta-container">
                            <div class="button-container">
                                <a href="<?php echo get_site_url() . '/influencer-signup/' ?>" class="sas-round-button secondary">Learn More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="programs" class="full-width">
            <div>
                <h2>Influencer Programs</h2>
                <h5>Privileged recognition for the best and brightest in our community</h5>
                <div class="program-section elite" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/shapes/elite-shape.svg' ?>');">
                    <div class="program-section-inner">
                        <div class="program-info">
                            <h3>Elite Influencers <img src="<?php echo $grey_heart_filled; ?>"></h3>
                            <p>Receive a black Elite Influencer Visa rewards card that we load up with cash as you get invited into exclusive campaigns and paid parnterships with some of our biggest brands.</p>
                            <a href="<?php echo get_site_url() . '/influencer-signup'; ?>" class="sas-round-button secondary">Learn More</a>
                        </div>
                        <div class="program-card">
                            <a target="_blank" href="https://www.instagram.com/amanda_strachan/" class="influencer-card">
                                <div class="background-image" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/amanda-background.jpg' ?>');"></div>
                                <div class="card-info">
                                    <div class="profile">
                                        <div class="profile-pic" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/amanda-profile.jpg' ?>');"></div>
                                        <span class="handle">@amanda_strachan</span>
                                        <div class="interests">FASHION <span class="dot"></span> HEALTHY LIFESTYLE <span class="dot"></span> FITESS</div>
                                    </div>
                                    <div class="stats">
                                        <div>
                                            <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/reach.svg'; ?>">
                                            <div>
                                                <div class="title">REACH</div>
                                                <span class="total">170K</span>
                                            </div>
                                        </div>
                                        <div>
                                            <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/engagement.svg'; ?>">
                                            <div>
                                                <span class="title">ENGAGEMENT</span>
                                                <span class="total">3%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="program-section young-stars" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/shapes/young-stars-shape.svg' ?>');">
                    <div class="program-section-inner">
                        <div class="program-card">
                            <a target="_blank" href="https://www.instagram.com/carson_kropfl/" class="influencer-card">
                                <div class="background-image" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/carson-background.jpg' ?>');"></div>
                                <div class="card-info">
                                    <div class="profile">
                                        <div class="profile-pic" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/carson-profile.jpg' ?>');"></div>
                                        <span class="handle">@carson_kropfl</span>
                                        <div class="interests">SKATEBOARDING <span class="dot"></span> FASHION <span class="dot"></span> ENTREPRENEURSHIP</div>
                                    </div>
                                    <div class="stats">
                                        <div>
                                            <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/reach.svg'; ?>">
                                            <div>
                                                <div class="title">REACH</div>
                                                <span class="total">3,369K</span>
                                            </div>
                                        </div>
                                        <div>
                                            <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/engagement.svg'; ?>">
                                            <div>
                                                <span class="title">ENGAGEMENT</span>
                                                <span class="total">5%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="program-info">
                            <h3>Young Stars <img src="<?php echo $blue_heart_filled; ?>"></h3>
                            <p>Young stars are Influencers between 13 - 19 years old who are growing their audience and their personal brand with relevant and relatable content.</p>
                            <a href="<?php echo get_site_url() . '/young-stars/' ?>" class="sas-round-button secondary">Learn More</a>
                        </div> 
                    </div>
                </div>
            </div>
        </section>
        <section id="featured">
            <div>
                <h2>Featured Campaigns</h2>
                <h5>The best marketplace items are going fast</h5>
                <?php echo do_shortcode('[featured-products]'); ?>
                <a href="<?php echo get_site_url() . '/shop/'; ?>" class="sas-round-button secondary">Go to Marketplace</a>
            </div>
        </section>
        <section id="testimonials" class="full-width">
            <div>
                <h2>Love From Our Influencers</h2>
                <h5>Our community seems to like us</h5>
                <div class="influencer-testimonial-cards-wrapper">
                    <div class="influencer-testimonial-card">
                        <a target="_blank" href="https://www.instagram.com/FrankieCena/" class="card-inner">
                            <div class="background-image" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/frankie.jpg' ?>');"></div>
                            <div class="card-info">
                                <div class="card-title">
                                    <span>FRANKIE CENA</span>
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/flags/canada.png'; ?>">
                                </div>
                                <div class="stats">
                                    <div class="engagement">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/engagement.svg'; ?>">
                                        <div class="info">
                                            <span class="title">ENGAGEMENT</span>
                                            <br>
                                            <span class="value">4.2%</span>
                                        </div>
                                    </div>

                                    <div class="reach">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/reach.svg'; ?>">
                                        <div class="info">
                                            <span class="title">REACH</span>
                                            <br>
                                            <span class="value">25.4K</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <p>"I've received some amazing products and experiences because of ShopandShout. The staff are fair, friendly, and fast at replying and the brands are varied and excellent, I can't wait to continue working with them!"</p>
                                    <span class="handle">@FrankieCena</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="influencer-testimonial-card">
                        <a target="_blank" href="https://www.instagram.com/clearly.clarissa/" class="card-inner">
                            <div class="background-image" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/clarissa.jpg' ?>');"></div>
                            <div class="card-info">
                                <div class="card-title">
                                    <span>CLARISSA DIOKNO</span>
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/flags/canada.png'; ?>">
                                </div>
                                <div class="stats">
                                    <div class="engagement">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/engagement.svg'; ?>">
                                        <div class="info">
                                            <span class="title">ENGAGEMENT</span>
                                            <br>
                                            <span class="value">4.2%</span>
                                        </div>
                                    </div>

                                    <div class="reach">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/reach.svg'; ?>">
                                        <div class="info">
                                            <span class="title">REACH</span>
                                            <br>
                                            <span class="value">12.8K</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <p>"ShopandShout is such an awesome concept, and I'm so happy that I found out about it. I love that influencers don't have to pay anything to try cool new products and services!"</p>
                                    <span class="handle">@clearly.clarissa</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="influencer-testimonial-card">
                        <a target="_blank" href="https://www.instagram.com/glitzglamom/" class="card-inner">
                            <div class="background-image" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/jenna.jpg' ?>');"></div>
                            <div class="card-info">
                                <div class="card-title">
                                    <span>JENNA</span>
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/flags/usa.png'; ?>">
                                </div>
                                <div class="stats">
                                    <div class="engagement">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/engagement.svg'; ?>">
                                        <div class="info">
                                            <span class="title">ENGAGEMENT</span>
                                            <br>
                                            <span class="value">7.2%</span>
                                        </div>
                                    </div>

                                    <div class="reach">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/reach.svg'; ?>">
                                        <div class="info">
                                            <span class="title">REACH</span>
                                            <br>
                                            <span class="value">42.9K</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <p>"ShopandShout connects influencers with brands in an awesome innovative way. The products featured are all fun, interesting, and very useful. I always make sure I check ShopandShout daily so I never miss an opportunity!"</p>
                                    <span class="handle">@glitzglamom</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <section id="partners" class="full-width">
            <div>
                <h2>Some of Our Brand Partners</h2>
                <h5>Brands who love collaborating with our Influencer community</h5>
                <?php echo do_shortcode('[brand-logos]'); ?>
            </div>
        </section>
    </div>
</div> <!-- #main-content -->

<?php

get_footer();
