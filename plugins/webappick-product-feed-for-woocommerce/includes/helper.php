<?php /** @noinspection PhpStatementHasEmptyBodyInspection, PhpUnusedLocalVariableInspection, PhpUnusedParameterInspection, PhpIncludeInspection */
/**
 * Helper Functions
 * @package WooFeed
 * @subpackage WooFeed_Helper_Functions
 * @version 1.0.2
 * @since WooFeed 3.1.40
 * @author KD <mhamudul.hk@gmail.com>
 * @copyright WebAppick
 *
 */


use CTXFeed\V5\Compatibility\WCMLCurrency;
use CTXFeed\V5\Notice\Dismiss;

if ( ! defined( 'ABSPATH' ) ) {
	die(); // Silence...
}

if ( ! function_exists( 'woo_feed_maybe_define_constant' ) ) {
	/**
	 * Define a constant if it is not already defined.
	 *
	 * @param string $name Constant name.
	 * @param mixed $value Value.
	 *
	 * @return void
	 * @since  3.2.1
	 */
	function woo_feed_maybe_define_constant( $name, $value ) {
		// phpcs:disable
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
		// phpcs:enable
	}
}
if ( ! function_exists( 'woo_feed_doing_it_wrong' ) ) {
	/**
	 * Wrapper for _doing_it_wrong.
	 *
	 * @param string $function Function used.
	 * @param string $message Message to log.
	 * @param string $version Version the message was added in.
	 *
	 * @return void
	 * @since  3.2.1
	 *
	 */
	function woo_feed_doing_it_wrong( $function, $message, $version ) {
		// phpcs:disable
		$message .= ' Backtrace: ' . wp_debug_backtrace_summary();

		if ( is_ajax() || WC()->is_rest_api_request() ) {
			do_action( 'doing_it_wrong_run', $function, $message, $version );
			error_log( "{$function} was called incorrectly. {$message}. This message was added in version {$version}." );
		} else {
			_doing_it_wrong( $function, $message, $version );
		}
		// phpcs:enable
	}
}
if ( ! function_exists( 'is_ajax' ) ) {

	/**
	 * Is_ajax - Returns true when the page is loaded via ajax.
	 *
	 * @return bool
	 */
	function is_ajax() {
		return function_exists( 'wp_doing_ajax' ) ? wp_doing_ajax() : defined( 'DOING_AJAX' );
	}
}
if ( ! function_exists( 'woo_feed_is_plugin_active' ) ) {
	/**
	 * Determines whether a plugin is active.
	 *
	 * @param string $plugin Path to the plugin file relative to the plugins directory.
	 *
	 * @return bool True, if in the active plugins list. False, not in the list.
	 * @since 3.1.41
	 * @see is_plugin_active()
	 *
	 */
	function woo_feed_is_plugin_active( $plugin ) {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return is_plugin_active( $plugin );
	}
}
if ( ! function_exists( 'wooFeed_is_plugin_inactive' ) ) {
	/**
	 * Determines whether the plugin is inactive.
	 *
	 * @param string $plugin Path to the plugin file relative to the plugins directory.
	 *
	 * @return bool True if inactive. False if active.
	 * @since 3.1.41
	 * @see wooFeed_is_plugin_inactive()
	 *
	 */
	function wooFeed_is_plugin_inactive( $plugin ) {
		return ! woo_feed_is_plugin_active( $plugin );
	}
}
if ( ! function_exists( 'wooFeed_deactivate_plugins' ) ) {
	/**
	 * Deactivate a single plugin or multiple plugins.
	 * Wrapper for core deactivate_plugins() function
	 *
	 * @param string|array $plugins Single plugin or list of plugins to deactivate.
	 * @param bool $silent Prevent calling deactivation hooks. Default is false.
	 * @param mixed $network_wide Whether to deactivate the plugin for all sites in the network.
	 *
	 * @return void
	 * @see deactivate_plugins()
	 *
	 */
	function wooFeed_Deactivate_plugins( $plugins, $silent = false, $network_wide = null ) {
		if ( ! function_exists( 'deactivate_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		deactivate_plugins( $plugins, $silent, $network_wide );
	}
}
if ( ! function_exists( 'wooFeed_is_supported_php' ) ) {
	/**
	 * Check if server php version meet minimum requirement
	 * @return bool
	 * @since 3.1.41
	 */
	function wooFeed_is_supported_php() {
		// PHP version need to be => WOO_FEED_MIN_PHP_VERSION
		return ! version_compare( PHP_VERSION, WOO_FEED_MIN_PHP_VERSION, '<' );
	}
}
if ( ! function_exists( 'wooFeed_check_WC' ) ) {
	function wooFeed_check_WC() {
		return class_exists( 'WooCommerce', false );
	}
}
if ( ! function_exists( 'wooFeed_is_WC_supported' ) ) {
	function wooFeed_is_WC_supported() {
		// Ensure WC is loaded before checking version
		return ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, WOO_FEED_MIN_WC_VERSION, '>=' ) );
	}
}
if ( ! function_exists( 'woo_feed_wc_version_check' ) ) {
	/**
	 * Check WooCommerce Version
	 *
	 * @param string $version
	 *
	 * @return bool
	 */
	function woo_feed_wc_version_check( $version = '3.0' ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$plugins = get_plugins();
		if ( array_key_exists( 'woocommerce/woocommerce.php', $plugins ) ) {
			$currentVersion = $plugins['woocommerce/woocommerce.php']['Version'];
			if ( version_compare( $currentVersion, $version, '>=' ) ) {
				return true;
			}
		}

		return false;
	}
}
if ( ! function_exists( 'woo_feed_wpml_version_check' ) ) {
	/**
	 * Check WooCommerce Version
	 *
	 * @param string $version
	 *
	 * @return bool
	 */
	function woo_feed_wpml_version_check( $version = '3.2' ) {
		// calling this function too early (before wc loaded) will not give correct output
		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			if ( version_compare( ICL_SITEPRESS_VERSION, $version, '>=' ) ) {
				return true;
			}
		}

		return false;
	}
}
if ( ! function_exists( 'wooFeed_Admin_Notices' ) ) {
	/**
	 * Display Admin Messages
	 * @hooked admin_notices
	 * @return void
	 * @since 3.1.41
	 */
	function wooFeed_Admin_Notices() {
		// @TODO Refactor this function with admin message class
		// WC Missing Notice..
		if ( ! wooFeed_check_WC() ) {
			$plugin_url = self_admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' );
			/**
			 * @noinspection HtmlUnknownTarget
			 */
			$plugin_url  = sprintf( '<a href="%s">%s</a>', $plugin_url, esc_html__( 'WooCommerce', 'woo-feed' ) );
			$plugin_name = sprintf( '<code>%s</code>', esc_html__( 'CTX Feed', 'woo-feed' ) );
			$wc_name     = sprintf( '<code>%s</code>', esc_html__( 'WooCommerce', 'woo-feed' ) );
			$message     = sprintf(
			/* translators: 1: this plugin name, 2: required plugin name, 3: required plugin name and installation url */
				esc_html__( '%1$s requires %2$s to be installed and active. You can installed/activate %3$s here.', 'woo-feed' ),
				$plugin_name,
				$wc_name,
				$plugin_url
			);
			printf( '<div class="error"><p><strong>%1$s</strong></p></div>', $message ); // phpcs:ignore
		}
		if ( wooFeed_check_WC() && ! wooFeed_is_WC_supported() ) {
			$plugin_url = self_admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' );
			$wcVersion  = defined( 'WC_VERSION' ) ? '<code>' . WC_VERSION . '</code>' : '<code>UNKNOWN</code>';
			$minVersion = '<code>' . WOO_FEED_MIN_WC_VERSION . '</code>';
			/**
			 * @noinspection HtmlUnknownTarget
			 */
			$plugin_url  = sprintf( '<a href="%s">%s</a>', $plugin_url, esc_html__( 'WooCommerce', 'woo-feed' ) );
			$plugin_name = sprintf( '<code>%s</code>', esc_html__( 'CTX Feed', 'woo-feed' ) );
			$wc_name     = sprintf( '<code>%s</code>', esc_html__( 'WooCommerce', 'woo-feed' ) );
			$message     = sprintf(
			/* translators: 1: this plugin name, 2: required plugin name, 3: required plugin required version, 4: required plugin current version, 5: required plugin update url and name */
				esc_html__( '%1$s requires %2$s version %3$s or above and %4$s found. Please upgrade %2$s to the latest version here %5$s', 'woo-feed' ),
				$plugin_name,
				$wc_name,
				$minVersion,
				$wcVersion,
				$plugin_url
			);
			printf( '<div class="error"><p><strong>%1$s</strong></p></div>', $message ); // phpcs:ignore
		}
	}
}
if ( ! function_exists( 'woo_feed_black_friday_notice' ) ) {
	/**
	 * CTX Feed Black Friday Notice
	 *
	 * @since 4.4.35
	 * @author Nazrul Islam Nayan
	 */
	function woo_feed_black_friday_notice() {
		$user_id = get_current_user_id();
		if ( ! get_user_meta( $user_id, 'woo_feed_black_friday_notice_2023_dismissed' ) ) {
			ob_start();
			?>
            <script type="text/javascript">
                (function ($) {
                    $(document).on('click', '.woo-feed-ctx-startup-notice button.notice-dismiss', function (e) {
                        e.preventDefault();
                        let nonce = $('#woo_feed_to_ctx_feed_nonce').val();
                        //woo feed black friday notice cancel callback
                        wp.ajax.post('woo_feed_save_black_friday_notice_2023_notice', {
                            _wp_ajax_nonce: nonce,
                            clicked: true,
                        }).then(response => {
                            console.log(response);
                        }).fail(error => {
                            console.log(error);
                        });
                    });
                })(jQuery)
            </script>
            <a  target="_blank" href="https://webappick.com/plugin/woocommerce-product-feed-pro/?utm_source=BFCM_banner&utm_medium=BFCM_Banner_Free_to_pro&utm_campaign=BFCM23&utm_id=1"
                class="notice woo-feed-ctx-startup-notice is-dismissible"
                style="background: url(<?php echo WOO_FEED_PLUGIN_URL . 'admin/images/ctx-feed-black-friday-banner-2023.png'; ?>) no-repeat top center;">
                <input type="hidden" id="woo_feed_to_ctx_feed_nonce"
                       value="<?php echo wp_create_nonce( 'woo-feed-to-ctx-feed-notice' ); ?>">
            </a>
			<?php
			$image = ob_get_contents();
		}
	}
}


if ( ! function_exists( 'woo_feed_halloween_notice_random' ) ) {
	/**
	 * CTX Feed Halloween Notice Random
	 *
	 * @since 4.5.3
	 * @author Nashir Uddin
	 */
	function woo_feed_halloween_notice_random() {
        $randomNumber = rand(1, 2);

        if ($randomNumber === 1) {
	        woo_feed_halloween_notice();
        } else {
	        woo_feed_halloween_notice_2();
        }
	}
}


if ( ! function_exists( 'woo_feed_halloween_notice' ) ) {
	/**
	 * CTX Feed Halloween Notice
	 *
	 * @since 4.5.3
	 * @author Nashir Uddin
	 */
	function woo_feed_halloween_notice() {
		$user_id = get_current_user_id();
		if ( ! get_user_meta( $user_id, 'woo_feed_halloween_notice_2023_dismissed' ) ) {
			ob_start();
			?>
			<script type="text/javascript">
				(function ($) {
					$(document).on('click', '.woo-feed-ctx-halloween-notice button.notice-dismiss', function (e) {
						e.preventDefault();
						let nonce = $('#woo_feed_to_ctx_feed_halloween_nonce').val();

						//woo feed halloween cancel callback
						wp.ajax.post('woo_feed_save_halloween_notice', {
							_wp_ajax_nonce: nonce,
							clicked: true,
						}).then(response => {
							console.log(response);
						}).fail(error => {
							console.log(error);
						});
					});
				})(jQuery)
			</script>
			<a target="_blank" href="https://webappick.com/plugin/woocommerce-product-feed-pro/?utm_source=HW_Banner_1b&utm_medium=HW_Banner_Free_to_pro&utm_campaign=HWbanner23&utm_id=1"
			   class="notice woo-feed-ctx-halloween-notice is-dismissible"
			   style="background: url(<?php echo WOO_FEED_PLUGIN_URL . 'admin/images/woo_feed_halloween_notice.png'; ?>) no-repeat top center;">
				<input type="hidden" id="woo_feed_to_ctx_feed_halloween_nonce"
					   value="<?php echo wp_create_nonce( 'woo-feed-to-ctx-feed-halloween-nonce' ); ?>">
			</a>
			<?php
			$image = ob_get_contents();
		}
	}
}

if ( ! function_exists( 'woo_feed_halloween_notice_2' ) ) {
	/**
	 * CTX Feed Halloween Notice
	 *
	 * @since 4.5.3
	 * @author Nashir Uddin
	 */
	function woo_feed_halloween_notice_2() {
		$user_id = get_current_user_id();
		if ( ! get_user_meta( $user_id, 'woo_feed_halloween_notice_2023_dismissed' ) ) {
			ob_start();
			?>
            <script type="text/javascript">
                (function ($) {
                    $(document).on('click', '.woo-feed-ctx-halloween-notice button.notice-dismiss', function (e) {
                        e.preventDefault();
                        let nonce = $('#woo_feed_to_ctx_feed_halloween_nonce').val();

                        //woo feed halloween cancel callback
                        wp.ajax.post('woo_feed_save_halloween_notice', {
                            _wp_ajax_nonce: nonce,
                            clicked: true,
                        }).then(response => {
                            console.log(response);
                        }).fail(error => {
                            console.log(error);
                        });
                    });
                })(jQuery)
            </script>
            <a target="_blank" href="https://webappick.com/plugin/woocommerce-product-feed-pro/?utm_source=HW_Banner_2w&utm_medium=HW_Banner_Free_to_pro&utm_campaign=HWbanner23&utm_id=1"
               class="notice woo-feed-ctx-halloween-notice is-dismissible"
               style="background: url(<?php echo WOO_FEED_PLUGIN_URL . 'admin/images/woo_feed_halloween_notice_2.png'; ?>) no-repeat top center;">
                <input type="hidden" id="woo_feed_to_ctx_feed_halloween_nonce"
                       value="<?php echo wp_create_nonce( 'woo-feed-to-ctx-feed-halloween-nonce' ); ?>">
            </a>
			<?php
			$image = ob_get_contents();
		}
	}
}

if ( ! function_exists( 'woo_feed_christmas_notice' ) ) {
	/**
	 * CTX Feed Christmas Notice
	 *
	 * @since 4.5.15
	 * @author Md. Nashir Uddin
	 */
	function woo_feed_christmas_notice() {
		$user_id = get_current_user_id();
		if ( ! get_user_meta( $user_id, 'woo_feed_christmas_notice_2023_dismissed' ) ) {
			ob_start();
			?>
			<script type="text/javascript">
				(function ($) {
					$(document).on('click', '.woo-feed-ctx-startup-notice button.notice-dismiss', function (e) {
						e.preventDefault();
						let nonce = $('#woo_feed_to_ctx_feed_nonce').val();

						//woo feed christmas notice cancel callback
						wp.ajax.post('woo_feed_save_christmas_notice_2023', {
							_wp_ajax_nonce: nonce,
							clicked: true,
						}).then(response => {
							console.log(response);
						}).fail(error => {
							console.log(error);
						});
					});
				})(jQuery)
			</script>
			<a  target="_blank" href="https://webappick.com/plugin/woocommerce-product-feed-pro/?utm_source=Christmass_23&utm_medium=Free_to_Pro&utm_campaign=Christmass23&utm_id=23"
				class="notice woo-feed-ctx-startup-notice is-dismissible"
				style="background: url(<?php echo WOO_FEED_PLUGIN_URL . 'admin/images/christmas-web-banner-2023.png'; ?>) no-repeat top center;">
				<input type="hidden" id="woo_feed_to_ctx_feed_nonce"
					   value="<?php echo wp_create_nonce( 'woo-feed-to-ctx-feed-notice' ); ?>">
			</a>
			<?php
			$image = ob_get_contents();
		}
	}
}

if ( ! function_exists( 'woo_feed_progress_bar' ) ) {
	/**
	 * Feed Progress Bar
	 *
	 * @since 4.1.1
	 */
	function woo_feed_progress_bar() {
		$progress_bar = '';
		ob_start();
		?>
		<div id="feed_progress_table" style="display: none;">
			<table class="table widefat fixed">
				<thead>
				<tr>
					<th><b><?php esc_html_e( 'Generating Product Feed', 'woo-feed' ); ?></b></th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>
						<div class="feed-progress-container">
							<div class="feed-progress-bar">
								<span class="feed-progress-bar-fill"></span>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div style="float: left;"><b style='color: darkblue;'><i
										class='dashicons dashicons-sos wpf_spin'></i></b>&nbsp;&nbsp;&nbsp;
						</div>
						<div class="feed-progress-status"></div>
						<div class="feed-progress-percentage"></div>
					</td>
				</tr>
				</tbody>
			</table>
			<br>
		</div>
		<?php
		$progress_bar .= ob_get_clean();

		echo $progress_bar;

	}
}

if ( ! function_exists( 'checkFTP_connection' ) ) {
	/**
	 * Verify if ftp module enabled
	 * @TODO improve module detection
	 * @return bool
	 */
	function checkFTP_connection() {
		return ( extension_loaded( 'ftp' ) || function_exists( 'ftp_connect' ) );
	}
}
if ( ! function_exists( 'checkSFTP_connection' ) ) {
	/**
	 * Verify if ssh/sftp module enabled
	 * @TODO improve module detection
	 * @return bool
	 */
	function checkSFTP_connection() {
		return ( extension_loaded( 'ssh2' ) || function_exists( 'ssh2_connect' ) );
	}
}
if ( ! function_exists( 'array_splice_assoc' ) ) {
	/**
	 * Array Splice Associative Array
	 * @see https://www.php.net/manual/en/function.array-splice.php#111204
	 *
	 * @param array $input
	 * @param string|int $offset
	 * @param string|int $length
	 * @param array $replacement
	 *
	 * @return array
	 */
	function array_splice_assoc( $input, $offset, $length, $replacement ) {
		$replacement = (array) $replacement;
		$key_indices = array_flip( array_keys( $input ) );
		if ( isset( $input[ $offset ] ) && is_string( $offset ) ) {
			$offset = $key_indices[ $offset ] + 1;
		}
		if ( isset( $input[ $length ] ) && is_string( $length ) ) {
			$length = $key_indices[ $length ] - $offset;
		}

		$input = array_slice( $input, 0, $offset, true ) + $replacement + array_slice( $input, $offset + $length, null, true );

		return $input;
	}
}
if ( ! function_exists( 'woo_feed_get_query_type_options' ) ) {
	/**
	 * Get Query available Types
	 *
	 * @param string $type
	 *
	 * @return array
	 * @since 3.3.11
	 */
	function woo_feed_get_query_type_options( $type = '' ) {
		if ( 'variation' === $type ) {
			return array(
				'individual' => __( 'Individual', 'woo-feed' ),
				'variable'   => __( 'Variable Dependable', 'woo-feed' ),
			);
		} else {
			return array(
				'wc'   => __( 'WC_Product_Query', 'woo-feed' ),
				'wp'   => __( 'WP_Query', 'woo-feed' ),
				'both' => __( 'Both', 'woo-feed' ),
			);
		}
	}
}
if ( ! function_exists( 'woo_feed_get_cache_ttl_options' ) ) {
	/**
	 * Cache Expiration Options
	 * @return array
	 */
	function woo_feed_get_cache_ttl_options() {
		return apply_filters(
			'woo_feed_cache_ttl_options',
			array(
				0                    => esc_html__( 'No Expiration ', 'woo-feed' ),
				MONTH_IN_SECONDS     => esc_html__( '1 Month', 'woo-feed' ),
				WEEK_IN_SECONDS      => esc_html__( '1 Week', 'woo-feed' ),
				DAY_IN_SECONDS       => esc_html__( '24 Hours', 'woo-feed' ),
				12 * HOUR_IN_SECONDS => esc_html__( '12 Hours', 'woo-feed' ),
				6 * HOUR_IN_SECONDS  => esc_html__( '6 Hours', 'woo-feed' ),
				HOUR_IN_SECONDS      => esc_html__( '1 Hours', 'woo-feed' ),
			)
		);
	}
}
if ( ! function_exists( 'woo_feed_get_custom2_merchant' ) ) {
	/**
	 * Get Merchant list that are allowed on Custom2 Template
	 * @return array
	 */
	function woo_feed_get_custom2_merchant() {
		return array( 'custom2', 'admarkt', 'yandex_xml', 'glami' );
	}
}
if ( ! function_exists( 'woo_feed_get_merchant_class' ) ) {
	/**
	 * @param string $provider
	 *
	 * @return string
	 */
	function woo_feed_get_merchant_class( $provider ) {
		if ( in_array(
			$provider,
			array(
				'google',
				'google_shopping_action',
				'google_local',
				'google_local_inventory',
				'adroll',
				'smartly.io',
			),
			true
		) ) {
			return 'Woo_Feed_Google';
		} elseif ( in_array( $provider, array( 'pinterest', 'pinterest_rss' ) ) ) {
			return 'Woo_Feed_Pinterest';
		} elseif ( 'facebook' === $provider ) {
			return 'Woo_Feed_Facebook';
		} elseif ( strpos( $provider, 'amazon' ) !== false ) {
			return 'Woo_Feed_Amazon';
		} elseif ( in_array( $provider, woo_feed_get_custom2_merchant(), true ) ) {
			if ( defined( 'WOO_FEED_PRO_VERSION' ) ) {
				return 'Woo_Feed_Custom_XML';
			} else {
				return 'Woo_Feed_Custom';
			}
			//return 'Woo_Feed_Custom_XML';
		} else {
			return 'Woo_Feed_Custom';
		}
	}
}
if ( ! function_exists( 'woo_feed_handle_file_transfer' ) ) {
	/**
	 * Transfer file as per ftp config
	 *
	 * @param string $fileFrom
	 * @param string $fileTo
	 * @param array $info
	 *
	 * @return bool
	 */
	function woo_feed_handle_file_transfer( $fileFrom, $fileTo, $info ) { // moved to V5/Helper/FeedHelper method name renamed as handle_file_transfer
		if ( 1 === (int) $info['ftpenabled'] ) {
			if ( ! file_exists( $fileFrom ) ) {
				woo_feed_log_feed_process( $info['filename'], 'Unable to process file transfer request. File does not exists.' );

				return false;
			}
			$ftpHost          = sanitize_text_field( $info['ftphost'] );
			$ftp_user         = sanitize_text_field( $info['ftpuser'] );
			$ftp_password     = sanitize_text_field( $info['ftppassword'] );
			$ftpPath          = trailingslashit( untrailingslashit( sanitize_text_field( $info['ftppath'] ) ) );
			$ftp_passive_mode = ( isset( $info['ftpmode'] ) && sanitize_text_field( $info['ftpmode'] ) === 'passive' ) ? true : false;
			if ( isset( $info['ftporsftp'] ) & 'ftp' === $info['ftporsftp'] ) {
				$ftporsftp = 'ftp';
			} else {
				$ftporsftp = 'sftp';
			}
			if ( isset( $info['ftpport'] ) && ! empty( $info['ftpport'] ) ) {
				$ftp_port = absint( $info['ftpport'] );
			} else {
				$ftp_port = false;
			}

			if ( ! $ftp_port || ! ( ( 1 <= $ftp_port ) && ( $ftp_port <= 65535 ) ) ) {
				$ftp_port = 'sftp' === $ftporsftp ? 22 : 21;
			}

			woo_feed_log_feed_process( $info['filename'], sprintf( 'Uploading Feed file via %s.', $ftporsftp ) );

			try {
				if ( 'ftp' === $ftporsftp ) {

					$ftp = new WebAppick\FTP\FTPConnection();
					if ( $ftp->connect( $ftpHost, $ftp_user, $ftp_password, $ftp_passive_mode, $ftp_port ) ) {
						return $ftp->upload_file( $fileFrom, $ftpPath . $fileTo );
					}
				} elseif ( 'sftp' === $ftporsftp ) {

					$sftp = new WebAppick\FTP\SFTPConnection( $ftpHost, $ftp_port );
					$sftp->login( $ftp_user, $ftp_password );

					return $sftp->upload_file( $fileFrom, $fileTo, $ftpPath );

				}
			} catch ( Exception $e ) {
				$message = 'Error Uploading Feed Via ' . $ftporsftp . PHP_EOL . 'Caught Exception :: ' . $e->getMessage();
				woo_feed_log( $info['filename'], $message, 'critical', $e, true );
				woo_feed_log_fatal_error( $message, $e );

				return false;
			}
		}

		return false;
	}
}
if ( ! function_exists( 'woo_feed_get_file_types' ) ) {
	function woo_feed_get_file_types() {
		return array(
			'xml'  => 'XML',
			'csv'  => 'CSV',
			'tsv'  => 'TSV',
			'xls'  => 'XLS',
			'xlsx' => 'XLSX',
			'txt'  => 'TXT',
			'json' => 'JSON',
		);
	}
}
if ( ! function_exists( 'woo_feed_get_default_brand' ) ) {
	/**
	 * Guess Brand name from Site URL
	 *
	 * @return string
	 */
	function woo_feed_get_default_brand() {
		$brand = apply_filters( 'woo_feed_pre_get_default_brand_name', null );
		if ( ! is_null( $brand ) ) {
			return $brand;
		}
		$brand = '';
		$url   = filter_var( site_url(), FILTER_SANITIZE_URL );
		if ( false !== $url ) {
			$url = wp_parse_url( $url );
			if ( array_key_exists( 'host', $url ) ) {
				if ( strpos( $url['host'], '.' ) !== false ) {
					$arr   = explode( '.', $url['host'] );
					$brand = $arr[ count( $arr ) - 2 ];
					$brand = ucfirst( $brand );
				} else {
					$brand = $url['host'];
					$brand = ucfirst( $brand );
				}
			}
		}

		return apply_filters( 'woo_feed_get_default_brand_name', $brand );
	}
}
if ( ! function_exists( 'woo_feed_merchant_require_google_category' ) ) {
	/**
	 * Check if current merchant supports google taxonomy for current attribute.
	 *
	 * @param string $merchant
	 * @param string $attribute
	 *
	 * @return array|bool
	 */
	function woo_feed_merchant_require_google_category( $merchant = null, $attribute = null ) {
		$list = array(
			'current_category'        => array(
				'google',
				'google_shopping_action',
				'google_local',
				'google_local_inventory',
				'facebook',
				'tiktok',
				'snapchat',
				'adroll',
				'smartly.io',
				'pinterest',
				'rakuten.de',
			),
			'fb_product_category'     => array( 'facebook' ),
			'google_product_category' => array( 'rakuten.de', 'tiktok', 'snapchat' ),
			'google_category_id'      => array(
				'daisycon',
				'daisycon_automotive',
				'daisycon_books',
				'daisycon_cosmetics',
				'daisycon_daily_offers',
				'daisycon_electronics',
				'daisycon_food_drinks',
				'daisycon_home_garden',
				'daisycon_housing',
				'daisycon_fashion',
				'daisycon_studies_trainings',
				'daisycon_telecom_accessories',
				'daisycon_telecom_all_in_one',
				'daisycon_telecom_gsm_subscription',
				'daisycon_telecom_gsm',
				'daisycon_telecom_sim',
				'daisycon_magazines',
				'daisycon_holidays_accommodations',
				'daisycon_holidays_accommodations_and_transport',
				'daisycon_holidays_trips',
				'daisycon_work_jobs',
			),
		);
		if ( null !== $merchant && null !== $attribute ) {
			return ( isset( $list[ $attribute ] ) && in_array( $merchant, $list[ $attribute ], true ) );
		}

		return $list;
	}
}
if ( ! function_exists( 'woo_feed_get_item_wrapper_hidden_merchant' ) ) {
	function woo_feed_get_item_wrapper_hidden_merchant() {
		return apply_filters(
			'woo_feed_item_wrapper_hidden_merchant',
			array(
				'google',
				'google_shopping_action',
				'facebook',
				'pinterest',
				'fruugo.au',
				'stylight.com',
				'nextad',
				'skinflint.co.uk',
				'comparer.be',
				'dooyoo',
				'hintaseuranta.fi',
				'incurvy',
				'kijiji.ca',
				'marktplaats.nl',
				'rakuten.de',
				'shopalike.fr',
				'spartoo.fi',
				'webmarchand',
				'skroutz',
				'daisycon',
				'daisycon_automotive',
				'daisycon_books',
				'daisycon_cosmetics',
				'daisycon_daily_offers',
				'daisycon_electronics',
				'daisycon_food_drinks',
				'daisycon_home_garden',
				'daisycon_housing',
				'daisycon_fashion',
				'daisycon_studies_trainings',
				'daisycon_telecom_accessories',
				'daisycon_telecom_all_in_one',
				'daisycon_telecom_gsm_subscription',
				'daisycon_telecom_gsm',
				'daisycon_telecom_sim',
				'daisycon_magazines',
				'daisycon_holidays_accommodations',
				'daisycon_holidays_accommodations_and_transport',
				'daisycon_holidays_trips',
				'daisycon_work_jobs',
			)
		);
	}
}

// The Editor.
if ( ! function_exists( 'woo_feed_parse_feed_rules' ) ) {
	/**
	 * Parse Feed Config/Rules to make sure that necessary array keys are exists
	 * this will reduce the uses of isset() checking
	 *
	 * @param array $rules rules to parse.
	 * @param string $context parsing context. useful for filtering, view, save, db, create etc.
	 *
	 * @return array
	 * @since 3.3.5 $context parameter added.
	 *
	 * @uses wp_parse_args
	 *
	 */
	function woo_feed_parse_feed_rules( $rules = array(), $context = 'view' ) {

		if ( empty( $rules ) ) {
			$rules = array();
		}
		$defaults = array(
			'provider'            => '',
			'filename'            => '',
			'feedType'            => '',
			'feed_country'        => '',
			'ftpenabled'          => 0,
			'ftporsftp'           => 'ftp',
			'ftphost'             => '',
			'ftpport'             => '21',
			'ftpuser'             => '',
			'ftppassword'         => '',
			'ftppath'             => '',
			'ftpmode'             => 'active',
			'is_variations'       => 'y',
			'variable_price'      => 'first',
			'variable_quantity'   => 'first',
			'feedLanguage'        => apply_filters( 'wpml_current_language', null ),
			'feedCurrency'        => get_woocommerce_currency(),
			'itemsWrapper'        => 'products',
			'itemWrapper'         => 'product',
			'delimiter'           => ',',
			'enclosure'           => 'double',
			'extraHeader'         => '',
			'vendors'             => array(),
			// Feed Config
			'mattributes'         => array(), // merchant attributes
			'prefix'              => array(), // prefixes
			'type'                => array(), // value (attribute) types
			'attributes'          => array(), // product attribute mappings
			'default'             => array(), // default values (patterns) if value type set to pattern
			'suffix'              => array(), // suffixes
			'output_type'         => array(), // output type (output filter)
			'limit'               => array(), // limit or command
			// filters tab
			'composite_price'     => '',
			'shipping_country'    => '',
			'tax_country'         => '',
			'product_ids'         => '',
			'categories'          => array(),
			'post_status'         => array( 'publish' ),
			'filter_mode'         => array(),
			'campaign_parameters' => array(),

			'ptitle_show'         => '',
			'decimal_separator'   => wc_get_price_decimal_separator(),
			'thousand_separator'  => wc_get_price_thousand_separator(),
			'decimals'            => wc_get_price_decimals(),
		);
		$rules                = wp_parse_args( $rules, $defaults );
		$rules['filter_mode'] = wp_parse_args(
			$rules['filter_mode'],
			array(
				'product_ids' => 'include',
				'categories'  => 'include',
				'post_status' => 'include',
			)
		);

		$rules['campaign_parameters'] = wp_parse_args(
			$rules['campaign_parameters'],
			array(
				'utm_source'   => '',
				'utm_medium'   => '',
				'utm_campaign' => '',
				'utm_term'     => '',
				'utm_content'  => '',
			)
		);

		if ( ! empty( $rules['provider'] ) && is_string( $rules['provider'] ) ) {
			/**
			 * filter parsed rules for provider
			 *
			 * @param array $rules
			 * @param string $context
			 *
			 * @since 3.3.7
			 *
			 */
			$rules = apply_filters( "woo_feed_{$rules['provider']}_parsed_rules", $rules, $context );
		}

		/**
		 * filter parsed rules
		 *
		 * @param array $rules
		 * @param string $context
		 *
		 * @since 3.3.7 $provider parameter removed
		 *
		 */
		return apply_filters( 'woo_feed_parsed_rules', $rules, $context );
	}
}
if ( ! function_exists( 'woo_feed_register_and_do_woo_feed_meta_boxes' ) ) {
	/**
	 * Registers the default Feed Editor MetaBoxes, and runs the `do_meta_boxes` actions.
	 *
	 * @param string|WP_Screen $screen Screen identifier. If you have used add_menu_page() or
	 *                                      add_submenu_page() to create a new screen (and hence screen_id)
	 *                                      make sure your menu slug conforms to the limits of sanitize_key()
	 *                                      otherwise the 'screen' menu may not correctly render on your page.
	 * @param array $feedRules current feed being processed.
	 *
	 * @return void
	 * @see register_and_do_post_meta_boxes()
	 *
	 * @since 3.2.6
	 *
	 */
	function woo_feed_register_and_do_woo_feed_meta_boxes( $screen, $feedRules = array() ) {
		if ( empty( $screen ) ) {
			$screen = get_current_screen();
		} elseif ( is_string( $screen ) ) {
			$screen = convert_to_screen( $screen );
		}
		// edit page MetaBoxes
		if ( 'ctx-feed_page_webappick-new-feed' === $screen->id || 'toplevel_page_webappick-manage-feeds' === $screen->id ) {
			add_meta_box( 'feed_merchant_info', 'Feed Merchant Info', 'woo_feed_merchant_info_metabox', null, 'side', 'default' );
		}
		/**
		 * This action is documented in wp-admin/includes/meta-boxes.php
		 * using screen id instead of post type
		 */
		do_action( 'add_meta_boxes', $screen->id, $feedRules );
		do_action( "add_meta_boxes_{$screen->id}", $feedRules );
		do_action( 'do_meta_boxes', $screen->id, 'normal', $feedRules );
		do_action( 'do_meta_boxes', $screen->id, 'advanced', $feedRules );
		do_action( 'do_meta_boxes', $screen->id, 'side', $feedRules );
	}
}
if ( ! function_exists( 'woo_feed_ajax_merchant_info' ) ) {
	add_action( 'wp_ajax_woo_feed_get_merchant_info', 'woo_feed_ajax_merchant_info' );
	function woo_feed_ajax_merchant_info() {
		if ( isset( $_REQUEST['nonce'] ) && wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ),
			'wpf_feed_nonce'
		) ) {
			$provider     = ( isset( $_REQUEST['provider'] ) && ! empty( $_REQUEST['provider'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['provider'] ) ) : '';
			$merchantInfo = new Woo_Feed_Merchant( $provider );
			$data         = array();
			$na           = esc_html__( 'N/A', 'woo-feed' );
			foreach ( $merchantInfo->get_info() as $k => $v ) {
				if ( 'link' === $k ) {
					/** @noinspection HtmlUnknownTarget */
					$data[ $k ] = empty( $v ) ? $na : sprintf(
						'<a href="%s" target="_blank">%s</a>',
						esc_url( $v ),
						esc_html__( 'Read Article', 'woo-feed' )
					);
				} elseif ( 'video' === $k ) {
					/** @noinspection HtmlUnknownTarget */
					$data[ $k ] = empty( $v ) ? $na : sprintf(
						'<a href="%s" target="_blank">%s</a>',
						esc_url( $v ),
						esc_html__( 'Watch Now', 'woo-feed' )
					);
				} elseif ( 'feed_file_type' === $k ) {
					if ( ! empty( $v ) ) {
						$v          = array_map(
							function ( $type ) {
								return strtoupper( $type );
							},
							(array) $v
						);
						$data[ $k ] = esc_html( implode( ', ', $v ) );
					} else {
						$data[ $k ] = $na;
					}
				} elseif ( 'doc' === $k ) {
					$links = '';
					foreach ( $v as $label => $link ) {
						/** @noinspection HtmlUnknownTarget */
						$links .= sprintf(
							'<li><a href="%s" target="_blank">%s</a></li>',
							esc_url( $link ),
							esc_html( $label )
						);
					}
					$data[ $k ] = empty( $links ) ? $na : $links;
				}
			}
			wp_send_json_success( $data );
		} else {
			wp_send_json_error( esc_html__( 'Invalid Request.', 'woo-feed' ) );
		}
		die();
	}
}
if ( ! function_exists( 'woo_feed_merchant_info_metabox' ) ) {
	/**
	 * Render Merchant Info Metabox
	 *
	 * @param array $feedConfig
	 *
	 * @return void
	 */
	function woo_feed_merchant_info_metabox( $feedConfig ) {
		$provider     = ( isset( $feedConfig['provider'] ) && ! empty( $feedConfig['provider'] ) ) ? $feedConfig['provider'] : '';
		$merchantInfo = new Woo_Feed_Merchant( $provider );

		//get feed options
		if ( isset( $_GET['feed'] ) ) {
			$filename     = str_replace( 'wf_feed_', '', wp_unslash( $_GET['feed'] ) );
			$feed_options = maybe_unserialize( get_option( 'wf_feed_' . $filename ) );
		}
		?>
		<span class="spinner"></span>
		<div class="merchant-infos">
			<?php foreach ( $merchantInfo->get_info() as $k => $v ) { ?>
				<div class="merchant-info-section <?php echo esc_attr( $k ); ?>">
					<?php if ( 'link' === $k ) { ?>
						<span class="dashicons dashicons-media-document" style="color: #82878c;"
							  aria-hidden="true"></span>
						<span><?php esc_html_e( 'Feed Specification:', 'woo-feed' ); ?></span>
						<strong class="data">
							<?php
							/** @noinspection HtmlUnknownTarget */
							( empty( $v ) ) ? esc_html_e(
								'N/A',
								'woo-feed'
							) : printf(
								'<a href="%s" target="_blank">%s</a>',
								esc_url( $v ),
								esc_html__( 'Read Article', 'woo-feed' )
							);
							?>
						</strong>
					<?php } elseif ( 'video' === $k ) { ?>
						<span class="dashicons dashicons-video-alt3" style="color: #82878c;" aria-hidden="true"></span>
						<span><?php esc_html_e( 'Video Documentation:', 'woo-feed' ); ?></span>
						<strong class="data">
							<?php
							/** @noinspection HtmlUnknownTarget */
							( empty( $v ) ) ? esc_html_e(
								'N/A',
								'woo-feed'
							) : printf(
								'<a href="%s" target="_blank">%s</a>',
								esc_url( $v ),
								esc_html__( 'Watch now', 'woo-feed' )
							);
							?>
						</strong>
					<?php } elseif ( 'feed_file_type' === $k ) { ?>
						<span class="dashicons dashicons-media-text" style="color: #82878c;"
							  aria-hidden="true"></span> <?php esc_html_e( 'Supported File Types:', 'woo-feed' ); ?>
						<strong class="data" style="display: block;padding-left: 24px;margin-top: 5px;">
							<?php
							if ( empty( $v ) ) {
								esc_html_e( 'N/A', 'woo-feed' );
							} else {
								$v = implode(
									', ',
									array_map(
										function ( $type ) {
											return esc_html( strtoupper( $type ) );
										},
										(array) $v
									)
								);
								echo esc_html( $v );
							}
							?>
						</strong>
						<?php
					} elseif ( 'doc' === $k ) {
						?>
						<span class="dashicons dashicons-editor-help" style="color: #82878c;" aria-hidden="true"></span>
						<span><?php esc_html_e( 'Support Docs:', 'woo-feed' ); ?></span>
						<ul class="data">
							<?php
							if ( empty( $v ) ) {
								esc_html_e( 'N/A', 'woo-feed' );
							} else {
								foreach ( $v as $label => $link ) {
									/** @noinspection HtmlUnknownTarget */
									printf(
										'<li><a href="%s" target="_blank">%s</a></li>',
										esc_url( $link ),
										esc_html( $label )
									);
								}
							}
							?>
						</ul>
						<?php
					}
					?>
				</div>
			<?php } ?>
			<div class="merchant-info-section woo-feed-open-file">
				<?php
				if ( isset( $feed_options['url'] ) && ! empty( $feed_options['url'] ) ) {
					echo sprintf(
						'<a href="%1$s" title="%2$s" aria-label="%2$s" target="_blank"><span class="dashicons dashicons-external" aria-hidden="true"></span> %3$s</a>',
						$feed_options['url'],
						esc_html__( 'View', 'woo-feed' ),
						esc_html__( 'Open Feed File', 'woo-feed' )
					);
				}
				?>
			</div>
		</div>
		<?php
	}
}
if ( ! function_exists( 'woo_feed_get_csv_delimiters' ) ) {
	/**
	 * Get CSV/TXT/TSV Delimiters
	 * @return array
	 */
	function woo_feed_get_csv_delimiters() {
		return array(
			','  => 'Comma',
			':'  => 'Colon',
			' '  => 'Space',
			'|'  => 'Pipe',
			';'  => 'Semi Colon',
			"\t" => 'TAB',
		);
	}
}
if ( ! function_exists( 'woo_feed_get_csv_enclosure' ) ) {
	/**
	 * Get CSV/TXT/TSV Enclosure for multiple words
	 * @return array
	 */
	function woo_feed_get_csv_enclosure() {
		return array(
			'double' => '"',
			'single' => '\'',
			' '      => 'None',
		);
	}
}

// Editor Tabs.
if ( ! function_exists( 'render_feed_config' ) ) {
	/**
	 * @param string $tabId
	 * @param array $feedRules
	 * @param bool $idEdit
	 */
	function render_feed_config( $tabId, $feedRules, $idEdit ) {
		global $provider, $wooFeedDropDown, $merchant;
		include WOO_FEED_FREE_ADMIN_PATH . 'partials/woo-feed-edit-config.php';
	}
}
if ( ! function_exists( 'render_filter_config' ) ) {
	/**
	 * @param string $tabId
	 * @param array $feedRules
	 * @param bool $idEdit
	 */
	function render_filter_config( $tabId, $feedRules, $idEdit ) {
		global $provider, $wooFeedDropDown, $merchant;
		include WOO_FEED_FREE_ADMIN_PATH . 'partials/woo-feed-edit-filter.php';
	}
}
if ( ! function_exists( 'render_ftp_config' ) ) {
	/**
	 * @param string $tabId
	 * @param array $feedRules
	 * @param bool $idEdit
	 */
	function render_ftp_config( $tabId, $feedRules, $idEdit ) {
		global $provider, $wooFeedDropDown, $merchant;
		include WOO_FEED_FREE_ADMIN_PATH . 'partials/woo-feed-edit-ftp.php';
	}
}

// Sanitization.
if ( ! function_exists( 'woo_feed_check_google_category' ) ) {
	/**
	 * @param array $feedInfo
	 *
	 * @return string
	 */
	function woo_feed_check_google_category( $feedInfo ) {
		// Check Google Product Category for Google & Facebook Template and show message.
		$list              = woo_feed_merchant_require_google_category();
		$cat_keys          = array_keys( $list );
		$merchants         = call_user_func_array( 'array_merge', array_values( $list ) );
		$checkCategory     = isset( $feedInfo['feedrules']['mattributes'] ) ? $feedInfo['feedrules']['mattributes'] : array();
		$checkCategoryType = isset( $feedInfo['feedrules']['type'] ) ? $feedInfo['feedrules']['type'] : array();
		$merchant          = isset( $feedInfo['feedrules']['provider'] ) ? $feedInfo['feedrules']['provider'] : array();
		$cat               = 'yes';
		foreach ( $list as $attribute => $merchants ) {
			if ( in_array( $merchant, $merchants, true ) && in_array( $attribute, $checkCategory, true ) ) {
				$catKey = array_search( $attribute, $checkCategory, true );
				if ( 'pattern' === $checkCategoryType[ $catKey ] ) {
					$checkCategoryValue = $feedInfo['feedrules']['default'];
				} else {
					$checkCategoryValue = $feedInfo['feedrules']['attributes'];
				}

				if ( empty( $checkCategoryValue[ $catKey ] ) ) {
					$cat = 'no';
				}
				break;
			}
		}

		return $cat;
	}
}
if ( ! function_exists( 'woo_feed_array_sanitize' ) ) {
	/**
	 * Sanitize array post
	 *
	 * @param array $array
	 *
	 * @return array
	 */
	function woo_feed_array_sanitize( $array ) {
		$newArray = array();
		if ( count( $array ) ) {
			foreach ( $array as $key => $value ) {
				if ( is_array( $value ) ) {
					foreach ( $value as $key2 => $value2 ) {
						if ( is_array( $value2 ) ) {
							foreach ( $value2 as $key3 => $value3 ) {
								$newArray[ $key ][ $key2 ][ $key3 ] = sanitize_text_field( $value3 );
							}
						} else {
							$newArray[ $key ][ $key2 ] = sanitize_text_field( $value2 );
						}
					}
				} else {
					$newArray[ $key ] = sanitize_text_field( $value );
				}
			}
		}

		return $newArray;
	}
}
if ( ! function_exists( 'woo_feed_sanitize_form_fields' ) ) {
	/**
	 * Sanitize Form Fields ($_POST Array)
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	function woo_feed_sanitize_form_fields( $data ) {
		foreach ( $data as $k => $v ) {
			if ( true === apply_filters( 'woo_feed_sanitize_form_fields', true, $k, $v, $data ) ) {
				if ( is_array( $v ) ) {
					$v = woo_feed_sanitize_form_fields( $v );
				} else {
					// $v = sanitize_text_field( $v ); #TODO should not trim Prefix and Suffix field
				}
			}
			$data[ $k ] = apply_filters( 'woo_feed_sanitize_form_field', $v, $k );
		}

		return $data;
	}
}
if ( ! function_exists( 'woo_feed_unique_feed_slug' ) ) {
	/**
	 * Generate Unique slug for feed.
	 * This function only check database for existing feed for generating unique slug.
	 * Use generate_unique_feed_file_name() for complete unique slug name.
	 *
	 * @param string $slug slug for checking uniqueness.
	 * @param string $prefix prefix to check with. Optional.
	 * @param int $option_id option id. Optional option id to exclude specific option.
	 *
	 * @return string
	 * @see wp_unique_post_slug()
	 *
	 */
	function woo_feed_unique_feed_slug( $slug, $prefix = '', $option_id = null ) {
		global $wpdb;
		/** @noinspection SpellCheckingInspection */
		$disallowed = array( 'siteurl', 'home', 'blogname', 'blogdescription', 'users_can_register', 'admin_email' );
		if ( $option_id && $option_id > 0 ) {
			$checkSql  = "SELECT option_name FROM $wpdb->options WHERE option_name = %s AND option_id != %d LIMIT 1";
			$nameCheck = $wpdb->get_var( $wpdb->prepare( $checkSql, $prefix . $slug, $option_id ) ); // phpcs:ignore
		} else {
			$checkSql  = "SELECT option_name FROM $wpdb->options WHERE option_name = %s LIMIT 1";
			$nameCheck = $wpdb->get_var( $wpdb->prepare( $checkSql, $prefix . $slug ) ); // phpcs:ignore
		}
		// slug found or slug in disallowed list
		if ( $nameCheck || in_array( $slug, $disallowed, true ) ) {
			$suffix = 2;
			do {
				$altName = _truncate_post_slug( $slug, 200 - ( strlen( $suffix ) + 1 ) ) . "-$suffix";
				if ( $option_id && $option_id > 0 ) {
					$nameCheck = $wpdb->get_var( $wpdb->prepare( $checkSql, $prefix . $altName, $option_id ) ); // phpcs:ignore
				} else {
					$nameCheck = $wpdb->get_var( $wpdb->prepare( $checkSql, $prefix . $altName ) ); // phpcs:ignore
				}
				$suffix ++;
			} while ( $nameCheck );
			$slug = $altName;
		}

		return $slug;
	}
}
if ( ! function_exists( 'woo_feed_unique_option_name' ) ) {
	/**
	 * Alias of woo_feed_unique_feed_slug
	 *
	 * @param string $slug
	 * @param string $prefix
	 * @param null $option_id
	 *
	 * @return string
	 * @see woo_feed_unique_feed_slug
	 *
	 * @since 3.3.8
	 *
	 */
	function woo_feed_unique_option_name( $slug, $prefix = '', $option_id = null ) {
		return woo_feed_unique_feed_slug( $slug, $prefix, $option_id );
	}
}
if ( ! function_exists( 'generate_unique_feed_file_name' ) ) {
	/**
	 * Generate Unique file Name.
	 * This will insure unique slug and file name for a single feed.
	 *
	 * @param string $filename
	 * @param string $type
	 * @param string $provider
	 *
	 * @return string|string[]
	 */
	function generate_unique_feed_file_name( $filename, $type, $provider ) {

		$feedDir      = woo_feed_get_file_dir( $provider, $type );
		$raw_filename = sanitize_title( $filename, '', 'save' );
		// check option name uniqueness ...
		$raw_filename = woo_feed_unique_feed_slug( $raw_filename, 'wf_feed_' );
		$raw_filename = sanitize_file_name( $raw_filename . '.' . $type );
		$raw_filename = wp_unique_filename( $feedDir, $raw_filename );
		$raw_filename = str_replace( '.' . $type, '', $raw_filename );

		return - 1 !== (int) $raw_filename ? $raw_filename : false;
	}
}

// File process.
if ( ! function_exists( 'woo_feed_check_valid_extension' ) ) {
	/**
	 * Check Feed File Extension Validity
	 *
	 * @param string $extension Ext to check.
	 *
	 * @return bool
	 */
	function woo_feed_check_valid_extension( $extension ) {
		return in_array( $extension, array_keys( woo_feed_get_file_types() ), true );
	}
}
if ( ! function_exists( 'woo_feed_save_feed_config_data' ) ) {
	/**
	 * Sanitize And Save Feed config data (array) to db (option table)
	 *
	 * @param array $data data to be saved in db
	 * @param null $feed_option_name feed (file) name. optional, if empty or null name will be auto generated
	 * @param bool $configOnly save only wf_config or both wf_config and wf_feed_. default is only wf_config
	 *
	 * @return bool|string          return false if failed to update. return filename if success
	 */
	function woo_feed_save_feed_config_data( $data, $feed_option_name = null, $configOnly = true ) {
		if ( ! is_array( $data ) ) {
			return false;
		}
		if ( ! isset( $data['filename'], $data['feedType'], $data['provider'] ) ) {
			return false;
		}
		// unnecessary form fields to remove
		$removables = array( 'closedpostboxesnonce', '_wpnonce', '_wp_http_referer', 'save_feed_config', 'edit-feed' );
		foreach ( $removables as $removable ) {
			if ( isset( $data[ $removable ] ) ) {
				unset( $data[ $removable ] );
			}
		}
		// parse rules
		$data = woo_feed_parse_feed_rules( $data );
		// Sanitize Fields
		$data = woo_feed_sanitize_form_fields( $data );
		if ( empty( $feed_option_name ) ) {
			$feed_option_name = generate_unique_feed_file_name(
				$data['filename'],
				$data['feedType'],
				$data['provider']
			);
		} else {
			$feed_option_name = woo_feed_extract_feed_option_name( $feed_option_name );
		}

		// get old config
		$old_data = get_option( 'wf_config' . $feed_option_name, array() );
		$update   = false;
		$updated  = false;
		if ( is_array( $old_data ) && ! empty( $old_data ) ) {
			$update = true;
		}

		/**
		 * Filters feed data just before it is inserted into the database.
		 *
		 * @param array $data An array of sanitized config
		 * @param array $old_data An array of old feed data
		 * @param string $feed_option_name Option name
		 *
		 * @since 3.3.3
		 *
		 */
		$data = apply_filters( 'woo_feed_insert_feed_data', $data, $old_data, 'wf_config' . $feed_option_name );

		if ( $update ) {
			/**
			 * Before Updating Config to db
			 *
			 * @param array $data An array of sanitized config
			 * @param string $feed_option_name Option name
			 */
			do_action( 'woo_feed_before_update_config', $data, 'wf_config' . $feed_option_name );
		} else {
			/**
			 * Before inserting Config to db
			 *
			 * @param array $data An array of sanitized config
			 * @param string $feed_option_name Option name
			 */
			do_action( 'woo_feed_before_insert_config', $data, 'wf_config' . $feed_option_name );
		}
		$updated = ( $data === $old_data );
		if ( false === $updated ) {
			// Store Config.
			$updated = update_option( 'wf_config' . $feed_option_name, $data, false );
		}
		// update wf_feed if wp_config update ok...
		if ( $updated && false === $configOnly ) {
			$old_feed  = maybe_unserialize( get_option( 'wf_feed_' . $feed_option_name ) );
			$feed_data = array(
				'feedrules'    => $data,
				'url'          => woo_feed_get_file_url( $feed_option_name, $data['provider'], $data['feedType'] ),
				'last_updated' => date( 'Y-m-d H:i:s', strtotime( current_time( 'mysql' ) ) ),
				'status'       => isset( $old_feed['status'] ) && 1 === (int) $old_feed['status'] ? 1 : 0,
				// set old status or disable auto update.
			);

			$saved2 = update_option( 'wf_feed_' . $feed_option_name, maybe_serialize( $feed_data ), false );
		}

		if ( $update ) {
			/**
			 * After Updating Config to db
			 *
			 * @param array $data An array of sanitized config
			 * @param string $feed_option_name Option name
			 */
			do_action( 'woo_feed_after_update_config', $data, 'wf_config' . $feed_option_name );
		} else {
			/**
			 * After inserting Config to db
			 *
			 * @param array $data An array of sanitized config
			 * @param string $feed_option_name Option name
			 */
			do_action( 'woo_feed_after_insert_config', $data, 'wf_config' . $feed_option_name );
		}

		// return filename on success or update status
		return $updated ? $feed_option_name : $updated;
	}
}
if ( ! function_exists( 'woo_feed_extract_feed_option_name' ) ) {
	/**
	 * Remove Feed Option Name Prefix and return the slug
	 *
	 * @param string $feed_option_name
	 *
	 * @return string
	 */
	function woo_feed_extract_feed_option_name( $feed_option_name ) {
		return str_replace( array( 'wf_feed_', 'wf_config' ), '', $feed_option_name );
	}
}
if ( ! function_exists( 'woo_feed_get_file_path' ) ) {
	/**
	 * Get File Path for feed or the file upload path for the plugin to use.
	 *
	 * @param string $provider provider name.
	 * @param string $type feed file type.
	 *
	 * @return string
	 */
	function woo_feed_get_file_path( $provider = '', $type = '' ) {
		$upload_dir = wp_get_upload_dir();

		return sprintf( '%s/woo-feed/%s/%s/', $upload_dir['basedir'], $provider, $type );
	}
}
if ( ! function_exists( 'woo_feed_get_file' ) ) {
	/**
	 * Get Feed File URL
	 *
	 * @param string $fileName
	 * @param string $provider
	 * @param string $type
	 *
	 * @return string
	 */
	function woo_feed_get_file( $fileName, $provider, $type ) {
		$fileName = woo_feed_extract_feed_option_name( $fileName );
		$path     = woo_feed_get_file_path( $provider, $type );

		return sprintf( '%s/%s.%s', untrailingslashit( $path ), $fileName, $type );
	}
}
if ( ! function_exists( 'woo_feed_get_file_url' ) ) {
	/**
	 * Get Feed File URL
	 *
	 * @param string $fileName
	 * @param string $provider
	 * @param string $type
	 *
	 * @return string
	 */
	function woo_feed_get_file_url( $fileName, $provider, $type ) {
		$fileName   = woo_feed_extract_feed_option_name( $fileName );
		$upload_dir = wp_get_upload_dir();

		return esc_url(
			sprintf(
				'%s/woo-feed/%s/%s/%s.%s',
				$upload_dir['baseurl'],
				$provider,
				$type,
				$fileName,
				$type
			)
		);
	}
}
if ( ! function_exists( 'woo_feed_check_feed_file' ) ) {
	/**
	 * Check if feed file exists
	 *
	 * @param string $fileName
	 * @param string $provider
	 * @param string $type
	 *
	 * @return bool
	 */
	function woo_feed_check_feed_file( $fileName, $provider, $type ) {
		$upload_dir = wp_get_upload_dir();

		return file_exists(
			sprintf(
				'%s/woo-feed/%s/%s/%s.%s',
				$upload_dir['basedir'],
				$provider,
				$type,
				$fileName,
				$type
			)
		);
	}
}
if ( ! function_exists( 'woo_feed_get_file_dir' ) ) {
	/**
	 * Get Feed Directory
	 *
	 * @param string $provider
	 * @param string $feedType
	 *
	 * @return string
	 */
	function woo_feed_get_file_dir( $provider, $feedType ) {
		$upload_dir = wp_get_upload_dir();

		return sprintf( '%s/woo-feed/%s/%s', $upload_dir['basedir'], $provider, $feedType );
	}
}
if ( ! function_exists( 'woo_feed_save_batch_feed_info' ) ) {
	/**
	 * Save Feed Batch Chunk
	 *
	 * @param string $feedService merchant.
	 * @param string $type file type (ext).
	 * @param string|array $string data.
	 * @param string $fileName file name.
	 * @param array $info feed config.
	 *
	 * @return bool
	 */
	function woo_feed_save_batch_feed_info( $feedService, $type, $string, $fileName, $info ) {
		$ext = $type;
		if ( 'csv' === $type || 'tsv' === $type || 'xls' === $type || 'xlsx' === $type || 'json' === $type ) {
			$string = wp_json_encode( $string );
			$ext    = 'json';
		}
		// Save File.
		$path   = woo_feed_get_file_dir( $feedService, $type );
		$file   = $path . '/' . $fileName . '.' . $ext;
		$save   = new Woo_Feed_Savefile();
		$status = $save->saveFile( $path, $file, $string );
		if ( woo_feed_is_debugging_enabled() ) {
			if ( $status ) {
				$message = sprintf( 'Batch chunk file (%s) saved.', $fileName );
			} else {
				$message = sprintf( 'Unable to save batch chunk file %s.', $fileName );
			}
			woo_feed_log_feed_process( $info['filename'], $message );
		}

		return $status;
	}
}
if ( ! function_exists( 'woo_feed_get_batch_feed_info' ) ) {
	/**
	 * @param string $feedService
	 * @param string $type
	 * @param string $fileName
	 *
	 * @return bool|array|string
	 */
	function woo_feed_get_batch_feed_info( $feedService, $type, $fileName ) {
		$ext = $type;
		if ( 'csv' === $type || 'tsv' === $type || 'xls' === $type || 'xlsx' === $type || 'json' === $type ) {
			$ext = 'json';
		}
		// Save File
		$path = woo_feed_get_file_dir( $feedService, $type );
		$file = $path . '/' . $fileName . '.' . $ext;
		if ( ! file_exists( $file ) ) {
			return false;
		}

		$data = file_get_contents( $file ); // phpcs:ignore

		if ( 'csv' === $type || 'tsv' === $type || 'xls' === $type || 'xlsx' === $type || 'json' === $type ) {
			$data = ( $data ) ? json_decode( $data, true ) : false;
		}

		return $data;
	}
}
if ( ! function_exists( 'woo_feed_unlink_tempFiles' ) ) {
	/**
	 * Remove temporary feed files
	 *
	 * @param array $config Feed config
	 * @param string $fileName feed file name.
	 *
	 * @return void
	 */
	function woo_feed_unlink_tempFiles( $config, $fileName ) {
		$type = $config['feedType'];
		$ext  = $type;
		$path = woo_feed_get_file_dir( $config['provider'], $type );

		if ( 'csv' === $type || 'tsv' === $type || 'xls' === $type || 'xlsx' === $type ) {
			$ext = 'json';
		}
		$files = array(
			'headerFile' => $path . '/' . 'wf_store_feed_header_info_' . $fileName . '.' . $ext,
			'bodyFile'   => $path . '/' . 'wf_store_feed_body_info_' . $fileName . '.' . $ext,
			'footerFile' => $path . '/' . 'wf_store_feed_footer_info_' . $fileName . '.' . $ext,
		);

		woo_feed_log_feed_process( $config['filename'], sprintf( 'Deleting Temporary Files (%s).', implode( ', ', array_values( $files ) ) ) );
		foreach ( $files as $key => $file ) {
			if ( file_exists( $file ) ) {
				unlink( $file ); // phpcs:ignore
			}
		}
	}
}
if ( ! function_exists( 'woo_feed_delete_feed' ) ) {
	/**
	 * Delete feed option and the file from uploads directory
	 *
	 * @param string|int $feed_id feed option name or ID.
	 *
	 * @return bool
	 */
	function woo_feed_delete_feed( $feed_id ) {
		global $wpdb;
		if ( ! is_numeric( $feed_id ) ) {
			$feed_name = woo_feed_extract_feed_option_name( $feed_id );
		} else {
			$feed_data   = $wpdb->get_row( $wpdb->prepare( "SELECT option_name FROM $wpdb->options WHERE option_id = %d", $feed_id ) ); // phpcs:ignore
			$option_name = $feed_data->option_name;
			$feed_name   = woo_feed_extract_feed_option_name( $feed_data->option_name );
		}
		$feedInfo = maybe_unserialize( get_option( 'wf_feed_' . $feed_name ) );
		if ( false !== $feedInfo ) {
			$feedInfo = $feedInfo['feedrules'];
		} else {
			$feedInfo = maybe_unserialize( get_option( 'wf_config' . $feed_name ) );
		}
		$deleted = false;
		$file    = woo_feed_get_file( $feed_name, $feedInfo['provider'], $feedInfo['feedType'] );
		// delete any leftover
		woo_feed_unlink_tempFiles( $feedInfo, $feed_name );
		if ( file_exists( $file ) ) {
			// file exists in upload directory
			if ( unlink( $file ) ) { // phpcs:ignore
				delete_option( 'wf_feed_' . $feed_name );
				delete_option( 'wf_config' . $feed_name );
				$deleted = true;
			}
		} else {
			delete_option( 'wf_feed_' . $feed_name );
			delete_option( 'wf_config' . $feed_name );
			$deleted = true;
		}

		// Delete cron schedule.
		$feed_cron_param = 'wf_config' . $feed_name;
		wp_clear_scheduled_hook( 'woo_feed_update_single_feed', array( $feed_cron_param ) );

		return $deleted;
	}
}

// Mics..
if ( ! function_exists( 'woo_feed_remove_query_args' ) ) {
	/**
	 * Add more items to the removable query args array...
	 *
	 * @param array $removable_query_args
	 *
	 * @return array
	 */
	function woo_feed_remove_query_args( $removable_query_args ) {
		global $plugin_page;

		if ( isset( $plugin_page ) && strpos( $plugin_page, 'webappick' ) !== false ) {
			$removable_query_args[] = 'feed_created';
			$removable_query_args[] = 'feed_updated';
			$removable_query_args[] = 'feed_imported';
			$removable_query_args[] = 'feed_regenerate';
			$removable_query_args[] = 'feed_name';
			$removable_query_args[] = 'link';
			$removable_query_args[] = 'wpf_message';
			$removable_query_args[] = 'cat';
			$removable_query_args[] = 'schedule_updated';
			$removable_query_args[] = 'settings_updated';
			/** @noinspection SpellCheckingInspection */
			$removable_query_args[] = 'WPFP_WPML_CURLANG';
		}

		return $removable_query_args;
	}

	add_filter( 'removable_query_args', 'woo_feed_remove_query_args', 10, 1 );
}
if ( ! function_exists( 'woo_feed_usort_reorder' ) ) {
	/**
	 * This checks for sorting input and sorts the data in our array accordingly.
	 *
	 * In a real-world situation involving a database, you would probably want
	 * to handle sorting by passing the 'orderby' and 'order' values directly
	 * to a custom query. The returned data will be pre-sorted, and this array
	 * sorting technique would be unnecessary.
	 *
	 * @param array $a first data.
	 *
	 * @param array $b second data.
	 *
	 * @return bool
	 */
	function woo_feed_usort_reorder( $a, $b ) {
		// If no sort, default to title
		$orderby = ( ! empty( $_REQUEST['orderby'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) : 'option_name'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		// If no order, default to asc
		$order  = ( ! empty( $_REQUEST['order'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) : 'asc'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$result = strcmp( $a[ $orderby ], $b[ $orderby ] ); // Determine sort order

		return ( 'asc' === $order ) ? $result : - $result; // Send final sort direction to usort
	}
}
if ( ! function_exists( 'str_replace_trim' ) ) {
	/**
	 * str_replace() wrapper with trim()
	 *
	 * @param mixed $search The value being searched for, otherwise known as the needle.
	 *                          An array may be used to designate multiple needles.
	 * @param mixed $replace The replacement value that replaces found search values.
	 *                          An array may be used to designate multiple replacements.
	 * @param mixed $subject The string or array being searched and replaced on,
	 *                          otherwise known as the haystack.
	 * @param string $charlist [optional]
	 *                          Optionally, the stripped characters can also be specified using the charlist parameter.
	 *                          Simply list all characters that you want to be stripped.
	 *                          With this you can specify a range of characters.
	 *
	 * @return array|string
	 */
	function str_replace_trim( $search, $replace, $subject, $charlist = " \t\n\r\0\x0B" ) {
		$replaced = str_replace( $search, $replace, $subject );
		if ( is_array( $replaced ) ) {
			return array_map(
				function ( $item ) use ( $charlist ) {
					return trim( $item, $charlist );
				},
				$replaced
			);
		} else {
			return trim( $replaced, $charlist );
		}
	}
}

if ( ! function_exists( 'woo_feed_strip_all_tags' ) ) {

	/*
	 * Extends wp_strip_all_tags to fix WP_Error object passing issue
	 *
	 * @param string | WP_Error $string
	 *
	 * @return string
	 * @since 4.4.19

	 * Function move to V5 module (V5/Helper/CommonHelper)

	 * */
	function woo_feed_strip_all_tags( $string ) {

		if ( $string instanceof WP_Error ) {
			return '';
		}

		return wp_strip_all_tags( $string );

	}
}


// Feed Functions.
if ( ! function_exists( 'woo_feed_generate_feed' ) ) {
	/**
	 * Update Feed Information
	 *
	 * @param array $info feed config array
	 * @param string $feed_option_name feed option/file name
	 *
	 * @return string|bool
	 */
	function woo_feed_generate_feed( $info, $feed_option_name ) {
		if ( false === $info || empty( $info ) ) {
			return false;
		}
		// parse rules.
		$info             = woo_feed_parse_feed_rules( isset( $info['feedrules'] ) ? $info['feedrules'] : $info );
		$feed_option_name = woo_feed_extract_feed_option_name( $feed_option_name );
		if ( ! empty( $info['provider'] ) ) {
			do_action( 'before_woo_feed_generate_feed', $info );

			// Generate Feed Data
			if ( 'googlereview' === $info['provider'] ) {
				$reviewObj = new Woo_Feed_Review( $info );
				$feedBody  = $reviewObj->make_review_xml_feed();
				$string    = $feedBody;

			} else {
				$products  = new Woo_Generate_Feed( $info['provider'], $info );
				$getString = $products->getProducts();
				if ( 'csv' === $info['feedType'] || 'tsv' === $info['feedType'] || 'xls' === $info['feedType'] || 'xlsx' === $info['feedType'] ) {
					$csvHead[0] = $getString['header'];
					if ( ! empty( $csvHead ) && ! empty( $getString['body'] ) ) {
						$string = array_merge( $csvHead, $getString['body'] );
					} else {
						$string = array();
					}
				} else {
					if ( 'json' === $info['feedType'] ) {
						$string = array();
					} else {
						$string = $getString['header'] . $getString['body'] . $getString['footer'];
					}
				}
			}

			$saveFile = false;
			// Check If any products founds
			if ( $string && ! empty( $string ) ) {
				// Save File
				$path = woo_feed_get_file_path( $info['provider'], $info['feedType'] );
				$file = woo_feed_get_file( $feed_option_name, $info['provider'], $info['feedType'] );
				$save = new Woo_Feed_Savefile();
				if ( 'csv' == $info['feedType'] || 'tsv' == $info['feedType'] || 'xls' == $info['feedType'] || 'json' == $info['feedType'] || 'xlsx' == $info['feedType'] ) {
					$saveFile = $save->saveValueFile( $path, $file, $string, $info, $info['feedType'] );
				} else {
					$saveFile = $save->saveFile( $path, $file, $string );
				}

				// Upload file to ftp server
				if ( 1 == (int) $info['ftpenabled'] ) {
					woo_feed_handle_file_transfer( $file, $feed_option_name . '.' . $info['feedType'], $info );
				}
			}
			$feed_URL = woo_feed_get_file_url( $feed_option_name, $info['provider'], $info['feedType'] );
			// Save Info into database
			$feedInfo = array(
				'feedrules'    => $info,
				'url'          => $feed_URL,
				'last_updated' => date( 'Y-m-d H:i:s', strtotime( current_time( 'mysql' ) ) ),
				'status'       => 1,
			);
			update_option( 'wf_feed_' . $feed_option_name, serialize( $feedInfo ), false ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
			do_action( 'after_woo_feed_generate_feed', $info );
			if ( $saveFile ) {
				return $feed_URL;
			} else {
				return false;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'woo_feed_get_schedule_interval_options' ) ) {
	/**
	 * Get Schedule Intervals
	 * @return mixed
	 */
	function woo_feed_get_schedule_interval_options() {
		return apply_filters(
			'woo_feed_schedule_interval_options',
			array(
				WEEK_IN_SECONDS      => esc_html__( '1 Week', 'woo-feed' ),
				DAY_IN_SECONDS       => esc_html__( '24 Hours', 'woo-feed' ),
				12 * HOUR_IN_SECONDS => esc_html__( '12 Hours', 'woo-feed' ),
				6 * HOUR_IN_SECONDS  => esc_html__( '6 Hours', 'woo-feed' ),
				HOUR_IN_SECONDS      => esc_html__( '1 Hours', 'woo-feed' ),
			)
		);
	}
}
if ( ! function_exists( 'woo_feed_get_minimum_interval_option' ) ) {
	function woo_feed_get_minimum_interval_option() {
		$intervals = array_keys( woo_feed_get_schedule_interval_options() );
		if ( ! empty( $intervals ) ) {
			return end( $intervals );
		}

		return 15 * MINUTE_IN_SECONDS;
	}
}
if ( ! function_exists( 'woo_feed_stripInvalidXml' ) ) {
	/**
	 * Remove non supported xml character
	 *
	 * @param string $value
	 *
	 * @return string
	 *
	 * Move to V5 module (V5/Helper/CommonHelper)
	 */
	function woo_feed_stripInvalidXml( $value ) {
		$ret = '';
		if ( empty( $value ) ) {
			return $ret;
		}
		$length = strlen( $value );
		for ( $i = 0; $i < $length; $i ++ ) {
			$current = ord( $value[ $i ] );
			if ( ( 0x9 == $current ) || ( 0xA == $current ) || ( 0xD == $current ) || ( ( $current >= 0x20 ) && ( $current <= 0xD7FF ) ) || ( ( $current >= 0xE000 ) && ( $current <= 0xFFFD ) ) || ( ( $current >= 0x10000 ) && ( $current <= 0x10FFFF ) ) ) {
				$ret .= chr( $current );
			} else {
				$ret .= '';
			}
		}

		return $ret;
	}
}
if ( ! function_exists( 'woo_feed_get_formatted_url' ) ) {
	/**
	 * Get Formatted URL
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	function woo_feed_get_formatted_url( $url = '' ) {
		if ( ! empty( $url ) ) {
			if ( substr( trim( $url ), 0, 4 ) === 'http' || substr(
				trim( $url ),
				0,
				3
			) === 'ftp' || substr( trim( $url ), 0, 4 ) === 'sftp' ) {
				return rtrim( $url, '/' );
			} else {
				$base = get_site_url();
				$url  = $base . $url;

				return rtrim( $url, '/' );
			}
		}

		return '';
	}
}
if ( ! function_exists( 'array_value_first' ) ) {
	/**
	 * Get First Value of an array
	 *
	 * @param array $arr
	 *
	 * @return mixed|null
	 * @since 3.0.0
	 */
	function array_value_first( array $arr ) {
		foreach ( $arr as $key => $unused ) {
			return $unused;
		}

		return null;
	}
}
if ( ! function_exists( 'woo_feed_make_url_with_parameter' ) ) {
	/**
	 * Make proper URL using parameters
	 *
	 * @param string $output
	 * @param string $suffix
	 *
	 * @return string
	 */
	function woo_feed_make_url_with_parameter( $output = '', $suffix = '' ) {
		if ( empty( $output ) || empty( $suffix ) ) {
			return $output;
		}

		$getParam = explode( '?', $output );
		$URLParam = array();
		if ( isset( $getParam[1] ) ) {
			$URLParam = woo_feed_parse_string( $getParam[1] );
		}

		$EXTRAParam = array();
		if ( ! empty( $suffix ) ) {
			$suffix     = str_replace( '?', '', $suffix );
			$EXTRAParam = woo_feed_parse_string( $suffix );
		}

		$params = array_merge( $URLParam, $EXTRAParam );
		if ( ! empty( $params ) && '' != $output ) {
			$params  = http_build_query( $params );
			$baseURL = isset( $getParam ) ? $getParam[0] : $output;
			$output  = $baseURL . '?' . $params;
		}

		return $output;
	}
}
if ( ! function_exists( 'woo_feed_parse_string' ) ) {
	/**
	 * Parse URL parameter
	 *
	 * @param string $str
	 *
	 * @return array
	 */
	function woo_feed_parse_string( $str = '' ) {

		// result array
		$arr = array();

		if ( empty( $str ) ) {
			return $arr;
		}

		// split on outer delimiter
		$pairs = explode( '&', $str );

		if ( ! empty( $pairs ) && is_array( $pairs ) ) {

			// loop through each pair
			foreach ( $pairs as $i ) {
				// split into name and value
				list( $name, $value ) = explode( '=', $i, 2 );

				// if name already exists
				if ( isset( $arr[ $name ] ) ) {
					// stick multiple values into an array
					if ( is_array( $arr[ $name ] ) ) {
						$arr[ $name ][] = $value;
					} else {
						$arr[ $name ] = array( $arr[ $name ], $value );
					}
				} // otherwise, simply stick it in a scalar
				else {
					$arr[ $name ] = $value;
				}
			}
		} elseif ( ! empty( $str ) ) {
			list( $name, $value ) = explode( '=', $str, 2 );
			$arr[ $name ]         = $value;
		}

		// return result array
		return $arr;
	}
}
if ( ! function_exists( 'woo_feed_replace_to_merchant_attribute' ) ) {
	/**
	 * Parse URL parameter
	 *
	 * @param string $pluginAttribute
	 * @param string $merchant
	 * @param string feedType CSV XML TXT
	 *
	 * @return string
	 */
	function woo_feed_replace_to_merchant_attribute( $pluginAttribute, $merchant, $feedType ) {
		$attributeClass     = new Woo_Feed_Default_Attributes();
		$merchantAttributes = '';
		if ( 'google' === $merchant
			 || 'google_shopping_action' === $merchant
			 || 'google_local' === $merchant
			 || 'google_local_inventory' === $merchant
			 || 'adroll' == $merchant
			 || 'smartly.io' == $merchant ) {
			if ( 'xml' === $feedType ) {
				$g_attributes = $attributeClass->googleXMLAttribute;
				if ( 'google_local' === $merchant ) {
					unset( $g_attributes['description'] );
				}
				$merchantAttributes = $g_attributes;
			} elseif ( 'csv' == $feedType || 'txt' == $feedType ) {
				$merchantAttributes = $attributeClass->googleCSVTXTAttribute;
			}
		} elseif ( 'facebook' == $merchant ) {
			if ( 'xml' == $feedType ) {
				$merchantAttributes = $attributeClass->facebookXMLAttribute;
			} elseif ( 'csv' == $feedType || 'txt' == $feedType ) {
				$merchantAttributes = $attributeClass->facebookCSVTXTAttribute;
			}
		} elseif ( 'pinterest' == $merchant ) {
			if ( 'xml' == $feedType ) {
				$merchantAttributes = $attributeClass->pinterestXMLAttribute;
			} elseif ( 'csv' == $feedType || 'txt' == $feedType ) {
				$merchantAttributes = $attributeClass->pinterestCSVTXTAttribute;
			}
		} elseif ( 'skroutz' == $merchant ) {
			if ( 'xml' == $feedType ) {
				$merchantAttributes = $attributeClass->skroutzXMLAttributes;
			}
		}

		if ( ! empty( $merchantAttributes ) && array_key_exists( $pluginAttribute, $merchantAttributes ) ) {
			return $merchantAttributes[ $pluginAttribute ][0];
		}

		return $pluginAttribute;
	}
}
if ( ! function_exists( 'woo_feed_add_cdata' ) ) {
	/**
	 * Parse URL parameter
	 *
	 * @param string $pluginAttribute
	 * @param string $attributeValue
	 * @param string $merchant
	 * @param string $feed_type
	 *
	 * @return string
	 */
	function woo_feed_add_cdata( $pluginAttribute, $attributeValue, $merchant, $feed_type ) {
		if ( 'xml' !== $feed_type ) {
			return "$attributeValue";
		}

		if ( 'custom' === $merchant ) {
			return "$attributeValue";
		}

		if ( 'shipping' === $pluginAttribute || 'tax' === $pluginAttribute ) {
			return "$attributeValue";
		}

		if ( strpos( $attributeValue, '<![CDATA[' ) !== false ) {
			return "$attributeValue";
		}

		$attributeClass     = new Woo_Feed_Default_Attributes();
		$merchantAttributes = '';
		if ( 'google' == $merchant ) {
			$merchantAttributes = $attributeClass->googleXMLAttribute;
		} elseif ( 'facebook' == $merchant ) {
			$merchantAttributes = $attributeClass->facebookXMLAttribute;
		} elseif ( 'pinterest' == $merchant ) {
			$merchantAttributes = $attributeClass->pinterestXMLAttribute;
		} elseif ( 'skroutz' == $merchant ) {
			$merchantAttributes = $attributeClass->skroutzXMLAttributes;
		}

		if ( ! empty( $merchantAttributes ) && array_key_exists( $pluginAttribute, $merchantAttributes ) ) {
			if ( 'true' == $merchantAttributes[ $pluginAttribute ][1] ) {
				return "<![CDATA[$attributeValue]]>";
			} else {
				return "$attributeValue";
			}
		} elseif ( false !== strpos( $attributeValue, '&' ) || 'http' == substr( trim( $attributeValue ), 0, 4 ) ) {
			if ( 'catch.com.au' === $merchant ) {
				if ( false !== strpos( $pluginAttribute, 'image' ) ) {
					return "$attributeValue";
				}
			} else {
				return "<![CDATA[ $attributeValue ]]>";
				//                return "$attributeValue";
			}
		} else {
			return "$attributeValue";
		}

		return "$attributeValue";
	}
}

// WooFeed Settings API
if ( ! function_exists( 'woo_feed_get_options' ) ) {
	/**
	 * Get saved settings.
	 *
	 * @param string $key Option name.
	 *                        All default values will be returned if this set to 'defaults',
	 *                        all settings will be return if set to 'all'.
	 * @param bool $default value to return if no matching data found for the key (option)
	 *
	 * @return array|bool|string|mixed
	 * @since 3.3.11
	 */
	function woo_feed_get_options( $key, $default = false ) {
		$defaults = array(
			'per_batch'                  => 200,
			'product_query_type'         => 'wc',
			'variation_query_type'       => 'individual',
			'enable_error_debugging'     => 'off',
			'cache_ttl'                  => 6 * HOUR_IN_SECONDS,
			'overridden_structured_data' => 'off',
			'disable_mpn'                => 'enable',
			'disable_brand'              => 'enable',
			'disable_pixel'              => 'enable',
			'pixel_id'                   => '',
			'disable_remarketing'        => 'disable',
			'remarketing_id'             => '',
			'remarketing_label'          => '',
			'allow_all_shipping'         => 'no',
			'only_free_shipping'         => 'yes',
			'only_local_pickup_shipping' => 'no',
			'enable_ftp_upload'          => 'no',
			'woo_feed_taxonomy'          => array(
				'brand' => 'disable',
			),
			'woo_feed_identifier'        => array(
				'gtin'                      => 'disable',
				'ean'                       => 'disable',
				'mpn'                       => 'disable',
				'isbn'                      => 'disable',
				'age_group'                 => 'disable',
				'material'                  => 'disable',
				'gender'                    => 'disable',
				'cost_of_good_sold'         => 'disable',
				'availability_date'         => 'enable',
				'unit'                      => 'disable',
				'unit_pricing_measure'      => 'disable',
				'unit_pricing_base_measure' => 'disable',
				'custom_field_0'            => 'disable',
				'custom_field_1'            => 'disable',
				'custom_field_2'            => 'disable',
				'custom_field_3'            => 'disable',
				'custom_field_4'            => 'disable',
			),
		);

		/**
		 * Add defaults without chainging the core values.
		 *
		 * @param array $defaults
		 *
		 * @since 3.3.11
		 */
		$defaults = wp_parse_args( apply_filters( 'woo_feed_settings_extra_defaults', array() ), $defaults );

		if ( 'defaults' === $key ) {
			return $defaults;
		}

		$settings = wp_parse_args( get_option( 'woo_feed_settings', array() ), $defaults );

		if ( 'all' === $key ) {
			return $settings;
		}

		if ( array_key_exists( $key, $settings ) ) {
			return $settings[ $key ];
		}

		return $default;
	}
}
if ( ! function_exists( 'woo_feed_save_options' ) ) {
	/**
	 * Save Settings.
	 *
	 * @param array $args Required. option key value paired array to save.
	 *
	 * @return bool
	 * @since 3.3.11
	 */
	function woo_feed_save_options( $args ) {
		$data     = woo_feed_get_options( 'all' );
		$defaults = woo_feed_get_options( 'defaults' );
		$_data    = $data;

		if ( array_key_exists( 'per_batch', $args ) ) {
			$data['per_batch'] = absint( $args['per_batch'] );
			if ( $data['per_batch'] <= 0 ) {
				$data['per_batch'] = $_data['per_batch'] > 0 ? $_data['per_batch'] : $defaults['per_batch'];
			}
			unset( $args['unset'] );
		}
		if ( array_key_exists( 'product_query_type', $args ) ) {
			$data['product_query_type'] = strtolower( $args['product_query_type'] );
			$query_types                = array_keys( woo_feed_get_query_type_options() );
			if ( ! in_array( $data['product_query_type'], $query_types ) ) {
				$data['product_query_type'] = in_array( $_data['product_query_type'], $query_types ) ? $_data['product_query_type'] : $defaults['product_query_type'];
			}
			unset( $args['product_query_type'] );
		}
		if ( array_key_exists( 'variation_query_type', $args ) ) {
			$data['variation_query_type'] = strtolower( $args['variation_query_type'] );
			$query_types                  = array_keys( woo_feed_get_query_type_options( 'variation' ) );
			if ( ! in_array( $data['variation_query_type'], $query_types, true ) ) {
				$data['variation_query_type'] = in_array( $_data['variation_query_type'], $query_types, true ) ? $_data['variation_query_type'] : $defaults['variation_query_type'];
			}
			unset( $args['variation_query_type'] );
		}
		if ( array_key_exists( 'enable_error_debugging', $args ) ) {
			$data['enable_error_debugging'] = strtolower( $args['enable_error_debugging'] );
			if ( ! in_array( $data['enable_error_debugging'], array( 'on', 'off' ) ) ) {
				$data['enable_error_debugging'] = in_array(
					$_data['enable_error_debugging'],
					array(
						'on',
						'off',
					)
				) ? $_data['enable_error_debugging'] : $defaults['enable_error_debugging'];
			}
			unset( $args['enable_error_debugging'] );
		}
		if ( array_key_exists( 'cache_ttl', $args ) ) {
			$data['cache_ttl'] = absint( $args['cache_ttl'] ); // cache ttl can be zero.
			unset( $args['cache_ttl'] );
		}
		if ( array_key_exists( 'overridden_structured_data', $args ) ) {
			$data['overridden_structured_data'] = strtolower( $args['overridden_structured_data'] );
			if ( ! in_array( $data['overridden_structured_data'], array( 'on', 'off' ) ) ) {
				$data['overridden_structured_data'] = in_array(
					$_data['overridden_structured_data'],
					array(
						'on',
						'off',
					)
				) ? $_data['overridden_structured_data'] : $defaults['overridden_structured_data'];
			}
			unset( $args['overridden_structured_data'] );
		}

		if ( array_key_exists( 'disable_pixel', $args ) ) {
			$data['disable_pixel'] = strtolower( $args['disable_pixel'] );
			if ( ! in_array( $data['disable_pixel'], array( 'enable', 'disable' ) ) ) {
				$data['disable_pixel'] = in_array(
					$_data['disable_pixel'],
					array(
						'enable',
						'disable',
					)
				) ? $_data['disable_pixel'] : $defaults['disable_pixel'];
			}
			unset( $args['disable_pixel'] );
		}
		if ( array_key_exists( 'pixel_id', $args ) ) {
			if ( isset( $args['pixel_id'] ) && ! empty( $args['pixel_id'] ) ) {
				$data['pixel_id'] = absint( $args['pixel_id'] );
			} else {
				$data['pixel_id'] = $defaults['pixel_id'];
			}
			unset( $args['pixel_id'] );
		}

		if ( array_key_exists( 'disable_remarketing', $args ) ) {
			$data['disable_remarketing'] = strtolower( $args['disable_remarketing'] );
			if ( ! in_array( $data['disable_remarketing'], array( 'enable', 'disable' ) ) ) {
				$data['disable_remarketing'] = in_array(
					$_data['disable_remarketing'],
					array(
						'enable',
						'disable',
					)
				) ? $_data['disable_remarketing'] : $defaults['disable_remarketing'];
			}
			unset( $args['disable_remarketing'] );
		}
		if ( array_key_exists( 'remarketing_id', $args ) ) {
			if ( isset( $args['remarketing_id'] ) && ! empty( $args['remarketing_id'] ) ) {
				$data['remarketing_id'] = $args['remarketing_id'];
			} else {
				$data['remarketing_id'] = $defaults['remarketing_id'];
			}
			unset( $args['remarketing_id'] );
		}
		if ( array_key_exists( 'remarketing_label', $args ) ) {
			if ( isset( $args['remarketing_label'] ) && ! empty( $args['remarketing_label'] ) ) {
				$data['remarketing_label'] = $args['remarketing_label'];
			} else {
				$data['remarketing_label'] = $defaults['remarketing_label'];
			}
			unset( $args['remarketing_label'] );
		}

		if ( array_key_exists( 'allow_all_shipping', $args ) ) {
			$data['allow_all_shipping'] = strtolower( $args['allow_all_shipping'] );
			if ( ! in_array( $data['allow_all_shipping'], array( 'yes', 'no' ) ) ) {
				$data['allow_all_shipping'] = in_array(
					$_data['allow_all_shipping'],
					array(
						'yes',
						'no',
					)
				) ? $_data['allow_all_shipping'] : $defaults['allow_all_shipping'];
			}
			unset( $args['allow_all_shipping'] );
		}

		if ( array_key_exists( 'only_free_shipping', $args ) ) {
			$data['only_free_shipping'] = strtolower( $args['only_free_shipping'] );
			if ( ! in_array( $data['only_free_shipping'], array( 'yes', 'no' ) ) ) {
				$data['only_free_shipping'] = in_array(
					$_data['only_free_shipping'],
					array(
						'yes',
						'no',
					)
				) ? $_data['only_free_shipping'] : $defaults['only_free_shipping'];
			}
			unset( $args['only_free_shipping'] );
		}

		if ( array_key_exists( 'only_local_pickup_shipping', $args ) ) {
			$data['only_local_pickup_shipping'] = strtolower( $args['only_local_pickup_shipping'] );
			if ( ! in_array( $data['only_local_pickup_shipping'], array( 'yes', 'no' ) ) ) {
				$data['only_local_pickup_shipping'] = in_array(
					$_data['only_local_pickup_shipping'],
					array(
						'yes',
						'no',
					)
				) ? $_data['only_local_pickup_shipping'] : $defaults['only_local_pickup_shipping'];
			}
			unset( $args['only_local_pickup_shipping'] );
		}

		if ( array_key_exists( 'enable_ftp_upload', $args ) ) {
			$data['enable_ftp_upload'] = strtolower( $args['enable_ftp_upload'] );
			if ( ! in_array( $data['enable_ftp_upload'], array( 'yes', 'no' ) ) ) {
				$data['enable_ftp_upload'] = in_array(
					$_data['enable_ftp_upload'],
					array(
						'yes',
						'no',
					)
				) ? $_data['enable_ftp_upload'] : $defaults['enable_ftp_upload'];
			}
			unset( $args['enable_ftp_upload'] );
		}

		if ( ! empty( $args ) ) {
			foreach ( $args as $key => $value ) {
				if ( has_filter( "woo_feed_save_{$key}_option" ) ) {
					$data[ $key ] = apply_filters( "woo_feed_save_{$key}_option", sanitize_text_field( $value ) );
				}
			}
		}

		return update_option( 'woo_feed_settings', $data, false );
	}
}
if ( ! function_exists( 'woo_feed_reset_options' ) ) {
	/**
	 * Restore the default settings.
	 *
	 * @return bool
	 * @since 3.3.11
	 */
	function woo_feed_reset_options() {
		return update_option( 'woo_feed_settings', woo_feed_get_options( 'defaults' ), false );
	}
}

// Caching. Wrapper for Transient API.
if ( ! function_exists( 'woo_feed_get_cached_data' ) ) {
	/**
	 * Get Cached Data
	 *
	 * @param string $key Cache Name
	 *
	 * @return mixed|false  false if cache not found.
	 * @since 3.3.10
	 */
	function woo_feed_get_cached_data( $key ) {
		if ( empty( $key ) ) {
			return false;
		}

		return get_transient( '__woo_feed_cache_' . $key );
	}
}
if ( ! function_exists( 'woo_feed_set_cache_data' ) ) {
	/**
	 *
	 * @param string $key Cache name. Expected to not be SQL-escaped. Must be
	 *                             172 characters or fewer in length.
	 * @param mixed $data Data to cache. Must be serializable if non-scalar.
	 *                             Expected to not be SQL-escaped.
	 * @param int|bool $expiration Optional. Time until expiration in seconds. Default 0 (no expiration).
	 *
	 * @return bool
	 */
	function woo_feed_set_cache_data( $key, $data, $expiration = false ) {

		if ( empty( $key ) ) {
			return false;
		}

		if ( false === $expiration ) {
			$expiration = WOO_FEED_CACHE_TTL;
		}

		return set_transient( '__woo_feed_cache_' . $key, $data, (int) $expiration );
	}
}
if ( ! function_exists( 'woo_feed_delete_cache_data' ) ) {
	/**
	 * Delete Cached Data
	 *
	 * @param string $key cache name.
	 *
	 * @return bool
	 */
	function woo_feed_delete_cache_data( $key ) {
		if ( empty( $key ) ) {
			return false;
		}

		return delete_transient( '__woo_feed_cache_' . $key );
	}
}
if ( ! function_exists( 'woo_feed_flush_cache_data' ) ) {
	/**
	 * Delete All Cached Data
	 *
	 * @return void
	 */
	function woo_feed_flush_cache_data() {
		global $wpdb;
		//      $wpdb->query( "DELETE FROM $wpdb->options WHERE {$wpdb->options}.option_name LIKE '_transient___woo_feed_cache_%' " ); // phpcs:ignore
		//      $wpdb->query( "DELETE FROM $wpdb->options WHERE {$wpdb->options}.option_name LIKE '_transient_timeout___woo_feed_cache_%'" ); // phpcs:ignore
		$wpdb->query( "DELETE FROM $wpdb->options WHERE ({$wpdb->options}.option_name LIKE '_transient_timeout___woo_feed_cache_%') OR ({$wpdb->options}.option_name LIKE '_transient___woo_feed_cache_%')" ); // phpcs:ignore
	}
}

// Price And Tax.
if ( ! function_exists( 'woo_feed_apply_tax_location_data' ) ) {
	/**
	 * Filter and Change Location data for tax calculation
	 *
	 * @param array $location Location array.
	 * @param string $tax_class Tax class.
	 * @param WC_Customer $customer WooCommerce Customer Object.
	 *
	 * @return array
	 */
	function woo_feed_apply_tax_location_data( $location, $tax_class, $customer ) {
		// @TODO use filter. add tab in feed editor so user can set custom settings.
		// @TODO tab should not list all country and cities. it only list available tax settings and user can just select one.
		// @TODO then it will extract the location data from it to use here.
		$wc_tax_location = array(
			WC()->countries->get_base_country(),
			WC()->countries->get_base_state(),
			WC()->countries->get_base_postcode(),
			WC()->countries->get_base_city(),
		);
		/**
		 * Filter Tax Location to apply before product loop
		 *
		 * @param array $tax_location
		 *
		 * @since 3.3.0
		 */
		$tax_location = apply_filters( 'woo_feed_tax_location_data', $wc_tax_location );
		if ( ! is_array( $tax_location ) || ( is_array( $tax_location ) && 4 !== count( $tax_location ) ) ) {
			$tax_location = $wc_tax_location;
		}

		return $tax_location;
	}
}

// Hook feed generating process...
if ( ! function_exists( 'woo_feed_apply_hooks_before_product_loop' ) ) {
	/**
	 * Apply Hooks Before Looping through ProductIds
	 *
	 * @param int[] $productIds product id array.
	 * @param array $feedConfig feed config array.
	 */
//	function woo_feed_apply_hooks_before_product_loop( $productIds, $feedConfig ) {
//		add_filter( 'woocommerce_get_tax_location', 'woo_feed_apply_tax_location_data', 10, 3 );
//
//		// RightPress dynamic pricing support.
//		add_filter( 'rightpress_product_price_shop_change_prices_in_backend', '__return_true', 999 );
//		add_filter( 'rightpress_product_price_shop_change_prices_before_cart_is_loaded', '__return_true', 999 );
//
//	}
}

if ( ! function_exists( 'woo_feed_remove_hooks_after_product_loop' ) ) {
	/**
	 * Remove Applied Hooks Looping through ProductIds
	 *
	 * @param int[] $productIds product id array.
	 * @param array $feedConfig feed config array.
	 *
	 * @see woo_feed_apply_hooks_before_product_loop
	 */
//	function woo_feed_remove_hooks_after_product_loop( $productIds, $feedConfig ) {
//		remove_filter( 'woocommerce_get_tax_location', 'woo_feed_apply_tax_location_data', 10 );
//
//		// RightPress dynamic pricing support.
//		remove_filter( 'rightpress_product_price_shop_change_prices_in_backend', '__return_true', 999 );
//		remove_filter( 'rightpress_product_price_shop_change_prices_before_cart_is_loaded', '__return_true', 999 );
//
//	}
}
if ( ! function_exists( 'woo_feed_remove_hooks_before_product_loop' ) ) {
	/**
	 * Remove Applied Hooks Looping through ProductIds
	 *
	 * @param int[] $productIds product id array.
	 * @param array $feedConfig feed config array.
	 *
	 * @see woo_feed_apply_hooks_before_product_loop
	 */
	function woo_feed_remove_hooks_before_product_loop( $productIds, $feedConfig ) {
		remove_filter( 'woocommerce_get_tax_location', 'woo_feed_apply_tax_location_data', 10 );
	}
}

if ( ! function_exists( 'woo_feed_product_taxonomy_term_separator' ) ) {
	/**
	 * Filter Product local category (type) separator
	 *
	 * @param string $separator
	 * @param array $config
	 *
	 * @return string
	 */
	function woo_feed_product_taxonomy_term_separator( $separator, $config ) {
		if ( 'trovaprezzi' === $config['provider'] ) {
			$separator = ',';
		}

		if ( false !== strpos( $config['provider'], 'daisycon' ) ) {
			$separator = '|';
		}

		return $separator;
	}
}
if ( ! function_exists( 'woo_feed_get_availability_attribute_filter' ) ) {
	/**
	 * Filter Product Availability Attribute Output For Template
	 *
	 * @param string $output Output string.
	 * @param WC_Product $product Product Object
	 * @param array $config Feed Config
	 *
	 * @return int
	 */
	function woo_feed_get_availability_attribute_filter( $output, $product, $config ) {
		$status   = $product->get_stock_status();
		$provider = $config['provider'];

		if ( 'trovaprezzi' === $provider ) {
			$output = 2;
			if ( $status ) {
				if ( 'instock' == $status ) {
					$output = 2;
				} elseif ( 'outofstock' == $status ) {
					$output = 0;
				} elseif ( 'onbackorder' == $status ) {
					$output = 1;
				}
			}
		}

		if ( false !== strpos( $provider, 'daisycon' ) ) {
			$output = 'true';
			if ( $status ) {
				if ( 'instock' == $status ) {
					$output = 'true';
				} elseif ( 'outofstock' == $status ) {
					$output = 'false';
				} elseif ( 'onbackorder' == $status ) {
					$output = 'false';
				}
			}
		}

		return $output;
	}
}

// Parse feed rules.
if ( ! function_exists( 'woo_feed_filter_parsed_rules' ) ) {
	/**
	 * Filter Feed parsed rules
	 *
	 * @param array $rules Feed Config
	 * @param string $context Parsing context
	 *
	 * @return array
	 * @since 3.3.7
	 */
	function woo_feed_filter_parsed_rules( $rules, $context ) {
		$provider = $rules['provider'];

		if ( 'create' === $context ) {
			if ( 'criteo' === $provider ) {
				$rules['itemsWrapper'] = 'channel';
				$rules['itemWrapper']  = 'item';
			}

			if ( 'wine_searcher' === $provider ) {
				$rules['itemsWrapper'] = 'product-list';
				$rules['itemWrapper']  = 'row';
				$rules['delimiter']    = '|';
				$rules['enclosure']    = ' ';
			}

			if ( 'trovaprezzi' === $provider ) {
				$rules['decimal_separator']  = ',';
				$rules['thousand_separator'] = '';
				$rules['decimals']           = 2;
				$rules['itemsWrapper']       = 'Products';
				$rules['itemWrapper']        = 'Offer';
				$rules['delimiter']          = '|';
				$rules['enclosure']          = ' ';
			}

			if ( false !== strpos( $provider, 'daisycon' ) ) {
				$rules['itemsWrapper'] = 'channel';
				$rules['itemWrapper']  = 'item';
			}

			if ( false !== strpos( $provider, 'zbozi.cz' ) ) {
				$rules['itemsWrapper'] = 'SHOP xmlns="http://www.zbozi.cz/ns/offer/1.0"';
				$rules['itemWrapper']  = 'SHOPITEM';
			}

			if ( false !== strpos( $provider, 'heureka.sk' ) ) {
				$rules['itemWrapper'] = 'SHOPITEM';
			}
		}

		return $rules;
	}
}

if ( ! function_exists( 'array_splice_preserve_keys' ) ) {
	/**
	 * Function to splice an array keeping key
	 */
	function array_splice_preserve_keys( &$input, $offset, $length = null, $replacement = array() ) {

		if ( empty( $replacement ) ) {
			return array_splice( $input, $offset, $length );
		}

		$part_before  = array_slice( $input, 0, $offset, $preserve_keys = true );
		$part_removed = array_slice( $input, $offset, $length, $preserve_keys = true );
		$part_after   = array_slice( $input, $offset + $length, null, $preserve_keys = true );

		$input = $part_before + $replacement + $part_after;

		return $part_removed;
	}
}

if ( ! function_exists( 'woo_feed_product_custom_fields' ) ) {
	function woo_feed_product_custom_fields() {
		/**
		 * Here array of a field contain 3 elements
		 * 1. Name
		 * 2. Is this fields enabled by default
		 * 3. Is this fields is a custom taxonomy
		 */
		$custom_fields = array(
			'brand'                     => array( __( 'Brand', 'woo-feed' ), true, true ),
			'gtin'                      => array( __( 'GTIN', 'woo-feed' ), true ),
			'mpn'                       => array( __( 'MPN', 'woo-feed' ), true ),
			'ean'                       => array( __( 'EAN', 'woo-feed' ), true ),
			'isbn'                      => array( __( 'ISBN', 'woo-feed' ), true ),
			'age_group'                 => array( __( 'Age group', 'woo-feed' ), true ),
			'gender'                    => array( __( 'Gender', 'woo-feed' ), true ),
			'material'                  => array( __( 'Material', 'woo-feed' ), true ),
			'cost_of_good_sold'         => array( __( 'Cost of good sold', 'woo-feed' ), true ),
			'availability_date'         => array( __( 'Availability Date', 'woo-feed' ), true, false, false ),
			'unit'                      => array( __( 'Unit', 'woo-feed' ), true ),
			'unit_pricing_measure'      => array( __( 'Unit Price Measure', 'woo-feed' ), true ),
			'unit_pricing_base_measure' => array( __( 'Unit Price Base Measure', 'woo-feed' ), true ),
			'custom_field_0'            => array( __( 'Custom field 0', 'woo-feed' ), true ),
			'custom_field_1'            => array( __( 'Custom field 1', 'woo-feed' ), true ),
			'custom_field_2'            => array( __( 'Custom field 2', 'woo-feed' ), true ),
			'custom_field_3'            => array( __( 'Custom field 3', 'woo-feed' ), true ),
			'custom_field_4'            => array( __( 'Custom field 4', 'woo-feed' ), true ),
		);

		return apply_filters( 'woo_feed_product_custom_fields', $custom_fields );
	}
}

if ( ! function_exists( 'woo_feed_product_attribute_cache_remove_cb' ) ) {

	add_action( 'wp_ajax_woo_feed_product_attribute_cache_remove', 'woo_feed_product_attribute_cache_remove_cb' );
	/**
	 * This function is called when product attribute swicher click.
	 */
	function woo_feed_product_attribute_cache_remove_cb() {
		$is_nonce_valid = isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wpf_feed_nonce' );

		if ( $is_nonce_valid ) {
			delete_transient( '__woo_feed_cache_woo_feed_dropdown_product_attributes' );
		} else {
			wp_send_json_error( esc_html__( 'Invalid Request.', 'woo-feed' ) );
		}

		wp_die();
	}
}

if ( ! function_exists( 'woo_feed_custom_fields_status_change_cb' ) ) {
	add_action( 'wp_ajax_woo_feed_custom_fields_status_change', 'woo_feed_custom_fields_status_change_cb' );
	/**
	 * This AJAX callback function is called when custom fields on/off switched
	 */
	function woo_feed_custom_fields_status_change_cb() {
		$is_nonce_valid = isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wpf_feed_nonce' );

		if ( $is_nonce_valid && isset(
			$_POST['field'],
			$_POST['status'],
			$_POST['isTaxonomy']
		) ) {
			$field       = sanitize_text_field( wp_unslash( $_POST['field'] ) );
			$is_taxonomy = sanitize_text_field( wp_unslash( $_POST['isTaxonomy'] ) );
			$status      = sanitize_text_field( wp_unslash( $_POST['status'] ) );
			$data        = woo_feed_get_options( 'all' );
			if ( 'true' === $is_taxonomy ) {
				$data['woo_feed_taxonomy'][ $field ] = ( 'true' === $status ) ? 'enable' : 'disable';
			} else {
				$data['woo_feed_identifier'][ $field ] = ( 'true' === $status ) ? 'enable' : 'disable';
			}
			update_option( 'woo_feed_settings', $data, false );
		} else {
			wp_send_json_error( esc_html__( 'Invalid Request.', 'woo-feed' ) );
		}

		wp_die();
	}
}

if ( ! function_exists( 'woo_feed_add_custom_identifier' ) ) {
	/**
	 * Add Custom fields into product inventory tab for Unique Identifier (GTIN,MPN,EAN)
	 *
	 * @since 3.7.8
	 */
	function woo_feed_add_custom_identifier() {
		$custom_fields            = woo_feed_product_custom_fields();
		$custom_identifier_filter = new Woo_Feed_Custom_Identifier_Filter( $custom_fields );
		$custom_identifier        = iterator_to_array( $custom_identifier_filter );

		echo '<div class="options_group">';
		if ( ! empty( $custom_identifier ) ) {
			echo sprintf( '<h4 class="%s" style="padding-left: 10px;color: black;">%s</h4>', esc_attr( 'woo-feed-option-title' ), esc_html__( 'CUSTOM FIELDS by CTX Feed', 'woo-feed' ) );
			foreach ( $custom_identifier as $key => $value ) {

				//identifier meta value for old and new version users
				$custom_field_key_previous   = sprintf( 'woo_feed_identifier_%s', strtolower( $key ) );
				$custom_field_value_previous = get_post_meta( get_the_ID(), $custom_field_key_previous, true );

				$custom_field_key   = sprintf( 'woo_feed_%s', strtolower( $key ) );
				$custom_field_value = get_post_meta( get_the_ID(), $custom_field_key, true );

				if( empty( $custom_field_value ) && is_plugin_active( 'woocommerce-multilingual/wpml-woocommerce.php' )){
					$wcmlCurrency  = new WCMLCurrency();
					$originalId = $wcmlCurrency->woo_feed_wpml_get_original_post_id( get_the_ID() );

					$custom_field_value = get_post_meta( $originalId, $custom_field_key, true );
				}

				if ( empty( $custom_field_value ) && ! empty( $custom_field_value_previous ) ) {
					$custom_field_key   = $custom_field_key_previous;
					$custom_field_value = $custom_field_value_previous;
				}

				$custom_field_id          = esc_attr( wp_unslash( "woo_feed_{$key}" ) );
				$custom_field_label       = esc_attr( wp_unslash( $custom_fields[ $key ][0] ) );
				$custom_field_description = __( 'Set product ', 'woo-feed' ) . esc_html( $custom_field_label ) . __( ' here.', 'woo-feed' );
				woocommerce_wp_text_input(
					array(
						'id'          => $custom_field_id,
						'value'       => esc_attr( wp_unslash( $custom_field_value ) ),
						'placeholder' => $custom_field_label,
						'label'       => $custom_field_label,
						'desc_tip'    => true,
						'description' => $custom_field_description,
					)
				);
			}
		}

		echo '</div>';

	}

//	add_action( 'woocommerce_product_options_inventory_product_data', 'woo_feed_add_custom_identifier' );
}

if ( ! function_exists( 'woo_feed_save_custom_identifier' ) ) {

	/**
	 * Updating custom fields data. (Unique Identifier (GTIN,MPN,EAN))
	 *
	 * @param int $id Post Id
	 * @param WP_Post $post Wp Post Object.
	 *
	 * @since 3.7.8
	 */
	function woo_feed_save_custom_identifier( $product_id, $product ) {
		$custom_fields            = woo_feed_product_custom_fields();
		$custom_identifier_filter = new Woo_Feed_Custom_Identifier_Filter( $custom_fields );
		$custom_identifier        = iterator_to_array( $custom_identifier_filter );
		$set_meta_val             = '';

		if ( ! empty( $custom_identifier ) ) {
			foreach ( $custom_identifier as $key => $name ) {
				$product_meta_key = "woo_feed_{$key}";

				$new_meta_key = "woo_feed_identifier_{$key}";
				$new_meta_val = get_post_meta( $product_id, $new_meta_key, true );
				$old_meta_val = get_post_meta( $product_id, $product_meta_key, true );

				if ( ! empty( $old_meta_val ) ) {
					$set_meta_val = $old_meta_val;
				} else {
					$set_meta_val = $new_meta_val;
				}

				$product_meta_value = isset( $_POST[ $product_meta_key ] ) ? sanitize_text_field( $_POST[ $product_meta_key ] ) : ( isset( $_POST[ "woo_feed_identifier_{$key}" ] ) ? sanitize_text_field( $_POST[ "woo_feed_identifier_{$key}" ] ) : $set_meta_val );

				if ( isset( $product_meta_value ) && ! empty( $product_meta_value ) ) {
					update_post_meta( $product_id, $product_meta_key, $product_meta_value );
				} else {
					delete_post_meta( $product_id, $product_meta_key );
				}
			}
		}

	}

//	add_action( 'save_post_product', 'woo_feed_save_custom_identifier', 10, 2 );
}

if ( ! function_exists( 'woo_feed_add_custom_identifier_for_variation' ) ) {

	/**
	 * Custom options in variation tab, here we are putting gtin, mpn, ean input fields in product variation tab
	 *
	 * @param int $loop Variation loop index.
	 * @param array $variation_data Variation info.
	 * @param WP_Post $variation Post Object.
	 *
	 * @since 3.7.8
	 */
	function woo_feed_add_custom_identifier_for_variation( $loop, $variation_data, $variation ) {
		$settings = woo_feed_get_options( 'all' );
		if ( isset( $settings['disable_mpn'] ) && 'enable' === $settings['disable_mpn'] ) {
			echo '<div class="woo-feed-variation-options">';
			$custom_fields            = woo_feed_product_custom_fields();
			$custom_identifier_filter = new Woo_Feed_Custom_Identifier_Filter( $custom_fields );
			$custom_identifier        = iterator_to_array( $custom_identifier_filter );

			if ( ! empty( $custom_identifier ) ) {
				echo '<div class="woo-feed-variation-options">';
				echo sprintf( '<h4 class="%s">%s</h4>', esc_attr( 'woo-feed-variation-option-title' ), esc_html__( 'CUSTOM FIELDS by CTX Feed', 'woo-feed' ) );
				echo '<div class="woo-feed-variation-items">';

				foreach ( $custom_identifier as $key => $value ) {
					$custom_field_id          = sprintf( 'woo_feed_%s_var[%d]', strtolower( $key ), $variation->ID );
					$custom_field_label       = isset( $value[0] ) ? $value[0] : '';
					$custom_field_description = sprintf( 'Set product %s here.', $custom_field_label );

					//identifier meta value for old and new version users
					if ( metadata_exists( 'post', $variation->ID, 'woo_feed_' . strtolower( $key ) . '_var' ) ) {
						$custom_field_key = sprintf( 'woo_feed_%s_var', strtolower( $key ) );
					} else {
						$custom_field_key = sprintf( 'woo_feed_identifier_%s_var', strtolower( $key ) );
					}

					woocommerce_wp_text_input(
						array(
							'id'            => $custom_field_id,
							'value'         => esc_attr( get_post_meta( $variation->ID, $custom_field_key, true ) ),
							'placeholder'   => esc_html( $custom_field_label ),
							'label'         => esc_html( $custom_field_label ),
							'desc_tip'      => true,
							'description'   => esc_html( $custom_field_description ),
							'wrapper_class' => 'form-row form-row-full',
						)
					);
				}
				echo '</div></div>';
			}
			echo '</div>';
		}
	}

//	add_action( 'woocommerce_product_after_variable_attributes', 'woo_feed_add_custom_identifier_for_variation', 10, 3 );
}

if ( ! function_exists( 'woo_feed_save_custom_identifier_for_variation' ) ) {

	/**
	 * Saving variation custom fields.
	 *
	 * @param int $variation_id Variation Id.
	 * @param int $i variations loop index.
	 *
	 * @since 3.7.8
	 */
	function woo_feed_save_custom_identifier_for_variation( $variation_id, $i ) {
		$custom_fields            = woo_feed_product_custom_fields();
		$custom_identifier_filter = new Woo_Feed_Custom_Identifier_Filter( $custom_fields );
		$custom_identifier        = iterator_to_array( $custom_identifier_filter );

		if ( ! empty( $custom_identifier ) ) {
			foreach ( $custom_identifier as $key => $value ) {
				$custom_field_value = isset( $_POST[ "woo_feed_{$key}_var" ][ $variation_id ] ) ? sanitize_text_field( $_POST[ "woo_feed_{$key}_var" ][ $variation_id ] ) : ( isset( $_POST[ "woo_feed_identifier_{$key}_var" ][ $variation_id ] ) ? sanitize_text_field( $_POST[ "woo_feed_identifier_{$key}_var" ] ) : '' );
				if ( isset( $custom_field_value ) ) {
					update_post_meta( $variation_id, "woo_feed_{$key}_var", $custom_field_value );
				}
			}
		}

	}

//	add_action( 'woocommerce_save_product_variation', 'woo_feed_save_custom_identifier_for_variation', 10, 2 );
}

if ( ! function_exists( 'woo_feed_category_mapping' ) ) {
	/**
	 * Category Mapping
	 */
	function woo_feed_category_mapping() {
		// Manage action for category mapping.
		if ( isset( $_GET['action'], $_GET['cmapping'] ) && 'edit-mapping' == $_GET['action'] ) {
			if ( count( $_POST ) && isset( $_POST['mappingname'] ) && isset( $_POST['edit-mapping'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
				check_admin_referer( 'category-mapping' );

				$mappingOption = sanitize_text_field( wp_unslash( $_POST['mappingname'] ) );
				$mappingOption = 'wf_cmapping_' . sanitize_title( $mappingOption );
				$mappingData   = woo_feed_array_sanitize( $_POST );
				$oldMapping    = maybe_unserialize( get_option( $mappingOption, array() ) );

				# Delete product attribute drop-down cache
				delete_transient( '__woo_feed_cache_woo_feed_dropdown_product_attributes' );

				if ( $oldMapping === $mappingData ) {
					update_option( 'wpf_message', esc_html__( 'Mapping Not Changed', 'woo-feed' ), false );
					wp_safe_redirect( admin_url( 'admin.php?page=webappick-feed-category-mapping&wpf_message=warning' ) );
					die();
				}

				if ( update_option( $mappingOption, serialize( $mappingData ), false ) ) { // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
					update_option( 'wpf_message', esc_html__( 'Mapping Updated Successfully', 'woo-feed' ), false );
					wp_safe_redirect( admin_url( 'admin.php?page=webappick-feed-category-mapping&wpf_message=success' ) );
					die();
				} else {
					update_option( 'wpf_message', esc_html__( 'Failed To Updated Mapping', 'woo-feed' ), false );
					wp_safe_redirect( admin_url( 'admin.php?page=webappick-feed-category-mapping&wpf_message=error' ) );
					die();
				}
			}
			require WOO_FEED_FREE_ADMIN_PATH . 'partials/woo-feed-category-mapping.php';
		} elseif ( isset( $_GET['action'] ) && 'add-mapping' == $_GET['action'] ) {
			if ( count( $_POST ) && isset( $_POST['mappingname'] ) && isset( $_POST['add-mapping'] ) ) {
				check_admin_referer( 'category-mapping' );

				$mappingOption = 'wf_cmapping_' . sanitize_text_field( wp_unslash( $_POST['mappingname'] ) );

				# Delete product attribute drop-down cache
				delete_transient( '__woo_feed_cache_woo_feed_dropdown_product_attributes' );

				if ( false !== get_option( $mappingOption, false ) ) {
					update_option( 'wpf_message', esc_html__( 'Another category mapping exists with the same name.', 'woo-feed' ), false );
					wp_safe_redirect( admin_url( 'admin.php?page=webappick-feed-category-mapping&wpf_message=warning' ) );
					die();
				}
				if ( update_option( $mappingOption, serialize( woo_feed_array_sanitize( $_POST ) ), false ) ) { // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
					update_option( 'wpf_message', esc_html__( 'Mapping Added Successfully', 'woo-feed' ), false );
					wp_safe_redirect( admin_url( 'admin.php?page=webappick-feed-category-mapping&wpf_message=success' ) );
					die();
				} else {
					update_option( 'wpf_message', esc_html__( 'Failed To Add Mapping', 'woo-feed' ), false );
					wp_safe_redirect( admin_url( 'admin.php?page=webappick-feed-category-mapping&wpf_message=error' ) );
					die();
				}
			}
			require WOO_FEED_FREE_ADMIN_PATH . 'partials/woo-feed-category-mapping.php';
		} else {
			require WOO_FEED_FREE_ADMIN_PATH . 'partials/woo-feed-category-mapping-list.php';
		}
	}
}

// Category mapping.
if ( ! function_exists( 'woo_feed_render_categories' ) ) {
	/**
	 * Get Product Categories
	 *
	 * @param int $parent Parent ID.
	 * @param string $par separator.
	 * @param string $value mapped values.
	 *
	 * @return void
	 */
	function woo_feed_render_categories( $parent = 0, $par = '', $value = '' ) {
		$categoryArgs = array(
			'taxonomy'     => 'product_cat',
			'parent'       => $parent,
			'orderby'      => 'term_group',
			'show_count'   => 1,
			'pad_counts'   => 1,
			'hierarchical' => 1,
			'title_li'     => '',
			'hide_empty'   => 0,
		);
		$categories   = get_categories( $categoryArgs );
		if ( ! empty( $categories ) ) {
			if ( ! empty( $par ) ) {
				$par = $par . ' > ';
			}
			foreach ( $categories as $cat ) {
				$class = $parent ? "treegrid-parent-{$parent} category-mapping" : 'treegrid-parent category-mapping';
				?>
				<tr class="treegrid-1 ">
					<th>
						<label for="cat_mapping_<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $par . $cat->name ); ?></label>
					</th>
					<td colspan="3">
						<!--suppress HtmlUnknownAttribute -->
						<?php
						$newOrGoogleFacebook = ! empty( $value )
											&& in_array(
												$value['mappingprovider'],
												array(
													'google',
													'facebook',
													'pinterest',
													'bing',
													'bing_local_inventory',
													'snapchat',
												)
											);

						$previous_mapping_value         = is_array( $value ) && isset( $value['cmapping'][ $cat->term_id ] ) ? esc_attr( $value['cmapping'][ $cat->term_id ] ) : '';
						$previous_listing_mapping_value = is_array( $value ) && isset( $value['gcl-cmapping'][ $cat->term_id ] ) && ! empty( $value['gcl-cmapping'][ $cat->term_id ] )
							? esc_attr( $value['gcl-cmapping'][ $cat->term_id ] )
							: $previous_mapping_value;

						?>
						<input <?php echo ( ! $newOrGoogleFacebook ) ? '' : 'style=" display: none;" '; ?>
								id="cat_mapping_<?php echo esc_attr( $cat->term_id ); ?>"
								class="<?php echo esc_attr( $class ); ?> woo-feed-mapping-input"
								autocomplete="off"
								type="text"
								name="cmapping[<?php echo esc_attr( $cat->term_id ); ?>]"
								placeholder="<?php echo esc_attr( $par . $cat->name ); ?>"
								data-cat_id="<?php echo esc_attr( $cat->term_id ); ?>"
								value="<?php echo $previous_mapping_value; ?>"
						>
						<span
							<?php echo ( $newOrGoogleFacebook ) ? '' : 'style=" display: none;" '; ?>class="wf_default wf_attributes">
							<select name="gcl-cmapping[<?php echo $cat->term_id; ?>]"
									class="selectize selectize-google-category woo-feed-mapping-select"
									data-selected="<?php echo $previous_mapping_value; ?>"
									data-placeholder="<?php esc_attr_e( 'Select A Category', 'woo-feed' ); ?>">
								<option value="<?php echo esc_attr( $previous_listing_mapping_value ); ?>" selected>
									<?php echo esc_attr( $previous_listing_mapping_value ); ?>
								</option>
							</select>

						</span>
					</td>
					<?php
					if ( ! empty( get_term_children( $cat->term_id, 'product_cat' ) ) ) {
						$woo_map_term_id = 'parent-' . $cat->term_id;
					} else {
						$woo_map_term_id = 'child-' . $cat->parent;
					}
					$termchildren = ! empty( get_term_children( $cat->term_id, 'product_cat' ) ) || $cat->parent;
					?>
					<td class="<?php echo $termchildren ? 'group-' . $woo_map_term_id : ''; ?>">
						<?php
						$childrencat = ! empty( get_term_children( $cat->term_id, 'product_cat' ) );
						if ( $childrencat ) {
							$title = __( 'Copy this category to subcategories', 'woo-feed' );
							echo '<span class="dashicons dashicons-arrow-down-alt" title=" ' . $title . '" id="cat-map-' . $cat->term_id . '"></span>';
						}
						?>
					</td>
				</tr>
				<?php
				// call and render the child category if any.
				woo_feed_render_categories( $cat->term_id, $par . $cat->name, $value );
			}
		}
	}
}

if ( ! function_exists( 'woo_feed_clear_cache_button' ) ) {
	/**
	 * Clear cache button.
	 *
	 * @return void
	 * @since  4.1.2
	 */
	function woo_feed_clear_cache_button() {
		?>
		<div class="wf_clean_cache_wrapper">
			<img class="woo-feed-cache-loader"
				 src="data:image/svg+xml,%3C%3Fxml%20version%3D%221.0%22%20encoding%3D%22iso-8859-1%22%3F%3E%0D%0A%3C%21--%20Generator%3A%20Adobe%20Illustrator%2019.0.0%2C%20SVG%20Export%20Plug-In%20.%20SVG%20Version%3A%206.00%20Build%200%29%20%20--%3E%0D%0A%3Csvg%20version%3D%221.1%22%20id%3D%22Capa_1%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20xmlns%3Axlink%3D%22http%3A%2F%2Fwww.w3.org%2F1999%2Fxlink%22%20x%3D%220px%22%20y%3D%220px%22%0D%0A%09%20viewBox%3D%220%200%20458.186%20458.186%22%20style%3D%22enable-background%3Anew%200%200%20458.186%20458.186%3B%22%20xml%3Aspace%3D%22preserve%22%3E%0D%0A%3Cg%3E%0D%0A%09%3Cg%3E%0D%0A%09%09%3Cpath%20d%3D%22M445.651%2C201.95c-1.485-9.308-10.235-15.649-19.543-14.164c-9.308%2C1.485-15.649%2C10.235-14.164%2C19.543%0D%0A%09%09%09c0.016%2C0.102%2C0.033%2C0.203%2C0.051%2C0.304c17.38%2C102.311-51.47%2C199.339-153.781%2C216.719c-102.311%2C17.38-199.339-51.47-216.719-153.781%0D%0A%09%09%09S92.966%2C71.232%2C195.276%2C53.852c62.919-10.688%2C126.962%2C11.29%2C170.059%2C58.361l-75.605%2C25.19%0D%0A%09%09%09c-8.944%2C2.976-13.781%2C12.638-10.806%2C21.582c0.001%2C0.002%2C0.002%2C0.005%2C0.003%2C0.007c2.976%2C8.944%2C12.638%2C13.781%2C21.582%2C10.806%0D%0A%09%09%09c0.003-0.001%2C0.005-0.002%2C0.007-0.002l102.4-34.133c6.972-2.322%2C11.675-8.847%2C11.674-16.196v-102.4%0D%0A%09%09%09C414.59%2C7.641%2C406.949%2C0%2C397.523%2C0s-17.067%2C7.641-17.067%2C17.067v62.344C292.564-4.185%2C153.545-0.702%2C69.949%2C87.19%0D%0A%09%09%09s-80.114%2C226.911%2C7.779%2C310.508s226.911%2C80.114%2C310.508-7.779C435.905%2C339.799%2C457.179%2C270.152%2C445.651%2C201.95z%22%2F%3E%0D%0A%09%3C%2Fg%3E%0D%0A%3C%2Fg%3E%0D%0A%3Cg%3E%0D%0A%3C%2Fg%3E%0D%0A%3Cg%3E%0D%0A%3C%2Fg%3E%0D%0A%3Cg%3E%0D%0A%3C%2Fg%3E%0D%0A%3Cg%3E%0D%0A%3C%2Fg%3E%0D%0A%3Cg%3E%0D%0A%3C%2Fg%3E%0D%0A%3Cg%3E%0D%0A%3C%2Fg%3E%0D%0A%3Cg%3E%0D%0A%3C%2Fg%3E%0D%0A%3Cg%3E%0D%0A%3C%2Fg%3E%0D%0A%3Cg%3E%0D%0A%3C%2Fg%3E%0D%0A%3Cg%3E%0D%0A%3C%2Fg%3E%0D%0A%3Cg%3E%0D%0A%3C%2Fg%3E%0D%0A%3Cg%3E%0D%0A%3C%2Fg%3E%0D%0A%3Cg%3E%0D%0A%3C%2Fg%3E%0D%0A%3Cg%3E%0D%0A%3C%2Fg%3E%0D%0A%3Cg%3E%0D%0A%3C%2Fg%3E%0D%0A%3C%2Fsvg%3E%0D%0A"
				 alt="loader">
			<input type="hidden" class="woo-feed-clean-cache-nonce"
                   value="<?php echo wp_create_nonce( 'clean_cache_nonce' ); //phpcs:ignore
					?>
				   ">
			<button type="button"><?php esc_html_e( 'Clear Cache', 'woo-feed' ); ?></button>
		</div>
		<?php
	}
}

if ( ! function_exists( 'woo_feed_clear_cache_data' ) ) {
	/**
	 * Clear cache data.
	 *
	 * @param int _ajax_clean_nonce nonce number.
	 *
	 * @since 4.1.2
	 */
	function woo_feed_clear_cache_data() {
		if ( isset( $_REQUEST['_ajax_clean_nonce'] ) ) {

            if ( isset( $_POST['type'] ) ) {
                $type = $_POST['type'] ;
            }else {
                $type = "woo_feed_attributes";
            }

			if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_ajax_clean_nonce'] ) ), 'clean_cache_nonce' ) ) {
				$data = array();

				global $wpdb;
				//TODO add wpdb prepare statement
				$wpdb->query( "DELETE FROM $wpdb->options WHERE ({$wpdb->options}.option_name LIKE '_transient_timeout___woo_feed_cache_%') OR ({$wpdb->options}.option_name LIKE '_transient___woo_feed_cache_%')" ); // phpcs:ignore

                $nonce = wp_create_nonce( "pressmodo_dismiss_notice_$type" );

                $data = array( 'success' => true, 'nonce' => $nonce );

				wp_send_json_success( $data );
			}
		} else {
			wp_send_json_error( esc_html__( 'Invalid Request.', 'woo-feed' ) );
		}
		wp_die();
	}
}
add_action( 'wp_ajax_clear_cache_data', 'woo_feed_clear_cache_data' );

if ( ! function_exists( 'woo_feed_get_current_timestamp' ) ) {
	/**
	 * Current local timestamp UTC.
	 *
	 * @since 4.2.0
	 */
	function woo_feed_get_current_timestamp() {

		$interval = get_option( 'wf_schedule' );

		$current_timestamp = time() + $interval;

		return $current_timestamp;
	}
}

if ( ! function_exists( 'woo_feed_deep_term' ) ) {

	/**
	 * Get product terms list by hierarchical order.
	 *
	 * @param object $term product term object
	 *
	 * @return string
	 * @since 4.3.88+
	 */
	function woo_feed_deep_term( $term, $taxonomy ) {

		if ( $term->parent === 0 ) {
			return $term->name;
		}

		$parent_term = get_term_by( 'term_id', $term->parent, $taxonomy );

		return woo_feed_deep_term( $parent_term, $taxonomy ) . ' > ' . $term->name;
	}
}

if ( ! function_exists( 'woo_feed_parent_category' ) ) {

	/**
	 * Get product terms list by hierarchical order.
	 *
	 * @param WP_Term $term product term object
	 *
	 * @return WP_Term $term product parent term object
	 * @since 4.4.19
	 */
	function woo_feed_parent_category( $term, $taxonomy ) {

		if ( $term->parent === 0 ) {
			return $term;
		}

		$parent_term = get_term_by( 'term_id', $term->parent, $taxonomy );

		return woo_feed_parent_category( $parent_term, $taxonomy );
	}
}

if ( ! function_exists( 'woo_feed_get_terms_list_hierarchical_order' ) ) {
	/**
	 * Get product terms list by hierarchical order.
	 *
	 * @param int $id post id
	 * @param bool $full_path get full category path if true
	 * @param string $taxonomy post taxonomy
	 *
	 * @return false|string
	 * @return string
	 * @since 4.2.1
	 *
	 */
	function woo_feed_get_terms_list_hierarchical_order( $id, $full_path = true, $taxonomy = 'product_cat' ) {

		$terms = get_the_terms( $id, $taxonomy );

		if ( count( $terms ) ) {

			if ( $full_path ) {
				return woo_feed_deep_term( $terms[ count( $terms ) - 1 ], $taxonomy );
			} else {
				return $terms[ count( $terms ) - 1 ]->name;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'woo_feed_get_products_ids_of_reviews' ) ) {
	/**
	 * Get reviews product ids .
	 *
	 * @return array $review_products_ids // review products ids
	 * @since 4.0.5
	 */
	function woo_feed_get_products_ids_of_reviews() {
		$args                = array( 'post_type' => 'product' );
		$reviews             = get_comments( $args );
		$review_products_ids = wp_list_pluck( $reviews, 'comment_post_ID' );

		return ! empty( $review_products_ids ) && is_array( $review_products_ids ) ? array_unique( $review_products_ids ) : array();

	}
}

if ( ! function_exists( 'woo_feed_get_approved_reviews_data' ) ) {
	/**
	 * Get approved review's data.
	 *
	 * @return mixed
	 * @since 4.3.0
	 */
	function woo_feed_get_approved_reviews_data() {
		$approved_reviews = array();
		$product_ids      = woo_feed_get_products_ids_of_reviews();

		if ( ! empty( $product_ids ) && is_array( $product_ids ) ) {

			foreach ( $product_ids as $product_id ) {
				$reviews = get_comments(
					array(
						'post_id'          => $product_id,
						'comment_type'     => 'review',
						'comment_approved' => 1,
						'parent'           => 0,
					)
				);

				$product_name = get_the_title( $product_id );
				$product_link = get_the_permalink( $product_id );

				if ( is_array( $reviews ) && sizeof( $reviews ) > 0 ) {
					foreach ( $reviews as $item ) {
						$review                            = array();
						$review['review_ratings']          = get_comment_meta( $item->comment_ID, 'rating', true );
						$review['review_id']               = $item->comment_ID;
						$review['reviewer']['name']        = strip_tags( trim( ucfirst( $item->comment_author ) ) );
						$review['reviewer']['reviewer_id'] = $item->user_id;
						$review['review_timestamp']        = $item->comment_date;
						$review['review_product_name']     = $product_name;
						$review['review_url']              = $product_link;
						$review['review_product_url']      = $product_link;
						$review['title']                   = $product_name;
						$review['content']                 = $item->comment_content;

						//product ids
						$review['products']['product']['product_ids']['gtins']['gtin']   = '';
						$review['products']['product']['product_ids']['mpns']['mpn']     = '';
						$review['products']['product']['product_ids']['skus']['sku']     = '';
						$review['products']['product']['product_ids']['brands']['brand'] = '';
						$review['products']['product']['product_name']                   = $product_name;
						$review['products']['product']['product_url']                    = $product_link;
						array_push( $approved_reviews, $review );
					}
				}
			}
		}

		return $approved_reviews;

	}
}

if ( ! function_exists( 'woo_feed_save_black_friday_notice_2023_notice' ) ) {
	/**
	 * Update user meta to work ctx startup notice once.
	 *
	 * @param int _ajax_nonce nonce number.
	 *
	 * @since 4.3.31
	 * @author Nazrul Islam Nayan
	 */
	function woo_feed_save_black_friday_notice_2023_notice() {
		if ( isset( $_REQUEST['_wp_ajax_nonce'] ) && wp_verify_nonce( wp_unslash( $_REQUEST['_wp_ajax_nonce'] ), 'woo-feed-to-ctx-feed-notice' ) ) { //phpcs:ignore
			$user_id = get_current_user_id();
			if ( isset( $_REQUEST['clicked'] ) ) {
				$updated_user_meta = add_user_meta( $user_id, 'woo_feed_black_friday_notice_2023_dismissed', 'true', true );

				if ( $updated_user_meta ) {
					wp_send_json_success( esc_html__( 'User meta updated successfully.', 'woo-feed' ) );
				} else {
					wp_send_json_error( esc_html__( 'Something is wrong.', 'woo-feed' ) );
				}
			}
		} else {
			wp_send_json_error( esc_html__( 'Invalid Request.', 'woo-feed' ) );
		}
		wp_die();
	}
}
add_action( 'wp_ajax_woo_feed_save_black_friday_notice_2023_notice', 'woo_feed_save_black_friday_notice_2023_notice' );


if ( ! function_exists( 'woo_feed_save_halloween_notice' ) ) {
	/**
	 * Update user meta to work ctx startup notice once.
	 *
	 * @param int _ajax_nonce nonce number.
	 *
	 * @since 4.5.3
	 * @author Nashir Uddin
	 */
	function woo_feed_save_halloween_notice() {
		if ( isset( $_REQUEST['_wp_ajax_nonce'] ) && wp_verify_nonce( wp_unslash( $_REQUEST['_wp_ajax_nonce'] ), 'woo-feed-to-ctx-feed-halloween-nonce' ) ) { //phpcs:ignore
			$user_id = get_current_user_id();
			if ( isset( $_REQUEST['clicked'] ) ) {
				$updated_user_meta = add_user_meta( $user_id, 'woo_feed_halloween_notice_2023_dismissed', 'true', true );

				if ( $updated_user_meta ) {
					wp_send_json_success( esc_html__( 'User meta updated successfully.', 'woo-feed' ) );
				} else {
					wp_send_json_error( esc_html__( 'Something is wrong.', 'woo-feed' ) );
				}
			}
		} else {
			wp_send_json_error( esc_html__( 'Invalid Request.', 'woo-feed' ) );
		}
		wp_die();
	}
}
add_action( 'wp_ajax_woo_feed_save_halloween_notice', 'woo_feed_save_halloween_notice' );


if ( ! function_exists( 'woo_feed_save_christmas_notice_2023' ) ) {
	/**
	 * Update user meta to work ctx startup notice once.
	 *
	 * @param int _ajax_nonce nonce number.
	 *
	 * @since 4.5.15
	 * @author Md. Nashir Uddin
	 */
	function woo_feed_save_christmas_notice_2023() {
		if ( isset( $_REQUEST['_wp_ajax_nonce'] ) && wp_verify_nonce( wp_unslash( $_REQUEST['_wp_ajax_nonce'] ), 'woo-feed-to-ctx-feed-notice' ) ) { //phpcs:ignore
			$user_id = get_current_user_id();
			if ( isset( $_REQUEST['clicked'] ) ) {
				$updated_user_meta = add_user_meta( $user_id, 'woo_feed_christmas_notice_2023_dismissed', 'true', true );

				if ( $updated_user_meta ) {
					wp_send_json_success( esc_html__( 'User meta updated successfully.', 'woo-feed' ) );
				} else {
					wp_send_json_error( esc_html__( 'Something is wrong.', 'woo-feed' ) );
				}
			}
		} else {
			wp_send_json_error( esc_html__( 'Invalid Request.', 'woo-feed' ) );
		}
		wp_die();
	}
}
add_action( 'wp_ajax_woo_feed_save_christmas_notice_2023', 'woo_feed_save_christmas_notice_2023' );

if ( ! function_exists( 'woo_feed_hide_promotion' ) ) {
	/**
	 * Update option to hide promotion.
	 *
	 * @param int _ajax_nonce nonce number.
	 *
	 * @since 5.1.7
	 */
	function woo_feed_hide_promotion() {
		if ( isset( $_REQUEST['_ajax_nonce'] ) ) {
			$hide_promotion = update_option( 'woo_feed_hide_promotion', 1 );
			$data           = array(
				'msg' => 'Hide promotion updated successfully.',
			);
			if ( $hide_promotion ) {
				wp_send_json_success( $data );
			} else {
				wp_send_json_error( esc_html__( 'Something is wrong.', 'woo-feed' ) );
			}
		} else {
			wp_send_json_error( esc_html__( 'Invalid Request.', 'woo-feed' ) );
		}
		wp_die();
	}
}
add_action( 'wp_ajax_woo_feed_hide_promotion', 'woo_feed_hide_promotion' );

if ( ! function_exists( 'array_key_first' ) ) {
	/**
	 * Array first key.
	 *
	 * @param array $arr given array.
	 *
	 * @return mixed
	 * @since  4.3.0
	 */
	function array_key_first( array $arr ) {
		foreach ( $arr as $k => $unused ) {
			return $k;
		}

		return null;
	}
}

if ( ! function_exists( 'woo_feed_custom_taxonomy' ) ) {
	function woo_feed_custom_taxonomy() {
		$custom_fields            = woo_feed_product_custom_fields();
		$custom_taxonomies_filter = new Woo_Feed_Custom_Taxonomy_Filter( $custom_fields );
		$custom_taxonomies        = iterator_to_array( $custom_taxonomies_filter );

		$settings = woo_feed_get_options( 'all' );
		if ( isset( $settings['woo_feed_taxonomy'], $settings['woo_feed_identifier'] ) ) {
			$custom_attributes = array_merge( $settings['woo_feed_taxonomy'], $settings['woo_feed_identifier'] );
		} else {
			$custom_attributes = $settings['woo_feed_taxonomy'];
		}

		if ( isset( $custom_attributes['brand'] ) && 'enable' === $custom_attributes['brand'] ) {
			if ( ! empty( $custom_taxonomies ) ) {
				foreach ( $custom_taxonomies as $key => $value ) {
					$taxonomy_name = esc_html( $value[0] );

					$labels       = array(
						'name'                       => $taxonomy_name . ' ' . __( 'by CTX Feed', 'woo-feed' ),
						'singular_name'              => $taxonomy_name,
						'menu_name'                  => $taxonomy_name . 's ' . __( 'by CTX Feed', 'woo-feed' ),
						'all_items'                  => __( 'All', 'woo-feed' ) . ' ' . $taxonomy_name . 's',
						'parent_item'                => __( 'Parent', 'woo-feed' ) . $taxonomy_name,
						'parent_item_colon'          => __( 'Parent:', 'woo-feed' ) . $taxonomy_name . ':',
						'new_item_name'              => __( 'New', 'woo-feed' ) . ' ' . $taxonomy_name . ' ' . __( 'Name', 'woo-feed' ),
						'add_new_item'               => __( 'Add New', 'woo-feed' ) . ' ' . $taxonomy_name,
						'edit_item'                  => __( 'Edit', 'woo-feed' ) . ' ' . $taxonomy_name,
						'update_item'                => __( 'Update', 'woo-feed' ) . ' ' . $taxonomy_name,
						'separate_items_with_commas' => __( 'Separate', 'woo-feed' ) . ' ' . $taxonomy_name . ' ' . __( 'with commas', 'woo-feed' ),
						'search_items'               => __( 'Search', 'woo-feed' ) . ' ' . $taxonomy_name,
						'add_or_remove_items'        => __( 'Add or remove', 'woo-feed' ) . ' ' . $taxonomy_name,
						'choose_from_most_used'      => __( 'Choose from the most used', 'woo-feed' ) . ' ' . $taxonomy_name . 's',
					);
					$args         = array(
						'labels'             => $labels,
						'hierarchical'       => true,
						'public'             => true,
						'show_ui'            => true,
						'show_admin_column'  => false,
						'show_in_rest'       => true,
						'show_in_nav_menus'  => true,
						'show_tagcloud'      => true,
						'show_in_quick_edit' => false,
					);
					$taxonomy_key = sprintf( 'woo-feed-%s', strtolower( $key ) );

					register_taxonomy( $taxonomy_key, 'product', $args );
				}
			}
		}

	}

	add_action( 'init', 'woo_feed_custom_taxonomy' );
}

if ( ! function_exists( 'woo_feed_brand_term_radio_checklist' ) ) {

	/**
	 * Use radio inputs product brand taxonomies
	 *
	 * @param $args
	 *
	 * @return mixed
	 */
	function woo_feed_brand_term_radio_checklist( $args ) {
		if ( ! empty( $args['taxonomy'] ) && 'woo-feed-brand' === $args['taxonomy'] ) {
			if ( empty( $args['walker'] ) || is_a( $args['walker'], 'Walker' ) ) {
				if ( ! class_exists( 'Woo_Feed_Brand_Walker_Category_Radio_Checklist' ) ) {
					/**
					 * Custom walker for switching checkbox inputs to radio.
					 *
					 * @see Walker_Category_Checklist
					 */
					class Woo_Feed_Brand_Walker_Category_Radio_Checklist extends Walker_Category_Checklist {
						function walk( $elements, $max_depth, ...$args ) {
							$output = parent::walk( $elements, $max_depth, ...$args );
							$output = str_replace(
								array( 'type="checkbox"', "type='checkbox'" ),
								array( 'type="radio"', "type='radio'" ),
								$output
							);

							return $output;
						}
					}
				}
				$args['walker'] = new Woo_Feed_Brand_Walker_Category_Radio_Checklist();
			}
		}

		return $args;
	}

	add_filter( 'wp_terms_checklist_args', 'woo_feed_brand_term_radio_checklist' );
}

if ( ! function_exists( 'woo_feed_filter_woocommerce_structured_data_product' ) ) {

	$settings                   = woo_feed_get_options( 'all' );
	$overridden_structured_data = $settings['overridden_structured_data'];


	if ( 'on' === $overridden_structured_data ) {

		/**
		 * Removed woocommerce default schema structure
		 *
		 * @param $markup
		 * @param $product
		 *
		 * @return array $markup
		 * @since 4.3.6
		 */
		function woo_feed_filter_woocommerce_structured_data_product( $markup, $product ) {

			if ( ! $product instanceof WC_Product ) {
				return $markup;
			}

			$settings      = woo_feed_get_options( 'all' );
			$disable_mpn   = $settings['disable_mpn'];
			$disable_brand = $settings['disable_brand'];

			$description           = apply_filters( 'woo_feed_schema_description', wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ), $product );
			$markup['description'] = $description;

			if ( 'enable' === $disable_brand ) {
				$brand_term = wp_get_post_terms( $product->get_id(), 'woo-feed-brand', array( 'fields' => 'names' ) );

				if ( ! is_wp_error( $brand_term ) ) {
					if ( isset( $brand_term[0] ) ) {
						$markup['brand']['name'] = $brand_term[0];
					}
				}
			}

			//get price and currency to work with all the compatible currency plugin
			$price    = $product->get_price();
			$currency = get_woocommerce_currency();

			//filter schema price & currency
			$price                        = apply_filters( 'woo_feed_schema_product_price', $price, $markup, $product );
			$currency                     = apply_filters( 'woo_feed_schema_product_currency', $currency, $markup, $product );
			$markup['offers'][0]['price'] = $price;
			$markup['offers'][0]['priceSpecification']['price']         = $price;
			$markup['offers'][0]['priceSpecification']['priceCurrency'] = $currency;
			$markup['offers'][0]['priceCurrency']                       = $currency;

			// Check if we have mpn data.
			if ( 'enable' === $disable_mpn && ! empty( $mpn ) ) {
				$mpn           = $product->get_meta( 'woo_feed_mpn' );
				$markup['mpn'] = $mpn;
			}

			$markup = apply_filters( 'woo_feed_after_wc_product_structured_data', $markup, $product );

			return $markup;
		}

		add_filter( 'woocommerce_structured_data_product', 'woo_feed_filter_woocommerce_structured_data_product', 10, 2 );

	}
}

if ( ! function_exists( 'woo_feed_trim_attribute' ) ) {
	/**
	 * Trim attribute by specific sign
	 *
	 * @param $attribute string feed attribute
	 *
	 * @return mixed
	 */
	function woo_feed_trim_attribute( $attribute ) {
		return str_replace( '_', ' ', $attribute );
	}
}

// Facebook pixel integration
if ( ! function_exists( 'woo_feed_facebook_pixel_init' ) ) {

	function woo_feed_facebook_pixel_init() {
		new WebAppick\Feed\Tracker\Facebook\Pixel();
	}

	add_action( 'init', 'woo_feed_facebook_pixel_init' );

}

// Google Remarketing integration
if ( ! function_exists( 'woo_feed_google_remarketing_init' ) ) {

	function woo_feed_google_remarketing_init() {
		new WebAppick\Feed\Tracker\Google\Remarketing();
	}

	add_action( 'init', 'woo_feed_google_remarketing_init' );

}

if ( ! function_exists( 'woo_feed_filter_dropdown_attributes' ) ) {
	/**
	 * Woo Feed Filter Dropdown Attributes
	 *
	 * @param array $default_attr default attributes
	 * @param array $merchants merchant names
	 *
	 * @return array $filtered_attributes
	 *
	 * @author Nazrul Islam Nayan
	 * @updated 23-12-2020
	 *
	 * @since 4.3.11
	 */
	function woo_feed_filter_dropdown_attributes( $default_attr, $merchants ) {
		$filtered_attributes                          = $default_attr;
		$snapchat_additional_attr                     = array();
		$snapchat_additional_attr['--18']             = 'Snapchat Additional Attributes';
		$snapchat_additional_attr['image_link']       = 'Image Link';
		$snapchat_additional_attr['icon_media_url']   = 'Icon Media Url[icon_media_url]';
		$snapchat_additional_attr['ios_app_name']     = 'IOS App Name[ios_app_name]';
		$snapchat_additional_attr['ios_app_store_id'] = 'IOS App Store ID[ios_app_store_id]';
		$snapchat_additional_attr['ios_url']          = 'IOS Url[ios_url]';
		$snapchat_additional_attr['android_app_name'] = 'Android App Name[android_app_name]';
		$snapchat_additional_attr['android_package']  = 'Android Package[android_package]';
		$snapchat_additional_attr['android_url']      = 'Android URL[android_url]';
		$snapchat_additional_attr['mobile_link']      = 'Mobile Link[mobile_link]';
		$snapchat_additional_attr['---18']            = '';

		//filtering attributes for pinterest merchant
		if ( in_array( 'pinterest', $merchants ) ) {
			if ( isset( $default_attr['ads_redirect'] ) ) {
				if ( array_key_exists( 'ads_redirect', $default_attr ) ) {
					$keys = array_keys( $default_attr );
					$keys[ array_search( 'ads_redirect', $keys ) ] = 'ads_link';
					$filtered_attributes                           = array_combine( $keys, $default_attr );
					$filtered_attributes['ads_link']               = 'Ads Link[ads_link]';
				}
			}
		}

		//filtering attributes for snapchat merchant
		if ( in_array( 'snapchat', $merchants ) ) {
			$filtered_attributes = array_merge( $filtered_attributes, $snapchat_additional_attr );
		}

		//filtering attributes for facebook merchant
		if ( in_array( 'facebook', $merchants ) ) {
			if ( isset( $filtered_attributes['excluded_destination'] ) ) {
				$facebook_attributes                                 = array();
				$facebook_attributes['quantity_to_sell_on_facebook'] = 'Quantity to sell on facebook [quantity_to_sell_on_facebook]';
				$filtered_attributes                                 = woo_feed_array_insert_after( $filtered_attributes, 'excluded_destination', $facebook_attributes );
			}
		}

		return $filtered_attributes;
	}

	add_filter( 'woo_feed_filter_dropdown_attributes', 'woo_feed_filter_dropdown_attributes', 2, 10 );
}


if ( ! function_exists( 'woo_feed_countries' ) ) {
	/**
	 * Woo Feed Country List
	 *
	 * @return array
	 * @since 4.3.16
	 * @author Nazrul Islam Nayan
	 * @updated 10-01-2021
	 *
	 */

	function woo_feed_countries() {

		return array(
			'AF' => 'Afghanistan',
			'AX' => 'Aland Islands',
			'AL' => 'Albania',
			'DZ' => 'Algeria',
			'AS' => 'American Samoa',
			'AD' => 'Andorra',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctica',
			'AG' => 'Antigua and Barbuda',
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AW' => 'Aruba',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BS' => 'Bahamas the',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BB' => 'Barbados',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BZ' => 'Belize',
			'BJ' => 'Benin',
			'BM' => 'Bermuda',
			'BT' => 'Bhutan',
			'BO' => 'Bolivia',
			'BA' => 'Bosnia and Herzegovina',
			'BW' => 'Botswana',
			'BV' => 'Bouvet Island (Bouvetoya)',
			'BR' => 'Brazil',
			'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
			'VG' => 'British Virgin Islands',
			'BN' => 'Brunei Darussalam',
			'BG' => 'Bulgaria',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodia',
			'CM' => 'Cameroon',
			'CA' => 'Canada',
			'CV' => 'Cape Verde',
			'KY' => 'Cayman Islands',
			'CF' => 'Central African Republic',
			'TD' => 'Chad',
			'CL' => 'Chile',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos (Keeling) Islands',
			'CO' => 'Colombia',
			'KM' => 'Comoros the',
			'CD' => 'Congo',
			'CG' => 'Congo the',
			'CK' => 'Cook Islands',
			'CR' => 'Costa Rica',
			'CI' => 'Cote d\'Ivoire',
			'HR' => 'Croatia',
			'CU' => 'Cuba',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'DK' => 'Denmark',
			'DJ' => 'Djibouti',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'EC' => 'Ecuador',
			'EG' => 'Egypt',
			'SV' => 'El Salvador',
			'GQ' => 'Equatorial Guinea',
			'ER' => 'Eritrea',
			'EE' => 'Estonia',
			'ET' => 'Ethiopia',
			'FO' => 'Faroe Islands',
			'FK' => 'Falkland Islands (Malvinas)',
			'FJ' => 'Fiji the Fiji Islands',
			'FI' => 'Finland',
			'FR' => 'France',
			'GF' => 'French Guiana',
			'PF' => 'French Polynesia',
			'TF' => 'French Southern Territories',
			'GA' => 'Gabon',
			'GM' => 'Gambia the',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GR' => 'Greece',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GG' => 'Guernsey',
			'GN' => 'Guinea',
			'GW' => 'Guinea-Bissau',
			'GY' => 'Guyana',
			'HT' => 'Haiti',
			'HM' => 'Heard Island and McDonald Islands',
			'VA' => 'Holy See (Vatican City State)',
			'HN' => 'Honduras',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Iran',
			'IQ' => 'Iraq',
			'IE' => 'Ireland',
			'IM' => 'Isle of Man',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'JM' => 'Jamaica',
			'JP' => 'Japan',
			'JE' => 'Jersey',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KI' => 'Kiribati',
			'KP' => 'Korea',
			'KR' => 'Korea',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyz Republic',
			'LA' => 'Lao',
			'LV' => 'Latvia',
			'LB' => 'Lebanon',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libyan Arab Jamahiriya',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MO' => 'Macao',
			'MK' => 'Macedonia',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malta',
			'MH' => 'Marshall Islands',
			'MQ' => 'Martinique',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MX' => 'Mexico',
			'FM' => 'Micronesia',
			'MD' => 'Moldova',
			'MC' => 'Monaco',
			'MN' => 'Mongolia',
			'ME' => 'Montenegro',
			'MS' => 'Montserrat',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar',
			'NA' => 'Namibia',
			'NR' => 'Nauru',
			'NP' => 'Nepal',
			'AN' => 'Netherlands Antilles',
			'NL' => 'Netherlands',
			'NC' => 'New Caledonia',
			'NZ' => 'New Zealand',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'MP' => 'Northern Mariana Islands',
			'NO' => 'Norway',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PW' => 'Palau',
			'PS' => 'Palestinian Territory',
			'PA' => 'Panama',
			'PG' => 'Papua New Guinea',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn Islands',
			'PL' => 'Poland',
			'PT' => 'Portugal, Portuguese Republic',
			'PR' => 'Puerto Rico',
			'QA' => 'Qatar',
			'RE' => 'Reunion',
			'RO' => 'Romania',
			'RU' => 'Russian Federation',
			'RW' => 'Rwanda',
			'BL' => 'Saint Barthelemy',
			'SH' => 'Saint Helena',
			'KN' => 'Saint Kitts and Nevis',
			'LC' => 'Saint Lucia',
			'MF' => 'Saint Martin',
			'PM' => 'Saint Pierre and Miquelon',
			'VC' => 'Saint Vincent and the Grenadines',
			'WS' => 'Samoa',
			'SM' => 'San Marino',
			'ST' => 'Sao Tome and Principe',
			'SA' => 'Saudi Arabia',
			'SN' => 'Senegal',
			'RS' => 'Serbia',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapore',
			'SK' => 'Slovakia (Slovak Republic)',
			'SI' => 'Slovenia',
			'SB' => 'Solomon Islands',
			'SO' => 'Somalia, Somali Republic',
			'ZA' => 'South Africa',
			'GS' => 'South Georgia and the South Sandwich Islands',
			'ES' => 'Spain',
			'LK' => 'Sri Lanka',
			'SD' => 'Sudan',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard & Jan Mayen Islands',
			'SZ' => 'Swaziland',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'SY' => 'Syrian Arab Republic',
			'TW' => 'Taiwan',
			'TJ' => 'Tajikistan',
			'TZ' => 'Tanzania',
			'TH' => 'Thailand',
			'TL' => 'Timor-Leste',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinidad and Tobago',
			'TN' => 'Tunisia',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'TC' => 'Turks and Caicos Islands',
			'TV' => 'Tuvalu',
			'UG' => 'Uganda',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'GB' => 'United Kingdom',
			'US' => 'United States',
			'UM' => 'United States Minor Outlying Islands',
			'VI' => 'United States Virgin Islands',
			'UY' => 'Uruguay, Eastern Republic of',
			'UZ' => 'Uzbekistan',
			'VU' => 'Vanuatu',
			'VE' => 'Venezuela',
			'VN' => 'Vietnam',
			'WF' => 'Wallis and Futuna',
			'EH' => 'Western Sahara',
			'YE' => 'Yemen',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe',
		);

	}
}


if ( ! function_exists( 'woo_feed_positioning_attribute_value' ) ) {
	/**
	 * Positioning new associative array in attribute's dropdown list
	 *
	 * @param $array array Main attribute array.
	 * @param $key string Targeted main array key, after that key index the given associative array should come.
	 * @param $input_array array Given associative array.
	 *
	 * @return array $array
	 * @since 4.3.18
	 * @author Nazrul Islam Nayan
	 * @updated 12-01-2021
	 *
	 */
	function woo_feed_positioning_attribute_value( $array, $key, $input_array ) {
		$keys  = array_keys( $array );
		$index = array_search( $key, $keys, true );
		$pos   = false === $index ? count( $array ) : $index + 1;

		$array = array_slice( $array, 0, $pos, true ) + $input_array + array_slice( $array, $pos, count( $array ) - 1, true );

		return $array;
	}
}

if ( ! function_exists( 'woo_feed_get_feed_file_list' ) ) {
	function woo_feed_get_feed_file_list() {
		global $wpdb;
		$feed_data = $wpdb->get_results( $wpdb->prepare( "SELECT option_name FROM $wpdb->options WHERE option_name like %s", 'wf_feed_%' ), ARRAY_A ); // phpcs:ignore
		$feed_urls = array();
		if ( ! empty( $feed_data ) and is_array( $feed_data ) ) {
			foreach ( $feed_data as $key => $data ) {
				$feed_info   = maybe_unserialize( get_option( $data['option_name'] ) );
				$feed_urls[] = $feed_info['url'];
			}
		}

		return $feed_urls;
	}
}

if ( ! function_exists( 'woo_feed_exclude_feed_from_wp_rocket_cache' ) ) {
	/**
	 * Exclude Feed file URL form WP Rocket caching
	 *
	 * @param $files
	 *
	 * @return array
	 */
	function woo_feed_exclude_feed_from_wp_rocket_cache( $files ) {
		return array_merge(
			$files,
			array(
				'/wp-content/uploads/woo-feed/(.*)',
			)
		);
	}
}

if ( ! function_exists( 'woo_feed_exclude_feed_from_litespeed_cache' ) ) {
	/**
	 * Exclude Feed file URL form LiteSpeed caching
	 *
	 * @return array|false
	 */
	function woo_feed_exclude_feed_from_litespeed_cache() {
		if ( ! class_exists( 'LiteSpeed\Core' ) || ! defined( 'LSCWP_DIR' ) ) {
			return false;
		}

		$litespeed_ex_paths = maybe_unserialize( get_option( 'litespeed.conf.cdn-exc' ) );
		if ( $litespeed_ex_paths && is_array( $litespeed_ex_paths ) && ! in_array( '/wp-content/uploads/woo-feed', $litespeed_ex_paths ) ) {
			$litespeed_ex_paths = array_merge(
				$litespeed_ex_paths,
				array( '/wp-content/uploads/woo-feed' )
			);
			update_option( 'litespeed.conf.cdn-exc', $litespeed_ex_paths );
		}

		return false;
	}
}

if ( ! function_exists( 'woo_feed_exclude_feed_from_wp_fastest_cache' ) ) {
	/**
	 * Exclude Feed file URL form WP Fastest caching
	 *
	 * @return array|false
	 */
	function woo_feed_exclude_feed_from_wp_fastest_cache() {

		if ( ! class_exists( 'WpFastestCache' ) ) {
			return false;
		}

		$wp_fastest_cache_ex_paths = json_decode( get_option( 'WpFastestCacheExclude' ) );
		if ( $wp_fastest_cache_ex_paths && is_array( $wp_fastest_cache_ex_paths ) ) {

			$feed_path_exist = false;
			foreach ( $wp_fastest_cache_ex_paths as $key => $path ) {
				if ( 'woo-feed' === $path->content ) {
					$feed_path_exist = true;
					break;
				}
			}

			if ( ! $feed_path_exist ) {
				$new_rule          = new stdClass();
				$new_rule->prefix  = 'contain';
				$new_rule->content = 'woo-feed';
				$new_rule->type    = 'page';

				$wp_fastest_cache_ex_paths = array_merge(
					$wp_fastest_cache_ex_paths,
					array( $new_rule )
				);

				update_option( 'WpFastestCacheExclude', wp_json_encode( $wp_fastest_cache_ex_paths ) );
			}
		} elseif ( empty( $wp_fastest_cache_ex_paths ) ) {
			$wp_fastest_cache_ex_paths = array();
			$new_rule                  = new stdClass();
			$new_rule->prefix          = 'contain';
			$new_rule->content         = 'woo-feed';
			$new_rule->type            = 'page';

			$wp_fastest_cache_ex_paths = array_merge(
				$wp_fastest_cache_ex_paths,
				array( $new_rule )
			);

			update_option( 'WpFastestCacheExclude', wp_json_encode( $wp_fastest_cache_ex_paths ) );
		}

		return false;
	}
}

if ( ! function_exists( 'woo_feed_exclude_feed_from_wp_super_cache' ) ) {
	/**
	 * Exclude Feed file URL form WP Super caching
	 *
	 * @return array|false
	 */
	function woo_feed_exclude_feed_from_wp_super_cache() {

		if ( ! function_exists( 'wpsc_init' ) ) {
			return false;
		}

		$wp_super_ex_paths = get_option( 'ossdl_off_exclude' );
		if ( $wp_super_ex_paths && strpos( $wp_super_ex_paths, 'woo-feed' ) === false ) {
			$wp_super_ex_paths = explode( ',', $wp_super_ex_paths );
			$wp_super_ex_paths = array_merge( $wp_super_ex_paths, array( 'woo-feed' ) );
			update_option( 'ossdl_off_exclude', implode( ',', $wp_super_ex_paths ) );
		}

		return false;
	}
}

if ( ! function_exists( 'woo_feed_exclude_feed_from_breeze_cache' ) ) {
	/**
	 * Exclude Feed file URL form BREEZE caching
	 *
	 * @return array|false
	 */
	function woo_feed_exclude_feed_from_breeze_cache() {

		if ( ! class_exists( 'Breeze_Admin' ) ) {
			return false;
		}

		$breeze_settings = maybe_unserialize( get_option( 'breeze_cdn_integration' ) );
		if ( is_array( $breeze_settings ) ) {
			$woo_feed_files                         = array( '.xml', '.csv', '.tsv', '.txt', '.xls' );
			$woo_feed_files                         = array_unique( array_merge( $woo_feed_files, $breeze_settings['cdn-exclude-content'] ) );
			$breeze_settings['cdn-exclude-content'] = $woo_feed_files;
			update_option( 'breeze_cdn_integration', $breeze_settings );
		}

		return false;
	}
}

if ( ! function_exists( 'woo_feed_exclude_feed_from_wp_optimize_cache' ) ) {
	/**
	 * Exclude Feed file URL form WP Optimize caching
	 *
	 * @return array|false
	 */
	function woo_feed_exclude_feed_from_wp_optimize_cache() {

		if ( ! class_exists( 'WP_Optimize' ) ) {
			return false;
		}

		$wp_optimize_ex_paths = maybe_unserialize( get_option( 'wpo_cache_config' ) );
		if ( isset( $wp_optimize_ex_paths['enable_page_caching'] ) && $wp_optimize_ex_paths['enable_page_caching'] ) { // If page Caching enabled
			if ( is_array( $wp_optimize_ex_paths ) && ! in_array( '/wp-content/uploads/woo-feed', $wp_optimize_ex_paths['cache_exception_urls'] ) ) {
				$woo_feed_ex_path['cache_exception_urls'] = array( '/wp-content/uploads/woo-feed' );
				$wp_optimize_ex_paths                     = array_merge_recursive(
					$wp_optimize_ex_paths,
					$woo_feed_ex_path
				);
				update_option( 'wpo_cache_config', $wp_optimize_ex_paths );
			}
		}

		return false;
	}
}

if ( ! function_exists( 'woo_feed_exclude_feed_from_cache_enabler_cache' ) ) {
	/**
	 * Exclude Feed file URL form Cache Enabler caching
	 *
	 * @return array|false
	 */
	function woo_feed_exclude_feed_from_cache_enabler_cache() {

		if ( ! class_exists( 'Cache_Enabler' ) ) {
			return false;
		}

		$cache_enabler_ex_paths = maybe_unserialize( get_option( 'cache_enabler' ) );
		if ( isset( $cache_enabler_ex_paths['excluded_page_paths'] ) && empty( $cache_enabler_ex_paths['excluded_page_paths'] ) ) {
			$cache_enabler_ex_paths['excluded_page_paths'] = '/wp-content/uploads/woo-feed/';
			update_option( 'cache_enabler', $cache_enabler_ex_paths );
		}

		return false;
	}
}

if ( ! function_exists( 'woo_feed_exclude_feed_from_swift_performance_cache' ) ) {
	/**
	 * Exclude Feed file URL form Swift Performance caching
	 *
	 * @return array|false
	 */
	function woo_feed_exclude_feed_from_swift_performance_cache() {

		if ( ! class_exists( 'Swift_Performance_Lite' ) ) {
			return false;
		}

		$swift_perform_ex_paths = maybe_unserialize( get_option( 'swift_performance_options' ) );

		if ( $swift_perform_ex_paths && isset( $swift_perform_ex_paths['exclude-strings'] ) ) {
			$exclude_strings = $swift_perform_ex_paths['exclude-strings'];
			if ( is_array( $exclude_strings ) && ! in_array( '/wp-content/uploads/woo-feed', $exclude_strings ) ) {
				$woo_feed_ex_path['exclude-strings'] = array( '/wp-content/uploads/woo-feed' );
				$swift_perform_ex_paths              = array_merge_recursive(
					$swift_perform_ex_paths,
					$woo_feed_ex_path
				);
			} else {
				$swift_perform_ex_paths['exclude-strings'] = array( '/wp-content/uploads/woo-feed' );
			}
			update_option( 'swift_performance_options', $swift_perform_ex_paths );
		} elseif ( empty( $swift_perform_ex_paths ) ) {
			$swift_perform_ex_paths['exclude-strings'] = array( '/wp-content/uploads/woo-feed' );
			update_option( 'swift_performance_options', $swift_perform_ex_paths );
		}

		return false;
	}
}

if ( ! function_exists( 'woo_feed_exclude_feed_from_speed_booster_cache' ) ) {
	/**
	 * Exclude Feed file URL form Speed Booster Pack caching
	 *
	 * @return array|false
	 */
	function woo_feed_exclude_feed_from_speed_booster_cache() {

		if ( ! class_exists( 'Speed_Booster_Pack' ) ) {
			return false;
		}

		$feed_files             = array();
		$speed_booster_settings = maybe_unserialize( get_option( 'sbp_options' ) );
		if ( isset( $speed_booster_settings['caching_exclude_urls'] ) ) {
			$feed_files           = woo_feed_get_feed_file_list();
			$caching_exclude_urls = $speed_booster_settings['caching_exclude_urls'];
			if ( ! empty( $caching_exclude_urls ) ) {
				if ( ! empty( $feed_files ) ) {
					foreach ( $feed_files as $key => $file ) {
						$file = str_replace( array( 'http://', 'https://' ), '', $file );
						if ( ! in_array( $file, explode( "\n", $caching_exclude_urls ) ) ) {
							$caching_exclude_urls .= "\n" . $file;
						}
					}
				}
			} else {
				$caching_exclude_urls = str_replace( array( 'http://', 'https://' ), '', implode( "\n", $feed_files ) );
			}
			$speed_booster_settings['caching_exclude_urls'] = $caching_exclude_urls;
			update_option( 'sbp_options', $speed_booster_settings );
		}

		//TODO CDN extension exclude
		return false;
	}
}

if ( ! function_exists( 'woo_feed_exclude_feed_from_comet_cache' ) ) {
	/**
	 * Exclude Feed file URL form Comet Cache caching
	 *
	 * @return array|false
	 */
	function woo_feed_exclude_feed_from_comet_cache() {
		if ( ! is_plugin_active( 'comet-cache/comet-cache.php' ) ) {
			return false;
		}

		$comet_cache_settings = maybe_unserialize( get_option( 'comet_cache_options' ) );

		if ( $comet_cache_settings && isset( $comet_cache_settings['exclude_uris'] ) ) {
			$exclude_uris = $comet_cache_settings['exclude_uris'];
			if ( strpos( $exclude_uris, '/wp-content/uploads/woo-feed' ) === false ) {
				$exclude_uris                        .= "\n/wp-content/uploads/woo-feed";
				$comet_cache_settings['exclude_uris'] = $exclude_uris;
				update_option( 'comet_cache_options', $comet_cache_settings );
			}
		}

		return false;
	}
}

if ( ! function_exists( 'woo_feed_exclude_feed_from_hyper_cache' ) ) {
	/**
	 * Exclude Feed file URL form Swift Performance caching
	 *
	 * @return array|false
	 */
	function woo_feed_exclude_feed_from_hyper_cache() {

		if ( ! class_exists( 'HyperCache' ) ) {
			return false;
		}

		$hyper_cache_settings = maybe_unserialize( get_option( 'hyper-cache' ) );
		if ( $hyper_cache_settings && isset( $hyper_cache_settings['reject_uris'] ) ) {
			$exclude_strings = $hyper_cache_settings['reject_uris'];
			if ( is_array( $exclude_strings ) && ! in_array( '/wp-content/uploads/woo-feed', $exclude_strings ) ) {
				$woo_feed_ex_path['reject_uris']         = array( '/wp-content/uploads/woo-feed' );
				$woo_feed_ex_path['reject_uris_enabled'] = 1;
				$hyper_cache_settings                    = array_merge_recursive(
					$hyper_cache_settings,
					$woo_feed_ex_path
				);
			}
			update_option( 'hyper-cache', $hyper_cache_settings );
		}

		return false;
	}
}

if ( ! function_exists( 'woo_feed_wp_options' ) ) {
	function woo_feed_wp_options() {
		if ( isset( $_GET['action'] ) && 'add-option' == $_GET['action'] ) {
			if ( count( $_POST ) && isset( $_POST['wpfp_option'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
				check_admin_referer( 'woo-feed-add-option' );

				$options   = get_option( 'wpfp_option', array() );
				$newOption = sanitize_text_field( $_POST['wpfp_option'] );
				$id        = explode( '-', $newOption );
				if ( false !== array_search( $id[0], array_column( $options, 'option_id' ) ) ) { // found
					update_option( 'wpf_message', esc_html__( 'Option Already Added.', 'woo-feed' ), false );
					wp_safe_redirect( admin_url( 'admin.php?page=webappick-wp-options&wpf_message=error' ) );
					die();
				} else {
					$options[ $id[0] ] = array(
						'option_id'   => $id[0],
						'option_name' => 'wf_option_' . str_replace( $id[0] . '-', '', $newOption ),
					);
					update_option( 'wpfp_option', $options, false );
					update_option( 'wpf_message', esc_html__( 'Option Successfully Added.', 'woo-feed' ), false );
					wp_safe_redirect( admin_url( 'admin.php?page=webappick-wp-options&wpf_message=success' ) );
					die();
				}
			}
			require WOO_FEED_FREE_ADMIN_PATH . 'partials/woo-feed-add-option.php';
		} else {
			require WOO_FEED_FREE_ADMIN_PATH . 'partials/woo-feed-option-list.php';
		}
	}
}

if ( ! function_exists( 'woo_feed_get_price_with_tax' ) ) {


	/**
	 * Get price with tax
	 *
	 * @param $price
	 * @param WC_Product $product product object
	 *
	 * @return float|mixed|string|void
	 */
	function woo_feed_get_price_with_tax( $price, $product ) {

		if ( woo_feed_wc_version_check( 3.0 ) ) {
			return wc_get_price_including_tax( $product, array( 'price' => $price ) );
		} else {
			return $product->get_price_including_tax( 1, $price );
		}

		return apply_filters( 'woo_feed_price_with_tax', $price, $product );
	}
}

if ( ! function_exists( 'woo_feed_get_dynamic_discounted_product_price' ) ) {

	/**
	 * Get price with dynamic discount
	 *
	 * @param WC_Product|WC_Product_Variable $product product object
	 * @param $price
	 * @param $config
	 * @param bool $tax product taxable or not
	 *
	 * @return mixed $price
	 */
	function woo_feed_get_dynamic_discounted_product_price( $price, $product, $feedConfig, $tax ) {
		$base_price               = $price;
		$discount_plugin_activate = false;

		/**
		 * PLUGIN: Discount Rules for WooCommerce
		 * URL: https://wordpress.org/plugins/woo-discount-rules/
		 */
		if ( is_plugin_active( 'woo-discount-rules/woo-discount-rules.php' ) ) {
			$discount_plugin_activate = true;

			//WPML multicurrency
			$wpml_active_currency_status = (is_plugin_active( 'woocommerce-multilingual/wpml-woocommerce.php' ) && $feedConfig['feedCurrency'] !== get_woocommerce_currency());
			if ( $wpml_active_currency_status ) {
				//Wpml custom price start
				$wpml_product_id = $product->get_id();
				$wpml_settings = get_option( 'icl_sitepress_settings' );
				$wpml_default_language = $wpml_settings['default_language'];
				global $wpdb;
				$wpml_table_name = $wpdb->prefix . 'icl_translations';
				$sql = $wpdb->prepare("SELECT `trid` FROM $wpml_table_name  WHERE `element_id` = %d",  $wpml_product_id );
				$result = $wpdb->get_results( $sql );
				$wpml_trid = $result[0]->trid;
				$sql = $wpdb->prepare("SELECT `element_id` FROM $wpml_table_name  WHERE `trid` = %d AND `language_code` = %s",  $wpml_trid, $wpml_default_language );
				$result = $wpdb->get_results( $sql );
				$original_id = $result[0]->element_id;

				$wpml_regular_price = get_post_meta($original_id, '_regular_price_' . $feedConfig['feedCurrency'], false );
				$wpml_sale_price = get_post_meta($original_id, '_sale_price_' . $feedConfig['feedCurrency'], false );

				$wpml_data     = get_option( '_wcml_settings' );
				$exchange_rate = $wpml_data['currency_options'][ $feedConfig['feedCurrency'] ]['rate'];

				if( count( $wpml_regular_price ) >= 1 ) {
					$wpml_regular_price = floatval($wpml_regular_price[0]) / floatval( $exchange_rate );
					$wpml_sale_price = floatval($wpml_sale_price[0]) / floatval( $exchange_rate );
				}
				//Wpml custom price end

				if ( $exchange_rate !== 0 ) {
					$exchange_rate = $base_price = floatval( $price ) / floatval( $exchange_rate );
				}
			} else {
				$exchange_rate = $product->get_price();
			}


			if ( class_exists( 'Wdr\App\Controllers\Configuration' ) ) {
				$config = Wdr\App\Controllers\Configuration::getInstance()->getConfig( 'calculate_discount_from', 'sale_price' );
				if ( isset( $config ) && ! empty( $config ) ) {
					if ( 'regular_price' === $config ) {
						$price = $product->get_regular_price();
						if( $wpml_active_currency_status ) {
							$price = $wpml_regular_price;
						}
					} elseif ( 'sale_price' === $config ) {
						$price = $product->get_sale_price();
						if( $wpml_active_currency_status ) {
							$price = $wpml_sale_price;
						}
					} else {
						$price = $exchange_rate;
					}
				} else {
					$price = $exchange_rate;
				}

				if ( $product->is_type( 'variable' ) ) {
					$min = $product->get_variation_price( 'min', false );
					$max = $product->get_variation_price( 'max', false );

					$price = $min;
					if ( $max === $base_price ) {
						$price = $max;
					}
				}

				$price = apply_filters( 'advanced_woo_discount_rules_get_product_discount_price_from_custom_price', false, $product, 1, $price, 'discounted_price', true, true );

				if ( empty( $price ) ) {
					$price = $base_price;
				}

				if ( ! isset( $feedConfig['feedCurrency'] ) ) {
					$feedConfig['feedCurrency'] = get_woocommerce_currency();
				}

				$price = apply_filters( 'wcml_raw_price_amount', $price, $feedConfig['feedCurrency'] );
			}
		}

		/**
		 * PLUGIN: Dynamic Pricing With Discount Rules for WooCommerce
		 * URL: https://wordpress.org/plugins/aco-woo-dynamic-pricing/
		 *
		 * This plugin does not apply discount on product page.
		 *
		 * Don't apply discount manually.
		 */

		if (is_plugin_active('aco-woo-dynamic-pricing/start.php')) {
			$discount_plugin_activate = true;
			if (class_exists('AWDP_Discount')) {

				$price = AWDP_Discount::instance()->wdpWCPAPrice($product->get_price(), $product);
				if( isset( $price['price'] ) ){
					if( $price['price'] == '' ) {
						$sale_price = $price['originalPrice'];
					} else {
						$sale_price = $price['price'];
					}
					$price = $sale_price;
				}
			}
		}

		/**
		 * PLUGIN: Conditional Discounts for WooCommerce
		 * URL: https://wordpress.org/plugins/woo-advanced-discounts/
		 *
		 * NOTE:* Automatically apply discount to $product->get_sale_price() method.
		 */
		if (is_plugin_active('woo-advanced-discounts/wad.php')) {
			$discount_plugin_activate = true;
			$discount_amount = 0;
//			global $wad_discounts;
			$wad_discounts = wad_get_active_discounts( true );

			if (isset($wad_discounts["product"])) {
				$price = $product->get_price();
				foreach ($wad_discounts["product"] as $discount_id ) {

					$wad_obj = new WAD_Discount( $discount_id );
					$is_disable = $wad_obj->settings['disable-on-product-pages'];
					if( $is_disable === "no") {

						$discount_products_list = $wad_obj->products_list->get_products(true);
						if ( is_array( $discount_products_list ) && count( $discount_products_list ) > 0 ) {
							if (in_array($product->get_id(), $discount_products_list)) {

								if ( isset($wad_obj->settings ) ) {
									$settings = $wad_obj->settings;
									$discount_type = $wad_obj->settings['action'];

									if ( false !== strpos( $discount_type, 'fixed' ) ) {
										$discount_amount = (float)$wad_obj->get_discount_amount( $price );
									} elseif (false !== strpos($discount_type, 'percentage')) {
										$percentage = $settings['percentage-or-fixed-amount'];
										$discount_amount = ($price * ($percentage / 100));
									}
								}

							}
						} else {
							if ( $wad_obj->is_applicable( $product->get_id() ) ) {
								if (isset($wad_obj->settings)) {
									$settings = $wad_obj->settings;
									$discount_type = $wad_obj->settings['action'];

									if (false !== strpos($discount_type, 'fixed')) {
										$discount_amount = (float)$wad_obj->get_discount_amount($price);
									} elseif (false !== strpos($discount_type, 'percentage')) {
										$percentage = $settings['percentage-or-fixed-amount'];
										$discount_amount = ($price * ($percentage / 100));
									}
								}
							}
						}
						$price = (float)$price - (float)$discount_amount;

					}

				}
//				$price = (float) $product->get_price() - (float) $discount_amount;
			}

		}

		/**
		 * PLUGIN: Pricing Deals for WooCommerce
		 * URL: https://wordpress.org/plugins/pricing-deals-for-woocommerce/
		 */
		if ( is_plugin_active( 'pricing-deals-for-woocommerce/vt-pricing-deals.php' ) ) {
			$discount_plugin_activate = true;
			if ( class_exists( 'VTPRD_Controller' ) ) {
				global $vtprd_rules_set;
				$vtprd_rules_set = maybe_unserialize(get_option( 'vtprd_rules_set' ));
				if ( ! empty( $vtprd_rules_set ) && is_array( $vtprd_rules_set ) ) {
					foreach ( $vtprd_rules_set as $key =>$vtprd_rule_set ) {
							$status = $vtprd_rule_set->rule_on_off_sw_select;
							if ( 'on' === $status || 'onForever' === $status ) {
								$discount_type = $vtprd_rule_set->rule_deal_info[0]['discount_amt_type'];
								$discount      = (float)$vtprd_rule_set->rule_deal_info[0]['discount_amt_count'];
								if ( 'currency' === $discount_type || 'fixedPrice' === $discount_type ) {
									$price = (float)$product->get_price() - $discount;
								} elseif ( 'percent' === $discount_type ) {
									$price = (float)$product->get_price() - ( ( (float)$product->get_price() * $discount ) / 100 );
								}

							}

					}
				}
			}
		}


		/**
		 * PLUGIN: Easy woo-commerce discount plugin
		 * URL: https://wordpress.org/plugins/easy-woocommerce-discounts/
		 */
		if (is_plugin_active('easy-woocommerce-discounts/easy-woocommerce-discounts.php')) {

			if ( doing_action( 'woo_feed_update' ) || doing_action( 'woo_feed_update_single_feed' ) ) {

				//all_products, products_in_list thn $products= [];

				$price_type= 'sale_price';
				$pricing = new WCCS_Pricing(
						WCCS()->WCCS_Conditions_Provider->get_pricings( array( 'status' => 1 ) )
				);
				$pricing_rules = $pricing->get_all_pricing_rules();

				if( count( $pricing_rules ) > 0){
					foreach ( $pricing_rules as $key => $value ) {
						$discount_type = $pricing_rules[$key]->discount_type;
						if( isset( $pricing_rules[$key]->discount ) ){
							$discount = (float)$pricing_rules[$key]->discount;
						}else {
							$discount = "";
						}
						if( $price == "") {
							$price = (float)$product->get_price();
						}

						$product_discounts_type = $pricing_rules[$key]->items[0]['item'];
						$with_products = $pricing_rules[$key]->items[0]['products'];
						if( is_numeric( $discount ) && $discount > 0 ) {
							if( $product_discounts_type === "all_products") {
								if ( 'percentage_discount' === $discount_type ) {
									$price = $price - ( ( $price * $discount ) / 100 );
								} elseif ( 'price_discount' === $discount_type ) {
									$price = $price - $discount;
								}
							}else if( $product_discounts_type === "products_in_list" ) {

								if( is_array( $with_products ) && count($with_products) > 0){

									if( in_array( $product->get_id(), $with_products )) {
										if ( 'percentage_discount' === $discount_type ) {
											$price = $price - ( ( $price * $discount ) / 100 );
										} elseif ( 'price_discount' === $discount_type ) {
											$price = $price - $discount;
										}
									}

								}
							} else if( $product_discounts_type ==="products_not_in_list" ) {
								if( !in_array( $product->get_id(), $with_products )) {
									if ( 'percentage_discount' === $discount_type ) {
										$price = $price - ( ( $price * $discount ) / 100 );
									} elseif ( 'price_discount' === $discount_type ) {
										$price = $price - $discount;
									}
								}
							}
						}
					}
				}


			}

//			$product_Pricing = new WCCS_Public_Product_Pricing( $product, $pricing, $apply_method = '' );
//			$price = $product_Pricing ->get_discounted_price( $discount, $discount_type );

		}

		//######################### YITH #########################################################
		/**
		 * PLUGIN: YITH WOOCOMMERCE DYNAMIC PRICING AND DISCOUNTS
		 * URL: hhttps://yithemes.com/themes/plugins/yith-woocommerce-dynamic-pricing-and-discounts/
		 *
		 * NOTE:*  YITH Automatically apply discount to $product->get_sale_price() method.
		 */
		//######################### RightPress ###################################################
		/**
		 * PLUGIN: WooCommerce Dynamic Pricing & Discounts
		 * URL: https://codecanyon.net/item/woocommerce-dynamic-pricing-discounts/7119279
		 *
		 * RightPress dynamic pricing supported. Filter Hooks applied to "woo_feed_apply_hooks_before_product_loop"
		 * to get the dynamic discounted price via $product->ger_sale_price(); method.
		 */
		//###################### Dynamic Pricing ##################################################
		/**
		 * PLUGIN: Dynamic Pricing
		 * URL: https://woocommerce.com/products/dynamic-pricing/
		 *
		 * Dynamic Pricing plugin doesn't show the options or any price change on your frontend.
		 * So a user will not even notice the discounts until he reaches the checkout.
		 * No need to add the compatibility.
		 */

		// Get Price with tax
		if ( $discount_plugin_activate && $tax ) {
			$price = woo_feed_get_price_with_tax( $price, $product );
		}

		if ( $price == 0 ) {
			$price = '';
		}

		return ( isset( $base_price ) || ( $price > 0 ) && ( $price < $base_price ) ) ? $price : $base_price;
	}
}

/**
 * Woo_Feed_Custom_Taxonomy_Filter is special extenstion class of FilterIterator
 *
 * @since 4.3.93
 */
if ( ! class_exists( 'Woo_Feed_Custom_Taxonomy_Filter' ) ) {
	class Woo_Feed_Custom_Taxonomy_Filter extends FilterIterator {
		public function __construct( array $items ) {
			$object = new ArrayObject( $items );

			//php 8 compitibility
			if( phpversion() >= 8 ) {
				get_mangled_object_vars( $object );
			}
			parent::__construct( $object->getIterator() );
		}

		#[\ReturnTypeWillChange]
		public function accept() {
			return array_key_exists( 2, parent::current() ) ? parent::current()[2] : false;
		}
	}
}

/**
 * Woo_Feed_Custom_Identifier_Filter is a extends class of FilterIterator
 *
 * @since 4.3.93
 */
if ( ! class_exists( 'Woo_Feed_Custom_Identifier_Filter' ) ) {
	class Woo_Feed_Custom_Identifier_Filter extends FilterIterator {

		public function __construct( array $items ) {
			$object = new ArrayObject( $items );
			parent::__construct( $object->getIterator() );
		}

		#[\ReturnTypeWillChange]
		public function accept() {
			if ( ! isset( parent::current()[3] ) || ( isset( parent::current()[3] ) && parent::current()[3] ) ) {
				$is_identifier = ! array_key_exists( 2, parent::current() ) ? true : ! parent::current()[2];
				if ( $is_identifier ) {
					$get_settings    = woo_feed_get_options( 'all' );
					$get_identifiers = isset( $get_settings['woo_feed_identifier'] ) ? $get_settings['woo_feed_identifier'] : array();

					if ( in_array( parent::key(), array_keys( $get_identifiers ), true ) ) {
						if ( 'enable' === $get_identifiers[ parent::key() ] ) {
							return parent::current();
						}
					} else {
						if ( parent::current()[1] ) {
							return parent::current();
						}
					}
				}
			}
		}
	}
}

if ( ! function_exists( 'woo_feed_array_splice_preserve_keys' ) ) {
	function woo_feed_array_splice_preserve_keys( &$input, $offset, $length = null, $replacement = array() ) {
		if ( empty( $replacement ) ) {
			return array_splice( $input, $offset, $length );
		}

		$part_before  = array_slice( $input, 0, $offset, $preserve_keys = true );
		$part_removed = array_slice( $input, $offset, $length, $preserve_keys = true );
		$part_after   = array_slice( $input, $offset + $length, null, $preserve_keys = true );

		$input = $part_before + $replacement + $part_after;

		return $part_removed;
	}
}

if ( ! function_exists( 'woo_feed_filter_count_cb' ) ) {
	/**
	 * Add AJAX action when client click filter tab.
	 */
	add_action( 'wp_ajax_woo_feed_filter_count', 'woo_feed_filter_count_cb' );
	/**
	 * This function return object with product counter based on status
	 * - Is product out of stock?
	 * - Is product is hidden?
	 * - Product has description or short description?
	 * - Product has image?
	 * - Product has price? Regulart price or sell price
	 *
	 * @return mixed array | error
	 */
	function woo_feed_filter_count_cb() {
		$is_nonce_valid = isset( $_GET['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'wpf_feed_nonce' );

		if ( $is_nonce_valid ) {
			$results = array(
				'hidden'     => woo_feed_hidden_products_count(),
				'noPrice'    => woo_feed_no_price_products_count(),
				'noImg'      => woo_feed_no_image_products_count(),
				'noDesc'     => woo_feed_no_description_products_count(),
				'outOfStock' => woo_feed_out_of_stock_products_count(),
				'backorder'  => woo_feed_backorder_products_count(),
			);
			wp_send_json_success( $results );
		} else {
			wp_send_json_error( esc_html__( 'Invalid Request.', 'woo-feed' ) );
		}
		wp_die();
	}
}
if ( ! function_exists( 'woo_feed_hidden_products_count' ) ) {
	/**
	 * This function give the hidden products count.
	 *
	 * @return integer
	 */
	function woo_feed_hidden_products_count() {
		$args     = array(
			'post_type'      => 'product',
			'posts_per_page' => - 1,
			'fields'         => 'ids',
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'exclude-from-catalog',
					'operator' => 'IN',
				),
			),
		);
		$products = new WP_Query( $args );

		return count( $products->posts );
	}
}
if ( ! function_exists( 'woo_feed_no_image_products_count' ) ) {
	/**
	 * This function give the products count which have no thumbnail image or gallery image
	 *
	 * @return integer
	 */
	function woo_feed_no_image_products_count() {
		$args     = array(
			'post_type'      => 'product',
			'posts_per_page' => - 1,
			'fields'         => 'ids',
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => '_thumbnail_id',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => '_product_image_gallery',
					'compare' => 'NOT EXISTS',
				),
			),
		);
		$products = new WP_Query( $args );

		return count( $products->posts );
	}
}
if ( ! function_exists( 'woo_feed_no_description_products_count' ) ) {
	/**
	 * This function give the products count which have no description/short description.
	 *
	 * @return integer
	 */
	function woo_feed_no_description_products_count() {
		add_filter( 'posts_where', 'woo_feed_filter_where_product_with_no_description' );
		$args     = array(
			'post_type'      => 'product',
			'posts_per_page' => - 1,
			'fields'         => 'ids',
		);
		$products = new WP_Query( $args );
		remove_filter( 'posts_where', 'woo_feed_filter_where_product_with_no_description' );

		return count( $products->posts );
	}
}
if ( ! function_exists( 'woo_feed_filter_where_product_with_no_description' ) ) {
	/**
	 * This function changes the wp query to get out of products without description.
	 *
	 * @param string $where
	 */
	function woo_feed_filter_where_product_with_no_description( $where = '' ) {
		$where .= "
                    AND trim( coalesce( post_content, '' ) ) = ''
                ";

		return $where;
	}
}
if ( ! function_exists( 'woo_feed_out_of_stock_products_count' ) ) {
	/**
	 * This function gives the `out of stock products` count.
	 *
	 * @return integer
	 */
	function woo_feed_out_of_stock_products_count() {
		$args     = array(
			'post_type'      => 'product',
			'posts_per_page' => - 1,
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'     => '_stock_status',
					'value'   => 'outofstock',
					'compare' => '==',
				),
			),
		);
		$products = new WP_Query( $args );

		return count( $products->posts );
	}
}
if ( ! function_exists( 'woo_feed_backorder_products_count' ) ) {
	/**
	 * This function gives the `backorder` products count.
	 *
	 * @return integer
	 */
	function woo_feed_backorder_products_count() {
		$args     = array(
			'post_type'      => 'product',
			'posts_per_page' => - 1,
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'     => '_stock_status',
					'value'   => 'onbackorder',
					'compare' => '==',
				),
			),
		);
		$products = new WP_Query( $args );

		return count( $products->posts );
	}
}
if ( ! function_exists( 'woo_feed_no_price_products_count' ) ) {
	/**
	 * This function give the products count which have no prices.
	 *
	 * @return integer
	 */
	function woo_feed_no_price_products_count() {
		$args     = array(
			'post_type'      => 'product',
			'posts_per_page' => - 1,
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'     => '_price',
					'value'   => '',
					'compare' => '==',
				),
			),
		);
		$products = new WP_Query( $args );

		return count( $products->posts );
	}
}

if ( ! function_exists( 'woo_feed_custom_field_meta_filter' ) ) {
	/**
	 * Identifier meta value filter for old and new version users
	 *
	 * @param $meta string Default Meta
	 * @param WC_Product $product
	 * @param $field string Meta field
	 *
	 * @return string Custom Field Meta.
	 * @since 4.3.99
	 *
	 */
	function woo_feed_custom_field_meta_filter( $meta, $product, $field ) {
		$id = $product->get_id();

		//identifier meta value for old and new version users
		if ( false !== strpos( $meta, 'woo_feed_identifier_' ) ) {

			$identifier = str_replace( 'woo_feed_identifier_', '', $meta );
			if ( metadata_exists( 'post', $id, 'woo_feed_' . $identifier ) ) {
				$meta = 'woo_feed_' . $identifier;
			} else {
				$meta = 'woo_feed_identifier_' . $identifier;
			}
		}

		return $meta;
	}

	add_filter( 'woo_feed_custom_field_meta', 'woo_feed_custom_field_meta_filter', 3, 10 );
}

if ( ! function_exists( 'woo_feed_strpos_array' ) ) {

	/**
	 * Extension of php `strpos` function
	 *
	 * @param $niddles array
	 * @param $haystack string
	 *
	 * @return boolean If any string exists.
	 * @since 4.3.100
	 *
	 */
	function woo_feed_strpos_array( $niddles, $haystack ) {

		if ( empty( $haystack ) ) {
			return;
		}

		foreach ( $niddles as $niddle ) {
			if ( strpos( $haystack, $niddle ) !== false ) {
				return true;
			}
		}

		return false;

	}
}

if ( ! function_exists( 'woo_feed_schema_description_filter' ) ) {
	/**
	 * Filter schema description
	 *
	 * @param $description mixed default product description
	 * @param $product mixed product object
	 *
	 * @return mixed
	 * @since 4.3.101
	 *
	 */
	function woo_feed_schema_description_filter( $description, $product ) {

		$description = do_shortcode( $description );
		$description = woo_feed_stripInvalidXml( $description );
		$description = preg_replace( '/\[\/?vc_.*?\]/', '', $description );
		$description = strip_shortcodes( $description );
		$description = preg_replace( '~[\r\n]+~', '', $description );

		//strip tags and spacial characters
		$strip_description = wp_strip_all_tags( wp_specialchars_decode( $description ) );

		$description = ! empty( strlen( $strip_description ) ) && 0 < strlen( $strip_description ) ? $strip_description : $description;

		return $description;
	}

	add_filter( 'woo_feed_schema_description', 'woo_feed_schema_description_filter', 10, 2 );
}


if ( ! function_exists( 'woo_feed_get_yoast_identifiers_value' ) ) {
	/**
	 * Get Yoast identifiers value
	 *
	 * @param $attribute_key string attribute key
	 * @param $product WC_Product product object
	 *
	 * @return mixed identifier value.
	 * @since 4.4.4
	 *
	 * @author Nazrul Islam Nayan
	 */
	function woo_feed_get_yoast_identifiers_value( $attribute_key, $product ) {
		$identifier = '';
		if ( class_exists( 'Yoast_WooCommerce_SEO' ) ) {
			$wpseo_identifier = get_post_meta( $product->get_id(), 'wpseo_global_identifier_values' );
			if ( $product->is_type( 'variation' ) ) {
				$wpseo_identifier = get_post_meta( $product->get_id(), 'wpseo_variation_global_identifiers_values' );
			}
			$wpseo_identifier = reset( $wpseo_identifier );

			if ( isset( $wpseo_identifier[ $attribute_key ] ) ) {
				$identifier = $wpseo_identifier[ $attribute_key ];
			}

			if ( empty( $identifier ) && $product->is_type( 'variation' ) ) {
				$parent     = wc_get_product( $product->get_parent_id() );
				$identifier = woo_feed_get_yoast_identifiers_value( $attribute_key, $parent );
			}
		}

		return $identifier;
	}

	if ( ! function_exists( 'woo_feed_parent_product_id' ) ) {
		/**
		 * Return variable product id for variation else main product id.
		 *
		 * @param WC_Product $product
		 *
		 * @return int
		 */
		function woo_feed_parent_product_id( $product ) {
			if ( $product->is_type( 'variation' ) ) {
				return $product->get_parent_id();
			}

			return $product->get_id();
		}
	}
}

#==== MERCHANT TEMPLATE OVERRIDE START ==============#
if ( ! function_exists( 'woo_feed_modify_google_color_attribute_value' ) ) {
	/**
	 * Replace comma (,) with slash (/) for Google Shopping template color attribute value
	 *
	 * @param $attribute_value
	 * @param $product
	 * @param $feed_config
	 *
	 * @return string
	 */
	function woo_feed_modify_google_color_attribute_value( $attribute_value, $product, $feed_config, $merchant_attribute ) {
		// Replace Google Color attribute value according to requirements
		if ( ( 'g:color' === $merchant_attribute || 'color' === $merchant_attribute )
			&& in_array(
				$feed_config['provider'],
				array(
					'google',
					'facebook',
					'pinterest',
					'bing',
					'snapchat',
				),
				true
			) ) {
			return str_replace( ', ', '/', $attribute_value );
		}

		return $attribute_value;
	}
}

if ( ! function_exists( 'woo_feed_modify_weight_attribute_value' ) ) {
	/**
	 * Add wight unit as suffix for Google Shopping template shipping_weight attribute.
	 *
	 * @param $attribute_value
	 * @param WC_Product $product
	 * @param $feed_config
	 *
	 * @return string
	 */
	function woo_feed_modify_weight_attribute_value( $attribute_value, $product, $feed_config ) {
		if ( isset( $feed_config['attributes'] )
			&& in_array(
				$feed_config['provider'],
				array(
					'google',
					'facebook',
					'pinterest',
					'bing',
					'snapchat',
				)
			) ) {
			$attributes = $feed_config['attributes'];
			$key        = array_search( 'weight', $attributes, true );
			if ( isset( $feed_config['suffix'] ) && ! empty( $key ) && array_key_exists( $key, $feed_config['suffix'] ) ) {
				$weight_suffix_unit = $feed_config['suffix'][ $key ];

				if ( empty( $weight_suffix_unit ) && ! empty( $attribute_value ) ) {
					$attribute_value .= ' ' . get_option( 'woocommerce_weight_unit' );
				}
			}
		}

		return $attribute_value;
	}
}

if ( ! function_exists( 'woo_feed_get_bestprice_categoryPath_attribute_value_modify' ) ) {
	/**
	 * Replace BestPrice categoryPath value from > to ,
	 *
	 * @param $attribute_value
	 * @param $product
	 * @param $feed_config
	 *
	 * @return string
	 */
	function woo_feed_get_bestprice_categoryPath_attribute_value_modify( $attribute_value, $product, $feed_config ) {
		$attribute_value = str_replace( '>', ', ', $attribute_value );

		return $attribute_value;
	}
}

if ( ! function_exists( 'woo_feed_availability_attribute_value_modify' ) ) {
	/**
	 * Modify  Availability attribute value based on channel.
	 *
	 * @param $attribute_value
	 * @param $product
	 * @param $feed_config
	 *
	 * @return string
	 */
	function woo_feed_availability_attribute_value_modify( $attribute_value, $product, $feed_config ) {
		if ( 'bestprice' === $feed_config['provider'] ) {
			if ( 'in stock' === $attribute_value ) {
				return 'Y';
			}

			return 'N';
		}

		if ( 'skroutz' === $feed_config['provider'] ) {

			if ( 'in stock' === $attribute_value ) {
				$in_stock_string = __( 'Delivery 1 to 3 days', 'woo-feed' );
			} else {
				$in_stock_string = __( 'Delivery up to 30 days', 'woo-feed' );
			}

			return $in_stock_string;
		}

		if ( 'pricerunner' === $feed_config['provider'] ) {
			if ( 'in stock' === $attribute_value ) {
				return 'Yes';
			}

			return 'No';
		}

		if ( 'google' === $feed_config['provider'] || 'pinterest' === $feed_config['provider'] ) {
			if ( 'on backorder' === $attribute_value || 'on_backorder' === $attribute_value ) {
				return 'preorder';
			}

			if ( 'google' === $feed_config['provider'] ) {
				if ( ! in_array( $attribute_value, array( 'in_stock', 'out_of_stock', 'on_backorder' ) ) ) {
					return 'in_stock';
				}
			} elseif ( ! in_array( $attribute_value, array( 'in stock', 'out of stock', 'on backorder' ) ) ) {
				return 'in stock';
			}
		}

		if ( 'facebook' === $feed_config['provider'] ) {
			if ( 'on backorder' === $attribute_value ) {
				return 'available for order';
			} elseif ( ! in_array( $attribute_value, array( 'in stock', 'out of stock', 'on backorder' ) ) ) {
				return 'in stock';
			}
		}

		return $attribute_value;
	}
}

if ( ! function_exists( 'woo_feed_spartoo_attribute_value_modify' ) ) {
	/**
	 * Modify Spartoo feed Parent/Child attribute value.
	 *
	 * @param $attribute_value
	 * @param $product
	 * @param $feed_config
	 *
	 * @return string
	 */
	function woo_feed_spartoo_attribute_value_modify( $attribute_value, $product, $feed_config ) {
		if ( 'spartoo.fi' === $feed_config['provider'] ) {
			if ( 'variation' === $attribute_value ) {
				return 'child';
			}

			return 'parent';
		}

		return $attribute_value;
	}
}

if ( ! function_exists( 'woo_feed_get_pinterest_rss_date_attribute_callback' ) ) {
	/**
	 * Convert date format to DATERFC822 for pinterest RSS Feed.
	 *
	 * @param $attribute_value
	 * @param $product
	 * @param $feed_config
	 *
	 * @return string
	 */
	function woo_feed_get_pinterest_rss_date_attribute_callback( $attribute_value, $product, $feed_config ) {
		if ( ! empty( $attribute_value ) ) {
			return date( 'r', strtotime( $attribute_value ) );
		}

		return $attribute_value;
	}
}

if ( ! function_exists( 'woo_feed_duplicate_feed' ) ) {
	/**
	 * @param string $feed_from Required. Feed name to duplicate from
	 * @param string $new_name Optional. New name for duplicate feed.
	 *                              Default to auto generated slug from the old name prefixed with number.
	 * @param bool $copy_file Optional. Copy the file. Default is true.
	 *
	 * @return bool|WP_Error        WP_Error object on error, true on success.
	 */
	function woo_feed_duplicate_feed( $feed_from, $new_name = '', $copy_file = true ) {

		if ( empty( $feed_from ) ) {
			return new WP_Error( 'invalid_feed_name_top_copy_from', esc_html__( 'Invalid Request.', 'woo-feed' ) );
		}
		// normalize the option name.
		$feed_from = woo_feed_extract_feed_option_name( $feed_from );
		// get the feed data for duplicating.
		$base_feed = maybe_unserialize( get_option( 'wf_feed_' . $feed_from, array() ) );
		// validate the feed data.
		if ( empty( $base_feed ) || ! is_array( $base_feed ) || ! isset( $base_feed['feedrules'] ) || ( isset( $base_feed['feedrules'] ) && empty( $base_feed['feedrules'] ) ) ) {
			return new WP_Error( 'empty_base_feed', esc_html__( 'Feed data is empty. Can\'t duplicate feed.', 'woo-feed' ) );
		}
		$part = '';
		if ( empty( $new_name ) ) {
			// generate a unique slug for duplicate the feed.
			$new_name = generate_unique_feed_file_name( $feed_from, $base_feed['feedrules']['feedType'], $base_feed['feedrules']['provider'] );
			// example-2 or example-2-2-3
			$part = ' ' . str_replace_trim( $feed_from . '-', '', $new_name ); // -2-2-3
		} else {
			$new_name = generate_unique_feed_file_name( $new_name, $base_feed['feedrules']['feedType'], $base_feed['feedrules']['provider'] );
		}
		// new name for the feed with numeric parts from the unique slug.
		$base_feed['feedrules']['filename'] = $base_feed['feedrules']['filename'] . $part;
		// copy feed config data.
		$saved_feed = woo_feed_save_feed_config_data( $base_feed['feedrules'], $new_name, false );
		if ( false === $saved_feed ) {
			return new WP_Error( 'unable_to_save_the_duplicate', esc_html__( 'Unable to save the duplicate feed data.', 'woo-feed' ) );
		}

		if ( true === $copy_file ) {
			// copy the data file.
			$original_file = woo_feed_get_file( $feed_from, $base_feed['feedrules']['provider'], $base_feed['feedrules']['feedType'] );
			$new_file      = woo_feed_get_file( $new_name, $base_feed['feedrules']['provider'], $base_feed['feedrules']['feedType'] );
			if ( copy( $original_file, $new_file ) ) {
				return true;
			} else {
				return new WP_Error( 'unable_to_copy_file', esc_html__( 'Feed Successfully Duplicated, but unable to generate the data file. Please click the "Regenerate Button"', 'woo-feed' ) );
			}
		}

		return true;
	}
}

if ( ! function_exists( 'woo_feed_is_google_group_merchant' ) ) {
	/**
	 * Check if the given merchant is a google group merchant (google, facebook, pinterest, bing)
	 *
	 * @param string $provider Feed Merchant
	 *
	 * @return boolean
	 * @since  4.4.22
	 * @author Nazrul Islam Nayan
	 */
	function woo_feed_is_google_group_merchant( $provider ) {
		return in_array( $provider, array( 'google', 'facebook', 'pinterest', 'bing' ) );
	}
}

if ( ! function_exists( 'woo_feed_filter_product_description_callback' ) ) {
	/**
	 * @param string $description Product Description
	 * @param WC_Product $product Product Object
	 * @param array $configFeed Feed Config
	 *
	 * @return mixed
	 */
	function woo_feed_filter_product_description_callback( $description, $product, $config ) {
		if ( empty( $description ) ) {
			return $description;
		}

		if ( isset( $config['provider'] ) && woo_feed_is_google_group_merchant( $config['provider'] ) ) {
			if ( strlen( $description ) > 5000 ) {
				for ( $I = 4999; $description[ $I ] != ' '; $I -- ) {
					;
				}
				$description = substr( $description, 0, $I );
			}
		}

		return $description;
	}
}

if ( ! function_exists( 'woo_feed_filter_product_title' ) ) {
	/**
	 * @param string $title Product Title
	 * @param WC_Product $product
	 * @param array $config Feed config
	 *
	 * @return string
	 */
	function woo_feed_filter_product_title( $title, $product, $config ) {

		if ( ! is_string( $title ) ) {
			return '';
		}

		if ( isset( $config['provider'] ) && in_array(
			$config['provider'],
			array(
				'google',
				'facebook',
				'pinterest',
				'bing',
			)
		) ) {
			if ( strlen( $title ) > 150 ) {
				for ( $I = 149; $title[ $I ] != ' '; $I --
				) {
					;
				}

				$title = substr( $title, 0, $I );
			}
		}

		return $title;
	}
}

if ( ! function_exists( 'woo_feed_array_insert_after' ) ) {
	/**
	 * Insert a value or key/value pair after a specific key in an array. If key doesn't exist, value is appended
	 * to the end of the array.
	 *
	 * @param array $array
	 * @param string $key
	 * @param array $new
	 *
	 * @return array
	 */
	function woo_feed_array_insert_after( array $array, $key, array $new ) {
		$keys  = array_keys( $array );
		$index = array_search( $key, $keys, true );
		$pos   = false === $index ? count( $array ) : $index + 1;

		return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
	}
}

if ( ! function_exists( 'woo_feed_get_js_dequeue_handles_list' ) ) {
	/**
	 * JS files handles list to dequeue from loading
	 *
	 * @return array
	 * @author Nazrul Islam Nayan
	 * @since 4.4.43
	 */
	function woo_feed_get_js_dequeue_handles_list() {
		$js_files_handles = array( 'common_aramex', 'jquery_chained', 'validate_aramex' ); //aramex shipping plugin handles

		return apply_filters( 'woo_feed_filter_js_dequeue_handles', $js_files_handles );
	}
}

if ( ! function_exists( 'woo_feed_get_plugin_pages_slugs' ) ) {
	/**
	 * Get Woo Feed Plugin Pages Slugs
	 *
	 * @return array
	 * @author Nazrul Islam Nayan
	 * @since 4.4.44
	 */
	function woo_feed_get_plugin_pages_slugs() {
		$woo_feed_plugin_pages = array(
			'webappick-manage-feeds',
			'webappick-new-feed',
			'webappick-wp-options',
			'webappick-feed-settings',
			'webappick-feed-docs',
			'webappick-feed-pro-vs-free',
			'webappick-wp-status',
			'webappick-feed-category-mapping',
			'webappick-wp-options',
		);

		return apply_filters( 'woo_feed_plugin_pages_slugs', $woo_feed_plugin_pages );
	}
}

if ( ! function_exists( 'woo_feed_make_feed_big_data' ) ) {
	function woo_feed_make_feed_big_data( $data, $ids, $config ) {

		//setup feed shipping data @TODO: need to make a class when another data setup will be added
		if ( isset( $config['attributes'] ) && in_array( 'shipping', $config['attributes'] ) ) {
			if ( class_exists( 'WC_Shipping_Zones' ) ) {
				$data['shipping_zones'] = WC_Shipping_Zones::get_zones();
			}
		}

		return $data;

	}

	add_filter( 'woo_feed_feed_big_data', 'woo_feed_make_feed_big_data', 10, 3 );
}


if ( ! function_exists( 'woo_feed_after_wc_product_structured_data' ) ) {
	function woo_feed_after_wc_product_structured_data( $markup, $product ) {

		if ( ! $product instanceof WC_Product ) {
			return $markup;
		}

		if ( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], 'attribute_' ) ) {
			$url            = $_SERVER['REQUEST_URI'];
			$url_components = parse_url( $url );
			$currency       = get_option( 'woocommerce_currency' );

			if ( isset( $url_components['query'] ) && ! empty( $url_components['query'] ) ) {
				parse_str( $url_components['query'], $params );

				$attributes      = $product->get_attributes();
				$attribute_names = array_keys( $attributes );

				if ( isset( $attribute_names ) && is_array( $attribute_names ) ) {
					$meta_query_items             = array();
					$meta_query_items['relation'] = 'AND';

					foreach ( $attribute_names as $attr_name ) {
						$attribute_name = 'attribute_' . $attr_name;

						if ( isset( $params[ $attribute_name ] ) ) {
							$new_query_item            = array();
							$new_query_item['key']     = $attribute_name;
							$new_query_item['value']   = $params[ $attribute_name ];
							$new_query_item['compare'] = 'LIKE';

							array_push( $meta_query_items, $new_query_item );
						}
					}

					$variation_id = get_posts(
						array(
							'post_type'   => 'product_variation',
							'numberposts' => 1,
							'post_status' => 'publish',
							'fields'      => 'ids',
							'post_parent' => $product->get_id(),
							'meta_query'  => $meta_query_items,
						)
					);

					if ( isset( $variation_id[0] ) ) {
						$variation_product = wc_get_product( $variation_id[0] );

						if ( $variation_product instanceof WC_Product_Variation ) {
							$variation_price = $variation_product->get_price();

							$markup['offers'][0]['@type']                               = 'Offer';
							$markup['offers'][0]['price']                               = $variation_price;
							$markup['offers'][0]['priceSpecification']['price']         = $variation_price;
							$markup['offers'][0]['priceSpecification']['priceCurrency'] = $currency;
							$markup['offers'][0]['priceCurrency']                       = $currency;
						}
					}
				}
			}
		}

		return $markup;

	}

	add_filter( 'woo_feed_after_wc_product_structured_data', 'woo_feed_after_wc_product_structured_data', 10, 2 );
}

if ( ! function_exists( 'woo_feed_filter_shipping_info_callback' ) ) {
	function woo_feed_filter_shipping_info_callback( $shipping_info, $shipping_zones, $product, $config ) {

		//when WooCommerce Advanced Shipping by sormano is activated
		if ( is_plugin_active( 'woocommerce-advanced-shipping/woocommerce-advanced-shipping.php' ) ) {
			$product_id = $product->get_id();

			//get advanced shipping post ids for post type `was`
			$args = array(
				'post_type' => 'was',
				'fields'    => 'ids',
			);

			$ids = get_posts( $args );

			// Set shipping cost
			$shipping_cost = 0;
			$tax           = 0;
			defined( 'WC_ABSPATH' ) || exit;

			// Load cart functions which are loaded only on the front-end.
			include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
			include_once WC_ABSPATH . 'includes/class-wc-cart.php';

			wc_load_cart();
			global $woocommerce;

			// Make sure to empty the cart again
			$woocommerce->cart->empty_cart();

			// add product to cart
			$woocommerce->cart->add_to_cart( $product_id, 1 );

			if ( isset( WC()->session->get( 'shipping_for_package_0' )['rates'] ) ) {
				$cart_shippings      = WC()->session->get( 'shipping_for_package_0' )['rates'];
				$exist_shipping_keys = array_keys( $cart_shippings );

				if ( isset( $cart_shippings ) && is_array( $cart_shippings ) ) {
					$adv_shipping = array();
					foreach ( $cart_shippings as $key => $cart_shipping ) {
						$new_adv_shipping = array();

						if ( in_array( $key, $ids ) ) {
							$new_adv_shipping['country'] = $config['feed_country'];
							$new_adv_shipping['service'] = '';
							$label                       = $cart_shipping->get_label();

							//advanced shipping service
							if ( isset( $label ) && ! empty( $label ) ) {
								$new_adv_shipping['service'] = $label;
							} else {
								$new_adv_shipping['service'] = get_the_title( $key );
							}

							//advanced shipping cost
							if ( ! empty( $cart_shipping->get_cost() ) ) {
								$new_adv_shipping['price'] = $cart_shipping->get_cost();
							} else {
								$new_adv_shipping['price'] = 0;
							}

							array_push( $adv_shipping, $new_adv_shipping );
						}
					}
				}
			}

			// Make sure to empty the cart again
			$woocommerce->cart->empty_cart();

			if ( ! empty( $adv_shipping ) ) {
				$shipping_info = array_merge( $shipping_info, $adv_shipping );
			}
		}

		return $shipping_info;

	}

	add_filter( 'woo_feed_filter_shipping_info', 'woo_feed_filter_shipping_info_callback', 10, 4 );
}

#=============== ACF ===============================================
if ( ! function_exists( 'woo_feed_get_acf_field_list' ) ) {
	/**
	 * Get Advance Custom Field (ACF) field list
	 *
	 *
	 * @return Array
	 */
	function woo_feed_get_acf_field_list() {
		$options = array();
		if ( class_exists( 'ACF' ) ) {
			$acf_fields = woo_feed_get_cached_data( 'acf_field_list' );
			if ( false === $acf_fields ) {
				$field_groups = acf_get_field_groups();
				foreach ( $field_groups as $group ) {
					// DO NOT USE here: $fields = acf_get_fields($group['key']);
					// because it causes repeater field bugs and returns "trashed" fields
					$fields = get_posts(
						array(
							'posts_per_page'         => - 1,
							'post_type'              => 'acf-field',
							'orderby'                => 'menu_order',
							'order'                  => 'ASC',
							'suppress_filters'       => true, // DO NOT allow WPML to modify the query
							'post_parent'            => $group['ID'],
							'post_status'            => 'any',
							'update_post_meta_cache' => false,
						)
					);
					foreach ( $fields as $field ) {
						$options[ 'acf_fields_' . $field->post_name ] = $field->post_title;
					}
				}

				woo_feed_set_cache_data( 'acf_field_list', $options );
			}
		}

		return $options;
	}
}

if ( ! function_exists( 'woo_feed_get_product_attributes' ) ) {
	/**
	 * Get Advance Custom Field (ACF) field list
	 *
	 *
	 * @return string
	 */
	function woo_feed_get_product_attributes( $selected = '' ) {
		return ( new Woo_Feed_Product_Attributes() )->getAttributes( $selected );
	}
}

if ( ! function_exists( 'get_woo_feed_attribute_highlighted' ) ) {

	/**
	 * Get Woo Feed Plugin WooCommerce Product attributes
	 *
	 * @author Md. Nashir Uddin
	 * @since 4.7.1
	 */

	function get_woo_feed_attribute_highlighted( $attribute_name, $i ) {
		global $post;

		$id = isset( $post->ID ) ? absint( $post->ID ) : '';

		// ID for either from ajax or from post
		$post_id        = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : $id;
		$attribute_name = strtolower( sanitize_title( $attribute_name ) );
		$val            = get_post_meta( $post_id, 'attribute_' . $attribute_name . '_highlighted_' . $i, true );

		return ! empty( $val ) ? $val : false;
	}
}
if ( ! function_exists( 'woo_feed_add_product_attribute_is_highlighted' ) ) {
	function woo_feed_add_product_attribute_is_highlighted( $attribute, $i = 0 ) {
		$value = get_woo_feed_attribute_highlighted( $attribute->get_name(), $i );
		?>
		<tr>
			<td>
				<div class="enable_highlighted">
					<label>
						<input type="hidden" name="attribute_highlighted[<?php echo esc_attr( $i ); ?>]" value="0" />
						<input type="checkbox" class="checkbox" <?php checked( $value, true ); ?> name="attribute_highlighted[<?php echo esc_attr( $i ); ?>]" value="1" />
						<?php esc_html_e( 'Highlight attribute', 'textdomain' ); ?>
					</label>
				</div>
			</td>
		</tr>
		<?php
	}
}
if ( ! function_exists( 'woo_feed_ajax_woocommerce_save_attributes' ) ) {

	/**
	 * Get Woo Feed Plugin WooCommerce Product attributes
	 *
	 * @author Md. Nashir Uddin
	 * @since 4.7.1
	 */

	function woo_feed_ajax_woocommerce_save_attributes() {

		check_ajax_referer( 'save-attributes', 'security' );

		parse_str( $_POST['data'], $data );

		if ( array_key_exists( 'attribute_highlighted', $data ) && is_array( $data['attribute_highlighted'] ) ) {

			$type = 'woo_feed_attributes';
			$product_attributes =array();
			$product_attributes = get_option( 'woo_feed_product_attributes' );
			if(empty($product_attributes) )
				$product_attributes =array();

			if ( empty( $product_attributes ) || $product_attributes != $data['attribute_names'] ) {
				$status = 0;
				if(is_array($data['attribute_names'] )) {
					foreach ( $data['attribute_names'] as $attribute ) {
						if ( in_array( $attribute, $product_attributes ) ) {
							$status = 1;
						} else {
							$status = 0;
							break;
						}
					}
				}
				if ( $status == 0 ) {
					$notice_data = Woo_Feed_Notices::get_woo_feed_notice_data();
					Woo_Feed_Notices::add_update_woo_feed_notice_data( $type, $notice_data );
					$data_merge = array_merge( $product_attributes, $data['attribute_names'] );
					update_option( 'woo_feed_product_attributes', array_unique( $data_merge ), 'no' );
				}
			}
		}
	}
}

if ( ! function_exists( 'woo_feed_publish_product' ) ) {

	/**
	 * Get Woo Feed Plugin WooCommerce Product Total Count
	 *
	 * @author Md. Nashir Uddin
	 * @since 4.7.2
	 */

	function woo_feed_publish_product( $new_status, $old_status, $post ) {

		$args          = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		);
		$products      = new WP_Query( $args );
		$product_count = $products->found_posts;
		$type          = 'woo_feed_product_count';

		if ( $product_count > 50000 ) {
			if (
				$new_status === 'publish'
				&& ! empty( $post->ID )
				&& in_array(
					$post->post_type,
					array( 'product' )
				)
			) {

				$notice_data = Woo_Feed_Notices::get_woo_feed_notice_data();
				Woo_Feed_Notices::add_update_woo_feed_notice_data( $type, $notice_data );
			}
		} else {
			Woo_Feed_Notices::update_woo_feed_notice_dismiss( $type, true );
		}

	}
}

if ( ! function_exists( 'woo_feed_saved_mc_options' ) ) {
	/**
	 * Update wp-options data based on Enable/Disable multicurrency options
	 *
	 * @return void
	 * @author Md. Nashir Uddin
	 * @since
	 */
	function woo_feed_saved_mc_options() {
		global $woocommerce_wpml;

		if ( ! isset( $woocommerce_wpml ) ) {
			return;
		};

		$notice_data = Woo_Feed_Notices::get_woo_feed_notice_data();
		$type        = 'enable_multi_currency';

		$multi_currency_enabled = $woocommerce_wpml->settings['enable_multi_currency'];

		if ( $multi_currency_enabled ) {
			Woo_Feed_Notices::add_update_woo_feed_notice_data( $type, $notice_data );
		} else {
			Woo_Feed_Notices::update_woo_feed_notice_dismiss( $type, true );
		}

	}
}

if ( ! function_exists( 'woo_feed_wcml_save_currency' ) ) {
	/**
	 * Update wp-options data based on save wcml currency
	 *
	 * @return void
	 * @author Md. Nashir Uddin
	 * @since
	 */
	function woo_feed_wcml_save_currency() {

		global $woocommerce_wpml;

		if ( ! isset( $woocommerce_wpml ) ) {
			return;
		};

		$notice_data = Woo_Feed_Notices::get_woo_feed_notice_data();

		$woo_feed_currency = $woocommerce_wpml->settings['currencies_order'];
		$rate_set = true;
		$type     = 'base_conversion_rate';
		while ( next( $woo_feed_currency ) !== false ) {
			$rate          = $woocommerce_wpml->settings['currency_options'][ current( $woo_feed_currency ) ]['rate'];
			$previous_rate = $woocommerce_wpml->settings['currency_options'][ current( $woo_feed_currency ) ]['previous_rate'];

			if ( $rate == '' ) {
				Woo_Feed_Notices::add_update_woo_feed_notice_data( $type, $notice_data );
				$rate_set = false;
				break;
			}
		}
		if ( $rate_set ) {
			Woo_Feed_Notices::update_woo_feed_notice_dismiss( $type, true );
		}
	}
}

#==== MERCHANT TEMPLATE OVERRIDE END ================#


if( ! function_exists('get_plugin_file')){
	/**
	 * @return false|mixed|string
	 *
	 */
	function get_plugin_file() {
		return WOO_FEED_PLUGIN_FILE;
	}
}

// Including pluggable functions file
require_once 'pluggable.php';

// End of file helper.php.
