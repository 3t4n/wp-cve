<?php
/**
* Read first data row from piecfw_file and check if it's encoded in specified
* encoding.
*/
if(!function_exists( 'piecfw_is_first_row_encoded_in')){
	function piecfw_is_first_row_encoded_in( $piecfw_file, $encoding ) {
		$handle = fopen( $piecfw_file, 'r' );
		// Keep reading from the stream until it reaches the end of the line.
		$line = fgets( $handle );
		if ( false === $line ) {
			return false;
		}
		// Second line is the first row.
		$line = fgets( $handle );
		if ( false === $line ) {
			return false;
		}
		fclose( $handle );
		return mb_detect_encoding( $line, $encoding, true );
	}
}
/**
* Escape a string to be used in a CSV context
*/
if(!function_exists( 'piecfw_esc_csv')){
	function piecfw_esc_csv( $field ) {
		$active_content_triggers = array( '=', '+', '-', '@' );
		if ( in_array( mb_substr( $field, 0, 1 ), $active_content_triggers, true ) ) {
			$field = "'" . $field;
		}
		return $field;
	}
}