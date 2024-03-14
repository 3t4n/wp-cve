<?php
/**
 * Carriers tab
 *
 * @package YITH\OrderTracking\PluginOptions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


return array(
	'carriers' => array(
		'section_carriers'     => array(
			'name' => __( 'Carriers', 'yith-woocommerce-order-tracking' ),
			'type' => 'title',
		),
		'home'                 => array(
			'id'   => 'ywot_carriers',
			'type' => 'carriers_list',
		),
		'section_carriers_end' => array(
			'type' => 'sectionend',
		),
	),
);
