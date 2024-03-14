<?php
if (!defined('ABSPATH')) {
    die;
}

if (!function_exists('plx_portal_dd')) {
    function plx_portal_dd($var)
    {
        echo '<pre>';
        echo var_dump($var);
        echo '</pre>';
        die();
    }
}
