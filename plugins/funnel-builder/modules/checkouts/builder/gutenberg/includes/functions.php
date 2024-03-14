<?php


if ( ! function_exists( 'bwfblocks_attr' ) ) {
    /**
     * Build list of attributes into a string and apply contextual filter on string.
     *
     * The contextual filter is of the form `bwfblocks_attr_{context}_output`.
     *
     * @since 1.2.0
     *
     * @param string $context    The context, to build filter name.
     * @param array  $attributes Optional. Extra attributes to merge with defaults.
     * @param array  $settings   Optional. Custom data to pass to filter.
     * @return string String of HTML attributes and values.
     */
    
    function bwfblocks_attr( $context, $attributes = array(), $settings = array() ) {
        $output = '';
    
        // Cycle through attributes, build tag attribute string.
        foreach ( $attributes as $key => $value ) {
    
            if ( ! $value ) {
                continue;
            }
    
            if ( true === $value ) {
                $output .= esc_html( $key ) . ' ';
            } else {
                $output .= sprintf( '%s="%s" ', esc_html( $key ), esc_attr( $value ) );
            }
        }
    
        $output = apply_filters( "bwfblocks_attr_{$context}_output", $output, $attributes, $settings, $context );
    
        return trim( $output );
    }
}