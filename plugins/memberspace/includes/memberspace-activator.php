<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 */

class MemberSpace_Activator {

	public static function activate() {

		// Flag banner for display on activation
		update_option( 'memberspace_display_banner', true );

		// Enable extra security by default
		update_option( 'memberspace_extra_security', true );

		// Load the site config when activating plugin
		$memberspace = new MemberSpace();
		$memberspace->refresh_site_config();
	}

}
