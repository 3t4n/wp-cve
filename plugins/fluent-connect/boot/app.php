<?php

use FluentConnect\Framework\Foundation\Application;
use FluentConnect\App\Hooks\Handlers\ActivationHandler;
use FluentConnect\App\Hooks\Handlers\DeactivationHandler;

return function($file) {

    register_activation_hook($file, function() {
        (new ActivationHandler)->handle();
    });

    register_deactivation_hook($file, function() {
        (new DeactivationHandler)->handle();
    });

    add_action('plugins_loaded', function() use ($file) {
        do_action('fluentconnector_loaded', new Application($file));
    });
};
