<?php

namespace S123\Includes\Base;

trait S123_Options
{
    public function s123_get_option($key, $default = null)
    {
        $options = $this->s123_get_options();
        return $options[$key] ?? $default;
    }

    public function s123_update_options($options): void
    {
        update_option(S123_BaseController::PLUGIN_NAME, $options);
    }

    public function s123_get_options(): array
    {
        $plugin_options = get_option(S123_BaseController::PLUGIN_NAME);
        return is_array($plugin_options) ? $plugin_options : [];
    }
}