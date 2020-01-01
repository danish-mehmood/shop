<?php
/**
 * Template Name: Products by social reach
 * Show products by facebook/intagram/twitter reach of users
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header("shop"); ?>
	
	<?php 
	
		
	if ( is_user_logged_in() ) {
		
		$user_id = get_current_user_id();	
		$user_instagram_reach = get_user_meta( $user_id, 'social_prism_user_instagram_reach', true );
		$user_birthdate = get_user_meta( $user_id, 'social_prism_user_birthday', true );
		$user_country = get_user_meta( $user_id, 'inf_country', true );
		$user_region = get_user_meta( $user_id, 'inf_region', true );

		$redirect_url = get_site_url();

		$query_array = array();

		$category_string = '';

		if( is_array($user_interests) && $user_interests[0] !== '' ) {

			foreach ( $user_interests as $interest ) {

				$category = get_terms('product_cat', array( 'name__like' =>htmlspecialchars($interest) ) );

				$category_string .= isset($category[0]->slug) ? $category[0]->slug . ',' : '';
			}

		} else {

			wp_redirect( get_site_url() . '/shop/?no_interests' );

			exit;
		}

		if ( $category_string == 'uncategorized' ) {

			$redirect_url .= '/shop/';

		} else {

			$redirect_url .= '/product-category/' . $category_string;
		}

		if( $user_instagram_reach !== '' ) {

			$query_array['instagram_reach'] = $user_instagram_reach;
		}

		if( $user_country !== '') {

			$query_array['location'] = $user_country;
		}

		wp_redirect( $redirect_url . '?' . http_build_query( $query_array ) );
		exit;


		// Old ShoutOuts for me code
		$user_info = get_userdata($user_id);
		$facebook_reach = get_user_meta($user_id, 'social_prism_user_facebook_reach', true);
		$instagram_reach = get_user_meta($user_id, 'social_prism_user_instagram_reach', true);
		$twitter_reach = get_user_meta($user_id, 'social_prism_user_twitter_reach', true); 
		$interest = get_user_meta($user_id, 'social_prism_user_interests', true);
		//echo $user_id;		
		do_action( 'woocommerce_before_main_content' );
		echo "<div class=\"vceabfdpd\" style='text-align:center;'>Hi, $user_info->first_name $user_info->last_name</div>";
		$facebook_reach 	= !empty( $facebook_reach ) ? $facebook_reach : 0;
		$twitter_reach 		= !empty( $twitter_reach ) ? $twitter_reach : 0;
		$instagram_reach 	= !empty( $instagram_reach ) ? $instagram_reach : 0;
		/* echo "<br>";
		echo "Insta: " . $instagram_reach;
		echo "<br>";
		echo "Twitter: " . $twitter_reach;
		echo "<br>";
		echo "Face: " . $facebook_reach;  */
	
	//Get reaches
	$fbReach		= range(0, $facebook_reach);
	$instaReach		= range(0, $instagram_reach);
	$twitterReach	= range(0, $twitter_reach);
	$interests 		= explode(",", $interest);
	$emtpy = "";
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	// Define Query Arguments
	$args = array( 
		'post_type'				=> 'product',
		'post_status' 			=> 'publish',
		'ignore_sticky_posts'	=> 1,
		/* 'orderby' 				=> $ordering_args['orderby'],
		'order' 				=> $ordering_args['order'], */
		'posts_per_page' 		=> 12,
		'paged' 				=> $paged,
		'tax_query' 			=> array(
			'relation'			=> "AND",
			array(
				'relation'		=> 'OR', /* Get Facebook Reach */
				array(
					'taxonomy' 		=> 'pa_facebook-reach',
					'terms' 		=> $fbReach,
					'field' 		=> 'slug',
					'operator'		=> 'IN',
				),
				array(
					'taxonomy' 		=> 'pa_facebook-reach',
					'terms' 		=> $emtpy,
					'field' 		=> 'slug',
					'operator'		=> 'NOT EXISTS',
				)		
			),
			array(
				'relation'		=> 'OR', /* Get Insta Reach */		
				array(
					'taxonomy' 		=> 'pa_instagram-reach',
					'terms' 		=> $instaReach,
					'field' 		=> 'slug',
					'operator'		=> 'IN',
				),	
				array(
					'taxonomy' 		=> 'pa_instagram-reach',
					'terms' 		=> $emtpy,
					'field' 		=> 'slug',
					'operator'		=> 'NOT EXISTS',
				)	
			),
			array( 
				'relation'		=> 'OR', /* Get Twitter Reach */
				array(
					'taxonomy' 		=> 'pa_twitter-reach',
					'terms' 		=> $twitterReach,
					'field' 		=> 'slug',
					'operator'		=> 'IN',
				),
				array(
					'taxonomy' 		=> 'pa_twitter-reach',
					'terms' 		=> $emtpy,
					'field' 		=> 'slug',
					'operator'		=> 'NOT EXISTS',
				),
				
			),	
			array(
				'relation'	=> 'OR',
				array(
					'taxonomy' 		=> 'product_cat', /* product category */
					'terms' 		=> $interests,
					'field' 		=> 'name',
					'operator' 		=> 'IN',
				),
				array(
					'taxonomy' 		=> 'product_cat', /* product category if not exists */
					'terms' 		=> $interests,
					'field' 		=> 'name',
					'operator' 		=> 'NOT EXISTS',
				) 			
			
			)
		)	
	);
	
	$wp_query = new WP_Query( $args );
	$woocommerce_loop['columns'] = 3;
	if ( $wp_query->have_posts() ) : ?>
		<?php woocommerce_product_loop_start(); ?>

			<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
				
				<?php wc_get_template_part( 'content', 'product' ); ?>

			<?php endwhile; // end of the loop. ?>
			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>
		<?php wp_reset_postdata();	?>
	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
			do_action( 'woocommerce_after_main_content' );
			
			
	
	
	}

	else{

		do_action( 'woocommerce_before_main_content' );
		echo "<h2 style='text-align:center;'>You must be logged-in to access this page.</h2>";
		echo '<div class="backToShop" style="text-align:center;"> <a href="/my-account" id="button_facebook" class="btn-blue" name="button"><span class="text-container">Login</span></a></div>';
		echo "</div>";

		if( is_active_sidebar("sidebar-1") ){
			echo "<div id='sidebar'>";
				dynamic_sidebar("sidebar-1"); 
			echo "</div>";	
		}	
		echo "</div>";
		echo "</div>";
	}
	
	
	
	
	//Sidebar
	
 get_footer("shop"); ?>
