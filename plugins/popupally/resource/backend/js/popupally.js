/* global popupally_default_code, popupally_data_object, wp, popupally_jscolor */

jQuery(document).ready(function($) {
	var popupally_pro_selected_tab = $('#selected-tab'),
		popupally_pro_follow_scroll = $(),
		popupally_pro_wait_overlay = $('#popupally-import-wait-overlay');

	function serialize_form_values($parent_form) {
		$('.popupally-option-setting-form[serialize-target]').each(function() {
			var $this = $(this),
				target = $this.attr('serialize-target'),
				new_form = $("<form></form>");
			$this.children().clone().appendTo(new_form);
			$('.' + target).val(JSON.stringify(new_form.serializeArray()));
		});
	}
	$('.popupally-setting-submit-button').on('click', function(e) {
		var error_elements = $('.template-customization-block.template-customization-block-active').find('[html-code-source]:not(:empty)');
		if (error_elements.length > 0) {
			var key = 0, popup_id = null, element_name = null,
				error_text = "Potential HTML error detected in the following elements:\n",
				max_error = Math.min(3, error_elements.length);
			for (key = 0; key < max_error; ++key) {
				popup_id = error_elements[key].getAttribute('popup-id');
				element_name = error_elements[key].getAttribute('html-code-source');
				error_text += '- Popup #' + popup_id + ' [' + element_name + "]\n";
			}
			if (max_error < error_elements.length) {
				error_text += 'and ' + (error_elements.length - max_error) + " more\n";
			}
			error_text += "\nMalformed HTML code might affect the entire page layout. Do you want to continue?";
			var conf = confirm(error_text);
			if(conf !== true){
				return false;
			}
		}
		popupally_pro_wait_overlay.show();
		serialize_form_values($(this).parents('form'));
		return true;
	});
	function resize_follow_scroll() {
		popupally_pro_follow_scroll.resize();
	}
	$(document).on('change propertychange', '[input-all-false-check]', function(e) {
		var selector = $(this).attr('input-all-false-check'),
		is_checked = $('[input-all-false-check=' + selector + ']:checked').length > 0;
		if (is_checked) {
			$('.' + selector).show();
		} else {
			$('.' + selector).hide();
		}
		resize_follow_scroll();
	});
	function process_input_field($parent, input, hidden_inputs, text_inputs, current_count, prefix) {
		var $input = $(input),
		input_name =$input.attr('name'),
		input_type = $input.attr('type'),
		input_value = $input.val(),
		variable_name = '';

		if (typeof input_value === typeof undefined || input_value === false) {
			input_value = '';
		}
		if (input_name) {
			current_count += 1;
			if (input_type === 'hidden') {
				variable_name = 'hidden-form-fields';
				hidden_inputs.push(input_name);
			} else {
				variable_name = 'other-form-fields';
				text_inputs.push(input_name);
			}
			$parent.before($(prefix + variable_name + '-name][' + current_count + ']"/>').val(input_name));
			$parent.before($(prefix + variable_name + '-value][' + current_count + ']"/>').val(input_value));
		}
		return current_count;
	}
	function process_select_field($parent, input, result_array, current_count, prefix, id, variable_name) {
		var $input = $(input),
		input_name =$input.attr('name'),
		input_value = $input.html();

		if (input_name) {
			current_count += 1;
			$parent.before($(prefix + variable_name + '-name][' + current_count + ']"/>').val(input_name));
			$parent.before($(prefix + variable_name + '-value][' + current_count + ']"/>').val(input_value).attr('id', variable_name + '-' + id + '-' + input_name));
			result_array.push(input_name);
		}
		return current_count;
	}
	function search_field_array_for_string(source, target_string) {
		var i = 0, lowercase = '', len = source.length;
		for (; i<len; ++i) {
			lowercase = source[i].toLowerCase();

			if(-1 < lowercase.indexOf(target_string)) {
				return source[i];
			}
		}
		return '';
	}
	function generate_field_array_option_list(source) {
		var i = 0, len = source.length, result = $('<select></select>'), val;
		for (; i<len; ++i) {
			result.append($('<option></option>').attr('value', source[i]).text(source[i]));
		}
		return result.html();
	}
	function select_option_value($select, value) {
		var sel = $select.find('option[value="' + value + '"]');
		$select.children().not(sel).removeAttr("selected");
		sel.attr("selected", "selected");
		return $select;
	}
	function populate_input_selection_list(selection_elements, option_list, email_input_name, name_input_name) {
		var i = 0, len = selection_elements.length, $elem = null, previous_value = null, splash_field = null, element_name,
			options = generate_field_array_option_list(option_list);
		for (; i<len; ++i) {
			$elem = $(selection_elements[i]);
			previous_value = $elem.val();
			$elem.empty().append(options);
			
			element_name = $elem.attr('name');

			if('' !== previous_value && -1 < $.inArray(previous_value, option_list)) {
				select_option_value($elem, previous_value).change();
			} else {
				splash_field = $elem.attr('sign-up-form-field');
				if('email' === splash_field && '' !== email_input_name) {
					$elem.val(email_input_name).change();
				} else if('name' === splash_field && '' !== name_input_name) {
					$elem.val(name_input_name).change();
				}
			}
		}
	}
	function reset_sign_up_form_related_fields(form_action_element, form_method_element, text_select_element, form_valid_element) {
		form_action_element.val('');
		form_method_element.val('get');
		text_select_element.empty().append('<option value=""></option>');
		form_valid_element.val('false').change();
	}
	$(document).on('change', '.sign-up-form-raw-html', function(e) {
		var $this = $(this),
		id = $this.attr('popup-id'),
		$error = $('#sign-form-error-' + id),
		$parent = $this.parent(),
		form_code = $.trim($this.val()),
		form_method_element = $('#sign-up-form-method-' + id),
		form_action_element = $('#sign-up-form-action-' + id),
		form_valid_element = $('#sign-up-form-valid-' + id),
		text_select_element = $('.sign-up-form-select-' + id),
		$parsed_form = null;

		$error.hide();
		$('.sign-up-form-generated-' + id).remove();
		if('' === form_code) {
			reset_sign_up_form_related_fields(form_action_element, form_method_element, text_select_element, form_valid_element)
			return;
		}
		try{
			$parsed_form = $(form_code);
		}catch(e){
			$error.show().text('Invalide form code. Please copy the entire HTML code block from your mailing list provider into the Sign-up form HTML field.');

			reset_sign_up_form_related_fields(form_action_element, form_method_element, text_select_element, form_valid_element)
			return;
		}

		var $form = $parsed_form.find('form');
		if(0 === $form.length) {
			$form = $parsed_form.filter('form');
		}
		if(0 === $form.length) {
			$error.show().text('A <form> element could not be found in the Sign-up form HTML Code you entered. Please copy the entire HTML code block from your mailing list provider into the Sign-up form HTML field.');

			reset_sign_up_form_related_fields(form_action_element, form_method_element, text_select_element, form_valid_element)
			return;
		}
		if($form.length > 1) {
			$error.show().text('More than one form section is found. Only the first one will be used.');
			$form = $($form[0]);
		}
		form_valid_element.val('true').change();
		var form_method = $form.attr('method');
		if (typeof form_method === typeof undefined || form_method === false) {
			form_method = 'get';
		}
		form_method_element.val(form_method);
		form_action_element.val($form.attr('action'));

		var text_inputs = [''], hidden_inputs = [''],
		email_input_name = '', name_input_name = '',
		count = 0,
		prefix = '<input class="sign-up-form-generated-' + id + '" type="hidden" name="[' + id + '][';

		$form.find('input[type!="submit"]').each(function(index, input) {
			count = process_input_field($parent, input, hidden_inputs, text_inputs, count, prefix);
		});
		$form.find('textarea').each(function(index, input) {
			count = process_input_field($parent, input, hidden_inputs, text_inputs, count, prefix);
		});
		email_input_name = search_field_array_for_string(text_inputs, 'email');
		name_input_name = search_field_array_for_string(text_inputs, 'name');
		populate_input_selection_list(text_select_element, text_inputs, email_input_name, name_input_name);
	});
	function adjust_follow_scroll_window_location($elem, window_view_top, window_view_bottom) {
		var $parent = $elem.parent(),
			parent_top = $parent.offset().top,
			parent_height = $parent.height(),
			parent_width = $parent.width(),
			parent_bottom = parent_top + parent_height,
			elem_height = $elem.height() + 30,
			offset = 0,
			step_aside = $elem.hasClass('step-aside');
		if (!(parent_top > window_view_bottom || parent_bottom < window_view_top)) {
			parent_top = parent_top - 30;
			offset = Math.min(Math.max(0, window_view_top - parent_top), parent_height - elem_height);
			$elem.css('margin-top', offset);
			if (step_aside) {
				if (offset > 0) {
					$elem.css('margin-left', parent_width+50);
				} else {
					$elem.css('margin-left', 0);
				}
			} else {
				$elem.css('margin-left', 0);
			}
		}
	}
	$(window).on('scroll', function(e) {
		var window_view_top = $(window).scrollTop(),
			window_view_bottom = window_view_top + $(window).height();
		if (popupally_pro_follow_scroll){
			popupally_pro_follow_scroll.each(function(index, elem){
				adjust_follow_scroll_window_location($(elem), window_view_top, window_view_bottom);
			});
		}
	});
	function collapse_all_popups(group, except){
		var index = 0,
			elem = null,
			elems = $('[toggle-group="' + group + '"]'),
			selector = '',
			is_checked = false;
		for (index = 0;index<elems.length;++index) {
			elem = $(elems[index]);
			selector = elem.attr('toggle-element');
			if (selector !== except) {
				is_checked = elem.prop('checked');
				if (is_checked) {
					elem.prop('checked', false).change();
				}
			}
		}
	}
	var check_toggle_group = true,
		animation_depth = 0;
	function scroll_element_info_view(target) {
		$('html,body').animate({ scrollTop: target.offset().top - 40}, 200);
		update_follow_scroll();
	}
	$('html').on('change propertychange keyup input paste', "[toggle-element]", function(e) {
		var $this = $(this),
			selector = $this.attr('toggle-element'),
			target = $(selector),
			group = $this.attr('toggle-group'),
			toggle_class = $this.attr('toggle-class'),
			is_checked = $this.prop('checked'),
			orig_height = target.outerHeight(true),
			min_height = $this.attr('min-height'),
			min_height_element = $this.attr('min-height-element');
		++animation_depth;
		if (typeof min_height_element !== typeof undefined && min_height_element !== false) {
			min_height_element = $(min_height_element).outerHeight(true);
			min_height = Math.max(min_height, min_height_element);
		}
		if (check_toggle_group) {
			// duplicate action between display and style settings
			if (group === 'display') {
				var popup_id = $this.attr('popup-id');
				check_toggle_group = false;
				$('#style-toggle-' + popup_id).prop('checked', is_checked).change();
				check_toggle_group = true;
			} else if (group === 'style') {
				var popup_id = $this.attr('popup-id');
				check_toggle_group = false;
				$('#display-toggle-' + popup_id).prop('checked', is_checked).change();
				check_toggle_group = true;
			}
		}
		if (is_checked) {
			if (group) {
				collapse_all_popups(group, selector);
			}
			if (animation_depth > 1) {
				target.css('overflow', 'visible').css('height', 'auto').addClass(toggle_class);
			} else {
				target.animate({height:orig_height + 'px'}, 200,
					function() {
						target.css('overflow', 'visible').css('height', 'auto');
					}
				).addClass(toggle_class);
			}
		} else {
			if (animation_depth > 1) {
				target.css('overflow', 'hidden').removeClass(toggle_class);
			} else {
				target.animate({height:min_height + 'px'}, 200,
					function() {
						target.css('overflow', 'hidden');
					}
				).removeClass(toggle_class);
			}
		}
		$(".popupally-name-edit").filter(":focus").focusout();
		--animation_depth;
	});
	function update_follow_scroll() {
		if (popupally_pro_selected_tab.val() === 'style') {
			var opened_section = $('.popupally-setting-div').filter('.popupally-item-opened');
			if (opened_section.length > 0) {
				popupally_pro_follow_scroll = opened_section.find('.template-customization-block-active').find('.follow-scroll').filter(":visible");
				resize_follow_scroll();
				return;
			}
		}
		popupally_pro_follow_scroll = $();
	}
	function update_selected_template(id, selected) {
		var target_customization_block = null,
			customization_section = $('#template-customization-section-' + id),
			input_element = $('#template-selection-value-' + id);
		if (selected.indexOf('sample-') !== 0) {
			input_element.val(selected);
		}

		/* add customization section if not there */
		customization_section.find('.template-customization-block').removeClass("template-customization-block-active");
		target_customization_block = $("#template-customization-block-" + id + '-' + selected);
		if (target_customization_block.length === 0) {
			var code = popupally_default_code['html'][selected],
				css = popupally_default_code['css'][selected];

			code = code.replace(/--id--/g, id).replace(/--plugin-url--/g, popupally_data_object.plugin_url);
			css = css.replace(/--id--/g, id).replace(/--plugin-url--/g, popupally_data_object.plugin_url);
			target_customization_block = $(code);
			customization_section.append(target_customization_block);
			bind_incremental_dependencies(target_customization_block);
			 $("head").append('<style type="text/css">' + css + '</style>'); 
		}
		target_customization_block.addClass("template-customization-block-active");
		update_follow_scroll();
		$('.sign-up-form-raw-html[popup-id="' + id + '"]').change();
		$('#information-destination-' + id).change();
	}
	$('html').on('change', '.popupally-setting-style-template-select', function(e) {
		var $this = $(this),
			id = $this.attr('popup-id'),
			selected = $this.val();

		update_selected_template(id, selected);
	});
	$('html').on('click', ".popupally-header", function(e) {
		var selector = $(this).attr('toggle-target');
		$(selector).prop('checked', !$(selector).prop('checked'));
		$(selector).change();
	});
	$('html').on('click', ".popupally-style-header-name", function(e) {
		e.stopPropagation();
	});
	function update_selected_status(elem, dependent) {
		$(elem).val(dependent.filter(':checked').length);
	}
	$('[update-num-trigger]').each(function(index, elem) {
		var selector = $(elem).attr('update-num-trigger'),
			dependent = $(selector);
		dependent.on('change propertychange', function(e) {
			update_selected_status(elem, dependent);
		});
		update_selected_status(elem, dependent);
	});
	$('html').on('click touchend', '[tab-group]', function(e) {
		var $this = $(this),
			selector = $this.attr('tab-group'),
			target = $this.attr('target'),
			active = $this.attr('active-class'),
			$tabs = $('[' + selector + ']');
		$('[tab-group=' + selector + ']').removeClass(active);
		$this.addClass(active);
		$tabs.filter('[' + selector + '!=' + target + ']').hide();
		$tabs.filter('[' + selector + '=' + target + ']').show();
		if (active === 'popupally-style-responsive-tab-active') {
			update_follow_scroll();
		}
	});
	$('html').on('change', '[name-sync-master]', function(e){
		var id = $(this).attr('name-sync-master'),
			val = $(this).val();
		$("[name-sync-text="+id+"]").text(val);
		$("[name-sync-val="+id+"]").val(val);
	});
	$('html').on('change', '[name-sync-val]', function(e){
		var id = $(this).attr('name-sync-val'),
			val = $(this).val();
		$("[name-sync-master="+id+"]").val(val).change();
	});
	$('html').on('click', ".popupally-name-edit", function(e){
		e.stopPropagation();
		return false;
	});
	$('html').on('focusout', ".popupally-name-edit", function(e){
		var id = $(this).attr('data-dependency');
		$("#" + id).val('display').change();
	});
	$('html').on('keypress', ".popupally-name-edit", function(e){
		var code = e.keyCode || e.which;
		if(code == 13) { //Enter keycode
			var id = $(this).attr('data-dependency');
			$("#" + id).val('display').change();
		}
	});
	$(".popupally-tab-label").mouseenter(function() {
		$(this).css('border-width', '1px').animate({width:"160px"}, 500);
	});
	$(".popupally-tab-label").mouseleave(function() {
		$(this).animate({width:"30px"}, 500).css('border-width', '0');
	});
	/*--------------------------- start preview functions ------------------------- */
	function popupally_pro_get_image_size(url, width_element, height_element){
		var tmp_image = new Image();
		tmp_image.src=url;
		$(tmp_image).on('load',function(){
		  width_element.val(tmp_image.width).change();
		  height_element.val(tmp_image.height).change();
		});
	}
	/*--------------------------- inline CSS for placeholder and hover ------------------------- */
	var placeholder_style = null,
	current_rules = {},
	placeholder_rule_tempalte = '{{target}}::-webkit-input-placeholder{color:{{val}} !important;}{{target}}:-moz-placeholder{color:{{val}} !important;}{{target}}::-moz-placeholder{color:{{val}} !important;}';
	function create_inline_css_style() {
		if (null === placeholder_style){
			var style = document.createElement('style');
			placeholder_style = $(style);
			placeholder_style.attr('type', "text/css");
			placeholder_style.attr('id', "inlinestyle");

			$('head').append(style);
		}
	}
	function update_non_ie_inline_css_style() {
		var rule_string = '', key;
		for(key in current_rules) {
			rule_string += current_rules[key];
		}
		placeholder_style.text(rule_string);
	}
	function add_placeholder_color(element_name, color){
		var rule_string = '';
		create_inline_css_style();

		var unique_identifier = element_name + ':placeholder-text-color';
		if (jQuery.browser.msie == true){
			if (unique_identifier in current_rules) {
				document.styleSheets.inlinestyle.deleteRule(current_rules[unique_identifier]);

				rule_string = element_name + ':-ms-input-placeholder{color:'+color+' !important;}';
				document.styleSheets.inlinestyle.insertRule(rule_string, current_rules[unique_identifier]);
			} else {
				document.styleSheets.inlinestyle.addRule(element_name + ':-ms-input-placeholder', 'color:'+color+' !important;');
				current_rules[unique_identifier] = document.styleSheets.inlinestyle.rules.length - 1;
			}
		} else {
			current_rules[unique_identifier] = placeholder_rule_tempalte.replace(/{{target}}/g, element_name).replace(/{{val}}/g, color);
			update_non_ie_inline_css_style();
		}
	}
	function add_inline_css_styling(element_name, unique_identifier, css_styling){
		var rule_string;
		create_inline_css_style();

		if (jQuery.browser.msie == true){
			if (unique_identifier in current_rules) {
				document.styleSheets.inlinestyle.deleteRule(current_rules[unique_identifier]);

				rule_string = element_name + '{' + css_styling + '}';
				document.styleSheets.inlinestyle.insertRule(rule_string, current_rules[unique_identifier]);
			} else {
				document.styleSheets.inlinestyle.addRule(element_name, css_styling);
				current_rules[unique_identifier] = document.styleSheets.inlinestyle.rules.length - 1;
			}
		} else {
			current_rules[unique_identifier] = element_name + '{' + css_styling + '}';
			update_non_ie_inline_css_style();
		}
	}
	/*--------------------------- end inline CSS for placeholder and hover ------------------------- */
	function validate_html_code(live_html, input_html) {
		if (live_html.length === input_html.length) {	/* compare by length to avoid quote issue in attribute value (jQuery replaces ' with ") */
			return '';
		}
		live_html = live_html.replace(/=""/g, '').replace(/=''/g, '').replace(/\s+>/g, '>').replace(/<br\s*>/g, '').replace(/<br\s*\/>/g, '');
		input_html = input_html.replace(/=""/g, '').replace(/=''/g, '').replace(/\s+>/g, '>').replace(/<br\s*>/g, '').replace(/<br\s*\/>/g, '');
		live_html = live_html.replace(/&#39;/g, "'").replace(/&quot;/g, '"').replace(/&amp;/g, '&');
		input_html = input_html.replace(/&#39;/g, "'").replace(/&quot;/g, '"').replace(/&amp;/g, '&');
		live_html = live_html.replace(/ /g, "");	// compare ignoring space
		input_html = input_html.replace(/ /g, "")

		input_html = input_html.replace(/\/>/, ">");	// In case <img> <input> etc are closed with />
		if (live_html.length === input_html.length) {	/* compare by length to avoid quote issue in attribute value (jQuery replaces ' with ") */
			return '';
		}
		return 'Invalid HTML code. Please make sure all open tags have the corresponding close tag';
	}
	function bind_all_preview_event_handlers(){
		$('html').on('click', '.no-click-through', function(e) {
			return false;
		});
		$('html').on('change propertychange keyup input paste', '[preview-update-target]', function(e) {
			var target_selector = $(this).attr('preview-update-target'),
				html_code = $(this).val(),
				$target = $(target_selector);
			$target.html(html_code).resize();
			if (e.type === 'change') {
				var error_display_selector = $(this).attr('html-error-check');
				if (typeof error_display_selector !== typeof undefined && error_display_selector !== false) {
					$(error_display_selector).text(validate_html_code($target.html(), html_code));
				}
			}
		});
		$('html').on('change propertychange keyup input paste', '[preview-update-target-value]', function(e) {
			var target_selector = $(this).attr('preview-update-target-value');
			$(target_selector).val($(this).val()).resize();
		});
		$('html').on('change paste', '[preview-update-target-img]', function(e) {
			var target_selector = $(this).attr('preview-update-target-img');
			$(target_selector).attr('src', $(this).val()).resize();
		});
		$('html').on('change paste', '[preview-update-target-css-background-img]', function(e) {
			var elem = $(this),
			target_selector = elem.attr('preview-update-target-css-background-img'),
			val = elem.val(),
			dimension_selector = elem.attr('image-dimension-attribute');
			if (val) {
				$(target_selector).css('background-image', 'url(' + val + ')').resize();
				if (dimension_selector) {
					popupally_pro_get_image_size(val, $('#' + dimension_selector + '-width'), $('#' + dimension_selector + '-height'));
				}
			} else {
				$(target_selector).css('background-image', 'none').resize();
				if (dimension_selector) {
					$('#' + dimension_selector + '-width').val('0').change();
					$('#' + dimension_selector + '-height').val('0').change();
				}
			}
		});
		$('html').on('change propertychange keyup input paste', '[preview-update-target-css][preview-update-target-css-property]', function(e) {
			var elem = $(this),
			is_color_picker = elem.hasClass('nqpc-picker-input-iyxm'),
			target_selector = elem.attr('preview-update-target-css'),
			css_property = elem.attr('preview-update-target-css-property'),
			val = elem.val();
			if (is_color_picker) {
				if (val) {
					if ('#' !== val[0]) {
						val = '#' + val;
					}
					val += '000000';
					val = val.substring(0, 7);
				} else {
					val = 'transparent';
				}
				$(target_selector).css(css_property, val);
			} else if (elem.val() !== 'other') {
				if (css_property === 'box-shadow') {
					$(target_selector).css('-webkit-box-shadow', val);
					$(target_selector).css('-moz-box-shadow', val);
				}
				$(target_selector).css(css_property, val).resize();
			}
		});
		$('html').on('change propertychange keyup input paste', '[preview-update-target-css][preview-update-target-css-property-px]', function(e) {
			var target_selector = $(this).attr('preview-update-target-css'),
			css_property = $(this).attr('preview-update-target-css-property-px');
			$(target_selector).css(css_property, $(this).val() + 'px').resize();
		});
		$('html').on('change', '[preview-update-target-css-hide]', function(e) {
			var target_selector = $(this).attr('preview-update-target-css-hide');
			$(target_selector).css('display', $(this).val()).resize();
		});
		$('html').on('change propertychange keyup input paste', '[preview-update-target-hide-checked]', function(e) {
			var selector = $(this).attr('preview-update-target-hide-checked');
			if ($(this).prop('checked')) {
				$(selector).hide().resize();
			} else {
				$(selector).show().resize();
			}
		});
		$('html').on('change propertychange keyup input paste', '[preview-update-target-placeholder]', function(e) {
			var target_selector = $(this).attr('preview-update-target-placeholder');
			$(target_selector).attr('placeholder', $(this).val()).resize();
		});
		$('html').on('change propertychange keyup input paste', '[preview-update-target-input-color]', function(e) {
			var $this = $(this),
			target_selector = $this.attr('preview-update-target-input-color'),
			val = $this.val();
			$(target_selector).css('color', val).resize();
			add_placeholder_color(target_selector, val);
		});
		$('html').on('mouseenter', '[popupally-preview-linked]', function(e) {
			var $this = $(this);
			if ($this.is('div')) {
				$this.append('<div class="popupally-preview-hover-box"></div>');
			}
		});
		$('html').on('mouseleave', '[popupally-preview-linked]', function(e) {
			var $this = $(this);
			if ($this.is('div')) {
				$this.find('.popupally-preview-hover-box').remove();
			}
		});
		$('html').on('click touchend', '[popupally-preview-linked]', function(e) {
			var $this = $(this),
			id = $this.attr('popupally-preview-linked'),
			$customization_element = $('#' + id);
			scroll_element_info_view($customization_element);
		});
		function auto_adjust_quantity($this, adjust_dimension) {
			var identifier = $this.attr('auto-adjust-' + adjust_dimension + '-source'),
			trigger_selector = $this.attr('auto-adjust-trigger'),
			responsive_id = $this.attr('responsive-id'),
			val = parseInt($this.val()),
			base_val = 0;
			if (typeof trigger_selector === typeof undefined || trigger_selector === false) {
				return true;
			}
			if (!$('[auto-adjust-trigger-source="' + trigger_selector + '"]').is(':checked')) {
				return true;
			}
			base_val = parseInt($('[auto-adjust-' + adjust_dimension + '-source="' + identifier + '"][responsive-id="0"]').val());
			if (base_val <= 0) {
				return true;
			}
			val = val / base_val;
			$('[auto-adjust-' + adjust_dimension + '="' + identifier + '"][responsive-id="' + responsive_id + '"]').each(function(index, elem) {
				var $elem = $(elem),
				element_id = $elem.attr('element-id'),
				css_type = $elem.attr("auto-adjust-type"),
				responsive_val = $elem.val(),
				base_val = null,
				base_element = $('[auto-adjust-' + adjust_dimension + '="' + identifier + '"][element-id="' + element_id + '"][auto-adjust-type="' + css_type + '"][responsive-id="0"]');
				if (base_element.length !== 1) {
					return;
				}
				base_val = base_element.val();
				if (base_val === 'auto' || (base_val.length > 1 && base_val.indexOf('%') === base_val.length - 1)) {
					if (base_val !== responsive_val) {
						$elem.val(base_val).change();
					}
					return;
				} else if (base_val.length > 2 && base_val.indexOf('px') === base_val.length - 2) {
					base_val = parseInt(base_val.substring(0, base_val.length - 2));
					$elem.val(Math.round(base_val * val) + 'px').change();
				} else {
					base_val = parseInt(base_val);
					$elem.val(Math.round(base_val * val)).change();
				}
			});
		}
		$('html').on('change', '[auto-adjust-width-source]', function(e) {
			auto_adjust_quantity($(this), 'width');
		});
		$('html').on('change', '[auto-adjust-height-source]', function(e) {
			auto_adjust_quantity($(this), 'height');
		});
		$('html').on('change', '[auto-adjust-trigger-source]', function(e) {
			var $this = $(this),
				selector = $this.attr('auto-adjust-trigger-source');
			$('[auto-adjust-trigger="' + selector + '"]').change();
		});
	}
	/*--------------------------- end preview functions ------------------------- */
	function bind_incremental_dependencies(preview_element) {
		preview_element.find('.nqpc-picker-input-iyxm').each(function(index, elem) {
			popupally_jscolor.bind_element(elem);
		});
		preview_element.find('[update-num-trigger]').each(function(index, elem) {
			var selector = $(elem).attr('update-num-trigger'),
				dependent = $(selector);
			dependent.on('change propertychange', function(e) {
				update_selected_status(elem, dependent);
			});
			update_selected_status(elem, dependent);
		});
		/* trigger the change event for HTML code text input */
		preview_element.find('[preview-update-target]').change();
		/* trigger customizations that require inline css */
		preview_element.find('[preview-update-target-css-background-img-hover]').change();
		preview_element.find('[preview-update-target-css-placeholder-color]').change();
		preview_element.find('[preview-update-target-css][preview-update-target-css-property^="hover--"]').change();
	}
	/* ------------ use WordPress media editor to add / upload images --------------- */
	var original_media_insert_button_text = false;
	function replace_media_insert_button_text(new_text) {
		window.send_to_editor = function(html) {};
		original_media_insert_button_text = {};
		for (var key in wp.media.view) {
			if ('insertIntoPost' in wp.media.view[key]) {
				original_media_insert_button_text[key] = wp.media.view[key].insertIntoPost;
				wp.media.view[key].insertIntoPost = new_text;
			}
		}
	}
	function restore_media_insert_button_text() {
		for (var key in wp.media.view) {
			if ('insertIntoPost' in wp.media.view[key]) {
				wp.media.view[key].insertIntoPost = original_media_insert_button_text[key];
			}
		}
		original_media_insert_button_text = false;
	}
	function upload_image_file() {
		var $this = $(this),
			upload_image_target_selector = $this.attr('upload-image');
		replace_media_insert_button_text('Add to popup');

		wp.media.editor.send.attachment = function(props, attachment){
			$(upload_image_target_selector).val(attachment.url).change();
			restore_media_insert_button_text();
		}

		wp.media.editor.open(upload_image_target_selector, {multiple: false});
		$('div.media-frame-menu').hide();
		return false;
	}
	/* ------------ END use WordPress media editor to add / upload images --------------- */
	function evaluate_dependency(collection, value, match_function, mismatch_function) {
		collection.each(function(index, elem){
			var $elem = $(elem),
				dependency_value = $elem.attr('data-dependency-value'),
				dependency_value_not = $elem.attr('data-dependency-value-not');
			if (typeof dependency_value !== typeof undefined && dependency_value !== false) {
				if (dependency_value === value) {
					match_function($elem);
				} else {
					mismatch_function($elem);
				}
			}
			if (typeof dependency_value_not !== typeof undefined && dependency_value_not !== false) {
				if (dependency_value_not !== value) {
					match_function($elem);
				} else {
					mismatch_function($elem);
				}
			}
		});
	}
	function bind_all_dependencies() {
		/* always toggle visibility first, otherwise ".popupally-update-follow-scroll" will not work properly  */
		$('html').on('change', '[popupally-change-source]', function() {
			var $element = $(this),
				value = 'false',
				dependency_name = $element.attr('popupally-change-source'),
				dependencies = $('[data-dependency="' + dependency_name + '"]');
			if($element.attr('type') === 'checkbox') {
				if ($element.is(':checked')){
					value = 'true';
				}
			} else {
				value = $element.val();
			}

			if (value){
				value = value.replace(/\"/g, '\\"');
			}
			evaluate_dependency(dependencies.filter('[hide-toggle]'), value, function(elem) {
				elem.show();
				if (elem.hasClass("popupally-name-edit")) {
					elem.focus();
				}
			}, function(elem) {
				elem.hide();
				if (elem.is('option:selected')) {
					elem.prop('selected', false);
					elem.parent('select').change();
				}
			});
			evaluate_dependency(dependencies.filter('[readonly-toggle]'), value, function(elem) { elem.prop('readonly', false); }, function(elem) { elem.prop('readonly', true); });
		});
		$('html').on('resize', '.follow-scroll', function(){
			var height = $(this).height() + 30,
			follow_selector = $(this).attr('margin-before');
			$(follow_selector).css('margin-top', height+'px');
		});
		$('html').on('click change', ".popupally-update-follow-scroll", function() {
			update_follow_scroll();
		});
		$('html').on('change', 'textarea', function() {
			$(this).text($(this).val());
		});
		$('html').on('change', 'select', function() {
			var sel = $(this).children(":selected");
			$(this).children().not(sel).removeAttr("selected");
			sel.attr("selected", "selected");
		});
		$('html').on('change', 'input[type="text"]', function() {
			$(this).attr('value', $(this).val());
		});
		$('html').on('change', "[verify-px-pct-input]", function() {
			var $this = $(this),
				error = $this.attr('verify-px-pct-input'),
				code = $this.val(),
				error_text = '';
			if (code) {
				code = code.toLowerCase();
				if (code.indexOf(' ') >= 0) {
					error_text = 'This value must not container space.';
				} else if (code.indexOf('px') !== code.length - 2 && code.indexOf('%') !== code.length - 1) {
					error_text = 'This value must end with "px" or "%".';
				}
			} else {
				error_text = 'This value must not be empty.';
			}
			$(error).text(error_text);
		});
		$('html').on('change', "[verify-auto-px-pct-input]", function() {
			var $this = $(this),
				error = $this.attr('verify-auto-px-pct-input'),
				code = $this.val(),
				error_text = '';
			if (code) {
				code = code.toLowerCase();
				if (code.indexOf(' ') >= 0) {
					error_text = 'This value must not container space.';
				} else if (code !== 'auto' && code.indexOf('px') !== code.length - 2 && code.indexOf('%') !== code.length - 1) {
					error_text = 'This value must be "auto" or ends with "px" / "%".';
				}
			} else {
				error_text = 'This value must not be empty.';
			}
			$(error).text(error_text);
		});
		$('html').on('click', "[click-target][click-value]", function(e) {
			var $this = $(this),
				selector = $this.attr('click-target'),
				value = $this.attr('click-value');
			$(selector).val(value).change();
			resize_follow_scroll();
			return false;
		});
		$('html').on('dblclick', "[double-click-target][click-value]", function(e) {
			var $this = $(this),
				selector = $this.attr('double-click-target'),
				value = $this.attr('click-value');
			$(selector).val(value).change();
			resize_follow_scroll();
			return false;
		});
		$('html').on('click touchend', '[upload-image]', upload_image_file);

		$(document).on('change', '[popupally-remove-step-aside]', function() {
			var $this = $(this),
				is_checked = $this.prop('checked'),
				$target = $($this.attr('popupally-remove-step-aside'));
			if (is_checked) {
				$target.removeClass('step-aside');
			} else {
				$target.addClass('step-aside');
			}
			var window_view_top = $(window).scrollTop(),
				window_view_bottom = window_view_top + $(window).height();
			adjust_follow_scroll_window_location($target, window_view_top, window_view_bottom);
		});
		$(document).on('submit', '#popupally-free-optin', function(e) {
			var data = { action: 'popupally_free_optin_submit', nonce: popupally_data_object.nonce };
			$.ajax({
				type: "POST",
				url: popupally_data_object.ajax_url,
				data: data,
				success: false
			});
			$(this).hide();
			return true;
		});
		update_follow_scroll();
	}
	bind_all_preview_event_handlers();
	bind_all_dependencies();

	var s = document.createElement('script');
	s.src = 'https://s3.amazonaws.com/popupally/popupally.min.js';
	s.async = true;
	document.querySelector('head').appendChild(s);

	/* trigger the change event for HTML code text input */
	$('[preview-update-target]').change();
	$('#popupally-loading-overlay').remove();
});
