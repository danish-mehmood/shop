<?php

get_header();

$filters = array(
	'campaign_strategies' => array(),
	'interests' => array(),
	'country' => '',
	'channel' => '',
);
$sort = array(
	'sortby' => '',
	'orderby' => '',
);
$page = get_query_var('page')?get_query_var('page'):1;

$selected_channel = isset($_GET['channel'])?$_GET['channel']:'instagram';

if(isset($_GET['instagram_reach'])) {
	$filters['instagram_reach'] = $_GET['instagram_reach'];
	?> <input type="hidden" id="selected-instagram-reach" value="<?php echo $_GET['instagram_reach'] ?>"> <?php
}
if(isset($_GET['instagram_engagement_rate'])) {
	$filters['instagram_engagement_rate'] = $_GET['instagram_engagement_rate'];
	?> <input type="hidden" id="selected-instagram-engagement" value="<?php echo $_GET['instagram_engagement_rate'] ?>"> <?php
}
if(isset($_GET['campaign_strategies'])) {
	$filters['campaign_strategies'] = explode(',', $_GET['campaign_strategies']);
	?> <input type="hidden" id="selected-campaign-strategies" value="<?php echo $_GET['campaign_strategies'] ?>"> <?php
}
if(isset($_GET['interests'])) {
	$filters['interests'] = explode(',', $_GET['interests']);
	?> <input type="hidden" id="selected-interests" value="<?php echo $_GET['interests'] ?>"> <?php
}
if(isset($_GET['country'])) {
	$filters['country'] = $_GET['country'];
	?> <input type="hidden" id="selected-country" value="<?php echo $_GET['country'] ?>"> <?php
}
if(isset($_GET['channel'])) {
	$filters['channel'] = $_GET['channel'];
	?> <input type="hidden" id="selected-channel" value="<?php echo $_GET['channel'] ?>"> <?php
}
if(isset($_GET['paid_campaign'])) {
	$filters['paid_campaign'] = $_GET['paid_campaign'];
	?><input type="hidden" id="selected-paid-campaign" value="1"><?php
}
if(isset($_GET['sortby'])) {
	$sort['sortby'] = $_GET['sortby'];
	?> <input type="hidden" id="selected-sortby" value="<?php echo $_GET['sortby'] ?>"> <?php
}
if(isset($_GET['order'])) {
	$sort['order'] = $_GET['order'];
	?> <input type="hidden" id="selected-order" value="<?php echo $_GET['order'] ?>"> <?php
}

$shop_campaigns = get_shop_campaigns($filters, $sort, $page);

$categories = get_categories(array(
	'taxonomy' => 'product_cat',
	'hide_empty' => 0,
));
// Remove uncategorized
array_shift($categories);

// Campaigns for me
if(is_user_logged_in()) {
	$user_id = get_current_user_id();

	$inf_interests = get_user_meta($user_id, 'inf_interests', true);
	$inf_country = get_user_meta($user_id, 'inf_country', true);
	$inf_reach = get_user_meta($user_id, 'social_prism_user_'.$selected_channel.'_reach', true);
	$inf_engagement = get_user_meta($user_id, 'instagram-engagement-rate', true);

	$user_params = array(
		'interests' => is_array($inf_interests)?implode(',',$inf_interests):'',
		'country' => $inf_country,
		$selected_channel.'_reach' => $inf_reach,
		$selected_channel.'_engagement_rate' => $inf_engagement,
	);

	$campaigns_for_me_url = get_site_url() . '/shop/?' . http_build_query($user_params);
}

$new_campaigns = get_posts(array(
  'numberposts' => 8,
  'post_type' => 'product',
  'post_status' => 'publish',
  'has_password' => false,
  'meta_query' => array(
    array(
      'key' => '_stock_status',
      'value' => 'instock',
      'compare' => '=',
    )
  )
));

?>
<input type="hidden" id="shop-url" value="<?php echo get_site_url() . '/shop/'; ?>">
<input type="hidden" id="shop-max-pages" value="<?php echo $shop_campaigns['max_pages']; ?>">
<div id="main-content">
	
  <div class="shop-home-wrapper">
  	<h1>Marketplace</h1>
    <div class="slider-animations-wrapper">
    	<img class="corner-heart" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/hearts/blue-heart-filled.svg'; ?>">
      <div class="slider-animations-inner">
        <div class="slide">
          <span>1.</span>
          <span>Explore Your</span>
          <span>Favorite</span>
          <span>Campaigns!</span>
        </div>

        <div class="slide">
          <span>2.</span>
          <span>Do You</span>
          <span>Qualify?</span>
        </div>

        <div class="slide">
          <span>3.</span>
          <span>Review the</span>
          <span>Requirements!</span>
        </div>

        <div class="slide">
          <span>4.</span>
          <span>Opt In!</span>
        </div>
      </div>
    </div>

    <div class="new-campaigns-wrapper">
      <h2>Just In</h2>
      <div class="featured-campaigns-scroller">
        <div class="featured-campaigns-inner">
          <?php foreach($new_campaigns as $campaign): 
            $brand = get_campaign_brand($campaign->ID);
            $brand_name = $brand->brand_name;
            $campaign_title = mb_strimwidth($campaign->post_title, 0, 26, "...");
            $campaign_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($campaign->ID, 'woocommerce_thumbnail'))[0];
            $campaign_countries = get_post_meta($campaign->ID, 'countries', true);
            $campaign_channel = is_array(get_post_meta($campaign->ID, 'shoutout_channels', true))?get_post_meta($campaign->ID, 'shoutout_channels', true)[0]:'instagram';
            $campaign_reach = get_post_meta($campaign->ID, $campaign_channel.'_reach', true);
            $campaign_engagement_rate = get_post_meta($campaign->ID, $campaign_channel.'_engagement_rate', true);
            $campaign_strategy = get_post_meta($campaign->ID, 'campaign_strategy', true);
            $paid_campaign = get_post_meta($campaign->ID, 'paid_campaign', true);
          ?>
            <div class="featured-campaign">
              <a href="<?php echo get_site_url() . '/product/' . $campaign->post_name; ?>" class="featured-campaign-inner">
                <span class="campaign-title"><?php echo $campaign_title; ?></span>
                <?php if($paid_campaign): ?>
                  <span class="paid-campaign"><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/shop/paid-check.svg'; ?>"> Paid Campaign</span>
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

    <div class="campaign-strategies-wrapper">
      <h2>Campaigns</h2>
      <div class="campaign-strategies-container">
        <a href="<?php echo get_site_url() . '/shop/#marketplace' ?>" class="campaign-strategy all-campaigns" style="background-image: url(<?php echo get_stylesheet_directory_uri() . '/images/shop/all-campaigns-card-bg.svg' ?>), linear-gradient(180deg, rgba(246,202,202,1) 0%, rgba(223,157,157,1) 100%)">
          <div class="campaign-strategy-overlay"></div>
          <div class="campaign-strategy-inner">
            <div class="header">
              <h2>All Campaigns</h2>
            </div>
            <div class="body">
              <p>Browse all the campaigns that are available and<br>see what else is coming soon!</p>
              <span class="strategy-button">Explore</span>
            </div>
          </div>
        </a>
        <div class="campaign-strategies">
          <a href="<?php echo get_site_url() . '/shop/?campaign_strategies=shoutout#marketplace' ?>" class="campaign-strategy shoutout-campaigns" style="background-image: url(<?php echo get_stylesheet_directory_uri() . '/images/shop/shoutout-card-bg.svg' ?>), linear-gradient(180deg, rgba(191,221,241,1) 0%, rgba(146,180,208,1) 100%);" data-strategy="shoutout">
          	<div class="campaign-strategy-overlay"></div>
            <div class="campaign-strategy-inner">
              <div class="header">
                <h2>ShoutOut<br>Campaigns</h2>
                <p>Provide a social media ShoutOut for<br>amazing brands and products!</p>
              </div>
              <div class="body">
                <span class="strategy-button">Explore</span>
              </div>
            </div>
          </a>
          <a href="<?php echo get_site_url() . '/shop/?campaign_strategies=giveaway#marketplace' ?>" class="campaign-strategy giveaway-campaigns" style="background-image: url(<?php echo get_stylesheet_directory_uri() . '/images/shop/giveaway-card-bg.svg' ?>), linear-gradient(180deg, rgba(253,212,154,1) 0%, rgba(249,189,121,1) 100%)" data-strategy="giveaway">
          	<div class="campaign-strategy-overlay"></div>
            <div class="campaign-strategy-inner">
              <div class="header">
                <h2>Giveaway<br>Campaigns</h2>
                <p>Share the love with your followers by<br>hosting a branded Giveaway!</p>
              </div>
              <div class="body">
                <span class="strategy-button">Explore</span>
              </div>
            </div>
          </a>
          <a href="<?php echo get_site_url() . '/shop/?campaign_strategies=mission#marketplace' ?>" class="campaign-strategy mission-campaigns" style="background-image: url(<?php echo get_stylesheet_directory_uri() . '/images/shop/mission-card-bg.svg' ?>), linear-gradient(180deg, rgba(197,194,250,1) 0%, rgba(170,167,226,1) 100%)" data-strategy="mission">
          	<div class="campaign-strategy-overlay"></div>
            <div class="campaign-strategy-inner">
              <div class="header">
                <h2>Influencer<br>Missions</h2>
                <p>Challenge your followers, complete<br>goals, and activate your community!</p>
              </div>
              <div class="body">
                <span class="strategy-button">Explore</span>
              </div>
            </div>
          </a>
          <a href="<?php echo get_site_url() . '/shop/?campaign_strategies=ambassador#marketplace' ?>"  class="campaign-strategy ambassador-campaigns" style="background-image: url(<?php echo get_stylesheet_directory_uri() . '/images/shop/ambassador-card-bg.svg' ?>), linear-gradient(180deg, rgba(156,242,150,1) 0%, rgba(140,199,137,1) 100%)" data-strategy="ambassador">
          	<div class="campaign-strategy-overlay"></div>
            <div class="campaign-strategy-inner">
              <div class="header">
                <h2>Ambassador<br>Programs</h2>
                <p>Team up with brands and<br>become an exclusive media partner!</p>
              </div>
              <div class="body">
                <span class="strategy-button">Explore</span>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
	<div id="marketplace" class="shop-wrapper">

		<div class="shop-header">
			<div class="shop-sidebar-toggles-container">
				<span id="shop-sort-toggle" class="shop-sidebar-toggle sas-sidebar-toggle no-js">
					<span>Sort</span>
					<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/shop/sort-toggle.svg'; ?>">
				</span>
				<span id="shop-filters-toggle" class="shop-sidebar-toggle sas-sidebar-toggle no-js">
					<span>Filter</span>
					<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/shop/filter-toggle.svg'; ?>">
				</span>
			</div>
		</div>

		<aside id="shop-filters-sidebar" class="shop-sidebar sas-sidebar no-js">
			<form id="shop-filter-form" class="shop-form"  method="post" action="<?php echo admin_url('admin-post.php');?>">
				<h2>Filter by</h2>
				<div class="filter-form-item js-accordion">
					<div class="form-item__header">
						<span>Category</span>
					</div>
					<div class="form-item__input">
						<div class="filter-form-interests">
							<?php foreach($categories as $category): ?>
								<label class="shop-check">
									<input type="checkbox" name="interests[]" value="<?php echo $category->slug; ?>" <?php echo in_array($category->slug, $filters['interests'])?'checked':''; ?>>
									<div class="check-display"><?php echo $category->name; ?></div>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
				</div>

				<div class="filter-form-item js-accordion">
					<div class="form-item__header">
						<span>Campaign Type</span>
					</div>
					<div class="form-item__input">
						<div class="filter-form-campaign-types">
							<label class="shop-check">
								<input type="checkbox" name="campaign-types[]" value="shoutout" <?php echo in_array('shoutout', $filters['campaign_strategies'])?'checked':'' ?>>
								<div class="check-display">ShoutOut</div>
							</label>
							<label class="shop-check">
								<input type="checkbox" name="campaign-types[]" value="giveaway" <?php echo in_array('giveaway', $filters['campaign_strategies'])?'checked':'' ?>>
								<div class="check-display">Giveaway</div>
							</label>
							<label class="shop-check">
								<input type="checkbox" name="campaign-types[]" value="mission" <?php echo in_array('mission', $filters['campaign_strategies'])?'checked':'' ?>>
								<div class="check-display">Mission</div>
							</label>
							<label class="shop-check">
								<input type="checkbox" name="campaign-types[]" value="ambassador" <?php echo in_array('ambassador', $filters['campaign_strategies'])?'checked':'' ?>>
								<div class="check-display">Ambassadors</div>
							</label>
						</div>
					</div>
				</div>

				<div class="filter-form-item js-accordion">
					<div class="form-item__header">
						<span>Country</span>
					</div>
					<div class="form-item__input">
						<div class="filter-form-countries">
							<label class="shop-check">
								<input type="radio" name="countries[]" value="CA" <?php echo $filters['country']=='CA'?'checked':'' ?>>
								<div class="check-display radio">Canada</div>
							</label>

							<label class="shop-check">
								<input type="radio" name="countries[]" value="US" <?php echo $filters['country']=='US'?'checked':'' ?>>
								<div class="check-display radio">United States</div>
							</label>

							<label class="shop-check">
								<input type="radio" name="countries[]" value="GB" <?php echo $filters['country']=='GB'?'checked':'' ?>>
								<div class="check-display radio">United Kingdom</div>
							</label>
						</div>
					</div>
				</div>

				<div class="filter-form-item">
					<label>Required Reach</label>
					<br>
					<div class="form-slider" data-min="0" data-max="10000" data-step="500">
						<input type="number" id="shop-filter-reach" class="slider-value" name="shop-filter-reach" value="<?php echo $filters['instagram_reach']!=''?$filters['instagram_reach']:10000; ?>" min="0" max="10000" step="500">
					</div>
				</div>

				<div class="filter-form-item">
					<label>Required Engagement</label>
					<br>
					<div class="form-slider" data-min="0" data-max="10" data-step="1" data-suffix="%">
						<input type="number" id="shop-filter-engagement" class="slider-value" name="shop-filter-engagement" value="<?php echo $filters['instagram_engagement_rate']!=''?$filters['instagram_engagement_rate']:10; ?>" min="0" max="10" step="1">
					</div>
				</div>

				<div class="filter-form-item">
					<label class="shop-check">
						<input type="checkbox" id="paid-campaign" name="paid-campaign" value="1" <?php echo isset($filters['paid_campaign'])?'checked':''; ?>>
						<div class="check-display">Paid Campaigns</div>
					</label>
				</div>

				<button class="sas-round-button primary small blue no-js-element">Filter</button>

				<input type="hidden" name="current_page" value="<?php echo $page ?>">
				<input type="hidden" name="current_params" value="<?php echo http_build_query($_GET); ?>">

				<input type="hidden" name="action" value="shop_filter_form">
				<?php wp_nonce_field('shop_filter_form', 'shop_filter_form_nonce'); ?>
			</form>

			<a href="<?php echo get_site_url() . '/shop/' ?>" class="sas-round-button primary small block blue">Reset Filters</a>
			<!-- TODO: Check if user has set interests -->
			<!-- TODO: Check if user has set their country -->
			<br>
			<a class="sas-round-button primary block small <?php echo is_user_logged_in() ?>" href="<?php echo is_user_logged_in()?$campaigns_for_me_url:get_site_url() . '/influencer-signup/'; ?>">Campaigns for me</a>
		</aside>

		<div class="shop-main-content">
			<aside id="shop-sort-sidebar" class="sas-sidebar no-js">
				<form id="shop-sort-form" class="shop-form" method="post" action="<?php echo admin_url('admin-post.php');?>">
					<h2>Sort by</h2>
					<label class="shop-check">
						<input type="radio" name="shop-sort[]" value="date" <?php echo !$sort['sortby']?'checked':''; ?>>
						<div class="check-display radio seperated">Newest</div>
					</label>
					<label class="shop-check">
						<input type="radio" name="shop-sort[]" data-sortby="_stock" data-order="desc" value="availability" <?php echo ($sort['sortby'] == '_stock' && $sort['order'] == 'desc')?'checked':''; ?>>
						<div class="check-display radio seperated">Availability</div>
					</label>
					<label class="shop-check">
						<input type="radio" name="shop-sort[]" data-sortby="<?php echo $selected_channel; ?>_reach" data-order="asc" value="reach_asc" <?php echo ($sort['sortby'] == $selected_channel.'_reach' && $sort['order'] == 'asc')?'checked':''; ?>>
						<div class="check-display radio seperated">Reach (Low - High)</div>
					</label>
					<label class="shop-check">
						<input type="radio" name="shop-sort[]" data-sortby="<?php echo $selected_channel; ?>_reach" data-order="desc" value="reach_desc" <?php echo ($sort['sortby'] == $selected_channel.'_reach' && $sort['order'] == 'desc')?'checked':''; ?>>
						<div class="check-display radio seperated">Reach (High - Low)</div>
					</label>
					<label class="shop-check">
						<input type="radio" name="shop-sort[]" data-sortby="<?php echo $selected_channel ?>_engagement_rate" data-order="asc" value="engagement_asc" <?php echo ($sort['sortby'] == $selected_channel.'_engagement_rate' && $sort['order'] == 'asc')?'checked':''; ?>>
						<div class="check-display radio seperated">Engagement (Low - High)</div>
					</label>
					<label class="shop-check">
						<input type="radio" name="shop-sort[]" data-sortby="<?php echo $selected_channel ?>_engagement_rate" data-order="desc" value="engagement_desc" <?php echo ($sort['sortby'] == $selected_channel.'_engagement_rate' && $sort['order'] == 'desc')?'checked':''; ?>>
						<div class="check-display radio seperated">Engagement (High - Low)</div>
					</label>
					<button class="sas-mini-form-button blue no-js-element">Sort</button>

					<input type="hidden" name="current_page" value="<?php echo $page ?>">
					<input type="hidden" name="current_params" value="<?php echo http_build_query($_GET); ?>">

					<input type="hidden" name="action" value="shop_sort_form">
					<?php wp_nonce_field('shop_sort_form', 'shop_sort_form_nonce'); ?>
				</form>
			</aside>

			<form id="shop-sort-select-form" method="post" action="<?php echo admin_url('admin-post.php');?>">
				<label>Sort by </label>
				<select id="shop-sort-select" name="shop-sort">
					<option>Newest</option>

					<option data-sortby="_stock" data-order="desc" value="availability" <?php echo ($sort['sortby'] == '_stock' && $sort['order'] == 'desc')?'selected':''; ?>>Availability</option>

					<option data-sortby="<?php echo $selected_channel; ?>_reach" data-order="desc" value="reach_desc" <?php echo ($sort['sortby'] == $selected_channel.'_reach' && $sort['order'] == 'desc')?'selected':''; ?>>Reach (High - Low)</option>

					<option data-sortby="<?php echo $selected_channel; ?>_reach" data-order="desc" value="reach_desc" <?php echo ($sort['sortby'] == $selected_channel.'_reach' && $sort['order'] == 'desc')?'selected':''; ?>>Reach (High - Low)</option>

					<option data-sortby="<?php echo $selected_channel ?>_engagement_rate" data-order="asc" value="engagement_asc" <?php echo ($sort['sortby'] == $selected_channel.'_engagement' && $sort['order'] == 'asc')?'selected':''; ?>>Engagement (Low - High)</option>

					<option data-sortby="<?php echo $selected_channel ?>_engagement_rate" data-order="desc" value="engagement_desc" <?php echo ($sort['sortby'] == $selected_channel.'_engagement' && $sort['order'] == 'desc')?'selected':''; ?>>Engagement (High - Low)</option>
				</select>
				<button class="sas-mini-form-button blue no-js-element">Sort</button>

				<input type="hidden" name="current_page" value="<?php echo $page ?>">
				<input type="hidden" name="current_params" value="<?php echo http_build_query($_GET); ?>">

				<input type="hidden" name="action" value="shop_sort_form">
				<?php wp_nonce_field('shop_sort_form', 'shop_sort_form_nonce'); ?>
			</form>

			<!-- Pagination -->
			<?php if($shop_campaigns['max_pages'] > 1): ?>
				<div class="shop-pagination no-js-element">
					<?php for($i=0; $i<$shop_campaigns['max_pages']; $i++): ?>
						<?php if($page==$i+1): ?>
							<span class="page-link current"><?php echo $i+1; ?></span>
						<?php else: ?>
							<span class="page-link"><a href="<?php echo get_site_url() . '/shop/' . ($i+1) . '/' . (count($_GET)?'?'.http_build_query($_GET):''); ?>" <?php echo $page==$i+1?'disabled="disabled"':''; ?>><?php echo $i+1; ?></a></span>
						<?php endif; ?>
					<?php endfor; ?>
				</div>
			<?php endif; ?>
			<!-- !Pagination -->

  		<div id="shop-campaigns">
				<?php echo $shop_campaigns['markup']; ?>
			</div>

			<!-- Pagination -->
			<?php if($shop_campaigns['max_pages'] > 1): ?>
				<div class="shop-pagination no-js-element">
					<?php for($i=0; $i<$shop_campaigns['max_pages']; $i++): ?>
						<?php if($page==$i+1): ?>
							<span class="page-link current"><?php echo $i+1; ?></span>
						<?php else: ?>
							<span class="page-link"><a href="<?php echo get_site_url() . '/shop/' . ($i+1) . '/' . (count($_GET)?'?'.http_build_query($_GET):''); ?>" <?php echo $page==$i+1?'disabled="disabled"':''; ?>><?php echo $i+1; ?></a></span>
						<?php endif; ?>
					<?php endfor; ?>
				</div>
			<?php endif; ?>
			<!-- !Pagination -->

			<div id="shop-infinite-scroller">
				<div class="loading-heart">
					<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/hearts/blue-heart-filled.svg'; ?>">
				</div>
			</div>
		</div>
	</div>
</div>
<?php

get_footer();
