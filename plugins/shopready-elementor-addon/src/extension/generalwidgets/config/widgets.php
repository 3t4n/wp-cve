<?php

  if(!defined('ABSPATH')){
    exit;
  }
    /**************************************** 
    * all Widgets settings 
    * Widget will read this arra
    * since 1.0
    **********************  ****************/

    /********************** *********************
    * since 1.0 
    * Navigation Menu
    * Mega Menu
    * Navigation Offcanvas
    * Mobile Menu
    * Area Title
    * Archive Title
    * MailChimps
    * Post Group 
    * Counter
    * Image Carasoul 
    * Team 
    * Service 
    * PopUp Video
    * Advanced Tabs
    ************************** **************/

    /****************************************
    * widgets/components Config
    * slug = directory_name+filename
    * key should be lower_case
    **************** *************/

    $return_component  = [

            'navigation_wready_menu' => [
                
                'title'         => esc_html__('Navigation Menu','shopready-elementor-addon'),
                'icon'          => 'eicon-navigation-horizontal',
                'show_in_panel' => false,
                'is_pro'        => false,
                'dashboard'     => 'yes',
                'category'      => [ 'wgenerel' ],
                'keywords'      => [ 'Navigation' , 'footer menu' ],
                'css'           => [
                  'woo-ready-extra-widgets-base' 
                ]
            ], 
    
            'navigation_woo_ready_mega_menu' => [
                
              'title'         => esc_html__('Mega Menu','shopready-elementor-addon'),
              'icon'          => 'eicon-menu-toggle',
              'show_in_panel' => false,
              'is_pro'        => false,
              'dashboard'     => 'yes',
              'category'      => [ 'wgenerel' ],
              'keywords'      => [ 'navigation' , 'mega menu' ,'header menu' ],
              'css'           => [
                'woo-ready-m-menu' 
              ],
            
            ], 

            'navigation_wr_offcanvas' => [
                
              'title'         => esc_html__('Navigation Offcanvas','shopready-elementor-addon'),
              'icon'          => 'eicon-menu-toggle',
              'show_in_panel' => false,
              'is_pro'        => true,
              'dashboard'     => 'yes',
              'category'      => [ 'wgenerel' ],
              'keywords'      => [ 'navigation' , 'sidebar' ,'header menu','offcanvas' ],
              'css'           => [
                'woo-ready-m-menu' 
              ],
              'js' => [

                'woo-ready-extra-widgets'

              ]
            ], 
            
            'navigation_woo_mobile_menu' => [
                
              'title'         => esc_html__('Mobile Menu','shopready-elementor-addon'),
              'icon'          => 'eicon-menu-toggle',
              'show_in_panel' => false,
              'is_pro'        => true,
              'dashboard'     => 'yes',
              'category'      => [ 'wgenerel' ],
              'keywords'      => [ 'navigation' , 'sidebar' ,'mobile menu','offcanvas' ],
              'css'           => [
                'woo-ready-m-menu' 
              ],
              'js' => [

                'woo-ready-extra-widgets'

              ]
            ],
            
            'heading_woo_title' => [

                'title'         => esc_html__('Area Title','shopready-elementor-addon'),
                'icon'          => 'eicon-site-title',
                'show_in_panel' => false,
                'is_pro'        => false,
                'dashboard'     => 'yes',
                'category'      => [ 'wgenerel' ],
                'keywords'      => [ 'title', 'area title', 'section title', 'page title' ],
                'css'           => [
                  'woo-ready-extra-widgets-base' 
                ]
            ],

            'heading_woo_archive_title' => [

              'title'         => esc_html__('Shop Archive Title','shopready-elementor-addon'),
              'icon'          => 'eicon-site-title',
              'show_in_panel' => false,
              'is_pro'        => false,
              'dashboard'     => 'yes',
              'category'      => [ 'wgenerel' ],
              'keywords'      => [ 'title', 'area title', 'section title', 'page title' ],
              'css'           => [
                'woo-ready-extra-widgets-base' 
              ]
            ],

            'heading_woo_archive_desc' => [

              'title'         => esc_html__('Shop Archive Desc','shopready-elementor-addon'),
              'icon'          => 'eicon-site-title',
              'show_in_panel' => false,
              'is_pro'        => false,
              'dashboard'     => 'yes',
              'category'      => [ 'wgenerel' ],
              'keywords'      => [ 'Shop Archive Desc','Desc' ],
              'css'           => [
                'woo-ready-extra-widgets-base' 
              ]
          ],
            
            'heading_woo_animate_headline' => [

                'title'         => esc_html__('SR Animate Headline','shopready-elementor-addon'),
                'icon'          => 'eicon-animated-headline',
                'show_in_panel' => false,
                'is_pro'        => true,
                'dashboard'     => 'yes',
                'category'      => [ 'wgenerel' ],
                'keywords'      => [ 'animate heading', 'headline', 'animation' ],

                'css'           => [

                  'animatedheadline','woo-ready-extra-widgets-base' 

                ],

                'js' => [

                  'animatedheadline','woo-ready-extra-widgets'

                ]
            ],
            
            'heading_woo_dual_text' => [

                'title'         => esc_html__('SR Duel Text','shopready-elementor-addon'),
                'icon'          => 'eicon-animated-headline',
                'show_in_panel' => false,
                'is_pro'        => true,
                'dashboard'     => 'yes',
                'category'      => [ 'wgenerel' ],
                'keywords'      => [ 'Duel text', 'text' ],

                'css'           => [

                  'woo-ready-extra-widgets-base' 

                ],

                'js' => [

                  'woo-ready-extra-widgets'

                ]
            ],

            'forms_woo_ready_contact_form_seven_widget' => [

              'title'         => esc_html__('Contact Form Seven','shopready-elementor-addon'),
              'icon'          => 'eicon-animated-headline',
              'show_in_panel' => false,
              'is_pro'        => true,
              'dashboard'     => 'yes',
              'category'      => [ 'wgenerel' ],
              'keywords'      => [ 'Contact Form 7', 'Contact Form Seven' ],
              'css'           => [
                'woo-ready-extra-widgets-base','nice-select' 
              ],

              'js' => [
                'woo-ready-extra-widgets','nice-select'
              ]
            ],

            'forms_woo_ready_weforms_widget' => [

              'title'         => esc_html__('WeForm','shopready-elementor-addon'),
              'icon'          => 'eicon-mail',
              'show_in_panel' => false,
              'is_pro'        => true,
              'dashboard'     => 'yes',
              'category'      => [ 'wgenerel' ],
              'keywords'      => [ 'WeForm', 'Contact Form' ],
              'css'           => [
                'woo-ready-extra-widgets-base','nice-select' 
              ],

              'js' => [
                'woo-ready-extra-widgets','nice-select'
              ]
            ],

            'generel_hotspot' => [

              'title'         => esc_html__('SR HotSpot','shopready-elementor-addon'),
              'icon'          => 'eicon-mail',
              'show_in_panel' => false,
              'is_pro'        => true,
              'dashboard'     => 'yes',
              'category'      => [ 'wgenerel' ],
              'keywords'      => [ 'HotSpot', 'Image Picker' ],
            ],

            'price_table_woo_ready_table' => [

              'title'         => esc_html__('Price Table','shopready-elementor-addon'),
              'icon'          => 'eicon-tabs',
              'show_in_panel' => false,
              'is_pro'        => true,
              'dashboard'     => 'yes',
              'category'      => [ 'wgenerel' ],
              'keywords'      => [ 'Price Table', 'Table' ],
              'css'           => [
                'woo-ready-extra-widgets-base'
              ],

              'js' => [
                
              ]
            ], 
            
            'accordion_woo_ready_adv' => [

              'title'         => esc_html__('Advanced Accordian','shopready-elementor-addon'),
              'icon'          => 'eicon-accordion',
              'show_in_panel' => false,
              'is_pro'        => true,
              'dashboard'     => 'yes',
              'category'      => [ 'wgenerel' ],
              'keywords'      => [ 'Accordian template', 'Accordian' ],
              'css'           => [
                'woo-ready-extra-widgets-base'
              ],

              'js' => [
                'woo-ready-extra-widgets'
              ]
            ],
            
            'subscription_woo_ready_mailchimps' => [

              'title'         => esc_html__('MailChimps','shopready-elementor-addon'),
              'icon'          => 'eicon-mailchimp',
              'show_in_panel' => false,
              'is_pro'        => true,
              'dashboard'     => 'yes',
              'category'      => [ 'wgenerel' ],
              'keywords'      => [ 'Subscriber', 'MailChimp', 'Email Signup' ],
              'css'           => [
                'woo-ready-extra-widgets-base'
              ],

              'js' => [
                'woo-ready-extra-widgets','ajaxchimp'
              ]
            ],
            
            'counter_woo_ready_counter' => [

              'title'         => esc_html__('Counter','shopready-elementor-addon'),
              'icon'          => 'eicon-counter',
              'show_in_panel' => false,
              'is_pro'        => true,
              'dashboard'     => 'yes',
              'category'      => [ 'wgenerel' ],
              'keywords'      => [ 'counter up', 'counter','counter box' ],
              'css'           => [
              'woo-ready-extra-widgets-base'
              ],

              'js' => [
                'jquery-numerator','woo-ready-extra-widgets'
              ]
            ],
            
            'posts_woo_ready_post_carousel' => [
                'title'         => esc_html__('Post Carousel','shopready-elementor-addon'),
                'icon'          => 'eicon-posts-carousel',
                'show_in_panel' => false,
                'is_pro'        => false,
                'dashboard'     => 'yes',
                'category'      => [ 'wgenerel' ],
                'keywords'      => [ 'Carousel', 'Post Carousel', 'Slider', 'post', 'blog' ],
                'css'           => [
                  'woo-ready-extra-widgets-base',
                  'slick'
                ],
                'js' => [
                  'slick',
                  'woo-ready-extra-widgets'
                ]
            ], 
            
            'posts_woo_ready_post_group' => [
                'title'         => esc_html__('Post Group','shopready-elementor-addon'),
                'icon'          => 'eicon-posts-group',
                'show_in_panel' => false,
                'is_pro'        => false,
                'dashboard'     => 'yes',
                'category'      => [ 'wgenerel' ],
                'keywords'      => [ 'Post', 'Post Group', 'Blog Post', 'Post Grid' ],
                'css'           => [
                  'woo-ready-extra-widgets-base',
                ],
                'js' => [
                  'imagesloaded',
                  'woo-ready-extra-widgets',
                  'masonry'
                ]
            ],

            'testimonial_woo_ready_testmonial' => [
              'title'         => esc_html__('Testimonial','shopready-elementor-addon'),
              'icon'          => 'eicon-testimonial',
              'show_in_panel' => false,
              'is_pro'        => false,
              'dashboard'     => 'yes',
              'category'      => [ 'wgenerel' ],
              'keywords'      => [ 'Testimonial', 'Testimonial Slider', 'Testimonial', 'Review', 'Feedback' ],
              'css'           => [
                'woo-ready-extra-widgets-base',
              ],
              'js' => [
               
                'woo-ready-extra-widgets',
                'owl-carousel',
               
              ],
              'css' => [
                'woo-ready-extra-widgets-base','owl-carousel'
              ]
            ], 
            
            'tab_shop_ready_advanced' => [
              'title'         => esc_html__('Advanced Tabs','shopready-elementor-addon'),
              'icon'          => 'eicon-tabs',
              'show_in_panel' => false, 
              'is_pro'        => true,
              'dashboard'     => 'yes',
              'category'      => [ 'shopready-elementor-addon' ],
              'keywords'      => [ 'Shop Ready Tab' , 'Advanced Tab' ],
              'js'            => [
                  'woo-ready-extra-widgets'
              ],
              'css' => [
                  'woo-ready-extra-widgets-base'
              ]
            ],
    ];

    // Call from anywhere
    return apply_filters( 'shop_ready_gen_widgets_config', $return_component );