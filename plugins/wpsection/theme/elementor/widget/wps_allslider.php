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



class wpsection_wps_allslider_Widget extends \Elementor\Widget_Base {



	public function get_name() {
		return 'wpsection_wps_allslider';
	}

	public function get_title() {
		return __( 'All Slider', 'wpsection' );
	}

	public function get_icon() {
		 return 'eicon-post-slider';
	}

	public function get_keywords() {
		return [ 'wpsection', 'wps_allslider' ];
	}

	public function get_categories() {
    return [ 'wpsection_category' ];
	} 

	protected function register_controls() {
		$this->start_controls_section(
			'wps_allslider',
			[
				'label' => esc_html__( 'Allsliders', 'wpsection' ),
			]
		);
		$this->add_control(
			'sec_class',
			[
				'label'       => __( 'Section Class', 'wpsection' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'Enter Section Class', 'wpsection' ),
			]
		);
		
	$this->add_control(
			'notice', [
				'label'       => esc_html__( '*********Important Notice*******', 'wpsection' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'NEVER PUT SLIDE IN SIDE SLIDER',
				'dynamic'     => [
					'active' => true,
				],
			]
		);
		
	$repeater = new Repeater();

		$repeater->add_control(
			'slider_title', [
				'label'       => esc_html__( 'Name', 'element-path' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'Slider Name',
				'dynamic'     => [
					'active' => true,
				],
			]
		);
		
		$repeater->add_control( 
			'slider_type',
			[
				'label' => esc_html__( 'Temlpate Type', 'element-path' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'template',
				'options' => [
					'shortcode'  => esc_html__( 'Shortcode Input', 'element-path' ),
					'template' => esc_html__( 'Select Template', 'element-path' ),
				],
			]
		);

		$repeater->add_control( 
			'wpsection_elemntor_template',
			[
				'label' => esc_html__( 'Template', 'element-path' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => wpsection_elementor_template_(),
				'condition' => [
					'slider_type' => 'template',
				],				
			]
		);	
		
		
		
		$repeater->add_control(
			'slider_shortcode', [
				'label'       => esc_html__( 'Shortcode', 'element-path' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => 'Put Shortcode Here',
				'dynamic'     => [
					'active' => true,
				],
				'condition' => [
					'slider_type' => 'shortcode',
				],	
			]
		);



		
		$this->add_control(
			'repeat',
			[
				'label'       => esc_html__( 'Sliders', 'element-path' ),
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

		
		
			// Arrow Button Setting
	
$this->start_controls_section(
			'slider_path_button_3_control',
			array(
				'label' => __( 'Slider Arrow  Settings', 'wpsection' ),
		
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
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
				'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav .owl-prev' => 'color: {{VALUE}} !important',
					'{{WRAPPER}}  .slider_path .owl-nav .owl-next' => 'color: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_path_button_3_color_hover',
			array(
				'label'     => __( 'Button Hover Color', 'wpsection' ),
				'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav .owl-prev:hover' => 'color: {{VALUE}} !important',
					'{{WRAPPER}}  .slider_path .owl-nav .owl-next:hover' => 'color: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_path_button_3_bg_color',
			array(
				'label'     => __( 'Background Color', 'wpsection' ),
				'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button' => 'background: {{VALUE}} !important',
				),
			)
		);	
$this->add_control(
			'slider_path_button_3_hover_color',
			array(
				'label'     => __( 'Background Hover Color', 'wpsection' ),
				'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button:hover' => 'background: {{VALUE}} !important',
				),
			)
		);	
		
		
	
		$this->add_control( 'slider_path_dot_3_width',
					[
						'label' => esc_html__( 'Arraw Width',  'wpsection' ),
						'condition'    => array( 'slider_path_show_dot' => 'show' ),
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
							'size' => 0,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-nav .owl-prev' => 'width: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .slider_path .owl-nav .owl-next' => 'width: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
		

	$this->add_control( 'slider_path_dot_3_height',
					[
						'label' => esc_html__( 'Arraw Height', 'wpsection' ),
						'condition'    => array( 'slider_path_show_dot' => 'show' ),
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
							'size' => 250,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-nav .owl-prev' => 'height: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .slider_path .owl-nav .owl-next ' => 'height: {{SIZE}}{{UNIT}};',
					
						]
					]
				);		
			
	
		
	$this->add_control(
			'slider_path_button_3_padding',
			array(
				'label'     => __( 'Padding', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button_3' => 'show' ),
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
				'condition'    => array( 'slider_path_show_button_3' => 'show' ),
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
				'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'label'    => __( 'Typography', 'wpsection' ),
				'selector' => '{{WRAPPER}}  .slider_path .owl-nav button',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'slider_path_border_3',
				'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'selector' => '{{WRAPPER}}  .slider_path .owl-nav .owl-prev, .slider_path .owl-nav .owl-next ',
			)
		);
	

		$this->add_control(
			'slider_path_border_3_radius',
			array(
				'label'     => __( 'Border Radius', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


				
		
				$this->add_control( 'slider_path_horizontal_prev',
					[
						'label' => esc_html__( 'Horizontal Position Previous',  'wpsection' ),
						'condition'    => array( 'slider_path_show_button_3' => 'show' ),
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
						'condition'    => array( 'slider_path_show_button_3' => 'show' ),
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
						'condition'    => array( 'slider_path_show_button_3' => 'show' ),
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
							'size' => 250,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-nav ' => 'top: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .slider_path .owl-nav ' => 'top: {{SIZE}}{{UNIT}};',
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
						'condition'    => array( 'slider_path_show_dot' => 'show' ),
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
							'size' => 0,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-theme .owl-dots span' => 'width: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
			
				$this->add_control( 'slider_path_dot_height',
					[
						'label' => esc_html__( 'Dot Height', 'wpsection' ),
						'condition'    => array( 'slider_path_show_dot' => 'show' ),
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
							'size' => 250,
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
				'condition'    => array( 'slider_path_show_dot' => 'show' ),
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
				'condition'    => array( 'slider_path_show_dot' => 'show' ),
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
				'condition'    => array( 'slider_path_show_dot' => 'show' ),
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
				'condition'    => array( 'slider_path_show_dot' => 'show' ),
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
				'condition'    => array( 'slider_path_show_dot' => 'show' ),
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
				'condition'    => array( 'slider_path_show_dot' => 'show' ),
				'selector' => '{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span',
			)
		);
	

		$this->add_control(
			'slider_path_dot_radius',
			array(
				'label'     => __( 'Border Radius', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_dot' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


				
		
				$this->add_control( 'slider_path_dot_horizontal',
					[
						'label' => esc_html__( 'Horizontal Position Previous',  'wpsection' ),
						'condition'    => array( 'slider_path_show_dot' => 'show' ),
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
							'size' => 0,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-theme .owl-dots' => 'left: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
			

				$this->add_control( 'slider_path_dot_vertical',
					[
						'label' => esc_html__( 'Vertical Position', 'wpsection' ),
						'condition'    => array( 'slider_path_show_dot' => 'show' ),
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
							'size' => 250,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-theme .owl-dots  ' => 'top: {{SIZE}}{{UNIT}};',
					
						]
					]
				);


		$this->end_controls_section();	
		
		
			
		
		}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$allowed_tags = wp_kses_allowed_html('post');
		?>


<?php

	
      echo '
     <script>
 jQuery(document).ready(function($)
 {

//put the js code under this line 

if ($(".sliderpath_banner-carousel-one").length) {
    $(".sliderpath_banner-carousel-one").owlCarousel({
        loop:true,
        margin:0,
        nav:true,
        active: true,
        smartSpeed: 1000,
        autoplay: 6000,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            800:{
                items:1
            },
            1024:{
                items:1
            }
        }
    });
}


//put the code above the line 

  });
</script>';
		

      echo '
 <style>
 
.slider_path .owl-theme .owl-dots {
    position: absolute;
    bottom: 10%;
    left: 47%;
}


.slider_path .owl-theme .owl-dots .owl-dot span {
    background: #18f45e;
    display: block;
    margin: 0px 5px 0px 5px;
    transition: opacity 200ms ease 0s;
    width: 15px;
    height: 15px;
}
.slider_path .owl-theme .owl-dots .owl-dot.active span {
    background: none repeat scroll 0 0 #ff126e;
}


.slider_path  .owl-nav{
    position: absolute;

}

.slider_path .owl-nav .owl-next{
    left: 90%;
	position: absolute;
}

.slider_path .owl-nav .owl-prev{
    left: 10%;
	position: absolute;
}

.slider_path .owl-nav .owl-prev,
.slider_path .owl-nav .owl-next {
    bottom: 50%;
    position: absolute;
    color: #222;
    width: 40px;
    height: 40px;
    background: #f4187c;
    border-radius: 50%;
   font-size:15px;
}

</style>';		
		

?>

<!-- This is the Main Area Astart=================== --> 
<div class="defult_six slider_path <?php echo esc_attr($settings['sec_class']);?>">        

<!-- Slider Mask=================== -->
<div class="defult_slider_1">

	
	
  
				<!-- Slider for Plugin plugin_slides =================== --> 	
				<div class="sliderpath_banner-carousel-one owl-theme owl-carousel owl_dots_one " >
					
					<?php foreach($settings['repeat'] as $item):?>

						<!-- Slider Elembnetor Template =================== --> 	
									<div class="slider_path_elemntor">
										<?php  if ( 'template' === $item['slider_type'] ) : ?>
										<?php $post_id = wpsection_elemntor_content($item['wpsection_elemntor_template']);
											echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($post_id); ?>
										<?php endif ; ?>
										 <?php  if ( 'shortcode' === $item['slider_type'] ) : ?>
										<?php echo do_shortcode($item['slider_shortcode'],$allowed_tags); ?>
										<?php endif ; ?>
										
									</div>	
					   <!-- Slider For Each Area =================== --> 
   					<?php endforeach?>
				<!-- End  Plugin plugin_slides =================== --> 		
         		</div>    
	 	
	
<!-- End Slider Mask=================== -->
</div>   
	
<!-- End of Main Area =================== -->	
</div>



             
		<?php 
	}

}





// Register widget
Plugin::instance()->widgets_manager->register( new \wpsection_wps_allslider_Widget() );


