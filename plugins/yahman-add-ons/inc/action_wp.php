<?php
defined( 'ABSPATH' ) || exit;


add_action( 'wp', 'yahman_addons_setup_AMP' );
function yahman_addons_setup_AMP() {

	
	require_once YAHMAN_ADDONS_DIR . 'inc/enqueue.php';

}
