<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}


class ContentViews_Block_Overlay8 extends ContentViews_Block {

	function __construct() {
		$this->block_name = basename( __FILE__, '.php' );

		$this->custom_attributes = $this->custom_atts();

		add_filter( PT_CV_PREFIX_ . 'block_editor_output', array( $this, 'filter_block_editor_output' ), 10, 2 );

		parent::__construct();
	}

	function custom_atts() {
		return ContentViews_Block_Overlay6::ovl6_atts();
	}

	function filter_block_editor_output( $output, $block_attributes ) {

		if ( $block_attributes[ 'blockName' ] === 'overlay8' ) {
			if ( !get_option( 'pt_cv_version_pro' ) ) {
				$output = ContentViews_Block_Common::upgrade_for_block( $block_attributes[ 'blockName' ], 'overlay-8' );
			}
		}

		return $output;
	}

}


