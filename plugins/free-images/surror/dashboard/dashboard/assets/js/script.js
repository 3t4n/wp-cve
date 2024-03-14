( function( $ ) {
	'use strict';

	const SurrorPage = {
		/**
		 * Init
		 */
		init() {
			this._bind();
		},

		/**
		 * Binds events
		 */
		_bind() {
			$( document ).on( 'click', '.s-menu-admin a', this.switchTab );
			$( document ).on( 'click', '.s-btn-install', this.installPlugin );
			$( document ).on( 'click', '.s-btn-activate', this.activatePlugin );
		},

		installPlugin: function( event ) {
			event.preventDefault()

			const btn = $( this )
			const slug = btn.attr( 'data-slug' ) || ''
			const init = btn.attr( 'data-init' ) || ''

			if ( ! slug ) {
				return
			}

			if ( btn.hasClass( 's-installing' ) ) {
				return
			}

			// Installing.
			btn.addClass( 's-installing' ).text( 'Installing..' )

			wp.updates.ajax( 'install-plugin', {
				slug: slug,
				success: function () {
					btn.removeClass( 's-installing' ).addClass( 's-activating' ).text( 'Activating..' )

					// Activate plugin.
					wp.ajax.post( 'surror_activate_plugin', {
						init: init,
						security: surrorVars.security,
						success: function () {
							btn.removeClass( 's-activating s-btn s-btn-install' ).addClass( 's-active' ).text( 'Active' )
						},
					} )
				},
				error: function () {
					btn.removeClass( 's-installing' ).addClass( 's-install-error' ).text( 'Error!' )
				},
			} )
		},

		activatePlugin: function( event ) {
			event.preventDefault()

			const btn = $( this )
			const slug = btn.attr( 'data-slug' ) || ''
			const init = btn.attr( 'data-init' ) || ''

			if ( ! slug ) {
				return
			}

			if ( btn.hasClass( 's-activating' ) ) {
				return
			}

			// Activating.
			btn.addClass( 's-activating' ).text( 'Activating..' )

			// Activate plugin.
			wp.ajax.post( 'surror_activate_plugin', {
				init: init,
				security: surrorVars.security,
				success: function () {
					btn.removeClass( 's-activating s-btn s-btn-activate' ).addClass( 's-active' ).text( 'Active' )

					location.reload()
				},
			} )
		},

		switchTab( event ) {
			event.preventDefault();
			console.log(event);

			const btn = $(this);
			const li = btn.parents('li');
			const ul = btn.parents('ul');
			const slug = btn.attr('data-slug') || '';

			ul.find('li').removeClass('active');
			li.addClass('active');

			$('.s-tab-content').hide();
			$( '.s-tab-content[data-slug="' + slug + '"]' ).show();
		},
	};

	/**
	 * Initialization
	 */
	$( function() {
		SurrorPage.init();
	});
} )(jQuery);
