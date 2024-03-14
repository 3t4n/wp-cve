<?php

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;
use Elementor\Utils;

class wpsection_wps_blog_grid_Widget extends \Elementor\Widget_Base
{
	public function get_name()
	{
		return 'wpsection_wps_blog_grid';
	}

	public function get_title()
	{
		return __('Blog Grid', 'wpsectionsupport');
	}

	public function get_icon()
	{
		return 'wpsd dashicons eicon-post';
	}

	public function get_keywords()
	{
		return ['wpsection', 'wps_blog_grid'];
	}

	public function get_categories()
	{
		return ['wpsection_category'];
	}


	
	protected function _register_controls()
	{
		$this->start_controls_section(
			'wps_blog_grid',
			[
				'label' => esc_html__('Blog Grid', 'wpsection'),
			]
		);


		$this->add_control(
			'thumb',
			[
				'label'   => esc_html__('Choose Post Image', 'constech'),
				'label_block' => true,
				'type'    => Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => array(
					'style1' => esc_html__('Meta Box Image', 'constech'),
					'style2' => esc_html__('Dafult Thumbnail', 'constech'),
				),
			]
		);
	
		$this->add_control(
			'column',
			[
				'label'   => esc_html__('Column', 'wpsection'),
				'type'    => Controls_Manager::SELECT,
				'default' => '4',
				'options' => array(
					'12'   => esc_html__('One Column', 'wpsection'),
					'6'   => esc_html__('Two Column', 'wpsection'),
					'4'   => esc_html__('Three Column', 'wpsection'),
					'3'   => esc_html__('Four Column', 'wpsection'),
					'2'   => esc_html__('Six Column', 'wpsection'),
				),
			]
		);
		
	$this->add_control(
			'column_tab',
			[
				'label'   => esc_html__('Tab Column', 'wpsection'),
				'type'    => Controls_Manager::SELECT,
				'default' => '4',
				'options' => array(
					'12'   => esc_html__('One Column', 'wpsection'),
					'6'   => esc_html__('Two Column', 'wpsection'),
					'4'   => esc_html__('Three Column', 'wpsection'),
					'3'   => esc_html__('Four Column', 'wpsection'),
					'2'   => esc_html__('Six Column', 'wpsection'),
				),
			]
		);
	
	
	
		$this->add_control(
			'query_number',
			[
				'label'   => esc_html__('Number of post', 'wpsection'),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,
			]
		);
		$this->add_control(
			'query_orderby',
			[
				'label'   => esc_html__('Order By', 'wpsection'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'       => esc_html__('Date', 'wpsection'),
					'title'      => esc_html__('Title', 'wpsection'),
					'menu_order' => esc_html__('Menu Order', 'wpsection'),
					'rand'       => esc_html__('Random', 'wpsection'),
				),
			]
		);
		$this->add_control(
			'query_order',
			[
				'label'   => esc_html__('Order', 'wpsection'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => array(
					'DESc' => esc_html__('DESC', 'wpsection'),
					'ASC'  => esc_html__('ASC', 'wpsection'),
				),
			]
		);
		$this->add_control(
			'query_exclude',
			[
				'label'       => esc_html__('Exclude', 'wpsection'),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__('Exclude posts, pages, etc. by ID with comma separated.', 'wpsection'),
			]
		);
		
	

$this->add_control(
    'query_category',
    [
        'type' => Controls_Manager::SELECT2,
        'label' => esc_html__('Category', 'wpsection'),
        'options' => get_wps_blog_categories(),
        'multiple' => true, // Allow multiple selections
    ]
);
			

		
$this->add_control(
    'text_limit',
    [
        'label' => esc_html__('Text Limit', 'wpsection'),
        'type' => Controls_Manager::NUMBER,
        'default' => 100, // Adjust the default value
        'min' => 1,
        'max' => 500, // Adjust the max value
        'step' => 1,
    ]
);
		
		
		$this->add_control(
			'read_more_bttn',
			[
				'label'       => __('Button', 'wpsection'),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => esc_html__('Enter your Button Title', 'wpsection'),
				'default' => esc_html__('Read More', 'wpsection'),
			]
		);
		
		
/*
		$this->add_control(
			'show_pagination',
			[
				'label' => __('Enable/Disable Pagination', 'wpsection'),
				'type'     => Controls_Manager::SWITCHER,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __('Enable/Disable Pagination', 'wpsection'),
			]
		);
*/		
		$this->add_control(
			'comment_icons',
			[
				'label' => esc_html__('Enter The icons', 'rashid'),
                'type' => Controls_Manager::ICONS,

			]
		);

		$this->end_controls_section();
		
		
		
		$this->start_controls_section(
            'blog_thumbnail_control',
            array(
                'label' => __( 'Image Settings', 'wpsection' ),
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
                    '{{WRAPPER}} .mr_product_thumb' => 'display: {{VALUE}} !important',
                ),
            )
        );      
    

    $this->add_control(
            'blog_thumbnail_padding',
            array(
                'label'     => __( 'Padding', 'wpsection' ),
             'condition'    => array( 'show_thumbnail' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
        
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .mr_product_thumb ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

    $this->add_control(
            'blog_thumbnail_x_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                    'condition'    => array( 'show_thumbnail' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
            
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .mr_product_thumb ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'blog_thumbnail_border',
                    'condition'    => array( 'show_thumbnail' => 'show' ),
                'selector' => '{{WRAPPER}} .mr_product_thumb ',
            )
        );
                
            $this->add_control(
            'blog_thumbnail_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'condition'    => array( 'show_thumbnail' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .mr_product_thumb ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );  
        $this->end_controls_section();
		
//Title		
		$this->start_controls_section(
			'content_section_two',
			[
				'label' => __('Title Setting', 'wpsection'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'show_title',
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
					'{{WRAPPER}} .mr_block_title' => 'display: {{VALUE}} !important',
				),
			)
		);
		$this->add_control(
			'blog_title_alingment',
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
				'condition'    => array('show_title' => 'show'),
				'toggle' => true,
				'selectors' => array(

					'{{WRAPPER}} .mr_block_title' => 'text-align: {{VALUE}} !important',
				),
			)
		);


		$this->add_control(
			'blog_title_padding',
			array(
				'label'     => __('Padding', 'wpsection'),
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
				'selector' => '{{WRAPPER}} .mr_block_title',
			)
		);
		$this->add_control(
			'blog_title_color',
			array(
				'label'     => __('Color', 'wpsection'),
				'condition'    => array('show_title' => 'show'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mr_block_title a' => 'color: {{VALUE}} !important',

				),
			)
		);
		$this->add_control(
			'blog_title_hover_color',
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


		$this->start_controls_section(
			'section_subtitle_style',
			[
				'label' => esc_html__('Catagories Setting', 'wpsection'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_subtitle',
			array(
				'label' => esc_html__('Catagories', 'ecolabe'),
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
			'blog_subtitle_alingment',
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
				'condition'    => array('show_subtitle' => 'show'),
				'toggle' => true,
				'selectors' => array(

					'{{WRAPPER}} .subtitle_alignment' => 'text-align: {{VALUE}} !important',
				),
			)
		);


		$this->add_control(
			'blog_subtitle_padding',
			array(
				'label'     => __('Padding', 'ecolab'),
				'condition'    => array('show_subtitle' => 'show'),
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
			'blog_subtitle_color',
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
			'blog_subtitle_hover_color',
			array(
				'label'     => __('Color Hover', 'ecolab'),
				'condition'    => array('show_subtitle' => 'show'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mr_block_subtitle:hover' => 'color: {{VALUE}} !important',


				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'content_section_date',
			[
				'label' => __('Date Setting', 'rashid'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_date',
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
					'{{WRAPPER}} .mr_post_date' => 'display: {{VALUE}} !important',
				),
			)
		);
		$this->add_control(
			'blog_date_alingment',
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
				'condition'    => array('show_date' => 'show'),
				'toggle' => true,
				'selectors' => array(
					'{{WRAPPER}} .mr_post_date' => 'text-align: {{VALUE}} !important',
				),
			)
		);
		$this->add_control(
			'blog_date_padding',
			array(
				'label'     => __('Padding', 'ecolab'),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em'],
				'condition'    => array('show_date' => 'show'),
				'selectors' => array(
					'{{WRAPPER}} .mr_post_date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'button_border',
				'condition'    => array('show_date' => 'show'),
				'selector' => '{{WRAPPER}} .mr_post_date',
			)
		);
		$this->add_control(
			'blog_button_border_radius',
			array(
				'label' => esc_html__('Border Radius', 'ecolab'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array('show_date' => 'show'),
				'size_units' => ['px', '%'],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .mr_post_date' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'date_typography',
				'condition'    => array('show_date' => 'show'),
				'label'    => __('Typography', 'ecolab'),
				'selector' => '{{WRAPPER}} .mr_post_date',
			)
		);
		$this->add_control(
			'blog_date_color',
			array(
				'label'     => __('Color', 'ecolab'),
				'condition'    => array('show_date' => 'show'),
				'separator' => 'after',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mr_post_date' => 'color: {{VALUE}} !important',
				),
			)
		);
		$this->add_control(
			'blog_date_hover_color',
			array(
				'label'     => __('Hover Color', 'ecolab'),
				'condition'    => array('show_date' => 'show'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mr_post_date:hover ' => 'color: {{VALUE}} !important',
				),
			)
		);
		$this->add_control(
			'blog_date_background_color',
			array(
				'label'     => __('Background Color', 'ecolab'),
				'condition'    => array('show_date' => 'show'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mr_post_date ' => 'background: {{VALUE}} !important',
				),
			)
		);
		$this->add_control(
			'blog_horizontal_position',
			[
				'label' => esc_html__('Horizontal Position Previous',  'wpsection'),
				'condition'    => array('show_date' => 'show'),
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
				'selectors' => [
					'{{WRAPPER}} .mr_post_date' => 'left: {{SIZE}}{{UNIT}};',
				]

			]
		);


		$this->add_control(
			'blog_vertical_position',
			[
				'label' => esc_html__('Vertical Position', 'wpsection'),
				'condition'    => array('show_date' => 'show'),
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
				'selectors' => [
					'{{WRAPPER}} .mr_post_date' => 'top: {{SIZE}}{{UNIT}};',

				]
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'content_section_three',
			[
				'label' => __('Text Setting', 'rashid'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_text',
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
					'{{WRAPPER}} .mr_f_block_text' => 'display: {{VALUE}} !important',
				),
			)
		);
		$this->add_control(
			'blog_text_alingment',
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
				'condition'    => array('show_text' => 'show'),
				'toggle' => true,
				'selectors' => array(
					'{{WRAPPER}} .mr_f_block_text' => 'text-align: {{VALUE}} !important',
				),
			)
		);
		$this->add_control(
			'blog_text_padding',
			array(
				'label'     => __('Padding', 'ecolab'),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em'],
				'condition'    => array('show_text' => 'show'),
				'selectors' => array(
					'{{WRAPPER}} .mr_f_block_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'condition'    => array('show_text' => 'show'),
				'label'    => __('Typography', 'ecolab'),
				'selector' => '{{WRAPPER}} .mr_f_block_text',
			)
		);
		$this->add_control(
			'blog_text_color',
			array(
				'label'     => __('Color', 'ecolab'),
				'condition'    => array('show_text' => 'show'),
				'separator' => 'after',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mr_f_block_text' => 'color: {{VALUE}} !important',
				),
			)
		);
		$this->add_control(
			'blog_text_hover_color',
			array(
				'label'     => __('Hover Color', 'ecolab'),
				'condition'    => array('show_text' => 'show'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mr_featured_block:hover ' => 'color: {{VALUE}} !important',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'author-setting',
			[
				'label' => __('Author Setting', 'rashid'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'show_author',
			array(
				'label' => esc_html__('Show Author', 'ecolabe'),
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
					'{{WRAPPER}} .mr_block_author' => 'display: {{VALUE}} !important',
				),
			)
		);
		$this->add_control(
			'blog_image_padding',
			array(
				'label'     => __('Image Padding', 'ecolab'),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array('show_author' => 'show'),
				'size_units' =>  ['px', '%', 'em'],

				'selectors' => array(
					'{{WRAPPER}} .author-thumb img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

		$this->add_control(
			'blog_image_margin',
			array(
				'label'     => __('Image Margin', 'ecolab'),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array('show_author' => 'show'),
				'size_units' =>  ['px', '%', 'em'],
				'selectors' => array(
					'{{WRAPPER}}  .author-thumb img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'image_border',
				'condition'    => array('show_author' => 'show'),
				'selector' => '{{WRAPPER}}  .author-thumb img',
			)
		);
		$this->add_control(
			'blog_image_border_radius',
			array(
				'label' => esc_html__('Image Border Radius', 'ecolab'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array('show_author' => 'show'),
				'size_units' => ['px', '%'],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .author-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			)
		);


		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => esc_html__('Image Box Shadow', 'ecolab'),
				'condition'    => array('show_author' => 'show'),
				'selector' => '{{WRAPPER}} .author-thumb img',
			]
		);



		$this->add_control(
			'blog_author_padding',
			array(
				'label'     => __('Padding', 'ecolab'),
				'condition'    => array('show_author' => 'show'),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em'],

				'selectors' => array(
					'{{WRAPPER}} .mr_block_author' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'author_typography',
				'condition'    => array('show_author' => 'show'),
				'label'    => __('Typography', 'ecolab'),
				'selector' => '{{WRAPPER}} .mr_block_author a',
			)
		);
		$this->add_control(
			'blog_author_color',
			array(
				'label'     => __('Color', 'ecolab'),
				'condition'    => array('show_author' => 'show'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mr_block_author a' => 'color: {{VALUE}} !important',

				),
			)
		);
		$this->add_control(
			'blog_author_hover_color',
			array(
				'label'     => __('Color Hover', 'ecolab'),
				'condition'    => array('show_author' => 'show'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mr_block_author a:hover' => 'color: {{VALUE}} !important',

				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'blog_comment_icon',
			[
				'label' => __('Comment Setting', 'rashid'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'show_icon',
			array(
				'label' => esc_html__('Show Icon Area', 'ecolab'),
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
					'{{WRAPPER}} .mr_block_comment' => 'display: {{VALUE}} !important',
				),
			)
		);
		$this->add_control(
			'blog_icon_color',
			array(
				'label'     => __(' Icon Color', 'ecolab'),
				'condition'    => array('show_icon' => 'show'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mr_block_comment i' => 'color: {{VALUE}} !important',

				),
			)
		);
		$this->add_control(
			'blog_icon_hover_color',
			array(
				'label'     => __(' Icon Hover Color', 'ecolab'),
				'condition'    => array('show_icon' => 'show'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mr_featured_block:hover i' => 'color: {{VALUE}} !important',

				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'icon_typography',
				'condition'    => array('show_icon' => 'show'),
				'label'    => __('Typography', 'ecolab'),
				'selector' => '{{WRAPPER}}  .mr_block_comment i',
			)
		);

		$this->add_control(
			'blog_icon_bg_padding',
			array(
				'label'     => __('Background Padding', 'ecolab'),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array('show_icon' => 'show'),
				'size_units' =>  ['px', '%', 'em'],

				'selectors' => array(
					'{{WRAPPER}} .mr_block_comment i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

		$this->add_control(
			'blog_icon_bg_margin',
			array(
				'label'     => __('Background Margin', 'ecolab'),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array('show_icon' => 'show'),
				'size_units' =>  ['px', '%', 'em'],
				'selectors' => array(
					'{{WRAPPER}}  .mr_block_comment i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);
		$this->add_control(
			'blog_icon_bg_color',
			array(
				'label'     => __('Background Color', 'ecolab'),
				'condition'    => array('show_icon' => 'show'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .mr_block_comment i' => 'background: {{VALUE}} !important',

				),
			)
		);


		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'icon_bg_border',
				'condition'    => array('show_icon' => 'show'),
				'selector' => '{{WRAPPER}}  .mr_block_comment i',
			)
		);
		$this->add_control(
			'blog_icon_border_radius',
			array(
				'label' => esc_html__('Icon Border Radius', 'ecolab'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array('show_icon' => 'show'),
				'size_units' => ['px', '%'],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .mr_block_comment i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_shadow',
				'label' => esc_html__('Icon Bg Box Shadow', 'ecolab'),
				'condition'    => array('show_icon' => 'show'),
				'selector' => '{{WRAPPER}} .mr_block_comment i',
			]
		);

		$this->add_control(
			'blog_comment_title_padding',
			array(
				'label'     => __('Padding', 'ecolab'),
				'condition'    => array('show_title' => 'show'),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em'],

				'selectors' => array(
					'{{WRAPPER}} .mr_block_comment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'      => 'comment_title_typography',
				'condition' => array('show_title' => 'show'),
				'label'     => __('Typography', 'ecolab'),
				'selector'  => '{{WRAPPER}} .mr_block_comment',
			)
		);
		$this->add_control(
			'blog_comment_title_color',
			array(
				'label'     => __('Comment Color', 'wpsection'),
				'condition'    => array('show_title' => 'show'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mr_block_comment' => 'color: {{VALUE}} !important',

				),
			)
		);
		$this->add_control(
			'blog_comment_title_hover_color',
			array(
				'label'     => __('Comment Color Hover', 'ecolab'),
				'condition'    => array('show_title' => 'show'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mr_block_comment:hover' => 'color: {{VALUE}} !important',

				),
			)
		);
		$this->end_controls_section();
		
		
//REad More		

	$this->start_controls_section(
			'readmore_setting',
			[
				'label' => __('Read More Setting', 'wpsection'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'show_readmore',
			array(
				'label' => esc_html__('Show Readmore', 'wpsection'),
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
					'{{WRAPPER}} .wps_read_button_area' => 'display: {{VALUE}} !important',
				),
			)
		);

	$this->add_control(
			'readmore_alingment',
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
				'condition'    => array('show_text' => 'show'),
				'toggle' => true,
				'selectors' => array(
					'{{WRAPPER}} .wps_read_button_area' => 'text-align: {{VALUE}} !important',
				),
			)
		);	
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'readmore_typography',
				'condition'    => array('show_author' => 'show'),
				'label'    => __('Typography', 'ecolab'),
				'selector' => '{{WRAPPER}} .wps_read_button a',
			)
		);
		$this->add_control(
			'readmore_color',
			array(
				'label'     => __('Color', 'wpsection'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wps_read_button a' => 'color: {{VALUE}} !important',

				),
			)
		);
		$this->add_control(
			'readmore_hover_color',
			array(
				'label'     => __('Color Hover', 'wpsection'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wps_read_button a:hover' => 'color: {{VALUE}} !important',

				),
			)
		);
		
		
		$this->add_control(
			'readmore_bg_color',
			array(
				'label'     => __('Backgorund Color', 'wpsection'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wps_read_button a' => 'background: {{VALUE}} !important',

				),
			)
		);
		$this->add_control(
			'readmore_bg_hover_color',
			array(
				'label'     => __('Backgorund Hover', 'wpsection'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wps_read_button a:hover' => 'background: {{VALUE}} !important',

				),
			)
		);
		
		
	$this->add_control(
			'readmore_margin',
			array(
				'label'     => __(' Margin', 'wpsection'),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em'],
				'selectors' => array(
					'{{WRAPPER}}  .wps_read_button a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);



	

		$this->add_control(
			'readmore_padding',
			array(
				'label'     => __('Padding', 'wpsection'),
				'condition'    => array('show_author' => 'show'),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em'],

				'selectors' => array(
					'{{WRAPPER}} .wps_read_button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);
			
		
		
		
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'readmore_border',
				'selector' => '{{WRAPPER}}  .wps_read_button a',
			)
		);
		$this->add_control(
			'readmore_border_radius',
			array(
				'label' => esc_html__('readmore Border Radius', 'wpsection'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wps_read_button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			)
		);		
		
		
			$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'readmore_shadow',
				'label' => esc_html__('Box Shadow', 'wpsection'),
				'selector' => '{{WRAPPER}} .wps_read_button a',
			]
		);


		
		$this->end_controls_section();		
	
		
//Project Block 		
	   $this->start_controls_section(
                'wps_blog_block_settings',
                array(
                    'label' => __( 'Block Setting', 'wpsection' ),
                    'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                )
            );

        
    $this->add_control(
            'wps_blog_show_block',
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
                    '{{WRAPPER}} .wp_blog_block' => 'display: {{VALUE}} !important',
                ),
            )
        );  


        

$this->add_control(
            'wps_blog_box_height',
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
                    '{{WRAPPER}} .wp_blog_block' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'wps_blog_block_color',
            array(
                'label'     => __( 'Background Color', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wp_blog_block' => 'background: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'wps_blog_block_hover_color',
            array(
                'label'     => __( 'Hover Color', 'wpsection' ),
               //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wp_blog_block:hover' => 'background: {{VALUE}} !important',
                ),
            )
        );
    
        $this->add_control(
            'wps_blog_block_margin',
            array(
                'label'     => __( 'Block Margin', 'wpsection' ),
                    //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .wp_blog_block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'wps_blog_block_padding',
            array(
                'label'     => __( 'Block Padding', 'wpsection' ),
                    //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .wp_blog_block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

            $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_blog_block_shadow',
                    //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wp_blog_block',
            ]
        );
      $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_blog_block_X_hover_shadow',
                   // 'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Hover Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wp_blog_block:hover',
            ]
        );

 
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'wps_blog_block_border',
                //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Border', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wp_blog_block',
            ]
        );
                
            $this->add_control(
            'wps_blog_block_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wp_blog_block' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

	   $this->end_controls_section();
				
	}
	
	protected function render()
	{

		
$settings = $this->get_settings_for_display();
		

$paged = wpsection_set($_POST, 'paged') ? esc_attr($_POST['paged']) : 1;

$this->add_render_attribute('wrapper', 'class', 'templatepath-wpsection');
					
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => wpsection_set($settings, 'query_number'),
    'orderby'        => wpsection_set($settings, 'query_orderby'),
    'order'          => wpsection_set($settings, 'query_order'),
    'paged'          => $paged
);

if (wpsection_set($settings, 'query_exclude')) {
    $settings['query_exclude'] = explode(',', $settings['query_exclude']);
    $args['post__not_in']      = wpsection_set($settings, 'query_exclude');
}

// Check if 'query_category' is set in the settings
if (wpsection_set($settings, 'query_category')) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => wpsection_set($settings, 'query_category'),
            'operator' => 'IN',
        ),
    );
}

$query = new \WP_Query($args);		
		

		if ($query->have_posts()) { ?>



<section class="blog-grid">
    <div class="auto-container">
        <div class="row clearfix">
            <?php while ($query->have_posts()) : $query->the_post();
                $meta_image = get_post_meta(get_the_id(), 'meta_image', true);
            ?>
                <div class="col-lg-<?php echo esc_attr($settings['column'], true); ?> col-md-<?php echo esc_attr($settings['column_tab'], true); ?>  col-sm-12 news-block">
                    <div class="wp_blog_block news-block-two wow fadeInUp animated" data-wow-delay="00ms" data-wow-duration="1500ms">
                        <div class="inner-box">
                            <div class="image-box">
                                <figure class="image mr_product_thumb">
                                    <a class="mr_product_link" href="<?php echo esc_url(the_permalink(get_the_id())); ?>">
                                        <?php if ('style1' === $settings['thumb']) : ?>
                                            <img src="<?php echo wp_get_attachment_url($meta_image['id']); ?>" alt="" />
                                        <?php endif; ?>
                                        <?php if ('style2' === $settings['thumb']) : ?>
                                            <?php the_post_thumbnail();    ?>
                                        <?php endif; ?>
                                    </a>
                                </figure>
                                <span class="post-date mr_post_date"><?php echo get_the_date('d'); ?> <?php echo get_the_date('M'); ?></span>
                            </div>
                            <div class="lower-content">
                                <div class="subtitle_alignment">
                                    <span class="category mr_block_subtitle mr_featured_block_subtitle">
                                        <?php
                                        $categories = get_the_category();
                                        if (!empty($categories)) {
                                            $category_link = esc_url(get_category_link($categories[0]->term_id));
                                            echo '<a href="' . $category_link . '">' . esc_html($categories[0]->name) . '</a>';
                                        }
                                        ?>
                                    </span>
                                </div>

                                <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url(the_permalink(get_the_id())); ?>"><?php the_title(); ?></a></h3>
                                <ul class="post-info clearfix">
                                    <li class="author-box mr_block_author">
                                        <div class="author-thumb"><?php echo get_avatar(get_the_author_meta('ID'), 90); ?></div>
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php the_author(); ?></a>
                                    </li>
                                    <li class="mr_featured_block mr_block_comment"><i class="<?php echo esc_attr($settings['comment_icons']['value']); ?>"></i><?php comments_number(); ?></li>
                                </ul>

                                <p class="mr_featured_block mr_f_block_text"><?php echo wpsection_trim(get_the_content(), $settings['text_limit']); ?></p>
                                <div class="wps_read_button_area">
                                    <button class="wps_read_button">
                                        <a class="theme_btn_none" href="<?php echo esc_url(the_permalink(get_the_id())); ?>"><?php echo wp_kses($settings['read_more_bttn'], $allowed_tags); ?></a>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile;
            wp_reset_postdata();?>
        </div>
    </div>
</section>


			


<?php }
		wp_reset_postdata();
	}
}

// Register widget
Plugin::instance()->widgets_manager->register(new \wpsection_wps_blog_grid_Widget());
