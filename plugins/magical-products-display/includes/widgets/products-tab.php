<?php


class mgProducts_Tab extends \Elementor\Widget_Base
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
        return 'mg_products_tab';
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
        return __('MPD Products Tab', 'magical-products-display');
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
        return 'eicon-product-tabs';
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
        return ['mpd', 'woo', 'product', 'grid', 'tab'];
    }

    /**
     * Retrieve the list of scripts the image comparison widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends()
    {
        return [
            'bootstrap-bundle'
        ];
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
            'bootstrap-custom',
            'mgproducts-tab',
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
            // discount badge
            $percent = 'percentage';
            $number = 'number';
        } else {
            // discount badge
            $percent = 'hide2';
            $number = 'hide3';
        }

        $this->start_controls_section(
            'bsktab_cats_section',
            [
                'label' => esc_html__('Select Products Categories', 'magical-products-display'),
            ]
        );

        if (get_option('mgppro_is_active', 'no') == 'yes') {
            $repeater = new \Elementor\Repeater();
            $repeater->add_control(
                'bsktab_pcat_id',
                [
                    'label' => __('Select Product', 'magical-products-display'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'label_block' => true,
                    'options' => mgproducts_display_taxonomy_list('product_cat', 'id'),

                ]
            );
            $repeater->add_control(
                'bsktab_pcat_icon',
                [
                    'label' => __('Icon', 'magical-products-display'),
                    'description' => __('Need to active for display the icon from icon section.', 'magical-products-display'),
                    'type' => \Elementor\Controls_Manager::ICONS,
                ]
            );

            $this->add_control(
                'bsktab_rcats',
                [
                    'label' => esc_html__('Product Categories', 'magical-products-display'),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'separator' => 'before',
                    'title_field' => get_the_title('{{{ bsktab_pcat_id }}}'),
                    'fields' => $repeater->get_controls(),
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );
            $this->add_control(
                'bsktab_cats',
                [
                    'label' => esc_html__('Product Categories', 'textdomain'),
                    'type' => \Elementor\Controls_Manager::HIDDEN,
                ]
            );
        } else {
            $this->add_control(
                'bsktab_cats',
                [
                    'label' => __('Select Product Categories', 'magical-products-display'),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => mgproducts_display_taxonomy_list('product_cat', 'id'),

                ]
            );
            $this->add_control(
                'bsktab_pcount_info',
                [
                    'label' => sprintf('<span style="color:red">%s</span>', __('You can select categories more easily and change position by drag and drop in the pro version.', 'magical-products-display')),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
        }

        $this->add_control(
            'bsktab_products_count',
            [
                'label'   => __('Product Limit', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'step'    => 1,
            ]
        );
        $this->add_control(
            'mgpdeg_rownumber',
            [
                'label'   => __('Grid Column For Desktop', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '12'   => __('1 Column', 'magical-products-display'),
                    '6'  => __('2 Column', 'magical-products-display'),
                    '4'  => __('3 Column', 'magical-products-display'),
                    '3'  => __('4 Column', 'magical-products-display'),
                    '2'  => __('6 Column', 'magical-products-display'),
                ]
            ]
        );
        $this->add_control(
            'mgpdeg_rownumber_tab',
            [
                'label'   => __('Grid Column For Tab', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => '6',
                'options' => [
                    '12'   => __('1 Column', 'magical-products-display'),
                    '6'  => __('2 Column', 'magical-products-display'),
                    '4'  => __('3 Column', 'magical-products-display'),
                    '3'  => __('4 Column', 'magical-products-display'),
                    '2'  => __('6 Column', 'magical-products-display'),
                ]
            ]
        );
        $this->add_control(
            'mgpdeg_rownumber_mob',
            [
                'label'   => __('Grid Column For Mobile', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => '12',
                'options' => [
                    '12'   => __('1 Column', 'magical-products-display'),
                    '6'  => __('2 Column', 'magical-products-display'),
                    '4'  => __('3 Column', 'magical-products-display'),
                    '3'  => __('4 Column', 'magical-products-display'),
                    '2'  => __('6 Column', 'magical-products-display'),
                ]
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'mgpd_options_section',
            [
                'label' => esc_html__('Tab Options', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'mgpd_type',
            [
                'label' => esc_html__('Type', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => esc_html__('Horizontal', 'magical-products-display'),
                    'vertical' => esc_html__('Vertical', 'magical-products-display'),
                ],
            ]
        );

        $this->add_control(
            'mgpd_style',
            [
                'label' => esc_html__('Tabs Style', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '1' => esc_html__('Style One', 'magical-products-display'),
                    '2' => esc_html__('Style Two', 'magical-products-display'),
                    '3' => esc_html__('Style Three', 'magical-products-display'),
                    '4' => esc_html__('Style Four', 'magical-products-display'),
                ],
            ]
        );
        $this->add_control(
            'mgpd_animation',
            [
                'label' => esc_html__('Tabs Animation', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'slideUp',
                'options' => [
                    'none' => esc_html__('None', 'magical-products-display'),
                    'fade' => esc_html__('Fade', 'magical-products-display'),
                    'slideDown' => esc_html__('Slide Down', 'magical-products-display'),
                    'slideUp' => esc_html__('Slide Up', 'magical-products-display'),
                    'slideRight' => esc_html__('Slide Right', 'magical-products-display'),
                    'slideLeft' => esc_html__('Slide Left', 'magical-products-display'),
                ],
            ]
        );
        $this->add_control(
            'mgpd_full_width',
            [
                'label' => esc_html__('Full Width Nav', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => esc_html__('Show', 'magical-products-display'),
                'label_off' => esc_html__('Hide', 'magical-products-display'),
                'condition' => [
                    'mgpd_type' => 'horizontal',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgpd_nav_align',
            [
                'label' => esc_html__('Nav Alignment', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'magical-products-display'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'magical-products-display'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'magical-products-display'),
                        'icon' => 'eicon-text-align-right',
                    ],

                ],
                'default' => 'left',
                'condition' => [
                    'mgpd_full_width!' => 'yes',
                    'mgpd_type' => 'horizontal',
                ],


            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'mgpd_tabicon_section',
            [
                'label' => sprintf('%s %s', esc_html__('Tab Icon ', 'magical-products-display'), mpd_display_pro_only_text()),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'mgpd_icon_show',
            [
                'label' => sprintf('%s %s', esc_html__('Show Icon? ', 'magical-products-display'), mpd_display_pro_only_text()),
                'description' => sprintf('%s %s', esc_html__('Set icon from categroy select section. ', 'magical-products-display'), mpd_display_pro_only_text()),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'magical-products-display'),
                'label_off' => esc_html__('No', 'magical-products-display'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'mgpd_icon_position',
            [
                'label' => esc_html__('Icon Position', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'magical-products-display'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'magical-products-display'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'top' => [
                        'title' => esc_html__('Top', 'magical-products-display'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'magical-products-display'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'left',
                'toggle' => false,
                'label_block' => false,
                'condition' => [
                    'mgpd_icon_show' => 'yes',
                ],

            ]
        );
        $this->end_controls_section();

        // Product Content
        $this->start_controls_section(
            'mgpdeg_layout',
            [
                'label' => esc_html__('Grid Layout', 'magical-products-display'),
            ]
        );
        $this->add_control(
            'mgpdeg_product_style',
            [
                'label'   => __('Grid Style', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1'   => __('Style One', 'magical-products-display'),
                    '2'  => __('Style Two', 'magical-products-display'),
                    '3'  => __('Style Three', 'magical-products-display'),
                ]
            ]
        );
        $this->add_control(
            'mgpd_fixd_grid_height',
            [
                'label' => esc_html__('Use Fixed Grid Height', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => esc_html__('Show', 'magical-products-display'),
                'label_off' => esc_html__('Hide', 'magical-products-display'),

            ]
        );
        $this->add_responsive_control(
            'mgpdeg_grid_height',
            [
                'label' => __('Grid Height', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 2000,
                        'step' => 1,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgpde-card.mgpdeg-card' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'mgpd_fixd_grid_height' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
        // Product image
        $this->start_controls_section(
            'mgpdeg_img_section',
            [
                'label' => esc_html__('Products Image', 'magical-products-display'),
            ]
        );
        $this->add_control(
            'mgpdeg_product_img_show',
            [
                'label'     => __('Show Products image', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'mgpdeg_img_size',
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
                    'mgpdeg_product_img_show' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgpdeg_img_effects',
            [
                'label' => esc_html__('Image Hover Effects', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'mgpr-hvr-shine',
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
                    'mgpdeg_product_img_show' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgpdeg_img_flip_show',
            [
                'label' => sprintf('%s %s', esc_html__('Active Image Flip ', 'magical-products-display'), mpd_display_pro_only_text()),
                'description'     => __('Two Product images create a hover flip. You need to add gallery images to view two different product images on the product edit page.', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => ' ',
                'condition' => [
                    'mgpdeg_product_img_show' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();
        // Product Content
        $this->start_controls_section(
            'mgpdeg_content',
            [
                'label' => esc_html__('Content Settings', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mgpdeg_show_title',
            [
                'label'     => __('Show Product Title', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );
        $this->add_control(
            'mgpdeg_crop_title',
            [
                'label'   => __('Crop Title By Word', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'step'    => 1,
                'default' => 5,
                'condition' => [
                    'mgpdeg_show_title' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgpdeg_title_tag',
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
                    'mgpdeg_show_title' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgpdeg_desc_show',
            [
                'label'     => __('Show Product Description', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',

            ]
        );
        $this->add_control(
            'mgpdeg_crop_desc',
            [
                'label'   => __('Crop Description By Word', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'step'    => 1,
                'default' => 15,
                'condition' => [
                    'mgpdeg_desc_show' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'mgpdeg_price_show',
            [
                'label'     => __('Show Product Price', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );

        $this->add_control(
            'mgpdeg_cart_btn',
            [
                'label'     => __('Show button', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );

        $this->add_responsive_control(
            'mgpdeg_content_align',
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
                'default' => 'center',
                'classes' => 'flex-{{VALUE}}',
                'selectors' => [
                    '{{WRAPPER}} .mgpde-card-text.mgpdeg-card-text' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'mgpdeg_meta_section',
            [
                'label' => __('Products Meta', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
                'default' => 'no',
            ]
        );
        $this->add_control(
            'mgpdeg_badge_show',
            [
                'label'     => __('Show Sale Badge', 'magical-products-display'),
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
            'mgpdeg_category_show',
            [
                'label'     => __('Show Category', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',

            ]
        );

        $this->add_control(
            'mgpdeg_ratting_show',
            [
                'label'     => __('Show Ratting', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => ' ',

            ]
        );

        $this->end_controls_section();

        // Advance icons style 
        // start Advance icons
        $this->start_controls_section(
            'mgpdeg_adicons',
            [
                'label' => sprintf('%s %s', __('Products Advance Icons', 'magical-products-display'), mpd_display_pro_only_text()),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        if (get_option('mgppro_is_active', 'no') == 'no') {

            $this->add_control(
                'mgpdeg_adicons_info',
                [
                    'label' => sprintf('<span style="color:red">%s</span>', __('Advance Icons Section only work with pro version.', 'magical-products-display')),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $mgpdeg_adicons_default = ' ';
        } else {
            $mgpdeg_adicons_default = 'yes';
        }

        $this->add_control(
            'mgpdeg_adicons_show',
            [
                'label' => __('Advance Icons Show', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'default' => $mgpdeg_adicons_default,
            ]
        );
        $this->add_control(
            'mgpdeg_adicons_position',
            [
                'label' => __('Advance Icons Position', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'right' => __('Show Right Side', 'magical-products-display'),
                    'left' => __('Show Left Side', 'magical-products-display'),
                ],
                'default' => 'right',
                'condition' => [
                    'mgpdeg_adicons_show' => 'yes',
                ]
            ]
        );
        if (function_exists('yith_wishlist_install')) {
            $this->add_control(
                'mgpdeg_wishlist_show',
                [
                    'label' => __('Show Wishlist Icon', 'magical-products-display'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Yes', 'magical-products-display'),
                    'label_off' => __('No', 'magical-products-display'),
                    'default' => 'yes',
                    'condition' => [
                        'mgpdeg_adicons_show' => 'yes',
                    ]
                ]
            );
            $this->add_control(
                'mgpdeg_wishlist_text',
                [
                    'label'       => __('Wishlist Text', 'magical-products-display'),
                    'type'        => \Elementor\Controls_Manager::TEXT,
                    'input_type'  => 'text',
                    'placeholder' => __('Wishlist', 'magical-products-display'),
                    'default'     => __('Wishlist', 'magical-products-display'),
                    'condition' => [
                        'mgpdeg_adicons_show' => 'yes',
                    ]
                ]
            );
        }
        $this->add_control(
            'mgpdeg_share_show',
            [
                'label' => __('Show Social Share Icons', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'default' => 'yes',
                'condition' => [
                    'mgpdeg_adicons_show' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'mgpdeg_share_text',
            [
                'label'       => __('Share Text', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('Share Now', 'magical-products-display'),
                'default'     => __('Share Now', 'magical-products-display'),
                'condition' => [
                    'mgpdeg_adicons_show' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'mgpdeg_video_show',
            [
                'label' => __('Show Video Icons', 'magical-products-display'),
                'description' => __('The video icons will only be displayed when a YouTube video is available', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'default' => 'yes',
                'condition' => [
                    'mgpdeg_adicons_show' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'mgpdeg_video_text',
            [
                'label'       => __('Video Text', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('Show Video', 'magical-products-display'),
                'default'     => __('Show Video', 'magical-products-display'),
                'condition' => [
                    'mgpdeg_adicons_show' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'mgpdeg_qrcode_show',
            [
                'label' => __('Show QR Code Icons', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'default' => 'yes',
                'condition' => [
                    'mgpdeg_adicons_show' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'mgpdeg_qrcode_text',
            [
                'label'       => __('QR Code Text', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('QR Code', 'magical-products-display'),
                'default'     => __('QR Code', 'magical-products-display'),
                'condition' => [
                    'mgpdeg_adicons_show' => 'yes',
                ]
            ]
        );
        $this->end_controls_section();

        // cart button style
        $this->start_controls_section(
            'mgpdeg_card_button',
            [
                'label' => __('Cart Button', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'mgpdeg_cart_btn' => 'yes',
                ]

            ]
        );
        $this->add_control(
            'mgpdeg_btn_type',
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
            'mgpdeg_card_text',
            [
                'label'       => __('Button Text', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('View details', 'magical-products-display'),
                'default'     => __('View details', 'magical-products-display'),
                'condition' => [
                    'mgpdeg_btn_type' => 'view',
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
            'mgpd_navwrapper_section',
            [
                'label' => esc_html__('Nav Wrapper', 'magical-products-display'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'bsktabnav_wrapper_bg',
                'label' => esc_html__('Background', 'magical-products-display'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .bsk-tabs ul.nav.nav-tabs',
            ]
        );
        $this->add_responsive_control(
            'bsktabnav_wrapper_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bsk-tabs .mpdtab-nav-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'bsktabnav_wrapper_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bsk-tabs ul.nav.nav-tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'bsktabnav_wrapper_border',
                'selector' => '{{WRAPPER}} .bsk-tabs ul.nav.nav-tabs',
            ]
        );

        $this->add_responsive_control(
            'mgpd_navwrapper_border_radius',
            [
                'label' => esc_html__('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bsk-tabs ul.nav.nav-tabs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mgpd_navwrapper_box_shadow',
                'label' => esc_html__('Box Shadow', 'magical-products-display'),
                'selector' => '{{WRAPPER}} .bsk-tabs ul.nav.nav-tabs',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'mgpd_navitems_style',
            [
                'label'     => esc_html__('Nav Items', 'magical-products-display'),
                'tab'     => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'         => 'mgpd_tabitems_typography',
                'selector'     => '{{WRAPPER}} .bsk-tabs ul.nav-tabs li a',
            ]
        );
        $this->add_responsive_control(
            'mgpd_tabitems_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bsk-tabs ul.nav-tabs li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpd_tabitems_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bsk-tabs ul.nav-tabs li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'mgpd_navitems_style_tabs'
        );
        $this->start_controls_tab(
            'bsktabnav_items_normal_tab',
            [
                'label' => esc_html__('Normal', 'magical-products-display'),
            ]
        );
        $this->add_control(
            'bsktabnav_items_normal_textcolor',
            [
                'label'         => esc_html__('Text Color', 'magical-products-display'),
                'type'         => \Elementor\Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .bsk-tabs ul.nav-tabs li a' => 'color: {{VALUE}};',

                ],
            ]
        );
        /*
        $this->add_control(
            'bsktabnav_items_normal_iconcolor', [
                'label'		 =>esc_html__( 'Icon Color', 'magical-products-display' ),
                'type'		 => \Elementor\Controls_Manager::COLOR,
                'selectors'	 => [
                    '{{WRAPPER}} .bsk-tabs ul.nav-tabs li a span i' => 'color: {{VALUE}};',
                ],
                'condition' => [
					'mgpd_icon_show' => 'yes',
				],
            ]
        );
*/
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'bsktabnav_items_normal_bg',
                'label' => esc_html__('Background', 'magical-products-display'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .bsk-tabs ul.nav-tabs li a,{{WRAPPER}} .mpdtabs-style4 .nav-tabs li a.active:after',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'bsktabnav_items_normal_border',
                'label' => esc_html__('Border', 'magical-products-display'),
                'selector' => '{{WRAPPER}} .bsk-tabs ul.nav-tabs li a',
            ]
        );

        $this->add_control(
            'bsktabnav_items_normal_border_radius',
            [
                'label' => esc_html__('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .bsk-tabs ul.nav-tabs li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'bsktabnav_items_normal_bshadow',
                'label' => esc_html__('Box Shadow', 'magical-products-display'),
                'selector' => '{{WRAPPER}} .bsk-tabs ul.nav-tabs li a',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'mgpd_navitems_style_active_tab',
            [
                'label' => esc_html__('Active', 'magical-products-display'),
            ]
        );
        $this->add_control(
            'bsktabnav_items_active_textcolor',
            [
                'label'         => esc_html__('Text Color', 'magical-products-display'),
                'type'         => \Elementor\Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .bsk-tabs ul.nav-tabs li a.active' => 'color: {{VALUE}};',

                ],
            ]
        );
        /*
        $this->add_control(
            'bsktabnav_items_active_iconcolor', [
                'label'		 =>esc_html__( 'Icon Color', 'magical-products-display' ),
                'type'		 => \Elementor\Controls_Manager::COLOR,
                'selectors'	 => [
                    '{{WRAPPER}} .bsk-tabs ul.nav-tabs li a.active span i' => 'color: {{VALUE}};',
                ],
                'condition' => [
					'mgpd_icon_show' => 'yes',
				],
            ]
        );
*/
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'bsktabnav_items_active_bg',
                'label' => esc_html__('Background', 'magical-products-display'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .bsk-tabs ul.nav-tabs li a.active, {{WRAPPER}} .mpdtabs-style1 .nav-tabs li a.active:after',
            ]
        );
        $this->add_control(
            'bsktabnav_items_active_arrowcolor',
            [
                'label'         => esc_html__('Extra Arrow Color', 'magical-products-display'),
                'type'         => \Elementor\Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .mpdtabs-style2 .nav-tabs li a.active, {{WRAPPER}} .mpdtabs-style2 .nav-tabs li a.active:hover' => 'border-top-color: {{VALUE}};',
                    '{{WRAPPER}}  .mpdtabs-style1 .nav-tabs li a:after' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}}  .mpdtabs-style3 .nav-tabs li a.active:after' => 'border-top-color: {{VALUE}};',
                ],


            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'bsktabnav_items_active_border',
                'label' => esc_html__('Border', 'magical-products-display'),
                'selector' => '{{WRAPPER}} .bsk-tabs ul.nav-tabs li a.active',
            ]
        );

        $this->add_control(
            'bsktabnav_items_active_border_radius',
            [
                'label' => esc_html__('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .bsk-tabs ul.nav-tabs li a.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'bsktabnav_items_active_bshadow',
                'label' => esc_html__('Box Shadow', 'magical-products-display'),
                'selector' => '{{WRAPPER}} .bsk-tabs ul.nav-tabs li a.active',
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'mgpdeg_style',
            [
                'label' => __('Grid style', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_responsive_control(
            'mgpdeg_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpdeg_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mgpdeg_bg_color',
                'label' => esc_html__('Background', 'magical-products-display'),
                'types' => ['classic', 'gradient'],

                'selector' => '{{WRAPPER}} .mgpdeg-card',
            ]
        );

        $this->add_control(
            'mgpdeg_border_radius',
            [
                'label' => __('Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpdeg_content_border',
                'selector' => '{{WRAPPER}} .mgpdeg-card',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mgpdeg_content_shadow',
                'selector' => '{{WRAPPER}} .mgpdeg-card',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'mgpdeg_img_style',
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
                    '{{WRAPPER}} .mgpdeg-card-img figure img' => 'flex: 0 0 {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',

                ],
            ]
        );

        $this->add_control(
            'mgpdeg_img_auto_height',
            [
                'label' => __('Image auto height', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'magical-products-display'),
                'label_off' => __('Off', 'magical-products-display'),
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'mgpdeg_img_height',
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
                    'mgpdeg_img_auto_height!' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card-img figure img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdeg_imgbg_height',
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
                    'mgpdeg_img_auto_height!' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card-img figure' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mgpdeg_img_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card-img, {{WRAPPER}} .mgpdeg-card-img figure img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpdeg_img_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card-img figure' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mgpdeg_img_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card-img figure img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mgpdeg_img_bgcolor',
                'label' => esc_html__('Background', 'magical-products-display'),
                //'types' => [ 'classic', 'gradient' ],

                'selector' => '{{WRAPPER}} .mgpdeg-card-img, {{WRAPPER}} .mgpdeg-card-img figure img',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpdeg_img_border',
                'selector' => '{{WRAPPER}} .mgpdeg-card-img figure img',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'mgpdeg_desc_style',
            [
                'label' => __('Product Title', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mgpdeg_title_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card .mgpde-ptitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpdeg_title_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card .mgpde-ptitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdeg_title_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card .mgpde-ptitle' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdeg_title_bgcolor',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card .mgpde-ptitle' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdeg_descb_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card .mgpde-ptitle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgpdeg_title_typography',
                'selector' => '{{WRAPPER}} .mgpdeg-card .mgpde-ptitle',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'mgpdeg_description_style',
            [
                'label' => __('Description', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'mgpdeg_desc_show' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'mgpdeg_description_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card-text p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpdeg_description_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card-text p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdeg_description_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card-text p' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdeg_description_bgcolor',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card-text p' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdeg_description_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card-text p' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgpdeg_description_typography',
                'selector' => '{{WRAPPER}} .mgpdeg-card-text p',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'mgpdeg_meta_style',
            [
                'label' => __('Products Meta', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'mgpdeg_meta_badge',
            [
                'label' => __('Products Sale Badge', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'mgpdeg_meta_badge_margin',
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
            'mgpdeg_meta_badge_padding',
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
            'mgpdeg_meta_badge_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgp-display-badge' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdeg_meta_badge_bgcolor',
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
                'name' => 'mgpdeg_meta_badge_typography',
                'selector' => '{{WRAPPER}} .mgp-display-badge',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpdeg_badge_border',
                'selector' => '{{WRAPPER}} .mgp-display-badge',
            ]
        );

        $this->add_control(
            'mgpdeg_badge_border_radius',
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

        //Category Style
        $this->add_control(
            'mgpdeg_meta_cat',
            [
                'label' => __('Category style', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'mgpdeg_meta_cat_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card-text .mgpde-category a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdeg_meta_cat_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-card-text .mgpde-category a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgpdeg_meta_cat_typography',
                'selector' => '{{WRAPPER}} .mgpdeg-card-text .mgpde-category a',
            ]
        );
        $this->add_control(
            'mgpdeg_meta_star',
            [
                'label' => __('Rating Style', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'mgpdeg_meta_star_color',
            [
                'label' => __('Rating star Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-product-rating .wd-product-ratting i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdeg_meta_starfill_color',
            [
                'label' => __('Rating star Fill Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-product-rating .wd-product-ratting .wd-product-user-ratting i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdeg_meta_star_textcolor',
            [
                'label' => __('Rating text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mg-rating-out, {{WRAPPER}} .mg-rating-out span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Start Advance icons Style
        $this->start_controls_section(
            'mgpdeg_adicons_style',
            [
                'label' => sprintf('%s %s', __('Advance icons Style', 'magical-products-display'), mpd_display_pro_only_text()),

                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'mgpdeg_adicons_show' => 'yes',
                ]
            ]
        );
        if (get_option('mgppro_is_active', 'no') == 'no') {

            $this->add_control(
                'mgpdeg_adicons_style_info',
                [
                    'label' => sprintf('<span style="color:red">%s</span>', __('The Section only work with pro version.', 'magical-products-display')),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
        }

        $this->add_responsive_control(
            'mgpdeg_adicons_padding',
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
            'mgpdeg_adicons_margin',
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
            'mgpdeg_adicons_size',
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
                'name' => 'mgpdeg_adicons_border',
                'selector' => '{{WRAPPER}} ul.xscar-advicon li',
            ]
        );

        $this->add_control(
            'mgpdeg_adicons_border_radius',
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
                'name' => 'mgpdeg_adicons_box_shadow',
                'selector' => '{{WRAPPER}} ul.xscar-advicon li',
            ]
        );
        $this->start_controls_tabs('mgpdeg_adicons_tabs');

        $this->start_controls_tab(
            'mgpdeg_adicons_normal_style',
            [
                'label' => __('Normal', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mgpdeg_adicons_color',
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
            'mgpdeg_adicons_bg_color',
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
            'mgpdeg_adicons_hover_style',
            [
                'label' => __('Hover', 'magical-products-display'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mgpdeg_adicons_boxshadow',
                'selector' => '{{WRAPPER}} ul.xscar-advicon li:hover',
            ]
        );

        $this->add_control(
            'mgpdeg_adicons_hcolor',
            [
                'label' => __('Icons Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.xscar-advicon li:hover i' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'mgpdeg_adicons_hbg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.xscar-advicon li:hover, {{WRAPPER}} ul.xscar-advicon li i:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'mgpdeg_adicons_hborder_color',
            [
                'label' => __('Border Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'mgpdeg_pagination_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} ul.xscar-advicon li:hover' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'mgpdeg_btn_style',
            [
                'label' => __('Button', 'magical-products-display'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mgpdeg_btn_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-cart-btn a.button,{{WRAPPER}} .mgpdeg-cart-btn a.added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mgpdeg_btn_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-cart-btn a.button,{{WRAPPER}} .mgpdeg-cart-btn a.added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgpdeg_btn_typography',
                'selector' => '{{WRAPPER}} .mgpdeg-cart-btn a.added_to_cart,{{WRAPPER}} .mgpdeg-cart-btn a.button',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mgpdeg_btn_border',
                'selector' => '{{WRAPPER}} .mgpdeg-cart-btn a.added_to_cart,{{WRAPPER}} .mgpdeg-cart-btn a.button',
            ]
        );

        $this->add_control(
            'mgpdeg_btn_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-cart-btn a.button,{{WRAPPER}} .mgpdeg-cart-btn a.added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mgpdeg_btn_box_shadow',
                'selector' => '{{WRAPPER}} .mgpdeg-cart-btn a.button,{{WRAPPER}} .mgpdeg-cart-btn a.added_to_cart',
            ]
        );
        $this->add_control(
            'mgpdeg_button_color',
            [
                'label' => __('Button color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs('infobox_btn_tabs');

        $this->start_controls_tab(
            'mgpdeg_btn_normal_style',
            [
                'label' => __('Normal', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mgpdeg_btn_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-cart-btn a.button,{{WRAPPER}} .mgpdeg-cart-btn a.added_to_cart' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mgpdeg_btn_bg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-cart-btn a.button,{{WRAPPER}} .mgpdeg-cart-btn a.added_to_cart' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'mgpdeg_btn_hover_style',
            [
                'label' => __('Hover', 'magical-products-display'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mgpdeg_btnhover_boxshadow',
                'selector' => '{{WRAPPER}} .mgpdeg-cart-btn a.button:hover,{{WRAPPER}} .mgpdeg-cart-btn a.added_to_cart:hover',
            ]
        );

        $this->add_control(
            'mgpdeg_btn_hcolor',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-cart-btn a.button:hover, {{WRAPPER}} .mgpdeg-cart-btn a.button:focus,{{WRAPPER}} .mgpdeg-cart-btn a.added_to_cart:hover, {{WRAPPER}} .mgpdeg-cart-btn a.added_to_cart:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mgpdeg_btn_hbg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-cart-btn a.button:hover, {{WRAPPER}} .mgpdeg-cart-btn a.button:focus,{{WRAPPER}} .mgpdeg-cart-btn a.added_to_cart:hover, {{WRAPPER}} .mgpdeg-cart-btn a.added_to_cart:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mgpdeg_btn_hborder_color',
            [
                'label' => __('Border Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'mgpdeg_btn_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgpdeg-cart-btn a.button:hover, {{WRAPPER}} .mgpdeg-cart-btn a.button:focus,{{WRAPPER}} .mgpdeg-cart-btn a.added_to_cart:hover, {{WRAPPER}} .mgpdeg-cart-btn a.added_to_cart:focus' => 'border-color: {{VALUE}};',
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
            'mgpd_attr_sec',
            [
                'label' => __('Magical Attributes', 'plugin-name'),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $this->add_control(
            'mgpd_attr_calss',
            [
                'label' => __('Custom Class', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
        $this->add_control(
            'mgpd_attr_id',
            [
                'label' => __('Custom ID', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'mgpd_custom_css_sec',
            [
                'label' => __('Magical Custom CSS', 'plugin-name'),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );
        $this->add_control(
            'mgpd_custom_css',
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
        $bsktab_rcats = $this->get_settings('bsktab_rcats');



        if (get_option('mgppro_is_active', 'no') == 'yes') {
            $bsktab_cats = $bsktab_rcats;
        } else {
            $bsktab_cats = $this->get_settings('bsktab_cats');
        }


        $mgpdeg_product_img_show = $this->get_settings('mgpdeg_product_img_show');
        $mgpdeg_badge_show    = $settings['mgpdeg_badge_show'];
        $mgpdeg_rownumber    = $settings['mgpdeg_rownumber'];
        $bsktab_products_count    = $settings['bsktab_products_count'];
        $mgpdeg_cart_btn   = $settings['mgpdeg_cart_btn'];
        $mgpdeg_product_style   = $settings['mgpdeg_product_style'];
        $mgpdeg_btn_type      = $settings['mgpdeg_btn_type'];
        $mgpdeg_card_text     = $settings['mgpdeg_card_text'];

        //advance Icons

        //pro icons 
        if (function_exists('yith_wishlist_install')) {
            $mgpdeg_wishlist_show = $settings['mgpdeg_wishlist_show'];
            $mgpdeg_wishlist_text = $settings['mgpdeg_wishlist_text'];
        } else {
            $mgpdeg_wishlist_show = ' ';
            $mgpdeg_wishlist_text = ' ';
        }


        $mgpdeg_share_show = $settings['mgpdeg_share_show'];
        $mgpdeg_share_text = $settings['mgpdeg_share_text'];
        $mgpdeg_qrcode_show = $settings['mgpdeg_qrcode_show'];
        $mgpdeg_qrcode_text = $settings['mgpdeg_qrcode_text'];
        $mgpdeg_video_show = $settings['mgpdeg_video_show'];
        $mgpdeg_video_text = $settings['mgpdeg_video_text'];
        $after_text = $settings['mgpdeg_badge_after_text'];
        $before_sign = $settings['mgpdeg_badge_before_sign'];

        if ($settings['mgpdeg_img_flip_show'] == 'yes' && (get_option('mgppro_is_active', 'no') == 'yes')) {
            $img_effects = 'no-effects';
        } else {
            $img_effects = $settings['mgpdeg_img_effects'];
        }

        $mgpd_rand = rand(253195, 56914658);

        if ($bsktab_cats) :
?>

            <div class="bsk-tabs no-load bsk-shadow mpdtabs-style<?php echo esc_attr($settings['mgpd_style']); ?> bsk-tab-<?php echo esc_attr($settings['mgpd_type']); ?>">

                <?php
                // if( $settings['mgpd_type'] == 'horizontal'):
                ?>

                <!-- Horijontal tab start -->
                <?php if ($settings['mgpd_type'] == 'vertical') : ?>
                    <div class="row">
                        <div class="col-md-3">
                            <!-- Horijontal tab end -->
                        <?php endif; ?>


                        <div class="mpdtab-nav-wrap bsknav-<?php if ($settings['mgpd_full_width'] == 'yes') : ?>full<?php else : ?>fit navalign-<?php echo esc_attr($settings['mgpd_nav_align']); ?><?php endif; ?>">
                            <ul class="nav nav-tabs <?php if ($settings['mgpd_full_width'] == 'yes' && $settings['mgpd_type'] == 'horizontal') : ?>nav-justified<?php endif; ?>" id="myTab" role="tablist">

                                <?php
                                foreach ($bsktab_cats as $index => $cats_id) :

                                    if (get_option('mgppro_is_active', 'no') == 'yes') {
                                        $cats_icon = isset($cats_id['bsktab_pcat_icon']) ? $cats_id['bsktab_pcat_icon'] : '';
                                        $cats_id = isset($cats_id['bsktab_pcat_id']) ? $cats_id['bsktab_pcat_id'] : '';
                                    } else {
                                        $cats_icon = '';
                                    }
                                    //check category Id
                                    if ($cats_id) :
                                        $cat_info = get_term_by('id', $cats_id, 'product_cat');
                                        if ($index == 0) {
                                            $bsklink_class = 'nav-link active';
                                        } else {
                                            $bsklink_class = 'nav-link';
                                        }
                                        $term_name = empty($cat_info->name) ? __('Select Category', 'magical-products-display') : $cat_info->name;

                                ?>

                                        <li class="nav-item" role="presentation">
                                            <a class="<?php echo esc_attr($bsklink_class); ?>" id="tab-<?php echo esc_attr($mgpd_rand . $index); ?>" data-bs-toggle="tab" data-bs-target="#bsktab<?php echo esc_attr($mgpd_rand . $index); ?>" href="#" role="tab" aria-controls="bsktab<?php echo esc_attr($mgpd_rand . $index); ?>" aria-selected="<?php if ($index == 0) : ?>true<?php else : ?>false<?php endif; ?>">
                                                <?php if ($settings['mgpd_icon_show'] == 'yes' && !empty($cats_icon) && ($settings['mgpd_icon_position'] == 'left' || $settings['mgpd_icon_position'] == 'top')) :
                                                ?>
                                                    <span class="mpdtabs-icon-<?php echo esc_attr($settings['mgpd_icon_position']); ?>">
                                                        <?php \Elementor\Icons_Manager::render_icon($cats_icon, ['aria-hidden' => 'true']); ?>
                                                    </span>
                                                <?php endif;
                                                ?>
                                                <span><?php echo esc_html($term_name); ?></span>
                                                <?php if ($settings['mgpd_icon_show'] == 'yes' && !empty($cats_icon) && ($settings['mgpd_icon_position'] == 'right' || $settings['mgpd_icon_position'] == 'bottom')) : ?>
                                                    <span class="mpdtabs-icon-<?php echo esc_attr($settings['mgpd_icon_position']); ?>">
                                                        <?php \Elementor\Icons_Manager::render_icon($cats_icon, ['aria-hidden' => 'true']); ?>
                                                    </span>
                                                <?php endif;  ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                            </ul>
                        </div>


                        <!-- Horijontal tab start -->
                        <?php if ($settings['mgpd_type'] == 'vertical') : ?>
                        </div>
                        <div class="col-md-9">
                            <!-- Horijontal tab end -->
                        <?php endif; ?>
                        <?php $mgp_unque_num = rand('8652397', '5832471'); ?>
                        <div class="tab-content mpdtab-content">
                            <?php if ($settings['mgpd_custom_css']) : ?>
                                <style>
                                    <?php echo esc_html($settings['mgpd_custom_css']); ?>
                                </style>
                            <?php endif; ?>
                            <?php
                            foreach ($bsktab_cats as $index => $cats_id) :
                                if (get_option('mgppro_is_active', 'no') == 'yes') {
                                    $cats_id = isset($cats_id['bsktab_pcat_id']) ? $cats_id['bsktab_pcat_id'] : '';
                                }

                                if ($cats_id) :
                                    $pcat_obj = get_term_by('id', $cats_id, 'product_cat');
                                    $pcat_slug = empty($pcat_obj->slug) ? null : $pcat_obj->slug;
                            ?>
                                    <div id="bsktab<?php echo esc_attr($mgpd_rand . $index); ?>" class="tab-pane fade in <?php if ($index == 0) : ?>show active<?php endif; ?>" role="tabpanel" aria-labelledby="home-tab">
                                        <div <?php if ($settings['mgpd_attr_id']) : ?> id="<?php echo esc_attr($settings['mgpd_attr_id']); ?>" <?php endif; ?> class="mgp-unique<?php echo esc_attr($mgp_unque_num); ?> mgproductd mgpde-items style<?php echo esc_attr($mgpdeg_product_style); ?> mgproductd-grid <?php echo esc_attr($settings['mgpd_attr_calss']); ?>">

                                            <div class="row">


                                                <?php
                                                $args = array(
                                                    'post_type'             => 'product',
                                                    'post_status'           => 'publish',
                                                    'ignore_sticky_posts'   => 1,
                                                    'posts_per_page'        => $bsktab_products_count,
                                                    'tax_query' => array(
                                                        array(
                                                            'taxonomy' => 'product_cat',
                                                            'field'    => 'slug',
                                                            'terms'    => $pcat_slug,
                                                        ),
                                                    ),
                                                );

                                                $mgpteb_item_products = new WP_Query($args);
                                                if ($mgpteb_item_products->have_posts()) :
                                                    while ($mgpteb_item_products->have_posts()) : $mgpteb_item_products->the_post();
                                                ?>
                                                        <div class="col-lg-<?php echo esc_attr($mgpdeg_rownumber); ?> col-md-<?php echo esc_attr($settings['mgpdeg_rownumber_tab']); ?> col-sm-<?php echo esc_attr($settings['mgpdeg_rownumber_mob']); ?>">
                                                            <div class="mgpde-shadow mgpde-card mgpdeg-card mb-4 mgpde-has-hover">
                                                                <?php if ($mgpdeg_product_img_show == 'yes') : ?>
                                                                    <div class="mgpde-card-img mgpdeg-card-img <?php echo esc_attr($img_effects); ?>">
                                                                        <?php
                                                                        if (class_exists('WooCommerce') && $mgpdeg_badge_show == 'yes') {
                                                                            mgproducts_display_products_badge(get_the_ID());
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
                                                                                <?php
                                                                                if ($settings['mgpdeg_img_flip_show'] == 'yes' && (get_option('mgppro_is_active', 'no') == 'yes')) {
                                                                                    do_action('mgppro_flip_product_image', get_the_ID(), $settings['mgpdeg_img_size']);
                                                                                } else {
                                                                                    the_post_thumbnail($settings['mgpdeg_img_size']);
                                                                                }
                                                                                ?>
                                                                            </a>
                                                                            <?php if ($settings['mgpdeg_adicons_show'] && get_option('mgppro_is_active', 'no') == 'yes') : ?>
                                                                                <div class="mgp-exicons exicons-<?php echo esc_attr($settings['mgpdeg_adicons_position']); ?>">
                                                                                    <?php do_action('mgproducts_pro_advance_icons', $mgpdeg_wishlist_show, $mgpdeg_wishlist_text, $mgpdeg_share_show, $mgpdeg_share_text, $mgpdeg_video_show, $mgpdeg_video_text, $mgpdeg_qrcode_show, $mgpdeg_qrcode_text); ?>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </figure>
                                                                        <?php if ($mgpdeg_cart_btn == 'yes' && $mgpdeg_product_style == '2') : ?>
                                                                            <div class="woocommerce mgpdeg-cart-btn">
                                                                                <?php if ($mgpdeg_btn_type == 'cart') : ?>
                                                                                    <?php woocommerce_template_loop_add_to_cart(); ?>
                                                                                <?php else : ?>
                                                                                    <a class="button " href="<?php the_permalink(); ?>"><?php echo esc_html($mgpdeg_card_text); ?></a>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <?php $this->products_content($settings); ?>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    endwhile;
                                                    wp_reset_query();
                                                    wp_reset_postdata();
                                                    ?>
                                                <?php endif; ?>
                                            </div>

                                        </div>
                                    </div>


                                <?php endif; ?>
                            <?php endforeach; ?>

                        </div>

                        <!-- Horijontal tab start -->
                        <?php if ($settings['mgpd_type'] == 'vertical') : ?>
                        </div>
                    </div>
                    <!-- Horijontal tab end -->
                <?php endif; ?>



            </div>
        <?php else : ?>
            <div class="alert alert-danger text-center">
                <?php echo esc_html('Please select products categories for display the Porducts.'); ?>
            </div>
        <?php endif; //Check tab item 
        ?>

    <?php
    }


    // products content 

    public function products_content($settings)
    {
        global $product;
        $rating_count = $product->get_rating_count();
        $mgpdeg_product_style = $settings['mgpdeg_product_style'];
        $mgpdeg_show_title = $settings['mgpdeg_show_title'];
        $mgpdeg_crop_title = $settings['mgpdeg_crop_title'];
        $mgpdeg_title_tag  = $settings['mgpdeg_title_tag'];
        $mgpdeg_desc_show  = $settings['mgpdeg_desc_show'];
        $mgpdeg_crop_desc  = $settings['mgpdeg_crop_desc'];
        $mgpdeg_price_show = $settings['mgpdeg_price_show'];
        $mgpdeg_cart_btn   = $settings['mgpdeg_cart_btn'];
        $mgpdeg_category_show = $settings['mgpdeg_category_show'];
        $mgpdeg_ratting_show  = $settings['mgpdeg_ratting_show'];
        $mgpdeg_badge_show    = $settings['mgpdeg_badge_show'];
        $mgpdeg_content_align = $settings['mgpdeg_content_align'];
        $mgpdeg_btn_type      = $settings['mgpdeg_btn_type'];
        $mgpdeg_card_text     = $settings['mgpdeg_card_text'];
    ?>
        <div class="mgpde-card-text mgpdeg-card-text mgp-text-style<?php echo esc_attr($mgpdeg_product_style); ?>">
            <?php if ($mgpdeg_category_show == 'yes' && $mgpdeg_product_style != '2') : ?>
                <div class="mgpde-meta mgpde-category">
                    <?php mgproducts_display_product_category(); ?>
                </div>
            <?php endif; ?>
            <?php if ($mgpdeg_ratting_show && $mgpdeg_product_style == '2') : ?>
                <div class="mg-rating-out">
                    <?php echo mgproducts_display_wc_get_rating_html(); ?>
                    <?php mgproducts_display_wc_rating_number(); ?>
                </div>
            <?php endif; ?>
            <?php if ($mgpdeg_show_title == 'yes') : ?>
                <a class="mgpde-ptitle-link" href="<?php the_permalink(); ?>">
                    <?php
                    printf(
                        '<%1$s class="mgpde-ptitle">%2$s</%1$s>',
                        tag_escape($mgpdeg_title_tag),
                        wp_trim_words(get_the_title(), $mgpdeg_crop_title)
                    );
                    ?>
                </a>
            <?php endif; ?>
            <?php if ($mgpdeg_category_show == 'yes' && $mgpdeg_product_style == '2') : ?>
                <div class="mgpde-meta mgpde-category">
                    <?php mgproducts_display_product_category(); ?>
                </div>
            <?php endif; ?>
            <?php if ($mgpdeg_ratting_show && $mgpdeg_product_style != '2') : ?>
                <div class="mg-rating-out">
                    <?php echo mgproducts_display_wc_get_rating_html(); ?>
                    <?php mgproducts_display_wc_rating_number(); ?>
                </div>
            <?php endif; ?>
            <?php if ($mgpdeg_desc_show) : ?>
                <p><?php echo wp_trim_words(get_the_content(), $mgpdeg_crop_desc, '...'); ?></p>
            <?php endif; ?>
            <?php if ($mgpdeg_price_show == 'yes' && $mgpdeg_product_style != '3') : ?>
                <div class="mgpdeg-product-price mb-2">
                    <?php woocommerce_template_loop_price(); ?>
                </div>
            <?php endif; ?>
            <?php if ($mgpdeg_cart_btn == 'yes' && $mgpdeg_product_style == '1') : ?>
                <div class="woocommerce mgpdeg-cart-btn">
                    <?php if ($mgpdeg_btn_type == 'cart') : ?>
                        <?php woocommerce_template_loop_add_to_cart(); ?>
                    <?php else : ?>
                        <a class="button " href="<?php the_permalink(); ?>"><?php echo esc_html($mgpdeg_card_text); ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if (($mgpdeg_price_show == 'yes' ||  $mgpdeg_cart_btn == 'yes')  && $mgpdeg_product_style == '3') : ?>
                <div class="mgpdeg-price-btn mb-2 mt-2">
                    <?php
                    if ($mgpdeg_price_show == 'yes') {
                        woocommerce_template_loop_price();
                    }
                    ?>
                    <?php if ($mgpdeg_cart_btn == 'yes') : ?>
                        <div class="woocommerce mgpdeg-cart-link">
                            <?php if ($mgpdeg_btn_type == 'cart') : ?>
                                <?php woocommerce_template_loop_add_to_cart(); ?>
                            <?php else : ?>
                                <a class="button " href="<?php the_permalink(); ?>"><?php echo esc_html($mgpdeg_card_text); ?></a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php
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
    } // products content






}
