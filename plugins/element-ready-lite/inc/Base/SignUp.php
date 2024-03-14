<?php 

namespace Element_Ready\Base;
use Element_Ready\Base\BaseController;

/**
* signup form widget
*/
class SignUp extends BaseController
{
	public function register() {
	
        add_action('init',[$this,'form_submit']);
        add_action('init', [$this,'_startSession'], 1);
    }

    function _startSession() {
        if(!session_id()) {
            session_start(['read_and_close' => true]);
        }
    }
    
    function form_validate($data){

        unset($_SESSION["element_ready_quomodo_reg_msg"]);
        //name
        if(isset($data['name']) && $data['name'] == ''){
            $_SESSION["element_ready_quomodo_reg_msg"]['name'] = esc_html__('Name cannot be empty','element-ready-lite');
        }
        // username
        if(isset($data[ 'username' ] ) && $data[ 'username' ] == ''){
            $_SESSION[ 'element_ready_quomodo_reg_msg' ][ 'username' ] = esc_html__('UserName cannot be empty','element-ready-lite');
        }
        if(isset($data['username'])){
            if(username_exists( $data[ 'username' ] ) ){
                $_SESSION[ 'element_ready_quomodo_reg_msg' ][ 'username' ] = esc_html__('UserName already exist','element-ready-lite');
            }
        }
        // email
        if(isset($data['email']) && $data['email'] == ''){
            $_SESSION[ 'element_ready_quomodo_reg_msg' ][ 'email' ] = esc_html__('Email cannot be empty','element-ready-lite');
        }else{

            if ( !is_email( $data['email'] ) ) {
                $_SESSION[ 'element_ready_quomodo_reg_msg' ][ 'valid_email' ] = esc_html__('Email is not valid','element-ready-lite');
            }

            if ( email_exists( $data['email'] ) ) {
                $_SESSION[ 'element_ready_quomodo_reg_msg' ][ 'exist_email' ] = esc_html__('Email is already exist','element-ready-lite');
            }

        }
        //password
        if(isset($data[ 'password' ]) && isset($data[ 'cpassword' ])){
            if( trim($data[ 'password' ]) != trim($data[ 'cpassword' ]) ){
                $_SESSION[ 'element_ready_quomodo_reg_msg' ][ 'password' ] = esc_html__('Password not macth','element-ready-lite');
            }
        }
        if(isset( $_SESSION[ 'element_ready_quomodo_reg_msg' ] ) ){
            return true;
        }
        return false;
    }
   
    public function form_submit(){

        $retrieved_nonce = sanitize_text_field( isset($_REQUEST['_wpnonce'])?$_REQUEST['_wpnonce']:'' );

        if (!wp_verify_nonce($retrieved_nonce, 'element_ready_quomodo_registration_action' ) ){
          return;  
        }
        if( !session_id() )
        {
          session_start(['read_and_close' => true]);
        }
        $values = map_deep($_REQUEST, 'sanitize_text_field');
        $error = $this->form_validate($values); 
        if($error == false){
            $this->user_registration_form_completion($values);   
        }  
        $request = sanitize_url($_SERVER["HTTP_REFERER"]);
        if(isset($_REQUEST['er_redirect'])){
            $request = sanitize_url($_REQUEST['er_redirect']);
        }
        wp_redirect($request); exit;
    }
	
    function user_registration_form_completion($data) {
        
            $userdata = array(
                'first_name' => sanitize_text_field($data['name']),
                'last_name'  => '',
                'user_login' => sanitize_text_field(trim($data['username'])),
                'user_email' => trim(sanitize_email($data['email'])),
                'user_pass'  => trim(sanitize_text_field($data['password'])),
            );
            $user = wp_insert_user( apply_filters( 'element_ready_new_user_args', $userdata )  );
            if ( is_wp_error( $user  ) ) {
                $_SESSION["element_ready_quomodo_reg_msg"]['submit_msg'] = $user->get_error_message();
            }else{
                $creds = array(
                    'user_login'    => sanitize_text_field($data['username']),
                    'user_password' => sanitize_text_field($data['password']),
                    'remember'      => true
                );
                $user = wp_signon( $creds, false );
                if(is_wp_error( $user )){
                    wp_set_current_user( $user->data->ID, $data['username']);
                    wp_set_auth_cookie( $user->data->ID , true, false );
                }
                do_action('element_ready_user_created_successfully',$user);
                $_SESSION["element_ready_quomodo_reg_msg_success"] = esc_html__('Registration Success','element-ready-lite');
            }
       
    }

   
}