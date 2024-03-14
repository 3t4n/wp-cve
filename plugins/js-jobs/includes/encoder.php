<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSEncoder {

    private $securekey, $iv;

    function __construct($textkey) {
        if($textkey != ''){
            $this->securekey = hash('sha256', $textkey, TRUE);
        }else{
            $this->securekey = '';
        }

        $this->iv = mcrypt_create_iv(32);
    }

    function encrypt($input) {
        $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->securekey, $input, MCRYPT_MODE_ECB, $this->iv);
        return jsjobslib::jsjobs_safe_encoding($output);

    }

    function decrypt($input) {
        $input = jsjobslib::jsjobs_safe_decoding($input);
        return jsjobslib::jsjobs_trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->securekey, $input, MCRYPT_MODE_ECB, $this->iv));
    }

}

?>
