<?php

use XCurrency\WpMVC\Enqueue\Enqueue;

$version = x_currency_version();

/**
 * Load dashboard related assets that helps to use icons and admin menu 
 */ 
Enqueue::script( 'x-currency-admin-menu', 'js/admin-script', [] );
wp_enqueue_style( 'x-currency-icon', x_currency_url( 'media/font/style.css' ), [], $version );

if ( 'toplevel_page_x-currency' !== get_current_screen()->id ) {
    return;
}

wp_enqueue_media();
/**
 * Load dashboard related assets that helps to create dashboard 
 */ 
Enqueue::script( 'x-currency-store', 'js/store', ['lodash', 'backbone', 'jquery'], true );

do_action( 'x_currency_admin_script_before' );

Enqueue::script( 'x-currency-switcher', 'js/switcher.js', [], true );
Enqueue::script( 'x-currency-admin', 'js/dashboard', ['x-currency-store', 'x-currency-switcher'], true );
Enqueue::style( 'x-currency-admin', 'css/dashboard', ['wp-components'] );

wp_set_script_translations( 'x-currency-admin', 'x-currency' );
wp_add_inline_style(
    'x-currency-admin', "
:root{
--x-currency-bg-image: url(" . x_currency_url( 'media/common/dot-bg.png' ) . ");
}" 
);

wp_localize_script(
    'x-currency-store', 'x_currency', [
        'base_currency' => x_currency_base_id()
    ] 
);

wp_localize_script(
    'x-currency-admin', 'x_currency', [
        'version'       => $version,
        'apiUrl'        => get_rest_url( '', '' ),
        'media_url'     => x_currency_url( 'media/' ),
        'prefix'        => x_currency_config()->get( 'app.post_type' ),
        'api_version'   => 'v1',
        'nonce'         => wp_create_nonce( 'wp_rest' ),
        'base_currency' => x_currency_base_id(),
        'preview'       => x_currency_url( 'resources/views/customizer/preview.html' ),
    ] 
);