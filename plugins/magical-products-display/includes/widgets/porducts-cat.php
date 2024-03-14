<?php


class mgProducts_cats extends \Elementor\Widget_Base
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
        return 'mg_products_cat';
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
        return __('MPD Products Categories', 'magical-products-display');
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
        return 'eicon-column';
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
        return ['mpd', 'porduct', 'categories', 'woo', 'category'];
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

        $this->start_controls_section(
            'mgpds_cats',
            [
                'label' => esc_html__('Products Categories Grid', 'magical-products-display'),
            ]
        );
        if (get_option('mgppro_is_active', 'no') == 'no') {

            $this->add_control(
                'mgpdeg_pcount_info',
                [
                    'label' => sprintf('<span style="color:red">%s</span>', __('You can select categories more easily and change position by drag and drop also add images easily in the pro version.', 'magical-products-display')),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
        }
        if (get_option('mgppro_is_active', 'no') == 'yes') {
            $repeater = new \Elementor\Repeater();
            $repeater->add_control(
                'mpdac_pcat_id',
                [
                    'label' => __('Select Product', 'magical-products-display'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'label_block' => true,
                    'options' => mgproducts_display_taxonomy_list('product_cat', 'id'),

                ]
            );
            $repeater->add_control(
                'mpdc_icat_img',
                [
                    'label' => __('Instant Category Image', 'magical-products-display'),
                    'description' => __('You can also set image from Product category edit page.', 'magical-products-display'),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                ]
            );

            $this->add_control(
                'mgpd_rcats',
                [
                    'label' => esc_html__('Content', 'magical-products-display'),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'separator' => 'before',
                    'title_field' => get_the_title('{{ mpdac_product_cat_id }}'),
                    'fields' => $repeater->get_controls(),
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );
        } else {
            $this->add_control(
                'mgpd_cats',
                [
                    'label' => __('Select Product Categories', 'magical-products-display'),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => mgproducts_display_taxonomy_list('product_cat', 'id'),

                ]
            );
        }


        $this->add_control(
            'mgpd_cats_column',
            [
                'label'   => __('Grid Column', 'magical-products-display'),
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
            'mgpd_cats_style',
            [
                'label'   => __('Style', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'style1',
                'options' => [
                    'style1'   => __('Style One', 'magical-products-display'),
                    'style2'  => __('Style Two', 'magical-products-display'),
                ]
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'mpdc_catdimg',
            [
                'label' => __('Default Categories Image', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'mpdc_img_info',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => __('If you want to add a unique image for every category then please go products category and add an image by edit. ', 'magical-products-display'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );
        $this->add_control(
            'mpdc_catimg_show',
            [
                'label' => __('Show Categories Images?', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'yes' => __('Yes', 'magical-products-display'),
                'no' => __('No', 'magical-products-display'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'mpdc_default_img',
            [
                'label' => __('Choose Default Image', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'medium_large',
                'separator' => 'none',
                'exclude' => [
                    'full',
                    'custom',
                    'large',
                    'shop_catalog',
                    'shop_single',
                    'shop_thumbnail'
                ],

            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'mpdc_cat_content',
            [
                'label' => __('Content', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'mpdc_desc_info',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => __('You need to add category description by products category edit.', 'magical-products-display'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );
        $this->add_control(
            'mpdc_catdes_show',
            [
                'label'     => __('Show Categories Description', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'

            ]
        );
        $this->add_control(
            'mpdc_catdes_crop',
            [
                'label'   => __('Crop Description By Word', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'step'    => 1,
                'default' => 10,
                'condition' => [
                    'mpdc_catdes_show' => 'yes',
                ]

            ]
        );

        $this->add_responsive_control(
            'text_align',
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
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-item' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'mpdc_pcount',
            [
                'label' => sprintf('%s %s', __('Products Count', 'magical-products-display'), mpd_display_pro_only_text()),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        if (get_option('mgppro_is_active', 'no') == 'no') {

            $this->add_control(
                'mgpdeg_pcount_info',
                [
                    'label' => sprintf('<span style="color:red">%s</span>', __('The Section only work with pro version.', 'magical-products-display')),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
        }
        $this->add_control(
            'mpdc_pcount_show',
            [
                'label'     => sprintf('%s %s', esc_html__('Show Products Number? ', 'magical-products-display'), mpd_display_pro_only_text()),
                'description' => __('You can show how many products inside the category?', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'default' => '',
            ]
        );
        $this->add_control(
            'mpdc_pcount_text',
            [
                'label'     => sprintf('%s %s', esc_html__('After Number Text ', 'magical-products-display'), mpd_display_pro_only_text()),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __('Enter Text', 'magical-products-display'),
                'default'     => __('Products', 'magical-products-display'),
                'condition' => [
                    'mpdc_pcount_show' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'mpdc_badge_sect',
            [
                'label' => __('Badge', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'mpdc_badge_use',
            [
                'label' => __('Use Card Badge?', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'default' => '',
            ]
        );
        $this->add_control(
            'badge_text',
            [
                'label'       => __('Badge Text', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('Badge Text', 'magical-products-display'),
                'default'     => __('Badge', 'magical-products-display'),
                'condition' => [
                    'mpdc_badge_use' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'badge_position',
            [
                'label' => __('Badge Position', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left-top' => [
                        'title' => __('Left Top', 'magical-products-display'),
                        'icon' => 'fas fa-arrow-up',
                    ],
                    'left-bottom' => [
                        'title' => __('Left Bottom', 'magical-products-display'),
                        'icon' => 'fas fa-arrow-down',
                    ],
                    'right-top' => [
                        'title' => __('Right Top', 'magical-products-display'),
                        'icon' => 'fas fa-arrow-up',
                    ],
                    'right-bottom' => [
                        'title' => __('Right Bottom', 'magical-products-display'),
                        'icon' => 'fas fa-arrow-right',
                    ],

                ],
                'default' => 'right-bottom',
                'condition' => [
                    'mpdc_badge_use' => 'yes',
                ],

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
            'mpdc_cat_basic_style',
            [
                'label' => __('Basic Style', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mpdc_cat_grid_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdc_cat_grid_margin',
            [
                'label' => __('Content Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mpdc_cat_grid_bg_color',
            [
                'label' => __('Card Background color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mpdc_cat_grid_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdc_cat_grid_border',
                'selector' => '{{WRAPPER}} .mpdkit-cat-item',
            ]
        );
        $this->add_control(
            'content_grid_boxshadow',
            [
                'label' => __('Use Box shadow?', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'yes' => __('Yes', 'magical-products-display'),
                'no' => __('No', 'magical-products-display'),
                'default' => 'yes',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mpdc_grid_boxshadow',
                'selector' => '{{WRAPPER}} .mpdkit-shadow',
                'condition' => [
                    'content_grid_boxshadow' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'mpdc_cat_content_style',
            [
                'label' => __('Content style', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mpdc_cat_content_padding',
            [
                'label' => __('Content Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-info.p-3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdc_cat_content_margin',
            [
                'label' => __('Content Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'mpdc_cat_img_style',
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
                'size_units' => ['px', '%', 'rem'],
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
                    '{{WRAPPER}} .mpdkit-cat-img figure img' => 'width: {{SIZE}}{{UNIT}};',

                ],
            ]
        );

        $this->add_control(
            'mpdc_cat_img_auto_height',
            [
                'label' => __('Image auto height', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'magical-products-display'),
                'label_off' => __('Off', 'magical-products-display'),
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'mpdc_cat_img_height',
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
                    'mpdc_cat_img_auto_height!' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-img figure img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mpdc_cat_img_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-img figure' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdc_cat_img_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-img figure' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mpdc_cat_img_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-img figure img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->add_control(
            'mpdc_cat_imgbg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-img' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdc_cat_img_border',
                'selector' => '{{WRAPPER}} .mpdkit-cat-img figure img',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'mpdc_cat_title_style',
            [
                'label' => __('Category Title', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mpdc_cat_title_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-bg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdc_cat_title_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-info h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mpdc_cat_title_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-info h2 a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mpdc_cat_title_typography',
                'selector' => '{{WRAPPER}} .mpdkit-cat-info h2',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mpdc_cat_title_bg',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .mpdkit-cat-bg',
            ]
        );
        $this->add_control(
            'mpdc_cat_title_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mgpdeg_cat_count_head',
            [
                'label'     => sprintf('%s %s', esc_html__('Categroy count text color ', 'magical-products-display'), mpd_display_pro_only_text()),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'mgpdeg_cat_count_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mgppro-catcount, {{WRAPPER}} .mgppro-catcount span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mgpdeg_cat_count_typography',
                'selector' => '{{WRAPPER}} .mgppro-catcount, {{WRAPPER}} .mgppro-catcount span',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'mgtm_cat_description_style',
            [
                'label' => __('Description style', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mpdc_cat_description_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-info p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdc_cat_description_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-info p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mpdc_cat_description_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpdkit-cat-info p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mpdc_cat_description_typography',
                'selector' => '{{WRAPPER}} .mpdkit-cat-info p',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'mpdc_badge_style',
            [
                'label' => __('Badge', 'magical-products-display'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'mpdc_badge_use' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdc_badge_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} span.mpdkit-cat-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdc_badge_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} span.mpdkit-cat-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mpdc_badge_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} span.mpdkit-cat-badge' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mpdc_badge_bgcolor',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} span.mpdkit-cat-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mpdc_badge_typography',
                'selector' => '{{WRAPPER}} span.mpdkit-cat-badge',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdc_badge_border',
                'selector' => '{{WRAPPER}} span.mpdkit-cat-badge',
            ]
        );

        $this->add_control(
            'mpdc_badge_bradius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} span.mpdkit-cat-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mpdc_badge_bshadow',
                'selector' => '{{WRAPPER}} span.mpdkit-cat-badge',
            ]
        );
        $this->add_control(
            'mpdc_badge_rotate',
            [
                'label' => __('Badge Rotate', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 360,
                        'step' => 1,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} span.mpdkit-cat-badge' => 'transform:rotate({{SIZE}}deg);',
                ],
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
        if (get_option('mgppro_is_active', 'no') == 'yes') {
            $mgpd_cats = $this->get_settings('mgpd_rcats');
        } else {
            $mgpd_cats = $this->get_settings('mgpd_cats');
        }


        $mgpd_cats_column = $this->get_settings('mgpd_cats_column');
        $mpdc_default_img = $this->get_settings('mpdc_default_img');


        if ($mgpd_cats) :
?>
            <div class="mpdkit-cat-grid mpdc-catg-<?php echo esc_attr($settings['mgpd_cats_style']); ?>">
                <div class="row">
                    <?php
                    foreach ($mgpd_cats as $cat_items) :
                        if (get_option('mgppro_is_active', 'no') == 'yes') {
                            $catid = $cat_items['mpdac_pcat_id'];
                            $mpdc_icat_img = $cat_items['mpdc_icat_img'];
                        } else {
                            $catid = $cat_items;
                            $mpdc_icat_img = '';
                        }


                        $thumb_id = get_term_meta($catid, 'thumbnail_id', true);
                        $thumb_url = wp_get_attachment_image_url($thumb_id, 'thumbnail_id', true);
                        $info = get_term_by('id', $catid, 'product_cat');
                        $urlarray = explode("/", $thumb_url);
                        $default_img = end($urlarray);
                        //term slug
                        $term_slug = empty($info->slug) ? null : $info->slug;
                        //term name
                        $term_name = empty($info->name) ? null : $info->name;
                        //term desc
                        $term_desc = empty($info->description) ? null : $info->description;

                        $cat_link = $term_slug ? get_term_link($term_slug, 'product_cat') : '#';
                    ?>
                        <div class="col-lg-<?php echo esc_attr($mgpd_cats_column); ?>">
                            <div class="mpdkit-cat-item <?php if ($settings['content_grid_boxshadow'] == 'yes') : ?>mgpdi-shadow<?php endif; ?>">

                                <?php if ($settings['mpdc_catimg_show']) : ?>
                                    <div class="mpdkit-cat-img">
                                        <figure>
                                            <a href="<?php echo esc_url($cat_link); ?> ">
                                                <?php if (!empty($mpdc_icat_img['url'] || $mpdc_icat_img['id'])) : ?>
                                                    <?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_html($cat_items, 'thumbnail', 'mpdc_icat_img'); ?>
                                                <?php else : ?>
                                                    <?php if ('default.png' == $default_img && !empty($mpdc_default_img['url'] || $mpdc_default_img['id'])) : ?>
                                                        <?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_html($settings, 'thumbnail', 'mpdc_default_img'); ?>
                                                    <?php else : ?>
                                                        <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php esc_html_e('Porduct Category ', 'magical-products-display'); ?><?php echo esc_html($term_name); ?>">
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                            </a>

                                        </figure>
                                        <?php if ($settings['mpdc_badge_use']) : ?>
                                            <span class="mpdkit-cat-badge mpdkit-cat-<?php echo esc_attr($settings['badge_position']); ?>"><?php echo esc_html($settings['badge_text']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($term_name || $term_desc) : ?>
                                    <div class="mpdkit-cat-info p-3">
                                        <div class="mpdkit-cat-bg">
                                            <h2><a href="<?php echo esc_url($cat_link); ?> "><?php echo esc_html($term_name); ?></a></h2>
                                            <?php
                                            if ($settings['mpdc_pcount_show'] && get_option('mgppro_is_active', 'no') == 'yes') {
                                                do_action('mgppro_cat_items_count', $catid, $settings['mpdc_pcount_text']);
                                            }

                                            ?>
                                        </div>
                                        <?php if ($settings['mpdc_catdes_show'] && $term_desc) : ?>
                                            <p><?php echo esc_html(wp_trim_words($term_desc, $settings['mpdc_catdes_crop'], '..')); ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else : ?>
            <div class="alert alert-danger">
                <?php echo esc_html('Please select products categories for display this section.'); ?>
            </div>
        <?php endif; ?>

<?php
    }
}
