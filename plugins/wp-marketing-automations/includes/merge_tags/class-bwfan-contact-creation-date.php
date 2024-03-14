<?php

/**
 * Class BWFAN_Contact_Creation_Date
 */
class BWFAN_Contact_Creation_Date extends BWFAN_Merge_Tag {
	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		$this->tag_name        = 'contact_creation_date';
		$this->tag_description = __( 'Contact Creation Date', 'autonami-automations-pro' );
		add_shortcode( 'bwfan_contact_creation_date', array( $this, 'parse_shortcode' ) );
		$this->support_date = true;
		$this->priority     = 15;
	}

	/**
	 * Parse shortcode
	 *
	 * @param $attr
	 *
	 * @return mixed|string|void
	 */
	public function parse_shortcode( $attr ) {
		$parameters           = [];
		$parameters['format'] = isset( $attr['format'] ) ? $attr['format'] : get_option( 'date_format' );

		if ( isset( $attr['modify'] ) ) {
			$parameters['modify'] = $attr['modify'];
		}

		$parameters['format'] = apply_filters( 'bwfan_contact_date_format', $parameters['format'] );

		if ( true === BWFAN_Merge_Tag_Loader::get_data( 'is_preview' ) ) {
			return $this->parse_shortcode_output( $this->get_dummy_preview( $parameters ), $attr );
		}

		$get_data = BWFAN_Merge_Tag_Loader::get_data();

		/** If Contact ID available */
		$cid           = isset( $get_data['contact_id'] ) ? $get_data['contact_id'] : '';
		$creation_date = $this->get_creation_date( $cid );
		if ( ! empty( $creation_date ) ) {
			$creation_date = $this->format_datetime( $creation_date, $parameters );

			return $this->parse_shortcode_output( $creation_date, $attr );
		}

		/** If order */
		$order = $this->get_order_object( $get_data );
		if ( ! empty( $order ) ) {
			$cid           = BWFAN_Woocommerce_Compatibility::get_order_data( $order, '_woofunnel_cid' );
			$creation_date = $this->get_creation_date( $cid );
			if ( false !== $creation_date ) {
				$creation_date = $this->format_datetime( $creation_date, $parameters );

				return $this->parse_shortcode_output( $creation_date, $attr );
			}
		}

		/** If user ID or email */
		$user_id = isset( $get_data['user_id'] ) ? $get_data['user_id'] : '';
		$email   = isset( $get_data['email'] ) ? $get_data['email'] : '';

		/** If cart */
		if ( empty( $email ) && isset( $get_data['cart_details'] ) && ! empty( $get_data['cart_details'] ) ) {
			$data = json_decode( $get_data['cart_details']['checkout_data'], true );
			if ( isset( $data['fields'] ) && isset( $data['fields']['billing_email'] ) ) {
				$email = $data['fields']['billing_email'];
			}
		}

		$contact = bwf_get_contact( $user_id, $email );
		if ( absint( $contact->get_id() ) > 0 ) {
			$creation_date = $contact->get_creation_date();
			if ( ! empty( $creation_date ) ) {
				$creation_date = $this->format_datetime( $creation_date, $parameters );

				return $this->parse_shortcode_output( $creation_date, $attr );
			}
		}

		return $this->parse_shortcode_output( '', $attr );
	}

	/**
	 * Return dummy value
	 *
	 * @return string
	 */
	public function get_dummy_preview( $parameters ) {
		return $this->format_datetime( date( 'j M Y' ), $parameters );
	}

	/**
	 * Get contact creation date
	 *
	 * @param $cid
	 *
	 * @return bool false|string
	 */
	public function get_creation_date( $cid ) {
		$cid = absint( $cid );
		if ( 0 === $cid ) {
			return '';
		}
		$contact = new WooFunnels_Contact( '', '', '', $cid );
		if ( $contact->get_id() > 0 ) {
			return $contact->get_creation_date();
		}

		return '';
	}

	/**
	 * Return mergetag schema
	 *
	 * @return array[]
	 */
	public function get_setting_schema() {
		$formats      = $this->date_formats;
		$date_formats = [];
		foreach ( $formats as $data ) {
			if ( isset( $data['format'] ) ) {
				$date_time      = date( $data['format'] );
				$date_formats[] = [
					'value' => $data['format'],
					'label' => $date_time,
				];
			}
		}

		return [
			[
				'id'          => 'format',
				'type'        => 'select',
				'options'     => $date_formats,
				'label'       => __( 'Select Date Format', 'wp-marketing-automations' ),
				"class"       => 'bwfan-input-wrapper',
				"placeholder" => 'Select',
				"required"    => false,
				"description" => ""
			],
			[
				'id'          => 'modify',
				'label'       => __( 'Modify (Optional)', 'wp-marketing-automations' ),
				'type'        => 'text',
				'class'       => '',
				'placeholder' => 'e.g. +2 months, -1 day, +6 hours',
				'required'    => false,
				'toggler'     => array(),
			],
		];
	}
}

BWFAN_Merge_Tag_Loader::register( 'bwf_contact', 'BWFAN_Contact_Creation_date', null, 'Contact' );
