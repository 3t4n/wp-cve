<?php
/**
 * Import Log ServerSendEvents
 *
 * @since 1.0.0
 *
 * @package Demo Importer Plus
 */

if ( ! class_exists( 'WP_Importer_Logger_ServerSentEvents' ) && class_exists( 'WP_Importer_Logger' ) ) {

	/**
	 * Import Log ServerSendEvents
	 */
	class WP_Importer_Logger_ServerSentEvents extends WP_Importer_Logger {

		/**
		 * Logs with an arbitrary level.
		 *
		 * @param mixed  $level Log level.
		 * @param string $message Log message.
		 * @param array  $context Log context.
		 * @return void
		 */
		public function log( $level, $message, array $context = array() ) {

			$data = compact( 'level', 'message' );

			switch ( $level ) {
				case 'emergency':
				case 'alert':
				case 'critical':
				case 'error':
				case 'warning':
				case 'notice':
				case 'info':
					if ( defined( 'WP_CLI' ) ) {
						if ( isset( $data['message'] ) && ! empty( $data['message'] ) ) {
							WP_CLI::line( $data['message'] );
						} else {
							WP_CLI::line( wp_json_encode( $data ) );
						}
					} else {
						echo "event: log\n";
						// TODO:CHECK
						echo 'data: ' . esc_html( wp_json_encode( $data ) ) . "\n\n";
					}
					flush();
					break;

				case 'debug':
					if ( defined( 'IMPORT_DEBUG' ) && IMPORT_DEBUG ) {
						if ( defined( 'WP_CLI' ) ) {
							if ( isset( $data['message'] ) && ! empty( $data['message'] ) ) {
								WP_CLI::line( $data['message'] );
							} else {
								WP_CLI::line( wp_json_encode( $data ) );
							}
						} else {
							echo "event: log\n";
							// TODO: Check
							echo 'data: ' . esc_html( wp_json_encode( $data ) ) . "\n\n";
						}
						flush();
						break;
					}
					break;
			}
		}
	}
}
