<?php


class mgProduct_Accordion extends \Elementor\Widget_Base
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
        return 'mpdaccordion_widget';
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
        return esc_html__('MPD Accordion', 'magical-products-display');
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
        return 'eicon-accordion';
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
        return ['accordion', 'toggle', 'tab', 'mpd', 'woo'];
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
            'mgproducts-accordion',
            'bootstrap-custom',
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
            'mpdac_item_section',
            [
                'label' => esc_html__('Accordion products', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'mpdac_product_id',
            [
                'label' => __('Select Product', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => true,
                // 'multiple' => false,
                'options' => mgproducts_display_product_name(),

            ]
        );
        $repeater->add_control(
            'mpdac_is_open',
            [
                'label' => esc_html__('Keep this slide open? ', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => esc_html__('Yes', 'magical-products-display'),
                'label_off' => esc_html__('No', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mpdac_items',
            [
                'label' => esc_html__('Content', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'separator' => 'before',
                'title_field' => get_the_title('{{ mpdac_product_id }}'),
                'fields' => $repeater->get_controls(),
                'dynamic' => [
                    'active' => true,
                ],/*
                'default' => [
                    [
                        'mpdac_title' => ' Magical Addons For Elementor Accordion Title ',
                        'mpdac_content' => 'Lorem ispam dummy text, you can edit or remove it. far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast',
                        'mpdac_is_open'    => 'yes'
                    ],
                    [
                        'mpdac_title' => ' Magical Addons For Elementor Accordion Title',
                        'mpdac_content' => 'Lorem ispam dummy text, you can edit or remove it. far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast',
                    ],
                    [
                        'mpdac_title' => 'Magical Addons For Elementor Accordion Title',
                        'mpdac_content' => 'Lorem ispam dummy text, you can edit or remove it. far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast',
                    ],
                ],*/
            ]
        );
        /*$this->add_control(
            'mpdac_open_first_slide',
            [
                'label' => esc_html__( 'Keep first slide auto open?', 'magical-products-display' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'magical-products-display' ),
                'label_off' => esc_html__( 'No', 'magical-products-display' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );*/
        $this->add_control(
            'mpdac_style',
            [
                'label' => esc_html__('Style', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'accoedion-primary',
                'options' => [
                    'accoedion-primary' => esc_html__('Primary', 'magical-products-display'),
                    'curve-shape' => esc_html__('Curve Shape', 'magical-products-display'),
                    'side-curve' => esc_html__('Side Curve', 'magical-products-display'),
                    'box-icon' => esc_html__('Box Icon', 'magical-products-display'),
                ],
            ]
        );
        $this->add_control(
            'mpdac_effect',
            [
                'label' => esc_html__('Animation Effect', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'effect2',
                'options' => [
                    'none' => esc_html__('No Effect', 'magical-products-display'),
                    'effect1' => esc_html__('Effect One', 'magical-products-display'),
                    'effect2' => esc_html__('Effect Two', 'magical-products-display'),
                    'effect3' => esc_html__('Effect Three', 'magical-products-display'),
                ],
            ]
        );
        $this->add_control(
            'mpdac_img_size',
            [
                'label' => esc_html__('Image Size', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'medium',
                'options' => [
                    'thumbnail'  => esc_html__('Thumbnail (150px x 150px max)', 'magical-products-display'),
                    'medium'   => esc_html__('Medium (300px x 300px max)', 'magical-products-display'),
                    'medium_large'   => esc_html__('Large (768px x 0px max)', 'magical-products-display'),
                    'large'   => esc_html__('Large (1024px x 1024px max)', 'magical-products-display'),
                    'full'   => esc_html__('Full Size (Original image size)', 'magical-products-display'),
                ],


            ]
        );
        $this->add_control(
            'mpdac_img_effects',
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


            ]
        );

        $this->add_responsive_control(
            'mpdac_text_align',
            [
                'label' => esc_html__('Alignment', 'magical-products-display'),
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

            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'mpdac_icon_section',
            [
                'label' => esc_html__('Icon', 'magical-products-display'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'mpdac_icon_show',
            [
                'label' => esc_html__('Show Icon?', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'magical-products-display'),
                'label_off' => esc_html__('No', 'magical-products-display'),
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'mpdac_selected_icon',
            [
                'label' => esc_html__('Icon', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'separator' => 'before',
                'default' => [
                    'value' => 'fas fa-plus',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [
                        'plus',
                        'plus-square',
                        'angle-double-down',
                        'angle-double-up',
                        'angle-double-right',
                        'angle-double-left',
                        'angle-double-left',
                        'angle-down',
                        'angle-up',
                        'angle-left',
                        'angle-right',
                        'arrow-circle-down',
                        'arrow-circle-up',
                        'arrow-circle-left',
                        'arrow-circle-right',
                        'arrow-down',
                        'arrow-up',
                        'arrow-left',
                        'arrow-right',
                        'caret-down',
                        'caret-up',
                        'caret-left',
                        'caret-right',
                    ],
                    'fa-regular' => [
                        'plus-square',
                        'plus-circle',
                        'arrow-alt-circle-down',
                        'arrow-alt-circle-up',
                        'arrow-alt-circle-left',
                        'arrow-alt-circle-right',
                    ],
                ],
                'condition' => [
                    'mpdac_icon_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'mpdac_selected_active_icon',
            [
                'label' => esc_html__('Active Icon', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-minus',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [
                        'plus',
                        'plus-square',
                        'angle-double-down',
                        'angle-double-up',
                        'angle-double-right',
                        'angle-double-left',
                        'angle-double-left',
                        'angle-down',
                        'angle-up',
                        'angle-left',
                        'angle-right',
                        'arrow-circle-down',
                        'arrow-circle-up',
                        'arrow-circle-left',
                        'arrow-circle-right',
                        'arrow-down',
                        'arrow-up',
                        'arrow-left',
                        'arrow-right',
                        'caret-down',
                        'caret-up',
                        'caret-left',
                        'caret-right',
                    ],
                    'fa-regular' => [
                        'plus-square',
                        'plus-circle',
                        'arrow-alt-circle-down',
                        'arrow-alt-circle-up',
                        'arrow-alt-circle-left',
                        'arrow-alt-circle-right',
                    ],
                ],
                'condition' => [
                    'mpdac_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'mpdac_icon_position',
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
                ],
                'default' => 'right',
                'toggle' => false,
                'label_block' => false,
                'condition' => [
                    'mpdac_icon_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->link_pro_added();
    }

    /**
     * Register Accordion widget style ontrols.
     *
     * Adds different input fields in the style tab to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_style_controls()
    {


        $this->start_controls_section(
            'mpdac_style_section',
            [
                'label' => esc_html__('Accordion Style', 'magical-products-display'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'mpdac_border_width',
            [
                'label' => esc_html__('Border Width', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .card.mgrc-item' => 'border-width: {{SIZE}}{{UNIT}};',

                ],
            ]
        );

        $this->add_control(
            'mpdac_border_color',
            [
                'label' => esc_html__('Border Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .card.mgrc-item' => 'border-color: {{VALUE}};',

                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'mpdac_title_style',
            [
                'label'     => esc_html__('Accordion Title Style', 'magical-products-display'),
                'tab'     => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'         => 'mpdac_title_typography',
                'selector'     => '{{WRAPPER}} .mgrc-title h2',
            ]
        );
        $this->add_control(
            'mpdac_usebg_color',
            [
                'label' => esc_html__('Hide default gradient? ', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => esc_html__('Yes', 'magical-products-display'),
                'label_off' => esc_html__('No', 'magical-products-display'),
            ]
        );
        $this->start_controls_tabs(
            'mpdac_accordion_style_tabs'
        );
        $this->start_controls_tab(
            'mpdac_open_tab',
            [
                'label' => esc_html__('Open', 'magical-products-display'),
            ]
        );
        $this->add_control(
            'mpdac_title_color_open',
            [
                'label'         => esc_html__('Color', 'magical-products-display'),
                'type'         => \Elementor\Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .mgrc-title h2' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mpdac_title_background_open',
                'label' => esc_html__('Background', 'magical-products-display'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .card-header.mg-accordion-title .mgrc-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdac_title_border_open',
                'label' => esc_html__('Border', 'magical-products-display'),
                'selector' => '{{WRAPPER}} .card-header.mg-accordion-title .mgrc-title',
            ]
        );

        $this->add_control(
            'mpdac_title_border_radius_open',
            [
                'label' => esc_html__('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .card-header.mg-accordion-title .mgrc-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mpdac_box_shadow_open',
                'label' => esc_html__('Box Shadow', 'magical-products-display'),
                'selector' => '{{WRAPPER}} .card-header.mg-accordion-title .mgrc-title',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'mpdac_style_close_tab',
            [
                'label' => esc_html__('Closed', 'magical-products-display'),
            ]
        );
        $this->add_control(
            'mpdac_title_color_close',
            [
                'label'         => esc_html__('Color', 'magical-products-display'),
                'type'         => \Elementor\Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .mgrc-title.collapsed h2' => 'color: {{VALUE}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mpdac_background_close',
                'label' => esc_html__('Background', 'magical-products-display'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .card-header.mg-accordion-title .mgrc-title.collapsed',

            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdac_title_border_close',
                'label' => esc_html__('Border', 'magical-products-display'),
                'condition' => [
                    'mpdac_style!' => ['curve-shape']
                ],
                'selector' => '{{WRAPPER}} .card-header.mg-accordion-title .mgrc-title.collapsed',
            ]
        );
        $this->add_control(
            'mpdac_border_radious_close',
            [
                'label' => esc_html__('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .card-header.mg-accordion-title .mgrc-title.collapsed' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mpdac_box_shadow_close',
                'label' => esc_html__('Box Shadow', 'magical-products-display'),
                'selector' => '{{WRAPPER}} .card-header.mg-accordion-title .mgrc-title.collapsed',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'mpdac_title_divide',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_responsive_control(
            'mpdac_title_padding',
            [
                'label' => esc_html__('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .card-header.mg-accordion-title .mgrc-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );



        $this->add_responsive_control(
            'mpdac_title_margin_bottom',
            [
                'label'             => esc_html__('Margin Bottom', 'magical-products-display'),
                'type'             => \Elementor\Controls_Manager::SLIDER,
                'default'         => [
                    'size' => '',
                ],
                'range'             => [
                    'px' => [
                        'min'     => -30,
                        'step'     => 1,
                    ],
                ],
                'size_units'     => ['px'],
                'selectors'         => [
                    '{{WRAPPER}} .card-header.mg-accordion-title .mgrc-title'    => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mg_cubestyle_bg_color',
            [
                'label' => __('cubestyle Background', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mg-side-curve .mgrc-title:before' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'mpdac_style' => 'side-curve',
                ],

            ]
        );


        $this->end_controls_section();
        //Icon Style Section
        $this->start_controls_section(
            'mpdac_section_icon_style',
            [
                'label'     => esc_html__('Title Icon Style', 'magical-products-display'),
                'tab'     => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'mpdac_icon_move_heading',
            [
                'type' => \Elementor\Controls_Manager::HEADING,
                'label' => esc_html__('Move icon', 'magical-products-display'),
                'separator' => 'before'
            ]
        );
        $this->start_controls_tabs(
            'mpdac_tabs_icon_move'
        );
        $this->start_controls_tab(
            'mpdac_icon_move_left_right',
            [
                'label' => esc_html__('Left & Right', 'magical-products-display'),
            ]
        );
        $this->add_responsive_control(
            'mpdac_icon_move_left_right_value',
            [
                'label' => esc_html__('Left & Right', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -10,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgc-icons.mgc-right-icon' => 'right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mgc-icons.mgc-left-icon' => 'left: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'mpdac_icon_move_topbottom',
            [
                'label' => esc_html__('Top & Bottom', 'magical-products-display'),
            ]
        );
        $this->add_responsive_control(
            'mpdac_icon_move_topbottom_value',
            [
                'label' => esc_html__('Top & Bottom', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgc-icons.mgc-left-icon, {{WRAPPER}} .mgc-icons.mgc-right-icon' => 'top: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->end_controls_tab();


        $this->end_controls_tabs();


        $this->start_controls_tabs(
            'mpdac_style_tabs_icon'
        );
        $this->start_controls_tab(
            'mpdac_icon_open_tab',
            [
                'label' => esc_html__('Slide Closed Icon', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mpdac_icon_color_close',
            [
                'label'         => esc_html__('Color', 'magical-products-display'),
                'type'         => \Elementor\Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .mgrc-title.collapsed .mgc-icon i' => 'color: {{VALUE}};',

                ],
            ]
        );


        $this->add_responsive_control(
            'mpdac_icon_typography_close',
            [
                'label' => esc_html__('Size', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgrc-title.collapsed .mgc-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_tab();
        $this->start_controls_tab(
            'mpdac_icon_close_tab',
            [
                'label' => esc_html__(' Slide Open icon', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mpdac_icon_color',
            [
                'label'         => esc_html__('Color', 'magical-products-display'),
                'type'         => \Elementor\Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .mgrc-title .mgc-icon i' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->add_responsive_control(
            'mpdac_icon_typography', //icon id different because replaced the previous control
            [
                'label' => esc_html__('Size', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgrc-title .mgc-icon i' => 'font-size: {{SIZE}}{{UNIT}};',

                ]
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
        $this->start_controls_section(
            'mpdac_img_style',
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
                    '{{WRAPPER}} .mgac-content .mpdac-product-image img' => 'flex: 0 0 {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',

                ],
            ]
        );

        $this->add_control(
            'mpdac_img_auto_height',
            [
                'label' => __('Image auto height', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'magical-products-display'),
                'label_off' => __('Off', 'magical-products-display'),
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'mpdac_img_height',
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
                    'mpdac_img_auto_height!' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgac-content .mpdac-product-image img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'mpdac_imgbg_height',
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
                    'mpdac_img_auto_height!' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgac-content .mpdac-product-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mpdac_img_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgac-content .mpdac-product-image figure img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdac_img_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgac-content .mpdac-product-image figure img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mpdac_img_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mgac-content .mpdac-product-image figure img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mpdac_img_bgcolor',
                'label' => esc_html__('Background', 'magical-products-display'),
                //'types' => [ 'classic', 'gradient' ],

                'selector' => '{{WRAPPER}} .mgac-content .mpdac-product-image figure img',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdac_img_border',
                'selector' => '{{WRAPPER}} .mgac-content .mpdac-product-image figure img',
            ]
        );
        $this->end_controls_section();
        //accordion content style 
        $this->start_controls_section(
            'mpdac_section_content_style',
            [
                'label'     => esc_html__('Products Content', 'magical-products-display'),
                'tab'     => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'mpdac_content_color',
            [
                'label'         => esc_html__('Color', 'magical-products-display'),
                'type'         => \Elementor\Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .mgac-content .mpdac-product-details' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'         => 'mpdac_content_typography',
                'selector'     => '{{WRAPPER}} .mgac-content .mpdac-product-details',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'mpdac_content_background',
                'label' => esc_html__('Background', 'magical-products-display'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .mgac-content .mpdac-product-details',
            ]
        );

        $this->add_control(
            'mpdac_content_border_radious',
            [
                'label' => esc_html__('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mgac-content .mpdac-product-details' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mpdac_content_padding',
            [
                'label' => esc_html__('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mgac-content .mpdac-product-details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mpdac_content_width',
            [
                'label' => esc_html__('Width', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mgac-content .mpdac-product-details' => 'width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_control(
            'mpdac_cprice_style',
            [
                'label' => __('Price Style', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'mpdac_cprice_color',
            [
                'label'         => esc_html__('Price Color', 'magical-products-display'),
                'type'         => \Elementor\Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .mpdac-product-opations .mpdac-product-price span, {{WRAPPER}} .eacolor .mpdac-product-opations .mpdac-product-price span' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'         => 'mpdac_cprice_typography',
                'selector'     => '{{WRAPPER}} .mpdac-product-opations .mpdac-product-price span, {{WRAPPER}} .eacolor .mpdac-product-opations .mpdac-product-price span',
            ]
        );

        $this->end_controls_section();
        //Button Style
        $this->start_controls_section(
            'mpdac_btn_style',
            [
                'label' => __('Button', 'magical-products-display'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mpdac_btn_padding',
            [
                'label' => __('Padding', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdac-product-btn a.button,{{WRAPPER}} .mpdac-product-btn a.added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'mpdac_btn_margin',
            [
                'label' => __('Margin', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdac-product-btn a.button,{{WRAPPER}} .mpdac-product-btn a.added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mpdac_btn_typography',
                'selector' => '{{WRAPPER}} .mpdac-product-btn a.added_to_cart,{{WRAPPER}} .mpdac-product-btn a.button',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mpdac_btn_border',
                'selector' => '{{WRAPPER}} .mpdac-product-btn a.added_to_cart,{{WRAPPER}} .mpdac-product-btn a.button',
            ]
        );

        $this->add_control(
            'mpdac_btn_border_radius',
            [
                'label' => __('Border Radius', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mpdac-product-btn a.button,{{WRAPPER}} .mpdac-product-btn a.added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mpdac_btn_box_shadow',
                'selector' => '{{WRAPPER}} .mpdac-product-btn a.button,{{WRAPPER}} .mpdac-product-btn a.added_to_cart',
            ]
        );
        $this->add_control(
            'mpdac_button_color',
            [
                'label' => __('Button color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs('infobox_btn_tabs');

        $this->start_controls_tab(
            'mpdac_btn_normal_style',
            [
                'label' => __('Normal', 'magical-products-display'),
            ]
        );

        $this->add_control(
            'mpdac_btn_color',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mpdac-product-btn a.button,{{WRAPPER}} .mpdac-product-btn a.added_to_cart' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mpdac_btn_bg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpdac-product-btn a.button,{{WRAPPER}} .mpdac-product-btn a.added_to_cart' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'mpdac_btn_hover_style',
            [
                'label' => __('Hover', 'magical-products-display'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mpdac_btnhover_boxshadow',
                'selector' => '{{WRAPPER}} .mpdac-product-btn a.button:hover,{{WRAPPER}} .mpdac-product-btn a.added_to_cart:hover',
            ]
        );

        $this->add_control(
            'mpdac_btn_hcolor',
            [
                'label' => __('Text Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpdac-product-btn a.button:hover, {{WRAPPER}} .mpdac-product-btn a.button:focus,{{WRAPPER}} .mpdac-product-btn a.added_to_cart:hover, {{WRAPPER}} .mpdac-product-btn a.added_to_cart:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mpdac_btn_hbg_color',
            [
                'label' => __('Background Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mpdac-product-btn a.button:hover, {{WRAPPER}} .mpdac-product-btn a.button:focus,{{WRAPPER}} .mpdac-product-btn a.added_to_cart:hover, {{WRAPPER}} .mpdac-product-btn a.added_to_cart:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mpdac_btn_hborder_color',
            [
                'label' => __('Border Color', 'magical-products-display'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'mpdac_btn_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mpdac-product-btn a.button:hover, {{WRAPPER}} .mpdac-product-btn a.button:focus,{{WRAPPER}} .mpdac-product-btn a.added_to_cart:hover, {{WRAPPER}} .mpdac-product-btn a.added_to_cart:focus' => 'border-color: {{VALUE}};',
                ],
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
        $mpdac_items = $this->get_settings('mpdac_items');
?>


        <?php
        $mpdac_rand = rand(253495, 56934658);

        if ($settings['mpdac_usebg_color'] == 'yes') {
            $mpdac_excolor = 'excolor';
        } else {
            $mpdac_excolor = 'eacolor';
        }
        ?>




        <div class="accordion mgaccordion mg-<?php echo esc_attr($settings['mpdac_style']); ?> <?php echo $mpdac_excolor; ?>" id="mpdAccordion<?php echo esc_attr($mpdac_rand); ?>">

            <?php if ($mpdac_items) : ?>
                <?php
                foreach ($mpdac_items as $index => $item) :

                    if ($item['mpdac_product_id']) :
                        $args = array(
                            'post_type' => 'product',
                            'p'         =>  $item['mpdac_product_id'],
                            'post_status'       =>  'publish',
                        );
                        $mpdac_loop = new WP_Query($args);
                        while ($mpdac_loop->have_posts()) :  $mpdac_loop->the_post();

                ?>

                            <div class="card mgrc-item mgrc-item-<?php echo esc_attr($settings['mpdac_text_align']); ?>-<?php echo esc_attr($settings['mpdac_icon_position']); ?> text-<?php echo esc_attr($settings['mpdac_text_align']); ?>">
                                <div class="card-header mg-accordion-title" id="heading<?php echo esc_attr($index); ?><?php echo esc_attr($mpdac_rand); ?>">
                                    <div class="mgrc-title <?php if ($item['mpdac_is_open'] != 'yes') : ?>collapsed<?php endif; ?> <?php if ($settings['mpdac_icon_position'] == 'left') : ?>mgrc-left<?php endif; ?>" data-bs-toggle="collapse" data-bs-target="#mgc-item<?php echo esc_attr($index); ?><?php echo esc_attr($mpdac_rand); ?>" aria-expanded="<?php if ($item['mpdac_is_open'] == 'yes') : ?>true<?php else : ?>false<?php endif; ?>" aria-controls="mgc-item<?php echo esc_attr($index); ?><?php echo esc_attr($mpdac_rand); ?>">
                                        <?php if ($settings['mpdac_icon_position'] == 'left' && $settings['mpdac_icon_show'] == 'yes') : ?>
                                            <div class="mgc-icons mgc-left-icon">
                                                <div class="mgc-icon">
                                                    <span class="mgc-close"><?php \Elementor\Icons_Manager::render_icon($settings['mpdac_selected_icon']); ?></span>

                                                </div>
                                                <div class="mgc-icon">
                                                    <span class="mgc-open"><?php \Elementor\Icons_Manager::render_icon($settings['mpdac_selected_active_icon']); ?></span>

                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <h2><?php the_title(); ?></h2>
                                        <?php if ($settings['mpdac_icon_position'] == 'right' && $settings['mpdac_icon_show'] == 'yes') : ?>
                                            <div class="mgc-icons mgc-right-icon">
                                                <div class="mgc-icon">
                                                    <span class="mgc-close"><?php \Elementor\Icons_Manager::render_icon($settings['mpdac_selected_icon']); ?></span>

                                                </div>
                                                <div class="mgc-icon">
                                                    <span class="mgc-open"><?php \Elementor\Icons_Manager::render_icon($settings['mpdac_selected_active_icon']); ?></span>

                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                </div>

                                <div id="mgc-item<?php echo esc_attr($index); ?><?php echo esc_attr($mpdac_rand); ?>" class="collapse mgac-mcontent mgaccont <?php if ($item['mpdac_is_open'] == 'yes') : ?>show<?php endif; ?>" aria-labelledby="heading<?php echo esc_attr($index); ?><?php echo esc_attr($mpdac_rand); ?>" data-bs-parent="#mpdAccordion<?php echo esc_attr($mpdac_rand); ?>">

                                    <div class="card-body mgac-content mgac-<?php echo esc_attr($settings['mpdac_effect']); ?>">

                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="mpdac-product-image <?php echo esc_attr($settings['mpdac_img_effects']); ?>">
                                                    <figure>
                                                        <a href="<?php the_permalink(); ?>">
                                                            <?php the_post_thumbnail($settings['mpdac_img_size']); ?>
                                                        </a>
                                                    </figure>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="mpdac-product-details">
                                                    <?php echo wp_trim_words(get_the_content(), 50); ?>
                                                    <div class="mpdac-product-opations">
                                                        <div class="mpdac-product-price">
                                                            <?php woocommerce_template_loop_price(); ?>
                                                        </div>
                                                        <div class="mpdac-product-btn">
                                                            <?php woocommerce_template_loop_add_to_cart(); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <div class="alert alert-warning text-center mt-5 mb-5" role="alert">
                            <?php echo esc_html('No Products found. Please add products for display the accordion.', 'magical-products-display'); ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="alert alert-danger text-center mt-5 mb-5" role="alert">
                    <?php echo esc_html('No Products found. Please add products for display the accordion.', 'magical-products-display'); ?>
                </div>
            <?php endif; ?>




        </div>



<?php
    }

    protected function content_template()
    {
    }
}
