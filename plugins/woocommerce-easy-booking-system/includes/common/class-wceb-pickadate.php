<?php

namespace EasyBooking;

/**
*
* Functions to register pickadate scripts and styles.
* @version 3.0.0
*
**/

defined( 'ABSPATH' ) || exit;

class Pickadate {

	/**
    *
    * Register pickadate.js script and its translation.
    *
    **/
    public static function register_scripts() {

        // Debugging mode
        if ( true === wceb_script_debug() ) {

            wp_register_script(
                'picker',
                plugins_url( 'assets/js/dev/picker.js', WCEB_PLUGIN_FILE  ),
                array( 'jquery' ),
                '1.0',
                true
            );

            wp_register_script(
                'legacy',
                plugins_url( 'assets/js/dev/legacy.js', WCEB_PLUGIN_FILE  ),
                array( 'jquery' ),
                '1.0',
                true
            );

            wp_register_script(
                'pickadate',
                plugins_url( 'assets/js/dev/picker.date.js', WCEB_PLUGIN_FILE  ),
                array( 'jquery', 'picker', 'legacy' ),
                '1.0',
                true
            );

        } else {

            // Concatenated and minified script including picker.js, picker.date.js and legacy.js
            wp_register_script(
                'pickadate',
                plugins_url( 'assets/js/pickadate.min.js', WCEB_PLUGIN_FILE ),
                array( 'jquery' ),
                '1.0',
                true
            );

        }

        // Pickadate.js translation. If it doesn't exist, load English translation file.
        if ( file_exists( plugin_dir_path( WCEB_PLUGIN_FILE ) . 'assets/js/translations/' . WCEB_LANG . '.js' ) ) {
            $translation_file = plugins_url( 'assets/js/translations/' . WCEB_LANG . '.js', WCEB_PLUGIN_FILE );
        } else {
            $translation_file = plugins_url( 'assets/js/translations/en_US.js', WCEB_PLUGIN_FILE );
        }

        wp_register_script(
            'pickadate.language',
            $translation_file,
            array( 'jquery', 'pickadate' ),
            '1.0',
            true
        );

        wp_localize_script(
            'pickadate.language',
            'params',
            array(
                'first_day' => absint( get_option( 'start_of_week' ) )
            )
        );

    }

    /**
    *
    * Register pickadate.js CSS.
    *
    **/
    public static function register_styles() {

    	// Get calendar theme - Force "Default" theme in admin.
    	$theme = is_admin() ? 'default' : get_option( 'wceb_calendar_theme' );

        // If multisite, register the CSS file corresponding to the blog ID
        if ( function_exists( 'is_multisite' ) && is_multisite() ) {
        	
            $blog_id = get_current_blog_id();

            wp_register_style(
                'picker',
                plugins_url( 'assets/css/' . $theme . '.' . $blog_id . '.min.css', WCEB_PLUGIN_FILE ),
                true
            );

        } else {

            wp_register_style(
                'picker',
                plugins_url( 'assets/css/' . $theme . '.min.css', WCEB_PLUGIN_FILE ),
                true
            );

        }

        // Pickadate right-to-left CSS
        if ( is_rtl() ) {

        	wp_register_style(
	            'rtl-style',
	            wceb_get_file_path( '', 'rtl', 'css' ),
	            true
	        );
    	}

    }

}

new Pickadate();