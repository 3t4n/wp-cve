<?php

/**
 * Class LSOW_Custom_Field_Datepicker
 */
class LSOW_Custom_Field_Datepicker extends SiteOrigin_Widget_Field_Text_Input_Base {

    protected function get_input_classes() {
        $input_classes = parent::get_input_classes();
        $input_classes[] = 'lsow-widget-input-datepicker';
        return $input_classes;
    }

    public function enqueue_scripts() {

        /* load jQuery-ui datepicker */
        wp_enqueue_script('jquery-ui-datepicker');
        
        wp_enqueue_script('lsow-datepicker-field', plugin_dir_url(__FILE__) . 'js/datepicker-field' . LSOW_JS_SUFFIX . '.js', array('jquery', 'jquery-ui-datepicker'), SOW_BUNDLE_VERSION);

        wp_enqueue_style('lsow-datepicker-css', plugin_dir_url(__FILE__) . 'css/jquery-ui.css', false, "1.11.4", false);
    }
}