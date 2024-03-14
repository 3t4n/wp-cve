<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
use XproElementorAddons\Control\Xpro_Elementor_Group_Control_Foreground;

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
class Testimonial extends Widget_Base {

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
		return 'xpro-testimonial';
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
		return __( 'Testimonial', 'xpro-elementor-addons' );
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
		return 'xi-testimonial xpro-widget-label';
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
		return array( 'testimonial', 'rating', 'review', 'feedback' );
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
			'section_general',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'          => esc_html__( 'Layout', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '1',
				'options'        => array(
					'1'  => esc_html__( 'Style 1', 'xpro-elementor-addons' ),
					'2'  => esc_html__( 'Style 2', 'xpro-elementor-addons' ),
					'3'  => esc_html__( 'Style 3', 'xpro-elementor-addons' ),
					'4'  => esc_html__( 'Style 4', 'xpro-elementor-addons' ),
					'5'  => esc_html__( 'Style 5', 'xpro-elementor-addons' ),
					'6'  => esc_html__( 'Style 6', 'xpro-elementor-addons' ),
					'7'  => esc_html__( 'Style 7', 'xpro-elementor-addons' ),
					'8'  => esc_html__( 'Style 8', 'xpro-elementor-addons' ),
					'9'  => esc_html__( 'Style 9', 'xpro-elementor-addons' ),
					'10' => esc_html__( 'Style 10', 'xpro-elementor-addons' ),
				),
				'prefix_class'   => 'xpro-testimonial-layout-',
				'render_type'    => 'template',
				'style_transfer' => true,
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => __( 'Choose Image', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'thumbnail',
				'default'   => 'large',
				'separator' => 'none',
				'exclude'   => array( 'image' ),
			)
		);

		$this->add_control(
			'name',
			array(
				'label'       => __( 'Name', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Jhon Walker', 'xpro-elementor-addons' ),
				'label_block' => true,
				'separator'   => 'before',
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'name_link',
			array(
				'label'       => __( 'Link', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://example.com',
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'designation',
			array(
				'label'       => __( 'Designation', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Managing Director', 'xpro-elementor-addons' ),
				'label_block' => true,
				'separator'   => 'before',
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'       => __( 'Description', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'It is a long established fact that a reader will be distracted by the readable content.', 'xpro-elementor-addons' ),
				'placeholder' => __( 'Type your description here', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'show_quote',
			array(
				'label'       => __( 'Show Quote', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'   => __( 'Hide', 'xpro-elementor-addons' ),
				'default'     => 'yes',
				'condition'   => array(
					'layout!' => array( '6', '9', '10' ),
				),
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'quote_icon',
			array(
				'label'     => esc_html__( 'Icons', 'xpro-elementor-addons' ),
				'type'      => \Elementor\Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-quote-left',
					'library' => 'solid',
				),
				'condition' => array(
					'show_quote' => 'yes',
					'layout!'    => array( '6', '9', '10' ),
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'          => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::CHOOSE,
				'options'        => array(
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
				'separator'      => 'before',
				'mobile_default' => 'center',
				'prefix_class'   => 'xpro-testimonial-align-%s',
				'selectors'      => array(
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_rating',
			array(
				'label' => __( 'Rating', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'ratting_style',
			array(
				'label'          => __( 'Type', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SELECT,
				'options'        => array(
					'none' => __( 'None', 'xpro-elementor-addons' ),
					'star' => __( 'Star', 'xpro-elementor-addons' ),
					'num'  => __( 'Number', 'xpro-elementor-addons' ),
				),
				'default'        => 'star',
				'style_transfer' => true,
			)
		);

		$this->add_control(
			'ratting',
			array(
				'label'      => __( 'Rating', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
					'size' => 4,
				),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 5,
						'step' => 1,
					),
				),
				'dynamic'    => array(
					'active' => true,
				),
			)
		);

		$this->end_controls_section();

		//Styling
		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => __( 'Image', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'          => __( 'Width', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'size_units'     => array( '%', 'px', 'vw' ),
				'range'          => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .xpro-testimonial-image > img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'          => __( 'Height', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( 'px', 'vh' ),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
					'vh' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .xpro-testimonial-image > img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'object-fit',
			array(
				'label'     => __( 'Object Fit', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => array(
					'height[size]!' => '',
				),
				'options'   => array(
					''        => __( 'Default', 'xpro-elementor-addons' ),
					'fill'    => __( 'Fill', 'xpro-elementor-addons' ),
					'cover'   => __( 'Cover', 'xpro-elementor-addons' ),
					'contain' => __( 'Contain', 'xpro-elementor-addons' ),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-testimonial-image > img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab(
			'normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .xpro-testimonial-image > img',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .xpro-testimonial-image > img',
			)
		);

		$this->add_control(
			'background_hover_transition',
			array(
				'label'     => __( 'Transition Duration', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 3,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-testimonial-image > img' => 'transition-duration: {{SIZE}}s',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .xpro-testimonial-image > img',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .xpro-testimonial-image > img',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-testimonial-image > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-testimonial-image > img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Content
		$this->start_controls_section(
			'section_content_style',
			array(
				'label' => __( 'Content', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'content_background',
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'types'     => array( 'classic', 'gradient' ),
				'exclude'   => array( 'image' ),
				'selector'  => '{{WRAPPER}}.xpro-testimonial-layout-4 .xpro-testimonial-inner-wrapper,{{WRAPPER}}.xpro-testimonial-layout-5 .xpro-testimonial-inner-wrapper,{{WRAPPER}}.xpro-testimonial-layout-6 .xpro-testimonial-content,{{WRAPPER}}.xpro-testimonial-layout-8 .xpro-testimonial-content',
				'condition' => array(
					'layout' => array( '4', '5', '6', '8' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'content_border',
				'selector'  => '{{WRAPPER}}.xpro-testimonial-layout-4 .xpro-testimonial-inner-wrapper,{{WRAPPER}}.xpro-testimonial-layout-5 .xpro-testimonial-inner-wrapper,{{WRAPPER}}.xpro-testimonial-layout-6 .xpro-testimonial-content,{{WRAPPER}}.xpro-testimonial-layout-8 .xpro-testimonial-content',
				'condition' => array(
					'layout' => array( '4', '5', '6', '8' ),
				),
			)
		);

		$this->add_responsive_control(
			'content_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}}.xpro-testimonial-layout-4 .xpro-testimonial-inner-wrapper,{{WRAPPER}}.xpro-testimonial-layout-5 .xpro-testimonial-inner-wrapper,{{WRAPPER}}.xpro-testimonial-layout-6 .xpro-testimonial-content,{{WRAPPER}}.xpro-testimonial-layout-8 .xpro-testimonial-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => array( '4', '5', '6', '8' ),
				),
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}}.xpro-testimonial-layout-4 .xpro-testimonial-inner-wrapper,{{WRAPPER}}.xpro-testimonial-layout-5 .xpro-testimonial-inner-wrapper,{{WRAPPER}}.xpro-testimonial-layout-6 .xpro-testimonial-content,{{WRAPPER}}.xpro-testimonial-layout-8 .xpro-testimonial-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
				'condition'  => array(
					'layout' => array( '4', '5', '6', '8' ),
				),
			)
		);

		$this->add_control(
			'heading_name',
			array(
				'label'     => __( 'Name', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'name!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'name_typography',
				'label'     => __( 'Typography', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-testimonial-title',
				'condition' => array(
					'name!' => '',
				),
			)
		);

		$this->add_group_control(
			Xpro_Elementor_Group_Control_Foreground::get_type(),
			array(
				'name'      => 'name_color',
				'label'     => __( 'Title Color', 'xpro-elementor-addons' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .xpro-testimonial-title',
				'condition' => array(
					'name!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'name_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-testimonial-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'name!' => '',
				),
			)
		);

		$this->add_control(
			'heading_designation',
			array(
				'label'     => __( 'Designation', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'designation!' => '',
				),
			)
		);

		$this->add_control(
			'designation_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-testimonial-designation' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'designation!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'designation_typography',
				'label'     => __( 'Typography', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-testimonial-designation',
				'condition' => array(
					'designation!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'designation_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-testimonial-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'designation!' => '',
				),
			)
		);

		$this->add_control(
			'heading_description',
			array(
				'label'     => __( 'Description', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'description!' => '',
				),
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-testimonial-description' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'description!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'description_typography',
				'label'     => __( 'Typography', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-testimonial-description',
				'condition' => array(
					'description!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'description_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-testimonial-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'description!' => '',
				),
			)
		);

		$this->end_controls_section();

		//Rating
		$this->start_controls_section(
			'section_rating_style',
			array(
				'label'     => __( 'Rating', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'ratting_style!' => 'none',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rating_typography',
				'label'     => __( 'Typography', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-rating-layout-num',
				'condition' => array(
					'ratting_style' => 'num',
				),
			)
		);

		$this->add_responsive_control(
			'ratting_size',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'condition'  => array(
					'ratting_style' => 'star',
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-testimonial-rating' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'ratting_space_between',
			array(
				'label'      => __( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 10,
						'step' => 1,
					),
				),
				'condition'  => array(
					'ratting_style' => 'star',
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-testimonial-rating > i' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rating_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-testimonial-rating, {{WRAPPER}} .xpro-rating-layout-star > i' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rating_fill',
			array(
				'label'     => __( 'Filled', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-rating-layout-star > .xpro-rating-filled' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'ratting_style' => 'star',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'rating_background',
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'types'     => array( 'classic', 'gradient' ),
				'exclude'   => array( 'image' ),
				'selector'  => '{{WRAPPER}} .xpro-rating-layout-num',
				'condition' => array(
					'ratting_style' => 'num',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'rating_border',
				'selector'  => '{{WRAPPER}} .xpro-rating-layout-num',
				'separator' => 'before',
				'condition' => array(
					'ratting_style' => 'num',
				),
			)
		);

		$this->add_responsive_control(
			'rating_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-rating-layout-num' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'ratting_style' => 'num',
				),
			)
		);

		$this->add_responsive_control(
			'rating_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-rating-layout-num' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'ratting_style' => 'num',
				),
			)
		);

		$this->add_responsive_control(
			'rating_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-testimonial-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Quote
		$this->start_controls_section(
			'section_quote_style',
			array(
				'label'     => __( 'Quote', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_quote' => 'yes',
					'layout!'    => array( '6', '9', '10' ),
				),
			)
		);

		$this->add_control(
			'quote_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-testimonial-quote > i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .xpro-testimonial-quote > svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'quote_sizes',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-testimonial-quote > i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-testimonial-quote > svg' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout!' => array( '6', '9', '10' ),
				),
			)
		);

		$this->add_responsive_control(
			'quote_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-testimonial-quote' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'layout!' => array( '6', '9', '10' ),
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

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'testimonial/layout/frontend.php';
	}
}
