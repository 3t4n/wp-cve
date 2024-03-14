<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use XproElementorAddons\Control\Xpro_Elementor_Widget_Area;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Xpro Elementor Addons
 *
 * Elementor widget.
 *
 * @since 1.0.0
 */
class Content_Toggle extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve image widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'xpro-content-toggle';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Content Toggle', 'xpro-elementor-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'xi-content-toggle xpro-widget-label';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the image widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'xpro-widgets' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_keywords() {
		return array( 'content', 'toggle', 'toggler', 'switch' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_content-primary',
			array(
				'label' => __( 'Primary', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'primary_label',
			array(
				'label'       => __( 'Label', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'default'     => __( 'Monthly', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'primary_source',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Source', 'xpro-elementor-addons' ),
				'default' => 'editor',
				'options' => array(
					'editor'   => __( 'Editor', 'xpro-elementor-addons' ),
					'template' => __( 'Template', 'xpro-elementor-addons' ),
					'dynamic'  => __( 'Dynamic', 'xpro-elementor-addons' ),
				),
			)
		);

		$this->add_control(
			'primary_editor',
			array(
				'label'      => __( 'Content Editor', 'xpro-elementor-addons' ),
				'default'    => __( 'Your primary content here.', 'xpro-elementor-addons' ),
				'show_label' => false,
				'type'       => Controls_Manager::WYSIWYG,
				'condition'  => array(
					'primary_source' => 'editor',
				),
			)
		);

		$this->add_control(
			'primary_content',
			array(
				'label'       => esc_html__( 'Content', 'xpro-elementor-addons' ),
				'type'        => Xpro_Elementor_Widget_Area::TYPE,
				'label_block' => true,
				'condition'   => array(
					'primary_source' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'primary_template',
			array(
				'label'         => __( 'Template', 'xpro-elementor-addons' ),
				'placeholder'   => __( 'Select a section template for as tab content', 'xpro-elementor-addons' ),
				'description'   => sprintf(
				/* translators: %s: HTML */
					__( 'Wondering what is section template or need to create one? Please click %1$shere%2$s ', 'xpro-elementor-addons' ),
					'<a target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=elementor_library&tabs_group=library&elementor_library_type=section' ) ) . '">',
					'</a>'
				),
				'type'          => Controls_Manager::SELECT2,
				'label_block'   => false,
				'show_label' => false,
				'options'       => xpro_elementor_get_section_templates(),
				'condition'     => array(
					'primary_source' => 'template',
				),
			)
		);

		$this->end_controls_section();

		//Secondary
		$this->start_controls_section(
			'section_content-secondary',
			array(
				'label' => __( 'Secondary', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'secondary_label',
			array(
				'label'       => __( 'Label', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'default'     => __( 'Yearly', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'secondary_source',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Source', 'xpro-elementor-addons' ),
				'default' => 'editor',
				'options' => array(
					'editor'   => __( 'Editor', 'xpro-elementor-addons' ),
					'template' => __( 'Template', 'xpro-elementor-addons' ),
					'dynamic'  => __( 'Dynamic', 'xpro-elementor-addons' ),
				),
			)
		);

		$this->add_control(
			'secondary_editor',
			array(
				'label'      => __( 'Content Editor', 'xpro-elementor-addons' ),
				'default'    => __( 'Your secondary content here.', 'xpro-elementor-addons' ),
				'show_label' => false,
				'type'       => Controls_Manager::WYSIWYG,
				'condition'  => array(
					'secondary_source' => 'editor',
				),
			)
		);

		$this->add_control(
			'secondary_content',
			array(
				'label'       => esc_html__( 'Content', 'xpro-elementor-addons' ),
				'type'        => Xpro_Elementor_Widget_Area::TYPE,
				'label_block' => true,
				'condition'   => array(
					'secondary_source' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'secondary_template',
			array(
				'label'         => __( 'Template', 'xpro-elementor-addons' ),
				'placeholder'   => __( 'Select a section template for as tab content', 'xpro-elementor-addons' ),
				'description'   => sprintf(
				/* translators: %s: HTML */
					__( 'Wondering what is section template or need to create one? Please click %1$shere%2$s ', 'xpro-elementor-addons' ),
					'<a target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=elementor_library&tabs_group=library&elementor_library_type=section' ) ) . '">',
					'</a>'
				),
				'type'          => Controls_Manager::SELECT2,
				'label_block'   => false,
				'show_label' => false,
				'options'       => xpro_elementor_get_section_templates(),
				'condition'     => array(
					'secondary_source' => 'template',
				),
			)
		);

		$this->add_control(
			'default_active',
			array(
				'label'        => __( 'Default Active', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		//General Style
		$this->start_controls_section(
			'section_style_general',
			array(
				'label' => __( 'Switch', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'              => esc_html__( 'Layout', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '1',
				'options'            => array(
					'1' => esc_html__( 'Style 1', 'xpro-elementor-addons' ),
					'2' => esc_html__( 'Style 2', 'xpro-elementor-addons' ),
					'3' => esc_html__( 'Style 3', 'xpro-elementor-addons' ),
					'4' => esc_html__( 'Style 4', 'xpro-elementor-addons' ),
					'5' => esc_html__( 'Style 5', 'xpro-elementor-addons' ),
					'6' => esc_html__( 'Style 6', 'xpro-elementor-addons' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'tab_horizontal_alignment',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-content-toggle-button-wrapper' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'size',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 20,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 40,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-content-toggle-button'                                             => 'height: {{SIZE}}{{UNIT}}; width: calc({{SIZE}}{{UNIT}} * 2);',
					'{{WRAPPER}} .xpro-content-toggle-button-layout-4 .xpro-content-toggle-button'        => 'height: calc({{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .xpro-content-toggle-handle'                                             => 'height: calc({{SIZE}}{{UNIT}} - 10px); width: calc({{SIZE}}{{UNIT}} - 10px);',
					'{{WRAPPER}} .xpro-content-toggle-button-layout-2 .xpro-content-toggle-button'        => 'height: {{SIZE}}{{UNIT}}; width: calc({{SIZE}}{{UNIT}} * 2);',
					'{{WRAPPER}} .xpro-content-toggle-button-layout-2 .xpro-content-toggle-handle'        => 'height: calc({{SIZE}}{{UNIT}} - 15px); width: calc({{SIZE}}{{UNIT}} - 15px);',
					'{{WRAPPER}} .xpro-content-toggle-button-wrapper.active .xpro-content-toggle-handle'  => 'left: calc({{SIZE}}{{UNIT}} + 5px);',
					'{{WRAPPER}} .xpro-content-toggle-button-layout-4.active .xpro-content-toggle-handle' => 'left: calc({{SIZE}}{{UNIT}} + 10px);',
					'{{WRAPPER}} .xpro-content-toggle-button-layout-2.active .xpro-content-toggle-handle' => 'left: calc({{SIZE}}{{UNIT}} + 2px);',
				),
				'condition'  => array(
					'layout!' => array( '6' ),
				),
			)
		);

		$this->add_responsive_control(
			'space_between',
			array(
				'label'      => __( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 25,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-content-toggle-button' => 'margin: 0 {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout!' => array( '6' ),
				),
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'      => __( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 5,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 250,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-content-toggle-button-layout-6 .xpro-content-toggle-button' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => '6',
				),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'      => __( 'Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 60,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-content-toggle-button-layout-6 .xpro-content-toggle-button' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => '6',
				),
			)
		);

		$this->start_controls_tabs( 'toggle_button_tabs' );

		$this->start_controls_tab(
			'toggle_button_normal',
			array(
				'label' => __( 'Primary', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'toggle_button_color',
			array(
				'label'     => __( 'Controller Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-content-toggle-button-wrapper .xpro-content-toggle-handle' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_button_background',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-content-toggle-button-wrapper .xpro-content-toggle-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_button_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-content-toggle-button-layout-2 .xpro-content-toggle-button' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_button_active',
			array(
				'label' => __( 'Secondary', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'toggle_button_active_color',
			array(
				'label'     => __( 'Controller Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-content-toggle-button-wrapper.active .xpro-content-toggle-handle' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_button_active_background',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-content-toggle-button-wrapper.active .xpro-content-toggle-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_button_active_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-content-toggle-button-layout-2.active .xpro-content-toggle-button' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		//Label Style
		$this->start_controls_section(
			'section_style_label',
			array(
				'label' => __( 'Label', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tab_content_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-content-toggle-before,{{WRAPPER}} .xpro-content-toggle-after,{{WRAPPER}} .xpro-content-toggle-button-layout-6 .xpro-content-toggle-button::before,{{WRAPPER}} .xpro-content-toggle-button-layout-6 .xpro-content-toggle-button::after',
			)
		);

		$this->start_controls_tabs( 'toggle_label_tabs' );

		$this->start_controls_tab(
			'toggle_label_primary',
			array(
				'label' => __( 'Primary', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'toggle_label_primary_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-content-toggle-button-wrapper.active .xpro-content-toggle-before'          => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-content-toggle-button-layout-6.active .xpro-content-toggle-button::before' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_label_primary_active_color',
			array(
				'label'     => __( 'Active Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-content-toggle-button-wrapper .xpro-content-toggle-before'          => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-content-toggle-button-layout-6 .xpro-content-toggle-button::before' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_label_secondary',
			array(
				'label' => __( 'Secondary', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'toggle_label_secondary_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-content-toggle-button-wrapper .xpro-content-toggle-after'          => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-content-toggle-button-layout-6 .xpro-content-toggle-button::after' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_label_secondary_active_color',
			array(
				'label'     => __( 'Active Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-content-toggle-button-wrapper.active .xpro-content-toggle-after'          => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-content-toggle-button-layout-6.active .xpro-content-toggle-button::after' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			array(
				'label' => __( 'Content', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'tab_content_alignment',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-toggle-content-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tab_content_typo',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-toggle-content-wrapper,{{WRAPPER}} .xpro-toggle-content-wrapper > *',
			)
		);

		$this->add_control(
			'tab_content_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-toggle-content-wrapper, {{WRAPPER}} .xpro-toggle-content-wrapper > *' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'tab_content_background',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-toggle-content-wrapper',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tab_content_box_shadow',
				'label'    => __( 'Box Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-toggle-content-wrapper',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'tab_content_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-toggle-content-wrapper',
			)
		);

		$this->add_responsive_control(
			'tab_content_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-toggle-content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tab_content_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-toggle-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tab_content_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-toggle-content-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Render image widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'content-toggle/layout/frontend.php';
	}
}
