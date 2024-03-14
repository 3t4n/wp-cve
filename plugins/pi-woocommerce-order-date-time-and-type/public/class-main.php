<?php

class pisol_dtt_free_main_controller{
    function __construct(){
        add_action('wp_loaded',array($this, 'addFields'));
    }

    function addFields(){
		/**
         * This filter allow you to hide all the fields added by this plugin 
         * so you can use this to disable the plugin when you have virtual product in
         * your cart
         */
		$pisol_disable_dtt_completely = apply_filters('pisol_disable_dtt_completely',false);
        if($pisol_disable_dtt_completely){
            return ;
        }
		$obj = new pi_dtt_display_fields();
	}
}

new pisol_dtt_free_main_controller();