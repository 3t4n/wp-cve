<?php

namespace AOP\App\Options\Fields;

use AOP\App\Plugin;

class Checkbox
{
    use TraitGeneral;

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
    private $optionValue;

    /**
     * @var mixed|string
     */
    private $classAttribute;

    /**
     * @var mixed|string
     */
    private $description;

    /**
     * Checkbox constructor.
     *
     * @param $args
     */
    public function __construct($args)
    {
        $this->pageName = $args['page_name'];
        $this->settingsName = $args['field_name'];
        $this->sectionName = $this->settingsName . '_section';

        $this->optionValue = checked(1, get_option($this->settingsName), false);

        $this->fieldLabel = isset($args['field_label']) ? stripslashes($args['field_label']) : '';
        $this->textRight = isset($args['text_right']) ? stripslashes($args['text_right']) : '';
        $this->classAttribute = isset($args['class_attribute']) ? $args['class_attribute'] : '';
        $this->description = isset($args['description']) ? $args['description'] : '';

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
                'type' => 'number',
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
     * @return int
     */
    private function optionCallback($value)
    {
        if (has_filter(Plugin::PREFIX_ . 'sanitize_option_' . $this->settingsName)) {
            return apply_filters(Plugin::PREFIX_ . 'sanitize_option_' . $this->settingsName, $value);
        }

        return ($value === '1') ? 1 : 0;
    }

    private function displayCallback()
    {
        print('<fieldset>');

        printf('<legend class="screen-reader-text"><span>%s</span></legend>', $this->fieldLabel);

        printf(
            '<label for="%s"><input type="checkbox" id="%s" name="%s" value="1" %s />%s</label>%s',
            $this->settingsName,
            $this->settingsName,
            $this->settingsName,
            $this->optionValue,
            $this->textRight,
            $this->description($this->description)
        );

        print('</fieldset>');
    }
}
