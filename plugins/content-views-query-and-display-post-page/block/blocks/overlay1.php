<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class ContentViews_Block_Overlay1 extends ContentViews_Block {

	function __construct() {
		$this->block_name = basename( __FILE__, '.php' );

		$this->custom_attributes = $this->custom_atts();

		parent::__construct();
	}

	function custom_atts() {
		return self::overlay_atts();
	}

	static function overlay_atts() {
		$atts = [
			'viewType'				 => [
				'default' => 'overlaygrid',
			],
			'gridGap'				 => [
				'default' => (object) [ 'md' => 10 ],
			],
			'overlaid'			 => [
				'__key'		 => '__SAME__',
				'type'		 => 'boolean',
				'default'	 => true,
			],
			'overlayClickable'		 => [
				'__key'		 => '__SAME__',
				'type'		 => 'boolean',
				'default'	 => get_option( 'pt_cv_version_pro' ) ? true : false,
			],
			'overOnHover'			 => [
				'__key'		 => '__SAME__',
				'type'		 => 'boolean',
				'default'	 => false,
			],
			'overlayType'			 => [
				'__key'		 => '__SAME__',
				'type'		 => 'string',
				'default'	 => 'simple',
			],
			'overlayColor'			 => [
				'__key'		 => '__SAME__',
				'type'		 => 'string',
				'default'	 => 'rgba(0,0,0,.4)',
			],
			'overlayGradient'		 => [
				'__key'		 => '__SAME__',
				'type'		 => 'string',
				'default'	 => 'linear-gradient(to bottom, rgba(0,0,0,0), rgba(0,0,0,0.5), rgba(0,0,0,0.9))',
			],
			'overlayOpacity'		 => [
				'__key'		 => '__SAME__',
				'type'		 => 'string',
				'default'	 => '0.8',
			],
			'overlayPosition'		 => [
				'__key'		 => '__SAME__',
				'type'		 => 'string',
				'default'	 => 'middle',
			],
			'showContent'			 => [
				'default' => false,
			],
			'showMeta'				 => [
				'default' => true,
			],
			'showReadmore'			 => [
				'default' => false,
			],
			'hetargetHeight'		 => [
				'__key'		 => '__SAME__',
				'type'		 => 'object',
				'default'	 => (object) [ 'md' => '250' ],
			],
			'hetargetHeightUnits'	 => [
				'__key'		 => '__SAME__',
				'type'		 => 'object',
				'default'	 => (object) [ 'md' => 'px' ],
			],
			'taxotermMargin'		 => [
				'type'		 => 'object',
				'default' => (object) [ 'md' => [ 'bottom' => 20 ] ],
			],
		];
		// disable, because it causes white text when overlaid=false, use CSS instead
//		foreach ( self::get_fields() as $element ) {
//			if ( strpos( $element, 'thumbnail' ) === false && !in_array( $element, [ 'hetarget', 'pagination', 'taxoterm' ] ) ) {
//				$atts[ "{$element}Color" ] = [ 'default' => '#fff' ];
//			}
//		}

		return $atts;
	}

}


