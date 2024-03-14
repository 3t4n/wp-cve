<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://http://phoeniixx.com/
 * @since      1.0.0
 *
 * @package    Phoen_Pincode_Zipcode
 * @subpackage Phoen_Pincode_Zipcode/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Phoen_Pincode_Zipcode
 * @subpackage Phoen_Pincode_Zipcode/admin
 * @author     PHOENIIXX TEAM <raghavendra@phoeniixx.com>
 */
class Phoen_Pincode_Zipcode_Admin {

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
	private $pincode;
	private $setting;
	use Stored;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name 	= $plugin_name;
		$this->version 		= $version;
		$this->pincode 		= new Pincode;
		$this->setting 		= new Setting;

		add_action('wp_ajax_phoenixx_pincodeonshiping_get_state', array($this,'phoenixx_pincodeonshiping_get_state'));
	    add_action('wp_ajax_nopriv_phoenixx_pincodeonshiping_get_state',array($this,'phoenixx_pincodeonshiping_get_state'));
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
		 * defined in Phoen_Pincode_Zipcode_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Phoen_Pincode_Zipcode_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/phoen-pincode-zipcode-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wp-color-picker' );
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
		 * defined in Phoen_Pincode_Zipcode_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Phoen_Pincode_Zipcode_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/phoen-pincode-zipcode-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('wp-color-picker');
	}

	public function phoeniixx_pincode_zipcode_menu() {

		add_menu_page(__('Zip codes','disp-test'), __('Zip Code List','disp-test'), 'manage_options' , 'phoeniixx-zipcode-pincode' , array($this,'phoen_pincode_setting_function_list') , PHOEN_PINCODE_ZIPCODE_URL."admin/images/page_white_zip.png" , '6');

		add_submenu_page('phoeniixx-zipcode-pincode', __('Pincode','displ-test'), __('Pincode','displ-test'), 'manage_options', 'phoeniixx-add-pincode', array($this,'phoeniixx_pincode_zipcode_store'));

		add_submenu_page('phoeniixx-zipcode-pincode', __('Setting','displ-test'), __('Settings','displ-test'), 'manage_options', 'phoeniixx-pincode-settings', array($this,'phoeniixx_pincode_zipcode_setting'));
		
	}

## ------------------------------------- MENU FUNCTION ----------------------------------------- ##
	public function phoen_pincode_setting_function_list(){
		$ID = !empty($_GET['id']) && is_numeric($_GET['id']) ? sanitize_text_field($_GET['id']) : 0;
		
		if(isset($_GET['action']) && $_GET['action'] === 'edit' && $ID !== 0 ) {
			$this->phoeniixx_pincode_zipcode_update_pincode($ID);
		
		}elseif (isset($_GET['action']) && $_GET['action'] === 'delete' && $ID !== 0 ) {
			$this->phoeniixx_pincode_zipcode_delete_pincode($ID);

		}else{
			$list = PHOEN_PINCODE_ZIPCODE_PATH.'admin/pages/list.php';
			file_exists($list) ? require_once($list) : 'FILE NOT FOUND';
		}
	}

	public function phoeniixx_pincode_zipcode_store(){
		$this->pincode_stored();
	}

	public function phoeniixx_pincode_zipcode_setting(){
		$this->setting_stored();
	}

## ------------------------------------------------------------------------------ ##
	
	private function phoeniixx_pincode_zipcode_update_pincode($ID){
		$this->pincode_stored($ID);
	}

	private function phoeniixx_pincode_zipcode_delete_pincode($ID){
		$this->pincode::delete($ID);
		wp_redirect(home_url('/wp-admin/admin.php?page=phoeniixx-zipcode-pincode'));
	}

	private function phoeniixx_pincode_zipcode_print_message($status,$message){
		$file = PHOEN_PINCODE_ZIPCODE_PATH.'admin/pages/message.php';
		file_exists($file) ? require_once($file) : 'FILE NOT FOUND';
	}

	private function phoenixx_pincodeonshiping_get_country_and_state($country_code = ''){
		global $woocommerce;
	    $response 		= [];
	    $countries_obj  = new WC_Countries();
	    $response   	= $countries_obj->__get('countries');
	  	
	  	if(isset($country_code) && !empty($country_code)){
	  		$country_code 	= sanitize_text_field($country_code);
	  		$state 			= $countries_obj->get_states( $country_code );
	        $response 		= $state ? $state : 'State Not Found';
	  	}
	  	return $response;
	}

	public function phoenixx_pincodeonshiping_get_state(){
        $country_code 	= (isset($_POST['country_code'])) ? sanitize_text_field($_POST['country_code']) :'';
        $countries_obj  = new WC_Countries();
        $state = $countries_obj->get_states( $country_code );
        $response = [];
        if($state){
            $response = ['status' => '1','state'=> $state ];
        }else{
            $response = ['status' => '0','state'=>'State Not Found'];
        }
        return wp_send_json($response);
        wp_die();
    }
}
