<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Zapier_Integration_Factory
{
    /**
     * @var Quform_Zapier_Options
     */
    protected $options;

    /**
     * @param Quform_Zapier_Options $options
     */
    public function __construct(Quform_Zapier_Options $options)
    {
        $this->options = $options;
    }

    public function create(array $config, Quform_Form $form)
    {
        return new Quform_Zapier_Integration($config, $form, $this->options);
    }
}
