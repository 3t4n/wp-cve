<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Settings_Styles_Section_Page' ) ) {

    ReonUtil::recursive_require( dirname( __FILE__ ), array( 'settings-ui-setions.php' ) );

    class WModes_Admin_Settings_Styles_Section_Page {

        public static function init() {

            $option_name = WModes_Admin_Page::get_option_name();

            add_filter( 'get-option-page-' . $option_name . 'section-styles_settings-fields', array( new self(), 'get_fields' ), 10 );
        }

        public static function get_fields( $in_fields ) {

            return apply_filters( 'wmodes-admin/get-settings-styles-section-panels', $in_fields );
        }

    }

}
