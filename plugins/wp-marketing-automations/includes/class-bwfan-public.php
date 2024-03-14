<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * BWFAN_Public class
 */
#[AllowDynamicProperties]
class BWFAN_Public {

	private static $ins = null;
	public $event_data = [];

	/**
	 * Store active automations by event slug
	 */
	public $active_automations = [];
	public $active_v2_automations = [];

	public function __construct() {
		/**
		 * Enqueue scripts
		 */
		add_action( 'wp_head', array( $this, 'enqueue_assets' ), 99 );
	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	public function enqueue_assets() {
		$setting_data           = BWFAN_Common::get_global_settings();
		$unsubscribe_page_check = false;
		if ( isset( $setting_data['bwfan_unsubscribe_page'] ) && ! empty( $setting_data['bwfan_unsubscribe_page'] ) ) {
			$unsubscribe_page_check = is_page( absint( $setting_data['bwfan_unsubscribe_page'] ) );
		}

		if ( ( false === bwfan_is_woocommerce_active() || ! is_checkout() ) && ! $unsubscribe_page_check ) {
			return;
		}

		if ( false === apply_filters( 'bwfan_public_scripts_include', true ) ) {
			return;
		}

		$data = [];

		$data['bwfan_checkout_js_data'] = 'no';
		$data['bwfan_no_thanks']        = __( 'No Thanks', 'woofunnels-autonami-automation-abandoned-cart' );
		$data['is_user_loggedin']       = 0;
		$data['ajax_url']               = admin_url( 'admin-ajax.php' );
		$data['wc_ajax_url']            = class_exists( 'WC_AJAX' ) ? WC_AJAX::get_endpoint( '%%endpoint%%' ) : '';
		$data['ajax_nonce']             = wp_create_nonce( 'bwfan-action-admin' );
		$data['message_no_contact']     = __( 'Sorry! We are unable to update preferences as no contact found.', 'wp-marketing-automations' );

		global $post;
		$data['current_page_id'] = ( $post instanceof WP_Post ) ? $post->ID : 0;

		$custom_field = [];
		if ( class_exists( 'WFACP_Common' ) ) {
			/** FunnelKit Checkout advanced fields */
			$checkout_fields = ( $post instanceof WP_Post ) ? WFACP_Common::get_checkout_fields( $post->ID ) : [];
			$custom_field    = isset( $checkout_fields['advanced'] ) ? array_filter( $checkout_fields['advanced'], function ( $fields ) {
				return 'wfacp_html' !== $fields['type'];
			} ) : [];
			$custom_field    = array_column( $custom_field, 'id' );
		}
		$data['bwfan_custom_checkout_field'] = $custom_field;

		$data['bwfan_ab_email_consent']         = '';
		$data['bwfan_ab_email_consent_message'] = '';
		$data['bwfan_ab_enable']                = '';

		if ( ! empty( $setting_data['bwfan_ab_enable'] ) ) {
			$data['bwfan_ab_enable'] = $setting_data['bwfan_ab_enable'];
			$checkout_data           = class_exists( 'WC' ) ? WC()->session->get( 'bwfan_data_for_js' ) : '';
			if ( ! empty( $checkout_data ) ) {
				$data['bwfan_checkout_js_data'] = $checkout_data;
			}
		}

		if ( isset( $setting_data['bwfan_ab_email_consent'] ) ) {
			$data['bwfan_ab_email_consent'] = $setting_data['bwfan_ab_email_consent'];
		}

		if ( isset( $setting_data['bwfan_ab_email_consent_message'] ) ) {
			$data['bwfan_ab_email_consent_message'] = $setting_data['bwfan_ab_email_consent_message'];
		}

		if ( is_user_logged_in() ) {
			$data['is_user_loggedin'] = 1;
			$user                     = wp_get_current_user();
			$bwf_contact              = bwf_get_contact( $user->ID, $user->user_email );
			$data['marketing_status'] = 0;
			if ( isset( $bwf_contact->meta->marketing_status ) ) {
				$data['marketing_status'] = 1;
			}
		}

		$site_language               = BWFAN_Common::get_site_current_language();
		$data['bwfan_site_language'] = ! empty( $site_language ) ? $site_language : get_locale();

		$data = apply_filters( 'bwfan_external_checkout_custom_data', $data );

		/**
		 * Bind on checkout page and when woocommerce active
		 */
		if ( bwfan_is_woocommerce_active() && is_checkout() ) {
			wp_enqueue_style( 'bwfan-public', BWFAN_PLUGIN_URL . '/assets/css/bwfan-public.min.css', array(), BWFAN_VERSION_DEV );
		}

		wp_enqueue_script( 'bwfan-public', BWFAN_PLUGIN_URL . '/assets/js/bwfan-public.js', array( 'jquery' ), BWFAN_VERSION_DEV, true );
		wp_localize_script( 'bwfan-public', 'bwfanParamspublic', $data );
	}

	/**
	 * Load all the active automations so that there event function can be registered
	 *
	 * @param $event_slug
	 */
	public function load_active_automations( $event_slug ) {
		if ( empty( $event_slug ) ) {
			return;
		}

		/** Get automations for a event slug if found in cache */
		if ( isset( $this->active_automations[ $event_slug ] ) ) {
			$events_data = $this->active_automations[ $event_slug ];
			if ( isset( $events_data[ $event_slug ] ) && count( $events_data[ $event_slug ] ) > 0 ) {
				$this->set_automation_event_data( $events_data );
			}

			return;
		}

		/** Get all active automations */
		$automations = BWFAN_Core()->automations->get_active_automations( 1, $event_slug );

		if ( empty( $automations ) ) {
			/** No active automations */
			$this->active_automations[ $event_slug ] = [];

			return;
		}

		$events_data = [];
		foreach ( $automations as $automation_id => $automation ) {
			$meta = $automation['meta'];
			unset( $automation['meta'] );
			$merge_data                                   = array_merge( $automation, $meta );
			$events_data[ $event_slug ][ $automation_id ] = $merge_data;
		}

		/** Set cache of found event automations for a event slug */
		$this->active_automations[ $event_slug ] = $events_data;

		if ( isset( $events_data[ $event_slug ] ) && count( $events_data[ $event_slug ] ) > 0 ) {
			$this->set_automation_event_data( $events_data );
		}

	}

	/**
	 * Load all the active automations so that there event function can be registered
	 *
	 * @param $event_slug
	 */
	public function load_active_v2_automations( $event_slug ) {
		if ( empty( $event_slug ) ) {
			return;
		}

		/** Get automations for a event slug if found in cache */
		if ( isset( $this->active_v2_automations[ $event_slug ] ) ) {
			$events_data = $this->active_v2_automations[ $event_slug ];
			if ( isset( $events_data[ $event_slug ] ) && count( $events_data[ $event_slug ] ) > 0 ) {
				$this->set_automation_event_data( $events_data, 2 );
			}

			return;
		}

		/** Get all active automations */
		$automations = BWFAN_Core()->automations->get_active_automations( 2, $event_slug );

		if ( empty( $automations ) ) {
			/** No active automations */
			$this->active_v2_automations[ $event_slug ] = [];

			return;
		}

		$events_data = [];
		foreach ( $automations as $automation_id => $automation ) {
			$meta = $automation['meta'];
			unset( $automation['meta'] );
			$merge_data                                   = array_merge( $automation, $meta );
			$events_data[ $event_slug ][ $automation_id ] = $merge_data;
		}

		/** Set cache of found event automations for a event slug */
		$this->active_v2_automations[ $event_slug ] = $events_data;

		if ( isset( $events_data[ $event_slug ] ) && count( $events_data[ $event_slug ] ) > 0 ) {
			$this->set_automation_event_data( $events_data, 2 );
		}

	}

	/**
	 * Register the actions for every event of every active automation
	 *
	 * @param $events_data
	 */
	private function set_automation_event_data( $events_data, $v = 1 ) {
		foreach ( $events_data as $event_slug => $event_data ) {
			/** @var $event_instance BWFAN_Event */
			$event_instance = BWFAN_Core()->sources->get_event( $event_slug );
			$source         = $event_instance->get_source();

			$this->event_data[ $source ][ $event_slug ] = $event_data; // Source Slug
			$event_instance->set_automations_data( $event_data, $v );
		}
	}

}

if ( class_exists( 'BWFAN_Core' ) ) {
	BWFAN_Core::register( 'public', 'BWFAN_Public' );
}
