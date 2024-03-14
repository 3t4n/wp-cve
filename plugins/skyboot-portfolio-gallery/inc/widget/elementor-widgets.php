<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skyboot_Portfolio_Elementor_widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'skyboot-portfolio-gallery';
    }
    
    public function get_title() {
        return __( 'Skyboot: Portfolio', 'skyboot-pg' );
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }
    public function get_categories() {
        return [ 'general' ];
    }

    protected function register_controls() {

        // Section Heading Tab
        $this->start_controls_section(
            'content_section_1',
            [
                'label' => esc_html__( 'Section Heading', 'skyboot-pg' ),
            ]
        );
            $this->add_control(
                'enable_sec_heading',
                [
                    'label' => esc_html__( 'Show/Hide Section Heading', 'skyboot-pg' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );        
            $this->add_control(
                'skb_section_heading',
                [
                    'label' => __( 'Heading', 'skyboot-pg' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => 'Photo Gallery',
                    'label_block' => 'true',
                    'title' => __( 'Heading', 'skyboot-pg' ),
                    'condition' => [
                        'enable_sec_heading' => 'yes',
                    ]                    
                ]
            );
            $this->add_control(
                'skb_sub_heading',
                [
                    'label' => __( 'Sub Heading', 'skyboot-pg' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'default' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod',
                    'label_block' => 'true',
                    'title' => __( 'Sub Heading', 'skyboot-pg' ),
                    'condition' => [
                        'enable_sec_heading' => 'yes',
                    ]                        
                ]
            );
             $this->add_control(
                'enable_sec_separator',
                [
                    'label' => esc_html__( 'Show/Hide Section Separator', 'skyboot-pg' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'enable_sec_heading' => 'yes',
                    ]                          
                ]
            );     
            $this->add_control(
                'separator_color1',
                [
                    'label' => __( 'Color 1', 'skyboot-pg' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#dbd9da',
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .skb-section-title-separator::before' => 'background-color: {{VALUE}};',
                    ]           
                ]
            );     
            $this->add_control(
                'separator_color2',
                [
                    'label' => __( 'Color 2', 'skyboot-pg' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#FF5500',
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .skb-section-title-separator::after' => 'background-color: {{VALUE}};',
                    ]               
                ]
            );
        $this->end_controls_section();

        // Post settings tab
        $this->start_controls_section(
            'post_settings_section',
            [
                'label' => esc_html__( 'Post Settings', 'skyboot-pg' ),
            ]
        );
            $this->add_control(
                'post_limit',
                [
                    'label' => __( 'Post Limit', 'skyboot-pg' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 12,
                    'label_block' => 'true',
                    'title' => __( 'Post Limit', 'skyboot-pg' ),
                    'separator'=>'before'
                ]
            );
            $this->add_control(
                'custom_order',
                [
                    'label' => esc_html__( 'Custom order', 'skyboot-pg' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );            
            $this->add_control(
                'postorder',
                [
                    'label' => esc_html__( 'Order', 'skyboot-pg' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'DESC',
                    'options' => [
                        'DESC'  => esc_html__('Descending','skyboot-pg'),
                        'ASC'   => esc_html__('Ascending','skyboot-pg'),
                    ],
                    'condition' => [
                        'custom_order' => 'yes',
                    ]
                ]
            );            
            $this->add_control(
                'orderby',
                [
                    'label' => esc_html__( 'Orderby', 'skyboot-pg' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'none',
                    'options' => [
                        'none'          => esc_html__('None','skyboot-pg'),
                        'ID'            => esc_html__('ID','skyboot-pg'),
                        'date'          => esc_html__('Date','skyboot-pg'),
                        'name'          => esc_html__('Name','skyboot-pg'),
                        'title'         => esc_html__('Title','skyboot-pg'),
                        'comment_count' => esc_html__('Comment count','skyboot-pg'),
                        'rand'          => esc_html__('Random','skyboot-pg'),
                    ],
                    'condition' => [
                        'custom_order' => 'yes',
                    ]
                ]
            );
        $this->end_controls_section();

        // Genarel settings tab
        $this->start_controls_section(
            'genarel_settings_section',
            [
                'label' => esc_html__( 'Genarel Settings', 'skyboot-pg' ),
            ]
        );
            $this->add_control(
                'enable_filter_menu',
                [
                    'label' => esc_html__( 'Show/Hide Filter Menu', 'skyboot-pg' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );      
            $this->add_control(
                'column_count',
                [
                    'label' => esc_html__( 'Column Count', 'skyboot-pg' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '4',
                    'options' => [
                        '6'   => esc_html__('2','skyboot-pg'),
                        '4'   => esc_html__('3','skyboot-pg'),
                        '3'  => esc_html__('4','skyboot-pg'),
                        '2'  => esc_html__('6','skyboot-pg'),
                    ]
                ]
            );
            $this->add_control(
                'space_left_right',
                [
                    'label' => __( 'Space LEFT/RIGHT ', 'skyboot-pg' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px'],
                    'allowed_dimensions' => [ 'left', 'right'],
                    'selectors' => [
                        '{{WRAPPER}} .skb-col-sm-4.skb-col-xs-12' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_control(
                'space_bottom',
                [
                    'label' => __( 'Space BOTTOM ', 'skyboot-pg' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px'],
                    'allowed_dimensions' => [ 'bottom'],
                    'selectors' => [
                        '{{WRAPPER}} .skb-gallery-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'default' => [
                        'left' => false,
                        'right' => false,
                        'bottom' => '30',
                        'top' => false,
                        'isLinked' => true,
                    ]
                ]
            );
            $this->add_control(
                'set_icon',
                [
                    'label' => __( 'Set Icon', 'plugin-domain' ),
                    'type' => Controls_Manager::ICON,
                    'include' => [
                        'fa fa-camera',
                        'fa fa-camera-retro',
                        'fa fa-link',
                        'fa fa-chain',
                        'fa fa-photo',
                        'fa fa-arrows',
                        'fa fa-arrows-alt',
                        'fa fa-eye',
                        'fa fa-eye-slash',
                        'fa fa-film',
                        'fa fa-folder-open',
                        'fa fa-folder-open-o',
                        'fa fa-search',

                    ],
                    'default' => 'fa fa-photo',
                ]
            );
            $this->add_control(
                'enable_title',
                [
                    'label' => esc_html__( 'Show/Hide Title', 'skyboot-pg' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );   
            $this->add_control(
                'enable_sub_title',
                [
                    'label' => esc_html__( 'Show/Hide Sub Title', 'skyboot-pg' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );
            $this->add_control(
                'enable_overlay',
                [
                    'label' => esc_html__( 'Enable Overlay', 'skyboot-pg' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );    
            $this->add_control(
                'overlay_type',
                [
                    'label' => esc_html__( 'Overlay Type', 'skyboot-pg' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'direction_hover',
                    'options' => [
                        'direction_hover'          => esc_html__('Direction Hover','skyboot-pg'),
                        'normal_effect'            => esc_html__('Normal Effect','skyboot-pg'),
                    ],
                    'condition' => [
                        'enable_overlay' => 'yes',
                    ]
                ]
            );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'overy_background',
                    'label' => __( 'Overlay Gradient Background', 'skyboot-pg' ),
                    'types' => [ 'classic', 'gradient'],
                    'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .hover-effect-bg',
                    'condition' => [
                        'enable_overlay' => 'yes',
                    ]
                ]
            );            
            $this->add_control(
                'enable_mouse_hover_image_zoom',
                [
                    'label' => esc_html__( 'Enable Mouse Hover Image Zoom', 'skyboot-pg' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );                          
        $this->end_controls_section();

        // Popup settings tab
        $this->start_controls_section(
            'popup_settings_section',
            [
                'label' => esc_html__( 'Popup Settings', 'skyboot-pg' ),
            ]
        );
            $this->add_control(
                'enable_popup',
                [
                    'label' => esc_html__( 'Show/Hide Popup', 'skyboot-pg' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            ); 
            $this->add_control(
                'enable_popup_content',
                [
                    'label' => esc_html__( 'Show/Hide Popup Content', 'skyboot-pg' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            ); 

            $this->add_control(
                'popup_overlay_bg',
                [
                    'label' => __( 'Overlay BG', 'skyboot-pg' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => 'rgba(0,0,0,0.8)'             
                ]
            );

          
        $this->end_controls_section();
    

        // Popup settings tab
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__( 'Style', 'skyboot-pg' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'section_title_typography',
                'label' => __( 'Section Title Typography', 'skyboot-pg' ),
                'selector' => '{{WRAPPER}} .skb-section-title h2'
            ]
        );
        $this->add_control(
            'section_title_color',
            [
                'label' => __( 'Section Title Color', 'skyboot-pg' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}} .skb-section-title h2' => 'color: {{VALUE}};',
                ],
                'default' => '#FF5500',
                'separator' => 'after'

            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'section_sub_title_typography',
                'label' => __( 'Section Sub Title Typography', 'skyboot-pg' ),
                'selector' => '{{WRAPPER}} .skb-section-title p'
            ]
        );

        $this->add_control(
            'section_sub_title_color',
            [
                'label' => __( 'Section Sub Title Color', 'skyboot-pg' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}} .skb-section-title p' => 'color: {{VALUE}};',
                ],
                'default' => '#7a7a7a',
                'separator' => 'after'

            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'filter_menu_typography',
                'label' => __( 'Filter Typography', 'skyboot-pg' ),
                'selector' => '{{WRAPPER}} .skb-button-group button'
            ]
        );        
        $this->add_control(
            'filter_menu_color',
            [
                'label' => __( 'Filter Color', 'skyboot-pg' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}} .skb-button-group button' => 'color: {{VALUE}};',
                ],
                'default' => '#39434a'
            ]
        );        
        $this->add_control(
            'filter_menu_active_color',
            [
                'label' => __( 'Filter Active Color', 'skyboot-pg' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}} .skb-button-group button.is-checked' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .skb-button-group button:hover' => 'color: {{VALUE}};'
                ],
                'default' => '#FF5500',
                'separator' => 'after'

            ]
        );               
        $this->add_control(
            'item_icon_color',
            [
                'label' => __( 'Item Icon Color', 'skyboot-pg' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}} .skb-gallery-icon i' => 'color: {{VALUE}};',
                ],
                'default' => '#ffffff',
                'separator' => 'after'
            ]
        );      
         $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'item_title_typography',
                'label' => __( 'Item Title Typography', 'skyboot-pg' ),
                'selector' => '{{WRAPPER}} .skb-gallery-inner-content h4',

            ]
        );     
        $this->add_control(
            'item_title_color',
            [
                'label' => __( 'Item Title Color', 'skyboot-pg' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}} .skb-gallery-inner-content h4' => 'color: {{VALUE}};',
                ],
                'default' => '#ffffff',
                'separator' => 'after'
            ]
        );   
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'item_content_typography',
                'label' => __( 'Item Content Typography', 'skyboot-pg' ),
                'selector' => '{{WRAPPER}} .skb-gallery-inner-content span'
            ]
        );          
        $this->add_control(
            'item_content_color',
            [
                'label' => __( 'Item Content Color', 'skyboot-pg' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}} .skb-gallery-inner-content span' => 'color: {{VALUE}};',
                ],
                'default' => '#ffffff'
            ]
        );      
        
          
        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        // all fileds
        $enable_sec_heading            = $this->get_settings_for_display('enable_sec_heading');
        $enable_sec_separator            = $this->get_settings_for_display('enable_sec_separator');
        $skb_section_heading            = $this->get_settings_for_display('skb_section_heading');
        $skb_sub_heading            = $this->get_settings_for_display('skb_sub_heading');
        $enable_filter_menu            = $this->get_settings_for_display('enable_filter_menu');
        $column_count            = $this->get_settings_for_display('column_count');
        $set_icon            = $this->get_settings_for_display('set_icon');
        $enable_title            = $this->get_settings_for_display('enable_title');
        $enable_sub_title            = $this->get_settings_for_display('enable_sub_title');
        $enable_overlay            = $this->get_settings_for_display('enable_overlay');
        $overlay_type            = $this->get_settings_for_display('overlay_type');
        $enable_mouse_hover_image_zoom            = $this->get_settings_for_display('enable_mouse_hover_image_zoom');


        // popup settings
        $enable_popup            = $this->get_settings_for_display('enable_popup');
        $enable_popup_content            = $this->get_settings_for_display('enable_popup_content');
        $popup_overlay_bg            = $this->get_settings_for_display('popup_overlay_bg');

        // post settings
        $custom_order_ck    = $this->get_settings_for_display('custom_order');
        $postorder          = $this->get_settings_for_display('postorder'); 
        $orderby            = $this->get_settings_for_display('orderby');

        $settings   = $this->get_settings_for_display();
        $id = $this->get_id();

        ?>

    <!--Gallery area-->
    <div class="skb-gellery-area skb-id-<?php echo $id; ?>">
         <div class="skb-row">
            <div class="skb-col-xs-12">
                <?php if ( $enable_sec_heading == 'yes' ): ?>
                <div class="skb-section-title">

                    <?php if ( $enable_sec_separator == 'yes' ): ?>
                    <span class="skb-section-title-separator"></span>
                    <?php endif;?>

                    <?php if ( isset( $skb_section_heading ) ):  ?>
                    <h2><?php echo esc_html( $skb_section_heading ); ?></h2>
                    <?php endif;?>

                    <?php if ( isset( $skb_sub_heading ) ):  ?>
                    <p><?php echo esc_html( $skb_sub_heading ); ?></p>
                    <?php endif;?>

                </div>
                <?php endif;?>

                <?php if ( $enable_filter_menu == 'yes' ): ?>
                <div class="skb-button-group text-center">
                    <button class="button is-checked" data-filter="*"><?php esc_html_e( 'All' , 'skyboot-pg' ); ?></button>
                    <?php
                    $terms = get_terms( 'skyboot_portfolio_cat' );
                     foreach( $terms as $term ) {                          
                        $slug = $term->slug;
                        $name = $term->name;                                    
                    ?>
                    <button class="button" data-filter=".<?php echo esc_html( $slug ); ?>"><?php echo esc_html( $name ); ?></button>
                    <?php } ?>
                </div>
                <?php endif;?>

            </div>
        </div>

        <!-- Gallery Iteam -->
        <div class="skb-row">
            <div class="skb-grid new">
                <?php
                    // WP_Query arguments
                    $args = array (
                        'post_type'					=> 'skyboot_portfolio',
                        'post_status'				=> 'publish',
                        'taxonomy' 					=> 'skyboot_portfolio_cat', 
                        'posts_per_page'			=> !empty( $settings['post_limit'] ) ? $settings['post_limit'] : 12,
                        'order'						=> $postorder,
                        'orderby'					=> $orderby
                    );

                    // The Query
                    $skyboot_portfolio = new \WP_Query( $args );

                    // The Loop
                    if ( $skyboot_portfolio->have_posts() ) :
                        while ( $skyboot_portfolio->have_posts() ) :
                            $skyboot_portfolio->the_post(); 

                            $skyboot_portfolio_categories = get_the_terms(get_the_id(),'skyboot_portfolio_cat');
                        
                        ?>

                        <div class="skb-col-sm-<?php echo esc_html( $column_count ); ?> skb-col-xs-12 skb-grid-item <?php foreach( (array) $skyboot_portfolio_categories as $single_slug){echo $single_slug->slug. ' ';}   ?>">
                            <div class="skb-gallery-item <?php echo $enable_mouse_hover_image_zoom == "yes" ? "image-mouse-hover" : " "; ?>">
                                
                                <?php if( $enable_overlay =="yes" ) : ?>
                                <div class="<?php echo $overlay_type=="direction_hover" ? "skb-direction-hover-effect": 'normal-effect'; ?> hover-effect-bg"></div>
                                <?php endif; ?>

                                <?php if( has_post_thumbnail() ) { ?>
                                <div class="skb-gallery-image">
                                    <img src="<?php echo wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ), 'full' );?>" alt="">
                                </div>
                                <?php } ?>

                                <div class="gallery-text text-center">

                                    <?php if( $enable_popup == 'yes' ) : ?>
                                    <div class="skb-gallery-icon">
                                        <a class="skb-popup vbox-item"
                                        data-gall="gall1" <?php if( $enable_popup_content == 'yes' ): ?>data-title="<?php the_content(); ?>"<?php endif; ?>
                                        href="<?php echo wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ), 'full' );?>">
                                            <i class="<?php echo esc_html( $set_icon ); ?>"></i>
                                        </a>
                                    </div>
                                    <?php endif; ?>

                                    <div class="skb-gallery-inner-content">
                                        
                                        <?php if( $enable_title == 'yes' ) : ?>
                                        <h4><?php the_title(); ?></h4>
                                        <?php endif; ?>
                                        
                                        <?php if( $enable_sub_title == 'yes' ) : ?>
                                        <span><?php the_excerpt(); ?></span>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); wp_reset_query(); endif; ?>

            </div>
        </div>
    </div>
    <!--Gallery area End-->        
    <script>

        jQuery(document).ready(function(){
        /*isotop masonry*/
        jQuery('.new').imagesLoaded( function() {
            if(jQuery.fn.isotope){
                var $portfolio = jQuery('.new');
                $portfolio.isotope({
                    itemSelector: '.skb-grid-item',
                    filter: '*',
                    resizesContainer: true,
                    layoutMode: 'masonry',
                    transitionDuration: '0.8s'          
                });
                jQuery('button').on('click', function(){
                    jQuery('button').removeClass('is-checked');
                    jQuery(this).addClass('is-checked');
                    var selector = jQuery(this).attr('data-filter');
                    $portfolio.isotope({
                        filter: selector,
                    });
                });
            };
        });   

        // Gallery hover effect
        jQuery('.skb-gallery-item').each( function() { jQuery(this).hoverdir(); } ); 

        /* Venobox active*/
        jQuery('.skb-popup').venobox({
            border: '10px',             // default: '0'
            numeratio: false,            // default: false
            infinigall: true,

            <?php if ( $popup_overlay_bg ): ?>
            overlayColor: '<?php echo $popup_overlay_bg; ?>',
            <?php endif; ?>
            
            bgcolor: '#ffffff',
            arrowsColor:'#ffffff',
            closeColor:'#ffffff',
            spinColor: '#d2d2d2',
            titleattr: 'data-title',
            titlePosition: 'bottom',            
            titleBackground: '#000000',
            titleColor: '#fff',
            spinColor: '#ffffff',       
            spinner: 'cube-grid',       
        });            
        });



    </script>    

        <?php

    }

    protected function content_template() {}

}

Plugin::instance()->widgets_manager->register_widget_type( new Skyboot_Portfolio_Elementor_widget() );

