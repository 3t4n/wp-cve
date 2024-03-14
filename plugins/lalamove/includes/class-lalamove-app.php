<?php
/**
 * Define the Lalamove APP constants
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Lalamove_App' ) ) :
	class Lalamove_App {
		public static $app_name                = 'Lalamove Plugin';
		public static $wc_auth_scope           = 'read_write';
		public static $wc_llm_rest_sql_keyword = 'Lalamove';
		public static $menu_slug               = 'Lalamove';
		public static $wc_llm_web_app_host     = 'https://web.lalamove.com';
		public static $sa_url                  = 'https://uba.huolalamove.net/sa?project=default';
		public static $llm_order_column_key    = 'Lalamove';
		public static $llm_order_column_title  = 'Lalamove';
		public static $wc_llm_api_host         = array(
			'sg' => 'https://sg-wc.lalamove.com',
			'br' => 'https://br-wc.lalamove.com',
		);
		public static $wc_llm_dc               = array(
			'sg' => array( 'sg', 30000, 'SIN' ),
			'my' => array( 'sg', 40000, 'SIN' ),
			'ph' => array( 'sg', 50000, 'SIN' ),
			'tw' => array( 'sg', 80000, 'SIN' ),
			'hk' => array( 'sg', 90000, 'SIN' ),
			'vn' => array( 'sg', 100000, 'SIN' ),
			'th' => array( 'sg', 110000, 'SIN' ),
			'id' => array( 'sg', 120000, 'SIN' ),
			'br' => array( 'br', 20000, 'SAO' ),
			'mx' => array( 'br', 60000, 'SAO' ),
		);

		public static $llm_order_status = array(
			'Unfulfilled'      => array( -1 ),
			'Assigning Driver' => array( 0, 6 ),
			'On Going'         => array( 1, 15 ),
			'Cancelled'        => array( 3, 9 ),
			'Picked Up'        => array( 7, 16 ),
			'Rejected'         => array( 4 ),
			'Completed'        => array( 2, 10, 11, 12, 13, 14 ),
			'Expired'          => array( 5, 8 ),
		);
	}
endif;
