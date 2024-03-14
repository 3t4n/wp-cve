<?php
/**
 * WPForm widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

defined( 'ABSPATH' ) || die();

class WPForm extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'WPForms', 'skt-addons-elementor' );
    }

	public function get_custom_help_url() {
		return '#';
	}

    /**
     * Get widget icon.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'skti skti-form';
    }

    public function get_keywords() {
        return [ 'wpf', 'wpform', 'form', 'contact', 'cf7', 'contact form', 'gravity', 'ninja' ];
    }

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {

		$this->start_controls_section(
			'_section_wpforms',
			[
				'label' => skt_addons_elementor_is_wpforms_activated() ? __( 'WPForms', 'skt-addons-elementor' ) : __( 'Missing Notice',
                    'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        if ( ! skt_addons_elementor_is_wpforms_activated() ) {

            $this->add_control(
                '_wpforms_missing_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => sprintf(
                        __( 'Hello %2$s, looks like %1$s is missing in your site. Please click on the link below and install/activate %1$s. Make sure to refresh this page after installation or activation.', 'skt-addons-elementor' ),
                        '<a href="'.esc_url( admin_url( 'plugin-install.php?s=WPForms&tab=search&type=term' ) ).'" target="_blank" rel="noopener">WPForms</a>',
                        skt_addons_elementor_get_current_user_display_name()
                    ),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
                ]
            );

            $this->add_control(
                '_wpforms_install',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => '<a href="'.esc_url( admin_url( 'plugin-install.php?s=WPForms&tab=search&type=term' ) ).'" target="_blank" rel="noopener">Click to install or activate WPForms</a>',
                ]
            );

        }else {

			$this->add_control(
				'form_id',
				[
					'label' => __( 'Select Your Form', 'skt-addons-elementor' ),
					'type' => Controls_Manager::SELECT,
					'label_block' => true,
					'options' => ['' => __( 'Select a WPForm', 'skt-addons-elementor' ) ] + \skt_addons_elementor_get_wpforms(),
				]
			);

		}

        $this->end_controls_section();
    }

	/**
     * Register widget style controls
     */
    protected function register_style_controls() {
		$this->__fields_style_controls();
		$this->__label_style_controls();
		$this->__submit_style_controls();
	}

    protected function __fields_style_controls() {

        $this->start_controls_section(
            '_section_fields_style',
            [
                'label' => __( 'Form Fields', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
		);

        $this->add_responsive_control(
            'field_margin',
            [
                'label' => __( 'Field Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wpforms-field:not(.wpforms-submit), .wpforms-field-required:not(.wpforms-submit)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'field_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wpforms-field input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-field textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'field_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wpforms-field input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-field textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'field_typography',
                'label' => __( 'Typography', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .wpforms-field input, {{WRAPPER}} .wpforms-field-textarea textarea',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
            ]
        );

        $this->add_control(
            'field_textcolor',
            [
                'label' => __( 'Field Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpforms-field input, {{WRAPPER}} .wpforms-field-textarea textarea' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
            'field_placeholder_color',
            [
                'label' => __( 'Field Placeholder Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ::-webkit-input-placeholder'	=> 'color: {{VALUE}};',
                    '{{WRAPPER}} ::-moz-placeholder'			=> 'color: {{VALUE}};',
                    '{{WRAPPER}} ::-ms-input-placeholder'		=> 'color: {{VALUE}};',
                ],
            ]
        );

		$this->start_controls_tabs( 'tabs_field_state' );

        $this->start_controls_tab(
            'tab_field_normal',
            [
                'label' => __( 'Normal State', 'skt-addons-elementor' ),
            ]
        );

		$this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'field_border',
                'selector' => '{{WRAPPER}} .wpforms-field input, {{WRAPPER}} .wpforms-field-textarea textarea',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'field_box_shadow',
                'selector' => '{{WRAPPER}} .wpforms-field input, {{WRAPPER}} .wpforms-field-textarea textarea',
            ]
        );

        $this->add_control(
            'field_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpforms-field input, {{WRAPPER}} .wpforms-field-textarea textarea' => 'background-color: {{VALUE}}',
                ],
            ]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
            'tab_field_focus',
            [
                'label' => __( 'Focus', 'skt-addons-elementor' ),
            ]
        );

		$this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'field_focus_border',
                'selector' => '{{WRAPPER}} .wpforms-field input:focus, {{WRAPPER}} .wpforms-field-textarea textarea:focus',
            ]
		);

		$this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'field_focus_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .wpforms-field input:focus, {{WRAPPER}} .wpforms-field-textarea textarea:focus',
            ]
		);

		$this->add_control(
            'field_focus_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpforms-field input:focus, {{WRAPPER}} .wpforms-field-textarea textarea:focus' => 'background-color: {{VALUE}}',
                ],
            ]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

        $this->end_controls_section();
	}

    protected function __label_style_controls() {

        $this->start_controls_section(
            'wpf-form-label',
            [
                'label' => __( 'Form Fields Label', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'label_margin',
            [
                'label' => __( 'Margin', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wpforms-field-container label.wpforms-field-label' => 'display: inline-block; padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wpforms-field-container label.wpforms-field-label' => 'display: inline-block; padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hr3',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => __( 'Label Typography', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .wpforms-field-container label.wpforms-field-label',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
            ]
        );

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'sublabel_typography',
                'label' => __( 'Sub Label Typography', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .wpforms-field-sublabel',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
            ]
        );

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typography',
                'label' => __( 'Description Typography', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .wpforms-field-description',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
            ]
        );

        $this->add_control(
            'label_color_popover',
            [
                'label' => __( 'Colors', 'skt-addons-elementor' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __( '', 'skt-addons-elementor' ),
                'label_on' => __( 'Custom', 'skt-addons-elementor' ),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_control(
            'label_color',
            [
                'label' => __( 'Label Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpforms-field-container label.wpforms-field-label' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'label_color_popover' => 'yes'
                ],
            ]
		);

		$this->add_control(
            'requered_label',
            [
                'label' => __( 'Required Label Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpforms-required-label' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'label_color_popover' => 'yes'
                ],
            ]
        );

		$this->add_control(
            'sublabel_color',
            [
                'label' => __( 'Sub Label Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpforms-field-sublabel' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'label_color_popover' => 'yes'
                ],
            ]
        );

		$this->add_control(
            'desc_label_color',
            [
                'label' => __( 'Description Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpforms-field-description' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'label_color_popover' => 'yes'
                ],
            ]
        );

        $this->end_popover();

        $this->end_controls_section();
	}

    protected function __submit_style_controls() {

        $this->start_controls_section(
            'submit',
            [
                'label' => __( 'Submit Button', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
		);

        $this->add_control(
            'submit_btn_width',
            [
                'label' => __( 'Button Full Width?', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'skt-addons-elementor' ),
                'label_off' => __( 'No', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'button_width',
            [
                'label' => __( 'Button Width', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'condition' => [
                    'submit_btn_width' => 'yes'
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100
                ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 800,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpforms-submit' => 'display: block; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
            'submit_btn_position',
            [
                'label' => __( 'Button Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'condition' => [
                    'submit_btn_width' => '',

                ],
                'desktop_default' => 'left',
                'toggle' => false,
				'prefix_class' => 'skt-form-btn--%s',
				'selectors' => [
                    '{{WRAPPER}} .wpforms-submit-container' => 'text-align: {{Value}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submit_margin',
            [
                'label' => __( 'Margin', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wpforms-submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submit_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wpforms-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'submit_typography',
                'selector' => '{{WRAPPER}} .wpforms-submit',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'submit_border',
                'selector' => '{{WRAPPER}} .wpforms-form .wpforms-submit-container button[type=submit]',
            ]
        );

        $this->add_control(
            'submit_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wpforms-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'submit_box_shadow',
                'selector' => '{{WRAPPER}} .wpforms-submit',
            ]
        );

        $this->add_control(
            'hr4',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'submit_color',
            [
                'label' => __( 'Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpforms-submit' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submit_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpforms-submit' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'submit_hover_color',
            [
                'label' => __( 'Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpforms-submit:hover, {{WRAPPER}} .wpforms-submit:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submit_hover_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpforms-submit:hover, {{WRAPPER}} .wpforms-submit:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submit_hover_border_color',
            [
                'label' => __( 'Border Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpforms-submit:hover, {{WRAPPER}} .wpforms-submit:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        if ( ! skt_addons_elementor_is_wpforms_activated() ) {
			skt_addons_elementor_show_plugin_missing_alert( __( 'WPForms', 'skt-addons-elementor' ) );
            return;
        }

        $settings = $this->get_settings_for_display();

        if ( ! empty( $settings['form_id'] ) ) {
            echo skt_addons_elementor_do_shortcode( 'wpforms', [
                'id' => $settings['form_id'],
            ] );
        }
    }
}