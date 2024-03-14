<?php

namespace AOP\App\Admin\AdminPages\Settings;

use AOP\App\Admin\AdminPages\Settings\Options\Checkbox;
use AOP\App\Admin\AdminPages\Settings\SubpageSettings;

class PluginSettings
{
    public static function allOptions()
    {
        $hideEditButton = new Checkbox([
            'page_name' => SubpageSettings::SLUG,
            'setting_name' => SubpageSettings::HIDE_EDIT_BUTTON,
            'field_label' => 'Hide "Edit page" button',
        ]);
    }
}
