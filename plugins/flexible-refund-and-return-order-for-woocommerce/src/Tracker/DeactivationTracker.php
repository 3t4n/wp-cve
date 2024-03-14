<?php
/**
 * Handles the modal displayed when the plugin is deactivated.
 */

namespace WPDesk\WPDeskFRFree\Tracker;

use FRFreeVendor\WPDesk\DeactivationModal;
use FRFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FRFreeVendor\WPDesk_Plugin_Info;

/**
 * .
 */
class DeactivationTracker implements Hookable {

	const PLUGIN_SLUG            = 'flexible-refund-and-return-order-for-woocommerce';
	const ACTIVATION_OPTION_NAME = 'plugin_activation_%s';

	/**
	 * @var WPDesk_Plugin_Info
	 */
	private $plugin_info;

	public function __construct( WPDesk_Plugin_Info $plugin_info ) {
		$this->plugin_info = $plugin_info;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hooks() {
		add_action( 'plugins_loaded', [ $this, 'load_deactivation_modal' ] );
	}

	/**
	 * @return void
	 * @internal
	 */
	public function load_deactivation_modal() {
		$docs_url    = get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/docs/elastyczne-zwroty-i-reklamacje-woocommerce/?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=flexible-refund-docs&utm_content=plugin-list' : 'https://wpdesk.net/docs/flexible-refund-and-cancel-order-for-woocommerce/?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=flexible-refund-docs&utm_content=plugin-list';
		$support_url = get_locale() === 'pl_PL' ? 'https://wordpress.org/support/plugin/flexible-refund-and-return-order-for-woocommerce/' : 'https://wordpress.org/support/plugin/flexible-refund-and-return-order-for-woocommerce/';

		new DeactivationModal\Modal(
			self::PLUGIN_SLUG,
			( new DeactivationModal\Model\FormTemplate( $this->plugin_info->get_plugin_name() ) ),
			( new DeactivationModal\Model\FormOptions() )
				->set_option(
					new DeactivationModal\Model\FormOption(
						'plugin_not_working',
						10,
						__( 'The plugin does not work properly', 'flexible-refund-and-return-order-for-woocommerce' ),
						sprintf(
							__( 'Contact us on %1$sthe support forum%2$s or read %3$sthe plugin DOCS%4$s for help.', 'flexible-refund-and-return-order-for-woocommerce' ),
							'<a href="' . esc_url( $support_url ) . '" target="_blank">',
							'</a>',
							'<a href="' . esc_url( $docs_url ) . '" target="_blank">',
							'</a>'
						),
						__( 'Please tell us what was the problem.', 'flexible-refund-and-return-order-for-woocommerce' )
					)
				)
				->set_option(
					new DeactivationModal\Model\FormOption(
						'difficult_to_use',
						20,
						__( 'The plugin is difficult to use', 'flexible-refund-and-return-order-for-woocommerce' ),
						sprintf(
							__( 'Check %1$sthe documentation%2$s or contact us on %3$sthe support forum%4$s for help.', 'flexible-refund-and-return-order-for-woocommerce' ),
							'<a href="' . esc_url( $support_url ) . '" target="_blank">',
							'</a>',
							'<a href="' . esc_url( $docs_url ) . '" target="_blank">',
							'</a>'
						),
						__( 'How can we do it better? Please write what was problematic.', 'flexible-refund-and-return-order-for-woocommerce' )
					)
				)
				->set_option(
					new DeactivationModal\Model\FormOption(
						'not_meet_requirements',
						30,
						__( 'The plugin does not meet all the requirements', 'flexible-refund-and-return-order-for-woocommerce' ),
						null,
						__( 'Please write what function is missing.', 'flexible-refund-and-return-order-for-woocommerce' )
					)
				)
				->set_option(
					new DeactivationModal\Model\FormOption(
						'found_better_plugin',
						40,
						__( 'I found a better plugin', 'flexible-refund-and-return-order-for-woocommerce' ),
						null,
						__( 'Please write what plugin is it and what was the reason for choosing it.', 'flexible-refund-and-return-order-for-woocommerce' )
					)
				)
				->set_option(
					new DeactivationModal\Model\FormOption(
						'no_longer_need',
						50,
						__( 'The plugin is no longer needed', 'flexible-refund-and-return-order-for-woocommerce' ),
						null,
						__( 'What is the reason for that?', 'flexible-refund-and-return-order-for-woocommerce' )
					)
				)
				->set_option(
					new DeactivationModal\Model\FormOption(
						'temporary_deactivation',
						60,
						__( 'It\'s a temporary deactivation (I\'m just debugging an issue)', 'flexible-refund-and-return-order-for-woocommerce' )
					)
				)
				->set_option(
					new DeactivationModal\Model\FormOption(
						'other',
						70,
						__( 'Other', 'flexible-refund-and-return-order-for-woocommerce' ),
						null,
						__( 'Please tell us what made you click this option.', 'flexible-refund-and-return-order-for-woocommerce' )
					)
				),
			( new DeactivationModal\Model\FormValues() )
				->set_value(
					new DeactivationModal\Model\FormValue(
						'is_localhost',
						[ $this, 'is_localhost' ]
					)
				)
				->set_value(
					new DeactivationModal\Model\FormValue(
						'plugin_using_time',
						[ $this, 'get_time_of_plugin_using' ]
					)
				),
			new DeactivationModal\Sender\DataWpdeskSender(
				$this->plugin_info->get_plugin_file_name(),
				$this->plugin_info->get_plugin_name()
			)
		);
	}

	/**
	 * @internal
	 */
	public function is_localhost(): bool {
		return ( in_array( $_SERVER['REMOTE_ADDR'] ?? '', [ '127.0.0.1', '::1' ] ) );
	}

	/**
	 * @return string|null
	 * @internal
	 */
	public function get_time_of_plugin_using() {
		$option_activation = sprintf( self::ACTIVATION_OPTION_NAME, $this->plugin_info->get_plugin_file_name() );
		$activation_date   = get_option( $option_activation, null );
		if ( $activation_date === null ) {
			return null;
		}


		$current_date = current_time( 'mysql' );

		return ( strtotime( $current_date ) - strtotime( $activation_date ) );
	}

}
