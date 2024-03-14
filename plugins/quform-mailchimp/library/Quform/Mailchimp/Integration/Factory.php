<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Mailchimp_Integration_Factory
{
    /**
     * @var Quform_Mailchimp_Options
     */
    protected $options;

    /**
     * @param Quform_Mailchimp_Options $options
     */
    public function __construct(Quform_Mailchimp_Options $options)
    {
        $this->options = $options;
    }

    public function create(array $config, Quform_Form $form)
    {
        return new Quform_Mailchimp_Integration($config, $form, $this->options);
    }
}
