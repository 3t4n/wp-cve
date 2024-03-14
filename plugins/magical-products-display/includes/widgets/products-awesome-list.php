<?php


class mgProducts_AwesomeList extends \Elementor\Widget_Base
{

    use mpdProHelpLink;
    /**
     * Get widget name.
     *
     * Retrieve Blank widget name.
     *
     * @return string Widget name.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_name()
    {
        return 'mgpd_awesomeList';
    }

    /**
     * Get widget title.
     *
     * Retrieve Blank widget title.
     *
     * @return string Widget title.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_title()
    {
        return __('MPD Products Awesome List', 'magical-products-display');
    }

    /**
     * Get widget icon.
     *
     * Retrieve Blank widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_icon()
    {
        return 'eicon-bullet-list';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the Blank widget belongs to.
     *
     * @return array Widget categories.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_categories()
    {
        return ['mpd-productwoo'];
    }

    public function get_keywords()
    {
        return ['mpd', 'woo', 'product', 'awesome', 'list'];
    }
    /**
     * Retrieve the list of styles the image comparison widget depended on.
     *
     * Used to set styles dependencies required to run the widget.
     *
     * @access public
     *
     * @return array Widget styles dependencies.
     */
    public function get_style_depends()
    {
        return [
            'bootstrap-grid',
        ];
    }
    /**
     * Register Blank widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls()
    {

        $this->register_content_controls();
        $this->register_style_controls();
        $this->register_advanced_controls();
    }

    /**
     * Register Blank widget content ontrols.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    function register_content_controls()
    {

        if (get_option('mgppro_is_active', 'no') == 'yes') {
            $pproducts = 'popular_products';
            $percent = 'percentage';
            $number = 'number';
        } else {
            $pproducts = 'best7';
            $percent = 'hide2';
            $number = 'hide3';
        }


        $this->start_controls_section(
            'mpdal_query',
            [
                'label' => esc_html__('Products Query', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mpdal_products_filter',
            [
                'label' => esc_html__('Filter By', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'recent',
                'options' => [
                    'recent' => esc_html__('Recent Products', 'magical-products-display'),
                    'featured' => esc_html__('Featured Products', 'magical-products-display'),
                    'best_selling' => esc_html__('Best Selling Products', 'magical-products-display'),
                    $pproducts => sprintf('%s %s', esc_html__('Popular Products', 'magical-products-display'), mpd_display_pro_only_text()),
                    'sale' => esc_html__('Sale Products', 'magical-products-display'),
                    'top_rated' => esc_html__('Top Rated Products', 'magical-products-display'),
                    'random_order' => esc_html__('Random Products', 'magical-products-display'),
                    'show_byid' => esc_html__('Show By Id', 'magical-products-display'),
                    'show_byid_manually' => esc_html__('Add ID Manually', 'magical-products-display'),
                ],
            ]
        );

        $this->add_control(
            'mpdal_product_id',
            [
                'label' => __('Select Product', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => mgproducts_display_product_name(),
                'condition' => [
                    'mpdal_products_filter' => 'show_byid',
                ]
            ]
        );

        $this->add_control(
            'mpdal_product_ids_manually',
            [
                'label' => __('Product IDs', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'mpdal_products_filter' => 'show_byid_manually',
                ]
            ]
        );

        $this->add_control(
            'mpdal_products_count',
            [
                'label'   => __('Product Limit', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'step'    => 1,
            ]
        );

        $this->add_control(
            'mpdal_grid_categories',
            [
                'label' => esc_html__('Product Categories', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => mgproducts_display_taxonomy_list(),
                'condition' => [
                    'mpdal_products_filter!' => 'show_byid',
                ]
            ]
        );

        $this->add_control(
            'mpdal_custom_order',
            [
                'label' => esc_html__('Custom order', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => esc_html__('Orderby', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'          => esc_html__('None', 'magical-products-display'),
                    'ID'            => esc_html__('ID', 'magical-products-display'),
                    'date'          => esc_html__('Date', 'magical-products-display'),
                    'name'          => esc_html__('Name', 'magical-products-display'),
                    'title'         => esc_html__('Title', 'magical-products-display'),
                    'comment_count' => esc_html__('Comment count', 'magical-products-display'),
                    'rand'          => esc_html__('Random', 'magical-products-display'),
                ],
                'condition' => [
                    'mpdal_custom_order' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => esc_html__('order', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'DESC'  => esc_html__('Descending', 'magical-products-display'),
                    'ASC'   => esc_html__('Ascending', 'magical-products-display'),
                ],
                'condition' => [
                    'mpdal_custom_order' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();
        // Product Content
        $this->start_controls_section(
            'mpdal_layout',
            [
                'label' => esc_html__('Layout', 'magical-products-display'),
            ]
        );
        $this->add_control(
            'mpdal_rownumber',
            [
                'label'   => __('List Column', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => '12',
                'options' => [
                    '12'   => __('1 Column', 'magical-products-display'),
                    '6'  => __('2 Columns', 'magical-products-display'),
                    '4'  => __('3 Columns', 'magical-products-display'),
                    '3'  => __('4 Columns', 'magical-products-display'),
                ]
            ]
        );
        $this->end_controls_section();

        // Product image
        $this->start_controls_section(
            'mpdal_img_section',
            [
                'label' => esc_html__('Products Image', 'magical-products-display'),
            ]
        );
        $this->add_control(
            'mpdal_product_img_show',
            [
                'label'     => __('Show Products image', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'mpdal_img_size',
            [
                'label' => esc_html__('Image Size', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'thumbnail',
                'options' => [
                    'thumbnail'  => esc_html__('Thumbnail (150px x 150px max)', 'magical-products-display'),
                    'medium'   => esc_html__('Medium (300px x 300px max)', 'magical-products-display'),
                    'medium_large'   => esc_html__('Large (768px x 0px max)', 'magical-products-display'),
                    'large'   => esc_html__('Large (1024px x 1024px max)', 'magical-products-display'),
                    'full'   => esc_html__('Full Size (Original image size)', 'magical-products-display'),
                ],
                'condition' => [
                    'mpdal_product_img_show' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mpdal_img_shape',
            [
                'label'   => __('Image Shape', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'square',
                'options' => [
                    'square'   => __('Square', 'magical-products-display'),
                    'circle'   => __('Circle', 'magical-products-display'),
                ]
            ]
        );
        $this->add_control(
            'mpdal_img_position',
            [
                'label'   => __('Image Position', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left'   => __('Left', 'magical-products-display'),
                    'right'   => __('Right', 'magical-products-display'),
                ]
            ]
        );
        $this->add_control(
            'mpdal_img_effects',
            [
                'label' => esc_html__('Image Hover Effects', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'mgpr-hvr-circle',
                'options' => [
                    'mgpr-default'  => esc_html__('No Effects', 'magical-products-display'),
                    'mgpr-hvr-circle'   => esc_html__('Circle Effect', 'magical-products-display'),
                    'mgpr-hvr-shine'   => esc_html__('Shine Effect', 'magical-products-display'),
                    'mgpr-hvr-flashing'   => esc_html__('Flashing Effect', 'magical-products-display'),
                    'mgpr-hvr-hover'   => esc_html__('Opacity Effect', 'magical-products-display'),
                    'mgpr-hvr-blur'   => esc_html__('Blur Effect', 'magical-products-display'),
                    'mgpr-hvr-rotate'   => esc_html__('Rotate Effect', 'magical-products-display'),
                    'mgpr-hvr-slide'   => esc_html__('Slide Effect', 'magical-products-display'),
                    'mgpr-hvr-zoom-out'   => esc_html__('Zoom Out Effect', 'magical-products-display'),
                    'mgpr-hvr-zoom-in'   => esc_html__('Zoom In Effect', 'magical-products-display'),
                ],
                'condition' => [
                    'mpdal_product_img_show' => 'yes',
                ]

            ]
        );
        $this->end_controls_section();
        // Product Content
        $this->start_controls_section(
            'mpdal_content',
            [
                'label' => esc_html__('Content Settings', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mpdal_show_title',
            [
                'label'     => __('Show Product Title', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );
        $this->add_control(
            'mpdal_crop_title',
            [
                'label'   => __('Crop Title By Word', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'step'    => 1,
                'default' => 5,
                'condition' => [
                    'mpdal_show_title' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mpdal_title_tag',
            [
                'label' => __('Title HTML Tag', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h6',
                'condition' => [
                    'mpdal_show_title' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mpdal_price_show',
            [
                'label'     => __('Show Product Price', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );

        $this->add_control(
            'mpdal_cart_btn',
            [
                'label'     => __('Show button', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',

            ]
        );

        $this->add_responsive_control(
            'mpdal_content_align',
            [
                'label' => __('Alignment', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Left', 'magical-products-display'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'magical-products-display'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Right', 'magical-products-display'),
                        'icon' => 'eicon-text-align-right',
                    ],

                ],
                'default' => 'flex-start',
                'classes' => 'flex-{{VALUE}}',
                /*'selectors' => [
					'{{WRAPPER}} .mpdal-block-inner.flex' => 'justify-content: {{VALUE}};',
				],*/
            ]
        );
        $this->add_responsive_control(
            'mgpdel_vertical_align',
            [
                'label' => __('Vertical Alignment', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Top', 'magical-products-display'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => __('Center', 'magical-products-display'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => __('Bottom', 'magical-products-display'),
                        'icon' => 'eicon-v-align-bottom',
                    ],

                ],
                'default' => 'flex-start',
                'classes' => 'flex-{{VALUE}}',
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card .mpdal-text-wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'mpdal_meta_section',
            [
                'label' => __('Products Meta', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
                'default' => 'no',
            ]
        );
        $this->add_control(
            'mpdal_badge_show',
            [
                'label'     => __('Show Badge', 'magical-products-display'),
                'description'     => __('The badge will show if available.', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );
        $this->add_control(
            'mgpdeg_badge_discount',
            [
                'label' => sprintf('%s %s', esc_html__('Discount Badge ', 'magical-products-display'), mpd_display_pro_only_text()),
                'description'     => __('The badge will show only sale products.', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'hide',
                'options' => [
                    'hide' => __('Hide', 'magical-products-display'),
                    $percent => sprintf('%s %s', esc_html__('Percentage Discount ', 'magical-products-display'), mpd_display_pro_only_text()),
                    $number => sprintf('%s %s', esc_html__('Number Discount ', 'magical-products-display'), mpd_display_pro_only_text()),
                ]
            ]
        );
        $this->add_control(
            'mgpdeg_badge_after_text',
            [
                'label'       => sprintf('%s %s', esc_html__('After Text ', 'magical-products-display'), mpd_display_pro_only_text()),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('Off', 'magical-products-display'),
                'default'     => __('Off', 'magical-products-display'),

            ]
        );
        $this->add_control(
            'mgpdeg_badge_before_sign',
            [
                'label'       => sprintf('%s %s', esc_html__('Show Number Before Sign ', 'magical-products-display'), mpd_display_pro_only_text()),
                'description'     => __('The badge will show only sale products.', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'mgpdeg_badge_discount' => 'number',
                ]

            ]
        );
        $this->add_control(
            'mpdal_category_show',
            [
                'label'     => __('Show Category', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',

            ]
        );

        $this->add_control(
            'mpdal_ratting_show',
            [
                'label'     => __('Show Ratting', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );
        $this->add_control(
            'mpdal_meta_position',
            [
                'label'   => __('Meta Position', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top'   => __('Top', 'magical-products-display'),
                    'middle'   => __('Middle', 'magical-products-display'),
                    'bottom'   => __('Bottom', 'magical-products-display'),
                ]
            ]
        );
        $this->end_controls_section();


        $this->start_controls_section(
            'mpdal_card_button',
            [
                'label' => __('Cart Button', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'mpdal_cart_btn' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mpdal_btn_type',
            [
                'label' => esc_html__('Button type', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'cart',
                'options' => [
                    'cart'  => esc_html__('Add to card button', 'magical-products-display'),
                    'view'   => esc_html__('View details', 'magical-products-display'),
                ],

            ]
        );


        $this->add_control(
            'mpdal_card_text',
            [
                'label'       => __('Button Text', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('View details', 'magical-products-display'),
                'default'     => __('View details', 'magical-products-display'),
                'condition' => [
                    'mpdal_btn_type' => 'view',
                ]
            ]
        );
        $this->end_controls_section();

        // Stock settings
        $this->start_controls_section(
            'mgpdeg_stock_section',
            [
                'label' => sprintf('%s %s', __('Products Stock', 'magical-products-display'), mpd_display_pro_only_text()),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
                'default' => 'no',
            ]
        );
        if (get_option('mgppro_is_active', 'no') == 'no') {

            $this->add_control(
                'mgpdeg_stock_info',
                [
                    'label' => sprintf('<span style="color:red">%s</span>', __('The Section only work with pro version.', 'magical-products-display')),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
        }
        $this->add_control(
            'mgpdeg_stock_show',
            [
                'label'     => sprintf('%s %s', esc_html__('Show Stock Slide ', 'magical-products-display'), mpd_display_pro_only_text()),
                'description'     => __('To display the product stock slide need to add stock quantity from the product edit page.', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',

            ]
        );
        $this->add_control(
            'mgpdeg_total_stock_show',
            [
                'label'     => sprintf('%s %s', esc_html__('Show Available products ', 'magical-products-display'), mpd_display_pro_only_text()),
                'description'     => __('You can show or hide total available stock', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'mgpdeg_stock_show' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgpdeg_stock_text',
            [
                'label'       => sprintf('%s %s', esc_html__('Products Available Text ', 'magical-products-display'), mpd_display_pro_only_text()),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('Available', 'magical-products-display'),
                'default'     => __('Available', 'magical-products-display'),
                'condition' => [
                    'mgpdeg_stock_show' => 'yes',
                    'mgpdeg_total_stock_show' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgpdeg_total_sold_show',
            [
                'label'     => sprintf('%s %s', esc_html__('Show total Sold ', 'magical-products-display'), mpd_display_pro_only_text()),
                'description'     => __('You can show or hide total Sold items', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'mgpdeg_stock_show' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'mgpdeg_sold_text',
            [
                'label'       => sprintf('%s %s', esc_html__('Total Sold Text ', 'magical-products-display'), mpd_display_pro_only_text()),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('Sold', 'magical-products-display'),
                'default'     => __('Sold', 'magical-products-display'),
                'condition' => [
                    'mgpdeg_stock_show' => 'yes',
                    'mgpdeg_total_sold_show' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgpdeg_stock_slide_show',
            [
                'label'     => sprintf('%s %s', esc_html__('Show Stock Slide ', 'magical-products-display'), mpd_display_pro_only_text()),
                'description'     => __('You can show or hide stock slide', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'mgpdeg_stock_show' => 'yes',
                ]

            ]
        );

        $this->end_controls_section();
        $this->link_pro_added();
    }

    /**
     * Register Blank widget style ontrols.
     *
     * Adds different input fields in the style tab to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_style_controls()
    {

        $this->start_controls_section(
            'mpdal_style',
            [
                'label' => __('List item style', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mpdal_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdal-container-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdal_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdal-container-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mpdal_bg_color',
                'label' => esc_html__('Background', 'magical-products-display'),
                'types' => ['classic', 'gradient'],

                'selector' => '{{WRAPPER}} .mpdal-container-inner',
            ]
        );

        $this->add_control(
            'mpdal_border_radius',
            [
                'label' => __('Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdal-container-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdal_content_border',
                'selector' => '{{WRAPPER}} .mpdal-container-inner',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mpdal_content_shadow',
                'selector' => '{{WRAPPER}} .mpdal-container-inner',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'mpdal_img_style',
            [
                'label' => __('Image style', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'image_width_set',
            [
                'label' => __('Width', 'magical-products-display'),
                'type' =>  \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'desktop_default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],

                ],
                'selectors' => [
                    '{{WRAPPER}} .mpdal-imgrap figure img' => 'flex: 0 0 {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',

                ],
            ]
        );

        $this->add_control(
            'mpdal_img_auto_height',
            [
                'label' => __('Image auto height', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'magical-products-display'),
                'label_off' => __('Off', 'magical-products-display'),
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'mpdal_img_height',
            [
                'label' => __('Image Height', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ]
                ],
                'condition' => [
                    'mpdal_img_auto_height!' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mpdal-imgrap figure img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mpdal_imgbg_height',
            [
                'label' => __('Image div Height', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ]
                ],
                'condition' => [
                    'mpdal_img_auto_height!' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mpdal-imgrap figure' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mpdal_img_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdal-imgrap, {{WRAPPER}} .mpdal-imgrap figure img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdal_img_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdal-imgrap figure' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mpdal_img_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdal-imgrap figure img, {{WRAPPER}} .mgpl-img-circle .mpdal-imgrap figure' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mpdal_img_bgcolor',
                'label' => esc_html__('Background', 'magical-products-display'),
                //'types' => [ 'classic', 'gradient' ],

                'selector' => '{{WRAPPER}} .mpdal-imgrap, {{WRAPPER}} .mpdal-imgrap figure img',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdal_img_border',
                'selector' => '{{WRAPPER}} .mpdal-imgrap figure img',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'mpdal_desc_style',
            [
                'label' => __('Product Title', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mpdal_title_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card .mpdal-ptitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdal_title_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card .mpdal-ptitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mpdal_title_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card .mpdal-ptitle' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mpdal_title_bgcolor',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card .mpdal-ptitle' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mpdal_descb_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card .mpdal-ptitle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mpdal_title_typography',
                'selector' => '{{WRAPPER}} .mgpdel-card .mpdal-ptitle',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'mpdal_meta_style',
            [
                'label' => __('Products Meta', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'mpdal_meta_badge',
            [
                'label' => __('Products Badge', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'mpdal_meta_badge_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdal-imgrap .mgp-display-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdal_meta_badge_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdal-imgrap .mgp-display-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mpdal_meta_badge_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpdal-imgrap .mgp-display-badge' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mpdal_meta_badge_bgcolor',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpdal-imgrap .mgp-display-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mpdal_meta_badge_typography',
                'selector' => '{{WRAPPER}} .mpdal-imgrap .mgp-display-badge',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdal_badge_border',
                'selector' => '{{WRAPPER}} .mpdal-imgrap .mgp-display-badge',
            ]
        );

        $this->add_control(
            'mpdal_badge_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdal-imgrap .mgp-display-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        // pro sale badge style
        $this->add_control(
            'mgpdeg_sale_badge',
            [
                'label' => sprintf('%s %s', __('Pro Discount Badge', 'magical-products-display'), mpd_display_pro_only_text()),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'mgpdeg_sale_badge_margin',
            [
                'label' => sprintf('%s %s', __('Margin', 'magical-products-display'), mpd_display_pro_only_text()),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} span.mgp-display-badge.mgp-pro-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpdeg_sale_badge_padding',
            [
                'label' => sprintf('%s %s', __('Padding', 'magical-products-display'), mpd_display_pro_only_text()),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} span.mgp-display-badge.mgp-pro-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdeg_sale_badge_color',
            [
                'label' => sprintf('%s %s', __('Text Color', 'magical-products-display'), mpd_display_pro_only_text()),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} span.mgp-display-badge.mgp-pro-badge' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdeg_sale_badge_bgcolor',
            [
                'label' => sprintf('%s %s', __('Background Color', 'magical-products-display'), mpd_display_pro_only_text()),

                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} span.mgp-display-badge.mgp-pro-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgpdeg_sale_badge_typography',
                'selector' => '{{WRAPPER}} span.mgp-display-badge.mgp-pro-badge',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpdeg_sale_badge_border',
                'selector' => '{{WRAPPER}} span.mgp-display-badge.mgp-pro-badge',
            ]
        );

        $this->add_control(
            'mgpdeg_sale_badge_border_radius',
            [
                'label' => sprintf('%s %s', __('Border Radius', 'magical-products-display'), mpd_display_pro_only_text()),

                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} span.mgp-display-badge.mgp-pro-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'mpdal_meta_cat',
            [
                'label' => __('Category style', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'mpdal_meta_cat_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdal-category a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdal_meta_cat_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdal-category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdal_cat_border',
                'selector' => '{{WRAPPER}} .mpdal-category a',
            ]
        );
        $this->add_control(
            'mpdal_meta_cat_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpdal-category a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mpdal_meta_cat_bgcolor',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpdal-category a' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mpdal_meta_cat_typography',
                'selector' => '{{WRAPPER}} .mpdal-category a',
            ]
        );
        $this->add_control(
            'mpdal_meta_star',
            [
                'label' => __('Rating Style', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'mpdal_meta_star_color',
            [
                'label' => __('Rating star Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpdal-product-rating .wd-product-ratting i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mpdal_meta_starfill_color',
            [
                'label' => __('Rating star Fill Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpdal-product-rating .wd-product-ratting .wd-product-user-ratting i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'mpdal_btn_style',
            [
                'label' => __('Button', 'magical-products-display'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mpdal_btn_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdal-cart-btn a.button,{{WRAPPER}} .mpdal-cart-btn a.added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdal_btn_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdal-cart-btn a.button, {{WRAPPER}} .mpdal-cart-btn a.added_to_cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mpdal_btn_typography',
                'selector' => '{{WRAPPER}} .mpdal-cart-btn a.button, {{WRAPPER}} .mpdal-cart-btn a.added_to_cart',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdal_btn_border',
                'selector' => '{{WRAPPER}} .mpdal-cart-btn a.button, {{WRAPPER}} .mpdal-cart-btn a.added_to_cart',
            ]
        );

        $this->add_control(
            'mpdal_btn_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdal-cart-btn a.button,{{WRAPPER}} .mpdal-cart-btn a.added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mpdal_btn_box_shadow',
                'selector' => '{{WRAPPER}} .mpdal-cart-btn a.button,{{WRAPPER}} .mpdal-cart-btn a.added_to_cart',
            ]
        );
        $this->add_control(
            'mpdal_button_color',
            [
                'label' => __('Button color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs('infobox_btn_tabs');

        $this->start_controls_tab(
            'mpdal_btn_normal_style',
            [
                'label' => __('Normal', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mpdal_btn_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mpdal-cart-btn a.button,{{WRAPPER}} .mpdal-cart-btn a.added_to_cart' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mpdal_btn_bg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpdal-cart-btn a.button,{{WRAPPER}} .mpdal-cart-btn a.added_to_cart' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'mpdal_btn_hover_style',
            [
                'label' => __('Hover', 'magical-products-display'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mpdal_btnhover_boxshadow',
                'selector' => '{{WRAPPER}} .mpdal-cart-btn a.button:hover,{{WRAPPER}} .mpdal-cart-btn a.added_to_cart:hover',
            ]
        );

        $this->add_control(
            'mpdal_btn_hcolor',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpdal-cart-btn a.button:hover, {{WRAPPER}} .mpdal-cart-btn a.button:focus,{{WRAPPER}} .mpdal-cart-btn a.added_to_cart:hover, {{WRAPPER}} .mpdal-cart-btn a.added_to_cart:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mpdal_btn_hbg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpdal-cart-btn a.button:hover, {{WRAPPER}} .mpdal-cart-btn a.button:focus,{{WRAPPER}} .mpdal-cart-btn a.added_to_cart:hover, {{WRAPPER}} .mpdal-cart-btn a.added_to_cart:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mpdal_btn_hborder_color',
            [
                'label' => __('Border Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'mpdal_btn_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mpdal-cart-btn a.button:hover, {{WRAPPER}} .mpdal-cart-btn a.button:focus,{{WRAPPER}} .mpdal-cart-btn a.added_to_cart:hover, {{WRAPPER}} .mpdal-cart-btn a.added_to_cart:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        // Products Stock Style
        $this->start_controls_section(
            'mgpdeg_pstock_style',
            [
                'label'     => sprintf('%s %s', esc_html__('Products Stock Style ', 'magical-products-display'), mpd_display_pro_only_text()),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'mgpdeg_stock_show' => 'yes',
                ]
            ]
        );
        if (get_option('mgppro_is_active', 'no') == 'no') {

            $this->add_control(
                'mgpdeg_stock_style_info',
                [
                    'label' => sprintf('<span style="color:red">%s</span>', __('The Section only work with pro version.', 'magical-products-display')),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
        }
        $this->add_control(
            'mgpdeg_pstock_text_style',
            [
                'label' => __('Text Style', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'mgpdeg_pstock_text_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgppro-stock-stext' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpdeg_pstock_text_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgppro-stock-stext' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdeg_pstock_text_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgppro-stock-stext' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgpdeg_pstock_text_typography',
                'selector' => '{{WRAPPER}} .mgppro-stock-stext .mgppro-total-stock, {{WRAPPER}} .mgppro-stock-stext .mgppro-available-stock',
            ]
        );
        $this->add_control(
            'mgpdeg_pstock_slide_style',
            [
                'label' => __('Slide Style', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mgpdeg_pstock_slide_bg',
                'label' => esc_html__('Slide Background', 'magical-products-display'),
                'types' => ['classic', 'gradient'],

                'selector' => '{{WRAPPER}} .mgppro-range1',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mgpdeg_pstock_slide_fillbg',
                'label' => esc_html__('Slide Fill Background', 'magical-products-display'),
                'types' => ['classic', 'gradient'],

                'selector' => '{{WRAPPER}} .mgppro-range2',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpdeg_pstock_slide_border',
                'selector' => '{{WRAPPER}} .mgppro-range1',
            ]
        );

        $this->add_control(
            'mgpdeg_pstock_slide_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgppro-range' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdeg_pstock_slide_height',
            [
                'label' => __('Slide Height', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                        'step' => 1,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgppro-range' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Blank widget Advanced ontrols.
     *
     * Adds different input fields in the style tab to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_advanced_controls()
    {
        $this->start_controls_section(
            'mpdal_attr_sec',
            [
                'label' => __('Magical Attributes', 'plugin-name'),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $this->add_control(
            'mpdal_attr_calss',
            [
                'label' => __('Custom Class', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
        $this->add_control(
            'mpdal_attr_id',
            [
                'label' => __('Custom ID', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'mpdal_custom_css_sec',
            [
                'label' => __('Magical Custom CSS', 'plugin-name'),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );
        $this->add_control(
            'mpdal_custom_css',
            [
                'label' => __('Custom CSS', 'plugin-domain'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'css',
                'rows' => 20,
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render Blank widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $mpdal_filter = $this->get_settings('mpdal_products_filter');
        $mpdal_products_count = $this->get_settings('mpdal_products_count');
        $mpdal_custom_order = $this->get_settings('mpdal_custom_order');
        $mpdal_grid_categories = $this->get_settings('mpdal_grid_categories');
        $orderby = $this->get_settings('orderby');
        $order = $this->get_settings('order');


        if ($mpdal_filter == 'best7') {
            $ptype = 'unknown';
        } else {
            $ptype = 'product';
        }
        // Query Argument
        $args = array(
            'post_type'             => $ptype,
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => $mpdal_products_count,
        );

        switch ($mpdal_filter) {

            case 'sale':
                $args['post__in'] = array_merge(array(0), wc_get_product_ids_on_sale());
                break;

            case 'featured':
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN',
                );
                break;

            case 'best_selling':
                $args['meta_key']   = 'total_sales';
                $args['orderby']    = 'meta_value_num';
                $args['order']      = 'desc';
                break;

            case 'popular_products':
                $args['meta_key']   = '_product_views_count';
                $args['orderby']    = 'meta_value_num';
                $args['date_query']      = array(
                    array(
                        'before'     => date('Y-m-d', strtotime('-7 days')),
                        'inclusive' => true,
                    )
                );

                break;

            case 'top_rated':
                $args['meta_key']   = '_wc_average_rating';
                $args['orderby']    = 'meta_value_num';
                $args['order']      = 'desc';
                break;

            case 'random_order':
                $args['orderby']    = 'rand';
                break;

            case 'show_byid':
                $args['post__in'] = $settings['mpdal_product_id'];
                break;

            case 'show_byid_manually':
                $args['post__in'] = explode(',', $settings['mpdal_product_ids_manually']);
                break;

            default: /* Recent */
                $args['orderby']    = 'date';
                $args['order']      = 'desc';
                break;
        }

        // Custom Order
        if ($mpdal_custom_order == 'yes') {
            $args['orderby'] = $orderby;
            $args['order'] = $order;
        }

        if (!(($mpdal_filter == "show_byid") || ($mpdal_filter == "show_byid_manually"))) {

            $product_cats = str_replace(' ', '', $mpdal_grid_categories);
            if ("0" != $mpdal_grid_categories) {
                if (is_array($product_cats) && count($product_cats) > 0) {
                    $field_name = is_numeric($product_cats[0]) ? 'term_id' : 'slug';
                    $args['tax_query'][] = array(
                        array(
                            'taxonomy' => 'product_cat',
                            'terms' => $product_cats,
                            'field' => $field_name,
                            'include_children' => false
                        )
                    );
                }
            }
        }



        //grid layout
        $mpdal_img_shape = $this->get_settings('mpdal_img_shape');
        $mpdal_img_position = $this->get_settings('mpdal_img_position');
        // grid content
        $mpdal_show_title = $this->get_settings('mpdal_show_title');
        $mpdal_crop_title = $this->get_settings('mpdal_crop_title');
        $mpdal_title_tag = $this->get_settings('mpdal_title_tag');
        $mpdal_price_show = $this->get_settings('mpdal_price_show');
        $mpdal_cart_btn = $this->get_settings('mpdal_cart_btn');
        $mpdal_meta_position = $this->get_settings('mpdal_meta_position');
        $mpdal_btn_type = $this->get_settings('mpdal_btn_type');
        $mpdal_card_text = $this->get_settings('mpdal_card_text');



        $mpdal_products = new WP_Query($args);
        $mgpl_unque_num = rand('598264', '7842351');
        if ($mpdal_products->have_posts()) :
?>

            <div <?php if ($settings['mpdal_attr_id']) : ?> id="<?php echo esc_attr($settings['mpdal_attr_id']); ?>" <?php endif; ?> class="mgpl-unique<?php echo esc_attr($mgpl_unque_num); ?> mpd-awesome-list <?php echo esc_attr($settings['mpdal_attr_calss']); ?>">
                <?php if ($settings['mpdal_custom_css']) : ?>
                    <style>
                        <?php echo esc_html($settings['mpdal_custom_css']); ?>
                    </style>
                <?php endif; ?>
                <div class="mgproductd mgp-list mgpde-items mgpdel-card mgpl-img-<?php echo esc_attr($mpdal_img_shape); ?> mgpl-position-<?php echo esc_attr($mpdal_img_position); ?>">
                    <div class="row">
                        <?php while ($mpdal_products->have_posts()) : $mpdal_products->the_post(); ?>
                            <div class="col-lg-<?php echo esc_attr($settings['mpdal_rownumber']); ?>">
                                <div class="mpdal-container-inner mb-4">
                                    <div class="mpdal-block-inner flex align-<?php echo esc_attr($settings['mpdal_content_align']); ?> mpdal-meta-<?php echo esc_attr($mpdal_meta_position); ?>">
                                        <?php
                                        if ($mpdal_img_position == 'left') {
                                            $this->mpd_awelist_img($settings);
                                        }
                                        ?>
                                        <div class="mpdal-text-wrapper">
                                            <?php
                                            if ($mpdal_meta_position == 'top') {
                                                $this->mpd_awelist_meta($settings);
                                            }
                                            ?>

                                            <?php if ($mpdal_show_title == 'yes') : ?>
                                                <a class="mpdal-ptitle-link" href="<?php the_permalink(); ?>">
                                                    <?php
                                                    printf(
                                                        '<%1$s class="mpdal-ptitle">%2$s</%1$s>',
                                                        tag_escape($mpdal_title_tag),
                                                        wp_trim_words(get_the_title(), $mpdal_crop_title)
                                                    );
                                                    ?>
                                                </a>
                                            <?php endif; ?>
                                            <?php
                                            if ($mpdal_meta_position == 'middle') {
                                                $this->mpd_awelist_meta($settings);
                                            }
                                            ?>
                                            <?php if ($mpdal_price_show == 'yes') : ?>
                                                <div class="mpdal-price mb-1">
                                                    <?php woocommerce_template_loop_price(); ?>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($mpdal_cart_btn == 'yes') : ?>
                                                <div class="mpdal-cart-btn">
                                                    <?php if ($mpdal_btn_type == 'cart') : ?>
                                                        <?php woocommerce_template_loop_add_to_cart(); ?>
                                                    <?php else : ?>
                                                        <a class="button " href="<?php the_permalink(); ?>"><?php echo esc_html($mpdal_card_text); ?></a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php
                                            if ($mpdal_meta_position == 'bottom') {
                                                $this->mpd_awelist_meta($settings);
                                            }
                                            if ($settings['mgpdeg_stock_show'] && get_option('mgppro_is_active', 'no') == 'yes') {
                                                do_action(
                                                    'mgppro_products_stock',
                                                    $settings['mgpdeg_total_stock_show'],
                                                    $settings['mgpdeg_stock_text'],
                                                    $settings['mgpdeg_total_sold_show'],
                                                    $settings['mgpdeg_sold_text'],
                                                    $settings['mgpdeg_stock_slide_show']
                                                );
                                            }
                                            ?>

                                        </div>
                                        <?php
                                        if ($mpdal_img_position == 'right') {
                                            $this->mpd_awelist_img($settings);
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>

                        <?php
                        endwhile;
                        wp_reset_query();
                        wp_reset_postdata();
                        ?>
                    </div><!-- //row end -->
                </div>
            </div>





        <?php
        endif;
    }



    public function mpd_awelist_img($settings)
    {
        if ($settings['mpdal_product_img_show'] != 'yes') {
            return;
        }
        if ($settings['mpdal_img_position'] == 'left') {
            $mlex_class = 'me-3';
        } else {
            $mlex_class = 'ms-4';
        }
        $after_text = $settings['mgpdeg_badge_after_text'];
        $before_sign = $settings['mgpdeg_badge_before_sign'];
        ?>
        <div class="mpdal-imgrap <?php echo esc_attr($mlex_class); ?>  <?php echo esc_attr($settings['mpdal_img_effects']); ?>">
            <?php
            if (class_exists('WooCommerce') && $settings['mpdal_badge_show'] == 'yes') {
                mgproducts_display_products_badge();
            }
            if (get_option('mgppro_is_active', 'no') == 'yes') {
                if ($settings['mgpdeg_badge_discount'] == 'percentage') {
                    do_action('mgppro_percent_sale_badge', $after_text);
                }
                if ($settings['mgpdeg_badge_discount'] == 'number') {
                    do_action('mgppro_number_sale_badge', $before_sign, $after_text);
                }
            }
            ?>
            <figure>
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail($settings['mpdal_img_size']); ?>
                </a>
            </figure>
        </div>
        <?php
    }

    public function mpd_awelist_meta($settings)
    {

        if ($settings['mpdal_category_show'] == 'yes') : ?>
            <div class="mpdal-category mb-1">
                <?php mgproducts_display_product_category(); ?>
            </div>
        <?php endif; ?>
        <?php if ($settings['mpdal_ratting_show']) : ?>
            <div class="mpdal-product-rating">
                <?php echo mgproducts_display_wc_get_rating_html(); ?>
                <?php // mgproducts_display_wc_rating_number(); 
                ?>
            </div>
<?php endif;
    }
}
