<?php

register_activation_hook(plugin_dir_path(__DIR__) . 'sendwp.php', function () {
    if (! sendwp_get_client_secret()) {
        sendwp_set_client_secret(sendwp_generate_secret());
    }
});