<?php
if ( !function_exists( 'mdwc_is_debug_enabled' ) ) {
    function mdwc_is_debug_enabled() {
        return 'yes' === get_option( 'mdwc_debug_enabled', 'yes' );
    }
}