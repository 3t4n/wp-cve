<?php

namespace MailOptin\UltimateMemberConnect;

class Connect
{
    public function __construct()
    {
        add_action('plugins_loaded', function () {
            if (class_exists('\UM') && defined('UM_PLUGIN')) {
                UMInit::get_instance();
            }
        });
    }

    /**
     * @return Connect|null
     */
    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}