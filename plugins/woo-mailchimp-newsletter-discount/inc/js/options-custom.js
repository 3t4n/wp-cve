/**
 * Custom scripts needed for the colorpicker, image button selectors,
 * and navigation tabs.
 */

 jQuery(document).ready(function($) {

	//select2
	$('.wcmnd-select2').select2();

	//Chart js
	var DaysArr = [];
	var DemoData = [];

	var VisitsData = jQuery('#analyticsData').val();
	var AnalyticsData = jQuery.parseJSON(VisitsData);

  	var getDaysInMonth = function( month, year ) {

  		const monthNames = [
	  		"January",
	  		"February",
	  		"March",
	  		"April",
	  		"May",
	  		"June",
	  		"July",
	  		"August",
	  		"September",
	  		"October",
	  		"November",
	  		"December"
  		];

			const d = new Date();
    	var days = new Date(year, month, 0).getDate();
    	var MonthName = monthNames[d.getMonth()];

    	if( days > 0 ) {
      		for (var i = 1; i <= days; i++) {
        	DaysArr.push(MonthName+' '+i);
        	DemoData.push(Math.floor(Math.random() * 11));
      	}
    	}
    	return DaysArr;
  	};

  	var Year = new Date().getFullYear();
  	var d = new Date();
  	var n = d.getMonth() + 1;

  	var progress = document.getElementById('animationProgress');
  	

	$(".ajax_products").find('.wcmnd-select2').select2({
		minimumInputLength: $( this ).data( 'minimum_input_length' ) ? $( this ).data( 'minimum_input_length' ) : '3',
		ajax: {
			url: wcmndOption.ajax_url,
			dataType: 'json',
			quietMillis: 250,
			data: function (params) {
				return {
        	 q: params.term, // search query
        	 action: 'wcmnd_ajax_products' // AJAX action for admin-ajax.php
        	};
        },

        processResults: function( data ) {
        	var terms = [];
        	if ( data ) {
        		$.each( data, function( id, text ) {
        			terms.push( { id: id, text: text } );
        		});
        	}
        	return {
        		results: terms
        	};
        },
        cache: true
    },
});

	// Loads the color pickers
	$('.of-color').wpColorPicker();

	// Image Options
	$('.of-radio-img-img').click(function(){
		$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
		$(this).addClass('of-radio-img-selected');
	});

	$('.of-radio-img-label').hide();
	$('.of-radio-img-img').show();
	$('.of-radio-img-radio').hide();


	// Loads tabbed sections if they exist
	if ( $('.nav-tab-wrapper').length ) {
		options_framework_tabs();
	}

	function options_framework_tabs() {

		var $group = $('.group'),
		$navtabs = $('.nav-tab-wrapper a'),
		active_tab = '';

		// Hides all the .group sections to start
		$group.hide();

		// Find if a selected tab is saved in localStorage
		if ( typeof(localStorage) != 'undefined' ) {
			active_tab = localStorage.getItem('active_tab');
		}

		// If active tab is saved and exists, load it's .group
		if ( active_tab != '' && $(active_tab).length ) {
			$(active_tab).fadeIn();
			$(active_tab + '-tab').addClass('nav-tab-active');
		} else {
			$('.group:first').fadeIn();
			$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
		}

		// Bind tabs clicks
		$navtabs.click(function(e) {

			e.preventDefault();

			// Remove active class from all tabs
			$navtabs.removeClass('nav-tab-active');

			$(this).addClass('nav-tab-active').blur();

			if (typeof(localStorage) != 'undefined' ) {
				localStorage.setItem('active_tab', $(this).attr('href') );
			}

			var selected = $(this).attr('href');

			$group.hide();
			$(selected).fadeIn();

		});
	}

	//Fetch Mailchimp List in Admin Section
	$('body').on('click', '#section-mailchimp_list_id a', function(e) {
		e.preventDefault();
		var SelectedLink = $(this);
		var MailchimpAPIKey = SelectedLink.parents('.group.mailchimpconfiguration').find('input#mailchimp_key').val();
		var ListSelected = SelectedLink.parents('#section-mailchimp_list_id').find('#mailchimp_list_id');

		if( MailchimpAPIKey == '' ) {
			alert(wcmndOption.mailchimp_api_key_missing);
		}
		else {
			SelectedLink.text(wcmndOption.please_wait);
		}

		if( MailchimpAPIKey !== '' ) {
			$.post(
				wcmndOption.ajax_url,
				{ mailchimp_api_key: MailchimpAPIKey, action: 'get_mailchimp_lists' },
				function(data) {
					if(data) {
						var response = jQuery.parseJSON(data);
						var Html = '<option value="select_opt">Select Option</option>';

						if( response !== '' ) {
							$.each(response, function(index, element) {
								Html += '<option value="'+element.list_id+'">'+element.list_name+'</option>'
							});
						}

						ListSelected.find('option').remove();
						ListSelected.append(Html);
						SelectedLink.text('here');
					}
				});
		}
	});

	var ProHtml = '<div class="wpr-pro-block"><span><a target="_blank" href="https://zetamatic.com/downloads/woocommerce-mailchimp-newsletter-discount/?utm_src=woo-mailchimp-newsletter-discount/">Upgrade to PRO to use this option</a></span></div>';

	$('#wcmnd_optionsframework .pro-feature').append(ProHtml);

	$('.pro-feature').hover(function() {
		var WrapperHeight = $(this).find('div.wpr-pro-block').innerHeight();
		var MiddelPos = (WrapperHeight/2)-30;
		$(this).find('.wpr-pro-block span').css('top', MiddelPos + 'px');
		$(this).find('.wpr-pro-block').toggleClass('show');
	});

  var aceEditor = document.getElementById('ace_editor');

  var editor = ace.edit(aceEditor);
  editor.session.setMode("ace/mode/css");
  editor.setTheme("ace/theme/monokai");

  var textarea = $('textarea[name="wcmnd_options[ace_editor]"]');
  editor.getSession().on("change", function () {
    sessionValue = editor.getSession().getValue();
    textarea.val(sessionValue);
  });

  $('div#section-ace-editor').find('.ace_editor').css('height', '250px');
  $('div#section-ace_editor').find('.ace_editor').css('width', '440px');


});
