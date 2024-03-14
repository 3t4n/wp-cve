<?php

use WPPayForm\App\Modules\FormComponents\InitComponents;

/**
 * Add only the plugin specific bindings here.
 *
 * $app
 * @var WPPayForm\App\Foundation\Application
 */

if (!function_exists('wpPayFormAddComponent')) {
    function wpPayFormAddComponent()
    {
        $component = new InitComponents();
        return $component->__init();
    }

    wpPayFormAddComponent();
}
