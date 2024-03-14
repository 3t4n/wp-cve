<?php

add_action( 'wp_head', function() {
	?><script>
		jQuery( document ).ready( function( $ ){
			$p = $( '#url' ).parent();
			$( '#url, label[for=url]' ).remove();
			$p
				.filter( function() { return $( this ).text().trim().length; } )
				.remove()
			;
		});
	</script><?php
} );
