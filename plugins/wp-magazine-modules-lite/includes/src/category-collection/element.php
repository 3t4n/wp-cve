<?php
/**
 * Category Collection Element.
 * 
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 * 
 */
class Wpmagazine_Modules_Lite_Category_Collection_Element extends \Elementor\Widget_Base {

    /**
     * @return - name of the widget
     */
    public function get_name() {
        return 'category-collection';
    }

    /**
     * @return - title of the widget
     */
    public function get_title() {
        return esc_html__( 'WP Magazine Category Collection', 'wp-magazine-modules-lite' );
    }

    /**
     * @return - icon for the widget
     */
    public function get_icon() {
        return 'cvicon-item cvicon-category';
    }

    /**
     * @return - category name for the widget
     */
    public function get_categories() {
        return [ 'wpmagazine-modules-lite' ];
    }

    /**
     * Get List of categories
     */
    public function cv_get_categories( $posttype ) {
        $taxonomies = get_taxonomies( array( 'object_type' => array( $posttype ) ) );
        if( !empty( $taxonomies ) ) {
            foreach( $taxonomies as $taxonomy ) {
                $taxonomy_name = $taxonomy;
                break;
            }
            $categories = get_terms( $taxonomy_name );
            if( !empty( $categories ) ) {
                foreach( $categories as $category ) {
                    $categories_lists[ $category->term_id ] = esc_html( $category->name ). ' ('.absint( $category->count ). ')';
                }
            }
        }
        return $categories_lists;
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
                    'one'   => esc_html__( 'One', 'wp-magazine-modules-lite' ),
                    'two'   => esc_html__( 'Two', 'wp-magazine-modules-lite' )
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
            'blockCategories',
            [
                'label' => esc_html__( 'Post Categories', 'wp-magazine-modules-lite' ),
                'type' => 'MULTICHECKBOX',
                'options' => $this->cv_get_categories( 'post' ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'query_setting_section',
            [
                'label' => esc_html__( 'Query Setting', 'wp-magazine-modules-lite' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'titleOption',
            [
                'label' => esc_html__( 'Show category title', 'wp-magazine-modules-lite' ),
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
            'descOption',
            [
                'label' => esc_html__( 'Show description', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'return_value' => 'show',
                'classes'   => 'cvmm-disable',
                'default' => 'hide',
            ]
        );
        
        $this->add_control(
            'catcountOption',
            [
                'label' => esc_html__( 'Show category count', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'return_value' => 'show',
                'default' => 'show',
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
        /****************************************************************/

        $this->start_controls_section(
            'fallback_image_section',
            [
                'label' => esc_html__( 'Fallback Image', 'wp-magazine-modules-lite' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_control(
                'fallbackImage',
                [
                    'label' => esc_html__( 'Choose Image', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'default' => [
                        'url' => '',
                    ],
                ]
            );

        $this->end_controls_section();
        /**************************************************/

        $this->start_controls_section(
            'style_section',
            [
            'label' => esc_html__( 'Layout Setting', 'wp-magazine-modules-lite' ),
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
                        'label' => esc_url( WPMAGAZINE_MODULES_LITE_INCLUDES_URL . '/assets/images/category-collection-layout-default.png' ),
                    ],
                    [
                        'value' => 'layout-one',
                        'label' => esc_url( WPMAGAZINE_MODULES_LITE_INCLUDES_URL . '/assets/images/category-collection-layout-one.png' ),
                    ]
                ],
            ]
        );
        
        $this->add_control(
            'blockColumn',
            [
                'label' => esc_html__( 'Block Column', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'four',
                'options' => [
                    'one'  => esc_html__( 'One', 'wp-magazine-modules-lite' ),
                    'two'  => esc_html__( 'Two', 'wp-magazine-modules-lite' ),
                    'three' => esc_html__( 'Three', 'wp-magazine-modules-lite' ),
                    'four'  => esc_html__( 'Four', 'wp-magazine-modules-lite' ),
                    'five'  => esc_html__( 'Five', 'wp-magazine-modules-lite' )
                ],
            ]
        );
        
        $this->add_control(
            'postMargin',
            [
                'label' => esc_html__( 'Allow margin between each post', 'wp-magazine-modules-lite' ),
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
            'image-settings-popover-toggle',
            [
                'label' => __( 'Image Settings', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __( 'Default', 'wp-magazine-modules-lite' ),
                'label_on' => __( 'Custom', 'wp-magazine-modules-lite' ),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();
            $this->add_control(
                'imageSize',
                [
                    'label' => esc_html__( 'Image Size', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'full',
                    'options' => [
                        'full'  => esc_html__( 'Full', 'wp-magazine-modules-lite' ),
                        'cvmm-large'  => esc_html__( 'Large', 'wp-magazine-modules-lite' ),
                        'cvmm-medium-plus' => esc_html__( 'Medium Plus', 'wp-magazine-modules-lite' ),
                        'cvmm-medium-square' => esc_html__( 'Medium Square', 'wp-magazine-modules-lite' ),
                        'cvmm-portrait' => esc_html__( 'Portrait', 'wp-magazine-modules-lite' ),
                        'cvmm-medium' => esc_html__( 'Medium', 'wp-magazine-modules-lite' ),
                        'cvmm-small' => esc_html__( 'Small', 'wp-magazine-modules-lite' ),
                        'thumbnail' => esc_html__( 'Thumbnail', 'wp-magazine-modules-lite' )
                    ],
                ]
            );
        $this->end_popover();

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
                    '.cvmm-post.cvmm-icon .cvmm-post-thumb::after' => 'background: {{VALUE}}',
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
                    '{{WRAPPER}} .cvmm-cat-title a:hover, {{WRAPPER}} .cvmm-read-more a:hover,{{WRAPPER}}  .cvmm-post-meta .cvmm-post-meta-item:hover>a, {{WRAPPER}} .cvmm-post-meta a:hover, {{WRAPPER}} .cvmm-post-meta .cvmm-post-meta-item:hover:before, {{WRAPPER}} .cvmm-view-more a:hover' => 'color: {{VALUE}}',
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
                            '{{WRAPPER}} .cvmm-cat-title' => 'text-align: {{VALUE}}',
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
                        'selector' => '{{WRAPPER}} .cvmm-cat-title a',
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
                            '{{WRAPPER}} .cvmm-cat-title a' => 'font-family: {{VALUE}}',
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
                            '{{WRAPPER}} .cvmm-cat-title a' => 'color: {{VALUE}}',
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
                            '{{WRAPPER}} .cvmm-cat-title a:hover' => 'color: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->end_controls_tab();

                //Content Typo Tab
                $this->start_controls_tab(
                    'desc_typo_tab',
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
                            '{{WRAPPER}} .cvmm-cat-content' => 'text-align: {{VALUE}}',
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
                        'selector' => '{{WRAPPER}} .cvmm-cat-content',
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
                            '{{WRAPPER}} .cvmm-cat-content' => 'font-family: {{VALUE}}',
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
                            '{{WRAPPER}} .cvmm-cat-content' => 'color: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->end_controls_tab();
            $this->end_controls_tab();
            /*** Start typography tabs ***/

        $this->end_controls_section();
    }
    
    /**
     * renders the widget content.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        extract( $settings );
        $element_id = $this->get_id();
        $titleOption  = ( $titleOption === 'show' );
        $descOption         = ( $descOption === 'show' );
        $catcountOption     = ( $catcountOption === 'show' );
        $postMargin         = ( $postMargin === 'show' );
        $fallbackImage      = $fallbackImage['url'];
        if( empty( $fallbackImage ) ) {
            unset( $fallbackImage );
        }

        echo '<div id="wpmagazine-modules-lite-category-collection-block-'.esc_html( $element_id ).'" class="wpmagazine-modules-lite-category-collection-block block-'.esc_html( $element_id ).' cvmm-block cvmm-block-category-collection--'.esc_html( $blockLayout ).'">';
            if( !empty( $blockTitle ) ) {
                echo '<h2 class="cvmm-block-title layout--'.esc_html( $blockTitleLayout ).'"><span>'.esc_html( $blockTitle ).'</span></h2>';
            }
            include( plugin_dir_path( __FILE__ ) .'/'.$blockLayout.'/'.$blockLayout.'.php' );
        echo '</div><!-- #wpmagazine-modules-lite-category-collection-block -->';
    }
}