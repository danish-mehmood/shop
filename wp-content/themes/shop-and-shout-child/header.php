<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<?php elegant_description(); ?>
	<?php elegant_keywords(); ?>
	<?php elegant_canonical(); ?>

	<?php do_action( 'et_head_meta' ); ?>

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<!-- Rollbar -->
	<?php if(!SAS_TEST_MODE) : ?>
	<script>
	var _rollbarConfig = {
	    accessToken: "9c4abd8dd7434449acaa08dd22b8978d",
	    captureUncaught: true,
	    captureUnhandledRejections: true,
	    payload: {
	        environment: "production"
	    }
	};
	// Rollbar Snippet
	!function(r){function e(n){if(o[n])return o[n].exports;var t=o[n]={exports:{},id:n,loaded:!1};return r[n].call(t.exports,t,t.exports,e),t.loaded=!0,t.exports}var o={};return e.m=r,e.c=o,e.p="",e(0)}([function(r,e,o){"use strict";var n=o(1),t=o(4);_rollbarConfig=_rollbarConfig||{},_rollbarConfig.rollbarJsUrl=_rollbarConfig.rollbarJsUrl||"https://cdnjs.cloudflare.com/ajax/libs/rollbar.js/2.4.6/rollbar.min.js",_rollbarConfig.async=void 0===_rollbarConfig.async||_rollbarConfig.async;var a=n.setupShim(window,_rollbarConfig),l=t(_rollbarConfig);window.rollbar=n.Rollbar,a.loadFull(window,document,!_rollbarConfig.async,_rollbarConfig,l)},function(r,e,o){"use strict";function n(r){return function(){try{return r.apply(this,arguments)}catch(r){try{console.error("[Rollbar]: Internal error",r)}catch(r){}}}}function t(r,e){this.options=r,this._rollbarOldOnError=null;var o=s++;this.shimId=function(){return o},"undefined"!=typeof window&&window._rollbarShims&&(window._rollbarShims[o]={handler:e,messages:[]})}function a(r,e){if(r){var o=e.globalAlias||"Rollbar";if("object"==typeof r[o])return r[o];r._rollbarShims={},r._rollbarWrappedError=null;var t=new p(e);return n(function(){e.captureUncaught&&(t._rollbarOldOnError=r.onerror,i.captureUncaughtExceptions(r,t,!0),i.wrapGlobals(r,t,!0)),e.captureUnhandledRejections&&i.captureUnhandledRejections(r,t,!0);var n=e.autoInstrument;return e.enabled!==!1&&(void 0===n||n===!0||"object"==typeof n&&n.network)&&r.addEventListener&&(r.addEventListener("load",t.captureLoad.bind(t)),r.addEventListener("DOMContentLoaded",t.captureDomContentLoaded.bind(t))),r[o]=t,t})()}}function l(r){return n(function(){var e=this,o=Array.prototype.slice.call(arguments,0),n={shim:e,method:r,args:o,ts:new Date};window._rollbarShims[this.shimId()].messages.push(n)})}var i=o(2),s=0,d=o(3),c=function(r,e){return new t(r,e)},p=d.bind(null,c);t.prototype.loadFull=function(r,e,o,t,a){var l=function(){var e;if(void 0===r._rollbarDidLoad){e=new Error("rollbar.js did not load");for(var o,n,t,l,i=0;o=r._rollbarShims[i++];)for(o=o.messages||[];n=o.shift();)for(t=n.args||[],i=0;i<t.length;++i)if(l=t[i],"function"==typeof l){l(e);break}}"function"==typeof a&&a(e)},i=!1,s=e.createElement("script"),d=e.getElementsByTagName("script")[0],c=d.parentNode;s.crossOrigin="",s.src=t.rollbarJsUrl,o||(s.async=!0),s.onload=s.onreadystatechange=n(function(){if(!(i||this.readyState&&"loaded"!==this.readyState&&"complete"!==this.readyState)){s.onload=s.onreadystatechange=null;try{c.removeChild(s)}catch(r){}i=!0,l()}}),c.insertBefore(s,d)},t.prototype.wrap=function(r,e,o){try{var n;if(n="function"==typeof e?e:function(){return e||{}},"function"!=typeof r)return r;if(r._isWrap)return r;if(!r._rollbar_wrapped&&(r._rollbar_wrapped=function(){o&&"function"==typeof o&&o.apply(this,arguments);try{return r.apply(this,arguments)}catch(o){var e=o;throw e&&("string"==typeof e&&(e=new String(e)),e._rollbarContext=n()||{},e._rollbarContext._wrappedSource=r.toString(),window._rollbarWrappedError=e),e}},r._rollbar_wrapped._isWrap=!0,r.hasOwnProperty))for(var t in r)r.hasOwnProperty(t)&&(r._rollbar_wrapped[t]=r[t]);return r._rollbar_wrapped}catch(e){return r}};for(var u="log,debug,info,warn,warning,error,critical,global,configure,handleUncaughtException,handleUnhandledRejection,captureEvent,captureDomContentLoaded,captureLoad".split(","),f=0;f<u.length;++f)t.prototype[u[f]]=l(u[f]);r.exports={setupShim:a,Rollbar:p}},function(r,e){"use strict";function o(r,e,o){if(r){var t;"function"==typeof e._rollbarOldOnError?t=e._rollbarOldOnError:r.onerror&&!r.onerror.belongsToShim&&(t=r.onerror,e._rollbarOldOnError=t);var a=function(){var o=Array.prototype.slice.call(arguments,0);n(r,e,t,o)};a.belongsToShim=o,r.onerror=a}}function n(r,e,o,n){r._rollbarWrappedError&&(n[4]||(n[4]=r._rollbarWrappedError),n[5]||(n[5]=r._rollbarWrappedError._rollbarContext),r._rollbarWrappedError=null),e.handleUncaughtException.apply(e,n),o&&o.apply(r,n)}function t(r,e,o){if(r){"function"==typeof r._rollbarURH&&r._rollbarURH.belongsToShim&&r.removeEventListener("unhandledrejection",r._rollbarURH);var n=function(r){var o,n,t;try{o=r.reason}catch(r){o=void 0}try{n=r.promise}catch(r){n="[unhandledrejection] error getting `promise` from event"}try{t=r.detail,!o&&t&&(o=t.reason,n=t.promise)}catch(r){t="[unhandledrejection] error getting `detail` from event"}o||(o="[unhandledrejection] error getting `reason` from event"),e&&e.handleUnhandledRejection&&e.handleUnhandledRejection(o,n)};n.belongsToShim=o,r._rollbarURH=n,r.addEventListener("unhandledrejection",n)}}function a(r,e,o){if(r){var n,t,a="EventTarget,Window,Node,ApplicationCache,AudioTrackList,ChannelMergerNode,CryptoOperation,EventSource,FileReader,HTMLUnknownElement,IDBDatabase,IDBRequest,IDBTransaction,KeyOperation,MediaController,MessagePort,ModalWindow,Notification,SVGElementInstance,Screen,TextTrack,TextTrackCue,TextTrackList,WebSocket,WebSocketWorker,Worker,XMLHttpRequest,XMLHttpRequestEventTarget,XMLHttpRequestUpload".split(",");for(n=0;n<a.length;++n)t=a[n],r[t]&&r[t].prototype&&l(e,r[t].prototype,o)}}function l(r,e,o){if(e.hasOwnProperty&&e.hasOwnProperty("addEventListener")){for(var n=e.addEventListener;n._rollbarOldAdd&&n.belongsToShim;)n=n._rollbarOldAdd;var t=function(e,o,t){n.call(this,e,r.wrap(o),t)};t._rollbarOldAdd=n,t.belongsToShim=o,e.addEventListener=t;for(var a=e.removeEventListener;a._rollbarOldRemove&&a.belongsToShim;)a=a._rollbarOldRemove;var l=function(r,e,o){a.call(this,r,e&&e._rollbar_wrapped||e,o)};l._rollbarOldRemove=a,l.belongsToShim=o,e.removeEventListener=l}}r.exports={captureUncaughtExceptions:o,captureUnhandledRejections:t,wrapGlobals:a}},function(r,e){"use strict";function o(r,e){this.impl=r(e,this),this.options=e,n(o.prototype)}function n(r){for(var e=function(r){return function(){var e=Array.prototype.slice.call(arguments,0);if(this.impl[r])return this.impl[r].apply(this.impl,e)}},o="log,debug,info,warn,warning,error,critical,global,configure,handleUncaughtException,handleUnhandledRejection,_createItem,wrap,loadFull,shimId,captureEvent,captureDomContentLoaded,captureLoad".split(","),n=0;n<o.length;n++)r[o[n]]=e(o[n])}o.prototype._swapAndProcessMessages=function(r,e){this.impl=r(this.options);for(var o,n,t;o=e.shift();)n=o.method,t=o.args,this[n]&&"function"==typeof this[n]&&("captureDomContentLoaded"===n||"captureLoad"===n?this[n].apply(this,[t[0],o.ts]):this[n].apply(this,t));return this},r.exports=o},function(r,e){"use strict";r.exports=function(r){return function(e){if(!e&&!window._rollbarInitialized){r=r||{};for(var o,n,t=r.globalAlias||"Rollbar",a=window.rollbar,l=function(r){return new a(r)},i=0;o=window._rollbarShims[i++];)n||(n=o.handler),o.handler._swapAndProcessMessages(l,o.messages);window[t]=n,window._rollbarInitialized=!0}}}}]);
	// End Rollbar Snippet
	</script>
	<!-- End Rollbar -->
	<?php endif; ?>

	<?php $template_directory_uri = get_template_directory_uri(); ?>
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( $template_directory_uri . '/js/html5.js"' ); ?>" type="text/javascript"></script>
	<![endif]-->

	<script type="text/javascript">
		document.documentElement.className = 'js';
	</script>

	<!-- Google API -->
	<script src="https://apis.google.com/js/platform.js" async defer></script>
	<meta name="google-signin-client_id" content="196707225696-dree7unq0gf7500eno8ad9vle3edq2bf.apps.googleusercontent.com">
	<!-- End Google API -->

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109836824-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'UA-109836824-1');
	</script>
	<!-- End Google Analytics -->

	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-KVGJ6R2');</script>
	<!-- End Google Tag Manager -->

	<!-- Favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_site_url() . '/apple-touch-icon.png' ?>">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_site_url() . '/favicon-32x32.png' ?>">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_site_url() . '/favicon-16x16.png' ?>">
	<link rel="manifest" href="<?php echo get_site_url() . '/site.webmanifest'; ?>">
	<link rel="mask-icon" href="<?php echo get_site_url() . '/safari-pinned-tab.svg'; ?>" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="theme-color" content="#ffffff">
	<!-- End Favicon -->

	<!-- Snap Pixel Code -->
	<script type='text/javascript'>
		(function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function()
		{a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};
		a.queue=[];var s='script';r=t.createElement(s);r.async=!0;
		r.src=n;var u=t.getElementsByTagName(s)[0];
		u.parentNode.insertBefore(r,u);})(window,document,
		'https://sc-static.net/scevent.min.js');

		snaptr('init', '011cca56-4d6f-4b02-8496-fcb608fd6e37', {
		'user_email': '__INSERT_USER_EMAIL__'
		});

		snaptr('track', 'PAGE_VIEW');
	</script>
	<!-- End Snap Pixel Code -->

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KVGJ6R2"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<?php
	$product_tour_enabled = et_builder_is_product_tour_enabled();
	$page_container_style = $product_tour_enabled ? ' style="padding-top: 0px;"' : ''; ?>
	<div id="page-container"<?php echo $page_container_style; ?>>
<?php
	if ( $product_tour_enabled || is_page_template( 'page-template-blank.php' ) ) {
		return;
	}

	$et_secondary_nav_items = et_divi_get_top_nav_items();

	$et_phone_number = $et_secondary_nav_items->phone_number;

	$et_email = $et_secondary_nav_items->email;

	$et_contact_info_defined = $et_secondary_nav_items->contact_info_defined;

	$show_header_social_icons = $et_secondary_nav_items->show_header_social_icons;

	$et_secondary_nav = $et_secondary_nav_items->secondary_nav;

	$et_top_info_defined = $et_secondary_nav_items->top_info_defined;

	$et_slide_header = 'slide' === et_get_option( 'header_style', 'left' ) || 'fullscreen' === et_get_option( 'header_style', 'left' ) ? true : false;
?>



	<?php if ( $et_slide_header || is_customize_preview() ) : ?>
		<div class="et_slide_in_menu_container">
			<?php if ( 'fullscreen' === et_get_option( 'header_style', 'left' ) || is_customize_preview() ) { ?>
				<span class="mobile_menu_bar et_toggle_fullscreen_menu"></span>
			<?php } ?>

			<?php
				if ( $et_contact_info_defined || true === $show_header_social_icons || false !== et_get_option( 'show_search_icon', true ) || class_exists( 'woocommerce' ) || is_customize_preview() ) { ?>
					<div class="et_slide_menu_top">

					<?php if ( 'fullscreen' === et_get_option( 'header_style', 'left' ) ) { ?>
						<div class="et_pb_top_menu_inner">
					<?php } ?>
			<?php }

				if ( true === $show_header_social_icons ) {
					get_template_part( 'includes/social_icons', 'header' );
				}

				et_show_cart_total();
			?>
			<?php if ( false !== et_get_option( 'show_search_icon', true ) || is_customize_preview() ) : ?>
				<?php if ( 'fullscreen' !== et_get_option( 'header_style', 'left' ) ) { ?>
					<div class="clear"></div>
				<?php } ?>
				<form role="search" method="get" class="et-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php
						printf( '<input type="search" class="et-search-field" placeholder="%1$s" value="%2$s" name="s" title="%3$s" />',
							esc_attr__( 'Search &hellip;', 'Divi' ),
							get_search_query(),
							esc_attr__( 'Search for:', 'Divi' )
						);
					?>
					<button type="submit" id="searchsubmit_header"></button>
				</form>
			<?php endif; // true === et_get_option( 'show_search_icon', false ) ?>

			<?php if ( $et_contact_info_defined ) : ?>

				<div id="et-info">
				<?php if ( '' !== ( $et_phone_number = et_get_option( 'phone_number' ) ) ) : ?>
					<span id="et-info-phone"><?php echo et_sanitize_html_input_text( $et_phone_number ); ?></span>
				<?php endif; ?>

				<?php if ( '' !== ( $et_email = et_get_option( 'header_email' ) ) ) : ?>
					<a href="<?php echo esc_attr( 'mailto:' . $et_email ); ?>"><span id="et-info-email"><?php echo esc_html( $et_email ); ?></span></a>
				<?php endif; ?>
				</div> <!-- #et-info -->

			<?php endif; // true === $et_contact_info_defined ?>
			<?php if ( $et_contact_info_defined || true === $show_header_social_icons || false !== et_get_option( 'show_search_icon', true ) || class_exists( 'woocommerce' ) || is_customize_preview() ) { ?>
				<?php if ( 'fullscreen' === et_get_option( 'header_style', 'left' ) ) { ?>
					</div> <!-- .et_pb_top_menu_inner -->
				<?php } ?>

				</div> <!-- .et_slide_menu_top -->
			<?php } ?>

			<div class="et_pb_fullscreen_nav_container">
				<?php
					$slide_nav = '';
					$slide_menu_class = 'et_mobile_menu';

					$slide_nav = wp_nav_menu( array( 'theme_location' => 'primary-menu', 'container' => '', 'fallback_cb' => '', 'echo' => false, 'items_wrap' => '%3$s' ) );
					$slide_nav .= wp_nav_menu( array( 'theme_location' => 'secondary-menu', 'container' => '', 'fallback_cb' => '', 'echo' => false, 'items_wrap' => '%3$s' ) );
				?>

				<ul id="mobile_menu_slide" class="<?php echo esc_attr( $slide_menu_class ); ?>">

				<?php
					if ( '' == $slide_nav ) :
				?>
						<?php if ( 'on' == et_get_option( 'divi_home_link' ) ) { ?>
							<li <?php if ( is_home() ) echo( 'class="current_page_item"' ); ?>><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'Divi' ); ?></a></li>
						<?php }; ?>

						<?php show_page_menu( $slide_menu_class, false, false ); ?>
						<?php show_categories_menu( $slide_menu_class, false ); ?>
				<?php
					else :
						echo( $slide_nav );
					endif;
				?>

				</ul>
			</div>
		</div>
	<?php endif; // true ==== $et_slide_header ?>



	<?php
    if(get_query_var('ref')) {
      $posts = get_posts(array(
        'post_type' => 'affiliate_link',
        'meta_key' => 'param_string',
        'meta_value' => get_query_var('ref'),
        'numberposts' => -1,
      ));

      if(!empty($posts)) {
        $pid = $posts[0]->ID;
        echo '<input type="hidden" id="affiliate-url-param" value="'.$pid.'">';
      }
    }
	?>

		<header id="main-header" class="<?php echo current_user_can('administrator')?'has-admin-bar':''; ?>" data-height-onload="<?php echo esc_attr( et_get_option( 'menu_height', '300' ) ); ?>">
			<?php
				$locations = get_nav_menu_locations();
				$desktop_menu = wp_get_nav_menu_object( $locations['primary-menu']);
				$mobile_menu = wp_get_nav_menu_object( $locations['secondary-menu']);

    		$desktop_menu_html = wp_nav_menu(array(
    			'menu' => $desktop_menu->term_id,
    			'echo' => false,
    		));
			?>
			<nav class="sas-desktop-nav">

				<div class="sas-logo-container">
					<a href="<?php echo get_site_url(); ?>"><img src="<?php echo get_stylesheet_directory_uri() . '/images/logos/shop-and-shout.png'; ?>"></a>
				</div>

				<div class="sas-nav-main">
				<?php echo $desktop_menu_html ?>
				</div>

				<div class="sas-nav-ctas">
					<?php if( is_user_logged_in() ) : ?>

					 <a href="<?php echo get_site_url() . '/my-account/customer-logout'; ?>" class="logout-link">Logout</a> <a href="<?php echo get_site_url() . '/my-account/edit-account'; ?>" class="my-account-link">My Account</a>

					<?php else : ?>


						<div class="sas-nav-dropdown login">
							<span href="<?php echo get_site_url() . '/my-account/'; ?>">Log in</span>
							<div class="sas-nav-dropdown-content">
								<a href="<?php echo get_site_url() . '/my-account/' ?>"><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/hearts/blue-heart-filled.svg'; ?>"> Influencers</a>
								<a href="<?php echo get_site_url() . '/my-account/?b=1'; ?>"><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/hearts/blue-heart-filled.svg'; ?>"> Brands</a>
							</div>
						</div>
						<div class="sas-nav-dropdown join">
							<div class="cta-join">
								<span>Join</span>
							</div>
							<div class="sas-nav-dropdown-content">
								<a href="<?php echo get_site_url() . '/influencer-signup/' ?>"><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/hearts/blue-heart-filled.svg'; ?>"> Join as an Influencer</a>
								<a href="<?php echo get_site_url() . '/brand-signup/'; ?>"><img src="<?php echo get_stylesheet_directory_uri() . '/images/icons/hearts/blue-heart-filled.svg'; ?>"> Join as a Brand</a>
							</div>
						</div>

					<?php endif; ?>
				</div>
			</nav>

			<nav class="sas-mobile-nav">
				<div class="sas-mobile-nav-bar">
					<div id="sas-mobile-nav-toggle" class="sas-mobile-nav__hamburger sas-sidebar-toggle">
						<div></div>
						<div></div>
						<div></div>
					</div>
					<div class="logo">
						<a href="<?php echo get_site_url(); ?>"><img src="<?php echo get_stylesheet_directory_uri() . '/images/logos/shop-and-shout.png'; ?>"></a>
					</div>
					<div class="spacer"></div>
				</div>

				<div id="sas-mobile-nav-sidebar" class="sas-sidebar">
					<div class="sas-mobile-nav-sidebar-tray">
						<div class="sidebar-tray__account">
							<?php if(is_user_logged_in()): 
								$user_id = get_current_user_id();
								$ig_profile_pic_url = get_user_meta($user_id, 'inf_instagram_profile_picture', true);
							?>
								<div class="account">
									<?php if($ig_profile_pic_url && check_remote_file($ig_profile_pic_url)): ?>
										<a href="<?php echo get_site_url() . '/my-account/edit-account/'; ?>"><img class="profile-pic" width="40" height="40" src="<?php echo esc_url($ig_profile_pic_url) ?>"></a>
									<?php endif; ?>
									<a class="my-account" href="<?php echo get_site_url() . '/my-account/edit-account/' ?>">My Account</a>
								</div>
							<?php else: ?><!-- if(is_user_logged_in()) -->
								<div class="signup-login">
									<a href="<?php echo get_site_url() . '/influencer-signup' ?>">Signup</a> / <a href="<?php echo get_site_url() . '/my-account/edit-account/'; ?>">Login</a>
								</div>
							<?php endif; ?><!-- if(is_user_logged_in()) -->
						</div>

						<ul class="sidebar-tray__nav-menu primary">
							<li class="menu-item menu-item-primary">
								<a href="<?php echo get_site_url() . '/shop' ?>"><img class="icon" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/mobile-nav/marketplace.svg' ?>"> <span>Marketplace</span></a>
							</li>

							<li class="menu-item menu-item-primary">
								<a href="<?php echo get_site_url(); ?>"><img class="icon" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/mobile-nav/home.svg' ?>"> <span>Home</span></a>
							</li>
						</ul>

						<ul class="sidebar-tray__nav-menu secondary">
							<li class="menu-item has-children">
								<a class="submenu-header">
									<span class="submenu-header__inner"><img class="icon" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/mobile-nav/for-influencers.svg' ?>"> <span>For Influencers</span></span>
									<img class="arrow" src="<?php echo get_stylesheet_directory_uri() . '/images/arrow.svg' ?>">
								</a>
								<ul class="submenu">
									<li class="submenu-item">
										<a href="<?php echo get_site_url() . '/shop' ?>">Marketplace</a>
									</li>
									<li class="submenu-item">
										<a href="<?php echo get_site_url() . '/young-stars' ?>">Young Stars</a>
									</li>
								</ul>
							</li>

							<li class="menu-item has-children">
								<a class="submenu-header">
									<span class="submenu-header__inner"><img class="icon" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/mobile-nav/for-brands.svg' ?>"> <span>For Brands</span></span>
									<img class="arrow" src="<?php echo get_stylesheet_directory_uri() . '/images/arrow.svg' ?>">
								</a>
								<ul class="submenu">
									<li class="submenu-item">
										<a href="<?php echo get_site_url() . '/brands' ?>">How it Works</a>
									</li>
									<li class="submenu-item">
										<a href="<?php echo get_site_url() . '/about-shoutouts' ?>">ShoutOuts</a>
									</li>
									<li class="submenu-item">
										<a href="<?php echo get_site_url() . '/about-giveaways' ?>">Giveaways</a>
									</li>
									<li class="submenu-item">
										<a href="<?php echo get_site_url() . '/about-missions' ?>">Missions</a>
									</li>
									<li class="submenu-item">
										<a href="<?php echo get_site_url() . '/about-ambassadors' ?>">Ambassador</a>
									</li>
									<li class="submenu-item">
										<a href="<?php echo get_site_url() . '/instagram-boost-info' ?>">Instagram Assistant</a>
									</li>
								</ul>
							</li>

							<li class="menu-item has-children">
								<a class="submenu-header">
									<span class="submenu-header__inner"><img class="icon" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/mobile-nav/about.svg' ?>"> <span>About</span></span>
									<img class="arrow" src="<?php echo get_stylesheet_directory_uri() . '/images/arrow.svg' ?>">
								</a>
								<ul class="submenu">
									<li class="submenu-item">
										<a href="<?php echo get_site_url() . '/about/' ?>">About ShopandShout</a>
									</li>
									<li class="submenu-item">
										<a href="http://blog.shopandshout.com/">Blog</a>
									</li>
									<li class="submenu-item">
										<a href="<?php echo get_site_url() . '/contact/' ?>">Contact Us</a>
									</li>
								</ul>
							</li>
						</ul>
						<?php if(is_user_logged_in()): ?>
							<ul class="sidebar-tray__nav-menu secondary">
								<li class="menu-item menu-item-primary">
									<a href="<?php echo get_site_url() . '/my-account/customer-logout/'; ?>"><img class="icon" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/mobile-nav/logout.svg' ?>"> <span>Logout</span></a>
								</li>
							</ul>
						<?php endif; ?>
					</div>
				</div>
			</nav>
		</header> <!-- #main-header -->
			
		<div id="sas-sidebar-overlay"></div>

		<div id="et-main-area">
