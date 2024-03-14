<?php
/**
 * Performs plugin uninstall operations.
 *
 * @since 1.0.0
 * @package EasyCloudflareTurnstile
 */

namespace EasyCloudflareTurnstile;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

	/**
	 * The Array of Delete Options
	 *
	 * @var array
	 */
	$options;

$options = [
	'wp_turnstile_settings',
	'ect_validated',
	'ect_store',
	'ect_disabled_ids',
	'ect_placement',
	'wppool-turnstile-captcha-spam-filter_allow_tracking',
	'wppool-turnstile-captcha-spam-filter_tracking_last_send',
	'ect_redirect_to_admin_page',
];

foreach ($options as $option) {
	delete_option( $option );
}