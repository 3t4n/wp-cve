<?php

if (!class_exists('Reon')) {
    return;
}

if (!class_exists('WModes_Admin_Settings_Section_Page')) {

    ReonUtil::recursive_require( dirname( __FILE__ ), array( 'settings-section.php' ) );
    
    class WModes_Admin_Settings_Section_Page {

        public static function init() {
            
            if( class_exists( 'WModes_Admin_Custom_CSS_Settings')){
                //this is NOT the usual method
                WModes_Admin_Custom_CSS_Settings::init(); 
            }
            
            $option_name = WModes_Admin_Page::get_option_name();

           add_filter('get-option-page-' . $option_name . 'section-settings-fields', array(new self(), 'get_fields'), 10); 
        }

        public static function get_fields($in_fields) {
            
            return apply_filters('wmodes-admin/get-settings-section-panels', $in_fields);
        }

    }

}
        
 