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
 * SMSAlertForms class
 */
class SMSAlertForms extends Widget_Base
{
    
    /**
     * Get name function.
     *
     * @return array
     */
    public function get_name()
    {
        return 'smsalert-form-widget';
    }

    /**
     * Get title function.
     *
     * @return array
     */
    public function get_title()
    {
        return __('SMS Alert Forms', 'sms-alert');
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
            'smsalertform',
            'smsalertform',
            'smsalert form',
            'smsalertform forms',
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
            'smsalert-form-styles',
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
        return ['smsalert-elementor'];
    }

    /**
     * Register controls function.     
     *
     * @return array
     */
    protected function register_controls()
    {
        $this->registerGeneralControls();        
        $this->registerTitleDescriptionStyleControls();
        $this->registerFormContainerStyleControls();
        $this->registerLabelStyleControls();
        $this->registerInputTextareaStyleControls();
        $this->registerPlaceholderStyleControls();       
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
            'section_smsalert_form',
            [
                'label' => __('SMS Alert Forms', 'sms-alert'),
            ]
        );

        $this->add_control(
            'form_list',
            [
                'label'       => esc_html__('SMS Alert Forms', 'sms-alert'),
                'type'        => Controls_Manager::SELECT,
                'label_block' => true,
                'options'     => array('select_form'=>'Select Form','sa_signup'=>'Signup With Mobile','sa_login'=>'Login With Otp','sa_subscription'=>'Subscription Form','sa_sharecart'=>'Share Cart Button'),
            'default' => 'select_form',
                
                
            ]
        );
        $this->add_control(
            'sa_ele_f_group',
            [
            'type' => Controls_Manager::SELECT,
            'label' =>__('Select Group', 'sms-alert'),
            'options' => $this->getGroupList(),
            'default' => '',                
            'condition' => [
                    'form_list' => ['sa_subscription']                    
                ],
            ]
        ); 
        $this->add_control(
            'custom_title_description',
            [
                'label'        => __('Enable Title & Description', 'sms-alert'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'sms-alert'),
                'label_off'    => __('No', 'sms-alert'),
                'return_value' => 'yes',
            'condition' => [
                    'form_list' => ['sa_signup','sa_login', 'sa_subscription'],
                ],
            ]
        );

        $this->add_control(
            'form_title_custom',
            [
                'label'       => esc_html__('Title', 'sms-alert'),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => '',
                'condition'   => [
                    'custom_title_description' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'form_description_custom',
            [
                'label'     => esc_html__('Description', 'sms-alert'),
                'type'      => Controls_Manager::TEXTAREA,
                'default'   => '',
                'condition' => [
                    'custom_title_description' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'labels_switch',
            [
                'label'        => __('Enable Label', 'sms-alert'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => __('Show', 'sms-alert'),
                'label_off'    => __('Hide', 'sms-alert'),
                'return_value' => 'yes',
            'condition' => [
                    'form_list' => ['sa_signup','sa_login','sa_subscription'],
                ],
            ]
        );
        $this->add_control(
            'sa_ele_f_mobile_lbl',
            [
                'label'        => __('Label', 'sms-alert'),
                'type'         => "text",
                'placeholder'      => 'Enter Label',
            'condition' => [
                    'form_list' => ['sa_signup','sa_login','sa_subscription'],
                    'labels_switch'=>'yes',
                ],
                
            ]
        );         
         $this->add_control(
            'sa_ele_f_user_mobile',
            [
                'label'        => __('Mobile Label', 'sms-alert'),
                'type'         => "text",
                'placeholder'      => 'Mobile',
            'condition' => [
                    'form_list' => ['sa_subscription'],
					 'labels_switch'=>'yes',                                       
                ],
				
                               
            ]
        );
        $this->add_control(
            'placeholder_switch',
            [
                'label'        => __('Enable Placeholder', 'sms-alert'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => __('Show', 'sms-alert'),
                'label_off'    => __('Hide', 'sms-alert'),
                'return_value' => 'yes',
            'condition' => [
                    'form_list' => ['sa_signup','sa_login','sa_subscription'],
                ],
            ]
        );
        $this->add_control(
            'sa_ele_f_mobile_place',
            [
                'label'        => __('Placeholder', 'sms-alert'),
                'type'         => "text",
                'placeholder'      => 'Enter Placeholder',
            'condition' => [
                    'form_list' => ['sa_signup','sa_login','sa_subscription'],
            'placeholder_switch'=>'yes',
                ],
                
            ]
        );       
        $this->add_control(
            'sa_ele_f_phone_placeholder',
            [
                'label'        => __("Mobile Placeholder", 'sms-alert'),
                'type'         => "text",
                'placeholder'      => 'Enter Mobile Number',
            'condition' => [
                    'form_list' => ['sa_subscription'],
                    'placeholder_switch'=>'yes',                     
                ],
                                
            ]
        );
        
        $this->add_control(
            'sa_ele_f_mobile_botton',
            [
                'label'        => __('Button Text', 'sms-alert'),
                'type'         => "text",
                'placeholder'      => 'Enter Button Text',
            'condition' => [
                    'form_list' => ['sa_signup','sa_login','sa_subscription'],
                ],
                
            ]
        ); 
		$this->add_control(
            'sa_ele_f_redirect_url',
            [
                'label'        => __('Redirect Url', 'sms-alert'),
                'type'         => "text",
                'placeholder'      => 'Enter Redirect Url',
            'condition' => [
                    'form_list' => ['sa_signup','sa_login'],
                ],
                
            ]
        );
        $this->end_controls_section();
    } 
    
    /**
     * Get GroupList function.     
     *
     * @return array
     */
    protected function getGroupList()
    {
        $groups = (array)json_decode(SmsAlertcURLOTP::groupList(), true);
        $obj=array();
        if (!empty($groups['status']) && "success" === $groups['status'] )
		{			
			foreach ( $groups['description'] as $group ) {
				$obj[$group['Group']['name']] = $group['Group']['name']; 
			} 
		}
        return $obj;
    }    
    
    /**
     * Register title description style controls function.     
     *
     * @return array
     */
    protected function registerTitleDescriptionStyleControls()
    {
        $this->start_controls_section(
            'section_form_title_style',
            [
                'label'     => __('Title & Description', 'sms-alert'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'custom_title_description' => 'yes',
                ],
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
                    '{{WRAPPER}} .smsalertform-widget-title,.smsalertform-widget-description #sa-subscribe-form'       => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .smsalertform-widget-description,.smsalertform-widget-description #sa-subscribe-form' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'custom_title_description' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'heading_title',
            [
                'label'     => __('Title', 'sms-alert'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'custom_title_description' => 'yes',
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
                    '{{WRAPPER}} .smsalertform-widget-title,.smsalertform-widget-description #sa-subscribe-form' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_title_description' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'form_title_typography',
                'label'     => __('Typography', 'sms-alert'),
                'selector'  => '{{WRAPPER}} .smsalertform-widget-title,.smsalertform-widget-description #sa-subscribe-form',
                'condition' => [
                    'custom_title_description' => 'yes',
                ],
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
                    '{{WRAPPER}} .smsalertform-widget-title,.smsalertform-widget-description #sa-subscribe-form' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                
                'condition' => [
                    'custom_title_description' => 'yes',
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
                    '{{WRAPPER}} .smsalertform-widget-title,.smsalertform-widget-description #sa-subscribe-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_description',
            [
                'label'     => __('Description', 'sms-alert'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'custom_title_description' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'heading_description_text_color',
            [
                'label'     => __('Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .smsalertform-widget-description,.smsalertform-widget-description #sa-subscribe-form' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_title_description' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'heading_description_typography',
                'label'     => __('Typography', 'sms-alert'),
                'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
                'selector'  => '{{WRAPPER}} .smsalertform-widget-description,.smsalertform-widget-description #sa-subscribe-form',
                'condition' => [
                    'custom_title_description' => 'yes',
                ],
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
                    '{{WRAPPER}} .smsalertform-widget-description,.smsalertform-widget-description #sa-subscribe-form' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_title_description' => 'yes',
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
                    '{{WRAPPER}} .smsalertform-widget-description,.smsalertform-widget-description #sa-subscribe-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .smsalertform-widget-wrapper',
            ]
        );

        $this->add_control(
            'form_container_link_color',
            [
                'label'     => __('Link Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_container_max_width',
            [
                'label'      => esc_html__('Max Width', 'sms-alert'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 1500,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 80,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .smsalertform-widget-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_container_alignment',
            [
                'label'       => esc_html__('Alignment', 'sms-alert'),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options'     => [
                    'default' => [
                        'title' => __('Default', 'sms-alert'),
                        'icon'  => 'fa fa-ban',
                    ],
                    'left' => [
                        'title' => esc_html__('Left', 'sms-alert'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'sms-alert'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'sms-alert'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'default',
            ]
        );

        $this->add_responsive_control(
            'form_container_margin',
            [
                'label'      => esc_html__('Margin', 'sms-alert'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .smsalertform-widget-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_container_padding',
            [
                'label'      => esc_html__('Padding', 'sms-alert'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .smsalertform-widget-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'form_container_border',
                'selector' => '{{WRAPPER}} .smsalertform-widget-wrapper',
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'form_container_box_shadow',
                'selector' => '{{WRAPPER}} .smsalertform-widget-wrapper',
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
                'label' => __('Labels', 'sms-alert'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'form_label_text_color',
            [
                'label'     => __('Text Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .smsalertform-widget-wrapper .sa-lwo-form label,.smsalertform-widget-wrapper .sa_subscriber' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'form_label_typography',
                'label'    => __('Typography', 'sms-alert'),
                'selector' => '{{WRAPPER}} .smsalertform-widget-wrapper .sa-lwo-form label,.smsalertform-widget-wrapper .sa_subscriber',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register input textarea style controls function.     
     *
     * @return array
     */
    protected function registerInputTextareaStyleControls()
    {
        $this->start_controls_section(
            'section_form_fields_style',
            [
                'label' => __('Input & Textarea', 'sms-alert'),
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input textarea, {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input select' => 'text-align: {{VALUE}};',
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):not(.select2-search__field), {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input textarea, {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input select, {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input .select2-container--default .select2-selection--multiple' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input textarea, {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input select' => 'color: {{VALUE}}',
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
                'selector'    => '{{WRAPPER}} .smsalertform-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):not(.select2-search__field), {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input textarea, {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input select,  {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input .select2-container--default .select2-selection--multiple',
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input textarea, {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input select,  {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input .select2-container--default .select2-selection--multiple' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input textarea, {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input select' => 'text-indent: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input select' => 'width: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input select' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_textarea_width',
            [
                'label' => __('Textarea Width', 'sms-alert'),
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input textarea' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_textarea_height',
            [
                'label' => __('Textarea Height', 'sms-alert'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 400,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input textarea' => 'height: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input textarea, {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'form_field_typography',
                'label'     => __('Typography', 'sms-alert'),
                'selector'  => '{{WRAPPER}} .smsalertform-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input textarea, {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input select',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'form_field_box_shadow',
                'selector'  => '{{WRAPPER}} .smsalertform-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input textarea, {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input select',
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input textarea:focus' => 'background-color: {{VALUE}}',
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
                'selector'    => '{{WRAPPER}} .smsalertform-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input textarea:focus',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'form_input_focus_box_shadow',
                'selector'  => '{{WRAPPER}} .smsalertform-widget-wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .smsalertform-widget-wrapper .sa-el-group,.smsalertform-widget-wrapper .sa_input textarea:focus',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }
    
    /**
     * Register placeholder style controls function.     
     *
     * @return array
     */
    protected function registerPlaceholderStyleControls()
    {
        $this->start_controls_section(
            'section_placeholder_style',
            [
                'label'     => __('Placeholder', 'sms-alert'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'placeholder_switch' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'form_placeholder_text_color',
            [
                'label'     => __('Text Color', 'sms-alert'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .smsalertform-widget-wrapper #smsalert_share_cart,.smsalertform-widget-wrapper .sa-lwo-form.sa-lwo-form,.smsalertform-widget-wrapper .sa_input input::-webkit-input-placeholder, {{WRAPPER}} .smsalertform-widget-wrapper #smsalert_share_cart,.smsalertform-widget-wrapper .sa-lwo-form,.smsalertform-widget-wrapper .sa_input textarea::-webkit-input-placeholder' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'placeholder_switch' => 'yes',
                ],
            ]
        );

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
                'label' => __('Submit Button', 'sms-alert'),
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
                'prefix_class' => 'smsalertform-widget-submit-button-',
                'condition'    => [
                    'form_submit_button_width_type' => 'custom',
                ],
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
                'prefix_class' => 'smsalertform-widget-submit-button-,smsalertform-widget-submit-button- #sa_subscribe',
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper #smsalert_share_cart,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_reg_with_otp_btn,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_login_with_otp_btn ,.smsalertform-widget-wrapper #sa_subscribe' => 'width: {{SIZE}}{{UNIT}}', ],
                'condition' => [
                    'form_submit_button_width_type' => 'custom',
                ],
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper #smsalert_share_cart,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_reg_with_otp_btn,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_login_with_otp_btn,.smsalertform-widget-wrapper #sa_subscribe' => 'background-color: {{VALUE}} !important;',
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper #smsalert_share_cart,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_reg_with_otp_btn,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_login_with_otp_btn,.smsalertform-widget-wrapper #sa_subscribe' => 'color: {{VALUE}} !important;',
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
                'selector'    => '{{WRAPPER}} .smsalertform-widget-wrapper #smsalert_share_cart,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_reg_with_otp_btn,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_login_with_otp_btn,.smsalertform-widget-wrapper #sa_subscribe',
            ]
        );

        $this->add_control(
            'form_submit_button_border_radius',
            [
                'label'      => __('Border Radius', 'sms-alert'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .smsalertform-widget-wrapper .sa-lwo-form .smsalert_login_with_otp_btn,.smsalertform-widget-wrapper #sa_subscribe' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper #smsalert_share_cart,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_reg_with_otp_btn,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_login_with_otp_btn,.smsalertform-widget-wrapper #sa_subscribe' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper #smsalert_share_cart,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_reg_with_otp_btn,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_login_with_otp_btn,.smsalertform-widget-wrapper #sa_subscribe' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'form_submit_button_typography',
                'label'     => __('Typography', 'sms-alert'),
                'selector'  => '{{WRAPPER}} .smsalertform-widget-wrapper #smsalert_share_cart,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_reg_with_otp_btn,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_login_with_otp_btn,.smsalertform-widget-wrapper #sa_subscribe',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'form_submit_button_box_shadow',
                'selector'  => '{{WRAPPER}} .smsalertform-widget-wrapper #smsalert_share_cart,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_reg_with_otp_btn,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_login_with_otp_btn,.smsalertform-widget-wrapper #sa_subscribe',
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper #smsalert_share_cart,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_reg_with_otp_btn,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_login_with_otp_btn,.smsalertform-widget-wrapper #sa_subscribe:hover' => 'background-color: {{VALUE}} !important;',
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper #smsalert_share_cart,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_reg_with_otp_btn,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_login_with_otp_btn,.smsalertform-widget-wrapper #sa_subscribe:hover' => 'color: {{VALUE}} !important;',
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
                    '{{WRAPPER}} .smsalertform-widget-wrapper #smsalert_share_cart,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_reg_with_otp_btn,.smsalertform-widget-wrapper .sa-lwo-form .smsalert_login_with_otp_btn,.smsalertform-widget-wrapper #sa_subscribe:hover' => 'border-color: {{VALUE}}',
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
            'smsalertform_widget_wrapper',
            [
                'class' => [
                    'smsalertform-widget-wrapper',
                ],
            ]
        );

        if ('yes' != $placeholder_switch) {
            $this->add_render_attribute('smsalertform_widget_wrapper', 'class', 'hide-placeholder');
        }
        
        if ('yes' != $labels_switch) {
            $this->add_render_attribute('smsalertform_widget_wrapper', 'class', 'hide-smsalert-form-labels');
            
        }
        if ($form_container_alignment) {
            $this->add_render_attribute('smsalertform_widget_wrapper', 'class', 'smsalertform-widget-align-' . $form_container_alignment . '');
        }
        if (!empty($form_list)) { ?>

            <div <?php echo wp_kses_post($this->get_render_attribute_string('smsalertform_widget_wrapper')); ?>>

            <?php if ('yes' == $custom_title_description) { ?>
                <div class="smsalertform-widget-heading">
                    <?php if ('' != $form_title_custom) { ?>
                    <h3 class="smsalertform-widget-title">
                        <?php echo esc_attr($form_title_custom); ?>
                    </h3>
                    <?php } ?>
                    <?php if ('' != $form_description_custom) { ?>
                    <p class="smsalertform-widget-description">
                        <?php echo wp_kses_post($this->parse_text_editor($form_description_custom)); ?>
                    </p>
                    <?php } ?>
                </div>                
            <?php } ?> 

            <?php 
            $values = $form_list;            
            switch ($values) {
            case 'sa_signup':
                echo do_shortcode("[sa_signupwithmobile sa_label='".$sa_ele_f_mobile_lbl."' sa_placeholder = '".$sa_ele_f_mobile_place."' sa_button = '".$sa_ele_f_mobile_botton."' redirect_url = '".$sa_ele_f_redirect_url."']");
                break; 
            case 'sa_login':
                echo do_shortcode("[sa_loginwithotp sa_label='".$sa_ele_f_mobile_lbl."' sa_placeholder = '".$sa_ele_f_mobile_place."' sa_button = '".$sa_ele_f_mobile_botton."' redirect_url = '".$sa_ele_f_redirect_url."']");
                break; 
            case 'sa_subscription':
                echo do_shortcode("[sa_subscribe group_name = '".$sa_ele_f_group."' sa_name='".$sa_ele_f_mobile_lbl."' sa_placeholder = '".$sa_ele_f_mobile_place."' sa_mobile = '".$sa_ele_f_user_mobile."' sa_mobile_placeholder = '".$sa_ele_f_phone_placeholder."'  sa_button = '".$sa_ele_f_mobile_botton."']");
                break;
            case 'sa_sharecart':
                echo do_shortcode("[sa_sharecart empty_msg='Your cart is empty!</br><small>Please add product into cart to see the preview and select share cart from widget dropdown</small>']");
                break;     
            }
        }
    }    
     /**
      * Content template function.     
      *
      * @return array
      */
    protected function content_template()
    {
    }
}