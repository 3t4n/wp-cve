(function ($) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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
	function paymentMethod() {
		this.init = function () {
			this.detectPaymentChange();
			this.dateChange();
			this.optionalFees();
			this.previousDate = '';
			this.removingExtraAddedFormField();
			this.dateChangeCount = 0;
		}

		this.optionalFees = function () {
			jQuery(document).on('change', '.pi-cefw-optional-fees', function () {
				jQuery('body').trigger('update_checkout', { update_shipping_method: true });
			});
		}

		this.dateChange = function () {
			var parent = this;
			jQuery(document).on('change', '#pi_delivery_date', function () {
				/**extra code added as it was leading to loop when used with different time for different pickup location addon plugin */
				var date = jQuery(this).val();
				var upd = parent.previousDate;
				/**
				 * during page reload we want to allow 2 request without blocking trigger
				 */
				if (upd != date && parent.dateChangeCount > 1) {
					parent.shippingChangeByDateChange();
				}

				/**
				 * we need this else plugin dont work will 2 changes are done in date
				 */
				/**
				 * in version 1.1.9.26 of different date and time for location we have disabled initial date selection so there is not more extra ajax so we dont need this counting mechanism any more 
				 */
				/** remove this in June 2022 */
				/* even remove "&& parent.dateChangeCount > 1" from line 56 */
				if (parent.dateChangeCount <= 1) {
					jQuery('body').trigger('update_checkout', { update_shipping_method: true });
				}
				/** End remove this in June 2022 */

				parent.previousDate = date;
				parent.dateChangeCount = parent.dateChangeCount + 1;
			});
		}

		this.detectPaymentChange = function () {
			var parent = this;
			jQuery('body').on('change', 'input[name="payment_method"]', function () {
				parent.cartReload();
			});
		}

		this.cartReload = function () {
			jQuery("body").trigger('update_checkout');
		}

		this.shippingChangeByDateChange = function () {
			var parent = this;
			var count = jQuery('input[name="trigger_by_pickup_location_change"]').length;
			if (count == 0) {
				jQuery('form.woocommerce-checkout').append('<input type="hidden" name="trigger_by_pickup_location_change" value="1">');
			}
			var new_count = jQuery('input[name="trigger_by_pickup_location_change"]').length;
			if (new_count > 0) {
				jQuery('body').trigger('update_checkout', { update_shipping_method: true });
			}
		}

		this.removingExtraAddedFormField = function () {
			jQuery(document).on('updated_checkout', function (e, response) {
				jQuery('form.woocommerce-checkout input[name="trigger_by_pickup_location_change"]').remove();
			});
		}
	}

	function pickupLocationChange() {
		jQuery(document).on("change", "input[name=\'pickup_location\'], select[name=\'pickup_location\']", function () {
			jQuery("body").trigger("update_checkout", { update_shipping_method: true });
		});
	}

	jQuery(function () {
		var paymentMethod_Obj = new paymentMethod();
		paymentMethod_Obj.init();

		pickupLocationChange();
	});

})(jQuery);
