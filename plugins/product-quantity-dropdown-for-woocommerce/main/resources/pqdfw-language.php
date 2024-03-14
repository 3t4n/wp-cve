<?php

// Use load textdomain
add_action( 'plugins_loaded', 'PQDFW_load_textdomain' );
function PQDFW_load_textdomain() {
    load_plugin_textdomain( 'product-quantity-dropdown-for-woocommerce', false, dirname( plugin_basename( PQDFW_PLUGIN_FILE ) ) . '/languages' ); 
}

// Use load textdomain mofile
function PQDFW_load_my_own_textdomain( $mofile, $domain ) {
    if ( 'product-quantity-dropdown-for-woocommerce' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
        $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
        $mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( PQDFW_PLUGIN_FILE ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
    }
    return $mofile;
}
add_filter( 'load_textdomain_mofile', 'PQDFW_load_my_own_textdomain', 10, 2 );