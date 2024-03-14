<?php
/**
 * WCF7 Notion service class.
 *
 * @package add-on-cf7-for-notion
 */

namespace WPC_WPCF7_NTN;

defined( 'ABSPATH' ) || exit;

use WPC_WPCF7_NTN\Options;
use function WPC_WPCF7_NTN\Helpers\tooltip;


/**
 * WCF7 Notion service class.
 * Manages service registration and Notion api key.
 */
class WPCF7_Notion_Service extends \WPCF7_Service {

	/**
	 * WPCF7_Notion_Service instance
	 *
	 * @var WPCF7_Notion_Service $instance
	 */
	private static $instance;

	/**
	 * Notion Api Key
	 *
	 * @var string|mixed $api_key
	 */
	private $api_key = '';

	/**
	 * Returns WPCF7_Notion_Service instance
	 *
	 * @return WPCF7_Notion_Service
	 */
	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Fill $api_key property with the one saved in the WPCF7 options
	 */
	private function __construct() {
		$option = \WPCF7::get_option( Options\get_option_key( 'notion_service' ) );

		if ( isset( $option['api_key'] ) ) {
			$this->api_key = $option['api_key'];
		}
	}

	/**
	 * Returns service title
	 *
	 * @return mixed
	 */
	public function get_title() {
		return __( 'Notion', 'add-on-cf7-for-notion' );
	}

	/**
	 * Returns true if the api key is defined
	 *
	 * @return bool
	 */
	public function is_active() {
		return (bool) $this->get_api_key();
	}

	/**
	 * Returns the api key
	 *
	 * @return mixed|string
	 */
	public function get_api_key() {
		return $this->api_key;
	}

	/**
	 * Returns the service categories
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( 'collaboration_platform' );
	}

	/**
	 * Returns the service icon
	 *
	 * @return string
	 */
	public function icon() {
		return ''; }

	/**
	 * Returns the link to the Notion website
	 *
	 * @return string|void
	 */
	public function link() {
		echo wp_kses_post(
			wpcf7_link(
				'https://www.notion.so',
				'notion.so'
			)
		);
	}

	/**
	 * Sends a debug information for a remote request to the PHP error log.
	 *
	 * @param string         $url URL to retrieve.
	 * @param array          $request Request arguments.
	 * @param array|WP_Error $response The response or WP_Error on failure.
	 * @return void
	 */
	protected function log( $url, $request, $response ) {
		wpcf7_log_remote_request( $url, $request, $response );
	}

	/**
	 * Returns the integration admin page.
	 *
	 * @param string|array|object $args Args.
	 * @return mixed
	 */
	protected function menu_page_url( $args = '' ) {
		$args = wp_parse_args( $args, array() );

		$url = menu_page_url( 'wpcf7-integration', false );
		$url = add_query_arg( array( 'service' => 'wpc_notion' ), $url );

		if ( ! empty( $args ) ) {
			$url = add_query_arg( $args, $url );
		}

		return $url;
	}

	/**
	 * Saves integration form data.
	 *
	 * @return void
	 */
	protected function save_data() {
		\WPCF7::update_option(
			Options\get_option_key( 'notion_service' ),
			array(
				'api_key' => $this->api_key,
			)
		);
	}

	/**
	 * Reset service data.
	 *
	 * @return void
	 */
	protected function reset_data() {
		$this->api_key = null;
		$this->save_data();
	}

	/**
	 * Integration form handler.
	 *
	 * @param string $action Action.
	 * @return void
	 */
	public function load( $action = '' ) {
		if ( 'setup' === $action && isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) {
			check_admin_referer( 'wpc-wpcf7-notion-setup' );

			if ( ! empty( $_POST['reset'] ) ) {
				$this->reset_data();
				$redirect_to = $this->menu_page_url( 'action=setup' );
			} else {
				$this->api_key = isset( $_POST['api_key'] )
					? trim( sanitize_text_field( wp_unslash( $_POST['api_key'] ) ) )
					: '';

				$notion_api = wpconnect_wpcf7_notion_get_api( $this->api_key );
				$databases  = $notion_api->get_databases();
				$confirmed  = ! is_wp_error( $databases );
				Helpers\process_notion_test_request_response(
					$confirmed ? $databases : null,
					$databases
				);

				if ( true === $confirmed ) {
					$redirect_to = $this->menu_page_url(
						array(
							'message' => 'success',
						)
					);

					$this->save_data();
				} elseif ( false === $confirmed ) {
					$redirect_to = $this->menu_page_url(
						array(
							'action'  => 'setup',
							'message' => 'unauthorized',
						)
					);
				} else {
					$redirect_to = $this->menu_page_url(
						array(
							'action'  => 'setup',
							'message' => 'invalid',
						)
					);
				}
			}

			wp_safe_redirect( $redirect_to );
			exit();
		}
	}

	/**
	 * Show admin notices.
	 *
	 * @param string $message Admin notice message.
	 * @return void
	 */
	public function admin_notice( $message = '' ) {
		if ( 'unauthorized' === $message ) {
			echo sprintf(
				'<div class="notice notice-error"><p><strong>%1$s</strong>: %2$s</p></div>',
				esc_html( __( 'Error', 'add-on-cf7-for-notion' ) ),
				esc_html( __( 'You have not been authenticated. Make sure the provided Internal integration token is correct.', 'add-on-cf7-for-notion' ) )
			);
		}

		if ( 'invalid' === $message ) {
			echo sprintf(
				'<div class="notice notice-error"><p><strong>%1$s</strong>: %2$s</p></div>',
				esc_html( __( 'Error', 'add-on-cf7-for-notion' ) ),
				esc_html( __( 'Invalid key values.', 'add-on-cf7-for-notion' ) )
			);
		}

		if ( 'success' === $message ) {
			echo sprintf(
				'<div class="notice notice-success"><p>%s</p></div>',
				esc_html( __( 'Settings saved.', 'add-on-cf7-for-notion' ) )
			);
		}
	}

	/**
	 * Displays information / service state.
	 *
	 * @param string $action Action.
	 * @return void
	 */
	public function display( $action = '' ) {
		echo '<p>' . sprintf(
			/* translators: %s: html link */
			esc_html( __( 'Store form submissions in Notion database. For details, see %s.', 'add-on-cf7-for-notion' ) ),
			wp_kses_post(
				str_replace(
					'<a',
					'<a target="_blank"',
					wpcf7_link(
						__( 'https://wordpress.org/plugins/add-on-cf7-for-notion/#installation', 'add-on-cf7-for-notion' ),
						__( 'Notion integration', 'add-on-cf7-for-notion' )
					)
				)
			)
		) . '</p>';

		if ( $this->is_active() ) {
			echo sprintf(
				'<p class="dashicons-before dashicons-yes">%s</p>',
				esc_html( __( 'Notion is active on this site.', 'add-on-cf7-for-notion' ) )
			);
			echo sprintf(
				'<p><b>%1$s</b>%2$s</p>',
				esc_html( __( 'Creating a connection:', 'add-on-cf7-for-notion' ) ),
				esc_html( __( ' Contact Forms ➜ Edit ➜ Notion tab', 'add-on-cf7-for-notion' ) )
			);
		}

		if ( 'setup' === $action ) {
			$this->display_setup();
		} else {
			echo sprintf(
				'<p><a href="%1$s" class="button">%2$s</a></p>',
				esc_url( $this->menu_page_url( 'action=setup' ) ),
				esc_html( __( 'Setup integration', 'add-on-cf7-for-notion' ) )
			);
		}
	}

	/**
	 * Displays service form.
	 *
	 * @return void
	 */
	private function display_setup() {
		$api_key = $this->get_api_key();

		?>
		<form method="post" action="<?php echo esc_url( $this->menu_page_url( 'action=setup' ) ); ?>">
			<?php wp_nonce_field( 'wpc-wpcf7-notion-setup' ); ?>
			<table class="form-table">
				<tbody>
				<tr>
					<th scope="row"><label for="publishable"><?php echo esc_html( __( 'Internal integration token', 'add-on-cf7-for-notion' ) ); ?>
					<?php
						tooltip(
							sprintf(
								/* translators: %1$s: Notion account link */
								esc_html__( 'To get your Internal integration token, follow %1$s.', 'add-on-cf7-for-notion' ),
								sprintf(
									'<a href="https://www.notion.so/help/create-integrations-with-the-notion-api" target="_blank">%1$s</a>',
									esc_html__( 'these explanations.', 'add-on-cf7-for-notion' )
								)
							)
						);
					?>
					</label></th>
					<td>
					<?php
					if ( $this->is_active() ) {
						echo esc_html( wpcf7_mask_password( $api_key, 4, 8 ) );
						echo sprintf(
							'<input type="hidden" value="%s" id="api_key" name="api_key" placeholder="secret_"/>',
							esc_attr( $api_key )
						);
					} else {
						echo sprintf(
							'<input type="text" aria-required="true" value="%s" id="api_key" name="api_key" class="regular-text code" placeholder="secret_" />',
							esc_attr( $api_key )
						);
					}
					?>
						</td>
				</tr>
				</tbody>
			</table>
			<?php
			if ( $this->is_active() ) {
				submit_button(
					_x( 'Remove key', 'API keys', 'add-on-cf7-for-notion' ),
					'small',
					'reset'
				);
			} else {
				submit_button( __( 'Save changes', 'add-on-cf7-for-notion' ) );
			}
			?>
		</form>
		<?php
	}
}
