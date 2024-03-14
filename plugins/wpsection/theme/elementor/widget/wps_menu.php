<?php

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Plugin;
use Elementor\Repeater;



class wpsection_wps_menu_Widget extends \Elementor\Widget_Base {
	public function get_name() {
		return 'wpsection_wps_menu';
	}

	public function get_title() {
		return __( 'Menu', 'wpsection' );
	}

	public function get_icon() {
		 return ' eicon-nav-menu';
	}

	public function get_keywords() {
		return [ 'wpsection', 'menu' ];
	}

	  public function get_categories() {
          return ['wpsection_category'];
    }


	

	protected function register_controls() {
		$this->start_controls_section(
			'menu_settings',
			[
				'label' => __( 'Menu General', 'wpsection' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

	
	
		
		
  $this->add_control(
            'wps_main_menu_container_width',
            [
                'label' => esc_html__( 'Main Section Width ', 'wpsection' ),
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
                    '{{WRAPPER}} .wps_header_area' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        ); 


    $this->add_control(
        'enable_wps_site_logo_one',
        [
            'label' => esc_html__('Enable Logo', 'wpsection'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes', // Set the default value
            'label_on' => esc_html__('Yes', 'wpsection'),
            'label_off' => esc_html__('No', 'wpsection'),
        ]
    );		

				
 $this->add_control(
    'wps_site_logo_one',
    [
        'label' => __('Logo', 'wpsection'),
		 'condition' => ['enable_wps_site_logo_one' => 'yes'],
        'type' => Controls_Manager::MEDIA,
       
    ]
);	
		
	  $this->add_control(
            'wps_main_logo_width',
            [
                'label' => esc_html__( 'Logo Width ', 'wpsection' ),
				'condition' => ['enable_wps_site_logo_one' => 'yes'],
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
                    '{{WRAPPER}} .wps_site_logo_link_one' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        ); 		
		
 $this->add_control(
        'wps_main_logo_order',
        [
            'label'   => esc_html__( 'Logo Order', 'wpsection' ),
			 'condition' => ['enable_wps_site_logo_one' => 'yes'],
            'type'    => Controls_Manager::SELECT,
            'default' => '1',
            'options' => array(
                '1'   => esc_html__( 'Order One', 'wpsection' ),
                '2'   => esc_html__( 'Order Two', 'wpsection' ),
				'3'   => esc_html__( 'Order Three', 'wpsection' ),
				'4'   => esc_html__( 'Order Four', 'wpsection' ),
            
            ),
        ]
    );			

 $this->add_control(
        'wps_main_menu_order',
        [
            'label'   => esc_html__( 'Menu Order', 'wpsection' ),
            'type'    => Controls_Manager::SELECT,
            'default' => '2',
            'options' => array(
                '1'   => esc_html__( 'Order One', 'wpsection' ),
                '2'   => esc_html__( 'Order Two', 'wpsection' ),
				'3'   => esc_html__( 'Order Three', 'wpsection' ),
				'4'   => esc_html__( 'Order Four', 'wpsection' ),
            
            ),
        ]
    );	

		

		
    $this->add_control(
        'enable_wps_main_icon_one',
        [
            'label' => esc_html__('Enable Search', 'wpsection'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes', // Set the default value
            'label_on' => esc_html__('Yes', 'wpsection'),
            'label_off' => esc_html__('No', 'wpsection'),
        ]
    );	
		
		
  $this->add_control(
    'wps_main_icon_one',
    [
        'label' => esc_html__('Icon', 'wpsection'),
		'condition' => ['enable_wps_main_icon_one' => 'yes'],
        'type' => Controls_Manager::ICONS,
        'default' => [
            'value' => 'eicon-search',
            'library' => 'solid', 
        ],
    ]
);
			
		
 $this->add_control(
        'wps_main_search_order',
        [
            'label'   => esc_html__( 'Search Order', 'wpsection' ),
			'condition' => ['enable_wps_main_icon_one' => 'yes'],
            'type'    => Controls_Manager::SELECT,
            'default' => '3',
            'options' => array(
                '1'   => esc_html__( 'Order One', 'wpsection' ),
                '2'   => esc_html__( 'Order Two', 'wpsection' ),
				'3'   => esc_html__( 'Order Three', 'wpsection' ),
				'4'   => esc_html__( 'Order Four', 'wpsection' ),
            
            ),
        ]
    );	
		

		

$this->end_controls_section();	
		
		
//sticky menu		
$this->start_controls_section(
                    'wps_sticky_menu_settings',
                    [
                        'label' => __( 'Sticky Menu Settings', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                ); 
		
	
	$this->add_control(
            'wps_main_slicky_width',
            [
                'label' => esc_html__( 'Sticky Width ', 'wpsection' ),
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
                    '{{WRAPPER}} .mr_sticky-header .mr_outer-container' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        ); 	
		
		
		
   $this->add_control(
        'enable_wps_site_logo_two',
        [
            'label' => esc_html__('Enable Logo', 'wpsection'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes', // Set the default value
            'label_on' => esc_html__('Yes', 'wpsection'),
            'label_off' => esc_html__('No', 'wpsection'),
        ]
    );			
		
	 $this->add_control(
    'wps_site_logo_two',
    [
        'label' => __('Logo', 'wpsection'),
		'condition' => ['enable_wps_site_logo_two' => 'yes'],
        'type' => Controls_Manager::MEDIA,
       
    ]
);		
 $this->add_control(
        'wps_main_logo_order_two',
        [
            'label'   => esc_html__( 'Logo Order', 'wpsection' ),
			'condition' => ['enable_wps_site_logo_two' => 'yes'],
            'type'    => Controls_Manager::SELECT,
            'default' => '1',
            'options' => array(
                '1'   => esc_html__( 'Order One', 'wpsection' ),
                '2'   => esc_html__( 'Order Two', 'wpsection' ),
				'3'   => esc_html__( 'Order Three', 'wpsection' ),
				'4'   => esc_html__( 'Order Four', 'wpsection' ),
            
            ),
        ]
    );			

		
		
		
 $this->add_control(
        'wps_main_menu_order_two',
        [
            'label'   => esc_html__( 'Menu Order', 'wpsection' ),
            'type'    => Controls_Manager::SELECT,
            'default' => '2',
            'options' => array(
                '1'   => esc_html__( 'Order One', 'wpsection' ),
                '2'   => esc_html__( 'Order Two', 'wpsection' ),
				'3'   => esc_html__( 'Order Three', 'wpsection' ),
				'4'   => esc_html__( 'Order Four', 'wpsection' ),
            
            ),
        ]
    );	

		
	
   $this->add_control(
        'enable_wps_main_icon_two',
        [
            'label' => esc_html__('Enable Search', 'wpsection'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes', // Set the default value
            'label_on' => esc_html__('Yes', 'wpsection'),
            'label_off' => esc_html__('No', 'wpsection'),
        ]
    );			
		
  $this->add_control(
    'wps_main_icon_two',
    [
        'label' => esc_html__('Icon', 'wpsection'),
		'condition' => ['enable_wps_main_icon_two' => 'yes'],
        'type' => Controls_Manager::ICONS,
        'default' => [
            'value' => 'eicon-search',
            'library' => 'solid', 
        ],
    ]
);
			
		
 $this->add_control(
        'wps_main_search_order_two',
        [
            'label'   => esc_html__( 'Search Order', 'wpsection' ),
			'condition' => ['enable_wps_main_icon_two' => 'yes'],
            'type'    => Controls_Manager::SELECT,
            'default' => '3',
            'options' => array(
                '1'   => esc_html__( 'Order One', 'wpsection' ),
                '2'   => esc_html__( 'Order Two', 'wpsection' ),
				'3'   => esc_html__( 'Order Three', 'wpsection' ),
				'4'   => esc_html__( 'Order Four', 'wpsection' ),
            
            ),
        ]
    );	
			
		
$this->end_controls_section();		
		
//Mobile Menu
$this->start_controls_section(
                    'wps_mobile_menu_settings',
                    [
                        'label' => __( 'Mobile Menu Settings', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                ); 
		
$this->add_control(
    'wps_site_logo_three',
    [
        'label' => __('Logo', 'wpsection'),
        'type' => Controls_Manager::MEDIA,
       
    ]
);			
		
$this->end_controls_section();	
//start of style settings		
	
$this->start_controls_section(
            'wps_menu_settings',
            array(
                'label' => __( 'Menu Main Area Settings', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
   
       $this->add_control(
            'wps_project_block_color',
            array(
                'label'     => __( 'Background Color', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wps_header_area' => 'background: {{VALUE}} !important',
                ),
            )
        );

   	     $this->add_control(
            'wps_project_block_hover_color',
            array(
                'label'     => __( 'Background Hover Color', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wps_header_area:hover' => 'background: {{VALUE}} !important',
                ),
            )
        );
	$this->add_control(
			'wps_menu_text_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
				'condition'    => array( 'wps_menu_show_text' => 'show' ),
				'selectors' => array(
					'{{WRAPPER}} .wps_header_area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
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
                    '{{WRAPPER}}  .wps_header_area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

		
		
 
	
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'wps_project_block_border',
                //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Border', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_header_area',
            ]
        );
                
            $this->add_control(
            'wps_project_block_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wps_header_area' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );	
		
		
	
    $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_project_block_shadow',
                    //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_header_area',
            ]
        );
		
      $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_project_block_X_hover_shadow',
                'label' => esc_html__( 'Hover Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_header_area:hover',
            ]
        );	
		

		$this->end_controls_section();
//Single Menu=========				
		
$this->start_controls_section(
            'wps_menu_one_settings',
            array(
                'label' => __( 'First Level Menu Settings', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
		
	$this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'wps_menu_one_typography',
                'label'    => __( 'Typography', 'wpsection' ),
                'selector' => '{{WRAPPER}}  .wps_header_area .mr_navigation > li > a ',
            )
        );
		
	   $this->add_control(
            'wps_menu_one_color',
            array(
                'label'     => __( 'Color', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wps_header_area .mr_navigation > li > a' => 'color: {{VALUE}} !important',
                ),
            )
        );
	
   
       $this->add_control(
            'wps_menu_one_bg_color',
            array(
                'label'     => __( 'Background Color', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wps_header_area .mr_navigation > li > a' => 'background: {{VALUE}} !important',
                ),
            )
        );
  $this->add_control(
            'wps_menu_one_bg_hover_color',
            array(
                'label'     => __( 'Background Hover Color', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wps_header_area .mr_navigation > li:hover > a' => 'background: {{VALUE}} !important',
                ),
            )
        );

   	
	$this->add_control(
			'wps_menu_one_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}} .wps_header_area .mr_navigation > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);
		

		
	$this->add_control(
            'wps_menu_one_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                    //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .wps_header_area .mr_navigation > li ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

		
		
 
	
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'wps_menu_one_border',
                //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Border', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_header_area .mr_navigation > li > a',
            ]
        );
                
            $this->add_control(
            'wps_menu_one_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wps_header_area .mr_navigation > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );	
		
		
	
    $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_menu_one_shadow',
                    //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_header_area .mr_navigation > li',
            ]
        );
		
      $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_menu_one_hover_shadow',
                'label' => esc_html__( 'Hover Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_header_area .mr_navigation > li:hover',
            ]
        );	
		

		$this->end_controls_section();	
		
		
		
		
		
		
//2nd level =========				
		
$this->start_controls_section(
            'wps_menu_two_settings',
            array(
                'label' => __( 'Second Level Menu Settings', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
		
		
	 $this->add_control(
            'wps_menu_submenu_two_width',
            [
                'label' => esc_html__( 'Submenu area Width ', 'wpsection' ),
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
                    '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );   		
	    $this->add_control(
            'wps_menu_two_submenu_bg_color',
            array(
                'label'     => __( 'Submenu area Background ', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul' => 'background: {{VALUE}} !important',
                ),
            )
        );	
    $this->add_control(
            'wps_menu_two_submenu_bg_hover_color',
            array(
                'label'     => __( 'Hover Submenu area Background ', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul:hover' => 'background: {{VALUE}} !important',
                ),
            )
        );		
		
$this->add_control(
			'wps_menu_submenu_two_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);
		

		
	$this->add_control(
            'wps_menu_submenu_two_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                    //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

		
		
 
	
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'wps_menu_submenu_two_border',
                //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Border', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul',
            ]
        );
                
            $this->add_control(
            'wps_menu_two_submenu_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );	
		
		
	
    $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_menu_two_submenu_shadow',
                    //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul',
            ]
        );
		
      $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_menu_two_hover_shadow',
                'label' => esc_html__( 'Hover Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul:hover',
            ]
        );			
// Separator
$this->add_control(
    'separator_menu_two',
    [
        'type' => \Elementor\Controls_Manager::DIVIDER,
    ]
);
		
// Single Level Two Menu 		
	$this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'wps_menu_two_typography',
                'label'    => __( 'Typography', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > a',
            )
        );
		
	   $this->add_control(
            'wps_menu_two_color',
            array(
                'label'     => __( 'Color', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > a' => 'color: {{VALUE}} !important',
                ),
            )
        );
	
   
       $this->add_control(
            'wps_menu_two_bg_color',
            array(
                'label'     => __( 'Background Color', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > a' => 'background: {{VALUE}} !important',
                ),
            )
        );
  $this->add_control(
            'wps_menu_two_bg_hover_color',
            array(
                'label'     => __( 'Background Hover Color', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}  .mr_main-menu .mr_navigation > li > ul > li:hover > a' => 'background: {{VALUE}} !important',
                ),
            )
        );

   	
	$this->add_control(
			'wps_menu_two_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);
		

		
	$this->add_control(
            'wps_menu_two_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                    //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .mr_main-menu .mr_navigation > li > ul > li ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

		
		
 
	
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'wps_menu_two_border',
                //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Border', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > a',
            ]
        );
                
            $this->add_control(
            'wps_menu_two_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );	
		
		
	
    $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_menu_two_shadow',
                    //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul',
            ]
        );
		
      $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_menu_two_hover_shadow',
                'label' => esc_html__( 'Hover Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul:hover',
            ]
        );	
		

		$this->end_controls_section();				
		
		
		
//3rd level =========				
		
$this->start_controls_section(
            'wps_menu_three_settings',
            array(
                'label' => __( 'Third Level Menu Settings', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
		
		
	 $this->add_control(
            'wps_menu_submenu_three_width',
            [
                'label' => esc_html__( 'Submenu area Width ', 'wpsection' ),
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
                    '{{WRAPPER}} .mr_main-menu .mr_navigation li > ul > li.dropdown > ul' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );   		
	    $this->add_control(
            'wps_menu_three_submenu_bg_color',
            array(
                'label'     => __( 'Submenu area Background ', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > ul' => 'background: {{VALUE}} !important',
                ),
            )
        );	
    $this->add_control(
            'wps_menu_three_submenu_bg_hover_color',
            array(
                'label'     => __( 'Hover Submenu area Background ', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > ul:hover' => 'background: {{VALUE}} !important',
                ),
            )
        );		
		
$this->add_control(
			'wps_menu_submenu_three_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);
		

		
	$this->add_control(
            'wps_menu_submenu_three_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                    //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > ul' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

		
		
 
	
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'wps_menu_submenu_three_border',
                //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Border', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > ul',
            ]
        );
                
            $this->add_control(
            'wps_menu_three_submenu_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > ul' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );	
		
		
	
    $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_menu_three_submenu_shadow',
                    //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > ul',
            ]
        );
		
      $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_menu_three_hover_shadow',
                'label' => esc_html__( 'Hover Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > ul:hover ',
            ]
        );			
// Separator
$this->add_control(
    'separator_menu_three',
    [
        'type' => \Elementor\Controls_Manager::DIVIDER,
    ]
);
		
// Single Level Two Menu 		
	$this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'wps_menu_three_typography',
                'label'    => __( 'Typography', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > ul > li > a',
            )
        );
		
	   $this->add_control(
            'wps_menu_three_color',
            array(
                'label'     => __( 'Color', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > ul > li > a' => 'color: {{VALUE}} !important',
                ),
            )
        );
	
   
       $this->add_control(
            'wps_menu_three_bg_color',
            array(
                'label'     => __( 'Background Color', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > ul > li > a' => 'background: {{VALUE}} !important',
                ),
            )
        );
  $this->add_control(
            'wps_menu_three_bg_hover_color',
            array(
                'label'     => __( 'Background Hover Color', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}  .mr_main-menu .mr_navigation > li > ul > li > ul > li:hover > a' => 'background: {{VALUE}} !important',
                ),
            )
        );

   	
	$this->add_control(
			'wps_menu_three_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > ul > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);
		

		
	$this->add_control(
            'wps_menu_three_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                    //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .mr_main-menu .mr_navigation > li > ul > li ul > li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

		
		
 
	
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'wps_menu_three_border',
                //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Border', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > ul > li > a',
            ]
        );
                
            $this->add_control(
            'wps_menu_three_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > ul > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );	
		
		
	
    $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_menu_three_shadow',
                    //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > ul',
            ]
        );
		
      $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_menu_three_hover_shadow',
                'label' => esc_html__( 'Hover Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_main-menu .mr_navigation > li > ul > li > ul:hover',
            ]
        );	
		

		$this->end_controls_section();				
				
		
		
		
		
		
//End 
	}

	protected function render() {
		global $plugin_root;
		$settings = $this->get_settings_for_display();

?>

<?php
 echo '
 <style>
 
 //CSS code Will be here 
 

 
 //CSS code End Here
 
</style>';		
		

	
      echo '
     <script>
 jQuery(document).ready(function($)
 {

//put the js code under this line 


function headerStyle() {
	if ($(".main-header").length) {
		var windowpos = $(window).scrollTop();
		var siteHeader = $(".main-header");
		var scrollLink = $(".scroll-top");
		if (windowpos >= 110) {
			siteHeader.addClass("fixed-header");
			scrollLink.addClass("open");
		} else {
			siteHeader.removeClass("fixed-header");
			scrollLink.removeClass("open");
		}
	}
}

headerStyle();

//Submenu Dropdown Toggle
if ($(".main-header li.dropdown ul").length) {
	

	$(".main-header .navigation li.dropdown").append("<div class=\"dropdown-btn\"><span class=\"fas fa-angle-down\"></span></div>");


}


if ($(".mobile-menu").length) {
	$(".mobile-menu .menu-box").mCustomScrollbar();

	var mobileMenuContent = $(".main-header .menu-area .main-menu").html();
	$(".mobile-menu .menu-box .menu-outer").append(mobileMenuContent);
	$(".mr_menu_sticky .main-menu").append(mobileMenuContent);

	//Dropdown Button
	$(".mobile-menu li.dropdown .dropdown-btn").on("click", function() {
		$(this).toggleClass("open");
		$(this).prev("ul").slideToggle(500);
	});

	//Dropdown Button
	$(".mobile-menu li.dropdown .dropdown-btn").on("click", function() {
		$(this).prev(".megamenu").slideToggle(900);
	});

	//Menu Toggle Btn
	$(".mobile-nav-toggler").on("click", function() {
		$("body").addClass("mobile-menu-visible");
	});

	//Menu Toggle Btn
	$(".mobile-menu .menu-backdrop, .mobile-menu .close-btn").on("click", function() {
		$("body").removeClass("mobile-menu-visible");
	});
}


	$(window).on("scroll", function() {
		headerStyle();

	});



//put the code above the line 

  });
</script>';		
		
		
		
?>


  <!--Search Popup-->
        <div id="mr_search-popup" class="mr_search-popup">
            <div class="mr_popup-inner">
                <div class="mr_upper-box clearfix">
                    <div class="mr_close-search pull-right">								
<?php echo (class_exists('Elementor\Plugin')) ? '<i class="eicon-close-circle"></i>' : '<i class="dashicons dashicons-dismiss"></i>'; ?>
					</div>
                </div>
                <div class="mr_overlay-layer"></div>
                <div class="auto-container">
                    <div class="mr_search-form">
                       <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post">	
                            <div class="mr_form-group">
                               <fieldset>    
									<input type="search" name="s" class="form-control" placeholder=" " required="">
									<button  class="search_button"  type="submit">						
	 <i class="<?php echo esc_attr($settings['wps_main_icon_one']['value']); ?>"></i>	
								   </button>
                                </fieldset>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>





        <header class="wps_header_area mr_main-header ">
            <div class="mr_header-lower">
                <div class="mr_outer-container">
                    <div class="mr_outer-box">
						
					<?php if ('yes' === $this->get_settings('enable_wps_site_logo_one')) : ?>
                        <div class="mr_logo-box order-<?php echo wp_kses($settings['wps_main_logo_order'], $allowed_tags); ?>">
							<figure class="mr_logo"> 
								<a href="<?php echo( home_url( '/' ) ); ?>" class="wps_site_logo_link_one" >
									<img class="wps_site_logo_one " src="<?php echo wp_get_attachment_url($settings['wps_site_logo_one']['id']); ?>" alt=""> 
								</a>
							</figure> 
                        </div>	
					<?php endif; ?>
						
						
                        <div class="mr_menu-area clearfix order-<?php echo wp_kses($settings['wps_main_menu_order'], $allowed_tags); ?>">
                            <div class="mr_mobile-nav-toggler">
                                <i class="mr_icon-bar"></i>
                                <i class="mr_icon-bar"></i>
                                <i class="mr_icon-bar"></i>
                            </div>
                            <nav class="mr_main-menu navbar-expand-md navbar-light">
                                <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
									<ul class="mr_navigation clearfix home-menu <?php echo $settings['enable_megamenu'] ? 'wps_mega' : ' ' ?>">
										
                                     <?php wp_nav_menu( array( 'theme_location' => 'main_menu', 'container_id' => 'navbarSupportedContent',
										'container_class'=>'collapse navbar-collapse sub-menu-bar',
										'menu_class'=>'nav navbar-nav',
										'fallback_cb'=>false, 
										'add_li_class'  => 'nav-item',
										'items_wrap' => '%3$s', 
										'container'=>false,
										'depth'=>'3',
										'walker'=> new Bootstrap_walker()  
										) ); ?>
                                    </ul>
                                </div>
                            </nav>
                        </div>
	
						
					<?php if ('yes' === $this->get_settings('enable_wps_main_icon_one')) : ?>	
                        <div class="mr_nav-right order-<?php echo wp_kses($settings['wps_main_search_order'], $allowed_tags); ?>">
                            <div class="mr_search-box-outer mr_search-toggler">
             						 <i class="<?php echo esc_attr($settings['wps_main_icon_one']['value']); ?>"></i>
                            </div>
                        </div>
					<?php endif; ?>	
						
                    </div>
                </div>
            </div>

 
			
			<!--sticky Header-->
            <div class="mr_sticky-header">
                <div class="mr_outer-container">
                    <div class="mr_outer-box">
                        
					<?php if ('yes' === $this->get_settings('enable_wps_site_logo_two')) : ?>	
						<div class="mr_logo-box">
                            <figure class="mr_logo">
								<a href="<?php echo( home_url( '/' ) ); ?>" class="wps_site_logo_link_two" >
									<img class="wps_site_logo_two " src="<?php echo wp_get_attachment_url($settings['wps_site_logo_two']['id']); ?>" alt=""> 
								</a>
							</figure>
                        </div>
					<?php endif; ?>
						
						
				
                        <div class="mr_menu-area clearfix">
                            <nav class="mr_main-menu clearfix">
                                <!--Keep This Empty / Menu will come through Javascript-->
                            </nav>
                        </div>

				<?php if ('yes' === $this->get_settings('enable_wps_main_icon_two')) : ?>		
                        <div class="mr_nav-right">
                            <div class="mr_search-box-outer mr_search-toggler">
								 <i class="<?php echo esc_attr($settings['wps_main_icon_two']['value']); ?>"></i>	
                            </div>
                        </div>
				<?php endif; ?>		
                    </div>
                </div>
            </div>
		
			
			
			
        </header>
        <!-- main-header end -->



     <!-- Mobile Menu  -->
        <div class="mr_mobile-menu">
            <div class="mr_menu-backdrop"></div>
            <div class="mr_close-btn"><i class="eicon-close-circle"></i></div>
            <nav class="mr_menu-box">
                <div class="mr_nav-logo">
						<a href="<?php echo( home_url( '/' ) ); ?>" class="wps_site_logo_link_three" >
							<img class="wps_site_logo_three " src="<?php echo wp_get_attachment_url($settings['wps_site_logo_three']['id']); ?>" alt=""> 
						</a>
				</div>
                <div class="mr_menu-outer"><!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header--></div>
            </nav>
        </div>        
     <!-- End Mobile Menu -->





        <?php

	}
}

// Register widget
Plugin::instance()->widgets_manager->register( new \wpsection_wps_menu_Widget() );