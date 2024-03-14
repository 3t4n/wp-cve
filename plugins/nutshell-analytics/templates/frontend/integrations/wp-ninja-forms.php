<?php
// IMPORTANT: This plugin is dynamically updated - MODIFICATIONS WILL BE OVERWRITTEN

/**************************************************
 * Name: Ninja Forms
 * Description: Automatically track submissions from Ninja Forms.
 *************************************************/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<!-- Nutshell Integration: Ninja Forms -->
<script type="text/javascript" data-registered="nutshell-plugin" >
	/* global Backbone, Marionette, mcfx */
	document.addEventListener( 'DOMContentLoaded', function() {
		marionette_loaded().then( function() {
			const mcfxSubmitController = Marionette.Object.extend( {
				initialize: function () {
					this.listenTo(
						Backbone.Radio.channel( 'forms' ),
						'before:submit',
						this.actionSubmit
					);
				},
				actionSubmit: function ( response ) {
					const form = document.querySelector(
						'#nf-form-' + response.id + '-cont form'
					);
					if ( form ) {
						form.id = 'nf-form-'+response.id; // Give it a nice ID for easier reference and exclusion
						mcfx( 'capture', form );
					}
				},
			} );
			new mcfxSubmitController();
		} );
	} );

	async function marionette_loaded() {
		while( typeof Marionette == 'undefined' ) {
			await new Promise(function(resolve) {
				setTimeout(resolve, 1000);
			});
		};
	}
</script>

<?php // IMPORTANT: This plugin is dynamically updated - MODIFICATIONS WILL BE OVERWRITTEN ?>
