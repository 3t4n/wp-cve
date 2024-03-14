<?php
/**
 * Post Tiles Element.
 * 
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 * 
 */
class Wpmagazine_Modules_Lite_Post_Tiles_Element extends \Elementor\Widget_Base {

    /**
     * @return - name of the widget
     */
    public function get_name() {
        return 'post-tiles';
    }

    /**
     * @return - title of the widget
     */
    public function get_title() {
        return esc_html__( 'WP Magazine Post Tiles', 'wp-magazine-modules-lite' );
    }

    /**
     * @return - icon for the widget
     */
    public function get_icon() {
        return 'cvicon-item cvicon-tiles';
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
            'featuredSectionOption',
            [
                'label' => esc_html__( 'Show featured section', 'wp-magazine-modules-lite' ),
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
                'basic_slider_tab',
                [
                    'label' => __( 'Basic - Slider Settings', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $sliderposttype = 'post';

            $this->add_control(
                'sliderpostCategory',
                [
                    'label' => esc_html__( 'Post Categories', 'wp-magazine-modules-lite' ),
                    'type' => 'MULTICHECKBOX',
                    'options' => $this->cv_get_categories( $sliderposttype ),
                ]
            );
    
            $this->add_control(
                'sliderbuttonOption',
                [
                    'label' => esc_html__( 'Show read more button', 'wp-magazine-modules-lite' ),
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
                'sliderbuttonLabel',
                [
                    'label' => esc_html__( 'Button Label', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Add label here...', 'wp-magazine-modules-lite' ),
                    'default'   => esc_html__( 'Read more', 'wp-magazine-modules-lite' ),
                    'condition' => [
                        'sliderbuttonOption' => 'show'
                    ],
                ]
            );
            
            $this->add_control(
                'basic_featured_tab',
                [
                    'label' => __( 'Basic - Featured Settings', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
    
            $featuredposttype = 'post';

            $this->add_control(
                'featuredpostCategory',
                [
                    'label' => esc_html__( 'Post Categories', 'wp-magazine-modules-lite' ),
                    'type' => 'MULTICHECKBOX',
                    'options' => $this->cv_get_categories( $featuredposttype ),
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
                'query_slider_tab',
                [
                    'label' => __( 'Query - Slider Settings', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'sliderpostCount',
                [
                    'label' => esc_html__( 'Post Count', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 10
                ]
            );
    
            $this->add_control(
                'sliderorderBy',
                [
                    'label' => esc_html__( 'Order By', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'date',
                    'options' => [
                        'date'   => esc_html__( 'Date', 'wp-magazine-modules-lite' ),
                        'title'  => esc_html__( 'Title', 'wp-magazine-modules-lite' )
                    ]
                ]
            );
    
            $this->add_control(
                'sliderorder',
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
                'sliderdateOption',
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
                'sliderauthorOption',
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
                'slidercategoryOption',
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
                'slidercategoriesCount',
                [
                    'label' => esc_html__( 'Categories Count', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 500,
                    'step' => 1,
                    'default' => 2,
                    'classes'   => 'cvmm-disable',
                    'condition' => [
                        'slidercategoryOption' => 'show'
                    ],
                ]
            );
    
            $this->add_control(
                'slidertagsOption',
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
                'slidertagsCount',
                [
                    'label' => esc_html__( 'Tags Count', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 500,
                    'step' => 1,
                    'default' => 2,
                    'classes'   => 'cvmm-disable',
                    'condition' => [
                        'slidertagsOption' => 'show'
                    ],
                ]
            );
            
            $this->add_control(
                'slidercommentOption',
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
                'slidercontentOption',
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
                'slidercontentType',
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
                        'slidercontentOption' => 'show'
                    ],
                ]
            );
    
            $this->add_control(
                'sliderwordCount',
                [
                    'label' => esc_html__( 'Content Length', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 500,
                    'step' => 1,
                    'default' => 10,
                    'classes'   => 'cvmm-disable',
                    'condition' => [
                        'slidercontentOption' => 'show'
                    ],
                ]
            );
            $this->add_control(
                'query_featured_tab',
                [
                    'label' => __( 'Query - Featured Settings', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
    
            $this->add_control(
                'featuredorderBy',
                [
                    'label' => esc_html__( 'Order By', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'date',
                    'options' => [
                        'date'   => esc_html__( 'Date', 'wp-magazine-modules-lite' ),
                        'title'  => esc_html__( 'Title', 'wp-magazine-modules-lite' )
                    ]
                ]
            );
    
            $this->add_control(
                'featuredorder',
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
                'featureddateOption',
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
                'featuredauthorOption',
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
                'featuredcategoryOption',
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
                'featuredcategoriesCount',
                [
                    'label' => esc_html__( 'Categories Count', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 500,
                    'step' => 1,
                    'default' => 2,
                    'classes'   => 'cvmm-disable',
                    'condition' => [
                        'featuredcategoryOption' => 'show'
                    ],
                ]
            );
    
            $this->add_control(
                'featuredtagsOption',
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
                'featuredtagsCount',
                [
                    'label' => esc_html__( 'Tags Count', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 500,
                    'step' => 1,
                    'default' => 2,
                    'classes'   => 'cvmm-disable',
                    'condition' => [
                        'featuredtagsOption' => 'show'
                    ],
                ]
            );
            
            $this->add_control(
                'featuredcommentOption',
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
                'featuredcontentOption',
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
                'featuredcontentType',
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
                        'featuredcontentOption' => 'show'
                    ],
                ]
            );
    
            $this->add_control(
                'featuredwordCount',
                [
                    'label' => esc_html__( 'Content Length', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 500,
                    'step' => 1,
                    'default' => 10,
                    'classes'   => 'cvmm-disable',
                    'condition' => [
                        'featuredcontentOption' => 'show'
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
            'carousel_options_section',
            [
                'label' => esc_html__( 'Carousel Options', 'wp-magazine-modules-lite' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_control(
                'carouselType',
                [
                    'label' => esc_html__( 'Enable fade animation', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                    'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                    'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                    'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                    'return_value' => 'show',
                    'default' => 'hide',
                    'classes'   => 'cvmm-disable',
                ]
            );

            $this->add_control(
                'carouselAuto',
                [
                    'label' => esc_html__( 'Enable auto slide', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                    'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                    'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                    'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                    'return_value' => 'show',
                    'default' => 'show',
                    'classes'   => 'cvmm-disable',
                ]
            );

            $this->add_control(
                'carouselDots',
                [
                    'label' => esc_html__( 'Show dots', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                    'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                    'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                    'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                    'return_value' => 'show',
                    'default' => 'hide',
                    'classes'   => 'cvmm-disable',
                ]
            );

            $this->add_control(
                'carouselControls',
                [
                    'label' => esc_html__( 'Show control buttons', 'wp-magazine-modules-lite' ),
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
                'carouselLoop',
                [
                    'label' => esc_html__( 'Enable items loop', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                    'label_off' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                    'show' => esc_html__( 'Show', 'wp-magazine-modules-lite' ),
                    'hide' => esc_html__( 'Hide', 'wp-magazine-modules-lite' ),
                    'return_value' => 'show',
                    'default' => 'show',
                    'classes'   => 'cvmm-disable',
                ]
            );

            $this->add_control(
                'carouselSpeed',
                [
                    'label' => esc_html__( 'Speed', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 200,
                    'max' => 3000,
                    'step' => 100,
                    'default' => 2500,
                    'classes'   => 'cvmm-disable',
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
                        'label' => esc_url( WPMAGAZINE_MODULES_LITE_INCLUDES_URL . '/assets/images/tiles-layout-default.png' ),
                    ],
                    [
                        'value' => 'layout-one',
                        'label' => esc_url( WPMAGAZINE_MODULES_LITE_INCLUDES_URL . '/assets/images/tiles-layout-one.png' ),
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
                'default' => 'show',
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
                'default' => 'show',
            ]
        );

        $this->add_control(
            'slider-image-popover-toggle',
            [
                'label' => __( 'Slider Image Settings', 'wp-magazine-modules-lite' ),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __( 'Default', 'wp-magazine-modules-lite' ),
                'label_on' => __( 'Custom', 'wp-magazine-modules-lite' ),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();
            $this->add_control(
                'sliderImageSize',
                [
                    'label' => esc_html__( 'Size', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'cover',
                    'options' => [
                        'cover'  => esc_html__( 'Cover', 'wp-magazine-modules-lite' ),
                        'contain'  => esc_html__( 'Contain', 'wp-magazine-modules-lite' ),
                        'auto' => esc_html__( 'Auto', 'wp-magazine-modules-lite' )
                    ],
                ]
            );

            $this->add_control(
                'sliderImagePosition',
                [
                    'label' => esc_html__( 'Position', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'center center',
                    'options' => [
                        'left top'  => esc_html__( 'left top', 'wp-magazine-modules-lite' ),
                        'center top'  => esc_html__( 'center top', 'wp-magazine-modules-lite' ),
                        'right top' => esc_html__( 'right top', 'wp-magazine-modules-lite' ),
                        'center right' => esc_html__( 'center right', 'wp-magazine-modules-lite' ),
                        'center center' => esc_html__( 'center center', 'wp-magazine-modules-lite' ),
                        'center left' => esc_html__( 'center left', 'wp-magazine-modules-lite' ),
                        'left bottom' => esc_html__( 'left bottom', 'wp-magazine-modules-lite' ),
                        'center bottom' => esc_html__( 'center bottom', 'wp-magazine-modules-lite' ),
                        'right bottom' => esc_html__( 'right bottom', 'wp-magazine-modules-lite' )
                    ],
                ]
            );

            $this->add_control(
                'sliderImageDimension',
                [
                    'label' => esc_html__( 'Position', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'center center',
                    'options' => [
                        'left top'  => esc_html__( 'left top', 'wp-magazine-modules-lite' ),
                        'center top'  => esc_html__( 'center top', 'wp-magazine-modules-lite' ),
                        'right top' => esc_html__( 'right top', 'wp-magazine-modules-lite' ),
                        'center right' => esc_html__( 'center right', 'wp-magazine-modules-lite' ),
                        'center center' => esc_html__( 'center center', 'wp-magazine-modules-lite' ),
                        'center left' => esc_html__( 'center left', 'wp-magazine-modules-lite' ),
                        'left bottom' => esc_html__( 'left bottom', 'wp-magazine-modules-lite' ),
                        'center bottom' => esc_html__( 'center bottom', 'wp-magazine-modules-lite' ),
                        'right bottom' => esc_html__( 'right bottom', 'wp-magazine-modules-lite' )
                    ],
                ]
            );
            
            $this->end_popover();
            

            $this->add_control(
                'featured-image-popover-toggle',
                [
                    'label' => __( 'Featured Image Settings', 'wp-magazine-modules-lite' ),
                    'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                    'label_off' => __( 'Default', 'wp-magazine-modules-lite' ),
                    'label_on' => __( 'Custom', 'wp-magazine-modules-lite' ),
                    'return_value' => 'yes',
                ]
            );
    
            $this->start_popover();

                $this->add_control(
                    'featuredImageSize',
                    [
                        'label' => esc_html__( 'Size', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => 'cover',
                        'options' => [
                            'cover'  => esc_html__( 'Cover', 'wp-magazine-modules-lite' ),
                            'contain'  => esc_html__( 'Contain', 'wp-magazine-modules-lite' ),
                            'auto' => esc_html__( 'Auto', 'wp-magazine-modules-lite' )
                        ],
                    ]
                );
        
                $this->add_control(
                    'featuredImagePosition',
                    [
                        'label' => esc_html__( 'Position', 'wp-magazine-modules-lite' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => 'center center',
                        'options' => [
                            'left top'  => esc_html__( 'left top', 'wp-magazine-modules-lite' ),
                            'center top'  => esc_html__( 'center top', 'wp-magazine-modules-lite' ),
                            'right top' => esc_html__( 'right top', 'wp-magazine-modules-lite' ),
                            'center right' => esc_html__( 'center right', 'wp-magazine-modules-lite' ),
                            'center center' => esc_html__( 'center center', 'wp-magazine-modules-lite' ),
                            'center left' => esc_html__( 'center left', 'wp-magazine-modules-lite' ),
                            'left bottom' => esc_html__( 'left bottom', 'wp-magazine-modules-lite' ),
                            'center bottom' => esc_html__( 'center bottom', 'wp-magazine-modules-lite' ),
                            'right bottom' => esc_html__( 'right bottom', 'wp-magazine-modules-lite' )
                        ],
                    ]
                );
                
                $this->add_control(
                    'featuredImageDimension',
                    [
                        'label' => esc_html__( 'Dimension', 'wp-magazine-modules-lite' ),
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
                    '{{WRAPPER}} .cvmm-post-tiles-block-main-content-wrap .cvmm-post-title a:hover, {{WRAPPER}} .cvmm-read-more a:hover,{{WRAPPER}}  .cvmm-post-meta .cvmm-post-meta-item:hover>a, {{WRAPPER}} .cvmm-post-meta a:hover, {{WRAPPER}} .cvmm-post-meta .cvmm-post-meta-item:hover:before' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .cvmm-post-tiles-slider-post-wrapper .slick-arrow:hover, {{WRAPPER}} .cvmm-post-tiles-slider-post-wrapper .slick-arrow:focus' => 'background: {{VALUE}}',
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
                            '{{WRAPPER}} .cvmm-featured-post-wrapper .cvmm-post-title a' => 'font-size: {{VALUE}}px',
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
                        'selectors' => [
                            '{{WRAPPER}} .cvmm-post-tiles-slider-post-wrapper .cvmm-post-title a' => 'font-size: {{VALUE}}px',
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
                            'top' => '2',
                            'right' => '10',
                            'bottom' => '2',
                            'left' => '10',
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
        $sliderbuttonOption = ( $sliderbuttonOption === 'show' );
        $slidercontentOption= ( $slidercontentOption === 'show' );
        $sliderdateOption   = ( $sliderdateOption === 'show' );
        $sliderauthorOption = ( $sliderauthorOption === 'show' );
        $slidercategoryOption= ( $slidercategoryOption === 'show' );
        $slidertagsOption   = ( $slidertagsOption === 'show' );
        $slidercommentOption= ( $slidercommentOption === 'show' );
        $featuredSectionOption= ( $featuredSectionOption === 'show' );
        $featuredcontentOption= ( $featuredcontentOption === 'show' );
        $featureddateOption   = ( $featureddateOption === 'show' );
        $featuredauthorOption = ( $featuredauthorOption === 'show' );
        $featuredcategoryOption= ( $featuredcategoryOption === 'show' );
        $featuredtagsOption   = ( $featuredtagsOption === 'show' );
        $featuredcommentOption= ( $featuredcommentOption === 'show' );

        $carouselType       = ( $carouselType === 'show' );
        $carouselDots       = ( $carouselDots === 'show' );
        $carouselControls   = ( $carouselControls === 'show' );
        $carouselAuto       = ( $carouselAuto === 'show' );
        $carouselLoop       = ( $carouselLoop === 'show' );
        $postFormatIcon     = ( $postFormatIcon === 'show' );
        $postMetaIcon       = ( $postMetaIcon === 'show' );
        $postButtonIcon     = ( $postButtonIcon === 'show' );
        $fallbackImage      = $fallbackImage['url'];
        $sliderposttype = $featuredposttype = 'post';
        if( empty( $fallbackImage ) ) {
            unset( $fallbackImage );
        }
    ?>
        <div id="wpmagazine-modules-lite-post-tiles-block-<?php echo esc_attr( $element_id ); ?>" class="wpmagazine-modules-lite-post-tiles-block block-<?php echo esc_attr( $element_id ); ?> cvmm-block cvmm-block-post-tiles--<?php echo esc_html( $blockLayout ); ?>">
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