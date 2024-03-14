(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	jQuery(function ($) {
		$("#pi_fees_start_time, #pi_fees_end_time").datepicker({
			dateFormat: 'yy/mm/dd',
		});

		function clearDate() {

			jQuery(document).on('click', ".pi-clear", function (e) {
				jQuery('input', jQuery(this).parent()).val("");
			})
		}
		clearDate();

		hideShowDropdown("#pi_fees_type", "#row_pi_percent_based_on", "percentage");
		hideShowDropdown("#pi_is_optional_fees", "#row_pi_selected_by_default", "yes");
		hideShowDropdown("#pi_is_optional_fees", "#row_pi_optional_title", "yes");

		function hideShowDropdown(parent, child, value_to_show) {
			var $ = jQuery;
			$(parent).on('change', function () {
				if ($(parent).val() == value_to_show) {
					$(child).fadeIn();
				} else {
					$(child).fadeOut();
				}
			});
			jQuery(parent).trigger("change");
		}

		function enableDisable() {
			jQuery(document).on('click', '.pi-cefw-status-change', function (e) {
				var id = jQuery(this).data('id');
				var status = jQuery(this).is(":checked") ? 1 : 0;
				jQuery("#pisol-cefw-fees-list-view").addClass('blocktable');
				jQuery.ajax({
					url: ajaxurl,
					method: 'POST',
					data: {
						id: id,
						status: status,
						action: 'pisol_cefw_change_status',
						_wpnonce: cefw_variables._wpnonce
					}
				}).always(function (d) {
					jQuery("#pisol-cefw-fees-list-view").removeClass('blocktable');
				})
			});
		}
		enableDisable();

		function ajaxSubmit() {
			$('#pisol-cefw-new-method').submit(function (e) {
				e.preventDefault();
				var form = $(this);
				blockUI()
				$.ajax({
					type: "POST",
					url: ajaxurl,
					dataType: 'json',
					data: form.serialize(), // serializes the form's elements.
					success: function (data) {


						if (data.error != undefined) {
							var html = ''

							jQuery.each(data.error, function (index, val) {
								html += '<p class="pi-cefw-notice error">' + val + '<span class="pi-close-notification dashicons dashicons-no-alt"></span></p>';
							});

							jQuery("#pisol-cefw-notices").html(html);

							$.alert({
								title: 'Error',
								content: html,
								type: 'red',
								columnClass: 'small'
							});
						}

						if (data.success != undefined) {
							var html = '<p class="pi-cefw-notice success">' + data.success + '<span class="pi-close-notification dashicons dashicons-no-alt"></span></p>';
							jQuery("#pisol-cefw-notices").html(html);

							$.alert({
								title: 'Success',
								content: html,
								type: 'green',
								columnClass: 'small'
							});
						}

						if (data.redirect != undefined) {
							window.location = data.redirect;
						}
					}
				}).always(function () {
					unblockUI();
				});
			});
		}
		ajaxSubmit();

		function blockUI() {
			jQuery("#pisol-cefw-new-method").addClass('pi-blocked')
		}

		function unblockUI() {
			jQuery("#pisol-cefw-new-method").removeClass('pi-blocked')
		}

		function hideNotification() {
			jQuery(document).on('click', '.pi-close-notification', function () {
				jQuery(this).parent().remove();
			})
		}
		hideNotification();

		function taxClass() {
			jQuery(document).on('change', '#pi_fees_taxable', function () {
				var val = jQuery(this).val();
				if (val == 'yes') {
					jQuery("#row_pi_fees_tax_class").fadeIn();
				} else {
					jQuery("#row_pi_fees_tax_class").fadeOut();
				}
			});
			jQuery('#pi_fees_taxable').trigger('change');
		}
		taxClass();

		jQuery("#pi_currency").selectWoo();

	});

})(jQuery);
