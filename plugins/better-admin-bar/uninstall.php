<?php
/**
 * Script to run on Better Admin Bar's un-installation.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$misc_settings       = get_option( 'swift_control_misc_settings', array() );
$delete_on_uninstall = isset( $misc_settings['delete_on_uninstall'] ) ? absint( $misc_settings['delete_on_uninstall'] ) : 0;

if ( ! $delete_on_uninstall ) {
	return;
}

// Delete the core options.
delete_option( 'swift_control_active_widgets' );
delete_option( 'swift_control_widget_settings' );
delete_option( 'swift_control_display_settings' );
delete_option( 'swift_control_color_settings' );
delete_option( 'swift_control_admin_bar_settings' );
delete_option( 'swift_control_misc_settings' );

// Delete the notice(s) option(s).
delete_option( 'swift_control_discontinue_message' );

// Delete the backwards compatibility option(s).
delete_option( 'swift_control_compat_migrate_options' );
