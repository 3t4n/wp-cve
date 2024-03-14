<?php
class BSK_GFCV_Dashboard_GravityForms {
	
	public $_bsk_gfcv_OBJ_gform_field = NULL;
	public $_bsk_gfcv_OBJ_gform_settings = NULL;
    
	public function __construct() {
		
		require_once( BSK_GFCV_DIR.'classes/dashboard/gravityforms/form-field.php' );
		require_once( BSK_GFCV_DIR.'classes/dashboard/gravityforms/form-settings.php' );
        
		$this->_bsk_gfcv_OBJ_gform_field = new BSK_GFCV_Dashboard_GForm_Field();
		$this->_bsk_gfcv_OBJ_gform_settings = new BSK_GFCV_Dashboard_GForm_Settings();
        
	}
	
}
