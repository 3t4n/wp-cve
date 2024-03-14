<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * WCSTM Lite Core Plugin Class
 */
class WCSTM_Lite {

	/**
	 * Plugin settings
	 */
	public static $settings = false;

	/**
	 * List of plugin filters
	 */
	private $additional_filters;

	/**
	 * Hook into the appropriate actions when the class is constructed
	 */
	public function __construct() {

		require_once WCSTM_LITE_PLUGIN_DIR . 'includes/class-wcstm-lite-search-filters.php';

		self::$settings = array(
			'wordpress'   => get_option( 'wcstm_lite_settings_wordpress' ),
			'woocommerce' => get_option( 'wcstm_lite_settings_woocommerce' )
		);

		if ( is_admin() ) {

			require_once WCSTM_LITE_PLUGIN_DIR . 'admin/class-wcstm-lite-admin.php';
			new WCSTM_Lite_Admin();

		}

		add_action( 'template_redirect', array( $this, 'wcstm_lite_search_terms' ) );

		$this->additional_filters = WCSTM_Search_Lite_Filters::get_instance();
		$this->additional_filters->set_options( self::$settings );

		//wordpress filters
		add_filter( 'posts_where', array( $this->additional_filters, 'search_in_excerpt' ), 10, 2 );
		add_filter( 'posts_where', array( $this->additional_filters, 'search_in_post_comments' ), 10, 2 );
		add_filter( 'posts_where', array( $this->additional_filters, 'search_in_post_category' ), 10, 2 );
		add_filter( 'posts_where', array( $this->additional_filters, 'search_in_post_tags' ), 10, 2 );

		//woocommerce filters
		if ( self::is_woocommerce() ) {
			add_filter( 'posts_where', array( $this->additional_filters, 'search_in_short_desc' ), 10, 2 );
			add_filter( 'posts_where', array( $this->additional_filters, 'search_in_product_sku' ), 10, 2 );
			add_filter( 'posts_where', array( $this->additional_filters, 'search_in_product_comments' ), 10, 2 );
			add_filter( 'posts_where', array( $this->additional_filters, 'search_in_product_category' ), 10, 2 );
			add_filter( 'posts_where', array( $this->additional_filters, 'search_in_product_tags' ), 10, 2 );
		}

	}

	public function wcstm_lite_search_terms() {

		if ( is_search() && get_search_query() ) {

			global $wp_query;

			$count_result = $wp_query->found_posts;

			$item         = array(
				'term'    => get_search_query(),
				'date'    => current_time( 'mysql' ),
				'results' => $count_result,
			);

			if ( $count_result == 0 ) {

				$get_items = get_option( 'wcstm_lite_terms_unsuccessful' );

				if ( count( $get_items ) >= 5 ) {
					unset( $get_items[ count( $get_items ) - 1 ] );
				}

				if ( !empty( $get_items ) ) {
					array_unshift( $get_items, $item );
					update_option( 'wcstm_lite_terms_unsuccessful', $get_items );
				} else {
					$get_items = array( $item );
					update_option( 'wcstm_lite_terms_unsuccessful', $get_items );
				}

			} else {

				$get_items = get_option( 'wcstm_lite_terms_recent' );

				if ( count( $get_items ) >= 5 ) {
					unset( $get_items[ count( $get_items ) - 1 ] );
				}

				if ( !empty( $get_items ) ) {
					array_unshift( $get_items, $item );
					update_option( 'wcstm_lite_terms_recent', $get_items );
				} else {
					$get_items = array( $item );
					update_option( 'wcstm_lite_terms_recent', $get_items );
				}

			}

		}

	}

	/**
	 * Check if WooCommerce is active
	 */
	public static function is_woocommerce() {

		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return true;
		}

		return false;

	}

}

new WCSTM_Lite();