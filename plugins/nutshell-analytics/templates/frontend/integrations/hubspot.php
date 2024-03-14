<?php
// IMPORTANT: This plugin is dynamically updated - MODIFICATIONS WILL BE OVERWRITTEN

/**************************************************
 * Name: HubSpot
 * Description: Automatically track submissions from HubSpot forms
 *************************************************/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<!-- Nutshell Integration: HubSpot -->
<script type="text/javascript" data-registered="nutshell-plugin" >
	if (
		/* global mcfx */
		'undefined' !== typeof mcfx
		&& 'undefined' !== typeof window.mcfxCaptureCustomFormData
	) {
		// Reference https://legacydocs.hubspot.com/global-form-events
		window.addEventListener( 'message', function( event ) {
			if (
				event.data.type === 'hsFormCallback' &&
				event.data.eventName === 'onFormSubmit'
			) {
				window.mcfxCaptureCustomFormData(event.data.data, 'hsForm_' + event.data.id );
			}
		} );
	}
</script>

<?php // IMPORTANT: This plugin is dynamically updated - MODIFICATIONS WILL BE OVERWRITTEN ?>
