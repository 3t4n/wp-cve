<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Print the button
function ns_back_to_top_arrow(){

echo '<a id="ns-back-to-top-arrow" href="#" class="btta-btn btta-btn-base ns-back-to-top"><span>'.get_option('ns_btta_font_awsome', '<i class="fa fa-arrow-up"></i>').'</span></a>';
wp_nonce_field( 'ns-btta-ajax-nonce-click', 'ns_btta_security' );
}
add_action( 'wp_footer', 'ns_back_to_top_arrow' );
?>