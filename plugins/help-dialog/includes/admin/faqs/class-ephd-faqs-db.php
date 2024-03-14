<?php  // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * CRUD for Help Dialog FAQs data
 *
 * @property string primary_key
 * @property string table_name
 */
class EPHD_FAQs_DB extends EPHD_DB {

	const PRIMARY_KEY = 'id';

	const FAQ_TITLE_LENGTH = 500;
	const FAQ_CONTENT_LENGTH = 5000;

	const STATUS_DRAFT = 'draft';
	const STATUS_PRIVATE = 'private';
	const STATUS_PUBLISH = 'publish';

	protected $table_name;
	protected $primary_key;
	protected $current_lang;
	protected $default_lang;

	/**
	 * Get things started
	 *
	 * @access  public
	 */
	public function __construct() {

		parent::__construct();

		global $wpdb;

		$this->table_name  = $wpdb->prefix . 'ephd_faqs';
		$this->primary_key = self::PRIMARY_KEY;
		$this->current_lang = EPHD_Multilang_Utilities::get_current_language();
		$this->default_lang = EPHD_Multilang_Utilities::get_default_language();
	}

	/**
	 * Get columns and formats
	 *
	 * @access  public
	 */
	public function get_column_format() {
		return array(
			'id'            => '%d',
			'faq_id'        => '%d',
			'question'      => '%s',
			'answer'        => '%s',
			'lang'          => '%s',
			'status'        => '%s',
			'date_modified' => '%s',
		);
	}

	/**
	 * Get default column values
	 *
	 * @return array
	 */
	public function get_column_defaults() {
		return array(
			'id'            => 0,
			'faq_id'        => 0,
			'question'      => '',
			'answer'        => '',
			'lang'          => '',
			'status'        => self::STATUS_DRAFT,
			'date_modified' => date('Y-m-d H:i:s'),
		);
	}

	/**
	 * Insert a new FAQ
	 *
	 * @param $id
	 * @param $faq_id
	 * @param $question
	 * @param $answer
	 * @param $status
	 * @param $lang
	 *
	 * @return object|false|WP_Error
	 */
	public function insert_faq( $id, $faq_id, $question, $answer, $status, $lang=false ) {

		// check user capabilities
		if ( ! current_user_can( 'edit_posts' ) ) {
			return false;
		}

		if ( empty( $lang ) ) {
			$lang = $this->current_lang;
		}

		// insert the record
		$record = array(
			'id'            => $id,
			'faq_id'        => $faq_id,
			'question'      => $question,
			'answer'        => $answer,
			'lang'          => $lang,
			'status'        => $status,
		);

		$id = parent::insert_record( $record );
		if ( empty( $id ) ) {
			return new WP_Error( 'db-insert-error', 'Could not insert Help Dialog FAQ record' );
		}

		// Set faq_id to the first id in the group
		if ( empty( $faq_id ) ) {
			$update_result = parent::update_record( $id, array( 'faq_id' => $id ) );
			if ( empty( $update_result ) ) {
				return new WP_Error( 'db-update-error', 'Could not update Help Dialog FAQ record: ' . $id );
			}
		}

		$faq = parent::get_by_primary_key( $id );
		if ( empty( $faq ) || is_wp_error( $faq ) ) {
			return is_wp_error( $faq ) ? $faq: new WP_Error( 'db-insert-error', 'Could not insert Help Dialog FAQ record.' );
		}

		return $faq;
	}

	/**
	 * Update FAQ record
	 *
	 * @param $id
	 * @param $question
	 * @param $answer
	 * @param $status
	 *
	 * @return object|false|WP_Error
	 */
	public function update_faq( $id, $question, $answer, $status ) {

		// check user capabilities
		if ( ! current_user_can( 'edit_posts' ) ) {
			return false;
		}

		// update the record
		$data = array(
			'question' => $question,
			'answer'   => $answer,
			'status'   => $status,
		);

		$result = parent::update_record( $id, $data );
		if ( empty( $result ) ) {
			return new WP_Error( 'db-update-error', 'Could not update Help Dialog FAQ record: ' . $id );
		}

		$faq = parent::get_by_primary_key( $id );
		if ( empty( $faq ) || is_wp_error( $faq ) ) {
			return is_wp_error( $faq ) ? $faq: new WP_Error( 'db-insert-error', 'Could not insert Help Dialog FAQ record.' );
		}

		return $faq;
	}

	/**
	 * Get FAQ question by faq_id
	 *
	 * @param $faq_id
	 *
	 * @return string
	 */
	public function get_faq_question_by_id( $faq_id ) {

		// get FAQs in all languages
		$faqs = $this->get_faq_with_all_lang_by_id( $faq_id );
		if ( is_wp_error( $faqs ) || empty ( $faqs ) ) {
			return '';
		}

		// get FAQ for current language
		foreach ( $faqs as $faq ) {
			if ( $faq->lang == $this->current_lang ) {
				return $faq->question;
			}
		}

		// return first FAQ (any language)
		return isset( $faqs[0]->question ) ? $faqs[0]->question : '';
	}

	/**
	 * Get FAQs by faq_ids  (current language)
	 *
	 * @param $faq_ids
	 *
	 * @return array|WP_Error
	 */
	public function get_faqs_by_ids( $faq_ids ) {
		global $wpdb;

		if ( empty( $faq_ids ) ) {
			return [];
		}

		// where clause for FAQs ids
		$where_clause = 'faq_id IN (';
		foreach ( $faq_ids as $faq_id ) {
			$where_clause .= $wpdb->prepare( '%d', $faq_id ) . ',';
		}
		$where_clause = rtrim( $where_clause, ',' ); // remove last comma
		$where_clause .= ')';

		// language filter
		$where_clause .= ' AND ( ' . $wpdb->prepare( 'lang = %s', $this->current_lang );

		// get current and default language for admin panel
		$where_clause .= is_admin() ? ' OR ' . $wpdb->prepare( 'lang = %s', $this->default_lang ) . ' ) ' : ' )';

		// status filter
		$allowed_statuses = self::get_allowed_statuses();
		$where_clause .= ' AND (';
		$count = 0;
		foreach ( $allowed_statuses as $allowed_status ) {
			$where_clause .= $wpdb->prepare( ' status = %s ', $allowed_status );
			$where_clause .= count( $allowed_statuses ) > ++$count ? ' OR ' : ') ';
		}

		$faqs_obj = parent::get_rows_by_where_clause( [], $where_clause ); //$wpdb->get_results( "SELECT * FROM {$this->table_name} WHERE {$where_clause}" );
		if ( $faqs_obj === null || is_wp_error( $faqs_obj ) ) {
			return new WP_Error( 'db-get-multiple-faqs-error', "Can't find FAQs by Group IDs: " . $faq_ids );
		}

		// for admin panel: return FAQs with current language if it exists, otherwise default language
		if ( is_admin() ) {
			$admin_faqs_obj = [];

			foreach ( $faqs_obj as $faq_obj ) {
				if ( empty( $admin_faqs_obj[$faq_obj->faq_id] ) || $faq_obj->lang == $this->current_lang ) {
					$admin_faqs_obj[$faq_obj->faq_id] = $faq_obj;
				}
			}
			$faqs_obj = $admin_faqs_obj;
		}

		return $faqs_obj;
	}

	/**
	 * Get all FAQs (current language)
	 *
	 * @return array
	 */
	public function get_all_faqs() {
		global $wpdb;

		// language filter
		$where_clause = $wpdb->prepare( '( lang = %s', $this->current_lang );

		// get current and default language for admin panel
		$where_clause .= is_admin() ? ' OR ' . $wpdb->prepare( 'lang = %s', $this->default_lang ) . ' ) ' : ' )';

		// status filter
		$allowed_statuses = self::get_allowed_statuses();
		$where_clause .= ' AND (';
		$count = 0;
		foreach ( $allowed_statuses as $allowed_status ) {
			$where_clause .= $wpdb->prepare( ' status = %s ', $allowed_status );
			$where_clause .= count( $allowed_statuses ) > ++$count ? ' OR ' : ') ';
		}

		$faqs_obj = parent::get_rows_by_where_clause( [], $where_clause );
		if ( is_wp_error( $faqs_obj ) ) {
			return $faqs_obj;
		}
		if ( empty( $faqs_obj ) ) {
			return [];
		}

		// for admin panel: return FAQs with current language if it exists, otherwise default language
		if ( is_admin() ) {
			$admin_faqs_obj = [];

			foreach ( $faqs_obj as $faq_obj ) {
				if ( empty( $admin_faqs_obj[$faq_obj->faq_id] ) || $faq_obj->lang == $this->current_lang ) {
					$admin_faqs_obj[$faq_obj->faq_id] = $faq_obj;
				}
			}
			$faqs_obj = $admin_faqs_obj;
		}

		return $faqs_obj;
	}

	/**
	 * Get one FAQ and all its language translations by faq_id
	 *
	 * @param $faq_id
	 *
	 * @return array|WP_Error
	 */
	public function get_faq_with_all_lang_by_id( $faq_id ) {
		global $wpdb;

		// language filter
		$where_clause = $wpdb->prepare( 'faq_id = %d', $faq_id );

		// status filter
		$allowed_statuses = self::get_allowed_statuses();
		$where_clause .= ' AND (';
		$count = 0;
		foreach ( $allowed_statuses as $allowed_status ) {
			$where_clause .= $wpdb->prepare( ' status = %s ', $allowed_status );
			$where_clause .= count( $allowed_statuses ) > ++$count ? ' OR ' : ') ';
		}

		$faqs = parent::get_rows_by_where_clause( [], $where_clause );
		if ( $faqs === null || is_wp_error( $faqs ) ) {
			return new WP_Error( 'db-get-one-faq-error', "Can't find FAQ by Group ID: " . $faq_id );
		}

		return $faqs;
	}

	/**
	 * Delete FAQ by faq_id (all language)
	 *
	 * @param $faq_id
	 * @return bool
	 */
	public function delete_faq_by_id( $faq_id ) {

		// check user capabilities
		if ( ! current_user_can( 'delete_posts' ) ) {
			return false;
		}

		$faqs = $this->get_faq_with_all_lang_by_id( $faq_id );
		if ( is_wp_error( $faqs ) || empty( $faqs ) ) {
			EPHD_Logging::add_log( "Can't find FAQ Group ID: " . $faq_id );
			return false;
		}

		foreach ( $faqs as $faq ) {
			$deleted = parent::delete_record( $faq->id );
			if ( empty( $deleted ) ) {
				EPHD_Logging::add_log( "Can't delete FAQs records. FAQ ID: " . $faq->id );
				return false;
			}
		}

		return true;
	}

	/**
	 * Get allowed statuses for current user
	 *
	 * @return array
	 */
	private static function get_allowed_statuses() {

		// Publish FAQs - for all users
		$statuses = array( self::STATUS_PUBLISH );

		// Private FAQs - based on global['private_faqs_included_roles] settings
		if ( self::can_user_read_private_faqs() ) {
			$statuses[] = self::STATUS_PRIVATE;
		}

		// Draft FAQs - for admins only
		if ( EPHD_Utilities::is_user_admin() ) {
			$statuses[] = self::STATUS_DRAFT;
		}

		return $statuses;
	}

	/**
	 * Is current user roles included for reading private FAQs
	 *
	 * @return bool
	 */
	private static function can_user_read_private_faqs() {

		// retrieve global configuration
		$global_config = ephd_get_instance()->global_config_obj->get_config( true );
		if ( is_wp_error( $global_config ) ) {
			EPHD_Logging::add_log( 'Failed to load global config', $global_config );
			return false;
		}

		$current_user = EPHD_Utilities::get_current_user();
		if ( empty( $current_user ) ) {
			return false;
		}

		$current_user_read_private_faqs_roles = array_intersect( $global_config['private_faqs_included_roles'], $current_user->roles );

		return ! empty( $current_user_read_private_faqs_roles );
	}

	/**
	 * Get list of FAQs statuses with their translated titles which need to display
	 *
	 * @return array
	 */
	public static function get_faq_statuses() {
		return [
			'draft'   => __( 'Draft', 'help-dialog' ),
			'public'  => __( 'Public', 'help-dialog' ),
			'private' => __( 'Private', 'help-dialog' ),
		];
	}

	/**
	 * Filter output of FAQs
	 *
	 * @param $faqs
	 *
	 * @return array
	 */
	private function filter_output( $faqs ) {
		return $faqs;
	}

	/**
	 * Create the table
	 *
	 * @access  public
	 */
	public function create_table() {
		global $wpdb;

		$collate = $wpdb->has_cap( 'collation' ) ? $wpdb->get_charset_collate() : '';

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " (
					id 		      BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
					faq_id        BIGINT(20) UNSIGNED NOT NULL,
					question      VARCHAR(" . self::FAQ_TITLE_LENGTH . ") NOT NULL,
					answer        VARCHAR(" . self::FAQ_CONTENT_LENGTH . ") NOT NULL,
					lang          VARCHAR(10) NOT NULL,
					status        VARCHAR(10) NOT NULL,
					date_modified DATETIME NOT NULL,
	                PRIMARY KEY (id),               
	                KEY ix_ephd_lang_status (faq_id, lang, status)
		) " . $collate . ";";

		dbDelta( $sql );
	}
}
