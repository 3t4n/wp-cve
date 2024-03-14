<?php
/**
 * Elementer Widget helper.
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

if (! is_plugin_active('woocommerce/woocommerce.php') ) {
    return;
}

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
 * SAShareCartWidget class
 */
class SAShareCartWidget extends Widget_Base
{
    
    /**
     * Get name function.
     *
     * @return array
     */
    public function get_name()
    {
        return 'smsalert-sharecart-widget';
    }

    /**
     * Get title function.
     *
     * @return array
     */
    public function get_title()
    {
        return __('SMS Alert Share Cart', 'sms-alert');
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
            'smsalertsharecart',
            'smsalertsharecart',
            'smsalert sharecart',
            'smsalert sharecart',
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
            'smsalert-sharecart-styles',
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
        return ['smsalertsharecart-elementor'];
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
		$this->registerTitleStyleControls(); 
        $this->registerInputStyleControls();    
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
            'section_smsalertsharecart_form',
            [
                'label' => __('SMS Alert Share Cart', 'sms-alert'),
            ]
        );     
 
        $this->add_control(
            'sa_ele_f_sharecart_title',
            [
                'label'        => __('Modal Title', 'sms-alert'),
                'type'         => "text",
                'placeholder'      => 'Enter Title', 
                                
            ]
        );        

        $this->add_control(
            'sa_ele_f_user_placehoder',
            [
                'label'        => __('User Name Placeholder', 'sms-alert'),
                'type'         => "text",
                'placeholder'      => 'Your Name',
                               
            ]
        );
        $this->add_control(
            'sa_ele_f_user_phone_placeholder',
            [
                'label'        => __('User Phone Placeholder', 'sms-alert'),
                'type'         => "text",
                'placeholder'      => 'Your Name Mobile',
                                
            ]
        );
        $this->add_control(
            'sa_ele_f_frnd_placeholder',
            [
                'label'        => __('Friend Name Placeholder', 'sms-alert'),
                'type'         => "text",
                'placeholder'      => 'Friend Name',
                               
            ]
        );
        $this->add_control(
            'sa_ele_f_frnd_phone_placeholder',
            [
                'label'        => __("Friend Phone Placeholder", 'sms-alert'),
                'type'         => "text",
                'placeholder'      => 'Friend Name Mobile',
                                
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
                'selector' => '{{WRAPPER}} .smsalertsharecart-widget-wrapper .modal-content',
                'exclude' => ['image'],        
            ]
        );   
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'form_container_border',
                'selector' => '{{WRAPPER}} .smsalertsharecart-widget-wrapper .modal-content',           
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper .modal-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
    protected function registerTitleStyleControls()
    {
        $this->start_controls_section(
            'section_form_description_style',
            [
                'label'     => __('Title', 'sms-alert'),
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper .box-title'=> 'text-align: {{VALUE}};'                
                ],
                
            ]
        );
        
        $this->add_control(
            'form_title_text_color',
            [
                'label'     => __('Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper .box-title' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper .box-title' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );  

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'form_title_typography',
                'label'     => __('Typography', 'sms-alert'),
                'selector'  => '{{WRAPPER}} .smsalertsharecart-widget-wrapper .box-title',
                
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper .box-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper .box-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    /**
     * Register Input Textarea style controls function.     
     *
     * @return array
     */    
    protected function registerInputStyleControls()
    {
        $this->start_controls_section(
            'section_form_fields_style',
            [
                'label' => __('Input Field', 'sms-alert'),
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper  input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname textarea,.smsalertsharecart-widget-wrapper #sc_umobile textarea,.smsalertsharecart-widget-wrapper #sc_fname textarea,.smsalertsharecart-widget-wrapper #sc_fmobile textarea {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname, .smsalertsharecart-widget-wrapper #sc_umobile , .smsalertsharecart-widget-wrapper #sc_fname , .smsalertsharecart-widget-wrapper #sc_fmobile  select' => 'text-align: {{VALUE}};',
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):not(.select2-search__field), {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname textarea, .smsalertsharecart-widget-wrapper #sc_umobile textarea, .smsalertsharecart-widget-wrapper #sc_fname textarea, .smsalertsharecart-widget-wrapper #sc_fmobile textarea  {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname select, .smsalertsharecart-widget-wrapper #sc_umobile  select, .smsalertsharecart-widget-wrapper #sc_fname  select, .smsalertsharecart-widget-wrapper #sc_fmobile  select{{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname, .smsalertsharecart-widget-wrapper #sc_umobile, .smsalertsharecart-widget-wrapper #sc_fname, .smsalertsharecart-widget-wrapper #sc_fmobile .select2-container--default .select2-selection--multiple' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname textarea, .smsalertsharecart-widget-wrapper #sc_umobile textarea, .smsalertsharecart-widget-wrapper #sc_fname textarea, .smsalertsharecart-widget-wrapper #sc_fmobile textarea{{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname, .smsalertsharecart-widget-wrapper #sc_umobile, .smsalertsharecart-widget-wrapper #sc_fname, .smsalertsharecart-widget-wrapper #sc_fmobile select' => 'color: {{VALUE}}',
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
                'selector'    => '{{WRAPPER}} .smsalertsharecart-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):not(.select2-search__field), {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname textarea, .smsalertsharecart-widget-wrapper #sc_umobile textarea , .smsalertsharecart-widget-wrapper #sc_fname textarea , .smsalertsharecart-widget-wrapper #sc_fmobile textarea {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname select {{WRAPPER}},.smsalertsharecart-widget-wrapper #sc_umobile select {{WRAPPER}},.smsalertsharecart-widget-wrapper #sc_fname select {{WRAPPER}},.smsalertsharecart-widget-wrapper #sc_fmobile select  {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname .select2-container--default .select2-selection--multiple, .smsalertsharecart-widget-wrapper #sc_umobile .select2-container--default .select2-selection--multiple, .smsalertsharecart-widget-wrapper #sc_fname .select2-container--default .select2-selection--multiple, .smsalertsharecart-widget-wrapper #sc_fmobile .select2-container--default .select2-selection--multiple',
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname textarea,.smsalertsharecart-widget-wrapper #sc_umobile textarea,.smsalertsharecart-widget-wrapper #sc_fname textarea,.smsalertsharecart-widget-wrapper #sc_fmobile textarea {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname select,.smsalertsharecart-widget-wrapper #sc_umobile select,.smsalertsharecart-widget-wrapper #sc_fname select,.smsalertsharecart-widget-wrapper #sc_fmobile select  {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname, .smsalertsharecart-widget-wrapper #sc_umobile, .smsalertsharecart-widget-wrapper #sc_fname, .smsalertsharecart-widget-wrapper #sc_fmobile .select2-container--default .select2-selection--multiple' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname textarea,.smsalertsharecart-widget-wrapper #sc_umobile textarea,.smsalertsharecart-widget-wrapper #sc_fname textarea,.smsalertsharecart-widget-wrapper #sc_fmobile textarea {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname, .smsalertsharecart-widget-wrapper #sc_umobile, .smsalertsharecart-widget-wrapper #sc_fname , .smsalertsharecart-widget-wrapper #sc_fmobile select' => 'text-indent: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname,.smsalertsharecart-widget-wrapper #sc_umobile,.smsalertsharecart-widget-wrapper #sc_fname,.smsalertsharecart-widget-wrapper #sc_fmobile select' => 'width: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname,.smsalertsharecart-widget-wrapper #sc_umobile,.smsalertsharecart-widget-wrapper #sc_fname,.smsalertsharecart-widget-wrapper #sc_fmobile select' => 'height: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname textarea,.smsalertsharecart-widget-wrapper #sc_umobile textarea,.smsalertsharecart-widget-wrapper #sc_fname textarea,.smsalertsharecart-widget-wrapper #sc_fmobile textarea {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname,.smsalertsharecart-widget-wrapper #sc_umobile,.smsalertsharecart-widget-wrapper #sc_fname,.smsalertsharecart-widget-wrapper #sc_fmobile select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname,.smsalertsharecart-widget-wrapper #sc_umobile,.smsalertsharecart-widget-wrapper #sc_fname,.smsalertsharecart-widget-wrapper #sc_fmobile' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'form_field_typography',
                'label'     => __('Typography', 'sms-alert'),
                'selector'  => '{{WRAPPER}} .smsalertsharecart-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname textarea,.smsalertsharecart-widget-wrapper #sc_umobile textarea,.smsalertsharecart-widget-wrapper #sc_fname textarea,.smsalertsharecart-widget-wrapper #sc_fmobile textarea {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname select,.smsalertsharecart-widget-wrapper #sc_umobile select,.smsalertsharecart-widget-wrapper #sc_fname,.smsalertsharecart-widget-wrapper #sc_fmobile select',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'form_field_box_shadow',
                'selector'  => '{{WRAPPER}} .smsalertsharecart-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname textarea,.smsalertsharecart-widget-wrapper #sc_umobile textarea,.smsalertsharecart-widget-wrapper #sc_fname  textarea{{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname select,.smsalertsharecart-widget-wrapper #sc_umobile select,.smsalertsharecart-widget-wrapper #sc_fname select,.smsalertsharecart-widget-wrapper #sc_fmobile select',
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname input:focus,.smsalertsharecart-widget-wrapper #sc_umobile:focus,.smsalertsharecart-widget-wrapper #sc_fname:focus,.smsalertsharecart-widget-wrapper #sc_fmobile:focus' => 'background-color: {{VALUE}}',
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
                'selector'    => '{{WRAPPER}} .smsalertsharecart-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname:focus,.smsalertsharecart-widget-wrapper #sc_umobile:focus,.smsalertsharecart-widget-wrapper #sc_fname:focus,.smsalertsharecart-widget-wrapper #sc_fmobile:focus',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'form_input_focus_box_shadow',
                'selector'  => '{{WRAPPER}} .smsalertsharecart-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_uname:focus,.smsalertsharecart-widget-wrapper #sc_umobile:focus,.smsalertsharecart-widget-wrapper #sc_fname:focus,.smsalertsharecart-widget-wrapper #sc_fmobile:focus',
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
                'label' => __('Share Cart Button', 'sms-alert'),
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
                'prefix_class' => 'smsalersharecart-widget-submit-button-',
                
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
                'prefix_class' => 'smsalertsharecart-widget-submit-button-',
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_btn' => 'width: {{SIZE}}{{UNIT}}', ],
                
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_btn' => 'background-color: {{VALUE}} !important;',
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_btn' => 'color: {{VALUE}} !important;',
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
                'selector'    => '{{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_btn',
            ]
        );

        $this->add_control(
            'form_submit_button_border_radius',
            [
                'label'      => __('Border Radius', 'sms-alert'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_btn' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'form_submit_button_typography',
                'label'     => __('Typography', 'sms-alert'),
                'selector'  => '{{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_btn',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'form_submit_button_box_shadow',
                'selector'  => '{{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_btn',
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_btn:hover' => 'background-color: {{VALUE}} !important;',
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_btn:hover' => 'color: {{VALUE}} !important;',
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
                    '{{WRAPPER}} .smsalertsharecart-widget-wrapper #sc_btn:hover' => 'border-color: {{VALUE}}',
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
            'smsalertsharecart_widget_wrapper',
            [
                'class' => [
                    'smsalertsharecart-widget-wrapper',
                ],
            ]
        );
        ?>
            <div <?php echo wp_kses_post($this->get_render_attribute_string('smsalertsharecart_widget_wrapper')); ?>>
                   <?php
                    echo SAPopup::getShareCartStyle(array('sa_title' =>$sa_ele_f_sharecart_title, 'sa_user_placeholder' =>$sa_ele_f_user_placehoder, 'sa_user_phone' =>$sa_ele_f_user_phone_placeholder, 'sa_frnd_placeholder' =>$sa_ele_f_frnd_placeholder,  'sa_frnd_phone'=>$sa_ele_f_frnd_phone_placeholder, 'sa_sharecart_button' =>$sa_submit_button));                     
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