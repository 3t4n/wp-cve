<?php

namespace Ilabs\BM_Woocommerce\Gateway;

use Exception;
use Ilabs\BM_Woocommerce\Data\Remote\Blue_Media\Client;
use Ilabs\BM_Woocommerce\Domain\Model\White_Label\Expandable_Group;
use Ilabs\BM_Woocommerce\Domain\Model\White_Label\Expandable_Group_Interface;
use Ilabs\BM_Woocommerce\Domain\Model\White_Label\Group;
use Ilabs\BM_Woocommerce\Domain\Service\Legacy\Importer;
use Ilabs\BM_Woocommerce\Domain\Service\White_Label\Group_Mapper;
use Ilabs\BM_Woocommerce\Domain\Woocommerce\Autopay_Order_Factory;
use Ilabs\BM_Woocommerce\Plugin;
use SimpleXMLElement;
use WC_Order;
use WC_Payment_Gateway;
use Ilabs\BM_Woocommerce\Gateway\Hooks\Payment_On_Account_Page;

class Blue_Media_Gateway extends WC_Payment_Gateway {

	const GATEWAY_PRODUCTION = 'https://pay.autopay.eu/';

	const GATEWAY_SANDBOX = 'https://testpay.autopay.eu/';

	const BLIK_0_CHANNEL = 509;

	const CARD_CHANNEL = 1500;

	const ITN_SUCCESS_STATUS_ID = 'SUCCESS';

	const ITN_PENDING_STATUS_ID = 'PENDING';

	const ITN_FAILURE_STATUS_ID = 'FAILURE';

	/**
	 * @var string
	 */
	private $gateway_url;

	/**
	 * @var string
	 */
	private $service_id;

	/**
	 * @var string
	 */
	private $private_key;

	/**
	 * @var bool
	 */
	private $testmode;

	private $payment_on_account_page = false;


	/**
	 *
	 * @throws Exception
	 */
	public function __construct() {
		( new Hooks() )->init();


		blue_media()->set_bluemedia_gateway( $this );

		( new Importer() )->handle_import();

		$this->id           = 'bluemedia';
		$this->icon
		                    = blue_media()->get_plugin_images_url()
		                      . '/logo-autopay.svg';
		$this->has_fields
		                    = true;
		$this->method_title = __( 'Autopay Instant payment',
			'bm-woocommerce' );
		$this->method_description
		                    = __( 'Instant payment, BLIK, credit card, Google Pay, Apple Pay',
			'bm-woocommerce' );

		$this->supports = [
			'products',
		];
		$this->init_form_fields();
		$this->init_settings();

		$this->title = __( 'Autopay gateway',
			'bm-woocommerce' );

		$this->description = '';
		$this->enabled     = $this->get_option( 'enabled' );
		$this->testmode    = $this->resolve_is_test_mode();


		$this->payment_on_account_page = apply_filters( 'autopay_payment_on_account_page',
			$this->payment_on_account_page );

		if ( $this->testmode && ! defined( 'BLUE_MEDIA_DISABLE_CACHE' ) ) {
			define( 'BLUE_MEDIA_DISABLE_CACHE', 1 );
		}

		$this->setup_mode();

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id,
			[ $this, 'process_admin_options' ] );

		if ( is_object( WC()->session ) && ! wp_doing_ajax() ) {
			if ( ! empty( WC()->session->get( 'bm_order_payment_params' ) ) ) {
				$params
					= WC()->session->get( 'bm_order_payment_params' )['params'];

				if ( $this->can_redirect_to_payment_gateway( (int) $params['OrderID'] ) ) {
					WC()->session->set( 'bm_order_payment_params', null );
					WC()->session->save_data();

					if ( 'yes' === $this->get_option( 'countdown_before_redirection' ) ) {
						ob_start();
						wp_head();
						$wp_head_html = ob_get_contents();
						ob_end_clean();
						echo preg_replace( '/<body[^>]*>.*<\/body>/isU', '',
							$wp_head_html );
						echo '<body>';
						blue_media()->locate_template( 'redirect_to_payment_overlay.php' );
					}

					if ( is_array( $params ) ) {
						printf( "<form method='post' id='paymentForm' action='%s'>
			 <input type='hidden' name='ServiceID'  value='%s' />
			 <input type='hidden' name='OrderID'  value='%s' />
			 <input type='hidden' name='Amount'  value='%s' />
			 <input type='hidden' name='GatewayID'  value='%s' />
			 <input type='hidden' name='Currency'  value='%s' />
			 <input type='hidden' name='CustomerEmail'  value='%s' />
			 <input type='hidden' name='PlatformName'  value='%s' />
			 <input type='hidden' name='PlatformVersion'  value='%s' />
			 <input type='hidden' name='PlatformPluginVersion'  value='%s' />
			 <input type='hidden' name='Hash'  value='%s' /></form>",
							$this->gateway_url . 'payment',
							$params['ServiceID'],
							$params['OrderID'],
							$params['Amount'],
							! empty( $params['GatewayID'] ) ? $params['GatewayID'] : '0',
							blue_media()->resolve_blue_media_currency_symbol(),
							$params['CustomerEmail'],
							$params['PlatformName'],
							$params['PlatformVersion'],
							$params['PlatformPluginVersion'],
							$params['Hash'] );
					}

					if ( 'yes' === $this->get_option( 'countdown_before_redirection' ) ) {
						_wp_footer_scripts();
						echo '</body>';
					} else {
						echo "<script type='text/javascript'>document.getElementById('paymentForm').submit();</script>";
					}

					blue_media()->get_woocommerce_logger()->log_debug(
						sprintf( '[Print payment form and submit by JS] [Params: %s]',
							serialize( $params )
						) );

					update_post_meta( $params['OrderID'],
						'bm_transaction_init_params', $params );
					blue_media()->update_payment_cache( 'bm_payment_start',
						'1' );

					exit;
				} else {
					WC()->session->set( 'bm_order_payment_params', null );
					WC()->session->save_data();

					blue_media()->get_woocommerce_logger()->log_debug(
						sprintf( '[Print payment form canceled because bm_transaction_init_params exists in Order.] [Params: %s]',
							serialize( $params )
						) );
				}
			}
		} else {
			blue_media()->get_woocommerce_logger()->log_debug(
				sprintf( '[is_object( WC()->session ) && ! wp_doing_ajax() returns false]  [is_object( WC()->session ) returns %s] [wp_doing_ajax() returns %s]',
					is_object( WC()->session ) ? 'true' : 'false',
					wp_doing_ajax() ? 'true' : 'false'
				) );


		}
		$this->webhook();
	}

	/**
	 * @param int $order_id
	 *
	 * @return bool
	 * @throws Exception
	 *
	 * @desc payment redirect loop protection
	 */
	private function can_redirect_to_payment_gateway( int $order_id ): bool {

		$status = get_post_meta( $order_id,
			'bm_transaction_init_params',
			true );

		$return          = empty( $status );
		$return_filtered = apply_filters( 'autopay_filter_can_redirect_to_payment_gateway',
			$return );

		blue_media()->get_woocommerce_logger()->log_debug(
			sprintf( '[can_redirect_to_payment_gateway] $return_filtered = %s',
				$return_filtered ? 'true' : 'false' )
		);

		return $return_filtered;
	}


	private function setup_mode() {
		$this->gateway_url = $this->testmode
			? self::GATEWAY_SANDBOX
			: self::GATEWAY_PRODUCTION;
		$this->private_key = $this->testmode
			? $this->get_option( 'test_private_key' )
			: $this->get_option( 'private_key' );
		$this->service_id  = $this->testmode
			? $this->get_option( 'test_service_id' )
			: $this->get_option( 'service_id' );
	}

	private function resolve_is_test_mode(): bool {
		if ( 'yes' === $this->get_option( 'testmode', 'no' ) ) {
			return true;
		} else {
			if ( 'yes' === $this->get_option( 'sandbox_for_admins', 'no' ) ) {
				$current_user = wp_get_current_user();
				if ( user_can( $current_user, 'administrator' ) ) {
					blue_media()->get_woocommerce_logger()->log_debug(
						'[resolve_is_test_mode] Test mode forced by sandbox_for_admins option' );

					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @return void
	 * @throws Exception
	 */
	public function init_form_fields() {
		add_filter( 'woocommerce_generate_import_from_bm_legacy_html',
			[ $this, 'generate_import_field' ] );

		if ( blue_media()
			     ->get_request()
			     ->get_by_key( 'bmtab' ) === 'channels' ) {
			return;
		}

		$this->form_fields = [
			'whitelabel'      => [
				'title'       => __( 'Gateway mode',
					'bm-woocommerce' ),
				'label'       => __( 'Enable whitelabel mode',
					'bm-woocommerce' ),
				'type'        => 'checkbox',
				'description' => __( 'White label is not available when block editor for Checkout page is active',
					'bm-woocommerce' ),
				'default'     => 'no',
				'desc_tip'    => false,
			],
			'blik_type'       => [
				'title'       => __( 'BLIK payment type',
					'bm-woocommerce' ),
				'type'        => 'select',
				'description' => __( 'BLIK-0 is not available when block editor for Checkout page is active',
					'bm-woocommerce' ),
				'options'     => [
					'with_redirect'           => __( 'Blik with redirect',
						'bm-woocommerce' ),
					'blik_0_without_redirect' => __( 'Blik-0 without redirect',
						'bm-woocommerce' ),
				],
				'default'     => 'with_redirect',
			],
			'testmode_header' => [
				'title' => __( 'Test environment',
					'bm-woocommerce' ),
				'type'  => 'title',
			],
			'testmode'        => [
				'title'       => __( 'Sandbox mode',
					'bm-woocommerce' ),
				'label'       => __( 'Enable Sandbox mode',
					'bm-woocommerce' ),
				'type'        => 'radio',
				'default'     => 'no',
				'options'     => [
					'yes' => __( 'Yes', 'bm-woocommerce' ),
					'no'  => __( 'No', 'bm-woocommerce' ),
				],
				'description' => __( 'It allows you to check the module\'s operation without having to pay for the order (no order fees are charged in the test mode).',
					'bm-woocommerce' ),
				'desc_tip'    => true,
			],
			'testmode_info'   => [
				'title'       => __( '',
					'bm-woocommerce' ),
				'description' => "<span class='p-info'>" . __( 'The service ID and shared key for the test environment are different from production data.',
						'bm-woocommerce' )
				                 . '<br>' . __( 'To get the data for the test environment,',
						'bm-woocommerce' ) . '<a href="https://developers.autopay.pl/kontakt?mtm_campaign=woocommerce_developers_formularz&mtm_source=woocommerce_backoffice&mtm_medium=hiperlink">'
				                 . ' ' . __( 'please contact us.',
						'bm-woocommerce' ) . '</a></span>',
				'type'        => 'title',
			],

			'service_id'  => [
				'title'       => __( 'Service ID',
					'bm-woocommerce' ),
				'description' => __( 'It contains only numbers. It is different for each shop',
					'bm-woocommerce' ),
				'type'        => 'text',
			],
			'private_key' => [
				'title'       => __( 'Production private Key',
					'bm-woocommerce' ),
				'description' => __( 'Contains numbers and lowercase letters. It is used to verify communication with the payment gateway. It should not be made available to the public',
					'bm-woocommerce' ),
				'type'        => 'password',
			],


			'ga4_tracking_id'                         => [
				'title'       => __( 'Google Analytics Tracking ID',
					'bm-woocommerce' ),
				'description' => ( function () {
					$desc           = __( 'The identifier is in the format G-XXXXXXX.',
						'bm-woocommerce' );
					$desc_link_text = __( 'Where can I find the Measurement ID?',
						'bm-woocommerce' );

					return "$desc <a class='bm_ga_help_modal' href='#'>$desc_link_text</a>";
				} )(),
				'type'        => 'text',
			],
			'ga4_api_secret'                          => [
				'title'       => __( 'Google Analytics Api secret',
					'bm-woocommerce' ),
				'description' => ( function () {
					$desc           = __( 'The identifier is in the format G-XXXXXXX.',
						'bm-woocommerce' );
					$desc_link_text = __( 'Where can I find the Measurement ID?',
						'bm-woocommerce' );

					return "$desc <a class='bm_ga_help_modal' href='#'>$desc_link_text</a>";
				} )(),
				'type'        => 'password',
			],
			'ga4_client_id'                           => [
				'title'       => __( 'Google Analytics Client ID',
					'bm-woocommerce' ),
				'description' => ( function () {
					$desc           = __( 'The identifier is in the format G-XXXXXXX.',
						'bm-woocommerce' );
					$desc_link_text = __( 'Where can I find the Measurement ID?',
						'bm-woocommerce' );

					return "$desc <a class='bm_ga_help_modal' href='#'>$desc_link_text</a>";
				} )(),
				'type'        => 'text',
			],
			'wc_payment_statuses'                     => [
				'title'       => __( 'Payment statuses',
					'bm-woocommerce' ),
				'description' => __( '',
					'bm-woocommerce' ),
				'type'        => 'title',
			],
			'wc_payment_status_on_bm_pending'         => [
				'title'       => __( 'Payment pending',
					'bm-woocommerce' ),
				'description' => __( '',
					'bm-woocommerce' ),
				'type'        => 'select',
				'options'     => wc_get_order_statuses(),
				'default'     => 'wc-pending',
			],
			'wc_payment_status_on_bm_success'         => [
				'title'       => __( 'Payment success',
					'bm-woocommerce' ),
				'description' => __( '',
					'bm-woocommerce' ),
				'type'        => 'select',
				'options'     => wc_get_order_statuses(),
				'default'     => 'wc-completed',
			],
			'wc_payment_status_on_bm_success_virtual' => [
				'title'       => __( 'Payment success for a cart containing only virtual products',
					'bm-woocommerce' ),
				'description' => __( '',
					'bm-woocommerce' ),
				'type'        => 'select',
				'options'     => wc_get_order_statuses(),
				'default'     => 'wc-completed',
			],
			'wc_payment_status_on_bm_failure'         => [
				'title'       => __( 'Payment failure',
					'bm-woocommerce' ),
				'description' => __( '',
					'bm-woocommerce' ),
				'type'        => 'select',
				'options'     => wc_get_order_statuses(),
				'default'     => 'wc-failed',
			],
			'advanced_opts_title'                     => [
				'title'       => __( 'Advanced settings',
					'bm-woocommerce' ),
				'description' => __( '',
					'bm-woocommerce' ),
				'type'        => 'title',
			],
			'test_service_id'                         => [
				'title'       => __( 'Test Service ID',
					'bm-woocommerce' ),
				'description' => __( 'It contains only numbers. It is different for each shop',
					'bm-woocommerce' ),
				'type'        => 'text',
			],
			'test_private_key'                        => [
				'title'       => __( 'Test Private Key',
					'bm-woocommerce' ),
				'description' => __( 'Contains numbers and lowercase letters. It is used to verify communication with the payment gateway. It should not be made available to the public',
					'bm-woocommerce' ),
				'type'        => 'password',
			],
			'debug_mode'                              => [
				'title'   => __( 'Debug mode',
					'bm-woocommerce' ),
				'label'   => __( 'Enable debug mode',
					'bm-woocommerce' ),
				'type'    => 'select',
				'default' => 'no',
				'options' => [
					'no'  => __( 'No', 'bm-woocommerce' ),
					'yes' => __( 'Yes', 'bm-woocommerce' ),
				],
			],
			'sandbox_for_admins'                      => [
				'title'   => __( 'Sandbox mode for logged-in administrator',
					'bm-woocommerce' ),
				'label'   => __( '',
					'bm-woocommerce' ),
				'type'    => 'select',
				'default' => 'no',
				'options' => [
					'no'  => __( 'No', 'bm-woocommerce' ),
					'yes' => __( 'Yes', 'bm-woocommerce' ),
				],
			],
			'autopay_only_for_admins'                 => [
				'title'   => __( 'Show Autopay payment gateway in Checkout only to logged-in administrators',
					'bm-woocommerce' ),
				'label'   => __( '',
					'bm-woocommerce' ),
				'type'    => 'select',
				'default' => 'no',
				'options' => [
					'no'  => __( 'No', 'bm-woocommerce' ),
					'yes' => __( 'Yes', 'bm-woocommerce' ),
				],
			],
			'countdown_before_redirection'            => [
				'title'   => __( 'Show countdown screen before redirection to increase compatibility',
					'bm-woocommerce' ),
				'label'   => __( '',
					'bm-woocommerce' ),
				'type'    => 'select',
				'default' => 'no',
				'options' => [
					'no'  => __( 'No', 'bm-woocommerce' ),
					'yes' => __( 'Yes', 'bm-woocommerce' ),
				],
			],
			'compatibility_with_live_update_checkout' => [
				'title'   => __( 'Compatibility mode with third-party plugins that reload checkout fragments',
					'bm-woocommerce' ),
				'label'   => __( '',
					'bm-woocommerce' ),
				'type'    => 'select',
				'default' => 'no',
				'options' => [
					'no'  => __( 'No', 'bm-woocommerce' ),
					'yes' => __( 'Yes', 'bm-woocommerce' ),
				],
			],

			'custom_button' => [
				'title'       => __( 'Import settings from legacy plugin',
					'bm-woocommerce' ),
				'type'        => 'import_from_bm_legacy',
				'description' => "",
				'desc_tip'    => false,
				'default'     => __( 'Import now',
					'bm-woocommerce' ),
			],
		];
	}

	public function generate_import_field() {
		ob_start();
		?>
        <tr valign="top">
            <th scope="row"
                class="titledesc"><?php _e( 'Import settings from 2.x/3.x version',
					'bm-woocommerce' ) ?></th>
            <td class="forminp">
                <fieldset>
                    <input type="submit" id="autopay_start_import"
                           class="button-primary"
                           value="<?php _e( 'Start import',
						       'bm-woocommerce' ) ?>">
                    <input type="hidden" name="autopay_import_legacy_settings"
                           id="autopay_import_legacy_settings"
                           value="0">
                    <p class="description"><?php _e( 'Imports Service ID, configuration key and environment setting ( testing / production)',
							'bm-woocommerce' ) ?></p>
                </fieldset>
            </td>
        </tr>

        <script>
            jQuery(document).ready(function () {
                jQuery('#autopay_start_import').click(function (e) {
                    e.preventDefault();
                    var form = jQuery(this).closest('form');
                    form.submit(function () {
                        return false;
                    });
                    jQuery('#autopay_import_legacy_settings').val("1")
                    form.unbind('submit').submit();
                });
            });
        </script>
		<?php
		return ob_get_clean();
	}

	public function render_gateway_channels_test() {
		echo '<h2>test</h2>';
	}


	/**
	 * Generate Select HTML.
	 *
	 * @param string $key Field key.
	 * @param array $data Field data.
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public function generate_radio_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$defaults  = [
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => [],
			'options'           => [],
		];

		$data  = wp_parse_args( $data, $defaults );
		$value = $this->get_option( $key );

		ob_start();
		?>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label
                        for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?><?php echo $this->get_tooltip_html( $data ); // WPCS: XSS ok. ?></label>
            </th>
            <td class="forminp">
                <fieldset>
                    <legend class="screen-reader-text">
                        <span><?php echo wp_kses_post( $data['title'] ); ?></span>
                    </legend>
					<?php foreach ( (array) $data['options'] as $option_key => $option_value ) : ?>
				<?php if ( is_array( $option_value ) ) : ?>

                    <optgroup label="<?php echo esc_attr( $option_key ); ?>">
						<?php foreach ( $option_value as $option_key_inner => $option_value_inner ) : ?>
                            <label
                                    for="<?php echo esc_attr( $option_key ); ?>"><?php echo esc_html( $option_value_inner ); ?></label>
                            <input id="<?php echo esc_attr( $option_key ); ?>"
                                   type="radio"
                                   name="<?php echo esc_attr( $field_key ); ?>"
                                   value="<?php echo esc_attr( $option_key_inner ); ?>" <?php checked( (string) $option_key_inner,
								esc_attr( $value ) ); ?>>
						<?php endforeach; ?>
                    </optgroup>
				<?php else : ?>
                    <label
                            for="<?php echo esc_attr( $option_key ); ?>"><?php echo esc_html( $option_value ); ?></label>
                    <input id="<?php echo esc_attr( $option_key ); ?>"
                           type="radio"
                           name="<?php echo esc_attr( $field_key ); ?>"
                           value="<?php echo esc_attr( $option_key ); ?>" <?php checked( (string) $option_key,
						esc_attr( $value ) ); ?>>
						<?php endif; ?>
						<?php endforeach; ?>
                    </input>
					<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
                </fieldset>
            </td>
        </tr>
		<?php

		return ob_get_clean();
	}


	/**
	 * @return void
	 * @throws Exception
	 */
	public function payment_fields() {
		$whitelabel = apply_filters( 'autopay_filter_option_whitelabel',
			$this->get_option( 'whitelabel', 'no' ) );

		if ( 'yes' === $whitelabel ) {
			$this->render_channels( $this->gateway_list() );
		}

		do_action( 'autopay_after_payment_field' );
	}

	/**
	 * @param $order_id
	 *
	 * @return array
	 * @throws Exception
	 */
	public function process_payment( $order_id ) {
		blue_media()->get_woocommerce_logger()->log_debug(
			sprintf( '[process_payment start] [Order id: %s]',
				$order_id
			) );

		if ( wc_notice_count( 'error' ) > 0 ) {
			blue_media()->get_woocommerce_logger()->log_debug(
				sprintf( '[process_payment wc_notice_count > 0 exiting] [Order id: %s]',
					$order_id
				) );

			return [];
		}

		global $woocommerce;
		$order           = wc_get_order( $order_id );
		$payment_channel = (int) $_POST['bm-payment-channel'] ?? null;

		$blik0_type = $this->get_option( 'blik_type', 'with_redirect' );

		if ( self::BLIK_0_CHANNEL === $payment_channel && 'blik_0_without_redirect' === $blik0_type ) {
			$blik_code = (string) $_POST['bluemedia_blik_code'];

			if ( $this->is_blik_0_code_valid( $blik_code ) ) {
				$this->process_blik_0_payment( $order, $blik_code );
			} else {
				wc_add_notice( __( 'The code you provided is invalid. Code should be 6 digits.',
					'bm-woocommerce' ),
					'error' );

				return [];
			}
		} elseif ( false && self::CARD_CHANNEL === $payment_channel ) {
			$this->process_card_payment( $order );
		} else {
			$params = [
				'params' => $this->prepare_initial_transaction_parameters(
					wc_get_order( $order_id ), $payment_channel
				),
			];
			WC()->session->set( 'bm_order_payment_params', $params );

			update_post_meta( $order_id, 'bm_order_payment_params', $params );

			blue_media()->get_woocommerce_logger()->log_debug(
				sprintf( '[bm_order_payment_params saved to wc_session] [Order id: %s]',
					$order_id
				) );
		}

		$this->schedule_remove_unpaid_orders( $order_id );

		$order->set_status( 'pending' );
		$order->save();

		$redirect_url = $this->get_return_url( $order );

		if ( $this->payment_on_account_page ) {

			if ( ! is_user_logged_in() ) {
				$signature = Payment_On_Account_Page::generate_signature( $order_id );

				$redirect_url = add_query_arg(
					[
						'autopay_payment_on_account_page' => '1',
						'sig'                             => $signature,
						'order_id'                        => $order_id,
					],
					$redirect_url );
			} else {
				$redirect_url = add_query_arg(
					[
						'autopay_payment_on_account_page' => '1',
					],
					$redirect_url );
			}
		}

		$return = [
			'result'   => 'success',
			'redirect' => $redirect_url,
		];

		blue_media()->get_woocommerce_logger()->log_debug(
			sprintf( '[process_payment] [Order id: %s] [return: %s]',
				$order_id,
				print_r( $return, true ),
			) );

		return $return;

	}

	private function is_blik_0_code_valid( string $code ): bool {

		return strlen( $code ) === 6 && ctype_digit( $code );
	}

	private function process_card_payment(
		WC_Order $order
	) {
		add_filter( 'woocommerce_get_checkout_order_received_url',
			function ( $redirect_url, $order ) {
				WC()->session->set( 'bm_original_order_received_url',
					$redirect_url );
				WC()->session->set( 'bm_wc_order_id',
					$order->get_id() );

				return '#';
			}, 10, 2 );


		$client = new Client();
		$params = [
			'ServiceID'     => $this->service_id,
			'OrderID'       => $order->get_id(),
			'Amount'        => $this->get_price_for_api_request( $order ),
			'Description'   => (string) $order->get_id(),
			'GatewayID'     => self::CARD_CHANNEL,
			'Currency'      => 'PLN',
			'CustomerEmail' => $order->get_billing_email(),
			'CustomerIP'    => blue_media()
				->get_core_helpers()
				->get_visitor_ip(),
			'Title'         => (string) $order->get_id(),
			'ScreenType'    => 'IFRAME',
		];

		$params = array_merge( $params, [
			'Hash' => $this->hash_transaction_parameters(
				$params ),
		] );

		try {
			update_post_meta( $order->get_id(),
				'bm_transaction_init_params', $params );

			$result = $this->decode_continue_transaction_response( $client->continue_transaction_request(
				$params,
				$this->gateway_url . 'payment'
			) );

			if ( isset( $result['reason'] ) ) {
				throw new Exception( $result['reason'] );
			}

			if ( empty( $result ) || ! is_array( $result ) || ! isset( $result['redirecturl'] ) ) {
				throw new Exception( sprintf( 'Continue transaction response invalid format (%s)',
					serialize( $result ) ) );
			}

			update_option( 'bm_api_last_error',
				sprintf( '[%s server time] [BlueMedia Card debug] [Response: %s]',
					date( "Y-m-d H:i:s", time() ),
					$result['redirecturl']
				)
			);

			WC()->session->set( 'bm_continue_transaction_start_error', '' );
			WC()->session->set( 'bm_continue_transaction_redirect_url',
				$result['redirecturl'] );

		} catch ( Exception $e ) {
			WC()->session->set( 'bm_continue_transaction_redirect_url', '' );
			WC()->session->set( 'bm_continue_transaction_start_error',
				$e->getMessage() );


			update_option( 'bm_api_last_error',
				sprintf( '[%s server time] [BlueMedia Card debug ERROR] [Response: %s]',
					date( "Y-m-d H:i:s", time() ),
					$e->getMessage()
				)
			);

		}
	}


	private function decode_continue_transaction_response( $response_xml ) {
		$xml = simplexml_load_string( $response_xml );

		if ( ! $xml ) {
			return [];
		}

		if ( isset( $xml->confirmation ) && (string) $xml->confirmation === 'NOTCONFIRMED' ) {
			return [
				'confirmation' => (string) $xml->confirmation,
				'reason'       => (string) $xml->reason,
				'hash'         => (string) $xml->hash,
			];
		} else {
			return [
				'status'      => (string) $xml->status,
				'redirecturl' => (string) $xml->redirecturl,
				'hash'        => (string) $xml->hash,
			];
		}
	}


	private function process_blik_0_payment(
		WC_Order $order,
		string $blik_authorization_code
	) {
		add_filter( 'woocommerce_get_checkout_order_received_url',
			function ( $redirect_url, $order ) {
				WC()->session->set( 'bm_original_order_received_url',
					$redirect_url );
				WC()->session->set( 'bm_wc_order_id',
					$order->get_id() );

				return '#';
			}, 10, 2 );

		$client = new Client();
		$params = [
			'ServiceID'         => $this->service_id,
			'OrderID'           => $order->get_id(),
			'Amount'            => $this->get_price_for_api_request( $order ),
			'Description'       => (string) $order->get_id(),
			'GatewayID'         => self::BLIK_0_CHANNEL,
			'Currency'          => 'PLN',
			'CustomerEmail'     => $order->get_billing_email(),
			'CustomerIP'        => blue_media()
				->get_core_helpers()
				->get_visitor_ip(),
			'Title'             => (string) $order->get_id(),
			'AuthorizationCode' => $blik_authorization_code,
		];

		$params = array_merge( $params, [
			'Hash' => $this->hash_transaction_parameters(
				$params ),
		] );

		try {
			update_post_meta( $order->get_id(),
				'bm_transaction_init_params', $params );

			$result = $this->decode_continue_transaction_response( $client->continue_transaction_request(
				$params,
				$this->gateway_url . 'payment'
			) );

			if ( isset( $result['reason'] ) ) {
				throw new Exception( $result['reason'] );
			}

			if ( empty( $result ) || ! is_array( $result ) ) {
				throw new Exception( sprintf( 'Continue transaction response invalid format (%s)',
					serialize( $result ) ) );
			}

			WC()->session->set( 'bm_continue_transaction_start_error', '' );

		} catch ( Exception $e ) {

			blue_media()->get_woocommerce_logger()->log_error(
				sprintf( '[continue_transaction_request] [Params: %s] [Error message: %s]',
					json_encode( $params ),
					$e->getMessage()
				) );

			WC()->session->set( 'bm_continue_transaction_start_error',
				__( 'Payment failed.',
					'bm-woocommerce' ) );
		}
	}

	private function get_price_for_api_request( WC_Order $order ) {
		$price = str_replace( ',', '.',
			(string) $order->get_total( false ) );
		if ( strpos( $price, '.' ) === false ) {
			$price = $price . '.00';
		}

		return $price;
	}

	private function schedule_remove_unpaid_orders( int $order_id ) {
		$woocommerce_hold_stock_minutes = (int) get_option( 'woocommerce_hold_stock_minutes' );

		if ( $woocommerce_hold_stock_minutes > 0 ) {
			$woocommerce_hold_stock_minutes *= 60;
			if ( ! wp_next_scheduled( 'bm_cancel_failed_pending_order_after_one_hour',
				[ $order_id ] ) ) {
				wp_schedule_single_event( time() + $woocommerce_hold_stock_minutes,
					'bm_cancel_failed_pending_order_after_one_hour',
					[ $order_id ] );
			}
		}
	}

	/**
	 * @return void
	 */
	public function webhook() {
		do_action( 'bm_debugger' );

		add_action( 'woocommerce_api_wc_gateway_bluemedia', function () {
			try {

				/*
				 * b) błędy autoryzacji (confirmation=NOTCONFIRMED oraz reason o jednej z wartości:
				 * ALIAS_DECLINED, ALIAS_NOT_FOUND, WRONG_TICKET, TICKET_EXPIRED, TICKET_USED) –
				 * wyświetlenie pola Kod Blik, w celu pobrania go i podania w parametrze AuthorizationCode kolejnej próby przedtransakcji
				 */
				if ( ! empty( $_POST ) ) {
					$posted                  = wp_unslash( $_POST );
					$posted_xml              = simplexml_load_string( base64_decode( $posted['transactions'] ) );
					$all_fields_itn          = [];
					$all_fields_reponse      = [];
					$order_success_to_update = [];
					$order_failure_to_update = [];
					$order_pending_to_update = [];

					blue_media()->get_woocommerce_logger()->log_debug(
						'Transactions from ITN: ' . print_r( base64_decode( $posted['transactions'] ),
							true )
					);

					$xw = xmlwriter_open_memory();
					xmlwriter_set_indent( $xw, 1 );
					$res = xmlwriter_set_indent_string( $xw, ' ' );

					xmlwriter_start_document( $xw, '1.0', 'UTF-8' );


					xmlwriter_start_element( $xw, 'confirmationList' );
					xmlwriter_start_element( $xw, 'serviceID' );
					xmlwriter_text( $xw, $this->service_id );
					xmlwriter_end_element( $xw ); // serviceID
					xmlwriter_start_element( $xw, 'transactionsConfirmations' );


					foreach (
						$posted_xml->xpath( '/transactionList/transactions/transaction' )
						as $transaction
					) {

						/**
						 * @var SimpleXMLElement $field
						 */
						foreach ( $transaction as $field ) {

							$fieldString = ( (string) $field );
							if ( ! empty( $field )
							) {
								if ( $field->getName() == 'customerData' ) {
									$customer_data_fields = (array) $field;
									foreach ( $customer_data_fields as $value ) {
										$all_fields_itn[] = $value;
									}
								} else {
									$all_fields_itn[] = $fieldString;
								}
							}
						}


						$wc_order_id     = (int) (string) $transaction->orderID;
						$bm_order_status = (string) $transaction->paymentStatus;
						$bm_remote_id    = (string) $transaction->remoteID;


						$init_params = get_post_meta( $wc_order_id,
							'bm_transaction_init_params', true );


						if ( ! is_array( $init_params ) ) {
							/*throw new Exception(
								'Autopay webhook error - transaction (ID: '
								. $wc_order_id . ') does not contain BlueMedia gateway data' );*/

							continue;
						}

						/**
						 * @var $bm_order_status SimpleXMLElement
						 */
						if ( ! self::validate_itn_params( $transaction,
							$init_params ) ) {
							ob_start();
							header( 'HTTP/1.0 401 Unauthorized' );
							echo __( 'validate_itn_params - not valid',
								esc_attr( blue_media()->get_from_config( 'slug' ) ) );

							blue_media()->get_woocommerce_logger()->log_error(
								sprintf( '[Validate_itn_params 401] [Transaction from ITN: %s] [Init params meta: %s]',
									json_encode( $transaction ),
									json_encode( $init_params )
								) );

							exit;
						}

						xmlwriter_start_element( $xw, 'transactionConfirmed' );
						xmlwriter_start_element( $xw, 'orderID' );
						xmlwriter_text( $xw, $wc_order_id );
						$all_fields_reponse[] = $wc_order_id;
						xmlwriter_end_element( $xw ); // orderID
						xmlwriter_start_element( $xw, 'confirmation' );
						xmlwriter_text( $xw, 'CONFIRMED' );
						$all_fields_reponse[] = 'CONFIRMED';
						xmlwriter_end_element( $xw ); // confirmation
						xmlwriter_end_element( $xw ); // transactionConfirmed
						$wc_order = wc_get_order( $wc_order_id );


						if ( self::ITN_SUCCESS_STATUS_ID === $bm_order_status ) {
							$order_success_to_update[ $bm_remote_id ] = $wc_order;
						}

						if ( self::ITN_PENDING_STATUS_ID === $bm_order_status ) {
							$order_pending_to_update[ $bm_remote_id ] = $wc_order;
						}

						if ( self::ITN_FAILURE_STATUS_ID === $bm_order_status ) {
							$order_failure_to_update[ $bm_remote_id ] = $wc_order;
						}

					}

					$hash_from_itn = $posted_xml->xpath( '/transactionList/hash' );
					$hash_from_itn = (string) $hash_from_itn[0];

					$is_hash_valid = $this->validate_itn_hash( $all_fields_itn,
						$hash_from_itn );

					if ( ! $is_hash_valid && ! $this->testmode ) {
						$this->testmode = true;
						$this->setup_mode();
						$is_hash_valid  = $this->validate_itn_hash( $all_fields_itn,
							$hash_from_itn );
						$this->testmode = false;
						$this->setup_mode();
					}

					if ( ! $is_hash_valid ) {

						blue_media()->get_woocommerce_logger()->log_debug(
							sprintf( '[webhook] [validate_itn_hash - not valid] [fields_itn: %s] [Hash: %s]',
								print_r( $all_fields_itn, true ),
								$hash_from_itn
							) );


						ob_start();
						header( 'HTTP/1.0 401 Unauthorized' );
						echo __( 'validate_itn_hash - not valid',
							'bm-woocommerce' );
						exit;
					} else {
						blue_media()
							->get_woocommerce_logger()
							->log_debug( '[webhook] [validate_itn_hash - OK]' );
					}

					foreach ( $order_success_to_update as $k => $wc_order ) {
						$autopay_order = ( new Autopay_Order_Factory() )->create_by_wc_order( $wc_order );
						if ( $autopay_order->is_order_only_virtual() ) {
							blue_media()->get_woocommerce_logger()->log_debug(
								sprintf( '[webhook] [is_order_only_virtual] returns true [Order_Id: %s]',
									$wc_order->get_id()
								) );

							$new_status = $this->get_option( 'wc_payment_status_on_bm_success_virtual',
								'completed' );
						} else {
							$new_status = $this->get_option( 'wc_payment_status_on_bm_success',
								'completed' );
						}

						$wc_order->payment_complete( $k );

						blue_media()->get_woocommerce_logger()->log_debug(
							sprintf( '[webhook] [$wc_order->set_status] [Status from ITN: SUCCESS] [Matched WC status: %s] [Order_Id: %s]',
								$new_status,
								$wc_order->get_id()
							) );

						$wc_order->set_status( $new_status );
						$wc_order->add_order_note( 'PayBM ITN: paymentStatus SUCCESS' );
						update_post_meta( $wc_order->get_id(),
							'bm_order_itn_status',
							self::ITN_SUCCESS_STATUS_ID );
						$wc_order->save();
					}

					foreach ( $order_pending_to_update as $k => $wc_order ) {
						$new_status = $this->get_option( 'wc_payment_status_on_bm_pending',
							'pending' );
						blue_media()->get_woocommerce_logger()->log_debug(
							sprintf( '[webhook] [$wc_order->set_status] [Status from ITN: PENDING] [Matched WC status: %s] [Order_Id: %s]',
								$new_status,
								$wc_order->get_id()
							) );

						$wc_order->set_status( $new_status );
						$wc_order->add_order_note( 'PayBM ITN: paymentStatus PENDING' );
						update_post_meta( $wc_order->get_id(),
							'bm_order_itn_status',
							self::ITN_PENDING_STATUS_ID );
						$wc_order->save();
					}

					foreach ( $order_failure_to_update as $k => $wc_order ) {
						$new_status = $this->get_option( 'wc_payment_status_on_bm_failure',
							'failed' );
						blue_media()->get_woocommerce_logger()->log_debug(
							sprintf( '[webhook] [$wc_order->set_status] [Status from ITN: FAILURE] [Matched WC status: %s] [Order_Id: %s]',
								$new_status,
								$wc_order->get_id()
							) );

						$wc_order->set_status( $new_status );
						$wc_order->add_order_note( 'PayBM ITN: paymentStatus FAILURE' );
						update_post_meta( $wc_order->get_id(),
							'bm_order_itn_status',
							self::ITN_FAILURE_STATUS_ID );
						$wc_order->save();
					}


					xmlwriter_end_element( $xw ); // transactionsConfirmations
					xmlwriter_start_element( $xw, 'hash' );
					xmlwriter_text( $xw,
						$this->generate_response_xml_hash( $all_fields_reponse ) );
					xmlwriter_end_element( $xw ); // hash
					xmlwriter_end_document( $xw );
					echo xmlwriter_output_memory( $xw );

					exit;//exit with 200
				}
			} catch ( Exception $e ) {
				blue_media()->get_woocommerce_logger()->log_error(
					sprintf( '[Webhook exception debug] [message: %s] [Post data: %s]',
						json_encode( $e->getMessage() ),
						json_encode( $_POST )
					) );

				die( 'Message: ' . $e->getMessage() . ' Code: ' . $e->getCode() );
			}
		} );

	}


	/**
	 * @param object $transaction_params_from_itn
	 * @param array $transaction_params_from_shop
	 *
	 * @return bool
	 */
	static private function validate_itn_params(
		object $transaction_params_from_itn,
		array $transaction_params_from_shop
	): bool {
		if ( (string) $transaction_params_from_shop['OrderID'] !== (string) $transaction_params_from_itn->orderID ) {
			return false;
		}
		if ( (string) $transaction_params_from_shop['Amount'] !== (string) $transaction_params_from_itn->amount ) {
			return false;
		}

		if ( ! empty( $params['GatewayID'] ) && (string) $transaction_params_from_itn->gatewayID !== (string) $params['GatewayID'] ) {
			return false;

		}

		return true;

	}

	/**
	 * @param array $all_fields_reponse
	 *
	 * @return string
	 */
	private function generate_response_xml_hash( array $all_fields_reponse
	): string {
		array_unshift( $all_fields_reponse, $this->service_id );

		return $this->hash_transaction_parameters( $all_fields_reponse );
	}

	/**
	 * @param array $transactions_from_itn
	 * @param $hash_from_itn
	 *
	 * @return bool
	 */
	private function validate_itn_hash(
		array $transactions_from_itn,
		$hash_from_itn
	): bool {
		array_unshift( $transactions_from_itn,
			$this->service_id );
		$itn_values_based_hash = $this->hash_transaction_parameters( $transactions_from_itn );

		return $hash_from_itn === $itn_values_based_hash;
	}

	/**
	 * @param WC_Order $wc_order
	 * @param int $payment_channel
	 *
	 * @return array
	 * @throws Exception
	 */
	private
	function prepare_initial_transaction_parameters(
		WC_Order $wc_order,
		int $payment_channel = 0
	): array {
		$price = $this->get_price_for_api_request( $wc_order );

		$params = [
			'ServiceID'             => $this->service_id,
			'OrderID'               => $wc_order->get_id(),
			'Amount'                => $price,
			'GatewayID'             => $payment_channel,
			'Currency'              => blue_media()->resolve_blue_media_currency_symbol(),
			'CustomerEmail'         => $wc_order->get_billing_email(),
			'PlatformName'          => 'Woocommerce',
			'PlatformVersion'       => WC_VERSION,
			'PlatformPluginVersion' => blue_media()->get_plugin_version(),
		];

		$params_hash = $this->hash_transaction_parameters(
			$params
		);

		return array_merge( $params, [ 'Hash' => $params_hash ] );
	}

	/**
	 * @param array $params
	 *
	 * @return string
	 * @throws Exception
	 */
	private
	function hash_transaction_parameters(
		array $params
	): string {

		$private_key_secured     = $this->secure_private_key( $this->get_private_key() );
		$imploded_string_secured = implode( '|',
				$params ) . '|' . $private_key_secured;
		$imploded_string         = implode( '|', $params ) . '|'
		                           . $this->get_private_key();

		blue_media()->get_woocommerce_logger()->log_debug(
			sprintf( '[hash_parameters] %s',
				$imploded_string_secured
			) );

		return hash( 'sha256', $imploded_string );
	}

	private function secure_private_key( string $key ): string {
		$length = strlen( $key );
		if ( $length <= 4 ) {
			return $key;
		}

		$hiddenPart  = substr( $key, 0, $length - 4 );
		$hiddenPart  = str_repeat( '*', strlen( $hiddenPart ) );
		$visiblePart = substr( $key, - 4 );

		return $hiddenPart . $visiblePart;
	}

	/**
	 * @return string
	 */
	private
	function get_private_key() {
		return $this->private_key;
	}

	/**
	 * @throws Exception
	 */
	public
	function gateway_list(
		$force_rebuild_cache = false
	): array {
		if ( defined( 'BLUE_MEDIA_DISABLE_CACHE' ) || $force_rebuild_cache || time()
		                                                                      - (int) get_option( 'bm_gateway_list_cache_time' )
		                                                                      > 600//10 minutes cache
		) {
			$gateway_list_cache = $this->api_get_gateway_list();

			if ( ! $this->resolve_is_test_mode() ) {
				update_option( 'bm_gateway_list_cache', $gateway_list_cache );
				update_option( 'bm_gateway_list_cache_time', time() );
			}

			/*blue_media()->get_woocommerce_logger()->log_debug(
				'Gateway list from API: ' . print_r( $gateway_list_cache, true )
			);*/

		} else {
			$gateway_list_cache = get_option( 'bm_gateway_list_cache' );
			if ( empty( $gateway_list_cache ) ) {
				$gateway_list_cache = $this->api_get_gateway_list();
				update_option( 'bm_gateway_list_cache', $gateway_list_cache );
				update_option( 'bm_gateway_list_cache_time', time() );
			}
		}

		return $gateway_list_cache;
	}

	/**
	 * @throws Exception
	 */
	private
	function api_get_gateway_list(): ?array {
		$service_id = $this->service_id;
		$message_id = substr( bin2hex( random_bytes( 32 ) ), 32 );
		$currencies = blue_media()->resolve_blue_media_currency_symbol();

		$params = [
			'ServiceID'  => $service_id,
			'MessageID'  => $message_id,
			'Currencies' => $currencies,
		];


		$params_hash = $this->hash_transaction_parameters(
			$params
		);

		$params = array_merge( $params, [ 'Hash' => $params_hash ] );

		$url = $this->gateway_url . 'gatewayList/v2';

		$wp_remote_post_args = [
			'headers' => [
				'content-type' => 'application/json',
			],
			'body'    => json_encode( $params ),
		];

		blue_media()->get_woocommerce_logger()->log_debug(
			sprintf( '[api_get_gateway_list request] [url: %s] [args: %s]',
				$url,
				print_r( $wp_remote_post_args, true )
			) );


		$result = wp_remote_post(
			$url,
			$wp_remote_post_args
		);


		if ( is_wp_error( $result ) ) {
			blue_media()->get_woocommerce_logger()->log_error(
				sprintf( '[gatewayList/v2] [error message: %s]',
					$result->get_error_message()
				) );
		}

		$result_decoded = json_decode( wp_remote_retrieve_body( $result ) );


		if ( is_object( $result_decoded )
		     && property_exists( $result_decoded,
				'result' )
		     && $result_decoded->result === 'ERROR' ) {

			blue_media()->get_woocommerce_logger()->log_error(
				sprintf( '[gatewayList/v2] [URL: %s] [Error: %s]',
					$url,
					print_r( $result_decoded, true )
				) );

			return [];
		}

		if ( is_object( $result_decoded ) && property_exists( $result_decoded,
				'gatewayList' ) ) {

			if ( empty( $result_decoded->gatewayList ) ) {
				blue_media()->get_woocommerce_logger()->log_error(
					sprintf( '[gatewayList/v2] [URL: %s] [Empty results: %s]',
						$url,
						serialize( $result_decoded )
					) );

				return [];
			}

			/*blue_media()->get_woocommerce_logger()->log_debug(
				sprintf( '[gatewayList/v2] [URL: %s] [Results: %s]',
					$url,
					serialize( $result_decoded )
				) );*/

			blue_media()->get_woocommerce_logger()->log_debug(
				sprintf( '[api_get_gateway_list] [response code: %s] [gatewayList count %s]',
					print_r( wp_remote_retrieve_response_code( $result ),
						true ),
					is_array( $result_decoded->gatewayList ) ? count( $result_decoded->gatewayList ) : 0
				) );


			return $result_decoded->gatewayList;
		}

		blue_media()->get_woocommerce_logger()->log_error(
			sprintf( '[gatewayList/v2] [URL: %s] [Failed decode results: %s]',
				$url,
				serialize( $result )
			) );

		return [];
	}

	/**
	 * @param array $channels
	 *
	 * @return void
	 * @throws Exception
	 */
	public
	function render_channels(
		array $channels
	) {

		$group_arr = ( new Group_Mapper( $channels ) )->map();

		echo '<div class="payment_box payment_method_bacs">';
		echo '<p>' . __( 'Instant payment, BLIK, credit card, Google Pay, Apple Pay',
				'bm-woocommerce' ) . '</p>';
		echo '<p>' . __( 'Select the payment method you want to use.',
				'bm-woocommerce' ) . '</p>';
		echo '</div>';
		echo '<div class="payment_box payment_method_bacs">';
		echo '<div class="bm-payment-channels-wrapper">';
		printf( '<ul id="shipping_method" class="woocommerce-shipping-methods bm-%s">',
			rand( 0, 1000 ) );

		/**
		 * @var Group[] $group_arr
		 */
		foreach ( $group_arr as $group ) {
			$expandable_Group = $group instanceof Expandable_Group;

			if ( empty( $group->get_items() ) ) {
				continue;
			}

			printf( "<div class='bm-group-%s%s'><li><ul>",
				$group->get_slug(),
				$expandable_Group ? ' bm-group-expandable' : '' );


			if ( $expandable_Group ) {
				// add radio before "PRZELEW INTERNETOWY" logo to add possibility
				// show-hide list of banks
				printf( '<li class="bm-payment-channel-group-item">
							<label for="bm-gateway-bank-group">
								<input type="radio" name="bm-payment-channel-group" id="bm-gateway-bank-group" >
                                <img src="%s" class="bm-payment-channel-group-method-logo">
								<p class="bm-payment-channel-group-method-name">%s</p>
							</label>
							<span class="bm-payment-channel-method-desc">
							<span>
							<span class="payment-method-description">%s</span>
							</span>
                        </span>
						</li>',
					$group->get_icon(),
					$group->get_name(),
					$group->get_subtitle()
				);

				echo '<div class="bm-group-expandable-wrapper">';
			}


			foreach ( $group->get_items() as $item ) {
				printf( '<li class="bm-payment-channel-item %s">
							<label class="bm-payment-channel-label" for="bm-gateway-id-%s">
								<input type="radio" name="bm-payment-channel" onclick="addCurrentClass(this)" data-index="0" id="bm-gateway-id-%s" value="%s" class="%s">
								<img src="%s" class="bm-payment-channel-method-logo">
								<p class="bm-payment-channel-method-name">%s</p>
							</label>
							<span class="bm-payment-channel-method-desc">%s</span>
                        </li>',
					(string) $item->get_class(),
					$item->get_id(),
					$item->get_id(),
					$item->get_id(),
					$expandable_Group ? 'bm-payment-channel-group-in-group' : '',
					$item->get_icon(),
					$item->get_name(),
					$item->get_description()
				);
				$script = $item->get_script();
				if ( $script ) {
					echo $item->get_script();
				}
			}
			if ( $expandable_Group ) {
				echo '</div>';
			}
			printf( "</li></ul></div>" );


		}

		echo '</ul></div>';

		echo '</div>';

		?>

        <script>
			<?php if ('yes' === $this->get_option( 'compatibility_with_live_update_checkout',
				'no' )):?>
            BmTimerValue = 1500;
			<?php else:?>
            BmTimerValue = 0;
			<?php endif;?>

            jQuery(document).ready(function () {
                clearTimeout(bm_global_timer)

                var isBlueMediaSelected = jQuery('#payment_method_bluemedia').is(':checked');

                if (isBlueMediaSelected) {
                    BmDeactivateNewOrderButton()
                }

                blueMediaRadioHide();

                bm_global_timer = setTimeout(function () {
                    bm_global_update_checkout_in_progress = 0;
                    blueMediaRadioTest();
                }, BmTimerValue);

            });

            jQuery("input[name='payment_method']").on("click touchstart", function () {
                var radioButtons = jQuery("input[name='payment_method']");
                for (var i = 0; i < radioButtons.length; i++) {
                    if (radioButtons[i].checked && radioButtons[i].id !== "payment_method_bluemedia") {
                        BmActivateNewOrderButton()
                        BmDeselectGroupedLi()
                    }
                }

                jQuery("input[id='payment_method_bluemedia']").on("click", function () {
                    jQuery(".payment_box").find("input[type='radio']").prop("checked", false);
                    jQuery(".payment_box").find("li").removeClass("selected");
                    BmDeactivateNewOrderButton()
                });

                clearTimeout(bm_global_timer);
                bm_global_timer = setTimeout(function () {

                    if (0 === bm_global_update_checkout_in_progress) {
                        console.log('blueMediaRadioTest bm_global_update_checkout_in_progress ' + bm_global_update_checkout_in_progress)
                        blueMediaRadioTest();
                    }
                }, BmTimerValue);

                jQuery('#payment_method_bluemedia').on('click', function () {

                    clearTimeout(bm_global_timer);
                    bm_global_timer = setTimeout(function () {

                        if (0 === bm_global_update_checkout_in_progress) {
                            console.log('click bm_global_update_checkout_in_progress ' + bm_global_update_checkout_in_progress)
                            blueMediaRadioShow();
                        }
                    }, BmTimerValue);

                });

                jQuery('ul.wc_payment_methods > li.wc_payment_method:not(.payment_method_bluemedia)').on('click', function () {
                    blueMediaRadioHide();
                });
            });

        </script><?php
	}


	public
	function render_channels_for_admin_panel(
		array $channels
	) {
		$group_arr = ( new Group_Mapper( $channels ) )->map_for_admin_panel();

		echo '<div class="payment_box payment_box_wpadmin payment_method_bacs">';
		echo '<div class="bm-payment-channels-wrapper">';
		echo '<ul id="shipping_method" class="woocommerce-shipping-methods">';

		/**
		 * @var Group[] $group_arr
		 */
		foreach ( $group_arr as $group ) {

			$expandable_Group = $group instanceof Expandable_Group;

			if ( empty( $group->get_items() ) ) {
				continue;
			}

			printf( "<div class='bm-group-%s%s'><li><ul>",
				$group->get_slug(),
				$expandable_Group ? ' bm-group-expandable' : '' );

			if ( $expandable_Group ) {
				printf( "<p class='bm-group-name'>%s</p>",
					$group->get_name() );
			}

			foreach ( $group->get_items() as $item ) {
				printf( '
                    <li class="bm-payment-channel-item %s">
                    <span class="bm-payment-channel-method-logo">
                            <img style="" src="%s">
                            </span>
                    <span class="%s">%s</span>
                            </li>',
					(string) $item->get_class(),
					$item->get_icon(),
					$expandable_Group ? 'bm-inside-expandable-group' : 'bm-inside-single-item',
					$item->get_name(),
				);
			}

			printf( "</li></ul></div>" );


		}

		echo '</ul></div>';
		echo '</div>';
	}

	/**
	 * Reposition an array element by its key.
	 *
	 * @param array $array The array being reordered.
	 * @param string|int $key They key of the element you want to reposition.
	 * @param int $order The position in the array you want to move the element
	 *     to. (0 is first)
	 *
	 * @throws \Exception
	 */
	private function repositionArrayElement(
		array &$array,
		$key,
		int $order
	): void {
		if ( ( $a = array_search( $key, array_keys( $array ) ) ) === false ) {
			throw new Exception( "The {$key} cannot be found in the given array." );
		}
		$p1    = array_splice( $array, $a, 1 );
		$p2    = array_splice( $array, 0, $order );
		$array = array_merge( $p2, $p1, $array );
	}


}
