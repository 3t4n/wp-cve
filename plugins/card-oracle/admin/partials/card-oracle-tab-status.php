<?php
/**
 * Admin View: Page - Status Report.
 *
 * @package CardOracle
 * @since 1.1.3
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( 'status' === $active_tab ) {
	require_once CARD_ORACLE_DIR . 'admin/class-card-oracle-admin-status.php';

	global $co_logs, $wpdb;

	if ( isset( $_POST['co_clear_logs_button'] ) && check_admin_referer( 'co_clear_logs_button' ) ) {
		// Clear Logs button pressed, delete the error log files.
		$co_logs->delete_logs( 0, 'error' );
	}

	$environment         = card_oracle_get_environment_info();
	$active_plugins      = $environment['active_plugins'];
	$active_theme        = wp_get_theme();
	$api_request_counts  = $environment['api_request_counts'];
	$card_oracle_options = isset( $environment['card_oracle_options'] ) ? $environment['card_oracle_options'] : array();
	$dropins_mu_plugins  = get_dropins_mu_plugins();
	$error_log_counts    = $environment['error_log_counts'];
	$error_log_rows      = isset( $_POST['error_rows'] ) ? sanitize_text_field( wp_unslash( $_POST['error_rows'] ) ) : '5';
	$inactive_plugins    = $environment['inactive_plugins'];
	$md5_files           = isset( $environment['md5_files'] ) ? $environment['md5_files'] : array();
	$post_type_counts    = isset( $environment['post_type_counts'] ) ? $environment['post_type_counts'] : array();
	$theme               = array(
		'name'           => $active_theme->name,
		'version'        => $active_theme->version,
		'author_url'     => esc_url_raw( $active_theme->{'Author URI'} ),
		'is_child_theme' => is_child_theme(),
	);
	?>

	<div class="updated card-oracle-message inline">
		<p>
			<?php esc_html_e( 'Please copy and paste this information in your ticket when contacting support:', 'card-oracle' ); ?>
		</p>
		<p class="submit">
			<a href="#" class="button-primary debug-report"><?php esc_html_e( 'Get system report', 'card-oracle' ); ?></a>
			<a class="button-secondary docs" href="https://www.chillichalli.com/card-oracle/tarot-card-oracle-status-report/" target="_blank">
				<?php esc_html_e( 'Understanding the status report', 'card-oracle' ); ?>
			</a>
		</p>
		<div id="debug-report">
			<textarea readonly="readonly"></textarea>
			<p class="submit">
				<button id="copy-for-support" class="button-primary" href="#" data-tip="<?php esc_attr_e( 'Copied!', 'card-oracle' ); ?>">
					<?php esc_html_e( 'Copy for support', 'card-oracle' ); ?>
				</button>
			</p>
			<div class="copy-success hidden">
				<p class="copy-success hidden">
					<?php esc_html_e( 'Copied!', 'card-oracle' ); ?>
				</p>
				<p class="copy-error hidden">
					<?php esc_html_e( 'Copying to clipboard failed. Please press Ctrl/Cmd+C to copy.', 'card-oracle' ); ?>
				</p>
			</div>
		</div>
	</div>
	<table class="card_oracle_status_table widefat" id="status">
		<thead>
			<tr>
				<th colspan="3" data-export-label="WordPress Environment"><h2><?php esc_html_e( 'WordPress environment', 'card-oracle' ); ?></h2></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td data-export-label="WordPress address (URL)"><?php esc_html_e( 'WordPress address (URL)', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><div class="card-oracle-help-tip"><p><?php esc_html_e( 'The root URL of your site.', 'card-oracle' ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></p></div></td>
				<td><?php echo esc_html( $environment['site_url'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="Site address (URL)"><?php esc_html_e( 'Site address (URL)', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The homepage URL of your site.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td><?php echo esc_html( $environment['home_url'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="Card Oracle Version"><?php esc_html_e( 'Card Oracle version', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The version of Card Oracle installed on your site.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td><?php echo esc_html( $environment['card_oracle_version'] ); ?></td>
			</tr>
			<?php if ( $environment['rss_feed'] ) : ?>
				<tr>
					<td data-export-label="Card Oracle RSS"><?php esc_html_e( 'Card Oracle RSS', 'card-oracle' ); ?>:</td>
					<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'Is Card Oracle RSS registered on your site?', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
					<td>
						<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
					</td>
				</tr>
			<?php endif; ?>
			<tr>
				<td data-export-label="Freemius Version"><?php esc_html_e( 'Freemius version', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The version of Freemius installed on your site.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td><?php echo esc_html( WP_FS__SDK_VERSION ); ?></td>
			</tr>
			<tr>
				<td data-export-label="Log Directory Writable"><?php esc_html_e( 'Log directory writable', 'card-oracle' ); ?>:</td>
				<td class="help"><?php echo card_oracle_help_tip( esc_html__( 'Card Oracle can write logs which makes debugging problems easier. The directory must be writable for this to happen.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td>
					<?php
					if ( $environment['log_directory_writable'] ) {
						echo '<mark class="yes"><span class="dashicons dashicons-yes"></span> <code class="private">' . esc_html( $environment['log_directory'] ) . '</code></mark> ';
					} else {
						/* Translators: %1$s: Log directory, %2$s: Log directory constant */
						echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'To allow logging, make %1$s writable or define a custom %2$s.', 'card-oracle' ), '<code>' . esc_html( $environment['log_directory'] ) . '</code>', '<code>CARD_ORACLE_LOG_DIR</code>' ) . '</mark>';
					}
					?>
				</td>
			</tr>
			<tr>
				<td data-export-label="WP Version"><?php esc_html_e( 'WordPress version', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The version of WordPress installed on your site.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td>
					<?php
					$latest_version = get_transient( 'card_oracle_system_status_wp_version_check' );

					if ( false === $latest_version ) {
						$version_check = wp_remote_get( 'https://api.wordpress.org/core/version-check/1.7/' );
						$api_response  = json_decode( wp_remote_retrieve_body( $version_check ), true );

						if ( $api_response && isset( $api_response['offers'], $api_response['offers'][0], $api_response['offers'][0]['version'] ) ) {
							$latest_version = $api_response['offers'][0]['version'];
						} else {
							$latest_version = $environment['wp_version'];
						}
						set_transient( 'card_oracle_system_status_wp_version_check', $latest_version, DAY_IN_SECONDS );
					}

					if ( version_compare( $environment['wp_version'], $latest_version, '<' ) ) {
						/* Translators: %1$s: Current version, %2$s: New version */
						echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%1$s - There is a newer version of WordPress available (%2$s)', 'card-oracle' ), esc_html( $environment['wp_version'] ), esc_html( $latest_version ) ) . '</mark>';
					} else {
						echo '<mark class="yes">' . esc_html( $environment['wp_version'] ) . '</mark>';
					}
					?>
				</td>
			</tr>
			<tr>
				<td data-export-label="WP Multisite"><?php esc_html_e( 'WordPress multisite', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'Whether or not you have WordPress Multisite enabled.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td><?php echo ( $environment['wp_multisite'] ) ? '<span class="dashicons dashicons-yes"></span>' : '&ndash;'; ?></td>
			</tr>
			<tr>
				<td data-export-label="WP Memory Limit"><?php esc_html_e( 'WordPress memory limit', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The maximum amount of memory (RAM) that your site can use at one time.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td>
					<?php
					if ( $environment['wp_memory_limit'] < 67108864 ) {
						/* Translators: %1$s: Memory limit, %2$s: Docs link. */
						echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%1$s - We recommend setting memory to at least 64MB. See: %2$s', 'card-oracle' ), esc_html( size_format( $environment['wp_memory_limit'] ) ), '<a href="https://wordpress.org/support/article/editing-wp-config-php/#increasing-memory-allocated-to-php" target="_blank">' . esc_html__( 'Increasing memory allocated to PHP', 'card-oracle' ) . '</a>' ) . '</mark>';
					} else {
						echo '<mark class="yes">' . esc_html( size_format( $environment['wp_memory_limit'] ) ) . '</mark>';
					}
					?>
				</td>
			</tr>
			<tr>
				<td data-export-label="WP Debug Mode"><?php esc_html_e( 'WordPress debug mode', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'Displays whether or not WordPress is in Debug Mode.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td>
					<?php if ( $environment['wp_debug_mode'] ) : ?>
						<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
					<?php else : ?>
						<mark class="no">&ndash;</mark>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td data-export-label="WP Cron"><?php esc_html_e( 'WordPress cron', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'Displays whether or not WP Cron Jobs are enabled.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td>
					<?php if ( $environment['wp_cron'] ) : ?>
						<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
					<?php else : ?>
						<mark class="no">&ndash;</mark>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td data-export-label="Database Prefix"><?php esc_html_e( 'Database prefix', 'card-oracle' ); ?></td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'Displays the table prefix used in your WordPress database.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td>
					<?php
					if ( strlen( $environment['database_prefix'] ) > 20 ) {
						/* Translators: %1$s: Database prefix. */
						echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%1$s - We recommend using a prefix with less than 20 characters.', 'card-oracle' ), esc_html( $environment['database_prefix'] ) ) . '</mark>';
					} else {
						echo '<mark class="yes">' . esc_html( $environment['database_prefix'] ) . '</mark>';
					}
					?>
				</td>
			</tr>
			<tr>
				<td data-export-label="Language"><?php esc_html_e( 'Language', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The current language used by WordPress. Default = English', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td><?php echo esc_html( $environment['language'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="External object cache"><?php esc_html_e( 'External object cache', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'Displays whether or not WordPress is using an external object cache.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td>
					<?php if ( $environment['external_object_cache'] ) : ?>
						<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
					<?php else : ?>
						<mark class="no">&ndash;</mark>
					<?php endif; ?>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="card_oracle_status_table widefat">
		<thead>
			<tr>
				<th colspan="3" data-export-label="Server Environment"><h2><?php esc_html_e( 'Server environment', 'card-oracle' ); ?></h2></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td data-export-label="Server Info"><?php esc_html_e( 'Server info', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'Information about the web server that is currently hosting your site.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td><?php echo esc_html( $environment['server_info'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="PHP Version"><?php esc_html_e( 'PHP version', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The version of PHP installed on your hosting server.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td>
					<?php
					if ( version_compare( $environment['php_version'], '7.2', '>=' ) ) {
						echo '<mark class="yes">' . esc_html( $environment['php_version'] ) . '</mark>';
					} else {
						$update_link = ' <a href="https://docs.woocommerce.com/document/how-to-update-your-php-version/" target="_blank">' . esc_html__( 'How to update your PHP version', 'card-oracle' ) . '</a>';
						$class       = 'error';

						if ( version_compare( $environment['php_version'], '5.4', '<' ) ) {
							$notice = '<span class="dashicons dashicons-warning"></span> ' . __( 'Card Oracle will run under this version of PHP. Support for this version will be dropped in the next major release. We recommend using PHP version 7.4 or above for greater performance and security.', 'card-oracle' ) . $update_link;
						} elseif ( version_compare( $environment['php_version'], '5.6', '<' ) ) {
							$notice = '<span class="dashicons dashicons-warning"></span> ' . __( 'Card Oracle will run under this version of PHP, however, it has reached end of life. We recommend using PHP version 7.4 or above for greater performance and security.', 'card-oracle' ) . $update_link;
						} elseif ( version_compare( $environment['php_version'], '7.2', '<' ) ) {
							$notice = __( 'We recommend using PHP version 7.4 or above for greater performance and security.', 'card-oracle' ) . $update_link;
							$class  = 'recommendation';
						}

						echo '<mark class="' . esc_attr( $class ) . '">' . esc_html( $environment['php_version'] ) . ' - ' . wp_kses_post( $notice ) . '</mark>';
					}
					?>
				</td>
			</tr>
			<?php if ( function_exists( 'ini_get' ) ) : ?>
				<tr>
					<td data-export-label="PHP Post Max Size"><?php esc_html_e( 'PHP post max size', 'card-oracle' ); ?>:</td>
					<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The largest filesize that can be contained in one post.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
					<td><?php echo esc_html( size_format( $environment['php_post_max_size'] ) ); ?></td>
				</tr>
				<tr>
					<td data-export-label="PHP Time Limit"><?php esc_html_e( 'PHP time limit', 'card-oracle' ); ?>:</td>
					<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups)', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
					<td><?php echo esc_html( $environment['php_max_execution_time'] ); ?></td>
				</tr>
				<tr>
					<td data-export-label="PHP Max Input Vars"><?php esc_html_e( 'PHP max input vars', 'card-oracle' ); ?>:</td>
					<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
					<td><?php echo esc_html( $environment['php_max_input_vars'] ); ?></td>
				</tr>
				<tr>
					<td data-export-label="PHP Sendmail Path"><?php esc_html_e( 'PHP sendmail path', 'card-oracle' ); ?>:</td>
					<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The path to sendmail on your server.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
					<td><?php echo esc_html( $environment['php_sendmail_path'] ); ?></td>
				</tr>
				<tr>
					<td data-export-label="cURL Version"><?php esc_html_e( 'cURL version', 'card-oracle' ); ?>:</td>
					<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The version of cURL installed on your server.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
					<td><?php echo esc_html( $environment['curl_version'] ); ?></td>
				</tr>
				<tr>
					<td data-export-label="SUHOSIN Installed"><?php esc_html_e( 'SUHOSIN installed', 'card-oracle' ); ?>:</td>
					<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'Suhosin is an advanced protection system for PHP installations. If enabled on your server, Suhosin may need to be configured to increase its data submission limits.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
					<td><?php echo $environment['suhosin_installed'] ? '<span class="dashicons dashicons-yes"></span>' : '&ndash;'; ?></td>
				</tr>
			<?php endif; ?>
			<?php if ( $environment['mysql_version'] ) : ?>
				<tr>
					<td data-export-label="MySQL Version"><?php esc_html_e( 'MySQL version', 'card-oracle' ); ?>:</td>
					<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The version of MySQL installed on your hosting server.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
					<td>
						<?php
						if ( version_compare( $environment['mysql_version'], '5.6', '<' ) && ! strstr( $environment['mysql_version_string'], 'MariaDB' ) ) {
							/* Translators: %1$s: MySQL version, %2$s: Recommended MySQL version. */
							echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%1$s - We recommend a minimum MySQL version of 5.6. See: %2$s', 'card-oracle' ), esc_html( $environment['mysql_version_string'] ), '<a href="https://wordpress.org/about/requirements/" target="_blank">' . esc_html__( 'WordPress requirements', 'card-oracle' ) . '</a>' ) . '</mark>';
						} else {
							echo '<mark class="yes">' . esc_html( $environment['mysql_version_string'] ) . '</mark>';
						}
						?>
					</td>
				</tr>
			<?php endif; ?>
			<tr>
				<td data-export-label="Max Upload Size"><?php esc_html_e( 'Max upload size', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The largest filesize that can be uploaded to your WordPress installation.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td><?php echo esc_html( size_format( $environment['max_upload_size'] ) ); ?></td>
			</tr>
			<tr>
				<td data-export-label="DOMDocument"><?php esc_html_e( 'DOMDocument', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'HTML/Multipart emails use DOMDocument to generate inline CSS in templates.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td>
					<?php
					if ( $environment['domdocument_enabled'] ) {
						echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
					} else {
						/* Translators: %s: classname and link. */
						echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'Your server does not have the %s class enabled - HTML/Multipart emails, and also some extensions, will not work without DOMDocument.', 'card-oracle' ), '<a href="https://php.net/manual/en/class.domdocument.php">DOMDocument</a>' ) . '</mark>';
					}
					?>
				</td>
			</tr>
			<tr>
				<td data-export-label="Multibyte String"><?php esc_html_e( 'Multibyte string', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'Multibyte String (mbstring) is used to convert character encoding, like for emails or converting characters to lowercase.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td>
					<?php
					if ( $environment['mbstring_enabled'] ) {
						echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
					} else {
						/* Translators: %s: classname and link. */
						echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'Your server does not support the %s functions - this is required for better character encoding. Some fallbacks will be used instead for it.', 'card-oracle' ), '<a href="https://php.net/manual/en/mbstring.installation.php">mbstring</a>' ) . '</mark>';
					}
					?>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="card_oracle_status_table widefat">
		<thead>
			<tr>
				<th colspan="3" data-export-label="Security"><h2><?php esc_html_e( 'Security', 'card-oracle' ); ?></h2></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td data-export-label="Secure connection (HTTPS)"><?php esc_html_e( 'Secure connection (HTTPS)', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'Is the connection secure?', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td>
					<?php if ( $environment['secure_connection'] ) : ?>
						<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
					<?php else : ?>
						<mark class="error"><span class="dashicons dashicons-warning"></span>
						<?php
						/* Translators: %s: docs link. */
						echo wp_kses_post( sprintf( __( 'Your site is not using HTTPS. <a href="%s" target="_blank">Learn more about HTTPS and SSL Certificates</a>.', 'card-oracle' ), 'https://blog.hubspot.com/marketing/what-is-ssl' ) );
						?>
						</mark>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td data-export-label="Hide errors from visitors"><?php esc_html_e( 'Hide errors from visitors', 'card-oracle' ); ?></td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'Error messages can contain sensitive information about your site. These should be hidden from untrusted visitors.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td>
					<?php if ( $environment['hide_errors'] ) : ?>
						<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
					<?php else : ?>
						<mark class="error"><span class="dashicons dashicons-warning"></span><?php esc_html_e( 'Error messages should not be shown to visitors.', 'card-oracle' ); ?></mark>
					<?php endif; ?>
				</td>
			</tr>
		</tbody>
	</table>
	<?php if ( $card_oracle_options ) : ?>
		<table class="card_oracle_status_table widefat">
			<thead>
				<tr>
					<th colspan="3" data-export-label="Card Oracle Options"><h2><?php esc_html_e( 'Card Oracle Options', 'card-oracle' ); ?></h2></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( $card_oracle_options as $name => $value ) { ?>
				<?php if ( '_key' === substr( $name, -4, 4 ) ) : ?>
					<tr>
						<td><?php echo esc_html( $name ); ?></td>
						<td class="card-oracle-help-blank">&nbsp;</td>
						<td><?php echo $value ? '*' . esc_html( substr( $value, -4 ) ) . ' <strong style="color:red;">' . esc_html__( '(Last 4 characters)', 'card-oracle' ) . '</strong>' : '&ndash;'; ?></td>
					</tr>
				<?php else : ?>
					<tr>
						<td><?php echo esc_html( $name ); ?></td>
						<td class="card-oracle-help-blank">&nbsp;</td>
						<td><?php echo $value ? esc_html( $value ) : '&ndash;'; ?></td>
					</tr>
				<?php endif; ?>

			<?php } ?>
			</tbody>
		</table>
	<?php endif; ?>
	<?php if ( $post_type_counts ) : ?>
		<table class="card_oracle_status_table widefat">
			<thead>
			<tr>
				<th colspan="3" data-export-label="Post Type Counts"><h2><?php esc_html_e( 'Post Type Counts', 'card-oracle' ); ?></h2></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ( $post_type_counts as $post_type_count ) { ?>
				<tr>
					<td><?php echo esc_html( $post_type_count->type ); ?></td>
					<td class="card-oracle-help-blank">&nbsp;</td>
					<td><?php echo absint( $post_type_count->count ); ?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	<?php endif; ?>
	<table class="card_oracle_status_table widefat">
		<thead>
			<tr>
				<th colspan="3" data-export-label="Active Plugins (<?php echo count( $active_plugins ); ?>)"><h2><?php esc_html_e( 'Active plugins', 'card-oracle' ); ?> (<?php echo count( $active_plugins ); ?>)</h2></th>
			</tr>
		</thead>
		<tbody>
			<?php CardOracleAdminStatus::output_plugins_info( $active_plugins ); ?>
		</tbody>
	</table>
	<table class="card_oracle_status_table widefat">
		<thead>
			<tr>
				<th colspan="3" data-export-label="Inactive Plugins (<?php echo count( $inactive_plugins ); ?>)"><h2><?php esc_html_e( 'Inactive plugins', 'card-oracle' ); ?> (<?php echo count( $inactive_plugins ); ?>)</h2></th>
			</tr>
		</thead>
		<tbody>
			<?php CardOracleAdminStatus::output_plugins_info( $inactive_plugins ); ?>
		</tbody>
	</table>
	<?php
	if ( 0 < count( $dropins_mu_plugins['dropins'] ) ) :
		?>
		<table class="card_oracle_status_table widefat">
			<thead>
				<tr>
					<th colspan="3" data-export-label="Dropin Plugins (<?php echo count( $dropins_mu_plugins['dropins'] ); ?>)"><h2><?php esc_html_e( 'Dropin Plugins', 'card-oracle' ); ?> (<?php echo count( $dropins_mu_plugins['dropins'] ); ?>)</h2></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ( $dropins_mu_plugins['dropins'] as $dropin ) {
					?>
					<tr>
						<td><?php echo wp_kses_post( $dropin['plugin'] ); ?></td>
						<td class="card-oracle-help-blank">&nbsp;</td>
						<td><?php echo wp_kses_post( $dropin['name'] ); ?>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
		<?php
	endif;
	if ( 0 < count( $dropins_mu_plugins['mu_plugins'] ) ) :
		?>
		<table class="card_oracle_status_table widefat">
			<thead>
				<tr>
					<th colspan="3" data-export-label="Must Use Plugins (<?php echo count( $dropins_mu_plugins['mu_plugins'] ); ?>)"><h2><?php esc_html_e( 'Must Use Plugins', 'card-oracle' ); ?> (<?php echo count( $dropins_mu_plugins['mu_plugins'] ); ?>)</h2></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ( $dropins_mu_plugins['mu_plugins'] as $mu_plugin ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					$plugin_name = esc_html( $mu_plugin['name'] );
					if ( ! empty( $mu_plugin['url'] ) ) {
						$plugin_name = '<a href="' . esc_url( $mu_plugin['url'] ) . '" aria-label="' . esc_attr__( 'Visit plugin homepage', 'card-oracle' ) . '" target="_blank">' . $plugin_name . '</a>';
					}
					?>
					<tr>
						<td><?php echo wp_kses_post( $plugin_name ); ?></td>
						<td class="card-oracle-help-blank">&nbsp;</td>
						<td>
						<?php
							/* translators: %s: plugin author */
							printf( esc_html__( 'by %s', 'card-oracle' ), esc_html( $mu_plugin['author_name'] ) );
							echo ' &ndash; ' . esc_html( $mu_plugin['version'] );
						?>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
	<?php endif; ?>
	<table class="card_oracle_status_table widefat" >
		<thead>
			<tr>
				<th colspan="3" data-export-label="Theme"><h2><?php esc_html_e( 'Theme', 'card-oracle' ); ?></h2></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td data-export-label="Name"><?php esc_html_e( 'Name', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The name of the current active theme.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td><?php echo esc_html( $theme['name'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="Version"><?php esc_html_e( 'Version', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The installed version of the current active theme.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td><?php echo esc_html( $theme['version'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="Author URL"><?php esc_html_e( 'Author URL', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The theme developers URL.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td><?php echo esc_html( $theme['author_url'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="Child Theme"><?php esc_html_e( 'Child theme', 'card-oracle' ); ?>:</td>
				<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'Displays whether or not the current theme is a child theme.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td>
					<?php
					if ( $theme['is_child_theme'] ) {
						echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
					} else {
						/* Translators: %s docs link. */
						echo '<span class="dashicons dashicons-no-alt"></span> &ndash; ' . wp_kses_post( sprintf( __( 'If you are thinking about modifying a parent theme, we recommend using a child theme. See: <a href="%s" target="_blank">How to create a child theme</a>', 'card-oracle' ), 'https://developer.wordpress.org/themes/advanced-topics/child-themes/' ) );
					}
					?>
					</td>
			</tr>
			<?php if ( $theme['is_child_theme'] ) : ?>
				<tr>
					<td data-export-label="Parent Theme Name"><?php esc_html_e( 'Parent theme name', 'card-oracle' ); ?>:</td>
					<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The name of the parent theme.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
					<td><?php echo esc_html( $theme['parent_name'] ); ?></td>
				</tr>
				<tr>
					<td data-export-label="Parent Theme Version"><?php esc_html_e( 'Parent theme version', 'card-oracle' ); ?>:</td>
					<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The installed version of the parent theme.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
					<td>
						<?php
						echo esc_html( $theme['parent_version'] );
						if ( version_compare( $theme['parent_version'], $theme['parent_version_latest'], '<' ) ) {
							/* translators: %s: parent theme latest version */
							echo ' &ndash; <strong style="color:red;">' . sprintf( esc_html__( '%s is available', 'card-oracle' ), esc_html( $theme['parent_version_latest'] ) ) . '</strong>';
						}
						?>
					</td>
				</tr>
				<tr>
					<td data-export-label="Parent Theme Author URL"><?php esc_html_e( 'Parent theme author URL', 'card-oracle' ); ?>:</td>
					<td class="help-tip"><?php echo card_oracle_help_tip( esc_html__( 'The parent theme developers URL.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
					<td><?php echo esc_html( $theme['parent_author_url'] ); ?></td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
	<?php if ( $md5_files ) : ?>
		<table class="card_oracle_status_table widefat">
			<thead>
				<tr>
					<th colspan="3" data-export-label="Card Oracle MD5 Sums"><h2><?php esc_html_e( 'Card Oracle MD5 Sums', 'card-oracle' ); ?></h2></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( $md5_files as $md5_file ) { ?>
				<?php $md5 = md5_file( $md5_file['filename'] ); ?>
				<tr>
					<td><?php echo esc_html( $md5_file['name'] ); ?></td>
					<td class="card-oracle-help-blank">&nbsp;</td>
					<?php if ( strcmp( $md5, $md5_file['md5sum'] ) !== 0 ) : ?>
						<td style="color:red;"><?php echo esc_html( $md5 ); ?></td>
					<?php else : ?>
						<td><?php echo esc_html( $md5 ); ?></td>
					<?php endif; ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php endif; ?>
	<?php if ( $error_log_counts > 0 ) : ?>
		<table class="card_oracle_status_table widefat">
			<thead>
				<tr>
					<th colspan="3" data-export-label="Error Logs (<?php echo esc_attr( $error_log_counts ); ?>)"><h2><?php esc_html_e( 'Error Logs', 'card-oracle' ); ?> (<?php echo esc_attr( $error_log_counts ); ?>)</h2></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$total_rows = $error_log_rows > $error_log_counts ? $error_log_counts : $error_log_rows;
				$logs       = $co_logs->get_connected_logs(
					array(
						'posts_per_page' => $total_rows,
						'log_type'       => 'error',
					)
				);

				if ( $logs ) {
					echo '<tr><td>' . esc_html__( 'Date', 'card-oracle' ) . '</td>';
					echo '<td>' . esc_html__( 'Title', 'card-oracle' ) . '</td>';
					echo '<td>' . esc_html__( 'Message', 'card-oracle' ) . '</td></tr>';
					foreach ( $logs as $log ) {
						?>
					<tr>
						<td><?php echo wp_kses_post( date_i18n( 'Y-M-d G:i:s', strtotime( $log->post_date ) ) ); ?></td>
						<td><?php echo wp_kses_post( $log->post_title ); ?></td>
						<td><?php echo wp_kses_post( $log->post_content ); ?></td>
					</tr>
						<?php
					}
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2" >
						<form action="" method="post">
							<input type="hidden" value="true" name="co_clear_logs_button" />
							<?php wp_nonce_field( 'co_clear_logs_button' ); ?>
							<?php submit_button( esc_attr( __( 'Clear Error Logs', 'card-oracle' ) ), 'primary', '', '' ); ?>
						</form>
					</td>
					<td style="text-align: right;">
						<form action="", method="post" id="error_rows_form" autocomplete="off">
						<span><?php esc_html_e( 'Rows to display:', 'card-oracle' ); ?></span>&nbsp;
						<select id="error_rows" name="error_rows">
							<?php
							$numrows_arr = array( '5', '10', '25', '50' );
							foreach ( $numrows_arr as $nrow ) {
								$selected = $error_log_rows === $nrow ? 'selected' : '';

								echo '<option value="' . esc_attr( $nrow ) . '" ' . esc_attr( $selected ) . '>' . esc_attr( $nrow ) . '</option>';
							}
							?>
							</select>
						</form>
					</td>
				</tr>
			</tfoot>
		</table>
	<?php endif; ?>
	<?php if ( $co_logs->has_debug_constant() && $co_logs->check_file_data() ) : ?>
	<table class="card_oracle_status_table widefat">
		<thead>
			<tr>
				<th id="card-oracle-debug-header" colspan="3" data-export-label="Debug Log"><h2><?php echo esc_html__( 'Debug Log', 'card-oracle' ) . ' (' . esc_attr( $environment['log_file'] ) . ')'; ?></h2></th>
			</tr>
		</thead>
		<tbody>
			<tr><td>
			<?php $co_logs->view_log( $environment['log_file'] ); ?>
			</td></tr>
		</tbody>
	</table>
	<?php endif; ?>
<?php } ?>
