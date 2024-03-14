<?php
/**
 * Nouvello WeManage Worker Init Class
 *
 * @package    Nouvello WeManage Worker
 * @subpackage Core
 * @author     Nouvello Studio
 * @copyright  (c) Copyright by Nouvello Studio
 * @since      1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if ( ! class_exists( 'Nouvello_WeManage_Worker_Init' ) ) :

	/**
	 * Init Class.
	 *
	 * @since 1.0
	 */
	final class Nouvello_WeManage_Worker_Init {

		/**
		 * Constructor.
		 */
		public function __construct() {

			// installation.
			register_activation_hook( NSWMW_ROOT_PATH . 'nouvello-wemanage-worker.php', array( $this, 'nouvello_api_db_install' ) );
			register_activation_hook( NSWMW_ROOT_PATH . 'nouvello-wemanage-worker.php', array( $this, 'nouvello_setup_wp_worker' ) );
			register_activation_hook( NSWMW_ROOT_PATH . 'nouvello-wemanage-worker.php', array( $this, 'nouvello_setup_wc_worker' ) );

			// activation.
			register_activation_hook( NSWMW_ROOT_PATH . 'nouvello-wemanage-worker.php', array( $this, 'nouvello_wemanage_worker_activation' ) );
			add_action( 'admin_init', array( $this, 'nouvello_activation_redirect' ) ); // redirect after activation.

			// deactivation.
			register_deactivation_hook( NSWMW_ROOT_PATH . 'nouvello-wemanage-worker.php', array( $this, 'nouvello_wemanage_worker_deactivation' ) );

			// uninstallation.
			register_uninstall_hook( NSWMW_ROOT_PATH . 'nouvello-wemanage-worker.php', 'nouvello_wemanage_worker_uninstall' );

			add_filter( 'plugin_row_meta', array( $this, 'add_connection_key_link' ), 10, 2 );
			// extension.
			add_action( 'init', array( $this, 'nouvello_extend_wc_api_worker' ) );
			add_filter( 'all_plugins', array( $this, 'nouvello_wl' ) );

			// enqueue.
			add_action( 'admin_enqueue_scripts', array( $this, 'nouvello_wemanage_enqueue' ) );
			// ajax.
			add_action( 'wp_ajax_enable_manual_installation', array( $this, 'enable_manual_installation' ) );

			// update complete callback.
			add_action( 'upgrader_process_complete', array( $this, 'upgrader_process_complete_callback' ), 10, 2 );

			// plugin settings page.
			add_action( 'admin_menu', array( $this, 'nouvello_register_admin_page_menu' ) );

		}

		/**
		 * API - Install Database.
		 */
		public function nouvello_api_db_install() {
			global $wpdb;

			$table_name = $wpdb->prefix . 'nouvello_api_keys';
			$charset_collate = $wpdb->get_charset_collate();

			$sql = 'CREATE TABLE IF NOT EXISTS ' . $table_name . ' (
				key_id BIGINT UNSIGNED NOT NULL auto_increment,
				user_id BIGINT UNSIGNED NOT NULL,
				description varchar(200) NULL,
				permissions varchar(10) NOT NULL,
				consumer_key char(64) NOT NULL,
				consumer_secret char(43) NOT NULL,
				nonces longtext NULL,
				truncated_key char(7) NOT NULL,
				last_access datetime NULL default null,
				PRIMARY KEY  (key_id),
				KEY consumer_key (consumer_key),
				KEY consumer_secret (consumer_secret)
			) ' . $charset_collate . ';';

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			return true;
		}


		/**
		 * Nouvello setup wc worker
		 */
		public function nouvello_setup_wp_worker() {
			if ( '' == get_option( 'nvl_wemanage_worker_wp' ) ) {
				// setup wp application keys.
				$status = $this->remove_all_nouvello_keys( 'nouvello_api_keys' );
				$data = $this->add_nouvello_key( 'nouvello_api_keys' );
				// save to transient - expires in 60 seconds.
				set_transient( 'ns-wmw-key', $data['consumer_key'], 60 );
				set_transient( 'ns-wmw-secret', $data['consumer_secret'], 60 );
				update_option( 'nvl_wemanage_worker_wp', time() ); // prevent running again.
			}
		}

		/**
		 * Nouvello setup wc worker
		 */
		public function nouvello_setup_wc_worker() {
			if ( class_exists( 'woocommerce' ) ) {
				if ( '' == get_option( 'nvl_wemanage_worker_wc' ) ) {
					$status = $this->remove_all_nouvello_keys( 'woocommerce_api_keys' );
					$data = $this->add_nouvello_key( 'woocommerce_api_keys' );
					// save to transient - expires in 60 seconds.
					set_transient( 'ns-wmw-wc-key', $data['consumer_key'], 60 );
					set_transient( 'ns-wmw-wc-secret', $data['consumer_secret'], 60 );
					update_option( 'nvl_wemanage_worker_wc', time() ); // prevent running again.
				} else {
					// fire webhook with plugin activation event. fired when user activates / reactivates from wp admin panel.
					nouvello_wemanage_worker()->webhooks->nouvello_wemanage_webhooks_plugin_reactivation();
				}
			}
		}

		/**
		 * Extend Nouvello wc api worker.
		 */
		public function nouvello_extend_wc_api_worker() {
			if ( class_exists( 'woocommerce' ) ) {
				require NSWMW_ROOT_PATH . '/includes/class-nouvello-wemanage-worker-api-wc-ext-controller.php';
				nouvello_wemanage_worker()->wc_api = new Nouvello_Wemanage_Worker_Api_WC_Ext_Controller();
				require NSWMW_ROOT_PATH . '/includes/class-nouvello-wemanage-worker-api-wc-ext-controller-functions.php';
				nouvello_wemanage_worker()->wc_api_functions = new Nouvello_Wemanage_Worker_Api_WC_Ext_Controller_Functions();
			}
		}

		/**
		 * Add plugin description links in plugins page.
		 *
		 * @param [type] $meta [description].
		 * @param [type] $slug [description].
		 */
		public function add_connection_key_link( $meta, $slug ) {
			if ( WEMANAGE_PLUGIN_FOLDER . '/nouvello-wemanage-worker.php' == $slug ) {
				$trigger = '<a href="#/" id="nouvello-worker-connection-key" ns-wmw-key="' . $this->return_activation_key() . '">' . esc_html__( 'Connection Management', 'ns-wmw' ) . '</a>';

				$settings = '<a href="' . admin_url( 'admin.php?page=wemanage_worker_settings' ) . '">' . esc_html__( 'Settings', 'ns-wmw' ) . '</a>';

				$modal = '
				<!-- The Modal -->
				<div id="ns-wmw-connection-modal" class="ns-wmw-modal">

					<!-- Modal content -->
					<div class="ns-wmw-modal-content">
						<div class="ns-wmw-modal-header">
							<span class="close">&times;</span>
							<h2 style="color:#fff; font-weight: 700">Connection Management</h2>
						</div>

						<div class="ns-wmw-modal-body">
							<div>
							<br>
								<p>There are two ways to connect your website to the management dashboard:</p>
								<h2>Automatic</h2>
								<ol>
									<li>Log into your account.</li>
									<li>Click the Add site button.</li>
									<li>Enter this website\'s URL, admin username and password, and the system will take care of everything.</li>
								</ol>
								<h2>Manual</h2>
								<ol>
									<li>Install and activate the <b>Worker</b> plugin.</li>
									<li>Click <a id="manual-installation-link" style="color:red">Activate</a> and copy the connection key.</li>
									<li>Log into your account.</li>
									<li>Click the Add site button.</li>
									<li>Enter this website\'s URL. When prompted, paste the connection key.</li>
									<li>Note: the connection key must be pasted into your account within 2 minutes of clicking Manual Installation.</li>
								</ol>
								<div>

									<input id="connection-key" type="hidden" value="' . $this->return_activation_key() . '">
									<input id="website-url" type="hidden" value="' . get_home_url() . '">
									<input id="website-name" type="hidden" value="' . get_bloginfo( 'name' ) . '">
									<input id="website-tagline" type="hidden" value="' . get_bloginfo( 'description', 'display' ) . '">
									<input id="ns-wmw-key" type="hidden" value="' . get_transient( 'ns-wmw-key' ) . '">
									<input id="ns-wmw-secret" type="hidden" value="' . get_transient( 'ns-wmw-secret' ) . '">
									<input id="ns-wmw-wc-key" type="hidden" value="' . get_transient( 'ns-wmw-wc-key' ) . '">
									<input id="ns-wmw-wc-secret" type="hidden" value="' . get_transient( 'ns-wmw-wc-secret' ) . '">
									<input id="ns-wmw-plugin-version" type="hidden" value="' . NSWMW_VER . '">
								</div>
								<br><br>
								<p>1. Press activate to start the activation process
								<button type="button" id="activate-btn" class="button" style="color: red; border-color: red;">Activate</button></p>
								<p>2. After activating connection, copy the code below and paste it in the app.</p>
								<p>' . $this->return_activation_key() . '<button type="button" id="copykey-btn" class="button">Copy</button></p>
								<br><br>
								<i>Ver ' . NSWMW_VER . '</i>
								<br>
							</div>
						</div>

					</div>

				</div>';

				$meta[] = $trigger . ', ' . $settings . $modal;

			}

			return $meta;
		}

		/**
		 * Activatio key.
		 *
		 * @return [type] [description]
		 */
		public function return_activation_key() {
			$key = get_option( 'nouvello-worker-activation-key' );
			if ( ! $key ) {
				$key = $this->get_random_string( 8 ) . '-' . $this->get_random_string( 4 ) . '-' . $this->get_random_string( 4 ) . '-' . $this->get_random_string( 8 ) . '-' . $this->get_random_string( 12 );
				add_option( 'nouvello-worker-activation-key', $key );
			}
			return $key;
		}

		/**
		 * Add key.
		 *
		 * @param string $table table name.
		 */
		private function add_nouvello_key( $table ) {

			$user_id = $this->get_super_admin_id(); // get super admin. (returns default ID 1 if none is found).

			$post_array = array(
				'key_id' => '0',
				'description' => 'Nouvello WeManage API Worker',
				'user' => $user_id,
				'permissions' => 'read_write',
			);

			global $wpdb;

			$response = array();

			try {
				if ( empty( $post_array['description'] ) ) {
					// throw new Exception( __( 'Description is missing.', 'woocommerce' ) ); // dev.
					die( 'No description' ); // temp. todo: error handling.
				}
				if ( empty( $post_array['user'] ) ) {
					// throw new Exception( __( 'User is missing.', 'woocommerce' ) ); // dev.
					die( 'No user' ); // temp. todo: error handling.
				}
				if ( empty( $post_array['permissions'] ) ) {
					// throw new Exception( __( 'Permissions is missing.', 'woocommerce' ) ); // dev.
					die( 'permissions' ); // temp. todo: error handling.
				}

				$key_id      = 0;
				$description = sanitize_text_field( wp_unslash( $post_array['description'] ) );
				$permissions = ( in_array( wp_unslash( $post_array['permissions'] ), array( 'read', 'write', 'read_write' ), true ) ) ? sanitize_text_field( wp_unslash( $post_array['permissions'] ) ) : 'read';
				$user_id     = absint( $post_array['user'] );

				$consumer_key    = 'ck_' . $this->nouvello_rand_hash();
				$consumer_secret = 'cs_' . $this->nouvello_rand_hash();

				$data = array(
					'user_id'         => $user_id,
					'description'     => $description,
					'permissions'     => $permissions,
					'consumer_key'    => $this->nouvello_api_hash( $consumer_key, $table ),
					'consumer_secret' => $consumer_secret,
					'truncated_key'   => substr( $consumer_key, -7 ),
				);

				$wpdb->insert(
					$wpdb->prefix . $table,
					$data,
					array(
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
					)
				);

				$key_id                      = $wpdb->insert_id;
				$response                    = $data;
				$response['consumer_key']    = $consumer_key;
				$response['consumer_secret'] = $consumer_secret;

			} catch ( Exception $e ) {
				return array( 'message' => $e->getMessage() );
			}

			return $response;

		}

		/**
		 * Remove key.
		 *
		 * @param  int    $key_id API Key ID.
		 * @param  string $table  table name.
		 * @return bool
		 */
		private function remove_key( $key_id, $table ) {
			global $wpdb;

			$delete = $wpdb->delete( $wpdb->prefix . $table, array( 'key_id' => $key_id ), array( '%d' ) );

			return $delete;
		}


		/**
		 * [remove_all_nouvello_keys description]
		 *
		 * @param  [type] $table [description].
		 * @return [type]        [description]
		 */
		public function remove_all_nouvello_keys( $table ) {

			$keys = $this->get_nouvello_keys( $table );
			$status = array();
			if ( isset( $keys ) && ! empty( $keys ) ) {
				foreach ( $keys as $key ) {
					$status[ $key->key_id ] = $this->remove_key( $key->key_id, $table );
				}
			}
			return $status;
		}

		/**
		 * [get_nouvello_keys description]
		 *
		 * @param  string $table  table name.
		 * @return [type]        [description]
		 */
		public function get_nouvello_keys( $table ) {
			global $wpdb;
			$key_description = 'Nouvello WeManage API Worker';
			$table = $wpdb->prefix . $table;
			// @codingStandardsIgnoreStart - ingore $table
			$keys = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $table . ' WHERE description = %s', $key_description ) );
			// @codingStandardsIgnoreEnd
			return $keys;
		}


		/**
		 * Generate a rand hash.
		 *
		 * @since  2.4.0
		 * @return string
		 */
		public function nouvello_rand_hash() {
			if ( ! function_exists( 'openssl_random_pseudo_bytes' ) ) {
				return sha1( wp_rand() );
			}

			return bin2hex( openssl_random_pseudo_bytes( 20 ) ); // @codingStandardsIgnoreLine
		}

		/**
		 * [nouvello_api_hash description]
		 *
		 * @param  [type] $data  [description].
		 * @param  string $table [description].
		 * @return [type]        [description]
		 */
		private function nouvello_api_hash( $data, $table = '' ) {
			$string = 'wc-api';
			if ( 'nouvello_api_keys' == $table ) {
				$string = 'nouvello-api';
			}
			return hash_hmac( 'sha256', $data, 'wc-api' ); // to do: replace hash with custom string.
		}

		/**
		 * [get_random_string description]
		 *
		 * @param  [type] $count [description].
		 * @return [type]        [description]
		 */
		public function get_random_string( $count ) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randstring = '';
			for ( $i = 0; $i < $count; $i++ ) {
				$randstring .= $characters[ rand( 0, ( strlen( $characters ) - 1 ) ) ];
			}
			return $randstring;
		}

		/**
		 * Get super admin ID - if none found returns ID 1 by default.
		 *
		 * @return [type] [description]
		 */
		public function get_super_admin_id() {
			$user_id = 1; // default user ID to set when creating key. This assumes super admin ID is 1 which is default for wp.
			// in some cases a super admin can have a different ID.
			// These cased are usually achived by manually by modifing the user IDS in the database to increase security and confused poternial hackers.
			// Let's found out.
			$super_admins = get_site_option( 'site_admins', array( 'admin' ) );
			if ( isset( $super_admins ) && ! empty( $super_admins ) && isset( $super_admins[0] ) ) {
				$super_admin_name = $super_admins[0];
				$user = get_user_by( 'login', $super_admin_name );
				if ( isset( $user ) && ! empty( $user ) && isset( $user->ID ) && is_numeric( $user->ID ) && is_super_admin( $user->ID ) ) {
					// if we have a super user ID - we set it here.
					$user_id = $user->ID;
				} else {
					$users_query = new WP_User_Query(
						array(
							'role' => 'administrator',
							'orderby' => 'ID',
						)
					);
					$results = $users_query->get_results();
					if ( isset( $results ) && ! empty( $results ) && isset( $results[0] ) ) {
						$super_admin = $results[0];
						if ( isset( $super_admin ) && ! empty( $super_admin ) && isset( $super_admin->ID ) && is_numeric( $super_admin->ID ) && is_super_admin( $super_admin->ID ) ) {
							// if we have a super user ID - we set it here.
							$user_id = $super_admin->ID;
						}
					}
				}
			}
			return $user_id;
		}

		/**
		 * Nouvello WL.
		 *
		 * @param  [type] $ap [description].
		 * @return [type]     [description]
		 */
		public function nouvello_wl( $ap ) {
			if ( get_option( 'nvl_wl_n' ) || get_option( 'nvl_wl_d' ) || get_option( 'nvl_wl_a' ) ) {
				if ( isset( $ap ) ) {
					foreach ( $ap as $k => $p ) {
						if ( 'Nouvello WeManage Worker' === $p['Name'] ) {
							$ap[ $k ]['Name'] = get_option( 'nvl_wl_n' );
							$ap[ $k ]['Description'] = get_option( 'nvl_wl_d' );
							$ap[ $k ]['Author'] = get_option( 'nvl_wl_a' );
						}
					}
				}
			}
			return $ap;
		}

		/**
		 * [nouvello_wemanage_enqueue description]
		 */
		public function nouvello_wemanage_enqueue() {

			wp_enqueue_style( 'nvl-wemanage-options-css', NSWMW_ROOT_DIR . '/includes/assets/admin/css/connection.min.css', array(), 1, 'all' );

			wp_register_script( 'nvl-wemanage-options-js', NSWMW_ROOT_DIR . '/includes/assets/admin/js/connection.min.js', array( 'jquery' ), 1, 'all' );

			wp_localize_script(
				'nvl-wemanage-options-js',
				'nvl_wemanage_options',
				array(
					'ajax_url'   => site_url() . '/wp-admin/admin-ajax.php',
					'ajax_nonce' => wp_create_nonce( 'nvl_admin_nonce' ),
					'wc_active'  => ( class_exists( 'woocommerce' ) ) ? true : false,
					'connection_key' => $this->return_activation_key(),
				)
			);

			wp_enqueue_script( 'nvl-wemanage-options-js' );

		}

		/**
		 * Ajax call back for enable manual installation
		 */
		public function enable_manual_installation() {
			check_ajax_referer( 'nvl_admin_nonce', 'nonce' );
			set_transient( 'nvl_wemanage_manual', true, 120 ); // enable manual mode for 120 seconds.
			die( 'enabled' );
		}

		/**
		 * Update complete callback.
		 *
		 * @param  [type] $upgrader_object [description].
		 * @param  [type] $options         [description].
		 */
		public function upgrader_process_complete_callback( $upgrader_object, $options ) {
			$current_plugin_path_name = WEMANAGE_PLUGIN_FOLDER . '/nouvello-wemanage-worker.php';

			if ( 'update' == $options['action'] && 'plugin' == $options['type'] ) {
				if ( isset( $options['plugins'] ) ) {
					foreach ( $options['plugins'] as $each_plugin ) {
						if ( $each_plugin == $current_plugin_path_name ) {
							// fire webhook with plugin update event.
							nouvello_wemanage_worker()->webhooks->nouvello_wemanage_webhooks_plugin_update();
						}
					}
				}
			}
		}

		/**
		 * Plugin activation
		 */
		public function nouvello_wemanage_worker_activation() {
			add_option( 'nouvello_redirect_after_activation_option', true );
		}

		/**
		 * Redirect after plugin activation
		 */
		public function nouvello_activation_redirect() {
			if ( get_option( 'nouvello_redirect_after_activation_option', false ) ) {
				delete_option( 'nouvello_redirect_after_activation_option' );
				wp_redirect( admin_url( 'admin.php?page=wemanage_worker_settings' ) );
				exit();
			}
		}

		/**
		 * Plugin deactivation
		 */
		public function nouvello_wemanage_worker_deactivation() {
			// fire webhook with plugin deactivation event.
			nouvello_wemanage_worker()->webhooks->nouvello_wemanage_webhooks_plugin_deactivation();
		}

		/**
		 * Register settings page.
		 */
		public function nouvello_register_admin_page_menu() {
			$wemanage_settings_page = add_submenu_page(
				'',
				'WEmanage worker',
				'WEmanage worker',
				'manage_options',
				'wemanage_worker_settings',
				array( $this, 'wemanage_worker_settings_content' )
			);
		}

		/**
		 * Plugin settings page markup.
		 */
		public function wemanage_worker_settings_content() {
			require NSWMW_ROOT_PATH . '/includes/screens/settings.php';
		}


	} // end of class

endif; // end if class exist.
