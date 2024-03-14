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
class wpsection_wps_product_grid_Widget extends \Elementor\Widget_Base {
  
public function get_name() {
		return 'wpsection_wps_product_grid';
	}

	public function get_title() {
		return __( 'Products Grid', 'wpsection' );
	}

	public function get_icon() {
		 return 'eicon-product-related';
	}

	public function get_keywords() {
		return [ 'wpsection', 'product' ];
	}

    public function get_categories() {
        return [  'wpsection_shop' ];
    }

    protected function _register_controls() {
    // bosch  111  
        $this->start_controls_section(
            'wpsection_wps_product_grid',
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

				
				],
			]
		);

		   

require 'extended_array/genareal_settings.php';	
  

$this->start_controls_section(
                'product_tab_c_settings',
                    [
                        'label' => __( 'Grid Settings', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                );		
		
		$this->add_control(
            'grid_width_x',
            array(
                'label' => __( 'Thumbnail Grid Width', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '7',
                'options' => [
                    '1'  => __('1 Column', 'wpsection' ),
                    '2' => __( '2 Columns', 'wpsection' ),
                    '3' => __( '3 Columns', 'wpsection' ),
                    '4' => __( '4 Columns', 'wpsection' ),
                    '5' => __( '5 Columns', 'wpsection' ),
                    '6' => __( '6 Columns', 'wpsection' ),
                    '7' => __( '7 Columns', 'wpsection' ),
                    '8' => __( '8 Columns', 'wpsection' ),
                    '9' => __( '9 Columns', 'wpsection' ),
                    '10' => __( '10 Columns', 'wpsection' ),
                    '12' => __( '12 Columns', 'wpsection' ),
                ],
            )
        );
		
	
		$this->add_control(
            'grid_order',
            array(
                'label' => __( 'Thumbnail Order', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '2',
                'options' => [
                    '1'  => __('Right Side', 'wpsection' ),
                    '2' => __( 'Left Side', 'wpsection' ),
       
                ],
            )
        );	
		
	$this->add_control(
            'grid_countwo_postiont',
            array(
                'label' => __( 'CountDwoun Position', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1'  => __('Under Thumbnail', 'wpsection' ),
                    '2' => __( 'Under Text', 'wpsection' ),
                    '3' => __( 'Inside Extende Area', 'wpsection' ),
             
                ],
            )
        );
	
		

	$this->end_controls_section();  
		
		

require 'extended_array/common_settings.php'; 


     
	
		
require 'extended_array/common_style.php';	
        
    
    }
    
//===================End of Query Area ========================


protected function render() {
require 'extended_array/render_settings.php'; 
       
    $query = new \WP_Query( $args );

    if ( $query->have_posts() ) { 
    $style = $settings['style']; 
    $style_folder = __DIR__ . '/wps_product_grid/';
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
Plugin::instance()->widgets_manager->register( new \wpsection_wps_product_grid_Widget() );
	
	
}
	