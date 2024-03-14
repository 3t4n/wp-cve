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
class wpsection_wps_category_Widget extends \Elementor\Widget_Base {
  
public function get_name() {
        return 'wpsection_wps_category';
    }

    public function get_title() {
        return __( 'Product Category', 'wpsection' );
    }

    public function get_icon() {
         return 'eicon-product-categories';
    }

    public function get_keywords() {
        return [ 'wpsection', 'product' ];
    }

    public function get_categories() {
        return [  'wpsection_shop' ];
    }



    protected function _register_controls() {
        $this->start_controls_section(
            'wpsection_wps_category',
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






 $this->add_control(
            'query_category',
            array(
                'label' => __( 'Select Category', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => mr_all_cat_list(), // Connect the function to the 'options' parameter
                'default' => ' ', // Set the default value to an empty string
                'description' => esc_html__( 'All Categories are Selected. Click Cross to Select Again', 'wpsection' ),
            )
        );



         $this->add_control(
                'show_product_cat_features',
               array(
                    'label' => __( 'Show Catagory', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'return_value' => '1',
                     'default'      => '0',
                    'placeholder' => __( 'Show Catagory', 'wpsection' ),
                )
            );
$this->end_controls_section();



//Catgory text Settings 
   $this->start_controls_section(
            'product_cat_x_settings',
            array(
                'label' => __( 'Catagory Title Setting', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
        
        
    $this->add_control(
            'show_cat_x_title',
            array(
                'label' => esc_html__( 'Show Title', 'wpsection' ),
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
                    '{{WRAPPER}} .wps_cat_title' => 'display: {{VALUE}} !important',
                ),
            )
        );  
    $this->add_control(
            'title_catx_alingment',
            array(
                'label' => esc_html__( 'Alignment', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'wpsection' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpsection' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'wpsection' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'flex-start',
                'condition'    => array( 'show_cat_x_title' => 'show' ),
                'toggle' => true,
                'selectors' => array(
                
                    '{{WRAPPER}} .wps_cat' => 'justify-content: {{VALUE}} !important',
                ),
            )
        );          


    $this->add_control(
            'catx_title_padding',
            array(
                'label'     => __( 'Padding', 'wpsection' ),
                'condition'    => array( 'show_cat_x_title' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .wps_cat_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

       $this->add_control(
            'catx_title_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                'condition'    => array( 'show_cat_x_title' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .wps_cat_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'carx_itle_typography',
                'condition'    => array( 'show_cat_x_title' => 'show' ),
                'label'    => __( 'Typography', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_cat_title',
            )
        );

        $this->add_control(
            'catx_title_color',
            array(
                'label'     => __( 'Color', 'wpsection' ),
                'condition'    => array( 'show_cat_x_title' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wps_cat_title' => 'color: {{VALUE}} !important',
        
                ),
            )
        );

        $this->end_controls_section();
    

//Catagory Text Settings End
//Catgry Number

  $this->start_controls_section(
            'product_cat_n_settings',
            array(
                'label' => __( 'Catagory Number  Setting', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
        
        
    $this->add_control(
            'show_cat_n_title',
            array(
                'label' => esc_html__( 'Show Title', 'wpsection' ),
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
                    '{{WRAPPER}} .wps_cat_number' => 'display: {{VALUE}} !important',
                ),
            )
        );  

      $this->add_control(
        'cat_postion_style',
        [
            'label'   => esc_html__( 'Select Style', 'wpsection' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'style-1',
            'options' => array(
                'style-1'   => esc_html__( 'Next to Text', 'wpsection' ),
                'style-2'   => esc_html__( 'Next Line ', 'wpsection' ),
            
            ),
        ]
    );

    $this->add_control(
            'title_catn_alingment',
            array(
                'label' => esc_html__( 'Alignment', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'wpsection' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpsection' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'wpsection' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'condition'    => array( 'cat_postion_style' => 'style-2' ),
                'toggle' => true,
                'selectors' => array(
                
                    '{{WRAPPER}} .wps_cat_number' => 'text-align: {{VALUE}} !important',
                ),
            )
        );          


    $this->add_control(
            'catn_title_padding',
            array(
                'label'     => __( 'Padding', 'wpsection' ),
                'condition'    => array( 'show_cat_n_title' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} ..wps_cat_number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

       $this->add_control(
            'catn_title_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                'condition'    => array( 'show_cat_n_title' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .wps_cat_number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'catn_itle_typography',
                'condition'    => array( 'show_cat_n_title' => 'show' ),
                'label'    => __( 'Typography', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_cat_number',
            )
        );

        $this->add_control(
            'catn_title_color',
            array(
                'label'     => __( 'Color', 'wpsection' ),
                'condition'    => array( 'show_cat_n_title' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wps_cat_number' => 'color: {{VALUE}} !important',
        
                ),
            )
        );

        $this->end_controls_section();
    
//Catagory Number End 

//Catagr thumbnail Settings

$this->start_controls_section(
            'thumbnail_catx_control',
            array(
                'label' => __( 'Catagory Thumbanil Settings', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
        
$this->add_control(
            'show_catx_thumbnail',
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
                    '{{WRAPPER}} .wps_cat_img' => 'display: {{VALUE}} !important',
                ),
            )
        );      
    $this->add_control( 'thumb_cat_width',
                    [
                        'label' => esc_html__( 'Width',  'wpsection' ),
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 300,
                                'step' => 1,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        
                        'selectors' => [
                            '{{WRAPPER}} .wps_cat_img img' => 'width: {{SIZE}}{{UNIT}};',
                        ]
                    
                    ]
                );
        
      $this->add_control(
        'thumb_cat_postion_style',
        [
            'label'   => esc_html__( 'Select Style', 'wpsection' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'style-1',
            'options' => array(
                'style-1'   => esc_html__( 'Next to Text', 'wpsection' ),
                'style-2'   => esc_html__( 'Next Line ', 'wpsection' ),
            
            ),
        ]
    );


    $this->add_control(
            'thumb_cat_alingment',
            array(
                'label' => esc_html__( 'Alignment', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'wpsection' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpsection' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'wpsection' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'condition'    => array( 'thumb_cat_postion_style' => 'style-2' ),
                'toggle' => true,
                'selectors' => array(
                
                    '{{WRAPPER}} .wps_cat_thumb ' => 'text-align: {{VALUE}} !important',
                ),
            )
        );          

    $this->add_control(
            'thumbnail_catx_padding',
            array(
                'label'     => __( 'Padding', 'wpsection' ),
            
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
        
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .wps_cat_img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

    $this->add_control(
            'thumbnail_catx_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                    'condition'    => array( 'show_thumbnail' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
            
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wps_cat_img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'thumbnail_catx_border',
                    'condition'    => array( 'show_thumbnail' => 'show' ),
                'selector' => '{{WRAPPER}} .wps_cat_img',
            )
        );
                
            $this->add_control(
            'thumbnail_catx_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wps_cat_img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );  
        $this->end_controls_section();
		// End of Quantity Settings               
//Product Bottom ARea

    $this->start_controls_section(
                'block_bottom_settings',
                array(
                    'label' => __( 'Product Bottom Setting', 'wpsection' ),
                    'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                )
            );

        
    $this->add_control(
            'show_block_bottom',
            array(
                'label' => esc_html__( 'Show Bottom', 'wpsection' ),
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
                    '{{WRAPPER}} .wps_product_details.product_bottom' => 'display: {{VALUE}} !important',
                ),
            )
        );  


        

$this->add_control(
            'box_bottom_height',
            [
                'label' => esc_html__( 'Min Height', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'condition'    => array( 'show_block_bottom' => 'show' ),
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
                    '{{WRAPPER}} .wps_product_details.product_bottom' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'block_bottom_color',
            array(
                'label'     => __( 'Background Color', 'wpsection' ),
                'condition'    => array( 'show_block_bottom' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wps_product_details.product_bottom' => 'background: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'block__bottom_hover_color',
            array(
                'label'     => __( 'Hover Color', 'wpsection' ),
               'condition'    => array( 'show_block_bottom' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wps_product_details.product_bottom:hover' => 'background: {{VALUE}} !important',
				
					
					
                ),
            )
        );
    
        $this->add_control(
            'block_bottom_margin',
            array(
                'label'     => __( 'Block Margin', 'wpsection' ),
                 'condition'    => array( 'show_block_bottom' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .wps_product_details.product_bottom' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'block_bottom_padding',
            array(
                'label'     => __( 'Block Padding', 'wpsection' ),
                  'condition'    => array( 'show_block_bottom' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .wps_product_details.product_bottom' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

            $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'block_bottom_shadow',
                  'condition'    => array( 'show_block_bottom' => 'show' ),
                'label' => esc_html__( 'Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_product_details.product_bottom',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'block_bottom_border',
                'condition'    => array( 'show_block_bottom' => 'show' ),
                'label' => esc_html__( 'Box Border', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_product_details.product_bottom',
            ]
        );
                
            $this->add_control(
            'block_bottom_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'condition'    => array( 'show_block_bottom' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wps_product_details.product_bottom' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        
        $this->end_controls_section();

                
        

		
		    


		
    }

protected function render() {




      global $product;
        global $wp_query;
        $settings = $this->get_settings_for_display();
        $allowed_tags = wp_kses_allowed_html('post');       
        $paged = get_query_var('paged');
        $paged = wpsection_set($_REQUEST, 'paged') ? esc_attr($_REQUEST['paged']) : $paged;
        $this->add_render_attribute( 'wrapper', 'class', 'templatepath-wpsection' );
    


        $product_per_page = 1;
        $product_order_by = 'date';
        $product_order    = 'DESC';

        $query_category = $settings['query_category'];
 
     
                $args = array(
                        'post_type' => 'product',
                        'posts_per_page' => $product_per_page,
                        'orderby' => $product_order_by,
                        'order' => $product_order,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field' => 'term_id',
                                'terms' => $query_category,
                                'operator' => 'IN',
                            ),
                        ),
                    );
               
      




    $query = new \WP_Query( $args );
    if ( $query->have_posts() ) {   
    $style = $settings['style']; 
    $style_folder = __DIR__ . '/wps_category/';
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

Plugin::instance()->widgets_manager->register( new \wpsection_wps_category_Widget() );
    
    
}
    