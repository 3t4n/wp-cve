<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

// 4 posts.  One on left >< 2 small above one big
class ContentViews_Block_Overlay3 extends ContentViews_Block {

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
				'default' => '4',
			],
		];

		return array_replace_recursive( ContentViews_Block_Overlay2::special_overlay_atts(), $atts );
	}

}


