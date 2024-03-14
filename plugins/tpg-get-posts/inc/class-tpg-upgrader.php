<?php
/*
 *  extended class to avoid conflict with wp upgrade
*/
if (!class_exists ('WP_Upgrader')) {
	require_once(ABSPATH ."/wp-admin/includes/class-wp-upgrader.php");
}

class tpg_upgrader extends WP_Upgrader {

    public function __construct() {
        parent::__construct();
	}
}

?>