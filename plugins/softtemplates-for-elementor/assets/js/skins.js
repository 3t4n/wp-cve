(function( $, skinsData ) {

	'use strict';

	var SofttemplateSkinsPage = {

		init: function() {

			var self = this;

			$( document )
				.on( 'click.SofttemplateSkinsPage', 'a[data-action="install-wizard"]', self.installWizard )
				.on( 'click.SofttemplateSkinsPage', 'a[data-action="activate-wizard"]', self.activateWizard );

		},

		showError: function( $button, message ) {
			$button.next( '.softtemplate-install-wizard__msg' ).html( message );
		},

		installWizard: function( event ) {

			event.preventDefault();

			var $this  = $( this );

			$this.html( skinsData.installing );

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					action:  'softtemplate_core_install_plugins_wizard',
				}
			}).done( function( response ) {

				if ( true === response.success ) {

					$this.html( skinsData.activate );
					$this.data( 'action', 'activate-wizard' );
					$this.attr( 'data-action', 'activate-wizard' );

				} else {
					SofttemplateSkinsPage.showError( $this, response.data.errorMessage );
					$this.html( skinsData.failed );
				}

			});

		},

		activateWizard: function( event ) {

			event.preventDefault();

			var $this  = $( this );

			$this.html( skinsData.activating );

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					action:  'softtemplate_core_activate_plugins_wizard'
				}
			}).done( function( response ) {

				if ( true === response.success ) {
					$this.replaceWith( response.data.pageContent );
					window.location.reload();
				} else {
					SofttemplateSkinsPage.showError( $this, response.data.errorMessage );
					$this.html( skinsData.failed );
				}

			});

		},

	};

	SofttemplateSkinsPage.init();

})( jQuery, window.SofttemplateSkinsData );
