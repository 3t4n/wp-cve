<?php

defined('ABSPATH') || exit;

/**
 * Avoid bug in theme Woodmart when a product is sync
 */
if (!function_exists('woodmart_admin_scripts_localize')) {
    function woodmart_admin_scripts_localize() {}
}

/**
 * Avoid bug in theme Basel when a product is sync
 */
if (!function_exists('basel_admin_scripts_localize')) {
    function basel_admin_scripts_localize() {}
}