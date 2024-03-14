<?php
/**
 * Class: LaStudioKit_Search
 * Name: Search
 * Slug: lakit-search
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class LaStudioKit_Search extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    if(!lastudio_kit()->is_optimized_css_mode()){
			    wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/search.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
			    $this->add_style_depends( $this->get_name() );
		    }
		    $this->add_script_depends( 'lastudio-kit-base' );
	    }
    }

	public function get_widget_css_config($widget_name){
		$file_url = lastudio_kit()->plugin_url( 'assets/css/addons/search.min.css' );
		$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/search.min.css' );
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
		return 'lakit-search';
	}

	public function get_widget_title() {
		return esc_html__( 'Search', 'lastudio-kit' );
	}

	public function get_icon() {
		return 'lastudio-kit-icon-search';
	}


	protected function register_controls() {

        $this->_start_controls_section(
            'section_search_general_settings',
            array(
                'label' => esc_html__( 'General Settings', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'search_placeholder',
            array(
                'label'   => esc_html__( 'Search Placeholder', 'lastudio-kit' ),
                'default' => esc_html__( 'Search &hellip;', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
            )
        );

        $this->_add_control(
            'show_search_submit',
            array(
                'label'        => esc_html__( 'Show Submit Button', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->_add_control(
            'search_submit_label',
            array(
                'label'     => esc_html__( 'Submit Button Label', 'lastudio-kit' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => '',
                'condition' => array(
                    'show_search_submit' => 'true',
                ),
            )
        );

        $this->_add_advanced_icon_control(
            'search_submit_icon',
            array(
                'label'     => esc_html__( 'Submit Button Icon', 'lastudio-kit' ),
                'type'        => Controls_Manager::ICON,
                'label_block' => false,
                'file'        => '',
                'skin'        => 'inline',
                'default'     => 'lastudioicon-zoom-1',
                'fa5_default' => array(
                    'value'   => 'lastudioicon-zoom-1',
                    'library' => 'lastudioicon',
                ),
                'condition' => array(
                    'show_search_submit' => 'true',
                ),
            )
        );

        $this->_add_control(
            'show_search_in_popup',
            array(
                'label'        => esc_html__( 'Show Search Form in Popup', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'true',
                'default'      => '',
            )
        );

        $this->_add_control(
            'full_screen_popup',
            array(
                'label'        => esc_html__( 'Full Screen Popup', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'true',
                'default'      => '',
                'condition' => array(
                    'show_search_in_popup' => 'true',
                ),
            )
        );

        $this->_add_advanced_icon_control(
            'search_popup_trigger_icon',
            array(
                'label'       => esc_html__( 'Popup Trigger Icon', 'lastudio-kit' ),
                'type'        => Controls_Manager::ICON,
                'label_block' => false,
                'file'        => '',
                'skin'        => 'inline',
                'default'     => 'lastudioicon-zoom-1',
                'fa5_default' => array(
                    'value'   => 'lastudioicon-zoom-1',
                    'library' => 'lastudioicon',
                ),
                'condition' => array(
                    'show_search_in_popup' => 'true',
                ),
            )
        );

        $this->_add_control(
            'search_trigger_label',
            array(
                'label'     => esc_html__( 'Trigger Label', 'lastudio-kit' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => '',
                'condition' => array(
                    'show_search_in_popup' => 'true',
                ),
            )
        );

        $this->_add_advanced_icon_control(
            'search_close_icon',
            array(
                'label'     => esc_html__( 'Popup Close Button Icon', 'lastudio-kit' ),
                'type'        => Controls_Manager::ICON,
                'label_block' => false,
                'file'        => '',
                'skin'        => 'inline',
                'default'     => 'lastudioicon-e-remove',
                'fa5_default' => array(
                    'value'   => 'lastudioicon-e-remove',
                    'library' => 'lastudioicon',
                ),
                'condition' => array(
                    'show_search_in_popup' => 'true',
                ),
            )
        );

        $this->_add_control(
            'popup_show_effect',
            array(
                'label'   => esc_html__( 'Show Effect', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'none'      => esc_html__( 'None', 'lastudio-kit' ),
                    'fade'      => esc_html__( 'Fade', 'lastudio-kit' ),
                    'scale'     => esc_html__( 'Scale', 'lastudio-kit' ),
                    'move-up'   => esc_html__( 'Move Up', 'lastudio-kit' ),
                    'move-down' => esc_html__( 'Move Down', 'lastudio-kit' ),
                ),
                'default' => 'move-up',
                'condition' => array(
                    'show_search_in_popup' => 'true',
                ),
            )
        );

        $this->_add_control(
            'search_post_type',
            array(
                'label'   => esc_html__( 'Search Type', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => array(
                    ''    => esc_html__( 'Everything', 'lastudio-kit' )
                ) + lastudio_kit_helper()->get_post_types()
            ),
            25
        );

        $this->_add_control(
            'search_tax_dropdown',
            array(
                'label'   => esc_html__( 'Tax dropdown?', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => array(
                    ''    => esc_html__( 'No', 'lastudio-kit' )
                ) + lastudio_kit_helper()->get_taxonomies_for_options( apply_filters('lastudio-kit/search/option/tax_dropdown', ['post_format', 'product_shipping_class']))
            ),
            25
        );

        $this->_add_control(
            'search_tax_dropdown_opt_all',
            array(
                'label'   => esc_html__( 'All Text', 'lastudio-kit' ),
                'default' => esc_html__( 'All', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'condition' => [
                    'search_tax_dropdown!' => ''
                ]
            )
        );

        $this->end_controls_section();

        $css_scheme = apply_filters(
            'lastudio-kit/search/css-schema',
            array(
                'form'                    => '.lakit-search__form',
                'form_input'              => '.lakit-search__field',
                'form_submit'             => '.lakit-search__submit',
                'form_submit_icon'        => '.lakit-search__submit-icon',
                'popup'                   => '.lakit-search__popup',
                'popup_full_screen'       => '.lakit-search__popup--full-screen',
                'popup_content'           => '.lakit-search__popup-content',
                'popup_close'             => '.lakit-search__popup-close',
                'popup_close_icon'        => '.lakit-search__popup-close-icon',
                'popup_trigger_container' => '.lakit-search__popup-trigger-container',
                'popup_trigger'           => '.lakit-search__popup-trigger',
                'popup_trigger_icon'      => '.lakit-search__popup-trigger-icon',
                'form_dropdown'           => '.lakit-search__dropdown',
            )
        );

        $this->_start_controls_section(
            'section_form_style',
            array(
                'label' => esc_html__( 'Form', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_control(
            'form_style',
            array(
                'label'     => esc_html__( 'Form Style', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING
            ),25
        );

        $this->_add_control(
            'form_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form'] => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'form_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['form'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'form_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['form'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'form_border',
                'label'          => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} ' . $css_scheme['form'],
            ),
            75
        );

        $this->_add_responsive_control(
            'form_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['form'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'form_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['form'],
            ),
            100
        );

        $this->_add_control(
            'form_dropdown_style',
            array(
                'label'     => esc_html__( 'Dropdown Field', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ),
            25
        );
        $this->_add_responsive_control(
            'form_dropdown_width',
            array(
                'label'      => esc_html__( 'Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['form_dropdown'] => 'width: {{SIZE}}{{UNIT}};',
                ),
            ),
            50
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'form_dropdown_typography',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['form_dropdown'],
            ),
            50
        );
        $this->_add_control(
            'form_dropdown_color',
            array(
                'label'  => esc_html__( 'Dropdown Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_dropdown'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );
        $this->_add_responsive_control(
            'form_dropdown_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['form_dropdown'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'form_dropdown_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['form_dropdown'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'form_dropdown_border',
                'label'          => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} ' . $css_scheme['form_dropdown'],
            ),
            75
        );

        $this->_add_responsive_control(
            'form_dropdown_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['form_dropdown'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_control(
            'form_input_style',
            array(
                'label'     => esc_html__( 'Input Field', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ),
            25
        );

		$this->_add_control(
			'form_input_align',
			[
				'label' => __( 'Text Alignment', 'lastudio-kit' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'lastudio-kit' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'lastudio-kit' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'lastudio-kit' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'lastudio-kit' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} ' . $css_scheme['form_input'] => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'form_input_typography',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['form_input'],
            ),
            50
        );

        $this->_start_controls_tabs( 'form_input_tabs' );

        $this->_start_controls_tab(
            'form_input_tab_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'form_input_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_input'] => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'form_input_color',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_input'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'form_input_placeholder_color',
            array(
                'label'  => esc_html__( 'Placeholder Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_input'] . '::-webkit-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['form_input'] . '::-moz-placeholder'          => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['form_input'] . ':-ms-input-placeholder'      => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'form_input_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['form_input'],
            ),
            100
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'form_input_tab_focus',
            array(
                'label' => esc_html__( 'Focus', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'form_input_bg_color_focus',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'form_input_color_focus',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'form_input_placeholder_color_focus',
            array(
                'label'  => esc_html__( 'Placeholder Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus::-webkit-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus::-moz-placeholder'          => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus:-ms-input-placeholder'      => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'form_input_border_color_focus',
            array(
                'label'  => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus' => 'border-color: {{VALUE}}',
                ),
                'condition' => array(
                    'form_input_border_border!' => '',
                ),
            ),75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'form_input_box_shadow_focus',
                'selector' => '{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus',
            ),
            100
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_responsive_control(
            'form_input_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['form_input'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            25
        );

        $this->_add_responsive_control(
            'form_input_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['form_input'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'form_input_border',
                'label'          => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} ' . $css_scheme['form_input'],
            ),
            75
        );

        $this->_add_responsive_control(
            'form_input_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['form_input'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_control(
            'form_submit_style',
            array(
                'label'     => esc_html__( 'Submit Button', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ),
            25
        );

		$this->_add_responsive_control(
			'form_submit_order',
			array(
				'label'   => esc_html__( 'Button Order', 'lastudio-kit' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => -1,
				'max'     => 3,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['form_submit'] => 'order: {{VALUE}};',
				),
			),
			100
		);

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'form_submit_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['form_submit'],
            ),
            50
        );

		$this->_add_responsive_control(
            'form_submit_icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['form_submit_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_start_controls_tabs( 'tabs_form_submit_style' );

        $this->_start_controls_tab(
            'tab_form_submit_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'form_submit_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_submit'] => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'form_submit_color',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_submit'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_form_submit_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'form_submit_bg_color_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_submit'] . ':hover' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'form_submit_color_hover',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_submit'] . ':hover' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'form_submit_hover_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'form_submit_border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['form_submit'] . ':hover' => 'border-color: {{VALUE}};',
                ),
            ),
            75
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_responsive_control(
            'form_submit_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['form_submit'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            25
        );

        $this->_add_responsive_control(
            'form_submit_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['form_submit'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'form_submit_border',
                'label'          => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} ' . $css_scheme['form_submit'],
            ),
            75
        );

        $this->_add_responsive_control(
            'form_submit_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['form_submit'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'form_submit_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['form_submit'],
            ),
            100
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_popup_style',
            array(
                'label'      => esc_html__( 'Popup Box', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition'  => array(
                    'show_search_in_popup' => 'true',
                ),
            )
        );

        $this->_add_responsive_control(
            'popup_width',
            array(
                'label' => esc_html__( 'Popup Content Width', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 1000,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['popup'] . ':not(' . $css_scheme['popup_full_screen'] . ')' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['popup_full_screen'] . ' ' . $css_scheme['popup_content'] => 'width: {{SIZE}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_control(
            'popup_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['popup'] => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'popup_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['popup'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'popup_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['popup'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'popup_border',
                'label'          => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} ' . $css_scheme['popup'],
            ),
            75
        );

        $this->_add_responsive_control(
            'popup_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['popup'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'popup_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['popup'],
            ),
            75
        );

        $this->_add_control(
            'popup_position',
            array(
                'label'     => esc_html__( 'Popup Position', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'full_screen_popup' => '',
                ),
            ),
            25
        );

        $this->_add_control(
            'popup_vert_position',
            array(
                'label'   => esc_html__( 'Vertical Postition by', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => array(
                    'top'    => esc_html__( 'Top', 'lastudio-kit' ),
                    'bottom' => esc_html__( 'Bottom', 'lastudio-kit' ),
                ),
                'condition' => array(
                    'full_screen_popup' => '',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'popup_top_position',
            array(
                'label'      => esc_html__( 'Top Indent', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'popup_vert_position' => 'top',
                    'full_screen_popup'   => '',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['popup'] => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'popup_bottom_position',
            array(
                'label'      => esc_html__( 'Bottom Indent', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'popup_vert_position' => 'bottom',
                    'full_screen_popup'   => '',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['popup'] => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
                ),
            ),
            25
        );

        $this->_add_control(
            'popup_hor_position',
            array(
                'label'   => esc_html__( 'Horizontal Position by', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => array(
                    'left'  => esc_html__( 'Left', 'lastudio-kit' ),
                    'right' => esc_html__( 'Right', 'lastudio-kit' ),
                ),
                'condition' => array(
                    'full_screen_popup' => '',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'popup_left_position',
            array(
                'label'      => esc_html__( 'Left Indent', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'popup_hor_position' => 'left',
                    'full_screen_popup'  => '',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['popup'] => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'popup_right_position',
            array(
                'label'      => esc_html__( 'Right Indent', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'popup_hor_position' => 'right',
                    'full_screen_popup'  => '',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['popup'] => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                ),
            ),
            25
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_popup_trigger_style',
            array(
                'label'      => esc_html__( 'Popup Trigger', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition'  => array(
                    'show_search_in_popup' => 'true',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'search_trigger_label_typography',
                'selector'  => '{{WRAPPER}} .lakit-search__popup-trigger:before',
                'condition'  => array(
                    'search_trigger_label!' => '',
                ),
            ),
            50
        );
        $this->_add_responsive_control(
            'search_trigger_label_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => esc_html__( 'Start', 'lastudio-kit' ),
                        'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
                    ),
                    'top' => array(
                        'title' => esc_html__( 'Top', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'End', 'lastudio-kit' ),
                        'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
                    ),
                    'bottom' => array(
                        'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'selectors_dictionary' => [
                    'top'    => 'flex-direction: column',
                    'bottom' => 'flex-direction: column-reverse',
                    'left' => 'flex-direction: row',
                    'right' => 'flex-direction: row-reverse',
                ],
                'selectors' => array(
                    '{{WRAPPER}} .lakit-search__popup-trigger' => '{{VALUE}}',
                ),
                'condition'  => array(
                    'search_trigger_label!' => '',
                )
            ),
            25
        );

        $this->_add_responsive_control(
            'popup_trigger_icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_start_controls_tabs( 'tabs_popup_trigger_style' );

        $this->_start_controls_tab(
            'tab_popup_trigger_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'popup_trigger_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger'] => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'popup_trigger_color',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_popup_trigger_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'popup_trigger_bg_color_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger'] . ':hover' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'popup_trigger_color_hover',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger'] . ':hover' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'popup_trigger_hover_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'popup_trigger_border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger'] . ':hover' => 'border-color: {{VALUE}};',
                ),
            ),
            75
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_responsive_control(
            'popup_trigger_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Start', 'lastudio-kit' ),
                        'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'End', 'lastudio-kit' ),
                        'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} '  . $css_scheme['popup_trigger_container'] => 'justify-content: {{VALUE}};',
                ),
                'separator' => 'before',
            ),
            25
        );

        $this->_add_responsive_control(
            'popup_trigger_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['popup_trigger'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'popup_trigger_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['popup_trigger'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'popup_trigger_border',
                'label'          => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} ' . $css_scheme['popup_trigger'],
            ),
            75
        );

        $this->_add_responsive_control(
            'popup_trigger_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_trigger'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'popup_trigger_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['popup_trigger'],
            ),
            100
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_popup_close_style',
            array(
                'label'      => esc_html__( 'Popup Close', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition'  => array(
                    'show_search_in_popup' => 'true',
                ),
            )
        );

        $this->_add_responsive_control(
            'popup_close_icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_close_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_start_controls_tabs( 'tabs_popup_close_style' );

        $this->_start_controls_tab(
            'tab_popup_close_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'popup_close_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_close'] => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'popup_close_color',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_close'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_popup_close_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'popup_close_bg_color_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_close'] . ':hover' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'popup_close_color_hover',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_close'] . ':hover' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'popup_close_hover_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'popup_close_border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_close'] . ':hover' => 'border-color: {{VALUE}};',
                ),
            ),
            75
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_responsive_control(
            'popup_close_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['popup_close'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            25
        );

        $this->_add_responsive_control(
            'popup_close_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['popup_close'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'popup_close_border',
                'label'          => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} ' . $css_scheme['popup_close'],
            ),
            75
        );

        $this->_add_responsive_control(
            'popup_close_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['popup_close'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'popup_close_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['popup_close'],
            ),
            100
        );

        $this->_end_controls_section();

	}

	protected function render() {

		$this->_context = 'render';

		if ( lastudio_kit()->get_theme_support( 'lastudio-kit' ) ) {
			$this->add_render_attribute('_wrapper', 'class', 'lakit-ajax-searchform');
		}

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

}
