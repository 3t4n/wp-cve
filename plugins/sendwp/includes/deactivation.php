<?php

register_deactivation_hook(plugin_dir_path(__DIR__) . 'sendwp.php', function () {
    $options = [
        'sendwp_client_secret',
        'sendwp_client_connected',
        'sendwp_forwarding_enabled',
        '_transient_timeout_sendwp_pulse_monitor',
        '_transient_sendwp_pulse_monitor',
    ];

    foreach ($options as $option) {
        if (get_option($option, false) !== false) {
            delete_option($option);
        }
    }
});