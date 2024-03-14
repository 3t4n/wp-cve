<?php

namespace Shop_Ready\extension\elewidgets\widgets\general;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;

/**
 * WooCommerce Breadcrumb
 *
 * @see https://docs.woocommerce.com/document/woocommerce_breadcrumb/
 * @author quomodosoft.com
 */
class Breadcrumb extends \Shop_Ready\extension\elewidgets\Widget_Base {


	/**
	 * Html Wrapper Class of html
	 */
	public $wrapper_class = true;

	protected function register_controls() {
		// Notice
		$this->start_controls_section(
			'notice_content_section',
			array(
				'label' => esc_html__( 'Notice', 'shopready-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'woo_ready_usage_direction_notice',
			array(
				'label'           => esc_html__( 'Important Note', 'shopready-elementor-addon' ),
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Use This Widget in WooCommerce page Template.', 'shopready-elementor-addon' ),
				'content_classes' => 'woo-ready-product-page-notice',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'editor_content_section',
			array(
				'label' => esc_html__( 'Editor Refresh', 'shopready-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_breadcrumb_content',
			array(
				'label'        => esc_html__( 'Content Refresh?', 'shopready-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'shopready-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'shopready-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'layouts_product_data_tabs_section',
			array(
				'label' => esc_html__( 'Layout', 'shopready-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => esc_html__( 'Layout', 'shopready-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					'default' => esc_html__( 'Default', 'shopready-elementor-addon' ),
					// 'wready-rating-two'   => esc_html__('Style 2','shopready-elementor-addon'),

				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'content_rating_section',
			array(
				'label' => esc_html__( 'Settings', 'shopready-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'delemeter',
			array(
				'label'       => __( 'Delimeter', 'shopready-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => ' -> ',
				'separator'   => 'none',
				'description' => 'https://dev.w3.org/html5/html-author/charref <br/> https://www.toptal.com/designers/htmlarrows/symbols/',

			)
		);

		$this->add_control(
			'home',
			array(
				'label'        => __( 'Home ?', 'shopready-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'shopready-elementor-addon' ),
				'label_off'    => __( 'Hide', 'shopready-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',

			)
		);

		$this->add_control(
			'home_label',
			array(
				'label'     => __( 'Home Text', 'shopready-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => 'Home',
				'separator' => 'none',
				'condition' => array(
					'home' => array( 'yes' ),
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Layouts
		 */

		$this->box_layout(
			array(
				'title'            => esc_html__( 'Container Wrapper', 'shopready-elementor-addon' ),
				'slug'             => 'wready_wc_breadcrumb',
				'element_name'     => '__wrapper_breadcrumb',
				'selector'         => '{{WRAPPER}} .woo-ready-wc-breadcrumb-layout > *',
				'disable_controls' => array(
					'position',
					'box-size',
				),
			)
		);

		/* Layouts End */

		$this->text_minimum_css(
			array(
				'title'            => esc_html__( 'Link', 'shopready-elementor-addon' ),
				'slug'             => 'wready_wc_breadcrumb_link',
				'element_name'     => 'wrating_product_breadcrumb_link',
				'selector'         => '{{WRAPPER}} .woo-ready-wc-breadcrumb-layout a',
				'hover_selector'   => '{{WRAPPER}} .woo-ready-wc-breadcrumb-layout a:hover',
				'disable_controls' => array(
					'display',
				),
			)
		);

		$this->text_minimum_css(
			array(
				'title'            => esc_html__( 'Text', 'shopready-elementor-addon' ),
				'slug'             => 'wready_wc_breadcrumb_thustext',
				'element_name'     => 'wrating_product_breadcrumb_text',
				'selector'         => '{{WRAPPER}} .woo-ready-wc-breadcrumb-layout > *',
				'hover_selector'   => false,
				'disable_controls' => array(
					'display',
				),
			)
		);
	}

	/**
	 * Override By elementor render method
	 *
	 * @return void
	 */
	protected function html() {
		$settings = $this->get_settings_for_display();
		$args     = array(
			'delimiter' => wp_kses_post( $settings['delemeter'] ),
			'home'      => wp_kses_post( $settings['home'] ? $settings['home_label'] : null ),
		);

		$this->add_render_attribute(
			'wrapper_style',
			array(
				'class' => array( 'woo-ready-wc-breadcrumb-layout', $settings['style'] ),
			)
		);

		echo wp_kses_post( sprintf( '<div %s>', $this->get_render_attribute_string( 'wrapper_style' ) ) );
		woocommerce_breadcrumb( $args );
		echo wp_kses_post( '</div>' );
	}
}
