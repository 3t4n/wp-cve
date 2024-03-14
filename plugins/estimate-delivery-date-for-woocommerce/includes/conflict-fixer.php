<?php

class pisol_edd_free_conflict_fixer{
    function __construct(){
        add_action( 'admin_enqueue_scripts', array($this,'removeConflictCausingScripts'), 1000 );
    }

    function removeConflictCausingScripts(){
        if(isset($_GET['page']) && $_GET['page'] == 'pi-edd'){
            wp_dequeue_script( 'jquery-timepicker' );

            /* color picker gets disabled because of this script */
            wp_dequeue_script( 'print-invoices-packing-slip-labels-for-woocommerce' );
        }
    }
    
}

new pisol_edd_free_conflict_fixer();