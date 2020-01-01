<?php

get_header();

$uid = get_current_user_id();

$param_string = '';

if( isset($_GET['edit']) ) {
	
	$edit = $_GET['edit'];

	$owner_id = get_post_meta( $edit, 'owner_id', true);

	if( $owner_id == $uid ) {

		$param_string = get_post_meta( $edit, 'param_string', true );
	
	} else {

		$edit = '';
	}
}

?>
<div id="main-content" class="affiliate-campaign-form-wrapper">
	<form id="affiliate-campaign-form" class="sas-form" method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
		<?php if( $edit ) : ?>
			<h1>Edit Affiliate Campaign</h1>
			<p style="color: red;">By changing this string any links using the old string will no longer function, if you'd like a new link but still want the old link to work please go <a href="<?php echo get_site_url() . '/my-account/affiliate' ?>">back to your dashboard</a> and click the "Create New Refferral Link" button</p>
		<?php else : ?>
			<h1>New Affiliate Campaign</h1>
		<?php endif; ?>
		<div class="error" style="color: red;"><?php if(isset($_GET['e']) && $_GET['e'] == 'exists') { echo 'Sorry, that affiliate link already exists, try something else.'; } ?></div>
		<input type="text" name="param_string" value="<?php echo esc_attr($param_string); ?>">
		<button class="sas-round-button-primary">Submit</button>
		<input type="hidden" name="edit" value="<?php echo esc_attr($edit); ?>">
		<input type="hidden" name="action" value="affiliate_campaign">
		<?php wp_nonce_field( 'affiliate_campaign', 'affiliate_campaign_nonce' ); ?>
	</form>
</div> <!-- #main-content -->

<?php

get_footer();