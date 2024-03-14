<?php

namespace ElementinvaderAddonsForElementor\Widgets;

use ElementinvaderAddonsForElementor\Core\Elementinvader_Base;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Typography;
use Elementor\Editor;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use ElementinvaderAddonsForElementor\Modules\Forms\Ajax_Handler;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class EliNewsletter extends Elementinvader_Base {

    // Default widget settings

    public $defaults = array();
    public $view_folder = 'form';
    public $inline_css = '';
    public $inline_css_tablet = '';
    public $inline_css_mobile = '';
    public $items_num = 0;

    public function __construct($data = array(), $args = null) {

        \Elementor\Controls_Manager::add_tab(
                'fields_tab',
                esc_html__('Fields', 'elementinvader-addons-for-elementor')
        );
        \Elementor\Controls_Manager::add_tab(
                'config_tab',
                esc_html__('Config', 'elementinvader-addons-for-elementor')
        );

        wp_enqueue_style('elementinvader_addons_for_elementor-main', plugins_url('/assets/css/main.css', ELEMENTINVADER_ADDONS_FOR_ELEMENTOR__FILE__));
        parent::__construct($data, $args);
    }

    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'eli-newsletter';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('Eli Newsletter', 'elementinvader-addons-for-elementor');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-mail';
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function register_controls() {
        $repeater = new Repeater();
        $field_types = [
            'text' => esc_html__('Short Text', 'elementinvader-addons-for-elementor'),
            'textarea' => esc_html__('Textarea', 'elementinvader-addons-for-elementor'),
            'url' => esc_html__('URL', 'elementinvader-addons-for-elementor'),
            'tel' => esc_html__('Tel', 'elementinvader-addons-for-elementor'),
            'password' => esc_html__('Password', 'elementinvader-addons-for-elementor'),
            'email' => esc_html__('Email', 'elementinvader-addons-for-elementor'),
            'radio' => esc_html__('Radio', 'elementinvader-addons-for-elementor'),
            'select' => esc_html__('Select', 'elementinvader-addons-for-elementor'),
            'checkbox' => esc_html__('Checkbox', 'elementinvader-addons-for-elementor'),
            //'range' => esc_html__( 'Range', 'elementinvader-addons-for-elementor' ),
            //'acceptance' => esc_html__( 'Acceptance', 'elementinvader-addons-for-elementor' ),
            'number' => esc_html__('Number', 'elementinvader-addons-for-elementor'),
            'date' => esc_html__('Date', 'elementinvader-addons-for-elementor'),
            'time' => esc_html__('Time', 'elementinvader-addons-for-elementor'),
            //'upload' => esc_html__( 'File Upload', 'elementinvader-addons-for-elementor' ),
            'html' => esc_html__('HTML Label', 'elementinvader-addons-for-elementor'),
            'hidden' => esc_html__('Hidden', 'elementinvader-addons-for-elementor'),
            'recaptcha' => esc_html__('recaptcha', 'elementinvader-addons-for-elementor'),
        ];

        /* START form field content */

        $repeater->start_controls_tabs('form_fields_tabs');

        $repeater->add_control(
                'field_type',
                [
                    'label' => esc_html__('Type of Field', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => $field_types,
                    'default' => 'text',
                ]
        );

        $repeater->add_control(
                'field_label',
                [
                    'label' => esc_html__('Field Name(label)', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                    'separator' => 'before',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'field_type',
                                'operator' => '!in',
                                'value' => [
                                    'recaptcha',
                                    'recaptcha_v3',
                                ],
                            ],
                        ],
                    ],
                ]
        );

        $repeater->add_control(
            'field_id',
            [
                'label' => esc_html__('Field ID', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'separator' => 'before',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'field_type',
                            'operator' => '!in',
                            'value' => [
                                'recaptcha',
                                'recaptcha_v3',
                            ],
                        ],
                    ],
                ],
            ]
        );

        $repeater->add_control(
                'placeholder',
                [
                    'label' => esc_html__('Placeholder', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__('Placeholder', 'elementinvader-addons-for-elementor'),
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'field_type',
                                'operator' => 'in',
                                'value' => [
                                    'tel',
                                    'text',
                                    'email',
                                    'textarea',
                                    'number',
                                    'url',
                                    'password',
                                ],
                            ],
                        ],
                    ],
                ]
        );

        $repeater->add_control(
                'field_value',
                [
                    'label' => esc_html__('Empty Value', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'field_type',
                                'operator' => 'in',
                                'value' => [
                                    'text',
                                    'email',
                                    'textarea',
                                    'url',
                                    'tel',
                                    'select',
                                    'number',
                                    'date',
                                    'time',
                                    'hidden',
                                ],
                            ],
                        ],
                    ],
                ]
        );

        $repeater->add_control(
                'required',
                [
                    'label' => esc_html__('Is Required', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'true',
                    'default' => '',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'field_type',
                                'operator' => '!in',
                                'value' => [
                                    'radio',
                                    'recaptcha',
                                    'recaptcha_v3',
                                    'hidden',
                                    'html',
                                ],
                            ],
                        ],
                    ],
                ]
        );

        $repeater->add_control(
                'field_options',
                [
                    'label' => esc_html__('Options', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXTAREA,
                    'default' => '',
                    'description' => esc_html__('Enter each option in a separate line. To differentiate between label and value, separate them with a pipe char ("|"). For example: First Name|f_name', 'elementinvader-addons-for-elementor'),
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'field_type',
                                'operator' => 'in',
                                'value' => [
                                    'select',
                                    'radio',
                                ],
                            ],
                        ],
                    ],
                ]
        );

        $repeater->add_control(
                'allow_multiple',
                [
                    'label' => esc_html__('Multiple Selection', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'true',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'field_type',
                                'value' => 'select',
                            ],
                        ],
                    ],
                ]
        );

        $repeater->add_control(
                'select_size',
                [
                    'label' => esc_html__('Rows', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 2,
                    'step' => 1,
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'field_type',
                                'value' => 'select',
                            ],
                            [
                                'name' => 'allow_multiple',
                                'value' => 'true',
                            ],
                        ],
                    ],
                ]
        );

        $repeater->add_control(
                'select_height',
                [
                    'label' => esc_html__('Height', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'render_type' => 'template',
                    'range' => [
                        'px' => [
                            'min' => 60,
                            'max' => 400,
                        ],
                    ],
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'field_type',
                                'value' => 'select',
                            ],
                            [
                                'name' => 'allow_multiple',
                                'value' => 'true',
                            ],
                        ],
                    ],
                ]
        );

        $repeater->add_control(
                'field_html',
                [
                    'label' => esc_html__('HTML', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::WYSIWYG,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'field_type',
                                'value' => 'html',
                            ],
                        ],
                    ],
                ]
        );

        $repeater->add_responsive_control(
                'width',
                [
                    'label' => esc_html__('Column Width', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__('Default', 'elementinvader-addons-for-elementor'),
                        'auto' => esc_html__('Auto', 'elementinvader-addons-for-elementor'),
                        '100' => '100%',
                        '80' => '80%',
                        '75' => '75%',
                        '66' => '66%',
                        '60' => '60%',
                        '50' => '50%',
                        '40' => '40%',
                        '33' => 'calc(100% / 3)',
                        '25' => '25%',
                        '20' => '20%',
                        'auto' => 'auto',
                        'auto_flexible' => 'auto flexible',
                    ],
                    'default' => '100',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'field_type',
                                'operator' => '!in',
                                'value' => [
                                    'hidden',
                                    'recaptcha',
                                    'recaptcha_v3',
                                ],
                            ],
                        ],
                    ],
                ]
        );

        $repeater->add_control(
                'rows',
                [
                    'label' => esc_html__('Rows', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 4,
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'field_type',
                                'value' => 'textarea',
                            ],
                        ],
                    ],
                ]
        );

        $repeater->add_control(
                'recaptcha_size', [
            'label' => esc_html__('Size', 'elementinvader-addons-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'normal',
            'options' => [
                'normal' => esc_html__('Normal', 'elementinvader-addons-for-elementor'),
                'compact' => esc_html__('Compact', 'elementinvader-addons-for-elementor'),
            ],
            'conditions' => [
                'terms' => [
                    [
                        'name' => 'field_type',
                        'value' => 'recaptcha',
                    ],
                ],
            ],
                ]
        );

        $repeater->add_control(
                'recaptcha_badge', [
            'label' => esc_html__('Badge', 'elementinvader-addons-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'bottomright',
            'options' => [
                'bottomright' => esc_html__('Bottom Right', 'elementinvader-addons-for-elementor'),
                'bottomleft' => esc_html__('Bottom Left', 'elementinvader-addons-for-elementor'),
                'inline' => esc_html__('Inline', 'elementinvader-addons-for-elementor'),
            ],
            'description' => esc_html__('To view the validation badge, switch to preview mode', 'elementinvader-addons-for-elementor'),
            'conditions' => [
                'terms' => [
                    [
                        'name' => 'field_type',
                        'value' => 'recaptcha_v3',
                    ],
                ],
            ],
                ]
        );

        $repeater->add_control(
                'css_classes',
                [
                    'label' => esc_html__('CSS Classes', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HIDDEN,
                    'default' => '',
                    'title' => esc_html__('Add your custom class WITHOUT the dot. e.g: my-class', 'elementinvader-addons-for-elementor'),
                ]
        );

        $repeater->add_control(
                'field_label_h',
                [
                    'label' => esc_html__('Label', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $repeater->add_control(
                'show_label',
                [
                    'label' => esc_html__('Label', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Show', 'elementinvader-addons-for-elementor'),
                    'label_off' => esc_html__('Hide', 'elementinvader-addons-for-elementor'),
                    'return_value' => 'true',
                    'default' => 'true',
                    'dynamic' => [
                        'active' => true,
                    ],
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'field_type',
                                'operator' => '!in',
                                'value' => [
                                    'radio',
                                    'checkbox',
                                    'recaptcha',
                                ],
                            ],
                        ],
                    ],
                ]
        );
        $repeater->add_control(
                'label_position',
                [
                    'label' => esc_html__('Label Position', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'above' => esc_html__('Above', 'elementinvader-addons-for-elementor'),
                        'inline' => esc_html__('Inline', 'elementinvader-addons-for-elementor'),
                    ],
                    'default' => 'above',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'show_label',
                                'value' => 'true',
                            ],
                            [
                                'name' => 'field_type',
                                'operator' => '!in',
                                'value' => [
                                    'radio',
                                    'recaptcha',
                                    'checkbox',
                                ],
                            ],
                        ],
                    ],
                ]
        );


        $repeater->end_controls_tabs();

        /* end form field content */

        /* START Form Fields */

        $this->start_controls_section(
                'section_form_fields',
                [
                    'label' => esc_html__('Newsletter Form Fields', 'elementinvader-addons-for-elementor'),
                    'tab' => 'fields_tab',
                ]
        );

        $this->add_control(
                'form_fields',
                [
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                        [
                            'custom_id' => 'email',
                            'field_type' => 'email',
                            'required' => 'true',
                            'field_label' => esc_html__('Email', 'elementinvader-addons-for-elementor'),
                            'placeholder' => esc_html__('Email', 'elementinvader-addons-for-elementor'),
                            'width' => '100',
                        ],
                    ],
                    'title_field' => '{{{ field_label }}}',
                ]
        );
        
        $this->add_control(
                'important_note',
                [
                        'label' => '',
                        'type' => \Elementor\Controls_Manager::RAW_HTML,
                        'raw' => sprintf(__('<a href="%s" target="_blank">link to Export mail list in CSV</a>', 'elementinvader-addons-for-elementor'), admin_url('tools.php?page=eli-mails')),
                        'content_classes' => '',
                ] 
        );
        
        $this->end_controls_section();

        /* END Form Fields */

        /* START Submit Button */

        $this->start_controls_section(
                'section_submit_button',
                [
                    'label' => esc_html__('Submit Button', 'elementinvader-addons-for-elementor'),
                    'tab' => 'fields_tab',
                ]
        );

        $this->add_control(
                'button_text',
                [
                    'label' => esc_html__('Text', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__('Send', 'elementinvader-addons-for-elementor'),
                    'placeholder' => esc_html__('Send', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_responsive_control(
                'button_width',
                [
                    'label' => esc_html__('Column Width', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__('Default', 'elementinvader-addons-for-elementor'),
                        '100' => '100%',
                        '80' => '80%',
                        '75' => '75%',
                        '66' => '66%',
                        '60' => '60%',
                        '50' => '50%',
                        '40' => '40%',
                        '33' => 'calc(100% / 3)',
                        '25' => '25%',
                        '20' => '20%',
                        'auto' => 'auto',
                        'auto_flexible' => 'auto flexible',
                    ],
                    'default' => '100',
                ]
        );

        $this->add_responsive_control(
                'button_align',
                [
                    'label' => esc_html__('Alignment', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'start' => [
                            'title' => esc_html__('Left', 'elementinvader-addons-for-elementor'),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__('Center', 'elementinvader-addons-for-elementor'),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'end' => [
                            'title' => esc_html__('Right', 'elementinvader-addons-for-elementor'),
                            'icon' => 'eicon-text-align-right',
                        ],
                        'stretch' => [
                            'title' => esc_html__('Justified', 'elementinvader-addons-for-elementor'),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'render_type' => 'template',
                    'default' => 'stretch',
                    'prefix_class' => 'elementor%s-button-align-',
                ]
        );

        $this->add_control(
                'selected_button_icon',
                [
                    'label' => esc_html__('Icon', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'button_icon',
                    'label_block' => true,
                ]
        );

        $this->add_control(
                'button_icon_align',
                [
                    'label' => esc_html__('Icon Position', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'left',
                    'options' => [
                        'left' => esc_html__('Before', 'elementinvader-addons-for-elementor'),
                        'right' => esc_html__('After', 'elementinvader-addons-for-elementor'),
                    ],
                    'condition' => [
                        'selected_button_icon[value]!' => '',
                    ],
                ]
        );

        $this->add_control(
                'button_icon_indent',
                [
                    'label' => esc_html__('Icon Spacing', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'condition' => [
                        'selected_button_icon[value]!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'button_css_id',
                [
                    'label' => esc_html__('Button ID', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                    'title' => esc_html__('Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementinvader-addons-for-elementor'),
                    'label_block' => false,
                    'description' => esc_html__('Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'elementinvader-addons-for-elementor'),
                    'separator' => 'before',
                ]
        );

        $this->end_controls_section();
        /* end */

        /* START Email Parameters */

        $this->start_controls_section(
                'section_form_options',
                [
                    'label' => esc_html__('Email Parameters', 'elementinvader-addons-for-elementor'),
                ]
        );
        
        $this->add_control(
                'disable_mail_send',
                [
                    'label' => esc_html__('Disable Send to Mail', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'elementinvader-addons-for-elementor'),
                    'label_off' => esc_html__('No', 'elementinvader-addons-for-elementor'),
                    'return_value' => 'yes',
                    'default' => '',
                ]
        );
        
        $this->add_control(
                'mail_data_to_email',
                [
                    'label' => esc_html__('To Email', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => get_bloginfo('admin_email'),
                    'condition' => [
                        'disable_mail_send' => '',
                    ],
                ]
        );

        $this->add_control(
                'mail_data_subject',
                [
                    'label' => esc_html__('Subject', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => esc_html__('Newsletter new email submission', 'elementinvader-addons-for-elementor'),
                    'description' => esc_html__('Add special data from fields like {field-id}', 'elementinvader-addons-for-elementor'),
                    'condition' => [
                        'disable_mail_send' => '',
                    ],
                ]
        );

        $this->add_control(
                'mail_data_from_email',
                [
                    'label' => esc_html__('From Email', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => get_bloginfo('admin_email'),
                    'condition' => [
                        'disable_mail_send' => '',
                    ],
                ]
        );

        $this->add_control(
                'mail_data_from_name',
                [
                    'label' => esc_html__('From Name', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => get_bloginfo('name'),
                    'condition' => [
                        'disable_mail_send' => '',
                    ],
                ]
        );
        $this->end_controls_section();
 
        $this->start_controls_section(
                'custom_messages',
                [
                    'label' => esc_html__('Custom Validation', 'elementinvader-addons-for-elementor'),
                ]
        );

        $default_messages = Ajax_Handler::get_default_messages();

        $this->add_control(
                'success_message',
                [
                    'label' => esc_html__('Success Message', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__('You are added to mail list', 'elementinvader-addons-for-elementor'),
                    'placeholder' => $default_messages[Ajax_Handler::SUCCESS],
                    'label_block' => true,
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'error_message',
                [
                    'label' => esc_html__('Error Message', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => $default_messages[Ajax_Handler::ERROR],
                    'placeholder' => $default_messages[Ajax_Handler::ERROR],
                    'label_block' => true,
                ]
        );

        $this->add_control(
                'required_field_message',
                [
                    'label' => esc_html__('Required Message', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => $default_messages[Ajax_Handler::FIELD_REQUIRED],
                    'placeholder' => $default_messages[Ajax_Handler::FIELD_REQUIRED],
                    'label_block' => true,
                ]
        );

        $this->add_control(
                'invalid_message',
                [
                    'label' => esc_html__('Invalid Message', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => $default_messages[Ajax_Handler::INVALID_FORM],
                    'placeholder' => $default_messages[Ajax_Handler::INVALID_FORM],
                    'label_block' => true,
                    
                ]
        );
        $this->end_controls_section();

        /* END Email Parameters */
        /* START Email Parameters */

        $this->start_controls_section(
                'section_form_recaptcha',
                [
                    'label' => esc_html__('Recaptcha Configuration', 'elementinvader-addons-for-elementor'),
                    'tab' => 'config_tab',
                ]
        );

        $this->add_control(
                'recaptcha_site_key',
                [
                    'label' => esc_html__('Site Key', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                ]
        );

        $this->add_control(
                'recaptcha_secret_key',
                [
                    'label' => esc_html__('Secret Key', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'description' => sprintf(esc_html__('Configurate only if use recaptha, %1$s Get Keys %2$s', 'elementinvader-addons-for-elementor'),'<a href="https://www.google.com/recaptcha/about/" target="_blank">','</a>'),
                ]
        );

        $this->add_control(
			'important_note_smtp',
			[
				'label' => '',
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => esc_html__('Please use plugin for SMTP configuration, we suggesting WP Mail SMTP', 'elementinvader-addons-for-elementor'),
			]
		);

        $this->end_controls_section();
        
        $this->start_controls_section(
                'section_send_action',
                [
                    'label' => esc_html__('Mailchimp Configuration', 'elementinvader-addons-for-elementor'),
                    'tab' => 'config_tab',
                ]
        );

        $this->add_control(
                'send_action_mailchimp_api_key',
                [
                    'label' => esc_html__('API Key', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                ]
        );

        $this->add_control(
                'send_action_mailchimp_list_id',
                [
                    'label' => esc_html__('Audience ID', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'description' => sprintf(esc_html__('%1$sWhere to get Audience ID?%2$s', 'elementinvader-addons-for-elementor'),'<a target="_blank" href="https://mailchimp.com/help/find-audience-id/">','</a>').'<br>'.
                        sprintf(esc_html__('%1$sWhere to get API Key?%2$s', 'elementinvader-addons-for-elementor'),'<a target="_blank" href="https://mailchimp.com/help/about-api-keys/">','</a>'),
                ]    
        );
        
        $this->add_control(
			'important_note_smtp2',
			[
				'label' => '',
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => esc_html__('Please use plugin for SMTP configuration, we suggesting WP Mail SMTP', 'elementinvader-addons-for-elementor'),
			]
		);
        $this->end_controls_section();
        
        $this->start_controls_section(
                'section_send_action_brevo',
                [
                    'label' => esc_html__('Brevo Configuration', 'elementinvader-addons-for-elementor'),
                    'tab' => 'config_tab',
                ]
        );

        $this->add_control(
                'send_action_brevo_api_key',
                [
                    'label' => esc_html__('API Key', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                ]
        );

        $this->add_control(
                'send_action_brevo_list_id',
                [
                    'label' => esc_html__('Audience ID', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'description' => sprintf(esc_html__('%1$sWhere to get Audience ID?%2$s', 'elementinvader-addons-for-elementor'),'<a target="_blank" href="https://account-app.brevo.com">','</a>').'<br>'.
                        sprintf(esc_html__('%1$sWhere to get API Key?%2$s', 'elementinvader-addons-for-elementor'),'<a target="_blank" href="https://account-app.brevo.com/account/login?target=http%3A%2F%2Faccount.brevo.com%2Fadvanced%2Fapi">','</a>'),
                ]    
        );
        
 
        $this->end_controls_section();

        /* TAB_STYLE */
        $this->start_controls_section(
                'section_form_style',
                [
                    'label' => esc_html__('Newsletter Form', 'elementinvader-addons-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
            'disable_scroll_to_form',
            [
                'label' => esc_html__('Disable scroll to form', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'elementinvader-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'elementinvader-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => '',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'alert_box_bellow_form',
            [
                'label' => esc_html__('Show validation below form', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'elementinvader-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'elementinvader-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => '',
                'separator' => 'before',
            ]
        );

        $this->add_control(
                'column_gap',
                [
                    'label' => esc_html__('Columns Gap', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 60,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group' => 'padding-left: {{SIZE}}{{UNIT}};padding-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_container' => 'margin-left: -{{SIZE}}{{UNIT}};margin-right: -{{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'row_gap',
                [
                    'label' => esc_html__('Rows Gap', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 60,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'heading_label',
                [
                    'label' => esc_html__('Label', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'label_spacing',
                [
                    'label' => esc_html__('Spacing', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 60,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->start_controls_tabs('tabs_label_style');
        /* START Label Normal */
        $this->start_controls_tab(
                'tab_label_normal',
                [
                    'label' => esc_html__('Normal', 'elementinvader-addons-for-elementor'),
                ]
        );
        $this->add_control(
                'label_color',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group label' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'label_typography',
                    'selector' => '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group label',
                ]
        );
        $this->end_controls_tab();
        /* END Label Normal */
        /* START Label Hover */
        $this->start_controls_tab(
                'tab_label_hover',
                [
                    'label' => esc_html__('Hover', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'label_color_hover',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group:hover label' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'label_typography_hover',
                    'selector' => '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group:hover label',
                ]
        );

        $this->add_control(
                'label_effect_duration',
                [
                    'label' => esc_html__('Transition Duration', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'render_type' => 'template',
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 3000,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group label' => 'transition-duration: {{SIZE}}ms',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->end_controls_tab();


        /* END Label Hover */

        $this->end_controls_tabs();
        $this->end_controls_section();
        /* END FORM */

        /* START FIELD */
        $this->start_controls_section(
                'section_field_style',
                [
                    'label' => esc_html__('Field', 'elementinvader-addons-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->start_controls_tabs('tabs_field_style');
        /* START FIELD Normal */
        $this->start_controls_tab(
                'tab_field_normal',
                [
                    'label' => esc_html__('Normal', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'field_text_color',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group .elementinvader_addons_for_elementor_f_field' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'field_typography',
                    'selector' => '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group .elementinvader_addons_for_elementor_f_field',
                ]
        );

        $this->add_control(
                'field_height',
                [
                    'label' => esc_html__('Field Height', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'render_type' => 'template',
                    'range' => [
                        'px' => [
                            'min' => 15,
                            'max' => 400,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group input.elementinvader_addons_for_elementor_f_field:not([type="radio"]):not([type="checkbox"]),{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group select.elementinvader_addons_for_elementor_f_field:not([type="radio"]):not([type="checkbox"])' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
        );

        $object = [
            'normal' => '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group input.elementinvader_addons_for_elementor_f_field:not([type="radio"]):not([type="checkbox"]),{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group select.elementinvader_addons_for_elementor_f_field:not([type="radio"]):not([type="checkbox"])',
        ];
        $this->generate_renders_tabs($object, 'field_padding', ['padding']);

        $this->add_control(
                'field_background_color',
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group .elementinvader_addons_for_elementor_f_field' => 'background-color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'field_border_color',
                [
                    'label' => esc_html__('Border Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group .elementinvader_addons_for_elementor_f_field' => 'border-color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'field_border_width',
                [
                    'label' => esc_html__('Border Width', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'placeholder' => '1',
                    'size_units' => ['px'],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group .elementinvader_addons_for_elementor_f_field' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'field_border_radius',
                [
                    'label' => esc_html__('Border Radius', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group .elementinvader_addons_for_elementor_f_field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'box_box_shadow',
                    'exclude' => [
                        'box_shadow_position',
                    ],
                    'selector' => '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group .elementinvader_addons_for_elementor_f_field',
                ]
        );
        $this->end_controls_tab();
        /* END FIELD Normal */

        /* START FIELD Focus */
        $this->start_controls_tab(
                'tab_field_focus',
                [
                    'label' => esc_html__('Focus', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'field_text_color_focus',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group .elementinvader_addons_for_elementor_f_field:focus' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'field_typography_focus',
                    'selector' => '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group .elementinvader_addons_for_elementor_f_field:focus',
                ]
        );

        $this->add_control(
                'field_background_color_focus',
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group .elementinvader_addons_for_elementor_f_field:focus' => 'background-color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'field_border_color_focus',
                [
                    'label' => esc_html__('Border Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group .elementinvader_addons_for_elementor_f_field:focus' => 'border-color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'field_border_width_focus',
                [
                    'label' => esc_html__('Border Width', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'placeholder' => '1',
                    'size_units' => ['px'],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group .elementinvader_addons_for_elementor_f_field:focus' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'field_border_radius_focus',
                [
                    'label' => esc_html__('Border Radius', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group .elementinvader_addons_for_elementor_f_field:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'field_box_shadow_focus',
                    'exclude' => [
                        'box_shadow_position',
                    ],
                    'selector' => '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group .elementinvader_addons_for_elementor_f_field:focus',
                ]
        );

        $this->add_control(
                'field_duration',
                [
                    'label' => esc_html__('Transition Duration', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'render_type' => 'template',
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 3000,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group .elementinvader_addons_for_elementor_f_field' => 'transition-duration: {{SIZE}}ms',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->end_controls_tab();

        /* END FIELD Focus */
        $this->end_controls_tabs();
        $this->end_controls_section();

        /* END FIELD */

        $this->start_controls_section(
                'section_button_style',
                [
                    'label' => esc_html__('Button', 'elementinvader-addons-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
                'tab_button_normal',
                [
                    'label' => esc_html__('Normal', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'button_background_color',
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_button button' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_text_color',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_button button' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_button button svg' => 'fill: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'button_typography',
                    'selector' => '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_button button',
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'button_border',
            'selector' => '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_button button',
                ]
        );

        $this->add_control(
                'button_border_radius',
                [
                    'label' => esc_html__('Border Radius', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}}  .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_button button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'button_text_padding',
                [
                    'label' => esc_html__('Text Padding', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_button button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'button_box_shadow',
                    'exclude' => [
                        'button_shadow_position',
                    ],
                    'selector' => '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_button button',
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'tab_button_hover',
                [
                    'label' => esc_html__('Hover', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'button_background_hover_color',
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}  .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_button button:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_hover_color',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}  .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_button button:hover' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_hover_border_color',
                [
                    'label' => esc_html__('Border Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}  .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_button button:hover' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'button_border_border!' => '',
                    ],
                ]
        );


        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'button_box_shadow_hover',
                    'exclude' => [
                        'button_shadow_position',
                    ],
                    'selector' => '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_button button:hover',
                ]
        );


        $this->add_control(
                'button_hover_animation',
                [
                    'label' => esc_html__('Animation', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HOVER_ANIMATION,
                ]
        );

        $this->add_control(
                'button_duration',
                [
                    'label' => esc_html__('Transition Duration', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'render_type' => 'template',
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 3000,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_button button' => 'transition-duration: {{SIZE}}ms',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /* START MESSAGES */
        $this->start_controls_section(
                'section_messages_style',
                [
                    'label' => esc_html__('Validations', 'elementinvader-addons-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
                'show_alerts_example',
                [
                    'label' => esc_html__('Show example alerts', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Show', 'elementinvader-addons-for-elementor'),
                    'label_off' => esc_html__('Hide', 'elementinvader-addons-for-elementor'),
                    'return_value' => 'true',
                    'default' => '',
                ]
        );

        $this->add_control(
                'heading_suc_message',
                [
                    'label' => esc_html__('Success Message', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $object = [
            'normal' => '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_alert.elementinvader_addons_for_elementor_alert-success',
        ];
        $this->generate_renders_tabs($object, 'success_message_color', 'text-block', ['align', 'typo', 'shadow', 'border_radius', 'transition']);

        $this->add_control(
                'error_mess_label',
                [
                    'label' => esc_html__('Error Message', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $object = [
            'normal' => '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_alert.elementinvader_addons_for_elementor_alert-danger',
        ];
        $this->generate_renders_tabs($object, 'card_caption_i_style', 'text-block', ['align', 'typo', 'shadow', 'border_radius', 'transition']);

        $this->add_control(
                'info_mess_label',
                [
                    'label' => esc_html__('Info Message', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $object = [
            'normal' => '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_alert.elementinvader_addons_for_elementor_alert-primary',
        ];
        $this->generate_renders_tabs($object, 'info_mess_label', 'text-block', ['align', 'typo', 'shadow', 'border_radius', 'transition']);

        $this->add_control(
                'info_mess_label_basic',
                [
                    'label' => esc_html__('Basic', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $object = [
            'normal' => '{{WRAPPER}} .elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_alert',
        ];
        $this->generate_renders_tabs($object, 'card_caption_i_style_basic', ['align', 'typo', 'padding', 'border_radius']);

        $this->end_controls_section();
        /* END MESSAGES */

        parent::register_controls();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function render() {
        $id_int = substr($this->get_id_int(), 0, 3);

        $settings = $this->get_settings();
        $settings['send_action_type'] = 'mail_base';
        /* tempararry for mail form */
        if (Plugin::$instance->editor->is_edit_mode()) {
            $form_data = [];
            $form_data['disable_mail_send'] = $settings['disable_mail_send'];
            $form_data['mail_data_to_email'] = $settings['mail_data_to_email'];
            $form_data['mail_data_subject'] = $settings['mail_data_subject'];
            $form_data['mail_data_from_email'] = $settings['mail_data_from_email'];
            $form_data['mail_data_from_name'] = $settings['mail_data_from_name'];
            $form_data['mail_data_to_email'] = $settings['mail_data_to_email'];

            $form_data['success_message'] = $settings['success_message'];
            $form_data['error_message'] = $settings['error_message'];
            $form_data['required_field_message'] = $settings['required_field_message'];
            $form_data['invalid_message'] = $settings['invalid_message'];

            $form_data['recaptcha_site_key'] = $settings['recaptcha_site_key'];
            $form_data['recaptcha_secret_key'] = $settings['recaptcha_secret_key'];
            $form_data['section_send_action_mailchimp_api_key'] = $settings['send_action_mailchimp_api_key'];
            $form_data['section_send_action_mailchimp_list_id'] = $settings['send_action_mailchimp_list_id'];

            update_option('elementinvader_addons_for_elementor_form_' . $this->get_id_int(), $form_data);
        }

        /* Form proccessing */

        $this->add_inline_editing_attributes('title', 'none');
        $this->add_inline_editing_attributes('subtitle', 'basic');
        $this->add_inline_editing_attributes('content', 'advanced');
        $this->add_inline_editing_attributes('form_button_text', 'none');

        $this->add_render_attribute('title', [
            'class' => 'wy-title elementor-inline-editing'
        ]);

        $this->add_render_attribute('subtitle', [
            'class' => 'subtitle elementor-inline-editing'
        ]);

        $this->add_render_attribute('content', [
            'class' => 'widget body'
        ]);

        $this->add_inline_editing_attributes('form_button_text', 'basic');
        $this->add_render_attribute('form_button_text', [
            'class' => 'btn btn-custom btn-custom-secondary'
        ]);
        $this->add_inline_editing_attributes('mail', 'basic');
        $this->add_render_attribute('mail', [
            'class' => 'text elementor-inline-editing'
        ]);

        $this->add_inline_editing_attributes('phone_first', 'basic');
        $this->add_render_attribute('phone_first', [
            'class' => 'text elementor-inline-editing'
        ]);
        $this->add_inline_editing_attributes('phone_second', 'basic');
        $this->add_render_attribute('phone_second', [
            'class' => 'text elementor-inline-editing'
        ]);
        $this->add_inline_editing_attributes('address', 'basic');
        $this->add_render_attribute('address', [
            'class' => 'text elementor-inline-editing'
        ]);
        ?>


        <?php
        $content = [];
        $content ['wlisting_fields'] = '';
        foreach ($settings['form_fields'] as $item_index => $item) :
            ?>
            <?php
            switch ($item['field_type']) :
                case 'textarea':
                    $content ['wlisting_fields'] .= $this->generate_textarea_field($item);
                    break;
                case 'select':
                    $content ['wlisting_fields'] .= $this->generate_select_field($item);
                    break;

                case 'radio':
                    $content ['wlisting_fields'] .= $this->generate_radio_field($item);
                    break;
                case 'checkbox':
                    $content ['wlisting_fields'] .= $this->generate_checkbox_field($item);
                    break;
                case 'html':
                    $content ['wlisting_fields'] .= $this->generate_html_field($item);
                    break;
                case 'recaptcha':
                    $content ['wlisting_fields'] .= $this->generate_recaptcha_field($item);
                    break;
                case 'tel':
                case 'text':
                case 'email':
                    $item['required'] = true;
                    $content ['wlisting_fields'] .= $this->generate_input_field($item);
                    break;
                case 'url':
                case 'password':
                case 'hidden':
                case 'file':
                case 'number':
                case 'date':
                case 'time':
                case 'upload':
                case 'search':
                    $content ['wlisting_fields'] .= $this->generate_input_field($item);
                    break;
            endswitch;
            ?>
        <?php endforeach; ?>
        <?php
        $this->add_field_css($settings, $prefix = 'button_width', 'button');
        $this->_generate_layout($settings, $content);
        $this->add_page_settings_css();

    }

    protected function _generate_layout($settings, $smart_data) {
        wp_enqueue_script('elementinvader_addons_for_elementor-main');

        $output = $this->view('widget_layout', ['settings' => $settings, 'smart_data' => $smart_data], true);
        $this->generate_css();

    }

    protected function generate_textarea_field($element = NULL, $index = NULL) {
        if (empty($element))
            return '';
        $output = $this->view('fields/textarea', ['element' => $element]);

        return $output;
    }

    protected function generate_select_field($element = NULL, $index = NULL) {
        if (empty($element))
            return '';
        $output = $this->view('fields/select', ['element' => $element]);

        return $output;
    }

    protected function generate_radio_field($element = NULL, $index = NULL) {
        if (empty($element))
            return '';
        $output = $this->view('fields/radio', ['element' => $element]);

        return $output;
    }

    protected function generate_checkbox_field($element = NULL, $index = NULL) {
        if (empty($element))
            return '';
        $output = $this->view('fields/checkbox', ['element' => $element]);

        return $output;
    }

    protected function generate_html_field($element = NULL, $index = NULL) {
        if (empty($element))
            return '';
        $output = $this->view('fields/html', ['element' => $element]);

        return $output;
    }

    protected function generate_input_field($element = NULL, $index = NULL) {
        if (empty($element))
            return '';
        $output = $this->view('fields/input', ['element' => $element]);

        return $output;
    }

    protected function generate_recaptcha_field($element = NULL, $index = NULL) {
        if (empty($element))
            return '';
        $settings = $this->get_settings();
        $output = $this->view('fields/recaptcha', ['settings' => $settings, 'element' => $element]);

        return $output;
    }

    public function add_field_css($element, $prefix = 'width', $index = '') {
        if (empty($index) && isset($element['_id']))
            $index = $element['_id'];

        if (isset($element[$prefix]) && $element[$prefix])
            if ($element[$prefix] == 'auto_flexible')
                $this->inline_css .= "
                        #elementinvader_addons_for_elementor_" . $this->get_id_int() . ".elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_{$index} {
                            width:auto;-webkit-flex: 1 2 auto;
                            flex: 1 2 auto;
                        }
                    ";
            elseif ($element[$prefix] == 'auto')
                $this->inline_css .= "
                        #elementinvader_addons_for_elementor_" . $this->get_id_int() . ".elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_{$index} {
                            
                            width:auto;-webkit-flex: 0 0 auto;
                            flex: 0 0 auto;
                        }
                    ";
            else
                $this->inline_css .= "
                        #elementinvader_addons_for_elementor_" . $this->get_id_int() . ".elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_{$index} {
                            width: {$element[$prefix]}%;
                            -webkit-flex: 0 0 {$element[$prefix]}%;
                            flex: 0 0 {$element[$prefix]}%;
                        }
                    ";


        if (isset($element[$prefix . '_tablet']) && $element[$prefix . '_tablet'])
            if ($element[$prefix . '_tablet'] == 'auto_flexible')
                $this->inline_css_tablet .= "
                        #elementinvader_addons_for_elementor_" . $this->get_id_int() . ".elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_{$index} {
                            width:auto;-webkit-flex: 1 2 auto;
                            flex: 1 2 auto;
                        }
                    ";
            elseif ($element[$prefix . '_tablet'] == 'auto')
                $this->inline_css_tablet .= "
                        #elementinvader_addons_for_elementor_" . $this->get_id_int() . ".elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_{$index} {
                            width:auto;-webkit-flex: 0 0 auto;
                            flex: 0 0 auto;
                        }
                    ";
            else
                $this->inline_css_tablet .= "
                        #elementinvader_addons_for_elementor_" . $this->get_id_int() . ".elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_{$index} {
                            width: {$element[$prefix . '_tablet']}%;
                            -webkit-flex: 0 0 {$element[$prefix . '_tablet']}%;
                            flex: 0 0 {$element[$prefix . '_tablet']}%;
                        }
                    ";

        if (isset($element[$prefix . '_mobile']) && $element[$prefix . '_mobile'])
            if ($element[$prefix . '_mobile'] == 'auto')
                $this->inline_css_mobile .= "
                        #elementinvader_addons_for_elementor_" . $this->get_id_int() . ".elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_{$index} {
                            width:auto;-webkit-flex: 0 0 auto;
                            flex: 0 0 auto;
                        }
                    ";
            elseif ($element[$prefix . '_mobile'] == 'auto_flexible')
                $this->inline_css_mobile .= "
                        #elementinvader_addons_for_elementor_" . $this->get_id_int() . ".elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_{$index} {
                            width:auto;-webkit-flex: 1 2 auto;
                            flex: 1 2 auto;
                        }
                    ";
            else
                $this->inline_css_mobile .= "
                        #elementinvader_addons_for_elementor_" . $this->get_id_int() . ".elementinvader_contact_form  .elementinvader_addons_for_elementor_f .elementinvader_addons_for_elementor_f_group.elementinvader_addons_for_elementor_f_group_el_{$index} {
                            width: {$element[$prefix . '_mobile']}%;
                            -webkit-flex: 0 0 {$element[$prefix . '_mobile']}%;
                            flex: 0 0 {$element[$prefix . '_mobile']}%;
                        }
                    ";
    }

    public function generate_css() {
        $output_css = '';
        $output_css .= $this->inline_css;
        $output_css .= sprintf('@media(max-width:%1$s){%2$s}', '991px', $this->inline_css_tablet);
        $output_css .= sprintf('@media(max-width:%1$s){%2$s}', '768px', $this->inline_css_mobile);

        wp_enqueue_style('eli-custom-inline', plugins_url( '/assets/css/custom-inline.css', ELEMENTINVADER_ADDONS_FOR_ELEMENTOR__FILE__ ));
       
        /* only for edit mode */
        if (Plugin::$instance->editor->is_edit_mode()) {
            echo '<style>'.sanitize_text_field($output_css).'</style>';
        } else {
            wp_add_inline_style( 'eli-custom-inline', $output_css );
        }
    }

    public function get_align_class($setting_data = '', $prefix = '') {
        $class = '';
        switch ($setting_data) {
            case 'start': $class = $prefix . 'left';
                break;
            case 'center': $class = $prefix . 'center';
                break;
            case 'end': $class = $prefix . 'right';
                break;
            case 'stretch': $class = $prefix . 'justify';
                break;
            default:
                break;
        }

        return $class;
    }

    public function el_icon_with_fallback($settings) {
        $migrated = isset($settings['__fa4_migrated']['selected_button_icon']);
        $is_new = empty($settings['button_icon']) && Icons_Manager::is_migration_allowed();

        if ($is_new || $migrated) {
            Icons_Manager::render_icon($settings['selected_button_icon'], ['aria-hidden' => 'true']);
        } else {
            ?><i class="<?php echo esc_attr($settings['button_icon']); ?>" aria-hidden="true"></i><?php
        }
    }

}
