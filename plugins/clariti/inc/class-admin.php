<?php
/**
 * Manages Clariti's admin integration.
 *
 * @package Clariti
 */

namespace Clariti;

/**
 * Manages Clariti's admin integration.
 */
class Admin {

	/**
	 * Option used to store the api key.
	 *
	 * @var string
	 */
	const API_KEY_OPTION = 'clariti_api_key';

	/**
	 * Option used to store the api host.
	 *
	 * @var string
	 */
	const API_HOST_OPTION = 'clariti_api_host';

	/**
	 * Option used to store the api secret.
	 *
	 * @var string
	 */
	const API_SECRET_OPTION = 'clariti_api_secret';

	/**
	 * Option to trigger changes on update
	 */
	const PLUGIN_VERSION_OPTION = 'clariti_plugin_version';

	/**
	 * Capability required to change settings.
	 *
	 * @var string
	 */
	const CAPABILITY = 'manage_options';

	/**
	 * Slug for the settings page.
	 *
	 * @var string
	 */
	const PAGE_SLUG = 'clariti';

	/**
	 * Group used for authentication settings.
	 *
	 * @var string
	 */
	const SETTINGS_GROUP = 'clariti-settings-group';

	/**
	 * Section used for authentication settings.
	 *
	 * @var string
	 */
	const SETTINGS_SECTION = 'clariti-settings-section';

	/**
	 * Register admin menu UX.
	 */
	public static function action_admin_menu() {
		register_setting(
			self::SETTINGS_GROUP,
			self::API_KEY_OPTION,
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		register_setting(
			self::SETTINGS_GROUP,
			self::API_HOST_OPTION,
			array(
				'sanitize_callback' => array( __CLASS__, 'sanitize_host_field' ),
			)
		);
		$page_title = __( 'Clariti', 'clariti' );
		add_options_page( $page_title, $page_title, self::CAPABILITY, self::PAGE_SLUG, array( __CLASS__, 'handle_settings_page' ) );
	}

	/**
	 * Renders the settings page.
	 */
	public static function handle_settings_page() {
		$key = self::get_api_key();

		if ( ! empty( $_GET['verify'] ) && $key ) {
			Notifier::action_updated_option( self::API_KEY_OPTION, $key, $key );
		}

		add_settings_section(
			self::SETTINGS_SECTION,
			false,
			false,
			self::PAGE_SLUG
		);
		if ( ! empty( $_GET['advanced'] ) || get_option( self::API_HOST_OPTION ) ) {
			add_settings_field(
				self::API_HOST_OPTION,
				__( 'API Host', 'clariti' ),
				array( __CLASS__, 'render_input_field' ),
				self::PAGE_SLUG,
				self::SETTINGS_SECTION,
				array(
					'name' => self::API_HOST_OPTION,
				)
			);
		}
		add_settings_field(
			self::API_KEY_OPTION,
			__( 'API Key', 'clariti' ),
			array( __CLASS__, 'render_input_field' ),
			self::PAGE_SLUG,
			self::SETTINGS_SECTION,
			array(
				'name' => self::API_KEY_OPTION,
			)
		);
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Clariti Settings', 'clariti' ); ?></h2>
			<form method="post" action="options.php">
				<?php
				settings_fields( self::SETTINGS_GROUP );
				do_settings_sections( self::PAGE_SLUG );
				?>
				<?php submit_button(); ?>
			</form>
			<?php if ( isset( $_GET['advanced'] ) && $_GET['advanced'] ) : ?>
				<form method="post" action="admin-post.php">
					<table class="form-table" role="presentation">
						<tbody>
						<tr>
							<th scope="row">Secret</th>
							<td>
								<input class="regular-text" type="text" disabled readonly value="<?php echo self::get_secret() ? 'Set' : 'Not set'; ?>" name="secret_is_set" id="secret_is_set">
							</td>
						</tr>
						</tbody>
					</table>
					<input type="hidden" name="action" value="clear_secret">
					<input type="hidden" name="clear-secret" value="1">
					<?php submit_button( 'Clear Secret' ); ?>
				</form>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Adds 'Settings' link to plugin settings.
	 *
	 * @param array $links Existing plugin action links.
	 * @return array
	 */
	public static function filter_plugin_action_links( $links ) {
		$links['clariti_settings'] = '<a href="' . esc_url( menu_page_url( self::PAGE_SLUG, false ) ) . '">' . esc_html__( 'Settings', 'clariti' ) . '</a>';
		return $links;
	}

	/**
	 * Render an input field.
	 *
	 * @param array $args Configuration arguments used by the input field.
	 */
	public static function render_input_field( $args ) {
		$defaults = array(
			'type' => 'text',
			'name' => '',
		);
		$args     = array_merge( $defaults, $args );
		if ( empty( $args['name'] ) ) {
			return;
		}
		$value = get_option( $args['name'] );
		?>
		<input type="<?php echo esc_attr( $args['type'] ); ?>"
			name="<?php echo esc_attr( $args['name'] ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			class="regular-text"
		/>
		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php echo wp_kses_post( $args['description'] ); ?></p>
			<?php
		endif;
	}

	/**
	 * Determine if a given API host is valid.
	 *
	 * @param string $host The API host domain.
	 * @return bool True if the API host domain is valid, false otherwise.
	 */
	public static function is_valid_api_host( $host ) {
		$expected_host_root = '.clariti.com';

		if ( substr( $host, - strlen( $expected_host_root ) ) === $expected_host_root ) {
			return true;
		}

		return false;
	}

	/**
	 * Validate the API host field before storing.
	 *
	 * @param string $value The API host domain.
	 * @return string The sanitized API host domain.
	 */
	public static function sanitize_host_field( $value ) {
		$value = trim( $value );

		if ( '' === $value ) {
			return '';
		}

		if ( self::is_valid_api_host( $value ) ) {
			return $value;
		}

		add_settings_error(
			self::API_HOST_OPTION,
			'invalid_url',
			__( 'The API host must be a *.clariti.com domain.', 'clariti' )
		);

		return get_option( self::API_HOST_OPTION, '' );
	}

	/**
	 * Set a random API secret.
	 *
	 * @return string The new secret.
	 */
	public static function set_secret(): string {
		$value = bin2hex( random_bytes( 32 ) );
		update_option( self::API_SECRET_OPTION, $value );

		return $value;
	}

	/**
	 * Get the current API secret.
	 *
	 * @return string The API secret.
	 */
	public static function get_secret(): string {
		$value = get_option( self::API_SECRET_OPTION, '' );

		return (string) $value;
	}

	/**
	 * Clear the API secret.
	 *
	 * @return void
	 */
	public static function clear_secret(): void {
		delete_option( Admin::API_SECRET_OPTION );
	}

	/**
	 * Get the current API key.
	 *
	 * @return string The API key.
	 */
	public static function get_api_key(): string {
		$value = get_option( Admin::API_KEY_OPTION, '' );

		return (string) $value;
	}

	/**
	 * Helper function to send messages to FE and make sure they persist between reloads.
	 *
	 * @param string $setting Slug title.
	 * @param string $code Slug-name identifier.
	 * @param string $message Formatted message.
	 * @param string $type Type, 'error', 'success', 'warning', and 'info'.
	 * @return void
	 */
	public static function send_admin_notification( $setting, $code, $message, $type ) {
		if ( function_exists( 'add_settings_error' ) ) {
			add_settings_error( $setting, $code, $message, $type );
			set_transient( 'settings_errors', get_settings_errors(), 30 ); // 30 seconds.=
		}
	}
}
