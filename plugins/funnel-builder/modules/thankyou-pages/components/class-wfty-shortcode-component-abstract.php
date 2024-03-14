<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WFTY_Shortcode_Component_Abstract' ) ) {
	#[AllowDynamicProperties]

 abstract class WFTY_Shortcode_Component_Abstract {

		public $data = false;
		protected $order = false;

		public function __construct( $shortcode_args ) {
			$this->data = $shortcode_args;
		}

		//Extended by Child
		public function get_slug() {
			return '';
		}

		//Extended by Child

		public function render() {
			return '';
		}

		//Extended by Child

		public function load_order( $order ) {
			$this->order = $order;
		}

		public function setup_data() {

		}

		public function get_meta() {
			return [];
		}

	}
}