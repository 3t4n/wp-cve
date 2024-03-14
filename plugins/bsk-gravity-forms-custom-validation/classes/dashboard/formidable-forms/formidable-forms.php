<?php
class BSK_GFCV_Dashboard_Formidable_Forms {
	
	public $_bsk_gfcv_OBJ_ff_field = NULL;
	public $_bsk_gfcv_OBJ_ff_settings = NULL;
    
    public static $_bsk_gfcv_ff_form_settings_option_name_prefix = '_bsk_forms_cv_ff_settings_of_';
    
	public function __construct() {
		
		require_once( BSK_GFCV_DIR.'classes/dashboard/formidable-forms/form-field.php' );
		require_once( BSK_GFCV_DIR.'classes/dashboard/formidable-forms/form-settings.php' );
        
		$this->_bsk_gfcv_OBJ_ff_field = new BSK_GFCV_Dashboard_FF_Field();
		$this->_bsk_gfcv_OBJ_ff_settings = new BSK_GFCV_Dashboard_FF_Settings();
	}
	
}
