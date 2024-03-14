<?php
class arforms_fusion_builder{
    function __construct(){
        add_action( 'fusion_builder_shortcodes_init', array( $this, 'arflite_load_fusion_elements') );
    }

    function arflite_load_fusion_elements()
    {
        require_once ARFLITE_FORMPATH.'/integrations/fusion/fusion_builder.php';
    }
}
global $arforms_fusion_builder;
$arforms_fusion_builder = new arforms_fusion_builder();
?>