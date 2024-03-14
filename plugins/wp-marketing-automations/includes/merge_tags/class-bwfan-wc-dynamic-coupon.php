<?php

class BWFAN_WC_Dynamic_Coupon extends BWFAN_Merge_Tag {

	private static $instance = null;
	protected $support_v2 = true;
	protected $support_v1 = false;


	public function __construct() {
		$this->tag_name        = 'wc_dynamic_coupon';
		$this->tag_description = __( 'WC Dynamic Coupon ', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_wc_dynamic_coupon', array( $this, 'parse_shortcode' ) );

		/** for getting the automation coupon step ids and their titles */
		add_action( 'wp_ajax_bwfan_get_automation_wc_dynamic_coupon', array( $this, 'bwfan_get_automation_wc_dynamic_coupon' ) );
		$this->priority = 25;
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

		$step_id    = isset( $attr['id'] ) ? $attr['id'] : 0;
		$contact_id = BWFAN_Merge_Tag_Loader::get_data( 'cid' );

		$contact_id = empty( $contact_id ) ? BWFAN_Merge_Tag_Loader::get_data( 'contact_id' ) : $contact_id;

		$automation_id = BWFAN_Merge_Tag_Loader::get_data( 'automation_id' );

		/** return if either contact_id or automation_id empty */
		if ( empty( $contact_id ) || empty( $automation_id ) ) {
			return $this->parse_shortcode_output( '', $attr );
		}

		$automation_contact_data = BWFAN_Model_Automation_Contact::get_automation_contact( $automation_id, $contact_id );
		$column_data             = json_decode( $automation_contact_data['data'], true );

		/** check for coupons */
		if ( ! array_key_exists( 'coupons', $column_data ) || ! isset( $column_data['coupons'] ) ) {
			return $this->parse_shortcode_output( '', $attr );
		}

		/** get step id from column data (coupons) in case empty */
		if ( empty( $step_id ) && isset( $column_data['coupons'] ) ) {
			$step_id = array_key_first( $column_data['coupons'] );
		}

		/** check for step id */
		if ( ! isset( $column_data['coupons'][ $step_id ] ) ) {
			return $this->parse_shortcode_output( '', $attr );
		}

		$coupon_code = $column_data['coupons'][ $step_id ];

		return $this->parse_shortcode_output( $coupon_code, $attr );
	}

	/**
	 * will return the title of create coupon action in single automation
	 */
	public function bwfan_get_automation_wc_dynamic_coupon() {

		$finalarr     = [];
		$automationId = absint( sanitize_text_field( $_POST['automationId'] ) );
		$merge_tag    = isset( $_POST['merge_tag'] ) ? sanitize_text_field( $_POST['merge_tag'] ) : '';

		/** check for automation id */
		if ( empty( $automationId ) ) {
			wp_send_json( array(
				'results' => $finalarr
			) );
			exit;
		}
		global $wpdb;

		/** To get automation step with action create coupon and stataus is 1 */
		$query   = "SELECT * FROM {$wpdb->prefix}bwfan_automation_step WHERE `aid` = {$automationId} AND `action` LIKE '%wc_create_coupon%' AND `status` = '1'";
		$results = $wpdb->get_results( $query, ARRAY_A );

		/** Check for empty step */
		if ( empty( $results ) ) {
			wp_send_json( array(
				'results' => $finalarr,
			) );
			exit;
		}

		/** @var  $automation_obj */
		$automation_obj = BWFAN_Automation_V2::get_instance( $automationId );

		/** Get automation meta data */
		$automation_data = $automation_obj->get_automation_meta_data();
		$mapped_arr      = [];

		/** Form  mapped array with step id and node id */
		foreach ( $automation_data['steps'] as $step ) {
			if ( isset( $step['stepId'] ) ) {
				$mapped_arr[ $step['stepId'] ] = $step['id'];
			}
		}

		/** Iterating over resulting steps */
		foreach ( $results as $data ) {
			$stepid    = $data['ID'];
			$step_data = ( array ) json_decode( $data['data'], true );

			/** Checking for title in coupon sidebar data */
			if ( isset( $step_data['sidebarData'] ) && isset( $step_data['sidebarData']['coupon_data'] ) && isset( $step_data['sidebarData']['coupon_data']['general'] ) && isset( $step_data['sidebarData']['coupon_data']['general']['title'] ) && ! empty( $step_data['sidebarData']['coupon_data']['general']['title'] ) ) {
				$coupon_title = $step_data['sidebarData']['coupon_data']['general']['title'] . ' ( #' . ( ! empty( $mapped_arr ) && isset( $mapped_arr[ $stepid ] ) ? $mapped_arr[ $stepid ] : $stepid ) . ' )';
			} else {
				$coupon_title = '#' . ( ! empty( $mapped_arr ) && isset( $mapped_arr[ $stepid ] ) ? $mapped_arr[ $stepid ] : $stepid );
			}

			if ( ! empty( $merge_tag ) ) {
				$finalarr[] = [
					'key'   => '{{wc_dynamic_coupon id="' . $stepid . '"}}',
					'value' => $coupon_title,
				];
			} else {
				$finalarr[] = [
					'key'   => $stepid,
					'value' => $coupon_title,
				];
			}
		}

		wp_send_json( array(
			'results' => $finalarr
		) );
		exit;
	}

	/**
	 * Show dummy value of the current merge tag.
	 *
	 * @return string
	 */
	public function get_dummy_preview() {
		return 'Dynamic coupon';
	}

	/**
	 * Return mergetag schema
	 *
	 * @return array[]
	 */
	public function get_setting_schema() {
		return [
			[
				'id'          => 'id',
				'type'        => 'ajax',
				'label'       => __( 'Step ID', 'wp-marketing-automations' ),
				"class"       => 'bwfan-input-wrapper',
				"required"    => true,
				'placeholder' => 'Select',
				"description" => "",
				"ajax_cb"     => 'bwfan_get_automation_wc_dynamic_coupon',
			],
		];
	}


}

/**
 * Register this merge tag to a group.
 */
if ( function_exists( 'bwfan_is_woocommerce_active' ) && bwfan_is_woocommerce_active() ) {
	BWFAN_Merge_Tag_Loader::register( 'bwf_contact', 'BWFAN_WC_Dynamic_Coupon', null, 'Contact' );
}

