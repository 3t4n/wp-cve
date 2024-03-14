<?php
/**
 * Email Customizer for WooCommerce common functions
 *
 * @author    ThemeHiGH
 * @category  Admin
 */

if(!defined('ABSPATH')){ exit; }

if(!class_exists('WECMF_Utils')) :
class WECMF_Utils {
	private static $css_elm_props_map;
	const OPTION_KEY_TEMPLATE_SETTINGS = 'thwecmf_template_settings';
	const SETTINGS_KEY_TEMPLATE_LIST = 'templates';
	const SETTINGS_KEY_TEMPLATE_MAP = 'template_map';
	const OPTION_KEY_ADVANCED_SETTINGS = 'thwecmf_advanced_settings';
	const OPTION_KEY_WECMF_MISC = 'thwecmf_misc_settings';
	const THWECMF_EMAIL_INDEX = array(
		'new_order',
		'cancelled_order',
		'failed_order',
		'customer_on_hold_order',
		'customer_processing_order',
		'customer_completed_order',
		'customer_refunded_order',
		'customer_partially_refunded_order',
		'customer_invoice',
		'customer_note',
		'customer_reset_password',
		'customer_new_account'
	);

	/**
     * WooCommerce version check
     *
     * @param  string $version default version to be checked against
	 * @return boolean current version is greater than 3.0 or not
     */
	public static function thwecmf_woo_version_check( $version = '3.0' ) {
	  	if(function_exists( 'is_woocommerce_active' ) && is_woocommerce_active() ) {
			global $woocommerce;
			if( version_compare( $woocommerce->version, $version, ">=" ) ) {
		  		return true;
			}
	  	}
	  	return false;
	}

	/**
     * Check WooCommerce version for emogrifier comaptibility
     *
     * @param  string $version default version to be checked against
	 * @return sboolean current version is greater than 3.6 or not
     */
	public static function thwecmf_emogrifier_version_check( $version = '3.6' ) {
	  	if(function_exists( 'is_woocommerce_active' ) && is_woocommerce_active() ) {
			global $woocommerce;
			if( version_compare( $woocommerce->version, $version, ">" ) ) {
		  		return true;
			}
	  	}
	  	return false;
	}

	/**
	 * Get decoded json data
	 *
	 * @param  string $data json data
	 * @return object || boolean decoded json data
	 */
	public static function is_json_decode($data){
		$json_data = json_decode($data);
		$json_data = json_last_error() == JSON_ERROR_NONE ?  $json_data : false;
		return $json_data;
	}

	/**
	 * created template directory
	 *
	 * @return boolean whether directory exists or directory created
	 */
	public static function create_directory(){
		$upload_dir = wp_upload_dir();
	    $wecm_dir = $upload_dir['basedir'].'/thwec_templates';
	  	$wecm_dir = trailingslashit($wecm_dir);
	  	$dir_exists = !file_exists($wecm_dir) && !is_dir($wecm_dir);
	  	if( $dir_exists ){
	  		return wp_mkdir_p( $wecm_dir );
	  	}
	  	return $dir_exists; 	
	}

    /**
     * created preivew directory
     *
     * @return boolean whether directory exists or directory created
     */
	public static function create_preview(){
		if( ! is_dir( self::get_template_preview_directory() ) ){
			return wp_mkdir_p(self::get_template_preview_directory());
		}
		return is_dir( self::get_template_preview_directory() );
	}

    /**
     * delete preivew directory
     *
     * @return boolean whether directory deleted or not
     */
	public static function delete_preview(){
		$dir = self::get_template_preview_directory();
		if( file_exists( $dir ) && is_dir( $dir ) ){
			self::delete_directory( $dir );
		}
	}

    /**
     * Delete the directory and files
     *
     * @param string $dir directory / file path
     * @return boolean whether directory deleted or not
     */
	public static function delete_directory( $dir ){
		$files = scandir( $dir ); // get all file names
		foreach( $files as $file ){ // iterate files
			if( $file != '.' && $file != '..' ){ //scandir() contains two values '.' & '..' 
				if( is_file( $dir.'/'.$file ) ){
					unlink( $dir.'/'.$file ); // delete file		  	
				}else if( is_dir( $dir.'/'.$file ) ){
					self::delete_directory( $dir.'/'.$file );
				}
			}
		}
		return rmdir( $dir );
	}

    /**
     * Get the path for template preview file
     *
     * @param string $name preview file name
     * @return string preview template path
     */
	public static function preview_path( $name ){
		return THWECMF_CUSTOM_T_PATH.'preview/thwecmf-preview-'.$name.'.php';
	}

	/**
	 * Prepare template name key from user input name
	 *
	 * @param  string $display_name user entered template name
	 * @return string $name template name key
	 */
	public static function prepare_template_name($display_name){
		$name = strtolower($display_name);
		$name = preg_replace('/\s+/', '_', $name);
		return $name;
	}

	/**
	 * Get decoded json data
	 *
	 * @param  string $data json data
	 * @return object || boolean decoded json data
	 */
	public static function thwecmf_is_json_decode($data){
		$json_data = json_decode($data);
		$json_data = json_last_error() == JSON_ERROR_NONE ?  $json_data : false;
		return $json_data;
	}

	/**
	 * Check if user has capability|| roles to do actions
	 *
	 * @return boolean capable or not
	 */
	public static function is_user_capable(){
		$capable = false;
		$user = wp_get_current_user();
		$allowed_roles = apply_filters('thwecmf_user_capabilities_override', array('editor', 'administrator') );
		if( array_intersect($allowed_roles, $user->roles ) ) {
   			$capable = true;
   		}else if( is_super_admin($user->ID ) ){
   			$capable = true;
   		}
   		return $capable;
	}

	/**
	 * Get plugin initial settings
	 *
	 * @return array $settings plugin settings
	 */
	public static function thwecmf_setup_initial_settings(){
		$settings = self::thwecmf_get_template_settings();
		if(isset($settings['templates']) && empty($settings['templates'])){
			$settings = self::thwecmf_save_template_settings(self::get_default_templates_json());
		}else{
			return true;
		}
		return $settings;
	}
	
    /**
     * Get the template settings
     *
     * @return array template settings
     */
	public static function thwecmf_get_template_settings(){
		$settings = get_option(self::OPTION_KEY_TEMPLATE_SETTINGS);
		if(empty($settings)){
			$settings = array(
				self::SETTINGS_KEY_TEMPLATE_LIST => array(), 
				self::SETTINGS_KEY_TEMPLATE_MAP => array()
			);
		}
		return $settings;
	}

    /**
     * Get list of templates created
     *
     * @param boolean/array $settings template settings
     * @return array list of created templates or empty array
     */
	public static function thwecmf_get_template_list($settings=false){
		if(!is_array($settings)){
			$settings = self::thwecmf_get_template_settings();
		}
		return is_array($settings) && isset($settings[self::SETTINGS_KEY_TEMPLATE_LIST]) ? $settings[self::SETTINGS_KEY_TEMPLATE_LIST] : array();
	}

    /**
     * Get the template map
     *
     * @param boolean/array $settings template settings
     * @return array template map
     */
	public static function thwecmf_get_template_map($settings=false){
		if(!is_array($settings)){
			$settings = self::thwecmf_get_template_settings();
		}
		return is_array($settings) && isset($settings[self::SETTINGS_KEY_TEMPLATE_MAP]) ? $settings[self::SETTINGS_KEY_TEMPLATE_MAP] : array();
	}

     /**
     * Reset the template settings
     *
     * @return array resetted template settings
     */
	public static function thwecmf_reset_template_map(){
		$settings = self::thwecmf_get_template_settings();
		if( is_array($settings) && isset($settings[self::SETTINGS_KEY_TEMPLATE_MAP]) ){
			$settings[self::SETTINGS_KEY_TEMPLATE_MAP] = array();
		}
		return $settings;
		
	}

    /**
     * Save template settings
     *
     * @param array $settings template settings to save
     * @param boolean $new update existing or add new settings
     * @return boolean $result settings saved or not
     */
	public static function thwecmf_save_template_settings($settings, $new=false){
		$result = false;
		if($new){
			$result = add_option(self::OPTION_KEY_TEMPLATE_SETTINGS, $settings);
		}else{
			$result = update_option(self::OPTION_KEY_TEMPLATE_SETTINGS, $settings);
		}
		return $result;
	}

    /**
     * Get advanced settings
     *
     * @return boolean/array advanced settings. boolean if empty
     */
	public static function thwecmf_get_advanced_settings(){
		$settings = get_option(self::OPTION_KEY_ADVANCED_SETTINGS);
		return empty($settings) ? false : $settings;
	}
	
    /**
     * Save value of a key from template settings
     *
     * @param array $settings template settings from which key to be retrieved
     * @param string $key key to be retrieved from settings
     * @return string value corresponding to the key from $settings
     */
	public static function thwecmf_get_setting_value($settings, $key){
		if(is_array($settings) && isset($settings[$key])){
			return $settings[$key];
		}
		return '';
	}
	
    /**
     * Get value of corresponding key from advanced settings
     *
     * @param string $key key to be retrieved from settings
     * @return string value corresponding to the key from $settings
     */
	public static function thwecmf_get_settings($key){
		$settings = self::thwecmf_get_advanced_settings();
		if(is_array($settings) && isset($settings[$key])){
			return $settings[$key];
		}
		return '';
	}

    /**
     * Get the template file path
     *
     * @param string $file name of the template path to be retrieved
     * @param boolean $preview if path of template retrieved should be preview template or not
     * @param boolean/string $ext extension of the file to be previewed
     * @return string/boolean file path if it exists or false
     */
	public static function get_template($file,$preview,$ext=false){
    	$extension = $ext ? $ext : 'php'; 
    	if( $preview ){
    		$file = self::preview_path( $file );
    	}else{
    		$file = THWECMF_CUSTOM_T_PATH.$file.'.'.$extension;
    	}
    	return file_exists($file) ? $file : false;
	}

     /**
     * Get the template file path
     *
     * @return array list of email statuses avialable in plugin
     */
	public static function email_statuses(){
		$email_statuses = array(
			'admin-new-order' 					=> 'Admin New Order',
			'admin-cancelled-order'				=> 'Admin Cancelled Order',
			'admin-failed-order'				=> 'Admin Failed Order',
			'customer-completed-order'			=> 'Customer Completed Order',
			'customer-on-hold-order'			=> 'Customer On Hold Order',
			'customer-processing-order'			=> 'Customer Processing Order',
			'customer-refunded-order'			=> 'Customer Refund Order',
			'customer-partially-refunded-order'	=> 'Customer Partially Refunded Order',
			'customer-invoice'					=> 'Customer Invoice / Order details',
			'customer-note'						=> 'Customer Note',
			'customer-reset-password'			=> 'Reset Password',
			'customer-new-account'				=> 'New Account',
		);
		return $email_statuses;
	}

    /**
     * Get default template contents
     *
     * @return boolean/array default template contents / false if not found
     */
	public static function thwecmf_get_templates( $name ){
		$path = TH_WECMF_PATH.'classes/inc/settings.txt';
		$content = file_get_contents( $path );
		$settings = unserialize(base64_decode($content));
		if( $name && isset( $settings['templates'][$name] ) ){
			return self::sanitize_template_data( $settings['templates'][$name] );
		}
		return false;
	}

    /**
     * Reset the template to default
     *
     * @param string $template template name of the template to reset
     * @return boolean reset or not
     */
	public static function thwecmf_reset_templates( $template ){
		$reset = false;
		$db_settings = self::thwecmf_get_template_settings();
		$template_settings = self::thwecmf_get_templates( $template );
		if( $template_settings &&  is_array( $template_settings ) ){
			$db_settings['templates'][$template] = $template_settings;
			$reset = self::thwecmf_save_template_settings( $db_settings);
		}
		return $reset;
	}	

    /**
     * Sanitize template data
     *
     * @param array template data
     * @return array sanitized template data
     */
    public static function sanitize_template_data( $data, $react_import=false ){
        foreach ( $data as $key => $value ) {
            if( $key === "file_name" ){
                $data[$key] = sanitize_file_name( $value );

            }else if( $key === "display_name" ){
                $data[$key] = sanitize_text_field( $value );

            }else if( $key === "template_data" ){
            	if($react_import){
            		$data["template_json"] = wp_kses( $value ,wp_kses_allowed_html('post') );
            	}else{
            		$data[$key] = wp_kses( $value ,wp_kses_allowed_html('post') );
            	}
                

            }
        }
        return $data;
    }

    /**
     * Check if the template is a valid email customizer template
     *
     * @return boolean valid template or not
     */
	public static function wecm_valid( $name = '', $key=false ){
		if( $key && !empty( $name ) ){
			$name = str_replace("_", "-", $name);
		}else{
			$name = isset($_POST['template_name']) ? sanitize_text_field($_POST['template_name']) : "";
			$name = $name === "Customer Partial Refunded Order" ? "Customer Partially Refunded Order" : $name;
			$name = $name ? str_replace(" ", "-", strtolower($name)) : $name;
		}
		if( $name && array_key_exists( $name, self::email_statuses() ) ){
			return true;
		}
		return false;
	}

    /**
     * Check if the tempalte save action is a valid action
     *
     * @return boolean valid action or not
     */
	public static function is_valid_action(){
		$ajax_ref = check_ajax_referer( 'thwecmf_ajax_save', 'thwecmf_security', false);
		if( $ajax_ref && self::is_user_capable() ) {
			return true;
		}
		return false;
	}

    /**
     * Check if the template is in customizable template list
     *
     * @return boolean whether it is a customizable template or not
     */
	public static function is_template($name=''){
		$template = !empty( $name ) ? $name : false;
		$template = !$template && isset( $_POST['template_name'] ) ? sanitize_text_field( $_POST['template_name'] ) : $template;
		$template = str_replace( " ", "_", $template);
		if( $template && in_array( $template, self::THWECMF_EMAIL_INDEX ) ){
			return true;
		}
		if( $template && in_array( str_replace("admin_", "", $template ), self::THWECMF_EMAIL_INDEX )  ){
			return true;
		}
		return false;
	}

     /**
     * Get the logged in user email
     *
     * @return string logged in user email
     */
	public static function get_logged_user_email(){
		$email = '';
	   	$current_user = wp_get_current_user();
		if( $current_user !== 0 ){
			$email =  $current_user->user_email;
		}
		return $email;
	}

	/**
	 * Get logged in user object
	 *
	 * @return boolean||object user object
	 */
	public static function get_logged_in_user(){
	   	$current_user = wp_get_current_user();
		if( $current_user !== 0 ){
			return $current_user;
			
		}
		return false;
	}

	/**
	 * Check if empty or not
	 *
	 * @param  string $value variable||key (in case of array) to be tested 
	 * @param  string $type type of variable
	 * @param  string||boolean $index array key if string
	 * @return boolean empty or not
	 */
	public static function is_not_empty( $value, $type, $index=false ){
		switch ( $type ) {
			case 'array':
				$empty = is_array( $value ) && !empty( $value );
				break;
			default:
				$empty = isset( $value[$index] ) && !empty( $value[$index] ); 
				break;
		}

		return $empty;
	}

	/**
	 * Email compatibility styles
	 *
	 * @return string styles
	 */
	public static function get_thwecmf_styles(){
		$styles = '#tpf_t_builder #template_container,#tpf_t_builder #template_header,#tpf_t_builder #template_body,#tpf_t_builder #template_footer{width:100% !important;}';
		$styles.= '#tpf_t_builder #template_container{width:100% !important;border:0px none transparent !important;box-shadow: none !important;}';
		$styles .= '#tpf_t_builder #body_content > table:first-child > tbody > tr > td{padding:15px 0px !important;}'; //To remove the padding after header when woocommerce header hook used in template (48px 48px 0px) 
		$styles .= '#tpf_t_builder div > table td,#tpf_t_builder div > table th{ font-size: 14px;line-height:150%;font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;}';
		
		// Fix - Order table quantity column header text breaks
		$styles .= '#tpf_t_builder div > table.td th, #tpf_t_builder div > table.td td{word-break: keep-all;}';
		$styles.= '#tpf_t_builder #wrapper{padding:0;background-color:transparent;}';
		// $styles.= '.main-builder .thwecmf-block a{color: #1155cc;}';
		$styles.= '#tpf_t_builder .thwecmf-columns p{color:#636363;font-size:14px;}';
		$styles.= '#tpf_t_builder .thwecmf-columns .td .td{padding:12px;}';
		$styles.= '#tpf_t_builder ul.wc-item-meta{font-size: small;margin: 1em 0 0;padding: 0;list-style: none;}';
		$styles.= '#tpf_t_builder ul.wc-item-meta li{margin: 0.5em 0 0;padding: 0;}';
		$styles.= '#tpf_t_builder ul.wc-item-meta li p{margin: 0;}';
		$styles.= '#tpf_t_builder .thwecmf-columns .address{font-size:14px;line-height:150%;}';
        if( apply_filters( 'thwec_enable_global_link_color', true ) ){
    		$styles .= '#tpf_t_builder  a.thwecmf-link,
            #tpf_t_builder  .thwecmf-block-text a,
            #tpf_t_builder .thwecmf-block-billing a,
            #tpf_t_builder .thwecmf-block-shipping a,
            #tpf_t_builder .thwecmf-block-customer a{
				color: '.self::get_template_global_css('link-color').';
                text-decoration: '.self::get_template_global_css('link-decoration').';
			}';
        }
        $styles.= '.thwecmf_downloadable_table{border:1px solid #e5e5e5;}';
        $styles.= '.thwecmf_downloadable_table th,.thwecmf_downloadable_table td{ font-size: 14px;line-height:150%;font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;padding:12px;}';
		return $styles;
	}

	/**
	 * Check if the action is template edit action
	 *
	 * @param  string $page page slug
	 * @return boolean template edit action or not
	 */
	public static function edit_template( $page ){
		if( $page == 'thwecmf_email_customizer' && isset( $_POST['i_edit_template'] ) ){
			return true;
		}
		return false;
	}

	/**
	 * Check if template file exists or not
	 *
	 * @return boolean template file existence
	 */
	public static function get_status(){
		$filename = isset( $_POST['i_template_name'] ) ? sanitize_text_field( $_POST['i_template_name'] ) : false;
		if( $filename ){
			$file = rtrim(THWECMF_CUSTOM_T_PATH, '/').'/'.$filename.'.php';
			if( file_exists( $file ) ){
				return true;
			}
		}
		return false;
	}

    /**
     * Font family list
     *
     * @return array font family list
     */
    public static function font_family_list(){
        return array(
            "helvetica"     =>  "'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif",
            "georgia"       =>  "Georgia, serif",
            "times"         =>  "'Times New Roman', Times, serif",
            "arial"         =>  "Arial, Helvetica, sans-serif",
            "arial-black"   =>  "'Arial Black', Gadget, sans-serif",
            "comic-sans"    =>  "'Comic Sans MS', cursive, sans-serif",
            "impact"        =>  "Impact, Charcoal, sans-serif",
            "tahoma"        =>  "Tahoma, Geneva, sans-serif",
            "trebuchet"     =>  "'Trebuchet MS', Helvetica, sans-serif",
            "verdana"       =>  "Verdana, Geneva, sans-serif",
        );
    }

	/**
	 * Check if current email template is an allowed template
	 *
	 * @param  string $email email template name
	 * @return boolean compatible email or not
	 */
	public static function is_compatible_email( $email, $object=true ){
		if(!$object){
			if( in_array( $email, self::THWECMF_EMAIL_INDEX ) ){
				return true;
			}
		}else if( in_array( $email->id, self::THWECMF_EMAIL_INDEX ) ){
			return true;
		}
		return false;
	}

	/**
	 * Get preview directory path
	 *
	 * @return string directory path
	 */
    public static function get_template_preview_directory(){
    	return THWECMF_CUSTOM_T_PATH.'/preview';
    }

	/**
	 * Get WooCommerce orders
	 *
	 * @return array orders
	 */
	public static function get_woo_orders(){
		$count = apply_filters( 'thwec_template_preview_order_count', 5 );
		if(apply_filters( 'thwec_get_order_by_wp_query', false )) {
			$orders = new WP_Query(
				array(
					'post_type'      => 'shop_order',
					'post_status'    => array_keys( wc_get_order_statuses() ),
					'posts_per_page' => $count,
				)
			);
			$order_objects = [];
			if ( $orders->posts ) {
				foreach ( $orders->posts as $order ) {
					$order_objects[] = wc_get_order( $order->ID );
				}
			}
			return $order_objects;
		}
        // $orders = wc_get_orders( array(
		//     'numberposts' => $count,
		//     'status'      => array_keys( wc_get_order_statuses() ), // Retrieve all order statuses
		// ) );
        //OR
        $WC_Order_Query = new WC_Order_Query(
            array(
                'status'    => array_keys( wc_get_order_statuses() ),
                'limit'        => $count
            )
        );
        $orders = $WC_Order_Query->get_orders();

        return $orders? $orders : array();
	}

    /**
     * Global link styles to be used in email template
     *
     * @return string style
     */
    public static function get_template_global_css( $type ){
        $css = '';
        if( $type == 'link-color' ){
            $link_color = '#1155cc';
            $link_color = apply_filters('thwecmf_template_link_color', sanitize_hex_color( $link_color ) );
            $css = is_null( $link_color ) ? '#1155cc' : $link_color;

        }else if( $type == 'link-decoration' ){
            $css = apply_filters('thwecmf_template_link_decoration', sanitize_text_field( 'underline' ) );
        }
        
        return $css;
    }

    public static function wecmf_capability() {
		$allowed = array('manage_woocommerce', 'manage_options');
		$capability = apply_filters('thwecmf_required_capability', 'manage_woocommerce');

		if(!in_array($capability, $allowed)){
			$capability = 'manage_woocommerce';
		}
		return $capability;
	}

	public static function dump( $str, $margin="100" ){
		?>
		<pre style="margin-left:<?php echo esc_attr($margin); ?>px;">
			<?php echo var_dump($str); ?>
		</pre>
		<?php
	}

}
endif;