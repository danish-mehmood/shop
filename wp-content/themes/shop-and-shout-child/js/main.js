// Functions
function infSignupCheckCookies( uid ) {

	var referredBy = '';
	var mypassionmedia = 0;

	if(getCookie('sas_referred_by')) {
		var referredBy = getCookie('sas_referred_by');
		console.log('sas_referred_by cookie found');
	}

	if(document.cookie.indexOf('mypassionmedia_signup=') > -1) {
		mypassionmedia = 1;
		console.log('mypassionmedia_signup cookie found');
	}

  var ajax_url = sas_forms_data.sas_ajax_url;

	data = {
		action: 'inf_signup_set_referral',
		uid: uid,
		referred_by: referredBy,
		mypassionmedia: mypassionmedia,
	}

	$.post( ajax_url, data, function(response){
		if(response) {}
	});
}
function brandSignupCheckCookies( uid ) {

		var referredBy = '';

		if(getCookie('sas_referred_by')) {
			var referredBy = getCookie('sas_referred_by');
			console.log('sas_referred_by cookie found');
		}

    var ajax_url = sas_forms_data.sas_ajax_url;

		data = {
			action: 'brand_signup_set_referral',
			uid: uid,
			referred_by: referredBy,
		}

		$.post( ajax_url, data );
}
function getCookie(name) {
    var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    return v ? v[2] : null;
}
function setCookie(name, value, days) {
    var d = new Date;
    d.setTime(d.getTime() + 24*60*60*1000*days);
    document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
}
jQuery( document ).ready( function($) {	
	// Sidebars
	var sidebars = $('.sas-sidebar');
	var sidebarToggles = $('.sas-sidebar-toggle');
	var sidebarOverlay = $('#sas-sidebar-overlay');

	function closeSidebars() {
		sidebars.removeClass('opened');
		sidebarOverlay.removeClass('opened');
		sidebarToggles.removeClass('opened');
		$('html').removeClass('mobile-noscroll');
	}

	function toggleSidebar(sidebar, sidebarToggle) {
		if(sidebar.hasClass('opened')) {
			closeSidebars();
		} else {
			closeSidebars();
			sidebarOverlay.addClass('opened');
			sidebarToggle.addClass('opened');
			sidebar.addClass('opened');
			$('html').addClass('mobile-noscroll');
		}
	}

	// Affiliate/Ambassador
	if( $('#affiliate-url-param').length ) {
		var link = $('#affiliate-url-param').val();
		var cookie = getCookie('sas_referred_by');

		if(!cookie) {
			setCookie('sas_referred_by', link, 3);
			console.log('cookie sas_referred_by: ' + link + ' has been set.');
		} else {
			console.log('cookie sas_referred_by already set');
		}
	}

	var currentURL = new URL(window.location);

	if( currentURL.searchParams.get('ref') && currentURL.searchParams.get('ref') == 'mypassionmedia' ) {

		var script = document.createElement('script');

		script.innerHTML = '!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version="2.0"; n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,"script","https://connect.facebook.net/en_US/fbevents.js");fbq("init", "801435089944213");fbq("track", "PageView");';

		document.head.appendChild(script);

		if(!getCookie('mypassionmedia_signup')) {
			var cookieDate = new Date();
			cookieDate.setDate(cookieDate.getDate() + 1);
			var cookieString = "mypassionmedia_signup=1; expires=" + cookieDate + "; path=/;";
			document.cookie = cookieString;
			console.log('mypassionmedia is set');
		} else {
			console.log('mypassionmedia already set');
		}
	}

	// ------------------------------------
	// -------- IMAGE PREVIEWS ------------
	// ------------------------------------
	$('.image-preview-wrapper').each(function(){
		var fileUpload = $(this).find('input[type="file"]');
		var imagePreviewContainer = $(this).find('.image-preview-container');

		fileUpload.on('change', function(){
		    files = $(this)[0].files;
		    if (files) {
		    	var fileCount = 0
		    	$.each(files, function() {

			        var reader = new FileReader();

			        reader.onload = function (e) {
			            var preview = imagePreviewContainer.find('.image-preview:nth-child(' + fileCount + ')');
			            if(preview.length) {
			            	preview.css('background-image', 'url(' + e.target.result + ')');
			            }
			        }

			        reader.readAsDataURL(this);

			        fileCount++;
		    	});
		    }
		});
	});

	if( $('#sas-welcome-page').length && getCookie('mypassionmedia_signup') ) {
		var script = document.createElement('script');

		script.innerHTML = '!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version="2.0"; n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,"script","https://connect.facebook.net/en_US/fbevents.js");fbq("init", "801435089944213");fbq("track", "PageView");';

		document.head.appendChild(script);
	}

	// Password Toggle Button
	if($('.sas-form').length) {
		$('input[type="password"]').each(function(){
			$('.toggle-password').on("click", function() {
				var input = $(this).parent().find('input');
				var type = input.attr('type');
				if( type === 'password' ) {
					this.innerText = 'Hide';
					input.attr('type', 'text');
				} else {
					this.innerText = 'Show';
					input.attr('type', 'password');
				}
			});
		});
	}

	// Accordions
	if($('.accordion-wrapper').length) {
		$('.accordion-head:not(.disabled)').click(function(){
			$(this).find('.accordion-toggle').toggleClass('toggled');
			$(this).parent().find('.accordion-toggle').not($(this).find('.accordion-toggle')).removeClass('minus');
			$('.accordion-body').not($(this).next()).slideUp();
			$(this).next().slideToggle();
		});
	}

	$('.accordion-body').hide();

	$('.sas-accordion-group .accordion-head:not(.disabled)').live('click', function(){
		var accordionGroup = $(this).parent().parent();
		var accordionBody = $(this).parent().find('.accordion-body');
		var accordionToggle = $(this).find('.accordion-toggle');

		accordionGroup.find('.accordion-toggle').not(accordionToggle).removeClass('toggled');
		accordionGroup.find('.accordion-body').not(accordionBody).slideUp();

		accordionToggle.toggleClass('toggled');
		accordionBody.slideToggle();
	});

	$('.shoutout-order-influencer-more-info .more-info-button').live('click', function(){
		var info = $(this).parent().parent().find('.shoutout-order-info');
		var toggle = $(this).find('.toggle');
		var container = $(this).parent().parent().parent();

		toggle.toggleClass('toggled');
		info.slideToggle();

		container.find('.toggle').not(toggle).removeClass('toggled');
		container.find('.shoutout-order-info').not(info).slideUp();
	});

	// Tabs functionality
	if($(".sas-tabs-container").length) {
		$('.tab-anchor').each(function(){
			var tabID = $(this).data('tab');
			var tab = $('#'+tabID);

			if($(this).attr('selected') != 'selected') {
				tab.hide();
			}
		});

		$('.tab-anchor').on('click', function(){
			$(this).attr('selected', 'selected');
			$('.tab-anchor').not(this).attr('selected', false);
			var tabID = $(this).data('tab');
			var tab = $('#'+tabID);

			var otherTabs = $('.tab').not(tab);

			tab.show();
			tab.attr('selected', 'selected');
			otherTabs.hide();
		});
	}

	$('.ui-tabs-nav a').live('click', function(e){
	    e.preventDefault();
	    e.stopPropagation();
	});

	// ------------------------------------
	// ----------- ANIMATIONS -------------
	// ------------------------------------

	if($('#hero-typed-desktop').length) {
		var homeHeroTyped = new Typed('#hero-typed-desktop .typed span', {
		  strings: ["Experiences", "Products", "Services"],
		  typeSpeed: 50,
		  backSpeed: 25,
		  backDelay: 1700,
		  loop: true,
		});
		var homeHeroTyped = new Typed('#hero-typed-mobile .typed span', {
		  strings: ["Experiences", "Products", "Services"],
		  typeSpeed: 50,
		  backSpeed: 25,
		  backDelay: 1700,
		  loop: true,
		});
	}

	if($('.social-listening-graphic').length) {

		$(window).on('scroll', function(){
			if( ! $('.social-listening-graphic').hasClass('animate') ) {
				var verticalPosition = $('.social-listening-graphic').offset().top;
				var scrollPosition = $(window).scrollTop();

				if( scrollPosition + 500 >= verticalPosition ) {
					$('.social-listening-graphic').addClass('animate');
				}
			}
		});
	}

	if($('.instagram-boost-graphic').length) {

		$(window).on('scroll', function(){
			if( ! $('.instagram-boost-graphic').hasClass('animate') ) {
				var verticalPosition = $('.instagram-boost-graphic').offset().top;
				var scrollPosition = $(window).scrollTop();

				if( scrollPosition + 500 >= verticalPosition ) {
					$('.instagram-boost-graphic').addClass('animate');
				}
			}
		});
	}

	// -----------------------------------
	// ----------- Mobile Nav ------------
	// -----------------------------------

	$('#sas-mobile-nav-toggle').click(function(){
		toggleSidebar($('#sas-mobile-nav-sidebar'), $(this));
	});

	$('#sas-sidebar-overlay').click(function(){
		closeSidebars();
	});

	$('#sas-mobile-nav-sidebar .menu-item.has-children').each(function(){

		var thisAnchor = $(this).find('.submenu-header');
		var thisMenu = $(this).find('.submenu');
		var notThisAnchor = $('#sas-mobile-nav-sidebar .menu-item.has-children .submenu-header').not(thisAnchor);
		var notThisMenu = $('#sas-mobile-nav-sidebar .submenu').not(thisMenu);

		thisAnchor.on('click', function(){
			notThisAnchor.removeClass('menu-open');
			notThisMenu.slideUp();
			thisAnchor.toggleClass('menu-open');
			thisMenu.slideToggle();
		});
	});

	// -----------------------------------
	// --------- Shop Sidebars -----------
	// -----------------------------------

	$('#shop-filters-toggle').click(function(){
		toggleSidebar($('#shop-filters-sidebar'), $(this));
	});

	$('#shop-sort-toggle').click(function(){
		toggleSidebar($('#shop-sort-sidebar'), $(this));
	});

	// ----------------------------- //
	// ---- Shop Functionality ----- //
	// ----------------------------- //
	if($('.shop-wrapper').length) {
		(function() {
			var shopURL = $('#shop-url').val();

			// Filters
			var selectedInstagramReach = $('#selected-instagram-reach').val();
			var selectedInstagramEngagement = $('#selected-instagram-engagement').val();
			var selectedCampaignStrategies = $('#selected-campaign-strategies').val();
			var selectedInterests = $('#selected-interests').val();
			var selectedCountry = $('#selected-country').val();
			var selectedChannel = $('#selected-channel').val()?$('#selected-channel').val():'instagram';
			var selectedPaidCampaign = $('#selected-paid-campaign').val()?1:0;
			
			// Sorting
			var selectedSortBy = $('#selected-sortby').val();
			var selectedOrder = $('#selected-order').val();

			// Infinite Scroll
			var preventInfiniteScroll = false;
			var currentScrollPage = 1;
			var currentMaxPages = $('#shop-max-pages').val();

			if(currentMaxPages > currentScrollPage) {
				$('#shop-infinite-scroller').show();
			}

			$('.no-js-element').hide();
			$('.no-js').removeClass('no-js');

			// Campaign strategy cards
			$('.campaign-strategies-container .campaign-strategy').on('click', function(){
				var strategy = $(this).data('strategy');
				selectedCampaignStrategies = strategy;
				ajaxUpdateShop();
			});
			
			// Filter Form
			$('.filter-form-item.js-accordion').each(function(){
				$(this).addClass('accordion');

				var formItemHeader = $(this).find('.form-item__header');
				var formItemInput = $(this).find('.form-item__input');

				formItemInput.hide();

				$(this).find('.form-item__header').on('click', function(){
					formItemHeader.toggleClass('opened');
					formItemInput.slideToggle();
				});
			});

			var filterSubmitTimeout;
			$('#shop-filter-form input').on('change', function(){
				clearTimeout(filterSubmitTimeout);
				filterSubmitTimeout = setTimeout(function(){
					$('#shop-filter-form').submit();
				}, 200);
			});

			$('#shop-filter-form').validate({
				submitHandler: function(form) {
					if($.preventDefault) {
						$.preventDefault();
					} else {
						$.returnValue = false;
					}
					// Collect data from inputs
					selectedInstagramReach = $('#shop-filter-reach').val();
					selectedInstagramEngagement = $('#shop-filter-engagement').val();
					selectedCampaignStrategies = [];
					$('.filter-form-campaign-types input[name="campaign-types[]"]:checked').each(function(){
						selectedCampaignStrategies.push($(this).val());
					});
					selectedCampaignStrategies = selectedCampaignStrategies.join();
					selectedInterests = [];
					$('.filter-form-interests input[name="interests[]"]:checked').each(function(){
						selectedInterests.push($(this).val());
					});
					selectedInterests = selectedInterests.join();
					selectedCountry = $('.filter-form-countries input[name="countries[]"]:checked').val();
					selectedPaidCampaign = $('#paid-campaign:checked').val()?1:0;

					// TODO: SELECTED CHANNEL
					selectedChannel = selectedChannel;

	      	ajaxUpdateShop();
				}
			});

			// Sort Form
			$('#shop-sort-form input').on('change', function(){
				$('#shop-sort-form').submit();
			});

			// Sort Form
			$('#shop-sort-form').validate({
				submitHandler: function(form) {
					if($.preventDefault) {
						$.preventDefault();
					} else {
						$.returnValue = false;
					}

					selectedSortBy = $(form).find('input:checked').data('sortby');
					selectedOrder = $(form).find('input:checked').data('order');

	      	ajaxUpdateShop();
				}
			});

			// Sort Select
			$('#shop-sort-select').select2({
				minimumResultsForSearch: -1
			});
			$('#shop-sort-select').on('change', function(){

				selected = $(this).find('option:selected');
				selectedSortBy = selected.data('sortby');
				selectedOrder = selected.data('order');

	      ajaxUpdateShop();
			});

			// Infinite Scrolling
			$(window).on('scroll', function(){
				var scrollPosition = document.documentElement.scrollTop;
				var loaderPosition = $('#shop-infinite-scroller').offset().top;

				if(scrollPosition >= (loaderPosition - 1200) && !preventInfiniteScroll && currentMaxPages > currentScrollPage) {
					ajaxUpdateShop(currentScrollPage + 1);
				}
			});

			function ajaxUpdateShop(page = 1) {
				// Giving shop content a random request ID so old requests don't overwrite new ones 
				// and so the shop doesn't update until the latest request has loaded
				var shopAjaxRequestID = Math.round(Math.random()*100000);

				preventInfiniteScroll = true;

				$('#shop-infinite-scroller').show();
				
				$('#shop-campaigns').attr('data-current-ajax-request-id', shopAjaxRequestID);

				if (page == 1) {
	      	$('#shop-campaigns').addClass('loading');
				}

	     	var ajax_url = sas_forms_data.sas_ajax_url;

	     	var data = {}

	     	if(selectedChannel && selectedChannel !== 'instagram')
	     		data.channel = selectedChannel;
	     	if(selectedInstagramReach)
	     		data.instagram_reach = selectedInstagramReach;
	     	if(selectedInstagramEngagement)
	     		data.instagram_engagement_rate = selectedInstagramEngagement;
	     	if(selectedCampaignStrategies)
	     		data.campaign_strategies = selectedCampaignStrategies;
	     	if(selectedInterests)
	     		data.interests = selectedInterests;
	     	if(selectedCountry)
	     		data.country = selectedCountry;
	     	if(selectedPaidCampaign)
	     		data.paid_campaign = 1;
	     	if(selectedSortBy)
	     		data.sortby = selectedSortBy;
	     	if(selectedOrder)
	     		data.order = selectedOrder;

	     	console.log(data);

	      var queryString = Object.keys(data).map(key => key + '=' + data[key]).join('&');
	      var newURL = queryString?shopURL+'?'+queryString:shopURL;

	      if(window.history.replaceState) {
	      	window.history.replaceState('shop', 'Campaign Marketplace', newURL);
	      } else {
	      	window.history.pushState('shop', 'Campaign Marketplace', newURL);
	      }

	      data.action = 'sort_filter_shop';
	      data.page = page;

	      $.post( ajax_url, data, function(response) {
	      	response = JSON.parse(response);

	      	preventInfiniteScroll = false;
	      	
	      	if(response.content) {
	      		if(page == 1) {
	      			$('#shop-campaigns[data-current-ajax-request-id="'+shopAjaxRequestID+'"]').html('');
	      		}
	      		$('#shop-campaigns[data-current-ajax-request-id="'+shopAjaxRequestID+'"]').removeClass('loading');
      			$('#shop-campaigns[data-current-ajax-request-id="'+shopAjaxRequestID+'"]').append(response.content);

      			currentMaxPages = response.maxPages;
      			currentScrollPage = page;

      			if(currentMaxPages > currentScrollPage) {
							$('#shop-infinite-scroller').show();
      			} else {
      				$('#shop-infinite-scroller').hide();
      			}
	      	}
	      });

			}
		})();
	}

	// ----------------------------- //
	// ------ Product Display ------ //
	// ----------------------------- //
	$('.product-image-slider').each(function(){
		var slider = $(this).find('.slider');
		var sliderNav = $(this).find('.slider-nav');
		sliderNav.show();

		slider.slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			asNavFor: sliderNav,
		});
		sliderNav.slick({
			slidesToShow: 3,
			slidesToScroll: 1,
			asNavFor: slider,
			arrows: false,
			centerMode: true,
			focusOnSelect: true,
		})
	});
	$('#inspiration-slider').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		auto: true,
		arrows: true,
	});
	$('.wc-notices-show').addClass('has-js');
	$('.wc-notices-wrapper').addClass('has-js');
	$('.wc-notice-overlay').on('click', function(){
		$('.wc-notices-wrapper').hide();
	});
	$('.wc-notices-wrapper .close-message .close').on('click', function(){
		$('.wc-notices-wrapper').hide();
	});
	$('.wc-notices-show .show').on('click', function(){
		$('.wc-notices-wrapper').show();
	});

	// ----------------------------------- //
	// ---- Instagram Boost Info Page ---- //
	// ----------------------------------- //
	if($('.real-followers-percentage-increase').length) {

		$(window).on('scroll', function(){
			if( ! $('.real-followers-percentage-increase').hasClass('animate') ) {
				var verticalPosition = $('.real-followers-percentage-increase').offset().top;
				var scrollPosition = $(window).scrollTop();

				if( scrollPosition + 500 >= verticalPosition ) {
					$('.real-followers-percentage-increase').addClass('animate');
					var following = $('.real-followers-percentage-increase').find('.increase-following .numerator .number');
					var engagement = $('.real-followers-percentage-increase').find('.increase-engagement .numerator .number');

					following.prop('Counter', 0.0).animate({
						counter: 20.0
					}, {
						duration: 2000,
						easing: 'swing',
						step: function(now) {
							$(this).text(now.toFixed(1));
						}
					});

					engagement.prop('Counter', 0.0).animate({
						counter: 2.00
					}, {
						duration: 2000,
						easing: 'swing',
						step: function(now) {
							$(this).text(now.toFixed(2));
						}
					});
				}
			}
		});
	}

	if($('.follower-line-graph').length) {

		$(window).on('scroll', function(){
			if( ! $('.follower-line-graph').hasClass('animate') ) {
				var verticalPosition = $('.follower-line-graph').offset().top;
				var scrollPosition = $(window).scrollTop();

				if( scrollPosition + 500 >= verticalPosition ) {
					$('.follower-line-graph').addClass('animate');

					var graphCounter = $('.follower-line-graph').find('.graph-outer .counter');
					var startNum = 1240;
					var endNum = 9360;

					graphCounter.prop('Counter', 0).animate({
						counter: (endNum - startNum)
					}, {
						duration: 4500,
						easing: 'swing',
						step: function(now) {
							$(this).text(Math.round((now + startNum )).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))
						}
					});
				}
			}
		});
	}

	if($('.highlight-card-wrapper').length) {

		$(window).on('scroll', function(){
			if( ! $('.highlight-card-wrapper').hasClass('animate') ) {
				var verticalPosition = $('.highlight-card-wrapper').offset().top;
				var scrollPosition = $(window).scrollTop();

				if( scrollPosition + 600 >= verticalPosition ) {
					$('.highlight-card-wrapper').addClass('animate');
				}
			}
		});
	}

	if($('.elite-preview-cards').length) {

		$('.meet-elite-desktop .elite-preview-cards').find('.preview-card').each(function(){
			$(this).click(function(){
				$(this).parent().find('.selected').removeClass('selected');
				$(this).addClass('selected');
				var inf = $(this).data('influencer');

				$('.elite-info-trays').find('.info-tray').each(function(){
					if( $(this).data('influencer') == inf ) {
						$(this).addClass('selected');
					} else {
						$(this).removeClass('selected');
					}
				});
			});
		});

		$('.meet-elite-mobile').find('.preview-card').each(function(){
			$(this).click(function(){
				$(this).parent().find('.preview-card.selected').not($(this)).removeClass('selected');
				$(this).toggleClass('selected');

				var inf = $(this).data('influencer');

				$('.meet-elite-mobile').find('.info-tray').each(function(){
					if( $(this).data('influencer') == inf ) {
						$(this).slideToggle();
					} else {
						$(this).slideUp();
					}
				});
			});
		});
	}

	// ------------------------------------
	// -------- Influencer Account --------
	// ------------------------------------

	$('.shoutout-link-submission-container').each(function(){
		var form = $(this).find('.shoutout-submission-form');
		var confirmCampaignReceived = $(this).find('.confirm-campaign-received');
		var openButton = $(confirmCampaignReceived).find('button');

		openButton.click(function(){
			confirmCampaignReceived.hide();
			form.show();
		});
	});

	$('.nudge-brand-container').each(function(){

		var form = $(this).find('.nudge-brand-form');
		var openButton = $(this).find('.nudge-brand-button');
		var cancelButton = $(this).find('.cancel-nudge');

		openButton.click(function(){
			openButton.hide();
			form.show();
		});

		$(this).find('.cancel-nudge').click(function(){
			openButton.show();
			form.hide();
		});
	});

	if($('#affiliate-data-container').length) {
		var affiliateLinkSelect = $('select#affiliate-link-select');
		var selectedLink = affiliateLinkSelect.find('option:selected').val();
		generateAffiliateData(selectedLink);

		affiliateLinkSelect.on('change', function(){
			var selectedLink = affiliateLinkSelect.find('option:selected').val();
			generateAffiliateData(selectedLink);
		});
	}

	function generateAffiliateData(linkID) {

		var ajax_url = sas_forms_data.sas_ajax_url;

		$('#affiliate-data-container').hide();
		$('.affiliate-data-placeholder').show();

    	$.post( ajax_url, {action: 'generate_affiliate_data', link_id: linkID }, function(response){
    		if(response) {
        		response = JSON.parse(response);
    			if(!response.errors) {
    				$('.affiliate-data-placeholder').hide();
    				$('#affiliate-data-container').html(response.content);
					$('#affiliate-data-container').show();
    			} else {

    			}
    		}
    	});
	}

	$('#affiliate-data-container').on('click', '#affiliate-payout-info-show', function(){
		$('#affiliate-payout-info').show();
		$('#affiliate-payout-info-show').hide();
	}).on('click', '#affiliate-payout-info-close', function(){
		$('#affiliate-payout-info').hide();
		$('#affiliate-payout-info-show').show();
	});

	// -----------------------------------
	// ---------- Brand Account ----------
	// -----------------------------------

	// Brand Dashboard
	if($('#brand-dashboard').length) {
		(function(){
			var ajax_url = sas_forms_data.sas_ajax_url;
			var siteUrl = $('#site-url').val();
			var selectedBrand = $('#selected-brand').val();
			var currentSection = $('#current-section').val()?$('#current-section').val():'campaigns';
			var dashboardLoading = $('#dashboard-loading');
			var campaignLoadingCompletion = {
				'analytics': false,
				'orders' : false,
			};

			loadBrandDashboard(selectedBrand);

			// Sidebar
			$('.sidebar__toggle').click(function(){
				$('#dashboard-sidebar').toggleClass('show');
			});

			$('#sidebar-brand-select').change(function(){
				selectedBrand = $(this).val();

				if(selectedBrand) {
					loadBrandDashboard(selectedBrand);
				}
			});
			$('#sidebar-brand-select.admin').select2();
			$('#sidebar-brand-select:not(.admin)').select2({
				minimumResultsForSearch: -1
			});

			$('.sidebar__nav-item').click(function(){
				var navItem = $(this).data('nav-item');
				var newUrl = siteUrl+'/brand-account/'+navItem;

				window.history.pushState({path:newUrl},'',newUrl); 
				currentSection = navItem;

				$('.sidebar__nav-item').removeClass('selected');
				$(this).addClass('selected');
				$('.dashboard-section').removeClass('selected');
				$('.dashboard-section[data-dashboard-section="'+navItem+'"]').addClass('selected');
			});

			// Dashboard Content
			function loadBrandDashboard(brandID) {

				data = {
					action: 'load_brand_dashboard',
					brand_id: brandID,
					current_section: currentSection,
				}

				$('#brand-dashboard .dashboard-content').hide();
				dashboardLoading.show();

				$.post( ajax_url, data, function(response) {

					response = JSON.parse(response);

					if(response.content) {
						dashboardLoading.hide();
						$('#brand-dashboard .dashboard-content').show();
						$('#brand-dashboard .dashboard-content').html(response.content);

						$('[data-toggle="tooltip"]').tooltip();

						// Campaigns
						var primaryCampaign = $('#campaign-select').val();
						var dateRange = false;
						if(primaryCampaign) {
							loadCampaignInfo(primaryCampaign, dateRange);
						}

						$('#brand-dashboard #campaign-select').change(function(){
							primaryCampaign = $(this).val();

							loadCampaignInfo(primaryCampaign, dateRange);
						});

						$('#timeline-type-select').change(function(){
							var timelineType = $(this).val();

							if(timelineType == 'range') {
								$('.timeline-range-container').show();
							} else {
								$('.timeline-range-container').hide();
								loadCampaignAnalytics(primaryCampaign, false);
							}
						}).select2({
							minimumResultsForSearch: -1
						});

					  $( function() {
					    var dateFormat = "mm/dd/yy"
					    var from = $( "#date-range-from" )
				        .datepicker({
				          changeMonth: true,
				          dateFormat: dateFormat,
				        })
				        .on( "change", function() {
				          to.datepicker( "option", "minDate", getDate( this ) );
				          
				          if($(this).val().length && $('#date-range-to').val().length) {
				          	$('#date-range-filter').removeAttr('disabled');
				          } else {
				          	$('#date-range-filter').attr('disabled', 'disabled');
				          }
				        });
				      var to = $( "#date-range-to" )
				      	.datepicker({
					        changeMonth: true,
				          dateFormat: dateFormat,
					      })
					      .on( "change", function() {
					        from.datepicker( "option", "maxDate", getDate( this ) );
				          
				          if($(this).val().length && $('#date-range-from').val().length) {
				          	$('#date-range-filter').removeAttr('disabled');
				          } else {
				          	$('#date-range-filter').attr('disabled', 'disabled');
				          }
					      });
					 
					    function getDate( element ) {
					      var date;
					      try {
					        date = $.datepicker.parseDate( dateFormat, element.value );
					      } catch( error ) {
					        date = null;
					      }
					 
					      return date;
					    }
					  } );

						$('#date-range-filter').click(function(){
							dateRange = {
								'from': $('#date-range-from').val(),
								'to': $('#date-range-to').val(),
							};
							loadCampaignAnalytics(primaryCampaign, dateRange);
						});

						// Account Settings
						$("#brand-info-form").validate(brandInfoFormValidation);
						$('#brand-user-invite').validate(brandUserInviteValidation);
						$('#brand-user-invite #role').select2({
							minimumResultsForSearch: -1
						});
						$('#brand-account-info-form').validate(brandAccountInfoFormValidation);
					} else {
						console.error('response.content is empty check load_brand_dashboard ajax function');
					}
				});
			}

			function loadCampaignInfo(campaignID) {
				// Edit button
				var edit = $('#edit-campaign-button');
				edit.removeAttr('disabled');
				edit.attr('href', siteUrl+'/design-campaign/?b='+selectedBrand+'&c='+campaignID);
				
				loadCampaignAnalytics(campaignID, false);
				loadCampaignOrders(campaignID);
			}

			function loadCampaignAnalytics(campaignID, dateRange) {

				// Adding a requestID attribute to the campaign container so that older 
				// requests don't overwrite new requests/load in while new request is loading
				var requestID = Math.floor(Math.random() * 100000);
				var analyticsContainer = $('#single-campaign .campaign-content .campaign-analytics');
				
				analyticsContainer.attr('data-request-id', requestID);
				analyticsContainer.hide();
				campaignLoadingCompletion['analytics'] = false;
				checkCampaignLoadingCompletion();

				data = {
					action: 'load_campaign_analytics',
					campaign_id: campaignID,
					date_range: dateRange?dateRange:0,
				}

				$.post( ajax_url, data, function(response) {

					response = JSON.parse(response);

					if(response.content) {
						analyticsContainer = $('#single-campaign .campaign-content .campaign-analytics[data-request-id="'+requestID+'"]');

						analyticsContainer.html(response.content);
						analyticsContainer.show();

						campaignLoadingCompletion['analytics'] = true;
						checkCampaignLoadingCompletion();

						$('[data-toggle="tooltip"]').tooltip();

						// Counter animations
						var additionalTime = 0;
						analyticsContainer.find('.grid-card .number-metric .primary-metric').each(function(){
							var value = $(this).data('value');
							var decimals = $(this).data('decimals');
							var prepend = $(this).data('prepend');
							var append = $(this).data('append');

							$(this).prop('Counter', 0).animate({
								counter: $(this).data('value')
							},{
								duration: (1000+additionalTime),
								easing: 'swing',
								step: function(now) {
									var decimalNum = now.toFixed(decimals);
											numParts = decimalNum.toString().split(".");
									    numParts[0] = numParts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
									    commaNum = numParts.join(".");
											commaNum = prepend?prepend+commaNum:commaNum;
											commaNum = append?commaNum+append:commaNum;

									$(this).text(commaNum);
								}
							});

							additionalTime-=100;
						});

						//Campaign Goal
						var period = $('#goal-period-select').val();
						loadCampaignGoal(campaignID, period);
						$('#goal-period-select').change(function(){
							loadCampaignGoal(campaignID, $(this).val());
						}).select2({
							minimumResultsForSearch: -1,
							width: '110px',
						});
					} else {
						console.error('response.content is empty check load_campaign_analytics ajax function');
					}
				});

			}

			function loadCampaignGoal(campaignID, period) {

				data = {
					action: 'load_campaign_goal',
					campaign_id: campaignID,
					period: period,
				}

				$('#goal-progress-metric .goal-loading').show();
				$('#goal-progress-metric .goal-content').hide();

				$.post( ajax_url, data, function(response) {

					response = JSON.parse(response);

					if(response.content) {
						$('#goal-progress-metric .goal-loading').hide();
						$('#goal-progress-metric .goal-content').html(response.content);
						$('#goal-progress-metric .goal-content').css('display', 'flex');

						// Counter animation
						$('#goal-progress-metric .primary-metric .percentage-metric').prop('Counter', 0).animate({
							counter: response.percentage,
						},{
							duration: (1000),
							easing: 'swing',
							step: function(now) {
								$(this).text((response.percentage>100?now.toFixed(0):now.toFixed(1))+'%');
							}
						});
						// Progress bar
						var barPercentage = response.percentage > 100 ? 100 : response.percentage;
						$('#goal-progress-metric .goal-content .progress-bar-inner').css('width', barPercentage+'%');
						if(barPercentage == 100) {
							$('#goal-progress-metric .goal-content .progress-bar-inner').addClass('full');
						}
					} else {
						console.error('response.content is empty check load_campaign_goal ajax function');
					}
				});
			}

			function loadCampaignOrders(campaignID) {

				// Adding a requestID attribute to the campaign container so that older 
				// requests don't overwrite new requests/load in while new request is loading
				var requestID = Math.floor(Math.random() * 100000);
				var ordersContainer = $('#single-campaign .campaign-content .campaign-orders');
				ordersContainer.attr('data-request-id', requestID);
				ordersContainer.hide();
				campaignLoadingCompletion['orders'] = false;
				checkCampaignLoadingCompletion();

				data = {
					action: 'load_campaign_orders',
					campaign_id: campaignID,
				}

				$.post( ajax_url, data, function(response) {

					response = JSON.parse(response);

					if(response.content) {
						ordersContainer = $('#single-campaign .campaign-content .campaign-orders[data-request-id="'+requestID+'"]');
						
						ordersContainer.html(response.content);
						
						// Form Validation
						// Brand ShoutOut tracking link forms
						ordersContainer.find('.tracking-link-form').each(function() {$(this).validate(trackingLinkFormValidation);});
						// Brand ShoutOut redemption code forms
						ordersContainer.find('.web-redemption-code-form').each(function() {$(this).validate(webRedemptionCodeFormValidation);});
						// Shoutout Error Forms
						shoutoutErrorFunctionality();
						ordersContainer.find('.shoutout-error-form').each(function(){$(this).validate(shoutoutErrorFormValidation);});
						// Brand ShoutOut rating forms
						ratingFormFunctionality();
						ordersContainer.find('.rating-form').each(function(){$(this).validate(ratingFormValidation);});

						ordersContainer.show();
						ordersContainer.find('.campaign-orders-table').tablesorter({
							sortList: [[2,1]]
						});
						
						campaignLoadingCompletion['orders'] = true;
						checkCampaignLoadingCompletion();
					} else {
						console.error('response.content is empty check load_campaign_orders ajax function');
					}
				});
			}

			function checkCampaignLoadingCompletion() {
				var allComplete = true;
				for(var key in campaignLoadingCompletion) {
					if(!campaignLoadingCompletion[key]) {
						allComplete = false;
					}
				}
				if(allComplete) {
					$('#campaign-loading').hide();
				} else {
					$('#campaign-loading').show();
				}
			}

		})();
	}

	var brandUserInviteValidation = {
		rules: {
			email: {
				email: true,
			}
		},
		submitHandler:function(form) {
			if($.preventDefault) {
				$.preventDefault();
			} else {
				$.returnValue = false;
			}

			var formErrors = $(form).find('.form-errors');
			var submit = $(form).find('button[type="submit"]');
			var successMessage = $(form).find('.success-message');

			formErrors.hide();
			formErrors.empty();
			successMessage.hide();

			submit.html('Sending  <i class="fa fa-spinner fa-spin"></i>');

			var brandID = $(form).find('#brand-id').val();
			var email = $(form).find('#email').val();
			var role = $(form).find('#role option:selected').val();

			var ajax_url = sas_forms_data.sas_ajax_url;

			data = {
				action: 'brand_user_invite',
				brand_id: brandID,
				email: email,
				role: role,
			}

			$.post( ajax_url, data, function(response) {

				submit.html('Save');

				response = JSON.parse(response);

				if(response.errors.length == 0) {
					submit.html('Invite');
					$(form).find('#email').val('');
					successMessage.html('<span>Invite sent to <b>' + email + '</b></span>');
					successMessage.show();
				} else {
					response.errors.forEach(function(element) {
						formErrors.append(element + '<br>');
					});

					formErrors.show();
				}
			});
		}
	}

	var brandInfoFormValidation = {
		rules: {
			brand_website: {
				validUrl: true,
			}
		},
		submitHandler:function(form) {
			if($.preventDefault) {
				$.preventDefault();
			} else {
				$.returnValue = false;
			}

			var formErrors = $(form).find('.form-errors');
			var submit = $(form).find('button[type="submit"]');

			formErrors.hide();
			formErrors.empty();

			submit.html('Saving  <i class="fa fa-spinner fa-spin"></i>');

			var brandID = $('#brand-id').val();
			var brandName = $('#brand-name').val();
			var brandWebsite = $('#brand-website').val();
			var brandStory = $('#brand-story').val();

			var ajax_url = sas_forms_data.sas_ajax_url;

			data = {
				action: 'edit_brand',
				brand_id: brandID,
				brand_name: brandName,
				brand_website: brandWebsite,
				brand_story: brandStory,
			}

			$.post( ajax_url, data, function(response) {

				submit.html('Save');

				response = JSON.parse(response);

				if(response.errors.length == 0) {
					submit.html('Saved');
				} else {
					response.errors.forEach(function(element) {
						formErrors.append(element + '<br>');
					});

					formErrors.show();
					$.scrollTo(0);
				}
			});
		}
	}

	var brandAccountInfoFormValidation = {
		rules: {
			phone: {
				phone: true,
			},
			email: {
				email: true,
			}
		},
		submitHandler:function(form) {
			if($.preventDefault) {
				$.preventDefault();
			} else {
				$.returnValue = false;
			}

			var formErrors = $(form).find('.form-errors');
			var submit = $(form).find('button[type="submit"]');

			formErrors.hide();
			formErrors.empty();

			submit.html('Saving <i class="fa fa-spinner fa-spin"></i>');

			var firstName = $('#contact-first-name').val();
			var lastName = $('#contact-last-name').val();
			var email = $('#contact-email').val();
			var phone = $('#contact-phone').val();
			var emailOptOut = $('#email-opt-out').is(':checked')?1:0;
			var nonce = $('#brand_account_info_nonce').val();

			var ajax_url = sas_forms_data.sas_ajax_url;

			data = {
				action: 'brand_account_info',
				nonce: nonce,
				first_name: firstName,
				last_name: lastName,
				email: email,
				phone: phone,
				email_opt_out: emailOptOut,
			}

			$.post( ajax_url, data, function(response) {

				submit.html('Save');

				response = JSON.parse(response);

				if(response.errors.length == 0) {
					submit.html('Saved')
				} else {
					response.errors.forEach(function(element) {
						formErrors.append(element + '<br>');
					});

					formErrors.show();
				}
			});
		}
	}

	var trackingLinkFormValidation = {
		rules: {
			tracking_link: {
				validUrl: true,
			},
		},
		submitHandler: function(form) {
			if($.preventDefault) {
				$.preventDefault();
			} else {
				$.returnValue = false;
			}

			// Collect data from inputs
			var tracking_link = $(form).find('.tracking_link').val();
			var shoutout = $(form).find('input[name="shoutout"]').val();
			var statusIcon = $(form).parent().parent().find('.status-icon');

			statusIcon.animate({
				right: '40px',
				opacity: 0,
			});

			/**
	         * AJAX URL to send data
	         * (from localize_script)
	         */
	        var ajax_url = sas_forms_data.sas_ajax_url;

	        // Data to send
	        data = {
	         	action: 'submit_shoutout_tracking_link',
	         	tracking_link: tracking_link,
	         	shoutout: shoutout,
	        };

	        //add code to display loading spinner
	        $(form).find('button').html('<i class="fa fa-spinner fa-spin"></i>');

	        //Do Ajax request
	        $.post( ajax_url, data, function(response) {
	        	$(form).find('button').html('Saved');
	        	$(form).find('button').css('background-color', 'green');
	        	if( response ) {
	         		$(form).find('.result-message').html( response );
	         		$(form).find('.result-message').addClass('alert-danger');
	         		$(form).find('.result-message').show();
		        }
	        });
		}
	};

	var webRedemptionCodeFormValidation = {
		submitHandler: function(form) {
			if($.preventDefault) {
				$.preventDefault();
			} else {
				$.returnValue = false;
			}

			// Collect data from inputs
			var code = $(form).find('.code').val();
			var shoutout = $(form).find('input[name="shoutout"]').val();

			/**
	         * AJAX URL to send data
	         * (from localize_script)
	         */
	        var ajax_url = sas_forms_data.sas_ajax_url;

	        // Data to send
	        data = {
	         	action: 'submit_shoutout_web_redemption_code',
	         	code: code,
	         	shoutout: shoutout,
	        };

	        //add code to display loading spinner
	        $(form).find('button').html('<i class="fa fa-spinner fa-spin"></i>');

	        //Do Ajax request
	        $.post( ajax_url, data, function(response) {
	        	$(form).find('button').html('Saved');
	        	$(form).find('button').css('background-color', 'green');
	        	if( response ) {
	         		$(form).find('.result-message').html( response );
	         		$(form).find('.result-message').addClass('alert-danger');
	         		$(form).find('.result-message').show();
		        }
	        });
		}
	};

	// Shoutout Error
	function shoutoutErrorFunctionality() {

		$('.brand-shoutout-error').each(function(){

			var errorFormContainer = $(this).parent().parent().find('.shoutout-error-form-container');

			$(this).click(function(){

				errorFormContainer.show();

				$(this).parent().hide();
			});
		});
	}
	var shoutoutErrorFormValidation = {
		submitHandler: function(form) {
			if($.preventDefault) {
				$.preventDefault();
			} else {
				$.returnValue = false;
			}

			// Collect data from inputs
			var order = $(form).find('input[name="order"]').val();
			var channel = $(form).find('input[name="channel"]').val();
			var report = $(form).find('textarea').val();

			/**
	         * AJAX URL to send data
	         * (from localize_script)
	         */
	        var ajax_url = sas_forms_data.sas_ajax_url;

	        // Data to send
	        data = {
	         	action: 'shoutout_error',
	         	order: order,
	         	channel: channel,
	         	report: report,
	        };

	        //add code to display loading spinner
	        $(form).find('button').html('Sending <i class="fa fa-spinner fa-spin"></i>');

	        //Do Ajax request
	        $.post( ajax_url, data, function(response) {
	        	if( response ) {
	        		if( response == 0 ) {
			        	$(form).find('button').html('Sent');
			        	$(form).find('button').css('background-color', 'green');
	        		} else {
		         		$(form).find('.result-message').html( response );
		         		$(form).find('.result-message').addClass('alert-danger');
		         		$(form).find('.result-message').show();
		         		$(form).find('button').html('Send');
	        		}
		        }
	        });
		}
	};

	//Brand Heart ratings
	function ratingFormFunctionality() {

		// Re Scoring
		$('.shoutout-final-score-container').each(function(){
			var reScoreContainer = $(this).parent().find('.shoutout-re-score-container');
			$(this).find('.brand-re-score-button').click(function(){
				$(this).parent().hide();
				reScoreContainer.show();
			});
		});

		// Scoring
		$('.rating-form').each(function() {
			var ratingNumber = $(this).find('input[name="rating"]');
			$(this).find('.hearts .heart').each(function(e){
				$(this).parent().children('li.heart').each(function(e){
					if( e < ratingNumber.val() ) {
						$(this).find('.full').show();
						$(this).find('.empty').hide();
					} else {
						$(this).find('.full').hide();
						$(this).find('.empty').show();
					}
				});
			});
			$(this).find('.hearts .heart').on('mouseover', function() {
				var onHeart = parseInt($(this).data('value'),10);

				$(this).parent().children('li.heart').each(function(e){
					if( e < onHeart ) {
						$(this).find('.full').show();
						$(this).find('.empty').hide();
					} else {
						$(this).find('.full').hide();
						$(this).find('.empty').show();
					}
				});
			}).on('mouseout', function() {
				$(this).parent().children('li.heart').each(function(e) {
					if( e < ratingNumber.val() ) {
						$(this).find('.full').show();
						$(this).find('.empty').hide();
					} else {
						$(this).find('.full').hide();
						$(this).find('.empty').show();
					}
				});
			}).on('click', function() {
				var statusIcon = $(this).parent().parent().parent().parent().parent().find('.status-icon');
				var rating = parseInt($(this).data('value'),10);
				var heartSize;

				if( rating < 3 ) {
					heartSize = '10px';
				} else if( rating < 5 ) {
					heartSize = '25px';
				} else {
					heartSize = '35px';
				}

				statusIcon.animate({
					width: heartSize,
				}).delay(0);

				ratingNumber.val(rating);

			});
		});
	}
	var ratingFormValidation = {
		submitHandler: function(form) {
			if($.preventDefault) {
				$.preventDefault();
			} else {
				$.returnValue = false;
			}

			// Collect data from inputs
			var rating = $(form).find('.rating').val();
			var shoutout = $(form).find('input[name="shoutout"]').val();
			var channel = $(form).find('input[name="channel"]').val();
			var notes = $(form).find('textarea').val();

			/**
	         * AJAX URL to send data
	         * (from localize_script)
	         */
	        var ajax_url = sas_forms_data.sas_ajax_url;

	        // Data to send
	        data = {
	         	action: 'rate_shoutout',
	         	rating: rating,
	         	shoutout: shoutout,
	         	channel: channel,
	         	notes: notes,
	        };

	        //add code to display loading spinner
	        $(form).find('button').html('<i class="fa fa-spinner fa-spin"></i>');

	        //Do Ajax request
	        $.post( ajax_url, data, function(response) {
	        	$(form).find('button').html('Saved');
	        	$(form).find('button').attr('disabled', 'disabled');
	        	$(form).find('button').css('background-color', 'green');
	        	if( response ) {
	         		$(form).find('.result-message').html( response );
	         		$(form).find('.result-message').addClass('alert-danger');
	         		$(form).find('.result-message').show();
		        }
	        });
		}
	};

	// Google SignIn
	startApp();
});

//---------------//
// Google SignIn //
//---------------//
var googleUser = {};
var startApp = function() {
gapi.load('auth2', function(){
  // Retrieve the singleton for the GoogleAuth library and set up the client.
  auth2 = gapi.auth2.init({
    client_id: '196707225696-dree7unq0gf7500eno8ad9vle3edq2bf.apps.googleusercontent.com',
    cookiepolicy: 'single_host_origin',
  });
  attachSignin(document.getElementById('inf_google'));
});
};

function attachSignin(element) {
auth2.attachClickHandler(element, {},
    function(googleUser) {

		var profile = googleUser.getBasicProfile();

		// Collect user data from google api
    var google_id = profile.getId();
    var first_name = profile.getGivenName();
    var last_name = profile.getFamilyName();
    var email = profile.getEmail();
    var referredBy = '';

    if(getCookie('sas_referred_by')) {
    	referredBy = getCookie('sas_referred_by');
    }

    /**
     * AJAX URL where to send data
     * (from localize_script)
     */
    var ajax_url = sas_forms_data.sas_ajax_url;

    // Data to send
    data = {
    	action: 'google_sign_in',
    	google_id: google_id,
    	first_name: first_name,
    	last_name: last_name,
    	email: email,
    	referred_by: referredBy,
    };

    // Do AJAX request
    jQuery.post( ajax_url, data, function(response) {
  		jQuery('#gSignIn-ajax-response').html(response);
    });

  }, function(error) {
  	console.log(JSON.stringify(error, undefined, 2));
  });
}
