<?php

function pisol_dtt_get_setting($variable, $default=""){
    $value = get_option($variable,$default);
    return apply_filters('pisol_dtt_setting_filter_'.$variable, $value, $variable, $default);
}