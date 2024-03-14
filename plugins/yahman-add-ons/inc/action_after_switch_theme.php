<?php
defined( 'ABSPATH' ) || exit;

function yahman_addons_after_switch_theme(){

	
	require_once YAHMAN_ADDONS_DIR . 'inc/remove_cache.php';

	yahman_addons_remove_all_cache();

}
