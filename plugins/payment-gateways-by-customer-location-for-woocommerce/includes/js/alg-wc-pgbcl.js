/**
 * alg-wc-pgbcl.js
 *
 * @version 1.1.0
 * @since   1.1.0
 *
 * @author  Algoritmika Ltd.
 */

jQuery( function( $ ) {
	"use strict";
	alg_wc_pgbcl.forEach( function( element ) {
		$( 'body' ).on( 'propertychange change click keyup input paste', 'input[name="' + element + '"]', function() {
			$( 'body' ).trigger( 'update_checkout' );
		} );
	} );
} );
