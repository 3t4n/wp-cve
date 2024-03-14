<?php
/**
 * @package Shop Ready
 */
namespace Shop_Ready\base\elementor\slider_controls;
use Elementor\Controls_Manager;

trait Carousol {

    /**
     * Slick Slider Options
     * except one parameter : attributes
     * In Attribute accept five parameter: Slidder settings Title, unique slider slug and  unique element Name, condition and tab
     * @return void
     *
     */
   public function carousol_slider_controls( $atts ) {

        $atts_variable = shortcode_atts(
            [
                'title'        => esc_html__( 'Slider options', 'shopready-elementor-addon' ),
                'slug'         => 'mini_box_style',
                'element_name' => '__shop_ready__',
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

        
         
        $this->add_responsive_control(
            $widget . '_items',
            [
                'label'          => __( 'Visible Product', 'shopready-elementor-addon' ),
                'type'           => Controls_Manager::SELECT,
                'options'        => [
                    '1' => __( '1', 'shopready-elementor-addon' ),
                    '2' => __( '2', 'shopready-elementor-addon' ),
                    '3' => __( '3', 'shopready-elementor-addon' ),
                    '4' => __( '4', 'shopready-elementor-addon' ),
                    '5' => __( '5', 'shopready-elementor-addon' ),
                    '6' => __( '6', 'shopready-elementor-addon' ),
                    '7' => __( '7', 'shopready-elementor-addon' ),
                    '8' => __( '8', 'shopready-elementor-addon' ),
                ],
                'default'        => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
            ]
        );

        $this->add_responsive_control(
            $widget . '_margin',
            [
                'label'      => __( 'Items Gap', 'shopready-elementor-addon' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => ['size' => 10],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
            ]
        );

        $this->add_control(
            $widget . '_responsiveClass',
            [
                'label'        => __( 'Responsive Class', 'shopready-elementor-addon' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => __( 'Yes', 'shopready-elementor-addon' ),
                'label_off'    => __( 'No', 'shopready-elementor-addon' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            $widget . '_smartSpeed',
            [
                'label'      => __( 'Smart Speed', 'shopready-elementor-addon' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => ['size' => 700],
                'range'      => [
                    'min'  => 100,
                    'max'  => 3000,
                    'step' => 1,
                ],
                'size_units' => '',
            ]
        );

        $this->add_control(
            $widget . '_autoplay',
            [
                'label'        => __( 'Autoplay', 'shopready-elementor-addon' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => __( 'Yes', 'shopready-elementor-addon' ),
                'label_off'    => __( 'No', 'shopready-elementor-addon' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            $widget . '_autoplay_speed',
            [
                'label'      => __( 'Autoplay Speed', 'shopready-elementor-addon' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => ['size' => 2000],
                'range'      => [
                    'px' => [
                        'min'  => 500,
                        'max'  => 5000,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'condition'  => [
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
            $widget . '_center',
            [
                'label'        => __( 'Center', 'shopready-elementor-addon' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'label_on'     => __( 'Yes', 'shopready-elementor-addon' ),
                'label_off'    => __( 'No', 'shopready-elementor-addon' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            $widget . '_nav',
            [
                'label'        => __( 'Nav', 'shopready-elementor-addon' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => __( 'Yes', 'shopready-elementor-addon' ),
                'label_off'    => __( 'No', 'shopready-elementor-addon' ),
                'return_value' => 'yes',
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
            $widget . '_navigation_heading',
            [
                'label'     => __( 'Navigation', 'shopready-elementor-addon' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            $widget . '_dots',
            [
                'label'        => __( 'Dots', 'shopready-elementor-addon' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'label_on'     => __( 'Yes', 'shopready-elementor-addon' ),
                'label_off'    => __( 'No', 'shopready-elementor-addon' ),
                'return_value' => 'yes',
            ]
        );

        do_action($widget . '_dots_extra', $this);
        
        $this->add_control(
            $widget . '_arrow_icons',
            [
                'label'       => __( 'Choose Arrow Icons', 'shopready-elementor-addon' ),
                'type'        => Controls_Manager::SELECT,
                'label_block' => true,
                'default'     => 'fa fa-angle-right',
                'options'     => [
                    'fa fa-angle-right'          => __( 'Angle', 'shopready-elementor-addon' ),
                    'fa fa-angle-double-right'   => __( 'Double Angle', 'shopready-elementor-addon' ),
                    'fa fa-chevron-right'        => __( 'Chevron', 'shopready-elementor-addon' ),
                    'fa fa-chevron-circle-right' => __( 'Chevron Circle', 'shopready-elementor-addon' ),
                    'fa fa-arrow-right'          => __( 'Arrow', 'shopready-elementor-addon' ),
                    'fa fa-long-arrow-right'     => __( 'Long Arrow', 'shopready-elementor-addon' ),
                    'fa fa-caret-right'          => __( 'Caret', 'shopready-elementor-addon' ),
                    'fa fa-caret-square-o-right' => __( 'Caret Square', 'shopready-elementor-addon' ),
                    'fa fa-arrow-circle-right'   => __( 'Arrow Circle', 'shopready-elementor-addon' ),
                    'fa fa-arrow-circle-o-right' => __( 'Arrow Circle O', 'shopready-elementor-addon' ),
                    'fa fa-toggle-right'         => __( 'Toggle', 'shopready-elementor-addon' ),
                    'fa fa-hand-o-right'         => __( 'Hand', 'shopready-elementor-addon' ),
                ],
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Carousol Slider Options Rendering Method
     *
     * except two parameter : attributes and  widget settings
     * In Attribute accept three parameter: render wrap name, main slider slug and class names of that slider wrapper
     * @return void
     *
     */

    public function carousol_slider_render( $atts, $settings ) {

        $atts_variable = shortcode_atts(
            [
                'wrap_name'   => 'woo-ready-slider-wrap',
                'slider_slug' => 'woo-ready-slider',
                'class'       => [],
            ], $atts );

        extract( $atts_variable );

        $widget = $this->get_name() . '_' . shop_ready_heading_camelize( $slider_slug );

        $items                 = $widget . '_items';
        $items_tablet          = $widget . '_items_tablet';
        $items_mobile          = $widget . '_items_mobile';
        $margin                = $widget . '_margin';
        $margin_tablet         = $widget . '_margin_tablet';
        $margin_mobile         = $widget . '_margin_mobile';
        $responsiveClass       = $widget . '_responsiveClass';
        $smartSpeed            = $widget . '_smartSpeed';
        $autoplay              = $widget . '_autoplay';
        $autoplay_speed        = $widget . '_autoplay_speed';
        $pause_on_hover        = $widget . '_pause_on_hover';
        $center                = $widget . '_center';
        $nav                   = $widget . '_nav';
        $infinite_loop         = $widget . '_infinite_loop';
        $grab_cursor           = $widget . '_grab_cursor';
        $dots                  = $widget . '_dots';
        $icons                 = $widget . '_arrow_icons';

        if ( !empty( $settings[$items] ) ) {
            $this->add_render_attribute( $wrap_name , 'data-items', $settings[ $items ] );
        }
        if ( !empty( $settings[$items_tablet] ) ) {
            $this->add_render_attribute( $wrap_name , 'data-items-tablet', $settings[ $items_tablet ] );
        }
        if ( !empty( $settings[$items_mobile] ) ) {
            $this->add_render_attribute( $wrap_name , 'data-items-mobile', $settings[ $items_mobile ] );
        }

        if ( !empty( $settings[$margin]['size'] ) ) {
            $this->add_render_attribute( $wrap_name , 'data-margin', $settings[$margin]['size'] );
        }
        if ( !empty( $settings[$margin_tablet]['size'] ) ) {
            $this->add_render_attribute( $wrap_name , 'data-margin-tablet', $settings[$margin_tablet]['size'] );
        }
        if ( !empty( $settings[$margin_mobile]['size'] ) ) {
            $this->add_render_attribute( $wrap_name , 'data-margin-mobile', $settings[$margin_mobile]['size'] );
        }

        if ( $settings[ $responsiveClass ] == 'yes' ) {
            $this->add_render_attribute( $wrap_name, 'data-responsiveClass', '1' );
        }


        if ( !empty( $settings[ $smartSpeed ][ 'size' ] ) ) {
            $this->add_render_attribute( $wrap_name , 'data-smartSpeed', $settings[$smartSpeed]['size'] );
        }

        if ( $settings[ $autoplay ] == 'yes' && !empty( $settings[ $autoplay_speed ][ 'size' ] ) ) {
            $this->add_render_attribute( $wrap_name, 'data-autoplay',
                $settings[ $autoplay_speed ][ 'size' ] );
        } else {
            $this->add_render_attribute( $wrap_name, 'data-autoplay', '0' );
        }


        if ( $settings[ $pause_on_hover ] == 'yes' ) {
            $this->add_render_attribute( $wrap_name, 'data-pause-on-hover', '1' );
        }

        if ( $settings[ $center ] == 'yes' ) {
            $this->add_render_attribute( $wrap_name, 'data-center', '1' );
        }

        if ( $settings[ $autoplay ] == 'yes' ) {
            $this->add_render_attribute( $wrap_name, 'data-isautoplay', '1' );
        }

        if ( $settings[ $nav ] == 'yes' ) {
            $this->add_render_attribute( $wrap_name, 'data-nav', '1' );
        }

        if ( $settings[ $infinite_loop ] == 'yes' ) {
            $this->add_render_attribute( $wrap_name, 'data-loop', '1' );
        }

        if ( $settings[ $grab_cursor ] == 'yes' ) {
            $this->add_render_attribute( $wrap_name, 'data-grab-cursor', '1' );
        }

        if ( $settings[ $dots ] == 'yes' ) {
            $this->add_render_attribute( $wrap_name, 'data-dots', '1' );
        }

        if ( $class == '' ) {        
            $this->add_render_attribute( $wrap_name, [ 'class' => $class,] );
        }

        if ( !empty( $settings[ $icons ] ) ) {

            $next_arrow = $settings[ $icons ];

            $prev_arrow = str_replace( "right", "left", $settings[ $icons ] );

            $next_icon = '<i class="'.$next_arrow.'"></i>';
            $prev_icon = '<i class="'.$prev_arrow.'"></i>';

            $this->add_render_attribute( $wrap_name, 'data-icon-next', $next_icon );
            $this->add_render_attribute( $wrap_name, 'data-icon-prev', $prev_icon );
        }

    }

}