<?php

/**
 * WP Product Feed Manager Add Channel Manager Page Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Add_Channel_Manager_Page' ) ) :

	class WPPFM_Add_Channel_Manager_Page extends WPPFM_Admin_Page {
		private $_header_class;
		private $_channel_manager_form;

		/** @noinspection PhpVoidFunctionResultUsedInspection */
		public function __construct() {
			parent::__construct();

			// enqueue the js translation scripts
			add_option( 'wp_enqueue_scripts', WPPFM_i18n_Scripts::wppfm_channel_manager_i18n() );

			$this->_header_class         = new WPPFM_Main_Header();
			$this->_channel_manager_form = new WPPFM_Channel_Manager_Page();
		}

		public function show( $updated ) {
			echo $this->page_opener();
			echo $this->_header_class->show( 'channel-manager-page' );
			echo $this->_channel_manager_form->display( $updated );
			echo $this->page_closer();
		}
	}

	// end of WPPFM_Add_Channel_Manager_Page class

endif;
