<?php

namespace WPPayForm\App\Modules\FormComponents;
use WPPayForm\App\Services\CountryNames;
use WPPayForm\Framework\Support\Arr;


if (!defined('ABSPATH')) {
    exit;
}

class CustomPhoneNumber extends BaseComponent
{
    public function __construct()
    {
        parent::__construct('phone', 13);
    }

    public function component()
    {
        $phone_visable_status = apply_filters('wppayform/available_phone_number_visable_status', array(
            "all"            => "All",
            "hidden_list"    => "Hide these",
            "visible_list"   => "Show all these",
            "priority_based" => "Prioritybased"
        ));

        $country_code = CountryNames::getAll();
        return array(
            'type' => 'phone',
            'quick_checkout_form' => true,
            'editor_title' => 'Phone Number',
            'is_pro' => 'no',
            'group' => 'input',
            'postion_group' => 'general',
            'conditional_hide' => true,
            'editor_elements' => array(
                'label' => array(
                    'label' => 'Field Label',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'required' => array(
                    'label' => 'Required',
                    'type' => 'switch',
                    'group' => 'general'
                ),
                'default_country_code' => array(
                    'label' => 'Default Country',
                    'type' => 'select_option',
                    'options' => $country_code,
                    'group' => 'general',
                    'creatable' => 'yes',
                ),
                'active_list' => array(
                    'label' => 'Show status',
                    'type' => 'radio_button',
                    'options' => $phone_visable_status,
                    'group' => 'general',
                    'creatable' => 'yes'
                ),
                'priority_country_code' => array(
                    'label' => 'Country List',
                    'type' => 'select_multi_tags',
                    'options' => $country_code,
                    'group' => 'general',
                    'creatable' => 'yes'
                ),

                'admin_label' => array(
                    'label' => 'Admin Label',
                    'type' => 'number',
                    'group' => 'advanced'
                ),
                'wrapper_class' => array(
                    'label' => 'Field Wrapper CSS Class',
                    'type' => 'text',
                    'group' => 'advanced'
                ),
                'element_class' => array(
                    'label' => 'Input Element CSS Class',
                    'type' => 'text',
                    'group' => 'advanced'
                ),
                'conditional_render' => array(
                    'type' => 'conditional_render',
                    'group' => 'advanced',
                    'label' => 'Conditional render',
                    'selection_type' => 'Conditional logic',
                    'conditional_logic' => array(
                        'yes' => 'Yes',
                        'no' => 'No'
                    ),
                    'conditional_type' => array(
                        'any' => 'Any',
                        'all' => 'All'
                    ),
                ),
            ),
            'field_options' => array(
                'label' => 'Phone Number',
                'placeholder' => '',
                'required' => 'no',
                'active_list' => 'all',
                'conditional_logic_option' => array(
                    'conditional_logic' => 'no',
                    'conditional_type'  => 'any',
                    'options' => array(
                        array(
                            'target_field' => '',
                            'condition' => '',
                            'value' => ''
                        )
                    ),
                ),
            )
        );
    }

    public function render($element, $form, $elements)
    {
        wp_enqueue_script('intlTelInput');
        wp_enqueue_style('intlTelInput');
        wp_enqueue_script('intlTelInputUtils');
        $element['type'] = 'tel';
        $element['field_options']['options'] = Arr::get($element, 'editor_elements.default_country_code.options');
        $this->renderPhoneInput($element, $form);
        $this->phone_settings($element, $form);
?>

    <?php

        // $this->renderPhoneInput($element, $form);
        // $this->renderSelectInput($element, $form);
    }

    private function phone_settings($element, $form)
    {
        add_action('wp_footer', function () use ($element, $form) {
            $inputId = 'phone_code_wpf_input_' . $form->ID . '_' . str_replace([' ', '[', ']'], '_', $element['id']) . '_input';
            $default_country_code = Arr::get($element, 'field_options.default_country_code');
            $isRequired = Arr::get($element, 'field_options.required');
            $hiddenId = 'phone_code_wpf_input_' . $form->ID . '_' . str_replace([' ', '[', ']'], '_', $element['id']) . '_input_hidden';
            $priority_country = Arr::get($element, 'field_options.priority_country_code');
            $active_list = Arr::get($element, 'field_options.active_list');
            ?>
            <script>
                function getIp(callback) {
                    fetch('https://ipinfo.io/json?token=8429776b978b16', {
                            headers: {
                                'Accept': 'application/json'
                            }
                        })
                        .then((resp) => resp.json())
                        .catch(() => {
                            return {
                                country: 'us',
                            };
                        })
                        .then((resp) => callback(resp.country));
                }
                jQuery(document).ready(function($) {
                    var input = document.querySelector("#<?php echo esc_attr($inputId); ?>");
                    var errorMsg = document.querySelector("#error_<?php echo esc_attr($inputId); ?>");
                    let show_type = "<?php echo esc_attr($active_list); ?>";
                    // error with intlTelInput return
                    var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
                    // convert priority countries php array to js array
                    //TODO there json_encode dosen't work
                    var priority_countries = new Array();
                    <?php
                        if(is_array($priority_country)) {
                            foreach ($priority_country as $key => $val) { ?>
                            priority_countries.push('<?php echo esc_attr($val); ?>');
                    <?php }} ?>

                    // initialise plugin
                    var iti = window.intlTelInput(input, {
                        initialCountry: "<?php echo esc_attr($default_country_code); ?>",
                        preferredCountries: show_type === 'priority_based' ? priority_countries : '',
                        onlyCountries: show_type === 'visible_list' ? priority_countries : '',
                        excludeCountries: show_type === 'hidden_list' ? priority_countries : '',
                        geoIpLookup: getIp,
                        utilsScript: "../../build/js/utils.js?1638200991544"
                    });
                    // reset code
                    var reset = function() {
                        errorMsg.innerHTML = "";
                    };

                    $('button.wpf_submit_button, .wpf_step_button').click(() => {
                        reset();
                        if (input.value.trim()) {
                            if (iti.isValidNumber()) {
                                let inputIndex = $(input).attr('data-intl-tel-input-id');
                                let counrty_code = $(`div[aria-owns=iti-${inputIndex}__country-listbox]`).attr('title').split(': ')
                                let full_phone_number = counrty_code[1]?.concat(` ${input.value.trim()}`)
                                $('#<?php echo esc_attr($hiddenId); ?>').val(full_phone_number)
                            } else {
                                var errorCode = iti.getValidationError();
                                errorMsg.innerHTML = errorMap[errorCode];
                            }
                        }
                        if ("<?php echo esc_attr($isRequired); ?>" === 'yes' && !$('#<?php echo esc_attr($inputId); ?>').val()) {
                            errorMsg.innerHTML = 'is required';
                        }
                    })

                    input.addEventListener('change', reset);
                    input.addEventListener('keyup', reset);
                })
            </script>
            <?php
        }, 9999);
    }
}
