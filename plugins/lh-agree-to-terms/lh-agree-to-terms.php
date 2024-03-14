<?php
/**
 * Plugin Name: LH Agree to Terms
 * Plugin URI: https://lhero.org/portfolio/lh-agree-to-terms/
 * Description: Add a required "Agree to terms" checkbox to login and/or register forms.
 * Version: 1.25
 * Author: Peter Shaw
 * Requires PHP: 5.3
 * Author URI: https://shawfactor.com
 * Text Domain: lh_agree_to_terms
 * Domain Path: /languages
*/

if (!class_exists('LH_Agree_to_terms_plugin')) {


class LH_Agree_to_terms_plugin {

var $filename;
var $options;
var $login_field_name = 'lh_agree_to_terms-login_field_name';
var $login_remember_name = 'lh_agree_to_terms-login_remember_name';
var $login_message_name = 'lh_agree_to_terms-login_message_name';
var $login_validity_name = 'lh_agree_to_terms-login_validity_name';
var $registration_field_name = 'lh_agree_to_terms-registration_field_name';
var $registration_message_name = 'lh_agree_to_terms-registration_message_name';
var $registration_validity_name = 'lh_agree_to_terms-registration_validity_name';
var $opt_name = 'lh_agree_to_terms-options';
var $namespace = 'lh_agree_to_terms';


private static $instance;

private function get_validity_message($type) {

if ($type == 'login'){ 

return $this->options[$this->login_validity_name];

} elseif  ($type == 'register'){ 

return $this->options[$this->registration_validity_name];

}


}

private function maybe_set_remember_cookie(){

if (isset($this->options[$this->login_remember_name]) and ($this->options[$this->login_remember_name] == 1)){ 

$cookie_expiry = time()+604800; /* expire in 1 week */

$cookie_expiry = apply_filters('lh_agree_to_terms_remember_filter', $cookie_expiry);

setcookie($this->namespace.'-remember_login', 'yes', $cookie_expiry);  

}


}

 /**
     * Helper function for registering and enqueueing scripts and styles.
     *
     * @name    The    ID to register with WordPress
     * @file_path        The path to the actual file
     * @is_script        Optional argument for if the incoming file_path is a JavaScript source file.
     */
    protected function load_file( $name, $file_path, $is_script = false, $deps = array(), $in_footer = true, $atts = array() ) {
        $url  = plugins_url( $file_path, __FILE__ );
        $file = plugin_dir_path( __FILE__ ) . $file_path;
        if ( file_exists( $file ) ) {
            if ( $is_script ) {
                wp_register_script( $name, $url, $deps, filemtime($file), $in_footer ); 
                wp_enqueue_script( $name );
            }
            else {
                wp_register_style( $name, $url, $deps, filemtime($file) );
                wp_enqueue_style( $name );
            } // end if
        } // end if
	  
	  if (isset($atts) and is_array($atts) and isset($is_script)){
		
		
  $atts = array_filter($atts);

if (!empty($atts)) {

  $this->script_atts[$name] = $atts; 
  
}

		  
	 add_filter( 'script_loader_tag', function ( $tag, $handle ) {
	   

	   
if (isset($this->script_atts[$handle][0]) and !empty($this->script_atts[$handle][0])){
  
$atts = $this->script_atts[$handle];

$implode = implode(" ", $atts);
  
unset($this->script_atts[$handle]);

return str_replace( ' src', ' '.$implode.' src', $tag );

unset($atts);
usent($implode);

		 

	 } else {
	   
 return $tag;	   
	   
	   
	 }
	

}, 10, 2 );
 

	
	  
	}
		
    } // end load_file


private function display_terms_form($type = "login") {



		/* Add an element to the login form, which must be checked */

		
$return_string = '';

if(isset($GLOBALS['lh_agree_to_terms-errors']) and is_wp_error( $GLOBALS['lh_agree_to_terms-errors']) ) {

$error = $GLOBALS['lh_agree_to_terms-errors'];

$return_string .= '<p>'.$error->get_error_message() .'</p>';

}


if (isset($this->options[$this->login_field_name]) and ($this->options[$this->login_field_name] == 1) and ($type == 'login')){  


$return_string .= '
<p>
<input type="checkbox" value="1" name="lh_agree_to_terms-accept" id="lh_agree_to_terms-accept" data-lh_agree_to_terms-validity_message="'.$this->get_validity_message($type).'"  required="required" ';


if (isset($_COOKIE[$this->namespace.'-remember_login']) and isset($this->options[$this->login_remember_name]) and ($this->options[$this->login_remember_name] == 1)){

$return_string .= 'checked="checked" ';

} 


$return_string .= '/> 
<label>'.$this->options[$this->login_message_name].'</label>
<input type="hidden" value="' . esc_attr( $type ) . '" name="lh_agree_to_terms-type" />
</p>
';



// include agree to terms javascript
$this->load_file( $this->namespace.'-script', '/scripts/lh-agree-to-terms.js', true, array(), true, array('defer="defer"'));


} elseif (isset($this->options[$this->registration_field_name]) and ($this->options[$this->registration_field_name] == 1) and ($type == 'register')){  

$return_string .= '<p>
<input type="checkbox" value="1" name="lh_agree_to_terms-accept" id="lh_agree_to_terms-accept" data-lh_agree_to_terms-validity_message="'.$this->get_validity_message($type).'" required="required" /> <label>'.$this->options[$this->registration_message_name].'</label><input type="hidden" value="' . esc_attr( $type ) . '" name="lh_agree_to_terms-type" />
</p>
';



// include agree to terms javascript
$this->load_file( $this->namespace.'-script', '/scripts/lh-agree-to-terms.js', true, array(), true, array('defer="defer"'));


}



return $return_string;

	}

public function registration_validation($errors, $sanitized_user_login, $user_email){  

if (isset($this->options[$this->registration_field_name]) and ($this->options[$this->registration_field_name] == 1)){


if(isset($_POST['lh_agree_to_terms-type']) && ($_POST['lh_agree_to_terms-type'] == "register") && isset($_POST['lh_agree_to_terms-accept']) && ($_POST['lh_agree_to_terms-accept'] == 1) ) {


$this->maybe_set_remember_cookie();

    } else {

$errors->add('lh_agree_to_terms', __( "Please agree to terms", $this->namespace ) );

//for custom registration solutions

$GLOBALS['lh_agree_to_terms-errors'] = $errors;



}

}

return $errors;
 
}

public function wpmu_validate_user_signup($results){  
    
    if (isset($this->options[$this->registration_field_name]) and ($this->options[$this->registration_field_name] == 1)){
        
        
if (is_user_logged_in()){
        
        
        } elseif (isset($_POST['lh_agree_to_terms-type']) && ($_POST['lh_agree_to_terms-type'] == "register") && isset($_POST['lh_agree_to_terms-accept']) && ($_POST['lh_agree_to_terms-accept'] == 1) ) {


$this->maybe_set_remember_cookie();

    } else {
  

$error = new WP_Error( 'generic', __( "Please agree to terms", $this->namespace ) );


$results['errors'] = $error;
//for custom registration solutions

$GLOBALS['lh_agree_to_terms-errors'] = $error;


}
        
        
    }
    
    
    return $results;
    
}




public function authenticate_user_acc($user = false, $password = false) {

global $wp;

if (isset($this->options[$this->login_field_name]) and ($this->options[$this->login_field_name] == 1) and (strpos($_SERVER['PHP_SELF'], 'xmlrpc') == false ) and !isset($wp->query_vars['rest_route'])){

if(isset($_POST['lh_agree_to_terms-type']) && ($_POST['lh_agree_to_terms-type'] == "login") && isset($_POST['lh_agree_to_terms-accept'])  && ($_POST['lh_agree_to_terms-accept'] == 1 )) {

$this->maybe_set_remember_cookie();

    } else{

        remove_action('authenticate', 'wp_authenticate_username_password', 20);
		$errors = new WP_Error();	
		$errors->add('lh_agree_to_terms-errors', $this->get_validity_message('login'));

//for custom login solutions

$GLOBALS['lh_agree_to_terms-errors'] = $errors;

//make the user variable an error, it will be returned

$user = $errors;




}


}

return $user;

}


public function register_terms_accept($errors) {
    
    if (!is_user_logged_in()){

			echo $this->display_terms_form('register');
			
    }

		return;
	}




public function login_terms_accept($errors){

			echo $this->display_terms_form('login');

		return;
	}

public function login_terms_accept_return($return_string){

$return_string .= $this->display_terms_form('login');

return $return_string;


}


public function lh_signing_register_terms_accept($content, $atts, $post){



$content .= '<p><input type="checkbox" value="1" name="lh_agree_to_terms-accept" id="lh_agree_to_terms-accept" data-lh_agree_to_terms-validity_message="'.$this->get_validity_message('register').'" required="required" /> <label>'.$this->options[$this->registration_message_name].'</label><input type="hidden" value="' . esc_attr( 'register' ) . '" name="lh_agree_to_terms-type" /></p>';




// include the agree to terms jaavscript
$this->load_file( $this->namespace.'-script', '/scripts/lh-agree-to-terms.js', true, array(), true, array('defer="defer"'));

return $content;




}

public function plugin_menu() {
add_options_page(__('LH Agree to Terms Options', $this->namespace ), __('Agree to terms', $this->namespace ), 'manage_options', $this->filename, array($this,"plugin_options"));

}


public function plugin_options() {

if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

   
 // See if the user has posted us some information
    // If they did, the nonce will be set

	if( isset($_POST[ $this->namespace."-backend_nonce" ]) && wp_verify_nonce($_POST[ $this->namespace."-backend_nonce" ], $this->namespace."-backend_nonce" )) {

if (isset($_POST[$this->login_field_name]) and (($_POST[$this->login_field_name] == "0") || ($_POST[$this->login_field_name] == "1"))){
$options[$this->login_field_name] = $_POST[ $this->login_field_name ];
}

if (isset($_POST[$this->login_remember_name]) and (($_POST[$this->login_remember_name] == "0") || ($_POST[$this->login_remember_name] == "1"))){
$options[$this->login_remember_name] = $_POST[ $this->login_remember_name ];
}



if (isset($_POST[ $this->login_message_name ]) && ($_POST[$this->login_message_name] != "")){

$options[ $this->login_message_name ] = wp_kses_post($_POST[ $this->login_message_name ]);

}


if (isset($_POST[ $this->login_validity_name ]) && ($_POST[$this->login_validity_name] != "")){

$options[ $this->login_validity_name ] = sanitize_text_field($_POST[ $this->login_validity_name ]);

}

if (($_POST[$this->registration_field_name] == "0") || ($_POST[$this->registration_field_name] == "1")){
$options[$this->registration_field_name] = $_POST[ $this->registration_field_name ];
}



if (isset($_POST[ $this->registration_message_name ]) && ($_POST[$this->registration_message_name] != "")){

$options[ $this->registration_message_name ] = wp_kses_post($_POST[ $this->registration_message_name ]);

}

if (isset($_POST[ $this->registration_validity_name ]) && ($_POST[$this->registration_validity_name] != "")){

$options[ $this->registration_validity_name ] = sanitize_text_field($_POST[ $this->registration_validity_name ]);

}



if (update_option( $this->opt_name, $options )){

$this->options = get_option($this->opt_name);

?>
<div class="updated"><p><strong><?php _e('LH Agree to Terms settings saved', $this->namespace  ); ?></strong></p></div>
<?php


}


}

// Now display the settings editing screen

include ('partials/option-settings.php');


}

// add a settings link next to deactive / edit
public function add_settings_link( $links, $file ) {

	if( $file == $this->filename ){
		$links[] = '<a href="'. admin_url( 'options-general.php?page=' ).$this->filename.'">Settings</a>';
	}
	return $links;
}


public function plugins_loaded(){


load_plugin_textdomain( $this->namespace, false, basename( dirname( __FILE__ ) ) . '/languages' ); 

}

    /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
    public static function get_instance(){
        if (null === self::$instance) {
            self::$instance = new self();
        }
 
        return self::$instance;
    }

public function __construct() {


		/* Initialize the plugin */
               $this->filename = plugin_basename( __FILE__ );
               $this->options = get_option($this->opt_name);

               /* Add menu */
		add_action('admin_menu', array($this, 'plugin_menu'));

	       /* Add settings link */
		add_filter('plugin_action_links', array($this,"add_settings_link"), 10, 2);

		/* Registration Validation Hooks  */
		add_filter('registration_errors', array($this, 'registration_validation'), 10, 3);
		add_filter('bp_signup_validate', array($this, 'authenticate_user_acc'), 10, 2);
		add_filter('wpmu_validate_user_signup', array($this, 'wpmu_validate_user_signup'), 10, 1);

		/* Login Validation Hooks */
		add_filter('wp_authenticate_user', array($this, 'authenticate_user_acc'), 10, 2);

		/* Output Hooks */
		add_filter('login_form', array($this, 'login_terms_accept') );




		add_filter('register_form', array($this, 'register_terms_accept'));
		add_action('signup_extra_fields', array($this, 'register_terms_accept'));
		add_action('bp_before_registration_submit_buttons', array($this, 'register_terms_accept'));
		
		
		
        add_action( 'plugins_loaded', array($this,"plugins_loaded"));
	
	
	}



}

$lh_agree_to_terms_instance = LH_Agree_to_terms_plugin::get_instance();

}

?>