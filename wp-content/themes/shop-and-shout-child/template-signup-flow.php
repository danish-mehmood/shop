<?php
/**
 * Template Name: Sign Up Flow
 * Show products by facebook/intagram/twitter reach of users
 */

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
</style>
<div id="main-content">
	<div class="fullscreen-top" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/shapes/fullscreen-top-band.svg'?>');">
	</div>
	<a class="fullscreen-top-logo" href="<?php echo get_site_url(); ?>"><img src="<?php echo get_stylesheet_directory_uri() . '/images/logos/shop-and-shout-white.png' ?>"></a>
	<?php the_content(); ?>
</div> <!-- #main-content -->

<?php
