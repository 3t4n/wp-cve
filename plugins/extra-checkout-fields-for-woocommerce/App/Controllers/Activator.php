<?php

namespace ECFFW\App\Controllers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use ECFFW\App\Helpers\CheckCompatible;

class Activator
{
    /**
     * Activator construct.
     */
    public function __construct() 
    {
        register_activation_hook(ECFFW_PLUGIN_FILE, function () {
            new CheckCompatible(); // Check Compatibility
        });
    }
}
