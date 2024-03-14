<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class ContentViews_Block_Pinterest extends ContentViews_Block {

	function __construct() {
		$this->block_name = basename( __FILE__, '.php' );

		$this->custom_attributes = $this->custom_atts();

		add_filter( PT_CV_PREFIX_ . 'block_editor_output', array( $this, 'filter_block_editor_output' ), 10, 2 );

		parent::__construct();
	}

	function custom_atts() {
		$atts = [
			'viewType' => [
				'default' => 'pinterest',
			],
			'pinNoBox'	 => [
				'type'		 => 'boolean',
			],
			'pinNoBd'	 => [
				'type'		 => 'boolean',
			],
		];

		return $atts;
	}


	function filter_block_editor_output( $output, $block_attributes ){

		if ( $block_attributes[ 'blockName' ] === 'pinterest' ) {
			if ( get_option( 'pt_cv_version_pro' ) ) {
				$prefix	 = '<p style="text-align: center; background: #eee; margin: 0;">' . __( 'This layout uses Javascript to render. Please view the page to see final output', 'content-views-query-and-display-post-page' ) . '</p>';
				$output	 = $prefix . $output . "<style> div.pt-cv-pinterest .pt-cv-page:not(.cvpshuffled) {opacity:1} </style>";
			} else {
				$output = ContentViews_Block_Common::upgrade_for_block( $block_attributes[ 'blockName' ], 'pinterest' );
			}
		}

		return $output;
	}

}

