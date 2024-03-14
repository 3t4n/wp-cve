<?php

/**
 * WP Product Feed Manager Add Feed List Page Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Add_Feed_List_Page' ) ) :

	class WPPFM_Add_Feed_List_Page extends WPPFM_Admin_Page {
		private $_header_class;
		private $_feed_list_form;

		public function __construct() {
			parent::__construct();

			$this->_header_class   = new WPPFM_Main_Header();
			$this->_feed_list_form = new WPPFM_Feed_List_Page();
		}

		public function show() {
			echo $this->page_opener();
			echo $this->_header_class->show( 'feed-list-page' );
			echo $this->_feed_list_form->display();
			echo $this->page_closer();
		}
	}

	// end of WPPFM_Add_Feed_List_Page class

endif;
