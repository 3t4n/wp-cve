<?php

/**
 * Class REVIVESO_Extensions
 *
 * Handles the extensions for the Revive.so plugin.
 */
defined( 'ABSPATH' ) || exit;

class REVIVESO_Extensions {
	/**
	 * Holds the class object.
	 *
	 * @since 1.0.4
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Free extensions.
	 *
	 * @since 1.0.4
	 *
	 * @var array
	 */
	public $free_extensions = array();

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.4
	 */
	public function __construct() {
		// Add admin menu items.
		add_action( 'admin_menu', array( $this, 'menu_items' ), 120 );

		add_action( 'update_option_reviveso_pro_license_key', array( $this, 'delete_extensions_transient' ) );
		// Set the AJAX actions.
		$this->set_ajax_actions();
	}

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @return object The REVIVESO_Extensions object.
	 * @since 1.0.4
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof REVIVESO_Extensions ) ) {
			self::$instance = new REVIVESO_Extensions();
		}

		return self::$instance;
	}

	/**
	 * Set AJAX actions.
	 *
	 * @since 1.0.4
	 */
	private function set_ajax_actions() {
		// AJAX action to reload extensions.
		add_action( 'wp_ajax_reviveso_reload_extensions', array( $this, 'reload_extensions' ) );
	}

	/**
	 * Adds submenu page to admin menu.
	 *
	 * @since 1.0.4
	 */
	public function menu_items() {
		$manage_options_cap = apply_filters( 'reviveso_manage_options_capability', 'manage_options' );
		global $submenu;

		if ( isset( $submenu['reviveso'] ) && in_array( 'reviveso-extensions', wp_list_pluck( $submenu['reviveso'], 2 ) ) ) {
			return;
		}

		add_submenu_page(
			'reviveso',
			__( 'Extensions', 'revive-so' ),
			__( 'Extensions', 'revive-so' ),
			$manage_options_cap,
			'reviveso-extensions',
			array( $this, 'extensions' )
		);
	}

	/**
	 * Extensions page.
	 *
	 * @since 1.0.4
	 */
	public function extensions() {
		$can_install = false;
		if ( current_user_can( 'install_plugins' ) ) {
			$can_install = true;
		}

		?>
		<div class="wrap">
		<div class="reviveso-extensions-header">
			<h3><?php
				echo esc_html__( 'Our extensions set:', 'revive-so' ); ?></h3>
			<div class="reviveso-extensions__action_buttons">
				<a href="#" id="reviveso-reload-extensions" class="button button-secondary" data-nonce="<?php
				echo wp_create_nonce( 'reviveseo_reload_extensions' ) ?>">
					<?php
					echo esc_html__( 'Reload extensions', 'revives-so' ); ?>
				</a>
			</div>
		</div>
		<div class="reviveso-addons-container">
			<?php
			// Get the addons.
			$addons = $this->get_addons();

			// Cycle through the addons and display them.
			if ( ! empty( $addons['all_extensions'] ) ) {
				foreach ( $addons['all_extensions'] as $slug => $addon ) {
					$extension = new REVIVESO_Extension( $slug, $addon );
					?>
					<div class='reviveso-addon'>
						<div class='reviveso-addon-box'>
							<div>
								<img src='<?php
								echo esc_url( $extension->get_image_url() ); ?>'>
							</div>
							<div class='reviveso-addon-content'>
								<h3><?php
									echo esc_html( $extension->get_name() ); ?></h3>
								<span class='reviveso-addon-version'>V <?php
									echo $extension->get_version();
									?>
								</span>
								<div class='reviveso-addon-description'>
									<?php
									echo wp_kses_post( $extension->get_description() ); ?>
								</div>
							</div>
						</div>
						<div class='reviveso-addon-actions'>
							<span class="reviveso-addon-info"></span>
							<?php
							$extension->render_actions( $can_install );
							?>
						</div>
					</div>
					<?php
				}
			}

			// Cycle through the free extensions and display them.
			if ( ! empty( $this->free_extensions ) ) {
				foreach ( $this->free_extensions as $slug => $addon ) {
					$extension = new REVIVESO_Extension( $slug, $addon );
					?>
					<div class='reviveso-addon'>
						<div class='reviveso-addon-box'>
							<div>
								<img src='<?php
								echo esc_url( $extension->get_image_url() ); ?>'>
							</div>
							<div class='reviveso-addon-content'>
								<h3><?php
									echo esc_html( $extension->get_name() ); ?></h3>
								<span class='reviveso-addon-version'>V <?php
									echo $extension->get_version();
									?>
								</span>
								<div class='reviveso-addon-description'>
									<?php
									echo wp_kses_post( $extension->get_description() ); ?>
								</div>
							</div>
						</div>
						<div class='reviveso-addon-actions'>
							<span class='reviveso-addon-info'></span>
							<?php
							$extension->render_actions( $can_install );
							?>
						</div>
					</div>
					<?php
				}
			}
			?>
		</div>
		<?php
	}

	/**
	 * Get the extensions from the server.
	 *
	 * @since 1.0.4
	 */
	public function get_addons() {
		if ( false !== $extensions = get_transient( 'reviveso_extensions' ) ) {
			return $extensions;
		}

		// Do activate request.
		$api_request = wp_remote_get( REVIVE_STORE_URL . 'wp-json/wpchill/v1/get-extensions' );
		// Check request.
		if ( is_wp_error( $api_request ) || wp_remote_retrieve_response_code( $api_request ) != 200 ) {
			return array(
				'failed'  => true,
				'message' => __( 'Could not connect to the license server', 'revive-so' ),
			);
		}
		$response                   = json_decode( wp_remote_retrieve_body( $api_request ), true );
		$response['all_extensions'] = $response;
		set_transient( 'reviveso_extensions', $response, 7 * DAY_IN_SECONDS );

		return $response;
	}

	/**
	 * Reload extensions. This deletes set transients and reloads the extensions.
	 *
	 * @since 1.0.4
	 */
	public function reload_extensions() {
		check_ajax_referer( 'reviveseo_reload_extensions', 'nonce' );
		$this->delete_extensions_transient();
		wp_send_json_success();
	}

	/**
	 * Delete extensions transient.
	 *
	 * @since 1.0.4
	 */
	public function delete_extensions_transient() {
		delete_transient( 'reviveso_extensions' );
		do_action( 'reviveso_clear_extensions_transients' );
	}
}
