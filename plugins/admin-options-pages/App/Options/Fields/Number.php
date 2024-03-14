<?php

namespace AOP\App\Options\Fields;

class Number
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
     * @var mixed|string
     */
    private $defaultValue;

    /**
     * @var mixed|string
     */
    private $minValue;

    /**
     * @var mixed|string
     */
    private $maxValue;

    /**
     * @var mixed|string
     */
    private $decimals;

    /**
     * @var mixed|string
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
     * Number constructor.
     *
     * @param $args
     */
    public function __construct($args)
    {
        $this->pageName     = $args['page_name'];
        $this->settingsName = $args['field_name'];
        $this->sectionName  = $this->settingsName . '_section';

        $this->defaultValue   = isset($args['default_value']) ? $args['default_value'] : '';
        $this->minValue       = isset($args['min_value']) ? $args['min_value'] : '';
        $this->maxValue       = isset($args['max_value']) ? $args['max_value'] : '';
        $this->decimals       = isset($args['decimals']) ? $args['decimals'] : '';
        $this->fieldLabel     = isset($args['field_label']) ? stripslashes($args['field_label']) : '';
        $this->textRight      = isset($args['text_right']) ? stripslashes($args['text_right']) : '';
        $this->description    = isset($args['description']) ? $args['description'] : '';
        $this->classAttribute = isset($args['class_attribute']) ? $args['class_attribute'] : '';

        $this->optionValue = get_option($this->settingsName);

        if ($this->optionValue === false) {
            $this->optionValue = $this->defaultValue;
        }

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
     * @return string
     */
    private function valueSteps()
    {
        if ($this->decimals === '0') {
            return 'step="1"';
        }

        if ($this->decimals === '1') {
            return 'step="0.1"';
        }

        if ($this->decimals === '2') {
            return 'step="0.01"';
        }

        return 'step="1"';
    }

    /**
     * @param $value
     *
     * @return false|float|string
     */
    private function optionCallback($value)
    {
        $floatValue = (float) $value;

        if (!is_numeric($floatValue)) {
            return;
        }

        if ($this->minValue) {
            if ($floatValue < $this->minValue) {
                return '';
            }
        }

        if ($this->maxValue) {
            if ($floatValue > $this->maxValue) {
                return '';
            }
        }

        return round($floatValue, $this->decimals);
    }

    private function displayCallback()
    {
        printf(
            '<input type="number" name="%s" id="%s" value="%s" %s %s %s class="small-text"/> %s%s',
            $this->settingsName,
            $this->settingsName,
            $this->optionValue,
            $this->minValue ? 'min="' . $this->minValue . '"' : 'min="0"',
            $this->maxValue ? 'max="' . $this->maxValue . '"' : '',
            $this->valueSteps(),
            $this->textRight,
            $this->description($this->description)
        );
    }
}
