<?php 
 /**
  * 
  * @package    DarklupLite - WP Dark Mode
  * @version    1.0.0
  * @author     
  * @Websites: 
  *
  */
if( ! defined( 'ABSPATH' ) ) {
    die( DARKLUPLITE_ALERT_MSG );
}

// VC Admin init hook
add_action( 'vc_build_admin_page', 'darkluplite_custom_param_type' );
function darkluplite_custom_param_type(){
	require_once DARKLUPLITE_DIR_PAGE_BUILDER . 'wpbakery/image-switch-param.php';
}

// VC Admin init hook
require_once DARKLUPLITE_DIR_PAGE_BUILDER . 'wpbakery/vc-darkmode-switch.php';
require_once DARKLUPLITE_DIR_PAGE_BUILDER . 'wpbakery/vc-darkmode-switch-markup.php';

// DarklupLite dark mode vc shortcode
add_shortcode( 'vc_darkluplite_darkmode_switch', 'darkluplite_darkmode_component' );