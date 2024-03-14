<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class WFTY_Shortcodes
 * @package WFTY
 * @author XlPlugins
 */
if ( ! class_exists( 'WFTY_Shortcodes' ) ) {
	#[AllowDynamicProperties]

  class WFTY_Shortcodes {

		public static function init() {
			add_shortcode( 'wfty_order_number', array( WFFN_Core()->thank_you_pages->data, 'get_order_id' ) );
			add_shortcode( 'wfty_customer_first_name', array( WFFN_Core()->thank_you_pages->data, 'get_customer_first_name' ) );
			add_shortcode( 'wfty_customer_last_name', array( WFFN_Core()->thank_you_pages->data, 'get_customer_last_name' ) );
			add_shortcode( 'wfty_customer_email', array( WFFN_Core()->thank_you_pages->data, 'get_customer_email' ) );
			add_shortcode( 'wfty_customer_phone_number', array( WFFN_Core()->thank_you_pages->data, 'get_customer_phone' ) );
			add_shortcode( 'wfty_customer_details', array( WFFN_Core()->thank_you_pages->data, 'get_customer_info' ) );
			add_shortcode( 'wfty_order_details', array( WFFN_Core()->thank_you_pages->data, 'get_order_details' ) );
			add_shortcode( 'wfty_order_total', array( WFFN_Core()->thank_you_pages->data, 'get_order_total' ) );
		}
	}
}


function wfty_customer_first_name() {
	return WFFN_Core()->thank_you_pages->data->get_customer_first_name();
}

function wfty_customer_last_name() {
	return WFFN_Core()->thank_you_pages->data->get_customer_last_name();
}

function wfty_customer_email() {
	return WFFN_Core()->thank_you_pages->data->get_customer_email();
}

function wfty_customer_phone_number() {
	return WFFN_Core()->thank_you_pages->data->get_customer_phone();
}

function wfty_order_number() {
	return WFFN_Core()->thank_you_pages->data->get_order_id();
}

function wfty_order_total( $args ) {
	return WFFN_Core()->thank_you_pages->data->get_order_total( $args );
}