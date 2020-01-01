<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
//do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

global $product;

$campaign_id = $product->get_id();

$related_campaigns = wc_get_related_products($campaign_id, 8);

$campaign_title = get_the_title($campaign_id);
$brand = get_campaign_brand($campaign_id);
$brand_name = $brand->brand_name;
$brand_story = $brand->brand_story;
$brand_website = $brand->brand_website;
$paid_campaign = get_post_meta($campaign_id, 'paid_campaign', true);
$product_description = get_post_meta($campaign_id, 'product_description', true);
$countries = get_post_meta($campaign_id, 'countries', true);
$regions = get_post_meta($campaign_id, 'regions', true);
$campaign_strategy = get_post_meta($campaign_id, 'campaign_strategy', true);
if(!$product->is_type('variable')) {
  $campaign_value = '$'.$product->get_price();
} else {
  $min_price = '$'.$product->get_variation_price('min');
  $max_price = '$'.$product->get_variation_price('max');

  $campaign_value = $min_price!=$max_price?$min_price . ' - ' .$max_price:$min_price;
}

$channel = get_post_meta($campaign_id, 'shoutout_channels', true)[0];
$engagement_rate = get_post_meta($campaign_id, $channel.'_engagement_rate', true);
$reach = get_post_meta($campaign_id, $channel.'_reach', true);
$min_age = get_post_meta($campaign_id, 'min_age', true);
$max_age = get_post_meta($campaign_id, 'max_age', true);
if($min_age && $max_age) {
  $age_range = $min_age . ' - ' . $max_age;
} elseif(!$min_age) {
  $age_range = 'Below ' . $max_age;
} else {
  $age_range = $min_age . '+';
}
$interests = get_the_terms($campaign_id, 'product_cat');
$description = get_the_content($campaign_id);
$inspiration_count = get_post_meta($campaign_id, 'shoutout_inspiration', true);
$inspiration_images = array();
for($i=0;$i<$inspiration_count;$i++) {
  $meta_key = 'shoutout_inspiration_'.$i.'_images';
  $inspiration_images[] = get_post_meta($campaign_id, $meta_key, true);
}
$visuals = get_post_meta($campaign_id, 'visuals', true);
$photo_tags_count = get_post_meta($campaign_id, 'photo_tags', true);
$photo_tags = array();
for($i=0;$i<$photo_tags_count;$i++) {
	$tag = get_post_meta($campaign_id, 'photo_tags_'.$i.'_tag', true);
	if($tag) {
		$photo_tags[$i] = '@'.$tag;
	}
}
$hashtags_count = get_post_meta($campaign_id, 'hashtags', true);
$hashtags = array();
for($i=0;$i<$hashtags_count;$i++) {
	$tag = get_post_meta($campaign_id, 'hashtags_'.$i.'_tag', true);
	if($tag) {
		$hashtags[$i] = '#'.$tag;
	}
}
$caption = get_post_meta($campaign_id, 'caption', true);
$prize_description = get_post_meta($campaign_id, 'prize_description', true);

$campaign_no_stock = !$product->is_in_stock();

$wc_notices = wc_print_notices(true);
?>
<div class="wc-notices-wrapper <?php echo $wc_notices?'has-notice':''; ?>">
  <div class="wc-notice-overlay"></div>
  <div class="wc-notices">
    <?php echo $wc_notices; ?>
  </div>
</div>
<div class="product-display-wrapper">
	<div id="product-display-left-area">
		<div class="left-area-container">
			<div class="campaign-head-desktop">

        <div class="title">
          <h1><?php echo esc_html($campaign_title); ?></h1>
          <span class="brand">By: <a target="_blank" href="<?php echo esc_url($brand_website); ?>" class="brand-name"><?php echo esc_html($brand_name); ?></a></span>
        </div>
        <div class="campaign-value">
          <span>Retail Value: <span class="value"><?php echo $campaign_value; ?></span></span>
        </div>
			</div>
			<div class="product-image-slider-container desktop">
				<?php echo do_shortcode('[product-display-slider]') ?>
			</div>

      <div class="strategy">
        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/shop/' . $campaign_strategy . '.svg'; ?>">
        <span><?php echo ucfirst($campaign_strategy) . ' Campaign';  ?></span>
      </div>
		</div>
	</div>

	<div id="product-display-right-area">
  <div class="wc-notices-show <?php echo $wc_notices?'has-notice':''; ?>">
    <span class="message">You have some issues opting-in</span>
    <span class="show">Show</span>
  </div>
		<div class="campaign-header-mobile">
			<div class="title">
        <?php if($paid_campaign): ?>
          <div class="paid-campaign">
            <span> Paid Campaign</span>
            <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/shop/paid-check.svg'; ?>">
          </div>
        <?php endif; ?>
				<h1><?php echo esc_html($campaign_title); ?></h1>
				<span class="brand">By: <a target="_blank" href="<?php echo esc_url($brand_website); ?>" class="brand-name"><?php echo esc_html($brand_name); ?></a></span>
			</div>
			<div class="flags-mobile">
				<?php if(is_array($countries)): ?>
					<?php foreach($countries as $country): ?>
						<div class="flag">
							<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/flags/' . $country . '.png'; ?>">
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="flag">
						<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/flags/globe.svg'; ?>">
					</div>
				<?php endif; ?>
			</div>
		</div>

    <div class="campaign-header-desktop">
        <?php if($paid_campaign): ?>
          <div class="paid-campaign">
            <span> Paid Campaign</span>
            <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/shop/paid-check.svg'; ?>">
          </div>
        <?php endif; ?>
      <h1>Influencer<br>Requirements</h1>
    </div>

		<div class="product-image-slider-container mobile">
			<?php echo do_shortcode('[product-display-slider]'); ?>
		</div>	

    <div class="campaign-info-mobile">
      <div class="strategy">
        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/shop/' . $campaign_strategy . '.svg'; ?>">
        <span><?php echo ucfirst($campaign_strategy) . ' Campaign'; ?></span>
      </div>
      <div class="value">
        <b><?php echo esc_html($campaign_value); ?> <span>Value</span></b>
      </div>
    </div>

		<div class="influencer-requirements">
			<div class="primary-requirements">
        <div class="reach">
          <span class="title">Reach</span>
          <span class="value"><?php echo $reach?esc_html($reach):'Not Required'; ?></span>
        </div>
        <div class="engagement">
          <span class="title">Engagement</span>
          <span class="value"><?php echo $engagement_rate?esc_html($engagement_rate).'%':'Not Required'; ?></span>
        </div>
        <div class="age-range">
          <span class="title">Age Range</span>
          <span class="value"><?php echo $age_range; ?></span>
        </div>
      </div>
      <div class="interests-requirements">
        <span class="title">Category</span>
        <div class="interests">
          <?php foreach($interests as $interest): ?>
            <span class="interest"><?php echo $interest->name; ?></span>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="location-requirements-desktop">
        <span class="title">Location</span>
        <div class="locations">
          <?php if(count($countries)): ?>
            <?php foreach($countries as $country): ?>
              <div class="flag">
                <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/flags/'.$country.'.png'; ?>">
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="flag">
              <img src="<?php echo get_stylesheet_directory_uri().'/images/icons/flags/globe.svg' ?>">
            </div>
          <?php endif; ?>
        </div>
        <?php if($regions): ?>
          <span class="regions">*Local region restrictions may apply</span>
        <?php endif; ?>
      </div>
		</div>

		<!-- Add to cart form -->
		<?php do_action( 'woocommerce_single_product_summary' ); ?>
    <?php if($campaign_no_stock) : ?>
      <form class="cart no-stock">
        <span class="no-stock">Coming Back Soon</span>
      </form>
    <?php endif; ?>
		<!-- !Add to cart form -->

    <?php if($product_description): ?>
      <div class="product-description">
        <h2>What You Will Receive</h2>
        <p class="description"><?php echo $product_description; ?></p>
      </div>
    <?php endif; ?>

		<div class="campaign-description">
			<h2>Description</h2>
			<p class="description"><?php echo $description; ?></p>
		</div>

		<div class="campaign-meta sas-accordion-group">
			<div id="guidelines" class="sas-accordion-container shop-accordion">
				<div class="accordion-head">
					<h2>ShoutOut Requirements</h2>
					<div class="accordion-toggle"></div>
				</div>
				<div class="accordion-body">
					<div class="requirement">
						<label>Visuals & Theme</label>
						<p><?php echo esc_html($visuals) ?></p>
					</div>
					<div class="requirement">
						<label>Required Photo Tags</label>
						<p><?php echo count($photo_tags)?implode(', ', $photo_tags):'None Required'; ?></p>
					</div>
					<div class="requirement">
						<label>Required Hashtags</label>
						<p><?php echo count($hashtags)?implode(', ', $hashtags):'None Required'; ?></p>
					</div>
					<div class="requirement">
						<label>Caption Requirements</label>
						<p><?php echo esc_html($caption); ?></p>
					</div>
          <?php if($prize_description && $campaign_strategy == 'giveaway'): ?>
            <div class="requirement">
              <label>Giveaway Guidelines / Prize Description</label>
              <p><?php echo esc_html($prize_description); ?></p>
            </div>
          <?php endif; ?>
				</div>
			</div>

      <?php if(count($inspiration_images)): ?>
        <div class="inspiration">
          <label>Content Inspiration</label>
          <div id="inspiration-slider">
            <?php foreach($inspiration_images as $image): ?>
              <img src="<?php echo wp_get_attachment_image_src($image, 'medium_large')[0]; ?>">
            <?php endforeach; ?>    
          </div>
        </div>
      <?php endif; ?>

			<div id="brand-story" class="sas-accordion-container shop-accordion">
				<div class="accordion-head">
					<h2>Brand Story</h2>
					<div class="accordion-toggle"></div>
				</div>
				<div class="accordion-body">
					<p><?php echo $brand_story; ?></p>
				</div>
			</div>
		</div>

		<div class="related-campaigns-wrapper">
      <h2>Campaigns You Might Like...</h2>
      <div class="featured-campaigns-scroller">
        <div class="featured-campaigns-inner">
          <?php foreach($related_campaigns as $campaign_id): 
          	$campaign = get_post($campaign_id);
            $brand = get_campaign_brand($campaign->ID);
            $brand_name = $brand->brand_name;
            $campaign_title = mb_strimwidth($campaign->post_title, 0, 28, "...");
            $campaign_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($campaign->ID, 'woocommerce_thumbnail'))[0];
            $campaign_countries = get_post_meta($campaign->ID, 'countries', true);
            $campaign_channel = is_array(get_post_meta($campaign->ID, 'shoutout_channels', true))?get_post_meta($campaign->ID, 'shoutout_channels', true)[0]:'instagram';
            $campaign_reach = get_post_meta($campaign->ID, $campaign_channel.'_reach', true);
            $campaign_engagement_rate = get_post_meta($campaign->ID, $campaign_channel.'_engagement_rate', true);
            $campaign_strategy = get_post_meta($campaign->ID, 'campaign_strategy', true);
            $paid_campaign = get_post_meta($campaign_id, 'paid_campaign', true);
          ?>
          
            <div class="featured-campaign">
              <a href="<?php echo get_site_url() . '/product/' . $campaign->post_name; ?>" class="featured-campaign-inner">
                <span class="campaign-title"><?php echo $campaign_title; ?></span>
                <?php if($paid_campaign): ?>
                  <span class="paid-campaign">Paid Campaign <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/shop/paid-check.svg'; ?>"></span>
                <?php endif; ?>
                <span class="brand">by <span class="brand-name"><?php echo $brand_name; ?></span></span>
                <div class="campaign-thumbnail" style="background-image: url(<?php echo $campaign_thumbnail; ?>)">
                  <div class="countries">
                    <?php if(is_array($campaign_countries)): ?>
                      <?php foreach($campaign_countries as $country): ?>
                        <div class="flag">
                          <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/flags/' . $country . '.png'; ?>">
                        </div>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <div class="flag">
                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/flags/globe.svg'; ?>">
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="shoutout-requirements">
                  <div class="reach"> 
                    <span class="title">Reach</span>
                    <span class="value"><?php echo $campaign_reach; ?></span>
                  </div>
                  <div class="engagement">
                    <span class="title">Engagement</span>
                    <span class="value"><?php echo $campaign_engagement_rate?$campaign_engagement_rate.'%':'Not Required'; ?></span>
                  </div>
                </div>
                <div class="shoutout-info">
                  <div class="strategy">
                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/shop/'.$campaign_strategy.'.svg' ?>">
                    <span class="strategy-name"><?php echo ucfirst($campaign_strategy); ?></span>
                  </div>
                  <span class="view-button">View Now</span>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
	</div>
</div>