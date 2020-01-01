<?php
/**
 * This file is used for registering ajax scripts and any ajax functions that don't involve form post data processing
 */
//------------------------//
//--------- Shop ---------//
//------------------------//
add_action( 'wp_ajax_sort_filter_shop', 'sort_filter_shop_ajax' );
add_action( 'wp_ajax_nopriv_sort_filter_shop', 'sort_filter_shop_ajax' );
function sort_filter_shop_ajax() {
  $response = array();

  $instagram_reach = isset($_POST['instagram_reach'])?$_POST['instagram_reach']:'';
  $instagram_engagement = isset($_POST['instagram_engagement_rate'])?$_POST['instagram_engagement_rate']:'';
  $campaign_strategies = isset($_POST['campaign_strategies'])?array_filter(explode(',',$_POST['campaign_strategies'])):array();
  $interests = isset($_POST['interests'])?array_filter(explode(',',$_POST['interests'])):array();
  $country = isset($_POST['country'])?$_POST['country']:'';
  $channel = isset($_POST['channel'])?$_POST['channel']:'';
  $paid_campaign = isset($_POST['paid_campaign'])?true:false;
  $sortby = isset($_POST['sortby'])?$_POST['sortby']:'';
  $order = isset($_POST['order'])?$_POST['order']:'';
  $page = isset($_POST['page'])?$_POST['page']:1;

  $filters = array(
    'campaign_strategies' => $campaign_strategies,
    'interests' => $interests,
    'country' => $country,
    'channel' => $channel,
    'paid_campaign' => $paid_campaign,
  );
  
  if($instagram_reach != '') {
    $filters['instagram_reach'] = $instagram_reach;
  }
  if($instagram_engagement != '') {
    $filters['instagram_engagement_rate'] = $instagram_engagement;
  }

  $sort = array(
    'sortby' => $sortby,
    'order' => $order,
  );

  $shop_campaigns = get_shop_campaigns($filters, $sort, $page);

  $response['content'] = $shop_campaigns['markup'];
  $response['maxPages'] = $shop_campaigns['max_pages'];
  $response['page'] = $page;

  exit(json_encode($response));
}

// ------------------- //
// ---- Form Ajax ---- //
// ------------------- //

// signup set referral
add_action( 'wp_ajax_inf_signup_set_referral', 'inf_signup_set_referral' );
add_action( 'wp_ajax_nopriv_inf_signup_set_referral', 'inf_signup_set_referral' );
function inf_signup_set_referral() {
  $uid = $_POST['uid'];
  $referred_by = $_POST['referred_by'];
  $mypassionmedia = $_POST['mypassionmedia'];

  if($mypassionmedia) {
    update_user_meta($uid, 'referralcode', 'mypassionmedia');
  }

  update_user_meta($uid, 'referred_by', $referred_by);

  exit(get_site_url() . '/welcome');
}

add_action( 'wp_ajax_brand_signup_set_referral', 'brand_signup_set_referral' );
add_action( 'wp_ajax_nopriv_brand_signup_set_referral', 'brand_signup_set_referral' );
function brand_signup_set_referral() {
    $uid = $_POST['uid'];
    $referred_by = $_POST['referred_by'];

    update_user_meta($uid, 'referred_by', $referred_by);

    exit();
}

add_action( 'wp_ajax_countries_JSON', 'countries_JSON' );
function countries_JSON() {

  $data = array();

  global $wpdb;
  $countries = $wpdb->get_results(
    "SELECT country_code, country_name
    FROM country
    ORDER BY country_name",
    OBJECT );

  foreach($countries as $country){
    $data[] = array(
      'id' => $country->country_code,
      'name' => $country->country_name,
    );
  }

  exit(json_encode($data));
}

// regions_JSON();
add_action( 'wp_ajax_regions_JSON', 'regions_JSON' );
function regions_JSON() {

  $data = array();

  $selected_countries = isset($_POST['selected_countries'])?$_POST['selected_countries']:'';

  global $wpdb;

  if(!empty($selected_countries)) {
    foreach($selected_countries as $country) {
      $country_code = $country['id'];

      $regions = $wpdb->get_results(
        "SELECT country_code, state_code, state_name
        FROM states
        WHERE country_code = '" . $country_code . "'
        ORDER BY state_name",
        OBJECT );

      foreach($regions as $region) {
        $data[] = array(
          'id' => $region->country_code . $region->state_code,
          'name' => $region->state_name,
        );
      }
    }
  }

  exit(json_encode($data));
}

// Design campaign product images
add_action( 'wp_ajax_design_campaign_upload_product_images', 'design_campaign_upload_product_images' );

function design_campaign_upload_product_images(){

  $attach_ids = array();

  if($_SERVER['REQUEST_METHOD'] == 'POST' && $_FILES) {
    $campaign_id = $_POST['campaign_id'];
    $files = $_FILES['campaign_images'];

    foreach($files['name'] as $key => $value) {

      if($files['name'][$key]) {

        $file = array(
          'name' => $files['name'][$key],
          'type' => $files['type'][$key],
          'tmp_name' => $files['tmp_name'][$key],
          'error' => $files['error'][$key],
          'size' => $files['size'][$key]
        );

        $_FILES = array('campaign_images' => $file);

        foreach($_FILES as $file => $array) {

          // check to make sure its a successful upload
          if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) exit('failed');

          require_once(ABSPATH . "wp-admin" . '/includes/image.php');
          require_once(ABSPATH . "wp-admin" . '/includes/file.php');
          require_once(ABSPATH . "wp-admin" . '/includes/media.php');

          $attach_id = media_handle_upload( $file, $campaign_id );

          $attach_ids[] = $attach_id;
        }
      }
    }
  }

  $current_images = get_post_meta($campaign_id, '_product_image_gallery', true);

  $updated_images = implode(',',$attach_ids) . ($current_images?','.$current_images:'');

  update_post_meta($campaign_id, '_product_image_gallery', $updated_images);

  // Get array of image urls
  $response= array();
  foreach(explode(',',$updated_images) as $attachment_id) {
    $image = wp_get_attachment_image_src($attachment_id, 'thumbnail');
    if(is_array($image)) {
      $response[] = array(
        'id' => $attachment_id,
        'url' => $image[0],
      );
    }
  }

  exit(json_encode($response, JSON_UNESCAPED_SLASHES));
}

// Design campaign product images
add_action( 'wp_ajax_design_campaign_update_product_images', 'design_campaign_update_product_images' );

function design_campaign_update_product_images(){
  $response = array('formErrors' => array());
  $campaign_id = isset($_POST['campaign_id'])?$_POST['campaign_id']:'';
  $attach_id = isset($_POST['attach_id'])?$_POST['attach_id']:'';
  $operation = isset($_POST['operation'])?$_POST['operation']:'';

  if($campaign_id && $attach_id) {
    $primary_image = get_post_thumbnail_id($campaign_id);
    $gallery_images = explode(',',get_post_meta($campaign_id, '_product_image_gallery', true));

    switch($operation) {
      case 'primary':
        if($attach_id !== $primary_image) {
          $key = array_search($attach_id, $gallery_images);
          unset($gallery_images[$key]);
          set_post_thumbnail($campaign_id, $attach_id);
          $gallery_images[] = $primary_image;
          update_post_meta($campaign_id, '_product_image_gallery', implode(',',$gallery_images));
        }
        break;
      case 'remove':
        if( in_array($attach_id, $gallery_images) ) {
          $key = array_search($attach_id, $gallery_images);
          unset($gallery_images[$key]);
          update_post_meta($campaign_id, '_product_image_gallery', implode(',',$gallery_images));
        } else if($primary_image == $attach_id) {
          delete_post_thumbnail($campaign_id);
        }
        break;
    }
  }

  exit(json_encode($response));
}

// Design campaign upload shoutout inspiration images
add_action( 'wp_ajax_design_campaign_upload_shoutout_inspiration_images', 'design_campaign_upload_shoutout_inspiration_images' );

function design_campaign_upload_shoutout_inspiration_images(){

  $response = array();

  if($_SERVER['REQUEST_METHOD'] == 'POST' && $_FILES) {
    $campaign_id = $_POST['campaign_id'];
    $files = $_FILES['inspiration_images'];

    foreach($files['name'] as $key => $value) {

      if($files['name'][$key]) {

        $file = array(
          'name' => $files['name'][$key],
          'type' => $files['type'][$key],
          'tmp_name' => $files['tmp_name'][$key],
          'error' => $files['error'][$key],
          'size' => $files['size'][$key]
        );

        $_FILES = array('inspiration_images' => $file);

        foreach($_FILES as $file => $array) {

          // check to make sure its a successful upload
          if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) exit('failed');

          require_once(ABSPATH . "wp-admin" . '/includes/image.php');
          require_once(ABSPATH . "wp-admin" . '/includes/file.php');
          require_once(ABSPATH . "wp-admin" . '/includes/media.php');

          $attach_id = media_handle_upload( $file, $campaign_id );

          $response['images'][] = array(
            'id' => $attach_id,
            'url' => wp_get_attachment_image_src($attach_id, 'thumbnail')[0],
          );
        }
      }
    }
  }

  $current_images = get_post_meta($campaign_id, 'shoutout_inspiration', true);
  for($i=0;$i<$current_images;$i++) {
    $meta_key = 'shoutout_inspiration_' . $i . '_images';
    $attach_id = get_post_meta($campaign_id, $meta_key, true);
    $url = wp_get_attachment_url($attach_id, 'thumbnail');

    $response['images'][] = array(
      'id' => $attach_id,
      'url' => $url,
    );
  }

  foreach($response['images'] as $index => $image) {
    if(!is_wp_error($image['id'])) {
      $meta_key = 'shoutout_inspiration_' . $index . '_images';
      update_post_meta($campaign_id, $meta_key, $image['id']);
    }
  }
  update_post_meta($campaign_id, 'shoutout_inspiration', count($response['images']));

  exit(json_encode($response, JSON_UNESCAPED_SLASHES));
}

// Design campaign update shoutout inspiration images
add_action( 'wp_ajax_design_campaign_remove_shoutout_inspiration_image', 'design_campaign_remove_shoutout_inspiration_image' );

function design_campaign_remove_shoutout_inspiration_image(){
  $response = array('formErrors' => array());
  $campaign_id = isset($_POST['campaign_id'])?$_POST['campaign_id']:'';
  $attach_id = isset($_POST['attach_id'])?$_POST['attach_id']:'';

  if($campaign_id && $attach_id) {
    $current_images = get_post_meta($campaign_id, 'shoutout_inspiration', true);

    for($i=0;$i<$current_images;$i++) {
      $meta_key = 'shoutout_inspiration_' . $i . '_images';
      $image = get_post_meta($campaign_id, $meta_key, true);
      if($attach_id == $image) {
        delete_post_meta($campaign_id, $meta_key, $image);
        update_post_meta($campaign_id, 'shoutout_inspiration', $current_images-1);
      }
    }
  }

  exit(json_encode($response));
}

/**
 * Exports CSV ambassador report
 */
add_action( 'wp_ajax_admin_export_ambassador_report', 'admin_export_ambassador_report' );

function admin_export_ambassador_report() {

  $response = array('errors'=>'');

  // Verify nonce
  if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'export_affiliate_report' ) ) {

    $response['errors'] .= 'Ooops, something went wrong, please try again later.';
    $response = json_encode($response);
    exit($response);
  }

  if($_POST['period']) {
    global $wpdb;

    $affiliate_users = array();

    $start_date = $_POST['period'];
    $end_date = date('M d Y', strtotime('+1 month', strtotime($start_date)));

    $args = array(
      'limit' => -1,
      'return' => 'ids',
      'date_completed' => $start_date.'...'.$end_date,
      'status' => 'completed',
    );
    $query = new WC_Order_Query($args);
    $orders = $query->get_orders();

    foreach($orders as $order_id) {
      $user_id = get_post_meta($order_id, '_customer_user', true);
      $referred_by = get_user_meta($user_id, 'referred_by', true);
      
      if($referred_by) { // If this user was referred by an affiliate

        // Check if this is this user's first completed order
        $query = new WC_Order_Query(array(
          'limit' => 1,
          'return' => 'objects',
          'orderby' => 'date_completed',
          'order' => 'ASC',
          'status' => 'completed',
          'customer_id' => $user_id,
        ));
        $customer_orders = $query->get_orders();

        if($customer_orders[0]->get_id() == $order_id) { // If this is the customer's first completed order

          // get the user this user was referred by
          $owner_id = get_post_meta($referred_by, 'owner_id', true);

          if($owner_id) {
            if(isset($affiliate_users[$owner_id]['completed_orders'])) {
              $affiliate_users[$owner_id]['completed_orders']++;
            } else {
              $affiliate_users[$owner_id]['completed_orders'] = 1;
            }
          }
        }
      }      
    }

    $start_datetime = new DateTime($start_date.' 00:00:00');
    $end_datetime = new DateTime($end_date.' 00:00:00');
    $sql = $wpdb->prepare('
      SELECT wp_users.ID, wp_users.user_login
      FROM wp_users
      WHERE 1=1
      AND CAST(user_registered AS DATE) BETWEEN %s AND %s',$start_datetime->format('Y-m-d H:i:s'), $end_datetime->format('Y-m-d H:i:s'));
    $users = $wpdb->get_results($sql);

    foreach($users as $user) {
      $referred_by = get_user_meta($user->ID, 'referred_by', true);

      if($referred_by) {
        $owner_id = get_post_meta($referred_by, 'owner_id', true);

        if($owner_id) {
          if(isset($affiliate_users[$owner_id]['signups'])) {
            $affiliate_users[$owner_id]['signups']++;
          } else {
            $affiliate_users[$owner_id]['signups'] = 1;
          }
        }
      }
    }

    $file_content = array(array(
      'Program Name', 
      'Dollar Ammount', 
      'First Name',
      'Last Name',
      'Date of Birth',
      'Contact Number',
      'Email Address',
      'SIN Number',
      'Suite Number',
      'Street Name and Number',
      'City',
      'Province',
      'Postal Code',
      'Country',
      'Customized Message',
    ));

    foreach($affiliate_users as $user_id => $affiliate_data) {

      $signups = isset($affiliate_data['signups'])?$affiliate_data['signups']:0;
      $completed_orders = isset($affiliate_data['completed_orders'])?$affiliate_data['completed_orders']:0;
      $signups_rate = .5;
      $shoutouts_rate = 4.5;

      $signups_owed = $signups * $signups_rate;
      $shoutouts_owed = $completed_orders * $shoutouts_rate;

      $user = get_userdata($user_id);

      $first_name = $user->first_name;
      $last_name = $user->last_name;
      $birthdate = get_user_meta($user->ID, 'social_prism_user_birthday', true);
      $phone = get_user_meta($user->ID, 'inf_phone', true);
      $email = $user->user_email;
      $address_2 = get_user_meta($user->ID, 'shipping_address_2', true);
      $address_1 = get_user_meta($user->ID, 'shipping_address_1', true);
      $city = get_user_meta($user->ID, 'shipping_city', true);
      $province = get_user_meta($user->ID, 'shipping_province', true);
      $postcode = get_user_meta($user->ID, 'shipping_postcode', true);
      $country = get_user_meta($user->ID, 'shipping_country', true);

      $file_content[] = array(
        'Affiliate',
        $signups_owed+$shoutouts_owed,
        $first_name,
        $last_name,
        $birthdate,
        $phone,
        $email,
        '', // Blank space for SIN
        $address_2,
        $address_1,
        $city,
        $province,
        $postcode,
        $country,
        '', // Blank space for custom message
      );
    }

    $file_path = get_home_path() . '/wp-content/affiliate-report-export-'.$start_datetime->format('M_d_Y').'-'.$end_datetime->format('M_d_Y').'.csv';
    $file_url = get_site_url() . '/wp-content/affiliate-report-export-'.$start_datetime->format('M_d_Y').'-'.$end_datetime->format('M_d_Y').'.csv';
    $file = fopen( $file_path, 'w');
    foreach($file_content as $row) {
      fputcsv($file, $row);
    }
    fclose($file);

    $response['fileURL'] = $file_url;
  } else {
    $response['errors'] = 'No period provided.';
  }

  $response = json_encode($response);

  exit($response);
}

/**
 * Exports CSV of all products
 */
add_action( 'wp_ajax_admin_export_products', 'admin_export_products' );
function admin_export_products() {

  $response = array('errors'=>'');

  $date_range_from = $_POST['date_range_from'];
  $date_range_to = $_POST['date_range_to'];

  // Verify nonce
  if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'export_products' ) ) {
    $response['errors'] .= 'Ooops, something went wrong, please try again later.';
    $response = json_encode($response);
    exit($response);
  }
  $file_contents = array(array(
    'Brand Name',
    'Primary Contact',
    'Campaign',
    'Campaign Type',
    'Date Published',
    'Status',
    'Country Availability',
    'IG Reach',
    'IG Engagement',
    'Total Orders',
    'Completed',
    'Cancelled',
    'Failed',
    'On Hold',
  ));

  if($date_range_from && $date_range_to) {
    $date_range_arg = $date_range_from . '...' . $date_range_to;
  } else {
    $date_range_arg = '';
    if($date_range_from) {
      $date_range_arg = '>'.$date_range_from;
    }
    if($date_range_to) {
      $date_range_arg = '<'.$date_range_to;
    }
  }

  $all_products = wc_get_products(array(
    'limit' => -1,
    'date_created' => $date_range_arg,
  ));

  foreach($all_products as $product) {
    // Product Info
    $product_id = $product->get_id();
    $post_obj = get_post($product_id);
    $campaign_title = $product->get_title();
    $campaign_type = get_post_meta($product_id, 'campaign_type', true);
    $date_published = get_the_date('m/d/Y', $product_id);
    $status = $product->get_status();
    $countries = implode(', ', (array)get_post_meta($product_id, 'location', true));
    $ig_reach = get_post_meta($product_id, 'instagram_reach', true);
    $ig_engagement = get_post_meta($product_id, 'instagram_engagement_rate', true);

    // Brand Info
    $brand = get_campaign_brand($product_id);
    $brand_name = $brand->brand_name;

    // Contacts
    $contacts = get_brand_contacts($brand->ID);
    $primary_contact = $contacts[0]['email'];
    
    // Orders
    $order_ids = get_orders_by_campaign_id($product_id);
    $cancelled_orders = 0;
    $completed_orders = 0;
    $failed_orders = 0;
    $on_hold_orders = 0;
    foreach($order_ids as $order_id) {
      $order = wc_get_order($order_id);
      $status = $order->get_status();

      switch($status) {
        case 'failed':
          $failed_orders++;
          break;
        case 'on-hold':
          $on_hold_orders++;
          break;
        case 'cancelled':
          $cancelled_orders++;
          break;
        case 'completed':
          $completed_orders++;
          break;
      }
    }

    $file_contents[] = array(
      $brand_name,
      $primary_contact,
      $campaign_title,
      $campaign_type,
      $date_published,
      $status,
      $countries,
      $ig_reach,
      $ig_engagement,
      count($order_ids),
      $completed_orders,
      $cancelled_orders,
      $failed_orders,
      $on_hold_orders,
    );
  }

  $file_path = get_home_path() . '/wp-content/product-export.csv';
  $file_url = get_site_url() . '/wp-content/product-export.csv';
  $file = fopen( $file_path, 'w');
  foreach($file_contents as $content) {
    fputcsv($file, $content);
  }
  fclose($file);

  $response['fileURL'] = $file_url;

  $response = json_encode($response);

  exit($response);
}

/**
 * Exports CSV of all orders
 */
add_action( 'wp_ajax_admin_export_orders', 'admin_export_orders' );

function admin_export_orders() {

  $response = array('errors'=>'');

  // Verify nonce
  if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'export_orders' ) ) {

    $response['errors'] .= 'Ooops, something went wrong, please try again later.';
    $response = json_encode($response);
    exit($response);
  }

  $selected_campaigns = isset($_POST['selected_campaigns'])?$_POST['selected_campaigns']:array();

  $date_range_from = isset($_POST['date_range_from'])?$_POST['date_range_from']:'';
  $date_range_to = isset($_POST['date_range_to'])?$_POST['date_range_to']:'';

  $file_contents = array(
    array(
      'Order ID',
      'Order Status',
      'Order Created',
      'Order Completed',
      'Campaign ID',
      'Campaign Title',
      'Brand ID',
      'Brand Name',
      'Influencer ID',
      'Influencer Name',
      'Influencer Email',
      'Influencer Phone',
      'Influencer Birthday',
      'Influencer Country',
      'Influencer Region',
      'Influencer Shipping Info',
      'Influencer Gender',
      'Influencer Interests',
      'Influencer IG Handle',
      'Influencer IG Reach',
      'Influencer IG Engagement',
    ),
  );

  $orders = array();

  if(!empty($selected_campaigns)) {

    foreach($selected_campaigns as $campaign_id) {
      $campaign_orders = get_orders_by_campaign_id($campaign_id);

      foreach($campaign_orders as $order_id) {
        $order = get_post($order_id);
        $order_date = strtotime($order->post_date);

        if(
          (!$date_range_from || $order_date >= strtotime($date_range_from))
          &&
          (!$date_range_to || $order_date <= strtotime($date_range_to))
        ) {
          $orders[] = $order_id;
        }
      }
    }
  } else {
    $order_posts = get_posts(array(
      'numberposts' => -1,
      'post_type' => 'shop_order',
      'post_status' => 'any',
      'date_query' => array(
        'after' => $date_range_from,
        'before' => $date_range_to,
      )
    ));

    foreach($order_posts as $post) {
      $orders[] = $post->ID;
    }
  }

  foreach($orders as $order_id) {
    $campaign_id = get_order_campaign($order_id);
    $brand = get_campaign_brand($campaign_id);
    $influencer_id = get_order_influencer($order_id);
    $influencer_data = get_userdata($influencer_id);
    $influencer_country = get_user_meta($influencer_id, 'inf_country', true);
    $influencer_region = get_user_meta($influencer_id, 'inf_region', true);
    // Shipping Info
    $shipping_first_name = get_user_meta($influencer_id, 'shipping_first_name', true);
    $shipping_last_name = get_user_meta($influencer_id, 'shipping_last_name', true);
    $shipping_country = get_user_meta($influencer_id, 'shipping_country', true);
    $shipping_address_1 = get_user_meta($influencer_id, 'shipping_address_1', true);
    $shipping_address_2 = get_user_meta($influencer_id, 'shipping_address_2', true);
    $shipping_city = get_user_meta($influencer_id, 'shipping_city', true);
    $shipping_state = get_user_meta($influencer_id, 'shipping_state', true);
    $shipping_postcode = get_user_meta($influencer_id, 'shipping_postcode', true);
    $influencer_shipping_info = $shipping_first_name . ' ' . $shipping_last_name . "\n"
      . $shipping_address_1 . ($shipping_address_2?', '.$shipping_address_2:'') . "\n"
      . $shipping_city . ' ' . $shipping_state . ', ' . $shipping_postcode . "\n"
      . $shipping_country;

    $wc_order = new WC_Order($order_id);

    $file_contents[] = array(
      $order_id,
      $wc_order->get_status(),
      $wc_order->get_date_created()->date('F j, Y'),
      $wc_order->get_date_completed()!=null?$wc_order->get_date_completed()->date('F j, Y'):'',
      $campaign_id,
      get_the_title($campaign_id),
      $brand->ID,
      $brand->brand_name,
      $influencer_id,
      $influencer_data?$influencer_data->first_name . ' ' . $influencer_data->last_name:'',
      $influencer_data?$influencer_data->user_email:'',
      get_user_meta($influencer_id, 'phone', true),
      get_user_meta($influencer_id, 'social_prism_user_birthday', true),
      $influencer_country?get_country_names(array($influencer_country))[$influencer_country]:'',
      $influencer_region?get_region_names(array($influencer_region))[$influencer_region]:'',
      $influencer_shipping_info,
      get_user_meta($influencer_id, 'social_prism_user_gender', true),
      is_array(get_user_meta($influencer_id, 'inf_interests', true))?implode(', ', get_user_meta($influencer_id, 'inf_interests', true)):'',
      get_user_meta($influencer_id, 'social_prism_user_instagram', true),
      get_user_meta($influencer_id, 'social_prism_user_instagram_reach', true),
      get_user_meta($influencer_id, 'instagram-engagement-rate', true),
    );
  }

  $file_path = get_home_path() . '/wp-content/order-export.csv';
  $file_url = get_site_url() . '/wp-content/order-export.csv';
  $file = fopen( $file_path, 'w');
  foreach($file_contents as $content) {
    fputcsv($file, $content);
  }
  fclose($file);

  $response['fileURL'] = $file_url;

  $response = json_encode($response);

  exit($response);
}

/**
 * Sends user data to Authentique API and collects data
 */
add_action( 'wp_ajax_admin_authentique_get_users', 'admin_authentique_get_users' );

function admin_authentique_get_users() {

  $response = array('errors'=>'');

  // Verify nonce
  if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'authentique_check' ) ) {

    $response['errors'] .= 'Ooops, something went wrong, please try again later.';
    $response = json_encode($response);
    exit($response);
  }

  $args = array(
    'meta_key' => 'social_prism_user_instagram',
    'meta_compare' => 'EXISTS',
    'fields' => 'ID',
  );

  $users = get_users($args);

  $response['users'] = $users;

  $response = json_encode($response);

  exit($response);
}

/**
 * Returns an array of all users with instagram handles and no authentique user not found error
 */
add_action( 'wp_ajax_admin_authentique_check', 'admin_authentique_check' );

function admin_authentique_check() {

  include_once('api/authentique.php');

  $response = array('errors'=>'');

  $uid = $_POST['user'];

  $username = get_user_meta( $uid, 'social_prism_user_instagram', true );
  $authentique_account_not_found = get_user_meta( $uid, 'authentique_account_not_found', true );

  if( $username && !$authentique_account_not_found ) {

    $authentique_audit_connected = get_user_meta( $uid, 'authentique_audit_connected', true );
    $authentique_audit_completed = get_user_meta( $uid, 'authentique_audit_completed', true );

    if( $authentique_audit_completed ) {

    } else if( $authentique_audit_connected ) {

      $authentique_data = get_audit_progress_or_result($username);

      if( isset($authentique_data['error_code']) ) {

        if( $authentique_data['error_code'] == 'audit_not_found_username' ) {

          $result = create_influencer_audit( $username );

          if( isset($result['success']) && $result['success'] ) {

            update_user_meta( $uid, 'authentique_audit_connected', 'true' );
            update_user_meta( $uid, 'authentique_account_private', false );
            update_user_meta( $uid, 'authentique_account_no_followers', false );

          }  else {

            if( $result['error_code'] == 'account_private' ) {

              update_user_meta( $uid, 'authentique_account_private', true );
              $response['errors'] .= 'Private account on retry';

            } else if ( $result['error_code'] == 'account_not_found' ) {

              update_user_meta( $uid, 'authentique_account_not_found', true );
              $response['errors'] .= 'Account not found on retry';

            } else if( $result['error_code'] == 'account_no_followers' ) {

              update_user_meta( $uid, 'authentique_account_no_followers', true );
              $response['errors'] .= 'No followers on retry';

            } else {

              $response['errors'] = $result;
            }
          }
        } else if( $authentique_data['error_code'] == 'account_private' ) {

          update_user_meta( $uid, 'authentique_account_private', true );

        } else {

          update_user_meta( $uid, 'authentique_unknown_error', implode(', ', (array)$authentique_data['error']) );
        }

      } else if( isset($authentique_data['success']) && !$authentique_data['result']['is_cancelled'] ) {

        $estimated_real_follower_percentage = $authentique_data['result']['estimated_real_follower_percentage'];
        $media_posts = $authentique_data['result']['media_posts'];
        $average_likes = $authentique_data['result']['average_likes'];
        $average_comments = $authentique_data['result']['average_comments'];
        $expected_likes = $authentique_data['result']['expected_likes'];
        $expected_comments = $authentique_data['result']['expected_comments'];
        $estimated_follower_average_posts = $authentique_data['result']['estimated_follower_average_posts'];
        $estimated_follower_average_followers = $authentique_data['result']['estimated_follower_average_followers'];
        $estimated_follower_average_following = $authentique_data['result']['estimated_follower_average_following'];
        $estimated_private_follower_percentage = $authentique_data['result']['estimated_follower_average_following'];
        $estimated_follower_engagement_rate_likes = $authentique_data['result']['estimated_follower_engagement_rate_likes'];
        $estimated_follower_engagement_rate_comments = $authentique_data['result']['estimated_follower_engagement_rate_comments'];
        $estimated_engagement_rate_percentage = $authentique_data['result']['estimated_engagement_rate_percentage'];
        $audit_progress_percentage = $authentique_data['result']['audit_progress_percentage'];

        if( $authentique_data['result']['is_completed'] ) {

          update_user_meta( $uid, 'authentique_audit_completed', 'true' );

        } else {

          update_user_meta( $uid, 'authentique_audit_completed', false );
        }

        update_user_meta( $uid, 'authentique_estimated_real_follower_percentage', $estimated_real_follower_percentage );
        update_user_meta( $uid, 'authentique_media_posts', $media_posts );
        update_user_meta( $uid, 'authentique_average_likes', $average_likes );
        update_user_meta( $uid, 'authentique_average_comments', $average_comments );
        update_user_meta( $uid, 'authentique_expected_likes', $expected_likes );
        update_user_meta( $uid, 'authentique_expected_comments', $expected_comments );
        update_user_meta( $uid, 'authentique_estimated_follower_average_posts', $estimated_follower_average_posts );
        update_user_meta( $uid, 'authentique_estimated_follower_average_followers', $estimated_follower_average_followers );
        update_user_meta( $uid, 'authentique_estimated_follower_average_following', $estimated_follower_average_following );
        update_user_meta( $uid, 'authentique_estimated_private_follower_percentage', $estimated_private_follower_percentage );
        update_user_meta( $uid, 'authentique_estimated_follower_engagement_rate_likes', $estimated_follower_engagement_rate_likes );
        update_user_meta( $uid, 'authentique_estimated_follower_engagement_rate_comments', $estimated_follower_engagement_rate_comments );
        update_user_meta( $uid, 'authentique_estimated_engagement_rate_percentage', $estimated_engagement_rate_percentage );
        update_user_meta( $uid, 'authentique_audit_progress_percentage', $audit_progress_percentage );
      }
    } else {

      $result = create_influencer_audit( $username );

      if( $result['success'] ) {

        update_user_meta( $uid, 'authentique_audit_connected', 'true' );
        update_user_meta( $uid, 'authentique_account_private', false );
        update_user_meta( $uid, 'authentique_account_no_followers', false );

      } else {

        if( $result['error_code'] == 'account_private' ) {

          update_user_meta( $uid, 'authentique_account_private', true );
          $response['errors'] .= 'Private account on first connect';

        } else if ( $result['error_code'] == 'account_not_found' ) {

          update_user_meta( $uid, 'authentique_account_not_found', true );
          $response['errors'] .= 'Account not found on first connect';

        } else if( $result['error_code'] == 'account_no_followers' ) {

          update_user_meta( $uid, 'authentique_account_no_followers', true );
          $response['errors'] .= 'No followers on first connect';

        } else {

          $response['errors'] = $result;
        }
      }
    }
  }

  $response = json_encode($response);

  exit($response);
}

/**
 * Returns an array of all users with instagram handles and no authentique user not found error
 */
add_action( 'wp_ajax_load_regions_select_choices', 'ajax_load_regions_select_choices' );

function ajax_load_regions_select_choices() {

  global $wpdb;

  $countries = $_POST['countries'];
  $include_exclude = $_POST['include_exclude'];
  $regions = array();

  if($include_exclude == 'include') {

    foreach( $countries as $country ) {

      $query = '
         SELECT country_code, state_code, state_name
         FROM states
         WHERE country_code = "' . $country . '"
         ORDER BY state_name
       ';
      $current_regions = $wpdb->get_results($query);

      $regions = array_merge($regions, $current_regions);
    }

    echo json_encode($regions);
    exit;

  } else {
    $query = 'SELECT country_code, state_code, state_name
    FROM states
    WHERE country_code NOT IN("' . implode('","', $countries) . '")';

    $regions = $wpdb->get_results($query);
    echo json_encode($regions);
    exit;
  }
  exit;
}

add_action( 'acf/load_field/name=regions', 'load_regions_select_choices' );

function load_regions_select_choices($field) {

  global $post;
  if( !$post || !isset($post->ID) || get_post_type($post->ID) != 'product') {
    return $field;
  }

  global $wpdb;

  $pid = $post->ID;

  $include_exclude = get_post_meta( $pid, 'countries_include_exclude', true );
  $countries = get_post_meta( $pid, 'countries', true );
  $regions = array();

  if($countries) {
    if($include_exclude == 'include') {

      foreach( $countries as $country ) {

        $query = '
          SELECT country_code, state_code, state_name
          FROM states
          WHERE country_code = "' . $country . '"
          ORDER BY state_name
        ';
        $current_regions = $wpdb->get_results($query);

        $regions = array_merge($regions, $current_regions);
      }

    } else {
      $query = 'SELECT country_code, state_code, state_name
      FROM states
      WHERE country_code NOT IN("' . implode('","', $countries) . '")';

      $regions = $wpdb->get_results($query);
    }
  }

  $choices = array();

  foreach( $regions as $region ) {
    $choices[$region->country_code . $region->state_code] = $region->state_name;
  }

  $field['choices'] = $choices;

  return $field;
}

add_action( 'wp_ajax_generate_affiliate_data', 'generate_affiliate_data' );

function generate_affiliate_data() {

  $uid = get_current_user_id();

  $affiliate_status = get_user_meta($uid, 'affiliate_status', true);

  $response = array('errors'=>'');

  $link_id = $_POST['link_id'];

  $owner_id = get_post_meta($link_id, 'owner_id', true);

  if($owner_id != $uid) {
    $response['errors'] .= 'Whoops, something went wrong. Please try again.';
    exit($response);
  }

  update_user_meta( $uid, 'selected_affiliate_link', $link_id );

  $param_string = get_post_meta($link_id, 'param_string', true);

  $start_date = 0;
  $end_date = date('M d Y');
  $link_data = get_affiliate_link_data($link_id, $start_date, $end_date);

  $signup_payout_amount = .5;
  $shoutout_payout_amount = 4.5;

  $affiliate_paid_total = 0;

  $signup_payout_total = number_format(count($link_data['signups']) * $signup_payout_amount, 2, '.', ',');
  $shoutout_payout_total = number_format(count($link_data['shoutouts']) * $shoutout_payout_amount, 2, '.', ',');

  $data_by_month = array();

  $signup_link = get_site_url() . '/influencer-signup/' . $param_string;

  ob_start();

  ?>
    <!-- TEST MODE -->
    <?php echo SAS_TEST_MODE ? '<h2>LinkID:' . $link_id . '</h2>' : '' ?>
    <!-- /TEST MODE -->
    <h1>Welcome to the ShopandShout Ambassador Program</h1>
    <h3>This is your opportunity to earn some extra cash!</h3>
    <p>If your friends and followers would like to experience some awesome products from our marketplace, share these links with them. You'll earn $5 each time someone signs up, and completes a campaign.</p>
    Custom Link (Use this link to refer friends and people in your social channels): <a href="<?php echo $signup_link; ?>"><?php echo $signup_link; ?></a>
    <br><br>
    <iframe src="https://www.facebook.com/plugins/share_button.php?href=<?php echo $signup_link; ?>/&layout=button&size=large&appId=1816071558669484&width=73&height=28" width="73" height="28" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>
    <br><br>
    <!-- <a href="<?php //echo get_site_url() . '/affiliate-campaign/?edit=' . $link_id; ?>" class="sas-mini-form-button">Edit Referral Link</a> -->
    <div class="affiliate-data-tables">
      <table class="totals">
        <tr>
          <th>Signups<br>($0.50 per Signup)</th>
          <th>Completed ShoutOuts<br>($4.50 per ShoutOut)</th>
        </tr>
        <tr>
          <td><?php echo esc_html(count($link_data['signups'])); ?></td>
          <td><?php echo esc_html(count($link_data['shoutouts'])); ?></td>
        </tr>
        <tr>
          <td>$<?php echo esc_html($signup_payout_total); ?></td>
          <td>$<?php echo esc_html($shoutout_payout_total); ?></td>
        </tr>
      </table>
      <div class="payouts">
        <div class="owed">Owed: $<?php echo esc_html(number_format(($signup_payout_total + $shoutout_payout_total) - $affiliate_paid_total, 2, '.', ',')) ?></div>
        <div class="payed">Paied: $<?php echo esc_html($affiliate_paid_total); ?></div>
        <div class="payout">
        <button class="sas-text-button small block" id="affiliate-payout-info-show">How do I get paid?</button>
        <div id="affiliate-payout-info">
          <button class="sas-text-button small block" id="affiliate-payout-info-close">Close</button>
          Once you've earned $25, we'll send you our Elite Influencer Visa Rewards card.
          <br><br>
          <img src="<?php echo get_stylesheet_directory_uri() . '/images/my-account/affiliate/elite-visa.png' ?>">
          <br><br>
          We'll continue to load it monthy, automatically, as you go!
        </div>
        </div>
      </div>
    </div>
    <div class="affiliate-data-charts">
      <canvas id="affiliate-signup-chart"></canvas>

      <?php
        // foreach($link_data['signups'] as $signup) {
        //   $signup_year = date('Y',strtotime($signup['signup_date']));
        //   $signup_month = date('n',strtotime($signup['signup_date']));

        //   $data_by_month[$signup_year][$signup_month]['signup_count']++;

        //   if($signup['completed_shoutouts']) {
        //     foreach( $signup['completed_shoutouts'] as $completed_shoutout ) {
        //       $shoutout_year = date('Y',strtotime($completed_shoutout['date_created']));
        //       $shoutout_month = date('n',strtotime($completed_shoutout['date_created']));

        //       $data_by_month[$shoutout_year][$shoutout_month]['completed_shoutout_count']++;
        //     }
        //   }
        // }
      ?>

      <script>

      // var ctx = document.getElementById('affiliate-signup-chart');
      // var signupChart = new Chart(ctx, {
          // type: 'bar',
          // data: {
              // labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Nov', 'Dec'],
              // datasets: [
                // {
                  // label: '# of completed ShoutOuts',
                  // data: [<?php //for($x=1; $x<=12; $x++){
                    // echo (isset($data_by_month['2019'][$x]['completed_shoutout_count']) ? $data_by_month['2019'][$x]['completed_shoutout_count'] : 0) . ',';
                  // }
                  ?>
                  //],
                   //backgroundColor: '#0099ff',
                 //},
                 //{
                   //label: '# of signups',
                   //data: [<?php //for($x=1; $x<=12; $x++){
                    // echo (isset($data_by_month['2019'][$x]['signup_count']) ? $data_by_month['2019'][$x]['signup_count'] : 0) . ',';
                  // }
                  ?>
                  //],
                   //backgroundColor: '#b8e2ff',
                //},
              //]
          //},
          //options: {
          //    scales: {
           //       yAxes: [{
             //         ticks: {
               //           beginAtZero: true,
                 //         stacked: true,
                   //   }
                 // }],
                 // xAxes: [{ stacked: true, }]
            //  }
        //  }
     // });
      </script>
    </div>

  <?php

  $response['content'] = ob_get_clean();

  $response = json_encode($response);

  exit($response);
}

//-----------------------------//
//------ Brand Dashboard ------//
//-----------------------------//
add_action( 'wp_ajax_load_brand_dashboard', 'load_brand_dashboard' );
function load_brand_dashboard() {
  $response = array();

  // User Info
  $user = wp_get_current_user();
  $user_id = $user->ID;
  $first_name = $user->first_name;
  $last_name = $user->last_name;
  $phone = get_user_meta($user_id, 'contact_phone', true);
  $email_opt_out = get_user_meta($user_id, 'email_opt_out', true);
  
  // Brand Info
  $brand_id = $_POST['brand_id'];
  $brand = get_post($brand_id);
  $brand_name = get_the_title($brand_id);
  $brand_story = $brand->post_content;
  $brand_website = get_post_meta($brand_id, 'brand_website', true );
  $brand_user_entries = get_brand_user_entries_by_brand_id($brand_id);
  ob_start();
  
  $campaigns = get_posts(array(
    'post_type' => 'product',
    'meta_key' => 'brand',
    'meta_value' => $brand_id,
    'numberposts' => -1,
    'post_status' => 'any',
  ));

  $dashboard_selected_campaign = get_user_meta($user_id, 'dashboard_selected_campaign', true);
  $current_section = $_POST['current_section']?$_POST['current_section']:'campaigns';

  $brand_user_role = check_brand_user_role($brand_id, $user_id);

  update_user_meta($user_id, 'dashboard_selected_brand', $brand_id);

  ob_start();

  if($brand_user_role || in_array('administrator', $user->roles, true)) { // User is a member of this brand
    ?>

    <section class="dashboard-section <?php echo $current_section=='campaigns'?'selected':'' ?>" data-dashboard-section="campaigns">
      <div id="single-campaign">
        <?php if($campaigns): ?>

          <div class="single-campaign__header">

            <?php if(count($campaigns)>1): ?>
              <span class="single-campaign__title">Campaigns </span>

              <select id="campaign-select">
                <?php foreach($campaigns as $campaign): ?>
                  <option value="<?php echo esc_attr($campaign->ID); ?>" <?php echo $dashboard_selected_campaign==$campaign->ID?'selected':''; ?>>
                    <?php echo get_the_title($campaign->ID)?get_the_title($campaign->ID):'Untitled Campaign'; ?><?php echo $campaign->post_status!='publish'?' ('.$campaign->post_status.')':''; ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <a disabled="disabled" id="edit-campaign-button" class="sas-mini-form-button" href="">Edit</a>

            <?php else: ?>

              <input type="hidden" id="campaign-select" value="<?php echo esc_attr($campaigns[0]->ID); ?>">
              <span class="single-campaign__title"><?php echo get_the_title($campaigns[0]->ID)?get_the_title($campaigns[0]->ID):'Untitled Campaign'; ?><?php echo $campaigns[0]->post_status!='publish'?' ('.$campaigns[0]->post_status.')':''; ?> </span>
              <a class="sas-mini-form-button" href="<?php echo get_site_url() . '/design-campaign/?b='.$brand_id.'&c='.$campaigns[0]->ID; ?>">Edit</a>

            <?php endif; ?>
            <a class="sas-mini-form-button blue" href="<?php echo get_site_url() . '/design-campaign/?b='.$brand_id ?>">+ New Campaign</a>

            <div class="timeline-container">
              <label>Timeline</label>
              <select id="timeline-type-select">
                <option value="lifetime" selected>Lifetime</option>
                <option value="range">Date Range</option>
              </select>
              <div class="timeline-range-container">
                <label>From:</label>
                <input type="text" id="date-range-from" autocomplete="false">
                <label>To:</label>
                <input type="text" id="date-range-to" autocomplete="false">
                <button class="sas-mini-form-button" id="date-range-filter" disabled="disabled">Filter</button>
              </div>
            </div>
          </div>

          <div class="single-campaign__body">
            <div class="campaign-content">
              <div class="campaign-analytics"></div>

              <div id="campaign-loading">
                <div class="campaign-loading__inner">
                  <div class="loading-heart">
                    <span>Loading</span> 
                    <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/hearts/blue-heart-filled.svg'; ?>">
                  </div>
                </div>
              </div>

              <div class="campaign-orders"></div>
            </div>
          </div>

        <?php else: ?> <!-- if($campaigns) -->

          <div class="campaigns__no-campaigns">
            <div>
              <p>You haven't set up a ShoutOut Campaign for <?php echo esc_html($brand_name); ?> yet.</p>
              <a class="sas-round-button-primary" href="<?php echo get_site_url() . '/design-campaign/?b=' . $brand_id; ?>">Design a new ShoutOut Campaign</a>
            </div>
          </div>

        <?php endif; ?>
      </div>
    </section>

    <section class="dashboard-section <?php echo $current_section=='account-settings'?'selected':'' ?>" data-dashboard-section="account-settings">
      <h1>Account</h1>
      <div id="account-settings">
        <h2><?php echo esc_html($brand_name); ?> Settings</h2>

        <section id="settings-users" class="account-settings-section">
          <div>
            <h3>Users</h3>
            <table class="brand-user-table">
              <tr>
                <th>User</th>
                <th>email</th>
                <th>Role</th>
              </tr>
              <?php foreach($brand_user_entries as $entry) :
                $user_data = get_userdata($entry['user']);
                $full_name = $user_data->first_name . ' ' . $user_data->last_name;
                $email = $user_data->user_email;
              ?>
              <tr>
                <td><?php echo esc_html($full_name); ?></td>
                <td><?php echo esc_html($email); ?></td>
                <td><?php echo esc_html($entry['role']); ?></td>
              </tr>
              <?php endforeach; ?>
            </table>
          </div>

          <div>
            <h3>Invite a new user</h3>
            <form class="sas-form mini" id="brand-user-invite">
              <div class="form-errors"></div>
              <div class="form-section">
                <div class="form-row">
                  <div class="form-item">
                    <label>Role</label>
                    <select id="role" required>
                      <option value="">Select a user role</option>
                      <option value="campaign_manager">Campaign Manager</option>
                      <option value="administrator">Administrator</option>
                    </select>
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-item">
                    <label>email</label>
                    <input type="text" name="email" id="email" required>
                  </div>
                </div>
              </div>
              <button type="submit">Invite</button>
              <input type="hidden" id="brand-id" value="<?php echo esc_attr($brand_id); ?>">
              <div class="success-message" style="display: none;"></div>
            </form>
          </div>
        </section>

        <section id="settings-brand-info" class="account-settings-section">
          <h3>Brand Info</h3>
          <a class="sas-mini-form-button" href="<?php echo get_site_url() . '/add-brand/'; ?>">Create a new Brand</a>
          <form id="brand-info-form" class="sas-form" method="post">

            <div class="form-errors"></div>

            <div class="form-section">
              <div class="form-row">
                <div class="form-item">
                  <label>Brand Name</label>
                  <input type="text" id="brand-name" value="<?php echo esc_attr($brand_name); ?>">
                </div>
                <div class="form-item">
                  <label>Brand Website</label>
                  <input type="text" name="brand_website" id="brand-website" value="<?php echo esc_attr($brand_website); ?>">
                </div>
              </div>
              <div class="form-row">
                <div class="form-item">
                  <label for="story">Brand Story <i data-toggle="tooltip" title="What makes your brand special, and unique? How would an Influencer relate to your brand? Good stories rock! " class="fa fa-question-circle grey-icon" ></i></label>
                  <textarea id="brand-story" rows=5><?php echo esc_html($brand_story); ?></textarea>
                </div>
              </div>
            </div>
            <button type="submit" class="sas-round-button primary blue-black">Save</button>
            <input type="hidden" id="brand-id" value="<?php echo esc_attr($brand_id); ?>">
          </form>
        </section>

        <section id="account-settings-personal">
          <h3>Contact Info</h3>
          <form id="brand-account-info-form" class="sas-form">

            <div class="form-errors"></div>

            <div class="form-section">
              <div class="form-row">
                <div class="form-item">
                  <label>Contact First Name</label>
                  <input type="text" id="contact-first-name" value="<?php echo esc_attr($first_name); ?>">
                </div>
                <div class="form-item">
                  <label>Contact Last Name</label>
                  <input type="text" id="contact-last-name" value="<?php echo esc_attr($last_name); ?>">
                </div>
              </div>

              <div class="form-row">
                <div class="form-item">
                  <label>Contact Phone</label>
                  <input type="text" name="contact_phone" id="contact-phone" value="<?php echo esc_attr($phone); ?>">
                </div>
              </div>

              <div class="form-row">
                <label>
                  <input type="checkbox" id="email-opt-out" <?php echo $email_opt_out?'checked':''; ?>>
                  <span>Opt out of campaign emails</span>
                </label>
              </div>
            </div>
            <?php wp_nonce_field('brand_account_info', 'brand_account_info_nonce'); ?>
            <button class="sas-round-button primary blue-black" type="submit">Save</button>
          </form>
        </section>
      </div>
    </section>

    <section class="dashboard-section <?php echo $current_section=='ig-assistant'?'selected':'' ?>" data-dashboard-section="ig-assistant">
      <h1>Instagram Assistant</h1>
      <div id="instagram-assistant">
        <iframe frameborder="0" style="background:transparent;height:2000px;width:99%;border:none;" src='https://forms.zohopublic.com/dacimovic/form/OnboardingFormShopnShout/formperma/6589YFphQ8mvsI8GFY71BZYngdIzGapv9HfYV7BdZlU'></iframe>
      </div>
    </section>

    <?php
  } else { // User is not a member of this brand
    ?>

    <div class="dashboard__not-a-member">
      <div class="dashboard__not-a-member-inner">
        <h1>Looks like you aren't a member of <?php echo esc_html($brand_name); ?></h1>
        <p>Please refresh the page, if the issue isn't resolved contact brands@shopandshout.com for support.</p>
      </div>
    </div>

    <?php
  }

  $response['content'] = ob_get_clean();

  exit(json_encode($response));
}

add_action( 'wp_ajax_load_campaign_analytics', 'load_campaign_analytics' );
function load_campaign_analytics() {

  $response = array('errors'=>'');

  $campaign_id = $_POST['campaign_id'];

  $date_range = $_POST['date_range'];

  if($campaign_id) {

    ob_start();
    
    $campaign_data = get_campaign_shoutout_data( $campaign_id, $date_range );
    $fulfillment_type = get_post_meta($campaign_id, 'fulfillment_type', true);
    if($fulfillment_type == 'shipping') {
      $activated_message = 'Influencers you have activated by indicating their product has been shipped';
    } elseif($fulfillment_type == 'code'){
      $activated_message = 'Influencers you have activated by providing their web redemption code';
    } else {
      $activated_message = 'Influencers you have activated by providing their ShoutOut instructions';
    }

    if($campaign_data['total_opted_in']) {
      $reach_improvement = $campaign_data['goal_reach']?round(((int)$campaign_data['total_reach']/(int)$campaign_data['goal_reach'])*100,1):0;
      $engagement_improvement = $campaign_data['projected_engagement_wghtavg']?round(((float)$campaign_data['engagement_wghtavg']/(float)$campaign_data['projected_engagement_wghtavg'])*100,1):0;
    ?>
      <div class="date-range">
        <?php echo date('M d Y',strtotime($campaign_data['date_range']['from'])); ?> - <?php echo date('M d Y',strtotime($campaign_data['date_range']['to'])); ?>
      </div>
      <br>
      <div class="campaign-analytics-grid">
        <div class="analytics-grid__basic-metrics">
          <div class="grid-card impressions">
            <span class="card-title">IMPRESSIONS</span>
            <div class="card-body number-metric basic-metrics">
              <?php echo $campaign_data['goal_reach']?'<span class="metric-projection">Goal: '.number_format((int)$campaign_data['goal_reach']*2.5, 0, '.', ',').'</span>':''; ?>

              <span class="metric-achieved">Achieved: <span class="primary-metric" data-decimals="0" data-value="<?php echo esc_html( round((int)$campaign_data['total_reach'] * 2.5) ); ?>"><?php echo esc_html( number_format(round((int)$campaign_data['total_reach'] * 2.5)) ); ?></span></span>

              <?php if($reach_improvement): ?>
                <span class="metric-goal-percentage <?php echo $reach_improvement>=100?'improvement':''; ?>">
                  <?php echo $reach_improvement ?>%
                </span>
              <?php endif; ?>
            </div>
          </div>
          <div class="grid-card reach">
            <span class="card-title">REACH</span>
            <div class="card-body number-metric basic-metrics">
              <?php echo $campaign_data['goal_reach']?'<span class="metric-goal">Goal: '.number_format($campaign_data['goal_reach'], 0, '.', ',').'</span>':''; ?>

              <span class="metric-achieved">Achieved: <span class="primary-metric" data-decimals="0" data-value="<?php echo round((int)$campaign_data['total_reach']); ?>"><?php echo number_format(round((int)$campaign_data['total_reach'])); ?></span></span>


              <?php if($reach_improvement): ?>
                <span class="metric-goal-percentage <?php echo $reach_improvement>=100?'improvement':''; ?>">
                  <?php echo $reach_improvement ?>%
                </span>
              <?php endif; ?>
            </div>
          </div>
          <div class="grid-card time-saved">
            <span class="card-title">TIME SAVED</span>
            <div class="card-body number-metric basic-metrics">
              <span class="primary-metric" data-decimals="1" data-append=" HRS" data-value="<?php echo esc_html( $campaign_data['total_enrolled'] * 2.5 ); ?>"><?php echo esc_html( $campaign_data['total_enrolled'] * 2.5 ); ?></span>
            </div>
          </div>
          <div class="grid-card engagement">
            <span class="card-title">AVG. ENGAGEMENT</span>
            <div class="card-body number-metric basic-metrics">
              <?php echo $campaign_data['projected_reach']?'<span class="metric-goal">Goal: '.$campaign_data['projected_engagement_wghtavg'].'%</span>':''; ?>

              <span class="metric-achieved">Achieved: <span class="primary-metric" data-decimals="2" data-append="%" data-value="<?php echo esc_html(round($campaign_data['engagement_wghtavg'], 2)); ?>"><?php echo esc_html(round($campaign_data['engagement_wghtavg'], 2)); ?></span></span>

              <?php if($engagement_improvement): ?>
                <span class="metric-goal-percentage <?php echo $engagement_improvement>=100?'improvement':''; ?>">
                  <?php echo $engagement_improvement ?>%
                </span>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="analytics-grid__basic-metrics">
          <div class="grid-card">
            <span class="card-title">INFLUENCER COUNT</span>
            <div class="card-body number-metric influencer-count">
              <div class="count-compare">
                
                <div class="count-compare__section activated">
                  <span class="count primary-metric" data-decimals="0" data-value="<?php echo esc_html( $campaign_data['total_activated'] ); ?>"><?php echo esc_html( $campaign_data['total_activated'] ); ?></span>
                  <span class="trend"></span>
                  <span class="title">ACTIVATED <i id="tooltip-thing" data-toggle="tooltip" class="fa fa-question-circle grey-icon" title="<?php echo $activated_message; ?>"></i></span>
                </div>

                <div class="count-compare__section opted-in">
                  <span class="count primary-metric" data-decimals="0" data-value="<?php echo esc_html( $campaign_data['total_opted_in'] ); ?>"><?php echo esc_html( $campaign_data['total_opted_in'] ); ?></span>
                  <span class="trend"></span>
                  <span class="title">OPTED IN <i id="tooltip-thing" data-toggle="tooltip" class="fa fa-question-circle grey-icon" title="Total qualified Influencers willing to participate in this Campaign"></i></span>
                </div>
              
              </div>
            </div>
          </div>
          <div class="grid-card">
            <span class="card-title">
              GOAL 
              <select id="goal-period-select">
                <option value="-1" selected>Lifetime</option>
                <option value="30">30 Day Goal</option>
                <option value="7">7 Day Goal</option>
              </select>
            </span>
            <div id="goal-progress-metric" class="card-body number-metric goal">
              <div class="goal-content">
              </div>
              <div class="goal-loading"><i class="fa fa-spinner fa-spin"></i></div>
            </div>
          </div>
        </div>

        <div class="grid-card">
          <span class="card-title">EARNED MEDIA VALUE</span>
          <div class="card-body number-metric media-value">
            <span class="primary-metric" data-prepend="$" data-value="<?php echo esc_html( $campaign_data['content_value'] ); ?>" data-decimals="0">$<?php echo esc_html( $campaign_data['content_value'] ); ?></span>
          </div>
        </div>
        <div class="grid-card">
          <span class="card-title">AUDIENCE DEMOGRAPHICS</span>
          <div class="card-body audience-demographics">
            <div class="demographics-section">
              <span class="section-title">Geography</span>
              <table>
                <tr><th>Canada</th><td><?php echo $campaign_data['geographic_data']['canada']['percent_average'] ?>%</td></tr>
                <tr><th>USA</th><td><?php echo $campaign_data['geographic_data']['us']['percent_average'] ?>%</td></tr>
                <tr><th>UK</th><td><?php echo $campaign_data['geographic_data']['uk']['percent_average'] ?>%</td></tr>
                <tr><th>Other</th><td><?php echo $campaign_data['geographic_data']['other']['percent_average'] ?>%</td></tr>
              </table>
              <span>(based on average estimated audience demographics)</span>
            </div>
          </div>
        </div>
      </div>
    <?php
    } elseif($date_range) {// if($campaign_data['total_influencers'])
    ?>
      <div class="campaign-analytics__no-influencers">
        <span>Looks like you didn't have any orders in that period.</span>
      </div>   
    <?php
    } else {
      echo ' ';
    }
    $response['content'] = ob_get_clean();

  } else {
    $response['errors'] = 'Campaign ID not set';
  }

  exit(json_encode($response));

}

add_action( 'wp_ajax_load_campaign_goal', 'load_campaign_goal' );
function load_campaign_goal() {

  $response = array('errors'=>'');

  $campaign_id = $_POST['campaign_id'];
  $period = $_POST['period'];

  ob_start();

  $campaign_goals = get_posts(array(
    'post_type' => 'campaign_goal',
    'post_status' => 'any',
    'numberposts' => -1,
    'meta_key' => 'start_date',
    'order' => 'ASC',
    'orderby' => 'meta_value',
    'meta_query' => array(
      array (
        'key' => 'campaign',
        'value' => $campaign_id,
      )
    )
  ));

  if($campaign_goals) {
    $start_date = get_post_meta($campaign_goals[0]->ID, 'start_date', true);
    $end_date = get_post_meta($campaign_goals[0]->ID, 'end_date', true);

    foreach($campaign_goals as $goal) {
      $new_start_date = get_post_meta($goal->ID, 'start_date', true);

      if(strtotime($new_start_date) && strtotime($new_start_date) > strtotime($start_date)) {
        $start_date = $new_start_date;
        $end_date = get_post_meta($goal->ID, 'end_date', true);
      }
    }
  } else {
    $start_date = get_the_date('Y-m-d H:i:s', $campaign_id);
  }

  if($period>0) {
    $period_seconds = $period * 24 * 60 * 60;
    $start_time = strtotime($start_date);
    $time_since_publish = time() - $start_time;
    $period_start_time = time() - $time_since_publish%$period_seconds;
    $period_end_time = $period_start_time+$period_seconds;
    $date_range = array(
      'from' => date('Y-m-d H:i:s', $period_start_time),
      'to' => date('Y-m-d H:i:s', $period_start_time+$period_seconds),
    );
  } else {
    $period_start_time = strtotime($start_date);
    $period_end_time = strtotime($end_date)?strtotime($end_date):time();
    $date_range = array(
      'from' => $start_date,
      'to' => $end_date?$end_date:date('Y-m-d H:i:s'),
    );
  }

  $goal_influencers = get_campaign_influencer_goal($campaign_id, $date_range);

  $response['percentage'] = round(($goal_influencers['enrolled']/$goal_influencers['goal'])*100,1);
  ?>
    <div class="goal__date-range">
      <span><?php echo date('M d Y', $period_start_time) . '-' . date('M d Y', $period_end_time); ?></span>
    </div>
    <span class="primary-metric">
      <span class="percentage-metric">0%</span>
      <br><br>
      <span class="subtext">
        <?php echo round($goal_influencers['enrolled']) ?> enrolled
        of
        <?php echo floor($goal_influencers['goal']) ?>
        <br>
        <span class="subtext-message">(Please cancel any Influencers you don't want to collaborate with)</span>
      </span>
    </span>
    <div class="goal__progress-bar">
      <div class="progress-bar-inner"></div>
    </div>
  <?php

  $response['content'] = ob_get_clean();

  exit(json_encode($response));
}

add_action( 'wp_ajax_load_campaign_orders', 'load_campaign_orders' );
function load_campaign_orders() {

  $response = array('errors'=>'');

  $campaign_id = $_POST['campaign_id'];

  if($campaign_id) {
    $user = wp_get_current_user();
    $user_id = $user->ID;

    update_user_meta($user_id, 'dashboard_selected_campaign', $campaign_id);

    $brand_id = get_post_meta($campaign_id, 'brand', true);
    $user_role = check_brand_user_role($brand_id, $user_id);

    $campaign_strategy = get_post_meta($campaign_id, 'campaign_strategy', true);
    $fulfillment_type = get_post_meta($campaign_id, 'fulfillment_type', true);
    $fulfillment_single_code = get_post_meta($campaign_id, 'fulfillment_single_code', true);
    $shoutout_channels = get_post_meta($campaign_id, 'shoutout_channels', true);

    $orders = get_orders_by_campaign_id($campaign_id);

    ob_start();
    ?>

    <?php if($user_role || in_array('administrator', $user->roles, true)): ?>

      <?php if($orders): ?>
        <span class="campaign-orders-table-title">INFLUENCERS OPTED IN</span>
        <table class="campaign-orders-table">
          <thead>
            <tr>
              <th style="text-align: center;" data-sortInitialOrder="asc">INFLUENCER</th>
              <th data-sortInitialOrder="desc">STATUS</th>
              <th data-sortInitialOrder="desc">OPT-IN DATE</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($orders as $order_id): 
              $influencer_id = get_order_influencer($order_id);
              
              if($influencer_id) :
                $order = wc_get_order($order_id);
                $order_status = $order->get_status();

                //Variations
                $order_items = $order->get_items();
                $item_product = reset($order_items);
                $product = $item_product->get_product();
                $variation_title = '';

                if($product->get_type() == 'variation') {
                  $variation_id = $product->get_variation_id();
                  $variation = new WC_Product_Variation($variation_id);
                  $attributes = $variation->get_attributes();

                  foreach($attributes as $key => $attribute) {
                    $attributes[$key] = ucwords(str_replace('-',' ',$attribute));
                  }

                  $variation_title = implode('<br>', $attributes);
                }

                // Score Disabled
                $order_date = $order->get_data()['date_created']->date('d-m-Y');
                $order_date_formatted = $order->get_data()['date_created']->date('m/d/Y');
                $new_date = strtotime( $order_date . ' +1 week' );
                $score_disabled = '';
                if ( strtotime( current_time( 'mysql' ) ) < $new_date ) {
                  $score_disabled = 'disabled';
                }

                // Shoutout Info
                $shoutout_links = array();
                $shoutout_scores = array();
                foreach( $shoutout_channels as $channel ) {
                  $channel_url = get_post_meta( $order_id, $channel . '_url', true );
                  $channel_score = get_post_meta($order_id, $channel . '_shoutout_rating', true);
                  $channel_notes = get_post_meta($order_id, $channel . '_shoutout_notes', true);
                  $shoutout_links[$channel] = $channel_url;
                  $shoutout_scores[$channel] = array(
                    'score' => $channel_score,
                    'notes' => $channel_notes,
                  );
                }
                $giveaway_winner = get_post_meta($order_id, 'giveaway_winner', true);

                // Influencer Info
                $inf_data = get_userdata($influencer_id);
                $instagram_handle = get_user_meta($influencer_id, 'social_prism_user_instagram', true);
                if($instagram_handle) {
                  $profile_url = get_site_url() . '/influencer/' . $instagram_handle;
                } else {
                  $profile_url = get_site_url() . '/influencer/' . $inf_data->user_nicename;
                }
                $first_name = $inf_data->first_name;
                $last_name = $inf_data->last_name;
                $profile_picture_url = get_user_meta( $influencer_id, 'inf_instagram_profile_picture', true );
                $influencer_score_data = influencer_get_score_data($influencer_id);
                $influencer_channel_data = array();

                foreach($shoutout_channels as $channel) {

                  $influencer_channel_data[$channel]['reach'] = get_user_meta($influencer_id, 'social_prism_user_' . $channel . '_reach', true);

                  $influencer_channel_data[$channel]['engagement'] = get_user_meta($influencer_id, $channel . '-engagement-rate', true);
                }

                // Shipping Info
                $shipping_country = get_user_meta( $influencer_id, 'shipping_country', true );
                $shipping_state = get_user_meta( $influencer_id, 'shipping_state', true );
                $shipping_city = get_user_meta( $influencer_id, 'shipping_city', true );
                $shipping_postcode = get_user_meta( $influencer_id, 'shipping_postcode', true );
                $shipping_address_1 = get_user_meta( $influencer_id, 'shipping_address_1', true );
                $shipping_address_2 = get_user_meta( $influencer_id, 'shipping_address_2', true );

                // Status Message
                $status = get_order_shoutout_status($order_id);
                $status_color = '';
                $status_message = '';
                if( $order_status == 'cancelled' || $order_status == 'refunded' ) {
                  $status_message = 'Cancelled';
                  $status_color = 'grey';
                } else if( $status === '' ) {
                  if( $fulfillment_type == 'shipping' ) {
                    $status_color = 'red';
                    $status_message = 'Let ' . $first_name . ' know that you have shipped their product.';
                  } else if( $fulfillment_type == 'code' ) {
                    if( $fulfillment_single_code == '' ) {
                      $status_color = 'red';
                      $status_message = 'Please send redemption code';
                    } else {
                      $status_color = 'yellow';
                      $status_message = 'Waiting for ' . $first_name . ' to give you a Shoutout';
                    }
                  } else {
                    $status_color = 'yellow';
                    $status_message = 'Waiting for ' . $first_name . ' to give you a Shoutout';
                  }
                } else if ( $status === 'post' ) {
                  $status_color = 'yellow';
                  $status_message = 'Waiting for ' . $first_name . ' to give you a Shoutout';
                } else if ( $status === 'review' ) {
                  $status_color = 'red';
                  $status_message = 'Please score your ShoutOut';
                  if ( $score_disabled === 'disabled' ) {
                    $status_message .= ' (can not score until one week after ShoutOut order creation)';
                  }
                } else {
                  $status_color = 'green';
                  $status_message = 'Complete';
                }
              ?>
                <tr class="<?php echo $order_status=='cancelled'||$order_status=='refunded'?'cancelled':''; ?>">
                  <td class="influencer-cell">
                    <div>
                      <?php if($order_status != 'cancelled' && $order_status != 'refunded'): ?>
                        <a target="_blank" href="<?php echo esc_url($profile_url); ?>"><?php echo esc_html($first_name . ' ' . $last_name); ?></a>
                        <br>
                        <?php if($variation_title): ?>
                          <section>
                            <b>Variation</b>
                            <br>
                            <span><?php echo $variation_title; ?></span>
                          </section>
                        <?php endif; ?>
                        <section class="influencer-info">
                          <?php if($profile_picture_url && check_remote_file($profile_picture_url)) : ?>
                            <div>
                              <div class="profile-pic">
                                <a href="<?php echo esc_url($profile_url) ?>" target="_blank"><img src="<?php echo esc_url($profile_picture_url); ?>"></a>
                              </div>
                            </div>
                          <?php endif; ?>
                          <?php if($fulfillment_type == 'shipping'): ?>
                            <div>
                              <b class="section-title">Shipping</b>
                              <br>
                              <span><?php echo $shipping_city.' '.$shipping_state.', '.$shipping_country.'<br>'.$shipping_address_1.($shipping_address_2?', '.$shipping_address_2:'').', '.$shipping_postcode; ?></span>
                            </div>
                          <?php endif; ?>
                        </section>

                        <div class="sas-accordion-group">
                          <div class="sas-accordion-container mini-accordion">
                            <div class="accordion-head">
                              More Info <div class="accordion-toggle"></div>
                            </div>
                            <div class="accordion-body" style="display: none;">
                              <section>
                              </section>

                              <section>
                                <?php foreach($shoutout_channels as $channel) : ?>
                                  <b><?php echo ucfirst($channel); ?></b><br>
                                  <span>Reach: <?php echo $influencer_channel_data[$channel]['reach']; ?></span>
                                  <br>
                                  <span>Engagement: <?php echo $influencer_channel_data[$channel]['engagement']; ?>%</span>
                                  <br>
                                <?php endforeach; ?>
                              </section>
                              
                              <section>
                                <span><b>Average Score:</b> <?php echo $influencer_score_data['average_score'] ? $influencer_score_data['average_score'] : 'N/A'; ?></span>
                              </section>
                            </div>
                          </div>
                        </div>
                      <?php else: ?>
                        <?php echo esc_html($first_name . ' ' . $last_name); ?>
                      <?php endif; ?>
                    </div>   
                  </td>
                  <td class="status-cell">
                    <div class="dashboard-table-status <?php echo $status_color; ?>">
                      <?php if($status_color == 'green'): ?>
                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/hearts/blue-heart-filled.svg'; ?>">
                      <?php elseif($status_color == 'red'): ?>
                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/hearts/red-heart-outline.svg'; ?>">
                      <?php else: ?>
                        <span class="status-indicator"></span>
                      <?php endif; ?>
                      <span class="status-message"><?php echo $status_message; ?></span>
                    </div> 
                  </td>
                  <td class="order-date-cell">
                    <?php echo esc_html($order_date_formatted); ?>
                  </td>
                  <td class="action-cell">
                    <?php if($giveaway_winner): ?>
                      <div class="giveaway_winner">
                        <label>Giveaway winner:</label>
                        <span><b><?php echo $giveaway_winner ?></b></span>
                      </div>
                      <br>
                    <?php endif; ?> <!-- if($giveaway_winner) -->
                    <?php if( $order_status == 'cancelled' || $order_status == 'refunded' ): ?>
                    <?php elseif( $status === '' ) : ?>

                      <?php if( $fulfillment_type == 'shipping' ) : ?>

                        <form class="tracking-link-form" method="post">
                          <div class="result-message"></div>
                          <span>Tracking URL:(optional) </span>
                          <br>
                          <input type="text" class="tracking_link" name="tracking_link">
                          <input type="hidden" name="shoutout" value="<?php echo $order_id; ?>">
                          <br>
                          <button class="sas-mini-form-button green">It's Shipping!</button>
                        </form>

                      <?php elseif( $fulfillment_type == 'code' && $fulfillment_single_code == '' ) : ?>

                        <form class="web-redemption-code-form" method="post">
                          <div class="result-message"></div>
                          <span>Enter here: </span>
                          <input type="text" class="code" name="code" required>
                          <input type="hidden" name="shoutout" value="<?php echo $order_id; ?>">
                          <button class="sas-mini-form-button green">Submit</button>
                        </form>

                      <?php else : ?>

                        <?php foreach ( $shoutout_links as $channel => $channel_url ) : ?>

                          <?php if ( $channel_url === '' ) : ?>

                            <div>
                              Waiting for <?php echo ucfirst($channel); ?> link
                            </div>

                          <?php endif; ?>

                        <?php endforeach; ?>

                      <?php endif; ?>
                      or<br>
                      <a href="<?php echo get_site_url() . '/reject-influencer/?order=' . $order_id; ?>" class="brand-reject-influencer">Cancel this <?php echo ucfirst($campaign_strategy); ?></a>

                    <?php else : ?><!-- $status !== '' -->

                      <?php foreach ( $shoutout_channels as $channel ) : ?>

                        <?php if ( $shoutout_links[$channel] === '' ) : ?>

                          <div>Waiting for <?php echo ucfirst($channel); ?> link</div>

                          <a href="<?php echo get_site_url() . '/reject-influencer/?order=' . $order_id; ?>" class="brand-reject-influencer">Cancel this <?php echo ucfirst($campaign_strategy); ?></a>

                        <?php elseif( $shoutout_scores[$channel]['score'] == '' ) : ?>

                          <div>
                            <a target="_blank" href="<?php echo esc_url($shoutout_links[$channel]); ?>">View your ShoutOut on <?php echo ucfirst($channel) ?></a>
                            <form class="rating-form" method="post">
                              <div class="result-message"></div>
                              <ul class="hearts">
                                  <li class="heart" data-value="1">
                                    <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                    <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                                  </li>
                                  <li class="heart" data-value="2">
                                    <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                    <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                                  </li>
                                  <li class="heart" data-value="3">
                                    <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                    <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                                  </li>
                                  <li class="heart" data-value="4">
                                    <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                    <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                                  </li>
                                  <li class="heart" data-value="5">
                                    <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                    <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                                  </li>
                                </ul>
                              <input type="hidden" class="rating" name="rating" value="<?php echo $shoutout_scores[$channel]['score']; ?>">
                              <input type="hidden" name="channel" value="<?php echo $channel; ?>">
                              <input type="hidden" name="shoutout" value="<?php echo $order_id; ?>">
                              <button class="sas-mini-form-button" <?php echo esc_html( $score_disabled ); ?>>Score</button>
                              <div class="brand-rating-notes">
                                <textarea rows="3" placeholder="Write a message to <?php echo esc_attr( $first_name . ' ' . $last_name ); ?>"></textarea>
                              </div>
                              <span class="brand-shoutout-error">Error with this ShoutOut?</span>
                            </form>

                            <div class="shoutout-error-form-container">
                              <form class="shoutout-error-form" method="post">

                                <div class="result-message"></div>

                                <input type="hidden" name="channel" value="<?php echo $channel; ?>">

                                <input type="hidden" name="order" value="<?php echo $order_id; ?>">

                                <textarea rows="3" placeholder="Please let us know what the issue with this ShoutOut is"></textarea>

                                <br>

                                <button class="sas-mini-form-button">Send</button>
                              </form>
                            </div>
                          </div>

                        <?php else : ?> <!-- if( $shoutout_scores[$channel] !== '' && $shoutout_links[$channel]['score'] !== '' ) -->

                          <div class="shoutout-final-score-container">

                            <a target="_blank" href="<?php echo esc_attr($shoutout_links[$channel]); ?>"><?php echo ucfirst($channel); ?></a>

                            <div class="hearts-rating-container">

                              <?php  for ( $x = 0; $x < $shoutout_scores[$channel]['score']; $x++ ) : ?>

                                <img class="heart-icon" width="20" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">

                              <?php endfor; ?>

                              <?php  for ( $x = 5; $x > $shoutout_scores[$channel]['score']; $x-- ) : ?>

                                <img class="heart-icon" width="20" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">

                              <?php endfor; ?>

                            </div>



                            <?php if( $shoutout_scores[$channel]['notes'] !== '' ) : ?>

                              <br><br>

                              <b>Notes:</b>

                              <div class="brand-shoutout-notes"><?php echo esc_html($shoutout_scores[$channel]['notes']); ?></div>

                            <?php endif ?>

                            <br>

                            <button class="brand-re-score-button sas-mini-form-button">Re-score</button>

                          </div>

                          <div class="shoutout-re-score-container">
                            <a target="_blank" href="<?php echo esc_url($shoutout_links[$channel]); ?>">View your ShoutOut on <?php echo ucfirst($channel) ?></a>
                            <form class="rating-form" method="post">
                              <div class="result-message"></div>
                              <ul class="hearts">
                                  <li class="heart" data-value="1">
                                    <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                    <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                                  </li>
                                  <li class="heart" data-value="2">
                                    <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                    <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                                  </li>
                                  <li class="heart" data-value="3">
                                    <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                    <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                                  </li>
                                  <li class="heart" data-value="4">
                                    <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                    <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                                  </li>
                                  <li class="heart" data-value="5">
                                    <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                    <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                                  </li>
                                </ul>
                              <input type="hidden" class="rating" name="rating" value="<?php echo $shoutout_scores[$channel]['score']; ?>">
                              <input type="hidden" name="channel" value="<?php echo $channel; ?>">
                              <input type="hidden" name="shoutout" value="<?php echo $order_id; ?>">
                              <button class="sas-mini-form-button" <?php echo esc_html( $score_disabled ); ?>>Score</button>
                              <div class="brand-rating-notes">
                                <textarea rows="3" placeholder="Write a message to <?php echo esc_attr( $first_name . ' ' . $last_name ); ?>"></textarea>
                              </div>
                              <span class="brand-shoutout-error">Error with this ShoutOut?</span>
                            </form>

                            <div class="shoutout-error-form-container">
                              <form class="shoutout-error-form" method="post">

                                <div class="result-message"></div>

                                <input type="hidden" name="channel" value="<?php echo $channel; ?>">

                                <input type="hidden" name="order" value="<?php echo $order_id; ?>">

                                <textarea rows="3" placeholder="Please let us know what the issue with this ShoutOut is"></textarea>

                                <br>

                                <button class="sas-mini-form-button">Send</button>
                              </form>
                            </div>
                          </div>

                        <?php endif; ?>

                      <?php endforeach; ?><!-- foreach ( $shoutout_links as $channel => $channel_url ) -->

                    <?php endif; ?>
                  </td>
                </tr>
              <?php else: ?> <!-- if($influencer_id) -->
                <tr style="text-align: center;">
                  <td>Order# <?php echo $order_id; ?></td>
                  <td>User no longer exists</td>
                  <td></td>
                </tr>
              <?php endif; ?> <!-- if($influencer_id) -->
            <?php endforeach; ?>
          </tbody>
        </table>

      <?php else: ?><!-- if($orders) -->

        <div class="campaign-no-orders"><span>No ShoutOuts so far.</span></div>

      <?php endif; ?><!-- if($orders) -->

    <?php else: ?><!-- if($user_role || in_array('administrator', $user->roles, true)) -->

      <div class="campaign__not-a-member">
        <div class="campaign__not-a-member-inner">
          <h1>Looks like you don't have access to this Campaign</h1>
          <p>Please refresh the page, if the issue isn't resolved contact brands@shopandshout.com for support.</p>
        </div>
      </div>

    <?php endif; ?><!-- if($user_role || in_array('administrator', $user->roles, true)) -->

    <?php

    $response['content'] = ob_get_clean();

  } else {
    $response['errors'] = 'Campaign ID not set';
  }

  exit(json_encode($response));

}

add_action( 'wp_ajax_generate_campaign_select_options', 'generate_campaign_select_options' );

function generate_campaign_select_options() {

  $response = array('errors'=>'');

  $user = wp_get_current_user();
  $uid = $user->ID;

  $brand_id = $_POST['brand_id'];

  if( in_array('administrator', $user->roles) ) {

    $campaigns = get_posts(array(
      'posts_per_page' => -1,
      'post_type' => 'product',
      'post_status' => array('draft', 'pending', 'publish', 'private'),
    ));

  } else if ($brand_id) {

    $campaigns = get_posts(array(
      'post_type' => 'product',
      'posts_per_page' => -1,
      'post_status' => array('draft', 'publish', 'pending', 'private'),
      'meta_key' => 'brand',
      'meta_value' => $brand_id,
    ));
  } else {
    $campaigns = array();
  }

  if(!empty($campaigns)) :

    ob_start();


    foreach($campaigns as $campaign) :

      $campaign_title = get_the_title($campaign->ID);
      $campaign_id = $campaign->ID;
      $status = $campaign->post_status;

      ?>

      <option value="<?php echo esc_attr($campaign_id); ?>"><?php echo esc_html($campaign_title) . ($status != 'publish' ? ' (' . $status . ')' : ''); ?></option>

      <?php

    endforeach;

    $response['content'] = ob_get_clean();

  else :

    $response['content'] = false;

  endif;

  $response = json_encode($response);

  exit($response);
}

add_action( 'wp_ajax_generate_campaign_info', 'generate_campaign_info' );

function generate_campaign_info() {

  $response = array('errors'=>'');

  $uid = get_current_user_id();

  // Campaign Info
  $campaign_id = $_POST['campaign_id'];
  $brand_id = isset($_POST['brand_id'])?$_POST['brand_id']:'';

  $product = new WC_Product($campaign_id);

  $response['campaign_status'] = $product->get_status();

  ob_start();

  if($campaign_id) {

    $orders = get_orders_by_campaign_id( $campaign_id );

    ?>

    <div>

      <?php if($orders) :

        $fulfillment_type = get_post_meta( $campaign_id, 'fulfillment_type', true );

        if($fulfillment_type == 'code') {
          $fulfillment_single_code = get_post_meta( $campaign_id, 'fulfillment_single_code', true );
        }

        $shoutout_channels = get_post_meta( $campaign_id, 'shoutout_channels', true );

        $campaign_data = get_campaign_shoutout_data( $campaign_id );

        //TODO: Replace with sorting
        $orders = array_reverse($orders);
      ?>

      <!-- TEST MODE -->
      <?php echo (SAS_TEST_MODE ? '<h4>CampaignID: ' . $campaign_id . '</h4>' : ''); ?>
      <!-- TEST MODE -->

      <div class="sas-accordion-group">
        <div class="sas-accordion-container title-accordion">
          <div class="accordion-head">
            <span>Campaign Analytics</span>
            <div class="accordion-toggle"></div>
          </div>

          <div class="accordion-body">
            <div class="analytics-panel-wrapper">
              <div class="panel">

                <h4>Summary</h4>
                <table class="summary-table">
                  <tr>
                    <td>Enrolled Influencers</td>
                    <td><?php echo esc_html( $campaign_data['total_influencers'] ); ?></td>
                  </tr>
                  <tr>
                    <td>Active Influencers</td>
                    <td><?php echo esc_html( $campaign_data['total_active'] ); ?></td>
                  </tr>
                  <tr>
                    <td>Time Saved</td>
                    <td><?php echo esc_html( $campaign_data['total_influencers'] * 2.5 ); ?>hrs</td>
                  </tr>
                  <tr>
                    <td>Content Value</td>
                    <td>$<?php echo esc_html( $campaign_data['content_value'] ); ?></td>
                  </tr>
                  <tr>
                    <td>Impressions</td>
                    <td><?php echo esc_html( number_format(round((int)$campaign_data['total_reach'] * 2.5)) ); ?></td>
                  </tr>
                  <tr>
                    <td>Average Engagement Rate</td>
                    <td><?php echo esc_html(round($campaign_data['engagement_wghtavg'], 2)); ?>%</td>
                  </tr>
                </table>
              </div>

              <div class="panel">
                <h4>Demographics</h4>
                <h5>Geography</h5>
                <table>
                  <tr><th>Canada</th><td><?php echo $campaign_data['geographic_data']['canada']['percent_average'] ?>%</td></tr>
                  <tr><th>USA</th><td><?php echo $campaign_data['geographic_data']['us']['percent_average'] ?>%</td></tr>
                  <tr><th>UK</th><td><?php echo $campaign_data['geographic_data']['uk']['percent_average'] ?>%</td></tr>
                  <tr><th>Other</th><td><?php echo $campaign_data['geographic_data']['other']['percent_average'] ?>%</td></tr>
                </table>
                <span>(based on average estimated audience demographics)</span>
              </div>
            </div>
            <div class="analytics-panel-wrapper">
              <div class="panel">
                <h4>Channels</h4>
                <?php foreach($shoutout_channels as $channel) : ?>
                  <h5><?php echo ucfirst($channel) ?></h5>
                  <table class="summary-table">
                    <tr>
                      <td>Projected Reach</td>
                      <td><?php echo number_format($campaign_data['projected_reaches'][$channel]); ?></td>
                    </tr>
                    <tr>
                      <td>Estimated Engagement</td>
                      <td><?php echo $campaign_data['estimated_engagements'][$channel] ?>%</td>
                    </tr>
                  </table>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="campaign-shoutout-orders-wrapper">
        <h3>ShoutOut Orders</h3>
        <?php foreach($orders as $order_id) :
          $order = wc_get_order($order_id);

          // WC Order status
          $order_status = $order->get_status();
        ?>
          <?php if($order_status != 'cancelled' && $order_status != 'trash') :

            // Variations
            $order_items = $order->get_items();
            $item_product = reset($order_items);
            $product = $item_product->get_product();
            $variation_title = '';
            if( $product->get_type() == 'variation' ) {

              $variation_id = $product->get_variation_id();

              $variation = new WC_Product_Variation($variation_id);

              $attributes = $variation->get_attributes();

              foreach($attributes as $key => $attribute) {
                $attributes[$key] = ucwords(str_replace('-',' ',$attribute));
              }

              $variation_title = implode('<br>', $attributes);
            }

            // Score Disabled
            $order_date = $order->get_data()['date_created']->date('d-m-Y');
            $order_date_formatted = $order->get_data()['date_created']->date('d M Y');
            $new_date = strtotime( $order_date . ' +1 week' );
            $score_disabled = '';
            if ( strtotime( current_time( 'mysql' ) ) < $new_date ) {
              $score_disabled = 'disabled';
            }

            // Shoutout Info
            $shoutout_links = array();
            $shoutout_scores = array();
            foreach( $shoutout_channels as $channel ) {
              $channel_url = get_post_meta( $order_id, $channel . '_url', true );
              $channel_score = get_post_meta($order_id, $channel . '_shoutout_rating', true);
              $channel_notes = get_post_meta($order_id, $channel . '_shoutout_notes', true);
              $shoutout_links[$channel] = $channel_url;
              $shoutout_scores[$channel] = array(
                'score' => $channel_score,
                'notes' => $channel_notes,
              );
            }

            // Influencer Info
            $influencer_id = get_order_influencer($order_id);
            $inf_data = get_userdata($influencer_id);
            $instagram_handle = get_user_meta($influencer_id, 'social_prism_user_instagram', true);
            if($instagram_handle) {
              $profile_url = get_site_url() . '/influencer/' . $instagram_handle;
            } else {
              $profile_url = get_site_url() . '/influencer/' . $inf_data->user_nicename;
            }
            $first_name = $inf_data->first_name;
            $last_name = $inf_data->last_name;
            $profile_picture_url = get_user_meta( $influencer_id, 'inf_instagram_profile_picture', true );
            $influencer_score_data = influencer_get_score_data($influencer_id);
            $influencer_channel_data = array();

            foreach($shoutout_channels as $channel) {

              $influencer_channel_data[$channel]['reach'] = get_user_meta($influencer_id, 'social_prism_user_' . $channel . '_reach', true);

              $influencer_channel_data[$channel]['engagement'] = get_user_meta($influencer_id, $channel . '-engagement-rate', true);
            }

            // Status Message
            $status = get_order_shoutout_status($order_id);
            $status_color = '';
            $status_message = '';
            if( $status === '' ) {
              if( $fulfillment_type == 'shipping' ) {
                $status_message = 'Let ' . $first_name . ' know that you have shipped their product.';
              } else if( $fulfillment_type == 'code' ) {
                if( $fulfillment_single_code == '' ) {
                  $status_color = 'yellow';
                  $status_message = 'Please send redemption code';
                } else {
                  $status_color = 'yellow';
                  $status_message = 'Waiting for ' . $first_name . ' to give you a Shoutout';
                }
              } else {
                $status_color = 'yellow';
                $status_message = 'Waiting for ' . $first_name . ' to give you a Shoutout';
              }
            } else if ( $status === 'post' ) {
              $status_color = 'yellow';
              $status_message = 'Waiting for ' . $first_name . ' to give you a Shoutout';
            } else if ( $status === 'review' ) {
              $status_color = 'red';
              $status_message = 'Please score your ShoutOut';
              if ( $score_disabled === 'disabled' ) {
                $status_message .= ' (can not score until one week after ShoutOut order creation)';
              }
            } else {
              $status_color = 'green';
              $status_message = 'Complete';
            }
          ?>
          <div class="shoutout-order-row">
            <div class="shoutout-order-row-head">
              <div class="head-cell influencer-info">

                <!-- TEST MODE -->
                <?php echo (SAS_TEST_MODE ? '<b>OrderID: ' . $order_id . '</b><br><b>InfluencerID: ' . $influencer_id . '</b><br><br>' : ''); ?>
                <!-- TEST MODE -->

                <?php if($profile_picture_url && check_remote_file($profile_picture_url)) : ?>
                  <div class="profile-pic">
                    <a href="<?php echo esc_url($profile_url) ?>" target="_blank"><img src="<?php echo esc_url($profile_picture_url); ?>"></a>
                  </div>
                <?php endif; ?>
                <a href="<?php echo esc_url($profile_url); ?>" target="_blank"><?php echo esc_html( $first_name . ' ' . $last_name ); ?> (View Profile)</a>
                <br>

                <?php if($variation_title) : ?>
                  <span style="text-align: left;"><b><?php echo $variation_title; ?></b></span>
                  <br>
                <?php endif; ?>

                <span><?php echo esc_html($order_date_formatted); ?></span>
                <br>

                <div class="shoutout-order-influencer-more-info">
                  <span class="more-info-button">More Info <span class="toggle"></span></span>
                  </div>
                <div class="shoutout-order-info" style="display: none;">
                  <?php foreach($shoutout_channels as $channel) : ?>
                    <b><?php echo ucfirst($channel); ?></b><br>
                    <span>Reach: <?php echo $influencer_channel_data[$channel]['reach']; ?></span>
                    <br>
                    <span>Engagement: <?php echo $influencer_channel_data[$channel]['engagement']; ?>%</span>
                    <br>
                  <?php endforeach; ?>
                  <br>
                  <span><b>Average Score:</b> <?php echo $influencer_score_data['average_score'] ? $influencer_score_data['average_score'] : 'N/A'; ?></span>
                </div>

              </div>

              <div class="head-cell shoutout-status">
                <?php if( $status == '' ) : ?>
                  <?php if( $fulfillment_type == 'shipping' ) : ?>
                    <img class="status-icon" width="35" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/mailtruck.png'; ?>"> <?php echo $status_message; ?>
                  <?php else : ?>
                    <span class="status-indicator" style="background-color: <?php echo $status_color; ?>"></span> <?php echo $status_message; ?>
                  <?php endif; ?>
                <?php elseif( $status == 'review' ) : ?>
                  <img class="status-icon" width="18" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg' ?>"> <?php echo $status_message; ?>
                <?php else : ?>
                  <span class="status-indicator" style="background-color: <?php echo $status_color; ?>"></span> <?php echo $status_message; ?>
                <?php endif; ?>
              </div>

              <div class="head-cell quick-action">
                <?php if( $status === '' ) : ?>

                  <?php if( $fulfillment_type == 'shipping' ) : ?>

                    <form class="tracking-link-form" method="post">
                      <div class="result-message"></div>
                      <span>Tracking URL:(optional) </span>
                      <br>
                      <input type="text" class="tracking_link" name="tracking_link">
                      <input type="hidden" name="shoutout" value="<?php echo $order_id; ?>">
                      <br>
                      <button class="sas-mini-form-button">It's Shipping!</button>
                    </form>

                  <?php elseif( $fulfillment_type == 'code' && $fulfillment_single_code == '' ) : ?>

                    <form class="web-redemption-code-form" method="post">
                      <div class="result-message"></div>
                      <span>Enter here: </span>
                      <input type="text" class="code" name="code" required>
                      <input type="hidden" name="shoutout" value="<?php echo $order_id; ?>">
                      <button class="sas-mini-form-button">Submit</button>
                    </form>

                  <?php else : ?>

                    <?php foreach ( $shoutout_links as $channel => $channel_url ) : ?>

                      <?php if ( $channel_url === '' ) : ?>

                        <div>
                          Waiting for <?php echo ucfirst($channel); ?> link
                        </div>

                      <?php endif; ?>

                    <?php endforeach; ?>

                  <?php endif; ?>

                  <a href="<?php echo get_site_url() . '/reject-influencer/?order=' . $order_id; ?>" class="brand-reject-influencer">Not feeling this Influencer?</a>

                <?php else : ?><!-- $status !== '' -->

                  <?php foreach ( $shoutout_channels as $channel ) : ?>

                    <?php if ( $shoutout_links[$channel] === '' ) : ?>

                      <div>Waiting for <?php echo ucfirst($channel); ?> link</div>

                      <a href="<?php echo get_site_url() . '/reject-influencer/?order=' . $order_id; ?>" class="brand-reject-influencer">Not feeling this Influencer?</a>

                    <?php elseif( $shoutout_scores[$channel]['score'] == '' ) : ?>

                      <div>
                        <a target="_blank" href="<?php echo esc_url($shoutout_links[$channel]); ?>">View your ShoutOut on <?php echo ucfirst($channel) ?></a>
                        <form class="rating-form" method="post">
                          <div class="result-message"></div>
                          <ul class="hearts">
                              <li class="heart" data-value="1">
                                <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                              </li>
                              <li class="heart" data-value="2">
                                <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                              </li>
                              <li class="heart" data-value="3">
                                <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                              </li>
                              <li class="heart" data-value="4">
                                <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                              </li>
                              <li class="heart" data-value="5">
                                <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                              </li>
                            </ul>
                          <input type="hidden" class="rating" name="rating" value="<?php echo $shoutout_scores[$channel]['score']; ?>">
                          <input type="hidden" name="channel" value="<?php echo $channel; ?>">
                          <input type="hidden" name="shoutout" value="<?php echo $order_id; ?>">
                          <button class="sas-mini-form-button" <?php echo esc_html( $score_disabled ); ?>>Score</button>
                          <div class="brand-rating-notes">
                            <textarea rows="3" placeholder="Write a message to <?php echo esc_attr( $first_name . ' ' . $last_name ); ?>"></textarea>
                          </div>
                          <span class="brand-shoutout-error">Error with this ShoutOut?</span>
                        </form>

                        <div class="shoutout-error-form-container">
                          <form class="shoutout-error-form" method="post">

                            <div class="result-message"></div>

                            <input type="hidden" name="channel" value="<?php echo $channel; ?>">

                            <input type="hidden" name="order" value="<?php echo $order_id; ?>">

                            <textarea rows="3" placeholder="Please let us know what the issue with this ShoutOut is"></textarea>

                            <br>

                            <button class="sas-mini-form-button">Send</button>
                          </form>
                        </div>
                      </div>

                    <?php else : ?> <!-- if( $shoutout_scores[$channel] !== '' && $shoutout_links[$channel]['score'] !== '' ) -->

                      <div class="shoutout-final-score-container">

                        <a target="_blank" href="<?php echo esc_attr($shoutout_links[$channel]); ?>"><?php echo ucfirst($channel); ?></a>

                        <div class="hearts-rating-container">

                          <?php  for ( $x = 0; $x < $shoutout_scores[$channel]['score']; $x++ ) : ?>

                            <img class="heart-icon" width="20" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">

                          <?php endfor; ?>

                          <?php  for ( $x = 5; $x > $shoutout_scores[$channel]['score']; $x-- ) : ?>

                            <img class="heart-icon" width="20" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">

                          <?php endfor; ?>

                        </div>



                        <?php if( $shoutout_scores[$channel]['notes'] !== '' ) : ?>

                          <br><br>

                          <b>Notes:</b>

                          <div class="brand-shoutout-notes"><?php echo esc_html($shoutout_scores[$channel]['notes']); ?></div>

                        <?php endif ?>

                        <br>

                        <button class="brand-re-score-button sas-mini-form-button">Re-score</button>

                      </div>

                      <div class="shoutout-re-score-container">
                        <a target="_blank" href="<?php echo esc_url($shoutout_links[$channel]); ?>">View your ShoutOut on <?php echo ucfirst($channel) ?></a>
                        <form class="rating-form" method="post">
                          <div class="result-message"></div>
                          <ul class="hearts">
                              <li class="heart" data-value="1">
                                <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                              </li>
                              <li class="heart" data-value="2">
                                <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                              </li>
                              <li class="heart" data-value="3">
                                <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                              </li>
                              <li class="heart" data-value="4">
                                <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                              </li>
                              <li class="heart" data-value="5">
                                <img class="full" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">
                                <img class="empty" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">
                              </li>
                            </ul>
                          <input type="hidden" class="rating" name="rating" value="<?php echo $shoutout_scores[$channel]['score']; ?>">
                          <input type="hidden" name="channel" value="<?php echo $channel; ?>">
                          <input type="hidden" name="shoutout" value="<?php echo $order_id; ?>">
                          <button class="sas-mini-form-button" <?php echo esc_html( $score_disabled ); ?>>Score</button>
                          <div class="brand-rating-notes">
                            <textarea rows="3" placeholder="Write a message to <?php echo esc_attr( $first_name . ' ' . $last_name ); ?>"></textarea>
                          </div>
                          <span class="brand-shoutout-error">Error with this ShoutOut?</span>
                        </form>

                        <div class="shoutout-error-form-container">
                          <form class="shoutout-error-form" method="post">

                            <div class="result-message"></div>

                            <input type="hidden" name="channel" value="<?php echo $channel; ?>">

                            <input type="hidden" name="order" value="<?php echo $order_id; ?>">

                            <textarea rows="3" placeholder="Please let us know what the issue with this ShoutOut is"></textarea>

                            <br>

                            <button class="sas-mini-form-button">Send</button>
                          </form>
                        </div>
                      </div>

                    <?php endif; ?>

                  <?php endforeach; ?><!-- foreach ( $shoutout_links as $channel => $channel_url ) -->

                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endif; // endif order !cancelled ?>
        <?php endforeach; ?>
      </div>

      <?php else : ?>

        <div class="campaign-no-orders"><span>No ShoutOuts so far.</span></div>

      <?php endif; // endif $orders ?>

    <?php

  } else {
    ?>
    <div class="campaign-no-campaigns">
      <div>
        <span>You haven't set up a ShoutOut Campaign for <?php echo get_the_title($brand_id); ?> yet.</span>
        <br>
        <a class="sas-round-button-primary" href="<?php echo get_site_url() . '/design-campaign/?b=' . $brand_id; ?>">Design a new ShoutOut Campaign</a>
      </div>
    </div>
    <?php
  } // endif $campaign_id

  $response['content'] = ob_get_clean();

  $response = json_encode($response);

  exit($response);
}