<?php
declare( strict_types=1 );
namespace WebFacing\cPanel\Email;

/**
 * Exit if accessed directly
 */
\defined( 'ABSPATH' ) || exit;

function var_export( $var, bool $return = true ) {

	if ( $return ) {
		return \var_export( $var, true );
	} elseif ( Main::$is_debug ) {
		echo '<pre>';
		\var_export( $var, false );
		echo '</pre>';
	}
}
function error_log( /*null|int|float|string|array|object*/ $message, int $message_type = 0 , ?string $destination = null, ?string $extra_headers = null ): bool {

	if ( Main::$is_debug ) {

		if ( \is_wp_error( $message ) ) {
			$message = $message->get_error_messages();
		}
		if ( \is_null( $message ) ) {
			$message = 'null';
		} elseif ( \is_bool( $message ) ) {
			$message = $message ? 'true' : 'false';
		} elseif ( \is_iterable( $message ) ) {

			if ( \is_array( $message ) && \array_is_list( $message ) ) {
				$message = \implode( ', ', $message );
			} else {
				$messages = [];

				foreach ( (array) $message as $key => $mess ) {
					$messages[] = $key . ': ' . \print_r( $mess, true );
				}
				$message = \implode( ', ', $messages );
			}
		}

		if ( ! \is_string( $message ) ) {
			$message = var_export( $message, true );
		}
		return \error_log( '[' . \dirname( \plugin_basename( PLUGIN_FILE ) ) . '] ' . $message, $message_type /*, $destination, $extra_headers*/ );
	} else {
		return false;
	}
}

function __( string $text ): string {
	return \__( $text, Main::$plugin->TextDomain );
}

function _x( string $text, string $context ): string {
	return \_x( $text, $context, Main::$plugin->TextDomain );
}

function _n( string $singular, string $plural, int $number ): string {
	return \_n( $singular, $plural, $number, Main::$plugin->TextDomain );
}

function _nx( string $singular, string $plural, int $number, string $context ): string {
	return \_nx( $singular, $plural, $number, $context, Main::$plugin->TextDomain );
}

function _n_noop( string $singular, string $plural ): array {
	return \_n_noop( $singular, $plural, Main::$plugin->TextDomain );
}

function _nx_noop( string $singular, string $plural, string $context ): array {
	return \_n_noop( $singular, $plural, $context, Main::$plugin->TextDomain );
}

function translate_nooped_plural( array $nooped_plural, int $count ): string {
	\translate_nooped_plural( $nooped_plural, $count, Main::$plugin->TextDomain );
}

function _e( string $text ): void {
	\_e( $text, Main::$plugin->TextDomain );
}

function _ex( string $text, string $context ): void {
	\_ex( $text, $context, Main::$plugin->TextDomain );
}

function esc_html__( string $text ): string {
	return \esc_html__( $text, Main::$plugin->TextDomain );
}

function esc_html_x( string $text, string $context ) {
	return \esc_html_x( $text, $context, Main::$plugin->TextDomain );
}

function get_option( string $key, $default = false ) {
	return \get_network_option( null, $key, $default );
}

function update_option( string $key, $value ): bool {
	return \update_network_option( null, $key, $value );
}

function delete_option( string $key ): bool {
	return \delete_network_option( null, $key );
}
