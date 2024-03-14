<?php

namespace AOP\App\Options\Fields;

use AOP\App\Plugin;

class TextField
{
    use TraitGeneral;
    use TraitValidation;

    /**
     * @var mixed
     */
    private $pageName;

    /**
     * @var mixed
     */
    private $settingsName;

    /**
     * @var string
     */
    private $sectionName;

    /**
     * @var string
     */
    private $fieldLabel;

    /**
     * @var string
     */
    private $textRight;

    /**
     * @var string
     */
    private $placeholder;

    /**
     * @var string
     */
    private $defaultValue;

    /**
     * @var bool|mixed|void
     */
    private $optionValue;

    /**
     * @var mixed|string
     */
    private $classAttribute;

    /**
     * @var mixed|string
     */
    private $fieldStyle;

    /**
     * @var mixed|string
     */
    private $textFormat;

    /**
     * @var mixed|string
     */
    private $description;

    /**
     * TextField constructor.
     *
     * @param $args
     */
    public function __construct($args)
    {
        $this->pageName = $args['page_name'];
        $this->settingsName = $args['field_name'];
        $this->sectionName = $this->settingsName . '_section';

        $this->placeholder = isset($args['placeholder']) ? stripslashes($args['placeholder']) : '';
        $this->defaultValue = isset($args['default_value']) ? stripslashes($args['default_value']) : '';
        $this->fieldLabel = isset($args['field_label']) ? stripslashes($args['field_label']) : '';
        $this->textRight = isset($args['text_right']) ? stripslashes($args['text_right']) : '';
        $this->description = isset($args['description']) ? $args['description'] : '';
        $this->classAttribute = isset($args['class_attribute']) ? $args['class_attribute'] : '';
        $this->fieldStyle = isset($args['field_style']) ? $args['field_style'] : '';
        $this->textFormat = isset($args['toggle_text_format']) ? $args['toggle_text_format'] : '';

        $this->optionValue = get_option($this->settingsName);

        add_action('admin_init', function () {
            $this->optionsSettingsInit();
        });
    }

    private function optionsSettingsInit()
    {
        register_setting(
            $this->pageName,
            $this->settingsName,
            [
                'type' => 'string',
                'group' => $this->pageName,
                'sanitize_callback' => function ($value) {
                    return $this->optionCallback($value);
                }
            ]
        );

        add_settings_section(
            $this->sectionName,
            '',
            '',
            $this->pageName
        );

        add_settings_field(
            $this->settingsName,
            $this->fieldLabel,
            function () {
                $this->displayCallback();
            },
            $this->pageName,
            $this->sectionName,
            [
                'label_for' => $this->settingsName,
                'class' => $this->classAttribute
            ]
        );
    }

    /**
     * @param $value
     *
     * @return mixed|string|void
     */
    private function optionCallback($value)
    {
        if (has_filter(Plugin::PREFIX_ . 'sanitize_option_' . $this->settingsName)) {
            return apply_filters(Plugin::PREFIX_ . 'sanitize_option_' . $this->settingsName, $value);
        }

        return $this->validatedTextField($value);
    }

    private function displayCallback()
    {
        printf(
            '<input type="text" name="%s" id="%s" value="%s" %s class="%s%s"/> %s%s',
            $this->settingsName,
            $this->settingsName,
            esc_html($this->optionValue),
            $this->placeholder ? 'placeholder="' . $this->placeholder . '"' : '',
            strtr($this->fieldStyle, '_', '-'),
            $this->textFormat ? ' code' : '',
            $this->textRight,
            $this->description($this->description)
        );
    }
}
