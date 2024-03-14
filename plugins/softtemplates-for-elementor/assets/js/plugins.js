(function( $, pluginsData ) {

	'use strict';

	var SofttemplatePluginsPage = {

		init: function() {

			var self = this;

			$( document )
				.on( 'click.SofttemplatePluginsPage', 'a[data-action="install"]', self.installPlugin )
				.on( 'click.SofttemplatePluginsPage', 'a[data-action="activate"]', self.activatePlugin )
				.on( 'click.SofttemplatePluginsPage', 'a[data-action="update"]', self.updatePlugin );

		},

		showError: function( $button, message ) {
			$button.closest( '.softtemplate-plugin' ).find( '.softtemplate-plugin__errors' ).html( message );
		},

		installPlugin: function( event ) {

			event.preventDefault();

			var $this  = $( this ),
				plugin = $this.data( 'plugin' );

			$this.html( pluginsData.installing );

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					action:  'softtemplate_core_install_plugin',
					plugin: plugin
				}
			}).done( function( response ) {

				if ( true === response.success ) {

					$this.html( pluginsData.activate );
					$this.data( 'activate' );
					$this.attr( 'data-action', 'activate' );

					$this.closest( '.softtemplate-plugin' ).find( '.user-version b' ).html( response.data.version );

				} else {
					SofttemplatePluginsPage.showError( $this, response.data.errorMessage );
					$this.html( pluginsData.failed );
				}

			});

		},

		activatePlugin: function( event ) {

			event.preventDefault();

			var $this  = $( this ),
				plugin = $this.data( 'plugin' );

			$this.html( pluginsData.activating );

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					action:  'softtemplate_core_activate_plugin',
					plugin: plugin
				}
			}).done( function( response ) {

				if ( true === response.success ) {
					$this.replaceWith( pluginsData.activated );
				} else {
					SofttemplatePluginsPage.showError( $this, response.data.errorMessage );
					$this.html( pluginsData.failed );
				}

			});

		},

		updatePlugin: function( event ) {

			event.preventDefault();

			var $this  = $( this ),
				plugin = $this.data( 'plugin' );

			$this.html( pluginsData.updating );

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					action:  'softtemplate_core_update_plugin',
					plugin: plugin
				}
			}).done( function( response ) {

				if ( true === response.success ) {
					$this.closest( '.softtemplate-plugin' ).find( '.user-version b' ).html( response.data.newVersion );
					$this.replaceWith( pluginsData.updated );
				} else {
					SofttemplatePluginsPage.showError( $this, response.data.errorMessage );
					$this.html( pluginsData.failed );
				}

			});

		}

	};

	SofttemplatePluginsPage.init();

})( jQuery, window.SofttemplatePluginsData );