<?php
/**
 * Nouvello WeManage Worker Chat Class
 *
 * @package    Nouvello WeManage Worker
 * @subpackage Chat
 * @author     Nouvello Studio
 * @copyright  (c) Copyright by Nouvello Studio
 * @since      1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Nouvello WeManage Worker Chat Class
 */
class Nouvello_WeManage_Worker_Chat {

	/**
	 * Constructor
	 */
	public function __construct() {
		// enqueue.
		add_action( 'wp_enqueue_scripts', array( $this, 'nouvello_chat_enqeue' ) );
	}

	/**
	 * Enqueue chat engine scripts.
	 */
	public function nouvello_chat_enqeue() {
		wp_register_script( 'nouvello-chat-engine', NSWMW_ROOT_DIR . '/includes/assets/js/nouvello-chat-engine.min.js', array( 'jquery' ), 1 );

		$localized_array = array(
			'server_url'        => WEMANAGE_SERVER_URL,
			'cn'                => get_option( 'nouvello-worker-activation-key' ),
			'url'               => site_url(),
			'post_id'           => get_the_ID(),
			'is_home'           => is_home(),
			'is_archive'        => is_archive(),
			'is_logged_in'      => is_user_logged_in(),
			'locale'            => get_locale(),
		);

		if ( class_exists( 'WooCommerce' ) ) {
			$localized_array['is_shop'] = is_shop();
			$localized_array['is_checkout'] = is_checkout();
			$localized_array['is_cart'] = is_cart();
			$localized_array['is_acount_page'] = is_account_page();
		}

		if ( is_user_logged_in() ) {
			$user_info = wp_get_current_user();
			if ( $user_info->first_name ) {
				$localized_array['first_name'] = $user_info->first_name;
			}
			if ( $user_info->last_name ) {
				$localized_array['last_name'] = $user_info->last_name;
			}
			$localized_array['avatar'] = get_avatar_url( $user_info->ID );
		}

		wp_localize_script(
			'nouvello-chat-engine',
			'nouvello_chat_engine_params',
			$localized_array
		);
		wp_enqueue_script( 'nouvello-chat-engine' );
	}

}
