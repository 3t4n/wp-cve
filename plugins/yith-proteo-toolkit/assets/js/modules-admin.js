(function ($) {
	// enable/disable a module
	$('span.form-switch').on('click', function () {
		var t = $(this),
			id = t.data('option_id');
		var proteoToolkitModulesNonce = yith_proteo_toolkit.proteoToolkitModulesNonce;

		t.animate({opacity: 0.25}, {easing: 'linear'});

		$.ajax({
			url: ajaxurl,
			data: {
				action: 'yith_proteo_toolkit_module_save',
				nonce: proteoToolkitModulesNonce,
				id: id
			},
			dataType: 'json',
			complete: function () {
				t.animate({opacity: 1}, {easing: 'linear'});
			},
			success: function (data) {
				if (data.success) {
					t.toggleClass('enabled');
					location.reload();
				}
			}
		});
	});
})(jQuery);