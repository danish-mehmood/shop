<?php

get_header();

if(!is_user_logged_in()) {
	wp_redirect(get_site_url().'/my-account');
}

$user = wp_get_current_user();
$user_id = $user->ID;
$user_roles = $user->roles;
if( in_array('administrator', $user_roles, true) ) {
	$user_brands = array();
	$all_brands = get_posts(array(
		'post_type' => 'brand',
		'numberposts' => -1,
	));

	foreach($all_brands as $brand) {
		$user_brands[] = array(
			'brand' => $brand->ID,
		);
	}
} elseif( in_array('brand', $user_roles, true) ) {
	$user_brands = get_brand_user_entries_by_user_id($user_id);
} else {
	wp_redirect(get_site_url() . '/my-account/edit-account');
}
$selected_brand = get_user_meta($user_id, 'dashboard_selected_brand', true)?get_user_meta($user_id, 'dashboard_selected_brand', true):$user_brands[0]['brand'];
$user_still_brand_member = false;
foreach($user_brands as $brand) {
	if($brand['brand']==$selected_brand) {
		$user_still_brand_member = true;
	}
}

$current_section = get_query_var('section')?get_query_var('section'):'campaigns';

?>
<div id="main-content">
	<div class="brand-account-wrapper">
		<input type="hidden" id="current-section" value="<?php echo esc_attr($current_section); ?>">
		<input type="hidden" id="site-url" value="<?php echo get_site_url(); ?>">
		<input type="hidden" id="selected-brand" value="<?php echo $user_still_brand_member?$selected_brand:$user_brands[0]['brand']; ?>">
		<div id="dashboard-loading">
			<div class="dashboard-loading__inner">
				<div class="loading-heart">
					<span>Loading</span> 
					<img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/hearts/blue-heart-filled.svg'; ?>">
				</div>
			</div>
		</div>

		<div class="sidebar-hamburger-container">
			<div class="sidebar__toggle">
				<i class="fa fa-bars"></i>
			</div>
		</div>
		<aside id="dashboard-sidebar">
			<div class="sidebar__inner">
				<div class="sidebar__toggle">
					<i class="fa fa-times"></i>
				</div>
				<div class="sidebar__brand-select-container">
					<?php if(count($user_brands)>1): ?>
						<label>Brand</label>
						<select id="sidebar-brand-select" class="<?php echo in_array('administrator', $user_roles, true)?'admin':''; ?>">
							<?php foreach($user_brands as $brand): ?>
								<option value="<?php echo $brand['brand']; ?>" <?php echo $brand['brand']==$selected_brand?'selected':''; ?>><?php echo get_the_title($brand['brand']); ?></option>
							<?php endforeach; ?>
						</select>
					<?php else: ?>
						<span class="sidebar__single-brand-title"><?php echo get_the_title($user_brands[0]['brand']); ?></span>
					<?php endif; ?>
				</div>
				<nav class="sidebar__nav">
					<ul class="sidebar__nav-list">
						<li class="sidebar__nav-item <?php echo $current_section=='campaigns'?'selected':'';?>" data-nav-item="campaigns"><!-- <img alt="Campaigns icon" width="25" height="25" src="https://via.placeholder.com/50"> --><span>Campaigns</span></li>
						<li class="sidebar__nav-item <?php echo $current_section=='account-settings'?'selected':'';?>" data-nav-item="account-settings"><!-- <img alt="Account icon" width="25" height="25" src="https://via.placeholder.com/50"> --><span>Account</span></li>
						<li class="sidebar__nav-item <?php echo $current_section=='ig-assistant'?'selected':'';?>" data-nav-item="ig-assistant"><!-- <img alt="Instagram Assistant icon" width="25" height="25" src="https://via.placeholder.com/50"> --><span>IG Assistant</span></li>
					</ul>
				</nav>
			</div>
		</aside>
		<main id="brand-dashboard">
			<div class="dashboard-content"></div>
		</main>
	</div> <!-- .brand-account-container -->
</div> <!-- #main-content -->

<?php

get_footer();
