<?php

// Use load textdomain
add_action( 'plugins_loaded', 'wplfcf7_load_textdomain' );
function wplfcf7_load_textdomain() {
    load_plugin_textdomain( 'woocommerce-product-list-field-for-contact-form-7', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

// Use load textdomain mofile
function wplfcf7_load_my_own_textdomain( $mofile, $domain ) {
    if ( 'woocommerce-product-list-field-for-contact-form-7' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
        $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
        $mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
    }
    return $mofile;
}
add_filter( 'load_textdomain_mofile', 'wplfcf7_load_my_own_textdomain', 10, 2 );