<?php

get_header();

$uid = get_current_user_id();

$value='';

if( isset($_GET['b']) && $_GET['b'] ) {
    if(check_brand_user_role($_GET['b'], $uid)) {
        // set brand ID hidden input
        ?>
        <input type="hidden" id="brand-id" value="<?php echo $_GET['b']; ?>">
        <input type="hidden" id="brand-account-url" value="<?php echo get_site_url() . '/brand-account/' ?>">
        <input type="hidden" id="content-url" value="<?php echo get_stylesheet_directory_uri(); ?>">
        <?php
        $campaign_id = isset($_GET['c'])?sanitize_key($_GET['c']):'';
        $selected_strategy = get_post_meta($campaign_id, 'campaign_strategy', true);
        $form_completion = get_post_meta($campaign_id, 'form_completion', true)?get_post_meta($campaign_id, 'form_completion', true):array('1' => '','2' => '','3' => '');
        // Check if campaign is set
        if( $campaign_id ) {
            // Check if campaign belongs to this brand
            $campaign_brand = get_post_meta($campaign_id, 'brand', true);
            if($campaign_brand == $_GET['b'] || current_user_can('administrator')) {
                ?>
                <input type="hidden" id="campaign-id" value="<?php echo $_GET['c']; ?>">
                <input type="hidden" id="selected-strategy" value="<?php echo $selected_strategy; ?>">
                <input type="hidden" id="form-completion" value='<?php echo json_encode($form_completion); ?>'>
                <?php

                // Get Influencer Description Fields
                $min_age = get_post_meta($campaign_id, 'min_age', true);
                $max_age = get_post_meta($campaign_id, 'max_age', true);
                $gender = get_post_meta($campaign_id, 'gender', true)?get_post_meta($campaign_id, 'gender', true):array();
                $countries = get_post_meta($campaign_id, 'countries', true);
                $country_names = get_country_names($countries);
                $countries_JSON = array();
                foreach($country_names as $code => $name) {
                    $countries_JSON[] = array('id' => $code, 'name' => $name);
                }
                $countries_JSON = json_encode($countries_JSON);

                $regions = get_post_meta($campaign_id, 'regions', true);
                $region_names = get_region_names($regions);
                $regions_JSON = array();
                foreach($region_names as $code => $name) {
                    $regions_JSON[] = array('id' => $code, 'name' => $name);
                }
                $regions_JSON = json_encode($regions_JSON);
                $selected_interests = wp_get_post_terms($campaign_id,'product_cat',array('fields'=>'ids'));
                $acquisition_timeline = get_post_meta($campaign_id, 'acquisition_timeline', true);
                $instagram_reach = get_post_meta($campaign_id, 'instagram_reach', true);
                $instagram_engagement = get_post_meta($campaign_id, 'instagram_engagement_rate', true);
                $authenticity = get_post_meta($campaign_id, 'authenticity', true);

                // Get Campaign Logistic Fields
                $variation_title = get_post_meta($campaign_id, 'variations_0_title', true);
                $variation_options_count = get_post_meta($campaign_id, 'variations_0_options', true);
                $variation_options = array();
                for($i=0;$i<$variation_options_count;$i++) {
                    $variation_options[] = get_post_meta($campaign_id, 'variations_0_options_'.$i.'_option', true);
                }
                $product = wc_get_product($campaign_id);
                $title = $product->get_title();
                $description = $product->get_description();
                $prize_description = get_post_meta($campaign_id, 'prize_description', true);
                $quantity = $product->get_stock_quantity();
                $value = $product->get_price();
                $campaign_type = get_post_meta($campaign_id, 'campaign_type', true);
                $program_goal = get_post_meta($campaign_id, 'program_goal', true);
                $payment_structure = get_post_meta($campaign_id, 'payment_structure', true);
                $primary_image_id = get_post_thumbnail_id($campaign_id);
                $primary_image_url = get_the_post_thumbnail_url($campaign_id, 'thumbnail');
                $image_gallery_ids = explode(',',get_post_meta($campaign_id, '_product_image_gallery', true));
                $image_gallery_urls = array();
                foreach($image_gallery_ids as $attach_id) {
                    $image = wp_get_attachment_image_src($attach_id, 'thumbnail');
                    if(is_array($image)) {
                        $image_gallery_urls[] = array(
                            'id' => $attach_id,
                            'url' => $image[0],
                        );
                    }
                }
                $fulfillment_type = get_post_meta($campaign_id, 'fulfillment_type', true);
                $fulfillment_type_other = get_post_meta($campaign_id, 'fulfillment_type_other', true);

                // Get Campaign Guidelines Fields
                $visuals = get_post_meta($campaign_id, 'visuals', true);
                $photo_tags_count = (int)get_post_meta($campaign_id, 'photo_tags', true);
                $photo_tags = array();
                for($i=0;$i<$photo_tags_count;$i++) {
                    $meta_key = 'photo_tags_' . $i . '_tag';
                    $photo_tags[] = get_post_meta($campaign_id, $meta_key, true);
                }
                $caption = get_post_meta($campaign_id, 'caption', true);
                $hashtags_count = (int)get_post_meta($campaign_id, 'photo_tags', true);
                $hashtags = array();
                for($i=0;$i<$hashtags_count;$i++) {
                    $meta_key = 'hashtags_' . $i . '_tag';
                    $hashtags[] = get_post_meta($campaign_id, $meta_key, true);
                }
                $inspiration_images = get_post_meta($campaign_id, 'shoutout_inspiration', true);
                $post_timeline = get_post_meta($campaign_id, 'post_timeline', true);
                $post_timeline_custom = get_post_meta($campaign_id, 'post_timeline_custom', true);

            } else { // Campaign doesn't belong to this brand
                if(SAS_TEST_MODE) {
                    echo '<h1>campaign does not belong to this brand: redirect</h1>';
                } else {
                    wp_redirect(get_site_url() . '/my-account');
                }
            }
        }

        // Get all product categories
        $args = array (
            'taxonomy' => 'product_cat',
            'hide_empty' => 0
        );
        $interests = get_categories($args);
        array_shift($interests); // Remove uncategorized

    } else { // user is NOT a member of this brand
        if(SAS_TEST_MODE) {
            echo '<h1>user is not a member of this brand: redirect</h1>';
        } else {
            wp_redirect('/my-account');
        }
    }
} else { // brand parameter not set
    // redirect
    if(SAS_TEST_MODE) {
        echo '<h1>brand parameter not set: redirect</h1>';
    } else {
       wp_redirect(get_site_url() . '/my-account');
    }
}

?>
<div id="main-content">
    <div class="design-campaign-wrapper">
        <div id="ambassador-submit-popup" style="display: none;">
            <div class="popup">
                <div class="popup-header">
                    <span>Thank you for Submitting your Ambassador Program Campaign application!</span>
                </div>
                <div class="popup-body">
                    <p>Due to the complex nature of these programs, we require you to book a demo with us for as much transparency as possible. Please continue to the link below to book a demo with a campaign manager.</p>
                    <a href="https://meetings.hubspot.com/vinod2?__hstc=181257784.6f58bc9ab977d31c2798250e2e8307c6.1565981109433.1567456031376.1567460956429.47&__hssc=181257784.102.1567460956429&__hsfp=2233295263" target="_blank" class="sas-round-button primary blue ambassador-submit">Book Demo</a>
                </div>
            </div>
            <div class="popup-success">
                <h3>Thanks for booking a demo, we look forward to reviewing your submission in person!</h3>
                <br>
                <a class="sas-round-button primary blue save-draft" href="<?php echo get_site_url() . '/my-account' ?>">Save &amp; Exit</a>
            </div>
        </div>
        <div style="display: none;" id="loading-overlay">
            <div class="loading-heart">
              <span>Loading</span> 
              <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/hearts/blue-heart-filled.svg'; ?>">
            </div>
        </div>
        <div id="ajax-notifier">
            <span class="saving">Saving <i class="fa fa-spinner fa-spin"></i></span>
            <span class="saved">Saved</span>
            <span class="not-saved">Not Saved</span>
        </div>

        <div id="design-campaign-form" data-brand-id="" data-campaign-id="">
            <nav id="design-campaign-nav">
                <div class="design-campaign-nav-inner">
                    <div data-form-part="0" class="design-campaign-nav-item completed">
                        <div class="dot"></div>
                        <div class="nav-item-inner">
                            <span>Select<br>Campaign</span>
                        </div>
                    </div>

                    <div data-form-part="1" class="design-campaign-nav-item <?php echo isset($form_completion[1])?$form_completion[1]:''; ?>" disabled>
                        <div class="dot"></div>
                        <div class="nav-item-inner">
                            <span>Influencer<br>Description</span>
                        </div>
                    </div>

                    <div data-form-part="2" class="design-campaign-nav-item <?php echo isset($form_completion[2])?$form_completion[2]:''; ?>" disabled>
                        <div class="dot"></div>
                        <div class="nav-item-inner">
                            <span>Campaign<br>Logistics</span>
                        </div>
                    </div>

                    <div data-form-part="3" class="design-campaign-nav-item <?php echo isset($form_completion[3])?$form_completion[3]:''; ?>" disabled>
                        <div class="dot"></div>
                        <div class="nav-item-inner">
                            <span>Guide Your<br><span class="campaign-strategy-dependent-text-influencer">Influencer</span>s</span>
                        </div>
                    </div>

                    <div data-form-part="4" class="design-campaign-nav-item" disabled>
                        <div class="dot"></div>
                        <div class="nav-item-inner">
                            <span>Review &amp;<br>Submit</span>
                        </div>
                    </div>
                </div>
            </nav>

            <form id="campaign-strategy" data-form-part="0" class="form-part">
                <h1>Which Campaign Strategy Would You Like to Build?</h1>
                <div class="design-campaign-frame">
                    <div id="campaign-strategy-select">
                        <label class="strategy-item">
                            <input type="radio" name="campaign-strategy" id="shoutout-campaign" value="shoutout" <?php echo $selected_strategy=='shoutout'?'checked':''; ?>>
                            <div class="strategy-item-inner">
                                <span>ShoutOut Campaign</span>
                                <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/shop/shoutout.svg'; ?>">
                                <p>Exchange products or services for social impressions and engagement!</p>
                            </div>
                        </label>
                        <label class="strategy-item">
                            <input type="radio" name="campaign-strategy" id="giveaway-campaign" value="giveaway" <?php echo $selected_strategy=='giveaway'?'checked':''; ?>>
                            <div class="strategy-item-inner">
                                <span>Giveaway Campaign</span>
                                <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/shop/giveaway.svg'; ?>">
                                <p>Set up a giveaway campaign for Influencers to thank their dedicated followers!</p>
                            </div>
                        </label>
                        <label class="strategy-item">
                            <input type="radio" name="campaign-strategy" id="mission-campaign" value="mission" <?php echo $selected_strategy=='mission'?'checked':''; ?>>
                            <div class="strategy-item-inner">
                                <span>Mission Campaign</span>
                                <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/shop/mission.svg'; ?>">
                                <P>Deploy Influencers to active duty!</P>
                            </div>
                        </label>
                        <label class="strategy-item">
                            <input type="radio" name="campaign-strategy" id="ambassador-campaign" value="ambassador" <?php echo $selected_strategy=='ambassador'?'checked':''; ?>>
                            <div class="strategy-item-inner">
                                <span>Ambassador Campaign</span>
                                <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/shop/ambassador.svg'; ?>">
                                <p>Team Up with Influencers and Acquire the Best Possible Brand Representatives!</p>
                            </div>
                        </label>
                    </div>
                    <div class="campaign-strategy-footer">
                        <div class="campaign-strategy-footer-inner">
                            <p>Unsure about which strategy is best for you?<br>Book a consultation with a campaign manager today!</p>
                            <div>
                                <a href="https://meetings.hubspot.com/vinod2" target="_blank" class="sas-round-button secondary white">Book Demo</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form> <!-- #campaign-strategy -->

            <form id="influencer-description" data-form-part="1" class="form-part" style="display: none;">

                <h2><img class="campaign-strategy-dependent-image" src="<?php echo get_stylesheet_directory_uri() . '/images/brand-account/design-campaign/' . $selected_strategy . '.svg'; ?>"> <span>Describe Your Ideal <span class="campaign-strategy-dependent-text-influencer"><?php echo $selected_strategy!='ambassador'?'Influencer':'Ambassador'; ?></span></span></h2>

                <div class="design-campaign-frame">
                    <div class="design-campaign-nav-mobile">
                        <div class="nav-left">
                            <a href="<?php echo get_site_url() . '/brand-account/' ?>" class="sas-round-button secondary small">Cancel Setup</a>
                            <button class="save-draft sas-text-button">Save &amp; Exit</button>
                        </div>
                        <div class="nav-right">
                            <button class="sas-round-button secondary dark-blue small previous-button">Previous</button>
                            <button class="sas-round-button primary dark-blue next-button">Next Step</button>
                        </div>
                    </div>

                    <div class="design-campaign-frame-inner form-row">
                        <div class="form-col">
                            <div class="form-item">
                                <label>Age Range</label>
                                <div class="form-slider" data-range-slider=true data-min="13" data-max="55" data-max-suffix="+">
                                    <input type="number" id="age-range-min" class="slider-min" value="<?php echo $min_age?$min_age:13; ?>" required>
                                    <input type="number" id="age-range-max" class="slider-max" value="<?php echo $max_age?$max_age:55; ?>" required>
                                </div>
                            </div>

                            <div id="gender-select" class="form-item">
                                <label>Gender <span class="subtext">(Select up to 3)</span></label>
                                <div class="select-bar">
                                    <div class="select-bar-inner">
                                        <label class="select-item">
                                            <input type="checkbox" name="gender-select" value="female" <?php echo in_array('female', $gender)?'checked':''; ?> required>
                                            <div class="select-item-inner">
                                                <span>Female</span>
                                            </div>
                                        </label>

                                        <label class="select-item">
                                            <input type="checkbox" name="gender-select" value="male" <?php echo in_array('male', $gender)?'checked':''; ?> required>
                                            <div class="select-item-inner">
                                                <span>Male</span>
                                            </div>
                                        </label>

                                        <label class="select-item ">
                                            <input type="checkbox" name="gender-select" value="other" <?php echo in_array('other', $gender)?'checked':''; ?> required>
                                            <div class="select-item-inner">
                                                <span>Other</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-item">
                                <label>Influencer Location(s)</label>
                                <div id="country-select">
                                    <label><span class="subtext">Countries<br>(for a global campaign do not specify a country)</span></label>
                                    <input type="text" class="token-input">
                                </div>
                                <input type="hidden" id="set-countries" value="<?php echo esc_attr($countries_JSON); ?>">

                                <div style="display: none;" id="region-select">
                                    <br>
                                    <label><span class="subtext">Regions<br>(for nation-wide, don't specify regions)</span></label>
                                    <input type="text" class="token-input">
                                </div>
                                <input type="hidden" id="set-regions" value="<?php echo esc_attr($regions_JSON); ?>">
                            </div>

                            <div class="form-item">
                                <label>Influencer Interests<br><span class="subtext">Select up to 12</span></label>
                                <div class="interests-select-container">
                                <?php foreach($interests as $interest) : ?>
                                    <label class="interest-container">
                                        <input type="checkbox" name="interest-select" class="interest-checkbox" value="<?php echo $interest->term_id; ?>" data-name="<?php echo $interest->name; ?>" <?php echo in_array($interest->term_id, $selected_interests)?'checked':''; ?> required>
                                        <div class="checkbox-inner">
                                            <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/interests/' . $interest->slug . '.svg'; ?>">
                                            <span><?php echo $interest->name; ?></span>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-item" data-hide-for-strategies="[ambassador,mission]">
                                <label>Number of Influencers and Estimated Timeline</label>
                                <div class="timeline-select-container">
                                    <label>
                                        <input type="radio" name="timeline" value="3" <?php echo $acquisition_timeline=='3'?'checked':'' ?> required>
                                        <div class="timeline-item">
                                            <div class="timeline-item-top">40+</div>
                                            <div class="timeline-item-bottom">3 Months</div>
                                        </div>
                                    </label>

                                    <label>
                                        <input type="radio" name="timeline" value="6" <?php echo $acquisition_timeline=='6'?'checked':'' ?> required>
                                        <div class="timeline-item">
                                            <div class="timeline-item-top">90+</div>
                                            <div class="timeline-item-bottom">6 Months</div>
                                        </div>
                                    </label>

                                    <label>
                                        <input type="radio" name="timeline" value="9" <?php echo $acquisition_timeline=='9'?'checked':'' ?> required>
                                        <div class="timeline-item">
                                            <div class="timeline-item-top">145+</div>
                                            <div class="timeline-item-bottom">9 Months</div>
                                        </div>
                                    </label>

                                    <label>
                                        <input type="radio" name="timeline" value="12" <?php echo $acquisition_timeline=='12'?'checked':'' ?> required>
                                        <div class="timeline-item">
                                            <div class="timeline-item-top">205+</div>
                                            <div class="timeline-item-bottom">12 Months</div>
                                        </div>
                                    </label>

                                    <label>
                                        <input type="radio" name="timeline" value="0" <?php echo $acquisition_timeline=='0'?'checked':'' ?> required>
                                        <div class="timeline-item">
                                            <div class="timeline-item-bottom">
                                                Not Sure
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="form-item">
                                <label>Influencer Reach<br><span class="subtext">Required Instagram following</span></label>
                                <div class="form-slider" data-min="1000" data-max="10000" data-step="500" data-suffix="+">
                                    <input type="number" name="instagram-reach" id="instagram-reach" class="slider-value" <?php echo $instagram_reach?'value="'.$instagram_reach.'"':1000; ?> required>
                                </div>
                            </div>

                            <div class="form-item">
                                <label>Engagement Rate</label>
                                <div class="form-slider" data-min="0" data-max="10" data-max-suffix="+" data-suffix="%">
                                    <input type="number" name="instagram-engagement" id="instagram-engagement" class="slider-value" <?php echo $instagram_engagement?'value="'.$instagram_engagement.'"':0; ?> required>
                                </div>
                            </div>

                            <div class="form-item">
                                <label disabled>Audience Authenticity (Premium)</label>
                                <div class="form-slider" data-min="50" data-max="90" data-step="5" data-suffix="%" disabled>
                                    <input type="number" name="authenticity" id="authenticity" class="slider-value" value="<?php echo $authenticity?$authenticity:70; ?>">
                                </div>
                            </div>

                            <div class="form-item">
                                <label disabled>ShoutOut Performance (Premium)<br><span class="subtext">Only accept Influencers who have delivered exceptional content to our brand community before.</span></label>
                                <div class="hearts-container">
                                    <?php for($i=0;$i<5;$i++): ?>
                                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/hearts/light-grey-heart-filled.svg' ?>">
                                    <?php endfor; ?>
                                </div>
                            </div>

                            <div class="form-item">
                                <label disabled>Influencer Audience Demographics (Coming Soon)</label>
                                <img src="<?php echo get_stylesheet_directory_uri() . '/images/brand-account/design-campaign/demographics-map.png'; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="design-campaign-frame-footer">
                        <div class="footer-left">
                            <a href="<?php echo get_site_url() . '/brand-account/' ?>" class="sas-round-button secondary small">Cancel Setup</a>
                            <button class="save-draft sas-text-button">Save &amp; Exit</button>
                        </div>
                        <div class="footer-right">
                            <button class="sas-round-button secondary dark-blue small previous-button">Previous</button>
                            <button class="sas-round-button primary dark-blue next-button">Next Step</button>
                        </div>
                    </div>
                </div>
            </form> <!-- #influencer-description -->

            <form id="campaign-logistics" data-form-part="2" class="form-part" style="display: none;">

                <h2><img class="campaign-strategy-dependent-image" src="<?php echo get_stylesheet_directory_uri() . '/images/brand-account/design-campaign/' . $selected_strategy . '.svg'; ?>"> <span> Campaign Logistics</span></h2>

                <div class="design-campaign-frame">
                    <div class="design-campaign-nav-mobile">
                        <div class="nav-left">
                            <a href="<?php echo get_site_url() . '/brand-account/' ?>" class="sas-round-button secondary small">Cancel Setup</a>
                            <button class="save-draft sas-text-button">Save &amp; Exit</button>
                        </div>
                        <div class="nav-right">
                            <button class="sas-round-button secondary dark-blue small previous-button">Previous</button>
                            <button class="sas-round-button primary dark-blue next-button">Next Step</button>
                        </div>
                    </div>

                    <div class="design-campaign-frame-inner form-row">
                        <div class="form-col">
                            <div class="form-item" data-hide-for-strategies="[ambassador,mission]">
                                <label>Does your product come in multiple colors or sizes? </label>
                                <br>
                                <div id="campaign-variations-container">
                                    <div class="variation-error-message"></div>
                                    <label>Variation Title</label>
                                    <input type="text" id="variation-title" placeholder="Title of this variation, eg. Color or Size" value="<?php echo $variation_title; ?>">
                                    <br><br>
                                    <label>Options for this variation eg. Red, Green, Blue or Small, Medium, Large (seperate each with a space or comma)</label>
                                    <select class="tag-input" data-prefix="" id="variation-options" data-separators="[' ', ',']" multiple>
                                        <?php foreach($variation_options as $option): ?>
                                            <?php if($option): ?>
                                                <option data-select2-tag="true" value="<?php echo $option; ?>" selected><?php echo $option; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-item">
                                <label>Title</label>
                                <input type="text" name="title" id="title" placeholder="Name of product or service" value="<?php echo $title; ?>" required>
                            </div>

                            <div class="form-item">
                                <label>Description<br><span class="subtext">100+ words recommended</span></label>
                                <textarea rows="6" name="description" id="description" placeholder="Sell your experience! What exactly do the Influencers get to experience from you? Be specific and detailed, talk about what makes this experience unique. What will their followers like about your product or service?" required><?php echo wp_strip_all_tags($description); ?></textarea>
                            </div>

                            <div class="form-item" data-hide-for-strategies="[shoutout,mission,ambassador]">
                                <label>Giveaway Guidelines / Prize Description</label>
                                <textarea rows="2" name="prize-description" id="prize-description" placeholder="Describe what the Influencers will be giving away to some lucky followers." required><?php echo $prize_description; ?></textarea>
                            </div>

                            <div class="form-item" data-hide-for-strategies="[ambassador]">
                                <label>Product Retail Value</label>
                                <input type="text" name="value" id="value" placeholder="Value of Product (USD)" value="<?php echo $value; ?>" required>
                            </div>

                            <div class="form-item" data-hide-for-strategies="[ambassador]">
                                <label>Influencers Will Receive: </label>
                                <div class="radio-group">
                                    <label>
                                        <input type="radio" name="campaign-type" value="product" <?php echo $campaign_type=='product'?'checked':''; ?> required>
                                        <div class="inner">
                                            <span class="checkmark"></span>
                                            <span class="label">Product</span>
                                        </div>
                                    </label>
                                    <label>
                                        <input type="radio" name="campaign-type" value="service" <?php echo $campaign_type=='service'?'checked':''; ?> required>
                                        <div class="inner">
                                            <span class="checkmark"></span>
                                            <span class="label">Service</span>
                                        </div>
                                    </label>
                                    <label>
                                        <input type="radio" name="campaign-type" value="experience" <?php echo $campaign_type=='experience'?'checked':''; ?> required>
                                        <div class="inner">
                                            <span class="checkmark"></span>
                                            <span class="label">Experience</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-col">

                            <div class="form-item" data-hide-for-strategies="[shoutout,mission,giveaway]">
                                <label>Program Goal</label>
                                <textarea name="program-goal" id="program-goal" rows="4" placeholder="What is your brand's goal for the Ambassador Program? How will Influencers benefit, and how will your Brand?" required><?php echo $program_goal; ?></textarea>
                            </div>

                            <div class="form-item" data-hide-for-strategies="[shoutout,mission,giveaway]">
                                <label>Payment Structure</label>
                                <textarea name="payment-structure" id="payment-structure" rows="3" placeholder="Describe the comission structure that your program will have. Please note ShopandShout operates in USD." required><?php echo $payment_structure; ?></textarea>
                            </div>

                            <div class="form-item no-ajax-save" data-hide-for-strategies="[ambassador]">
                                <label>Product Image Upload<br><span class="subtext">We recommend a minimum of 4 images</span></label>
                                <div class="image-upload-box">
                                    <div class="input">
                                        <span class="advanced-upload"><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/forms/image-placeholder.png' ?>"><br>Drag images here<br>or</span>
                                        <label class='image-upload-input-container'>
                                            <span>browse your computer</span>
                                            <input name="campaign-image-input" style="display: none;" type="file" id="campaign-image-input" accept="image/*" multiple>
                                        </label>
                                    </div>
                                    <div class="uploading">Uploading <i class="fa fa-spinner fa-spin"></i><br>This could take a minute.</div>
                                    <div class="success">Done!</div>
                                    <div class="error">Something went wrong, please refresh and try again.</div>
                                </div>
                            </div>

                            <div class="form-item" data-hide-for-strategies="[ambassador]">
                                <label>Product Images<br><span class="subtext">Highlighted image will be the hero image (please use an image with a plain white background)</span></label>
                                <div id="campaign-preview-images-container">
                                    <?php if($primary_image_id) : ?>
                                        <div class="preview-image primary" data-attachment-id="<?php echo $primary_image_id; ?>">
                                            <span class="remove"></span>
                                            <img src="<?php echo $primary_image_url; ?>">
                                        </div>
                                    <?php endif; ?>
                                    <?php foreach($image_gallery_urls as $image) : ?>
                                        <div class="preview-image" data-attachment-id="<?php echo $image['id']; ?>">
                                            <span class="remove"></span>
                                            <img src="<?php echo $image['url']; ?>">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="form-item" data-hide-for-strategies="[ambassador,mission]">
                                <label>Fulfillment Options<br><span class="subtext">How will the Influencer receive their product</span></label>

                                <div class="radio-group">
                                    <label>
                                        <input type="radio" name="fulfillment-type" value="shipping" <?php echo $fulfillment_type=='shipping'?'checked':''; ?> required>
                                        <div class="inner">
                                            <span class="checkmark"></span>
                                            <span class="label">Direct Shipping (You'll provide tracking!)</span>
                                        </div>
                                    </label>

                                    <label>
                                        <input type="radio" name="fulfillment-type" value="code" <?php echo $fulfillment_type=='code'?'checked':''; ?> required>
                                        <div class="inner">
                                            <span class="checkmark"></span>
                                            <span class="label">Web Redemption Code (You'll provide Influencers with a unique redemption code.)</span>
                                        </div>
                                    </label>

                                    <label>
                                        <input type="radio" name="fulfillment-type" value="other" <?php echo $fulfillment_type=='other'?'checked':''; ?> required>
                                        <div class="inner">
                                            <span class="checkmark"></span>
                                            <span class="label">
                                                <span>Other (Please Describe)</span>
                                                <input class="check-custom-input" type="text" id="fulfillment-type-other" placeholder="Please describe your fulfillment type" value="<?php echo $fulfillment_type_other; ?>">
                                            </span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="design-campaign-frame-footer">
                        <div class="footer-left">
                            <a href="<?php echo get_site_url() . '/brand-account/' ?>" class="sas-round-button secondary small">Cancel Setup</a>
                            <button class="save-draft sas-text-button">Save &amp; Exit</button>
                        </div>
                        <div class="footer-right">
                            <button class="sas-round-button secondary dark-blue small previous-button">Previous</button>
                            <button class="sas-round-button primary dark-blue next-button">Next Step</button>
                        </div>
                    </div>
                </div>
            </form> <!-- #campaign-logistics -->

            <form id="campaign-guidelines" data-form-part="3" class="form-part" style="display: none;">

                <h2><img class="campaign-strategy-dependent-image" src="<?php echo get_stylesheet_directory_uri() . '/images/brand-account/design-campaign/' . $selected_strategy . '.svg'; ?>"> <span><span class="campaign-strategy-dependent-text"></span> Guidelines</span></h2>

                <div class="design-campaign-frame">
                    <div class="design-campaign-nav-mobile">
                        <div class="nav-left">
                            <a href="<?php echo get_site_url() . '/brand-account/' ?>" class="sas-round-button secondary small">Cancel Setup</a>
                            <button class="save-draft sas-text-button">Save &amp; Exit</button>
                        </div>
                        <div class="nav-right">
                            <button class="sas-round-button secondary dark-blue small previous-button">Previous</button>
                            <button class="sas-round-button primary dark-blue next-button">Next Step</button>
                        </div>
                    </div>

                    <div class="design-campaign-frame-inner form-row">
                        <div class="form-col">
                            <div class="form-item" data-hide-for-strategies="[ambassador]">
                                <label>Visual Description</label>
                                <textarea rows="3" name="visuals" id="visuals" placeholder="Give a visual description of what the Influencer's post(s) should look and feel like" required><?php echo $visuals; ?></textarea>
                            </div>

                            <div class="form-item" data-hide-for-strategies="[ambassador]">
                                <label>Photo Tags<br><span class="subtext">Include your Instagram handle and any other accounts you want tagged in the Influencer's post<br>Please seperate each @ tag with a space</span></label>
                                <select class="tag-input" id="photo-tags" data-separators="[' ', ',']" data-prefix="@" multiple required>
                                    <?php foreach($photo_tags as $tag) : ?>
                                        <?php if($tag) : ?>
                                            <option data-select2-tag="true" value="<?php echo $tag; ?>" selected><?php echo '@'.$tag; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-item" data-hide-for-strategies="[ambassador]">
                                <label>Caption Description</label>
                                <textarea rows="3" name="caption" id="caption" placeholder="Provide some guidance, for what you'd like the Influencer's caption to say. Remember that the caption is an important part of an Influencer's identity. The more creative freedom you allow, the more authentic the post will appear to their followers" required><?php echo $caption ?></textarea>
                            </div>

                            <div class="form-item" data-hide-for-strategies="[ambassador]">
                                <label>Hashtags<br><span class="subtext">Hashtags are included in the Influencer's caption eg. #shoutout<br>Please seperate each # tag with a space</span></label>
                                <select class="tag-input" name="hashtags" id="hashtags" data-separators="[' ', ',']" data-prefix="#" multiple required>
                                    <?php foreach($hashtags as $tag) : ?>
                                        <?php if($tag): ?>
                                            <option data-select2-tag="true" value="<?php echo $tag; ?>" selected><?php echo '#'.$tag; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-col">

                            <div class="form-item no-ajax-save">
                                <label data-hide-for-strategies="[ambassador]">Influencer Inspiration Board<br><span class="subtext">Upload Files</span></label>
                                <label data-hide-for-strategies="[shoutout,giveaway,mission]">Ambassador Inspiration<br><span class="subtext">Please upload a Brand Logo in .png format, as well as any supplementary images. Please also upload a few photos of what you think exemplary Influencers content should look and feel like.</span></label>
                                <div class="image-upload-box">
                                    <div class="input">
                                        <span class="advanced-upload"><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/forms/image-placeholder.png' ?>"><br>Drag images here<br>or</span>
                                        <label class='image-upload-input-container'>
                                            <span>browse your computer</span>
                                            <input style="display: none;" type="file" id="shoutout-inspiration-input" accept="image/*" multiple>
                                        </label>
                                    </div>
                                    <div class="uploading">Uploading <i class="fa fa-spinner fa-spin"></i><br>This could take a minute.</div>
                                    <div class="success">Done!</div>
                                    <div class="error">Something went wrong, please refresh and try again.</div>
                                </div>
                            </div>

                            <div class="form-item">
                                <label>Inspiration Images<br><span class="subtext">Include images that you've previously approved, or would like to use to inspire your Influencers</span></label>
                                <div id="shoutout-inspiration-preview-images-container">
                                    <?php for($i=0;$i<$inspiration_images;$i++) :
                                        $meta_key = 'shoutout_inspiration_' . $i . '_images';
                                        $image = get_post_meta($campaign_id, $meta_key, true);
                                        $url = $image?wp_get_attachment_url($image, 'thumbnail'):'';
                                    ?>
                                        <?php if($image) : ?>
                                            <div class="preview-image" data-attachment-id="<?php echo $image ?>">
                                                <span class="remove"></span>
                                                <img src="<?php echo $url ?>">
                                            </div>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                            </div>

                            <div id="post-timeline" class="form-item" data-hide-for-strategies="[ambassador]">
                                <label>Post Timeline<br><span class="subtext">How long do Influencers have to post about your Campaign?</span></label>
                                <div class="select-bar">
                                    <div class="select-bar-inner">
                                        <label class="select-item">
                                            <input type="radio" name="timeline-select" value="1-week" <?php echo $post_timeline=='1-week'?'checked':''; ?> required>
                                            <div class="select-item-inner">
                                                <span>1 Week</span>
                                            </div>
                                        </label>

                                        <label class="select-item">
                                            <input type="radio" name="timeline-select" value="2-weeks" <?php echo $post_timeline=='2-weeks'?'checked':''; ?> required>
                                            <div class="select-item-inner">
                                                <span>2 Weeks</span>
                                            </div>
                                        </label>

                                        <label class="select-item custom">
                                            <input type="radio" name="timeline-select" value="custom" <?php echo $post_timeline=='custom'?'checked':''; ?> required>
                                            <div class="select-item-inner">
                                                <span>Custom</span>
                                            </div>
                                        </label>
                                    </div>
                                    <br>
                                    <div class="custom-input">
                                        <label><span class="subtext">Enter a specific date, or notes about your ideal schedule</span></label>
                                        <input type="text"<?php echo $post_timeline=='custom'?'style="display: block;"':''; ?> id="post-timeline-custom" placeholder="Enter custom timeline" value="<?php echo $post_timeline_custom; ?>" class="custom-input">
                                    </div>
                                </div>
                            </div>

                            <div class="form-item" data-hide-for-strategies="[shoutout,mission,ambassador]">
                                <p style="font-size: 12px;">By clicking next step you agree to Instagram's terms and conditions for giveaways which can be viewed online hosted on <a href="https://www.shortstack.com/blog/example-giveaway-rules-for-instagram-contests-and-giveaways/" target="_blank">ShortStack</a></p>
                            </div>
                        </div>
                    </div>

                    <div class="design-campaign-frame-footer">
                        <div class="footer-left">
                            <a href="<?php echo get_site_url() . '/brand-account/' ?>" class="sas-round-button secondary small">Cancel Setup</a>
                            <button class="save-draft sas-text-button">Save &amp; Exit</button>
                        </div>
                        <div class="footer-right">
                            <button class="sas-round-button secondary dark-blue small previous-button">Previous</button>
                            <button class="sas-round-button primary dark-blue next-button">Next Step</button>
                        </div>
                    </div>
                </div>
            </form> <!-- #campaign-guidelines -->

            <form id="campaign-review" data-form-part="4" class="form-part" style="display: none;">

                <h2>Final Campaign Overview & Submission</h2>

                <div class="design-campaign-frame">
                    <div class="design-campaign-frame-inner form-row">
                        <div id="campaign-review-inner">
                            <h1>At a Glance</h1> <span class="title-subtext">Please ensure all fields are filled out to your satisfaction.</span>
                            <div class="campaign-review-section">
                                <div class="review-col">
                                    <div class="review-item" data-review-item="age-range">
                                        <label>Age Range</label>
                                        <div class="value"><?php echo $min_age . ' - ' . $max_age; ?></div>
                                    </div>
                                    <div class="review-item" data-review-item="gender">
                                        <label>Gender</label>
                                        <div class="value">Not Set</div>
                                    </div>
                                    <div class="review-item" data-review-item="countries">
                                        <label>Countries</label>
                                        <div class="value">Global</div>
                                    </div>
                                    <div class="review-item" data-review-item="regions">
                                        <label>Regions</label>
                                        <div class="value">All Regions</div>
                                    </div>
                                    <div class="review-item" data-review-item="interests">
                                        <label>Interests</label>
                                        <div class="value">Not Set</div>
                                    </div>
                                </div>
                                <div class="review-col">
                                    <div class="review-item" data-review-item="acquisition-timeline" data-hide-for-strategies="[ambassador,mission]">
                                        <label>Number of Influencers and Estimated Timeline</label>
                                        <?php
                                            $acquisition_timeline_label = 'Not Set';
                                            switch($acquistion_timeline) {
                                                case '3' :
                                                    $acquisition_timeline_label = '3 Months, 40+ Influencers';
                                                    break;
                                                case '6' :
                                                    $acquisition_timeline_label = '6 Months, 90+ Influencers';
                                                    break;
                                                case '9' :
                                                    $acquisition_timeline_label = '9 Months, 145+ Influencers';
                                                    break;
                                                case '12' :
                                                    $acquisition_timeline_label = '12 Months, 205+ Influencers';
                                                    break;
                                                case '0' :
                                                    $acquisition_timeline_label = 'Not Sure';
                                                    break;
                                            }
                                        ?>
                                        <div class="value"><?php echo $acquisition_timeline_label; ?></div>
                                    </div>
                                    <div class="review-item" data-review-item="instagram-reach">
                                        <label>Reach</label>
                                        <div class="value"><?php echo $instagram_reach?$instagram_reach:'Not Set'?></div>
                                    </div>
                                    <div class="review-item" data-review-item="instagram-engagement">
                                        <label>Engagement Rate</label>
                                        <div class="value"><?php echo $instagram_engagement?$instagram_engagment.'%':'Not Set';?></div>
                                    </div>
                                    <div class="review-item" data-review-item="authenticity">
                                        <label>Audience Authenticity</label>
                                        <div class="value"><?php echo $authenticity?$authenticity.'%':'Not Set'?></div>
                                    </div>
                                </div>
                                <div class="edit-col">
                                    <span class="edit" data-form-part="1"><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/edit.svg'; ?>"><span class="edit-inner"> Edit</span></span>
                                </div>
                            </div>
                            <div class="campaign-review-section">
                                <div class="review-col">
                                    <div class="review-item" data-review-item="title">
                                        <label>Title</label>
                                        <div class="value"><?php echo $title?$title:'Not Set'; ?></div>
                                    </div>
                                    <div class="review-item" data-review-item="description" >
                                        <label>Description</label>
                                        <div class="value"><?php echo $description?$description:'Not Set' ?></div>
                                    </div>
                                    <div class="review-item" data-review-item="prize-description" data-hide-for-strategies="[shoutout,mission,ambassador]">
                                        <label>Prize Description</label>
                                        <div class="value"><?php echo $prize_description?$prize_description:'Not Set' ?></div>
                                    </div>
                                    <div class="review-item" data-review-item="value" data-hide-for-strategies="[ambassador]">
                                        <label>ShoutOut Value</label>
                                        <div class="value"><?php echo $value?'$'.$value:'Not Set'; ?></div>
                                    </div>
                                </div>
                                <div class="review-col">
                                    <div class="review-item" data-review-item="campaign-type" data-hide-for-strategies="[ambassador]">
                                        <label>Campaign Type</label>
                                        <div class="value"><?php echo $campaign_type?ucfirst($campaign_type):'Not Set'; ?></div>
                                    </div>
                                    <div class="review-item" data-review-item="program-goal" data-hide-for-strategies="[shoutout,mission,giveaway]">
                                        <label>Program Goal</label>
                                        <div class="value"><?php echo $program_goal?$program_goal:'Not Set'; ?></div>
                                    </div>
                                    <div class="review-item" data-review-item="payment-structure" data-hide-for-strategies="[shoutout,mission,giveaway]">
                                        <label>Payment Structure</label>
                                        <div class="value"><?php echo $payment_structure?$payment_structure:'Not Set'; ?></div>
                                    </div>
                                    <div class="review-item" data-review-item="campaign-images" data-hide-for-strategies="[ambassador]">
                                        <label>Campaign Images</label>
                                        <div class="value">
                                            <?php if($primary_image_id) : ?>
                                                <div class="preview-image primary" data-attachment-id="<?php echo $primary_image_id; ?>">
                                                    <img src="<?php echo $primary_image_url; ?>">
                                                </div>
                                            <?php endif; ?>
                                            <?php foreach($image_gallery_urls as $image) : ?>
                                                <div class="preview-image" data-attachment-id="<?php echo $image['id']; ?>">
                                                    <img src="<?php echo $image['url']; ?>">
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="review-item" data-review-item="fulfillment-type" data-hide-for-strategies="[mission, ambassador]">
                                        <label>Fulfillment Type</label>
                                        <div class="value"><?php echo $fulfillment_type?ucfirst($fulfillment_type):'Not Set'; ?></div>
                                    </div>
                                </div>
                                <div class="edit-col">
                                    <span class="edit" data-form-part="2"><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/edit.svg'; ?>"><span class="edit-inner"> Edit</span></span>
                                </div>
                            </div>
                            <div class="campaign-review-section">
                                <div class="review-col">
                                    <div class="review-item" data-review-item="visuals" data-hide-for-strategies="[ambassador]">
                                        <label>Visual Description</label>
                                        <div class="value"><?php echo $visuals?$visuals:'Not Set'; ?></div>
                                    </div>
                                    <div class="review-item" data-review-item="photo-tags" data-hide-for-strategies="[ambassador]">
                                        <label>Photo Tags</label>
                                        <div class="value"><?php echo $photo_tags?$photo_tags:'Not Set'; ?></div>
                                    </div>
                                    <div class="review-item" data-review-item="caption" data-hide-for-strategies="[ambassador]">
                                        <label>Caption Description</label>
                                        <div class="value"><?php echo $caption?$caption:'Not Set'; ?></div>
                                    </div>
                                    <div class="review-item" data-review-item="hashtags" data-hide-for-strategies="[ambassador]">
                                        <label>Hashtags</label>
                                        <div class="value"><?php echo $hashtags?$hashtags:'Not Set'; ?></div>
                                    </div>
                                </div>
                                <div class="review-col">
                                    <div class="review-item" data-review-item="inspiration-images">
                                        <label>Inspiration Images</label>
                                        <div class="value">
                                            <?php foreach($inspiration_images as $image) : ?>
                                                <?php if(!empty($image['images'])) : ?>
                                                    <div class="preview-image" data-attachment-id="<?php echo $image['images']['ID']; ?>">
                                                        <img src="<?php echo $image['images']['sizes']['thumbnail']; ?>">
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="review-item" data-review-item="post-timeline">
                                        <label>Post Timeline</label>
                                        <div class="value"><?php echo $post_timeline?$post_timeline:'Not Set'; ?></div>
                                    </div>
                                </div>
                                <div class="edit-col">
                                    <span class="edit" data-form-part="3"><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/edit.svg'; ?>"><span class="edit-inner"> Edit</span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="design-campaign-frame-footer">
                        <div class="footer-left">
                            <a href="<?php echo get_site_url() . '/brand-account/' ?>" class="sas-round-button secondary small">Cancel Setup</a>
                            <button class="save-draft sas-text-button">Save &amp; Exit</button>
                        </div>
                        <div class="footer-right">
                            <button class="sas-round-button secondary dark-blue small previous-button">Previous</button>
                            <button class="sas-round-button primary dark-blue submit" disabled>Submit Campaign</button>
                        </div>
                    </div>
                </div>
            </form> <!-- #campaign-review -->
        </div>
    </div>
</div> <!-- #main-content -->

<?php

get_footer();
