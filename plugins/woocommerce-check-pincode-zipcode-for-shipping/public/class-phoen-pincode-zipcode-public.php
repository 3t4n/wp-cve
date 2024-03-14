<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://http://phoeniixx.com/
 * @since      1.0.0
 *
 * @package    Phoen_Pincode_Zipcode
 * @subpackage Phoen_Pincode_Zipcode/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Phoen_Pincode_Zipcode
 * @subpackage Phoen_Pincode_Zipcode/public
 * @author     PHOENIIXX TEAM <raghavendra@phoeniixx.com>
 */
class Phoen_Pincode_Zipcode_Public {

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
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name 	= $plugin_name;
		$this->version 		= $version;
		$this->pincode 		= new Pincode; 
		$this->setting 		= new Setting;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/phoen-pincode-zipcode-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/phoen-pincode-zipcode-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script($this->plugin_name, 'phoeniixx_pincode_zipcode_ajax_check', array( 'ajaxurl' => admin_url( 'admin-ajax.php'), 'nonce' => wp_create_nonce('phoeniixx_pincode_zipcode_ajax_nonce') ));
	}

	private function phoeniixx_pincode_zipcode_enter_pincode(){
		$enter_pincode_file['file'] 	= PHOEN_PINCODE_ZIPCODE_PATH.'public/pages/enter-pincode.php';
		$enter_pincode_file['setting']	= $this->setting::get();
		if(file_exists($enter_pincode_file['file'])){
			return $enter_pincode_file;
		}
		return false;
	}

	private function phoeniixx_pincode_zipcode_available_pincode($pincode){
		$pincode_data_set['available_pincode_file'] = PHOEN_PINCODE_ZIPCODE_PATH.'public/pages/available-pincode.php';

		if(file_exists($pincode_data_set['available_pincode_file']) && !empty($pincode) && !is_nan($pincode)){
			$pincode_data_set['pincode'] 		= sanitize_text_field( $pincode );
			$pincode_data_set['setting'] 		= $this->setting::get();
			$pincode_data_set['pincode_data'] 	= $this->pincode::select($pincode,'pincode');

			if(!empty($pincode_data_set['pincode_data']) && is_array($pincode_data_set['pincode_data'])){
			
				if(is_user_logged_in()){
					update_user_meta(get_current_user_id(),'shipping_postcode',$pincode);
					update_user_meta(get_current_user_id(),'billing_postcode',$pincode);
					update_user_meta(get_current_user_id(),'shipping_state',$pincode_data_set['pincode_data'][0]['state']);
					update_user_meta(get_current_user_id(),'billing_state',$pincode_data_set['pincode_data'][0]['state']);
					update_user_meta(get_current_user_id(),'shipping_country',$pincode_data_set['pincode_data'][0]['country']);
					update_user_meta(get_current_user_id(),'billing_country',$pincode_data_set['pincode_data'][0]['country']);
				}
			}else{
				$pincode_data_set = [];
			}
			return $pincode_data_set;
		}
		return array();
	}

	private function phoeniixx_pincode_zipcode_include_by_ajax($file = false,$available_pincode_data){
		if($file){
			ob_start();
            	require($file);
            	$contents = ob_get_contents();
        	ob_end_clean();
        	return $contents;
		}
		return false;
	}

	private function phoeniixx_pincode_zipcode_set_file(){
		$enter_pincode_data = $this->phoeniixx_pincode_zipcode_enter_pincode();
		if(isset($_COOKIE['phoeniixx-pincode-zipcode']) && !empty($_COOKIE['phoeniixx-pincode-zipcode'])){
			$available_pincode_data = $this->phoeniixx_pincode_zipcode_available_pincode($_COOKIE['phoeniixx-pincode-zipcode']);
			if(!empty($available_pincode_data)){
				require($available_pincode_data['available_pincode_file']);
			}else{
				require($enter_pincode_data['file']);
			}
		}else{
			require($enter_pincode_data['file']);
		}
	}

	public function phoeniixx_pincode_zipcode_after_add_to_cart_button(){
		echo "<div id='phoeniixx-pincode-file' style='margin-bottom:2%;'>";
		$this->phoeniixx_pincode_zipcode_set_file();
		echo "</div>";
	}

	public function phoeniixx_check_pincode_by_ajax(){
		$data['status'] 		= false;
		$data['file'] 			= false;
		$pincode 				= isset($_POST['pincode']) ? sanitize_text_field( $_POST['pincode'] ) : '';
		$nonce_verify 			= wp_verify_nonce( $_POST['nonce'], 'phoeniixx_pincode_zipcode_ajax_nonce' );
		
		if(!empty($pincode) && !is_null($pincode)){
			
			$available_pincode_data = $this->phoeniixx_pincode_zipcode_available_pincode($pincode);	
			$pincode 				= $this->pincode::select($pincode,'pincode');
			
			if(!empty($pincode) && is_array($pincode)){
				setcookie("phoeniixx-pincode-zipcode",$_POST['pincode'],time() + (10 * 365 * 24 * 60 * 60),"/");
				$data['status'] = true;
				$data['file'] 	= !empty($available_pincode_data) ? $this->phoeniixx_pincode_zipcode_include_by_ajax($available_pincode_data['available_pincode_file'],$available_pincode_data) : false;
			}
        } 	
        return wp_send_json($data);
        wp_die();	
	}

	function phoeniixx_pincode_zipcode_validate_order( $fields, $errors ){
    	global $table_prefix, $wpdb;

    	if( isset($fields[ 'billing_postcode' ])){
    		$pincode_data = $this->pincode::select($fields[ 'billing_postcode' ],'pincode');

    		if(empty($pincode_data)){
    			$errors->add( 'validation', '<li><strong>Pincode </strong> Pincode Does Not Exists</li>' );
	    	}

	    	// if(!empty($pincode_data) && $pincode_data[0]['country'] != $fields[ 'billing_country' ]){
    		// 	$errors->add( 'validation', 'Country Does Not Match With Pincode' );
    		// }

    		// if(!empty($pincode_data) && $pincode_data[0]['state'] != $fields[ 'billing_state' ]){
    		// 	$errors->add( 'validation', 'State Does Not Match With Pincode' );
    		// }
    	}
    }

    public function phoeniixx_pincode_zipcode_addToCart_validate(){
		$verify_script = PHOEN_PINCODE_ZIPCODE_PATH.'public/pages/script.php';
		if(file_exists($verify_script)){
			require_once($verify_script);
		}
	}

	public function phoeniixx_pincode_zipcode_add_script(){
		$setting_data = $this->setting::get();
		?>
		<script>
			const pincode 		= {
		        pincode_error   : "<?= $setting_data['wrong_pincode_error'] ?>",
		        input_error     : "<?= $setting_data['pincode_input_error'] ?>",
		    };
		</script>
		<?php
	}

	public function phoeniixx_zipcode_pinode_is_cod_enable($gateways){
		$pincode 		= $_COOKIE['phoeniixx-pincode-zipcode'];
       	$pincode_data 	= $this->pincode::select($pincode,'pincode');
	    if (!empty($pincode_data) && is_array($pincode_data) && $pincode_data[0]['cod'] !== 'yes'){
	    	unset($gateways['cod']);
        }
        return $gateways;
	}

}//closed closed from here
