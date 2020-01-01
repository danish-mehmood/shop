<?php if ( 'on' == et_get_option( 'divi_back_to_top', 'false' ) ) : ?>
	<span class="et_pb_scroll_top et-pb-icon"></span>
<?php endif;
if ( ! is_page_template( 'page-template-blank.php' ) ) : ?>
			<footer id="main-footer">
				<table id="footer-top">
					<tr>
						<td>
							<ul class="footer-links">
								<li><a href="/terms">Terms and<br>Conditions</a></li>
								<li><a href="/privacy">Privacy<br>Policy</a></li>
								<li><a href="/contact">Contact</a></li>
							</ul>
						</td>
						<td class="social_footer">
							<span>
								<p>Follow Us</p>
								<ul>
									<li>
										<a href="https://www.facebook.com/ShopandShout/" target="_blank">
											<img src="<?php echo content_url() . '/uploads/2018/11/Facebook-icon-white.svg'; ?>" />
										</a>
									</li>
									<li>
										<a href="https://twitter.com/shopandshout" target="_blank">
											<img src="<?php echo content_url() . '/uploads/2018/11/Twitter-icon-white.svg'; ?>" />
										</a>
									</li>
										<li>
										<a href="
								https://www.instagram.com/ishopandshout/" target="_blank">
											<img src="<?php echo content_url() . '/uploads/2018/11/Insta-icon-white.svg'; ?>" />
										</a>
									</li>
								</ul>
							</span>
						</td>
					</tr>
				</table>
				<div id="footer-bottom">
					<div class="container clearfix">
						<div class="copyright">
							<div id="location-address">725 Granville St Suite #420, Vancouver, BC, <br>Canada, V7Y 1C6</div>
							© <?php echo date( 'Y' );?> Shop And Shout ltd.
						</div>
					</div>	<!-- .container -->
				</div>
			</footer> <!-- #main-footer -->
		</div> <!-- #et-main-area -->
<?php endif; // ! is_page_template( 'page-template-blank.php' ) ?>
	</div> <!-- #page-container -->
	<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri() ;?>/vendor/slick/slick.css"/>
	<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ;?>/vendor/slick/slick.js"></script>
	<?php wp_footer(); ?>
</body>
</html>