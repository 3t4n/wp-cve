<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class ContentViews_Block_Collapsible extends ContentViews_Block {

	function __construct() {
		$this->block_name = basename( __FILE__, '.php' );

		$this->custom_attributes = $this->custom_atts();

		parent::__construct();
	}

	function custom_atts() {
		$atts = [
			'viewType' => [
				'default' => 'collapsible',
			],
			'openFirst'	 => [
				'__key'		 => 'collapsible-open-first-item',
				'type'		 => 'boolean',
				'default'	 => true,
			],
			'openAll'	 => [
				'__key'		 => 'collapsible-open-all',
				'type'		 => 'boolean',
			],
		];

		return $atts;
	}

}

