<?php

/**
 * Tilopay hook init, validate and activated plugin.
 *
 * @package  Tilopay
 */

namespace Tilopay;

class InitTilopay {
	public static function initHooks() {

		$tilopay_helper = new TilopayHelper();
		//Link settings
		add_filter('plugin_action_links_' . TPAY_PLUGIN_NAME, array($tilopay_helper, 'settings_link'));

		register_activation_hook(__FILE__, array($tilopay_helper, 'tpay_plugin_cancel_tilopay'));

		//Check if tilpay payment is enable
		add_filter('woocommerce_available_payment_gateways', array($tilopay_helper, 'tilopay_gateway_payment_status'));

		add_action('tpay_my_cron_tilopay', array($tilopay_helper, 'tpay_my_process_tilopay'));

		add_filter('cron_schedules', array($tilopay_helper, 'tpay_add_cron_recurrence_interval'));

		//Is located at TilopayHelper, function tilopay_on_init
		add_action('plugins_loaded', array(new TilopayHelper(), 'tilopay_on_init'));
		//Is located at TilopayHelper, function load_tilopay_textdomain
		add_filter('load_textdomain_mofile', array(new TilopayHelper(), 'load_tilopay_textdomain'), 10, 2);
	}
}
