<?php
/**
 * Add image masking support to some specific widgets
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Extension;

use Elementor\Widget_Base;
use Skt_Addons_Elementor\Elementor\Controls\Group_Control_Mask_Image;

defined('ABSPATH') || die();

class Image_Masking {

	public static function init() {
        add_action( 'elementor/element/image/section_image/before_section_end', [ __CLASS__, 'add_controls' ] );
        add_action( 'elementor/element/image-box/section_image/before_section_end', [ __CLASS__, 'add_controls' ] );
		add_action( 'elementor/element/skt-card/_section_image/before_section_end', [ __CLASS__, 'add_controls' ] );
        add_action( 'elementor/element/skt-infobox/_section_media/before_section_end', [ __CLASS__, 'add_controls' ] );
        add_action( 'elementor/element/skt-promo-box/_section_title/before_section_end', [ __CLASS__, 'add_controls' ] );
        add_action( 'elementor/element/skt-member/_section_info/before_section_end', [ __CLASS__, 'add_controls' ] );
	}

	/**
	 * @param Widget_Base $element
	 */
	public static function add_controls( Widget_Base $element ) {

		$args = self::widget_to_args_map( $element->get_name() );

		$element->start_injection( [
			'type' => 'control',
			'at' => $args['at'],
			'of' => $args['of'],
		] );

		$element->add_group_control(
			Group_Control_Mask_Image::get_type(),
			[
				'name' => 'image_masking',
				'selector' => '{{WRAPPER}} ' . $args['selector'],
				'condition' => $args['condition'],
			]
		);

		$element->end_injection();
	}

    /**
     * @param string $widget_name
     * @return mixed
     */
	public static function widget_to_args_map( $widget_name = '' ) {
		$map = [
			'image' => [
				'at' => 'after',
				'of' => 'image',
				'selector' => '.elementor-image, {{WRAPPER}} .elementor-widget-container',
				'condition' => []
			],
			'image-box' => [
				'at' => 'after',
				'of' => 'image',
				'selector' => '.elementor-image-box-img',
				'condition' => []
			],
			'skt-card' => [
				'at' => 'after',
				'of' => 'image',
				'selector' => '.skt-card-figure img',
				'condition' => []
			],
			'skt-infobox' => [
				'at' => 'after',
				'of' => 'image',
				'selector' => '.skt-infobox-figure.skt-infobox-figure--image',
				'condition' => [
					'type' => 'image'
				]
			],
			'skt-promo-box' => [
				'at' => 'after',
				'of' => 'image',
				'selector' => '.skt-promo-box-thumb',
				'condition' => []
			],
			'skt-member' => [
				'at' => 'before',
				'of' => 'thumbnail_size',
				'selector' => '.skt-member-figure',
				'condition' => []
			]
		];

		return $map[ $widget_name ];
	}
}

Image_Masking::init();