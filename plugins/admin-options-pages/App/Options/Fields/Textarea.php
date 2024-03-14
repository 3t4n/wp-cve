<?php

namespace AOP\App\Options\Fields;

use AOP\App\Plugin;

class Textarea
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
    private $placeholder;

    /**
     * @var string
     */
    private $defaultValue;

    /**
     * @var string
     */
    private $fieldLabel;

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
    private $description;

    /**
     * @var mixed|string
     */
    private $descriptionItalic;

    /**
     * @var bool|mixed|void
     */
    private $optionValue;

    /**
     * Textarea constructor.
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
        $this->classAttribute = isset($args['class_attribute']) ? $args['class_attribute'] : '';
        $this->fieldStyle = isset($args['field_style']) ? $args['field_style'] : '';
        $this->description = isset($args['description']) ? $args['description'] : '';
        $this->descriptionItalic = isset($args['description_italic']) ? $args['description_italic'] : '';

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
            '<textarea rows="10" cols="80" name="%s" id="%s" %s class="%s"/>%s</textarea>%s',
            $this->settingsName,
            $this->settingsName,
            $this->placeholder ? 'placeholder="' . $this->placeholder . '"' : '',
            strtr($this->fieldStyle, '_', '-'),
            $this->optionValue,
            $this->description($this->description, $this->descriptionItalic)
        );
    }
}
