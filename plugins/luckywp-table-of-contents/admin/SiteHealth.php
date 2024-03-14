<?php

namespace luckywp\tableOfContents\admin;

use luckywp\tableOfContents\core\base\BaseObject;
use luckywp\tableOfContents\core\Core;

class SiteHealth extends BaseObject
{

    public function init()
    {
        add_filter('debug_information', [$this, 'debugInfo']);
    }

    /**
     * @param array $info
     * @return array
     */
    public function debugInfo($info)
    {
        $info[Core::$plugin->slug] = [
            'label' => Core::$plugin->getName(),
            'fields' => [
                'domVersion' => [
                    'label' => __('DOM/XML Version', 'luckywp-table-of-contents'),
                    'value' => extension_loaded('dom') ? phpversion('dom') : __('not loaded', 'luckywp-table-of-contents'),
                ],
                'libxmlVersion' => [
                    'label' => __('libXML Version', 'luckywp-table-of-contents'),
                    'value' => extension_loaded('libxml') ? LIBXML_DOTTED_VERSION : __('not loaded', 'luckywp-table-of-contents'),
                ],
                'intlVersion' => [
                    'label' => __('intl Version', 'luckywp-table-of-contents'),
                    'value' => extension_loaded('intl') ? phpversion('intl') : __('not loaded', 'luckywp-table-of-contents'),
                ],
                'icuVersion' => [
                    'label' => __('ICU Version', 'luckywp-table-of-contents'),
                    'value' => defined('INTL_ICU_VERSION') ? INTL_ICU_VERSION : __('not loaded', 'luckywp-table-of-contents'),
                ],
                'settings' => [
                    'label' => __('Settings', 'luckywp-table-of-contents'),
                    'value' => __('encoded data to copy', 'luckywp-table-of-contents'),
                    'debug' => Core::$plugin->settings->toJson(),
                ],
            ],
        ];
        return $info;
    }
}
