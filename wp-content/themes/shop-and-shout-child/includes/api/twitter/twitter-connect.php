<?php
$twitter_redirect_uri = get_permalink( get_option('woocommerce_myaccount_page_id') ).'edit-account/'; // URL of page/file that processes a request

//Twitter connect
include_once("twitteroauth.php");
if(isset($_GET['request']))
{
		//Fresh authentication
		$connection = new TwitterOAuth('zNlSfpzxs7kA7q5QkvIdiNOt2', 'CfVqmBKlOzjjPmqGYsnYHHF3leUNE3KIg3Zd8C7BAexyznoOyJ', $access_token, $access_token_secret);
		$request_token = $connection->getRequestToken($twitter_redirect_uri);
		print_r($request_token);

		//Received token info from twitter
		$_SESSION['token'] 			= $request_token['oauth_token'];
		$_SESSION['token_secret'] 	= $request_token['oauth_token_secret'];

		//Any value other than 200 is failure, so continue only if http code is 200
		if($connection->http_code == '200')
		{
		//redirect user to twitter
		$twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']);
		wp_redirect($twitter_url);
		exit;
		}else{
		die("error connecting to twitter! try again later!");
		}
}
if(isset($_REQUEST['oauth_token']) && $_SESSION['token'] == $_REQUEST['oauth_token']){

	$connection = new TwitterOAuth('zNlSfpzxs7kA7q5QkvIdiNOt2', 'CfVqmBKlOzjjPmqGYsnYHHF3leUNE3KIg3Zd8C7BAexyznoOyJ', $_SESSION['token'] , $_SESSION['token_secret']);
	$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
	if($connection->http_code == '200')
	{
	$params_twitter = array('include_email' => 'true');
	$user_data = $connection->get('account/verify_credentials', $params_twitter);
	//print_r($user_data);die;
	$twitter_screen_name=$user_data['screen_name'];
	$twitter_followers=$user_data['followers_count'];
	$twitter_email=$user_data['email'];
	$twitter_name=$user_data['name'];
	$twitter_following=$user_data['friends_count'];
	$twitter_bio=$user_data['description'];
	$twitter_count=$user_data['statuses_count'];
	$twitter_url='https://twitter.com/'.$user_data['screen_name'];
	if( isset( $twitter_email ) ){
		//$user = get_user_by( 'email', $twitter_email );
					$user_id = $user->ID;
					if($user_id!=''){
						update_user_meta( $user_id, 'social_prism_user_twitter', $twitter_screen_name );
						update_user_meta( $user_id, 'social_prism_user_twitter_reach', $twitter_followers );
						update_user_meta( $user_id, 'twitter-full-name', $twitter_name );
						update_user_meta( $user_id, 'twitter-following', $twitter_following );
						update_user_meta( $user_id, 'twitter-bio', $twitter_bio );
						update_user_meta( $user_id, 'twitter-count', $twitter_count );
						update_user_meta( $user_id, 'twitter-url', $twitter_url );
						wp_redirect($twitter_redirect_uri);
						exit;
					}
	 } else { ?>
						<script type='text/javascript'>
						jQuery(document).ready(function(){
								alert('Your social accounts must use the same email address as you used to sign up with ShopandShout.');
								window.location ='<?php echo $twitter_redirect_uri; ?>';
							});
						</script>
					<?php }
	 } else {
	    die("error, try again later!");
	 }
 }
