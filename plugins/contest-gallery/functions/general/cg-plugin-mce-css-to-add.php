<?php
add_action('cg_plugin_mce_css_to_add','cg_plugin_mce_css_to_add');
if(!function_exists('cg_plugin_mce_css_to_add')){
    function cg_plugin_mce_css_to_add( $mce_css ) {
        if ( !empty( $mce_css ) ){
            $mce_css .= ',';
            $mce_css .= plugins_url( '../../v10/v10-css/backend/cg_tinymce.css', __FILE__ );
            return $mce_css;
        }else{
            return '';
        }
    }
}
