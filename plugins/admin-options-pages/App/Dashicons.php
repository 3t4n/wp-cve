<?php

namespace AOP\App;

use AOP\Lib\Illuminate\Support\Collection;
use Exception;

class Dashicons
{
    /**
     * @link https://github.com/WordPress/dashicons/
     *
     * @return Collection
     * @throws Exception
     */
    public static function allIconsCollection()
    {
        global $wp_version;

        $aopDashiconsDir = Plugin::assetsDir() . 'css/dashicons.css';
        $wpDashiconsDir  = ABSPATH . WPINC . '/css/dashicons.css';

        if (File::exists($wpDashiconsDir) && $wp_version >= '5.2') {
            $dir = $wpDashiconsDir;
        } elseif (File::exists($aopDashiconsDir)) {
            $dir = $aopDashiconsDir;
        } else {
            throw new Exception('File dashicons.css is not loaded correctly');
        }

        return Collection::make(explode('.dashicons-', File::get($dir)))->filter(function ($item) {
            return strpos($item, 'content');
        })->map(function ($item) {
            $item = explode('content:', $item);
            $name = strstr($item[0], ':', true);
            $css = strstr(strrchr($item[1], '\\f'), '"', true);

            return [$name, $css];
        })->values();
    }
}
