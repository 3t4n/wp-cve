<?php

class PMSC_Server_Fixer_IO extends PMCS_Exchange_Server_Abstract {
	public $api_url = 'http://data.fixer.io/api/latest';
	public $access_key = '40f5132cca9672351dbd28919b16e3ff';
	public $base = '';
	public $label = '';
	public $website = 'https://fixer.io/quickstart';

	public function __construct() {
		$this->label = 'fixer.io';
		$this->base = $this->get_shop_base();
	}

	public function settings() {
		$fields = array();
		$fields[] = array(
			'title'    => __( 'Access Key ', 'pmcs' ),
			'desc'     => __( 'Get your access_key at <a href="https://fixer.io/quickstart" target="blank">fixer.io</a>', 'pmcs' ),
			'id'       => 'pmcs_fixer_io_access_key',
			'default'  => '',
			'type'     => 'text',
			'desc_tip' => false,
		);

		$fields[] = array(
			'title'    => __( 'Is paid plan.', 'pmcs' ),
			'desc'     => __( 'Check if you are using paid API.', 'pmcs' ),
			'id'       => 'pmcs_fixer_io_is_paid',
			'default'  => '',
			'type'     => 'checkbox',
			'desc_tip' => false,
		);

		return $fields;
	}

	public function before_update() {
		if ( ! get_option( 'pmcs_fixer_io_access_key' ) ) {
			?>
		<div class="notice notice-warning is-dismissible">
			<p><?php _e( 'Please enter your access key', 'pmcs' ); ?></p>
		</div>
			<?php
		}
	}

	public function build_query_url() {
		$access_key = get_option( 'pmcs_fixer_io_access_key', $this->access_key );
		$is_paid = get_option( 'pmcs_fixer_io_is_paid', 'no' );
		if ( ! $access_key ) {
			$access_key = $this->access_key;
		}

		$args = array(
			'access_key' => $access_key,
		);

		if ( 'yes' == $is_paid ) {
			$args = array(
				'access_key' => $access_key,
				'base' => $this->base,
			);
		}

		$url = add_query_arg(
			$args,
			$this->api_url
		);
		return $url;
	}

}
