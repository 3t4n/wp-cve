<?php 

namespace Element_Ready\Base;
use Element_Ready\Base\BaseController;

/**
* signIn form widget
*/
class SignIn extends BaseController
{

     public $errors = [];
     public $success = null;
    
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
         
        if(isset($_SESSION["element_ready_quomodo_login_msg"])){
            unset( $_SESSION["element_ready_quomodo_login_msg"] );
        }
       
        if(isset($data['password']) && $data['password'] == ''){
            $this->errors = esc_html__('Password should not empty','element-ready-lite');
            $_SESSION["element_ready_quomodo_login_msg"]['valid_email'] = esc_html__('Password should not empty','element-ready-lite');
        } 
 
        if(isset($_SESSION["element_ready_quomodo_login_msg"])){
            return true;
        }    

        return false;
        
    }
    public function login($data){
       
        $creds = array(
            'user_login'    => sanitize_text_field($data['username']),
            'user_password' => sanitize_text_field($data['password']),
            'remember'      => true
        );

        if(is_email($data['username'])){
            $creds[ 'user_email' ] = $data['username'];
        }
       
        $creds[ 'remember' ] = sanitize_text_field( isset( $_POST[ 'rememberme' ] ) ? true : false );
        $user = wp_signon( $creds, false );
        $userID = $user->ID;

        wp_set_current_user( $userID, $data['username']);
        wp_set_auth_cookie( $userID, true, false );

        if ( is_wp_error( $user ) ) {
            $_SESSION[ 'element_ready_quomodo_login_msg' ][ 'valid_email' ] = $user->get_error_message();
        }else{
            $this->success = esc_html__( 'Login Success' , 'element-ready-lite' ); 
            $_SESSION[ 'element_ready_quomodo_login_success_msg' ] = esc_html__('Login Success','element-ready-lite');  
        }      
    }
  
    public function form_submit(){
       
        
        $retrieved_nonce = isset($_REQUEST['_wpnonce'])?$_REQUEST['_wpnonce']:'';
       
        if ( !wp_verify_nonce( $retrieved_nonce, 'element_ready_quomodo_login_action' ) ){
          return;  
        }

        if( !session_id() )
        {
            session_start(['read_and_close' => true]);
        }
        $values = map_deep( $_REQUEST, 'sanitize_text_field' );
        $error =  $this->form_validate($values); 
        if($error == false){
          $this->login($values);   
        }
        $request = sanitize_url($_SERVER["HTTP_REFERER"]);
        if(isset($_REQUEST['er_redirect'])){
            $request = sanitize_url($_REQUEST['er_redirect']);
        }
        wp_redirect(esc_url_raw( $request )); exit;
   }
	

   
}