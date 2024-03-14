<?php

namespace Rockschtar\WordPress\ColoredAdminPostList\Utils;

class PluginVersion
{
    public static function get()
    {
        $plugin_data = get_file_data(CAPL_PLUGIN_FILE, [
            'Version' => 'Version',
        ], 'plugin');

        return $plugin_data['Version'];
    }
}
