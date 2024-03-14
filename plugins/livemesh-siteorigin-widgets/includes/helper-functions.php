<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
function lsow_get_terms( $taxonomy )
{
    global  $wpdb ;
    $term_coll = array();
    
    if ( taxonomy_exists( $taxonomy ) ) {
        $terms = get_terms( $taxonomy );
        // Get all terms of a taxonomy
        if ( $terms && !is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $term_coll[$term->term_id] = $term->name;
            }
        }
    } else {
        $qt = 'SELECT * FROM ' . $wpdb->terms . ' AS t INNER JOIN ' . $wpdb->term_taxonomy . ' AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy =  "' . $taxonomy . '" AND tt.count > 0 ORDER BY  t.term_id DESC LIMIT 0 , 30';
        $terms = $wpdb->get_results( $qt, ARRAY_A );
        if ( $terms && !is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $term_coll[$term['term_id']] = $term['name'];
            }
        }
    }
    
    return $term_coll;
}

function lsow_entry_terms_list(
    $taxonomy = 'category',
    $separator = ', ',
    $before = ' ',
    $after = ' '
)
{
    global  $post ;
    $output = '<span class="lsow-' . $taxonomy . '-list">';
    $output .= get_the_term_list(
        $post->ID,
        $taxonomy,
        $before,
        $separator,
        $after
    );
    $output .= '</span>';
    return $output;
}

function lsow_get_chosen_terms( $query_args )
{
    $chosen_terms = array();
    $taxonomies = array();
    
    if ( !empty($query_args) && !empty($query_args['tax_query']) ) {
        $term_queries = $query_args['tax_query'];
        foreach ( $term_queries as $terms_query ) {
            if ( !is_array( $terms_query ) ) {
                continue;
            }
            $field = $terms_query['field'];
            $taxonomy = $terms_query['taxonomy'];
            $terms = $terms_query['terms'];
            if ( empty($taxonomy) || empty($terms) ) {
                continue;
            }
            if ( !in_array( $taxonomy, $taxonomies ) ) {
                $taxonomies[] = $taxonomy;
            }
            
            if ( is_array( $terms ) ) {
                foreach ( $terms as $term ) {
                    $chosen_terms[] = get_term_by( $field, $term, $taxonomy );
                }
            } else {
                $chosen_terms[] = get_term_by( $field, $terms, $taxonomy );
            }
        
        }
    }
    
    // Remove duplicates
    $taxonomies = array_unique( $taxonomies );
    $return = array( $chosen_terms, $taxonomies );
    return apply_filters( 'lsow_chosen_taxonomy_terms', $return, $query_args );
}

function lsow_get_taxonomy_info( $taxonomy )
{
    $output = '';
    $terms = get_the_terms( get_the_ID(), $taxonomy );
    
    if ( !empty($terms) && !is_wp_error( $terms ) ) {
        $output .= '<span class="lsow-terms">';
        $term_count = 0;
        foreach ( $terms as $term ) {
            $term_link = get_term_link( $term->slug, $taxonomy );
            
            if ( !empty($term_link) && !is_wp_error( $term_link ) ) {
                if ( $term_count != 0 ) {
                    $output .= ', ';
                }
                $output .= '<a href="' . get_term_link( $term->slug, $taxonomy ) . '">' . $term->name . '</a>';
                $term_count = $term_count + 1;
            }
        
        }
        $output .= '</span>';
    }
    
    return apply_filters( 'lsow_taxonomy_info', $output, $taxonomy );
}

function lsow_get_info_for_taxonomies( $taxonomies )
{
    $output = '';
    foreach ( $taxonomies as $taxonomy ) {
        $output .= lsow_get_taxonomy_info( $taxonomy );
    }
    return apply_filters( 'lsow_taxonomies_info', $output, $taxonomies );
}

// get all registered taxonomies
function lsow_get_taxonomies_map()
{
    $map = array();
    $taxonomies = lsow_get_all_taxonomies();
    foreach ( $taxonomies as $taxonomy ) {
        $map[$taxonomy] = $taxonomy;
    }
    return apply_filters( 'lsow_taxonomies_map', $map );
}

function lsow_get_all_taxonomies()
{
    $taxonomies = get_taxonomies( array(
        'public'   => true,
        '_builtin' => false,
    ) );
    $taxonomies = array_merge( array(
        'category' => 'category',
        'post_tag' => 'post_tag',
    ), $taxonomies );
    return $taxonomies;
}

function lsow_entry_published( $format = null )
{
    if ( empty($format) ) {
        $format = get_option( 'date_format' );
    }
    $published = '<span class="published"><abbr title="' . sprintf( get_the_time( esc_html__( 'l, F, Y, g:i a', 'livemesh-so-widgets' ) ) ) . '">' . sprintf( get_the_time( $format ) ) . '</abbr></span>';
    return apply_filters( 'lsow_entry_published', $published, $format );
    $link = '<span class="published">' . '<a href="' . get_day_link( get_the_time( esc_html__( 'Y', 'livemesh-so-widgets' ) ), get_the_time( esc_html__( 'm', 'livemesh-so-widgets' ) ), get_the_time( esc_html__( 'd', 'livemesh-so-widgets' ) ) ) . '" title="' . sprintf( get_the_time( esc_html__( 'l, F, Y, g:i a', 'livemesh-so-widgets' ) ) ) . '">' . '<span class="updated">' . get_the_time( $format ) . '</span>' . '</a></span>';
    return apply_filters( 'lsow_entry_published_link', $link, $format );
}

function lsow_entry_author()
{
    $author = '<span class="author vcard">' . esc_html__( 'By ', 'livemesh-so-widgets' ) . '<a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author_meta( 'display_name' ) ) . '">' . esc_html( get_the_author_meta( 'display_name' ) ) . '</a></span>';
    return apply_filters( 'lsow_entry_author', $author );
}

/* Return the css class name to help achieve the number of columns specified for mobile resolution */
function lsow_get_grid_classes( $settings, $columns_field = 'per_line' )
{
    $grid_classes = ' lsow-grid-desktop-';
    $grid_classes .= $settings[$columns_field];
    $grid_classes .= ' lsow-grid-tablet-';
    $grid_classes .= $settings[$columns_field . '_tablet'];
    $grid_classes .= ' lsow-grid-mobile-';
    $grid_classes .= $settings[$columns_field . '_mobile'];
    return apply_filters(
        'lsow_grid_classes',
        $grid_classes,
        $settings,
        $columns_field
    );
}

/*
* Converting string to boolean is a big one in PHP
*/
function lsow_to_boolean( $value )
{
    if ( !isset( $value ) ) {
        return false;
    }
    
    if ( $value == 'true' || $value == '1' ) {
        $value = true;
    } elseif ( $value == 'false' || $value == '0' ) {
        $value = false;
    }
    
    return (bool) $value;
    // Make sure you do not touch the value if the value is not a string
}

/**
 * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.7
 * @param str $hex Colour as hexadecimal (with or without hash);
 * @percent float $percent Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() )
 * @return str Lightened/Darkend colour as hexadecimal (with hash);
 */
function lsow_color_luminance( $hex, $percent )
{
    // validate hex string
    $hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
    $new_hex = '#';
    if ( strlen( $hex ) < 6 ) {
        $hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
    }
    // convert to decimal and change luminosity
    for ( $i = 0 ;  $i < 3 ;  $i++ ) {
        $dec = hexdec( substr( $hex, $i * 2, 2 ) );
        $dec = min( max( 0, $dec + $dec * $percent ), 255 );
        $new_hex .= str_pad(
            dechex( $dec ),
            2,
            0,
            STR_PAD_LEFT
        );
    }
    return $new_hex;
}

function lsow_get_option( $option_name, $default = null )
{
    $settings = get_option( 'lsow_settings' );
    
    if ( !empty($settings) && isset( $settings[$option_name] ) ) {
        $option_value = $settings[$option_name];
    } else {
        $option_value = $default;
    }
    
    return apply_filters(
        'lsow_get_option',
        $option_value,
        $option_name,
        $default
    );
}

function lsow_update_option( $option_name, $option_value )
{
    $settings = get_option( 'lsow_settings' );
    if ( empty($settings) ) {
        $settings = array();
    }
    $settings[$option_name] = $option_value;
    update_option( 'lsow_settings', $settings );
}

/**
 * Update multiple options in one go
 * @param array $setting_data An collection of settings key value pairs;
 */
function lsow_update_options( $setting_data )
{
    $settings = get_option( 'lsow_settings' );
    if ( empty($settings) ) {
        $settings = array();
    }
    foreach ( $setting_data as $setting => $value ) {
        // because of get_magic_quotes_gpc()
        $value = stripslashes( $value );
        $settings[$setting] = $value;
    }
    update_option( 'lsow_settings', $settings );
}

/**
 * Get system info
 *
 */
function lsow_get_sysinfo()
{
    global  $wpdb ;
    // Get theme info
    $theme_data = wp_get_theme();
    $theme = $theme_data->Name . ' ' . $theme_data->Version;
    $return = '### <strong>Begin System Info</strong> ###' . "\n\n";
    // Start with the basics...
    $return .= '-- Site Info' . "\n\n";
    $return .= 'Site URL:                 ' . site_url() . "\n";
    $return .= 'Home URL:                 ' . home_url() . "\n";
    $return .= 'Multisite:                ' . (( is_multisite() ? 'Yes' : 'No' )) . "\n";
    // Theme info
    $plugin = get_plugin_data( LSOW_PLUGIN_FILE );
    // Plugin configuration
    $return .= "\n" . '-- Plugin Configuration' . "\n\n";
    $return .= 'Name:                     ' . $plugin['Name'] . "\n";
    $return .= 'Version:                  ' . $plugin['Version'] . "\n";
    // WordPress configuration
    $return .= "\n" . '-- WordPress Configuration' . "\n\n";
    $return .= 'Version:                  ' . get_bloginfo( 'version' ) . "\n";
    $return .= 'Language:                 ' . (( defined( 'WPLANG' ) && WPLANG ? WPLANG : 'en_US' )) . "\n";
    $return .= 'Permalink Structure:      ' . (( get_option( 'permalink_structure' ) ? get_option( 'permalink_structure' ) : 'Default' )) . "\n";
    $return .= 'Active Theme:             ' . $theme . "\n";
    $return .= 'Show On Front:            ' . get_option( 'show_on_front' ) . "\n";
    // Only show page specs if frontpage is set to 'page'
    
    if ( get_option( 'show_on_front' ) == 'page' ) {
        $front_page_id = get_option( 'page_on_front' );
        $blog_page_id = get_option( 'page_for_posts' );
        $return .= 'Page On Front:            ' . (( $front_page_id != 0 ? get_the_title( $front_page_id ) . ' (#' . $front_page_id . ')' : 'Unset' )) . "\n";
        $return .= 'Page For Posts:           ' . (( $blog_page_id != 0 ? get_the_title( $blog_page_id ) . ' (#' . $blog_page_id . ')' : 'Unset' )) . "\n";
    }
    
    $return .= 'ABSPATH:                  ' . ABSPATH . "\n";
    $return .= 'WP_DEBUG:                 ' . (( defined( 'WP_DEBUG' ) ? ( WP_DEBUG ? 'Enabled' : 'Disabled' ) : 'Not set' )) . "\n";
    $return .= 'Memory Limit:             ' . WP_MEMORY_LIMIT . "\n";
    $return .= 'Registered Post Stati:    ' . implode( ', ', get_post_stati() ) . "\n";
    // Get plugins that have an update
    $updates = get_plugin_updates();
    // WordPress active plugins
    $return .= "\n" . '-- WordPress Active Plugins' . "\n\n";
    $plugins = get_plugins();
    $active_plugins = get_option( 'active_plugins', array() );
    foreach ( $plugins as $plugin_path => $plugin ) {
        if ( !in_array( $plugin_path, $active_plugins ) ) {
            continue;
        }
        $update = ( array_key_exists( $plugin_path, $updates ) ? ' (needs update - ' . $updates[$plugin_path]->update->new_version . ')' : '' );
        $return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
    }
    // WordPress inactive plugins
    $return .= "\n" . '-- WordPress Inactive Plugins' . "\n\n";
    foreach ( $plugins as $plugin_path => $plugin ) {
        if ( in_array( $plugin_path, $active_plugins ) ) {
            continue;
        }
        $update = ( array_key_exists( $plugin_path, $updates ) ? ' (needs update - ' . $updates[$plugin_path]->update->new_version . ')' : '' );
        $return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
    }
    
    if ( is_multisite() ) {
        // WordPress Multisite active plugins
        $return .= "\n" . '-- Network Active Plugins' . "\n\n";
        $plugins = wp_get_active_network_plugins();
        $active_plugins = get_site_option( 'active_sitewide_plugins', array() );
        foreach ( $plugins as $plugin_path ) {
            $plugin_base = plugin_basename( $plugin_path );
            if ( !array_key_exists( $plugin_base, $active_plugins ) ) {
                continue;
            }
            $update = ( array_key_exists( $plugin_path, $updates ) ? ' (needs update - ' . $updates[$plugin_path]->update->new_version . ')' : '' );
            $plugin = get_plugin_data( $plugin_path );
            $return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
        }
    }
    
    // Server configuration (really just versioning)
    $return .= "\n" . '-- Webserver Configuration' . "\n\n";
    $return .= 'PHP Version:              ' . PHP_VERSION . "\n";
    $return .= 'MySQL Version:            ' . $wpdb->db_version() . "\n";
    $return .= 'Webserver Info:           ' . $_SERVER['SERVER_SOFTWARE'] . "\n";
    // PHP configs... now we're getting to the important stuff
    $return .= "\n" . '-- PHP Configuration' . "\n\n";
    $return .= 'Memory Limit:             ' . ini_get( 'memory_limit' ) . "\n";
    $return .= 'Upload Max Size:          ' . ini_get( 'upload_max_filesize' ) . "\n";
    $return .= 'Post Max Size:            ' . ini_get( 'post_max_size' ) . "\n";
    $return .= 'Upload Max Filesize:      ' . ini_get( 'upload_max_filesize' ) . "\n";
    $return .= 'Time Limit:               ' . ini_get( 'max_execution_time' ) . "\n";
    $return .= 'Max Input Vars:           ' . ini_get( 'max_input_vars' ) . "\n";
    $return .= 'Display Errors:           ' . (( ini_get( 'display_errors' ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A' )) . "\n";
    $return = apply_filters( 'edd_sysinfo_after_php_config', $return );
    // PHP extensions and such
    $return .= "\n" . '-- PHP Extensions' . "\n\n";
    $return .= 'cURL:                     ' . (( function_exists( 'curl_init' ) ? 'Supported' : 'Not Supported' )) . "\n";
    $return .= 'fsockopen:                ' . (( function_exists( 'fsockopen' ) ? 'Supported' : 'Not Supported' )) . "\n";
    $return .= 'SOAP Client:              ' . (( class_exists( 'SoapClient' ) ? 'Installed' : 'Not Installed' )) . "\n";
    $return .= 'Suhosin:                  ' . (( extension_loaded( 'suhosin' ) ? 'Installed' : 'Not Installed' )) . "\n";
    $return .= "\n" . '### End System Info ###';
    return $return;
}

/** Isotope filtering support for Portfolio pages **/
function lsow_get_taxonomy_terms_filter( $taxonomies, $chosen_terms = array() )
{
    $output = '';
    $terms = array();
    
    if ( empty($chosen_terms) ) {
        foreach ( $taxonomies as $taxonomy ) {
            global  $wp_version ;
            
            if ( version_compare( $wp_version, '4.5', '>=' ) ) {
                $taxonomy_terms = get_terms( array(
                    'taxonomy' => $taxonomy,
                ) );
            } else {
                $taxonomy_terms = get_terms( $taxonomy );
            }
            
            if ( !empty($taxonomy_terms) && !is_wp_error( $taxonomy_terms ) ) {
                $terms = array_merge( $terms, $taxonomy_terms );
            }
        }
    } else {
        $terms = $chosen_terms;
    }
    
    
    if ( !empty($terms) ) {
        $output .= '<div class="lsow-taxonomy-filter">';
        $output .= '<div class="lsow-filter-item segment-0 lsow-active"><a data-value="*" href="#">' . esc_html__( 'All', 'livemesh-so-widgets' ) . '</a></div>';
        $segment_count = 1;
        foreach ( $terms as $term ) {
            $output .= '<div class="lsow-filter-item segment-' . intval( $segment_count ) . '"><a href="#" data-value=".term-' . intval( $term->term_id ) . '" title="' . esc_html__( 'View all items filed under ', 'livemesh-so-widgets' ) . esc_attr( $term->name ) . '">' . esc_html( $term->name ) . '</a></div>';
            $segment_count++;
        }
        $output .= '</div>';
    }
    
    return apply_filters(
        'lsow_taxonomy_terms_filter',
        $output,
        $taxonomies,
        $chosen_terms
    );
}

function lsow_get_animation_atts( $animation )
{
    $animate_class = "";
    $animation_attr = "";
    
    if ( $animation != "none" ) {
        $animate_class = ' lsow-animate-on-scroll';
        if ( in_array( $animation, array(
            'bounceIn',
            'bounceInUp',
            'bounceInDown',
            'bounceInLeft',
            'bounceInRight',
            'fadeIn',
            'fadeInLeft',
            'fadeInRight',
            'fadeInUp',
            'fadeInDown',
            'fadeInLeftBig',
            'fadeInRightBig',
            'fadeInUpBig',
            'fadeInDownBig',
            'flipInX',
            'flipInY',
            'lightSpeedIn',
            'rotateIn',
            'rotateInDownLeft',
            'rotateInDownRight',
            'rotateInUpLeft',
            'rotateInUpRight',
            'slideInUp',
            'slideInDown',
            'slideInLeft',
            'slideInRight',
            'zoomIn',
            'zoomInUp',
            'zoomInDown',
            'zoomInLeft',
            'zoomInRight',
            'rollIn'
        ) ) ) {
            $animate_class .= ' lsow-visible-on-scroll';
        }
        $animation_attr = ' data-animation="' . esc_attr( $animation ) . '"';
    }
    
    $return = array( $animate_class, $animation_attr );
    return apply_filters( 'lsow_animation_attributes', $return, $animation );
}

function lsow_get_animation_options()
{
    return apply_filters( 'lsow_animation_options', array(
        'none'        => __( 'None', 'livemesh-so-widgets' ),
        'fadeIn'      => __( 'Fade In', 'livemesh-so-widgets' ),
        'fadeInLeft'  => __( 'Fade In Left', 'livemesh-so-widgets' ),
        'fadeInRight' => __( 'Fade In Right', 'livemesh-so-widgets' ),
    ) );
}

function lsow_get_legacy_template_part( $template_name, $settings )
{
    // Allow the user to place the templates in a different folder
    $templates_folder = apply_filters( 'lsow_templates_folder', 'siteorigin-widgets' );
    $template = locate_template( $templates_folder . '/' . $template_name . '.php' );
    /* If template is found */
    
    if ( '' !== $template ) {
        ob_start();
        include $template;
        return ob_get_clean();
    }
    
    return null;
}

function lsow_get_module_template_part( $template_name, $module )
{
    // Allow the user to place the templates in a different folder
    $templates_folder = apply_filters( 'lsow_templates_folder', 'siteorigin-widgets/modules' );
    $template = locate_template( $templates_folder . '/' . $template_name . '.php' );
    /* If template is found */
    
    if ( '' !== $template ) {
        ob_start();
        include $template;
        return ob_get_clean();
    }
    
    return null;
}

function lsow_get_template_part( $template_name, $args = null, $return = false )
{
    $template_file = $template_name . '.php';
    $default_folder = LSOW_PLUGIN_DIR . 'templates/';
    // Allow the user to place the templates in a different folder than the default livemesh-siteorigin-widgets/ folder
    $theme_folder = apply_filters( 'lsow_addons_templates_folder', LSOW_PLUGIN_SLUG );
    /* Look for the file in the theme */
    $template = locate_template( $theme_folder . '/' . $template_file );
    if ( !$template ) {
        $template = $default_folder . $template_file;
    }
    if ( $args && is_array( $args ) ) {
        extract( $args );
    }
    if ( $return ) {
        ob_start();
    }
    if ( file_exists( $template ) ) {
        include $template;
    }
    if ( $return ) {
        return ob_get_clean();
    }
    return null;
}

function lsow_disable_lazy_load_classes()
{
    // no-lazyload - wp-smushit
    // data-no-lazy="1" - wprocket, rocket-lazy-load
    // skip-lazy - jetpack, SG Optimizer using filter in functions.php
    // exclude-me - autoptimize
    // a3-notlazy - a3-lazy-load
    return apply_filters( 'lsow_disable_lazy_load_classes', 'skip-lazy no-lazyload exclude-me a3-notlazy' );
}
