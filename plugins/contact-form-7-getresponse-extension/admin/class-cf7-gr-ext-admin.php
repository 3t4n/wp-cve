<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wensolutions.com/
 * @since      1.0.0
 *
 * @package    Cf7_Gr_Ext
 * @subpackage Cf7_Gr_Ext/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cf7_Gr_Ext
 * @subpackage Cf7_Gr_Ext/admin
 * @author     WEN Solutions <info@wensolutions.com>
 */
class Cf7_Gr_Ext_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ). '/css/cf7-gr-ext-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name , plugin_dir_url( __FILE__ ) . '/js/cf7-gr-ext-admin.js', array( 'jquery' ) );
		// Localize the script with new data
		$translation_array = array(
			'base_url' => home_url( '/' ),
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'messages' => array(
				'remove_alert' => __( 'Are you sure to delete?', 'cf7-gr-ext' ),
				'select_campaign' => __( 'Select Campaign', 'cf7-gr-ext' ),
				'select_custom_fields' => __( 'Select custom field', 'cf7-gr-ext' )
			)
		);
		wp_localize_script( $this->plugin_name, 'cf7_options', $translation_array );

	}

	function cf7_add_tab( $panels ) {

    	$new_page = array(
    		'cf7-gr-ext' => array(
    			'title' => __( 'GetResponse Settings', 'cf7-gr-ext' ),
    			'callback' => array( $this,'cf7_tab_callback' )
    		)
    	);

    	$panels = array_merge( $panels, $new_page );

    	return $panels;

    }

    function cf7_tab_callback($args) {
    	$options = get_option( 'cf7_gs_ext_basics_options' );
    	if ( empty( $options ) || ! isset( $options['gs_con'] ) ) {
    		echo sprintf( '%s. <a href="%s">%s</a>.', __( 'Setup Process is not completed', 'cf7-gr-ext' ),  esc_url( menu_page_url( 'wpcf7-integration', false ) ), __( 'Click here to complete', 'cf7-gr-ext' ) );
    		return;
    	}

		$cf7_gr = get_post_meta( $args->id(), 'cf7_gs_settings', true );
		include CF7_GR_EXT_BASE . 'admin/partials/cf7-gr-ext-admin-display.php';

    }

    function save_options($args) {
		$cf7_gr = $_POST['cf7-gs'];
		update_post_meta( $args->id(), 'cf7_gs_settings', $cf7_gr );
    }

    function update_campaigns(){

		$options = get_option( 'cf7_gs_ext_basics_options' );

		$getresponse = new GetResponse( $options['gs_key'] );
		$account = $getresponse->accounts();

		if( isset( $account->accountId ) && '' != $account->accountId ){

			$campaigns 	 = $getresponse->getCampaigns();

			if( !empty( $campaigns ) ){
				$new_options = $options;
				$new_options['gs_camp'] = $campaigns;
				$new_options['gs_con'] = 1;

				update_option( 'cf7_gs_ext_basics_options', $new_options );

				if( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ){
					echo json_encode( $new_options );
					exit;
				}
			} # END if( !empty( $campaigns ) )
			else{
				$new_options = $options;
				unset( $new_options['gs_camp'] );
				update_option( 'cf7_gs_ext_basics_options', $new_options );
			}

		}
		else{
			$new_options = $options;
			$new_options['gs_con'] = false;
			unset( $new_options['gs_camp'] );
			update_option( 'cf7_gs_ext_basics_options', $new_options );

			// add_action( 'admin_notices', array( $this, 'api_key_invalid_message' ) );

		}
	}

	function gr_update_custom_field(){
		$options = get_option( 'cf7_gs_ext_basics_options' );

		$getresponse = new GetResponse( $options['gs_key'] );
		$account = $getresponse->accounts();

		if( isset( $account->accountId ) && '' != $account->accountId ){

			$custom_fields = $getresponse->getCustomFields();

			if( !empty( $custom_fields ) ){
				$new_options = $options;
				$new_options['gs_custom_fields'] = $custom_fields;
				$new_options['gs_con'] = 1;

				update_option( 'cf7_gs_ext_basics_options', $new_options );

				if( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ){
					echo json_encode( $new_options );
					exit;
				}
			} # END if( !empty( $campaigns ) )
			else{
				$new_options = $options;
				unset( $new_options['gs_camp'] );
				update_option( 'cf7_gs_ext_basics_options', $new_options );
			}

		}
		else{
			$new_options = $options;
			$new_options['gs_con'] = false;
			unset( $new_options['gs_camp'] );
			update_option( 'cf7_gs_ext_basics_options', $new_options );

			// add_action( 'admin_notices', array( $this, 'api_key_invalid_message' ) );

		}
	}

	/**
	 * Check requirements
	 *
	 * @since 1.0.0
	 */
	public function check_requirements() {

		$plugin = plugin_basename( CF7_GR_EXT_BASE_FILE );
		$plugin_data = get_plugin_data( CF7_GR_EXT_BASE_FILE, false );

		if ( ! class_exists( 'WPCF7' ) ) {
			if ( is_plugin_active( $plugin ) ) {
				deactivate_plugins( $plugin );
				
				wp_die( sprintf( __( '%s requires the %s plugin to work. Please activate it first.', 'cf7-gr-ext' ) . '<br><br>' . __( 'Back to the WordPress %s Plugins page %s.', 'cf7-gr-ext' ), '<strong>' . $plugin_data['Name'] . '</strong>', '<strong>Contact Form 7</strong>', '<a href="' . get_admin_url( null, 'plugins.php' ) . '">', '</a>'  ) );
			}
		}

	}
}
