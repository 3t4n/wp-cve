<?php

/**
 * Member widget class
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Group_Control_Text_Shadow;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Skt_Addons_Elementor\Elementor\Controls\Select2;

defined('ABSPATH') || die();

class Mailchimp extends Base {

    private $settings;

    /**
     * Get widget title.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('MailChimp', 'skt-addons-elementor');
    }

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

        $this->settings = skt_addons_elementor_get_credentials('mailchimp');
	}

    /**
     * Get widget icon.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'skti skti-mail-chimp';
    }

    public function get_keywords() {
        return ['email', 'mail chimp', 'mail', 'subscription'];
    }

	/**
     * Register widget content controls
     */
    protected function register_content_controls() {
		$this->__mailchimp_content_controls();
		$this->__mailchimp_form_content_controls();
		$this->__success_error_content_controls();
	}

    protected function __mailchimp_content_controls() {

        $this->start_controls_section(
            '_section_mailchimp',
            [
                'label' => __('MailChimp', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'mailchimp_api_check',
            [
                'raw' => '<strong>' . esc_html__('Please note!', 'skt-addons-elementor') . '</strong> ' . esc_html__('Please set API Key in SKT Addons Dashboard - ', 'skt-addons-elementor') . '<a style="border-bottom-color: inherit;" href="'. esc_url(admin_url('admin.php?page=skt-addons#credentials')) . '" target="_blank" >'. esc_html__('Credentials', 'skt-addons-elementor') .'</a>' . esc_html__(' - MailChimp and Create Audience.', 'skt-addons-elementor'),
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'render_type' => 'ui',
                'condition' => [
                    'mailchimp_api_choose' => 'global',
                ],
            ]
        );

        /*
        * Need to solve api get issue from controller to controller
        */

        $this->add_control(
            'mailchimp_api_choose',
            [
                'label' => __('Choose API from', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'global',
                'options' =>  [
                    'global' => __('Global', 'skt-addons-elementor'),
                    'custom' => __('Custom', 'skt-addons-elementor'),
                ],
            ]
        );

        $this->add_control(
            'mailchimp_api',
            [
                'label' => __('MailChimp API', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __('Enter your mailchimp api here', 'skt-addons-elementor'),
                'condition' => [
                    'mailchimp_api_choose' => 'custom',
                ],
                'dynamic' => [ 'active' => true]
            ]
        );

        $this->add_control(
			'mailchimp_lists',
			[
				'label' => __( 'Audience', 'skt-addons-elementor' ),
				'label_block' => true,
				'type' => Select2::TYPE,
				'multiple' => false,
				'placeholder' => 'Choose your created audience ',
				'dynamic_params' => [
					'object_type' => 'mailchimp_list',
					'global_api'   => isset($this->settings['api'])? $this->settings['api']: '',
					'control_dependency' => [
						'mailchimp_api_choose' => 'mailchimp_api_choose',
						'mailchimp_api' => 'mailchimp_api',
					]
				],
				'select2options' => [
					'minimumInputLength' => 0,
				],
                'description' => esc_html__('Create a audience/ list in mailchimp account ', 'skt-addons-elementor') . '<a href="https://mailchimp.com/help/create-audience/" target="_blank"> ' . esc_html__('Create Audience', 'skt-addons-elementor') . '</a>',
			]
		);

        $this->add_control(
            'mailchimp_list_tags',
            [
                'label' => __('Tags', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __('Tag-1, Tag-2', 'skt-addons-elementor'),
                'description' => __('Enter tag here to separate your subscribers. Use comma separator to use multiple tags. Example: Tag-1, Tag-2, Tag-3', 'skt-addons-elementor'),
                'condition' => [
                    'mailchimp_lists!' => '',
                ],
                'dynamic' => [ 'active' => true]
            ]
        );

        $this->end_controls_section();
	}

    protected function __mailchimp_form_content_controls() {

        $this->start_controls_section(
            '_section_mailchimp_form',
            [
                'label' => __('Form', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'form_alignment',
            [
                'label' => __('Form Alignment', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => esc_html__('Horizontal', 'skt-addons-elementor'),
                    'vertical' => esc_html__('Vertical', 'skt-addons-elementor'),
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'enable_name',
            [
                'label' => __('Enable Name?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'skt-addons-elementor'),
                'label_off' => __('No', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            '_fname_heading',
            [
                'label' => __('First Name:', 'skt-addons-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'enable_name' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'fname_label',
            [
                'label' => __('Label', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('First Name input label', 'skt-addons-elementor'),
                'condition' => [
                    'enable_name' => 'yes',
                ],
                'dynamic' => ['active' => true]
            ]
        );

        $this->add_control(
            'fname_placeholder',
            [
                'label' => __('Placeholder', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('First Name', 'skt-addons-elementor'),
                'placeholder' => __('First Name input placeholder', 'skt-addons-elementor'),
                'condition' => [
                    'enable_name' => 'yes',
                ],
                'dynamic' => ['active' => true]
            ]
        );

        $this->add_control(
            'fname_enable_icon',
            [
                'label' => __('Enable Icon With Input?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'skt-addons-elementor'),
                'label_off' => __('No', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'enable_name' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'fname_icon',
            [
                'label' => __('Icon', 'skt-addons-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'skti skti-user',
                    'library' => 'regular',
                ],
                'condition' => [
                    'enable_name' => 'yes',
                    'fname_enable_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'fname_icon_position',
            [
                'label' => __('Icon Position', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before'  => __('Before Input', 'skt-addons-elementor'),
                    'after' => __('After Input', 'skt-addons-elementor'),
                ],
                'condition' => [
                    'enable_name' => 'yes',
                    'fname_enable_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_lname_heading',
            [
                'label' => __('Last Name:', 'skt-addons-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'enable_name' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'lname_label',
            [
                'label' => __('Label', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Last Name input label', 'skt-addons-elementor'),
                'condition' => [
                    'enable_name' => 'yes',
                ],
                'dynamic' => ['active' => true]
            ]
        );

        $this->add_control(
            'lname_placeholder',
            [
                'label' => __('Placeholder', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Last Name', 'skt-addons-elementor'),
                'placeholder' => __('Last Name input placeholder', 'skt-addons-elementor'),
                'condition' => [
                    'enable_name' => 'yes',
                ],
                'dynamic' => ['active' => true]
            ]
        );

        $this->add_control(
            'lname_enable_icon',
            [
                'label' => __('Enable Icon With Input?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'skt-addons-elementor'),
                'label_off' => __('No', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'enable_name' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'lname_icon',
            [
                'label' => __('Icon', 'skt-addons-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'skti skti-user',
                    'library' => 'regular',
                ],
                'condition' => [
                    'enable_name' => 'yes',
                    'lname_enable_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'lname_icon_position',
            [
                'label' => __('Icon Position', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before'  => __('Before Input', 'skt-addons-elementor'),
                    'after' => __('After Input', 'skt-addons-elementor'),
                ],
                'condition' => [
                    'enable_name' => 'yes',
                    'lname_enable_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'enable_phone',
            [
                'label' => __('Enable Phone?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'skt-addons-elementor'),
                'label_off' => __('No', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            '_phone_heading',
            [
                'label' => __('Phone:', 'skt-addons-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'enable_phone' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'phone_label',
            [
                'label' => __('Label', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Phone input label', 'skt-addons-elementor'),
                'condition' => [
                    'enable_phone' => 'yes',
                ],
                'dynamic' => ['active' => true]
            ]
        );

        $this->add_control(
            'phone_placeholder',
            [
                'label' => __('Placeholder', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Phone', 'skt-addons-elementor'),
                'placeholder' => __('Phone input placeholder', 'skt-addons-elementor'),
                'condition' => [
                    'enable_phone' => 'yes',
                ],
                'dynamic' => ['active' => true]
            ]
        );

        $this->add_control(
            'phone_enable_icon',
            [
                'label' => __('Enable Icon With Input?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'skt-addons-elementor'),
                'label_off' => __('No', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'enable_phone' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'phone_icon',
            [
                'label' => __('Icon', 'skt-addons-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'skti skti-phone',
                    'library' => 'regular',
                ],
                'condition' => [
                    'enable_phone' => 'yes',
                    'phone_enable_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'phone_icon_position',
            [
                'label' => __('Icon Position', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before'  => __('Before Input', 'skt-addons-elementor'),
                    'after' => __('After Input', 'skt-addons-elementor'),
                ],
                'condition' => [
                    'enable_phone' => 'yes',
                    'phone_enable_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_email_heading',
            [
                'label' => __('Email:', 'skt-addons-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'email_label',
            [
                'label' => __('Label', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Email input label', 'skt-addons-elementor'),
                'dynamic' => ['active' => true]
            ]
        );

        $this->add_control(
            'email_placeholder',
            [
                'label' => __('Placeholder', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Email', 'skt-addons-elementor'),
                'placeholder' => __('Email input placeholder', 'skt-addons-elementor'),
                'dynamic' => ['active' => true]
            ]
        );

        $this->add_control(
            'email_enable_icon',
            [
                'label' => __('Enable Icon With Input?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'skt-addons-elementor'),
                'label_off' => __('No', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'email_icon',
            [
                'label' => __('Icon', 'skt-addons-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'skti skti-envelop',
                    'library' => 'regular',
                ],
                'condition' => [
                    'email_enable_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'email_icon_position',
            [
                'label' => __('Icon Position', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before'  => __('Before Input', 'skt-addons-elementor'),
                    'after' => __('After Input', 'skt-addons-elementor'),
                ],
                'condition' => [
                    'email_enable_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_button_heading',
            [
                'label' => __('Button:', 'skt-addons-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Text', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Subscribe', 'skt-addons-elementor'),
                'dynamic' => ['active' => true]
            ]
        );

        $this->add_control(
            'button_enable_icon',
            [
                'label' => __('Enable Icon With Button?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'skt-addons-elementor'),
                'label_off' => __('No', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label' => __('Icon', 'skt-addons-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'skti skti-tick',
                    'library' => 'regular',
                ],
                'condition' => [
                    'button_enable_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'button_icon_position',
            [
                'label' => __('Icon Position', 'skt-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before'  => __('Before Input', 'skt-addons-elementor'),
                    'after' => __('After Input', 'skt-addons-elementor'),
                ],
                'condition' => [
                    'button_enable_icon' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
	}

    protected function __success_error_content_controls() {

        $this->start_controls_section(
            '_section_success_error_label',
            [
                'label' => esc_html__('Success & Error', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'mailchimp_success_message',
            [
                'label' => __('Success Message', 'skt-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __('Your data inserted on Mailchimp.', 'skt-addons-elementor'),
                'placeholder' => __('Type your success message here', 'skt-addons-elementor'),
                'dynamic' => ['active' => true]
            ]
        );

        $this->add_control(
            'mailchimp_success_message_show_in_editor',
            [
                'label' => __('Success Message Show in Editor?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'skt-addons-elementor'),
                'label_off' => __('No', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'mailchimp_error_message_show_in_editor',
            [
                'label' => __('Error Message Show in Editor?', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'skt-addons-elementor'),
                'label_off' => __('No', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register styles related controls
     */
    protected function register_style_controls() {
		$this->__mailchimp_label_style_controls();
		$this->__input_style_controls();
		$this->__input_icon_style_controls();
		$this->__button_style_controls();
		$this->__success_error_style_controls();
	}

    protected function __mailchimp_label_style_controls() {

        $this->start_controls_section(
            '_section_style_mailchimp_label',
            [
                'label' => esc_html__('Label', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'input_label_typography',
                'label' => esc_html__('Typography', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-mc-input-label',
            ]
        );

        $this->add_control(
            'input_label_color',
            [
                'label' => esc_html__('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-input-label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_label_margin',
            [
                'label' => esc_html__('Margin', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-input-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
	}

    protected function __input_style_controls() {

        // input style
        $this->start_controls_section(
            'input_style',
            [
                'label' => esc_html__('Input', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'space_between_input',
            [
                'label' => __('Space Between Input (px)', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'tablet_default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 5,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-mailchimp-form.vertical .skt-mc-input-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-mailchimp-form.horizontal .skt-mc-input-wrapper' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'input_style_inputted_value_color',
            [
                'label' => esc_html__('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-input input' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'input_typography',
                'label' => esc_html__('Typography', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-mc-input input',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'input_style_background',
                'label' => esc_html__('Background', 'skt-addons-elementor'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .skt-mc-input input',
                'exclude' => [
                    'image'
                ]
            ]
        );

        $this->add_responsive_control(
            'input_style_radius',
            [
                'label' => esc_html__('Border Radius', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-input input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'input_style_border',
                'label' => esc_html__('Border', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-mc-input input',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'input_style_box_shadow',
                'label' => esc_html__('Box Shadow', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-mc-input input, {{WRAPPER}} .skt-mc-input input:focus',
            ]
        );

        $this->add_responsive_control(
            'input_style_padding',
            [
                'label' => esc_html__('Padding', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'        => [
                    'top'        => 0,
                    'right'        => 20,
                    'bottom'    => 0,
                    'left'        => 20
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-input input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'input_style_width__switch',
            [
                'label' => esc_html__('Use Width', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'skt-addons-elementor'),
                'label_off' => esc_html__('Hide', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_responsive_control(
            'input_style_width',
            [
                'label' => esc_html__('Width', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'default'    => [
                    'unit'    => '%',
                    'size'    => 66
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-input-wrapper' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
                ],
                'condition'    => [
                    'input_style_width__switch' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'input_style_margin_bottom',
            [
                'label' => esc_html__('Margin Bottom', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-input-wrapper:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'form_style_switcher!' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'input_style_margin_right',
            [
                'label' => esc_html__('Margin Right', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px',],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-mailchimp-form.horizontal .skt-mc-input-wrapper:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'form_style_switcher' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'inline_margin_bottom',
            [
                'label'        => esc_html__('Margin Bottom', 'skt-addons-elementor'),
                'type'        => Controls_Manager::SLIDER,
                'devices'    => ['mobile'],
                'selectors' => [
                    '{{WRAPPER}} .multiple_form_fields > .skt-mc-input-wrapper:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'form_style_switcher' => 'yes', // Inline Style
                    'section_form_name_show' => 'yes', // Show Names
                ]
            ]
        );

        $this->add_control(
            'input_style_placeholder_heading',
            [
                'label' => esc_html__('Placeholder', 'skt-addons-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'input_style_placeholder_color',
            [
                'label' => esc_html__('Placeholder Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-input input::-webkit-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-mc-input input::-moz-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-mc-input input:-ms-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-mc-input input:-moz-placeholder' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_style_placeholder_font_size',
            [
                'label' => esc_html__('Font Size', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-input input::-webkit-input-placeholder' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-mc-input input::-moz-placeholder' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-mc-input input:-ms-input-placeholder' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-mc-input input:-moz-placeholder' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
	}

    protected function __input_icon_style_controls() {

        $this->start_controls_section(
            'input_icon_style_holder',
            [
                'label' => esc_html__('Input Icon', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'input_icon_background',
                'label' => esc_html__('Background', 'skt-addons-elementor'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .skt-mc-input .skt-mc-icon-wrapper',
                'exclude' => [
                    'image'
                ]
            ]
        );

        $this->add_control(
            'input_icon_color_hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'input_icon_color',
            [
                'label' => esc_html__('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-input .skt-mc-icon-wrapper i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .skt-mc-input .skt-mc-icon-wrapper svg path'    => 'stroke: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_icon_font_size',
            [
                'label' => esc_html__('Font Size', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-input .skt-mc-icon-wrapper' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-mc-input .skt-mc-icon-wrapper svg'    => 'max-width: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}};',
                    // '{{WRAPPER}} .skt-mc-input .skt-mc-icon-wrapper svg'    => 'height: 1em, width: auto;',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'input_icon_border',
                'label' => esc_html__('Border', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-mc-input .skt-mc-icon-wrapper',
            ]
        );

        $this->add_responsive_control(
            'input_icon_padding',
            [
                'label' => esc_html__('Padding', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-input .skt-mc-icon-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_icon_border_radius',
            [
                'label' => esc_html__('Border Radius', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-input .skt-mc-icon-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;;',
                ],
            ]
        );

        $this->end_controls_section();
	}

    protected function __button_style_controls() {

        $this->start_controls_section(
            'button_style_holder',
            [
                'label' => esc_html__('Button', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => esc_html__('Typography', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-mc-button',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => [
                    'top'       => 8,
                    'right'     => 20,
                    'bottom'    => 8,
                    'left'      => 20,
                    'unit'      => 'px',
                    'isLinked'  => ''
                ],
                'tablet_default' => [
                    'top'       => 8,
                    'right'     => 15,
                    'bottom'    => 8,
                    'left'      => 15,
                    'unit'      => 'px',
                    'isLinked'  => ''
                ],
                'mobile_default' => [
                    'top'       => 8,
                    'right'     => 10,
                    'bottom'    => 8,
                    'left'      => 10,
                    'unit'      => 'px',
                    'isLinked'  => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'label' => esc_html__('Box Shadow', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-mc-button',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'label' => esc_html__('Border', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-mc-button',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'button_title_shadow',
                'selector' => '{{WRAPPER}} .skt-mc-button',
            ]
        );

        $this->add_control(
            'button_style_use_width_height',
            [
                'label' => esc_html__('Use Width', 'skt-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'skt-addons-elementor'),
                'label_off' => esc_html__('Hide', 'skt-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_responsive_control(
            'button_width',
            [
                'label' => esc_html__('Width', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-button-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'button_style_use_width_height' => 'yes'
                ]
            ]
        );


        $this->add_responsive_control(
            'button_style_margin',
            [
                'label' => esc_html__('Margin', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'button_normal_and_hover_tabs'
        );
        $this->start_controls_tab(
            'button_normal_tab',
            [
                'label' => esc_html__('Normal', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => esc_html__('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-button' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .skt-mc-button svg path'    => 'stroke: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'button_background',
                'label' => esc_html__('Background', 'skt-addons-elementor'),
                'types' => ['classic', 'gradient',],
                'selector' => '{{WRAPPER}} .skt-mc-button',
                'exclude' => [
                    'image'
                ]
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'button_hover_tab',
            [
                'label' => esc_html__('Hover', 'skt-addons-elementor'),
            ]
        );

        $this->add_control(
            'button_color_hover',
            [
                'label' => esc_html__('Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-button:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .skt-mc-button:hover svg path'    => 'stroke: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'button_background_hover',
                'label' => esc_html__('Background', 'skt-addons-elementor'),
                'types' => ['classic', 'gradient',],
                'selector' => '{{WRAPPER}} .skt-mc-button:hover',
                'exclude' => [
                    'image'
                ]
            ]
        );

        $this->add_control(
            'button_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'skt-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-button:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'button_border_border!' => ''
                ]
            ]
        );



        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'button_icon_heading',
            [
                'label' => esc_html__('Icon', 'skt-addons-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'button_icon_padding_right',
            [
                'label' => esc_html__('Icon Spacing', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-button .skt-mc-icon-wrapper > i, {{WRAPPER}} .skt-mc-button .skt-mc-icon-wrapper > svg' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'button_icon_position' => 'before'
                ]
            ]
        );

        $this->add_responsive_control(
            'button_icon_padding_left',
            [
                'label' => esc_html__('Icon Spacing', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-button .skt-mc-icon-wrapper > i, {{WRAPPER}} .skt-mc-button .skt-mc-icon-wrapper > svg' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'button_icon_position' => 'after'
                ]
            ]
        );

        $this->add_responsive_control(
            'button_icon_size',
            [
                'label' => esc_html__('Icon Size', 'skt-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    //     '{{WRAPPER}} .skt-mc-button .skt-mc-icon-wrapper > i, {{WRAPPER}} .skt-mc-button .skt-mc-icon-wrapper > i' => 'font-size: {{SIZE}}{{UNIT}};',
                    //     '{{WRAPPER}} .skt-mc-button .skt-mc-icon-wrapper > i, {{WRAPPER}} .skt-mc-button .skt-mc-icon-wrapper > svg' => 'max-width: {{SIZE}}{{UNIT}}; height: auto',
                    '{{WRAPPER}} .skt-mc-button .skt-mc-icon-wrapper' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-mc-button .skt-mc-icon-wrapper svg' => 'max-width: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();
	}

    protected function __success_error_style_controls() {

        $this->start_controls_section(
            'success_error',
            [
                'label' => esc_html__('Success & Error Message', 'skt-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'success_error_padding',
            [
                'label' => esc_html__('Padding', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-response-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'success_error_margin',
            [
                'label' => esc_html__('Margin', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-response-message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'success_error_border_radius',
            [
                'label' => esc_html__('Border Radius', 'skt-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .skt-mc-response-message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'         => 'success_error_typography',
                'selector'     => '{{WRAPPER}} .skt-mc-response-message',
            ]
        );

        $this->add_control(
            'success_heading',
            [
                'label' => esc_html__('Success:', 'skt-addons-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'success_color',
            [
                'label'         => esc_html__('Color', 'skt-addons-elementor'),
                'type'         => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .skt-mc-response-message.success' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'success_bg_color',
                'label'         => esc_html__('Background Color', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-mc-response-message.success',
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'success_border',
                'label' => esc_html__('Border', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-mc-response-message.success',
            ]
        );

        $this->add_control(
            'error_heading',
            [
                'label' => esc_html__('Error:', 'skt-addons-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'error_color',
            [
                'label'         => esc_html__('Color', 'skt-addons-elementor'),
                'type'         => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .skt-mc-response-message.error' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'error_bg_color',
                'label'         => esc_html__('Background Color', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-mc-response-message.error',
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'error_border',
                'label' => esc_html__('Border', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-mc-response-message.error',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        $form_fields = (($settings['enable_name'] == 'yes' || $settings['enable_phone'] == 'yes') ? 'multiple_form_fields' : '');
        $list_id = ((is_array($settings['mailchimp_lists']))? (isset($settings['mailchimp_lists'][0])? ltrim($settings['mailchimp_lists'][0]): ''): (ltrim($settings['mailchimp_lists'])));

?>
        <div class="skt-mailchimp-wrapper" data-post-id="<?php echo esc_attr(get_the_id()); ?>" data-widget-id="<?php echo esc_attr($this->get_id()); ?>">
            <?php if (\Elementor\Plugin::$instance->editor->is_edit_mode() && $settings['mailchimp_success_message_show_in_editor'] == 'yes') : ?>
                <div class="skt-mc-response-message success"><?php esc_html_e('This is a dummy message for success. This won\'t show in preview', 'skt-addons-elementor'); ?></div>
            <?php endif; ?>
            <?php if (\Elementor\Plugin::$instance->editor->is_edit_mode() && $settings['mailchimp_error_message_show_in_editor'] == 'yes') : ?>
                <div class="skt-mc-response-message error"><?php esc_html_e('This is a dummy message for error. This won\'t show in preview', 'skt-addons-elementor'); ?></div>
            <?php endif; ?>
            <div class="skt-mc-response-message"></div>
            <form class="skt-mailchimp-form <?php echo esc_attr($settings['form_alignment']); ?> <?php echo esc_attr($form_fields); ?>" data-list-id="<?php echo esc_attr($list_id); ?>" data-success-message="<?php echo esc_attr($settings['mailchimp_success_message']); ?>">
                <?php if ($settings['enable_name'] == 'yes') : ?>
                    <div class="skt-mc-input-wrapper">
                        <?php if (!empty($settings['fname_label'])) : ?>
                            <label class="skt-mc-input-label"><?php echo esc_html($settings['fname_label']); ?></label>
                        <?php endif; ?>
                        <div class="skt-mc-input <?php echo esc_attr($settings['fname_icon_position']); ?>">
                            <?php if ($settings['fname_enable_icon'] == 'yes' && $settings['fname_icon_position'] == 'before') : ?>
                                <div class="skt-mc-icon-wrapper"><?php skt_addons_elementor_render_icon($settings, null, 'fname_icon'); ?></div>
                            <?php endif; ?>
                            <input type="text" name="fname" placeholder="<?php echo esc_attr($settings['fname_placeholder']); ?>">
                            <?php if ($settings['fname_enable_icon'] == 'yes' && $settings['fname_icon_position'] == 'after') : ?>
                                <div class="skt-mc-icon-wrapper"><?php skt_addons_elementor_render_icon($settings, null, 'fname_icon'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="skt-mc-input-wrapper">
                        <?php if (!empty($settings['lname_label'])) : ?>
                            <label class="skt-mc-input-label"><?php echo esc_html($settings['lname_label']); ?></label>
                        <?php endif; ?>
                        <div class="skt-mc-input <?php echo esc_attr($settings['lname_icon_position']); ?>">
                            <?php if ($settings['lname_enable_icon'] == 'yes' && $settings['lname_icon_position'] == 'before') : ?>
                                <div class="skt-mc-icon-wrapper"><?php skt_addons_elementor_render_icon($settings, null, 'lname_icon'); ?></div>
                            <?php endif; ?>
                            <input type="text" name="lname" placeholder="<?php echo esc_attr($settings['lname_placeholder']); ?>">
                            <?php if ($settings['lname_enable_icon'] == 'yes' && $settings['lname_icon_position'] == 'after') : ?>
                                <div class="skt-mc-icon-wrapper"><?php skt_addons_elementor_render_icon($settings, null, 'lname_icon'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($settings['enable_phone'] == 'yes') : ?>
                    <div class="skt-mc-input-wrapper">
                        <?php if (!empty($settings['phone_label'])) : ?>
                            <label class="skt-mc-input-label"><?php echo esc_html($settings['phone_label']); ?></label>
                        <?php endif; ?>
                        <div class="skt-mc-input <?php echo esc_attr($settings['phone_icon_position']); ?>">
                            <?php if ($settings['phone_enable_icon'] == 'yes' && $settings['phone_icon_position'] == 'before') : ?>
                                <div class="skt-mc-icon-wrapper"><?php skt_addons_elementor_render_icon($settings, null, 'phone_icon'); ?></div>
                            <?php endif; ?>
                            <input type="text" name="phone" placeholder="<?php echo esc_attr($settings['phone_placeholder']); ?>">
                            <?php if ($settings['phone_enable_icon'] == 'yes' && $settings['phone_icon_position'] == 'after') : ?>
                                <div class="skt-mc-icon-wrapper"><?php skt_addons_elementor_render_icon($settings, null, 'phone_icon'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="skt-mc-input-wrapper">
                    <?php if (!empty($settings['email_label'])) : ?>
                        <label class="skt-mc-input-label"><?php echo esc_html($settings['email_label']); ?></label>
                    <?php endif; ?>
                    <div class="skt-mc-input <?php echo esc_attr($settings['email_icon_position']); ?>">
                        <?php if ($settings['email_enable_icon'] == 'yes' && $settings['email_icon_position'] == 'before') : ?>
                            <div class="skt-mc-icon-wrapper"><?php skt_addons_elementor_render_icon($settings, null, 'email_icon'); ?></div>
                        <?php endif; ?>
                        <input type="email" name="email" placeholder="<?php echo esc_attr($settings['email_placeholder']); ?>" required>
                        <?php if ($settings['email_enable_icon'] == 'yes' && $settings['email_icon_position'] == 'after') : ?>
                            <div class="skt-mc-icon-wrapper"><?php skt_addons_elementor_render_icon($settings, null, 'email_icon'); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="skt-mc-button-wrapper">

                    <button type="submit" class="skt-mc-button" name="skt-mailchimp">
                        <?php if ($settings['button_enable_icon'] == 'yes' && $settings['button_icon_position'] == 'before') : ?>
                            <div class="skt-mc-icon-wrapper"><?php skt_addons_elementor_render_icon($settings, null, 'button_icon'); ?></div>
                        <?php endif; ?>
                        <?php echo esc_attr($settings['button_text']); ?>
                        <?php if ($settings['button_enable_icon'] == 'yes' && $settings['button_icon_position'] == 'after') : ?>
                            <div class="skt-mc-icon-wrapper"><?php skt_addons_elementor_render_icon($settings, null, 'button_icon'); ?></div>
                        <?php endif; ?>
                    </button>
                </div>
            </form>
        </div>
<?php
    }
}