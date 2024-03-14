<?php
/**
 * Show the custom tab to manage shortcodes
 *
 * @author  YITH
 * @package YITH/Search/Options
 * @version 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shortcodes = array(
	'shortcodes' => array(
		'shortcodes-tab' => array(
			'type'   => 'custom_tab',
			'action' => 'ywcas_show_shortcode_tab',
		),
	),
);

return $shortcodes;
