<?php

namespace LIBRARY;

class PluginInstaller {
	
	private $plugins;

	public function init() {
		$this->set_plugins();

		add_action( 'library/plugin_intaller_before_plugin_activation', array( $this, 'before_plugin_activation' ) );

		add_action( 'wp_ajax_library_install_plugin', array( $this, 'install_plugin_callback' ) );
	}

	public function before_plugin_activation( $slug ) {
		if ( $slug === 'wpforms-lite' ) {
			update_option( 'wpforms_activation_redirect', true );
		}

		if ( $slug === 'all-in-one-seo-pack' ) {
			update_option( 'aioseo_activation_redirect', true );
		}

		if ( $slug === 'wp-mail-smtp' ) {
			update_option( 'wp_mail_smtp_activation_prevent_redirect', true );
		}
	}

	public function set_plugins() {
		$all_plugins = Helpers::apply_filters( 'library/register_plugins', array() );

		$this->plugins = $this->filter_plugins( $all_plugins );
	}

	public function get_theme_plugins() {
		$theme_plugins = Helpers::apply_filters( 'library/register_plugins', array() );

		return $this->filter_plugins( $theme_plugins );
	}

	public function install_plugin_callback() {
		check_ajax_referer( 'library-ajax-verification', 'security' );

		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( esc_html__( 'Could not install the plugin. You don\'t have permission to install plugins.', 'borderless' ) );
		}

		$slug = ! empty( $_POST['slug'] ) ? sanitize_key( wp_unslash( $_POST['slug'] ) ) : '';

		if ( empty( $slug ) ) {
			wp_send_json_error( esc_html__( 'Could not install the plugin. Plugin slug is missing.', 'borderless' ) );
		}

		if ( $this->is_plugin_active( $slug ) ) {
			wp_send_json_success( esc_html__( 'Plugin is already installed and activated!', 'borderless' ) );
		}

		if ( $this->is_plugin_installed( $slug ) ) {
			$activated = $this->activate_plugin( $this->get_plugin_basename_from_slug( $slug ), $slug );

			if ( ! is_wp_error( $activated ) ) {
				wp_send_json_success( esc_html__( 'Plugin was already installed! We activated it for you.', 'borderless' ) );
			} else {
				wp_send_json_error( $activated->get_error_message() );
			}
		}

		if ( ! $this->filesystem_permissions_allowed() ) {
			wp_send_json_error( esc_html__( 'Could not install the plugin. Don\'t have file permission.', 'borderless' ) );
		}

		remove_action( 'upgrader_process_complete', [ 'Language_Pack_Upgrader', 'async_upgrade' ], 20 );

		$extra         = array();
		$extra['slug'] = $slug; 
		$source        = $this->get_download_url( $slug );
		$api           = empty( $this->get_plugin_data( $slug )['source'] ) ? $this->get_plugins_api( $slug ) : null;
		$api           = ( false !== $api ) ? $api : null;

		if ( ! empty( $api ) && is_wp_error( $api ) ) {
			wp_send_json_error( $api->get_error_message() );
		}

		if ( ! class_exists( '\Plugin_Upgrader', false ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}

		$skin_args = array(
			'type'   => 'web',
			'plugin' => '',
			'api'    => $api,
			'extra'  => $extra,
		);

		$upgrader = new \Plugin_Upgrader( new PluginInstallerSkin( $skin_args ) );

		$upgrader->install( $source );

		wp_cache_flush();

		if ( $upgrader->plugin_info() ) {
			$activated = $this->activate_plugin( $upgrader->plugin_info(), $slug );

			if ( ! is_wp_error( $activated ) ) {
				wp_send_json_success(
					esc_html__( 'Plugin installed and activated succesfully.', 'borderless' )
				);
			} else {
				wp_send_json_success( $activated->get_error_message() );
			}
		}

		wp_send_json_error( esc_html__( 'Could not install the plugin. WP Plugin installer could not retrieve plugin information.', 'borderless' ) );
	}

	public function install_plugin( $slug ) {
		if ( empty( $slug ) ) {
			return false;
		}

		if ( ! current_user_can( 'install_plugins' ) ) {
			return false;
		}

		if ( $this->is_plugin_active( $slug ) ) {
			return true;
		}

		if ( $this->is_plugin_installed( $slug ) ) {
			$activated = $this->activate_plugin( $this->get_plugin_basename_from_slug( $slug ), $slug );

			return ! is_wp_error( $activated );
		}

		if ( ! $this->filesystem_permissions_allowed() ) {
			return false;
		}

		remove_action( 'upgrader_process_complete', [ 'Language_Pack_Upgrader', 'async_upgrade' ], 20 );

		$extra         = array();
		$extra['slug'] = $slug;
		$source        = $this->get_download_url( $slug );
		$api           = empty( $this->get_plugin_data( $slug )['source'] ) ? $this->get_plugins_api( $slug ) : null;
		$api           = ( false !== $api ) ? $api : null;

		if ( ! empty( $api ) && is_wp_error( $api ) ) {
			return false;
		}

		if ( ! class_exists( '\Plugin_Upgrader', false ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}

		$skin_args = array(
			'type'   => 'web',
			'plugin' => '',
			'api'    => $api,
			'extra'  => $extra,
		);

		$upgrader = new \Plugin_Upgrader( new PluginInstallerSkinSilent( $skin_args ) );

		$upgrader->install( $source );

		wp_cache_flush();

		if ( $upgrader->plugin_info() ) {
			$activated = $this->activate_plugin( $upgrader->plugin_info(), $slug );

			if ( ! is_wp_error( $activated ) ) {
				return true;
			}
		}

		return false;
	}

	private function activate_plugin( $plugin_filename, $slug ) {
		Helpers::do_action( 'library/plugin_intaller_before_plugin_activation', $slug );

		$activated = activate_plugin( $plugin_filename );

		return $activated;
	}

	private function filesystem_permissions_allowed() {
		$library  = BorderlessLibraryImporter::get_instance();
		$url   = esc_url_raw( $library->get_plugin_settings_url() );
		$creds = request_filesystem_credentials( $url, '', false, false, null );

		if ( false === $creds || ! WP_Filesystem( $creds ) ) {
			return false;
		}

		return true;
	}

	public function get_plugin_data( $slug ) {
		$data = [];

		foreach ( $this->plugins as $plugin ) {
			if ( $plugin['slug'] === $slug ) {
				$data = $plugin;
				break;
			}
		}

		return $data;
	}

	public function get_download_url( $slug ) {
		$plugin_data = $this->get_plugin_data( $slug );

		if ( ! empty( $plugin_data['source'] ) ) {
			return $plugin_data['source'];
		}

		return $this->get_wp_repo_download_url( $slug );
	}

	protected function get_wp_repo_download_url( $slug ) {
		$source = '';
		$api    = $this->get_plugins_api( $slug );

		if ( false !== $api && isset( $api->download_link ) ) {
			$source = $api->download_link;
		}

		return $source;
	}

	protected function get_plugins_api( $slug ) {
		static $api = array(); // Cache received responses.

		if ( ! isset( $api[ $slug ] ) ) {
			if ( ! function_exists( 'plugins_api' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			}

			$api[ $slug ] = plugins_api( 'plugin_information', array( 'slug' => $slug, 'fields' => array( 'sections' => false ) ) );
		}

		return $api[ $slug ];
	}

	public function get_plugins( $plugin_folder = '' ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return get_plugins( $plugin_folder );
	}

	protected function get_plugin_basename_from_slug( $slug ) {
		$keys = array_keys( $this->get_plugins() );

		foreach ( $keys as $key ) {
			if ( preg_match( '/^' . $slug . '\//', $key ) ) {
				return $key;
			}
		}

		return false;
	}

	public function is_plugin_installed( $slug ) {
		return ( ! empty( $this->get_plugin_basename_from_slug( $slug ) ) );
	}

	public function is_plugin_active( $slug ) {
		$plugin_path = $this->get_plugin_basename_from_slug( $slug );

		if ( empty( $plugin_path ) ) {
			return false;
		}

		return is_plugin_active( $plugin_path );
	}

	public function get_missing_plugins() {
		$missing = [];

		foreach ( $this->plugins as $plugin_data ) {
			if ( ! $this->is_plugin_active( $plugin_data['slug'] ) ) {
				$missing[] = $plugin_data;
			}
		}

		return $missing;
	}

	private function filter_plugins( $plugins ) {
		return array_filter(
			$plugins,
			function ( $plugin ) {
				if ( empty( $plugin['slug'] ) || empty( $plugin['name'] ) ) {
					return false;
				}

				return true;
			}
		);
	}
}
