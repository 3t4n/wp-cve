<?php 

    $this->add_control(
            'product_grid_type',
            array(
                'label'   => esc_html__( 'Products Type', 'wpsection' ),
                'type'    =>  \Elementor\Controls_Manager::SELECT,
                'default' => 'recent_products',
                'options' => array(
                    'featured_products'     => esc_html__( 'Featured Products', 'wpsection' ),
                    'sale_products'         => esc_html__( 'Sale Products', 'wpsection' ),
                    'best_selling_products' => esc_html__( 'Best Selling Products', 'wpsection' ),
                    'recent_products'       => esc_html__( 'Recent Products', 'wpsection' ),
                    'top_rated_products'    => esc_html__( 'Top Rated Products', 'wpsection' ),
                    'product_category'      => esc_html__( 'Product Category', 'wpsection' ),
                ),
            )
        );



 $this->add_control(
            'query_category',
            array(
                'label' => __( 'Select Category', 'wpsection' ),
                'condition' => array('product_grid_type' => 'product_category'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => mr_shop_product_cat_list(), // Connect the function to the 'options' parameter
                'default' => ' ', // Set the default value to an empty string
                'description' => esc_html__( 'All Categories are Selected. Click Cross to Select Again', 'wpsection' ),
            )
        );


    

      $this->add_control(
            'query_number',
              array(
            
                'label'   => esc_html__( 'Number of post', 'wpsection' ),
                'label_block' => false,
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
                'min'     => 1,
                'max'     => 100,
                'step'    => 1,
            )
        );
   
     $this->add_control(
            'query_orderby',
          array(
                'label'   => esc_html__( 'Order By', 'wpsection' ),
                'label_block' => false,
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'date',
                'options' => array(
                    'date'       => esc_html__( 'Date', 'wpsection' ),
                    'title'      => esc_html__( 'Title', 'wpsection' ),
                    'menu_order' => esc_html__( 'Menu Order', 'wpsection' ),
                    'rand'       => esc_html__( 'Random', 'wpsection' ),
                ),
            )
        );

   $this->add_control(
            'query_order',
            array(
                'label'   => esc_html__( 'Order', 'wpsection' ),
                'label_block' => false,
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => array(
                    'DESC' => esc_html__( 'DESC', 'wpsection' ),
                    'ASC'  => esc_html__( 'ASC', 'wpsection' ),
                ),
                'separator' => 'after'
            )
        );

    $this->add_control(
            'wps_columns',
            array(
                'label' => __( 'Normal Columns Settings', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
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
            'wps_columns_tab',
            array(
                'label' => __( 'Tab Columns Settings', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
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
            'container_width',
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
                    '{{WRAPPER}} .auto-container' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );    



    $this->add_control(
                'show_features_expand',
               array(
                    'label' => __( 'Features Text Expand', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'return_value' => '1',
                     'default'      => '1',
                    'placeholder' => __( 'Show Features Text Expand', 'wpsection' ),
                )
            );

  $this->add_control(
            'wps_columns_expand',
            array(
                'label' => __( 'Expand Settings', 'wpsection' ),
				 'condition'    => array( 'show_features_expand' => '1' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top'  => __( 'Expand on Top', 'wpsection' ),
                    'bottom' => __( 'Expand to Bottom', 'wpsection' ),
               
                ],
            )
        );

  $this->add_control(
            'expand_top_height',
            [
                'label' => esc_html__( 'Expand Top Height ', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
				 'condition'    => array( 'wps_columns_expand' => 'top' ),
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
                'default' => [
                    'unit' => 'px',
                    'size' => 80,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mr_shop .product-block_hr_001:hover .wps_hide_two_block .hider_area_2' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mr_shop .product-block_hr_001:hover .wps_hide_two_block .wps_product_details.product_bottom.mr_bottom' => 'margin-top: -{{SIZE}}{{UNIT}};',
					
					
                ],
            ]
        );  



    $this->add_control(
                'wps_block_pagination',
                [
                    'label'   => __('Enable Pagination', 'wpsection'),
                    'type'    => \Elementor\Controls_Manager::SWITCHER,
                    'default' => 'no',
                ]
            );
    //End of Genaral Settings

$this->end_controls_section();