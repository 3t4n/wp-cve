<?php

namespace LaStudioKitExtensions\GiveWp\Widgets;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\LaStudioKit_Base;

class GiveFormDonate extends LaStudioKit_Base {

	public $css_file_name = 'givewp.min.css';

	protected function enqueue_addon_resources(){
		$this->add_script_depends( 'jquery-isotope' );
		if(!lastudio_kit_settings()->is_combine_js_css()) {
			$this->add_script_depends( 'lastudio-kit-base' );
			if(!lastudio_kit()->is_optimized_css_mode()) {
				wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/' . $this->css_file_name ), null, lastudio_kit()->get_version() );
				$this->add_style_depends( $this->get_name() );
			}
		}
	}

	public function get_widget_css_config($widget_name){

        $css_file_name = $this->css_file_name;

		$file_url = lastudio_kit()->plugin_url(  'assets/css/addons/' . $css_file_name );
		$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/' . $css_file_name );

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
		return 'lakit-give-form-donate';
	}

	public function get_widget_title() {
		return __('GiveWP Form Donate', 'lastudio-kit');
	}

	public function get_keywords() {
		return [ 'give', 'donation', 'grid', 'form', 'goal' ];
	}

    protected function register_controls()
    {
        $this->_start_controls_section(
            'section_meta',
            [
                'label' => __( 'Settings', 'lastudio-kit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'form_id',
            [
                'label' =>  esc_html__( 'Form ID', 'lastudio-kit' ),
                'type' => 'lastudiokit-query',
                'options' => [],
                'label_block' => true,
                'autocomplete' => [
                    'object' => 'post',
                    'query' => [
                        'post_type' => [ 'give_forms' ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'display_style',
            [
                'label' => __( 'Form Display Style', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'description' => __( 'Choose which display to use for this GiveWP form.', 'lastudio-kit' ),
                'options' => [
                    'onpage' => __('Full Form','lastudio-kit'),
                    'button' => __('Button Only', 'lastudio-kit')
                ],
                'default' => 'onpage'
            ]
        );

        $this->add_control(
            'continue_button_title',
            [
                'label' => __( 'Reveal Button Text', 'lastudio-kit' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => __( 'Text on the button that reveals the form.', 'lastudio-kit' ),
                'default' => __('Continue to Donate', 'lastudio-kit'),
                'condition' => [
                    'display_style!' => 'onpage',
                ]
            ]
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_action_button',
            array(
                'label'     => esc_html__( 'Button', 'lastudio-kit' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->_add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->_add_responsive_control(
            'text_padding',
            [
                'label' => __( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_start_controls_tabs( 'tabs_button_style' );

        $this->_start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'button_text_color',
            [
                'label' => __( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_control(
            'icon_color',
            [
                'label' => __( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-button .elementor-button-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'label' => __( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .elementor-button',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ]
                ],
            ]
        );

        $this->_add_control(
            'border_radius',
            [
                'label' => __( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            \LaStudioKitExtensions\Elementor\Controls\Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'button_box_shadow',
                'selector'  => '{{WRAPPER}} .elementor-button'
            ]
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'hover_color',
            [
                'label' => __( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_control(
            'hover_icon_color',
            [
                'label' => __( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover .elementor-button-icon, {{WRAPPER}} .elementor-button:focus .elementor-button-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );


        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'button_background_hover',
                'label' => __( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                ],
            ]
        );

        $this->_add_control(
            'button_hover_border_color',
            [
                'label' => __( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_control(
            'border_radius_hover',
            [
                'label' => __( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            \LaStudioKitExtensions\Elementor\Controls\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow_hover',
                'selector' => '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus',
            ]
        );

        $this->_add_control(
            'hover_animation',
            [
                'label' => __( 'Hover Animation', 'lastudio-kit' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();


        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_form_style',
            array(
                'label'     => esc_html__( 'Form Style', 'lastudio-kit' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->_end_controls_section();
    }

    protected function render() {
        $form_id = $this->get_settings_for_display('form_id');
        $display_style = $this->get_settings_for_display('display_style');
        if(empty($form_id)){
            $form_id = get_the_ID();
        }
        if($display_style === 'button'){
            $donate_text = $this->get_settings_for_display('continue_button_title');
            echo sprintf(
                '<button type="button" class="elementor-button lakit-posts__btn-donate" data-id="%3$s"><span class="btn__text">%1$s</span>%2$s</button>',
                $donate_text,
                $this->_get_icon('donate_icon', '<span class="lakit-btn-more-icon">%s</span>'),
                esc_attr($form_id)
            );
            echo '<div class="mfp-hide give-donation-grid-item-form lakit-give-form-modal give-modal--slide" data-id="'.esc_attr($form_id).'">';
        }
        else{
            echo '<div class="lakit-give-form-modal" data-id="'.esc_attr($form_id).'">';
        }
        echo give_form_shortcode(
            [
                'id' => $form_id,
                'display_style' => 'onpage',
                'show_title' => 'false',
                'show_goal' => 'false',
                'show_content' => 'none',
            ]
        );
        echo '</div>';
    }
}