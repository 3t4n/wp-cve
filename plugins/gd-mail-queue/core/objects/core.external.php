<?php

if (!defined('ABSPATH')) { exit; }

class gdmaq_core_external {
    public $buddypress_force_wp_mail = false;

    public function __construct() {
        foreach (array('buddypress_force_wp_mail') as $key) {
            $this->$key = gdmaq_settings()->get($key, 'external');
        }

        if ($this->buddypress_force_wp_mail) {
            add_filter('bp_email_use_wp_mail', '__return_true');
        }
    }

    /** @return gdmaq_core_external */
    public static function instance() {
        static $_gdmaq_external = false;

        if (!$_gdmaq_external) {
            $_gdmaq_external = new gdmaq_core_external();
        }

        return $_gdmaq_external;
    }
}

/** @return gdmaq_core_external */
function gdmaq_external() {
    return gdmaq_core_external::instance();
}
