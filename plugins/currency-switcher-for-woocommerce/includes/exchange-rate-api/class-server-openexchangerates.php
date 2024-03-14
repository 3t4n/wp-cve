<?php

class PMSC_Server_Openexchangerates extends PMCS_Exchange_Server_Abstract {
	public $api_url = 'https://openexchangerates.org/api/latest.json';
	public $app_id = 'e5edc61adc1f474c83175a9fdffa642f';
	public $base = 'USD';
	public $label = '';
	public $website = 'https://openexchangerates.org';

	public function __construct() {
		$this->label = 'openexchangerates.org';
		$this->base = $this->get_shop_base();
	}

	public function settings() {
		$fields = array();
		$fields[] = array(
			'title'    => __( 'APP ID', 'pmcs' ),
			'desc'     => __( 'Get your app id at <a target="_blank" href="https://openexchangerates.org/account/app-ids">openexchangerates.org</a>', 'pmcs' ),
			'id'       => 'pmcs_openexchangerates_app_id',
			'default'  => '',
			'type'     => 'text',
			'desc_tip' => false,
		);
		return $fields;
	}

	public function before_update() {
		if ( ! get_option( 'pmcs_openexchangerates_app_id' ) ) {
			?>
		<div class="notice notice-warning is-dismissible">
			<p><?php _e( 'Please enter your app id', 'pmcs' ); ?></p>
		</div>
			<?php
		}
	}

	public function build_query_url() {
		$app_id = get_option( 'pmcs_openexchangerates_app_id', $this->app_id );
		if ( ! $app_id ) {
			$app_id = $this->app_id;
		}
		$url = add_query_arg(
			array(
				'app_id' => $app_id,
				'base' => $this->base,
			),
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
		$rest = $this->api_request();
		$this->save( $rest );
		return $rest;
	}


}
