<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author     WooThemes
 * @package    WooCommerce/Templates
 * @version    1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$pid = get_the_ID();
the_title( '<h1 class="product_title entry-title">', '</h1>' );
echo'<div class="brnd_nm">';

// if using new brand info custom fields
if (get_post_meta( $pid, 'brand', true )) {
    $brand_id = get_post_meta($pid, 'brand', true);
	$brand_name = get_the_title($brand_id);
	$brand_website = get_post_meta( $brand_id, 'brand_website', true );
	
	echo '<a href="' . $brand_website . '" target="_blank">' . $brand_name . '</a>';
}
	
echo'</div>';