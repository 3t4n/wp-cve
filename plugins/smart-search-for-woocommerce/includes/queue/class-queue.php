<?php
/**
 * Searchanise Queue
 *
 * @package Searchanise/Queue
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

/**
 * Searchanise queue class
 */
class Queue {

	const NO_DATA               = 'N';
	const PHRASE                = 'phrase';

	// Queue actions.
	const UPDATE_PAGES          = 'update_pages';
	const UPDATE_PRODUCTS       = 'update_products';
	const UPDATE_ATTRIBUTES     = 'update_attributes';
	const UPDATE_CATEGORIES     = 'update_categories';

	const DELETE_PAGES          = 'delete_pages';
	const DELETE_PAGES_ALL      = 'delete_pages_all';
	const DELETE_PRODUCTS       = 'delete_products';
	const DELETE_PRODUCTS_ALL   = 'delete_products_all';
	const DELETE_FACETS         = 'delete_facets';
	const DELETE_FACETS_ALL     = 'delete_facets_all';
	const DELETE_ATTRIBUTES     = 'delete_attributes';
	const DELETE_ATTRIBUTES_ALL = 'delete_attributes_all';
	const DELETE_CATEGORIES     = 'delete_categories';
	const DELETE_CATEGORIES_ALL = 'delete_categories_all';

	const PREPARE_FULL_IMPORT   = 'prepare_full_import';
	const START_FULL_IMPORT     = 'start_full_import';
	const GET_INFO              = 'update_info';
	const END_FULL_IMPORT       = 'end_full_import';

	/**
	 * Main queue action types
	 *
	 * @var string[]
	 */
	public static $main_action_types = array(
		self::PREPARE_FULL_IMPORT,
		self::START_FULL_IMPORT,
		self::END_FULL_IMPORT,
	);

	/**
	 * Queue action types
	 *
	 * @var string[]
	 */
	public static $action_types = array(
		self::UPDATE_PAGES,
		self::UPDATE_PRODUCTS,
		self::UPDATE_CATEGORIES,
		self::UPDATE_ATTRIBUTES,

		self::DELETE_PAGES,
		self::DELETE_PAGES_ALL,
		self::DELETE_ATTRIBUTES,
		self::DELETE_ATTRIBUTES_ALL,
		self::DELETE_CATEGORIES,
		self::DELETE_CATEGORIES_ALL,
		self::DELETE_FACETS,
		self::DELETE_FACETS_ALL,
		self::DELETE_PRODUCTS,
		self::DELETE_PRODUCTS_ALL,

		self::PREPARE_FULL_IMPORT,
		self::START_FULL_IMPORT,
		self::END_FULL_IMPORT,
	);

	// Queue statues.
	const STATUS_PENDING    = 'pending';
	const STATUS_PROCESSING = 'processing';

	/**
	 * Queue statuses
	 *
	 * @var string[]
	 */
	public static $status_types = array(
		self::STATUS_PENDING,
		self::STATUS_PROCESSING,
	);

	/**
	 * Self instance
	 *
	 * @var Queue
	 */
	private static $instance = null;

	/**
	 * Returns self instance
	 *
	 * @return Queue
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Checks if update action
	 *
	 * @param string $action Action.
	 *
	 * @return boolean
	 */
	public static function is_update_action( $action ) {
		return in_array(
			$action,
			array(
				self::UPDATE_ATTRIBUTES,
				self::UPDATE_CATEGORIES,
				self::UPDATE_PAGES,
				self::UPDATE_PRODUCTS,
			)
		);
	}

	/**
	 * Checks if delete action
	 *
	 * @param string $action Action.
	 *
	 * @return boolean
	 */
	public static function is_delete_action( $action ) {
		return in_array(
			$action,
			array(
				self::DELETE_ATTRIBUTES,
				self::DELETE_CATEGORIES,
				self::DELETE_FACETS,
				self::DELETE_PAGES,
				self::DELETE_PRODUCTS,
			)
		);
	}

	/**
	 * Checks if delete all action
	 *
	 * @param string $action Action.
	 *
	 * @return boolean
	 */
	public static function is_delete_all_action( $action ) {
		return in_array(
			$action,
			array(
				self::DELETE_ATTRIBUTES_ALL,
				self::DELETE_CATEGORIES_ALL,
				self::DELETE_FACETS_ALL,
				self::DELETE_PAGES_ALL,
				self::DELETE_PRODUCTS_ALL,

			)
		);
	}

	/**
	 * Return Se Api type by action
	 *
	 * @param string $action Action.
	 *
	 * @return string
	 */
	public static function get_api_type_by_action( $action ) {
		switch ( $action ) {
			case self::DELETE_PRODUCTS:
			case self::DELETE_PRODUCTS_ALL:
				return 'items';

			case self::DELETE_CATEGORIES:
			case self::DELETE_CATEGORIES_ALL:
				return 'categories';

			case self::DELETE_PAGES:
			case self::DELETE_PAGES_ALL:
				return 'pages';

			case self::DELETE_FACETS:
			case self::DELETE_FACETS_ALL:
				return 'facets';

			default:
				return '';
		}
	}

	/**
	 * Checks if queue already running
	 *
	 * @param object $q Queue.
	 */
	public static function is_queue_running( $q ) {
		return ! empty( $q )
			&& self::STATUS_PROCESSING == $q->status
			// NOTE: $q['started'] can be in future.
			&& $q->started + Api::get_instance()->get_max_processing_time() > time();
	}

	/**
	 * Checks if queue has errors
	 *
	 * @param object $q Queue.
	 *
	 * @return boolean
	 */
	public static function is_queue_has_error( $q ) {
		return ! empty( $q ) && $q->attempts >= Api::get_instance()->get_max_error_count();
	}

	/**
	 * Get total items in Se Queue
	 *
	 * @return int
	 */
	public function get_total_items() {
		global $wpdb;

		return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}wc_se_queue" ) );
	}

	/**
	 * Clear queue for full import
	 *
	 * @param string $lang_code Lang code.
	 */
	public function prepare_full_import( $lang_code ) {
		global $wpdb;

		if ( empty( $lang_code ) ) {
			return false;
		}

		$lang_code = Api::get_instance()->get_locale_settings( $lang_code );

		return $wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->prefix}wc_se_queue WHERE lang_code = %s AND action <> %s",
				$lang_code,
				self::PREPARE_FULL_IMPORT
			)
		);
	}

	/**
	 * Clear all actions in queue
	 *
	 * @param array $lang_code Lang code.
	 */
	public function clear_actions( $lang_code = null ) {
		global $wpdb;

		if ( empty( $lang_code ) ) {
			$wpdb->query( "TRUNCATE {$wpdb->prefix}wc_se_queue" );
		} else {
			$lang_code = Api::get_instance()->get_locale_settings( $lang_code );
			$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}wc_se_queue WHERE lang_code = %s", $lang_code ) );
		}

		return $this;
	}

	/**
	 * Adds action to queue
	 *
	 * @param string $action    Queue action.
	 * @param mixed  $data      Queue data.
	 * @param string $lang_code Lang code.
	 *
	 * @return boolean
	 */
	public function add_action( $action, $data = null, $lang_code = null ) {
		global $wpdb;

		if (
			! in_array( $action, self::$action_types )
			|| ! Api::get_instance()->check_parent_private_key()
			|| ( ! Api::get_instance()->is_realtime_sync_mode() && ! in_array( $action, self::$main_action_types ) )
		) {
			return false;
		}

		$data = wp_json_encode( $data );
		$data = array( $data );

		$engines = Api::get_instance()->get_engines( $lang_code );

		if ( self::PREPARE_FULL_IMPORT == $action && ! empty( $lang_code ) ) {
			$this->clear_actions( $lang_code );
		}

		foreach ( $data as $d ) {
			foreach ( $engines as $engine ) {
				if ( Api::get_instance()->get_module_status() != 'Y' && ! in_array( $action, self::$main_action_types ) ) {
					continue;
				}

				$lang_code = Api::get_instance()->get_locale_settings( $engine['lang_code'] );

				if ( self::PHRASE != $action ) {
					// Remove duplicated actions.
					$wpdb->query(
						$wpdb->prepare(
							"DELETE FROM {$wpdb->prefix}wc_se_queue WHERE action = %s AND lang_code = %s AND status = %s AND data = %s",
							$action,
							$lang_code,
							'pending',
							$d
						)
					);
				}

				$this->insert_data(
					array(
						'data'      => $d,
						'action'    => $action,
						'lang_code' => $engine['lang_code'],
					)
				);
			}
		}

		return true;
	}

	/**
	 * Insert direct data to queue
	 *
	 * @param array $data Queue data.
	 */
	public function insert_data( $data ) {
		global $wpdb;

		if ( isset( $data['lang_code'] ) ) {
			$data['lang_code'] = Api::get_instance()->get_locale_settings( $data['lang_code'] );
		}

		return $wpdb->insert( $wpdb->prefix . 'wc_se_queue', $data );
	}

	/**
	 * Returns current or next queue
	 *
	 * @param int    $queue_id  Queue ID.
	 * @param string $lang_code Lang code.
	 *
	 * @return object|null
	 */
	public function get_next_queue( $queue_id = null, $lang_code = null ) {
		global $wpdb;

		$queue = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}wc_se_queue
				WHERE queue_id > %d AND (lang_code = %s OR %d)
				ORDER BY priority DESC, queue_id ASC LIMIT 1",
				! empty( $queue_id ) ? $queue_id : 0,
				! empty( $lang_code ) ? Api::get_instance()->get_locale_settings( $lang_code ) : '',
				! empty( $lang_code ) ? 0 : 1
			)
		);

		if ( ! empty( $queue ) ) {
			$queue->lang_code = Api::get_instance()->get_locale( $queue->lang_code );
		}

		return ! empty( $queue ) ? $queue : null;
	}

	/**
	 * Delete queue row
	 *
	 * @param int $queue_id Queue ID.
	 */
	public function delete_queue_by_id( $queue_id ) {
		global $wpdb;

		return $wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->prefix}wc_se_queue WHERE queue_id = %s",
				$queue_id
			)
		);
	}

	/**
	 * Set queue status
	 *
	 * @param int $queue_id Queue ID.
	 */
	public function set_queue_status_processing( $queue_id ) {
		global $wpdb;

		return $wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->prefix}wc_se_queue SET status = %s, started = %d, attempts = attempts + 1 WHERE queue_id = %d",
				self::STATUS_PROCESSING,
				time(),
				$queue_id
			)
		);
	}

	/**
	 * Set error in queue record
	 *
	 * @param int    $queue_id      Queue id.
	 * @param int    $next_try_time Timestamp.
	 * @param string $error         Error.
	 */
	public function set_queue_error_by_id( $queue_id, $next_try_time, $error = '' ) {
		global $wpdb;

		return $wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->prefix}wc_se_queue SET error = %s WHERE queue_id = %d",
				$error,
				$queue_id
			)
		);
	}

	/**
	 * Adds update products to queue to queue
	 *
	 * @param array $product_ids Product ids.
	 *
	 * @return bool
	 */
	public function add_action_update_products( $product_ids ) {
		if ( empty( $product_ids ) ) {
			return false;
		}

		$chunks = array_chunk( (array) $product_ids, SE_PRODUCTS_PER_PASS );

		foreach ( $chunks as $p_ids ) {
			$this->add_action( self::UPDATE_PRODUCTS, $p_ids );
		}

		return true;
	}

	/**
	 * Adds delete product action to quque
	 *
	 * @param int $product_ids Product ids.
	 *
	 * @return bool
	 */
	public function add_action_delete_products( $product_ids ) {
		if ( ! empty( $product_ids ) ) {
			$this->add_action( self::DELETE_PRODUCTS, (array) $product_ids );
		}

		return true;
	}

	/**
	 * Adds update page action to queue
	 *
	 * @param array $page_ids Page ids.
	 *
	 * @return bool
	 */
	public function add_action_update_pages( $page_ids ) {
		if ( empty( $page_ids ) ) {
			return false;
		}

		$chunks = array_chunk( (array) $page_ids, SE_PAGES_PER_PASS );

		foreach ( $chunks as $p_ids ) {
			$this->add_action( self::UPDATE_PAGES, $p_ids );
		}

		return true;
	}

	/**
	 * Adds delete page action to quque
	 *
	 * @param int $page_ids Page Ids.
	 *
	 * @return bool
	 */
	public function add_action_delete_pages( $page_ids ) {
		if ( ! empty( $page_ids ) ) {
			$this->add_action( self::DELETE_PAGES, (array) $page_ids );
		}

		return true;
	}

	/**
	 * Adds update category action to queue
	 *
	 * @param array $category_ids Category Ids.
	 *
	 * @return bool
	 */
	public function add_action_update_category( $category_ids ) {
		if ( empty( $category_ids ) ) {
			return false;
		}

		$chunks = array_chunk( (array) $category_ids, SE_CATEGORIES_PER_PASS );
		foreach ( $chunks as $p_ids ) {
			$this->add_action( self::UPDATE_CATEGORIES, $p_ids );
		}

		return true;
	}

	/**
	 * Adds delete category action to quque
	 *
	 * @param int $category_ids Category Ids.
	 */
	public function add_action_delete_categories( $category_ids ) {
		if ( ! empty( $category_ids ) ) {
			$this->add_action( self::DELETE_CATEGORIES, (array) $category_ids );
		}
	}

	/**
	 * Adds update attribute action to queue
	 */
	public function add_action_update_attributes() {
		$this->add_action( self::UPDATE_ATTRIBUTES );
	}

	/**
	 * Adds delete facet action to queue
	 *
	 * @param string|array $names Facets names.
	 */
	public function add_action_delete_facets( $names ) {
		if ( ! empty( $names ) ) {
			$this->add_action( self::DELETE_FACETS, (array) $names );
		}
	}

	/**
	 * Check if queue is OK
	 *
	 * @return boolean
	 */
	public function get_queue_status() {
		$q = $this->get_next_queue();

		if ( empty( $q ) ) {
			return true;
		}

		if ( self::is_queue_has_error( $q ) ) {
			// Maximum attemps reached.
			$status = false;
		} elseif ( $q->started > 0 && $q->started + HOUR_IN_SECONDS < time() ) {
			// Queue item processed more than one hour.
			$status = false;
		} else {
			$status = true;
		}

		return $status;
	}
}
