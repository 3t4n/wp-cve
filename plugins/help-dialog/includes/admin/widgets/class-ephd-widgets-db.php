<?php  // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * CRUD for Help Dialog Widgets data
 *
 * @property string primary_key
 * @property string table_name
 */
class EPHD_Widgets_DB extends EPHD_DB {

	const PRIMARY_KEY = 'widget_id';
	const DEFAULT_ID = 1;
	const CONFIG_KEY = 'config';
	const EPHD_WIDGETS_CONFIG_NAME = 'ephd_widgets_config';

	protected $table_name;
	protected $primary_key;

	private $cached_settings = null;
	private $default_config;
	private $config_spec;

	/**
	 * Get things started
	 *
	 * @access  public
	 */
	public function __construct() {

		parent::__construct();

		global $wpdb;

		$this->table_name = $wpdb->prefix . 'ephd_widgets';
		$this->primary_key = self::PRIMARY_KEY;

		$this->config_spec = EPHD_Config_Specs::get_fields_specification( self::EPHD_WIDGETS_CONFIG_NAME );
		$this->default_config = EPHD_Config_Specs::get_default_hd_config( self::EPHD_WIDGETS_CONFIG_NAME );
	}

	/**
	 * Get columns and formats
	 *
	 * @access  public
	 */
	public function get_column_format() {
		return array(
			'widget_id'           => '%d',
			'config'              => '%s',
			'location_pages_list' => '%s',
			'location_posts_list' => '%s',
			'location_cpts_list'  => '%s',
			'faqs_sequence'       => '%s',
		);
	}

	/**
	 * Get default column values
	 *
	 * @return array
	 */
	public function get_column_defaults() {
		return array(
			'widget_id'           => 0, // use 0 here as need to insert a new record, while in specs we have default 1
			'config'              => serialize( $this->get_default_config_column() ),   // config column includes multiple configuration fields
			'location_pages_list' => serialize( $this->config_spec['location_pages_list']['default'] ),
			'location_posts_list' => serialize( $this->config_spec['location_posts_list']['default'] ),
			'location_cpts_list'  => serialize( $this->config_spec['location_cpts_list']['default'] ),
			'faqs_sequence'       => serialize( $this->config_spec['faqs_sequence']['default'] ),
		);
	}

	/**
	 * Get column names
	 *
	 * @return array
	 */
	private static function get_column_names() {
		return array(
			'config',
			'location_pages_list',
			'location_posts_list',
			'location_cpts_list',
			'faqs_sequence',
		);
	}

	/**
	 * Get serialized column names
	 *
	 * @return array
	 */
	private static function get_serialized_column_names() {
		return array(
			'config',
			'location_pages_list',
			'location_posts_list',
			'location_cpts_list',
			'faqs_sequence',
		);
	}

	/**
	 * Insert a new Widgets
	 *
	 * @param $widgets_config
	 *
	 * @return array|WP_Error
	 */
	public function update_config( $widgets_config ) {

		// ensure we have correct configuration
		if ( empty( $widgets_config ) || ! is_array( $widgets_config ) ) {
			EPHD_Logging::add_log( 'Widgets config is empty or not array' );
			return new WP_Error( 'update_widget_config', 'Invalid Widget configuration.' );
		}

		$input_filter = new EPHD_Input_Filter();

		$updated_widgets_config = [];
		foreach ( $widgets_config as $widget_id => $widget_config ) {

			// first sanitize and validate input
			$new_widget_config = $input_filter->validate_and_sanitize_specs( $widget_config, $this->config_spec );
			if ( is_wp_error( $new_widget_config ) ) {
				EPHD_Logging::add_log( 'Failed to sanitize Widget settings', $new_widget_config );
				return $new_widget_config;
			}

			// use defaults for missing configuration
			$updated_widgets_config[$widget_id] = wp_parse_args( $new_widget_config, $this->default_config );

			// convert back to config array
			foreach ( $this->config_spec as $spec_name => $spec ) {
				if ( ! in_array( $spec_name, self::get_column_names() ) ) {
					$updated_widgets_config[ $widget_id ][ self::CONFIG_KEY ][ $spec_name ] = $updated_widgets_config[ $widget_id ][ $spec_name ];
					unset( $updated_widgets_config[ $widget_id ][ $spec_name ] );
				}
			}

			$updated_widgets_config[$widget_id][self::CONFIG_KEY]['widget_id'] = $widget_id;
			$record = self::serialize_values( $updated_widgets_config[$widget_id] );
			if ( empty( $record ) ) {
				EPHD_Logging::add_log( "Can't serialize Widget record. Widget ID: " . $widget_id );
				return new WP_Error( 'update_widget_config', "Can't serialize Widget record. Widget ID: " . $widget_id );
			}

			$result = parent::upsert_record( $widget_id, $record );
			if ( empty( $result ) ) {
				EPHD_Logging::add_log( "Can't Insert/Update Widget record. Widget ID: " . $widget_id );
				return new WP_Error( 'update_widget_config', "Can't Insert/Update Widget record. Widget ID: " . $widget_id );
			}

			// merge configs into a one-dimensional array
			$updated_widgets_config[$widget_id] = array_merge( $updated_widgets_config[$widget_id], $updated_widgets_config[$widget_id][self::CONFIG_KEY] );
			unset( $updated_widgets_config[$widget_id][self::CONFIG_KEY] );
		}

		// cached the settings for future use
		$this->cached_settings = $updated_widgets_config;

		return $updated_widgets_config;
	}

	/**
	 * Get all Widgets config.
	 *
	 * @param $return_error
	 * @return array|WP_Error
	 */
	public function get_config( $return_error=false ) {

		// retrieve config if already cached
		if ( is_array( $this->cached_settings ) ) {
			$widgets_config = [];
			foreach ( $this->cached_settings as $widget_id => $cached_setting ) {
				$widgets_config[$widget_id] = wp_parse_args( $cached_setting, $this->default_config );
			}

			return $widgets_config;
		}

		// Get all widgets configs as array of objects
		$widget_config_records = parent::get_rows_by_where_clause( [], '1=1' );
		if ( is_wp_error( $widget_config_records ) ) {
			EPHD_Logging::add_log( "Can't get Widget records" );
			if ( $return_error ) {
				return new WP_Error( 'DB231', __( "Did not find Help Dialog configuration", 'help-dialog' ) . ': ' . $widget_config_records->get_error_message() . '' );
			}

			// return default
			return [ self::DEFAULT_ID => $this->default_config ];
		}

		if ( empty( $widget_config_records ) || ! is_array( $widget_config_records ) ) {
			EPHD_Logging::add_log( "Can't get Widget records" );
			if ( $return_error ) {
				return new WP_Error( 'DB231', __( "Did not find Help Dialog configuration.", 'help-dialog' ) . ' (E77) ' );
			}

			// return default
			return [ self::DEFAULT_ID => $this->default_config ];
		}

		// process each widget row
		$widgets_config = [];
		foreach ( $widget_config_records as $widget_config_obj ) {

			$widget_config_obj = self::unserialize_values( $widget_config_obj );
			if ( empty( $widget_config_obj ) ) {
				EPHD_Logging::add_log( "Can't unserialize Widget record." );
				if ( $return_error ) {
					return new WP_Error( 'DB231', __( "Did not find Help Dialog configuration.", 'help-dialog' ) . ' (E78) ' );
				}

				// return default
				return [ self::DEFAULT_ID => $this->default_config ];
			}

			$widget_config = (array)$widget_config_obj;
			$widget_config = array_merge( $widget_config, $widget_config[self::CONFIG_KEY] );
			unset( $widget_config[self::CONFIG_KEY] );

			// use defaults for missing configuration
			$widget_id = empty( $widget_config['widget_id'] ) ? self::DEFAULT_ID : $widget_config['widget_id'];
			$widgets_config[$widget_id] = wp_parse_args( $widget_config, $this->default_config );
		}

		// cached the config for future use
		$this->cached_settings = $widgets_config;

		return $widgets_config;
	}

	/**
	 * Delete Widget by id
	 *
	 * @param $widget_id
	 * @return bool
	 */
	public function delete_widget_by_id( $widget_id ) {

		// check user capabilities
		if ( ! current_user_can( 'delete_posts' ) ) {
			return false;
		}

		$deleted = parent::delete_record( $widget_id );
		if ( empty( $deleted ) ) {
			EPHD_Logging::add_log( "Can't delete Widget records. Widget ID: " . $widget_id );
			return false;
		}

		return true;
	}

	/**
	 * Return configuration array of widget by ID
	 *
	 * @param $widget_id
	 * @return array | false
	 */
	public function get_widget_config_by_id( $widget_id ) {

		if ( empty( $widget_id ) ) {
			return false;
		}

		// try to retrieve widget record as object
		$widget_record = $this->get_by_primary_key( $widget_id );

		// record not found
		if ( empty( $widget_record ) ) {
			return false;
		}

		// error occurred
		if ( is_wp_error( $widget_record ) ) {
			EPHD_Logging::add_log( "Can't get Widget record", $widget_record );
			return false;
		}

		// Convert DB record that contains columns as fields of object to one dimensional array as usual config
		$widget_record = self::unserialize_values( $widget_record );
		if ( empty( $widget_record ) ) {
			EPHD_Logging::add_log( "Can't unserialize Widget record." );
			return false;
		}

		$widget_config = (array)$widget_record;
		$widget_config = array_merge( $widget_config, $widget_config[self::CONFIG_KEY] );
		unset( $widget_config[self::CONFIG_KEY] );

		// use defaults for missing configuration fields
		return wp_parse_args( $widget_config, $this->default_config );
	}

	/**
	 * Serialize inserted/updated config
	 *
	 * @param $record
	 *
	 * @return bool|array
	 */
	private static function serialize_values( $record ) {

		$serialized_column_names = self::get_serialized_column_names();

		foreach ( $record as $record_key => $record_value ) {
			// is serialized column?
			if ( ! in_array( $record_key , $serialized_column_names ) ) {
				continue;
			}
			$record[$record_key] = maybe_serialize( $record_value );
		}

		return $record;
	}

	/**
	 * Unserialize config
	 *
	 * @param $record
	 *
	 * @return bool|array
	 */
	private static function unserialize_values( $record ) {

		if ( empty( $record ) ) {
			return $record;
		}

		$serialized_column_names = self::get_serialized_column_names();

		foreach ( $record as $record_key => $record_value ) {
			// is serialized column?
			if ( ! in_array( $record_key , $serialized_column_names ) ) {
				continue;
			}
			$record->{$record_key} = maybe_unserialize( $record_value );
		}

		return $record;
	}

	/**
	 * Filter output of Widgets
	 *
	 * @param $widgets
	 *
	 * @return array
	 */
	private function filter_output( $widgets ) {
		return $widgets;
	}

	/**
	 * Return default value for 'config' column
	 *
	 * @return array
	 */
	private function get_default_config_column() {
		$default_config_column = [];
		foreach ( $this->default_config as $field_name => $field_value ) {

			// add to 'config' column only those fields which do not have dedicated column in DB
			if ( in_array( $field_name, self::get_column_names() ) ) {
				continue;
			}

			$default_config_column[$field_name] = $field_value;
		}

		return $default_config_column;
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
					widget_id 		    BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
					config              LONGTEXT NOT NULL,
					location_pages_list LONGTEXT NOT NULL,
					location_posts_list LONGTEXT NOT NULL,
					location_cpts_list  LONGTEXT NOT NULL,
					faqs_sequence       LONGTEXT NOT NULL,
	                PRIMARY KEY (widget_id)
		) " . $collate . ";";

		dbDelta( $sql );
	}

	/**
	 * Reset Widgets configuration
	 *
	 * @return array|WP_Error
	 */
	public function reset_config() {

		$result = parent::clear_table();
		if ( empty( $result ) ) {
			EPHD_Logging::add_log( 'Cannot clear Widgets table' );
			return new WP_Error( 'DB231', __( "Cannot clear Widgets table.", 'help-dialog' ) . ' (E79) ' );
		}

		$widgets_config = [ EPHD_Config_Specs::DEFAULT_ID => EPHD_Config_Specs::get_default_hd_config( EPHD_Widgets_DB::EPHD_WIDGETS_CONFIG_NAME ) ];
		return $this->update_config( $widgets_config );
	}
}
