<?php

function facebook_page_data_shortcode() {
  $uid = get_current_user_id();
  $facebook_user_page = get_user_meta( $uid, 'facebook_user_page', true );
  $fb_page_id = !empty($_GET['fbpid'])?$_GET['fbpid']:'';

  $post_id = get_posts(array(
    'numberposts' => -1,
    'post_type' => 'social_profile',
    'meta_query' => array(array(
      'meta_key' => 'facebook_page_id',
      'meta_value' => $facebook_user_page,
    )),
  ));

  if (!empty($post_id)) {
    $pid = $post_id[0]->ID;

    $facebook_page_birthday  = get_post_meta($pid,'facebook_page_birthday',true);
    $facebook_page_about  = get_post_meta($pid,'facebook_page_about',true);
    $facebook_page_category  = get_post_meta($pid,'facebook_page_category',true);
    $facebook_page_description  = get_post_meta($pid,'facebook_page_description',true);
    $facebook_page_emails = get_post_meta($pid,'facebook_page_emails',true);
    $facebook_page_fan_count  = get_post_meta($pid,'facebook_page_fan_count',true);
    $facebook_page_founded  = get_post_meta($pid,'facebook_page_founded',true);
    $facebook_page_general_info  = get_post_meta($pid,'facebook_page_general_info',true);
    $facebook_page_hours  = get_post_meta($pid,'facebook_page_hours',true);
    $facebook_page_id = get_post_meta($pid,'facebook_page_id',true);
    $facebook_page_location = get_post_meta($pid,'facebook_page_location',true);
    $facebook_page_link = get_post_meta($pid,'facebook_page_link',true);
    $facebook_page_members = get_post_meta($pid,'facebook_page_members',true);
    $facebook_page_name  = get_post_meta($pid,'facebook_page_name',true);
    $facebook_page_personal_interests = get_post_meta($pid,'facebook_page_personal_interests',true);
    $facebook_page_phone = get_post_meta($pid,'facebook_page_phone',true);
    $facebook_page_price_range = get_post_meta($pid,'facebook_page_price_range',true);
    $facebook_page_products = get_post_meta($pid,'facebook_page_products',true);
    $facebook_page_single_line_address = get_post_meta($pid,'facebook_page_single_line_address',true);
    $facebook_page_rating_count = get_post_meta($pid,'facebook_page_rating_count',true);
    $facebook_page_star_rating = get_post_meta($pid,'facebook_page_star_rating',true);
    $facebook_page_username  = get_post_meta($pid,'facebook_page_username',true);
    $facebook_page_verification_status  = get_post_meta($pid,'facebook_page_verification_status',true);
    $facebook_page_website  = get_post_meta($pid,'facebook_page_website',true);
    $facebook_page_whatsapp_number = get_post_meta($pid,'facebook_page_whatsapp_number',true);

    $out = '<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#facebook-data" aria-expanded="false" aria-controls="collapseExample">
        View Page Data
      </button>';

    $out .= '<div class="collapse" id="facebook-data">';

    $out .= '<h4>' . esc_attr($facebook_page_name) . '</h4>';
    $out .= '<p>' . esc_attr($facebook_page_general_info) . '</p>';

    $out .= '<div class="col-sm-12"><div class="form-group">';

    $out .= '<label>Category</label>';
    // $out .= '<input type="text" value="' . esc_attr( $facebook_page_category ) . '" disabled>';

    $out .= '<label>Website</label>';
    $out .= '<input type="text" value="' . esc_attr( $facebook_page_website ) . '" disabled>';

    // $out .= '<label>Emails</label>';
    // $out .= '<input type="text" value="' . esc_attr( $facebook_page_emails ) . '" disabled>';

    $out .= '<label>Address</label>';
    $out .= '<input type="text" value="' . esc_attr( $facebook_page_single_line_address ) . '" disabled>';

    $out .= '<label>Phone Number</label>';
    $out .= '<input type="text" value="' . esc_attr( $facebook_page_phone ) . '" disabled>';

    $out .= '<label>WhatsApp Number</label>';
    $out .= '<input type="text" value="' . esc_attr( $facebook_page_whatsapp_number ) . '" disabled>';

    $out .= '<label>Fan Count</label>';
    $out .= '<input type="text" value="' . esc_attr( $facebook_page_fan_count ) . '" disabled>';

    $out .= '<label>Star Rating</label>';
    $out .= '<input type="text" value="' . esc_attr( $facebook_page_star_rating ) . ' /' . esc_attr( $facebook_page_rating_count ) . '" disabled>';

    $out .= '<label>Varification Status</label>';
    $out .= '<input type="text" value="' . esc_attr( $facebook_page_verification_status ) . '" disabled>';

    $out .= '<label>About</label>';
    $out .= '<textarea value="" disabled>' . esc_attr( $facebook_page_about ) . '</textarea>';

    $out .= '<label>Description</label>';
    $out .= '<textarea disabled >' . esc_attr( $facebook_page_description ) . '</textarea>';

    $out .= '<label>Founded</label>';
    $out .= '<input type="text" value="' . esc_attr( $facebook_page_founded ) . '" disabled>';

    $out .= '<label>Birthday</label>';
    $out .= '<textarea value="" disabled>' . esc_attr( $facebook_page_birthday ) . '</textarea>';

    $out .= '<label>Personal Interests</label>';
    $out .= '<textarea value="" disabled>' . esc_attr( $facebook_page_personal_interests ) . '</textarea>';

    $out .= '</div></div>';

    $out .= '<div class="col-sm-12"><div class="form-group">';
    $out .= '<label>Products</label>';
    $out .= '<textarea value="" disabled>' . esc_attr( $facebook_page_products ) . '</textarea>';

    $out .= '<label>Price Range</label>';
    $out .= '<input type="text" value="' . esc_attr( $facebook_page_price_range ) . '" disabled>';

    $out .= '</div></div>';

    $out .= '<div class="col-sm-12"><div class="form-group">';

    $out .= '<label>Members</label>';

    $out .= '</div></div>';
    $out .= '</div>'; // collapse

  } else {

    $args = array(
      'meta_input' => array(
        'facebook_page_id' => $fb_page_id,
      ),
      'post_status' => 'publish',
      'post_title' => 'Facebook Page',
      'post_type' => 'social_profile',
      'post_content' => 'Page id: ' . $facebook_user_page
    );
    wp_insert_post($args);

    wp_redirect('/my-account/edit-account/');
    exit;

  }
  echo $out;
}

add_shortcode('facebook_page_data', 'facebook_page_data_shortcode');

?>
