(function($){

	XoloWebsitesInstallTheme = {

		/**
		 * Init
		 */
		init: function() {
			this._bind();
		},

		/**
		 * Binds events for the Xolo Websites.
		 *
		 * @since 1.3.2
		 * 
		 * @access private
		 * @method _bind
		 */
		_bind: function()
		{
			$( document ).on( 'click', '.xolo-websites-theme-not-installed', XoloWebsitesInstallTheme._install_and_activate );
			$( document ).on( 'click', '.xolo-websites-theme-installed-but-inactive', XoloWebsitesInstallTheme._activateTheme );
			$( document ).on('wp-theme-install-success' , XoloWebsitesInstallTheme._activateTheme);
		},

		/**
		 * Activate Theme
		 *
		 * @since 1.3.2
		 */
		_activateTheme: function( event, response ) {
			event.preventDefault();

			$('#xolo-theme-activation-xl a').addClass('processing');

			if( response ) {
				$('#xolo-theme-activation-xl a').text( XoloWebsitesInstallThemeVars.installed );
			} else {
				$('#xolo-theme-activation-xl a').text( XoloWebsitesInstallThemeVars.activating );
			}

			// WordPress adds "Activate" button after waiting for 1000ms. So we will run our activation after that.
			setTimeout( function() {

				$.ajax({
					url: XoloWebsitesInstallThemeVars.ajaxurl,
					type: 'POST',
					data: {
						'action' : 'xolo-websites-activate-theme'
					},
				})
				.done(function (result) {
					if( result.success ) {
						$('#xolo-theme-activation-xl a').text( XoloWebsitesInstallThemeVars.activated );
						$('#xolo-theme-activation-xl a').removeClass( 'shake' );

						setTimeout(function() {
							location.reload();
						}, 1000);
					}

				});

			}, 3000 );

		},

		/**
		 * Install and activate
		 *
		 * @since 1.3.2
		 * 
		 * @param  {object} event Current event.
		 * @return void
		 */
		_install_and_activate: function(event ) {
			event.preventDefault();
			var theme_slug = $(this).data('theme-slug') || '';
			console.log( theme_slug );
			console.log( 'yes' );

			var btn = $( event.target );

			if ( btn.hasClass( 'processing' ) ) {
				return;
			}

			btn.text( XoloWebsitesInstallThemeVars.installing ).addClass('processing');

			if ( wp.updates.shouldRequestFilesystemCredentials && ! wp.updates.ajaxLocked ) {
				wp.updates.requestFilesystemCredentials( event );
			}
			
			wp.updates.installTheme( {
				slug: theme_slug
			});
		}

	};

	/**
	 * Initialize
	 */
	$(function(){
		XoloWebsitesInstallTheme.init();
	});

})(jQuery);