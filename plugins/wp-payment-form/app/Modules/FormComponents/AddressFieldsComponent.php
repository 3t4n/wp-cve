<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\Framework\Support\Arr;
use WPPayForm\App\Services\CountryNames;

if (!defined('ABSPATH')) {
    exit;
}

class AddressFieldsComponent extends BaseComponent
{
    protected $componentName = 'address_input';

    public function __construct()
    {
        parent::__construct($this->componentName, 600);
        add_filter('wppayform/submitted_value_' . $this->componentName, array($this, 'formatValue'), 10, 1);
    }

    public function component()
    {
        return array(
            'type' => $this->componentName,
            'editor_title' => 'Address Field',
            'group' => 'input',
            'postion_group' => 'general',
            'is_pro' => 'no',
            'editor_elements' => array(
                'label' => array(
                    'label' => 'Label',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'subfields' => [
                    'label' => 'Address Fields',
                    'type' => 'address_subfields',
                    'group' => 'general',
                    'fields' => [
                        'address_line_1' => 'Address Line 1',
                        'address_line_2' => 'Address Line 2',
                        'city' => 'City',
                        'state' => 'State',
                        'zip_code' => 'ZIP Code',
                        'country' => 'Country'
                    ]
                ],
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
                'label' => 'Address',
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
                'subfields' => [
                    'address_line_1' => [
                        'isNumberic' => 'no',
                        'label' => 'Address Line 1',
                        'field_options' => array(
                            'label' => 'Address Line 1',
                        ),
                        'placeholder' => 'Address Line 1',
                        'visibility' => 'yes',
                        'required' => 'yes',
                        'type' => 'text',
                        'id' => 'address_line_1',
                        'default_value' => ''
                    ],
                    'address_line_2' => [
                        'isNumberic' => 'no',
                        'label' => 'Address Line 2',
                        'field_options' => array(
                            'label' => 'Address Line 2',
                        ),
                        'placeholder' => 'Address Line 2',
                        'visibility' => 'yes',
                        'required' => 'no',
                        'type' => 'text',
                        'id' => 'address_line_2',
                        'default_value' => ''
                    ],
                    'city' => [
                        'isNumberic' => 'no',
                        'label' => __('City', 'wp-payment-form'),
                        'field_options' => array(
                            'label' => 'City',
                        ),
                        'placeholder' => __('City', 'wp-payment-form'),
                        'visibility' => 'yes',
                        'required' => 'no',
                        'type' => 'text',
                        'id' => 'city',
                        'default_value' => ''
                    ],
                    'state' => [
                        'isNumberic' => 'no',
                        'label' => __('State', 'wp-payment-form'),
                        'placeholder' => __('State', 'wp-payment-form'),
                        'field_options' => array(
                            'label' => 'State',
                        ),
                        'visibility' => 'yes',
                        'required' => 'yes',
                        'type' => 'text',
                        'id' => 'state',
                        'default_value' => ''
                    ],
                    'zip_code' => [
                        'isNumberic' => 'no',
                        'label' => __('ZIP Code', 'wp-payment-form'),
                        'field_options' => array(
                            'label' => 'ZIP Code',
                        ),
                        'placeholder' => __('ZIP Code', 'wp-payment-form'),
                        'visibility' => 'yes',
                        'required' => 'no',
                        'type' => 'text',
                        'id' => 'zip_code',
                        'default_value' => ''
                    ],
                    'country' => [
                        'isNumberic' => 'no',
                        'label' => __('Country', 'wp-payment-form'),
                        'field_options' => array(
                            'label' => 'Country',
                        ),
                        'placeholder' => __('Select Country', 'wp-payment-form'),
                        'visibility' => 'yes',
                        'required' => 'yes',
                        'type' => 'select',
                        'id' => 'country',
                        'default_value' => ''
                    ],
                ]
            )
        );
    }

    public function formatValue($value)
    {
        if (is_array($value)) {
            $value = array_filter($value);
            if (!empty($value['country'])) {
                $countryCode = $value['country'];
                $countries = CountryNames::getAll();
                if (isset($countries[$countryCode])) {
                    $value['country'] = $countries[$countryCode];
                }
            }
            $value = implode(', ', $value);
        }

        return $value;
    }

    public function render($element, $form, $elements)
    {
        $subFields = Arr::get($element, 'field_options.subfields', []);
        $disable = Arr::get($element, 'field_options.disable', false);
        $hasCondition = Arr::get($element, 'field_options.conditional_logic_option.conditional_logic')  === 'yes' ? true : false;
        $hiddenAttr = Arr::get($element, 'field_options.conditional_logic_option.conditional_logic')  === 'yes' ? 'display:none' : 'display:block';
        if ($disable) {
            return;
        }
        $inputFields = [];
        $fieldName = Arr::get($element, 'id');
        foreach ($subFields as $fieldKey => $subField) {
            $field = $this->getFormattedElement($fieldKey, $fieldName, $subField, $form);
            if ($field) {
                $inputFields[] = $field;
            }
        }

        echo $hasCondition ? "<div required_id=" . $fieldName . " style=" . $hiddenAttr . " class='wpf_address_wrapper wpf_has_condition'>" : '<div class="wpf_address_wrapper">';
        if ($addressLabel = Arr::get($element, 'field_options.label')) {
            echo '<label condition_id=' . $fieldName . ' class="wpf_address_heading">' . $addressLabel . '</label>';
        }
        foreach (array_chunk($inputFields, 2) as $itemGroup) {
            echo '<div class="wpf-t-container">';
            foreach ($itemGroup as $field) {
                echo '<div class="wpf-t-cell">';
                $this->renderSubField($field, $form);
                echo '</div>';
            }
            echo '</div>';
        }
        echo '</div>';
    }

    private function getFormattedElement($fieldKey, $fieldName, $field, $form)
    {
        if (Arr::get($field, 'visibility') != 'yes') {
            return false;
        }

        $element = [
            'type' => Arr::get($field, 'type', 'text'),
            'group' => 'input',
            'postion_group' => 'general',
            'editor_elements' => [],
            'field_options' => [
                'label' => Arr::get($field, 'label'),
                'placeholder' => Arr::get($field, 'placeholder'),
                'required' => Arr::get($field, 'required'),
                'default_value' => Arr::get($field, 'default_value')
            ],
            'id' => $fieldName . '[' . $field['id'] . ']',
            'condition_id' => $field['id']
        ];

        if ($field['id'] == 'country') {
            $countries = CountryNames::getAll();
            $countries = apply_filters('wppayform/address_countries', $countries, $form);
            $countriesOptions = [];
            foreach ($countries as $isoCode => $country) {
                $countriesOptions[] = [
                    'label' => $country,
                    'value' => $isoCode
                ];
            }
            $element['field_options']['options'] = $countriesOptions;
            $element['field_options']['type'] = 'select';
        }

        return $element;
    }

    private function renderSubField($field, $form)
    {
        if ($field['type'] == 'select') {
            $this->renderSelectInput($field, $form);
        } else {
            $this->renderNormalInput($field, $form);
        }
    }
}
