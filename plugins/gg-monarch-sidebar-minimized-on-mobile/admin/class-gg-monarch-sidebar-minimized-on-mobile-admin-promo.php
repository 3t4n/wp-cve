<?php


use MSMoMDP\Wp\Settings;
use MSMoMDP\Wp\AdminPromo;
use MSMoMDP\Std\Core\Arr;
use MSMoMDP\Wp\AdminNotice;
use MSMoMDP\Std\Html\Element;


class GG_Monarch_Sidebar_Minimized_On_Mobile_Admin_Promo {


	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		 $this->plugin_name = $plugin_name;
		$this->version      = $version;
	}

	public function add_admin_notice() {
		AdminPromo::backward_comp_add_activated_options( 'dp_msmom_basic_options' );
		$screen = get_current_screen();
		if ( ! $screen ) {
			return;
		}
		$a4r_notice_id     = GG_MONARCH_SIDEBAR_MINIMIZED_ON_MOBILE_PREFIX . '-a4r-notice';
		$itpromo_notice_id = GG_MONARCH_SIDEBAR_MINIMIZED_ON_MOBILE_PREFIX . '-intro-tour-promo-notice';
		switch ( $screen->base ) {
			case 'dashboard':
				if ( AdminPromo::is_right_time_for_ask_4_rating( 22, $a4r_notice_id, 'dp_msmom_basic_options' ) ) {
					AdminNotice::render_ask_for_rating_notice(
						3,
						GG_MONARCH_SIDEBAR_MINIMIZED_ON_MOBILE_NAME,
						GG_MONARCH_SIDEBAR_MINIMIZED_ON_MOBILE_PRODUCT_ASK_FOR_RATING_LINK_FREE,
						'gg-monarch-sidebar-minimized-on-mobile',
						$a4r_notice_id,
						null,
						'',
						'dp-msmom-notice'
					);
				} elseif ( AdminPromo::is_right_time_for_random( 14, 25, 'dp_msmom_basic_options' ) ) {
					self::render_intro_tour_promo_notice( $itpromo_notice_id );
				}
				break;
			default:
				if ( AdminPromo::is_right_time_for_ask_4_rating( 30, $a4r_notice_id, 'dp_msmom_basic_options' ) ) {
					AdminNotice::render_ask_for_rating_notice(
						4,
						GG_MONARCH_SIDEBAR_MINIMIZED_ON_MOBILE_NAME,
						GG_MONARCH_SIDEBAR_MINIMIZED_ON_MOBILE_PRODUCT_ASK_FOR_RATING_LINK_FREE,
						'gg-monarch-sidebar-minimized-on-mobile',
						$a4r_notice_id,
						null,
						'',
						'dp-msmom-notice'
					);
				} elseif ( AdminPromo::is_right_time_for_random( 20, 4, 'dp_msmom_basic_options' ) ) {
					self::render_intro_tour_promo_notice( $itpromo_notice_id );
				}
		}
	}

	public static function render_intro_tour_promo_notice( $notice_id ) {
		AdminNotice::render_notice(
			__(
				'Have you heard of our <strong>Intro Tour Tutorial Plugin</strong>? Would you like to:' .
				'<ul>' .
				'<li>Give a helping hand to visitors who come to your site for the first time?</li>' .
				'<li>Teach your visitors interactively how to use your comprehensive web site or web application?</li>' .
				'<li>Guide your co-workers interactively on how to manage functions and features on your WordPress admin board?</li>' .
				'<li>Introduce a new feature or product in a tasteful and user-friendly way?</li>' .
				'</ul>' .
				'Check <strong>FREE</strong> or <strong>PRO</strong> version at DeepPresentation.',
				'gg-monarch-sidebar-minimized-on-mobile'
			),
			'success',
			true,
			'https://deeppresentation.com/plugins/dp-intro-tours/?start-intro-tour=true',
			'button button-primary button-pro-promo',
			__( 'CHECK IT OUT', 'gg-monarch-sidebar-minimized-on-mobile' ),
			true,
			__( 'FREE or PRO', 'gg-monarch-sidebar-minimized-on-mobile' ),
			'dp-msmom-notice',
			$notice_id,
			'dp-notice-show-till-user-react'
		);
	}
}
