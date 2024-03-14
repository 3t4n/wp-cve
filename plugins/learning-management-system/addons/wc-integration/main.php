<?php
/**
 * Addon Name: WooCommerce Integration
 * Addon URI: https://masteriyo.com/wordpress-lms/
 * Addon Type: integration
 * Description: WooCommerce Integration allows to enroll users using WooCommerce checkout process and payment methods.
 * Author: Masteriyo
 * Author URI: https://masteriyo.com
 * Version: 1.8.1
 * Requires: WooCommerce
 * Plan: Free
 */

use Masteriyo\Pro\Addons;
use Masteriyo\Addons\WcIntegration\Helper;
use Masteriyo\Addons\WcIntegration\WcIntegrationAddon;

define( 'MASTERIYO_WC_INTEGRATION_ADDON_FILE', __FILE__ );
define( 'MASTERIYO_WC_INTEGRATION_ADDON_BASENAME', plugin_basename( __FILE__ ) );
define( 'MASTERIYO_WC_INTEGRATION_ADDON_DIR', dirname( __FILE__ ) );
define( 'MASTERIYO_WC_INTEGRATION_ASSETS', dirname( __FILE__ ) . '/assets' );
define( 'MASTERIYO_WC_INTEGRATION_TEMPLATES', dirname( __FILE__ ) . '/templates' );
define( 'MASTERIYO_WC_INTEGRATION_ADDON_SLUG', 'wc-integration' );


if ( ( new Addons() )->is_active( MASTERIYO_WC_INTEGRATION_ADDON_SLUG ) && ! Helper::is_wc_active() ) {
	add_action(
		'admin_notices',
		function() {
			printf(
				'<div class="notice notice-warning is-dismissible"><p><strong>%s </strong>%s</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">%s</span></button></div>',
				esc_html( 'Masteriyo:' ),
				wp_kses_post( 'WooCommerce Integration addon requires WooCommerce to be installed and activated.', 'masteriyo' ),
				esc_html__( 'Dismiss this notice.', 'masteriyo' )
			);
		}
	);
}

// Bail early if WooCommerce is not activated.
if ( ! Helper::is_wc_active() ) {
	add_filter(
		'masteriyo_pro_addon_wc-integration_activation_requirements',
		function ( $result, $request, $controller ) {
			$result = __( 'WooCommerce is to be installed and activated for this addon to work properly', 'masteriyo' );
			return $result;
		},
		10,
		3
	);

	add_filter(
		'masteriyo_pro_addon_data',
		function( $data, $slug ) {
			if ( 'wc-integration' === $slug ) {
				$data['requirement_fulfilled'] = masteriyo_bool_to_string( Helper::is_wc_active() );
			}

			return $data;
		},
		10,
		2
	);
}


// Bail early if the addon is not active.
if ( ! ( ( new Addons() )->is_active( MASTERIYO_WC_INTEGRATION_ADDON_SLUG ) && Helper::is_wc_active() ) ) {
	return;
}

// Initialize wc integration addon.
WcIntegrationAddon::instance()->init();
