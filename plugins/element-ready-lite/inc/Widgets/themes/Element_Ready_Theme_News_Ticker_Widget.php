<?php

namespace Element_Ready\Widgets\themes;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Repeater;
use Element_Ready\Base\Repository\Base_Modal;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Element_Ready_Theme_News_Ticker_Widget extends Widget_Base {

	public function get_name() {
		return 'Element_Ready_Theme_News_Ticker_Widget';
	}

	public function get_title() {
		return esc_html__( 'ER News Ticker', 'element-ready-lite' );
	}

	public function get_icon() {
		return ' eicon-tags';
	}

	public function get_categories() {
		return array('element-ready-addons');
	}

    public function get_keywords() {
        return [ 'slider', 'news ticker', 'owl', 'topbar news' ];
    }

	public function get_script_depends() {

		return[
			'owl-carousel',
			'element-ready-core',
		];

	}

	public function get_style_depends() {

        wp_register_style( 'eready-post-blog' , ELEMENT_READY_ROOT_CSS. 'widgets/blog.css' );
		
		return[
			'owl-carousel','eready-post-blog'
		];
	}

	protected function register_controls() {

		$this->start_controls_section(
            'section_slider_tab',
                [
                    'label' => esc_html__('Slider Settings', 'element-ready-lite'),
                ]
            );

			
			$this->add_control(
				'autoplayHoverPause',
				[
					'label'       => esc_html__('autoplayHoverPause', 'element-ready-lite'),
					'type'        => Controls_Manager::SWITCHER,
					'label_on'    => esc_html__('Yes', 'element-ready-lite'),
					'label_off'   => esc_html__('No', 'element-ready-lite'),
					'default'     => 'yes',
					'description' => esc_html__('Use for slider autoplayHoverPause', 'element-ready-lite'),
				]
			);

			$this->add_control(
				'autoplay',
				[
					'label'       => esc_html__('autoplay', 'element-ready-lite'),
					'type'        => Controls_Manager::SWITCHER,
					'label_on'    => esc_html__('Yes', 'element-ready-lite'),
					'label_off'   => esc_html__('No', 'element-ready-lite'),
					'default'     => 'yes',
					
				]
			);

			$this->add_control(
				'nav',
				[
					'label'       => esc_html__('nav', 'element-ready-lite'),
					'type'        => Controls_Manager::SWITCHER,
					'label_on'    => esc_html__('Yes', 'element-ready-lite'),
					'label_off'   => esc_html__('No', 'element-ready-lite'),
					'default'     => 'yes',
					
				]
			);

			$this->add_control(
				'loop',
				[
					'label'       => esc_html__('loop', 'element-ready-lite'),
					'type'        => Controls_Manager::SWITCHER,
					'label_on'    => esc_html__('Yes', 'element-ready-lite'),
					'label_off'   => esc_html__('No', 'element-ready-lite'),
					'default'     => 'yes',
					
				]
			);

			$this->add_control(
                'autoplayTimeout',
                    [
                        'label'         => esc_html__( 'autoplayTimeout', 'element-ready-lite' ),
                        'type'          => Controls_Manager::NUMBER,
                        'default'       => '3000',
                    ]
                );

			$this->add_control(
                'smartSpeed',
                    [
                        'label'         => esc_html__( 'smartSpeed', 'element-ready-lite' ),
                        'type'          => Controls_Manager::NUMBER,
                        'default'       => '2000',
                    ]
             );

			$this->add_control(
                'margin',
                    [
                        'label'         => esc_html__( 'margin', 'element-ready-lite' ),
                        'type'          => Controls_Manager::NUMBER,
                        'default'       => '20',
                    ]
             );
		$this->end_controls_section();
		$this->start_controls_section(
            'section_general_tab',
                [
                    'label' => esc_html__('Posts General', 'element-ready-lite'),
                ]
            );

				$this->add_control(
                'title',
                    [
                        'label'         => esc_html__( 'Heading', 'element-ready-lite' ),
                        'type'          => Controls_Manager::TEXT,
                        'default'       => 'Trending',
                    ]
                );
				$this->add_control(
                'post_count',
                    [
                        'label'         => esc_html__( 'Post count', 'element-ready-lite' ),
                        'type'          => Controls_Manager::NUMBER,
                        'default'       => '8',
                    ]
                );

                $this->add_control(
                'post_title_crop',
                    [
                        'label'         => esc_html__( 'Post title crop', 'element-ready-lite' ),
                        'type'          => Controls_Manager::NUMBER,
                        'default'       => '8',
                    ]
                );

				$this->add_control(
					'sticky_post',
					[
						'label'       => esc_html__('Show Feature post', 'element-ready-lite'),
						'type'        => Controls_Manager::SWITCHER,
						'label_on'    => esc_html__('Yes', 'element-ready-lite'),
						'label_off'   => esc_html__('No', 'element-ready-lite'),
						'default'     => 'no',
						'description' => esc_html__('Use Sticky option to feature posts', 'element-ready-lite'),
					]
				);

				$this->add_control(
					'post_sortby',
					[
						'label'     =>esc_html__( 'Post sort by', 'element-ready-lite' ),
						'type'      => Controls_Manager::SELECT,
						'default'   => 'latestpost',
						'options'   => [
							'latestpost'    => esc_html__( 'Latest', 'element-ready-lite' ),
							'popularposts'  => esc_html__( 'Popular / most view', 'element-ready-lite' ),
							'mostdiscussed' => esc_html__( 'Most discussed', 'element-ready-lite' ),
							'fb_share'      => esc_html__( 'Most fb share', 'element-ready-lite' ),
							'tranding'      => esc_html__( 'Tranding', 'element-ready-lite' ),
						],
					]
				);
		
				$this->add_control(
					'post_order',
					[
						'label'     =>esc_html__( 'Post order', 'element-ready-lite' ),
						'type'      => Controls_Manager::SELECT,
						'default'   => 'DESC',
						'options'   => [
							'DESC'      =>esc_html__( 'Descending', 'element-ready-lite' ),
							'ASC'       =>esc_html__( 'Ascending', 'element-ready-lite' ),
						],
					]
				);
		   
		$this->end_controls_section();
		do_action( 'element_ready_section_data_exclude_tab', $this , $this->get_name() );  
		do_action( 'element_ready_section_date_filter_tab', $this , $this->get_name());  
		do_action( 'element_ready_section_taxonomy_filter_tab', $this , $this->get_name());  
	   
		$this->start_controls_section(
			'style_nav_section',
			[
				'label' => esc_html__( 'Navigation', 'element-ready-lite' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs(
			'nav_style_tabs'
		);
		
		$this->start_controls_tab(
			'nav_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'element-ready-lite' ),
			]
		);

		$this->add_control(
			'nav_text_color',
			[
				'label' => esc_html__( 'Color', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .owl-nav' => 'color: {{VALUE}}',
					'{{WRAPPER}} .owl-nav i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'nav_width_wrapper_background',
				'label' => esc_html__( 'Background', 'element-ready-lite' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .elements-ready--top-newsticker--area .owl-nav div',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'navs_width_wrapper_border',
				'label' => esc_html__( 'Border', 'element-ready-lite' ),
				'selector' => '{{WRAPPER}} .elements-ready--top-newsticker--area .owl-nav div',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'nav_content_typography',
				'selector' => '{{WRAPPER}} .owl-nav',
			]
		);

		$this->add_control(
			'nav_wrapper_margin',
			[
				'label' => esc_html__( 'Margin', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .owl-nav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_position_left',
			[
				'label' => esc_html__( 'Position Right', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				
				'selectors' => [
					'{{WRAPPER}} .elements-ready--top-newsticker--area .owl-nav' => 'right: {{SIZE}}{{UNIT}};',
				],
		
			]
		);

		$this->add_responsive_control(
			'nav_position_top',
			[
				'label' => esc_html__( 'Position Top', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				
				'selectors' => [
					'{{WRAPPER}} .elements-ready--top-newsticker--area .owl-nav' => 'top: {{SIZE}}{{UNIT}};',
				],
		
			]
		);
		
		$this->end_controls_tab();
		$this->start_controls_tab(
			'nav_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'element-ready-lite' ),
			]
		);

		$this->add_control(
			'nav_text_hover_color',
			[
				'label' => esc_html__( 'Color', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elements-ready--top-newsticker--area .owl-nav .owl-next:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .elements-ready--top-newsticker--area .owl-nav .owl-prev:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'nav_width_wrapper_hjover_background',
				'label' => esc_html__( 'Background', 'element-ready-lite' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .elements-ready--top-newsticker--area .owl-nav .owl-next:hover,{{WRAPPER}} .elements-ready--top-newsticker--area .owl-nav .owl-prev:hover',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'navs_width_wrapper_hover_border',
				'label' => esc_html__( 'Border', 'element-ready-lite' ),
				'selector' => '{{WRAPPER}} .elements-ready--top-newsticker--area .owl-nav .owl-next:hover, {{WRAPPER}} .elements-ready--top-newsticker--area .owl-nav .owl-prev:hover',
			]
		);

		$this->end_controls_tab();
		
		$this->end_controls_tabs();

		
		$this->end_controls_section();
		$this->start_controls_section(
			'style_heading_section',
			[
				'label' => esc_html__( 'Heading', 'element-ready-lite' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'_heading_text_color',
				[
					'label' => esc_html__( 'Color', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .er-news-ticker-trand' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'heading_content_typography',
					'selector' => '{{WRAPPER}} .er-news-ticker-trand',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'heading_border_wrapper_border',
					'label' => esc_html__( 'Border', 'element-ready-lite' ),
					'selector' => '{{WRAPPER}} .er-news-ticker-trand',
				]
			);

			$this->add_control(
				'heading_k_wrapper_padding',
				[
					'label' => esc_html__( 'Padding', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .er-news-ticker-trand' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'heading_width_wrapper_background',
					'label' => esc_html__( 'Background', 'element-ready-lite' ),
					'types' => [ 'classic', 'gradient'],
					'selector' => '{{WRAPPER}} .er-news-ticker-trand',
				]
			);

		$this->end_controls_section();
		$this->start_controls_section(
			'style_post_section',
			[
				'label' => esc_html__( 'Post', 'element-ready-lite' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'_post_text_color',
				[
					'label' => esc_html__( 'Color', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elements-ready--top-newsticker--area .elements-ready--top-newsticker--item a ' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'post_content_typography',
					'selector' => '{{WRAPPER}} .elements-ready--top-newsticker--area .elements-ready--top-newsticker--item a',
				]
			);

			$this->add_control(
				'post_k_wrapper_padding',
				[
					'label' => esc_html__( 'Padding', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .elements-ready--top-newsticker--area .elements-ready--top-newsticker--item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		$data            = new Base_Modal($settings);
        $query           = $data->get();
        
        if(!$query){
          return;  
        }
		
		
    ?>
        <div class="elements-ready--top-newsticker--area">
			<?php if($settings['title'] !=''): ?>
				<div class="er-news-ticker-trand"><?php echo esc_html($settings['title']); ?></div>
			<?php endif; ?>
			<div
			 data-autoplayHoverPause="<?php echo esc_attr($settings['autoplayHoverPause']); ?>" 
			 data-loop="<?php echo esc_attr($settings['loop']); ?>" 
			 data-nav="<?php echo esc_attr($settings['nav']); ?>" 
			 data-autoplay="<?php echo esc_attr($settings['autoplay']); ?>" 
			 data-autoplayTimeout="<?php echo esc_attr($settings['autoplayTimeout']); ?>" 
			 data-smartSpeed="<?php echo esc_attr($settings['smartSpeed']); ?>" 
			 data-margin="<?php echo esc_attr($settings['margin']); ?>" 
			 data-is_rtl="<?php echo esc_attr(is_rtl()? 'yes' : 'no'); ?>"
			 class="element-ready-topbar-carousel owl-carousel">
				<?php 

					while($query->have_posts()) : 
					$query->the_post(); 
				
				?>
					<div class="elements-ready--top-newsticker--item">
						<a href="<?php the_permalink(); ?>"> <?php echo wp_kses_post( wp_trim_words( get_the_title(), $settings['post_title_crop'], '' ) ); ?> </a>
					</div>
				<?php
					endwhile;
				wp_reset_postdata(); 
				?>
			</div>
		</div>
	<?php
	}
}