<?php

class BWFAN_API_Get_Event_Data extends BWFAN_API_Base {
	public static $ins;
	public $total_count = 0;

	public function __construct() {
		parent::__construct();
		$this->method       = WP_REST_Server::READABLE;
		$this->route        = '/event/';
		$this->request_args = array(
			'source'     => array(
				'description' => __( 'Source for get actions.', 'wp-marketing-automations-crm' ),
				'type'        => 'string',
			),
			'event_slug' => array(
				'description' => __( 'Event slug for get event\'s data', 'wp-marketing-automations-crm' ),
				'type'        => 'string',
			)
		);
	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function process_api_call() {
		$source     = ! empty( $this->get_sanitized_arg( 'source', 'key' ) ) ? $this->get_sanitized_arg( 'source', 'text_field' ) : '';
		$event_slug = ! empty( $this->get_sanitized_arg( 'event_slug', 'key' ) ) ? $this->get_sanitized_arg( 'event_slug', 'text_field' ) : '';
		if ( empty( $source ) || empty( $event_slug ) ) {
			return $this->error_response( __( 'Required parameter is missing', 'wp-marketing-automations' ), null, 500 );
		}
		$actions = BWFAN_Core()->automations->get_all_actions();
		if ( ! isset( $actions[ $source ]['actions'] ) ) {
			return $this->error_response( __( 'Action not found for this source ' . $source, 'wp-marketing-automations' ), null, 500 );
		}

		$event = BWFAN_Core()->sources->get_event( $event_slug );
		if ( empty( $event ) ) {
			return $this->error_response( __( 'Event not exist', 'wp-marketing-automations' ), null, 500 );
		}

		$data            = [];
		$data['actions'] = $actions[ $source ]['actions'];

		/**
		 *  Get Event's rules
		 * @var BWFAN_EVENT
		 **/

		$event_rule_groups = $event->get_rule_group();
		$all_rules_group   = BWFAN_Core()->rules->get_all_groups();
		$all_rules         = apply_filters( 'bwfan_rule_get_rule_types', array() );

		$rules = [];
		foreach ( $event_rule_groups as $rule_group ) {
			if ( isset( $all_rules_group[ $rule_group ] ) ) {
				$rules[ $rule_group ] = $all_rules_group[ $rule_group ];
			}
			if ( isset( $all_rules[ $rule_group ] ) ) {
				$rules[ $rule_group ]['rules'] = $all_rules[ $rule_group ];
			}
			if ( ! isset( $rules[ $rule_group ]['rules'] ) ) {
				unset( $rules[ $rule_group ] );
			}
		}

		$data['rules'] = $rules;

		/**
		 * Get Event's all merge_tags
		 **/

		$all_merge_tags         = BWFAN_Core()->merge_tags->get_all_merge_tags();
		$event_merge_tag_groups = $event->get_merge_tag_groups();

		$mergetags = [];
		foreach ( $event_merge_tag_groups as $merge_tag_group ) {
			if ( isset( $all_merge_tags[ $merge_tag_group ] ) ) {
				$tag_data = array_map( function ( $tags ) {
					return [
						'tag_name'        => $tags->get_name(),
						'tag_description' => $tags->get_description()
					];
				}, $all_merge_tags[ $merge_tag_group ] );

				$mergetags[ $merge_tag_group ] = array_replace( $mergetags, $tag_data );
			}
		}
		$data['merge_tags'] = $mergetags;

		return $this->success_response( $data, __( 'Events found', 'wp-marketing-automations' ) );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Get_Event_Data' );
