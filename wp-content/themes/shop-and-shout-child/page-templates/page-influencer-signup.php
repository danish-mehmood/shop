<?php

get_header();

?>
<style type="text/css">
	html{
		margin-top: 0 !important;
	}
	header {
		display: none;
	}
	#page-container {
		padding: 0 !important;
	}
	.fullscreen-top-logo {
		padding: 20px;
	}
	.fullscreen-top-logo img {
		width: 195px;
	}
	@media only screen and (max-width: 767px) {
		header {
			display: block;
		}
		.fullscreen-top-logo {
			display: none;
		}
	}
</style>
<div id="main-content">
	<a class="fullscreen-top-logo" href="<?php echo get_site_url(); ?>"><img src="<?php echo get_stylesheet_directory_uri() . '/images/logos/shop-and-shout.png' ?>"></a>
	<div class="inf-registration-wrapper">
		<div>
		<?php echo do_shortcode('[ss_inf-registration-form]'); ?>
		</div>
		<div style="background-image: url('<?php echo get_stylesheet_directory_uri() ?>/images/photos/influencer-signup-side.jpg');">
		</div>
	</div>
</div> <!-- #main-content -->

<?php
