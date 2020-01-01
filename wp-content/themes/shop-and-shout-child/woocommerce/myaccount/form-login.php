<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<style type="text/css">
	footer {
		display: none;
	}
	#page-container {
		padding-top: 0 !important;
		margin-top: 0 !important;
	}
	#main-content .container {
		padding: 0;
		margin: 0;
		width: 100% !important;
		max-width: 100% !important;
	}
	#left-area {
		padding-bottom: 0;
	}
</style>

<div class="inf-login-wrapper">
	<div>
		<div class="inf-login-form-container">
			<?php do_action( 'woocommerce_before_customer_login_form' );?>
			<?php wc_print_notices(); ?>
			<?php echo do_shortcode('[ss_inf_login_form]'); ?>
		</div>
	</div>
	<div style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/photos/' . ( isset($_GET['brand']) ? 'brand-login-side.jpg' : 'influencer-login-side.jpg' ); ?>');"></div>
</div>
<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
