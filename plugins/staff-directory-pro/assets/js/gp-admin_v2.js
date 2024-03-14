var gold_plugins_init_upgrade_slideshow = function () {
	$ = jQuery;
	var slideCount = $('.gp_slideshow ul li').length;
	var slideWidth = $('.gp_slideshow ul li').width();
	var slideHeight = $('.gp_slideshow ul li').height();
	var sliderUlWidth = slideCount * slideWidth;
	
	$('.gp_slideshow').css({ width: slideWidth, height: slideHeight });
	
	$('.gp_slideshow ul').css({ width: sliderUlWidth, marginLeft: - slideWidth });
	
    $('.gp_slideshow ul li:last-child').prependTo('.gp_slideshow ul');

    function moveLeft() {
        $('.gp_slideshow ul').animate({
            left: + slideWidth
        }, 200, function () {
            $('.gp_slideshow ul li:last-child').prependTo('.gp_slideshow ul');
            $('.gp_slideshow ul').css('left', '');
        });
    };

    function moveRight() {
        $('.gp_slideshow ul').animate({
            left: - slideWidth
        }, 200, function () {
            $('.gp_slideshow ul li:first-child').appendTo('.gp_slideshow ul');
            $('.gp_slideshow ul').css('left', '');
        });
    };

    $('a.control_prev').click(function () {
        moveLeft();
    });

    $('a.control_next').click(function () {
        moveRight();
    });
	
};


var cd_gold_plugins_init_coupon_box = function () {
	var $form = jQuery('#mc-embedded-subscribe-form');
	if ($form.length > 0) {
	
		var btn = $form.find('input[type="submit"]');
		
		//if already subscribed, cut to the chase
		if ( (jQuery('#gold_plugins_already_subscribed').val() == 1) ) {
			gold_plugins_ml_ajax_success($form, btn, 0);
		}
	
		// bind to form's submit action to reveal coupon box
		$form.bind('submit', function () {
			
			var btn = jQuery(this).find('input[type="submit"]');
			btn.val('Sending Now...');
			var $ajax_url = 'https://goldplugins.com/list-manage/ajax.php';
			var $ajax_data = $form.serialize();
			jQuery.ajax(
			{
				url: $ajax_url,
				data: $ajax_data,
				dataType: 'jsonp',
				success: function (dat) {
					setTimeout(function () {
						gold_plugins_ml_ajax_success($form, btn);
						// tell wordpress to always show the "after" state from now on
						setUserSetting( '_gp_ml_has_subscribed', '1' );
					}, 300);
				},
				error: function () {
					// reset the box, so they can at least try again
					setUserSetting( '_gp_ml_has_subscribed', '0' );
				}
			});
			
			// stop the form's normal submit process
			return false;
		});
	}
};

var gold_plugins_ml_ajax_success = function ($form, btn, speed) {
	if(typeof(speed) == 'undefined') {
		speed = 400;
	}
	$form.find('.fields_wrapper').slideUp(speed);
	$cpn_box = gold_plugins_get_coupon_box_new();
	btn.val('Coupon sent!');
	$cpn_box.fadeIn((speed * 2));
	btn.after($cpn_box);
};

var gold_plugins_get_coupon_box_new = function () {
	var coupon_html = 
	'<div id="mc-show-coupon-codes" class="modern">' + 
		'<h3>Your Coupon Code Is On The Way!</h3>' +
		'<p class="thx">We\'ve sent you an email with your coupon code for 10% off @plugin_name! If you don\'t see it within a few minutes, you might want to look in your Junk Mail folder.</p>' + 
		'<h4><strong>Ready to buy now?</strong></h4>' +
		'<p class="thx">If you\'re ready to buy now, <a href="@personal_url" target="_blank">click here to visit the pricing page</a>. Your coupon code will be applied automatically.</p>' +
	'</div>';
	
	// replace links in the HTML before inserting it
	$plugin_name = jQuery('#mc-upgrade-plugin-name').val();
	$personal_url = jQuery('#mc-upgrade-link-per').val();
	$biz_url = jQuery('#mc-upgrade-link-biz').val();
	$dev_url = jQuery('#mc-upgrade-link-dev').val();
	coupon_html = coupon_html.replace(/@plugin_name/g, $plugin_name);
	coupon_html = coupon_html.replace(/@personal_url/g, $personal_url);
	coupon_html = coupon_html.replace(/@biz_url/g, $biz_url);
	coupon_html = coupon_html.replace(/@dev_url/g, $dev_url);						
	var coupon_div = jQuery(coupon_html);

	// make the whole buttons clickable
	coupon_div.on('click', '.upgrade_link', function (e) {
		if( !jQuery("a").is(e.target) ) {
			$href = jQuery(this).find('a:first').attr('href');
			// try to open in a new tab
			window.open(
			  $href,
			  '_blank'
			);
			return false;			
		}
		return true;
	});	
	return coupon_div;
};

/* Staff Grid Widgets */
/* 
 * Call this function inline from the Staff Grid widget's HTML to intialize its 
 * custom controls. This is an admin function.
 */
var gp_init_staff_grid_widgets = function(form_id)
{
	/* Looks for text inputs with the color_picker class inside parent_form,
	 * and converts them to wpColorPickers. Saves the original text input's HTML
	 * as input_html data property, so that it can be restored if needed.
	 *
	 * @param parent_form Form element to search inside for colorpicker inputs.
	 */
	var setup_colorpickers = function(parent_form)
	{
		if ( typeof(jQuery().wpColorPicker) != 'function' ) {
			return;
		}
		
		var colorpickers = jQuery('.color_picker', parent_form);	
		if ( colorpickers.length == 0 ) {
			return;
		}
		
		var handle_update_color_input = function (e, ui) {
			var input = this;
			
			// note: can't trigger change right away or it won't work,
			// so delay the refresh just a bit
			setTimeout( function () {
					jQuery(input).trigger('change');
					if ( typeof(wp.customize) != 'undefined' ) {
						wp.customize.previewer.refresh();
					}
			}, 100 );
			return true;
		};
		
		colorpickers.each( function () {
			if ( jQuery(this).data('hasIris') ) {
				return;
			}

			// locate the target text input and convert to jQ object
			var input = jQuery.find('input[type="text"]:first', this);
			input = jQuery( input );
			
			// save the old HTML of the text input so we can restore it
			var input_html = input.prop('outerHTML');
			jQuery(this).data('input_html', input_html);
			
			// convert input to color picker. this removes the old 
			// input element
			input.wpColorPicker({
				hide: true,
				change: handle_update_color_input
			});
			
			// prevent double initialization
			jQuery(this).data('hasIris', 1);
		});
	};

	/* Replaces all wpColorPickers in the form with their original text inputs.
	 * The original input's HTML must have been stored as a data attribute, 
	 * input_html (e.g., by the setup_colorpickers function) in order to be 
	 * successfully. 
	 *
	 * @param parent_form Form element to search inside for colorpicker inputs.
	 */
	var remove_colorpickers = function(parent_form)
	{
		if ( typeof(jQuery().wpColorPicker) != 'function' ) {
			return;
		}
		
		var colorpickers = jQuery('.color_picker', parent_form);	
		if ( colorpickers.length == 0 ) {
			return;
		}
		
		colorpickers.each( function () {
			if ( !jQuery(this).data('hasIris') ) {
				return;
			}
			var color_picker = jQuery( jQuery.find('.wp-picker-container', this) );
			var input = jQuery( jQuery.find('input[type="text"]:first', this) );
			var color = input.wpColorPicker('color');
			
			// replace wpColorPicker with a text box, using the original
			// which we've stored in the colorpicker's data property
			var new_input = jQuery( jQuery(this).data('input_html') );
			new_input.val(color);
			new_input.insertBefore(color_picker);
			
			// remove the color picker
			color_picker.remove();
			jQuery(this).data('hasIris', 0);
		});
	};


	/* 
	 * Sets up triggers to create/destroy colorpickers when needed.
	 *
	 * After Janus panels in the staff grid widget are opened, init any colorpickers.
	 * Then, before the Janus panel is closed, replace color pickers with their 
	 * original text inputs.
	 */
	var setup_all_colorpickers = function(gp_forms)
	{
		// setup Janus triggers to add/remove color pickers when the panel 
		// is hidden/shown. wpColorPicker acts up if we don't remove them
		// while the panel is closed
		gp_forms.on('janus_after_open', 'fieldset', function (e) {				
			setup_colorpickers(e.target)
			jQuery(e.target).css('height', 'auto');
		});
		gp_forms.on('janus_before_close', 'fieldset', function (e) {				
			// replace colorpickers with their original text inputs
			remove_colorpickers(e.target);
		});
	};

	var trigger_dependent_field_update = function(field, trigger, is_checked)
	{
		field.trigger('dependent_field_changed', {
			trigger: trigger,
			is_checked: is_checked
		});		
	}

	/* Shows or hides a dependent field depending on whether its 
	 * paired radio button is checked.
	 *
	 * @param field Element to show or hide (any element works)
	 * @param trigger Radio button to check
	 */
	var update_dependent_field = function (field, trigger) {
		// exit if invalid trigger was specified
		if ( trigger.length == 0 ) {
			return;
		}
		
		var is_checked = trigger.is(':checked');
		trigger_dependent_field_update(field, trigger, is_checked);		

		// show the field if trigger is checked, or hide it if not
		if ( is_checked && !field.is(':visible') ) {
			field.slideDown();
		}
		else if ( !is_checked && field.is(':visible') ) {
			field.slideUp();
		}
	};

	/* Add event bindings to make dependent checkboxes work. 
	 * Dependent checkboxes are only visible when theircorresponding 
	 * radio button (the trigger) is checked. The trigger is in 
	 * indicated with the data-trigger attribute on checkbox, which must 
	 * contain a valid jQuery selector to a radio button 
	 * (else nothing will happen).
	 *
	 * @param checkbox Element to show or hide (any element works)
	 * @param trigger parent widget containing the checkbox and trigger
	 */
	var setup_dependent_field = function (checkbox, widget) {
		checkbox = jQuery( checkbox );				
		// if checkbox is already initialized, exit
		if ( checkbox.data('d_c_initialized') == 1 ) {
			return;
		}

		// make sure trigger exists and is a radio button
		if ( checkbox.data('trigger') ) {
			var trigger = jQuery( checkbox.data('trigger'), widget );
			
			// trigger must exist and be a radio button
			if ( trigger.length == 0 || !trigger.is(':radio') ) {
				return;
			}					
		} else {
			// no trigger specified on checkbox
			return;
		}
		
		// note: we're attaching to the parent fieldset, so that we
		// can respond to clicks on any radio button inside the fieldset 
		jQuery(trigger).parents('fieldset:first').on('change', 'input[type="radio"]', function () {
			// NOTE: Delay one tick before we call this function,
			// otherwise the radio button's checked val will still 
			// reflect what it was *before* the event, whereas we
			// need the radio button's checked val *after* the event
			setTimeout( function () {
				update_dependent_field(checkbox, trigger);
			}, 1);
		});
		
		// show/hide the checkbox now, based on the initial state
		update_dependent_field(checkbox, trigger);
		
		// update the checkbox state when janus panels are opened
		jQuery(widget).on('janus_after_open', function () {
			update_dependent_field(checkbox, trigger);
		});

		// mark this checkbox as already initialized
		checkbox.data('d_c_initialized', 1 );		
	};

	var setup_all_dependent_fields = function (widget) {
		// find and initialize all dependent checkboxes
		var dependent_fields = jQuery('.dependent_field', widget);
		dependent_fields.each(function () {
			setup_dependent_field(this, widget);
		});
	};
	
	parent_form = jQuery(form_id);	
	setup_all_colorpickers(parent_form);
	setup_all_dependent_fields(parent_form);	

	
	parent_form.on('dependent_field_changed', '.text_animation_dependent_field', function (e, data) {
		var my_checkbox = jQuery('input[type="checkbox"]', this);
		if( data.is_checked ) {
			my_checkbox.attr('data-shortcode-value-if-unchecked', '0');
			my_checkbox.removeAttr('data-shortcode-hidden');
		} else {
			my_checkbox.removeAttr('data-shortcode-value-if-unchecked');
			my_checkbox.attr('data-shortcode-hidden', '1');	
		}
	});	
};


/* Staff Table Widgets */
/* 
 * Call this function inline from the Staff Table widget's HTML to intialize its 
 * custom controls. This is an admin function.
 */
var gp_init_staff_table_widgets = function(form_id)
{
	var filter_prototype_elements = function(list)
	{
		return list.filter(function (index) {
			me = jQuery(this);
			var my_name = me.attr('name');
			var is_real = (my_name.indexOf('__i__') == -1);
			return is_real;
		});	
	};
	
	var build_list_from_inputs = function (inputs)
	{
		var str_list = '';
		inputs.each(function () {
			if (str_list.length > 0) {
				str_list += ',';
			}
			str_list += get_input_col_name( jQuery(this) );
		});
		return str_list;		
	};
	
	var update_hidden_inputs = function (form)
	{
		var hidden_input_columns = jQuery(form).find('.staff_table_columns_input');
		//hidden_input_columns = filter_prototype_elements(hidden_input_columns);

		var hidden_input_sort_order = jQuery(form).find('.staff_table_sort_order_input');
		//hidden_input_sort_order = filter_prototype_elements(hidden_input_sort_order);
		
		var all_checkboxes = jQuery(form).find('input[type="checkbox"]');
		//all_checkboxes = filter_prototype_elements(all_checkboxes);

		var checked_inputs = jQuery(form).find('input[type="checkbox"]:checked');
		//checked_inputs = filter_prototype_elements(checked_inputs);
		
		/* build comma seperated list of checkbox order display */
		var str_sort_order = build_list_from_inputs(all_checkboxes);		

		/* build comma seperated list of columns to display */
		var str_columns = build_list_from_inputs(checked_inputs);		
		
		hidden_input_columns.val(str_columns);
		hidden_input_columns.attr('value', str_columns);		

		hidden_input_sort_order.val(str_sort_order);
		hidden_input_sort_order.attr('value', str_sort_order);
		
		jQuery(form).find('input[type="checkbox"]').each( function () {
			jQuery(this).trigger('change');
		} ) ;
	};

	var get_input_col_name = function (input)
	{
		var old_name = input.attr('name');
		var override_name = input.data('shortcode-key');
		var real_name = '';
		var rgx = /\[[0-9]\]/;
		
		if (override_name) {
			real_name = override_name;
		} else {
			var pos = old_name.indexOf('[__i__]');			
			if (pos > 0) {
				var real_name = old_name.substr(pos + 8);
				real_name = real_name.substr(0, real_name.length - 1); // chip off trailing '['
			} else if (rgx.test(old_name)) {
				var matches = old_name.match(rgx);
				var first_match = matches[0];
				var real_name = old_name.substr( old_name.indexOf(first_match) + first_match.length + 1 );
				real_name = real_name.substr(0, real_name.length - 1); // chip off trailing '['
			}
		}
		
		// remove show_
		var pos = real_name.indexOf('show_');
		if (pos > -1) {
			var real_name = real_name.substr(pos + 5);
			real_name = real_name.substr(0, real_name.length);
		}			
		return real_name;
	};

	var make_fields_sortable = function(fieldset, parent_form)
	{
		if ( typeof(jQuery().sortable) != 'function' ) {
			return;
		}
		jQuery(fieldset).sortable({
			'axis' : 'y',
			'items' : '.sortable',
			'update' : function () {
				update_hidden_inputs(parent_form);
				setTimeout( function () {
					if (typeof(wp.customize) !== 'undefined') {
						wp.customize.previewer.refresh();
					}
				}, 1);
			}
		});
	};
	
	
	var parent_form = jQuery(form_id);
	var fieldset = parent_form.find('.staff_table_fields_to_display');
	fieldset.on('click', 'input[type="checkbox"]', function () {				
		// update columns hidden field to convey the correct field order
		update_hidden_inputs(parent_form);
	});
	update_hidden_inputs(parent_form);	
	make_fields_sortable(fieldset, parent_form);
};
