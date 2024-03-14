<?php
/**
 * Class: LaStudioKit_Image_Compare
 * Name: Image Compare
 * Slug: lakit-image-compare
 */

namespace Elementor;

use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class LaStudioKit_Image_Compare extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    wp_register_script( $this->get_name(), lastudio_kit()->plugin_url('assets/js/addons/image-compare.min.js'), [], lastudio_kit()->get_version(), true);
		    $this->add_script_depends( $this->get_name() );
		    if(!lastudio_kit()->is_optimized_css_mode()) {
			    wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/image-compare.min.css' ), [], lastudio_kit()->get_version() );
			    $this->add_style_depends( $this->get_name() );
		    }
	    }
    }

	public function get_widget_css_config($widget_name){
		$file_url = lastudio_kit()->plugin_url(  'assets/css/addons/image-compare.min.css' );
		$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/image-compare.min.css' );
		return [
			'key' => $widget_name,
			'version' => lastudio_kit()->get_version(true),
			'file_path' => $file_path,
			'data' => [
				'file_url' => $file_url
			]
		];
	}

	public function get_name() {
		return 'lakit-image-compare';
	}

	public function get_keywords() {
		return [ 'before', 'after', 'image', 'compare' ];
	}

	public function get_widget_title() {
		return esc_html__( 'Image Compare', 'lastudio-kit' );
	}

	public function get_icon() {
		return 'eicon-image-before-after';
	}

	protected function register_controls() {

		$this->_start_controls_section(
			'section_images',
			array(
				'label' => esc_html__( 'Images', 'lastudio-kit' ),
			)
		);

		$this->_add_control(
			'image_before',
			array(
				'label'   => esc_html__( 'Before image', 'lastudio-kit' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'dynamic' => array( 'active' => true ),
			)
		);


		$this->_add_control(
			'image_after',
			array(
				'label'   => esc_html__( 'Before image', 'lastudio-kit' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_labels',
			array(
				'label' => esc_html__( 'Labels', 'lastudio-kit' ),
			)
		);

		$this->_add_control(
			'show_label',
			[
				'label' => __( 'Show Labels?', 'lastudio-kit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'lastudio-kit' ),
				'label_off' => __( 'Hide', 'lastudio-kit' ),
				'default' => '',
			]
		);

		$this->_add_control(
			'before_title',
			array(
				'label'   => esc_html__( 'Before label', 'lastudio-kit' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'condition' => [
					'show_label' => 'yes',
				],
			)
		);

		$this->_add_control(
			'after_title',
			array(
				'label'   => esc_html__( 'After label', 'lastudio-kit' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'condition' => [
					'show_label' => 'yes',
				],
			)
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'lastudio-kit' ),
			)
		);
		$this->_add_control(
			'image_size',
			array(
				'type'       => 'select',
				'label'      => esc_html__( 'Image Size', 'lastudio-kit' ),
				'default'    => 'full',
				'options'    => lastudio_kit_helper()->get_image_sizes(),
			)
		);
		$this->_add_control(
			'compare_mode',
			array(
				'type'       => 'select',
				'label'      => esc_html__( 'Mode', 'lastudio-kit' ),
				'default'    => 'horizontal',
				'options'    => [
					'horizontal' => esc_html__( 'Horizontal', 'lastudio-kit' ),
					'vertical' => esc_html__( 'Vertical', 'lastudio-kit' ),
				],
			)
		);

		$this->_add_control(
			'starting_point',
			array(
				'label' => esc_html__( 'Starting Point', 'lastudio-kit' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				),
				'size_units' => ['%'],
				'default' => [
					'size' => 50,
					'unit' => '%'
				],
			)
		);
		$this->_add_control(
			'hover_start',
			[
				'label' => __( 'Start on hover', 'lastudio-kit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'lastudio-kit' ),
				'label_off' => __( 'No', 'lastudio-kit' ),
				'default' => '',
			]
		);

		$this->_end_controls_section();

		$css_scheme = apply_filters(
			'lastudio-kit/banner/css-schema',
			array(
				'banner'         => '.lakit-banner',
				'banner_content' => '.lakit-banner__content',
				'banner_overlay' => '.lakit-banner__overlay',
				'banner_title'   => '.lakit-banner__title',
				'banner_text'    => '.lakit-banner__text',
			)
		);

		$this->_start_controls_section(
			'section_style_general',
			array(
				'label'      => esc_html__( 'General', 'lastudio-kit' ),
				'tab'        => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'control_color',
			[
				'label' => __( 'Control Color', 'lastudio-kit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}}' => '--icv--control-color: {{VALUE}}',
				],
			]
		);
		$this->_add_control(
			'control_type',
			array(
				'type'       => 'select',
				'label'      => esc_html__( 'Control Type', 'lastudio-kit' ),
				'default'    => 'arrow',
				'options'    => [
					'arrow' => esc_html__( 'Arrow', 'lastudio-kit' ),
					'triangle' => esc_html__( 'Triangle', 'lastudio-kit' ),
				],
			)
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_style_images',
			array(
				'label'      => esc_html__( 'Images', 'lastudio-kit' ),
				'tab'        => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'enable_custom_image_height',
			array(
				'label'        => esc_html__( 'Enable Custom Image Height', 'lastudio-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
				'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
				'return_value' => 'true',
				'default'      => '',
				'prefix_class' => 'enable-c-height-',
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label' => esc_html__( 'Image Height', 'lastudio-kit' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
					'%' => [
						'min' => 0,
						'max' => 200,
					],
					'vh' => array(
						'min' => 0,
						'max' => 100,
					)
				),
				'size_units' => ['px', '%', 'vh', 'custom'],
				'default' => [
					'size' => 300,
					'unit' => 'px'
				],
				'selectors' => array(
					'{{WRAPPER}} .lakit-image-compare:before' => 'padding-bottom: {{SIZE}}{{UNIT}};'
				),
				'condition' => [
					'enable_custom_image_height!' => ''
				]
			)
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_style_labels',
			array(
				'label'      => esc_html__( 'Labels', 'lastudio-kit' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_label' => 'yes',
				],
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'label' => __( 'Typography', 'lastudio-kit' ),
				'selector' => '{{WRAPPER}} .lakit-icv__label',
			)
		);
		$this->add_control(
			'label_color',
			[
				'label' => __( 'Label Color', 'lastudio-kit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .lakit-icv__label' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'label_bg',
				'selector' => '{{WRAPPER}} .lakit-icv__label',
				'types' => [ 'classic', 'gradient'],
				'exclude' => [ 'image' ],
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'label_border',
				'label'       => esc_html__( 'Border', 'lastudio-kit' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .lakit-icv__label'
			)
		);

		$this->add_responsive_control(
			'label_radius',
			array(
				'label'      => __( 'Border Radius', 'lastudio-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .lakit-icv__label'=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'label_padding',
			array(
				'label'      => __( 'Padding', 'lastudio-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .lakit-icv__label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->_add_control(
			'label___before',
			array(
				'label' => esc_html__( 'Label before position', 'lastudio-kit' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->_add_control(
			'label1_pos_horizontal',
			[
				'label' => esc_html__( 'Horizontal Orientation', 'lastudio-kit' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => is_rtl() ? 'right' : 'left',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'lastudio-kit' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'lastudio-kit' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'toggle' => false
			]
		);

		$this->add_responsive_control(
			'label1_pos_offset_x',
			[
				'label' => esc_html__( 'Offset', 'lastudio-kit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .lakit-icv__label-before' => '{{label1_pos_horizontal.VALUE}}: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->_add_control(
			'label1_pos_vertical',
			[
				'label' => esc_html__( 'Vertical Orientation', 'lastudio-kit' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'lastudio-kit' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'top',
				'toggle' => false
			]
		);

		$this->add_responsive_control(
			'label1_pos_offset_y',
			[
				'label' => esc_html__( 'Offset', 'lastudio-kit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .lakit-icv__label-before' => '{{label1_pos_vertical.VALUE}}: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->_add_control(
			'label___after',
			array(
				'label' => esc_html__( 'Label after position', 'lastudio-kit' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->_add_control(
			'label2_pos_horizontal',
			[
				'label' => esc_html__( 'Horizontal Orientation', 'lastudio-kit' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => is_rtl() ? 'left' : 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'lastudio-kit' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'lastudio-kit' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'toggle' => false
			]
		);

		$this->add_responsive_control(
			'label2_pos_offset_x',
			[
				'label' => esc_html__( 'Offset', 'lastudio-kit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .lakit-icv__label-after' => '{{label2_pos_horizontal.VALUE}}: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->_add_control(
			'label2_pos_vertical',
			[
				'label' => esc_html__( 'Vertical Orientation', 'lastudio-kit' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'lastudio-kit' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'top',
				'toggle' => false
			]
		);

		$this->add_responsive_control(
			'label2_pos_offset_y',
			[
				'label' => esc_html__( 'Offset', 'lastudio-kit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .lakit-icv__label-after' => '{{label2_pos_vertical.VALUE}}: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->_end_controls_section();
	}

	protected function render() {

		$this->_context = 'render';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

	public function _get_image_before() {

		$image = $this->get_settings_for_display( 'image_before' );

		if ( empty( $image['id'] ) && empty( $image['url'] ) ) {
			return;
		}

		$format = apply_filters( 'lastudio-kit/image-compare/image-format', '<img src="%1$s" alt="%2$s" class="lakit-icv__img">' );

		if ( empty( $image['id'] ) ) {
			return sprintf( $format, $image['url'], '' );
		}

		$size = $this->get_settings_for_display( 'image_size' );

		if ( ! $size ) {
			$size = 'full';
		}

		$image_url = wp_get_attachment_image_url( $image['id'], $size );
		$alt       = esc_attr( Control_Media::get_image_alt( $image ) );

		return sprintf( $format, $image_url, $alt );
	}

	public function _get_image_after() {

		$image = $this->get_settings_for_display( 'image_after' );

		if ( empty( $image['id'] ) && empty( $image['url'] ) ) {
			return;
		}

		$format = apply_filters( 'lastudio-kit/image-compare/image-format', '<img src="%1$s" alt="%2$s" class="lakit-icv__img">' );

		if ( empty( $image['id'] ) ) {
			return sprintf( $format, $image['url'], '' );
		}

		$size = $this->get_settings_for_display( 'image_size' );

		if ( ! $size ) {
			$size = 'full';
		}

		$image_url = wp_get_attachment_image_url( $image['id'], $size );
		$alt       = esc_attr( Control_Media::get_image_alt( $image ) );

		return sprintf( $format, $image_url, $alt );
	}

	public function _get_js_settings(){
		$starting_point = $this->get_settings_for_display('starting_point');
		return json_encode([
			'startingPoint' => !empty($starting_point['size']) ? absint($starting_point['size']) : 50,
			'verticalMode'  => $this->get_settings_for_display('compare_mode') === 'vertical',
			'hoverStart'    => filter_var( $this->get_settings_for_display('hover_start'), FILTER_VALIDATE_BOOLEAN ),
			'showLabels'    => filter_var( $this->get_settings_for_display('show_label'), FILTER_VALIDATE_BOOLEAN ),
			'labelOptions' => [
				'before' => $this->get_settings_for_display('before_title'),
				'after' => $this->get_settings_for_display('after_title'),
			],
			'controlType' => $this->get_settings_for_display('control_type')
		]);
	}

}
