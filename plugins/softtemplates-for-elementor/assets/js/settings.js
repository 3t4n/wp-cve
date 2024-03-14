(function( $, settingsData ) {

	'use strict';

	var SofttemplateSettingsPage = {

		init: function() {

			var self = this;

			$( document )
				.on( 'click.SofttemplateSettingsPage', '#softtemplate_activate_license', self.activateLicese )
				.on( 'click.SofttemplateSettingsPage', '#softtemplate_deactivate_license', self.deactivateLicese );

		},

		activateLicese: function() {

			var $licenseInput = $( '#softtemplate_core_license' ),
				licesne       = $licenseInput.val();

			if ( ! licesne ) {
				$licenseInput.addClass( 'softtemplate-error' );
				$( '.softtemplate-core-license__errors' ).html( settingsData.messages.empty );
			} else {
				window.location = settingsData.activateUrl.replace( '%license_key%', licesne );
			}

		},

		deactivateLicese: function() {
			window.location = settingsData.deactivateUrl;
		}

	};

	SofttemplateSettingsPage.init();

})( jQuery, window.SofttemplateSettingsData );