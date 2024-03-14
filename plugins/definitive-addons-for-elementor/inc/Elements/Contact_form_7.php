<?php
/**
 * Contact Form7
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
namespace Definitive_Addons_Elementor\Elements;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use \Elementor\Widget_Base;

defined('ABSPATH') || die();

/**
 * Contact Form7
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Contact_Form7 extends Widget_Base
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
        return __('DA: Contact Form 7', 'definitive-addons-for-elementor');
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
        return 'dafe_contact_form7';
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
        return 'eicon-envelope';
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
        return [ 'form', 'contact', 'letter','7' ];
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
        $da = new Reuse();
        if (!function_exists('wpcf7')) {
            $this->start_controls_section(
                'dafe_reminder_msg',
                [
                    'label' => __('Reminder Message!', 'definitive-addons-for-elementor'),
                ]
            );

            $this->add_control(
                'cf7_reminder_msg_txt',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => __('Contact Form 7 is not installed & activated. Please install and activate it.', 'definitive-addons-for-elementor'),
                    'content_classes' => 'reminder_msg',
                ]
            );

               $this->end_controls_section();
        } else {
            
            $this->start_controls_section(
                'dafe_section_cf7',
                [
                'label' => __('Contact Form 7', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
                ]
            );
            
            
            if (is_array($da->dafe_get_form_list())) {
        
                $this->add_control(
                    'cf7_form_list',
                    [
                    'label' =>__('Select Form', 'definitive-addons-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'label_block' => true,
                    'options' =>$da->dafe_get_form_list(),
                    'default' => '0',
                    ]
                );
            } else {
                $this->add_control(
                    'cf7_form_list',
                    [
                    'type' => Controls_Manager::RAW_HTML,
                    'label' =>$da->dafe_get_form_list()
                    ]
                );
            }
            
            $this->add_control(
                'show_hide_title',
                [
                'label' => __('Show/Hide Title', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true,
                ]
            );
        
            $this->add_control(
                'title',
                [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => __('Title Text', 'definitive-addons-for-elementor'),
                'condition' => [
                  'show_hide_title' => 'yes',
                ],
                'default' =>__('I am Contact Form 7 Title.', 'definitive-addons-for-elementor')
                ]
            );
        
            $this->add_control(
                'title_tag',
                [
                'label' =>__('Title HTML Tag', 'definitive-addons-for-elementor'),
                'type' =>Controls_Manager::SELECT,
                'default' => 'h1',
                
                'options' => [
                'h1' => __('H1', 'definitive-addons-for-elementor'),
                'h2' => __('H2', 'definitive-addons-for-elementor'),
                'h3' => __('H3', 'definitive-addons-for-elementor'),
                'h4' => __('H4', 'definitive-addons-for-elementor'),
                'h5' => __('H5', 'definitive-addons-for-elementor'),
                'h6' => __('H6', 'definitive-addons-for-elementor'),
                'span' =>__('Span', 'definitive-addons-for-elementor')
                ],
                ]
            );
        
            $this->add_control(
                'show_hide_desc',
                [
                'label' => __('Show/Hide Description', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true,
                ]
            );
        
            $this->add_control(
                'description_txt',
                [
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'label' => __('Description Text', 'definitive-addons-for-elementor'),
                'condition' => [
                  'show_hide_desc' => 'yes',
                ],
                'default' => __('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget .', 'definitive-addons-for-elementor')
                ]
            );
            $this->add_control(
                'cf7_alignment',
                [
                'label' =>__('Title Align', 'definitive-addons-for-elementor'),
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
                'default' => 'center',
                
                ]
            );

            $this->end_controls_section();

            //

            $this->start_controls_section(
                'cf7_container',
                [
                'label' => __('Form Container', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );
        
            $this->add_responsive_control(
                'container_padding',
                [
                'label' =>__('Container Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                '{{WRAPPER}} .cf7_container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                ]
            );
            $this->add_responsive_control(
                'container_margin',
                [
                'label' => __('Container Margin', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                '{{WRAPPER}} .cf7_container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                ]
            );
        
            $this->add_control(
                'container_bg_color',
                [
                'label' => __('Container Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#eee',
                'selectors' => [
                '{{WRAPPER}} .cf7_container,{{WRAPPER}} .wpcf7-form' => 'background-color: {{VALUE}}',
                ],
                ]
            );
        
        
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .cf7_container',
                ]
            );

            $this->add_responsive_control(
                'container_border_radius',
                [
                'label' => __('Container Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                '{{WRAPPER}} .cf7_container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
                ]
            );
            $this->end_controls_section();
        
            $this->start_controls_section(
                'cf7_title_section',
                [
                'label' => __('Form Title', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_responsive_control(
                'title_bottom_spacing',
                [
                'label' => __('Title Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                'size' => 20
                ],
                'selectors' => [
                '{{WRAPPER}} .cf7_title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                ]
            );

            $this->add_control(
                'title_color',
                [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .cf7_title' => 'color: {{VALUE}}',
                ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                'name' => 'title_font',
                'selector' => '{{WRAPPER}} .cf7_title',
                
                ]
            );


       
            $this->end_controls_section();
        
            $this->start_controls_section(
                'cf7_description_section',
                [
                'label' => __('Form Description', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_responsive_control(
                'desc_bottom_spacing',
                [
                'label' => __('Description Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                'size' => 40
                ],
                'selectors' => [
                '{{WRAPPER}} .cf7_description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                ]
            );

            $this->add_control(
                'desc_color',
                [
                'label' => __('Description Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .cf7_description' => 'color: {{VALUE}}',
                ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                'name' => 'Description_font',
                'selector' => '{{WRAPPER}} .cf7_description',
                
                ]
            );


       
            $this->end_controls_section();
        
            $this->start_controls_section(
                'cf7_label_section',
                [
                'label' => __('Form Label', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_responsive_control(
                'label_bottom_spacing',
                [
                'label' => __('Label Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                ]
            );

            $this->add_control(
                'label_color',
                [
                'label' => __('Label Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form label,{{WRAPPER}} .cf7_container span.wpcf7-list-item-label' => 'color: {{VALUE}}',
                ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                'name' => 'label_font',
                'selector' => '{{WRAPPER}} .wpcf7 .wpcf7-form label',
                
                ]
            );


       
            $this->end_controls_section();
        
            $this->start_controls_section(
                'cf7_input_section',
                [
                'label' => __('Text Input', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_responsive_control(
                'txt_input_bottom_spacing',
                [
                'label' => __('Text Input Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                '{{WRAPPER}} .wpcf7-form .wpcf7-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                ]
            );
            $this->add_responsive_control(
                'txt_input_width',
                [
                'label' => __('Text Input Width(%)', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 60,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => 60,
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 60,
					'unit' => '%',
				],
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-text' => 'width: {{SIZE}}%;',
                ],
                ]
            );

            $this->add_control(
                'txt_input_color',
                [
                'label' => __('Text Input Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-text' => 'color: {{VALUE}}',
                ],
                ]
            );
        
            $this->add_control(
                'txt_input_bg_color',
                [
                'label' => __('Text Input Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-text' => 'background-color: {{VALUE}};',
                ],
                ]
            );
        
            $this->add_control(
                'txt_input_focus_color',
                [
                'label' => __('Text Input Focus Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-text' => '1px solid color: {{VALUE}}',
                ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                'name' => 'txt_input_font',
                'selector' => '{{WRAPPER}} .wpcf7 .cf7_container .wpcf7-text',
                
                ]
            );
        
            $this->add_responsive_control(
                'txt_input_padding',
                [
                'label' => __('Text Input Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                '{{WRAPPER}} .wpcf7-form .wpcf7-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                ]
            );
        
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                'name' => 'txt_input_border',
                'selector' => '{{WRAPPER}} .wpcf7-form .wpcf7-text',
                ]
            );

            $this->add_responsive_control(
                'txt_input_border_radius',
                [
                'label' => __('Text Input Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                '{{WRAPPER}} .wpcf7-form .wpcf7-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
                ]
            );

            $this->end_controls_section();
        
            $this->start_controls_section(
                'cf7_txt_area_section',
                [
                'label' => __('Text Area Input', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_responsive_control(
                'txt_area_bottom_spacing',
                [
                'label' => __('Text Area Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-textarea' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                ]
            );

            $this->add_control(
                'txt_area_color',
                [
                'label' => __('Text Area Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-textarea' => 'color: {{VALUE}}',
                ],
                ]
            );
        
            $this->add_control(
                'txt_area_bg_color',
                [
                'label' => __('Text Area Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-textarea' => 'background-color: {{VALUE}}',
                ],
                ]
            );
            $this->add_control(
                'txt_area_focus_color',
                [
                'label' => __('Text Area Focus Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-textarea:focus' => 'border:1px solid {{VALUE}}',
                ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                'name' => 'txt_area_font',
                'selector' => '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-textarea',
                
                ]
            );
        
            $this->add_responsive_control(
                'txt_area_padding',
                [
                'label' => __('Text Area Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                ]
            );
        
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                'name' => 'txt_area_border',
                'selector' => '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-textarea',
                ]
            );

            $this->add_responsive_control(
                'txt_area_border_radius',
                [
                'label' => __('Text Area Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
                ]
            );


            $this->end_controls_section();
        
            $this->start_controls_section(
                'cf7_checkbox_radio_section',
                [
                'label' => __('Checkbox and Radio Button', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );
        
            $this->add_control(
                'layout',
                [
                'label' =>__('Checkbox Layout', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => [
                'inline'  => __('Inline', 'definitive-addons-for-elementor'),
                'block' => __('Block', 'definitive-addons-for-elementor'),
                    
                ],
                'default' => 'inline',
                
                ]
            );
        
            $this->add_control(
                'layout_radio',
                [
                'label' =>__('Radio Button Layout', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => [
                'inliner'  => __('Inline', 'definitive-addons-for-elementor'),
                'blockr' => __('Block', 'definitive-addons-for-elementor'),
                    
                ],
                'default' => 'inliner',
                
                ]
            );


     
            $this->add_responsive_control(
                'checkbox_size',
                [
                'label' => __('Checkbox/Radio Button Size', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                'px' => [
                  'min' => 0,
                  'max' => 100,
                  'step' => 1,
                ],
                ],
                
                'selectors' => [
                '{{WRAPPER}} .wpcf7 input[type="checkbox"]' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
                
                ]
            );
        
        
        
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                'name' => 'checkbox_font',
                'selector' => '{{WRAPPER}} .cf7_container .wpcf7-list-item-label',
                
                ]
            );
        
        

            $this->end_controls_section();
        
            $this->start_controls_section(
                'cf7_button_section',
                [
                'label' =>__('Form Button', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );
        
            $this->add_responsive_control(
                'btn_bottom_spacing',
                [
                'label' =>__('Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-submit' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                ]
            );
        
            $this->add_responsive_control(
                'btn_padding',
                [
                'label' =>__('Button Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'=>['top' => '7','right' => '15','bottom' => '7','left' => '15'],
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'btn_color',
                [
                'label' =>__('Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default'  => '#fff',
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-submit' => 'color: {{VALUE}}',
                ],
                ]
            );
        
            $this->add_control(
                'btn_bg_color',
                [
                'label' =>__('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default'  => '#000',
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-submit' => 'background-color: {{VALUE}}',
                ],
                ]
            );
        
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                'label' =>__('Button Shadow', 'definitive-addons-for-elementor'),
                'name'  => 'btn_box_shadow',

                'selector' => '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-submit',
                ]
            );
        
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                'name' => 'btn_border',
                'selector' => '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-submit',
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
                'btn_hover_color',
                [
                'label' =>__('Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-submit:hover' => 'color: {{VALUE}}',
                ],
                ]
            );
            $this->add_control(
                'btn_hover_bg_color',
                [
                'label' =>__('Hover Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-submit:hover' => 'background-color: {{VALUE}}',
                ],
                ]
            );
        
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                'label' => __('Hover Shadow', 'definitive-addons-for-elementor'),
                'name'     => 'btn_hvr_shadow',

                'selector' => '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-submit:hover',
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
                'btn_border_radius',
                [
                'label' => __('Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
                ]
            );
        
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                'name' => 'btn_font',
                'selector' => '{{WRAPPER}} .wpcf7 .wpcf7-form .wpcf7-submit',
                
                ]
            );
        
        
    

            $this->end_controls_section();
    
        } 
    }
    
	
	

	protected function render() {
		
        $settings = $this->get_settings_for_display();
		$cf7_form_list = $this->get_settings_for_display('cf7_form_list');
		$title_tag = $this->get_settings_for_display('title_tag');
		$description_txt = $this->get_settings_for_display('description_txt');
		$align = $this->get_settings_for_display('cf7_alignment');
		$show_hide_title = $this->get_settings_for_display('show_hide_title');
		$show_hide_desc = $this->get_settings_for_display('show_hide_desc');
		$layout = $this->get_settings_for_display('layout');
		$layout_radio = $this->get_settings_for_display('layout_radio');
		
		
		?>
			
			<div  id="cf-7" class="cf7_container <?php echo esc_attr($layout); ?> <?php echo esc_attr($layout_radio); ?>">
				<div class="form_header <?php echo esc_attr($align); ?>">
				<?php if (!empty($settings['title'])){  ?>
				<<?php echo esc_attr($title_tag);?> class="cf7_title <?php echo esc_attr($show_hide_title); ?>"><?php echo esc_html($settings['title']);?></<?php echo esc_attr($title_tag);?>>
				<?php } ?>
				<p class="cf7_description <?php echo esc_attr($align); ?> <?php echo esc_attr($show_hide_desc); ?>"><?php echo esc_html($description_txt);?></p>
				</div>
				
				<?php echo do_shortcode('[contact-form-7 id="' . esc_attr($cf7_form_list) . '" ]');?>
				
			</div>
		<?php
    }
	
}
