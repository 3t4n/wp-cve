<?php
/**
 * Class WC_Cardpay_Authnet_Credit_Cards legacy file.
 *
 * @package Authorize.Net CIM for WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WC_Cardpay_Authnet_Credit_Cards
 */
class WC_Cardpay_Authnet_Credit_Cards {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'woocommerce_after_my_account', array( $this, 'render_credit_cards' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'card_scripts' ) );
		add_action( 'wp_ajax_delete_card', array( $this, 'delete_card' ) );
		add_action( 'wp_ajax_add_update_card', array( $this, 'add_update_card' ) );
	}

	/**
	 * Display saved cards
	 */
	public function render_credit_cards() {
		$gateway = new WC_Cardpay_Authnet_Gateway();
		if ( ! is_user_logged_in() | 'no' === $gateway->enabled | 'no' === $gateway->cim_enabled ) {
			return;
		}

		$cards = $this->get_saved_cards();
		wc_get_template( 'credit-cards.php', array( 'cards' => $cards ), 'woocommerce-cardpay-authnet/', WC_CARDPAY_AUTHNET_TEMPLATE_PATH );
	}

	/**
	 * Load scripts
	 */
	public function card_scripts() {
		wp_enqueue_script( 'cardpay-authnet', WC_CARDPAY_AUTHNET_PLUGIN_URL . '/assets/js/cardpay-authnet.js', array(), '1.0', true );
		wp_localize_script( 'cardpay-authnet', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( 'wc-credit-card-form' );
	}

	/**
	 * Add_update_card function.
	 *
	 * @return void
	 */
	public function add_update_card() {
		$nonce      = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : '';
		$permission = wp_verify_nonce( $nonce, 'add_card_nonce' );
		if ( false === $permission ) {
			echo 'error';
		} else {
			$gateway  = new WC_Cardpay_Authnet_Gateway();
			$authnet  = new WC_Cardpay_Authnet_API();
			$response = $authnet->create_profile( $gateway );
			if ( isset( $response->customerProfileId ) && ! empty( $response->customerProfileId ) ) {
				$card_raw       = isset( $_POST['authnet-card-number'] ) ? sanitize_text_field( wp_unslash( $_POST['authnet-card-number'] ) ) : '';
				$card_number    = str_replace( ' ', '', $card_raw );
				$card_type      = $authnet->get_card_type( $card_number );
				$exp_raw        = isset( $_POST['authnet-card-expiry'] ) ? sanitize_text_field( wp_unslash( $_POST['authnet-card-expiry'] ) ) : '';
				$exp_date_array = explode( '/', $exp_raw );
				$exp_month      = trim( $exp_date_array[0] );
				$exp_year       = trim( $exp_date_array[1] );
				$exp_date       = $exp_month . substr( $exp_year, -2 );
				$current_cards  = count( $this->get_saved_cards() );
				$make_default   = isset( $_POST['authnet-make-default'] ) || ! $current_cards;
				if ( $make_default ) {
					$this->clear_default();
				}
				$new_card = empty( $_POST['authnet-card-id'] );
				if ( $new_card ) {
					$card      = array(
						'post_type'     => 'authnet_credit_card',
						/* translators: 1: token value, 2: expiration date */
						'post_title'    => sprintf( __( 'Token %1$s &ndash; %2$s', 'woocommerce-cardpay-authnet' ), $response->customerPaymentProfileIdList[0], strftime( _x( '%1$b %2$d, %Y @ %I:%M %p', 'Token date parsed by strftime', 'woocommerce-cardpay-authnet' ) ) ),
						'post_content'  => '',
						'post_status'   => 'publish',
						'ping_status'   => 'closed',
						'post_author'   => get_current_user_id(),
						'post_password' => uniqid( 'card_' ),
						'post_category' => '',
					);
					$post_id   = wp_insert_post( $card );
					$card_meta = array(
						'customer_id' => $response->customerProfileId,
						'payment_id'  => $response->customerPaymentProfileIdList[0],
						'cc_last4'    => substr( $card_number, -4 ),
						'expiry'      => $exp_date,
						'cardtype'    => $card_type,
						'is_default'  => $make_default ? 'yes' : 'no',
					);
					add_post_meta( $post_id, '_authnet_card', $card_meta );
				} else {
					$card_id   = sanitize_text_field( wp_unslash( $_POST['authnet-card-id'] ) );
					$card      = get_post( $card_id );
					$card_meta = get_post_meta( $card->ID, '_authnet_card', true );
					if ( 'yes' === $card_meta['is_default'] ) {
						$current_default = true;
					} else {
						$current_default = false;
					}
					/* translators: 1: token value, 2: expiration date */
					$card->post_title = sprintf( __( 'Token %1$s &ndash; %2$s', 'woocommerce-cardpay-authnet' ), $response->customerPaymentProfileIdList[0], strftime( _x( '%1$b %2$d, %Y @ %I:%M %p', 'Token date parsed by strftime', 'woocommerce-cardpay-authnet' ) ) );
					wp_update_post( $card );
					$new_card_meta = array(
						'customer_id' => $response->customerProfileId,
						'payment_id'  => $response->customerPaymentProfileIdList[0],
						'cc_last4'    => substr( $card_number, -4 ),
						'expiry'      => $exp_date,
						'cardtype'    => $card_type,
						'is_default'  => $current_default || $make_default ? 'yes' : 'no',
					);
					update_post_meta( $card_id, '_authnet_card', $new_card_meta );
				}
				$cards = $this->get_saved_cards();
				echo wp_kses_post( wc_get_template( 'credit-cards-table.php', array( 'cards' => $cards ), 'woocommerce-cardpay-authnet/', WC_CARDPAY_AUTHNET_TEMPLATE_PATH ) );
			} else {
				echo 'error';
			}
		}
		die();
	}

	/**
	 * Delete_card function.
	 *
	 * @return void
	 */
	public function delete_card() {
		$permission = check_ajax_referer( 'delete_card_nonce', 'nonce', false );
		if ( false === $permission ) {
			echo 'error';
		} else {
			$request_id = isset( $_REQUEST['id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) : '';
			wp_delete_post( $request_id );
			echo 'success';
		}
		die();
	}

	/**
	 * Clear_default function.
	 *
	 * @return void
	 */
	public function clear_default() {
		$cards = $this->get_saved_cards();
		foreach ( $cards as $card ) {
			$card_meta               = get_post_meta( $card->ID, '_authnet_card', true );
			$card_meta['is_default'] = 'no';
			update_post_meta( $card->ID, '_authnet_card', $card_meta );
		}
	}

	/**
	 * Get_saved_cards function.
	 *
	 * @access private
	 * @return array
	 */
	private function get_saved_cards() {
		$args  = array(
			'post_type' => 'authnet_credit_card',
			'author'    => get_current_user_id(),
			'orderby'   => 'post_date',
			'order'     => 'ASC',
		);
		$cards = get_posts( $args );
		return $cards;
	}
}
new WC_Cardpay_Authnet_Credit_Cards();
