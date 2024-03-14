<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * V2 Automation
 */
#[AllowDynamicProperties]
class BWFAN_Automation_V2 {
	private static $ins = null;

	public $automation_id = 0;
	public $automation_data = [];
	public $automation_meta_data = [];
	public $automation_steps = [];
	public $step_type = [
		'wait'        => 1,
		'action'      => 2,
		'benchmark'   => 3,
		'conditional' => 4,
		'exit'        => 5,
		'jump'        => 6,
	];
	public $error = '';

	/**
	 * Class constructor
	 *
	 * @param int $automation_id
	 */
	public function __construct( $automation_id = 0 ) {
		if ( intval( $automation_id ) > 0 ) {
			$this->set_automation_id( $automation_id );
			$this->fetch_automationdata_by_id();
		} else {
			return new WP_Error( 'Automation Id not found' );
		}
	}

	/**
	 * Fetch automation data and set to automation_data
	 *
	 * @return void
	 */
	public function fetch_automationdata_by_id() {
		$data = BWFAN_Model_Automations_V2::get_automation( $this->automation_id );
		if ( is_null( $data ) ) {
			$this->error = 'Automation not found with provided ID';
		}
		if ( isset( $data['benchmark'] ) && ! empty( $data['benchmark'] ) ) {
			$data['benchmark'] = json_decode( $data['benchmark'] );
		}
		$this->automation_data = $data;
	}

	/**
	 * Get instance
	 *
	 * @param $id
	 *
	 * @return BWFAN_Automation_V2|WP_Error|null
	 */
	public static function get_instance( $id = 0 ) {
		if ( intval( $id ) <= 0 ) {
			return new WP_Error( 'Automation Id not found' );
		}
		if ( null === self::$ins ) {
			self::$ins = new self( $id );
		}

		return self::$ins;
	}

	/**
	 * Get automation data
	 *
	 * @return array
	 */
	public function get_automation_data() {
		return $this->automation_data;
	}

	/**
	 * Returns get API data
	 *
	 * @return array
	 */
	public function get_automation_API_data() {

		$response = [
			'status'  => false,
			'message' => '',
			'data'    => []
		];

		if ( ! empty( $this->error ) ) {
			$response['message'] = $this->error;
		} else {
			$data = [
				'eventsList'      => $this->get_event_list(),
				'actionsList'     => $this->get_action_list(),
				'goals'           => $this->get_event_list( true ),
				'data'            => $this->automation_data,
				'merge_tags'      => $this->get_merge_tags_lists(),
				'delay_variables' => $this->get_merge_tags_lists( true ),
			];
			// meta data
			$automation_meta = $this->get_automation_meta_data();
			if ( isset( $automation_meta['steps'] ) && ! empty( $automation_meta['steps'] ) ) {
				$data['steps'] = $this->get_automation_steps( $automation_meta['steps'] );
				unset( $automation_meta['steps'] );
			}
			if ( isset( $automation_meta['links'] ) && ! empty( $automation_meta['links'] ) ) {
				$data['links'] = $automation_meta['links'];
				unset( $automation_meta['links'] );
			}

			if ( isset( $automation_meta['count'] ) && ! empty( $automation_meta['count'] ) ) {
				$data['count'] = $automation_meta['count'];
				unset( $automation_meta['count'] );
			}
			if ( isset( $automation_meta['event_meta'] ) ) {
				$automation_meta['event_meta'] = BWFAN_Common::fetch_updated_data( $automation_meta['event_meta'] );
			}

			$data['meta'] = $automation_meta;

			$response['status']  = true;
			$response['message'] = 'Successfully fetched automation';
			$response['data']    = $data;
		}

		return $response;
	}

	/**
	 * Returns event data
	 *
	 * @param bool $get_goals
	 *
	 * @return array[]
	 */
	public function get_event_list( $get_goals = false ) {
		$eventList = [
			'group'    => BWFAN_Core()->sources->get_event_groups(),
			'list'     => [],
			'subgroup' => [],
		];

		$eventList['list']     = BWFAN_Load_Sources::get_api_event_list_data( $get_goals );
		$eventList['subgroup'] = $get_goals ? BWFAN_Load_Sources::get_goal_subgroup() : BWFAN_Core()->sources->get_event_subgroups();

		$eventList['subgroup_priority'] = BWFAN_Core()->sources->get_event_subgroup_priority();

		if ( $get_goals ) {
			$eventList['group'] = array_values( $eventList['group'] );

			return $eventList;
		}

		$dynamic_events = BWFAN_Core()->sources->get_dynamic_event_data();

		if ( isset( $dynamic_events['list'] ) && ! empty( $dynamic_events['list'] ) ) {
			$eventList['list'] = array_merge( $dynamic_events['list'], $eventList['list'] );
		}
		if ( isset( $dynamic_events['group'] ) && ! empty( $dynamic_events['group'] ) ) {
			$eventList['group'] = $this->merge_group_data( $eventList['group'], $dynamic_events['group'] );
		}
		if ( isset( $dynamic_events['subgroup'] ) && ! empty( $dynamic_events['subgroup'] ) ) {
			$eventList['subgroup'] = $this->merge_subgroup_data( $eventList['subgroup'], $dynamic_events['subgroup'] );
		}

		if ( isset( $dynamic_events['subgroup_priority'] ) && ! empty( $dynamic_events['subgroup_priority'] ) ) {
			$eventList['subgroup_priority'] = array_merge( $dynamic_events['subgroup_priority'], $eventList['subgroup_priority'] );
		}


		return $eventList;
	}

	public function merge_group_data( $main_groups, $dynamic_groups ) {
		foreach ( $dynamic_groups as $key => $value ) {
			if ( isset( $main_groups[ $key ] ) ) {
				if ( isset( $main_groups[ $key ]['subgroup'] ) && isset( $value['subgroup'] ) && ! empty( $value['subgroup'] ) ) {
					$main_groups[ $key ]['subgroup'] = array_unique( array_values( array_merge( $main_groups[ $key ]['subgroup'], $value['subgroup'] ) ) );
				}
			} else {
				$main_groups[ $key ] = $value;
			}
		}

		return array_values( $main_groups );
	}

	public function merge_subgroup_data( $main_sub_groups, $dynamic_sub_groups ) {
		foreach ( $dynamic_sub_groups as $key => $value ) {
			if ( isset( $main_sub_groups[ $key ] ) ) {
				foreach ( $value as $subkey => $data ) {
					if ( isset( $main_sub_groups[ $key ][ $subkey ] ) ) {
						$main_sub_groups[ $key ][ $subkey ] = array_unique( array_merge( $main_sub_groups[ $key ][ $subkey ], $data ) );
					} else {
						$main_sub_groups[ $key ][ $subkey ] = $data;
					}
				}
			} else {
				$main_sub_groups[ $key ] = $value;
			}
		}

		return $main_sub_groups;
	}

	/**
	 * Returns available action for event
	 *
	 * @return array[]
	 */
	public function get_action_list() {
		$automationdata  = $this->automation_data;
		$actionList      = [
			'group'    => BWFAN_Core()->integration->get_integration_group(),
			'list'     => BWFAN_Core()->integration::get_available_actions( isset( $automationdata['event'] ) ? $automationdata['event'] : '' ),
			'subgroup' => BWFAN_Core()->integration->get_integration_subgroups(),
		];
		$dynamic_actions = BWFAN_Core()->integration->get_dynamic_action_data();

		$actionList['list']     = ! empty( $dynamic_actions['list'] ) ? array_merge( $dynamic_actions['list'], $actionList['list'] ) : $actionList['list'];
		$actionList['group']    = ! empty( $dynamic_actions['group'] ) ? $this->merge_group_data( $actionList['group'], $dynamic_actions['group'] ) : array_values( $actionList['group'] );
		$actionList['subgroup'] = ! empty( $dynamic_actions['subgroup'] ) ? $this->merge_subgroup_data( $actionList['subgroup'], $dynamic_actions['subgroup'] ) : $actionList['subgroup'];

		if ( isset( $actionList['subgroup']['wp_adv']['WordPress'] ) && in_array( 'wp_http_post', $actionList['subgroup']['wp_adv']['WordPress'] ) ) {
			$actionList['subgroup']['wp_adv']['WordPress'] = array_diff( $actionList['subgroup']['wp_adv']['WordPress'], array( 'wp_http_post' ) );
		}

		return $actionList;
	}

	/**
	 * Returns merge tags for automation based on event
	 */
	public function get_merge_tags_lists( $get_delay_variables = false ) {
		$data = $this->automation_data;
		if ( empty( $data['event'] ) ) {
			return [];
		}
		$event = BWFAN_Core()->sources->get_event( $data['event'] );
		if ( is_null( $event ) ) {
			return [];
		}
		/**
		 * Get Event's all merge_tags
		 **/
		$all_merge_tags         = BWFAN_Core()->merge_tags->get_all_merge_tags();
		$event_merge_tag_groups = $event->get_merge_tag_groups();
		$mergeGroup             = BWFAN_Core()->merge_tags->get_merge_tag_groups();
		$merge_tags             = [];
		$delay_tags             = [];

		foreach ( $event_merge_tag_groups as $merge_tag_group ) {
			$field_tags          = [];
			$all_merge_tags_data = [];
			$all_delay_tags      = [];
			if ( ! isset( $all_merge_tags[ $merge_tag_group ] ) ) {
				continue;
			}

			/** creating custom contact field merge tag if present */
			if ( isset( $all_merge_tags[ $merge_tag_group ]['bwfan_contact_field'] ) ) {
				$field_merge_tags = $all_merge_tags[ $merge_tag_group ]['bwfan_contact_field'];
				$field_tags       = $this->get_contact_fields_tags( $field_merge_tags->get_priority() );
				unset( $all_merge_tags[ $merge_tag_group ]['bwfan_contact_field'] ); // unsetting so that it will not loop over again
			}

			foreach ( $all_merge_tags[ $merge_tag_group ] as $tags ) {
				if ( ! $tags->is_support_v2() ) {
					continue;
				}

				// store delay variables
				if ( true === $get_delay_variables && true === $tags->is_delay_variable() ) {
					$delay_data = [
						'tag_name'        => $tags->get_name(),
						'tag_description' => $tags->get_description(),
						'priority'        => $tags->get_priority(),
					];
					if ( method_exists( $tags, 'get_delay_setting_schema' ) ) {
						$delay_data['schema'] = $tags->get_delay_setting_schema();
					}
					if ( method_exists( $tags, 'get_default_values' ) ) {
						$delay_data['default_val'] = $tags->get_default_values();
					}

					$all_delay_tags[] = $delay_data;
				}

				$tag_data = [
					'tag_name'        => $tags->get_name(),
					'tag_description' => $tags->get_description(),
					'priority'        => $tags->get_priority(),
				];
				if ( method_exists( $tags, 'get_setting_schema' ) ) {
					$tag_data['schema'] = $tags->get_setting_schema();
				}
				if ( method_exists( $tags, 'get_default_values' ) ) {
					$tag_data['default_val'] = $tags->get_default_values();
				}

				$all_merge_tags_data[] = $tag_data;
			}

			// merging tag_data and field tags if present
			if ( $merge_tag_group === 'bwf_contact' && ! empty( $field_tags ) ) {
				$all_merge_tags_data = array_merge( $all_merge_tags_data, $field_tags );
			}

			$tag_data = array_filter( $all_merge_tags_data );
			uasort( $tag_data, function ( $a, $b ) {
				return $a['priority'] <= $b['priority'] ? - 1 : 1;
			} );

			$mergeTagGroup = isset( $mergeGroup[ $merge_tag_group ] ) ? $mergeGroup[ $merge_tag_group ] : $merge_tag_group;

			if ( ! empty( $all_delay_tags ) ) {
				$delay_data = array_filter( $all_delay_tags );
				uasort( $delay_data, function ( $a, $b ) {
					return $a['priority'] <= $b['priority'] ? - 1 : 1;
				} );

				// store delay tags
				if ( ! empty( $delay_tags[ $mergeTagGroup ] ) ) {
					$delay_tags[ $mergeTagGroup ] = array_merge( $delay_tags[ $mergeTagGroup ], $delay_data );
				} else {
					$delay_tags[ $mergeTagGroup ] = $delay_data;
				}
			} else {
				if ( ! empty( $merge_tags[ $mergeTagGroup ] ) ) {
					$merge_tags[ $mergeTagGroup ] = array_merge( $merge_tags[ $mergeTagGroup ], $tag_data );
				} else {
					$merge_tags[ $mergeTagGroup ] = $tag_data;
				}
			}
		}

		return false === $get_delay_variables ? $merge_tags : $delay_tags;
	}

	/**
	 * fetching all the contact custom fields for creating merge tag
	 *
	 * @param $priority
	 *
	 * @return array
	 */
	public function get_contact_fields_tags( $priority ) {
		$fields = BWFCRM_Fields::get_custom_fields( 1, 1, null );

		$return = [];
		foreach ( $fields as $field ) {
			if ( ! is_array( $field ) || ! isset( $field['slug'] ) ) {
				continue;
			}

			$field_group                               = 'contact_field key="' . $field['slug'] . '"';
			$return[ $field_group ]['tag_name']        = 'contact_field key="' . $field['slug'] . '"';
			$return[ $field_group ]['tag_description'] = $field['name'];
			$return[ $field_group ]['priority']        = $priority;

		}

		return $return;
	}

	/**
	 * Get Automation meta data
	 */
	public function get_automation_meta_data() {
		if ( empty( $this->automation_meta_data ) ) {
			$this->fetch_automation_metadata();
		}

		return $this->automation_meta_data;
	}

	/**
	 * Fetch automation data and set to automation_data
	 *
	 * @return void
	 */
	public function fetch_automation_metadata() {
		$this->automation_meta_data = BWFAN_Model_Automationmeta::get_automation_meta( $this->automation_id );
	}

	/**
	 * Fetch automation steps
	 *
	 * @param $steps
	 *
	 * @return array
	 */
	public function get_automation_steps( $steps ) {
		$automation_steps = array();
		if ( ! empty( $steps ) ) {
			/** Get all steps for the automation */
			$feted_data = BWFAN_Model_Automation_Step::get_all_automation_steps( $this->automation_id );

			/** Set var for orphan step */
			$all_automation_steps = [];

			/** Format automation step by step id */
			if ( ! empty( $feted_data ) ) {
				$this->automation_steps = $this->format_step_data( $feted_data['steps'] );

				/** Setting all steps id found for this automation */
				$all_automation_steps = array_keys( $this->automation_steps );
			}

			/** Set automation step data to react UI */
			foreach ( $steps as $step ) {
				if ( ! in_array( $step['id'], array( 'start', 'end' ) ) && isset( $step['stepId'] ) && intval( $step['stepId'] ) > 0 && isset( $this->automation_steps[ $step['stepId'] ] ) ) {
					$data = $this->automation_steps[ $step['stepId'] ];

					/** Unset the steps id from orphan array */
					array_splice( $all_automation_steps, array_search( $step['stepId'], $all_automation_steps ), 1 );

					/** Set step note data if found */
					if ( isset( $data['data'] ) && isset( $data['data']['note'] ) ) {
						$step['data']['note'] = $data['data']['note'];
					}

					/** If tags and list in data then fetch updated name of tags & list */
					if ( isset( $data['data'] ) && isset( $data['data']['sidebarData'] ) && isset( $data['action'] ) && isset( $data['action']['intergration'] ) && 'autonami' === $data['action']['intergration'] ) {
						$data['data']['sidebarData'] = BWFAN_Common::modify_step_admin_data( $step['type'], $data['data']['sidebarData'] );
					}

					$step['step_status'] = isset( $this->automation_steps[ $step['stepId'] ]['status'] ) ? intval( $this->automation_steps[ $step['stepId'] ]['status'] ) : 1;

					/** Check for different case */
					switch ( $step['type'] ) {
						case 'action':
							if ( isset( $data['action'] ) && ! empty( $data['action'] ) && isset( $data['action']['action'] ) ) {
								$step['data']['selected'] = $data['action']['action'];
								$action                   = BWFAN_Core()->integration->get_action( $data['action']['action'] );
								if ( ! is_null( $action ) && method_exists( $action, 'get_desc_text' ) && isset( $data['data'] ) && isset( $data['data']['sidebarData'] ) ) {
									$step['data']['desc_text'] = $action->get_desc_text( $data['data']['sidebarData'] );
								}
							}
							if ( isset( $data['action'] ) && ! empty( $data['action'] ) && isset( $data['action']['intergration'] ) ) {
								$step['data']['intergration'] = $data['action']['intergration'];
							}
							if ( isset( $data['data'] ) && isset( $data['data']['sidebarData'] ) ) {
								$step['data']['sidebarValues'] = $data['data']['sidebarData'];
							}
							break;
						case 'wait':
						case 'conditional':
							if ( isset( $data['data'] ) && isset( $data['data']['sidebarData'] ) ) {
								$step['data']['sidebarValues'] = $data['data']['sidebarData'];
							}
							break;
						case 'jump':
							if ( isset( $data['data'] ) && isset( $data['data']['sidebarData'] ) ) {
								/** If jump step is deleted */
								if ( isset( $data['data']['sidebarData']->jump_to ) && isset( $data['data']['sidebarData']->jump_to->step ) ) {
									$jump_step_id = $data['data']['sidebarData']->jump_to->step;
									$is_active    = BWFAN_Model_Automation_Step::is_step_active( $jump_step_id );
									if ( empty( $is_active ) && 'end' !== $jump_step_id ) {
										$data['data']['sidebarData']->jump_to = '';

										/** Update jump data in the database */
										BWFAN_Model_Automation_Step::update_automation_step_data( $step['stepId'], [ 'data' => wp_json_encode( $data['data'] ) ] );
									}
								}
								$step['data']['sidebarValues'] = $data['data']['sidebarData'];
							}
							break;
						case 'benchmark':
							if ( isset( $data['action'] ) && ! empty( $data['action'] ) && isset( $data['action']['benchmark'] ) ) {
								$step['data']['benchmark'] = $data['action']['benchmark'];
								$benchmark                 = BWFAN_Core()->sources->get_event( $data['action']['benchmark'] );
								if ( ! is_null( $benchmark ) && method_exists( $benchmark, 'get_desc_text' ) && isset( $data['data'] ) && isset( $data['data']['sidebarData'] ) ) {
									$step['data']['desc_text'] = $benchmark->get_desc_text( $data['data']['sidebarData'] );
								}
							}
							if ( isset( $data['action'] ) && ! empty( $data['action'] ) && isset( $data['action']['source'] ) ) {
								$step['data']['source'] = $data['action']['source'];
							}
							if ( isset( $data['data'] ) && isset( $data['data']['sidebarData'] ) ) {
								$step['data']['sidebarValues'] = $data['data']['sidebarData'];
							}
							break;
						default:
							break;
					}
				}

				$automation_steps[] = $step;
			}

			/** Check for orphan step includes */
			if ( ! empty( $all_automation_steps ) ) {
				/** Delete Orphan steps added */
				BWFAN_Model_Automation_Step::delete_automation_steps( $all_automation_steps );
			}
		}

		/** Return formatted step data for front */
		return $automation_steps;
	}

	/**
	 * Format step data while saving it to db
	 *
	 * @param $steps
	 *
	 * @return array
	 */
	public function format_step_data( $steps ) {
		if ( ! is_array( $steps ) ) {
			return [];
		}
		$result = [];

		foreach ( $steps as $step ) {
			if ( isset( $step['data'] ) && ! empty( $step['data'] ) ) {
				$step['data'] = ( array ) json_decode( $step['data'] );
			}
			if ( isset( $step['action'] ) && ! empty( $step['action'] ) ) {
				$step['action'] = ( array ) json_decode( $step['action'] );
			}
			$result[ $step['ID'] ] = $step;
		}

		return $result;
	}

	/**
	 * Get automation completed contacts count
	 *
	 * @return string|null
	 */
	public function get_complete_count() {
		$aid = $this->get_automation_id();

		return BWFAN_Model_Automation_Complete_Contact::get_complete_count( $aid );
	}

	/**
	 * Return automation id
	 *
	 * @return int
	 */
	public function get_automation_id() {
		return $this->automation_id;
	}

	/**
	 * Set automation id
	 *
	 * @param $automation_id
	 *
	 * @return void
	 */
	public function set_automation_id( $automation_id ) {
		$this->automation_id = $automation_id;
	}

	/**
	 * Get active automation contacts count
	 *
	 * @return string|null
	 */
	public function get_active_count() {
		$aid = $this->get_automation_id();

		return BWFAN_Model_Automation_Contact::get_active_count( $aid, 'active' );
	}

	/**
	 * Update Automation meta data
	 *
	 * @param $meta
	 * @param $steps
	 * @param $links
	 * @param $count
	 *
	 * @return false
	 */
	public function update_automation_meta_data( $meta, $steps, $links, $count ) {
		$response      = false;
		$meta_data_arr = [];
		/** Update Automation meta data */
		if ( ! empty( $meta ) ) {
			$meta_data = $this->get_automation_meta_data();
			$new_val   = [];
			foreach ( $meta as $key => $value ) {
				if ( is_array( $value ) ) {
					$value = maybe_serialize( $value );
				}
				if ( isset( $meta_data[ $key ] ) ) {
					$meta_data_arr[ $key ] = $value;
				} else {
					$new_val[ $key ] = $value;
				}
			}
			/** Insert new automation meta data */
			if ( ! empty( $new_val ) ) {
				$response = BWFAN_Model_Automationmeta::insert_automation_meta_data( $this->automation_id, $new_val );
			}
		}

		/** Set step data */
		if ( ! empty( $steps ) ) {
			$meta_data_arr['steps'] = maybe_serialize( $this->format_step_save_data( $steps ) );
		}

		/** Set link data */
		if ( ! empty( $links ) ) {
			$meta_data_arr['links'] = maybe_serialize( $links );
			$iteration_array        = $this->get_step_iteration_array( $steps, $links );
			if ( ! empty( $iteration_array ) ) {
				if ( $this->automation_data['start'] != $iteration_array['start'][0]['next'] ) {
					$this->update_automation_main_table( [ 'start' => $iteration_array['start'][0]['next'] ] );
				}
				$meta_data_arr['step_iteration_array'] = maybe_serialize( $iteration_array );

			}
		}

		/** Set node count */
		if ( ! empty( $count ) ) {
			$meta_data_arr['count'] = $count;
		}

		if ( empty( $meta_data_arr ) ) {
			return $response;
		}

		return BWFAN_Model_Automationmeta::update_automation_meta_values( $this->automation_id, $meta_data_arr );
	}

	/**
	 * Modify step data
	 * Save Benchmark data
	 *
	 * @param $steps
	 *
	 * @return array
	 */
	public function format_step_save_data( $steps ) {
		if ( ! is_array( $steps ) ) {
			return [];
		}
		$benchmark = [];
		$result    = [];
		foreach ( $steps as $step ) {
			if ( isset( $step['type'] ) && $step['type'] == 'benchmark' && isset( $step['data'] ) && isset( $step['data']['benchmark'] ) ) {
				$benchmark[ $step['stepId'] ] = $step['data']['benchmark'];
			}
			if ( isset( $step['type'] ) && $step['type'] != 'yesNoNode' && isset( $step['data'] ) && ! empty( $step['data'] ) ) {
				$step['data'] = [];
			}
			$result[] = $step;
		}

		$this->update_automation_main_table( [
			'benchmark' => ! empty( $benchmark ) ? wp_json_encode( $benchmark ) : ''
		] );

		return $result;
	}

	/**
	 * Update Automation main table data
	 *
	 * @param $data
	 *
	 * @return false|void
	 */
	public function update_automation_main_table( $data ) {
		$response = false;

		if ( empty( $data ) ) {
			return $response;
		}

		/** Update Automation data in main table */
		return BWFAN_Model_Automations_V2::update_automation( $this->automation_id, $data );
	}

	/**
	 * Get Iteration array
	 *
	 * @param $steps
	 * @param $links
	 *
	 * @return array
	 */
	public function get_step_iteration_array( $steps, $links ) {
		$automationSteps = [];
		foreach ( $steps as $step ) {
			if ( 'yesNoNode' === $step['type'] ) {
				$step['stepId'] = $step['id'];
			}

			$automationSteps[ $step['id'] ] = $step;
		}

		$link_data = [];
		foreach ( $links as $link ) {
			if ( empty( $link ) ) {
				continue;
			}
			$source = $link['source'] != 'start' ? $automationSteps[ $link['source'] ]['stepId'] : $link['source'];
			$target = $link['target'] != 'end' ? $automationSteps[ $link['target'] ]['stepId'] : $link['target'];
			if ( $automationSteps[ $link['target'] ]['type'] == 'yesNoNode' ) {
				$index                          = strpos( $target, 'yes' ) !== false ? 'yes' : 'no';
				$link_data[ $source ][ $index ] = array(
					'next' => $target,
					'type' => $automationSteps[ $link['target'] ]['type'],
				);
			} else {
				$link_data[ $source ][] = array(
					'next' => $target,
					'type' => $automationSteps[ $link['target'] ]['type'],
				);
			}
		}

		return $link_data;
	}

	/**
	 * Add new automation step
	 *
	 * @param $type
	 * @param $api_data
	 *
	 * @return int
	 */
	public function add_new_automation_step( $type, $api_data = [] ) {
		$time = current_time( 'mysql', 1 );
		$data = [
			'aid'        => $this->automation_id,
			'type'       => isset( $this->step_type[ $type ] ) ? $this->step_type[ $type ] : 2,
			'created_at' => $time,
			'updated_at' => $time,
			'status'     => isset( $api_data['status'] ) ? $api_data['status'] : 0,
		];

		/** Add data on moving and copying step */
		if ( ! empty( $api_data ) ) {
			$action = [];
			$meta   = [];

			/** Set note data */
			if ( isset( $api_data['note'] ) && ! empty( $api_data['note'] ) ) {
				$meta['note'] = $api_data['note'];
			}

			/** Set side bar data */
			if ( isset( $api_data['sidebarValues'] ) && ! empty( $api_data['sidebarValues'] ) ) {
				$meta['sidebarData'] = $api_data['sidebarValues'];
			}

			/** Set action data */
			if ( $data['type'] === 2 ) {
				if ( isset( $api_data['selected'] ) && ! empty( $api_data['selected'] ) ) {
					$action['action'] = $api_data['selected'];
				}
				if ( isset( $api_data['intergration'] ) && ! empty( $api_data['intergration'] ) ) {
					$action['intergration'] = $api_data['intergration'];
				}
			}

			/** Set benchmark data */
			if ( $data['type'] === 3 ) {
				if ( isset( $api_data['benchmark'] ) && ! empty( $api_data['benchmark'] ) ) {
					$action['benchmark'] = $api_data['benchmark'];
				}
				if ( isset( $api_data['source'] ) && ! empty( $api_data['source'] ) ) {
					$action['source'] = $api_data['source'];
				}
			}

			/** Set action data if available */
			if ( ! empty( $action ) ) {
				$data['action'] = wp_json_encode( $action );
			}

			/** Set step meta data if available */
			if ( ! empty( $meta ) ) {
				$data['data'] = wp_json_encode( $meta );
			}

		}

		/** return save status */
		return BWFAN_Model_Automation_Step::create_new_automation_step( $data );
	}

	/**
	 * Update step
	 *
	 * @param $step_id
	 * @param $data
	 * @param int $update_status
	 * @param boolean $update_async
	 *
	 * @return bool
	 */
	public function update_automation_step_data( $step_id, $data, $update_status = 1, $update_async = false ) {
		if ( empty( $data ) || 0 === intval( $step_id ) ) {
			return false;
		}

		/** Get step saved data */
		$step_data = BWFAN_Model_Automation_Step::get_step_data_by_id( $step_id );
		if ( empty( $step_data ) ) {
			return false;
		}

		$data['updated_at'] = current_time( 'mysql', 1 );
		$data['aid']        = $this->automation_id;
		$data['status']     = $update_status;

		$temp_data = ! empty( $step_data['data'] ) ? json_decode( $step_data['data'], true ) : [];
		if ( isset( $data['data'] ) && is_array( $data['data'] ) ) {
			$temp_data = array_merge( $temp_data, $data['data'] );
		}

		/** Unset completed and queued data */
		if ( isset( $temp_data['completed'] ) ) {
			unset( $temp_data['completed'] );
		}

		if ( isset( $temp_data['queued'] ) ) {
			unset( $temp_data['queued'] );
		}

		$data['data'] = wp_json_encode( $temp_data );

		if ( isset( $data['action'] ) && ! empty( $data['action'] ) ) {
			$data['action'] = wp_json_encode( $data['action'] );
		}

		$response = BWFAN_Model_Automation_Step::update_automation_step_data( $step_id, $data );
		if ( $response && true === $update_async ) {
			/** Update execution time of automation if delay time changed */
			if ( 1 === absint( $step_data['type'] ) ) {
				$this->update_automation_execution_time( $step_id );
			}

			if ( 3 === absint( $step_data['type'] ) ) {
				/** Update active automation if goal step setting changed */
				$this->update_active_automations( $step_id, $data['data'] );
			}
		}

		return $response;
	}

	public function update_automation_execution_time( $step_id ) {
		$automations = BWFAN_Model_Automation_Contact::get_automation_contact_by_sid( $step_id );

		if ( empty( $automations ) ) {
			return;
		}

		$automation_contact_ids = array_column( $automations, 'ID' );
		if ( empty( $automation_contact_ids ) ) {
			return;
		}

		$key  = 'bwf_delay_automations_' . $step_id;
		$args = [ 'sid' => $step_id ];

		/** Un-schedule */
		if ( bwf_has_action_scheduled( 'bwfan_delay_step_updated', $args ) ) {
			bwf_unschedule_actions( 'bwfan_delay_step_updated', $args );
		}
		delete_option( $key );

		/** Schedule the action and data */
		bwf_schedule_recurring_action( time(), 60, 'bwfan_delay_step_updated', $args );
		update_option( $key, $automation_contact_ids, false );
	}

	public function update_active_automations( $step_id, $goal_settings ) {
		$settings               = json_decode( $goal_settings, true );
		$goal_run               = isset( $settings['sidebarData']['bwfan_goal_run'] ) ? $settings['sidebarData']['bwfan_goal_run'] : 'wait';
		$automations            = BWFAN_Model_Automation_Contact::get_automation_contact_by_sid( $step_id, 'goal' );
		$automation_contact_ids = empty( $automations ) ? [] : array_column( $automations, 'ID' );

		$key  = 'bwf_goal_automations_' . $step_id;
		$args = [ 'sid' => $step_id, 'goal_run' => $goal_run ];

		/** Un-schedule */
		if ( bwf_has_action_scheduled( 'bwfan_goal_step_updated', $args ) ) {
			bwf_unschedule_actions( 'bwfan_goal_step_updated', $args );
		}
		delete_option( $key );

		/** Schedule the action and data */
		bwf_schedule_recurring_action( time(), 60, 'bwfan_goal_step_updated', $args );
		update_option( $key, $automation_contact_ids, false );
	}

	/**
	 * Delete automation step
	 *
	 * @param $step_id
	 *
	 * @return bool
	 */
	public function delete_automation_step( $step_id ) {

		if ( ! intval( $step_id ) > 0 ) {
			return false;
		}

		return BWFAN_Model_Automation_Step::update_automation_step_data( $step_id, [ 'status' => 3 ] );
	}

	/**
	 * Change the status of action automation
	 *
	 * @param $to_status
	 * @param $id
	 *
	 * @return bool
	 */
	public function change_automation_status( $to_status, $id ) {
		if ( empty( $to_status ) ) {
			return false;
		}
		$current_time = current_time( 'timestamp', 1 );
		$data         = array(
			'status'    => $to_status,
			'last_time' => $current_time
		);

		/**If status is run now then set current time in execution time*/
		if ( 'now' === $to_status ) {
			$data['status'] = 1;
			$data['e_time'] = $current_time;
		}

		$response = BWFAN_Model_Automation_Contact::update( $data, array(
			'ID' => $id
		) );

		return $response;
	}

	public function delete_migrations( $aid ) {
		BWFAN_Model_Automation_Step::delete_steps_by_aid( $aid );
		BWFAN_Model_Automation_Contact::delete_automation_contact_by_aid( $aid );
		BWFAN_Model_Automation_Complete_Contact::delete_automation_contact_by_aid( $aid );
		BWFAN_Model_Automation_Contact_Trail::delete_automation_trail_by_id( $aid );
	}
}

BWFAN_Core::register( 'automations_v2', 'BWFAN_Automation_V2' );
