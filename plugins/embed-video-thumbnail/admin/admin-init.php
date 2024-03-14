<?php

// Load the TGM init if it exists
if (file_exists(__DIR__ . '/tgm/tgm-init.php')) {
    require_once __DIR__ . '/tgm/tgm-init.php';
}

// Load Redux extensions
if (file_exists(__DIR__ . '/extensions-loader.php')) {
    require_once __DIR__ . '/extensions-loader.php';
}

// Load the embedded Redux Framework
if (file_exists(__DIR__ . '/redux-framework/framework.php')) {
    require_once __DIR__ . '/redux-framework/framework.php';
}

// Load the theme/plugin options
if (file_exists(__DIR__ . '/options-init.php')) {
    require_once __DIR__ . '/options-init.php';
}

if (!\function_exists('addReduxCustomPanelCSS')) {
    function addReduxCustomPanelCSS()
    {
        wp_register_style('redux-custom-css', IKANAWEB_EVT_URL . '/admin/css/custom.css', ['redux-admin-css'], time(), 'all');
        wp_enqueue_style('redux-custom-css');
    }

    add_action('redux/page/' . IKANAWEB_EVT_SLUG . '/enqueue', 'addReduxCustomPanelCSS');
}
