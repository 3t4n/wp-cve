<?php

function directorist_wpml_integration_get_template( string $path = '', array $data = [], bool $extract = true ) {

    $file = DIRECTORIST_WPML_INTEGRATION_TEMPLATE_PATH . $path . '.php';

    if ( ! file_exists( $file ) ) {
        return;
    }

    if ( $extract ) {
        extract( $data );
    }
    
    include $file;
}

function directorist_wpml_integration_get_view( string $path = '', array $data = [], bool $extract = true ) {

    $file = DIRECTORIST_WPML_INTEGRATION_VIEW_PATH . $path . '.php';

    if ( ! file_exists( $file ) ) {
        return;
    }

    if ( $extract ) {
        extract( $data );
    }
    
    include $file;
}