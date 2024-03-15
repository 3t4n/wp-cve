<?php
if (! defined ( 'ABSPATH' )) {
	die ();
}


if(!function_exists("enqueue_fields_styles")){
    function wpsf_load_fields_styles(){
        wp_enqueue_media(); 
        wp_enqueue_script( 'jquery-ui-dialog' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'jquery-ui-accordion' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script('wpsf-plugins');
        wp_enqueue_script('wpsf-fields');
        wp_enqueue_script('wpsf-framework');
        
        
        wp_enqueue_style( 'wp-jquery-ui-dialog' ); 
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style('font-awesome');
        wp_enqueue_style('wpsf-plugins');
        wp_enqueue_style('wpsf-framework');
        
        if (WPSF_ACTIVE_LIGHT_THEME) {
			wp_enqueue_style ( 'wpsf-framework-theme');
		}
		
		if (is_rtl ()) {
			wp_enqueue_style ( 'wpsf-framework-rtl' );
		}
    }
}

if (! function_exists ( 'wpsf_admin_enqueue_scripts' )) {
	function wpsf_admin_enqueue_scripts() {
        $css_files = array(
            'wpsf-plugins' => array('/assets/css/wpsf-plugins.css',array(),'1.0.0','all'),
            'wpsf-framework' => array('/assets/css/wpsf-framework.css',array(),'1.0.0','all'),
            'font-awesome' => array('/assets/css/font-awesome.css',array(),'4.7.0','all'),
            'wpsf-framework-theme' => array('/assets/css/wpsf-framework-light.css',array(),'1.0.0','all'),
            'wpsf-framework-rtl' => array('/assets/css/wpsf-framework-rtl.css',array(),'1.0.0','all'),
        );
        
        $js_files = array(
            'wpsf-plugins' => array('/assets/js/wpsf-plugins.js',array(),'1.0.0',true),
            'wpsf-fields' => array('/assets/js/wpsf-fields.js',array('wpsf-plugins'),'1.0.0',true),
            'wpsf-framework' => array('/assets/js/wpsf-framework.js',array('wpsf-fields'),'1.0.0',true),
            'wpsf-quick-edit' => array('/assets/js/wpsf-quick-edit.js',null,'1.0','',true),
        );
        
        foreach($css_files as $id => $file){
            wp_register_style($id,WPSF_URI.$file[0],$file[1],$file[2],$file[3]);
        }
        
        foreach($js_files as $id => $file){
            wp_register_script($id,WPSF_URI.$file[0],$file[1],$file[2],$file[3]);
        }
	}
	add_action ( 'admin_enqueue_scripts', 'wpsf_admin_enqueue_scripts',1);
}