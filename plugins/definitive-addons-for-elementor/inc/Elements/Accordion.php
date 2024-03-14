<?php
/**
 * Accordion
 * 
 * @category Definitive,accordion,element,widget,faq
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

/**
 * Accordion
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Accordion extends Widget_Base
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
        return __('DA: Accordion', 'definitive-addons-for-elementor');
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
        return 'dafe_accordion';
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
        return 'eicon-expand';
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
        return [ 'accordion', 'faq', 'definitive','addons' ];
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
     * Register widget content controls
     *
     * @return void.
     */
    protected function register_controls()
    {
        
        $this->start_controls_section(
            'dafe_accordion_content',
            [
                'label' => __('Accordion Content', 'definitive-addons-for-elementor'),
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
                'default' => __('I am Accordion Title', 'definitive-addons-for-elementor')
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
            'dafe_accordion_btn_text',
            [   
            'label'       =>__('Button Text', 'definitive-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'placeholder' =>__('View Details', 'definitive-addons-for-elementor'),
            'default'     =>__('View Details', 'definitive-addons-for-elementor'),
            'condition'   => [
                    'dafe_show_hide_btn' => 'yes'
                ],
                
            ]
        );
        $repeater->add_control(
            'btn_url',
            [   
                'label'         =>__('Button Link', 'definitive-addons-for-elementor'),
                'type'          => Controls_Manager::URL,
                'default'       => [
                    'url'           => '#',
                    'is_external'   => ''
                ],
                'show_external'     => true,
                'placeholder'       =>__('http://softfirm.com', 'definitive-addons-for-elementor'),
                'condition'     => [
                    'dafe_show_hide_btn' => 'yes'
                ]
            ]
        );
        

        $repeater->add_control(
            'dafe_accordion_content_area', [
            'type'    => Controls_Manager::WYSIWYG,
            'label'   =>__('Accordion Content', 'definitive-addons-for-elementor'),
                
            'default' =>__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur consectetur nunc mi, ac vestibulum mauris vehicula eu. In eget volutpat metus. Fusce egestas, massa ut posuere rhoncus, sem ipsum laoreet tellus, at scelerisque nisl purus ac leo. Vivamus est lectus, volutpat vitae odio non, porta interdum lorem. Maecenas ex augue, aliquam id placerat eu, sollicitudin ac eros. In vel augue pharetra, accumsan ipsum vitae, suscipit quam. Nunc porta vestibulum eleifend.', 'definitive-addons-for-elementor')
            ]
        );

 
        $this->add_control(
            'dafe_accordion_item_repeater',
            [
            'type'         => Controls_Manager::REPEATER,
            'fields'     => $repeater->get_controls(),
            'default'    => [
            [ 
            'title' =>__('Accordion Title-1', 'definitive-addons-for-elementor')
                        
            ],
            [ 
            'title' =>__('Accordion Title-2', 'definitive-addons-for-elementor') 
            ],
            [ 
            'title' =>__('Accordion Title-3', 'definitive-addons-for-elementor') 
            ]
            ],
            'title_field' => '{{title}}'
            ]
        );
    
        
        $this->add_control(
            'dafe_accordion_title_icon_show_hide', [
            'label'        =>__('Show/Hide Title Icon', 'definitive-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'       => __('On', 'definitive-addons-for-elementor'),
            'label_off'    => __('Off', 'definitive-addons-for-elementor'),
            'default'      => 'yes',
            'return_value' => 'yes'
            ]
        );
        
        $this->add_control(
            'dafe_accordion_title_icon',
            [
            'label'       => __('Title Icon', 'definitive-addons-for-elementor'),
            'type'        => Controls_Manager::ICONS,
            'label_block' => true,
            'default'     => [
            'value'   => 'fas fa-user-plus',
            'library' => 'fa-solid'
            ],
            'condition'   => [
                    'dafe_accordion_title_icon_show_hide' => 'yes'
            ]
            ]
        );
        
        $this->add_control(
            'dafe_accordion_item_icon_show_hide', [
            'label'        =>__('Show/Hide Item Icon', 'definitive-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'       => __('On', 'definitive-addons-for-elementor'),
            'label_off'    => __('Off', 'definitive-addons-for-elementor'),
            'default'      => 'yes',
            'return_value' => 'yes'
            ]
        );
        
        
        $this->add_control(
            'dafe_accordion_item_icon_active',
            [
            'label'   =>__('Active Icon', 'definitive-addons-for-elementor'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
            'value' => 'fas fa-minus',
            'library' => 'fa-solid',
            ],
            'condition'   => [
                    'dafe_accordion_item_icon_show_hide' => 'yes'
            ]
                
                
            ]
        );
        $this->add_control(
            'dafe_accordion_item_icon_inactive',
            [
            'label'   =>__('Inactive Icon', 'definitive-addons-for-elementor'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
            'value' => 'fas fa-plus',
            'library' => 'fa-solid',
            ],
            'condition'   => [
                    'dafe_accordion_item_icon_show_hide' => 'yes'
            ]
                
                
            ]
        );


        $this->end_controls_section();

        // style

        
        $this->start_controls_section(
            'dafe_accordion_title_style',
            [
                'label' => __('Accordion Title', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
 
        $this->start_controls_tabs(
            'dafe_title_colors',
            [
            'label' => __('Accordion Title Colors', 'definitive-addons-for-elementor'),
            ]
        );

        $this->start_controls_tab(
            'dafe_normal_color_tab',
            [
            'label' => __('Normal', 'definitive-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'title_color',
            [
            'label' => __('Color', 'definitive-addons-for-elementor'),
            'type'  => Controls_Manager::COLOR,

            'selectors' => [
                    '{{WRAPPER}} .dafe-accordion-title' => 'color: {{VALUE}}',
                ],
                
            ]
        );
        
        
        $this->add_control(
            'title_bg_color',
            [
            'label' => __('Background Color', 'definitive-addons-for-elementor'),
            'type'  => Controls_Manager::COLOR,
            'default' => '#eeeeee',
            'selectors' => [
                    '{{WRAPPER}} .dafe-accordion-title' => 'background-color: {{VALUE}}',
                ],
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'selector' => '{{WRAPPER}} .dafe-accordion-title',
            'fields_options'     => [
                        'border'          => [
                            'default'    => 'solid'
                        ],
                        'width'           => [
                            'default'    => [
                                'top'    => '1',
                                'right'  => '1',
                                'bottom' => '1',
                                'left'   => '1'
                            ]
                        ],
                        'color'          => [
                            'default'    => '#000000'
                        ]
                ],

            ]
        );
        
        

        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_hover_tab',
            [
            'label' => __('Hover', 'definitive-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'title_hvr_color',
            [
            'label'          => __('Color', 'definitive-addons-for-elementor'),
            'type'           => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .dafe-accordion-title:hover' => 'color: {{VALUE}}',
                ],
                
            ]
        );
        $this->add_control(
            'title_bg_hvr_color',
            [
            'label'          => __('Background Color', 'definitive-addons-for-elementor'),
            'type'           => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .dafe-accordion-title:hover' => 'background-color: {{VALUE}}',
            '{{WRAPPER}} .dafe-accordion-title.active' => 'background-color: {{VALUE}}!important',
            ],
                
            ]
        );

        $this->add_control(
            'title_border_hvr_color',
            [
            'label'     => __('Border Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .dafe-accordion-title:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'dafe_title_font',
                'selector' => '{{WRAPPER}} .dafe-accordion-title',
                
            ]
        );
        

        $this->add_responsive_control(
            'title_padding',
            [
                'label' =>__('Title Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
            'default'    => [
            'top'    => '10',
            'right'  => '20',
            'bottom' => '10',
            'left'   => '10',
            'isLinked' => false,
                ],

                'selectors' => [
                    '{{WRAPPER}} .dafe-accordion-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        
        
        $this->end_controls_section();
        
        
        $this->start_controls_section(
            'title_icon_style',
            [
                'label' =>__('Title Icon', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            'condition'   => [
            'dafe_accordion_title_icon_show_hide' => 'yes'
                ]
                
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
                    '{{WRAPPER}} .dafe-title-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .dafe-title-icon i' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'icon_title_hover_color',
            [
                'label' => __('Icon Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-title-icon i:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'title_icon_right_spacing',
            [
                'label' => __('Right Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px','%'],
            'default' => [
            'size' => 15
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-title-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    
                ],
                
            ]
        );
        
        $this->add_responsive_control(
            'title_icon_left_spacing',
            [
                'label' => __('Left Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px','%'],
            'default' => [
            'size' => 15
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-title-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                    
                ],
                
            ]
        );

        $this->end_controls_section();
        


        $this->start_controls_section(
            'active_icon_style',
            [
                'label' => __('Icon - Active & Inactive', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            'condition'   => [
            'dafe_accordion_item_icon_show_hide' => 'yes'
                ]
                
            ]
        );
        
        $this->add_responsive_control(
            'active_inactive_icon_size',
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
                    '{{WRAPPER}} .dafe-active-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .dafe-inactive-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
    
        $this->add_responsive_control(
            'active_icon_right_spacing',
            [
                'label' => __('Right Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px','%'],
            'default' => [
            'size' => 10
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-active-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .dafe-inactive-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                
            ]
        );
        $this->add_control(
            'active_inactive_icon_color',
            [
                'label' => __('Icon Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#6EC1E4',
                'selectors' => [
                    '{{WRAPPER}} .dafe-inactive-icon i,{{WRAPPER}} .dafe-active-icon i' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'active_inactive_icon_hvr_color',
            [
                'label' => __('Icon Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-inactive-icon i:hover,{{WRAPPER}} .dafe-active-inactive-icon .dafe-active-icon i:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        

        $this->end_controls_section();


        $this->start_controls_section(
            'accordion_style_content',
            [
                'label' => __('Accordion Content', 'definitive-addons-for-elementor'),
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
                    '{{WRAPPER}} .dafe-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        
        
        $this->add_control(
            'content_color',
            [
                'label' => __('Content Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-accordion-content' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .dafe-accordion-content' => 'background-color: {{VALUE}}',
                ],
                'default'    =>'#ffffff',
            ]
        );
        
        $this->add_control(
            'content_bg_hvr_color',
            [
                'label' => __('Content Background Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-accordion-content:hover' => 'background-color: {{VALUE}}',
                ],
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'dafe_content_font',
                'selector' => '{{WRAPPER}} .dafe-accordion-content',
                
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
            'name'     => 'accordion_content_border',
            'selector' => '{{WRAPPER}} .dafe-accordion-content',
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
                            'default'    => '#000000'
                        ]
                ],
            ]
        );
        
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'accordion_button_style',
            [
                'label' => __('Accordion Button', 'definitive-addons-for-elementor'),
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
                    '{{WRAPPER}} .accordion-btn-container a.accordion-btn' => 'color: {{VALUE}}!important',
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
                    '{{WRAPPER}} .accordion-btn-container a.accordion-btn' => 'background-color: {{VALUE}}!important',
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
                    '{{WRAPPER}} .accordion-btn-container a.accordion-btn:hover' => 'color: {{VALUE}}!important',
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
                    '{{WRAPPER}} .accordion-btn-container a.accordion-btn:hover' => 'background-color: {{VALUE}}!important',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_font',
                'selector' => '{{WRAPPER}} .accordion-btn-container a.accordion-btn',
                
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
                    '{{WRAPPER}} .accordion-btn-container a.accordion-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .accordion-btn-container a.accordion-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'selector' => '{{WRAPPER}} .accordion-btn-container a.accordion-btn',
                
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
                    '{{WRAPPER}} .accordion-btn-container a.accordion-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );


        $this->end_controls_section();
        
        $this->start_controls_section(
            'dafe_accordion_container_style',
            [
                'label' => __('Accordion Container', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .dafe-accordion-container',
            ]
        );

        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Container Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'dafe_container_shadow',

            'selector' => '{{WRAPPER}} .dafe-accordion-container',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Container Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'dafe_container_hvr_shadow',

            'selector' => '{{WRAPPER}} .dafe-accordion-container:hover',
            ]
        );
        
        $this->end_controls_section();
        
    
    }



	protected function render() {
        
		
		$settings = $this->get_settings_for_display();
		
	
        ?>

<div class="dafe-accordion-container">
    <?php 
		foreach ( $settings['dafe_accordion_item_repeater'] as  $key => $accordion_item ) : ?>
			<div class="dafe-accordion-entry">
			<?php if ( $accordion_item['title'] ) : ?>
			<div class="dafe-accordion-title-container">
				<a class="dafe-accordion-title" href="#accordion-<?php echo esc_attr($key); ?>">
				
				<?php if( !empty( $settings['dafe_accordion_title_icon']['value'])) : ?>
					<span class="dafe-title-icon">

						<?php Icons_Manager::render_icon( $settings['dafe_accordion_title_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                       
					</span>
				<?php endif; ?>
					
					<?php echo esc_html( $accordion_item['title'] ); ?>
				
				<?php if( !empty( $settings['dafe_accordion_item_icon_active']['value'])) : ?>
					<span class="dafe-active-icon">
						<?php Icons_Manager::render_icon( $settings['dafe_accordion_item_icon_active'], [ 'aria-hidden' => 'true' ] ); ?>
					</span>
				<?php endif; ?>
				<?php if( !empty( $settings['dafe_accordion_item_icon_inactive']['value'])) : ?>
					<span class="dafe-inactive-icon">
						<?php Icons_Manager::render_icon( $settings['dafe_accordion_item_icon_inactive'], [ 'aria-hidden' => 'true' ] ); ?>
                                   
					</span> 
				<?php endif; ?>
				</a>
			</div>
			<?php endif; ?>
				<?php if ( $accordion_item['dafe_accordion_content_area'] ) : ?>
					<div id="accordion-<?php echo esc_attr($key); ?>" class="dafe-accordion-content">
						<p><?php echo wp_kses_post( $accordion_item['dafe_accordion_content_area'] ); ?></p>
						<?php if ( $accordion_item['dafe_accordion_btn_text'] ) : ?>
						<?php
						if ( ! empty( $accordion_item['btn_url']['url'] ) ) {
							$this->add_link_attributes( 'acc_link'.$key, $accordion_item['btn_url'] );
						}
						?>
						<div class="accordion-btn-container">
							<a  <?php $this->print_render_attribute_string( 'acc_link'.$key ); ?> class="accordion-btn link">
						
								<?php echo esc_html($accordion_item['dafe_accordion_btn_text']); ?>
										
							</a>
						</div>
						<?php endif; ?>
					</div><!-- content end -->
				<?php endif; ?>
			</div><!-- section end -->
    
<?php endforeach; ?>
</div><!-- accordion end -->


        <?php 
	}
}