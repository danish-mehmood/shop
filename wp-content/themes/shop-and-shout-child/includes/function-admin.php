<?php
if ( is_admin() ) {
    add_action( 'admin_menu', 'add_admin_data_menu', 100 );
}

function add_admin_data_menu() {
    add_menu_page(
        'Data',
        'Data',
        'manage_options', // Required user capability
        'sas-data',
        'generate_data_page',
        'dashicons-chart-area',
        5
    );
}

function generate_data_page() {
$campaigns = get_posts(array(
    'post_type' => 'product',
    'post_status' => array('publish', 'draft', 'pending'),
    'numberposts' => -1,
));
?>
<div>
	<h1>Site Data</h1>

    <br>

    <h3>Products</h3>
	<form id="admin-export-products" method="post">
        <iframe class="file-download" style="display:none;"></iframe>
        <div style="display: none;" class="notice is-dismissible"> 
            <p><strong class="notice-message"></strong></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        </div>
        <br>
        <label>Date Range (leave empty for all)</label>
        <br>
        <label>From</label>
        <input type="text" id="product-date-range-from">
        <br>
        <label>To</label>
        <input type="text" id="product-date-range-to">
        <br>

		<?php wp_nonce_field('export_products', 'export-products'); ?>
		<button class="button button-primary" type="submit">Export Products</button>
	</form>

    <br>

    <h3>Orders</h3>
    <form id="admin-export-orders" method="post">
        <iframe class="file-download" style="display:none;"></iframe>
        <div style="display: none;" class="notice is-dismissible"> 
            <p><strong class="notice-message"></strong></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        </div>
        <br>
        <label>Select Campaign(s) (leave empty for all)</label>
        <br>
        <select id="campaign-select" multiple>
            <?php foreach($campaigns as $campaign) : 
                $orders = get_orders_by_campaign_id($campaign->ID);
                if($campaign->post_title && count($orders)) : ?>
                    <option value="<?php echo $campaign->ID ?>"><?php echo $campaign->post_title . ' (' . $campaign->post_status . ')' . ' (' . count($orders) . ')' ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <br><br>
        <label>Date Range (leave empty for all)</label>
        <br>
        <label>From</label>
        <input type="text" id="order-date-range-from">
        <br>
        <label>To</label>
        <input type="text" id="order-date-range-to">
        <br>
        <?php wp_nonce_field('export_orders', 'export-orders'); ?>
        <button class="button button-primary" type="submit">Export Orders</button>
    </form>
</div>
<?php
}

if ( is_admin() ) {
    add_action( 'admin_menu', 'add_admin_affiliate_menu', 100 );
}

function add_admin_affiliate_menu() {
    add_menu_page(
        'Affiliate',
        'Affiliate',
        'manage_options', // Required user capability
        'sas-affiliate',
        'generate_admin_affiliate_page',
        'dashicons-groups',
        5
    );
}

function generate_admin_affiliate_page() {

?>
<div>
    <h1>Reports</h1>
    <form id="admin-export-affiliate-report" method="post">
        <iframe class="file-download" style="display:none;"></iframe>
        <div style="display: none;" class="notice is-dismissible"> 
            <p><strong class="notice-message"></strong></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        </div>
        <br>
        <?php wp_nonce_field('export_affiliate_report', 'export-affiliate-report'); ?>
        <input type="text" id="period-select"><br>
        <button class="button button-primary" type="submit">Export Products</button>
    </form>
</div>
<?php
}

if ( is_admin() ) {
    add_action( 'admin_menu', 'add_user_menu_entry', 100 );
}

function add_user_menu_entry() {
    add_users_page(
        'Authentique',
        'Authentique',
        'manage_options',
        'authentique',
        'generate_authentique_page'
    );

    add_users_page(
        'Testing',
        'Testing',
        'manage_options',
        'testing',
        'generate_testing_page'
    );
}

function generate_authentique_page() {

?>
<div>
    <h2>Authentique processes</h2>
    <form id="admin-authentique-check" method="post">
        <div style="display: none;" class="notice is-dismissible"> 
            <p><strong class="notice-message"></strong></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        </div>
        <br>
        <?php wp_nonce_field('authentique_check', 'authentique-check-nonce'); ?>
        <button class="button button-primary" type="submit">Check Authentique</button>
    </form>
</div>
<?php
}

function generate_testing_page() {

?>
<div>
    <h2>Email Test</h2>
    <form id="admin-email-check" method="post">
        <div style="display: none;" class="notice is-dismissible"> 
            <p><strong class="notice-message"></strong></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        </div>
        <br>
        <?php wp_nonce_field('email_check', 'email-check-nonce'); ?>
        <label>User Email:</label>
        <input type="text" id="test-user-email">
        <br>
        <label>From Email:</label>
        <input type="text" id="test-email-from">
        <br>
        <label>Message Subject:</label>
        <input type="text" id="test-email-subject">
        <br>
        <label>Message Body:</label>
        <textarea id="test-email-body"></textarea>
        <br>
        <button class="button button-primary" type="submit">Send Email</button>
    </form>
</div>
<?php
}

?>