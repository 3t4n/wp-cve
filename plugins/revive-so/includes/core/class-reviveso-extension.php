<?php

/**
 * Class REVIVESO_Extension
 *
 * Create a new extension object.
 */
defined( 'ABSPATH' ) || exit;

class REVIVESO_Extension {

	private $name;
	private $slug;
	private $version;
	private $file;
	private $path;
	private $url;
	private $description;
	private $image_url;

	/**
	 * REVIVESO_Extension constructor.
	 *
	 * @param  string  $slug  The extension slug.
	 * @param  array   $args  The extension arguments.
	 *
	 * @since 1.0.4
	 */
	public function __construct( $slug, $args ) {
		// Create the extension object.
		$this->set_data( $slug, $args );
	}

	/**
	 * Set the extension data.
	 *
	 * @param  string  $slug  The extension slug.
	 * @param  array   $args  The extension arguments.
	 *
	 * @since 1.0.4
	 */
	private function set_data( $slug, $args ) {
		// Set the extension data.
		$this->set_name( $args['name'] );
		$this->set_slug( $slug );
		$this->set_version( $args['version'] );
		$this->set_file( basename( $args['path'] ) );
		$this->set_path( $args['path'] );
		$this->set_description( $args['description'] );
		$this->set_image_url( $args['image'] );
	}

	/**
	 * Set the extension name.
	 *
	 * @since 1.0.4
	 */
	public function set_name( $name ) {
		$this->name = $name;
	}

	/**
	 * Set the extension slug.
	 *
	 * @since 1.0.4
	 */
	public function set_slug( $slug ) {
		$this->slug = $slug;
	}

	/**
	 * Set the extension version.
	 *
	 * @since 1.0.4
	 */
	public function set_version( $version ) {
		$this->version = $version;
	}

	/**
	 * Set the extension file.
	 *
	 * @since 1.0.4
	 */
	public function set_file( $file ) {
		$this->file = $file;
	}

	/**
	 * Set the extension path.
	 *
	 * @since 1.0.4
	 */
	public function set_path( $path ) {
		$this->path = $path;
	}

	/**
	 * Set the extension description.
	 *
	 * @since 1.0.4
	 */
	public function set_description( $description ) {
		$this->description = $description;
	}

	/**
	 * Set the extension image url.
	 *
	 * @since 1.0.4
	 */
	public function set_image_url( $url ) {
		$this->image_url = $url;
	}

	/**
	 * Get the extension name.
	 *
	 * @since 1.0.4
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Get the extension slug.
	 *
	 * @since 1.0.4
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Get the extension version.
	 *
	 * @since 1.0.4
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Get the extension file.
	 *
	 * @since 1.0.4
	 */
	public function get_file() {
		return $this->file;
	}

	/**
	 * Get the extension path.
	 *
	 * @since 1.0.4
	 */
	public function get_path() {
		return $this->path;
	}

	/**
	 * Get the extension description.
	 *
	 * @since 1.0.4
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Get the extension image url.
	 *
	 * @since 1.0.4
	 */
	public function get_image_url() {
		return $this->image_url;
	}

	/**
	 * Check if the extension is installed.
	 *
	 * @retun bool
	 *
	 * @since 1.0.4
	 */
	public function is_installed() {
		$installed   = false;
		$plugin_path = WP_PLUGIN_DIR . '/' . $this->get_path();
		if ( file_exists( $plugin_path ) ) {
			$installed = true;
		}

		return $installed;
	}

	/**
	 * Check if the extension is active.
	 *
	 * @retun bool
	 *
	 * @since 1.0.4
	 */
	public function is_active() {
		$active  = false;
		$plugins = get_option( 'active_plugins' );
		foreach ( $plugins as $plugin ) {
			if ( $plugin === $this->get_path() ) {
				$active = true;
				break;
			}
		}

		return $active;
	}

	/**
	 * Check if the extension is free.
	 *
	 * @retun bool
	 *
	 * @since 1.0.4
	 */
	public function is_free() {
		$extensions_handler = REVIVESO_Extensions::get_instance();
		if ( isset( $extensions_handler->free_extensions[$this->get_slug()] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get the installation url.
	 *
	 * @return string
	 *
	 * @since 1.0.4
	 */
	public function get_free_installation_url() {
		return wp_nonce_url( admin_url( 'plugins.php?action=install-plugin&plugin=' . $this->get_path(), 'activate-plugin_' . $this->get_path() ) );
	}

	/**
	 * Get the activation url.
	 *
	 * @return string
	 *
	 * @since 1.0.4
	 */
	public function get_activation_url() {
		return add_query_arg(
			array(
				'action'        => 'activate',
				'plugin'        => rawurlencode( $this->get_path() ),
				'plugin_status' => 'all',
				'paged'         => '1',
				'_wpnonce'      => wp_create_nonce( 'activate-plugin_' . $this->get_path() ),
			),
			admin_url( 'plugins.php' )
		);
	}

	/**
	 * Get the deactivation url.
	 *
	 * @return string
	 *
	 * @since 1.0.4
	 */
	public function get_deactivation_url() {
		return add_query_arg(
			array(
				'action'        => 'deactivate',
				'plugin'        => rawurlencode( $this->get_path() ),
				'plugin_status' => 'all',
				'paged'         => '1',
				'_wpnonce'      => wp_create_nonce( 'deactivate-plugin_' . $this->get_path() ),
			),
			admin_url( 'plugins.php' )
		);
	}

	/**
	 * Render the extension actions.
	 *
	 * @param  bool  $can_install  Whether the user can install plugins.
	 *
	 * @since 1.0.4
	 */
	public function render_actions( $can_install ) {
		if ( ! $can_install ) {
			return;
		}

		if ( ! $this->is_free() ) {
			$html = '<a href="https://revive.so/pricing" class="button button-secondary no-js" target="_blank">' . esc_html__( 'Upgrade', 'revive-so' ) . '</a>';
		} else {
			if ( $this->is_active() ) {
				$html = '<a href="#" class=" button button-secondary" data-slug="' . esc_attr( $this->get_slug() ) . '" data-action="deactivate" data-url="' . esc_url( $this->get_deactivation_url() ) . '">' . esc_html__( 'Deactivate', 'revive-so' ) . '</a>';
			} else {
				if ( $this->is_installed() ) {
					$html = '<a href="#" class=" button button-primary" data-slug="' . esc_attr( $this->get_slug() ) . '" data-action="activate" data-url="' . esc_url( $this->get_activation_url() ) . '">' . esc_html__( 'Activate', 'revive-so' ) . '</a>';
				} else {
					$html = '<a href="' . esc_url( $this->get_free_installation_url() ) . '" data-slug="' . esc_attr( $this->get_slug() ) . '" data-action="install" class="button button-primary" data-url="' . $this->get_free_installation_url() . '">' . esc_html__( 'Install', 'revive-so' ) . '</a>';
				}
			}
		}

		echo wp_kses_post( $html );
	}
}
