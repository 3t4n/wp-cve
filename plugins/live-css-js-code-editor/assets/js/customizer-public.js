(function( $ ) {

	'use strict';

	var api = wp.customize;

	$(function(){

		/* Customizer live CSS changes */
		api('live_code_css_field',function( value ) {
		    value.bind(function( e ) {
		        $( '#live-code-editor-css' ).html( e );
		    });
		});

	});

})( jQuery );