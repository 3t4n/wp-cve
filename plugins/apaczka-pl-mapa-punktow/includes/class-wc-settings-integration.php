<?php
/**
 * Apaczka.pl Mapa Punktów
 *
 * @package Apaczka Mapa Punktów
 * @author  InspireLabs
 * @link    https://inspirelabs.pl/
 */

namespace Apaczka_Points_Map;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Integration with WooCommerce settings.
 */
class WC_Settings_Integration extends \WC_Integration {
	/**
	 * Maps_Integration constructor.
	 */
	public function __construct() {
		$this->id                 = 'woocommerce-maps-apaczka';
		$this->method_title       = __( 'Apaczka.pl WooCommerce Points Map', 'apaczka-pl-mapa-punktow' );
		$this->method_description = __( 'WooCommerce integration with Apaczka.pl Points Map.', 'apaczka-pl-mapa-punktow' );

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		add_action( 'woocommerce_update_options_integration_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * Init plugin settings form fields.
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'app_id'     => array(
				'title'       => __( 'App ID', 'apaczka-pl-mapa-punktow' ),
				'type'        => 'text',
				'default'     => '',
				'description' => __( 'Application ID. You can generate it in Apaczka customer panel.', 'apaczka-pl-mapa-punktow' ),
				'desc_tip'    => true,
			),
			'app_secret' => array(
				'title'       => __( 'App Secret', 'apaczka-pl-mapa-punktow' ),
				'type'        => 'password',
				'default'     => '',
				'description' => __( 'Application Secret. You can generate it in Apaczka customer panel.', 'apaczka-pl-mapa-punktow' ),
				'desc_tip'    => true,
			),
		);
	}

	/**
	 * Information in plugin setting about connection status.
	 */
	public function admin_options() {
		?>
		<div class="wrap">
			<div class="inspire-settings">
				<div class="inspire-main-content">
					<?php
					parent::admin_options();

					$status = __( 'Error. Please fill in valid App ID and App Secret.', 'apaczka-pl-mapa-punktow' );
					if ( true === $this->is_api_connected() ) {
						$status = __( 'Connected', 'apaczka-pl-mapa-punktow' );
					}

					?>
					<p><?php esc_html_e( 'Connection status:', 'apaczka-pl-mapa-punktow' ); ?> <?php echo esc_html( $status ); ?></p>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Checked connection with Apaczka API.
	 *
	 * @return bool
	 */
	public function is_api_connected() {
		\Apaczka\Api::$app_id     = $this->settings['app_id'];
		\Apaczka\Api::$app_secret = $this->settings['app_secret'];

		$result   = false;
		$response = json_decode( \Apaczka\Api::service_structure(), true );

		if ( isset( $response['status'] ) && 200 === $response['status'] ) {
			$result = true;
		}

		$this->set_correct_api_connection( $result );

		return $result;
	}

	/**
	 * Sets information about correct connection with Apaczka API in database.
	 *
	 * @param bool $is_connected .
	 */
	private function set_correct_api_connection( $is_connected ) {
		if ( true === $is_connected ) {
			$this->settings['correct_api_connection'] = 'yes';
		} else {
			$this->settings['correct_api_connection'] = 'no';
		}

		update_option( $this->get_option_key(), $this->settings );
	}
}
