<?php
/**
 * Searchanise Api helper
 *
 * @package Searchanise/ApiSe
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

/**
 * ApiSe helper class
 */
class Api {

	// Export statuses.
	const EXPORT_STATUS_NONE          = 'none';
	const EXPORT_STATUS_QUEUED        = 'queued';
	const EXPORT_STATUS_START         = 'start';
	const EXPORT_STATUS_PROCESSING    = 'processing';
	const EXPORT_STATUS_SENT          = 'sent';
	const EXPORT_STATUS_DONE          = 'done';
	const EXPORT_STATUS_SYNC_ERROR    = 'sync_error';

	// Addon statuses.
	const ADDON_STATUS_ENABLED        = 'enabled';
	const ADDON_STATUS_DISABLED       = 'disabled';
	const ADDON_STATUS_DELETED        = 'deleted';

	// Sync Modes.
	const SYNC_MODE_REALTIME          = 'realtime';
	const SYNC_MODE_PERIODIC          = 'periodic';
	const SYNC_MODE_MANUAL            = 'manual';

	// Default values.
	const DEFAULT_SEARCH_FIELD_ID     = '#search,form input[name="s"]';
	const DEFAULT_SEARCH_RESULTS_PAGE = 'search-results';
	const DEFAULT_COLOR_NAME          = 'color';
	const DEFAULT_SIZE_NAME           = 'size';

	// Cookie values(RecentlyViewedProducts).
	const COOKIE_RECENTLY_VIEWED_LIMIT = 20;
	const COOKIE_RECENTLY_VIEWED_NAME  = 'se-recently-viewed-products';

	// Min versions.
	const MIN_WOOCOMMERCE_VERSION             = '3.0.0';
	const MIN_WORDPRESS_VERSION               = '4.0.0';
	const MIN_WORDPRESS_VERSION_FOR_WP_JQUERY = '5.6';

	const OPTION_PREFIX = 'se_';
	const LABEL_FOR_PRICES_USERGROUP        = 'se_price_';
	const LABEL_FOR_LIST_PRICES_USERGROUP   = 'se_list_price_';
	const LABEL_FOR_MAX_PRICES_USERGROUP    = 'se_max_price_';

	const USERGROUP_GUEST = 'guest';

	const SUGGESTIONS_MAX_RESULTS = 1;

	const LOAD_PRIORITY           = 10;
	const POSTPONED_LOAD_PRIORITY = 99;

	/**
	 * Current instance
	 *
	 * @var Api
	 */
	private static $instance = null;

	/**
	 * Returns class instance
	 *
	 * @return Api
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Returns parent private key
	 *
	 * @return string
	 */
	public function get_parent_private_key() {
		return $this->get_setting( 'parent_private_key' );
	}

	/**
	 * Returns woocommerce version
	 *
	 * @return string
	 */
	public function get_woocommerce_plugin_version() {
		$data = get_file_data( SE_ABSPATH . DIRECTORY_SEPARATOR . 'woocommerce-searchanise.php', array( 'Woo' ), 'plugin' );

		return $data[0];
	}

	/**
	 * Check if parent private key exists
	 *
	 * @return bool
	 */
	public function check_parent_private_key() {
		$parent_private_key = $this->get_parent_private_key();

		return ! empty( $parent_private_key );
	}

	/**
	 * Set parent key
	 *
	 * @param string $parent_private_key Parent private key.
	 *
	 * @return void
	 */
	public function set_parent_private_key( $parent_private_key ) {
		$this->set_setting( 'parent_private_key', '', $parent_private_key );
	}

	/**
	 * Set private key
	 *
	 * @param string $api_key Api key.
	 * @param string $lang_code Lang code.
	 */
	public function set_private_key( $api_key, $lang_code ) {
		$this->set_setting( 'private_key', $lang_code, $api_key );
	}

	/**
	 * Get private key for lang code
	 *
	 * @param string $lang_code Lang code.
	 *
	 * @return mixed|string
	 */
	public function get_private_key( $lang_code ) {
		static $private_keys = array();

		if ( ! isset( $private_keys[ $lang_code ] ) ) {
			$private_keys[ $lang_code ] = $this->get_setting( 'private_key', $lang_code );
		}

		return isset( $private_keys[ $lang_code ] ) ? $private_keys[ $lang_code ] : '';
	}

	/**
	 * Returns private keys
	 *
	 * @return array
	 */
	public function get_private_keys() {
		global $wpdb;

		static $private_keys = array();

		if ( empty( $private_keys ) ) {
			$keys = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT value, lang_code FROM {$wpdb->prefix}wc_se_settings WHERE name = %s",
					'private_key'
				),
				ARRAY_A
			);

			foreach ( $keys as $k ) {
				$k['lang_code'] = $this->get_locale( $k['lang_code'] );
				$private_keys[ $k['lang_code'] ] = $k['value'];
			}
		}

		return $private_keys;
	}

	/**
	 * Check private key
	 *
	 * @param string $lang_code Lang code.
	 *
	 * @return bool
	 */
	public function check_private_key( $lang_code ) {
		$private_key = $this->get_private_key( $lang_code );

		return ! empty( $private_key );
	}

	/**
	 * Check private keys from request
	 */
	public function check_request_private_key() {
		static $check = null;

		if ( null === $check ) {
			$parent_private_key = isset( $_REQUEST['parent_private_key'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['parent_private_key'] ) ) : '';

			if (
				empty( $parent_private_key )
				|| $parent_private_key != $this->get_parent_private_key()
			) {
				$check = false;
			} else {
				$check = true;
			}
		}

		return $check;
	}

	/**
	 * Returns registered api keys
	 */
	public function get_api_keys() {
		global $wpdb;

		static $api_keys;

		if ( empty( $api_keys ) ) {
			$keys = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT
					value, lang_code
				FROM {$wpdb->prefix}wc_se_settings
				WHERE name = %s AND lang_code IN (SELECT lang_code FROM `{$wpdb->prefix}wc_se_settings` WHERE name = %s and value != %s)",
					'api_key',
					'export_status',
					self::EXPORT_STATUS_NONE
				),
				ARRAY_A
			);

			foreach ( $keys as $k ) {
				$k['lang_code'] = $this->get_locale( $k['lang_code'] );
				$api_keys[ $k['lang_code'] ] = $k['value'];
			}
		}

		return $api_keys;
	}

	/**
	 * Set api key
	 *
	 * @param string $api_key Api key.
	 * @param string $lang_code Lang code.
	 */
	public function set_api_key( $api_key, $lang_code ) {
		$this->set_setting( 'api_key', $lang_code, $api_key );
	}

	/**
	 * Get api key
	 *
	 * @param string $lang_code Lang code.
	 */
	public function get_api_key( $lang_code ) {
		static $api_keys = array();

		if ( ! isset( $api_keys[ $lang_code ] ) ) {
			$api_keys[ $lang_code ] = $this->get_setting( 'api_key', $lang_code );
		}

		return isset( $api_keys[ $lang_code ] ) ? $api_keys[ $lang_code ] : '';
	}

	/**
	 * Returns export statuses
	 */
	public function get_export_statuses() {
		global $wpdb;

		$statuses = array();

		if ( empty( $statuses ) ) {
			$keys = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT
					value, lang_code
				FROM {$wpdb->prefix}wc_se_settings
				WHERE name = %s AND value != %s",
					'export_status',
					self::EXPORT_STATUS_NONE
				),
				ARRAY_A
			);

			foreach ( $keys as $k ) {
				$k['lang_code'] = $this->get_locale( $k['lang_code'] );
				$statuses[ $k['lang_code'] ] = $k['value'];
			}
		}

		return $statuses;
	}

	/**
	 * Returns export status
	 *
	 * @param string $lang_code Lang code.
	 */
	public function get_export_status( $lang_code ) {
		return $this->get_setting( 'export_status', $lang_code );
	}

	/**
	 * Checks export status
	 *
	 * @param string $lang_code Lang code.
	 */
	public function check_export_status( $lang_code ) {
		return $this->get_export_status( $lang_code ) == self::EXPORT_STATUS_DONE;
	}

	/**
	 * Chekc if export status done
	 *
	 * @param string $lang_code Lang code.
	 * @param bool   $skip_time_check Skip flag.
	 */
	public function check_export_status_is_done( $lang_code = null, $skip_time_check = false ) {
		$engines_data = $this->get_engines( $lang_code );

		foreach ( $engines_data as $engine_data ) {
			if ( self::EXPORT_STATUS_SENT != $engine_data['export_status'] ) {
				continue;
			}

			if (
				( time() - $this->get_last_request( $lang_code ) ) > 10
				|| ( $this->get_last_request( $lang_code ) - 10 ) > time()
				|| $skip_time_check
			) {
				try {
					$response = self::get_instance()->send_request(
						'/api/state/get/json',
						$engine_data['private_key'],
						array(
							'status' => '',
							'full_import' => '',
						),
						true
					);

				} catch ( Searchanise_Exception $e ) {
					$response = array();
				}

				$variables = isset( $response['variable'] ) ? $response['variable'] : array();
				if ( ! empty( $variables ) && isset( $variables['status'] ) ) {
					if ( 'normal' == $variables['status'] && 'done' == $variables['full_import'] ) {
						$this->set_export_status( self::EXPORT_STATUS_DONE, $engine_data['lang_code'] );
						$skip_time_check = true;

					} elseif ( 'disabled' == $variables['status'] ) {
						$this->set_export_status( self::EXPORT_STATUS_NONE, $engine_data['lang_code'] );
					}
				}
			}
		}
	}

	/**
	 * Set export status
	 *
	 * @param string $status    Export status.
	 * @param string $lang_code Lang code.
	 */
	public function set_export_status( $status, $lang_code ) {
		$this->set_setting( 'export_status', $lang_code, $status );
	}

	/**
	 * Set setting value
	 *
	 * @param string $name      Setting name.
	 * @param string $lang_code Lang code.
	 * @param mixed  $value     Value.
	 */
	public function set_setting( $name, $lang_code, $value ) {
		global $wpdb;

		$wpdb->replace(
			"{$wpdb->prefix}wc_se_settings",
			array(
				'name'      => $name,
				'lang_code' => $this->get_locale_settings( $lang_code ),
				'value'     => $value,
			)
		);
	}

	/**
	 * Get setting value
	 *
	 * @param string $name      Setting name.
	 * @param string $lang_code Lang code.
	 */
	public function get_setting( $name, $lang_code = '' ) {
		global $wpdb;

		return $wpdb->get_var(
			$wpdb->prepare(
				"SELECT
				value
			FROM {$wpdb->prefix}wc_se_settings
			WHERE name = %s AND lang_code = %s",
				$name,
				$this->get_locale_settings( $lang_code )
			)
		);
	}

	/**
	 * Get system setting value
	 *
	 * @param string $name    Setting name.
	 * @param bool   $default Default flag.
	 */
	public function get_system_setting( $name, $default = false ) {
		return get_option( self::OPTION_PREFIX . $name, $default );
	}

	/**
	 * Set system setting value
	 *
	 * @param string $name  Setting name.
	 * @param mixed  $value Default flag.
	 */
	public function set_system_setting( $name, $value ) {
		update_option( self::OPTION_PREFIX . $name, $value );
	}

	/**
	 * Progress output
	 *
	 * @param string $progress Progress.
	 */
	public function echo_progress( $progress ) {
		if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
			return;
		}

		echo wp_kses( $progress, array( 'br' => array() ) );
	}

	/**
	 * Returns last se request
	 *
	 * @param string $lang_code Lang code.
	 */
	public function get_last_request( $lang_code = '' ) {
		return $this->get_setting( 'last_request', '' );
	}

	/**
	 * Set last searchanise request
	 *
	 * @param int    $time      Timestamp.
	 * @param string $lang_code Lang code.
	 */
	public function set_last_request( $time, $lang_code = '' ) {
		$this->set_setting( 'last_request', $lang_code, $time );
	}

	/**
	 * Get last resync datetime
	 *
	 * @param string $lang_code Lang code.
	 */
	public function get_last_resync( $lang_code ) {
		return $this->get_setting( 'last_resync', '' );
	}

	/**
	 * Set last resync datetime
	 *
	 * @param string $lang_code Lang code.
	 * @param string $time Datetime.
	 */
	public function set_last_resync( $lang_code, $time ) {
		$this->set_setting( 'last_resync', '', $time );
	}

	/**
	 * Returns last requests
	 *
	 * @return array
	 */
	public function get_last_requests() {
		global $wpdb;

		$requests = array();

		if ( empty( $requests ) ) {
			$keys = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT
					value, lang_code
				FROM {$wpdb->prefix}wc_se_settings
				WHERE name = %s",
					'last_request'
				),
				ARRAY_A
			);

			foreach ( $keys as $k ) {
				$requests[ $k['lang_code'] ] = $this->format_date( $k['value'] );
			}
		}

		return $requests;
	}

	/**
	 * Returns last resync datetimes
	 *
	 * @return array
	 */
	public function get_last_resyncs() {
		global $wpdb;

		$resyncs = array();

		if ( empty( $resyncs ) ) {
			$keys = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT
					value, lang_code
				FROM {$wpdb->prefix}wc_se_settings
				WHERE name = %s",
					'last_resync'
				),
				ARRAY_A
			);

			foreach ( $keys as $k ) {
				$resyncs[ $k['lang_code'] ] = $this->format_date( $k['value'] );
			}
		}

		return $resyncs;
	}

	/**
	 * Format timestamp
	 *
	 * @param string $timestamp Timestamp.
	 *
	 * @return string
	 */
	public function format_date( $timestamp ) {
		if ( empty( $timestamp ) ) {
			return '';
		}

		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );

		return date_i18n( $date_format, $timestamp ) . ' ' . date_i18n( $time_format, $timestamp );
	}

	/**
	 * Check if installed
	 *
	 * @return bool
	 */
	public function check_auto_install() {
		return $this->get_setting( 'auto_installed' ) != 'Y';
	}

	/**
	 * Set is installed
	 *
	 * @param bool $value Flag.
	 */
	public function set_auto_install( $value = false ) {
		$this->set_setting( 'auto_installed', '', true == $value ? 'Y' : 'N' );
	}

	/**
	 * Returns resync interval
	 *
	 * @return string
	 */
	public function get_resync_interval() {
		return $this->get_system_setting( 'resync_interval', 'daily' );
	}

	/**
	 * Returns index interval
	 *
	 * @return string
	 */
	public function get_index_interval() {
		return 'every_minute';
	}

	/**
	 * Checks if cron async enabled
	 *
	 * @return bool
	 */
	public function check_cron_async_enabled() {
		return $this->get_system_setting( 'cron_async_enabled', 'N' ) == 'Y';
	}

	/**
	 * Checks if ajax async enabled
	 *
	 * @return bool
	 */
	public function check_ajax_async_enabled() {
		return $this->get_system_setting( 'ajax_async_enabled', 'N' ) == 'Y';
	}

	/**
	 * Checks if object asycn enabled
	 *
	 * @return bool
	 */
	public function check_object_async_enabled() {
		return $this->get_system_setting( 'object_async_enabled', 'Y' ) == 'Y';
	}

	/**
	 * Returns sync mode
	 *
	 * @return string
	 */
	public function get_sync_mode() {
		return $this->get_system_setting( 'sync_mode', self::SYNC_MODE_REALTIME );
	}

	/**
	 * Returns true if realtime sync mode enabled
	 *
	 * @return bool
	 */
	public function is_realtime_sync_mode() {
		return $this->get_sync_mode() == self::SYNC_MODE_REALTIME;
	}

	/**
	 * Returns true if periodic sync mode enabled
	 *
	 * @return bool
	 */
	public function is_periodic_sync_mode() {
		return $this->get_sync_mode() == self::SYNC_MODE_PERIODIC;
	}

	/**
	 * Returns true if manual sync mode enabled
	 *
	 * @return bool
	 */
	public function is_manual_sync_mode() {
		return $this->get_sync_mode() == self::SYNC_MODE_MANUAL;
	}

	/**
	 * Returns max suggestion results count
	 *
	 * @return int
	 */
	public function get_suggestions_max_results() {
		return self::SUGGESTIONS_MAX_RESULTS;
	}

	/**
	 * Returns search input selector
	 *
	 * @return string
	 */
	public function get_search_input_selector() {
		return $this->get_system_setting( 'search_input_selector', htmlentities( stripslashes( self::DEFAULT_SEARCH_FIELD_ID ), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 ) );
	}

	/**
	 * Returns true if direct images are used
	 *
	 * @return bool
	 */
	public function use_direct_image_links() {
		return $this->get_system_setting( 'use_direct_image_links', 'N' ) == 'Y';
	}

	/**
	 * Returns true if need import blog posts
	 *
	 * @return bool
	 */
	public function import_block_posts() {
		return $this->get_system_setting( 'import_block_posts', 'N' ) == 'Y';
	}

	/**
	 * Returns true if analytics need to be shown on dashboard
	 *
	 * @return bool
	 */
	public function is_show_analytics_on_dashboard() {
		return $this->get_system_setting( 'show_analytics_on_dashboard', 'N' ) == 'Y';
	}

	/**
	 * Returns true native jquery is using
	 *
	 * @return bool
	 */
	public function is_use_wp_jquery() {
		return $this->get_system_setting( 'use_wp_jquery', 'N' ) == 'Y';
	}

	/**
	 * Returns true if need import also bought products
	 *
	 * @return bool
	 */
	public function import_also_bought_products() {
		return Async::IMPORT_ALSO_BOUGHT_PRODUCTS;
	}

	/**
	 * Set if widget enabled
	 *
	 * @param string $status Status.
	 * @param string $lang_code Lang code.
	 */
	public function set_result_widget_enabled( $status, $lang_code ) {
		$this->set_setting( 'result_widget_enabled', $lang_code, $status );
	}

	/**
	 * Returns if widget enabled
	 *
	 * @param string $lang_code Lang code.
	 */
	public function is_result_widget_enabled( $lang_code ) {
		return $this->get_setting( 'result_widget_enabled', $lang_code ) == 'Y';
	}

	/**
	 * Set if navigation enabled
	 *
	 * @param string $status Status.
	 * @param string $lang_code Lang code.
	 */
	public function set_navigation_enabled( $status, $lang_code ) {
		$this->set_setting( 'navigation_enabled', $lang_code, $status );
	}

	/**
	 * Returns if navigation enabled
	 *
	 * @param string $lang_code Lang code.
	 */
	public function is_navigation_enabled( $lang_code ) {
		return $this->get_setting( 'navigation_enabled', $lang_code ) == 'Y';
	}

	/**
	 * Set if weglot enabled
	 *
	 * @param string $status Status.
	 * @param string $lang_code Lang code.
	 */
	public function set_integration_weglot_enabled( $status, $lang_code ) {
		$this->set_setting( 'integration_weglot_enabled', $lang_code, $status );
	}

	/**
	 * Returns if weglot enabled
	 *
	 * @param string $lang_code Lang code.
	 */
	public function is_integration_weglot_enabled( $lang_code ) {
		return $this->get_setting( 'integration_weglot_enabled', $lang_code ) == 'Y';
	}

	/**
	 * Returns search result page id
	 *
	 * @return string
	 */
	public function get_search_results_page() {
		return $this->get_system_setting( 'search_result_page', self::DEFAULT_SEARCH_RESULTS_PAGE );
	}

	/**
	 * Get async memory limit
	 *
	 * @return string
	 */
	public function get_async_memory_limit() {
		return SE_MEMORY_LIMIT;
	}

	/**
	 * Returns maximum processing time
	 *
	 * @return int
	 */
	public function get_max_processing_time() {
		return SE_MAX_PROCESSING_TIME;
	}

	/**
	 * Returns max errors count
	 *
	 * @return int
	 */
	public function get_max_error_count() {
		return SE_MAX_ERROR_COUNT;
	}

	/**
	 * Returns products per pass
	 *
	 * @return int
	 */
	public function get_products_per_pass() {
		return SE_PRODUCTS_PER_PASS;
	}

	/**
	 * Returns categories per pass
	 *
	 * @return int
	 */
	public function get_categories_per_pass() {
		return SE_CATEGORIES_PER_PASS;
	}

	/**
	 * Returns pages per pass
	 *
	 * @return int
	 */
	public function get_pages_per_pass() {
		return SE_PAGES_PER_PASS;
	}

	/**
	 * Set notification async status
	 *
	 * @param bool $status Status.
	 */
	public function set_notification_async_completed( $status = true ) {
		$this->set_system_setting( 'notification_async_complete', $status );
	}

	/**
	 * Checks if notification exist
	 *
	 * @return string
	 */
	public function check_notificaton_async_completed() {
		return $this->get_system_setting( 'notification_async_complete' ) == true;
	}

	/**
	 * Returns color attributes
	 *
	 * @return array
	 */
	public function get_color_attributes() {
		$attributes = $this->get_system_setting( 'color_attribute' );

		return is_array( $attributes ) ? $attributes : array_map( 'trim', explode( ',', $attributes ) );
	}

	/**
	 * Returns size attributes
	 *
	 * @return array
	 */
	public function get_size_attributes() {
		$attributes = $this->get_system_setting( 'size_attribute' );

		return is_array( $attributes ) ? $attributes : array_map( 'trim', explode( ',', $attributes ) );
	}

	/**
	 * Returns custom attributes
	 *
	 * @return array
	 */
	public function get_custom_attributes() {
		$attributes = $this->get_system_setting( 'custom_attribute' );
		$custom_taxonomies = $this->get_system_setting( 'custom_taxonomies' );

		$attributes = is_array( $attributes ) ? $attributes : array_map( 'trim', explode( ',', $attributes ) );

		$custom_taxonomies = explode( ', ', $custom_taxonomies );

		return array_merge( $attributes, $custom_taxonomies );
	}

	/**
	 * Returns custom product fields
	 *
	 * @return array
	 */
	public function get_custom_product_fields() {
		$attributes = $this->get_system_setting( 'custom_product_fields' );

		return is_array( $attributes ) ? $attributes : array_map( 'trim', explode( ',', $attributes ) );
	}

	/**
	 * Check if module is rated
	 *
	 * @return bool
	 */
	public function get_is_rated() {
		return $this->get_system_setting( 'admin_footer_text_rated' ) == 'Y';
	}

	/**
	 * Set if module is rated
	 */
	public function set_is_rated() {
		$this->set_system_setting( 'admin_footer_text_rated', 'Y' );
	}

	/**
	 * Set indexation flag
	 *
	 * @param bool $value Flag.
	 */
	public function set_is_need_reindexation( $value ) {
		$this->set_system_setting( 'need_reindexation', true == $value ? 'Y' : 'N' );
	}

	/**
	 * Returns indexation flag
	 *
	 * @return bool
	 */
	public function get_is_need_reindexation() {
		return $this->get_system_setting( 'need_reindexation' ) == 'Y';
	}

	/**
	 * Delete keys
	 *
	 * @param string $lang_code Lang code.
	 *
	 * @return bool
	 */
	public function delete_keys( $lang_code = null ) {
		$engines = $this->get_engines( $lang_code );

		foreach ( $engines as $engine ) {
			$this->addon_status_request( self::ADDON_STATUS_DELETED, $engine['lang_code'] );
			Queue::get_instance()->clear_actions( $engine['lang_code'] );

			$this->set_api_key( '', $engine['lang_code'] );
			$this->set_private_key( '', $engine['lang_code'] );
			$this->set_export_status( self::EXPORT_STATUS_NONE, $engine['lang_code'] );
		}

		return true;
	}

	/**
	 * Cleanup module data
	 *
	 * @return bool
	 */
	public function cleanup() {
		$result = $this->delete_keys() == true;

		if ( $result ) {
			$this->set_parent_private_key( '' );
			$this->set_auto_install( false );
		}

		return $result;
	}

	/**
	 * Returns price labels
	 *
	 * @param string $type_price Price type.
	 *
	 * @return array
	 */
	public function get_cur_label_for_prices_usergroup( $type_price = 'price' ) {
		switch ( $type_price ) {
			case 'price':
				$label_price = self::LABEL_FOR_PRICES_USERGROUP;
				break;
			case 'list_price':
				$label_price = self::LABEL_FOR_LIST_PRICES_USERGROUP;
				break;
			case 'max_price':
				$label_price = self::LABEL_FOR_MAX_PRICES_USERGROUP;
				break;
		}

		$current_user = wp_get_current_user();

		/**
		 * Get is use usergroups
		 *
		 * @since 1.0.0
		 */
		$se_use_usergroups = (bool) apply_filters( 'se_is_use_usergroups', false );

		if ( ! empty( $current_user->roles ) && $se_use_usergroups && $label_price ) {
			return $label_price . $current_user->roles[0];
		} else {
			return false;
		}
	}

	/**
	 * Returns if need hide empty prices
	 *
	 * @return bool
	 */
	public function get_hide_empty_price() {
		/**
		 * Get is hide empty price
		 *
		 * @since 1.0.0
		 */
		return (bool) apply_filters( 'se_is_hide_empty_price', false );
	}

	/**
	 * Returns usergroup ids
	 *
	 * @return array
	 */
	public function get_current_usergroup_ids() {
		$current_user = wp_get_current_user();
		$default_usergroup = array( self::USERGROUP_GUEST );

		return array_merge( $default_usergroup, $current_user->roles );
	}

	/**
	 * Returns Searchanise addon options
	 *
	 * @return array
	 */
	public function get_addon_options() {
		global $wp_version, $wp_db_version, $wp_local_package;

		$ret = array();

		$ret['parent_private_key']      = $this->get_parent_private_key();
		$ret['private_key']             = $this->get_private_keys();
		$ret['api_key']                 = $this->get_api_keys();
		$ret['export_status']           = $this->get_export_statuses();

		$ret['last_request']            = $this->get_last_requests();
		$ret['last_resync']             = $this->get_last_resyncs();

		$ret['addon_status']            = $this->get_module_status() == 'Y' ? 'enabled' : 'disabled';
		$ret['addon_version']           = $this->get_system_setting( 'version' );

		$ret['php_verison']             = PHP_VERSION;

		// Get WP version.
		$ret['wordpress_version']       = $wp_version;
		$ret['wordpress_db_version']    = $wp_db_version;
		$ret['wordpress_local_package'] = $wp_local_package;
		$ret['wordpress_path']          = ABSPATH;

		// Get WooCommerce version.
		$ret['woocommerce'] = get_plugin_data( WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'woocommerce/woocommerce.php' );

		$ret['plugins'] = get_plugins();

		foreach ( $ret['plugins'] as $plugin_key => $plugin ) {
			$ret['plugins'][ $plugin_key ]['Status'] = is_plugin_active( $plugin_key ) ? 'A' : 'D';
		}

		/**
		 * Get addon options
		 *
		 * @since 1.0.0
		 */
		return (array) apply_filters( 'se_addon_options', $ret );
	}

	/**
	 * Returns true if search enabled
	 *
	 * @return bool
	 */
	public function get_enabled_searchanise_search() {
		return $this->get_system_setting( 'enabled_searchanise_search', 'Y' ) == 'Y';
	}

	/**
	 * Returns store name by lang_code
	 *
	 * @param string $lang_code  Lang code.
	 *
	 * @return string Store name
	 */
	public function get_store_name( $lang_code ) {
		if ( ! function_exists( 'wp_get_available_translations' ) ) {
			require_once ABSPATH . 'wp-admin/includes/translation-install.php';
		}

		static $names = array();

		if ( ! isset( $names[ $lang_code ] ) ) {
			$available_translations = wp_get_available_translations();
			$full_name = Locales::get_full_name_from_lang_code( $lang_code );

			if ( ! empty( $full_name ) ) {
				$names[ $lang_code ] = $full_name;
			} elseif ( isset( $available_translations[ $lang_code ]['english_name'] ) ) {
				$names[ $lang_code ] = $available_translations[ $lang_code ]['english_name'];
			} else {
				/**
				 * Get english name
				 *
				 * @since 1.0.0
				 */
				$names[ $lang_code ] = 'en_US' == $lang_code ? 'English' : apply_filters( 'se_get_english_name', $lang_code );
			}
		}

		return $names[ $lang_code ];
	}

	/**
	 * Returns ISO language name by lang_code
	 *
	 * @param string $lang_code  Lang code.
	 *
	 * @return string ISO name.
	 */
	public function get_iso_lang_name( $lang_code ) {
		if ( ! function_exists( 'wp_get_available_translations' ) ) {
			require_once ABSPATH . 'wp-admin/includes/translation-install.php';
		}

		static $names = array();

		if ( ! isset( $names[ $lang_code ] ) ) {
			$available_translations = wp_get_available_translations();

			if ( isset( $available_translations[ $lang_code ] ) ) {
				$names[ $lang_code ] = current( $available_translations[ $lang_code ]['iso'] );
			} else {
				$names[ $lang_code ] = 'en';
			}
		}

		return $names[ $lang_code ];
	}

	/**
	 * Check module status
	 *
	 * @return boolean
	 */
	public function get_module_status() {
		return Installer::is_searchanise_installed()
			&& ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) ? 'Y' : 'N';
	}

	/**
	 * Check and test enviroments
	 *
	 * @param boolean $display_errors Display flag.
	 */
	public function check_enviroments( $display_errors = true ) {
		if (
			defined( 'DOING_AJAX' ) && DOING_AJAX
			|| defined( 'DOING_CRON' ) && DOING_CRON
			|| ! is_admin()
		) {
			return;
		}

		$errors = array();

		if ( ! self::get_instance()->test_connect() ) {
			/* translators: service url */
			$errors[] = sprintf( __( 'Searchanise: There is no connection to Searchanise server! For Searchanise to work properly, the store server must be able to access %s. Please contact Searchanise <a href="mailto: feedback@searchanise.com">feedback@searchanise.com</a> technical support or your system administrator.', 'woocommerce-searchanise' ), SE_SERVICE_URL );
		}

		if ( ! Queue::get_instance()->get_queue_status() ) {
			$errors[] = __( 'Searchanise: We found an issue with the export of changes in your store catalog. To resolve the issue please contact Searchanise <a href="mailto:feedback@searchanise.com">feedback@searchanise.com</a> technical support.', 'woocommerce-searchanise' );
		}

		if ( ! empty( $errors ) && true == $display_errors ) {
			foreach ( $errors as $error ) {
				add_action(
					'admin_notices',
					function () use ( $error ) {
						echo '<div class="error notice"><p>' . wp_kses( $error, array( 'a' => array( 'href' => array() ) ) ) . '</p></div>';
					}
				);
			}
		}

		return $errors;
	}

	/**
	 * Get Searchanise admin url
	 *
	 * @param string $mode  Searchanise admin mode.
	 *
	 * @return string
	 */
	public function get_admin_url( $mode = '' ) {
		return get_admin_url( null, 'admin.php?page=searchanise' . ( '' != $mode ? '&mode=' . $mode : '' ) );
	}

	/**
	 * Get frontend url with parameters
	 *
	 * @param string $lang_code Lang code.
	 * @param array  $params    Query parameters.
	 *
	 * @return string
	 */
	public function get_frontend_url( $lang_code, $params = array() ) {
		$site_url = get_site_url();

		/**
		 * Get frontend url pre
		 *
		 * @since 1.0.0
		 */
		$site_url = apply_filters( 'se_get_frontend_url_pre', $site_url, $lang_code, $params );

		$separator = strpos( $site_url, '?' ) === false ? '?' : '&';

		if ( ! empty( $params ) ) {
			$query = http_build_query( $params );
		}

		$url = $site_url . ( ! empty( $query ) ? $separator . $query : '' );

		/**
		 * Filters frontend url
		 *
		 * @since 1.0.0
		 *
		 * @param string $url Frontend url
		 * @param string $lang_code Lang code
		 * @param array $params Url params
		 */
		return apply_filters( 'se_get_frontend_url', $url, $lang_code, $params );
	}

	/**
	 * Get engines data
	 *
	 * @param string $lang_code     Engine lang_code.
	 * @param bool   $use_cache     If true, cache will be used.
	 * @param bool   $for_uninstall If used under uninstaller.
	 *
	 * @return array
	 */
	public function get_engines( $lang_code = null, $use_cache = true, $for_uninstall = false ) {
		static $engines_data = array();

		if ( ! empty( $lang_code ) ) {
			$active_languages = array( $lang_code );
		} elseif ( $for_uninstall ) {
			$active_languages = $this->get_langs_for_uninstall( array( $this->get_locale() ) );
		} else {
			$active_languages = array( $this->get_locale() );

			/**
			 * Get active languages
			 *
			 * @since 1.0.0
			 *
			 * @param array $active_languages
			 */
			$active_languages = (array) apply_filters( 'se_get_active_languages', $active_languages );
		}

		$engines = array();
		foreach ( $active_languages as $lang_code ) {
			if ( $use_cache && ! empty( $engines_data[ $lang_code ] ) ) {
				$engines[ $lang_code ] = $engines_data[ $lang_code ];

			} else {
				$engines[ $lang_code ] = array(
					'lang_code'          => $lang_code,
					'status'             => 'A',
					'language_name'      => $this->get_store_name( $lang_code ),
					'url'                => $this->get_frontend_url( $lang_code ),
					'api_key'            => $this->get_api_key( $lang_code ),
					'private_key'        => $this->get_private_key( $lang_code ),
					'parent_private_key' => $this->get_parent_private_key(),
					'export_status'      => $this->get_export_status( $lang_code ),
				);
				$engines_data[ $lang_code ] = $engines[ $lang_code ];
			}
		}

		/**
		 * Get active engines
		 *
		 * @since 1.0.0
		 *
		 * @param array $engines
		 * @param string $lang_code
		 */
		return (array) apply_filters( 'se_get_engines', $engines, $lang_code );
	}

	/**
	 * Parse searchanise response
	 *
	 * @param string $response          Response.
	 * @param bool   $show_notification If true, error notification will be shown.
	 * @throws Searchanise_Exception     Raise if $show_notification is false.
	 */
	public function parse_response( $response, $show_notification = false ) {
		$data = json_decode( $response, true );

		if ( empty( $data ) ) {
			return false;
		}

		if ( ! empty( $data['errors'] ) && is_array( $data['errors'] ) ) {
			if ( true == $show_notification ) {
				foreach ( $data['errors'] as $e ) {
					$this->add_admin_notitice( (string) $e, 'error' );
				}
			} else {
				throw new Searchanise_Exception( implode( ',', array_map( 'wp_kses', $data['errors'] ) ) );
			}

			return false;
		} elseif ( 'ok' === $data ) {
			return true;
		} else {
			return $data;
		}
	}

	/**
	 * Test connect to Searchanise
	 *
	 * @param int $timeout Wait timeout.
	 *
	 * @return boolean
	 */
	public function test_connect( $timeout = 5 ) {
		$passed = true;

		$result = wp_remote_get(
			SE_SERVICE_URL . '/api/test',
			array(
				'timeout' => $timeout,
			)
		);

		if ( ! is_wp_error( $result ) ) {
			$response = wp_remote_retrieve_body( $result );
			$passed = 'OK' == $response;
		} else {
			$passed = false;
		}

		return $passed;
	}

	/**
	 * Send api request
	 *
	 * @param string  $url Api url.
	 * @param string  $private_key Engine private key.
	 * @param array   $data Data for send.
	 * @param boolean $only_http Using http or https.
	 *
	 * @return string Response data
	 */
	public function send_request( $url, $private_key, $data = array(), $only_http = true ) {
		$response = false;
		$params = array( 'private_key' => $private_key ) + $data;

		Logger::get_instance()->debug(
			array_merge(
				array(
					'url'      => SE_SERVICE_URL . $url,
					'timeout'  => SE_REQUEST_TIMEOUT,
					'method'   => 'post',
					'onlyHttp' => $only_http,
				),
				$params
			)
		);

		if ( ! empty( $params['private_key'] ) ) {
			$result = wp_remote_post(
				SE_SERVICE_URL . $url,
				array(
					'timeout' => SE_REQUEST_TIMEOUT,
					'headers' => array(
						'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
					),
					'body'    => $params,
				)
			);

			if ( ! is_wp_error( $result ) ) {
				$response_body = wp_remote_retrieve_body( $result );

				if ( ! empty( $response_body ) ) {
					try {
						$response = $this->parse_response( $response_body );
					} catch ( Searchanise_Exception $e ) {
						Logger::get_instance()->error(
							array(
								'error_message' => $e->getMessage(),
							)
						);
					}
				}

				Logger::get_instance()->debug(
					array(
						'response_body' => $response_body,
					)
				);

			} else {
				/* translators: error message */
				$message = sprintf( __( 'Error occurs during http request: %s' ), $result->get_error_message() );
				$this->add_admin_notitice( $message );
				Logger::get_instance()->error(
					array(
						'error_message' => $result->get_error_message(),
					)
				);
			}

			$this->set_last_request( time() );

		} else {
			Logger::get_instance()->debug(
				array(
					'error_message' => __( 'Empty private key', 'woocommerce-searchanise' ),
				)
			);
		}

		return $response;
	}

	/**
	 * Send addon status to Searchanise
	 *
	 * @param string $status Addon status.
	 * @param string $lang_code Lang Code.
	 *
	 * @return boolean
	 */
	public function addon_status_request( $status, $lang_code ) {
		$private_key = $this->get_private_key( $lang_code );

		if ( ! empty( $private_key ) ) {
			$result = wp_remote_post(
				SE_SERVICE_URL . '/api/state/update/json',
				array(
					'timeout' => SE_REQUEST_TIMEOUT,
					'headers' => array(
						'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
					),
					'body' => array(
						'private_key'  => $private_key,
						'addon_status' => $status,
					),
				)
			);

			return ! is_wp_error( $result );
		}

		return false;
	}

	/**
	 * Signup
	 *
	 * @param string  $lang_code         Lang code for signup.
	 * @param boolean $show_notifications If true notifications will be shown.
	 * @param boolean $fl_send_request   If true, addon status will be sent to Searchanise.
	 *
	 * @return string Signup status
	 */
	public function signup( $lang_code = null, $show_notifications = true, $fl_send_request = true ) {
		if ( php_sapi_name() == 'cli' && ! defined( 'PHPUNIT_SEARCHANISE' ) && ! defined( 'WP_CLI' ) ) {
			return false;
		}

		@ignore_user_abort( true );
		@set_time_limit( 3600 );

		if ( $this->check_auto_install() ) {
			$this->set_auto_install( true );
		}

		$connected = false;
		$current_user = wp_get_current_user();

		if ( ! empty( $current_user ) ) {
			if ( ! defined( 'PHPUNIT_SEARCHANISE' ) && ! defined( 'WP_CLI' ) ) {
				$email = $current_user->user_email;
			} else {
				$email = 'admin@example.com'; // email for unit tests.
			}
		}

		if ( ! empty( $email ) ) {
			$engines_data       = $this->get_engines( $lang_code, false );
			$parent_private_key = $this->get_parent_private_key();

			foreach ( $engines_data as $engine_data ) {
				$lang_code   = $engine_data['lang_code'];
				$private_key = $engine_data['private_key'];

				if ( ! empty( $private_key ) ) {
					if ( $fl_send_request ) {
						$this->addon_status_request( self::ADDON_STATUS_ENABLED, $lang_code );
					}

					continue;
				}

				if ( true == $show_notifications ) {
					$this->echo_progress( 'Connecting to Searchanise..' );
				}

				$request = wp_remote_post(
					SE_SERVICE_URL . '/api/signup/json',
					array(
						'timeout' => SE_REQUEST_TIMEOUT,
						'headers' => array(
							'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
						),
						'body' => array(
							'url'                => $engine_data['url'],
							'email'              => $email,
							'language'           => $lang_code,
							'parent_private_key' => $parent_private_key,
							'version'            => SE_PLUGIN_VERSION,
							'platform'           => SE_PLATFORM,
							'woocommerce_version' => $this->get_woocommerce_plugin_version(),
						),
					)
				);

				if ( ! is_wp_error( $request ) ) {
					$response = wp_remote_retrieve_body( $request );
				}

				if ( true == $show_notifications ) {
					$this->echo_progress( '.' );
				}

				if ( ! empty( $response ) ) {
					$response = $this->parse_response( $response, $show_notifications );

					if ( ! empty( $response['keys']['api'] ) && ! empty( $response['keys']['private'] ) ) {
						$api_key = (string) $response['keys']['api'];
						$private_key = (string) $response['keys']['private'];

						if ( empty( $api_key ) || empty( $private_key ) ) {
							return false;
						}

						if ( empty( $parent_private_key ) ) {
							$this->set_parent_private_key( $private_key );
							$parent_private_key = $private_key;
						}

						$this->set_api_key( $api_key, $lang_code );
						$this->set_private_key( $private_key, $lang_code );

						$connected = true;
					}
				} else {
					if ( true == $show_notifications ) {
						$this->echo_progress( ' Error<br />' );
					}

					return false;
				}

				$this->set_export_status( self::EXPORT_STATUS_NONE, $lang_code );
			}
		} else {
			// Empty email.
			return false;
		}

		if ( $connected ) {
			if ( true == $show_notifications ) {
				$this->echo_progress( 'Done<br />' );
				$this->add_admin_notitice( __( 'Congratulations, you\'ve just connected to Searchanise' ), 'success' );
			}
		}

		return true;
	}

	/**
	 * Start full import
	 *
	 * @param string  $lang_code Lang code fro queue import. If null, all engines will be imported.
	 * @param boolean $show_notifications If true, notification will be shown.
	 *
	 * @return boolean
	 */
	public function queue_import( $lang_code = null, $show_notifications = true ) {
		if ( ! $this->check_parent_private_key() ) {
			return false;
		}

		$this->set_notification_async_completed( false );

		Queue::get_instance()
			->clear_actions( $lang_code )
			->add_action( Queue::PREPARE_FULL_IMPORT, Queue::NO_DATA, $lang_code );

		$engines = $this->get_engines( $lang_code, false );
		foreach ( $engines as $engine ) {
			$this->set_export_status( self::EXPORT_STATUS_QUEUED, $engine['lang_code'] );
		}

		$this->send_addon_version();
		$this->send_store_timezone();

		if ( $show_notifications ) {
			$this->add_admin_notitice( __( 'The product catalog is queued for syncing', 'woocommerce-searchanise' ), 'success' );
		}

		return true;
	}

	/**
	 * Check if import is completed and display a message
	 */
	public function show_notification_async_completed() {
		if ( ! $this->check_notificaton_async_completed() ) {
			$all_stores_done = true;

			$engines = $this->get_engines();

			foreach ( $engines as $engine ) {
				if ( ! $this->check_export_status( $engine['lang_code'] ) ) {
					$all_stores_done = false;
					break;
				}
			}

			if ( $all_stores_done ) {
				/* translators: admin url */
				$this->add_admin_notitice( sprintf( __( 'Catalog indexation is complete. Use <a href="%s">Admin Panel</a> for configuration.', 'woocommerce-searchanise' ), $this->get_admin_url( '' ) ), 'success' );
				$this->set_notification_async_completed( true );
			}
		}

		return true;
	}

	/**
	 * Adds admin notice to queue
	 *
	 * @param string $message Message text.
	 * @param string $type    Message type.
	 */
	public function add_admin_notitice( $message, $type = 'notice' ) {
		$admin_notices = $this->get_system_setting( 'admin_notices' );
		$admin_notices = is_array( $admin_notices ) ? $admin_notices : array();
		$admin_notices[] = compact( 'type', 'message' );
		$this->set_system_setting( 'admin_notices', $admin_notices );
	}

	/**
	 * Get all admin notices
	 *
	 * @param bool $clear If true, notices will be erased.
	 *
	 * @return array
	 */
	public function get_admin_notices( $clear = true ) {
		$admin_notices = $this->get_system_setting( 'admin_notices' );

		if ( true == $clear ) {
			$this->set_system_setting( 'admin_notices', array() );
		}

		return is_array( $admin_notices ) ? $admin_notices : array();
	}

	/**
	 * Check if search is allowed for Engine
	 *
	 * @param sting $lang_code Lang code.
	 *
	 * @return boolean
	 */
	public function is_search_allowed( $lang_code ) {
		return in_array(
			$this->get_export_status( $lang_code ),
			array(
				self::EXPORT_STATUS_QUEUED,
				self::EXPORT_STATUS_START,
				self::EXPORT_STATUS_PROCESSING,
				self::EXPORT_STATUS_SENT,
				self::EXPORT_STATUS_DONE,
			)
		);
	}

	/**
	 * Checks if async needed
	 *
	 * @param string $lang_code Lang code.
	 *
	 * @return boolean|string
	 */
	public function check_start_async( $lang_code = null ) {
		$ret = false;
		$q = Queue::get_instance()->get_next_queue( $lang_code );

		if ( ! empty( $q ) ) {
			if ( Queue::is_queue_running( $q ) ) {
				$ret = false;

			} elseif ( Queue::is_queue_has_error( $q ) ) {
				$status = $this->get_export_status( $q->lang_code );

				if ( self::EXPORT_STATUS_SYNC_ERROR != $status ) {
					$this->set_export_status( self::EXPORT_STATUS_SYNC_ERROR, $q->lang_code );
				}
			} else {
				$ret = true;
			}
		}

		return $ret;
	}

	 /**
	  * Returns currency rate
	  *
	  * @return float
	  */
	public function get_currency_rate() {
		$currency_rate = 1.0;

		/**
		 * Get currency rate
		 *
		 * @since 1.0.0
		 *
		 * @param int $currency_rate
		 */
		return apply_filters( 'se_get_currency_rate', $currency_rate );
	}

	/**
	 * Print_r function custom wrapper
	 */
	public function print_r() {
		$args = func_get_args();
		echo '<ol style="font-family: Courier; font-size: 12px; border: 1px solid #dedede; background-color: #efefef; float: left; padding-right: 20px;">';
		foreach ( $args as $v ) {
			echo '<li><pre>' . esc_html( print_r( $v, true ) ) . "\n" . '</pre></li>';
		}
		echo '</ol><div style="clear:left;"></div>';
	}

	/**
	 * Test if locale is default
	 *
	 * @param string $lang_code Lang code.
	 *
	 * @return bool
	 */
	public function is_default_locale( $lang_code ) {
		return $lang_code == $this->get_default_locale();
	}

	/**
	 * Returns default db locale
	 *
	 * @return string Locale or false
	 */
	public function get_default_locale() {
		$locale = false;

		if ( is_multisite() ) {
			if ( wp_installing() ) {
				$ms_locale = get_site_option( 'WPLANG' );
			} else {
				$ms_locale = get_option( 'WPLANG' );

				if ( false === $ms_locale ) {
					$ms_locale = get_site_option( 'WPLANG' );
				}
			}

			if ( false !== $ms_locale ) {
				$locale = $ms_locale;
			}
		} else {
			$db_locale = get_option( 'WPLANG' );

			if ( false !== $db_locale ) {
				$locale = $db_locale;
			}
		}

		if ( empty( $locale ) ) {
			$locale = 'en_US';
		}

		return $locale;
	}

	/**
	 * Returns current active locale
	 *
	 * @param string $lang_code Lang code.
	 *
	 * @return string
	 */
	public function get_locale( $lang_code = null ) {
		if ( 'default' == $lang_code ) {
			$lang_code = $this->get_default_locale();
		}

		if ( empty( $lang_code ) ) {
			$lang_code = get_locale();
		}

		return $lang_code;
	}

	/**
	 * Returns locale name for settings
	 *
	 * @param string $lang_code Lang code.
	 *
	 * @return string
	 */
	public function get_locale_settings( $lang_code = '' ) {
		return $this->is_default_locale( $lang_code ) ? 'default' : $lang_code;
	}

	/**
	 * Escape string for JavaScript
	 *
	 * @param string $str Input string.
	 * @return string
	 */
	public function escape_javascript( $str ) {
		$str = html_entity_decode( $str, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 );
		// remove carriage return.
		$str = str_replace( "\r", '', (string) $str );
		// escape all characters with ASCII code between 0 and 31.
		$str = addcslashes( $str, "\0..\37'\\" );
		// escape double quotes.
		$str = str_replace( '"', '\"', $str );
		// replace \n with double quotes.
		$str = str_replace( "\n", '\n', $str );

		return $str;
	}

	/**
	 * Sends addon version to Searchanise
	 *
	 * @return bool
	 */
	public function send_addon_version() {
		global $wp_version;

		$result = false;
		$parent_private_key = $this->get_parent_private_key();

		if ( ! empty( $parent_private_key ) ) {
			$addon_options = $this->get_addon_options();
			$result = $this->send_request(
				'/api/state/update/json',
				$parent_private_key,
				array(
					'addon_version'    => $addon_options['addon_version'],
					'platform_edition' => ! empty( $addon_options['woocommerce'] ) ? $addon_options['woocommerce']['Version'] : '',
					'platform_version' => $wp_version,
					'woocommerce_version' => $this->get_woocommerce_plugin_version(),
				),
				true
			);
		}

		return $result;
	}

	/**
	 * Sends site timezone to Searchanise
	 *
	 * @return bool
	 */
	public function send_store_timezone() {
		$result = false;
		$parent_private_key = $this->get_parent_private_key();
		$timezone = wp_timezone_string();

		if ( ! empty( $parent_private_key ) && ! empty( $timezone ) ) {
			$result = $this->send_request(
				'/api/state/update/json',
				$parent_private_key,
				array(
					'store_timezone' => $timezone,
				),
				true
			);
		}

		return $result;
	}

	/**
	 * Set recently view product
	 *
	 * @param int $product_id Product Id.
	 */
	public function set_recently_viewed_product_id( $product_id ) {
		$cookie_name = self::COOKIE_RECENTLY_VIEWED_NAME;
		if ( empty( $_COOKIE[ $cookie_name ] ) ) {
			$viewed_products = array();
		} else {
			$viewed_products = (array) explode( ',', sanitize_text_field( wp_unslash( $_COOKIE[ $cookie_name ] ) ) );
		}

		// add product_id to array.
		if ( ! in_array( $product_id, $viewed_products ) ) {
			array_unshift( $viewed_products, $product_id );
		} else {
			// if product_id in array move to first.
			unset( $viewed_products[ array_search( $product_id, $viewed_products ) ] );
			array_unshift( $viewed_products, $product_id );
		}

		// limit.
		if ( count( $viewed_products ) > self::COOKIE_RECENTLY_VIEWED_LIMIT ) {
			array_pop( $viewed_products );
		}

		// setcookie(wc).
		wc_setcookie( $cookie_name, join( ',', $viewed_products ), strtotime( '+ 180 day' ) );
	}

	/**
	 * Returns views product ids
	 *
	 * @return string
	 */
	public function get_recently_viewed_product_ids() {
		return isset( $_COOKIE[ self::COOKIE_RECENTLY_VIEWED_NAME ] ) ? sanitize_text_field( wp_unslash( $_COOKIE[ self::COOKIE_RECENTLY_VIEWED_NAME ] ) ) : '';
	}

	/**
	 * Get link for lang_code
	 *
	 * @param string $link Link.
	 * @param string $lang_code Lang code.
	 *
	 * @return string
	 */
	public function get_language_link( $link, $lang_code ) {
		/**
		 * Get link for lang_code
		 *
		 * @since 1.0.0
		 *
		 * @param string $link
		 * @param $lang_code
		 */
		return apply_filters( 'se_get_language_link', $link, $lang_code );
	}

	/**
	 * Get currently language
	 *
	 * @return string
	 */
	public function get_currently_language() {
		/**
		 * Get currently language
		 *
		 * @since 1.0.0
		 */
		$currently_language = apply_filters( 'se_get_current_language', false );

		return ! empty( $currently_language ) ? $currently_language : $this->get_locale();
	}

	/**
	 * Get all active language from the db
	 *
	 * @param array $active_languages Active languages.
	 *
	 * @return array $langs
	 */
	public function get_langs_for_uninstall( $active_languages ) {
		global $wpdb;

		$all_langs = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT
				DISTINCT lang_code
			FROM {$wpdb->prefix}wc_se_settings
			WHERE name = %s AND lang_code != %s",
				'export_status',
				'default'
			)
		);

		return array_merge( $active_languages, $all_langs );
	}

	/**
	 * Get the status in WooCommerce plugin.
	 *
	 * @return int|null The status of the WooCommerce plugin, or null if class WC_Helper doesn't exist.
	 */
	public function get_wc_status() {

		$plugin_version = $this->get_woocommerce_plugin_version();

		if ( empty( $plugin_version ) ) {
			return true;
		}

		if ( class_exists( '\WC_Helper_Options', false ) ) {
			$auth = \WC_Helper_Options::get( 'auth' );
			if ( empty( $auth ) ) {
				return true;
			}
		}

		if ( class_exists( '\WC_Helper' ) ) {
			$subscriptions = \WC_Helper::get_subscriptions();

			$status = false;

			foreach ( $subscriptions as $element ) {
				if ( 'smart-search-and-product-filter' === $element['zip_slug'] ) {
					$status = $element['sites_active'];
					break;
				}
			}

			return $status;
		}

		return false;
	}
}
