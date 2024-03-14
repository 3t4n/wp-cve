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

class wps_gallery_slide extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'wps_gallery_slide';
    }

    public function get_title()
    {
        return __('Gallery Slide', 'wpsection');
    }

    public function get_icon()
    {
        return 'eicon-image-rollover';
    }

    public function get_keywords()
    {
        return ['wps', 'portfolio'];
    }

    public function get_categories()
    {
     return ['wpsection_category'];
    }



    protected function register_controls()
    {
        $this->start_controls_section(
            'portfolio',
            [
                'label' => esc_html__('Gallery Slide', 'wpsection'),
            ]
        );
        $this->add_control(
            'sec_class',
            [
                'label'       => __('Section Class', 'wpsection'),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __('Enter Section Class', 'wpsection'),
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
					'6' => __('6 Columns', 'wpsection'),
                ],
            )
        );
		   $this->add_control(
            'wps_columns_tab',
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
					'6' => __('6 Columns', 'wpsection'),
					
                ],
            )
        );
		
  $this->add_control(
            'wps_tab_container_width',
            [
                'label' => esc_html__( 'Section Width ', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 3000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1320,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_project_gallery .auto-container' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );   			
		
    $this->add_control(
        'enable_slide',
        [
            'label' => esc_html__('Enable Slide', 'your-text-domain'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes', // Set the default value
            'label_on' => esc_html__('Yes', 'wpsection'),
            'label_off' => esc_html__('No', 'wpsection'),
        ]
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
    'block_plus_icon',
    [
        'label' => esc_html__('Plus Icon', 'rashid'),
        'type' => Controls_Manager::ICONS,
        'default' => [
            'value' => 'eicon-lightbox-expand', // Set your default icon class here
            'library' => 'solid', // Set the icon library (solid, regular, or brands)
        ],
    ]
);

 $repeater->add_control(
    'block_link_icon',
    [
        'label' => esc_html__('Link Icon', 'rashid'),
        'type' => Controls_Manager::ICONS,
        'default' => [
            'value' => 'eicon-lightbox-expand', // Set your default icon class here
            'library' => 'solid', // Set the icon library (solid, regular, or brands)
        ],
    ]
);
	
		
        $repeater->add_control(
            'block_subtitle',
            [
                'label' => esc_html__('Designation', 'rashid'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Enter your Subtitle', 'rashid'),
            ]
        );
        $repeater->add_control(
            'block_title',
            [
                'label' => esc_html__('Name', 'rashid'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Enter your title', 'rashid'),
            ]
        );
        $repeater->add_control(
            'block_btnlink_x',
            [
                'label' => __('Button Url', 'rashid'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'rashid'),
                'show_external' => true,
                'default' => [
                'url' => '',
                'is_external' => true,
                'nofollow' => true,
                        ],
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

		
		
		
		
		
		
//Title		
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('Title Setting', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'show_title',
            array(
                'label' => esc_html__('Show Title', 'ecolabe'),
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
                    '{{WRAPPER}} .mr_block_title' => 'display: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'title_alingment',
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
                'condition'    => array('show_title' => 'show'),
                'toggle' => true,
                'selectors' => array(

                    '{{WRAPPER}} .mr_block_title' => 'text-align: {{VALUE}} !important',
                ),
            )
        );


        $this->add_control(
            'title_padding',
            array(
                'label'     => __('Padding', 'ecolab'),
                'condition'    => array('show_title' => 'show'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}} .mr_block_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'condition'    => array('show_title' => 'show'),
                'label'    => __('Typography', 'ecolab'),
                'selector' => '{{WRAPPER}} .mr_block_title a',
            )
        );
        $this->add_control(
            'title_color',
            array(
                'label'     => __('Color', 'ecolab'),
                'condition'    => array('show_title' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_block_title a' => 'color: {{VALUE}} !important',

                ),
            )
        );
        $this->add_control(
            'title_hover_color',
            array(
                'label'     => __('Color Hover', 'ecolab'),
                'condition'    => array('show_title' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_featured_block:hover a' => 'color: {{VALUE}} !important',

                ),
            )
        );
        $this->end_controls_section();
		
//Subtitle
        $this->start_controls_section(
            'section_subtitle_style',
            [
                'label' => esc_html__('SubTitle Setting', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'show_subtitle',
            array(
                'label' => esc_html__('Show Sub Title', 'ecolabe'),
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
                    '{{WRAPPER}} .mr_block_subtitle' => 'display: {{VALUE}} !important',
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
                'condition'    => array('show_title' => 'show'),
                'toggle' => true,
                'selectors' => array(

                    '{{WRAPPER}} .mr_block_subtitle' => 'text-align: {{VALUE}} !important',
                ),
            )
        );


        $this->add_control(
            'subtitle_padding',
            array(
                'label'     => __('Padding', 'ecolab'),
                'condition'    => array('show_title' => 'show'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}} .mr_block_subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'subtitle_typography',
                'condition'    => array('show_subtitle' => 'show'),
                'label'    => __('Typography', 'ecolab'),
                'selector' => '{{WRAPPER}} .mr_block_subtitle',
            )
        );
        $this->add_control(
            'subtitle_color',
            array(
                'label'     => __('Color', 'ecolab'),
                'condition'    => array('show_subtitle' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_block_subtitle' => 'color: {{VALUE}} !important',

                ),
            )
        );
        $this->add_control(
            'subtitle_hover_color',
            array(
                'label'     => __('Color Hover', 'ecolab'),
                'condition'    => array('show_subtitle' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_featured_block_subtitle:hover' => 'color: {{VALUE}} !important',

                ),
            )
        );
        $this->end_controls_section();
// Thumbnail SEttings		
		
	$this->start_controls_section(
            'thumbnail_control',
            array(
                'label' => __( 'Thumbanil Settings', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
        
$this->add_control(
            'show_thumbnail',
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
                    '{{WRAPPER}} .wps_project_gallery .wps_image_box' => 'display: {{VALUE}} !important',
                ),
            )
        );   



		
    $this->add_control(
            'wps_thumbnail_bg',
            [
                'label' => esc_html__('Background Color', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_project_gallery .wps_image_box' => 'background-color: {{VALUE}} !important;',
                ],
                'default' => '#3A9E1E', 
            ]
        );	
		
	    $this->add_control(
            'wps_thumbnail_hover_bg',
            [
                'label' => esc_html__('Background Hover Color', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_project_gallery .image-box:before' => 'background: {{VALUE}} !important;',
                ],
                'default' => '#3A9E1E', 
            ]
        );		
		
		
    $this->add_control(
            'thumbnail_padding',
            array(
                'label'     => __( 'Padding', 'wpsection' ),
             'condition'    => array( 'show_thumbnail' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
        
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .wps_project_gallery .wps_image_box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

    $this->add_control(
            'thumbnail_x_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                    'condition'    => array( 'show_thumbnail' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
            
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wps_project_gallery .wps_image_box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'thumbnail_border',
                    'condition'    => array( 'show_thumbnail' => 'show' ),
                'selector' => '{{WRAPPER}} .wps_project_gallery .wps_image_box',
            )
        );
                
            $this->add_control(
            'thumbnail_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'condition'    => array( 'show_thumbnail' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wps_image_box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );  
		
		
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'thumbnail_box_shadow',
                'label' => esc_html__('Box Shadow', 'wpsection'),
				'selector' => '{{WRAPPER}} .wps_project_gallery .wps_image_box',
			]
		);
        $this->end_controls_section();
        
//End of Thumbnail 
		
//Icon		

        $this->start_controls_section(
            'section_portfollio_style',
            [
                'label' => esc_html__('Icon Setting', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
  
	   $this->add_control(
            'wps_project_icon',
            array(
                'label' => esc_html__('Show Icons', 'wpsection'),
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
                    '{{WRAPPER}} .wps_project_icon' => 'display: {{VALUE}} !important',
                ),
            )
        );
	
		
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Icon Color', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_project_icon a' => 'color: {{VALUE}} !important;',
                ],
                'default' => '#fff', 
            ]
        );
        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color Hover', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_project_icon a:hover' => 'color: {{VALUE}} !important;',
                ],
                'default' => '#101A30', 
            ]
        );
        $this->add_control(
            'button_background_color',
            [
                'label' => esc_html__(' Background Color', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_project_icon a' => 'background-color: {{VALUE}} !important;',
                ],
                'default' => '#3A9E1E', 
            ]
        );
        $this->add_control(
            'wps_project_icon_bg_hover',
            [
                'label' => esc_html__('Background Hover Color', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_project_icon a:hover' => 'background-color: {{VALUE}} !important;',
                ],
                'default' => '#fff', 
            ]
        );
		
		
	        $this->add_control(
            'wps_project_icon_width',
            [
                'label' => esc_html__('Icon Box Width',  'wpsection'),
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
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_project_icon a' => 'width: {{SIZE}}{{UNIT}};',
                ]

            ]
        );


        $this->add_control(
            'wps_project_icon_height',
            [
                'label' => esc_html__('Icon Box Height', 'wpsection'),
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
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_project_icon a' => 'height: {{SIZE}}{{UNIT}};',

                ]
            ]
        );



        $this->add_control(
            'wps_project_icono_padding',
            array(
                'label'     => __('Padding', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}}  .wps_project_icon a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'wps_icon_margin',
            array(
                'label'     => __('Margin', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}}  .wps_project_icon a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

		
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'wps_projce_icon_typo',
                'label'    => __('Typography', 'wpsection'),
                'selector' => '{{WRAPPER}}  .wps_project_icon a',
            )
        );
		
		
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'wps_project_icon_border',
                'selector' => '{{WRAPPER}}  .wps_project_icon a ',
            )
        );


        $this->add_control(
            'wps_project_icon_radious',
            array(
                'label'     => __('Border Radius', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}}  .wps_project_icon a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
		
    


        $this->add_control(
            'wps_project_expand_icon_horizontal',
            [
                'label' => esc_html__('Expand Icon Horizontal',  'wpsection'),
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
                    '{{WRAPPER}} .wps_project_expand_icon' => 'left: {{SIZE}}{{UNIT}};',
                ]

            ]
        );
		
		
	$this->add_control(
            'wps_project_expand_icon_vertical',
            [
                'label' => esc_html__('Expand Icon Vertical', 'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2000,
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
                    '{{WRAPPER}} .wps_project_expand_icon' => 'top: {{SIZE}}{{UNIT}};',

                ]
            ]
        );	
		
		
		
        $this->add_control(
            'wps_project_plus_icon_horizontal',
            [
                'label' => esc_html__('Plus Icon Horizontal Position', 'wpsection'),
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
                    '{{WRAPPER}} .wps_project_plus_icon' => 'right: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'wps_project_plus_icon_vertical',
            [
                'label' => esc_html__('Plus Icon Vertical Position', 'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2000,
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
                    '{{WRAPPER}} .wps_project_plus_icon' => 'top: {{SIZE}}{{UNIT}};',

                ]
            ]
        );
	
		
		
	    $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'project_icon_shadow',
                'label' => esc_html__('Box Shadow', 'wpsection'),
				'selector' => '{{WRAPPER}} .wps_project_icon a',
			]
		);
		

        $this->end_controls_section();			
		
// Slide Arrow		
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
                    '{{WRAPPER}} .slider_path .owl-nav ' => 'display: {{VALUE}} !important',
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
                    '{{WRAPPER}}  .slider_path .owl-nav button' => 'color: {{VALUE}} !important',
    

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
                    '{{WRAPPER}}  .slider_path .owl-nav button:hover' => 'color: {{VALUE}} !important',
    

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
                    '{{WRAPPER}}  .slider_path .owl-nav button' => 'background: {{VALUE}} !important',
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
                    '{{WRAPPER}}  .slider_path .owl-nav button:hover' => 'background: {{VALUE}} !important',
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
                    '{{WRAPPER}} .slider_path .owl-nav button' => 'width: {{SIZE}}{{UNIT}};',
           
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
                    '{{WRAPPER}} .slider_path .owl-nav button' => 'height: {{SIZE}}{{UNIT}};',
   

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
                    '{{WRAPPER}}  .slider_path .owl-nav button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
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
                    '{{WRAPPER}}  .slider_path .owl-nav button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'slider_path_button_3_typography',
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'label'    => __('Typography', 'wpsection'),
                'selector' => '{{WRAPPER}}  .slider_path .owl-nav button',
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'slider_path_border_3',
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'selector' => '{{WRAPPER}}  .slider_path .owl-nav button',
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
                    '{{WRAPPER}}  .slider_path .owl-nav button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
                'label' => esc_html__('Box Shadow', 'wpsection'),
				'selector' => '{{WRAPPER}} .slider_path .owl-nav button',
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
                    '{{WRAPPER}} .slider_path .owl-nav .owl-prev' => 'left: {{SIZE}}{{UNIT}};',
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
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .slider_path .owl-nav .owl-next' => 'right: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .slider_path .owl-nav button ' => 'top: {{SIZE}}{{UNIT}};',
                ]
            ]
        );


        $this->end_controls_section();



        // Dot Button Setting

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
                    '{{WRAPPER}} .slider_path .owl-dots  ' => 'display: {{VALUE}} !important',
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
                    '{{WRAPPER}} .slider_path .owl-dots .owl-dot' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .slider_path .owl-dots .owl-dot' => 'height: {{SIZE}}{{UNIT}};',

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
                    '{{WRAPPER}}  .slider_path .owl-dots .owl-dot' => 'background: {{VALUE}} !important',

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
                    '{{WRAPPER}}  .slider_path .owl-dots .owl-dot:hover' => 'background: {{VALUE}} !important',

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
                    '{{WRAPPER}} .slider_path  .owl-dots .owl-dot.active' => 'background: {{VALUE}} !important',
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
                    '{{WRAPPER}}  .slider_path  .owl-dots .owl-dot' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
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
                    '{{WRAPPER}}  .slider_path  .owl-dots .owl-dot' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'slider_path_dot_border',
                'condition'    => array('slider_path_show_dot' => 'show'),
                'selector' => '{{WRAPPER}}  .slider_path  .owl-dots .owl-dot',
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
                    '{{WRAPPER}}  .slider_path  .owl-dots .owl-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
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
                    '{{WRAPPER}} .slider_path  .owl-dots .owl-dot' => 'left: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .slider_path  .owl-dots .owl-dot ' => 'top: {{SIZE}}{{UNIT}};',

                ]
            ]
        );
        $this->end_controls_section();
//Project Block 		
	   $this->start_controls_section(
                'wps_project_block_settings',
                array(
                    'label' => __( 'Block Setting', 'wpsection' ),
                    'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                )
            );

        
    $this->add_control(
            'wps_project_show_block',
            array(
                'label' => esc_html__( 'Show Block', 'wpsection' ),
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
                    '{{WRAPPER}} .wp_project_block' => 'display: {{VALUE}} !important',
                ),
            )
        );  


        

$this->add_control(
            'wps_project_box_height',
            [
                'label' => esc_html__( 'Min Height', 'wpsection' ),
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
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wp_project_block' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'wps_project_block_color',
            array(
                'label'     => __( 'Background Color', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wp_project_block' => 'background: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'wps_project_block_hover_color',
            array(
                'label'     => __( 'Hover Color', 'wpsection' ),
               //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wp_project_block:hover' => 'background: {{VALUE}} !important',
                ),
            )
        );
    
        $this->add_control(
            'wps_project_block_margin',
            array(
                'label'     => __( 'Block Margin', 'wpsection' ),
                    //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .wp_project_block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'wps_project_block_padding',
            array(
                'label'     => __( 'Block Padding', 'wpsection' ),
                    //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .wp_project_block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

            $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_project_block_shadow',
                    //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wp_project_block',
            ]
        );
      $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_project_block_X_hover_shadow',
                   // 'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Hover Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wp_project_block:hover',
            ]
        );

 
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'wps_project_block_border',
                //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Border', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wp_project_block',
            ]
        );
                
            $this->add_control(
            'wps_project_block_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wp_project_block' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
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
		    //Column Settings Area   

        if($settings['wps_columns'] == '10') {
                        $columns_markup = ' mr_column_10 ';
                    }      
         else if($settings['wps_columns'] == '9') {
                        $columns_markup = ' mr_column_9 ';
                    } 
        else if($settings['wps_columns'] == '8') {
                        $columns_markup = ' mr_column_8 ';
                    }   
         else if($settings['wps_columns'] == '7') {
                        $columns_markup = ' mr_column_7 ';
                    } 
        else if($settings['wps_columns'] == '6') {
                        $columns_markup = 'col-lg-2 ';
                    }
        else if($settings['wps_columns'] == '5') {
                        $columns_markup = ' mr_column_5 ';
                    } 
        else if($settings['wps_columns'] == '4') {
                        $columns_markup = 'col-lg-3 ';
                    }   
         else if($settings['wps_columns'] == '3') {
                        $columns_markup = 'col-lg-4 ';
                    }
        else if($settings['wps_columns'] == '2') {
                        $columns_markup = 'col-lg-6 ';
                    } 
        else if($settings['wps_columns'] == '1') {
                        $columns_markup = 'col-lg-12 ';
                    }

// Tab Column 

  //Column Settings Area   

        if($settings['wps_columns_tab'] == '10') {
                        $columns_markup_tab = ' mr_column_10 ';
                    }      
         else if($settings['wps_columns_tab'] == '9') {
                        $columns_markup_tab = ' mr_column_9 ';
                    } 
        else if($settings['wps_columns_tab'] == '8') {
                        $columns_markup_tab = ' mr_column_8 ';
                    }   
         else if($settings['wps_columns_tab'] == '7') {
                        $columns_markup_tab = ' mr_column_7 ';
                    } 
        else if($settings['wps_columns_tab'] == '6') {
                        $columns_markup_tab = ' col-md-2';
                    }
        else if($settings['wps_columns_tab'] == '5') {
                        $columns_markup_tab = ' mr_column_5 ';
                    } 
        else if($settings['wps_columns_tab'] == '4') {
                        $columns_markup_tab = ' col-md-3 ';
                    }   
         else if($settings['wps_columns_tab'] == '3') {
                        $columns_markup_tab = ' col-md-4';
                    }
        else if($settings['wps_columns_tab'] == '2') {
                        $columns_markup_tab = ' col-md-6';
                    } 
        else if($settings['wps_columns_tab'] == '1') {
                        $columns_markup_tab = ' col-md-12';
                    }


$columns_markup_print = $columns_markup . ' ' . $columns_markup_tab;
?>




<?php
        echo '
        <style>
		
.wps_project_gallery .slider_path .owl-nav button,.slider_path .owl-theme .owl-dots {
    position: absolute;
}
.wps_project_gallery  .slider_path .owl-dots{
display:flex;
}


        </style>';

        ?>


        <?php
        echo '
        <script>
          jQuery(document).ready(function($)
          {
            //put the js code under this line 
            //project-carousel
            if ($(".project-carousel").length) {
              $(".project-carousel").owlCarousel({
                loop:true,
			    margin:0,
			    nav:true,
			    smartSpeed: 500,
			    autoplay: 1000,
                navText: ["<span class=\'eicon-arrow-left\'></span>", "<span class=\'eicon-arrow-right\'></span>"],
                responsive:{
                  0:{
                    items:1
                  },
                  480:{
                    items:1
                  },
                  600:{
                    items:' . esc_js($settings['wps_columns_tab']) . '
                  },
                  800:{
                    items:' . esc_js($settings['wps_columns_tab']) . '
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



   <section class="wps_project_gallery">
	     <div class="auto-container">
        <div class="project-section">
		 <div class="<?php echo $settings['enable_slide'] ? 'project-carousel owl-carousel slider_path owl-theme owl-dots owl-nav ' . esc_attr($widget_id) : 'row'; ?>">			
					
                    <?php foreach ($settings['repeater'] as $item) : ?>
				 <div class="wp_project_block project-block-one <?php echo $settings['enable_slide'] ? '' : $columns_markup_print; ?>">			
                            <div class="inner-box mr_block_bttn mr_btton_a">
                                <figure class="image-box wps_image_box">
                                    <?php if (wp_get_attachment_url($item['block_image']['id'])) : ?>
                                        <img class="mr_product_thumb" src="<?php echo wp_get_attachment_url($item['block_image']['id']); ?>" alt="">
                                    <?php else : ?>
                                        <div class="noimage"></div>
                                    <?php endif; ?>
                                </figure>
                                <div class="content-box">
                                 
									
					<div class="wps_project_icon view-btn">
                    <?php if (!empty($settings['block_plus_icon']['value'])) : ?>
                      <a href="<?php echo wp_get_attachment_url($item['block_image_one']['id']); ?>" class="wps_project_plus_icon lightbox-image" data-fancybox="gallery">
                        <i class="<?php echo esc_attr($settings['block_plus_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['block_link_icon']['value'])) : ?>
                      <a href="<?php echo esc_url($item['block_btnlink_x_one']['url']); ?>" class="wps_project_expand_icon wps_image_link ">
                        <i class="<?php echo esc_attr($settings['block_link_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                  </div>		
									
									
									
									
									
                                    <div class="text-box">
                                        <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle'], $allowed_tags); ?></p>
                                        <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x']['url']); ?>"><?php echo wp_kses($item['block_title'], $allowed_tags); ?></a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div> 
			</div>
        </section>

<?php
    }
}

// Register widget
Plugin::instance()->widgets_manager->register(new \wps_gallery_slide());
