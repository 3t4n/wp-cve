<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class BWFAN_Load_Integrations
 * @package Autonami
 * @author XlPlugins
 */
#[AllowDynamicProperties]
class BWFAN_Load_Integrations {
	/**
	 * Saves all the main integration's object
	 * @var array
	 */
	private static $integrations = array();
	/**
	 * Saves all the action's object
	 * @var array
	 */
	private static $available_actions = array();
	private static $integration_actions = array();

	private static $integration_localize_data = array();
	private static $integration_actions_localize_data = array();
	private static $action_localize_data = array();
	private static $ins = null;
	private static $dynamic_register_actions = [
		'list' => []
	];

	/**
	 * BWFAN_Load_Integrations constructor.
	 */
	protected function __construct() {
	}

	/**
	 * Return the object of current class
	 *
	 * @return null|BWFAN_Load_Integrations
	 */
	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	/**
	 * Register the integration when the integration file is included
	 *
	 * @param $class
	 */
	public static function register( $class ) {
		if ( class_exists( $class ) && method_exists( $class, 'get_instance' ) ) {
			$temp_integration = $class::get_instance();
			if ( $temp_integration instanceof BWFAN_Integration ) {
				$connector_slug = $temp_integration->get_connector_slug();

				/**
				 * If a connector
				 */
				if ( ! empty( $connector_slug ) ) {
					$all_connectors = WFCO_Load_Connectors::get_all_connectors();

					if ( empty( $all_connectors ) ) {
						return;
					}
					$saved_connectors = WFCO_Common::$connectors_saved_data;

					if ( empty( $saved_connectors ) ) {
						/** One time fetching from database is required */
						WFCO_Common::get_connectors_data();
						$saved_connectors = WFCO_Common::$connectors_saved_data;
					}

					if ( empty( $saved_connectors ) ) {
						/** If still no saved connectors, then return */
						return;
					}

					if ( isset( $all_connectors[ $connector_slug ] ) && ! isset( $saved_connectors[ $connector_slug ] ) ) {
						return;
					}
				}

				$slug                                     = $temp_integration->get_slug();
				self::$integrations[ $slug ]              = $temp_integration;
				self::$integration_localize_data[ $slug ] = $temp_integration->get_localize_data();
				$temp_integration->load_actions();
				$group_slug = $temp_integration->get_group_slug();
				if ( ! isset( self::$dynamic_register_actions['group'][ $group_slug ] ) ) {
					self::$dynamic_register_actions['group'][ $group_slug ] = [
						'group_slug' => $group_slug,
						'label'      => $temp_integration->get_group_name(),
						'subgroup'   => [ $slug ],
					];
				} elseif ( isset( self::$dynamic_register_actions['group'][ $group_slug ] ) && ! in_array( $slug, self::$dynamic_register_actions['group'][ $group_slug ]['subgroup'] ) ) {
					self::$dynamic_register_actions['group'][ $group_slug ]['subgroup'][] = $slug;
				}
			}
		}
	}

	/**
	 * Register every action when action file is included
	 *
	 * @param $action_obj BWFAN_Action
	 */
	public static function register_actions( BWFAN_Action $action_obj ) {
		if ( method_exists( $action_obj, 'get_instance' ) ) {
			/**
			 * @var $temp_instance BWFAN_Action;
			 */
			$slug                                                                  = $action_obj->get_slug();
			$integration_type                                                      = $action_obj->get_integration_type();
			$localized_data                                                        = $action_obj->get_localize_data();
			self::$available_actions[ $slug ]                                      = $action_obj;
			self::$integration_actions[ $integration_type ][ $slug ]               = $action_obj;
			self::$action_localize_data[ $slug ]                                   = $localized_data;
			self::$integration_actions_localize_data[ $integration_type ][ $slug ] = $localized_data;

			$integration = BWFAN_Core()->integration->get_integration( $integration_type );
			if ( $action_obj->support_v2 ) {
				self::$dynamic_register_actions['list'][ $slug ]                                               = [
					'action_name' => $action_obj->get_name(),
					'lock'        => true,
				];
				self::$dynamic_register_actions['subgroup'][ $integration_type ][ $integration->get_name() ][] = $slug;
				if ( isset( self::$dynamic_register_actions['group'][ $integration_type ] ) ) {
					self::$dynamic_register_actions['group'][ $integration_type ]['subgroup'][] = $slug;
				}
			}
		}
	}

	/**
	 * Returns all action available
	 *
	 * @return array
	 */
	public static function get_all_action_list() {
		return apply_filters( 'bwfan_all_action_list', [
			'automation_end'                       => 'End Automation',
			'crm_add_contact_note'                 => 'Add Contact Note',
			'crm_add_tag'                          => 'Add Tags',
			'crm_add_to_list'                      => 'Add Contact to List',
			'crm_change_contact_status'            => 'Change Status',
			'crm_create_contact'                   => 'Create Contact',
			'crm_rmv_from_list'                    => 'Remove Contact From List',
			'crm_rmv_tag'                          => 'Remove Tags',
			'crm_update_customfields'              => 'Update Fields',
			'wp_createuser'                        => 'Create User',
			'wp_custom_callback'                   => 'Custom Callback',
			'wp_debug'                             => 'Debug',
			'wp_http_post'                         => 'HTTP Request',
			'wp_update_user_meta'                  => 'Update User Meta',
			'wp_update_user_role'                  => 'Update User Role',
			'za_send_data'                         => 'Send Data To Zapier',
			'pro_wc_create_coupon'                 => 'Create Coupon',
			'wc_remove_coupon'                     => 'Delete Coupon',
			'wp_sendemail'                         => 'Send Email',
			'ac_add_tag'                           => 'Add Tags',
			'ac_add_to_automation'                 => 'Add Contact To Automation',
			'ac_add_to_list'                       => 'Add Contact To List',
			'ac_create_abandoned_cart'             => 'Create Abandoned Cart',
			'ac_create_contact'                    => 'Create Contact',
			'ac_rmv_from_automation'               => 'Remove Contact From Automation',
			'ac_rmv_from_list'                     => 'Remove Contact From List',
			'ac_rmv_tag'                           => 'Remove Tags',
			'ac_update_customfields'               => 'Update Fields',
			'mailchimp_add_cart'                   => 'Add Abandoned Cart',
			'mailchimp_add_tags'                   => 'Add Tags',
			'mailchimp_add_to_automation'          => 'Add Contact to Automation',
			'mailchimp_add_to_list'                => 'Add Contact to List',
			'mailchimp_remove_from_automation'     => 'Remove Contact from Automation',
			'mailchimp_remove_from_list'           => 'Remove Contact from List',
			'mailchimp_remove_tags'                => 'Remove Tags',
			'mailchimp_update_custom_fields'       => 'Update Custom (Merge) Fields',
			'hubspot_add_contact_to_list'          => 'Add Contact to List',
			'hubspot_add_contact_to_workflow'      => 'Add Contact to Workflow',
			'hubspot_create_contact'               => 'Create Contact',
			'hubspot_remove_contact_from_list'     => 'Remove Contact from List',
			'hubspot_remove_contact_from_workflow' => 'Remove Contact from Workflow',
			'hubspot_update_contact'               => 'Update Contact',
			'twilio_send_sms'                      => 'Send SMS',
			'wcm_delete_membership'                => 'Remove From Membership Plan',
			'wcm_update_plan'                      => 'Assign / Update Membership Plan',
			'gr_add_tags'                          => 'Add Tags',
			'gr_add_to_list'                       => 'Add to List',
			'gr_create_contact'                    => 'Create Contact',
			'gr_remove_tags'                       => 'Remove Tag',
			'gr_update_custom_fields'              => 'Update Custom Field',
			'gr_remove_from_list'                  => 'Remove from List',
			'ontraport_add_tags'                   => 'Add Tag',
			'ontraport_add_to_campaign'            => 'Add to Campaign',
			'ontraport_create_contact'             => 'Create Contact',
			'ontraport_rmv_from_campaign'          => 'Remove from Campaign',
			'ontraport_rmv_tag'                    => 'Remove Tag',
			'ontraport_update_contact_fields'      => 'Update Contact Field',
			'klaviyo_add_to_list'                  => 'Add to List',
			'klaviyo_remove_from_list'             => 'Remove from List',
			'klaviyo_update_profile_fields'        => 'Update Profile Field',
			'ck_add_customfields'                  => 'Add Custom Field',
			'ck_add_tags'                          => 'Add Tag',
			'ck_add_to_sequence'                   => 'Add Sequence',
			'ck_rmv_tags'                          => 'Remove Tag',
			'bulkgate_send_transactional_sms'      => 'Send SMS',
			'ac_create_order'                      => 'Create Order',
			'ac_create_deal'                       => 'Create Deal',
			'ac_create_deal_note'                  => 'Create Deal Note',
			'ac_update_deal'                       => 'Update Deal',
			'wlm_add_user_to_pay_per_post'         => 'Add User to Pay Per Post',
			'wlm_remove_user_from_pay_per_post'    => 'Remove User from Pay Per Post',
			'wlm_user_add_level'                   => 'Add User to Level',
			'wlm_user_cancel_level'                => 'Cancel User Level',
			'wlm_user_move_level'                  => 'Move User to Level',
			'wlm_user_remove_level'                => 'Remove User from Level',
			'ld_add_user_to_group'                 => 'Add User to Group',
			'ld_enroll_user_into_course'           => 'Enroll User into Course(s)',
			'ld_remove_user_from_course'           => 'Remove User from Course(s)',
			'ld_add_user_from_group'               => 'Remove User from Group',
			'ld_reset_course_progress'             => 'Reset Course Progress',
			'ld_reset_quiz_attempts'               => 'Reset Quiz Attempts',
			'gs_delete_data'                       => 'Delete Row',
			'gs_insert_data'                       => 'Insert Row',
			'gs_update_data'                       => 'Update Row',
			'sl_message'                           => 'Sends a message to a channel',
			'sl_message_user'                      => 'Sends a message to a user',
		] );
	}

	/**
	 * @param string $type
	 *
	 * Return integration object
	 *
	 * @return BWFAN_Integration|null
	 */
	public function get_integration( $type = 'wp' ) {
		return isset( self::$integrations[ $type ] ) ? self::$integrations[ $type ] : null;
	}

	/**
	 * Get available action based on event
	 */
	public static function get_available_actions( $evt = '' ) {
		if ( empty( $evt ) ) {
			return [];
		}
		$filtered_action = [];
		$all_actions     = self::$available_actions;

		foreach ( $all_actions as $action ) {
			$action_data                         = $action->get_action_data_for_api();
			$action_data['integration_nicename'] = isset( $action_data['integration_slug'] ) && isset( self::$integration_localize_data[ $action_data['integration_slug'] ] ) ? self::$integration_localize_data[ $action_data['integration_slug'] ]['nice_name'] : '';
			if ( ! empty( $action_data ) && ! empty( $action_data['integration_nicename'] ) && $action_data['support_v2'] ) {
				$include = true;
				if ( ! empty( $action_data['included_events'] ) && ! in_array( $evt, $action_data['included_events'] ) ) {
					$include = false;
				} else if ( ! empty( $action_data['excluded_events'] ) && in_array( $evt, $action_data['excluded_events'] ) ) {
					$include = false;
				}

				if ( $include ) {
					$filtered_action[ $action_data['slug'] ] = $action_data;
				}
			}
		}

		$all_actions       = self::get_all_action_list();
		$all_actions_array = [];
		foreach ( $all_actions as $action_slug => $action_label ) {
			if ( isset( $filtered_action[ $action_slug ] ) ) {
				$all_actions_array[ $action_slug ] = $filtered_action[ $action_slug ];
			} else {
				$all_actions_array[ $action_slug ] = [
					'action_name' => $action_label,
					'lock'        => true,
				];
			}
			unset( $filtered_action[ $action_slug ] );
		}

		if ( ! empty( $filtered_action ) ) {
			$all_actions_array = array_merge( $all_actions_array, $filtered_action );
		}

		return $all_actions_array;
	}

	/**
	 * Return all available action registered which register by their integration
	 * @return array
	 */
	public function get_actions( $slug = '' ) {
		return self::$available_actions;
	}

	/**
	 * Return all the integrations with instance
	 *
	 * @return array
	 */
	public function get_integrations() {
		return self::$integrations;
	}

	/**
	 * @param string $slug
	 *
	 * Return integration object
	 *
	 * @return BWFAN_Action
	 */
	public function get_action( $slug = 'wp' ) {
		return isset( self::$available_actions[ $slug ] ) ? self::$available_actions[ $slug ] : null;
	}

	public function get_integration_localize_data( $type = '' ) {
		if ( '' !== $type ) {
			return isset( self::$integration_localize_data[ $type ] ) ? self::$integration_localize_data[ $type ] : [];
		}

		return self::$integration_localize_data;
	}

	/**
	 * Array
	 * (
	 * [wc_add_order_note] => wc
	 * [wc_create_coupon] => wc
	 * [wc_remove_coupon] => wc
	 * [wp_custom_callback] => wp
	 * [wp_debug] => wp
	 * [wp_http_post] => wp
	 * [wp_sendemail] => wp
	 * )
	 * @return array
	 */
	public function get_mapped_arr_action_with_integration() {
		$actions  = self::$available_actions;
		$map_data = [];
		if ( count( $actions ) > 0 ) {
			foreach ( $actions as $action ) {
				$type              = $action->get_integration_type();
				$slug              = $action->get_slug();
				$map_data[ $slug ] = $type;

			}
		}

		return $map_data;
	}

	/**
	 *
	 *
	 * @return array
	 */
	public function get_mapped_arr_integration_name_with_action_name() {
		$integrations = self::get_all_integrations();
		$data         = [];
		if ( count( $integrations ) > 0 ) {
			/**
			 * @var $integration BWFAN_Integration
			 */
			foreach ( $integrations as $slug => $actions ) {
				$integration = $this->get_integration( $slug );
				if ( count( $actions ) === 0 ) {
					continue;
				}
				$nice_name          = $integration->get_name();
				$data[ $nice_name ] = [];
				/**
				 * @var $action BWFAN_Action
				 */
				foreach ( $actions as $action ) {
					$data[ $nice_name ][ $action->get_slug() ] = [
						'label'     => $action->get_name(),
						'available' => 'yes',
						'priority'  => $action->get_action_priority(),
					];
				}

				uasort( $data[ $nice_name ], function ( $item1, $item2 ) {
					return ( $item1['priority'] <=> $item2['priority'] );
				} );
			}
		}

		return $data;
	}

	/**
	 * Return all the actions with group and their integrations
	 *
	 * @return array
	 */
	public static function get_all_integrations() {
		return self::$integration_actions;
	}

	public function get_integration_actions_localize_data() {
		return self::$integration_actions_localize_data;
	}

	public function get_actions_schemas( $event = '', $integration_actions = array() ) {
		if ( empty( $integration_actions ) ) {
			/** @var BWFAN_Action[] $actions */
			$integration_actions = $this->get_actions_objs( $event );
		}

		$schemas = array();
		foreach ( $integration_actions as $integration => $actions ) {

			/** @var BWFAN_Action[] $actions */
			foreach ( $actions as $slug => $action ) {
				$final_actions[ $integration ][ $slug ] = $action->get_fields_schema();
			}
		}

		return $schemas;
	}

	public function get_actions_objs( $event = '' ) {
		$integrations = self::$integration_actions;

		$final_actions = array();
		foreach ( $integrations as $integration => $actions ) {

			/** @var BWFAN_Action[] $actions */
			foreach ( $actions as $slug => $action ) {
				$included_events = $action->get_included_events();
				$excluded_events = $action->get_excluded_events();

				if ( ! empty( $included_events ) && in_array( $event, $included_events ) ) {
					$final_actions[ $integration ][ $slug ] = $action;
					continue;
				}

				if ( empty( $excluded_events ) || ! in_array( $event, $excluded_events ) ) {
					$final_actions[ $integration ][ $slug ] = $action;
				}
			}
		}

		return $final_actions;
	}

	/**
	 * Returns integration group array
	 *
	 * @return array
	 */
	public function get_integration_group() {
		return apply_filters( 'bwfan_integration_groups', [
			'messaging'  => [
				'group_slug' => 'messaging',
				'label'      => 'Messaging',
				'subgroup'   => [
					'wp',
					'slack',
					'twilio',
					'bulkgate',
					'wabot',
				],
				'priority'   => 25,
			],
			'autonami'   => [
				'group_slug' => 'autonami',
				'label'      => 'Automations',
				'subgroup'   => [
					'autonami',
				],
				'priority'   => 15,
			],
			'wp'         => [
				'group_slug' => 'wp',
				'label'      => 'WordPress',
				'subgroup'   => [
					'wp_adv',
				],
				'priority'   => 25,
			],
			'wc'         => [
				'group_slug' => 'wc',
				'label'      => 'WooCommerce',
				'subgroup'   => [
					'wc',
					'wcs',
					'wcm',
				],
				'priority'   => 35,
			],
			'woofunnels' => [
				'group_slug' => 'woofunnels',
				'label'      => 'Funnel Builder',
				'subgroup'   => [
					'upstroke',
				],
				'priority'   => 8,
			],
			'crm'        => [
				'group_slug' => 'crm',
				'label'      => 'CRM',
				'subgroup'   => [
					'activecampaign',
					'convertkit',
					'mailchimp',
					'getresponse',
					'ontraport',
					'drip',
					'klaviyo',
				],
				'priority'   => 55,
			],
			'send_data'  => [
				'group_slug' => 'send_data',
				'label'      => 'Send Data',
				'subgroup'   => [
					'zapier',
				],
				'priority'   => 85,
			],
			'wlm'        => [
				'group_slug' => 'wlm',
				'label'      => 'Membership',
				'subgroup'   => [
					'wlm',
				],
				'priority'   => 60,
			],
			'lms'        => [
				'group_slug' => 'lms',
				'label'      => 'LMS',
				'subgroup'   => [
					'ld',
				],
				'priority'   => 65,
			],
			'affiliate'  => [
				'group_slug' => 'affiliate',
				'label'      => 'Affiliate',
				'subgroup'   => [
					'affwp',
				],
				'priority'   => 75,
			],
		] );
	}

	/**
	 * Returns Integration subgroups
	 *
	 * @return array
	 */
	public function get_integration_subgroups() {
		return apply_filters( 'bwfan_integration_subgroups', [
			'hubspot'        => [
				'Hubspot' => [
					'hubspot_create_contact',
					'hubspot_update_contact',
					'hubspot_add_contact_to_list',
					'hubspot_remove_contact_from_list',
					'hubspot_add_contact_to_workflow',
					'hubspot_remove_contact_from_workflow',
				],
			],
			'autonami'       => [
				'Contact' => [
					'crm_create_contact',
					'crm_change_contact_status',
					'crm_update_customfields',
					'crm_add_tag',
					'crm_rmv_tag',
					'crm_add_to_list',
					'crm_rmv_from_list',
					'crm_add_contact_note',
					'automation_end',
				],
			],
			'wp'             => [
				'Email' => [
					'wp_sendemail',
				],
			],
			'wp_adv'         => [
				'WordPress' => [
					'wp_createuser',
					'wp_update_user_meta',
					'wp_update_user_role',
					'wp_custom_callback',
					'wp_http_post',
					'wp_debug',
				],
			],
			'twilio'         => [
				'Twilio' => [
					'twilio_send_sms',
				],
			],
			'wcm'            => [
				'Membership' => [
					'wcm_update_plan',
					'wcm_delete_membership',
				],
			],
			'wcs'            => [
				'Subscriptions' => [
					'wcs_change_subscription_status',
					'wcs_cancel_order_subscriptions',
					'wcs_add_note',
					'wcs_add_coupon',
					'wcs_remove_coupon',
					'wcs_send_subscription_invoice',
				],
			],
			'wc'             => [
				'WooCommerce' => [
					'pro_wc_create_coupon',
					'wc_remove_coupon',
				],
			],
			'getresponse'    => [
				'Get Response' => [
					'gr_create_contact',
					'gr_update_custom_fields',
					'gr_add_tags',
					'gr_remove_tags',
					'gr_add_to_list',
					'gr_remove_from_list',
				],
			],
			'ontraport'      => [
				'Ontraport' => [
					'ontraport_create_contact',
					'ontraport_update_contact_fields',
					'ontraport_add_tags',
					'ontraport_rmv_tag',
					'ontraport_add_to_campaign',
					'ontraport_rmv_from_campaign',
				],
			],
			'mailchimp'      => [
				'Mailchimp' => [
					'mailchimp_add_to_list',
					'mailchimp_remove_from_list',
					'mailchimp_add_tags',
					'mailchimp_remove_tags',
					'mailchimp_add_to_automation',
					'mailchimp_remove_from_automation',
					'mailchimp_update_custom_fields',
					'mailchimp_add_cart',
				],
			],
			'klaviyo'        => [
				'Klaviyo' => [
					'klaviyo_add_to_list',
					'klaviyo_remove_from_list',
					'klaviyo_update_profile_fields',
				],
			],
			'convertkit'     => [
				'Convertkit' => [
					'ck_add_tags',
					'ck_rmv_tags',
					'ck_add_to_sequence',
					'ck_add_customfields',
				],
			],
			'bulkgate'       => [
				'BulkGate' => [
					'bulkgate_send_transactional_sms',
				],
			],
			'activecampaign' => [
				'ActiveCampaign' => [
					'ac_create_contact',
					'ac_update_customfields',
					'ac_add_tag',
					'ac_rmv_tag',
					'ac_add_to_list',
					'ac_rmv_from_list',
					'ac_add_to_automation',
					'ac_rmv_from_automation',
					'ac_create_abandoned_cart',
				],
			],
			'wlm'            => [
				'WishList Member' => [
					'wlm_add_user_to_pay_per_post',
					'wlm_remove_user_from_pay_per_post',
					'wlm_user_add_level',
					'wlm_user_remove_level',
					'wlm_user_move_level',
					'wlm_user_cancel_level',
				],
			],
			'ld'             => [
				'LearnDash' => [
					'ld_enroll_user_into_course',
					'ld_remove_user_from_course',
					'ld_add_user_to_group',
					'ld_add_user_from_group',
					'ld_reset_course_progress',
					'ld_reset_quiz_attempts',
				],
			],
			'google_sheets'  => [
				'Google Sheets' => [
					'gs_insert_data',
					'gs_update_data',
					'gs_delete_data',
				],
			],
			'zapier'         => [
				'POST'   => [
					'wp_http_post',
				],
				'Zapier' => [
					'za_send_data',
				],
			],
			'slack'          => [
				'Slack' => [
					'sl_message',
					'sl_message_user',
				]
			]
		] );
	}

	public function get_dynamic_action_data() {
		return self::$dynamic_register_actions;
	}
}

if ( class_exists( 'BWFAN_Load_Integrations' ) ) {
	BWFAN_Core::register( 'integration', 'BWFAN_Load_Integrations' );
}
