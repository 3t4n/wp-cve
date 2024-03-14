<?php

require_once FNSF_AF2_MENU_PARENTS_CLASS;
class Fnsf_Af2FormularbuilderPreview extends Fnsf_Af2MenuBuilder {

    protected function fnsf_get_builder_heading() { return array('label' => 'Form preview', 'icon' => 'fas fa-share-square'); }
    protected function fnsf_get_builder_template() { return FNSF_AF2_BUILDER_TEMPLATE_FORMULAR_PREVIEW; }
    protected function fnsf_get_menu_builder_control_buttons_() { return array(array('id' => 'af2_goto_formularbuilder_settings', 'icon' => 'fas fa-cog', 'label' => __('Back to Editor' , 'funnelforms-free') )); }
    protected function fnsf_get_menu_builder_pre_control_buttons_() { 
        return array(
            array('id' => 'device_button_mobile', 'icon' => 'fas fa-mobile-alt', 'label' => 'Mobil' ),
            array('id' => 'device_button_ipad', 'icon' => 'fas fa-tablet-alt', 'label' => 'Tablet' ),
            array('id' => 'device_button_desktop', 'icon' => 'fas fa-desktop', 'label' => 'Desktop' )
        ); 
    }

    protected function fnsf_get_close_editor_url() { return admin_url('/admin.php?page='.FNSF_FORMULAR_SLUG ); }

    protected function fnsf_get_builder_script() { return 'af2_formularbuilder_preview'; }
    protected function fnsf_get_builder_style() { return 'af2_formularbuilder_preview_style'; }
    protected function fnsf_get_builder_script_object_name() { return 'af2_formularbuilder_preview_object';  }
    protected function fnsf_get_builder_script_localize_array() { 
        $idFob = sanitize_text_field($_GET['id']);
        return array(
            'redirect_formularbuilder_settings_url' => admin_url('/admin.php?page='.FNSF_FORMULARBUILDER_SETTINGS_SLUG.'&id='.$idFob ),
            'strings' => array(

            )
        );
    }
    
    protected function fnsf_load_resources() {
        require_once FNSF_AF2_RESOURCE_HANDLER_PATH;
        af2_load_frontend_resources();
        load_basic_frontend_resources();

        parent::fnsf_load_resources();
    }


    public static function fnsf_save_function($content) {
        $own_content = $content;
        $echo_content = array();


        array_push($echo_content, array('label' => __('Saved successfully!', 'funnelforms-free'), 'type' => 'af2_success'));


        $own_content['error'] = false;

        foreach( $echo_content as $content ) {
            if($content['type'] == 'af2_error') {
                $own_content['error'] = true;
                break;
            }
        }

        echo json_encode($echo_content);
        return $own_content;
    }
}