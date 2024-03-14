<?php

namespace Payamito\Woocommerce\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'direct access abort ' );
}

if ( ! class_exists( "Modules" ) ) {
	class Modules
	{

		private static $instance;
		private        $modules = [];

		/**
		 * Class instance
		 *
		 * @return static
		 * @since 0.0.1
		 */
		public static function get_instance()
		{
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct()
		{
			self::define();
			self::include();
			$this->class();
		}

		private static function define()
		{
			if ( ! defined( 'PAYAMITO_WC_Module_DIR' ) ) {
				define( 'PAYAMITO_WC_Module_DIR', PAYAMITO_WC_DIR . '/modules' );
			}

			if ( ! defined( 'PAYAMITO_WC_Module_URL' ) ) {
				define( 'PAYAMITO_WC_Module_URL', PAYAMITO_WC_URL . '/modules' );
			}
		}

		private static function include()
		{
			include_once PAYAMITO_WC_Module_DIR . '/class-base.php';

			require_once PAYAMITO_WC_Module_DIR . '/abandonment/module.php';
		}

		private function class()
		{
			$this->modules['abandonment'] = payamito_wc_abandoned();
		}

		public function get_modules()
		{
			return $this->modules;
		}
	}
}
