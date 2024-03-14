<?php


class mgProducts_List extends \Elementor\Widget_Base
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
        return 'mg_products_list';
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
        return __('MPD Products List', 'magical-products-display');
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
        return 'eicon-editor-list-ul';
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
        return ['mpd', 'woo', 'product', 'ecommerce', 'list'];
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
            // discount badge
            $percent = 'percentage';
            $number = 'number';
        } else {
            $pproducts = 'best7';
            // discount badge
            $percent = 'hide2';
            $number = 'hide3';
        }

        $this->start_controls_section(
            'mgpdel_query',
            [
                'label' => esc_html__('Products Query', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mgpdel_products_filter',
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
            'mgpdel_product_id',
            [
                'label' => __('Select Product', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => mgproducts_display_product_name(),
                'condition' => [
                    'mgpdel_products_filter' => 'show_byid',
                ]
            ]
        );

        $this->add_control(
            'mgpdel_product_ids_manually',
            [
                'label' => __('Product IDs', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'mgpdel_products_filter' => 'show_byid_manually',
                ]
            ]
        );

        $this->add_control(
            'mgpdel_products_count',
            [
                'label'   => __('Product Limit', 'magical-products-display'),
                'description' => esc_html__('Set products number for this section', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'step'    => 1,
            ]
        );

        $this->add_control(
            'mgpdel_grid_categories',
            [
                'label' => esc_html__('Product Categories', 'magical-products-display'),
                'description' => esc_html__('Leave Empty For Show All Categories', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => mgproducts_display_taxonomy_list(),
                'condition' => [
                    'mgpdel_products_filter!' => 'show_byid',
                ]
            ]
        );

        $this->add_control(
            'mgpdel_custom_order',
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
                    'mgpdel_custom_order' => 'yes',
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
                    'mgpdel_custom_order' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();
        // Product Content
        $this->start_controls_section(
            'mgpdel_layout',
            [
                'label' => esc_html__('List Layout', 'magical-products-display'),
            ]
        );
        $this->add_control(
            'mgpdel_product_style',
            [
                'label'   => __('List Style', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1'   => __('Style One', 'magical-products-display'),
                    '2'  => __('Style Two', 'magical-products-display'),
                ]
            ]
        );
        $this->add_control(
            'mgpdel_img_position',
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
        $this->end_controls_section();
        // Product image
        $this->start_controls_section(
            'mgpdel_img_section',
            [
                'label' => esc_html__('Products Image', 'magical-products-display'),
            ]
        );
        $this->add_control(
            'mgpdel_product_img_show',
            [
                'label'     => __('Show Products image', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'mgpdel_img_size',
            [
                'label' => esc_html__('Image Size', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'medium_large',
                'options' => [
                    'thumbnail'  => esc_html__('Thumbnail (150px x 150px max)', 'magical-products-display'),
                    'medium'   => esc_html__('Medium (300px x 300px max)', 'magical-products-display'),
                    'medium_large'   => esc_html__('Large (768px x 0px max)', 'magical-products-display'),
                    'large'   => esc_html__('Large (1024px x 1024px max)', 'magical-products-display'),
                    'full'   => esc_html__('Full Size (Original image size)', 'magical-products-display'),
                ],
                'condition' => [
                    'mgpdel_product_img_show' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgpdel_img_effects',
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
                    'mgpdel_product_img_show' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgpdel_img_flip_show',
            [
                'label' => sprintf('%s %s', esc_html__('Active Image Flip ', 'magical-products-display'), mpd_display_pro_only_text()),
                'description'     => __('Two Product images create a hover flip. You need to add gallery images to view two different product images on the product edit page.', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => ' ',
                'condition' => [
                    'mgpdel_product_img_show' => 'yes',
                ]
            ]
        );
        $this->end_controls_section();
        // Product Content
        $this->start_controls_section(
            'mgpdel_content',
            [
                'label' => esc_html__('Content Settings', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mgpdel_show_title',
            [
                'label'     => __('Show Product Title', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );
        $this->add_control(
            'mgpdel_crop_title',
            [
                'label'   => __('Crop Title By Word', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'step'    => 1,
                'default' => 5,
                'condition' => [
                    'mgpdel_show_title' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgpdel_title_tag',
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
                'default' => 'h2',
                'condition' => [
                    'mgpdel_show_title' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgpdel_desc_show',
            [
                'label'     => __('Show Product Description', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );
        $this->add_control(
            'mgpdel_crop_desc',
            [
                'label'   => __('Crop Description By Word', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'step'    => 1,
                'default' => 25,
                'condition' => [
                    'mgpdel_desc_show' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'mgpdel_price_show',
            [
                'label'     => __('Show Product Price', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );

        $this->add_control(
            'mgpdel_cart_btn',
            [
                'label'     => __('Show button', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );

        $this->add_responsive_control(
            'mgpdel_content_align',
            [
                'label' => __('Alignment', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'magical-products-display'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'magical-products-display'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'magical-products-display'),
                        'icon' => 'eicon-text-align-right',
                    ],

                ],
                'default' => 'left',
                'classes' => 'flex-{{VALUE}}',
                'selectors' => [
                    '{{WRAPPER}} .mgpde-card-text.mgpdel-card-text' => 'text-align: {{VALUE}};',
                ],
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
                'default' => 'center',
                'classes' => 'flex-{{VALUE}}',
                'selectors' => [
                    '{{WRAPPER}} .mgpde-card-text.mgpdel-card-text' => 'justify-content: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'mgpdel_meta_section',
            [
                'label' => __('Products Meta', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
                'default' => 'no',
            ]
        );
        $this->add_control(
            'mgpdel_badge_show',
            [
                'label'     => __('Show Sale Badge', 'magical-products-display'),
                'description'     => __('The badge will show if available.', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );
        $this->add_control(
            'mgpdel_badge_discount',
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
            'mgpdel_badge_after_text',
            [
                'label'       => sprintf('%s %s', esc_html__('After Text ', 'magical-products-display'), mpd_display_pro_only_text()),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('Off', 'magical-products-display'),
                'default'     => __('Off', 'magical-products-display'),

            ]
        );
        $this->add_control(
            'mgpdel_badge_before_sign',
            [
                'label'     => __('Show Number Before Sign', 'magical-products-display'),
                'description'     => __('The badge will show only sale products.', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'mgpdel_badge_discount' => 'number',
                ]

            ]
        );
        $this->add_control(
            'mgpdel_category_show',
            [
                'label'     => __('Show Category', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );

        $this->add_control(
            'mgpdel_ratting_show',
            [
                'label'     => __('Show Ratting', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'mgpdel_adicons',
            [
                'label' => sprintf('%s %s', __('Products Advance Icons', 'magical-products-display'), mpd_display_pro_only_text()),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        if (get_option('mgppro_is_active', 'no') == 'no') {

            $this->add_control(
                'mgpdel_adicons_info',
                [
                    'label' => sprintf('<span style="color:red">%s</span>', __('Advance Icons Section only work with pro version.', 'magical-products-display')),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $mgpdel_adicons_default = ' ';
        } else {
            $mgpdel_adicons_default = 'yes';
        }

        $this->add_control(
            'mgpdel_adicons_show',
            [
                'label' => __('Advance Icons Show', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'default' => $mgpdel_adicons_default,
            ]
        );
        $this->add_control(
            'mgpdel_adicons_position',
            [
                'label' => __('Advance Icons Position', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'right' => __('Show Right Side', 'magical-products-display'),
                    'left' => __('Show Left Side', 'magical-products-display'),
                ],
                'default' => 'right',
                'condition' => [
                    'mgpdel_adicons_show' => 'yes',
                ]
            ]
        );
        if (function_exists('yith_wishlist_install')) {
            $this->add_control(
                'mgpdel_wishlist_show',
                [
                    'label' => __('Show Wishlist Icon', 'magical-products-display'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Yes', 'magical-products-display'),
                    'label_off' => __('No', 'magical-products-display'),
                    'default' => 'yes',
                    'condition' => [
                        'mgpdel_adicons_show' => 'yes',
                    ]
                ]
            );
            $this->add_control(
                'mgpdel_wishlist_text',
                [
                    'label'       => __('Wishlist Text', 'magical-products-display'),
                    'type'        => \Elementor\Controls_Manager::TEXT,
                    'input_type'  => 'text',
                    'placeholder' => __('Wishlist', 'magical-products-display'),
                    'default'     => __('Wishlist', 'magical-products-display'),
                    'condition' => [
                        'mgpdel_adicons_show' => 'yes',
                    ]
                ]
            );
        }
        $this->add_control(
            'mgpdel_share_show',
            [
                'label' => __('Show Social Share Icons', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'default' => 'yes',
                'condition' => [
                    'mgpdel_adicons_show' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'mgpdel_share_text',
            [
                'label'       => __('Share Text', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('Share Now', 'magical-products-display'),
                'default'     => __('Share Now', 'magical-products-display'),
                'condition' => [
                    'mgpdel_adicons_show' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'mgpdel_video_show',
            [
                'label' => __('Show Video Icons', 'magical-products-display'),
                'description' => __('The video icons will only be displayed when a YouTube video is available', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'default' => 'yes',
                'condition' => [
                    'mgpdel_adicons_show' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'mgpdel_video_text',
            [
                'label'       => __('Video Text', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('Show Video', 'magical-products-display'),
                'default'     => __('Show Video', 'magical-products-display'),
                'condition' => [
                    'mgpdel_adicons_show' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'mgpdel_qrcode_show',
            [
                'label' => __('Show QR Code Icons', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'default' => 'yes',
                'condition' => [
                    'mgpdel_adicons_show' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'mgpdel_qrcode_text',
            [
                'label'       => __('QR Code Text', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('QR Code', 'magical-products-display'),
                'default'     => __('QR Code', 'magical-products-display'),
                'condition' => [
                    'mgpdel_adicons_show' => 'yes',
                ]
            ]
        );
        $this->end_controls_section();


        $this->start_controls_section(
            'mgpdel_card_button',
            [
                'label' => __('Cart Button', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'mgpdel_cart_btn' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgpdel_btn_type',
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
            'mgpdel_card_text',
            [
                'label'       => __('Button Text', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('View details', 'magical-products-display'),
                'default'     => __('View details', 'magical-products-display'),
                'condition' => [
                    'mgpdel_btn_type' => 'view',
                ]
            ]
        );
        $this->end_controls_section();

        // Stock settings
        $this->start_controls_section(
            'mgpdel_stock_section',
            [
                'label' => sprintf('%s %s', __('Products Stock', 'magical-products-display'), mpd_display_pro_only_text()),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
                'default' => 'no',
            ]
        );
        if (get_option('mgppro_is_active', 'no') == 'no') {

            $this->add_control(
                'mgpdel_stock_info',
                [
                    'label' => sprintf('<span style="color:red">%s</span>', __('The Section only work with pro version.', 'magical-products-display')),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
        }
        $this->add_control(
            'mgpdel_stock_show',
            [
                'label'     => sprintf('%s %s', esc_html__('Show Stock Slide ', 'magical-products-display'), mpd_display_pro_only_text()),
                'description'     => __('To display the product stock slide need to add stock quantity from the product edit page.', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',

            ]
        );
        $this->add_control(
            'mgpdel_total_stock_show',
            [
                'label'     => sprintf('%s %s', esc_html__('Show Available products ', 'magical-products-display'), mpd_display_pro_only_text()),
                'description'     => __('You can show or hide total available stock', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'mgpdel_stock_show' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgpdel_stock_text',
            [
                'label'       => sprintf('%s %s', esc_html__('Products Available Text ', 'magical-products-display'), mpd_display_pro_only_text()),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('Available', 'magical-products-display'),
                'default'     => __('Available', 'magical-products-display'),
                'condition' => [
                    'mgpdel_stock_show' => 'yes',
                    'mgpdel_total_stock_show' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgpdel_total_sold_show',
            [
                'label'     => sprintf('%s %s', esc_html__('Show total Sold ', 'magical-products-display'), mpd_display_pro_only_text()),
                'description'     => __('You can show or hide total Sold items', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'mgpdel_stock_show' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'mgpdel_sold_text',
            [
                'label'       => sprintf('%s %s', esc_html__('Total Sold Text ', 'magical-products-display'), mpd_display_pro_only_text()),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('Sold', 'magical-products-display'),
                'default'     => __('Sold', 'magical-products-display'),
                'condition' => [
                    'mgpdel_stock_show' => 'yes',
                    'mgpdel_total_sold_show' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgpdel_stock_slide_show',
            [
                'label'     => sprintf('%s %s', esc_html__('Show Stock Slide ', 'magical-products-display'), mpd_display_pro_only_text()),
                'description'     => __('You can show or hide stock slide', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'mgpdel_stock_show' => 'yes',
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
            'mgpdel_style',
            [
                'label' => __('Layout style', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mgpdel_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpdel_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mgpdel_bg_color',
                'label' => esc_html__('Background', 'magical-products-display'),
                'types' => ['classic', 'gradient'],

                'selector' => '{{WRAPPER}} .mgpdel-card',
            ]
        );

        $this->add_control(
            'mgpdel_border_radius',
            [
                'label' => __('Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpdel_content_border',
                'selector' => '{{WRAPPER}} .mgpdel-card',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mgpdel_content_shadow',
                'selector' => '{{WRAPPER}} .mgpdel-card',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'mgpdel_img_style',
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
                    '{{WRAPPER}} .mgpdel-card-img figure img' => 'flex: 0 0 {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',

                ],
            ]
        );

        $this->add_control(
            'mgpdel_img_auto_height',
            [
                'label' => __('Image auto height', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'magical-products-display'),
                'label_off' => __('Off', 'magical-products-display'),
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'mgpdel_img_height',
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
                    'mgpdel_img_auto_height!' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card-img figure img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdel_imgbg_height',
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
                    'mgpdel_img_auto_height!' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card-img figure' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgpdel_img_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card-img, {{WRAPPER}} .mgpdel-card-img figure img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpdel_img_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card-img figure' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mgpdel_img_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card-img figure img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mgpdel_img_bgcolor',
                'label' => esc_html__('Background', 'magical-products-display'),
                //'types' => [ 'classic', 'gradient' ],

                'selector' => '{{WRAPPER}} .mgpdel-card-img, {{WRAPPER}} .mgpdel-card-img figure img',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpdel_img_border',
                'selector' => '{{WRAPPER}} .mgpdel-card-img figure img',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'mgpdel_desc_style',
            [
                'label' => __('Product Title', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mgpdel_title_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card .mgpde-ptitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpdel_title_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card .mgpde-ptitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdel_title_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card .mgpde-ptitle' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdel_title_bgcolor',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card .mgpde-ptitle' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdel_descb_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card .mgpde-ptitle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgpdel_title_typography',
                'selector' => '{{WRAPPER}} .mgpdel-card .mgpde-ptitle',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'mgpdel_description_style',
            [
                'label' => __('Description', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mgpdel_description_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card-text p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpdel_description_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card-text p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdel_description_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card-text p' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdel_description_bgcolor',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card-text p' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdel_description_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card-text p' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgpdel_description_typography',
                'selector' => '{{WRAPPER}} .mgpdel-card-text p',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'mgpdel_meta_style',
            [
                'label' => __('Products Meta', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'mgpdel_meta_badge',
            [
                'label' => __('Products Badge', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'mgpdel_meta_badge_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgp-display-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpdel_meta_badge_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgp-display-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdel_meta_badge_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgp-display-badge' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdel_meta_badge_bgcolor',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgp-display-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgpdel_meta_badge_typography',
                'selector' => '{{WRAPPER}} .mgp-display-badge',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpdel_badge_border',
                'selector' => '{{WRAPPER}} .mgp-display-badge',
            ]
        );

        $this->add_control(
            'mgpdel_badge_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgp-display-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // pro sale badge style
        $this->add_control(
            'mgpdel_sale_badge',
            [
                'label' => sprintf('%s %s', __('Pro Discount Badge', 'magical-products-display'), mpd_display_pro_only_text()),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'mgpdel_sale_badge_margin',
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
            'mgpdel_sale_badge_padding',
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
            'mgpdel_sale_badge_color',
            [
                'label' => sprintf('%s %s', __('Text Color', 'magical-products-display'), mpd_display_pro_only_text()),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} span.mgp-display-badge.mgp-pro-badge' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdel_sale_badge_bgcolor',
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
                'name' => 'mgpdel_sale_badge_typography',
                'selector' => '{{WRAPPER}} span.mgp-display-badge.mgp-pro-badge',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpdel_sale_badge_border',
                'selector' => '{{WRAPPER}} span.mgp-display-badge.mgp-pro-badge',
            ]
        );

        $this->add_control(
            'mgpdel_sale_badge_border_radius',
            [
                'label' => sprintf('%s %s', __('Border Radius', 'magical-products-display'), mpd_display_pro_only_text()),

                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} span.mgp-display-badge.mgp-pro-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        //category style   
        $this->add_control(
            'mgpdel_meta_cat',
            [
                'label' => __('Category style', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'mgpdel_meta_cat_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card-text .mgpde-category a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpdel_meta_cat_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card-text .mgpde-category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpdel_cat_border',
                'selector' => '{{WRAPPER}} .mgpdel-card-text .mgpde-category a',
            ]
        );
        $this->add_control(
            'mgpdel_meta_cat_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card-text .mgpde-category a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdel_meta_cat_bgcolor',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-card-text .mgpde-category a' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgpdel_meta_cat_typography',
                'selector' => '{{WRAPPER}} .mgpdel-card-text .mgpde-category a',
            ]
        );
        $this->add_control(
            'mgpdel_meta_star',
            [
                'label' => __('Rating Style', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'mgpdel_meta_star_color',
            [
                'label' => __('Rating star Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-product-rating .wd-product-ratting i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdel_meta_starfill_color',
            [
                'label' => __('Rating star Fill Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-product-rating .wd-product-ratting .wd-product-user-ratting i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdel_meta_revtext_color',
            [
                'label' => __('Review Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} span.mgp-rating-count' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        //start advance icons style
        $this->start_controls_section(
            'mgpdel_adicons_style',
            [
                'label' => sprintf('%s %s', __('Advance icons Style', 'magical-products-display'), mpd_display_pro_only_text()),

                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'mgpdel_adicons_show' => 'yes',
                ]
            ]
        );
        if (get_option('mgppro_is_active', 'no') == 'no') {

            $this->add_control(
                'mgpdel_adicons_style_info',
                [
                    'label' => sprintf('<span style="color:red">%s</span>', __('The Section only work with pro version.', 'magical-products-display')),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
        }

        $this->add_responsive_control(
            'mgpdel_adicons_padding',
            [
                'label' => __('Icons Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} ul.xscar-advicon li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpdel_adicons_margin',
            [
                'label' => __('Icons Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} ul.xscar-advicon li i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpdel_adicons_size',
            [
                'label' => __('Icons size', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],

                ],
                'selectors' => [
                    '{{WRAPPER}} ul.xscar-advicon li i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpdel_adicons_border',
                'selector' => '{{WRAPPER}} ul.xscar-advicon li',
            ]
        );

        $this->add_control(
            'mgpdel_adicons_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} ul.xscar-advicon li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mgpdel_adicons_box_shadow',
                'selector' => '{{WRAPPER}} ul.xscar-advicon li',
            ]
        );
        $this->start_controls_tabs('mgpdel_adicons_tabs');

        $this->start_controls_tab(
            'mgpdel_adicons_normal_style',
            [
                'label' => __('Normal', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mgpdel_adicons_color',
            [
                'label' => __('Icons Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} ul.xscar-advicon li i' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'mgpdel_adicons_bg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.xscar-advicon li, {{WRAPPER}} ul.xscar-advicon li i' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'mgpdel_adicons_hover_style',
            [
                'label' => __('Hover', 'magical-products-display'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mgpdel_adicons_boxshadow',
                'selector' => '{{WRAPPER}} ul.xscar-advicon li:hover',
            ]
        );

        $this->add_control(
            'mgpdel_adicons_hcolor',
            [
                'label' => __('Icons Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.xscar-advicon li:hover i' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'mgpdel_adicons_hbg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.xscar-advicon li:hover, {{WRAPPER}} ul.xscar-advicon li i:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'mgpdel_adicons_hborder_color',
            [
                'label' => __('Border Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'mgpdel_pagination_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} ul.xscar-advicon li:hover' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();

        //start button style section
        $this->start_controls_section(
            'mgpdel_btn_style',
            [
                'label' => __('Button', 'magical-products-display'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mgpdel_btn_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-cart-btn a.button,{{WRAPPER}} .mgpdel-cart-btn a.added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpdel_btn_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-cart-btn a.button, {{WRAPPER}} .mgpdel-cart-btn a.added_to_cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgpdel_btn_typography',
                'selector' => '{{WRAPPER}} .mgpdel-cart-btn a.button, {{WRAPPER}} .mgpdel-cart-btn a.added_to_cart',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpdel_btn_border',
                'selector' => '{{WRAPPER}} .mgpdel-cart-btn a.button, {{WRAPPER}} .mgpdel-cart-btn a.added_to_cart',
            ]
        );

        $this->add_control(
            'mgpdel_btn_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-cart-btn a.button,{{WRAPPER}} .mgpdel-cart-btn a.added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mgpdel_btn_box_shadow',
                'selector' => '{{WRAPPER}} .mgpdel-cart-btn a.button,{{WRAPPER}} .mgpdel-cart-btn a.added_to_cart',
            ]
        );
        $this->add_control(
            'mgpdel_button_color',
            [
                'label' => __('Button color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs('infobox_btn_tabs');

        $this->start_controls_tab(
            'mgpdel_btn_normal_style',
            [
                'label' => __('Normal', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mgpdel_btn_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-cart-btn a.button,{{WRAPPER}} .mgpdel-cart-btn a.added_to_cart' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mgpdel_btn_bg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-cart-btn a.button,{{WRAPPER}} .mgpdel-cart-btn a.added_to_cart' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'mgpdel_btn_hover_style',
            [
                'label' => __('Hover', 'magical-products-display'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mgpdel_btnhover_boxshadow',
                'selector' => '{{WRAPPER}} .mgpdel-cart-btn a.button:hover,{{WRAPPER}} .mgpdel-cart-btn a.added_to_cart:hover',
            ]
        );

        $this->add_control(
            'mgpdel_btn_hcolor',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-cart-btn a.button:hover, {{WRAPPER}} .mgpdel-cart-btn a.button:focus,{{WRAPPER}} .mgpdel-cart-btn a.added_to_cart:hover, {{WRAPPER}} .mgpdel-cart-btn a.added_to_cart:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mgpdel_btn_hbg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-cart-btn a.button:hover, {{WRAPPER}} .mgpdel-cart-btn a.button:focus,{{WRAPPER}} .mgpdel-cart-btn a.added_to_cart:hover, {{WRAPPER}} .mgpdel-cart-btn a.added_to_cart:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mgpdel_btn_hborder_color',
            [
                'label' => __('Border Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'mgpdel_btn_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgpdel-cart-btn a.button:hover, {{WRAPPER}} .mgpdel-cart-btn a.button:focus,{{WRAPPER}} .mgpdel-cart-btn a.added_to_cart:hover, {{WRAPPER}} .mgpdel-cart-btn a.added_to_cart:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        // Products Stock Style
        $this->start_controls_section(
            'mgpdel_pstock_style',
            [
                'label'     => sprintf('%s %s', esc_html__('Products Stock Style ', 'magical-products-display'), mpd_display_pro_only_text()),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'mgpdel_stock_show' => 'yes',
                ]
            ]
        );
        if (get_option('mgppro_is_active', 'no') == 'no') {

            $this->add_control(
                'mgpdel_stock_style_info',
                [
                    'label' => sprintf('<span style="color:red">%s</span>', __('The Section only work with pro version.', 'magical-products-display')),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
        }
        $this->add_control(
            'mgpdel_pstock_text_style',
            [
                'label' => __('Text Style', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'mgpdel_pstock_text_margin',
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
            'mgpdel_pstock_text_padding',
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
            'mgpdel_pstock_text_color',
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
                'name' => 'mgpdel_pstock_text_typography',
                'selector' => '{{WRAPPER}} .mgppro-stock-stext .mgppro-total-stock, {{WRAPPER}} .mgppro-stock-stext .mgppro-available-stock',
            ]
        );
        $this->add_control(
            'mgpdel_pstock_slide_style',
            [
                'label' => __('Slide Style', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mgpdel_pstock_slide_bg',
                'label' => esc_html__('Slide Background', 'magical-products-display'),
                'types' => ['classic', 'gradient'],

                'selector' => '{{WRAPPER}} .mgppro-range1',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mgpdel_pstock_slide_fillbg',
                'label' => esc_html__('Slide Fill Background', 'magical-products-display'),
                'types' => ['classic', 'gradient'],

                'selector' => '{{WRAPPER}} .mgppro-range2',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpdel_pstock_slide_border',
                'selector' => '{{WRAPPER}} .mgppro-range1',
            ]
        );

        $this->add_control(
            'mgpdel_pstock_slide_radius',
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
            'mgpdel_pstock_slide_height',
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
            'mgpdel_attr_sec',
            [
                'label' => __('Magical Attributes', 'plugin-name'),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $this->add_control(
            'mgpdel_attr_calss',
            [
                'label' => __('Custom Class', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
        $this->add_control(
            'mgpdel_attr_id',
            [
                'label' => __('Custom ID', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'mgpdel_custom_css_sec',
            [
                'label' => __('Magical Custom CSS', 'plugin-name'),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );
        $this->add_control(
            'mgpdel_custom_css',
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
        $mgpdel_filter = $this->get_settings('mgpdel_products_filter');
        $mgpdel_products_count = $this->get_settings('mgpdel_products_count');
        $mgpdel_custom_order = $this->get_settings('mgpdel_custom_order');
        $mgpdel_grid_categories = $this->get_settings('mgpdel_grid_categories');
        $orderby = $this->get_settings('orderby');
        $order = $this->get_settings('order');


        if ($mgpdel_filter == 'best7') {
            $ptype = 'unknown';
        } else {
            $ptype = 'product';
        }
        // Query Argument
        $args = array(
            'post_type'             => $ptype,
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => $mgpdel_products_count,
        );

        switch ($mgpdel_filter) {

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
                $args['post__in'] = $settings['mgpdel_product_id'];
                break;

            case 'show_byid_manually':
                $args['post__in'] = explode(',', $settings['mgpdel_product_ids_manually']);
                break;

            default: /* Recent */
                $args['orderby']    = 'date';
                $args['order']      = 'desc';
                break;
        }

        // Custom Order
        if ($mgpdel_custom_order == 'yes') {
            $args['orderby'] = $orderby;
            $args['order'] = $order;
        }

        if (!(($mgpdel_filter == "show_byid") || ($mgpdel_filter == "show_byid_manually"))) {

            $product_cats = str_replace(' ', '', $mgpdel_grid_categories);
            if ("0" != $mgpdel_grid_categories) {
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
        $mgpdel_product_style = $this->get_settings('mgpdel_product_style');
        $mgpdel_img_position = $this->get_settings('mgpdel_img_position');
        // grid content
        $mgpdel_product_img_show = $this->get_settings('mgpdel_product_img_show');
        $mgpdel_show_title = $this->get_settings('mgpdel_show_title');
        $mgpdel_crop_title = $this->get_settings('mgpdel_crop_title');
        $mgpdel_title_tag = $this->get_settings('mgpdel_title_tag');
        $mgpdel_desc_show = $this->get_settings('mgpdel_desc_show');
        $mgpdel_crop_desc = $this->get_settings('mgpdel_crop_desc');
        $mgpdel_price_show = $this->get_settings('mgpdel_price_show');
        $mgpdel_cart_btn = $this->get_settings('mgpdel_cart_btn');
        $mgpdel_category_show = $this->get_settings('mgpdel_category_show');
        $mgpdel_ratting_show = $this->get_settings('mgpdel_ratting_show');
        $mgpdel_badge_show = $this->get_settings('mgpdel_badge_show');
        $mgpdel_content_align = $this->get_settings('mgpdel_content_align');
        $mgpdel_btn_type = $this->get_settings('mgpdel_btn_type');
        $mgpdel_card_text = $this->get_settings('mgpdel_card_text');

        //pro icons 
        if (function_exists('yith_wishlist_install')) {
            $mgpdel_wishlist_show = $settings['mgpdel_wishlist_show'];
            $mgpdel_wishlist_text = $settings['mgpdel_wishlist_text'];
        } else {
            $mgpdel_wishlist_show = ' ';
            $mgpdel_wishlist_text = ' ';
        }


        $mgpdel_share_show = $settings['mgpdel_share_show'];
        $mgpdel_share_text = $settings['mgpdel_share_text'];
        $mgpdel_qrcode_show = $settings['mgpdel_qrcode_show'];
        $mgpdel_qrcode_text = $settings['mgpdel_qrcode_text'];
        $mgpdel_video_show = $settings['mgpdel_video_show'];
        $mgpdel_video_text = $settings['mgpdel_video_text'];
        $after_text = $settings['mgpdel_badge_after_text'];
        $before_sign = $settings['mgpdel_badge_before_sign'];


        if ($mgpdel_content_align == 'center') {
            $rating_class = 'flex-center';
        } elseif ($mgpdel_content_align == 'right') {
            $rating_class = 'flex-right';
        } else {
            $rating_class = 'flex-left';
        }

        if ($settings['mgpdel_img_flip_show'] == 'yes' && (get_option('mgppro_is_active', 'no') == 'yes')) {
            $img_effects = 'no-effects';
        } else {
            $img_effects = $settings['mgpdel_img_effects'];
        }


        $mgpdel_products = new WP_Query($args);
        $mgpl_unque_num = rand('598264', '7842351');
        if ($mgpdel_products->have_posts()) :
?>

            <div <?php if ($settings['mgpdel_attr_id']) : ?> id="<?php echo esc_attr($settings['mgpdel_attr_id']); ?>" <?php endif; ?> class="mgpl-unique<?php echo esc_attr($mgpl_unque_num); ?> magical-products-list <?php echo esc_attr($settings['mgpdel_attr_calss']); ?>">
                <?php if ($settings['mgpdel_custom_css']) : ?>
                    <style>
                        <?php echo esc_html($settings['mgpdel_custom_css']); ?>
                    </style>
                <?php endif; ?>
                <div class="mgproductd mgp-list mgpde-items mgpdel-card mgpl-style<?php echo esc_attr($mgpdel_product_style); ?>">
                    <?php while ($mgpdel_products->have_posts()) : $mgpdel_products->the_post(); ?>
                        <div class="mgpde-shadow mgpde-lcard mb-4 mgpde-has-hover">
                            <div class="row mgp-img-<?php echo esc_attr($mgpdel_img_position); ?>">
                                <?php if ($mgpdel_product_img_show == 'yes') : ?>
                                    <div class="col-md-6">
                                        <div class="mgpde-card-img mgpdel-card-img mgpl-img <?php echo esc_attr($img_effects); ?>">
                                            <?php
                                            if (class_exists('WooCommerce') && $mgpdel_badge_show == 'yes') {
                                                mgproducts_display_products_badge();
                                            }

                                            if (get_option('mgppro_is_active', 'no') == 'yes') {
                                                if ($settings['mgpdel_badge_discount'] == 'percentage') {
                                                    do_action('mgppro_percent_sale_badge', $after_text);
                                                }
                                                if ($settings['mgpdel_badge_discount'] == 'number') {
                                                    do_action('mgppro_number_sale_badge', $before_sign, $after_text);
                                                }
                                            }
                                            ?>
                                            <figure>
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php
                                                    if ($settings['mgpdel_img_flip_show'] == 'yes' && (get_option('mgppro_is_active', 'no') == 'yes')) {
                                                        do_action('mgppro_flip_product_image', get_the_ID(), $settings['mgpdel_img_size']);
                                                    } else {
                                                        the_post_thumbnail($settings['mgpdel_img_size']);
                                                    }
                                                    ?>
                                                </a>
                                                <?php if ($settings['mgpdel_adicons_show'] && get_option('mgppro_is_active', 'no') == 'yes') : ?>
                                                    <div class="mgp-exicons exicons-<?php echo esc_attr($settings['mgpdel_adicons_position']); ?>">
                                                        <?php do_action('mgproducts_pro_advance_icons', $mgpdel_wishlist_show, $mgpdel_wishlist_text, $mgpdel_share_show, $mgpdel_share_text, $mgpdel_video_show, $mgpdel_video_text, $mgpdel_qrcode_show, $mgpdel_qrcode_text); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- If has products image -->
                                    <?php else :  ?>
                                        <div class="col-md-12">
                                            <!-- If no products image -->
                                        <?php endif; // End product image check 
                                        ?>
                                        <div class="mgpde-card-text mgpdel-card-text">

                                            <?php if ($mgpdel_category_show == 'yes') : ?>
                                                <div class="mgpde-meta mgpde-category">
                                                    <?php mgproducts_display_product_category(); ?>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($mgpdel_show_title == 'yes') : ?>
                                                <a class="mgpde-ptitle-link" href="<?php the_permalink(); ?>">
                                                    <?php
                                                    printf(
                                                        '<%1$s class="mgpde-ptitle">%2$s</%1$s>',
                                                        tag_escape($mgpdel_title_tag),
                                                        wp_trim_words(get_the_title(), $mgpdel_crop_title)
                                                    );
                                                    ?>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($mgpdel_price_show == 'yes' && $mgpdel_product_style == 2) : ?>
                                                <div class="mgpdel-product-price">
                                                    <?php
                                                    woocommerce_template_loop_price();
                                                    ?>
                                                </div>

                                            <?php endif; ?>
                                            <?php if ($mgpdel_ratting_show) : ?>
                                                <div class="mgpdel-product-rating">
                                                    <?php echo mgproducts_display_wc_get_rating_html(); ?>
                                                    <?php mgproducts_display_wc_rating_number(); ?>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($mgpdel_desc_show) : ?>
                                                <p><?php echo wp_trim_words(get_the_content(), $mgpdel_crop_desc, '...'); ?></p>
                                            <?php endif; ?>
                                            <?php if ($mgpdel_price_show == 'yes' && $mgpdel_product_style == 1) : ?>
                                                <div class="mgpdel-product-price mb-4">
                                                    <?php
                                                    woocommerce_template_loop_price();
                                                    ?>
                                                </div>

                                            <?php endif; ?>
                                            <?php if ($mgpdel_cart_btn == 'yes') : ?>
                                                <div class="woocommerce mgpdel-cart-btn">
                                                    <?php if ($mgpdel_btn_type == 'cart') : ?>
                                                        <?php woocommerce_template_loop_add_to_cart(); ?>
                                                    <?php else : ?>
                                                        <a class="button " href="<?php the_permalink(); ?>"><?php echo esc_html($mgpdel_card_text); ?></a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php
                                            if ($settings['mgpdel_stock_show'] && get_option('mgppro_is_active', 'no') == 'yes') {
                                                do_action(
                                                    'mgppro_products_stock',
                                                    $settings['mgpdel_total_stock_show'],
                                                    $settings['mgpdel_stock_text'],
                                                    $settings['mgpdel_total_sold_show'],
                                                    $settings['mgpdel_sold_text'],
                                                    $settings['mgpdel_stock_slide_show']
                                                );
                                            }

                                            ?>
                                        </div>
                                        </div>
                                    </div>
                            </div>
                        <?php
                    endwhile;
                    wp_reset_query();
                    wp_reset_postdata();
                        ?>
                        </div>
                </div>





    <?php
        endif;
    }
}
