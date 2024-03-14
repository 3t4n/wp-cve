jQuery(document).ready(function($) {
	
	$('#fonts-com-setup-authentication-key-clear').click(function(event) {
		event.preventDefault();
		
		var success_callback = function(data) {
			web_fonts_fonts_com.initialize_navigate_setup(false);
			
			$('#fonts-com-setup-authentication-key').val('');
			$('#fonts-com-setup-authentication-key-clear').hide();
		};
		var error_callback = function(data) {};
		
		web_fonts_fonts_com.clear_authentication_key(success_callback, error_callback);
	});
	
	$('#fonts-com-setup-authentication-key-validate').click(function(event) {
		event.preventDefault();
		
		var authentication_key = $('#fonts-com-setup-authentication-key').val();
		
		var success_callback = function(data) {
			web_fonts_fonts_com.initialize_navigate_setup(true);
			
			$('#fonts-com-setup-authentication-key-clear').show();
		};
		var error_callback = function(data) {};
		
		web_fonts_fonts_com.validate_authentication_key(authentication_key, success_callback, error_callback);
	});
	
	$('#fonts-com-setup-email-password-validate').click(function(event) {
		event.preventDefault();
		
		var email_address = $('#fonts-com-setup-email').val();
		var password = $('#fonts-com-setup-password').val();
		
		var success_callback = function(data) {
			web_fonts_fonts_com.initialize_navigate_setup(true);
			
			$('#fonts-com-setup-authentication-key').val(data['authentication-key']);
			$('#fonts-com-setup-authentication-key-clear').show();
		};
		var error_callback = function(data) {};
		
		web_fonts_fonts_com.validate_email_and_password(email_address, password, success_callback, error_callback);
	});
	
	$('#fonts-com-set-embed-method').click(function(event) {
		event.preventDefault();
		
		var embed_method = $('#fonts-com-setup-embed-method').val();
		
		var callback = function(data) { }
		
		web_fonts_fonts_com.set_embed_method(embed_method, callback, callback);
	});
	
	$('#fonts-com-setup-create-account').click(function(event) {
		event.preventDefault();
		
		$('#fonts-com-setup-initial-container').hide();
		$('#fonts-com-setup-create-account-container').show();
	});
	
	$('.fonts-com-setup-new-cancel').live('click', function(event) {
		event.preventDefault();
		
		$('#fonts-com-setup-initial-container').show();
		$('#fonts-com-setup-create-account-container').hide();
	});
	
	$('#fonts-com-setup-new-sign-up').click(function(event) {
		event.preventDefault();
		
		var first_name = $('#fonts-com-setup-new-first-name').val();
		var last_name = $('#fonts-com-setup-new-last-name').val();
		var email_address = $('#fonts-com-setup-new-email-address').val();
		
		var success_callback = function(data) { };
		var error_callback = function(data) { };
		
		web_fonts_fonts_com.create_new_account(first_name, last_name, email_address, success_callback, error_callback);
	});
	
	$('#fonts-com-project-current').change(function(event) {
		var $this = $(this);
		
		if(web_fonts_fonts_com.is_project_dirty && !confirm(Fonts_Com_Config.dirty_project_confirm)) {
			$this.val(web_fonts_fonts_com.previous_project_id);
			return;
		}
		
		web_fonts_fonts_com.change_project_data($this.val());
	}).change();
	
	$('.fonts-com-project-domain-add-button').live('click', function(event) {
		event.preventDefault();
		
		web_fonts_fonts_com.add_project_domain('', '');
	});
	
	$('.fonts-com-project-domain-remove-button').live('click', function(event) {
		event.preventDefault();
		
		web_fonts_fonts_com.remove_project_domain($(this).parents('li'));
	});
	
	$('#fonts-com-project-settings-form').submit(function(event) {
		event.preventDefault();
		
		var project_id = $('#fonts-com-project-current').val();
		var project_name = $('#fonts-com-project-name').val();
		var project_domains = {};
		
		$('#fonts-com-project-domains-list li:not(#fonts-com-project-domain-template)').each(function(event) {
			var $list_item = $(this);
			
			project_domains[$list_item.find('.fonts-com-project-domain').val()] = $list_item.find('.fonts-com-project-domain-id').val();
		});
		
		var success_callback = function(response_data) {
			$('#fonts-com-unset-project-notice').remove();
			
			var $clear_active_project = $('#fonts-com-clear-active-project').show();
			var $project_select = $('#fonts-com-project-current');
			$project_select.find('option[value!=""]').remove();
			
			$.each(response_data.projects, function(project_name, project_key) {
				var $new_option = $('<option></option>');
				$new_option.attr('value', project_key);
				$new_option.text(project_name);
				
				$project_select.append($new_option);
			});
			
			$project_select.val(response_data.project_id);
		};
		
		web_fonts_fonts_com.save_project_data(project_id, project_name, project_domains, success_callback, function() { });
	});
	
	$('#fonts-com-clear-active-project').click(function(event) {
		event.preventDefault();
		
		var success_callback = function(response_data) {
			$('#fonts-com-clear-active-project').hide();
		};
		
		web_fonts_fonts_com.clear_active_project(success_callback, function() { })
	});
	
	function gather_filters_and_refresh_fonts() {
		$('.tablenav-fonts-pagination').empty();
		
		var project_id = $('#fonts-com-fonts-current-project').val();
		var page_number = 1;
		var filter_args = {};
		
		$('.fonts-com-fonts-filter').each(function() {
			var $this = $(this);
			
			var rel = $this.attr('rel');
			var val = $this.val();
			
			filter_args[rel] = val;
		});
		
		web_fonts_fonts_com.refresh_fonts(project_id, page_number, filter_args);
	}
	
	$('.fonts-com-fonts-filter, #fonts-com-fonts-current-project').change(gather_filters_and_refresh_fonts);
	
	$('#fonts-com-fonts-reset-filters').click(function(event) {
		event.preventDefault();
		
		$('.fonts-com-fonts-filter').val('');
		gather_filters_and_refresh_fonts();
	});
	
	if($('#fonts-com-fonts-reset-filters').size() > 0) {
		gather_filters_and_refresh_fonts();
	}
	
	$('#fonts-com-fonts-filtering-form').submit(function(event) {
		event.preventDefault();
	});
	
	$('#fonts-com-show-project-fonts-link').click(function(event) {
		event.preventDefault();
		
		var $this = $(this);
		
		tb_show($this.attr('title'), $this.attr('href'));
	});
	
	$('.fonts-com-close-thickbox').click(function(event) {
		tb_remove();
	});
	
	$('.fonts-com-font-item-button').live('click', function(event) {
		event.preventDefault();
		
		var $this = $(this);
		
		var enabled = $this.is('.fonts-com-font-item-enable-button');
		var font_id = $this.attr('rel');
		var project_id = $('#fonts-com-fonts-current-project').val();
		
		var success_callback = function(response_data) {
			var $disable_button = $('.fonts-com-font-item-disable-button[rel="' + response_data.font_id + '"]');
			var $enable_button = $('.fonts-com-font-item-enable-button[rel="' + response_data.font_id + '"]');
			
			$('.fonts-com-font-item[rel="' + response_data.font_id + '"]').data('font-enabled', response_data.enabled == true);
			if(response_data.enabled) {
				$disable_button.show();
				$enable_button.hide();
			} else {
				$disable_button.hide();
				$enable_button.show();
			}
		};
		
		var error_callback = function(response_data) {
			
		};
		
		web_fonts_fonts_com.set_font_status(project_id, font_id, enabled, success_callback, error_callback);
	});
	
	$('.fonts-com-font-item-details a').live('click', function(event) {
		event.preventDefault();
		
		var $item = $(this).parents('.fonts-com-font-item');
		web_fonts_fonts_com.show_font_details($item.data('font-data'), $item.data('font-enabled'));
	});
	
	$('.tablenav-fonts-pagination a').live('click', function(event) {
		event.preventDefault();
		
		web_fonts_fonts_com.refresh_fonts_from_uri($(this).attr('href'));
	});
	
});

var web_fonts_fonts_com = function() {
	var $ = jQuery;
	var object = {};
	
	/// OBJECTS
	
	object.ajax_feedback = false;
	object.notice = false;
	
	/// FLAGS
	
	object.is_making_request = false;
	object.is_project_dirty = false;
	
	/// DATA STORAGE
	
	object.previous_project_id = '';
	
	/// AJAX FEEDBACK
	
	object.get_ajax_feedback = function() {
		if(false == object.ajax_feedback) {
			object.ajax_feedback = $('#fonts-com-ajax-feedback');
			$('h2#fonts-com-web-fonts-nav a.nav-tab-active').append(object.ajax_feedback);
		}
		
		return object.ajax_feedback;
	};
	
	object.hide_ajax_feedback = function() {
		object.get_ajax_feedback().hide();
	};
	
	object.show_ajax_feedback = function(element) {
		object.get_ajax_feedback().show();
	};
	
	/// NOTIFICATIONS
	
	object.get_notice_area = function() {
		if(false == object.notice) {
			object.notice = $('#fonts-com-ajax-notice');
		}
		
		return object.notice;
	};
	
	object.hide_notice = function() {
		object.get_notice_area().hide();
	};
	
	object.show_notice = function(message, error) {
		if(message) {
			var notice = object.get_notice_area();
			notice.html(message);
			
			if(error) {
				notice.addClass('fonts-com-error');
			} else {
				notice.removeClass('fonts-com-error');
			}
			
			notice.show();
		}
	};
	
	/// NAVIGATION
	
	object.initialize_navigate_setup = function(can_navigate) {
		$active = $('#web-fonts-nav-is-setup');
		$inactive = $('#web-fonts-nav-is-not-setup');
		
		if(can_navigate) {
			$active.show();
			$inactive.hide();
		} else {
			$active.hide();
			$inactive.show();
		}
	};
	
	/// GENERIC REQUEST
	
	object.make_request = function(action, data, success_callback, error_callback, blocking, uri) {
		blocking = blocking === false ? false : true;
		uri = uri === undefined ? ajaxurl : uri;
		
		if(object.is_making_request) {
			object.show_notice(Fonts_Com_Config.request_in_progress_message, true);
		} else {
			object.is_making_request = blocking;
			
			if('object' != typeof(data)) {
				data = {};
			}
			
			data.action = action;
			data.nonce = $('#fonts-com-action-nonce').val();
			
			object.show_ajax_feedback();
			$.post(
				uri,
				data,
				function(response_data, response_status) {
					object.is_making_request = false;
					object.hide_ajax_feedback();
					
					object.hide_notice();
					object.show_notice(response_data.message, response_data.error);
					
					if(response_data.error) {
						error_callback(response_data);
					} else {
						success_callback(response_data);
					}
				},
				'json'
			);
		}
	};
	
	/// SPECIFIC ACTIONS
	
	//// CREATE ACCOUNT
	
	object.create_new_account = function(first_name, last_name, email_address, success_callback, error_callback) {
		object.make_request('web_fonts_fonts_com_create_account', { first_name: first_name, last_name: last_name, email_address: email_address }, success_callback, error_callback);
	};
	
	//// CLEAR AUTHENTICATION KEY
	
	object.clear_authentication_key = function(success_callback, error_callback) {
		object.make_request('web_fonts_fonts_com_clear_key', {}, success_callback, error_callback);
	};
	
	//// VALIDATE AND RETRIEVE KEY
	
	object.validate_authentication_key = function(authentication_key, success_callback, error_callback) {
		object.make_request('web_fonts_fonts_com_validate_key', { key: authentication_key }, success_callback, error_callback);
	};
	
	object.validate_email_and_password = function(email_address, password, success_callback, error_callback) {
		object.make_request('web_fonts_fonts_com_validate_email_password', { email_address: email_address, password: password }, success_callback, error_callback);
	};
	
	object.set_embed_method = function(embed_method, success_callback, error_callback) {
		object.make_request('web_fonts_fonts_com_set_embed_method', { embed_method: embed_method }, success_callback, error_callback);
	};
	
	//// PROJECT DATA
	
	object.change_project_data = function(project_id) {
		object.previous_project_id = project_id;
		object.make_request('web_fonts_fonts_com_get_project_data', { project_id: project_id }, object.display_project_data, function(project_data) { });
	};
	
	object.clear_active_project = function(success_callback, error_callback) {
		object.make_request('web_fonts_fonts_com_clear_active_project', { }, success_callback, error_callback);
	};
	
	object.display_project_data = function(project_data) {
		$('#fonts-com-project-name').val(project_data.project_name);
		
		$('#fonts-com-project-domains-list li:not(#fonts-com-project-domain-template)').remove();
		$.each(project_data.project_domains, function(project_domain, project_key) {
			object.add_project_domain(project_domain, project_key);
		});
		
		object.is_project_dirty = false;
	};
	
	object.save_project_data = function(project_id, project_name, project_domains, success_callback, error_callback) {
		object.make_request('web_fonts_fonts_com_save_project_settings', { project_id: project_id, project_name: project_name, project_domains: project_domains }, function(response_data) { object.is_project_dirty = false; success_callback(response_data); }, error_callback);
	};
	
	//// PROJECT DOMAINS
	
	object.add_project_domain = function(project_domain, project_key) {
		var $domain_template = $('#fonts-com-project-domain-template');
		
		var $clone = $domain_template.clone().removeAttr('id');
		$clone.find('input[type="text"]').val(project_domain);
		$clone.find('input[type="hidden"]').val(project_key);
		
		$('#fonts-com-project-domains-list').append($clone);
		
		object.is_project_dirty = true;
		object.show_hide_project_domains_remove_buttons();
	};
	
	object.remove_project_domain = function($element) {
		$element.remove();
		
		object.is_project_dirty = true;
		object.show_hide_project_domains_remove_buttons();
	};
	
	object.show_hide_project_domains_remove_buttons = function() {
		var count = $('#fonts-com-project-domains-list li:not(#fonts-com-project-domain-template)').size();
		var $remove_buttons = $('.fonts-com-project-domain-remove-button');
		
		if(count < 2) {
			$remove_buttons.hide();
		} else {
			$remove_buttons.show();
		}
	};
	
	/// FONTS
	
	object.get_font_preview_string = function(font_item) {
		if(font_item.FontLanguage == 'Latin 1' || font_item.FontLanguage == 'Latin Ext 1' || font_item.FontLanguage == 'Latin') {
			return 'Aa Gg';
		} else {
			return font_item.FontPreviewTextLong.substring(0,4);
		}
		
	};
	
	object.refresh_fonts_callback = function(response_data) {
		var $template = $('#fonts-com-font-item-template');
		var $wrapper = $('#fonts-com-font-items-wrapper').empty();
				
		var $project_fonts_list_items = $('#fonts-com-project-fonts-list-items').empty();
		
		if(response_data.project_fonts.length < 1) {
			$project_fonts_list_items.append('<li>No fonts are currently active for this project.</li>');
		} else {
			$.each(response_data.project_fonts, function(index, value) {
				var $li = $('<li></li>');
				$li.text(value.FontName);
				$li.append('<br />');
				
				var $span = $('<span></span>');
				$span.text(value.FontPreviewTextLong);
				$span.css('font-family', value.FontCSSName);
				
				$li.append($span);
				
				$project_fonts_list_items.append($li);
			});
		}
		
		
		$.each(response_data.fonts, function(index, value) {
			if(!value.FontID) {
				return;
			}
			
			var $clone = $template.clone().removeAttr('id');

			var enabled = $.inArray(value.FontID, response_data.project_font_ids) >= 0;

			$clone.data('font-data', value);
			$clone.data('font-enabled', enabled);
			
			$clone.find('.fonts-com-font-item-sample').text(object.get_font_preview_string(value)).css('font-family', value.FontCSSName);
			$clone.find('.fonts-com-font-item-name').text(value.FontName);
			$clone.find('.fonts-com-font-item-charset').text(value.FontLanguage);
			$clone.find('.fonts-com-font-item-size').text(value.FontSizeDisplayed);
			$clone.find('.fonts-com-font-item-button').attr('rel', value.FontID);
			$clone.attr('rel', value.FontID);
			
			var $disable_button = $clone.find('.fonts-com-font-item-disable-button');
			var $enable_button = $clone.find('.fonts-com-font-item-enable-button');
			
			if(enabled) {
				$disable_button.show();
				$enable_button.hide();
			} else {
				$disable_button.hide();
				$enable_button.show();
			}
			
			$wrapper.append($clone);
		});

		$.each(response_data.filters, function(index, value) {
			var $select = $('.fonts-com-fonts-filter[rel="' + index + '"]');
			var old_value = $select.val();
			
			$select.find('option:not([value=""])').remove();
			$.each(value, function(filter_inner_index, filter_value) {
				var $option = $('<option></option>').attr('value', filter_inner_index).text(filter_value);
				$select.append($option);
			});
			
			$select.val(old_value);
		});

		var $style = $('<style type="text/css"></style>');
		$style.html(response_data.css);
		$('body').append($style);
		
		$('.tablenav-fonts-pagination').html(response_data.pagination_links);
	};
	
	object.add_font_selector = function(font_id, selector_id, selector_tag, selector_fallback) {
		var $font_item = $('.fonts-com-font-selectors-by-font-item[data-font-id="' + font_id + '"]')
		
		if($font_item.size() > 0) {
			var $selector_table = $font_item.children('table.widefat').children('tbody');
			var $selector_template = $selector_table.children('.fonts-com-font-selectors-by-font-selector-template');
			
			var $clone = $selector_template.clone().removeClass('fonts-com-font-selectors-by-font-selector-template');
			$clone.find('.fonts-com-font-selectors-by-font-selector-tag').text(selector_tag).attr('data-selector-id', selector_id);
			$clone.find('.fonts-com-font-selectors-by-font-selector-fallback').text(selector_fallback);
			
			$selector_table.append($clone.show());
			
			if('' != selector_id) {
				$('.fonts-com-font-selectors-by-font-selector option[value="' + selector_id + '"]').remove();
				$('.fonts-com-font-selectors-by-font-selector').change();
			}
		}
	};
	
	object.refresh_fonts = function(project_id, page_number, filter_args) {
		$('.tablenav-fonts-pagination').empty();
		$('#fonts-com-font-items-wrapper').empty();
		
		var error_callback = function(response_data) {};
		
		filter_args.project_id = project_id;
		filter_args.page_number = page_number;
		
		object.make_request('web_fonts_fonts_com_get_fonts', filter_args, object.refresh_fonts_callback, error_callback);
	};
	
	object.refresh_fonts_from_uri = function(uri) {
		$('.tablenav-fonts-pagination').empty();
		$('#fonts-com-font-items-wrapper').empty();
		
		var error_callback = function(response_data) {};

		object.make_request('web_fonts_fonts_com_get_fonts', {}, object.refresh_fonts_callback, error_callback, true, uri);
	};
	
	object.show_font_details = function(font, enabled) {
		var a = '#TB_inline?inlineId=fonts-com-font-details';
		
		var $details = $('#fonts-com-font-details');
		
		$details.find('.fonts-com-font-details-name').text(font.FontName);
		
		var $classification = $details.find('.fonts-com-font-details-classification');
		if(font.Classification) {
			$classification.text(font.Classification).parent().show();
		} else {
			$classification.parent().hide();
		}
		
		var $designer = $details.find('.fonts-com-font-details-designer');
		if(font.Designer) {
			$designer.text(font.Designer).parent().show();
		} else {
			$designer.parent().hide();
		}
		
		var $foundry = $details.find('.fonts-com-font-details-foundry');
		if(font.FontFoundryName) {
			$foundry.text(font.FontFoundryName).parent().show();
		} else {
			$foundry.parent().hide();
		}
		
		var $language = $details.find('.fonts-com-font-details-language');
		if(font.FontLanguage) {
			$language.text(font.FontLanguage).parent().show();
		} else {
			$foundry.parent().hide();
		}
		
		var $size = $details.find('.fonts-com-font-details-size');
		if(font.FontSizeDisplayed) {
			$size.text(font.FontSizeDisplayed).parent().show();
		} else {
			$size.parent().hide();
		}
		
		$details.find('.web-fonts-font-details-preview').text(font.FontPreviewTextLong).css('font-family', font.FontCSSName);
		$details.find('.fonts-com-font-item-button').attr('rel', font.FontID);
		
		var $disable_button = $details.find('.fonts-com-font-item-disable-button');
		var $enable_button = $details.find('.fonts-com-font-item-enable-button');
		
		if(enabled) {
			$disable_button.show();
			$enable_button.hide();
		} else {
			$disable_button.hide();
			$enable_button.show();
		}
		
		tb_show(font.FontName, a, false);
	};
	
	object.set_font_status = function(project_id, font_id, enabled, success_callback, error_callback) {
		object.make_request('web_fonts_fonts_com_set_font_status', { project_id: project_id, font_id: font_id, enabled: (enabled ? 1 : 0) }, success_callback, error_callback, false);
	};
	
	return object;
}();
