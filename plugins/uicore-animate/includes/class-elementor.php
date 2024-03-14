<?php

namespace UiCoreAnimate;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;

/**
 * Scripts and Styles Class
 */
class Elementor
{
    function __construct()
    {
        // Register new custom animations
        add_filter('elementor/controls/animations/additional_animations', [$this, 'new_animations'], 4);

        //only if UICORE_VERION is newer than 5.0.7 TODO: remove this check after 6.0.0 is released
        if (!defined('UICORE_VERSION') || (defined('UICORE_VERSION') && version_compare(UICORE_VERSION, '5.0.7', '>='))) {
            // Split text Heading animation
            add_action('elementor/element/heading/section_title_style/after_section_end', [$this, 'split_animation'], 55);
            add_action('elementor/element/text-editor/section_drop_cap/after_section_end', [$this, 'split_animation'], 55);
            add_action('elementor/element/highlighted-text/section_style_text/after_section_end', [$this, 'split_animation'], 55);
            //TODO: ADD uicore-the-title and uicore-page-description widgets

            //Floating Widget
            add_action( 'elementor/element/before_section_end', [ $this, 'register_controls_for_float' ], 10, 3 );

            //Fluid Gradient extender
            add_action( 'elementor/element/section/section_advanced/before_section_start', [$this, 'fluid_gradient_controls'] );
            add_action( 'elementor/element/container/section_background/before_section_end', [$this, 'fluid_gradient_controls'] );

            //required assets for extending 
            add_action('elementor/frontend/section/before_render', [$this, 'should_script_enqueue']);
            add_action('elementor/frontend/container/before_render', [$this, 'should_script_enqueue']);
            add_action('elementor/frontend/widget/before_render', [$this, 'should_script_enqueue']);
            add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_scripts']);
        }
    }

    /**
     * Get all registered scripts
     *
     * @return array
     */
    public static function new_animations($animations)
    {
        $new_animations = [
            'ZoomOut - UiCore Animate' => [
                'zoomOut' => 'Zoom Out',
                'zoomOutDown' => 'Zoom Out Down',
                'zoomOutLeft' => 'Zoom Out Left',
                'zoomOutRight' => 'Zoom Out Right',
                'zoomOutUp' => 'Zoom Out Up',
            ],
        ];

        return \array_merge($animations, $new_animations);
    }

    

    static function split_animation(Controls_Stack $widget)
    {
       
        $widget->start_controls_section(
            'section_ui_split_animation',
            [
                'label' => UICORE_ANIMATE_BADGE . esc_html__('Split Text Animation', 'uicore-animate'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $widget->add_control(
            'ui_animate_split',
            [
                'label'              => esc_html__('Animate by Characters', 'uicore-animate'),
                'type'               => Controls_Manager::SWITCHER,
                'default'            => '',
                'return_value'       => 'ui-split-animate',
                'frontend_available' => true,
                'prefix_class'       => ' ',
                // 'render_type'		 => 'none'
            ]
        );
        $widget->add_control(
            'ui_animate_split_by',
            [
                'label' => __('Split by', 'uicore-animate'),
                'type' => Controls_Manager::SELECT,
                'default' => 'chars',
                'options' => [
                    'chars' => __('Char', 'uicore-animate'),
                    'words' => __('word', 'uicore-animate'),
                    'lines' => __('line', 'uicore-animate'),
                ],
                'frontend_available' => true,
                'condition' => array(
                    'ui_animate_split' => 'ui-split-animate',
                ),
                'prefix_class'       => 'ui-splitby-',
                // 'render_type'		=> 'none'
            ]
        );
        $widget->add_control(
            'ui_animate_split_style',
            [
                'label' => __('Animation', 'uicore-animate'),
                'type' => Controls_Manager::SELECT,
                'default' => 'fadeInUp',
                'options' => Helper::get_split_animations_list(),
                'frontend_available' => true,
                'condition' => array(
                    'ui_animate_split' => 'ui-split-animate',
                ),
                // 'render_type'		=> 'none'
            ]
        );


        $widget->add_control(
            'ui_animate_split_speed',
            [
                'label' => __('Speed', 'uicore-animate'),
                'type' => Controls_Manager::SLIDER,
                'condition' => array(
                    'ui_animate_split' => 'ui-split-animate',
                ),
                'default' => [
                    'unit' => 'px',
                    'size' => 1500,
                ],
                'range' => [
                    'px' => [
                        'min'  => 10,
                        'max'  => 3000,
                        'step' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' => '---ui-speed: {{SIZE}}ms',
                ],
            ]
        );
        $widget->add_control(
            'ui_animate_split_delay',
            [
                'label' => __('Animation Delay', 'uicore-animate'),
                'type' => Controls_Manager::SLIDER,
                'condition' => array(
                    'ui_animate_split' => 'ui-split-animate',
                ),
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1500,
                        'step' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' => '---ui-delay: {{SIZE}}ms',
                ],
            ]
        );
        $widget->add_control(
            'ui_animate_split_stager',
            [
                'label' => __('Stagger', 'uicore-animate'),
                'type' => Controls_Manager::SLIDER,
                'condition' => array(
                    'ui_animate_split' => 'ui-split-animate',
                ),
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min'  => 2,
                        'max'  => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' => '---ui-stagger: {{SIZE}}ms',
                ],
            ]
        );

        $widget->end_controls_section();
    }


    public function enqueue_scripts($type)
    {
        $list = [
            'split' => [
                'script'    => true,
                'style'     => true
            ],
            'fluid' => [
                'script'    => true,
                'style'     => true
            ],
        ];
        if ($type) {
            $list = [$type => $list[$type]];
        }
        foreach ($list as $type => $data) {
            if ($data['script']) {
                wp_enqueue_script('ui-e-' . $type, UICORE_ANIMATE_URL . '/assets/js/' . $type . '.js', ['jquery'], UICORE_ANIMATE_VERSION, true);
            }
            if ($data['style']) {
                wp_enqueue_style('ui-e-' . $type, UICORE_ANIMATE_URL . '/assets/css/' . $type . '.css', [], UICORE_ANIMATE_VERSION, );
            }
        }
    }

    public function should_script_enqueue($widget)
    {
        if ('ui-split-animate' === $widget->get_settings_for_display('ui_animate_split')) {
            $this->enqueue_scripts('split');
        }
        if ('yes' === $widget->get_settings_for_display('section_fluid_on')) {
            $this->enqueue_scripts('fluid');
        }
    }

    function register_controls_for_float($widget, $widget_id, $args)
    {
        static $widgets = [
            'section_effects', /* Section */
        ];

        if (!in_array($widget_id, $widgets)) {
            return;
        }

        $widget->add_control(
            'uicore_enable_float',
            [
                'label'        => UICORE_ANIMATE_BADGE . esc_html__('Floating effect', 'uicore-animate'),
                'description'  => esc_html__('Add a looping up-down animation.', 'uicore-animate'),
                'type'         => Controls_Manager::SWITCHER,
                'separator'    => 'before',
                'default' => '',
                'prefix_class' => 'ui-float-',
                'return_value' => 'widget',
                'frontend_available' => false,
            ]
        );
        $widget->add_control(
            'uicore_float_size',
            [
                'label' => __('Floating height', 'uicore-animate'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    'ui-float-s' => __('Small', 'uicore-animate'),
                    '' => __('Default', 'uicore-animate'),
                    'ui-float-l' => __('Large', 'uicore-animate'),
                ],
                'condition' => array(
                    'uicore_enable_float' => 'widget',
                ),
                'prefix_class' => ' ',
            ]
        );
    }

    /**
     * Fluid Gradient extender
     *
     * @param \Elementor\Controls_Stack $element
     * @param string $section_id
     * @return void
     * @author Andrei Voica <andrei@uicore.co>
     * @since 3.2.1
     */
    function fluid_gradient_controls(Controls_Stack $section)
    {
        $section->start_injection(
            [
                'type' => 'control',
                'at'   => 'after',
                'of'   => 'background_background',
            ]
        );

        $section->add_control(
            'section_fluid_on',
            [
                'label'        => UICORE_ANIMATE_BADGE . esc_html__('Fluid Gradient', 'uicore-animate'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'return_value' => 'yes',
                'description'  => esc_html__('Enable Fluid Gradient background.', 'uicore-animate'),
                'separator'    => ['before'],
                'render_type'  => 'template',
                'frontend_available' => true,
            ]
        );

        $section->add_control(
            'uicore_fluid_animation',
            [
                'label' => __('Animation', 'uicore-animate'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('None', 'uicore-animate'),
                    'ui-fluid-animation-1' => __('Style 1', 'uicore-animate'),
                    'ui-fluid-animation-2' => __('Style 2', 'uicore-animate'),
                    'ui-fluid-animation-3' => __('Style 3', 'uicore-animate'),
                    'ui-fluid-animation-4' => __('Style 4', 'uicore-animate'),
                    'ui-fluid-animation-5' => __('Style 5', 'uicore-animate'),
                    'ui-fluid-animation-6' => __('Style 6', 'uicore-animate'),
                ],
                'condition' => array(
                    'section_fluid_on' => 'yes',
                ),
                'prefix_class' => ' ',
            ]
        );

        $section->add_control(
            'ui_fluid_opacity',
            [
                'label' => __('Opacity', 'uicore-animate'),
                'type' => Controls_Manager::SLIDER,
                'condition' => [
                    'section_fluid_on' => 'yes',
                ],
                'range' => [
                    'px' => [
                        'min'  => 0.05,
                        'max'  => 1,
                        'step' => 0.05,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ui-e-fluid-canvas' => 'opacity: {{SIZE}}',
                ],
            ]
        );

        $section->add_control(
            'section_fluid_color_1',
            [
                'label'     => esc_html__('Color 1', 'uicore-animate'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'section_fluid_on' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ui-e-fluid-canvas' => '--ui-fluid-1: {{VALUE}}',
                ],
            ]
        );
        $section->add_control(
            'section_fluid_color_2',
            [
                'label'     => esc_html__('Color 2', 'uicore-animate'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'section_fluid_on' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ui-e-fluid-canvas' => '--ui-fluid-2: {{VALUE}}',
                ],
            ]
        );
        $section->add_control(
            'section_fluid_color_3',
            [
                'label'     => esc_html__('Color 3', 'uicore-animate'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'section_fluid_on' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ui-e-fluid-canvas' => '--ui-fluid-3: {{VALUE}}',
                ],
            ]
        );
        $section->add_control(
            'section_fluid_color_4',
            [
                'label'     => esc_html__('Color 4', 'uicore-animate'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'section_fluid_on' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ui-e-fluid-canvas' => '--ui-fluid-4: {{VALUE}}',
                ],
            ]
        );

        $section->end_injection();
    }

    public function fluid_gradient_print_template($template)
    {
        $template =     '
        <#
        if ( settings.section_fluid_on === \'yes\' ) {
            if ( settings.uicore_fluid_animation != \'ui-fluid-animation-5\' ) {
            #>
                <div class="ui-fluid-gradient-wrapper">
                    <div class="ui-fluid-gradient"></div>
                </div>
            <# } else {
            #>
            <div class="ui-fluid-gradient-wrapper">
                <canvas id="ui-gradient-canvas-<?php echo $section->get_id(); ?>" data-transition-in />
            </div>
            <# } 
        } 
        #>
            ' . $template;
        return $template;
    }

    public function fluid_gradient_render($section)
    {
        $active = $section->get_settings('section_fluid_on');
        $type = $section->get_settings('uicore_fluid_animation');

        if ('yes' === $active) {
            $section->add_render_attribute('_wrapper', 'class', 'has-ui-fluid-gradient');
            if ($type != 'ui-fluid-animation-5') {
?>
                <div class="ui-fluid-gradient-pre">
                    <div class="ui-fluid-gradient"></div>
                </div>
            <?php
            } else {
            ?>
                <div class="ui-fluid-gradient-pre">
                    <canvas id="ui-gradient-canvas-<?php echo $section->get_id(); ?>" data-transition-in />
                </div>
<?php
            }
        }
    }
}
