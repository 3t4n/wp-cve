<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

// 3-5 posts. One big on left.
class ContentViews_Block_Overlay2 extends ContentViews_Block {

	function __construct() {
		$this->block_name = basename( __FILE__, '.php' );

		$this->custom_attributes = $this->custom_atts();

		parent::__construct();
	}

	function custom_atts() {
		return self::special_overlay_atts();
	}

	static function special_overlay_atts() {
		$atts = [
			'columns'		 => [
				'default' => (object) [ 'md' => 2, 'sm' => 2, 'xs' => 1 ],
			],
			'postsPerPage' => [
				'default' => '3',
			],
			'thumbnailsmMaxWidth'	 => [
				'default' => '',
			],
			'thumbnailsmMaxWidthUnits'	 => [
				'default' => '',
			],
			'thumbnailHeight'			 => [
				'default' => '',
			],
			'formatWrap'			 => [
				'default' => '',
			],
			'isSpec'					 => [
				'__key'		 => '__SAME__',
				'type'		 => 'string',
				'default'	 => '1',
			],

			'thumbnailHeight'		 => [
				'default' => (object) [ 'md' => '350' ],
			],
			'thumbnailsmHeight'		 => [
				'default' => (object) [ 'md' => '175' ],
			],

			'titlesmfSize'		 => [
				'type'		 => 'object',
				'default' => (object) [ 'md' => 20 ],
			],
		];

		return array_replace_recursive( ContentViews_Block_OneBig1::onebig_atts(), ContentViews_Block_Overlay1::overlay_atts(), $atts );
	}

}


