<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
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
$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
?>
<ul class="social-share clearfix">
		<li class="facebook">
			<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink();?>" target="_blank" rel="noopener noreferrer">
				<i class="fab fa-facebook-f"></i>
				<div class="fusion-woo-social-share-text"><span>Share On Facebook</span></div>
			</a>
		</li>
		<li class="twitter">
			<a href="https://twitter.com/intent/tweet?url=<?php echo get_permalink();?>&text=<?php echo get_the_title();?>" target="_blank" rel="noopener noreferrer">
				<i class="fab fa-twitter"></i>
				<div class="fusion-woo-social-share-text"><span>Tweet This Product</span></div>
			</a>
		</li>
		<li class="pinterest"><a href="http://pinterest.com/pin/create/button/?url=<?php echo get_permalink();?>&amp;description=<?php echo get_the_title();?>&amp;media=<?php echo $featured_img_url;?>" target="_blank" rel="noopener noreferrer">
			<i class="fab fa-pinterest-p"></i>
				<div class="fusion-woo-social-share-text"><span>Pin This Product</span></div>
			</a>
		</li>
		<li class="email">
			<a href="mailto:?subject=<?php echo get_the_title();?>&amp;body=<?php echo get_permalink();?>" target="_blank" rel="noopener noreferrer">
				<i class="far fa-envelope"></i>
				<div class="fusion-woo-social-share-text"><span>Mail This Product</span></div>
			</a>
		</li>
	</ul>
<?php
if (  1 == 2 ) : ?> <!-- Temporarily disabling section -->

	<section class="related products">
		<div class="fusion-title title sep-double">
			<h2><?php esc_html_e( 'Related products', 'woocommerce' ); ?></h2>
			<div class="title-sep-container"><div class="title-sep sep-double "></div></div>
		</div>

		<?php woocommerce_product_loop_start(); ?>

			<?php foreach ( $related_products as $related_product ) : ?>

				<?php
				 	$post_object = get_post( $related_product->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object );

					wc_get_template_part( 'content', 'product' ); ?>

			<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>

	</section>

<?php endif;

wp_reset_postdata();
