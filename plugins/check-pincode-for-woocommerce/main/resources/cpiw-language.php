<?php

/*translation word*/
add_action( 'plugins_loaded', 'CPIW_load_textdomain' );
function CPIW_load_textdomain() {
    load_plugin_textdomain( 'check-pincode-in-woocommerce', false, dirname( plugin_basename( CPIW_PLUGIN_FILE ) ) . '/languages' ); 
}
function CPIW_load_my_own_textdomain( $mofile, $domain ) {
    if ( 'check-pincode-in-woocommerce' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
        $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
        $mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( CPIW_PLUGIN_FILE ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
    }
    return $mofile;
}
add_filter( 'load_textdomain_mofile', 'CPIW_load_my_own_textdomain', 10, 2 );