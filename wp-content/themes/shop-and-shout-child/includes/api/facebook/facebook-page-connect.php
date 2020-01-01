<?php

require_once  __DIR__ . '/config.php';

// If the fbpid parameter is sent, update the user mets
$fb_page_id = !empty($_GET['fbpid'])?$_GET['fbpid']:'';
if (isset($_GET['fbpid'])) {
  update_user_meta( $uid, 'facebook_user_page', $_GET['fbpid'] );
}

$uid = get_current_user_id();
$facebook_user_page = get_user_meta( $uid, 'facebook_user_page', true );

if (!empty($fbPages) && !empty(json_decode($fbPages->asJson()))) {

  if ( !empty($facebook_user_page) ) {
    $fb_query_page = $facebook_user_page;
  } else {
    $fb_query_page = $fbPages[0]['id'];
  }

  try {

    $fields = array(
      'about',
      'birthday',
      'category',
      'category_list',
      'current_location',
      'description',
      'emails',
      'fan_count',
      'general_info',
      'hours',
      'id',
      'location',
      'link',
      'members',
      'name',
      'new_like_count',
      'overall_star_rating',
      'payment_options',
      'personal_info',
      'personal_interests',
      'price_range',
      'phone',
      'products',
      'rating_count',
      'username',
      'website',
    );

    $graphResponsePages = $fb->get(
      '/' . $fb_query_page . '?fields=' . implode(',',$fields)
    );
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
  }

  $page = $graphResponsePages->getDecodedBody();

  $fbPageBirthday  = !empty($page['birthday'])?$page['birthday']:'';
  $fbPageAbout  = !empty($page['about'])?$page['about']:'';
  $fbPageCategory  = !empty($page['category'])?$page['category']:'';
  $fbPageCategoryList  = !empty($page['category_list'])?$page['category_list']:'';
  $fbPageDescription  = !empty($page['description'])?$page['description']:'';
  $fbPageFanCount  = !empty($page['fan_count'])?$page['fan_count']:'';
  $fbPageEmails  = !empty($page['emails'])?$page['emails']:'';
  $fbPageEngagement  = !empty($page['engagement'])?$page['engagement']:'';
  $fbPageFounded  = !empty($page['founded'])?$page['founded']:'';
  $fbPageGeneralInfo  = !empty($page['general_info'])?$page['general_info']:'';
  $fbPageHours  = !empty($page['hours'])?$page['hours']:'';
  $fbPageID  = !empty($page['id'])?$page['id']:'';
  $fbPageLink = !empty($page['link'])?$page['link']:'';
  $fbPageLocation = !empty($page['location'])?$page['location']:'';
  $fbPageName = !empty($page['name'])?$page['name']:'';
  $fbPagePersonalInterest = !empty($page['personal_interests'])?$page['personal_interests']:'';
  $fbPagePhone = !empty($page['phone'])?$page['phone']:'';
  $fbPagePreferredAudience = !empty($page['preferred_audience'])?$page['preferred_audience']:'';
  $fbPageStarRating = !empty($page['overall_star_rating'])?$page['overall_star_rating']:'';
  $fbPagePriceRange = !empty($page['price_range'])?$page['price_range']:'';
  $fbPageProducts = !empty($page['products'])?$page['products']:'';
  $fbPagePaymentOptions = !empty($page['payment_options'])?$page['payment_options']:'';
  $fbPageRatingCount = !empty($page['rating_count'])?$page['rating_count']:'';
  $fbPageSingleLineAddress = !empty($page['single_line_address'])?$page['single_line_address']:'';
  $fbPageTalkingCount = !empty($page['talking_about_count'])?$page['talking_about_count']:'';
  $fbPageVerificationStatus  = !empty($page['verification_status'])?$page['verification_status']:'';
  $fbPageUsername  = !empty($page['username'])?$page['username']:'';
  $fbPageWebsite  = !empty($page['link'])?$page['link']:'';
  $fbPageWhatsAppNumber  = !empty($page['whatsapp_number'])?$page['whatsapp_number']:'';

  $post_id = get_posts(array(
    'numberposts' => -1,
    'post_type' => 'social_profile',
    'meta_query' => array(array(
      'meta_key' => 'facebook_page_id',
      'meta_value' => $facebook_user_page,
    )),
  ));

  // If there's already a post with the page id, update
  if (!empty($post_id)) {
    $pid = $post_id[0]->ID;

    update_post_meta($pid,'facebook_page_birthday',$fbPageBirthday);
    update_post_meta($pid,'facebook_page_about',$fbPageAbout);
    update_post_meta($pid,'facebook_page_category',$fbPageCategory);
    update_post_meta($pid,'facebook_page_category_list',$fbPageCategoryList);
    update_post_meta($pid,'facebook_page_description',$fbPageDescription);
    update_post_meta($pid,'facebook_page_emails',$fbPageEmails);
    update_post_meta($pid,'facebook_page_fan_count',$fbPageFanCount);
    update_post_meta($pid,'facebook_page_founded',$fbPageFounded);
    update_post_meta($pid,'facebook_page_general_info',$fbPageGeneralInfo);
    update_post_meta($pid,'facebook_page_hours',$fbPageHours);
    update_post_meta($pid,'facebook_page_id',$fbPageID);
    update_post_meta($pid,'facebook_page_link',$fbPageLink);
    update_post_meta($pid,'facebook_page_location',$fbPageLocation);
    update_post_meta($pid,'facebook_page_name',$fbPageName);
    update_post_meta($pid,'facebook_page_price_range',$fbPagePriceRange);
    update_post_meta($pid,'facebook_page_personal_interests',$fbPagePersonalInterest);
    update_post_meta($pid,'facebook_page_phone',$fbPagePhone);
    update_post_meta($pid,'facebook_page_products',$fbPageProducts);
    update_post_meta($pid,'facebook_page_rating_count',$fbPageRatingCount);
    update_post_meta($pid,'facebook_page_star_rating',$fbPageStarRating);
    update_post_meta($pid,'facebook_page_single_line_address',$fbPageSingleLineAddress);
    update_post_meta($pid,'facebook_page_username',$fbPageUsername);
    update_post_meta($pid,'facebook_page_verification_status',$fbPageVerificationStatus);
    update_post_meta($pid,'facebook_page_website',$fbPageWebsite);
    update_post_meta($pid,'facebook_page_whatsapp_number',$fbPageWhatsAppNumber);

  }
}

?>
