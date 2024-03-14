<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Xpro Elementor Addons
 *
 * Elementor widget.
 *
 * @since 1.0.0
 */
class Donation_Form_Grid extends Widget_Base {

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
		return 'xpro-dontation-form-grid';
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
		return __( 'Donation Form Grid', 'xpro-elementor-addons' );
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
		return 'xi-clipboard xpro-widget-label';
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
		return array( 'give', 'donation', 'form' );
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

		if ( ! class_exists( '\Give' ) ) {
			$this->add_control(
				'givewp_missing_notice',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf(
					/* translators: %s: Title */
						__( 'Looks like %1$s is missing in your site. Please click on the link below and install/activate %1$s. Make sure to refresh this page after installation or activation.', 'xpro-elementor-addons' ),
						'<a href="' . esc_url( admin_url( 'plugin-install.php?s=givewp&tab=search&type=term' ) )
						. '" target="_blank" rel="noopener">GiveWP</a>'
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
				)
			);

			$this->add_control(
				'givewp_install',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => '<a href="' . esc_url( admin_url( 'plugin-install.php?s=givewp&tab=search&type=term' ) ) . '" target="_blank" rel="noopener">Click to install or activate GiveWP Plugin</a>',
				)
			);
			$this->end_controls_section();

			return;
		}

		$this->add_control(
			'forms_source',
			array(
				'label'   => __( 'Source', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'forms' => __( 'Forms', 'xpro-elementor-addons' ),
					'cats'  => __( 'Categories', 'xpro-elementor-addons' ),
					'tags'  => __( 'Tags', 'xpro-elementor-addons' ),
				),
				'default' => 'forms',
			)
		);

		$this->add_control(
			'all_forms',
			array(
				'label'     => __( 'All Forms', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'xpro-elementor-addons' ),
				'label_off' => __( 'Hide', 'xpro-elementor-addons' ),
				'default'   => 'yes',
				'toggle'    => true,
				'condition' => array(
					'forms_source' => 'forms',
				),
			)
		);

		$this->add_control(
			'form_ids',
			array(
				'label'       => __( 'Form IDs', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Form-1, Form-2', 'xpro-elementor-addons' ),
				'condition'   => array(
					'all_forms!'   => 'yes',
					'forms_source' => 'forms',
				),
			)
		);

		$this->add_control(
			'cats',
			array(
				'label'     => __( 'Form Categories', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'forms_source' => 'cats',
				),
			)
		);

		$this->add_control(
			'tags',
			array(
				'label'     => __( 'Form Tags', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'forms_source' => 'tags',
				),
			)
		);

		$this->add_control(
			'exclude',
			array(
				'label' => __( 'Exclude', 'xpro-elementor-addons' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'     => __( 'Show Title', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'xpro-elementor-addons' ),
				'label_off' => __( 'Hide', 'xpro-elementor-addons' ),
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_excerpt',
			array(
				'label'     => __( 'Show Excerpt', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'xpro-elementor-addons' ),
				'label_off' => __( 'Hide', 'xpro-elementor-addons' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'excerpt_length',
			array(
				'label'     => __( 'Excerpt Length', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 10,
				'condition' => array(
					'show_excerpt' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_goal',
			array(
				'label'     => __( 'Show Goal', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'xpro-elementor-addons' ),
				'label_off' => __( 'Hide', 'xpro-elementor-addons' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'show_bar',
			array(
				'label'     => __( 'Show Bar', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'xpro-elementor-addons' ),
				'label_off' => __( 'Hide', 'xpro-elementor-addons' ),
				'default'   => 'yes',
				'condition' => array(
					'show_goal' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_featured_image',
			array(
				'label'     => __( 'Show Image', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'xpro-elementor-addons' ),
				'label_off' => __( 'Hide', 'xpro-elementor-addons' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'show_donate_button',
			array(
				'label'     => __( 'Donate Button', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'xpro-elementor-addons' ),
				'label_off' => __( 'Hide', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'     => __( 'Order By', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'separator' => 'before',
				'options'   => array(
					'date'             => __( 'Date Created', 'xpro-elementor-addons' ),
					'title'            => __( 'Form Name', 'xpro-elementor-addons' ),
					'amount_donated'   => __( 'Amount Donated', 'xpro-elementor-addons' ),
					'name'             => __( 'Form Name', 'xpro-elementor-addons' ),
					'number_donations' => __( 'Number of Donations', 'xpro-elementor-addons' ),
					'menu_order'       => __( 'Menu Order', 'xpro-elementor-addons' ),
					'post__in'         => __( 'Form ID', 'xpro-elementor-addons' ),
					'closest_to_goal'  => __( 'Closest to Goal', 'xpro-elementor-addons' ),
				),
				'default'   => 'date',
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => __( 'Order', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'desc' => __( 'Descending', 'xpro-elementor-addons' ),
					'asc'  => __( 'Ascending', 'xpro-elementor-addons' ),
				),
				'default' => 'desc',
			)
		);

		$this->add_control(
			'columns',
			array(
				'label'   => __( 'Columns', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'best-fit' => __( 'Default', 'xpro-elementor-addons' ),
					'1'        => '1',
					'2'        => '2',
					'3'        => '3',
					'4'        => '4',
				),
				'default' => 'best-fit',
			)
		);

		$this->add_control(
			'display_style',
			array(
				'label'   => __( 'Display Type', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'redirect'     => __( 'Redirect to Page', 'xpro-elementor-addons' ),
					'modal_reveal' => __( 'Open modal window', 'xpro-elementor-addons' ),
				),
				'default' => 'redirect',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_general',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'space_between',
			array(
				'label'      => __( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 15,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .give-wrap .give-grid' => 'grid-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'item_box_shadow',
				'label'    => __( 'Box Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .give-wrap .give-grid__item',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'item_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .give-wrap .give-grid__item',
			)
		);

		$this->add_responsive_control(
			'item_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .give-wrap .give-grid__item' => 'overflow:hidden; border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			array(
				'label' => __( 'Image', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
					'{{WRAPPER}} .give-wrap .give-card__media img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'separator_panel_style',
			array(
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			)
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab(
			'normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'opacity',
			array(
				'label'     => __( 'Opacity', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .give-wrap .give-card__media img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .give-wrap .give-card__media img',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'opacity_hover',
			array(
				'label'     => __( 'Opacity', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .give-wrap .give-grid__item:hover .give-card__media img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .give-wrap .give-grid__item:hover .give-card__media img',
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
					'{{WRAPPER}} .give-wrap .give-card__media img' => 'transition-duration: {{SIZE}}s',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .give-wrap .give-card__media img',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .give-wrap .give-card__media img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->add_control(
			'content_background',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .give-form-grid-content' => 'background: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'content_border',
				'selector' => '{{WRAPPER}} .give-form-grid-content',
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .give-form-grid-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_title',
			array(
				'label'     => __( 'Title', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .give-form-grid-content .give-form-grid-content__title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .give-form-grid-content .give-form-grid-content__title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .give-form-grid-content .give-form-grid-content__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'heading_excerpt',
			array(
				'label'     => __( 'Content', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .give-form-grid-content .give-form-grid-content__text' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .give-form-grid-content .give-form-grid-content__text',
			)
		);

		$this->add_responsive_control(
			'excerpt_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .give-form-grid-content .give-form-grid-content__text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Button
		$this->start_controls_section(
			'section_button_style',
			array(
				'label'     => __( 'Button', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_donate_button' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .give-form-grid-content>button',
			)
		);

		$this->start_controls_tabs(
			'button_style_tabs'
		);

		$this->start_controls_tab(
			'button_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .give-form-grid-content>button' => 'transition: all .3s ease; color: {{VALUE}} !important; text-decoration-color: {{VALUE}} !important;',
					'{{WRAPPER}} .give-form-grid-content>button > span' => 'transition: color .3s ease; color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .give-form-grid-content>button' => 'transition: all .3s ease; background: {{VALUE}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .give-form-grid-content>button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover_tab_style',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'button_hcolor',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .give-form-grid-content>button:hover,{{WRAPPER}} .give-form-grid-content>button:focus' => 'color: {{VALUE}} !important; text-decoration-color: {{VALUE}} !important;',
					'{{WRAPPER}} .give-form-grid-content>button:hover > span,{{WRAPPER}} .give-form-grid-content>button:focus > span' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'button_hbg',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .give-form-grid-content>button:hover,{{WRAPPER}} .give-form-grid-content>button:focus' => 'background: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'button_hborder',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .give-form-grid-content>button:hover,{{WRAPPER}} .give-form-grid-content>button:focus' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .give-form-grid-content>button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_item_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .give-form-grid-content>button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'button_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .give-form-grid-content>button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Progress Bar
		$this->start_controls_section(
			'section_bar_style',
			array(
				'label'     => __( 'Progress Bar', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_bar' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'bar_content_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .form-grid-raised__details>span',
			)
		);

		$this->add_control(
			'bar_content_color',
			array(
				'label'     => __( 'Content Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .form-grid-raised__details>span' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'bar_content_background',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .give-form-grid-progress,{{WRAPPER}} .form-grid-raised' => 'background: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'bar_content_border',
				'selector' => '{{WRAPPER}} .give-form-grid-progress',
			)
		);

		$this->add_responsive_control(
			'bar_content_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .give-form-grid-progress' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'bar_heading',
			array(
				'label'     => __( 'Bar', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'bar_height',
			array(
				'label'      => __( 'Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .give-form-grid-progress-bar .give-progress-bar' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'bar_color',
			array(
				'label'     => __( 'Bar Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .give-form-grid-progress-bar .give-progress-bar>span' => 'background: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'bar_bg',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .give-form-grid-progress-bar .give-progress-bar' => 'background: {{VALUE}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'bar_shadow',
				'label'    => __( 'Bar Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .give-form-grid-progress-bar .give-progress-bar',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'bar_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .give-form-grid-progress-bar .give-progress-bar',
			)
		);

		$this->add_responsive_control(
			'bar_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .give-form-grid-progress-bar .give-progress-bar,{{WRAPPER}} .give-form-grid-progress-bar .give-progress-bar>span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'bar_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .give-form-grid-progress-bar .give-progress-bar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		if ( ! class_exists( '\Give' ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'donation-form-grid/layout/frontend.php';
	}
}
