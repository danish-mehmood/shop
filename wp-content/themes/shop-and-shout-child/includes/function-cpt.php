<?php
/*
Custom post type for Client logo (OLD)
*/
function shopandshout_logo() {
	$labels = array(
		'name' => _x( 'Client Logo', 'Post type general name', 'textdomain' ) ,
		'singular_name' => _x( 'Client Logo', 'Post type singular name', 'textdomain' ) ,
		'menu_name' => _x( 'Client Logos', 'Admin Menu text', 'textdomain' ) ,
		'name_admin_bar' => _x( 'Client Logo', 'Add New on Toolbar', 'textdomain' ) ,
		'add_new' => __( 'Add New', 'textdomain' ) ,
		'add_new_item' => __( 'Add New Logo', 'textdomain' ) ,
		'new_item' => __( 'New Logo', 'textdomain' ) ,
		'edit_item' => __( 'Edit Logo', 'textdomain' ) ,
		'view_item' => __( 'View Logo', 'textdomain' ) ,
		'all_items' => __( 'All Logos', 'textdomain' ) ,
		'search_items' => __(' Search Logos', 'textdomain' ) ,
		'not_found' => __( 'No Logos found.', 'textdomain' ) ,
		'not_found_in_trash' => __( 'No Logos found in Trash.', 'textdomain' ) 
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'menu_icon' => 'dashicons-images-alt2',
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'client-logo'
		) ,
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array(
			'title',
			'thumbnail',
		)
	);
	register_post_type( 'client-logo', $args );
}

add_action( 'init', 'shopandshout_logo' );

// Adding CPT for storing data of unregistered users
add_action( 'init', 'unregistered_user_custom_post_type' );
function unregistered_user_custom_post_type() {
    $args = array(
       	'labels'      => array(
           	'name'          => __('Unregistered Users'),
           	'singular_name' => __('Unregistered User'),
       	),
       	'public'      => false,
       	'has_archive' => false,
       	'rewrite'     => array( 'slug' => 'unregistered_users' ),
    );

    register_post_type('unregistered_user', $args );
}

// Adding CPT for brands
add_action( 'init', 'brand_custom_post_type' );
function brand_custom_post_type() {
    $args = array(
       	'labels'      => array(
           	'name'          => __('Brands'),
           	'singular_name' => __('Brand'),
       	),
       	'public'      => true,
       	'has_archive' => false,
       	'rewrite'     => array( 'slug' => 'brand' ),
		    'menu_icon'   => 'dashicons-building',
    );

    register_post_type( 'brand', $args );
}
add_action('add_meta_boxes', 'brand_meta_boxes');
function brand_meta_boxes() {

    add_meta_box(
        'brand-users',
        'Brand Users',
        'brand_users_callback',
        'brand'
    );
}
function brand_users_callback($post) {
  $brand_user_entries = get_brand_user_entries_by_brand_id($post->ID);
  ?>
  <a target="_blank" class="button button-primary button-small" href="<?php echo get_site_url() . '/wp-admin/post-new.php?post_type=brand_user'; ?>">Add User</a>
  <table cellpadding="7">
    <tr>
      <th>Edit Entry</th>
      <th>User</th>
      <th>Phone</th>
      <th>role</th>
    </tr>
    <?php foreach($brand_user_entries as $entry) : ?>
    <?php
      $entry_id = $entry['entry_id'];
      $user_id = $entry['user'];
      $user = get_userdata($user_id);
      $email = $user->user_email;
      $phone = get_user_meta($user_id, 'contact_phone', true) ? get_user_meta($user_id, 'contact_phone', true) : get_user_meta($user_id, 'brand_phone', true);
    ?>
      <tr>
        <td><a target="_blank" href="<?php echo get_site_url() . '/wp-admin/post.php?post=' . $entry_id . '&action=edit'; ?>"><?php echo $entry_id ?></a></td>
        <td><a target="_blank" href="<?php echo get_site_url() . '/wp-admin/user-edit.php?user_id=' . $user_id; ?>"><?php echo $email; ?></a></td>
        <td><?php echo $phone ?></td>
        <td><?php echo $entry['role']; ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
  <?php
  echo ob_get_clean();
}

// Adding CPT for brand user entries
add_action( 'init', 'brand_user_custom_post_type' );
function brand_user_custom_post_type() {
    $args = array(
       'labels'      => array(
           'name'          => __('Brand Users'),
           'singular_name' => __('Brand User'),
       ),
       'public'      => true,
       'has_archive' => false,
       'rewrite'     => array( 'slug' => 'brand_user' ),
    );

    register_post_type( 'brand_user', $args );
}

// Product addition meta boxes
add_action('add_meta_boxes', 'product_meta_boxes');
function product_meta_boxes() {

    add_meta_box(
      'campaign-goals',
      'Campaign Goals',
      'campaign_goals_callback',
      'product',
      'advanced',
      'high'
    );
}
function campaign_goals_callback($post) {
  $campaign_goals = get_posts(array(
    'post_type' => 'campaign_goal',
    'post_status' => 'any',
    'numberposts' => -1,
    'meta_key' => 'campaign',
    'meta_value' => $post->ID,
  ));
  ob_start();
  ?>
    <table style="text-align: left" cellpadding="5">
      <tr>
        <th>Edit</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Goal (daily influencers)</th>
      </tr>
      <?php foreach($campaign_goals as $goal): 
        $value = get_post_meta($goal->ID, 'goal', true);
        $start_date = get_post_meta($goal->ID, 'start_date', true);
        $end_date = get_post_meta($goal->ID, 'end_date', true);
      ?>
        <tr>
          <td><a target="_blank" href="<?php echo get_site_url() . '/wp-admin/post.php?post='.$goal->ID.'&action=edit'; ?>"><?php echo $goal->ID ?></a></td>
          <td><?php echo $start_date; ?></td>
          <td><?php echo $end_date?$end_date:'Not Set'; ?></td>
          <td><?php echo $value; ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
    <a target="_blank" href="<?php echo get_site_url() . '/wp-admin/post-new.php?post_type=campaign_goal' ?>">Create New Goal</a>
  <?php
  echo ob_get_clean();
}

// Adding CPT for campaign goal
add_action( 'init', 'campaign_goal_custom_post_type' );
function campaign_goal_custom_post_type() {
    $args = array(
      'labels'      => array(
        'name'          => __('Campaign Goals'),
        'singular_name' => __('Campaign Goal'),
      ),
      'public'      => true,
      'has_archive' => false,
      'rewrite'     => array( 'slug' => 'campaign_goal' ),
      'menu_icon'   => 'dashicons-yes',
    );

    register_post_type( 'campaign_goal', $args );
}

//Exclusive Content CPT
add_action( 'init', 'inf_exclusive_content_custom_post_type' );
function inf_exclusive_content_custom_post_type() {
	$args = array(
		'labels'      => array(
		   'name'          => __('Exclusive Content'),
		   'singular_name' => __('Exclusive Content'),
		),
		'public'      => true,
		'has_archive' => true,
		'rewrite'     => array( 'slug' => 'exclusive_content' ),
		'supports'    => array('title', 'editor', 'thumbnail'),
		'menu_icon'   => 'dashicons-pressthis',
	);

	register_post_type( 'exclusive_content', $args );
}

//Affiliate Link CPT
add_action( 'init', 'affiliate_link_custom_post_type' );
function affiliate_link_custom_post_type() {
	$args = array(
		'labels'      => array(
		   'name'          => __('Affiliate Links'),
		   'singular_name' => __('Affiliate Link'),
		),
		'public'      => true,
		'has_archive' => false,
		'rewrite'     => array( 'slug' => 'affiliate_link' ),
    'publicly_queryable' => false,
    'query_var' => false,
	);

	register_post_type( 'affiliate_link', $args );
}

// Adding CPT for affiliate payouts
add_action( 'init', 'affiliate_payout_custom_post_type' );
function affiliate_payout_custom_post_type() {
    $args = array(
      'public'      => false,
      'has_archive' => false,
      'rewrite'     => array( 'slug' => 'affiliate_payout_user' ),
    );

    register_post_type( 'affiliate_payout', $args );
}

// Adding CPT for affiliate payout user entries
add_action( 'init', 'affiliate_payout_user_custom_post_type' );
function affiliate_payout_user_custom_post_type() {
    $args = array(
        'labels'      => array(
            'name'          => __('Brands'),
            'singular_name' => __('Brand'),
        ),
        'public'      => true,
        'has_archive' => false,
        'rewrite'     => array( 'slug' => 'brand' ),
    'menu_icon'   => 'dashicons-building',
    );

    register_post_type( 'brand', $args );
}

/*
 * Add columns to exhibition post list
 */
function add_affiliate_link_acf_columns ( $columns ) {
    return array_merge ( $columns, array ( 
        'owner' => 'Owner',
        'link_string' => 'Link String'
    ) );
}
add_filter ( 'manage_affiliate_link_posts_columns', 'add_affiliate_link_acf_columns' );
function affiliate_link_custom_column ( $column, $post_id ) {
    switch ( $column ) {
    case 'owner':
        $owner_id = get_post_meta($post_id, 'owner_id', true);
        $user_data = get_userdata($owner_id);
        $email = $user_data->user_email;
        echo '<a href="' . get_site_url() . '/wp-admin/user-edit.php?user_id=' . $owner_id . '">' . $email . '</a>';
        break;
    case 'link_string':
        $param_string = get_post_meta($post_id, 'param_string', true);
        echo $param_string;
        break;
    }
}
add_action ( 'manage_affiliate_link_posts_custom_column', 'affiliate_link_custom_column', 10, 2 );

?>