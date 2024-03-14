<?php

if (!defined('FW')) {
    die('Forbidden');
}

/** @internal */
function _filter_disable_shortcodes($to_disable)
{
    $to_disable[] = 'calendar';
    $to_disable[] = 'widget_area';
    $to_disable[] = 'map';
//  $to_disable[] = 'icon-box';
    return $to_disable;
}
add_filter('fw_ext_shortcodes_disable_shortcodes', '_filter_disable_shortcodes');
