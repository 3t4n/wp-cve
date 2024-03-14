<?php
/**
 * @package Shop Ready
 */
namespace Shop_Ready\base\elementor\slider_controls;
use Elementor\Controls_Manager;

trait Slick {

    /**
     * Slick Slider Options
     * except one parameter : attributes
     * In Attribute accept five parameter: Slidder settings Title, unique slider slug and  unique element Name, condition and tab
     * @return void
     */

    public function slick_slider_controls( $atts ) {

        $atts_variable = shortcode_atts(
            [
                'title'        => esc_html__( 'Slider options', 'shopready-elementor-addon' ),
                'slug'         => 'mini_box_style',
                'element_name' => '__mangocube__',
                'condition'    => '',
                'tab'          => Controls_Manager::TAB_CONTENT,

            ], $atts );

        extract( $atts_variable );

        $widget = $this->get_name() . '_' . shop_ready_heading_camelize( $slug );

        $tab_start_section_args = [
            'label' => $title,
            'tab'   => $tab,
        ];
        if ( is_array( $condition ) ) {
            $tab_start_section_args['condition'] = $condition;
        }

        $this->start_controls_section(
            $widget . '_slider_options',
            $tab_start_section_args
        );

        $this->add_control(
            $widget . '_slides_per_page',
            [
                'label'   => __( 'Slides Per Item', 'shopready-elementor-addon' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => __( '3', 'shopready-elementor-addon' ),
                'options' => [
                    '1'  => __( '1', 'shopready-elementor-addon' ),
                    '2'  => __( '2', 'shopready-elementor-addon' ),
                    '3'  => __( '3', 'shopready-elementor-addon' ),
                    '4'  => __( '4', 'shopready-elementor-addon' ),
                    '5'  => __( '5', 'shopready-elementor-addon' ),
                    '6'  => __( '6', 'shopready-elementor-addon' ),
                    '7'  => __( '7', 'shopready-elementor-addon' ),
                    '8'  => __( '8', 'shopready-elementor-addon' ),
                    '9'  => __( '9', 'shopready-elementor-addon' ),
                    '10' => __( '10', 'shopready-elementor-addon' ),
                ],
            ]
        );

        $this->add_responsive_control(
            $widget . '_mobile_slides_per_page',
            [
                'label'   => __( 'Mobile Responsive Item', 'shopready-elementor-addon' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => __( '3', 'shopready-elementor-addon' ),
                'options' => [
                    '1'  => __( '1', 'shopready-elementor-addon' ),
                    '2'  => __( '2', 'shopready-elementor-addon' ),
                    '3'  => __( '3', 'shopready-elementor-addon' ),
                    '4'  => __( '4', 'shopready-elementor-addon' ),
                    '5'  => __( '5', 'shopready-elementor-addon' ),
                    '6'  => __( '6', 'shopready-elementor-addon' ),
                    '7'  => __( '7', 'shopready-elementor-addon' ),
                    '8'  => __( '8', 'shopready-elementor-addon' ),
                    '9'  => __( '9', 'shopready-elementor-addon' ),
                    '10' => __( '10', 'shopready-elementor-addon' ),
                ],
            ]
        );

        $this->add_responsive_control(
            $widget . '_ipad_slides_per_page',
            [
                'label'   => __( 'Ipad Responsive Item', 'shopready-elementor-addon' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => __( '3', 'shopready-elementor-addon' ),
                'options' => [
                    '1'  => __( '1', 'shopready-elementor-addon' ),
                    '2'  => __( '2', 'shopready-elementor-addon' ),
                    '3'  => __( '3', 'shopready-elementor-addon' ),
                    '4'  => __( '4', 'shopready-elementor-addon' ),
                    '5'  => __( '5', 'shopready-elementor-addon' ),
                    '6'  => __( '6', 'shopready-elementor-addon' ),
                    '7'  => __( '7', 'shopready-elementor-addon' ),
                    '8'  => __( '8', 'shopready-elementor-addon' ),
                    '9'  => __( '9', 'shopready-elementor-addon' ),
                    '10' => __( '10', 'shopready-elementor-addon' ),
                ],
            ]
        );

        $this->add_control(
            $widget . '_slider_speed',
            [
                'label'       => __( 'Speed', 'shopready-elementor-addon' ),
                'description' => __( 'Duration of transition between slides (in ms)',
                    'shopready-elementor-addon' ),
                'type'        => Controls_Manager::SLIDER,
                'default'     => ['size' => 400],
                'range'       => [
                    'min'  => 100,
                    'max'  => 3000,
                    'step' => 1,
                ],
                'size_units'  => '',
            ]
        );

        $this->add_control(
            $widget . '_autoplay',
            [
                'label'        => __( 'Autoplay', 'shopready-elementor-addon' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'shopready-elementor-addon' ),
                'label_off'    => __( 'No', 'shopready-elementor-addon' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            $widget . '_autoplay_speed',
            [
                'label'     => __( 'Autoplay Speed', 'shopready-elementor-addon' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => ['size' => 2000],
                'range'     => [
                    'min'  => 500,
                    'max'  => 5000,
                    'step' => 1,
                ],
                'condition' => [
                    $widget . '_autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            $widget . '_pause_on_hover',
            [
                'label'        => __( 'Pause On Hover', 'shopready-elementor-addon' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => __( 'Yes', 'shopready-elementor-addon' ),
                'label_off'    => __( 'No', 'shopready-elementor-addon' ),
                'return_value' => 'yes',
                'condition'    => [
                    $widget . '_autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            $widget . '_pause_on_focus',
            [
                'label'        => __( 'Pause on Focus', 'shopready-elementor-addon' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'label_on'     => __( 'Yes', 'shopready-elementor-addon' ),
                'label_off'    => __( 'No', 'shopready-elementor-addon' ),
                'return_value' => 'yes',
                'condition'    => [
                    $widget . '_autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            $widget . '_infinite_loop',
            [
                'label'        => __( 'Infinite Loop', 'shopready-elementor-addon' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => __( 'Yes', 'shopready-elementor-addon' ),
                'label_off'    => __( 'No', 'shopready-elementor-addon' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            $widget . '_grab_cursor',
            [
                'label'        => __( 'Grab Cursor', 'shopready-elementor-addon' ),
                'description'  => __( 'Shows grab cursor when you hover over the slider',
                    'shopready-elementor-addon' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => __( 'Show', 'shopready-elementor-addon' ),
                'label_off'    => __( 'Hide', 'shopready-elementor-addon' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            $widget . '_slides_to_scroll',
            [
                'label'   => __( 'Slides To Scroll', 'shopready-elementor-addon' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => __( '1', 'shopready-elementor-addon' ),
                    '2' => __( '2', 'shopready-elementor-addon' ),
                    '3' => __( '3', 'shopready-elementor-addon' ),
                    '4' => __( '4', 'shopready-elementor-addon' ),
                ],
            ]
        );

        $this->add_control(
            $widget . '_arrows',
            [
                'label'        => __( 'Arrows', 'shopready-elementor-addon' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'label_on'     => __( 'Yes', 'shopready-elementor-addon' ),
                'label_off'    => __( 'No', 'shopready-elementor-addon' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            $widget . '_dots',
            [
                'label'        => __( 'Dots', 'shopready-elementor-addon' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => __( 'Yes', 'shopready-elementor-addon' ),
                'label_off'    => __( 'No', 'shopready-elementor-addon' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            $widget . '_center_mode',
            [
                'label'        => __( 'Center Mode', 'shopready-elementor-addon' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'label_on'     => __( 'Yes', 'shopready-elementor-addon' ),
                'label_off'    => __( 'No', 'shopready-elementor-addon' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            $widget . '_center_padding',
            [
                'label'     => __( 'Center Padding', 'shopready-elementor-addon' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => ['size' => 2000],
                'range'     => [
                    'min'  => 1,
                    'max'  => 500,
                    'step' => 1,
                ],
                'condition' => [
                    $widget . '_center_mode' => 'yes',
                ],
            ]
        );

        $this->add_control(
            $widget . '_fade',
            [
                'label'        => __( 'Fade', 'shopready-elementor-addon' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'label_on'     => __( 'Yes', 'shopready-elementor-addon' ),
                'label_off'    => __( 'No', 'shopready-elementor-addon' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            $widget . '_vertical',
            [
                'label'        => __( 'Vertical', 'shopready-elementor-addon' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'label_on'     => __( 'Yes', 'shopready-elementor-addon' ),
                'label_off'    => __( 'No', 'shopready-elementor-addon' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            $widget . '_vertical_swiping',
            [
                'label'        => __( 'Vertical Swiping', 'shopready-elementor-addon' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'label_on'     => __( 'Yes', 'shopready-elementor-addon' ),
                'label_off'    => __( 'No', 'shopready-elementor-addon' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            $widget . '_focus_on_select',
            [
                'label'        => __( 'Focus On Select', 'shopready-elementor-addon' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'label_on'     => __( 'Yes', 'shopready-elementor-addon' ),
                'label_off'    => __( 'No', 'shopready-elementor-addon' ),
                'return_value' => 'yes',
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Slick Slider Options Rendering Method
     *
     * except two parameter : attributes and  widget settings
     * In Attribute accept three parameter: render wrap name, main slider slug and class names of that slider wrapper
     * @return void
     *
     */

    public function slick_slider_render( $atts, $settings ) {

        $atts_variable = shortcode_atts(
            [
                'wrap_name'   => 'woo-ready-slider-wrap',
                'slider_slug' => 'woo-ready-slider',
                'class'       => [],
            ], $atts );

        extract( $atts_variable );

        $widget = $this->get_name() . '_' . shop_ready_heading_camelize( $slider_slug );

        $slides_per_page        = $widget . '_slides_per_page';
        $slides_ipad_per_page   = $widget . '_ipad_slides_per_page';
        $slides_mobile_per_page = $widget . '_mobile_slides_per_page';
        $slider_speed            = $widget . '_slider_speed';
        $slider_autoplay         = $widget . '_autoplay';
        $slider_autoplay_speed   = $widget . '_autoplay_speed';
        $slider_pause_on_hover   = $widget . '_pause_on_hover';
        $slider_pause_on_focus   = $widget . '_pause_on_focus';
        $slider_loop             = $widget . '_infinite_loop';
        $slider_grab_cursor      = $widget . '_grab_cursor';
        $slider_slides_to_scroll = $widget . '_slides_to_scroll';
        $slider_arrows           = $widget . '_arrows';
        $slider_dots             = $widget . '_dots';
        $center_mode             = $widget . '_center_mode';
        $center_padding          = $widget . '_center_padding';
        $slider_fade             = $widget . '_fade';
        $slider_vertical         = $widget . '_vertical';
        $slider_vertical_swiping = $widget . '_vertical_swiping';
        $slider_focus_on_select  = $widget . '_focus_on_select';

        $data_slides_per_page        = $settings[$slides_per_page] ? $settings[$slides_per_page] : 4;
        $data_mobile_slides_per_page = $settings[$slides_mobile_per_page] ? $settings[$slides_mobile_per_page] : 4;
        $data_slides_ipad_per_page   = $settings[$slides_ipad_per_page] ? $settings[$slides_ipad_per_page] : 3;
        
        $data_speed            = $settings[$slider_speed]['size'] ? $settings[$slider_speed]['size'] : 9999;
        $data_autoplay         = ( $settings[$slider_autoplay] == 'yes' ) ? true : false;
        $data_autoplay_speed   = ( $settings[$slider_autoplay] == 'yes' && !empty( $settings[$slider_autoplay_speed]['size'] ) ) ? $settings[$slider_autoplay_speed]['size'] : 0;
        $data_pause_on_hover   = ( $settings[$slider_pause_on_hover] == 'yes' ) ? true : false;
        $data_pause_on_focus   = ( $settings[$slider_pause_on_focus] == 'yes' ) ? true : false;
        $data_infinite_loop    = ( $settings[$slider_loop] == 'yes' ) ? true : false;
        $data_grab_cursor      = ( $settings[$slider_grab_cursor] == 'yes' ) ? true : false;
        $data_slides_to_scroll = $settings[$slider_slides_to_scroll] ? $settings[$slider_slides_to_scroll] : 1;
        $data_arrows           = ( $settings[$slider_arrows] == 'yes' ) ? true : false;
        $data_dots             = ( $settings[$slider_dots] == 'yes' ) ? true : false;
        $data_center           = ( $settings[$center_mode] == 'yes' ) ? true : false;
        $data_center_padding   = ( $settings[$center_mode] == 'yes' && !empty( $settings[$center_padding]['size'] ) ) ? $settings[$center_padding]['size'] : 0;
        $data_fade             = ( $settings[$slider_fade] == 'yes' ) ? true : false;
        $data_vertical         = ( $settings[$slider_vertical] == 'yes' ) ? true : false;
        $data_vertical_swiping = ( $settings[$slider_vertical_swiping] == 'yes' ) ? true : false;
        $data_focus_on_select  = ( $settings[$slider_focus_on_select] == 'yes' ) ? true : false;
        
        $this->add_render_attribute(
            $wrap_name,
            [
                'class'                      => $class,
                'data-slides-to-show'        => $data_slides_per_page,
                'data-mobile-slides-to-show' => $data_mobile_slides_per_page,
                'data-ipad-slides-to-show'   => $data_slides_ipad_per_page,
                'data-speed'                 => $data_speed,
                'data-autoplay'              => $data_autoplay,
                'data-autoplay-speed'        => $data_autoplay_speed,
                'data-pause-on-hover'        => $data_pause_on_hover,
                'data-pause-on-focus'        => $data_pause_on_focus,
                'data-loop'                  => $data_infinite_loop,
                'data-grab-cursor'           => $data_grab_cursor,
                'data-slides-to-scroll'      => $data_slides_to_scroll,
                'data-arrows'                => $data_arrows,
                'data-dots'                  => $data_dots,
                'data-center'                => $data_center,
                'data-center-padding'        => $data_center_padding,
                'data-fade'                  => $data_fade,
                'data-vertical'              => $data_vertical,
                'data-vertical-swiping'      => $data_vertical_swiping,
                'data-focus-on-select'       => $data_focus_on_select,
            ]
        );

    }

}