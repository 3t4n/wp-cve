<?php

/**
 * Class LSOW_Custom_Field_Datepicker
 */
class LSOW_Custom_Field_Timepicker extends SiteOrigin_Widget_Field_Text_Input_Base {

    protected function get_input_classes() {
        $input_classes = parent::get_input_classes();
        $input_classes[] = 'lsow-widget-input-timepicker';
        return $input_classes;
    }

    public function enqueue_scripts() {

        /* load jQuery-ui datepicker */
        wp_enqueue_script('jquery-ui-datepicker');
        
        wp_enqueue_script( 'jquery-ui-slider' );

        wp_enqueue_script('lsow-timepicker-addon', plugin_dir_url(__FILE__) . 'js/jquery-ui-timepicker-addon' . LSOW_JS_SUFFIX . '.js', array('jquery', 'jquery-ui-datepicker' , 'jquery-ui-slider'), SOW_BUNDLE_VERSION);

        wp_enqueue_style('lsow-timepicker-addon-css', plugin_dir_url(__FILE__) . 'css/jquery-ui-timepicker-addon.css', false, "1.6.3", false);

        wp_enqueue_script('lsow-timepicker-field', plugin_dir_url(__FILE__) . 'js/timepicker-field' . LSOW_JS_SUFFIX . '.js', array('jquery', 'jquery-ui-datepicker', 'lsow-timepicker-addon'), SOW_BUNDLE_VERSION);

        wp_enqueue_style('lsow-timepicker-css', plugin_dir_url(__FILE__) . 'css/jquery-ui.css', false, "1.11.4", false);
    }
}