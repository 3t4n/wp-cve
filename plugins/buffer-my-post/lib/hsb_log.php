<?php

Class log {

	public function error( $msg ) {
		$date = date( 'd.m.Y h:i:s' );
		$log  = $msg . "   |  Date:  " . $date . "\n";
		$hsb_enable_log = get_option( 'hsb_enable_log' );
		if ( $hsb_enable_log ) {
			error_log( $log, 3, 'error.log' );
		}
	}

	public function general( $msg ) {
		$date = date( 'd.m.Y h:i:s' );
		$log  = $msg . "   |  Date:  " . $date . "\n";
		$hsb_enable_log = get_option( 'hsb_enable_log' );
		if ( $hsb_enable_log ) {
			error_log( $log, 3, 'general.log' );
		}
	}

}

?>