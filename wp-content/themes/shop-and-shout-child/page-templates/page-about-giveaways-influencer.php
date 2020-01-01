<?php

get_header();

$blue_heart_filled = get_stylesheet_directory_uri() . '/images/icons/hearts/blue-heart-filled.svg';
$grey_heart_filled = get_stylesheet_directory_uri() . '/images/icons/hearts/grey-heart-filled.svg';

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
                                Giveaway
                                <br> Campaigns
                            </h1>
                            <h1 class="hero-title-desktop">
                                 Giveaway<br>
                                 Campaigns
                            </h1>
                            <img class="hero-image" src="<?php echo get_stylesheet_directory_uri() . '/images/pages/home/giveaway-big.svg'; ?>"></img>
                           
                            <p>Try out different products and experiences, and<br> then let your followers in on the fun!
                            </p>
                        </div>
                        <div class="hero-buttons" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/shapes/hero-shape.svg' ?>');">
                            <div class="hero-buttons-inner">
                                <a href="<?php echo get_site_url() . '/influencer-signup' ?>" class="hero-button sas-round-button secondary">Sign Up</a>
                                <a href="<?php echo get_site_url() . '/shop' ?>" class="hero-button sas-round-button secondary blue">View Giveaways</a>
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
                            <h2>Why Opt in to a Giveaway Campaign?</h2>

                            <div class="about-info">
                                <h5>Giveaways: The Greatest Way to Grow </h5>
                                <p>Giveaways help you connect with your followers like nothing else. Spread the love, grow your brand, and reach a bigger audience the fastest by hosting Giveaways. <br> <br>Sign up to host a Giveaway now and watch your brand transform!</p>
                                
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
                <h2>Giveaways for Days</h2>
                <h5>See Some Examples of Influencer Giveaways!</h5>
                <div class="influencer-testimonial-cards-wrapper">
                    <div class="influencer-testimonial-card">
                        <a target="_blank" href="https://www.instagram.com/FrankieCena/" class="card-inner">
                            <div class="background-image" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/mom-giveaway.png' ?>');"></div>
                            <div class="card-info">
                                <div class="card-title">
                                    <span>JENN <br>@theoverwhelmedmommy</span>
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/flags/canada.png'; ?>">
                                </div>
                                <div class="stats">
                                    <div class="engagement">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/engagement.svg'; ?>">
                                        <div class="info">
                                            <span class="title">POST ENGAGEMENT</span>
                                            <br>
                                            <span class="value">1.5%</span>
                                        </div>
                                    </div>

                                    <div class="reach">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/reach.svg'; ?>">
                                        <div class="info">
                                            <span class="title">REACH</span>
                                            <br>
                                            <span class="value">76.7K</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <p>"Do you let your kids get messy? I’m talkin’ dirt under those fingernails, paint all over their faces (or yours), clothes-so-stained-you-have-to-throw-them-away kind of messy. Our messiest moments always turn out to be our happiest, smiliest, giggliest ..."</p>
                                    
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="influencer-testimonial-card">
                        <a target="_blank" href="https://www.instagram.com/clearly.clarissa/" class="card-inner">
                            <div class="background-image" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/juhui-giveaway.png' ?>');"></div>
                            <div class="card-info">
                                <div class="card-title">
                                    <span>JUHUI
                                        <br>@whatsup.juice</span>
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
                                    <p>"Makeup is one of my favorite things to do in the morning:sunny: Coffee-> Workout-> Shower-> Makeup! It makes me ready for my day! I received new makeup brush set from @evalinabeautycosmetics These rose gold + pick brushes are the cutest makeup brushes..."</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="influencer-testimonial-card">
                        <a target="_blank" href="https://www.instagram.com/glitzglamom/" class="card-inner">
                            <div class="background-image" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/pages/home/todd-giveaway.png' ?>');"></div>
                            <div class="card-info">
                                <div class="card-title">
                                    <span>TODD
                                        <br> @biohackertodd</span>
                                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/flags/usa.png'; ?>">
                                </div>
                                <div class="stats">
                                    <div class="engagement">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/engagement.svg'; ?>">
                                        <div class="info">
                                            <span class="title">POST ENGAGEMENT</span>
                                            <br>
                                            <span class="value">3.8%</span>
                                        </div>
                                    </div>

                                    <div class="reach">
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/bullets/reach.svg'; ?>">
                                        <div class="info">
                                            <span class="title">REACH</span>
                                            <br>
                                            <span class="value">22.1K</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <p>"Giveaway! I've been using @youpluscbd for over 3 years now. One of the companies I've been most loyal to. Why? Because it works, and the quality is top notch. This is the CBD I buy and give to a family member who has a lot of pain, yet doesn't want to get high from..."</p>
                        
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            
        </section>
         <section id="featured">
            <div>
                <h2>Featured Campaigns</h2>
                <h5>The best marketplace campaigns are going fast!</h5>
                <?php echo do_shortcode('[featured-products]'); ?>
                <div>
                    <a href="<?php echo get_site_url() . '/influencer-signup' ?>" class="button sas-round-button secondary">Sign Up Now</a>
                </div>
        </section>
    </div>
</div> <!-- #main-content -->

<?php

get_footer();
