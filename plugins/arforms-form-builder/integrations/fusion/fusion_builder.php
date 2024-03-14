<?php

class fusion_builder_elements{
    function __construct(){

        add_action('fusion_builder_enqueue_live_scripts' ,array($this, 'arf_load_style'), 10);
        add_action('fusion_builder_admin_scripts_hook',array($this, 'arf_load_style'), 10);
        
        add_action( 'fusion_builder_before_init', array( $this, 'fusion_builder_elements_map') );
    }

    function arf_load_style()
    {
        global $arfliteversion;
        wp_enqueue_style( 'arforms_fusion_css', '/wp-content/plugins/arforms-form-builder/integrations/fusion/css/fusion_builder.css'  , array() ,$arfliteversion);
    }

    function fusion_builder_elements_map()
    {
        global $arfliteversion, $wpdb, $tbl_arf_forms, $arfliteformhelper, $arformsmain , $arflitemainhelper;

        $where_clause = ' AND arf_is_lite_form = 1';
		if( $arformsmain->arforms_is_pro_active() ){
			$where_clause = ' AND arf_is_lite_form = 0';
		}
		$arforms_forms_lite_data = $wpdb->get_results( 'SELECT * FROM `' . $tbl_arf_forms . "` WHERE is_template=0 AND (status is NULL OR status = '' OR status = 'published') {$where_clause} ORDER BY id DESC" );//phpcs:ignore
		$arforms_forms_lite_list = array();
		$n                       = 0;

		foreach ( $arforms_forms_lite_data as $k => $value ) {
            $arforms_forms_lite_list['']            =__("Please select a form",'arforms-form-builder');
			$arforms_forms_lite_list[$value->id]    = $arflitemainhelper->arflitetruncate( html_entity_decode( stripslashes( $value->name ) ), 33 ) . ' (id: ' . $value->id . ')';
			
		}


        fusion_builder_map(
            fusion_builder_frontend_data(

                'arf_fusion_elements',
                [
                    'name'                     => esc_attr__( 'ARForms', 'arforms-form-builder' ),
                    'shortcode'                => 'ARForms',
                    'icon'                     => 'arforms-logo',
                    'tab'                 => 'ARForms',
                    'params'                   => [
                        [
                            'type'        => 'select',
                            'heading'     => esc_attr__( 'Form', 'arforms-form-builder' ),
                            'param_name'  => 'id',
                            'value'       => $arforms_forms_lite_list,
                        ],
                        [
                            'type'        => 'hidden',
                            'param_name'  => 'is_fusionbuilder',
                            'value'       => true,
                        ],
                    ],
                ]
            )
        );
    }
}
global $fusion_builder_elements;
$fusion_builder_elements = new fusion_builder_elements();

?>