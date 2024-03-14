/* global cb_change_mail_sender_admin_settings */

'use strict';

var CBChangeMailSender = window.CBChangeMailSender || {};
CBChangeMailSender.Admin = CBChangeMailSender.Admin || {};

CBChangeMailSender.Admin.Settings = CBChangeMailSender.Admin.Settings || ( function( document, window, $ ) {

	/**
	 * Public functions and properties.
	 *
	 * @since 1.3.0
	 *
	 * @type {object}
	 */
	var app = {

		/**
		 * Start the engine. DOM is not ready yet, use only to init something.
		 *
		 * @since 1.3.0
		 */
		init: function() {

			$( app.ready );
		},

		/**
		 * DOM is fully loaded.
		 *
		 * @since 1.3.0
		 */
		ready: function() {

			app.bindActions();
		},

		/**
		 * Bind all actions/events.
		 *
		 * @since 1.3.0
		 */
		bindActions: function() {

			$( document ).on( 'click', '.cb-change-mail-sender-product-education-dismiss', app.productEducationDismiss );
		},

		/**
		 * Event triggered when product education is dismissed.
		 *
		 * @since 1.3.0
		 *
		 * @param {Event} e Event object.
		 */
		productEducationDismiss: function( e ) {

			e.preventDefault();

			// Find the parent container.
			var $parent = $( this ).parents( '.cb-change-mail-sender-product-education' ).first();

			if ( $parent.length <= 0 ) {
				return;
			}

			var dataProductEducationId = $parent.data( 'productEducationId' );
			var dataNonce = $parent.data( 'nonce' );

			if ( ! dataProductEducationId || ! dataNonce ) {
				return;
			}

			$.post(
				cb_change_mail_sender_admin_settings.ajaxurl,
				{
					action: 'cb_change_mail_sender_product_education_dismiss',
					nonce: dataNonce,
					productEducationId: dataProductEducationId
				},
				function ( response ) {

					if ( ! response.success ) {
						alert( response.data );
						return;
					}

					$parent.fadeTo( 100, 0, function() {
						$parent.slideUp( 100, function() {
							$parent.remove();
						})
					} )
				}
			)
		}
	};

	// Expose to public.
	return app;
} ( document, window, jQuery ) );

CBChangeMailSender.Admin.Settings.init();
