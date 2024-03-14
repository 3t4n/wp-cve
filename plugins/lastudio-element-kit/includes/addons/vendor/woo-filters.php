<?php

/**
 * Class: LaStudioKit_Woo_Filters
 * Name: Menu Cart
 * Slug: lakit-menucart
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Woo Filters Widget
 */
class LaStudioKit_Woo_Filters extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
        if(!lastudio_kit_settings()->is_combine_js_css()) {
            $this->add_style_depends( 'lastudio-kit-base' );
            $this->add_style_depends( 'lastudio-kit-woocommerce' );
            $this->add_script_depends('lastudio-kit-base' );
        }
    }

    public function get_name() {
        return 'lakit-woofilters';
    }

    public function get_categories() {
        return [ 'lastudiokit-woocommerce' ];
    }

    protected function get_widget_title() {
        return esc_html__( 'Product Filters', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-site-search';
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_layout',
            array(
                'label' => esc_html__( 'Layout', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'layout',
            [
                'label' => esc_html__( 'Layout', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default'    => esc_html__('Default', 'lastudio-kit'),
                    'aside'      => esc_html__('Aside', 'lastudio-kit'),
                    'toggle'     => esc_html__('Toggle', 'lastudio-kit'),
                ],
                'default' => 'default',
            ]
        );

        $this->add_control(
            'type',
            [
                'label' => esc_html__( 'Skin', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'vertical'     => esc_html__('Vertical', 'lastudio-kit'),
                    'horizontal'       => esc_html__('Horizontal', 'lastudio-kit'),
                ],
                'default' => 'vertical',
            ]
        );

        $this->add_control(
            'item_as_dropdown',
            [
                'label'     => esc_html__( 'Dropdown Style?', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'default'      => '',
                'return_value' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'item_alignment',
            [
                'label' => esc_html_x( 'Justify Content', 'Flex Container Control', 'elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html_x( 'Flex Start', 'Flex Container Control', 'elementor' ),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html_x( 'Center', 'Flex Container Control', 'elementor' ),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html_x( 'Flex End', 'Flex Container Control', 'elementor' ),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html_x( 'Space Between', 'Flex Container Control', 'elementor' ),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html_x( 'Space Around', 'Flex Container Control', 'elementor' ),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html_x( 'Space Evenly', 'Flex Container Control', 'elementor' ),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-woofilters_block' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'type' => 'vertical',
                ],
            ]
        );

        $this->add_control(
            'filter_label',
            [
                'label' => esc_html__( 'Label', 'lastudio-kit' ),
                'type'  => Controls_Manager::TEXT,
                'condition' => [
                    'layout' => ['aside', 'toggle'],
                ],
            ]
        );

        $this->add_control(
            'filter_icon',
            [
                'label'            => esc_html__( 'Icon', 'lastudio-kit' ),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin'             => 'inline',
                'label_block'      => false,
                'condition'        => [
                    'layout' => ['aside', 'toggle'],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_filters',
            array(
                'label' => esc_html__( 'Filters', 'lastudio-kit' ),
            )
        );

        $filter_type = [
            'cat_list'          => esc_html__('Category[List]', 'lastudio-kit'),
            'cat_dropdown'      => esc_html__('Category[Dropdown]', 'lastudio-kit'),
            'tag_list'          => esc_html__('Tag[List]', 'lastudio-kit'),
            'tag_dropdown'      => esc_html__('Tag[Dropdown]', 'lastudio-kit'),
            'rating'            => esc_html__('Rating', 'lastudio-kit'),
            'price_range'       => esc_html__('Price Range', 'lastudio-kit'),
            'price_list'        => esc_html__('Price List', 'lastudio-kit'),
            'sort_by_list'      => esc_html__('Sort By[List]', 'lastudio-kit'),
            'sort_by_dropdown'  => esc_html__('Sort By[Dropdown]', 'lastudio-kit'),
            'result_count'      => esc_html__('Result Count', 'lastudio-kit'),
            'product_attribute' => esc_html__('Product Attribute', 'lastudio-kit'),
            'active_filters'    => esc_html__('Active Filters', 'lastudio-kit'),
        ];

        $filter_type_json = json_encode($filter_type);

        $filters = new Repeater();

        $filters->add_control(
            'filter_source',
            [
                'label'     => esc_html__( 'Source', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT2,
                'options' => $filter_type
            ]
        );

        $filters->add_responsive_control(
            'item_width',
            [
                'label' => esc_html__( 'Block Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px', 'em', 'rem' ],
                'default' => [
                    'unit' => '%'
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--lakit-filter-block-width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $filters->add_control(
            'filter_label',
            [
                'label' => esc_html__( 'Label', 'lastudio-kit' ),
                'type'  => Controls_Manager::TEXT,
            ]
        );

        $filters->add_control(
            'filter_icon',
            [
                'label'            => esc_html__( 'Icon', 'lastudio-kit' ),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin'             => 'inline',
                'label_block'      => false,
            ]
        );

        $filters->add_control(
            'filter_price_list',
            [
                'label' => esc_html__( 'Price List', 'lastudio-kit' ),
                'description' => esc_html__( 'Enter the price by format: min_price|max_price. Divide price list with semicolon (;). Example: 10|20;30|40', 'lastudio-kit' ),
                'type'  => Controls_Manager::TEXTAREA,
                'condition' => [
                    'filter_source' => 'price_list',
                ],
            ]
        );

        $filters->add_control(
            'type',
            [
                'label'     => esc_html__( 'Type', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'list'      => esc_html__('List', 'lastudio-kit'),
                    'swatch'    => esc_html__('Attribute Swatch', 'lastudio-kit'),
                ],
                'condition' => [
                    'filter_source' => 'product_attribute',
                ],
            ]
        );

        $attribute_array      = array();
        $std_attribute        = '';
        $attribute_taxonomies = wc_get_attribute_taxonomies();

        if ( ! empty( $attribute_taxonomies ) ) {
            foreach ( $attribute_taxonomies as $tax ) {
                if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
                    $attribute_array[ $tax->attribute_name ] = $tax->attribute_label;
                }
            }
            $std_attribute = current( $attribute_array );
        }

	    $filter_attr_json = json_encode($attribute_array);

        $filters->add_control(
            'attribute',
            [
                'label'     => esc_html__( 'Attribute', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => $attribute_array,
                'default' => $std_attribute,
                'condition' => [
                    'filter_source' => 'product_attribute',
                ],
            ]
        );

        $filters->add_control(
            'query_type',
            [
                'label'     => esc_html__( 'Query type', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'or' => 'Or',
                    'and' => 'And',
                ],
                'default' => 'and',
                'condition' => [
                    'filter_source' => 'product_attribute',
                ],
            ]
        );

        $filters->add_control(
            'show_count',
            [
                'label'     => esc_html__( 'Show Count', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'default'      => '',
                'return_value' => 'yes',
                'condition' => [
                    'filter_source' => ['cat_list', 'cat_dropdown', 'product_attribute'],
                ],
            ]
        );

        $filters->add_control(
            'show_label',
            [
                'label'     => esc_html__( 'Show Label', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'default'      => '',
                'return_value' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .swatch-anchor-label' => 'display: inherit',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-hint:before, {{WRAPPER}} {{CURRENT_ITEM}} .lakit-hint:after' => 'display: none',
                ],
                'condition' => [
                    'filter_source' => ['product_attribute'],
                ],
            ]
        );

        $filters->add_control(
            'inline',
            [
                'label'     => esc_html__( 'Is Inline', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'default'      => '',
                'return_value' => 'yes',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'terms' => [
                                ['name' => 'filter_source', 'operator' => 'in', 'value' => ['cat_list', 'tag_list', 'price_list', 'sort_by_list']]
                            ]
                        ],
                        [
                            'terms' => [
                                ['name' => 'filter_source', 'operator' => '===', 'value' => 'product_attribute'],
                                ['name' => 'type', 'operator' => 'in', 'value' => ['list', 'swatch']],
                            ]
                        ],
                    ]
                ],
            ]
        );

        $filters->add_control(
            'hide_on',
            [
                'label'        => esc_html__( 'Hide on', 'lastudio-kit' ),
                'type'         => Controls_Manager::SELECT2,
                'options'      => [
                    'desktop' => esc_html__( 'Desktop', 'lastudio-kit' ),
                    'laptop' => esc_html__( 'Laptop', 'lastudio-kit' ),
                    'tablet' => esc_html__( 'Tablet', 'lastudio-kit' ),
                    'mobile_extra' => esc_html__( 'Mobile Extra', 'lastudio-kit' ),
                    'mobile' => esc_html__( 'Mobile', 'lastudio-kit' ),
                ],
                'multiple' => true,
            ]
        );

        $this->add_control(
            'filters',
            array(
                'type'          => Controls_Manager::REPEATER,
                'fields'        => $filters->get_controls(),
                'title_field'   => "<# let filter_type_json=$filter_type_json; filter_attr_json=$filter_attr_json; filter_type_label= filter_type_json[filter_source] + (filter_source==='product_attribute' ? ' [' + filter_attr_json[attribute] + ']' : '') #>{{{ filter_type_label }}}",
                'prevent_empty' => false,
                'default'       => [
                    [
                        'filter_source' => 'cat_list'
                    ],
                ]
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_main_heading_style',
            array(
                'label' => esc_html__( 'Toggle/Aside', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout' => ['aside', 'toggle'],
                ],
            )
        );

        $this->add_control(
            'filter_box_position',
            [
                'label' => esc_html__( 'Filter Box Position', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-order-start',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-order-end',
                    ],
                ],
                'selectors_dictionary' => [
                    'left'    => '--lakit-filter-x_pos: -100%; left: 0',
                    'right' => '--lakit-filter-x_pos: 100%; right: 0',
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-woofilters_area' => '{{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'filter_box_width',
            [
                'label' => esc_html__( 'Filter Box Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', '%', 'em', 'rem' ),
                'selectors' => [
                    '{{WRAPPER}} ' => '--lakit-filter-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'filter_main_heading_font',
                'label' => esc_html__( 'Heading Typography', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .woofilter-litem',
            ]
        );
        $this->_add_control(
            'filter_main_heading_color',
            [
                'label'     => esc_html__( 'Heading Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woofilter-litem' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->_add_control(
            'filter_main_heading_bgcolor',
            [
                'label'     => esc_html__( 'Heading Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woofilter-litem' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'filter_main_heading_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', 'em', 'rem' ),
                'min' => 0,
                'max' => 100,
                'selectors' => [
                    '{{WRAPPER}} .woofilter-litem .woofilter-litem-icon' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'filter_main_heading_icon_position',
            [
                'label' => esc_html__( 'Icon Position', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-order-start',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-order-end',
                    ],
                ],
                'selectors_dictionary' => [
                    'left'    => 'order: -1;',
                    'right' => 'order: 1;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woofilter-litem .woofilter-litem-icon' => '{{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'filter_main_heading_icon_padding',
            array(
                'label'       => esc_html__( 'Icon Padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', 'rem' ),
                'selectors'   => array(
                    '{{WRAPPER}} .woofilter-litem .woofilter-litem-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->add_responsive_control(
            'filter_main_heading_padding',
            array(
                'label'       => esc_html__( 'Heading Padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', 'rem' ),
                'selectors'   => array(
                    '{{WRAPPER}} .woofilter-litem' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->add_responsive_control(
            'filter_main_heading_margin',
            array(
                'label'       => esc_html__( 'Heading Margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', 'rem' ),
                'selectors'   => array(
                    '{{WRAPPER}} .woofilter-litem' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_main_heading_border',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .woofilter-litem',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'filter_main_heading_shadow',
                'selector' => '{{WRAPPER}} .woofilter-litem',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_filter_box',
            array(
                'label' => esc_html__( 'Filter Box', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            '_zone_filter_list',
            [
                'label' => esc_html__( 'Filter List', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control(
            'filter_box_gap',
            [
                'label' => esc_html__( 'Filter List Gap', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-woofilters_block' => 'gap: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'filter_box_bg',
                'selector' => '{{WRAPPER}} .lakit-woofilters_area',
            )
        );
        $this->add_responsive_control(
            'filter_box_padding',
            array(
                'label'       => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', '%', 'em', 'rem', 'vw', 'vh' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakit-woofilters_area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->add_responsive_control(
            'filter_box_margin',
            array(
                'label'       => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', '%', 'em', 'rem', 'vw', 'vh' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakit-woofilters_area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->add_responsive_control(
            'filter_box_radius',
            array(
                'label'       => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakit-woofilters_area' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_box_border',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakit-woofilters_area',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'filter_box_shadow',
                'selector' => '{{WRAPPER}} .lakit-woofilters_area',
            ]
        );

        $this->add_control(
            '_zone_filter_list_box',
            [
                'label' => esc_html__( 'Filter List Box', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'filter_list_box_bg',
                'selector' => '{{WRAPPER}} .lakit-woofilters_block_item',
            )
        );
        $this->add_responsive_control(
            'filter_list_box_padding',
            array(
                'label'       => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakit-woofilters_block_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->add_responsive_control(
            'filter_list_box_radius',
            array(
                'label'       => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakit-woofilters_block_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_list_box_border',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakit-woofilters_block_item',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'filter_list_box_shadow',
                'selector' => '{{WRAPPER}} .lakit-woofilters_block_item',
            ]
        );

        $this->add_control(
            '_zone_filter_list_item_box',
            [
                'label' => esc_html__( 'Filter List Item Box', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'filter_list_item_box_max_height_enable',
            [
                'label'     => esc_html__( 'Set box height?', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'default'      => '',
                'return_value' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .lakit-woofilters_block_item__filter' => 'overflow-y: auto'
                ],
            ]
        );

        $this->add_responsive_control(
            'filter_list_item_box_max_height',
            [
                'label' => esc_html__( 'Box Max Height', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'vh', 'vw' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-woofilters_block_item__filter' => 'max-height: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'filter_list_item_box_max_height_enable' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'filter_list_item_box_bg',
                'selector' => '{{WRAPPER}} .lakit-woofilters_block_item__filter',
            )
        );
        $this->add_responsive_control(
            'filter_list_item_box_padding',
            array(
                'label'       => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakit-woofilters_block_item__filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->add_responsive_control(
            'filter_list_item_box_radius',
            array(
                'label'       => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} .lakit-woofilters_block_item__filter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_list_item_box_border',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakit-woofilters_block_item__filter',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'filter_list_item_box_shadow',
                'selector' => '{{WRAPPER}} .lakit-woofilters_block_item__filter',
            ]
        );

        $this->add_control(
            '_zone_filter_list_heading',
            [
                'label' => esc_html__( 'Filter List Heading', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'filter_list_heading_font',
                'label' => esc_html__( 'Heading Typography', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .woofilter-bitem',
            ]
        );
        $this->_add_control(
            'filter_list_heading_color',
            [
                'label'     => esc_html__( 'Heading Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woofilter-bitem' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->_add_control(
            'filter_list_heading_bgcolor',
            [
                'label'     => esc_html__( 'Heading Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woofilter-bitem' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'filter_list_heading_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units'  => array( 'px', 'em', 'rem' ),
                'min' => 0,
                'max' => 100,
                'selectors' => [
                    '{{WRAPPER}} .woofilter-bitem .woofilter-bitem-icon' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'filter_list_heading_icon_position',
            [
                'label' => esc_html__( 'Icon Position', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-order-start',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-order-end',
                    ],
                ],
                'selectors_dictionary' => [
                    'left'    => 'order: -1;',
                    'right' => 'order: 1;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woofilter-bitem .woofilter-bitem-icon' => '{{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'filter_list_heading_icon_padding',
            array(
                'label'       => esc_html__( 'Icon Padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', 'rem' ),
                'selectors'   => array(
                    '{{WRAPPER}} .woofilter-bitem .woofilter-bitem-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->add_responsive_control(
            'filter_list_heading_padding',
            array(
                'label'       => esc_html__( 'Heading Padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', 'rem' ),
                'selectors'   => array(
                    '{{WRAPPER}} .woofilter-bitem' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->add_responsive_control(
            'filter_list_heading_margin',
            array(
                'label'       => esc_html__( 'Heading Margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', 'rem' ),
                'selectors'   => array(
                    '{{WRAPPER}} .woofilter-bitem' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_list_heading_border',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .woofilter-bitem',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'filter_list_heading_shadow',
                'selector' => '{{WRAPPER}} .woofilter-bitem',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_filter_list_item_style',
            array(
                'label' => esc_html__( 'Filter List Item', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'filter_list_item_gap',
            [
                'label' => esc_html__( 'Item gap', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-filter-item-gap: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->start_controls_tabs( 'tabs_filter_list_item' );
        $this->start_controls_tab(
            'tab_filter_list_item_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'filter_list_item_font',
                'selector' => '{{WRAPPER}} .lakit-woofilters_block_item__filter li,{{WRAPPER}} .lakit-woofilters_block_item__filter ul.children li',
            ]
        );
        $this->add_control(
            'filter_list_item_color',
            [
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-filter-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'filter_list_item_bgcolor',
            [
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-filter-bgcolor: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'filter_list_item_padding',
            array(
                'label'       => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', 'rem' ),
                'selectors'   => array(
                    '{{WRAPPER}}' => '--lakit-filter-item-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_list_item_radius',
            array(
                'label'       => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', 'rem', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}}' => '--lakit-filter-item-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_list_item_border',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakit-woofilters_block_item__filter li,{{WRAPPER}} .lakit-woofilters_block_item__filter ul.children li',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'filter_list_item_shadow',
                'selector' => '{{WRAPPER}} .lakit-woofilters_block_item__filter li,{{WRAPPER}} .lakit-woofilters_block_item__filter ul.children li',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_filter_list_item_active',
            array(
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            )
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'filter_list_item_font_active',
                'selector' => '{{WRAPPER}} .lakit-woofilters_block_item__filter li:hover,{{WRAPPER}} .lakit-woofilters_block_item__filter li.active,{{WRAPPER}} .lakit-woofilters_block_item__filter ul.children li.active,{{WRAPPER}} .lakit-woofilters_block_item__filter ul.children li:hover',
            ]
        );
        $this->add_control(
            'filter_list_item_color_active',
            [
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-filter-active-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'filter_list_item_bgcolor_active',
            [
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-filter-active-bgcolor: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'filter_list_item_padding_active',
            array(
                'label'       => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', 'rem' ),
                'selectors'   => array(
                    '{{WRAPPER}}' => '--lakit-filter-item-active-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_list_item_radius_active',
            array(
                'label'       => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', 'rem', '%' ),
                'selectors'   => array(
                    '{{WRAPPER}}' => '--lakit-filter-item-active-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_list_item_border_active',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakit-woofilters_block_item__filter li:hover,{{WRAPPER}} .lakit-woofilters_block_item__filter li.active,{{WRAPPER}} .lakit-woofilters_block_item__filter ul.children li.active,{{WRAPPER}} .lakit-woofilters_block_item__filter ul.children li:hover',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'filter_list_item_shadow_active',
                'selector' => '{{WRAPPER}} .lakit-woofilters_block_item__filter li:hover,{{WRAPPER}} .lakit-woofilters_block_item__filter li.active,{{WRAPPER}} .lakit-woofilters_block_item__filter ul.children li.active,{{WRAPPER}} .lakit-woofilters_block_item__filter ul.children li:hover',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_filter_list_item_tag_style',
            array(
                'label' => esc_html__('Filter Item Tags', 'lastudio-kit'),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'filter_list_item_tag_gap',
            [
                'label' => esc_html__('Item gap', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .lakit-wfi-source_tag_list' => '--lakit-filter-item-gap: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->start_controls_tabs('tabs_filter_list_item_tag');
        $this->start_controls_tab(
            'tab_filter_list_item_tag_normal',
            array(
                'label' => esc_html__('Normal', 'lastudio-kit'),
            )
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'filter_list_item_tag_font',
                'selector' => '{{WRAPPER}} .lakit-wfi-source_tag_list .lakit-woofilters_block_item__filter li',
            ]
        );
        $this->add_control(
            'filter_list_item_tag_color',
            [
                'label' => esc_html__('Color', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-wfi-source_tag_list' => '--lakit-filter-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'filter_list_item_tag_bgcolor',
            [
                'label' => esc_html__('Background Color', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-wfi-source_tag_list' => '--lakit-filter-bgcolor: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'filter_list_item_tag_padding',
            array(
                'label' => esc_html__('Padding', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', 'em', 'rem'),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-wfi-source_tag_list' => '--lakit-filter-item-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_list_item_tag_radius',
            array(
                'label' => esc_html__('Border Radius', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', 'rem', '%' ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-wfi-source_tag_list' => '--lakit-filter-item-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_list_item_tag_border',
                'label' => esc_html__('Border', 'lastudio-kit'),
                'selector' => '{{WRAPPER}} .lakit-wfi-source_tag_list .lakit-woofilters_block_item__filter li',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'filter_list_item_tag_shadow',
                'selector' => '{{WRAPPER}} .lakit-wfi-source_tag_list .lakit-woofilters_block_item__filter li',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_filter_list_item_tag_active',
            array(
                'label' => esc_html__('Active', 'lastudio-kit'),
            )
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'filter_list_item_tag_font_active',
                'selector' => '{{WRAPPER}} .lakit-wfi-source_tag_list .lakit-woofilters_block_item__filter li:hover,{{WRAPPER}} .lakit-wfi-source_tag_list .lakit-woofilters_block_item__filter li.active',
            ]
        );
        $this->add_control(
            'filter_list_item_tag_color_active',
            [
                'label' => esc_html__('Color', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-wfi-source_tag_list' => '--lakit-filter-active-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'filter_list_item_tag_bgcolor_active',
            [
                'label' => esc_html__('Background Color', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-wfi-source_tag_list' => '--lakit-filter-active-bgcolor: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'filter_list_item_tag_padding_active',
            array(
                'label' => esc_html__('Padding', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', 'em', 'rem'),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-wfi-source_tag_list' => '--lakit-filter-item-active-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_list_item_tag_radius_active',
            array(
                'label' => esc_html__('Border Radius', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px', 'em', 'rem', '%' ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-wfi-source_tag_list' => '--lakit-filter-item-active-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_list_item_tag_border_active',
                'label' => esc_html__('Border', 'lastudio-kit'),
                'selector' => '{{WRAPPER}} .lakit-wfi-source_tag_list .lakit-woofilters_block_item__filter li:hover,{{WRAPPER}} .lakit-wfi-source_tag_list .lakit-woofilters_block_item__filter li.active',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'filter_list_item_tag_shadow_active',
                'selector' => '{{WRAPPER}} .lakit-wfi-source_tag_list .lakit-woofilters_block_item__filter li:hover,{{WRAPPER}} .lakit-wfi-source_tag_list .lakit-woofilters_block_item__filter li.active',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();


        $this->start_controls_section(
            'section_filter_list_item_attr_list_style',
            array(
                'label' => esc_html__('Filter Attribute List', 'lastudio-kit'),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'filter_list_item_attr_list_gap',
            [
                'label' => esc_html__('Item gap', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_list' => '--lakit-filter-item-gap: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->start_controls_tabs('tabs_filter_list_item_attr_list');
        $this->start_controls_tab(
            'tab_filter_list_item_attr_list_normal',
            array(
                'label' => esc_html__('Normal', 'lastudio-kit'),
            )
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'filter_list_item_attr_list_font',
                'selector' => '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_list .lakit-woofilters_block_item__filter li',
            ]
        );
        $this->add_control(
            'filter_list_item_attr_list_color',
            [
                'label' => esc_html__('Color', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_list' => '--lakit-filter-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'filter_list_item_attr_list_bgcolor',
            [
                'label' => esc_html__('Background Color', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_list' => '--lakit-filter-bgcolor: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'filter_list_item_attr_list_padding',
            array(
                'label' => esc_html__('Padding', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', 'em', 'rem'),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_list' => '--lakit-filter-item-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_list_item_attr_list_radius',
            array(
                'label' => esc_html__('Border Radius', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', 'em', 'rem', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_list' => '--lakit-filter-item-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_list_item_attr_list_border',
                'label' => esc_html__('Border', 'lastudio-kit'),
                'selector' => '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_list .lakit-woofilters_block_item__filter li',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'filter_list_item_attr_list_shadow',
                'selector' => '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_list .lakit-woofilters_block_item__filter li',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_filter_list_item_attr_list_active',
            array(
                'label' => esc_html__('Active', 'lastudio-kit'),
            )
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'filter_list_item_attr_list_font_active',
                'selector' => '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_list .lakit-woofilters_block_item__filter li:hover,{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_list .lakit-woofilters_block_item__filter li.active',
            ]
        );
        $this->add_control(
            'filter_list_item_attr_list_color_active',
            [
                'label' => esc_html__('Color', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_list' => '--lakit-filter-active-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'filter_list_item_attr_list_bgcolor_active',
            [
                'label' => esc_html__('Background Color', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_list' => '--lakit-filter-active-bgcolor: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'filter_list_item_attr_list_padding_active',
            array(
                'label' => esc_html__('Padding', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', 'em', 'rem'),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_list' => '--lakit-filter-item-active-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_list_item_attr_list_radius_active',
            array(
                'label' => esc_html__('Border Radius', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', 'em', 'rem', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_list' => '--lakit-filter-item-active-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_list_item_attr_list_border_active',
                'label' => esc_html__('Border', 'lastudio-kit'),
                'selector' => '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_list .lakit-woofilters_block_item__filter li:hover,{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_list .lakit-woofilters_block_item__filter li.active',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'filter_list_item_attr_list_shadow_active',
                'selector' => '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_list .lakit-woofilters_block_item__filter li:hover,{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_list .lakit-woofilters_block_item__filter li.active',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_filter_list_item_attr_swatch_style',
            array(
                'label' => esc_html__('Filter Attribute Swatches', 'lastudio-kit'),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'filter_list_item_attr_swatch_gap',
            [
                'label' => esc_html__('Item gap', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_swatch' => '--lakit-filter-item-gap: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'filter_list_item_attr_swatch_width',
            [
                'label' => esc_html__('Width', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', '%'],
                'selectors' => [
                    '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_swatch .swatch-anchor' => 'width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'filter_list_item_attr_swatch_height',
            [
                'label' => esc_html__('Height', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', '%'],
                'selectors' => [
                    '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_swatch .swatch-anchor' => 'height: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'filter_list_item_attr_swatch_radius',
            [
                'label' => esc_html__('Border Radius', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', '%'],
                'selectors' => [
                    '{{WRAPPER}} .lakit-wfi-source_product_attribute.lakit-wfi-type_swatch .swatch-anchor' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_filter_list_dropdown_style',
            array(
                'label' => esc_html__( 'Filter DropDown', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'filter_list_item_dd_font',
                'selector' => '{{WRAPPER}} .woocommerce-result-count,{{WRAPPER}} .lakit-dropdown--label, {{WRAPPER}} select',
            ]
        );
        $this->add_control(
            'filter_list_item_dd_color',
            [
                'label' => esc_html__('Color', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-result-count' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-dropdown--label' => 'color: {{VALUE}}',
                    '{{WRAPPER}} select' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'filter_list_item_dd_padding',
            array(
                'label' => esc_html__('Padding', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', 'em', 'rem', '%'),
                'selectors' => array(
                    '{{WRAPPER}} select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-dropdown--label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_list_item_dd_border',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} select, {{WRAPPER}} .lakit-dropdown--label',
            ]
        );
        $this->add_responsive_control(
            'filter_list_item_dd_radius',
            array(
                'label'       => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} select, {{WRAPPER}} .lakit-dropdown--label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );
        $this->end_controls_section();
    }

    protected function render() {
        $layout = $this->get_settings_for_display('layout');
        $type = $this->get_settings_for_display('type');
        $filters_label = $this->_get_html('filter_label', '<span class="woofilter-litem-title">%s</span>');
        $filters_icon = $this->_get_icon_setting(  $this->get_settings_for_display('filter_icon'), '<span class="woofilter-litem-icon elementor-button-icon">%s</span>');
        $item_as_dropdown = wc_string_to_bool($this->get_settings_for_display('item_as_dropdown')) ? 'lakit-woofilters--item_dd' : '';

        $filters = $this->get_settings_for_display('filters');
        $this->_processed_item = false;
        if(!empty($filters)){
            echo sprintf('<div class="lakit-woofilters lakit-woofilters--layout_%1$s lakit-woofilters--type_%2$s %3$s">', $layout, $type, $item_as_dropdown);
            if($layout != 'default'){
                echo sprintf('<div class="lakit-woofilters_block_label"><span class="woofilter-litem">%1$s%2$s</span></div>', $filters_label, $filters_icon);
            }
            echo '<div class="lakit-woofilters_area">';
            echo '<div class="lakit-woofilters_block">';
            foreach ($filters as $filter){
                $this->_processed_item = $filter;

                $el_class = 'lakit-wfi-source_' . $filter['filter_source'];

                if(!empty($filter['type'])){
                    $el_class .= ' lakit-wfi-type_' . $filter['type'];
                }
                if(!empty($filter['attribute'])){
                    $el_class .= ' lakit-wfi-attr_' . $filter['attribute'];
                    $el_class .= ' lakit-wfi-attr_qtype_' . $filter['query_type'];
                }
                if( wc_string_to_bool( $filter['inline'] ) ){
                    $el_class .= ' lakit-wfi-type_list_inline b--inline';
                }
                else{
                    $el_class .= ' b--normal';
                }

                if(!empty($filter['hide_on'])){
                    $el_class .= ' elementor-hidden-' . join(' elementor-hidden-', $filter['hide_on']);
                }

                $block_title_html = '';
                $item_label = !empty($filter['filter_label']) ? '<span class="woofilter-bitem-title">'.$filter['filter_label'].'</span>' : '';
                $item_icon = $this->_get_icon_setting($filter['filter_icon'], '<span class="woofilter-bitem-icon elementor-button-icon">%s</span>');
                if(!empty($item_label) || !empty($item_icon)){
                    $block_title_html = sprintf('<div class="lakit-woofilters_block_item__title"><span class="woofilter-bitem">%1$s%2$s</span></div>', $item_label, $item_icon);
                }
                $block_filter_html = sprintf(
                    '<div class="lakit-woofilters_block_item__filter">[lakit_woofilter_item source="%1$s" type="%2$s" attribute="%3$s" inline="%4$s" show_count="%5$s" price="%6$s" query_type="%7$s"]</div>',
                    esc_attr($filter['filter_source']),
                    esc_attr($filter['type']),
                    esc_attr($filter['attribute']),
                    esc_attr($filter['inline']),
                    esc_attr($filter['show_count']),
                    esc_attr($filter['filter_price_list']),
                    esc_attr($filter['query_type'])
                );

                echo sprintf(
                    '<div class="lakit-woofilters_block_item elementor-repeater-item-%1$s %4$s">%2$s%3$s</div>',
                    esc_attr($filter['_id']),
                    $block_title_html,
                    $block_filter_html,
                    esc_attr($el_class)
                );
                $this->_processed_index++;
            }

            $this->_processed_item = false;
            $this->_processed_index = 0;

            echo '</div>';
            echo '</div>';
            echo '<div class="lakit-woofilters_area__overlay"></div>';
            echo '</div>';
        }
    }

}