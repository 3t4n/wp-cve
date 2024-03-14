<?php defined('ABSPATH') || exit;

class Nouvello_WeManage_Utm_CF7_Form
{
    const DEFAULT_ENABLE_ATTRIBUTION = 1;
    const DEFAULT_CONVERSION_TYPE = 'lead';
    const DEFAULT_COOKIE_EXPIRY = 30; //days

    private static $instance;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    public static function get_form($form)
    {

        if (is_a($form, 'WPCF7_ContactForm')) :
            return $form;
        endif;

        if (is_numeric($form)) :
            $form = wpcf7_contact_form($form);
            return $form;
        endif;

        return false;
    }

    public static function get_settings($form)
    {

        return array(
            'enable_attribution' => self::is_enabled($form),
            'conversion_type' => self::get_conversion_type($form),
            'cookie_expiry' => self::get_cookie_expiry($form)
        );
    }

    public static function is_enabled($form)
    {

        $form = self::get_form($form);

        if ($form === false) :
            throw new Exception('Contact Form object not found.');
        endif;

        $module_settings = [];

        $enable_attribution = $form->pref('nouvello_utm_enable_attribution');

        if (
            $enable_attribution === 'on'
            || $enable_attribution === 'true'
            || $enable_attribution === '1'
        ) :

            $enable_attribution = 1;

        elseif (
            $enable_attribution === 'off'
            || $enable_attribution === 'false'
            || $enable_attribution === '0'
        ) :

            $enable_attribution = 0;

        elseif (isset($module_settings['global_status']) && $module_settings['global_status'] == 'default_disable') :

            //disabled by default
            $enable_attribution = 0;

        else :

            $enable_attribution = self::DEFAULT_ENABLE_ATTRIBUTION;

        endif;

        //force disable
        if (isset($module_settings['global_status']) && $module_settings['global_status'] == 'force_disable') :

            $enable_attribution = 0;

        endif;

        return !empty($enable_attribution) ? 1 : 0;
    }

    public static function get_conversion_type($form)
    {

        $form = self::get_form($form);

        if ($form === false) :
            throw new Exception('Contact Form object not found.');
        endif;

        $conversion_type = $form->pref('nouvello_utm_conversion_type');

        return (in_array($conversion_type, array('lead', 'order'), true) ? $conversion_type : self::DEFAULT_CONVERSION_TYPE);
    }

    public static function get_cookie_expiry($form)
    {

        $form = self::get_form($form);

        if ($form === false) :
            throw new Exception('Contact Form object not found.');
        endif;

        $cookie_expiry = $form->pref('nouvello_utm_cookie_expiry');

        return (int) ($cookie_expiry > 0 ? $cookie_expiry : self::DEFAULT_COOKIE_EXPIRY);
    }
}
