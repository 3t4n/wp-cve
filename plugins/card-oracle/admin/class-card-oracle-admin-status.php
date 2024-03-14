<?php
/**
 * Debug/Status page
 *
 * @package Card_Oracle/Admin/Status
 * @version 1.1.3
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CardOracleAdminStatus Class.
 */
class CardOracleAdminStatus {

	/**
	 * Handles output of the reports page in admin.
	 */
	public static function output() {
		include_once dirname( __FILE__ ) . '/views/card-oracle-admin-page-status.php';
	}

	/**
	 * Handles output of report.
	 */
	public static function status_report() {
		include_once dirname( __FILE__ ) . '/partials/card-oracle-tab-status.php';
	}

	/**
	 * Prints the information about plugins for the system status report.
	 * Used for both active and inactive plugins sections.
	 *
	 * @param array $plugins List of plugins to display.
	 * @return void
	 */
	public static function output_plugins_info( $plugins ) {
		foreach ( $plugins as $plugin ) {
			if ( ! empty( $plugin['name'] ) ) {
				// Link the plugin name to the plugin url if available.
				$plugin_name = esc_html( $plugin['name'] );
				if ( ! empty( $plugin['url'] ) ) {
					$plugin_name = '<a href="' . esc_url( $plugin['url'] ) . '" aria-label="' . esc_attr__( 'Visit plugin homepage', 'card-oracle' ) . '" target="_blank">' . $plugin_name . '</a>';
				}

				$version_string = $plugin['version'];
				$network_string = '';
				?>
				<tr>
					<td><?php echo wp_kses_post( $plugin_name ); ?></td>
					<td class="card-oracle-help-blank">&nbsp;</td>
					<td>
						<?php
						/* translators: %s: plugin author */
						printf( esc_html__( 'by %s', 'card-oracle' ), esc_html( $plugin['author_name'] ) );
						echo ' &ndash; ' . esc_html( $version_string ) . $network_string; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</td>
				</tr>
				<?php
			}
		}
	}
}
