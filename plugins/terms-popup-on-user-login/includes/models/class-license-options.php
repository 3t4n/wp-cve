<?php

class TPUL_License_Options{

    private $options = false;
    private $options_name = 'tpul_settings_license_option';

    private $defaults = array(
        'tplu_license_key_valid_until'	   =>	'',
    );

    public function __construct() {     
        $this->options = get_option( $this->options_name );
    }

    public function default_options() {
        return $this->defaults;
    }

    public function get_options(){
        if( false ==  $this->options) {            
            return $this->default_options();
        }
        return $this->options;
    }
}
