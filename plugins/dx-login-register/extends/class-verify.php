<?php

class Dxlore_Login_Register_Verify {
	
	/**
	 * Hooks
	 */
	function __construct() {
		add_action( 'registration_errors', array( $this, 'rigister_verify' ), 10, 3 );
		add_filter( 'random_password', array( $this, 'forbidden_random_password' ) );
		add_filter( 'wp_redirect', array( $this, 'register_redirect' ) );
		add_filter( 'wp_authenticate_user', array( $this, 'login_verify' ), 10, 2 );
		add_filter( 'login_redirect', array( $this, 'login_redirect' ) );
		add_action( 'wp_footer', array( $this, 'set_current_page_cookie' ) );
		add_action( 'admin_init', array( $this, 'remove_default_password_nag' ) );
	}
	
	/**
	 * Username mininum length check
	 */
 	function username_minlen( $errors ) {
		$min = isset( $this->settings['register_name_minlen'] ) ? $this->settings['register_name_minlen'] : '';
		if( $min && $_REQUEST['user_login'] ) {
			if( $min > strlen( $_REQUEST['user_login'] ) )
				$errors->add( 'username_min', __( '<strong>ERROR</strong>: Username minimum length must not be less than ', DXLORE_PRE ) . $min . __( ' bytes', DXLORE_PRE ) );
		}
		return $errors;
	}	
	
	/**
	 * Forbidden username check
	 */
	function is_forbidden_username( $errors ) {
		$fobidden = isset( $this->settings['register_forbidden_name'] ) ? $this->settings['register_forbidden_name'] : '';
		if( $fobidden ) {
			$fobiddens = explode( ',', $fobidden );
			if( $fobiddens ) {
				foreach( $fobiddens as $fb ) {
					if( stripos( $_REQUEST['user_login'], $fb ) !== false ) {
						$errors->add( 'forbidden_name', __( '<strong>ERROR</strong>: Username is not allowed to use the field: ', DXLORE_PRE )  . $fb );
						break;
					}
				}
			}
		}
		return $errors;
	}
	
	/**
	 * Password mininum length check
	 */
 	function password_minlen( $errors ) {
		$pass = isset( $this->settings['register_password'][0] ) ? $this->settings['register_password'][0] : '';
		if( 'yes' == $pass ) {
			if( empty( $_REQUEST['user_pass'] ) ) {
				$errors->add( 'pass_empty', __( '<strong>ERROR</strong>: Please enter Password', DXLORE_PRE ) );
			} else {
				$min = isset( $this->settings['register_pass_minlen'] ) ? $this->settings['register_pass_minlen'] : '';
				if( $min ) {
					if( $min > strlen( $_REQUEST['user_pass'] ) )
						$errors->add( 'pass_min', __( '<strong>ERROR</strong>: Password minimum length must not be less than ', DXLORE_PRE ) . $min . __( ' bytes', DXLORE_PRE ) );
				}
			}
		}
		return $errors;
	}
	
	/**
	 * Password comfirm
	 */
 	function password_comfirm( $errors ) {
		$pass = isset( $this->settings['register_password'][0] ) ? $this->settings['register_password'][0] : '';
		if( 'yes' == $pass ) {
			if( $_REQUEST['user_pass'] != $_REQUEST['pass_comfirm'] ) {
				$errors->add( 'pass_comfirm', __( '<strong>ERROR</strong>: Please enter the same password in the two password fields.', DXLORE_PRE ) );
			}
		}
		return $errors;
	}
	
	/**
	 * Captcha comfirm
	 */
 	function captcha_comfirm( $errors ) {
		$captcha = isset( $this->settings['register_captcha'][0] ) ? $this->settings['register_captcha'][0] : '';
		if( 'yes' == $captcha ) {
			session_start();
			if( isset( $_SESSION['register_captcha_timeout'] ) && $_SESSION['register_captcha_timeout'] < time() ){
				$errors->add( 'captcha_timeout', __( '<strong>ERROR</strong>: Captcha has expired, please re-enter.', DXLORE_PRE ) );
			} elseif( isset( $_SESSION['register_captcha'] ) && strtolower( $_REQUEST['captcha'] ) != $_SESSION['register_captcha'] ) {
				$errors->add( 'captcha_comfirm', __( '<strong>ERROR</strong>: Please enter the correct captcha.', DXLORE_PRE ) );
			}
		}
		return $errors;
	}			
	
	/**
	 * Register extra verify
	 */	
	function rigister_verify( $errors, $sanitized_user_login, $user_email ) {
		if( isset( $_REQUEST['action'] ) && 'register' == $_REQUEST['action'] ) {
			$this->settings = get_option( DXLORE_PRE . '_settings' );
			$errors = $this->username_minlen( $errors );
			$errors = $this->is_forbidden_username( $errors );
			$errors = $this->password_minlen( $errors );
			$errors = $this->password_comfirm( $errors );
			$errors = $this->captcha_comfirm( $errors );	
		}
		
		return $errors;
	}
	
	/**
	 * Forbidden random password
	 */
	function forbidden_random_password( $pass ) {
		$enter_pass = isset( $this->settings['register_password'][0] ) ? $this->settings['register_password'][0] : '';
		if( 'yes' == $enter_pass ) {
			if( isset( $_REQUEST['user_pass'] ) && $_REQUEST['user_pass'] ) {
				$pass = $_REQUEST['user_pass'];
			}
		}
		return $pass;
	}
	
	/**
	 * Set register redirect
	 */
	function register_redirect( $location ) {
		$redirect = isset( $this->settings['register_redirect'] ) ? $this->settings['register_redirect'] : '';
		if( ! empty( $redirect ) ) {
			if( isset( $_REQUEST['action'] ) && 'register' == $_REQUEST['action'] ) {
				$user = isset( $_REQUEST['user_login'] ) ? $_REQUEST['user_login'] : '';
				$pass = isset( $_REQUEST['user_pass'] ) ? $_REQUEST['user_pass'] : '';
				if( $user && $pass ) {
					session_destroy();
					$user = wp_signon( array(
						'user_login' => $user,
						'user_password' => $pass,
						'remember' => true,
					) );
					if( ! is_wp_error( $user ) ) {
						if( 'current page' == $redirect ) {
							if( isset( $_COOKIE['dx_current_page'] ) && $_COOKIE['dx_current_page'] )
								$location = $_COOKIE['dx_current_page'];
							else
								$location = admin_url();
						}
						else
							$location = $redirect;
					}
				}
			}
		}
		return $location;
	}
	
	/**
	 * Login verify
	 */
	function login_verify( $user, $password ) {
		$settings = get_option( DXLORE_PRE . '_settings' );
		$captcha = isset( $settings['login_captcha'][0] ) ? $settings['login_captcha'][0] : '';
		if( 'yes' == $captcha ) {
			session_start();
			$errors = new WP_Error;
			if( isset( $_SESSION['register_captcha_timeout'] ) && $_SESSION['register_captcha_timeout'] < time() && isset( $_REQUEST['captcha'] ) ){
				$errors->add( 'captcha_timeout', __( '<strong>ERROR</strong>: Captcha has expired, please re-enter.', DXLORE_PRE ) );
			} elseif( isset( $_SESSION['register_captcha'] ) && isset( $_REQUEST['captcha'] ) && strtolower( $_REQUEST['captcha'] ) != $_SESSION['register_captcha'] ) {
				$errors->add( 'captcha_comfirm', __( '<strong>ERROR</strong>: Please enter the correct captcha.', DXLORE_PRE ) );
			}
			if( $errors->errors )
				return $errors;
			else
				return $user;
		}
		return $user;
	}	
	
	/**
	 * Set login redirect
	 */
	function login_redirect( $location ) {
		$settings = get_option( DXLORE_PRE . '_settings' );
		$redirect = isset( $settings['login_redirect'] ) ? $settings['login_redirect'] : ''; 
		if( ! empty( $redirect ) ) {
			if( 'current page' == $redirect ) {
				if( isset( $_COOKIE['dx_current_page'] ) && $_COOKIE['dx_current_page'] )
					$location = $_COOKIE['dx_current_page'];
			}
			else
				$location = $redirect;
		}		
		return $location;
	}
	
	/**
	 * Set current page cookie
	 */
	function set_current_page_cookie() {
		$settings = get_option( DXLORE_PRE . '_settings' );
		$login_redirect = isset( $settings['login_redirect'] ) ? $settings['login_redirect'] : '';
		$register_redirect = isset( $settings['register_redirect'] ) ? $settings['register_redirect'] : ''; 
		if( 'current page' == $login_redirect || 'current page' == $register_redirect ):
	?>
			<script type="text/javascript">
				SetCookie( 'dx_current_page', window.location.href );
				function SetCookie(name,value)
				{  
					var Days = 1;
					var exp  = new Date();
					exp.setTime(exp.getTime() + Days*24*60*60*1000);  
					document.cookie = name + "="+ escape (value) + ";path=/"; 
				} 
			</script>
	<?php
		endif;
	}
	
	/**
	 * Remove default password nag admin notices
	 */	
	function remove_default_password_nag() {
		global $pagenow;
		if ( 'profile.php' == $pagenow || ! get_user_option('default_password_nag') ) //Short circuit it.
			return;
		
		$settings = get_option( DXLORE_PRE . '_settings' );
		$pass = isset( $settings['register_password'][0] ) ? $settings['register_password'][0] : '';
		if( 'yes' == $pass ) {
			global $current_user;
			delete_user_setting( 'default_password_nag' );
			update_user_option( $current_user->ID, 'default_password_nag', false, true);
		}
	}
	
}

new Dxlore_Login_Register_Verify;