/*
 * ======================================================================
 * Slider Pro Add-ons
 * ======================================================================
 */
(function( $ ) {

	var AddOn = {

		/**
		 * Verify if a license key is valid for a certain add-on.
		 * 
		 * @since 4.8.0
		 */
		verifyLicenseKey: function( licenseKey, addOnSlug, nonce, args = null ) {
			$.ajax({
				url: sp_add_ons_js_vars.ajaxurl,
				type: 'post',
				data: {
					action: 'sliderpro_verify_add_on_license_key',
					add_on_slug: addOnSlug,
					license_key: licenseKey,
					nonce: nonce
				},
				error: function( error ) {
					var response = { 'status': 'not_valid', 'message': error.statusText };
					args.fail( response );
				},
				success: function( data ) {
					var response = JSON.parse( data );

					if ( response['status'] === 'valid' && args.success !== undefined ) {
						args.success( response );
					} else if ( response['status'] !== 'valid' && args.fail !== undefined ) {
						args.fail( response );
					}
				},
				complete: function() {
					if ( args.always !== undefined ) {
						args.always();
					}
				}
			});
		},

		/**
		 * Install add-on
		 * 
		 * @since 4.8.0
		 */
		install: function( licenseKey, addOnSlug, nonce, args ) {
			$.ajax({
				url: sp_add_ons_js_vars.ajaxurl,
				type: 'post',
				data: {
					action: 'sliderpro_install_add_on',
					add_on_slug: addOnSlug,
					license_key: licenseKey,
					nonce: nonce
				},
				error: function( error ) {
					var response = { 'status': 'not_installed', 'message': error.statusText };
					
					if ( args.fail !== undefined ) {
						args.fail( response );
					}
				},
				success: function( data ) {
					var response = JSON.parse( data );

					if ( response['status'] === 'installed' && args.success !== undefined ) {
						args.success( response );
					} else if ( response['status'] !== 'installed' && args.fail !== undefined ) {
						args.fail( response );
					}
				},
				complete: function() {
					if ( args.always !== undefined ) {
						args.always();
					}
				}
			});
		},

		/**
		 * Activate add-on
		 * 
		 * @since 4.8.0
		 */
		activate: function( addOnSlug, nonce, args ) {
			$.ajax({
				url: sp_add_ons_js_vars.ajaxurl,
				type: 'post',
				data: {
					action: 'sliderpro_activate_add_on',
					add_on_slug: addOnSlug,
					nonce: nonce
				},
				error: function( error ) {
					var response = { 'status': 'not_activated', 'message': error.statusText };

					if ( args.fail !== undefined ) {
						args.fail( response );
					}
				},
				success: function( data ) {
					var response = JSON.parse( data );

					if ( response['status'] === 'activated' && args.success !== undefined ) {
						args.success( response );
					} else if ( response['status'] !== 'activated' && args.fail !== undefined ) {
						args.fail( response );
					}
				},
				complete: function() {
					if ( args.always !== undefined ) {
						args.always();
					}
				}
			});
		},

		/**
		 * Deactivate add-on
		 * 
		 * @since 4.8.0
		 */
		deactivate: function( addOnSlug, nonce, args ) {
			$.ajax({
				url: sp_add_ons_js_vars.ajaxurl,
				type: 'post',
				data: {
					action: 'sliderpro_deactivate_add_on',
					add_on_slug: addOnSlug,
					nonce: nonce
				},
				error: function( error ) {
					var response = { 'status': 'not_deactivated', 'message': error.statusText };
					
					if ( args.fail !== undefined ) {
						args.fail( response );
					}
				},
				success: function( data ) {
					var response = JSON.parse( data );

					if ( response['status'] === 'deactivated' && args.success !== undefined ) {
						args.success( response );
					} else if ( response['status'] !== 'deactivated' && args.fail !== undefined ) {
						args.fail( response );
					}
				},
				complete: function() {
					if ( args.always !== undefined ) {
						args.always();
					}
				}
			});
		}
	};

	var InstallAddOn = {

		/**
		 * Reference to the modal window.
		 *
		 * @since 4.8.0
		 * 
		 * @type {jQuery Object}
		 */
		$installAddOnWindow: null,

		/**
		 * Indicates the slug of the add-on.
		 *
		 * @since 4.8.0
		 * 
		 * @type {string}
		 */
		addOnSlug: '',

		/**
		 * Indicates the specified license key.
		 *
		 * @since 4.8.0
		 * 
		 * @type {string}
		 */
		licenseKey: '',

		/**
		 * Stores the nonce(s) for the action
		 *
		 * @since 4.8.0
		 * 
		 * @type {JSON object}
		 */
		nonce: {},

		/**
		 * Reference to the install button.
		 *
		 * @since 4.8.0
		 * 
		 * @type {jQuery Object}
		 */
		$installButton: null,

		/**
		 * Reference to the submit button.
		 *
		 * @since 4.8.0
		 * 
		 * @type {jQuery Object}
		 */
		$submitButton: null,
		
		/**
		 * Reference to the install log.
		 *
		 * @since 4.8.0
		 * 
		 * @type {jQuery Object}
		 */
		$installLog: null,
		
		/**
		 * Reference to a row from the install log.
		 *
		 * @since 4.8.0
		 * 
		 * @type {jQuery Object}
		 */
		$installLogRow: null,

		/**
		 * Indicates whether the page will be reloaded when the modal window is closed.
		 *
		 * @since 4.8.0
		 * 
		 * @type {boolean}
		 */
		reloadPageOnClose: false,		

		/**
		 * Open the modal window.
		 *
		 * @since 4.8.0
		 */
		open: function( target ) {
			var that = this,
				url = $.lightURLParse( target.attr( 'href' ) );
			
			this.$installButton = target.attr( 'disabled', true );
			this.addOnSlug = url.add_on;
			this.nonce = JSON.parse( target.attr( 'data-nonce' ) );
			this.reloadPageOnClose = false;

			$( '<div class="modal-overlay"><span class="modal-overlay-spinner"></span></div>' ).appendTo( 'body' );

			$.ajax({
				url: sp_add_ons_js_vars.ajaxurl,
				type: 'post',
				data: {
					action: 'sliderpro_load_install_add_on',
					add_on_slug: this.addOnSlug
				},
				complete: function( data ) {
					$( '.modal-overlay-spinner' ).remove();
					that.$installAddOnWindow = $( data.responseText ).appendTo( 'body' );
					that.init();
				}
			});
		},

		/**
		 * Initialize the window controls.
		 * 
		 * @since 4.8.0
		 */
		init: function() {
			var that = this;

			this.$submitButton = this.$installAddOnWindow.find( '.license-key-submit-button' );
			this.$installLog = $( '.install-log' );
			this.$installLogRow = $( '<div class="install-log-row"><span class="install-log-icon dashicons"></span><p class="install-log-message"></p></div>' );

			this.$installAddOnWindow.find( '.close-x' ).on( 'click', function( event ) {
				event.preventDefault();
				that.close();
			});

			this.$submitButton.on( 'click', function( event ) {
				event.preventDefault();
				that.$installLog.empty().show();
				that._submitInstallAddOnHandler();
			});
		},

		/**
		 * Start the add-on installation process with verifying the license key.
		 * 
		 * @since 4.8.0
		 */
		_submitInstallAddOnHandler: function() {
			var that = this,
				nonce = this.nonce['verify'],
				$verifyLicenseKeyLog = this.$installLogRow.clone().appendTo( this.$installLog ),
				$verifyLicenseKeyLogIcon = $verifyLicenseKeyLog.find( '.install-log-icon' ).addClass( 'dashicons-update processing-icon' ),
				$verifyLicenseKeyLogMessage = $verifyLicenseKeyLog.find( '.install-log-message' ).text( sp_add_ons_js_vars.check_license_key );

			this.licenseKey = this.$installAddOnWindow.find( '.license-key-input' ).val();

			this.$submitButton.attr( 'disabled', true );
			
			AddOn.verifyLicenseKey( this.licenseKey, this.addOnSlug, nonce, {
				success: function( response ) {
					$verifyLicenseKeyLogIcon.addClass( 'dashicons-yes success-icon' );
					$verifyLicenseKeyLogMessage.text( response['message'] );
					
					// Go to the installation process.
					that._verifyLicenseKeySuccessHandler();
				},
				fail: function( response ) {
					if ( response['status'] === 'used' ) {
						$verifyLicenseKeyLogIcon.addClass( 'dashicons-warning warning-icon' );

						// Even if the license key is already in use, allow the installation.
						that._verifyLicenseKeySuccessHandler();
					} else {
						that.$submitButton.removeAttr( 'disabled' );
						$verifyLicenseKeyLogIcon.addClass( 'dashicons-no-alt fail-icon' );
					}

					$verifyLicenseKeyLogMessage.text( response['message'] );
				},
				always: function() {
					$verifyLicenseKeyLogIcon.removeClass( 'dashicons-update processing-icon' );
				}
			});
		},

		/**
		 * If the license key is valid, start the add-on installation process.
		 * 
		 * @since 4.8.0
		 */
		_verifyLicenseKeySuccessHandler: function() {
			var that = this,
				nonce = this.nonce['install'],
				$installAddOnLog = this.$installLogRow.clone().appendTo( this.$installLog ),
				$installAddOnLogIcon = $installAddOnLog.find( '.install-log-icon' ).addClass( 'dashicons-update processing-icon' ),
				$installAddOnLogMessage = $installAddOnLog.find( '.install-log-message' ).text( sp_add_ons_js_vars.install_add_on );

			AddOn.install( this.licenseKey, this.addOnSlug, nonce, {
				success: function( response ) {
					$installAddOnLogIcon.addClass( 'dashicons-yes success-icon' );
					$installAddOnLogMessage.text( response['message'] );

					// The page can reload even if the next steps fail.
					that.reloadPageOnClose = true;

					// Go to the activation process.
					that._installAddOnSuccessHandler();
				},
				fail: function( response ) {
					that.$submitButton.removeAttr( 'disabled' );
					$installAddOnLogIcon.addClass( 'dashicons-no-alt fail-icon' );
					$installAddOnLogMessage.text( response['message'] );
				},
				always: function() {
					$installAddOnLogIcon.removeClass( 'dashicons-update processing-icon' );
				}
			});
		},

		/**
		 * If the add-on was installed successfully, try to activate the add-on.
		 * 
		 * @since 4.8.0
		 */
		_installAddOnSuccessHandler: function() {
			var that = this,
				nonce = this.nonce['activate'],
				$activateAddOnLog = this.$installLogRow.clone().appendTo( this.$installLog ),
				$activateAddOnLogIcon = $activateAddOnLog.find( '.install-log-icon' ).addClass( 'dashicons-update processing-icon' ),
				$activateAddOnLogMessage = $activateAddOnLog.find( '.install-log-message' ).text( sp_add_ons_js_vars.activate_add_on );

			AddOn.activate( this.addOnSlug, nonce, {
				success: function( response ) {
					$activateAddOnLogIcon.addClass( 'dashicons-yes success-icon' );
					$activateAddOnLogMessage.text( response['message'] );

					// If the activation was successfull, reload the page.
					window.location = sp_add_ons_js_vars.admin + '?page=sliderpro-add-ons';
				},
				fail: function( response ) {
					$activateAddOnLogIcon.addClass( 'dashicons-no-alt fail-icon' );
					$activateAddOnLogMessage.text( response['message'] );
				},
				always: function() {
					$activateAddOnLogIcon.removeClass( 'dashicons-update processing-icon' );
				}
			});
		},

		/**
		 * Handle window closing.
		 * 
		 * @since 4.8.0
		 */
		close: function() {
			this.$installAddOnWindow.find( '.close-x' ).off( 'click' );
			this.$submitButton.off( 'click' );
			this.$installAddOnWindow.remove();
			$( '.modal-overlay' ).remove();
			this.$installButton.removeAttr( 'disabled' );

			if ( this.reloadPageOnClose === true ) {
				window.location = sp_add_ons_js_vars.admin + '?page=sliderpro-add-ons';
			}
		}
	};

	var EditAddOnLicenseKey = {

		/**
		 * Reference to the modal window.
		 *
		 * @since 4.8.0
		 * 
		 * @type {jQuery Object}
		 */
		$editLicenseKeyWindow: null,

		/**
		 * Indicates the slug of the add-on.
		 *
		 * @since 4.8.0
		 * 
		 * @type {string}
		 */
		addOnSlug: '',

		/**
		 * Reference to the update button.
		 *
		 * @since 4.8.0
		 * 
		 * @type {jQuery Object}
		 */
		$updateButton: null,

		/**
		 * Open the modal window.
		 *
		 * @since 4.8.0
		 */
		open: function( target ) {
			var that = this,
				url = $.lightURLParse( target.attr( 'href' ) ),
				nonce = target.attr( 'data-nonce' );
			
			this.addOnSlug = url.add_on;

			$( '<div class="modal-overlay"><span class="modal-overlay-spinner"></span></div>' ).appendTo( 'body' );

			$.ajax({
				url: sp_add_ons_js_vars.ajaxurl,
				type: 'post',
				data: {
					action: 'sliderpro_load_edit_add_on_license_key',
					add_on_slug: this.addOnSlug,
					nonce: nonce
				},
				complete: function( data ) {
					$( '.modal-overlay-spinner' ).remove();
					that.$editLicenseKeyWindow = $( data.responseText ).appendTo( 'body' );
					that.init();
				}
			});
		},

		/**
		 * Initialize the window controls.
		 * 
		 * @since 4.8.0
		 */
		init: function() {
			var that = this;

			this.$updateButton = this.$editLicenseKeyWindow.find( '.license-key-update-button' );
			
			this.$updateButton.on( 'click', function( event ) {
				event.preventDefault();
				that._updateLicenseKeyHandler();
			});

			this.$editLicenseKeyWindow.find( '.close-x' ).on( 'click', function( event ) {
				event.preventDefault();
				that.close();
			});
		},

		_updateLicenseKeyHandler: function() {
			var that = this,
				nonce = this.$updateButton.attr( 'data-nonce' ),
				licenseKey = this.$editLicenseKeyWindow.find( '.license-key-input' ).val(),
				$licenseKeyInfo = this.$editLicenseKeyWindow.find( '.license-key-info' ),
				$verifyLog = this.$editLicenseKeyWindow.find( '.verify-log' );

			this.$updateButton.attr( 'disabled', true );
			$verifyLog.show();
			$verifyLog.find( '.verify-log-icon' ).addClass( 'dashicons-update processing-icon' );
			$verifyLog.find( '.verify-log-message' ).text( sp_add_ons_js_vars.check_license_key );
			$licenseKeyInfo.hide();

			if ( $licenseKeyInfo.hasClass( 'license-key-not-valid' ) ) {
				$licenseKeyInfo.removeClass( 'license-key-not-valid' );
			}

			if ( $licenseKeyInfo.hasClass( 'license-key-valid' ) ) {
				$licenseKeyInfo.removeClass( 'license-key-valid' );
			}

			AddOn.verifyLicenseKey( licenseKey, this.addOnSlug, nonce, {
				success: function( response ) {
					if ( response.info !== undefined ) {
						$licenseKeyInfo.html( response.info ).addClass( 'license-key-valid' );
					} else if ( response.message !== undefined ) {
						$licenseKeyInfo.html( response.message ).addClass( 'license-key-valid' );
					}
				},
				fail: function( response ) {
					if ( response.info !== undefined ) {
						$licenseKeyInfo.html( response.info ).addClass( 'license-key-not-valid' );
					} else if ( response.message !== undefined ) {
						$licenseKeyInfo.html( response.message ).addClass( 'license-key-not-valid' );
					}
				},
				always: function( response ) {
					that.$updateButton.removeAttr( 'disabled' );
					$verifyLog.find( '.verify-log-icon' ).removeClass( 'dashicons-update processing-icon' );
					$verifyLog.find( '.verify-log-message' ).empty();
					$verifyLog.hide();
					$licenseKeyInfo.show();
				}
			});
		},

		/**
		 * Handle window closing.
		 * 
		 * @since 4.8.0
		 */
		close: function() {
			this.$editLicenseKeyWindow.find( '.close-x' ).off( 'click' );
			this.$updateButton.off( 'click' );
			this.$editLicenseKeyWindow.remove();
			$( '.modal-overlay' ).remove();
		}
	};

	var MoreDetails = {

		/**
		 * Reference to the modal window.
		 *
		 * @since 4.8.0
		 * 
		 * @type {jQuery Object}
		 */
		$moreDetailsWindow: null,

		/**
		 * Indicates the slug of the add-on.
		 *
		 * @since 4.8.0
		 * 
		 * @type {string}
		 */
		addOnSlug: '',

		/**
		 * Open the modal window.
		 *
		 * @since 4.8.0
		 */
		open: function( target ) {
			var that = this,
				url = $.lightURLParse( target.attr( 'href' ) ),
				nonce = target.attr( 'data-nonce' );
			
			this.addOnSlug = url.add_on;

			$( '<div class="modal-overlay"><span class="modal-overlay-spinner"></span></div>' ).appendTo( 'body' );

			$.ajax({
				url: sp_add_ons_js_vars.ajaxurl,
				type: 'post',
				data: {
					action: 'sliderpro_load_add_on_more_details',
					add_on_slug: this.addOnSlug,
					nonce: nonce
				},
				complete: function( data ) {
					$( '.modal-overlay-spinner' ).remove();
					that.$moreDetailsWindow = $( data.responseText ).appendTo( 'body' );
					that.init();
				}
			});
		},

		/**
		 * Initialize the window controls.
		 * 
		 * @since 4.8.0
		 */
		init: function() {
			var that = this,
				$modalWindow = this.$moreDetailsWindow.find( '.modal-window' );

			this.$moreDetailsWindow.find( '.close-x' ).on( 'click', function( event ) {
				event.preventDefault();
				that.close();
			});

			this.$moreDetailsWindow.scrollTop(0)
			this.$moreDetailsWindow.find( '.more-details-screenshots a' ).attr( 'target', '_blank' );

			$( window ).on( 'resize.addOnMoreDetails', function() {
                if ( $modalWindow.outerHeight() >= $( window ).height() - 70 ) {
                    that.$moreDetailsWindow.addClass( 'modal-window-top' );
                } else {
                    that.$moreDetailsWindow.removeClass( 'modal-window-top' );
                }
            });

            $( window ).trigger( 'resize' );
		},

		/**
		 * Handle window closing.
		 * 
		 * @since 4.8.0
		 */
		close: function() {
			this.$moreDetailsWindow.find( '.close-x' ).off( 'click' );
			$( window ).off( 'resize.addOnMoreDetails' );
			this.$moreDetailsWindow.remove();
			$( '.modal-overlay' ).remove();
		}
	};

	$( document ).ready(function() {
		$( '.more-details' ).on( 'click', function( event ) {
			event.preventDefault();
			MoreDetails.open( $( this ) )
		});

		$( '.edit-license-key' ).on( 'click', function( event ) {
			event.preventDefault();
			EditAddOnLicenseKey.open( $( this ) );
		});

		$( '.install-add-on' ).on( 'click', function( event ) {
			event.preventDefault();
			InstallAddOn.open( $( this ) );
		});

		$( '.activate-add-on' ).on( 'click', function( event ) {
			var $button = $( this ),
				$buttonText = $button.find( 'p' ),
				$buttonIcon = $button.find( 'span' ),
				addOnSlug = $button.attr( 'data-slug' ),
				nonce = JSON.parse( $button.attr( 'data-nonce' ) )['activate'];

			event.preventDefault();

			$button.addClass( 'disabled' );
			$buttonText.text( sp_add_ons_js_vars.activating );
			$buttonIcon.attr( 'class', 'dashicons dashicons-update processing-icon' );
			
			AddOn.activate( addOnSlug, nonce, {
				success: function() {
					$buttonText.text( sp_add_ons_js_vars.activated );
					$buttonIcon.attr( 'class', 'dashicons dashicons-yes success-icon' );
					
					window.location = sp_add_ons_js_vars.admin + '?page=sliderpro-add-ons';
				},
				fail: function() {
					$button.removeClass( 'disabled' );

					$buttonText.text( sp_add_ons_js_vars.try_again );
					$buttonIcon.attr( 'class', 'dashicons dashicons-no-alt fail-icon' );
				},
				always: function() {

				}
			});
		});

		$( '.deactivate-add-on' ).on( 'click', function( event ) {
			var $button = $( this ),
				$buttonText = $button.find( 'p' ),
				$buttonIcon = $button.find( 'span' ),
				addOnSlug = $button.attr( 'data-slug' ),
				nonce = JSON.parse( $button.attr( 'data-nonce' ) )['deactivate'];
			
			event.preventDefault();

			$button.addClass( 'disabled' );
			$buttonText.text( sp_add_ons_js_vars.deactivating );
			$buttonIcon.attr( 'class', 'dashicons dashicons-update processing-icon' );
			
			AddOn.deactivate( addOnSlug, nonce, {
				success: function() {
					$buttonText.text( sp_add_ons_js_vars.deactivated );
					$buttonIcon.attr( 'class', 'dashicons dashicons-yes success-icon' );
					
					window.location = sp_add_ons_js_vars.admin + '?page=sliderpro-add-ons';
				},
				fail: function() {
					$button.removeClass( 'disabled' );

					$buttonText.text( sp_add_ons_js_vars.try_again );
					$buttonIcon.attr( 'class', 'dashicons dashicons-no-alt fail-icon' );
				},
				always: function() {
					
				}
			});
		});
	});

})( jQuery );