<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

class DateComponent extends BaseComponent
{
    public function __construct()
    {
        parent::__construct('date', 19);
    }

    public function component()
    {
        $dateFormats = apply_filters('wppayform/available_date_formats', array(
            'm/d/Y' => 'm/d/Y - (Ex: 04/28/2018)', // USA
            'd/m/Y' => 'd/m/Y - (Ex: 28/04/2018)', // Canada, UK
            'd.m.Y' => 'd.m.Y - (Ex: 28.04.2019)', // Germany
            'n/j/y' => 'n/j/y - (Ex: 4/28/18)',
            'm/d/y' => 'm/d/y - (Ex: 04/28/18)',
            'M/d/Y' => 'M/d/Y - (Ex: Apr/28/2018)',
            'y/m/d' => 'y/m/d - (Ex: 18/04/28)',
            'Y-m-d' => 'Y-m-d - (Ex: 2018-04-28)',
            'd-M-y' => 'd-M-y - (Ex: 28-Apr-18)',
            'm/d/Y h:i K' => 'm/d/Y h:i K - (Ex: 04/28/2018 08:55 PM)', // USA
            'm/d/Y H:i' => 'm/d/Y H:i - (Ex: 04/28/2018 20:55)', // USA
            'd/m/Y h:i K' => 'd/m/Y h:i K - (Ex: 28/04/2018 08:55 PM)', // Canada, UK
            'd/m/Y H:i' => 'd/m/Y H:i - (Ex: 28/04/2018 20:55)', // Canada, UK
            'd.m.Y h:i K' => 'd.m.Y h:i K - (Ex: 28.04.2019 08:55 PM)', // Germany
            'd.m.Y H:i' => 'd.m.Y H:i - (Ex: 28.04.2019 20:55)', // Germany
            'h:i K' => 'h:i K (Only Time Ex: 08:55 PM)',
            'H:i' => 'H:i (Only Time Ex: 20:55)',
        ));

        return array(
            'type' => 'date',
            'quick_checkout_form' => true,
            'editor_title' => 'Date & Time Field',
            'group' => 'input',
            'is_pro' => 'no',
            'postion_group' => 'general',
            'editor_elements' => array(
                'label' => array(
                    'label' => 'Field Label',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'placeholder' => array(
                    'label' => 'Placeholder',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'required' => array(
                    'label' => 'Required',
                    'type' => 'switch',
                    'group' => 'general'
                ),
                'date_format' => array(
                    'label' => 'Date Format',
                    'type' => 'select_option',
                    'options' => $dateFormats,
                    'group' => 'general',
                    'creatable' => 'yes',
                    'info' => 'To create your own format check this <a target="_blank" href="https://paymattic.com/docs/date-formats-customization/">documentation</a>'
                    ),
                'default_value' => array(
                    'label' => 'Default Value',
                    'type' => 'text',
                    'group' => 'general'
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
                'element_class' => array(
                    'label' => 'Input Element CSS Class',
                    'type' => 'text',
                    'group' => 'advanced'
                ),
                'custom_config' => array(
                    'label' => 'Advanced Date Configuration',
                    'type' => 'custom_date_config',
                    'group' => 'advanced',
                    'css_class' => 'wpf_code_editor',
                    'placeholder' => '{}',
                    'tips' => 'You can write your own date configuration in jSON. Please write valid configuration as per flatpickr config.',
                    'info' => 'Only valid JS object will work. Please check <a target="_blank" href="https://paymattic.com/docs/date-formats-customization/">the documentation for available config options</a>'
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
                'label' => 'Date & Time',
                'placeholder' => '',
                'required' => 'no',
                'date_format' => 'm/d/Y',
                'custom_config' => '',
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
        wp_enqueue_script('flatpickr');
        wp_enqueue_style('flatpickr');

        $fieldOptions = Arr::get($element, 'field_options', false);
        $disable = Arr::get($fieldOptions, 'disable', false);
        $hiddenAttr = Arr::get($element, 'field_options.conditional_logic_option.conditional_logic')  === 'yes' ? 'none' : 'block';
        $has_pro = defined('WPPAYFORMHASPRO') && WPPAYFORMHASPRO;
        $displayValue = $has_pro === true ? $hiddenAttr : '';
        if ($disable) {
            return;
        }

        if (!$fieldOptions) {
            return;
        }
        $defaultValue = apply_filters('wppayform/input_default_value', Arr::get($fieldOptions, 'default_value'), $element, $form);

        $controlClass = $this->elementControlClass($element);
        $inputClass = $this->elementInputClass($element);
        $inputId = 'wpf_input_' . $form->ID . '_' . $element['id'];
        $attributes = array(
            'data-required' => Arr::get($fieldOptions, 'required'),
            'data-type' => 'input',
            'name' => $element['id'],
            'placeholder' => Arr::get($fieldOptions, 'placeholder'),
            'value' => $defaultValue,
            'type' => 'text',
            'id' => $inputId,
            'class' => $inputClass . ' wpf_date_field',
        );

        $config = $this->getDateFormatConfigJSON($fieldOptions, $form);
        $customConfig = $this->getCustomConfig($fieldOptions, $form);
       
        $this->loadToFooter($config, $customConfig, $form, $attributes['id']);

        if (Arr::get($fieldOptions, 'required') == 'yes') {
            $attributes['required'] = true;
        }
        ?>
        <div style = "display : <?php echo esc_attr($displayValue); ?>" id="wpf_<?php echo esc_attr($this->elementName); ?>" data-element_type="<?php echo esc_attr($this->elementName); ?>"
             class="<?php echo esc_attr($controlClass); ?>">
            <?php $this->buildLabel($fieldOptions, $form, array('for' => $inputId)); ?>
            <div class="wpf_input_content">
                <input <?php $this->printAttributes($attributes); ?> />
            </div>
        </div>
        <?php
    }

    private function getDateFormatConfigJSON($fieldOptions, $form)
    {
        // We are converting moment.js format to flatpickr format
        $fieldOptions['date_format'] = $this->convertDateFormat($fieldOptions['date_format']);
        $dateFormat = Arr::get($fieldOptions, 'date_format');
        
        if (!$dateFormat) {
            $dateFormat = 'm/d/Y';
        }

        $config = apply_filters('wppayform/frontend_date_format', array(
            'dateFormat' => $dateFormat,
            'enableTime' => $this->hasTime($dateFormat),
            'noCalendar' => !$this->hasDate($dateFormat),
        ), $fieldOptions, $form);
        
        return json_encode($config);
    }

    public function getCustomConfig($fieldOptions, $form)
    {
        $customConfigObject = Arr::get($fieldOptions, 'custom_config');
        
       if (!$customConfigObject || substr($customConfigObject, 0, 1) != '{' || substr($customConfigObject, -1) != '}') {
            $customConfigObject = '{}';
        }

        return $customConfigObject;
    }

    private function loadToFooter($config, $customConfigObject, $form, $id)
	{
		add_action('wp_footer', function () use ($config, $customConfigObject, $id, $form) {
			?>
            <script type="text/javascript">
                jQuery(document).ready(function ($, $customConfigObject) {
                  
                    function initPicker() {
                        if(typeof flatpickr == 'undefined') {
                            return;
                        }
                        flatpickr.localize(window.wp_payform_general.date_i18n);
                        var config = <?php echo $config ?>;

                        try {
                            var customConfig =  <?php echo $customConfigObject; ?> 
                        } catch (e) {
                            var customConfig = {};
                        }

                        var config = $.extend({}, config, customConfig);
                        
                        if (!config.locale) {
                            config.locale = 'default';
                        }

                        if(jQuery('#<?php echo esc_attr($id); ?>').length) {
                            flatpickr('#<?php echo esc_attr($id); ?>', config);
                        }
                    }
                    initPicker();
                   
                });
            </script>
			<?php
		}, 99999);
        
    }

    private function hasTime($string)
    {
        $timeStrings = ['H', 'h', 'G', 'i', 'S', 's', 'K'];
        foreach ($timeStrings as $timeString) {
            if (strpos($string, $timeString) != false) {
                return true;
            }
        }
        return false;
    }

    private function hasDate($string)
    {
        $dateStrings = ['d', 'D', 'l', 'j', 'J', 'w', 'W', 'F', 'm', 'n', 'M', 'U', 'Y', 'y', 'Z'];
        foreach ($dateStrings as $dateString) {
            if (strpos($string, $dateString) != false) {
                return true;
            }
        }
        return false;
    }

    private function convertDateFormat($dateFormat)
    {
        $oldFormats = [
            'M/D/YYYY' => 'n/j/Y',
            'M/D/YY' => 'n/j/YY',
            'MM/DD/YY' => 'm/d/y',
            'MM/DD/YYYY' => 'MM/DD/Y',
            'MMM/DD/YYYY' => 'MMM/DD/Y',
            'YY/MM/DD' => 'y/m/d',
            'YYYY-MM-DD' => 'Y-m-d',
            'DD-MMM-YY' => 'd-M-y',
        ];

        $dateFormat = str_replace(array_keys($oldFormats), array_values($oldFormats), $dateFormat);

        return $dateFormat;
    }
}
