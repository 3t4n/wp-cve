<?php

namespace ECFFW\App\Controllers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Deactivator
{
    /**
     * Deactivator construct.
     */
    public function __construct() 
    {
        register_deactivation_hook(ECFFW_PLUGIN_FILE, function () {
            //
        });
    }
}
