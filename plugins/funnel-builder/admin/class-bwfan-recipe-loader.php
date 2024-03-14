<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

#[AllowDynamicProperties]

class WFFN_Recipe_Loader {
	private static $instance = null;
	public static $web_url = '';

	/**
	 * Constructor
	 */
	public function __construct() {
		self::$web_url = 'https://app.getautonami.com/recipes';
	}

	/**
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return WFFN_Recipe_Loader|null
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get recipes listing array
	 *
	 * @param bool $load_new
	 *
	 * @return array
	 */
	public static function get_recipes_array( $load_new = false ) {
		$recipes_data['data']        = self::get_recipe_data( $load_new );
		$recipes_data['total_count'] = ! empty( $recipes_data['data'] ) ? count( $recipes_data['data'] ) : 0;
		$recipes_data['filters']     = self::get_recipes_filter();

		return $recipes_data;
	}

	/**
	 * Get the recipes listing data from the database or server
	 *
	 * @param bool $load_new
	 *
	 * @return array|mixed
	 */
	public static function get_recipe_data( $load_new ) {
		if ( ! $load_new && get_option( 'bwfan_get_recipes' ) ) {
			$result = get_option( 'bwfan_get_recipes' );

			if ( ! empty( $result ) ) {
				return json_decode( $result, true );
			}
		}

		$request = wp_remote_get( self::$web_url );
		if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
			return [];
		}
		$result = wp_remote_retrieve_body( $request );
		update_option( 'bwfan_get_recipes', $result, false );

		return json_decode( $result, true );
	}

	/**
	 * Get recipes group or sub group filters
	 *
	 * @return array
	 */
	public static function get_recipes_filter() {
		$data = [
			'groups'    => [
				'All',
				'WooCommerce',
				'WordPress',
				'FunnelKit',
				'Learndash',
				'WP Forms',
				'AffiliateWP'
			],
			'subGroups' => [
				'WooCommerce' => [
					'Cart',
					'Customer',
					'Orders',
					'Subscription',
					'Reviews',
				],
				'WordPress'   => [
					'User'
				],
				'FunnelKit'  => [
					'Optin Form'
				],
			]
		];

		return apply_filters( 'bwfan_recipe_group_filters', $data );
	}
}

if ( class_exists( 'WFFN_Core' ) ) {
	WFFN_Core::register( 'bwfan_recipe', 'WFFN_Recipe_Loader' );
}