<?php
/* Admin Scripts and Styles */
/* Make sure Theme has jQuery! */
function register_jquery_in_cpln(){
    wp_enqueue_script('jquery');
}
add_action( 'wp_enqueue_scripts', 'register_jquery_in_cpln' );

/* Front End Scripts and Styles */
function cpln_scripts_and_styles(){
	wp_enqueue_script('casper_leaving_script', plugin_dir_url( __FILE__ ).'../caspers-leave-notice.js', '', '', true);
	wp_enqueue_style( 'casper_leaving_style', plugin_dir_url( __FILE__ ).'../caspers-leave-notice.css' );
}
add_action( 'wp_enqueue_scripts', 'cpln_scripts_and_styles' );

/* Add Settings Link to Plugin Page */
add_filter('plugin_action_links', 'cpln_settings_link', 10, 2);
function cpln_settings_link($links, $file) {
    if ( $file == 'caspers-leave-notice/caspers-leave-notice.php' ) {
		$links['donate'] = sprintf( '<a href="%s" target="_blank"> %s </a>', 'https://www.paypal.me/xace90', __( 'Donate', 'cpln' ) );
        $links['settings'] = sprintf( '<a href="%s"> %s </a>', admin_url( 'options-general.php?page=cpleavenotice' ), __( 'Settings', 'cpln' ) );
    }
    return $links;
}