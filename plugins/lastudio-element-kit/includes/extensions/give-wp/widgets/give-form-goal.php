<?php

namespace LaStudioKitExtensions\GiveWp\Widgets;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\LaStudioKit_Base;
use Elementor\LaStudioKit_Posts;
use Elementor\Repeater;
use LaStudioKitExtensions\Elementor\Controls\Group_Control_Related as Group_Control_Related;

class GiveFormGoal extends LaStudioKit_Base {

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
		return 'lakit-give-form-goal';
	}

	public function get_widget_title() {
		return __('GiveWP Form Goal', 'lastudio-kit');
	}

	public function get_keywords() {
		return [ 'give', 'donation', 'grid', 'form', 'goal' ];
	}

	protected function set_template_output(){
		return lastudio_kit()->plugin_path('includes/extensions/give-wp/widget-templates');
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

        $this->_add_control(
            'show_text',
            array(
                'type'         => 'switcher',
                'label'        => esc_html__( 'Show Text', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->add_control(
            'raised_text',
            array(
                'label' => esc_html__( 'Raised Text', 'lastudio-kit' ),
                'type'  => Controls_Manager::TEXT,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-goal-progress .raised span:first-child:before' => 'content: "{{VALUE}}";',
                ),
                'condition' => [
                    'show_text' => 'yes'
                ]
            )
        );
        $this->add_control(
            'goal_text',
            array(
                'label' => esc_html__( 'Goal Text', 'lastudio-kit' ),
                'type'  => Controls_Manager::TEXT,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-goal-progress .raised span:before' => 'content: "{{VALUE}}";',
                ),
                'condition' => [
                    'show_text' => 'yes'
                ]
            )
        );
        $this->_add_control(
            'show_progress_bar',
            array(
                'type'         => 'switcher',
                'label'        => esc_html__( 'Show Progress Bar', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_form_goal',
            array(
                'label'     => esc_html__( 'Form Goal', 'lastudio-kit' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'goal_typography',
                'selector' => '{{WRAPPER}} .lakit-goal-progress .raised span',
            )
        );
        $this->_add_control(
            'goal_text_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-goal-progress .raised' => 'color: {{VALUE}}',
                )
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'goal_label_typography',
                'label'    => esc_html__( 'Label Typography', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakit-goal-progress .raised span:before',
            )
        );
        $this->_add_control(
            'goal_label_color',
            array(
                'label'     => esc_html__( 'Label Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-goal-progress .raised span:before' => 'color: {{VALUE}}',
                )
            )
        );
        $this->_add_responsive_control(
            'goal_raise_gap',
            array(
                'label'       => esc_html__( 'Amount Spacing', 'lastudio-kit' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => [ 'px' ],
                'selectors'   => [
                    '{{WRAPPER}} .lakit-goal-progress .raised' => 'gap: {{SIZE}}{{UNIT}};'
                ],
            )
        );
        $this->add_responsive_control(
            'goal_amount_justify',
            [
                'label' => esc_html_x( 'Justify Content', 'Flex Container Control', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html_x( 'Flex Start', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html_x( 'Center', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html_x( 'Flex End', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html_x( 'Space Between', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html_x( 'Space Around', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html_x( 'Space Evenly', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-goal-progress .raised' => 'justify-content: {{VALUE}};',
                ],
            ]
        );
        $this->_add_control(
            'heading__goal_percent',
            [
                'label'       => esc_html__( 'Percent Progress', 'lastudio-kit' ),
                'type'        => Controls_Manager::HEADING,
                'label_block' => true,
                'separator'   => 'before',
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'goal_percent_typography',
                'selector' => '{{WRAPPER}} .progress-percent',
            )
        );
        $this->_add_control(
            'goal_percent_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .progress-percent' => 'color: {{VALUE}}',
                )
            )
        );
        $this->_add_control(
            'heading__goal_processbar',
            [
                'label'       => esc_html__( 'Progress Bar', 'lastudio-kit' ),
                'type'        => Controls_Manager::HEADING,
                'label_block' => true,
                'separator'   => 'before',
            ]
        );
        $this->_add_responsive_control(
            'goal_processbar_height',
            array(
                'label'       => esc_html__( 'Process bar height', 'lastudio-kit' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => [ 'px' ],
                'selectors'   => [
                    '{{WRAPPER}}' => '--lakit-progress-bar-height: {{SIZE}}{{UNIT}};'
                ],
            )
        );
        $this->_add_responsive_control(
            'goal_processbar_radius',
            array(
                'label'       => esc_html__( 'Border radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => [ 'px' ],
                'selectors'   => [
                    '{{WRAPPER}}' => '--lakit-progress-bar-radius: {{SIZE}}{{UNIT}};'
                ],
            )
        );
        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'goal_processbar_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .give-progress-bar',
                'fields_options' => [
                    'background' => [
                        'label' => esc_html__('Normal Background', 'pixel-gallery')
                    ]
                ],
                'exclude'  => array(
                    'image',
                    'position',
                    'xpos',
                    'ypos',
                    'attachment',
                    'attachment_alert',
                    'repeat',
                    'size',
                    'bg_width'
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'goal_processbar_active_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .give-progress-bar span',
                'fields_options' => [
                    'background' => [
                        'label' => esc_html__('Active Background', 'pixel-gallery')
                    ]
                ],
                'exclude'  => array(
                    'image',
                    'position',
                    'xpos',
                    'ypos',
                    'attachment',
                    'attachment_alert',
                    'repeat',
                    'size',
                    'bg_width'
                ),
            )
        );
        $this->_add_responsive_control(
            'goal_processbar_margin',
            array(
                'label'      => esc_html__( 'Progress Bar Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-goal-progress .give-progress-bar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'goal_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-goal-progress' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator'   => 'before',
            )
        );

        $this->_add_responsive_control(
            'goal_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-goal-progress' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'goal_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .lakit-goal-progress',
            )
        );

        $this->_end_controls_section();
    }

    protected function render() {

        $this->_context = 'render';

        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();
    }
}