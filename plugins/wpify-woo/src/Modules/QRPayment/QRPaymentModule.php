<?php

namespace WpifyWoo\Modules\QRPayment;

use DateTime;
use Exception;
use WC_Email;
use WC_Order;
use WP_Error;
use WpifyWoo\Abstracts\AbstractModule;
use WpifyWooDeps\Rikudou\CzQrPayment\Options\QrPaymentOptions;
use WpifyWooDeps\Rikudou\CzQrPayment\QrPayment;
use WpifyWooDeps\Rikudou\Iban\Iban\CzechIbanAdapter;
use WpifyWooDeps\Rikudou\Iban\Iban\IBAN;

class QRPaymentModule extends AbstractModule {
	/**
	 * @return void
	 */
	public function setup() {
		add_filter( 'wpify_woo_settings_' . $this->id(), array( $this, 'settings' ) );
		add_action( 'template_redirect', [ $this, 'display_qr_code_on_thankyou' ] );
		add_action( 'wpify_woo_render_qr_code', [ $this, 'display_qr_code' ] );
		add_shortcode( 'wpify_woo_render_qr_code', array( $this, 'display_qr_code_shortcode' ) );

		$email_position_hook = $this->get_setting( 'email_position' ) ?: 'woocommerce_email_before_order_table';
		add_action( $email_position_hook, [ $this, 'display_qr_code_in_email' ], 20, 4 );
	}

	public function display_qr_code_on_thankyou() {
		if ( is_checkout() && ! empty( is_wc_endpoint_url( 'order-received' ) ) ) {
			$position = $this->get_setting( 'thankyou_position' );

			if ( ! empty( $position ) && $position === 'dont_show' ) {
				return;
			}

			if ( $position === 'before_order' ) {
				add_action( 'woocommerce_thankyou', [ $this, 'display_qr_code' ], 1, 1 );
			} elseif ( $position === 'after_thankyou' ) {
				add_action( 'woocommerce_thankyou', [ $this, 'display_qr_code' ], 20, 1 );
			} else {
				add_action( 'woocommerce_before_thankyou', [ $this, 'display_qr_code' ], 10, 1 );
			}
		}
	}

	/**
	 * Module ID
	 * @return string
	 */
	public function id(): string {
		return 'qr_payment';
	}

	/**
	 * Module name
	 * @return string
	 */
	public function name(): string {
		return __( 'QR Payment', 'wpify-woo' );
	}

	/**
	 * Module settings
	 * @return array[]
	 */
	public function settings(): array {

		$settings = array(
			array(
				'id'      => 'payment_methods',
				'type'    => 'multi_group',
				'label'   => __( 'Enabled payment methods', 'wpify-woo' ),
				'buttons' => array(
					'add'    => __( 'Add payment method', 'wpify-woo-conditional-payment' ),
					'remove' => __( 'Remove payment method', 'wpify-woo-conditional-payment' ),
				),
				'items'   => [
					[
						'id'      => 'payment_method',
						'type'    => 'select',
						'label'   => __( 'Payment method', 'wpify-woo' ),
						'options' => function () {
							return $this->plugin->get_woocommerce_integration()->get_gateways();
						},
					],
					[
						'id'      => 'enabled_emails',
						'type'    => 'multi_select',
						'label'   => __( 'Show in emails', 'wpify-woo' ),
						'options' => function () {
							return $this->get_emails_select();
						},
					],
					[
						'id'      => 'accounts',
						'type'    => 'multi_group',
						'label'   => __( 'Accounts', 'wpify-woo' ),
						'buttons' => array(
							'add'    => __( 'Add account', 'wpify-woo-conditional-payment' ),
							'remove' => __( 'Remove account', 'wpify-woo-conditional-payment' ),
						),
						'items'   => [
							[
								'id'    => 'number',
								'type'  => 'text',
								'label' => __( 'Account number', 'wpify-woo' ),
							],
							[
								'id'    => 'bank_code',
								'type'  => 'text',
								'label' => __( 'Bank Code', 'wpify-woo' ),
							],
							[
								'id'    => 'iban',
								'type'  => 'text',
								'label' => __( 'IBAN - required for SK payments', 'wpify-woo' ),
							],
							[
								'id'      => 'type',
								'type'    => 'select',
								'label'   => __( 'QR Type', 'wpify-woo' ),
								'options' => [
									[
										'label' => 'CZ - QR Platba',
										'value' => 'cz',
									],
									[
										'label' => 'SK - Pay BY Square',
										'value' => 'sk',
									],
								],
							],
							[
								'id'      => 'enabled_currencies',
								'type'    => 'multi_select',
								'label'   => __( 'Enabled currencies', 'wpify-woo' ),
								'options' => function () {
									return $this->get_currencies_select();
								},
							],
							[
								'id'      => 'enabled_countries',
								'type'    => 'multi_select',
								'label'   => __( 'Enabled countries', 'wpify-woo' ),
								'options' => function () {
									return $this->get_countries_select();
								},
							],
							[
								'id'    => 'label',
								'type'  => 'text',
								'label' => __( 'Info label', 'wpify-woo' ),
							],
						],
					],
				],
			),
			[
				'id'    => 'note',
				'label' => __( 'Message to recipient', 'wpify-woo' ),
				'desc'  => __( 'Enter the message to recipient if you want it. You can use codes <code>{order}</code> to insert the order number and <code>{shop_name}</code> to insert the name of the shop. So the message might look like, for example, "QR payment order {order} from {shop_name}".', 'wpify-woo' ),
				'type'  => 'text',
			],
			[
				'id'    => 'title_before',
				'label' => __( 'Title before', 'wpify-woo' ),
				'desc'  => __( 'You can use codes <code>{order}</code> to insert the order number and <code>{total}</code> to insert the value of order.', 'wpify-woo' ),
				'type'  => 'wysiwyg',
			],
			[
				'id'    => 'title_after',
				'label' => __( 'Title after', 'wpify-woo' ),
				'desc'  => __( 'You can use codes <code>{order}</code> to insert the order number and <code>{total}</code> to insert the value of order.', 'wpify-woo' ),
				'type'  => 'wysiwyg',
			],
			array(
				'id'      => 'thankyou_position',
				'label'   => __( 'QR position on thank you page', 'wpify-woo' ),
				'desc'    => __( 'Select where the QR code should be placed on the thank you page.', 'wpify-woo' ),
				'type'    => 'select',
				'options' => [
					[
						'label' => __( 'Dont show', 'wpify-woo' ),
						'value' => 'dont_show',
					],
					[
						'label' => __( 'Before page content', 'wpify-woo' ),
						'value' => 'before_thankyou',
					],
					[
						'label' => __( 'Before Order details', 'wpify-woo' ),
						'value' => 'before_order',
					],
					[
						'label' => __( 'After page content', 'wpify-woo' ),
						'value' => 'after_thankyou',
					],
				],
			),
			array(
				'id'      => 'email_position',
				'label'   => __( 'QR position in emails', 'wpify-woo' ),
				'desc'    => __( 'Select where the QR code should be placed in the emails.', 'wpify-woo' ),
				'type'    => 'select',
				'options' => [
					[
						'label' => __( 'Before order table', 'wpify-woo' ),
						'value' => 'woocommerce_email_before_order_table',
					],
					[
						'label' => __( 'After order table', 'wpify-woo' ),
						'value' => 'woocommerce_email_after_order_table',
					],
					[
						'label' => __( 'In order meta', 'wpify-woo' ),
						'value' => 'woocommerce_email_order_meta',
					],
				],
				'default' => 'woocommerce_email_before_order_table',
			),
			[
				'id'    => 'compatibility_mode',
				'label' => __( 'Compatibility mode', 'wpify-woo' ),
				'desc'  => __( 'The SK version requires XZ utils (https://tukaani.org/xz/). If your serevr does not support this, you can enable the compatibility mode, in which the QR code will be generated using the external API QR-Platba.cz and QRGenerator.sk. This is not recommended for performance reasons, as an unnecessary API call is done on thankyou page.',
					'wpify-woo' ),
				'type'  => 'toggle',
			],
			[
				'id'    => 'save_img',
				'label' => __( 'Save as image', 'wpify-woo' ),
				'desc'  => __( 'Some email clients have a problem with displaying images in base64. If you enable this, the QR code will be saved as a file and then linked to.', 'wpify-woo' ),
				'type'  => 'toggle',
			],

		);

		return $settings;
	}

	public function get_currencies_select() {
		$currencies = [];
		foreach ( get_woocommerce_currencies() as $key => $val ) {
			$currencies[] = [
				'label' => $val,
				'value' => $key,
			];
		}

		return $currencies;
	}

	public function get_emails_select() {
		$emails = [];
		foreach ( WC()->mailer()->get_emails() as $wc_email ) {
			$emails[] = [
				'label' => $wc_email->title . ' - ' . esc_html( $wc_email->is_customer_email() ? __( 'Customer', 'woocommerce' ) : $wc_email->get_recipient() ),
				'value' => $wc_email->id,
			];
		}

		return $emails;
	}

	public function get_countries_select() {
		$countries = [];
		foreach ( WC()->countries->get_allowed_countries() as $key => $val ) {
			$countries[] = [
				'label' => $val,
				'value' => $key,
			];
		}

		return $countries;
	}

	/**
	 * Render QR code
	 *
	 * @param int|WC_Order $order
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function render_qr_code( $order, $account ) {
		$note      = $this->get_setting( 'note' );
		$note_text = '';
		$qrCode    = '';

		if ( ! empty( $note ) ) {
			$replaces  = [
				'{order}'     => $order->get_order_number(),
				'{shop_name}' => get_bloginfo( 'name' ),
			];
			$note_text = str_replace( array_keys( $replaces ), array_values( $replaces ), $note );
		} else {
			$note_text = $this->name();
		}

		$payment_details = [
			'total'          => $order->get_total(),
			'vs'             => $order->get_order_number(),
			'currency'       => $order->get_currency(),
			'due_date'       => date( 'Y-m-d' ),
			'account_number' => $account['number'] ?? '',
			'bank_code'      => $account['bank_code'] ?? '',
			'iban'           => isset( $account['iban'] ) ? str_replace( ' ', '', $account['iban'] ) : '',
			'note'           => $note_text,
		];
		$payment_details = apply_filters( 'wpify_woo_qr_payment_details', $payment_details );

		if ( ! $this->get_setting( 'compatibility_mode' ) ) {
			if ( 'cz' === $account['type'] ) {
				$payment = new QrPayment( new CzechIbanAdapter( $payment_details['account_number'], $payment_details['bank_code'] ), [
					QrPaymentOptions::VARIABLE_SYMBOL => $payment_details['vs'],
					QrPaymentOptions::AMOUNT          => $payment_details['total'],
					QrPaymentOptions::CURRENCY        => $payment_details['currency'],
					QrPaymentOptions::DUE_DATE        => new DateTime( $payment_details['due_date'] ),
					QrPaymentOptions::COMMENT         => $payment_details['note'],
				] );
				try {
					$qrCode = $payment->getQrCode()->getDataUri();
				} catch ( Exception $e ) {
					$this->plugin->get_logger()->error( sprintf( 'QR payment: error create QR code.' ),
						array(
							'data' => array(
								'order_id'        => $order->get_id(),
								'message'         => $e->getMessage(),
								'payment_details' => $payment_details,
							),
						)
					);

					return new WP_Error( 'error', 'QR ERROR: ' . $e->getMessage() );
				}
			} elseif ( 'sk' === $account['type'] ) {
				$payment = new \WpifyWooDeps\rikudou\SkQrPayment\QrPayment();
				$payment->setOptions( [
					QrPaymentOptions::AMOUNT                                          => $payment_details['total'],
					QrPaymentOptions::CURRENCY                                        => $payment_details['currency'],
					QrPaymentOptions::DUE_DATE                                        => new DateTime( $payment_details['due_date'] ),
					QrPaymentOptions::VARIABLE_SYMBOL                                 => $payment_details['vs'],
					QrPaymentOptions::COMMENT                                         => $payment_details['note'],
					\WpifyWooDeps\rikudou\SkQrPayment\Payment\QrPaymentOptions::IBANS => [
						new IBAN( $payment_details['iban'] ),
					],
				] );
				try {
					$qrCode = $payment->getQrCode()->getDataUri();
				} catch ( Exception $e ) {
					$this->plugin->get_logger()->error( sprintf( 'QR payment: error create QR code.' ),
						array(
							'data' => array(
								'order_id'        => $order->get_id(),
								'message'         => $e->getMessage(),
								'payment_details' => $payment_details,
							),
						)
					);

					return new WP_Error( 'error', 'QR ERROR: ' . $e->getMessage() );
				}
			}
		} else {
			if ( 'cz' === $account['type'] ) {
				$account_prefix = '';
				$account_number = $payment_details['account_number'];
				if ( str_contains( $account_number, '-' ) ) {
					$ex             = explode( '-', $account_number );
					$account_prefix = $ex[0];
					$account_number = $ex[1];
				}
				$url    = add_query_arg( [
					'accountPrefix' => (int) $account_prefix,
					'accountNumber' => (int) $account_number,
					'bankCode'      => (int) $payment_details['bank_code'],
					'amount'        => floatval( $payment_details['total'] ),
					'currency'      => (string) $payment_details['currency'],
					'vs'            => (int) $payment_details['vs'],
					'message'       => (string) $payment_details['note'],
				], 'https://api.paylibo.com/paylibo/generator/czech/image' );
				$qrCode = base64_encode( wp_remote_retrieve_body( wp_remote_get( $url ) ) );
				$qrCode = "data:image/png;base64,{$qrCode}";
			} elseif ( 'sk' === $account['type'] ) {
				$url    = add_query_arg( [
					'iban'         => (string) $payment_details['iban'],
					'bankCode'     => (int) $payment_details['bank_code'],
					'amount'       => floatval( $payment_details['total'] ),
					'currency'     => (string) $payment_details['currency'],
					'vs'           => (int) $payment_details['vs'],
					'payment_note' => (string) $payment_details['note'],
					'format'       => 'png',
					'size'         => 256,
				], 'https://api.qrgenerator.sk/by-square/pay/base64' );
				$qrCode = json_decode( wp_remote_retrieve_body( wp_remote_get( $url ) ) );
				$qrCode = "data:{$qrCode->mime};base64,{$qrCode->data}";
			}
		}

		return $qrCode;
	}

	/**
	 * Save QR code as image file
	 *
	 * @param $base64_string
	 * @param $output_file
	 *
	 * @return false|int
	 */
	public function save_as_file( $base64_string, $output_file ) {
		$img  = str_replace( 'data:image/png;base64,', '', $base64_string );
		$img  = str_replace( ' ', '+', $img );
		$data = base64_decode( $img );
		$file = $output_file;

		return file_put_contents( $file, $data );
	}

	/**
	 * Display QR code
	 *
	 * @param int|WC_Order $order
	 *
	 * @return void|WP_Error|null
	 * @throws Exception
	 */
	public function display_qr_code( $order ) {
		if ( is_numeric( $order ) ) {
			$order = wc_get_order( $order );
		}

		$payment_methods = $this->get_setting( 'payment_methods' );

		if ( ! is_array( $payment_methods ) || empty( $payment_methods ) ) {
			return null;
		}

		$payment_method = $order->get_payment_method();
		$currency       = $order->get_currency();
		$country        = $order->get_billing_country();
		$accounts       = [];

		foreach ( $payment_methods as $item ) {
			if ( empty( $item['payment_method'] ) || $payment_method !== $item['payment_method'] ) {
				continue;
			}

			foreach ( $item['accounts'] as $account ) {
				if ( ! empty( $account['enabled_currencies'] ) && ! in_array( $currency, $account['enabled_currencies'] ) ) {
					continue;
				}
				$enabled_countries = ! empty( $account['enabled_countries'] ) ? $account['enabled_countries'] : array_keys( WC()->countries->get_allowed_countries() );
				if ( ! in_array( $country, $enabled_countries ) ) {
					continue;
				}
				$accounts[] = $account;
			}
		}
		if ( empty( $accounts ) ) {
			return null;
		}

		$qrCodes = [];
		$qrInfo  = [];

		foreach ( $accounts as $key => $account ) {
			$base64 = $this->render_qr_code( $order, $account );

			if ( is_wp_error( $base64 ) ) {
				echo $base64->get_error_message();
				continue;
			}

			if ( $this->get_setting( 'save_img' ) ) {
				$qr_path  = wp_upload_dir()['basedir'] . '/qr-payment/';
				$qr_url   = wp_upload_dir()['baseurl'] . '/qr-payment/';
				$hash     = ! empty( $account['number'] ) ? hash( 'sha1', $order->get_id() . $account['number'] ) : hash( 'sha1', $order->get_id() . $account['iban'] );
				$compa    = $this->get_setting( 'compatibility_mode' ) ? '1' : '0';
				$filename = 'qr-' . $account['type'] . $compa . $hash . '.png';

				if ( ! file_exists( $qr_path ) ) {
					mkdir( $qr_path, 0755, true );
				}

				if ( ! file_exists( $qr_path . $filename ) ) {
					$save_img = $this->save_as_file( $base64, $qr_path . $filename );

					if ( ! $save_img ) {
						return new WP_Error( 'error', __( 'The creation of the file for the QR code failed.', 'wpify-woo' ) );
					}
				}

				$qrCodes[ $key ] = $qr_url . $filename;
			} else {
				$qrCodes[ $key ] = $base64;
			}

			$qrInfo[ $key ] = $account['label'] ?? '';
		}

		if ( $qrCodes ) {
			$replaces = [
				'{order}' => $order->get_order_number(),
				'{total}' => wc_price( $order->get_total() ),
			];

			$title_before = str_replace( array_keys( $replaces ), array_values( $replaces ), $this->get_setting( 'title_before' ) );
			$title_after  = str_replace( array_keys( $replaces ), array_values( $replaces ), $this->get_setting( 'title_after' ) );

			echo '<div class="wpify-woo-qr-payment">';
			echo sprintf( '<div class="wpify-woo-qr-payment_title-before">%s</div>', $title_before );
			foreach ( $qrCodes as $qrKey => $qrCode ) {
				if ( is_wp_error( $qrCode ) ) {
					echo $qrCode->get_error_message();
				} else {
					$altText = __( 'QR Payment', 'wpify-woo' );
					if ( isset( $qrInfo[ $qrKey ] ) && ! empty( $qrInfo[ $qrKey ] ) ) {
						echo '<div class="wpify-woo-qr-payment_code">';
						echo '<p>' . $qrInfo[ $qrKey ] . '</p>';
						echo "<img src='{$qrCode}' alt='{$altText}' style='display: inline-block'>";
						echo '</div>';
					} else {
						echo "<img class='wpify-woo-qr-payment_code' src='{$qrCode}' alt='{$altText}' style='display: inline-block'>";
					}
				}
			}
			echo sprintf( '<div class="wpify-woo-qr-payment_title-after">%s</div>', $title_after );
			echo '</div>';
		}
	}

	/**
	 * Display QR code in emails
	 *
	 * @param int|WC_Order         $order
	 * @param WC_Email|string|null $email
	 *
	 * @throws Exception
	 */
	public function display_qr_code_in_email( $order = null, $sent_to_admin = null, $plain_text = null, $email = null ) {
		$payment_methods = $this->get_setting( 'payment_methods' );

		if ( ! is_array( $payment_methods ) || empty( $payment_methods ) ) {
			return null;
		}

		if ( is_numeric( $order ) ) {
			$order = wc_get_order( $order );
		}

		foreach ( $payment_methods as $item ) {
			if (
				empty( $order )
				|| ! is_a( $order, 'WC_Order' )
				|| $order->get_payment_method() != $item["payment_method"]
				|| empty( $email )
				|| ! is_a( $email, 'WC_Email' )
				|| empty( $item['enabled_emails'] )
				|| ! in_array( $email->id, $item['enabled_emails'] )
				|| $plain_text
			) {
				continue;
			}

			$this->display_qr_code( $order );
		}
	}

	/**
	 * Render the [wpify_woo_render_qr_code] shortcode.
	 *
	 * @return string
	 */
	public function display_qr_code_shortcode() {
		if ( ! isset( $_GET['key'] ) ) {
			return;
		}

		$order_id = wc_get_order_id_by_order_key( $_GET['key'] );

		ob_start();
		$this->display_qr_code( $order_id );

		return ob_get_clean();
	}
}
