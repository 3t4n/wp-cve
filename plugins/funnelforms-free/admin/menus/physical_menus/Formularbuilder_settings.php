<?php

require_once FNSF_AF2_MENU_PARENTS_CLASS;
class Fnsf_Af2FormularbuilderSettings extends Fnsf_Af2MenuBuilder {

    protected function fnsf_get_builder_heading() { return array('label' => 'Form editor', 'icon' => 'fas fa-edit'); }
    protected function fnsf_get_builder_template() { return FNSF_AF2_BUILDER_TEMPLATE_FORMULAR_SETTINGS; }
    protected function fnsf_get_builder_sidebar_edit_() { return array('label' => __('Settings' , 'funnelforms-free')); }
    protected function fnsf_get_menu_builder_control_buttons_() { return array(array('id' => 'af2_goto_formularbuilder', 'icon' => 'fas fa-cog', 'label' => __('Back to Editor' , 'funnelforms-free'))); }

    protected function fnsf_get_close_editor_url() { return admin_url('/admin.php?page='.FNSF_FORMULAR_SLUG ); }

    protected function fnsf_get_builder_script() { return 'af2_formularbuilder_settings'; }
    protected function fnsf_get_builder_style() { return 'af2_formularbuilder_settings_style'; }
    protected function fnsf_get_builder_script_object_name() { return 'af2_formularbuilder_settings_object';  }
    protected function fnsf_get_builder_script_localize_array() { 
        $idFobs = sanitize_text_field($_GET['id']);
        return array(
            'redirect_formularbuilder_url' => admin_url('/admin.php?page='.FNSF_FORMULARBUILDER_SLUG.'&id='.$idFobs ),
            'redirect_formularbuilder_preview_url' => admin_url('/admin.php?page='.FNSF_FORMULARBUILDER_PREVIEW_SLUG.'&id='.$idFobs ),
            'standard_success_image' => plugins_url('/res/images/success_standard.png', AF2F_PLUGIN),
            'strings' => array(
                'success_text' => __('Thank you! The form was sent successfully!', 'funnelforms-free'),

            )
        );
    }

    protected function fnsf_get_af2_builder_custom_contents_() {
        $upload_dir = wp_upload_dir();

        $fsnf_af2_fonts_dir = $upload_dir['basedir'] . '/af2_fonts';

        if (!file_exists($fsnf_af2_fonts_dir)) {
            wp_mkdir_p($fsnf_af2_fonts_dir);
        }

        $file_names = array();

        $files = scandir($fsnf_af2_fonts_dir);
        foreach ($files as $file) {
            if (is_file($fsnf_af2_fonts_dir . '/' . $file)) {
                $file_names[] = $file;
            }
        }

        return array("files" => $file_names);
    }
    
    protected function fnsf_get_builder_sidebar_edit_elements_() {
        require_once FNSF_AF2_MENU_FORMULARBUILDER_SETTINGS_ELEMENTS_PATH;
        return fnsf_get_formularbuilder_settings_elements($this->fnsf_get_af2_builder_custom_contents_());
    }

    protected function fnsf_load_resources() {
        require_once FNSF_AF2_RESOURCE_HANDLER_PATH;
        load_media_iconpicker();
        load_colorpicker();

        parent::fnsf_load_resources();
    }


    public static function fnsf_save_function($content) {
        $own_content = $content;
        $echo_content = array();

        if(!isset($own_content['name']) || trim($own_content['name']) == '') {
            array_push($echo_content, array('label' => __('No title specified!', 'funnelforms-free'), 'type' => 'af2_error'));
        }

        $keys = array('fe_title', 'global_next_text', 'global_prev_text');
        foreach($own_content['styling'] as $key => $value) {
            if(in_array($key, $keys)) continue;
            if(!isset($value) || trim($value) == '') {
                array_push($echo_content, array('label' => __('Design values can not be saved empty!', 'funnelforms-free'), 'type' => 'af2_error'));
            }
        }


        if(isset($own_content['sections']) && is_array($own_content['sections']) && sizeof($own_content['sections']) > 6) {
            array_push($echo_content, array('label' => __('In the Free Version, a maximum of 6 elements can be arranged in a row!', 'funnelforms-free'), 'type' => 'af2_error'));
        }


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