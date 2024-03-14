<?php

namespace AOP\App\Options\Fields;

use AOP\App\Plugin;
use AOP\App\Enqueue;

class ColorPicker
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
     * @var
     */
    private $textRight;

    /**
     * @var
     */
    private $placeholder;

    /**
     * @var mixed|string
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
     * @var
     */
    private $fieldStyle;

    /**
     * @var
     */
    private $textFormat;

    /**
     * @var mixed|string
     */
    private $description;

    /**
     * ColorPicker constructor.
     *
     * @param $args
     */
    public function __construct($args)
    {
        $this->pageName     = $args['page_name'];
        $this->settingsName = $args['field_name'];
        $this->sectionName  = $this->settingsName . '_section';

        $this->defaultValue   = isset($args['default_value']) ? $args['default_value'] : '';
        $this->fieldLabel     = isset($args['field_label']) ? stripslashes($args['field_label']) : '';
        $this->description    = isset($args['description']) ? $args['description'] : '';
        $this->classAttribute = isset($args['class_attribute']) ? $args['class_attribute'] : '';

        $this->optionValue = get_option($this->settingsName);

        add_action('admin_init', function () {
            $this->optionsSettingsInit();
        });

        add_action('admin_enqueue_scripts', function () {
            $this->enqueueScriptsAndStyles();
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
            ['class' => $this->classAttribute]
        );
    }

    private function optionCallback($value)
    {
        return $this->validatedColor($value);
    }

    private function enqueueScriptsAndStyles()
    {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        wp_enqueue_script(
            Plugin::PREFIX . 'color-picker-js',
            Plugin::assetsUrl() . 'js/' . Plugin::PREFIX . 'color-picker.js',
            [],
            Enqueue::version('js/' . Plugin::PREFIX . 'color-picker.js'),
            'all'
        );
    }

    private function displayCallback()
    {
        printf('<style>%s</style>', '.button.button-small.wp-picker-clear {margin-left: 6px;min-height: 28px;}');

        printf(
            '<input type="text" name="%s" id="%s" value="%s" class="%s"/>%s',
            $this->settingsName,
            $this->settingsName,
            $this->optionValue,
            Plugin::PREFIX . 'color-picker',
            $this->description($this->description)
        );
    }
}
