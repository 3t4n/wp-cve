<?php
/**
 * @package Shop Ready
 */
namespace Shop_Ready\base\elementor\style_controls\common;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

trait Widget_Animation {
  
    public function animate($atts){

        $atts_variable = shortcode_atts(
            array(
                'title'           => esc_html__('Animation','shopready-elementor-addon'),
                'slug'            => 'entrance_animation',
                'hover'    => false
                
           
            ), $atts );

        extract($atts_variable);

        $widget = $this->get_name().'_'.shop_ready_heading_camelize($slug);
        $this->start_controls_section(
            $widget.'_style_after_before_section',
			[
				'label' => $title,
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			$slug.'_entrance_animation',
			[
				'label' => __( 'Entrance Animation', 'shopready-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::ANIMATION,
				'prefix_class' => 'animated ',
			]
		);

        if($hover){

            $this->add_control(
                $slug.'_hover_animation',
                [
                    'label' => __( 'Hover Animation', 'shopready-elementor-addon' ),
                    'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
                    'prefix_class' => 'elementor-animation-',
                ]
            );
        }
    
		$this->end_controls_section();
    } 
    
  
  }