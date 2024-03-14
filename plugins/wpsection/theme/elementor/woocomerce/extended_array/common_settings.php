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


//==================================Thumbnail Setting =====================================
    $this->start_controls_section(
                    'product_x_thumb_settings',
                    [
                        'label' => __( 'Thumbnail Settings', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                ); 

$this->add_control(
            'show_thumbnaili_view_setting',
            array(
                'label' => __( 'Thumbnail Options Settings', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                 'condition' => array('show_product_x_thumbnail' => '1'),
                'default' => 'thumbnai_meta_optins',
                'description' => __( 'Meta set diffrent Style / Elemntor set SAME fol All', 'wpsection' ),
                'options' => [
                    'thumbnai_meta_optins'  => __( 'Meta Thumbnail Settings', 'wpsection' ),
                    'thumbnai_elementor_optins' => __( 'Elemntor Thumbnail Settings', 'wpsection' ),
                
               
                ],
            )
        );


  $this->add_control(
            'wps_thumbnial_select',
            array(
                'label' => __( 'Thumbnail Style Settings', 'wpsection' ),
                'condition'    => array( 'show_thumbnaili_view_setting' => 'thumbnai_elementor_optins' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'thumbnail',
                'description' => __( 'If Option not found fallback  is Thumbnail', 'wpsection' ),
                'options' => [
                    'thumbnail'  => __( 'Thumbnail', 'wpsection' ),
                    'meta' => __( 'Meta Image', 'wpsection' ),
                    'meta_flip' => __( 'Meta Flip Image', 'wpsection' ),
                    'slide_number' => __( 'Slide Number', 'wpsection' ),
                    'hover_slide' => __( 'Hover Slides', 'wpsection' ),
               
                ],
            )
        );

  $this->add_control(
            'wps_thumbnail_width',
            [
                'label' => esc_html__( 'Image Width ', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'condition' => array('show_product_x_thumbnail' => '1'),
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
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mr_product_block img,.mr_product_block .flip-box-front' => 'max-width: {{SIZE}}{{UNIT}}!important;',
                    
                ],
            ]
        );  


  $this->add_control(
                'show_block_column_slide_nav',
               array(
                    'label' => __( 'Hide Dot/Color', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'condition' => array('show_product_x_thumbnail' => '1'),
                     'return_value' => '1',
                     'default'      => '1',
                    'placeholder' => __( 'Hide Slide Dot/Nav', 'wpsection' ),
                    'description' => __( 'This will disbale the dot or nav in the block', 'wpsection' ),
                   
                )
            );

 $this->add_control(
            'wps_product_color_dot',
            array(
                'label' => __( 'Product Slide Nav ', 'wpsection' ),
                'condition'    => array( 'show_block_column_slide_nav' => '1' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'product_dot',
                'description' => __( 'Set Style of dot from Sytle ', 'wpsection' ),
                'options' => [
                    'product_dot'  => __( 'Dot Color Defult', 'wpsection' ),
                    'product_color' => __( 'Color Form Meta', 'wpsection' ),
               
                ],
            )
        );

         $this->add_control(
                'show_product_x_thumbnail',
               array(
                    'label' => __( 'Hide Thumbnail', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'return_value' => '1',
                     'default'      => '1',
                    'placeholder' => __( 'Show Thumbnail', 'wpsection' ),
                )
            );




$this->end_controls_section();


// ======================= Title ====================================================
    $this->start_controls_section(
                    'product_title_settings',
                    [
                        'label' => __( 'Title Settings', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                ); 

     $this->add_control(
            'position_order_one',
            array(
                'label' => __( 'Position Order Settings', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1'  => __( '1st Position', 'wpsection' ),
                    '2' => __( '2nd Position', 'wpsection' ),
                    '3' => __( '3rd Position', 'wpsection' ),
                    '4' => __( '4th Position', 'wpsection' ),
                    '5' => __( '5th Position', 'wpsection' ),
                    '6' => __( '6th Position', 'wpsection' ),
                    '7' => __( '7th Position', 'wpsection' ),
                    '8' => __( '8th Position', 'wpsection' ),
                    '9' => __( '9th Position', 'wpsection' ),
                    '10' => __( '10th Position', 'wpsection' ),
                ],
            )
        );

         $this->add_control(
                'show_product_title',
               array(
                    'label' => __( 'Show Title', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'return_value' => '1',
                     'default'      => '1',
                    'placeholder' => __( 'Show Percent', 'wpsection' ),
                )
            );
$this->end_controls_section();



// ======================================= Review Rating Text =======================
    $this->start_controls_section(
                    'meta_review_settings',
                    [
                        'label' => __( 'Rating Settings', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                ); 


   $this->add_control(
            'review_text',
            array(
                'label'       => __( 'Review Text', 'wpsection' ),
                 'condition'    => array( 'show_product_x_rating' => '1' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'default' => __( 'Review', 'wpsection' ),
             
            )
        );


     $this->add_control(
            'position_order_two',
            array(
                'label' => __( 'Position Order Settings', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'condition'    => array( 'show_product_x_rating' => '1' ),
                'default' => '2',
                'options' => [
                    '1'  => __( '1st Position', 'wpsection' ),
                    '2' => __( '2nd Position', 'wpsection' ),
                    '3' => __( '3rd Position', 'wpsection' ),
                    '4' => __( '4th Position', 'wpsection' ),
                    '5' => __( '5th Position', 'wpsection' ),
                    '6' => __( '6th Position', 'wpsection' ),
                    '7' => __( '7th Position', 'wpsection' ),
                    '8' => __( '8th Position', 'wpsection' ),
                    '9' => __( '9th Position', 'wpsection' ),
                    '10' => __( '10th Position', 'wpsection' ),
                ],
            )
        );

         $this->add_control(
                'show_product_x_rating',
               array(
                    'label' => __( 'Show Percent', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'return_value' => '1',
                     'default'      => '1',
                    'placeholder' => __( 'Show Percent', 'wpsection' ),
                )
            );
$this->end_controls_section();

// ======================================= PRice Text =======================
    $this->start_controls_section(
                    'meta_price_s_settings',
                    [
                        'label' => __( 'Price Settings', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                ); 


     $this->add_control(
            'position_order_three',
            array(
                'label' => __( 'Position Order Settings', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'condition'    => array( 'show_product_price' => '1' ),
                'default' => '3',
                'options' => [
                    '1'  => __( '1st Position', 'wpsection' ),
                    '2' => __( '2nd Position', 'wpsection' ),
                    '3' => __( '3rd Position', 'wpsection' ),
                    '4' => __( '4th Position', 'wpsection' ),
                    '5' => __( '5th Position', 'wpsection' ),
                    '6' => __( '6th Position', 'wpsection' ),
                    '7' => __( '7th Position', 'wpsection' ),
                    '8' => __( '8th Position', 'wpsection' ),
                    '9' => __( '9th Position', 'wpsection' ),
                    '10' => __( '10th Position', 'wpsection' ),
                ],
            )
        );

         $this->add_control(
                'show_product_price',
               array(
                    'label' => __( 'Show Price', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'return_value' => '1',
                     'default'      => '1',
                    'placeholder' => __( 'Show Price', 'wpsection' ),
                )
            );
$this->end_controls_section();

// ======================================= Progress Text =======================
    $this->start_controls_section(
                    'meta_progress_x_settings',
                    [
                        'label' => __( 'Progress Settings', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                ); 

     $this->add_control(
            'position_order_four',
            array(
                'label' => __( 'Position Order Settings', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'condition'    => array( 'show_product_progress' => '1' ),
                'default' => '4',
                'options' => [
                    '1'  => __( '1st Position', 'wpsection' ),
                    '2' => __( '2nd Position', 'wpsection' ),
                    '3' => __( '3rd Position', 'wpsection' ),
                    '4' => __( '4th Position', 'wpsection' ),
                    '5' => __( '5th Position', 'wpsection' ),
                    '6' => __( '6th Position', 'wpsection' ),
                    '7' => __( '7th Position', 'wpsection' ),
                    '8' => __( '8th Position', 'wpsection' ),
                    '9' => __( '9th Position', 'wpsection' ),
                    '10' => __( '10th Position', 'wpsection' ),
                ],
            )
        );

         $this->add_control(
                'show_product_progress',
               array(
                    'label' => __( 'Show Prgress', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'return_value' => '1',
                     'default'      => '0',
                    'placeholder' => __( 'Show Prgress', 'wpsection' ),
                )
            );
$this->end_controls_section();


//========================== In stock Available =================================

    $this->start_controls_section(
                    'meta_stock_settings',
                    [
                        'label' => __( 'In Stock ', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                ); 

      $this->add_control(
            'instock_text',
            array(
                'label'       => __( 'Stock Text', 'wpsection' ),
                    'condition'    => array( 'show_instock' => '1' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'default' => __( 'In Stock', 'wpsection' ),
                   
            )
        );

          $this->add_control(
            'instock_text_not',
            array(
                'label'       => __( 'Stock Out Text', 'wpsection' ),
                    'condition'    => array( 'show_instock' => '1' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'default' => __( 'Sold Out', 'wpsection' ),
                   
            )
        );



    $this->add_control(
    'instock_icon',
    [
        'label' => __( 'In Stock Icon', 'wpsection' ),
        'condition'    => array( 'show_instock' => '1' ),
        'type' => \Elementor\Controls_Manager::ICONS,
        'default' => [
            'value' => 'eicon-product-stock',
            'library' => 'solid',
        ],
    ]
    );

   $this->add_control(
    'outstock_icon',
    [
        'label' => __( 'Out Stock Icon', 'wpsection' ),
        'condition'    => array( 'show_instock' => '1' ),
        'type' => \Elementor\Controls_Manager::ICONS,
        'default' => [
            'value' => 'eicon-product-info',
            'library' => 'solid',
        ],
    ]
    );



     $this->add_control(
            'position_order_five',
            array(
                'label' => __( 'Position Order Settings', 'wpsection' ),
                'condition'    => array( 'show_instock' => '1' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '5',
                'options' => [
                    '1'  => __( '1st Position', 'wpsection' ),
                    '2' => __( '2nd Position', 'wpsection' ),
                    '3' => __( '3rd Position', 'wpsection' ),
                    '4' => __( '4th Position', 'wpsection' ),
                    '5' => __( '5th Position', 'wpsection' ),
                    '6' => __( '6th Position', 'wpsection' ),
                    '7' => __( '7th Position', 'wpsection' ),
                    '8' => __( '8th Position', 'wpsection' ),
                    '9' => __( '9th Position', 'wpsection' ),
                    '10' => __( '10th Position', 'wpsection' ),
                ],
            )
        );
     $this->add_control(
                'show_instock',
                array(
                    'label' => __( 'Show In Stock', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                    'return_value' => '1',
                     'default'      => '1',
                    'placeholder' => __( 'Show In Stock', 'wpsection' ),
                     'separator' => 'after'
                )
            );
$this->end_controls_section();   



//=============================== CountDown ======================================
$this->start_controls_section(
            'countdown_settings',
            [
                'label' => __('Countdown Settings', 'wpsection'),
            ]
        );

        $this->add_control(
            'offer_days',
            [
                'label' => __('Days Text', 'wpsection'),
                'condition'    => array( 'show_countdown' => '1' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Days',
            ]
        );

        $this->add_control(
            'offer_hours',
            [
                'label' => __('Hours Text', 'wpsection'),
                'condition'    => array( 'show_countdown' => '1' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Hours',
            ]
        );

        $this->add_control(
            'offer_min',
            [
                'label' => __('Minutes Text', 'wpsection'),
                'condition'    => array( 'show_countdown' => '1' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Minutes',
            ]
        );

        $this->add_control(
            'offer_sec',
            [
                'label' => __('Seconds Text', 'wpsection'),
                 'condition'    => array( 'show_countdown' => '1' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Seconds',
            ]
        );


     $this->add_control(
            'position_order_six',
            array(
                'label' => __( 'Position Order Settings', 'wpsection' ),
                'condition'    => array( 'show_countdown' => '1' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '6',
                'options' => [
                    '1'  => __( '1st Position', 'wpsection' ),
                    '2' => __( '2nd Position', 'wpsection' ),
                    '3' => __( '3rd Position', 'wpsection' ),
                    '4' => __( '4th Position', 'wpsection' ),
                    '5' => __( '5th Position', 'wpsection' ),
                    '6' => __( '6th Position', 'wpsection' ),
                    '7' => __( '7th Position', 'wpsection' ),
                    '8' => __( '8th Position', 'wpsection' ),
                    '9' => __( '9th Position', 'wpsection' ),
                    '10' => __( '10th Position', 'wpsection' ),
                ],
            )
        );

     $this->add_control(
                'show_countdown',
                array(
                    'label' => __( 'Show Countdown', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                    'return_value' => '1',
                     'default'      => '0',
                    'placeholder' => __( 'Show Countdown', 'wpsection' ),
                     'separator' => 'after'
                )
            );


        $this->end_controls_section(); 


//===============================Offer Text ======================================
$this->start_controls_section(
            'offer_n_settings',
            [
                'label' => __('Offer Settings', 'wpsection'),
            ]
        );


     $this->add_control(
            'position_order_ten',
            array(
                'label' => __( 'Position Order Settings', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'condition'    => array( 'show_offer_x_event' => '1' ),
                'default' => '7',
                'options' => [
                    '1'  => __( '1st Position', 'wpsection' ),
                    '2' => __( '2nd Position', 'wpsection' ),
                    '3' => __( '3rd Position', 'wpsection' ),
                    '4' => __( '4th Position', 'wpsection' ),
                    '5' => __( '5th Position', 'wpsection' ),
                    '6' => __( '6th Position', 'wpsection' ),
                    '7' => __( '7th Position', 'wpsection' ),
                    '8' => __( '8th Position', 'wpsection' ),
                    '9' => __( '9th Position', 'wpsection' ),
                    '10' => __( '10th Position', 'wpsection' ),
                ],
            )
        );

     $this->add_control(
                'show_offer_x_event',
                array(
                    'label' => __( 'Show Offer Text', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                    'return_value' => '1',
                    'default'      => '0',
                    'placeholder' => __( 'Show Offer Text', 'wpsection' ),
                     'separator' => 'after'
                )
            );


        $this->end_controls_section(); 


 // ======================================= Product catargory =======================
    $this->start_controls_section(
                    'meta_catitem_settings',
                    [
                        'label' => __( 'Catagory Settings', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                ); 

     $this->add_control(
            'position_order_nine',
            array(
                'label' => __( 'Position Order Settings', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'condition'    => array( 'show_product_cat_features' => '1' ),
                'default' => '9',
                'options' => [
                    '1'  => __( '1st Position', 'wpsection' ),
                    '2' => __( '2nd Position', 'wpsection' ),
                    '3' => __( '3rd Position', 'wpsection' ),
                    '4' => __( '4th Position', 'wpsection' ),
                    '5' => __( '5th Position', 'wpsection' ),
                    '6' => __( '6th Position', 'wpsection' ),
                    '7' => __( '7th Position', 'wpsection' ),
                    '8' => __( '8th Position', 'wpsection' ),
                    '9' => __( '9th Position', 'wpsection' ),
                    '10' => __( '10th Position', 'wpsection' ),
                ],
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

 // ======================================= Product Features  Text =======================
    $this->start_controls_section(
                    'meta_featured_settings',
                    [
                        'label' => __( 'Features Text Settings', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                ); 

     $this->add_control(
            'position_order_seven',
            array(
                'label' => __( 'Position Order Settings', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'condition'    => array( 'show_product_features' => '1' ),
                'default' => '7',
                'options' => [
                    '1'  => __( '1st Position', 'wpsection' ),
                    '2' => __( '2nd Position', 'wpsection' ),
                    '3' => __( '3rd Position', 'wpsection' ),
                    '4' => __( '4th Position', 'wpsection' ),
                    '5' => __( '5th Position', 'wpsection' ),
                    '6' => __( '6th Position', 'wpsection' ),
                    '7' => __( '7th Position', 'wpsection' ),
                    '8' => __( '8th Position', 'wpsection' ),
                    '9' => __( '9th Position', 'wpsection' ),
                    '10' => __( '10th Position', 'wpsection' ),
                ],
            )
        );

 

         $this->add_control(
                'show_product_features',
               array(
                    'label' => __( 'Show Features Text', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'return_value' => '1',
                     'default'      => '0',
                    'placeholder' => __( 'Show Features Text', 'wpsection' ),
                )
            );
$this->end_controls_section();

   
// ====================================== Product Button  ===================================

$this->start_controls_section(
            'quick_view_button_settings',
            [
                'label' => __('Add to Cart Button', 'wpsection'),
            ]
        );

  $this->add_control(
                'show_prduct_x_button',
                array(
                    'label' => __( 'Show Product Button', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                    'return_value' => '1',
                     'default'      => '1',
                    'placeholder' => __( 'Show Product Button', 'wpsection' ),
                     'separator' => 'after'
                )
            );




    $this->add_control(
            'wps_quick_view_button', [
                'label'       => esc_html__( 'Buton Text', 'wpsection' ),
                 'condition'    => array( 'show_prduct_x_button' => '1' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'Add to Cart',
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );
        


        
      $this->add_control(
            'position_order_eight',
            array(
                'label' => __( 'Position Order Settings', 'wpsection' ),
                'condition'    => array( 'show_prduct_x_button' => '1' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '8',
                'options' => [
                    '1'  => __( '1st Position', 'wpsection' ),
                    '2' => __( '2nd Position', 'wpsection' ),
                    '3' => __( '3rd Position', 'wpsection' ),
                    '4' => __( '4th Position', 'wpsection' ),
                    '5' => __( '5th Position', 'wpsection' ),
                    '6' => __( '6th Position', 'wpsection' ),
                    '7' => __( '7th Position', 'wpsection' ),
                    '8' => __( '8th Position', 'wpsection' ),
                    '9' => __( '9th Position', 'wpsection' ),
                    '10' => __( '10th Position', 'wpsection' ),
                ],
            )
        );
    



$this->add_control(
                'show_prduct_addtocart_icon',
                array(
                    'label' => __( 'Show Add to Cart Icons', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'condition'    => array( 'show_prduct_x_button' => '1' ),
                    'return_value' => '1',
                    'default'      => '1',

                )
            );

    $this->add_control(
    'wps_product_adcart_icon',
    [
        'label' => __( 'Add to Cart Icon', 'wpsection' ),
        'condition'    => array( 'show_prduct_addtocart_icon' => '1' ),

        'type' => \Elementor\Controls_Manager::ICONS,
        'default' => [
            'value' => 'eicon-cart-light',
            'library' => 'solid',
        ],
    ]
    );

 


 $this->add_control(
                'show_prduct_custom_link',
                array(
                    'label' => __( 'Show Custom Link', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'condition'    => array( 'show_prduct_x_button' => '1' ),
                    'return_value' => '1',
                    'default'      => '0',
                    'placeholder' => __( 'Show Custom Link', 'wpsection' ),

                )
            );

        $this->add_control(
            'wps_quick_view_button_link', [
                'label'       => esc_html__( 'Set Link - Default Cart Page', 'wpsection' ),
                 'condition'    => array( 'show_prduct_custom_link' => '1' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ' ',
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );


        $this->end_controls_section(); 

//Product product plusin minus 


$this->start_controls_section(
                'wps_product_plus_minus',
                    [
                        'label' => __( 'Plus Minus Settings', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                );

   $this->add_control(
            'position_order_eleven',
            array(
                'label' => __( 'Position Order Settings', 'wpsection' ),
                'condition'    => array( 'wps_product_qun_hide' => '1' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '11',
                'options' => [
                    '1'  => __( '1st Position', 'wpsection' ),
                    '2' => __( '2nd Position', 'wpsection' ),
                    '3' => __( '3rd Position', 'wpsection' ),
                    '4' => __( '4th Position', 'wpsection' ),
                    '5' => __( '5th Position', 'wpsection' ),
                    '6' => __( '6th Position', 'wpsection' ),
                    '7' => __( '7th Position', 'wpsection' ),
                    '8' => __( '8th Position', 'wpsection' ),
                    '9' => __( '9th Position', 'wpsection' ),
                    '10' => __( '10th Position', 'wpsection' ),
                    '11' => __( '11th Position', 'wpsection' ),
                    '12' => __( '12th Position', 'wpsection' ),
                ],
            )
        );

 $this->add_control(
                'wps_product_qun_hide',
                array(
                    'label' => __( 'Hide Product Quantity', 'wpsection' ),
                    //'condition'    => array( 'show_prduct_x_button' => '1' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                    'return_value' => '1',
                     'default'      => '1',
                    'placeholder' => __( 'Hide Product Quantityn', 'wpsection' ),
            
                )
            );

$this->end_controls_section(); 
//End of product plusin minus

// ======================================Hot offe==============================================
    $this->start_controls_section(
                'meta_sale_settings',
                    [
                        'label' => __( 'Hot/Sale Settings', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                ); 



            $this->add_control(
            'hot_text',
            array(
                'label'       => __( 'Hot/Sale Text', 'wpsection' ),
                 'condition'    => array( 'show_hot' => '1' ),
                  'description'       => __( 'Check Product Meta for change Text.This is Default.', 'wpsection' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __( 'Hot/Sale', 'wpsection' ),
             
            )
        );


        $this->add_control(
                'show_hot',
               array(
                    'label' => __( 'Show Hot Tag', 'wpsection' ),
                     'description'       => __( 'This will hide Total Area', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'return_value' => '1',
                     'default'      => '1',
                    'placeholder' => __( 'Show Hot Tag', 'wpsection' ),
                )
            );

          $this->add_control(
                'show_hot_percent',
               array(
                    'label' => __( 'Show Hot Percent', 'wpsection' ),
                     'description'       => __( 'This will hide Only Percentage Area', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'return_value' => '1',
                     'default'      => '1',
                    'placeholder' => __( 'Show Hot Percent', 'wpsection' ),
                      'separator' => 'after'
                )
            );

  $this->end_controls_section();
// ======================================Special Offer ==============================================
    $this->start_controls_section(
                'meta_spcl_settings',
                    [
                        'label' => __( 'Special Offer Settings', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                ); 



            $this->add_control(
            'spcl_text',
            array(
                'label'       => __( 'Special Offer Text', 'wpsection' ),
                 'condition'    => array( 'show_spcl' => '1' ),
                  'description'       => __( 'Check Product Meta for change Text.This is Default.', 'wpsection' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __( 'Hot/Sale', 'wpsection' ),
             
            )
        );


        $this->add_control(
                'show_spcl',
               array(
                    'label' => __( 'Special Offer Tag', 'wpsection' ),
                     'description'       => __( 'This will hide Total Area', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'return_value' => '1',
                     'default'      => '1',
                    'placeholder' => __( 'Show Special Offer', 'wpsection' ),
                )
            );

  $this->end_controls_section();            

// ====================================== Wish List ==============================================
 $this->start_controls_section(
                'meta_wishlist_settings',
                    [
                        'label' => __( 'Wishlist Settings', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                ); 

      $this->add_control(
            'wishlist_tooltip',
            array(
                'label'       => __( 'Tooltip Wish list Text', 'wpsection' ),
                 'condition'    => array( 'show_whishlist' => '1' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'default' => __( 'wishlist', 'wpsection' ),
             

            )
        );  

$this->add_control(
            'wps_wishlist_link', [
                'label'       => esc_html__( 'Wish List Page Link', 'wpsection' ),
                      'condition'    => array( 'show_whishlist' => '1' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ' Wish List Link',
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );
        

  $this->add_control(
            'overlay_order_one',
            array(
                'label' => __( 'Position Order Settings', 'wpsection' ),
                'condition'    => array( 'show_whishlist' => '1' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1'  => __( '1st Position', 'wpsection' ),
                    '2' => __( '2nd Position', 'wpsection' ),
                    '3' => __( '3rd Position', 'wpsection' ),
                    '4' => __( '4th Position', 'wpsection' ),
                    '5' => __( '5th Position', 'wpsection' ),
                    '6' => __( '6th Position', 'wpsection' ),
                ],
            )
        );
    

           $this->add_control(
                'show_whishlist',
                array(
                    'label' => __( 'Show Wish List', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'return_value' => '1',
                     'default'      => '1',
                    'placeholder' => __( 'Show Wish List', 'wpsection' ),
                       'separator' => 'after'
                )
            );
                
  $this->end_controls_section();  

 // ======================================Show Compare ==============================================    
 $this->start_controls_section(
                    'meta_compare_settings',
                    [
                        'label' => __( 'Compare Settings', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                ); 
      $this->add_control(
            'compare_tooltip',
            array(
                'label'       => __( 'Tooltip Compare', 'wpsection' ),
                    'condition'    => array( 'show_compare' => '1' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'default' => __( 'Compare', 'wpsection' ),
             
            )
        );

     

    $this->add_control(
    'compare_icon',
    [
        'label' => __( 'Icon', 'wpsection' ),
        'condition'    => array( 'show_compare' => '1' ),
        'type' => \Elementor\Controls_Manager::ICONS,
        'default' => [
            'value' => 'eicon-product-related',
            'library' => 'solid',
        ],
    ]
    );
     
       $this->add_control(
            'overlay_order_two',
            array(
                'label' => __( 'Position Order Settings', 'wpsection' ),
                'condition'    => array( 'show_compare' => '1' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '2',
                'options' => [
                    '1'  => __( '1st Position', 'wpsection' ),
                    '2' => __( '2nd Position', 'wpsection' ),
                    '3' => __( '3rd Position', 'wpsection' ),
                    '4' => __( '4th Position', 'wpsection' ),
                    '5' => __( '5th Position', 'wpsection' ),
                    '6' => __( '6th Position', 'wpsection' ),
                ],
            )
        );
    
       $this->add_control(
                'show_compare',
                 array(
                    'label' => __( 'Show Compare', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'return_value' => '1',
                     'default'      => '1',
                    'placeholder' => __( 'Show Compare', 'wpsection' ),
                      'separator' => 'after'
                )
            );      
 $this->end_controls_section(); 

 // ====================================== Quick view ============================================== 
      $this->start_controls_section(
                    'meta_quickview_settings',
                    [
                        'label' => __( 'Quick View Settings', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                ); 

        $this->add_control(
            'quickview_tooltip',
            array(
                'label'       => __( 'Quickview Compare', 'wpsection' ),
                    'condition'    => array( 'show_quickview' => '1' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'default' => __( 'Quickview', 'wpsection' ),
               
            )
        );

    $this->add_control(
    'quickview_icon',
    [
        'label' => __( 'Icon', 'wpsection' ),
        'condition'    => array( 'show_quickview' => '1' ),
        'type' => \Elementor\Controls_Manager::ICONS,
        'default' => [
            'value' => 'eicon-product-add-to-cart',
            'library' => 'solid',
        ],
    ]
    );


   $this->add_control(
            'overlay_order_three',
            array(
                'label' => __( 'Position Order Settings', 'wpsection' ),
                'condition'    => array( 'show_quickview' => '1' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '1'  => __( '1st Position', 'wpsection' ),
                    '2' => __( '2nd Position', 'wpsection' ),
                    '3' => __( '3rd Position', 'wpsection' ),
                    '4' => __( '4th Position', 'wpsection' ),
                    '5' => __( '5th Position', 'wpsection' ),
                    '6' => __( '6th Position', 'wpsection' ),
                ],
            )
        );
             $this->add_control(
                'show_quickview',
                 array(
                    'label' => __( 'Show Quickview', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                     'return_value' => '1',
                     'default'      => '1',
                    'placeholder' => __( 'Show Quickview', 'wpsection' ),
                    'separator' => 'after'
                )
            );

    $this->end_controls_section();      

// ====================================== add to cart  ==============================================  
    $this->start_controls_section(
                    'meta_addtocar_settings',
                    [
                        'label' => __( 'Thumbnail Add to Cart', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                ); 

      $this->add_control(
            'addtocart_tooltip',
            array(
                'label'       => __( 'Tooltip Add to Cart', 'wpsection' ),
                    'condition'    => array( 'show_addtocart' => '1' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'default' => __( 'Add to Cart', 'wpsection' ),
                   
            )
        );


    $this->add_control(
    'addtocart_icon',
    [
        'label' => __( 'Icon', 'wpsection' ),
        'condition'    => array( 'show_addtocart' => '1' ),
        'type' => \Elementor\Controls_Manager::ICONS,
        'default' => [
            'value' => 'eicon-cart',
            'library' => 'solid',
        ],
    ]
    );

 $this->add_control(
            'overlay_order_four',
            array(
                'label' => __( 'Position Order Settings', 'wpsection' ),
                'condition'    => array( 'show_addtocart' => '1' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '1'  => __( '1st Position', 'wpsection' ),
                    '2' => __( '2nd Position', 'wpsection' ),
                    '3' => __( '3rd Position', 'wpsection' ),
                    '4' => __( '4th Position', 'wpsection' ),
                    '5' => __( '5th Position', 'wpsection' ),
                    '6' => __( '6th Position', 'wpsection' ),
                ],
            )
        );

            $this->add_control(
                'show_addtocart',
                array(
                    'label' => __( 'Show Add to cart', 'wpsection' ),
                    'type'     => \Elementor\Controls_Manager::SWITCHER,
                    'return_value' => '1',
                     'default'      => '1',
                    'placeholder' => __( 'Show Add tp Cart', 'wpsection' ),
                     'separator' => 'after'
                )
            );
$this->end_controls_section();     

