(function ($) {
	"use strict";

	var FISettings = {

		showDependentNipFields: function () {
			var field = $("#woocommerce_add_nip_field");
			if (field.length) {
				field.change(function () {
					if ($(this).is(':checked')) {
						$('.nip-additional-fields').closest('tr').show();
					} else {
						$('.nip-additional-fields').closest('tr').hide();
					}
				});
				field.trigger('change');
			}
		},

		showDependentSignatureFields: function () {
			var field = $("#show_signatures");
			if (field.length) {
				field.change(function () {
					if ($(this).is(':checked')) {
						$('#signature_user').closest('tr').show();
					} else {
						$('#signature_user').closest('tr').hide();
					}
				});
				field.trigger('change');
			}
		},

		showDependentExchangeFields: function () {
			var field = $('.woocommerce_currency_exchange_enable');
			if (field.length) {
				field.change(function () {
					let value = $(this).val();
					if (value === 'on' || value === 'yes_without_tax') {
						$('.exchange-table-fields').closest('tr').show();
					} else {
						$('.exchange-table-fields').closest('tr').hide();
					}
				});
				field.trigger('change');
			}
		},

		showDependentMossFields: function () {
			let field = $('.woocommerce_eu_vat_vies_validate');
			if (field.length) {
				field.change(function () {
					if ($(this).is(':checked')) {
						$('.vies-validation-fields').closest('tr').show();
					} else {
						$('.vies-validation-fields').closest('tr').hide();
					}
				});
				field.trigger('change');
			}
		},


		taxes: function () {

			var fixHelper = function (e, ui) {
				ui.children().each(function () {
					$(this).width($(this).width());
				});
				return ui;
			};

			$('#flexible_invoices_tax_table tbody').sortable({
				handle: 'td:first',
				helper: fixHelper,
			});

			let table = jQuery('#flexible_invoices_tax_table');
			if (table.length) {
				table.on('click', '#insert_tax', function () {
					let index = 0;
					let new_row = $($('#tax-rates-row').html());
					$(document).find('.row-num').each(function () {
						let new_index = parseInt($(this).val());
						if (new_index > index) {
							index = new_index;
						}
					});
					let increaseIndex = index + 1;
					new_row.find('input').attr('value', '');
					new_row.find('.row-num').val(increaseIndex);
					var inputname = new RegExp('\\[' + 0 + '\\]', 'gm');
					let elem = new_row.html().replace(inputname, '[' + increaseIndex + ']');
					$('#flexible_invoices_tax_table tbody').append('<tr>' + elem + '</tr>');
					return false;
				});

				table.on('click', '.delete-item', function () {
					let rows = $(this).closest('tbody').find('tr');
					if (rows.length > 1) {
						$(this).closest('tr').remove();
					}

					return false;
				});
			}
		},

		initSelect2: function () {
			var select = jQuery('.select2');
			if (select.length) {
				select.select2({
					width: '400px'
				});
			}
		},

		subSettings: function () {
			let tab_cookie_name = 'general';
			let nav_active = jQuery('.nav-tab-active');
			if (nav_active.length) {
				const queryString = window.location.search;
				const urlParams = new URLSearchParams(queryString);
				tab_cookie_name = urlParams.get('tab');
			}
			let sub_table = jQuery('.sub-table');
			if (sub_table.length) {
				jQuery('.js-subsubsub-wrapper a').click(function () {
					jQuery(this).closest('ul').find('.current').removeClass('current');
					jQuery(this).addClass('current');
					jQuery('.form-table').hide();
					sub_table.hide();
					jQuery('.field-settings-' + jQuery(this).attr('id').replace('tab-anchor-', '')).show();
					wpCookies.set('fi-settings-tab-' + tab_cookie_name, jQuery(this).attr('id').replace('tab-anchor-', ''));
				});


				var tab_cookie = wpCookies.get('fi-settings-tab-' + tab_cookie_name);
				if (tab_cookie) {
					var tab_element = jQuery('.sub-tab-' + tab_cookie);
					tab_element.click();
				} else {
					sub_table.hide();
					sub_table.first().show()
				}
			}
		},
		editDisabledFields: function () {
			let disabled_field = jQuery('.form-table .edit_disabled_field');
			if (disabled_field.length) {
				disabled_field.after('<a class="remove-disabled" href="#"><span class="dashicons dashicons-edit"></span></a>');
			}

			jQuery('.form-table').on('click', '.remove-disabled', function () {
				let input = $(this).prev();
				if (input.attr('disabled') === 'disabled') {
					$(this).prev().removeAttr('disabled');
				} else {
					$(this).prev().attr('disabled', 'disabled');
				}

				return false;
			});

		},

	}

	FISettings.showDependentNipFields();
	FISettings.showDependentSignatureFields();
	FISettings.showDependentExchangeFields();
	FISettings.showDependentMossFields();
	FISettings.initSelect2();
	FISettings.editDisabledFields();
	FISettings.taxes();
	FISettings.subSettings();

})
(jQuery);
