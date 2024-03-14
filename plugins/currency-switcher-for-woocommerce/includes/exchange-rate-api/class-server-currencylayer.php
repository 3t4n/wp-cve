<?php

class PMSC_Server_Currencylayer extends PMCS_Exchange_Server_Abstract {
	public $api_url = 'http://www.apilayer.net/api/live';
	public $access_key = '183bd85117f5bbed2c587e5262576270';
	public $base = '';
	public $label = '';
	public $website = 'https://currencylayer.com/quickstart';

	public function __construct() {
		$this->label = 'currencylayer.com';
		$this->base = $this->get_shop_base();
	}

	public function settings() {
		$fields = array();
		$fields[] = array(
			'title'    => __( 'Access Key ', 'pmcs' ),
			'desc'     => __( 'Get your access_key at <a target="_blank" href="https://currencylayer.com/quickstart">currencylayer.com</a>', 'pmcs' ),
			'id'       => 'pmcs_currencylayer_access_key',
			'default'  => '',
			'type'     => 'text',
			'desc_tip' => false,
		);

		$fields[] = array(
			'title'    => __( 'Is paid plan.', 'pmcs' ),
			'desc'     => __( 'Check if you are using paid API.', 'pmcs' ),
			'id'       => 'pmcs_currencylayer_is_paid',
			'default'  => '',
			'type'     => 'checkbox',
			'desc_tip' => false,
		);

		return $fields;
	}

	public function before_update() {
		if ( ! get_option( 'pmcs_currencylayer_access_key' ) ) {
			?>
		<div class="notice notice-warning is-dismissible">
			<p><?php _e( 'Please enter your access key', 'pmcs' ); ?></p>
		</div>
			<?php
		}
	}

	public function build_query_url() {
		$access_key = get_option( 'pmcs_currencylayer_access_key', $this->access_key );
		$is_paid = get_option( 'pmcs_currencylayer_is_paid', 'no' );
		if ( ! $access_key ) {
			$access_key = $this->access_key;
		}

		$args = array(
			'access_key' => $access_key,
		);

		if ( 'yes' == $is_paid ) {
			$args['source'] = $this->base;
		}

		$url = add_query_arg(
			$args,
			$this->api_url
		);
		return $url;
	}

	/**
	 * Update exchange rates
	 *
	 * @param string|bool $from
	 * @param string|bool $to
	 * @return array
	 */
	public function update( $from = false, $to = false ) {
		$data = $this->api_request();
		$new_data = array(
			'base' => '',
			'rates' => array(),
		);
		if ( isset( $data['source'] ) ) {
			$new_data = array(
				'base' => $data['source'],
				'rates' => array(),
			);
			foreach ( $data['quotes'] as $code => $rate ) {
				$new_code = substr( $code, 3 );
				$new_data['rates'][ $new_code ] = $rate;
			}
		}

		$this->save( $new_data );
		return $new_data;
	}

}
