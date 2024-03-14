<?php

namespace AOP\App\Admin\AdminPages\Settings\Options;

class Checkbox
{
    private $pageName;
    private $settingsName;
    private $sectionName;
    private $fieldLabel;
    private $textRight;
    private $classAttribute;
    private $description;

    public function __construct($args)
    {
        $this->pageName     = $args['page_name'];
        $this->settingsName = $args['setting_name'];
        $this->sectionName  = $this->settingsName . '_section';

        $this->fieldLabel     = isset($args['field_label']) ? stripslashes($args['field_label']) : '';
        $this->textRight      = isset($args['text_right']) ? stripslashes($args['text_right']) : '';
        $this->classAttribute = isset($args['class_name']) ? $args['class_name'] : '';
        $this->description    = isset($args['description']) ? $args['description'] : '';

        add_action('admin_init', [$this, 'view']);
    }

    public function view()
    {
        register_setting(
            $this->pageName,
            $this->settingsName,
            [
                'group' => $this->pageName,
                'sanitize_callback' => [$this, 'optionCallback']
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
            [$this, 'displayCallback'],
            $this->pageName,
            $this->sectionName,
            [
                'label_for' => $this->settingsName,
                'class' => $this->classAttribute
            ]
        );
    }

    public function optionCallback($value)
    {
        return ($value === '1') ? 1 : 0;
    }

    public function displayCallback()
    {
        $option = get_option($this->settingsName);

        if (!$option) {
            add_option($this->settingsName, 0, '', 'no');
        }

        $optionValue = checked(1, $option, false);

        print('<fieldset>');

        printf('<legend class="screen-reader-text"><span>%s</span></legend>', $this->fieldLabel);

        printf(
            '<label for="%s"><input type="checkbox" id="%s" name="%s" value="1" %s />%s</label><p class="description">%s</p>',
            $this->settingsName,
            $this->settingsName,
            $this->settingsName,
            $optionValue,
            $this->textRight,
            $this->description
        );

        print('</fieldset>');
    }
}
