<?php 
/* Validation filter */

add_filter( 'wpcf7_validate_selct_multiselct', 'wpcf7_selct_multiselct_validation_filter', 10, 2 );
add_filter( 'wpcf7_validate_selct_multiselct*', 'wpcf7_selct_multiselct_validation_filter', 10, 2 );

function wpcf7_selct_multiselct_validation_filter( $result, $tag ) {
	$tag = new WPCF7_FormTag( $tag );
	
	$name = $tag->name;
	
	$empty = ! isset( $_POST[$name] ) || empty( $_POST[$name] );
	if ( $tag->is_required() and $empty ) {
		$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
	}

	return $result;
}

?>