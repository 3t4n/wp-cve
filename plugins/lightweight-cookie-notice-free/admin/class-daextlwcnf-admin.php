<?php

/*
 * this class should be used to work with the administrative side of wordpress
 */

class Daextlwcnf_Admin {

	protected static $instance = null;
	private $shared = null;

    private $screen_id_help = null;
    private $screen_id_pro_version = null;
	private $screen_id_options = null;

	public $menu_options = null;

	private function __construct() {

		//assign an instance of the plugin info
		$this->shared = Daextlwcnf_Shared::get_instance();

		//Load admin stylesheets and JavaScript
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		//Add the admin menu
		add_action( 'admin_menu', array( $this, 'me_add_admin_menu' ) );

		//Load the options API registrations and callbacks
		add_action( 'admin_init', array( $this, 'op_register_options' ) );

		//this hook is triggered during the creation of a new blog
		add_action( 'wpmu_new_blog', array( $this, 'new_blog_create_options_and_tables' ), 10, 6 );

		//this hook is triggered during the deletion of a blog
		add_action( 'delete_blog', array( $this, 'delete_blog_delete_options' ), 10, 1 );

		//Require and instantiate the class used to register the menu options
		require_once( $this->shared->get( 'dir' ) . 'admin/inc/class-daextlwcnf-menu-options.php' );
		$this->menu_options = new Daextlwcnf_Menu_Options( $this->shared );

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

	/*
	 * Enqueue admin specific styles.
	 */
	public function enqueue_admin_styles() {

		$screen = get_current_screen();

        //Menu Help
        if ($screen->id == $this->screen_id_help) {

            wp_enqueue_style($this->shared->get('slug') . '-menu-help',
                $this->shared->get('url') . 'admin/assets/css/menu-help.css', array(), $this->shared->get('ver'));

        }

        //Menu Pro Version
        if ($screen->id == $this->screen_id_pro_version) {

            wp_enqueue_style($this->shared->get('slug') . '-menu-pro-version',
                $this->shared->get('url') . 'admin/assets/css/menu-pro-version.css', array(), $this->shared->get('ver'));

        }

		//Menu Options
		if ( $screen->id == $this->screen_id_options ) {

			//Framework Options
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-framework-options',
				$this->shared->get( 'url' ) . 'admin/assets/css/framework/options.css', array(),
				$this->shared->get( 'ver' ) );

			//jQuery UI Tooltip
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip',
				$this->shared->get( 'url' ) . 'admin/assets/css/jquery-ui-tooltip.css', array(),
				$this->shared->get( 'ver' ) );

			//Select2
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-select2',
				$this->shared->get( 'url' ) . 'admin/assets/inc/select2/dist/css/select2.min.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-select2-custom',
				$this->shared->get( 'url' ) . 'admin/assets/css/select2-custom.css', array(),
				$this->shared->get( 'ver' ) );

			//WP Color Picker
			wp_enqueue_style( 'wp-color-picker' );

		}

	}

	/*
	 * Enqueue admin-specific JavaScript.
	 */
	public function enqueue_admin_scripts() {

		$wp_localize_script_data = array(
			'deleteText' => esc_html__( 'Delete', 'daextlwcnf' ),
			'cancelText' => esc_html__( 'Cancel', 'daextlwcnf' ),
		);

		$screen = get_current_screen();

		//Menu Options
		if ( $screen->id == $this->screen_id_options ) {

			//Select2
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-select2',
				$this->shared->get( 'url' ) . 'admin/assets/inc/select2/dist/js/select2.min.js', 'jquery',
				$this->shared->get( 'ver' ) );

			//jQuery UI Tooltip
			wp_enqueue_script( 'jquery-ui-tooltip' );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip-init',
				$this->shared->get( 'url' ) . 'admin/assets/js/jquery-ui-tooltip-init.js', 'jquery',
				$this->shared->get( 'ver' ) );

			//Options Menu
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-menu-options',
				$this->shared->get( 'url' ) . 'admin/assets/js/menu-options.js',
				array( 'jquery', 'daextlwcnf-select2' ),
				$this->shared->get( 'ver' ) );
			wp_localize_script( $this->shared->get( 'slug' ) . '-menu-options', 'objectL10n',
				$wp_localize_script_data );

			//Color Picker Initialization
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-wp-color-picker-init',
				$this->shared->get( 'url' ) . 'admin/assets/js/wp-color-picker-init.js',
				array( 'jquery', 'wp-color-picker' ), false, true );

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
                    $this->schedule_cron_event();

				}

				//switch to the current blog
				switch_to_blog( $current_blog );

			} else {

				/*
				 * if this is not a "Network Activation" create options and
				 * tables only for the current blog
				 */
				$this->ac_initialize_options();
                $this->schedule_cron_event();

			}

		} else {

			/*
			 * if this is not a multisite installation create options and
			 * tables only for the current blog
			 */
			$this->ac_initialize_options();
            $this->schedule_cron_event();

		}

	}

	public function schedule_cron_event() {
		if ( ! wp_next_scheduled( 'daextlwcnf_cron_hook' ) ) {
			wp_schedule_event( time(), 'weekly', 'daextlwcnf_cron_hook' );
		}
	}

	/*
	 * plugin deactivation
	 */
	public function dc_deactivate( $networkwide ) {
		wp_clear_scheduled_hook( 'daextlwcnf_cron_hook' );
	}

	//create the options and tables for the newly created blog
	public function new_blog_create_options_and_tables( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {

		global $wpdb;

		/*
		 * if the plugin is "Network Active" create the options and tables for
		 * this new blog
		 */
		if ( is_plugin_active_for_network( 'lightweight-cookie-notice/init.php' ) ) {

			//get the id of the current blog
			$current_blog = $wpdb->blogid;

			//switch to the blog that is being activated
			switch_to_blog( $blog_id );

			//create options for the new blog
			$this->ac_initialize_options();
			$this->schedule_cron_event();

			//switch to the current blog
			switch_to_blog( $current_blog );

		}

	}

	//delete options for the deleted blog
	public function delete_blog_delete_options( $blog_id ) {

		global $wpdb;

		//get the id of the current blog
		$current_blog = $wpdb->blogid;

		//switch to the blog that is being activated
		switch_to_blog( $blog_id );

		//delete options for the new blog
		$this->un_delete_options();

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
	 * Plugin delete.
	 */
	static public function un_delete() {

		/*
		 * Delete options for all the sites in the network.
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

				//delete options for the iterated blog
				Daextlwcnf_Admin::un_delete_options();

			}

			//switch to the current blog
			switch_to_blog( $current_blog );

		} else {

			/*
			 * If this is not a multisite installation delete options only for the current blog.
			 */
			Daextlwcnf_Admin::un_delete_options();

		}

	}

	/*
	 * Delete plugin options.
	 */
	static public function un_delete_options() {

		//assign an instance of Daextlwcnf_Shared
		$shared = Daextlwcnf_Shared::get_instance();

		foreach ( $shared->get( 'options' ) as $key => $value ) {
			delete_option( $key );
		}

	}

	/*
	 * Register the admin menu.
	 */
	public function me_add_admin_menu() {


		//The icon in Base64 format
		$icon_base64 = 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyNS4xLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAyMCAyMCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjAgMjA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxwYXRoIGQ9Ik05LjksMTAuMmMwLDAsMS41LTAuMSwzLjItMC4yQzE2LjYsNi43LDIwLDIuNiwyMCwyLjZjLTQuNC0wLjktNi43LTAuNS02LjctMC41Yy0xLDAuMi0xLjYsMC4zLTIuMSwwLjUNCgljLTAuOSwxLjQtMS4xLDIuMy0xLjEsMi4zcy0wLjEtMC44LTAuMS0xLjhjLTIuOCwxLjItNC45LDMtNC45LDNjMCwwLDAsMC0wLjEsMC4xQzQuNiw3LjcsNC41LDguOSw0LjUsOC45UzQuMSw4LjQsMy45LDcuMw0KCWMtMi43LDMuMS0yLjEsNi40LTIuMSw2LjRjNi4zLTcuNywxMS44LTguNywxMS44LTguN0M0LjMsMTAsMCwxNy45LDAsMTcuOXMxLjEsMC4yLDEuNS0wLjJjMCwwLDAuMy0xLjEsMS44LTMuMQ0KCWMwLDAsNC4xLDAuNyw4LjQtMy4zYzAsMCwwLjEtMC4xLDAuMS0wLjFDMTAuNywxMC43LDkuOSwxMC4yLDkuOSwxMC4yeiIvPg0KPC9zdmc+DQo=';

		//The icon in the data URI scheme
		$icon_data_uri = 'data:image/svg+xml;base64,' . $icon_base64;

		add_menu_page(
			esc_html__( 'LCN', 'daextlwcnf' ),
			esc_html__( 'Cookie Notice', 'daextlwcnf' ),
			'manage_options',
			$this->shared->get( 'slug' ) . '-help',
			array( $this, 'me_display_menu_help' ),
			$icon_data_uri
		);

        $this->screen_id_help = add_submenu_page(
            $this->shared->get( 'slug' ) . '-help',
            esc_html__( 'LCN - Help', 'daextlwcnf' ),
            esc_html__( 'Help', 'daextlwcnf' ),
            'manage_options',
            $this->shared->get( 'slug' ) . '-help',
            array( $this, 'me_display_menu_help' )
        );

        $this->screen_id_pro_version = add_submenu_page(
            $this->shared->get( 'slug' ) . '-help',
            esc_html__( 'LCN - Pro Version', 'daextlwcnf' ),
            esc_html__( 'Pro Version', 'daextlwcnf' ),
            'manage_options',
            $this->shared->get( 'slug' ) . '-pro-version',
            array( $this, 'me_display_menu_pro_version' )
        );

		$this->screen_id_options = add_submenu_page(
			$this->shared->get( 'slug' ) . '-help',
			esc_html__( 'LCN - Options', 'daextlwcnf' ),
			esc_html__( 'Options', 'daextlwcnf' ),
			'manage_options',
			$this->shared->get( 'slug' ) . '-options',
			array( $this, 'me_display_menu_options' )
		);

	}

    /*
     * includes the options view
     */
    public function me_display_menu_help() {
        include_once( 'view/help.php' );
    }

    /*
     * includes the options view
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

}