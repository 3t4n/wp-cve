<?php

use FluentSupport\Framework\Foundation\Application;
use FluentSupport\App\Hooks\Handlers\ActivationHandler;
use FluentSupport\App\Hooks\Handlers\DeactivationHandler;

return function ($file) {

    register_activation_hook($file, function () {
        (new ActivationHandler)->handle();
    });

    register_deactivation_hook($file, function () {
        (new DeactivationHandler)->handle();
    });

    add_action('plugins_loaded', function () use ($file) {
        $application = new Application($file);
        do_action('fluent_support_loaded', $application);
        do_action('fluent_support_addons_loaded', $application);

        add_action('init', function () {
            load_plugin_textdomain('fluent-support', false, 'fluent-support/language/');
        });

        add_action('fluent_support/admin_app_loaded', function () {
            if (!wp_next_scheduled('fluent_support_hourly_tasks')) {
                wp_schedule_event(time(), 'hourly', 'fluent_support_hourly_tasks');
            }

            if (!wp_next_scheduled('fluent_support_daily_tasks')) {
                wp_schedule_event(time(), 'daily', 'fluent_support_daily_tasks');
            }

            if (!wp_next_scheduled('fluent_support_weekly_tasks')) {
                wp_schedule_event(time(), 'weekly', 'fluent_support_weekly_tasks');
            }
        });

    });
};
