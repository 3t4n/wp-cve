<?php

/**
 * WP Product Feed Manager Add Support Page Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Add_Support_Page' ) ) :

	class WPPFM_Add_Support_Page extends WPPFM_Admin_Page {
		private $_header_class;
		private $_support_form;

		/** @noinspection PhpVoidFunctionResultUsedInspection */
		public function __construct() {
			parent::__construct();

			// enqueue the js translation scripts
			add_option( 'wp_enqueue_scripts', WPPFM_i18n_Scripts::wppfm_support_i18n() );

			$this->_header_class = new WPPFM_Main_Header();
			$this->_support_form = new WPPFM_Support_Page();
		}

		public function show() {
			echo $this->page_opener();
			echo $this->_header_class->show( 'support-page' );
			echo $this->_support_form->display();
			echo $this->page_closer();
		}
	}

	// end of WPPFM_Add_Support_Page class

endif;
