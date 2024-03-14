(function ($) {
	wp.customize.bind('ready', function () {
		$('#clp-customizer-export').on('click', function (e) {
			e.preventDefault();

			var data = {
				action: 'clp_ajax_export_settings',
				nonce: $(this).data('nonce'),
			};

			$.post(ajaxurl, data, function (response) {
				if (response) {
					$('<iframe />')
						.attr('src', ajaxurl + '?action=clp_ajax_export_settings&nonce=' + data.nonce)
						.appendTo('body')
						.hide();
				}
			});
		});

		$('#clp-customizer-reset').on('click', function (e) {
			e.preventDefault();

			if (confirm('Are you sure you want to reset Custom Login Page to the default settings?')) {
				var data = {
					action: 'clp_ajax_reset_settings',
					nonce: $(this).data('nonce'),
				};

				$.post(ajaxurl, data, function (res) {
					document.location.reload();
				});
			}
		});

		// import button ajax call
		jQuery('#clp-customizer-import').on('change', function (e) {
			e.preventDefault();
			var reader = new FileReader();

			var $input = $('#clp-import-json');
			var $label = $(this).find('span');

			$input.prop('disabled', true);

			reader.onload = function (e) {
				var data = {
					action: 'clp_ajax_import_settings',
					nonce: $input.data('nonce'),
					json: e.target.result,
				};

				// change label
				$label.text('Importing..');

				$.post(ajaxurl, data, function (response) {
					if (response === 'success') {
						if (confirm('Import successful! Please reload the page.')) {
							document.location.reload();
						} else {
							$input.prop('disabled', false);
							$label.text('Import settings');
						}
					} else {
						$input.prop('disabled', false);
						$label.text('Import settings');
						alert('Import failed, please select correct JSON file with the settings.');
					}
				});
			};

			reader.readAsText(e.target.files[0]);
		});
	});
})(jQuery);
