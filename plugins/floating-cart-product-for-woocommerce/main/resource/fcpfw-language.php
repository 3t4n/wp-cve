<?php

// load plugin textdomain
add_action( 'plugins_loaded', 'FCPFW_load_textdomain' );
function FCPFW_load_textdomain() {
    load_plugin_textdomain( 'floating-cart-product-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

// load plugin textdomain mofile
function FCPFW_load_my_own_textdomain( $mofile, $domain ) {
    if ( 'free-gifts-for-woocommerce' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
        $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
        $mofile = WP_PLUGIN_DIR . '/' . plugin_basename( __FILE__ ) . '/languages/' . $domain . '-' . $locale . '.mo';
    }
    return $mofile;
}
add_filter( 'load_textdomain_mofile', 'FCPFW_load_my_own_textdomain', 10, 2 );