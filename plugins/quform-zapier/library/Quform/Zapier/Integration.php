<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */
class Quform_Zapier_Integration
{
    /**
     * @var array
     */
    protected $config = array();

    /**
     * @var Quform_Form
     */
    protected $form;

    /**
     * @var Quform_Zapier_Options
     */
    protected $options;

    public function __construct(array $config, Quform_Form $form, Quform_Zapier_Options $options)
    {
        $this->setConfig($config);
        $this->form = $form;
        $this->options = $options;
    }

    /**
     * Run the integration
     *
     * @param   array  $result  The result to return to the form processor, an empty array by default
     * @return  array
     */
    public function run(array $result)
    {
        if ( ! Quform::isNonEmptyString($this->config('webhookUrl'))) {
            return $result;
        }

        $data = array_merge($this->getFormValues(), $this->getAdditionalFields());

        $data = apply_filters('quform_zapier_integration_data', $data, $this->form, $this);
        $data = apply_filters('quform_zapier_integration_data_' . $this->config('id'), $data, $this->form, $this);

        $response = wp_remote_post($this->config('webhookUrl'), array('body' => $data));

        $result = apply_filters('quform_zapier_response', $result, $response, $this->form, $this);
        $result = apply_filters('quform_zapier_response' . $this->config('id'), $result, $response, $this->form, $this);

        return $result;
    }

    /**
     * Get the form values to send to Zapier
     *
     * @return array
     */
    protected function getFormValues()
    {
        $values = array();

        foreach ($this->form->getRecursiveIterator() as $element) {
            if ($element instanceof Quform_Element_Field) {
                $values[$element->getAdminLabel()] = $element->getValueText();
            }
        }

        $values = apply_filters('quform_zapier_integration_form_values', $values, $this->form, $this);
        $values = apply_filters('quform_zapier_integration_form_values_' . $this->config('id'), $values, $this->form, $this);

        return $values;
    }

    /**
     * Get the additional field values to send to Zapier
     *
     * @return array
     */
    protected function getAdditionalFields()
    {
        $fields = array();

        foreach ($this->config('additionalFields') as $field) {
            if (Quform::isNonEmptyString($field['key']) && Quform::isNonEmptyString($field['value'])) {
                $fields[$field['key']] = $this->form->replaceVariables($field['value']);
            }
        }

        $fields = apply_filters('quform_zapier_integration_additional_fields', $fields, $this->form, $this);
        $fields = apply_filters('quform_zapier_integration_additional_fields_' . $this->config('id'), $fields, $this->form, $this);

        return $fields;
    }

    /**
     * Returns the config value for the given $key
     *
     * @param   string  $key
     * @param   null    $default
     * @return  mixed   The config value or $default if not set
     */
    public function config($key, $default = null)
    {
        $value = Quform::get($this->config, $key, $default);

        if ($value === null) {
            $value = Quform::get(call_user_func(array(get_class($this), 'getDefaultConfig')), $key, $default);
        }

        return $value;
    }

    /**
     * Set the config value for the given $key or multiple values using an array
     *
     * @param   string|array  $key    Key or array of key/values
     * @param   mixed         $value  Value or null if $key is array
     * @return  $this
     */
    public function setConfig($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->config[$k] = $v;
            }
        } else {
            $this->config[$key] = $value;
        }

        return $this;
    }

    /**
     * Get the default integration configuration
     *
     * @param   string|null  $key  Get the config by key, if omitted the full config is returned
     * @return  array
     */
    public static function getDefaultConfig($key = null)
    {
        $config = apply_filters('quform_zapier_default_integration', array(
            'name' => '',
            'active' => true,
            'trashed' => false,
            'formId' => null,
            'webhookUrl' => '',
            'logicEnabled' => false,
            'logicAction' => true,
            'logicMatch' => 'all',
            'logicRules' => array(),
            'additionalFields' => array()
        ));

        if (Quform::isNonEmptyString($key)) {
            return Quform::get($config, $key);
        }

        return $config;
    }
}
