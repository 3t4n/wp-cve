<?php

namespace WPPayForm\App\Modules\Builder;

use WPPayForm\Framework\Support\Arr;
use WPPayform\Framework\Support\Str;

class Helper
{
    public static $formInstance = 0;

    public static function getFormInstaceClass($formId)
    {
        static::$formInstance += 1;
        return 'wpf_form_instance_' . $formId . '_' . static::$formInstance;
    }

    public static function makeMenuUrl($page = 'wppayform_settings', $component = null)
    {
        $baseUrl = admin_url('admin.php?page=' . $page);

        $hash = Arr::get($component, 'hash', '');
        if ($hash) {
            $baseUrl = $baseUrl . '#' . $hash;
        }

        $query = Arr::get($component, 'query');

        if ($query) {
            $paramString = http_build_query($query);
            if ($hash) {
                $baseUrl .= '?' . $paramString;
            } else {
                $baseUrl .= '&' . $paramString;
            }
        }

        return $baseUrl;
    }

    public static function getHtmlElementClass($value1, $value2, $class = 'active', $default = '')
    {
        return $value1 === $value2 ? $class : $default;
    }

    public static function sanitizeForCSV($content)
    {
        if (is_array($content)) {
            $content = implode(', ', $content);
        }
        $formulas = ['=', '-', '+', '@', "\t", "\r"];

        if (Str::startsWith($content, $formulas)) {
            $content = "'" . $content;
        }

        return $content;
    }
}
