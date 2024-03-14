<?php

if ( ! class_exists( 'MUCheckConflicts' ) ) {
	class MUCheckConflicts {
		private static $saved_options;
		private static $settings;
		private static $backup_settings;
		private static $current_user_ip;
		private static $real_active_plugins;
		private static $current_plugin = 'check-conflicts/check-conflicts.php';

		public static function init() {
			//add menu
			add_action( 'admin_menu', [ self::class, 'add_menu' ] );

			// override active plugins
			add_filter( 'option_active_plugins', [ self::class, 'change_active_plugins' ], 0 );
			// override active plugins from network
			add_filter( 'site_option_active_sitewide_plugins', [ self::class, 'change_sitewide_plugins' ], 0 );

			// override active theme
			add_filter( 'stylesheet', [ self::class, 'change_stylesheet' ], 0 );
			add_filter( 'template', [ self::class, 'change_template' ], 0 );

			add_filter( 'plugin_action_links', [ self::class, 'manage_plugins_action_links' ], 0, 4 );
			add_filter( 'bulk_actions-plugins', [ self::class, 'manage_plugins_bulk_actions' ] );
		}

		public static function add_menu() {
			add_menu_page( 'Check Conflicts',  __( 'Check Conflicts' ), 'manage_options', 'check_conflicts_settings', [ self::class, 'render_main_page' ], 'dashicons-plugins-checked', 65 );
		}

		private static function handle_post() {
			$page = filter_input( INPUT_GET, 'page' );
			if ( 'check_conflicts_settings' !== $page ) {
				return;
			}
			$save_settings = filter_input( INPUT_POST, 'save_settings' );
			if ( !is_null( $save_settings ) && check_admin_referer( 'save-settings' ) ) {
				self::save_settings();
				self::refresh_page();
			}

			$reset_settings = filter_input( INPUT_POST, 'reset_settings' );
			if ( !is_null( $reset_settings ) && check_admin_referer( 'save-settings' ) ) {
				self::reset_settings();
				self::refresh_page();
			}

			$remove_mu_plugin = filter_input( INPUT_POST, 'remove_mu_plugin' );
			if ( !is_null( $remove_mu_plugin ) && check_admin_referer( 'save-settings' ) ) {
				self::remove_mu_plugin();
				self::refresh_page();
			}

		}

		public static function deactivation() {
			self::reset_settings();
			self::remove_mu_plugin();
			self::refresh_page();
		}

		private static function remove_mu_plugin() {
			global $wp_filesystem;

			if ( WP_Filesystem( request_filesystem_credentials( 'admin.php?page=check_conflicts_settings', 'direct', false, WP_CONTENT_DIR, ['data'] ) ) ) {
				$wp_filesystem->delete( __FILE__ );
			}
		}

		private static function refresh_page() {
			echo '<script type="text/javascript">window.location.reload();</script>';
			exit;
		}

		private static function check_current_ip() {
			$allow = false;
			$settings = self::get_settings();

			if ( !empty( $settings['ips'] ) ) {
				$ips_value = explode( ',', $settings['ips'] );
				$ips = array_map( 'trim', $ips_value );
				$current_ip = self::get_current_ip();

				$allow = in_array( $current_ip, $ips, true );
			}

			return $allow;
		}

		private static function get_new_theme() {
			$settings = self::get_settings();

			return wp_get_theme( $settings['theme'] );
		}

		private static function get_saved_options() {
			if ( is_null( self::$saved_options ) ) {
				self::$saved_options = get_option( 'check_conflicts_settings', [] );

				if ( ! empty( self::$saved_options ) && self::apply_plugin_settings() ) {
					//Prevent update active plugins when settings are enabled
					add_filter( 'pre_update_option_active_plugins', function( $value, $old_value, $option ) {
						return $old_value;
					}, 10, 3 );
					add_filter( 'pre_update_site_option_active_sitewide_plugins', function( $value, $old_value, $option, $network_id ) {
						return $old_value;
					}, 10, 4 );

					add_action( 'admin_notices', [ self::class, 'admin_notices' ] );
				}
			}

			return self::$saved_options;
		}

		public static function change_stylesheet( $active_stylesheet ) {
			if ( self::apply_plugin_settings() ) {
				$new_theme = self::get_new_theme();
				if ( ! empty( $new_theme ) && ! empty( $new_theme['Status'] ) && 'publish' === $new_theme['Status'] ) {
					$active_stylesheet = $new_theme['Stylesheet'];
				}
			}

			return $active_stylesheet;
		}

		public static function change_template( $active_template ) {
			if ( self::apply_plugin_settings() ) {
				$new_theme = self::get_new_theme();
				if ( ! empty( $new_theme ) && ! empty( $new_theme['Status'] ) && 'publish' === $new_theme['Status'] ) {
					$active_template = $new_theme['Template'];
				}

			}

			return $active_template;
		}

		private static function apply_plugin_settings() {
			$active_plugins = self::get_active_plugins();

			if ( !in_array( self::$current_plugin, $active_plugins, true ) ||
					is_multisite() && is_network_admin() ) {
				return false;
			}
			// don't override active plugins if settings are empty
			$override = self::get_saved_options();
			if ( ! $override || ! self::check_current_ip() ) {
				return false;
			}

			return true;
		}

		public static function change_sitewide_plugins( $sitewide_plugins ) {
			if ( ! self::apply_plugin_settings() ) {
				return $sitewide_plugins;
			}
			return [];
		}

		public static function change_active_plugins( $active_plugins ) {
			if ( ! self::apply_plugin_settings() ) {
				return $active_plugins;
			}

			$settings = self::get_settings();
			if ( is_array( $settings['plugins'] ) ) {
				$settings['plugins'][] = self::$current_plugin;
				$active_plugins = $settings['plugins'];
			}

			return $active_plugins;
		}

		public static function manage_plugins_bulk_actions( $bulk_actions ) {
			if ( self::apply_plugin_settings() ) {
				unset( $bulk_actions['activate-selected'], $bulk_actions['deactivate-selected'] );
			}

			return $bulk_actions;
		}

		public static function manage_plugins_action_links( $actions, $plugin_file, $plugin_data, $context ) {
			if ( self::apply_plugin_settings() ) {
				unset( $actions['activate'], $actions['deactivate'] );

				$plugin_url = add_query_arg( 'page', 'check_conflicts_settings', admin_url( 'admin.php' ) );
				$actions['check_conflicts_settings'] = sprintf(
					'<a href="%s" aria-label="%s">%s</a>',
					esc_url( $plugin_url ),
					esc_attr__( 'Go to Check Conflicts Settings' ),
					__( 'Check Conflicts Settings' )
				);
			}

			return $actions;
		}

		private static function wrap_admin_notices( $message )  {
			echo '<div class="notice notice-error"><p>' . $message . '</p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		public static function admin_notices() {
			$page = filter_input( INPUT_GET, 'page' );
			$plugin_url = add_query_arg( 'page', 'check_conflicts_settings', admin_url( 'admin.php' ) );
			if ( 'check_conflicts_settings' !== $page ) {
				self::wrap_admin_notices( sprintf( esc_html__( '%1$sCheck Conflicts%2$s plugin overrides active plugins and theme for your IP.' ), '<a href="' . esc_url( $plugin_url ) . '">', '</a>' ) );
			}

			global $pagenow;
			if ( 'plugins.php' === $pagenow ) {
				self::wrap_admin_notices( sprintf( esc_html__( 'You can\'t activate/deactivate plugins - reset %1$sCheck Conflicts%2$s plugin settings first.' ), '<a href="' . esc_url( $plugin_url ) . '">', '</a>' ) );
			}

			if ( 'themes.php' === $pagenow ) {
				self::wrap_admin_notices( sprintf( esc_html__( 'Activate theme will affect the site but you won\'t see it  - reset %1$sCheck Conflicts%2$s plugin settings first.' ), '<a href="' . esc_url( $plugin_url ) . '">', '</a>' ) );
			}
		}

		private static function save_settings() {
			$old_settings = self::get_settings();

			$ips = filter_input( INPUT_POST, 'ips' );
			if ( !empty( $ips ) ) {
				$new_settings['ips'] = $ips;
			}

			$theme = filter_input( INPUT_POST, 'theme' );
			if ( !empty( $theme ) ) {
				$new_settings['theme'] = $theme;
			}
			$plugins = filter_input( INPUT_POST, 'plugins', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			if ( empty( $plugins ) ) {
				$plugins = [];
			}
			$new_settings['plugins'] = $plugins;

			if ( ! empty( $old_settings ) ) {
				$new_settings = array_merge( $old_settings, $new_settings );
			}
			update_option( 'check_conflicts_settings', $new_settings );
			wp_cache_delete( 'check_conflicts_settings', 'options' );
		}

		private static function reset_settings() {
			delete_option( 'check_conflicts_settings' );
			wp_cache_delete( 'check_conflicts_settings', 'options' );
		}

		private static function get_backup_settings() {
			if ( is_null( self::$backup_settings  ) ) {
				self::$backup_settings = get_option( 'check_conflicts_backup', [] );
			}

			return self::$backup_settings;
		}

		private static function get_active_plugins() {
			if ( is_null( self::$real_active_plugins ) ) {
				global $wpdb;

				$active_plugins = maybe_unserialize( $wpdb->get_var( "SELECT option_value FROM $wpdb->options WHERE option_name = 'active_plugins' LIMIT 1" ) );

				if ( is_multisite() ) {
					$network_id = get_current_network_id();
					$network_plugins = maybe_unserialize( $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM $wpdb->sitemeta WHERE meta_key = 'active_sitewide_plugins' AND site_id = %d", $network_id ) ) );
					$network_plugins = array_keys( $network_plugins );
					$active_plugins = array_unique( array_merge( $network_plugins, $active_plugins ) );
				}

				self::$real_active_plugins = $active_plugins;
			}

			return self::$real_active_plugins;
		}

		private static function get_default_settings( $empty = false ) {
			if ( $empty ) {
				$default_settings = [
					'ips' => '',
					'theme' => '',
					'plugins' => '',
				];
			} else {
				$current_ip = self::get_current_ip();
				$current_theme = get_option( 'template' );
				$active_plugins = self::get_active_plugins();

				$default_settings = [
					'ips' => $current_ip,
					'theme' => $current_theme,
					'plugins' => $active_plugins,
				];
			}

			/**
			 * Filter default settings
			 *
			 * @param array $default_settings Default settings
			 */
			return apply_filters( 'check_conflicts_default_settings', $default_settings );
		}

		/**
		 * Tries to get the public IP address of the current user.
		 *
		 * @return string The IP Address
		 */
		private static function get_current_ip() {
			if ( is_null( self::$current_user_ip ) ) {
				$result = (object) array(
					'ip' => $_SERVER['REMOTE_ADDR'],
					'proxy' => false,
					'proxy_ip' => '',
				);

				/*
				 * This code tries to bypass a proxy and get the actual IP address of the user behind the proxy.
				 * Warning: These values might be spoofed!
				 */
				$ip_fields = array(
					'HTTP_CLIENT_IP',
					'HTTP_X_FORWARDED_FOR',
					'HTTP_X_FORWARDED',
					'HTTP_X_CLUSTER_CLIENT_IP',
					'HTTP_FORWARDED_FOR',
					'HTTP_FORWARDED',
					'REMOTE_ADDR',
				);
				$forwarded = false;
				foreach ( $ip_fields as $key ) {
					if ( true === array_key_exists( $key, $_SERVER ) ) {
						foreach ( explode( ',', $_SERVER[$key] ) as $ip ) {
							$ip = trim( $ip );

							if ( false !== filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) {
								$forwarded = $ip;
								break 2;
							}
						}
					}
				}

				// If we found a different IP address than REMOTE_ADDR then it's a proxy!
				if ( ! empty( $forwarded ) && $forwarded !== $result->ip ) {
					$result->proxy = true;
					$result->proxy_ip = $result->ip;
					$result->ip = $forwarded;
				}

				if ( $result->ip ) {
					$user_ip = $result->ip;
				} else {
					$user_ip = '';
				}

				/**
				 * Filter Current user IP
				 *
				 * @param string $user_ip Current user IP
				 */
				self::$current_user_ip = apply_filters( 'check_conflicts_user_ip', $user_ip );
			}

			return self::$current_user_ip;
		}

		private static function get_settings() {
			if ( is_null( self::$settings ) ) {

				$saved = self::get_saved_options();
				if ( !is_array( $saved ) ) {
					$saved = [];
				}

				if ( empty( $saved ) ) {
					$default = self::get_default_settings();
				} else {
					$default = self::get_default_settings( true );
				}
				$settings = array_merge( $default, $saved );

				/**
				 * Filter settings
				 *
				 * @param array $settings Settings
				 */
				self::$settings = apply_filters( 'check_conflicts_settings', $settings );
			}

			return self::$settings;
		}

		/**
		 * Render main page for settings
		 */
		public static function render_main_page() {
			if ( ! is_plugin_active( 'check-conflicts/check-conflicts.php' ) ) {
				return;
			}
			self::handle_post();

			$settings = self::get_settings();
			$args = [];
			if ( is_multisite() ) {
				$args['allowed'] = 'true';
				$args['blog_id'] = get_current_blog_id();
			}
			$themes = wp_get_themes( $args );
			$plugins = get_plugins();
			$backup = ! empty( self::get_backup_settings() );
			?>
<div class="wrap">
<h1><?php esc_html_e( 'Settings' ); ?></h1>
<p><?php esc_html_e( 'These settings apply only to selected IP addresses.' ); ?></p>

<form method="post" name="settings">
	<?php wp_nonce_field( 'save-settings' ); ?>

	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><label for="ips"><?php esc_html_e( 'Apply to IPs' ); ?> </label></th>
			<td>
				<input name="ips" id="ips" type="text" value="<?php echo esc_attr( $settings['ips'] ); ?>" />
				<p class="description">
					<?php esc_html_e( 'By default, it sets your current IP if it\'s possible to detect it, but you can change it or add another IP' ); ?><br>
					<?php printf( esc_html__( '%1$sNote%2$s: you can set several IPs. Use comma (%1$s,%2$s) as a separator between several IPs.' ), '<strong>', '</strong>' ); ?>
				</p>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="theme"><?php esc_html_e( 'Theme' ); ?> </label></th>
			<td>
				<select name="theme" id="theme">
					<?php
						foreach ( $themes as $key => $name ) {
							echo '<option value="' . esc_attr( $key ) . '" ' . selected( $key ===$settings['theme'], true, false  ) . ' >' . esc_html( $name->get( 'Name' ) ) . '</option>';
						}
					?>
				</select>
				<p class="description">
					<?php // esc_html_e( '' ); ?>
				</p>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="plugins"><?php esc_html_e( 'Plugins' ); ?> </label></th>
			<td>
				<select name="plugins[]" id="plugins" multiple="multiple" size="<?php echo count( $plugins ); ?>" style="overflow-y: hidden;">
					<?php
						foreach ( $plugins as $key => $name ) {
							echo '<option value="' . esc_attr( $key ) . '" ' . selected( in_array( $key, $settings['plugins'], true ), true, false  ) . ' >' . esc_html( $name['Name'] ) . '</option>';
						}
					?>
				</select>
				<p class="description">
					<?php esc_html_e( 'Multiple options can be selected at once. Hold down the control (ctrl) button to select/unselect multiple options.' ); ?>
				</p>
			</td>
		</tr>

	</table>

	<p class="submit">
		<?php submit_button( __( 'Save settings' ), 'primary', 'save_settings', false ); ?>&nbsp;&nbsp;
		<?php submit_button( __( 'Cancel' ), '', 'cancel', false ); ?>&nbsp;&nbsp;
		<?php $readonly = self::get_saved_options() ? [] : [ 'disabled' => 'disabled' ]; ?>&nbsp;&nbsp;
		<?php submit_button( __( 'Reset settings' ), '', 'reset_settings', false, $readonly ); ?>&nbsp;&nbsp;
		<?php if ( $backup ) { ?>
			<?php // submit_button( __( 'Restore   backup' ), '', 'restore_backup', false ); ?>&nbsp;&nbsp;
			<?php // submit_button( __( 'Remove Backup' ), '', 'remove_backup', false ); ?>&nbsp;&nbsp;
		<?php } else { ?>
			<?php // submit_button( __( 'Create Backup' ), '', 'create_backup', false ); ?>&nbsp;&nbsp;
		<?php } ?>
		<?php submit_button( __( 'Remove this MU plugin' ), '', 'remove_mu_plugin', false, [ 'style' => 'color: red; border-color: red;' ] ); ?>&nbsp;&nbsp;
	</p>

	<p><?php printf( esc_html__( 'Read more about the general process of %1$sCheck Conflicts%2$s.' ), '<a href="https://wpmudev.com/blog/wordpress-plugin-conflicts-how-to-check-for-them-and-what-to-do/" target="_blank">', '</a>' ); ?></p>

</form></div>
			<?php
		}


	}

	MUCheckConflicts::init();
}
