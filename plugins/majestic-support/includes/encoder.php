<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_encoder {

    private $securekey, $iv;

    function __construct($textkey = '') {
    }

    function MJTC_encrypt($input) {
        return MJTC_majesticsupportphplib::MJTC_safe_encoding($input);
    }

    function MJTC_decrypt($input) {
        return MJTC_majesticsupportphplib::MJTC_safe_decoding($input);
    }

}

?>
