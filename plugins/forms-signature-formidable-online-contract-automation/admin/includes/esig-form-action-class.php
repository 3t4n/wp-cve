<?php
class FrmDefEsigAction extends FrmFormAction{
    
        public function __construct() {
            
            $action_ops = array(
            'classes'   => 'icon icon-esignature',
            'active'    => true,
            'event'     => array( 'create','update' ),
            'limit'     => 99,
            'priority'  => 10,
            'ajax_load' => false,
		);
                    
                 $action_options = apply_filters('frm_esig_control_settings', $action_ops);
                 
                 if(function_exists('esig_formidable_get')){
                     $page= esig_formidable_get('page');
                     $formidable_settings= esig_formidable_get('frm_action'); 
                     if($page == "formidable" && $formidable_settings=="settings"){
                         wp_enqueue_style('esig-formidable-icon-css');
                     }
                    
                     
                 }
                 else {
                     wp_enqueue_style('esig-formidable-icon-css');
                 }
                 
                 
                 
                parent::__construct( 'esig', __( 'E-signature', 'formidable' ), $action_options );
       
	}
        
        public function form( $form_action, $args = array() ) {
            
                extract($args);
                $form =  $args['form'];
                $formId = $form->id;
                
            include( dirname(__FILE__) .'/esig_action_settings.php');
		
	}
        
        public function get_defaults() {
	    return array(
            'signer_name'   => '[signer_name]',
            'signer_email'  => '[signer_email]',
            'signing_logic'  => '',
            'select_sad' => '',
            'underline_data' => '[default-message]',
            'enable_signing_reminder_email' => '',
            'reminder_email' => '',
            'first_reminder_send' => '',
            'expire_reminder' => '',
	    );
	}
        
}