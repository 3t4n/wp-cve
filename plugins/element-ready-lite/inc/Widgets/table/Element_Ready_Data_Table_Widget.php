<?php

namespace Element_Ready\Widgets\table;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Repeater;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

require_once(ELEMENT_READY_DIR_PATH . '/inc/style_controls/common/common.php');
require_once(ELEMENT_READY_DIR_PATH . '/inc/style_controls/position/position.php');
require_once(ELEMENT_READY_DIR_PATH . '/inc/style_controls/box/box_style.php');
require_once(ELEMENT_READY_DIR_PATH . '/inc/content_controls/common.php');

class Element_Ready_Data_Table_Widget extends Widget_Base
{

    use \Elementor\Element_Ready_Common_Style;
    use \Elementor\Element_ready_common_content;
    use \Elementor\Element_Ready_Box_Style;
    public function get_name()
    {
        return 'Element_Ready_Data_Table_Widget';
    }

    public function get_title()
    {
        return esc_html__('ER Data Table', 'element-ready-lite');
    }

    public function get_icon()
    {
        return 'eicon-table';
    }

    public function get_categories()
    {
        return ['element-ready-addons'];
    }

    public function get_keywords()
    {
        return ['Table', 'ER Data Table', 'Tables'];
    }

    public function get_script_depends()
    {
        return ['datatables'];
        return ['element-ready-core'];
    }

    public function get_style_depends()
    {
        return ['datatables'];
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'datatable_layout',
            [
                'label' => esc_html__('Table Layout', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'datatable_style',
            [
                'label' => esc_html__('Layout', 'element-ready-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => esc_html__('Layout One', 'element-ready-lite'),
                    '2' => esc_html__('Layout Two', 'element-ready-lite'),
                    '3' => esc_html__('Layout Three', 'element-ready-lite'),
                ],
            ]
        );

        $this->add_control(
            'show_datatable_sorting',
            [
                'label' => esc_html__('Show Sorting Options', 'element-ready-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-ready-lite'),
                'label_off' => esc_html__('Hide', 'element-ready-lite'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

        // Sorting Options
        $this->start_controls_section(
            'datatable_sorting_options',
            [
                'label' => esc_html__('Sorting Options', 'element-ready-lite'),
                'condition' => [
                    'show_datatable_sorting' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'show_datatable_paging',
            [
                'label' => esc_html__('Pagination', 'element-ready-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-ready-lite'),
                'label_off' => esc_html__('Hide', 'element-ready-lite'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'show_datatable_searching',
            [
                'label' => esc_html__('Searching', 'element-ready-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-ready-lite'),
                'label_off' => esc_html__('Hide', 'element-ready-lite'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'show_datatable_ordering',
            [
                'label' => esc_html__('Ordering', 'element-ready-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-ready-lite'),
                'label_off' => esc_html__('Hide', 'element-ready-lite'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'show_datatable_info',
            [
                'label' => esc_html__('Footer Info', 'element-ready-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-ready-lite'),
                'label_off' => esc_html__('Hide', 'element-ready-lite'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

        // Table Header
        $this->start_controls_section(
            'datatable_header',
            [
                'label' => esc_html__('Table Header', 'element-ready-lite'),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'column_name',
            [
                'label' => esc_html__('Column Name', 'element-ready-lite'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('No', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'header_column_list',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'column_name' => esc_html__('No', 'element-ready-lite'),
                    ],

                    [
                        'column_name' => esc_html__('Name', 'element-ready-lite'),
                    ],

                    [
                        'column_name' => esc_html__('Designation', 'element-ready-lite'),
                    ],

                    [
                        'column_name' => esc_html__('Email', 'element-ready-lite'),
                    ]

                ],
                'title_field' => '{{{ column_name }}}',
            ]
        );

        $this->end_controls_section();

        // Table Content
        $this->start_controls_section(
            'datatable_content',
            [
                'label' => esc_html__('Table Content', 'element-ready-lite'),
            ]
        );

        $repeater_one = new \Elementor\Repeater();

        $repeater_one->add_control(
            'field_type',
            [
                'label' => esc_html__('Fild Type', 'element-ready-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'row',
                'options' => [
                    'row' => esc_html__('Row', 'element-ready-lite'),
                    'col' => esc_html__('Column', 'element-ready-lite'),
                ],
            ]
        );

        $repeater_one->add_control(
            'cell_text',
            [
                'label' => esc_html__('Cell Content', 'element-ready-lite'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Abdur Rohman', 'element-ready-lite'),
                'condition' => [
                    'field_type' => 'col',
                ]
            ]
        );

        $repeater_one->add_control(
            'cell_icon',
            [
                'label' => __('Icon', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
                'condition' => [
                    'field_type' => 'col',
                ]
            ]
        );


        $repeater_one->add_control(
            'row_colspan',
            [
                'label' => esc_html__('Colspan', 'element-ready-lite'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 1,
                'condition' => [
                    'field_type' => 'col',
                ]
            ]
        );

        $this->add_control(
            'content_list',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_one->get_controls(),
                'default' => [
                    [
                        'field_type' => esc_html__('row', 'element-ready-lite'),
                    ],

                    [
                        'field_type' => esc_html__('col', 'element-ready-lite'),
                        'cell_text' => esc_html__('1', 'element-ready-lite'),
                        'row_colspan' => esc_html__('1', 'element-ready-lite'),
                    ],

                    [
                        'field_type' => esc_html__('col', 'element-ready-lite'),
                        'cell_text' => esc_html__('Abdur Rohman', 'element-ready-lite'),
                        'row_colspan' => esc_html__('1', 'element-ready-lite'),
                    ],

                    [
                        'field_type' => esc_html__('col', 'element-ready-lite'),
                        'cell_text' => esc_html__('Developer', 'element-ready-lite'),
                        'row_colspan' => esc_html__('1', 'element-ready-lite'),
                    ],


                    [
                        'field_type' => esc_html__('col', 'element-ready-lite'),
                        'cell_text' => esc_html__('admin@gmail.com', 'element-ready-lite'),
                        'row_colspan' => esc_html__('1', 'element-ready-lite'),
                    ]

                ],
                'title_field' => '{{{field_type}}}',
            ]
        );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            '_table_style_section',
            [
                'label' => esc_html__('Table', 'element-ready-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'datatable_bg_color',
            [
                'label' => esc_html__('Background Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .element__ready__datatable' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'datatable_padding',
            [
                'label' => esc_html__('Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__datatable' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'datatable_margin',
            [
                'label' => esc_html__('Margin', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__datatable' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'datatable_border',
                'label' => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__datatable',
            ]
        );

        $this->add_responsive_control(
            'datatable_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__datatable' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->end_controls_section();

        // Table Header Style tab section
        $this->start_controls_section(
            '_table_header_style_section',
            [
                'label' => esc_html__('Table Header', 'element-ready-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'datatable_header_bg_color',
            [
                'label' => esc_html__('Background Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .element__ready__datatable thead tr th' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'datatable_header_text_color',
            [
                'label' => esc_html__('Text Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .element__ready__datatable thead tr th' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'datatable_header_typography',
                'label' => esc_html__('Typography', 'element-ready-lite'),

                'selector' => '{{WRAPPER}} .element__ready__datatable thead tr th',
            ]
        );

        $this->add_responsive_control(
            'datatable_header_padding',
            [
                'label' => esc_html__('Table Header Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__datatable thead tr th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'datatable_header_border',
                'label' => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__datatable thead tr th',
            ]
        );

        $this->add_responsive_control(
            'datatable_header_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__datatable thead tr th' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'datatable_header_align',
            [
                'label' => esc_html__('Alignment', 'element-ready-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'element-ready-lite'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__datatable thead tr th' => 'text-align: {{VALUE}};',
                ],
                'default' => '',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'datatable_header_first_align',
            [
                'label' => esc_html__('First Column Alignment', 'element-ready-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'element-ready-lite'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__datatable thead tr th:first-child' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .element__ready__datatable tr td:first-child' => 'text-align: {{VALUE}};',
                ],
                'default' => '',
                'separator' => 'before',
            ]
        );


        $this->end_controls_section();

        $this->element_size(
            array(
                'title' => esc_html__('Col Width', 'element-ready-lite'),
                'slug' => 'header_wcol_width_box_style',
                'element_name' => '_col_width_element_ready_',
                'selector' => '{{WRAPPER}} .element__ready__datatable tr td:first-child',
                'hover_selector' => false,
            )
        );
        // Table Body Style tab section
        $this->start_controls_section(
            '_table_body_style_section',
            [
                'label' => esc_html__('Table Body', 'element-ready-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'datatable_body_bg_color',
            [
                'label' => esc_html__('Background Color ( Event )', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .element__ready__datatable tbody tr:nth-child(even)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'datatable_body_odd_bg_color',
            [
                'label' => esc_html__('Background Color ( Odd )', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .element__ready__datatable tbody tr' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'datatable_body_text_color',
            [
                'label' => esc_html__('Text Color', 'element-ready-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .element__ready__datatable tbody tr td' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'datatable_body_typography',
                'label' => esc_html__('Typography', 'element-ready-lite'),

                'selector' => '{{WRAPPER}} .element__ready__datatable tbody tr td',
            ]
        );

        $this->add_responsive_control(
            'datatable_body_padding',
            [
                'label' => esc_html__('Table Body Padding', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__datatable tbody tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'datatable_body_border',
                'label' => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__datatable tbody tr td',
            ]
        );

        $this->add_responsive_control(
            'datatable_body_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-ready-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__datatable tbody tr td' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'datatable_body_align',
            [
                'label' => esc_html__('Alignment', 'element-ready-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'element-ready-lite'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__datatable tbody tr td' => 'text-align: {{VALUE}};',
                ],
                'default' => '',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->text_wrapper_css(
            array(
                'title' => esc_html__('Search label', 'element-ready-lite'),
                'slug' => 'search_label_box_style',
                'element_name' => '_search_alabel_wrapper_element_ready_',
                'selector' => '{{WRAPPER}} .dataTables_filter label',
                'hover_selector' => false,
            )
        );

        $this->text_wrapper_css(
            array(
                'title' => esc_html__('Search input', 'element-ready-lite'),
                'slug' => 'search_input_box_style',
                'element_name' => '_search_input_wrapper_element_ready_',
                'selector' => '{{WRAPPER}} .dataTables_filter input',
            )
        );

        $this->box_css(
            array(
                'title' => esc_html__('Header Page wrapper', 'element-ready-lite'),
                'slug' => 'header_pagination_box_style',
                'element_name' => '_header_wrapper_element_ready_',
                'selector' => '{{WRAPPER}} .dataTables_wrapper .dataTables_length select',
                'hover_selector' => false,
            )
        );

        $this->text_wrapper_css(
            array(
                'title' => esc_html__('Page option text', 'element-ready-lite'),
                'slug' => 'header_page_option_text_box_style',
                'element_name' => '_header_select_option_element_ready_',
                'selector' => '{{WRAPPER}} .dataTables_wrapper .dataTables_length select option',
                'hover_selector' => false,
            )
        );

        $this->text_wrapper_css(
            array(
                'title' => esc_html__('Header Page text', 'element-ready-lite'),
                'slug' => 'header_page_text_box_style',
                'element_name' => '_header_wrappr_element_ready_',
                'selector' => '{{WRAPPER}} .dataTables_wrapper .dataTables_length label',
                'hover_selector' => false,
            )
        );


        $this->text_wrapper_css(
            array(
                'title' => esc_html__('Pagination Link', 'element-ready-lite'),
                'slug' => 'footer_pagination_box_style',
                'element_name' => '_footer_wrapper_element_ready_',
                'selector' => '{{WRAPPER}} .dataTables_wrapper .dataTables_paginate .paginate_button',
                'hover_selector' => '{{WRAPPER}} .dataTables_wrapper .dataTables_paginate .paginate_button:hover',
            )
        );

        $this->text_wrapper_css(
            array(
                'title' => esc_html__('Pagination Link Active', 'element-ready-lite'),
                'slug' => 'footer_pagination__activebox_style',
                'element_name' => '_footer_active_wrapper_element_ready_',
                'selector' => '{{WRAPPER}} .dataTables_wrapper .dataTables_paginate .paginate_button.current',
                'hover_selector' => '{{WRAPPER}} .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover',
            )
        );

        $this->text_wrapper_css(
            array(
                'title' => esc_html__('Footer info ', 'element-ready-lite'),
                'slug' => 'footer_info_ebox_style',
                'element_name' => '_footer_info_wrapper_element_ready_',
                'selector' => '{{WRAPPER}} .dataTables_info',
                'hover_selector' => false,
            )
        );

    }

    protected function render($instance = [])
    {

        $settings = $this->get_settings_for_display();
        $id = $this->get_id();

        $this->add_render_attribute('datatable_attr', 'class', 'element__ready__datatable element__ready__datatable__style__' . $settings['datatable_style']);

        if ($settings['show_datatable_sorting'] != 'yes') {
            $this->add_render_attribute('datatable_attr', 'class', 'table-responsive');
        }

        $table_tr = array();
        $table_td = array();

        foreach ($settings['content_list'] as $content_row) {

            $row_id = rand(0, 1000);
            if ($content_row['field_type'] == 'row') {
                $table_tr[] = [
                    'id' => $row_id,
                    'type' => $content_row['field_type'],
                ];
            }
            if ($content_row['field_type'] == 'col') {

                $table_tr_keys = array_keys($table_tr);
                $last_key = end($table_tr_keys);

                $table_td[] = [
                    'row_id' => $table_tr[$last_key]['id'],
                    'title' => $content_row['cell_text'] . element_ready_render_icons($content_row['cell_icon']),
                    'colspan' => $content_row['row_colspan'],
                ];
            }
        }

        $options_array = array(
            'id' => $id,
            'show_pagi' => $settings['show_datatable_paging'] == 'yes' ? 'true' : 'false',
            'show_searching' => $settings['show_datatable_searching'] == 'yes' ? 'true' : 'false',
            'ordering' => $settings['show_datatable_ordering'] == 'yes' ? 'true' : 'false',
            'info' => $settings['show_datatable_info'] == 'yes' ? 'true' : 'false'
        );

        $this->add_render_attribute('datatable_attr', 'data-options', json_encode($options_array));

        ?>
        <div <?php echo $this->get_render_attribute_string('datatable_attr'); ?>>
            <table class="<?php echo 'element__ready__datatable__' . esc_attr($id); ?>">
                <?php if ($settings['header_column_list']): ?>
                    <thead>
                        <tr>
                            <?php
                            foreach ($settings['header_column_list'] as $headeritem) {
                                echo wp_kses_post(sprintf('<th>%s</th>', esc_html($headeritem['column_name'])));
                            }
                            ?>
                        </tr>
                    </thead>
                <?php endif; ?>
                <tbody>
                    <?php for ($i = 0; $i < count($table_tr); $i++): ?>
                        <tr>
                            <?php
                            for ($j = 0; $j < count($table_td); $j++):
                                if ($table_tr[$i]['id'] == $table_td[$j]['row_id']):
                                    ?>
                                    <td<?php echo esc_attr($table_td[$j]['colspan']) > 1 ? ' colspan="' . $table_td[$j]['colspan'] . '"' : ''; ?>>
                                        <?php echo wp_kses_post($table_td[$j]['title']); ?>
                                        </td>
                                        <?php
                                endif;
                            endfor; ?>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}