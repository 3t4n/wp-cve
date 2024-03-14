<?php

/*
  Plugin Name: Genesis Simple Comments
  Plugin URI: http://DesignsByNicktheGeek.com
  Version: 1.0.1
  Author: Nick_theGeek
  Author URI: http://DesignsByNicktheGeek.com
  Description: Makes common changes to the Genesis Comments easy as typing your name. Requires Genesis 1.8+
 */

/*
 * To Do:
 *      Create and setup screen shots
 */

/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    wp_die( __( "Sorry, you are not allowed to access this page directly.", 'gsc' ) );
}

define( 'GSC_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'GSC_SETTINGS_FIELD', 'gsc-settings' );

add_action( 'admin_init', 'register_gsc_settings' );

/**
 * This registers the settings field
 */
function register_gsc_settings() {
    register_setting( GSC_SETTINGS_FIELD, GSC_SETTINGS_FIELD );
}

register_activation_hook( __FILE__, 'gsc_activation_check' );

/**
 * Checks for minimum Genesis Theme version before allowing plugin to activate
 *
 * @author Nathan Rice
 * @uses gsc_truncate()
 * @since 0.1
 * @version 0.2
 */
function gsc_activation_check() {

    $latest = '1.7';

    $theme_info = get_theme_data( TEMPLATEPATH . '/style.css' );

    if ( basename( TEMPLATEPATH ) != 'genesis' ) {
        deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate ourself
        wp_die( sprintf( __( 'Sorry, you can\'t activate unless you have installed %1$sGenesis%2$s', 'gsc' ), '<a href="http://designsbynickthegeek.com/go/genesis">', '</a>' ) );
    }

    $version = gsc_truncate( $theme_info['Version'], 3 );

    if ( version_compare( $version, $latest, '<' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate ourself
        wp_die( sprintf( __( 'Sorry, you can\'t activate without %1$sGenesis %2$s%3$s or greater', 'gsc' ), '<a href="http://designsbynickthegeek.com/go/genesis">', $latest, '</a>' ) );
    }
}

/**
 *
 * Used to cutoff a string to a set length if it exceeds the specified length
 *
 * @author Nick Croft
 * @since 0.1
 * @version 0.2
 * @param string $str Any string that might need to be shortened
 * @param string $length Any whole integer
 * @return string
 */
function gsc_truncate( $str, $length=10 ) {

    if ( strlen( $str ) > $length ) {
        return substr( $str, 0, $length );
    } else {
        $res = $str;
    }

    return $res;
}

/**
 * Pull a Simple Comments option from the database, return value
 *
 * @since 0.1
 */
function gsc_get_option( $key, $setting = null ) {

    // get setting
    $setting = $setting ? $setting : GSC_SETTINGS_FIELD;

    // setup caches
    static $settings_cache = array( );
    static $options_cache = array( );

    // Check options cache
    if ( isset( $options_cache[$setting][$key] ) ) {

        // option has been cached
        return $options_cache[$setting][$key];
    }

    // check settings cache
    if ( isset( $settings_cache[$setting] ) ) {

        // setting has been cached
        $options = apply_filters( 'gsc_options', $settings_cache[$setting], $setting );
    } else {

        // set value and cache setting
        $options = $settings_cache[$setting] = apply_filters( 'gsc_options', get_option( $setting ), $setting );
    }

    // check for non-existent option
    if ( !is_array( $options ) || !array_key_exists( $key, ( array ) $options ) ) {

        // cache non-existent option
        $options_cache[$setting][$key] = '';

        return '';
    }

    // option has been cached, cache option
    $options_cache[$setting][$key] = stripslashes( wp_kses_decode_entities( $options[$key] ) );

    return $options_cache[$setting][$key];
}

/**
 * Pull an Simple Comments option from the database, echo value
 *
 * @since 0.1
 */
function gsc_option( $hook = null, $field = null ) {
    echo gsc_get_option( $hook, $field );
}

add_action( 'genesis_init', 'gsc_init', 15 );

/** Loads required files when needed */
function gsc_init() {

    /** Load textdomain for translation */
    load_plugin_textdomain( 'gsc', false, basename( dirname( __FILE__ ) ) . '/languages/' );

    if ( is_admin ( ) )
        require_once(GSC_PLUGIN_DIR . '/admin.php');

    else
        require_once(GSC_PLUGIN_DIR . '/output.php');
    
    if( ! class_exists( 'NTG_Theme_Settings_Builder' ) )
            require_once(GSC_PLUGIN_DIR . '/classes/admin-builder.php');
}
