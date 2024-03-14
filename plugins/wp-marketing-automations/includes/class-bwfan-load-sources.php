<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class BWFAN_Load_Sources
 * @package Autonami
 * @author XlPlugins
 */
#[AllowDynamicProperties]
class BWFAN_Load_Sources {
	public static $all_events = [];
	/**
	 * Saves all the main trigger's object
	 * @var array
	 */
	private static $sources_obj = [];
	/**
	 * Saves all the action's object
	 * @var array
	 */
	private static $sources_events_obj = [];
	private static $sources_events_arr = [];
	private static $sources_events_arr1 = [];
	private static $sources_localize_data = [];
	private static $sources_events_localize = [];
	private static $events_localize = [];
	private static $events_subgroup = [];
	private static $goal_subgroup = [];
	private static $optgroup_data = [];
	private static $ins = null;
	private static $registered_events = []; // This property is used for displaying all events options in global settings to stop event
	private static $dynamic_register_events = [];

	/**
	 * BWFAN_Load_Sources constructor.
	 */
	public function __construct() {
	}

	/**
	 * Return the object of current class
	 *
	 * @return null|BWFAN_Load_Sources
	 */
	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	/**
	 * Register the source when the source file is included
	 *
	 * @param $source_class BWFAN_Source
	 *
	 * @return void
	 */
	public static function register( $source_class ) {
		if ( ! method_exists( $source_class, 'get_instance' ) ) {
			return;
		}
		/**
		 * @var $source BWFAN_Source
		 */
		$source = $source_class::get_instance();
		$slug   = $source->get_slug();
		$source->load_events();
		self::$sources_obj[ $slug ] = $source;

		self::$sources_localize_data[ $slug ] = $source->get_localize_data();
		$group_slug                           = $source->get_group_slug();

		if ( ! isset( self::$dynamic_register_events['group'][ $group_slug ] ) ) {
			self::$dynamic_register_events['group'][ $group_slug ] = [
				'group_slug' => $group_slug,
				'label'      => $source->get_group_name(),
				'subgroup'   => [ $slug ],
			];
		} elseif ( isset( self::$dynamic_register_events['group'][ $group_slug ] ) && ! in_array( $slug, self::$dynamic_register_events['group'][ $group_slug ]['subgroup'] ) ) {
			self::$dynamic_register_events['group'][ $group_slug ]['subgroup'][] = $slug;
		}
		do_action( 'bwfan_' . $slug . '_source_loaded', $source );
	}

	/**
	 * Returns event group array
	 *
	 * @return array
	 */
	public static function get_event_groups() {
		$eventGroup = [
			'autonami'   => [
				'group_slug' => 'autonami',
				'label'      => 'Automations',
				'subgroup'   => [
					'autonami',
				],
				'priority'   => 5,
			],
			'wc'         => [
				'group_slug' => 'wc',
				'label'      => 'WooCommerce',
				'subgroup'   => [
					'wc',
					'wcs',
					'wcm',
					'wc_wishlist',
				],
				'priority'   => 10,
			],
			'wp'         => [
				'group_slug' => 'wp',
				'label'      => 'WordPress',
				'subgroup'   => [
					'wp',
				],
				'priority'   => 20,
			],
			'woofunnels' => [
				'group_slug' => 'woofunnels',
				'label'      => 'Funnel Builder',
				'subgroup'   => [
					'optinform',
					'upstroke',
				],
				'priority'   => 8,
			],
			'lms'        => [
				'group_slug' => 'lms',
				'label'      => 'LMS',
				'subgroup'   => [
					'ld',
				],
				'priority'   => 40,
			],
			'messaging'  => [
				'group_slug' => 'messaging',
				'label'      => 'Messaging',
				'subgroup'   => [
					'twilio',
				],
				'priority'   => 50,
			],
			'forms'      => [
				'group_slug' => 'forms',
				'label'      => 'Forms',
				'subgroup'   => [
					'elementor',
					'wpforms',
					'gf',
					'tve',
					'ninjaform',
					'cf7',
					'fluentforms',
					'formidable_forms',
				],
				'priority'   => 60,
			],
			'affiliate'  => [
				'group_slug' => 'affiliate',
				'label'      => 'Affiliate',
				'subgroup'   => [
					'affwp',
				],
				'priority'   => 70,
			],
			'crm'        => [
				'group_slug' => 'crm',
				'label'      => 'CRM',
				'subgroup'   => [
					'ac',
					'drip',
					'mailchimp',
					'hubspot',
					'mautic',
					'ontraport',
				],
				'priority'   => 80,
			],
		];

		uasort( $eventGroup, function ( $a, $b ) {
			return $a['priority'] <=> $b['priority'];
		} );

		return apply_filters( 'bwfan_event_groups', $eventGroup );
	}

	/**
	 * Register every event when event file is included
	 *
	 * @param $event BWFAN_Event
	 */
	public static function register_events( BWFAN_Event $event ) {
		if ( ! method_exists( $event, 'get_instance' ) ) {
			return;
		}
		$all_event_list    = self::get_all_event_list();
		$temp_source       = $event->get_source();
		$event_slug        = $event->get_slug();
		$optgroup          = $event->get_optgroup_label();
		$optgroup_priority = $event->get_optgroup_priority();
		$priority          = $event->get_priority();

		self::$sources_events_obj[ $temp_source ][ $event_slug ]      = $event;
		self::$registered_events[ $event_slug ]                       = $event;
		self::$sources_events_localize[ $temp_source ][ $event_slug ] = $event->get_localize_data();
		self::$events_localize[ $event_slug ]                         = self::$sources_events_localize[ $temp_source ][ $event_slug ];

		if ( ! isset( self::$sources_events_arr[ $temp_source ] ) ) {
			self::$sources_events_arr[ $temp_source ] = [
				'events' => [],
			];
		}
		self::$sources_events_arr[ $temp_source ]['events'][ $optgroup ][ $event_slug ] = [
			'name'      => $event->get_name(),
			'available' => 'yes',
		];

		self::$events_subgroup[ $temp_source ][ $optgroup ][] = $event_slug;

		if ( $event->is_goal() ) {
			self::$goal_subgroup[ $temp_source ][ $optgroup ][] = $event_slug;
		}

		self::$optgroup_data[ $optgroup ] = $optgroup_priority;

		self::$sources_events_arr1[ $temp_source ]['events'][ strval( $priority ) ][] = [
			'opt_group' => $optgroup,
			'slug'      => $event_slug,
			'name'      => $event->get_name(),
			'available' => 'yes',
		];

//		if ( ! isset( $all_event_list[ $event_slug ] ) ) {
		self::$dynamic_register_events['list'][ $event_slug ] = [
			'event_name' => $event->get_name(),
			'lock'       => true,
		];

		self::$dynamic_register_events['subgroup'][ $temp_source ][ $optgroup ][] = $event_slug;
		self::$dynamic_register_events['subgroup_priority'][ $optgroup ]          = $optgroup_priority;
		if ( isset( self::$dynamic_register_events['group'][ $temp_source ] ) ) {
			self::$dynamic_register_events['group'][ $temp_source ]['subgroup'][] = $event_slug;
		}
//		}
	}

	/**
	 * Static array of all events
	 *
	 * @return array
	 */
	public static function get_all_event_list() {
		return apply_filters( 'bwfan_all_event_list', [
			'affwp_affiliate_report'        => 'Affiliate Digests',
			'affwp_application_approved'    => 'Application Approved',
			'affwp_application_rejected'    => 'Application Rejected',
			'affwp_makes_sale'              => 'Affiliate Makes A Sale',
			'affwp_referral_rejected'       => 'Referral Rejected',
			'affwp_signup'                  => 'Application Sign Up',
			'affwp_status_change'           => 'Affiliate Status Change',
			'autonami_webhook_received'     => 'Webhook Received',
			'crm_assigned_list'             => 'Added to List',
			'crm_assigned_tag'              => 'Tag is Added',
			'crm_contact_subscribed'        => 'Contact Subscribes',
			'crm_contact_unsubscribed'      => 'Contact Unsubscribes',
			'crm_link_trigger'              => 'Link is Clicked',
			'crm_unassigned_tag'            => 'Tag is Removed',
			'crm_unassigned_list'           => 'Removed from List',
			'formidable_form_submit'        => 'Form Submits',
			'elementor_popup_form_submit'   => 'Popup Form Submission',
			'elementor_form_submit'         => 'Form Submits',
			'fluent_form_submit'            => 'Form Submits',
			'gf_form_submit'                => 'Form Submits',
			'ld_user_added_to_group'        => 'User Added to Group',
			'ld_user_completes_course'      => 'User Completes a Course',
			'ld_user_completes_lesson'      => 'User Completes a Lesson',
			'ld_user_completes_quiz'        => 'User Completes a Quiz',
			'ld_user_completes_topic'       => 'User Completes a Topic',
			'ld_user_enrolled_into_course'  => 'User Enrolled into a Course',
			'ld_user_remove_from_group'     => 'User Removed into a Group',
			'ld_user_removed_from_course'   => 'User Removed into a Course',
			'ninja_form_submit'             => 'Form Submits',
			'funnel_optin_form_submit'      => 'Form Submits',
			'tve_lead_form_submit'          => 'Form Submits',
			'upstroke_funnel_ended'         => 'Funnel Ended',
			'upstroke_offer_payment_failed' => 'Offer Payment Failed',
			'upstroke_offer_rejected'       => 'Offer Rejected',
			'upstroke_offer_viewed'         => 'Offer Viewed',
			'upstroke_product_accepted'     => 'Offer Accepted',
			'wcm_membership_created'        => 'Membership Created',
			'wcm_status_changed'            => 'Membership Status Changed',
			'wcs_before_end'                => 'Subscriptions Before End',
			'wcs_before_renewal'            => 'Subscriptions Before Renewal',
			'wcs_card_expiry'               => 'Customer Before Card Expiry',
			'wcs_created'                   => 'Subscriptions Created',
			'wcs_note_added'                => 'Subscription Note Added',
			'wcs_renewal_payment_complete'  => 'Subscriptions Renewal Payment Complete',
			'wcs_renewal_payment_failed'    => 'Subscriptions Renewal Payment Failed',
			'wcs_status_changed'            => 'Subscriptions Status Changed',
			'wcs_trial_end'                 => 'Subscriptions Trial End',
			'wc_wishlist_item_onsale'       => 'Wishlist Item on Sale',
			'wc_wishlist_reminder'          => 'Wishlist Reminder',
			'wc_wishlist_user_add_product'  => 'User Adds Product To Wishlist',
			'wlm_add_level'                 => 'User Added to Membership Level',
			'wlm_cancel_level'              => 'User Membership Level Cancelled',
			'wlm_expired_user_level'        => 'User Membership Level Expired',
			'wlm_remove_level'              => 'User Removed from Membership Level',
			'wlm_user_registration'         => 'User Submits a Registration Form',
			'wpforms_form_submit'           => 'Form Submits',
			'cf7_form_submit'               => 'Form Submits',
			'wc_comment_post'               => 'Review Received',
			'wc_new_order'                  => 'Order Created',
			'wc_order_note_added'           => 'Order Note Added',
			'wc_order_status_change'        => 'Order Status Changed',
			'wc_product_purchased'          => 'Order Created',
			'wc_product_refunded'           => 'Order Item Refunded',
			'wc_product_stock_reduced'      => 'Order Item Stock',
			'wc_order_status_pending'       => 'Order Status Pending',
			'wc_customer_win_back'          => 'Customer Win Back',
			'ab_cart_abandoned'             => 'Cart Abandoned',
			'ab_cart_recovered'             => 'Cart Recovered',
			'wp_user_creation'              => 'User Created',
			'wp_user_login'                 => 'User Login',
			'ac_webhook_received'           => 'Webhook Received',
			'hubspot_message_received'      => 'Webhook Received',
			'mailchimp_message_received'    => 'Webhook Received',
			'ontraport_webhook_received'    => 'Webhook Received',
			'mautic_webhook_received'       => 'Webhook Received',
			'twilio_message_received'       => 'SMS Received',
			'drip_webhook_received'         => 'Webhook Received',
		] );
	}

	/**
	 * Return all the Sources With object
	 *
	 * @return array
	 */
	public static function get_sources_obj() {
		ksort( self::$sources_obj );

		return apply_filters( 'bwfan_get_sources', self::$sources_obj );
	}

	/**
	 * Return all the events with group and their sources
	 *
	 * @return array
	 */
	public static function get_all_sources_obj() {
		$data = apply_filters( 'bwfan_get_all_sources', self::$sources_events_obj );

		return $data;
	}

	/**
	 * Hierarchy of source and events
	 * @return mixed|void
	 *
	 */
	public static function get_sources_events_arr() {
		$test_arr    = [];
		$final_array = [];
		if ( is_array( self::$sources_events_arr1 ) && count( self::$sources_events_arr1 ) > 0 ) {
			foreach ( self::$sources_events_arr1 as $source_slug => $events_list ) {
				if ( is_array( $events_list['events'] ) && count( $events_list['events'] ) > 0 ) {
					$keys = array_keys( $events_list['events'] );
					sort( $keys );
					foreach ( $keys as $key ) {
						$test_arr[ $source_slug ]['events'][ $key ] = $events_list['events'][ $key ];
					}
				}
			}

			foreach ( $test_arr as $source_slug => $events_list ) {
				if ( ! isset( $final_array[ $source_slug ] ) ) {
					$final_array[ $source_slug ] = [];
				}
				if ( ! isset( $final_array[ $source_slug ]['events'] ) ) {
					$final_array[ $source_slug ]['events'] = [];
				}
				foreach ( $events_list['events'] as $event ) {
					if ( ! is_array( $event ) ) {
						continue;
					}
					foreach ( $event as $event_data ) {
						if ( ! isset( $final_array[ $source_slug ]['events'][ $event_data['opt_group'] ] ) ) {
							$final_array[ $source_slug ]['events'][ $event_data['opt_group'] ] = [];
						}
						$final_array[ $source_slug ]['events'][ $event_data['opt_group'] ][ $event_data['slug'] ] = [
							'name'      => $event_data['name'],
							'available' => $event_data['available'],
						];
					}
				}
			}
		}

		return $final_array;
	}

	/**
	 * Returns souce subgroup
	 *
	 * @return array
	 */
	public static function get_source_subgoup() {
		return self::$events_subgroup;
	}

	/**
	 * Returns gaol subgroup
	 *
	 * @return array
	 */
	public static function get_goal_subgroup() {
		return self::$goal_subgroup;
	}

	/** Return event data for event */
	public static function get_api_event_list_data( $get_goal = false ) {
		$final_event_arr = [];
		foreach ( self::$sources_events_obj as $event_arr ) {
			foreach ( $event_arr as $key => $event_obj ) {
				if ( $get_goal && ! $event_obj->is_goal() ) {
					continue;
				}
				try {
					$data = $get_goal ? $event_obj->get_goal_data_for_api() : $event_obj->get_event_data_for_api();
				} catch ( Error $e ) {
					continue;
				}
				$data['source_label'] = '';
				if ( isset( $data['source_type'] ) && isset( self::$sources_localize_data[ $data['source_type'] ] ) ) {
					$data['source_label'] = self::$sources_localize_data[ $data['source_type'] ]['nice_name'];
				}
				if ( ! empty( $data ) ) {
					$final_event_arr[ $key ] = $data;
				}
			}
		}

		if ( ! $get_goal ) {
			$all_events       = self::get_all_event_list();
			$all_events_array = [];
			foreach ( $all_events as $event_slug => $event_label ) {
				if ( isset( $final_event_arr[ $event_slug ] ) ) {
					$all_events_array[ $event_slug ] = $final_event_arr[ $event_slug ];
				} else {
					$all_events_array[ $event_slug ] = [
						'event_name' => $event_label,
						'lock'       => true,
					];
				}
				unset( $final_event_arr[ $event_slug ] );
			}

			if ( ! empty( $final_event_arr ) ) {
				$final_event_arr = array_merge( $all_events_array, $final_event_arr );
			} else {
				$final_event_arr = $all_events_array;
			}

		}

		return $final_event_arr;
	}

	/**
	 * Returns optgroup priority
	 *
	 * @return array
	 */
	public static function get_optgroup_priority() {
		return self::$optgroup_data;
	}

	/**
	 * Return the source instance
	 *
	 * @return BWFAN_Source
	 */
	public function get_source( $slug = 'wp' ) {
		return isset( self::$sources_obj[ $slug ] ) ? self::$sources_obj[ $slug ] : null;
	}

	/**
	 * Returns the registered actions
	 *
	 * @param  $event
	 *
	 * @return array
	 */
	public function get_events() {
		return self::$registered_events;
	}

	/**
	 * Returns the registered actions
	 *
	 * @param  $event
	 *
	 * @return BWFAN_Event
	 */
	public function get_event( $event = '' ) {
		return isset( self::$registered_events[ $event ] ) ? self::$registered_events[ $event ] : null;
	}

	/**
	 * GEt Event  localize data with under the source
	 * @return array
	 */
	public function get_sources_events_localize_data() {
		return apply_filters( 'bwfan_source_action_localize_data', self::$sources_events_localize );
	}

	/**
	 * Get source localize data
	 * @return array
	 */
	public function get_source_localize_data() {
		uasort( self::$sources_localize_data, function ( $item1, $item2 ) {
			return $item1['priority'] <=> $item2['priority'];
		} );

		return apply_filters( 'bwfan_source_localize_data', self::$sources_localize_data );
	}

	/**
	 * Returns event subgroup array
	 *
	 * @return array
	 */
	public function get_event_subgroups() {
		return apply_filters( 'bwfan_event_subgroups', [
			'affwp'            => [
				'AffiliateWP' => [
					'affwp_application_approved',
					'affwp_application_rejected',
					'affwp_signup',
					'affwp_makes_sale',
					'affwp_status_change',
					'affwp_referral_rejected',
					'affwp_affiliate_report',
				],
			],
			'autonami'         => [
				'Contact'    => [
					'crm_assigned_tag',
					'crm_unassigned_tag',
					'crm_assigned_list',
					'crm_unassigned_list',
					'crm_contact_subscribed',
					'crm_contact_unsubscribed',
					'crm_link_trigger',
				],
				'Automation' => [
					'autonami_webhook_received',
				],
			],
			'formidable_forms' => [
				'Formidable Forms' => [
					'formidable_form_submit',
				],
			],
			'elementor'        => [
				'Elementor' => [
					'elementor_form_submit',
					'elementor_popup_form_submit',
				],
			],
			'fluentforms'      => [
				'Fluent Form' => [
					'fluent_form_submit',
				],
			],
			'gf'               => [
				'Gravity Form' => [
					'gf_form_submit',
				],
			],
			'ld'               => [
				'LearnDash' => [
					'ld_user_added_to_group',
					'ld_user_remove_from_group',
					'ld_user_enrolled_into_course',
					'ld_user_removed_from_course',
					'ld_user_completes_course',
					'ld_user_completes_lesson',
					'ld_user_completes_quiz',
					'ld_user_completes_topic',
				],
			],
			'ninjaform'        => [
				'Ninja Form' => [
					'ninja_form_submit',
				],
			],
			'optinform'        => [
				'Optin Form' => [
					'funnel_optin_form_submit',
				],
			],
			'tve'              => [
				'Thrive Leads' => [
					'tve_lead_form_submit',
				],
			],
			'upstroke'         => [
				'One-Click Upsells' => [
					'upstroke_funnel_started',
					'upstroke_offer_viewed',
					'upstroke_product_accepted',
					'upstroke_offer_rejected',
					'upstroke_offer_payment_failed',
				],
			],
			'wcm'              => [
				'Membership' => [
					'wcm_membership_created',
					'wcm_status_changed',
				],
			],
			'wcs'              => [
				'Subscription' => [
					'wcs_created',
					'wcs_before_renewal',
					'wcs_before_end',
					'wcs_renewal_payment_complete',
					'wcs_renewal_payment_failed',
					'wcs_status_changed',
					'wcs_trial_end',
					'wcs_card_expiry',
					'wcs_note_added',
				],
			],
			'wc_wishlist'      => [
				'WooCommerce Wishlist' => [
					'wc_wishlist_item_onsale',
					'wc_wishlist_reminder',
					'wc_wishlist_user_add_product',
				],
			],
			'wlm'              => [
				'WishList Member' => [
					'wlm_add_level',
					'wlm_cancel_level',
					'wlm_expired_user_level',
					'wlm_remove_level',
					'wlm_user_registration',
				],
			],
			'wpforms'          => [
				'WPForms' => [
					'wpforms_form_submit',
				],
			],
			'cf7'              => [
				'Contact Form 7' => [
					'cf7_form_submit',
				],
			],
			'wc'               => [
				'Reviews'  => [
					'wc_comment_post',
				],
				'Cart'     => [
					'ab_cart_abandoned',
					'ab_cart_recovered',
				],
				'Orders'   => [
					'wc_new_order',
					'wc_product_purchased',
					'wc_order_status_change',
					'wc_product_refunded',
					'wc_product_stock_reduced',
					'wc_order_status_pending',
					'wc_order_note_added',
				],
				'Customer' => [
					'wc_customer_win_back',
				],
			],
			'wp'               => [
				'User' => [
					'wp_user_creation',
					'wp_user_login',
				],
			],
			'ac'               => [
				'ActiveCampaign' => [
					'ac_webhook_received',
				],
			],
			'hubspot'          => [
				'Hubspot' => [
					'hubspot_message_received',
				],
			],
			'mailchimp'        => [
				'Mailchimp' => [
					'mailchimp_message_received',
				],
			],
			'ontraport'        => [
				'Ontraport' => [
					'ontraport_webhook_received',
				],
			],
			'mautic'           => [
				'Mautic' => [
					'mautic_webhook_received',
				],
			],
			'twilio'           => [
				'Twilio' => [
					'twilio_message_received',
				],
			],
		] );
	}

	/**
	 * Returns event subgroup priority
	 *
	 * @return array
	 */
	public function get_event_subgroup_priority() {
		return apply_filters( 'bwfan_event_subgroup_priority', [
			'Contact'              => 15,
			'Automation'           => 20,
			'User'                 => 10,
			'AffiliateWP'          => 10,
			'LearnDash'            => 10,
			'Optin Form'           => 5,
			'One-Click Upsells'    => 10,
			'WPForms'              => 5,
			'Elementor'            => 10,
			'Thrive Leads'         => 15,
			'Gravity Form'         => 20,
			'Contact Form 7'       => 25,
			'Fluent Form'          => 30,
			'Ninja Form'           => 35,
			'Formidable Forms'     => 50,
			'Cart'                 => 5,
			'Orders'               => 10,
			'Customer'             => 16,
			'Reviews'              => 18,
			'Subscription'         => 20,
			'Membership'           => 25,
			'WooCommerce Wishlist' => 30,
			'ActiveCampaign'       => 10,
			'Drip'                 => 20,
			'Mailchimp'            => 30,
			'Hubspot'              => 40,
			'Mautic'               => 50,
			'Ontraport'            => 60,
			'Twilio'               => 10,
			'WishList Member'      => 10,
		] );
	}

	public function get_dynamic_event_data() {
		return self::$dynamic_register_events;
	}
}

if ( class_exists( 'BWFAN_Core' ) ) {
	BWFAN_Core::register( 'sources', 'BWFAN_Load_Sources' );
}
