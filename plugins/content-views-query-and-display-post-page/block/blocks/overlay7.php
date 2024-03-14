<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}


class ContentViews_Block_Overlay7 extends ContentViews_Block {

	function __construct() {
		$this->block_name = basename( __FILE__, '.php' );

		$this->custom_attributes = $this->custom_atts();	

		parent::__construct();
	}

	function custom_atts() {
		return ContentViews_Block_Overlay6::ovl6_atts();
	}	

}


