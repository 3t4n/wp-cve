<?php 
spl_autoload_register( function ($class_name ) {
    if ( false === strpos( $class_name, 'Adminz' ) ) {
        return;
    }
    $file_parts = explode( '\\', $class_name );
    $namespace = '';
    $file_name = "";
    for ( $i = count( $file_parts ) - 1; $i > 0; $i-- ) {

        $current = strtolower( $file_parts[ $i ] );

        $current = str_ireplace( '_', '-', $current );

        if ( count( $file_parts ) - 1 === $i ) {

            if ( strpos( strtolower( $file_parts[ count( $file_parts ) - 1 ] ), 'interface' ) ) {

                $interface_name = explode( '_', $file_parts[ count( $file_parts ) - 1 ] );
                $interface_name = $interface_name[0];

                $file_name = "interface-$interface_name.php";

            } else {
                $file_name = "class-$current.php";
            }
        } else {
            $namespace = '/' . $current . $namespace;
        }
    }
    $filepath  = trailingslashit( dirname( dirname( __FILE__ ) ) . $namespace );
    $filepath .= $file_name;
    // Open comment to check file name
    if ( file_exists( $filepath ) and is_file($filepath)) {
        include_once( $filepath );
    } /*else {
        wp_die(
            esc_html( "The file attempting to be loaded at $filepath does not exist." )
        );
    }*/
} );

