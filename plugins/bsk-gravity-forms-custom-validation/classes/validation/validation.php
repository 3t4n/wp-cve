<?php

class BSK_GFCV_Validation {
    
    public $_OBJ_common = NULL;
    public $_OBJ_forms_gf = NULL;
    public $_OBJ_forms_ff = NULL;
    public $_OBJ_forms_wpf = NULL;
    public $_OBJ_forms_cf7 = NULL;

	public function __construct() {
        
        require_once( BSK_GFCV_DIR.'classes/validation/common.php' );
		require_once( BSK_GFCV_DIR.'classes/validation/gravityforms.php' );
        require_once( BSK_GFCV_DIR.'classes/validation/formidable-forms.php' );
        //require_once( BSK_GFCV_DIR.'classes/validation/wpforms.php' );
        //require_once( BSK_GFCV_DIR.'classes/validation/cf7.php' );
        
		$this->_OBJ_common = new BSK_GFCV_Validation_Common();
        
        $init_args = array();
        $init_args['common_class'] = $this->_OBJ_common;
        
		$this->_OBJ_forms_gf = new BSK_GFCV_Validation_GravityForms( $init_args );
        $this->_OBJ_forms_ff = new BSK_GFCV_Validation_FormidableForms( $init_args );
        //$this->_OBJ_forms_wpf = new BSK_GFBLCV_Validation_WPForms( $init_args );
        //$this->_OBJ_forms_cf7 = new BSK_GFBLCV_Validation_CF7( $init_args );
	}
	
}