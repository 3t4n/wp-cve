<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();
/**
 * uninstall.php - IntelliWidget Uninstall
 *
 * @package IntelliWidget
 * @author Jason C Fleming
 * @copyright 2014-2015 Lilaea Media LLC
 * @access public
 */
function intelliwidget_delete_plugin() {
    // get database access object
    global $wpdb;
    // remove widgets
    delete_option( 'widget_intelliwidget' );
    // remove postmeta entries
    $wpdb->query( "DELETE FROM " . $wpdb->prefix . "postmeta WHERE meta_key LIKE '%intelliwidget%'" );
}
// call function
//intelliwidget_delete_plugin();

?>
