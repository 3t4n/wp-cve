<?php

namespace Searchanise\Extensions;

defined('SE_ABSPATH') || exit;

use Searchanise\SmartWoocommerceSearch\AbstractExtension;
use Searchanise\SmartWoocommerceSearch\ApiSe;
use Searchanise\SmartWoocommerceSearch\Queue;

class WcWeglot extends AbstractExtension
{
    const OPTION_NAME = '_transient_weglot_cache_cdn';
    const SAVE_TIME_OPTION = 30;

    public $plugin_path;

    public function __construct()
    {
        $this->plugin_path = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $this->getBaseNameExtra();

        if ($this->isActive() && $this->isAvailableIntegration()) {
            add_filter('se_get_frontend_url_pre', array($this, 'seGetFrontendUrlPre'), 10, 2);
            add_action('activated_plugin', array($this, 'checkIsActivatedPlugin'), 10, 2);

            $this->setHooks();
        }

        parent::__construct();
    }

    public function isActive()
    {
        return is_plugin_active($this->getBaseName()) || is_plugin_active($this->getBaseNameExtra());
    }

    protected function getFilters()
    {
        return array(
            'se_get_english_name',
            'se_get_translate',
            'se_get_language_link',
            'se_get_current_language',
        );
    }

    public function getBaseName()
    {
        return defined('WEGLOT_BNAME') ? plugin_basename(WEGLOT_BNAME) : '';
    }

    public function getBaseNameExtra()
    {
        return 'weglot/weglot.php';
    }

    public function setHooks()
    {
        // Hook for change weglot settings
        add_action('add_option_' . self::OPTION_NAME, array($this, 'addOption'), 10, 2);
        add_action('update_option_' . self::OPTION_NAME, array($this, 'changeOption'), 10, 2);
        add_action('se_get_active_languages', array($this, 'seGetActiveLanguages'), 10, 2);
        add_filter('delete_option_' . self::OPTION_NAME, array($this, 'deactivateAddon'), 10, 2);

        // Deactivate weglot
        register_deactivation_hook($this->plugin_path, array($this, 'deactivateAddon'));
    }

    public function changeOption($old_value, $new_value)
    {
        // Check old value and new from plugin option
        if (isset($old_value['languages'], $new_value['languages'])) {
            // If difference deactivate old langs
            if ($old_value['languages'] != $new_value['languages']) {
                $this->deactivateAddonLanguages($old_value['languages']);
            }
        }

        $this->activatedPlugin();
    }

    /**
     * Check if activated plugin, needed for activation plugin action
     *
     * @param  string $plugin           - Path to the plugin file
     * @param  boolean $network_wide    - Whether to enable the plugin for all sites in the network
     *                                    or just the current site. Multisite only. Default is false.
     * @return void
     */
    public function checkIsActivatedPlugin($plugin, $network_wide)
    {
        if ($plugin == $this->getBaseName()) {
            $this->activatedPlugin(true);
        }
    }

    /**
     * When adding option to activate plugin without args
     *
     * @param  string $option_name name added option
     *
     * @return void
     */
    public function addOption($option_name)
    {
        $this->activatedPlugin();
    }

    // Start import/register engine, if change setting weglot or added new language
    public function activatedPlugin($activated = false)
    {
        $lang_codes = $this->getLangCodesList();

        if ($this->checkIsNotOldLanguage($lang_codes, $activated)) {
            if (!empty($lang_codes)) {
                foreach ($lang_codes as $lang_code) {
                    if ($lang_code != ApiSe::getInstance()->getLocale()) {
                        if (!ApiSe::getInstance()->checkPrivateKey($lang_code)) {
                            // Engine doesn't exist, register one
                            if (ApiSe::getInstance()->signup($lang_code, false, false) == true) {
                                ApiSe::getInstance()->queueImport($lang_code, false);

                                ApiSe::getInstance()->addAdminNotitice(
                                    sprintf(
                                        __('Searchanise: New search engine for %s created. Catalog import started'),
                                        $lang_code
                                    ),
                                    'info'
                                );
                            }
                        } else {
                            // Engine exists run import
                            ApiSe::getInstance()->addonStatusRequest(ApiSe::ADDON_STATUS_ENABLED, $lang_code);
                            ApiSe::getInstance()->queueImport($lang_code, false);
                            ApiSe::getInstance()->addAdminNotitice('Searchanise: Language settings updated. The product catalog is queued for syncing with Searchanise.');
                        }
                    }
                }
            } else {
                // Run reimport
                ApiSe::getInstance()->queueImport(null, false);
            }
        }
    }

    /**
     * Deactivated old langs needed for hook change option
     *
     * @param  mixed $olds_option_value
     *
     * @return void
     */
    public function deactivateAddonLanguages($olds_option_value)
    {
        $lang_codes = $this->getLangCodesList();

        // Disabled old lang engines
        foreach ($olds_option_value as $old) {
            if (!in_array($old['language_to'], $lang_codes)) {
                ApiSe::getInstance()->addonStatusRequest(ApiSe::ADDON_STATUS_DISABLED, $old['language_to']);
                ApiSe::getInstance()->setExportStatus(ApiSe::EXPORT_STATUS_NONE, $old['language_to']);
            }
        }
    }

    /**
     * Get all weglot active langs
     *
     * @return array langs
     */
    public function getLangCodesList()
    {
        $lang_codes = array();

        // Get enabled language codes from weglot
        if (function_exists('weglot_get_destination_languages')) {
            $weglot_destination_languages = weglot_get_destination_languages();

            // Original weglot language
            $default_language = $this->getOriginalLanguage();

            foreach ($weglot_destination_languages as $lang) {
                if ($default_language != $lang['language_to']) {
                    $lang_codes[] = $lang['language_to'];
                }
            }
        }

        return $lang_codes;
    }

    public function getOriginalLanguage()
    {
        return weglot_get_original_language();
    }

    /**
     * Get all language from the db
     *
     * @return array $langs
     */
    private static function getAllWeglotLangs()
    {
        global $wpdb;

        $weglot_langs = $wpdb->get_col("SELECT DISTINCT `lang_code` FROM {$wpdb->prefix}wc_se_settings WHERE lang_code != 'default' AND lang_code !=''");

        return $weglot_langs;
    }

    /**
     * Disabled all weglot langs
     *
     * @return void
     */
    public function deactivateAddon()
    {
        set_transient('old_value_weglot_langs', $this->getLangCodesList(), self::SAVE_TIME_OPTION);
        $lang_codes = self::getAllWeglotLangs();

        // Disabled all weglot lang engines, clear action and set export status none
        foreach ($lang_codes as $lang_code) {
            ApiSe::getInstance()->addonStatusRequest(ApiSe::ADDON_STATUS_DISABLED, $lang_code);
            Queue::getInstance()->clearActions($lang_code);
            ApiSe::getInstance()->setExportStatus(ApiSe::EXPORT_STATUS_NONE, $lang_code);
        }
    }

    /**
     * Disabled all lang and delete engine
     *
     * @return void
     */
    public function uninstallAddon()
    {
        $lang_codes = self::getAllWeglotLangs();

        foreach ($lang_codes as $lang_code) {
            ApiSe::getInstance()->deleteKeys($lang_code);
        }
    }

    /**
     * All active langs added for default
     *
     * @param  array/string $active_languages
     *
     * @return array all active langs
     */
    public function seGetActiveLanguages($active_languages)
    {
        if ($this->isActive()) {
            $active_languages = array_merge((array)$active_languages, $this->getLangCodesList());
        }

        return $active_languages;
    }

    /**
     * Get currently url for engine
     *
     * @param  string $site_url
     * @param  string $lang_code
     *
     * @return string
     */
    public function seGetFrontendUrlPre($site_url, $lang_code, $params = array())
    {
        $lang_code = $this->getExternalCode($lang_code);

        if ($lang_code != ApiSe::getInstance()->getLocale()) {
            $site_url .= '/' . $lang_code . '/';
        }

        return $site_url;
    }

    /**
     * Get english full name for lang code
     *
     * @param  string $lang_code
     *
     * @return string full English name
     */
    public function seGetEnglishName($lang_code)
    {
        $english_name = false;

        if ($this->isActive() && function_exists('weglot_get_service')) {
            $language_data = weglot_get_service('Language_Service_Weglot')->get_destination_languages(true);

            foreach ($language_data as $lang) {
                if ($lang->getInternalCode() == $lang_code) {
                    $english_name = $lang->getEnglishName();
                    break;
                }
            }
        }

        return $english_name;
    }

    /**
     * Get External Code for lang_code
     *
     * @param string $lang_code
     *
     * @return string $lang_code
     */
    public function getExternalCode($lang_code)
    {
        $external_code = $lang_code;

        if ($this->isActive() && function_exists('weglot_get_service')) {
            $language_data = weglot_get_service('Language_Service_Weglot')->get_destination_languages(true);

            foreach ($language_data as $lang) {
                if ($lang->getInternalCode() == $lang_code) {
                    $external_code = $lang->getExternalCode();
                    break;
                }
            }
        } else {
            $external_code = $this->checkExternalCode($lang_code);
        }

        return $external_code;
    }

    /**
     * Check lang code in External code
     *
     * @param  string $external_code
     *
     * @return string external code
     */
    public function checkExternalCode($lang_code)
    {
        $external_codes = array(
            'tw' => 'zh-tw',
            'br' => 'pt-br',
        );

        foreach ($external_codes as $key => $value) {
            if ($key == $lang_code) {
                $external_code = $value;
                break;
            }
        }

        return isset($external_code) ? $external_code : $lang_code;
    }

    /**
     * Get currently link with lang code
     *
     * @param  string $link
     * @param  string $lang_code
     *
     * @return string $link with $lang_code
     */
    public function seGetLanguageLink($link, $lang_code)
    {
        $lang_code = $this->getExternalCode($lang_code);
        $lang_link = $link;

        if ($lang_code != ApiSe::getInstance()->getLocale()) {
            $lang_link = str_replace(get_site_url(), get_site_url() . '/' . $lang_code, $link);
        }

        return $lang_link;
    }

    /**
     * Translate $context for $currently_language
     *
     * @param  array   $content             - Value for translate
     * @param  string  $currently_language  - Language for translate
     * @param  boolean $is_json             - Is $context json
     *
     * @return array
     */
    public function seGetTranslate($content, $currently_language)
    {
        $translated_content = $content;

        // Key in array $content for translate ('name' and 'description' is default translate values)
        $extra_keys = $this->getAllAttributeForTranslate();

        if ($this->isActive() && $this->isAvailableIntegration() && function_exists('weglot_get_service')) {
            try {
                $parser = weglot_get_service('Parser_Service_Weglot')->get_parser();
            } catch (\Exception $e) {
                return $content;
            }

            $original_language = $this->getOriginalLanguage();

            if ($currently_language != ApiSe::getInstance()->getLocale()) {
                try {
                    if (is_array($translated_content)) {
                        // Replace key name in array so it doesn't translate
                        $hack = $this->changeKeyName($translated_content, 'name', 'hack_name');
                        $translate = $parser->translate(wp_json_encode($hack), $original_language, $currently_language, $extra_keys);

                        // Return key name back
                        $translated_content = $this->changeKeyName(json_decode($translate, true), 'hack_name', 'name');
                    }
                } catch (\Exception $e) {
                    return $content;
                }
            }
        }

        return $translated_content;
    }

    /**
     * Change key in array
     *
     * @param  array  $array
     * @param  string $oldKey key for change
     * @param  string $newKey replace key
     *
     * @return array
     */
    public function changeKeyName($array, $oldKey, $newKey)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->changeKeyName($value, $oldKey, $newKey);
            }

            if ($key === $oldKey) {
                $array[$newKey] = $array[$oldKey];
                unset($array[$oldKey]);
            }
        }

        return $array;
    }

    /**
     * Get lang code from currently url
     *
     * @return string lang code
     */
    public function seGetCurrentLanguage()
    {
        $currently_language = false;

        if ($this->isActive() && function_exists('weglot_get_current_language')) {
            $currently_language = weglot_get_current_language() != $this->getOriginalLanguage() ? weglot_get_current_language() : false;
        }

        return $currently_language;
    }

    /**
     * Get woocommerce attribute names for translate
     *
     * @param  array $extra_keys
     *
     * @return array
     */
    public function getAllAttributeForTranslate()
    {
        $extra_keys = array(
            'title',
            'value',
            'summary',
            'categories',
            'tags',
            'category_ids',
            'stock_status',
            'price',
        );

        $attributes = wc_get_attribute_taxonomies();

        if (!empty($attributes)) {
            $wooc_attr = array();

            foreach ($attributes as $atr) {
                $wooc_attr[] = $atr->attribute_name;
            }

            $extra_keys = array_merge($extra_keys, $wooc_attr);
        }

        return $extra_keys;
    }

    /**
     * Check is old language settings
     *
     * @param  array $lang_codes
     * @param  bool  $activated
     *
     * @return bool
     */
    public function checkIsNotOldLanguage($lang_codes, $activated = false)
    {
        return $activated ? $activated : !empty(get_transient('old_value_weglot_langs')) && get_transient('old_value_weglot_langs') != $lang_codes;
    }

    /**
     * Check is available integration
     *
     * @return void
     */
    public function isAvailableIntegration($lang_code = false)
    {
        if (!$lang_code) {
            $lang_code = ApiSe::getInstance()->getDefaultLocale();
        }

        return ApiSe::getInstance()->isIntegrationWeglotEnabled($lang_code) == 'Y';
    }
}
