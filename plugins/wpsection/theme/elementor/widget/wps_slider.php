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



class wpsection_wpsection_Widget extends \Elementor\Widget_Base {


	public function get_name() {
		return 'wpsection_wpsection';
	}

	public function get_title() {
		return __( 'Basic Slider', 'wpsection' );
	}

	public function get_icon() {
		 return 'eicon-post-slider';
	}

	public function get_keywords() {
		return [ 'wpsection', 'Basic Slider' ];
	}

	public function get_categories() {
      return ['wpsection_category'];
	} 

	
	protected function register_controls() {

		$this->start_controls_section(
			'section_slider_content',
			[
				'label' => esc_html__( 'Content', 'element-path' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);


	
    $this->add_control(
            'style',
            array(
                'label' => __( 'Normal Columns Settings', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'style-1',
                'options' => [
                    'style-1'  => __( 'Default Slider', 'wpsection' ),
                    'style-2' => __( 'Style Two', 'wpsection' ),
                    'style-3' => __( 'Style Three', 'wpsection' ),
                    'style-4' => __( 'Style Four', 'wpsection' ),
                    'style-5' => __( 'Style Five', 'wpsection' ),
                    'style-6' => __( 'Style Six', 'wpsection' ),
                    'style-7' => __( 'Style Seven', 'wpsection' ),
                    'style-8' => __( 'Style Eight', 'wpsection' ),
                    'style-9' => __( 'Style Nine', 'wpsection' ),
                    'style-10' => __( 'Style Ten', 'wpsection' ),
                ],
            )
        );	
		
		
		
		    $this->add_control(
            'hero_wps_columns',
            array(
                'label' => __( 'Normal Columns Settings', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1'  => __( '1 Column', 'wpsection' ),
                    '2' => __( '2 Columns', 'wpsection' ),
                    '3' => __( '3 Columns', 'wpsection' ),
                    '4' => __( '4 Columns', 'wpsection' ),
                    '5' => __( '5 Columns', 'wpsection' ),
                    '6' => __( '6 Columns', 'wpsection' ),
                    '7' => __( '7 Columns', 'wpsection' ),
                    '8' => __( '8 Columns', 'wpsection' ),
                    '9' => __( '9 Columns', 'wpsection' ),
                    '10' => __( '10 Columns', 'wpsection' ),
                ],
            )
        );

    $this->add_control(
            'hero_wps_columns_tab',
            array(
                'label' => __( 'Tab Columns Settings', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
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
                'hero_slide_auto_loop',
                 array(
                    'label' => __( 'Show Auto Loop', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'return_value' => '1',
                     'default'      => '0',
                    'placeholder' => __( 'Enable Slider', 'wpsection' ),
                )
            );		
		
		
$this->add_control(
    'show_image_900',
    array(
        'label' => esc_html__( 'Hide Image under 900px', 'wpsection' ),
        'type' => \Elementor\Controls_Manager::CHOOSE,
        'options' => [
            'show' => [
                'show' => esc_html__( 'Show', 'wpsection' ),
                'icon' => 'eicon-check-circle',
            ],
            'none' => [
                'none' => esc_html__( 'Hide', 'wpsection' ),
                'icon' => 'eicon-close-circle',
            ],
        ],
        'default' => 'show',
        'selectors' => array(
            '{{WRAPPER}} .wps_floating_img_area' => '{{VALUE}}',
        ),
    )
);

// Custom CSS
echo '
<style>
    @media screen and (max-width: 1000px) {
        {{WRAPPER}} .wps_floating_img_area {
            display: none !important;
        }
    }
</style>';


		

		$repeater = new Repeater();

		$repeater->add_control(
			'slider_title', [
				'label'       => esc_html__( 'Title', 'element-path' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'Slider Title',
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		
		
//New Code from Plugin



$repeater->add_control( 
			'slider_type',
			[
				'label' => esc_html__( 'Content type', 'element-path' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'content',
				'options' => [
					'content'  => esc_html__( 'Slider Path', 'element-path' ),
					//'template' => esc_html__( 'Elmntor Template', 'element-path' ),
				],
			]
		);

	

//End of plugin code


	$repeater->add_control(
			'slider_path_image',
			[
				'label'   => esc_html__( 'Select BG Image', 'element-path' ),
				'condition'    => array( 'slider_type' => 'content' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [ 'active' => true ],
				'default' => [
					'url' => WPSECTION_PLUGIN_URL ."assets/images/placeholder.png",
				],
			]
		);

//Title Area	

	   $repeater->add_control(
			'slider_path_title', [
				'label'       => esc_html__( 'Slides Title', 'element-path' ),
				'condition'    => array( 'slider_type' => 'content' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'Slides Title is the Best way to get the Title in Slider', 'element-path' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

	
//SubTitle Area

	    $repeater->add_control(
			'slider_path_subtitle', [
				'label'       => esc_html__( 'Slides Sub Title', 'element-path' ),
				'condition'    => array( 'slider_type' => 'content' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'We are Since 2005', 'element-path' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);


//Text Area		
		 $repeater->add_control(
			'slider_path_text', [
				'label'       => esc_html__( 'Slides Text', 'element-path' ),
				'condition'    => array( 'slider_type' => 'content' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'Get all the Quality Service and Support form us anytime ', 'element-path' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);


//Button One Area
		
		$repeater->add_control(
			'slider_path_button', [
				'label'       => esc_html__( 'Button', 'element-path' ),
				'condition'    => array( 'slider_type' => 'content' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Read More', 'element-path' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'slider_path_link', [
				'label'       => esc_html__( 'Link', 'element-path' ),
				'condition'    => array( 'slider_type' => 'content' ),
				'type'        => Controls_Manager::URL,
			]
		);


//Button Two  Area
		
		$repeater->add_control(
			'slider_path_button_2', [
				'label'       => esc_html__( 'Button Two', 'element-path' ),
				'condition'    => array( 'slider_type' => 'content' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'slider_path_link_2', [
				'label'       => esc_html__( 'Link ', 'element-path' ),
				'condition'    => array( 'slider_type' => 'content' ),
				'type'        => Controls_Manager::URL,
			]
		);


		
		
//Shape Image one
$repeater->add_control(
    'show_slider_image_one',
    array(
        'label'         => __( 'Show Slider Image One', 'wpsection' ),
        'type'          => \Elementor\Controls_Manager::SWITCHER,
        'return_value'  => '1',
        'default'       => '0',
    )
);

$repeater->add_control(
    'slider_path_image_one',
    [
        'label'      => esc_html__( 'Slider Image One', 'wpsection' ),
        'condition'  => ['show_slider_image_one' => '1'],
        'type'       => \Elementor\Controls_Manager::MEDIA,
        'dynamic'    => ['active' => true],
     
    ]
);

$repeater->add_control(
			'slider_path_image_width_one',
			[
				'label' => esc_html__( 'Image Width', 'wpsection' ),
				'condition'  => ['show_slider_image_one' => '1'],
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3000,
						'step' => 5,
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
					'{{WRAPPER}} .wps_slider_img_one img' => 'width: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);			
		
$repeater->add_control(
    'slider_class_image_one',
    [
        'label'       => esc_html__( 'CSS Class for Image One', 'wpsection' ),
        'condition'   => ['show_slider_image_one' => '1'],
        'type'        => \Elementor\Controls_Manager::TEXT,
        'label_block' => true,
        'dynamic'     => ['active' => true],
    ]
);
		
	$repeater->add_control( 
		'slider_image_vertical_one',
					[
						'label' => esc_html__( 'Vertical Position', 'wpsection' ),
						 'condition'   => ['show_slider_image_one' => '1'],
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => -1000,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => -1000,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 100,
						],
						'selectors' => [
							'{{WRAPPER}} .wps_slider_img_one img' => 'top: {{SIZE}}{{UNIT}};',
						]
					]
				);		
	

	$repeater->add_control( 
		'slider_image_horizontal_one',
					[
						'label' => esc_html__( 'Horizontal Position Previous',  'wpsection' ),
					 'condition'   => ['show_slider_image_one' => '1'],
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
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
							'size' => 100,
						],
						'selectors' => [
							'{{WRAPPER}} .wps_slider_img_one img' => 'left: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
		

		
$repeater->add_control( 
            'slider_image_border_radius_one',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
               'condition'   => ['show_slider_image_one' => '1'],
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}}  .wps_slider_img_one img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );		
			
			
//Image Two
$repeater->add_control(
    'show_slider_image_two',
    array(
        'label'         => __( 'Show Slider Image Two', 'wpsection' ),
        'type'          => \Elementor\Controls_Manager::SWITCHER,
        'return_value'  => '1',
        'default'       => '0',
    )
);

$repeater->add_control(
    'slider_path_image_two',
    [
        'label'      => esc_html__( 'Slider Image Two', 'wpsection' ),
        'condition'  => ['show_slider_image_two' => '1'],
        'type'       => \Elementor\Controls_Manager::MEDIA,
        'dynamic'    => ['active' => true],
     
    ]
);

		
$repeater->add_control(
			'slider_path_image_width_two',
			[
				'label' => esc_html__( 'Image Width', 'wpsection' ),
				'condition'  => ['show_slider_image_two' => '1'],
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3000,
						'step' => 5,
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
					'{{WRAPPER}} .wps_slider_img_two img' => 'width: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);	
$repeater->add_control(
    'slider_class_image_two',
    [
        'label'       => esc_html__( 'CSS Class for Image Two', 'wpsection' ),
        'condition'   => ['show_slider_image_two' => '1'],
        'type'        => \Elementor\Controls_Manager::TEXT,
        'label_block' => true,
        'dynamic'     => ['active' => true],
    ]
);
		
	$repeater->add_control( 
		'slider_image_vertical_two',
					[
						'label' => esc_html__( 'Vertical Position', 'wpsection' ),
						 'condition'   => ['show_slider_image_two' => '1'],
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => -1000,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => -1000,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 100,
						],
						'selectors' => [
							'{{WRAPPER}} .wps_slider_img_two img' => 'top: {{SIZE}}{{UNIT}};',
						]
					]
				);		
	

	$repeater->add_control( 
		'slider_image_horizontal_two',
					[
						'label' => esc_html__( 'Horizontal Position Previous',  'wpsection' ),
					 'condition'   => ['show_slider_image_two' => '1'],
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
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
							'size' => 100,
						],
						'selectors' => [
							'{{WRAPPER}} .wps_slider_img_two img' => 'left: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
		

		
$repeater->add_control( 
            'slider_image_border_radius_two',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
               'condition'   => ['show_slider_image_two' => '1'],
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}}  .wps_slider_img_two img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );		

		
		

$repeater->add_control(
    'show_slider_image_three',
    array(
        'label'         => __( 'Show Slider Image Three', 'wpsection' ),
        'type'          => \Elementor\Controls_Manager::SWITCHER,
        'return_value'  => '1',
        'default'       => '1',
    )
);

$repeater->add_control(
    'slider_path_image_three',
    [
        'label'      => esc_html__( 'Slider Image Three', 'wpsection' ),
        'condition'  => ['show_slider_image_three' => '1'],
        'type'       => \Elementor\Controls_Manager::MEDIA,
        'dynamic'    => ['active' => true],
       
    ]
);

$repeater->add_control(
			'slider_path_image_width_three',
			[
				'label' => esc_html__( 'Image Width', 'wpsection' ),
				'condition'  => ['show_slider_image_three' => '1'],
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3000,
						'step' => 5,
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
					'{{WRAPPER}} .wps_slider_img_three img' => 'width: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);	
$repeater->add_control(
    'slider_class_image_three',
    [
        'label'       => esc_html__( 'CSS Class for Image Three', 'wpsection' ),
        'condition'   => ['show_slider_image_three' => '1'],
        'type'        => \Elementor\Controls_Manager::TEXT,
        'label_block' => true,
        'dynamic'     => ['active' => true],
    ]
);
		
	$repeater->add_control( 
		'slider_image_vertical_three',
					[
						'label' => esc_html__( 'Vertical Position', 'wpsection' ),
						 'condition'   => ['show_slider_image_three' => '1'],
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => -1000,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => -1000,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 100,
						],
						'selectors' => [
							'{{WRAPPER}} .wps_slider_img_three img' => 'top: {{SIZE}}{{UNIT}};',
						]
					]
				);		
	

	$repeater->add_control( 
		'slider_image_horizontal_three',
					[
						'label' => esc_html__( 'Horizontal Position Previous',  'wpsection' ),
					 'condition'   => ['show_slider_image_three' => '1'],
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
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
							'size' => 100,
						],
						'selectors' => [
							'{{WRAPPER}} .wps_slider_img_three img' => 'left: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
		

		
$repeater->add_control( 
            'slider_image_border_radius_three',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
               'condition'   => ['show_slider_image_three' => '1'],
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}}  .wps_slider_img_three img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );		
			

	
		

$repeater->add_control(
    'show_slider_image_four',
    array(
        'label'         => __( 'Show Slider Image Four', 'wpsection' ),
        'type'          => \Elementor\Controls_Manager::SWITCHER,
        'return_value'  => '1',
        'default'       => '0',
    )
);

$repeater->add_control(
    'slider_path_image_four',
    [
        'label'      => esc_html__( 'Slider Image Four', 'wpsection' ),
        'condition'  => ['show_slider_image_four' => '1'],
        'type'       => \Elementor\Controls_Manager::MEDIA,
        'dynamic'    => ['active' => true],
       
    ]
);

$repeater->add_control(
			'slider_path_image_width_four',
			[
				'label' => esc_html__( 'Image Width', 'wpsection' ),
				'condition'  => ['show_slider_image_four' => '1'],
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3000,
						'step' => 5,
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
					'{{WRAPPER}} .wps_slider_img_four img' => 'width: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);	
$repeater->add_control(
    'slider_class_image_four',
    [
        'label'       => esc_html__( 'CSS Class for Image Four', 'wpsection' ),
        'condition'   => ['show_slider_image_four' => '1'],
        'type'        => \Elementor\Controls_Manager::TEXT,
        'label_block' => true,
        'dynamic'     => ['active' => true],
    ]
);
		
	$repeater->add_control( 
		'slider_image_vertical_four',
					[
						'label' => esc_html__( 'Vertical Position', 'wpsection' ),
						 'condition'   => ['show_slider_image_four' => '1'],
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => -1000,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => -1000,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 100,
						],
						'selectors' => [
							'{{WRAPPER}} .wps_slider_img_four img' => 'top: {{SIZE}}{{UNIT}};',
						]
					]
				);		
	

	$repeater->add_control( 
		'slider_image_horizontal_four',
					[
						'label' => esc_html__( 'Horizontal Position Previous',  'wpsection' ),
					 'condition'   => ['show_slider_image_four' => '1'],
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
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
							'size' => 100,
						],
						'selectors' => [
							'{{WRAPPER}} .wps_slider_img_four img' => 'left: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
		

		
$repeater->add_control( 
            'slider_image_border_radius_four',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
               'condition'   => ['show_slider_image_four' => '1'],
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}}  .wps_slider_img_four img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );		

		

$repeater->add_control(
    'show_slider_image_five',
    array(
        'label'         => __( 'Show Slider Image Five', 'wpsection' ),
        'type'          => \Elementor\Controls_Manager::SWITCHER,
        'return_value'  => '1',
        'default'       => '0',
    )
);

$repeater->add_control(
    'slider_path_image_five',
    [
        'label'      => esc_html__( 'Slider Image Five', 'wpsection' ),
        'condition'  => ['show_slider_image_five' => '1'],
        'type'       => \Elementor\Controls_Manager::MEDIA,
        'dynamic'    => ['active' => true],
       
    ]
);

		
$repeater->add_control(
			'slider_path_image_width_five',
			[
				'label' => esc_html__( 'Image Width', 'wpsection' ),
				'condition'  => ['show_slider_image_five' => '1'],
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3000,
						'step' => 5,
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
					'{{WRAPPER}} .wps_slider_img_five img' => 'width: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);	
$repeater->add_control(
    'slider_class_image_five',
    [
        'label'       => esc_html__( 'CSS Class for Image Five', 'wpsection' ),
        'condition'   => ['show_slider_image_five' => '1'],
        'type'        => \Elementor\Controls_Manager::TEXT,
        'label_block' => true,
        'dynamic'     => ['active' => true],
    ]
);
		
	$repeater->add_control( 
		'slider_image_vertical_five',
					[
						'label' => esc_html__( 'Vertical Position', 'wpsection' ),
						 'condition'   => ['show_slider_image_five' => '1'],
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => -1000,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => -1000,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 100,
						],
						'selectors' => [
							'{{WRAPPER}} .wps_slider_img_five img' => 'top: {{SIZE}}{{UNIT}};',
						]
					]
				);		
	

	$repeater->add_control( 
		'slider_image_horizontal_five',
					[
						'label' => esc_html__( 'Horizontal Position Previous',  'wpsection' ),
					 'condition'   => ['show_slider_image_five' => '1'],
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
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
							'size' => 100,
						],
						'selectors' => [
							'{{WRAPPER}} .wps_slider_img_five img' => 'left: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
		

		
$repeater->add_control( 
            'slider_image_border_radius_five',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
               'condition'   => ['show_slider_image_five' => '1'],
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}}  .wps_slider_img_five img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );		
					
		
//End

		$this->add_control(
			'repeat',
			[
				'label'       => esc_html__( 'Sliders', 'element-path' ),
				//'condition'    => array( 'slider_style' => 'plugin_slides' ),
				'show_label'  => false,
				'type'        => Controls_Manager::REPEATER,
				'separator'   => 'before',
				'title_field' => '{{ title }}',
				'dynamic'     => [
					'active' => true,
				],
				'default'     => [
					[
						'title' => esc_html__( 'Slider Path Slide', 'element-path' ),
					],
				],
				'fields'      => $repeater->get_controls(),
			]
		);




		$this->end_controls_section();

//==================== Star of Setting area==============================================
	
// Basic Setting
	
$this->start_controls_section(
			'slider_path_basic_control',
			
			array(
				'label' => __( 'Slider Basic Settings', 'ecolab' ),
				
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				//'condition'    => array( 'slider_style' => 'plugin_slides' ),
			)
		);
		

		
		
$this->add_control(
			'slider_path_basic_show',
			array(
				'label' => esc_html__( 'Show Slider', 'ecolab' ),
					//'condition'    => array( 'slider_style' => 'plugin_slides' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'show' => esc_html__( 'Show', 'ecolab' ),	
						'icon' => 'eicon-check-circle',
					],
					'none' => [
						'none' => esc_html__( 'Hide', 'ecolab' ),
						'icon' => 'eicon-close-circle',
					],
				],
				'default' => 'show',
				'selectors' => array(
					'{{WRAPPER}} .slider_path' => 'display: {{VALUE}} !important',
				),
			)
		);	

	$this->add_control(
			'slider_path_alingment',
			array(
				'label' => esc_html__( 'Alignment', 'ecolab' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'ecolab' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'ecolab' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'ecolab' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'condition'    => array( 'slider_path_basic_show' => 'show' ),
				'toggle' => true,
				'selectors' => array(
				
					'{{WRAPPER}} .slider_path_title ' => 'text-align: {{VALUE}} !important',
					'{{WRAPPER}} .slider_path_text ' => 'text-align: {{VALUE}} !important',
					'{{WRAPPER}} .slider_path_subtitle ' => 'text-align: {{VALUE}} !important',
				),
			)
		);
$this->add_control(
			'slider_path_button_xx_alingment',
			array(
				'label' => esc_html__( 'Button Alignment', 'ecolab' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'ecolab' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'ecolab' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'ecolab' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'condition'    => array( 'slider_path_basic_show' => 'show' ),
				'toggle' => true,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button_container ' => 'justify-content: {{VALUE}} !important',
				),
			)
		);			
				
		
$this->add_control(
			'slider_path_slider_width',
			[
				'label' => esc_html__( 'Block Width', 'ecolab' ),
				'condition'    => array( 'slider_path_basic_show' => 'show' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3000,
						'step' => 5,
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
					'{{WRAPPER}} .slider_path_slide' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);	
		$this->add_control(
			'slider_path_slider_height',
			[
				'label' => esc_html__( 'Block Height', 'ecolab' ),
				'condition'    => array( 'slider_path_basic_show' => 'show' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .slider_path_slide' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);	

	$this->add_control(
			'slider_path_container_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors' => array(
					'{{WRAPPER}} .slider_path_container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);
		
	$this->add_control(
			'slider_path_container_margin',
			array(
				'label'     => __( 'Margin', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors' => array(
					'{{WRAPPER}} .slider_path_container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);
		

$this->add_control(
			'slider_path_container_bgcolor',
			array(
				'label'     => __( 'Slider Background Color', 'ecolab' ),
				'condition'    => array( 'slider_path_basic_show' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_container' => 'background: {{VALUE}} !important',
				),
			)
		);
$this->add_control(
			'slider_path_slider_before_color',
			array(
				'label'     => __( 'Slider Before Color', 'ecolab' ),
				'condition'    => array( 'slider_path_basic_show' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_slide:before' => 'background: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_path_slider_after_color',
			array(
				'label'     => __( 'Slider After Color', 'ecolab' ),
				'condition'    => array( 'slider_path_basic_show' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_slide:after' => 'background: {{VALUE}} !important',
				),
			)
		);	
	
$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'slider_path_slider_border',
				'condition'    => array( 'slider_path_basic_show' => 'show' ),
				'label' => esc_html__( 'Box Border', 'ecolab' ),
				'selector' => '{{WRAPPER}} .slider_path_slide',
			]
		);
		
		$this->add_control(
			'slider_path_slider_border_radius',
			array(
				'label' => esc_html__( 'Border Radius', 'ecolab' ),
				'condition'    => array( 'slider_path_basic_show' => 'show' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'condition'    => array( 'show_button' => 'show' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .slider_path_slide' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			)
		);


 $this->add_control( 'slider_path_background_size',
		            [
		                'label' => esc_html__( 'Background Size', 'rashid'),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => '',
		                'options' => [
		                	'' => esc_html__( 'Default', 'rashid' ),
		                    'auto' => esc_html__( 'Auto', 'rashid' ),
		                    'cover' => esc_html__( 'Cover', 'rashid' ),
		                    'contain' => esc_html__( 'Contain', 'rashid' ),
		                ],
		            ]
		        );
$this->add_control( 'slider_path_background_position',
		            [
		                'label' => esc_html__( 'Background Position', 'rashid' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => '',
		                'options' => [
		                	'' => esc_html__( 'Default', 'rashid' ),
		                	'center center' => esc_html__( 'Center Center', 'rashid' ),
		                	'center left' => esc_html__( 'Center Left', 'rashid' ),
		                	'center right' => esc_html__( 'Center Right', 'rashid' ),
		                	'top center' => esc_html__( 'Top Center', 'rashid'),
		                	'top left' => esc_html__( 'Top Left', 'rashid' ),
		                	'top right' => esc_html__( 'Top Right', 'rashid' ),
		                	'bottom center' => esc_html__( 'Bottom Center', 'rashid' ),
		                	'bottom left' => esc_html__( 'Bottom Left', 'rashid' ),
		                	'bottom right' => esc_html__( 'Bottom Right', 'rashid' ),
		                ],
		            ]
		        );



$this->add_control(
		'slider_path_slider_animation',
		[
			'label'   => esc_html__( 'Slider Image Animation ', 'rashid' ),
			'condition'    => array( 'slider_path_basic_show' => 'show' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '1',
			'options' => array(
				'slider_path_animation_slider_style_1'   => esc_html__( 'Animations Style 01', 'rashid' ),
				'slider_path_animation_slider_style_2'   => esc_html__( 'Animations Style 02', 'rashid' ),
				'slider_path_animation_slider_style_3'   => esc_html__( 'Animations Style 03', 'rashid' ),
			
			),
		]
	);			

$this->add_control(
			'slider_path_zoom_show',
			array(
				'label' => esc_html__( 'Show Slider Zoom Animation', 'ecolab' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'show' => esc_html__( 'Show', 'ecolab' ),	
						'icon' => 'eicon-check-circle',
					],
					'none' => [
						'none' => esc_html__( 'Hide', 'ecolab' ),
						'icon' => 'eicon-close-circle',
					],
				],
				'default' => 'show',
				'selectors' => array(
					'{{WRAPPER}} .slider_slider_path .owl-stage' => 'transform: {{VALUE}} !important',
				),
			)
		);	


		$this->end_controls_section();
		
//End of Button	
// Title Slider Setting 001 	==================	

		
		$this->start_controls_section(
			'slider_path_title_settings',
			array(
				'label' => __( 'Title Setting', 'ecolab' ),
				//'condition'    => array( 'slider_style' => 'plugin_slides' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
		
	$this->add_control(
			'slider_path_show_title',
			array(
				'label' => esc_html__( 'Show Title', 'ecolabe' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'show' => esc_html__( 'Show', 'ecolab' ),	
						'icon' => 'eicon-check-circle',
					],
					'none' => [
						'none' => esc_html__( 'Hide', 'ecolab' ),
						'icon' => 'eicon-close-circle',
					],
				],
				'default' => 'show',
				'selectors' => array(
					'{{WRAPPER}} .slider_path_title' => 'display: {{VALUE}} !important',
				),
			)
		);	

	


	$this->add_control(
			'slider_path_title_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'condition'    => array( 'slider_path_show_title' => 'show' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .slider_path_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'slider_path_title_typography',
				'condition'    => array( 'slider_path_show_title' => 'show' ),
				'label'    => __( 'Typography', 'ecolab' ),
				'selector' => '{{WRAPPER}} .slider_path_title ',
			)
		);
		$this->add_control(
			'slider_path_title_color',
			array(
				'label'     => __( 'Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_title' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .slider_path_title ' => 'color: {{VALUE}} !important',
		
				),
			)
		);

	$this->add_control(
		'slider_path_animation_title',
		[
			'label'   => esc_html__( 'Slider Title Animatin Style ', 'rashid' ),
			'condition'    => array( 'slider_path_show_title' => 'show' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '1',
			'options' => array(
				'slider_path_animation_title_style_1'   => esc_html__( 'Animations Style 01', 'rashid' ),
				'slider_path_animation_title_style_2'   => esc_html__( 'Animations Style 02', 'rashid' ),
				'slider_path_animation_title_style_3'   => esc_html__( 'Animations Style 03', 'rashid' ),
			
			),
		]
	);




	$this->end_controls_section();
	

// Subtitle Slider Setting 002 	==================	

		
		$this->start_controls_section(
			'slider_path_subtitle_settings',
			array(
				'label' => __( 'Sub Title Setting', 'ecolab' ),
				//'condition'    => array( 'slider_style' => 'plugin_slides' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
		
	$this->add_control(
			'slider_path_show_subtitle',
			array(
				'label' => esc_html__( 'Show Sub Title', 'ecolabe' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'show' => esc_html__( 'Show', 'ecolab' ),	
						'icon' => 'eicon-check-circle',
					],
					'none' => [
						'none' => esc_html__( 'Hide', 'ecolab' ),
						'icon' => 'eicon-close-circle',
					],
				],
				'default' => 'show',
				'selectors' => array(
					'{{WRAPPER}} .slider_path_subtitle' => 'display: {{VALUE}} !important',
				),
			)
		);	



	$this->add_control(
			'slider_path_subtitle_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'condition'    => array( 'slider_path_show_subtitle' => 'show' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .slider_path_subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'slider_path_subtitle_typography',
				'condition'    => array( 'slider_path_show_subtitle' => 'show' ),
				'label'    => __( 'Typography', 'ecolab' ),
				'selector' => '{{WRAPPER}} .slider_path_subtitle ',
			)
		);
		$this->add_control(
			'slider_path_subtitle_color',
			array(
				'label'     => __( 'Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_subtitle' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .slider_path_subtitle ' => 'color: {{VALUE}} !important',
		
				),
			)
		);

	$this->add_control(
		'slider_path_animation_subtitle',
		[
			'label'   => esc_html__( 'Slider Animatin Subtitle ', 'rashid' ),
			'condition'    => array( 'slider_path_show_subtitle' => 'show' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '1',
			'options' => array(
				'slider_path_animation_subtitle_style_1'   => esc_html__( 'Animations Style 01', 'rashid' ),
				'slider_path_animation_subtitle_style_2'   => esc_html__( 'Animations Style 02', 'rashid' ),
				'slider_path_animation_subtitle_style_3'   => esc_html__( 'Animations Style 03', 'rashid' ),
			
			),
		]
	);


		$this->end_controls_section();

//Slider Text 03 ==============

		$this->start_controls_section(
			'slider_path_text_settings',
			array(
				'label' => __( 'Text Setting', 'ecolab' ),
				//'condition'    => array( 'slider_style' => 'plugin_slides' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
		
	$this->add_control(
			'slider_path_show_text',
			array(
				'label' => esc_html__( 'Show Text', 'ecolabe' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'show' => esc_html__( 'Show', 'ecolab' ),	
						'icon' => 'eicon-check-circle',
					],
					'none' => [
						'none' => esc_html__( 'Hide', 'ecolab' ),
						'icon' => 'eicon-close-circle',
					],
				],
				'default' => 'show',
				'selectors' => array(
					'{{WRAPPER}} .slider_path_text' => 'display: {{VALUE}} !important',
				),
			)
		);	
		


	$this->add_control(
			'slider_path_text_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'condition'    => array( 'slider_path_show_text' => 'show' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .slider_path_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'slider_path_text_typography',
				'condition'    => array( 'slider_path_show_subtitle' => 'show' ),
				'label'    => __( 'Typography', 'ecolab' ),
				'selector' => '{{WRAPPER}} .slider_path_text ',
			)
		);
		$this->add_control(
			'slider_path_text_color',
			array(
				'label'     => __( 'Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_subtitle' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .slider_path_text ' => 'color: {{VALUE}} !important',
		
				),
			)
		);

	$this->add_control(
		'slider_path_animation_text',
		[
			'label'   => esc_html__( 'Slider Animatin Text ', 'rashid' ),
			'condition'    => array( 'slider_path_show_subtitle' => 'show' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '1',
			'options' => array(
				'slider_path_animation_text_style_1'   => esc_html__( 'Animations Style 01', 'rashid' ),
				'slider_path_animation_text_style_2'   => esc_html__( 'Animations Style 02', 'rashid' ),
				'slider_path_animation_text_style_3'   => esc_html__( 'Animations Style 03', 'rashid' ),
			
			),
		]
	);

		$this->end_controls_section();	

// Button Setting 005

$this->start_controls_section(
			'slider_path_button_control',
			array(
				'label' => __( 'Button Settings', 'ecolab' ),
				//'condition'    => array( 'slider_style' => 'plugin_slides' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
$this->add_control(
			'slider_path_show_button',
			array(
				'label' => esc_html__( 'Show Button', 'ecolab' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'show' => esc_html__( 'Show', 'ecolab' ),	
						'icon' => 'eicon-check-circle',
					],
					'none' => [
						'none' => esc_html__( 'Hide', 'ecolab' ),
						'icon' => 'eicon-close-circle',
					],
				],
				'default' => 'show',
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button' => 'display: {{VALUE}} !important',
				),
			)
		);	
		
		
		

$this->add_control(
			'slider_path_button_color',
			array(
				'label'     => __( 'Button Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button' => 'color: {{VALUE}} !important',

				),
			)
		);

$this->add_control(
			'slider_path_button_color_hover',
			array(
				'label'     => __( 'Button Color Hover', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button:hover' => 'color: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_path_button_bg_color',
			array(
				'label'     => __( 'Background Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button' => 'background: {{VALUE}} !important',
				),
			)
		);	
$this->add_control(
			'slider_path_button_hover_color',
			array(
				'label'     => __( 'Background Hover Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button:before' => 'background: {{VALUE}} !important',
					'{{WRAPPER}} .slider_path_button:hover' => 'background: {{VALUE}} !important',
				),
			)
		);				
	$this->add_control(
			'slider_path_button_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	$this->add_control(
			'slider_path_button_margin',
			array(
				'label'     => __( 'Margin', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'slider_path_button_typography',
				'condition'    => array( 'slider_path_show_button' => 'show' ),
				'label'    => __( 'Typography', 'ecolab' ),
				'selector' => '{{WRAPPER}} .slider_path_button',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'slider_path_border',
				'condition'    => array( 'slider_path_show_button' => 'show' ),
				'selector' => '{{WRAPPER}} .slider_path_button',
			)
		);
	

		$this->add_control(
			'slider_path_border_radius',
			array(
				'label'     => __( 'Border Radius', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

		$this->end_controls_section();
		
//End of Button		

// Button Setting 005

$this->start_controls_section(
			'slider_path_button_2_control',
			array(
				'label' => __( 'Button 2 Settings', 'ecolab' ),
				//'condition'    => array( 'slider_style' => 'plugin_slides' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
$this->add_control(
			'slider_path_show_button_2',
			array(
				'label' => esc_html__( 'Show Button', 'ecolab' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'show' => esc_html__( 'Show', 'ecolab' ),	
						'icon' => 'eicon-check-circle',
					],
					'none' => [
						'none' => esc_html__( 'Hide', 'ecolab' ),
						'icon' => 'eicon-close-circle',
					],
				],
				'default' => 'show',
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button_2' => 'display: {{VALUE}} !important',
				),
			)
		);	
		


$this->add_control(
			'slider_path_button_2_color',
			array(
				'label'     => __( 'Button Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button_2' => 'color: {{VALUE}} !important',

				),
			)
		);

$this->add_control(
			'slider_path_button_2_color_hover',
			array(
				'label'     => __( 'Button Color Hover', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button_2:hover' => 'color: {{VALUE}} !important',

				),
			)
		);


$this->add_control(
			'slider_path_button_2_bg_color',
			array(
				'label'     => __( 'Background Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button_2' => 'background: {{VALUE}} !important',
				),
			)
		);	
$this->add_control(
			'slider_path_button_2_hover_color',
			array(
				'label'     => __( 'Hover Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button_2:before' => 'background: {{VALUE}} !important',
					'{{WRAPPER}} .slider_path_button_2:hover' => 'background: {{VALUE}} !important',
				),
			)
		);				
	$this->add_control(
			'slider_path_button_2_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button_2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	$this->add_control(
			'slider_path_button_2_margin',
			array(
				'label'     => __( 'Margin', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button_2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'slider_path_button_2_typography',
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'label'    => __( 'Typography', 'ecolab' ),
				'selector' => '{{WRAPPER}} .slider_path_button_2',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'slider_path_border_2',
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'selector' => '{{WRAPPER}} .slider_path_button_2',
			)
		);
	

		$this->add_control(
			'slider_path_border_2_radius',
			array(
				'label'     => __( 'Border Radius', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button_2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

		$this->end_controls_section();
		
//End of Button	

$this->start_controls_section(
			'slider_path_button_3_control',
			array(
				'label' => __( 'Slider Arrow  Settings', 'wpsection' ),
		
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'    => array( 'style' => 'style-1' ),
			)
		);
		
$this->add_control(
			'slider_path_show_button_3',
			array(
				'label' => esc_html__( 'Show Button', 'wpsection' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'show' => esc_html__( 'Show', 'wpsection' ),	
						'icon' => 'eicon-check-circle',
					],
					'none' => [
						'none' => esc_html__( 'Hide', 'wpsection' ),
						'icon' => 'eicon-close-circle',
					],
				],
				'default' => 'show',
				'selectors' => array(
					'{{WRAPPER}} .slider_path .owl-nav ' => 'display: {{VALUE}} !important',
				),
			)
		);		

$this->add_control(
			'slider_path_button_3_color',
			array(
				'label'     => __( 'Button Color', 'wpsection' ),
				//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#cbcbcb',
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button' => 'color: {{VALUE}} !important',
	

				),
			)
		);
$this->add_control(
			'slider_path_button_3_color_hover',
			array(
				'label'     => __( 'Button Hover Color', 'wpsection' ),
				//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff ',
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button:hover' => 'color: {{VALUE}} !important',
			

				),
			)
		);
$this->add_control(
			'slider_path_button_3_bg_color',
			array(
				'label'     => __( 'Background Color', 'wpsection' ),
				//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#f3f3f3 ',
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button' => 'background: {{VALUE}} !important',
				),
			)
		);	
$this->add_control(
			'slider_path_button_3_hover_color',
			array(
				'label'     => __( 'Background Hover Color', 'wpsection' ),
				//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button:hover' => 'background: {{VALUE}} !important',
				),
			)
		);	
		
		
	
		$this->add_control( 'slider_path_dot_3_width',
					[
						'label' => esc_html__( 'Arraw Width',  'wpsection' ),
						//'condition'    => array( 'slider_path_show_dot' => 'show' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
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
							'{{WRAPPER}} .slider_path .owl-nav button' => 'width: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
		

	$this->add_control( 'slider_path_dot_3_height',
					[
						'label' => esc_html__( 'Arraw Height', 'wpsection' ),
						//'condition'    => array( 'slider_path_show_dot' => 'show' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
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
							'size' =>30,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-nav button' => 'height: {{SIZE}}{{UNIT}};',
					
						]
					]
				);		
			
	
		
	$this->add_control(
			'slider_path_button_3_padding',
			array(
				'label'     => __( 'Padding', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	$this->add_control(
			'slider_path_button_3_margin',
			array(
				'label'     => __( 'Margin', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'slider_path_button_3_typography',
				//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'label'    => __( 'Typography', 'wpsection' ),
				'selector' => '{{WRAPPER}}  .slider_path .owl-nav button',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'slider_path_border_3',
				//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'selector' => '{{WRAPPER}}  .slider_path .owl-nav button ',
			)
		);
	

		$this->add_control(
			'slider_path_border_3_radius',
			array(
				'label'     => __( 'Border Radius', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


				
		
				$this->add_control( 'slider_path_horizontal_prev',
					[
						'label' => esc_html__( 'Horizontal Position Previous',  'wpsection' ),
						//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
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
							'{{WRAPPER}} .slider_path .owl-nav .owl-prev' => 'left: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
				$this->add_control( 'slider_path_horizontal_next',
					[
						'label' => esc_html__( 'Horizontal Position Next', 'wpsection'),
						//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
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
							'{{WRAPPER}} .slider_path .owl-nav .owl-next' => 'left: {{SIZE}}{{UNIT}};',
						],
						
					]
				);
		
				$this->add_control( 'slider_path_vertical',
					[
						'label' => esc_html__( 'Vertical Position', 'wpsection' ),
						//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => -1000,
								'max' => 1000,
								'step' => 1,
							],
							'%' => [
								'min' => -1000,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 200,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-nav button' => 'top: {{SIZE}}{{UNIT}};',
				
						]
					]
				);


		$this->end_controls_section();
		
	

// Dot Button Setting
	
$this->start_controls_section(
			'slider_path_dot_control',
			array(
				'label' => __( 'Slider Dot  Settings', 'wpsection' ),
		
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'    => array( 'style' => 'style-1' ),
			)
		);
		
$this->add_control(
			'slider_path_show_dot',
			array(
				'label' => esc_html__( 'Show Dot', 'wpsection' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'show' => esc_html__( 'Show', 'wpsection' ),	
						'icon' => 'eicon-check-circle',
					],
					'none' => [
						'none' => esc_html__( 'Hide', 'wpsection' ),
						'icon' => 'eicon-close-circle',
					],
				],
				'default' => 'show',
				'selectors' => array(
					'{{WRAPPER}} .slider_path .owl-dots ' => 'display: {{VALUE}} !important',
				),
			)
		);		


				$this->add_control( 'slider_path_dot_width',
					[
						'label' => esc_html__( 'Dot Width',  'wpsection' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
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
							'{{WRAPPER}} .slider_path .owl-theme .owl-dots span' => 'width: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
			
				$this->add_control( 'slider_path_dot_height',
					[
						'label' => esc_html__( 'Dot Height', 'wpsection' ),
					
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
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
							'{{WRAPPER}} .slider_path .owl-theme .owl-dots span ' => 'height: {{SIZE}}{{UNIT}};',
					
						]
					]
				);		
		
$this->add_control(
			'slider_path_dot_color',
			array(
				'label'     => __( 'Dot Color', 'wpsection' ),
			
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span' => 'background: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_path_dot_color_hover',
			array(
				'label'     => __( 'Dot Hover Color', 'wpsection' ),
			
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span:hover' => 'background: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_path_dot_bg_color',
			array(
				'label'     => __( 'Active Color', 'wpsection' ),
			
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => array(
					'{{WRAPPER}}  .owl-theme .owl-dots .owl-dot.active span' => 'background: {{VALUE}} !important',
				),
			)
		);	
			
	$this->add_control(
			'slider_path_dot_padding',
			array(
				'label'     => __( 'Padding', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
			
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	$this->add_control(
			'slider_path_dot_margin',
			array(
				'label'     => __( 'Margin', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
		
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'slider_path_dot_border',
			
				'selector' => '{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span',
			)
		);
	

		$this->add_control(
			'slider_path_dot_radius',
			array(
				'label'     => __( 'Border Radius', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
		
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


				
		
				$this->add_control( 'slider_path_dot_horizontal',
					[
						'label' => esc_html__( 'Horizontal Position Previous',  'wpsection' ),
					
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
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
							'size' => 200,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-theme .owl-dots' => 'left: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
			

				$this->add_control( 'slider_path_dot_vertical',
					[
						'label' => esc_html__( 'Vertical Position', 'wpsection' ),
					
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
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
							'{{WRAPPER}} .slider_path .owl-theme .owl-dots  ' => 'top: {{SIZE}}{{UNIT}};',
					
						]
					]
				);


		$this->end_controls_section();	
		
		

$this->start_controls_section(
                'hero_block_settings',
                array(
                    'label' => __( 'Block Setting', 'wpsection' ),
                    'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                )
            );

    
        $this->add_control(
            'hero_block_margin',
            array(
                'label'     => __( 'Block Margin', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .wps_hero_slider_block .owl-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'hero_block_padding',
            array(
                'label'     => __( 'Block Padding', 'wpsection' ),
                    //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .wps_hero_slider_block .owl-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

            $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'hero_block_shadow',
                    //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_hero_slider_block .owl-item',
            ]
        );
      $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'hero_block_X_hover_shadow',
                    //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Hover Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_hero_slider_block .owl-item:hover',
            ]
        );

 
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'hero_block_border',
                //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Border', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_hero_slider_block .owl-item',
            ]
        );
                
            $this->add_control(
            'hero_block_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wps_hero_slider_block .owl-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );







        
$this->end_controls_section();   		


	
//End Dot
	
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$allowed_tags = wp_kses_allowed_html('post');
		
	
		?>


<?php
  $style = $settings['style'];
    $style_folder = __DIR__ . '/wps_slider/';
    $style_file = $style_folder . $style . '.php';

    if (is_readable($style_file)) {
        require $style_file;
    } else {
        echo "Style file '$style.php' not found or could not be read.";
    }

?>
<!-- End of Main Area =================== -->
             
		<?php 
	}

}





// Register widget
Plugin::instance()->widgets_manager->register( new \wpsection_wpsection_Widget() );


