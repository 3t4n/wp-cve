<?php
/*
* Plugin Name Native Emoji
* Version 3.0.1
* Author Daniel Brandenburg
*/

// Required files
$parse_uri = explode( 'wp-content', $_SERVER[ 'SCRIPT_FILENAME' ] );
require_once( $parse_uri[ 0 ] . 'wp-load.php' );

// Check if post content
if( $_POST ){
    
	// Post Vars
    $class = esc_sql( $_POST[ 'class' ] );
	$key   = esc_sql( $_POST[ 'key' ] );
    $code  = htmlspecialchars( '&#x' . str_replace( '-', '&#x', $key ) . ';' );
    
	// SQL vars
	global $wpbd;
	$table_name = $wpdb->prefix . 'nep_native_emoji';
	$uid        = get_current_user_id(); 
	$time       = current_time( 'mysql' );
    
    if( $uid == 0 )
        return;
    
	// Insert Data to table
	$existent_emoji = $wpdb->get_row( "SELECT * FROM $table_name WHERE btn_id = '$key' AND uid = '$uid'" );
    
	if ( $existent_emoji == null ) {
		$wpdb->insert( 
			$table_name, 
			array( 'time' => $time, 'btn_id' => $key, 'class' => $class, 'code' => $code, 'uid' => $uid ), 
			array( '%s','%s','%s','%s','%d' ) 
		);
	}
    
	else {
		$wpdb->update( 
			$table_name, 
			array( 'time' => $time ),
			array( 'btn_id' => $key ),
			array( '%s' ),
			array( '%s' )
		);	
	}
}
?>