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


if (class_exists('woocommerce')) {
class wpsection_wps_product_slide_Widget extends \Elementor\Widget_Base {
  
public function get_name() {
        return 'wpsection_wps_product_slide';
    }

    public function get_title() {
        return __( 'Products Slide', 'wpsection' );
    }

    public function get_icon() {
         return 'eicon-post-slider';
    }

    public function get_keywords() {
        return [ 'wpsection', 'product' ];
    }

    public function get_categories() {
         return [  'wpsection_shop' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'wpsection_wps_product_slide',
            [
                'label' => esc_html__( 'Genaral Settings ', 'wpsection' ),
            ]
        );

        $this->add_control(
            'style',
            [
                'label'       => __( 'Template Layout', 'wpsection' ),
                'type'        => 'elementor-layout-control',
                'default' => 'style-1',
                'options' => [
                    'style-1' => [
                        'label' => esc_html__('Layout 1', 'wpsection' ),
                        'image' => plugin_dir_url( __FILE__ ) . 'images/s1.png',
                    ],
                    'style-2' => [
                        'label' => esc_html__('Layout 2', 'wpsection' ),
                        'image' => plugin_dir_url( __FILE__ ) . 'images/s2.png',
                    ],
                    
                    'style-3' => [
                        'label' => esc_html__('Layout 3', 'wpsection' ),
                        'image' => plugin_dir_url( __FILE__ ) . 'images/s3.png',
                    ],
					
                ],
            ]
        );

require 'extended_array/genareal_settings.php';	

require 'extended_array/common_settings.php'; 	  

    $this->start_controls_section(
                    'slider_settings',
                    [
                        'label' => __( 'Slider Settings', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                );    
    $this->add_control(
                'show_slider',
                 array(
                    'label' => __( 'Show Slider', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'return_value' => '1',
                     'default'      => '1',
                    'placeholder' => __( 'Enable Slider', 'wpsection' ),
                )
            );
		  $this->add_control(
                'slide_auto_loop',
                 array(
                    'label' => __( 'Show Auto Loop', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'return_value' => '1',
                     'default'      => '0',
                    'placeholder' => __( 'Enable Slider', 'wpsection' ),
                )
            );
$this->end_controls_section();   

require 'extended_array/common_style.php'; 
		
require 'extended_array/slider_control.php'; 		    

//=============================End of Area DO NOT Edit BEllow Codes============   
		
    }

protected function render() {
require 'extended_array/render_settings.php'; 		
    $query = new \WP_Query( $args );
    if ( $query->have_posts() ) {   
    $style = $settings['style']; 
    $style_folder = __DIR__ . '/wps_product_slide/';
    $style_file = $style_folder . $style . '.php';
    if (is_readable($style_file)) {
        require $style_file;
    } else {
        echo "Style file '$style.php' not found or could not be read.";
    }
 }
   wp_reset_postdata();
    }
}

Plugin::instance()->widgets_manager->register( new \wpsection_wps_product_slide_Widget() );
    
    
}
    