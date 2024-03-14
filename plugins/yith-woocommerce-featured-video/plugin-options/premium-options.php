<?php
/**
 * Return the plugin options
 *
 * @package YITH WooCommerce Featured Audio Video Content\Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'premium' => array(
		'home' => array(
			'type'   => 'custom_tab',
			'action' => 'yith_wc_featured_audio_video_premium',
		),
	),
);
