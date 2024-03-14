<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Settings_CountDown_Styles_Page' ) ) {

    ReonUtil::recursive_require( dirname( __FILE__ ), array( 'settings-ui-countdown-sections.php' ) );

    class WModes_Admin_Settings_CountDown_Styles_Page {

        public static function init() {
            
            $option_name = WModes_Admin_Page::get_option_name();

            add_filter( 'get-option-page-' . $option_name . 'section-countdown_styles_settings-fields', array( new self(), 'get_fields' ), 10 );
        }

        public static function get_fields( $in_fields ) {

            return apply_filters( 'wmodes-admin/get-settings-countdown-styles-section-panels', $in_fields );
        }
    }

}