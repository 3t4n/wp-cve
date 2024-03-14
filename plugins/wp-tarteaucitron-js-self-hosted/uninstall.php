<?php // exit if uninstall constant is not defined
if (!defined('WP_UNINSTALL_PLUGIN'))
    exit;

function startsWith($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}

// remove plugin options
$options = wp_load_alloptions();
foreach ($options as $slug => $values) {
    if (startsWith($slug, "tac_")) {
        delete_option($slug);
    }
}