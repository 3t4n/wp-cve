<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class ContentViews_Block_OneBig1 extends ContentViews_Block {

	function __construct() {
		$this->block_name = basename( __FILE__, '.php' );
		$this->title			 = 'Big Post 1';
		$this->custom_attributes = $this->custom_atts();

		parent::__construct();
	}

	function custom_atts() {
		$atts = self::onebig_atts();
		$atts[ 'imgSize' ][ 'default' ] = 'full';
		return $atts;
	}

	static function onebig_atts() {
		$atts = [
			'viewType' => [
				'default' => 'onebig',
			],
			'columns'			 => [
				'default' => (object) [ 'md' => 1, 'sm' => 1, 'xs' => 1 ],
			],
			// hide Columns option. Show others* options
			'hasOne'					 => [
				'__key'		 => '__SAME__',
				'type'		 => 'string',
				'default'	 => '1',
			],
			'onePosition'	 => [
				'__key'		 => '__SAME__',
				'type'		 => 'string',
				'default'	 => 'above-others',
			],
			'showThumbnailOthers'	 => [
				'__key'		 => 'show-field-thumbnail-Others',
				'type'		 => 'boolean',
				'default'	 => true,
			],
			'showTaxonomyOthers' => [
				'__key'		 => 'show-field-taxoterm-Others',
				'type'		 => 'boolean',
				'default'	 => true,
			],
			'showContentOthers'	 => [
				'__key'		 => 'show-field-content-Others',
				'type'		 => 'boolean',
				'default'	 => true,
			],
			'showReadmoreOthers' => [
				'__key'		 => 'show-field-readmore-Others',
				'type'		 => 'boolean',
				'default'	 => true,
			],
			'showMeta'	 => [
				'default'	 => true,
			],
			'showMetaOthers'	 => [
				'__key'		 => 'show-field-meta-fields-Others',
				'type'		 => 'boolean',
				'default'	 => true,
			],
			'metaWhichOthers'		 => [
				'__key'		 => '__SAME__',
				'type'		 => 'array',
				'default'	 => array_slice( ContentViews_Block_Common::meta_list(), 0, 2 ),
			],
			'imgSize'				 => [
				'default' => 'large',
			],
			'imgSizeOthers'			 => [
				'__key'		 => '__SAME__',
				'type'		 => 'string',
				'default'	 => self::default_img_size(),
			],
			'formatWrap'				 => [
				'__key'		 => 'lf-nowrap',
				'type'		 => 'string',
				'default'	 => 'yes',
			],
			'thumbPosition'			 => [
				'__key'		 => 'field-thumbnail-position',
				'type'		 => 'string',
				'default'	 => 'left',
			],
			'thumbPositionOthers'	 => [
				'__key'		 => '__SAME__',
				'type'		 => 'string',
				'default'	 => 'left',
			],
			'thumbnailsmMaxWidth'		 => [
				'__key'	 => '__SAME__',
				'type'		 => 'object',
				'default'	 => (object) [ 'md' => '40' ],
			],
			'thumbnailsmMaxWidthUnits'	 => [
				'__key'	 => '__SAME__',
				'type'		 => 'object',
				'default'	 => (object) [ 'md' => '%' ],
			],
			'thumbnailsmHeight'			 => [
				'__key'	 => '__SAME__',
				'type'	 => 'object',
			],
			'thumbnailsmHeightUnits'	 => [
				'__key'	 => '__SAME__',
				'type'	 => 'object',
			],
			'excerptLengthOthers'		 => [
				'__key'		 => '__SAME__',
				'type'		 => 'string',
				'default'	 => '15',
			],
			'titlefSize'		 => [
				'type'		 => 'object',
				'default' => (object) [ 'md' => 26 ],
			],
		];


		return $atts;
	}

}

