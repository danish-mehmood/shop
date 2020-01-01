
jQuery( document ).ready( function($) {

	// Functions
	function getCookie(name) {
	    var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
	    return v ? v[2] : null;
	}
	function setCookie(name, value, days) {
	    var d = new Date;
	    d.setTime(d.getTime() + 24*60*60*1000*days);
	    document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
	}

	// ------------------- //
	// - Form Validation - //
	// ------------------- //

	//jQuery validator defaults
	$.validator.setDefaults({
	    errorElement: "span",
	    errorClass: "help-block error-help-block",
	    highlight: function (element, errorClass, validClass) {
	        // Only validation controls
	        if (!$(element).hasClass('novalidation')) {
	            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
	            $(element).closest('.checkbox').removeClass('has-success').addClass('has-error');
	        }
	    },
	    unhighlight: function (element, errorClass, validClass) {
	        // Only validation controls
	        if (!$(element).hasClass('novalidation')) {
	            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
	            $(element).closest('.checkbox').removeClass('has-error').addClass('has-success');
	        }
	    },
	    errorPlacement: function (error, element) {
	        if (element.parent('.input-group').length) {
	            error.insertAfter(element.parent());
	        }
	        else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
	            error.insertAfter(element.parent().parent());
	        }
	        else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
	            error.appendTo(element.parent().parent());
	        }
	        else {
	            error.insertBefore(element);
	        }
	    }
	});

	// Validator custom methods
	$.validator.addMethod('validUrl', function(value, element) {
        var url = $.validator.methods.url.bind(this);
        return url(value, element) || url('http://' + value, element);
    	}, 'Please enter a valid URL'
    );
  	$.validator.addMethod("passCheck",function(value, element){
    	return /^(?=.*?[A-Z])(?=.*?[0-9]).{6,}$/.test(value);
  	}, 'Please enter a valid password.');
	$.validator.addMethod('phone', function(value) {
		var numbers = value.split(/\d/).length - 1;
		return (10 <= numbers && numbers <= 20 && value.match(/^(\+){0,1}(\d|\s|\(|\)){10,20}$/));
	}, 'Please enter a valid phone number');
	/**
	 * influencer registration
	 */
	$("#inf-registration-form").validate({
		rules: {
		    inf_password: {
		    	passCheck: true
		    }
		},
		messages: {
		    inf_first_name: {
				required: 'First Name*'
		    },
		    inf_last_name: {
		    	required: 'Last Name*'
		    },
		    inf_email: {
		    	required: 'Email*',
		    	email: 'Please enter a valid email'
		   	},
	    	inf_password: {
		    	required: 'Password*'
		    }
		},
	  submitHandler:function(form) {
		  //   /**
		  //    * Prevent default action, so when user clicks button he doesn't navigate away from page
		  //    */
	    if ($.preventDefault) {
        $.preventDefault();
	    } else {
        $.returnValue = false;
	    }

	    var submit = $('button[type="submit"]');

	    submit.html('Submitting <i class="fa fa-spinner fa-spin"></i>');

		    // If for some reason result field is visible hide it
		    $('.result-message').hide();

		    // Collect data from inputs
		    var inf_firstname = $('input[name="inf_firstname"]').val();
		    var inf_lastname = $('input[name="inf_lastName"]').val();
		    var inf_email = $('input[name="inf_email"]').val();
		    var inf_password = $('input[name="inf_password"]').val();
		    var inf_nonce = $('#register_influencer_nonce').val();
		    var inf_referral = $('#referral').val();
				var referred_by = '';

			if(getCookie('sas_referred_by')) {
				referred_by = getCookie('sas_referred_by');
			}

	    /**
	     * AJAX URL where to send data
	     * (from localize_script)
	     */
	    var ajax_url = sas_forms_data.sas_ajax_url;

	    // Data to send
	    data = {
	    	action: 'register_influencer',
	        name: inf_firstname,
	        last_name: inf_lastname,
	        mail: inf_email,
	        password: inf_password,
	        nonce: inf_nonce,
	        referral: inf_referral,
	        referred_by: referred_by,
	    };

	    // Do AJAX request
	    $.post( ajax_url, data, function(response) {
		    // If we have response
		    submit.html('Sign Up');
		    if( response ) {
						response = JSON.parse(response);
						console.log(response);
        		if( response.url ) {
							window.location = response.url;
							submit.attr('disabled', 'disabled');
		        } else {
		            $('.result-message').html( response.error ); // If there was an error, display it in results div
		            $('.result-message').addClass('alert-danger'); // Add class failed to results div
		            $('.result-message').show(); // Show results div
		        }
		    }
	    });
		}
	});

	// Influencer audience demographics form
	$('#inf-audience-demographics-form').validate({
		submitHandler: function(form) {
			if($.preventDefault) {
				$.preventDefault();
			} else {
				$.returnValue = false;
			}

        	$(form).find('button').html('Save <i class="fa fa-spinner fa-spin"></i>');

			// Collect data from inputs
			var top_country_1 = $('#inf_top_country_1').val();
			var top_country_2 = $('#inf_top_country_2').val();
			var top_country_3 = $('#inf_top_country_3').val();
			var top_country_percentage_1 = $('#inf_percentage_1').val();
			var top_country_percentage_2 = $('#inf_percentage_2').val();
			var top_country_percentage_3 = $('#inf_percentage_3').val();
			var female_percentage = $('#inf_female_percentage').val();
			var male_percentage = $('#inf_male_percentage').val();
			var non_binary_percentage = $('#inf_non_binary_percentage').val();

			/**
	     * AJAX URL to send data
	     * (from localize_script)
	     */

	    var ajax_url = sas_forms_data.sas_ajax_url;

	    // Data to send
	    data = {
	     	action: 'inf_account_audience_demographics',
	     	top_country_1: top_country_1,
	     	top_country_2: top_country_2,
	     	top_country_3: top_country_3,
	     	top_country_percentage_1: top_country_percentage_1,
	     	top_country_percentage_2: top_country_percentage_2,
	     	top_country_percentage_3: top_country_percentage_3,
	     	female_percentage: female_percentage,
	     	male_percentage: male_percentage,
	     	non_binary_percentage: non_binary_percentage,
	    };

	    //Do Ajax request
	    $.post( ajax_url, data, function(response) {
			$(form).find('button').html('Saved');
	    	if( response ) {
	     		$('.result-message').html( response );
	     		$('.result-message').addClass('alert-danger');
	     		$('.result-message').show();
	      }
	    });
		}
	});

	// Influencer personal info form (this functionality was replaced with admin post due to certain 
	// Influencers having issues with javascript, this should either be deleted or reintegrated)
	$('#inf-personal-info-form').validate({
		rules: {
			inf_phone_number: {
				phone: true,
			},
			inf_birthdate_day: {
				number: true,
				range: [1, 31],
			},
			inf_birthdate_year: {
				number: true,
				minlength: 4,
				maxlength: 4,
			},

		},
		submitHandler: function(form) {
			// if($.preventDefault) {
			// 	$.preventDefault();
			// } else {
			// 	$.returnValue = false;
			// }

    	$(form).find('button').html('Saving <i class="fa fa-spinner fa-spin"></i>');
			form.submit();

			// Collect data from inputs
			// var first_name = $('#inf_first_name').val();
			// var last_name = $('#inf_last_name').val();
			// var email = $('#inf_email').val();
			// var phone = $('#inf_phone_number').val();
			// var country = $('#inf_country option:selected').val();
			// var region = $('#inf_region option:selected').val();
			// var gender = $('#inf_gender option:selected').val();
			// var birthdate_month = $('#inf_birthdate_month option:selected').val();
			// var birthdate_day = $('#inf_birthdate_day').val();
			// var birthdate_year = $('#inf_birthdate_year').val();
			//
			// /**
	    //      * AJAX URL to send data
	    //      * (from localize_script)
	    //      */
			//
	    //     var ajax_url = sas_forms_data.sas_ajax_url;
			//
	    //     // Data to send
	    //     data = {
	    //      	action: 'inf_account_personal_info',
	    //      	first_name: first_name,
	    //      	last_name: last_name,
	    //      	email: email,
	    //      	phone: phone,
	    //      	country: country,
	    //      	region: region,
	    //      	gender: gender,
	    //      	birthdate_month: birthdate_month,
	    //      	birthdate_day: birthdate_day,
	    //      	birthdate_year: birthdate_year,
	    //     };
			//
	    //     //Do Ajax request
	    //     $.post( ajax_url, data, function(response) {
    	// 		$(form).find('button').html('Saved');
			//
	    //     	if( response ) {
	    //      		$(form).find('.result-message').html( response );
	    //      		$(form).find('.result-message').addClass('alert-danger');
	    //      		$(form).find('.result-message').show();
		  //       }
	    //     });
		}
	});

	// Influencer shipping info form
	$('#inf-shipping-info-form').validate({
		submitHandler: function(form) {
			if($.preventDefault) {
				$.preventDefault();
			} else {
				$.returnValue = false;
			}

    	$(form).find('button').html('Save <i class="fa fa-spinner fa-spin"></i>');

			// Collect data from inputs
			var shipping_address_1 = $('#inf_shipping_address_1').val();
			var shipping_address_2 = $('#inf_shipping_address_2').val();
			var shipping_country = $('#inf_shipping_country').val();
			var shipping_city = $('#inf_shipping_city').val();
			var shipping_region = $('#inf_shipping_region').val();
			var shipping_postcode = $('#inf_shipping_postcode').val();

			/**
	     * AJAX URL to send data
	     * (from localize_script)
	     */
	    var ajax_url = sas_forms_data.sas_ajax_url;

	    // Data to send
	    data = {
	     	action: 'inf_account_shipping_info',
	     	shipping_address_1: shipping_address_1,
	     	shipping_address_2: shipping_address_2,
	     	shipping_country: shipping_country,
	     	shipping_city: shipping_city,
	     	shipping_region: shipping_region,
	     	shipping_postcode: shipping_postcode,
	    };

	    //Do Ajax request
	    $.post( ajax_url, data, function(response) {
			$(form).find('button').html('Saved');

	    	if( response ) {
	     		$(form).find('.result-message').html( response );
	     		$(form).find('.result-message').addClass('alert-danger');
	     		$(form).find('.result-message').show();
	      }
	    });
		}
	});

	// Influencer change password form
	if ( $('#inf-change-password-form').length ){
		$('#inf-change-password-form').validate({
			rules: {
			    inf_password: {
			    	passCheck: true,
			    },
			    inf_confirm_password: {
			    	equalTo: inf_password,
			    },
			},
			submitHandler: function(form) {
				if($.preventDefault) {
					$.preventDefault();
				} else {
					$.returnValue = false;
				}

	        	$(form).find('button').html('Save <i class="fa fa-spinner fa-spin"></i>');

				// Collect data from inputs
				var old_password = $('#inf_old_password').val();
				var new_password = $('#inf_password').val();

				/**
         * AJAX URL to send data
         * (from localize_script)
         */

        var ajax_url = sas_forms_data.sas_ajax_url;

        // Data to send
        data = {
        	action: 'inf_account_change_password',
         	old_password: old_password,
         	new_password: new_password,
        };

        //Do Ajax request
        $.post( ajax_url, data, function(response) {
  			$(form).find('button').html('Saved');

        	if( response ) {
         		$(form).find('.result-message').html( response );
         		$(form).find('.result-message').addClass('alert-danger');
         		$(form).find('.result-message').show();
	        }
        });
			}
		});
	}

	// Influencer select interests form
	$('#inf-interests-select-form').validate({
		submitHandler: function(form) {
			if($.preventDefault) {
				$.preventDefault();
			} else {
				$.returnValue = false;
			}

    	$(form).find('button').html('Save <i class="fa fa-spinner fa-spin"></i>');

			// Collect data from inputs
			var inf_interests = new Array();
			$('input[name="interests[]"]:checked').each( function(){
				inf_interests.push($(this).val());
			});

			/**
       * AJAX URL to send data
       * (from localize_script)
       */

      var ajax_url = sas_forms_data.sas_ajax_url;

      // Data to send
      data = {
       	action: 'inf_account_update_interests',
       	interests: inf_interests,
      };

      //Do Ajax request
      $.post( ajax_url, data, function(response) {
			$(form).find('button').html('Saved');

      	if( response ) {
       		$('.result-message').html( response );
       		$('.result-message').addClass('alert-danger');
       		$('.result-message').show();
        }
      });
		}
	});

	$('.nudge-brand-form').each(function(){
		$(this).validate({
			submitHandler:function(form) {
		    /**
		     * Prevent default action, so when user clicks button he doesn't navigate away from page
		     */
		    if ($.preventDefault) {
		        $.preventDefault();
		    } else {
		        $.returnValue = false;
		    }

		    // If for some reason result field is visible hide it
		    $('.result-message').hide();

        	$(form).find('button').html('Nudging <i class="fa fa-spinner fa-spin"></i>');

		    // Collect data from inputs
		    var type = $(form).find('input[name="type"]').val();
		    var order = $(form).find('input[name="order"]').val();

		    /**
		     * AJAX URL where to send data
		     * (from localize_script)
		     */
		    var ajax_url = sas_forms_data.sas_ajax_url;

		    // Data to send
		    data = {
		    	action: 'inf_nudge_brand',
		    	type: type,
		    	order: order,
		    };

		    // Do AJAX request
		    $.post( ajax_url, data, function(response) {

			    if( response ) {

			    	$(form).html('<p>Brand has been nudged, thank you!</p>');
			    }
		    });
			}
		})
	});

	//Influencer ShoutOut links form
	$('#shoutout-links-form').validate({
		rules: {
			instagram_url: {
				validUrl: true
			},
			facebook_url: {
				validUrl: true
			},
			twitter_url: {
				validUrl: true
			}
		}
	});

	// Influencer add to cart agreement
	$('#add-to-cart-agreement-form').validate();

	// Affiliate Payout Info form
	$('#affiliate-payout-info-form').validate({
		rules: {
			phone_number: {
				phone: true,
			},
			birthdate_day: {
				number: true,
				range: [1, 31],
			},
			birthdate_year: {
				number: true,
				minlength: 4,
				maxlength: 4,
			},
		},
		submitHandler: function(form) {
			if($.preventDefault) {
				$.preventDefault();
			} else {
				$.returnValue = false;
			}

    	$(form).find('button').html('Save <i class="fa fa-spinner fa-spin"></i>');

			// Collect data from inputs
			var first_name = $('#first_name').val();
			var last_name = $('#last_name').val();
			var phone = $('#phone').val();
			var birthdate_month = $('#birthdate_month option:selected').val();
			var birthdate_day = $('#birthdate_day').val();
			var birthdate_year = $('#birthdate_year').val();
			var address_1 = $('#shipping_address_1').val();
			var address_2 = $('#shipping_address_2').val();
			var country = $('#shipping_country').val();
			var region = $('#shipping_state').val();
			var city = $('#shipping_city').val();
			var postcode = $('#shipping_postcode').val();

			/**
       * AJAX URL to send data
       * (from localize_script)
       */
      var ajax_url = sas_forms_data.sas_ajax_url;

      // Data to send
      data = {
       	action: 'affiliate_payout_info',
       	first_name: first_name,
       	last_name: last_name,
       	phone: phone,
       	birthdate_month: birthdate_month,
       	birthdate_day: birthdate_day,
       	birthdate_year: birthdate_year,
       	address_1: address_1,
       	address_2: address_2,
       	country: country,
       	region: region,
       	city: city,
       	postcode: postcode,
      };

      //Do Ajax request
      $.post( ajax_url, data, function(response) {

			$(form).find('button').html('Saved');

      	if(response) {

      		response = JSON.parse(response);

  			if(!response.errors) {
  				$(form).find('.form-content').hide();
  				$(form).find('.success-content').show();
  			} else {
  				$(form).find('.error-message').html(response.errors);
  			}
  		}
      });
		}
	});

	// -------------------- //
	// ------ BRANDS ------ //
	// -------------------- //

	// Brand registration form
	if($('#brand-registration-form-wrapper').length) {
		$('#brand').click(function(){
			$('#type-select').hide();
			$('#brand-registration-form').show();
		});

		$('#employee').click(function(){
			$('#type-select').hide();
			$('#employee-message').show();
		});

		$('#employee-message-close').click(function(){
			$('#type-select').show();
			$('#employee-message').hide();
		});
	}

	$('#brand-registration-form').validate({
		rules: {
			brand_website: {
				validUrl: true,
			},
			email: {
				email: true,
			},
			phone: {
				phone: true,
			},
			password_1: {
				minlength: 6,
			},
			password_2: {
				equalTo: '#password',
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

			submit.html('Submitting  <i class="fa fa-spinner fa-spin"></i>');

			var brandName = $('#brand-name').val();
			var brandWebsite = $('#brand-website').val();
			var brandStory = $('#brand-story').val();
			var firstName = $('#first-name').val();
			var lastName = $('#last-name').val();
			var email = $('#email').val();
			var phone = $('#phone').val();
			var password = $('#password').val();
			var nonce = $('#brand_registration_nonce').val();

			var ajax_url = sas_forms_data.sas_ajax_url;

			data = {
				action: 'brand_registration',
				nonce: nonce,
				brand_name: brandName,
				brand_website: brandWebsite,
				brand_story: brandStory,
				first_name: firstName,
				last_name: lastName,
				email: email,
				phone: phone,
				password: password,
			}

			$.post( ajax_url, data, function(response) {

				submit.html('Submit');

				response = JSON.parse(response);

				if(response.errors.length == 0) {
					$('#brand-registration-form').hide();
					$('#brand-registration-success').show();
					if(response.uid) {
						brandSignupCheckCookies(response.uid);
					}
				} else {
					response.errors.forEach(function(element) {
						formErrors.append(element + '<br>');
					});

					formErrors.show();
					$.scrollTo(0);
				}
			});
		}
	});

	$('#add-brand-form').validate({
		rules: {
			brand_website: {
				validUrl: true,
			},
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

			submit.html('Submitting  <i class="fa fa-spinner fa-spin"></i>');

			var brandName = $('#brand-name').val();
			var brandWebsite = $('#brand-website').val();
			var brandStory = $('#brand-story').val();
			var nonce = $('#add_brand_nonce').val();

			var ajax_url = sas_forms_data.sas_ajax_url;

			data = {
				action: 'add_brand',
				nonce: nonce,
				brand_name: brandName,
				brand_website: brandWebsite,
				brand_story: brandStory,
			}

			$.post( ajax_url, data, function(response) {

				submit.html('Submit');

				response = JSON.parse(response);

				if(response.errors.length == 0) {
					$('#add-brand-form').hide();
					$('#add-brand-success').show();
				} else {
					response.errors.forEach(function(element) {
						formErrors.append(element + '<br>');
					});

					formErrors.show();
					$.scrollTo(0);
				}
			});
		}
	});

	$('#brand-invite-registration-form').validate({
		rules: {
			email: {
				email: true,
			},
			phone: {
				phone: true,
			},
			password_1: {
				minlength: 6,
			},
			password_2: {
				equalTo: '#password',
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

			submit.html('Submitting  <i class="fa fa-spinner fa-spin"></i>');

			var firstName = $('#first-name').val();
			var lastName = $('#last-name').val();
			var email = $('#email').val();
			var phone = $('#phone').val();
			var password = $('#password').val();
			var inviteString = $('#invite-string').val();
			var nonce = $('#brand_invite_registration_nonce').val();

			var ajax_url = sas_forms_data.sas_ajax_url;

			data = {
				action: 'brand_invite_registration',
				nonce: nonce,
				first_name: firstName,
				last_name: lastName,
				email: email,
				phone: phone,
				password: password,
				invite_string: inviteString,
			}

			$.post( ajax_url, data, function(response) {

				submit.html('Submit');

				response = JSON.parse(response);

				if(response.errors.length == 0) {
					$('#brand-invite-registration-form').hide();
					$('#brand-registration-success').show();
				} else {
					response.errors.forEach(function(element) {
						formErrors.append(element + '<br>');
					});

					formErrors.show();
					$.scrollTo(0);
				}
			});
		}
	});

	// Brand shedule demo contact form
	$("#brands-demo-form").validate({
		rules: {
		    contact_phone: {
		    	phone: true
		    }
		},
	    submitHandler:function(form) {
		    /**
		     * Prevent default action, so when user clicks button he doesn't navigate away from page
		     */
		    if ($.preventDefault) {
		        $.preventDefault();
		    } else {
		        $.returnValue = false;
		    }

		    // If for some reason result field is visible hide it
		    $('.result-message').hide();

      	$(form).find('button').html('Sending <i class="fa fa-spinner fa-spin"></i>');

		    // Collect data from inputs
		    var info = new Array();
		    $('.checkbox_info:checked').each(function(){
		    	info.push($(this).val());
		    });
		    var contactName = $('#contact-name').val();
		    var companyName = $('#company-name').val();
		    var contactEmail = $('#contact-email').val();
		    var contactPhone = $('#contact-phone').val();
		    var nonce = $('#brands_schedule_demo_nonce').val();

		    /**
		     * AJAX URL where to send data
		     * (from localize_script)
		     */
		    var ajax_url = sas_forms_data.sas_ajax_url;

		    // Data to send
		    data = {
		    	action: 'brands_schedule_demo',
	        info: info,
	        contact_name: contactName,
	        company_name: companyName,
	        contact_email: contactEmail,
	        contact_phone: contactPhone,
	        nonce: nonce
		    };

		    // Do AJAX request
		    $.post( ajax_url, data, function(response) {

			    if( response ) {

			    	if( response == 1 ) {

			    		$('.brands-demo-form-container').html('<h1>Request sent,<br>Thank you!</h1><p>A brand representative will contact you shortly with more information.</p>');
	        		$(form).find('button').html('Request Demo');

			    	} else {

		        	$(form).find('button').html('Request Demo');

	            $('.result-message').html( response ); // If there was an error, display it in results div
	            $('.result-message').addClass('alert-danger'); // Add class failed to results div
	            $('.result-message').show(); // Show results div
			    	}

			    }
		    });
		}
	});

	// Brand shedule demo contact form
	$("#brands-simple-demo-form").validate({
	    submitHandler:function(form) {
		    /**
		     * Prevent default action, so when user clicks button he doesn't navigate away from page
		     */
		    if ($.preventDefault) {
		        $.preventDefault();
		    } else {
		        $.returnValue = false;
		    }

		    // If for some reason result field is visible hide it
		    $('.result-message').hide();

        	$(form).find('button').html('Sending <i class="fa fa-spinner fa-spin"></i>');

		    // Collect data from inputs
		    var contactEmail = $('#contact-email').val();
		    var nonce = $('#brands_schedule_demo_nonce').val();

		    /**
		     * AJAX URL where to send data
		     * (from localize_script)
		     */
		    var ajax_url = sas_forms_data.sas_ajax_url;

		    // Data to send
		    data = {
		    	action: 'brands_schedule_demo_simple',
		        contact_email: contactEmail,
		        nonce: nonce,
		    };

		    // Do AJAX request
		    $.post( ajax_url, data, function(response) {

			    if( response ) {

			    	if( response == 1 ) {

			    		$('.brands-simple-demo-form-container').html('<h1>Request sent,<br>Thank you!</h1><p>A brand representative will contact you shortly with more information.</p>');
	        		$(form).find('button').html('Request Demo');

			    	} else {

		        	$(form).find('button').html('Request Demo');

	            $('.result-message').html( response ); // If there was an error, display it in results div
	            $('.result-message').addClass('alert-danger'); // Add class failed to results div
	            $('.result-message').show(); // Show results div
			    	}

			    }
		    });
		}
	});

	$('#brand-reject-influencer-form .checkbox_info[value="other"]').click(function(){
		$('#brand-reject-influencer-form .reason-other').toggle();
	});

	$('#brand-reject-influencer-form').validate({
		submitHandler: function(form) {
			if($.preventDefault) {
				$.preventDefault();
			} else {
				$.returnValue = false;
			}

			// Collect data from inputs
			var boxes = new Array();
			$(form).find('.checkbox_info:checked').each(function(){
				boxes.push($(this).val());
			});
			var other = $('#brand-reject-influencer-form textarea').val();
			var order = $('#brand-reject-influencer-form .order').val();

			/**
       * AJAX URL to send data
       * (from localize_script)
       */
      var ajax_url = sas_forms_data.sas_ajax_url;

      // Data to send
      data = {
       	action: 'reject_influencer',
       	order: order,
       	boxes: boxes,
       	other: other,
      };

      //add code to display loading spinner
      $(form).find('button').html('Submitting <i class="fa fa-spinner fa-spin"></i>');

      //Do Ajax request
      $.post( ajax_url, data, function(response) {
      	$(form).find('button').html('Submit');
      	if( response ) {
       		if( response == 0 ) {
       			$(form).hide();
       			$('.brand-reject-influencer-form-container .thank-you').show();
       		} else {
         		$(form).find('.result-message').html( response );
         		$(form).find('.result-message').addClass('alert-danger');
         		$(form).find('.result-message').show();
       		}
        }
      });
		}
	});

	$('.shoutout-final-score-container').each(function(){
		var reScoreContainer = $(this).parent().find('.shoutout-re-score-container');
		$(this).find('.brand-re-score-button').click(function(){
			$(this).parent().hide();
			reScoreContainer.show();
		});
	});

	$('.brand-shoutout-error').each(function(){

		var errorFormContainer = $(this).parent().parent().find('.shoutout-error-form-container');

		$(this).click(function(){

			errorFormContainer.show();

			$(this).parent().hide();
		});
	});

	//Influencer signup and login form scripts
	if($('.sas-form').length){
		$('#inf_firstname').keyup(function(){
			var label = $("label[for='"+$(this).attr('id')+"']");
			if( $(this).val() !== '' ) {
				label.css('opacity','1');
			} else {
				label.css('opacity','0');
			}
		});
		$('#inf_lastName').keyup(function(){
			var label = $("label[for='"+$(this).attr('id')+"']");
			if( $(this).val() !== '' ) {
				label.css('opacity','1');
			} else {
				label.css('opacity','0');
			}
		});
		$('#inf_email').keyup(function(){
			var label = $("label[for='"+$(this).attr('id')+"']");
			if( $(this).val() !== '' ) {
				label.css('opacity','1');
			} else {
				label.css('opacity','0');
			}
		});
		$('#inf_password').keyup(function(){
			var label = $("label[for='"+$(this).attr('id')+"']");
			if( $(this).val() !== '' ) {
				label.css('opacity','1');
			} else {
				label.css('opacity','0');
			}
		});


		$('#contact-name').keyup(function(){
			var label = $("label[for='"+$(this).attr('id')+"']");
			if( $(this).val() !== '' ) {
				label.css('opacity','1');
			} else {
				label.css('opacity','0');
			}
		});

		$('#company-name').keyup(function(){
			var label = $("label[for='"+$(this).attr('id')+"']");
			if( $(this).val() !== '' ) {
				label.css('opacity','1');
			} else {
				label.css('opacity','0');
			}
		});
		$('#contact-email').keyup(function(){
			var label = $("label[for='"+$(this).attr('id')+"']");
			if( $(this).val() !== '' ) {
				label.css('opacity','1');
			} else {
				label.css('opacity','0');
			}
		});
		$('#contact-phone').keyup(function(){
			var label = $("label[for='"+$(this).attr('id')+"']");
			if( $(this).val() !== '' ) {
				label.css('opacity','1');
			} else {
				label.css('opacity','0');
			}
		});

		//password validation popup
		if($('#password-info').length) {
			$('input[name="inf_password"]').keyup(function(){
				var pass = $(this).val();
				if (pass.length >= 6) {
					$('#length').addClass('valid').removeClass('invalid');
				} else {
					$('#length').removeClass('valid').addClass('invalid');
				}
				if ( pass.match(/[A-Z]/) ) {
				    $('#uppercase').removeClass('invalid').addClass('valid');
				} else {
				    $('#uppercase').removeClass('valid').addClass('invalid');
				}
				if ( pass.match(/\d/) ) {
				    $('#number').removeClass('invalid').addClass('valid');
				} else {
				    $('#number').removeClass('valid').addClass('invalid');
				}
			}).focus(function() {
				$('#password-info').show();
			}).blur(function() {
				$('#password-info').hide();
			});
		}
	}

	// Influencer interests checkbox functionality
	if($('#inf-interests-container').length){
		function checkCheckCount(checkCount) {
			if(checkCount >= 3) {
				$('#inf-interests-container :checkbox:not(:checked)').attr("disabled", true);
			} else {
				$('#inf-interests-container :checkbox:not(:checked):not(".age-disabled")').attr("disabled", false);
			}
		}
		var checkCount = $('#inf-interests-container :checked').length;
		checkCheckCount(checkCount);
		$('#inf-interests-container :checkbox').change(function(){
			checkCount = $('#inf-interests-container :checked').length;
			checkCheckCount(checkCount);
		});
	}

	//Influencer welcome form functionality
	if($('#inf-welcome-form').length) {
		var checkCount = $(':checked').length;
		$('#inf-welcome-form :checkbox').change(function(){
			checkCount = $(':checked').length;
			if (checkCount > 0) {
				$('#inf-welcome-form button[type="submit"]').removeAttr('disabled');
			} else {
				$('#inf-welcome-form button[type="submit"]').attr('disabled', 'disabled');
			}
		})
	}

	// Influencer country and region ajax functionality
	if($('#inf-personal-info-form').length) {
		function getRegionFromCountry( selectedCountry ) {
			var ajax_url = sas_forms_data.sas_ajax_url;

	        // Data to send
	        data = {
	         	action: 'get_region_from_country',
	         	country: selectedCountry,
	        };

	        //Do Ajax request
	        $.post( ajax_url, data, function(response) {
	        	if( response ) {
	         		$('#region-response').html( response );
		        }
	        });
		}

		var selectedCountry = jQuery('#inf_country option:selected').val();
		if( selectedCountry !== '' ) {
			getRegionFromCountry( selectedCountry );
		}
		$('#inf_country').change(function(){
			var selectedCountry = jQuery('#inf_country option:selected').val();

			getRegionFromCountry( selectedCountry );
		})
	}



	// -------------------------------------------------- //
	// --- Custom Form Inputs/jQuery UI element setup --- //
	// -------------------------------------------------- //

	// Number Stepper
	$('.form-number-stepper').each(function(){
		var numberInput = $(this).find('input[type="number"]');
		$('.form-number-stepper .minus').on('click', function(){
			numberInput.focus();
			numberInput[0].stepDown(1);
			numberInput.trigger('change');
			numberInput.blur();
		});
		$('.form-number-stepper .plus').on('click', function(){
			numberInput.focus();
			numberInput[0].stepUp(1);
			numberInput.trigger('change');
			numberInput.blur();
		});
	});

	// Sliders
	$('.form-slider').each(function(){
		var rangeSlider = $(this).data('range-slider');
		var prefix = $(this).data('prefix');
		var suffix = $(this).data('suffix');
		var step = $(this).data('step');
		var minVal = $(this).data('min');
		var maxVal = $(this).data('max');
		var disabled = $(this).attr('disabled');

		if(rangeSlider) {
			var minInput = $(this).find('.slider-min');
			var maxInput = $(this).find('.slider-max');

			$(this).slider({
				range: true,
				min: minVal,
				max: maxVal,
				step: step,
				values: [minInput.val() ? minInput.val() : minVal, maxInput.val() ? maxInput.val() : maxVal],
				slide: function(e, ui) {
					minInput.focus();
					minInput.val(ui.values[0]).trigger('change');
					minInput.blur();
					maxInput.focus();
					maxInput.val(ui.values[1]).trigger('change');
					maxInput.blur();
				},
			}).slider('float', {
				prefix: prefix,
				suffix: suffix,
			});
		} else {
			var input = $(this).find('.slider-value');

			$(this).slider({
				range: "min",
				min: minVal,
				max: maxVal,
				step: step,
				value: [input.val() ? input.val() : minVal],
				slide: function(e, ui) {
					input.focus();
					input.val(ui.value).trigger('change');
					input.blur();
				},
			}).slider('float', {
				prefix: prefix,
				suffix: suffix,
			});
		}

		if(disabled) {
			$(this).slider('disable');
		}
	});

	// Select bar
	$('.select-bar').on('change', '.select-item', function(){
		if($(this).hasClass('custom')) {
			$(this).closest('.select-bar').find('.custom-input').show();
		} else {
			$(this).closest('.select-bar').find('.custom-input').hide();
		}
	});

	// Datepickers
	$("#ui-datepicker-div").wrap('<div style="position:absolute;top:0px;"></div>');
	$('.datepicker-range').each(function(){
		var from = $(this).find('.from');
		var to = $(this).find('.to');

		from.datepicker({
			minDate: new Date(),
			dateFormat: 'mm/dd/yy',
		}).on('change', function(){
			to.datepicker("option", "minDate", getDate(this));
		});
		to.datepicker({
			minDate: new Date(),
			dateFormat: 'mm/dd/yy',
		}).on('change', function(){
			from.datepicker("option", "maxDate", getDate(this));
		});
	});
	function getDate(element) {
		var date;

		try{
			date = $.datepicker.parseDate('mm/dd/yy', $(element).val());
		} catch(error) {
			date = new Date();
		}

		return date;
	}

	//Drag and drop image upload
	var isAdvancedUpload = function() {
		var div = document.createElement('div');
		return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
	}

	//Tag inputs
	$('.tag-input').each(function(){
		var separators = $(this).data('separators');
		var prefix = $(this).data('prefix');
		$(this).select2({
			tags: true,
			formatNoMatches: function() {
				return '';
			},
			createTag: function(params) {
				if(params.term.indexOf(prefix) === -1) {
					return {
						id: params.term,
						text: prefix+params.term,
						newTag: true,
					}
				}

				return {
					id: params.term.replace(prefix,''),
					text: params.term,
					newTag: true,
				}
			},
			tokenSeparators: separators,
			dropdownCssClass: 'select2-hidden',
		});
	});

	if(isAdvancedUpload) {
		uploadBox = $('.image-upload-box');
		fileInput = uploadBox.find('input[type="file"]');

		uploadBox.addClass('has-advanced-upload');

		var droppedFiles = false;

		uploadBox.on('drag dragstart dragend dragover dragenter dragleave drop', function(e){
			e.preventDefault();
		})
		.on('dragover dragenter', function(){
			uploadBox.addClass('is-dragover');
		})
		.on('dragleave dragend drop', function(){
			uploadBox.removeClass('is-dragover');
		})
		.on('drop', function(e) {
			droppedFiles = e.originalEvent.dataTransfer.files;
			fileInput.prop('files', droppedFiles);
			fileInput.change();

		})
	}

	// Design Campaign Form
	if($('#design-campaign-form').length){

		var ajax_url = sas_forms_data.sas_ajax_url;

		var brandID = $('#brand-id').val();
		var campaignID = $('#campaign-id').val();
		var selectedStrategy = $('#selected-strategy').val();
		var setCountries = $('#set-countries').val()?JSON.parse($('#set-countries').val()):'';
		var setRegions = $('#set-regions').val()?JSON.parse($('#set-regions').val()):'';
		var stepForms = $('form[data-form-part]');
		var currentStep = 0;
		var loadOverlay = $('#loading-overlay');
		var ajaxNotifier = $('#ajax-notifier');
		var ajaxTimeout = false;
		var contentURL = $('#content-url').val();
		var formCompletion = $('#form-completion').val()?JSON.parse($('#form-completion').val()):new Object;

		if(campaignID) { // Editing existing campaign

			setCampaignStrategyDependentFields(selectedStrategy);

			$('#design-campaign-nav .design-campaign-nav-item[disabled]').removeAttr('disabled');

			slideToStep(1);
		}

		// Setting up data source for countries autocomplete tag input
  	$.post(ajax_url,{action: 'countries_JSON'},function(response){
  		response = JSON.parse(response);
  		$('#country-select .token-input').tokenInput(response,{
  			theme: 'facebook',
  			preventDuplicates: true,
  			prePopulate: setCountries,
  			onAdd: function(item) {
  				var selectedCountries = $('#country-select .token-input').tokenInput('get');
  				updateRegionSelect(selectedCountries);
  			},
  			onDelete: function(item) {
  				var selectedCountries = $('#country-select .token-input').tokenInput('get');
  				updateRegionSelect(selectedCountries);
  			},
  		});

			var selectedCountries = $('#country-select .token-input').tokenInput('get');
			$.post(ajax_url, {action: 'regions_JSON', selected_countries: selectedCountries}, function(response){
				response = JSON.parse(response);
				if(response.length) {
					$('#region-select .token-input').tokenInput(response, {
						theme: 'facebook',
						preventDuplicates: true,
						prePopulate: setRegions,
					});
					$('#region-select').show();
				}
			});
    	});

    	// Data source for regions after country select
    	function updateRegionSelect(selectedCountries){
    		if(selectedCountries.length) {
    			$.post(ajax_url,{action: 'regions_JSON', selected_countries: selectedCountries},function(response){
    				response = JSON.parse(response);
    				if(response.length) {
    					$('#region-select .token-input-list-facebook').remove();
	    				$('#region-select .token-input').tokenInput(response,{
	    					theme: 'facebook',
	    					preventDuplicates: true,
	    				});
	    				$('#region-select').show();
    				}
    			});
    		} else {
    			$('#region-select').hide();
    		}

    	}

		// After selecting a campaign strategy
		$('#campaign-strategy-select input[name="campaign-strategy"]').on('click', function(e){
			e.stopPropagation();

			var newStrategy = $(this).val();

			if(newStrategy != selectedStrategy) {

				selectedStrategy = newStrategy;

				data = {
					action: 'design_campaign_update',
					campaign_id: campaignID,
					brand_id: brandID,
					campaign_strategy: selectedStrategy,
					form_part: 0,
				}

				loadOverlay.show();

				$.post(ajax_url, data, function(response) {
					// hide overlay
					loadOverlay.hide();
					// parse json
					response = JSON.parse(response);

					// Check for errors
					if(!response.formErrors.length) {
						// Update campaign strategy dependent text
						setCampaignStrategyDependentFields(selectedStrategy);

						if(response.newCampaign) {
							// Set the current campaignID
							campaignID = response.newCampaign;

							// Update url with campaignID
				          	var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?b=' + brandID + '&c=' + campaignID;
							window.history.pushState({path:newurl},'',newurl);

							// Slide to next step
							slideToStep(currentStep+1);

						} else {
							// update relevant fields
							slideToStep(currentStep+1);
						}

						$('#design-campaign-nav .design-campaign-nav-item[disabled]').removeAttr('disabled');
					} else {
						console.log('formErrors: ' + response.formErrors);
					}
				});
			} else {
				slideToStep(currentStep+1);
			}
		});

		// Form Step Navigation
		$('.next-button').on('click', function(e){
			e.preventDefault();
			var currentForm = $('form[data-form-part="'+currentStep+'"]');
			if(currentForm.valid()) {
				slideToStep(currentStep+1);
				window.scrollTo('', 0);
			} else {
				window.scrollTo('', currentForm[0].offsetTop);
			}
		});
		$('.previous-button').on('click', function(e){
			e.preventDefault();
			slideToStep(currentStep-1);
		});
		$('#design-campaign-nav').on('click', '.design-campaign-nav-item:not([disabled])', function(){
			var formPart = $(this).data('form-part');
			if(currentStep!=formPart) {
				slideToStep(formPart);
			}
		});
		$('.save-draft').on('click', function(e){
			e.preventDefault();
			if(currentStep<4) {
				$('form[data-form-part="'+currentStep+'"]').submit();
			}
			window.location.href = $('#brand-account-url').val();
		});
		$('#campaign-review-inner .edit').on('click', function(e){
			var formPart = $(this).data('form-part');
			slideToStep(formPart);
		});
		$('#campaign-review').on('click', '.submit:not([disabled])', function(e){
			e.preventDefault();
			if(selectedStrategy == 'ambassador') {
				$('#ambassador-submit-popup').show();
			} else {
				$(this).html('Submitting <i class="fa fa-spinner fa-spin"></i>');
				$('form[data-form-part='+currentStep+']').submit();
				window.location.href = $('#brand-account-url').val();
			}
		});
		$('.ambassador-submit').on('click', function(e){
			$('#ambassador-submit-popup').find('.popup').hide();
			$('#ambassador-submit-popup').find('.popup-success').show();
		})

		// --------------------- //
		// -- Form Validation -- //
		// --------------------- //

		//Validator defaults
		$.validator.setDefaults({
		    errorElement: "span",
		    errorClass: "form-item-error",
		    ignore: '.novalidation',
		    highlight: function (element, errorClass, validClass) {
		        // Only validation controls
		        $(element).closest('.form-item').removeClass('has-success').addClass('has-error');
		        elementID = $(element).attr('id');
		        $('[data-review-item="'+elementID+'"]').addClass('has-error');
		    },
		    unhighlight: function (element, errorClass, validClass) {
		        // Only validation controls
	            $(element).closest('.form-item').removeClass('has-error').addClass('has-success');
		        elementID = $(element).attr('id');
		        $('[data-review-item="'+elementID+'"]').removeClass('has-error');
		    },
		    errorPlacement: function (error, element) {

		    },
		});

		$('#influencer-description').validate({
			invalidHandler:function(form) {
				var formPart = $(form.target).data('form-part');
				setFormStatus(formPart, 'error');
				influencerDescriptionSubmission(form);
			},
			submitHandler: function(form) {
				var formPart = $(form).data('form-part');
				setFormStatus(formPart, 'completed');
				influencerDescriptionSubmission(form);
			},
		});

		function influencerDescriptionSubmission(form) {

			ajaxNotifier.removeClass();
			ajaxNotifier.addClass('saving');

			// Collect form data
			var minAge = $('#age-range-min').val(),
				maxAge = $('#age-range-max').val(),
				gender = [],
				countries = [],
				regions = [],
				interests = [],
				interestNames = [],
				acquisitionTimeline = $('input[name="timeline"]:checked').val(),
				instagramReach = $('#instagram-reach').val(),
				instagramEngagement = $('#instagram-engagement').val(),
				authenticity = $('#authenticity').val();

			$('input[name="gender-select"]:checked').each(function(){
				gender.push($(this).val());
			});
			if($('#country-select .token-input-list-facebook').length) {
				countries = $('#country-select .token-input').tokenInput('get');
			}
			if(countries.length) {
				try {
					regions = $('#region-select .token-input').tokenInput('get');
				} catch(e) {

				}
			}
			$('input[name="interest-select"]:checked').each(function(){
				interests.push($(this).val());
				interestNames.push($(this).data('name'));
			});

			data = {
				action: 'design_campaign_update',
				campaign_id: campaignID,
				brand_id: brandID,
				form_part: 1,
				form_completion: formCompletion,
				min_age: minAge,
				max_age: maxAge,
				gender: gender,
				countries: countries,
				regions: regions,
				interests: interests,
				acquisition_timeline: acquisitionTimeline,
				instagram_reach: instagramReach,
				instagram_engagement: instagramEngagement,
				authenticity: authenticity,
			};

	        $.post( ajax_url, data, function(response) {
				// parse json
				response = JSON.parse(response);

				// Check for errors
				if(response.formErrors.length) {
					console.log(response.formErrors);
				} else {
					ajaxNotifier.removeClass();
					ajaxNotifier.addClass('saved');

					if(ajaxTimeout) clearTimeout(ajaxTimeout);

					ajaxTimeout = setTimeout(function(){
						ajaxNotifier.removeClass();
						ajaxTimeout = false;
					}, 2500);

					// Campaign Review Update
					$('.review-item[data-review-item="age-range"] .value').html(minAge + ' - '+ maxAge);
					$('.review-item[data-review-item="gender"] .value').html(gender?gender.join(', '):'Not Set');
					var countriesList = [];
					if(countries) {
						$(countries).each(function(e){
							countriesList.push($(this)[0]['name']);
						});
					}
					$('.review-item[data-review-item="countries"] .value').html(countries?countriesList.join(', '):'Global');
					var regionsList = [];
					if(regions) {
						$(regions).each(function(e){
							regionsList.push($(this)[0]['name']);
						});
					}
					$('.review-item[data-review-item="regions"] .value').html(regions?regionsList.join(', '):'Regions');
					$('.review-item[data-review-item="interests"] .value').html(interests?interestNames.join(', '):'Not Set');
					var acquisitionTimelineMessage = '';
					switch(acquisitionTimeline) {
						case '3' :
							acquisitionTimelineMessage = '3 Months, 40+ Influencers';
							break;
						case '6' :
							acquisitionTimelineMessage = '6 Months, 90+ Influencers';
							break;
						case '9':
							acquisitionTimelineMessage = '9 Months, 145+ Influencers';
							break;
						case '12':
							acquisitionTimelineMessage = '12 Months, 205+ Influencers';
							break;
						case '0':
							acquisitionTimelineMessage = 'Not Sure';
							break;
					}
					$('.review-item[data-review-item="acquisition-timeline"] .value').html(acquisitionTimelineMessage);
					$('.review-item[data-review-item="authenticity"] .value').html(authenticity?authenticity+'%':'Not Set');
					$('.review-item[data-review-item="instagram-reach"] .value').html(instagramReach?instagramReach:'Not Set');
					$('.review-item[data-review-item="instagram-engagement"] .value').html(instagramEngagement?instagramEngagement+'%':'Not Set');
				}
	        });
		}

		$('#campaign-logistics').validate({
			invalidHandler:function(form) {
				var formPart = $(form.target).data('form-part');
				setFormStatus(formPart, 'error');
				campaignLogisticsSubmission(form);
			},
			submitHandler:function(form) {
				var formPart = $(form).data('form-part');
				setFormStatus(formPart, 'completed');
				campaignLogisticsSubmission(form);
			},
		});

		function campaignLogisticsSubmission(form) {

			ajaxNotifier.removeClass();
			ajaxNotifier.addClass('saving');

			// Collect form data
			var variationTitle = $('#variation-title').val(),
				variationOptions = [],
				title = $('#title').val(),
				description = $('#description').val(),
				prizeDescription = $('#prize-description').val(),
				value = $('#value').val(),
				campaignType = $('input[name="campaign-type"]:checked').val(),
				programGoal = $('#program-goal').val(),
				paymentStructure = $('#payment-structure').val(),
				fulfillmentType = $('input[name="fulfillment-type"]:checked').val(),
				fulfillmentTypeOther = $('#fulfillment-type-other').val();

			$('#variation-options').find('option[data-select2-tag="true"][value]').each(function(){
				if($(this).val()) {
					variationOptions.push($(this).val());
				}
			});

			data = {
				action: 'design_campaign_update',
				campaign_id: campaignID,
				brand_id: brandID,
				form_part: 2,
				form_completion: formCompletion,
				variation_title: variationTitle,
				variation_options: variationOptions,
				title: title,
				description: description,
				prize_description: prizeDescription,
				value: value,
				campaign_type: campaignType,
				program_goal: programGoal,
				payment_structure: paymentStructure,
				fulfillment_type: fulfillmentType,
				fulfillment_type_other: fulfillmentTypeOther,
			};

	        $.post( ajax_url, data, function(response) {
				// parse json
				response = JSON.parse(response);

				// Check for errors
				if(response.formErrors.length) {
					console.log(response.formErrors);
				} else {
					ajaxNotifier.removeClass();
					ajaxNotifier.addClass('saved');

					if(ajaxTimeout) clearTimeout(ajaxTimeout);

					ajaxTimeout = setTimeout(function(){
						ajaxNotifier.removeClass();
						ajaxTimeout = false;
					}, 2500);

					// Campaign Review Update
					$('.review-item[data-review-item="title"] .value').html(title);
					$('.review-item[data-review-item="description"] .value').html(description);
					$('.review-item[data-review-item="prize-description"] .value').html(prizeDescription);
					$('.review-item[data-review-item="value"] .value').html('$'+value);
					$('.review-item[data-review-item="campaign-type"] .value').html(campaignType);
					$('.review-item[data-review-item="program-goal"] .value').html(programGoal);
					$('.review-item[data-review-item="payment-structure"] .value').html(paymentStructure);
					$('.review-item[data-review-item="fulfillment-type"] .value').html(fulfillmentType);
				}
	        });
		}

		$('#campaign-guidelines').validate({
			invalidHandler:function(form) {
				var formPart = $(form.target).data('form-part');
				setFormStatus(formPart, 'error');
				campaignGuidelinesSubmission(form);
			},
			submitHandler:function(form) {
				var formPart = $(form).data('form-part');
				setFormStatus(formPart, 'completed');
				campaignGuidelinesSubmission(form);
			},
		});

		function campaignGuidelinesSubmission(form) {

			ajaxNotifier.removeClass();
			ajaxNotifier.addClass('saving');

			// Collect form data
			var visuals = $('#visuals').val(),
				photoTags = [],
				caption = $('#caption').val(),
				hashtags = [],
				postTimeline = $('input[name="timeline-select"]:checked').val();
				postTimelineCustom = $('#post-timeline-custom').val();

			$('#photo-tags').find('option[data-select2-tag="true"][value]').each(function(){
				if($(this).val()) {
					photoTags.push($(this).val());
				}
			});
			$('#hashtags').find('option[data-select2-tag="true"][value]').each(function(){
				if($(this).val()) {
					hashtags.push($(this).val());
				}
			});

			data = {
				action: 'design_campaign_update',
				campaign_id: campaignID,
				brand_id: brandID,
				form_part: 3,
				form_completion: formCompletion,
				visuals: visuals,
				photo_tags: photoTags,
				caption: caption,
				hashtags: hashtags,
				post_timeline: postTimeline,
				post_timeline_custom: postTimelineCustom,
			};

	        $.post( ajax_url, data, function(response) {
				// parse json
				response = JSON.parse(response);

				// Check for errors
				if(response.formErrors.length) {
					console.log(response.formErrors);
				} else {
					ajaxNotifier.removeClass();
					ajaxNotifier.addClass('saved');

					if(ajaxTimeout) clearTimeout(ajaxTimeout);

					ajaxTimeout = setTimeout(function(){
						ajaxNotifier.removeClass();
						ajaxTimeout = false;
					}, 2500);

					// Campaign Review Update
					$('.review-item[data-review-item="visuals"] .value').html(visuals?visuals:'Not Set');
					$('.review-item[data-review-item="photo-tags"] .value').html(photoTags.length?photoTags.join(', '):'Not Set');
					$('.review-item[data-review-item="caption"] .value').html(caption?caption:'Not Set');
					$('.review-item[data-review-item="hashtags"] .value').html(hashtags.length?hashtags.join(', '):'Not Set');
					$('.review-item[data-review-item="post-timeline"] .value').html(postTimeline?postTimeline:'Not Set');
				}
	        });
		}

		$('#campaign-review').validate({
			submitHandler:function(form) {
				data = {
					action: 'design_campaign_update',
					campaign_id: campaignID,
					brand_id: brandID,
					form_part: 4,
				};

		        $.post( ajax_url, data, function(response) {
					// parse json
					response = JSON.parse(response);

					// Check for errors
					if(response.formErrors.length) {
						console.log(response.formErrors);
					} else {
						ajaxNotifier.removeClass();
						ajaxNotifier.addClass('saved');

						if(ajaxTimeout) clearTimeout(ajaxTimeout);

						ajaxTimeout = setTimeout(function(){
							ajaxNotifier.removeClass();
							ajaxTimeout = false;
						}, 2500);
					}
		        });
			},
		});

		// Campaign thumbnail/gallery upload
		$('#campaign-image-input').on('change', function(e){
			var data = new FormData();

			var uploadBox = $(this).closest('.image-upload-box');

			uploadBox.addClass('uploading');

	        // Loop through each data and create an array file[] containing our files data.
	        $.each($(this), function(i, obj) {
	            $.each(obj.files,function(j,file){
	                data.append('campaign_images[' + j + ']', file);
	            })
	        });

	        data.append('action', 'design_campaign_upload_product_images');

	        data.append('campaign_id', campaignID);

	        var previewContainer = $('#campaign-preview-images-container');
	        var reviewContainer = $('.review-item[data-review-item="campaign-images"] .value');

	        $.ajax({
	        	type: 'POST',
	        	url: ajax_url,
	        	data: data,
	        	contentType: false,
	        	processData: false,
	        	success: function(response) {
	        		response = JSON.parse(response);

	        		if(response) {
				        previewContainer.empty();
				        reviewContainer.empty();
				        response.forEach(function(image) {
				        	previewContainer.append('<div class="preview-image" data-attachment-id="'+image['id']+'" ><span class="remove"></span><img src="'+image['url']+'"></div>');
				        	reviewContainer.append('<div class="preview-image" ><img src="'+image['url']+'"></div>');
				        });
	        		}

					uploadBox.removeClass('uploading');
					uploadBox.addClass('success');

					setTimeout(function(){
						uploadBox.removeClass('success');
					}, 2500);
	        	},
	        });
		});

		// Campaign thumbnail/gallery preview images
		$('#campaign-preview-images-container').on('click', '.preview-image', function(){

			var attachID = $(this).data('attachment-id');

			data = {
				action: 'design_campaign_update_product_images',
				campaign_id: campaignID,
				attach_id: attachID,
				operation: 'primary',
			};

			$('#campaign-preview-images-container').find('.primary').removeClass('primary');

			$(this).addClass('primary');

	        $.post( ajax_url, data, function(response) {
				// parse json
				response = JSON.parse(response);

				// Check for errors
				if(response.formErrors.length) {

					ajaxNotifier.removeClass();
					ajaxNotifier.addClass('error');

					if(ajaxTimeout) clearTimeout(ajaxTimeout);

					ajaxTimeout = setTimeout(function(){
						ajaxNotifier.removeClass();
						ajaxTimeout = false;
					}, 2500);
				} else {
					ajaxNotifier.removeClass();
					ajaxNotifier.addClass('saved');

					if(ajaxTimeout) clearTimeout(ajaxTimeout);

					ajaxTimeout = setTimeout(function(){
						ajaxNotifier.removeClass();
						ajaxTimeout = false;
					}, 2500);
				}
	        });
		});

		$('#campaign-preview-images-container').on('click', '.preview-image .remove', function(e){

			var attachID = $(this).closest('.preview-image').data('attachment-id');

			e.stopPropagation();

			data = {
				action: 'design_campaign_update_product_images',
				campaign_id: campaignID,
				attach_id: attachID,
				operation: 'remove',
			};

	        $.post( ajax_url, data, function(response) {
				// parse json
				response = JSON.parse(response);

				// Check for errors
				if(response.formErrors.length) {
					console.log(response.formErrors);

					ajaxNotifier.removeClass();
					ajaxNotifier.addClass('error');

					if(ajaxTimeout) clearTimeout(ajaxTimeout);

					ajaxTimeout = setTimeout(function(){
						ajaxNotifier.removeClass();
						ajaxTimeout = false;
					}, 2500);
				} else {
					ajaxNotifier.removeClass();
					ajaxNotifier.addClass('saved');

					if(ajaxTimeout) clearTimeout(ajaxTimeout);

					ajaxTimeout = setTimeout(function(){
						ajaxNotifier.removeClass();
						ajaxTimeout = false;
					}, 2500);
				}
	        });


			$(this).closest('.preview-image').remove();
		});

		// ShoutOut inspiration images
		$('#shoutout-inspiration-input').on('change', function(e){
			var data = new FormData();

			var uploadBox = $(this).closest('.image-upload-box');

	        // Loop through each data and create an array file[] containing our files data.
	        $.each($(this), function(i, obj) {
	        	if(obj.files.length) {

					uploadBox.addClass('uploading');

		            $.each(obj.files,function(j,file){
		                data.append('inspiration_images[' + j + ']', file);
		            });

			        data.append('action', 'design_campaign_upload_shoutout_inspiration_images');

			        data.append('campaign_id', campaignID);

			        var previewContainer = $('#shoutout-inspiration-preview-images-container');
			        var reviewContainer = $('.review-item[data-review-item="inspiration-images"] .value');

			        $.ajax({
			        	type: 'POST',
			        	url: ajax_url,
			        	data: data,
			        	contentType: false,
			        	processData: false,
			        	success: function(response) {
			        		response = JSON.parse(response);

			        		if('images' in response) {
						        previewContainer.empty();
						        reviewContainer.empty();
						        response['images'].forEach(function(image) {
						        	if(image['url'] != null) {
							        	previewContainer.append('<div class="preview-image" data-attachment-id="'+image['id']+'" ><span class="remove"></span><img src="'+image['url']+'"></div>');
							        	reviewContainer.append('<div class="preview-image"><img src="'+image['url']+'"></div>');
						        	}
						        });
			        		}

							uploadBox.removeClass('uploading');
							uploadBox.addClass('success');

							setTimeout(function(){
								uploadBox.removeClass('success');
							}, 2500);
			        	},
			        });
	        	}
	        });
		});

		$('#shoutout-inspiration-preview-images-container').on('click', '.preview-image .remove', function(e){

			var attachID = $(this).closest('.preview-image').data('attachment-id');

			e.stopPropagation();

			data = {
				action: 'design_campaign_remove_shoutout_inspiration_image',
				campaign_id: campaignID,
				attach_id: attachID,
			};

	        $.post( ajax_url, data, function(response) {
				// parse json
				response = JSON.parse(response);

				// Check for errors
				if(response.formErrors.length) {
					console.log(response.formErrors);

					ajaxNotifier.removeClass();
					ajaxNotifier.addClass('error');

					if(ajaxTimeout) clearTimeout(ajaxTimeout);

					ajaxTimeout = setTimeout(function(){
						ajaxNotifier.removeClass();
						ajaxTimeout = false;
					}, 2500);
				} else {
					ajaxNotifier.removeClass();
					ajaxNotifier.addClass('saved');

					if(ajaxTimeout) clearTimeout(ajaxTimeout);

					ajaxTimeout = setTimeout(function(){
						ajaxNotifier.removeClass();
						ajaxTimeout = false;
					}, 2500);
				}
	        });


			$(this).closest('.preview-image').remove();
		});

		// Form specific functions
		function slideToStep(slideTo) {

			currentForm = $('form[data-form-part='+currentStep+']');
			nextForm = $('form[data-form-part='+slideTo+']');
			wrapper = $('.design-campaign-wrapper');
			formMinHeight = currentForm.height()>nextForm.height()?currentForm.height():nextForm.height();

			wrapper.css('min-height', formMinHeight+88);

			if(currentStep < slideTo) {
				currentForm.attr('class', 'form-part').css('position', 'absolute').addClass('slide-out-left');
				setTimeout(function(){
					currentForm.hide();
					wrapper.css('min-height', 0);
				}, 700);
				nextForm.attr('class', 'form-part').css('position', 'static').show().addClass('slide-in-right');
			} else {
				currentForm.attr('class', 'form-part').addClass('slide-out-right');
				setTimeout(function(){
					currentForm.hide();
					nextForm.css('position', 'static');
					wrapper.css('min-height', 0);
				}, 700);
				nextForm.attr('class', 'form-part').css('position', 'absolute').show().addClass('slide-in-left');
			}



			// Slide the current form step left
			if(currentStep>0&&currentStep<4) {
				currentForm.submit();
			}
			// Update nav item classes
			$('#design-campaign-nav .design-campaign-nav-item').removeClass('current');
			$('#design-campaign-nav .design-campaign-nav-item[data-form-part="'+slideTo+'"]').addClass('current');

			currentStep = slideTo;

			return;
		}

		function setFormStatus(formPart, status) {
				formCompletion[formPart] = status;
				if(status == 'completed') {
					$('.design-campaign-nav-item[data-form-part="'+formPart+'"]').removeClass('error').addClass(status);
				} else if(status == 'error') {
					$('.design-campaign-nav-item[data-form-part="'+formPart+'"]').removeClass('completed').addClass(status);
				}

				var allFormsCompleted = true;
				for(var i in formCompletion) {
					if(formCompletion[i] != 'completed') {
						allFormsCompleted = false;
					}
				};
				if(allFormsCompleted) {
					$('.submit').removeAttr('disabled');
				} else {
					$('.submit').attr('disabled', 'disabled');
				}
		}

		function setCampaignStrategyDependentFields(campaignStrategy) {
			// SET CAMPAIGN STRATEGY DEPENDENT TEXT
			$('.campaign-strategy-dependent-image').attr('src', contentURL+'/images/brand-account/design-campaign/'+campaignStrategy+'.svg');

			$('[data-hide-for-strategies]').each(function(){
				var hideForStrategies = $(this).data('hide-for-strategies');
				if(hideForStrategies.includes(campaignStrategy)){
					$(this).hide();
					if($(this).hasClass('form-item')) {
						$(this).find('input,textarea').addClass('novalidation');
					}
				} else {
					$(this).show();
					if($(this).hasClass('form-item')) {
						$(this).find('input,textarea').removeClass('novalidation');
					}
				}
			});
			switch(campaignStrategy) {
				case 'shoutout':
					$('.campaign-strategy-dependent-text').html('ShoutOut');
					$('.campaign-strategy-dependent-text-influencer').html('Influencer');
					break;
				case 'giveaway':
					$('.campaign-strategy-dependent-text').html('Giveaway');
					$('.campaign-strategy-dependent-text-influencer').html('Influencer');
					break;
				case 'mission':
					$('.campaign-strategy-dependent-text').html('Mission');
					$('.campaign-strategy-dependent-text-influencer').html('Influencer');
					break;
				case 'ambassador':
					$('.campaign-strategy-dependent-text').html('Ambassador');
					$('.campaign-strategy-dependent-text-influencer').html('Ambassador');
					break;
			}
		}
	}
});
// tooltips
jQuery(function ($) {
  $('[data-toggle="tooltip"]').tooltip();
})
