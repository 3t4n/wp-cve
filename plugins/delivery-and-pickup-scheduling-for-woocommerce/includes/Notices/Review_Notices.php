<?php
/**
 * Review Notices.
 *
 * Notices to review the plugin.
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

use Lpac_DPS\Notices\Notice;
use Lpac_DPS\Traits\Plugin_Info;

/**
 * Class Upsells_Notices.
 */
class Review_Notices extends Notice {

	use Plugin_Info;

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct() {
		$this->create_review_plugin_notice();
	}

	/**
	 * Create leave review for plugin notice.
	 *
	 * @return void
	 */
	public function create_review_plugin_notice() {

		$days_since_installed = $this->get_days_since_installed();

		// Show notice after 3 weeks.
		if ( $days_since_installed < 21 ) {
			return;
		}

		$content = array(
			'title' => __( 'Has Chwazi - Delivery & Pickup Scheduling Helped You?', 'delivery-and-pickup-scheduling-for-woocommerce' ),
			'body'  => __( 'Hey! its Uriahs Victor, Sole Developer working on Chwazi. Has the plugin benefited your website? If yes, then would you mind taking a few seconds to leave a kind review? Reviews go a long way and they really help keep me motivated to continue working on the plugin and making it better.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
			'cta'   => __( 'Sure!', 'delivery-and-pickup-scheduling-for-woocommerce' ),
			'link'  => 'https://wordpress.org/support/plugin/delivery-and-pickup-scheduling-for-woocommerce/reviews/#new-post',
		);

		$this->create_notice_markup( 'leave_review_notice_1', $content );
	}
}
