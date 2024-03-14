<?php

/**
 * Login class.
 *
 * @category   Class
 * @package    OTPless
 * @subpackage WordPress
 * @author     OTPless <help@otpless.com>
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * php version 7.4.0
 */

class Otpless_Login
{
    public $path;
    public $url;
    public $wc;
    public $redirect_page;
    public $new_user_redirect_page;
    public $role;
    public $cId;
    public $appId;
    public $cSecret;
    private $user_email;
    private $selected_pages=[];

    /**
     * Constructor
     *
     * @since 0.0.0
     * @access public
     */
    public function __construct()
    {

	    $this->path = plugin_dir_path( dirname(__FILE__) );
        $this->url = plugin_dir_url( dirname(__FILE__) );
       
        $otpless_options = get_option('otpless_option_name');
        if (empty($otpless_options)){
            $otpless_options['wc_login'] = 1;
            $otpless_options['widget_login'] = 1;
        }
        
        $this->redirect_page = isset($otpless_options['redirect_page']) ? $otpless_options['redirect_page'] : '';
        $this->new_user_redirect_page = isset($otpless_options['new_user_redirect_page']) ? $otpless_options['new_user_redirect_page'] : '';
        $this->selected_pages = isset($otpless_options['pages']) ? $otpless_options['pages'] : array();
        
        $this->cId = isset($otpless_options['clientId']) ? $otpless_options['clientId'] : '';
        $this->appId = isset($otpless_options['appId']) ? $otpless_options['appId'] : '';
        $this->cSecret = isset($otpless_options['clientSecret']) ? $otpless_options['clientSecret'] : '';
        if(empty($this->appId)){
            $this->appId = $this->get_otpless_merchant_app_id();
        }
		
		if(empty($this->appId) || empty($this->cId) || empty($this->cSecret)){
			add_action('admin_notices', array($this, 'my_custom_notice'));
		}

            $this->role = isset($otpless_options['user_role']) ? $otpless_options['user_role'] : "customer";
    
            if($otpless_options['wc_login'] == 1 && !is_user_logged_in()){
                add_filter( 'woocommerce_locate_template', array($this, 'my_custom_myaccount_page'), 10, 3 );
                add_filter( 'woocommerce_account_menu_items', array($this,'remove_logout_link' ));
                add_filter( 'wp_enqueue_scripts', array($this,'remove_account_password_fields' ));
            }
    
            if($otpless_options['widget_login'] == 1 && !is_user_logged_in()){
                add_action('template_redirect', array($this, 'wpse_inspect_page_id'));
            }
            add_filter( 'auto_update_plugin', array($this, 'disable_otpless_wordpress_auto_update'), 10, 2 );
			
            add_shortcode( 'otpless_signin', array($this, 'otpless_login_page_drag_and_drop_button_shortcode'));
            add_action( 'init', array( $this, 'otpless_authorize_user' ), 10 );
        // }    
    }
	
	function my_custom_notice() {
		$setting_url =  site_url() . "/wp-admin/options-general.php?page=OTPless";
		?>
<style>
.otpless-a:hover {
    color: #000;
    text-decoration: underline !important
}
</style>
<div class="notice notice-info is-dismissible"
    style="background-color: #ffedee;font-size: 13px;color: black;padding: 10px;border-radius: 5px;display: flex;align-items: center;border-left-color: #d63638;">
    <img src="https://d1j61bbz9a40n6.cloudfront.net/website/home/v4/logo/black_logo.svg" /><span
        style="font-weight: 600;">Complete your integration - OTPLESS</span>&nbsp; Enter the required details in the
    website settings. <a href="<?php echo $setting_url;?>" class="otpless-a"
        style="color: #000000 !important;    margin-left: 6px;text-decoration: none;font-size: 17px;"
        target="_blank">Click here for more information.</a>

</div>
<?php
	}

    public function get_otpless_merchant_app_id(){
        
        $url = site_url();
        $post_data = json_encode(array(
            "loginUri" => $url,
            "platform" => "WORDPRESS"
        ));

        $clientId = $this->cId; 
        $clientSecret = $this->cSecret; 

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://metaverse.otpless.app/internal/merchant/get-app-details',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
                'clientId: ' . $clientId,
                'clientSecret: ' . $clientSecret,
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $decoded_response = json_decode($response, true);
        if ($decoded_response && $decoded_response['statusCode'] == "200") {
            return $decoded_response['data']['appId'];
        } else {
            return '';
        }
    }

    function disable_otpless_wordpress_auto_update( $update, $item ) {
        if ( $item->slug === 'otpless' || $item->plugin === 'otpless/wp-otpless.php') {
            return false;
        }
    
        return $update;
    }

    function wpse_inspect_page_id() {
        if (!is_admin() && !is_feed() && !is_trackback()) {
            $current_page_id = get_queried_object_id();
            $this->current_page_id = $current_page_id;
            $this->checkPage();
        }
    }

    function checkPage() {
        if (!empty($this->selected_pages)) {
            if (in_array($this->current_page_id, $this->selected_pages)) {
                if (!is_user_logged_in()) {
                    add_action('wp_enqueue_scripts', array($this, 'otpless_wordpress_script'));
                }
            }
        }else{
            if(!is_user_logged_in()){
                add_action( 'wp_enqueue_scripts', array($this, 'otpless_wordpress_script'));
            }
        }
    }

    function otpless_wordpress_script() {
        ?>
<script>
window.isClientUserLogin = false;
</script>
<?php
        $this -> importScript();
    }


    function remove_account_password_fields( $fields ) {
        ?>
<style>
.woocommerce-EditAccountForm.edit-account fieldset {
    display: none;
}
</style>
<?php
	}

    function remove_logout_link( $menu_links ){
        unset( $menu_links['customer-logout'] );
        return $menu_links;
    }

    function my_custom_myaccount_page( $template, $template_name, $template_path ) {
        $basename = basename( $template );
        if( $basename == 'form-login.php' ) {
            $template = plugin_dir_path( __FILE__ ) . 'templates/otpless-login-page.php';
        }
        return $template;
    }

    function importScript() {
        $template = '<script src="https://otpless.com/v2/wordpress.js.gz" data-appid="'.$this->appId.'" cid="'.$this->cId.'" id="otpless-sdk"></script>';
        echo $template;
    }
    

    function otpless_login_page_drag_and_drop_button_shortcode() {
        if(!is_user_logged_in()){
                $html = '<div id="otpless-login-page" ></div><script src="https://otpless.com/v2/wordpress.js.gz" data-appid="'.$this->appId.'" cid="'.$this->cId.'"  id="otpless-sdk"></script>';
          return $html;
        }else{
            $html = '';
        }
        return $html;
    }

    public function otpless_create_user($mobile, $name, $email){

        if(!$email == '' && !$email == null && !$mobile == '' && !$mobile == null){
            $result = wp_create_user($mobile, wp_generate_password(), $email);  // Create wp user
            if(is_wp_error($result)){ // If get error to create the user - log message
                $error = $result->get_error_message();
                $this->log( $error );
            }else{ // If user was successfully created - receive and return login url

                $user_data = get_user_by('id', $result);
                $user_id_role = new WP_User($user_data->ID);
                $user_id_role->set_role($this->role);
    
                wp_update_user( array(
                    'ID' => $user_data->ID,
                    'display_name' =>  $name,
                    'user_nicename' => $name
               ) );
    
               $current_time = current_time('mysql');

               update_user_meta( $user_data->ID, 'last_login', $current_time);
               update_user_meta( $user_data->ID, 'first_name', $name );
               update_user_meta( $user_data->ID, 'mobile', $mobile );
               update_user_meta( $user_data->ID, 'nickname', $name );
               update_user_meta( $user_data->ID, 'billing_phone', $this->getMobileNumberWithPlus($mobile) );
               
               $user_data = get_user_by( 'login', $mobile);
                if($user_data){
                    $this->login_session($user_data->ID, "NEW");
                }

                if($login_url = $this->get_login_url($user_data)){
                    return $login_url;
                }
            }
        }else if(!$email == '' && !$email == null && ( $mobile == '' || $mobile == null)) {
            $result = wp_create_user($email, wp_generate_password(), $email);
            if(is_wp_error($result)){ // If get error to create the user - log message
                $error = $result->get_error_message();
                $this->log( $error );
            }else{ // If user was successfully created - receive and return login url

                $user_data = get_user_by('id', $result);
                $user_id_role = new WP_User($user_data->ID);
                $user_id_role->set_role($this->role);
    
                wp_update_user( array(
                    'ID' => $user_data->ID,
                    'display_name' =>  $name,
                    'user_nicename' => $name
               ) );
    
               $current_time = current_time('mysql');

               update_user_meta( $user_data->ID, 'last_login', $current_time);
               update_user_meta( $user_data->ID, 'first_name', $name );
               update_user_meta( $user_data->ID, 'mobile', $mobile );
               update_user_meta( $user_data->ID, 'nickname', $name );
               update_user_meta( $user_data->ID, 'billing_phone', $this->getMobileNumberWithPlus($mobile) );
               
               
               $user_data = get_user_by( 'email', $email);
                if($user_data){
                    $this->login_session($user_data->ID, "NEW");
                }
                if($login_url = $this->get_login_url($user_data)){
                    return $login_url;
                }
            }
        }else {
            $result = wp_create_user($mobile, wp_generate_password(), $mobile.'@otplessmail.com');
            if(is_wp_error($result)){ // If get error to create the user - log message
                $error = $result->get_error_message();
                $this->log( $error );
            }else{ // If user was successfully created - receive and return login url

                $user_data = get_user_by('id', $result);
                $user_id_role = new WP_User($user_data->ID);
                $user_id_role->set_role($this->role);
    
                wp_update_user( array(
                    'ID' => $user_data->ID,
                    'display_name' =>  $name,
                    'user_nicename' => $name
               ) );
    
               $current_time = current_time('mysql');

               update_user_meta( $user_data->ID, 'last_login', $current_time);
               update_user_meta( $user_data->ID, 'first_name', $name );
               update_user_meta( $user_data->ID, 'mobile', $mobile );
               update_user_meta( $user_data->ID, 'nickname', $name );
               update_user_meta( $user_data->ID, 'billing_phone', $this->getMobileNumberWithPlus($mobile) );
               
               $user_data = get_user_by( 'login', $mobile);
                if($user_data){
                    $this->login_session($user_data->ID, "NEW");
                }
                if($login_url = $this->get_login_url($user_data)){
                    return $login_url;
                }
            }
        }
    }

    public function login_session($user_Id, $user_type){
        $referrer =  sanitize_text_field($_SERVER['HTTP_REFERER']);
        wp_set_auth_cookie( $user_Id, true, true ); 
        $origin = filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL);
        if($user_type == "NEW"){
            if($this->new_user_redirect_page == "" || $this->new_user_redirect_page == null || $this->new_user_redirect_page == $origin || $this->new_user_redirect_page == "self"){
                if (isset($referrer)) {
                    wp_redirect($referrer);
                    $this->exit();
                }
                wp_redirect( site_url() );// Redirect
            }else{

                wp_redirect( $this->new_user_redirect_page ); // Redirect
            }
        }else{
            if($this->redirect_page == "" || $this->redirect_page == null || $this->redirect_page == $origin || $this->redirect_page == "self"){
                if (isset($referrer)) {
                    wp_redirect($referrer);
                    $this->exit();
                }
                wp_redirect( site_url() );// Redirect
            }else{
                wp_redirect( $this->redirect_page ); // Redirect
            }
        }
        
        $this->exit();
    }

    /**
     * Authorize the user
     *
     * Check user auth key, set auth data in cookie and redirect to the page specified in the settings
     * Fired by `login_head` action hook.
     *
     * @since 0.0.0
     * @access public
     */
    public function otpless_authorize_user()
    {
        if(isset($_POST['logout'])){
            wp_logout();
            if (isset($_SERVER['HTTP_REFERER'])) {
                $referrer = $_SERVER['HTTP_REFERER'];
                wp_redirect($referrer);
                $this->exit();
            }
            wp_redirect(home_url());
            $this->exit();
        }
        
        if(isset($_POST['waName']) && (isset($_POST['waNumber']) || isset($_POST['waEmail']))){ 

            if(!$_POST['waNumber'] == "" && !$_POST['waNumber'] == null ){
                $mobile_number = sanitize_text_field( $_POST['waNumber'] ); 
                $user_data = get_user_by( 'login', $mobile_number);
                if($user_data){
                    $display_name = get_user_meta($user_data->ID, 'display_name', true);
                    if (empty($display_name)) {
                        wp_update_user( array(
                            'ID' => $user_data->ID,
                            'display_name' =>  $_POST['waName'],
                            'user_nicename' => $_POST['waName']
                        ) );
                    }
                    if(!$_POST['waEmail'] == "" && !$_POST['waEmail'] == null ){
                        wp_update_user( array(
                            'ID' => $user_data->ID,
                            'user_email' =>  $_POST['waEmail']
                        ) ); 
                    }
                    $current_time = current_time('mysql');

                    update_user_meta( $user_data->ID, 'last_login', $current_time);
                    update_user_meta( $user_data->ID, 'mobile', $mobile_number );
                    update_user_meta( $user_data->ID, 'billing_phone', $this->getMobileNumberWithPlus($mobile_number) );

                    $this->login_session($user_data->ID, "OLD");
                }else{
                    $user_id = $this->get_user_id_by_mobile($mobile_number);
                    if($user_id != 0){
                        $this->login_session($user_id, "OLD");
                    }
                    if(!$_POST['waEmail'] == "" && !$_POST['waEmail'] == null){
                        $email = sanitize_email( sanitize_text_field( $_POST['waEmail'] ) );
                        $user_data = get_user_by( 'email', $email);
                        if($user_data){
                            $display_name = get_user_meta($user_data->ID, 'display_name', true);
                            if (empty($display_name)) {
                                wp_update_user( array(
                                    'ID' => $user_data->ID,
                                    'display_name' =>  $_POST['waName'],
                                    'user_nicename' => $_POST['waName']
                                ) );
                            }

                            $current_time = current_time('mysql');

                            update_user_meta( $user_data->ID, 'last_login', $current_time);
                            update_user_meta( $user_data->ID, 'mobile', sanitize_text_field($_POST['waNumber']));
                            update_user_meta( $user_data->ID, 'billing_phone', $this->getMobileNumberWithPlus(sanitize_text_field($_POST['waNumber'])) );

                            $this->login_session($user_data->ID, "OLD");
                        }
                    }
                    $this->otpless_create_user(sanitize_text_field($_POST['waNumber']), sanitize_text_field($_POST['waName']), sanitize_text_field($_POST['waEmail']));
                }
            }

            if(!$_POST['waEmail'] == "" && !$_POST['waEmail'] == null ){
                $email = sanitize_email( sanitize_text_field( $_POST['waEmail'] ) );
                $user_data = get_user_by( 'email', $email);
                if($user_data){
                    $display_name = get_user_meta($user_data->ID, 'display_name', true);
                    if (empty($display_name)) {
                        wp_update_user( array(
                            'ID' => $user_data->ID,
                            'display_name' =>  $name,
                            'user_nicename' => $name
                        ) );
                    }
                    $current_time = current_time('mysql');

                    update_user_meta( $user_data->ID, 'last_login', $current_time);
                    $this->login_session($user_data->ID, "OLD");
                }else{
                    if(!$_POST['waNumber'] == "" && !$_POST['waNumber'] == null){
                        $mobile_number = sanitize_text_field( $_POST['waNumber'] ); 
                        $user_data = get_user_by( 'login', $mobile_number);
                        if($user_data){
                            $display_name = get_user_meta($user_data->ID, 'display_name', true);
                            if (empty($display_name)) {
                                wp_update_user( array(
                                    'ID' => $user_data->ID,
                                    'display_name' =>  $name,
                                    'user_nicename' => $name
                                ) );
                            }
                            if(!$_POST['waEmail'] == "" && !$_POST['waEmail'] == null ){
                                wp_update_user( array(
                                    'ID' => $user_data->ID,
                                    'user_email' =>  $_POST['waEmail']
                                ) ); 
                            }
                            $current_time = current_time('mysql');

                            update_user_meta( $user_data->ID, 'last_login', $current_time);
                            $this->login_session($user_data->ID, "OLD");
                        }else{
                            $user_id = $this->get_user_id_by_mobile($mobile_number);
                            if($user_id != 0){
                                $this->login_session($user_id, "OLD");
                            }
                        }
                    }
                    $this->otpless_create_user(sanitize_text_field($_POST['waNumber']), sanitize_text_field($_POST['waName']), sanitize_text_field($_POST['waEmail']));
                }
            }
        }
    }

    function getMobileNumberWithPlus($mobile_number) {
        $mobile_number = "+".$mobile_number;
        return $mobile_number;
    }
    

    function get_user_id_by_mobile($mobile_number) {
        $user_id = 0;

        $users = get_users(array(
            'meta_key' => 'mobile',
            'meta_value' => $mobile_number,
            'fields' => 'ID'
        ));
        if (!empty($users)) {
            $user_id = $users[0];
            return $user_id;
        }
        $users = get_users(array(
            'meta_key' => 'billing_phone',
            'meta_value' => $mobile_number,
            'fields' => 'ID'
        ));
    
        if (!empty($users)) {
            $user_id = $users[0]; 
        }else{
            $users = get_users(array(
                'meta_key' => 'billing_phone',
                'meta_value' => "+".$mobile_number,
                'fields' => 'ID'
            ));
        
            if (!empty($users)) {
                $user_id = $users[0];
            }else if (preg_match('/^\d{12}$/', $mobile_number)) {
                $mobile_number = substr($mobile_number, 2);
                $user = get_users(array(
                    'meta_key' => 'billing_phone',
                    'meta_value' => $mobile_number,
                    'fields' => 'ID'
                ));
                if (!empty($user)) {
                    $user_id = $user[0];
                }
            }
        }
    
        return $user_id;
    }

    /**
     * Return url to auto login
     *
     * Generate the url to auth user by user data
     * @param object $user_data
     * @return string
     * @todo fix signature
     *
     * @since 0.0.0
     * @access public
     */
    public function get_login_url($user_data)
    {
        if(!is_wp_error( $key = get_password_reset_key( $user_data ) )){ // Generate password reset key
            $login_url = add_query_arg( array(
                'key' => $key,
                'email' => rawurlencode( $user_data->user_email )
            ), site_url('wp-login.php') );
            return $login_url;
        }
        return false;
    }

    /**
     * Include file by path and return
     *
     * @param string $path
     * @return string
     *
     * @codeCoverageIgnore
     */
    protected function get_template_content(string $path): string
    {
        $html = '';
        if (file_exists($path)) {
            ob_start();
            include $path;
            $html .= ob_get_clean();
        }

        return $html;
    }

    /**
     * Terminates execution of the script
     *
     * Use this method to die scripts!
     *
     * @param string $message
     */
    protected function exit()
    {
        exit;
    }

    /**
     * Logging
     *
     * @param $message
     */
    protected function log($message): void
    {
        if (!is_string($message)) {
            $message = print_r($message, true);
        }
        error_log($message);
    }
}