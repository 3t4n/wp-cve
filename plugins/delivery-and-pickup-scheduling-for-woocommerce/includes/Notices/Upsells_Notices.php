<?php
/**
 * Class responsible for upsell notices.
 *
 * Author:         Uriahs Victor
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Notices
 */

namespace Lpac_DPS\Notices;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Lpac_DPS\Traits\Plugin_Info;

/**
 * Class Upsells_Notices.
 */
class Upsells_Notices extends Notice {

	use Plugin_Info;

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct() {
		$this->create_pro_notice();
		$this->GoogleAddonNotice();
	}

	/**
	 * Create initial pro released notice.
	 *
	 * @return void
	 */
	public function create_pro_notice() {

		$installed_plugins = get_plugins();

		$is_pro = array_key_exists( 'delivery-and-pickup-scheduling-pro/delivery-and-pickup-scheduling.php', $installed_plugins );

		if ( $is_pro ) {
			return;
		}

		$days_since_installed = $this->get_days_since_installed();

		// Show notice after 1 month and a week.
		if ( $days_since_installed < 35 ) {
			return;
		}

		$content = array(
			'title' => __( 'Upgrade to Chwazi - Delivery & Pickup Scheduling PRO!', 'delivery-and-pickup-scheduling-for-woocommerce' ),
			'body'  => __( 'Elevate your store 🚀 Checkout the PRO version of Chwazi.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
			'link'  => 'https://chwazidatetime.com',
		);

		$this->create_notice_markup( 'initial_pro_launch_notice', $content );
	}


	/**
	 * Create Google Add-On release notice.
	 *
	 * @return void
	 * @since 1.2.5
	 */
	public function GoogleAddonNotice() {

		$days_since_installed = $this->get_days_since_installed();

		// Show notice after 1 day of installation.
		if ( $days_since_installed < 1 ) {
			return;
		}

		$content = array(
			'title' => __( 'Schedule Orders in Google Calendar', 'delivery-and-pickup-scheduling-for-woocommerce' ),
			'body'  => __( 'Stay on track with your delivery and pickup orders by having them automatically scheduled in your Google Calendar.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
			'link'  => 'https://chwazidatetime.com/google-calendar-add-on/?utm_source=banner&utm_medium=chwazi-notice&utm_campaign=addon-upsell',
		);

		$this->create_notice_markup( 'chwazi_gcalendar_addon_launch_notice', $content );
	}
}
