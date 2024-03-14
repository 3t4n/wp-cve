<?php
/**
 * Call to Action
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
namespace Definitive_Addons_Elementor\Elements;
use Elementor\Group_Control_Background;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
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
 * Call to Action
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class CTA extends Widget_Base
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
        return __('DA: Call to Action', 'definitive-addons-for-elementor');
    }
    
    /**
     * Get widget name.
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'dafe_cta';
    }

    /**
     * Get widget icon.
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-call-to-action';
    }
    
    /**
     * Get widget keywords.
     *
     * @access public
     *
     * @return string Widget keywords.
     */
    public function get_keywords()
    {
        return [ 'cta', 'icon', 'call', 'action' ];
    }
    
    /**
     * Get widget categories.
     *
     * @access public
     *
     * @return string Widget categories.
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
            'dafe_section_cta',
            [
                'label' => __('Call to Action', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

       

        $this->add_control(
            'icon',
            [
            'label'   =>__('Icon', 'definitive-addons-for-elementor'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
            'value' => 'fa fa-cog',
            'library' => 'fa-solid',
            ]
                
            ]
        );
        
        $this->add_control(
            'icon_design',
            [
                'label' => __('Icon Design', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
               
                'options' => [
            'normal' => __('Normal', 'definitive-addons-for-elementor'),
            'circle' =>  __('Circle', 'definitive-addons-for-elementor'),
            'square' =>  __('Square', 'definitive-addons-for-elementor'),
            'rounded' => __('Rounded', 'definitive-addons-for-elementor'),
                    
                ],
                'default' => 'circle',
                'toggle' => false,
            ]
        );
        
        

        $this->add_control(
            'title',
            [
            'label' =>__('Call to Action Title', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' =>__('Definitive Addons for Elementor.', 'definitive-addons-for-elementor'),
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
                'default' => 'h1',
                'toggle' => false,
            ]
        );


      
        $this->add_control(
            'subtitle',
            [
            'label' =>__('Call to Action Description', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::TEXTAREA,
            'default' =>__('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'btn_txt',
            [
            'label' =>__('Button Text', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' =>__('Button Text', 'definitive-addons-for-elementor'),
            ]
        );
        $this->add_control(
            'link',
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
            'cta_section_style_entry',
            [
                'label' => __('Call to Action Style', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'cta_align',
            [
            'label' =>__('CTA Style', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => true,
            'options' => [
                    
            'left' => [
            'title' =>__('Button Left - Icon Right', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-left',
            ],
                    
            'right' => [
            'title' =>__('Button Right - Icon Left', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-right',
            ],
            ],
            'default' => 'right',
                
            ]
        );
    
        $this->end_controls_section();
         
        $this->start_controls_section(
            'cta_section_style_icon',
            [
                'label' => __('Icon Style', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                    ],
                ],
                'default' => [
                'size' => 50
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => __('Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                'size' => 26
                ],
                'condition' => [
                'icon_design!' => 'normal',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->start_controls_tabs(
            'dafe_icon_colors',
            [
            'label' => __('Icon Colors', 'definitive-addons-for-elementor'),
            ]
        );

        $this->start_controls_tab(
            'dafe_icon_normal_color_tab',
            [
            'label' =>__('Normal', 'definitive-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon i' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'icon_bg_color',
            [
                'label' => __('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                'icon_design!' => 'normal',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Icon Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'icon_box_shadow',
            'condition' => [
            'icon_design!' => 'normal',
            ],

            'selector' => '{{WRAPPER}} .dafe-icon-container .dafe-icon',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
            'condition' => [
            'icon_design!' => 'normal',
                ],
                'selector' => '{{WRAPPER}} .dafe-icon-container .dafe-icon',
            ]
        );

        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_icon_hover_tab',
            [
            'label' => __('Hover', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'icon_hover_color',
            [
                'label' => __('Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon:hover i' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'icon_hover_bg_color',
            [
                'label' => __('Hover Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'condition' => [
            'icon_design!' => 'normal',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'icon_hvr_box_shadow',
            'condition' => [
            'icon_design!' => 'normal',
            ],

            'selector' => '{{WRAPPER}} .dafe-icon-container .dafe-icon:hover',
            ]
        );
        
        $this->add_control(
            'icon_border_hvr_color',
            [
            'label'     => __('Border Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'condition' => [
            'icon_design!' => 'normal',
            ],
            'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        
        $this->end_controls_tab();
        $this->end_controls_tabs();
        
/*
        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => __('Icon Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
            'condition' => [
            'icon_design!' => 'normal',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        */
        
        $this->add_responsive_control(
            'icon_text_spacing',
            [
                'label' => __('Space between Icon & Text', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .call-to-action.right .dafe-icon-container' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .call-to-action.left .dafe-icon-container' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'default' => [
                'size' => 50
                ]
            ]
        );
        
        

        $this->end_controls_section();

        // title style

        $this->start_controls_section(
            'cta_section_style_title',
            [
                'label' => __('CTA Title', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __('Title Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .cta-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cta-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'title_hvr_color',
            [
                'label' => __('Title Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cta-title:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        
         

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_font',
                'selector' => '{{WRAPPER}} .cta-title',
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
            'name' => 'title_shadow',
            'selector' => '{{WRAPPER}} .cta-title',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
            'name' => 'title_stroke',
            'selector' => '{{WRAPPER}} .cta-title',
            ]
        );
        
        $this->end_controls_section();

        $this->start_controls_section(
            'cta_section_style_subtitle',
            [
                'label' => __('CTA Description', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Description Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#54595F',
                'selectors' => [
                    '{{WRAPPER}} .cta-sub-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_font',
                'selector' => '{{WRAPPER}} .cta-sub-title',
                
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
                'label' => __('Space between Button & Text', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .call-to-action.right .cta-icon-button' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .call-to-action.left .cta-icon-button' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'default' => [
                'size' => 50
                ]
            ]
        );
        
        $this->add_responsive_control(
            'button_width',
            [
                'label' => __('Button Width', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'default' => [
                'size' => 150
                ],
                'selectors' => [
                    '{{WRAPPER}} .dactabtn' => 'width: {{SIZE}}{{UNIT}};',
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
                'label' => __('Text Color', 'definitive-addons-for-elementor'),
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
            'default' =>'#000',
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
                'label' => __('Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dactabtn:hover,{{WRAPPER}} .dactabtn:hover .icon-btn' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'button_bg_hvr_color',
            [
                'label' => __('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' =>'#000',
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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_font',
                'selector' => '{{WRAPPER}} .dactabtn',
                
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
            'cta_section_style_content',
            [
                'label' => __('CTA Container', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('CTA Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
        
                'selectors' => [
                    '{{WRAPPER}} .call-to-action' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        
        $this->add_control(
            'content_bg_color',
            [
                'label' => __('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .call-to-action' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Container Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'container_box_shadow',

            'selector' => '{{WRAPPER}} .call-to-action',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .call-to-action',
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
            'content_bg_hvr_color',
            [
                'label' => __('Hover Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .call-to-action:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
    
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'container_hvr_shadow',

            'selector' => '{{WRAPPER}} .call-to-action:hover',
            ]
        );
        $this->add_control(
            'container_border_hvr_color',
            [
            'label'     => __('Border Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .call-to-action:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        
        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .call-to-action' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        
        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings_for_display();
	
		$icon_height = $this->get_settings_for_display( 'icon_height' );
		$title_tag = $this->get_settings_for_display( 'title_tag' );
		
		$cta_align = $this->get_settings_for_display( 'cta_align' );
		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'cta_link', $settings['link'] );
		}
		
?>


	<div class="call-to-action <?php echo esc_attr($cta_align); ?>">
	
	
		<?php if ($settings['icon']){ ?>
			<div class="dafe-icon-container">
						<div class="dafe-icon dafe-<?php echo esc_attr($settings['icon_design'])?>">
						<?php Icons_Manager::render_icon($settings['icon'], [ 'aria-hidden' => 'true' ]); ?>
											
						</div>
			</div>
		
	<?php } ?>
	
		
		<div class="icon-wrap-cta">
			
			<div class="cta-txt">
				<?php if ($settings['title']){ ?>
					<<?php echo esc_attr($title_tag); ?> class="cta-title"><?php echo esc_html($settings['title']); ?></<?php echo esc_attr($title_tag); ?>>
				<?php } ?>	
				<?php if ($settings['subtitle']){ ?>
					<p class="cta-sub-title"><?php echo esc_html($settings['subtitle']); ?></p>
				<?php } ?>
			</div>
		</div>
		<?php if ($settings['btn_txt']){ ?>
		<div class="cta-icon-button">
	
					<a <?php $this->print_render_attribute_string( 'cta_link' ); ?> class="btn-default dactabtn">
					<?php echo esc_html($settings['btn_txt']);  ?>
						<span class="<?php echo esc_attr($settings['btn_icon']['value']); ?> icon-btn"></span>
					</a>
	
		</div>
		<?php } ?>	
	
	</div>

        <?php
    }
}
