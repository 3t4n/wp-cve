<?php
/*
	Counter-Hits.
*/

	// if uninstall.php is not called by WordPress, die
	if (!defined('WP_UNINSTALL_PLUGIN')) {
		die;
	}
	
	// Удаляем настройки Плагина
	delete_option('wpgear_counter_hits');
