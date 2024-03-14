<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

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
class Woo_Add_To_Cart extends Widget_Base {

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
		return 'xpro-woo-add-to-cart';
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
		return __( 'Add To Cart', 'xpro-elementor-addons' );
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
		return 'xi-cart xpro-widget-label';
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
		return array( 'xpro-themer' );
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
		return array( 'add', 'cart', 'button' );
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
			'section_general_fields',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
			)
		);

		//notice
		if ( ! class_exists( '\WooCommerce' ) ) {
			$this->add_control(
				'woo_missing_notice',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf(
					/* translators: 1$s: Title */
						__( 'Looks like %1$s is missing in your site. Please click on the link below and install/activate %1$s. Make sure to refresh this page after installation or activation.', 'xpro-elementor-addons' ),
						'<a href="' . esc_url( admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) )
						. '" target="_blank" rel="noopener">Woocommerce Plugin</a>'
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
				)
			);

			$this->add_control(
				'woo_install',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => '<a href="' . esc_url( admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) ) . '" target="_blank" rel="noopener">Click to install or activate Woocommerce Plugin</a>',
				)
			);
			$this->end_controls_section();

			return;
		}

		$this->add_control(
			'show_stock',
			array(
				'label'        => esc_html__( 'Show Stock Status', 'xpro-elementor-addons' ),
				'description'  => esc_html__( 'Show stock status, If product have stock enable.', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'xpro-elementor-addons' ),
				'label_off'    => esc_html__( 'No', 'xpro-elementor-addons' ),
				'default'      => 'yes',
				'return_value' => 'yes',
				'selectors'    => array(
					'{{WRAPPER}} .stock' => 'display: block;',
				),
			)
		);

		$this->add_control(
			'show_variation_description',
			array(
				'label'        => esc_html__( 'Variation Description', 'xpro-elementor-addons' ),
				'description'  => esc_html__( 'Show product variation description.', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'xpro-elementor-addons' ),
				'label_off'    => esc_html__( 'No', 'xpro-elementor-addons' ),
				'default'      => 'yes',
				'return_value' => 'yes',
				'selectors'    => array(
					'{{WRAPPER}} .woocommerce-variation-description' => 'display: block;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_stock_status_style',
			array(
				'label'     => __( 'Stock Status', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_stock' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'stock_status_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'stock_status_typography',
				'label'          => esc_html__( 'Typography', 'xpro-elementor-addons' ),
				'selector'       => '{{WRAPPER}} .stock',
				'exclude'        => array( 'font_style', 'text_decoration', 'letter_spacing' ),
				'fields_options' => array(
					'typography'     => array(
						'default' => 'custom',
					),
					'font_size'      => array(
						'label'      => esc_html__( 'Font Size (px)', 'xpro-elementor-addons' ),
						'default'    => array(
							'size' => '14',
							'unit' => 'px',
						),
						'size_units' => array( 'px' ),
					),
					'text_transform' => array(
						'default' => 'uppercase',
					),
					'font_weight'    => array(
						'default' => '500',
					),
					'line_height'    => array(
						'label'      => esc_html__( 'Line Height (px)', 'xpro-elementor-addons' ),
						'default'    => array(
							'size' => '17',
							'unit' => 'px',
						),
						'size_units' => array( 'px' ),
					),
				),
			)
		);

		$this->add_control(
			'in_stock_color',
			array(
				'label'     => esc_html__( 'In Stock Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .stock' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'out_of_stock_color',
			array(
				'label'     => esc_html__( 'Out Of Stock Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .stock.out-of-stock' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'xpro_section_add_cart_quantity_style',
			array(
				'label' => esc_html__( 'Quantity Input', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'quantity_typography',
				'label'    => esc_html__( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .quantity .qty',
				'exclude'  => array( 'text_transform', 'line_height', 'text_decoration' ),
			)
		);

		$this->add_control(
			'quantity_color',
			array(
				'label'     => esc_html__( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .quantity .qty' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'quantity_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .quantity .qty' => 'background: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'quantity_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .quantity .qty, {{WRAPPER}} form .xpro-quantity-wrap button' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'quantity_input_width',
			array(
				'label'      => esc_html__( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} form.cart input.qty' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'quantity_input_height',
			array(
				'label'      => esc_html__( 'Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} form.cart input.qty' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'quantity_wrap_margin',
			array(
				'label'      => esc_html__( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .quantity' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'variations_styles',
			array(
				'label' => esc_html__( 'Variations', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'variation_tabs' );

		$this->start_controls_tab(
			'variation_label_tab',
			array(
				'label' => esc_html__( 'Label', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'variation_label_typography',
				'label'    => esc_html__( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .variations label, {{WRAPPER}} .variations select',
			)
		);

		$this->add_control(
			'variation_label_color',
			array(
				'label'     => esc_html__( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .variations td label' => 'color: {{VALUE}};',
					'{{WRAPPER}} .variations select'   => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'variation_label_display_style',
			array(
				'label'     => esc_html__( 'Display Style', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'row',
				'options'   => array(
					'row'    => array(
						'title' => __( 'Inline', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-editor-list-ul',
					),
					'column' => array(
						'title' => __( 'Block', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-ellipsis-h',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .variations tr' => 'flex-direction: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'variation_inline_label_width',
			array(
				'label'      => esc_html__( 'Label Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .variations th.label' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .variations td.value' => 'width: 100%;',
				),
				'condition'  => array(
					'variation_label_display_style' => 'row',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'variation_description_tab',
			array(
				'label'     => esc_html__( 'Description', 'xpro-elementor-addons' ),
				'condition' => array(
					'show_variation_description' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'variation_description_typography',
				'label'    => esc_html__( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-variation-description',
				'exclude'  => array( 'font_family', 'font_style', 'text_decoration' ),
			)
		);

		$this->add_control(
			'variation_description_color',
			array(
				'label'     => esc_html__( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#666666',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-variation-description p' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'variation_description_margin',
			array(
				'label'      => esc_html__( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-variation-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'variation_price_tab',
			array(
				'label' => esc_html__( 'Price', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'variation_price_typography',
				'label'    => esc_html__( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} :is(.price, .price del, .price ins )',
				'exclude'  => array( 'text_transform' ),
			)
		);

		$this->add_control(
			'variation_price_color',
			array(
				'label'     => esc_html__( 'Price Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} :is(.price, .price del, .price ins )' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'variation_sale_price_color',
			array(
				'label'     => esc_html__( 'Sale Price Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .price ins .amount' => 'background: transparent; color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'variation_price_discount_badge_color',
			array(
				'label'     => esc_html__( 'Discount Badge Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#FFFFFF',
				'selectors' => array(
					'{{WRAPPER}} .xpro-badge' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'variation_price_discount_badge_bg_color',
			array(
				'label'     => esc_html__( 'Discount Badge Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .xpro-badge' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'variation_price_discount_badge_font_size',
			array(
				'label'      => esc_html__( 'Badge Font Size', 'xpro-elementor-addons' ),
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
					'{{WRAPPER}} .xpro-badge' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'variation_price_margin',
			array(
				'label'      => esc_html__( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-variation-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; display: block;',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'variation_dropdown',
			array(
				'label'     => esc_html__( 'Dropdown', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'variation_dropdown_color',
			array(
				'label'     => esc_html__( 'Dropdown Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .variations select' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'variation_dropdown_border',
				'label'    => esc_html__( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .variations select',
			)
		);

		$this->add_control(
			'variation_dropdown_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .variations select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'variation_dropdown_border_border!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'variation_item_margin',
			array(
				'label'      => esc_html__( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .variations tr' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'variation_swatches_styles',
			array(
				'label' => esc_html__( 'Swatches', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'variation_swatch_tabs' );

		$this->start_controls_tab(
			'variation_swatch_color_tab',
			array(
				'label' => esc_html__( 'Color', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'variation_swatch_color_width',
			array(
				'label'      => esc_html__( 'Swatch Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 150,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro_swatches .swatch.swatch_color' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'variation_swatch_color_height',
			array(
				'label'      => esc_html__( 'Swatch Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 150,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro_swatches .swatch.swatch_color' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'variation_swatch_color_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro_swatches .swatch_color' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'variation_swatch_color_label_border',
				'label'     => esc_html__( 'Border', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro_swatches .swatch_color',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'variation_swatch_color_selected_label_border',
			array(
				'label'     => esc_html__( 'Selected Border', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro_swatches .swatch_color.selected' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'variation_swatch_image_tab',
			array(
				'label' => esc_html__( 'Image', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'variation_swatch_image_width',
			array(
				'label'      => esc_html__( 'Swatch Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 150,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro_swatches .swatch.swatch_image' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'variation_swatch_image_height',
			array(
				'label'      => esc_html__( 'Swatch Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 150,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro_swatches .swatch.swatch_image' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'variation_swatch_image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro_swatches .swatch_image' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'variation_swatch_image_label_border',
				'label'     => esc_html__( 'Border', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro_swatches .swatch_image',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'variation_swatch_image_selected_label_border',
			array(
				'label'     => esc_html__( 'Selected Border', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro_swatches .swatch_image.selected' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'variation_swatch_label_tab',
			array(
				'label' => esc_html__( 'Label', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'variation_swatch_label_typography',
				'label'    => esc_html__( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro_swatches .swatch_label',
			)
		);

		$this->add_control(
			'variation_swatch_label_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro_swatches .swatch_label' => 'color: {{VALUE}} !important;',
				),
			)
		);
		$this->add_control(
			'variation_swatch_label_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro_swatches .swatch_label' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'variation_swatch_label_selected_label_border',
			array(
				'label'     => esc_html__( 'Selected Border', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro_swatches .swatch_label.selected' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'variation_swatch_label_label_border',
				'label'     => esc_html__( 'Border', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro_swatches .swatch_label',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'variation_swatch_label_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro_swatches .swatch_label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'variation_swatch_label_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro_swatches .swatch_label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		// Button
		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => __( 'Button', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} form.cart button.alt',
			)
		);

		$this->add_control(
			'button__width',
			array(
				'label'      => esc_html__( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} form.cart button.alt' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'button_style_tabs' );
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
					'{{WRAPPER}} form.cart button.alt' => 'color: {{VALUE}} !important;',
				),
			)
		);
		$this->add_control(
			'button_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} form.cart button.alt' => 'background-color: {{VALUE}} !important;',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} form.cart button.alt',
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
					'{{WRAPPER}} form.cart button.alt:hover,{{WRAPPER}} form.cart button.alt:focus' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'button_h_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} form.cart button.alt:hover,{{WRAPPER}} form.cart button.alt:focus' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'button_hborder',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} form.cart button.alt:hover,{{WRAPPER}} form.cart button.alt:focus' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} form.cart button.alt' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} form.cart button.alt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'button_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} form.cart button.alt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();

	}

	public function get_a_simple_product( $type = 'simple' ) {

		$args = array(
			'type'    => array( $type ),
			'status'  => array( 'publish', 'draft' ),
			'limit'   => 1,
			'orderby' => 'ID',
			'order'   => 'DESC'
		);

		$prod = wc_get_products( $args );

		return empty( $prod[0] ) ? false : $prod[0];
	}

	/**
	 * Grab a product for elementor editor preview
	 *
	 * @param $post_type
	 * @return string
	 */
	public function get_product( $post_type ) {

		global $product;

		if ( 'product' === $post_type ) {
			$product = wc_get_product();
		} else {
			$product = $this->get_a_simple_product();
		}

		return empty( $product ) ? false : $product;

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

		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'woo-add-to-cart/layout/frontend.php';

	}
}
