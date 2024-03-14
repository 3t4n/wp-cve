<?php

namespace CTXFeed\V5\Query;

class BOTHQuery implements QueryInterface {
	private $config;

	public function __construct( $config, $args = [] ) {
		$this->config    = $config;
		$this->arguments = empty($args) ? $this->get_query_arguments() : wp_parse_args( $args, $this->get_query_arguments());
	}

	public function get_product_types() {
		return false;
	}

	public function get_query_arguments() {
		return [];
	}

	public function get_product_status() {
		return false;
	}

	public function product_ids() {
		$wp = ( new WPQuery( $this->config, $this->arguments ) )->product_ids();
		$wc = ( new WCQuery( $this->config, $this->arguments ) )->product_ids();

		return array_unique( array_merge( $wc, $wp ) );
	}
}
