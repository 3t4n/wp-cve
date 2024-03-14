<?php

namespace AOP\App\Options\Fields;

use AOP\App\Plugin;

class WysiwygEditor
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
    private $toggleShowMediaUploadButtons;

    /**
     * @var mixed|string
     */
    private $description;

    /**
     * @var mixed|string
     */
    private $toolbar;

    /**
     * TextField constructor.
     *
     * @param $args
     */
    public function __construct($args)
    {
        $this->pageName     = $args['page_name'];
        $this->settingsName = $args['field_name'];
        $this->sectionName  = $this->settingsName . '_section';

        $this->placeholder = isset($args['placeholder']) ? stripslashes($args['placeholder']) : '';

        $this->fieldLabel                   = isset($args['field_label']) ? stripslashes($args['field_label']) : '';
        $this->description                  = isset($args['description']) ? $args['description'] : '';
        $this->classAttribute               = isset($args['class_attribute']) ? $args['class_attribute'] : '';
        $this->toggleShowMediaUploadButtons = isset($args['toggle_show_media_upload_buttons']) ? $args['toggle_show_media_upload_buttons'] : '';
        $this->toolbar                      = isset($args['toolbar']) ? $args['toolbar'] : '';

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

        return wp_kses_post($value);
    }

    private function displayCallback()
    {
        if ($this->description) {
            print('<p>' . $this->description . '</p>');
        }

        $editorSettings = [
            'media_buttons' => $this->toggleShowMediaUploadButtons === 'on',
            'teeny' => $this->toolbar === 'basic',
            'wpautop' => false
        ];

        wp_editor(
            wpautop($this->optionValue),
            $this->settingsName,
            apply_filters(Plugin::PREFIX_ . 'editor_settings_' . $this->settingsName, $editorSettings)
        );
    }
}
