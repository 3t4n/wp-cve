<?php
class arf_beaver_builder{
    function __construct(){

        add_action( 'init', array( $this, 'arf_load_modules') );

        add_action( 'enqueue_block_editor_assets' ,array($this,'arflite_enqueue_beaver_builder_assets'));
    }

    static public function arf_load_modules() {
        if ( class_exists( 'FLBuilder' ) ) {
            require_once ARFLITE_FORMPATH.'/integrations/Beaver_Builder/modules/beaver_builder_module/beaver_builder_module.php';
        }
       
    }

    function arflite_enqueue_beaver_builder_assets()
    {
        global $arfliteversion, $wpdb, $tbl_arf_forms, $arfliteformhelper, $arformsmain;

        $where_clause = ' AND arf_is_lite_form = 1';
		if( $arformsmain->arforms_is_pro_active() ){
			$where_clause = ' AND arf_is_lite_form = 0';
		}
		$arforms_forms_lite_data = $wpdb->get_results( 'SELECT * FROM `' . $tbl_arf_forms . "` WHERE is_template=0 AND (status is NULL OR status = '' OR status = 'published') {$where_clause} ORDER BY id DESC" );//phpcs:ignore
		$arforms_forms_lite_list = array();
		$n                       = 0;

		foreach ( $arforms_forms_lite_data as $k => $value ) {
            $arforms_forms_lite_list['']            =__("Please select a form",'arforms-form-builder');
			$arforms_forms_lite_list[$value->id]    = $value->name . ' (id: ' . $value->id . ')';
			
		}

        return $arforms_forms_lite_list;
    }
}
global $arf_beaver_builder;
$arf_beaver_builder =new arf_beaver_builder();

?>