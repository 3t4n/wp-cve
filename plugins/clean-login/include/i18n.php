<?php

function clean_login_load_textdomain(){
	load_plugin_textdomain( 'clean-login', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}
add_action( 'plugins_loaded', 'clean_login_load_textdomain' );
