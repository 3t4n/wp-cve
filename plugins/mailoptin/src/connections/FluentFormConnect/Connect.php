<?php

namespace MailOptin\FluentFormConnect;

class Connect
{
    public function __construct()
    {
        add_action('plugins_loaded', function () {
            if (class_exists('\FluentForm\App\Http\Controllers\IntegrationManagerController')) {
                FluentForms::get_instance();
            }
        }, 99);
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