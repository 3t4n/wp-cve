<?php
/**
 * Searchanise Async
 *
 * @package Searchanise/Async
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

/**
 * Searchanise syncronisation class
 */
class Async {

	const COMPRESS_RATE = 5;

	// Attribute weights.
	const WEIGHT_SHORT_TITLE          = 100;
	const WEIGHT_SHORT_DESCRIPTION    = 40;
	const WEIGHT_DESCRIPTION          = 40;
	const WEIGHT_DESCRIPTION_GROUPED  = 30;

	const WEIGHT_CATEGORIES           = 60;
	const WEIGHT_TAGS                 = 60;

	const WEIGHT_META_TITLE           = 80;
	const WEIGHT_META_KEYWORDS        = 100;
	const WEIGHT_META_DESCRIPTION     = 40;
	const WEIGHT_META_FIELD           = 60;

	const WEIGHT_SELECT_ATTRIBUTES    = 60;
	const WEIGHT_TEXT_ATTRIBUTES      = 60;
	const WEIGHT_TEXT_AREA_ATTRIBUTES = 40;

	// Image sizes.
	const IMAGE_SIZE     = 300;
	const THUMBNAIL_SIZE = 70;

	// Async statuses.
	const STATUS_ASYNC_DISABLED    = 'disabled';
	const STATUS_ASYNC_PROCESSING  = 'processing';
	const STATUS_ASYNC_ERROR_LANG  = 'lang_error';
	const STATUS_ASYNC_OK          = 'OK';

	// Async request flags.
	const FL_SHOW_STATUS_ASYNC      = 'show_status';
	const FL_SHOW_STATUS_ASYNC_KEY  = 'Y';
	const FL_IGNORE_PROCESSING      = 'ignore_processing';
	const FL_IGNORE_PROCESSING_KEY  = 'Y';
	const FL_DISPLAY_ERRORS         = 'display_errors';
	const FL_DISPLAY_ERRORS_KEY     = 'Y';
	const FL_LANG_CODE              = 'lang_code';

	// Prefixes.
	const PRODUCT_META_FIELD_PREFIX = 'product_meta_field_';
	const CUSTOM_TAXONOMY_PREFIX    = 'custom_taxonomy_';

	const SEND_VARIATIONS             = false;
	const STRIP_POST_CONTENT          = true;  // set to false if you want to import full content of pages/posts including all html tags.
	const IMPORT_ALSO_BOUGHT_PRODUCTS = true;

	const LIMIT_WOOCOMMERCE_IMAGES = 10;

	/**
	 * Self instance
	 *
	 * @var Async
	 */
	private static $instance = null;

	/**
	 * Returns class instance
	 *
	 * @return Async
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Async initialization
	 */
	public static function init() {
		if ( Api::get_instance()->get_module_status() != 'Y' ) {
			// Do not run async if module is not installed.
			return;
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			add_action( 'wp_ajax_nopriv_se_async', array( __CLASS__, 'ajax_async' ) );
			add_action( 'wp_ajax_se_async', array( __CLASS__, 'ajax_async' ) );
			add_action( 'wp_ajax_nopriv_searchanise_rated', array( __CLASS__, 'rated' ) );
			add_action( 'wp_ajax_searchanise_rated', array( __CLASS__, 'rated' ) );

		} elseif (
			! defined( 'DOING_CRON' )
			&& (
				Api::get_instance()->check_ajax_async_enabled()
				|| Api::get_instance()->check_object_async_enabled()
			)
		) {
			if ( Api::get_instance()->check_start_async() ) {
				$async_url = admin_url( 'admin-ajax.php' );

				if ( Api::get_instance()->check_object_async_enabled() ) {
					if ( is_admin() ) {
						add_action(
							'admin_footer',
							function () use ( $async_url ) {
								self::add_async_objects( $async_url );
							},
							100
						);
					} else {
						add_action(
							'wp_footer',
							function () use ( $async_url ) {
								self::add_async_objects( $async_url );
							},
							100
						);
					}
				} elseif ( Api::get_instance()->check_ajax_async_enabled() ) {
					self::add_jquery_objects( $async_url );
				}
			}
		} //endif async.

		// Check export status.
		if ( ! defined( 'DOING_AJAX' ) && ! defined( 'DOING_CRON' ) ) {
			if ( is_admin() ) {
				Api::get_instance()->check_export_status_is_done();
			} else {
				Api::get_instance()->check_export_status_is_done( Api::get_instance()->get_locale() );
			}
		}
	}

	/**
	 * Adds jquery async requests
	 *
	 * @param string $async_url Async url.
	 */
	public static function add_jquery_objects( $async_url ) {
		wc_enqueue_js(
			"jQuery.ajax({
				method: 'get',
				url: '{$async_url}',
				data: {
					action: 'se_async'
				},
				async: true
			});"
		);
	}

	/**
	 * Adds object async requests
	 *
	 * @param string $async_url Async url.
	 */
	public static function add_async_objects( $async_url ) {
		$allowed_tags = array(
			'object' => array(
				'data' => array(),
				'width' => array(),
				'height' => array(),
				'type' => array(),
			),
		);
		echo wp_kses( "<object data=\"{$async_url}?action=se_async\" width=\"0\" height=\"0\" type=\"text/html\"></object>", $allowed_tags );
	}

	/**
	 * Process async queue
	 *
	 * @param string  $lang_code Lang code.
	 * @param boolean $fl_ignore_processing Ignore flag.
	 *
	 * @return string
	 * @throws Searchanise_Exception Searchanise execption.
	 */
	public function async( $lang_code = null, $fl_ignore_processing = false ) {
		global $wpdb;

		@ignore_user_abort( true );
		@set_time_limit( 0 );
		wp_raise_memory_limit( 'searchanise_async' );

		$locale_switched = false;

		if ( ! empty( $lang_code ) && Api::get_instance()->get_locale( $lang_code ) != $lang_code ) {
			if ( switch_to_locale( $lang_code ) == true ) {
				$locale_switched = true;
			} else {
				return self::STATUS_ASYNC_ERROR_LANG;
			}
		}

		Profiler::start_block( 'async' );
		Api::get_instance()->echo_progress( '.' );
		// Read first element from queue.
		$q = Queue::get_instance()->get_next_queue( null, $lang_code );

		while ( ! empty( $q ) ) {
			Logger::get_instance()->debug( $q );

			$data_for_send = array();
			$status        = false;
			$error         = '';

			$engines = Api::get_instance()->get_engines( $q->lang_code );
			$engine  = current( $engines );
			$header  = $this->get_header( $q->lang_code );

			$data = $q->data;
			if ( Queue::NO_DATA != $data ) {
				$data = json_decode( $data, true );
			}

			$private_key = $engine['private_key'];

			if ( empty( $private_key ) ) {
				Queue::get_instance()->delete_queue_by_id( $q->queue_id );
				$q = array();
				continue;
			}

			if ( Queue::is_queue_running( $q ) ) {
				if ( ! $fl_ignore_processing ) {
					return self::STATUS_ASYNC_PROCESSING;
				}
			}

			if ( Queue::is_queue_has_error( $q ) ) {
				Api::get_instance()->set_export_status( Api::EXPORT_STATUS_SYNC_ERROR, $engine['lang_code'] );
				return self::STATUS_ASYNC_DISABLED;
			}

			// Set queue to processing state.
			Queue::get_instance()->set_queue_status_processing( $q->queue_id );

			try {
				Profiler::start_block( $q->action . ':' . $q->queue_id );

				if ( Queue::PREPARE_FULL_IMPORT == $q->action ) {
					Queue::get_instance()->prepare_full_import( $engine['lang_code'] );

					Queue::get_instance()->insert_data(
						array(
							'data'      => Queue::NO_DATA,
							'action'    => Queue::START_FULL_IMPORT,
							'lang_code' => $engine['lang_code'],
						)
					);

					Queue::get_instance()->insert_data(
						array(
							'data'      => Queue::NO_DATA,
							'action'    => Queue::GET_INFO,
							'lang_code' => $engine['lang_code'],
						)
					);

					Queue::get_instance()->insert_data(
						array(
							'data'      => Queue::NO_DATA,
							'action'    => Queue::DELETE_FACETS_ALL,
							'lang_code' => $engine['lang_code'],
						)
					);

					$this->add_task_by_chunk( $engine['lang_code'], Queue::UPDATE_PRODUCTS, true );
					Queue::get_instance()->insert_data(
						array(
							'data'      => Queue::NO_DATA,
							'action'    => Queue::UPDATE_ATTRIBUTES,
							'lang_code' => $engine['lang_code'],
						)
					);

					$this->add_task_by_chunk( $engine['lang_code'], Queue::UPDATE_CATEGORIES, true );
					$this->add_task_by_chunk( $engine['lang_code'], Queue::UPDATE_PAGES, true );

					Queue::get_instance()->insert_data(
						array(
							'data'      => Queue::NO_DATA,
							'action'    => Queue::END_FULL_IMPORT,
							'lang_code' => $engine['lang_code'],
						)
					);

					$status = true;

				} elseif ( Queue::START_FULL_IMPORT == $q->action ) {
					$status = Api::get_instance()->send_request( '/api/state/update/json', $private_key, array( 'full_import' => Api::EXPORT_STATUS_START ), true );

					if ( true == $status ) {
						Api::get_instance()->set_export_status( Api::EXPORT_STATUS_PROCESSING, $engine['lang_code'] );
					}
				} elseif ( Queue::GET_INFO == $q->action ) {
					$info = Api::get_instance()->send_request( '/api/state/info/json', $private_key, array(), true );

					if ( ! empty( $info['result_widget_enabled'] ) ) {
						Api::get_instance()->set_result_widget_enabled( $info['result_widget_enabled'], $engine['lang_code'] );
					}

					if ( ! empty( $info['use_navigation'] ) ) {
						Api::get_instance()->set_navigation_enabled( $info['use_navigation'], $engine['lang_code'] );
					}

					if ( ! empty( $info['is_weglot_enabled'] ) ) {
						Api::get_instance()->set_integration_weglot_enabled( $info['is_weglot_enabled'], $engine['lang_code'] );
					}

					$status = true;

				} elseif ( Queue::END_FULL_IMPORT == $q->action ) {
					$export_done = Api::get_instance()->send_request( '/api/state/update/json', $private_key, array( 'full_import' => Api::EXPORT_STATUS_DONE ), true );

					if ( true == $export_done ) {
						Api::get_instance()->set_export_status( Api::EXPORT_STATUS_SENT, $engine['lang_code'] );
						Api::get_instance()->set_last_resync( $engine['lang_code'], time() );
					}

					// Update search results page.
					if ( Api::get_instance()->is_result_widget_enabled( $engine['lang_code'] ) ) {
						Installer::create_search_results_page( array(), true );
					} else {
						Installer::delete_search_results_page();
					}

					$status = true;

				} elseif ( Queue::is_delete_all_action( $q->action ) ) {
					$type = Queue::get_api_type_by_action( $q->action );

					if ( ! empty( $type ) ) {
						$status = Api::get_instance()->send_request( "/api/{$type}/delete/json", $private_key, array( 'all' => true ), true );
					}
				} elseif ( Queue::is_update_action( $q->action ) ) {
					$data_for_send = array();

					switch ( $q->action ) {
						case Queue::UPDATE_PRODUCTS:
							$data_for_send = $this->get_products_data( $data, $engine['lang_code'], true );
							break;

						case Queue::UPDATE_CATEGORIES:
							$data_for_send = $this->get_categories_data( $data, $engine['lang_code'] );
							break;

						case Queue::UPDATE_PAGES:
							$data_for_send = $this->get_pages_data( $data, $engine['lang_code'] );
							break;

						case Queue::UPDATE_ATTRIBUTES:
							$facets = array();
							$product_filters = $this->get_product_filters( $engine['lang_code'] );

							foreach ( $product_filters as $filter ) {
								$facets[] = $this->prepare_facet_data( $filter, $engine['lang_code'] );
							}

							if ( ! empty( $facets ) ) {
								$facets_data = array( 'schema' => $facets );
								$data_for_send = $this->get_translate( $facets_data, $engine['lang_code'] );
							} else {
								$status = true;
							}

							break;
					}

					if ( ! empty( $data_for_send ) ) {
						$data_for_send = wp_json_encode( array_merge( $header, $data_for_send ) );

						if ( function_exists( 'gzcompress' ) ) {
							$data_for_send = gzcompress( $data_for_send, self::COMPRESS_RATE );
						}

						$status = Api::get_instance()->send_request( '/api/items/update/json', $private_key, array( 'data' => $data_for_send ), true );
					}
				} elseif ( Queue::is_delete_action( $q->action ) ) {
					$type = Queue::get_api_type_by_action( $q->action );

					if ( ! empty( $type ) ) {
						foreach ( $data as $item_id ) {
							$data_for_send = array();

							if ( Queue::DELETE_FACETS == $q->action ) {
								$data_for_send['attribute'] = $item_id;
							} else {
								$data_for_send['id'] = $item_id;
							}

							$status = Api::get_instance()->send_request( "/api/{$type}/delete/json", $private_key, $data_for_send, true );
							Api::get_instance()->echo_progress( '.' );

							if ( false == $status ) {
								break;
							}
						}
					}
				} elseif ( Queue::PHRASE == $q->action ) {
					foreach ( $data as $phrase ) {
						$status = Api::get_instance()->send_request( '/api/phrases/update/json', $private_key, array( 'phrase' => $phrase ), true );
						Api::get_instance()->echo_progress( '.' );

						if ( false == $status ) {
							break;
						}
					}
				} else {
					// Unknown action name.
					throw new Searchanise_Exception( __( 'Unknown queue action', 'woocommerce-searchanise' ) );
				} // End if

				// Check for database errors.
				if ( '' != $wpdb->last_error ) {
					throw new Searchanise_Exception( __( 'SQL Error', 'woocommerce-searchanise' ) . ' ' . $wpdb->last_error . '. Query: ' . $wpdb->last_query );
				}

				Profiler::end_block( $q->action . ':' . $q->queue_id );

			} catch ( Searchanise_Exception $e ) {
				Profiler::end_block( $q->action . ':' . $q->queue_id );
				$status = false;
				$error = $e->getMessage();
				Logger::get_instance()->error(
					array(
						'q'     => $q,
						'error' => $error,
					)
				);
			}

			Logger::get_instance()->debug( array( 'status' => $status ) );

			if ( true == $status ) {
				Queue::get_instance()->delete_queue_by_id( $q->queue_id );
				$q = Queue::get_instance()->get_next_queue( $q->queue_id, $lang_code );

			} else {
				$next_started_time = time() - Api::get_instance()->get_max_processing_time() + $q->attempts * 60;
				Queue::get_instance()->set_queue_error_by_id( $q->queue_id, $next_started_time, $error );

				// Try again later.
				break;
			}
		} // End while.

		Api::get_instance()->echo_progress( '.' );
		Profiler::end_block( 'async' );

		// Restore locale if it was switched.
		if ( true == $locale_switched ) {
			restore_previous_locale();
		}

		$info = Profiler::get_blocks_info();
		Logger::get_instance()->debug( $info );

		return self::STATUS_ASYNC_OK;
	}

	/**
	 * Generate request header
	 *
	 * @param string $lang_code Lang code.
	 *
	 * @return array
	 */
	public function get_header( $lang_code ) {
		$shop_url = get_permalink( wc_get_page_id( 'shop' ) );

		if ( empty( $shop_url ) ) {
			$shop_url = home_url( '/shop' );
		}

		$header = array(
			'header' => array(
				'id'      => Api::get_instance()->get_language_link( $shop_url, $lang_code ),
				'updated' => gmdate( 'c' ),
			),
		);

		/**
		 * Filters header data for Searchanise
		 *
		 * @since 1.0.0
		 *
		 * @param array $header      Header data
		 * @param string $lang_code  Lang code
		 */
		return (array) apply_filters( 'se_get_header', $header, $lang_code );
	}

	/**
	 * Adds task to queue
	 *
	 * @param string  $lang_code      Lang code.
	 * @param string  $action         Action.
	 * @param boolean $is_only_active Process only active.
	 *
	 * @return boolean
	 */
	private function add_task_by_chunk( $lang_code, $action, $is_only_active = true ) {
		$i = 0;
		$step = 50;
		$start = 0;
		$max = 0;

		switch ( $action ) {
			case Queue::UPDATE_PRODUCTS:
				$step = Api::get_instance()->get_products_per_pass() * 50;
				list($start, $max) = $this->get_min_max_product_id( $is_only_active, $lang_code );
				break;

			case Queue::UPDATE_CATEGORIES:
				$step = Api::get_instance()->get_categories_per_pass() * 50;
				list($start, $max) = $this->get_min_max_category_id( $lang_code );
				break;

			case Queue::UPDATE_PAGES:
				$step = Api::get_instance()->get_pages_per_pass() * 50;
				list($start, $max) = $this->get_min_max_page_id( $lang_code );
				break;

			default:
				return false;
		}

		do {
			$chunk_item_id = null;

			switch ( $action ) {
				case Queue::UPDATE_PRODUCTS:
					$chunk_item_id = $this->get_products_ids_from_range( $start, $max, $step, $lang_code, $is_only_active );
					break;

				case Queue::UPDATE_CATEGORIES:
					$chunk_item_id = $this->get_categories_ids_from_range( $start, $max, $step, $lang_code );
					break;

				case Queue::UPDATE_PAGES:
					$chunk_item_id = $this->get_pages_ids_from_range( $start, $max, $step, $lang_code );
					break;
			}

			if ( empty( $chunk_item_id ) ) {
				break;
			}

			$end = max( $chunk_item_id );
			$start = $end + 1;

			$chunk_item_id = array_chunk( $chunk_item_id, Api::get_instance()->get_products_per_pass() );

			foreach ( $chunk_item_id as $item_ids ) {
				$queue_data = array(
					'data'      => wp_json_encode( $item_ids ),
					'action'    => $action,
					'lang_code' => $lang_code,
				);

				Queue::get_instance()->insert_data( $queue_data );
				unset( $queue_data ); // For memory safe.
			}
		} while ( $end <= $max );

		return true;
	}

	/**
	 * Returns min and max product ids
	 *
	 * @param boolean $is_only_active Process only active.
	 * @param string  $lang_code    Lang code.
	 *
	 * @return array
	 */
	public function get_min_max_product_id( $is_only_active = true, $lang_code = '' ) {
		global $wpdb;

		$min_max = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT
				MIN(ID) AS min,
				MAX(ID) AS max
			FROM $wpdb->posts
			WHERE (post_status IN ('publish') OR %d)
			AND post_type = %s",
				$is_only_active ? 0 : 1,
				'product'
			),
			ARRAY_A
		);

		return array( (int) $min_max['min'], (int) $min_max['max'] );
	}

	/**
	 * Calculates products count for import
	 *
	 * @param bool   $is_only_active Process only active.
	 * @param string $lang_code    Lang code.
	 *
	 * @return int
	 */
	public function get_products_count( $is_only_active, $lang_code = '' ) {
		global $wpdb;

		$count = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT
					COUNT(ID)
				FROM $wpdb->posts
				WHERE (post_status IN ('publish') OR %d)
				AND post_type = %s",
				$is_only_active ? 0 : 1,
				'product'
			)
		);

		return $count;
	}

	/**
	 * Returns min and max category ids
	 *
	 * @param string $lang_code Lang code.
	 *
	 * @return array
	 */
	private function get_min_max_category_id( $lang_code ) {
		global $wpdb;

		$min_max = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT
					MIN(t.term_id) AS min,
					MAX(t.term_id) AS max
				FROM $wpdb->terms as t
				INNER JOIN $wpdb->term_taxonomy AS tt ON tt.term_id = t.term_id AND taxonomy = %s",
				'product_cat'
			),
			ARRAY_A
		);

		return array( (int) $min_max['min'], (int) $min_max['max'] );
	}

	/**
	 * Returns min and max page ids
	 *
	 * @param string $lang_code Lang code.
	 *
	 * @return array
	 */
	public function get_min_max_page_id( $lang_code ) {
		global $wpdb;

		$min_max = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT
				MIN(ID) AS min,
				MAX(ID) AS max
			FROM $wpdb->posts
			WHERE post_status = %s
			AND post_type IN ('" . implode( "', '", esc_sql( self::get_post_types() ) ) . "')",
				'publish'
			),
			ARRAY_A
		);

		return array( (int) $min_max['min'], (int) $min_max['max'] );
	}

	/**
	 * Return valid product ids from range
	 *
	 * @param int     $start          Start id.
	 * @param int     $end            End   id.
	 * @param int     $step           Step to process.
	 * @param string  $lang_code      Lang code.
	 * @param boolean $is_only_active Process only active.
	 *
	 * @return array
	 */
	public function get_products_ids_from_range( $start, $end, $step, $lang_code, $is_only_active ) {
		global $wpdb;

		$statuses = array( 'draft', 'pending', 'private', 'publish' );

		$ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT
				ID
			FROM $wpdb->posts
			WHERE
				post_status IN ('" . implode( "', '", esc_sql( $statuses ) ) . "' OR %d)
				AND ID >= %s
				AND ID <= %s
				AND post_type = %s
				ORDER BY ID
				LIMIT %d",
				$is_only_active ? 0 : 1,
				$start,
				$end,
				'product',
				$step
			)
		);

		/**
		 * Filters product_ids from given range
		 *
		 * @since 1.0.0
		 *
		 * @param array $ids        Product ids
		 * @param int $start        Start product id
		 * @param int $end          End product id
		 * @param int $step         Maximum products count
		 * @param string $lang_code Lang code
		 */
		return (array) apply_filters( 'se_get_products_ids_from_range', $ids, $start, $end, $step, $lang_code, $is_only_active );
	}

	/**
	 * Return valid category ids from range
	 *
	 * @param int    $start     Start id.
	 * @param int    $end       End id.
	 * @param int    $step      Step.
	 * @param string $lang_code Lang code.
	 *
	 * @return array
	 */
	private function get_categories_ids_from_range( $start, $end, $step, $lang_code ) {
		global $wpdb;

		$ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT
					t.term_id
				FROM $wpdb->terms as t
				INNER JOIN $wpdb->term_taxonomy AS tt ON tt.term_id = t.term_id AND taxonomy = %s
				WHERE
					t.term_id >= %s
					AND t.term_id <= %s
				ORDER BY t.term_id
				LIMIT %d",
				array( 'product_cat', $start, $end, $step )
			)
		);

		/**
		 * Filters category_ids from given range
		 *
		 * @since 1.0.0
		 *
		 * @param array $ids        Category ids
		 * @param int $start        Start category id
		 * @param int $end          End category id
		 * @param int $step         Maximum categories count
		 * @param string $lang_code Lang code
		 */
		return (array) apply_filters( 'se_get_categories_ids_from_range', $ids, $start, $end, $step, $lang_code );
	}

	/**
	 * Return valid page ids from range
	 *
	 * @param int    $start     Start id.
	 * @param int    $end       End id.
	 * @param int    $step      Step between ids.
	 * @param string $lang_code Lang code.
	 *
	 * @return array
	 */
	private function get_pages_ids_from_range( $start, $end, $step, $lang_code ) {
		global $wpdb;

		$ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT
				ID
			FROM $wpdb->posts
			WHERE
				ID >= %s
				AND ID <= %s
				AND post_status = %s
				AND post_type IN ('" . implode( "', '", esc_sql( self::get_post_types() ) ) . "')
				ORDER BY ID
				LIMIT %d",
				array( $start, $end, 'publish', $step )
			)
		);

		/**
		 * Filters page_ids from given range
		 *
		 * @since 1.0.0
		 *
		 * @param array $ids        Page ids
		 * @param int $start        Start page id
		 * @param int $end          End page id
		 * @param int $step         Maximum pages count
		 * @param string $lang_code Lang code
		 */
		return (array) apply_filters( 'se_get_pages_ids_from_range', $ids, $start, $end, $step, $lang_code );
	}

	/**
	 * Get products filters
	 *
	 * @param string $lang_code Lang code.
	 *
	 * @return array
	 */
	public function get_product_filters( $lang_code ) {
		$filters = array();

		$filters[] = array(
			'name'     => 'price',
			'label'    => __( 'Price', 'woocommerce' ),
			'type'     => 'slider',
			'position' => 5,
		);

		$filters[] = array(
			'name'     => 'stock_status',
			'label'    => __( 'Stock status', 'woocommerce' ),
			'type'     => 'select',
			'position' => 10,
		);

		if ( Api::get_instance()->is_result_widget_enabled( $lang_code ) ) {
			$filters[] = array(
				'name'        => 'categories',
				'label'       => __( 'Categories', 'woocommerce' ),
				'type'        => 'select',
				'text_search' => 'Y',
				'weight'      => self::WEIGHT_CATEGORIES,
				'position'    => 15,
			);
			$filters[] = array(
				'name'        => 'category_ids',
				'label'       => __( 'Categories', 'woocommerce' ) . ' - IDs',
				'weight'      => 0,
				'text_search' => 'N',
				'facet'       => 'N',
			);
		} else {
			$filters[] = array(
				'name'        => 'category_ids',
				'label'       => __( 'Categories', 'woocommerce' ) . ' - IDs',
				'type'        => 'select',
				'text_search' => 'N',
				'weight'      => 0,
				'position'    => 15,
			);
			$filters[] = array(
				'name'        => 'categories',
				'label'       => __( 'Categories', 'woocommerce' ),
				'text_search' => 'N',
				'weight'      => 0,
				'facet'       => 'N',
			);
		}

		$filters[] = array(
			'name'     => 'tags',
			'label'    => __( 'Product tags', 'woocommerce' ),
			'type'     => 'select',
			'position' => 20,
		);

		$filters = array_merge( $filters, $this->get_attribute_filters( $lang_code, 25 ) );

		/**
		 * Filters available product filters
		 *
		 * @since 1.0.0
		 *
		 * @param array $filters     Product filters
		 * @param string $lang_code  Lang code
		 */
		return (array) apply_filters( 'se_get_get_product_filters', $filters, $lang_code );
	}

	/**
	 * Generate product attribute filters
	 *
	 * @param string $lang_code Lang code.
	 * @param int    $position  Start filter position.
	 *
	 * @return array
	 */
	public function get_attribute_filters( $lang_code, $position = 30 ) {
		$filters = array();
		$attributes = wc_get_attribute_taxonomies();

		foreach ( $attributes as $attr ) {
			$filters[] = self::generate_filter_from_attribute( $attr, $lang_code, 'select', $position );
			$position += 5;
		}

		$system_custom_attributtes = Api::get_instance()->get_custom_attributes();
		$custom_attributes = $this->check_attributtes( $system_custom_attributtes );

		if ( ! empty( $custom_attributes ) ) {
			foreach ( $custom_attributes as $custom ) {
				$taxonomy = get_taxonomy( $custom );
				$filters[] = self::generate_filter_from_attribute( $taxonomy, $lang_code, 'select', $position );
				$position += 5;
			}
		}

		return $filters;
	}

	/**
	 * Generate Searchanise filter data from WC attribute
	 *
	 * @param object $attr  WC attribute data.
	 * @param string $lang_code Lang code.
	 * @param string $type  Facet type.
	 * @param int    $position Facet position.
	 *
	 * @return array
	 */
	public static function generate_filter_from_attribute( $attr, $lang_code, $type = 'select', $position = null ) {
		$filter = array(
			'name'        => self::get_taxonomy_id( isset( $attr->name ) ? $attr->name : $attr->attribute_name ),
			'label'       => isset( $attr->label ) ? $attr->label : $attr->attribute_label,
			'text_search' => 'Y',
			'type'        => $type,
		);

		if ( null !== $position ) {
			$filter['position'] = $position;
		}

		/**
		 * Filters generated facet data
		 *
		 * @since 1.0.0
		 *
		 * @param array $filter           Facet data.
		 * @param object $attr            Original taxonomy attribute.
		 * @param $lang_code              Lang code.
		 */
		return (array) apply_filters( 'se_generate_filter_from_attribute', $filter, $attr, $lang_code );
	}

	/**
	 * Return all product tags
	 *
	 * @param string $lang_code Lang code.
	 *
	 * @return array
	 */
	public function get_product_tags( $lang_code ) {
		$product_tags = array();
		$_product_tags = get_terms(
			array(
				'taxonomy'   => 'product_tag',
				'hide_empty' => false,
			)
		);

		foreach ( $_product_tags as $tag_term ) {
			$product_tags[] = $tag_term instanceof \WP_Term ? array(
				'name' => $tag_term->slug,
				'label' => $tag_term->name,
			) : $tag_term;
		}

		/**
		 * Filters product tags
		 *
		 * @since 1.0.0
		 *
		 * @param array $product_tags Product tags
		 * @param string $lang_code   Lang code
		 */
		return (array) apply_filters( 'se_get_product_tags', $product_tags, $lang_code );
	}

	/**
	 * Prepare facet data from filter
	 *
	 * @param array  $filter    Filter data.
	 * @param string $lang_code Lang code.
	 *
	 * @return array
	 */
	public function prepare_facet_data( $filter, $lang_code ) {
		$entry = array();
		static $color_attributes = array();
		static $size_attributes = array();

		if ( empty( $color_attributes ) ) {
			$color_attributes = Api::get_instance()->get_color_attributes();
		}

		if ( empty( $size_attributes ) ) {
			$size_attributes = Api::get_instance()->get_size_attributes();
		}

		if ( ! empty( $filter['name'] ) ) {
			$entry['name'] = $filter['name'];

			if ( isset( $filter['text_search'] ) ) {
				$entry['text_search'] = $filter['text_search'];
			}

			if ( isset( $filter['weight'] ) ) {
				$entry['weight'] = $filter['weight'];
			}

			if ( ! isset( $filter['facet'] ) || 'N' != $filter['facet'] ) {
				$entry['facet']['title']      = isset( $filter['label'] ) ? $filter['label'] : $filter['name'];
				$entry['facet']['position']   = isset( $filter['position'] ) ? $filter['position'] : '';
				$entry['facet']['type']       = isset( $filter['type'] ) ? $filter['type'] : 'select';
				$entry['facet']['appearance'] = 'default';

				if ( Api::get_instance()->is_result_widget_enabled( $lang_code ) ) {
					if ( in_array( strtolower( $entry['name'] ), $color_attributes ) ) {
						$entry['facet']['appearance'] = 'color';
					}

					if ( in_array( strtolower( $entry['name'] ), $size_attributes ) ) {
						$entry['facet']['appearance'] = 'size';
					}
				}
			} else {
				$entry['facet'] = 'N';
			}
		}

		return $entry;
	}

	/**
	 * Return product ids excluded from indexation
	 *
	 * @return array
	 */
	public function get_excluded_product_ids() {
		static $excluded_product_ids = array();

		$excluded_tags = Api::get_instance()->get_system_setting( 'excluded_tags' );
		if ( ! empty( $excluded_tags ) && empty( $excluded_product_ids ) ) {
			if ( ! is_array( $excluded_tags ) ) {
				$excluded_tags = explode( ',', $excluded_tags );
			}

			$excluded_product_ids = wc_get_products(
				array(
					'tag'     => $excluded_tags,
					'limit'   => -1,
					'for_searchanise' => true,
					'return' => 'ids',
				)
			);
		}

		return $excluded_product_ids;
	}

	/**
	 * Returns related product ids
	 *
	 * @param WC_Product $product Product data.
	 * @param int        $limit          Maximum related products.
	 *
	 * @return array
	 */
	public function get_related_product_ids( $product, $limit = 100 ) {
		return wc_get_related_products( $product->get_id(), $limit, $this->get_excluded_product_ids() );
	}

	/**
	 * Get products data for Se request
	 *
	 * @param array   $product_ids Product Ids.
	 * @param string  $lang_code   Lang code.
	 * @param boolean $lf_echo     Output if true.
	 */
	public function get_products_data( $product_ids, $lang_code, $lf_echo = true ) {
		$products = array();
		$schema = array();
		$items = array();

		$product_ids = array_diff( (array) $product_ids, $this->get_excluded_product_ids() );

		if ( ! empty( $product_ids ) ) {
			$products = wc_get_products(
				array(
					'include' => $product_ids,
					'limit'   => -1,
					'for_searchanise' => true,
				)
			);
		}

		if ( $lf_echo ) {
			Api::get_instance()->echo_progress( '.' );
		}

		if ( ! empty( $products ) ) {
			$this->get_products_additional( $products );

			foreach ( $products as $product ) {
				$item = array();
				$data = $this->prepare_product_data( $product, $lang_code );

				if ( empty( $data ) ) {
					continue;
				}

				foreach ( $data as $name => $d ) {
					if ( ! empty( $d['name'] ) ) {
						$name = $d['name'];
					} else {
						$d['name'] = $name;
					}

					if ( isset( $d['value'] ) ) {
						$item[ $name ] = $d['value'];
						unset( $d['value'] );
					}

					if ( ! empty( $d ) ) {
						$schema[ $name ] = $d;
					}
				}
				$items[] = $item;
			}
		}

		$products_data = array(
			'schema' => $schema,
			'items'  => $items,
		);

		return $this->get_translate( $products_data, $lang_code );
	}

	/**
	 * Returns sortable attributes
	 *
	 * @return array
	 */
	public function get_sortable_attributes() {
		$sortable_attributes = array(
			'title',
			'sales_amount',
			'created',
			'modified',
			'price',
			'menu_order',
			'stock_status',
		);

		/**
		 * Filters sortable attributes
		 *
		 * @since 1.0.0
		 *
		 * @param array $sortable_attributes Sortable attributes list
		 */
		return (array) apply_filters( 'se_get_sortable_attributes', $sortable_attributes );
	}

	/**
	 * Get product image url
	 *
	 * @param int $image_id Image identifier.
	 * @param int $size     Image size.
	 *
	 * @return string
	 */
	private function get_product_image( $image_id, $size ) {
		$image_url = '';

		/**
		 * Pre filter product image data
		 *
		 * @since 1.0.0
		 *
		 * @param string   $image_url Product image url
		 * @param int|null $image_id  Attachment ID
		 * @param int      $size      Image size
		 */
		$image_url = apply_filters( 'se_get_product_image_pre', $image_url, $image_id, $size );

		if ( empty( $image_url ) && ! empty( $image_id ) && ! empty( $size ) ) {
			if ( Api::get_instance()->use_direct_image_links() ) {
				$image = wc_get_product_attachment_props( $image_id );

				if ( ! empty( $image['url'] ) ) {
					$image_url = $image['url'];
				}
			} else {
				$image_src = wp_get_attachment_image_src( $image_id, array( $size, $size ), true );

				if ( ! empty( $image_src ) ) {
					$image_url = $image_src[0];
				}
			}
		}

		/**
		 * Post filter product image data
		 *
		 * @since 1.0.0
		 *
		 * @param string   $image_url Product image url.
		 * @param int|null $image_id  Attachment ID.
		 * @param int      $size      Image size.
		 */
		return apply_filters( 'se_get_product_image_post', $image_url, $image_id, $size );
	}

	/**
	 * Trim and remove empty values from list
	 *
	 * @param array $values Values.
	 *
	 * @return array
	 */
	private function filter_grouped_values( array $values ) {
		return array_unique(
			array_filter(
				array_map( 'trim', $values ),
				function ( $v ) {
					return ! empty( $v );
				}
			)
		);
	}

	/**
	 * Returns available usergroups
	 *
	 * @return array
	 */
	public function get_user_groups() {
		static $user_groups = array();

		if ( empty( $user_groups ) ) {
			$user_groups = array_keys( wp_roles()->roles );
		}

		/**
		 * Filters available usergroups
		 *
		 * @since 1.0.0
		 *
		 * @param array $user_groups User groups
		 */
		return (array) apply_filters( 'se_get_usergroups', $user_groups );
	}

	/**
	 * Checks if usergroup prices functionality is available
	 *
	 * @return boolean
	 */
	public function is_usergroup_prices_available() {
		$is_usergroup_prices_available = false;

		/**
		 * Filters usergroup price availability for Searchanise
		 *
		 * @since 1.0.0
		 *
		 * @param bool $is_usergroup_prices_available Usergroup price availability
		 */
		return (bool) apply_filters( 'se_is_usergroup_prices_available', $is_usergroup_prices_available );
	}

	/**
	 * Generate product prices for usergroups
	 *
	 * @param array      $entry             Product entry.
	 * @param WC_Product $product_data      WC product.
	 * @param string     $lang_code         Lang code.
	 * @param array      $children_products Children products.
	 *
	 * @return boolean
	 */
	public function generate_usergroup_product_prices( &$entry, $product_data, $lang_code, $children_products = array() ) {
		if ( empty( $product_data ) ) {
			return false;
		}

		// Clean up user roles.
		$current_user        = wp_get_current_user();
		$curent_user_roles   = $current_user->roles;
		$current_user->roles = array();

		// General common prices.
		$prices = $this->generate_product_prices( $product_data, $children_products, $lang_code );
		$entry['price'] = array(
			'value' => (float) $prices['price'],
			'title' => __( 'Price', 'woocommerce' ),
			'type'  => 'float',
		);
		$entry['list_price'] = array(
			'value' => (float) $prices['regular_price'],
			'title' => __( 'Regular price', 'woocommerce' ),
			'type'  => 'float',
		);
		$entry['sale_price'] = array(
			'value' => (float) $prices['sale_price'],
			'title' => __( 'Sale price', 'woocommerce' ),
			'type'  => 'float',
		);
		$entry['max_price'] = array(
			'value' => (float) $prices['max_price'],
			'title' => __( 'Max price', 'woocommerce' ),
			'type'  => 'float',
		);

		if ( isset( $prices['max_discount'] ) ) {
			$entry['discount'] = array(
				'value' => (int) round( $prices['max_discount'] ),
				'title' => __( 'Discount', 'woocommerce' ),
				'type'  => 'int',
			);
		}

		// Generate usergroup prices if plugin is active.
		if ( $this->is_usergroup_prices_available() ) {
			foreach ( $this->get_user_groups() as $role ) {
				// Set user role and generate price for it.
				$current_user->roles = array( $role );
				$prices = $this->generate_product_prices( $product_data, $children_products, $lang_code );

				$entry[ Api::LABEL_FOR_PRICES_USERGROUP . $role ] = array(
					'value' => (float) $prices['price'],
					'title' => __( 'Price for ', 'woocommerce-searchanise' ) . $role,
					'type'  => 'float',
				);

				$entry[ Api::LABEL_FOR_MAX_PRICES_USERGROUP . $role ] = array(
					'value' => (float) $prices['max_price'],
					'title' => __( 'Max price for ', 'woocommerce-searchanise' ) . $role,
					'type'  => 'float',
				);

				$entry[ Api::LABEL_FOR_LIST_PRICES_USERGROUP . $role ] = array(
					'value' => (float) $prices['regular_price'],
					'title' => __( 'Regular price ', 'woocommerce-searchanise' ) . $role,
					'type'  => 'float',
				);

			}
		}

		// Restore original roles.
		$current_user->roles = $curent_user_roles;

		return true;
	}

	/**
	 * Generate product prices for current usergroup
	 *
	 * @param WC_Product $product_data      WC product data.
	 * @param array      $children_products Children products.
	 * @param string     $lang_code         Lang code.
	 *
	 * @return array
	 */
	public function generate_product_prices( $product_data, $children_products = null, $lang_code = null ) {
		// Fix for the "Booster for WooCommerce" plugin.
		// Prevent using wrong currency while indexing, caused by plugin's Multicurrency module.
		if ( function_exists( 'WCJ' ) && function_exists( 'wcj_remove_change_price_hooks' ) ) {
			$wcj_multicurrency = WCJ()->modules['multicurrency'];
			if ( isset( $wcj_multicurrency->price_hooks_priority ) ) {
				wcj_remove_change_price_hooks( $wcj_multicurrency, $wcj_multicurrency->price_hooks_priority );
			}
		}

		if ( $product_data instanceof \WC_Product_Variable ) {
			// Variable product.
			$prices = $this->get_variation_product_prices( $product_data );

		} elseif ( $product_data instanceof \WC_Product_Grouped ) {
			// Grouped product.
			$child_prices = array();
			$child_regular_prices = array();
			$child_sale_prices = array();
			$discounts = array();
			$children = ! empty( $children_products ) ? $children_products : $this->get_children_products( $product_data );

			foreach ( $children as $child ) {
				$_child_prices = $this->generate_product_prices( $child, null, $lang_code );

				if ( ! empty( $_child_prices['price'] ) ) {
					$child_prices[] = $_child_prices['price'];
				}

				if ( ! empty( $_child_prices['regular_price'] ) ) {
					$child_regular_prices[] = $_child_prices['regular_price'];
				}

				if ( ! empty( $_child_prices['sale_price'] ) ) {
					$child_sale_prices[] = $_child_prices['sale_price'];
				}

				if ( ! empty( $_child_prices['max_discount'] ) ) {
					$discounts[] = $_child_prices['max_discount'];
				}
			}

			if ( ! empty( $child_prices ) || ! empty( $child_regular_prices ) || ! empty( $child_sale_prices ) ) {

				if ( ! empty( $child_sale_prices ) ) {
					$max_price = max( $child_sale_prices );
				} elseif ( ! empty( $child_regular_prices ) ) {
					$max_price = max( $child_regular_prices );
				} else {
					$max_price = max( $child_prices );
				}
			} else {
				$min_price = 0;
				$max_price = 0;
			}

			if ( empty( $child_prices ) && empty( $child_sale_prices ) ) {
				$price = 0;
			} else {
				$price = min( array_merge( $child_prices, $child_sale_prices ) );
			}

			if ( empty( $child_regular_prices ) ) {
				$regular_price = 0;
			} else {
				$regular_price = min( $child_regular_prices );
			}

			if ( empty( $child_sale_prices ) ) {
				$sale_price = 0;
			} else {
				$sale_price = min( $child_sale_prices );
			}

			$prices = array(
				'price'         => $price,
				'regular_price' => $regular_price,
				'sale_price'    => $sale_price,
				'max_price'     => $max_price,
			);

			if ( ! empty( $discounts ) ) {
				$prices['max_discount'] = max( $discounts );
			}
		} else {
			// Simple product.
			$prices = $this->get_simple_product_prices( $product_data );
		}

		/**
		 * Filters generated usergroup prices
		 *
		 * @since 1.0.0
		 *
		 * @param array $prices            Usergroup prices data
		 * @param WC_Product $product_data Product data
		 * @param array $children_products Product children (for grouped product)
		 * @param string $lang_code        Lang code
		 */
		return (array) apply_filters( 'se_generate_product_prices', $prices, $product_data, $children_products, $lang_code );
	}

	/**
	 * Generates product prices for Variable product
	 *
	 * @param WC_Product_Variable $product_data Product data.
	 *
	 * @return array
	 */
	public function get_variation_product_prices( $product_data ) {
		// Variable product.
		$variations = $product_data->get_available_variations();
		$discounts = array();

		foreach ( $variations as $v ) {
			if (
				( $v['is_in_stock'] || 'yes' !== get_option( 'woocommerce_hide_out_of_stock_items' ) ) &&
				! empty( $v['display_regular_price'] ) &&
				$v['display_price'] < $v['display_regular_price']
			) {
				$discounts[] = ( 1.0 - (float) $v['display_price'] / (float) $v['display_regular_price'] ) * 100;
			}
		}

		$prices = array(
			'price'         => $product_data->get_variation_price( 'min', true ),
			'regular_price' => $product_data->get_variation_regular_price( 'min', true ),
			'sale_price'    => $product_data->get_variation_sale_price( 'min', true ),
			'max_price'     => $product_data->get_variation_price( 'max', true ),
		);

		if ( ! empty( $discounts ) ) {
			$prices['max_discount'] = max( $discounts );
		}

		return $prices;
	}

	/**
	 * Get simple product's prices array
	 *
	 * @param WC_Product $product_data Product data.
	 *
	 * @return array
	 */
	public function get_simple_product_prices( $product_data ) {
		if ( $product_data->is_on_sale() ) {
			$price = wc_get_price_to_display( $product_data, array( 'price' => $product_data->get_sale_price() ) );
			$max_price = $price;
		} else {
			$price = wc_get_price_to_display( $product_data );
			$max_price = $price;
		}

		$regular_price      = wc_get_price_to_display( $product_data, array( 'price' => $product_data->get_regular_price() ) );
		$sale_price         = wc_get_price_to_display( $product_data, array( 'price' => $product_data->get_sale_price() ) );

		$prices = array(
			'price'         => $price,
			'regular_price' => $regular_price,
			'sale_price'    => $sale_price,
			'max_price'     => $max_price,
		);

		if ( ! empty( $regular_price ) ) {
			$prices['max_discount'] = ( 1.0 - $price / $regular_price ) * 100;
		}

		return $prices;
	}

	/**
	 * Returns available usergroups for product
	 *
	 * @param WC_Product $product_data Product data.
	 * @param string     $lang_code    Lang code.
	 *
	 * @return array
	 */
	public function get_products_usergroup_ids( $product_data, $lang_code ) {
		$usergroup_ids = array( Api::USERGROUP_GUEST );

		/**
		 * Filters product usergroup ids
		 *
		 * @since 1.0.0
		 *
		 * @param array $usergroup_ids     Product usergroup ids
		 * @param WC_product $product_data Product data
		 * @param $lang_code               Lang code
		 */
		return (array) apply_filters( 'se_product_usergroup_ids', $usergroup_ids, $product_data, $lang_code );
	}

	/**
	 * Returns add to cart url for product
	 *
	 * @param WC_Product $product_data Product data.
	 *
	 * @return string
	 */
	public function get_add_to_cart_product_url( $product_data ) {
		if (
			empty( $product_data )
			|| ! $product_data instanceof \WC_Product
			|| in_array( $product_data->get_type(), array( 'grouped', 'external' ) )
		) {
			return '';
		}

		return admin_url(
			'admin-ajax.php?' . http_build_query(
				array(
					'action'     => 'se_ajax_add_to_cart',
					'product_id' => $product_data->get_id(),
				)
			)
		);
	}

	/**
	 * Returns children products for group
	 *
	 * @param WC_Product_Grouped $product_data Product data.
	 *
	 * @return array
	 */
	public function get_children_products( $product_data ) {
		$children = array_map( 'wc_get_product', $product_data->get_children() );
		$children = array_filter(
			$children,
			function ( $c ) {
				return $c instanceof \WC_Product;
			}
		);

		foreach ( $children as $k => $child ) {
			if ( $this->get_product_quantity( $child ) == 0 && 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
				unset( $children[ $k ] );
			}
		}

		return $children;
	}

	/**
	 * Generate product data
	 *
	 * @param WC_Product $product_data Product data.
	 * @param string     $lang_code        Lang code.
	 *
	 * @return array
	 */
	public function prepare_product_data( $product_data, $lang_code ) {
		// Fix for gift-wrapper-for-woocommerce module.
		if ( class_exists( 'GTW_Frontend', false ) ) {
			global $product;
			$product = $product_data;
		}

		$entry = array(
			'id' => array(
				'value' => $product_data->get_id(),
				'title' => __( 'product Id', 'woocommerce-searchanise' ),
			),
			'title' => array(
				'value'  => $product_data->get_name(),
				'title'  => __( 'Product Title', 'woocommerce' ),
				'weight' => self::WEIGHT_SHORT_TITLE,
			),
			'slug' => array(
				'value' => $product_data->get_slug(),
				'title' => __( 'Slug', 'woocommerce' ),
			),
			'summary' => array(
				'value' => $this->remove_content_noise( $product_data->get_short_description() != '' ? $product_data->get_short_description() : $product_data->get_description() ),
				'title' => __( 'Summary', 'woocommerce-searchanise' ),
			),
			'product_type' => array(
				'value' => $product_data->get_type(),
				'title' => __( 'Product Type', 'woocommerce' ),
			),
			'link' => array(
				'value' => Api::get_instance()->get_language_link( $product_data->get_permalink(), $lang_code ),
				'title' => __( 'Product URL', 'woocommerce-searchanise' ),
			),
			'product_code' => array(
				'value'  => $product_data->get_sku(),
				'title'  => __( 'SKU', 'woocommerce' ),
				'weight' => self::WEIGHT_SHORT_TITLE,
			),
			'visibility' => array(
				'value' => $product_data->get_catalog_visibility(), // visible | catalog | search | hidden.
				'title' => __( 'Visibility', 'woocommerce' ),
			),
			'status' => array(
				'value' => $product_data->get_status(), // published, trash, private, ...
				'title' => __( 'Status', 'woocommerce' ),
			),
			'image_link' => array(
				'title' => __( 'Image link', 'woocommerce-searchanise' ),
			),
			'needs_shipping' => array(
				'value' => $product_data->needs_shipping() ? 'N' : 'Y',
				'title' => __( 'Free shipping', 'woocommerce' ),
			),
			'sold_individually' => array(
				'value' => $product_data->get_sold_individually() ? 'Y' : 'N',
				'title' => __( 'Sold individually', 'woocommerce' ),
			),
			'virtual' => array(
				'value' => $product_data->get_virtual() ? 'Y' : 'N',
				'title' => __( 'Virutal', 'woocommerce' ),
			),
			'downloadable' => array(
				'value' => $product_data->get_downloadable() ? 'Y' : 'N',
				'title' => __( 'Downloadable', 'woocommerce' ),
			),
			'menu_order' => array(
				'value' => $product_data->get_menu_order(),
				'title' => __( 'Menu order', 'woocommerce' ),
				'type'  => 'int',
			),
			'weight' => array(
				'value' => (float) $product_data->get_weight(),
				'title' => __( 'Weight', 'woocommerce' ),
				'type'  => 'float',
			),
			'length' => array(
				'value' => (float) $product_data->get_length(),
				'title' => __( 'Length', 'woocommerce' ),
				'type'  => 'float',
			),
			'width' => array(
				'value' => (float) $product_data->get_width(),
				'title' => __( 'Width', 'woocommerce' ),
				'type'  => 'float',
			),
			'height' => array(
				'value' => (float) $product_data->get_height(),
				'title' => __( 'Height', 'woocommerce' ),
				'type'  => 'float',
			),
		);

		if ( $product_data instanceof \WC_Product_Variable ) {
			// Variable product.
			$variations = $product_data->get_available_variations();
			$variants = array();
			$variants_skus = array();
			$variants_descriptions = array();

			foreach ( $variations as $v ) {
				$variant = array(
					'product_code' => $v['sku'],
					'variation_id' => $v['variation_id'],
					'price'        => (float) $v['display_price'],
					'list_price'   => (float) $v['display_regular_price'],
					'is_in_stock'  => $v['is_in_stock'] ? 'Y' : 'N', // TODO: Need additional check for variation manage stock.
					'description'  => $this->remove_content_noise( $v['variation_description'] ),
					'active'       => $v['variation_is_active'] ? 'Y' : 'N',
					'visible'      => $v['variation_is_visible'] ? 'Y' : 'N',
					'image_link'   => '',
				);

				// Generate image link.
				if ( Api::get_instance()->is_result_widget_enabled( $lang_code ) ) {
					$image_url = $this->get_product_image( $v['image_id'], self::IMAGE_SIZE );
				} else {
					$image_url = $this->get_product_image( $v['image_id'], self::THUMBNAIL_SIZE );
				}
				$variant['image_link'] = ! empty( $image_url ) ? $image_url : '';

				// Adds attributes.
				if ( ! empty( $v['attributes'] ) ) {
					foreach ( $v['attributes'] as $attr_name => $attr_val ) {
						$parsed_attr_name = str_replace( 'attribute_pa_', '', $attr_name );
						$variant['attributes'][ $parsed_attr_name ] = $attr_val;
					}
				}

				if ( ! empty( $variant['product_code'] ) ) {
					$variants_skus[] = $variant['product_code'];
				}

				if ( ! empty( $variant['description'] ) ) {
					$variants_descriptions[] = $variant['description'];
				}

				$variants[] = $variant;
			}

			if ( ! empty( $variants ) && self::SEND_VARIATIONS ) {
				$entry['woocommerce_variants'] = array(
					'name'  => 'woocommerce_variants',
					'title' => __( 'WooCommerce variants', 'woocommerce-searchanise' ),
					'value' => $variants,
				);
			}

			// Grouped data.
			if ( ! empty( $variants_skus ) ) {
				$entry['se_grouped_product_code'] = array(
					'title'       => __( 'SKU', 'woocommerce' ) . ' - Grouped',
					'weight'      => self::WEIGHT_SHORT_TITLE,
					'value'       => $this->filter_grouped_values( $variants_skus ),
					'text_search' => 'Y',
				);
			}

			if ( ! empty( $variants_descriptions ) ) {
				$entry['se_grouped_short_description'] = array(
					'title'       => __( 'Product short description', 'woocommerce' ) . ' - Grouped',
					'weight'      => self::WEIGHT_DESCRIPTION_GROUPED,
					'value'       => $this->filter_grouped_values( $variants_descriptions ),
					'text_search' => 'Y',
				);
			}
		} elseif ( $product_data instanceof \WC_Product_Grouped ) {
			// Grouped product.
			$children = $this->get_children_products( $product_data );

			foreach ( $children as $child ) {
				$child_skus[]               = $child->get_sku();
				$child_short_descriptions[] = $child->get_short_description();
				$child_full_descriptions[]  = $child->get_description();
			}

			// Grouped data.
			if ( ! empty( $child_skus ) ) {
				$entry['se_grouped_product_code'] = array(
					'title'       => __( 'SKU', 'woocommerce' ) . ' - Grouped',
					'weight'      => self::WEIGHT_SHORT_TITLE,
					'value'       => $this->filter_grouped_values( $child_skus ),
					'text_search' => 'Y',
				);
			}

			if ( ! empty( $child_short_descriptions ) ) {
				$entry['se_grouped_short_description'] = array(
					'title'       => __( 'Product short description', 'woocommerce' ) . ' - Grouped',
					'weight'      => self::WEIGHT_DESCRIPTION_GROUPED,
					'value'       => $this->filter_grouped_values( $child_short_descriptions ),
					'text_search' => 'Y',
				);
			}

			if ( ! empty( $child_full_descriptions ) ) {
				$entry['se_grouped_full_description'] = array(
					'title'       => __( 'Product description', 'woocommerce' ) . ' - Grouped',
					'weight'      => self::WEIGHT_DESCRIPTION_GROUPED,
					'value'       => $this->filter_grouped_values( $child_full_descriptions ),
					'text_search' => 'Y',
				);
			}
		}

		// Generate usergroup prices.
		$this->generate_usergroup_product_prices( $entry, $product_data, $lang_code, isset( $children ) ? $children : array() );

		// Generate image link.
		if ( Api::get_instance()->is_result_widget_enabled( $lang_code ) ) {
			$image_url = $this->get_product_image( $product_data->get_image_id(), self::IMAGE_SIZE );
		} else {
			$image_url = $this->get_product_image( $product_data->get_image_id(), self::THUMBNAIL_SIZE );
		}

		if ( ! empty( $image_url ) ) {
			$entry['image_link']['value'] = htmlspecialchars( $image_url, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 );
		}

		// Generate image gallery.
		if ( Api::get_instance()->is_result_widget_enabled( $lang_code ) ) {
			$gallery_image_ids = $product_data->get_gallery_image_ids();

			if ( ! empty( $gallery_image_ids ) ) {
				$gallery_images = array();
				$i = 0;

				foreach ( $gallery_image_ids as $image_id ) {
					if ( $i <= self::LIMIT_WOOCOMMERCE_IMAGES ) {
						$image_url = $this->get_product_image( $image_id, self::IMAGE_SIZE );

						if ( ! empty( $image_url ) ) {
							$gallery_images[] = htmlspecialchars( $image_url, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 );
						}

						$i++;
					}
				}

				if ( ! empty( $gallery_images ) ) {
					$entry['woocommerce_images'] = array(
						'value' => $gallery_images,
						'title' => __( 'Product images', 'woocommerce' ),
					);
				}
			}
		}

		// Adds full description if needed.
		if ( $product_data->get_short_description() != '' && $product_data->get_description() != '' ) {
			$entry['full_description'] = array(
				'name'        => 'full_description',
				'title'       => __( 'Product description', 'woocommerce' ),
				'text_search' => 'Y',
				'weight'      => self::WEIGHT_DESCRIPTION,
				'value'       => $this->remove_content_noise( $product_data->get_description() ),
			);
		}

		// Adds stock data.
		$entry['quantity'] = array(
			'value' => $this->get_product_quantity( $product_data, isset( $children ) ? $children : array() ),
			'title' => __( 'Stock quantity', 'woocommerce' ),
			'type'  => 'int',
		);
		$entry['stock_status'] = array(
			'name'  => 'stock_status',
			'title' => __( 'Stock status', 'woocommerce' ),
			'value' => $this->get_stock_status( $product_data, $lang_code ),
		);
		$entry['is_in_stock'] = array(
			'name'  => 'is_in_stock',
			'value' => 0 !== $entry['quantity']['value'] ? 'Y' : 'N',
		);

		// Adds product attributes.
		$attributes = $product_data->get_attributes();
		$custom_attributes = $this->check_attributtes( Api::get_instance()->get_custom_attributes() );

		if ( ! empty( $attributes ) ) {
			foreach ( $attributes as $attr_name => $attr ) {
				$this->generate_product_attribute( $entry, $attr, $lang_code );
			}
		}

		if ( ! empty( $custom_attributes ) ) {
			$this->generate_custom_attribute( $entry, $custom_attributes, $product_data, $lang_code );
		}

		$products_meta_fields = Api::get_instance()->get_custom_product_fields();

		if ( ! empty( $products_meta_fields ) ) {
			$this->generate_product_meta_fields( $entry, $products_meta_fields, $product_data );
		}

		// Add dates.
		$created = $product_data->get_date_created();
		$modified = $product_data->get_date_modified();

		if ( $created instanceof \WC_DateTime ) {
			$entry['created'] = array(
				'value' => $created->getTimestamp(),
				'title' => __( 'Created at', 'woocommerce' ),
				'type'  => 'int',
			);
		}

		if ( $modified instanceof \WC_DateTime ) {
			$entry['modified'] = array(
				'value' => $modified->getTimestamp(),
				'title' => __( 'Updated at', 'woocommerce' ),
				'type'  => 'int',
			);
		}

		// Adds tags.
		$tag_ids = $product_data->get_tag_ids();
		if ( ! empty( $tag_ids ) ) {
			$entry['tags'] = array(
				'title'       => __( 'Product tags', 'woocommerce' ),
				'type'        => 'text',
				'text_search' => 'Y',
				'weight'      => self::WEIGHT_TAGS,
				'value'       => $this->get_product_terms( $tag_ids, 'product_tag', $lang_code ),
			);
		}

		// Adds review data.
		if ( 'yes' === get_option( 'woocommerce_enable_reviews', 'yes' ) && $product_data->get_reviews_allowed() ) {
			$entry['total_reviews'] = array(
				'value' => (int) $product_data->get_review_count(),
				'title' => __( 'Total reviews', 'woocommerce-searchanise' ),
			);
			$entry['reviews_average_score'] = array(
				'value' => (float) round( $product_data->get_average_rating(), 1 ),
				'title' => __( 'Average reviews score', 'woocommerce-searchanise' ),
			);
		}

		// Adds category data.
		$category_ids = $product_data->get_category_ids();
		if ( ! empty( $category_ids ) ) {
			$entry['category_ids'] = array(
				'name'        => 'category_ids',
				'title'       => __( 'Categories', 'woocommerce' ) . ' - IDs',
				'value'       => $category_ids,
				'weight'      => 0,
				'text_search' => 'N',
				'type'        => 'text',
			);

			$entry['categories'] = array(
				'value'       => $this->get_product_terms( $category_ids, 'product_cat', $lang_code ),
				'title'       => __( 'Categories', 'woocommerce' ),
				'text_search' => 'Y',
				'weight'      => self::WEIGHT_CATEGORIES,
				'type'        => 'text',
			);
		}

		// Adds sales data.
		$entry['sales_amount'] = array(
			'name'       => 'sales_amount',
			'title'      => __( 'Sales amount', 'woocommerce' ),
			'text_search' => 'N',
			'type'       => 'int',
			'value'      => (int) get_post_meta( $product_data->get_id(), 'total_sales', true ),
		);
		// TODO: sales_total.

		// Adds usergroup for visibility.
		$usergroup_ids = $this->get_products_usergroup_ids( $product_data, $lang_code );
		if ( ! empty( $usergroup_ids ) ) {
			$entry['usergroup_ids'] = array(
				'name'       => 'usergroup_ids',
				'title'      => __( 'User role', 'woocommerce' ) . ' - IDs',
				'text_search' => 'N',
				'value'      => $usergroup_ids,
			);
		}

		// Adds meta data.
		$entry = array_merge( $entry, $this->prepare_product_meta_data( $product_data, $lang_code ) );

		// Add to cart functionality.
		$entry['add_to_cart_url'] = array(
			'name'        => 'add_to_cart_url',
			'title'       => __( 'Add to cart url', 'woocommerce' ),
			'text_search' => 'N',
			'sorting'     => 'N',
			'filter_type' => 'none',
			'value'       => $this->get_add_to_cart_product_url( $product_data ),
			$lang_code,
		);

		// Adds upsell & crossell & related products for Recommendations.
		$entry['cross_sell_product_ids'] = array(
			'name'        => 'cross_sell_product_ids',
			'title'       => __( 'Cross-Sell Products', 'woocommerce' ) . ' - IDs',
			'filter_type' => 'none',
			'value'       => $product_data->get_cross_sell_ids(),
		);

		$entry['up_sell_product_ids'] = array(
			'name'        => 'up_sell_product_ids',
			'title'       => __( 'Up-Sell Products', 'woocommerce' ) . ' - IDs',
			'filter_type' => 'none',
			'value'       => $product_data->get_upsell_ids(),
		);

		$entry['related_product_ids'] = array(
			'name'        => 'related_product_ids',
			'title'       => __( 'Related Products', 'woocommerce' ) . ' - IDs',
			'filter_type' => 'none',
			'value'       => $this->get_related_product_ids( $product_data ),
		);

		// Adds also bought products for Recommendations.
		$entry['also_bought_product_ids'] = array(
			'name'        => 'also_bought_product_ids',
			'title'       => __( 'Also bought product', 'woocommerce' ) . ' - IDs',
			'filter_type' => 'none',
			'value'       => implode( ',', $product_data->also_bought_product_ids ),
		);

		/**
		 * Adds additional attributes in format array({attr_name} => {type})
		 */
		$additional_attributes = array();

		foreach ( $additional_attributes as $name => $type ) {
			$method = 'get_' . $name;
			if ( method_exists( $product_data, $method ) ) {
				$value = call_user_func( array( $product_data, $method ) );

				if ( '' !== $value ) {
					$entry[ $name ] = array(
						'name'  => $name,
						'title' => $name,
						'type'  => $type,
						'value' => call_user_func( array( $product_data, $method ) ),
					);
				}
			}
		}

		// Check sorting attributes.
		$sortable_attributes = $this->get_sortable_attributes();
		foreach ( $entry as $name => &$v ) {
			if ( in_array( $name, $sortable_attributes ) ) {
				$v['sorting'] = 'Y';
			}
		}

		/**
		 * Filters prepared product data for Searchanise
		 *
		 * @since 1.0.0
		 *
		 * @param array $entry      Prepared product data
		 * @param WC_Product        Original product data
		 * @param string $lang_code Lang code
		 */
		return (array) apply_filters( 'se_prepare_product_data', $entry, $product_data, $lang_code );
	}

	/**
	 * Prepare product meta data
	 *
	 * @param WC_Product $product_data Product data.
	 * @param string     $lang_code Lang code.
	 *
	 * @return array
	 */
	private function prepare_product_meta_data( $product_data, $lang_code ) {
		$seometa_themes = array(
			// alphabatized.
			'Builder'      => array(
				'meta_title'       => '_builder_seo_title',
				'meta_description' => '_builder_seo_description',
				'meta_keywords'    => '_builder_seo_keywords',
			),
			'Catalyst'     => array(
				'meta_title'       => '_catalyst_title',
				'meta_description' => '_catalyst_description',
				'meta_keywords'    => '_catalyst_keywords',
			),
			'Frugal'       => array(
				'meta_title'       => '_title',
				'meta_description' => '_description',
				'meta_keywords'    => '_keywords',
			),
			'Genesis'      => array(
				'meta_title'       => '_genesis_title',
				'meta_description' => '_genesis_description',
				'meta_keywords'    => '_genesis_keywords',
			),
			'Headway'      => array(
				'meta_title'       => '_title',
				'meta_description' => '_description',
				'meta_keywords'    => '_keywords',
			),
			'Hybrid'       => array(
				'meta_title'  => 'Title',
				'meta_description' => 'Description',
				'meta_keywords'    => 'Keywords',
			),
			'Thesis 1.x'   => array(
				'meta_title'       => 'thesis_title',
				'meta_description' => 'thesis_description',
				'meta_keywords'    => 'thesis_keywords',
			),
			'WooFramework' => array(
				'meta_title'       => 'seo_title',
				'meta_description' => 'seo_description',
				'meta_keywords'    => 'seo_keywords',
			),
		);

		$seometa_plugins = array(
			// alphabatized.
			'Add Meta Tags' => array(
				'meta_title'       => '_amt_title',
				'meta_description' => '_amt_description',
				'meta_keywords'    => '_amt_keywords',
			),
			'All in One SEO Pack'          => array(
				'meta_title'       => '_aioseop_title',
				'meta_description' => '_aioseop_description',
				'meta_keywords'    => '_aioseop_keywords',
			),
			'Greg\'s High Performance SEO' => array(
				'meta_title'       => '_ghpseo_secondary_title',
				'meta_description' => '_ghpseo_alternative_description',
				'meta_keywords'    => '_ghpseo_keywords',
			),
			'Headspace2'                   => array(
				'meta_title'      => '_headspace_page_title',
				'meta_description' => '_headspace_description',
				'meta_keywords'    => '_headspace_keywords',
			),
			'Infinite SEO'                 => array(
				'meta_title'       => '_wds_title',
				'meta_description' => '_wds_metadesc',
				'meta_keywords'    => '_wds_keywords',
			),
			'Jetpack'                => array(
				'meta_description' => 'advanced_seo_description',
			),
			'Meta SEO Pack'                => array(
				'meta_description' => '_msp_description',
				'meta_keywords'    => '_msp_keywords',
			),
			'Platinum SEO'                 => array(
				'meta_title'       => 'title',
				'meta_description' => 'description',
				'meta_keywords'    => 'keywords',
			),
			'SEOpressor'                 => array(
				'meta_title'       => '_seopressor_meta_title',
				'meta_description' => '_seopressor_meta_description',
			),
			'SEO Title Tag'                => array(
				'meta_title'       => 'title_tag',
				'meta_description' => 'meta_description',
			),
			'SEO Ultimate'                 => array(
				'meta_title'       => '_su_title',
				'meta_description' => '_su_description',
				'meta_keywords'    => '_su_keywords',
			),
			'Yoast SEO'                    => array(
				'meta_title'       => '_yoast_wpseo_title',
				'meta_description' => '_yoast_wpseo_metadesc',
				'meta_keywords'    => '_yoast_wpseo_metakeywords',
			),
		);

		$meta_data = array(
			'meta_title' => array(),
			'meta_description' => array(),
			'meta_keywords' => array(),
		);
		$seometa_platforms = array_merge( $seometa_themes, $seometa_plugins );

		// Get meta values.
		foreach ( $seometa_platforms as $platform => $schema ) {
			$_metas = get_post_meta( $product_data->get_id() );

			foreach ( $schema as $name => $field ) {
				if ( isset( $_metas[ $field ] ) ) {
					if ( is_array( $_metas[ $field ] ) ) {
						foreach ( $_metas[ $field ] as $k => $v ) {
							$meta_data[ $name ] = array_merge( $meta_data[ $name ], 'meta_keywords' == $name ? explode( ',', $v ) : array( $v ) );
						}
					} else {
						$meta_data[ $name ] = array_merge( $meta_data[ $name ], 'meta_keywords' == $name ? explode( ',', $_metas[ $field ] ) : array( $_metas[ $field ] ) );
					}
				}
			}
		}

		// Filter for Yoast SEO.
		$meta_data['meta_title'] = str_replace( array( '%%title%%', '%%sep%%', '%%sitename%%', '%%page%%' ), array( '', '', '', '' ), $meta_data['meta_title'] );
		$meta_data['meta_description'] = str_replace( array( '%%title%%', '%%sep%%', '%%sitename%%', '%%page%%' ), array( '', '', '', '' ), $meta_data['meta_description'] );

		/**
		 * Filters product metadata
		 *
		 * @since 1.0.0
		 *
		 * @param array $meta_data Product metadata
		 * @param string $lang_code Lang code
		 */
		$meta_data = apply_filters( 'se_prepare_product_meta_data', $meta_data, $lang_code );

		// Prepare data.
		$entry = array();
		if ( ! empty( $meta_data['meta_title'] ) ) {
			$entry['meta_title'] = array(
				'value'       => array_map( 'trim', array_unique( $meta_data['meta_title'] ) ),
				'title'       => __( 'Meta title', 'woocommerce-searchanise' ),
				'text_search' => 'Y',
				'weight'      => self::WEIGHT_META_TITLE,
			);
		}

		if ( ! empty( $meta_data['meta_description'] ) ) {
			$entry['meta_description'] = array(
				'value'       => array_map( 'strip_tags', array_map( 'trim', array_unique( $meta_data['meta_description'] ) ) ),
				'title'       => __( 'Meta description', 'woocommerce-searchanise' ),
				'text_search' => 'Y',
				'weight'      => self::WEIGHT_META_DESCRIPTION,
			);
		}

		if ( ! empty( $meta_data['meta_keywords'] ) ) {
			$entry['meta_keywords'] = array(
				'value'       => array_map( 'trim', array_unique( $meta_data['meta_keywords'] ) ),
				'title'       => __( 'Meta keywords', 'woocommerce-searchanise' ),
				'text_search' => 'Y',
				'weight'      => self::WEIGHT_META_KEYWORDS,
			);
		}

		return $entry;
	}

	/**
	 * Generate product attribute
	 *
	 * @param array  $entry     Searchanise data.
	 * @param object $attr      Attribute.
	 * @param string $lang_code Lang code.
	 */
	public function generate_product_attribute( &$entry, $attr, $lang_code ) {
		if ( $attr->is_taxonomy() ) {
			$taxonomy_object = $attr->get_taxonomy_object();
			$terms = $attr->get_terms();
			$variants = array();

			foreach ( $terms as $term ) {
				if ( Api::get_instance()->is_result_widget_enabled( $lang_code ) ) {
					$variants[] = wp_specialchars_decode( $term->name );
				} else {
					$variants[] = $term->slug;
				}
			}

			$attribute_data = array(
				'title'       => $taxonomy_object->attribute_label,
				'type'        => 'text',
				'text_search' => 'Y',
				'weight'      => self::WEIGHT_SELECT_ATTRIBUTES,
				'value'       => $variants,
			);

			/**
			 * Filters data for generated taxonomy atrribute
			 *
			 * @since 1.0.0
			 *
			 * @param array  $attribute_data Taxonomy attribute data
			 * @param object $attr           Taxonomy attribute
			 * @param string $lang_code      Lang code
			 */
			$attribute_data = (array) apply_filters( 'se_generate_taxonomy_attribute', $attribute_data, $attr, $lang_code );
			$entry[ self::get_taxonomy_id( $taxonomy_object->attribute_name ) ] = $attribute_data;

		} else {
			$attribute_data = array(
				'title'       => $attr->get_name(),
				'type'        => 'text',
				'text_search' => $attr->get_visible() ? 'Y' : 'N',
				'weight'      => $attr->get_visible() ? self::WEIGHT_TEXT_ATTRIBUTES : 0,
				'value'       => $attr->get_options(),
			);
			$attribute_id = self::get_attribute_id( $attr );

			if ( ! empty( $attribute_id ) ) {
				/**
				 * Filters simple attribute data
				 *
				 * @since 1.0.0
				 *
				 * @param array  $attribute_data Attribute data
				 * @param object $attr           Attribute
				 * @param string $lang_code      Lang code
				 */
				$entry[ $attribute_id ] = (array) apply_filters( 'se_generate_simple_attribute', $attribute_data, $attr, $lang_code );
			}
		}
	}

	/**
	 * Returns identifier in Searchanise for taxonomy object
	 *
	 * @param string $taxonomy Taxonomy attribute.
	 *
	 * @return string
	 */
	public static function get_taxonomy_id( $taxonomy ) {
		if ( preg_match( '/^[a-zA-Z_-][0-9a-zA-Z_-]+$/i', $taxonomy ) ) {
			return self::CUSTOM_TAXONOMY_PREFIX . $taxonomy;
		} else {
			return self::CUSTOM_TAXONOMY_PREFIX . md5( $taxonomy );
		}
	}

	/**
	 * Returns identifier in Searchanise for attribute
	 *
	 * @param object $attr Attribute.
	 *
	 * @return string
	 */
	public static function get_attribute_id( $attr ) {
		$attribute_name = $attr->get_name();

		if ( ! empty( $attribute_name ) ) {
			$attribute_name = 'custom_attribute_' . md5( strtolower( $attribute_name ) );
		}

		return $attribute_name;
	}

	/**
	 * Get stock product status
	 *
	 * @param WC_Product $product   Product.
	 * @param string     $lang_code Lang code.
	 *
	 * @return string
	 */
	public function get_stock_status( $product, $lang_code ) {
		$stock_status = $product->get_stock_status();

		if ( Api::get_instance()->is_result_widget_enabled( $lang_code ) ) {
			$statuses = wc_get_product_stock_status_options();
			$stock_status = $statuses[ $product->get_stock_status() ];
		}

		/**
		 * Filters available stock statuses
		 *
		 * @since 1.0.0
		 *
		 * @param string $stock_status Stock status name
		 * @param WC_Product           Product data
		 */
		return apply_filters( 'se_get_stock_status', $stock_status, $product );
	}

	/**
	 * Get stock product quantity
	 *
	 * @param WC_Product $product          Product data.
	 * @param array      $united_products  United product data.
	 *
	 * @return int
	 */
	public function get_product_quantity( $product, $united_products = array() ) {
		$quantity = 1;

		if ( ( get_option( 'woocommerce_manage_stock' ) == 'yes' && $product->get_manage_stock() ) || ! empty( $united_products ) ) {
			$out_of_stock_amount = (int) get_option( 'woocommerce_notify_no_stock_amount' );
			$quantity = max( 0, $product->get_stock_quantity() - $out_of_stock_amount );

			if ( $quantity <= 0 ) {
				$quantity = $this->get_quantity( $product->get_stock_status() );
			}

			if ( ! empty( $united_products ) && 1 != $quantity ) { // TODO: Check if really needed.
				$quantities = array();

				foreach ( $united_products as $_product ) {
					$quantities[] = $this->get_product_quantity( $_product );
				}

				$quantity = in_array( 1, $quantities ) ? 1 : ( in_array( -1, $quantities ) ? -1 : 0 );
			}
		} else {
			$quantity = $this->get_quantity( $product->get_stock_status() );
		}

		// Limits quantity in rage -1, 0, -1.
		$quantity = max( -1, min( 1, $quantity ) );

		/**
		 * Filters product quanity
		 *
		 * @since 1.0.0
		 *
		 * @param int         $quantity  Product quantity
		 * @param  WC_Product $product   Product data
		 */
		return (int) apply_filters( 'se_get_product_quanity', $quantity, $product );
	}

	/**
	 * Get additional data for products
	 *
	 * @param array $products Products list.
	 */
	public function get_products_additional( array &$products ) {
		if ( empty( $products ) ) {
			return;
		}

		$all_product_ids = array();
		$also_bought_data = array();
		foreach ( $products as $product ) {
			$all_product_ids[] = $product->get_id();
		}

		if ( Api::get_instance()->import_also_bought_products() ) {
			$also_bought_data = $this->get_also_bought_products( $all_product_ids );
		}

		foreach ( $products as &$product ) {
			$product_id = $product->get_id();

			$product->also_bought_product_ids = array();
			if ( isset( $also_bought_data[ $product_id ] ) ) {
				$product->also_bought_product_ids = $also_bought_data[ $product_id ];
			}
		}

		/**
		 * Get additional data for products
		 *
		 * @since 1.0.0
		 *
		 * @param array $products Products list
		 */
		$products = (array) apply_filters( 'se_get_products_additional', $products );
	}

	/**
	 * Get Product Term
	 *
	 * @param array  $terms_ids Term ids.
	 * @param string $type      Term type.
	 * @param string $lang_code Lang code.
	 *
	 * @return array Terms list
	 */
	private function get_product_terms( $terms_ids, $type, $lang_code ) {
		$terms = array();

		if ( ! empty( $terms_ids ) ) {
			$terms_list = get_terms(
				array(
					'taxonomy'   => $type,
					'include'    => $terms_ids,
					'hide_empty' => false,
				)
			);

			if ( $terms_list && ! is_wp_error( $terms_list ) ) {
				foreach ( $terms_list as $term ) {
					/**
					 * Get Product Term
					 *
					 * @since 1.0.0
					 */
					$terms[] = (string) apply_filters( 'se_get_product_term_name', wp_specialchars_decode( $term->name ), $term, $lang_code );
				}
			}
		}

		return $terms;
	}

	/**
	 * Fetch also bought product ids
	 *
	 * @param array $product_ids Product identifiers.
	 * @param int   $limit_days  Limit order interval in days.
	 *
	 * @return array
	 */
	public function get_also_bought_products( array $product_ids, $limit_days = 180 ) {
		global $wpdb;

		$results = array();
		$pid = array_map( 'intval', $product_ids );

		// Fetch all order for products.
		$_all_orders = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT
					order_id,
					product_id
				FROM {$wpdb->prefix}wc_order_product_lookup
				WHERE
				product_id IN ('" . implode( "', '", esc_sql( $pid ) ) . "')
				AND date_created > DATE_SUB(NOW(), INTERVAL %d DAY)",
				$limit_days
			),
			ARRAY_A
		);

		$all_orders = array();
		$all_orders_ids = array();
		foreach ( $_all_orders as $data ) {
			$all_orders[ $data['product_id'] ][] = $data['order_id'];
			$all_orders_ids[] = $data['order_id'];
		}
		unset( $_all_orders );

		if ( ! empty( $all_orders ) ) {
			$all_orders_ids = array_map( 'intval', $all_orders_ids );

			// Fetch all order items for selected orders.
			$all_orders_products = array();
			$_all_orders_products = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT
						product_id,
						order_id
					FROM {$wpdb->prefix}wc_order_product_lookup
					WHERE
						order_id IN ('" . implode( "', '", esc_sql( $all_orders_ids ) ) . "')
					ORDER BY order_id DESC"
				),
				ARRAY_A
			);
			foreach ( $_all_orders_products as $data ) {
				$all_orders_products[ $data['order_id'] ][] = $data['product_id'];
			}
			unset( $_all_orders_products );

			// Assemble bought products data.
			foreach ( $all_orders as $product_id => $order_ids ) {
				$results[ $product_id ] = isset( $results[ $product_id ] ) ? $results[ $product_id ] : array();

				foreach ( $order_ids as $order_id ) {
					if ( isset( $all_orders_products[ $order_id ] ) ) {
						$results[ $product_id ] = array_merge( $results[ $product_id ], $all_orders_products[ $order_id ] );

						// Remove self.
						$self_index = array_search( $product_id, $results[ $product_id ] );
						if ( false !== $self_index ) {
							unset( $results[ $product_id ][ $self_index ] );
						}
					}
				}
			}

			$results = array_map( 'array_unique', $results );
		}

		return $results;
	}

	/**
	 * Generate categories data
	 *
	 * @param array  $category_ids Category ids.
	 * @param string $lang_code    Lang code.
	 *
	 * @return array
	 */
	public function get_categories_data( $category_ids, $lang_code ) {
		$categories = array();
		$data = array();

		if ( ! empty( $category_ids ) ) {
			$categories = get_terms(
				array(
					'taxonomy'   => 'product_cat',
					'include'    => (array) $category_ids,
					'hide_empty' => false,
				)
			);
		}

		foreach ( $categories as $cat ) {
			if ( in_array( $cat->slug, $this->get_excluded_categories() ) ) {
				continue;
			}

			$image_url = '';
			// TODO: Get categories images using one loop in future.
			$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id' );

			if ( ! empty( $thumbnail_id ) ) {
				$thumbnail_id = reset( $thumbnail_id );
				$image_url    = $this->get_product_image( $thumbnail_id, self::THUMBNAIL_SIZE );
			}

			$category_data = array(
				'id'            => $cat->term_id,
				'parent_id'     => $cat->parent,
				'path'          => $cat->parent ? implode( '/', array_reverse( get_ancestors( $cat->term_id, 'product_cat', 'taxonomy' ) ) ) . '/' . $cat->term_id : '',
				'link'          => Api::get_instance()->get_language_link( get_term_link( $cat ), $lang_code ),
				'title'         => $cat->name,
				'summary'       => $cat->description,
				'image_link'    => $image_url,
			);

			/**
			 * Filters categories data for Searchanise
			 *
			 * @since 1.0.0
			 *
			 * @param array $category_data Prepared category data
			 * @param object $cat          Original category
			 * @param string $lang_code    Lang code
			 */
			$data[] = (array) apply_filters( 'se_get_category_data', $category_data, $cat, $lang_code );
		}

		$categories_data = array( 'categories' => $data );

		return $this->get_translate( $categories_data, $lang_code );
	}

	/**
	 * Generate pages data
	 *
	 * @param array  $page_ids  Page ids.
	 * @param string $lang_code Lang code.
	 *
	 * @return array
	 */
	public function get_pages_data( $page_ids, $lang_code ) {
		$pages = array();
		$data = array();

		if ( ! empty( $page_ids ) ) {
			$pages = get_posts(
				array(
					'include'     => (array) $page_ids,
					'post_type'   => self::get_post_types(),
				)
			);
		}

		$excluded_pages = array_merge(
			$this->get_excluded_pages(),
			array(
				Api::get_instance()->get_system_setting( 'search_result_page' ),
			)
		);

		foreach ( $pages as $post ) {
			if (
				'publish' == $post->post_status
				&& ! in_array( $post->post_name, $excluded_pages )
			) {
				$page_data = array(
					'id'         => $post->ID,
					'link'       => Api::get_instance()->get_language_link( get_permalink( $post ), $lang_code ),
					'title'      => $post->post_title,
					'summary'    => self::STRIP_POST_CONTENT ? $this->get_strip_post_content( $post ) : $post->post_content,
					'image_link' => (string) get_the_post_thumbnail_url( $post->ID ),
				);

				/**
				 * Filters prepared page data for Searchanise
				 *
				 * @since 1.0.0
				 *
				 * @param array $page_data  Prepared page data
				 * @param WP_Post $post     Page data
				 * @param string $lang_code Lang code
				 */
				$data[] = (array) apply_filters( 'se_get_page_data', $page_data, $post, $lang_code );
			}
		}

		$pages_data = array( 'pages' => $data );

		return $this->get_translate( $pages_data, $lang_code );
	}

	/**
	 * Get post strip content
	 *
	 * @param object $post Post object.
	 *
	 * @return string post content
	 */
	public function get_strip_post_content( $post ) {
		$excerpt = $this->remove_content_noise( $post->post_content );

		return wp_strip_all_tags( $excerpt );
	}

	/**
	 * Remove all tags, shortcodes.
	 *
	 * @param  string $content Content data.
	 *
	 * @return string
	 */
	public function remove_content_noise( $content ) {
		$noise_patterns = array(
			// Wordpress shortcodes.
			'/\[[^\]]*\][^\[]*\[\/[^\[]*\]/',
			'/\[[^\]]*\]/',
			// strip out comments.
			"'<!--(.*?)-->'is",
			// strip out cdata.
			"'<!\[CDATA\[(.*?)\]\]>'is",
			// Script tags removal now preceeds style tag removal.
			// strip out <script> tags.
			"'<\s*script[^>]*[^/]>(.*?)<\s*/\s*script\s*>'is",
			"'<\s*script\s*>(.*?)<\s*/\s*script\s*>'is",
			// strip out <style> tags.
			"'<\s*style[^>]*[^/]>(.*?)<\s*/\s*style\s*>'is",
			"'<\s*style\s*>(.*?)<\s*/\s*style\s*>'is",
			// strip out preformatted tags.
			"'<\s*(?:code)[^>]*>(.*?)<\s*/\s*(?:code)\s*>'is",
			// strip out <pre> tags.
			"'<\s*pre[^>]*[^/]>(.*?)<\s*/\s*pre\s*>'is",
			"'<\s*pre\s*>(.*?)<\s*/\s*pre\s*>'is",
			// remove all values ​​in brackets.
			'/\[.*?\]/',
			'/\{.*?\}/',
		);

		// If there is ET builder (Divi), remove shortcodes.
		if ( function_exists( 'et_pb_is_pagebuilder_used' ) ) {
			$noise_patterns[] = '/\[\/?et_pb.*?\]/';
		}

		foreach ( $noise_patterns as $pattern ) {
			$content = preg_replace( $pattern, '', $content );
		}

		$content = str_replace( '&nbsp;', ' ', $content );

		return html_entity_decode( $content, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 );
	}

	/**
	 * Returns post types to be indexed as pages
	 *
	 * @return array
	 */
	public static function get_post_types() {
		$types = array( 'page' );

		if ( Api::get_instance()->import_block_posts() ) {
			$types[] = 'post';
		}

		return $types;
	}

	/**
	 * Returns excluded pages from indexation
	 *
	 * @return array
	 */
	public function get_excluded_pages() {
		$excluded_pages = Api::get_instance()->get_system_setting( 'excluded_pages' );

		if ( ! is_array( $excluded_pages ) ) {
			$excluded_pages = explode( ',', $excluded_pages );
		}

		/**
		 * Returns excluded pages from indexation
		 *
		 * @since 1.0.0
		 *
		 * @param array $excluded_pages
		 */
		return (array) apply_filters( 'se_get_excluded_pages', $excluded_pages );
	}

	/**
	 * Returns excluded categories from indexation
	 *
	 * @return array
	 */
	public function get_excluded_categories() {
		$excluded_categories = Api::get_instance()->get_system_setting( 'excluded_categories' );

		if ( ! is_array( $excluded_categories ) ) {
			$excluded_categories = explode( ',', $excluded_categories );
		}

		/**
		 * Returns excluded categories from indexation
		 *
		 * @since 1.0.0
		 *
		 * @param array $excluded_categories
		 */
		return (array) apply_filters( 'se_get_excluded_categories', $excluded_categories );
	}

	/**
	 * Rates ajax request action
	 */
	public static function rated() {
		Api::get_instance()->set_is_rated();
		wp_die( 'OK' );
	}

	/**
	 * Async ajax request action
	 */
	public static function ajax_async() {
		if ( Api::get_instance()->get_module_status() != 'Y' ) {
			wp_die( esc_html( __( 'Searchanise module not enabled', 'woocommerce-searchanise' ) ) );
		}

		$lang_code = ! empty( $_REQUEST[ self::FL_LANG_CODE ] ) ? sanitize_key( $_REQUEST[ self::FL_LANG_CODE ] ) : null;

		if ( $lang_code && ! Api::get_instance()->check_private_key( $lang_code ) ) {
			wp_die( esc_html( __( 'Invalid private key', 'woocommerce-searchanise' ) ) );
		}

		if ( ! empty( $_REQUEST[ self::FL_DISPLAY_ERRORS ] ) && self::FL_DISPLAY_ERRORS_KEY == $_REQUEST[ self::FL_DISPLAY_ERRORS ] ) {
			@error_reporting( E_ALL | E_STRICT );
			@ini_set( 'display_startup_errors', 1 );
		} else {
			@error_reporting( 0 );
			@ini_set( 'display_startup_errors', 0 );
		}

		$fl_ignore_processing = ! empty( $_REQUEST[ self::FL_IGNORE_PROCESSING ] ) && self::FL_IGNORE_PROCESSING_KEY == $_REQUEST[ self::FL_IGNORE_PROCESSING ];
		$fl_show_status       = ! empty( $_REQUEST[ self::FL_SHOW_STATUS_ASYNC ] ) && self::FL_SHOW_STATUS_ASYNC_KEY == $_REQUEST[ self::FL_SHOW_STATUS_ASYNC ];

		$status = self::get_instance()->async( $lang_code, $fl_ignore_processing );

		if ( $fl_show_status ) {
			/* translators: status */
			echo esc_html( sprintf( __( 'Searchanise status sync: %s', 'woocommerce-searchanise' ), $status ) );
		}

		wp_die();
	}

	/**
	 * Get custom attributes from taxonomies
	 *
	 * @return array
	 */
	public function generate_custom_product_attribute() {
		$custom_attributes = array();
		$taxonomies = get_option( 'cptui_taxonomies', array() );

		foreach ( $taxonomies as $tax ) {
			if ( in_array( 'product', $tax['object_types'] ) ) {
				$custom_attributes[] = $tax;
			}
		}

		return $custom_attributes;
	}

	/**
	 * Get custom taxonomies from taxonomies
	 *
	 * @return string
	 */
	public function get_custom_taxonomies() {
		return get_option( 'se_custom_taxonomies', false );
	}

	/**
	 * Generate custom attribite taxonomies
	 *
	 * @param array  $entry      Searchanise data.
	 * @param object $attributes Custom attributes.
	 * @param object $data       Product data.
	 * @param string $lang_code  Lang code.
	 *
	 * @return void
	 */
	public function generate_custom_attribute( &$entry, $attributes, $data, $lang_code ) {
		foreach ( $attributes as $custom ) {
			$terms = wc_get_product_terms( $data->get_id(), $custom, array( 'fields' => 'all' ) );
			$taxonomy = get_taxonomy( $custom );

			$attribute_data = array(
				'title'       => $taxonomy->label,
				'type'        => 'text',
				'text_search' => 'Y',
				'weight'      => 0,
				'value'       => $this->get_options( $terms, $lang_code ),
			);

			$entry[ self::get_taxonomy_id( $taxonomy->name ) ] = $attribute_data;
		}
	}

	/**
	 * Generate product meta fields
	 *
	 * @param array  $entry                    Searchanise data.
	 * @param array  $product_meta_fields_name Field names.
	 * @param object $data                     Product data.
	 *
	 * @return void
	 */
	public function generate_product_meta_fields( &$entry, $product_meta_fields_name, $data ) {
		foreach ( $product_meta_fields_name as $name ) {
			$value = get_post_meta( $data->get_id(), $name );

			if ( $value ) {
				$post_meta_data = array(
					'title'       => $name,
					'type'        => 'text',
					'text_search' => 'Y',
					'weight'      => self::WEIGHT_META_FIELD,
					'value'       => $value,
				);

				$name = ! preg_match( '/^[a-zA-Z_-][0-9a-zA-Z_-]+$/i', $name ) ? md5( strtolower( $name ) ) : $name;

				$entry[ self::PRODUCT_META_FIELD_PREFIX . $name ] = $post_meta_data;
			}
		}
	}

	/**
	 * Get value options for custom attributes
	 *
	 * @param object $terms     Terms object.
	 * @param string $lang_code Lang code.
	 *
	 * @return array options
	 */
	public function get_options( $terms, $lang_code ) {
		$options = array();

		foreach ( $terms as $term ) {
			if ( Api::get_instance()->is_result_widget_enabled( $lang_code ) ) {
				$options[] = html_entity_decode( $term->name, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 );
			} else {
				$options[] = $term->slug;
			}
		};

		return $options;
	}

	/**
	 * Check isset attributtes
	 *
	 * @param  array $custom_attributes Custom attrs list.
	 *
	 * @return array
	 */
	public function check_attributtes( $custom_attributes ) {
		$attributtes = $this->generate_custom_product_attribute();
		$custom = array();

		foreach ( $attributtes as $attr ) {
			if ( in_array( $attr['name'], $custom_attributes ) ) {
				$custom[] = $attr['name'];
			}
		}

		return $custom;
	}

	/**
	 * Translate value for lang_code
	 *
	 * @param array  $content    Content.
	 * @param string $lang_code  Lang code.
	 *
	 * @return array
	 */
	public function get_translate( $content, $lang_code ) {
		/**
		 * Translate value for lang_code
		 *
		 * @since 1.0.0
		 *
		 * @param array  $content
		 * @param string $lang_code
		 */
		return apply_filters( 'se_get_translate', $content, $lang_code );
	}

	/**
	 * Get product Quantity
	 *
	 * @param  string $stock_status Stock status.
	 *
	 * @return int
	 */
	public function get_quantity( $stock_status ) {
		switch ( $stock_status ) {
			case 'instock':
				$quantity = 1;
				break;
			case 'outofstock':
				$quantity = 0;
				break;
			case 'onbackorder':
				$quantity = -1;
				break;
		}

		return $quantity;
	}

	/**
	 * Get post meta keys for type product, exclude hidden(with '_%')
	 *
	 * @return array
	 */
	public function get_meta_product_types() {
		global $wpdb;

		return $wpdb->get_results(
			$wpdb->prepare(
				"SELECT
					post_title AS label,
					post_excerpt AS name
				FROM $wpdb->posts
				WHERE post_excerpt IN (SELECT
					DISTINCT pm.meta_key
				FROM $wpdb->postmeta pm
				LEFT JOIN $wpdb->posts AS p ON pm.post_id = p.ID
				WHERE p.post_type = %s AND pm.meta_key NOT LIKE %s)",
				'product',
				'\_%'
			)
		);
	}
}
