<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

// 5 posts.  One big | 4 others
class ContentViews_Block_Overlay5 extends ContentViews_Block {

	function __construct() {
		$this->block_name = basename( __FILE__, '.php' );

		$this->custom_attributes = $this->custom_atts();

		parent::__construct();
	}

	function custom_atts() {
		$atts = [
			'columns'		 => [
				'default' => (object) [ 'md' => 4, 'sm' => 4, 'xs' => 1 ],
			],
			'postsPerPage' => [
				'default' => '5',
			],
		];

		return array_replace_recursive( ContentViews_Block_Overlay2::special_overlay_atts(), $atts );
	}

}


