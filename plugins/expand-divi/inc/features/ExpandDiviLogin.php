<?php
/**
 * Expand Divi Login
 * changes the logo and url of the wp login page 
 *
 * @package  ExpandDivi/ExpandDiviLogin
 */

// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ExpandDiviLogin {
	public $options;

	/**
	 * constructor
	 */
	function __construct() {
		$this->options = get_option( 'expand_divi' );
		add_action( 'login_enqueue_scripts', array( $this, 'expand_divi_output_login_script' ) );
	}

	/**
	 * script to change the logo and url of the wp login page 
	 *
	 * @return string
	 */
	public function expand_divi_output_login_script( $content ) {
		$url = $this->options['login_page_url']; 
		$img_url = $this->options['login_page_img_url']; 
		
		echo '<script>
			var de_php_vars = {"url":"' . $url . '", "img_url":"' . $img_url . '"};

			window.onload=function() {
				var el = document.querySelector(".login h1 a");
			el.href = de_php_vars.url;
			el.style.backgroundImage = "url(" + de_php_vars.img_url + ")";
			}
		</script>';
	}
}
new ExpandDiviLogin();