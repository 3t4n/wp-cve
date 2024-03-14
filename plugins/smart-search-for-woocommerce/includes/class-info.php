<?php
/**
 * Searchanise information
 *
 * @package Searchanise/Info
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

/**
 * Searchanise information class
 */
class Info {

	const RESYNC             = 'resync';
	const OUTPUT             = 'visual';
	const PROFILER           = 'profiler';
	const LANG_CODE          = 'lang_code';
	const DISPLAY_ERRORS     = 'display_errors';
	const PRODUCT_ID         = 'product_id';
	const PRODUCT_IDS        = 'product_ids';
	const CATEGORY_ID        = 'category_id';
	const CATEGORY_IDS       = 'category_ids';
	const PAGE_ID            = 'page_id';
	const PAGE_IDS           = 'page_ids';
	const PARENT_PRIVATE_KEY = 'parent_private_key';

	/**
	 * Adds searchanise information page to frontend
	 */
	public static function init() {
		add_rewrite_rule( '^searchanise/info', 'index.php?is_searchanise_page=1&post_type=page', 'top' );
		add_action(
			'query_vars',
			function ( $vars ) {
				$vars[] = 'is_searchanise_page';
				return $vars;
			}
		);
		add_filter(
			'template_include',
			function ( $template ) {
				if ( get_query_var( 'is_searchanise_page' ) ) {
					$template = SE_ABSPATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'searchanise_info.php';
				}

				return $template;
			},
			1000,
			1
		);

		return true;
	}

	/**
	 * Display Searchanise information
	 */
	public static function display() {
		$visual = self::get_param( self::OUTPUT, false );

		if ( ! Api::get_instance()->check_request_private_key() ) {
			$addon_options = Api::get_instance()->get_addon_options();
			$options = array(
				'status'  => $addon_options['addon_status'],
				'api_key' => $addon_options['api_key'],
			);

			if ( $visual ) {
				Api::get_instance()->print_r( $options );
			} else {
				print( wp_json_encode( $options ) );
			}
		} else {
			$resync         = self::get_param( self::RESYNC, 'N' );
			$display_errors = self::get_param( self::DISPLAY_ERRORS, 'N' );
			$lang_code      = self::get_param( self::LANG_CODE, Api::get_instance()->get_locale() );
			$product_id     = self::get_param( self::PRODUCT_ID, false );
			$product_ids    = self::get_param( self::PRODUCT_IDS, false );
			$category_id    = self::get_param( self::CATEGORY_ID, false );
			$category_ids   = self::get_param( self::CATEGORY_IDS, false );
			$page_id        = self::get_param( self::PAGE_ID, false );
			$page_ids       = self::get_param( self::PAGE_IDS, false );

			if ( 'Y' == $display_errors ) {
				@error_reporting( E_ALL | E_STRICT );
				@ini_set( 'display_startup_errors', 1 );

				fn_se_define( 'WP_DEBUG', true );
				fn_se_define( 'WP_DEBUG_DISPLAY', true );
			} else {
				@error_reporting( 0 );
				@ini_set( 'display_startup_errors', 0 );

				fn_se_define( 'WP_DEBUG_DISPLAY', false );
			}

			$product_ids = $product_id ? $product_id : ( $product_ids ? explode( ',', $product_ids ) : 0 );
			$category_ids = $category_id ? $category_id : ( $category_ids ? explode( ',', $category_ids ) : 0 );
			$page_ids = $page_id ? $page_id : ( $page_ids ? explode( ',', $page_ids ) : 0 );

			if ( 'Y' == $resync ) {
				Api::get_instance()->queue_import( null, false );

			} elseif ( ! empty( $product_ids ) || ! empty( $page_ids ) || ! empty( $category_ids ) ) {
				switch_to_locale( $lang_code ); // Emulate language.

				$products_data = Async::get_instance()->get_products_data( $product_ids, $lang_code, false );
				$categories    = Async::get_instance()->get_categories_data( $category_ids, $lang_code );
				$pages         = Async::get_instance()->get_pages_data( $page_ids, $lang_code );

				$feed = array(
					'header'     => Async::get_instance()->get_header( $lang_code ),
					'items'      => $products_data['items'],
					'schema'     => $products_data['schema'],
					'categories' => $categories['categories'],
					'pages'      => $pages['pages'],
				);

				if ( $visual ) {
					Api::get_instance()->print_r( $feed );
				} else {
					print( wp_json_encode( $feed ) );
				}
			} else {
				$options = self::get_info( $lang_code );

				if ( $visual ) {
					Api::get_instance()->print_r( $options );
				} else {
					print( wp_json_encode( $options ) );
				}
			}
		}
	}

	/**
	 * Gets Searchanise plugin info
	 *
	 * @param string $lang_code Lang code.
	 *
	 * @return array
	 */
	public static function get_info( $lang_code ) {
		$options = Api::get_instance()->get_addon_options();
		$options = array_merge(
			array(
				'api_key' => array(),
			),
			$options
		);

		$options['log_dir']                  = SE_LOG_DIR;
		$options['next_queue']               = Queue::get_instance()->get_next_queue();
		$options['total_items_in_queue']     = Queue::get_instance()->get_total_items();
		$options['queue_status']             = Queue::get_instance()->get_queue_status() ? 'Y' : 'N';

		$options['search_input_selector']    = html_entity_decode( Api::get_instance()->get_search_input_selector(), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 );
		$options['search_enabled']           = Api::get_instance()->get_enabled_searchanise_search() ? 'Y' : 'N';

		$options['sync_mode']                = Api::get_instance()->get_sync_mode();
		$options['cron_async_enabled']       = Api::get_instance()->check_cron_async_enabled() ? 'Y' : 'N';
		$options['ajax_async_enabled']       = Api::get_instance()->check_ajax_async_enabled() ? 'Y' : 'N';

		$options['max_execution_time']       = ini_get( 'max_execution_time' );
		@set_time_limit( 0 );
		$options['max_execution_time_after'] = ini_get( 'max_execution_time' );

		$options['ignore_user_abort']        = ini_get( 'ignore_user_abort' );
		@ignore_user_abort( 1 );
		$options['ignore_user_abort_after']  = ini_get( 'ignore_user_abort_after' );

		$options['memory_limit'] = ini_get( 'memory_limit' );
		wp_raise_memory_limit( 'searchanise_async' );
		$options['memory_limit_after']       = ini_get( 'memory_limit' );

		list($start, $max) = Async::get_instance()->get_min_max_product_id( true, $lang_code );
		$options['products']['min'] = $start;
		$options['products']['max'] = $max;
		$options['products']['count'] = Async::get_instance()->get_products_count( true, $lang_code );

		list($start, $max) = Async::get_instance()->get_min_max_page_id( $lang_code );
		$options['pages']['min'] = $start;
		$options['pages']['max'] = $max;

		return $options;
	}

	/**
	 * Returns param from request
	 *
	 * @param string $name Param name.
	 * @param string $default Default value.
	 */
	private static function get_param( $name, $default = '' ) {
		return isset( $_REQUEST[ $name ] ) ? strtoupper( sanitize_key( $_REQUEST[ $name ] ) ) : $default;
	}
}
