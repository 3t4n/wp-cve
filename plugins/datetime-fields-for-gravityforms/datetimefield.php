<?php
/*
Plugin Name: Date/Time Fields for GravityForms
Plugin URI: http://www.gravityforms.com
Description: Create a new custom field for "GravityForms" plugin called "Date/Time" field
Version: 1.0
Author: EFE Technology
Author URI: http://efe.com.vn/
*/

define( 'GF_DATE_TIME_FIELDS_VERSION', '1.0' );

add_action( 'gform_loaded', array( 'GF_Date_Time_Field_Bootstrap', 'load' ), 5 );

class GF_Date_Time_Field_Bootstrap {

    public static function load() {

        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }

        require_once( 'class-gfdatetimefield.php' );

        GFAddOn::register( 'GFDateTimeField' );
    }

}