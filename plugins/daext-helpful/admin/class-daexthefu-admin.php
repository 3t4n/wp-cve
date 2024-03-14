<?php

/*
 * this class should be used to work with the administrative side of wordpress
 */

class daexthefu_Admin {

	protected static $instance = null;
	private $shared = null;

	private $screen_id_statistics = null;
	private $screen_id_maintenance = null;
	private $screen_id_help = null;
	private $screen_id_pro_version = null;
	private $screen_id_options = null;

	public $menu_options = null;

	private function __construct() {

		//assign an instance of the plugin info
		$this->shared = daexthefu_Shared::get_instance();

		//Load admin stylesheets and JavaScript
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		//Add the admin menu
		add_action( 'admin_menu', array( $this, 'me_add_admin_menu' ) );

		//Require and instantiate the class used to register the menu options
		require_once( $this->shared->get( 'dir' ) . 'admin/inc/class-daexthefu-menu-options.php' );
		$this->menu_options = new Daexthefu_Menu_Options( $this->shared );

		//Load the options API registrations and callbacks
		add_action( 'admin_init', array( $this, 'op_register_options' ) );

		//Change the WordPress footer text on all the plugin menus
		add_filter( 'admin_footer_text', array( $this, 'change_footer_text' ) );

	}

	/*
	 * return an instance of this class
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}


	public function enqueue_admin_styles() {

		$screen = get_current_screen();

		//menu statistics
		if ( $screen->id == $this->screen_id_statistics ) {

			wp_enqueue_style( $this->shared->get( 'slug' ) . '-framework-menu',
				$this->shared->get( 'url' ) . 'admin/assets/css/framework/menu.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-menu-dashboard',
				$this->shared->get( 'url' ) . 'admin/assets/css/menu-statistics.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip',
				$this->shared->get( 'url' ) . 'admin/assets/css/jquery-ui-tooltip.css', array(),
				$this->shared->get( 'ver' ) );

			//jQuery UI Dialog
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-jquery-ui-dialog',
				$this->shared->get( 'url' ) . 'admin/assets/css/jquery-ui-dialog.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-jquery-ui-dialog-custom',
				$this->shared->get( 'url' ) . 'admin/assets/css/jquery-ui-dialog-custom.css', array(),
				$this->shared->get( 'ver' ) );

			//Select2
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-select2',
				$this->shared->get( 'url' ) . 'admin/assets/inc/select2/css/select2.min.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-select2-custom',
				$this->shared->get( 'url' ) . 'admin/assets/css/select2-custom.css', array(),
				$this->shared->get( 'ver' ) );

		}

		//menu options
		if ( $screen->id == $this->screen_id_maintenance ) {

			wp_enqueue_style( $this->shared->get( 'slug' ) . '-framework-menu',
				$this->shared->get( 'url' ) . 'admin/assets/css/framework/menu.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip',
				$this->shared->get( 'url' ) . 'admin/assets/css/jquery-ui-tooltip.css', array(),
				$this->shared->get( 'ver' ) );

			//jQuery UI Dialog
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-jquery-ui-dialog',
				$this->shared->get( 'url' ) . 'admin/assets/css/jquery-ui-dialog.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-jquery-ui-dialog-custom',
				$this->shared->get( 'url' ) . 'admin/assets/css/jquery-ui-dialog-custom.css', array(),
				$this->shared->get( 'ver' ) );

			//Select2
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-select2',
				$this->shared->get( 'url' ) . 'admin/assets/inc/select2/css/select2.min.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-select2-custom',
				$this->shared->get( 'url' ) . 'admin/assets/css/select2-custom.css', array(),
				$this->shared->get( 'ver' ) );

		}

		//Menu Help
		if ( $screen->id == $this->screen_id_help ) {

			wp_enqueue_style( $this->shared->get( 'slug' ) . '-menu-help',
				$this->shared->get( 'url' ) . 'admin/assets/css/menu-help.css', array(), $this->shared->get( 'ver' ) );

		}

		//Menu Pro Version
		if ( $screen->id == $this->screen_id_pro_version ) {

			wp_enqueue_style( $this->shared->get( 'slug' ) . '-menu-pro-version',
				$this->shared->get( 'url' ) . 'admin/assets/css/menu-pro-version.css', array(), $this->shared->get( 'ver' ) );

		}

		//menu options
		if ( $screen->id == $this->screen_id_options ) {

			wp_enqueue_style( $this->shared->get( 'slug' ) . '-framework-options',
				$this->shared->get( 'url' ) . 'admin/assets/css/framework/options.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip',
				$this->shared->get( 'url' ) . 'admin/assets/css/jquery-ui-tooltip.css', array(),
				$this->shared->get( 'ver' ) );

			//Select2
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-select2',
				$this->shared->get( 'url' ) . 'admin/assets/inc/select2/css/select2.min.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-select2-custom',
				$this->shared->get( 'url' ) . 'admin/assets/css/select2-custom.css', array(),
				$this->shared->get( 'ver' ) );

			//WP Color Picker
			wp_enqueue_style( 'wp-color-picker' );

		}

	}

	public function enqueue_admin_scripts() {

		$wp_localize_script_data = array(
			'deleteText'         => strip_tags( __( 'Delete', 'daext-helpful' ) ),
			'cancelText'         => strip_tags( __( 'Cancel', 'daext-helpful' ) ),
			'chooseAnOptionText' => strip_tags( __( 'Choose an Option ...', 'daext-helpful' ) ),
			'closeText'          => strip_tags( __( 'Close', 'daext-helpful' ) ),
			'postText'           => strip_tags( __( 'Post', 'daext-helpful' ) ),
			'itemsText'          => strip_tags( __( 'items', 'daext-helpful' ) ),
			'dateTooltipText'    => strip_tags( __( 'The date of the feedback.', 'daext-helpful' ) ),
			'ratingTooltipText'  => strip_tags( __( 'The rating received by the feedback.', 'daext-helpful' ) ),
			'commentTooltipText' => strip_tags( __( 'The comment associated with the feedback.', 'daext-helpful' ) ),
		);

		$screen = get_current_screen();

		//menu statistics
		if ( $screen->id == $this->screen_id_statistics ) {

			//jQuery UI Tooltips
			wp_enqueue_script( 'jquery-ui-tooltip' );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip-init',
				$this->shared->get( 'url' ) . 'admin/assets/js/jquery-ui-tooltip-init.js', array( 'jquery' ),
				$this->shared->get( 'ver' ) );

			//Select2
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-select2',
				$this->shared->get( 'url' ) . 'admin/assets/inc/select2/js/select2.min.js', array( 'jquery' ),
				$this->shared->get( 'ver' ) );

			//Menu Statistics
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-menu-statistics',
				$this->shared->get( 'url' ) . 'admin/assets/js/menu-statistics.js', array(
					'jquery',
					'jquery-ui-dialog',
					'daexthefu-select2',
					'jquery-ui-tooltip',
					'daexthefu-jquery-ui-tooltip-init'
				), $this->shared->get( 'ver' ) );

			wp_localize_script( $this->shared->get( 'slug' ) . '-menu-statistics', 'objectL10n',
				$wp_localize_script_data );

			//Store the JavaScript parameters in the window.DAEXTHEFU_PARAMETERS object
			$script = 'window.DAEXTHEFU_PARAMETERS = {';
			$script .= 'ajaxUrl: "' . admin_url( 'admin-ajax.php' ) . '",';
			$script .= 'nonce: "' . wp_create_nonce( "daexthefu" ) . '",';
			$script .= 'adminUrl: "' . get_admin_url() . '",';
			$script .= '};';
			if ( $script !== false ) {
				wp_add_inline_script( $this->shared->get( 'slug' ) . '-menu-statistics', $script, 'before' );
			}

		}

		//Menu Maintenance
		if ( $screen->id == $this->screen_id_maintenance ) {

			//Maintenance Menu
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-menu-maintenance',
				$this->shared->get( 'url' ) . 'admin/assets/js/menu-maintenance.js',
				array( 'jquery', 'jquery-ui-dialog' ),
				$this->shared->get( 'ver' ) );
			wp_localize_script( $this->shared->get( 'slug' ) . '-menu-maintenance', 'objectL10n',
				$wp_localize_script_data );

			//jQuery UI Tooltip
			wp_enqueue_script( 'jquery-ui-tooltip' );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip-init',
				$this->shared->get( 'url' ) . 'admin/assets/js/jquery-ui-tooltip-init.js', array( 'jquery' ),
				$this->shared->get( 'ver' ) );

			//Select2
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-select2',
				$this->shared->get( 'url' ) . 'admin/assets/inc/select2/js/select2.min.js', array( 'jquery' ),
				$this->shared->get( 'ver' ) );

		}

		//menu options
		if ( $screen->id == $this->screen_id_options ) {

			//jQuery UI Tooltips
			wp_enqueue_script( 'jquery-ui-tooltip' );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip-init',
				$this->shared->get( 'url' ) . 'admin/assets/js/jquery-ui-tooltip-init.js', array( 'jquery' ),
				$this->shared->get( 'ver' ) );

			//Select2
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-select2',
				$this->shared->get( 'url' ) . 'admin/assets/inc/select2/js/select2.min.js', array('jquery'),
				$this->shared->get( 'ver' ) );

			//Color Picker Initialization
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-wp-color-picker-init',
				$this->shared->get( 'url' ) . 'admin/assets/js/wp-color-picker-init.js',
				array( 'jquery', 'wp-color-picker' ), false, true );

			//Menu Options
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-menu-options',
				$this->shared->get( 'url' ) . 'admin/assets/js/menu-options.js', array(
					'jquery'
				), $this->shared->get( 'ver' ) );

		}

		/**
		 * When the editor file is loaded (only in the post editor) add helpful form statistics as
		 * json data in a property of the window.DAEXTHEFU_PARAMETERS object.
		 */
		//Load the assets for the post editor
		$available_post_types_a = get_post_types( array(
			'show_ui' => true
		) );

		//Remove the "attachment" post type
		$available_post_types_a = array_diff( $available_post_types_a, array( 'attachment' ) );
		if ( in_array( $screen->id, $available_post_types_a ) ) {

			//get all the feedback of this form
			global $wpdb;
			$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_feedback";
			$safe_sql   = $wpdb->prepare( "SELECT * FROM $table_name WHERE post_id = %d",
				get_the_ID() );
			$feedback_a = $wpdb->get_results( $safe_sql, ARRAY_A );

			//keep only the feedback of associated with the form with the highest ID
			$positive_value = 0;
			$negative_value = 0;
			foreach ( $feedback_a as $feedback ) {

				//count the number of positive and negative values
				if ( intval( $feedback['value'], 10 ) === 0 ) {
					$negative_value ++;
				} else {
					$positive_value ++;
				}

			}

			$statistics_a = [
				'positive_value' => $positive_value,
				'negative_value' => $negative_value,
			];

			//Store the JavaScript parameters in the window.DAEXTHEFU_PARAMETERS object
			$initialization_script = 'window.DAEXTHEFU_PARAMETERS = {';
			$initialization_script .= "statistics: " . json_encode( $statistics_a );
			$initialization_script .= '};';
			wp_add_inline_script( $this->shared->get( 'slug' ) . '-editor-js', $initialization_script, 'before' );

		}

	}

	/*
	 * plugin activation
	 */
	public function ac_activate( $networkwide ) {

		/*
		 * create options and tables for all the sites in the network
		 */
		if ( function_exists( 'is_multisite' ) and is_multisite() ) {

			/*
			 * if this is a "Network Activation" create the options and tables
			 * for each blog
			 */
			if ( $networkwide ) {

				//get the current blog id
				global $wpdb;
				$current_blog = $wpdb->blogid;

				//create an array with all the blog ids
				$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

				//iterate through all the blogs
				foreach ( $blogids as $blog_id ) {

					//switch to the iterated blog
					switch_to_blog( $blog_id );

					//create options and tables for the iterated blog
					$this->ac_initialize_options();
					$this->ac_create_database_tables();
					$this->ac_initialize_custom_css();

				}

				//switch to the current blog
				switch_to_blog( $current_blog );

			} else {

				/*
				 * if this is not a "Network Activation" create options and
				 * tables only for the current blog
				 */
				$this->ac_initialize_options();
				$this->ac_create_database_tables();
				$this->ac_initialize_custom_css();

			}

		} else {

			/*
			 * if this is not a multisite installation create options and
			 * tables only for the current blog
			 */
			$this->ac_initialize_options();
			$this->ac_create_database_tables();
			$this->ac_initialize_custom_css();

		}

	}

	//create the options and tables for the newly created blog
	public function new_blog_create_options_and_tables( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {

		global $wpdb;

		/*
		 * if the plugin is "Network Active" create the options and tables for
		 * this new blog
		 */
		if ( is_plugin_active_for_network( 'helpful-pro/init.php' ) ) {

			//get the id of the current blog
			$current_blog = $wpdb->blogid;

			//switch to the blog that is being activated
			switch_to_blog( $blog_id );

			//create options and database tables for the new blog
			$this->ac_initialize_options();
			$this->ac_create_database_tables();
			$this->ac_initialize_custom_css();

			//switch to the current blog
			switch_to_blog( $current_blog );

		}

	}

	//delete options and tables for the deleted blog
	public function delete_blog_delete_options_and_tables( $blog_id ) {

		global $wpdb;

		//get the id of the current blog
		$current_blog = $wpdb->blogid;

		//switch to the blog that is being activated
		switch_to_blog( $blog_id );

		//create options and database tables for the new blog
		$this->un_delete_options();
		$this->un_delete_database_tables();

		//switch to the current blog
		switch_to_blog( $current_blog );

	}

	/*
	 * initialize plugin options
	 */
	private function ac_initialize_options() {

		foreach ( $this->shared->get( 'options' ) as $key => $value ) {
			add_option( $key, $value );
		}

	}

	/*
	 * create the plugin database tables
	 */
	private function ac_create_database_tables() {

		global $wpdb;

		//Get the database character collate that will be appended at the end of each query
		$charset_collate = $wpdb->get_charset_collate();

		//check database version and create the database
		if ( intval( get_option( $this->shared->get( 'slug' ) . '_database_version' ), 10 ) < 1 ) {

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			//create *prefix*_feedback
			$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_feedback";
			$sql        = "CREATE TABLE $table_name (
                feedback_id bigint(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                date datetime DEFAULT NULL,
                post_id bigint(20) UNSIGNED DEFAULT NULL,
                value tinyint(1) UNSIGNED DEFAULT NULL,
                description text NOT NULL DEFAULT '',
                ip_address text NOT NULL DEFAULT ''
            ) $charset_collate";
			dbDelta( $sql );

			//create *prefix*_archive
			$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_archive";
			$sql        = "CREATE TABLE $table_name (
                archive_id bigint(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                post_id bigint(20) UNSIGNED,
                post_title text NOT NULL DEFAULT '',
                post_type varchar(20) NOT NULL DEFAULT '',
                post_date datetime DEFAULT NULL,
                positive_feedback bigint(20) UNSIGNED,
                negative_feedback bigint(20) UNSIGNED,
                pfr tinyint(1) DEFAULT -1
            ) $charset_collate";
			dbDelta( $sql );

			//Update database version
			update_option( $this->shared->get( 'slug' ) . '_database_version', "1" );

		}

	}

	/*
	 * plugin delete
	 */
	static public function un_delete() {

		/*
		 * delete options and tables for all the sites in the network
		 */
		if ( function_exists( 'is_multisite' ) and is_multisite() ) {

			//get the current blog id
			global $wpdb;
			$current_blog = $wpdb->blogid;

			//create an array with all the blog ids
			$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

			//iterate through all the blogs
			foreach ( $blogids as $blog_id ) {

				//switch to the iterated blog
				switch_to_blog( $blog_id );

				//create options and tables for the iterated blog
				daexthefu_Admin::un_delete_options();
				daexthefu_Admin::un_delete_database_tables();

			}

			//switch to the current blog
			switch_to_blog( $current_blog );

		} else {

			/*
			 * if this is not a multisite installation delete options and
			 * tables only for the current blog
			 */
			daexthefu_Admin::un_delete_options();
			daexthefu_Admin::un_delete_database_tables();

		}

	}

	/*
	 * Delete plugin options.
	 */
	static public function un_delete_options() {

		//assign an instance of Daexthefu_Shared
		$shared = Daexthefu_Shared::get_instance();

		foreach ( $shared->get( 'options' ) as $key => $value ) {
			delete_option( $key );
		}

	}

	/*
	 * delete plugin database tables
	 */
	static public function un_delete_database_tables() {

		//assign an instance of Daexthefu_Shared
		$shared = Daexthefu_Shared::get_instance();

		global $wpdb;
		$table_name = $wpdb->prefix . $shared->get( 'slug' ) . "_archive";
		$sql        = "DROP TABLE $table_name";
		$wpdb->query( $sql );

		$table_name = $wpdb->prefix . $shared->get( 'slug' ) . "_feedback";
		$sql        = "DROP TABLE $table_name";
		$wpdb->query( $sql );

	}

	/*
	 * register the admin menu
	 */
	public function me_add_admin_menu() {

		add_menu_page(
			esc_html__( 'HF', 'daext-helpful' ),
			esc_html__( 'Helpful', 'daext-helpful' ),
			get_option( $this->shared->get( 'slug' ) . '_statistics_menu_capability' ),
			$this->shared->get( 'slug' ) . '-statistics',
			array( $this, 'me_display_menu_statistics' ),
			'dashicons-thumbs-up'
		);

		$this->screen_id_statistics = add_submenu_page(
			$this->shared->get( 'slug' ) . '-statistics',
			esc_html__( 'HF - Statistics', 'daext-helpful' ),
			esc_html__( 'Statistics', 'daext-helpful' ),
			get_option( $this->shared->get( 'slug' ) . '_statistics_menu_capability' ),
			$this->shared->get( 'slug' ) . '-statistics',
			array( $this, 'me_display_menu_statistics' )
		);

		$this->screen_id_maintenance = add_submenu_page(
			$this->shared->get( 'slug' ) . '-statistics',
			esc_html__( 'HF - Maintenance', 'daext-helpful' ),
			esc_html__( 'Maintenance', 'daext-helpful' ),
			get_option( $this->shared->get( 'slug' ) . '_maintenance_menu_capability' ),
			$this->shared->get( 'slug' ) . '-maintenance',
			array( $this, 'me_display_menu_maintenance' )
		);

		$this->screen_id_help = add_submenu_page(
			$this->shared->get( 'slug' ) . '-statistics',
			esc_html__( 'HF - Help', 'daext-helpful' ),
			esc_html__( 'Help', 'daext-helpful' ),
			'manage_options',
			$this->shared->get( 'slug' ) . '-help',
			array( $this, 'me_display_menu_help' )
		);

		$this->screen_id_pro_version = add_submenu_page(
			$this->shared->get( 'slug' ) . '-statistics',
			esc_html__( 'HF - Pro Version', 'daext-helpful' ),
			esc_html__( 'Pro Version', 'daext-helpful' ),
			'manage_options',
			$this->shared->get( 'slug' ) . '-pro-version',
			array( $this, 'me_display_menu_pro_version' )
		);

		$this->screen_id_options = add_submenu_page(
			$this->shared->get( 'slug' ) . '-statistics',
			esc_html__( 'HF - Options', 'daext-helpful' ),
			esc_html__( 'Options', 'daext-helpful' ),
			'manage_options',
			$this->shared->get( 'slug' ) . '-options',
			array( $this, 'me_display_menu_options' )
		);

	}

	/*
	 * includes the statistics menu
	 */
	public function me_display_menu_statistics() {
		include_once( 'view/statistics.php' );
	}

	/*
	 * includes the maintenance menu
	 */
	public function me_display_menu_maintenance() {
		include_once( 'view/maintenance.php' );
	}

	/*
	 * includes the form menu
	 */
	public function me_display_menu_help() {
		include_once( 'view/help.php' );
	}

	/*
	 * includes the pro version menu
	 */
	public function me_display_menu_pro_version() {
		include_once( 'view/pro_version.php' );
	}

	/*
	 * includes the options menu
	 */
	public function me_display_menu_options() {
		include_once( 'view/options.php' );
	}

	/*
	 * register options
	 */
	public function op_register_options() {

		$this->menu_options->register_options();

	}

	/*
     * Generate the custom.css file based on the values of the options and write them down in the custom.css file.
     */
	public function write_custom_css() {

		//turn on output buffering
		ob_start();

		//Button Font Family
		echo '.daexthefu-button-text, .daexthefu-comment-submit, .daexthefu-comment-cancel{font-family: ' .
		     htmlspecialchars( get_option( $this->shared->get( 'slug' ) . '_button_font_family' ),
			     ENT_COMPAT ) . ' !important; }';

		//Feedback Button Font Size
		echo '.daexthefu-button-text{font-size: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_rating_button_font_size' ) ) . 'px !important; }';

		//Feedback Button Font Style
		echo '.daexthefu-button-text{font-style: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_rating_button_font_style' ) ) . ' !important; }';

		//Feedback Button Font Weight
		echo '.daexthefu-button-text{font-weight: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_rating_button_font_weight' ) ) . ' !important; }';

		//Feedback Button Line Height
		echo '.daexthefu-button-text{line-height: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_rating_button_line_height' ) ) . 'px !important; }';

		//Feedback Button Font Color
		echo '.daexthefu-button-text{color: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_rating_button_font_color' ) ) . ' !important; }';

		//Button Font Size
		echo '.daexthefu-comment-submit, .daexthefu-comment-cancel{font-size: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_button_font_size' ) ) . 'px !important; }';

		//Button Font Style
		echo '.daexthefu-comment-submit, .daexthefu-comment-cancel{font-style: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_button_font_style' ) ) . ' !important; }';

		//Button Font Weight
		echo '.daexthefu-comment-submit, .daexthefu-comment-cancel{font-weight: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_button_font_weight' ) ) . ' !important; }';

		//Button Line Height
		echo '.daexthefu-comment-submit, .daexthefu-comment-cancel{line-height: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_button_line_height' ) ) . 'px !important; }';

		//Primary Button Background Color
		echo '.daexthefu-comment-submit{background: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_primary_button_background_color' ) ) . ' !important; }';

		//Primary Button Border Color
		echo '.daexthefu-comment-submit{border-color: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_primary_button_border_color' ) ) . ' !important; }';

		//Secondary Button Background Color
		echo '.daexthefu-comment-cancel{background: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_secondary_button_background_color' ) ) . ' !important; }';

		//Secondary Button Border Color
		echo '.daexthefu-comment-cancel{border-color: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_secondary_button_border_color' ) ) . ' !important; }';

		//Comment Intro Font Family
		echo '.daexthefu-comment-label,
		.daexthefu-comment-character-counter-number, .daexthefu-comment-character-counter-text,
		.daexthefu-successful-submission-text{font-family: ' .
		     htmlspecialchars( get_option( $this->shared->get( 'slug' ) . '_base_font_family' ),
			     ENT_COMPAT ) . ' !important; }';

		//Comment Intro Font Size
		echo '.daexthefu-comment-label,
		.daexthefu-comment-character-counter-number, .daexthefu-comment-character-counter-text,
		.daexthefu-successful-submission-text{font-size: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_base_font_size' ) ) . 'px !important; }';

		//Comment Intro Font Style
		echo '.daexthefu-comment-label,
		.daexthefu-comment-character-counter-number, .daexthefu-comment-character-counter-text,
		.daexthefu-successful-submission-text{font-style: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_base_font_style' ) ) . ' !important; }';

		//Comment Intro Font Weight
		echo '.daexthefu-comment-label,
		.daexthefu-comment-character-counter-number, .daexthefu-comment-character-counter-text,
		.daexthefu-successful-submission-text{font-weight: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_base_font_weight' ) ) . ' !important; }';

		//Comment Intro Line Height
		echo '.daexthefu-comment-label,
		.daexthefu-comment-character-counter-number, .daexthefu-comment-character-counter-text,
		.daexthefu-successful-submission-text{line-height: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_base_line_height' ) ) . 'px !important; }';

		//Comment Intro Font Color
		echo '.daexthefu-comment-label{color: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_label_font_color' ) ) . ' !important; }';

		//Successful Submission Font Color
		echo '.daexthefu-successful-submission-text{color: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_successful_submission_font_color' ) ) . ' !important; }';

		//Character Counter Font Color
		echo '.daexthefu-comment-character-counter-number, .daexthefu-comment-character-counter-text{color: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_character_counter_font_color' ) ) . ' !important; }';

		//Primary Button Font Color
		echo '.daexthefu-comment-submit{color: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_primary_button_font_color' ) ) . ' !important; }';

		//Secondary Button Font Color
		echo '.daexthefu-comment-cancel{color: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_secondary_button_font_color' ) ) . ' !important; }';

		//Comment Textarea Border Color Selected
		echo '.daexthefu-comment-textarea:focus{border-color: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_comment_textarea_border_color_selected' ) ) . ' !important; }';

		//Comment Textarea Font Family
		echo '.daexthefu-comment-textarea{font-family: ' .
		     htmlspecialchars( get_option( $this->shared->get( 'slug' ) . '_comment_textarea_font_family' ),
			     ENT_COMPAT ) . ' !important; }';

		//Comment Textarea Font Size
		echo '.daexthefu-comment-textarea{font-size: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_comment_textarea_font_size' ) ) . 'px !important; }';

		//Comment Textarea Font Style
		echo '.daexthefu-comment-textarea{font-style: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_comment_textarea_font_style' ) ) . ' !important; }';

		//Comment Textarea Font Weight
		echo '.daexthefu-comment-textarea{font-weight: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_comment_textarea_font_weight' ) ) . ' !important; }';

		//Comment Textarea Line Height
		echo '.daexthefu-comment-textarea{line-height: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_comment_textarea_line_height' ) ) . 'px !important; }';

		//Comment Textarea Font Color
		echo '.daexthefu-comment-textarea{color: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_comment_textarea_font_color' ) ) . ' !important; }';

		//Comment Textarea Background Color
		echo '.daexthefu-comment-textarea{background: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_comment_textarea_background_color' ) ) . ' !important; }';

		//Comment Textarea Border Color
		echo '.daexthefu-comment-textarea{border-color: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_comment_textarea_border_color' ) ) . ' !important; }';

		//Background
		if ( intval( get_option( $this->shared->get( 'slug' ) . '_background' ), 10 ) === 1 ) {
			echo '.daexthefu-container{background: ' .
			     esc_attr( get_option( $this->shared->get( 'slug' ) . '_background_color' ) ) . ' !important; }';
		}

		//Border
		$border = intval( get_option( $this->shared->get( 'slug' ) . '_border' ), 10 );
		switch ( $border ) {

			case 0:
				echo '.daexthefu-container{border-width: 0 !important; }';
				break;

			case 1:
				echo '.daexthefu-container{border-width: 1px 0 !important; }';
				break;

			case 2:
				echo '.daexthefu-container{border-width: 0 1px !important; }';
				break;

			case 3:
				echo '.daexthefu-container{border-width: 1px !important; }';

				//The border radius on the container is applied only if the container border is set to "Complete"
				echo '.daexthefu-container{border-radius: ' .
				     esc_attr( get_option( $this->shared->get( 'slug' ) . '_border_radius' ) ) . 'px !important; }';

				break;

		}

		echo '.daexthefu-container{border-color: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_border_color' ) ) . ' !important; }';

		//Container Horizontal Padding/Container Vertical Padding
		echo '.daexthefu-container{padding: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_container_vertical_padding' ) ) . 'px ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_container_horizontal_padding' ) ) . 'px ' .
		     '!important; }';

		//Container Horizontal Margin/Container Vertical Margin
		echo '.daexthefu-container{margin: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_container_vertical_margin' ) ) . 'px ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_container_horizontal_margin' ) ) . 'px ' .
		     '!important; }';

		//Title Font Family
		echo '.daexthefu-title{font-family: ' .
		     htmlspecialchars( get_option( $this->shared->get( 'slug' ) . '_title_font_family' ),
			     ENT_COMPAT ) . ' !important; }';

		//Title Font Size
		echo '.daexthefu-title{font-size: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_title_font_size' ) ) . 'px !important; }';

		//Title Font Style
		echo '.daexthefu-title{font-style: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_title_font_style' ) ) . ' !important; }';

		//Title Font Weight
		echo '.daexthefu-title{font-weight: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_title_font_weight' ) ) . ' !important; }';

		//Title Line Height
		echo '.daexthefu-title{line-height: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_title_line_height' ) ) . 'px !important; }';

		//Title Font Color
		echo '.daexthefu-title{color: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_title_font_color' ) ) . ' !important; }';

		//Description Font Family
		echo '.daexthefu-description{font-family: ' .
		     htmlspecialchars( get_option( $this->shared->get( 'slug' ) . '_description_font_family' ),
			     ENT_COMPAT ) . ' !important; }';

		//Description Font Size
		echo '.daexthefu-description{font-size: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_description_font_size' ) ) . 'px !important; }';

		//Description Font Style
		echo '.daexthefu-description{font-style: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_description_font_style' ) ) . ' !important; }';

		//Description Font Weight
		echo '.daexthefu-description{font-weight: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_description_font_weight' ) ) . ' !important; }';

		//Description Line Height
		echo '.daexthefu-description{line-height: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_description_line_height' ) ) . 'px !important; }';

		//Description Font Color
		echo '.daexthefu-description{color: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_description_font_color' ) ) . 'px !important; }';

		//Button Icon Primary Color
		echo '.happy-face-cls-1, .sad-face-cls-1, .thumb-up-cls-1, .thumb-down-cls-1{fill: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_button_icon_primary_color' ) ) . ' !important; }';

		//Button Icon Secondary Color
		echo '.happy-face-cls-3, .sad-face-cls-4, .thumb-up-cls-3, .thumb-down-cls-3{fill: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_button_icon_secondary_color' ) ) . ' !important; }';

		//Button Icon Primary Color Positive Selected
		echo '.daexthefu-yes:hover .daexthefu-icon-primary-color,
		.daexthefu-yes-selected .daexthefu-icon-primary-color' .
		     '{fill: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_button_icon_primary_color_positive_selected' ) ) . ' !important }';

		//Button Icon Secondary Color Positive Selected
		echo '.daexthefu-yes:hover .daexthefu-icon-secondary-color,' .
		     '.daexthefu-yes-selected .daexthefu-icon-secondary-color' .
		     '{fill:' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_button_icon_secondary_color_positive_selected' ) ) . ' !important }';

		//Button Icon Primary Color Negative Selected
		echo '.daexthefu-no:hover .daexthefu-icon-primary-color,
			.daexthefu-no-selected .daexthefu-icon-primary-color' .
		     '{fill: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_button_icon_primary_color_negative_selected' ) ) . ' !important }';

		//Button Icon Secondary Color Negative Selected
		echo '.daexthefu-no:hover .daexthefu-icon-secondary-color,' .
		     '.daexthefu-no-selected .daexthefu-icon-secondary-color' .
		     '{fill:' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_button_icon_secondary_color_negative_selected' ) ) . ' !important }';

		//Positive Feedback Button Background Color
		echo '.daexthefu-button-type-icon-and-text.daexthefu-button.daexthefu-yes,
		.daexthefu-button-type-text.daexthefu-button.daexthefu-yes,
		.daexthefu-button-type-icon-and-text.daexthefu-button.daexthefu-no,
		.daexthefu-button-type-text.daexthefu-button.daexthefu-no{background: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_rating_button_background_color' ) ) . ' !important; }';

		//Button Icons Border Color
		echo '.daexthefu-icon-circle{fill: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_button_icons_border_color' ) ) . ' !important; }';

		//Border Radius
		echo '.daexthefu-button,
		.daexthefu-comment-textarea{border-radius: ' .
		     esc_attr( get_option( $this->shared->get( 'slug' ) . '_border_radius' ) ) . 'px !important; }';

		$custom_css_string = ob_get_clean();

		//Get the upload directory path and the file path
		$upload_dir_path  = $this->get_plugin_upload_path();
		$upload_file_path = $this->get_plugin_upload_path() . 'custom-' . get_current_blog_id() . '.css';

		//If the plugin upload directory doesn't exists create it
		if ( ! is_dir( $upload_dir_path ) ) {
			mkdir( $upload_dir_path );
		}

		//Write the custom css file
		return @file_put_contents( $upload_file_path,
			$custom_css_string, LOCK_EX );

	}

	/*
	 * initialize the custom-[blog_id].css file
	 */
	public function ac_initialize_custom_css() {

		/*
		 * Write the custom-[blog_id].css file or die if the file can't be created or modified.
		 */
		if ( $this->write_custom_css() === false ) {
			die( "The plugin can't write files in the upload directory." );
		}

	}

	/**
	 * Get the plugin upload path.
	 *
	 * @return string The plugin upload path
	 */
	public function get_plugin_upload_path() {

		$upload_path = WP_CONTENT_DIR . '/uploads/daexthefu_uploads/';

		return $upload_path;

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

		if ( $screen->id == $this->screen_id_statistics or
		     $screen->id == $this->screen_id_maintenance or
		     $screen->id == $this->screen_id_help or
		     $screen->id == $this->screen_id_options ) {

			echo '<a target="_blank" href="http://wordpress.org/support/plugin/daext-helpful#postform">' . esc_attr__( 'Contact Support',
					'daext-helpful' ) . '</a> | ' .
			     '<a target="_blank" href="https://translate.wordpress.org/projects/wp-plugins/daext-helpful/">' . esc_attr__( 'Translate',
					'daext-helpful' ) . '</a> | ' .
			     str_replace(
				     [ '[stars]', '[wp.org]' ],
				     [
					     '<a target="_blank" href="https://wordpress.org/support/plugin/daext-helpful/reviews/?filter=5">&#9733;&#9733;&#9733;&#9733;&#9733;</a>',
					     '<a target="_blank" href="http://wordpress.org/plugins/daext-helpful/" >wordpress.org</a>'
				     ],
				     __( 'Add your [stars] on [wp.org] to spread the love.', 'daext-helpful' )
			     );

		}

	}

}