<?php
/**
 * Emementer Widget helper.
 *
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */

use Helper\ElementorWidget;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography as Scheme_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Schemes\Color as Scheme_Color;


if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Emementer Widget helper.
 *
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 * SAExitIntentWidget class
 */
class SAExitIntentWidget extends Widget_Base
{
    
    /**
     * Get name function.
     *
     * @return array
     */
    public function get_name()
    {
        return 'smsalert-exitintent-widget';
    }

    /**
     * Get title function.
     *
     * @return array
     */
    public function get_title()
    {
        return __('SMS Alert Exit Intent', 'sms-alert');
    }

    /**
     * Get icon function.
     *
     * @return array
     */
    public function get_icon()
    {
        return 'eicon-form-horizontal';
    }

    /**
     * Get keywords function.     
     *
     * @return array
     */
    public function get_keywords()
    {
        return [
            'smsalertexitintent',
            'smsalertexitintent',
            'smsalerts exitintent',
            'smsalert exitintent',
            'contact form',
            'form',
            'elementor form',
        ];
    }

    /**
     * Get  categories function.     
     *
     * @return array
     */
    public function get_categories()
    {        
        return ['general'];
    }

    /**
     * Get style depends function.     
     *
     * @return array
     */
    public function get_style_depends()
    {
        return [
            'smsalert-exitintent-styles',
            'smsalert-public-default',
        ];
    }
     
    /**
     * Get  scrip depends function.     
     *
     * @return array
     */
    public function get_script_depends()
    {
        return ['smsalertexitintent-elementor'];
    }
    /**
     * Register controls function.     
     *
     * @return array
     */
    protected function register_controls()
    {
        $this->registerGeneralControls();        
        $this->registerFormContainerStyleControls();                    
        $this->registerDescriptionStyleControls();
        $this->registerLabelStyleControls(); 
        $this->registerInputTextareaStyleControls();        
        $this->registerSubmitButtonStyleControls();       
        
    }

    /**
     * Register general controls function.     
     *
     * @return array
     */
    protected function registerGeneralControls()
    {
        $this->start_controls_section(
            'section_smsalertexitintent_form',
            [
                'label' => __('SMS Alert Exit Intent', 'sms-alert'),
            ]
        );
 
        $this->add_control(
            'sa_ele_f_mobile_title',
            [
                'label'        => __('Modal Title', 'sms-alert'),
                'type'         => "text",
                'placeholder'      => 'Enter Title', 
                                
            ]
        );        

        $this->add_control(
            'sa_ele_f_mobile_description',
            [
                'label'        => __('Modal Description', 'sms-alert'),
                'type'         => "textarea",
                'placeholder'      => 'Enter Description',
                               
            ]
        );
        $this->add_control(
            'sa_ele_f_mobile_label',
            [
                'label'        => __('Phone Label', 'sms-alert'),
                'type'         => "text",
                'placeholder'      => 'Enter Label',
                                
            ]
        );
        $this->add_control(
            'sa_ele_f_mobile_placeholder',
            [
                'label'        => __('Phone Placeholder', 'sms-alert'),
                'type'         => "text",
                'placeholder'      => 'Enter Placeholder',
                               
            ]
        );
        $this->add_control(
            'sa_submit_button',
            [
                'label'        => __('Button Text', 'sms-alert'),
                'type'         => "text",
                'placeholder'  => 'Enter Button Text',
                               
            ]
        );       
        $this->end_controls_section();
    }    
 
    /**
     * Register form container style controls function.     
     *
     * @return array
     */
    protected function registerFormContainerStyleControls()
    {
        $this->start_controls_section(
            'section_form_container_style',
            [
                'label' => __('Form Container', 'sms-alert'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'form_container_background',
                'label'    => __('Background', 'sms-alert'),
                'types'    => ['classic'],
                'selector' => '{{WRAPPER}} .smsalertexitintent-widget-wrapper',
                'exclude' => ['image'],        
            ]
        );   
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'form_container_border',
                'selector' => '{{WRAPPER}} .smsalertexitintent-widget-wrapper',
            'exclude' => ['Width'],
            ]
        );
        $this->add_control(
            'form_container_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'sms-alert'),
                'type'       => Controls_Manager::DIMENSIONS,
                'separator'  => 'before',
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );      
        $this->end_controls_section();
    }    
    
     /**
      * Register Description style controls function.     
      *
      * @return array
      */
    protected function registerDescriptionStyleControls()
    {
        $this->start_controls_section(
            'section_form_description_style',
            [
                'label'     => __('Title & Description', 'sms-alert'),
                'tab'       => Controls_Manager::TAB_STYLE,
                
            ]
        );

        $this->add_responsive_control(
            'heading_alignment',
            [
                'label'   => __('Alignment', 'sms-alert'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'sms-alert'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'sms-alert'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'sms-alert'),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper .sa_description'=> 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper .sa_title' => 'text-align: {{VALUE}};',
                ],
                
            ]
        );

        $this->add_control(
            'heading_title',
            [
                'label'     => __('Title', 'sms-alert'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
               
            ]
        );

        $this->add_control(
            'form_title_text_color',
            [
                'label'     => __('Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper .sa_title' => 'color: {{VALUE}}',
                ],
                
            ]
        );
        $this->add_control(
            'form_title_bg_color',
            [
                'label'     => __('Background Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper .sa_title' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );  

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'form_title_typography',
                'label'     => __('Typography', 'sms-alert'),
                'selector'  => '{{WRAPPER}} .smsalertexitintent-widget-wrapper .sa_title',
                
            ]
        );

        $this->add_responsive_control(
            'form_title_margin',
            [
                'label'              => __('Margin', 'sms-alert'),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => ['px', 'em', '%'],
                'allowed_dimensions' => 'vertical',
                'placeholder'        => [
                    'top'    => '',
                    'right'  => 'auto',
                    'bottom' => '',
                    'left'   => 'auto',
                ],
                'selectors' => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper .sa_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                
               
            ]
        );

        $this->add_responsive_control(
            'form_title_padding',
            [
                'label'      => esc_html__('Padding', 'sms-alert'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper .sa_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_description',
            [
                'label'     => __('Description', 'sms-alert'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                
            ]
        );

        $this->add_control(
            'heading_description_text_color',
            [
                'label'     => __('Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper .sa_description' => 'color: {{VALUE}}',
                ],
                
            ]
        );
        $this->add_control(
            'heading_description_bg_color',
            [
                'label'     => __('Background Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper .sa_description' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );  

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'heading_description_typography',
                'label'     => __('Typography', 'sms-alert'),
                'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
                'selector'  => '{{WRAPPER}} .smsalertexitintent-widget-wrapper .sa_description',
                
            ]
        );

        $this->add_responsive_control(
            'heading_description_margin',
            [
                'label'              => __('Margin', 'sms-alert'),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => ['px', 'em', '%'],
                'allowed_dimensions' => 'vertical',
                'placeholder'        => [
                    'top'    => '',
                    'right'  => 'auto',
                    'bottom' => '',
                    'left'   => 'auto',
                ],
                'selectors' => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper .sa_description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                
            ]
        );

        $this->add_responsive_control(
            'heading_description_padding',
            [
                'label'      => esc_html__('Padding', 'sms-alert'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper .sa_description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }
    /**
     * Register label style controls function.     
     *
     * @return array
     */
    protected function registerLabelStyleControls()
    {
        $this->start_controls_section(
            'section_form_label_style',
            [
                'label' => __('Phone Label', 'sms-alert'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'form_label_text_color',
            [
                'label'     => __('Text Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper #sa_label' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'form_label_bg_color',
            [
                'label'     => __('Background Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper .sa_label' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        ); 
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'form_label_typography',
                'label'    => __('Typography', 'sms-alert'),
                'selector' => '{{WRAPPER}} .smsalertexitintent-widget-wrapper #sa_label',
            ]
        );
        $this->end_controls_section();
    }


    /**
     * Register Input Textarea style controls function.     
     *
     * @return array
     */    
    protected function registerInputTextareaStyleControls()
    {
        $this->start_controls_section(
            'section_form_fields_style',
            [
                'label' => __('Phone Field', 'sms-alert'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'input_alignment',
            [
                'label'   => __('Alignment', 'sms-alert'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'smsale'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'sms-alert'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'sms-alert'),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile textarea, {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile select' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_form_fields_style');

        $this->start_controls_tab(
            'tab_form_fields_normal',
            [
                'label' => __('Normal', 'sms-alert'),
            ]
        );

        $this->add_control(
            'form_field_bg_color',
            [
                'label'     => __('Background Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):not(.select2-search__field), {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile textarea, {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile select, {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile .select2-container--default .select2-selection--multiple' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'form_field_text_color',
            [
                'label'     => __('Text Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile textarea, {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile select' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'form_field_border',
                'label'       => __('Border', 'sms-alert'),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .smsalertexitintent-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):not(.select2-search__field), {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile textarea, {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile select,  {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile .select2-container--default .select2-selection--multiple',
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'form_field_radius',
            [
                'label'      => __('Border Radius', 'sms-alert'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile textarea, {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile select,  {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile .select2-container--default .select2-selection--multiple' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_field_text_indent',
            [
                'label' => __('Text Indent', 'sms-alert'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 60,
                        'step' => 1,
                    ],
                    '%' => [
                        'min'  => 0,
                        'max'  => 30,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile textarea, {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile select' => 'text-indent: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'form_input_width',
            [
                'label' => __('Input Width', 'sms-alert'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1200,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile select' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_input_height',
            [
                'label' => __('Input Height', 'sms-alert'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 80,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile select' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_field_padding',
            [
                'label'      => __('Padding', 'sms-alert'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile textarea, {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_field_spacing',
            [
                'label' => __('Spacing', 'sms-alert'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'form_field_typography',
                'label'     => __('Typography', 'sms-alert'),
                'selector'  => '{{WRAPPER}} .smsalertexitintent-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile textarea, {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile select',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'form_field_box_shadow',
                'selector'  => '{{WRAPPER}} .smsalertexitintent-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile textarea, {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile select',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_form_fields_focus',
            [
                'label' => __('Focus', 'sms-alert'),
            ]
        );

        $this->add_control(
            'form_field_bg_color_focus',
            [
                'label'     => __('Background Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile textarea:focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'form_input_focus_border',
                'label'       => __('Border', 'sms-alert'),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .smsalertexitintent-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile textarea:focus',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'form_input_focus_box_shadow',
                'selector'  => '{{WRAPPER}} .smsalertexitintent-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-mobile textarea:focus',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }
    
     
    
     /**
      * Register submit button style controls function.     
      *
      * @return array
      */
    protected function registerSubmitButtonStyleControls()
    {
        $this->start_controls_section(
            'section_form_submit_button_style',
            [
                'label' => __('Save Cart Button', 'sms-alert'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'form_submit_button_align',
            [
                'label'   => __('Alignment', 'sms-alert'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'sms-alert'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'sms-alert'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'sms-alert'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default'      => '',
                'prefix_class' => 'smsalertexitintent-widget-submit-button-',
                
            ]
        );

        $this->add_control(
            'form_submit_button_width_type',
            [
                'label'   => __('Width', 'sms-alert'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => [
                    'full-width' => __('Full Width', 'sms-alert'),
                    'custom'     => __('Custom', 'sms-alert'),
                ],
                'prefix_class' => 'smsalertexitintent-widget-submit-button-',
            ]
        );

        $this->add_responsive_control(
            'form_submit_button_width',
            [
                'label' => __('Width', 'sms-alert'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1200,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-submit' => 'width: {{SIZE}}{{UNIT}}', ],
                
            ]
        );

        $this->start_controls_tabs('tabs_submit_button_style');

        $this->start_controls_tab(
            'tab_submit_button_normal',
            [
                'label' => __('Normal', 'sms-alert'),
            ]
        );

        $this->add_control(
            'form_submit_button_bg_color_normal',
            [
                'label'     => __('Background Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#409EFF',
                'selectors' => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-submit' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'form_submit_button_text_color_normal',
            [
                'label'     => __('Text Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-submit' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'form_submit_button_border_normal',
                'label'       => __('Border', 'sms-alert'),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-submit',
            ]
        );

        $this->add_control(
            'form_submit_button_border_radius',
            [
                'label'      => __('Border Radius', 'sms-alert'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_submit_button_padding',
            [
                'label'      => __('Padding', 'sms-alert'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_submit_button_margin',
            [
                'label' => __('Margin Top', 'sms-alert'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 150,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-submit' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'form_submit_button_typography',
                'label'     => __('Typography', 'sms-alert'),
                'selector'  => '{{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-submit',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'form_submit_button_box_shadow',
                'selector'  => '{{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-submit',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_submit_button_hover',
            [
                'label' => __('Hover', 'sms-alert'),
            ]
        );

        $this->add_control(
            'form_submit_button_bg_color_hover',
            [
                'label'     => __('Background Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-submit:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'form_submit_button_text_color_hover',
            [
                'label'     => __('Text Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-submit:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->add_control(
            'form_submit_button_border_color_hover',
            [
                'label'     => __('Border Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .smsalertexitintent-widget-wrapper #cart-exit-intent-submit:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }
    
    
    
    

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     *
     * @return array
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
		$settings = !empty($settings)?$settings:array();
        extract($settings);
        $this->add_render_attribute(
            'smsalertexitintent_widget_wrapper',
            [
                'class' => [
                    'smsalertexitintent-widget-wrapper',
                ],
            ]
        );
        ?>
        <div <?php echo wp_kses_post($this->get_render_attribute_string('smsalertexitintent_widget_wrapper')); ?>>
	   <?php
		echo SAPopup::getExitIntentStyle(array('cvt_title' =>$sa_ele_f_mobile_title, 'cvt_description' =>$sa_ele_f_mobile_description, 'cvt_label' =>$sa_ele_f_mobile_label, 'cvt_placeholder' =>$sa_ele_f_mobile_placeholder, 'cvt_button' =>$sa_submit_button));
					
    }    

     /**
      * Content template function.     
      *
      * @return array
      */
    function content_template()
    {
    }
}