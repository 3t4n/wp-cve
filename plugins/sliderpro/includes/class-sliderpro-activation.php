<?php
/**
 * Handles the activation and deactivation of the plugin.
 * 
 * @since 4.0.0
 */
class BQW_SliderPro_Activation {

	/**
	 * Current class instance.
	 * 
	 * @since 4.0.0
	 * 
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Initialize the Slider Pro plugin.
	 *
	 * Activate the plugin for a newly added blog.
	 *
	 * @since 4.0.0
	 */
	private function __construct() {
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_blog' ) );
	}

	/**
	 * Return the current class instance.
	 *
	 * @since 4.0.0
	 * 
	 * @return object The instance of the current class.
	 */
	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Activate the plugin for the entire network or only
	 * for a single site.
	 *
	 * @since 4.0.0
	 * 
	 * @param bool $network_wide Whether the plugin will be activated network-wide.
	 */
	public static function activate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide ) {
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();
			} else {
				self::single_activate();
			}
		} else {
			self::single_activate();
		}
	}

	/**
	 * Deactivate the plugin for the entire network or only
	 * for a single site.
	 *
	 * @since 4.0.0
	 * 
	 * @param bool $network_wide Whether the plugin will be deactivated network-wide.
	 */
	public static function deactivate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide ) {
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_deactivate();
				}

				restore_current_blog();
			} else {
				self::single_deactivate();
			}
		} else {
			self::single_deactivate();
		}
	}

	/**
	 * Called when a new blog is created in the network.
	 *
	 * @since 4.0.0
	 * 
	 * @param int $blog_id The id of the newly created blog.
	 */
	public function activate_new_blog( $blog_id ) {
		if ( did_action( 'wpmu_new_blog' ) !== 1 ) {
			return 1;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();
	}

	/**
	 * Return a list of all blogs' id's.
	 *
	 * @since 4.0.0
	 * 
	 * @return object The id's.
	 */
	private static function get_blog_ids() {
		global $wpdb;

		$sql = "SELECT blog_id FROM $wpdb->blogs WHERE archived = '0' AND spam = '0' AND deleted = '0'";

		return $wpdb->get_col($sql);
	}

	/**
	 * Called for a single blog when the plugin is activated.
	 *
	 * Creates the database tables for the plugin.
	 *
	 * @since 4.0.0
	 */
	private static function single_activate() {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$table_name = $prefix . 'slider_pro_sliders';

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		// when the slider is activated for the first time, the tables don't exist, so we need to create them
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
			$create_sliders_table = "CREATE TABLE ". $prefix . "slider_pro_sliders (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				name varchar(100) NOT NULL,
				settings text NOT NULL,
				created varchar(11) NOT NULL,
				modified varchar(11) NOT NULL,
				panels_state text NOT NULL,
				PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8;";
			
			$create_slides_table = "CREATE TABLE ". $prefix . "slider_pro_slides (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				slider_id mediumint(9) NOT NULL,
				label varchar(100) NOT NULL,
				position mediumint(9) NOT NULL,
				visibility varchar(20) NOT NULL,
				main_image_id mediumint(9) NOT NULL,
				main_image_source text NOT NULL,
				main_image_retina_source text NOT NULL,
				main_image_small_source text NOT NULL,
				main_image_medium_source text NOT NULL,
				main_image_large_source text NOT NULL,
				main_image_retina_small_source text NOT NULL,
				main_image_retina_medium_source text NOT NULL,
				main_image_retina_large_source text NOT NULL,
				main_image_alt text NOT NULL,
				main_image_title text NOT NULL,
				main_image_width mediumint(9) NOT NULL,
				main_image_height mediumint(9) NOT NULL,
				main_image_link text NOT NULL,
				main_image_link_title text NOT NULL,
				thumbnail_source text NOT NULL,
				thumbnail_retina_source text NOT NULL,
				thumbnail_alt text NOT NULL,
				thumbnail_title text NOT NULL,
				thumbnail_link text NOT NULL,
				thumbnail_link_title text NOT NULL,
				thumbnail_content text NOT NULL,
				caption text NOT NULL,
				html text NOT NULL,
				settings text NOT NULL,
				PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8;";
			
			$create_layers_table = "CREATE TABLE ". $prefix . "slider_pro_layers (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				slider_id mediumint(9) NOT NULL,
				slide_id mediumint(9) NOT NULL,
				position mediumint(9) NOT NULL,
				name text NOT NULL,
				type text NOT NULL,
				text text NOT NULL,
				heading_type varchar(100) NOT NULL,
				image_source text NOT NULL,
				image_alt text NOT NULL,
				image_link text NOT NULL,
				image_retina text NOT NULL,
				video_source varchar(20) NOT NULL,
				video_id varchar(100) NOT NULL,
				video_poster text NOT NULL,
				video_retina_poster text NOT NULL,
				video_load_mode text NOT NULL,
				video_params text NOT NULL,
				settings text NOT NULL,
				PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8;";

			dbDelta( $create_sliders_table );
			dbDelta( $create_slides_table );
			dbDelta( $create_layers_table );

			update_option( 'sliderpro_version', BQW_SliderPro::VERSION );
		}

		$wpdb->query( "DELETE FROM " . $prefix . "options WHERE option_name LIKE '%sliderpro_cache_%' AND NOT option_name = 'sliderpro_cache_expiry_interval'" );
	}
	
	/**
	 * Called for a single blog when the plugin is deactivated.
	 *
	 * @since 4.0.0
	 */
	private static function single_deactivate() {
		
	}
}