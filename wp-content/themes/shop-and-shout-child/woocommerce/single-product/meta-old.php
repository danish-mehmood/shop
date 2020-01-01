<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
$pid = $product->get_id();

if ( get_post_meta ( $pid, 'facebook_reach', true ) !== '' ) {
	$facebook_reach = get_post_meta ( $pid, 'facebook_reach', true );
}

// if using new custom field for reach
if ( get_post_meta ( $pid, 'instagram_reach', true ) !== '' ) {
	$instagram_reach = get_post_meta ( $pid, 'instagram_reach', true );
}

// if using new custom field for reach
if ( get_post_meta ( $pid, 'twitter_reach', true ) !== '' ) {
	$twitter_reach = get_post_meta ( $pid, 'twitter_reach', true );
}

$hashtags_and_keywords = get_the_terms( $pid, 'pa_hashtags-and-keywords' );
$instagram_tags = get_the_terms( $pid, 'pa_instagram-tags' );
$timeline = get_the_terms( $pid, 'pa_timeline' );
$interests = get_the_terms($pid, 'product_cat');

?>
<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<span class="sku_wrapper"><strong><?php esc_html_e( 'SKU:', 'woocommerce' ); ?> </strong><span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woocommerce' ); ?></span></span>

	<?php endif; ?>

	<?php
		echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( '<strong>Tag:</strong>', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span>' );
	?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>
	<div class="prod_req single-product-requirements">
		<h2>Influencer Requirements:</h2>

		<table class="prod_rq_table_1">
			<tr>
				<th>
					Reach
					<img src="<?php echo get_stylesheet_directory_uri();?>/images/icons/shop/reach.svg">
				</th>
				<th>
					Engagement
					<img src="<?php echo get_stylesheet_directory_uri();?>/images/icons/shop/engagement.svg">
				</th>
			</tr>

			<?php if ( isset( $facebook_reach ) && $facebook_reach !== '' ) : ?>
			<tr>
				<td>
					<div class="slider-social-icon-container left-margin-0">
						<img class="slider-social-icon" src="/wp-content/uploads/2016/12/fb_icon_white-150x150.png" alt="" title="" width="" height=""><?php echo $facebook_reach; ?>
					</div>
				</td>

				<td>
				<?php
					if( get_field( "facebook_engagement_rate", $pid ) ){
						echo get_field( "facebook_engagement_rate", $pid ).' %';
					} else {
						echo 'n/a';
					}
				?>
				</td>
			</tr>
			<?php endif; ?>

			<?php if ( isset( $twitter_reach ) && $twitter_reach !== '' ) : ?>
			<tr>
				<td>
					<div class="slider-social-icon-container left-margin-0">
						<img class="slider-social-icon" src="/wp-content/uploads/2016/12/twitter_icon_white-150x150.png" alt="" title="" width="" height=""><?php echo $twitter_reach; ?>
					</div>
				</td>

				<td>
				<?php
					if( get_field( "twitter_engagement_rate", $pid ) ){
						echo get_field( "twitter_engagement_rate", $pid ).' %';
					} else {
						echo 'n/a';
					}
				?>
				</td>
			</tr>
			<?php endif; ?>

			<?php if ( isset( $instagram_reach ) && $instagram_reach !== '' ) : ?>
			<tr>
				<td>
					<div class="slider-social-icon-container">
						<img class="slider-social-icon" src="/wp-content/uploads/2016/12/ig_icon_white-150x150.png" alt="" title="" width="" height=""><?php echo $instagram_reach; ?>
					</div>
				</td>

				<td>
				<?php
					if( get_field( "instagram_engagement_rate", $pid ) ){
						echo get_field( "instagram_engagement_rate", $pid ).' %';
					} else {
						echo 'Any %';
					}
				?>
				</td>
			</tr>
			<?php endif; ?>
		</table>

		<table class="prod_rq_table_2">
			<tr class="usr_img">
				<th>
					Interests
					<img src="<?php echo get_stylesheet_directory_uri();?>/images/icons/shop/interests.svg">
				</th>
				<th>
					Location
					<img src="<?php echo get_stylesheet_directory_uri();?>/images/icons/shop/location.svg">
				</th>
			</tr>

			<tr>
				<td class="interests">
				<?php foreach($interests as $interest) : ?>
					<img data-toggle="tooltip" title="<?php echo $interest->name; ?>" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/interests/' . $interest->slug . '.svg'; ?>">
				<?php endforeach; ?>
				</td>

				<td>
				<?php
					$countries = get_post_meta( $pid, 'countries', true );
					if ( $countries ) {
						foreach ($countries as $country) {
							if( $country == 'CA' || $country == 'GB' || $country == 'US' ) {

								echo '<img width="25px" class="flg_ic" alt="'.$country.'" src="'.get_stylesheet_directory_uri().'/images/icons/flags/' . $country . '.png"> &nbsp;&nbsp;';

							}
						}
					} else {
						echo '<img data-toggle="tooltip" title="Available Globally" width="25px" class="flg_ic" alt="global" src="'.get_stylesheet_directory_uri().'/images/icons/flags/globe.svg">';
					}

				?>
				</td>
			</tr>
		</table>
	</div>
</div>

<div class="sas-product-content2">
	<?php if ( isset($hashtags_and_keywords[0]->name) ) : ?>
	<div>
		Hashtags and keywords to include: <b><?php echo $hashtags_and_keywords[0]->name; ?></b>
	</div>
	<?php endif; ?>

	<?php if ( isset($instagram_tags[0]->name) ) : ?>
		<div>
			Instagram tags: <b><?php echo $instagram_tags[0]->name; ?></b>
		</div>
	<?php endif; ?>

	<?php if ( isset($timeline[0]->name) ) : ?>
		<div>
			Timeline: <b><?php echo $timeline[0]->name; ?></b>
		</div>
	<?php endif; ?>
</div>
