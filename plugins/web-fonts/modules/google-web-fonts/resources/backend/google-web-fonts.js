var web_fonts_google_view_model = {};
jQuery(document).ready(function($) {
	var initializer = {
		// SETTINGS PAGE
		saved_api_key: $('#google-web-fonts-api-key').val(),
		
		// FONTS PAGE
		font_search_keyword: $('#google-web-fonts-font-search-keyword').val(),
		font_search_sort: $('#google-web-fonts-font-search-sort').val()
	};

	var web_fonts_google_create_style_link = function(font) {
		if($('link[data-font-id="' + font.id + '"]').size() < 1) {
			var $link = $('<link href="http://fonts.googleapis.com/css?family=' + font.url_param + '" rel="stylesheet" type="text/css" />').attr('data-font-id', font.id);
			$('head').append($link);
		}
	}
	
	var web_fonts_google_view_model_font = function(family, family_name, is_enabled, style, style_string, url_param, weight, weight_string) {
		var font = {
			family: ko.observable(family),
			family_name: ko.observable(family_name),
			is_enabled: ko.observable(is_enabled),
			style: ko.observable(style),
			style_string: ko.observable(style_string),
			url_param: ko.observable(url_param),
			weight: ko.observable(weight),
			weight_string: ko.observable(weight_string)
		};
		
		return font;
	};
	
	web_fonts_google_view_model = (function(initializer) {
		var self = {};
		
		/** SETTINGS PAGE **/
		
		self.unsaved_api_key = ko.observable(initializer.saved_api_key);
		self.has_unsaved_api_key = ko.computed(function() { return '' != self.unsaved_api_key(); }, self);
		self.save_unsaved_api_key = function() {
			web_fonts_google_web_fonts.set_key(self.unsaved_api_key, function(data) {
				self.unsaved_api_key(data.key);
				self.saved_api_key(data.key);
				web_fonts_google_web_fonts.initialize_navigate_setup(true);
			}, function(data) {
				web_fonts_google_web_fonts.initialize_navigate_setup(false);
			});
		};
		
		self.saved_api_key = ko.observable(initializer.saved_api_key);
		self.has_saved_api_key = ko.computed(function() { return '' != self.saved_api_key(); }, self);
		self.clear_saved_api_key = function() {
			web_fonts_google_web_fonts.clear_key(function(data) {
				self.saved_api_key('');
				self.unsaved_api_key('');
			}, function(data) {
				
			});
		};
		
		/** FONTS PAGE **/

		self.enabled_fonts = ko.observableArray();
		self.fonts = ko.observableArray();
		self.selected_font = ko.observable();
		
		self.font_search_keyword = ko.observable(initializer.font_search_keyword);
		self.font_search_sort = ko.observable(initializer.font_search_sort);
		
		self.get_fonts = function(search_keyword, search_sort, page_number) {
			self.font_search_keyword(search_keyword);
			self.font_search_sort(search_sort);
			
			web_fonts_google_web_fonts.get_fonts(search_keyword, search_sort, page_number, function(data) {
				self.enabled_fonts.removeAll();
				self.fonts.removeAll();
				
				$.each(data.enabled_fonts, function(index, font) {
					self.enabled_fonts.push(web_fonts_google_view_model_font(font.family, font.family_name, font.is_enabled, font.style, font.style_string, font.url_param, font.weight, font.weight_string));
				});
				
				$.each(data.fonts, function(index, font) {
					web_fonts_google_create_style_link(font);
					self.fonts.push(web_fonts_google_view_model_font(font.family, font.family_name, font.is_enabled, font.style, font.style_string, font.url_param, font.weight, font.weight_string));
				});
				
				$('#google-web-fonts-font-items-wrapper-container').attr('data-last-keyword', search_keyword).attr('data-last-sort', search_sort).find('.tablenav-fonts-pagination').html(data.pagination_links);
			}, function(data) {
				
			});
		};
		
		self.reset_search = function() {
			self.get_fonts('', 'alpha', 0);
		};
		
		self.set_font_status = function(enabled, font) {
			web_fonts_google_web_fonts.set_font_status(font, enabled, function(data) {
				self.enabled_fonts.removeAll();
				$.each(data.enabled_fonts, function(index, font) {
					self.enabled_fonts.push(web_fonts_google_view_model_font(font.family, font.family_name, font.is_enabled, font.style, font.style_string, font.url_param, font.weight, font.weight_string));
				});
				
				font.is_enabled(data.enabled);
			}, function(data) {
				
			});
		};
		
		self.select_font = function(font) {
			self.selected_font(font);
			
			var a = '#TB_inline?inlineId=google-web-fonts-font-details';
			tb_show(font.family_name(), a, false);
		};
		
		self.show_enabled_fonts = function() {
			var a = '#TB_inline?inlineId=google-web-fonts-enabled-fonts-list';
			tb_show(Google_Web_Fonts_Config.enabled_fonts_title, a, false);
		};
		
		/// On initialization
		self.get_fonts(self.font_search_keyword(), self.font_search_sort(), 0);
		
		return self;
	})(initializer);

	ko.applyBindings(web_fonts_google_view_model);
	
	$('#google-web-fonts-font-search-sort, #google-web-fonts-font-search-keyword').change(function(event) {
		web_fonts_google_view_model.get_fonts(web_fonts_google_view_model.font_search_keyword(), web_fonts_google_view_model.font_search_sort(), 0);
	});
	
	$('#google-web-fonts-fonts-filtering-form').submit(function(event) {
		event.preventDefault();
	});
	
	$('.google-web-fonts-close-thickbox').live('click', function(event) {
		event.preventDefault();
		
		tb_remove();
	});
	
	$('#google-web-fonts-font-items-wrapper-container .tablenav-fonts-pagination a').live('click', function(event) {
		event.preventDefault();
		
		var $this = $(this)
		, $container = $this.parents('#google-web-fonts-font-items-wrapper-container');
		
		var page_number_parts = $this.attr('href').match(/page_number=(\d+)/), page_number;
		if(page_number_parts && page_number_parts.length > 1) {
			page_number = page_number_parts[1];
		} else {
			page_number = 1;
		}
		
		web_fonts_google_view_model.get_fonts($container.attr('data-last-keyword'), $container.attr('data-last-sort'), page_number);
	});
});

var web_fonts_google_web_fonts = (function() {
	var $ = jQuery;
	var object = {};
	
	/// OBJECTS
	
	object.ajax_feedback = false;
	object.notice = false;
	
	/// FLAGS
	
	object.is_making_request = false;
	object.is_project_dirty = false;
	
	/// AJAX FEEDBACK
	
	object.get_ajax_feedback = function() {
		if(false == object.ajax_feedback) {
			object.ajax_feedback = $('#google-web-fonts-ajax-feedback');
			$('h2#google-web-fonts-web-fonts-nav a.nav-tab-active').append(object.ajax_feedback);
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
			object.notice = $('#google-web-fonts-ajax-notice');
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
				notice.addClass('google-web-fonts-error');
			} else {
				notice.removeClass('google-web-fonts-error');
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
			object.show_notice(Google_Web_Fonts_Config.request_in_progress_message, true);
		} else {
			object.is_making_request = blocking;
			
			if('object' != typeof(data)) {
				data = {};
			}
			
			data.action = action;
			data.nonce = $('#google-web-fonts-action-nonce').val();
			
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
	
	//// CLEAR AUTHENTICATION KEY
	
	object.clear_key = function(success_callback, error_callback) {
		object.make_request('web_fonts_google_web_fonts_clear_key', {}, success_callback, error_callback);
	};
	
	object.set_key = function(key, success_callback, error_callback) {
		object.make_request('web_fonts_google_web_fonts_set_key', { key: key }, success_callback, error_callback);
	};
	
	//// VALIDATE AND RETRIEVE KEY
	
	object.get_fonts = function(search_keyword, search_sort, page, success_callback, error_callback) {
		object.make_request('web_fonts_google_web_fonts_get_fonts', { search_keyword: search_keyword, search_sort: search_sort, page_number: page }, success_callback, error_callback, false);
	};
	
	object.set_font_status = function(font_data, enabled, success_callback, error_callback) {
		object.make_request('web_fonts_google_web_fonts_set_font_status', { font_data: font_data, enabled: (enabled ? 1 : 0) }, success_callback, error_callback, false);
	};
	
	return object;
})();
