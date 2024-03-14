<?php 

class WPTD_Elementor_Video_Popup_Settings {
	
	private static $_instance = null;
	
	public function __construct() {

		//WPTD video popup admin menu
		add_action( 'admin_menu', array( $this, 'wptd_admin_menu' ) );
		
		//WPTD video popup admin scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'wptd_admin_scripts' ) );
		
		//Plugin Links
		add_filter( 'plugin_action_links', array( $this, 'wptd_elementor_video_popup_plugin_action_links' ), 90, 2 );
				
	}
	
	public function wptd_admin_menu() {
		add_menu_page( 
			esc_html__( 'WPTD Video Popup', 'wptd-video-popup' ),
			esc_html__( 'Video Popup', 'wptd-video-popup' ),
			'manage_options',
			'wptd-video-popup', 
			array( $this, 'wptd_elementor_video_popup_page' ),
			'dashicons-admin-collapse',
			6
		);
	}
	
	public function wptd_admin_scripts(){
		wp_enqueue_style( 'wptd-video-popup-admin', WPTD_EVP_URL . 'admin/assets/css/style.css', array(), '1.0.0', 'all' );
	}
	
	public function wptd_elementor_video_popup_page() {
		require_once ( WPTD_EVP_DIR . 'admin/admin-page.php' );
	}
	
	public function wptd_elementor_video_popup_plugin_action_links( $plugin_actions, $plugin_file ){		
		$new_actions = array(); 
		if( 'wptd-video-popup/wptd-video-popup.php' === $plugin_file ) {
			$new_actions = array( sprintf( __( '<a href="%s">Settings</a>', 'wptd-video-popup' ), esc_url( admin_url( 'admin.php?page=wptd-video-popup' ) ) ) );
		}
		return array_merge( $new_actions, $plugin_actions );
	}
	
	/**
	 * Creates and returns an instance of the class
	 * @since 1.0.0
	 * @access public
	 * return object
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

}
WPTD_Elementor_Video_Popup_Settings::get_instance();