<?php
/**
 * Banner Element.
 * 
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 * 
 */
if( !class_exists( 'Wpmagazine_Modules_Lite_Banner_Element' ) ) :
    class Wpmagazine_Modules_Lite_Banner_Element extends \Elementor\Widget_Base {
        /**
         * @return - name of the widget
         */
        public function get_name() {
            return 'banner';
        }

        /**
         * @return - title of the widget
         */
        public function get_title() {
            return esc_html__( 'WP Magazine Banner', 'wp-magazine-modules-lite' );
        }

        /**
         * @return - icon for the widget
         */
        public function get_icon() {
            return 'cvicon-item cvicon-banner';
        }

        /**
         * @return - category name for the widget
         */
        public function get_categories() {
            return [ 'wpmagazine-modules-lite' ];
        }

        /**
         * 
         */
        public function cv_get_pages() {
            $page_list[''] = esc_html__( 'Select a page', 'wp-magazine-modules-lite' );
            $pages = get_pages( array( 'post_status' => 'publish' ) );
            foreach( $pages as $page ) {
                $page_list[ esc_html( $page->post_name ) ] = esc_html( $page->post_title );
            }
            return $page_list;
        }

        /**
         * add controls for widget.
         */
        protected function register_controls() {

            //General Settings
            $this->start_controls_section(
                'general_setting_section',
                [
                    'label' => esc_html__( 'General Setting', 'wp-magazine-modules-lite' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'blockTitle',
                [
                    'label' => esc_html__( 'Title', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Enter title', 'wp-magazine-modules-lite' )
                ]
            );

            $this->add_control(
                'blockTitleLayout',
                [
                    'label' => esc_html__( 'Block Title Layout', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'default'   => esc_html__( 'Default', 'wp-magazine-modules-lite' ),
                        'one'       => esc_html__( 'One', 'wp-magazine-modules-lite' ),
                        'two'       => esc_html__( 'Two', 'wp-magazine-modules-lite' )
                    ],
                    'condition' => [
                        'blockTitle!' => ''
                    ]
                ]
            );

            $this->add_control(
                'blockTitleAlign',
                [
                    'label' => esc_html__( 'Text Align', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'wp-magazine-modules-lite' ),
                            'icon' => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'wp-magazine-modules-lite' ),
                            'icon' => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'wp-magazine-modules-lite' ),
                            'icon' => 'fa fa-align-right',
                        ],
                    ],
                    'default' => 'left',
                    'toggle' => true,
                    'condition' => [
                        'blockTitle!' => ''
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .cvmm-block-title' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'contentType',
                [
                    'label' => esc_html__( 'Content Type', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'page',
                    'options' => [
                        'page'     => esc_html__( 'Page', 'wp-magazine-modules-lite' ),
                        'custom'   => esc_html__( 'Custom ( pro )', 'wp-magazine-modules-lite' )
                    ]
                ]
            );

            $this->add_control(
                'bannerPage',
                [
                    'label' => esc_html__( 'Select a page', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => $this->cv_get_pages(),
                    'condition' => [
                        'contentType'  => 'page'
                    ]
                ]
            );

            $this->add_control(
                'bannerImage',
                [
                    'label' => esc_html__( 'Banner Image', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'default' => [
                        'url' => '',
                    ],
                    'classes'   => 'cvmm-disable',
                    'condition' => [
                        'contentType'  => 'custom'
                    ]
                ]
            );

            $this->add_control(
                'titleOption',
                [
                    'label' => esc_html__( 'Show title', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                    'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                    'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                    'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                    'return_value' => 'show',
                    'default' => 'show',
                ]
            );

            $this->add_control(
                'bannerTitle',
                [
                    'label' => esc_html__( 'Title', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default'   => esc_html__( 'WP Magazine Modules Lite', 'wp-magazine-modules-lite' ),
                    'placeholder' => esc_html__( 'Add title here...', 'wp-magazine-modules-lite' ),
                    'classes'   => 'cvmm-disable',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'contentType',
                                'operator' => 'in',
                                'value' => [
                                    'custom',
                                ],
                            ],
                            [
                                'name' => 'titleOption',
                                'operator' => 'in',
                                'value' => [
                                    'show',
                                ],
                            ],
                        ],
                    ],
                ]
            );

            $this->add_control(
                'bannerTitleLink',
                [
                    'label' => esc_html__( 'Title Link', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Add link here...', 'wp-magazine-modules-lite' ),
                    'classes'   => 'cvmm-disable',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'contentType',
                                'operator' => 'in',
                                'value' => [
                                    'custom',
                                ],
                            ],
                            [
                                'name' => 'titleOption',
                                'operator' => 'in',
                                'value' => [
                                    'show',
                                ],
                            ],
                        ],
                    ]
                ]
            );

            $this->add_control(
                'descOption',
                [
                    'label' => esc_html__( 'Show description', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                    'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                    'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                    'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                    'return_value' => 'show',
                    'default' => 'show',
                ]
            );

            $this->add_control(
                'bannerDesc',
                [
                    'label' => esc_html__( 'Description', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                    'default'   => esc_html__( 'Complete Magazine Plugin', 'wp-magazine-modules-lite' ),
                    'placeholder' => esc_html__( 'Add desc here...', 'wp-magazine-modules-lite' ),
                    'classes'   => 'cvmm-disable',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'contentType',
                                'operator' => 'in',
                                'value' => [
                                    'custom',
                                ],
                            ],
                            [
                                'name' => 'descOption',
                                'operator' => 'in',
                                'value' => [
                                    'show',
                                ],
                            ],
                        ],
                    ]
                ]
            );

            $this->add_control(
                'button1Option',
                [
                    'label' => esc_html__( 'Show button one', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                    'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                    'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                    'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                    'return_value' => 'show',
                    'default' => 'show',
                ]
            );

            $this->add_control(
                'button1Label',
                [
                    'label' => esc_html__( 'Button Text', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => esc_html__( 'Button One', 'wp-magazine-modules-lite' ),
                    'condition' => [
                        'button1Option'  => 'show'
                    ]
                ]
            );

            $this->add_control(
                'button1Link',
                [
                    'label' => esc_html__( 'Button Link', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => esc_url( '#' ),
                    'condition' => [
                        'button1Option'  => 'show'
                    ]
                ]
            );

            $this->add_control(
                'button2Option',
                [
                    'label' => esc_html__( 'Show button two', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                    'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                    'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                    'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                    'return_value' => 'show',
                    'default' => 'show',
                ]
            );

            $this->add_control(
                'button2Label',
                [
                    'label' => esc_html__( 'Button Text', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => esc_html__( 'Button Two', 'wp-magazine-modules-lite' ),
                    'condition' => [
                        'button2Option'  => 'show'
                    ]
                ]
            );

            $this->add_control(
                'button2Link',
                [
                    'label' => esc_html__( 'Button Link', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => esc_url( '#' ),
                    'condition' => [
                        'button2Option'  => 'show'
                    ]
                ]
            );
            $this->end_controls_section();

            /**************************************************************/
            $this->start_controls_section(
                'extra_option_section',
                [
                'label' => esc_html__( 'Extra Settings', 'wp-magazine-modules-lite' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'permalinkTarget',
                [
                    'label' => esc_html__( 'Links open in', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '_blank',
                    'options' => [
                        '_self'  => esc_html__( 'Same Tab', 'wp-magazine-modules-lite' ),
                        '_blank'  => esc_html__( 'New Tab', 'wp-magazine-modules-lite' )
                    ],
                ]
            );
            $this->end_controls_section();
            /**************************************************/
            $this->start_controls_section(
                'style_section',
                [
                'label' => esc_html__( 'Layout Settings', 'wp-magazine-modules-lite' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_control(
                'blockLayout',
                [
                    'label' => esc_html__( 'Layouts', 'wp-magazine-modules-lite' ),
                    'type' => 'RADIOIMAGE',
                    'default' => 'layout-default',
                    'options' => [
                        [
                            'value' => 'layout-default',
                            'label' => esc_url( WPMAGAZINE_MODULES_LITE_INCLUDES_URL . '/assets/images/banner-layout-default.png' ),
                        ],
                        [
                            'value' => 'layout-one',
                            'label' => esc_url( WPMAGAZINE_MODULES_LITE_INCLUDES_URL . '/assets/images/banner-layout-one.png' ),
                        ]
                    ],
                ]
            );
            $this->end_controls_section();
            
            /**************************************************/
            $this->start_controls_section(
                'element_color_section',
                [
                'label' => esc_html__( 'Color Settings', 'wp-magazine-modules-lite' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_control(
                'blockPrimaryColor',
                [
                    'label' => esc_html__( 'Primary Color', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#029FB2',
                    'selectors' => [        
                        '{{WRAPPER}} .cvmm-block-title.layout--one span' => 'border-color: {{VALUE}}',
                        '{{WRAPPER}} .cvmm-block-title.layout--two span' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_control(
                'blockHoverColor',
                [
                    'label' => esc_html__( 'Hover Color', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#029FB2',
                    'selectors' => [
                        '{{WRAPPER}} .cvmm-banner-title a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->end_controls_section();
            /**************************************************/
            $this->start_controls_section(
                'element_typography_section',
                [
                'label' => esc_html__( 'Typography Section', 'wp-magazine-modules-lite' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );

                $this->add_control(
                    'typographyOption',
                    [
                        'label' => esc_html__( 'Inherit default from plugin typography', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                        'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                        'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                        'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                        'return_value' => 'show',
                        'default' => 'show',
                    ]
                );
                
                /**
                 * Start typography tabs
                 */
                $this->start_controls_tabs(
                    'style_typography_tabs'
                );
            
                    //Block Title Typo Tab
                    $this->start_controls_tab(
                        'block_title_typo_tab',
                        [
                            'label' => esc_html__( 'Block Title', 'wp-magazine-modules-lite' ),
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Typography::get_type(),
                        [
                            'name' => 'block_title_typography',
                            'label' => esc_html__( 'Block Title', 'wp-magazine-modules-lite' ),
                            'selector' => '{{WRAPPER}} .cvmm-block-title',
                            'exclude' => [ 'letter_spacing', 'font_family' ],
                            'fields_options'   => [
                                'font_weight' => [ 'default' => 700 ],
                                'font_style' => [ 'default' => 'normal' ],
                                'font_size' => [ 'default' => [ 'unit' => 'px','size' => 32 ] ],
                                'text_transform' => [ 'default' => 'uppercase' ],
                                'text_decoration' => [ 'default' => 'none' ],
                                'line_height' => [ 'default' => [ 'unit' => 'em','size' => 1.5 ] ],
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'block_title_family',
                        [
                            'label' => esc_html__( 'Font Family', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'Roboto',
                            'options' => [
                                'Yanone Kaffeesatz'  => esc_html__( 'Yanone Kaffeesatz', 'wp-magazine-modules-lite' ),
                                'Roboto'  => esc_html__( 'Roboto', 'wp-magazine-modules-lite' ),
                                'Open Sans' => esc_html__( 'Open Sans', 'wp-magazine-modules-lite' ),
                                'Roboto Slab' => esc_html__( 'Roboto Slab', 'wp-magazine-modules-lite' ),
                                'Poppins' => esc_html__( 'Poppins', 'wp-magazine-modules-lite' )
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-block-title' => 'font-family: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'blockTitleColor',
                        [
                            'label' => esc_html__( 'Font Color', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#3b3b3b',
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-block-title' => 'color: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'blockTitleBorderColor',
                        [
                            'label' => esc_html__( 'Border Color', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#f47e00',
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-block-title.layout--one span' => 'border-color: {{VALUE}}',

                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->end_controls_tab();

                    //Title Typo Tab
                    $this->start_controls_tab(
                        'title_typo_tab',
                        [
                            'label' => esc_html__( 'Title', 'wp-magazine-modules-lite' ),
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'titleTextAlign',
                        [
                            'label' => esc_html__( 'Text Align', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::CHOOSE,
                            'options' => [
                                'left' => [
                                    'title' => esc_html__( 'Left', 'wp-magazine-modules-lite' ),
                                    'icon' => 'fa fa-align-left',
                                ],
                                'center' => [
                                    'title' => esc_html__( 'Center', 'wp-magazine-modules-lite' ),
                                    'icon' => 'fa fa-align-center',
                                ],
                                'right' => [
                                    'title' => esc_html__( 'Right', 'wp-magazine-modules-lite' ),
                                    'icon' => 'fa fa-align-right',
                                ],
                            ],
                            'default' => 'left',
                            'toggle' => true,
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-title' => 'text-align: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Typography::get_type(),
                        [
                            'name' => 'title_typography',
                            'label' => esc_html__( 'Title', 'wp-magazine-modules-lite' ),
                            'selector' => '{{WRAPPER}} .cvmm-banner-title a',
                            'exclude' => [ 'letter_spacing', 'font_family' ],
                            'fields_options'   => [
                                'font_weight' => [ 'default' => 700 ],
                                'font_style' => [ 'default' => 'normal' ],
                                'font_size' => [ 'default' => [ 'unit' => 'px','size' => 28 ] ],
                                'text_transform' => [ 'default' => 'capitalize' ],
                                'text_decoration' => [ 'default' => 'none' ],
                                'line_height' => [ 'default' => [ 'unit' => 'em','size' => 1.5 ] ],
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'title_family',
                        [
                            'label' => esc_html__( 'Font Family', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'Roboto',
                            'options' => [
                                'Yanone Kaffeesatz'  => esc_html__( 'Yanone Kaffeesatz', 'wp-magazine-modules-lite' ),
                                'Roboto'  => esc_html__( 'Roboto', 'wp-magazine-modules-lite' ),
                                'Open Sans' => esc_html__( 'Open Sans', 'wp-magazine-modules-lite' ),
                                'Roboto Slab' => esc_html__( 'Roboto Slab', 'wp-magazine-modules-lite' ),
                                'Poppins' => esc_html__( 'Poppins', 'wp-magazine-modules-lite' )
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-title a' => 'font-family: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'titleFontColor',
                        [
                            'label' => esc_html__( 'Font Color', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#333333',
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-title a' => 'color: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'titleHoverColor',
                        [
                            'label' => esc_html__( 'Hover Color', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#f47e00',
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-title a:hover' => 'color: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->end_controls_tab();

                    //Description Typo Tab
                    $this->start_controls_tab(
                        'description_typo_tab',
                        [
                            'label' => esc_html__( 'Description', 'wp-magazine-modules-lite' ),
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );
                    
                    $this->add_control(
                        'descTextAlign',
                        [
                            'label' => esc_html__( 'Text Align', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::CHOOSE,
                            'options' => [
                                'left' => [
                                    'title' => esc_html__( 'Left', 'wp-magazine-modules-lite' ),
                                    'icon' => 'fa fa-align-left',
                                ],
                                'center' => [
                                    'title' => esc_html__( 'Center', 'wp-magazine-modules-lite' ),
                                    'icon' => 'fa fa-align-center',
                                ],
                                'right' => [
                                    'title' => esc_html__( 'Right', 'wp-magazine-modules-lite' ),
                                    'icon' => 'fa fa-align-right',
                                ],
                            ],
                            'default' => 'left',
                            'toggle' => true,
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-desc' => 'text-align: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Typography::get_type(),
                        [
                            'name' => 'content_typography',
                            'label' => esc_html__( 'Content/Excerpt', 'wp-magazine-modules-lite' ),
                            'selector' => '{{WRAPPER}} .cvmm-banner-desc',
                            'exclude' => [ 'letter_spacing', 'font_family' ],
                            'fields_options'   => [
                                'font_weight' => [ 'default' => 400 ],
                                'font_style' => [ 'default' => 'normal' ],
                                'font_size' => [ 'default' => [ 'unit' => 'px','size' => 15 ] ],
                                'text_transform' => [ 'default' => 'none' ],
                                'text_decoration' => [ 'default' => 'none' ],
                                'line_height' => [ 'default' => [ 'unit' => 'em','size' => 2 ] ],
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'content_family',
                        [
                            'label' => esc_html__( 'Font Family', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'Roboto',
                            'options' => [
                                'Yanone Kaffeesatz'  => esc_html__( 'Yanone Kaffeesatz', 'wp-magazine-modules-lite' ),
                                'Roboto'  => esc_html__( 'Roboto', 'wp-magazine-modules-lite' ),
                                'Open Sans' => esc_html__( 'Open Sans', 'wp-magazine-modules-lite' ),
                                'Roboto Slab' => esc_html__( 'Roboto Slab', 'wp-magazine-modules-lite' ),
                                'Poppins' => esc_html__( 'Poppins', 'wp-magazine-modules-lite' )
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-desc' => 'font-family: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'descFontColor',
                        [
                            'label' => esc_html__( 'Font Color', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#3b3b3b',
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-desc' => 'color: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->end_controls_tab();

                    //Button One Typo Tab
                    $this->start_controls_tab(
                        'button1_typo_tab',
                        [
                            'label' => esc_html__( 'Button One', 'wp-magazine-modules-lite' ),
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );
                    
                    $this->add_control(
                        'button1TextAlign',
                        [
                            'label' => esc_html__( 'Text Align', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::CHOOSE,
                            'options' => [
                                'left' => [
                                    'title' => esc_html__( 'Left', 'wp-magazine-modules-lite' ),
                                    'icon' => 'fa fa-align-left',
                                ],
                                'center' => [
                                    'title' => esc_html__( 'Center', 'wp-magazine-modules-lite' ),
                                    'icon' => 'fa fa-align-center',
                                ],
                                'right' => [
                                    'title' => esc_html__( 'Right', 'wp-magazine-modules-lite' ),
                                    'icon' => 'fa fa-align-right',
                                ],
                            ],
                            'default' => 'left',
                            'toggle' => true,
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-one' => 'text-align: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Typography::get_type(),
                        [
                            'name' => 'button1_typography',
                            'label' => esc_html__( 'Button', 'wp-magazine-modules-lite' ),
                            'selector' => '{{WRAPPER}} .cvmm-banner-button-one',
                            'exclude' => [ 'letter_spacing', 'line_height', 'text_decoration', 'font_style', 'font_family' ],
                            'fields_options'   => [
                                'font_weight' => [ 'default' => 400 ],
                                'font_size' => [ 'default' => [ 'unit' => 'px','size' => 15 ] ],
                                'text_transform' => [ 'default' => 'none' ],
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'button1_family',
                        [
                            'label' => esc_html__( 'Font Family', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'Roboto',
                            'options' => [
                                'Yanone Kaffeesatz'  => esc_html__( 'Yanone Kaffeesatz', 'wp-magazine-modules-lite' ),
                                'Roboto'  => esc_html__( 'Roboto', 'wp-magazine-modules-lite' ),
                                'Open Sans' => esc_html__( 'Open Sans', 'wp-magazine-modules-lite' ),
                                'Roboto Slab' => esc_html__( 'Roboto Slab', 'wp-magazine-modules-lite' ),
                                'Poppins' => esc_html__( 'Poppins', 'wp-magazine-modules-lite' )
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-one' => 'font-family: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'button1FontColor',
                        [
                            'label' => esc_html__( 'Font Color', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#3b3b3b',
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-one' => 'color: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'button1HoverColor',
                        [
                            'label' => esc_html__( 'Hover Color', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#3b3b3b',
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-one:hover' => 'color: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'button1BackgroundColor',
                        [
                            'label' => esc_html__( 'Background Color', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => 'transparent',
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-one' => 'background-color: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'button1BackgroundHoverColor',
                        [
                            'label' => esc_html__( 'Background Hover Color', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#f47e00',
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-one:hover' => 'background-color: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'button1BorderType',
                        [
                            'label' => esc_html__( 'Border Type', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'solid',
                            'options' => [
                                'none'   => esc_html__( 'None', 'wp-magazine-modules-lite' ),
                                'solid'   => esc_html__( 'Solid', 'wp-magazine-modules-lite' ),
                                'dotted'   => esc_html__( 'Dotted', 'wp-magazine-modules-lite' ),
                                'dashed'   => esc_html__( 'Dashed', 'wp-magazine-modules-lite' )
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-one' => 'border-style: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'button1BorderWeight',
                        [
                            'label' => esc_html__( 'Border Weight', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'min' => 0,
                            'max' => 10,
                            'step' => 0.1,
                            'default' => 1,
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-one' => 'border-width: {{VALUE}}px',
                            ],
                            'conditions' => [
                                'terms' => [
                                    [
                                        'name' => 'button1BorderType',
                                        'operator' => '!in',
                                        'value' => [
                                            'none',
                                        ],
                                    ],
                                    [
                                        'name' => 'typographyOption',
                                        'operator' => '!in',
                                        'value' => [
                                            'show',
                                        ],
                                    ],
                                ],
                            ],
                        ]
                    );

                    $this->add_control(
                        'button1BorderColor',
                        [
                            'label' => esc_html__( 'Border Color', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => 'transparent',
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-one' => 'border-color: {{VALUE}}',
                            ],
                            'conditions' => [
                                'terms' => [
                                    [
                                        'name' => 'button1BorderType',
                                        'operator' => '!in',
                                        'value' => [
                                            'none',
                                        ],
                                    ],
                                    [
                                        'name' => 'typographyOption',
                                        'operator' => '!in',
                                        'value' => [
                                            'show',
                                        ],
                                    ],
                                ],
                            ],
                        ]
                    );

                    $this->add_control(
                        'button1BorderHoverColor',
                        [
                            'label' => esc_html__( 'Border Hover Color', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#f47e00',
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-one:hover' => 'border-color: {{VALUE}}',
                            ],
                            'conditions' => [
                                'terms' => [
                                    [
                                        'name' => 'button1BorderType',
                                        'operator' => '!in',
                                        'value' => [
                                            'none',
                                        ],
                                    ],
                                    [
                                        'name' => 'typographyOption',
                                        'operator' => '!in',
                                        'value' => [
                                            'show',
                                        ],
                                    ],
                                ],
                            ],
                        ]
                    );

                    $this->add_control(
                        'button1Padding',
                        [
                            'label' => esc_html__( 'Padding', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'default'   => [
                                'top' => '2',
                                'right' => '10',
                                'bottom' => '2',
                                'left' => '10',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-one' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );
                    $this->end_controls_tab();

                    //Button One Typo Tab
                    $this->start_controls_tab(
                        'button2_typo_tab',
                        [
                            'label' => esc_html__( 'Button Two', 'wp-magazine-modules-lite' ),
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );
                    
                    $this->add_control(
                        'button2TextAlign',
                        [
                            'label' => esc_html__( 'Text Align', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::CHOOSE,
                            'options' => [
                                'left' => [
                                    'title' => esc_html__( 'Left', 'wp-magazine-modules-lite' ),
                                    'icon' => 'fa fa-align-left',
                                ],
                                'center' => [
                                    'title' => esc_html__( 'Center', 'wp-magazine-modules-lite' ),
                                    'icon' => 'fa fa-align-center',
                                ],
                                'right' => [
                                    'title' => esc_html__( 'Right', 'wp-magazine-modules-lite' ),
                                    'icon' => 'fa fa-align-right',
                                ],
                            ],
                            'default' => 'left',
                            'toggle' => true,
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-two' => 'text-align: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Typography::get_type(),
                        [
                            'name' => 'button2_typography',
                            'label' => esc_html__( 'Button', 'wp-magazine-modules-lite' ),
                            'selector' => '{{WRAPPER}} .cvmm-banner-button-two',
                            'exclude' => [ 'letter_spacing', 'line_height', 'text_decoration', 'font_style', 'font_family' ],
                            'fields_options'   => [
                                'font_weight' => [ 'default' => 400 ],
                                'font_size' => [ 'default' => [ 'unit' => 'px','size' => 15 ] ],
                                'text_transform' => [ 'default' => 'none' ],
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'button2_family',
                        [
                            'label' => esc_html__( 'Font Family', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'Roboto',
                            'options' => [
                                'Yanone Kaffeesatz'  => esc_html__( 'Yanone Kaffeesatz', 'wp-magazine-modules-lite' ),
                                'Roboto'  => esc_html__( 'Roboto', 'wp-magazine-modules-lite' ),
                                'Open Sans' => esc_html__( 'Open Sans', 'wp-magazine-modules-lite' ),
                                'Roboto Slab' => esc_html__( 'Roboto Slab', 'wp-magazine-modules-lite' ),
                                'Poppins' => esc_html__( 'Poppins', 'wp-magazine-modules-lite' )
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-two' => 'font-family: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'button2FontColor',
                        [
                            'label' => esc_html__( 'Font Color', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#3b3b3b',
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-two' => 'color: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'button2HoverColor',
                        [
                            'label' => esc_html__( 'Hover Color', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#3b3b3b',
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-two:hover' => 'color: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'button2BackgroundColor',
                        [
                            'label' => esc_html__( 'Background Color', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => 'transparent',
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-two' => 'background-color: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'button2BackgroundHoverColor',
                        [
                            'label' => esc_html__( 'Background Hover Color', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#f47e00',
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-two:hover' => 'background-color: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'button2BorderType',
                        [
                            'label' => esc_html__( 'Border Type', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'solid',
                            'options' => [
                                'none'   => esc_html__( 'None', 'wp-magazine-modules-lite' ),
                                'solid'   => esc_html__( 'Solid', 'wp-magazine-modules-lite' ),
                                'dotted'   => esc_html__( 'Dotted', 'wp-magazine-modules-lite' ),
                                'dashed'   => esc_html__( 'Dashed', 'wp-magazine-modules-lite' )
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-two' => 'border-style: {{VALUE}}',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );

                    $this->add_control(
                        'button2BorderWeight',
                        [
                            'label' => esc_html__( 'Border Weight', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'min' => 0,
                            'max' => 10,
                            'step' => 0.1,
                            'default' => 1,
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-two' => 'border-width: {{VALUE}}px',
                            ],
                            'conditions' => [
                                'terms' => [
                                    [
                                        'name' => 'button2BorderType',
                                        'operator' => '!in',
                                        'value' => [
                                            'none',
                                        ],
                                    ],
                                    [
                                        'name' => 'typographyOption',
                                        'operator' => '!in',
                                        'value' => [
                                            'show',
                                        ],
                                    ],
                                ],
                            ],
                        ]
                    );

                    $this->add_control(
                        'button2BorderColor',
                        [
                            'label' => esc_html__( 'Border Color', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => 'transparent',
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-two' => 'border-color: {{VALUE}}',
                            ],
                            'conditions' => [
                                'terms' => [
                                    [
                                        'name' => 'button2BorderType',
                                        'operator' => '!in',
                                        'value' => [
                                            'none',
                                        ],
                                    ],
                                    [
                                        'name' => 'typographyOption',
                                        'operator' => '!in',
                                        'value' => [
                                            'show',
                                        ],
                                    ],
                                ],
                            ],
                        ]
                    );

                    $this->add_control(
                        'button2BorderHoverColor',
                        [
                            'label' => esc_html__( 'Border Hover Color', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#f47e00',
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-two:hover' => 'border-color: {{VALUE}}',
                            ],
                            'conditions' => [
                                'terms' => [
                                    [
                                        'name' => 'button2BorderType',
                                        'operator' => '!in',
                                        'value' => [
                                            'none',
                                        ],
                                    ],
                                    [
                                        'name' => 'typographyOption',
                                        'operator' => '!in',
                                        'value' => [
                                            'show',
                                        ],
                                    ],
                                ],
                            ],
                        ]
                    );

                    $this->add_control(
                        'button2Padding',
                        [
                            'label' => esc_html__( 'Padding', 'wp-magazine-modules-lite' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'default'   => [
                                'top' => '2',
                                'right' => '10',
                                'bottom' => '2',
                                'left' => '10',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .cvmm-banner-button-two' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'condition' => [
                                'typographyOption!' => 'show'
                            ]
                        ]
                    );
                    $this->end_controls_tab();
                $this->end_controls_tab(); // End typography tabs "style_typography_tabs"
            $this->end_controls_section();
        }

        /**
         * renders the widget content.
         */
        protected function render() {
            $settings = $this->get_settings_for_display();
            extract( $settings );
            $element_id = $this->get_id();
            $titleOption    = ( $titleOption === 'show' );
            $descOption     = ( $descOption === 'show' );
            $button1Option  = ( $button1Option === 'show' );
            $button2Option  = ( $button2Option === 'show' );
            $typographyOption= ( $typographyOption === 'show' );

            echo '<div id="wpmagazine-modules-lite-banner-block-'.esc_html( $element_id ).'" class="wpmagazine-modules-lite-banner-block block-'.esc_html( $element_id ).' cvmm-block cvmm-block-banner--'.esc_html( $blockLayout ).'">';
                if( !empty( $blockTitle ) ) {
                    echo '<h2 class="cvmm-block-title layout--'.esc_html( $blockTitleLayout ).'"><span>'.esc_html( $blockTitle ).'</span></h2>';
                }
                switch( $contentType ) {
                    default: $page_query = new WP_Query(
                                                    array(
                                                        'post_status'   => 'publish',
                                                        'post_type'     => 'page',
                                                        'name'          => esc_html( $bannerPage )
                                                    ));
                                    if( $page_query->have_posts() ) :
                                        while( $page_query->have_posts() ) : $page_query->the_post();
                                            $title = get_the_title();
                                            $bannerTitleLink = get_the_permalink();
                                            $description = get_the_content();
                                            $imageUrl = get_the_post_thumbnail_url();
                                        endwhile;
                                    endif;
                                break;
                }

                include( plugin_dir_path( __FILE__ ) .'/'.$blockLayout.'/'.$blockLayout.'.php' );
            echo '</div>';
        }
    }
endif;