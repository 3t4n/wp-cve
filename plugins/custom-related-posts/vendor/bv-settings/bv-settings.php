<?php
if ( ! class_exists( 'BV_Settings' ) ) {
    define( 'BVS_VERSION', '1.0.1' );
    define( 'BVS_DIR', trailingslashit( dirname( __FILE__ ) ) );
    define( 'BVS_URL', plugin_dir_url( __FILE__ ) );

    require( 'includes/class-bv-settings.php' );
}