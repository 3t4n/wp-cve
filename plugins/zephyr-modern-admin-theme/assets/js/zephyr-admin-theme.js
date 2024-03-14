(function () {
	jQuery(document).ready(function ($) {
		jQuery('.zat-color-picker').wpColorPicker();

		var mediaUploader;

		jQuery('#zat-login-logo-button, .zat-login-logo-image').on('click', function () {
			if (mediaUploader) {
				mediaUploader.open();
				return;
			}

			mediaUploader = wp.media.frames.file_frame = wp.media({
				title: 'Choose Logo',
				button: {
					text: 'Choose Logo'
				}, multiple: false
			});

			var image_holder = jQuery('.zat-login-logo-image');
			var image_input = jQuery('#zat-login-logo-value');

			mediaUploader.on('select', function () {
				var attachment = mediaUploader.state().get('selection').first().toJSON();
				image_holder.css('background-image', 'url(' + attachment.url + ')');
				image_input.val(attachment.url);
			});
			// Open the uploader dialog
			mediaUploader.open();
		});

		jQuery('#zat-login-logo-reset-button').on('click', function () {
			var image_holder = jQuery('.zat-login-logo-image');
			var image_input = jQuery('#zat-login-logo-value');
			image_input.val('');
			image_holder.css('background-image', 'url(' + jQuery(this).data('reset-logo') + ')');
		});

		var dashboardLogoUploader;

		jQuery('#zat-dashboard-logo-button, .zat-dashboard-logo-image').on('click', function () {
			if (dashboardLogoUploader) {
				dashboardLogoUploader.open();
				return;
			}

			dashboardLogoUploader = wp.media.frames.file_frame = wp.media({
				title: 'Choose Logo',
				button: {
					text: 'Choose Logo'
				}, multiple: false
			});

			var image_holder = jQuery('.zat-dashboard-logo-image');
			var image_input = jQuery('#zat-dashboard-logo-value');

			dashboardLogoUploader.on('select', function () {
				var attachment = dashboardLogoUploader.state().get('selection').first().toJSON();
				image_holder.css('background-image', 'url(' + attachment.url + ')');
				image_input.val(attachment.url);
			});
			// Open the uploader dialog
			dashboardLogoUploader.open();
		});

		jQuery('#zat-dashboard-logo-reset-button').on('click', function () {
			var image_holder = jQuery('.zat-dashboard-logo-image');
			var image_input = jQuery('#zat-dashboard-logo-value');
			image_input.val('');
			image_holder.css('background-image', 'url(' + jQuery(this).data('reset-logo') + ')');
		});

		jQuery('.zat-theme-palette').on('click', function () {
			var palette = jQuery(this);
			var primary_color = palette.data('primary-color');
			var secondary_color = palette.data('secondary-color');
			var background_color = palette.data('background-color');
			var gradient_start = palette.data('gradient-start');
			var gradient_end = palette.data('gradient-end');
			var button_primary = palette.data('button-primary');
			var button_hover = palette.data('button-hover');
			var text_color = palette.data('text-color');

			jQuery('#zpm-primary-color').val(primary_color).trigger('change');
			jQuery('#zpm-secondary-color').val(secondary_color).trigger('change');
			jQuery('#zpm-background-color').val(background_color).trigger('change');
			jQuery('#zpm-gradient-start').val(gradient_start).trigger('change');
			jQuery('#zpm-gradient-end').val(gradient_end).trigger('change');
			jQuery('#zpm-button-primary').val(button_primary).trigger('change');
			jQuery('#zpm-button-hover').val(button_hover).trigger('change');
			jQuery('#zat-text-color').val(text_color).trigger('change');
		});

		jQuery('#zat-dark-mode-switch').on('change', function () {
			jQuery('#zat-theme-palettes').addClass('dark-mode');
		});

		jQuery('#zat-light-mode-switch').on('change', function () {
			jQuery('#zat-theme-palettes').removeClass('dark-mode');
		});



		// Dark Theme
		if (zat_localized.settings.theme_mode == 'dark') {
			jQuery('div, span, p, article, section').filter(function () {
				var match = 'rgb(255, 255, 255)'; // match background-color: black
				/*
					true = keep this element in our wrapped set
					false = remove this element from our wrapped set*/
				if (jQuery(this).hasClass('zpm-modal') || jQuery(this).hasClass('modal')) {
					return false;
				}
				return (jQuery(this).css('background-color') == match);

			}).css({
				backgroundColor: 'rgba(255,255,255,.05)',
				color: '#fff',
				borderColor: 'rgba(255,255,255,.1)',
			}).find('*').css('color', '#fff');

			jQuery('ul').filter(function () {
				var match = 'rgb(255, 255, 255)'; // match background-color: black
				/*
					true = keep this element in our wrapped set
					false = remove this element from our wrapped set
																	 */
				return (jQuery(this).css('background-color') == match);

			}).css({
				backgroundColor: zat_localized.settings.secondary_color,
				color: '#fff',
				borderColor: zat_localized.settings.secondary_color,
			}).find('*').css('color', '#fff');
		}
	});

	jQuery(window).load(function () {
		// Remove parenthes from counts
		jQuery('.count').text(function (_, text) {
			return text.replace(/\(|\)/g, '');
		}).css('color', '#fff !important');
	});
})(jQuery);
