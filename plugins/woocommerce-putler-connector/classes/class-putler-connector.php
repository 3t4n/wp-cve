<?php
/**
 * Class for Putler connector.
 *
 * @package     woocommerce-putler-connector/classes/
 * @version     1.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Putler_Connector' ) ) {

	/**
	 * Putler Connector class.
	 */
	class Putler_Connector {

		/**
		 * Email address.
		 *
		 * @var string $email_address
		 */
		private $email_address = '';

		/**
		 * The API token.
		 *
		 * @var string $api_token
		 */
		private $api_token = '';

		/**
		 * The version number.
		 *
		 * @var float $version
		 */
		private $version;

		/**
		 * Batch size.
		 *
		 * @var int $batch_size
		 */
		private $batch_size = 100;

		/**
		 * API URL.
		 *
		 * @var string $api_url
		 */
		private $api_url;

		/**
		 * Setting URL.
		 *
		 * @var string $settings_url
		 */
		public $settings_url;

		/**
		 * Variable to hold instance of Putler_Connector
		 *
		 * @var $instance
		 */
		protected static $instance = null;

		/**
		 * Call this method to get singleton
		 *
		 * @return Putler_Connector
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Putler_Connector Constructor.
		 *
		 * @access public
		 * @return void
		 */
		private function __construct() {

			$this->api_url = 'https://web.putler.com/connectorAPI';

			$this->version = WPC_VERSION;

			$settings = get_option( 'putler_connector_settings', null );
			if ( ! empty( $settings ) ) {
				$api_token           = ( ! empty( $settings['api_token'] ) ) ? explode( ',', $settings['api_token'] ) : null;
				$this->email_address = ( ! empty( $settings['email_address'] ) ) ? $settings['email_address'] : null;

				if ( ! empty( $api_token ) ) {
					foreach ( $api_token as $token ) {
						if ( strpos( $token, 'web-' ) !== false ) {
							$this->api_token = $token;
							break;
						}
					}
				}
			}

			// Show a message when no web tokens found.
			if ( empty( $this->api_token ) && ! empty( $settings ) ) {
				add_action( 'admin_notices', array( &$this, 'putler_desktop_deprecated' ) );
			}

			if ( is_admin() ) {
				$this->settings_url = admin_url( 'tools.php?page=putler_connector' );
				add_action( 'admin_menu', array( &$this, 'add_admin_menu_page' ) );
				add_action( 'wp_ajax_putler_connector_connection_heartbeat', array( &$this, 'connection_heartbeat' ) );
				add_action( 'wp_ajax_putler_connector_resync', array( &$this, 'send_resync_request' ) );
			}

			add_action( 'init', array( &$this, 'request_handler' ) );
		}

		/**
		 * Function to handle all incoming requests.
		 *
		 * @return void.
		 */
		public function request_handler() {
			$url_path = basename( trim( wp_parse_url( add_query_arg( array() ), PHP_URL_PATH ), '/' ) );
			if ( empty( $url_path ) || 'ptwp-putler-connector' !== $url_path ) {
				return;
			}
			$func_nm = ( ! empty( $_REQUEST['action'] ) ) ? trim( sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
			if ( ! empty( $func_nm ) && in_array( $func_nm, array( 'get_auth_token', 'get_temp_token', 'putler_connector_get_data', 'putler_connector_sync_complete', 'get_plugin_info' ), true ) && is_callable( array( $this, $func_nm ) ) ) {
				$this->$func_nm();
			}
		}
		/**
		 * Function to get plugin information.
		 */
		public function get_plugin_info() {
			if ( empty( $this->authenticate_request() ) ) {
				header( 'Content-Type: text/xml' );
				while ( ob_get_contents() ) {
					ob_clean();
				}
				$response = $this->generate_valid_xml_from_array(
					array(
						'ACK'     => 'Failure',
						'MESSAGE' => __( 'Authentication Failure', 'woocommerce-putler-connector' ),
					),
					PUTLER_GATEWAY
				);
				die( $response ); // phpcs:ignore WordPress.Security.EscapeOutput
			}
			wp_send_json(
				array(
					'wpc_version' => WPC_VERSION,
					'wc_version'  => WOOCOMMERCE_VERSION,
				)
			);
		}

		/**
		 * Function to handle deprecation notice for Putler desktop.
		 *
		 * @return void.
		 */
		public function putler_desktop_deprecated() {
			if ( empty( $this->api_token ) || empty( $this->email_address ) ) {
				/* translators: Putler URL */
				echo wp_kses_post( '<div id="putler_configure_message" class="updated fade error"><p>' . sprintf( __( 'Putler Connector for Putler desktop has deprecated. Please upgrade to <strong><a href="%s" target="_blank">Putler Web</a></strong>.', 'woocommerce-putler-connector' ), 'https://web.putler.com/' ) . '</p></div>' );
			}
		}

		/**
		 * Function to generate temp pc token.
		 *
		 * @param int    $str_length String length.
		 * @param string $type Type.
		 *
		 * @return string.
		 */
		public function generate_random_string( $str_length = 0, $type = 'Alphanumeric' ) {

			$str_alphanumeric = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			$str_alphabet     = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$str_random       = '';

			switch ( $type ) {
				case 'Alphanumeric':
					for ( $i = 0; $i < $str_length; $i ++ ) {
						$str_random .= $str_alphanumeric[ wp_rand( 0, strlen( $str_alphanumeric ) - 1 ) ];
					}
					break;
				case 'Alphabets':
					for ( $i = 0; $i < $str_length; $i ++ ) {
						$str_random .= $str_alphabet[ wp_rand( 0, strlen( $str_alphabet ) - 1 ) ];
					}
					break;
			}

			return $str_random;
		}

		/**
		 * Function to add admin menu.
		 *
		 * @return void.
		 */
		public function add_admin_menu_page() {
			add_management_page( __( 'Putler Connector', 'woocommerce-putler-connector' ), __( 'Putler Connector', 'woocommerce-putler-connector' ), 'manage_options', 'putler_connector', array( &$this, 'display_page' ) );
		}

		/**
		 * Function to handle the display page.
		 *
		 * @return void.
		 */
		public function display_page() {

			$authenticate = 0;

			$last_synced_date = get_option( 'sa_' . PUTLER_GATEWAY_PREFIX . '_last_updated' );
			$authenticated    = get_option( 'putler_connector_authenticated' );
			$display_msg      = '<span class="dashicons dashicons-yes" style="color:#0CCC0C;font-size: 2em;width: 1em;height: 1em;line-height: 0.7;"></span>' . __( 'Putler is connected', 'woocommerce-putler-connector' );

			?>

			<script type="text/javascript">
				var send_resync_request = function () {
					jQuery.ajax({
						type: 'POST',
						url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=putler_connector_resync' : ajaxurl + '?action=putler_connector_resync',
						dataType: "text",
						action: 'putler_connector_resync',
						security: '<?php echo esc_html( wp_create_nonce( 'ptwp-security' ) ); ?>',
						success: function (response) {
							response = JSON.parse(response);
							if (response.ack === 'Success') {
								window.location.href = "<?php echo esc_url_raw( $this->settings_url ); ?>";
							}
						}
					});
				};
			</script>

			<?php

			if ( empty( $authenticated ) ) {
				$authenticate = 1;

				if ( ( ! empty( $this->api_token ) && empty( $this->email_address ) ) || ( empty( $this->api_token ) && ! empty( $this->email_address ) ) ) { // code to delete both the email & token if only one of them is present.
					delete_option( 'putler_connector_settings' );
					$this->api_token     = '';
					$this->email_address = '';
				}
			}

			if ( ! empty( $last_synced_date ) ) {
				$display_msg .= __( ' & last sync date was ', 'woocommerce-putler-connector' ) . $last_synced_date . '.';
			} else {
				$display_msg .= __( ' & No orders have been synced yet.', 'woocommerce-putler-connector' );
			}

			$action = ( ! empty( $_REQUEST['action'] ) ) ? trim( sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) ) : ''; // phpcs:ignore
			if ( ( ! empty( $action ) && PUTLER_GATEWAY_PREFIX . '_activate' === $action ) || ( empty( $this->api_token ) || empty( $this->email_address ) ) ) {
				$authenticate = 1;
			}

			if ( 1 === $authenticate ) {
				$display_msg = __( 'Trying to Connect to Putler...', 'woocommerce-putler-connector' );
			} else {

				$resync           = get_transient( 'putler_connector_resync' );
				$resync_on_update = get_option( '_' . PUTLER_GATEWAY_PREFIX . '_delete_and_resync' );

				if ( ! empty( $resync_on_update ) ) {
					$resync = 1;
					?>

					<script type="text/javascript">
						send_resync_request();
					</script>

					<?php

					delete_option( '_' . PUTLER_GATEWAY_PREFIX . '_delete_and_resync' );
				}

				if ( ! empty( $resync ) ) {
					if ( ! empty( sanitize_text_field( wp_unslash( $_REQUEST['post_activation'] ) ) ) ) { // phpcs:ignore
						$display_msg = __( 'Your transactions are getting synced with Putler. Please check after some time.', 'woocommerce-putler-connector' );
					} else {
						$display_msg = __( 'Your resync request is under process and your transactions will be resynced soon.', 'woocommerce-putler-connector' );
					}
				} else {
					$display_msg .= '</div> <div> <input type="submit" id="putler_connector_resync" class="button-primary" value="' . __( 'Resync Data', 'woocommerce-putler-connector' ) . '">';
				}
			}

			echo wp_kses_post(
				'<div class="wrap" id="putler_connector_settings_page" style="font-size: 1.1em;">
                    <h1>' . __( 'Putler Connector', 'woocommerce-putler-connector' ) . '</h1> <br/>
                    <div>' . $display_msg . '</div>
                  </div>'
			);

			if ( 1 === $authenticate ) {
				$this->authenticate();
			}

			?>
			<script type="text/javascript">
				document.addEventListener('DOMContentLoaded', function() {
					jQuery(document).on('click', '#putler_connector_resync', function () {
						send_resync_request();
					});
				});
			</script>


			<?php

		}

		/**
		 * Function to handle the authenticate process.
		 *
		 * @return void.
		 */
		public function authenticate() {

			$authenticate = 1;
			if ( ! empty( $this->api_token ) && ! empty( $this->email_address ) ) { // for existing users.

				$result = $this->validate_api_info( $this->api_token, $this->email_address, 'validate', array( 'Site-URL' => site_url() ) );

				if ( ! is_wp_error( $result ) ) {

					$res_body = ( ! empty( $result['body'] ) ) ? json_decode( $result['body'], true ) : array();

					if ( ( ! empty( $result['response']['code'] ) && 200 === intval( $result['response']['code'] ) ) &&
							( ! empty( $res_body['ack'] ) && 'Success' === $res_body['ack'] ) ) {
						$authenticate = 0;

						update_option( 'putler_connector_authenticated', 1 );

						$msg = wp_kses_post( '<span>' . __( 'Successfully Authenticated!!!', 'woocommerce-putler-connector' ) . ' </span> <span class="dashicons dashicons-yes" style="color:#0CCC0C;font-size: 2em;width: 1em;height: 1em;line-height: 0.7;"></span>' );
						$this->show_message( $msg );

						?>
						<script type="text/javascript">
							setTimeout(function () {
								window.location.href = "<?php echo esc_url_raw( $this->settings_url ); ?>";
							}, 3000);
						</script>
						<?php

					} else {
						delete_option( 'putler_connector_authenticated' );
						delete_option( 'putler_connector_settings' );

						?>

						<script type="text/javascript">
							setTimeout(function () {
								window.location.href = "<?php echo esc_url_raw( $this->settings_url ); ?>";
							}, 3000);
						</script>

						<?php
						exit;
					}
				} else {
					delete_option( 'putler_connector_authenticated' );
					delete_option( 'putler_connector_settings' );

					?>

					<script type="text/javascript">
						setTimeout(function () {
							window.location.href = "<?php echo esc_url_raw( $this->settings_url ); ?>";
						}, 3000);
					</script>

					<?php
					exit;
				}
			}

			if ( 1 === $authenticate ) { // for new users.
				$existing_user = 1;

				if ( empty( $this->api_token ) ) {
					$this->api_token = $this->generate_random_string( 15 );
					update_option( 'putler_connector_temp_token', $this->api_token ); // TODO: retest & confirm.
					$existing_user = 0;
				}

				// getting temp token.
				$result = $this->validate_api_info( $this->api_token, $this->email_address, 'get_temp_token', array( 'Site-URL' => site_url() ) );

				if ( ! is_wp_error( $result ) ) {

					$res_body = ( ! empty( $result['body'] ) ) ? json_decode( $result['body'], true ) : array();

					if ( ( ! empty( $result['response']['code'] ) && 200 === $result['response']['code'] ) &&
							( ! empty( $res_body['ack'] ) && 'Success' === $res_body['ack'] ) ) {

						$msg = __( 'Authenticating...', 'woocommerce-putler-connector' );
						$this->show_message( $msg );

						?>
						<script type="text/javascript">
							var start_timestamp = Date.now();
							var pc_connection_heartbeat = function ( start_timestamp ) {

								jQuery.ajax({
									type: 'POST',
									url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=putler_connector_connection_heartbeat' : ajaxurl + '?action=putler_connector_connection_heartbeat',
									dataType: "text",
									action: 'putler_connector_connection_heartbeat',
									success: function (response) {
										response = JSON.parse(response);
										if (response.ack == 'Success') {
											jQuery("#putler_connector_settings_page").append('<br/> <div><span>Successfully Authenticated!!!</span> <span class="dashicons dashicons-yes" style="color:#0CCC0C;font-size: 2em;width: 1em;height: 1em;line-height: 0.7;"></span></div>');

											<?php update_option( '_' . PUTLER_GATEWAY_PREFIX . '_delete_and_resync', 1, 'no' ); // for enabling resync on update. ?>

											setTimeout(function () {
												window.location.href = "<?php echo esc_url_raw( $this->settings_url ) . '&post_activation=1'; ?>";
											}, 3000);

										} else {
											current_timestamp = Date.now();
											if( current_timestamp - start_timestamp >= 30000 ) {
													jQuery("#putler_connector_settings_page").html('<h1><?php echo esc_html__( 'Putler Connector', 'woocommerce-putler-connector' ); ?></h1>'+
																									'<br/>'+
																									'<div style="background: lightyellow;border: 0.2em solid #c5c593;border-radius: 0.2em;padding: 0.75em 1em;">'+
																										'<h2>Something went wrong while authenticating!</h2>'+
																										'<div>Here\'s what to do next:'+
																											'<ul>'+
																												'<li style="list-style-type:disc;margin-left:1em;margin-bottom:0.5em;">'+
																														'Do you have any security plugin active on your website?'+
																														'<br/>'+
																														'If you do, kindly go to your security plugin and whitelist this URL "<strong>https://web.putler.com/</strong>" Once whitelisted, try authenticating your store again.'+
																												'</li>'+
																												'<li style="list-style-type:disc;margin-left:1em;margin-bottom:0.5em;">If you still face the same issue even after white-listing OR you don\'t have any security plugin active - <a href="https://www.putler.com/contact-us/" target="_blank">send us a message</a>, we will get back to you ASAP</li>'+
																											'</ul>'+
																									'</div>');
											} else {
												setTimeout(function ( start_timestamp ) {
													pc_connection_heartbeat( start_timestamp );
												}, 3000, start_timestamp);
											}
										}
									}
								});
							}

							pc_connection_heartbeat(start_timestamp);
						</script>

						<?php

					} else {

						if ( empty( $existing_user ) ) {

							$msg = __( 'Authentication Failed.', 'woocommerce-putler-connector' ) . '<br/> <br/> <div class="notice notice-error"> ' . sprintf( /* translators: %s: Name of ecommerce gateway */ esc_html__( 'Please make sure that you have added an %s account in Putler. If you do not have a Putler account, you can create one for free and enjoy trial for 14 days.', 'woocommerce-putler-connector' ), PUTLER_GATEWAY ) . ' <strong><i><a href="https://web.putler.com/#!/signup" target="_blank">' . __( 'Try Putler for free!', 'woocommerce-putler-connector' ) . '</a></i></strong>. <br/> <br/>' . sprintf( /* translators: %s: Name of ecommerce gateway */ esc_html__( 'Once the %s account has been added successfully, please click ', 'woocommerce-putler-connector' ), PUTLER_GATEWAY ) . '<a href="">' . __( 'here', 'woocommerce-putler-connector' ) . '.</a> </div>';

						} else {
							$msg = __( 'Authentication Failed.', 'woocommerce-putler-connector' ) . ' ' . __( 'You would need to reset the account in ', 'woocommerce-putler-connector' ) . ' <strong><a href="https://web.putler.com/" target="_blank">' . __( 'Putler Web ', 'woocommerce-putler-connector' ) . '</a></strong>';
						}

						$this->show_message( $msg );
					}
				} else {
					$msg = __( 'Authentication Failed.', 'woocommerce-putler-connector' ) . ' <a href="">Try again</a>';
					$this->show_message( $msg );
				}
			}
		}

		/**
		 * Function for handling heartbeat request during authentication process.
		 *
		 * @return void.
		 */
		public function connection_heartbeat() {

			$response = array( 'ack' => 'Failure' );

			$authenticated = get_option( 'putler_connector_authenticated' );
			$settings      = get_option( 'putler_connector_settings', null );

			if ( ! empty( $settings ) && ! empty( $authenticated ) ) {
				$this->api_token     = ( ! empty( $settings['api_token'] ) ) ? $settings['api_token'] : null;
				$this->email_address = ( ! empty( $settings['email_address'] ) ) ? $settings['email_address'] : null;

				if ( ! empty( $this->api_token ) && ! empty( $this->email_address ) ) {
					$response = array( 'ack' => 'Success' );
				}
			}

			wp_send_json( $response );
		}

		/**
		 * Function to handle resync request.
		 *
		 * @return void.
		 */
		public function send_resync_request() {

			check_ajax_referer( 'ptwp-resync', 'security' );

			$response = array( 'ack' => 'Failure' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json( $response );
			}

			$result = $this->validate_api_info(
				$this->api_token,
				$this->email_address,
				'delete_and_sync',
				array(
					'Gateway'  => PUTLER_GATEWAY,
					'Site-URL' => site_url(),
				)
			);

			if ( ! is_wp_error( $result ) ) {
				set_transient( 'putler_connector_resync', 1, ( HOUR_IN_SECONDS * 3 ) );
				$response = array( 'ack' => 'Success' );
			}

			wp_send_json( $response );
		}

		/**
		 * Function to transaction process completion.
		 *
		 * @return void.
		 */
		public function putler_connector_sync_complete() {
			if ( empty( $this->authenticate_request() ) ) {
				header( 'Content-Type: text/xml' );
				while ( ob_get_contents() ) {
					ob_clean();
				}
				$response = $this->generate_valid_xml_from_array(
					array(
						'ACK'     => 'Failure',
						'MESSAGE' => __( 'Authentication Failure', 'woocommerce-putler-connector' ),
					),
					PUTLER_GATEWAY
				);
				die( $response ); // phpcs:ignore WordPress.Security.EscapeOutput
			}
			delete_transient( 'putler_connector_resync' );
			wp_send_json( array( 'ack' => 'Success' ) );
		}

		/**
		 * Function to handle temp token for authentication process.
		 *
		 * @return void.
		 */
		public function get_temp_token() {

			$this->api_token   = get_option( 'putler_connector_temp_token', false );
			$temp_pc_token     = ( ! empty( $_SERVER['HTTP_X_PC_TEMP_TOKEN'] ) ) ? trim( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_PC_TEMP_TOKEN'] ) ) ) : '';
			$temp_putler_token = ( ! empty( $_SERVER['HTTP_X_PUTLER_TEMP_TOKEN'] ) ) ? trim( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_PUTLER_TEMP_TOKEN'] ) ) ) : '';

			if ( ! empty( $temp_pc_token ) && $temp_pc_token === $this->api_token ) {
				$response = array(
					'ack'     => 'Success',
					'MESSAGE' => __( 'Authentication Successful', 'woocommerce-putler-connector' ),
				);

				update_option( 'putler_connector_putler_temp_token', $temp_putler_token );

				$result = $this->validate_api_info(
					$this->api_token,
					$this->email_address,
					'get_auth_token',
					array(
						'X-Putler-Temp-Token' => $temp_putler_token,
						'Site-URL'            => site_url(),
					)
				);

				if ( ! is_wp_error( $result ) ) {

					$res_body = ( ! empty( $result['body'] ) ) ? json_decode( $result['body'], true ) : array();

					if ( ! ( ( ! empty( $result['response']['code'] ) && 200 === intval( $result['response']['code'] ) ) &&
								( ! empty( $res_body['ack'] ) && 'Success' === $res_body['ack'] ) ) ) {
						$msg = __( 'Authentication Failed.', 'woocommerce-putler-connector' ) . ' <a href="">Try again</a>';
						$this->show_message( $msg );
					}
				} else {
					$msg = __( 'Authentication Failed.', 'woocommerce-putler-connector' ) . ' <a href="">Try again</a>';
					$this->show_message( $msg );
				}
			} else {
				$response = array(
					'ack'     => 'Failure',
					'MESSAGE' => __( 'Authentication Failure', 'woocommerce-putler-connector' ),
				);
				$msg      = __( 'Authentication Failed.', 'woocommerce-putler-connector' ) . ' <a href="">Try again</a>';
				$this->show_message( $msg );
			}

			wp_send_json( $response );
		}

		/**
		 * Function for getting authorization token.
		 *
		 * @return void.
		 */
		public function get_auth_token() {
			$this->api_token   = get_option( 'putler_connector_temp_token', false );
			$temp_putler_token = get_option( 'putler_connector_putler_temp_token', false );

			$temp_pc_token      = ( ! empty( $_SERVER['HTTP_X_PC_TEMP_TOKEN'] ) ) ? trim( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_PC_TEMP_TOKEN'] ) ) ) : ''; // phpcs:ignore
			$temp_putler_token1 = ( ! empty( $_SERVER['HTTP_X_PUTLER_TEMP_TOKEN'] ) ) ? trim( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_PUTLER_TEMP_TOKEN'] ) ) ) : ''; // phpcs:ignore
			$settings           = array();

			if ( ( ! empty( $temp_pc_token ) && $temp_pc_token === $this->api_token ) && ( ! empty( $temp_putler_token1 ) && $temp_putler_token1 === $temp_putler_token ) ) {
				$response = array(
					'ack'     => 'Success',
					'MESSAGE' => __( 'Authentication Successful', 'woocommerce-putler-connector' ),
				);

				$this->email_address       = ( ! empty( $_SERVER['HTTP_X_PUTLER_EMAIL'] ) ) ? trim( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_PUTLER_EMAIL'] ) ) ) : ''; // phpcs:ignore
				$settings['email_address'] = $this->email_address;
				$this->api_token           = ( ! empty( $_SERVER['HTTP_X_PUTLER_AUTH_TOKEN'] ) ) ? trim( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_PUTLER_AUTH_TOKEN'] ) ) ) : ''; // phpcs:ignore
				$settings['api_token']     = $this->api_token;

				$result = $this->validate_api_info(
					$this->api_token,
					$this->email_address,
					'set_auth_token',
					array(
						'X-Putler-Temp-Token' => $temp_putler_token,
						'Gateway'             => PUTLER_GATEWAY,
						'AccountName'         => get_bloginfo( 'name' ),
						'Site-URL'            => site_url(),
					)
				);
				if ( ! is_wp_error( $result ) ) {

					$res_body = ( ! empty( $result['body'] ) ) ? json_decode( $result['body'], true ) : array();

					if ( ( ! empty( $result['response']['code'] ) && 200 === intval( $result['response']['code'] ) ) &&
							( ! empty( $res_body['ack'] ) && 'Success' === $res_body['ack'] ) ) {

						delete_option( 'putler_connector_temp_token' );
						delete_option( 'putler_connector_putler_temp_token' );

						update_option( 'putler_connector_settings', $settings );
						update_option( 'putler_connector_authenticated', 1 );

						?>
						<script type="text/javascript">
							jQuery("#putler_connector_settings_page").append('<br/> <br/> <div></div>');
						</script>
						<?php

					} else {
						$msg = __( 'Authentication Failed.', 'woocommerce-putler-connector' ) . ' <a href="">Try again</a>';
						$this->show_message( $msg );
					}
				} else {
					$msg = __( 'Authentication Failed.', 'woocommerce-putler-connector' ) . ' <a href="">Try again</a>';
					$this->show_message( $msg );
				}
			} else {
				$response = array(
					'ack'     => 'Failure',
					'MESSAGE' => __( 'Authentication Failure', 'woocommerce-putler-connector' ),
				);
				$msg      = __( 'Authentication Failed.', 'woocommerce-putler-connector' ) . ' <a href="">Try again</a>';
				$this->show_message( $msg );
			}

			wp_send_json( $response );
		}

		/**
		 * Function to display authentication messages.
		 *
		 * @param string $message The message.
		 *
		 * @return void.
		 */
		private function show_message( $message ) {
			if ( ! empty( $message ) ) {
				?>
				<script type="text/javascript">
					var message = '<?php echo wp_kses_post( $message ); ?>';
					jQuery("#putler_connector_settings_page").append('<br/> <div>' + message + '</div>');
				</script>
				<?php
			}
		}

		/**
		 * Function to handle validation of credentials.
		 *
		 * @param string $token The token.
		 * @param string $email Email address.
		 * @param string $action Action name.
		 * @param array  $headers The headers.
		 *
		 * @return array.
		 */
		private function validate_api_info( $token = '', $email = '', $action = '', $headers = array() ) {
			// Validate with API server.
			$result = wp_remote_post(
				$this->api_url,
				array(
					'headers' => array_merge(
						array(
							'Authorization' => 'Basic ' . base64_encode( $email . ':' . $token ), // phpcs:ignore
							'User-Agent'    => 'Putler Connector/' . $this->version,
						),
						$headers
					),
					'body'    => array( 'action' => $action ),
				)
			);

			return $result;
		}

		/**
		 * Function for generating XML from given array.
		 *
		 * @param array  $array The array.
		 * @param string $node_name The node name.
		 *
		 * @return string.
		 */
		public function generate_xml_from_array( $array, $node_name ) {
			$xml = '';
			if ( is_array( $array ) || is_object( $array ) ) {
				foreach ( $array as $key => $value ) {
					if ( is_numeric( $key ) ) {
						$key = $node_name;
					}

					$node_value = '';

					if ( ! empty( $value ) ) {
						$node_value = "\n" . $this->generate_xml_from_array( $value, $node_name );
					}

					$xml .= '<' . $key . '>' . $node_value . '</' . $key . '>' . "\n";
				}
			} else {
				$xml = htmlspecialchars( $array, ENT_QUOTES ) . "\n";
			}

			return $xml;
		}

		/**
		 * Function for generating XML.
		 *
		 * @param array  $array The array.
		 * @param string $node_block The node block.
		 * @param string $node_name The node name.
		 *
		 * @return string.
		 */
		public function generate_valid_xml_from_array( $array = array(), $node_block = PUTLER_GATEWAY, $node_name = 'node' ) {
			$xml  = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
			$xml .= '<!--email_off--><' . $node_block . '>' . "\n";
			$xml .= $this->generate_xml_from_array( $array, $node_name ); // phpcs:ignore WordPress.Security.NonceVerification
			$xml .= '</' . $node_block . '><!--email_off-->' . "\n";

			return $xml;
		}

		/**
		 * Function to get transaction data based on request from Putler.
		 *
		 * @return void.
		 */
		public function putler_connector_get_data() {
			$response = array(
				'status'  => 'OK',
				'message' => '',
				'results' => array(),
			);

			$is_valid = $this->authenticate_request();

			header( 'Content-Type: text/xml' );
			while ( ob_get_contents() ) {
				ob_clean();
			}

			if ( empty( $is_valid ) ) {
				$response = array(
					'ACK'     => 'Failure',
					'MESSAGE' => __( 'Authentication Failure', 'woocommerce-putler-connector' ),
				);
				$response = $this->generate_valid_xml_from_array( $response, PUTLER_GATEWAY );
				die( $response ); // phpcs:ignore WordPress.Security.EscapeOutput
			}

			if ( empty( sanitize_text_field( wp_unslash( $_REQUEST['STARTDATE'] ) ) ) || empty( sanitize_text_field( wp_unslash( $_REQUEST['ENDDATE'] ) ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$response = array(
					'ACK'     => 'Failure',
					'MESSAGE' => __( 'Params Missing', 'woocommerce-putler-connector' ),
				);
				$response = $this->generate_valid_xml_from_array( $response, PUTLER_GATEWAY );
				die( $response ); // phpcs:ignore WordPress.Security.EscapeOutput
			}
			update_option( 'sa_' . PUTLER_GATEWAY_PREFIX . '_last_updated', current_time( 'Y-m-d H:i:s' ) ); // updating the last synced time.

			$offset       = ( ! empty( $_REQUEST['OFFSET'] ) ) ? absint( sanitize_text_field( wp_unslash( $_REQUEST['OFFSET'] ) ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification
			$sub_offset   = ( ! empty( $_REQUEST['SUBOFFSET'] ) ) ? absint( sanitize_text_field( wp_unslash( $_REQUEST['SUBOFFSET'] ) ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification
			$limit        = ( ! empty( $_REQUEST['LIMIT'] ) ) ? absint( sanitize_text_field( wp_unslash( $_REQUEST['LIMIT'] ) ) ) : 100; // phpcs:ignore WordPress.Security.NonceVerification
			$send_headers = ( isset( $_REQUEST['SENDHEADERS'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['SENDHEADERS'] ) ) : 'true'; // phpcs:ignore WordPress.Security.NonceVerification

			// Getting the data from ecommerce plugins.
			$params = array(
				'start_date' => sanitize_text_field( wp_unslash( $_REQUEST['STARTDATE'] ) ), // phpcs:ignore WordPress.Security.NonceVerification
				'end_date'   => sanitize_text_field( wp_unslash( $_REQUEST['ENDDATE'] ) ), // phpcs:ignore WordPress.Security.NonceVerification
				'offset'     => $offset,
				'sub_offset' => $sub_offset,
				'limit'      => $limit,
			);
			$params = apply_filters( 'putler_connector_get_orders', $params );

			// Check if all orders are received...
			foreach ( (array) $params as $connector => $orders ) {

				$ocount           = ( ! empty( $orders['count'] ) ) ? $orders['count'] : 0;
				$prev_start_limit = ( ! empty( $orders['last_start_limit'] ) ) ? $orders['last_start_limit'] : 0;

				// Send one batch to the server.
				if ( ! empty( $orders['data'] ) && is_array( $orders['data'] ) && count( $orders['data'] ) > 0 ) {

					if ( PUTLER_GATEWAY === $connector ) { // New API Code.
						$data = ( ! empty( $orders['data'] ) && is_array( $orders['data'] ) && count( $orders['data'] ) > 0 ) ? $this->array_to_csv( $orders['data'], $send_headers ) : '';

						if ( $ocount < $limit ) {
							$ack    = 'Success';
							$offset = 0;
						} else {
							$ack    = 'SuccessWithWarning';
							$offset = $ocount + $prev_start_limit;
						}

						$response = array(
							'ACK'    => $ack,
							'DATA'   => $data,
							'OFFSET' => $offset,
						);

						update_option( 'sa_' . PUTLER_GATEWAY_PREFIX . '_last_updated', current_time( 'Y-m-d H:i:s' ) ); // updating the last synced time.

						$response = $this->generate_valid_xml_from_array( $response, PUTLER_GATEWAY );
					}
				} else {
					$response = array(
						'ACK'    => 'Success',
						'DATA'   => '',
						'OFFSET' => ( $ocount + $prev_start_limit ),
					);
					$response = $this->generate_valid_xml_from_array( $response, PUTLER_GATEWAY );
				}
			}

			die( $response ); // phpcs:ignore WordPress.Security.EscapeOutput
		}

		/**
		 * Function convert array to csv.
		 *
		 * @param array  $orders The orders.
		 * @param string $send_headers Whether to send the headers or not.
		 *
		 * @return string.
		 */
		public function array_to_csv( $orders = array(), $send_headers = 'true' ) {
			if ( empty( $orders ) ) {
				return '';
			}

			$oid = ob_start();
			$fp  = fopen( 'php://output', 'a+' );
			foreach ( (array) $orders as $index => $row ) {
				if ( 0 === $index && 'true' === $send_headers ) {
					fputcsv( $fp, array_keys( $row ) );
				}
				fputcsv( $fp, $row );
			}
			fclose( $fp ); // phpcs:ignore
			$csv_data = ob_get_clean();
			if ( ob_get_clean() > 0 ) {
				ob_end_clean();
			}

			return $csv_data;
		}

		/**
		 * Function to authenticate request.
		 *
		 * @return boolean authentication flag.
		 */
		protected function authenticate_request() {
			$authorization_header = ( ! empty( $_SERVER['HTTP_AUTHORIZATION'] ) ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_AUTHORIZATION'] ) ) : '';
			if ( ! empty( $authorization_header ) && 0 === strpos( $authorization_header, 'Basic ' ) ) {
				$base64_credentials = substr( $authorization_header, 6 );
				$auth               = base64_decode( $base64_credentials ); // phpcs:ignore
				if ( empty( $auth ) ) {
					return false;
				}
				$credentials = explode( ':', $auth );
				if ( empty( $credentials ) || ! is_array( $credentials ) || ( is_array( $credentials ) && ( empty( $credentials[0] ) || empty( $credentials[1] ) ) ) ) {
					return false;
				}
				return ( trim( $credentials[0] ) === $this->email_address && trim( $credentials[1] ) === $this->api_token ) ? true : false;
			}
			return false;
		}
	}
}

