<?php
/**
 * Revolut Api Settings
 *
 * Provides configuration for API settings
 *
 * @package WooCommerce
 * @category Payment Gateways
 * @author Revolut
 * @since 2.0
 */

/**
 * WC_Revolut_Settings_API class.
 */
class WC_Revolut_Advanced_Settings extends WC_Revolut_Settings_API {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id        = 'revolut_advanced_settings';
		$this->tab_title = __( 'Advanced Settings', 'revolut-gateway-for-woocommerce' );
		$this->init_form_fields();
		$this->init_settings();
		$this->hooks();
	}

	/**
	 * Add required filters
	 */
	public function hooks() {
		add_action( 'woocommerce_settings_checkout', array( $this, 'admin_options' ) );
		add_filter( 'wc_revolut_settings_nav_tabs', array( $this, 'admin_nav_tab' ), 10 );
		add_action( 'woocommerce_update_options_checkout_' . $this->id, array( $this, 'process_admin_options' ) );
	}

		/**
		 * Displays configuration page with tabs
		 */
	public function admin_options() {
		if ( $this->check_is_get_data_submitted( 'page' ) && $this->check_is_get_data_submitted( 'section' ) ) {
			$is_revolut_api_section = 'wc-settings' === $this->get_request_data( 'page' ) && $this->id === $this->get_request_data( 'section' );

			if ( $is_revolut_api_section ) {
				echo wp_kses_post( '<table class="form-table">' );
				$this->generate_settings_html( $this->get_form_fields(), true );
				echo wp_kses_post( '</table>' );
			}
		}
	}

	/**
	 * Initialize Settings Form Fields
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'title'                              => array(
				'type'  => 'title',
				'title' => __( 'Revolut Gateway - Advanced Settings', 'revolut-gateway-for-woocommerce' ),
			),
			'clear_unused_order_records'         => array(
				'title'       => 'Clear unused orders now',
				'type'        => 'text',
				'description' => '<button class="revolut_clear_unused_order_records" style="min-height: 30px;"><span id="span-for-active-button-sandbox">Clear</span></button><br><br><b>What is this?</b> The plugin creates a Revolut order every time a customer is attempting to pay. If you have fast checkout options active, this could mean that an order is created for every site visitor. If you have limited space on your database or you have a lot of site visitors, you might end up with a lot of unused orders. You can use this button to delete unused orders. WARNING: This could also delete orders that customers have not yet paid but have the intention to, so make sure that this is only used when there are no visitors on your website',
			),
			'consent_clear_unused_order_records' => array(
				'title'   => '',
				'label'   => __( 'By ticking this box I understand that unused order IDs stored in my website’s database will be deleted. I understand as well that this is run at my own risk and could cause temporary issues with payments being failed.', 'revolut-gateway-for-woocommerce' ),
				'type'    => 'checkbox',
				'default' => 'no',
				'class'   => 'info_clear_unused_order_records',
			),

		);
	}
}
