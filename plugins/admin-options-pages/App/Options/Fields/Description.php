<?php

namespace AOP\App\Options\Fields;

class Description
{
    /**
     * @var mixed
     */
    private $pageName;

    /**
     * @var mixed
     */
    private $descriptionField;

    /**
     * @var string
     */
    private $sectionName;

    /**
     * @var mixed|string
     */
    private $classAttribute;

    /**
     * Description constructor.
     *
     * @param $args
     */
    public function __construct($args)
    {
        $this->pageName = $args['page_name'];
        $this->descriptionField = $args['description'];
        $this->sectionName = $args['id'] . '_section';

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
                $this->displayCallback();
            },
            $this->pageName
        );
    }

    private function displayCallback()
    {
        printf(
            '<p%s>%s</p>',
            $this->classAttribute ? ' class="' . $this->classAttribute . '"' : '',
            stripslashes($this->descriptionField)
        );
    }
}
