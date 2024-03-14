<?php
namespace XproElementorAddons\Module;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined( 'ABSPATH' ) || die();

class Equal_Height {

	public static function init() {

		add_action('elementor/element/section/section_advanced/after_section_end', [__CLASS__, 'add_controls_section']);
		add_action('elementor/frontend/section/before_render', [__CLASS__, 'equal_height_before_render'], 10, 1);

		add_action('elementor/element/container/section_layout/after_section_end', [__CLASS__, 'add_controls_section']);
		add_action('elementor/frontend/container/before_render', [__CLASS__, 'equal_height_before_render'], 10, 1);
	}

	public static function add_controls_section( Element_Base $element ) {

		$element->start_controls_section(
			'section_xpro_elementor_equal_height',
			array(
				'label' => __( 'Equal Height', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			'xpro_elementor_equal_height_on',
			[
				'label'        => esc_html__('Enable', 'xpro-elementor-addons'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);

		$element->add_control(
			'xpro_elementor_equal_height_selector',
			array(
				'label'     => esc_html__( 'Apply To', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'widgets'    => 'Widgets',
					'custom'     => 'Selector',
				),
				'default'   => 'widgets',
				'condition' => array(
					'xpro_elementor_equal_height_on' => 'yes',
				),
			)
		);

		$element->add_control(
			'xpro_elementor_equal_height_custom_selector',
			array(
				'label'       => esc_html__( 'Selector', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '.class-name',
				'condition'   => array(
					'xpro_elementor_equal_height_on'       => 'yes',
					'xpro_elementor_equal_height_selector' => 'custom',
				),
			)
		);

		$element->end_controls_section();
	}

	public static function equal_height_before_render($section)
	{

		$settings = $section->get_settings_for_display();

		if (isset($settings['xpro_elementor_equal_height_on']) && 'yes' == $settings['xpro_elementor_equal_height_on']) {

			$height_option = $settings['xpro_elementor_equal_height_selector'];

			if($settings['xpro_elementor_equal_height_selector'] === 'custom'){
				$height_option = esc_attr($settings['xpro_elementor_equal_height_custom_selector']);
			}

			if ($height_option) {
				$section->add_render_attribute('_wrapper', 'data-xpro-equal-height', $height_option);
			}
		}
	}

}

Equal_Height::init();
