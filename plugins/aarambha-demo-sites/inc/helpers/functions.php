<?php

/**
 * Helper file for defining helper functions.
 * 
 * @since       1.0.0
 * @package     Aarambha_Demo_Sites
 * @subpackage  Aarambha_Demo_Sites/Inc/Helpers
 */

if (!defined('WPINC')) {
    exit;    // Exit if accessed directly.
}


/**
 * Get active theme slug.
 * 
 * @since 1.0.0
 * 
 * @return string
 */
function aarambha_ds_get_theme()
{
    if (defined('AARAMBHA_DS_THEME')) {
        return AARAMBHA_DS_THEME;
    }

    $activeTheme = wp_get_theme();

    if ($activeTheme->get('Template')) {
        return $activeTheme->get('Template');
    }

    return $activeTheme->get('TextDomain');
}

/**
 * Get active theme slug.
 * 
 * @since 1.0.0
 * 
 * @return string
 */
function aarambha_ds_get_actual_theme()
{
    $activeTheme = wp_get_theme();

    if ($activeTheme->get('Template')) {
        return $activeTheme->get('Template');
    }

    return $activeTheme->get('TextDomain');
}

/**
 * Get active theme author name.
 * 
 * @since 1.0.0
 * 
 * @return string
 */
function aarambha_ds_get_theme_author()
{
    $theme = wp_get_theme();

    if ($theme->get('Template')) {
        $theme = wp_get_theme($theme->get('Template'));
    }

    return $theme->get('Author');
}

/**
 * Returns the parent theme name.
 * 
 * @since 1.0.0
 * 
 * @return string
 */
function aarambha_ds_get_theme_name()
{
    $theme = wp_get_theme();

    if ($theme->get('Template')) {
        $theme = wp_get_theme($theme->get('Template'));
    }

    return $theme->get('Name');
}

/**
 * Returns the WordPress uploads base directory.
 * 
 * @since 1.0.0
 * 
 * @return string Path to wordpress uploads folder.
 */
function aarambha_ds_get_upload_base_dir()
{
    $wp_upload  = wp_upload_dir();
    $base_dir   = $wp_upload['basedir'];

    return $base_dir;
}

/**
 *  Custom directory path.
 * 
 * @since 1.0.0
 * 
 * @return string
 */
function aarambha_ds_get_custom_uploads_dir()
{

    $upload_dir       = aarambha_ds_get_theme();
    $base_dir         = aarambha_ds_get_upload_base_dir();
    $demos_dir        = "{$base_dir}/{$upload_dir}-templates";

    return $demos_dir;
}

/**
 * Get the demos directory.
 * 
 * @since 1.0.0
 * @param string $slug Demo slug.
 * 
 * @return string.
 */
function aarambha_ds_get_demos_dir($slug)
{
    $baseDir =  aarambha_ds_get_custom_uploads_dir();

    return "{$baseDir}/$slug";
}

/**
 * Get the available widgets.
 * 
 * @return array
 */
function aarambha_ds_get_available_widgets()
{
    global $wp_registered_widget_controls;

    $widget_controls = $wp_registered_widget_controls;

    $available_widgets = [];

    foreach ($widget_controls as $widget) {

        // No duplicates.
        if (!empty($widget['id_base']) && !isset($available_widgets[$widget['id_base']])) {
            $available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
            $available_widgets[$widget['id_base']]['name']    = $widget['name'];
        }
    }

    return apply_filters('wie_available_widgets', $available_widgets);
}

/**
 * Error Log
 *
 * A wrapper function for the error_log() function.
 *
 * @since 1.0.0
 *
 * @param  mixed $message Error message.
 * 
 * @return void
 */
function aarambha_ds_error_log($message = '')
{
    if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {

        if (is_array($message)) {
            $message = wp_json_encode($message);
        }

        error_log($message); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
    }
}


/**
 * Recursive sanitation for text or array
 * 
 * @param $array_or_string (array|string)
 * @since  1.0.0
 * @return mixed
 */
function aarambha_ds_sanitize_text_or_array_field($array_or_string) {
    
    if( is_string($array_or_string) ){
        $array_or_string = sanitize_text_field($array_or_string);
    }elseif( is_array($array_or_string) ){
        foreach ( $array_or_string as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = aarambha_ds_sanitize_text_or_array_field($value);
            }
            else {
                $value = sanitize_text_field( $value );
            }
        }
    }

    return $array_or_string;
}


/**
 * After demo imported action.
 *
 * @since @since 1.1.3
 * @see aarambha_ds_set_elementor_load_fa4_shim()
 */
add_action( 'aarambha_ds_after_demo_imported', 'aarambha_ds_set_elementor_load_fa4_shim' );

/**
 * Set Elementor Load FontAwesome 4 support.
 *
 * @since @since 1.1.3
 */
function aarambha_ds_set_elementor_load_fa4_shim() {
    $elementor_load_fa4_shim = get_option( 'elementor_load_fa4_shim' );

    if ( ! $elementor_load_fa4_shim || '' === $elementor_load_fa4_shim ) {
        update_option( 'elementor_load_fa4_shim', 'yes' );
    }
}

/**
 * After demo imported action.
 *
 * @since 1.1.3
 * @see aarambha_ds_set_elementor_active_kit()
 */
add_action( 'aarambha_ds_after_demo_imported', 'aarambha_ds_set_elementor_active_kit' );

/**
 * Set Elementor kit properly.
 *
 * @since 1.1.3
 */
function aarambha_ds_set_elementor_active_kit() {

    $elementor_version = defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : false;

    if ( version_compare( $elementor_version, '3.0.0', '>=' ) ) {

        global $wpdb;
        $page_ids = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE (post_name = %s OR post_title = %s) AND post_type = 'elementor_library' AND post_status = 'publish'", 'default-kit', 'Default Kit' ) );

        if ( ! is_null( $page_ids ) ) {

            $page_id    = 0;
            $delete_ids = array();

            // Retrieve page with greater id and delete others.
            if ( sizeof( $page_ids ) > 1 ) {

                foreach ( $page_ids as $page ) {
                    if ( $page->ID > $page_id ) {
                        if ( $page_id ) {
                            $delete_ids[] = $page_id;
                        }

                        $page_id = $page->ID;
                    } else {
                        $delete_ids[] = $page->ID;
                    }
                }
            } else {
                $page_id = $page_ids[0]->ID;
            }

            // Update `elementor_active_kit` page.
            if ( $page_id > 0 ) {
                wp_update_post(
                    array(
                        'ID'        => $page_id,
                        'post_name' => sanitize_title( 'Default Kit' ),
                    )
                );
                update_option( 'elementor_active_kit', $page_id );
            }
        }
    }
}








