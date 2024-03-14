<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package hreflang-manager-lite
 */

/**
 * This class should be used to work with the administrative side of WordPress.
 */
class Daexthrmal_Admin {

	/**
	 * Class instance.
	 *
	 * @var null
	 */
	protected static $instance = null;

	/**
	 * Instance of the shared class.
	 *
	 * @var Daexthrmal_Shared|null
	 */
	private $shared = null;

	/**
	 * The screen id of the "Connections" menu.
	 *
	 * @var null
	 */
	private $screen_id_connections = null;

	/**
	 * The screen id of the "Help" menu.
	 *
	 * @var null
	 */
	private $screen_id_help = null;

	/**
	 * The screen id of the "Pro Version" menu.
	 *
	 * @var null
	 */
	private $screen_id_pro_version = null;

	/**
	 * The screen id of the "Options" menu.
	 *
	 * @var null
	 */
	private $screen_id_export_to_pro = null;

	/**
	 * The screen id of the "Options" menu.
	 *
	 * @var null
	 */
	private $screen_id_options = null;

	/**
	 * Constructor.
	 */
	private function __construct() {

		// assign an instance of the plugin info.
		$this->shared = Daexthrmal_Shared::get_instance();

		// Load admin stylesheets and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the admin menu.
		add_action( 'admin_menu', array( $this, 'me_add_admin_menu' ) );

		// Load the options API registrations and callbacks.
		add_action( 'admin_init', array( $this, 'op_register_options' ) );

		// this hook is triggered during the creation of a new blog.
		add_action( 'wpmu_new_blog', array( $this, 'new_blog_create_options_and_tables' ), 10, 6 );

		// this hook is triggered during the deletion of a blog.
		add_action( 'delete_blog', array( $this, 'delete_blog_delete_options_and_tables' ), 10, 1 );

		// Fires before a post is sent to the trash.
		add_action( 'wp_trash_post', array( $this, 'delete_post_connection' ) );

		// Export XML controller.
		add_action( 'init', array( $this, 'export_xml_controller' ) );

		// Change the WordPress footer text on all the plugin menus.
		add_filter( 'admin_footer_text', array( $this, 'change_footer_text' ) );
	}

	/**
	 * Return an instance of this class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Enqueue admin-specific stylesheets.
	 *
	 * @return void
	 */
	public function enqueue_admin_styles() {

		$screen = get_current_screen();

		// menu connnections.
		if ( $screen->id === $this->screen_id_connections ) {
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-framework-menu', $this->shared->get( 'url' ) . 'admin/assets/css/framework/menu.css', array(), $this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-menu-connections', $this->shared->get( 'url' ) . 'admin/assets/css/menu-connections.css', array(), $this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-chosen', $this->shared->get( 'url' ) . 'admin/assets/inc/chosen/chosen-min.css', array(), $this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-chosen-custom', $this->shared->get( 'url' ) . 'admin/assets/css/chosen-custom.css', array(), $this->shared->get( 'ver' ) );

			// jQuery UI Tooltip.
			wp_enqueue_style(
				$this->shared->get( 'slug' ) . '-jquery-ui-tooltip',
				$this->shared->get( 'url' ) . 'admin/assets/css/jquery-ui-tooltip.css',
				array(),
				$this->shared->get( 'ver' )
			);

			// jQuery UI Dialog.
			wp_enqueue_style(
				$this->shared->get( 'slug' ) . '-jquery-ui-dialog',
				$this->shared->get( 'url' ) . 'admin/assets/css/jquery-ui-dialog.css',
				array(),
				$this->shared->get( 'ver' )
			);
			wp_enqueue_style(
				$this->shared->get( 'slug' ) . '-jquery-ui-dialog-custom',
				$this->shared->get( 'url' ) . 'admin/assets/css/jquery-ui-dialog-custom.css',
				array(),
				$this->shared->get( 'ver' )
			);

		}

		// Menu Help.
		if ( $screen->id === $this->screen_id_help ) {

			wp_enqueue_style(
				$this->shared->get( 'slug' ) . '-menu-help',
				$this->shared->get( 'url' ) . 'admin/assets/css/menu-help.css',
				array(),
				$this->shared->get( 'ver' )
			);

		}

		// Menu Pro Version.
		if ( $screen->id === $this->screen_id_pro_version ) {

			wp_enqueue_style(
				$this->shared->get( 'slug' ) . '-menu-pro-version',
				$this->shared->get( 'url' ) . 'admin/assets/css/menu-pro-version.css',
				array(),
				$this->shared->get( 'ver' )
			);

		}

		// menu options.
		if ( $screen->id === $this->screen_id_options ) {
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-framework-options', $this->shared->get( 'url' ) . 'admin/assets/css/framework/options.css', array(), $this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip', $this->shared->get( 'url' ) . 'admin/assets/css/jquery-ui-tooltip.css', array(), $this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-chosen', $this->shared->get( 'url' ) . 'admin/assets/inc/chosen/chosen-min.css', array(), $this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-chosen-custom', $this->shared->get( 'url' ) . 'admin/assets/css/chosen-custom.css', array(), $this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-menu-options', $this->shared->get( 'url' ) . 'admin/assets/css/menu-options.css', array(), $this->shared->get( 'ver' ) );
		}
	}

	/**
	 * Enqueue admin-specific javascript.
	 */
	public function enqueue_admin_scripts() {

		$wp_localize_script_data = array(
			'deleteText' => esc_attr__( 'Delete', 'hreflang-manager-lite' ),
			'cancelText' => esc_attr__( 'Cancel', 'hreflang-manager-lite' ),
		);

		$screen = get_current_screen();

		// menu connnections.
		if ( $screen->id === $this->screen_id_connections ) {
			wp_enqueue_script( 'jquery-ui-tooltip' );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip-init', $this->shared->get( 'url' ) . 'admin/assets/js/jquery-ui-tooltip-init.js', 'jquery', $this->shared->get( 'ver' ) );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-chosen', $this->shared->get( 'url' ) . 'admin/assets/inc/chosen/chosen-min.js', array( 'jquery' ), $this->shared->get( 'ver' ) );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-connections-menu', $this->shared->get( 'url' ) . 'admin/assets/js/connections-menu.js', array( 'jquery', 'jquery-ui-dialog' ), $this->shared->get( 'ver' ) );
			wp_localize_script( $this->shared->get( 'slug' ) . '-connections-menu', 'objectL10n', $wp_localize_script_data );

		}

		// menu options.
		if ( $screen->id === $this->screen_id_options ) {
			wp_enqueue_script( 'jquery-ui-tooltip' );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip-init', $this->shared->get( 'url' ) . 'admin/assets/js/jquery-ui-tooltip-init.js', 'jquery', $this->shared->get( 'ver' ) );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-chosen', $this->shared->get( 'url' ) . 'admin/assets/inc/chosen/chosen-min.js', array( 'jquery' ), $this->shared->get( 'ver' ) );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-options-menu', $this->shared->get( 'url' ) . 'admin/assets/js/options-menu.js', array( 'jquery' ), $this->shared->get( 'ver' ) );
		}
	}

	/**
	 * Plugin activation.
	 */
	public function ac_activate( $networkwide ) {

		/**
		 * Delete options and tables for all the sites in the network.
		 */
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			/**
			 * If this is a "Network Activation" create the options and tables
			 * for each blog.
			 */
			if ( $networkwide ) {

				// get the current blog id.
				global $wpdb;
				$current_blog = $wpdb->blogid;

				// create an array with all the blog ids.
				$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

				// iterate through all the blogs.
				foreach ( $blogids as $blog_id ) {

					// switch to the iterated blog.
					switch_to_blog( $blog_id );

					// create options and tables for the iterated blog.
					$this->ac_initialize_options();
					$this->ac_create_database_tables();

				}

				// switch to the current blog.
				switch_to_blog( $current_blog );

			} else {

				/**
				 * If this is not a "Network Activation" create options and
				 * tables only for the current blog.
				 */
				$this->ac_initialize_options();
				$this->ac_create_database_tables();

			}
		} else {

			/**
			 * If this is not a multisite installation create options and
			 * tables only for the current blog.
			 */
			$this->ac_initialize_options();
			$this->ac_create_database_tables();

		}
	}

	/**
	 * Create the options and tables for the newly created blog.
	 *
	 * @param $blog_id
	 * @param $user_id
	 * @param $domain
	 * @param $path
	 * @param $site_id
	 * @param $meta
	 *
	 * @return void
	 */
	public function new_blog_create_options_and_tables( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {

		global $wpdb;

		/**
		 * If the plugin is "Network Active" create the options and tables for
		 * this new blog.
		 */
		if ( is_plugin_active_for_network( 'hreflang-manager/init.php' ) ) {

			// get the id of the current blog.
			$current_blog = $wpdb->blogid;

			// switch to the blog that is being activated.
			switch_to_blog( $blog_id );

			// create options and database tables for the new blog.
			$this->ac_initialize_options();
			$this->ac_create_database_tables();

			// switch to the current blog.
			switch_to_blog( $current_blog );

		}
	}

	/**
	 * Delete options and tables for the deleted blog.
	 *
	 * @param $blog_id
	 *
	 * @return void
	 */
	public function delete_blog_delete_options_and_tables( $blog_id ) {

		global $wpdb;

		// get the id of the current blog.
		$current_blog = $wpdb->blogid;

		// switch to the blog that is being activated.
		switch_to_blog( $blog_id );

		// create options and database tables for the new blog.
		$this->un_delete_options();
		$this->un_delete_database_tables();

		// switch to the current blog.
		switch_to_blog( $current_blog );
	}

	/**
	 * Initialize plugin options.
	 */
	private function ac_initialize_options() {

		foreach ( $this->shared->get( 'options' ) as $key => $value ) {
			add_option( $key, $value );
		}
	}

	/**
	 * Create the plugin database tables.
	 */
	private function ac_create_database_tables() {

		global $wpdb;

		// Get the database character collate that will be appended at the end of each query.
		$charset_collate = $wpdb->get_charset_collate();

		// check database version and create the database.
		if ( intval( get_option( $this->shared->get( 'slug' ) . '_database_version' ), 10 ) < 1 ) {

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			// create *prefix*_statistic.
			$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . '_connection';
			$sql        = "CREATE TABLE $table_name (
                connection_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                url_to_connect TEXT DEFAULT '' NOT NULL,
                url1 TEXT DEFAULT '' NOT NULL,
                language1 VARCHAR(9) DEFAULT '' NOT NULL,
                script1 VARCHAR(4) DEFAULT '' NOT NULL,
                locale1 VARCHAR(2) DEFAULT '' NOT NULL,
                url2 TEXT DEFAULT '' NOT NULL,
                language2 VARCHAR(9) DEFAULT '' NOT NULL,
                script2 VARCHAR(4) DEFAULT '' NOT NULL,
                locale2 VARCHAR(2) DEFAULT '' NOT NULL,
                url3 TEXT DEFAULT '' NOT NULL,
                language3 VARCHAR(9) DEFAULT '' NOT NULL,
                script3 VARCHAR(4) DEFAULT '' NOT NULL,
                locale3 VARCHAR(2) DEFAULT '' NOT NULL,
                url4 TEXT DEFAULT '' NOT NULL,
                language4 VARCHAR(9) DEFAULT '' NOT NULL,
                script4 VARCHAR(4) DEFAULT '' NOT NULL,
                locale4 VARCHAR(2) DEFAULT '' NOT NULL,
                url5 TEXT DEFAULT '' NOT NULL,
                language5 VARCHAR(9) DEFAULT '' NOT NULL,
                script5 VARCHAR(4) DEFAULT '' NOT NULL,
                locale5 VARCHAR(2) DEFAULT '' NOT NULL,
                url6 TEXT DEFAULT '' NOT NULL,
                language6 VARCHAR(9) DEFAULT '' NOT NULL,
                script6 VARCHAR(4) DEFAULT '' NOT NULL,
                locale6 VARCHAR(2) DEFAULT '' NOT NULL,
                url7 TEXT DEFAULT '' NOT NULL,
                language7 VARCHAR(9) DEFAULT '' NOT NULL,
                script7 VARCHAR(4) DEFAULT '' NOT NULL,
                locale7 VARCHAR(2) DEFAULT '' NOT NULL,
                url8 TEXT DEFAULT '' NOT NULL,
                language8 VARCHAR(9) DEFAULT '' NOT NULL,
                script8 VARCHAR(4) DEFAULT '' NOT NULL,
                locale8 VARCHAR(2) DEFAULT '' NOT NULL,
                url9 TEXT DEFAULT '' NOT NULL,
                language9 VARCHAR(9) DEFAULT '' NOT NULL,
                script9 VARCHAR(4) DEFAULT '' NOT NULL,
                locale9 VARCHAR(2) DEFAULT '' NOT NULL,
                url10 TEXT DEFAULT '' NOT NULL,
                language10 VARCHAR(9) DEFAULT '' NOT NULL,
                script10 VARCHAR(4) DEFAULT '' NOT NULL,
                locale10 VARCHAR(2) DEFAULT '' NOT NULL
            ) $charset_collate";
			dbDelta( $sql );

			// Update database version.
			update_option( $this->shared->get( 'slug' ) . '_database_version', '1' );

		}
	}

	/**
	 * Plugin delete.
	 */
	public static function un_delete() {

		/**
		 * Delete options and tables for all the sites in the network.
		 */
		if ( function_exists( 'is_multisite' ) and is_multisite() ) {

			// get the current blog id.
			global $wpdb;
			$current_blog = $wpdb->blogid;

			// create an array with all the blog ids.
			$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

			// iterate through all the blogs.
			foreach ( $blogids as $blog_id ) {

				// swith to the iterated blog.
				switch_to_blog( $blog_id );

				// create options and tables for the iterated blog.
				self::un_delete_options();
				self::un_delete_database_tables();

			}

			// switch to the current blog.
			switch_to_blog( $current_blog );

		} else {

			/**
			 * If this is not a multisite installation delete options and
			 * tables only for the current blog.
			 */
			self::un_delete_options();
			self::un_delete_database_tables();

		}
	}

	/**
	 * Delete plugin options.
	 */
	public static function un_delete_options() {

		// assign an instance of Daexthrmal_Shared.
		$shared = Daexthrmal_Shared::get_instance();

		foreach ( $shared->get( 'options' ) as $key => $value ) {
			delete_option( $key );
		}
	}

	/**
	 * Delete plugin database tables.
	 */
	public static function un_delete_database_tables() {

		// Assign an instance of Daexthrmal_Shared.
		$shared = Daexthrmal_Shared::get_instance();

		global $wpdb;

		$table_name = $wpdb->prefix . $shared->get( 'slug' ) . '_connection';
		$sql        = "DROP TABLE $table_name";
		$wpdb->query( $sql );
	}

	/**
	 * Register the admin menu.
	 */
	public function me_add_admin_menu() {

		add_menu_page(
			'HM',
			'Hreflang',
			'manage_options',
			$this->shared->get( 'slug' ) . '_connections',
			array( $this, 'me_display_menu_connections' ),
			'dashicons-admin-site'
		);

		$this->screen_id_connections = add_submenu_page(
			$this->shared->get( 'slug' ) . '_connections',
			esc_html__( 'HM - Connections', 'hreflang-manager-lite' ),
			esc_html__( 'Connections', 'hreflang-manager-lite' ),
			'manage_options',
			$this->shared->get( 'slug' ) . '_connections',
			array( $this, 'me_display_menu_connections' )
		);

		$this->screen_id_export_to_pro = add_submenu_page(
			$this->shared->get( 'slug' ) . '_connections',
			esc_html__( 'HM - Export to Pro', 'hreflang-manager-lite' ),
			esc_html__( 'Export to Pro', 'hreflang-manager-lite' ),
			'manage_options',
			$this->shared->get( 'slug' ) . '_export_to_pro',
			array( $this, 'me_display_menu_export_to_pro' )
		);

		$this->screen_id_help = add_submenu_page(
			$this->shared->get( 'slug' ) . '_connections',
			esc_html__( 'HM - Help', 'hreflang-manager-lite' ),
			esc_html__( 'Help', 'hreflang-manager-lite' ),
			'manage_options',
			$this->shared->get( 'slug' ) . '_help',
			array( $this, 'me_display_menu_help' )
		);

		$this->screen_id_pro_version = add_submenu_page(
			$this->shared->get( 'slug' ) . '_connections',
			esc_html__( 'HM - Pro Version', 'hreflang-manager-lite' ),
			esc_html__( 'Pro Version', 'hreflang-manager-lite' ),
			'manage_options',
			$this->shared->get( 'slug' ) . '_pro_version',
			array( $this, 'me_display_menu_pro_version' )
		);

		$this->screen_id_options = add_submenu_page(
			$this->shared->get( 'slug' ) . '_connections',
			esc_html__( 'HM - Options', 'hreflang-manager-lite' ),
			esc_html__( 'Options', 'hreflang-manager-lite' ),
			'manage_options',
			$this->shared->get( 'slug' ) . '_options',
			array( $this, 'me_display_menu_options' )
		);
	}

	/**
	 * Includes the "Connections" menu.
	 */
	public function me_display_menu_connections() {
		include_once 'view/connections.php';
	}

	/**
	 * Includes the "Export" to pro menu.
	 */
	public function me_display_menu_export_to_pro() {
		include_once 'view/export-to-pro.php';
	}

	/**
	 * Includes the "Help" menu.
	 */
	public function me_display_menu_help() {
		include_once 'view/help.php';
	}

	/**
	 * Includes the "Pro version" menu.
	 */
	public function me_display_menu_pro_version() {
		include_once 'view/pro-version.php';
	}

	/**
	 * Includes the "Options" view.
	 */
	public function me_display_menu_options() {
		include_once 'view/options.php';
	}

	/**
	 * Register options.
	 */
	public function op_register_options() {

		// section general ----------------------------------------------------------.
		add_settings_section(
			'daexthrmal_general_settings_section',
			null,
			null,
			'daexthrmal_general_options'
		);

		add_settings_field(
			'detect_url_mode',
			esc_html__( 'Detect URL Mode', 'hreflang-manager-lite' ),
			array( $this, 'detect_url_mode_callback' ),
			'daexthrmal_general_options',
			'daexthrmal_general_settings_section'
		);

		register_setting(
			'daexthrmal_general_options',
			'daexthrmal_detect_url_mode',
			array( $this, 'detect_url_mode_validation' )
		);

		add_settings_field(
			'https',
			esc_html__( 'HTTPS', 'hreflang-manager-lite' ),
			array( $this, 'https_callback' ),
			'daexthrmal_general_options',
			'daexthrmal_general_settings_section'
		);

		register_setting(
			'daexthrmal_general_options',
			'daexthrmal_https',
			array( $this, 'https_validation' )
		);

		add_settings_field(
			'auto_trailing_slash',
			esc_html__( 'Auto Trailing Slash', 'hreflang-manager-lite' ),
			array( $this, 'auto_trailing_slash_callback' ),
			'daexthrmal_general_options',
			'daexthrmal_general_settings_section'
		);

		register_setting(
			'daexthrmal_general_options',
			'daexthrmal_auto_trailing_slash',
			array( $this, 'auto_trailing_slash_validation' )
		);

		add_settings_field(
			'auto_delete',
			esc_html__( 'Auto Delete', 'hreflang-manager-lite' ),
			array( $this, 'auto_delete_callback' ),
			'daexthrmal_general_options',
			'daexthrmal_general_settings_section'
		);

		register_setting(
			'daexthrmal_general_options',
			'daexthrmal_auto_delete',
			array( $this, 'auto_delete_validation' )
		);

		add_settings_field(
			'auto_alternate_pages',
			esc_html__( 'Auto Alternate Pages', 'hreflang-manager-lite' ),
			array( $this, 'auto_alternate_pages_callback' ),
			'daexthrmal_general_options',
			'daexthrmal_general_settings_section'
		);

		register_setting(
			'daexthrmal_general_options',
			'daexthrmal_auto_alternate_pages',
			array( $this, 'auto_alternate_pages_validation' )
		);

		add_settings_field(
			'show_log',
			esc_html__( 'Show Log', 'hreflang-manager-lite' ),
			array( $this, 'show_log_callback' ),
			'daexthrmal_general_options',
			'daexthrmal_general_settings_section'
		);

		register_setting(
			'daexthrmal_general_options',
			'daexthrmal_show_log',
			array( $this, 'show_log_validation' )
		);

		// section defaults ----------------------------------------------------------

		add_settings_section(
			'daexthrmal_defaults_settings_section',
			null,
			null,
			'daexthrmal_defaults_options'
		);

		$connections_in_menu = get_option( 'daexthrmal_connections_in_menu' );
		for ( $i = 1;$i <= 10;$i++ ) {

			add_settings_field(
				'default_language_' . $i,
				esc_html__( 'Default Language', 'hreflang-manager-lite' ) . ' ' . $i,
				array( $this, 'default_language_' . $i . '_callback' ),
				'daexthrmal_defaults_options',
				'daexthrmal_defaults_settings_section'
			);

			register_setting(
				'daexthrmal_defaults_options',
				'daexthrmal_default_language_' . $i,
				array( $this, 'default_language_' . $i . '_validation' )
			);

			add_settings_field(
				'default_script_' . $i,
				esc_html__( 'Default Script', 'hreflang-manager-lite' ) . ' ' . $i,
				array( $this, 'default_script_' . $i . '_callback' ),
				'daexthrmal_defaults_options',
				'daexthrmal_defaults_settings_section'
			);

			register_setting(
				'daexthrmal_defaults_options',
				'daexthrmal_default_script_' . $i,
				array( $this, 'default_script_' . $i . '_validation' )
			);

			add_settings_field(
				'default_locale_' . $i,
				esc_html__( 'Default Locale', 'hreflang-manager-lite' ) . ' ' . $i,
				array( $this, 'default_locale_' . $i . '_callback' ),
				'daexthrmal_defaults_options',
				'daexthrmal_defaults_settings_section'
			);

			register_setting(
				'daexthrmal_defaults_options',
				'daexthrmal_default_locale_' . $i,
				array( $this, 'default_locale_' . $i . '_validation' )
			);

		}
	}

	/**
	 * @return void
	 */
	public function show_log_callback() {

		$html  = '<select id="daexthrmal-show-log" name="daexthrmal_show_log">';
		$html .= '<option ' . selected( intval( get_option( 'daexthrmal_show_log' ) ), 0, false ) . ' value="0">' . esc_html__( 'No', 'hreflang-manager-lite' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( 'daexthrmal_show_log' ) ), 1, false ) . ' value="1">' . esc_html__( 'Yes', 'hreflang-manager-lite' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Select "Yes" to display the log on the front-end. Please note that the log will be displayed only to the users who have access to the "Connections" menu.', 'hreflang-manager-lite' ) . '"></div>';

		echo $html;
	}

	public function show_log_validation( $input ) {

		return intval( $input, 10 ) === 1 ? '1' : '0';
	}

	public function https_callback() {

		$html  = '<select id="daexthrmal-https" name="daexthrmal_https">';
		$html .= '<option ' . selected( intval( get_option( 'daexthrmal_https' ) ), 0, false ) . ' value="0">' . esc_html__( 'No', 'hreflang-manager-lite' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( 'daexthrmal_https' ) ), 1, false ) . ' value="1">' . esc_html__( 'Yes', 'hreflang-manager-lite' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Select "Yes" if your website is using the HTTPS protocol. This option will be considered only if "Detect URL Mode" is set to "Server Variable".', 'hreflang-manager-lite' ) . '"></div>';

		echo $html;
	}

	public function https_validation( $input ) {

		return intval( $input, 10 ) === 1 ? '1' : '0';
	}

	public function detect_url_mode_callback() {

		$html  = '<select id="daexthrmal-detect-url-mode" name="daexthrmal_detect_url_mode">';
		$html .= '<option ' . selected( get_option( 'daexthrmal_detect_url_mode' ), 0, false ) . ' value="0">' . esc_html__( 'Server Variable', 'hreflang-manager-lite' ) . '</option>';
		$html .= '<option ' . selected( get_option( 'daexthrmal_detect_url_mode' ), 1, false ) . ' value="1">' . esc_html__( 'WP Request', 'hreflang-manager-lite' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Select the method used to detect the URL of the page.', 'hreflang-manager-lite' ) . '"></div>';

		echo $html;
	}

	public function detect_url_mode_validation( $input ) {

		return intval( $input, 10 ) == 1 ? '1' : '0';
	}

	public function auto_trailing_slash_callback() {

		$html  = '<select id="daexthrmal-auto-trailing-slash" name="daexthrmal_auto_trailing_slash">';
		$html .= '<option ' . selected( intval( get_option( 'daexthrmal_auto_trailing_slash' ) ), 0, false ) . ' value="0">' . esc_html__( 'No', 'hreflang-manager-lite' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( 'daexthrmal_auto_trailing_slash' ) ), 1, false ) . ' value="1">' . esc_html__( 'Yes', 'hreflang-manager-lite' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Enable this option to compare the URL defined in the "URL to Connect" field with the URL of the page with and without trailing slash.', 'hreflang-manager-lite' ) . '"></div>';

		echo $html;
	}

	public function auto_trailing_slash_validation( $input ) {

		return intval( $input, 10 ) === 1 ? '1' : '0';
	}

	public function auto_delete_callback() {

		$html  = '<select id="daexthrmal-auto-delete" name="daexthrmal_auto_delete">';
		$html .= '<option ' . selected( intval( get_option( 'daexthrmal_auto_delete' ) ), 0, false ) . ' value="0">' . esc_html__( 'No', 'hreflang-manager-lite' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( 'daexthrmal_auto_delete' ) ), 1, false ) . ' value="1">' . esc_html__( 'Yes', 'hreflang-manager-lite' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Enable this option to automatically delete the connection associated with a post when the post is trashed.', 'hreflang-manager-lite' ) . '"></div>';

		echo $html;
	}

	public function auto_delete_validation( $input ) {

		return intval( $input, 10 ) === 1 ? '1' : '0';
	}

	public function auto_alternate_pages_callback() {

		$html  = '<select id="daexthrmal-auto-alternate-pages" name="daexthrmal_auto_alternate_pages">';
		$html .= '<option ' . selected( intval( get_option( 'daexthrmal_auto_alternate_pages' ) ), 0, false ) . ' value="0">' . esc_html__( 'No', 'hreflang-manager-lite' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( 'daexthrmal_auto_alternate_pages' ) ), 1, false ) . ' value="1">' . esc_html__( 'Yes', 'hreflang-manager-lite' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'With this option enabled, the plugin automatically generates the connections for the alternate pages. This option should only be used if this WordPress installation serves the alternate pages.', 'hreflang-manager-lite' ) . '"></div>';

		echo $html;
	}

	public function auto_alternate_pages_validation( $input ) {

		return intval( $input, 10 ) === 1 ? '1' : '0';
	}

	// 1 ----------------------------------------------------------------------------------------------------------------
	public function default_language_1_callback() {
		$html           = '<select id="daexthrmal-default-language-1" class="daexthrmal-default-language" name="daexthrmal_default_language_1">';
		$array_language = get_option( 'daexthrmal_language' );
		foreach ( $array_language as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_language_1' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of language', 'hreflang-manager-lite' ) . ' 1.' . '"></div>';
		echo $html;
	}

	public function default_language_1_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_script_1_callback() {
		$html         = '<select id="daexthrmal-default-script-1" class="daexthrmal-default-script" name="daexthrmal_default_script_1">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_script = get_option( 'daexthrmal_script' );
		foreach ( $array_script as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_script_1' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of script', 'hreflang-manager-lite' ) . ' 1.' . '"></div>';

		echo $html;
	}

	public function default_script_1_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_locale_1_callback() {
		$html         = '<select id="daexthrmal-default-locale-1" class="daexthrmal-default-locale" name="daexthrmal_default_locale_1">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_locale = get_option( 'daexthrmal_locale' );
		foreach ( $array_locale as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_locale_1' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of locale', 'hreflang-manager-lite' ) . ' 1.' . '"></div>';

		echo $html;
	}

	public function default_locale_1_validation( $input ) {
		return sanitize_text_field( $input );
	}

	// 2 ----------------------------------------------------------------------------------------------------------------
	public function default_language_2_callback() {
		$html           = '<select id="daexthrmal-default-language-2" class="daexthrmal-default-language" name="daexthrmal_default_language_2">';
		$array_language = get_option( 'daexthrmal_language' );
		foreach ( $array_language as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_language_2' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of language', 'hreflang-manager-lite' ) . ' 2.' . '"></div>';
		echo $html;
	}

	public function default_language_2_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_script_2_callback() {
		$html         = '<select id="daexthrmal-default-script-2" class="daexthrmal-default-script" name="daexthrmal_default_script_2">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_script = get_option( 'daexthrmal_script' );
		foreach ( $array_script as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_script_2' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of script', 'hreflang-manager-lite' ) . ' 2.' . '"></div>';
		echo $html;
	}

	public function default_script_2_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_locale_2_callback() {
		$html         = '<select id="daexthrmal-default-locale-2" class="daexthrmal-default-locale" name="daexthrmal_default_locale_2">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_locale = get_option( 'daexthrmal_locale' );
		foreach ( $array_locale as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_locale_2' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of locale', 'hreflang-manager-lite' ) . ' 2.' . '"></div>';
		echo $html;
	}

	public function default_locale_2_validation( $input ) {
		return sanitize_text_field( $input );
	}

	// 3 ----------------------------------------------------------------------------------------------------------------
	public function default_language_3_callback() {
		$html           = '<select id="daexthrmal-default-language-3" class="daexthrmal-default-language" name="daexthrmal_default_language_3">';
		$array_language = get_option( 'daexthrmal_language' );
		foreach ( $array_language as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_language_3' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of language', 'hreflang-manager-lite' ) . ' 3.' . '"></div>';
		echo $html;
	}

	public function default_language_3_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_script_3_callback() {
		$html         = '<select id="daexthrmal-default-script-3" class="daexthrmal-default-script" name="daexthrmal_default_script_3">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_script = get_option( 'daexthrmal_script' );
		foreach ( $array_script as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_script_3' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of script', 'hreflang-manager-lite' ) . ' 3.' . '"></div>';

		echo $html;
	}

	public function default_script_3_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_locale_3_callback() {
		$html         = '<select id="daexthrmal-default-locale-3" class="daexthrmal-default-locale" name="daexthrmal_default_locale_3">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_locale = get_option( 'daexthrmal_locale' );
		foreach ( $array_locale as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_locale_3' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of locale', 'hreflang-manager-lite' ) . ' 3.' . '"></div>';

		echo $html;
	}

	public function default_locale_3_validation( $input ) {
		return sanitize_text_field( $input );
	}

	// 4 ----------------------------------------------------------------------------------------------------------------
	public function default_language_4_callback() {
		$html           = '<select id="daexthrmal-default-language-4" class="daexthrmal-default-language" name="daexthrmal_default_language_4">';
		$array_language = get_option( 'daexthrmal_language' );
		foreach ( $array_language as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_language_4' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of language', 'hreflang-manager-lite' ) . ' 4.' . '"></div>';
		echo $html;
	}

	public function default_language_4_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_script_4_callback() {
		$html         = '<select id="daexthrmal-default-script-4" class="daexthrmal-default-script" name="daexthrmal_default_script_4">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_script = get_option( 'daexthrmal_script' );
		foreach ( $array_script as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_script_4' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of script', 'hreflang-manager-lite' ) . ' 4.' . '"></div>';

		echo $html;
	}

	public function default_script_4_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_locale_4_callback() {
		$html         = '<select id="daexthrmal-default-locale-4" class="daexthrmal-default-locale" name="daexthrmal_default_locale_4">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_locale = get_option( 'daexthrmal_locale' );
		foreach ( $array_locale as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_locale_4' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of locale', 'hreflang-manager-lite' ) . ' 4.' . '"></div>';

		echo $html;
	}

	public function default_locale_4_validation( $input ) {
		return sanitize_text_field( $input );
	}

	// 5 ----------------------------------------------------------------------------------------------------------------
	public function default_language_5_callback() {
		$html           = '<select id="daexthrmal-default-language-5" class="daexthrmal-default-language" name="daexthrmal_default_language_5">';
		$array_language = get_option( 'daexthrmal_language' );
		foreach ( $array_language as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_language_5' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of language', 'hreflang-manager-lite' ) . ' 5.' . '"></div>';
		echo $html;
	}

	public function default_language_5_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_script_5_callback() {
		$html         = '<select id="daexthrmal-default-script-5" class="daexthrmal-default-script" name="daexthrmal_default_script_5">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_script = get_option( 'daexthrmal_script' );
		foreach ( $array_script as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_script_5' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of script', 'hreflang-manager-lite' ) . ' 5.' . '"></div>';

		echo $html;
	}

	public function default_script_5_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_locale_5_callback() {
		$html         = '<select id="daexthrmal-default-locale-5" class="daexthrmal-default-locale" name="daexthrmal_default_locale_5">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_locale = get_option( 'daexthrmal_locale' );
		foreach ( $array_locale as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_locale_5' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of locale', 'hreflang-manager-lite' ) . ' 5.' . '"></div>';

		echo $html;
	}

	public function default_locale_5_validation( $input ) {
		return sanitize_text_field( $input );
	}

	// 6 ----------------------------------------------------------------------------------------------------------------
	public function default_language_6_callback() {
		$html           = '<select id="daexthrmal-default-language-6" class="daexthrmal-default-language" name="daexthrmal_default_language_6">';
		$array_language = get_option( 'daexthrmal_language' );
		foreach ( $array_language as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_language_6' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of language', 'hreflang-manager-lite' ) . ' 6.' . '"></div>';
		echo $html;
	}

	public function default_language_6_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_script_6_callback() {
		$html         = '<select id="daexthrmal-default-script-6" class="daexthrmal-default-script" name="daexthrmal_default_script_6">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_script = get_option( 'daexthrmal_script' );
		foreach ( $array_script as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_script_6' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of script', 'hreflang-manager-lite' ) . ' 6.' . '"></div>';

		echo $html;
	}

	public function default_script_6_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_locale_6_callback() {
		$html         = '<select id="daexthrmal-default-locale-6" class="daexthrmal-default-locale" name="daexthrmal_default_locale_6">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_locale = get_option( 'daexthrmal_locale' );
		foreach ( $array_locale as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_locale_6' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of locale', 'hreflang-manager-lite' ) . ' 6.' . '"></div>';

		echo $html;
	}

	public function default_locale_6_validation( $input ) {
		return sanitize_text_field( $input );
	}

	// 7 ----------------------------------------------------------------------------------------------------------------
	public function default_language_7_callback() {
		$html           = '<select id="daexthrmal-default-language-7" class="daexthrmal-default-language" name="daexthrmal_default_language_7">';
		$array_language = get_option( 'daexthrmal_language' );
		foreach ( $array_language as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_language_7' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of language', 'hreflang-manager-lite' ) . ' 7.' . '"></div>';
		echo $html;
	}

	public function default_language_7_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_script_7_callback() {
		$html         = '<select id="daexthrmal-default-script-7" class="daexthrmal-default-script" name="daexthrmal_default_script_7">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_script = get_option( 'daexthrmal_script' );
		foreach ( $array_script as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_script_7' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of script', 'hreflang-manager-lite' ) . ' 7.' . '"></div>';

		echo $html;
	}

	public function default_script_7_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_locale_7_callback() {
		$html         = '<select id="daexthrmal-default-locale-7" class="daexthrmal-default-locale" name="daexthrmal_default_locale_7">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_locale = get_option( 'daexthrmal_locale' );
		foreach ( $array_locale as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_locale_7' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of locale', 'hreflang-manager-lite' ) . ' 7.' . '"></div>';

		echo $html;
	}

	public function default_locale_7_validation( $input ) {
		return sanitize_text_field( $input );
	}

	// 8 ----------------------------------------------------------------------------------------------------------------
	public function default_language_8_callback() {
		$html           = '<select id="daexthrmal-default-language-8" class="daexthrmal-default-language" name="daexthrmal_default_language_8">';
		$array_language = get_option( 'daexthrmal_language' );
		foreach ( $array_language as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_language_8' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of language', 'hreflang-manager-lite' ) . ' 8.' . '"></div>';
		echo $html;
	}

	public function default_language_8_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_script_8_callback() {
		$html         = '<select id="daexthrmal-default-script-8" class="daexthrmal-default-script" name="daexthrmal_default_script_8">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_script = get_option( 'daexthrmal_script' );
		foreach ( $array_script as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_script_8' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of script', 'hreflang-manager-lite' ) . ' 8.' . '"></div>';

		echo $html;
	}

	public function default_script_8_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_locale_8_callback() {
		$html         = '<select id="daexthrmal-default-locale-8" class="daexthrmal-default-locale" name="daexthrmal_default_locale_8">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_locale = get_option( 'daexthrmal_locale' );
		foreach ( $array_locale as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_locale_8' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of locale', 'hreflang-manager-lite' ) . ' 8.' . '"></div>';

		echo $html;
	}

	public function default_locale_8_validation( $input ) {
		return sanitize_text_field( $input );
	}

	// 9 ----------------------------------------------------------------------------------------------------------------
	public function default_language_9_callback() {
		$html           = '<select id="daexthrmal-default-language-9" class="daexthrmal-default-language" name="daexthrmal_default_language_9">';
		$array_language = get_option( 'daexthrmal_language' );
		foreach ( $array_language as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_language_9' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of language', 'hreflang-manager-lite' ) . ' 9.' . '"></div>';
		echo $html;
	}

	public function default_language_9_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_script_9_callback() {
		$html         = '<select id="daexthrmal-default-script-9" class="daexthrmal-default-script" name="daexthrmal_default_script_9">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_script = get_option( 'daexthrmal_script' );
		foreach ( $array_script as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_script_9' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of script', 'hreflang-manager-lite' ) . ' 9.' . '"></div>';

		echo $html;
	}

	public function default_script_9_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_locale_9_callback() {
		$html         = '<select id="daexthrmal-default-locale-9" class="daexthrmal-default-locale" name="daexthrmal_default_locale_9">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_locale = get_option( 'daexthrmal_locale' );
		foreach ( $array_locale as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_locale_9' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the default value of locale', 'hreflang-manager-lite' ) . ' 9.' . '"></div>';

		echo $html;
	}

	public function default_locale_9_validation( $input ) {
		return sanitize_text_field( $input );
	}

	// 10 ----------------------------------------------------------------------------------------------------------------
	public function default_language_10_callback() {
		$html           = '<select id="daexthrmal-default-language-10" class="daexthrmal-default-language" name="daexthrmal_default_language_10">';
		$array_language = get_option( 'daexthrmal_language' );
		foreach ( $array_language as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_language_10' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Default Language', 'hreflang-manager-lite' ) . ' 10' . '"></div>';
		echo $html;
	}

	public function default_language_10_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_script_10_callback() {
		$html         = '<select id="daexthrmal-default-script-10" class="daexthrmal-default-script" name="daexthrmal_default_script_10">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_script = get_option( 'daexthrmal_script' );
		foreach ( $array_script as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_script_10' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Default Script', 'hreflang-manager-lite' ) . ' 10' . '"></div>';

		echo $html;
	}

	public function default_script_10_validation( $input ) {
		return sanitize_text_field( $input );
	}

	public function default_locale_10_callback() {
		$html         = '<select id="daexthrmal-default-locale-10" class="daexthrmal-default-locale" name="daexthrmal_default_locale_10">';
		$html        .= '<option value="">' . esc_html__( 'Not Assigned', 'hreflang-manager-lite' ) . '</option>';
		$array_locale = get_option( 'daexthrmal_locale' );
		foreach ( $array_locale as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . selected( get_option( 'daexthrmal_default_locale_10' ), $value, false ) . '>' . $value . ' - ' . $key . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Default Locale', 'hreflang-manager-lite' ) . ' 10' . '"></div>';

		echo $html;
	}

	public function default_locale_10_validation( $input ) {
		return sanitize_text_field( $input );
	}

	/**
	 * Deletes a connection by using the permalink of the trashed post. Note that this operation is performed only if
	 * the 'Auto Delete' option is enabled.
	 */
	public function delete_post_connection( $post_id ) {

		if ( intval( get_option( $this->shared->get( 'slug' ) . '_auto_delete' ), 10 ) === 1 ) {

			$permalink = get_the_permalink( $post_id, false );

			global $wpdb;
			$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . '_connection';
			$safe_sql   = $wpdb->prepare( "DELETE FROM $table_name WHERE url_to_connect = %s", $permalink );
			$wpdb->query( $safe_sql );

		}
	}

	/*
	 * The click on the "Export" button available in the "Export" menu is intercepted and the
	 * method that generates the downloadable XML file is called
	 */
	public function export_xml_controller() {

		/*
		 * Intercept requests that come from the "Export" button of the
		 * "Hreflang Export -> Export" menu and generate the downloadable XML file
		 */
		if ( isset( $_POST['daexthrmal_export'] ) ) {

			// verify capability
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'hreflang-manager-lite' ) );
			}

			// get the data from the 'connect' db
			global $wpdb;
			$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . '_connection';
			$connect_a  = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY connection_id ASC", ARRAY_A );

			// if there are data generate the csv header and the content
			if ( count( $connect_a ) > 0 ) {

				// generate the header of the XML file
				header( 'Content-Encoding: UTF-8' );
				header( 'Content-type: text/xml; charset=UTF-8' );
				header( 'Content-Disposition: attachment; filename=hreflang-manager-' . time() . '.xml' );
				header( 'Pragma: no-cache' );
				header( 'Expires: 0' );

				// generate initial part of the XML file
				$out  = '<?xml version="1.0" encoding="UTF-8" ?>';
				$out .= '<root>';

				// set column content
				foreach ( $connect_a as $connect ) {

					$out .= '<connect>';

					$url      = array();
					$language = array();
					$script   = array();
					$locale   = array();

					// Add the values for the fields that exists in the standard version
					for ( $i = 1; $i <= 10; $i++ ) {

						$url[ $i ]      = $connect[ 'url' . $i ];
						$language[ $i ] = $connect[ 'language' . $i ];
						$script[ $i ]   = $connect[ 'script' . $i ];
						$locale[ $i ]   = $connect[ 'locale' . $i ];

					}

					// Add the values for the fields that exists only in the pro version
					for ( $i = 11; $i <= 100; $i++ ) {

						$url[ $i ]      = '';
						$language[ $i ] = 'en';
						$script[ $i ]   = '';
						$locale[ $i ]   = '';

					}

					$out .= '<url_to_connect>' . esc_attr( $connect['url_to_connect'] ) . '</url_to_connect>';
					$out .= '<url>' . json_encode( $url ) . '</url>';
					$out .= '<language>' . json_encode( $language ) . '</language>';
					$out .= '<script>' . json_encode( $script ) . '</script>';
					$out .= '<locale>' . json_encode( $locale ) . '</locale>';

					$out .= '</connect>';

				}

				// generate the final part of the XML file
				$out .= '</root>';

			} else {
				return false;
			}

			echo $out;
			die();

		}
	}

	/**
	 * Echo all the dismissible notices based on the values of the $notices array.
	 *
	 * @param $notices
	 */
	public function dismissible_notice( $notices ) {

		foreach ( $notices as $notice ) {
			echo '<div class="' . esc_attr( $notice['class'] ) . ' settings-error notice is-dismissible below-h2"><p>' . esc_html( $notice['message'] ) . '</p></div>';
		}
	}

	/**
	 * Change the WordPress footer text on all the plugin menus.
	 */
	public function change_footer_text() {

		$screen = get_current_screen();

		if ( $screen->id == $this->screen_id_connections or
			$screen->id == $this->screen_id_help or
			$screen->id == $this->screen_id_pro_version or
			$screen->id == $this->screen_id_export_to_pro or
			$screen->id == $this->screen_id_options ) {

			echo '<a target="_blank" href="http://wordpress.org/support/plugin/hreflang-manager-lite#postform">' . esc_attr__(
				'Contact Support',
				'hreflang-manager-lite'
			) . '</a> | ' .
				'<a target="_blank" href="https://translate.wordpress.org/projects/wp-plugins/hreflang-manager-lite/">' . esc_attr__(
					'Translate',
					'hreflang-manager-lite'
				) . '</a> | ' .
				str_replace(
					array( '[stars]', '[wp.org]' ),
					array(
						'<a target="_blank" href="https://wordpress.org/support/plugin/hreflang-manager-lite/reviews/?filter=5">&#9733;&#9733;&#9733;&#9733;&#9733;</a>',
						'<a target="_blank" href="http://wordpress.org/plugins/hreflang-manager-lite/" >wordpress.org</a>',
					),
					__( 'Add your [stars] on [wp.org] to spread the love.', 'hreflang-manager-lite' )
				);

		}
	}
}
