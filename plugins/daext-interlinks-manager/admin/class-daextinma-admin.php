<?php

/*
 * this class should be used to work with the administrative side of wordpress
 */

class Daextinma_Admin {

	protected static $instance = null;
	private $shared = null;

	private $screen_id_dashboard = null;
	private $screen_id_juice = null;
	private $screen_id_help = null;
	private $screen_id_pro_version = null;
	private $screen_id_options = null;
	private $menu_options = null;

	private function __construct() {

		//assign an instance of the plugin info
		$this->shared = Daextinma_Shared::get_instance();

		//Load admin stylesheets and JavaScript
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		//Add the admin menu
		add_action( 'admin_menu', array( $this, 'me_add_admin_menu' ) );

		//Load the options API registrations and callbacks
		add_action( 'admin_init', array( $this, 'op_register_options' ) );

		//Add the meta box
		add_action( 'add_meta_boxes', array( $this, 'create_meta_box' ) );

		//Save the meta box
		add_action( 'save_post', array( $this, 'daextinma_save_meta_interlinks_options' ) );

		//this hook is triggered during the creation of a new blog
		add_action( 'wpmu_new_blog', array( $this, 'new_blog_create_options_and_tables' ), 10, 6 );

		//this hook is triggered during the deletion of a blog
		add_action( 'delete_blog', array( $this, 'delete_blog_delete_options_and_tables' ), 10, 1 );

		//Require and instantiate the class used to register the menu options
		require_once( $this->shared->get( 'dir' ) . 'admin/inc/class-daextinma-menu-options.php' );
		$this->menu_options = new Daextinma_Menu_Options( $this->shared );

	}

	/*
	 * return an istance of this class
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	public function enqueue_admin_styles() {

		$screen = get_current_screen();

		//menu dashboard
		if ( $screen->id == $this->screen_id_dashboard ) {

			wp_enqueue_style( $this->shared->get( 'slug' ) . '-framework-menu',
				$this->shared->get( 'url' ) . 'admin/assets/css/framework/menu.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-menu-dashboard',
				$this->shared->get( 'url' ) . 'admin/assets/css/menu-dashboard.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip',
				$this->shared->get( 'url' ) . 'admin/assets/css/jquery-ui-tooltip.css', array(),
				$this->shared->get( 'ver' ) );

			//Chosen
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-chosen',
				$this->shared->get( 'url' ) . 'admin/assets/inc/chosen/chosen-min.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-chosen-custom',
				$this->shared->get( 'url' ) . 'admin/assets/css/chosen-custom.css', array(),
				$this->shared->get( 'ver' ) );

		}

		//menu juice
		if ( $screen->id == $this->screen_id_juice ) {

			wp_enqueue_style( $this->shared->get( 'slug' ) . '-framework-menu',
				$this->shared->get( 'url' ) . 'admin/assets/css/framework/menu.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-menu-juice',
				$this->shared->get( 'url' ) . 'admin/assets/css/menu-juice.css', array(), $this->shared->get( 'ver' ) );
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

		}

		//Menu Help
		if ( $screen->id == $this->screen_id_help ) {

			wp_enqueue_style( $this->shared->get( 'slug' ) . '-menu-help',
				$this->shared->get( 'url' ) . 'admin/assets/css/menu-help.css', array(), $this->shared->get( 'ver' ) );

		}

		//Menu Pro Version
		if ( $screen->id == $this->screen_id_pro_version ) {

			//Pro Version Menu
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-menu-pro-version',
				$this->shared->get( 'url' ) . 'admin/assets/css/menu-pro-version.css', array(),
				$this->shared->get( 'ver' ) );

		}

		//menu options
		if ( $screen->id == $this->screen_id_options ) {

			wp_enqueue_style( $this->shared->get( 'slug' ) . '-framework-options',
				$this->shared->get( 'url' ) . 'admin/assets/css/framework/options.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip',
				$this->shared->get( 'url' ) . 'admin/assets/css/jquery-ui-tooltip.css', array(),
				$this->shared->get( 'ver' ) );

			//Chosen
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-chosen',
				$this->shared->get( 'url' ) . 'admin/assets/inc/chosen/chosen-min.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-chosen-custom',
				$this->shared->get( 'url' ) . 'admin/assets/css/chosen-custom.css', array(),
				$this->shared->get( 'ver' ) );

		}

		/*
		 * Load the post editor CSS if at least one of the two meta box is
		 * enabled with the current $screen->id
		 */
		$load_post_editor_css = false;

		$interlinks_options_post_types_a = maybe_unserialize( get_option( $this->shared->get( 'slug' ) . '_interlinks_options_post_types' ) );
		if ( is_array( $interlinks_options_post_types_a ) and in_array( $screen->id,
				$interlinks_options_post_types_a ) ) {
			$load_post_editor_css = true;
		}

		$interlinks_optimization_post_types_a = maybe_unserialize( get_option( $this->shared->get( 'slug' ) . '_interlinks_optimization_post_types' ) );
		if ( is_array( $interlinks_optimization_post_types_a ) and in_array( $screen->id,
				$interlinks_optimization_post_types_a ) ) {
			$load_post_editor_css = true;
		}

		if ( $load_post_editor_css ) {

			//Post Editor CSS
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-post-editor',
				$this->shared->get( 'url' ) . 'admin/assets/css/post-editor.css', array(),
				$this->shared->get( 'ver' ) );

		}

	}

	/*
	 * enqueue admin-specific javascript
	 */
	public function enqueue_admin_scripts() {

		//Store the JavaScript parameters in the window.DAEXTLETAL_PARAMETERS object
		$initialization_script = 'window.DAEXTINMA_PARAMETERS = {';
		$initialization_script .= 'ajax_url: "' . admin_url( 'admin-ajax.php' ) . '",';
		$initialization_script .= 'nonce: "' . wp_create_nonce( "daextinma" ) . '",';
		$initialization_script .= 'admin_url: "' . get_admin_url() . '"';
		$initialization_script .= '};';

		$wp_localize_script_data = array(
			'deleteText'             => strip_tags( __( 'Delete', 'daext-interlinks-manager') ),
			'cancelText'             => strip_tags( __( 'Cancel', 'daext-interlinks-manager') ),
			'chooseAnOptionText'     => strip_tags( __( 'Choose an Option ...', 'daext-interlinks-manager') ),
			'closeText'              => strip_tags( __( 'Close', 'daext-interlinks-manager') ),
			'postText'               => strip_tags( __( 'Post', 'daext-interlinks-manager') ),
			'anchorTextText'         => strip_tags( __( 'Anchor Text', 'daext-interlinks-manager') ),
			'juiceText'              => strip_tags( __( 'Juice (Value)', 'daext-interlinks-manager') ),
			'juiceVisualText'        => strip_tags( __( 'Juice (Visual)', 'daext-interlinks-manager') ),
			'postTooltipText'        => strip_tags( __( 'The post that includes the link.', 'daext-interlinks-manager') ),
			'anchorTextTooltipText'  => strip_tags( __( 'The anchor text of the link.', 'daext-interlinks-manager') ),
			'juiceTooltipText'       => strip_tags( __( 'The link juice generated by the link.', 'daext-interlinks-manager') ),
			'juiceVisualTooltipText' => strip_tags( __( 'The visual representation of the link juice generated by the link.', 'daext-interlinks-manager') ),
			'juiceModalTitleText'    => strip_tags( __( 'Internal Inbound Links for', 'daext-interlinks-manager') ),
			'itemsText'              => strip_tags( __( 'items', 'daext-interlinks-manager') )
		);

		$screen = get_current_screen();

		//menu dashboard
		if ( $screen->id == $this->screen_id_dashboard ) {

			wp_enqueue_script( $this->shared->get( 'slug' ) . '-menu-dashboard',
				$this->shared->get( 'url' ) . 'admin/assets/js/menu-dashboard.js', array( 'jquery' ),
				$this->shared->get( 'ver' ) );
			wp_add_inline_script( $this->shared->get( 'slug' ) . '-menu-dashboard', $initialization_script, 'before' );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip-init',
				$this->shared->get( 'url' ) . 'admin/assets/js/jquery-ui-tooltip-init.js',
				array( 'jquery', 'jquery-ui-tooltip' ), $this->shared->get( 'ver' ) );

			//Chosen
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-chosen',
				$this->shared->get( 'url' ) . 'admin/assets/inc/chosen/chosen-min.js', array( 'jquery' ),
				$this->shared->get( 'ver' ) );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-chosen-init',
				$this->shared->get( 'url' ) . 'admin/assets/js/chosen-init.js', array( 'jquery' ),
				$this->shared->get( 'ver' ) );
			wp_localize_script( $this->shared->get( 'slug' ) . '-chosen-init', 'objectL10n', $wp_localize_script_data );
		}

		//menu juice
		if ( $screen->id == $this->screen_id_juice ) {

			wp_enqueue_script( $this->shared->get( 'slug' ) . '-menu-juice',
				$this->shared->get( 'url' ) . 'admin/assets/js/menu-juice.js', array( 'jquery', 'jquery-ui-dialog' ),
				$this->shared->get( 'ver' ) );
			wp_add_inline_script( $this->shared->get( 'slug' ) . '-menu-juice', $initialization_script, 'before' );
			wp_localize_script( $this->shared->get( 'slug' ) . '-menu-juice', 'objectL10n', $wp_localize_script_data );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip-init',
				$this->shared->get( 'url' ) . 'admin/assets/js/jquery-ui-tooltip-init.js',
				array( 'jquery', 'jquery-ui-tooltip' ), $this->shared->get( 'ver' ) );

		}

		//menu options
		if ( $screen->id == $this->screen_id_options ) {

			wp_enqueue_script( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip-init',
				$this->shared->get( 'url' ) . 'admin/assets/js/jquery-ui-tooltip-init.js',
				array( 'jquery', 'jquery-ui-tooltip' ), $this->shared->get( 'ver' ) );

			//Chosen
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-chosen',
				$this->shared->get( 'url' ) . 'admin/assets/inc/chosen/chosen-min.js', array( 'jquery' ),
				$this->shared->get( 'ver' ) );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-chosen-init',
				$this->shared->get( 'url' ) . 'admin/assets/js/chosen-init.js', array( 'jquery' ),
				$this->shared->get( 'ver' ) );
			wp_localize_script( $this->shared->get( 'slug' ) . '-chosen-init', 'objectL10n', $wp_localize_script_data );

		}

		//Load the post editor JS if at least one of the two meta boxes is enabled with the current $screen->id
		$load_post_editor_js = false;

		$interlinks_options_post_types_a = maybe_unserialize( get_option( $this->shared->get( 'slug' ) . '_interlinks_options_post_types' ) );
		if ( is_array( $interlinks_options_post_types_a ) and in_array( $screen->id,
				$interlinks_options_post_types_a ) ) {
			$load_post_editor_js = true;
		}

		$interlinks_optimization_post_types_a = maybe_unserialize( get_option( $this->shared->get( 'slug' ) . '_interlinks_optimization_post_types' ) );
		if ( is_array( $interlinks_optimization_post_types_a ) and in_array( $screen->id,
				$interlinks_optimization_post_types_a ) ) {
			$load_post_editor_js = true;
		}

		if ( $load_post_editor_js ) {

			//Post Editor Js
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-post-editor',
				$this->shared->get( 'url' ) . 'admin/assets/js/post-editor.js', array( 'jquery' ),
				$this->shared->get( 'ver' ) );
			wp_add_inline_script( $this->shared->get( 'slug' ) . '-post-editor', $initialization_script, 'before' );

		}

	}

	/*
	 * plugin activation
	 */
	public function ac_activate( $networkwide ) {

		/*
		 * delete options and tables for all the sites in the network
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

					//swith to the iterated blog
					switch_to_blog( $blog_id );

					//create options and tables for the iterated blog
					$this->ac_initialize_options();
					$this->ac_create_database_tables();

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

			}

		} else {

			/*
			 * if this is not a multisite installation create options and
			 * tables only for the current blog
			 */
			$this->ac_initialize_options();
			$this->ac_create_database_tables();

		}

	}

	//create the options and tables for the newly created blog
	public function new_blog_create_options_and_tables( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {

		global $wpdb;

		/*
		 * if the plugin is "Network Active" create the options and tables for
		 * this new blog
		 */
		if ( is_plugin_active_for_network( 'interlinks-manager/init.php' ) ) {

			//get the id of the current blog
			$current_blog = $wpdb->blogid;

			//switch to the blog that is being activated
			switch_to_blog( $blog_id );

			//create options and database tables for the new blog
			$this->ac_initialize_options();
			$this->ac_create_database_tables();

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

			//create *prefix*_archive
			$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_archive";
			$sql        = "CREATE TABLE $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                post_id bigint(20) NOT NULL DEFAULT '0',
                post_title text NOT NULL DEFAULT '',
                post_type varchar(20) NOT NULL DEFAULT '',
                post_date datetime DEFAULT NULL,
                manual_interlinks bigint(20) NOT NULL DEFAULT '0',
                content_length bigint(20) NOT NULL DEFAULT '0',
                recommended_interlinks bigint(20) NOT NULL DEFAULT '0',
                optimization tinyint(1) NOT NULL DEFAULT '0'
            ) $charset_collate";

			dbDelta( $sql );

			//create *prefix*_juice
			global $wpdb;
			$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_juice";
			$sql        = "CREATE TABLE $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                url varchar(2083) NOT NULL DEFAULT '',
                iil bigint(20) NOT NULL DEFAULT '0',
                juice bigint(20) NOT NULL DEFAULT '0',
                juice_relative bigint(20) NOT NULL DEFAULT '0'
            ) $charset_collate";

			dbDelta( $sql );

			//create *prefix*_anchors
			global $wpdb;
			$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_anchors";
			$sql        = "CREATE TABLE $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                url varchar(2083) NOT NULL DEFAULT '',
                anchor longtext NOT NULL DEFAULT '',
                post_id bigint(20) NOT NULL DEFAULT '0',
                post_title text NOT NULL DEFAULT '',
                juice bigint(20) NOT NULL DEFAULT '0'
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

				//swith to the iterated blog
				switch_to_blog( $blog_id );

				//create options and tables for the iterated blog
				Daextinma_Admin::un_delete_options();
				Daextinma_Admin::un_delete_database_tables();

			}

			//switch to the current blog
			switch_to_blog( $current_blog );

		} else {

			/*
			 * if this is not a multisite installation delete options and
			 * tables only for the current blog
			 */
			Daextinma_Admin::un_delete_options();
			Daextinma_Admin::un_delete_database_tables();

		}

	}

	/*
	 * delete plugin options
	 */
	static public function un_delete_options() {

		//assign an instance of Daextamp_Shared
		$shared = Daextinma_Shared::get_instance();

		foreach ( $shared->get( 'options' ) as $key => $value ) {
			delete_option( $key );
		}

	}

	/*
	 * delete plugin database tables
	 */
	static public function un_delete_database_tables() {

		//assign an instance of Daextinma_Shared
		$shared = Daextinma_Shared::get_instance();

		global $wpdb;

		$table_name = $wpdb->prefix . $shared->get( 'slug' ) . "_archive";
		$sql        = "DROP TABLE $table_name";
		$wpdb->query( $sql );

		$table_name = $wpdb->prefix . $shared->get( 'slug' ) . "_juice";
		$sql        = "DROP TABLE $table_name";
		$wpdb->query( $sql );

		$table_name = $wpdb->prefix . $shared->get( 'slug' ) . "_anchors";
		$sql        = "DROP TABLE $table_name";
		$wpdb->query( $sql );

	}

	/*
	 * register the admin menu
	 */
	public function me_add_admin_menu() {

		add_menu_page(
			esc_html__( 'IM', 'daext-interlinks-manager'),
			esc_html__( 'Interlinks', 'daext-interlinks-manager'),
			'edit_posts',
			$this->shared->get( 'slug' ) . '-dashboard',
			array( $this, 'me_display_menu_dashboard' ),
			'dashicons-admin-links'
		);

		$this->screen_id_dashboard = add_submenu_page(
			$this->shared->get( 'slug' ) . '-dashboard',
			esc_html__( 'IM - Dashboard', 'daext-interlinks-manager'),
			esc_html__( 'Dashboard', 'daext-interlinks-manager'),
			'edit_posts',
			$this->shared->get( 'slug' ) . '-dashboard',
			array( $this, 'me_display_menu_dashboard' )
		);

		$this->screen_id_juice = add_submenu_page(
			$this->shared->get( 'slug' ) . '-dashboard',
			esc_html__( 'IM - Juice', 'daext-interlinks-manager'),
			esc_html__( 'Juice', 'daext-interlinks-manager'),
			'edit_posts',
			$this->shared->get( 'slug' ) . '-juice',
			array( $this, 'me_display_menu_juice' )
		);

		$this->screen_id_help = add_submenu_page(
			$this->shared->get( 'slug' ) . '-dashboard',
			esc_html__( 'IM - Help', 'daext-interlinks-manager'),
			esc_html__( 'Help', 'daext-interlinks-manager'),
			'edit_posts',
			$this->shared->get( 'slug' ) . '-help',
			array( $this, 'me_display_menu_help' )
		);

		$this->screen_id_pro_version = add_submenu_page(
			$this->shared->get( 'slug' ) . '-dashboard',
			esc_html__( 'IM - Pro Version', 'daext-interlinks-manager'),
			esc_html__( 'Pro Version', 'daext-interlinks-manager'),
			'edit_posts',
			$this->shared->get( 'slug' ) . '-pro-version',
			array( $this, 'me_display_menu_pro_version' )
		);

		$this->screen_id_options = add_submenu_page(
			$this->shared->get( 'slug' ) . '-dashboard',
			esc_html__( 'IM - Options', 'daext-interlinks-manager'),
			esc_html__( 'Options', 'daext-interlinks-manager'),
			'manage_options',
			$this->shared->get( 'slug' ) . '-options',
			array( $this, 'me_display_menu_options' )
		);

	}

	/*
	 * includes the dashboard view
	 */
	public function me_display_menu_dashboard() {
		include_once( 'view/dashboard.php' );
	}

	/*
	 * includes the juice view
	 */
	public function me_display_menu_juice() {
		include_once( 'view/juice.php' );
	}

	/*
	 * includes the help view
	 */
	public function me_display_menu_help() {
		include_once( 'view/help.php' );
	}

	/*
     * includes the pro version view
     */
	public function me_display_menu_pro_version() {
		include_once( 'view/pro_version.php' );
	}

	/*
	 * includes the options view
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

	//meta box -----------------------------------------------------------------
	public function create_meta_box() {

		if ( current_user_can( 'edit_posts' ) ) {

			/*
			 * Load the "Interlinks Options" meta box only in the post types defined
			 * with the "Interlinks Options Post Types" option
			 */
			$interlinks_options_post_types_a = maybe_unserialize( get_option( $this->shared->get( 'slug' ) . '_interlinks_options_post_types' ) );
			if ( is_array( $interlinks_options_post_types_a ) ) {
				foreach ( $interlinks_options_post_types_a as $key => $post_type ) {
					add_meta_box( 'daextinma-meta-options', esc_html__( 'Interlinks Options', 'daext-interlinks-manager'),
						array( $this, 'create_options_meta_box_callback' ), $post_type, 'side', 'high' );
				}
			}

		}

		if ( current_user_can( 'edit_posts' ) ) {

			/*
			 * Load the "Interlinks Optimization" meta box only in the post types
			 * defined with the "Interlinks Optimization Post Types" option
			 */
			$interlinks_optimization_post_types_a = maybe_unserialize( get_option( $this->shared->get( 'slug' ) . '_interlinks_optimization_post_types' ) );
			if ( is_array( $interlinks_optimization_post_types_a ) ) {
				foreach ( $interlinks_optimization_post_types_a as $key => $post_type ) {
					add_meta_box( 'daextinma-meta-optimization',
						esc_html__( 'Interlinks Optimization', 'daext-interlinks-manager'),
						array( $this, 'create_optimization_meta_box_callback' ), $post_type, 'side', 'default' );
				}
			}

		}

	}

	//display the Interlinks Options meta box content
	public function create_options_meta_box_callback( $post ) {

		//retrieve the Interlinks Manager data values
		$seo_power = get_post_meta( $post->ID, '_daextinma_seo_power', true );
		if ( strlen( trim( $seo_power ) ) === 0 ) {
			$seo_power = intval( get_option( $this->shared->get( 'slug' ) . '_default_seo_power' ), 10 );
		}

		?>

        <label for="daextinma-seo-power"><?php esc_html_e( 'SEO Power', 'daext-interlinks-manager'); ?></label>
        <input type="text" name="daextinma_seo_power" id="daextinma-seo-power"
               value="<?php echo esc_attr( $seo_power ); ?>" class="regular-text" maxlength="7">

		<?php

		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'daextinma_nonce' );

	}

	//display the Interlinks Optimization meta box content
	public function create_optimization_meta_box_callback( $post ) {

		echo $this->shared->generate_interlinks_optimization_metabox_html( $post );

	}

	//Save the Interlinks Options meta data
	public function daextinma_save_meta_interlinks_options( $post_id ) {

		//security verifications -----------------------------------------------

		// verify if this is an auto save routine.
		// If it is our form has not been submitted, so we dont want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		/*
		 * verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times
		 */
		if ( ! isset( $_POST['daextinma_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['daextinma_nonce'] ),
				plugin_basename( __FILE__ ) ) ) {
			return;
		}

		//verify the capability
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		//end security verifications -------------------------------------------

		//save the "SEO Power" only if it's included in the allowed values
		if ( intval( $_POST['daextinma_seo_power'], 10 ) !== 0 and intval( $_POST['daextinma_seo_power'],
				10 ) <= 1000000 ) {
			update_post_meta( $post_id, '_daextinma_seo_power', intval( $_POST['daextinma_seo_power'], 10 ) );
		}

	}

}