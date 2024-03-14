/* globals wp_mail_smtp, ajaxurl */
'use strict';

var MBMailSMTP = window.MBMailSMTP || {};
MBMailSMTP.Admin = MBMailSMTP.Admin || {};

/**
 * WP Mail SMTP Admin area module.
 *
 * @since 1.6.0
 */
MBMailSMTP.Admin.Settings = MBMailSMTP.Admin.Settings || ( function( document, window, $ ) {
	alert('test');

	/**
	 * Public functions and properties.
	 *
	 * @since 1.6.0
	 *
	 * @type {object}
	 */
	var app = {

		/**
		 * State attribute showing if one of the plugin settings
		 * changed and was not yet saved.
		 *
		 * @since 1.9.0
		 *
		 * @type {boolean}
		 */
		pluginSettingsChanged: false,

		/**
		 * Start the engine. DOM is not ready yet, use only to init something.
		 *
		 * @since 1.6.0
		 */
		init: function() {

			// Do that when DOM is ready.
			$( document ).ready( app.ready );
		},

		/**
		 * DOM is fully loaded.
		 *
		 * @since 1.6.0
		 */
		ready: function() {

			app.pageHolder = $( '.baby-mail-smtp-tab-settings' );

			// If there are screen options we have to move them.
			$( '#screen-meta-links, #screen-meta' ).prependTo( '#baby-mail-smtp-header-temp' ).show();

			app.bindActions();
		},

		/**
		 * Process all generic actions/events, mostly custom that were fired by our API.
		 *
		 * @since 1.6.0
		 */
		bindActions: function() {

			// Mailer selection.
			$( '.baby-mail-smtp-mailer-image', app.pageHolder ).click( function() {
				$( this ).parents( '.baby-mail-smtp-mailer' ).find( 'input' ).trigger( 'click' );
			} );

			$( '.baby-mail-smtp-mailer input', app.pageHolder ).click( function() {
				var $input = $( this );

				if ( $input.prop( 'disabled' ) ) {

					// Educational Popup.
					if ( $input.hasClass( 'educate' ) ) {
						app.education.upgradeMailer( $input );
					}

					return false;
				}

				// Deselect the current mailer.
				$( '.baby-mail-smtp-mailer', app.pageHolder ).removeClass( 'active' );

				// Select the correct one.
				$( this ).parents( '.baby-mail-smtp-mailer' ).addClass( 'active' );

				// Hide all mailers options and display for a currently clicked one.
				$( '.baby-mail-smtp-mailer-option', app.pageHolder ).addClass( 'hidden' ).removeClass( 'active' );
				$( '.baby-mail-smtp-mailer-option-' + $( this ).val(), app.pageHolder ).addClass( 'active' ).removeClass( 'hidden' );
			} );

			app.mailers.smtp.bindActions();

			// Dismiss Pro banner at the bottom of the page.
			$( '#baby-mail-smtp-pro-banner-dismiss', app.pageHolder ).on( 'click', function() {
				$.ajax( {
					url: ajaxurl,
					dataType: 'json',
					type: 'POST',
					data: {
						action: 'wp_mail_smtp_ajax',
						task: 'pro_banner_dismiss'
					}
				} )
					.always( function() {
						$( '#baby-mail-smtp-pro-banner', app.pageHolder ).fadeOut( 'fast' );
					} );
			} );

			// Dissmis educational notices for certain mailers.
			$( '.js-baby-mail-smtp-mailer-notice-dismiss', app.pageHolder ).on( 'click', function( e ) {
				e.preventDefault();

				var $btn = $( this ),
					$notice = $btn.parents( '.inline-notice' );

				if ( $btn.hasClass( 'disabled' ) ) {
					return false;
				}

				$.ajax( {
					url: ajaxurl,
					dataType: 'json',
					type: 'POST',
					data: {
						action: 'wp_mail_smtp_ajax',
						task: 'notice_dismiss',
						notice: $notice.data( 'notice' ),
						mailer: $notice.data( 'mailer' )
					},
					beforeSend: function() {
						$btn.addClass( 'disabled' );
					}
				} )
					.always( function() {
						$notice.fadeOut( 'fast', function() {
							$btn.removeClass( 'disabled' );
						} );
					} );
			} );

			// Show/hide debug output.
			$( '#baby-mail-smtp-debug .error-log-toggle' ).on( 'click', function( e ) {
				e.preventDefault();

				$( '#baby-mail-smtp-debug .error-log-toggle' ).find( '.dashicons' ).toggleClass( 'dashicons-arrow-right-alt2 dashicons-arrow-down-alt2' );
				$( '#baby-mail-smtp-debug .error-log' ).slideToggle();
				$( '#baby-mail-smtp-debug .error-log-note' ).toggle();
			} );

			// Remove mailer connection.
			$( '.js-baby-mail-smtp-provider-remove', app.pageHolder ).on( 'click', function() {
				return confirm( wp_mail_smtp.text_provider_remove );
			} );

			// Copy input text to clipboard.
			$( '.baby-mail-smtp-setting-copy', app.pageHolder ).click( function( e ) {
				e.preventDefault();

				var target = $( '#' + $( this ).data( 'source_id' ) ).get( 0 );

				target.select();

				document.execCommand( 'Copy' );

				var $buttonIcon = $( this ).find( '.dashicons' );

				$buttonIcon
					.removeClass( 'dashicons-admin-page' )
					.addClass( 'dashicons-yes-alt baby-mail-smtp-success baby-mail-smtp-animate' );

				setTimeout(
					function() {
						$buttonIcon
							.removeClass( 'dashicons-yes-alt baby-mail-smtp-success baby-mail-smtp-animate' )
							.addClass( 'dashicons-admin-page' );
					},
					1000
				);
			} );

			// Notice bar: click on the dissmiss button.
			$( '#baby-mail-smtp-notice-bar' ).on( 'click', '.dismiss', function() {
				var $notice = $( this ).closest( '#baby-mail-smtp-notice-bar' );

				$notice.addClass( 'out' );
				setTimeout(
					function() {
						$notice.remove();
					},
					300
				);

				$.post(
					ajaxurl,
					{
						action: 'wp_mail_smtp_notice_bar_dismiss',
						nonce: wp_mail_smtp.nonce,
					}
				);
			} );

			app.triggerExitNotice();
			app.beforeSaveChecks();

			// Register change event to show/hide plugin supported settings for currently selected mailer.
			$( '.js-baby-mail-smtp-setting-mailer-radio-input', app.pageHolder ).on( 'change', this.processMailerSettingsOnChange );

			// Disable multiple click on the Email Test tab submit button and display a loader icon.
			$( '.baby-mail-smtp-tab-test #email-test-form' ).on( 'submit', function() {
				var $button = $( '.baby-mail-smtp-tab-test #email-test-form .baby-mail-smtp-btn' );

				$button.attr( 'disabled', true );
				$button.find( 'span' ).hide();
				$button.find( '.baby-mail-smtp-loading' ).show();
			} );
		},

		education: {
			upgradeMailer: function( $input ) {

				$.alert( {
					backgroundDismiss: true,
					escapeKey: true,
					animationBounce: 1,
					theme: 'modern',
					type: 'blue',
					animateFromElement: false,
					draggable: false,
					closeIcon: true,
					useBootstrap: false,
					title: wp_mail_smtp.education.upgrade_title.replace( /%name%/g, $input.siblings( 'label' ).text().trim() ),
					icon: '"></i>' + wp_mail_smtp.education.upgrade_icon_lock + '<i class="',
					content: $( '.baby-mail-smtp-mailer-options .baby-mail-smtp-mailer-option-' + $input.val() + ' .baby-mail-smtp-setting-field' ).html(),
					boxWidth: '550px',
					onOpenBefore: function() {
						this.$btnc.after( '<div class="discount-note">' + wp_mail_smtp.education.upgrade_bonus + wp_mail_smtp.education.upgrade_doc + '</div>' );
					},
					buttons: {
						confirm: {
							text: wp_mail_smtp.education.upgrade_button,
							btnClass: 'btn-confirm',
							keys: [ 'enter' ],
							action: function() {
								window.open( wp_mail_smtp.education.upgrade_url + '&utm_content=' + encodeURI( $input.val() ), '_blank' );
							}
						}
					}
				} );
			}
		},

		/**
		 * Individual mailers specific js code.
		 *
		 * @since 1.6.0
		 */
		mailers: {
			smtp: {
				bindActions: function() {

					// Hide SMTP-specific user/pass when Auth disabled.
					$( '#baby-mail-smtp-setting-smtp-auth' ).change( function() {
						$( '#baby-mail-smtp-setting-row-smtp-user, #baby-mail-smtp-setting-row-smtp-pass' ).toggleClass( 'inactive' );
					} );

					// Port default values based on encryption type.
					$( '#baby-mail-smtp-setting-row-smtp-encryption input' ).change( function() {

						var $input = $( this ),
							$smtpPort = $( '#baby-mail-smtp-setting-smtp-port', app.pageHolder );

						if ( 'tls' === $input.val() ) {
							$smtpPort.val( '587' );
							$( '#baby-mail-smtp-setting-row-smtp-autotls' ).addClass( 'inactive' );
						} else if ( 'ssl' === $input.val() ) {
							$smtpPort.val( '465' );
							$( '#baby-mail-smtp-setting-row-smtp-autotls' ).removeClass( 'inactive' );
						} else {
							$smtpPort.val( '25' );
							$( '#baby-mail-smtp-setting-row-smtp-autotls' ).removeClass( 'inactive' );
						}
					} );
				}
			}
		},

		/**
		 * Exit notice JS code when plugin settings are not saved.
		 *
		 * @since 1.9.0
		 */
		triggerExitNotice: function() {

			var $settingPages = $( '.baby-mail-smtp-page-general:not( .baby-mail-smtp-tab-test )' );

			// Display an exit notice, if settings are not saved.
			$( window ).on( 'beforeunload', function() {
				if ( app.pluginSettingsChanged ) {
					return wp_mail_smtp.text_settings_not_saved;
				}
			} );

			// Set settings changed attribute, if any input was changed.
			$( ':input:not( #baby-mail-smtp-setting-license-key, .baby-mail-smtp-not-form-input )', $settingPages ).on( 'change', function() {
				app.pluginSettingsChanged = true;
			} );

			// Clear the settings changed attribute, if the settings are about to be saved.
			$( 'form', $settingPages ).on( 'submit', function() {
				app.pluginSettingsChanged = false;
			} );
		},

		/**
		 * Perform any checks before the settings are saved.
		 *
		 * Checks:
		 * - warn users if they try to save the settings with the default (PHP) mailer selected.
		 *
		 * @since 2.1.0
		 */
		beforeSaveChecks: function() {

			$( 'form', app.pageHolder ).on( 'submit', function() {
				if ( $( '.baby-mail-smtp-mailer input:checked', app.pageHolder ).val() === 'mail' ) {
					var $thisForm = $( this );

					$.alert( {
						backgroundDismiss: false,
						escapeKey: false,
						animationBounce: 1,
						theme: 'modern',
						type: 'orange',
						animateFromElement: false,
						draggable: false,
						closeIcon: false,
						useBootstrap: false,
						icon: '"></i><img src="' + wp_mail_smtp.plugin_url + '/assets/images/font-awesome/exclamation-circle-solid-orange.svg" style="width: 40px; height: 40px;" alt="' + wp_mail_smtp.default_mailer_notice.icon_alt + '"><i class="',
						title: wp_mail_smtp.default_mailer_notice.title,
						content: wp_mail_smtp.default_mailer_notice.content,
						boxWidth: '550px',
						buttons: {
							confirm: {
								text: wp_mail_smtp.default_mailer_notice.save_button,
								btnClass: 'btn-confirm',
								keys: [ 'enter' ],
								action: function() {
									$thisForm.off( 'submit' ).submit();
								}
							},
							cancel: {
								text: wp_mail_smtp.default_mailer_notice.cancel_button,
							},
						}
					} );

					return false;
				}
			} );
		},

		/**
		 * On change callback for showing/hiding plugin supported settings for currently selected mailer.
		 *
		 * @since 2.3.0
		 */
		processMailerSettingsOnChange: function() {

			var mailerSupportedSettings = wp_mail_smtp.all_mailers_supports[ $( this ).val() ];

			for ( var setting in mailerSupportedSettings ) {
				// eslint-disable-next-line no-prototype-builtins
				if ( mailerSupportedSettings.hasOwnProperty( setting ) ) {
					$( '.js-baby-mail-smtp-setting-' + setting, app.pageHolder ).toggle( mailerSupportedSettings[ setting ] );
				}
			}

			// Special case: "from email" (group settings).
			var $mainSettingInGroup = $( '.js-baby-mail-smtp-setting-from_email' );

			$mainSettingInGroup.closest( '.baby-mail-smtp-setting-row' ).toggle(
				mailerSupportedSettings['from_email'] || mailerSupportedSettings['from_email_force']
			);
			$mainSettingInGroup.siblings( '.baby-mail-smtp-setting-mid-row-sep' ).toggle(
				mailerSupportedSettings['from_email'] && mailerSupportedSettings['from_email_force']
			);

			// Special case: "from name" (group settings).
			$mainSettingInGroup = $( '.js-baby-mail-smtp-setting-from_name' );

			$mainSettingInGroup.closest( '.baby-mail-smtp-setting-row' ).toggle(
				mailerSupportedSettings['from_name'] || mailerSupportedSettings['from_name_force']
			);
			$mainSettingInGroup.siblings( '.baby-mail-smtp-setting-mid-row-sep' ).toggle(
				mailerSupportedSettings['from_name'] && mailerSupportedSettings['from_name_force']
			);
		}
	};

	// Provide access to public functions/properties.
	return app;
}( document, window, jQuery ) );

// Initialize.
MBMailSMTP.Admin.Settings.init();
