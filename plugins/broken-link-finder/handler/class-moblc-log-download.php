<?php
/**
 * This file has code for downloading log file.
 *
 * @package bronken-link-finder/handler
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Moblc_Log_Download' ) ) {
	/**
	 * This is log download class.
	 */
	class Moblc_Log_Download {

		/**
		 * Constructor.
		 */
		public function __construct() {
			global $moblc_dir_path;
			require_once $moblc_dir_path . DIRECTORY_SEPARATOR . 'handler' . DIRECTORY_SEPARATOR . 'class-moblcutility.php';
			add_action( 'admin_init', array( $this, 'moblc_feedback_actions' ) );
		}
		/**
		 * This function checks if request[options] is for 'log file download' it will call the download log file function.
		 *
		 * @return void
		 */
		public function moblc_feedback_actions() {
			if ( current_user_can( 'manage_options' ) && isset( $_POST['option'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce is verified in functions seprately
				switch ( ( isset( $_REQUEST['option'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['option'] ) ) : '' ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- NonceVerification.Missing -- Nonce is verified in functions seprately
					case 'log_file_download':
						$this->moblc_download_log_file();
						break;

				}
			}
		}

		/**
		 * Function for downloading log file.
		 *
		 * @return void
		 */
		public function moblc_download_log_file() {

			$nonce = isset( $_POST['moblc_nonce_download_log'] ) ? sanitize_key( $_POST['moblc_nonce_download_log'] ) : '';

			if ( ! wp_verify_nonce( $nonce, 'moblc-nonce-download-log' ) ) {
				$error = new WP_Error();
				MOBLCUtility::moblc_debug_file( 'nonce error:' . $nonce );
				wp_send_json( $error );
			} else {
				ob_start();
				$debug_log_path = wp_upload_dir();
				$debug_log_path = $debug_log_path['basedir'];
				$file_name      = 'blc_debug_log.txt';
				$status         = file_exists( $debug_log_path . DIRECTORY_SEPARATOR . $file_name );

				if ( $status ) {
					header( 'Pragma: public' );
					header( 'Expires: 10' );
					header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
					header( 'Content-Type: application/octet-stream' );
					header( 'Content-Disposition: attachment; filename="' . $file_name . '"' );
					header( 'Content-Transfer-Encoding: binary' );
					header( 'Content-Length: ' . filesize( $debug_log_path . DIRECTORY_SEPARATOR . $file_name ) );
					while ( ob_get_level() ) {
						ob_end_clean();
						readfile( $debug_log_path . DIRECTORY_SEPARATOR . $file_name );//phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_readfile -- readfile can be used here.
						MOBLCUtility::moblc_debug_file( 'Downloading debug file:' );
						exit;
					}
				} else {
					do_action( 'moblc_show_message', 'File does not exist.', 'ERROR' );
				}
			}
		}
	}
	new Moblc_Log_Download();
}

