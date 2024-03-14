<?php

namespace FloatingButton\Dashboard;

defined( 'ABSPATH' ) || exit;

use FloatingButton\WOW_Plugin;

class SupportForm {

	public static function init(): void {

		$plugin  = WOW_Plugin::info( 'name' ) . ' v.' . WOW_Plugin::info( 'version' );
		$license = get_option( 'wow_license_key_' . WOW_Plugin::PREFIX, 'no' );

		self::send();

		?>

        <form method="post">

            <fieldset>
                <legend>
					<?php esc_html_e( 'Support Form', 'floating-button' ); ?>
                </legend>

                <div class="wowp-field has-addon">
                    <label for="support-name" class="label"><?php esc_html_e( 'Your Name', 'floating-button' ); ?></label>
                    <input type="text" name="support[name]" id="support-name">
                    <span class="is-addon">
                        <span class="dashicons dashicons-text"></span>
                    </span>
                </div>

                <div class="wowp-field has-addon">
                    <label for="support-email" class="label"><?php esc_html_e( 'Contact email', 'floating-button' ); ?></label>
                    <input type="text" name="support[email]" id="support-email"
                           value="<?php echo sanitize_email( get_option( 'admin_email' ) ); ?>">
                    <span class="is-addon">
                        <span class="dashicons dashicons-email"></span>
                    </span>
                </div>

                <div class="wowp-field has-addon">
                    <label for="support-link"
                           class="label"><?php esc_html_e( 'Link to the issue', 'floating-button' ); ?></label>
                    <input type="text" name="support[link]" id="support-link"
                           value="<?php echo esc_url( get_option( 'home' ) ); ?>">
                    <span class="is-addon">
                        <span class="dashicons dashicons-admin-links"></span>
                    </span>
                </div>

                <div class="wowp-field has-addon">
                    <label for="support-type" class="label"><?php esc_html_e( 'Message type', 'floating-button' ); ?></label>
                    <select name="support[type]" id="support-type">
                        <option value="Issue"><?php esc_html_e( 'Issue', 'floating-button' ); ?></option>
                        <option value="Idea"><?php esc_html_e( 'Idea', 'floating-button' ); ?></option>
                    </select>
                    <span class="is-addon">
                        🗯
                    </span>
                </div>

                <div class="wowp-field has-addon">
                    <label for="support-plugin" class="label"><?php esc_html_e( 'Plugin', 'floating-button' ); ?></label>
                    <input type="text" readonly name="support[plugin]" id="support-plugin"
                           value="<?php echo esc_attr( $plugin ); ?>">
                    <span class="is-addon">
                    <span class="dashicons dashicons-admin-plugins"></span>
                         </span>
                </div>

                <div class="wowp-field has-addon">
                    <label for="support-license" class="label"><?php esc_html_e( 'License Key', 'floating-button' ); ?></label>
                    <input type="text" readonly name="support[license]" id="support-license"
                           value="<?php echo esc_attr( $license ); ?>">
                    <span class="is-addon">
                    🔑
                        </span>
                </div>

                <div class="wowp-field is-full">
					<?php
					$content   = esc_attr__( 'Enter Your Message', 'floating-button' );
					$editor_id = 'support-message';
					$settings  = array(
						'textarea_name' => 'support[message]',
					);
					wp_editor( $content, $editor_id, $settings ); ?>
                </div>

                <div class="wowp-field">
					<?php submit_button( __( 'Send to Support', 'floating-button' ), 'primary', 'submit', false ); ?>
                </div>

				<?php wp_nonce_field( WOW_Plugin::PREFIX . '_nonce_action', WOW_Plugin::PREFIX . '_nonce_name' ); ?>
            </fieldset>

            <div class="wowp-field is-full">
                <input type="checkbox" name="support[debug]" id="support-debug" value="1" checked="checked">
                <label for="support-debug">Send system information</label>
            </div>
            <div class="wowp-field is-full">
                <p></p>
                <details class="wowp-details">
                    <summary><?php esc_html_e( 'System Information', 'floating-button' ); ?></summary>
                    <textarea readonly="readonly" aria-readonly="true" name="support[info]" rows="20" cols="100"
                              class="large-text code"><?php echo esc_html( self::sysinfo_get() ); ?></textarea>
                </details>
            </div>

        </form>

		<?php


	}

	private static function send(): void {
		if ( ! self::verify() ) {
			return;
		}


		$error = self::error();
		if ( ! empty( $error ) ) {
			echo '<p class="wowp-notice wowp-error">' . esc_html( $error ) . '</p>';

			return;
		}

		$support = $_POST['support'];

		$headers = array(
			'From: ' . esc_attr( $support['name'] ) . ' <' . sanitize_email( $support['email'] ) . '>',
			'content-type: text/html',
		);

		$debug = isset( $support['debug'] ) ? '<p style="font-size: 12px;">-------<br/>' . nl2br( wp_kses_post( $support['info'] ) ) .'</p>' : '';

		$message_mail = '<html>
                        <head></head>
                        <body>
                        <table>
                        <tr>
                        <td><strong>License Key:</strong></td>
                        <td>' . esc_attr( $support['license'] ) . '</td>
                        </tr>
                        <tr>
                        <td><strong>Plugin:</strong></td>
                        <td>' . esc_attr( $support['plugin'] ) . '</td>
                        </tr>
                        <tr>
                        <td><strong>Website:</strong></td>
                        <td><a href="' . esc_url( $support['link'] ) . '" target="_blank">' . esc_url( $support['link'] ) . '</a></td>
                        </tr>
                        </table>
                        <p/>
                        ' . nl2br( wp_kses_post( $support['message'] ) ) . wp_kses_post( $debug ) . ' 
                        </body>
                        </html>';
		$type         = sanitize_text_field( $support['type'] );
		$to_mail      = WOW_Plugin::info( 'email' );
		$send         = wp_mail( $to_mail, 'Support Ticket: ' . $type, $message_mail, $headers );

		if ( $send ) {
			$text = __( 'Your message has been sent to the support team.', 'floating-button' );
			echo '<p class="wowp-notice wowp-success">' . esc_html( $text ) . '</p>';
		} else {
			$text = __( 'Sorry, but message did not send. Please, contact us ', 'floating-button' ) . $to_mail;
			echo '<p class="wowp-notice wowp-error">' . esc_html( $text ) . '</p>';
		}

	}

	private static function error(): ?string {
		if ( ! self::verify() ) {
			return '';
		}
		$support = $_POST['support'];
		$fields  = [ 'name', 'email', 'link', 'type', 'plugin', 'license', 'message' ];

		foreach ( $fields as $field ) {
			if ( empty( $support[ $field ] ) ) {
				return __( 'Please fill in all the form fields below.', 'floating-button' );
			}
		}

		return '';
	}

	private static function verify(): bool {
		$support      = $_POST['support'] ?? [];
		$nonce_name   = WOW_Plugin::PREFIX . '_nonce_name';
		$nonce_action = WOW_Plugin::PREFIX . '_nonce_action';

		return ! empty( $support ) && wp_verify_nonce( $_POST[ $nonce_name ], $nonce_action );
	}

	private static function sysinfo_get() {
		global $wpdb;

		// Get theme info
		$theme_data   = wp_get_theme();
		$theme        = $theme_data->Name . ' ' . $theme_data->Version;
		$parent_theme = $theme_data->Template;
		if ( ! empty( $parent_theme ) ) {
			$parent_theme_data = wp_get_theme( $parent_theme );
			$parent_theme      = $parent_theme_data->Name . ' ' . $parent_theme_data->Version;
		}

		$return = '### Begin System Info (Generated ' . date( 'Y-m-d H:i:s' ) . ') ###' . "\n\n";

		// Start with the basics...
		$return .= '-- Site Info' . "\n\n";
		$return .= 'Site URL:                 ' . site_url() . "\n";
		$return .= 'Home URL:                 ' . home_url() . "\n";
		$return .= 'Multisite:                ' . ( is_multisite() ? 'Yes' : 'No' ) . "\n";

		$locale = get_locale();

		// WordPress configuration
		$return .= "\n" . '-- WordPress Configuration' . "\n\n";
		$return .= 'Version:                  ' . get_bloginfo( 'version' ) . "\n";
		$return .= 'Language:                 ' . ( ! empty( $locale ) ? $locale : 'en_US' ) . "\n";
		$return .= 'Permalink Structure:      ' . ( get_option( 'permalink_structure' ) ?: 'Default' ) . "\n";
		$return .= 'Active Theme:             ' . $theme . "\n";
		$return .= 'WP Timezone:              ' . wp_timezone_string() . "\n";
		if ( $parent_theme !== $theme ) {
			$return .= 'Parent Theme:             ' . $parent_theme . "\n";
		}

		$return .= "\n";

		$return .= 'Show On Front:            ' . get_option( 'show_on_front' ) . "\n";

		// Only show page specs if frontpage is set to 'page'
		if ( get_option( 'show_on_front' ) == 'page' ) {
			$front_page_id = get_option( 'page_on_front' );
			$blog_page_id  = get_option( 'page_for_posts' );

			$return .= 'Page On Front:            ' . ( $front_page_id != 0 ? '#' . $front_page_id : 'Unset' ) . "\n";
			$return .= 'Page For Posts:           ' . ( $blog_page_id != 0 ? '#' . $blog_page_id : 'Unset' ) . "\n";
		}

		$return .= 'ABSPATH:                  ' . ABSPATH . "\n";

		$return .= 'Table Prefix:             ' . 'Length: ' . strlen( $wpdb->prefix ) . '   Status: ' . ( strlen( $wpdb->prefix ) > 16 ? 'ERROR: Too long' : 'Acceptable' ) . "\n";
		// Commented out per https://github.com/easydigitaldownloads/Easy-Digital-Downloads/issues/3475
		//$return .= 'Admin AJAX:               ' . ( edd_test_ajax_works() ? 'Accessible' : 'Inaccessible' ) . "\n";
		$return .= 'WP_DEBUG:                 ' . ( defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set' ) . "\n";
		$return .= 'Memory Limit:             ' . WP_MEMORY_LIMIT . "\n";

		// EDD Database tables
		$return .= "\n" . '-- Plugin Database Table' . "\n\n";

		$database_name  = $wpdb->prefix . WOW_Plugin::PREFIX;
		$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $database_name ) );

		if (  $wpdb->get_var( $query ) == $database_name ) {
			$row_count = $wpdb->get_var( "SELECT COUNT(*) FROM $database_name" );
			$return    .= $database_name . '              Number of rows in table ' . absint( $row_count ) . "\n";
		} else {
			$return .= $database_name . '              Database does not exist.' . "\n";
		}

		// Get plugins that have an update
		$updates = get_plugin_updates();

		// Must-use plugins
		// NOTE: MU plugins can't show updates!
		$muplugins = get_mu_plugins();
		if ( count( $muplugins ) > 0 ) {
			$return .= "\n" . '-- Must-Use Plugins' . "\n\n";

			foreach ( $muplugins as $plugin => $plugin_data ) {
				$return .= str_pad( $plugin_data['Name'] . ': ', 26, ' ' ) . $plugin_data['Version'] . "\n";
			}

			$return = apply_filters( 'edd_sysinfo_after_wordpress_mu_plugins', $return );
		}

		// WordPress active plugins
		$return .= "\n" . '-- WordPress Active Plugins' . "\n\n";

		$plugins        = get_plugins();
		$active_plugins = get_option( 'active_plugins', array() );

		foreach ( $plugins as $plugin_path => $plugin ) {
			if ( ! in_array( $plugin_path, $active_plugins ) ) {
				continue;
			}

			$update     = ( array_key_exists( $plugin_path, $updates ) ) ? ' (needs update - ' . $updates[ $plugin_path ]->update->new_version . ')' : '';
			$plugin_url = '';
			if ( ! empty( $plugin['PluginURI'] ) ) {
				$plugin_url = $plugin['PluginURI'];
			} elseif ( ! empty( $plugin['AuthorURI'] ) ) {
				$plugin_url = $plugin['AuthorURI'];
			} elseif ( ! empty( $plugin['Author'] ) ) {
				$plugin_url = $plugin['Author'];
			}
			if ( $plugin_url ) {
				$plugin_url = "\n" . $plugin_url;
			}
			$return .= str_pad( $plugin['Name'] . ': ', 26, ' ' ) . $plugin['Version'] . $update . $plugin_url . "\n\n";
		}

		// WordPress inactive plugins
		$return .= "\n" . '-- WordPress Inactive Plugins' . "\n\n";

		foreach ( $plugins as $plugin_path => $plugin ) {
			if ( in_array( $plugin_path, $active_plugins ) ) {
				continue;
			}

			$update     = ( array_key_exists( $plugin_path, $updates ) ) ? ' (needs update - ' . $updates[ $plugin_path ]->update->new_version . ')' : '';
			$plugin_url = '';
			if ( ! empty( $plugin['PluginURI'] ) ) {
				$plugin_url = $plugin['PluginURI'];
			} elseif ( ! empty( $plugin['AuthorURI'] ) ) {
				$plugin_url = $plugin['AuthorURI'];
			} elseif ( ! empty( $plugin['Author'] ) ) {
				$plugin_url = $plugin['Author'];
			}
			if ( $plugin_url ) {
				$plugin_url = "\n" . $plugin_url;
			}
			$return .= str_pad( $plugin['Name'] . ': ', 26, ' ' ) . $plugin['Version'] . $update . $plugin_url . "\n\n";
		}

		if ( is_multisite() ) {
			// WordPress Multisite active plugins
			$return .= "\n" . '-- Network Active Plugins' . "\n\n";

			$plugins        = wp_get_active_network_plugins();
			$active_plugins = get_site_option( 'active_sitewide_plugins', array() );

			foreach ( $plugins as $plugin_path ) {
				$plugin_base = plugin_basename( $plugin_path );

				if ( ! array_key_exists( $plugin_base, $active_plugins ) ) {
					continue;
				}

				$update     = ( array_key_exists( $plugin_path, $updates ) ) ? ' (needs update - ' . $updates[ $plugin_path ]->update->new_version . ')' : '';
				$plugin     = get_plugin_data( $plugin_path );
				$plugin_url = '';
				if ( ! empty( $plugin['PluginURI'] ) ) {
					$plugin_url = $plugin['PluginURI'];
				} elseif ( ! empty( $plugin['AuthorURI'] ) ) {
					$plugin_url = $plugin['AuthorURI'];
				} elseif ( ! empty( $plugin['Author'] ) ) {
					$plugin_url = $plugin['Author'];
				}
				if ( $plugin_url ) {
					$plugin_url = "\n" . $plugin_url;
				}
				$return .= str_pad( $plugin['Name'] . ': ', 26, ' ' ) . $plugin['Version'] . $update . $plugin_url . "\n\n";
			}
		}

		// Server configuration (really just versioning)
		$return .= "\n" . '-- Webserver Configuration' . "\n\n";
		$return .= 'PHP Version:              ' . PHP_VERSION . "\n";
		$return .= 'MySQL Version:            ' . $wpdb->db_version() . "\n";
		$return .= 'Webserver Info:           ' . $_SERVER['SERVER_SOFTWARE'] . "\n";

		// PHP configs... now we're getting to the important stuff
		$return .= "\n" . '-- PHP Configuration' . "\n\n";
		$return .= 'Memory Limit:             ' . ini_get( 'memory_limit' ) . "\n";
		$return .= 'Upload Max Size:          ' . ini_get( 'upload_max_filesize' ) . "\n";
		$return .= 'Post Max Size:            ' . ini_get( 'post_max_size' ) . "\n";
		$return .= 'Upload Max Filesize:      ' . ini_get( 'upload_max_filesize' ) . "\n";
		$return .= 'Time Limit:               ' . ini_get( 'max_execution_time' ) . "\n";
		$return .= 'Max Input Vars:           ' . ini_get( 'max_input_vars' ) . "\n";
		$return .= 'Display Errors:           ' . ( ini_get( 'display_errors' ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A' ) . "\n";

		// PHP extensions and such
		$return .= "\n" . '-- PHP Extensions' . "\n\n";
		$return .= 'cURL:                     ' . ( function_exists( 'curl_init' ) ? 'Supported' : 'Not Supported' ) . "\n";
		$return .= 'fsockopen:                ' . ( function_exists( 'fsockopen' ) ? 'Supported' : 'Not Supported' ) . "\n";
		$return .= 'SOAP Client:              ' . ( class_exists( 'SoapClient' ) ? 'Installed' : 'Not Installed' ) . "\n";
		$return .= 'Suhosin:                  ' . ( extension_loaded( 'suhosin' ) ? 'Installed' : 'Not Installed' ) . "\n";

		$return .= "\n" . '### End System Info ###';

		return $return;
	}
}
