<?php


use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;

class wps_tesimonial_slide_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'wps_tesimonial_slide';
    }

    public function get_title()
    {
        return __('Testimonial Slide', 'wpssupport');
    }

    public function get_icon()
    {
        return 'eicon-testimonial-carousel';
    }

    public function get_keywords()
    {
        return ['wps', 'Testimonial'];
    }

    public function get_categories()
    {
        return ['wpsection_category'];
    }



    protected function register_controls()
    {
        $this->start_controls_section(
            'testimonial',
            [
                'label' => esc_html__('Testimonial', 'Testimonial'),
            ]
        );
        $this->add_control(
            'sec_class',
            [
                'label'       => __('Section Class', 'Testimonial'),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __('Enter Section Class', 'Testimonial'),
            ]
        );
        $this->add_control(
            'wps_columns',
            array(
                'label' => __('Columns Settings', 'wpsection'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '1'  => __('1 Column', 'wpsection'),
                    '2' => __('2 Columns', 'wpsection'),
                    '3' => __('3 Columns', 'wpsection'),
                    '4' => __('4 Columns', 'wpsection'),
                    '5' => __('5 Columns', 'wpsection'),
                ],
            )
        );
		    $this->add_control(
            'testi_wps_columns_tab',
            array(
                'label' => __( 'Tab Columns Settings', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '1'  => __( '1 Column', 'wpsection' ),
                    '2' => __( '2 Columns', 'wpsection' ),
                    '3' => __( '3 Columns', 'wpsection' ),
                    '4' => __( '4 Columns', 'wpsection' ),
                    '6' => __( '6 Columns', 'wpsection' ),
                ],
            )
        );

				
  $this->add_control(
                'testi_slide_auto_loop',
                 array(
                    'label' => __( 'Show Auto Loop', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'return_value' => '1',
                     'default'      => '0',
                    'placeholder' => __( 'Enable Slider', 'wpsection' ),
                )
    );	
		

        $repeater = new Repeater();
        
	
		
        $repeater->add_control(
            'block_image',
            [
                'label' => __('Image', 'rashid'),
                'type' => Controls_Manager::MEDIA,
                'default' => ['url' => Utils::get_placeholder_image_src(),],
            ]
        );


		
			
        $repeater->add_control(
            'block_title',
            [
                'label' => esc_html__('Name', 'rashid'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Rashid Mahfuz', 'rashid'),
            ]
        );
		
	
		
		
        $repeater->add_control(
            'block_designation',
            [
                'label' => esc_html__('Designation', 'rashid'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('CTO', 'rashid'),
            ]
        );
		
		
		
		
		
        $repeater->add_control(
            'block_text',
            [
                'label' => esc_html__('Text', 'rashid'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Very Good Theme', 'rashid'),
            ]
        );
		
		
		
		
	$repeater->add_control(
            'number_of_stars',
            [
                'label'     => esc_html__('Number of Stars', 'greengia'),
                'type'      => Controls_Manager::SELECT,
                'default'   => '5', 
                'options'   => [
                    '1' => '1 Star',
                    '2' => '2 Stars',
                    '3' => '3 Stars',
                    '4' => '4 Stars',
                    '5' => '5 Stars',
                ],
            ]
        );
		
	
	   $repeater->add_control(
            'alt_text',
            [
                'label' => esc_html__('Thumbnial ALT Text', 'rashid'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Image', 'rashid'),
            ]
        );	
		
        $this->add_control(
            'repeater',
            [
                'label' => esc_html__('Repeater List', 'wpsection'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'list_title' => esc_html__('Title #1', 'wpsection'),
                        'list_content' => esc_html__('Item content. Click the edit button to change this text.', 'wpsection'),
                    ],
                ],
            ]
        );


        $this->end_controls_section();
		
		
// Rating Icon Settings		
        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => esc_html__('Rating Icon Setting', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
		
		
        $this->add_control(
            'show_icon',
            array(
                'label' => esc_html__('Show Icon', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'wpsection'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'wpsection'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .rating' => 'display: {{VALUE}} !important',
                ),
            )
        );


        $this->add_control(
            'wps_rating_color',
            [
                'label'     => esc_html__('Star Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f39c12', 
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .rating i' => 'color: {{VALUE}} !important',
                ),
            ]
        );
        $this->add_control(
            'icon_alingment',
            array(
                'label' => esc_html__('Alignment', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'wpsection'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'wpsection'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'wpsection'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .rating' => 'text-align: {{VALUE}} !important',
                ),
            )
        );
		
	   $this->add_control(
            'wps_testi_icon_margin',
            array(
                'label'     => __('Margin', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,

                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );	
	
   $this->add_control(
            'wps_testi_icon_padding',
            array(
                'label'     => __('Paddings', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,

                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );			
	$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'wps_slider_icon_size',
				'label'    => __( 'Typography', 'wpsection' ),
				'selector' => '{{WRAPPER}}  .wps_testimonials .rating i',
			)
		);

  $this->add_control(
    'testi_order_one',
    [
       'label' => esc_html__('Vertical Order', 'wpsection'),
        'type' => Controls_Manager::TEXT,
        'default' => 1,
    
    ]
);			
	
        $this->end_controls_section();
		
// Testi Text Settings		
		
        $this->start_controls_section(
            'wps_testi_text_settings',
            [
                'label' => __('Text Setting', 'rashid'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
            'wps_testi_show_text',
            array(
                'label' => esc_html__('Show Text', 'ecolab'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'ecolab'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'ecolab'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .wps_testi_text' => 'display: {{VALUE}} !important',
                ),
            )
        );

        $this->add_control(
            'testi_text_alingment',
            array(
                'label' => esc_html__('Alignment', 'ecolab'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'ecolab'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'ecolab'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'ecolab'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .wps_testi_text' => 'text-align: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'testi_text_margin',
            array(
                'label'     => __('Margin', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .wps_testi_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->add_control(
            'testi_text_padding',
            array(
                'label'     => __('Padding', 'ecolab'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .wps_testi_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
		
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'testi_text_typography',
                'label'    => __('Typography', 'ecolab'),
                'selector' => '{{WRAPPER}} .wps_testimonials .wps_testi_text',
            )
        );
        $this->add_control(
            'testi_text_color',
            array(
                'label'     => __('Color', 'ecolab'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .wps_testi_text' => 'color: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'testi_text_hover_color',
            array(
                'label'     => __('Hover Color', 'ecolab'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .wps_testi_text:hover ' => 'color: {{VALUE}} !important',
                ),
            )
        );
		
$this->add_control(
    'testi_order_two',
    [
       'label' => esc_html__('Vertical Order', 'wpsection'),
        'type' => Controls_Manager::TEXT,
        'default' => 2,
    
    ]
);	


        $this->end_controls_section();
		
		
// Test Thumbnail Image		
		
        $this->start_controls_section(
            'wps_thumbnail_control',
            array(
                'label' => __('Image Settings', 'wpsection'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'wps_show_thumbnail',
            array(
                'label' => esc_html__('Show Button', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'wpsection'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'wpsection'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .thumb-alingment' => 'display: {{VALUE}} !important',
                )
            )
        );

		
		
        $this->add_control(
            'testi_slider_width',
            [
                'label' => esc_html__('Thumb Width',  'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_testimonials .thumb-box' => 'width: {{SIZE}}{{UNIT}};',
                ]

            ]
        );
        $this->add_control(
            'testi_thumb_alingment',
            array(
                'label' => esc_html__('Alignment', 'ecolab'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'ecolab'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'ecolab'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'ecolab'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .thumb-alingment' => 'text-align: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'testi_thumbnail_padding',
            array(
                'label'     => __('Padding', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,

                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .wps_testi_thum' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'testi_thumbnail_x_margin',
            array(
                'label'     => __('Margin', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .wps_testi_thum' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'thumbnail_border',
                'selector' => '{{WRAPPER}} .wps_testimonials .wps_testi_thum',
            )
        );

        $this->add_control(
            'test_thumbnail_border_radius',
            array(
                'label'     => __('Border Radius', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .wps_testi_thum' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
		

		
		
$this->add_control(
    'testi_order_three',
    [
        'label' => esc_html__('Vertical Order', 'wpsection'),
        'type' => Controls_Manager::TEXT,
        'default' => 3,
    
    ]
);	
		
		
		
        $this->end_controls_section();
		
		
//Testi Text Settings		
        $this->start_controls_section(
            'section_testimonial_style',
            [
                'label' => esc_html__('Title Setting', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'test_show_title',
            array(
                'label' => esc_html__('Show Title', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'wpsection'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'wpsection'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .wps_testi_title' => 'display: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'testi_title_alingment',
            array(
                'label' => esc_html__('Alignment', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'wpsection'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'wpsection'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'wpsection'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => array(

                    '{{WRAPPER}} .wps_testimonials .wps_testi_title' => 'text-align: {{VALUE}} !important',
                ),
            )
        );


        $this->add_control(
            'testi_title_padding',
            array(
                'label'     => __('Padding', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .wps_testi_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->add_control(
            'testi_title_margin',
            array(
                'label'     => __('Margin', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .wps_testi_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'label'    => __('Typography', 'wpsection'),
                'selector' => '{{WRAPPER}} .wps_testimonials .wps_testi_title',
            )
        );
        $this->add_control(
            'testi_title_color',
            array(
                'label'     => __('Color', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .wps_testi_title' => 'color: {{VALUE}} !important',

                ),
            )
        );

		
		
$this->add_control(
    'testi_order_four',
    [
        'label' => esc_html__('Vertical Order', 'wpsection'),
        'type' => Controls_Manager::TEXT,
        'default' => 4,
    
    ]
);		
		
        $this->end_controls_section();
		
		
		
		
//Designation Settings

        $this->start_controls_section(
            'wps_designation_style',
            [
                'label' => esc_html__('Designation Setting', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'testi_how_designation',
            array(
                'label' => esc_html__('Show Designation', 'ecolabe'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'ecolab'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'ecolab'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .wps_testi_designation' => 'display: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'subtitle_alingment',
            array(
                'label' => esc_html__('Alignment', 'ecolab'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'ecolab'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'ecolab'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'ecolab'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => array(

                    '{{WRAPPER}} .wps_testimonials .wps_testi_designation' => 'text-align: {{VALUE}} !important',
                ),
            )
        );


        $this->add_control(
            'subtitle_padding',
            array(
                'label'     => __('Padding', 'ecolab'),
 
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .wps_testi_designation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'subtitle_typography',
                'label'    => __('Typography', 'ecolab'),
                'selector' => '{{WRAPPER}} .wps_testimonials .wps_testi_designation',
            )
        );
        $this->add_control(
            'subtitle_color',
            array(
                'label'     => __('Color', 'ecolab'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .wps_testi_designation' => 'color: {{VALUE}} !important',

                ),
            )
        );
		
$this->add_control(
    'testi_order_five',
    [
        'label' => esc_html__('Vertical Order', 'wpsection'),
        'type' => Controls_Manager::TEXT,
        'default' => 5,
    
    ]
);		

        $this->end_controls_section();
		
		
		
        $this->start_controls_section(
            'block_settings',
            array(
                'label' => __('Block Setting', 'wpsection'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );


        $this->add_control(
            'show_block',
            array(
                'label' => esc_html__('Show Block', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'wpsection'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'wpsection'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .mr_product_block' => 'display: {{VALUE}} !important',
                ),
            )
        );

        $this->add_control(
            'block_color',
            array(
                'label'     => __('Background Color', 'wpsection'),
                'condition'    => array('show_block' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_product_block' => 'background: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'block_hover_color',
            array(
                'label'     => __('Hover Color', 'wpsection'),
                'condition'    => array('show_block' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_product_block:hover' => 'background: {{VALUE}} !important',
                ),
            )
        );

        $this->add_control(
            'block_margin',
            array(
                'label'     => __('Block Margin', 'wpsection'),
                'condition'    => array('show_block' => 'show'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}}  .mr_product_block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'block_padding',
            array(
                'label'     => __('Block Padding', 'wpsection'),
                'condition'    => array('show_block' => 'show'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}}  .mr_product_block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'block_shadow',
                'condition'    => array('show_block' => 'show'),
                'label' => esc_html__('Box Shadow', 'wpsection'),
                'selector' => '{{WRAPPER}} .mr_product_block',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'block_X_hover_shadow',
                'condition'    => array('show_block' => 'show'),
                'label' => esc_html__('Hover Box Shadow', 'wpsection'),
                'selector' => '{{WRAPPER}} .mr_product_block:hover',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'block_border',
                'condition'    => array('show_block' => 'show'),
                'label' => esc_html__('Box Border', 'wpsection'),
                'selector' => '{{WRAPPER}} .mr_product_block',
            ]
        );

        $this->add_control(
            'block_border_radius',
            array(
                'label'     => __('Border Radius', 'wpsection'),
                'condition'    => array('show_block' => 'show'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}} .mr_product_block' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->end_controls_section();
		
		
//Slider arrow 		
        $this->start_controls_section(
            'slider_path_button_3_control',
            array(
                'label' => __('Slider Arrow  Settings', 'wpsection'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'slider_path_show_button_3',
            array(
                'label' => esc_html__('Show Button', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'wpsection'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'wpsection'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .owl-nav ' => 'display: {{VALUE}} !important',
                ),
            )
        );

        $this->add_control(
            'slider_path_button_3_color',
            array(
                'label'     => __('Button Color', 'wpsection'),
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default' => '#cbcbcb',
                'selectors' => array(
                    '{{WRAPPER}}  .wps_testimonials .owl-nav button' => 'color: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'slider_path_button_3_color_hover',
            array(
                'label'     => __('Button Hover Color', 'wpsection'),
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff ',
                'selectors' => array(
                    '{{WRAPPER}}  .wps_testimonials .owl-nav button:hover' => 'color: {{VALUE}} !important',

                ),
            )
        );
        $this->add_control(
            'slider_path_button_3_bg_color',
            array(
                'label'     => __('Background Color', 'wpsection'),
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default' => '#f3f3f3 ',
                'selectors' => array(
                    '{{WRAPPER}}  .wps_testimonials .owl-nav button' => 'background: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'slider_path_button_3_hover_color',
            array(
                'label'     => __('Background Hover Color', 'wpsection'),
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default' => '#222',
                'selectors' => array(
                    '{{WRAPPER}}  .wps_testimonials .owl-nav button:hover' => 'background: {{VALUE}} !important',
                ),
            )
        );



        $this->add_control(
            'slider_path_dot_3_width',
            [
                'label' => esc_html__('Arraw Width',  'wpsection'),
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_testimonials .owl-nav button' => 'width: {{SIZE}}{{UNIT}};',
                ]

            ]
        );


        $this->add_control(
            'slider_path_dot_3_height',
            [
                'label' => esc_html__('Arraw Height', 'wpsection'),
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_testimonials .owl-nav button' => 'height: {{SIZE}}{{UNIT}};',

                ]
            ]
        );



        $this->add_control(
            'slider_path_button_3_padding',
            array(
                'label'     => __('Padding', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}}  .wps_testimonials .owl-nav button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'slider_path_button_3_margin',
            array(
                'label'     => __('Margin', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}}  .wps_testimonials .owl-nav button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'slider_path_button_3_typography',
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'label'    => __('Typography', 'wpsection'),
                'selector' => '{{WRAPPER}}  .wps_testimonials .owl-nav button',
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'slider_path_border_3',
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'selector' => '{{WRAPPER}} .wps_testimonials .owl-nav button ',
            )
        );


        $this->add_control(
            'slider_path_border_3_radius',
            array(
                'label'     => __('Border Radius', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .owl-nav button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'label' => esc_html__('Box Shadow', 'wpsection'),
                'selector' => '{{WRAPPER}} .wps_testimonials .owl-nav button',
            ]
        );



        $this->add_control(
            'slider_path_horizontal_prev',
            [
                'label' => esc_html__('Horizontal Position Previous',  'wpsection'),
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_testimonials .owl-nav button.owl-prev' => 'left: {{SIZE}}{{UNIT}};',
                ]

            ]
        );
        $this->add_control(
            'slider_path_horizontal_next',
            [
                'label' => esc_html__('Horizontal Position Next', 'wpsection'),
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_testimonials .owl-nav button.owl-next' => 'right: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'slider_path_vertical',
            [
                'label' => esc_html__('Vertical Position', 'wpsection'),
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_testimonials .owl-nav button ' => 'top: {{SIZE}}{{UNIT}};',
                ]
            ]
        );


        $this->end_controls_section();



// Dot Button Setting

        $this->start_controls_section(
            'slider_path_dot_control',
            array(
                'label' => __('Slider Dot  Settings', 'wpsection'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,

            )
        );

        $this->add_control(
            'slider_path_show_dot',
            array(
                'label' => esc_html__('Show Dot', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'wpsection'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'wpsection'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .owl-dots button' => 'display: {{VALUE}} !important',
                ),
            )
        );


        $this->add_control(
            'slider_path_dot_width',
            [
                'label' => esc_html__('Dot Width',  'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'condition'    => array('slider_path_show_dot' => 'show'),
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_testimonials .owl-dots button' => 'width: {{SIZE}}{{UNIT}};',
                ]

            ]
        );

        $this->add_control(
            'slider_path_dot_height',
            [
                'label' => esc_html__('Dot Height', 'wpsection'),
                'condition'    => array('slider_path_show_dot' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_testimonials .owl-dots button ' => 'height: {{SIZE}}{{UNIT}};',

                ]
            ]
        );

        $this->add_control(
            'slider_path_dot_color',
            array(
                'label'     => __('Dot Color', 'wpsection'),

                'type'      => \Elementor\Controls_Manager::COLOR,
                'default' => '#222',
                'condition'    => array('slider_path_show_dot' => 'show'),
                'selectors' => array(
                    '{{WRAPPER}}  .wps_testimonials .owl-dots button' => 'background: {{VALUE}} !important',

                ),
            )
        );
        $this->add_control(
            'slider_path_dot_color_hover',
            array(
                'label'     => __('Dot Hover Color', 'wpsection'),

                'type'      => \Elementor\Controls_Manager::COLOR,
                'condition'    => array('slider_path_show_dot' => 'show'),
                'selectors' => array(
                    '{{WRAPPER}}   .wps_testimonials .owl-dots button:hover' => 'background: {{VALUE}} !important',

                ),
            )
        );
        $this->add_control(
            'slider_path_dot_bg_color',
            array(
                'label'     => __('Active Color', 'wpsection'),

                'type'      => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'condition'    => array('slider_path_show_dot' => 'show'),
                'selectors' => array(
                    '{{WRAPPER}}  .wps_testimonials .owl-dots .active' => 'background: {{VALUE}} !important',
                ),
            )
        );

        $this->add_control(
            'slider_path_dot_padding',
            array(
                'label'     => __('Padding', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,

                'size_units' =>  ['px', '%', 'em'],
                'condition'    => array('slider_path_show_dot' => 'show'),
                'selectors' => array(
                    '{{WRAPPER}}  .wps_testimonials .owl-dots button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'slider_path_dot_margin',
            array(
                'label'     => __('Margin', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,

                'size_units' =>  ['px', '%', 'em'],
                'condition'    => array('slider_path_show_dot' => 'show'),
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .owl-dots button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'slider_path_dot_border',
                'condition'    => array('slider_path_show_dot' => 'show'),
                'selector' => '{{WRAPPER}} .wps_testimonials .owl-dots button',
            )
        );


        $this->add_control(
            'slider_path_dot_radius',
            array(
                'label'     => __('Border Radius', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,

                'size_units' =>  ['px', '%', 'em'],
                'condition'    => array('slider_path_show_dot' => 'show'),
                'selectors' => array(
                    '{{WRAPPER}} .wps_testimonials .owl-dots button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );




        $this->add_control(
            'slider_path_dot_horizontal',
            [
                'label' => esc_html__('Horizontal Position Previous',  'wpsection'),
                'condition'    => array('slider_path_show_dot' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_testimonials .owl-dots' => 'left: {{SIZE}}{{UNIT}};',
                ]

            ]
        );


        $this->add_control(
            'slider_path_dot_vertical',
            [
                'label' => esc_html__('Vertical Position', 'wpsection'),
                'condition'    => array('slider_path_show_dot' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -200,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_testimonials .owl-dots ' => 'top: {{SIZE}}{{UNIT}};',

                ]
            ]
        );


        $this->end_controls_section();
    }

    /**
     * Render button widget output on the frontend.
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $allowed_tags = wp_kses_allowed_html('post');
?>


        <?php
        echo '
        <script>
          jQuery(document).ready(function($)
          {
            //put the js code under this line 
            //three-item-carousel
            if ($(".three-item-carousel").length) {
              $(".three-item-carousel").owlCarousel({
            
				animateOut: "fadeOut",
				animateIn: "fadeIn",
				loop:true,
				margin:0,
				dots: true,
				nav:true,
				singleItem:true,
				smartSpeed: 500,

				autoplay: ' . json_encode($settings['testi_slide_auto_loop'] === '1') . ',
				
				
                navText: ["<span class=\'eicon-arrow-left\'></span>", "<span class=\'eicon-arrow-right\'></span>"],
                responsive:{
                  0:{
                    items:1
                  },
                  480:{
                    items:1
                  },
                  600:{
                    items:' . esc_js($settings['testi_wps_columns_tab']) . '
                  },
                  800:{
                     items:' . esc_js($settings['testi_wps_columns_tab']) . '
                  },
                  1200:{
                    items:' . esc_js($settings['wps_columns']) . '
                  }
                }
              });    		
            }
          });
        </script>';

        ?>


        <section class="wps_testimonials testimonial-section ">
            <div class="auto-container">
                <div class="three-item-carousel owl-carousel slider_path owl-theme  nav-style-one">
                    <?php foreach ($settings['repeater'] as $item) : ?>
                          <div class="testimonial-block-one mr_product_block">
                            <div class="inner-box">
                                <div class="text-box">
									
<div class="wps_order order-<?php echo wp_kses($settings['testi_order_one'], $allowed_tags); ?>">
	<ul class="rating clearfix">
		<?php
		$rating = $item['number_of_stars'];
		$full_stars = $rating > 0 ? $rating : 0;
		$empty_stars = 5 - $full_stars;

		for ($rs = 1; $rs <= $full_stars; $rs++) {
			echo '<li class="mr_star_full"><i class="eicon-star"></i></li>';
		}
		for ($rns = 1; $rns <= $empty_stars; $rns++) {
			echo '<li class="mr_star_empty"><i class="eicon-star-o"></i></li>';
		}
		?>
	</ul>
</div>	
									
<div class="wps_order order-<?php echo wp_kses($settings['testi_order_two'], $allowed_tags); ?>">									
    <p class="wps_testi_text "><?php echo wp_kses($item['block_text'], $allowed_tags); ?></p>
</div>
									
<div class="wps_order order-<?php echo wp_kses($settings['testi_order_three'], $allowed_tags); ?> thumb-alingment">	
	<figure class="thumb-box">
    <?php if (!empty($item['block_image']['id']) && wp_get_attachment_url($item['block_image']['id'])) : ?>
        <img class="wps_testi_thum" src="<?php echo wp_get_attachment_url($item['block_image']['id']); ?>" alt="<?php echo wp_kses($item['alt_text'], $allowed_tags); ?>">
    <?php endif; ?>
</figure>

</div>	
									
<div class="wps_order order-<?php echo wp_kses($settings['testi_order_four'], $allowed_tags); ?>">										
	<h5 class="wps_testi_title"><?php echo wp_kses($item['block_title'], $allowed_tags); ?></h5>
</div>		
	
<div class="wps_order order-<?php echo wp_kses($settings['testi_order_five'], $allowed_tags); ?>">		
	<h6 class="wps_testi_designation"><?php echo wp_kses($item['block_designation'], $allowed_tags); ?></h6>	
</div>									
									
                               
								</div>
                              
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

<?php
    }
}

// Register widget
Plugin::instance()->widgets_manager->register(new \wps_tesimonial_slide_Widget());
