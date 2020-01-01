jQuery( document ).ready( function($) {
	// --- ADMIN FORMS --- //

	// Export ambassador report
	$('#admin-export-affiliate-report').validate({
		submitHandler: function(form) {
			if($.preventDefault) {
				$.preventDefault();
			} else {
				$.returnValue = false;
			}

        	var submit = $(form).find('button[type="submit"]');
        	var fileDL = $(form).find('.file-download');
        	var notice = $(form).find('.notice');
        	var noticeMessage = $(notice).find('.notice-message');
        	var period = $('#period-select').val();

        	submit.html('Exporting <i class="fa fa-spinner fa-spin"></i>');

	        var ajax_url = admin_forms_data.admin_ajax_url;

	        var nonce = $('#export-affiliate-report').val();

	        // Data to send
	        data = {
	         	action: 'admin_export_ambassador_report',
	         	period: period,
	         	nonce: nonce,
	        };

	        //Do Ajax request
	        $.post( ajax_url, data, function(response) {
	        	
	        	submit.html('Export Report');
	        	
	        	if(response) {
	        		response = JSON.parse(response);
	        		if(response.errors) {
		        		notice.show();
		        		notice.addClass('notice-error');
		        		noticeMessage.html(response.errors);
	        		} else {
	        			if(response.fileURL) {
	        				fileDL.attr('src', response.fileURL);
	        			}
	        		}
	        	}
	        });
		}
	});
	
	// Export products
	$('#admin-export-products #product-date-range-from').datepicker();
	$('#admin-export-products #product-date-range-to').datepicker();
	$('#admin-export-products').validate({
		submitHandler: function(form) {
			if($.preventDefault) {
				$.preventDefault();
			} else {
				$.returnValue = false;
			}

        	var submit = $(form).find('button[type="submit"]');
        	var fileDL = $(form).find('.file-download');
        	var notice = $(form).find('.notice');
        	var noticeMessage = $(notice).find('.notice-message');

        	submit.html('Exporting <i class="fa fa-spinner fa-spin"></i>');

	        var ajax_url = admin_forms_data.admin_ajax_url;

	        var dateRangeFrom = $('#product-date-range-from').val();
	        var dateRangeTo = $('#product-date-range-to').val();

	        var nonce = $('#export-products').val();

	        // Data to send
	        data = {
	         	action: 'admin_export_products',
	         	date_range_from: dateRangeFrom,
	         	date_range_to: dateRangeTo,
	         	nonce: nonce,
	        };

	        //Do Ajax request
	        $.post( ajax_url, data, function(response) {
	        	
	        	submit.html('Export Products');
	        	
	        	if(response) {
	        		response = JSON.parse(response);
	        		if(response.errors) {
		        		notice.show();
		        		notice.addClass('notice-error');
		        		noticeMessage.html(response.errors);
	        		} else {
	        			if(response.fileURL) {
	        				fileDL.attr('src', response.fileURL);
	        			}
	        		}
	        	}
	        });
		}
	});

	// Export orders
	$('#admin-export-orders #campaign-select').select2({
		tags: true,
		tokenSeparators: [',', ' '],
	});
	$('#admin-export-orders #order-date-range-from').datepicker();
	$('#admin-export-orders #order-date-range-to').datepicker();
	$('#admin-export-orders').validate({
		submitHandler: function(form) {
			if($.preventDefault) {
				$.preventDefault();
			} else {
				$.returnValue = false;
			}

        	var submit = $(form).find('button[type="submit"]');
        	var fileDL = $(form).find('.file-download');
        	var notice = $(form).find('.notice');
        	var noticeMessage = $(notice).find('.notice-message');

        	submit.html('Exporting <i class="fa fa-spinner fa-spin"></i>');

	        var ajax_url = admin_forms_data.admin_ajax_url;

	        var selectedCampaigns = [];
	        $(form).find('#campaign-select option:selected').each(function(){
	        	selectedCampaigns.push($(this).val());
	        });
	        var dateRangeFrom = $('#order-date-range-from').val();
	        var dateRangeTo = $('#order-date-range-to').val();

	        var nonce = $('#export-orders').val();

	        // Data to send
	        data = {
	         	action: 'admin_export_orders',
	         	nonce: nonce,
	         	selected_campaigns: selectedCampaigns,
	         	date_range_from: dateRangeFrom,
	         	date_range_to: dateRangeTo,
	        };

	        // Do Ajax request
	        $.post( ajax_url, data, function(response) {
	        	
	        	submit.html('Export Orders');
	        	
	        	if(response) {
	        		response = JSON.parse(response);
	        		if(response.errors) {
		        		notice.show();
		        		notice.addClass('notice-error');
		        		noticeMessage.html(response.errors);
	        		} else {
	        			if(response.fileURL) {
	        				fileDL.attr('src', response.fileURL);
	        			}
	        		}
	        	}
	        });
		}
	});

	var authentiqueUsers = new Array();

	var authentiqueCount = 0;

	// Check authentique rating
	$('#admin-authentique-check').validate({
		submitHandler: function(form) {
			if($.preventDefault) {
				$.preventDefault();
			} else {
				$.returnValue = false;
			}

        	var submit = $(form).find('button[type="submit"]');
        	var notice = $(form).find('.notice');
        	var noticeMessage = $(notice).find('.notice-message');

        	submit.html('Processing <i class="fa fa-spinner fa-spin"></i>');

	        var ajax_url = admin_forms_data.admin_ajax_url;

	        var nonce = $('#authentique-check-nonce').val();

	        // Data to send
	        data = {
	         	action: 'admin_authentique_get_users',
	         	nonce: nonce,
	        };

	        //Do Ajax request
	        $.post( ajax_url, data, function(response) {
	        	
	        	submit.html('Check Authentique');
	        	
	        	if(response) {
	        		response = JSON.parse(response);
	        		if(response.errors) {
		        		notice.show();
		        		notice.addClass('notice-error');
		        		noticeMessage.html(response.errors);
	        		} else {
	        			if(response.users) {
	        				authentiqueUsers = response.users;
	        				authentiqueCount = 0;
	        				authentiqueProcessUsers();
	        			}
	        		}
	        	}
	        });
		}
	});

	function authentiqueProcessUsers() {

		var ajax_url = admin_forms_data.admin_ajax_url;
        	
    	$.post( ajax_url, {action: 'admin_authentique_check', user: authentiqueUsers[authentiqueCount]}, function(response){
    		if(response) {
        		response = JSON.parse(response);
    			if(!response.errors) {

    			} else {
    				console.log( 'error encountered for: ' + authentiqueUsers[authentiqueCount] );
    				console.log( response.errors );
    			}

    			if( authentiqueCount % 100 == 0 ) {

    				console.log( authentiqueUsers.length - authentiqueCount + ' users remaining' );
    			}

    			if( authentiqueUsers.length - 1 > authentiqueCount ) {

	    			authentiqueCount ++;
	    			authentiqueProcessUsers();
    			}

    		}
    	});
	}

	$('#admin-email-check').validate({
		submitHandler: function(form) {
			if($.preventDefault) {
				$.preventDefault();
			} else {
				$.returnValue = false;
			}

    	var submit = $(form).find('button[type="submit"]');
    	var notice = $(form).find('.notice');
    	var noticeMessage = $(notice).find('.notice-message');

    	var userEmail = $('#test-user-email').val();
    	var fromEmail = $('#test-email-from').val();
    	var emailSubject = $('#test-email-subject').val();
    	var emailBody = $('#test-email-body').val();

    	submit.html('Processing <i class="fa fa-spinner fa-spin"></i>');

      var ajax_url = admin_forms_data.admin_ajax_url;

      var nonce = $('#email-check-nonce').val();

      // Data to send
      data = {
       	action: 'admin_test_email',
       	nonce: nonce,
       	user_email: userEmail,
       	from_email: fromEmail,
       	email_subject: emailSubject,
       	email_body: emailBody,
      };

      //Do Ajax request
      $.post( ajax_url, data, function(response) {
      	
      	submit.html('Send Email');
      	
      	if(response) {
      		response = JSON.parse(response);
      		if(response.errors) {
        		notice.show();
        		notice.addClass('notice-error');
        		noticeMessage.html(response.errors);
      		} else {
        		notice.show();
        		notice.addClass('notice-success');
        		noticeMessage.html('Email has been sent!');
      		}
      	}
      });
		}
	});

	if($('#campaign-countries-select').length) {
		var ajax_url = admin_forms_data.admin_ajax_url;
		
		$(document).on('change', '#campaign-countries-select .acf-input select', function(e){

			// the reason we set a seperate function for this is 
			// that "this" has no real meaning in an anonymous 
			// function as each call is a new JS object and we need "this" in order 
			// to be to abort previous AJAX requests
			update_regions_on_country_change(e, $);
		});
	}
});

function update_regions_on_country_change(e, $) {
	if(this.request) {
		// if a recent request has been made abort it
		this.request.abort();
	}

	// get the region select field and remove all existing choices
	var region_select = $('#campaign-regions-select .acf-input select');
	region_select.empty();

	// get the target of the event and then get the value of that field
	var target = $(e.target);
	var countries = target.val();
	var includeExclude = $('#campaign-countries-include-exclude .acf-input input[type="radio"]:checked').val();

	if(!countries) {
		return;
	}

	var data = {
		action: 'load_regions_select_choices',
		countries: countries,
		include_exclude: includeExclude,
	}

	data = acf.prepareForAjax(data);

	this.request = $.ajax({
		url: acf.get('ajaxurl'),
		data: data,
		type: 'post',
		dataType: 'json',
		success: function(json) {
			if(!json) {
				return;
			}

			for(i=0; i<json.length; i++) {
				var region_item = '<option value="'+json[i]['country_code']+json[i]['state_code']+'">'+json[i]['state_name']+'</option>';
				region_select.append(region_item);
			}
		},
		error: function(jqxhr, status, exception) {
			console.log(jqxhr);
			console.log(status);
			console.log(exception);
		}
	});
}