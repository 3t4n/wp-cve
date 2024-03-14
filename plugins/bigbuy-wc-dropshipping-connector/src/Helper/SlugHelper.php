<?php

namespace WcMipConnector\Helper;

defined('ABSPATH') || exit;

class SlugHelper
{
    private function __construct()
    {
    }

    /**
     * @param string $slug
     * @return string
     */
    public static function sanitize(string $slug): string
    {
        $result = $slug;

        if (\function_exists('sanitize_title_with_dashes')) {
            $result = sanitize_title_with_dashes($result);
        }

        return \str_replace('+', '', $result);
    }
}