<?php
function lpp_register_custom_menu_page() {
    add_menu_page(
        esc_html__( 'Logo or Image Replace', 'textdomain' ),
        esc_html__( 'Logo or Image Replace', 'textdomain' ),
        'manage_options',
        'qc_lpp',
        'lpp_menu_item_callback',
        'dashicons-format-gallery',
        6
    );

    add_submenu_page(
        'qc_lpp',
        __( 'Settings', 'textdomain' ),
        __( 'Settings', 'textdomain' ),
        'manage_options',
        'qc-lpp-settings',
        'qc_lp_settings_callback'
    );
    add_submenu_page(
        'qc_lpp',
        __( 'Help', 'textdomain' ),
        __( 'Help', 'textdomain' ),
        'manage_options',
        'qc-lpp-help',
        'qc_lp_help_callback'
    );

}
add_action( 'admin_menu', 'lpp_register_custom_menu_page' );

function lpp_menu_item_callback(){
	// ();
}
function qc_lp_help_callback(){
    require_once( qcld_lpp_path .'/includes/help-template.php' );
}
function qc_lp_settings_callback(){
    require_once( qcld_lpp_path .'/includes/setings-page-template.php' );
}

add_filter( 'custom_menu_order', 'qc_lpp_custom_menu_order' );
function qc_lpp_custom_menu_order($menu_ord){
    global $submenu;
  //  echo '<pre>'; print_r( $submenu['qc_lpp'] ); echo '</pre>'; exit();
    $arr = array();
    $arr[] = $submenu['qc_lpp'][1];
    $arr[] = $submenu['qc_lpp'][2];
    $arr[] = $submenu['qc_lpp'][300];
    $submenu['qc_lpp'] = $arr;
    return $menu_ord;
}