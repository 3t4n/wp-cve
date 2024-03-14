<?php
/**
 * Search fields option page
 *
 * @author  YITH
 * @package YITH/Search/Options
 * @version 2.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$times = array();
for ( $i = 0; $i < 24; $i ++ ) {
	$date        = new DateTime( 'today ' . ( $i < 10 ? '0' . $i : $i ) . ':00' );
	$times[ $i ] = $date->format( get_option( 'time_format' ) );
}


$search_fields_tab = array(
	'search-fields' => array(
		'section_search_settings'           => array(
			'name' => '',
			'type' => 'title',
			'id'   => 'ywcas_search_fields_settings',

		),
		'custom-search-fields-field'        => array(
			'name'             => '',
			'desc'             => '',
			'id'               => 'ywcas_search_fields_field',
			'type'             => 'yith-field',
			'yith-type'        => 'search-fields',
			'yith-display-row' => false,
		),
		'section_end_search_settings'       => array(
			'type' => 'sectionend',
			'id'   => 'ywcas_search_fields_settings_end',
		),
		'section_search_index_settings'     => array(
			'name'        => __( 'Search index', 'yith-woocommerce-ajax-search' ),
			'type'        => 'title',
			'desc'        => __( 'A search index helps your users quickly find information and products on your shop. It is designed to map search queries to documents or URLs that might appear in the results.', 'yith-woocommerce-ajax-search' ),
			'id'          => 'ywcas_search_index_settings',
		),
		'schedule-index'                    => array(
			'name'      => _x( 'Schedule indexing', 'Admin label option', 'yith-woocommerce-ajax-search' ),
			'desc'      => __( 'Enable a recurring indexing of fields.', 'yith-woocommerce-ajax-search' ),
			'id'        => 'yith_wcas_schedule_indexing',
			'type'      => 'yith-field',
			'yith-type' => 'onoff',
			'default'   => 'no',
		),
		'schedule-index-interval'           => array(
			'name'              => _x( 'Indexing interval', 'Admin label option', 'yith-woocommerce-ajax-search' ),
			'desc'              => '',
			'id'                => 'yith_wcas_schedule_indexing_interval',
			'type'              => 'yith-field',
			'yith-type'         => 'select',
			'class'             => 'wc-enhanced-select',
			'options'           => array(
				'daily'  => __( 'Daily', 'yith-woocommerce-ajax-search' ),
				'weekly' => __( 'Weekly', 'yith-woocommerce-ajax-search' ),
			),
			'custom_attributes' => array(
				'style' => 'width:200px',
			),
			'default'           => 'weekly',
			'deps'              => array(
				'id'    => 'yith_wcas_schedule_indexing',
				'value' => 'yes',
			),
		),
		'schedule-index-time'               => array(
			'name'              => _x( 'Schedule time', 'Admin label option', 'yith-woocommerce-ajax-search' ),
			'desc'              => '',
			'id'                => 'yith_wcas_schedule_indexing_time',
			'type'              => 'yith-field',
			'yith-type'         => 'select',
			'class'             => 'wc-enhanced-select',
			'custom_attributes' => array(
				'style' => 'width:200px',
			),
			'options'           => $times,
			'default'           => 1,
			'deps'              => array(
				'id'    => 'yith_wcas_schedule_indexing',
				'value' => 'yes',
			),
		),
		'index'                             => array(
			'name'      => _x( 'Index status', 'Admin label option', 'yith-woocommerce-ajax-search' ),
			'desc'      => '',
			'id'        => 'yith_wcas_index',
			'type'      => 'yith-field',
			'yith-type' => 'ywcas-index',
		),
		'section_end_search_index_settings' => array(
			'type' => 'sectionend',
			'id'   => 'ywcas_search_fields_settings_end',
		),
	),
);

/**
 * APPLY_FILTERS: ywcas_search_fields_options_tab
 *
 * This filter allow to manage the search fields tab
 *
 * @param   array  $search_fields_tab  List of options.
 *
 * @return array
 */
return apply_filters( 'ywcas_search_fields_options_tab', $search_fields_tab );
