<?php

use WPPayForm\App\Hooks\Handlers\ActivationHandler;
use WPPayForm\App\Hooks\Handlers\DeactivationHandler;
use WPPayForm\Framework\Foundation\Application;

return function ($file) {
    register_activation_hook($file, function () {
        (new ActivationHandler)->handle();
    });

    register_deactivation_hook($file, function () {
        (new DeactivationHandler)->handle();
    });

    add_action('plugins_loaded', function () use ($file) {
        // check the server here
        if (substr(phpversion(), 0, 3) == '7.0') {
            add_action('admin_notices', function () {
                $class = 'notice notice-error fc_message';
                $message = 'Looks like you are using PHP 7.0 which is not supported by WPPayForm. Please upgrade your PHP Version greater than to 7.2';
                printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
            });
        } else {
            do_action('wppayform_loaded', new Application($file));
        }
    });
};
