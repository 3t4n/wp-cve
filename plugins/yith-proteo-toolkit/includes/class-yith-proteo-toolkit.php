<?php
/**
 * YITH Proteo Toolkit class
 *
 * @package YITH_Proteo_tookit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'YITH_Proteo_Toolkit' ) ) {
	/**
	 * Main Proteo Toolkit class.
	 *
	 * @access private
	 */
	class YITH_Proteo_Toolkit {
		/**
		 * Constructor
		 */
		public function __construct() {

			require_once YITH_PROTEO_TOOLKIT_PATH . 'includes/class-yith-proteo-toolkit-modules.php';
		}
	}

}
new YITH_Proteo_Toolkit();
