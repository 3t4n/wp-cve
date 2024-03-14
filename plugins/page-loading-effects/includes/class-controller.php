<?php

/**
 * Cookie Management
 * - using WP Post Meta
 *
 * @todo Replace only if your creating your own Plugin
 * @todo PLE - Find all and replace text
 *
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Controller Class
 * Handling database query
 *
 * @since 1.0.0
 */
class PLE_Controller {

  /**
  * Sets a cookie __ple-session if it doesn't exist already.
  * @return string $out Cookie value
  */
  private function generate_cookie($coo_key, $coo_val, $ssl_cookie  = false, $expire = 30) {
    $timestamp = time() + 3600 * 24 * (int) $expire;
    // Return existing cookie if exist
   	if( isset($_COOKIE[$coo_key]) ) return;
    // Or create new
    $parse_url = wp_parse_url(home_url());
    $cookie_domain = '';
    if( isset($parse_url['host']) ) $cookie_domain = $parse_url['host'];
    $cookie_path = '/';
    if(setcookie( $coo_key, $coo_val, $timestamp, $cookie_path, $cookie_domain, $ssl_cookie, true )) return;
    return false;

  }
  /**
   * Init Cookie
   * @return bool|string
   */
  public function cookie_kicks($coo_key, $expire) {
    // Bail out
    if ( is_admin() ) return false;
    $out = false;
     // Front-end cookie is secure when the auth cookie is secure and the site's home URL is forced HTTPS.
    $ssl_cookie = is_ssl() && 'https' === parse_url( get_option( 'home' ), PHP_URL_SCHEME );
    $coo_val = wp_generate_password(12);
    PLE_Controller::generate_cookie($coo_key, $coo_val, $ssl_cookie, $expire);
    return;
  }
  /**
   * Remove cookie
   * @return null
   */
  public function remove_cookie($coo_key){
  	if(isset($_COOKIE[$coo_key])) {
    	unset($_COOKIE[$coo_key]);
    	setcookie($coo_key, '', time() - 3600, '/'); // empty value and old timestamp
    	return;
	}
	return;
  }

   /**
   * Getting values WP Options (Database)
   *
   * To display its value, <?php echo RRH()->db->get_option('','rrh_options', 0) ?>
   *
   * @access public
   * @since 1.0.0
   * @param string $key_option It's a get_option key
   * @param int $index Position of indexes
   * @param int|string $default_value Default value when empty
   * @return array $output 
   *  var_dump(PLE()->ctrl->get_option('rrh_options', 0, ''));
   */
	public function get_option($key_option = NULL, $default_value = '', $index = NULL){
    	$output = false;
	    if(empty($key_option)) return $output;
	    // check if it has default value
	    if(!empty($default_value)){
	    	$output = $default_value;
	    }
	    // Just get value from database when no indexes
	    if($index === NULL && !empty( get_option($key_option) )){
	    	$output = get_option($key_option);
	    	return $output;
	    }
	    // If there is indexes
	    if($index !== NULL && !empty( get_option($key_option)[$index] )){
	      $output = get_option($key_option)[$index];
	    }
	    return $output;
  	}

}
