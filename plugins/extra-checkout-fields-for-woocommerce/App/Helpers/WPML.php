<?php

namespace ECFFW\App\Helpers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class WPML
{
    /**
     * Check the WPML is active or not.
     * @return bool
     */
    public static function isActive() {
        return class_exists('SitePress');
    }

    /**
     * Check the WPML String Translation Addon is active or not.
     * @return bool
     */
    public static function stringTranslationIsActive() {
        return class_exists('WPML_String_Translation');
    }

    /**
     * Resister Dynamic String for Translation.
     * 
     * @param string $name
     * @param string $string
     * @return void
     */
    public static function registerString($name, $string)
    {
        if (has_action('wpml_register_single_string')) {
            do_action('wpml_register_single_string', 'extra-checkout-fields-for-woocommerce', $name, $string);
        }
    }

    /**
     * Translate Registered String.
     * 
     * @param string $string
     * @param string $name
     * @param string $code
     * @return string translated
     */
    public static function translateString($name, $string, $code = null)
    {
        if (has_filter('wpml_translate_single_string')) {
            if ($code == null) {
                return apply_filters('wpml_translate_single_string', $string, 'extra-checkout-fields-for-woocommerce', $name);
            } else {
                return apply_filters('wpml_translate_single_string', $string, 'extra-checkout-fields-for-woocommerce', $name, $code);
            }
        }
        return $string;
    }

    /**
     * Get Current language code.
     * 
     * @return string|null code
     */
    public static function getCurrentLanguage() 
    {
        if (has_filter('wpml_current_language')) {
            return apply_filters('wpml_current_language', null);
        }
        return null;
    }

    /**
     * Get Translated Post IDs.
     * 
     * @param int id
     * @return array ids
     */
    public static function getTranslatedPostIds($post_id) 
    {
        global $sitepress;
        $translated_ids = [];
        if (!isset($sitepress)) return $translated_ids;
        $trid = $sitepress->get_element_trid($post_id, 'post_product');
        $translations = $sitepress->get_element_translations($trid, 'product');
        foreach ($translations as $lang => $translation) {
            $translated_ids[] = $translation->element_id;
        }
        return $translated_ids;
    }

    /**
     * Get Translated Post Original ID.
     * 
     * @param int id
     * @return string|null id
     */
    public static function getTranslatedPostOriginalId($post_id) 
    {
        global $sitepress;
        if (!isset($sitepress)) return null;
        $trid = $sitepress->get_element_trid($post_id, 'post_product');
        $translations = $sitepress->get_element_translations($trid, 'product');
        foreach ($translations as $lang => $translation) {
            if ($translation->original == '1') {
                return $translation->element_id;
            }
        }
        return null;
    }
}
