<?php

namespace BDroppy\Services\System;

use BDroppy\Init\Core;

if (!defined('ABSPATH')) exit;

/**
 * Allows log files to be written to for debugging purposes
 *
 * @class \BrandsSync\Logger
 * @author WooThemes
 */
class SystemLanguage
{
    private $core;
    private $config;

    public $languages = [
        "it" => ["name" => 'Italian (Italy)' ,          "code" =>'it_IT'],
        "et" => ["name" => 'Estonian (Estonia)',        'code' => 'et_EE'],
        "ru" => ["name" => 'Russian (Russia)',          'code' => 'ru_RU'],
        "hu" => ["name" => 'Hungarian (Hungary)',       'code' => 'hu_HU'],
        "sv" => ["name" => 'Swedish (Sweden)',          'code' => 'sv_SE'],
        "sk" => ["name" => 'Slovak (Slovakia)',         'code' => 'sk_SK'],
        "cs" => ["name" => 'Czech (Czech Republic)',    'code' => 'cs_CZ'],
        "pt" => ["name" => 'Portuguese (Portugal)',     'code' => 'pt_PT'],
        "pl" => ["name" => 'Polish (Poland)',           'code' => 'pl_PL'],
        "en" => ["name" => 'English (United States)',   "code" => 'en_US'],
        "fr" => ["name" => 'French (France)',           'code' => 'fr_FR'],
        "de" => ["name" => 'German (Germany)',          'code' => 'de_DE'],
        "es" => ["name" => 'Spanish (Spain)',           'code' => 'es_ES'],
        "ro" => ["name" => 'Romanian (Romania)',        'code' => 'ro_RO'],
        "nl" => ["name" => 'Dutch (Netherlands)',       'code' => 'nl_NL'],
        "fi" => ["name" => 'Finnish (Finland)',         'code' => 'fi_FI'],
        "bg" => ["name" => 'Bulgarian (Bulgaria)',      'code' => 'bg_BG'],
        "da" => ["name" => 'Danish (Denmark)',          'code' => 'da_DK'],
        "lt" => ["name" => 'Lithuanian (Lithuania)',    'code' => 'lt_LT'],
        "el" => ["name" => 'Greek Greece',              'code' => 'el_GR'],
    ];

    public function __construct(Core $core)
    {
        $this->core = $core;
        $this->config = $core->getConfig();
    }

    public static function hasWpmlSupport()
    {
        return in_array('sitepress-multilingual-cms/sitepress.php', apply_filters('active_plugins', get_option('active_plugins')));
    }


    public function getAvailable($lang)
    {
        $defLang = $lang;
        if (in_array(substr($lang, 0, 2), array_keys($this->languages))) {
            return $this->languages[substr($lang, 0, 2)]['code'];
        } else {
            return $defLang;
        }
    }

    public function getLanguages()
    {
        return $this->languages;
    }

    public function getActives($is_Array = true)
    {
        if ($this->hasWpmlSupport()) {
            global $sitepress;
            $languages = $sitepress->get_active_languages();
            $languages = array_column($languages, 'code');
            return array_map([$this, 'getAvailable'], $languages);
        } else {
            $setting_language = $this->config->catalog->get('import-language', null);
            $lang = isset($setting_language) ? $setting_language : get_locale();
            return $is_Array ? array_map([$this, 'getAvailable'], [$lang]) : $this->getAvailable($lang);
        }
    }
}
