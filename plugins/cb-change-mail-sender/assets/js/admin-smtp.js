'use strict';

var CBChangeMailSender = window.CBChangeMailSender || {};
CBChangeMailSender.Admin = CBChangeMailSender.Admin || {};

CBChangeMailSender.Admin.SMTP = CBChangeMailSender.Admin.SMTP || ( function( document, window, $) {

	/**
	 * Run-time elements container.
	 *
	 * @since 1.3.0
	 *
	 * @type {object}
	 */
	var el = {};

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

			app.initVars();
			app.bindActions();
		},

		/**
		 * Init variables.
		 *
		 * @since 1.3.0
		 */
		initVars: function() {

			el = {
				$stepInstall:    $( 'section.step-install' ),
				$stepInstallNum: $( 'section.step-install .num img' ),
				$stepSetup:      $( 'section.step-setup' ),
				$stepSetupNum:   $( 'section.step-setup .num img' ),
			};
		},

		/**
		 * Bind all actions/events.
		 *
		 * @since 1.3.0
		 */
		bindActions: function() {

			// Step 'Install' button click.
			el.$stepInstall.on( 'click', 'button', app.stepInstallClick );

			// Step 'Setup' button click.
			el.$stepSetup.on( 'click', 'button', app.gotoURL );
		},

		/**
		 * Step 'Install' button click.
		 *
		 * @since 1.3.0
		 */
		stepInstallClick: function() {

			var $btn = $( this ),
				action = $btn.attr( 'data-action' ),
				plugin = $btn.attr( 'data-plugin' ),
				ajaxAction = '';

			if ( $btn.hasClass( 'disabled' ) ) {
				return;
			}

			switch ( action ) {
				case 'activate':
					ajaxAction = 'cb_change_mail_sender_activate_smtp';
					$btn.text( cb_change_mail_sender_admin_smtp.activating );
					break;

				case 'install':
					ajaxAction = 'cb_change_mail_sender_install_smtp';
					$btn.text( cb_change_mail_sender_admin_smtp.installing );
					break;

				case 'goto-url':
					window.location.href = $btn.attr( 'data-url' );
					return;

				default:
					return;
			}

			$btn.addClass( 'disabled' );
			app.showSpinner( el.$stepInstallNum );

			var data = {
				action: ajaxAction,
				nonce : cb_change_mail_sender_admin_smtp.nonce,
				plugin: plugin
			};

			$.post( cb_change_mail_sender_admin_smtp.ajaxurl, data )
				.done( function( res ) {
					app.stepInstallDone( res, $btn, action );
				} )
				.always( function() {
					app.hideSpinner( el.$stepInstallNum );
				}
			);
		},

		/**
		 * Display spinner.
		 *
		 * @since 1.3.0
		 *
		 * @param {jQuery} $el Section number image jQuery object.
		 */
		showSpinner: function( $el ) {

			$el.siblings( '.loader' ).removeClass( 'hidden' );
		},

		/**
		 * Done part of the 'Install' step.
		 *
		 * @since 1.3.0
		 *
		 * @param {object} res    Result of $.post() query.
		 * @param {jQuery} $btn   Button.
		 * @param {string} action Action (for more info look at the app.stepInstallClick() function).
		 */
		stepInstallDone: function( res, $btn, action ) {

			var success = 'install' === action ? res.success && res.data.is_activated : res.success;

			if ( success ) {
				el.$stepInstallNum.attr( 'src', el.$stepInstallNum.attr( 'src' ).replace( 'step-1.', 'step-complete.' ) );
				$btn.addClass( 'grey' ).removeClass( 'button-primary' ).text( cb_change_mail_sender_admin_smtp.activated );
				app.stepInstallPluginStatus();

				return;
			}

			var activationFail = ( 'install' === action && res.success && ! res.data.is_activated ) || 'activate' === action,
				url            = ! activationFail ? cb_change_mail_sender_admin_smtp.manual_install_url : cb_change_mail_sender_admin_smtp.manual_activate_url,
				msg            = ! activationFail ? cb_change_mail_sender_admin_smtp.error_could_not_install : cb_change_mail_sender_admin_smtp.error_could_not_activate,
				btn            = ! activationFail ? cb_change_mail_sender_admin_smtp.download_now : cb_change_mail_sender_admin_smtp.plugins_page;

			$btn.removeClass( 'grey disabled' ).text( btn ).attr( 'data-action', 'goto-url' ).attr( 'data-url', url );
			$btn.after( '<p class="error">' + msg + '</p>' );
		},

		/**
		 * Callback for step 'Install' completion.
		 *
		 * @since 1.3.0
		 */
		stepInstallPluginStatus: function() {

			$.post(
				cb_change_mail_sender_admin_smtp.ajaxurl,
				{
					action: 'cb_change_mail_sender_smtp_page_check_plugin_status',
					nonce : cb_change_mail_sender_admin_smtp.nonce
				}
			).done( app.stepInstallPluginStatusDone );
		},

		/**
		 * Done part of the callback for step 'Install' completion.
		 *
		 * @since 1.3.0
		 *
		 * @param {object} res Result of $.post() query.
		 */
		stepInstallPluginStatusDone: function( res ) {

			if ( ! res.success ) {
				return;
			}

			el.$stepSetup.removeClass( 'grey' );
			el.$stepSetupBtn = el.$stepSetup.find( 'button' );
			el.$stepSetupBtn.removeClass( 'grey disabled' ).addClass( 'button-primary' );

			if ( res.data.setup_status > 0 ) {
				el.$stepSetupNum.attr( 'src', el.$stepSetupNum.attr( 'src' ).replace( 'step-2.svg', 'step-complete.svg' ) );
				el.$stepSetupBtn.attr( 'data-url', cb_change_mail_sender_admin_smtp.smtp_settings_url ).text( cb_change_mail_sender_admin_smtp.smtp_settings );

				return;
			}

			el.$stepSetupBtn.attr( 'data-url', cb_change_mail_sender_admin_smtp.smtp_wizard_url ).text( cb_change_mail_sender_admin_smtp.smtp_wizard );
		},

		/**
		 * Hide spinner.
		 *
		 * @since 1.3.0
		 *
		 * @param {jQuery} $el Section number image jQuery object.
		 */
		hideSpinner: function( $el ) {

			$el.siblings( '.loader' ).addClass( 'hidden' );
		},

		/**
		 * Go to URL by click on the button.
		 *
		 * @since 1.3.0
		 */
		gotoURL: function() {

			var $btn = $( this );

			if ( $btn.hasClass( 'disabled' ) ) {
				return;
			}

			window.location.href = $btn.attr( 'data-url' );
		}
	};

	// Expose to public.
	return app;
} ( document, window, jQuery ) )

CBChangeMailSender.Admin.SMTP.init();
