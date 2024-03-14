<?php

namespace Checkout\Contribuinte;

class Translations
{
    /**
     * Translations domain
     * @var string
     */
    private $domain = 'contribuinte-checkout';

    /**
     * Language files folder
     * @var string
     */
    private $folder = '/languages/';

    /**
     * Translations constructor.
     */
    public function __construct()
    {
        add_action('init', [$this, 'loadPluginTranslations']);
    }

    /**
     * Loads plugin translations
     */
    public function loadPluginTranslations()
    {
        load_plugin_textdomain(
            $this->domain,
            FALSE,
            basename(dirname(CONTRIBUINTE_CHECKOUT_PLUGIN_FILE)) . $this->folder
        );
    }
}