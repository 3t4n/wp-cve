<?php
// IMPORTANT: This plugin is dynamically updated - MODIFICATIONS WILL BE OVERWRITTEN

/**************************************************
 * Name: Gravity Forms
 * Description: Automatically track submissions from Gravity Forms
 *************************************************/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<!-- Nutshell Integration: Gravity Forms -->
<script type="text/javascript" data-registered="nutshell-plugin" >
	if (
		/* global mcfx */
		'undefined' !== typeof mcfx
	) {
		document.addEventListener( 'submit.gravityforms', function( e ) {
			if ( 'function' === typeof mcfx ) {
				mcfx( 'capture', e.target );
			}
		} );
	}
</script>

<?php // IMPORTANT: This plugin is dynamically updated - MODIFICATIONS WILL BE OVERWRITTEN ?>
