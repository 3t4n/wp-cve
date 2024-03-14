<?php
/**
 * Timeline Element.
 * 
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 * 
 */
class Wpmagazine_Modules_Lite_Timeline_Element extends \Elementor\Widget_Base {

    /**
     * @return - name of the widget
     */
    public function get_name() {
        return 'timeline';
    }

    /**
     * @return - title of the widget
     */
    public function get_title() {
        return esc_html__( 'WP Magazine Timeline', 'wp-magazine-modules-lite' );
    }

    /**
     * @return - icon for the widget
     */
    public function get_icon() {
        return 'cvicon-item cvicon-timeline';
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
    public function cv_get_categories() {
        $categories_lists[''] = esc_html__( 'All', 'wp-magazine-modules-lite' );
        $categories = get_terms( 'category' );
        if( !empty( $categories ) ) {
            foreach( $categories as $category ) {
                $categories_lists[ $category->term_id ] = $category->name;
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
                    'default' => esc_html__( 'Block Title', 'wp-magazine-modules-lite' )
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
                'contentType',
                [
                    'label' => esc_html__( 'Content Type', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'post',
                    'options' => [
                        'post'      => esc_html__( 'Post', 'wp-magazine-modules-lite' ),
                        'custom'  => esc_html__( 'Custom ( pro )', 'wp-magazine-modules-lite' )
                    ]
                ]
            );

            $this->add_control(
                'postCategory',
                [
                    'label' => esc_html__( 'Category', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $this->cv_get_categories(),
                    'condition' => [
                        'contentType'  => 'post'
                    ]
                ]
            );
            
            $this->add_control(
                'postCount',
                [
                    'label' => esc_html__( 'Post Count', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 6,
                    'step' => 1,
                    'default' => 4,
                    'condition' => [
                        'contentType'  => 'post'
                    ]
                ]
            );

            $repeater = new \Elementor\Repeater();
            $repeater->add_control(
                'timeline_image', [
                    'label' => __( 'Image', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::MEDIA
                ]
            );

            $repeater->add_control(
                'timeline_date',
                [
                    'label' => __( 'Date', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::DATE_TIME,
                ]
            );

            $repeater->add_control(
                'timeline_title', [
                    'label' => __( 'Title', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXT
                ]
            );

            $repeater->add_control(
                'timeline_desc', [
                    'label' => __( 'Description', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXTAREA
                ]
            );

            $this->add_control(
                'timelineRepeater',
                [
                    'label' => __( 'Timeline Content', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                        [
                            'timeline_date' => '',
                            'timeline_title' => __( 'Highlight News', 'wp-magazine-modules-lite' ),
                            'timeline_desc' => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', 'wp-magazine-modules-lite' ),
                        ]
                    ],
                    'classes'   => 'cvmm-disable',
                    'title_field' => '{{{ timeline_title }}}',
                ]
            );

        $this->end_controls_section();

        /**************************************************************/
        $this->start_controls_section(
            'query_option_section',
            [
            'label' => esc_html__( 'Query Settings', 'wp-magazine-modules-lite' ),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
            $this->add_control(
                'thumbOption',
                [
                    'label' => esc_html__( 'Show/Hide Thumbnail', 'wp-magazine-modules-lite' ),
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
                'dateOption',
                [
                    'label' => esc_html__( 'Show/Hide Date', 'wp-magazine-modules-lite' ),
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
                'titleOption',
                [
                    'label' => esc_html__( 'Show/Hide Title', 'wp-magazine-modules-lite' ),
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
                'contentOption',
                [
                    'label' => esc_html__( 'Show/Hide Content', 'wp-magazine-modules-lite' ),
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
                        'label' => esc_url( WPMAGAZINE_MODULES_LITE_INCLUDES_URL . '/assets/images/timeline-layout-default.png' ),
                    ],
                    [
                        'value' => 'layout-one',
                        'label' => esc_url( WPMAGAZINE_MODULES_LITE_INCLUDES_URL . '/assets/images/timeline-layout-one.png' ),
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
                    '{{WRAPPER}} .cvmm-timeline-title a:hover' => 'color: {{VALUE}}',
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

                //Date Typo Tab
                $this->start_controls_tab(
                    'date_typo_tab',
                    [
                        'label' => esc_html__( 'Date', 'wp-magazine-modules-lite' ),
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_control(
                    'dateTextAlign',
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
                            '{{WRAPPER}} .cvmm-timeline-date' => 'text-align: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_group_control(
                    \Elementor\Group_Control_Typography::get_type(),
                    [
                        'name' => 'date_typography',
                        'label' => esc_html__( 'Date', 'wp-magazine-modules-lite' ),
                        'selector' => '{{WRAPPER}} .cvmm-timeline-date',
                        'exclude' => [ 'letter_spacing', 'font_family' ],
                        'fields_options'   => [
                            'font_weight' => [ 'default' => 400 ],
                            'font_style' => [ 'default' => 'normal' ],
                            'font_size' => [ 'default' => [ 'unit' => 'px','size' => 14 ] ],
                            'text_transform' => [ 'default' => 'capitalize' ],
                            'text_decoration' => [ 'default' => 'none' ],
                            'line_height' => [ 'default' => [ 'unit' => 'em','size' => 1.2 ] ],
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_control(
                    'date_family',
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
                            '{{WRAPPER}} .cvmm-timeline-date' => 'font-family: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_control(
                    'dateFontColor',
                    [
                        'label' => esc_html__( 'Font Color', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'default' => '#333333',
                        'selectors' => [
                            '{{WRAPPER}} .cvmm-timeline-date' => 'color: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_control(
                    'dateHoverColor',
                    [
                        'label' => esc_html__( 'Hover Color', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'default' => '#f47e00',
                        'selectors' => [
                            '{{WRAPPER}} .cvmm-timeline-date' => 'color: {{VALUE}}',
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
                            '{{WRAPPER}} .cvmm-timeline-title' => 'text-align: {{VALUE}}',
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
                        'selector' => '{{WRAPPER}} .cvmm-timeline-title',
                        'exclude' => [ 'letter_spacing', 'font_family' ],
                        'fields_options'   => [
                            'font_weight' => [ 'default' => 400 ],
                            'font_style' => [ 'default' => 'normal' ],
                            'font_size' => [ 'default' => [ 'unit' => 'px','size' => 15 ] ],
                            'text_transform' => [ 'default' => 'none' ],
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
                            '{{WRAPPER}} .cvmm-timeline-title' => 'font-family: {{VALUE}}',
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
                        'default' => '#3b3b3b',
                        'selectors' => [
                            '{{WRAPPER}} .cvmm-timeline-title a' => 'color: {{VALUE}}',
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
                    'descriptionTextAlign',
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
                            '{{WRAPPER}} .cvmm-timeline-desc p' => 'text-align: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_group_control(
                    \Elementor\Group_Control_Typography::get_type(),
                    [
                        'name' => 'description_typography',
                        'label' => esc_html__( 'Description', 'wp-magazine-modules-lite' ),
                        'selector' => '{{WRAPPER}} .cvmm-timeline-desc p',
                        'exclude' => [ 'letter_spacing', 'font_family' ],
                        'fields_options'   => [
                            'font_weight' => [ 'default' => 400 ],
                            'font_style' => [ 'default' => 'normal' ],
                            'font_size' => [ 'default' => [ 'unit' => 'px','size' => 15 ] ],
                            'text_transform' => [ 'default' => 'none' ],
                            'text_decoration' => [ 'default' => 'none' ],
                            'line_height' => [ 'default' => [ 'unit' => 'em','size' => 1.2 ] ],
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_control(
                    'description_family',
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
                            '{{WRAPPER}} .cvmm-timeline-desc p' => 'font-family: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_control(
                    'descriptionFontColor',
                    [
                        'label' => esc_html__( 'Font Color', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'default' => '#3b3b3b',
                        'selectors' => [
                            '{{WRAPPER}} .cvmm-timeline-desc p' => 'color: {{VALUE}}',
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
        $contentType = 'post';
        if( $contentType == 'custom' ) {
            foreach( $timelineRepeater as $key => $timeline ) {
                $timelineRepeater[$key]['timeline_image'] = $timeline['timeline_image']['url'];
            }
        }
    ?>
        <div id="wpmagazine-modules-lite-timeline-block-<?php echo esc_attr( $element_id ); ?>" class="wpmagazine-modules-lite-timeline-block block-<?php echo esc_attr( $element_id ); ?> cvmm-block cvmm-block-timeline--<?php echo esc_html( $blockLayout ); ?>">
            <?php
                if( !empty( $blockTitle ) ) {
                    echo '<h2 class="cvmm-block-title layout--'.esc_html( $blockTitleLayout ).'"><span>'.esc_html( $blockTitle ).'</span></h2>';
                }

            include( plugin_dir_path( __FILE__ ) . esc_html( $blockLayout ).'/'.$blockLayout.'.php' );
        ?>
        </div><!-- #wpmagazine-modules-lite-banner-block -->
    <?php
    }
}