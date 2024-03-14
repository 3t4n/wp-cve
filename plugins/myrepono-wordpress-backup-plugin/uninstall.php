<?php
/**
 * @package myRepono
 * @version 2.0.12
 */


if (!defined( 'WP_UNINSTALL_PLUGIN')) {

	exit;

}

if (get_option('myrepono-plugin')) {

	delete_option('myrepono-plugin');

}

if (get_option('myrepono-plugin-config')) {

	delete_option('myrepono-plugin-config');

}


?>