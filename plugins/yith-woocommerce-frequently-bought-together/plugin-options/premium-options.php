<?php // phpcs:ignore WordPress.NamingConventions
/**
 * Premium options
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH\FrequentlyBoughtTogether
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WFBT' ) ) {
	exit;
} // Exit if accessed directly.

$options = array(
	'premium' => array(
		'landing' => array(
			'type'   => 'custom_tab',
			'action' => 'yith_wfbt_premium',
		),
	),
);

return $options;
