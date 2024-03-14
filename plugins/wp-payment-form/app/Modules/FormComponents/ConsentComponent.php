<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

class ConsentComponent extends BaseComponent
{
    protected $valueDefault = '';

    protected $componentName = 'terms_conditions';

    public function __construct()
    {   
        parent::__construct($this->componentName, 600);

        add_filter('wppayform/maybe_conver_html_' . $this->componentName, array($this, 'convertValueToHtml'), 10, 3);
    }

    public function convertValueToHtml($values, $submission, $element)
    {
        if (is_array($values)) {
            return implode(',', $values);
        }
    }

    public function component()
    {
        return array(
            'type' => $this->componentName,
            'editor_title' => 'Consent/T&C',
            'conditional_hide' => true,
            'group' => 'input',
            'is_pro' => 'no',
            'postion_group' => 'general',
            'editor_elements' => array(
                'label' => array(
                    'label' => 'Terms Text',
                    'type' => 'textarea',
                    'group' => 'general',
                    'info' => 'Provide Terms & Conditions / Consent Text (HTML Supported)'
                ),
                'tc_description' => array(
                    'label' => 'Terms Description (optional)',
                    'type' => 'html',
                    'group' => 'general',
                    'info' => 'The full description of your terms and condition. It will show as scrollable text'
                ),
                'required' => array(
                    'label' => 'Required',
                    'type' => 'switch',
                    'group' => 'general'
                ),
                'default_value' => array(
                    'label' => 'Default Value',
                    'type' => 'text',
                    'group' => 'general',
                    'info' => 'Keep value 1 if you want to make it pre-checked by default'
                ),
                'admin_label' => array(
                    'label' => 'Admin Label',
                    'type' => 'text',
                    'group' => 'advanced'
                ),
                'wrapper_class' => array(
                    'label' => 'Field Wrapper CSS Class',
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
                'label' => __('I Agree With The Terms And Condition'),
                'required' => 'yes',
                'wrapper_class' => '',
                'admin_label' => 'Terms & Condition Agreement',
                'tc_description' => '',
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
        /**
         * 
         * move this below transaltable text to render method from consructor because textdomain doesn't load on constructor and we only need this value upon rendering
         *  
         * */ 
        $termsValue = __('Agreed', 'wp-payment-form');
        $this->valueDefault = apply_filters('wppayform/terms_value', $termsValue);

        $fieldOptions = Arr::get($element, 'field_options', false);
        $hidden_attr = Arr::get($element, 'field_options.conditional_logic_option.conditional_logic')  === 'yes' ? 'none' : 'block';
        $disable = Arr::get($fieldOptions, 'disable');

        if (!$fieldOptions || $disable) {
            return;
        }
        $controlClass = $this->elementControlClass($element);
        $inputClass = $this->elementInputClass($element);
        $inputId = 'wpf_input_' . $form->ID . '_' . $element['id'];
        $defaultValue = Arr::get($fieldOptions, 'default_value');
        $defaultValues = apply_filters('wppayform/input_default_value', $defaultValue, $element, $form);
        $controlAttributes = array(
            'data-element_type' => $this->elementName,
            'class' => $controlClass . ' wpf_consent_wrapper',
            'data-target_element' => $element['id']
        );
        if (Arr::get($fieldOptions, 'required') == 'yes') {
            $controlAttributes['data-checkbox_required'] = 'yes';
        }
        $termDescription = Arr::get($fieldOptions, 'tc_description');

        ?>
        <div style = "display : <?php echo esc_attr($hidden_attr); ?>" <?php $this->printAttributes($controlAttributes); ?>>
            <div class="wpf_multi_form_controls wpf_input_content">
                <?php
                $optionId = $element['id'] . '_' . $form->ID;
                $attributes = array(
                    'class' => 'form-check-input ' . $inputClass,
                    'type' => 'checkbox',
                    'data-required' => Arr::get($fieldOptions, 'required') == 'yes' ? 'yes' : '',
                    'required' => Arr::get($fieldOptions, 'required') == 'yes' ? 1 : 0,
                    'name' => $element['id'] . '[]',
                    'id' => $optionId,
                    'condition_id' => $element['id'],
                    'value' => $this->valueDefault
                );
                if ($defaultValues == '1' || $defaultValues == 1) {
                    $attributes['checked'] = 'true';
                }
                ?>
                <div class="form-check wpf_t_c_checks">
                    <input <?php $this->printAttributes($attributes); ?>>
                    <label class="form-check-label" for="<?php echo esc_attr($optionId); ?>">
                        <?php echo wp_kses_post(Arr::get($fieldOptions, 'label')); ?>
                    </label>
                </div>
                <?php if ($termDescription) : ?>
                    <div class="wpf_tc_scroll">
                        <?php echo wp_kses_post($termDescription); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
