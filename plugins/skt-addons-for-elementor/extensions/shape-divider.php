<?php
namespace Skt_Addons_Elementor\Elementor\Extension;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Shapes;

defined( 'ABSPATH' ) || die();

class Shape_Divider {

	public static function init() {
		add_filter( 'elementor/shapes/additional_shapes', [__CLASS__, 'additional_shape_divider'] );
		add_action( 'elementor/element/section/section_shape_divider/before_section_end', [__CLASS__, 'update_shape_list'] );
	}

	public static function update_shape_list( Element_Base $element ) {
		$default_shapes = [];
		$skt_addons_elementor_shapes_top = [];
		$skt_addons_elementor_shapes_bottom = [];

		foreach ( Shapes::get_shapes() as $shape_name => $shape_props ) {
			if ( ! isset( $shape_props['skt_addons_elementor_shape'] ) ) {
				$default_shapes[ $shape_name ] = $shape_props['title'];
			} elseif ( ! isset( $shape_props['skt_addons_elementor_shape_bottom'] ) ){
				$skt_addons_elementor_shapes_top[ $shape_name ] = $shape_props['title'];
			} else {
				$skt_addons_elementor_shapes_bottom[ $shape_name ] = $shape_props['title'];
			}
		}

		$element->update_control(
			'shape_divider_top',
			[
				'type' => Controls_Manager::SELECT,
				'groups' => [
					[
						'label' => __( 'Disable', 'skt-addons-elementor' ),
						'options' => [
							'' => __( 'None', 'skt-addons-elementor' ),
						],
					],
					[
						'label' => __( 'Default Shapes', 'skt-addons-elementor' ),
						'options' => $default_shapes,
					],
					[
						'label' => __( 'SKT Shapes', 'skt-addons-elementor' ),
						'options' => $skt_addons_elementor_shapes_top,
					],
				],
			]
		);

		$element->update_control(
			'shape_divider_bottom',
			[
				'type' => Controls_Manager::SELECT,
				'groups' => [
					[
						'label' => __( 'Disable', 'skt-addons-elementor' ),
						'options' => [
							'' => __( 'None', 'skt-addons-elementor' ),
						],
					],
					[
						'label' => __( 'Default Shapes', 'skt-addons-elementor' ),
						'options' => $default_shapes,
					],
					[
						'label' => __( 'SKT Shapes', 'skt-addons-elementor' ),
						'options' => array_merge( $skt_addons_elementor_shapes_top, $skt_addons_elementor_shapes_bottom ),
					],
				],
			]
		);
	}

	/**
	 * Undocumented function
	 *
	 * @param array $shape_list
	 * @return void
	 */
	public static function additional_shape_divider( $shape_list ) {
		$skt_addons_elementor_shapes = [
			'abstract-web' => [
				'title' => _x( 'Abstract Web', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/abstract-web.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/abstract-web.svg',
				'has_flip' => true,
				'has_negative' => false,
				'skt_addons_elementor_shape' => true,
			],
			'crossline' => [
				'title' => _x( 'Crossline', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/crossline.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/crossline.svg',
				'has_flip' => true,
				'has_negative' => false,
				'skt_addons_elementor_shape' => true,
			],
			'droplet' => [
				'title' => _x( 'Droplet', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/droplet.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/droplet.svg',
				'has_flip' => true,
				'has_negative' => true,
				'skt_addons_elementor_shape' => true,
			],
			'flame' => [
				'title' => _x( 'Flame', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/flame.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/flame.svg',
				'has_flip' => true,
				'has_negative' => false,
				'skt_addons_elementor_shape' => true,
			],
			'frame' => [
				'title' => _x( 'Frame', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/frame.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/frame.svg',
				'has_flip' => true,
				'has_negative' => true,
				'skt_addons_elementor_shape' => true,
			],
			'half-circle' => [
				'title' => _x( 'Half Circle', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/half-circle.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/half-circle.svg',
				'has_flip' => true,
				'has_negative' => true,
				'skt_addons_elementor_shape' => true,
			],
			'multi-cloud' => [
				'title' => _x( 'Multi Cloud', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/multi-cloud.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/multi-cloud.svg',
				'has_flip' => true,
				'has_negative' => false,
				'skt_addons_elementor_shape' => true,
			],
			'multi-wave' => [
				'title' => _x( 'Multi Wave', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/multi-wave.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/multi-wave.svg',
				'has_flip' => true,
				'has_negative' => false,
				'skt_addons_elementor_shape' => true,
			],
			'smooth-zigzag' => [
				'title' => _x( 'Smooth Zigzag', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/smooth-zigzag.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/smooth-zigzag.svg',
				'has_flip' => true,
				'has_negative' => false,
				'skt_addons_elementor_shape' => true,
			],
			'splash' => [
				'title' => _x( 'Splash', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/splash.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/splash.svg',
				'has_flip' => true,
				'has_negative' => true,
				'skt_addons_elementor_shape' => true,
			],
			'splash2' => [
				'title' => _x( 'Splash 2', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/splash2.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/splash2.svg',
				'has_flip' => true,
				'has_negative' => true,
				'skt_addons_elementor_shape' => true,
			],
			'torn-paper' => [
				'title' => _x( 'Torn Paper', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/torn-paper.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/torn-paper.svg',
				'has_flip' => true,
				'has_negative' => false,
				'skt_addons_elementor_shape' => true,
			],
			'brush' => [
				'title' => _x( 'Brush', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/brush.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/brush.svg',
				'has_flip' => true,
				'has_negative' => true,
				'skt_addons_elementor_shape' => true,
			],
			'sports' => [
				'title' => _x( 'Sports', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/sports.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/sports.svg',
				'has_flip' => true,
				'has_negative' => false,
				'skt_addons_elementor_shape' => true,
				'skt_addons_elementor_shape_bottom' => true,
			],
			'landscape' => [
				'title' => _x( 'Landscape', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/landscape.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/landscape.svg',
				'has_flip' => true,
				'has_negative' => false,
				'skt_addons_elementor_shape' => true,
				'skt_addons_elementor_shape_bottom' => true,
			],
			'nature' => [
				'title' => _x( 'Nature', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/nature.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/nature.svg',
				'has_flip' => true,
				'has_negative' => false,
				'skt_addons_elementor_shape' => true,
				'skt_addons_elementor_shape_bottom' => true,
			],
			'desert' => [
				'title' => _x( 'Desert', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/desert.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/desert.svg',
				'has_flip' => true,
				'has_negative' => false,
				'skt_addons_elementor_shape' => true,
				'skt_addons_elementor_shape_bottom' => true,
			],
			'under-water' => [
				'title' => _x( 'Under Water', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/under-water.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/under-water.svg',
				'has_flip' => true,
				'has_negative' => false,
				'skt_addons_elementor_shape' => true,
				'skt_addons_elementor_shape_bottom' => true,
			],
			'cityscape-layer' => [
				'title' => _x( 'Cityscape Layer', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/cityscape-layer.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/cityscape-layer.svg',
				'has_flip' => true,
				'has_negative' => false,
				'skt_addons_elementor_shape' => true,
				'skt_addons_elementor_shape_bottom' => true,
			],
			'drop' => [
				'title' => _x( 'Drop', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/drop.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/drop.svg',
				'has_flip' => true,
				'has_negative' => false,
				'skt_addons_elementor_shape' => true,
			],
			'mosque' => [
				'title' => _x( 'Mosque', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/mosque.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/mosque.svg',
				'has_flip' => true,
				'has_negative' => false,
				'skt_addons_elementor_shape' => true,
			],
			'christmas' => [
				'title' => _x( 'Christmas', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/christmas.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/christmas.svg',
				'has_flip' => true,
				'has_negative' => true,
				'skt_addons_elementor_shape' => true,
			],
			'christmas2' => [
				'title' => _x( 'Christmas 2', 'Shapes', 'skt-addons-elementor' ),
				'path' => SKT_ADDONS_ELEMENTOR_DIR_PATH . 'assets/imgs/shape-divider/christmas2.svg',
				'url' => SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/shape-divider/christmas2.svg',
				'has_flip' => true,
				'has_negative' => true,
				'skt_addons_elementor_shape' => true,
			]
		];

		/*
		 * svg path should contain elementor class to show in editor mode
		*/
		return array_merge( $skt_addons_elementor_shapes, $shape_list );
	}
}

Shape_Divider::init();