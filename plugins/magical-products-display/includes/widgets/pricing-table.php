<?php


class mgProduct_Pricing_Table extends \Elementor\Widget_Base
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
        return 'mp_products_pricingt';
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
        return __('MPD Pricing Table', 'magical-products-display');
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
        return 'eicon-table';
    }

    public function get_keywords()
    {
        return ['pricing', 'price', 'table', 'mpd', 'package'];
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
            'mgproducts-pricing',
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
            'mpdpr_style_sec',
            [
                'label' => __('Pricing Table Style', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'mpdpr_product_style',
            [
                'label'   => __('Style', 'magical-products-display'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'one',
                'options' => [
                    'one'   => __('Style One', 'magical-products-display'),
                    'two'  => __('Style Two', 'magical-products-display'),
                    'three'  => __('Style Three', 'magical-products-display'),
                    'four'  => __('Style Four', 'magical-products-display'),
                ]
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'mpdpr_icon_section',
            [
                'label' => __('Icon or Image', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'mpdpr_use_icon',
            [
                'label' => __('Show Icon or image?', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'mpdpr_icon_type',
            [
                'label' => __('Icon Type', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'icon' => [
                        'title' => __('Icon', 'magical-products-display'),
                        'icon' => 'fas fa-info',
                    ],
                    'image' => [
                        'title' => __('Image', 'magical-products-display'),
                        'icon' => 'far fa-image',
                    ],

                ],
                'default' => 'icon',
                'toggle' => true,
                'condition' => [
                    'mpdpr_use_icon' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'mpdpr_main_icon_position',
            [
                'label' => __('Icon position', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'magical-products-display'),
                        'icon' => 'fas fa-arrow-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'magical-products-display'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'magical-products-display'),
                        'icon' => 'fas fa-arrow-right',
                    ],

                ],
                'default' => '',
                'toggle' => false,
                'selectors' => [
                    '{{WRAPPER}} .mpd-micon' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'mpdpr_use_icon' => 'yes',
                    'mpdpr_icon_type' => 'icon',
                ],
            ]
        );


        $this->add_control(
            'mpdpr_type_selected_icon',
            [
                'label' => __('Choose Icon', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'mpdpr_icon_type' => 'icon',
                    'mpdpr_use_icon' => 'yes',
                ],
            ]
        );


        $this->add_control(
            'mpdpr_type_image',
            [
                'label' => __('Choose Image', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'mpdpr_icon_type' => 'image',
                    'mpdpr_use_icon' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'mpdpr_thumbnail',
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
                'condition' => [
                    'mpdpr_icon_type' => 'image',
                    'mpdpr_use_icon' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'mgpr_iconimg_posotion',
            [
                'label' => __('Icon Or Image Position', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'default' => [
                        'title' => __('Default', 'magical-products-display'),
                        'icon' => 'fas fa-arrows-alt',
                    ],
                    'top' => [
                        'title' => __('top', 'magical-products-display'),
                        'icon' => 'fas fa-arrow-up',
                    ],
                    'middle' => [
                        'title' => __('middle', 'magical-products-display'),
                        'icon' => 'fas fa-arrow-down',
                    ],

                ],
                'default' => 'default',
                'condition' => [
                    'mpdpr_use_icon' => 'yes',
                ]
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'mpdpr_section_title',
            [
                'label' => __('Title', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'mpdpr_title',
            [
                'label'       => __('Pricing title ', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('Plane Name', 'magical-products-display'),
                'default'     => __('Standard', 'magical-products-display'),
                'label_block'     => true,

            ]
        );
        $this->add_control(
            'mpdpr_title_tag',
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
                'default' => 'h4',
            ]
        );
        $this->add_responsive_control(
            'mpdpr_title_align',
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
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bpto-top-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'mpdpr_section_subtitle',
            [
                'label' => __('Subtitle', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'mpdpr_subtitle',
            [
                'label'       => __('Subtitle', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'label_block'     => true,

            ]
        );
        $this->add_control(
            'mpdpr_subtitle_tag',
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
            ]
        );
        $this->add_responsive_control(
            'mpdpr_subtitle_align',
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
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mpdpr-subtitle' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'mpdpr_price_section',
            [
                'label' => __('Price', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'mpdpr_currency',
            [
                'label' => __('Currency', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('$', 'magical-products-display'),
                //'label_block' => true,
            ]
        );
        $this->add_responsive_control(
            'mpdpr_currency_align',
            [
                'label' => __('Currency Position', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'magical-products-display'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'right' => [
                        'title' => __('Right', 'magical-products-display'),
                        'icon' => 'eicon-text-align-right',
                    ],

                ],
                'default' => 'left',
            ]
        );
        $this->add_control(
            'mpdpr_price',
            [
                'label' => __('Price', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('9.99', 'magical-products-display'),
                //'label_block' => true,
            ]
        );
        $this->add_control(
            'mpdpr_price_extext',
            [
                'label' => __('Extra text', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('/Month', 'magical-products-display'),
                'label_block' => true,
            ]
        );
        $this->add_responsive_control(
            'mpdpr_ex_position',
            [
                'label' => __('Extra Text Position', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'right' => [
                        'title' => __('Right', 'magical-products-display'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'bottom' => [
                        'title' => __('Bottom', 'magical-products-display'),
                        'icon' => 'eicon-text-align-right',
                    ],

                ],
                'default' => 'right',
            ]
        );
        $this->add_responsive_control(
            'mpdpr_inline_price_align',
            [
                'label' => __('Price Alignment', 'magical-products-display'),
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
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bpto-prize.mpd-ptext-right' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'mpdpr_ex_position' => 'right',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdpr_price_align',
            [
                'label' => __('Price Alignment', 'magical-products-display'),
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
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bpto-prize' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'mpdpr_ex_position' => 'bottom',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'mpdpr_desc_section',
            [
                'label' => __('Description & Features list', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'mpdpr_desc_show',
            [
                'label'     => __('Show Description', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'

            ]
        );
        $this->add_control(
            'mpdpr_desc',
            [
                'label'       => __('Description', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'input_type'  => 'text',
                'placeholder' => __('Pricing description goes here.', 'magical-products-display'),
                'default'     => __('Best pricing plan for you. It\' editable text you can edit it.', 'magical-products-display'),
                'condition' => [
                    'mpdpr_desc_show' => 'yes',
                ]
            ]
        );
        $this->add_responsive_control(
            'mpdpr_desc_align',
            [
                'label' => __('Description Alignment', 'magical-products-display'),
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
                'default' => '',
                'condition' => [
                    'mpdpr_desc_show' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mpdpr-desc' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mpdpr_features_show',
            [
                'label'     => __('Show Features List', 'magical-products-display'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'

            ]
        );
        $this->add_control(
            'mpdpr_ftitle',
            [
                'label'       => __('Feature Title', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'label_block'     => true,
                'default' => esc_html__('Features', 'magical-products-display'),

            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'mpdpr_list_text',
            [
                'label' => __('Features List', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::TEXT,
                //'default' => __( 'List item' , 'magical-products-display' ),
                'description' => __('Some style not support list fields', 'magical-products-display'),
                'placeholder' => __('Enter List text', 'magical-products-display'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'mpdpr_list_item',
            [
                'label' => __('Features List', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'mpdpr_list_text' => __('List text one', 'magical-products-display'),
                    ],
                    [
                        'mpdpr_list_text' => __('List text two', 'magical-products-display'),
                    ],
                    [
                        'mpdpr_list_text' => __('List text three', 'magical-products-display'),
                    ],
                    [
                        'mpdpr_list_text' => __('List text four', 'magical-products-display'),
                    ],
                ],
                'title_field' => '{{{ mpdpr_list_text }}}',
                'condition' => [
                    'mpdpr_features_show' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdpr_list_align',
            [
                'label' => __('List Alignment', 'magical-products-display'),
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
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mpd-feature' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'mpdpr_features_show' => 'yes',
                ],
            ]
        );


        $this->end_controls_section();


        $this->start_controls_section(
            'mpdpr_button_section',
            [
                'label' => __('Button', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'mpdpr_btntitle',
            [
                'label'       => __('Button Title', 'magical-products-display'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'text',
                'placeholder' => __('Button Text', 'magical-products-display'),
                'default'     => __('Choose Plan', 'magical-products-display'),
            ]
        );
        $this->add_control(
            'mpdpr_btn_link',
            [
                'label' => __('Button Link', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'magical-products-display'),
                'default' => [
                    'url' => '#',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'mpdpr_usebtn_icon',
            [
                'label' => __('Use icon', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'default' => ' ',
            ]
        );

        $this->add_control(
            'mpdpr_btn_selected_icon',
            [
                'label' => __('Choose Icon', 'magical-products-display'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-right',
                    'library' => 'solid',
                ],
                'condition' => [
                    'mpdpr_usebtn_icon' => 'yes',
                ],
            ]
        );


        $this->add_responsive_control(
            'mpdpr_icon_position',
            [
                'label' => __('Button Icon Position', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'magical-products-display'),
                        'icon' => 'fas fa-arrow-left',
                    ],
                    'right' => [
                        'title' => __('Right', 'magical-products-display'),
                        'icon' => 'fas fa-arrow-right',
                    ],

                ],
                'default' => 'right',
                'condition' => [
                    'mpdpr_usebtn_icon' => 'yes',
                ],

            ]
        );
        $this->add_control(
            'mpdpr_iconspace',
            [
                'label' => __('Icon Spacing', 'magical-products-display'),
                'type' => Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],

                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'condition' => [
                    'mpdpr_usebtn_icon' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mg-pricing .tbptitw-main .mpd-btn.mpdprice-btn .left i' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mg-pricing .tbptitw-main .mpd-btn.mpdprice-btn .right i' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mpdpr_btn_align',
            [
                'label' => __('Button Alignment', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'magical-products-display'),
                        'icon' => 'fas fa-arrow-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'magical-products-display'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'magical-products-display'),
                        'icon' => 'fas fa-arrow-right',
                    ],

                ],
                'default' => '',
                'toggle' => false,
                'selectors' => [
                    '{{WRAPPER}} .mpdpri-btn-main' => 'text-align: {{VALUE}};',
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'mpdpr_feature_section',
            [
                'label' => __('Feature', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'mpdpr_set_feature',
            [
                'label' => __('Set As Feature', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'default' => '',
            ]
        );
        /*
		$this->add_control(
			'mpdpr_use_feture_batch',
			[
				'label' => __( 'Use Feature Badge', 'magical-products-display' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'magical-products-display' ),
				'label_off' => __( 'No', 'magical-products-display' ),
				'default' => ' ',
			]
		);
        $this->add_control(
			'mpdpr_btntitle',
			[
				'label'       => __( 'Badge Text', 'magical-products-display' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'input_type'  => 'text',
				'placeholder' => __( 'Featured', 'magical-products-display' ),
				'default'     => __( 'Choose Plan', 'magical-products-display' ),
			]
		);
        */
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
            'mgpr_base_style',
            [
                'label' => __('Base Style', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,

            ]
        );
        $this->add_responsive_control(
            'mpdpr_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdpr_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpd-pricing.tbptitw-main' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mpdpr_bg_color',
                'label' => esc_html__('Background', 'magical-products-display'),
                'types' => ['classic', 'gradient'],

                'selector' => '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto',
            ]
        );

        $this->add_control(
            'mpdpr_border_radius',
            [
                'label' => __('Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdpr_content_border',
                'selector' => '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mpdpr_content_shadow',
                'selector' => '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'mpdpr_icon_style',
            [
                'label' => __('Icon Style', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'mpdpr_use_icon' => 'yes',
                    'mpdpr_icon_type' => 'icon',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdpr_icon_size',
            [
                'label' => __('Icon Size', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-micon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'mpdpr_icon_type' => 'icon'
                ]
            ]
        );
        $this->add_responsive_control(
            'mpdpr_icon_bspacing',
            [
                'label' => __('Bottom Spacing', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-micon i,{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-micon svg' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'mpdpr_icon_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-micon i,{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-micon svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdpr_icon_opacity',
            [
                'label' => __('Icon opacity', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-micon i,{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-micon svg' => 'opacity: {{SIZE}};',
                ],

            ]
        );
        $this->add_control(
            'mgpr_active_icon_absulate',
            [
                'label' => __('Active Absolute position', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'default' => 'no',
            ]
        );
        $this->add_responsive_control(
            'mpdpr_icon_absolutepo',
            [
                'label' => __('Icon Absolute Position', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-micon' => 'top: {{TOP}}{{UNIT}};right: {{RIGHT}}{{UNIT}}; bottom:{{BOTTOM}}{{UNIT}};left: {{LEFT}}{{UNIT}};position:absolute',


                ],
                'condition' => [
                    'mgpr_active_icon_absulate' => 'yes'
                ]
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdpr_icon_border',
                'selector' => '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-micon i,{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-micon svg'

            ]
        );

        $this->add_responsive_control(
            'mpdpr_icon_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-micon i,{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-micon svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .mg-pricing-icon i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mpdpr_icon_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-micon i,{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-micon svg'
            ]
        );

        $this->add_control(
            'mpdpr_icon_color',
            [
                'label' => __('Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-micon i' => 'color: {{VALUE}}',
                ],

            ]
        );

        $this->add_control(
            'mpdpr_icon_bg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-micon i,{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-micon svg' => 'background-color: {{VALUE}};',
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'mpdpr_img_style',
            [
                'label' => __('Image Style', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'mpdpr_use_icon' => 'yes',
                    'mpdpr_icon_type' => 'image',
                ],
            ]
        );

        $this->add_responsive_control(
            'mpdpr_image_width',
            [
                'label' => __('Image Width', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 400,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mpd-pricing-img img' => 'width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'mpdpr_image_height',
            [
                'label' => __('Image Height', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 400,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mpd-pricing-img img, {{WRAPPER}} .mg-pricing-icon svg' => 'height: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_responsive_control(
            'mpdpr_img_spacing',
            [
                'label' => __('Bottom Spacing', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mpd-pricing-img img' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'mpdpr_img_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpd-pricing-img img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdpr_img_opacity',
            [
                'label' => __('Image opacity', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mpd-pricing-img img' => 'opacity: {{SIZE}};',
                ],

            ]
        );
        $this->add_control(
            'mgpr_active_img_absulate',
            [
                'label' => __('Active Absolute position', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'magical-products-display'),
                'label_off' => __('No', 'magical-products-display'),
                'default' => 'no',
            ]
        );
        $this->add_responsive_control(
            'mpdpr_img_absolutepo',
            [
                'label' => __('Image Absolute Position', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpd-pricing-img' => 'top: {{TOP}}{{UNIT}};right: {{RIGHT}}{{UNIT}}; bottom:{{BOTTOM}}{{UNIT}};left: {{LEFT}}{{UNIT}};position:absolute',

                ],
                'condition' => [
                    'mgpr_active_img_absulate' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'mpdpr_img_bg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpd-pricing-img img' => 'background-color: {{VALUE}};',
                ],

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdpr_img_border',
                'selector' => '{{WRAPPER}} .mpd-pricing-img img'

            ]
        );

        $this->add_responsive_control(
            'mpdpr_img_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpd-pricing-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .mg-pricing-icon i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mpdpr_img_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .mpd-pricing-img img'
            ]
        );



        $this->end_controls_section();

        $this->start_controls_section(
            'mpdpr_title_style',
            [
                'label' => __('Title', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mpdpr_title_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .bpto-top-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdpr_title_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .bpto-top-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mpdpr_title_color',
            [
                'label' => __('Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .bpto-top-heading' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mpdpr_title_bgcolor',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .bpto-top-heading' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mpdpr_titleb_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .bpto-top-heading' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mpdpr_title_typography',
                'selector' => '{{WRAPPER}} .tbptitw-main .bpto-top-heading',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdpr_title_border',
                'selector' => '{{WRAPPER}} .tbptitw-main .bpto-top-heading',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'mpdpr_desc_style',
            [
                'label' => __('Description & list', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'mpdpr_description_heading',
            [
                'type' => \Elementor\Controls_Manager::HEADING,
                'label' => __('Description', 'magical-products-display'),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'mpdpr_desc_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpdpr-desc p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdpr_desc_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpdpr-desc p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mpdpr_description_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpdpr-desc p' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mpdpr_description_bgcolor',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpdpr-desc p' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mpdpr_descb_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpdpr-desc p' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mpdpr_desc_typography',
                'selector' => '{{WRAPPER}} .tbptitw-main .mpdpr-desc p',
            ]
        );
        $this->add_control(
            'mpdpr_list_heading',
            [
                'type' => \Elementor\Controls_Manager::HEADING,
                'label' => __('List Header style', 'magical-products-display'),
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mpdpr_listhead_typography',
                'selector' => '{{WRAPPER}} .tbptitw-main .single-bpto .bpto-bottom-heading-text',
            ]
        );
        $this->add_responsive_control(
            'mpdpr_listhead_margin',
            [
                'label' => __('List Header Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .single-bpto .bpto-bottom-heading-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mpdpr_list_style',
            [
                'type' => \Elementor\Controls_Manager::HEADING,
                'label' => __('List style', 'magical-products-display'),
                'separator' => 'before'
            ]
        );
        /*
        $this->add_control(
			'mpdpr_list_style',
			[
				'label' => __( 'List Style', 'magical-products-display' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'disc',
				'options' => [
					'none'  => __( 'None', 'magical-products-display' ),
					'disc'  => __( 'Disc', 'magical-products-display' ),
					'circle' => __( 'Circle', 'magical-products-display' ),
					'square' => __( 'Square', 'magical-products-display' ),
					'decimal' => __( 'Decimal', 'magical-products-display' ),
					'decimal-leading-zero' => __( 'Decimal-leading-zero', 'magical-products-display' ),
					'lower-roman' => __( 'Lower Roman', 'magical-products-display' ),
					'upper-roman' => __( 'Upper Roman', 'magical-products-display' ),
					'lower-greek' => __( 'Lower Greek', 'magical-products-display' ),
					'lower-latin' => __( 'Lower Latin', 'magical-products-display' ),
					'armenian' => __( 'Armenian', 'magical-products-display' ),
					'georgian' => __( 'Georgian', 'magical-products-display' ),
					'lower-alpha' => __( 'Lower Alpha', 'magical-products-display' ),
					'upper-alpha' => __( 'Upper Alpha', 'magical-products-display' ),
				],
				'selectors' => [
                    '{{WRAPPER}} .mg-price-list ul' => 'list-style: {{VALUE}};',
                ],
			]
		);
*/

        $this->add_responsive_control(
            'mpdpr_list_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdpr_list_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mpdpr_list_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto ul li p' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mpdpr_list_bgcolor',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto ul li' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mpdpr_listb_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto ul li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdpr_list_border',
                'selector' => '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto ul li',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mpdpr_list_typography',
                'selector' => '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto ul li p',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'mpdpr_price_style',
            [
                'label' => __('Price', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'mpdpr_price_currency',
            [
                'type' => \Elementor\Controls_Manager::HEADING,
                'label' => __('Currency style', 'magical-products-display'),
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control(
            'mpdpr_currency_margin',
            [
                'label' => __('Currency Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto span.bpto-curency' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mpdpr_currency_typography',
                'selector' => '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto span.bpto-curency',
            ]
        );
        $this->add_control(
            'mpdpr_currency_color',
            [
                'label' => __('Currency Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto span.bpto-curency' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mpdpr_price_heading',
            [
                'type' => \Elementor\Controls_Manager::HEADING,
                'label' => __('Price style', 'magical-products-display'),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'mpdpr_price_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto .bpto-price-tag' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdpr_price_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto span.bpto-prixe-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mpdpr_price_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto span.bpto-prixe-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mpdpr_price_bgcolor',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto .bpto-price-tag' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mpdpr_priceb_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto .bpto-price-tag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mpdpr_price_typography',
                'selector' => '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .single-bpto span.bpto-prixe-text',
            ]
        );
        $this->add_control(
            'mpdpr_pextra_heading',
            [
                'type' => \Elementor\Controls_Manager::HEADING,
                'label' => __('Price Extra text style', 'magical-products-display'),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'mpdpr_pextra_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-exptext' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdpr_pextra_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-exptext' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mpdpr_pextra_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-exptext' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mpdpr_pextra_bgcolor',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-exptext' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'mpdpr_pextrab_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-exptext' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mpdpr_pextra_typography',
                'selector' => '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-exptext',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdpr_pextra_border',
                'selector' => '{{WRAPPER}} .tbptitw-main .mpd-pricet-base .mpd-exptext',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'mpdpr_price_btn_section',
            [
                'label' => __('Button', 'magical-products-display'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mpdpr_btn_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-btn.mpdprice-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdpr_btn_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-btn.mpdprice-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mpdpr_btn_typography',
                'selector' => '{{WRAPPER}} .tbptitw-main .mpd-btn.mpdprice-btn',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdpr_btn_border',
                'selector' => '{{WRAPPER}} .tbptitw-main .mpd-btn.mpdprice-btn',
            ]
        );

        $this->add_control(
            'mpdpr_btn_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-btn.mpdprice-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mpdpr_btn_box_shadow',
                'selector' => '{{WRAPPER}} .tbptitw-main .mpd-btn.mpdprice-btn',
            ]
        );
        $this->add_control(
            'mpdpr_button_color',
            [
                'label' => __('Button color', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs('infobox_btn_tabs');

        $this->start_controls_tab(
            'mpdpr_btn_normal_style',
            [
                'label' => __('Normal', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mpdpr_btn_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-btn.mpdprice-btn' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'mpdpr_btn_bg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-btn.mpdprice-btn' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'mpdpr_btn_hover_style',
            [
                'label' => __('Hover', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mpdpr_btn_hcolor',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-btn.mpdprice-btn:hover, {{WRAPPER}} .tbptitw-main .mpd-btn.mpdprice-btn:focus' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'mpdpr_btn_hbg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-btn.mpdprice-btn:hover, {{WRAPPER}} .tbptitw-main .mpd-btn.mpdprice-btn:focus' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'mpdpr_btn_hborder_color',
            [
                'label' => __('Border Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'mpdpr_btn_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tbptitw-main .mpd-btn.mpdprice-btn:hover, {{WRAPPER}} .tbptitw-main .mpd-btn.mpdprice-btn:focus' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mpdpr_btn_box_shadowhv',
                'selector' => '{{WRAPPER}} .tbptitw-main .mpd-btn.mpdprice-btn:hover, {{WRAPPER}} .tbptitw-main .mpd-btn.mpdprice-btn:focus',
            ]
        );


        $this->end_controls_tab();
        $this->end_controls_tabs();

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

?>
        <div class="mpd-pricing tbptitw-main">
            <?php $this->mpd_pricing_gone($settings); ?>
        </div>

    <?php
    }


    public function mpd_pricing_gone($settings)
    {
        $settings = $this->get_settings_for_display();
        $mpdpr_set_feature = $settings['mpdpr_set_feature'];
        $mpdpr_product_style = $settings['mpdpr_product_style'];
        $mgpr_iconimg_posotion = $settings['mgpr_iconimg_posotion'];



    ?>

        <div class="tbptitw-main">
            <div class="mpd-pricet-base style-<?php echo esc_attr($mpdpr_product_style); ?>">
                <div class="single-bpto <?php if ($mpdpr_set_feature) : ?>bpto-highlight-<?php echo esc_attr($mpdpr_product_style);
                                                                                        endif; ?>">
                    <div class="bpto-top">
                        <?php
                        if ($mgpr_iconimg_posotion == 'default') {
                            if ($mpdpr_product_style != 'four') {
                                $this->imgicon_output($settings);
                            }
                        } elseif ($mgpr_iconimg_posotion == 'top') {
                            $this->imgicon_output($settings);
                        }

                        ?>
                        <?php $this->title_output($settings); ?>

                        <?php
                        if ($mpdpr_product_style == 'one' || $mpdpr_product_style == 'two') {
                            $this->price_output($settings);
                        }
                        ?>

                        <?php $this->subtitle_output($settings); ?>

                        <?php
                        if ($mgpr_iconimg_posotion == 'default') {
                            if ($mpdpr_product_style == 'four') {
                                $this->imgicon_output($settings);
                            }
                        } elseif ($mgpr_iconimg_posotion == 'middle') {
                            $this->imgicon_output($settings);
                        }
                        ?>
                    </div>


                    <?php $this->desc_output($settings); ?>

                    <?php
                    if ($mpdpr_product_style == 'three' || $mpdpr_product_style == 'four') {
                        $this->price_output($settings);
                    }
                    ?>
                    <?php $this->pricing_btn_output($settings); ?>
                </div>
            </div>
        </div>

    <?php
    }

    // image and icon output 
    public function imgicon_output($settings)
    {
        if (empty($settings['mpdpr_use_icon'])) {
            return;
        }
    ?>

        <?php if ($settings['mpdpr_icon_type'] == 'icon') : ?>
            <div class="mpd-micon <?php echo esc_attr($settings['mpdpr_main_icon_position']); ?>">
                <?php \Elementor\Icons_Manager::render_icon($settings['mpdpr_type_selected_icon']); ?>
            </div>
        <?php endif; ?>
        <?php if ($settings['mpdpr_icon_type'] == 'image') : ?>
            <figure class="mpd-pricing-img">
                <?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_html($settings, 'mpdpr_thumbnail', 'mpdpr_type_image'); ?>
            </figure>
        <?php endif;
    }

    // image and icon output 
    public function desc_output($settings)
    {

        $mpdpr_list_align = $settings['mpdpr_list_align'];
        $mpdpr_list_items = $settings['mpdpr_list_item'];
        $mpdpr_desc_align = $settings['mpdpr_desc_align'];
        $mpdpr_desc = $settings['mpdpr_desc'];
        $this->add_inline_editing_attributes('mpdpr_desc');
        $mpdpr_ftitle = $settings['mpdpr_ftitle'];
        $this->add_inline_editing_attributes('mpdpr_ftitle');
        $this->add_render_attribute('mpdpr_ftitle', 'class', 'bpto-bottom-heading-text');



        ?>

        <?php if ($settings['mpdpr_desc_show']) : ?>
            <div class="mpdpr-desc">
                <p <?php echo $this->get_render_attribute_string('mpdpr_desc'); ?>> <?php echo wp_kses_post($mpdpr_desc); ?></p>
            </div>
        <?php endif; ?>
        <?php if ($settings['mpdpr_features_show']) : ?>
            <div class="bpto-bottom mpd-feature">
                <h3 <?php echo $this->get_render_attribute_string('mpdpr_ftitle'); ?>><?php echo wp_kses_post($mpdpr_ftitle); ?></h3>
                <ul class="mpdpr-list">
                    <?php foreach ($mpdpr_list_items as $item) : ?>


                        <li>
                            <p><?php echo esc_html($item['mpdpr_list_text']); ?></p>
                        </li>
                    <?php endforeach; ?>

                </ul>
            </div>
        <?php
        endif; // features list check


    }


    //Pricing button
    function pricing_btn_output($settings)
    {
        $mpdpr_usebtn_icon = $settings['mpdpr_usebtn_icon'];


        $mpdpr_btntitle = $settings['mpdpr_btntitle'];
        $btn_link = $settings['mpdpr_btn_link'];
        $mpdpr_icon_position =  $settings['mpdpr_icon_position'];
        ?>
        <div class="mpdpri-btn-main">
            <?php
            if ($mpdpr_usebtn_icon) :
                $this->add_inline_editing_attributes('mpdpr_btntitle', 'none');
                $this->add_render_attribute('mpdpr_link', 'class', 'mpd-btn mpdprice-btn');

                $this->add_render_attribute('mpdpr_link', 'href', esc_url($btn_link['url']));
                if (!empty($btn_link['is_external'])) {
                    $this->add_render_attribute('mpdpr_link', 'target', '_blank');
                }
                if (!empty($btn_link['nofollow'])) {
                    $this->set_render_attribute('mpdpr_link', 'rel', 'nofollow');
                }
            ?>
                <a <?php echo $this->get_render_attribute_string('mpdpr_link'); ?>>
                    <?php if ($mpdpr_icon_position == 'left') : ?>
                        <span class="left">
                            <?php \Elementor\Icons_Manager::render_icon($settings['mpdpr_btn_selected_icon']); ?></span>

                    <?php endif; ?>
                    <span <?php echo $this->get_render_attribute_string('mpdpr_btntitle'); ?>><?php echo mgproducts_kses_tags($mpdpr_btntitle); ?></span>
                    <?php if ($mpdpr_icon_position == 'right') : ?>
                        <span class="right"><?php \Elementor\Icons_Manager::render_icon($settings['mpdpr_btn_selected_icon']); ?></span>
                    <?php endif; ?>
                </a>
            <?php else : ?>
                <a <?php echo $this->get_render_attribute_string('mpdpr_link'); ?>><span <?php echo $this->get_render_attribute_string('mpdpr_btntitle'); ?>><?php echo mgproducts_kses_tags($mpdpr_btntitle); ?></span></a>
            <?php endif; ?>
        </div>
    <?php

    } // btn output 
    // Title output
    public function title_output($settings)
    {
        if (empty($settings['mpdpr_title'])) {
            return;
        }
        //title
        $mpdpr_title = $settings['mpdpr_title'];
        $mpdpr_title_align = $settings['mpdpr_title_align'];
        $mpdpr_title_tag = $settings['mpdpr_title_tag'];
        $this->add_inline_editing_attributes('mpdpr_title');
        $this->add_render_attribute('mpdpr_title', 'class', 'bpto-top-heading');

        printf(
            '<%1$s %2$s>%3$s</%1$s>',
            tag_escape($mpdpr_title_tag),
            $this->get_render_attribute_string('mpdpr_title'),
            mgproducts_kses_tags($mpdpr_title)
        );
    } // end title 
    // Subtitle output
    public function subtitle_output($settings)
    {
        if (empty($settings['mpdpr_subtitle'])) {
            return;
        }
        //title
        $mpdpr_subtitle = $settings['mpdpr_subtitle'];
        $mpdpr_subtitle_tag = $settings['mpdpr_subtitle_tag'];
        $this->add_inline_editing_attributes('mpdpr_subtitle');
        $this->add_render_attribute('mpdpr_subtitle', 'class', 'mpdpr-subtitle');

        printf(
            '<%1$s %2$s>%3$s</%1$s>',
            tag_escape($mpdpr_subtitle_tag),
            $this->get_render_attribute_string('mpdpr_subtitle'),
            mgproducts_kses_tags($mpdpr_subtitle)
        );
    } // end subtitle 

    public function price_output($settings)
    {
        /* if( empty($mg_pr_title) ){
        return;
    } */
        $mpdpr_currency = $settings['mpdpr_currency'];
        $mpdpr_currency_align = $settings['mpdpr_currency_align'];
        $mpdpr_price = $settings['mpdpr_price'];
        $mpdpr_price_extext = $settings['mpdpr_price_extext'];
        $mpdpr_ex_position = $settings['mpdpr_ex_position'];
    ?>
        <div class="bpto-prize mpd-ptext-<?php echo esc_attr($mpdpr_ex_position); ?>">
            <div class="bpto-price-tag ">
                <?php if ($mpdpr_currency_align == 'left') : ?>
                    <span class="bpto-curency"><?php echo esc_html($mpdpr_currency); ?></span>
                <?php endif; ?>
                <span class="bpto-prixe-text"><?php echo esc_html($mpdpr_price); ?></span>
                <?php if ($mpdpr_currency_align == 'right') : ?>
                    <span class="bpto-curency"><?php echo esc_html($mpdpr_currency); ?></span>
                <?php endif; ?>
            </div>
            <p class="mpd-exptext"><?php echo esc_html($mpdpr_price_extext); ?></p>
        </div>

<?php

    } // end price 



}
