<?php
/**
 * Core class.
 *
 * @package 	Leverage Browser Caching
 */

if ( ! class_exists( 'Lbrowserc_Core' ) ) {
	/**
	 * Core class of plugin.
	 */
	class Lbrowserc_Core {

		/**
		 * Store htaccess_file, used to store path of htaccess file.
		 *
		 * @var string
		 */
		public $htaccess_file;

		/**
		 * Store unique_string, used to identify codes in htaccess file.
		 *
		 * @var string
		 */
		public $unique_string;

		/**
		 * Store htaccess_cntn, used to store htaccess file content.
		 *
		 * @var string
		 */
		public $htaccess_cntn;

		/**
		 * Store valid, use to check true false.
		 *
		 * @var bool
		 */
		public $valid;

		/**
		 * Store pattern, used to remove plugin code from htaccess file.
		 *
		 * @var string
		 */
		public $pattern;

		/**
		 * Store message, used to add admin notice etc.
		 *
		 * @var string
		 */
		public $message;

		/**
		 * Store plugin action link.
		 *
		 * @var string
		 */
		public $custom_link;

		/**
		 * This will add code, if not found. and also call deactivation_hook
		 */
		public function __construct() {

			$this->htaccess_file = wp_normalize_path( ABSPATH . '.htaccess' );

			// Go ahead, if file exist.
			if ( file_exists( $this->htaccess_file ) ) {

				// Go ahead, if file readable and writable.
				if ( is_readable( $this->htaccess_file ) && is_writable( $this->htaccess_file ) ) {

					// Check if code already present in htaccess.
					$this->unique_string 	= 'LBROWSERCSTART';
					$this->htaccess_cntn 	= file_get_contents( $this->htaccess_file );
					$this->valid 			= false;

					if ( strpos( $this->htaccess_cntn, $this->unique_string ) !== false ) {
						$this->valid = true;
					}

					if ( ! $this->valid ) {
						// Code does not have in htaccess file. let add them.
						// Present code + plugin code.
						$this->htaccess_cntn = $this->htaccess_cntn . $this->code_to_add();

						file_put_contents( $this->htaccess_file, $this->htaccess_cntn );
						// Welcome.
					}
				} else {
					add_action( 'admin_notices', array( $this, 'no_htaccess_access_notice' ) );
				}
			} else {
				add_action( 'admin_notices', array( $this, 'no_htaccess_notice' ) );
			}
			
			register_deactivation_hook( LBROWSERC_FILE, array( $this, 'remove_code' ) );

			// Add plugin action link.
			add_filter( 'plugin_action_links_' . LBROWSERC_BASE_FILE, array( $this, 'plugin_action_links' ), 10, 4 );
			
		}

		/**
		 * This will remove code from htaccess, if found.
		 */
		public function remove_code() {

			$this->htaccess_file = wp_normalize_path( ABSPATH . '.htaccess' );

			// Go ahead, if file exist.
			if ( file_exists( $this->htaccess_file ) ) {

				// Go ahead, if file readable and writable.
				if ( is_readable( $this->htaccess_file ) && is_writable( $this->htaccess_file ) ) {

					// Check if code already present.
					$this->unique_string 	= 'LBROWSERCSTART';
					$this->htaccess_cntn 	= file_get_contents( $this->htaccess_file );
					$this->valid 			= false;

					if ( strpos( $this->htaccess_cntn, $this->unique_string ) !== false ) {
						$this->valid = true;
					}

					if ( $this->valid ) {

						// Code found, remove them.
						$this->pattern 			= '/#\s?LBROWSERCSTART.*?LBROWSERCEND/s';
						$this->htaccess_cntn 	= preg_replace( $this->pattern, '', $this->htaccess_cntn );
						$this->htaccess_cntn 	= preg_replace( "/\n+/","\n", $this->htaccess_cntn );

						file_put_contents( $this->htaccess_file, $this->htaccess_cntn );
						// Bye Bye.
					}
				} else {
					// Note: no_htaccess_access_notice.
				}
			} else {
				// Note: no_htaccess_notice.
			}
		}

		/**
		 * Codes to be add.
		 */
		public function code_to_add() {
			$this->htaccess_cntn  = "\n";
			$this->htaccess_cntn .= '# LBROWSERCSTART Browser Caching' . "\n";
			$this->htaccess_cntn .= '<IfModule mod_expires.c>' . "\n";
			$this->htaccess_cntn .= 'ExpiresActive On' . "\n";
			$this->htaccess_cntn .= 'ExpiresByType image/gif "access 1 year"' . "\n";
			$this->htaccess_cntn .= 'ExpiresByType image/jpg "access 1 year"' . "\n";
			$this->htaccess_cntn .= 'ExpiresByType image/jpeg "access 1 year"' . "\n";
			$this->htaccess_cntn .= 'ExpiresByType image/png "access 1 year"' . "\n";
			$this->htaccess_cntn .= 'ExpiresByType image/x-icon "access 1 year"' . "\n";
			$this->htaccess_cntn .= 'ExpiresByType text/css "access 1 month"' . "\n";
			$this->htaccess_cntn .= 'ExpiresByType text/javascript "access 1 month"' . "\n";
			$this->htaccess_cntn .= 'ExpiresByType text/html "access 1 month"' . "\n";
			$this->htaccess_cntn .= 'ExpiresByType application/javascript "access 1 month"' . "\n";
			$this->htaccess_cntn .= 'ExpiresByType application/x-javascript "access 1 month"' . "\n";
			$this->htaccess_cntn .= 'ExpiresByType application/xhtml-xml "access 1 month"' . "\n";
			$this->htaccess_cntn .= 'ExpiresByType application/pdf "access 1 month"' . "\n";
			$this->htaccess_cntn .= 'ExpiresByType application/x-shockwave-flash "access 1 month"' . "\n";
			$this->htaccess_cntn .= 'ExpiresDefault "access 1 month"' . "\n";
			$this->htaccess_cntn .= '</IfModule>' . "\n";
			$this->htaccess_cntn .= '# END Caching LBROWSERCEND' . "\n";

			return $this->htaccess_cntn;
		}

		/**
		 * If htaccess is not exists.
		 */
		public function no_htaccess_notice() {
			$this->message = '<div class="error"><p>';
			$this->message .= __( 'Plugin Leverage Browser Caching: htaccess file not found. This plugin works only for Apache server. If you are using Apace server, please create it.', 'lbrowserc' );
			$this->message .= '</p></div>';
			echo wp_kses_post( $this->message );
		}

		/**
		 * If htaccess is not access able.
		 */
		public function no_htaccess_access_notice() {
			$this->message = '<div class="error"><p>';
			$this->message .= __( 'Plugin Leverage Browser Caching: htaccess file is not readable or writable. Please change permission of htaccess file.', 'lbrowserc' );
			$this->message .= '</p></div>';
			echo wp_kses_post( $this->message );
		}

		/**
		 * Call back for action links.
		 *
		 * @param array $actions links.
		 */
		public function plugin_action_links( $actions ) {
			$this->custom_actions = array(
				'configure' => sprintf( '<a target="_blank" href="%s">%s</a>', 'https://www.paypal.me/RinkuYadav', __( 'Donate to Author', 'lbrowserc' ) ),
				);
			return array_merge( $this->custom_actions, $actions );
		}

	}
} // End if().
