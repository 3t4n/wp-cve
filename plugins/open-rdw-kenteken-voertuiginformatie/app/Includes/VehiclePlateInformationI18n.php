<?php

namespace Tussendoor\OpenRDW\Includes;

/**
 * Define the internationalization functionality
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 * @see       http://www.tussendoor.nl
 * @since      2.0.0
 */
class VehiclePlateInformationI18n
{
    public $dot_config;

    /**
     * Load the plugin text domain for translation.
     *
     * @since    2.0.0
     */
    public function __construct()
    {
        global $dot_config;
        $this->dot_config = $dot_config;
    }
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain(
            'open-rdw-kenteken-voertuiginformatie',
            false,
            $this->dot_config['plugin.folder'].'/languages/'
        );
    }
}
