<?php

namespace AOP\App\Options\Fields;

class Subtitle {
    /**
     * @var mixed
     */
    private $pageName;

    /**
     * @var string
     */
    private $fieldTitle;

    /**
     * @var string
     */
    private $sectionName;

    /**
     * @var mixed|string
     */
    private $classAttribute;

    public function __construct($args)
    {
        $this->pageName = $args['page_name'];
        $this->fieldTitle = stripslashes($args['field_title']);
        $this->sectionName = $this->fieldTitle . '_section';
        $this->classAttribute = isset($args['class_attribute']) ? $args['class_attribute'] : '';

        add_action('admin_init', function () {
            $this->optionsSettingsInit();
        });
    }

    private function optionsSettingsInit()
    {
        add_settings_section(
            $this->sectionName,
            '',
            function () {
                $this->optionCallback();
            },
            $this->pageName
        );
    }

    private function optionCallback()
    {
        printf(
            '<h2%s>%s</h2>',
            $this->classAttribute ? ' class="' . $this->classAttribute . '"' : '',
            $this->fieldTitle
        );
    }
}
