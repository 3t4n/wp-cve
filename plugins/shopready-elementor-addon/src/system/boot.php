<?php

/*
 **
 ****** In step one we created a constant called APPLICATION_PATH.
 ******** Now a smarty security check is to make sure that constant is set.
 ********** This will stope hackers from directly accessing the file from the browser.
 ******** So we just make sure that the APPLICATION_PATH is in the scope
 ******
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/*
 **
 *** Now at this point we can start including our files, and creating the objects etc.
 *** The first object were going to be including is the regsitry object.
 **
 */

/*
 * Session start
 */

if (!session_id()) {
	session_start(['read_and_close' => true]);
}

// if( class_exists('woocommerce') ){

// 	if( isset( WC()->session ) ){
// 		WC()->session->init();
// 	}
// }


/*
 **
 *** Loaded all plugin helper functions
 *** 
 **
 */
foreach (array('generals', 'elementor') as $file) {
	require SHOP_READY_DIR_PATH . '/src/helpers/' . $file . '.php';
}

/*
 ** docs - https://github.com/mattstauffer/Torch/blob/master/components/container/index.php
 ** laravel container package
 */

require SHOP_READY_DIR_PATH . '/src/system/config/config_function.php';

\Shop_Ready\system\App::register_services();







