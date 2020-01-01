<?php

$redirect_uri = get_site_url() . '/welcome';

//------------------------------//
//--- Instagram Login/signup ---//
//------------------------------//
class InstagramApi
{
    public function GetAccessToken($client_id, $redirect_uri, $client_secret, $code) {
        $url = 'https://api.instagram.com/oauth/access_token';

        $curlPost = 'client_id='. $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code='. $code . '&grant_type=authorization_code';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($http_code != '200')
            throw new Exception('Error : Failed to receieve access token');

        return $data['access_token'];
    }

    public function GetUserProfileInfo($access_token) {
        $url = 'https://api.instagram.com/v1/users/self/?access_token=' . $access_token;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($data['meta']['code'] != 200 || $http_code != 200)
            throw new Exception('Error : Failed to get user information');

        return $data['data'];
    }
}

$insta_client_id='569c9a5389f041c7adef9c41ef499ed4';
$insta_client_secret='4983db61872c40bd8ab2191bed4afefd';

if( isset( $_GET['code'] ) && strlen( $_GET['code'] ) < 200) {

    try {
        $instagram_ob = new InstagramApi();

        // Get the access token
        $access_token = $instagram_ob->GetAccessToken( $insta_client_id, $redirect_uri, $insta_client_secret, $_GET['code'] );

        // Get user information
        $user_info = $instagram_ob->GetUserProfileInfo($access_token);

        function fetchData($url){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }

        $result = fetchData("https://api.instagram.com/v1/users/".$user_info['id']."/media/recent/?access_token=".$access_token."&count=20");
        $result = json_decode($result);

        $total_insta_likes_count = 0;
        $total_insta_commt_count = 0;
        foreach ($result->data as $post) {
            (float)$total_insta_likes_count+=$post->likes->count;
            (float)$total_insta_commt_count+=$post->comments->count;
        }

        $totalLikesandComments=($total_insta_likes_count+$total_insta_commt_count)/20;
        $insta_followed_by = $user_info['counts']['followed_by'];
        $engagementrate = round((($totalLikesandComments/$insta_followed_by)*100),2);
        $insta_username = $user_info['username'];
        $instagram_full_name = $user_info['full_name'];
        $instagram_bio = $user_info['bio'];
        $instagram_profile_picture = $user_info['profile_picture'];
        $instagram_following = $user_info['counts']['follows'];
        $instagram_url = 'https://www.instagram.com/' . $insta_username;

        $ig_id = $user_info['id'];

        // Authentique score
        $authentique_token = '63f5216a98f876e39ea244f1291173dcf76a5100f664bc8a48d4cd0e168d62f7983f5f543d378e47';
        $url = 'https://agency-api.authentique.app/influencers';
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Key ' . $authentique_token,
        );
        $params = array(
            'usernames' => array($insta_username),
        );
        $json_string = json_encode($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_string );

        $result = json_decode(curl_exec($ch), true);

        curl_close($ch);

        $authentique_connected = false;

        if( $result['results'][0]['success'] == 1 ) {
            $authentique_connected = true;
        }

        // Find users
        $user = get_users(array(
            'meta_key' => 'inf_instagram_id',
            'meta_value' => $ig_id,
            'number' => -1,
            'count_total' => false
        ));


        if( !empty($user) ) { // If instagram id has been found
            if ( is_user_logged_in() && get_current_user_id() !== $user[0]->ID ) {
                $uid = get_current_user_id();
                update_user_meta($user[0]->ID, 'inf_instagram_id', '');
            } else {
                $uid = $user[0]->ID;
                wp_set_current_user( $uid );
                wp_set_auth_cookie( $uid );
            }

            update_user_meta( $uid, 'inf_instagram_id', $ig_id );
            update_user_meta( $uid, 'social_prism_user_instagram', $insta_username );
            update_user_meta( $uid, 'social_prism_user_instagram_reach', $insta_followed_by );
            update_user_meta( $uid, 'instagram-full-name', $instagram_full_name );
            update_user_meta( $uid, 'instagram-bio', $instagram_bio );
            update_user_meta( $uid, 'inf_instagram_profile_picture', $instagram_profile_picture );
            update_user_meta( $uid, 'instagram-following', $instagram_following );
            update_user_meta( $uid, 'instagram-engagement-rate', $engagementrate );
            update_user_meta( $uid, 'inf_instagram_total_likes', $total_insta_likes_count );
            update_user_meta( $uid, 'inf_instagram_total_comments', $total_insta_commt_count );
            update_user_meta( $uid, 'inf_authentique_connected', $authentique_connected );

            $links = get_posts(array(
                'post_type' => 'affiliate_link',
                'meta_key' => 'owner_id',
                'meta_value' => $uid,
            ));

            if(!count($links)) {

                $link_id = wp_insert_post(array(
                    'post_type' => 'affiliate_link',
                    'post_status' => 'publish',
                ));
                update_post_meta($link_id, 'owner_id', $uid);
                update_post_meta($link_id, 'param_string', sanitize_title($insta_username));
            }

            wp_redirect( get_site_url() . '/welcome-interests/' );

        } else { //If instagram id not found
            if ( is_user_logged_in() ) { // If user is logged in
                // Add instagram id and instagram info to account
                $uid = get_current_user_id();
                update_user_meta( $uid, 'inf_instagram_id', $ig_id );
                update_user_meta( $uid, 'social_prism_user_instagram', $insta_username );
                update_user_meta( $uid, 'social_prism_user_instagram_reach', $insta_followed_by );
                update_user_meta( $uid, 'instagram-full-name', $instagram_full_name );
                update_user_meta( $uid, 'instagram-bio', $instagram_bio );
                update_user_meta( $uid, 'inf_instagram_profile_picture', $instagram_profile_picture );
                update_user_meta( $uid, 'instagram-following', $instagram_following );
                update_user_meta( $uid, 'instagram-engagement-rate', $engagementrate );
                update_user_meta( $uid, 'inf_instagram_total_likes', $total_insta_likes_count );
                update_user_meta( $uid, 'inf_instagram_total_comments', $total_insta_commt_count );
                update_user_meta( $uid, 'inf_authentique_connected', $authentique_connected );

                $links = get_posts(array(
                    'post_type' => 'affiliate_link',
                    'meta_key' => 'owner_id',
                    'meta_value' => $uid,
                ));

                if(!count($links)) {

                    $link_id = wp_insert_post(array(
                        'post_type' => 'affiliate_link',
                        'post_status' => 'publish',
                    ));
                    update_post_meta($link_id, 'owner_id', $uid);
                    update_post_meta($link_id, 'param_string', sanitize_title($insta_username));
                }

                wp_redirect( get_site_url() . '/welcome-interests/');
                exit;
                
            }
        }
    }
    catch(Exception $e) {
        echo $e->getMessage();
        exit;
    }
} else if ( isset( $_GET['error_reason'] ) ) { // something went wrong
    echo '<br><br><br><br><p style="color: red; text-align: center;">Something went wrong, please try again.</p>';
}

?>
