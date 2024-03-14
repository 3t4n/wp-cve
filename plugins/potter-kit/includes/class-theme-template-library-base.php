<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Potter_Kit_Theme_Template_Library_Base' ) ) {

	/**
	 * Base Class For Potter for common functions
	 *
	 * @package Potter Kit
	 * @subpackage  Potter Kit Template Library
	 * @since 1.0.0
	 */
	class Potter_Kit_Theme_Template_Library_Base {

		/**
		 * Run Block
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function run() {

			if ( method_exists( $this, 'add_template_library' ) ) {
				add_filter( 'potter_kit_demo_lists', array( $this, 'add_template_library' ) );
			}
		}
	}
}
