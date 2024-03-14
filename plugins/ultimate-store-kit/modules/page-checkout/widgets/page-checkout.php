<?php

namespace UltimateStoreKit\Modules\PageCheckout\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Plugin;

use UltimateStoreKit\Base\Module_Base;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Page_Checkout extends Module_Base {

    public function get_name() {
        return 'usk-page-checkout';
    }

    public function get_title() {
        return BDTUSK . esc_html__('Checkout (Page)', 'ultimate-store-kit');
    }

    public function get_icon() {
        return 'usk-widget-icon usk-icon-page-checkout usk-new';
    }

    public function get_categories() {
        return ['ultimate-store-kit'];
    }

    public function get_keywords() {
        return ['cart', 'order', 'wc', 'checkout'];
    }

    public function get_style_depends() {
        if ($this->usk_is_edit_mode()) {
            return ['usk-all-styles'];
        } else {
            return ['wc-checkout', 'ultimate-store-kit-font', 'usk-page-checkout'];
        }
    }

    public function get_script_depends() {
        return ['usk-page-checkout'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_style_heading',
            [
                'label' => __('Heading', 'ultimate-store-kit'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'heading_color',
            [
                'label'     => __('Color', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-order-review .order_review_heading, {{WRAPPER}} .usk-checkout-billing-address .usk-checkout-billing-address-header' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'heading_bottom_spacing',
            [
                'label'         => __('Bottom Spacing', 'ultimate-store-kit'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'vh'],
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-order-review .order_review_heading, {{WRAPPER}} .usk-checkout-billing-address .usk-checkout-billing-address-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );
        $this->add_control(
            'heading_alignment',
            [
                'label'         => __('Alignment', 'ultimate-store-kit'),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'left'      => [
                        'title' => __('Left', 'ultimate-store-kit'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center'    => [
                        'title' => __('Center', 'ultimate-store-kit'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'     => [
                        'title' => __('Right', 'ultimate-store-kit'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default'       => 'left',
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-order-review .order_review_heading, {{WRAPPER}} .usk-checkout-billing-address .usk-checkout-billing-address-header' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'heading_typography',
                'label'     => __('Typography', 'ultimate-store-kit'),
                'selector'  => '{{WRAPPER}} .usk-checkout-order-review .order_review_heading, {{WRAPPER}} .usk-checkout-billing-address .usk-checkout-billing-address-header',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_checkout_shipping_form_container',
            [
                'label' => esc_html__('Container', 'ultimate-store-kit'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'checkout_shipping_form_container_bg',
            [
                'label'     => esc_html__('Background', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields .woocommerce-shipping-fields__field-wrapper' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields .woocommerce-billing-fields__field-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'checkout_shipping_form_container_alignment',
            [
                'label'     => esc_html__(
                    'Alignment',
                    'ultimate-store-kit'
                ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__(
                            'Left',
                            'ultimate-store-kit'
                        ),
                        'icon'        => 'eicon-text-align-left',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'ultimate-store-kit'),
                        'icon'        => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields .woocommerce-shipping-fields__field-wrapper' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields .woocommerce-shipping-fields__field-wrapper .woocommerce-input-wrapper input' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields .woocommerce-shipping-fields__field-wrapper .woocommerce-input-wrapper select' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields .woocommerce-billing-fields__field-wrapper' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields .woocommerce-billing-fields__field-wrapper .woocommerce-input-wrapper input' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields .woocommerce-billing-fields__field-wrapper .woocommerce-input-wrapper select' => 'text-align: {{VALUE}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'checkout_shipping_form_container_padding',
            [
                'label'      => esc_html__('Padding', 'ultimate-store-kit'),
                'type'       => Controls_Manager::DIMENSIONS,
                'selectors'  => [
                    '{{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields .woocommerce-shipping-fields__field-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields .woocommerce-billing-fields__field-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->add_responsive_control(
            'checkout_shipping_form_container_margin',
            [
                'label'      => esc_html__('Margin', 'ultimate-store-kit'),
                'type'       => Controls_Manager::DIMENSIONS,
                'selectors'  => [
                    '{{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields .woocommerce-shipping-fields__field-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields .woocommerce-billing-fields__field-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'checkout_shipping_form_container_border',
                'label'     => __('Border', 'ultimate-store-kit'),
                'selector'  => '{{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields .woocommerce-shipping-fields__field-wrapper, {{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields .woocommerce-billing-fields__field-wrapper',
            ]
        );
        $this->add_responsive_control(
            'checkout_shipping_form_container_radius',
            [
                'label'      => esc_html__('Border Radius', 'ultimate-store-kit'),
                'type'       => Controls_Manager::DIMENSIONS,
                'selectors'  => [
                    '{{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields .woocommerce-shipping-fields__field-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields .woocommerce-billing-fields__field-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_checkout_billing_address_form',
            [
                'label' => __('Form', 'ultimate-store-kit'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs(
            'tabs_checkout_billing_address_forms'
        );
        $this->start_controls_tab(
            'tab_checkout_billing_address_label',
            [
                'label' => __('Label', 'ultimate-store-kit'),
            ]
        );

        $this->add_control(
            'checkout_billing_address_input_label_color',
            [
                'label'     => esc_html__('Color', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields__field-wrapper .form-row label,
                    {{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields__field-wrapper .form-row label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'checkout_billing_address_input_label_indicator_color',
            [
                'label'     => esc_html__('Required Indicator Color:', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields__field-wrapper .form-row label abbr,
                    {{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields__field-wrapper .form-row label abbr' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'title',
            [
                'label'         => __('Bottom Spacing', 'ultimate-store-kit'),
                'type'          => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields__field-wrapper .form-row label,
                    {{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields__field-wrapper .form-row label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'checkout_billing_address_input_label_typography',
                'label'     => __('Typography', 'ultimate-store-kit'),
                'selector'  => '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields__field-wrapper .form-row label, {{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields__field-wrapper .form-row label',
                'separator' => 'before'
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_checkout_billing_address_input',
            [
                'label' => __('Input', 'ultimate-store-kit'),
            ]
        );
        $this->add_control(
            'checkout_billing_address_input_color',
            [
                'label'     => esc_html__('Color', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields__field-wrapper :is(input, textarea, .woocommerce-input-wrapper .select2-selection, select)' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields__field-wrapper :is(input, input::placeholder, textarea::placeholder, .woocommerce-input-wrapper .select2-selection, select):focus' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'checkout_billing_address_input_placeholder_color',
            [
                'label'     => esc_html__('Placeholder Color', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields__field-wrapper :is(input, textarea, .woocommerce-input-wrapper .select2-selection, select)::placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields__field-wrapper :is(input, textarea, .woocommerce-input-wrapper .select2-selection, select)::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'checkout_billing_address_input_background',
            [
                'label'     => esc_html__('Background', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields__field-wrapper :is(input, textarea, .select2-selection, select)' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields__field-wrapper :is(input, textarea, .woocommerce-input-wrapper .select2-selection, select)'  => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'checkout_billing_address_input_padding',
            [
                'label'      => esc_html__('Padding', 'ultimate-store-kit'),
                'type'       => Controls_Manager::DIMENSIONS,
                'selectors'  => [
                    '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields__field-wrapper :is(input, textarea, .select2-selection, select),
                    {{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields__field-wrapper :is(input, textarea, .woocommerce-input-wrapper .select2-selection, select)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_control(
            'checkout_billing_address_input_margin',
            [
                'label'      => esc_html__('Margin', 'ultimate-store-kit'),
                'type'       => Controls_Manager::DIMENSIONS,
                'selectors'  => [
                    '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields__field-wrapper :is(input, textarea, .woocommerce-input-wrapper .select2-selection, select),
                    {{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields__field-wrapper :is(input, textarea, .woocommerce-input-wrapper .select2-selection, select)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'           => 'checkout_billing_address_input_border',
                'label'          => esc_html__('Border', 'ultimate-store-kit'),
                'selector' =>
                '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields__field-wrapper :is(input, textarea, .woocommerce-input-wrapper .select2-selection, select),
                {{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields__field-wrapper :is(input, textarea, .woocommerce-input-wrapper .select2-selection, select)',
            ]
        );
        $this->add_control(
            'checkout_billing_address_input_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'ultimate-store-kit'),
                'type'       => Controls_Manager::DIMENSIONS,
                'selectors'  => [
                    '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields__field-wrapper :is(input, textarea, .woocommerce-input-wrapper .select2-selection, select),
                   {{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields__field-wrapper :is(input, textarea, .woocommerce-input-wrapper .select2-selection, select)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'
                ],
            ]
        );

        $this->add_control(
            'heading_checkout_billing_address_input_focus',
            [
                'label'     => __('F O C U S', 'ultimate-store-kit'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'checkout_billing_address_input_color_focus',
            [
                'label'     => esc_html__('Color', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields__field-wrapper :is(input, textarea, .select2-selection, select):focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields__field-wrapper :is(input, input::placeholder, textarea::placeholder, .woocommerce-input-wrapper .select2-selection, select):focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'checkout_billing_address_input_background_focus',
            [
                'label'     => esc_html__('Background', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields__field-wrapper :is(input, textarea, .select2-selection, select):focus' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields__field-wrapper :is(input, input::placeholder, textarea::placeholder, .woocommerce-input-wrapper .select2-selection, select):focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'checkout_billing_address_input_border_focus',
                'label'    => esc_html__('Border', 'ultimate-store-kit'),
                'separator' => 'after',
                'selector' =>
                '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields__field-wrapper :is(input, textarea, .select2-selection, select):focus,
                {{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields__field-wrapper :is(input, input::placeholder, textarea::placeholder, .woocommerce-input-wrapper .select2-selection, select):focus',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'checkout_billing_address_input_typography',
                'label'     => __('Typography', 'ultimate-store-kit'),
                'separator' => 'before',
                'selector'  =>
                '{{WRAPPER}} .usk-checkout-billing-address .woocommerce-billing-fields__field-wrapper :is(input, textarea, .woocommerce-input-wrapper .select2-selection, select),
                {{WRAPPER}} .usk-checkout-shipping-form .woocommerce-shipping-fields__field-wrapper :is(input, input::placeholder, textarea::placeholder, .woocommerce-input-wrapper .select2-selection, select):focus',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'section_style_layout',
            [
                'label' => __('Order Details', 'ultimate-store-kit'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'table_border',
                'label'     => __('Border', 'ultimate-store-kit'),
                'selector'  => '{{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table th,
                {{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table td',
            ]
        );
        $this->start_controls_tabs(
            'tabs_table_header'
        );
        $this->start_controls_tab(
            'tab_table_header',
            [
                'label' => __('HEADER', 'ultimate-store-kit'),
            ]
        );
        $this->add_control(
            'orders_header_color',
            [
                'label'     => esc_html__('Text Color', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table thead th' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'orders_header_background',
            [
                'label'     => esc_html__('Background', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table thead th' => 'background-color: {{VALUE}}',
                ],
            ]
        );


        $this->add_responsive_control(
            'table_header_padding',
            [
                'label'      => esc_html__('Padding', 'ultimate-store-kit'),
                'type'       => Controls_Manager::DIMENSIONS,
                'selectors'  => [
                    '{{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '.rtl {{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table thead th' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}} !important;',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'orders_header_text_typography',
                'label'          => esc_html__('Typography', 'ultimate-store-kit'),
                'selector'       => '{{WRAPPER}} .usk-checkout-order-review #order_review .woocommerce-checkout-review-order-table thead th',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_table_body',
            [
                'label' => __('BODY', 'ultimate-store-kit'),
            ]
        );
        $this->add_control(
            'table_body_text_color',
            [
                'label'     => esc_html__('Text Color', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table tbody td' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'orders_body_background',
            [
                'label'     => esc_html__('Background', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table > tbody > tr' => 'background-color: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_control(
            'table_body_price_color',
            [
                'label'     => esc_html__('Price Color', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table tbody td .amount' => 'color: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_responsive_control(
            'table_body_data_padding',
            [
                'label'      => esc_html__('Row Padding ', 'ultimate-store-kit'),
                'type'       => Controls_Manager::DIMENSIONS,
                'selectors'  => [
                    '{{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table:not(.shipping__table--multiple) > tbody > tr > td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'orders_body_typography',
                'label'    => esc_html__(' Typography', 'ultimate-store-kit'),
                'selector' => '{{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table tbody :is(td, label, .amount, strong, bdi)',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_table_footer',
            [
                'label' => __('FOOTER', 'ultimate-store-kit'),
            ]
        );
        $this->add_control(
            'footer_text_color',
            [
                'label'     => esc_html__('Text Color', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table tfoot :is(th, td, label)' => 'color: {{VALUE}} !important'
                ],
            ]
        );

        $this->add_control(
            'footer_price_color',
            [
                'label'     => esc_html__('Price Color', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table tfoot :is(th, td) .amount' => 'color: {{VALUE}} !important'
                ],
            ]
        );
        $this->add_control(
            'footer_subtotal_bg_color',
            [
                'label'     => esc_html__('Subtotal Background Color', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table tfoot .cart-subtotal' => 'background-color: {{VALUE}} !important'
                ],
            ]
        );

        $this->add_control(
            'footer_total_bg_color',
            [
                'label'     => esc_html__('Total Background Color', 'ultimate-store-kit'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table tfoot .order-total' => 'background-color: {{VALUE}} !important'
                ],
            ]
        );

        $this->add_responsive_control(
            'table_footer_data_padding',
            [
                'label'      => esc_html__('Padding ', 'ultimate-store-kit'),
                'type'       => Controls_Manager::DIMENSIONS,
                'selectors'  => [
                    '{{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table tfoot :is(th, td, label)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '.rtl {{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table tfoot :is(th, td, label)' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}} !important;',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'footer_color_typography',
                'label'    => esc_html__('Text Typography', 'ultimate-store-kit'),
                'selector' => '{{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table tfoot th,
				{{WRAPPER}} .usk-checkout-order-review .woocommerce-checkout-review-order-table tfoot td :is(label, .amount)',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        // $this->start_controls_section(
        //     'checkout_payment_content_section',
        //     [
        //         'label' => esc_html__('Content', 'ultimate-store-kit-pro'),
        //         'tab'   => Controls_Manager::TAB_STYLE,
        //     ]
        // );


        // $this->end_controls_section();

        $this->start_controls_section(
            'checkout_payment_methods',
            [
                'label' => esc_html__('Payment Methods', 'ultimate-store-kit-pro'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs(
            'tabs_checkout_payment_methods'
        );
        $this->start_controls_tab(
            'tab_content_checkout_payment_method',
            [
                'label' => __('Content', 'ultimate-store-kit'),
            ]
        );
        $this->add_control(
            'checkout_payment_text_color',
            [
                'label'     => esc_html__('Text Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-payment #payment .payment_methods .payment_box'      => 'color: {{VALUE}};',
                    '{{WRAPPER}} .usk-checkout-payment #payment .payment_methods .payment_box p'    => 'color: {{VALUE}};',
                    '{{WRAPPER}} .usk-checkout-payment #payment .payment_methods .payment_box a'    => 'color: {{VALUE}};',
                    '{{WRAPPER}} .usk-checkout-payment #payment .woocommerce-privacy-policy-text p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'checkout_payment_link_color',
            [
                'label'     => esc_html__('Link Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-payment a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'checkout_payment_link_hover_color',
            [
                'label'     => esc_html__('Link hover Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-payment a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'checkout_payment_content_typography',
                'label'    => esc_html__('Typography', 'ultimate-store-kit-pro'),
                'selector' => '{{WRAPPER}} .usk-checkout-payment #payment :is(.payment_box, .woocommerce-terms-and-conditions-wrapper, .payment_method_paypal) :is(p, a)',
            ]
        );
        $this->add_control(
            'checkout_payment_method_radio_input_color',
            [
                'label'     => esc_html__('Checked Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-payment #payment .wc_payment_method input[type="radio"]' => 'accent-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'checkout_payment_label_color',
            [
                'label'     => esc_html__('Label Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-payment .wc_payment_method label' => 'color: {{VALUE}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'checkout_payment_methods_checkbox_padding',
            [
                'label'     => esc_html__('Padding', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-payment #payment .payment_methods li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'checkout_payment_methods_checkbox_margin',
            [
                'label'     => esc_html__('Margin', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-payment #payment .wc_payment_method input[type="radio"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'checkout_payment_methods_border',
                'label'     => esc_html__('Border', 'ultimate-store-kit-pro'),
                'selector'  => '{{WRAPPER}} .usk-checkout-payment #payment .payment_methods li',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'checkout_payment_methods_radius',
            [
                'label'     => esc_html__('Border Radius', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-payment #payment .payment_methods li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'checkout_payment_methods_checkbox_spacing',
            [
                'label'     => __('Bottom Spacing', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::SLIDER,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-payment #payment .payment_methods li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'checkout_payment_label_typography',
                'label'     => esc_html__('Typography', 'ultimate-store-kit-pro'),
                'selector'  => '{{WRAPPER}} .usk-checkout-payment .wc_payment_method label',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_button_checkout_payment_method',
            [
                'label' => __('Button', 'ultimate-store-kit'),
            ]
        );
        $this->add_control(
            'checkout_payment_order_button_color',
            [
                'label'     => esc_html__('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-payment #payment #place_order' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'checkout_payment_order_button_background',
            [
                'label'     => esc_html__('Background', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-payment #payment #place_order' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'checkout_payment_order_button_padding',
            [
                'label'     => esc_html__('Padding', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-payment #payment #place_order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'checkout_payment_input_border',
                'label'     => esc_html__('Border', 'ultimate-store-kit-pro'),
                'selector'  => '{{WRAPPER}} .usk-checkout-payment #payment #place_order',
            ]
        );


        $this->add_control(
            'checkout_payment_order_button_radius',
            [
                'label'     => esc_html__('Border Radius', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-payment #payment #place_order' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'checkout_payment_order_button_width',
            [
                'label'        => __('Full Width', 'ultimate-store-kit-pro'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'ultimate-store-kit-pro'),
                'label_off'    => __('No', 'ultimate-store-kit-pro'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
                'selectors'    => [
                    '{{WRAPPER}} .usk-checkout-payment #payment #place_order' => 'width: 100%;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'checkout_payment_order_button_typography',
                'label'    => esc_html__('Typography', 'ultimate-store-kit-pro'),
                'selector' => '{{WRAPPER}} .usk-checkout-payment #payment #place_order',
            ]
        );
        $this->add_control(
            'heading_button_hover',
            [
                'label'     => __('H O V E R', 'ultimate-store-kit'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'checkout_payment_order_button_tabs_hover_clr',
            [
                'label'     => esc_html__('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-payment #payment #place_order:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'checkout_payment_order_button_tabs_hover_bg',
            [
                'label'     => esc_html__('Background', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-checkout-payment #payment #place_order:hover' => 'background: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function checkout_payment_methods() {

?>
        <div class="usk-checkout-payment">
            <?php if (Plugin::instance()->editor->is_edit_mode()) {
                woocommerce_checkout_payment();
            } else {
                if (!empty(WC()->cart) && !WC()->cart->is_empty()) {
                    woocommerce_checkout_payment();
                }
            }
            ?>
        </div>
    <?php

    }
    public function checkout_shipping_form() {
        $checkout = WC()->checkout();
        if (Plugin::instance()->editor->is_edit_mode()) {
            $screen_check = true;
        } else {
            $screen_check = WC()->cart->needs_shipping_address();
        }
    ?>
        <div class="usk-checkout-shipping-form">
            <div class="woocommerce-shipping-fields">
                <?php if (true === $screen_check) : ?>
                    <h3 id="ship-to-different-address">
                        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                            <input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked(apply_filters('woocommerce_ship_to_different_address_checked', 'shipping' === get_option('woocommerce_ship_to_destination') ? 1 : 0), 1); ?> type="checkbox" name="ship_to_different_address" value="1" />
                            <span><?php esc_html_e('Ship to a different address?', 'ultimate-store-kit'); ?></span>
                        </label>
                    </h3>
                    <div class="shipping_address">
                        <?php do_action('woocommerce_before_checkout_shipping_form', $checkout); ?>
                        <div class="woocommerce-shipping-fields__field-wrapper">
                            <?php $fields = $checkout->get_checkout_fields('shipping');
                            foreach ($fields as $key => $field) {
                                woocommerce_form_field($key, $field, $checkout->get_value($key));
                            } ?>
                        </div>
                        <?php do_action('woocommerce_after_checkout_shipping_form', $checkout); ?>
                    </div>
                <?php endif;
                ?>
            </div>
        </div>
    <?php
    }
    protected function checkout_billing_address() {
        $settings = $this->get_settings_for_display();
        $checkout = WC()->checkout(); ?>

        <div class="usk-checkout-billing-address">
            <div class="woocommerce-billing-fields">

                <?php if (wc_ship_to_billing_address_only() && WC()->cart->needs_shipping()) : ?>
                    <h3 class="usk-checkout-billing-address-header"><?php esc_html_e('Billing &amp; Shipping', 'ultimate-store-kit'); ?></h3>
                <?php else : ?>
                    <h3 class="usk-checkout-billing-address-header"><?php esc_html_e('Billing details', 'ultimate-store-kit'); ?></h3>
                <?php endif; ?>
                <?php do_action('woocommerce_before_checkout_billing_form', $checkout); ?>

                <div class="woocommerce-billing-fields__field-wrapper">
                    <?php $fields = $checkout->get_checkout_fields('billing');
                    foreach ($fields as $key => $field) {
                        woocommerce_form_field($key, $field, $checkout->get_value($key));
                    } ?>

                </div>

                <?php do_action('woocommerce_after_checkout_billing_form', $checkout); ?>

            </div>

            <?php if (!is_user_logged_in() && $checkout->is_registration_enabled()) : ?>
                <div class="woocommerce-account-fields">
                    <?php if (!$checkout->is_registration_required()) : ?>

                        <p class="form-row form-row-wide create-account">
                            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                                <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked((true === $checkout->get_value('createaccount') || (true === apply_filters('woocommerce_create_account_default_checked', false))), true); ?> type="checkbox" name="createaccount" value="1" />
                                <span><?php esc_html_e('Create an account?', 'ultimate-store-kit'); ?></span>
                            </label>
                        </p>

                    <?php endif; ?>

                    <?php do_action('woocommerce_before_checkout_registration_form', $checkout); ?>

                    <?php if ($checkout->get_checkout_fields('account')) : ?>

                        <div class="create-account">
                            <?php foreach ($checkout->get_checkout_fields('account') as $key => $field) : ?>
                                <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
                            <?php endforeach; ?>
                            <div class="clear"></div>
                        </div>

                    <?php endif; ?>

                    <?php do_action('woocommerce_after_checkout_registration_form', $checkout); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php
    }

    public function checkout_order_review() {
    ?>
        <div class="usk-checkout-order-review">
            <h3 id="order_review_heading" class="order_review_heading"><?php esc_html_e('Your order', 'ultimate-store-kit'); ?></h3>
            <div id="order_review" class="woocommerce-checkout-review-order">
                <?php do_action('woocommerce_checkout_before_order_review'); ?>
                <?php
                global $wp;
                if (isset($wp->query_vars['order-pay'])) {
                    \WC_Shortcode_Checkout::output([]);
                } else {
                    woocommerce_order_review();
                }

                ?>
            </div>
            <?php do_action('woocommerce_checkout_after_order_review'); ?>
        </div>
    <?php
    }
    public function render() {
    ?>

        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
            <div class="usk-page-checkout">

                <div class="usk-checkout-address-wrapper">
                    <?php $this->checkout_billing_address(); ?>
                    <?php $this->checkout_shipping_form(); ?>
                </div>
                <div class="usk-checkout-details-wrapper">
                    <?php $this->checkout_order_review(); ?>
                    <?php $this->checkout_payment_methods(); ?>
                </div>
            </div>
        </form>

<?php
    }
}
