// Either create a new empty object, or work with the existing one.
window.UM_CTB = window.UM_CTB || {};

(function( window, document, $, app, undefined ) {
	'use strict';

	// Cache specific objects from the DOM so we don't look them up repeatedly.
	app.cache = function() {
		app.$ = {};
		app.$.select = $( document.getElementById( '_um_ctb_content_type' ) );
		app.$.field = $( document.getElementById( 'jpry_extra_field' ) );
		app.$.field_container = app.$.field.closest( '.cmb-row');
	};

	app.init = function() {
		// Store/cache our selectors
		app.cache();
		
		// Show the custom container when the selection is 'show-field'
		app.$.select.on( 'change', function( event ) {

			var selected_id = jQuery( '.cmb2-id--um-ctb-type-' + $(this).val() );
			jQuery('.um-ctb--hide').hide();
			selected_id.show();
		} ).trigger( 'change' );
	};

	$( document ).ready( app.init );
})( window, document, jQuery, UM_CTB );