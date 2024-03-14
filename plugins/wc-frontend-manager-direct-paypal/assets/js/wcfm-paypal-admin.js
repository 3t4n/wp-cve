;(function ($, document) {
	"use strict";

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

	const Wcfm_PayPal_MarketPlace_Settings = {
		init: function () {
			this.setup();
			this.bindEvents();
			this.inputToggle();
			this.disbursementPeriodToggle();
			this.disbursementPeriodValidation();
			this.noticeIntervalToggle();
		},

		setup: function () {
			this.document = $(document);
			this.sandbox_mode = `#${wcfm_paypal_admin_l10n.payment_id_prefix}test_mode`;
			this.disbursement_mode = `#${wcfm_paypal_admin_l10n.payment_id_prefix}disbursement_mode`;
			this.disbursement_delay_period = `#${wcfm_paypal_admin_l10n.payment_id_prefix}disbursement_delay_period`;
			this.notice_checkbox = `#${wcfm_paypal_admin_l10n.payment_id_prefix}display_notice_to_non_connected_sellers`;
			this.notice_interval = `#${wcfm_paypal_admin_l10n.payment_id_prefix}display_notice_interval`;
		},

		bindEvents: function () {
			// toggle sandbox mode
			this.document.on( "change", this.sandbox_mode, this.inputToggle.bind(this) );
			// toggle disbursement mode
			this.document.on( "change", this.disbursement_mode, this.disbursementPeriodToggle.bind(this) );
			// validate disbursement period validation
			this.document.on( "change", this.disbursement_delay_period, this.disbursementPeriodValidation.bind(this) );
			// toggle notice interval fields
			this.document.on( "change", this.notice_checkbox, this.noticeIntervalToggle.bind(this) );
		},

		inputToggle: function () {
			let settings_input_ids = ["client_id", "client_secret"];

			if ( $(this.sandbox_mode).is(":checked") ) {
				settings_input_ids.map( function( id ) {
					$(`#${wcfm_paypal_admin_l10n.payment_id_prefix}sandbox_${id}`).closest("tr").show();
					$(`#${wcfm_paypal_admin_l10n.payment_id_prefix}${id}`).closest("tr").hide();
				} );
			} else {
				settings_input_ids.map( function( id ) {
					$(`#${wcfm_paypal_admin_l10n.payment_id_prefix}sandbox_${id}`).closest("tr").hide();
					$(`#${wcfm_paypal_admin_l10n.payment_id_prefix}${id}`).closest("tr").show();
				} );
			}
		},

		disbursementPeriodToggle: function() {
			let val = $(this.disbursement_mode).val();
			if ( val === 'DELAYED' ) {
				$(`#${wcfm_paypal_admin_l10n.payment_id_prefix}disbursement_delay_period`).closest('tr').show();
			} else {
				$(`#${wcfm_paypal_admin_l10n.payment_id_prefix}disbursement_delay_period`).closest('tr').hide();
			}
		},

		disbursementPeriodValidation: function() {
			let disbursementPeriod = $(this.disbursement_delay_period);
			if ( parseInt( disbursementPeriod.val() ) > 29 ) {
				disbursementPeriod.val( 29 );
			}
		},

		noticeIntervalToggle: function() {
			let noticeCheckbox = $(this.notice_checkbox);
			let noticeInterval = $(this.notice_interval);
			if ( noticeCheckbox.prop('checked') ) {
				noticeInterval.closest('tr').show();
			} else {
				noticeInterval.closest('tr').hide();
			}
		},
	};

	$(document).ready(function ($) {
		Wcfm_PayPal_MarketPlace_Settings.init();
	});
})(jQuery, document);
