<?php
/**
 * Post Filter Element.
 * 
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 * 
 */
class Wpmagazine_Modules_Lite_Post_Filter_Element extends \Elementor\Widget_Base {

    /**
     * @return - name of the widget
     */
    public function get_name() {
        return 'post-filter';
    }

    /**
     * @return - title of the widget
     */
    public function get_title() {
        return esc_html__( 'WP Magazine Post Filter', 'wp-magazine-modules-lite' );
    }

    /**
     * @return - icon for the widget
     */
    public function get_icon() {
        return 'cvicon-item cvicon-filter';
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

        $posttype = 'post';

        $this->add_control(
            'postCategory',
            [
                'label' => esc_html__( 'Post Categories', 'wp-magazine-modules-lite' ),
                'type' => 'MULTICHECKBOX',
                'options' => $this->cv_get_categories( $posttype ),
            ]
        );

        $this->add_control(
            'buttonOption',
            [
                'label' => esc_html__( 'Show read more button', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'return_value' => 'show',
                'default' => 'hide',
            ]
        );

        $this->add_control(
            'buttonLabel',
            [
                'label' => esc_html__( 'Button Label', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Add label here...', 'wp-magazine-modules-lite' ),
                'default'   => esc_html__( 'Read more', 'wp-magazine-modules-lite' ),
                'condition' => [
                    'buttonOption' => 'show'
                ],
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
            'postCount',
            [
                'label' => esc_html__( 'Post Count', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 4,
                'step' => 1,
                'default' => 4
            ]
        );

        $this->add_control(
            'orderBy',
            [
                'label' => esc_html__( 'Order By', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date'   => esc_html__( 'Date', 'wp-magazine-modules-lite' ),
                    'title'  => esc_html__( 'Title', 'wp-magazine-modules-lite' ),
                ]
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => esc_html__( 'Order', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => [
                    'asc'   => esc_html__( 'Ascending', 'wp-magazine-modules-lite' ),
                    'desc'   => esc_html__( 'Descending', 'wp-magazine-modules-lite' )
                ]
            ]
        );

        $this->add_control(
            'thumbOption',
            [
                'label' => esc_html__( 'Show thumbnail', 'wp-magazine-modules-lite' ),
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
            'dateOption',
            [
                'label' => esc_html__( 'Show date', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'return_value' => 'show',
                'default' => 'hide',
            ]
        );
        
        $this->add_control(
            'authorOption',
            [
                'label' => esc_html__( 'Show author', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'return_value' => 'show',
                'default' => 'hide',
            ]
        );
        
        $this->add_control(
            'categoryOption',
            [
                'label' => esc_html__( 'Show categories', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'return_value' => 'show',
                'default' => 'hide',
            ]
        );

        $this->add_control(
            'categoriesCount',
            [
                'label' => esc_html__( 'Categories Count', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 500,
                'step' => 1,
                'default' => 2,
                'classes'   => 'cvmm-disable',
                'condition' => [
                    'categoryOption' => 'show'
                ],
            ]
        );

        $this->add_control(
            'tagsOption',
            [
                'label' => esc_html__( 'Show tags', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'return_value' => 'show',
                'default' => 'hide',
            ]
        );
        
        $this->add_control(
            'tagsCount',
            [
                'label' => esc_html__( 'Tags Count', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 500,
                'step' => 1,
                'default' => 2,
                'classes'   => 'cvmm-disable',
                'condition' => [
                    'tagsOption' => 'show'
                ],
            ]
        );
        
        $this->add_control(
            'commentOption',
            [
                'label' => esc_html__( 'Show comments number', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'return_value' => 'show',
                'default' => 'hide',
            ]
        );

        $this->add_control(
            'contentOption',
            [
                'label' => esc_html__( 'Show content', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'return_value' => 'show',
                'default' => 'hide',
            ]
        );
         
        $this->add_control(
            'contentType',
            [
                'label' => esc_html__( 'Content Type', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'excerpt',
                'options' => [
                    'excerpt'   => esc_html__( 'Excerpt', 'wp-magazine-modules-lite' ),
                    'content'   => esc_html__( 'Content', 'wp-magazine-modules-lite' )
                ],
                'classes'   => 'cvmm-disable',
                'condition' => [
                    'contentOption' => 'show'
                ],
            ]
        );

        $this->add_control(
            'wordCount',
            [
                'label' => esc_html__( 'Content Length', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 500,
                'step' => 1,
                'default' => 15,
                'classes'   => 'cvmm-disable',
                'condition' => [
                    'contentOption' => 'show'
                ],
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
                        'label' => esc_url( WPMAGAZINE_MODULES_LITE_INCLUDES_URL . '/assets/images/filter-layout-default.png' ),
                    ],
                    [
                        'value' => 'layout-one',
                        'label' => esc_url( WPMAGAZINE_MODULES_LITE_INCLUDES_URL . '/assets/images/filter-layout-one.png' ),
                    ]
                ],
            ]
        );
        
        $this->add_control(
            'postFormatIcon',
            [
                'label' => esc_html__( 'Show post format icon', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'return_value' => 'show',
                'default' => 'hide',
            ]
        );
 
        $this->add_control(
            'postMetaIcon',
            [
                'label' => esc_html__( 'Show post meta before icon', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'return_value' => 'show',
                'default' => 'hide',
            ]
        );

        $this->add_control(
            'postButtonIcon',
            [
                'label' => esc_html__( 'Show read more button icon', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                'return_value' => 'show',
                'default' => 'hide',
            ]
        );

        $this->add_control(
            'blockColumn',
            [
                'label' => esc_html__( 'Block Column', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'three',
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
                    '{{WRAPPER}} .cvmm-post.cvmm-icon .cvmm-post-thumb::after' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .cvmm-block-title.layout--one span, {{WRAPPER}} .cvmm-block-post-filter--layout-default .cvmm-term-titles-wrap li.active, {{WRAPPER}} .cvmm-block-post-filter--layout-default .cvmm-term-titles-wrap li:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .cvmm-block-post-filter--layout-one .cvmm-term-titles-wrap li.active, {{WRAPPER}} .cvmm-block-post-filter--layout-one .cvmm-term-titles-wrap li:hover,{{WRAPPER}} .cvmm-block-title.layout--two span' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .cvmm-post-title a:hover, {{WRAPPER}} .cvmm-read-more a:hover,{{WRAPPER}}  .cvmm-post-meta .cvmm-post-meta-item:hover>a, {{WRAPPER}} .cvmm-post-meta a:hover, {{WRAPPER}} .cvmm-post-meta .cvmm-post-meta-item:hover:before, {{WRAPPER}} .cvmm-view-more a:hover' => 'color: {{VALUE}}',
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
                            '{{WRAPPER}} .cvmm-post-title' => 'text-align: {{VALUE}}',
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
                        'selector' => '{{WRAPPER}} .cvmm-post-title a',
                        'exclude' => [ 'letter_spacing', 'font_size', 'font_family' ],
                        'fields_options'   => [
                            'font_weight' => [ 'default' => 700 ],
                            'font_style' => [ 'default' => 'normal' ],
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
                            '{{WRAPPER}} .cvmm-post-title a' => 'font-family: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_control(
                    'titleFontSize',
                    [
                        'label' => esc_html__( 'Font Size', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::NUMBER,
                        'min' => 1,
                        'max' => 200,
                        'step' => 1,
                        'default' => 28,
                        'selectors' => [
                            '{{WRAPPER}} .cvmm-post-block-trailing-post-wrap .cvmm-post-title a' => 'font-size: {{VALUE}}px',
                            '{{WRAPPER}} .cvmm-block-post-filter--layout-default .cvmm-post-title a' => 'font-size: {{VALUE}}px',
                            '{{WRAPPER}} .cvmm-block-post-filter--layout-two .cvmm-post-title a' => 'font-size: {{VALUE}}px',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_control(
                    'mainblocktitleFontSize',
                    [
                        'label' => esc_html__( 'Main Block Font Size', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::NUMBER,
                        'min' => 1,
                        'max' => 200,
                        'step' => 1,
                        'default' => 30,
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 'blockLayout',
                                    'operator' => '!in',
                                    'value' => [
                                        'layout-two',
                                    ],
                                ],
                                [
                                    'name' => 'blockLayout',
                                    'operator' => '!in',
                                    'value' => [
                                        'layout-default',
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
                        'selectors' => [
                            '{{WRAPPER}} .cvmm-post-block-main-post-wrap .cvmm-post-title a' => 'font-size: {{VALUE}}px',
                        ],
                    ]
                );

                $this->add_control(
                    'titleFontColor',
                    [
                        'label' => esc_html__( 'Font Color', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'default' => '#333333',
                        'selectors' => [
                            '{{WRAPPER}} .cvmm-post-title a' => 'color: {{VALUE}}',
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
                            '{{WRAPPER}} .cvmm-post-title a:hover' => 'color: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->end_controls_tab();

                //Meta Typo Tab
                $this->start_controls_tab(
                    'meta_typo_tab',
                    [
                        'label' => esc_html__( 'Meta', 'wp-magazine-modules-lite' ),
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );
                
                $this->add_control(
                    'metaTextAlign',
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
                            '{{WRAPPER}} .cvmm-post-meta' => 'text-align: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_group_control(
                    \Elementor\Group_Control_Typography::get_type(),
                    [
                        'name' => 'meta_typography',
                        'label' => esc_html__( 'Meta', 'wp-magazine-modules-lite' ),
                        'selector' => '{{WRAPPER}} .cvmm-post-meta .cvmm-post-meta-item',
                        'exclude' => [ 'letter_spacing', 'font_family' ],
                        'fields_options'   => [
                            'font_weight' => [ 'default' => 400 ],
                            'font_style' => [ 'default' => 'normal' ],
                            'font_size' => [ 'default' => [ 'unit' => 'px','size' => 14 ] ],
                            'text_transform' => [ 'default' => 'capitalize' ],
                            'text_decoration' => [ 'default' => 'none' ],
                            'line_height' => [ 'default' => [ 'unit' => 'em','size' => 1.8 ] ],
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_control(
                    'meta_family',
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
                            '{{WRAPPER}} .cvmm-post-meta .cvmm-post-meta-item' => 'font-family: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_control(
                    'metaFontColor',
                    [
                        'label' => esc_html__( 'Font Color', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'default' => '#434343',
                        'selectors' => [
                            '{{WRAPPER}} .cvmm-post-meta .cvmm-post-meta-item' => 'color: {{VALUE}}',
                            '{{WRAPPER}} .cvmm-post-meta .cvmm-post-meta-item a' => 'color: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_control(
                    'metaHoverColor',
                    [
                        'label' => esc_html__( 'Hover Color', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'default' => '#f47e00',
                        'selectors' => [
                            '{{WRAPPER}} .cvmm-post-meta .cvmm-post-meta-item:hover' => 'color: {{VALUE}}',
                            '{{WRAPPER}} .cvmm-post-meta .cvmm-post-meta-item a:hover' => 'color: {{VALUE}}',
                            '{{WRAPPER}} .cvmm-post-meta .cvmm-post-meta-item:hover:before' => 'color: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->end_controls_tab();

                //Content Typo Tab
                $this->start_controls_tab(
                    'content_typo_tab',
                    [
                        'label' => esc_html__( 'Content', 'wp-magazine-modules-lite' ),
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
                            '{{WRAPPER}} .cvmm-post-content' => 'text-align: {{VALUE}}',
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
                        'selector' => '{{WRAPPER}} .cvmm-post-content',
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
                            '{{WRAPPER}} .cvmm-post-content' => 'font-family: {{VALUE}}',
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
                            '{{WRAPPER}} .cvmm-post-content' => 'color: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->end_controls_tab();

                //Button Typo Tab
                $this->start_controls_tab(
                    'button_typo_tab',
                    [
                        'label' => esc_html__( 'Button', 'wp-magazine-modules-lite' ),
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );
                
                $this->add_control(
                    'buttonTextAlign',
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
                            '{{WRAPPER}} .cvmm-read-more' => 'text-align: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_group_control(
                    \Elementor\Group_Control_Typography::get_type(),
                    [
                        'name' => 'button_typography',
                        'label' => esc_html__( 'Button', 'wp-magazine-modules-lite' ),
                        'selector' => '{{WRAPPER}} .cvmm-read-more a',
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
                    'button_family',
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
                            '{{WRAPPER}} .cvmm-read-more a' => 'font-family: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_control(
                    'buttonFontColor',
                    [
                        'label' => esc_html__( 'Font Color', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'default' => '#3b3b3b',
                        'selectors' => [
                            '{{WRAPPER}} .cvmm-read-more a' => 'color: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_control(
                    'buttonHoverColor',
                    [
                        'label' => esc_html__( 'Hover Color', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'default' => '#3b3b3b',
                        'selectors' => [
                            '{{WRAPPER}} .cvmm-read-more a:hover' => 'color: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_control(
                    'buttonBackgroundColor',
                    [
                        'label' => esc_html__( 'Background Color', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'default' => 'transparent',
                        'selectors' => [
                            '{{WRAPPER}} .cvmm-read-more a' => 'background-color: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_control(
                    'buttonBackgroundHoverColor',
                    [
                        'label' => esc_html__( 'Background Hover Color', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'default' => '#f47e00',
                        'selectors' => [
                            '{{WRAPPER}} .cvmm-read-more a:hover' => 'background-color: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_control(
                    'buttonBorderType',
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
                            '{{WRAPPER}} .cvmm-read-more a' => 'border-style: {{VALUE}}',
                        ],
                        'condition' => [
                            'typographyOption!' => 'show'
                        ]
                    ]
                );

                $this->add_control(
                    'buttonBorderWeight',
                    [
                        'label' => esc_html__( 'Border Weight', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::NUMBER,
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                        'default' => 1,
                        'selectors' => [
                            '{{WRAPPER}} .cvmm-read-more a' => 'border-width: {{VALUE}}px',
                        ],
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 'buttonBorderType',
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
                    'buttonBorderColor',
                    [
                        'label' => esc_html__( 'Border Color', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'default' => 'transparent',
                        'selectors' => [
                            '{{WRAPPER}} .cvmm-read-more a' => 'border-color: {{VALUE}}',
                        ],
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 'buttonBorderType',
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
                    'buttonBorderHoverColor',
                    [
                        'label' => esc_html__( 'Border Hover Color', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'default' => '#f47e00',
                        'selectors' => [
                            '{{WRAPPER}} .cvmm-read-more a:hover' => 'border-color: {{VALUE}}',
                        ],
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 'buttonBorderType',
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
                    'buttonPadding',
                    [
                        'label' => esc_html__( 'Padding', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'default'   => [
                            'top' => '5',
                            'right' => '0',
                            'bottom' => '5',
                            'left' => '0',
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .cvmm-read-more a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        $buttonOption       = ( $buttonOption === 'show' );
        $contentOption      = ( $contentOption === 'show' );
        $thumbOption        = ( $thumbOption === 'show' );
        $titleOption        = ( $titleOption === 'show' );
        $dateOption         = ( $dateOption === 'show' );
        $authorOption       = ( $authorOption === 'show' );
        $categoryOption     = ( $categoryOption === 'show' );
        $tagsOption         = ( $tagsOption === 'show' );
        $commentOption      = ( $commentOption === 'show' );
        $postFormatIcon     = ( $postFormatIcon === 'show' );
        $postMetaIcon       = ( $postMetaIcon === 'show' );
        $postButtonIcon     = ( $postButtonIcon === 'show' );
        $postMargin         = ( $postMargin === 'show' );
        $fallbackImage      = $fallbackImage['url'];
        $posttype = 'post';
        if( empty( $fallbackImage ) ) {
            unset( $fallbackImage );
        }
        $attributes = $settings;
        echo '<div id="wpmagazine-modules-lite-post-filter-block-'.esc_html( $element_id ).'" class="wpmagazine-modules-lite-post-filter-block block-'.esc_html( $element_id ).' cvmm-block cvmm-block-post-filter--'.esc_html( $blockLayout ).'">';
            include( plugin_dir_path( __FILE__ ) .'/'.$blockLayout.'/'.$blockLayout.'.php' );
        echo '</div><!-- #wpmagazine-modules-lite-post-filter-block -->';
    }
}