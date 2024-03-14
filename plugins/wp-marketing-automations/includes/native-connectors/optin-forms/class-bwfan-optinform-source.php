<?php

if ( ! bwfan_is_autonami_pro_active() && ! class_exists( 'BWFAN_OptinForm_Source' ) ) {

	class BWFAN_OptinForm_Source extends BWFAN_Source {
		private static $instance = null;

		public function __construct() {
			$this->event_dir  = __DIR__;
			$this->nice_name  = __( 'Funnel Builder', 'autonami-automations-pro' );
			$this->group_name = __( 'Funnel Builder', 'autonami-automations-pro' );
			$this->group_slug = 'woofunnels';
			$this->priority   = 8;
		}

		/**
		 * Ensures only one instance of the class is loaded or can be loaded.
		 *
		 * @return BWFAN_CalderaForm_Source|null
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	}

	/**
	 * Register this as a source.
	 */
	if ( function_exists( 'bwfan_is_funnel_optin_forms_active' ) && bwfan_is_funnel_optin_forms_active() ) {
		BWFAN_Load_Sources::register( 'BWFAN_OptinForm_Source' );
	}
}
