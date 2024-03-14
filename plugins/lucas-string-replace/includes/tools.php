<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function lsr_stripslashes( $value ){
    $value = is_array($value) ?
                array_map('lsr_stripslashes', $value) :
                stripslashes($value);
    return $value;
}