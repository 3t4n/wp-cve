<?php

namespace Bluehost\Maestro;

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

use \WP_CLI\Utils;
use NewfoldLabs\WP\Module\Maestro\Auth\WebPro;

/**
 * Implements the webPro association as a WP CLI command
 *
 * @since 1.2.0
 */
$associate_command = function ( $args, $assoc_args ) {
	list( $secret_key ) = $args;
	$web_pro            = new WebPro( $secret_key );

	$success = $web_pro->connect();

	if ( ! $success ) {
		\WP_CLI::log( \WP_CLI::colorize( '%rFailed to associate webPro %n' ) );
		\WP_CLI::halt( 400 );
	}

	\WP_CLI::log( \WP_CLI::colorize( '%gAssociated webPro %n' ) );
};

\WP_CLI::add_command( 'webPro associate', $associate_command );
