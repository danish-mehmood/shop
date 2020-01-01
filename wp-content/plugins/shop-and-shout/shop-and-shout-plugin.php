<?php
/* Plugin Name: Shop And Shout
 * Description: Functionality for the Shop and Shout Site
 * Version:     1.0.0
 * Author:      Shop and Shout ltd.
 * Author URI:  https://shopandshout.com/
 */

// Selectively enqueue plugin syles and scripts (leave theme-specific code in the theme)

// Connect to social media

// Create API connection variables (shared)

    // Create API permission variables (specific)
include 'facebook-login/index.php';

//  Create shortcodes for all login/out and connect buttons
// https://codex.wordpress.org/Shortcode_API

// Create shortcodes for displaying social data (include conditional logic)
add_shortcode( 'ss_inf-registration-form', 'ss_inf_registration_form_shortcode' );
function ss_inf_registration_form_shortcode() {
ob_start();

include( get_stylesheet_directory() . '/includes/api/facebook/facebook-signup.php' );

$ref = ( isset($_GET['ref']) ? $_GET['ref'] : '' );
?>
<div class="inf-registration-form-container">
    <div class="signup-as-brand">Not an Influencer? <a href="<?php echo get_site_url() . '/brand-signup/' ?>">Signup as a Brand</a> instead.</div>
  <form class="sas-form" id="inf-registration-form" method="post" action="<?php echo admin_url('admin-post.php');  ?>">
    <h1>Ready to collaborate with great brands?</h1>

    <a href="<?php  echo $facebook_signup_url ?>"class="social-connect-button" id="inf_facebook"><img src=" <?php echo content_url() . '/uploads/2018/11/Facebook-Icon.svg'; ?>"><span>SIGN UP WITH FACEBOOK</span></a>

    <div id="gSignInWrapper">
      <div id="inf_google" class="social-connect-button">
        <img src="<?php echo content_url() . '/uploads/2018/11/Google-Icon.svg'; ?>"><span>SIGN UP WITH GOOGLE</span>
      </div>
    </div>
    <div id="gSignIn-ajax-response"></div>

    <p>or</p>

    <div class="alert result-message alert-danger">
      <?php echo isset($_GET['errors'])&&$_GET['errors']=='user_exists'?'Sorry, that email is already in use.':''; ?>
      <?php echo isset($_GET['errors'])&&$_GET['errors']=='nonce_failed'?'Oops, something went wrong, please try again.':''; ?>
    </div>
    <div class="form-group">
      <label for="inf_firstname">First Name</label>
      <input type="text" name="inf_first_name" placeholder="First Name" class="form-control" required data-hj-whitelist />
    </div>
    <div class="form-group">
      <label for="inf_lastName">Last Name</label>
      <input type="text" name="inf_last_name" placeholder="Last Name" class="form-control" required data-hj-whitelist />
    </div>
    <div class="form-group">
      <label for="inf_email">Email</label>
      <input type="email" name="inf_email" placeholder="Your email address" class="form-control" required data-hj-whitelist />
    </div>
    <div class="password-info-container">
      <div id="password-info">
        <ul>
          <li id="uppercase" class="invalid"><span>One uppercase letter</span></li>
          <li id="number" class="invalid"><span>One number</span></li>
          <li id="length" class="invalid"><span>6 characters minimum</span></li>
        </ul>
      </div>
    </div>
    <div class="form-group">
      <label for="inf_password">Password</label>
      <input type="password" name="inf_password" placeholder="Pasword" class="form-control form-password" autocomplete="new-password" required  />
      <span class="toggle-password">Show</span>
    </div>
    <br>
    <p style="text-align: left; font-size: 12px; color: #333;">
    Have a referral code? (You can also submit this later)
    </p>
    <div class="form-control">
      <input type="text" name="referral" value="<?php echo esc_attr($ref); ?>" placeholder="Referral Code" class="form-control" data-hj-whitelist />
    </div>
    <input type="hidden" name="action" value="register_influencer">
    <?php wp_nonce_field( 'register_influencer','register_influencer_nonce', true, true ); ?>
    <div class="checkbox">
      <label><span>By signing up you are agreeing to ShopandShout's <a href="<?php echo get_site_url();?>/terms/" target="_blank">terms and conditions</a></span></label>
    </div>
    <br>
    <button type="submit" class="sas-form-submit">Sign Up</button>
  </form>
</div>
<?php
return ob_get_clean();
}



/* Shortcode for login form  */
add_shortcode( 'ss_inf_login_form', 'ss_inf_login_form_shortcode' );
function ss_inf_login_form_shortcode() {
ob_start();

include( get_stylesheet_directory() . '/includes/api/social-login.php');
?>

<div class="inf-login-form-container">
    <form id="inf-login-form" class="sas-form" method="post">
		<h1>Welcome back! We missed you</h1>

		<?php if( !isset($_GET['b'] ) ) : ?>

            <a href="<?php echo 'https://api.instagram.com/oauth/authorize/?client_id=' . $insta_client_id . '&redirect_uri=' . urlencode($redirect_uri) . '&response_type=code&scope=basic&hl=en' ?>" class="social-connect-button" id="inf_instagram"><img src="<?php echo content_url() . '/uploads/2018/11/Insta-icon-white.svg'; ?>"><span>SIGN IN WITH INSTAGRAM</span></a>

	        <a href="<?php echo $facebook_login_url ?>"clsass="social-connect-button" id="inf_facebook" scope="public_profile,email" onlogin="checkLoginState();"><img src="<?php echo content_url() . '/uploads/2018/11/Facebook-Icon.svg'; ?>"><span>SIGN IN WITH FACEBOOK</span></a>

			<div id="gSignInWrapper">
				<div id="inf_google" class="social-connect-button">
					<img src="<?php echo content_url() . '/uploads/2018/11/Google-Icon.svg' ?>"><span>SIGN IN WITH GOOGLE</span>
				</div>
			</div>
			<div id="gSignIn-ajax-response"></div>
			<script>startApp();</script>

	        <p>or</p>

    	<?php endif; ?>

		<div class="form-group">
			<label for="inf_email">Username / Email</label>
			<input type="text" name="username" id="inf_email" placeholder="Your email address" class="form-control" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
		</div>

		<div class="form-group">
			<label for="inf_password">Password</label>
			<input type="password" name="password" id="inf_password" placeholder="password" required/>
			<span class="toggle-password">Show</span>
		</div>
		<br>
		<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot password?', 'woocommerce' ); ?></a>

		<?php do_action( 'woocommerce_login_form' ); ?>
		<br>
		<br>
		<label >
			<input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span>Remember me</span>
		</label>
		<br><br>
		<button type="submit" class="sas-form-submit fixed-button" name="login" value="Login">Login</button>
		<br>
		<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>

		<?php do_action( 'woocommerce_login_form_end' ); ?>
	</form>
</div>

<?php
return ob_get_clean();
}


?>
