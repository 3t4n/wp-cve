<?php
/**
 * Pricing Table
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
namespace Definitive_Addons_Elementor\Elements;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use \Elementor\Widget_Base;

defined('ABSPATH') || die();
/**
 * Pricing Table
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Pricing_Table extends Widget_Base
{
    
    /**
     * Get widget title.
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('DA: Pricing Table', 'definitive-addons-for-elementor');
    }

      /**
       * Get element name.
       *
       * @access public
       *
       * @return string element name.
       */ 
    public function get_name()
    {
        return 'dafe_pricing_table';
    }

    /**
     * Get element icon.
     *
     * @access public
     *
     * @return string element icon.
     */
    public function get_icon()
    {
        return 'eicon-price-table';
    }
    
    /**
     * Get element keywords.
     *
     * @access public
     *
     * @return string element keywords.
     */
    public function get_keywords()
    {
        return [ 'icon', 'list'];
    }
    
    /**
     * Get element categories.
     *
     * @access public
     *
     * @return string element categories.
     */
    public function get_categories()
    {
        return [ 'definitive-addons' ];
    }
    
    
    /**
     * Registering widget content controls
     *
     * @return void.
     */
    protected function register_controls()
    {
        
        $this->start_controls_section(
            'section_pricing_table',
            [
                'label' => __('Pricing Table', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'table_title',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' =>__('Pricing Table Title', 'definitive-addons-for-elementor'),
                'default' =>__('Table Title', 'definitive-addons-for-elementor')
            ]
        );
        
        $this->add_control(
            'title_tag',
            [
                'label' => __('Title HTML Tag', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
               
                'options' => [
                    'h1'  => [
                        'title' => __('H1', 'definitive-addons-for-elementor'),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2'  => [
                        'title' => __('H2', 'definitive-addons-for-elementor'),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3'  => [
                        'title' => __('H3', 'definitive-addons-for-elementor'),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4'  => [
                        'title' => __('H4', 'definitive-addons-for-elementor'),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5'  => [
                        'title' => __('H5', 'definitive-addons-for-elementor'),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6'  => [
                        'title' => __('H6', 'definitive-addons-for-elementor'),
                        'icon' => 'eicon-editor-h6'
                    ]
                ],
                'default' => 'h2',
                'toggle' => false,
            ]
        );
        
        $this->add_control(
            'table_price',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
               'label' => __('Offer Price', 'definitive-addons-for-elementor'),
                'default' => __('$29', 'definitive-addons-for-elementor'),
            ]
        );
        $this->add_control(
            'price_separator',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' =>__('Pricing Separator', 'definitive-addons-for-elementor'),
                'default' =>__('/', 'definitive-addons-for-elementor')
            ]
        );
        
        $this->add_control(
            'price_text',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' =>__('Pricing Offer Text', 'definitive-addons-for-elementor'),
                'default' =>__('month', 'definitive-addons-for-elementor')
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'icon',
            [
            'label'   =>__('List Icon', 'definitive-addons-elementor'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
            'value' => 'fas fa-check',
            'library' => 'fa-solid',
            ],

            ]
        );

        $repeater->add_control(
            'list_txt',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' =>__('Pricing List Text', 'definitive-addons-for-elementor'),
                
            ]
        );
        
        
        $this->add_control(
            'pricing_tables',
            [
            'label'       =>__('Pricing Table Item', 'definitive-addons-for-elementor'),
            'type'        => Controls_Manager::REPEATER,
            'seperator'   => 'before',
            'default' => [
                    
            [ 'list_txt' =>__('4 GB SSD Storage', 'definitive-addons-for-elementor') ],
                    
            [ 'list_txt' => __('LiteSpeed Web Server', 'definitive-addons-for-elementor') ],
                    
            [ 'list_txt' =>__('4 Addon Domains', 'definitive-addons-for-elementor') ],
                    
            [ 'list_txt' => __('FREE Weekly Backup', 'definitive-addons-for-elementor') ],
                    
            [ 'list_txt' => __('FREE SSL Life Time', 'definitive-addons-for-elementor') ]
            ],
                
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{list_txt}}',
            
            ]
        );
        
        $this->add_control(
            'pricing_table_alignment',
            [
            'label' =>__('Pricing Table Align', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => true,
            'options' => [
                    
            'left' => [
            'title' =>__('Left', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-left',
            ],
            'center' => [
            'title' =>__('Center', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-center',
            ],
            'right' => [
            'title' =>__('Right', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-right',
            ],
            ],
            'default' => 'left',
                
            ]
        );
        
        
        $this->add_control(
            'icon_position',
            [
            'label' =>__('Icon Position', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => true,
            'options' => [
                    
            'left' => [
            'title' =>__('Left', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-left',
            ],
                    
            'right' => [
            'title' =>__('Right', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-right',
            ],
            ],
            'default' => 'left',
                
            ]
        );
    
        $this->add_control(
            'btn_txt',
            [
            'label' =>__('Button Text', 'elementor-definitive-addons-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' =>__('Button Text', 'elementor-definitive-addons-for-elementor'),
            ]
        );
        $this->add_control(
            'btn_link',
            [
                'label' => __('Button Link', 'definitive-addons-for-elementor'),
                'separator' => 'before',
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://softfirm.net/',
               
            ]
        );
        
        $this->add_control(
            'btn_icon',
            [
            'label'   =>__('Button Icon', 'definitive-addons-for-elementor'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
            'value' => 'fas fa-long-arrow-alt-right',
            'library' => 'fa-solid',
            ]
                
            ]
        );
        

        $this->end_controls_section();

       

        // style
    
        $this->start_controls_section(
            'section_style_header',
            [
                'label' =>__('Table Header', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'table_header_bg_color',
            [
                'label' => __('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#323844',
                'selectors' => [
                    '{{WRAPPER}} .table-header' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'table_header_bg_hvr_color',
            [
                'label' => __('Hover Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .table-header:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'header_padding',
            [
                'label' => __('Header Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
            'default'    => [
            'top'    => '10',
            'right'  => '10',
            'bottom' => '0',
            'left'   => '10'
                ],
                'selectors' => [
                    '{{WRAPPER}} .table-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'header_bottom_spacing',
            [
                'label' => __('Header Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .table-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                   
                ],
                'default' => [
                'size' => 15
                ]
            ]
        );
        
        
        $this->end_controls_section();
    
    
        $this->start_controls_section(
            'section_style_title',
            [
                'label' =>__('Table Title', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'table_title_color',
            [
                'label' => __('Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .table-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'table_title_separator_color',
            [
                'label' => __('Separator Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#323844',
                'selectors' => [
                    '{{WRAPPER}} .table-title' => 'border-bottom-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'table_title_bg_color',
            [
                'label' => __('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            
                'selectors' => [
                    '{{WRAPPER}} .table-title-container' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_font',
                'selector' => '{{WRAPPER}} .table-title',
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
            'name' => 'title_shadow',
            'selector' => '{{WRAPPER}} .table-title',
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
            'name' => 'title_stroke',
            'selector' => '{{WRAPPER}} .table-title',
            ]
        );

        
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => __('Title Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .table-title-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        
        $this->end_controls_section();
    
        $this->start_controls_section(
            'section_style_pricing',
            [
                'label' =>__('Table Pricing', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'table_pricing_color',
            [
                'label' => __('Price Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#84E46E',
                'selectors' => [
                    '{{WRAPPER}} .table-price' => 'color: {{VALUE}}',
                ],
            ]
        );
    
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_font',
                'selector' => '{{WRAPPER}} .table-price',
                
            ]
        );
        
        
        $this->add_control(
            'table_price_text_color',
            [
                'label' => __('Price Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .price-text,{{WRAPPER}} .price-separator' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_text_font',
                'selector' => '{{WRAPPER}} .price-text',
                
            ]
        );
        
        
        $this->add_control(
            'table_pricing_bg_color',
            [
                'label' =>__('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .table-price' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'price_padding',
            [
                'label' => __('Price Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .table-price-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'price_container_separator_color',
            [
                'label' =>__('Bottom Separator Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#323844',
                'selectors' => [
                    '{{WRAPPER}} .table-price-container' => 'border-bottom-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_style_iist_icon',
            [
                'label' =>__('List Icon', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('Size', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                    ],
                ],
                'default' => [
                'size' => 14
                ],
                'selectors' => [
                    '{{WRAPPER}} .icon-left,{{WRAPPER}} .icon-right' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        
        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#84E46E',
                'selectors' => [
                    '{{WRAPPER}} .icon-left,{{WRAPPER}} .icon-right' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'icon_hover_color',
            [
                'label' => __('Icon Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .icon-left:hover,{{WRAPPER}} .icon-right:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_style_spacing',
            [
                'label' => __('Table List Spacing', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'item_btm_spacing',
            [
                'label' =>__('Table Item Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .list-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                
            ]
        );
        
        
        $this->add_responsive_control(
            'icon_btn_spacing_left',
            [
                'label' => __('Space between Icon & Text', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                        'icon_position' => 'left',
                ],
                
            ]
        );
        $this->add_responsive_control(
            'icon_btn_spacing_right',
            [
                'label' =>__('Space between Icon & Text', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                        'icon_position' => 'right',
                ],
                
            ]
        );
        
        $this->add_responsive_control(
            'list_bottom_spacing',
            [
                'label' => __('Table List Container Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .list-container' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                   
                ],
                'default' => [
                'size' => 15
                ]
            ]
        );
        
        
        $this->end_controls_section();
        
        

        $this->start_controls_section(
            'section_style_list_txt',
            [
                'label' => __('Table List Text', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
      

        $this->add_control(
            'list_text_color',
            [
                'label' => __('Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .list-text' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'list_item_separator_color',
            [
                'label' => __('List Item Separator Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .list-text' => 'border-bottom-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'list_text_hvr_color',
            [
                'label' => __('Text Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .list-text:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'list_txt_font',
                'selector' => '{{WRAPPER}} .list-text',
                
            ]
        );
        
        
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'button_style_start',
            [
                'label' => __('Button', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'button_text_spacing',
            [
                'label' => __('Space between Button Text & Icon', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} span.fas.fa-long-arrow-alt-right.icon-btn' => 'margin-left: {{SIZE}}{{UNIT}};',
                    
                ],
                'default' => [
                'size' => 3
                ]
            ]
        );
        $this->add_responsive_control(
            'button_bottom_spacing',
            [
                'label' => __('Button Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .table-icon-button' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    
                ],
                'default' => [
                'size' => 3
                ]
            ]
        );
        
        $this->add_responsive_control(
            'button_padding',
            [
                'label' =>__('Button Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
            'default'=>['top' =>'7','right' =>'12','bottom' =>'7','left' =>'12'],
                'selectors' => [
                    '{{WRAPPER}} .dactabtn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'dafe_button_colors',
            [
            'label' => __('Button Colors', 'definitive-addons-for-elementor'),
            ]
        );

        $this->start_controls_tab(
            'dafe_button_normal_color_tab',
            [
            'label' =>__('Normal', 'definitive-addons-for-elementor'),
            ]
        );
        $this->add_control(
            'button_color',
            [
                'label' =>__('Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' =>'#fff',
                'selectors' => [
                    '{{WRAPPER}} .dactabtn,{{WRAPPER}} .icon-btn' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' =>'#84E46E',
                'selectors' => [
                    '{{WRAPPER}} .dactabtn' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Button Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'btn_box_shadow',

            'selector' => '{{WRAPPER}} .dactabtn',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .dactabtn',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_button_hover_tab',
            [
            'label' => __('Hover', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'button_hvr_color',
            [
                'label' => __('Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' =>'#eee',
                'selectors' => [
                    '{{WRAPPER}} .dactabtn:hover,{{WRAPPER}} .dactabtn:hover .icon-btn' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'button_bg_hvr_color',
            [
                'label' => __('Hover Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' =>'rgb(18, 190, 41)',
                'selectors' => [
                    '{{WRAPPER}} .dactabtn:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Button Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'btn_hvr_shadow',

            'selector' => '{{WRAPPER}} .dactabtn:hover',
            ]
        );
        
        $this->add_control(
            'button_border_hvr_color',
            [
                'label' => __('Border Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dactabtn:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        
        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->add_responsive_control(
            'btn_icon_size',
            [
                'label' => __('Button Icon Size', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                    ],
                ],
                'default' => [
                'size' => 14
                ],
                'selectors' => [
                    '{{WRAPPER}} .icon-btn' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' =>__('Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dactabtn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_font',
                'selector' => '{{WRAPPER}} .dactabtn',
               
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_style_content',
            [
                'label' =>__('Pricing Table Content', 'definitive-addons-for-elementor'),
                'tab'  => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'content_padding',
            [
                'label' =>__('Container Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
            'default'=>['top' =>'0','right' =>'0','bottom' =>'10','left' =>'0'],
                'selectors' => [
                    '{{WRAPPER}} .pricing-table-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->start_controls_tabs(
            'dafe_container_colors',
            [
            'label' => __('Container Colors', 'definitive-addons-for-elementor'),
            ]
        );

        $this->start_controls_tab(
            'dafe_container_normal_color_tab',
            [
            'label' => __('Normal', 'definitive-addons-for-elementor'),
            ]
        );

        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_background',
                'selector' => '{{WRAPPER}} .pricing-table-container',
                'exclude' => [
                    'image'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Container Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'container_shadow',

            'selector' => '{{WRAPPER}} .pricing-table-container',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'price_table_border',
                'selector' => '{{WRAPPER}} .pricing-table-container',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_container_hover_tab',
            [
            'label' =>__('Hover', 'definitive-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'container_bg_hvr_color',
            [
                'label' => __('Hover Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .pricing-table-container:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'container_hvr_shadow',

            'selector' => '{{WRAPPER}} .pricing-table-container:hover',
            ]
        );
        
        $this->add_control(
            'container_border_hvr_color',
            [
            'label'     => __('Border Hover Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .pricing-table-container:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->add_responsive_control(
            'price_table_border_radius',
            [
                'label' =>__('Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .pricing-table-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        
        $this->end_controls_section();

        
    }

	
	
	protected function render( ) {
        
		$settings = $this->get_settings_for_display();
		$pricing_tables = $this->get_settings_for_display('pricing_tables');
	
		$title_tag = $this->get_settings_for_display('title_tag');
		$pricing_table_alignment = $this->get_settings_for_display('pricing_table_alignment');
		$icon_position = $this->get_settings_for_display('icon_position');
		
		$layout = '';
		$add_icon_left = '';
		$add_icon_right = '';

        ?>
<div class="pricing-table-container <?php echo esc_attr($pricing_table_alignment) ?>">
	<div class="table-header">
	<div class="table-header-inner">
		<div class="table-title-container">
		
		<?php if ($settings['table_title']){ ?>
			<<?php echo esc_attr($title_tag); ?> class="table-title"><?php echo esc_html($settings['table_title']); ?></<?php echo esc_attr($title_tag); ?>>
		<?php } ?>	
       
		</div>
		<div class="table-price-container">
		
			<span class="table-price"><?php echo esc_html($settings['table_price']); ?></span>
			<span class="price-separator"><?php echo esc_html($settings['price_separator']); ?></span>
			<span class="price-text"><?php echo esc_html($settings['price_text']); ?></span>
		
		</div>
		</div>
	</div>
	<div class="list-container">
      <?php
	  foreach ( $settings['pricing_tables'] as $pricing_table) :

		if ($icon_position == 'left'){
			$add_icon_left = $pricing_table['icon']['value'];
		}else {
			$add_icon_right = $pricing_table['icon']['value'];
		} 
		
	?>
			<div class="list-text">
				<span class="<?php echo esc_attr($add_icon_left); ?> icon-left"></span>
				<?php echo esc_html($pricing_table['list_txt']); ?>
				<span class="<?php echo esc_attr($add_icon_right); ?> icon-right"></span>
			</div>  
	
        <?php endforeach; ?>
	</div>
	<?php
	
	if ( ! empty( $settings['btn_link']['url'] ) ) {
			$this->add_link_attributes( 'pricing_link', $settings['btn_link'] );
		}
	?>
	
	<?php if ( ! empty( $settings['btn_txt'] ) ) : ?>
	<div class="table-icon-button">
	
					<a <?php $this->print_render_attribute_string( 'pricing_link' ); ?> class="btn-default dactabtn">
					<?php echo esc_html($settings['btn_txt']);  ?>
						<span class="<?php echo esc_attr($settings['btn_icon']['value']); ?> icon-btn"></span>
					</a>
		
	</div>
	<?php endif ?>
	
</div>	

        <?php
    }
	
	
	protected function content_template() {
		
	}
}
