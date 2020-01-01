<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$customer_orders = wc_get_orders( array(
	'customer_id' => get_current_user_id(),
	'status' => array( 'wc-on-hold', 'wc-completed', 'wc-cancelled', 'wc-pending', 'wc-processing', 'wc-failed' ),
	'limit' => -1,
) );

do_action( 'woocommerce_before_account_orders', $has_orders );
// Error/Success message
$message = '';
if ( isset( $_GET['message'] ) ) {
	if( $_GET['message'] == 'success' ) {
		$message = 'Thanks for submitting your ShoutOut link, please submit the links to the rest of your posts so the brand can rate them.';
	} else if( $_GET['message'] == 'success-all' ) {
		$message = 'Thanks for submitting your ShoutOut links, stay tuned and the brand will rate your submissions as soon as they can!';
	}
}

?>

<?php if( $message ) : ?>
	<h3><?php echo $message ?></h3>
<?php endif; ?>


<?php if ( $has_orders ) : ?>

	<div class="accordion-wrapper">

	<?php foreach ( $customer_orders as $customer_order ) : ?>

		<?php
			$order_id = $customer_order->get_id();
			$order = wc_get_order( $customer_order );
			$order_status = $customer_order->get_data()['status'];
			$product_id = get_order_campaign($order_id);

			if ( $product_id > 0 ) :

				$campaign_title = get_the_title( $product_id );
				$campaign_strategy = get_post_meta($product_id, 'campaign_strategy', true);

				$brand_id = get_post_meta($product_id, 'brand', true);
				$brand_name = ($brand_id ? get_the_title($brand_id) : '');

				$shoutout_channels = get_post_meta( $product_id, 'shoutout_channels', true );

				$fulfillment_type = get_post_meta( $product_id, 'fulfillment_type', true );
				$promo_code_url = get_post_meta( $product_id, 'promo_code_url', true );
				$shoutout_code = get_post_meta( $order_id, 'shoutout_code', true );

				$product_shipped = get_post_meta( $order_id, 'product_shipped', true );
				$tracking_link = get_post_meta( $order_id, 'shoutout_shipment_tracking_link', true );

				$fulfillment_single_code = get_post_meta( $product_id, 'fulfillment_single_code', true );
				$instructions_count = get_post_meta( $product_id, 'instructions', true );

				$accordion_status = 'Please submit links to your ShoutOuts';
				$rated = true;
				$links_submitted = true;

				$hashtags = get_post_meta($product_id, 'hashtags');
				$excerpt = the_excerpt($product_id);

				if( is_array( $shoutout_channels ) ) {

					foreach( $shoutout_channels as $shoutout_channel ) {

						if ( get_post_meta( $order_id, $shoutout_channel . '_url', true ) === '' )  {

							$links_submitted = false;
						}

						if( get_post_meta( $order_id, $shoutout_channel . '_shoutout_rating', true ) === '' ) {

							$rated = false;
						}
					}
				}

				if( $rated === true ) {

					$accordion_status = 'ShoutOut Complete!';

				} else if ( $links_submitted === true ) {

					$accordion_status = 'Waiting for Brand rating';
				}
			?>

			<?php if( $order_status === 'cancelled' ) : ?>

				<div class="accordion-head disabled">
					<span><?php echo esc_html( $campaign_title ) . ' - <b>Cancelled</b>'; ?></span>
				</div>

			<?php else : ?>

				<div class="accordion-head">
					<span><?php echo esc_html( $campaign_title ) . ' - <b>' . esc_html( $accordion_status ) . '</b>'; ?></span>
					<span class="accordion-toggle"></span>
				</div>

			<?php endif; ?>

			<div class="accordion-body">
				<table>
					<tr>
						<td>
							<b>Order# <?php echo $order_id; ?></b>
						</td>
					</tr>
					<tr>
						<td>
							<b>Campaign:</b>
						</td>

						<td>
							<?php echo ( $brand_name !== '' ? $brand_name . ' - ' : '' ); echo esc_html( $campaign_title ); ?>
						</td>
					</tr>

					<tr>
						<td>
							<b>Order Date:</b>
						</td>

						<td style="margin-bottom: 20px;">
							<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
						</td>
					</tr>

					<tr>
						<td>
							<b>ShoutOut Score:</b>
						</td>

						<td>

						<?php if ( is_array( $shoutout_channels ) ) : ?>

							<?php foreach( $shoutout_channels as $channel ) : ?>

								<h5><?php echo ucfirst($channel); ?></h5>

								<?php if( get_post_meta( $order_id, $channel . '_shoutout_rating', true ) !== '' ) : ?>

									<?php $shoutout_rating = get_post_meta( $order_id, $channel . '_shoutout_rating', true ); ?>

									<div class="shoutout-rating-container">

										<?php  for ( $x = 0; $x < $shoutout_rating; $x++ ) : ?>

										<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/blue-heart-filled.svg'?>">

										<?php endfor; ?>

										<?php  for ( $x = 5; $x > $shoutout_rating; $x-- ) : ?>

										<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/grey-heart.svg'?>">

										<?php endfor; ?>

									</div>

									<br>

								<?php else : ?>

									<?php if ( get_post_meta( $order_id, $channel . '_url', true ) !== '' ) : ?>

										<p>Waiting for brand rating</p>

										<div class="nudge-brand-container"><span class="nudge-brand-button">
											Waiting a long time for your score? nudge brand for rating</span><br>

											<form class="sas-form nudge-brand-form" method="post">
												<p>You're about to nudge <?php echo $brand_name ?> to score your ShoutOut, please confirm.</p>

												<button class="sas-mini-form-button">Yes</button> <span class="cancel-nudge">no</span>

												<input type="hidden" name="type" value="scoring">

												<input type="hidden" name="order" value="<?php echo $order_id; ?>">

											</form>
										</div>

									<?php else : ?>

										<p>Please submit a link to your ShoutOut</p>

									<?php endif; ?>

									<div class="shoutout-link-submission-container">

										<?php if(!get_post_meta( $order_id, $channel . '_url', true )) : ?>
											<div class="confirm-campaign-received">
												<h5>Are you ready to submit your <?php echo ucfirst($channel); ?> ShoutOut?</h5>
												<button class="sas-round-button-primary">I'm Ready!</button>
											</div>
										<?php endif ?>
										<form class="sas-form shoutout-submission-form" style="<?php echo get_post_meta( $order_id, $channel . '_url', true ) ? '' : 'display: none;'; ?>" method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">


											<div class="form-control">
												<label><?php echo ucfirst($channel) ?> post URL</label>
												<input type="text" name="<?php echo esc_attr($channel); ?>_url" value="<?php echo get_post_meta( $order_id, $channel . '_url', true ); ?>">
											</div>

											<br>

											<button class="sas-form-submit">Submit</button>

											<br>

											<input type="hidden" name="shoutout" value="<?php echo $order_id; ?>">

											<input type="hidden" name="action" value="shoutout_links">

										</form>
									</div>

								<?php endif; ?>

							<?php endforeach; ?><!-- End foreach $shoutout_channels -->

							<?php if($campaign_strategy == 'giveaway'): ?>
								<h4>When you've chosen a winner for the giveaway, let the brand know here.</h4>
								<form class="sas-form" method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">


									<div class="form-control">
										<label>Giveaway winner email address</label>
										<input type="text" name="giveaway_winner" value="<?php echo get_post_meta( $order_id, 'giveaway_winner', true ); ?>">
									</div>

									<br>

									<button class="sas-form-submit">Submit</button>
									
									<input type="hidden" name="shoutout" value="<?php echo $order_id; ?>">
									<input type="hidden" name="action" value="giveaway_winner">
								</form>
							<?php endif; ?>

						<?php endif; ?>

						</td>
					</tr>

					<tr>
					<?php if ( $fulfillment_type == 'shipping' ) : ?>

						<td><b>Shipment:</b></td>

						<?php if ( $product_shipped || $tracking_link ) : ?>

							<td>Your product has been shipped! <?php echo ( $tracking_link ? 'You can track it here: <a href="' . $tracking_link . '">' . $tracking_link . '</a>' : '' ); ?></td>

						<?php else : ?>

							<td>
								Waiting for brand to ship your product.

								<div class="nudge-brand-container">
									<span class="nudge-brand-button">
									Waiting a long time for your shipment info? Nudge the brand in case they forgot.</span><br>

									<form class="sas-form nudge-brand-form" method="post">
										<p>You're about to nudge <?php echo $brand_name ?> to send shipping info, please confirm.</p>

										<button class="sas-mini-form-button">Yes</button> <span class="cancel-nudge">no</span>

										<input type="hidden" name="type" value="shipping">

										<input type="hidden" name="order" value="<?php echo $order_id; ?>">

									</form>
								</div>
							</td>

						<?php endif; ?>

					<?php elseif( $fulfillment_type == 'code' ) : ?>

						<td><b>Redemption Code:</b></td>

						<?php if( $fulfillment_single_code == '' ) : ?>

							<?php if ( $shoutout_code === '' ) : ?>

								<td>Waiting for brand to send redemption code.</td>

							<?php else : ?>

								<td>
									<?php echo esc_attr( $shoutout_code ); ?>

									<br>

									<p>redeem your code at <a href="<?php echo esc_attr($promo_code_url); ?>"><?php echo esc_attr($promo_code_url); ?></a></p>
								</td>

							<?php endif; ?>

						<?php else : ?>

							<td>
								<?php echo esc_attr( $fulfillment_single_code ); ?>

								<br>

								<p>redeem your code at <a href="<?php echo esc_attr($promo_code_url); ?>"><?php echo esc_attr($promo_code_url); ?></a></p>
							</td>

						<?php endif; ?>

					<?php endif; ?> <!-- endif $fulfillment_type -->

					</tr>
					<?php if( $instructions_count > 0 ) : ?>
						<tr>
							<td><b>Next Steps:</b></td>
							<td>
							<?php
								for( $i=0; $i<$instructions_count; $i++ ) {
									$meta_key = 'instructions_' . $i . '_steps';
									$step = get_post_meta( $product_id, $meta_key, true );
									if( $step !== '' ) {
										echo 'Step ' . ( $i + 1 ) . '. ' . $step . '<br>';
									}
								}
							?>
							</td>
						</tr>
					<?php endif; ?>
				</table>

				<br>

				<?php echo do_shortcode('[shoutout-requirements campaign_id="'.$product_id.'"]'); ?>
			</div><!-- .accordion-body -->

		<?php endif; ?><!-- endif $product_id > 0 -->

	<?php endforeach; ?><!-- endforeach $customer_orders -->

	</div><!-- .accordion-wrapper -->

<?php else : ?><!-- else if !$has_orders -->
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
			<?php _e( 'Go shop', 'woocommerce' ) ?>
		</a>
		<?php _e( 'No order has been made yet.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
