<?php

if ( ! defined('ABSPATH')) {
    exit;
}

if ( ! function_exists('qxcm_prepare_string')) {

    function qxcm_prepare_string(string $str): string
    {
        return wp_unslash($str);
    }

}