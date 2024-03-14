<?php
/**
 * Tabs
 * 
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
namespace Definitive_Addons_Elementor\Elements;

if (! defined('ABSPATH') ) { 
    exit;
}

use \Elementor\Control_Media;
use \Elementor\Icons_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use \Elementor\Widget_Base;

/**
 * Tabs
 * 
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Tabs extends Widget_Base
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
        return __('DA: Tabs', 'definitive-addons-for-elementor');
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
        return 'dafe_tabs';
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
        return 'eicon-tabs';
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
        return [ 'tab', 'faq', 'definitive','addons' ];
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
            'dafe_tabs_content',
            [
                'label' => __('Tab Content', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();
       
        $repeater->add_control(
            'title',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => __('Title', 'definitive-addons-for-elementor'),
                'default' => __('I am Tab Title', 'definitive-addons-for-elementor')
            ]
        );
        
        $repeater->add_control(
            'dafe_show_hide_btn',
            [
                'label'        =>__('Show/Hide Button.', 'definitive-addons-for-elementor'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'       => __('On', 'definitive-addons-for-elementor'),
            'label_off'    => __('Off', 'definitive-addons-for-elementor'),
                'default'      => 'no',
                'return_value' => 'yes',
                'separator'       => 'before'
            ]
        );
        $repeater->add_control(
            'dafe_tab_btn_text',
            [   
            'label'       => __('Button Text', 'definitive-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'placeholder' =>__('View Details', 'definitive-addons-for-elementor'),
            'default'     =>__('View Details', 'definitive-addons-for-elementor'),
            'condition'   => [
                    'dafe_show_hide_btn' => 'yes'
                ],
                
            ]
        );
        $repeater->add_control(
            'dafe_tab_btn_url',
            [   
                'label'         =>__('Button Link', 'definitive-addons-for-elementor'),
                'type'          => Controls_Manager::URL,
                'default'       => [
                    'url'           => '#',
                    'is_external'   => ''
                ],
                'show_external'     => true,
                'placeholder'       => __('http://softfirm.com', 'definitive-addons-for-elementor'),
                'condition'     => [
                    'dafe_show_hide_btn' => 'yes'
                ]
            ]
        );
        

        $repeater->add_control(
            'dafe_tab_content_area', [
            'type'    => Controls_Manager::WYSIWYG,
            'label'   =>__('Tabs Content', 'definitive-addons-for-elementor'),
                
            'default' =>__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur consectetur nunc mi, ac vestibulum mauris vehicula eu. In eget volutpat metus. Fusce egestas, massa ut posuere rhoncus, sem ipsum laoreet tellus, at scelerisque nisl purus ac leo. Vivamus est lectus, volutpat vitae odio non, porta interdum lorem. Maecenas ex augue, aliquam id placerat eu, sollicitudin ac eros. In vel augue pharetra, accumsan ipsum vitae, suscipit quam. Nunc porta vestibulum eleifend.', 'definitive-addons-for-elementor')
            ]
        );

 
        $this->add_control(
            'dafe_tab_item_repeater',
            [
            'type'         => Controls_Manager::REPEATER,
            'fields'     => $repeater->get_controls(),
            'default'    => [
            [ 
            'title'          =>__('Tab Title#1', 'definitive-addons-for-elementor')
                        
            ],
            [ 'title' =>__('Tab Title#2', 'definitive-addons-for-elementor') ],
            [ 'title' =>__('Tab Title#3', 'definitive-addons-for-elementor') ]
            ],
            'title_field' => '{{title}}'
            ]
        );
    
        
        $this->add_control(
            'dafe_tab_title_icon_show_hide', [
            'label'        =>__('Show/Hide Title Icon', 'definitive-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'       => __('On', 'definitive-addons-for-elementor'),
            'label_off'    => __('Off', 'definitive-addons-for-elementor'),
            'default'      => 'no',
            'return_value' => 'yes'
            ]
        );
        
        $this->add_control(
            'dafe_tab_title_icon',
            [
            'label'       => __('Title Icon', 'definitive-addons-for-elementor'),
            'type'        => Controls_Manager::ICONS,
            'label_block' => true,
            'default'     => [
            'value'   => 'fas fa-user-plus',
            'library' => 'fa-solid'
            ],
            'condition'   => [
                    'dafe_tab_title_icon_show_hide' => 'yes'
            ]
            ]
        );
        

        $this->end_controls_section();

        // style
    
        
        
        
        
        $this->start_controls_section(
            'dafe_tab_title_style',
            [
                'label' => __('Tab Title', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
 
        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-title h4' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'title_bg_color',
            [
                'label' =>__('Title Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#eeeeee',
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-title' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'title_hvr_color',
            [
                'label' => __('Title Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-title h4:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'dafe_title_font',
                'selector' => '{{WRAPPER}} .dafe-tabs-title h4',
                
            ]
        );
        
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => __('Title Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
            'default'    => [
            'top'    => '10',
            'right'  => '10',
            'bottom' => '10',
            'left'   => '10',
            'isLinked' => false,
                ],

                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'selector' => '{{WRAPPER}} .dafe-tabs-title',
                
            ]
        );

        
        $this->end_controls_section();
        
        
        $this->start_controls_section(
            'title_icon_style',
            [
                'label' => __('Icon Before Title', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'title_icon_size',
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
                'size' => 18
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-title-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        
        
        $this->add_control(
            'icon_title_color',
            [
                'label' => __('Icon Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#6EC1E4',
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-title-icon i' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'icon_title_hover_color',
            [
                'label' => __('Icon Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-title-icon i:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        

        $this->end_controls_section();
        
        $this->start_controls_section(
            'active_active_title_style',
            [
                'label' => __('Active Title', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->start_controls_tabs(
            'dafe_active_title_colors',
            [
            'label' => __('Active Title Colors', 'definitive-addons-for-elementor'),
            ]
        );

        $this->start_controls_tab(
            'dafe_active_title_normal_color_tab',
            [
            'label' => __('Normal', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'active_title_color',
            [
                'label' => __('Active Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#6EC1E4',
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-title.active h4' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        
        $this->add_control(
            'active_title_bg_color',
            [
                'label' => __('Active Title Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#eeefff',
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-title.active' => 'background-color: {{VALUE}}',
                ],
            ]
        );
    
        
        $this->add_control(
            'active_icon_color',
            [
                'label' => __('Active Icon Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#6EC1E4',
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-title.active .dafe-tabs-title-icon i' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'active_header_border',
                'selector' => '{{WRAPPER}} .dafe-tabs-title.active',
            'fields_options'     => [
                        'border'          => [
                            'default'    => 'solid'
                        ],
                        'width'           => [
                            'default'    => [
                                'top'    => '1',
                                'right'  => '1',
                                'bottom' => '0',
                                'left'   => '1'
                            ]
                        ],
                        'color'          => [
                            'default'    => '#6EC1E4'
                        ]
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_active_title_hover_tab',
            [
            'label' => __('Hover', 'definitive-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'active_title_hvr_color',
            [
                'label' => __('Active Title Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#6EC1c4',
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-title.active:hover h4' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'active_title_bg_hvr_color',
            [
                'label' => __('Active Title Hover Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#eeefff',
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-title.active:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'active_icon_hvr_color',
            [
                'label' => __('Acitve Icon Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-title.active .dafe-tabs-title-icon i:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        $this->end_controls_tabs();
        

        $this->end_controls_section();

        $this->start_controls_section(
            'tab_style_content',
            [
                'label' => __('Tabs Content', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('Content Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
            'default'    => [
            'top'    => '10',
            'right'  => '15',
            'bottom' => '10',
            'left'   => '15'
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'content_margin',
            [
                'label' => __('Content Margin', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'content_text_color',
            [
                'label' => __('Content Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-content' => 'color: {{VALUE}}',
                ],
                'default'    =>'#000000',
            ]
        );

        
        $this->add_control(
            'content_bg_color',
            [
                'label' => __('Content Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-content' => 'background-color: {{VALUE}}',
                ],
                'default'    =>'#ffffff',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
            'name'     => 'tab_content_border',
            'selector' => '{{WRAPPER}} .dafe-tabs-content',
            'fields_options'     => [
                        'border'          => [
                            'default'    => 'solid'
                        ],
                        'width'           => [
                            'default'    => [
                                'top'    => '0',
                                'right'  => '1',
                                'bottom' => '1',
                                'left'   => '1'
                            ]
                        ],
                        'color'          => [
                            'default'    => '#6EC1E4'
                        ]
                ],
            ]
        );
        
        $this->add_responsive_control(
            'tab_content_border_radius',
            [
            'label'      => __('Border Radius', 'definitive-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
                
                'selectors'  => [
                    '{{WRAPPER}} .dafe-tabs-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'tab_button_style',
            [
                'label' => __('Tabs Button', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        

        $this->add_control(
            'btn_color',
            [
                'label' => __('Button Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default'  => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-content a.tabs-btn' => 'color: {{VALUE}}!important',
                ],
            ]
        );
        
        $this->add_control(
            'btn_bg_color',
            [
                'label' => __('Button Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default'  => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-content a.tabs-btn' => 'background-color: {{VALUE}}!important',
                ],
            ]
        );
        
        $this->add_control(
            'btn_hover_color',
            [
                'label' => __('Button Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default'  => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-content a.tabs-btn:hover' => 'color: {{VALUE}}!important',
                ],
            ]
        );
        
        $this->add_control(
            'btn_hover_bg_color',
            [
                'label' => __('Button Hover Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default'  => '#1D26E6',
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-content a.tabs-btn:hover' => 'background-color: {{VALUE}}!important',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_font',
                'selector' => '{{WRAPPER}} .dafe-tabs-content a.tabs-btn',
                
            ]
        );
        
        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => __('Button Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
            'default'    => [
            'top'    => '10',
            'right'  => '15',
            'bottom' => '10',
            'left'   => '15',
            'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-content a.tabs-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'btn_margin',
            [
                'label' => __('Button Margin', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-content a.tabs-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'selector' => '{{WRAPPER}} .dafe-tabs-content a.tabs-btn',
                
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label' => __('Button Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
            'default'    => [
            'top'    => '5',
            'right'  => '5',
            'bottom' => '5',
            'left'   => '5',
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-content a.tabs-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );


        $this->end_controls_section();
        
        $this->start_controls_section(
            'dafe_tab_container_style',
            [
                'label' => __('Tabs Container', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        $this->add_responsive_control(
            'tab_container_padding',
            [
                'label' => __('Container Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
            'default'    => [
            'top'    => '10',
            'right'  => '10',
            'bottom' => '10',
            'left'   => '10',
            'isLinked' => false,
                ],

                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'name' => 'tab_container_background',
                'selector' => '{{WRAPPER}} .dafe-tabs-container',
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Container Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'container_box_shadow',

            'selector' => '{{WRAPPER}} .dafe-tabs-container',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tab_container_border',
                'selector' => '{{WRAPPER}} .dafe-tabs-container',
                
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_container_hover_tab',
            [
            'label' =>__('Hover', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tab_container_hvr_background',
                'selector' => '{{WRAPPER}} .dafe-tabs-container:hover',
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'container_box_hvr_shadow',

            'selector' => '{{WRAPPER}} .dafe-tabs-container:hover',
            ]
        );
        
        $this->add_control(
            'container_border_hvr_color',
            [
            'label'     => __('Border Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-container:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'tab_container_border_radius',
            [
                'label' => __('Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-tabs-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        
        
        $this->end_controls_section();
        
    
    }





	protected function render() {
        
		
		$settings = $this->get_settings_for_display();
		
	
        ?>

        <div class="dafe-tabs-container">
		
	
            <ul class="dafe-tabs-header">
		<?php
		
		foreach ( $settings['dafe_tab_item_repeater'] as $tab_item ) : ?>
					<?php if ( $tab_item['title'] ) : ?>
					<li  class="dafe-tabs-title" data-tab-id="da-tab-<?php echo esc_attr($this->get_id()) . esc_attr($tab_item['_id']); ?>">
					
						<span class="dafe-tabs-title-icon">
                        <?php Icons_Manager::render_icon( $settings['dafe_tab_title_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </span>      
						<h4><?php echo wp_kses_post( $tab_item['title'] ); ?></h4>
					</a>
					
				<?php endif; ?>
				
		<?php endforeach; ?>		
			 </ul>	
		
		
		<div class="dafe-tabs-content-container">
		<?php
		foreach ( $settings['dafe_tab_item_repeater'] as $key => $tab_item ) : ?>
		
                
				<?php if ( $tab_item['dafe_tab_content_area'] ) : ?>
					<div class="dafe-tabs-content" id="da-tab-<?php echo esc_attr($this->get_id()) . esc_attr($tab_item['_id']); ?>"> 
						<?php echo wp_kses_post( $tab_item['dafe_tab_content_area'] ); ?>
				
				
				<?php if ( $tab_item['dafe_show_hide_btn'] ) : ?>
					<?php if ( $tab_item['dafe_tab_btn_text'] ) : ?>
				
				<?php	if ( ! empty( $tab_item['dafe_tab_btn_url']['url'] ) ) {
						$this->add_link_attributes( 'tab_btn_link'.$key, $tab_item['dafe_tab_btn_url'] );
					}  ?>
             
						<div class="tabs-btn-container">
							<a <?php $this->print_render_attribute_string( 'tab_btn_link'.$key ); ?> class="tabs-btn link">
						
								<?php echo esc_html($tab_item['dafe_tab_btn_text']); ?>
										
							</a>
						</div>
					<?php endif; ?>
				<?php endif; ?>
					</div>
				<?php endif; ?>
		        

           <?php endforeach; ?>

        </div>
	</div>
        <?php 
}
}