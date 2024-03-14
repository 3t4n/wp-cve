<?php
/**
 * Addon Name: SCORM Integration
 * Addon URI: https://masteriyo.com/wordpress-lms/
 * Description: Seamlessly integrates SCORM, optimizing e-learning by enabling efficient content management and tracking for enhanced user experiences.
 * Author: Masteriyo
 * Author URI: https://masteriyo.com
 * Addon Type: feature
 * Plan: Free
 */

use Masteriyo\Pro\Addons;

define( 'MASTERIYO_SCORM_FILE', __FILE__ );
define( 'MASTERIYO_SCORM_BASENAME', plugin_basename( __FILE__ ) );
define( 'MASTERIYO_SCORM_DIR', dirname( __FILE__ ) );
define( 'MASTERIYO_SCORM_SLUG', 'scorm' );
define( 'MASTERIYO_SCORM_URL', plugin_dir_url( MASTERIYO_SCORM_FILE ) );
define( 'MASTERIYO_SCORM_ADDON_TEMPLATES', dirname( __FILE__ ) . '/templates' );


// Bail early if the addon is not active.
if ( ! ( new Addons() )->is_active( MASTERIYO_SCORM_SLUG ) ) {
	return;
}

require_once __DIR__ . '/helper/scorm.php';

add_filter(
	'masteriyo_service_providers',
	function( $providers ) {
		return array_merge( $providers, require_once dirname( __FILE__ ) . '/config/providers.php' );
	}
);

add_action(
	'masteriyo_before_init',
	function() {
		masteriyo( 'addons.scorm' )->init();
	}
);
