<?php

namespace AOP\App\Options\Fields;

use AOP\App\Plugin;
use AOP\Lib\Illuminate\Support\Collection;

class Select
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
     * @var bool|mixed|void
     */
    private $optionValue;

    /**
     * @var Collection
     */
    private $tableList;

    /**
     * @var mixed|string
     */
    private $classAttribute;

    /**
     * @var mixed|string
     */
    private $description;

    /**
     * RadioButton constructor.
     *
     * @param $args
     */
    public function __construct($args)
    {
        $this->pageName     = $args['page_name'];
        $this->settingsName = $args['field_name'];
        $this->sectionName  = $this->settingsName . '_section';
        $this->tableList    = Collection::make($args['table_list']);

        $this->optionValue = get_option($this->settingsName);

        $this->fieldLabel     = isset($args['field_label']) ? stripslashes($args['field_label']) : '';
        $this->classAttribute = isset($args['class_attribute']) ? $args['class_attribute'] : '';
        $this->description    = isset($args['description']) ? $args['description'] : '';

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
     * @return mixed|void
     */
    private function optionCallback($value)
    {
        if (has_filter(Plugin::PREFIX_ . 'sanitize_option_' . $this->settingsName)) {
            return apply_filters(Plugin::PREFIX_ . 'sanitize_option_' . $this->settingsName, $value);
        }

        if (!$this->tableList->pluck('value')->contains($value)) {
            return;
        }

        return $value;
    }

    private function displayCallback()
    {
        printf(
            '<select name="%s" id="%s"/>',
            $this->settingsName,
            $this->settingsName
        );

        $this->tableList->map(function ($row) {
            vprintf('<option %s value="%s">%s</option>', [
                $this->optionValue === $row['value'] ? 'selected="selected"' : '',
                $row['value'],
                $row['label']
            ]);
        });

        print('</select>');

        print($this->description($this->description));
    }
}
