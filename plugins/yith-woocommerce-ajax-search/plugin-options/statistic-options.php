<?php
/**
 * Statistic search page
 *
 * @author  YITH
 * @package YITH/Search/Options
 * @version 2.1
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$statistic_tab = array(
	'statistic' => array(
		'statistic-tab' => array(
			'type'   => 'custom_tab',
			'action' => 'ywcas_show_statistic_tab',
		),
	),
);

return $statistic_tab;