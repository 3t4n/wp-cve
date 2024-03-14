<?php

Class log {


	/*
	 User Errors...
	*/
	public function user( $msg, $username ) {
		$date = date( 'd.m.Y h:i:s' );
		$log  = $msg . "   |  Date:  " . $date . "  |  User:  " . $username . "\n";
		error_log( $log, 3, plugin_dir_path( __FILE__ ) . 'user_errors.log' );
	}

	/*
   General Errors...
  */
	public function general( $msg ) {
		$date = date( 'd.m.Y h:i:s' );
		$log  = $msg . "   |  Date:  " . $date . "\n";
		error_log( $msg . "   |  Tarih:  " . $date, 3, plugin_dir_path( __FILE__ ) . 'errors.log' );
	}

}

?>