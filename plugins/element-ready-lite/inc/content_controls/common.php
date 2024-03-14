<?php

namespace Elementor;

use Elementor\Controls_Manager;

trait Element_ready_common_content {

    
    public function content_text($atts) {
        
        $atts_variable = shortcode_atts(
            array(
                'title'     => esc_html__('Heading','element-ready-lite'),
                'slug'      => '_heading_content',
                'condition' => '',
                'controls'  => [

                    'title_hide' =>   [
                        'label'   => esc_html__( 'Show title', 'element-ready-lite' ),
                        'type'    => \Elementor\Controls_Manager::SELECT,
                        'default' => '',
                        'options' => [
                            ''     => esc_html__( 'Yes', 'element-ready-lite' ),
                            'none' => esc_html__( 'No', 'element-ready-lite' ),
                        ],
                        'selectors' => [
                           '{{WRAPPER}} ' => 'text-align: {{VALUE}};',
                        ],
                    ],
                   
                    'widget_title'=> [
                        'label'   => esc_html__( 'Heading Title', 'element-ready-lite' ),
                        'type'    => \Elementor\Controls_Manager::TEXT,
                        'default' => '',
                    ],
    
                    'widget_content'=> [
                        'label'   => esc_html__( 'Heading Content', 'element-ready-lite' ),
                        'type'    => \Elementor\Controls_Manager::TEXTAREA,
                        'default' => '',
                    ],
    
                    'dropdown' => [
                        'label'        => esc_html__( 'Dropdown', 'element-ready-lite' ),
                        'type'         => \Elementor\Controls_Manager::SWITCHER,
                        'label_on'     => esc_html__( 'Yes', 'element-ready-lite' ),
                        'label_off'    => esc_html__( 'No', 'element-ready-lite' ),
                        'return_value' => 'yes',
                        'default'      => '',
                    ],
    
                    'website_url' => [
    
                        'label'       => esc_html__( 'Link', 'element-ready-lite' ),
                        'type'        => \Elementor\Controls_Manager::URL,
                        'placeholder' => esc_html__( 'https://your-link.com', 'element-ready-lite' ),
                       
                    ],
    
                    'randge_slider' => [
    
                        'label' => esc_html__( 'Number Range', 'element-ready-lite' ),
                        'type'  => \Elementor\Controls_Manager::SLIDER,
                        'range' => [
                            'px' => [
                                'min'  => 0,
                                'max'  => 1000,
                                'step' => 1,
                            ],
                           
                        ],
                      
                    ],
    
                    'icons'=> [
                        'label' => esc_html__( 'Icon', 'element-ready-lite' ),
                        'type'  => \Elementor\Controls_Manager::ICONS,
                    ],
    
                    'media' => [
                        'label'   => esc_html__( 'Choose Image', 'element-ready-lite' ),
                        'type'    => \Elementor\Controls_Manager::MEDIA,
                        'default' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                    ],
    
                    'alignment_etx' => [
                        'label'   => esc_html__( 'Alignment', 'element-ready-lite' ),
                        'type'    => \Elementor\Controls_Manager::CHOOSE,
                        'options' => [
                            'left' => [
                                'title' => esc_html__( 'Left', 'element-ready-lite' ),
                                'icon'  => 'fa fa-align-left',
                            ],
                            'center' => [
                                'title' => esc_html__( 'Center', 'element-ready-lite' ),
                                'icon'  => 'fa fa-align-center',
                            ],
                            'right' => [
                                'title' => esc_html__( 'Right', 'element-ready-lite' ),
                                'icon'  => 'fa fa-align-right',
                            ],
                        ],
                        'default' => 'center',
                        'toggle'  => true,
                    ]
                ]
            ), $atts );

        extract($atts_variable);    

        $widget = $this->get_name().'_'.element_ready_heading_camelize($slug);

        $tab_start_section_args =  [
            'label' => $title,
         ];

        if(is_array($condition)){
            $tab_start_section_args['condition'] = $condition;
        }
        
        /*----------------------------
            ELEMENT__content
        -----------------------------*/
        $this->start_controls_section(
            $widget.'_content_section',
            $tab_start_section_args
        );
        
        if(is_array($controls)){
             
            foreach($controls as $control_key => $control_item){
             
                if(isset($control_item['responsive'])){
                    $this->add_responsive_control(
                        $control_key,
                        $control_item
                    );
                }else{
                    $this->add_control(
                        $control_key,
                        $control_item
                    );
                }  
              
            }
            
        }
     
        
        $this->end_controls_section();
        /*----------------------------
            ELEMENT__STYLE END
        -----------------------------*/
    }

    public function slick_slider_option(){
         /*----------------------------------
            CAROUSEL SETTING
        ------------------------------------*/
        $this->start_controls_section(
            'slider_option',
            [
                'label'     => esc_html__( 'Carousel Option', 'element-ready-lite' ),
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

            $this->add_control(
                'slitems',
                [
                    'label'     => esc_html__( 'Slider Items', 'element-ready-lite' ),
                    'type'      => Controls_Manager::NUMBER,
                    'min'       => 1,
                    'max'       => 20,
                    'step'      => 1,
                    'default'   => 3,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slrows',
                [
                    'label'     => esc_html__( 'Slider Rows', 'element-ready-lite' ),
                    'type'      => Controls_Manager::NUMBER,
                    'min'       => 1,
                    'max'       => 5,
                    'step'      => 1,
                    'default'   => 0,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_responsive_control(
                'slitemmargin',
                [
                    'label'     => esc_html__( 'Slider Item Margin', 'element-ready-lite' ),
                    'type'      => Controls_Manager::NUMBER,
                    'min'       => 0,
                    'max'       => 100,
                    'step'      => 1,
                    'default'   => 1,
                    'selectors'  => [
                        '{{WRAPPER}} .element__ready__single__post' => 'margin: calc( {{VALUE}}px / 2 );',
                        '{{WRAPPER}} .column-item' => 'margin: calc( {{VALUE}}px / 2 );',
                        '{{WRAPPER}} .slick-list' => 'margin: calc( -{{VALUE}}px / 2 );',
                    ],
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slarrows',
                [
                    'label'        => esc_html__( 'Slider Arrow', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator'    => 'before',
                    'default'      => 'yes',
                    'condition'    => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'nav_position',
                [
                    'label'   => esc_html__( 'Arrow Position', 'element-ready-lite' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'outside_vertical_center_nav',
                    'options' => [
                        'inside_vertical_center_nav'  => esc_html__( 'Inside Vertical Center', 'element-ready-lite' ),
                        'outside_vertical_center_nav' => esc_html__( 'Outside Vertical Center', 'element-ready-lite' ),
                        'top_left_nav'                => esc_html__( 'Top Left', 'element-ready-lite' ),
                        'top_center_nav'              => esc_html__( 'Top Center', 'element-ready-lite' ),
                        'top_right_nav'               => esc_html__( 'Top Right', 'element-ready-lite' ),
                        'bottom_left_nav'             => esc_html__( 'Bottom Left', 'element-ready-lite' ),
                        'bottom_center_nav'           => esc_html__( 'Bottom Center', 'element-ready-lite' ),
                        'bottom_right_nav'            => esc_html__( 'Bottom Right', 'element-ready-lite' ),
                    ],
                    'condition' => [
                        'slarrows' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slprevicon',
                [
                    'label'     => esc_html__( 'Previous icon', 'element-ready-lite' ),
                    'type'      => Controls_Manager::ICON,
                    'label_block' => true,
                    'default'   => 'fa fa-angle-left',
                    'condition' => [
                        'slider_on' => 'yes',
                        'slarrows'  => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slnexticon',
                [
                    'label'     => esc_html__( 'Next icon', 'element-ready-lite' ),
                    'type'      => Controls_Manager::ICON,
                    'label_block' => true,
                    'default'   => 'fa fa-angle-right',
                    'condition' => [
                        'slider_on' => 'yes',
                        'slarrows'  => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'nav_visible',
                [
                    'label'   => esc_html__( 'Arrow Visibility', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'visibility:visible;opacity:1;',
                    'default'   => 'no',
                    'selectors'  => [
                        '{{WRAPPER}} .owl-nav > div' => '{{VALUE}}',
                    ],
                    'condition'   => [
                        'slarrows' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'sldots',
                [
                    'label'        => esc_html__( 'Slider dots', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator'    => 'before',
                    'default'      => 'no',
                    'condition'    => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slpause_on_hover',
                [
                    'type'         => Controls_Manager::SWITCHER,
                    'label_off'    => esc_html__('No', 'element-ready-lite'),
                    'label_on'     => esc_html__('Yes', 'element-ready-lite'),
                    'return_value' => 'yes',
                    'separator'    => 'before',
                    'default'      => 'yes',
                    'label'        => esc_html__('Pause on Hover?', 'element-ready-lite'),
                    'condition'    => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slcentermode',
                [
                    'label'        => esc_html__( 'Center Mode', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator'    => 'before',
                    'default'      => 'no',
                    'condition'    => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slcenterpadding',
                [
                    'label'     => esc_html__( 'Center padding', 'element-ready-lite' ),
                    'type'      => Controls_Manager::NUMBER,
                    'min'       => 0,
                    'max'       => 500,
                    'step'      => 1,
                    'default'   => 50,
                    'condition' => [
                        'slider_on'    => 'yes',
                        'slcentermode' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slfade',
                [
                    'label'        => esc_html__( 'Slider Fade', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator'    => 'before',
                    'default'      => 'no',
                    'condition'    => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slfocusonselect',
                [
                    'label'        => esc_html__( 'Focus On Select', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator'    => 'before',
                    'default'      => 'no',
                    'condition'    => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slvertical',
                [
                    'label'        => esc_html__( 'Vertical Slide', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator'    => 'before',
                    'default'      => 'no',
                    'condition'    => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slinfinite',
                [
                    'label'        => esc_html__( 'Infinite', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator'    => 'before',
                    'default'      => 'yes',
                    'condition'    => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slrtl',
                [
                    'label'        => esc_html__( 'RTL Slide', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator'    => 'before',
                    'default'      => 'no',
                    'condition'    => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slautolay',
                [
                    'label'        => esc_html__( 'Slider auto play', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator'    => 'before',
                    'default'      => 'no',
                    'condition'    => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slautoplay_speed',
                [
                    'label'     => esc_html__('Autoplay speed', 'element-ready-lite'),
                    'type'      => Controls_Manager::NUMBER,
                    'default'   => 3000,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );


            $this->add_control(
                'slanimation_speed',
                [
                    'label'     => esc_html__('Autoplay animation speed', 'element-ready-lite'),
                    'type'      => Controls_Manager::NUMBER,
                    'default'   => 300,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slscroll_columns',
                [
                    'label'     => esc_html__('Slider item to scroll', 'element-ready-lite'),
                    'type'      => Controls_Manager::NUMBER,
                    'min'       => 1,
                    'max'       => 10,
                    'step'      => 1,
                    'default'   => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'heading_tablet',
                [
                    'label'     => esc_html__( 'Tablet', 'element-ready-lite' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'after',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'sltablet_display_columns',
                [
                    'label'     => esc_html__('Slider Items', 'element-ready-lite'),
                    'type'      => Controls_Manager::NUMBER,
                    'min'       => 1,
                    'max'       => 8,
                    'step'      => 1,
                    'default'   => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'sltablet_scroll_columns',
                [
                    'label'     => esc_html__('Slider item to scroll', 'element-ready-lite'),
                    'type'      => Controls_Manager::NUMBER,
                    'min'       => 1,
                    'max'       => 8,
                    'step'      => 1,
                    'default'   => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'sltablet_width',
                [
                    'label'       => esc_html__('Tablet Resolution', 'element-ready-lite'),
                    'description' => esc_html__('The resolution to tablet.', 'element-ready-lite'),
                    'type'        => Controls_Manager::NUMBER,
                    'default'     => 750,
                    'condition'   => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'heading_mobile',
                [
                    'label'     => esc_html__( 'Mobile Phone', 'element-ready-lite' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'after',
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slmobile_display_columns',
                [
                    'label'     => esc_html__('Slider Items', 'element-ready-lite'),
                    'type'      => Controls_Manager::NUMBER,
                    'min'       => 1,
                    'max'       => 4,
                    'step'      => 1,
                    'default'   => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slmobile_scroll_columns',
                [
                    'label'     => esc_html__('Slider item to scroll', 'element-ready-lite'),
                    'type'      => Controls_Manager::NUMBER,
                    'min'       => 1,
                    'max'       => 4,
                    'step'      => 1,
                    'default'   => 1,
                    'condition' => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slmobile_width',
                [
                    'label'       => esc_html__('Mobile Resolution', 'element-ready-lite'),
                    'description' => esc_html__('The resolution to mobile.', 'element-ready-lite'),
                    'type'        => Controls_Manager::NUMBER,
                    'default'     => 480,
                    'condition'   => [
                        'slider_on' => 'yes',
                    ]
                ]
            );

        $this->end_controls_section();
        /*-----------------------
            SLIDER OPTIONS END
        -------------------------*/
    }

    public function slick_settings($settings){
            // Slider options
            if( $settings['slider_on'] == 'yes' ){

                $this->add_render_attribute( 'element_ready_post_slider_attr', 'class', 'element-ready-carousel-activation' );

                $slideid = rand(2564,1245);

                $slider_settings = [
                    'slideid'          => $slideid,
                    'arrows'          => ('yes' === $settings['slarrows']),
                    'arrow_prev_txt'  => $settings['slprevicon'],
                    'arrow_next_txt'  => $settings['slnexticon'],
                    'dots'            => ('yes' === $settings['sldots']),
                    'autoplay'        => ('yes' === $settings['slautolay']),
                    'autoplay_speed'  => absint($settings['slautoplay_speed']),
                    'animation_speed' => absint($settings['slanimation_speed']),
                    'pause_on_hover'  => ('yes' === $settings['slpause_on_hover']),
                    'center_mode'     => ( 'yes' === $settings['slcentermode']),
                    'center_padding'  => absint($settings['slcenterpadding']),
                    'rows'            => absint($settings['slrows']),
                    'fade'            => ( 'yes' === $settings['slfade']),
                    'focusonselect'   => ( 'yes' === $settings['slfocusonselect']),
                    'vertical'        => ( 'yes' === $settings['slvertical']),
                    'rtl'             => ( 'yes' === $settings['slrtl']),
                    'infinite'        => ( 'yes' === $settings['slinfinite']),
                ];

                $slider_responsive_settings = [
                    'display_columns'        => $settings['slitems'],
                    'scroll_columns'         => $settings['slscroll_columns'],
                    'tablet_width'           => $settings['sltablet_width'],
                    'tablet_display_columns' => $settings['sltablet_display_columns'],
                    'tablet_scroll_columns'  => $settings['sltablet_scroll_columns'],
                    'mobile_width'           => $settings['slmobile_width'],
                    'mobile_display_columns' => $settings['slmobile_display_columns'],
                    'mobile_scroll_columns'  => $settings['slmobile_scroll_columns'],

                ];

                $slider_settings = array_merge( $slider_settings, $slider_responsive_settings );

                $this->add_render_attribute( 'element_ready_post_slider_attr', 'data-settings', wp_json_encode( $slider_settings ) );
            }
    }

}