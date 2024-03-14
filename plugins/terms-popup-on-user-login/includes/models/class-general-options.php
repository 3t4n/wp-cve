<?php

class TPUL_General_Options {

    private $options = false;
    private $options_name = 'tpul_settings_general_options';

    private $defaults = array(
        'modal_to_show'       =>    '',
        'tplu_license_key'       =>    '',
        'tplu_license_key_valid_until'       =>    '',
        'tplu_license_key_last_checked'       =>    '',
    );

    public function __construct() {
        $this->options = get_option($this->options_name);
    }

    public function default_options() {
        return $this->defaults;
    }

    public function get_options() {
        if (false ==  $this->options) {
            return $this->default_options();
        }
        return $this->options;
    }

    public function get_modal_to_show() {
        return $this->options['modal_to_show'];
    }
}
