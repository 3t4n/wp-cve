<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class ContentViews_Block_Grid1 extends ContentViews_Block {

	function __construct() {
		$this->block_name = basename( __FILE__, '.php' );
		$this->title			 = 'Grid';
		$this->custom_attributes = $this->custom_atts();

		parent::__construct();
	}

	function custom_atts() {
		$atts = [
			'columns'			 => [
				'default'	 => (object) [ 'md' => 3, 'xs' => 1 ],
			],
			'thumbnailHeight' => [
				'default' => (object) [ 'md' => '250' ],
			],
		];

		return $atts;
	}

}

