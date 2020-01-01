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
                                ShoutOut
                                <br> Campaigns
                            </h1>
                            <h1 class="hero-title-desktop">
                                 ShoutOut <br>
                                 Campaigns
                            </h1>
                            <img class="hero-image" src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/shoutout-angle1.svg'; ?>"></img>
                            
                            <p>Experience Different Products and Services in Exchange <br>for Creating Captivating Content.
                            </p>
                        </div>
                        <div class="hero-buttons" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/shapes/hero-shape.svg' ?>');">
                            <div class="hero-buttons-inner">
                                <a href="<?php echo get_site_url() . '/influencer-signup' ?>" class="hero-button sas-round-button secondary">Sign Up</a> &nbsp &nbsp &nbsp &nbsp &nbsp
                                <a href="<?php echo get_site_url() . '/shop' ?>" class="hero-button sas-round-button secondary blue">View Campaigns</a>
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
                            <h2>Why Opt-in to a ShoutOut Campaign?</h2>
                            <h5>Content, Exposure, and Advocacy for Your Community</h5>
                            <div class="about-info">
                                <p>ShoutOut campaigns allow micro-Influencers to collaborate with cool new brands and engage their audience. Our Marketplace was designed to help authentic micro-Influencers automate brand collaborations so they can grow faster and build stronger relationships with their audience. <br><br>Sign up now to use your social creativity for amazing products and experiences!</p>
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
                <h2>Shout it Out</h2>
                <h5>See Some ShoutOuts from Our Community!</h5>
                <div class="influencer-testimonial-cards-wrapper">
                    <div class="influencer-testimonial-card">
                        <a target="_blank" href="https://www.instagram.com/georginastokes/" class="card-inner">
                            <div class="background-image" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/georgina-shoutout.png' ?>');"></div>
                            <div class="card-info">
                                <div class="card-title">
                                    <span>GEORGINA <br>@georginastokes</span>
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/flags/canada.png'; ?>">
                                </div>
                                <div class="stats">
                                    <div class="engagement">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/engagement.svg'; ?>">
                                        <div class="info">
                                            <span class="title">POST ENGAGEMENT</span>
                                            <br>
                                            <span class="value">3.5%</span>
                                        </div>
                                    </div>

                                    <div class="reach">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/reach.svg'; ?>">
                                        <div class="info">
                                            <span class="title">REACH</span>
                                            <br>
                                            <span class="value">10.7K</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <p>"The only decisions I'll be making this weekend are where to eat, where to ski, and how long to snuggle with the cats! We had such a lovely brunch last sunday @waterstcafe in Gastown. Vancouver. We were so impressed with how luxurious it felt, the service was..."</p>
                                    
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="influencer-testimonial-card">
                        <a target="_blank" href="https://www.instagram.com/p/B1sSQkjgDwE/" class="card-inner">
                            <div class="background-image" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/nikki-shoutout.png' ?>');"></div>
                            <div class="card-info">
                                <div class="card-title">
                                    <span>NIKKI <br>@nikkikorek</span>
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/flags/canada.png'; ?>">
                                </div>
                                <div class="stats">
                                    <div class="engagement">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/engagement.svg'; ?>">
                                        <div class="info">
                                            <span class="title">POST ENGAGEMENT</span>
                                            <br>
                                            <span class="value">2.0%</span>
                                        </div>
                                    </div>

                                    <div class="reach">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/reach.svg'; ?>">
                                        <div class="info">
                                            <span class="title">REACH</span>
                                            <br>
                                            <span class="value">27.5K</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <p>"Hello! As you know, SLEEP is SOOOOO important. Sleep deprivation is a BIG DEAL, and having 5 hours or less of sleep each night can significantly impact stress harmones, sex hormones, and your RESULTS!...Ingredients like the ones in
@aeryonwellness
SNOOZE can not..."</p>
                                    
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="influencer-testimonial-card">
                        <a target="_blank" href="https://www.instagram.com/p/ByAwU55luSx/" class="card-inner">
                            <div class="background-image" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/raelyn-shoutout.png' ?>');"></div>
                            <div class="card-info">
                                <div class="card-title">
                                    <span>RAELYN<br>@raelynreimer</span>
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/flags/usa.png'; ?>">
                                </div>
                                <div class="stats">
                                    <div class="engagement">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/engagement.svg'; ?>">
                                        <div class="info">
                                            <span class="title">POST ENGAGEMENT</span>
                                            <br>
                                            <span class="value">6.5%</span>
                                        </div>
                                    </div>

                                    <div class="reach">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/reach.svg'; ?>">
                                        <div class="info">
                                            <span class="title">REACH</span>
                                            <br>
                                            <span class="value">10.1K</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <p>"With three little boys pulling at my legs, it's often hard to find some me time.. or to feel like a girl in this hosueful of boys! You can imagine my excitement when I received this gorgeous, Canadian Made box from @SIMPLYBEAUTIFULBOX! Simply ..."</p>
                                    
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
           <center>     <a href="<?php echo get_site_url() . '/influencer-signup' ?>" class="button sas-round-button secondary">Sign Up Now</a></center>
        </section>
        
    </div>
</div> <!-- #main-content -->

<?php

get_footer();
