<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       https://timersys.com/geotargeting/
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/admin
 * @author     Your Name <email@example.com>
 */
class GeoTarget_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $GeoTarget    The ID of this plugin.
	 */
	private $GeoTarget;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $GeoTarget       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $GeoTarget, $version ) {

		$this->GeoTarget = $GeoTarget;
		$this->version = $version;
		$this->load_dependencies();
	}

	/**
	 * Load the required dependencies for admin area.
	 *
	 *
	 * - GeoTarget_Settings. Settings page and functions
	 *
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/includes/class-geotarget-admin-settings.php';

	}

	/**
	 * Add menu for Settings page of the plugin
	 * @since  1.0.0
	 * @return  void
	 */
	public function add_settings_menu() {

		$settings = new GeoTarget_Settings( $this->GeoTarget, $this->version );

		add_menu_page('GeoTargeting', 'GeoTargeting', 'manage_options', 'geot-settings', array($settings, 'settings_page'), 'dashicons-share-alt' );
		add_submenu_page( 'geot-settings', 'Settings', 'Settings', 'manage_options', 'geot-settings',array($settings, 'settings_page') );
		add_submenu_page( 'geot-settings', 'Ip test', 'Ip test', 'manage_options', 'geot-ip-test',array($settings, 'ip_test_page') );
	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		global $pagenow;

		if( 'post.php' == $pagenow ) {
   			wp_enqueue_style('wp-jquery-ui-dialog');
   		}
		wp_enqueue_style( 'geot-chosen', plugin_dir_url( __FILE__ ) . 'css/chosen.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->GeoTarget, plugin_dir_url( __FILE__ ) . 'css/geotarget.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $pagenow;

		if( 'post.php' == $pagenow ) {
			wp_enqueue_script('jquery-ui-dialog');
   		}

		wp_enqueue_script( 'geot-chosen', plugin_dir_url( __FILE__ ) . 'js/chosen.jquery.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->GeoTarget, plugin_dir_url( __FILE__ ) . 'js/geotargeting-admin.js', array( 'jquery','geot-chosen' ), $this->version, false );

	}

	/**
	 * Register the metaboxes on all posts types
	 */
	public function add_meta_boxes()
	{

		$post_types = apply_filters( 'geot/get_post_types', array(), array('attachment') );

		foreach ($post_types as $cpt) {

			add_meta_box(
				'geot-settings',
				__( 'GeoTargeting Options', 'geot' ),
				array( $this, 'geot_options_view' ),
				$cpt,
				'normal',
				'core'
			);

		}

	}

	/**
	 * Display the view for Geot metabox options
	 * @return mixed
	 */
	public function geot_options_view( $post, $metabox )
	{
		$opts 		= apply_filters('geot/metaboxes/get_cpt_options', GeoTarget_Filters::get_cpt_options( $post->ID ), $post->ID );
		$countries 	= apply_filters('geot/get_countries', array());

		include 'partials/metabox-options.php';
	}


	/**
	* Saves popup options and rules
	*/
	public function save_meta_options( $post_id ) {

		// Verify that the nonce is set and valid.
		if ( !isset( $_POST['geot_options_nonce'] ) || ! wp_verify_nonce( $_POST['geot_options_nonce'], 'geot_options' ) ) {
			return $post_id;
		}

		// can user edit this post?
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		$opts = $_POST['geot'];
		$country_codes = '';
		unset( $_POST['geot'] );


		// save box settings
		update_post_meta( $post_id, 'geot_options', apply_filters( 'geot/metaboxes/sanitized_options', $opts ) );

		//convert countries and regions into commas list of country codes
		if( isset( $opts['country_code'] ) && is_array( $opts['country_code'] ) ){

			$country_codes = implode(',', $opts['country_code']);
		}
		$regions 	= apply_filters('geot/get_regions', array());

		if( is_array($opts['region'] ) ) {


			foreach ($opts['region'] as $region) {
				foreach ($regions as $r) {
					if( $region == $r['name'] ) {

						$country_codes =  $country_codes . ',' . implode(',', $r['countries']);
					}
				}
			}
		}
		update_post_meta( $post_id, 'geot_countries', trim($country_codes, ',') );

	}

}
