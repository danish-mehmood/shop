<?php get_header(); ?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div class="404-pg error-pg">
				<article id="post-0" <?php post_class( 'et_pb_post not_found' ); ?>>
					<div class="error-message">404</div>
					<h2>Oops, This Page Could Not Be Found!</h2>
					<?php echo get_search_form(); ?>
				</article> <!-- .et_pb_post -->
			</div> <!-- #left-area -->


		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php

get_footer();
