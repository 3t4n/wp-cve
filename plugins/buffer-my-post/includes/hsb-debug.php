<?php

function HSB_DEBUG( $str ) {
	global $hsb_debug;
	$hsb_enable_log = get_option( 'hsb_enable_log' );
	if ( $hsb_enable_log ) {
		$hsb_debug->enable( true );
	}

	$hsb_debug->add_to_log( $str );
}

function hsb_is_debug_enabled() {
	global $hsb_debug;

	return $hsb_debug->is_enabled();
}

class HsbDebug {
	var $debug_file;
	var $log_messages;

	public function __construct() {
		$this->debug_file = false;
	}

	public function is_enabled() {
		return ( $this->debug_file );
	}

	public function enable( $enable_or_disable ) {
		if ( $enable_or_disable ) {
			$this->debug_file   = fopen( WP_CONTENT_DIR . '/plugins/buffer-my-post/log.txt', 'a+t' );
			$this->log_messages = 0;
		} else if ( $this->debug_file ) {
			fclose( $this->debug_file );
			$this->debug_file = false;
		}
	}

	public function add_to_log( $str ) {
		if ( $this->debug_file ) {

			$log_string = $str;

			// Write the data to the log file
			fwrite( $this->debug_file, sprintf( "%12s %s\n", time(), $log_string ) );
			fflush( $this->debug_file );

			$this->log_messages ++;
		}
	}
}

global $hsb_debug;
$hsb_debug = new HsbDebug();


?>
