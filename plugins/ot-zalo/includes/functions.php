<?php

function ot_zalo_get_option($key, $default = '')
{
    $options = get_option('ot_zalo');
    $option = isset($options[$key]) ? $options[$key] : $default;
    return $option;
}