<?php
/**
 * Addon Name: Migration Tool
 * Addon URI: https://masteriyo.com/wordpress-lms/
 * Description: Effortlessly migrate data from various LMS platforms to Masteriyo with the Masteriyo Migration Tool.
 * Author: Masteriyo
 * Author URI: https://masteriyo.com
 * Addon Type: feature
 * Plan: Free
 */

use Masteriyo\Pro\Addons;

define( 'MASTERIYO_MIGRATION_TOOL_FILE', __FILE__ );
define( 'MASTERIYO_MIGRATION_TOOL_BASENAME', plugin_basename( __FILE__ ) );
define( 'MASTERIYO_MIGRATION_TOOL_DIR', dirname( __FILE__ ) );
define( 'MASTERIYO_MIGRATION_TOOL_SLUG', 'migration-tool' );


// Bail early if the addon is not active.
if ( ! ( new Addons() )->is_active( MASTERIYO_MIGRATION_TOOL_SLUG ) ) {
	return;
}

add_filter(
	'masteriyo_service_providers',
	function( $providers ) {
		return array_merge( $providers, require_once dirname( __FILE__ ) . '/config/providers.php' );
	}
);

add_action(
	'masteriyo_before_init',
	function() {
		masteriyo( 'addons.migration-tool' )->init();
	}
);
