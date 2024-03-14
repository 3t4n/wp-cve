<?php

use Payamito\Woocommerce\Modules\Abandoned\Admin\Settings;
use Payamito\Woocommerce\Modules\Base;

/**
 *  Recover Abandoned cart module
 *
 * @package
 * @since 1.1
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( "Payamito_WC_Abandoned" ) ) {
	final class Payamito_WC_Abandoned extends Base
	{

		public $modules = [];

		public function get_name()
		{
			return "abandoned";
		}

		public function __construct()
		{
			$this->init();
		}

		public function init()
		{
			$this->define();
			$this->include();
			$this->class();
		}

		protected function include()
		{
			require_once PAYAMITO_WC_Module_DIR . '/abandonment/class-helper.php';
			require_once PAYAMITO_WC_Module_DIR . '/abandonment/class-db.php';
			require_once PAYAMITO_WC_Module_DIR . '/abandonment/class-template.php';
			require_once PAYAMITO_WC_Module_DIR . '/abandonment/admin/class-settings.php';
			require_once PAYAMITO_WC_Module_DIR . '/abandonment/class-abandonment.php';
		}

		public function define()
		{
			if ( ! defined( 'PAYAMITO_WC_ABANDONED' ) ) {
				define( 'PAYAMITO_WC_ABANDONED', "1.0.0" );
			}
		}

		protected function class()
		{
			if ( is_admin() ) {
				$settings = new Settings;
				$settings->add_settings();
			}

			Payamito\Woocommerce\Modules\Abandoned\Abandonment::get_instance();
		}

		public function activate()
		{
			do_action( "payamito_wc_abandoned_activate" );
		}

		public function deactivate()
		{
			do_action( "payamito_wc_abandoned_deactivate" );
		}
	}
}

function payamito_wc_abandoned()
{
	return Payamito_WC_Abandoned::get_instance();
}