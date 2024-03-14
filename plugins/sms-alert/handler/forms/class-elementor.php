<?php

/**
 * This elementor form via sms notification
 *
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */ 
if (! defined('ABSPATH') ) {
    exit; // Exit if accessed directly.
}

if (! is_plugin_active('elementor/elementor.php') ) {
    return; 
}

if (! is_plugin_active('elementor-pro/elementor-pro.php') ) {
    return; 
}    
  
/**
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 *
 * class SAElementor
 */  
class SAElementor extends FormInterface
{
    /**
     * Elementor form key
     *
     * @var $form_session_var
     */
    private $form_session_var = FormSessionVars::ELEMENTOR_FORM;

    /**
     * Handle OTP form
     *
     * @return void
     */
    public function handleForm()
    {
        add_action('elementor_pro/forms/validation', [ $this, 'checkPhoneVerified' ], 9, 2);
        add_action('elementor_pro/forms/validation', [ $this, 'elementorFormValidationErrors' ], 11, 2);
    }
    
    /**
     * This function shows validation error message.
     *
     * @param $record       Form Record
     * @param $ajax_handler Ajax Handler 
     *
     * @return void.
     */
    public function checkPhoneVerified( $record, $ajax_handler )
    {
        SmsAlertUtility::checkSession();
        if (isset($_SESSION['sa_mobile_verified'])  ) {
            unset($_SESSION['sa_mobile_verified']);
            $fields = $record->get_field(
                [
                'type' => 'recaptcha',
                ] 
            );

            if (empty($fields) ) {
                $fields = $record->get_field(
                    [
                    'type' => 'recaptcha_v3',
                    ] 
                );
                if (empty($fields) ) {
                      return;
                }
            }
            $field = current($fields);
            $record->remove_field($field['id']);
            return;
        }
    }

    /**
     * This function shows validation error message.
     *
     * @param $record       Form Record
     * @param $ajax_handler Ajax_Handler
     *
     * @return void.
     */
    public function elementorFormValidationErrors( $record, $ajax_handler )
    {
        if (!$ajax_handler->is_success) {
            return;
        }
        if (isset($_REQUEST['option']) && 'smsalert_elementor_form_otp' === sanitize_text_field(wp_unslash($_REQUEST['option']))) {
            SmsAlertUtility::initialize_transaction($this->form_session_var);
        } else {
            return;
        }

        $fields = $record->get_field(
            [
            'type' => 'sa_billing_phone',
             ] 
        );
        $field = current($fields);
        $user_phone = $field['value'];
        if (isset($user_phone) && SmsAlertUtility::isBlank($user_phone) ) {
            wp_send_json(SmsAlertUtility::_create_json_response(__('Please enter phone number.', 'sms-alert'), SmsAlertConstants::ERROR_JSON_TYPE));
            exit();
        }

        return $this->processFormFields($user_phone);
    }

    /**
     * This function processed form fields.
     *
     * @param string $user_phone User phone.
     *
     * @return bool
     */
    public function processFormFields( $user_phone )
    {
        global $phoneLogic;
        $phone_num = preg_replace('/[^0-9]/', '', $user_phone);

        if (! isset($phone_num) || ! SmsAlertUtility::validatePhoneNumber($phone_num) ) {
            wp_send_json(SmsAlertUtility::_create_json_response(str_replace('##phone##', $getdata['user_phone'], $phoneLogic->_get_otp_invalid_format_message()), SmsAlertConstants::ERROR_JSON_TYPE));
            exit();
        }
        
        smsalert_site_challenge_otp('test', null, null, $phone_num, 'phone', null, null, 'ajax');
    }
    
    /**
     * Check your otp setting is enabled or not.
     *
     * @return bool
     */
    public static function isFormEnabled()
    {
        $user_authorize = new smsalert_Setting_Options();
        $islogged       = $user_authorize->is_user_authorised();
        return ( is_plugin_active('elementor/elementor.php') && $islogged && is_plugin_active('elementor-pro/elementor-pro.php') ) ? true : false;
    }

    /**
     * Handle after failed verification
     *
     * @param object $user_login   users object.
     * @param string $user_email   user email.
     * @param string $phone_number phone number.
     *
     * @return void
     */
    public function handle_failed_verification( $user_login, $user_email, $phone_number )
    {
        SmsAlertUtility::checkSession();
        if (! isset($_SESSION[ $this->form_session_var ]) ) {
            return;
        }
        if (isset($_SESSION[ $this->form_session_var ]) ) {
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('INVALID_OTP'), 'error'));
        }
    }

    /**
     * Handle after post verification
     *
     * @param string $redirect_to  redirect url.
     * @param object $user_login   user object.
     * @param string $user_email   user email.
     * @param string $password     user password.
     * @param string $phone_number phone number.
     * @param string $extra_data   extra hidden fields.
     *
     * @return void
     */
    public function handle_post_verification( $redirect_to, $user_login, $user_email, $password, $phone_number, $extra_data )
    {
        SmsAlertUtility::checkSession();
        if (! isset($_SESSION[ $this->form_session_var ]) ) {
            return;
        }
        $_SESSION['sa_mobile_verified'] = true;
        if (isset($_SESSION[ $this->form_session_var ]) ) {
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('VALID_OTP'), 'success'));
        }
    }

    /**
     * Clear otp session variable
     *
     * @return void
     */
    public function unsetOTPSessionVariables()
    {
        unset($_SESSION[ $this->form_session_var ]);
    }

    /**
     * Check current form submission is ajax or not
     *
     * @param bool $is_ajax bool value for form type.
     *
     * @return bool
     */
    public function is_ajax_form_in_play( $is_ajax )
    {
        SmsAlertUtility::checkSession();
        return isset($_SESSION[ $this->form_session_var ]) ? true : $is_ajax;
    }

    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {

    }
}
new SAElementor();

/**
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 *
 * class SAElementor
 */
class Elementor extends ElementorPro\Modules\Forms\Fields\Field_Base
{
     /**
      * Get type
      *
      * @return void
      */
    public function get_type()
    {
        return 'sa_billing_phone';
    }

     /**
      * Get name
      *
      * @return void
      */
    public function get_name()
    {
        return __('SMSAlert', 'sms-alert');
    }


     /**
      * Construct
      *
      * @return void
      */
    public function __construct()
    {
        $user_authorize = new smsalert_Setting_Options();
        $islogged       = $user_authorize->is_user_authorised();
        if (!$islogged ) { 
            return; 
        }
        
        parent::__construct();

        add_action('elementor_pro/init', [ $this, 'addCustomAction' ]);
        add_action('elementor/widget/before_render_content', [ $this, 'addShortcode' ]);    
        add_filter('elementor_pro/forms/field_types', [ $this, 'registerFieldType' ]);
        add_action('elementor/preview/init', [ $this, 'editorInlineJS' ]);
        add_filter('elementor/document/before_save', array( $this, 'checkSmsalertField' ), 100, 2);
    }
    
    
    /**
     * EditorInlineJS
     *
     * @return void
     */
    public function editorInlineJS()
    {
        add_action(
            'wp_footer', function () {
                ?>
        <script>
        var ElementorFormSAField = ElementorFormSAField || {};
        jQuery( document ).ready( function( $ ) {
        
            function renderField( inputField, item, i, settings ) {
                var itemClasses = item.css_classes,
                    required = '',
                    fieldName = 'form_field_';

                if ( item.required ) {
                    required = 'required';
                }
                return '<input type="sa_billing_phone" class="elementor-field-textual ' + itemClasses + '" name="' + fieldName + '" id="form_field_' + i + '" ' + required + ' placeholder="' + item.sa_billing_phone + '" value="' + item.sa_default_value + '">';
            }
            
            elementor.hooks.addFilter( 'elementor_pro/forms/content_template/field/sa_billing_phone', renderField, 10, 4 );
        } );
        </script>
                <?php
            } 
        );    
    }
    
    
    /**
     * CheckSmsalertField
     *
     * @param $obj   obj
     * @param $datas datas
     *
     * @return void
     */
    public function checkSmsalertField($obj, $datas)
    {
		if (!empty($datas['elements'])) {
            $smsalert_action_added = false;
            $smsalert_field_added = false;    
            foreach ( $datas['elements'] as $data ) {
                if (array_key_exists('elements', $data) ) {
                    foreach ( $data['elements'] as $element ) {
                        if (array_key_exists('elements', $element) ) {
                            foreach ( $element['elements'] as $setting ) {
                                if (array_key_exists('settings', $setting) ) {
                                    if (!empty($setting['settings']['submit_actions']) && in_array("smsalert", $setting['settings']['submit_actions']) ) {
                                                 $smsalert_action_added = true;
                                        if (!empty($setting['settings']['form_fields'])) {
                                            foreach ($setting['settings']['form_fields'] as $fields) {
                                                if ($fields['field_type'] == 'sa_billing_phone') {
                                                    $smsalert_field_added = true;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($smsalert_action_added && !$smsalert_field_added) {
                wp_send_json_error([ 'statusText' => esc_html__('Please add field type SMS Alert in your form.', 'sms-alert'),'readyState'=>4,'status'=>500 ]);
            }
        }
    }
    
    
    /**
     * RegisterFieldType
     *
     * @param $fields fields
     *
     * @return void
     */
    public function registerFieldType( $fields )
    {
        ElementorPro\Plugin::instance()->modules_manager->get_modules('forms')->add_form_field_type(self::get_type(), $this);
        $fields[ self::get_type() ] = self::get_name();
        return $fields;
    }


     /**
      * AddShortcode
      *
      * @param $form form
      *
      * @return void
      */
    public function addShortcode($form)
    {
        if ('form' === $form->get_name() ) {
            $country_flag_enable    = smsalert_get_option('checkout_show_country_code', 'smsalert_general');
            
            $settings                 = $form->get_settings();
            $form_name                 = $settings['form_name'];
            $fields                       = $settings['form_fields'];
            $inline_script = '';
            foreach ($fields as $field) {
                if ($field['field_type'] == 'sa_billing_phone' ) {
                    if ('on' === $country_flag_enable ) {
                        $uniqueNo = rand();
                        $unique  = $form->get_id();
                        $inline_script .= 'jQuery(document).ready(function(){
							jQuery(".elementor-form #form-field-'.$field['custom_id'].'").each(function () 
							{
								jQuery(this).addClass("phone-valid");
							});	
							initialiseCountrySelector(".phone-valid");						
						});';
                    }
                    if ('true' === $settings['otp_verification_enable'] ) {
                        $unique  = $form->get_id();
						
                        echo do_shortcode('[sa_verify id="" phone_selector="#form-field-'.$field['custom_id'].'" submit_selector=".elementor-element-'.$unique.' .elementor-field-type-submit .elementor-button"]');
                        
                        $inline_script .= 'jQuery(document).ready(function(){
                            function addModalInForm(){

                                jQuery(".modal.smsalertModal").each(function(){

                                    var form_id = jQuery(this).attr("data-form-id");

                                    if ( form_id.indexOf("saFormNo_") > -1){

                                        var class_unq = form_id.substring(form_id.indexOf("_")+ 1);                                jQuery("#sa_verify_"+class_unq).parents("form").append(jQuery(".modal.smsalertModal[data-form-id="+form_id+"]"));
                                    }
                                });
                            }
                            setTimeout(function(){ addModalInForm(); }, 3000);
                        });';
                    }
                } elseif ('recaptcha_v3' == $field['field_type'] && 'true' === $settings['otp_verification_enable']) {
                    $inline_script .= 'jQuery(document).ready(function(){
						var recaptcha_div = jQuery("#form-field-'.$field['custom_id'].'").parents("form").find("[data-sitekey]");
					    if(recaptcha_div.length>0 && recaptcha_div.attr("data-size") == "invisible")
						{
						  recaptcha_div.removeClass("elementor-g-recaptcha").addClass("g-recaptcha").attr("id","sa-grecaptcha").html("");	
						  var site_key = recaptcha_div.attr("data-sitekey");
						  grecaptcha.ready(function() {  
							grecaptcha.render("sa-grecaptcha", {
								"sitekey" : site_key
						    });
							grecaptcha.execute();
						  }); 	  
						}
					});'; 
                }
				if ( ! wp_script_is( 'sainlinescript-handle-footer', 'enqueued' ) ) {
				 wp_register_script( 'sainlinescript-handle-footer', '', [], '', true );
				 wp_enqueue_script( 'sainlinescript-handle-footer'  ); 
				}
				wp_add_inline_script( "sainlinescript-handle-footer", $inline_script);
            }
        }
    }

    /**
     *  Add action smsalert
     *
     * @return void
     */
    public function addCustomAction()
    {
        // Instantiate the action class
        $smsalert_action = new Sendmsms_Action_After_Submit;

        // Register the action with form widget
        \ElementorPro\Plugin::instance()->modules_manager->get_modules('forms')->add_form_action($smsalert_action->get_name(), $smsalert_action);
    }
    
    /**
     * Update form widget controls.
     *
     * @param $widget form widget .
     *
     * @return void
     */    
    public function update_controls( $widget )
    {
        $elementor = ElementorPro\Plugin::elementor();

        $control_data = $elementor->controls_manager->get_control_from_stack($widget->get_unique_name(), 'form_fields');

        if (is_wp_error($control_data) ) {
            return;
        }

        $field_controls = [
        'sa_billing_phone' => [
                    'name'         => 'sa_billing_phone',
                    'label'        => esc_html__('Placeholder', 'sms-alert'),
                    'type'         => Elementor\Controls_Manager::TEXT,
                    'condition'    => [
                        'field_type' => $this->get_type(),
                    ],
                    'tab'          => 'content',
                    'inner_tab'    => 'form_fields_content_tab',
                    'tabs_wrapper' => 'form_fields_tabs',
        ],
        'sa_default_value' => [
        'name'         => 'sa_default_value',
        'label'        => esc_html__('Default Value', 'sms-alert'),
        'type'         => Elementor\Controls_Manager::TEXT,
        'default' => '',
        'dynamic' => [
         'active' => true,
        ],
        'condition'    => [
         'field_type' => $this->get_type(),
        ],
        'tab'          => 'advanced',
        'inner_tab'    => 'form_fields_advanced_tab',
        'tabs_wrapper' => 'form_fields_tabs',
        ],
        ];

        $control_data['fields'] = $this->inject_field_controls($control_data['fields'], $field_controls);
        $widget->update_control('form_fields', $control_data);
    }

    /**
     * Render
     *
     * @param string      $item       item
     * @param integer     $item_index item_index
     * @param Widget_Base $form       form
     *
     * @return void
     */
    public function render( $item, $item_index, $form )
    {
        $form->add_render_attribute('input' . $item_index, 'class', 'elementor-field-textual');
        
        $form->add_render_attribute('input' . $item_index, 'type', 'sa_billing_phone', true);
        $form->add_render_attribute('input' . $item_index, 'placeholder', $item['sa_billing_phone']);
        $form->add_render_attribute('input' . $item_index, 'value', $item['sa_default_value']);
        
        echo '<input ' . $form->get_render_attribute_string('input' . $item_index) . '>';
    }
}
new Elementor();

/**
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 *
 * Class Sendmsms_Action_After_Submit
 */

class Sendmsms_Action_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base
{
    /**
     * Get Name
     *
     * @return string
     */
    public function get_name()
    {
        return 'smsalert';
    }

    /**
     * Get Label
     *
     * @return string
     */
    public function get_label()
    {
        return __('SMSAlert', 'sms-alert');
    }

    /**
     * Register Settings Section
     *
     * @param $widget widget
     *
     * @return void
     */
    public function register_settings_section( $widget )
    {
        $widget->start_controls_section(
            'section_smsalert',
            [
            'label' => __('SMS Alert', 'sms-alert'),
            'condition' => [
            'submit_actions' => $this->get_name(),
            ],
            ]
        );
        
        $widget->add_control(
            'otp_verification_enable',
            [
            'label' => __('OTP verification', 'sms-alert'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('On', 'sms-alert'),
            'label_off' => __('Off', 'sms-alert'),
            'return_value' => 'true',
            'default' => 'true',
            ]
        );
        
        $widget->add_control(
            'customer_sms_enable',
            [
            'label' => __('Customer SMS', 'sms-alert'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('On', 'sms-alert'),
            'label_off' => __('Off', 'sms-alert'),
            'return_value' => 'true',
            'default' => 'true',
            ]
        );

        $widget->add_control(
            'customer_message',
            [
            'label' => __('Customer Message', 'sms-alert'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'placeholder' => __('Write yout text or use fields shortcode', 'sms-alert'),
            'label_block' => true,
            'render_type' => 'none',
            'default' => SmsAlertMessages::showMessage('DEFAULT_CONTACT_FORM_CUSTOMER_MESSAGE'),
            'classes' => '',
            'description' => __('Use fields shortcodes for send form data or write your custom text.', 'sms-alert'),
            ]
        );

        $widget->add_control(
            'admin_sms_enable',
            [
            'label' => __('Admin SMS', 'sms-alert'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('On', 'sms-alert'),
            'label_off' => __('Off', 'sms-alert'),
            'return_value' => 'true',
            'default' => 'true',
            ]
        );
        
        $widget->add_control(
            'admin_number',
            [
            'label' => __('Admin Phone', 'sms-alert'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => __('8010551055', 'sms-alert'),
            'label_block' => true,
            'render_type' => 'none',
            'classes' => '',
            'description' => __('Send Message to admin on this number', 'sms-alert'),
            ]
        );

        $widget->add_control(
            'admin_message',
            [
            'label' => __('Admin Message', 'sms-alert'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'placeholder' => __('Write yout text or use fields shortcode', 'sms-alert'),
            'label_block' => true,
            'render_type' => 'none',
            'default' => SmsAlertMessages::showMessage('DEFAULT_CONTACT_FORM_ADMIN_MESSAGE'),
            'classes' => '',
            'description' => __('Use fields shortcodes for send form data or write your custom text.', 'sms-alert'),
            'separator' => 'after',

            ]
        );

        $widget->end_controls_section();
    }


    /**
     * On Export
     *
     * @param array $element element
     *
     * @return Void
     */
    public function on_export( $element )
    {
        unset(
            $element['settings']['otp_verification_enable'],
            $element['settings']['admin_sms_enable'],
            $element['settings']['admin_number'],
            $element['settings']['admin_message'],
            $element['settings']['customer_sms_enable'],
            $element['settings']['customer_message']
        );
        return $element;
    }


    /**
     * Runs the action after submit
     *
     * @param $record       record
     * @param $ajax_handler Ajax_Handler
     *
     * @return void
     */
    public function run( $record, $ajax_handler )
    {

        if (!$ajax_handler->is_success) {
            return;
        }

        $admin_number             = $record->get_form_settings('admin_number');
        $admin_message             = $record->get_form_settings('admin_message');
        $customer_message         = $record->get_form_settings('customer_message');
        $customer_sms_enable    = $record->get_form_settings('customer_sms_enable');
        $admin_sms_enable         = $record->get_form_settings('admin_sms_enable');

        // get form fields
        $fields                  = $record->get('fields');

        if ('true' === $customer_sms_enable && '' !== $customer_message ) {

            $cust_phone = '';
            foreach ( $fields as $field ) {
                if ($field['type'] == 'sa_billing_phone' ) {
                    $cust_phone = $field['value'];
                }
            }

            $message = $this->parseSmsBody($fields, $customer_message);
            do_action('sa_send_sms', $cust_phone, $message);
        }

        if ('true' === $admin_sms_enable && '' !== $admin_message && '' !== $admin_number) {

            $message = $this->parseSmsBody($fields, $admin_message);
            do_action('sa_send_sms', $admin_number, $message);
        }
    }
 
    /**
     * Parse sms body
     *
     * @param $fields  fields
     * @param $message message
     *
     * @return void
     */
    public function parseSmsBody( $fields, $message )
    {

        $replaced_arr = array();

        foreach ( $fields as $key => $val ) {

            $replaced_arr['[field id="'.$key.'"]'] = $val['value'];
        }

        $message = str_replace(array_keys($replaced_arr), array_values($replaced_arr), $message);
        return $message;
    }

}
new Sendmsms_Action_After_Submit();
