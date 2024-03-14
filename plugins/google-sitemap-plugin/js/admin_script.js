( function( $ ) {
	"use strict";
	$( document ).ready( function() {
		$( '#gglstmp_split_sitemap' ).on ( 'change', function(){
			if ( $( this ).is( ':checked' ) ) {
				$( '.gglstmp_split_sitemap_items' ).removeClass( 'hidden' );
			} else {
				$( '.gglstmp_split_sitemap_items' ).addClass( 'hidden' );
			}
		});
	});
} )( jQuery );