<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}
// layout4: 1-col for small items
class ContentViews_Block_OneBig2 extends ContentViews_Block {

	function __construct() {
		$this->block_name = basename( __FILE__, '.php' );
		$this->title			 = 'Big Post 2';
		$this->custom_attributes = $this->custom_atts();

		parent::__construct();
	}

	function custom_atts() {
		$atts = [
			'onePosition'			 => [
				'default' => 'beside-others',
			],
			'oneWidth'				 => [
				'__key'		 => '__SAME__',
				'type'		 => 'string',
				'default'	 => '50%',
			],
			'swapPosition'				 => [
				'__key'		 => '__SAME__',
				'type'		 => 'boolean',
				'default'	 => false,
			],
			'showThumbnailOthers'	 => [
				'default' => false,
			],
			'showTaxonomyOthers'	 => [
				'default' => false,
			],
			'showContentOthers'		 => [
				'default' => false,
			],
			'showReadmoreOthers'	 => [
				'default' => false,
			],
			'thumbnailsmMaxWidth'		 => [
				'default'	 => (object) [ 'md' => '100' ],
			],
			'thumbnailsmMaxWidthUnits'	 => [
				'default'	 => (object) [ 'md' => 'px' ],
			],
		];


		return array_replace_recursive( ContentViews_Block_OneBig1::onebig_atts(), $atts );
	}

	static function one_width() {
		return array( '33%' => '1/3', '50%' => '1/2', '66%' => '2/3' );
    }

}

