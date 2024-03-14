<?php  // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * CRUD for search data
 *
 * @property string primary_key
 * @property string table_name
 */
class EPHD_Search_DB extends EPHD_DB {

	const PER_PAGE = 20;

	const PRIMARY_KEY = 'search_id';
	const WIDGET_NAME_LENGTH = 200;  // this has the same length as 'name' field inside the WordPress terms table

	/**
	 * Get things started
	 *
	 * @access  public
	 */
	public function __construct() {

		parent::__construct();

		global $wpdb;

		$this->table_name  = $wpdb->prefix . 'ephd_search';
		$this->primary_key = self::PRIMARY_KEY;
	}

	/**
	 * Get columns and formats
	 *
	 * @access  public
	 */
	public function get_column_format() {
		return array(
			'search_id'      => '%d',
			'widget_id'      => '%d',
			'widget_name'    => '%s',
			'page_id'        => '%d',
			'user_id'        => '%d',
			'submit_date'    => '%s',
			'user_input'     => '%s',
			'keywords'       => '%s',
			'found_faqs'     => '%d',
			'found_posts'    => '%d',
			'found_articles' => '%d',
		);
	}

	/**
	 * Get default column values
	 *
	 * @return array
	 */
	public function get_column_defaults() {
		return array(
			'widget_id'         => 0,
			'widget_name'       => '',
			'page_id'           => 0,
			'user_input'        => '',
			'keywords'          => '',
			'submit_date'       => date( 'Y-m-d H:i:s' ),
			'found_faqs'        => 0,
			'found_posts'       => 0,
			'found_articles'    => 0,
		);
	}

	/**
	 * Insert a new Search record
	 *
	 * @param $widget_id
	 * @param $widget_name
	 * @param $page_id
	 * @param $user_id
	 * @param $user_input
	 * @param $keywords
	 * @param $found_faqs
	 * @param $found_posts
	 * @param $found_articles
	 *
	 * @return int|WP_Error - WP_Error or record ID
	 */
	public function insert_search_record( $widget_id, $widget_name, $page_id, $user_id, $user_input, $keywords, $found_faqs, $found_posts, $found_articles ) {

		// insert the record
		$record = array(
			'widget_id'             => $widget_id,
			'widget_name'           => $widget_name,
			'page_id'               => $page_id,
			'user_id'               => $user_id,
			'user_input'            => $user_input,
			'keywords'              => $keywords,
	        'found_faqs'            => $found_faqs,
		    'found_posts'           => $found_posts,
		    'found_articles'        => $found_articles,
		);

		$search_id = parent::insert_record( $record );
		if ( empty($search_id) ) {
			return new WP_Error( 'db-insert-error', 'Could not insert Help Dialog Search record' );
		}

		return $search_id;
	}

	/**
	 * Get list of search's fields with their translated titles which need to display as columns
	 *
	 * @return array
	 */
	public static function get_search_column_fields() {
		return [
			'submit_date'   		    => __( 'Date', 'help-dialog' ),
			'user_id'                   => __( 'User ID', 'help-dialog' ),
			'widget_name'               => __( 'Widget Name', 'help-dialog' ),
			'keywords'                  => __( 'Keywords', 'help-dialog' ),
		];
	}

	/**
	 * Filter output of searches
	 *
	 * @param $searches
	 *
	 * @return array
	 */
	private function filter_output( $searches ) {

		foreach ( $searches as $index => $data ) {

			// change format of showing search datetime
			$searches[$index]->submit_date = date( 'M j, Y @ g:ia', strtotime( $data->submit_date ) );
		}

		return $searches;
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
	                search_id 		BIGINT(20) NOT NULL AUTO_INCREMENT,
	                widget_id       INT(20) NOT NULL,
	                widget_name 	VARCHAR(" . self::WIDGET_NAME_LENGTH . ") NOT NULL,
	                page_id         BIGINT(20) NOT NULL,
	                user_id         INT(20) NOT NULL,
	                submit_date     datetime NOT NULL,
	                user_input      varchar(150) NOT NULL,
	                keywords        varchar(150) NOT NULL,
	                found_faqs      SMALLINT(20) NOT NULL,
		            found_posts     SMALLINT(20) NOT NULL,
		            found_articles  SMALLINT(20) NOT NULL,
	                PRIMARY KEY  (search_id),
	                KEY ix_ephd_date (submit_date),
	                KEY ix_ephd_keywords (keywords)
		) " . $collate . ";";

		dbDelta( $sql );
	}
}