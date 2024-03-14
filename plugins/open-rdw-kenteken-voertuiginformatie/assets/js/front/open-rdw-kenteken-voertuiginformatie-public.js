jQuery(document).ready(function () {
	/**
	 * On th click we toggle the visibility.
	 */
	jQuery(document).on('click', '.open-rdw-head th', function(e) {
		e.preventDefault();
		jQuery(this).closest('tr').nextUntil('.open-rdw-head').toggle();
	});

	/**
	 * This code is responsible for contact form 7 support and makes an ajax call to our plugin.
	 */
	jQuery(document).on('change', '.open-data-rdw-hook', function() {
		var el = jQuery(this).closest('form');

		jQuery(el).find('#open_rdw-loading').show();
		jQuery(el).find('#open_rdw-error').hide();
		jQuery(el).find('#open_rdw-accepted').hide();

		var val = jQuery(this).val();
		var kenteken = val.replace(/[^a-z0-9]/gi, '').toLowerCase();

		var data = {
			action: 'get_open_rdw_data',
			kenteken: kenteken
		};

		jQuery.post(ajax.ajax_url, data, function(res) {

			jQuery(el).find('#open_rdw-loading').hide();
			if (typeof res.data == 'undefined' || res.data.errors) {
				jQuery(el).find('#open_rdw-error').show();
			} else {
				jQuery(el).find('#open_rdw-accepted').show();
			}

			jQuery.each(res.data.result, function(name, value) {
				if (name !== 'kenteken') {
					jQuery(el).find('input[name="' + name + '"]').val(value).trigger('change');
				}
			});
		});
	});
});
