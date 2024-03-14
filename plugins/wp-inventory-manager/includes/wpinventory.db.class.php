<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract Database class for accessing inventory data
 * @author    WP Inventory Manager
 * @package   WPInventory
 * @copyright 2013 - WP Inventory Manager
 */
class WPIMDB extends WPIMCore {

	private static $instance = NULL;

	private static $fresh_install = FALSE;

	/**
	 * @var wpdb
	 */
	protected $wpdb;
	protected $table_prefix;

	/**
	 * Name of Core inventory table.  Stores inventory items.
	 * @var string
	 */
	public $inventory_table;

	/**
	 * Name of category table.  Stores category names.
	 * @var string
	 */
	public $category_table;

	/**
	 * Name of inventory images table.  Stores images for items.  Supports multiple images per item.
	 * @var string
	 */
	public $image_table;

	/**
	 * Name of inventory media table.  Stores items such as PDF's, docs, etc.  Supports multiple media per item.
	 * @var string
	 */
	public $media_table;

	/**
	 * Name of inventory status table.  Allows for various statii to be supported.  This version only supports active /
	 * inactive
	 * @var string
	 */
	public $status_table;

	/**
	 * Name of inventory labels table.  Allows for custom field labels / renaming.  This version only supports custom
	 * labels, not types.
	 * @var string
	 */
	public $label_table;

	/**
	 * Name of table that stores records of reservations made.
	 * @var string
	 */
	public $reservation_table;

	/**
	 * Name of table that stores the items for a given reservation.
	 * @var string
	 */
	public $reservation_item_table;

	public $category_fields = [
		'category_id',
		'category_name',
		'category_description',
		'category_slug',
		'category_sort_order'
	];

	/**
	 * Constructor magic method.
	 */
	public function __construct() {
		global $wpdb;
		global $table_prefix;
		$this->wpdb         = $wpdb;
		$this->table_prefix = $table_prefix;

		self::$config = WPIMConfig::getInstance();

		$this->set_table_names();
		$this->check_tables();
	}

	public static function getInstance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * This is here purely to prevent someone from cloning the class
	 */
	private function __clone() {
	}

	/**
	 * Define the names of the tables used throughout
	 */
	public function set_table_names() {
		global $table_prefix;
		$this->inventory_table        = $table_prefix . 'wpinventory_item';
		$this->category_table         = $table_prefix . 'wpinventory_category';
		$this->image_table            = $table_prefix . 'wpinventory_image';
		$this->media_table            = $table_prefix . 'wpinventory_media';
		$this->status_table           = $table_prefix . 'wpinventory_status';
		$this->label_table            = $table_prefix . 'wpinventory_label';
		$this->reservation_table      = $table_prefix . 'wpinventory_reservation';
		$this->reservation_item_table = $table_prefix . 'wpinventory_reservation_item';
		// Types is available in pro version
		// Ledger / history table / structure is available in pro version
		// Additional / custom fields available in pro version
	}

	/**
	 * Ensures that the SEO slug is unique
	 *
	 * @param string  $type - inventory|category
	 * @param string  $slug
	 * @param string  $name
	 * @param integer $id
	 *
	 * @return string
	 */
	public function validate_slug( $type, $slug, $name, $id ) {
		if ( $id && ! $slug ) {
			$slug = $this->wpdb->get_var( $this->wpdb->prepare( 'SELECT inventory_slug FROM ' . $this->inventory_table . ' WHERE inventory_id = %d', $id ) );
		}

		if ( ! $slug ) {
			$slug = str_replace( ' ', '-', $name );
			$slug = strtolower( preg_replace( '/[^\da-z_-]/i', '', $name ) );
		}

		// Protect against no name / slug passed in....
		if ( ! $slug ) {
			$slug = ( 'category' !== $type ) ? 'item' : 'category';
		}

		$inv_exists_id = $this->wpdb->get_var( $this->wpdb->prepare( 'SELECT inventory_id
			FROM ' . $this->inventory_table . ' 
			WHERE inventory_slug = %s AND inventory_id <> %d', $slug, $id ) );

		$cat_exists_id = $this->wpdb->get_var( $this->wpdb->prepare( 'SELECT category_id
			FROM ' . $this->category_table . '
			WHERE category_slug = %s AND category_id <> %d', $slug, $id ) );


		if ( $inv_exists_id || $cat_exists_id ) {
			$slug .= '-' . $id;
		}

		return sanitize_text_field($slug);
	}

	public function wpdb() {
		return $this->wpdb;
	}

	public function query( $sql ) {
		return $this->wpdb->query( $sql );
	}

	public function get_results( $sql ) {
		return $this->wpdb->get_results( $sql );
	}

	public function get_var( $sql ) {
		return $this->wpdb->get_var( $sql );
	}

	public function prepare( $sql, $args ) {
		$args = func_get_args();
		array_shift( $args );

		return $this->wpdb->prepare( $sql, $args );
	}

	/**
	 * Check for sort column clicked, and verify as legitimate field
	 *
	 * @param string $order        - the default order
	 * @param array  $fields       - array of allowed fields name
	 * @param bool   $return_array - flag whether to return array or string
	 *
	 * @return string
	 */
	public function parse_sort( $order, $fields, $return_array = FALSE ) {

		$fields = apply_filters( 'wpim_allowed_sort_fields', $fields );

		$sortby          = self::request('sortby', '');
		$default_sortdir = 'ASC';
		if ( is_scalar( $order ) && ( stripos( $order, '_date_' ) !== FALSE || stripos( $sortby, '_date_' ) !== FALSE ) ) {
			$default_sortdir = 'DESC';
		};

		$sortdir = self::request('sortdir', $default_sortdir);

		// This ensures that the $_GET arguments override the shortcode arguments
		if ( $sortby ) {
			if ( in_array( $sortby, (array) $fields ) ) {
				$order = $sortby;
			}
		}

		if ( ! is_array( $order ) && stripos( $order, ',' ) !== FALSE ) {
			$order = explode( ',', $order );
		}

		if ( is_array( $order ) ) {
			$order = array_map( 'trim', $order );
		} else {
			$order = trim( $order );
		}

		$new_order = [];

		foreach ( (array) $order AS $sby ) {
			$sdir = '';
			if ( FALSE !== stripos( $sby, ' DESC' ) ) {
				$sdir = ' DESC';
				$sby  = str_ireplace( ' DESC', '', $sby );
			} else if ( FALSE !== stripos( $sby, ' ASC' ) ) {
				$sdir = ' ASC';
				$sby  = str_ireplace( ' ASC', '', $sby );
			}

			if ( in_array( $sby, (array) $fields ) ) {
				$new_order[] = $sby . $sdir;
			} else if ( in_array( 'inventory_' . $sby, (array) $fields ) ) {
				$new_order[] = 'inventory_' . $sby . $sdir;
			} else if ( 'category_name' == $sby || 'category_id' == $sby ) {
				$new_order[] = 'category_name' . $sdir;
			} else if ( in_array( $sby, $this->category_fields ) ) {
				$new_order[] = $sby . $sdir;
			}
		}

		$numeric = self::labels()->get_numeric();
		$numeric = apply_filters( 'wpim_parse_sort_numeric', $numeric );

		$new_order = (array) $new_order;
		foreach ( $new_order AS $i => $field ) {
			$bits      = explode( ' ', $field );
			$field     = $bits[0];
			$this_sdir = ( ! empty( $bits[1] ) ) ? $bits[1] : '';

			if ( 'category_id' == $field ) {
				$new_order[ $i ] = 'category_name';
			}

			if ( in_array( $field, $numeric ) ) {
				$new_order[ $i ] = 'CAST(`' . $field . '` AS DECIMAL)';
				// Doesn't work.
//				$new_order[ $i ] = "{$field} REGEXP '^\d*[^\da-z&\.\' \-\"\!\@\#\$\%\^\*\(\)\;\:\\,\?\/\~\`\|\_\-]' {$this_flip_sby}, {$field} + 0 {$this_sby}, {$field} {$this_sby}";
				// Uses up too much memory, causes error
//				$new_order[ $i ] = "udf_NaturalSortFormat({$field}, 10, '.'){$this_sdir}";
			}
		}

		$new_order = apply_filters( 'wpim_parse_order', $new_order );

		$order = implode( ',', $new_order );
		$order = str_ireplace( 'category_id', 'category_name', $order );

		$dir = '';
		if ( ! preg_match( "/(ASC|DESC)$/i", $order ) ) {
			$dir = ( strtolower( trim( $sortdir ) ) == 'desc' ) ? ' DESC' : '';
		}

		$order = [
			'order' => $order,
			'dir'   => $dir
		];

		self::$sortby = trim( implode( ' ', $order ) );

		return ( ! $return_array ) ? trim( implode( ' ', $order ) ) : $order;
	}

	/**
	 * Convert date / time to mysql format
	 *
	 * @param mixed $date
	 * @param mixed $time
	 *
	 * @return string
	 */
	protected function date_to_mysql( $date, $time = FALSE ) {
		if ( ! is_numeric( $date ) ) {
			$date = strtotime( $date );
		}

		$format = ( $time ) ? 'Y-m-d H:i:s' : 'Y-m-d';

		return date( $format, $date );
	}

	/**
	 * Parse an array of rows of data from the database
	 *
	 * @param array $records
	 *
	 * @return array
	 */
	public function parseFromDb( $records ) {
		if ( is_array( $records ) ) {
			foreach ( $records AS $key => $row ) {
				$records[ $key ] = $this->parseRowFromdb( $row );
			}
		} else if ( is_object( $records ) ) {
			$records = $this->parseRowFromDb( $records );
		}

		return $records;
	}

	/**
	 * Prepare a single row of data
	 *
	 * @param object|array $row
	 *
	 * @return array|object
	 */
	public function parseRowFromDb( $row ) {
		$is_object = ( is_object( $row ) ) ? TRUE : FALSE;
		$row       = (array) $row;

		return ( $is_object ) ? (object) $row : $row;
	}

	/**
	 * Determine if the necessary tables exist, and if not, create them
	 */
	public function activate_plugin() {
		$db = new self;
		$db->check_tables();
	}

	private function check_tables() {
		// Database updates.  Incremented and tracked by version
		$inventory_version = self::$config->get( 'version', 0 );

		// Initial Install - set up tables
		if ( ! (float) $inventory_version ) {

			self::$fresh_install = TRUE;

			$tables = $this->getDBTables();

			// Check for existence of main inventory table
			if ( ! in_array( $this->inventory_table, $tables ) ) {
				$sql = "CREATE TABLE IF NOT EXISTS `" . $this->inventory_table . "` (
				  `inventory_id` INT(11) NOT NULL AUTO_INCREMENT,
				  `inventory_number` TEXT NOT NULL,
				  `inventory_name` TEXT NULL,
				  `inventory_description` TEXT NULL,
				  `inventory_size` TEXT NULL,
				  `inventory_manufacturer` TEXT NULL,
				  `inventory_make` TEXT NULL,
				  `inventory_model` TEXT NULL,
				  `inventory_year` TEXT NULL,
				  `inventory_serial` TEXT NULL,
				  `inventory_fob` TEXT NULL,
				  `inventory_quantity` INT(11) NOT NULL DEFAULT 0,
				  `inventory_quantity_reserved` INT(11) NOT NULL DEFAULT 0,
				  `inventory_price` FLOAT DEFAULT NULL,
				  `status_id` INT(11) NOT NULL DEFAULT 0,
				  `inventory_slug` VARCHAR(255) NULL,
				  `inventory_sort_order` INT(11) NOT NULL DEFAULT 0,
				  `category_id` INT(11) NOT NULL DEFAULT 1,
				  `user_id` INT(11) NOT NULL,
				  `inventory_date_added` DATETIME NULL,
				  `inventory_date_updated` DATETIME NULL,
				  PRIMARY KEY (`inventory_id`)
				);";
				$this->wpdb->query( $sql );
			}

			// Check for existence of category table
			if ( ! in_array( $this->category_table, $tables ) ) {
				$sql = "CREATE TABLE IF NOT EXISTS `" . $this->category_table . "` (
				`category_id` INT(11) NOT NULL AUTO_INCREMENT,
				`category_name` VARCHAR(255) NOT NULL,
				`category_description` VARCHAR(255) NOT NULL,
				`category_slug` VARCHAR(255) NULL,
				`category_sort_order` INT(11) NOT NULL DEFAULT 0,
				PRIMARY KEY (`category_id`)
				);";
				$this->wpdb->query( $sql );
			}

			// Check for existence of image table
			if ( ! in_array( $this->image_table, $tables ) ) {
				$sql = "CREATE TABLE IF NOT EXISTS `" . $this->image_table . "` (
				`image_id` INT(11) NOT NULL AUTO_INCREMENT,
				`inventory_id` INT(11) NOT NULL,
				`post_id` VARCHAR(255) NOT NULL,
				`image` TEXT NOT NULL,
				`thumbnail` TEXT NOT NULL,
				`medium` TEXT NOT NULL,
				`large` TEXT NOT NULL,
				`image_sort_order` INT(11) NOT NULL DEFAULT 0,
				PRIMARY KEY (`image_id`),
				KEY `inventory_id` (`inventory_id`),
				KEY `post_id` (`post_id`)
				);";
				$this->wpdb->query( $sql );
			}

			// Check for existence of media table
			if ( ! in_array( $this->media_table, $tables ) ) {
				$sql = "CREATE TABLE IF NOT EXISTS `" . $this->media_table . "` (
				`media_id` INT(11) NOT NULL AUTO_INCREMENT,
				`inventory_id` INT(11) NOT NULL,
				`media_title` VARCHAR(255) NOT NULL,
				`media` TEXT NOT NULL,
				`media_sort_order` INT(11) NOT NULL DEFAULT 0,
				PRIMARY KEY (`media_id`),
				KEY `inventory_id` (`inventory_id`)
				);";
				$this->wpdb->query( $sql );
			}

			// Check for existence of status table
			if ( ! in_array( $this->status_table, $tables ) ) {
				$sql = "CREATE TABLE IF NOT EXISTS `" . $this->status_table . "` (
				`status_id` INT(11) NOT NULL AUTO_INCREMENT,
				`status_name` VARCHAR(255) NOT NULL,
				`status_description` VARCHAR(255) NOT NULL,
				`status_sort_order` INT(11) NOT NULL DEFAULT 0,
				PRIMARY KEY (`status_id`)
				);";
				$this->wpdb->query( $sql );
			}

			self::$config->set( 'version', '0.1' );
		}

		/*
		 * Version 0.2
		 *  - Add label fields
		 */
		if ( version_compare( $inventory_version, '0.0.2' ) < 0 ) {

			$tables = $this->getDBTables();

			// Check for existence of status table
			if ( ! in_array( $this->label_table, $tables ) ) {
				$sql = "CREATE TABLE IF NOT EXISTS `" . $this->label_table . "` (
				`label_id` INT(11) NOT NULL AUTO_INCREMENT,
				`label_field` VARCHAR(255) NOT NULL,
				`label_label` VARCHAR(255) NOT NULL,
				PRIMARY KEY (`label_id`)
				);";
				$this->wpdb->query( $sql );
			}
			self::$config->set( 'version', 0.2 );
		}

		/*
			* Version 0.3
			*  - Add status fields to inventory table
			*/
		if ( version_compare( $inventory_version, '0.0.3' ) < 0 ) {
			$sql = "ALTER TABLE `" . $this->inventory_table . "`
   				ADD COLUMN inventory_status INT(11) NOT NULL DEFAULT 1
   				";
			$this->wpdb->query( $sql );
			self::$config->set( 'version', 0.3 );
		}

		/*
			* Version 0.4
			*  - Add in_use field to label table
			*/
		if ( version_compare( $inventory_version, '0.0.4' ) < 0 ) {
			$sql = "ALTER TABLE `" . $this->label_table . "`
   			ADD COLUMN is_used TINYINT(4) NOT NULL DEFAULT 1
   			";
			$this->wpdb->query( $sql );
			self::$config->set( 'version', 0.4 );
		}

		/*
		 * Version 1.0.8
		 * - Ack.  Convert to utf-8 - Greek customer having issues!
		 */
		if ( version_compare( $inventory_version, '1.0.8' ) < 0 ) {

			// Make a backup
			$sql = "CREATE TABLE " . $this->inventory_table . "_backup LIKE " . $this->inventory_table;
			$this->wpdb->query( $sql );

			// Update to ut8
			$sql = "INSERT " . $this->inventory_table . "_backup SELECT * FROM " . $this->inventory_table;
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->inventory_table . " CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->inventory_table . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->category_table . " CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->category_table . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->image_table . " CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->image_table . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->label_table . " CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->label_table . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->media_table . " CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->media_table . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->status_table . " CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->status_table . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "DROP TABLE " . $this->inventory_table . '_backup';
			$this->wpdb->query( $sql );

			self::$config->set( 'version', '1.0.8' );
		}

		/*
   		 * Version 1.1.6
   		 *  - Add numeric field to label table (to indicate if should be sorted numerically)
   		 */
		if ( version_compare( $inventory_version, '1.1.6' ) < 0 ) {
			$sql = "ALTER TABLE `" . $this->label_table . "`
   			ADD COLUMN is_numeric TINYINT(4) NOT NULL DEFAULT 0
   			";
			$this->wpdb->query( $sql );
			self::$config->set( 'version', '1.1.6' );
		}

		/*
   		 * Version 1.2.2
   		 *  - Add status "inactive" field to properly integrate status control
		 *  - Insert statuses, update items
		 *  - Remove vestigial status_id column
   		 */
		if ( version_compare( $inventory_version, '1.2.2' ) < 0 ) {
			$sql = "ALTER TABLE `" . $this->status_table . "`
   			ADD COLUMN is_active TINYINT(4) NOT NULL DEFAULT 0
   			";
			$this->wpdb->query( $sql );

			$sql = "INSERT INTO `" . $this->status_table . "` (status_id, status_name, status_description, status_sort_order, is_active)
			 VALUES (1, 'Inactive', 'Inactive (default status)', 0, 0),
			  (2, 'Active', 'Active (default status)', 1, 1)";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE `" . $this->inventory_table . "` DROP COLUMN `status_id`";
			$this->wpdb->query( $sql );

			$sql = "UPDATE `" . $this->inventory_table . "` SET inventory_status = 2";
			$this->wpdb->query( $sql );

			self::$config->set( 'version', '1.2.2' );
		}


		if ( version_compare( $inventory_version, '1.2.3' ) < 0 ) {
			$sql = "ALTER TABLE " . $this->inventory_table . " ADD INDEX `category_id` (`category_id`)";
			$this->wpdb->query( $sql );

			self::$config->set( 'version', '1.2.3' );
		}

		if ( version_compare( $inventory_version, '1.2.4' ) < 0 ) {
			$sql = "ALTER TABLE " . $this->inventory_table . " ADD INDEX `inventory_status` (`inventory_status`)";
			$this->wpdb->query( $sql );

			self::$config->set( 'version', '1.2.4' );
		}

		if ( version_compare( $inventory_version, '1.2.5' ) < 0 ) {
			// What? No updates? Yep.  There WERE some here, but had to be removed due to failed attempts to get natural sorting working.
			self::$config->set( 'version', '1.2.5' );
		}

		if ( version_compare( $inventory_version, '1.2.6' ) < 0 ) {
			// Attempt to remove ALL themes except default, UNLESS user is using one of them.
			$all_themes = self::load_available_themes();

			// keeping the default theme for sure
			$keep_theme = [ 'Default Theme' ];

			// preventive - if customer is using a theme, keep it too!
			$used_theme = self::$config->get( 'theme' );
			if ( ! empty( $used_theme ) ) {
				$keep_theme[] = $used_theme;
			}

			$keep_theme    = array_combine( $keep_theme, $keep_theme );
			$reject_themes = array_diff_key( $all_themes, $keep_theme );

			$plugin_path = trailingslashit( self::$path );
			$plugin_url  = trailingslashit( self::$url );
			foreach ( $reject_themes AS $name => $theme ) {
				foreach ( $theme AS $type => $url ) {
					$path = str_ireplace( $plugin_url, $plugin_path, $url );
					if ( file_exists( $path ) ) {
						unlink( $path );
					}
				}
			}

			self::$config->set( 'version', '1.2.6' );
		}

		if ( version_compare( $inventory_version, '1.2.7' ) < 0 ) {
			$used_theme = self::$config->get( 'theme' );
			$keep_theme = [ 'Default Theme' ];

			if ( $used_theme != $keep_theme ) {

				$theme_folder    = trailingslashit( get_stylesheet_directory() );
				$override_filter = "{$theme_folder}wpinventory";

				if ( ! file_exists( $override_filter ) ) {
					$success = @mkdir( $override_filter );
				}
			}

			self::$config->set( 'version', '1.2.7' );
		}

		/**
		 * Adds fields for last updated by
		 */
		if ( version_compare( $inventory_version, '1.2.8' ) < 0 ) {
			$sql = "ALTER TABLE " . $this->inventory_table . " ADD COLUMN inventory_updated_by INT(11) NULL AFTER inventory_date_updated";
			$this->wpdb->query( $sql );

			self::$config->set( 'version', '1.2.8' );
		}

		/*
		* Version 1.2.9
		*  - Add include_in_sort field to label table
		*/
		if ( version_compare( $inventory_version, '1.2.9' ) < 0 ) {
			$sql = "ALTER TABLE `" . $this->label_table . "`
   			ADD COLUMN include_in_sort TINYINT(4) NOT NULL DEFAULT 1
   			";
			$this->wpdb->query( $sql );
			self::$config->set( 'version', '1.2.9' );
		}

		/*
		* Version 1.3.2
		*  - Create table to track reservations over time
		*/
//		$inventory_version = '1.3.1';
		if ( version_compare( $inventory_version, '1.3.2' ) < 0 ) {
			$sql = "CREATE TABLE IF NOT EXISTS `{$this->reservation_table}` (
				`reservation_id` INT(11) NOT NULL AUTO_INCREMENT,
				`reservation_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`reservation_ip_address` VARCHAR(50) NOT NULL DEFAULT '',
				`reservation_email_address` VARCHAR(100) NOT NULL DEFAULT '',
				`reservation_form_data` TEXT NULL DEFAULT '',
 				`reservation_total` float NOT NULL DEFAULT 0,
				PRIMARY KEY (`reservation_id`)
				);";

			$this->wpdb->query( $sql );

			$sql = "CREATE TABLE IF NOT EXISTS `{$this->reservation_item_table}` (
				`reservation_item_id` INT(11) NOT NULL AUTO_INCREMENT,
				`reservation_id` INT(11) NOT NULL,
				`inventory_id` INT(11) NOT NULL,
				`reservation_quantity` FLOAT NOT NULL,
				`reservation_item_cost` FLOAT NOT NULL DEFAULT 0,
				`reservation_item_price` FLOAT NOT NULL DEFAULT 0,
				PRIMARY KEY (`reservation_item_id`)
				);";

			$this->wpdb->query( $sql );


			// add indexes for speed / performance
			$this->add_index( $this->reservation_item_table, 'inventory_id' );
			$this->add_index( $this->reservation_item_table, 'reservation_id' );

			self::$config->set( 'version', '1.3.2' );
		}

		if ( apply_filters( 'wpim_do_fresh_install', self::$fresh_install ) ) {
			require_once self::$path . 'includes/wpinventory.default.php';
			$default = new WPIMDefaultItems();
			add_action( 'admin_init', [ $default, 'install' ] );
		}

		do_action( 'wpim_activate_plugin' );
	}

	/**
	 * Utility to properly parse out custom where arguments.
	 * Example: [wpinventory where="quantity <= 5 OR quantity_reserved >= 2"]
	 *
	 * WP "munges" HTML inside of attributes, and the < and > are considered HTML.
	 * So, parse out the munged attributes into a single, comprehensible attribute,
	 * then decode the html.
	 *
	 * Lastly, provide support for passing in labels or partial field names.  Example: Qty or quantity get mapped to
	 * inventory_quantity
	 *
	 * @param string $where
	 * @param array  $args
	 *
	 * @return string
	 */
	public function parse_custom_where( $where, $args = [] ) {
		if ( ! empty( $args ) ) {
			$wheres = [];
			$start  = FALSE;
			foreach ( $args AS $key => $value ) {
				if ( 'where' == $key ) {
					$start = TRUE;
				}

				if ( $start ) {
					if ( is_numeric( $key ) || 'where' == $key ) {
						$wheres[] = $value;
					} else {
						$start = FALSE;
					}
				}
			}

			$where = implode( ' ', $wheres );
		}

		$where = str_ireplace( [ '&#8221;', '&#8243' ], '', $where );

		$where = html_entity_decode( $where );

		$where = str_ireplace( [ ';' ], '', $where );

		$labels = self::get_labels();

		foreach ( $labels AS $field => $info ) {
			$label = $info['label'];
			$part  = str_ireplace( 'inventory_', '', $field );

			// prevent empty spaces from getting replaced in case the user doesn't have labels set up
			if ( trim( $part ) ) {
				$where = preg_replace( "/\b{$part}\b/i", $field, $where );
			}

			if ( trim( $label ) ) {
				$regex = preg_quote( $label, '/' );
				$where = preg_replace( "/\b{$regex}\b/i", $field, $where );
			}

			if ( trim( $field ) ) {
				if ( $info['is_numeric'] && preg_match( "/\b{$field}\b/", $where ) ) {
					$where = preg_replace( "/\b{$field}\b/", "CAST({$field} AS DECIMAL(10,2))", $where );
				}
			}
		}

		if ( $where && FALSE !== stripos( $where, ' OR ' ) ) {
			// ensure proper grouping in case there are OR clauses
			$where = "($where)";
		}

		return $where;
	}

	protected function get_error() {
		return $this->wpdb->last_error;
	}

	public function get_message() {
		return self::$error;
	}

	/**
	 * Parse the value for a WHERE clause.
	 * Handles multiple values (pipe-separated), turning into an IN clause.
	 *
	 * @param             $value
	 * @param null|string $field - optional.  Passing in the field name will cause the function to return a full WHERE clause
	 *
	 * @return string
	 */
	protected function parse_where_value( $value, $field = NULL ) {
		if ( stripos( $value, ',' ) ) {
			// TODO: CLEANUP AFTER REFACTOR COMMA SEPARATED
			$value = self::parse_arg_separator( $value );
//			$value = explode( self::ARGUMENT_SEPARATOR, $value );
//			$value = array_map( 'trim', $value );
		}

		if ( is_array( $value ) ) {
			$in     = array_fill( 0, count( $value ), '%s' );
			$in     = implode( ',', $in );
			$clause = $this->wpdb->prepare( "  IN ({$in})", $value );
		} else {
			$clause = $this->wpdb->prepare( " = %s", $value );
		}

		if ( $field ) {
			$clause = " {$field} {$clause}";
		}

		return $clause;
	}

	protected function append_where( $sql, $clause, $and = 'AND' ) {
		if ( trim( $sql ) ) {
			$sql .= ' ' . $and;
		}

		return $sql . ' ' . $clause;
	}

	/**
	 * Checks if columns exist in a table, and adds them if not.
	 *
	 * @param string $table
	 * @param array  $column_definitions - array in column name => column definition structure
	 *                                   example:
	 *                                   [ 'inventory_id'   => 'INT(11) NOT NULL',
	 *                                   'inventory_name' => "VARCHAR(50) NOT NULL DEFAULT ''"
	 *                                   ....
	 *                                   ]
	 */
	protected function add_columns( $table, $column_definitions ) {
		$existing = $this->wpdb->get_results( "SHOW COLUMNS FROM {$table}" );
		$existing = array_map( function ( $row ) {
			return $row->Field;
		}, $existing );

		foreach ( $column_definitions AS $name => $definition ) {
			if ( ! in_array( $name, $existing ) ) {
				$this->add_column( $table, $name, $definition, FALSE );
			}
		}
	}

	/**
	 * Checks if a single column exists in a table, and adds it if not.
	 *
	 * @param string       $table
	 * @param string       $column
	 * @param string       $definition
	 * @param null|boolean $existing   - If known, pass a boolean:
	 *                                 FALSE if not existing (column WILL be added), TRUE if existing (column will NOT be added),
	 *                                 or omit argument (or pass NULL) to have the function check if the column exists.
	 *
	 * @return bool
	 */
	protected function add_column( $table, $column, $definition, $existing = NULL ) {
		if ( NULL === $existing ) {
			$existing = (bool) $this->wpdb->get_row( "SHOW COLUMNS FROM `{$table}` LIKE '{$column}'" );
		}

		if ( $existing ) {
			return FALSE;
		}

		return (bool) $this->wpdb->query( "ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$definition}" );
	}

	/**
	 * Checks if a non-unique index exists on a column in a table, and adds it if not.
	 * NOTE: Currently only supports non-unique, single-column indexes.  Multi-column, or unique indexes not supported.
	 *
	 * @param string $table
	 * @param string $column
	 *
	 * @return bool
	 */
	protected function add_index( $table, $column ) {
		$existing = (bool) $this->wpdb->get_row( "SHOW INDEX FROM `{$table}` WHERE Column_name LIKE '{$column}'" );

		if ( $existing ) {
			return FALSE;
		}

		return (bool) $this->wpdb->query( "ALTER TABLE `{$table}` ADD INDEX `{$column}` (`{$column}`)" );
	}

	protected function getDBTables() {
		// We are checking enough tables it makes sense to just build an array with all existing tables,
		// Rather than run the check table query multiple times
		$fulltables = $this->wpdb->get_results( "SHOW TABLES", ARRAY_N );

		// Put into an array that's a bit easier to use
		foreach ( $fulltables as $table ) {
			$tables[ $table[0] ] = $table[0];
		}

		return $tables;
	}
}
