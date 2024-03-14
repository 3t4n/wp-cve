<?php

namespace Mnet\Utils;

class MnetAdUtils
{
    public static function getPageType()
    {
        if (\is_home() || \is_front_page()) {
            return MNET_PAGETYPE_HOME;
        } elseif (\is_search()) {
            return MNET_PAGETYPE_SEARCH;
        } elseif (\is_single()) {
            return MNET_PAGETYPE_ARTICLE;
        } elseif (\is_category()) {
            return MNET_PAGETYPE_CATEGORY;
        } elseif (\is_archive() || (\is_home() && !\is_front_page())) {
            return MNET_PAGETYPE_ARCHIVE;
        } elseif (\is_page()) {
            return MNET_PAGETYPE_STATIC;
        } elseif (\is_admin()) {
            return MNET_PAGETYPE_ADMIN;
        } else {
            return MNET_PAGETYPE_ALL;
        }
    }

    public static function trimUrl($url)
    {
        $url = preg_replace('/http(s)?\:\/\//', '', $url);
        $url = preg_replace('/\/.*\.php\//', '/', $url);
        if ($url[strlen($url) - 1] == '/') {
            $url = substr($url, 0, strlen($url) - 1);
        }
        return $url;
    }


    public static function getCssOptions($options)
    {
        $default_css_options = include(__DIR__ . '/defaultCssOptions.php');
        return array_merge($default_css_options, $options);
    }
}
