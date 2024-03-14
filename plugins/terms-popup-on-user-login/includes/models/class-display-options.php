<?php

class TPUL_Display_Options{

    private $options = false;
    private $options_name = 'tpul_settings_term_modal_display_options';

    private $defaults = array(

        'terms_modal_width'         	       =>	'',
        'terms_modal_height'         	       =>	'',

        'terms_modal_border_rnd'	           =>	'',
        'terms_modal_btn_border_rnd'	       =>	'',

        'terms_modal_acc_btn_size'	           =>	'',
        'terms_modal_acc_btn_color'	           =>	'',
        'terms_modal_acc_btn_txt_color'	       =>	'',

        'terms_modal_dec_btn_size'	           =>	'',
        'terms_modal_dec_btn_color' 	       =>	'',
        'terms_modal_dec_btn_txt_color'	       =>	'',

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
