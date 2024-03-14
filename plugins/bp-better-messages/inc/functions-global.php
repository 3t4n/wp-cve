<?php
defined( 'ABSPATH' ) || exit;

function bm_bp_is_current_component($component = ''){
    if( ! function_exists('bp_is_current_component') ){
        return false;
    } else {
        return bp_is_current_component( $component );
    }
}

if ( ! function_exists( 'str_ends_with' ) ) {
    /**
     * Polyfill for `str_ends_with()` function added in PHP 8.0.
     *
     * Performs a case-sensitive check indicating if
     * the haystack ends with needle.
     *
     * @since 5.9.0
     *
     * @param string $haystack The string to search in.
     * @param string $needle   The substring to search for in the `$haystack`.
     * @return bool True if `$haystack` ends with `$needle`, otherwise false.
     */
    function str_ends_with( $haystack, $needle ) {
        if ( '' === $haystack && '' !== $needle ) {
            return false;
        }

        $len = strlen( $needle );

        return 0 === substr_compare( $haystack, $needle, -$len, $len );
    }
}
