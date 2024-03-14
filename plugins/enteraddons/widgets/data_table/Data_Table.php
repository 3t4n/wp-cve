<?php
namespace Enteraddons\Widgets\Data_Table;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * Enteraddons elementor Data Table widget.
 *
 * @since 1.0
 */

class Data_Table extends Widget_Base {
    
	public function get_name() {
		return 'enteraddons-data-table';
	}

	public function get_title() {
		return esc_html__( 'Data Table', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-data-table';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

        $repeater = new \Elementor\Repeater();
        // ---------------------------------------- Data Table Heading ------------------------------

        $this->start_controls_section(
            'enteraddons_data_table_content_settings',
            [
                'label' => esc_html__( 'Data Table Heading', 'enteraddons' ),
            ]
        ); 
        $repeater->add_control(
            'dp_heading_text', 
            [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Enteraddons' , 'enteraddons' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
			'show_icon',
			[
				'label' => esc_html__( 'Show Icon', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'enteraddons' ),
				'label_off' => esc_html__( 'Hide', 'enteraddons' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
        $repeater->add_control(
            'heading_icon_type',
            [
                'label' => esc_html__( 'Icon Type', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'icon' => esc_html__( 'Icon', 'enteraddons' ),
                    'img'  => esc_html__( 'Image', 'enteraddons' ),
                ],
                'condition'	=>[
                    'show_icon'  => 'yes'
                ],
            ]
        );
        $repeater->add_control(
            'dp_heading_icon',
            [
                'label' => esc_html__( 'Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-check',
                    'library' => 'solid',
                ],
                'condition'	=>[
                    'heading_icon_type'  => 'icon',
                    'show_icon'  => 'yes'
                ],
            ]
        );
        $repeater->add_control(
            'dp_heading_image',
            [
                'label' => esc_html__( 'Image', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                     'heading_icon_type' => 'img' ,
                     'show_icon'  => 'yes'
                    ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_control(
            'dp_heading_content_repetable',
            [
                'label' => esc_html__( 'Heading Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'dp_heading_text' => esc_html__( 'Domain', 'enteraddons' ),
                    ],
                    [
                        'dp_heading_text' => esc_html__( '1 Year', 'enteraddons' ),
                        
                    ],
                    [
                        'dp_heading_text' => esc_html__( '2 Year', 'enteraddons' ),
                        
                    ],
                    [
                        'dp_heading_text' => esc_html__( '10 Year', 'enteraddons' ),
                    ],
                    [
                        'dp_heading_text' => esc_html__( 'Renew', 'enteraddons' ),
                        
                    ],
                    [
                        'dp_heading_text' => esc_html__( 'Transfer', 'enteraddons' ),
                    ],
                    [
                        'dp_heading_text' => esc_html__( 'Order', 'enteraddons' ),
                        
                    ],
                       
                ],
                'title_field' => '{{{ dp_heading_text }}}',
            ]
        );
        $this->end_controls_section();

        // ---------------------------------------- Data Table content ------------------------------

        $this->start_controls_section(
            'table_body_content',
            [
                'label'	=> esc_html__('Table Content','enteraddons'),
            ]
        );

        $_repeater = new \Elementor\Repeater();

        $_repeater->add_control(
            'tbody_condition',
            [
                'label' => esc_html__( 'Row/Column', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'row',
                'options' => [
                    'row' => esc_html__( 'Row', 'enteraddons'),
                    'col' => esc_html__( 'Column', 'enteraddons'),
                    
                ],
            ]
        );
        $_repeater->add_control(
            'tbody_content_condition',
            [
                'label' => esc_html__( 'Select', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'contents',
                'options' => [
                    'contents' => esc_html__( 'Content', 'enteraddons'),
                    'btn' => esc_html__( 'Button', 'enteraddons'),
                    
                ],
                'condition'=> [
                    'tbody_condition'	=> 'col'
                ],
            ]
        );
        $_repeater->add_control(
			'ea_show_icon',
			[
				'label' => esc_html__( 'Show Icon', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
                'condition'	=>[
                    'tbody_content_condition'	=> 'contents',
                ],
				'label_on' => esc_html__( 'Show', 'enteraddons' ),
				'label_off' => esc_html__( 'Hide', 'enteraddons' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
        $_repeater->add_control(
            'content_title', [
                'label' => esc_html__( 'Content Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Content Title' , 'enteraddons' ),
                'label_block' => true,
                'condition'	=>[

                    'tbody_content_condition'	=> 'contents',
                ],
            ]
        );
        $_repeater->add_control(
            'btn_title', [
                'label' => esc_html__( 'Button Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Buy How' , 'enteraddons' ),
                'label_block' => true,
                'condition'	=>[

                    'tbody_content_condition'	=> 'btn',
                ],
            ]
        );
        $_repeater->add_control(
            'btn_links',
            [
                'label' => esc_html__( 'Button Link', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'enteraddons' ),
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                    'custom_attributes' => '',
                ],
                'label_block' => true,
                'condition'=> [
                    'tbody_content_condition'=>'btn'
                ],
            ]
        );
        $_repeater->add_control(
            'icon_type',
            [
                'label' => esc_html__( 'Icon Type', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'condition'	=>[

                    'tbody_content_condition'	=> 'contents',
                    'ea_show_icon'  => 'yes'
                ],
                'default' => 'icon',
                'options' => [
                    'icon' => esc_html__( 'Icon', 'enteraddons' ),
                    'img'  => esc_html__( 'Image', 'enteraddons' ),
                ],
            ]
        );
        $_repeater->add_control(
            'tbody_icon',
            [
                'label' => esc_html__( 'Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-check',
                    'library' => 'solid',
                ],
                'condition'	=>[
                    'tbody_content_condition'	=> 'contents',
                    'icon_type'  => 'icon',
                    'ea_show_icon'  => 'yes'
                ],
            ]
        );
        $_repeater->add_control(
            'tbody_image',
            [
                'label' => esc_html__( 'Image', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                     'tbody_content_condition'	=> 'contents',
                     'icon_type' => 'img' ,
                     'ea_show_icon'  => 'yes'
                    ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_control(
            'tbody_list',
            [
                'label' => esc_html__( 'Content List', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $_repeater->get_controls(),
                'default'=>[
                    [
                        'tbody_condition'=>'col',
                        'tbody_content_condition'=>'contents',
                        'content_title'=>esc_html__('Content Title','enteraddons'),
                    ],
                    [
                        'tbody_condition'=>'col',
                        'tbody_content_condition'=>'contents',
                        'content_title'=>esc_html__('Content Title','enteraddons'),
                    ],
                    [
                        'tbody_condition'=>'col',
                        'tbody_content_condition'=>'contents',
                        'content_title'=>esc_html__('Content Title','enteraddons'),
                    ],
                    [
                        'tbody_condition'=>'col',
                        'tbody_content_condition'=>'contents',
                        'content_title'=>esc_html__('Content Title','enteraddons'),
                    ],
                    [
                        'tbody_condition'=>'col',
                        'tbody_content_condition'=>'contents',
                        'content_title'=>esc_html__('Content Title','enteraddons'),
                    ],
                    [
                        'tbody_condition'=>'col',
                        'tbody_content_condition'=>'contents',
                        'content_title'=>esc_html__('Content Title','enteraddons'),
                    ],
                    [
                        'tbody_condition'=>'col',
                        'tbody_content_condition'=>'btn',
                        'btn_title'=>esc_html__( 'Button Title', 'enteraddons' ),
                    ],
                    [
                        'tbody_condition'=>'row',
                    ],
                    [
                        'tbody_condition'=>'col',
                        'tbody_content_condition'=>'contents',
                        'content_title'=>esc_html__('Content Title','enteraddons'),
                    ],
                    
                    [
                        'tbody_condition'=>'col',
                        'tbody_content_condition'=>'contents',
                        'content_title'=>esc_html__('Content Title','enteraddons'),
                    ],
                    [
                        'tbody_condition'=>'col',
                        'tbody_content_condition'=>'contents',
                        'content_title'=>esc_html__('Content Title','enteraddons'),
                    ],
                    [
                        'tbody_condition'=>'col',
                        'tbody_content_condition'=>'contents',
                        'content_title'=>esc_html__('Content Title','enteraddons'),
                    ],
                    [
                        'tbody_condition'=>'col',
                        'tbody_content_condition'=>'contents',
                        'content_title'=>esc_html__('Content Title','enteraddons'),
                    ],
                    [
                        'tbody_condition'=>'col',
                        'tbody_content_condition'=>'contents',
                        'content_title'=>esc_html__('Content Title','enteraddons'),
                    ],
                    [
                        'tbody_condition'=>'col',
                        'tbody_content_condition'=>'btn',
                        'btn_title'=>esc_html__( 'Button Title', 'enteraddons' ),
                    ],
  
                ],
                'title_field' => '{{{ tbody_condition }}}',
            ]
        );

        $this->end_controls_section();

        // ---------------------------------------- Data Table Wrapper Style ------------------------------

        $this->start_controls_section(
            'general_style',
            [
                'label' => esc_html__( 'Wrapper Style', 'enteraddons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE
            ]
        );
        $this->add_responsive_control(
            'table_width',
            [
                'label'      => esc_html__('Width', 'enteraddons'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range'      => [
                    '%'  => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1200,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ea-table-wrapper table' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'table_border',
                    'label' => esc_html__( 'Border', 'enteraddons'),
                    'selector' => '{{WRAPPER}} .ea-table-wrapper table ',
                ]
        );
        $this->add_responsive_control(
            'wrapper_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-table-wrapper table ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'table_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-table-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'table_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-table-wrapper table' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'table_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-table-wrapper table',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'table_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-table-wrapper table',
            ]
        );

        $this->end_controls_section();

        // ---------------------------------------- Data Table Heading Style ------------------------------

        $this->start_controls_section(
            'table_heading_style',
            [
                'label' => esc_html__( 'Heading Style', 'enteraddons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'table_section_header_radius',
            [
                'label' => esc_html__( 'Header Border Radius', 'enteraddons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],

                'selectors' => [ 
                    '{{WRAPPER}} .ea-table-wrapper table thead tr th:first-child' => 'border-radius: {{SIZE}}px 0px 0px 0px;',
                    '{{WRAPPER}} .ea-table-wrapper table thead tr th:last-child' => 'border-radius: 0px {{SIZE}}px 0px 0px;',
                ],
            ]
        );
        $this->add_responsive_control(
            'data_table_each_header_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-table-wrapper table thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                 'name' => 'data_table_header_title_typography',
                'selector' => '{{WRAPPER}} .ea-table-wrapper table thead tr th',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'data_table_header_title_text_stroke',
                'selector' => '{{WRAPPER}} .ea-table-wrapper table thead tr th',
            ]
        );
        $this->add_responsive_control(
            'heading_icon_position',
            [
                'label' => esc_html__( 'Icon Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'row' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'column' => [
                        'title' => esc_html__( 'Top', 'enteraddons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'column-reverse' => [
                        'title' => esc_html__( 'Bottom', 'enteraddons' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'row',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ea-heading-content' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'data_table_header_title_alignment',
            [
                'label' => esc_html__( 'Title Alignment', 'enteraddons'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'enteraddons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'enteraddons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}}  .ea-heading-content' => 'justify-content: {{VALUE}}',
                ]
                
            ]
        );
        $this->start_controls_tabs('data_table_header_title_clrbg');

        $this->start_controls_tab( 
            'data_table_header_title_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons') 
            ] 
        );
        $this->add_control(
            'data_table_header_title_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .ea-table-wrapper table thead tr th' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'data_table_header_title_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'enteraddons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4a4893',
                'selectors' => [
                    '{{WRAPPER}} .ea-table-wrapper table thead' => 'background-color: {{VALUE}};'
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab( 
            'data_table_header_title_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons') 
            ]
        );
        $this->add_control(
            'data_table_header_title_hover_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .ea-table-wrapper table thead tr th:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'data_table_header_title_hover_cell_bg_color',
            [
                'label' => esc_html__( 'Cell Background Color', 'enteraddons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-table-wrapper table thead tr th:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Data Table Heading  Icon Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'data_table_header_icon_settings', [
                'label' => esc_html__( 'Header Icon Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs( 'tab_infobox_icon' );
        //  Controls tab For Normal
        $this->start_controls_tab(
            'data_table_header_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'data_table_header_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-heading-content i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'data_table_header_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-heading-content i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'data_table_header_icon_width',
            [
                'label' => esc_html__( 'Icon Container Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-heading-content i' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'data_table_header_icon_height',
            [
                'label' => esc_html__( 'Icon Container Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-heading-content i' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'data_table_header_icon_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-heading-content i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'data_table_header_icon_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-heading-content i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'data_table_header_icon_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-heading-content i',
            ]
        );
        $this->add_responsive_control(
            'data_table_header_icon_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-heading-content i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'data_table_header_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-heading-content i',
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'data_table_header_icon_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'data_table_header_icon_hover_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-heading-content:hover i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'data_table_header_hover_icon__background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-heading-content:hover i',
            ]
        );
        $this->end_controls_tab(); // End Controls tab
        $this->end_controls_tabs(); //  end controls tabs section
        $this->end_controls_section();

         /**
         * Style Tab
         * ----------------------------- Table Heading Icon Image Style Settings ------------------------------
         *
         */

         $this->start_controls_section(
            'table_header_img_icon_settings', [
                'label' => esc_html__( 'Heading Image Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'table_header_img_width',
            [
                'label' => esc_html__( 'Image Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-heading-content img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'table_header_img_height',
            [
                'label' => esc_html__( 'Image Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-heading-content img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        ); 
        $this->add_responsive_control(
            'table_header_img_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-heading-content img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'table_header_img_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-heading-content img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'table_header_img_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-heading-content img',
            ]
        );
        $this->add_responsive_control(
            'table_header_img_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-heading-content img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        // ---------------------------------------- Data Table Content Style ------------------------------
        $this->start_controls_section(
            'section_data_table_content_style_settings',
            [
                'label' => esc_html__( 'Table Content Style', 'enteraddons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                 'name' => 'ea_data_table_content_typography',
                'selector' => '{{WRAPPER}} .ea-table-wrapper table tbody td',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'ea_data_table_cell_border',
                    'label' => esc_html__( 'Border', 'enteraddons'),
                    'selector' => '{{WRAPPER}} .ea-table-wrapper table tbody td',
                ]
        );
        $this->add_responsive_control(
            'ea_data_table_each_cell_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                         '{{WRAPPER}} .ea-table-wrapper table tbody td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
            ]
        );
        $this->add_responsive_control(
            'content_icon_position',
            [
                'label' => esc_html__( 'Icon Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'row' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'column' => [
                        'title' => esc_html__( 'Top', 'enteraddons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'column-reverse' => [
                        'title' => esc_html__( 'Bottom', 'enteraddons' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'row',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ea-td-content' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'ea_data_table_content_alignment',
            [
                'label' => esc_html__( 'Content Alignment', 'enteraddons'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'enteraddons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'enteraddons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ea-td-content' => 'justify-content: {{VALUE}}',
                ]
                
            ]
        );
        $this->start_controls_tabs(
            'style_tabs'
        );

        $this->start_controls_tab(
            'style_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'content_text_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-table-wrapper table tbody td' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'odd_main_bg_color_heading',
            [
                'label' => esc_html__( 'Odd Row Background', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'odd_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-table-wrapper table tbody tr',
            ]
        ); 
        $this->add_control(
            'even_main_bg_color_heading',
            [
                'label' => esc_html__( 'Even Row Background', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'even_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-table-wrapper table tbody tr:nth-child(even)',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'even_text_hover_color',
            [
                'label' => esc_html__( 'Hover Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-table-wrapper table tbody td:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'even_bg_hover_color_heading',
            [
                'label' => esc_html__( 'Row Hover Background', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'even_bg_hover_color',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-table-wrapper table tbody tr:hover',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Data Table Content Icon Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'table_content_icon_settings', [
                'label' => esc_html__( 'Content Icon Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs( 'tab_table_content_icon' );
        //  Controls tab For Normal
        $this->start_controls_tab(
            'table_content_header_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'table_content_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-td-content i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'table_content_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-td-content i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'table_content_icon_width',
            [
                'label' => esc_html__( 'Icon Container Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-td-content i' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'table_content_icon_height',
            [
                'label' => esc_html__( 'Icon Container Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-td-content i' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'table_content_icon_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-td-content i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'table_content_icon_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-td-content i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'table_content_icon_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-td-content i',
            ]
        );
        $this->add_responsive_control(
            'ea-td-contenticon_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-td-content i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'table_content_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-td-content i',
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'table_content_icon_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'table_content_icon_hover_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-td-content:hover i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'table_content_hover_icon__background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-td-content:hover i',
            ]
        );
        $this->end_controls_tab(); // End Controls tab
        $this->end_controls_tabs(); //  end controls tabs section
        $this->end_controls_section();

        /**
         * Style Tab
         * ----------------------------- Table Content Icon Image Style Settings ------------------------------
         *
         */

         $this->start_controls_section(
            'table_content_img_icon_settings', [
                'label' => esc_html__( 'Content Image Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'img_icon_width',
            [
                'label' => esc_html__( 'Image Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-td-content img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_icon_height',
            [
                'label' => esc_html__( 'Image Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-td-content img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        ); 
        $this->add_responsive_control(
            'img_icon_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-td-content img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_icon_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-td-content img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'img_icon_wrapper_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-td-content img',
            ]
        );
        $this->add_responsive_control(
            'img_icon_wrapper_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-td-content img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        // ---------------------------------------- Data Table Button Style ------------------------------

        $this->start_controls_section(
            'table_cell_button_style',
            [
                'label' => esc_html__( 'Button Style', 'enteraddons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'table_cell_button_title_typography',
                'selector' => '{{WRAPPER}} .ea-table-wrapper table a.btn-custom-reverse',
            ]
        );
        $this->add_control(
            'table_cell_button_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-table-wrapper table a.btn-custom-reverse' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'table_cell_button_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-table-wrapper table a.btn-custom-reverse',
            ]
        );
        $this->add_responsive_control(
            'table_cell_button_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                        'step' => 1,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-table-wrapper table a.btn-custom-reverse' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'table_cell_button_hr',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        $this->start_controls_tabs(
            'table_cell_button_tabs',
        );
        $this->start_controls_tab(
            'table_cell_button_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'table_cell_button_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-table-wrapper table a.btn-custom-reverse' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'table_cell_button_bg',
            [
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-table-wrapper table a.btn-custom-reverse' => 'background: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'table_cell_button_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'table_cell_button_hover_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-table-wrapper table a.btn-custom-reverse:hover' => 'color: {{VALUE}}',
                ],
            ]
        );	
        $this->add_control(
            'table_cell_button_hover_bg',
            [
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-table-wrapper table a.btn-custom-reverse:hover' => 'background: {{VALUE}}',
                ],
            ]
        );	
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        // Tema template render
        $obj = new Data_Table_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();

    }
    
    public function get_style_depends() {
        return [ 'enteraddons-global-style'];
    }

}
