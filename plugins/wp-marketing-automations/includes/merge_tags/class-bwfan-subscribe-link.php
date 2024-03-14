<?php

class BWFAN_Contact_Subscribe_Link extends BWFAN_Merge_Tag {

	private static $instance = null;
	protected $support_v2 = true;
	protected $support_v1 = false;


	public function __construct() {
		$this->tag_name        = 'contact_subscribe_link';
		$this->tag_description = __( 'Subscribe URL', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_contact_subscribe_link', array( $this, 'parse_shortcode' ) );
		$this->priority = 23;
	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Parse the merge tag and return its value.
	 *
	 * @param $attr
	 *
	 * @return mixed|void
	 */
	public function parse_shortcode( $attr ) {
		if ( true === BWFAN_Merge_Tag_Loader::get_data( 'is_preview' ) ) {
			return $this->parse_shortcode_output( $this->get_dummy_preview(), true );
		}

		$contact_id    = BWFAN_Merge_Tag_Loader::get_data( 'cid' );
		$contact_id    = empty( $contact_id ) ? BWFAN_Merge_Tag_Loader::get_data( 'contact_id' ) : $contact_id;
		$automation_id = BWFAN_Merge_Tag_Loader::get_data( 'automation_id' );

		/** Return if either contact_id or automation_id empty */
		if ( empty( $contact_id ) || empty( $automation_id ) ) {
			return $this->parse_shortcode_output( '', $attr );
		}

		$contact = new WooFunnels_Contact( '', '', '', intval( $contact_id ) );
		if ( ! $contact instanceof WooFunnels_Contact || 0 === intval( $contact->get_id() ) ) {
			return $this->parse_shortcode_output( '', $attr );
		}

		$redirect_url = isset( $attr['redirect'] ) && ! empty( $attr['redirect'] ) ? $attr['redirect'] : '';

		$args = [
			'bwfan-action'  => 'subscribe',
			'automation-id' => absint( $automation_id ),
			'bwfan-uid'     => $contact->get_uid(),
		];

		/** Get redirect url from global setting if url is empty */
		if ( empty( $redirect_url ) ) {
			$general_options   = BWFAN_Common::get_global_settings();
			$confirmation_type = isset( $general_options['after_confirmation_type'] ) ? $general_options['after_confirmation_type'] : 'show_message';

			if ( 'show_message' === $confirmation_type ) {
				$args['show_message'] = 1;
			} else {
				$redirect_url = isset( $general_options['bwfan_confirmation_redirect_url'] ) && ! empty( $general_options['bwfan_confirmation_redirect_url'] ) ? $general_options['bwfan_confirmation_redirect_url'] : home_url( '/' );
			}
		}

		if ( ! empty( $redirect_url ) && false !== wp_http_validate_url( $redirect_url ) ) {
			$args['bwfan-link'] = $redirect_url;
		}

		return add_query_arg( $args, esc_url_raw( home_url( '/' ) ) );
	}

	/**
	 * Show dummy value of the current merge tag.
	 *
	 * @return string
	 */
	public function get_dummy_preview() {
		return '';
	}

	/**
	 * Return mergetag schema
	 *
	 * @return array[]
	 */
	public function get_setting_schema() {
		return [
			[
				'id'          => 'redirect',
				'label'       => __( 'Redirect', 'wp-marketing-automations' ),
				'type'        => 'text',
				'class'       => '',
				'placeholder' => '',
				'required'    => false,
				'toggler'     => array(),
			],
		];
	}
}

/**
 * Register this merge tag to a group.
 */
if ( ! bwfan_is_autonami_pro_active() ) {
	return;
}
BWFAN_Merge_Tag_Loader::register( 'bwf_contact', 'BWFAN_Contact_Subscribe_Link', null, 'Contact' );
