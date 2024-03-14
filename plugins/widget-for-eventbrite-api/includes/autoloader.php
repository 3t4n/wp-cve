<?php

/**
 * Autoloader based on tutorial by
 * Tom McFarlin https://tommcfarlin.com/  Licensed          GPL-2.0+
 */
if ( !defined( 'WPINC' ) ) {
    die;
}
if ( !spl_autoload_register( 'fullworks_WidgetForEventbriteAPI_autoload' ) ) {
    wp_die( esc_html__( 'System error - autoloader failed', 'widget-for-eventbrite-api' ) );
}
function fullworks_WidgetForEventbriteAPI_autoload( $class_name )
{
    global  $wfea_fs ;
    // If the specified $class_name does not include our namespace, duck out.
    if ( false === strpos( $class_name, 'WidgetForEventbriteAPI' ) ) {
        return;
    }
    // Split the class name into an array to read the namespace and class.
    $file_parts = explode( '\\', $class_name );
    // Do a reverse loop through $file_parts to build the path to the file.
    $namespace = '';
    for ( $i = count( $file_parts ) - 1 ;  $i > 0 ;  $i-- ) {
        // Read the current component of the file part.
        $current = strtolower( $file_parts[$i] );
        $current = str_ireplace( '_', '-', $current );
        // If we're at the first entry, then we're at the filename.
        
        if ( count( $file_parts ) - 1 === $i ) {
            /* If 'interface' is contained in the parts of the file name, then
             * define the $file_name differently so that it's properly loaded.
             * Otherwise, just set the $file_name equal to that of the class
             * filename structure.
             */
            
            if ( strpos( strtolower( $file_parts[count( $file_parts ) - 1] ), 'interface' ) ) {
                // Grab the name of the interface from its qualified name.
                $interface_name = explode( '_', $file_parts[count( $file_parts ) - 1] );
                $interface_name = $interface_name[0];
                $file_name = "interface-{$interface_name}.php";
                $file_name_no_suffix = "interface-{$interface_name}";
            } else {
                $file_name = "class-{$current}.php";
                $file_name_no_suffix = "class-{$current}";
            }
        
        } else {
            $namespace = '/' . $current . $namespace;
        }
    
    }
    // Now build a path to the file using mapping to the file location.
    $filepath = trailingslashit( dirname( dirname( __FILE__ ) ) . $namespace );
    $filepath .= $file_name_no_suffix;
    // If the file exists in the specified path, then include it.
    
    if ( file_exists( $filepath . '.php' ) ) {
        include_once $filepath . '.php';
        return;
    }
    
    if ( null !== $wfea_fs ) {
    }
    wp_die( esc_html( "The file attempting to be loaded at {$filepath} does not exist." ) );
}
