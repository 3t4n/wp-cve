<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class ContentViews_Block_List1 extends ContentViews_Block {

	function __construct() {
		$this->block_name = basename( __FILE__, '.php' );
		$this->title			 = 'List';
		$this->custom_attributes = $this->custom_atts();

		parent::__construct();
	}

	function custom_atts() {
		$atts = [
			'layoutFormat' => [
				'default' => '2-col',
			],
			'columns'		 => [
				'default' => (object) [ 'md' => 1, 'sm' => 1, 'xs' => 1 ],
			],
			'formatWrap'	 => [
				'__key'		 => 'lf-nowrap',
				'type'		 => 'string',
				'default'	 => 'yes',
			],
			'zigzag'				 => [
				'__key'		 => 'lf-alternate',
				'type'		 => 'string',
				'default'	 => '',
			],
			'thumbPosition'			 => [
				'__key'		 => 'field-thumbnail-position',
				'type'		 => 'string',
				'default'	 => 'left',
			],
			'thumbnailMaxWidth'		 => [
				'default' => (object) [ 'md' => '40' ],
			],
			'thumbnailMaxWidthUnits'	 => [
				'default' => (object) [ 'md' => '%' ],
			],
			'thumbnailHeight'		 => [
				'default' => (object) [ 'md' => '300' ],
			],
			'titlefSize'		 => [
				'type'		 => 'object',
				'default' => (object) [ 'md' => 22 ],
			],
		];


		return $atts;
	}

}

