<?php

class pisol_dtt_pro_conflict_fixer{
    function __construct(){
        add_action( 'admin_enqueue_scripts', array($this,'removeConflictCausingScripts'), 1000 );
        add_action( 'admin_footer', array($this,'removeScriptFromAdminFooter'), 10000000000 );
        add_action( 'wp_enqueue_scripts', array($this,'removeFrontConflict'), 900);
    }

    function removeConflictCausingScripts(){
        if(isset($_GET['page']) && $_GET['page'] == 'pisol-dtt'){
            wp_dequeue_script( 'jquery-timepicker' );

            /* color picker gets disabled because of this script */
            wp_dequeue_script( 'print-invoices-packing-slip-labels-for-woocommerce' );
        }
    }

    function removeScriptFromAdminFooter(){
        if(isset($_GET['page']) && $_GET['page'] == 'pisol-dtt'){
            /**
             * https://wordpress.org/plugins/makecommerce/
             */
            wp_dequeue_script( 'wc_mk_timepicker' );
         }
    }

    function removeFrontConflict(){
        $this->fixForEverestForms();
    }

    /**
     * https://wordpress.org/plugins/everest-forms/
     * it adds its own version of selectWoo that brakes the checkout process
     */
    function fixForEverestForms(){
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if(is_plugin_active( 'everest-forms/everest-forms.php')){
            if(function_exists('is_checkout') && is_checkout()){
                wp_deregister_script( 'selectWoo' );
                wp_register_script( 'selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo.full.min.js', array( 'jquery' ), '1.0.6' );
            }
        }

        /**
         * some theme adds bootstrap-datepicker that replaces our datepicker
         * and causes issue
         */
        if(function_exists('is_checkout') && is_checkout()){
            wp_dequeue_script( 'bootstrap-datepicker' );
	        wp_deregister_script( 'bootstrap-datepicker' );
        }
    }
    
}

new pisol_dtt_pro_conflict_fixer();