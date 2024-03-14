<?php
/*
  * Plugin Name: Login with Mobile Number for WooCommerce
  * Plugin URI: 
  * Description: User registration with mobile number and login with mobile number.
  * Author: Sunarc
  * Author URI: https://www.suncartstore.com/
  * Version: 1.0.6
 */

if (!defined("ABSPATH"))
      exit;

if (!function_exists('lmnwsunarcwooc_phone_register_field')) {
	function lmnwsunarcwooc_phone_register_field() {?>
	       <p class="form-row form-row-wide">
	       <label for="reg_billing_phone"><?php _e( 'Phone', 'woocommerce' ); ?><span class="required">*</span></label>
	       <input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" maxlength="10" required="" value="<?php sanitize_text_field( $_POST['billing_phone'] ); ?>" />
	       </p>
	       <div class="clear"></div>
	       <?php
	 }
 }
 add_action( 'woocommerce_register_form_start', 'lmnwsunarcwooc_phone_register_field' );



 /**
* Phone Validating.
*/
if (!function_exists('lmnwsunarcwooc_validate_phone_field')) {
function lmnwsunarcwooc_validate_phone_field( $username, $email, $validation_errors ) {
      if ( isset( $_POST['billing_phone'] ) && empty( $_POST['billing_phone'] ) ) {
             $validation_errors->add( 'billing_phone_error', __( 'Please provide a valid phone number.', 'woocommerce' ) );
      }
      elseif( isset( $_POST['billing_phone'] ) && !empty( $_POST['billing_phone'] ) ){
      		$user = reset(
      	             get_users(
      	              array(
      	               'meta_key' => 'billing_phone',
      	               'meta_value' => sanitize_text_field( $_POST['billing_phone'] ),
      	               'number' => 1,
      	               'count_total' => false
      	              )
      	             )
      	            );
      		if ($user) {
      			$validation_errors->add( 'billing_phone_error', __( 'An account is already registered with your phone number. <a href="#" class="showlogin">Please log in.</a>', 'woocommerce' ) );
      		}
      }
      else{

      }
         return $validation_errors;
}
}
add_action( 'woocommerce_register_post', 'lmnwsunarcwooc_validate_phone_field', 10, 3 );



/**
* Save Phone field.
*/
if (!function_exists('lmnwsunarcwooc_save_phone_field')) {
function lmnwsunarcwooc_save_phone_field( $customer_id ) {
    if ( isset( $_POST['billing_phone'] ) ) {
            // Phone input filed which is used in WooCommerce
            update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
    }
}
}
add_action( 'woocommerce_created_customer', 'lmnwsunarcwooc_save_phone_field' );



/**
* Change label.
*/
add_filter( 'gettext', 'lmnwsunarcwooc_change_registration_usename_label', 10, 3 );
if (!function_exists('lmnwsunarcwooc_change_registration_usename_label')) {
function lmnwsunarcwooc_change_registration_usename_label( $translated, $text, $domain ) {
    if ( ! is_admin() && 'woocommerce' === $domain ) {
        if( $text === 'Username' ) {
            $translated = __( 'Account number', $domain );
        } elseif( $text === 'Username or email address' ) {
            $translated = __( 'Phone number or email address', $domain );
        }
        else{

        }
    }

    return $translated;
}
}


/**
* Custom authentication for login.
*/
if (isset($_POST['username']) && isset($_POST['password'])) {
	//Remove wordpress authentication
	remove_filter('authenticate', 'wp_authenticate_username_password', 20);

	add_filter('authenticate', function($user, $email, $password){

		if(preg_match("/^[0-9]{10}+$/", $_POST['username'])) {
		  $type = 'phone';
		}
		elseif(filter_var(sanitize_text_field( $_POST['username'] ), FILTER_VALIDATE_EMAIL)){
			$type = 'email';
		}
		else{
			$type = 'username';
		}


	    $user_login = sanitize_text_field( $_POST['username'] );
	    $password   = $_POST['password'];

	    //Check for empty fields
	        if(empty($user_login) || empty ($password)){        
	            //create new error object and add errors to it.
	            $error = new WP_Error();

	            if(empty($user_login)){ //No email
	                $error->add('empty_phone_email', __('<strong>ERROR</strong>: Phone / Email field is empty.'));
	            }
	            if(empty($password)){ //No password
	                $error->add('empty_password', __('<strong>ERROR</strong>: Password field is empty.'));
	            }

	            return $error;
	        }

	        //Check if user exists in WordPress database
	        if ($type == 'phone') {
	        	$user = reset(
	                     get_users(
	                      array(
	                       'meta_key' => 'billing_phone',
	                       'meta_value' => $user_login,
	                       'number' => 1,
	                       'count_total' => false
	                      )
	                     )
	                    );
	        }
	        elseif ($type == 'email') {
	        	$user = get_user_by('email',$user_login);
	        }
	        else{
	        	$user = get_user_by('login',$user_login);
	        }
	        

	        if(!$user){
	            $error = new WP_Error();
	            $error->add('invalid', __('<strong>ERROR</strong>: Either the phone or password you entered is invalid.'));
	            return $error;
	        }
	        else{ //check password
	            if(!wp_check_password($password, $user->user_pass, $user->ID)){ //bad password
	                $error = new WP_Error();
	                $error->add('invalid', __('<strong>ERROR</strong>: Either the phone or password you entered is invalid.'));
	                return $error;
	            }else{
	                return $user; //passed
	            }
	        }
	}, 20, 3);
}



/**
* Custom phone validation from my account.
*/
add_action( 'woocommerce_after_save_address_validation','lmnwsunarccustom_validation_phone',1,2 );
if (!function_exists('lmnwsunarccustom_validation_phone')) {
function lmnwsunarccustom_validation_phone( $user_id, $load_address ) {

	switch ( $load_address ) {
		case 'billing':
			if(isset($_POST['billing_phone']))
			   {
			       $account_billing_phone   = ! empty( $_POST['billing_phone'] ) ? wc_clean( $_POST['billing_phone'] ) : '';
			       $get_user                = wp_get_current_user();
			       $user_phone = get_user_meta( $get_user->ID, 'billing_phone', true );
			       if(strlen($account_billing_phone) !== 10 )
			       {
			           return wc_add_notice( __( 'Enter a valid 10 digit mobile number', 'woocommerce' ), 'error' );
			           exit();
			       }
			       elseif($account_billing_phone !== $user_phone)
			       {
			           $user_exist = get_users(array('meta_value' => array($account_billing_phone)));
			           if($user_exist)
			           {
			               wc_add_notice( __( 'Mobile number already exist.', 'woocommerce' ), 'error' );
			           }
			       }
			   }
			break;

		case 'shipping':
			// shipping info
			break;
	}
}
}


