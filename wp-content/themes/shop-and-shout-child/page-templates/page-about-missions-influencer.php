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
                                Influencer
                                <br> Missions
                            </h1>
                            <h1 class="hero-title-desktop">
                                 Influencer<br>
                                 Missions
                            </h1>
                            <img class="hero-image" src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/missions-big.svg'; ?>"></img>
                            
                            <p>Get Invited to Events, Recruit Your Followers for Contests,<br> and Engage your Community in More Diverse Ways!                          </p>
                        </div>
                        <div class="hero-buttons" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/shapes/hero-shape.svg' ?>');">
                            <div class="hero-buttons-inner">
                                <a href="<?php echo get_site_url() . '/influencer-signup' ?>" class="hero-button sas-round-button secondary">Sign Up</a>
                                <a href="<?php echo get_site_url() . '/shop' ?>" class="hero-button sas-round-button secondary blue">View Missions</a>
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
                            <h2>Why Opt in to Influencer Missions?</h2>
                            <h5>Turn Content Creation on its Head</h5>
                            <div class="about-info">
                                <p>Missions are a great way to show your influence while growing your brand portfolio. Immerse yourself and your audience in unique campaigns. From restaurant experiences and and speed boat adventures to path-to-purchase shopping and event attendanace, Influencer Missions are a whole new way to collab with brands. </p>
                                
                            </div>
                            <p><b>Branded storytelling with a happy ending. Every time</b> <img class="about-heart-desktop" src="<?php echo $blue_heart_filled; ?>"></p> 
                             
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
       
        <section id="how-it-works">
            <div>
                <h2><br>4 Simple Steps</h2>
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
       

        <section id="testimonials" class="full-width">
            <div>
                <h2>Mission Mania</h2>
                <h5>See some examples of amazing Mission campaigns!</h5>
                <div class="influencer-testimonial-cards-wrapper">
                    <div class="influencer-testimonial-card">
                        <a target="_blank" href="https://www.instagram.com/p/B0bUVyRAiDo/" class="card-inner">
                            <div class="background-image" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/mary-mission.png' ?>');"></div>
                            <div class="card-info">
                                <div class="card-title">
                                    <span>MARY<br>@wanderwithmary</span>
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/flags/canada.png'; ?>">
                                </div>
                                <div class="stats">
                                    <div class="engagement">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/engagement.svg'; ?>">
                                        <div class="info">
                                            <span class="title">POST ENGAGEMENT</span>
                                            <br>
                                            <span class="value">3.9%</span>
                                        </div>
                                    </div>

                                    <div class="reach">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/reach.svg'; ?>">
                                        <div class="info">
                                            <span class="title">REACH</span>
                                            <br>
                                            <span class="value">28.3K</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <p>"Giveaway Mission! - Do you love treasure hunting as much as I do? Well I'm so excited to start GoldHunt - the original, real-life treasure hunt. If I tell you there's been $100k in treasure hidden somewhere in Vancouver, would you believe me? Maps are available..."</p>
                                    
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="influencer-testimonial-card">
                        <a target="_blank" href="https://www.instagram.com/p/By8LRyaHkTn/" class="card-inner">
                            <div class="background-image" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/clarissa-mission.png' ?>');"></div>
                            <div class="card-info">
                                <div class="card-title">
                                    <span>CLARISSA <br>@clearlyclarissa</span>
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/flags/canada.png'; ?>">
                                </div>
                                <div class="stats">
                                    <div class="engagement">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/engagement.svg'; ?>">
                                        <div class="info">
                                            <span class="title">POST ENGAGEMENT</span>
                                            <br>
                                            <span class="value">2.9%</span>
                                        </div>
                                    </div>

                                    <div class="reach">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/reach.svg'; ?>">
                                        <div class="info">
                                            <span class="title">REACH</span>
                                            <br>
                                            <span class="value">13.2K</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <p>"Mission Giveaway! - Hey friends! Want to win a $125 giftcard to a restaurant of your choice? I've partnered up with @ishopandshout and @angusreidforum for an awesome giveaway! Just follow thise steps: <br> 1: Like this post; 2: Sign up for the Angus..."</p>
                                    
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="influencer-testimonial-card">
                        <a target="_blank" href="https://www.instagram.com/p/ByvosFzhEOj/" class="card-inner">
                            <div class="background-image" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/ming-mission.png' ?>');"></div>
                            <div class="card-info">
                                <div class="card-title">
                                    <span>MIN<br>@min.jing</span>
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/flags/usa.png'; ?>">
                                </div>
                                <div class="stats">
                                    <div class="engagement">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/engagement.svg'; ?>">
                                        <div class="info">
                                            <span class="title">POST ENGAGEMENT</span>
                                            <br>
                                            <span class="value">10.5%</span>
                                        </div>
                                    </div>

                                    <div class="reach">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/reach.svg'; ?>">
                                        <div class="info">
                                            <span class="title">REACH</span>
                                            <br>
                                            <span class="value">1.6K</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <p>"Wanna promote & work with brands? Sign up as an Influencer with @ishopandshout & start collaborating with some of your favourite brands!! I love it, it's super fun and of course very easy. LINK IN MY BIO TO GET STARTED!!! #ishopandshout #shopandshoutpartner"</p>
                                    
                                </div>
                            </div>
                        </a>
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
           <center> <a href="<?php echo get_site_url() . '/influencer-signup' ?>" class="button sas-round-button secondary">Sign Up Now</a></center>
           <br>
           <br>
        </div>
    </div>
</div> <!-- #main-content -->

<?php

get_footer();
