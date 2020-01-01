<?php
/**
 * Additional Information tab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/additional-information.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
$facebook_reach = get_the_terms( $product->get_id(), 'pa_facebook-reach' );
$twitter_reach = get_the_terms( $product->get_id(), 'pa_twitter-reach' );
$instagram_reach = get_the_terms( $product->get_id(), 'pa_instagram-reach' );
$heading = esc_html( apply_filters( 'woocommerce_product_additional_information_heading', __( 'ShoutOut on', 'woocommerce' ) ) );

?>

<?php if ( $heading ) : ?>
	<h2 class="tabs-heading">
		<?php echo $heading; ?>
	</h2>
	<?php
		$fb_user_url='';
		$tw_user_url='';
		$ig_user_url='';
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
			$fb_user_url=get_user_meta( $user_id,'facebook-url',true );
			$tw_user_url=get_user_meta( $user_id,'twitter-url',true );
			$ig_user_url=get_user_meta( $user_id,'instagram-url',true );
		}
		?>
		<ul class="req_tab_social">
			<?php if ( isset($facebook_reach[0]->name) ) : ?>
			<li>
				<a href="<?php echo $fb_user_url;?>" target="_blank">
				<img src="<?php echo content_url() . '/uploads/2016/12/fb_icon_white-150x150.png' ?>" />
				</a>
			</li>
			<?php endif; ?>
			<?php if ( isset($twitter_reach[0]->name) ) : ?>
			<li>
				<a href="<?php echo $tw_user_url;?>" target="_blank">
				<img src="<?php echo content_url() . '/uploads/2016/12/twitter_icon_white-150x150.png' ?>" />
				</a>
			</li>
			<?php endif; ?>
			<?php if ( isset($instagram_reach[0]->name) ) : ?>
			<li>
				<a href="<?php echo $ig_user_url;?>" target="_blank">
				<img src="<?php echo content_url() . '/uploads/2016/12/ig_icon_white-150x150.png' ?>" />
				</a>
			</li>
			<?php endif; ?>
		</ul>
<?php endif; ?>

<?php do_action( 'woocommerce_product_additional_information', $product ); ?>
