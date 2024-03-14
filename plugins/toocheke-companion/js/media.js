jQuery(document).ready(function ($) {
	/* Comics Navigation  Images */
	var mediaUploader;
	var hiddenField = "";
	var imageField = "";

	if ($('#toocheke-comics-navigation').is(":checked")) {
		$('#toocheke-comics-navigation').closest('tr').siblings().hide();
	}

	$('.upload-custom-button').on('click', function (e) {
		e.preventDefault();
		hiddenField = $(this).data('hidden');
		imageField = $(this).data('image');
		if (mediaUploader) {
			mediaUploader.open();
			return;
		}

		mediaUploader = wp.media.frames.file_frame = wp.media({
			title: 'Select an Image For Your Button',
			button: {
				text: 'Choose this image'
			},
			multiple: false
		});

		mediaUploader.on('select', function () {

		});

		mediaUploader.on('close', function () {
			attachment = mediaUploader.state().get('selection').first().toJSON();
			$('#' + hiddenField).val(attachment.url);
			$('#' + imageField).attr("src", attachment.url);
			hiddenField = "";
			imageField = "";
		});

		mediaUploader.open();

	});

	$('#toocheke-comics-navigation').click(function () {
		if ($(this).is(":checked")) {
			$(this).closest('tr').siblings().animate({ height: 'toggle', opacity: 'toggle' }, 'slow');
		}
		else if ($(this).is(":not(:checked)")) {
			$(this).closest('tr').siblings().animate({ height: 'toggle', opacity: 'toggle' }, 'slow');
		}
	});

	/* Series Hero Image */
	// Uploading files
	//Desktop
	var series_hero_desktop_file_frame;

	jQuery.fn.upload_series_hero_image = function (button) {
		var button_id = button.attr('id');
		var field_id = button_id.replace('_button', '');

		// If the media frame already exists, reopen it.
		if (series_hero_desktop_file_frame) {
			series_hero_desktop_file_frame.open();
			return;
		}

		// Create the media frame.
		series_hero_desktop_file_frame = wp.media.frames.series_hero_desktop_file_frame = wp.media({
			title: jQuery(this).data('uploader_title'),
			button: {
				text: jQuery(this).data('uploader_button_text'),
			},
			multiple: false
		});

		// When an image is selected, run a callback.
		series_hero_desktop_file_frame.on('select', function () {
			jQuery('#series-hero-metabox img').attr('src', '');
			var attachment = series_hero_desktop_file_frame.state().get('selection').first().toJSON();
			jQuery("#" + field_id).val(attachment.id);
			jQuery("#series-hero-metabox img").attr('src', attachment.url);
			jQuery("#series-hero-metabox img").attr('srcset', attachment.url);
			jQuery('#series-hero-metabox img').show();
			jQuery('#' + button_id).attr('id', 'remove_series_hero_image_button');
			jQuery('#remove_series_hero_image_button').text('Remove series hero image');
		});

		// Finally, open the modal
		series_hero_desktop_file_frame.open();
	};

	jQuery('#series-hero-metabox').on('click', '#upload_series_hero_image_button', function (event) {
		event.preventDefault();
		jQuery.fn.upload_series_hero_image(jQuery(this));
	});

	jQuery('#series-hero-metabox').on('click', '#remove_series_hero_image_button', function (event) {
		event.preventDefault();
		jQuery('#upload_series_hero_image').val('');
		jQuery('#series-hero-metabox img').attr('src', '');
		jQuery("#series-hero-metabox img").attr('srcset', '');
		jQuery('#series-hero-metabox img').hide();
		jQuery(this).attr('id', 'upload_series_hero_image_button');
		jQuery('#upload_series_hero_image_button').text('Set series hero image');
	});

	//Mobile
	var series_hero_mobile_file_frame;

	jQuery.fn.upload_series_mobile_hero_image = function (button) {
		var button_id = button.attr('id');
		var field_id = button_id.replace('_button', '');

		// If the media frame already exists, reopen it.
		if (series_hero_mobile_file_frame) {
			series_hero_mobile_file_frame.open();
			return;
		}

		// Create the media frame.
		series_hero_mobile_file_frame = wp.media.frames.series_hero_mobile_file_frame = wp.media({
			title: jQuery(this).data('uploader_title'),
			button: {
				text: jQuery(this).data('uploader_button_text'),
			},
			multiple: false
		});

		// When an image is selected, run a callback.
		series_hero_mobile_file_frame.on('select', function () {
			var attachment = series_hero_mobile_file_frame.state().get('selection').first().toJSON();
			jQuery("#" + field_id).val(attachment.id);
			jQuery("#series-mobile-hero-metabox img").attr('src', attachment.url);
			jQuery("#series-mobile-hero-metabox img").attr('srcset', attachment.url);
			jQuery('#series-mobile-hero-metabox img').show();
			jQuery('#' + button_id).attr('id', 'emove_series_mobile_hero_image_button');
			jQuery('#emove_series_mobile_hero_image_button').text('Remove series hero image');
		});

		// Finally, open the modal
		series_hero_mobile_file_frame.open();
	};

	jQuery('#series-mobile-hero-metabox').on('click', '#upload_series_mobile_hero_image_button', function (event) {
		event.preventDefault();
		jQuery.fn.upload_series_mobile_hero_image(jQuery(this));
	});

	jQuery('#series-mobile-hero-metabox').on('click', '#remove_series_mobile_hero_image_button', function (event) {
		event.preventDefault();
		jQuery('#upload_series_mobile_hero_image').val('');
		jQuery('#series-mobile-hero-metabox img').attr('src', '');
		jQuery("#series-mobile-hero-metabox img").attr('srcset', '');
		jQuery('#series-mobile-hero-metabox img').hide();
		jQuery(this).attr('id', 'upload_series_mobile_hero_image_button');
		jQuery('#upload_series_mobile_hero_image_button').text('Set series hero image');
	});
	//Background
	var series_bg_file_frame;

	jQuery.fn.upload_series_bg_image = function (button) {
		var button_id = button.attr('id');
		var field_id = button_id.replace('_button', '');

		// If the media frame already exists, reopen it.
		if (series_bg_file_frame) {
			series_bg_file_frame.open();
			return;
		}

		// Create the media frame.
		series_bg_file_frame = wp.media.frames.series_bg_file_frame = wp.media({
			title: jQuery(this).data('uploader_title'),
			button: {
				text: jQuery(this).data('uploader_button_text'),
			},
			multiple: false
		});

		// When an image is selected, run a callback.
		series_bg_file_frame.on('select', function () {
			var attachment = series_bg_file_frame.state().get('selection').first().toJSON();
			jQuery("#" + field_id).val(attachment.id);
			jQuery("#series-bg-image-metabox img").attr('src', attachment.url);
			jQuery("#series-bg-image-metabox img").attr('srcset', attachment.url);
			jQuery('#series-bg-image-metabox img').show();
			jQuery('#' + button_id).attr('id', 'remove_series_bg_image_button');
			jQuery('#remove_series_bg_image_button').text('Remove series background image image');
		});

		// Finally, open the modal
		series_bg_file_frame.open();
	};

	jQuery('#series-bg-image-metabox').on('click', '#upload_series_bg_image_button', function (event) {
		event.preventDefault();
		jQuery.fn.upload_series_bg_image(jQuery(this));
	});

	jQuery('#series-bg-image-metabox').on('click', '#remove_series_bg_image_button', function (event) {
		event.preventDefault();
		jQuery('#upload_series_bg_image').val('');
		jQuery('#series-bg-image-metabox img').attr('src', '');
		jQuery("#series-bg-image-metabox img").attr('srcset', '');
		jQuery('#series-bg-image-metabox img').hide();
		jQuery(this).attr('id', 'upload_series_bg_image_button');
		jQuery('#upload_series_bg_image_button').text('Set series background image');
	});

});
