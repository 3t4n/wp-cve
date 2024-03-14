<?php

function my_svi_pricing_js_path( $default_pricing_js_path )
{
    return dirname( __FILE__ ) . '/library/freemius-pricing/freemius-pricing.js';
}

//svi_fs()->add_filter('freemius_pricing_js_path', 'my_svi_pricing_js_path');
if ( !function_exists( 'svi_fs_custom_icon' ) ) {
    function svi_fs_custom_icon()
    {
        return dirname( dirname( __FILE__ ) ) . '/admin/images/svi.png';
    }

}
svi_fs()->add_filter( 'plugin_icon', 'svi_fs_custom_icon' );
if ( !function_exists( 'svi_fs_suport' ) ) {
    function svi_fs_suport()
    {
        return 'https://www.smart-variations.com';
    }

}
if ( !function_exists( 'svi_fs_is_submenu_visible' ) ) {
    function svi_fs_is_submenu_visible( $is_visible, $submenu_id )
    {
        switch ( $submenu_id ) {
            case 'support':
                
                if ( svi_fs()->is_plan( 'pro', true ) ) {
                    $return = true;
                } else {
                    $return = false;
                }
                
                break;
            case 'pricing':
                
                if ( svi_fs()->is_plan( 'pro', true ) ) {
                    $return = false;
                } else {
                    $return = $is_visible;
                }
                
                break;
            case 'contact':
                $return = false;
                break;
            default:
                $return = $is_visible;
        }
        if ( !current_user_can( 'edit_products' ) ) {
            $return = false;
        }
        return $return;
    }

}
svi_fs()->add_filter(
    'is_submenu_visible',
    'svi_fs_is_submenu_visible',
    10,
    2
);
function svi_default_currency( $currency )
{
    return 'auto';
}

svi_fs()->add_filter( 'default_currency', 'svi_default_currency' );