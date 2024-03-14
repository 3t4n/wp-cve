/* global popupally_action_object */

jQuery(document).ready(function($) {
	var currently_opened = false,
		priority = false,
		configuration_map = new Object(),
		timed_delay_popup_config = false, exit_intent_popup_config = false; // used to ensure only one popup of the same kind can be enabled at a time

	initialize();
	// start (possible) autoplay videos
	function load_iframe_source(config) {
		if (config['popup-selector']) {
			$(config['popup-selector'] + ' iframe').each(function(){
				var src = $(this).attr('popupally-delay-src');
				if (typeof src !== typeof undefined && src !== false) {
					$(this).attr('src', src);
					$(this).attr('popupally-delay-src', '');
				}
			});
		}
	}
	function pause_video(config) {
		if (config['popup-selector']) {
			$(config['popup-selector'] + ' iframe').each(function(){
				var src = $(this).attr('src');
				$(this).attr('src', '');
				$(this).attr('popupally-delay-src', src);
			});
		}
	}
	function timedPopup(config) {
		if (false === priority || priority < config['priority']) {
			openPopup(config, 0);
			createPopupCookie(config['cookie-name'], config['cookie-duration']);
		}
	}
	function checkExitIntentPopup(config) {
		if (config['checking'] && (false === priority || priority < config['priority'])) {
			openPopup(config, 1);
			createPopupCookie(config['cookie-name'], config['cookie-duration']);
		}
		config['checking']=false;
	}
	function is_valid_expression(selector) {
		if (selector) {
			try{
				var elements = $(selector);
				return true;
			} catch (e) {
				return false;
			}
		}
		return false;
	}
	var is_dragging = false;
	$('html').on('touchstart', function() {
		is_dragging = false;
	});
	$('html').on('touchmove', function() {
		is_dragging = true;
	});
	function click_handler_wrapper(event, trigger_element, check_source, handler) {
		if (check_source && event.target !== trigger_element ) {
			return;
		}
		if (event.type === 'touchend') {
			if (is_dragging) {
				return false;
			}
		}
		return handler($(trigger_element));
	}
	function bind_click_handler(selector, check_source, handler) {
		if ($.isFunction($.fn.on)) {
			$('html').on('touchend click', selector, function(e) {
				return click_handler_wrapper(e, this, check_source, handler);
			});
		} else {
			$(selector).bind('touchend click', function(e) {
				return click_handler_wrapper(e, this, check_source, handler);
			});
		}
	}
	function process_config_triggers(config, hasOnFunction) {
		if ('disabled' in config && config['disabled']) {
			return;
		}
		pause_video(config);
		var hasOpenedCookie = readPopupCookie(config['cookie-name']);
		if (timed_delay_popup_config === false && config['timed-popup-delay'] >= 0) {
			timed_delay_popup_config = config;
			if (hasOpenedCookie === null) {
				setTimeout(function() {timedPopup(config);},config['timed-popup-delay']*1000);
			}
		}
		if (exit_intent_popup_config === false && config['enable-exit-intent-popup']) {
			exit_intent_popup_config = config;
			if (hasOpenedCookie === null) {
				config['previousY'] = -1;
				$(document).mousemove(function(e) {trackMouse(e, config);});
			}
		}
		if (is_valid_expression(config['close-trigger'])) {
			bind_click_handler(config['close-trigger'], true, function($this) {
				closePopup(config);
				return false;
			});
		}
	}
	function initialize() {
		var id = false, hasOnFunction = $.isFunction($.fn.on);
		configuration_map = popupally_action_object.popup_param;
		for (id in popupally_action_object.popup_param) {
			process_config_triggers(configuration_map[id], hasOnFunction);
		}

		$(document).keyup(function(e) {
			if (e.keyCode != 27)
				return;
			closePopup(currently_opened);
			return false;
		});
	}
	function trackMouse(e, config) {
		if (config['hasOpened']) return;
		config['checking']=false;
		if (e.clientY < config['previousY']) {
			var predictedY = e.clientY + (e.clientY - config['previousY']);
			if (predictedY<=10) {
				config['checking']=true;
				setTimeout(function(){checkExitIntentPopup(config);},1);
			}
		}
		config['previousY'] = e.clientY;
	}
	function closePopup(config) {
		currently_opened = false;
		if (config['popup-selector'] && config['popup-class']) {
			$(config['popup-selector']).removeClass(config['popup-class']);
		}
		pause_video(config);
	}
	function openPopup(config, type) {
		if (3 > type) { /* only record priority for time, exit-intent and scroll-trigger */
			if (false === priority || priority < config['priority']) {
				priority = config['priority'];
			}
		}
		if (currently_opened) return;
		currently_opened = config;
		if (config['popup-selector'] && config['popup-class']) {
			load_iframe_source(config);
			$(config['popup-selector']).addClass(config['popup-class']);
		}
	}
	function createPopupCookie(cookie_name,days) {
		var expires = "",
			date = null;
		if (cookie_name) {
			if (days !== 0) {
				date = new Date();
				date.setTime(date.getTime()+(days*24*60*60*1000));
				expires = "; expires="+date.toGMTString();
			}
			document.cookie = cookie_name+"=disable"+expires+"; path=/";
		}
	}
	function readPopupCookie(cookie_name) {
		if (cookie_name) {
			var nameEQ = cookie_name + "=",
			cookie = document.cookie,
			index = cookie.indexOf(nameEQ);
			if (index >= 0) {
				index += nameEQ.length;
				endIndex = cookie.indexOf(';', index);
				if (endIndex < 0) endIndex = cookie.length;
				return cookie.substring(index, endIndex);
			}
		}
		return null;
	}
	var ontraport_hidden_fields = ['contact_id','afft_','aff_','ref_','own_','sess_','utm_source','utm_medium','utm_term','utm_content','utm_campaign','referral_page','oprid'];
	function process_ontraport_hidden_fields($parent) {
		var i, cookie_value = null;
		for (i = 0; i < ontraport_hidden_fields.length;i++) {
			cookie_value = readPopupCookie(ontraport_hidden_fields[i]);
			if (cookie_value) {
				$parent.find('[name="' + ontraport_hidden_fields[i] + '"]').val(cookie_value);
			}
		}
	}
	$(document).on('submit', '.popupally-signup-form-ishdye', function() {
		var $parent = $(this);

		process_ontraport_hidden_fields($parent);
		return true;
	});
});