<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       codeless.co
 * @since      1.0.0
 *
 * @package    Slider_Templates
 * @subpackage Slider_Templates/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Slider_Templates
 * @subpackage Slider_Templates/admin
 * @author     Codeless <info@codeless.co>
 */
class Slider_Templates_Admin {

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


	private $user_data;

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

		if( get_option( 'slider_templates_logged_in', false ) !== FALSE )
			update_option( 'slider_templates_is_premium', $this->check_is_premium( get_option( 'slider_templates_logged_in', false ) ) );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Slider_Templates_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Slider_Templates_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/slider-templates-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Slider_Templates_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Slider_Templates_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/slider-templates-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'st', array(
			'apilink' => 'https://slider-templates.com/wp-json/wp/v2/',
			'customapilink' => 'https://slider-templates.com/wp-json/st/v1/',
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'pluginurl' => admin_url( 'admin.php?page=slider-templates' ),
			'login_nonce' => wp_create_nonce('login_nonce'),
			'install_nonce' => wp_create_nonce('install_nonce'),
			'logged' => get_option( 'slider_templates_logged_in', false ),
			'themeslug' => get_option('stylesheet'),
			'is_premium' => (int) $this->check_is_premium( get_option( 'slider_templates_logged_in', false ) )
		) );
	}

	public function check_premium_theme_key( $key ){
		$option = get_option( $key );
		if( is_array( $option ) && isset( $option['purchase_code'] ) ){
			return $option['purchase_code'];
		}
		return false;
	}

	public function create_menu() {
		add_menu_page(
			__( 'Slider Templates', 'slider-templates' ),
			__( 'Slider Templates', 'slider-templates' ),
			'manage_options',
			'slider-templates',
			array( $this, 'menu_page_content' ),
			'dashicons-schedule',
			3
		);
	}

	public function menu_page_content() {
		$logged_user = get_option( 'slider_templates_logged_in', false );
		
		if( $logged_user ){
			$user_data = array(
				'id' => $logged_user,
				'email' => get_option( 'slider_templates_email' ),
				'is_premium' => get_option( 'slider_templates_is_premium', false)
			);

			$this->user_data = $user_data;
		}
		$connected = get_option( 'slider_templates_connection_agree', false );

		if( $connected )
			$templates = $this->api_get_templates();
		$message = $this->get_message();
		
		$premium_theme_key = apply_filters( 'slider_templates_premium_theme_key', 'specular_purchase_info' );
		$premium_theme_actived_key = $this->check_premium_theme_key( $premium_theme_key );

		$theme_included = apply_filters( 'slider_templates_theme_included', array() );

		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/slider-templates-admin-display.php';
	}

	public function login_post(){
		$nonce = $_POST['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'login_nonce' ) )
			die();
		
		$email = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : false;
		$id = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : false;
	
		if( $id && $email ){
			update_option( 'slider_templates_email', $email );
			update_option( 'slider_templates_logged_in', $id );
			update_option( 'slider_templates_connection_agree', true );
			update_option( 'slider_templates_is_premium', $this->check_is_premium($id) );
			wp_send_json_success();
		}
	}

	

	public function api_get_template_url($id){
		$email = get_option( 'slider_templates_email' );
		$password = get_option( 'slider_templates_password' );

		if( $email && $password ){
			$wp_request_headers = array(
				'Authorization' => 'Basic ' . base64_encode( $email.':'.$password )
			  );
			  $wp_request_url = 'https://slider-templates.com/wp-json/wp/v2/dlm_download_version?search=%23'.(int) $id;
			  $wp_get_response = wp_remote_request(
				$wp_request_url,
				array(
					'method'    => 'GET',
					'headers'   => $wp_request_headers
				)
			  );
			$data = json_decode( wp_remote_retrieve_body($wp_get_response), true );
			$url = json_decode($data[0]['_files']);
			return $url[0];
		}
	}


	public function api_get_templates(){

		if( get_option('slider_templates_all_templates',false) )
			return get_option('slider_templates_all_templates');

		$response = wp_remote_get( 'https://slider-templates.com/wp-json/wp/v2/portfolio?_embed&per_page=100' );
		$pages = (int) wp_remote_retrieve_header( $response, 'x-wp-totalpages' );	
		$data = json_decode( wp_remote_retrieve_body($response) );
		if( $pages > 1 ){
			for( $i = 2; $i <= $pages; $i++ ){
				$response = wp_remote_get( 'https://slider-templates.com/wp-json/wp/v2/portfolio?_embed&per_page=100&page='.$i );
				$data_new = json_decode( wp_remote_retrieve_body($response) );
				$data = array_merge( $data_new, $data );
			}
		}
		if( get_option('slider_templates_all_templates',false) )
			update_option('slider_templates_all_templates', $data);
		else
			add_option( 'slider_templates_all_templates', $data );

		return $data;
	}

	public function check_is_premium( $id ){
	
		$response = wp_remote_get( 'https://slider-templates.com/wp-content/plugins/indeed-membership-pro/apigate.php?ihch=0g2gmyQKwnWbhjCRyRXGC6M3garvs&action=verify_user_level&uid='.$id.'&lid=3' );
		$data = json_decode( wp_remote_retrieve_body($response) );
		if( $data !== false && is_object($data) )
			return $data->response;
		return false;
	}

	public function install_template(){
		$nonce = $_POST['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'install_nonce' ) )
			die();

		$url = urldecode( sanitize_text_field( $_POST['file'] ) );
		$download_id = sanitize_text_field( $_POST['download_id'] );

		$premium_theme_key = apply_filters( 'slider_templates_premium_theme_key', 'specular_purchase_info' );
		$premium_theme_actived_key = $this->check_premium_theme_key( $premium_theme_key );

		if( ! $this->can_download_limit( $download_id ) && !$premium_theme_actived_key )
			wp_send_json_error( "Limit-END" );

		$download = download_url($url);
		if(class_exists('RevSlider') && $download){	
			$slider = new RevSlider();
			$slider->importSliderFromPost(true, true, $download);  
			$this->register_download( $download_id );
			wp_send_json_success();
		}
		return false;
	}

	public function register_download($download_id){
		$data = get_option( 'slider_templates_download_history', array() );
		if( !in_array( $download_id,$data ) )
			$data[] = $download_id;

		update_option( 'slider_templates_download_history', $data );
	}

	public function can_download_limit( $download_id ){

		$theme_included = apply_filters( 'slider_templates_theme_included', array() );
		$history = get_option( 'slider_templates_download_history', array() );

		if( in_array( $download_id, $history ) && count($history) == 1 )
			return true;
		else{
			if( in_array( $download_id, $theme_included ) || empty( $history ) )
				return true;
		}

		if( get_option( 'slider_templates_is_premium', false ) )
			return true;

		return false;
	}


	public function manage_get_requests(){
		$st_action = isset( $_GET['st_action'] ) && !empty( $_GET['st_action'] ) ? sanitize_text_field( $_GET['st_action'] ) : false;
		if( $st_action == 'install' ){
			$st_template = isset( $_GET['st_template'] ) && !empty( $_GET['st_template'] ) ? sanitize_text_field( $_GET['st_template'] ) : false;
			if( $st_template )
				$this->install_template( (int) $st_template );

		}

		if( $st_action == 'connect' )
			$this->connect();

		if( $st_action == 'disconnect' )
			$this->disconnect();

		if( $st_action == 'logout' )
			$this->logout();
	}

	public function connect(){
		update_option( 'slider_templates_connection_agree', true );
		wp_redirect( admin_url( 'admin.php?page=slider-templates' ) );
	}

	public function disconnect(){
		update_option( 'slider_templates_connection_agree', false );
		$this->logout();
	}

	public function logout(){
		update_option( 'slider_templates_email', false );
		update_option( 'slider_templates_password', false );
		update_option( 'slider_templates_logged_in', false );
		update_option( 'slider_templates_is_premium', false );
		update_option( 'slider_templates_connection_agree', false );
		
		unset( $_COOKIE['st-logged-in'] );
    	setcookie( 'st-logged-in', '', time() - ( 15 * 60 ) );
	}

	public function get_message(){
		$message = isset( $_GET['message'] ) ? sanitize_text_field( $_GET['message'] ) : false;
		return $message;
	}
}