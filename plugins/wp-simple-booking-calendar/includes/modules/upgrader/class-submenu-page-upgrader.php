<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


Class WPSBC_Submenu_Page_Upgrader extends WPSBC_Submenu_Page {

	/**
	 * Callback for the HTML output for the Calendar page
	 *
	 */
	public function output() {

		include 'views/view-upgrader.php';

	}

}