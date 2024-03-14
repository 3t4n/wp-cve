<?php 
class VC_REDO { 
    private $file; 
    function __construct($file) { 
        $this->file = $file; 
        add_action( 'vc_before_init', array($this, 'vc_redo_widgets' )); 
        add_action( 'wp_ajax_vc_save_data', array($this, 'vc_redo_update' ));
        add_action( 'init', array( $this, 'check_for_install' ) );
     }
}


