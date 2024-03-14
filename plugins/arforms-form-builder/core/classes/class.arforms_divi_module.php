<?php
class arf_divi_builder{
    function __construct(){
        add_action( 'wp_ajax_arforms_divi_preview', array( $this, 'arforms_divi_module_preview' ) );

        add_action( 'divi_extensions_init', array( $this, 'arforms_load_divi_extension' ) );
    }

    function arforms_divi_module_preview(){

       $form_id = !empty($_POST['form_id']) ? intval($_POST['form_id']) : '';

       $params = '';
       $params = ' is_divibuilder="true" ';

        if ( is_plugin_active( 'arforms/arforms.php' ) ) {
            $form_string = do_shortcode( '[ARForms id='.$form_id.' '.$params.' ]' );
        } else {
            $form_string = do_shortcode( '[ARForms id='.$form_id.' '.$params.' ]' );
        }

        wp_send_json_success(
            $form_string
		);
    }

    function arforms_load_divi_extension(){
        require_once ARFLITE_FORMPATH.'/integrations/Divi/class.arforms_divi_extension.php';
    }
}
global $arf_divi_builder;
$arf_divi_builder =new arf_divi_builder();

?>