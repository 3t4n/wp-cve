<?php

class PMCS_Exchange_Rate_API {
	/**
	 * List exchange rate API servers.
	 *
	 * @var array
	 */
	protected $servers = array();

	/**
	 * Current woocommerce currency code.
	 *
	 * @var boolean
	 */
	protected $base = false;

	/**
	 * Constuctor.
	 */
	public function __construct() {
		$this->base = get_woocommerce_currency();
		add_action( 'wp_ajax_load_exchange_rates', array( $this, 'ajax_load_exchange_rates' ) );
	}

	/**
	 * Ajax load exchannge rates.
	 *
	 * @return void
	 */
	public function ajax_load_exchange_rates() {
		$nonce = false;
		if ( isset( $_REQUEST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) );
		}
		if ( ! wp_verify_nonce( $nonce, 'pmcs_settings' ) ) {
			die( 'Security check' );
		}
		$form_currency = isset( $_REQUEST['form_currency'] ) ? sanitize_text_field( $_REQUEST['form_currency'] ) : $this->base;
		$to_currency = isset( $_REQUEST['to_currency'] ) ? sanitize_text_field( $_REQUEST['to_currency'] ) : '';

		wp_send_json( $this->convert_base( $form_currency, $to_currency ) );
		die();
	}

	/**
	 * Convert currency rate base on the base rate.
	 *
	 * @param string|bool $base Currency code.
	 * @param string|bool $to API rate data.
	 * @return array
	 */
	public function convert_base( $base = false, $to = false ) {
		if ( ! $base ) {
			$base = $this->base;
		}
		if ( empty( $data ) || ! is_array( $data ) || ! isset( $data['base'] ) ) {
			$data = false;
		}

		if ( ! $data ) {
			$data = $this->get_rates( $base, $to );
		}

		if ( $data['base'] != $base ) {
			$base_rate = $data['rates'][ $base ];
			$one_base = false;
			if ( 0 != $base_rate ) {
				$one_base = round( 1 / $base_rate, 10 );
			}
			$new_rates = array();
			foreach ( $data['rates'] as $key => $value ) {
				$new_rates[ $key ] = $value * $one_base;
			}
			$data['base'] = $base;
			$data['rates'] = $new_rates;
		}
		return $data;
	}

	/**
	 * Add exchange rate server.
	 *
	 * @param string $class_name
	 * @return void
	 */
	public function add_server( $class_name = '' ) {
		if ( class_exists( $class_name ) ) {
			$c = new $class_name();
			if ( $c instanceof PMCS_Exchange_Server_Abstract ) {
				$this->servers[ $class_name ] = $c;
			}
		}
	}

	/**
	 * Undocumented function
	 *
	 * @param string $base From currency.
	 * @param string $to To currency.
	 * @return bool|float
	 */
	public function covert( $base, $to ) {
		$ratas = $this->get_rates();
		if ( $base == $ratas['base'] ) {
			if ( isset( $ratas['rates'][ $to ] ) ) {
				return $ratas['rates'][ $to ];
			}
		}
		return false;
	}

	/**
	 * Get current exchange rate api server.
	 *
	 * @return string
	 */
	public function get_current_server_id() {
		return get_option( 'pmsc_exchange_rate_server', false );
	}

	/**
	 * Get exchange rate server object by class name.
	 *
	 * @param string $class_name API server class name.
	 * @return PMCS_Exchange_Server_Abstract|bool|object
	 */
	public function get_server_by_id( $class_name ) {
		if ( isset( $this->servers[ $class_name ] ) ) {
			return $this->servers[ $class_name ];
		}
		return false;
	}

	/**
	 * Get all rates from API server.
	 *
	 * @return array
	 */
	public function get_rates( $from = '', $to = '' ) {
		$current_server = $this->get_current_server_id();
		$server = $this->get_server_by_id( $current_server );
		if ( $server ) {
			return $server->get_rates( $from, $to );
		}
		return array(
			'base' => '',
			'rates' => array(),
		);
	}

	/**
	 * Get currency rate by code.
	 *
	 * @param string $currency_code
	 * @return number|bool
	 */
	public function get_rate( $currency_code ) {
		$rates = $this->get_rates();
		$currency_code = strtoupper( $currency_code );
		if ( ! isset( $rates['rates'] ) || ! is_array( $rates['rates'] ) ) {
			return false;
		}

		if ( ! isset( $rates['rates'][ $currency_code ] ) ) {
			return false;
		}

		return $rates['rates'][ $currency_code ];
	}

	/**
	 * Update currencies rates.
	 *
	 * @return void
	 */
	public function update() {
		$current_server = $this->get_current_server_id();
		if ( 'none' == $current_server ) {
			return;
		}
		$server = $this->get_server_by_id( $current_server );
		if ( $server ) {
			if ( isset( $_POST['pmsc_exchange_rate_server'] ) ) {
				if ( method_exists( $server, 'before_update' ) ) {
					$server->before_update();
				}
			}

			$data = $server->update();
			/**
			 * @todo Update currency rates from API
			 */
			if ( isset( $data['rates'] ) && $data['base'] == $server->base ) {
				$option_key = 'pmcs_currencies';

				// Load using currencies.
				$currencies = $server->get_using_currencies();

				foreach ( $currencies as $k => $currency ) {
					$c = $currency['currency_code'];
					if ( $currency['default'] ) {
						$currency['rate'] = 1;
					} elseif ( isset( $data['rates'][ $c ] ) ) {
						$currency['rate'] = $data['rates'][ $c ];
					}
					$currencies[ $k ] = $currency;
				}
				update_option( $option_key, $currencies );

			}
		}
	}

	/**
	 * Get all exchange rate API servers.
	 *
	 * @return array
	 */
	public function get_servers() {
		return $this->servers;
	}

	/**
	 * Get all exchange rate API servers for select.
	 *
	 * @return array
	 */
	public function get_servers_for_select() {
		$options = array(
			'none' => __( 'Select an API server', 'pmcs' ),
		);
		foreach ( $this->servers as $class => $object ) {
			$options[ $class ] = $object->label;
		}
		return $options;
	}

	/**
	 * Get current api server settings.
	 *
	 * @return array
	 */
	public function get_settings() {
		$settings = array();
		foreach ( $this->servers as $class => $object ) {
			$server_settings = array();
			foreach ( $object->settings() as $field ) {
				if ( ! isset( $field['class'] ) ) {
					$field['class'] = '';
				}

				$field['class'] .= ' pmsc_exc_server ' . $class;
				$settings[] = $field;
			}
		}
		return $settings;
	}

}
