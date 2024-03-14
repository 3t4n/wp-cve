/**
 * Custom jQuery functions and trigger events
 */
jQuery(document).ready(function ($) {
	/* CPT switch */
	$('.ctp-switch').on('click', function () {
		var loader = $(this).parent().next();

		loader.show();

		var main_control = $(this);
		var data = {
			action: 'ctp_switch',
			value: this.checked,
			security: $('#ctp_tabs_nonce').val(),
			option_name: main_control.attr('rel'),
		};

		$.post(ajaxurl, data, function (response) {
			response = $.trim(response);

			if ('1' == response) {
				main_control.parent().parent().addClass('active');
				main_control.parent().parent().removeClass('inactive');
			} else if ('0' == response) {
				main_control.parent().parent().addClass('inactive');
				main_control.parent().parent().removeClass('active');
			} else {
				alert(response);
			}
			loader.hide();
		});
	});
	/* CPT switch End */
	$('#setting-error-settings_updated').hide();

	// Show Hide Toggle Box
	$('.option-content').hide();

	$('.open').show();

	$('h3.option-toggle').click(function (e) {
		e.preventDefault();
		if (!$(this).hasClass('option-active')) {
			$(this).siblings('.option-content').stop(true, true).hide(400);
			$(this).siblings('.option-toggle').removeClass('option-active');
			$(this)
				.toggleClass('option-active')
				.next()
				.stop(true, true)
				.slideToggle(400);
			return false;
		}
	});

	setTimeout(function () {
		$('.fade').fadeOut('slow', function () {
			$('.fade').remove();
		});
	}, 2000);

	if ($('#catchwebtools_seo_title').length) {
		var length = $('#catchwebtools_seo_title').val().length;
		$('#catchwebtools_seo_title_left').html(70 - length);

		length = $('#catchwebtools_seo_description').val().length;
		$('#catchwebtools_seo_description_left').html(156 - length);

		$('#catchwebtools_seo_title').keypress(function () {
			var length = $(this).val().length;
			$('#catchwebtools_seo_title_left').html(70 - length);
		});
		$('#catchwebtools_seo_description').keypress(function () {
			var length = $(this).val().length;
			$('#catchwebtools_seo_description_left').html(156 - length);
		});
	}

	//For Color picker in social icons
	var options_social_color = {
		change: function (event, ui) {
			var icon_brand_color = $(
				'#catchwebtools_social_icon_brand_color'
			).val();

			$('.genericon').css({
				color: ui.color.toString(),
			});

			$('.genericon').hover(
				function () {
					$(this).css('color', '');
				},
				function () {
					$(this).css('color', ui.color.toString());
				}
			);
		},
	};

	//For Color picker in social icons hover
	var options_social_hover_color = {
		change: function (event, ui) {
			var icon_brand_color = $(
				'#catchwebtools_social_icon_brand_color'
			).val();

			if (
				!(
					'hover' == icon_brand_color ||
					'hover-static' == icon_brand_color
				)
			) {
				$('.genericon').hover(
					function () {
						$(this).css('color', ui.color.toString());
					},
					function () {
						$(this).css(
							'color',
							$('#catchwebtools_social_color').val()
						);
					}
				);
			}
		},
	};

	$('#catchwebtools_social_color').wpColorPicker(options_social_color);

	$('#catchwebtools_social_hover_color').wpColorPicker(
		options_social_hover_color
	);

	var default_color = $('#catchwebtools_social_color').val();

	var default_hover_color = $('#catchwebtools_social_hover_color').val();

	var icon_size = $('#catchwebtools_social_icon_size').val() + 'px';

	var icon_brand_color = $('#catchwebtools_social_icon_brand_color').val();

	if ('hover-static' != icon_brand_color) {
		$('.genericon').css({
			color: default_color,
		});

		$('.genericon').hover(
			function () {
				$(this).css('color', '');
			},
			function () {
				$(this).css('color', default_color);
			}
		);
	}

	if (!('hover' == icon_brand_color || 'hover-static' == icon_brand_color)) {
		$('.genericon').hover(
			function () {
				$(this).css('color', default_hover_color);
			},
			function () {
				$(this).css('color', default_color);
			}
		);
	}

	//Fire on page load
	$('.genericon').css({
		'font-size': icon_size,
		width: icon_size,
		height: icon_size,
	});

	$('#catchwebtools_social_icon_size').change(function () {
		var size = this.value + 'px';

		$('.genericon').css({
			'font-size': size,
			width: size,
			height: size,
		});
	});

	//Fire on page load
	if ('hover-static' == icon_brand_color) {
		$('#catchwebtools_social_color_hover_main').hide();
		$('#catchwebtools_social_color_main').hide();
	} else if ('hover' == icon_brand_color) {
		$('#catchwebtools_social_color_hover_main').hide();
		$('#catchwebtools_social_color_main').show();
	} else {
		$('#catchwebtools_social_color_hover_main').show();
		$('#catchwebtools_social_color_main').show();
	}

	$('#catchwebtools_social_icon_brand_color').change(function () {
		var icon_brand_color = $(
			'#catchwebtools_social_icon_brand_color'
		).val();

		$('.catchwebtools-social').removeClass('social-brand-hover');
		$('.catchwebtools-social').removeClass('social-brand-static');

		if ('hover-static' == icon_brand_color) {
			$('#catchwebtools_social_color_hover_main').hide();
			$('#catchwebtools_social_color_main').hide();
			$('.catchwebtools-social').addClass('social-brand-static');
			$('.genericon').css({
				color: '',
			});

			$('.genericon').hover(
				function () {
					$(this).css('color', '');
				},
				function () {
					$(this).css('color', '');
				}
			);
		} else if ('hover' == icon_brand_color) {
			$('#catchwebtools_social_color_hover_main').hide();
			$('#catchwebtools_social_color_main').show();
			$('.catchwebtools-social').addClass('social-brand-hover');
		} else {
			$('#catchwebtools_social_color_hover_main').show();
			$('#catchwebtools_social_color_main').show();
		}
	});

	// jQuery Match Height init for sidebar spots
	$(document).ready(function () {
		$(
			'.catchp-sidebar-spot .sidebar-spot-inner, .col-2 .catchp-lists li, .col-3 .catchp-lists li, .catch-modules'
		).matchHeight();
	});
});

/**
 * Function to inverted hex color with the one in parameter
 * @param  {[string]} hexTripletColor [hex color]
 * @return {[string]}                 [inverted hex color]
 */
function invertColor(hexTripletColor) {
	var color = hexTripletColor;
	color = color.substring(1); // remove #
	color = parseInt(color, 16); // convert to integer
	color = 0xffffff ^ color; // invert three bytes
	color = color.toString(16); // convert to hex
	color = ('000000' + color).slice(-6); // pad with leading zeros
	color = '#' + color; // prepend #
	return color;
}

(function ($) {
	'use strict';

	/**
	 * Custom jQuery functions and trigger events
	 */
	jQuery(document).ready(function ($) {
		$('#setting-error-settings_updated').hide();

		setTimeout(function () {
			$('.fade').fadeOut('slow', function () {
				$('.fade').remove();
			});
		}, 2000);

		var custom_uploader;

		$('.st_upload_button').click(function (e) {
			e.preventDefault();

			var title, this_selector, button_text, attachment;

			title = $(this).val();

			this_selector = $(this); //For later use

			button_text = $(this).attr('ref');

			//Extend the wp.media object
			custom_uploader = wp.media.frames.file_frame = wp.media({
				title: title,
				button: {
					text: button_text,
				},
				multiple: true,
			});

			//When a file is selected, grab the URL and set it as the text field's value
			custom_uploader.on('select', function () {
				attachment = custom_uploader
					.state()
					.get('selection')
					.first()
					.toJSON();
				this_selector.prev().val(attachment.url);
			});

			//Open the uploader dialog
			custom_uploader.open();
		});

		//For Color picker in icon color
		var myOptions = {
			change: function (event, ui) {
				$('.dashicon_to_top_admin').css({
					color: ui.color.toString(),
				});
			},
		};

		$('.catchwebtools_to_top_options_icon_color').wpColorPicker(myOptions);

		//For Color picker in icon background color
		var myOptions2 = {
			change: function (event, ui) {
				$('.dashicon_to_top_admin').css({
					'background-color': ui.color.toString(),
				});
			},
		};

		$('.catchwebtools_to_top_options_icon_bg_color').wpColorPicker(
			myOptions2
		);

		$('#catchwebtools_to_top_options_border_radius').change(function () {
			$('.dashicon_to_top_admin').css({
				'-webkit-border-radius':
					$('#catchwebtools_to_top_options_border_radius').val() +
					'%',
				'-moz-border-radius':
					$('#catchwebtools_to_top_options_border_radius').val() +
					'%',
				'border-radius':
					$('#catchwebtools_to_top_options_border_radius').val() +
					'%',
			});
		});

		$('#catchwebtools_to_top_options_icon_size').change(function () {
			$('.dashicon_to_top_admin').css({
				'font-size':
					$('#catchwebtools_to_top_options_icon_size').val() + 'px',
				height:
					$('#catchwebtools_to_top_options_icon_size').val() + 'px',
				width:
					$('#catchwebtools_to_top_options_icon_size').val() + 'px',
			});
		});

		$('.dashicon_to_top_admin').css({
			'-webkit-border-radius':
				$('#catchwebtools_to_top_options_border_radius').val() + '%',
			'-moz-border-radius':
				$('#catchwebtools_to_top_options_border_radius').val() + '%',
			'border-radius':
				$('#catchwebtools_to_top_options_border_radius').val() + '%',
			color: $('.catchwebtools_to_top_options_icon_color').val(),
			'background-color': $(
				'.catchwebtools_to_top_options_icon_bg_color'
			).val(),
			'font-size':
				$('#catchwebtools_to_top_options_icon_size').val() + 'px',
			height: $('#catchwebtools_to_top_options_icon_size').val() + 'px',
			width: $('#catchwebtools_to_top_options_icon_size').val() + 'px',
		});

		$('#catchwebtools_to_top_options_style').change(function () {
			var value;
			value = $(this).val();
			if ('image' == value) {
				$('.catchwebtools_to_top_options_image_settings').show();
				$('.catchwebtools_to_top_options_icon_settings').hide();
			} else {
				$('.catchwebtools_to_top_options_icon_settings').show();
				$('.catchwebtools_to_top_options_image_settings').hide();
			}
		});

		var value;
		value = $('#catchwebtools_to_top_options_style').val();
		if ('image' == value) {
			$('.catchwebtools_to_top_options_image_settings').show();
			$('.catchwebtools_to_top_options_icon_settings').hide();
		} else {
			$('.catchwebtools_to_top_options_icon_settings').show();
			$('.catchwebtools_to_top_options_image_settings').hide();
		}
	});
})(jQuery);

/**
 * Facebook Script
 */
(function (d, s, id) {
	var js,
		fjs = d.getElementsByTagName(s)[0];

	if (d.getElementById(id)) return;

	js = d.createElement(s);
	js.id = id;

	js.src =
		'//connect.facebook.net/en_US/all.js#xfbml=1&appId=276203972392824';

	fjs.parentNode.insertBefore(js, fjs);
})(document, 'script', 'facebook-jssdk');

/**
 * Twitter Script
 */
!(function (d, s, id) {
	var js,
		fjs = d.getElementsByTagName(s)[0];

	if (!d.getElementById(id)) {
		js = d.createElement(s);

		js.id = id;

		js.src = '//platform.twitter.com/widgets.js';

		fjs.parentNode.insertBefore(js, fjs);
	}
})(document, 'script', 'twitter-wjs');
