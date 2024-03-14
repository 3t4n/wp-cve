<?php
/**
* Don't display if wordpress admin class is not found
* Protects code if wordpress breaks
* @since 0.2
*/
if ( ! function_exists( 'is_admin' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

require('privMenu.php');

/**
 * Initialize privWidget class
 * @var Ambiguous $myprivWidgetClass
 * @since 1.7.1
 */
$myprivMenuClass = new privMenu();

$option_name = $myprivMenuClass->privMenuOption;

delete_post_meta_by_key( $option_name );


?>
