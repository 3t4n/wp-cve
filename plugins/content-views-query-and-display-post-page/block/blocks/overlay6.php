<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

// One big above 3 others
class ContentViews_Block_Overlay6 extends ContentViews_Block {

	function __construct() {
		$this->block_name = basename( __FILE__, '.php' );

		$this->custom_attributes = $this->custom_atts();

		parent::__construct();
	}

	function custom_atts() {
		return self::ovl6_atts();
	}

	static function ovl6_atts() {
		$atts = [
			'columns'		 => [
				'default' => (object) [ 'md' => 2, 'sm' => 2, 'xs' => 1 ],
			],
			'postsPerPage' => [
				'default' => '5',
			],
			'isSpec'		 => [
				'default' => '',
			],

			'sameAs'		 => [
				'default'	 => 'overlay6',
			],
		];

		return array_replace_recursive( ContentViews_Block_Overlay2::special_overlay_atts(), $atts );
	}

}


