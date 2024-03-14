<?php
/**
 * Custom Post Template.
 *
 * PHP version 7
 *
 * @package  Hellowoofy_Com
 */

/**
 * Custom Post Template.
 *
 * Template Class
 *
 * @package  Hellowoofy_Com
 */
class Hellowoofy_Com {

	/**
	 * HelloWoofy Main Class contructor.
	 *
	 * @since    1.0.3
	 */
	public function __construct() {

		/* enque public styles and scripts */
		add_action( 'wp_enqueue_scripts', array( $this, 'mws_public_enque_scripts' ) );

		/* enque admin styles and scripts */
		add_action( 'admin_enqueue_scripts', array( $this, 'mws_admin_enque_scripts' ) );

		/* Create custom table on plugin activation */
		register_activation_hook( __FILE__, array( $this, 'mws_create_tbl_on_activation' ) );

		/* Load all files */
		$this->mws_load_admin_files();

		/* Load public files */
		$this->mws_load_public_files();

		/* custom endpoint */
		add_action( 'rest_api_init', array( $this, 'mws_api_callback' ) );

	}

	/**
	 * Public enque scripts.
	 *
	 * @since    1.0.3
	 */
	public function mws_public_enque_scripts() {
		wp_register_style( 'mws_pubilc_card_css', plugins_url( 'assets/css/cards.css', __FILE__ ), array(), MWS_PLUGIN_VERSION, 'all' );
		wp_register_script( 'mws_publc_main_js', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), MWS_PLUGIN_VERSION, 'all' );
	}


	/**
	 * Admin enque scripts.
	 *
	 * @since    1.0.3
	 */
	public function mws_admin_enque_scripts() {
		// Call predefined enqueue media method.
		wp_enqueue_media();
		// select2 css.
		wp_register_style( 'mws_select2_css', plugins_url( 'assets/css/select2.min.css', __FILE__ ), array(), MWS_PLUGIN_VERSION, 'all' );
		// context bootstrap.
		wp_register_style( 'mws_context_bootstrap', plugins_url( 'assets/css/context.bootstrap.css', __FILE__ ), array(), MWS_PLUGIN_VERSION, 'all' );
		// context css.
		wp_register_style( 'mws_context_css', plugins_url( 'assets/css/context.standalone.css', __FILE__ ), array(), MWS_PLUGIN_VERSION, 'all' );
		// cards css.
		wp_register_style( 'mws_admin_card_css', plugins_url( 'assets/css/admin-card.css', __FILE__ ), array(), MWS_PLUGIN_VERSION, 'all' );
		// bootstrap min.css.
		wp_register_style( 'mws_admin_boostrap_min_css', plugins_url( 'assets/css/bootstrap.min.css', __FILE__ ), array(), MWS_PLUGIN_VERSION, 'all' );
		// jquery.
		wp_register_script( 'mws_jquery', plugins_url( 'assets/js/jquery.min.js', __FILE__ ), array( 'jquery' ), MWS_PLUGIN_VERSION, true );
		// select 2 js.
		wp_register_script( 'mws_select2_js', plugins_url( 'assets/js/select2.min.js', __FILE__ ), array( 'jquery' ), MWS_PLUGIN_VERSION, true );
		// bundle.min.js.
		wp_register_script( 'mws_admin_bundle_min_js', plugins_url( 'assets/js/bootstrap.bundle.min.js', __FILE__ ), array( 'jquery' ), MWS_PLUGIN_VERSION, true );

		// initialize js .
		wp_register_script( 'mws_initiailze_js', plugins_url( 'assets/js/initialize.js', __FILE__ ), array( 'jquery' ), MWS_PLUGIN_VERSION, true );
		wp_enqueue_script( 'mws_initiailze_js' );
		// Call admin ajax.
		$localized_vars = array(
			'ajax_url'       => admin_url( 'admin-ajax.php' ),
			'loader_gif_url' => plugin_dir_url( __FILE__ ) . 'assets/img/003.gif',
		);
		wp_localize_script( 'mws_initiailze_js', 'mws_admin_ajax_url', $localized_vars );
		// context.js.
		wp_register_script( 'mws_context_js', plugins_url( 'assets/js/context.js', __FILE__ ), array( 'jquery' ), MWS_PLUGIN_VERSION, true );

		/* custom js */
		wp_register_script( 'mws_custom_admin_js', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), MWS_PLUGIN_VERSION, true );

		/* admin stories js */
		wp_register_script( 'mws_custom_admin_stories_js', plugins_url( 'assets/js/admin-stories.js', __FILE__ ), array( 'jquery' ), MWS_PLUGIN_VERSION, true );
        
        /* clipboard js */
		wp_register_script( 'mws_clipboard_js', plugins_url( 'assets/js/clipboard.min.js', __FILE__ ), array( 'jquery' ), MWS_PLUGIN_VERSION, true );

	}

	/**
	 * Create custom table to store request data in case of failure in custom endpoints.
	 *
	 * @since    1.0.3
	 */
	public function mws_create_tbl_on_activation() {

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$table_name = $wpdb->prefix . 'max_fail_requests';
		$sql = "CREATE TABLE $table_name (
        id INTEGER NOT NULL AUTO_INCREMENT,
        webhook VARCHAR(255) NOT NULL,
        request_data text,
        timestam VARCHAR(255),
         PRIMARY KEY (id)
         ) $charset_collate;";
		dbDelta( $sql );

	}

	/**
	 * Inlude the file that containse all custom endpoint.
	 *
	 * @since    1.0.3
	 */
	public function mws_api_callback() {

		require_once plugin_dir_path( __FILE__ ) . 'endpoints/class-mws-custom-endpoints.php';

	}

	/**
	 * Load admin files.
	 *
	 * @since    1.0.3
	 */
	public function mws_load_admin_files() {
		/* Register custom post types */
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-register-post-type.php';
		/* Admin menu for Woofly Api */
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-admin-menu.php';
		/* Custom template to show Google web Story */
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-custom-post-template.php';
		/* file with ajax callback */
		require_once plugin_dir_path( __FILE__ ) . 'admin/helper.php';
	}

	/**
	 * Load public files.
	 *
	 * @since    1.0.3
	 */
	public function mws_load_public_files() {

		require_once plugin_dir_path( __FILE__ ) . 'public/class-display-slider-on-front-end.php';

	}
}
