<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class ContentViews_Block_Scrollable extends ContentViews_Block {

	function __construct() {
		$this->block_name = basename( __FILE__, '.php' );

		$this->custom_attributes = $this->custom_atts();

		parent::__construct();
	}

	function custom_atts() {
		$atts = [
			'viewType' => [
				'default' => 'scrollable',
			],
			'columns'		 => [
				'default' => (object) [ 'md' => 2, 'xs' => 1 ],
			],
			'thumbnailHeight'		 => [
				'default' => (object) [ 'md' => '300' ],
			],
			'thumbnailMaxWidth'		 => [
				'default' => (object) [ 'md' => '100' ],
			],
			'thumbnailMaxWidthUnits'	 => [
				'default' => (object) [ 'md' => '%' ],
			],
			'rowNum'	 => [
				'__key'		 => 'scrollable-number-rows',
				'type'		 => 'string',
				'default'	 => '1',
			],
			'slideNum'	 => [
				'__key'		 => 'scrollable-number-slides',
				'type'		 => 'string',
				'default'	 => '3',
			],
			'scrollNav'	 => [
				'__key'		 => 'scrollable-navigation',
				'type'		 => 'boolean',
				'default'	 => true,
			],
			'scrollIndi'	 => [
				'__key'		 => 'scrollable-indicator',
				'type'		 => 'boolean',
				'default'	 => true,
			],
			'scrollAuto'	 => [
				'__key'		 => 'scrollable-auto-cycle',
				'type'		 => 'boolean',
				'default'	 => false,
			],
			'scrollInterval'	 => [
				'__key'		 => 'scrollable-interval',
				'type'		 => 'string',
				'default'	 => '5',
			],
			'scrollBelow'	 => [
				'__key'		 => 'scrollable-textbelow',
				'type'		 => 'boolean',
			],
		];

		return $atts;
	}

}

