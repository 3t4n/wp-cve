<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
function ns_btta_options_form()
{
	require_once( 'ns_admin_option_dashboard.php');
}
/* *** add menu page and add sub menu page *** */
add_action( 'admin_menu', function()  {
    add_menu_page('Back To Top', 'Back To Top', 'manage_options', 'ns-btta-options-page', 'ns_btta_options_form', plugin_dir_url( __FILE__ ).'img/backend-sidebar-icon.png', 60);
	add_submenu_page('ns-btta-options-page', 'How to install premium version', 'How to install premium version', 'manage_options', 'how-to-install-premium-version', function(){  wp_redirect('http://www.nsthemes.com/how-to-install-the-premium-version/'); exit; });
});

/* *** add style *** */
add_action( 'admin_enqueue_scripts', function() {
	wp_enqueue_style('ns-option-css-placeholder-img-page', plugin_dir_url( __FILE__ ) . 'css/ns-option-css-page.css');
	wp_enqueue_style('ns-option-css-cus-placeholder-img', plugin_dir_url( __FILE__ ) . 'css/ns-option-css-custom-page.css');
	wp_enqueue_script( 'ns-option-js-placeholder-img-page', plugins_url( '/js/ns-option-js-page.js' , __FILE__ ), array( 'jquery' ) );
});
?>