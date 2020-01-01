<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
  //styles
  wp_enqueue_style(
  	'parent-style',
  	get_template_directory_uri() . '/style.css'
  );
  wp_enqueue_style(
  	'child-style',
  	get_stylesheet_directory_uri() . '/style.css',
  	array('parent-style')
  );
  wp_enqueue_style(
    'fontawesome',
    'https://use.fontawesome.com/releases/v5.0.9/css/all.css',
    array('parent-style')
  );
  wp_enqueue_style(
  	'index-style',
  	get_stylesheet_directory_uri() . '/styles/css/index.css',
  	array('parent-style'),
  	wp_get_theme()->Version
  );
  wp_enqueue_style(
    'jquery-ui-css', 
    'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css'
  );

  //scripts
  wp_enqueue_script(
  	'chart-js',
  	get_stylesheet_directory_uri() . '/js/inc/Chart.bundle.min.js'
  );
  wp_enqueue_script(
      'modernizr',
      get_stylesheet_directory_uri() . '/js/inc/modernizr.js'
  );
  wp_enqueue_script(
  	'jquery-ui-script',
  	get_stylesheet_directory_uri() . '/js/inc/jquery-ui.min.js'
  );
  wp_enqueue_script(
    'jquery-ui-touch-punch',
    get_stylesheet_directory_uri() . '/js/inc/jquery-ui-touch-punch.min.js'
  );
  wp_enqueue_script(
    'jquery-ui-slider-pips',
    get_stylesheet_directory_uri() . '/js/inc/jquery-ui-slider-pips.js'
  );
  wp_enqueue_script(
  	'scrollTo-script',
  	get_stylesheet_directory_uri() . '/js/inc/jquery.scrollTo.min.js'
  );
  wp_enqueue_script(
  	'validate',
  	get_stylesheet_directory_uri() . '/js/inc/jquery.validate.min.js'
  );
  wp_enqueue_script(
    'tokeninput',
    get_stylesheet_directory_uri() . '/js/inc/jquery.tokeninput.js'
  );
  wp_enqueue_script(
    'select2',
    get_stylesheet_directory_uri() . '/js/inc/select2.full.min.js'
  );
  wp_enqueue_script(
  	'popper',
  	get_stylesheet_directory_uri() . '/js/inc/popper.min.js'
  );
  wp_enqueue_script(
    'typed',
    get_stylesheet_directory_uri() . '/js/inc/typed.min.js'
  );
  wp_enqueue_script(
  	'bootstrap-bundle',
  	get_stylesheet_directory_uri() . '/js/inc/bootstrap.bundle.min.js'
  );
  wp_enqueue_script(
    'table-sorter',
    get_stylesheet_directory_uri() . '/js/inc/jquery.tablesorter.min.js'
  );
  wp_enqueue_script(
  	'main-script',
  	get_stylesheet_directory_uri() . '/js/main.js',
  	array(),
  	wp_get_theme()->Version
  );
	wp_register_script(
		'sas_forms_script',
		get_stylesheet_directory_uri(). '/js/forms.js',
		array('jquery'),
    	wp_get_theme()->Version
	);
	wp_enqueue_script('sas_forms_script');
  	wp_localize_script(
  		'sas_forms_script',
  		'sas_forms_data',
  		array( 'sas_ajax_url' => admin_url( 'admin-ajax.php' ) )
  	);
}

add_action('admin_enqueue_scripts', 'theme_admin_enqueue_scripts');
function theme_admin_enqueue_scripts() {
	wp_register_script(
		'admin_script',
		get_stylesheet_directory_uri() . '/js/admin.js',
		array('jquery'),
		wp_get_theme()->Version
	);
	wp_enqueue_script('admin_script');
  	wp_localize_script(
  		'admin_script',
  		'admin_forms_data',
  		array( 'admin_ajax_url' => admin_url( 'admin-ajax.php' ) )
  	);
    wp_enqueue_script(
    	'validate',
    	get_stylesheet_directory_uri() . '/js/inc/jquery.validate.min.js'
    );
}

require_once 'vendor/autoload.php';

include('includes/function-emails.php');
include('includes/function-admin.php');
include('includes/function-shortcodes.php');
include('includes/function-theme-setup.php');
include('includes/function-cpt.php');
include('includes/function-hooks.php');
include('includes/function-post.php');
include('includes/function-ajax.php');

// Adding support for page template subdirectory
add_filter( 'page_template_hierarchy', 'page_template_add_subdir' );
function page_template_add_subdir( $templates = array() ) {
    if( empty( $templates ) || ! is_array( $templates ) || count( $templates ) < 3 )
        return $templates;

    $page_tpl_idx = 0;
    if( $templates[0] === get_page_template_slug() ) {
        $page_tpl_idx = 1;
    }

    $page_tpls = array( 'page-templates/' . $templates[$page_tpl_idx] );

    if( $templates[$page_tpl_idx] === urldecode( $templates[$page_tpl_idx + 1] ) ) {
        $page_tpls[] = 'page-templates/' . $templates[$page_tpl_idx + 1];
    }

    array_splice( $templates, $page_tpl_idx, 0, $page_tpls );

    return $templates;
}

// Load a page of campaigns for the shop based on filters/sorting/pagination
function get_shop_campaigns($filters, $sort, $page = 1) {

  $return = array(
    'markup' => '',
    'max_pages' => '',
  );

  $query_params = array();

  // Instagram Reach
  if(isset($filters['instagram_reach']) && $filters['instagram_reach'] < 10000) {
    $query_params[] = array(
      'key' => 'instagram_reach',
      'value' => $filters['instagram_reach'],
      'type' => 'numeric',
      'compare' => '<=',
    );
  }
  // Instagram Engagement
  if(isset($filters['instagram_engagement_rate'])) {
    $query_params[] = array(
      'key' => 'instagram_engagement_rate',
      'value' => $filters['instagram_engagement_rate'],
      'type' => 'numeric',
      'compare' => '<=',
    );
  }
  // Campaign Strategies
  if(isset($filters['campaign_strategies'])) {
    $query_params[] = array(
      'key' => 'campaign_strategy',
      'value' => $filters['campaign_strategies'],
    );
  }
  // Interests
  if(isset($filters['interests']) && !empty($filters['interests'])) {
    $tax_query = array(
      'relation' => 'AND',
      array(
        'taxonomy' => 'product_cat',
        'field' => 'slug',
        'terms' => $filters['interests'],
      )
    );
  }
  // Interests
  if(isset($filters['country'])) {
    $query_params[] = array(
      'key' => 'countries',
      'value' => $filters['country'],
      'compare' => 'LIKE',
    );
  }
  // Channel
  if(isset($filters['channel'])) {
    $query_params[] = array(
      'key' => 'shoutout_channels',
      'value' => $filters['channel'],
      'compare' => 'LIKE',
    );
  }
  if(isset($filters['paid_campaign']) && $filters['paid_campaign']) {
    $query_params[] = array(
      'key' => 'paid_campaign',
      'value' => true,
      'compare' => '=',
    );
  }

  $query = array(
    'posts_per_page' => 12,
    'post_type' => 'product',
    'post_status' => 'publish',
    'paged' => $page,
    'meta_query' => $query_params,
    'tax_query' => isset($tax_query)?$tax_query:'',
  );

  if(isset($sort['sortby']) && $sort['sortby']) {
    $query['meta_key'] = $sort['sortby'];
    $query['orderby'] = 'meta_value_num';
    $query['order'] = $sort['order'];
  }

  $campaign_query = new WP_Query($query);
  $max_pages = $campaign_query->max_num_pages;

  $return['max_pages'] = $max_pages;

  ob_start();

  ?>

  <div class="shop-campaigns-page">
    <?php if(isset($campaign_query->posts) && $campaign_query->posts) : ?>
      <?php foreach($campaign_query->posts as $campaign): 

        $campaign_id = $campaign->ID;
        $campaign_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($campaign_id), 'medium')[0];
        $campaign_countries = get_post_meta($campaign_id, 'countries', true);
        $campaign_strategy = get_post_meta($campaign_id, 'campaign_strategy', true);
        $campaign_title = $campaign->post_title;
        $campaign_brand = get_campaign_brand($campaign_id);
        $campaign_post_date = date('M d Y',strtotime($campaign->post_date));
        $campaign_slug = get_post_field('post_name', $campaign_id);
        $paid_campaign = get_post_meta($campaign_id, 'paid_campaign', true);
        $password_protected = post_password_required($campaign_id);
        
        // TODO: Choose reach and engagement based on selected channel
        $campaign_reach = get_post_meta($campaign_id, 'instagram_reach', true);
        $campaign_engagement = get_post_meta($campaign_id, 'instagram_engagement_rate', true);
        // !TODO //

        $product = wc_get_product($campaign_id);

        $campaign_interests = get_the_terms($campaign_id, 'product_cat');

        $campaign_no_stock = !$product->is_in_stock();

        $coming_soon=false;
        if($password_protected || $campaign_no_stock )
          $coming_soon = true;
      ?>
        <a <?php echo !$password_protected?'href="'.get_site_url().'/product/'.$campaign_slug.'"':''; ?> class="shop-campaign <?php echo $coming_soon?'coming-soon':''; ?>">
          <div class="shop-campaign-inner">
            <div class="coming-soon-overlay"></div>
            <div class="campaign-header">
              <?php if($password_protected): ?>
                <div class="coming-soon-stamp">
                  <span>Coming Soon</span>
                </div>
              <?php elseif($campaign_no_stock): ?>
                <div class="coming-soon-stamp blue">
                  <span>Coming Back Soon</span>
                </div>
              <?php endif; ?>
              <div class="campaign-image" style="background-image:url(<?php echo esc_url($campaign_thumbnail); ?>)">
                <div class="flags">
                  <?php if(is_array($campaign_countries)): ?>
                    <?php foreach($campaign_countries as $country): ?>
                      <div class="flag">
                        <img width="20" height="20" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/flags/'.$country.'.png'; ?>">
                      </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="flag">
                      <img width="20" height="20" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/flags/globe.svg' ?>">
                    </div>
                  <?php endif; ?>
                </div>
                <div class="campaign-strategy-desktop">
                  <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/shop/'.$campaign_strategy.'.svg'; ?>">
                  <span class="label"><?php echo ucfirst($campaign_strategy); ?></span>
                </div>
              </div>
                <div class="campaign-strategy-mobile">
                  <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/shop/'.$campaign_strategy.'.svg'; ?>">
                  <span class="label"><?php echo ucfirst($campaign_strategy); ?></span>
                </div>
            </div>

            <div class="campaign-body">
              <span class="campaign-title"><?php echo esc_html($campaign_title); ?></span>
              
              <div class="campaign-brand">By <span class="brand-name"><?php echo esc_html($campaign_brand->brand_name); ?></span></div>


              <?php if($paid_campaign): ?>
                <div class="paid-campaign">
                  <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/shop/paid-check.svg'; ?>">
                  <span>Paid Campaign</span>
                </div>
              <?php endif; ?>

              <div class="campaign-requirements">
                <div class="reach-engagement">
                  <div class="reach">
                    <span class="title">Reach:</span>
                    <span class="value"><?php echo $campaign_reach?esc_html($campaign_reach):'N/A'; ?></span>
                  </div>
                  <div class="engagement">
                    <span class="title">Engagement:</span>
                    <span class="value"><?php echo $campaign_engagement?esc_html($campaign_engagement).'%':'N/A'; ?></span>
                  </div>
                </div>
                <div class="interests">
                  <span class="title">Category:</span>
                  <div class="interests-container">
                    <?php for($i=0;$i<count($campaign_interests)&&$i<3;$i++): ?>
                      <?php if($i<2) : ?>
                        <div class="interest"><?php echo $campaign_interests[$i]->name; ?></div>
                      <?php else: ?>
                        <div class="interest-more">+More</div>
                      <?php endif; ?>
                    <?php endfor; ?>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </a>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="shop-no-campaigns">
        <span>We couldn't find any campaigns.</span>
      </div>
    <?php endif; ?>
  </div>
  <?php

  $return['markup'] = ob_get_clean();

  return $return;
}

// Woocommerce custom product filters
add_action('woocommerce_product_query', 'custom_field_shop_filters', 10, 1);

$GLOBALS['my_query_filters'] = array(
	'instagram_reach',
	'facebook_reach',
	'twitter_reach',
	'instagram_engagement_rate',
	'age',
	'location',
);

function custom_field_shop_filters( $query ) {
	if( is_admin() ) return;

	$meta_query = $query->get('meta_query');

	foreach( $GLOBALS['my_query_filters'] as $name ) {

		if( empty( $_GET[$name] ) ) {

			continue;
		}

		if( $name == 'instagram_reach' || $name == 'twitter_reach' || $name == 'facebook_reach' ) {

			$value = $_GET[$name];
			$compare = '<=';
			$type = 'NUMERIC';

		} else if ( $name == 'location' ) {

			$value = $_GET[$name];
			$compare = 'LIKE';
			$type = 'CHAR';
		}

		$meta_query[] = array(
			'key' => $name,
			'value' => $value,
			'compare' => $compare,
			'type' => $type,
		);
	}

	$query->set('meta_query', $meta_query);
}

/**
 * Calculate count of shoutout ratings, total sum of shoutout ratings
 * and average ShoutOut score for a given influencer
 */
function influencer_get_score_data( $influencer_id ) {

	$customer_orders = get_posts( array(
	    'numberposts' => -1,
	    'meta_key'    => '_customer_user',
	    'meta_value'  => $influencer_id,
	    'post_type'   => wc_get_order_types(),
	    'post_status' => array_keys( wc_get_order_statuses() ),
	) );

	$rating_count = 0;
	$ratings_sum = 0;
	$average_score = 0;

	// Get each rating for each socail channel
	foreach( $customer_orders as $order ) {

		$order_id = $order->ID;

		$channels = array( 'instagram', 'facebook', 'twitter' );

		foreach( $channels as $channel ) {

			$rating = get_post_meta( $order_id, $channel . '_shoutout_rating', true );

			if( $rating !== '' ) {

				$ratings_sum += $rating;

				$rating_count++;
			}
		}
	}

	if( $rating_count !== 0 ) {

		$average_score = ceil( $ratings_sum / $rating_count );
	}

	$score_data = array(
		'rating_count' => $rating_count,
		'ratings_sum' => $ratings_sum,
		'average_score' => $average_score,
	);

	return $score_data;
}

/**
 * Check if influencer qualifies for elite program
 *
 * @return bool
 */
function influencer_elite_qualification_check( $influencer_id ) {

	$elite = false;

	$score_data = influencer_get_score_data( $influencer_id );

	$ratings_count = $score_data['rating_count'];
	$average_score = $score_data['average_score'];
	$authentique_estimated_real_follower_percentage = get_user_meta( $influencer_id, 'authentique_estimated_real_follower_percentage', true );

	if( $ratings_count >= 4 && $average_score >= 4 && $authentique_estimated_real_follower_percentage >= 80 ) {

		$elite = true;
	}

	return $elite;
}

/**
 * Get an array of orders by campaign id
 */
add_action('init', 'get_orders_by_campaign_id');
function get_orders_by_campaign_id( $campaign_id, $date_range = false ) {

	global $wpdb;
  
  $date_range_string = "";

  if($date_range && isset($date_range['from']) && isset($date_range['to'])) {
    $start_time = strtotime($date_range['from']);
    $end_time = strtotime($date_range['to'])?strtotime($date_range['to']):strtotime('now');

    $date_range_string = 
      "AND posts.post_date >= '".date('Y-m-d H:i:s', $start_time)."'"
      . "AND posts.post_date <= '".date('Y-m-d H:i:s', $end_time)."'";
  }

	$order_querystr = "
		SELECT order_items.order_id
    FROM {$wpdb->prefix}woocommerce_order_items as order_items
    LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
    LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
    WHERE posts.post_type = 'shop_order'
    AND order_items.order_item_type = 'line_item'
    AND order_item_meta.meta_key = '_product_id'
    AND order_item_meta.meta_value = '$campaign_id'"
    .$date_range_string;

	$orders = $wpdb->get_col( $order_querystr );

	return $orders;
}

/**
 * Get campaign ID from order ID
 *
 * @param    int  $order_id ID of the order
 * @return 	 int
 *
 */
function get_order_campaign( $order_id ) {

	$order = wc_get_order( $order_id );

	$order_items = $order->get_items();
	$item_product = reset( $order_items );
	$product_data = $item_product->get_data();

	$campaign_id = $product_data['product_id'];

	return $campaign_id;
}

/**
 * Get influencer ID from order ID
 *
 * @param    int  $order_id ID of the order
 *
 * @return 	 int
 */
function get_order_influencer( $order_id ) {

	$order = wc_get_order( $order_id );

	$data = $order->get_data();

	$influencer_id = $data['customer_id'];

	return $influencer_id;
}

/**
 * Get campaign brand
 *
 * @param 	int  $campaing_id ID of the campaign
 *
 * @return 	array
 */
function get_campaign_brand( $campaign_id ) {

	$brand = (object) array(
		'ID' => '',
		'brand_name' => '',
    'brand_website' => '',
    'brand_story' => '',
	);

	$brand->ID = get_post_meta( $campaign_id, 'brand', true );

  $post = get_post($brand->ID);

	if( $brand->ID ) {
		$brand->brand_name = get_the_title($brand->ID);
    $brand->brand_website = get_post_meta($brand->ID, 'brand_website', true);
    $brand->brand_story = $post->post_content;
	}

	return $brand;
}

/**
 * Get brand user entries by user id
 *
 * @param 	int  $user_id
 *
 * @return 	array
 */
function get_brand_user_entries_by_user_id( $user_id ) {

	$brand_user_entries = array();

	$brand_user_posts = get_posts(array(
		'post_type' => 'brand_user',
		'meta_key' => 'user',
		'meta_value' => $user_id,
		'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit'),
        'numberposts' => -1,
	));

	if($brand_user_posts) {
		foreach($brand_user_posts as $entry) {

			$brand_id = get_post_meta( $entry->ID, 'brand', true );
      $status = get_post_status($brand_id);
      if($status && $status != 'trash') {
        $role = get_post_meta( $entry->ID, 'role', true );
        $brand_user_entries[] = array(
          'brand' => $brand_id,
          'role' => $role,
          'email_opt_out' => false,
        );
      }
		}
	}

	return $brand_user_entries;
}

/**
 * Get brand user entries by user id
 *
 * @param 	int  $user_id
 *
 * @return 	array
 */
function get_brand_user_entries_by_brand_id( $brand_id ) {

	$brand_user_entries = array();

	$brand_user_posts = get_posts(array(
		'post_type' => 'brand_user',
		'meta_key' => 'brand',
		'meta_value' => $brand_id,
		'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit'),
        'numberposts' => -1,
	));

	if($brand_user_posts) {
		foreach($brand_user_posts as $entry) {
			$user_id = get_post_meta( $entry->ID, 'user', true );

			$user = get_userdata($user_id);

			if($user) {
				$role = get_post_meta( $entry->ID, 'role', true );
				$brand_user_entries[] = array(
                    'entry_id' => $entry->ID,
					'user' => $user_id,
					'role' => $role,
					'email_opt_out' => false,
				);
			}
		}
	}

	return $brand_user_entries;
}

function check_brand_user_role($brand_id, $user_id) {

	$is_a_member = false;

	$brand_user_entries = get_brand_user_entries_by_brand_id($brand_id);

	foreach($brand_user_entries as $entry) {
		if($entry['user'] == $user_id) {
			$is_a_member = $entry['role'];
		}
	}

	return $is_a_member;
}

function get_brand_contacts($brand_id) {
  $contacts = array();

  if($brand_id) {
    $brand_user_entries = get_brand_user_entries_by_brand_id($brand_id);

    foreach($brand_user_entries as $entry) {

      if(!$entry['email_opt_out']) {

        $user_data = get_userdata($entry['user']);
        $email = $user_data->user_email;
        $name = $user_data->first_name . ' ' . $user_data->last_name;
        $phone = get_user_meta($user_data->ID, 'contact_phone', true);
        $email_opt_out = get_user_meta($user_data->ID, 'email_opt_out', true);

        $contacts[] = array(
          'ID' => $user_data->ID,
          'email' => $email,
          'name' => $name,
          'phone' => $phone,
          'email_opt_out' => $email_opt_out,
        );
      }
    }
  }

  return $contacts;
}

/**
 * Get campaign shoutout data by campaign id
 *
 * @param 	int  $campaign_id ID of the campaign
 *
 * @return 	array
 */
function get_campaign_shoutout_data( $campaign_id, $date_range = false ) {

  $campaign_instagram_reach = get_post_meta($campaign_id, 'instagram_reach', true);
  $campaign_instagram_engagement = get_post_meta($campaign_id, 'instagram_engagement_rate', true)?get_post_meta($campaign_id, 'instagram_engagement_rate', true):1;

  $goal_date_ranges = array();

  $campaign_goals = get_posts(array(
    'post_type' => 'campaign_goal',
    'post_status' => 'any',
    'numberposts' => -1,
    'meta_key' => 'campaign',
    'meta_value' => $campaign_id,
    'orderby' => array(
      'start_date' => 'DESC',
    ),
  ));

  $goal_counter = 0;
  foreach($campaign_goals as $goal) {
    $goal_counter++;
    
    $goal_start_date = get_post_meta($goal->ID, 'start_date', true);
    $goal_end_date = get_post_meta($goal->ID, 'end_date', true);
    $goal = get_post_meta($goal->ID, 'goal', true);

    if($date_range) {
      $date_range_from = strtotime($date_range['from'])>strtotime($goal_start_date)?$date_range['from']:$goal_start_date;
      
      $goal_date_ranges[] = array(
        'from' => $date_range_from,
        'to' => $goal_end_date?$goal_end_date:date('Y-m-d H:i:s'),
        'goal' => $goal,
      );
    } else {
      $goal_date_ranges[] = array(
        'from' => $goal_start_date,
        'to' => $goal_end_date?$goal_end_date:date('Y-m-d H:i:s'),
        'goal' => $goal,
      );
    }

    if(isset($campaign_goals[$goal_counter])) {
      $next_goal_id = $campaign_goals[$goal_counter]->ID;
      $next_goal_start_date = get_post_meta($next_goal_id, 'start_date', true);
      
      if(strtotime($next_goal_start_date)>strtotime($goal_end_date)) {
        $goal_date_ranges[] = array(
          'from' => $goal_end_date,
          'to' => $next_goal_start_date,
          'goal' => 0,
        );
      }
    }
  }

  if(!$date_range) {
    $date_range = array(
      'from' => $goal_date_ranges[0]['from'],
      'to' => $goal_date_ranges[(count($goal_date_ranges)-1)]['to'],
    );
  }

  $goal_reach = 0;
  foreach($goal_date_ranges as $goal_date_range) {
    $period_time = strtotime($goal_date_range['to']) - strtotime($goal_date_range['from']);
    $period = $period_time/60/60/24;

    $goal = $goal_date_range['goal']*$period;

    $goal_reach += $goal*$campaign_instagram_reach;
  }

	$channels = get_post_meta( $campaign_id, 'shoutout_channels', true )?get_post_meta( $campaign_id, 'shoutout_channels', true ):array();
	$orders = get_orders_by_campaign_id( $campaign_id, $date_range );

	$total_influencers = count($orders);
  $total_enrolled = 0;
  $total_activated = 0;
  $total_completed = 0;
	$total_reach = 0;
  $total_reaches = array();
  $projected_reach = 0;
  $projected_reaches = array();
  $campaign_total_reach = 0;
  $total_content_value = 0;
	$engagement_reach_matrix = array();
	$geographic_data = array(
		'canada' => array(
			'count' => 0,
			'percent_sum' => 0,
			'percent_average' => 0,
		),
		'us' => array(
			'count' => 0,
			'percent_sum' => 0,
			'percent_average' => 0,
		),
		'uk' => array(
			'count' => 0,
			'percent_sum' => 0,
			'percent_average' => 0,
		),
		'other' => array(
			'count' => 0,
			'percent_sum' => 0,
			'percent_average' => 0,
		),
	);

  foreach($channels as $channel) {
    $total_reaches[$channel] = 0;
    $projected_reaches[$channel] = 0;
    $campaign_reach = get_post_meta($campaign_id, $channel.'_reach', true);
    $campaign_total_reach += $campaign_reach;
  }

	foreach( $orders as $order_id ) {

		$order = wc_get_order( $order_id );
    $order_status = $order->get_status();
    $shoutout_status = get_order_shoutout_status($order_id);
    $order = $order->get_data();
		$influencer_id = $order['customer_id'];
    $all_links_submitted = true;
    $instagram_reach = get_user_meta($influencer_id, 'social_prism_user_instagram_reach', true);
    $instagram_engagement = get_user_meta( $influencer_id, 'instagram-engagement-rate', true );
    
    // Enrolled Orders Data
    if($order_status != 'cancelled' && $order_status != 'refunded') {
        $total_enrolled++;

      // Activated Orders Data
      if($shoutout_status != '') {
        $total_activated++;
      }
    }
    foreach( $channels as $channel ) {
      $channel_link = get_post_meta($order_id, $channel.'_url', true);
      if(!$channel_link) {
        $all_links_submitted = false;
      }
    }

    // Completed Orders Data
    if($all_links_submitted) {
      $total_completed++;

      // Content Value
      if($instagram_reach<2500) {
        $content_value = 50;
      } else if($instagram_reach<5000) {
        $content_value = 100;
      } else if($instagram_reach<10000) {
        $content_value = 150;
      } else if($instagram_reach<15000) {
        $content_value = 200;
      } else if($instagram_reach<20000) {
        $content_value = 250;
      } else if($instagram_reach<25000) {
        $content_value = 300;
      } else if($instagram_reach<50000) {
        $content_value = 350;
      } else if($instagram_reach<100000) {
        $content_value = 500;
      } else {
        $content_value = 1000;
      }
      $total_content_value += $content_value;

      // Engagement Weighted Average
      $engagement_reach_matrix[] = array('engagement' => $instagram_engagement, 'reach' => $instagram_reach);

      foreach($channels as $channel) {
        $influencer_reach = get_user_meta($influencer_id, 'social_prism_user_'.$channel.'_reach', true);
        $campaign_reach = get_post_meta($campaign_id, $channel.'_reach', true);
        $total_reaches[$channel]+=$influencer_reach;
        $total_reach+=$influencer_reach;
        $projected_reaches[$channel]+=$campaign_reach;
      }
    }

		$country_1 = get_user_meta($influencer_id, 'inf_top_country_1', true);
		$country_2 = get_user_meta($influencer_id, 'inf_top_country_2', true);
		$country_3 = get_user_meta($influencer_id, 'inf_top_country_3', true);
		$country_percentage_1 = (float)get_user_meta($influencer_id, 'inf_top_country_percentage_1', true);
		$country_percentage_2 = (float)get_user_meta($influencer_id, 'inf_top_country_percentage_2', true);
		$country_percentage_3 = (float)get_user_meta($influencer_id, 'inf_top_country_percentage_3', true);

		$country_data = array(
			$country_1 => $country_percentage_1,
			$country_2 => $country_percentage_2,
			$country_3 => $country_percentage_3,
		);

		foreach( $country_data as $country => $percentage ) {
			if( $country == 'Canada' ) {

				$geographic_data['canada']['count']++;
				$geographic_data['canada']['percent_sum'] += $percentage;

			} else if ( $country == 'United States' ) {

				$geographic_data['us']['count']++;
				$geographic_data['us']['percent_sum'] += $percentage;

			} else if ( $country == 'United Kingdom' ) {

				$geographic_data['uk']['count']++;
				$geographic_data['uk']['percent_sum'] += $percentage;

			} else {

				$geographic_data['other']['count']++;
				$geographic_data['other']['percent_sum'] += $percentage;
			}
		}
	}

  // Engagement Weighted Average
  $engagement_wghtavg_numerator = 0;
  $engagement_wghtavg_denominator = 0;
  foreach( $engagement_reach_matrix as $row ) {
    $engagement_wghtavg_numerator += ((int)$row['reach'] * (int)$row['engagement']);
    $engagement_wghtavg_denominator += $row['reach'];
  }
  $engagement_wghtavg = $engagement_wghtavg_denominator?$engagement_wghtavg_numerator/$engagement_wghtavg_denominator:0;

  // Geographic Data
	$geographic_data['canada']['percent_average'] = ( $geographic_data['canada']['count'] > 0 ? round( $geographic_data['canada']['percent_sum'] / $geographic_data['canada']['count'], 2 ) : 0 );
	$geographic_data['us']['percent_average'] = ( $geographic_data['us']['count'] > 0 ? round( $geographic_data['us']['percent_sum'] / $geographic_data['us']['count'], 2 ) : 0 );
	$geographic_data['uk']['percent_average'] = ( $geographic_data['uk']['count'] > 0 ? round( $geographic_data['uk']['percent_sum'] / $geographic_data['uk']['count'], 2 ) : 0 );
	$geographic_data['other']['percent_average'] = ( $geographic_data['other']['count'] > 0 ? round( $geographic_data['other']['percent_sum'] / $geographic_data['other']['count'], 2 ) : 0 );

	$data = array(
    'date_range' => $date_range,
		'total_opted_in' => $total_influencers,
    'total_enrolled' => $total_enrolled,
    'total_activated' => $total_activated,
    'total_completed' => $total_completed,
    'projected_reach' => $campaign_total_reach*$total_completed,
    'goal_reach' => $goal_reach,
		'total_reach' => $total_reach,
		'content_value' => $total_content_value,
    'total_reaches' => $total_reaches,
		'projected_reaches' => $projected_reaches,
		'engagement_wghtavg' => $engagement_wghtavg,
    'projected_engagement_wghtavg' => $campaign_instagram_engagement,
		'geographic_data' => $geographic_data,
	);
	return $data;
}

/**
 * Get age from user birthdate
 *
 * @param string $birthdate birthdate in the form of m/d/Y
 */
function calculate_user_age( $birthdate ) {

	//explode the date to get month, day and year
  if($birthdate) {
    $birthdate = explode("/", $birthdate);

    //get age from date or birthdate
    $age = (date("md", date("U", mktime(0, 0, 0, (int)$birthdate[0], $birthdate[1], $birthdate[2]))) > date("md")
    ? ((date("Y") - $birthdate[2]) - 1)
    : (date("Y") - $birthdate[2]));
  } else {
    $age = false;
  }

	return $age;
}

/**
 * Get age from user birthdate
 *
 * @param string $birthdate birthdate in the form of m/d/Y
 */
function influencer_profile_card( $influencer_id, $is_public, $viewer_is_brand ) {

$influencer_data = get_userdata( $influencer_id );
$nicename = $influencer_data->data->user_nicename;
$display_name = $influencer_data->display_name;
$email = $influencer_data->user_email;
$first_name = get_user_meta( $influencer_id, 'first_name', true );
$last_name = get_user_meta( $influencer_id, 'last_name', true );
$country = get_user_meta( $influencer_id, 'social_prism_user_location', true );
$region = get_user_meta( $influencer_id, 'social_prism_user_state_province', true );

$elite_qualification = influencer_elite_qualification_check( $influencer_id );

$score_data = influencer_get_score_data( $influencer_id );
$average_score = $score_data['average_score'];

// Audience demographics
$top_country_1 = get_user_meta( $influencer_id, 'inf_top_country_1', true );
$top_country_2 = get_user_meta( $influencer_id, 'inf_top_country_2', true );
$top_country_3 = get_user_meta( $influencer_id, 'inf_top_country_3', true );

$top_country_percentage_1 = get_user_meta( $influencer_id, 'inf_top_country_percentage_1', true );
$top_country_percentage_2 = get_user_meta( $influencer_id, 'inf_top_country_percentage_2', true );
$top_country_percentage_3 = get_user_meta( $influencer_id, 'inf_top_country_percentage_3', true );

$female_percentage = get_user_meta( $influencer_id, 'inf_female_percentage', true );
$male_percentage = get_user_meta( $influencer_id, 'inf_male_percentage', true );
$non_binary_percentage = get_user_meta( $influencer_id, 'inf_non_binary_percentage', true );

// Social
$profile_picture = get_user_meta( $influencer_id, 'inf_instagram_profile_picture', true );
$instagram_handle = get_user_meta( $influencer_id, 'social_prism_user_instagram', true );
$instagram_reach = get_user_meta( $influencer_id, 'social_prism_user_instagram_reach', true );
$instagram_engagement = get_user_meta( $influencer_id, 'instagram-engagement-rate', true );
$instagram_total_likes = get_user_meta( $influencer_id, 'inf_instagram_total_likes', true );
$instagram_total_comments = get_user_meta( $influencer_id, 'inf_instagram_total_comments', true );
$authentique_audit_completed = get_user_meta( $influencer_id, 'authentique_audit_completed', true );
$authentique_average_likes = get_user_meta( $influencer_id, 'authentique_average_likes', true );
$authentique_average_comments = get_user_meta( $influencer_id, 'authentique_average_comments', true );
$authentique_expected_likes = get_user_meta( $influencer_id, 'authentique_expected_likes', true );
$authentique_expected_comments = get_user_meta( $influencer_id, 'authentique_expected_comments', true );
$facebook_reach = get_user_meta( $influencer_id, 'social_prism_user_facebook_reach', true );
$twitter_reach = get_user_meta( $influencer_id, 'social_prism_user_twitter_reach', true );

// Shipping info
$shipping_first_name = get_user_meta( $influencer_id, 'shipping_first_name', true );
$shipping_last_name = get_user_meta( $influencer_id, 'shipping_last_name', true );
$shipping_company = get_user_meta( $influencer_id, 'shipping_company', true );
$shipping_country = get_user_meta( $influencer_id, 'shipping_country', true );
$shipping_state = get_user_meta( $influencer_id, 'shipping_state', true );
$shipping_city = get_user_meta( $influencer_id, 'shipping_city', true );
$shipping_postcode = get_user_meta( $influencer_id, 'shipping_postcode', true );
$shipping_address_1 = get_user_meta( $influencer_id, 'shipping_address_1', true );
$shipping_address_2 = get_user_meta( $influencer_id, 'shipping_address_2', true );

ob_start();
?>
<div class="influencer-profile-card-container">
	<div class="influencer-profile-card">

		<h3><?php echo esc_html( $first_name, ' ', $last_name ); ?></h3>

		<?php if( !$is_public ) : ?>

			<p><a href="<?php echo get_site_url() . '/influencer/' . esc_attr($nicename); ?>">View public profile</a></p>

		<?php endif; ?>

		<?php echo ( $viewer_is_brand ? '<p>' . esc_html( $email ) . '</p>' : '' ); ?>

		<p>ShoutOut Score</p>

		<p>
			<?php if ( $average_score !== 0 ) : ?>

				<?php  for ( $x = 0; $x < $average_score; $x++ ) : ?>
					<img class="heart-icon" width="20" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
				<?php endfor; ?>

				<?php  for ( $x = 5; $x > $average_score; $x-- ) : ?>
					<img class="heart-icon" width="20" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
				<?php endfor; ?>

			<?php else : ?>

				N/A

			<?php endif; ?>
		</p>

		<?php if( $elite_qualification ) : ?>

			<div class="inf-elite-badge">
				<img width="110" height="66" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/elite-cursive-small.png'; ?>">
			</div>

		<?php endif; ?>

			<?php if( $profile_picture !== '' && check_remote_file($profile_picture)) : ?>

				<?php
					$inf_image_chart = '';

					if ( $instagram_reach !== '' ) {
						$inf_image_chart = get_stylesheet_directory_uri() . '/images/icons/profile-image-chart-instagram.svg';
						if ( $facebook_reach !== '' && $twitter_reach !== '' ) {
							$inf_image_chart = get_stylesheet_directory_uri() . '/images/icons/profile-image-chart-instagram-facebook-twitter.svg';
						} else if ( $facebook_reach !== '' ) {
							$inf_image_chart = get_stylesheet_directory_uri() . '/images/icons/profile-image-chart-instagram-facebook.svg';
						} else if( $twitter_reach !== '' ) {
							$inf_image_chart = get_stylesheet_directory_uri() . '/images/icons/profile-image-chart-instagram-twitter.svg';
						}
					}
				?>
				<p>
					<img class="inf-profile-image-chart" src="<?php echo $inf_image_chart; ?>">

					<div class="inf-profile-image">
						<a target="_blank" href="<?php echo 'https://www.instagram.com/' . esc_attr( $instagram_handle ); ?>"><img src="<?php echo $profile_picture; ?>"></a>
					</div>
				</p>
			<?php endif; ?>

			<?php if( $instagram_reach !== '' ) : ?>

				<p>Total Reach - <?php echo esc_attr( absint($instagram_reach) + absint($facebook_reach) + absint($twitter_reach) ) ?></p>

			<?php endif; ?>

		<?php if( $region !== '' && $country !== '' ) : ?>

			<div class="inf-location-details">
				<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/location-pin.svg'; ?>">
				<span> <?php echo esc_attr( $region . ', ' . $country ); ?> </span>

				<?php if( !$is_public ) : ?>

					<a href="<?php echo get_site_url(); ?>/my-account/personal"><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/edit.svg'; ?>"></a>

				<?php endif; ?>

			</div>

		<?php endif; ?>

		<?php if ( $instagram_engagement !== '' && $instagram_total_likes !== '' && $instagram_total_comments !== '' ) : ?>
			<div class="influencer-profile-section-card">
				<h5><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/instagram-coral.svg'; ?>"> Instagram Stats</h5>
				<span><?php echo esc_attr( $instagram_engagement ); ?>% Engagement rate/post</span>
				<br><br>
				<table class="profile-card-likes-comments">
					<tr>
						<th></th>
						<th><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/likes-heart.svg'; ?>"><br>Likes</th>
						<th><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/comments-bubble.svg'; ?>"><br>Comments</th>
					</tr>
					<?php if( $authentique_audit_completed ) : ?>
						<tr>
							<th>Avg. per post</th>
							<td><?php echo esc_html((int)$authentique_average_likes); ?></td>
							<td><?php echo esc_html((int)$authentique_average_comments); ?></td>
						</tr>
						<tr>
							<th>Target</th>
							<td><?php echo esc_html((int)$authentique_expected_likes); ?></td>
							<td><?php echo esc_html((int)$authentique_expected_comments); ?></td>
						</tr>
					<?php endif; ?>
					<tr>
						<th>Lifetime</th>
						<td><?php echo esc_html( $instagram_total_likes ); ?></td>
						<td><?php echo esc_attr( $instagram_total_comments ); ?></td>
					</tr>
				</table>
			</div>
			<br><br>

		<?php endif; ?>

		<?php if( $female_percentage !== '' && $male_percentage !== '' ) : ?>

		<div class="influencer-profile-section-card">
			<h5>Audience</h5>
			<div class="row">
				<div class="col-4 offset-2 inf-audience-gender-info">
					<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/woman.svg'; ?>">
					<div>
						<span>Female</span><br>
						<span><?php echo esc_attr( $female_percentage ); ?>%</span>
					</div>
				</div>
				<div class="col-4 inf-audience-gender-info">
					<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/man.svg'; ?>">
					<div>
						<span>Male</span><br>
						<span><?php echo esc_attr( $male_percentage ); ?>%</span>
					</div>
				</div>
			</div>
			<br><br>
			<span>Top three locations</span><br><br>

			<table class="inf-locations-details">
				<tr>
					<td>
						<?php if( $top_country_1 == 'Canada') : ?>
							<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/canada-flag.svg'; ?>">
						<?php elseif( $top_country_1 == 'United States' ) : ?>
							<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/us-flag.svg'; ?>">
						<?php elseif( $top_country_1 == 'United Kingdom' ) : ?>
							<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/uk-flag.svg'; ?>">
						<?php endif; ?>
					</td>

					<td><?php echo esc_html( $top_country_percentage_1 ); ?>%</td>

					<td><?php echo esc_html( $top_country_1) ?></td>
				</tr>

				<tr>
					<td>
						<?php if( $top_country_2 == 'Canada') : ?>
							<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/canada-flag.svg'; ?>">
						<?php elseif( $top_country_2 == 'United States' ) : ?>
							<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/us-flag.svg'; ?>">
						<?php elseif( $top_country_2 == 'United Kingdom' ) : ?>
							<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/uk-flag.svg'; ?>">
						<?php endif; ?>
					</td>
					<td><?php echo esc_attr( $top_country_percentage_2 ); ?>%</td>
					<td><?php echo esc_attr( $top_country_2 ) ?></td>
				</tr>

				<tr>
					<td>
						<?php if( $top_country_3 == 'Canada') : ?>
							<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/canada-flag.svg'; ?>">
						<?php elseif( $top_country_3 == 'United States' ) : ?>
							<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/us-flag.svg'; ?>">
						<?php elseif( $top_country_3 == 'United Kingdom' ) : ?>
							<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/uk-flag.svg'; ?>">
						<?php endif; ?>
					</td>
					<td><?php echo esc_attr( $top_country_percentage_3 ); ?>%</td>
					<td><?php echo esc_attr( $top_country_3 ) ?></td>
				</tr>
			</table>
		</div>

		<br><br>

		<?php endif; ?>

		<?php if ( $viewer_is_brand && $shipping_address_1 != '' ) : ?>

			<div class="influencer-profile-section-card">

				<h5>Shipping</h5>

				<?php echo ( $shipping_company != '' ? '<div>' . esc_html($shipping_company) . '</div>' : '' ); ?>

				<div><?php echo esc_html(ucfirst($shipping_first_name) . ' ' . ucfirst($shipping_last_name) ); ?></div>

				<div><?php echo esc_html( $shipping_address_1 ) . ' ' . esc_html( $shipping_address_2 ); ?></div>

				<div><?php echo esc_html( $shipping_city . ' ' . $shipping_state . ', ' . $shipping_postcode ) ?></div>
			</div>

		<?php endif; ?>
	</div>
</div>
<?php
return ( !is_admin() ? ob_get_clean() : '' );
}

/**
 * Convert partial urls to complete urls for use in anchors
 * with external links
 *
 * @param string $partial_url Partial url to be converted
 *
 * @return string
 */
function complete_partial_url( $partial_url ) {

	if( $partial_url !== '' && strpos($partial_url, 'https://') === false && strpos($partial_url, 'http://') === false && strpos($partial_url, '//') === false ) {

		$complete_url = '//' . $partial_url;

	} else {

		$complete_url = $partial_url;
	}

	return $complete_url;
}

function set_campaign_primary_image( $file, $campaign_id ) {

	// Upload thumbnail image to image library
	if ($file !== '') {

		$uploaddir = wp_upload_dir();
		$uploadfile = $uploaddir['path'] . '/' . basename( $file['name'] );

		move_uploaded_file( $file['tmp_name'] , $uploadfile );

		$filename = basename( $uploadfile );
		$wp_filetype = wp_check_filetype(basename($filename), null );
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
			'post_content' => '',
			'post_status' => 'inherit'
		);
		$attach_id = wp_insert_attachment( $attachment, $uploadfile );

		// attach uploaded image to post
		if ($attach_id > 0){

			update_post_meta($campaign_id, '_thumbnail_id', $attach_id);
		}
	}
}

function set_campaign_gallery_images( $files, $campaign_id ) {

	// Upload image gallery to library
	if ($files !== '') {

		$attachment_ids = '';

		foreach ($files['name'] as $key => $value) {

			if ($files['name'][$key]) {

				$file = array(
					'name' => $files['name'][$key],
					'type' => $files['type'][$key],
					'tmp_name' => $files['tmp_name'][$key],
					'error' => $files['error'][$key],
					'size' => $files['size'][$key]
				);
			}

			$_FILES = array("gallery_upload" => $file);

			foreach ($_FILES as $file => $array) {

				$attachment_id = media_handle_upload($file, $campaign_id);
				$attachment_ids .= $attachment_id . ',';
			}

		}

		//store all image ids
		$attachment_ids = rtrim($attachment_ids, ',');

		update_post_meta( $campaign_id, '_product_image_gallery', $attachment_ids);
	}
}

add_filter( 'hf_csv_customer_post_columns', 'woo_export_additional_columns', 1, 10);
function woo_export_additional_columns( $columns ) {

	unset(
		$columns['ID'],
		$columns['customer_id'],
		$columns['user_login'],
		$columns['user_pass'],
		$columns['user_nicename'],
		$columns['user_url'],
		$columns['user_registered'],
		$columns['display_name'],
		$columns['user_status'],
		$columns['roles'],
		$columns['nickname'],
		$columns['description'],
		$columns['rich_editing'],
		$columns['syntax_highlighting'],
		$columns['admin_color'],
		$columns['use_ssl'],
		$columns['show_admin_bar_front'],
		$columns['locale'],
		$columns['wp_user_level'],
		$columns['dismissed_wp_pointers'],
		$columns['last_update'],
		$columns['billing_first_name'],
		$columns['billing_last_name'],
		$columns['billing_company'],
		$columns['billing_email'],
		$columns['billing_phone'],
		$columns['billing_address_1'],
		$columns['billing_address_2'],
		$columns['billing_postcode'],
		$columns['billing_city'],
		$columns['billing_state'],
		$columns['billing_country']
	);

	$columns['inf_phone'] = 'inf_phone';
	$columns['social_prism_user_location'] = 'social_prism_user_location';
	$columns['social_prism_user_state_province'] = 'social_prism_user_state_province';
	$columns['social_prism_user_birthday'] = 'social_prism_user_birthday';
	$columns['inf_gender'] = 'inf_gender';
	$columns['social_prism_user_instagram'] = 'social_prism_user_instagram';
	$columns['social_prism_user_instagram_reach'] = 'social_prism_user_instagram_reach';
	$columns['instagram-engagement-rate'] = 'instagram-engagement-rate';
	$columns['social_prism_user_facebook_reach'] = 'social_prism_user_facebook_reach';
	$columns['social_prism_user_twitter_reach'] = 'social_prism_user_twitter_reach';
	$columns['inf_authentique_score'] = 'inf_authentique_score';
	$columns['inf_authentique_actual_average_likes'] = 'inf_authentique_actual_average_likes';
	$columns['inf_authentique_expected_average_likes'] = 'inf_authentique_expected_average_likes';
	$columns['inf_authentique_actual_average_comments'] = 'inf_authentique_actual_average_comments';
	$columns['inf_authentique_expected_average_comments'] = 'inf_authentique_expected_average_comments';

	return $columns;
}

function get_country_names($country_codes) {
	global $wpdb;
	$country_names = array();

	if( is_array($country_codes) ) {
		$country_codes = implode('","', $country_codes);
	}

	$query = '
		SELECT country_code, country_name
		FROM country
		WHERE country_code IN ("' . $country_codes . '")
	';

	$results = $wpdb->get_results($query);

	foreach( $results as $result ) {
		$country_names[$result->country_code] = $result->country_name;
	}

	return $country_names;
}

function get_region_names($state_codes) {
	global $wpdb;
	$region_names = array();

	if( is_array($state_codes) ) {
		$state_codes = implode('","', $state_codes);
	}

	$query = '
		SELECT CONCAT(country_code, state_code) AS state_key, state_name
		FROM states
		WHERE CONCAT(country_code, state_code) IN("' . $state_codes . '")
	';

	$results = $wpdb->get_results($query);

	foreach( $results as $result ) {
		$region_names[$result->state_key] = $result->state_name;
	}

	return $region_names;
}

function get_affiliate_link_data($link_id, $start_date, $end_date) {

    $start_date = strtotime($start_date);
    $end_date = strtotime($end_date);

	$data = array(
		'signups' => array(),
		'shoutouts' => array(),
	);

	// Get all users with referred_by meta of link id for total signups
	$signup_args = array(
		'meta_query' => array(
			array(
				'key' => 'referred_by',
				'value' => $link_id,
				'compare' => 'LIKE',
			),
		),
	);
	$signups = get_users($signup_args);

    foreach( $signups as $user ) {

        $signup_date = strtotime($user->user_registered);

        if( $signup_date >= $start_date && $signup_date <= $end_date ) {

            $data['signups'][] = array(
                'ID' => $user->ID,
                'signup_date' => $user->user_registered,
            );
        }

        $customer_orders = wc_get_orders(array(
            'limit' => -1,
            'customer_id' => $user->ID,
        ));

        // Find the user's first completed order
        $first_completed = false;

        foreach( $customer_orders as $order ) {

            $date_completed = $order->get_date_completed();

            if($date_completed) {

                $date_completed = $date_completed->date('Y-m-d H:i:s');


                if($first_completed) {

                    if(strtotime($first_completed['date']) > strtotime($date_completed)) {
                        $first_completed['ID'] = $order->ID;
                        $first_completed['date'] = $date_completed;
                    }

                } else {
                    $first_completed = array(
                        'ID' => $order->ID,
                        'date' => $date_completed,
                    );
                }
            }
        }

        if($first_completed && strtotime($first_completed['date']) >= $start_date && strtotime($first_completed['date']) <= $end_date) {

            $data['shoutouts'][] = array(
                'user_id' => $user->ID,
                'order_id' => $first_completed['ID'],
                'date_completed' => $first_completed['date'],
            );
        }
    }

	return $data;
}

function check_remote_file($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($ch);
    curl_close($ch);
    if($result !== FALSE)
    {
        return true;
    }
    else
    {
        return false;
    }
}

/**
 * Get shoutout status from order ID
 *
 * @param    int  $order_id ID of the order
 * @return   string
 *
 */
function get_order_shoutout_status( $order_id ) {
    return get_post_meta( $order_id, 'shoutout_status', true );
}

function get_campaign_influencer_goal($campaign_id , $date_range) {

  $goal_influencers = array(
    'total' => 0,
    'enrolled' => 0,
    'active' => 0,
    'goal' => 0,
  );

  $orders = get_orders_by_campaign_id($campaign_id, $date_range);
  $channels = (array)get_post_meta($campaign_id, 'shoutout_channels', true);

  $goal_influencers['total'] = count($orders);

  foreach($orders as $order_id) {

    // Enrolled Influencers
    $order = wc_get_order($order_id);
    $order_status = $order->get_status();
    if($order_status != 'cancelled' && $order_status != 'refunded') {
      $goal_influencers['enrolled']++;
    }

    // Active Influencers
    $all_links_submitted = true;
    foreach($channels as $channel) {
      $channel_url = get_post_meta($order_id, $channel.'_url', true);

      if(!$channel_url) {
        $all_links_submitted = false;
      }
    }

    if($all_links_submitted) {
      $goal_influencers['active']++;
    }
  }

  // Get latest campaign goal
  $campaign_goals = get_posts(array(
    'post_type' => 'campaign_goal',
    'post_status' => 'any',
    'meta_key' => 'campaign',
    'meta_value' => $campaign_id,
    'numberposts' => -1,
  ));
  $goal = get_post_meta($campaign_goals[0]->ID, 'goal', true);
  $period = (strtotime($date_range['to']) - strtotime($date_range['from']))/60/60/24;
  $goal_influencers['goal'] = $period*$goal;

  return $goal_influencers;
}