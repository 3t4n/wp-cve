jQuery(document).ready(function($) {

	function wc_sf_toggle_settings(selector, show) {
		var $items = $(selector).closest('tr');
		if (show) {
			$items.show();
		}
		else {
			$items.hide();
		}
	}

	// custom invoice numbering
	$('input[name=woocommerce_sf_invoice_custom_num]').on('click', function(e) {
		wc_sf_toggle_settings('.custom-invoice-numbering-item', $(this).prop('checked'));
	});
	wc_sf_toggle_settings('.custom-invoice-numbering-item', $('input[name=woocommerce_sf_invoice_custom_num]').prop('checked'));

	// custom comment
	$('input[name=woocommerce_sf_comments]').on('click', function(e) {
		wc_sf_toggle_settings('.custom-comment-item', $(this).prop('checked'));
	});
	wc_sf_toggle_settings('.custom-comment-item', $('input[name=woocommerce_sf_comments]').prop('checked'));

	// company billing fields
	$('input[name=woocommerce_sf_add_company_billing_fields]').on('click', function(e) {
		wc_sf_toggle_settings('.company-billing-fields-item', $(this).prop('checked'));
		wc_sf_toggle_settings('#woocommerce_sf_validate_eu_vat_number', $(this).prop('checked') && ('optional' == $('select[name=woocommerce_sf_add_company_billing_fields_vat]').val() || 'required' == $('select[name=woocommerce_sf_add_company_billing_fields_vat]').val()));
	});
	wc_sf_toggle_settings('.company-billing-fields-item', $('input[name=woocommerce_sf_add_company_billing_fields]').prop('checked'));

	// validate eu vat id
	$('select[name=woocommerce_sf_add_company_billing_fields_vat]').on('change', function(e) {
		wc_sf_toggle_settings('#woocommerce_sf_validate_eu_vat_number', $('input[name=woocommerce_sf_add_company_billing_fields]').prop('checked') && ('optional' == $(this).val() || 'required' == $(this).val()));
	});
	wc_sf_toggle_settings('#woocommerce_sf_validate_eu_vat_number', $('input[name=woocommerce_sf_add_company_billing_fields]').prop('checked') && ('optional' == $('select[name=woocommerce_sf_add_company_billing_fields_vat]').val() || 'required' == $('select[name=woocommerce_sf_add_company_billing_fields_vat]').val()));



	// add country settings
	$('body').on('click', 'a.sf-add-country-settings', function(e) {
		e.preventDefault();

		$('tbody#sf-countries').append(
			'<tr>' + $('tbody#sf-countries tr[data-name=template]').html() + '</tr>'
		);
	});



	// delete country settings
	$('body').on('click', 'a.sf-delete-country-settings', function(e) {
		e.preventDefault();

		$(this).closest('tr').remove();
	});



	// process country settings
	if ($('input[name=woocommerce_sf_country_settings]').length) {
		$('body').on('submit', 'form', function(e) {
			var country_settings = [];

			$('tbody#sf-countries tr:not([data-name=template])').each(function() {
				country_settings.push({
					'country': $(this).find('select[name=_country_country]').val(),
					'vat_id': $(this).find('input[name=_country_vat]').val(),
					'vat_id_only_final_consumer': $(this).find('input[name=_country_vat_id_only_final_consumer]').prop('checked'),
					'tax_id': $(this).find('input[name=_country_tax]').val(),
					'bank_account_id': $(this).find('input[name=_country_bank_account_id]').val(),
					'proforma_sequence_id': $(this).find('input[name=_country_proforma_invoice_sequence_id]').val(),
					'invoice_sequence_id': $(this).find('input[name=_country_invoice_sequence_id]').val(),
					'cancel_sequence_id': $(this).find('input[name=_country_cancel_sequence_id]').val()
				});
			});

			$('input[name=woocommerce_sf_country_settings]').val(JSON.stringify(country_settings));
		});
	}



	// test api connection
	$('a.wc-sf-api-test').on('click', function(e) {
		e.preventDefault();

		$('span.wc-sf-api-test-loading').show();
		$('span.wc-sf-api-test-ok').hide();
		$('span.wc-sf-api-test-fail').hide();
		$('span.wc-sf-api-test-fail-message').hide();

		var data = {
			'action': 'wc_sf_api_test',
			'woocommerce_sf_lang': $('input[name=woocommerce_sf_lang]:checked').val(),
			'woocommerce_sf_email': $('input[name=woocommerce_sf_email]').val(),
			'woocommerce_sf_apikey': $('input[name=woocommerce_sf_apikey]').val(),
			'woocommerce_sf_company_id': $('input[name=woocommerce_sf_company_id]').val(),
			'woocommerce_sf_sandbox': $('input[name=woocommerce_sf_sandbox]').prop('checked') ? 'yes' : 'no'
		};

		jQuery.post(ajaxurl, data, function(response) {
			$('span.wc-sf-api-test-loading').hide();

			if ('OK' == $.trim(response)) {
				$('span.wc-sf-api-test-ok').show();
			}
			else {
				$('span.wc-sf-api-test-fail').show();
				$('span.wc-sf-api-test-fail-message').text(response).show();
			}
		});
	});




	// check document pdf url
	$('a.sf-url-check').on('click', function(e) {
		e.preventDefault();

		var $this = $(this);
		var url = $this.attr('href');
		var data = {
			'action': 'wc_sf_url_check',
			'security': wc_sf.ajaxnonce,
			'url': url
		};

		jQuery.post(ajaxurl, data, function(response) {
			if (false == response.includes('200')) {

				// show error
				$this.replaceWith('<p class="wc-sf-url-error">' + ($this.attr('data-error') ?? 'Error') + '</p>');
			}
			else {

				// continue to url
				if ('_blank' == $this.attr('target')) {
					window.open($this.attr('href'), '_blank');
				}
				else {
					window.location = $this.attr('href');
				}
			}
		});
	});
});
