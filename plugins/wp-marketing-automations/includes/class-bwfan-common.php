<?php

/**
 * Class BWFAN_Common
 * Handles Common Functions For Admin as well as front end interface
 */
#[AllowDynamicProperties]
class BWFAN_Common {
	public static $http;

	public static $integrations_saved_data = array();
	public static $date_format = null;
	public static $time_format = null;
	public static $admin_email = null;
	public static $select2ajax_functions = null;
	public static $events_async_data = null;
	public static $time_periods = null;
	public static $taxonomy_post_type = array();
	public static $offer_product_types = array(
		'simple',
		'variable',
		'variation',
		'subscription',
		'variable-subscription',
		'subscription_variation',
	);

	public static $exec_task_id = null;

	public static $recurring_actions_db = [];

	/** For v2 automation - special property */
	public static $end_v2_current_contact_automation = false;
	public static $change_data_strore = false;
	public static $last_automation_cid = [];

	/** Cached DB Calls */
	public static $cached_db_data = [];

	public static $random_products = [];

	public static $dynamic_str = '';

	public static $unsubscribe_page_link = '';

	public static $order_status_change = [];

	public static function init() {
		self::$date_format           = get_option( 'date_format' );
		self::$time_format           = get_option( 'time_format' );
		self::$admin_email           = get_option( 'admin_email' );
		self::$select2ajax_functions = array( 'get_subscription_product', 'get_membership_plans', 'get_coupon' );

		register_deactivation_hook( BWFAN_PLUGIN_FILE, array( __CLASS__, 'deactivation' ) );

		/** Loading WooFunnels core */
		add_action( 'plugins_loaded', function () {
			WooFunnel_Loader::include_core();
		}, - 99 );

		add_action( 'plugins_loaded', [ __CLASS__, 'set_dynamic_string' ], 0 );

		add_filter( 'modify_set_data', array( __CLASS__, 'parse_default_merge_tags' ), 10, 1 );
		add_action( 'bwfan_delete_order_meta_payment_failed', array( __CLASS__, 'delete_order_meta' ), 10, 1 );
		add_filter( 'bwfan_select2_ajax_callable', array( __CLASS__, 'get_callable_object' ), 1, 2 );

		add_action( 'admin_notices', array( __CLASS__, 'bwfan_run_cron_test' ) );
		add_action( 'rest_api_init', array( __CLASS__, 'add_plugin_endpoint' ) );

		/** showing consent text on checkout page */
		add_action( 'wp', array( __CLASS__, 'display_marketing_optin_checkbox' ) );

		add_filter( 'action_scheduler_queue_runner_batch_size', array( __CLASS__, 'ac_increase_queue_batch_size' ) );
		add_filter( 'action_scheduler_queue_runner_time_limit', array( __CLASS__, 'ac_increase_max_execution_time' ) );
		add_filter( 'cron_schedules', array( __CLASS__, 'make_custom_events_time' ) );

		/** Action Scheduler custom table worker callback */
		add_action( 'bwfan_run_queue', array( __CLASS__, 'run_as_ct_worker' ) );
		add_action( 'action_scheduler_pre_init', array( __CLASS__, 'as_pre_init_cb' ) );
		add_action( 'action_scheduler_pre_init', array( __CLASS__, 'as_pre_init_cli_cb' ) );

		/** Action Scheduler custom table v2 worker callback */
		add_action( 'bwfan_run_queue_v2', array( __CLASS__, 'run_as_ct_v2_worker' ) );
		add_action( 'action_scheduler_pre_init', array( __CLASS__, 'as_pre_init_v2_cb' ), 11 );
		add_action( 'action_scheduler_pre_init', array( __CLASS__, 'as_pre_init_cli_v2_cb' ), 12 );

		/** Enable WooFunnels Action Scheduler Data Store */
		add_filter( 'enable_woofunnels_as_ds', '__return_true' );

		/** Convert all active abandoned rows to abandoned */
		add_action( 'bwfan_check_abandoned_carts', array( __CLASS__, 'check_for_abandoned_carts' ) );

		/** Delete all the old abandoned cart rows and their queued tasks */
		add_action( 'bwfan_delete_old_abandoned_carts', array( __CLASS__, 'delete_old_abandoned_carts' ) );
		add_action( 'bwfan_mark_abandoned_lost_cart', array( __CLASS__, 'mark_abandoned_lost_cart' ) );

		add_action( 'bwfan_delete_expired_autonami_coupons', array( __CLASS__, 'delete_expired_autonami_coupons' ) );

		add_action( 'bwfan_get_sources_events', array( __CLASS__, 'merge_pro_events' ) );

		add_action( 'woofunnels_woocommerce_thankyou', array( __CLASS__, 'hit_cron_to_run_tasks' ) );

		/** Auto deploy coupon in the cart */
		add_action( 'wp', array( __CLASS__, 'auto_apply_wc_coupon' ), 20 );
		/** Handling when restoring abandoned cart */
		add_action( 'bwfan_abandoned_cart_restored', array( __CLASS__, 'auto_apply_wc_coupon' ) );

		/** update order meta marketing_status details */
		add_action( 'woocommerce_checkout_create_order', array( __CLASS__, 'bwfan_update_order_user_consent' ), 10, 2 );
		add_action( 'bwf_normalize_contact_meta_before_save', array( __CLASS__, 'save_marketing_status_for_user' ), 20, 2 );
		/** save marketing status if order is failed  */
		add_action( 'woocommerce_order_status_failed', array( __CLASS__, 'woocommerce_order_status_failed' ), 999, 2 );

		/**
		 * Hooked over action_scheduler_pre_init
		 * Initiating core action scheduler
		 */
		add_action( 'bwf_after_action_scheduler_load', array( __CLASS__, 'bwf_after_action_scheduler_load' ), 11 );

		/**Update the execution time of v2 automations if delay time changed */
		add_action( 'bwfan_delay_step_updated', [ __CLASS__, 'bwfan_delay_step_updated' ], 10, 1 );

		add_action( 'bwfan_goal_step_updated', [ __CLASS__, 'bwfan_goal_step_updated' ], 10, 2 );

		add_action( 'bwfan_lost_cart_triggered', [ __CLASS__, 'bwfan_lost_cart_triggered' ] );

		add_action( 'bwfan_automation_step_deleted', [ __CLASS__, 'bwfan_automation_step_deleted' ], 10, 1 );

		add_action( 'bwfan_store_automation_completed_ids', [ __CLASS__, 'bwfan_store_automation_completed_ids' ] );
		add_action( 'bwfan_store_automation_active_ids', [ __CLASS__, 'bwfan_store_automation_active_ids' ] );

		/** Automation contact bulk action */
		add_action( 'bwfan_automation_contact_bulk_action', [ __CLASS__, 'bwfan_automation_contact_bulk_action' ], 10, 4 );

		/** Bulk action on listing pages */
		add_action( 'bwfan_bulk_action', [ __CLASS__, 'bwfan_bulk_action' ], 10, 2 );

		add_action( 'bwfan_delete_expired_coupons', array( __CLASS__, 'delete_expired_dynamic_coupons' ) );

		/** Update contact wp user id after a wp user is deleted */
		add_action( 'delete_user', array( __CLASS__, 'update_contact_wp_id' ) );

		add_action( 'bwfan_update_meta_automations_v2', [ __CLASS__, 'update_meta_automations_v2' ] );

		add_action( 'bwfan_delete_logs', [ __CLASS__, 'delete_bwfan_logs' ] );

		/** Update automation contact's step trail status */
		add_action( 'bwfan_update_contact_trail', [ __CLASS__, 'bwfan_update_contact_trail' ] );

		add_action( 'woocommerce_order_status_changed', [ __CLASS__, 'order_status_change' ], 10, 3 );

		/** Cron 'every 8 hrs' schedule */
		add_filter( 'cron_schedules', [ __CLASS__, 'add_schedules' ], 100 );
		add_action( 'fka_clear_duplicate_actions', array( __CLASS__, 'remove_duplicate_actions' ) );
	}

	public static function display_marketing_optin_checkbox() {
		/** check for woocommerce active **/
		if ( ! function_exists( 'bwfan_is_woocommerce_active' ) || false === bwfan_is_woocommerce_active() ) {
			return;
		}

		/** check for woocommerce checkout page **/
		if ( ! is_checkout() && ! wp_doing_ajax() ) {
			return;
		}

		$general_options = self::get_global_settings();
		/** showing consent text on checkout page */
		if ( isset( $general_options['bwfan_user_consent_position'] ) && 'below_term' === $general_options['bwfan_user_consent_position'] ) {
			add_action( 'woocommerce_checkout_after_terms_and_conditions', function () {
				self::add_user_consent_after_terms_and_conditions();
			} );
		} elseif ( isset( $general_options['bwfan_user_consent_position'] ) && 'below_phone' === $general_options['bwfan_user_consent_position'] ) {
			/** Below Phone */
			add_filter( 'woocommerce_form_field', function ( $field, $key, $args, $value ) {
				if ( 'billing_phone' === $key ) {
					$field_priority = $args['priority'] ? $args['priority'] : '';
					$field          .= self::add_user_consent_after_terms_and_conditions( true, $field_priority );
				}

				return $field;
			}, 99, 4 );
		} else {
			/** Below Email */
			add_filter( 'woocommerce_form_field', function ( $field, $key, $args, $value ) {
				if ( 'billing_email' === $key ) {
					$field_priority = $args['priority'] ? $args['priority'] : '';
					$field          .= self::add_user_consent_after_terms_and_conditions( true, $field_priority );
				}

				return $field;
			}, 99, 4 );
		}
	}

	public static function get_global_settings() {
		$global_settings = get_option( 'bwfan_global_settings', array() );
		$global_settings = self::override_non_changeable_settings( $global_settings );
		$global_settings = wp_parse_args( $global_settings, self::get_default_global_settings( $global_settings ) );

		return apply_filters( 'bwfan_get_global_settings', $global_settings );
	}

	/** Deleting from in-db settings, as those were old Shortcodes saved before the update */
	public static function override_non_changeable_settings( $global_settings ) {
		if ( ! is_array( $global_settings ) ) {
			return $global_settings;
		}
		unset( $global_settings['bwfan_unsubscribe_button'] );
		unset( $global_settings['bwfan_subscriber_recipient'] );
		unset( $global_settings['bwfan_subscriber_name'] );

		return $global_settings;
	}

	/**
	 * fetching the providers from the array
	 *
	 * @param $providers
	 * @param $default
	 *
	 * @return false|mixed|string
	 */
	public static function get_provider_value( $providers, $default = '' ) {
		if ( ! empty( $default ) && isset( $providers[ $default ] ) ) {
			return $default;
		}

		$providers = array_keys( $providers );

		return end( $providers );
	}


	public static function get_default_global_settings( $global_settings = [] ) {
		$email_settings = self::get_global_email_settings( $global_settings );

		$defaults = array_replace( array(
			'bwfan_ac_b_s'                                 => 25,
			'bwfan_ac_t_l'                                 => 30,
			'bwfan_unsubscribe_button'                     => "[wfan_unsubscribe_button label=__('Update my preference','wp-marketing-automations')]",
			'bwfan_subscriber_recipient'                   => '[wfan_contact_email]',
			'bwfan_subscriber_name'                        => '[wfan_contact_name]',
			'bwfan_unsubscribe_email_label'                => __( 'Unsubscribe', 'wp-marketing-automations' ),
			'bwfan_unsubscribe_data_success'               => __( 'Your subscription preference has been updated.', 'wp-marketing-automations' ),
			'bwfan_email_service'                          => self::get_default_email_provider(),
			'bwfan_sms_service'                            => self::get_default_sms_provider(),
			'bwfan_shortener_service'                      => self::get_default_shortener_provider(),
			'bwfan_whatsapp_service'                       => self::get_default_whatsapp_provider(),
			'bwfan_sandbox_mode'                           => false,
			'bwfan_make_logs'                              => 0,
			'bwfan_ab_enable'                              => 0,
			'bwfan_ab_exclude_users_cart_tracking'         => 0,
			'bwfan_ab_exclude_emails'                      => '',
			'bwfan_ab_exclude_roles'                       => array(),
			'bwfan_ab_init_wait_time'                      => 15,
			'bwfan_disable_abandonment_days'               => 15,
			'bwfan_ab_track_on_add_to_cart'                => 0,
			'bwfan_ab_email_consent'                       => 0,
			'bwfan_ab_mark_lost_cart'                      => 15,
			'bwfan_order_tracking_conversion'              => 15,
			'bwfan_ab_restore_cart_message_success'        => '',
			'bwfan_ab_restore_cart_message_failure'        => __( 'Your cart could not be restored, it may have expired.', 'wp-marketing-automations' ),
			'bwfan_ab_email_consent_message'               => __( 'Your email and cart are saved so we can send you email reminders about this order. {{no_thanks label="No Thanks"}}', 'wp-marketing-automations' ),
			'bwfan_user_consent'                           => 0,
			'bwfan_user_consent_message'                   => __( 'Keep me up to date on news and exclusive offers.', 'wp-marketing-automations' ),
			'bwfan_user_consent_eu'                        => '1',
			'bwfan_user_consent_non_eu'                    => '0',
			'bwfan_delete_autonami_generated_coupons_time' => 1,
			'bwfan_user_consent_position'                  => 'below_term',
			'bwfan_email_footer_setting'                   => '<p><span style="font-size: 14px; font-family: arial, helvetica, sans-serif;">{{business_name}}, {{business_address}}</span><br /><span style="font-size: 14px; font-family: arial, helvetica, sans-serif;">Don\'t want to stay in the loop? We\'ll be sad to see you go, but you can click here to <a href="{{unsubscribe_link}}">unsubscribe</a></span></p>',
			'bwfan_sms_unsubscribe_text'                   => 'Reply STOP to unsubscribe',
			'bwfan_bounce_select'                          => '',
			'bwfan_unsubscribe_page'                       => '',
			'bwfan_unsubscribe_from_all_label'             => __( 'Unsubscribe from all Email Lists', 'wp-marketing-automations' ),
			'bwfan_unsubscribe_from_all_description'       => __( 'You will still receive important billing and transactional emails', 'wp-marketing-automations' ),
			'after_confirmation_type'                      => 'show_message',
			'bwfan_confirmation_message'                   => '<h2>Subscription Confirmed</h2><p>Your subscription to our list has been confirmed.</p><p>Thank you for subscribing!</p><p>&nbsp;</p>'
		), $email_settings );

		if ( self::is_whatsapp_services_enabled() ) {
			$defaults['bwfan_whatsapp_gap_btw_message'] = 1;
			$services                                   = BWFCRM_Core()->conversation->get_whatsapp_services();
			if ( ! empty( $services ) ) {
				$defaults['bwfan_primary_whats_app_service'] = [
					[
						'key'   => $services[0]['value'],
						'label' => $services[0]['label']
					]
				];
			}
		}

		if ( true === apply_filters( 'bwfan_ab_delete_inactive_carts', false ) ) {
			$defaults['bwfan_ab_remove_inactive_cart_time'] = 30;
		}

		return apply_filters( 'bwfan_default_global_settings', $defaults );
	}

	public static function get_global_email_settings( $global = [] ) {
		$global_settings = array();

		/** Email Settings */
		if ( ! isset( $global['bwfan_email_from'] ) || empty( $global['bwfan_email_from'] ) ) {
			if ( bwfan_is_woocommerce_active() ) {
				$global_settings['bwfan_email_from'] = get_option( 'woocommerce_email_from_address' );
				$global_settings['bwfan_email_from'] = sanitize_email( $global_settings['bwfan_email_from'] );
			} else {
				$global_settings['bwfan_email_from'] = sanitize_email( get_bloginfo( 'admin_email' ) );
			}
		} else {
			$global_settings['bwfan_email_from'] = $global['bwfan_email_from'];
		}

		if ( ! isset( $global['bwfan_email_from_name'] ) || empty( $global['bwfan_email_from_name'] ) ) {
			if ( bwfan_is_woocommerce_active() ) {
				$global_settings['bwfan_email_from_name'] = get_option( 'woocommerce_email_from_name' );
				$global_settings['bwfan_email_from_name'] = wp_specialchars_decode( esc_html( $global_settings['bwfan_email_from_name'] ), ENT_QUOTES );
			} else {
				$global_settings['bwfan_email_from_name'] = wp_specialchars_decode( esc_html( get_bloginfo( 'name' ) ), ENT_QUOTES );
			}
		} else {
			$global_settings['bwfan_email_from_name'] = $global['bwfan_email_from_name'];
		}

		$global_settings['bwfan_email_reply_to']         = $global_settings['bwfan_email_from'];
		$global_settings['bwfan_email_per_second_limit'] = 15;
		$global_settings['bwfan_email_daily_limit']      = 10000;

		return array(
			'bwfan_email_from'             => $global_settings['bwfan_email_from'],
			'bwfan_email_from_name'        => $global_settings['bwfan_email_from_name'],
			'bwfan_email_reply_to'         => $global_settings['bwfan_email_reply_to'],
			'bwfan_email_per_second_limit' => $global_settings['bwfan_email_per_second_limit'],
			'bwfan_email_daily_limit'      => $global_settings['bwfan_email_daily_limit'],
		);
	}

	/**
	 * fetching the default email provider
	 * @return false|mixed|string
	 */
	public static function get_default_email_provider() {
		$default   = apply_filters( 'bwfan_default_email_services', 'wp' );
		$providers = self::get_email_services();

		return self::get_provider_value( $providers, $default );
	}

	public static function get_email_services() {
		$services = apply_filters( 'bwfan_email_services', array() );

		return ! empty( $services ) ? $services : array();
	}

	/**
	 * fetching default sms provider
	 * @return false|mixed|string
	 */
	public static function get_default_sms_provider() {
		$default   = apply_filters( 'bwfan_default_sms_services', 'bwfco_twilio' );
		$providers = self::get_sms_services();

		return self::get_provider_value( $providers, $default );
	}

	public static function get_sms_services() {
		$services = apply_filters( 'bwfan_sms_services', array() );

		return ! empty( $services ) ? $services : array();
	}

	/**
	 * it fetches the default shortener provider
	 * @return int|string
	 */
	public static function get_default_shortener_provider() {
		$default   = apply_filters( 'bwfan_default_shortener_services', '' );
		$providers = self::get_shortener_services();

		return self::get_provider_value( $providers, $default );
	}

	/**
	 * it fetches the shortener services and also have the filter to add new shortener services
	 * @return array|void
	 */
	public static function get_shortener_services() {
		$services = apply_filters( 'bwfan_shortener_services', array() );

		return ! empty( $services ) ? $services : array();
	}

	/**
	 * fetching the default whatsapp service
	 *
	 * @return false|mixed|string
	 */
	public static function get_default_whatsapp_provider() {
		$default   = apply_filters( 'bwfan_default_whatsapp_services', '' );
		$providers = self::get_whatsapp_services();

		return self::get_provider_value( $providers, $default );
	}

	/**
	 * fetching all the whatsapp services available and active
	 *
	 * @return array|mixed|void
	 */
	public static function get_whatsapp_services() {
		$services = apply_filters( 'bwfan_whatsapp_services', array() );

		return ! empty( $services ) ? $services : array();
	}

	/**
	 * Check for whatsapp services
	 *
	 * @return bool
	 */
	public static function is_whatsapp_services_enabled() {
		if ( ! bwfan_is_autonami_pro_active() || ! class_exists( 'WFCO_Autonami_Connectors_Core' ) || is_null( BWFCRM_Core()->conversation ) ) {
			return false;
		}

		$services = BWFCRM_Core()->conversation->get_whatsapp_services();
		if ( is_array( $services ) && count( $services ) > 0 ) {
			return true;
		}

		return false;
	}

	public static function add_user_consent_after_terms_and_conditions( $return = false, $field_priority = '' ) {
		$global_settings = self::get_global_settings();

		$marketing_status = 0;
		if ( empty( $global_settings['bwfan_user_consent'] ) ) {
			$marketing_status = 1;
		}

		if ( empty( $marketing_status ) && is_user_logged_in() ) {
			$user        = wp_get_current_user();
			$bwf_contact = bwf_get_contact( $user->ID, $user->user_email );
			$status      = self::get_contact_status( $bwf_contact );
			/** Check contact's marketing status, email and sms status */
			if ( 1 === absint( $status['status'] ) && 1 === absint( $status['email_status'] ) && 1 === absint( $status['sms_status'] ) ) {
				$marketing_status = 1;
			}
		}

		$country_code            = self::maybe_get_user_country_code();
		$tax_supported_countries = WC()->countries->get_european_union_countries();
		$check                   = in_array( $country_code, $tax_supported_countries, true );

		if ( true === $check ) {
			$checked = 'checked';
			if ( empty( $global_settings['bwfan_user_consent_eu'] ) ) {
				$checked = '';
			}
		} else {
			$checked = 'checked';
			if ( empty( $global_settings['bwfan_user_consent_non_eu'] ) ) {
				$checked = '';
			}
		}

		$user_consent_message = self::get_user_consent_message_in_site_language( $global_settings );

		if ( ! $return ) {
			if ( 1 === $marketing_status ) {
				echo '<input id="bwfan_user_consent" name="bwfan_user_consent" value="1" type="hidden" />';

				return;
			}
			echo '<p class="bwfan_user_consent wfacp-form-control-wrapper wfacp-col-full wfacp-consent-term-condition form-row">';
			echo '<label for="bwfan_user_consent" class="checkbox">';
			echo '<input id="bwfan_user_consent" name="bwfan_user_consent" type="checkbox" value="1" ' . esc_html( $checked ) . ' />';
			echo wp_kses_post( $user_consent_message );
			echo '</label>';
			echo '</p>';
		} else {
			if ( 1 === $marketing_status ) {
				return '<input id="bwfan_user_consent" name="bwfan_user_consent" value="1" type="hidden" />';
			}
			$field_priority = ! empty( $field_priority ) ? 'data-priority="' . ( absint( $field_priority ) + 5 ) . '"' : '';
			$return         = '<p class="bwfan_user_consent wfacp-form-control-wrapper wfacp-col-full wfacp-consent-term-condition form-row" ' . $field_priority . '>';
			$return         .= '<label for="bwfan_user_consent" class="checkbox">';
			$return         .= '<input id="bwfan_user_consent" name="bwfan_user_consent" type="checkbox" value="1" ' . esc_html( $checked ) . ' />';
			$return         .= wp_kses_post( $user_consent_message );
			$return         .= '</label>';
			$return         .= '</p>';

			return $return;
		}
	}

	/**
	 * getting checkout consent message in site language
	 *
	 * @param $global_settings
	 *
	 * @return mixed
	 */
	public static function get_user_consent_message_in_site_language( $global_settings ) {
		$site_language = self::get_site_current_language();
		$site_language = ! empty( $site_language ) ? $site_language : get_locale();

		$message_index = 'bwfan_user_consent_message_' . $site_language;

		return isset( $global_settings[ $message_index ] ) && ! empty( $global_settings[ $message_index ] ) ? $global_settings[ $message_index ] : $global_settings['bwfan_user_consent_message'];
	}

	public static function get_site_current_language() {

		/** WPML */
		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			global $sitepress;

			return $sitepress->get_current_language();
		}

		/** Polylang */
		if ( function_exists( 'pll_the_languages' ) ) {
			return pll_current_language( 'locale' );
		}

		/** TranslatePress **/
		if ( bwfan_is_translatepress_active() ) {
			global $TRP_LANGUAGE;

			return $TRP_LANGUAGE;
		}

		/** Weglot */
		if ( defined( 'BWFAN_VERSION' ) && version_compare( BWFAN_VERSION, '2.0.2', '>' ) && function_exists( 'bwfan_is_weglot_active' ) && bwfan_is_weglot_active() ) {
			$request_url_service = weglot_get_request_url_service();
			$lang_obj            = $request_url_service->get_current_language();
			$current_language    = $lang_obj->getExternalCode();
			$current_language    = empty( $current_language ) ? $lang_obj->getInternalCode() : $current_language;

			return $current_language;
		}
	}

	/**
	 * may be get user country from woocommerce session
	 * @return array|mixed|string
	 */
	public static function maybe_get_user_country_code() {

		if ( ! is_null( WC()->session ) ) {
			$country_code = WC()->session->get( 'bwfan_user_checkout_country', '' );
			if ( ! empty( $country_code ) ) {
				return $country_code;
			}

			$country_code = self::get_user_country();
			WC()->session->set( 'bwfan_user_checkout_country', $country_code );

			return $country_code;

		}

		$country_code = self::get_user_country();

		return $country_code;
	}

	/** get user country
	 * @return mixed
	 */
	public static function get_user_country() {
		$user_location = WC_Geolocation::geolocate_ip();
		$country_code  = $user_location['country'];

		return $country_code;
	}

	/**
	 * Restrict product link display for product layouts in emails
	 *
	 * @return mixed|void
	 */
	public static function disable_product_link() {
		return apply_filters( 'bwfan_disable_product_link', false );
	}

	/**
	 * Restrict thumbnails display for product layouts in emails
	 *
	 * @return mixed|void
	 */
	public static function disable_product_thumbnail() {
		return apply_filters( 'bwfan_disable_product_thumbnail', false );
	}

	/**
	 * @param DateTime $datetime
	 */
	public static function convert_to_gmt( $datetime ) {
		$datetime->modify( '-' . self::get_timezone_offset() * HOUR_IN_SECONDS . ' seconds' );
	}

	public static function get_timezone_offset() {
		$timezone = get_option( 'timezone_string' );
		if ( $timezone ) {
			$timezone_object = new DateTimeZone( $timezone );

			return $timezone_object->getOffset( new DateTime( 'now' ) ) / HOUR_IN_SECONDS;
		} else {
			return floatval( get_option( 'gmt_offset', 0 ) );
		}
	}

	public static function convert_to_site_time( $date ) {
		return self::convert_from_gmt( $date );
	}

	/**
	 * @param $datetime DateTime
	 *
	 * @return mixed
	 */
	public static function convert_from_gmt( $datetime ) {
		return $datetime->modify( '+' . self::get_timezone_offset() * HOUR_IN_SECONDS . ' seconds' );
	}

	/**
	 * @param $screen_type
	 *
	 * @return bool
	 */
	public static function is_load_admin_assets( $screen_type = 'single' ) {
		$page = filter_input( INPUT_GET, 'page' );
		if ( empty( $page ) ) {
			return false;
		}
		if ( 'all' === $screen_type ) {
			$is_autonami = ( false !== strpos( $page, 'autonami' ) );
			if ( $page === 'autonami' || $is_autonami ) {
				return true;
			}
		} elseif ( 'builder' === $screen_type ) {
			if ( $page === 'autonami-automations' && filter_input( INPUT_GET, 'edit' ) > 0 ) {
				return true;
			}
		} elseif ( 'all' === $screen_type || 'builder' === $screen_type ) {
			if ( $page === 'autonami-automations' && filter_input( INPUT_GET, 'edit' ) > 0 ) {
				return true;
			}
		} elseif ( 'all' === $screen_type || 'settings' === $screen_type ) {
			if ( $page === 'autonami-settings' || false !== strpos( filter_input( INPUT_GET, 'path' ), 'settings' ) ) {
				return true;
			}
		} elseif ( 'automation' === $screen_type ) {
			if ( $page === 'autonami-automations' && filter_input( INPUT_GET, 'edit' ) > 0 ) {
				return true;
			}
		} elseif ( 'recipe' === $screen_type ) {
			if ( $page === 'autonami-automations' && filter_input( INPUT_GET, 'tab' ) === 'recipe' ) {
				return true;
			}
		}
		$screen = get_current_screen();

		return apply_filters( 'bwfan_enqueue_scripts', false, $screen_type, $screen );
	}

	public static function array_flatten( $array ) {
		if ( ! is_array( $array ) ) {
			return false;
		}
		$result = iterator_to_array( new RecursiveIteratorIterator( new RecursiveArrayIterator( $array ) ), false );

		return $result;
	}

	public static function pr( $arr ) {
		echo '<pre>';
		print_r( $arr ); //phpcs:ignore WordPress.PHP.DevelopmentFunctions
		echo '</pre>';
	}

	public static function pc( $val1, $val2 = '' ) {
		if ( ! class_exists( 'pc' ) ) {
			return;
		}
		pc::debug( $val1, $val2 ); //phpcs:ignore WordPress.PHP.DevelopmentFunctions
	}

	/**
	 * Slug-ify the class name and remove underscores and convert it to filename
	 * Helper function for the auto-loading
	 *
	 * @param $class_name
	 *
	 * @return mixed|string
	 * @see BWFAN_Gateways::integration_autoload();
	 */
	public static function slugify_classname( $class_name ) {
		$classname = sanitize_title( $class_name );
		$classname = str_replace( '_', '-', $classname );

		return $classname;
	}

	public static function maybe_convert_html_tag( $val ) {
		if ( false === is_string( $val ) ) {
			return $val;
		}
		$val = str_replace( '&lt;', '<', $val );
		$val = str_replace( '&gt;', '>', $val );

		return $val;
	}

	public static function string2hex( $string ) {
		$hex = '';
		for ( $i = 0; $i < strlen( $string ); $i ++ ) {
			$hex .= dechex( ord( $string[ $i ] ) );
		}

		return $hex;
	}

	/**
	 * Return sidebar options on single automation screen.
	 *
	 * @return mixed|void
	 */
	public static function get_sidebar_menu() {
		$sidebar_menu = array(

			'20' => array(
				'icon' => 'dashicons dashicons-networking',
				'name' => __( 'Automation', 'wp-marketing-automations' ),
				'key'  => 'automation',
			),
			'50' => array(
				'icon' => 'dashicons dashicons-admin-tools',
				'name' => __( 'Tools', 'wp-marketing-automations' ),
				'key'  => 'tools',
			),
		);

		return apply_filters( 'bwfan_builder_menu', $sidebar_menu );
	}

	/**
	 * Checks if the current page is autonami page or not.
	 *
	 * @return bool
	 */
	public static function is_autonami_page() {
		if ( isset( $_GET['page'] ) && 'autonami-automations' === sanitize_text_field( $_GET['page'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification
			return true;
		}

		return false;
	}

	/**
	 * Remove autonami events on plugin deactivation.
	 */
	public static function deactivation() {
		if ( bwf_has_action_scheduled( 'bwfan_run_queue' ) ) {
			bwf_unschedule_actions( 'bwfan_run_queue' );
		}
		if ( bwf_has_action_scheduled( 'bwfan_check_abandoned_carts' ) ) {
			bwf_unschedule_actions( 'bwfan_check_abandoned_carts' );
		}
		if ( bwf_has_action_scheduled( 'bwfan_delete_expired_autonami_coupons' ) ) {
			bwf_unschedule_actions( 'bwfan_delete_expired_autonami_coupons' );
		}
		if ( bwf_has_action_scheduled( 'bwfan_mark_abandoned_lost_cart' ) ) {
			bwf_unschedule_actions( 'bwfan_mark_abandoned_lost_cart' );
		}
		if ( bwf_has_action_scheduled( 'bwfan_delete_old_abandoned_carts' ) ) {
			bwf_unschedule_actions( 'bwfan_delete_old_abandoned_carts' );
		}
	}

	/**
	 * Send a remote call.
	 *
	 * @param $api_url
	 * @param $data
	 * @param string $method_type
	 *
	 * @return array|mixed|object|string|null
	 */
	public static function send_remote_call( $api_url, $data, $method_type = 'post' ) {
		if ( 'get' === $method_type ) {
			$httpPostRequest = self::http()->get( $api_url, array(
				'body'      => $data,
				'sslverify' => false,
				'timeout'   => 30,
			) );
		} else {
			$httpPostRequest = self::http()->post( $api_url, array(
				'body'      => $data,
				'sslverify' => false,
				'timeout'   => 30,
			) );
		}

		if ( isset( $httpPostRequest->errors ) ) {
			$response = null;
		} elseif ( isset( $httpPostRequest['body'] ) && '' !== $httpPostRequest['body'] ) {
			$body     = $httpPostRequest['body'];
			$response = json_decode( $body, true );
		} else {
			$response = 'No result';
		}

		return $response;
	}

	public static function http() {
		if ( null === self::$http ) {
			self::$http = new WP_Http();
		}

		return self::$http;
	}

	/**
	 * Return all the merge tags from a string.
	 *
	 * @param $text
	 *
	 * @return array|null
	 */
	public static function get_merge_tags_from_text( $text ) {
		$merge_tags      = null;
		$more_merge_tags = null;
		if ( ! is_array( $text ) ) {
			preg_match_all( '/\{{(.*?)\}}/', $text, $more_merge_tags );
		}

		if ( is_array( $more_merge_tags[1] ) && count( $more_merge_tags[1] ) > 0 ) {
			$merge_tags = $more_merge_tags[1];
		}

		return $merge_tags;
	}

	/**
	 * Return the merge tags which will behave as array.
	 *
	 * @param $merge_tags
	 * @param $integration_merge_tags
	 * @param $action_data
	 *
	 * @return array
	 */
	public static function initial_parse_merge_tags( $merge_tags, $integration_merge_tags, $action_data ) {
		$dynamic_array = array();
		if ( ! is_array( $action_data ) || count( $action_data ) === 0 ) {
			return $dynamic_array;
		}
		foreach ( $action_data as $key1 => $value1 ) {
			if ( ! is_array( $value1 ) || count( $value1 ) === 0 ) {
				$dynamic_array[ $key1 ] = $value1;
				continue;
			}

			foreach ( $value1 as $key2 => $value2 ) {
				if ( ! in_array( $key2, $merge_tags, true ) || ( ! is_array( $integration_merge_tags ) || ! in_array( $key2, $integration_merge_tags, true ) ) ) {
					$dynamic_array[ $key2 ] = $value2;
					continue;
				}

				if ( isset( $dynamic_array[ $key2 ] ) ) {
					array_push( $dynamic_array[ $key2 ], $value2 );
				} else {
					$dynamic_array[ $key2 ] = array( $value2 );
				}
			}
		}

		return $dynamic_array;
	}

	public static function filter_tasks( $all_tasks, $all_tasks_meta ) {
		$result = array();
		foreach ( $all_tasks_meta as $value1 ) {
			if ( isset( $value1['bwfan_task_id'] ) ) {
				$id = $value1['bwfan_task_id'];
			} elseif ( isset( $value1['bwfan_log_id'] ) ) {
				$id = $value1['bwfan_log_id'];
			}
			if ( isset( $all_tasks[ $id ] ) ) {
				$result['all_tasks'][ $id ] = array(
					$all_tasks[ $id ]['integration_slug'] => $all_tasks[ $id ]['integration_action'],
				);
				if ( 'integration_data' === $value1['meta_key'] ) {
					$meta                            = maybe_unserialize( $value1['meta_value'] );
					$meta['automation_id']           = $all_tasks[ $id ]['automation_id'];
					$result['all_tasks_meta'][ $id ] = $meta;
				}
				$result['all_tasks_status'][ $id ]        = $all_tasks[ $id ]['status'];
				$result['all_tasks_automation_id'][ $id ] = $all_tasks[ $id ]['automation_id'];
				$result['all_tasks_attempts'][ $id ]      = $all_tasks[ $id ]['attempts'];
			}
		}

		return $result;
	}

	/**
	 * Remove backslashes from $_POST content of the automation.
	 *
	 * @param $posted_data
	 *
	 * @return array
	 */
	public static function remove_back_slash_from_automation( $posted_data ) {
		if ( ! is_array( $posted_data ) || count( $posted_data ) === 0 ) {
			return $posted_data;
		}
		foreach ( $posted_data as $key1 => $value1 ) {
			if ( isset( $value1['ajax_data'] ) ) {
				$posted_data[ $key1 ]['ajax_data'] = self::remove_backslashes( $value1['ajax_data'] );
			}
			if ( isset( $value1['data']['field_value'] ) && ! is_array( $value1['data']['field_value'] ) ) {
				$posted_data[ $key1 ]['data']['field_value'] = self::remove_newlines( self::remove_backslashes( $value1['data']['field_value'] ) );
			}
		}

		return $posted_data;
	}

	public static function remove_backslashes( $string ) {
		return preg_replace( '/\\\\/', '', $string );
	}

	public static function remove_newlines( $string ) {
		return trim( preg_replace( '/\s+/', ' ', $string ) );
	}

	/**
	 * Get all the merge tags of all the events.
	 *
	 * @param $all_sources
	 *
	 * @return array
	 */
	public static function get_all_events_merge_tags() {
		$all_events_merge_tags = array();
		$merge_tags            = BWFAN_Core()->merge_tags->get_localize_tags_with_source();
		$source_events         = BWFAN_Core()->sources->get_events();

		$merge_tags = self::get_v1_supported_mergetag( $merge_tags );

		/**
		 * @var $event_object BWFAN_Event
		 */
		foreach ( $source_events as $event_key => $event_object ) {
			$event_merge_tags = $event_object->get_merge_tag_groups();
			if ( empty( $event_merge_tags ) ) {
				continue;
			}

			$curr_event_merge_tags = array();
			foreach ( $event_merge_tags as $head ) {
				if ( ! isset( $merge_tags[ $head ] ) ) {
					continue;
				}
				$curr_event_merge_tags[ $head ] = $merge_tags[ $head ];
			}
			$all_events_merge_tags[ $event_key ] = apply_filters( 'bwfan_default_merge_tags', $curr_event_merge_tags );
		}

		return $all_events_merge_tags;
	}

	/** get the supported v1 merge tag */
	public static function get_v1_supported_mergetag( $all_merge_tags ) {
		if ( empty( $all_merge_tags ) ) {
			return $all_merge_tags;
		}
		/** Filter v1 merge tags */
		foreach ( $all_merge_tags as $mergeGroup => $mergeTagList ) {
			if ( empty( $mergeTagList ) ) {
				continue;
			}
			$final_Arr = [];
			foreach ( $mergeTagList as $mergeTagKey => $mergeTagData ) {
				/** check if the merge tag not support v1 */
				if ( isset( $mergeTagData['support_v1'] ) && empty( $mergeTagData['support_v1'] ) ) {
					unset( $all_merge_tags[ $mergeGroup ][ $mergeTagKey ] );
					continue;
				}
				$final_Arr[ $mergeTagKey ] = $mergeTagData;
			}
			if ( ! empty( $final_Arr ) ) {
				$all_merge_tags[ $mergeGroup ] = $final_Arr;
			}
		}

		return $all_merge_tags;
	}

	/**
	 * @param $all_sources
	 *
	 * @return array
	 */
	public static function get_all_events_rules() {
		$all_rules_groups = array();
		$events           = BWFAN_Core()->sources->get_events();
		if ( empty( $events ) ) {
			return $all_rules_groups;
		}
		/**
		 * @var $event BWFAN_Event
		 */
		foreach ( $events as $slug => $event ) {
			$all_rules_groups[ $slug ] = array_merge( $event->get_rule_group(), BWFAN_Core()->rules->get_default_rule_groups() );
		}

		return $all_rules_groups;
	}

	public static function sort_automations( $all_automations ) {
		$all_automations_temp = array();
		$nice_names           = array();

		if ( is_array( $all_automations ) && count( $all_automations ) > 0 ) {
			foreach ( $all_automations as $int_slug => $int_obj ) {
				$nice_names[] = $int_obj->get_name();
			}
			asort( $nice_names );

			foreach ( $nice_names as $int_nice_name ) {
				foreach ( $all_automations as $int_slug => $int_obj ) {
					if ( $int_nice_name === $int_obj->get_name() ) {
						$all_automations_temp[ $int_slug ] = $int_obj;
					}
				}
			}

			$all_automations = $all_automations_temp;
		}

		return $all_automations;
	}

	/**
	 * Save integration data for a connector.
	 *
	 * @param $data
	 * @param $slug
	 * @param $status
	 *
	 * @return int
	 */
	public static function save_integration_data( $data, $slug, $status ) {
		$new_task_data                     = array();
		$new_task_data['last_sync']        = current_time( 'timestamp', 1 );
		$new_task_data['integration_slug'] = $slug;
		$new_task_data['api_data']         = maybe_serialize( $data );
		$new_task_data['status']           = $status;
		BWFAN_Model_Settings::insert( $new_task_data );

		return BWFAN_Model_Settings::insert_id();
	}

	/**
	 * Update integration data for a connector.
	 *
	 * @param $data
	 * @param $id
	 */
	public static function update_integration_data( $data, $id ) {
		$meta_data              = array();
		$meta_data['api_data']  = maybe_serialize( $data );
		$meta_data['last_sync'] = current_time( 'timestamp', 1 );
		$where                  = array(
			'ID' => $id,
		);
		BWFAN_Model_Settings::update( $meta_data, $where );
	}

	public static function get_parsed_time( $wp_date_format, $logs ) {
		$logs_temp = array();

		$logs_temp[ date( 'Y-m-d H:i:s' ) ] = __( 'Error in generating logs', 'wp-marketing-automations' );
		if ( ! is_array( $logs ) || count( $logs ) === 0 ) {
			return array_reverse( $logs_temp, true );
		}

		$logs_temp = array();
		foreach ( $logs as $timestamp => $message ) {
			$time = get_date_from_gmt( date( 'Y-m-d H:i:s', $timestamp ), $wp_date_format );
			if ( empty( $message ) ) {
				$message = __( 'No response from API', 'wp-marketing-automations' );
			} else {
				$message = str_replace( "'", '', $message );
			}
			$logs_temp[ $time ] = $message;
		}

		return array_reverse( $logs_temp, true );
	}

	public static function string( $string ) {
		return sanitize_text_field( $string );
	}

	public static function add_default_merge_tags( $event_merge_tags ) {
		$default_merge_tags = self::get_default_merge_tags( false );
		foreach ( $default_merge_tags as $merge_tag => $details ) {
			$event_merge_tags[ $merge_tag ] = $details[0];
		}

		return $event_merge_tags;
	}

	public static function get_default_merge_tags( $load_values ) {
		$current_date      = null;
		$current_time      = null;
		$current_date_time = null;

		if ( $load_values ) {
			$cdt               = self::$date_format . ' ' . self::$time_format;
			$ct                = self::$time_format;
			$cd                = self::$date_format;
			$current_date_time = get_date_from_gmt( date( 'Y-m-d H:i:s' ), $cdt );
			$current_time      = get_date_from_gmt( date( 'H:i:s' ), $ct );
			$current_date      = get_date_from_gmt( date( 'Y-m-d' ), $cd );
		}
		$default_merge_tags = array(
			'admin_email'       => array( __( 'Admin email', 'wp-marketing-automations' ), self::$admin_email ),
			'current_date'      => array( __( 'Current date when task will be executed', 'wp-marketing-automations' ), $current_date ),
			'current_time'      => array( __( 'Current time when task will be executed', 'wp-marketing-automations' ), $current_time ),
			'current_date_time' => array( __( 'Current date and time when task will be executed', 'wp-marketing-automations' ), $current_date_time ),
		);

		return apply_filters( 'bwfan_modify_default_merge_tags', $default_merge_tags );
	}

	public static function parse_default_merge_tags( $data, $recursive = false ) {
		if ( ! is_array( $data ) || count( $data ) === 0 ) {
			return $data;
		}

		$default_merge_tags_values = self::get_default_merge_tags( true );

		/**         *
		 * This function only decode two level array
		 */
		foreach ( $default_merge_tags_values as $merge_tag => $details ) {
			foreach ( $data as $key1 => $value1 ) {
				if ( in_array( gettype( $value1 ), array( 'int', 'boolean' ), true ) ) {
					continue;
				}

				if ( is_array( $value1 ) ) {
					if ( empty( $value1 ) ) {
						continue;
					}
					foreach ( $value1 as $key2 => $value2 ) {
						if ( is_array( $value2 ) ) {
							$data[ $key1 ][ $key2 ] = $value2;
						} else {
							if ( false !== strpos( $value2, '{{' . $merge_tag . '}}' ) ) {
								$data[ $key1 ][ $key2 ] = str_replace( '{{' . $merge_tag . '}}', $details[1], $value2 );
							}
						}
					}
				} else {

					if ( is_object( $value1 ) ) { // ignore if the value is object type in case of leandash events
						continue;
					}

					if ( false !== strpos( $value1, '{{' . $merge_tag . '}}' ) ) {
						$data[ $key1 ] = str_replace( '{{' . $merge_tag . '}}', $details[1], $value1 );
						continue;
					}
				}
			}
		}

		return $data;
	}

	public static function filter_actions_conditions( $selected_actions, $automation_details ) {
		$automation_actions    = $automation_details['actions'];
		$automation_conditions = ( isset( $automation_details['condition'] ) ) ? $automation_details['condition'] : array();
		$temp_actions          = array();
		$temp_conditions       = array();

		foreach ( $selected_actions as $single_indexes ) {
			$single_ind                             = explode( '_', $single_indexes );
			$group_id                               = $single_ind[0];
			$child_id                               = $single_ind[1];
			$temp_actions[ $group_id ][ $child_id ] = $automation_actions[ $group_id ][ $child_id ];
		}

		if ( is_array( $automation_conditions ) && count( $automation_conditions ) > 0 ) {
			foreach ( $temp_actions as $group_id => $actions ) {
				/** Checking if group id not present in the condition */
				if ( ! isset( $automation_conditions[ $group_id ] ) ) {
					continue;
				}
				/** Checking if actions empty */
				if ( ! is_array( $actions ) || 0 === count( $actions ) ) {
					continue;
				}
				/** Checking if action id  condition not exist */
				foreach ( $actions as $action_id => $action_data ) {
					if ( ! isset( $automation_conditions[ $group_id ][ $action_id ] ) ) {
						continue;
					}
					if ( ! is_array( $action_data ) ) {
						continue;
					}
					$temp_conditions[ $group_id ][ $action_id ] = $action_data;
				}
			}
		}

		$automation_details['actions']   = $temp_actions;
		$automation_details['condition'] = $temp_conditions;

		return $automation_details;
	}

	/**
	 *
	 * Check for the user order count , if not found in the usermeta then fires wpdb query
	 *
	 * @param int $user_id
	 *
	 * @return int
	 * @since 2.7.1
	 */
	public static function get_customer_order_count( $user_id, $force = false ) {
		global $wpdb;

		$order_count      = implode( "','", wc_get_order_types( 'order-count' ) );
		$trashed_statuses = implode( "','", self::get_order_trashed_statuses() );

		if ( BWF_WC_Compatibility::is_hpos_enabled() ) {
			$query = $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}wc_orders as orders LEFT JOIN {$wpdb->prefix}wc_orders_meta AS meta ON orders.id = meta.order_id WHERE meta.meta_key= '_customer_user' AND orders.type IN (%s) AND orders.status NOT IN (%s) AND meta.meta_value= %d", $order_count, $trashed_statuses, $user_id );
			$count = $wpdb->get_var( $query );//phpcs:ignore WordPress.DB.PreparedSQL

			if ( empty( $wpdb->last_error ) && ! empty( $count ) ) {
				return intval( $count );
			}
		}

		$query = $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->posts as posts LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id WHERE   meta.meta_key= '_customer_user' AND posts.post_type IN (%s) AND posts.post_status NOT IN (%s) AND meta.meta_value= %d", $order_count, $trashed_statuses, $user_id );

		$count = $wpdb->get_var( $query );//phpcs:ignore WordPress.DB.PreparedSQL

		if ( ! empty( $wpdb->last_error ) ) {
			return 0;
		}

		return intval( $count );
	}

	public static function get_order_trashed_statuses() {
		return apply_filters( 'bwfan_get_order_trashed_statuses', array( 'wc-cancelled', 'wc-refunded', 'wc-failed', 'trash', 'draft' ) );
	}

	public static function get_funnel_data( $funnel_id ) {
		$data                = array();
		$data['funnel_id']   = $funnel_id;
		$funnel_details      = get_post( $funnel_id );
		$data['funnel_name'] = $funnel_details->post_title;

		return $data;
	}

	public static function get_offer_data( $offer_id ) {
		$data               = array();
		$data['offer_id']   = $offer_id;
		$offer_details      = get_post( $offer_id );
		$data['offer_name'] = $offer_details->post_title;
		$data['offer_type'] = get_post_meta( $offer_id, '_offer_type', true );

		return $data;
	}

	/**
	 * Return subscription products by searched term.
	 *
	 * @param $searched_term
	 *
	 * @return array
	 * @throws Exception
	 */
	public static function get_subscription_product( $searched_term ) {
		$subscription_products = array();
		$results               = array();
		$term                  = $searched_term;
		$include_variations    = true;
		$data_store            = WC_Data_Store::load( 'product' );
		$ids                   = $data_store->search_products( $term, '', (bool) $include_variations );
		$product_objects       = array_filter( array_map( 'wc_get_product', $ids ), 'wc_products_array_filter_readable' );

		foreach ( $product_objects as $product_object ) {
			if ( WC_Subscriptions_Product::is_subscription( $product_object ) ) {
				$results[] = array(
					'id'   => $product_object->get_id(),
					'text' => rawurldecode( $product_object->get_formatted_name() ),
				);
			}
		}
		$subscription_products['results'] = $results;

		return $subscription_products;
	}

	/**
	 * Get membership plans by searched term.
	 *
	 * @param $searched_term
	 *
	 * @return array
	 */
	public static function get_membership_plans( $searched_term ) {
		$membership_plans = array();
		$results          = array();
		$query_params     = array(
			'post_type'      => 'wc_membership_plan',
			'posts_per_page' => - 1,
		);

		if ( '' !== $searched_term ) {
			$query_params['s'] = $searched_term;
		}

		$query = new WP_Query( $query_params );

		if ( $query->found_posts > 0 ) {
			foreach ( $query->posts as $post ) {
				$results[] = array(
					'id'   => $post->ID,
					'text' => $post->post_title,
				);
			}
		}

		$membership_plans['results'] = $results;

		return $membership_plans;
	}

	/**
	 * Get membership names by membership ids.
	 *
	 * @param $membership_plans
	 *
	 * @return array
	 */
	public static function get_membership_pre_data( $membership_plans ) {
		$plans = array();
		if ( is_array( $membership_plans ) && count( $membership_plans ) > 0 ) {
			foreach ( $membership_plans as $id ) {
				$plan_name    = get_the_title( $id );
				$plans[ $id ] = $plan_name;
			}
		}

		return $plans;
	}

	/**
	 * Get subscription names by subscription ids.
	 *
	 * @param $subscription_products
	 *
	 * @return array
	 */
	public static function get_subscription_pre_data( $subscription_products ) {
		$products = array();
		if ( is_array( $subscription_products ) && count( $subscription_products ) > 0 ) {
			foreach ( $subscription_products as $id ) {
				$product         = wc_get_product( $id );
				$product_name    = $product->get_formatted_name();
				$products[ $id ] = $product_name;
			}
		}

		return $products;
	}

	public static function delete_order_meta( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order instanceof WC_Order ) {
			return;
		}

		$order->delete_meta_data( '_bwfan_poid' );
		$order->delete_meta_data( '_bwfan_package' );
		$order->delete_meta_data( '_bwfan_fun_id' );
		$order->save();
	}

	public static function get_sorted_automations( $rows ) {
		$result = array();
		if ( is_array( $rows ) && count( $rows ) > 0 ) {
			foreach ( $rows as $value1 ) {
				$result[ $value1['ID'] ] = $value1;
			}
		}

		return $result;
	}

	public static function get_callable_object( $is_empty, $data ) {
		if ( in_array( 'get_' . $data['type'], self::$select2ajax_functions, true ) ) {
			return array( __CLASS__, 'get_' . $data['type'] );
		} else {
			return $is_empty;
		}
	}

	public static function get_product_image( $product, $size = 'shop_catalog', $only_url = false, $img_width = '' ) {
		$image_id = $product->get_image_id();

		$image_url = '';
		if ( ! empty( $image_id ) ) {
			$image_url = wp_get_attachment_image_url( $image_id, $size );
		}

		if ( empty( $image_url ) && function_exists( 'wc_placeholder_img_src' ) ) {
			$image_url = wc_placeholder_img_src( $size );
		}

		/** Correcting the image URL if not an http link **/
		if ( 'http' !== substr( $image_url, 0, 4 ) ) {
			$image_url = site_url( $image_url );
		}

		if ( $only_url ) {
			$image = $image_url;
		} else {
			$style = ! empty( $img_width ) ? "width='{$img_width}'" : '';
			$image = '<img src="' . $image_url . '" ' . $style . ' class="bwfan-product-image" alt="' . sanitize_text_field( self::get_name( $product ) ) . '">';
		}

		return $image;
	}

	/**
	 * @param $product WC_Product
	 *
	 * @return mixed
	 */
	public static function get_name( $product ) {
		return $product->get_name();
	}

	/**
	 * @param $order WC_Order
	 *
	 * @return array
	 */
	public static function get_order_cross_sells( $order ) {
		$cross_sells = array();
		$in_order    = array();
		$items       = $order->get_items();

		foreach ( $items as $item ) {
			$product     = $item->get_product();
			$in_order[]  = self::is_variation( $product ) ? $product->get_parent_id() : $product->get_id();
			$cross_sells = array_merge( $product->get_cross_sell_ids(), $cross_sells );
		}

		return array_diff( $cross_sells, $in_order );
	}

	public static function is_variation( $product ) {
		return $product->is_type( array( 'variation', 'subscription_variation' ) );
	}

	/**
	 * @param $name
	 *
	 * @return mixed
	 */
	public static function get_cookie( $name ) {
		return isset( $_COOKIE[ $name ] ) ? sanitize_text_field( $_COOKIE[ $name ] ) : false;
	}

	/**
	 * Clear a cookie.
	 *
	 * @param $name
	 */
	public static function clear_cookie( $name ) {
		if ( isset( $_COOKIE[ $name ] ) ) {
			self::set_cookie( $name, '', time() - HOUR_IN_SECONDS );
		}
	}

	/**
	 * Set a cookie - wrapper for setcookie using WP constants.
	 *
	 * @param string $name Name of the cookie being set.
	 * @param string $value Value of the cookie.
	 * @param integer $expire Expiry of the cookie.
	 * @param bool $secure Whether the cookie should be served only over https.
	 * @param bool $httponly Whether the cookie is only accessible over HTTP, not scripting languages like JavaScript. @since 3.6.0.
	 */
	public static function set_cookie( $name, $value, $expire = 0, $secure = false, $httponly = false ) {
		if ( self::is_cli() || self::is_cron() || self::is_rest() ) {
			return;
		}
		if ( headers_sent() ) {
			return;
		}
		setcookie( $name, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure, apply_filters( 'bwfan_cookie_httponly', $httponly, $name, $value, $expire, $secure ) ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.cookies_setcookie
	}

	/**
	 * Checks whether the current request is a WP CLI request
	 *
	 * @return bool
	 */
	public static function is_cli() {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			return true;
		}

		return false;
	}

	/**
	 * Checks whether the current request is a WP cron request
	 *
	 * @return bool
	 */
	public static function is_cron() {
		if ( defined( 'DOING_CRON' ) && true === DOING_CRON ) {
			return true;
		}

		return false;
	}

	/**
	 * Checks whether the current request is a WP rest request
	 *
	 * @return bool
	 */
	public static function is_rest() {
		if ( defined( 'REST_REQUEST' ) && true === REST_REQUEST ) {
			return true;
		}

		return false;
	}

	public static function get_line_subtotal( $item ) {
		return isset( $item['line_subtotal'] ) ? floatval( $item['line_subtotal'] ) : 0;
	}

	/**
	 * @return float
	 */
	public static function get_line_subtotal_tax( $item ) {
		return isset( $item['line_subtotal_tax'] ) ? floatval( $item['line_subtotal_tax'] ) : 0;
	}

	public static function get_quantity( $item ) {
		return isset( $item['quantity'] ) ? absint( $item['quantity'] ) : 0;
	}

	public static function price( $price, $currency = '' ) {
		$args = array( 'currency' => $currency );

		return wc_price( $price, $args );
	}

	/**
	 * Get those coupons which are user made only.
	 *
	 * @param $searched_term
	 *
	 * @return array
	 */
	public static function get_coupon( $searched_term ) {
		$membership_plans = array();
		$results          = array();
		$query_params     = array(
			'post_type'      => 'shop_coupon',
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'     => '_is_bwfan_coupon',
					'compare' => 'NOT EXISTS',
				),
			),
		);

		if ( '' !== $searched_term ) {
			$query_params['s'] = $searched_term;
		}

		$query = new WP_Query( $query_params );

		if ( $query->found_posts > 0 ) {
			foreach ( $query->posts as $post ) {
				$results[] = array(
					'id'   => $post->ID,
					'text' => $post->post_title,
				);
			}
		}

		$membership_plans['results'] = $results;

		return $membership_plans;
	}

	public static function validate_action_date_before_save( $all_actions ) {
		if ( ! is_array( $all_actions ) || 0 === count( $all_actions ) ) {
			return false;
		}

		$modified_actions = $all_actions;

		foreach ( $all_actions as $row_index => $row_actions ) {
			if ( null === $row_actions ) {
				continue;
			}
			if ( ! is_array( $row_actions ) || 0 === count( $row_actions ) ) {
				$modified_actions[ $row_index ] = array();
				continue;
			}

			foreach ( $row_actions as $action_index => $action_details ) {
				if ( isset( $action_details['temp_action_slug'] ) && ! empty( $action_details['temp_action_slug'] ) ) {
					$modified_actions[ $row_index ][ $action_index ]['temp_action_slug'] = '';
				}
			}
		}

		return $modified_actions;
	}

	/**
	 * Sort actions when automation is saved.
	 *
	 * @param $all_actions
	 *
	 * @return array
	 */
	public static function sort_actions( $all_actions ) {
		if ( ! is_array( $all_actions ) || 0 === count( $all_actions ) ) {
			return $all_actions;
		}

		foreach ( $all_actions as $row_index => $row_actions ) {
			if ( null === $row_actions && ! is_array( $row_actions ) || 0 === count( $row_actions ) ) {
				unset( $all_actions[ $row_index ] );
				continue;
			}

			foreach ( $row_actions as $action_index => $action_details ) {
				if ( ! is_array( $action_details ) || 0 === count( $action_details ) ) {
					unset( $all_actions[ $row_index ][ $action_index ] );
				}
			}
		}

		return $all_actions;
	}

	/**
	 * Attach default merge tags to every event.
	 *
	 * @param $all_events_merge_tags
	 * @param $all_merge_tags
	 *
	 * @return array
	 */
	public static function attach_default_merge_to_events( $all_events_merge_tags, $all_merge_tags ) {
		if ( ! is_array( $all_events_merge_tags ) || 0 === count( $all_events_merge_tags ) ) {
			return $all_events_merge_tags;
		}

		foreach ( $all_events_merge_tags as $event_slug => $groups ) {
			if ( ! is_array( $groups ) || 0 === count( $groups ) ) {
				continue;
			}

			$all_events_merge_tags[ $event_slug ]['bwfan_default'] = $all_merge_tags['bwfan_default'];
		}

		return $all_events_merge_tags;
	}

	/**
	 * Get wc products by searched term.
	 *
	 * @param bool $term
	 * @param bool $include_variations
	 * @param bool $return
	 *
	 * @return mixed|void
	 */
	public static function product_search( $term = false, $include_variations = false, $return = false ) {
		self::check_nonce();
		if ( empty( $term ) ) {

			if ( isset( $_POST['term'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$term = stripslashes( sanitize_text_field( $_POST['term'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
			}
		}

		// if ( empty( $term ) ) {
		// 	wp_die();
		// }

		$variations = true;
		if ( true !== $include_variations ) {
			$variations = false;
		}
		$ids = self::search_products( $term, $variations );

		/**
		 * Products types that are allowed in the offers
		 */
		$product_objects = array_filter( array_map( 'wc_get_product', $ids ), 'wc_products_array_filter_editable' );
		$products        = array();
		foreach ( $product_objects as $product_object ) {
			if ( 'pending' === $product_object->get_status() ) {
				continue;
			}
			$products[] = array(
				'id'   => $product_object->get_id(),
				'text' => rawurldecode( self::get_formatted_product_name( $product_object ) ),
			);
		}
		$data = apply_filters( 'bwfan_woocommerce_json_search_found_products', $products );
		if ( true === $return ) {
			return $data;
		}

		wp_send_json( $data );
	}

	/**
	 * Check nonce.
	 */
	public static function check_nonce() {
		$nonce     = ( isset( $_REQUEST['_wpnonce'] ) ) ? sanitize_text_field( $_REQUEST['_wpnonce'] ) : ''; //phpcs:ignore WordPress.Security.NonceVerification
		$bwf_nonce = ( isset( $_REQUEST['bwf_nonce'] ) ) ? sanitize_text_field( $_REQUEST['bwf_nonce'] ) : '';//phpcs:ignore WordPress.Security.NonceVerification

		if ( wp_verify_nonce( $bwf_nonce, 'bwf_secure_key' ) || wp_verify_nonce( $nonce, 'bwfan-action-admin' ) ) {
			return;
		}

		/** check if current user has the permission or not */
		$default_permissions = array( 'manage_options' );
		$permissions         = method_exists( 'BWFAN_Common', 'access_capabilities' ) ? BWFAN_Common::access_capabilities() : $default_permissions;
		foreach ( $permissions as $permission ) {
			if ( current_user_can( $permission ) ) {
				return;
			}
		}
		// This nonce is not valid.
		$resp = array(
			'msg'    => __( 'Invalid request, security validation failed.', 'wp-marketing-automations' ),
			'status' => false,
		);
		wp_send_json( $resp );
	}

	/**
	 * Get wc products by searched term.
	 *
	 * @param $term
	 * @param bool $include_variations
	 *
	 * @return array
	 */
	public static function search_products( $term, $include_variations = false, $limit = 10, $offset = 0 ) {
		self::check_nonce();
		global $wpdb;
		$like_term     = '%' . $wpdb->esc_like( $term ) . '%';
		$post_statuses = current_user_can( 'edit_private_products' ) ? array(
			'private',
			'publish',
			'draft',
		) : array( 'publish', 'draft' );
		$product_type  = [ 'product' ];
		if ( true === $include_variations ) {
			$product_type[] = 'product_variation';
		}
		$query = $wpdb->prepare( "SELECT DISTINCT posts.ID FROM {$wpdb->posts} AS posts LEFT JOIN {$wpdb->wc_product_meta_lookup} AS wc_product_meta_lookup ON posts.ID = wc_product_meta_lookup.product_id WHERE (posts.post_title LIKE %s OR wc_product_meta_lookup.sku LIKE %s OR posts.ID LIKE %s) AND posts.post_status IN ('" . implode( "','", $post_statuses ) . "') AND posts.post_type IN ('" . implode( "','", $product_type ) . "') ORDER BY posts.post_parent ASC, posts.post_title ASC", $like_term, $like_term, $like_term ); //phpcs:ignore WordPress.DB.PreparedSQL,WordPress.DB.PreparedSQLPlaceholders
		if ( ! empty( $limit ) ) {
			$query .= " LIMIT %d, %d ";
			$query = $wpdb->prepare( $query, $offset, $limit );
		}
		$product_ids = $wpdb->get_col( $query );

		if ( is_numeric( $term ) ) {
			$post_id   = absint( $term );
			$post_type = get_post_type( $post_id );

			if ( 'product_variation' === $post_type && $include_variations ) {
				$product_ids[] = $post_id;
			} elseif ( 'product' === $post_type ) {
				$product_ids[] = $post_id;
			}

			$product_ids[] = wp_get_post_parent_id( $post_id );
		}

		return wp_parse_id_list( $product_ids );
	}

	public static function get_formatted_product_name( $product ) {
		$formatted_variation_list = self::get_variation_attribute( $product );
		$arguments                = array();

		if ( ! empty( $formatted_variation_list ) && count( $formatted_variation_list ) > 0 ) {
			foreach ( $formatted_variation_list as $att => $att_val ) {
				if ( '' === $att_val ) {
					$att_val = __( 'any' );
				}
				$att         = strtolower( $att );
				$att_val     = strtolower( $att_val );
				$arguments[] = "$att: $att_val";
			}
		}

		return sprintf( '%s (#%d) %s', $product->get_title(), $product->get_id(), ( count( $arguments ) > 0 ) ? '(' . implode( ',', $arguments ) . ')' : '' );
	}

	public static function get_variation_attribute( $variation ) {
		$variation_attributes = array();
		if ( is_a( $variation, 'WC_Product_Variation' ) ) {
			$variation_attributes = $variation->get_attributes();

		} else {
			if ( is_array( $variation ) ) {
				foreach ( $variation as $key => $value ) {
					$variation_attributes[ str_replace( 'attribute_', '', $key ) ] = $value;
				}
			}
		}

		return ( $variation_attributes );
	}

	public static function array_equal( $a, $b ) {
		return ( is_array( $a ) && is_array( $b ) && count( $a ) === count( $b ) && array_diff( $a, $b ) === array_diff( $b, $a ) ); //phpcs:ignore WordPress.PHP.StrictComparisons
	}

	public static function validate_string_multi( $actual_values, $compare_type, $expected_value ) {
		if ( empty( $expected_value ) ) {
			return false;
		}

		$actual_value = '';
		// look for at least one item that validates the text match
		foreach ( $actual_values as $coupon_code => $coupon_data ) {
			$actual_value = is_string( $coupon_code ) ? $coupon_code : $coupon_data;
			if ( self::validate_string( $actual_value, $compare_type, $expected_value ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param $actual_value
	 * @param $compare_type
	 * @param $expected_value
	 *
	 * @return bool
	 */
	public static function validate_string( $actual_value, $compare_type, $expected_value ) {
		// case insensitive
		$actual_value   = strtolower( (string) $actual_value );
		$expected_value = strtolower( (string) $expected_value );
		$return_status  = false;
		switch ( $compare_type ) {

			case 'is':
				$return_status = ( $actual_value === $expected_value );//phpcs:ignore WordPress.PHP.StrictComparisons
				break;

			case 'is_not':
				$return_status = ( $actual_value !== $expected_value );//phpcs:ignore WordPress.PHP.StrictComparisons
				break;

			case 'contains':
				$return_status = strstr( $actual_value, $expected_value ) !== false;
				break;

			case 'not_contains':
				$return_status = strstr( $actual_value, $expected_value ) === false;
				break;

			case 'starts_with':
				$length = strlen( $expected_value );

				$return_status = substr( $actual_value, 0, $length ) === $expected_value;
				break;

			case 'ends_with':
				$length = strlen( $expected_value );

				if ( 0 === $length ) {
					$return_status = true;
				} else {
					$return_status = substr( $actual_value, - $length ) === $expected_value;
				}
				break;

			case 'blank':
				$return_status = empty( $actual_value );
				break;

			case 'not_blank':
				$return_status = ! empty( $actual_value );
				break;
		}

		return $return_status;
	}

	public static function get_bwf_customer( $email, $wpid ) {
		if ( function_exists( 'bwf_get_contact' ) ) {
			$get_contact = bwf_get_contact( $wpid, $email );

			return bwf_get_customer( $get_contact );
		}

		return null;
	}

	/**
	 * Run the check and update the status.
	 */
	public static function bwfan_run_cron_test( $forced = false ) {
		if ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) {

			$message                    = __( 'The DISABLE_WP_CRON constant is set to true . WP-Cron is disabled and will not run on it\'s own.', 'wp-marketing-automations' );
			$url                        = rest_url( '/woofunnels/v1/worker' ) . '?' . time();
			$message                    .= '<br>' . __( 'Copy following URL and paste it in your Cpanel' );
			$message                    .= '<br><i>' . $url . '</i>';
			$current_version            = BWFAN_VERSION;
			$current_ver                = str_replace( '.', '_', $current_version );
			$version_key                = 'bwfan_version_' . $current_ver;
			$versionArr                 = array();
			$versionArr[ $version_key ] = array(
				'html' => $message,
				'type' => 'wf_error',
			);
			$versionStatus              = WooFunnels_Notifications::get_instance()->get_notification( $version_key, 'bwfan' );

			if ( isset( $versionStatus['error'] ) && $versionStatus['error'] == $version_key . ' Key or Notification group may be Not Available.' ) { //phpcs:ignore WordPress.PHP.StrictComparisons
				$notice_check_in_db = WooFunnels_Notifications::get_instance()->get_dismiss_notification_key( 'bwfan' );
				if ( is_array( $notice_check_in_db ) && false === in_array( $version_key, $notice_check_in_db ) ) {//phpcs:ignore WordPress.PHP.StrictInArray
					WooFunnels_Notifications::get_instance()->register_notification( $versionArr, 'bwfan' );
				}
			}
		}
	}

	/**
	 * Returns the timestamp in the blog's time and format.
	 */
	public static function bwfan_get_datestring( $timestamp = '' ) {
		if ( empty( $timestamp ) ) {
			$timestamp = time();
		}

		return get_date_from_gmt( date( 'Y-m-d H:i:s', $timestamp ), get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
	}

	/**
	 * Get unique actions from a single automation.
	 *
	 * @param $automation_details
	 * @param $selected_actions
	 *
	 * @return array
	 */
	public static function get_automation_selected_action_slugs( $automation_details, $selected_actions ) {
		$automation_actions = $automation_details['meta']['actions'];
		$action_slugs       = array();

		foreach ( $selected_actions as $value1 ) {
			$groups = explode( ',', $value1 );
			foreach ( $groups as $group_actions ) {
				$action_indexes = explode( '_', $group_actions );
				$group_index    = $action_indexes[0];
				$action_index   = $action_indexes[1];
				$action         = $automation_actions[ $group_index ][ $action_index ];
				$action_slugs[] = $action['integration_slug'] . ':' . $action['action_slug'];
			}
		}

		$action_slugs = array_unique( $action_slugs );

		return $action_slugs;
	}

	public static function get_actions_filter_data() {
		return self::get_all_actions_names();
	}

	/**
	 * Get all actions readable names with action slug
	 *
	 * @return array
	 */
	public static function get_all_actions_names() {
		global $wpdb;
		$filter_table           = null;
		$filtered_table_actions = null;

		if ( ( isset( $_GET['tab'] ) && 'tasks' === sanitize_text_field( $_GET['tab'] ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification
			$filter_table = $wpdb->prefix . 'bwfan_tasks';
		}
		if ( ( isset( $_GET['tab'] ) && 'logs' === sanitize_text_field( $_GET['tab'] ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification
			$filter_table = $wpdb->prefix . 'bwfan_logs';
		}

		$task_status = ( isset( $_GET['status'] ) && '' !== $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 't_0'; //phpcs:ignore WordPress.Security.NonceVerification
		if ( strpos( $task_status, '_' ) !== false ) {
			$task_status = explode( '_', $task_status );
			$task_status = intval( $task_status[1] );
		} else {
			$task_status = 0;
		}

		$params = array();

		if ( ! is_null( $filter_table ) ) {
			$query = 'SELECT DISTINCT(integration_action) as actions FROM ' . $filter_table;
			if ( ! is_null( $task_status ) ) {
				$query    .= ' WHERE `status` = %d';
				$params[] = $task_status;
			}
			$parsed_query     = $wpdb->prepare( $query, $params ); // phpcs:ignore WordPress.DB.PreparedSQL
			$distinct_actions = $wpdb->get_results( $parsed_query, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL
			if ( is_array( $distinct_actions ) && count( $distinct_actions ) > 0 ) {
				foreach ( $distinct_actions as $values ) {
					$filtered_table_actions[ $values['actions'] ] = $values['actions'];
				}
			}
		}

		$result      = array();
		$all_sources = BWFAN_Core()->integration->get_integrations();
		$all_actions = BWFAN_Load_Integrations::get_all_integrations();
		if ( is_array( $all_actions ) && count( $all_actions ) > 0 ) {
			foreach ( $all_actions as $source_slug => $source_actions ) {
				if ( ! is_array( $source_actions ) || 0 === count( $source_actions ) ) {
					break;
				}
				foreach ( $source_actions as $actions_slug => $action_object ) {
					if ( ! is_null( $filtered_table_actions ) && in_array( $actions_slug, $filtered_table_actions, true ) ) {
						$result[ $actions_slug ] = $all_sources[ $source_slug ]->get_name() . ': ' . $action_object->get_name();
						continue;
					} elseif ( is_null( $filtered_table_actions ) ) {
						$result[ $actions_slug ] = $all_sources[ $source_slug ]->get_name() . ': ' . $action_object->get_name();
					}
				}
			}
		}
		ksort( $result );

		return $result;
	}

	public static function modify_display_numbers( $value = false ) {
		if ( false === $value ) {
			return 0;
		}
		if ( 1000 > $value ) {
			return $value;
		}

		return intval( $value / 1000 ) . 'k';
	}

	/**
	 * Get automations with title
	 *
	 * @return array
	 */
	public static function get_automations_filter_data() {
		$result = array();
		global $wpdb;
		$automation_table      = $wpdb->prefix . 'bwfan_automations';
		$automation_meta_table = $wpdb->prefix . 'bwfan_automationmeta';
		$params                = array();
		$query                 = 'SELECT am.`bwfan_automation_id`, am.`meta_value` ';
		$query                 .= 'FROM ' . $automation_meta_table . ' AS am';
		$query                 .= ' INNER JOIN ' . $automation_table . ' AS aut ON am.`bwfan_automation_id` = aut.`ID`';
		$query                 .= ' WHERE 1=1';
		$query                 .= ' AND am.`meta_key` = %s';
		$params[]              = 'title';
		$parsed_query          = $wpdb->prepare( $query, $params ); // phpcs:ignore WordPress.DB.PreparedSQL
		$all_automations       = $wpdb->get_results( $parsed_query, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL

		if ( false === is_array( $all_automations ) || 0 === count( $all_automations ) ) {
			return $result;
		}
		foreach ( $all_automations as $details ) {
			$result[ $details['bwfan_automation_id'] ] = $details['meta_value'];
		}

		return $result;
	}

	/**
	 * Increase the queue batch size while processing actions. default is 5.
	 *
	 * @param $batch_size
	 *
	 * @return mixed
	 */
	public static function ac_increase_queue_batch_size( $batch_size ) {
		$global_settings = self::get_global_settings();
		if ( isset( $global_settings['bwfan_ac_b_s'] ) && $global_settings['bwfan_ac_b_s'] > 0 ) {
			$batch_size = intval( $global_settings['bwfan_ac_b_s'] );
		}

		return $batch_size;
	}

	/**
	 * Increase the maximum execution time while processing actions. default is 30 seconds.
	 *
	 * @param $max_timeout
	 *
	 * @return mixed
	 */
	public static function ac_increase_max_execution_time( $max_timeout ) {
		$global_settings = self::get_global_settings();
		if ( isset( $global_settings['bwfan_ac_t_l'] ) && $global_settings['bwfan_ac_t_l'] > 0 ) {
			$max_timeout = intval( $global_settings['bwfan_ac_t_l'] );
		}

		return $max_timeout;
	}

	/**
	 * Make custom cron times for autonami events.
	 *
	 * @param $schedules
	 *
	 * @return mixed
	 */
	public static function make_custom_events_time( $schedules ) {
		$schedules['bwfan_once_in_day']         = array(
			'interval' => 86400,
			'display'  => __( 'Once in a day', 'wp-marketing-automations' ),
		);
		$schedules['bwfan_once_in_two_minutes'] = array(
			'interval' => 120,
			'display'  => __( 'Once in 2 minutes', 'wp-marketing-automations' ),
		);
		$schedules['bwfan_every_minute']        = array(
			'interval' => 60,
			'display'  => __( 'Every minute', 'wp-marketing-automations' ),
		);
		$schedules['bwfan_once_in_week']        = array(
			'interval' => 604800,
			'display'  => __( 'Once in a week', 'wp-marketing-automations' ),
		);

		return $schedules;
	}

	/**
	 * Make a new endpoint which will receive the event data
	 */
	public static function add_plugin_endpoint() {
		register_rest_route( 'autonami/v1', '/events', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( __CLASS__, 'capture_async_events' ),
			'permission_callback' => '__return_true',
		) );
		register_rest_route( 'autonami/v1', '/worker', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( __CLASS__, 'run_worker_tasks' ),
			'permission_callback' => '__return_true',
		) );
		register_rest_route( 'autonami/v1', '/autonami-cron', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( __CLASS__, 'run_autonami_cron_events' ),
			'permission_callback' => '__return_true',
		) );
		register_rest_route( 'autonami/v1', '/delete-tasks', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( __CLASS__, 'delete_automation_tasks_by_unique_action_ids' ),
			'permission_callback' => '__return_true',
		) );
		register_rest_route( 'autonami/v1', '/update-contact-automation', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( __CLASS__, 'update_contact_meta' ),
			'permission_callback' => '__return_true',
		) );
		register_rest_route( 'autonami/v1', '/update-generated-increment', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( __CLASS__, 'update_generated_increment' ),
			'permission_callback' => '__return_true',
		) );
		register_rest_route( 'autonami/v1', '/wc-add-to-cart', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( __CLASS__, 'wc_add_to_cart' ),
			'permission_callback' => '__return_true',
		) );
		/** v2 autonami endpoint */
		register_rest_route( 'autonami/v2', '/worker', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( __CLASS__, 'run_worker_tasks' ),
			'permission_callback' => '__return_true',
		) );

		register_rest_route( 'autonami/v2', '/order_status_change', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( __CLASS__, 'capture_order_status_change' ),
			'permission_callback' => array( 'WooFunnels_DB_Updater', 'validate' ),
		) );
	}

	/**
	 * Callback function for receiving the event data
	 *
	 * @param WP_REST_Request $request
	 */
	public static function capture_async_events( WP_REST_Request $request ) {
		self::nocache_headers();
		$resp            = array();
		$post_parameters = $request->get_body_params();

		/** Advanced logs */
		self::event_advanced_logs( 'Event endpoint data received' );
		self::event_advanced_logs( $post_parameters );

		if ( empty( $post_parameters ) ) {
			return;
		}

		/** Check Unique key security */
		$unique_key = get_option( 'bwfan_u_key', false );
		if ( false === $unique_key || ! isset( $post_parameters['unique_key'] ) || $post_parameters['unique_key'] !== $unique_key ) {
			return;
		}

		if ( ( isset( $post_parameters['source'] ) && isset( $post_parameters['event'] ) ) && ( ! isset( $post_parameters['automation_id'] ) ) ) {
			/** Set posted params to static property */
			self::$events_async_data = $post_parameters;

			$event_slug  = $post_parameters['event'];
			$event       = BWFAN_Core()->sources->get_event( $event_slug );
			$resp['msg'] = 'success';

			/** Try to run v2 automations */
			self::maybe_run_v2_automations( $event_slug, $post_parameters );

			/** Check if the Event has active automations, used this check again for cases like Form Submissions trigger */
			if ( ! is_null( $event ) && false !== $event->get_current_event_automations() ) {
				try {
					$event->capture_async_data();
				} catch ( Exception $exception ) {
					$resp['msg'] = $exception->getMessage();
				}
			}
			wp_send_json( $resp );
		}

		BWFAN_Core()->logger->log( 'Automation Source Or Event data is not available, Data - ' . print_r( self::$events_async_data, true ), 'event_lifecycle' ); //phpcs:ignore WordPress.PHP.DevelopmentFunctions
		wp_send_json( array(
			'msg'  => '',
			'time' => time(),
		) );
	}

	/**
	 * Find v2 active automations for event and run
	 *
	 * @param $event_slug
	 *
	 * @return false|void
	 */
	public static function maybe_run_v2_automations( $event_slug, $post_parameters ) {
		BWFAN_Core()->public->load_active_v2_automations( $event_slug );

		/** @var BWFAN_Event $event */
		$event = BWFAN_Core()->sources->get_event( $event_slug );
		if ( ! $event instanceof BWFAN_Event ) {
			return false;
		}

		/**
		 * Run Autonami async events
		 * Form submission, Automation goals, Normalize 3rd party tables
		 */
		self::extend_async_capture( $event, $post_parameters );

		$automations = $event->get_automations_data( 2 );
		if ( ! is_array( $automations ) || count( $automations ) === 0 ) {
			BWFAN_Core()->logger->log( 'No v2 active automations found. Event - ' . $event->get_slug(), $event->log_type );

			return false;
		}

		self::log_test_data( 'Found v2 automations. Event - ' . $event->get_slug() );
		self::log_test_data( 'IDs: ' . implode( ', ', array_keys( $automations ) ) );

		$any_automation_ran = false;
		foreach ( $automations as $automation_id => $automation_data ) {
			$automation_data = self::remove_extra_automation_data( $automation_data );
			if ( ! empty( $post_parameters ) && is_array( $post_parameters ) ) {
				$automation_data = array_merge( $automation_data, $post_parameters );
			}

			if ( empty( $automation_data ) ) {
				continue;
			}

			if ( false === $event->validate_v2_event_settings( $automation_data ) ) {
				continue;
			}

			$automation_data = $event->capture_v2_data( $automation_data );
			if ( empty( $automation_data ) ) {
				continue;
			}

			$event->global_data = $event->get_event_data( $automation_data );
			/** For modify data */
			$event->global_data = apply_filters( 'bwfan_modify_event_data', $event->global_data );
			$event->event_data  = $event->get_automation_event_data( $automation_data );

			/** Don't extend locally */
			$result = $event->handle_automation_run_v2( $automation_id, $automation_data );
			/** Set last run automation contact id */
			self::$last_automation_cid[ $automation_id ] = $result;

			$any_automation_ran = $any_automation_ran || ! empty( $result );
		}

		return $any_automation_ran;
	}

	/**
	 * @param BWFAN_Event $event
	 * @param array $post_parameters
	 */
	public static function extend_async_capture( $event, $post_parameters ) {
		/** Capture Async: Form Submission */
		if ( isset( $post_parameters['is_form_submission'] ) && 1 === absint( $post_parameters['is_form_submission'] ) && bwfan_is_autonami_pro_active() ) {
			try {
				BWFCRM_Core()->merge_tags->load_merge_tags();
				BWFCRM_Core()->forms->capture_async_form_submission();
			} catch ( Error $e ) {
				BWFAN_Common::log_test_data( $event->get_slug() . ' : Capture Async: Form Submission try catch failed. Error: ' . $e->getMessage(), 'extend_async_capture' );
			}
		}

		/** Capture Async: Goal Controller */
		if ( $event->is_goal() ) {
			try {
				BWFAN_Common::log_test_data( $event->get_slug() . ' is a goal', 'goal-check' );
				BWFAN_Goal_Controller::capture_async_goal( $event, $post_parameters );
			} catch ( Error $e ) {
				BWFAN_Common::log_test_data( $event->get_slug() . ' : Goal execution try catch failed. Error: ' . $e->getMessage(), 'extend_async_capture' );
			}
		}

		if ( $event->is_db_normalize() ) {
			try {
				if ( method_exists( $event, 'execute_normalization' ) ) {
					$event->execute_normalization( $post_parameters );
				}
			} catch ( Error $e ) {
				BWFAN_Common::log_test_data( $event->get_slug() . ' : Normalization method not found. Error: ' . $e->getMessage(), 'extend_async_capture' );
			}
		}
	}

	/**
	 * Aman testing purpose only
	 *
	 * @param $data
	 * @param $file
	 * @param $force
	 *
	 * @return void
	 */
	public static function log_test_data( $data, $file = 'v2-automations', $force = false ) {
		if ( empty( $data ) ) {
			return;
		}
		if ( true === $force ) {
			add_filter( 'bwfan_before_making_logs', '__return_true' );
		}
		if ( is_array( $data ) ) {
			BWFAN_Core()->logger->log( self::$dynamic_str, $file );
			BWFAN_Core()->logger->log( print_r( $data, true ), $file );

			return;
		}
		BWFAN_Core()->logger->log( self::$dynamic_str . ' - ' . $data, $file );
	}

	public static function log_l2_data( $data, $file = 'v2-automations', $force = false ) {
		if ( empty( $data ) ) {
			return;
		}

		$should_log = apply_filters( 'bwfan_log_level_2_logs', false );
		if ( false === $should_log ) {
			return;
		}

		if ( true === $force ) {
			add_filter( 'bwfan_before_making_logs', '__return_true' );
		}
		if ( is_array( $data ) ) {
			BWFAN_Core()->logger->log( print_r( $data, true ), $file );

			return;
		}
		BWFAN_Core()->logger->log( $data, $file );
	}

	public static function remove_extra_automation_data( $automation_data ) {
		if ( array_key_exists( 'goal', $automation_data ) ) {
			unset( $automation_data['goal'] );
		}
		if ( array_key_exists( 'steps', $automation_data ) ) {
			unset( $automation_data['steps'] );
		}
		if ( array_key_exists( 'links', $automation_data ) ) {
			unset( $automation_data['links'] );
		}
		if ( array_key_exists( 'count', $automation_data ) ) {
			unset( $automation_data['count'] );
		}
		if ( array_key_exists( 'requires_update', $automation_data ) ) {
			unset( $automation_data['requires_update'] );
		}
		if ( array_key_exists( 'step_iteration_array', $automation_data ) ) {
			unset( $automation_data['step_iteration_array'] );
		}
		if ( array_key_exists( 'unique_key', $automation_data ) ) {
			unset( $automation_data['unique_key'] );
		}

		return $automation_data;
	}

	/**
	 * Callback function for running autonami tasks
	 *
	 * @param WP_REST_Request $request
	 */
	public static function run_worker_tasks( WP_REST_Request $request ) {
		self::nocache_headers();
		$post_parameters = $request->get_body_params();
		if ( ! is_array( $post_parameters ) || ! isset( $post_parameters['worker'] ) ) {
			return;
		}

		/**
		 * Check Unique key security
		 */
		$unique_key = get_option( 'bwfan_u_key', false );
		if ( false === $unique_key || ! isset( $post_parameters['unique_key'] ) || $post_parameters['unique_key'] !== $unique_key ) {
			return;
		}

		$v = ( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], 'autonami/v1/worker' ) !== false ) ? 1 : 2;

		self::event_advanced_logs( "V{$v} worker callback received" );

		self::worker_as_run();

		/** Logs */
		if ( defined( 'BWF_CHECK_CRON_SCHEDULE' ) && true === BWF_CHECK_CRON_SCHEDULE ) {
			add_filter( 'bwf_logs_allowed', '__return_true', PHP_INT_MAX );
			$logger_obj = BWF_Logger::get_instance();
			$logger_obj->log( date_i18n( 'Y-m-d H:i:s' ) . ' - after worker run', 'cron-check-v' . $v, 'autonami' );
		}

		$resp        = array();
		$resp['msg'] = 'success';
		wp_send_json( $resp );
	}

	public static function worker_as_run() {
		if ( ! class_exists( 'ActionScheduler_QueueRunner' ) ) {
			return;
		}

		/** Check if Autonami is in sandbox mode */
		if ( true === BWFAN_Common::is_sandbox_mode_active() ) {
			return;
		}

		/** Modify Action Scheduler filters */
		self::modify_as_filters();

		$as_ins = ActionScheduler_QueueRunner::instance();

		/** Run Action Scheduler worker */
		$as_ins->run();
	}

	/**
	 * Check if sandbox is active or not
	 *
	 * @return bool
	 */
	public static function is_sandbox_mode_active() {
		if ( defined( 'BWFAN_SANDBOX_MODE' ) && true === BWFAN_SANDBOX_MODE ) {
			return true;
		}
		$global_settings = get_option( 'bwfan_global_settings', array() );
		if ( empty( $global_settings ) || ! isset( $global_settings['bwfan_sandbox_mode'] ) ) {
			return false;
		}
		if ( 1 === intval( $global_settings['bwfan_sandbox_mode'] ) ) {
			return true;
		}

		return false;
	}

	public static function modify_as_filters() {
		/** Remove all existing filters */
		remove_all_filters( 'action_scheduler_queue_runner_time_limit' );
		remove_all_filters( 'action_scheduler_queue_runner_batch_size' );
		remove_all_filters( 'action_scheduler_queue_runner_concurrent_batches' );
		remove_all_filters( 'action_scheduler_timeout_period' );
		remove_all_filters( 'action_scheduler_cleanup_batch_size' );
		remove_all_filters( 'action_scheduler_maximum_execution_time_likely_to_be_exceeded' );

		/** Adding all filters for Autonami Action Scheduler only */
		add_filter( 'action_scheduler_queue_runner_time_limit', function () {
			return apply_filters( 'bwfan_as_per_call_time', 30 );
		}, 999 );
		add_filter( 'action_scheduler_queue_runner_batch_size', function () {
			return apply_filters( 'bwfan_as_per_call_batch_size', 30 );
		}, 999 );
		add_filter( 'action_scheduler_queue_runner_concurrent_batches', function () {
			return 5;
		}, 999 );
		add_filter( 'action_scheduler_timeout_period', function () {
			return 300;
		}, 999 );
		add_filter( 'action_scheduler_cleanup_batch_size', function () {
			return 20;
		}, 999 );
		add_filter( 'action_scheduler_maximum_execution_time_likely_to_be_exceeded', function ( $val, $ins, $processed_actions, $execution_time, $max_execution_time ) {
			return ( $execution_time > $max_execution_time );
		}, 99999, 5 );
	}

	/**
	 * Check if cart abandonment is active or not
	 *
	 * @return bool
	 */
	public static function is_cart_abandonment_active() {
		$global_settings = get_option( 'bwfan_global_settings', array() );
		if ( empty( $global_settings ) || ! isset( $global_settings['bwfan_ab_enable'] ) || empty( $global_settings['bwfan_ab_enable'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * action_scheduler_pre_init action hook
	 */
	public static function as_pre_init_cb() {

		if ( ( ! isset( $_GET['rest_route'] ) || '/autonami/v1/worker' !== sanitize_text_field( $_GET['rest_route'] ) ) && false === strpos( $_SERVER['REQUEST_URI'], '/autonami/v1/worker' ) ) { //phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput
			return;
		}
		if ( ! class_exists( 'BWFAN_AS_CT' ) ) {
			return;
		}

		/** BWFAN_AS_CT instance */
		$as_ct_ins = BWFAN_AS_CT::instance();

		/** Set new AS CT data store */
		$as_ct_ins->change_data_store();
		self::$change_data_strore = true;
	}

	/**
	 * action_scheduler_pre_init action hook for version 2
	 */
	public static function as_pre_init_v2_cb() {

		if ( ( ! isset( $_GET['rest_route'] ) || '/autonami/v2/worker' !== sanitize_text_field( $_GET['rest_route'] ) ) && false === strpos( $_SERVER['REQUEST_URI'], '/autonami/v2/worker' ) ) { //phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput
			return;
		}
		if ( ! class_exists( 'BWFAN_AS_V2' ) ) {
			return;
		}

		/** BWFAN_AS_V2 instance */
		$ins = BWFAN_AS_V2::instance();

		/** Unset orphaned claims */
		$ins->unset_orphaned_claims();

		/** Set new AS V2 data store */
		$ins->change_data_store();
		self::$change_data_strore = true;
	}

	/**
	 * action_scheduler_pre_init action hook for autonami cli
	 */
	public static function as_pre_init_cli_cb() {

		global $argv;

		if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
			return;
		}

		/**
		 * $argv holds arguments passed to script
		 * https://www.php.net/manual/en/reserved.variables.argv.php
		 */

		if ( empty( $argv ) ) {
			WP_CLI::log( 'Autonami WP CLI arguments not found.' );

			return;
		}

		if ( ! isset( $argv[1] ) || 'autonami-tasks' !== $argv[1] ) {
			return;
		}
		if ( ! isset( $argv[2] ) || 'run' !== $argv[2] ) {
			return;
		}

		if ( ! class_exists( 'BWFAN_AS_CT' ) ) {
			WP_CLI::log( 'BWFAN_AS_CT class not found.' );
		}

		/** BWFAN_AS_CT instance */
		$as_ct_ins = BWFAN_AS_CT::instance();

		/** Set new AS CT data store */
		$as_ct_ins->change_data_store();
		self::$change_data_strore = true;
	}

	/**
	 * action_scheduler_pre_init action hook for version 2 for autonami cli
	 */
	public static function as_pre_init_cli_v2_cb() {

		global $argv;

		if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
			return;
		}

		/**
		 * $argv holds arguments passed to script
		 * https://www.php.net/manual/en/reserved.variables.argv.php
		 */

		if ( empty( $argv ) ) {
			WP_CLI::log( 'Autonami V2 WP CLI arguments not found.' );

			return;
		}

		if ( ! isset( $argv[1] ) || 'autonami-tasks' !== $argv[1] ) {
			return;
		}
		if ( ! isset( $argv[2] ) || 'run' !== $argv[2] ) {
			return;
		}
		if ( ! class_exists( 'BWFAN_AS_V2' ) ) {
			WP_CLI::log( 'BWFAN_AS_V2 class not found.' );
		}

		/** BWFAN_AS_V2 instance */
		$ins = BWFAN_AS_V2::instance();

		/** Set new AS V2 data store */
		$ins->change_data_store();
		self::$change_data_strore = true;
	}

	/**
	 * This function is called when rest endpoint of cron is hit.
	 *
	 * @param WP_REST_Request $request
	 */
	public static function run_autonami_cron_events( WP_REST_Request $request ) {
		self::nocache_headers();
		$resp        = array();
		$resp['msg'] = 'success';

		if ( isset( $_GET['debug'] ) && 'yes' === $_GET['debug'] ) {
			$resp['msg'] = 'connection established';
			wp_send_json( $resp );
		}

		self::run_as_ct_worker();

		wp_send_json( $resp );
	}

	/**
	 * 1 min worker callback
	 */
	public static function run_as_ct_worker() {
		/** Maybe v1 automation not active */
		if ( false === self::is_automation_v1_active() && bwf_has_action_scheduled( 'bwfan_run_queue' ) ) {
			bwf_unschedule_actions( 'bwfan_run_queue' );
		}

		/** Check if a single task is available for execution */
		if ( false === self::is_automation_v1_active() || false === BWFAN_Model_Tasks::maybe_tasks_available() ) {
			return;
		}

		$url       = rest_url( '/autonami/v1/worker' ) . '?' . time();
		$body_data = array(
			'worker'     => true,
			'unique_key' => get_option( 'bwfan_u_key', false ),
		);
		$args      = bwf_get_remote_rest_args( $body_data );
		wp_remote_post( $url, $args );
	}

	/**
	 * v2 automation 1 min worker callback
	 *
	 * @return void
	 */
	public static function run_as_ct_v2_worker() {
		/** Advanced logs */
		self::event_advanced_logs( 'V2 action callback' );

		/** Logs */
		if ( defined( 'BWF_CHECK_CRON_SCHEDULE' ) && true === BWF_CHECK_CRON_SCHEDULE ) {
			add_filter( 'bwf_logs_allowed', '__return_true', PHP_INT_MAX );
			$logger_obj = BWF_Logger::get_instance();
			$logger_obj->log( date_i18n( 'Y-m-d H:i:s' ) . ' - before worker hit', 'cron-check-v2', 'autonami' );
		}

		/** Check if any contact in automation can proceed */
		if ( false === BWFAN_Model_Automation_Contact::maybe_can_execute() ) {
			return;
		}

		$url       = rest_url( '/autonami/v2/worker' ) . '?' . time();
		$body_data = array(
			'worker'     => true,
			'unique_key' => get_option( 'bwfan_u_key', false ),
		);
		$args      = bwf_get_remote_rest_args( $body_data );

		$resp = wp_remote_post( $url, $args );
		self::event_advanced_logs( 'V2 worker response' );
		self::event_advanced_logs( $resp );
	}

	/**
	 * phpcs:ignore WordPress.Security.NonceVerification
	 * Return the html for tasks links on tasks listing page.
	 *
	 * @return string
	 */
	public static function get_link_options_for_tasks() {
		$scheduled_count = BWFAN_Core()->tasks->fetch_tasks_count( 0, 0 );
		$scheduled       = sprintf( __( 'Scheduled (%d)', 'wp-marketing-automations' ), $scheduled_count );

		$paused_count          = BWFAN_Core()->tasks->fetch_tasks_count( 0, 1 );
		$paused                = sprintf( __( 'Paused (%d)', 'wp-marketing-automations' ), $paused_count );
		$completed_count       = BWFAN_Core()->logs->fetch_logs_count( 1 );
		$completed             = sprintf( __( 'Completed (%d)', 'wp-marketing-automations' ), $completed_count );
		$failed_count          = BWFAN_Core()->logs->fetch_logs_count( 0 );
		$failed                = sprintf( __( 'Failed (%d)', 'wp-marketing-automations' ), $failed_count );
		$get_campaign_statuses = apply_filters( 'bwfan_admin_trigger_nav', array(
			't_0' => $scheduled,
			't_1' => $paused,
			'l_1' => $completed,
			'l_0' => $failed,
		) );
		$html                  = '<ul class="subsubsub subsubsub_bwfan">';
		$html_inside           = array();
		$current_status        = 't_0';

		if ( isset( $_GET['status'] ) && '' !== $_GET['status'] ) { //phpcs:ignore WordPress.Security.NonceVerification
			$current_status = sanitize_text_field( $_GET['status'] );//phpcs:ignore WordPress.Security.NonceVerification
		}

		// For listing screen
		$all_statuses = array(
			't_0' => array(
				'tab' => 'tasks',
			),
			't_1' => array(
				'tab' => 'tasks',
			),
			'l_0' => array(
				'tab' => 'logs',
			),
			'l_1' => array(
				'tab' => 'logs',
			),
		);

		foreach ( $get_campaign_statuses as $slug => $status ) {
			$need_class = '';
			if ( $slug === $current_status ) {
				$need_class = 'current';
			}

			$args = array(
				'status' => $slug,
			);

			$args['tab']   = $all_statuses[ $slug ]['tab'];
			$url           = add_query_arg( $args, admin_url( 'admin.php?page=autonami-automations' ) );
			$html_inside[] = sprintf( '<li><a href="%s" class="%s">%s</a> </li>', $url, $need_class, $status );
		}

		if ( is_array( $html_inside ) && count( $html_inside ) > 0 ) {
			$html .= implode( '', $html_inside );
		}
		$html .= '</ul>';

		return $html;
	}

	public static function get_logging_status() {
		$global_settings = self::get_global_settings();

		return ( isset( $global_settings['bwfan_make_logs'] ) && 1 === intval( $global_settings['bwfan_make_logs'] ) ) ? true : false;
	}

	/**
	 * Capture all the action ids of an automation and delete all its tasks except for completed tasks.
	 *
	 * @param WP_REST_Request $request
	 */
	public static function delete_automation_tasks_by_unique_action_ids( WP_REST_Request $request ) {
		self::nocache_headers();
		$post_parameters = $request->get_body_params();

		if ( false === is_array( $post_parameters ) || 0 === count( $post_parameters ) ) {
			return;
		}
		if ( ! isset( $post_parameters['automation_id'] ) || ! isset( $post_parameters['a_track_id'] ) || ! isset( $post_parameters['t_to_delete'] ) ) {
			return;
		}
		/**
		 * Check Unique key security
		 */
		$unique_key = get_option( 'bwfan_u_key', false );
		if ( false === $unique_key || ! isset( $post_parameters['unique_key'] ) || $post_parameters['unique_key'] !== $unique_key ) {
			return;
		}

		$automation_id = sanitize_text_field( $post_parameters['automation_id'] );
		$a_track_id    = sanitize_text_field( $post_parameters['a_track_id'] );
		$t_to_delete   = $post_parameters['t_to_delete'];
		$t_to_delete   = self::is_json( $t_to_delete ) ? json_decode( $t_to_delete, true ) : $t_to_delete;

		if ( false === is_array( $t_to_delete ) || 0 === count( $t_to_delete ) ) {
			return;
		}
		foreach ( $t_to_delete as $key1 => $action_index ) {
			$t_to_delete[ $key1 ] = $a_track_id . '_' . $action_index;
		}

		BWFAN_Core()->tasks->delete_by_index_ids( $automation_id, $t_to_delete );
		BWFAN_Core()->logs->delete_by_index_ids( $automation_id, $t_to_delete );
	}

	/**
	 * Convert string/ json to array
	 *
	 * @param $value
	 *
	 * @return array
	 */
	public static function make_array( $value ) {
		$value = self::is_json( $value ) ? json_decode( $value, true ) : $value;

		return empty( $value ) || ! is_array( $value ) ? [] : $value;
	}

	/**
	 * Check if string is a json
	 *
	 * @param $string
	 *
	 * @return bool
	 */
	public static function is_json( $string ) {
		if ( ! is_string( $string ) ) {
			return false;
		}
		json_decode( $string );

		return ( json_last_error() === JSON_ERROR_NONE );
	}

	/**
	 * Array unique and sort
	 *
	 * @param $value
	 *
	 * @return array|mixed
	 */
	public static function unique( $value ) {
		if ( ! is_array( $value ) ) {
			return $value;
		}

		$value = array_unique( $value );
		sort( $value );

		return $value;
	}

	/**
	 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
	 * Non-scalar values are ignored.
	 *
	 * @param string|array $var Data to sanitize.
	 *
	 * @return string|array
	 */
	public static function bwfan_clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'self::bwfan_clean', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}

	/**
	 * Update contact automation table for current automation.
	 *
	 * @param WP_REST_Request $request
	 */
	public static function update_contact_meta( WP_REST_Request $request ) {
		self::nocache_headers();
		$post_parameters = $request->get_body_params();

		if ( false === is_array( $post_parameters ) || 0 === count( $post_parameters ) ) {
			return;
		}
		if ( ! isset( $post_parameters['automation_id'] ) || ! isset( $post_parameters['email'] ) || ! isset( $post_parameters['user_id'] ) ) {
			return;
		}
		/**
		 * Check Unique key security
		 */
		$unique_key = get_option( 'bwfan_u_key', false );
		if ( false === $unique_key || ! isset( $post_parameters['unique_key'] ) || $post_parameters['unique_key'] !== $unique_key ) {
			return;
		}

		$automation_id = sanitize_text_field( $post_parameters['automation_id'] );
		$email         = sanitize_text_field( $post_parameters['email'] );
		$user_id       = sanitize_text_field( $post_parameters['user_id'] );
		$contact_obj   = bwf_get_contact( $user_id, $email );
		$contact_id    = $contact_obj->id;

		if ( ! isset( $contact_id ) || empty( $contact_id ) ) {
			return;
		}

		$data = array(
			'contact_id'    => $contact_id,
			'automation_id' => $automation_id,
			'time'          => time(),
		);

		BWFAN_Model_Contact_Automations::insert( $data );
	}

	public static function update_generated_increment( WP_REST_Request $request ) {
		self::nocache_headers();
		$post_parameters = $request->get_body_params();
		if ( false === is_array( $post_parameters ) || 0 === count( $post_parameters ) ) {
			return;
		}
		if ( ! isset( $post_parameters['id'] ) ) {
			return;
		}
		/**
		 * Check Unique key security
		 */
		$unique_key = get_option( 'bwfan_u_key', false );
		if ( false === $unique_key || ! isset( $post_parameters['unique_key'] ) || $post_parameters['unique_key'] !== $unique_key ) {
			return;
		}

		$date = date( 'Y-m-d' );
		/**
		 * All calculations are done via this row and the stats displayed are fetched from this row only.
		 */
		WFCO_Model_Report_views::update_data( $date, 0, 1 );
		/**
		 * If AeroCheckout page
		 *
		 * This row is saved for future use
		 */
		if ( ! empty( $post_parameters['id'] ) ) {
			WFCO_Model_Report_views::update_data( $date, $post_parameters['id'], 1 );
		}
	}

	public static function wc_add_to_cart( WP_REST_Request $request ) {
		self::nocache_headers();
		$post_parameters = $request->get_body_params();
		if ( false === is_array( $post_parameters ) || 0 === count( $post_parameters ) ) {
			return;
		}
		if ( ! isset( $post_parameters['id'] ) || ! isset( $post_parameters['coupon_data'] ) || ! isset( $post_parameters['items'] ) || ! isset( $post_parameters['fees'] ) ) {
			return;
		}
		/**
		 * Check Unique key security
		 */
		$unique_key = get_option( 'bwfan_u_key', false );
		if ( false === $unique_key || ! isset( $post_parameters['unique_key'] ) || $post_parameters['unique_key'] !== $unique_key ) {
			return;
		}

		$abandoned_obj = BWFAN_Abandoned_Cart::get_instance();
		$user_id       = $post_parameters['id'];
		$user_data     = get_userdata( $user_id );
		$email         = $user_data->user_email;
		$coupon_data   = $post_parameters['coupon_data'];
		$items         = $post_parameters['items'];
		$fees          = $post_parameters['fees'];
		$cart_details  = $abandoned_obj->get_cart_by_key( 'email', $email, '%s' );

		if ( false === $cart_details ) {
			self::create_abandoned_cart( array(
				'user_id'    => $user_id,
				'email'      => $email,
				'coupons'    => $coupon_data,
				'items'      => $items,
				'fees'       => $fees,
				'cookie_key' => $post_parameters['bwfan_visitor'],
			) );

			return;
		}

		$cart_details['coupons'] = $coupon_data;
		$cart_details['items']   = $items;
		$cart_details['fees']    = $fees;
		$data                    = self::get_abandoned_totals( $cart_details );
		$data['user_id']         = $user_id;
		$data['last_modified']   = current_time( 'mysql', 1 );

		$where = array(
			'ID' => $cart_details['ID'],
		);

		BWFAN_Model_Abandonedcarts::update( $data, $where );
	}

	private static function create_abandoned_cart( $data ) {
		$customer      = new WC_Customer( $data['user_id'] );
		$checkout_data = array(
			'fields' => array(
				'billing_first_name'  => $customer->get_billing_first_name(),
				'billing_last_name'   => $customer->get_billing_last_name(),
				'billing_company'     => $customer->get_billing_company(),
				'billing_country'     => $customer->get_billing_country(),
				'billing_address_1'   => $customer->get_billing_address_1(),
				'billing_address_2'   => $customer->get_billing_address_2(),
				'billing_city'        => $customer->get_billing_city(),
				'billing_state'       => $customer->get_billing_state(),
				'billing_postcode'    => $customer->get_billing_postcode(),
				'billing_phone'       => $customer->get_billing_phone(),
				'billing_email'       => $customer->get_billing_email(),
				'shipping_first_name' => $customer->get_shipping_first_name(),
				'shipping_last_name'  => $customer->get_shipping_last_name(),
				'shipping_company'    => $customer->get_shipping_company(),
				'shipping_country'    => $customer->get_shipping_country(),
				'shipping_address_1'  => $customer->get_shipping_address_1(),
				'shipping_address_2'  => $customer->get_shipping_address_2(),
				'shipping_city'       => $customer->get_shipping_city(),
				'shipping_state'      => $customer->get_shipping_state(),
				'shipping_postcode'   => $customer->get_shipping_postcode(),
			),
		);

		$data['status']        = 0;
		$data['created_time']  = current_time( 'mysql', 1 );
		$data['last_modified'] = current_time( 'mysql', 1 );
		$data['token']         = self::create_token( 32 );
		$data['checkout_data'] = wp_json_encode( $checkout_data );
		$data['currency']      = get_woocommerce_currency();
		$data                  = self::get_abandoned_totals( $data );

		BWFAN_Model_Abandonedcarts::insert( $data );
	}

	public static function create_token( $length = 25, $case_sensitive = true, $more_numbers = false ) {
		$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';

		if ( $case_sensitive ) {
			$chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
		if ( $more_numbers ) {
			$chars .= '01234567890123456789';
		}

		$password     = '';
		$chars_length = strlen( $chars );

		for ( $i = 0; $i < $length; $i ++ ) {
			$password .= substr( $chars, wp_rand( 0, $chars_length - 1 ), 1 );
		}

		return $password;
	}

	private static function get_abandoned_totals( $data ) {
		$coupon_data      = $data['coupons'];
		$items            = $data['items'];
		$fees             = $data['fees'];
		$calculated_total = 0;

		foreach ( maybe_unserialize( $items ) as $item ) {
			$line_subtotal_tax = isset( $item['line_subtotal_tax'] ) ? floatval( $item['line_subtotal_tax'] ) : 0;
			$line_subtotal     = isset( $item['line_subtotal'] ) ? floatval( $item['line_subtotal'] ) : 0;
			$calculated_total  += $line_subtotal + $line_subtotal_tax;
		}
		foreach ( maybe_unserialize( $coupon_data ) as $coupon ) {
			$calculated_total -= $coupon['discount_incl_tax'];
		}
		foreach ( maybe_unserialize( $fees ) as $fee ) {
			$calculated_total += ( $fee->total + $fee->tax );
		}

		$calculated_total   = wc_format_decimal( $calculated_total, wc_get_price_decimals() );
		$data['total']      = $calculated_total;
		$data['total_base'] = apply_filters( 'bwfan_ab_cart_total_base', $calculated_total );

		return $data;
	}

	/**
	 * Get human readable time format like 18 minutes 47 seconds ago
	 *
	 * @param $timestamp
	 * @param $date
	 *
	 * @return string
	 */
	public static function get_human_readable_time( $timestamp, $date ) {
		$current_timestamp = gmdate( 'U' );
		if ( $current_timestamp > $timestamp ) {
			$schedule_display_string = '<time title="' . $date . '">' . self::human_interval( gmdate( 'U' ) - $timestamp ) . __( ' ago', 'wp-marketing-automations' ) . '</time>';
		} else {
			$schedule_display_string = '<time title="' . $date . '">' . __( 'in ', 'wp-marketing-automations' ) . self::human_interval( $timestamp - gmdate( 'U' ) ) . '</time>';
		}

		return $schedule_display_string;
	}

	public static function human_interval( $interval, $periods_to_include = 2 ) {

		self::$time_periods = array(
			array(
				'seconds' => YEAR_IN_SECONDS,
				'names'   => _n_noop( '%s year', '%s years', 'action-scheduler' ),
			),
			array(
				'seconds' => MONTH_IN_SECONDS,
				'names'   => _n_noop( '%s month', '%s months', 'action-scheduler' ),
			),
			array(
				'seconds' => WEEK_IN_SECONDS,
				'names'   => _n_noop( '%s week', '%s weeks', 'action-scheduler' ),
			),
			array(
				'seconds' => DAY_IN_SECONDS,
				'names'   => _n_noop( '%s day', '%s days', 'action-scheduler' ),
			),
			array(
				'seconds' => HOUR_IN_SECONDS,
				'names'   => _n_noop( '%s hour', '%s hours', 'action-scheduler' ),
			),
			array(
				'seconds' => MINUTE_IN_SECONDS,
				'names'   => _n_noop( '%s minute', '%s minutes', 'action-scheduler' ),
			),
			array(
				'seconds' => 1,
				'names'   => _n_noop( '%s second', '%s seconds', 'action-scheduler' ),
			),
		);

		if ( $interval <= 0 ) {
			return __( 'Now!', 'action-scheduler' );
		}

		$output = '';

		for ( $time_period_index = 0, $periods_included = 0, $seconds_remaining = $interval; $time_period_index < count( self::$time_periods ) && $seconds_remaining > 0 && $periods_included < $periods_to_include; $time_period_index ++ ) {

			$periods_in_interval = floor( $seconds_remaining / self::$time_periods[ $time_period_index ]['seconds'] );
			if ( $periods_in_interval > 0 ) {
				if ( ! empty( $output ) ) {
					$output .= ' ';
				}
				$output            .= sprintf( _n( self::$time_periods[ $time_period_index ]['names'][0], self::$time_periods[ $time_period_index ]['names'][1], $periods_in_interval, 'action-scheduler' ), $periods_in_interval );
				$seconds_remaining -= $periods_in_interval * self::$time_periods[ $time_period_index ]['seconds'];
				$periods_included ++;
			}
		}

		return $output;
	}

	/**
	 * Return seconds from 24 hr format.
	 *
	 * @param $str_time
	 *
	 * @return float|int
	 */
	public static function get_seconds_from_time_format( $str_time ) {
		$hours   = '';
		$minutes = '';
		$seconds = '';

		sscanf( $str_time, '%d:%d:%d', $hours, $minutes, $seconds );
		$time_seconds = ( isset( $hours ) && ! empty( $hours ) ) ? absint( $hours ) * 3600 + absint( $minutes ) * 60 + absint( $seconds ) : absint( $minutes ) * 60 + absint( $seconds );

		return $time_seconds;
	}

	/**
	 * Get the nearest date.
	 *
	 * @param $actual_timestamp
	 * @param $days_selected
	 *
	 * @return false|int
	 */
	public static function get_nearest_date( $actual_timestamp, $days_selected ) {
		$date_object = new DateTime( date( ( 'Y-m-d H:i:s' ), $actual_timestamp ) );

		for ( $h = 0; $h < 7; $h ++ ) {
			if ( in_array( $date_object->format( "N" ), $days_selected ) ) {
				return $date_object->getTimestamp();
			}
			$date_object->modify( '+1 days' );
		}

		return $actual_timestamp;
	}

	/**
	 * Find the closest matching date.
	 *
	 * @param $array
	 * @param $date
	 *
	 * @return mixed
	 */
	public static function find_closest( $array, $date ) {
		$interval = array();
		foreach ( $array as $day ) {
			$interval[] = abs( strtotime( $date ) - strtotime( $day ) );
		}

		asort( $interval );
		$closest = key( $interval );

		return $array[ $closest ];
	}

	/**
	 * @param $ids
	 * @param $status
	 */
	public static function update_abandoned_rows( $ids, $status ) {
		global $wpdb;
		$automationCount        = count( $ids );
		$stringPlaceholders     = array_fill( 0, $automationCount, '%s' );
		$placeholdersautomation = implode( ', ', $stringPlaceholders );
		$sql_query              = "Update {table_name} Set status = $status WHERE ID IN ($placeholdersautomation)";
		$sql_query              = $wpdb->prepare( $sql_query, $ids ); // WPCS: unprepared SQL OK

		BWFAN_Model_Abandonedcarts::get_results( $sql_query );
	}

	/**
	 * Delete all the tasks related to an abandoned cart row
	 *
	 * @param $abandoned_cart_id
	 */
	public static function delete_abandoned_cart_tasks( $abandoned_cart_id ) {
		global $wpdb;
		$meta_key = 'c_a_id';
		$query    = $wpdb->prepare( 'SELECT `bwfan_task_id` FROM {table_name} WHERE meta_key = %s AND meta_value = %s', $meta_key, $abandoned_cart_id );
		$result   = BWFAN_Model_Taskmeta::get_results( $query );

		if ( ! is_array( $result ) || count( $result ) === 0 ) {
			return;
		}

		$task_ids = array();
		foreach ( $result as $value1 ) {
			$task_ids[] = $value1['bwfan_task_id'];
		}

		BWFAN_Core()->tasks->delete_tasks( $task_ids );
	}

	/**
	 * This function checks for all the active carts. If last modified time of active carts exceeds the global cart timeout setting,
	 * then those carts will me made as abandoned.
	 */
	public static function check_for_abandoned_carts() {
		if ( ! class_exists( 'WooCommerce' ) && bwf_has_action_scheduled( 'bwfan_check_abandoned_carts' ) ) {
			bwf_unschedule_actions( 'bwfan_check_abandoned_carts' );

			return;
		}
		$global_settings = self::get_global_settings();
		if ( empty( $global_settings['bwfan_ab_enable'] ) ) {
			return;
		}

		/** Maybe run */
		if ( false === BWFAN_Model_Abandonedcarts::maybe_run( $global_settings['bwfan_ab_init_wait_time'] ) ) {
			return;
		}

		$all_sources = BWFAN_Load_Sources::get_all_sources_obj();
		$all_sources['wc']['ab_cart_abandoned']->load_hooks();
		$all_sources['wc']['ab_cart_abandoned']->get_eligible_abandoned_rows();
	}

	/**
	 * Delete all the old abandoned rows from db table. This function runs once in a week.
	 */
	public static function delete_old_abandoned_carts() {
		if ( false === apply_filters( 'bwfan_ab_delete_inactive_carts', false ) ) {
			return;
		}

		global $wpdb;
		$global_settings        = self::get_global_settings();
		$abandoned_time_in_days = absint( $global_settings['bwfan_ab_remove_inactive_cart_time'] ) * 1440;
		$query                  = $wpdb->prepare( 'select T.ID from {table_name} T where TIMESTAMPDIFF(MINUTE,T.last_modified,UTC_TIMESTAMP) > %d', $abandoned_time_in_days );
		$abandoned_carts        = BWFAN_Model_Abandonedcarts::get_results( $query );

		if ( ! is_array( $abandoned_carts ) || count( $abandoned_carts ) === 0 ) {
			return;
		}

		$abandoned_cart_ids = array();
		foreach ( $abandoned_carts as $value1 ) {
			$abandoned_cart_ids[] = $value1['ID'];
		}

		$automationCount        = count( $abandoned_cart_ids );
		$stringPlaceholders     = array_fill( 0, $automationCount, '%s' );
		$placeholdersautomation = implode( ', ', $stringPlaceholders );
		$sql_query              = "Delete FROM {table_name} WHERE ID IN ($placeholdersautomation)";
		$sql_query              = $wpdb->prepare( $sql_query, $abandoned_cart_ids ); // WPCS: unprepared SQL OK

		BWFAN_Model_Abandonedcarts::delete_multiple( $sql_query );
	}

	/**
	 * Scheduling recurring action for marking lost cart
	 *
	 * @return void
	 */
	public static function mark_abandoned_lost_cart() {
		if ( ! class_exists( 'WooCommerce' ) && bwf_has_action_scheduled( 'bwfan_mark_abandoned_lost_cart' ) ) {
			bwf_unschedule_actions( 'bwfan_mark_abandoned_lost_cart' );

			return;
		}

		if ( ! bwf_has_action_scheduled( 'bwfan_lost_cart_triggered' ) ) {
			bwf_schedule_recurring_action( time(), 120, 'bwfan_lost_cart_triggered' );
		}
	}

	/**
	 * Mark cart lost action callback
	 *
	 * @return void
	 */
	public static function bwfan_lost_cart_triggered() {
		global $wpdb;
		$global_settings        = self::get_global_settings();
		$abandoned_time_in_days = absint( $global_settings['bwfan_ab_mark_lost_cart'] ) * 1440;
		$start_time             = time();
		do {
			/** Get carts to mark lost carts */
			$query = "SELECT ID, email FROM {$wpdb->prefix}bwfan_abandonedcarts WHERE TIMESTAMPDIFF(MINUTE,last_modified,UTC_TIMESTAMP) > %d AND `status` !=  %d LIMIT %d";
			$query = $wpdb->prepare( $query, $abandoned_time_in_days, 2, 10 );
			$carts = $wpdb->get_results( $query, ARRAY_A );

			/** Un-schedule the action if no cart found */
			if ( empty( $carts ) ) {
				bwf_unschedule_actions( 'bwfan_lost_cart_triggered' );

				return;
			}

			/** Add lost tags & lists */
			self::add_lost_cart_tags( $carts );

			$ids                = array_column( $carts, 'ID' );
			$stringPlaceholders = array_fill( 0, count( $ids ), '%d' );
			$placeholder        = implode( ', ', $stringPlaceholders );
			/** Changing status recoverable into lost cart */
			$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}bwfan_abandonedcarts SET `status` = 2 WHERE ID IN ( $placeholder )", $ids ) );
		} while ( ( time() - $start_time ) > 10 );
	}

	/**
	 * Add tags and list to lost carts
	 *
	 * @param $carts
	 *
	 * @return void
	 */
	public static function add_lost_cart_tags( $carts ) {
		if ( ! class_exists( 'BWFCRM_Contact' ) || ! is_array( $carts ) || 0 === count( $carts ) ) {
			return;
		}

		if ( false === method_exists( 'BWFAN_Model_Terms', 'get_crm_term_ids' ) ) {
			/** Pro version 2.4.4 or higher required */
			return;
		}

		$global_settings = self::get_global_settings();

		$lost_cart_tags = isset( $global_settings['bwfan_lostcart_tag_selector'] ) ? json_decode( $global_settings['bwfan_lostcart_tag_selector'], true ) : [];
		$lost_cart_tags = BWFAN_Model_Terms::get_crm_term_ids( $lost_cart_tags, BWFCRM_Term_Type::$TAG );

		$lost_cart_list = isset( $global_settings['bwfan_lostcart_list_selector'] ) ? $global_settings['bwfan_lostcart_list_selector'] : [];
		$lost_cart_list = BWFAN_Model_Terms::get_crm_term_ids( $lost_cart_list, BWFCRM_Term_Type::$LIST );

		/** If tags & lists are not set in setting */
		if ( empty( $lost_cart_tags ) && empty( $lost_cart_list ) ) {
			return;
		}

		foreach ( $carts as $cart ) {
			$contact = new BWFCRM_Contact( $cart['email'] );
			if ( ! $contact->is_contact_exists() ) {
				continue;
			}

			if ( ! empty( $lost_cart_tags ) ) {
				$contact->set_tags_v2( $lost_cart_tags );
			}

			if ( ! empty( $lost_cart_list ) ) {
				$contact->set_lists_v2( $lost_cart_list );
			}

			$contact->save();
		}
	}

	/**
	 * Delete all the old abandoned rows from db table. This function runs once in a week.
	 */
	public static function delete_expired_autonami_coupons() {
		if ( ! class_exists( 'WooCommerce' ) && bwf_has_action_scheduled( 'bwfan_delete_expired_autonami_coupons' ) ) {
			bwf_unschedule_actions( 'bwfan_delete_expired_autonami_coupons' );

			return;
		}

		global $wpdb;

		$global_settings = self::get_global_settings();

		/** 1 day = 1440 minutes */
		$coupon_time_in_days = absint( $global_settings['bwfan_delete_autonami_generated_coupons_time'] ) * 1440;
		if ( ( 30 * 1440 ) < $coupon_time_in_days ) {
			$coupon_time_in_days = 30 * 1440;
		}

		$query = $wpdb->prepare( "
				SELECT m1.post_id as id
				FROM {$wpdb->prefix}postmeta as m1
				LEFT JOIN {$wpdb->prefix}postmeta as m2
				ON m1.post_id = m2.post_id
				LEFT JOIN {$wpdb->prefix}postmeta as m3
				ON m1.post_id = m3.post_id
				WHERE m1.meta_key = %s
				AND m1.meta_value = %d
				AND m2.meta_key = %s
				AND TIMESTAMPDIFF(MINUTE,FROM_UNIXTIME(m2.meta_value),UTC_TIMESTAMP) > %d
				AND m3.meta_key= %s
				AND m3.meta_value <>''
				", '_is_bwfan_coupon', 1, 'date_expires', $coupon_time_in_days, 'date_expires' );

		$coupons = $wpdb->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL
		if ( empty( $coupons ) ) {
			return;
		}

		foreach ( $coupons as $coupon ) {
			wp_delete_post( $coupon->id, true );
		}
	}

	/**
	 * Get all the scheduled task of given automation ids and the contact email
	 *
	 * @param array $winback_automations
	 * @param string $email
	 *
	 * @return array
	 */
	public static function get_schedule_task_by_email( $winback_automations, $email ) {
		global $wpdb;
		$task_table_name      = $wpdb->prefix . 'bwfan_tasks';
		$task_meta_table_name = $wpdb->prefix . 'bwfan_taskmeta';
		$tasks_results        = array();

		if ( empty( $winback_automations ) || empty( $email ) ) {
			return $tasks_results;
		}

		foreach ( $winback_automations as $automation_id ) {
			$query = $wpdb->prepare( "SELECT t.ID FROM $task_table_name AS t JOIN $task_meta_table_name AS tm ON t.ID=tm.bwfan_task_id WHERE t.automation_id = %d AND tm.meta_key='integration_data' AND tm.meta_value LIKE %s", $automation_id, '%' . $email . '%' );

			$tasks_results[ $automation_id ] = $wpdb->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL
		}

		return $tasks_results;
	}

	/**
	 * Get all the scheduled tasks of given automation ids and the contact phone number
	 *
	 * @param $winback_automations
	 * @param $phone
	 *
	 * @return array
	 */
	public static function get_schedule_task_by_phone( $winback_automations, $phone ) {
		global $wpdb;
		$task_table_name      = $wpdb->prefix . 'bwfan_tasks';
		$task_meta_table_name = $wpdb->prefix . 'bwfan_taskmeta';
		$tasks_results        = array();

		if ( empty( $winback_automations ) || empty( $phone ) ) {
			return $tasks_results;
		}

		foreach ( $winback_automations as $automation_id ) {
			$query = $wpdb->prepare( "SELECT t.ID FROM $task_table_name AS t JOIN $task_meta_table_name AS tm ON t.ID=tm.bwfan_task_id WHERE t.automation_id = %d AND tm.meta_key='integration_data' AND tm.meta_value LIKE %s", $automation_id, '%' . $phone . '%' );

			$tasks_results[ $automation_id ] = $wpdb->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL
		}

		return $tasks_results;
	}

	public static function wc_get_cart_recovery_url( $token, $coupon = '', $lang = '', $data = [] ) {
		$checkout_id = get_option( 'woocommerce_checkout_page_id' );
		if ( isset( $data['aero_data']['wfacp_is_checkout_override'] ) && true === $data['aero_data']['wfacp_is_checkout_override'] && isset( $data['aero_data']['wfacp_id'] ) && intval( $data['aero_data']['wfacp_id'] ) > 0 && 'publish' === get_post_status( $data['aero_data']['wfacp_id'] ) ) {
			$checkout_id = $data['aero_data']['wfacp_id'];
		}

		/**
		 * Making checkout page compatible with the WPML
		 * Trying & getting the base language translation post to validate the checkout page
		 */
		$url = ! empty( $checkout_id ) ? self::get_permalink_by_language( $checkout_id, $lang ) : '';
		if ( empty( $url ) ) {
			$url = home_url();
		}

		$url = add_query_arg( array(
			'bwfan-ab-id' => $token,
		), $url );

		if ( ! empty( $coupon ) ) {
			$url = add_query_arg( array(
				'bwfan-coupon' => preg_replace( "/&#?[a-z0-9]{2,8};/i", "", $coupon ),
			), $url );
		}

		return apply_filters( 'bwfan_abandoned_cart_restore_link', $url, $token, $coupon );
	}

	/**
	 * @param $post_id
	 * @param string $lang
	 * get permalink by language
	 *
	 * @return false|mixed|string|void
	 */

	public static function get_permalink_by_language( $post_id, $lang = '' ) {

		$url = get_permalink( $post_id );

		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			global $sitepress;

			$language_code = $sitepress->get_default_language();

			if ( ! empty( $lang ) ) {
				$language_code = $lang;
			}
			if ( version_compare( ICL_SITEPRESS_VERSION, '3.2' ) > 0 ) {
				$post_id = apply_filters( 'wpml_object_id', $post_id, 'page', false, $language_code );
			} else {
				$post_id = wpml_object_id_filter( $post_id, 'page', false, $language_code );
			}

			$url = apply_filters( 'wpml_permalink', $url, $language_code );
		}

		/** in case of translatepress */
		if ( bwfan_is_translatepress_active() ) {
			$trp_settings  = get_option( 'trp_settings' );
			$language_code = $trp_settings['default-language'];
			if ( ! empty( $lang ) ) {
				$language_code = $lang;
			}
			$trp           = TRP_Translate_Press::get_trp_instance();
			$url_converter = $trp->get_component( 'url_converter' );
			$url           = $url_converter->get_url_for_language( $language_code, $url, '' );
		}

		/** for polylang language */
		if ( function_exists( 'pll_current_language' ) ) {
			$language_code = pll_default_language();

			if ( ! empty( $lang ) ) {
				$language_code = $lang;
			}

			$url = add_query_arg( array(
				'lang' => $language_code,
			), $url );
		}

		if ( function_exists( 'bwfan_is_weglot_active' ) && bwfan_is_weglot_active() ) {
			$language_code = weglot_get_original_language();

			if ( ! empty( $lang ) ) {
				$language_code = $lang;
			}

			$site_url = home_url();
			$url      = str_replace( $site_url, $site_url . '/' . $language_code, $url );
		}

		return $url;
	}

	/**
	 * Get all the abandoned carts by email with status 1 and 2
	 *
	 * @param $email
	 *
	 * @return array|null|object|void
	 */
	public static function get_email_abandoned( $email ) {
		if ( empty( $email ) ) {
			return;
		}
		global $wpdb;
		$abandoned_table = $wpdb->prefix . 'bwfan_abandonedcarts';
		$abandoned_data  = $wpdb->get_results( "select ID,last_modified from $abandoned_table where status in(1,2) and email='" . $email . "' order by last_modified limit 0,3", ARRAY_A );

		return $abandoned_data;
	}

	/**
	 * Set the status 3 i.e. aborted of the abandoned cart
	 *
	 * @param $abandoned_id
	 */
	public static function set_email_cart_aborted( $abandoned_id ) {
		if ( empty( $abandoned_id ) ) {
			return;
		}

		$data           = [];
		$data['status'] = 3;
		$where          = array(
			'ID' => $abandoned_id,
		);

		BWFAN_Model_Abandonedcarts::update( $data, $where );
	}

	public static function merge_default_actions() {
		$all_automations = self::get_default_connector();
		$integrations    = self::get_default_actions();
		$default_data    = array();

		foreach ( $all_automations as $a_slug => $automation ) {
			$nice_name = $automation['nice_name'];
			if ( isset( $integrations[ $a_slug ] ) ) {
				$actions = $integrations[ $a_slug ];
				foreach ( $actions as $slug => $action ) {
					if ( ! class_exists( 'bwfan_' . $slug ) || ! bwfan_is_autonami_pro_active() ) {
						$default_data[ $nice_name ][ $slug ] = $action;
					}
				}
			}
		}

		return empty( $default_data ) ? new stdClass() : $default_data;
	}

	public static function get_default_connector() {
		return array(
			'wc'             => array(
				'nice_name'          => __( 'WooCommerce', 'wp-marketing-automations' ),
				'slug'               => 'wp_adv',
				'connector_slug'     => '',
				'native_integration' => true,
			),
			'wp_adv'         => array(
				'nice_name'          => __( 'WordPress Advanced', 'wp-marketing-automations' ),
				'slug'               => 'wp_adv',
				'connector_slug'     => '',
				'native_integration' => true,
			),
			'activecampaign' => array(
				'nice_name'          => __( 'ActiveCampaign', 'wp-marketing-automations' ),
				'slug'               => 'activecampaign',
				'connector_slug'     => 'bwfco_activecampaign',
				'native_integration' => false,
			),
			'drip'           => array(
				'nice_name'          => __( 'Drip', 'wp-marketing-automations' ),
				'slug'               => 'drip',
				'connector_slug'     => 'bwfco_drip',
				'native_integration' => false,
			),
			'google_sheets'  => array(
				'nice_name'          => __( 'Google Sheets', 'wp-marketing-automations' ),
				'slug'               => 'google_sheets',
				'connector_slug'     => 'bwfco_google_sheets',
				'native_integration' => false,
			),
			'slack'          => array(
				'nice_name'          => __( 'Slack', 'wp-marketing-automations' ),
				'slug'               => 'slack',
				'connector_slug'     => 'bwfco_slack',
				'native_integration' => false,
			),
			'zapier'         => array(
				'nice_name'          => __( 'Zapier', 'wp-marketing-automations' ),
				'slug'               => 'zapier',
				'connector_slug'     => '',
				'native_integration' => false,
			),
		);
	}

	public static function get_default_actions() {

		return array(
			'wc'             => array(
				'wc_change_order_status' => __( 'Change Order Status', 'wp-marketing-automations' ),
				'wc_add_order_note'      => __( 'Add Order Note', 'wp-marketing-automations' ),
				'wc_remove_coupon'       => __( 'Delete Coupon', 'wp-marketing-automations' ),
			),
			'activecampaign' => array(
				'ac_create_contact'        => __( 'Create Contact', 'wp-marketing-automations' ),
				'ac_add_tag'               => __( 'Add Tags', 'wp-marketing-automations' ),
				'ac_rmv_tag'               => __( 'Remove Tags', 'wp-marketing-automations' ),
				'ac_add_to_automation'     => __( 'Add Contact To Automation', 'wp-marketing-automations' ),
				'ac_rmv_from_automation'   => __( 'Remove Contact From Automation', 'wp-marketing-automations' ),
				'ac_add_to_list'           => __( 'Add Contact To List', 'wp-marketing-automations' ),
				'ac_rmv_from_list'         => __( 'Remove Contact From List', 'wp-marketing-automations' ),
				'ac_create_abandoned_cart' => __( 'Create Abandoned Cart', 'wp-marketing-automations' ),
				'ac_create_order'          => __( 'Create Order', 'wp-marketing-automations' ),
				'ac_create_deal'           => __( 'Create Deal', 'wp-marketing-automations' ),
				'ac_create_deal_note'      => __( 'Create Deal Note', 'wp-marketing-automations' ),
				'ac_update_deal'           => __( 'Update Deal', 'wp-marketing-automations' ),
				'ac_update_customfields'   => __( 'Update Fields', 'wp-marketing-automations' ),
			),
			'drip'           => array(
				'dr_create_subscriber' => __( 'Create / Update Subscriber', 'wp-marketing-automations' ),
				'dr_add_tags'          => __( 'Add Tags', 'wp-marketing-automations' ),
				'dr_rmv_tags'          => __( 'Remove Tags', 'wp-marketing-automations' ),
				'dr_add_to_campaign'   => __( 'Add Subscriber to Campaign', 'wp-marketing-automations' ),
				'dr_rmv_from_campaign' => __( 'Remove Subscriber from Campaign', 'wp-marketing-automations' ),
				'dr_add_to_workflow'   => __( 'Add Subscriber to Workflow', 'wp-marketing-automations' ),
				'dr_rmv_from_workflow' => __( 'Remove Subscriber from Workflow', 'wp-marketing-automations' ),
				'dr_add_cart'          => __( 'Cart Activity', 'wp-marketing-automations' ),
				'dr_add_order'         => __( 'Add A New Order', 'wp-marketing-automations' ),
				'dr_add_customfields'  => __( 'Update Custom fields of Subscriber', 'wp-marketing-automations' ),
			),
			'convertkit'     => array(
				'ck_add_customfields'  => __( 'Update Custom Fields', 'wp-marketing-automations' ),
				'ck_add_tags'          => __( 'Add Tags', 'wp-marketing-automations' ),
				'ck_rmv_tags'          => __( 'Remove Tags', 'wp-marketing-automations' ),
				'ck_add_to_sequence'   => __( 'Add Subscriber To Sequence', 'wp-marketing-automations' ),
				'ck_rmv_from_sequence' => __( 'Remove Subscriber from Sequence', 'wp-marketing-automations' ),
				'ck_add_order'         => __( 'Create A New Purchase', 'wp-marketing-automations' ),
			),
			'google_sheets'  => array(
				'gs_insert_data' => __( 'Insert Row', 'wp-marketing-automations' ),
				'gs_update_data' => __( 'Update Row', 'wp-marketing-automations' ),
			),
			'slack'          => array(
				'sl_message_user' => __( 'Sends a message to a user', 'wp-marketing-automations' ),
				'sl_message'      => __( 'Sends a message to a channel', 'wp-marketing-automations' ),
			),
			'zapier'         => array(
				'za_send_data' => __( 'Send data to zapier', 'wp-marketing-automations' ),
			),
			'twilio'         => array(
				'twilio_send_sms' => __( 'Send SMS', 'wp-marketing-automations' ),
			),
			'wp_adv'         => array(
				'wp_createuser'       => __( 'Create User', 'wp-marketing-automations' ),
				'wp_update_user_meta' => __( 'Update User Meta', 'wp-marketing-automations' ),
				'wp_http_post'        => __( 'HTTP Post', 'wp-marketing-automations' ),
				'wp_custom_callback'  => __( 'Custom Callback', 'wp-marketing-automations' ),
				'wp_debug'            => __( 'Debug', 'wp-marketing-automations' ),
			),
		);
	}

	public static function merge_pro_events( $events ) {

		$default = self::default_events();
		foreach ( $default as $slug => $data ) {
			if ( ! class_exists( 'BWFAN_' . $slug ) || ! bwfan_is_autonami_pro_active() ) {
				$source_type                                           = $data['source_type'];
				$events[ $source_type ]['events']['Upstroke'][ $slug ] = array(
					'name'      => $data['event_name'],
					'available' => 'no',
				);
			}
		}

		return $events;
	}

	public static function default_events() {
		return array();

		return array(
			'wf_offer_viewed'         => array(
				'source_type'         => 'wc',
				'event_name'          => __( 'Offer Viewed', 'wp-marketing-automations' ),
				'event_desc'          => 'This automation would trigger when an offer is viewed by the user.',
				'slug'                => 'wf_offer_viewed',
				'is_time_independent' => false,
				'excluded_actions'    => array(),
				'event_saved_data'    => array(),
				'available'           => 'no',
			),
			'wf_product_accepted'     => array(
				'source_type'         => 'wc',
				'event_name'          => __( 'Offer Accepted', 'wp-marketing-automations' ),
				'event_desc'          => 'This automation would trigger when an upstroke offer is accepted by the user.',
				'slug'                => 'wf_product_accepted',
				'is_time_independent' => false,
				'excluded_actions'    => array(),
				'event_saved_data'    => array(),
				'available'           => 'no',
			),
			'wf_offer_payment_failed' => array(
				'source_type'         => 'wc',
				'event_name'          => __( 'Offer Payment Failed', 'wp-marketing-automations' ),
				'event_desc'          => 'This automation would trigger when the payment is failed while accepting an offer.',
				'slug'                => 'wf_offer_payment_failed',
				'is_time_independent' => false,
				'excluded_actions'    => array(),
				'event_saved_data'    => array(),
				'available'           => 'no',
			),
			'wf_offer_rejected'       => array(
				'source_type'         => 'wc',
				'event_name'          => __( 'Offer Rejected', 'wp-marketing-automations' ),
				'event_desc'          => 'This automation would trigger when an offer is rejected by the user.',
				'slug'                => 'wf_offer_rejected',
				'is_time_independent' => false,
				'excluded_actions'    => array(),
				'event_saved_data'    => array(),
				'available'           => 'no',
			),
		);
	}

	/**
	 * Get Autonami notifications only
	 *
	 * @return array
	 */
	public static function get_autonami_notifications() {
		if ( ! class_exists( 'WooFunnels_Notifications' ) ) {
			return array();
		}
		$notifications_list = WooFunnels_Notifications::get_instance()->get_all_notifications();
		if ( ! is_array( $notifications_list ) || ! isset( $notifications_list['bwfan'] ) ) {
			return array();
		}
		if ( ! is_array( $notifications_list['bwfan'] ) || count( $notifications_list['bwfan'] ) === 0 ) {
			return array();
		}

		return array(
			'bwfan' => $notifications_list['bwfan'],
		);
	}

	/**
	 * Used as fallback to make sure all the tasks run on the thank you page
	 */
	public static function hit_cron_to_run_tasks() {
		$url  = rest_url( '/autonami/v1/autonami-cron' );
		$args = bwf_get_remote_rest_args( array(), 'GET' );
		wp_remote_post( $url, $args );
	}

	public static function add_ordinal_number_suffix( $num ) {
		if ( ! in_array( ( $num % 100 ), array( 11, 12, 13 ), true ) ) {
			switch ( $num % 10 ) {
				// Handle 1st, 2nd, 3rd
				case 1:
					return $num . 'st';
				case 2:
					return $num . 'nd';
				case 3:
					return $num . 'rd';
			}
		}

		return $num . 'th';
	}

	public static function bwfan_recipe_list_template() {
		ob_start();

		include plugin_dir_path( __FILE__ ) . 'bwfan-recipe-list-template.php';

		return ob_get_clean();
	}

	public static function auto_apply_wc_coupon() {
		if ( ! isset( $_GET['bwfan-coupon'] ) || empty( $_GET['bwfan-coupon'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput
			return;
		}
		$coupon_code = $_GET['bwfan-coupon']; //phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput

		if ( WC()->cart instanceof WC_Cart && ! empty( $coupon_code ) && ! WC()->cart->has_discount( $coupon_code ) ) {
			/** Keep existing wc notices */
			$wc_notices = wc_get_notices();

			WC()->cart->add_discount( $coupon_code );

			/** Add all old wc notices back */
			WC()->session->set( 'wc_notices', $wc_notices );
		}
	}

	public static function mark_automation_require_update( $automation_id, $state = true ) {
		if ( empty( $automation_id ) ) {
			return;
		}

		$meta_data = array(
			'meta_value' => ( true === $state ) ? 1 : 0,
		);
		$where     = array(
			'bwfan_automation_id' => $automation_id,
			'meta_key'            => 'requires_update',
		);
		BWFAN_Model_Automationmeta::update( $meta_data, $where );
	}

	/**
	 * checking plugin dependency
	 *
	 * @param $plugin_depend
	 *
	 * @return array|bool
	 */
	public static function plugin_dependency_check( $plugin_depend ) {
		if ( empty( $plugin_depend ) ) {
			return true;
		}
		$plugin_error = array();
		foreach ( $plugin_depend as $plugins ) {
			$function_name = 'bwfan_is_' . $plugins . '_active';

			/** checking if function exists */
			if ( ! function_exists( $function_name ) ) {
				continue;
			}

			if ( false === $function_name() ) {
				$nice_name      = self::plugin_dependency_nice_names( $plugins );
				$plugin_error[] = "{$nice_name} plugin is not active.";
			}
		}

		return empty( $plugin_error ) ? true : $plugin_error;
	}

	public static function plugin_dependency_nice_names( $slug ) {
		switch ( $slug ) {
			case 'woocommerce':
				$slug = 'WooCommerce';
				break;
			case 'edd':
				$slug = 'Easy Digital Downloads';
				break;
			case 'woocommerce_subscriptions':
				$slug = 'WooCommerce Subscriptions';
				break;
			case 'woocommerce_membership':
				$slug = 'WooCommerce Membership';
				break;
			case 'woofunnels_upstroke':
				$slug = 'FunnelKit Upsells';
				break;
			case 'autonami_pro':
				$slug = 'FunnelKit Automations Pro';
				break;
			case 'autonami_connector':
				$slug = 'FunnelKit Automations Connectors';
				break;
			case 'affiliatewp':
				$slug = 'AffiliateWP';
				break;
		}

		return $slug;
	}

	/**
	 * Load Hooks after Action Scheduler is loaded
	 */
	public static function bwf_after_action_scheduler_load() {
		/** Schedule WP cron event */
		add_action( 'admin_init', array( __CLASS__, 'maybe_set_bwf_ct_worker' ) );

	}

	/**
	 * Register WP cron schedules
	 *
	 * @param $schedules
	 *
	 * @return void
	 */
	public static function add_schedules( $schedules ) {
		$schedules['fka_eight_hours'] = [
			'interval' => 28800,
			'display'  => __( 'Every Eight hours', 'wp-marketing-automations' )
		];

		return $schedules;
	}

	/**
	 * Scheduling event with core callback
	 */
	public static function maybe_set_bwf_ct_worker() {
		if ( ! wp_next_scheduled( 'bwf_as_run_queue' ) ) {
			wp_schedule_event( time(), 'bwf_every_minute', 'bwf_as_run_queue' );
		}

		if ( ! wp_next_scheduled( 'fka_clear_duplicate_actions' ) ) {
			wp_schedule_event( time(), 'fka_eight_hours', 'fka_clear_duplicate_actions' );
		}
	}

	public static function hide_free_products_cart_order_items() {
		return apply_filters( 'bwfan_items_display_hide_free_products', false );
	}

	/**
	 * Return if emogrifier library is supported.
	 *
	 * @return bool
	 * @since 3.5.0
	 */
	public static function supports_emogrifier() {
		return class_exists( 'DOMDocument' ) && version_compare( PHP_VERSION, '5.5', '>=' );
	}

	public static function color_light_or_dark( $color, $dark = '#000000', $light = '#FFFFFF' ) {
		return self::color_hex_is_light( $color ) ? $dark : $light;
	}

	public static function color_hex_is_light( $color ) {
		$hex = str_replace( '#', '', $color );

		$c_r = hexdec( substr( $hex, 0, 2 ) );
		$c_g = hexdec( substr( $hex, 2, 2 ) );
		$c_b = hexdec( substr( $hex, 4, 2 ) );

		$brightness = ( ( $c_r * 299 ) + ( $c_g * 587 ) + ( $c_b * 114 ) ) / 1000;

		return $brightness > 155;
	}

	public static function maybe_clear_cache() {

		/**
		 * Clear WordPress cache
		 */
		if ( function_exists( 'wp_cache_flush' ) ) {
			wp_cache_flush();
		}

		/**
		 * Checking if wp fastest cache installed
		 * Clear cache of wp fastest cache
		 */
		if ( class_exists( 'WpFastestCache' ) ) {
			global $wp_fastest_cache;
			if ( method_exists( $wp_fastest_cache, 'deleteCache' ) ) {
				$wp_fastest_cache->deleteCache();
			}

			// clear all cache
			if ( function_exists( 'wpfc_clear_all_cache' ) ) {
				wpfc_clear_all_cache( true );
			}
		}

		/**
		 * Checking if wp Autoptimize installed
		 * Clear cache of Autoptimize
		 */
		if ( class_exists( 'autoptimizeCache' ) ) {
			autoptimizeCache::clearall();
		}

		/**
		 * Checking if W3Total Cache plugin activated.
		 * Clear cache of W3Total Cache plugin
		 */
		if ( function_exists( 'w3tc_flush_all' ) ) {
			w3tc_flush_all();
		}
	}

	/**
	 * Updating marketing_status in order meta
	 *
	 * @param $order_id
	 * @param $posted_data
	 * @param WC_Order $order
	 */
	public static function bwfan_update_order_user_consent( $order ) {
		$marketing_status = isset( $_POST['bwfan_user_consent'] ) ? absint( $_POST['bwfan_user_consent'] ) : 0; //phpcs:ignore WordPress.Security.NonceVerification
		$order->update_meta_data( 'marketing_status', $marketing_status );
	}

	/** updating contact marketing status
	 *
	 * @param WooFunnels_Contact $contact
	 * @param int $order_id
	 */
	public static function save_marketing_status_for_user( $contact, $order_id ) {
		$order = wc_get_order( absint( $order_id ) );
		if ( ! $order instanceof WC_Order ) {
			return;
		}

		$marketing_status = $order->get_meta( 'marketing_status' );
		if ( empty( $marketing_status ) ) {
			return;
		}

		/** Below code will mark the contact subscribe as we get user consent from the checkout */

		/** If unsubscribed rows found for the contact, then remove */
		$has_unsubscribed = self::maybe_delete_unsubscribe_rows( $contact );

		/** in case contact already subscribed and earlier not unsubscribe than return */
		if ( 1 === intval( $contact->get_status() ) && false === $has_unsubscribed ) {
			return;
		}

		/** Added filter to allow double optin and can still make the contact unverified in case of consent is checked */
		$is_single_optin = apply_filters( 'bwfcrm_contact_enable_single_optin', true, $contact, $order, $marketing_status );
		if ( false === $is_single_optin ) {
			$contact->set_status( 0 );
			$contact->save();

			return;
		}

		/** Manually set the prop to trigger the subscribe event run in case it was unsubscribed earlier */
		if ( true === $has_unsubscribed ) {
			$contact->is_subscribed = true;
		}

		$contact->set_status( 1 );
		$contact->save();
	}

	/**
	 * Save marketing status if order failed
	 *
	 * @param $order_id
	 * @param $order
	 *
	 * @return void
	 */
	public static function woocommerce_order_status_failed( $order_id, $order ) {
		if ( ! $order instanceof WC_Order ) {
			return;
		}
		$cid     = $order->get_meta( '_woofunnel_cid' );
		$contact = '';
		if ( 0 === intval( $cid ) ) {
			$contact = new WooFunnels_Contact( '', '', '', $cid );
		}

		/** Get contact object by email or user id */
		if ( empty( $contact ) || empty( $contact->get_id() ) ) {
			$contact = bwf_get_contact( $order->get_user_id(), $order->get_billing_email() );
		}

		if ( empty( $contact ) || empty( $contact->get_id() ) ) {
			return;
		}

		self::save_marketing_status_for_user( $contact, $order_id );
	}

	public static function get_form_submit_events() {
		return apply_filters( 'bwfan_get_form_submit_events', array( 'BWFAN_CF7_Form_Submit' ) );
	}

	/**
	 * Get automations by title for different versions
	 *
	 * @param $search
	 * @param $version
	 * @param $ids
	 *
	 * @return array|object|stdClass[]|null
	 */
	public static function get_automation_by_title( $search, $version = 0, $ids = [], $limit = 10, $offset = 0 ) {
		global $wpdb;

		/** Search all automation from v1 and v2 */
		if ( 0 === absint( $version ) ) {
			return self::search_automation_by_title( $search, $limit, $offset );
		}

		$query    = '';
		$id_query = '';

		if ( ! empty( $ids ) ) {
			$id_query = " AND ID IN(" . implode( ',', $ids ) . ") ";
		}

		$where = '';
		switch ( absint( $version ) ) {
			case  2 :
				if ( ! empty( $search ) ) {
					$where = $wpdb->prepare( " AND title LIKE %s ", '%' . $search . '%' );
				}
				$query = "SELECT ID as id, title  FROM {$wpdb->prefix}bwfan_automations WHERE 1=1 $where $id_query ORDER BY title ASC";
				break;
			case  1 :
				if ( ! empty( $search ) ) {
					$where = $wpdb->prepare( " AND am.meta_value LIKE %s AND am.meta_key = 'title'", '%' . $search . '%' );
				}
				$query = "SELECT am.bwfan_automation_id AS id,am.meta_value AS title FROM {$wpdb->prefix}bwfan_automationmeta AS am WHERE 1=1 $where $id_query GROUP BY am.bwfan_automation_id ORDER BY am.meta_value ASC";
				break;
		}

		if ( ! empty( $limit ) ) {
			$query .= " LIMIT %d, %d";
			$query = $wpdb->prepare( $query, $offset, $limit );
		}

		return ! empty( $query ) ? $wpdb->get_results( $query, ARRAY_A ) : [];;
	}

	/**
	 * Get automations by title
	 *
	 * @param $search
	 * @param $limit
	 * @param $offset
	 *
	 * @return array|object|stdClass[]|null
	 */
	public static function search_automation_by_title( $search = '', $limit = 10, $offset = 0 ) {
		global $wpdb;
		$where = '';
		/** Search automations by title form automation table */
		if ( ! empty( $search ) ) {
			$where = $wpdb->prepare( " WHERE title LIKE %s ", '%' . $search . '%' );
		}
		$query = "SELECT ID as id, title FROM {$wpdb->prefix}bwfan_automations $where ORDER BY title ASC";
		if ( ! empty( $limit ) ) {
			$query .= " LIMIT %d, %d";
			$query = $wpdb->prepare( $query, $offset, $limit );
		}
		$automations = $wpdb->get_results( $query, ARRAY_A );

		/** Search automations by title form automation meta table */
		if ( ! empty( $search ) ) {
			$where = $wpdb->prepare( " WHERE am.meta_value LIKE %s AND am.meta_key = 'title'", '%' . $search . '%' );
		}
		$meta_query = "SELECT am.bwfan_automation_id AS id,am.meta_value AS title FROM {$wpdb->prefix}bwfan_automationmeta AS am $where GROUP BY am.bwfan_automation_id ORDER BY am.meta_value ASC";
		if ( ! empty( $limit ) ) {
			$meta_query .= " LIMIT %d, %d";
			$meta_query = $wpdb->prepare( $meta_query, $offset, $limit );
		}

		$automation_meta = $wpdb->get_results( $meta_query, ARRAY_A );

		return array_merge( $automations, $automation_meta );
	}

	/**
	 * @param $search
	 * @param $offset
	 * @param $limit
	 */
	public static function get_unsubscribers( $search, $offset, $limit ) {
		global $wpdb;
		$where = '';
		/** Check for search unsubscriber */
		if ( ! empty( $search ) ) {
			$where = $wpdb->prepare( " WHERE `recipient` LIKE %s ", '%' . $search . '%' );
		}

		/** Query to fetch unsubscribers data from DB */
		$unsubscribers = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}bwfan_message_unsubscribe $where ORDER BY ID DESC LIMIT $offset,$limit " );//phpcs:ignore WordPress.DB.PreparedSQL
		if ( empty( $unsubscribers ) ) {
			return array();
		}

		$found_posts = array();
		$items       = array();

		foreach ( $unsubscribers as $unsubscriber ) {
			$c_type = absint( $unsubscriber->c_type );
			$oid    = empty( $unsubscriber->automation_id ) ? 0 : absint( $unsubscriber->automation_id );
			$otype  = '';
			$oname  = '';

			if ( 1 === $c_type ) {
				$automation_meta = BWFAN_Core()->automations->get_automation_data_meta( $oid );
				$oname           = isset( $automation_meta['title'] ) && ! empty( $automation_meta['title'] ) ? $automation_meta['title'] : __( 'No Title', 'wp-marketing-automations' );
				$otype           = __( 'Automation', 'wp-marketing-automations' );
			} elseif ( 2 === $c_type ) {
				$broadcast = bwfan_is_autonami_pro_active() ? BWFAN_Model_Broadcast::get( $oid ) : array();
				$oname     = isset( $broadcast['title'] ) ? $broadcast['title'] : __( 'No Title', 'wp-marketing-automations' );
				$otype     = __( 'Broadcast', 'wp-marketing-automations' );
			} elseif ( $c_type > 2 ) {
				$otype = __( 'Manual', 'wp-marketing-automations' );
			}

			$items[] = array(
				'id'              => $unsubscriber->ID,
				'recipient'       => $unsubscriber->recipient,
				'date'            => date( self::get_date_format(), strtotime( $unsubscriber->c_date ) ),
				'automation_id'   => $oid,
				'automation_name' => empty( $oname ) && empty( $otype ) ? '-' : ( empty( $oname ) ? $otype : "$otype: $oname" ),
				'source_type'     => $c_type,
			);
		}

		$found_posts['found_posts'] = BWFAN_Model_Message_Unsubscribe::count_rows();
		$found_posts['items']       = $items;

		return $found_posts;
	}

	public static function get_date_format() {
		return get_option( 'date_format', '' ) . ' ' . get_option( 'time_format', '' );
	}

	/** run global tools
	 *
	 * @param $tool_type
	 *
	 * @return array
	 */
	public static function run_global_tools( $tool_type ) {
		global $wpdb;
		$result = array();

		switch ( $tool_type ) {
			case 'run_all_tasks':
				$result['msg'] = __( 'All queued tasks have been scheduled to run now', 'wp-marketing-automations' );
				$all_tasks     = BWFAN_Core()->tasks->get_all_tasks();
				if ( ! is_array( $all_tasks ) || 0 === count( $all_tasks ) ) {
					$result['msg']    = __( 'There are no tasks.', 'wp-marketing-automations' );
					$result['status'] = false;

					return $result;
				}

				$task_ids = array();
				foreach ( $all_tasks as $task_id => $task_details ) {  //phpcs:ignore WordPressVIPMinimum.Variables.VariableAnalysis
					$task_ids[] = $task_id;
				}
				BWFAN_Core()->tasks->rescheduled_tasks( true, $task_ids );

				break;
			case 'delete_completed_tasks':
				$logs = $wpdb->get_results( $wpdb->prepare( "
									SELECT ID
									FROM {$wpdb->prefix}bwfan_logs
									WHERE `status` = %d
									", 1 ) );

				if ( ! empty( $logs ) ) {
					$completed_tasks = array();
					foreach ( $logs as $log ) {
						$completed_tasks[] = $log->ID;
					}
					BWFAN_Core()->logs->delete_logs( $completed_tasks );
				}

				$result['msg'] = __( 'All completed tasks successfully deleted.', 'wp-marketing-automations' );

				break;
			case 'delete_failed_tasks':
				$logs = $wpdb->get_results( $wpdb->prepare( "
									SELECT ID
									FROM {$wpdb->prefix}bwfan_logs
									WHERE `status` = %d
									", 0 ) );

				if ( ! empty( $logs ) ) {
					$failed_tasks = array();
					foreach ( $logs as $log ) {
						$failed_tasks[] = $log->ID;
					}
					BWFAN_Core()->logs->delete_logs( $failed_tasks );
				}

				$result['msg'] = __( 'All failed tasks successfully deleted.', 'wp-marketing-automations' );

				break;
			case 'delete_previous_logs_automation':
				if ( false !== bwf_has_action_scheduled( 'bwfan_delete_older_logs' ) ) {
					$result['msg']    = __( 'A process is already scheduled for deleting old logs of this automation', 'wp-marketing-automations' );
					$result['status'] = false;

					return $result;
				}

				$data_inputs           = isset( $_POST['data_inputs'] ) ? json_decode( self::remove_backslashes( $_POST['data_inputs'] ), true ) : array(); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput, WordPress.Security.NonceVerification
				$automation_id         = $data_inputs['automation_id'];
				$result['msg']         = __( 'Process scheduled for deleting old logs of this automation', 'wp-marketing-automations' );
				$data                  = array();
				$data['days']          = apply_filters( 'bwfan_logs_days_deletion_limit', 15 );
				$data['limit']         = apply_filters( 'bwfan_logs_days_deletion_count', 200 );
				$data['automation_id'] = intval( $automation_id );

				bwf_schedule_single_action( time(), 'bwfan_delete_older_logs', $data );

				break;
			case 'delete_expired_coupons':
				if ( false !== bwf_has_action_scheduled( 'bwfan_delete_expired_coupons' ) ) {
					$result['msg']    = __( 'A process is already scheduled for deleting expired Funnelkit Automation generated coupons', 'wp-marketing-automations' );
					$result['status'] = false;

					return $result;
				}

				$result['msg'] = __( 'Process scheduled for deleting expired Funnelkit Automation generated coupons', 'wp-marketing-automations' );

				bwf_schedule_recurring_action( time(), 2, 'bwfan_delete_expired_coupons', array() );

				break;
			case 'delete_lost_carts':
				$wpdb->query( $wpdb->prepare( "
									DELETE
									FROM {$wpdb->prefix}bwfan_abandonedcarts
									WHERE `status` = %d
									", 2 ) );

				$result['msg'] = __( 'All lost carts successfully deleted.', 'wp-marketing-automations' );

				break;
		}

		return $result;
	}

	/** get setting schema
	 *
	 * @return array[]
	 */
	public static function get_setting_schema() {
		if ( ! function_exists( 'get_editable_roles' ) ) {
			require_once ABSPATH . 'wp-admin/includes/user.php';
		}
		$editable_roles = get_editable_roles();
		$user_roles     = array();
		if ( $editable_roles ) {
			foreach ( $editable_roles as $role => $details ) {
				$name                         = translate_user_role( $details['name'] );
				$user_roles[ $role ]['label'] = $name;
				$user_roles[ $role ]['value'] = $role;
			}
		}
		$user_roles = array_values( $user_roles );

		/** SMS Service Providers */
		$sms_options         = self::get_sms_services();
		$is_twilio_connected = ( is_array( $sms_options ) && isset( $sms_options['bwfco_twilio'] ) );
		$sms_options         = array_map( function ( $sms, $slug ) {
			return array(
				'label' => $sms,
				'value' => $slug,
			);
		}, $sms_options, array_keys( $sms_options ) );

		/** Email Service Providers */
		$email_options = self::get_email_services();
		$email_options = array_map( function ( $email, $slug ) {
			return array(
				'label' => $email,
				'value' => $slug,
			);
		}, $email_options, array_keys( $email_options ) );

		$show_fields = true;

		if ( function_exists( 'bwfan_is_autonami_pro_active' ) && ! bwfan_is_autonami_pro_active() ) {
			$show_fields = false;
		}

		$bounce_settings_schema = self::get_bounce_settings_schema();

		$bounce_settings_schema = array_merge( $bounce_settings_schema, array(
			array(
				'id'          => 'bwfan_email_per_second_limit',
				'label'       => __( 'Max Sending Limit (per sec)', 'wp-marketing-automations' ),
				'type'        => 'number',
				'class'       => 'bwfan_email_per_second_limit',
				'placeholder' => '15',
				'hint'        => __( 'Enter maximum email sending limit. Note: This is an indicative limit and not the actual sending rate for Funnelkit Automation. The real sending rate would vary based on site configuration, plugin used and even SMTP API connection.', 'wp-marketing-automations' ),
				'required'    => false,
				'show'        => $show_fields,
				'toggler'     => array(),
			),
			array(
				'id'          => 'bwfan_email_daily_limit',
				'label'       => __( 'Max Sending Limit (per day)', 'wp-marketing-automations' ),
				'type'        => 'number',
				'class'       => 'bwfan_email_daily_limit ',
				'placeholder' => '10000',
				'hint'        => __( 'Enter maximum email sending limit allowed in 24 hours', 'wp-marketing-automations' ),
				'required'    => false,
				'show'        => $show_fields,
				'toggler'     => array(),
			)
		) );

		$email_field = array(
			array(
				'id'          => 'bwfan_email_from_name',
				'label'       => __( 'From Name', 'wp-marketing-automations' ),
				'type'        => 'text',
				'class'       => 'bwfan_email_from_name',
				'placeholder' => 'Enter Name',
				'hint'        => __( 'Name that will be used to send emails', 'wp-marketing-automations' ),
				'required'    => false,
				'toggler'     => array(),
			),
			array(
				'id'          => 'bwfan_email_from',
				'label'       => __( 'From Email', 'wp-marketing-automations' ),
				'type'        => 'text',
				'class'       => 'bwfan_email_from',
				'placeholder' => 'Enter Email',
				'hint'        => __( 'Valid email address that will be used to send emails', 'wp-marketing-automations' ),
				'required'    => false,
				'toggler'     => array(),
			),
			array(
				'id'          => 'bwfan_email_reply_to',
				'label'       => __( 'Reply To Email', 'wp-marketing-automations' ),
				'type'        => 'text',
				'class'       => 'bwfan_email_reply_to',
				'placeholder' => 'Enter Email',
				'hint'        => __( 'Valid email address that will be used to receive replies', 'wp-marketing-automations' ),
				'required'    => false,
				'toggler'     => array(),
			),
			array(
				'id'          => 'bwfan_email_footer_setting',
				'label'       => __( 'Unsubscribe Text', 'wp-marketing-automations' ),
				'type'        => 'richeditor',
				'class'       => 'bwfan_setting_business_name',
				'required'    => false,
				'wrap_before' => '<h3>Footer</h3>',
				'toggler'     => array(),
				'hint'        => __( 'Anti-spam laws require you to put a physical address and an unsubscribe link at the bottom of every email. Use dynamic tags <b>{{business_name}}</b> for Business Name', 'wp-marketing-automations' ) . ', ' . __( '<b>{{business_address}}</b> for Business Address', 'wp-marketing-automations' ) . ' and ' . __( '<b>{{unsubscribe_link}}</b> for Unsubscribe page link', 'wp-marketing-automations' ),
			),
		);

		if ( ! empty( $bounce_settings_schema ) ) {
			$email_field = array_merge( $email_field, $bounce_settings_schema );
		}

		/** added email provider setting in case of multiple provider */
		if ( count( $email_options ) > 1 ) {
			$email_provider_schema = array(
				array(
					'id'       => 'bwfan_email_service',
					'label'    => __( 'Default Email Provider', 'wp-marketing-automations' ),
					'type'     => 'select',
					'class'    => '',
					'options'  => $email_options,
					'required' => false,
					'multiple' => false,
					'hint'     => __( 'Select default provider for sending emails', 'wp-marketing-automations' ),
					'toggler'  => array(),
				),
			);
			$email_field           = array_merge( $email_field, $email_provider_schema );
		}

		$twilio_sms_unsubscribe_settings = [];
		$twilio_sms_sort_url             = [];
		$unsubscribe_text                = [];
		$messaging_service_sid           = [];

		/** getting the twilio sms webhook link for unsubscribes in case connector and class exists */
		if ( $is_twilio_connected ) {
			if ( bwfan_is_autonami_connector_active() && class_exists( 'BWFAN_Twilio_SMS_Unsubscribe' ) ) {
				$twilio_sms_unsub_instance       = new BWFAN_Twilio_SMS_Unsubscribe();
				$twilio_webhook                  = $twilio_sms_unsub_instance->get_webhooks();
				$twilio_sms_unsubscribe_settings = array(
					'id'        => 'bwfan_sms_webhook_twilio',
					'label'     => __( 'Unsubscribe Webhook URL', 'wp-marketing-automations' ),
					'type'      => 'copier',
					'class'     => 'bwfan_sms_webhook_twilio',
					'hint'      => __( "Paste this URL into your Twilio's settings to stop sending messages to the contacts. <a href='https://funnelkit.com/docs/autonami-2/sms-broadcasts/unsubscribe-text/'>Learn more</a> on how to set up.", 'wp-marketing-automations' ),
					'required'  => false,
					'copy_text' => $twilio_webhook['link'],
				);
				if ( true === apply_filters( 'bwfan_enable_twilio_advanced_settings', false ) ) {
					$twilio_sms_sort_url   = array(
						'id'            => 'bwfan_twilio_shorten_url',
						'label'         => __( 'Enable URL Shortening', 'wp-marketing-automations' ),
						'type'          => 'checkbox',
						'checkboxlabel' => __( "Twilio's native URL shortening", 'wp-marketing-automations' ),
						'hint'          => __( "Enable URL shortening by Twilio. <a href='https://www.twilio.com/docs/messaging/features/link-shortening' target='_blank'>Click here</a> to understand more.", "wp-marketing-automations" ),
						'required'      => false,
					);
					$messaging_service_sid = array(
						'id'       => 'bwfan_twilio_messaging_service_sid',
						'label'    => __( 'Messaging Service ID', 'wp-marketing-automations' ),
						'type'     => 'text',
						'hint'     => __( "Messaging Service ID is required for shortening.", 'wp-marketing-automations' ),
						'required' => false,
						'toggler'  => array(
							'fields'   => array(
								array(
									'id'    => 'bwfan_twilio_shorten_url',
									'value' => true,
								),
							),
							'relation' => 'OR',
						),
					);
				}
			}
			$unsubscribe_text = array(
				'id'           => 'bwfan_sms_unsubscribe_text',
				'label'        => __( 'Text', 'wp-marketing-automations' ),
				'type'         => 'text',
				'class'        => 'bwfan_sms_unsubscribe_text',
				'placeholder'  => __( 'Enter Unsubscribe Text', 'wp-marketing-automations' ),
				'required'     => true,
				'disabled'     => method_exists( 'BWFCRM_Common', 'get_sms_provider_slug' ) && ! empty( BWFCRM_Common::get_sms_provider_slug() ) ? false : true,
				'wrap_before'  => '<h3>Unsubscribe Text</h3>',
				'isProSetting' => true,
			);
		}
		$sms_fields = array(
			$unsubscribe_text,
			$twilio_sms_unsubscribe_settings,
			$twilio_sms_sort_url,
			$messaging_service_sid,
			array(
				'id'            => 'bwfan_disable_sms_tracking',
				'label'         => __( 'Disable Click Tracking', 'wp-marketing-automations' ),
				'type'          => 'checkbox',
				'checkboxlabel' => __( 'Disable Click Tracking for all SMS Broadcasts and Automations', 'wp-marketing-automations' ),
				'hint'          => __( 'Click tracking adds special variables to the URL that increases the SMS character limit. Enable this setting to reduce the character limit. Note: Funnelkit Automation cannot track conversion rate if click tracking is disabled.', 'wp-marketing-automations' ),
				'class'         => 'bwfan_disable_sms_tracking',
				'required'      => false,
			),
		);

		/** added email provider setting in case of multiple provider */
		if ( count( $sms_options ) > 1 ) {
			$sms_provider_schema = array(
				array(
					'id'       => 'bwfan_sms_service',
					'label'    => __( 'Default SMS Provider', 'wp-marketing-automations' ),
					'type'     => 'select',
					'class'    => '',
					'options'  => $sms_options,
					'required' => false,
					'multiple' => false,
					'hint'     => __( 'Select default provider for sending SMS', 'wp-marketing-automations' ),
					'toggler'  => array(),
				),
			);
			$sms_fields          = array_merge( $sms_fields, $sms_provider_schema );
		}

		$general_fields   = [];
		$general_fields[] = array(
			'id'          => 'autonami_pro',
			'label'       => __( 'FunnelKit Automations Pro', 'wp-marketing-automations' ),
			'type'        => 'license',
			'license'     => self::get_pro_license( false ),
			'wrap_before' => '<h3 style="margin-bottom: 0;">License</h3>',
			'toggler'     => array(),
		);
		if ( true === bwfan_is_autonami_pro_active() && true === bwfan_is_autonami_connector_active() ) {
			$general_fields[] = array(
				'id'               => 'autonami_connector',
				'label'            => __( 'FunnelKit Automations Connectors', 'wp-marketing-automations' ),
				'type'             => 'license',
				'isConnectorField' => true,
				'license'          => self::get_connector_license( false ),
				'toggler'          => array(),
			);
		}
		$general_fields[] = array(
			'id'          => 'bwfan_setting_business_name',
			'label'       => __( 'Business Name', 'wp-marketing-automations' ),
			'type'        => 'text',
			'class'       => 'bwfan_setting_business_name',
			'placeholder' => 'Enter Business Name',
			'hint'        => '',
			'required'    => false,
			'wrap_before' => '<h3>Business Details</h3><p><i>Anti-spam laws require you to put a physical address at the bottom of every email where you can be reached.</i></p>',
			'toggler'     => array(),
		);
		$general_fields[] = array(
			'id'          => 'bwfan_setting_business_address',
			'label'       => __( 'Business Address', 'wp-marketing-automations' ),
			'type'        => 'text',
			'class'       => 'bwfan_setting_business_address',
			'placeholder' => 'Enter Business Address',
			'hint'        => '',
			'required'    => false,
			'toggler'     => array(),
		);

		$checkout_consent_fields         = [];
		$ab_email_consent_message_fields = [];
		$checkout_consent_fields[]       = array(
			'id'       => 'bwfan_user_consent_message',
			'label'    => __( 'Text', 'wp-marketing-automations' ),
			'type'     => 'textarea',
			'class'    => '',
			'required' => false,
			'toggler'  => array(
				'fields'   => array(
					array(
						'id'    => 'bwfan_user_consent',
						'value' => true,
					),
				),
				'relation' => 'OR',
			),
		);

		$ab_email_consent_message_fields[] = array(
			'id'       => 'bwfan_ab_email_consent_message',
			'type'     => 'textarea',
			'class'    => 'bwfan_ab_email_consent_message',
			'required' => false,
			'hint'     => __( "Use merge tag {{no_thanks label='No Thanks'}} to let users opt out of cart tracking.", 'wp-marketing-automations' ),
			'toggler'  => array(
				'fields'   => array(
					array(
						'id'    => 'bwfan_ab_enable',
						'value' => true,
					),
					array(
						'id'    => 'bwfan_ab_email_consent',
						'value' => true,
					),
				),
				'relation' => 'AND',
			),
		);
		if ( bwfan_is_autonami_pro_active() && method_exists( 'BWFAN_PRO_Common', 'get_language_settings' ) ) {
			$language_data = BWFAN_PRO_Common::get_language_settings();
			if ( is_array( $language_data ) && isset( $language_data['lang_options'] ) && ! empty( $language_data['lang_options'] ) ) {
				$default_language = strval( get_locale() );

				/** Change default language field label */
				$checkout_consent_fields[0]['label'] = __( 'Text (Default)', 'wp-marketing-automations' );

				foreach ( $language_data['lang_options'] as $lang_slug => $lang_name ) {
					if ( $default_language === strval( $lang_slug ) ) {
						continue;
					}
					$checkout_consent_fields[] = array(
						'id'       => 'bwfan_user_consent_message_' . $lang_slug,
						'label'    => __( 'Text (' . $lang_name . ')', 'wp-marketing-automations' ),
						'type'     => 'textarea',
						'class'    => '',
						'required' => false,
						'toggler'  => array(
							'fields'   => array(
								array(
									'id'    => 'bwfan_user_consent',
									'value' => true,
								),
							),
							'relation' => 'OR',
						),
					);

					$ab_email_consent_message_fields[] = array(
						'id'       => 'bwfan_ab_email_consent_message_' . $lang_slug,
						'type'     => 'textarea',
						'class'    => 'bwfan_ab_email_consent_message',
						'required' => false,
						'hint'     => __( "( $lang_name ) Use merge tag {{no_thanks label='No Thanks'}} to let users opt out of cart tracking.", 'wp-marketing-automations' ),
						'toggler'  => array(
							'fields'   => array(
								array(
									'id'    => 'bwfan_ab_enable',
									'value' => true,
								),
								array(
									'id'    => 'bwfan_ab_email_consent',
									'value' => true,
								),
							),
							'relation' => 'AND',
						),
					);
				}
			}
		}

		$settings = array(
			array(
				'key'     => 'general',
				'label'   => 'General', // label is used for left side tab item
				'heading' => 'General Settings',
				'tabs'    => array(
					array(
						'key'     => 'general',
						'label'   => 'General', // label is used for left side tab item
						'heading' => 'General',
						'fields'  => $general_fields,
					),
					array(
						'key'     => 'emails',
						'label'   => 'Email', // label is used for left side tab item
						'heading' => 'Email Settings',
						'fields'  => $email_field,
					),
					array(
						'key'     => 'sms',
						'label'   => 'SMS', // label is used for left side tab item
						'heading' => 'SMS Settings',
						'fields'  => $sms_fields,
					),
					array(
						"key"         => 'whatsapp',
						"label"       => 'WhatsApp', // label is used for left side tab item
						"heading"     => 'WhatsApp',
						"showSection" => bwfan_is_autonami_pro_active() ? BWFCRM_Core()->conversation->is_whatsapp_service_available() : false,
						'fields'      => self::get_whatsapp_services_fields(),
					),
					array(
						"key"          => 'abandonment',
						"label"        => 'Cart', // label is used for left side tab item
						"heading"      => 'Cart',
						"isWooSection" => true,
						"fields"       => array_merge( array(
							array(
								'id'            => 'bwfan_ab_enable',
								'label'         => __( 'Enable Cart Tracking', 'wp-marketing-automations' ),
								'type'          => 'checkbox',
								'checkboxlabel' => __( "Enable to live capture buyer's email & cart details", 'wp-marketing-automations' ),
								'class'         => 'bwfan_ab_enable',
								'required'      => false,
								'toggler'       => array(),
							),
							array(
								'id'          => 'bwfan_ab_init_wait_time',
								'label'       => __( 'Wait Period (minutes)', 'wp-marketing-automations' ),
								'type'        => 'number',
								'class'       => '',
								'placeholder' => '15',
								'hint'        => __( 'Wait for a given time before the cart is marked as Recoverable', 'wp-marketing-automations' ),
								'required'    => false,
								'toggler'     => array(
									'fields'   => array(
										array(
											'id'    => 'bwfan_ab_enable',
											'value' => true,
										),
									),
									'relation' => 'OR',
								),
							),
							array(
								'id'          => 'bwfan_disable_abandonment_days',
								'label'       => __( 'Cool Off Period (days)', 'wp-marketing-automations' ),
								'type'        => 'number',
								'class'       => '',
								'placeholder' => '15',
								'required'    => false,
								'hint'        => __( 'Exclude customers from cart abandonment tracking if the order was placed days ago (recommended 15 days)', 'wp-marketing-automations' ),
								'toggler'     => array(
									'fields'   => array(
										array(
											'id'    => 'bwfan_ab_enable',
											'value' => true,
										),
									),
									'relation' => 'OR',
								),
							),
							array(
								'id'          => 'bwfan_ab_mark_lost_cart',
								'label'       => __( 'Lost Cart (days)', 'wp-marketing-automations' ),
								'type'        => 'number',
								'class'       => '',
								'placeholder' => '15',
								'required'    => false,
								'hint'        => __( 'Mark the user as Lost if the order is not made within the given days', 'wp-marketing-automations' ),
								'toggler'     => array(
									'fields'   => array(
										array(
											'id'    => 'bwfan_ab_enable',
											'value' => true,
										),
									),
									'relation' => 'OR',
								),
							),
							array(
								'id'            => 'bwfan_ab_email_consent',
								'label'         => __( 'Notice', 'wp-marketing-automations' ),
								'type'          => 'checkbox',
								'checkboxlabel' => __( 'When entering email addresses, inform customers that their email and cart data are saved to send abandonment reminders', 'wp-marketing-automations' ),
								'class'         => 'bwfan_ab_email_consent',
								'required'      => false,
								'wrap_before'   => '<h3>GDPR Consent</h3>',
								'toggler'       => array(
									'fields'   => array(
										array(
											'id'    => 'bwfan_ab_enable',
											'value' => true,
										),
									),
									'relation' => 'OR',
								),
							)
						), $ab_email_consent_message_fields, array(
							array(
								'id'          => 'bwfan_ab_tag_selector',
								'label'       => __( 'Add Tag', 'wp-marketing-automations' ),
								'type'        => 'tagselector',
								'class'       => '',
								'placeholder' => '',
								'required'    => false,
								'isProField'  => true,
								'wrap_before' => '<br/><h3>Cart is Abandoned</h3>',
								'hint'        => __( 'The selected tag(s) will be added when cart is abandoned. The tag(s) will be automatically removed when cart recovers', 'wp-marketing-automations' ),
								'toggler'     => array(
									'fields'   => array(
										array(
											'id'    => 'bwfan_ab_enable',
											'value' => true,
										),
									),
									'relation' => 'AND',
								),
							),
							array(
								'id'                  => 'bwfan_ab_list_selector',
								'label'               => __( 'Add list', 'wp-marketing-automations' ),
								"type"                => 'search',
								'autocompleter'       => 'lists',
								"allowFreeTextSearch" => false,
								'required'            => false,
								'isProField'          => true,
								'wrap_before'         => '',
								'hint'                => __( 'The selected lists(s) will be added when cart is abandoned. The list(s) will be automatically removed when cart recovers', 'wp-marketing-automations' ),
								'toggler'             => array(
									'fields'   => array(
										array(
											'id'    => 'bwfan_ab_enable',
											'value' => true,
										),
									),
									'relation' => 'AND',
								),
							),
							array(
								'id'          => 'bwfan_lostcart_tag_selector',
								'label'       => __( 'Add Tag', 'wp-marketing-automations' ),
								'type'        => 'tagselector',
								'class'       => '',
								'placeholder' => '',
								'required'    => false,
								'isProField'  => true,
								'wrap_before' => '<br/><h3>Cart is Lost</h3>',
								'hint'        => __( 'The selected tag(s) will be added when cart is lost. The tag(s) will be automatically removed when cart recovers', 'wp-marketing-automations' ),
								'toggler'     => array(
									'fields'   => array(
										array(
											'id'    => 'bwfan_ab_enable',
											'value' => true,
										),
									),
									'relation' => 'AND',
								),
							),
							array(
								'id'                  => 'bwfan_lostcart_list_selector',
								'label'               => __( 'Add list', 'wp-marketing-automations' ),
								"type"                => 'search',
								'autocompleter'       => 'lists',
								"allowFreeTextSearch" => false,
								'required'            => false,
								'isProField'          => true,
								'wrap_before'         => '',
								'hint'                => __( 'The selected lists(s) will be added when cart is lost. The list(s) will be automatically removed when cart recovers', 'wp-marketing-automations' ),
								'toggler'             => array(
									'fields'   => array(
										array(
											'id'    => 'bwfan_ab_enable',
											'value' => true,
										),
									),
									'relation' => 'AND',
								),
							),
							array(
								'id'            => 'bwfan_ab_track_on_add_to_cart',
								'label'         => __( 'Track on Add to Cart', 'wp-marketing-automations' ),
								'type'          => 'checkbox',
								'checkboxlabel' => __( 'Track carts when a product is added to the cart for logged-in users', 'wp-marketing-automations' ),
								'class'         => 'bwfan_ab_track_on_add_to_cart',
								'required'      => false,
								'wrap_before'   => '<h3>User</h3>',
								'toggler'       => array(
									'fields'   => array(
										array(
											'id'    => 'bwfan_ab_enable',
											'value' => true,
										),
									),
									'relation' => 'OR',
								),
							),
							array(
								'id'            => 'bwfan_ab_exclude_users_cart_tracking',
								'label'         => __( 'Exclude User Roles', 'wp-marketing-automations' ),
								'type'          => 'checkbox',
								'checkboxlabel' => __( 'Exclude user roles from cart tracking', 'wp-marketing-automations' ),
								'class'         => 'bwfan_ab_exclude_users_cart_tracking',
								'required'      => false,
								'toggler'       => array(
									'fields'   => array(
										array(
											'id'    => 'bwfan_ab_enable',
											'value' => true,
										),
									),
									'relation' => 'OR',
								),
							),
							array(
								'id'          => 'bwfan_ab_exclude_roles',
								'type'        => 'select',
								'class'       => '',
								'options'     => $user_roles,
								'required'    => false,
								'inlineTags'  => false,
								'multiple'    => true,
								'placeholder' => 'Select',
								'hint'        => __( 'Carts for selected user roles will not be tracked', 'wp-marketing-automations' ),
								'toggler'     => array(
									'fields'   => array(
										array(
											'id'    => 'bwfan_ab_enable',
											'value' => true,
										),
										array(
											'id'    => 'bwfan_ab_exclude_users_cart_tracking',
											'value' => true,
										),
									),
									'relation' => 'AND',
								),
							),
							array(
								'id'          => 'bwfan_ab_exclude_emails',
								'label'       => __( 'Emails', 'wp-marketing-automations' ),
								'type'        => 'textarea',
								'class'       => '',
								'required'    => false,
								'wrap_before' => '<br/><h3>Blacklist Emails</h3>',
								'hint'        => __( 'Enter emails, domains or partials to exclude from cart  abandonment tracking separated by comma(,) or in new line
                                            <br>You can add full emails (i.e. foo@example.com) or domains (i.e. domain.com), or partials (i.e. john)', 'wp-marketing-automations' ),
								'toggler'     => array(
									'fields'   => array(
										array(
											'id'    => 'bwfan_ab_enable',
											'value' => true,
										),
									),
									'relation' => 'AND',
								),
							),
							array(
								'id'          => 'bwfan_ab_restore_cart_message_success',
								'label'       => __( 'Cart Success Notice', 'wp-marketing-automations' ),
								'type'        => 'text',
								'class'       => '',
								'placeholder' => '',
								'wrap_before' => '<h3>Checkout Notice</h3>',
								'required'    => false,
								'hint'        => __( "Notice when cart is successfully restored. Leave blank in case you don't want to show a notice.", 'wp-marketing-automations' ),
								'toggler'     => array(
									'fields'   => array(
										array(
											'id'    => 'bwfan_ab_enable',
											'value' => true,
										),
									),
									'relation' => 'OR',
								),
							),
							array(
								'id'          => 'bwfan_ab_restore_cart_message_failure',
								'label'       => __( 'Cart Failure Notice', 'wp-marketing-automations' ),
								'type'        => 'text',
								'class'       => '',
								'placeholder' => '',
								'required'    => false,
								'hint'        => __( "Notice when cart fails to restore. Leave blank in case you don't want to show a notice.", 'wp-marketing-automations' ),
								'toggler'     => array(
									'fields'   => array(
										array(
											'id'    => 'bwfan_ab_enable',
											'value' => true,
										),
									),
									'relation' => 'OR',
								),
							)
						) ),
					),
					/*
					array(
						"key"     => 'conversion',
						"label"   => 'Conversions', // label is used for left side tab item
						"heading" => 'Conversions',
						"fields"  => [
							array(
								"id"          => 'bwfan_order_tracking_conversion',
								"label"       => __( 'Order Tracking Conversion', 'wp-marketing-automations' ),
								"type"        => 'number',
								"class"       => 'bwfan_order_tracking_conversion',
								"placeholder" => "Days to track order",
								"hint"        => __( "Days to Track order details for conversion.", 'wp-marketing-automations' ),
								"required"    => false,
								"toggler"     => array(),
							),
						],
					),*/
					array(
						'key'          => 'optin',
						'label'        => __( 'Checkout Consent', 'wp-marketing-automations' ),
						'heading'      => __( 'Checkout Consent', 'wp-marketing-automations' ),
						'isWooSection' => true,
						'fields'       => array_merge( array(
							array(
								'id'            => 'bwfan_user_consent',
								'label'         => __( 'Enable Marketing Consent', 'wp-marketing-automations' ),
								'type'          => 'checkbox',
								'checkboxlabel' => __( 'Enable an optin on checkout to ask for the consent of marketing emails.', 'wp-marketing-automations' ),
								'hint'          => __( 'Note: For logged in users, this field would not be visible to Contacts if they are subscribed', 'wp-marketing-automations' ),
								'class'         => 'bwfan_user_consent',
								'required'      => false,
								'wrap_before'   => '',
								'toggler'       => array(),
							)
						), $checkout_consent_fields, array(
							array(
								'id'       => 'bwfan_user_consent_position',
								'label'    => __( 'Consent Field Position', 'wp-marketing-automations' ),
								'type'     => 'select',
								'multiple' => false,
								'class'    => 'bwfan_user_consent_position',
								'options'  => array(
									array(
										'value' => 'below_term',
										'label' => __( 'Below Terms & Condition', 'wp-marketing-automations' ),
									),
									array(
										'value' => 'below_email',
										'label' => __( 'Below Email Field', 'wp-marketing-automations' ),
									),
									array(
										'value' => 'below_phone',
										'label' => __( 'Below Phone Field', 'wp-marketing-automations' ),
									),
								),
								'required' => false,
								'toggler'  => array(
									'fields'   => array(
										array(
											'id'    => 'bwfan_user_consent',
											'value' => true,
										),
									),
									'relation' => 'OR',
								),
							),
							array(
								'id'          => 'bwfan_user_consent_eu',
								'label'       => __( 'EU Contacts', 'wp-marketing-automations' ),
								'type'        => 'select',
								'multiple'    => false,
								'class'       => 'bwfan_user_consent_eu',
								'options'     => array(
									array(
										'value' => '1',
										'label' => __( 'Checked', 'wp-marketing-automations' ),
									),
									array(
										'value' => '0',
										'label' => __( 'Unchecked', 'wp-marketing-automations' ),
									),
								),
								'required'    => false,
								'hint'        => __( 'EU contacts are determined by their IP address. To respect GDPR, keep it unchecked.', 'wp-marketing-automations' ),
								'wrap_before' => '<br/><h3>Consent Checked Behaviour</h3>',
								'toggler'     => array(
									'fields'   => array(
										array(
											'id'    => 'bwfan_user_consent',
											'value' => true,
										),
									),
									'relation' => 'OR',
								),
							),
							array(
								'id'       => 'bwfan_user_consent_non_eu',
								'label'    => __( 'Non-EU Contacts', 'wp-marketing-automations' ),
								'type'     => 'select',
								'multiple' => false,
								'class'    => 'bwfan_user_consent_non_eu',
								'options'  => array(
									array(
										'value' => '1',
										'label' => __( 'Checked', 'wp-marketing-automations' ),
									),
									array(
										'value' => '0',
										'label' => __( 'Unchecked', 'wp-marketing-automations' ),
									),
								),
								'required' => false,
								'toggler'  => array(
									'fields'   => array(
										array(
											'id'    => 'bwfan_user_consent',
											'value' => true,
										),
									),
									'relation' => 'OR',
								),
							)
						) ),
					),
					array(
						'key'     => 'preference',
						'label'   => 'Preference Pages', // label is used for left side tab item
						'heading' => 'Preference Settings',
						'fields'  => array_merge( array(
							array(
								'id'       => 'bwfan_unsubscribe_page',
								'label'    => __( 'Page', 'wp-marketing-automations' ),
								'type'     => 'ajax',
								'multiple' => false,
								'class'    => 'bwfan-unsubscribe-page',
								'options'  => array(),
								'required' => false,
								'hint'     => self::get_unsubscribe_page_hint_text(),
								'ajax_cb'  => 'bwfan_select_unsubscribe_page',
								'toggler'  => array(),
							),
						), self::get_lists_preference_schema(), array(
							array(
								'id'          => 'bwfan_unsubscribe_from_all_label',
								'label'       => __( 'Unsubscribe All Lists Label', 'wp-marketing-automations' ),
								'type'        => 'text',
								'class'       => 'bwfan_unsubscribe_from_all_label',
								'placeholder' => '',
								'required'    => false,
								'hint'        => __( 'Label for Unsubscribe from all list option', 'wp-marketing-automations' ),
							),
							array(
								'id'          => 'bwfan_unsubscribe_from_all_description',
								'label'       => __( 'Unsubscribe All Lists Description', 'wp-marketing-automations' ),
								'type'        => 'text',
								'class'       => 'bwfan_unsubscribe_from_all_description',
								'placeholder' => '',
								'required'    => false,
								'hint'        => __( 'Description for Unsubscribe from all list option', 'wp-marketing-automations' ),
							),
							array(
								'id'          => 'bwfan_unsubscribe_data_success',
								'label'       => __( 'Text', 'wp-marketing-automations' ),
								'type'        => 'text',
								'class'       => 'bwfan_unsubscribe_data_success',
								'placeholder' => '',
								'required'    => false,
								'wrap_before' => '<br/><h3>Confirmation Message</h3>',
								'hint'        => __( 'Confirmation message when lists subscription is updated', 'wp-marketing-automations' ),
								'toggler'     => array(),
							),
						) ),
					),
					array(
						'key'     => 'double-optin',
						'label'   => 'Double Opt-in', // label is used for left side tab item
						'heading' => 'Double Opt-in',
						'fields'  => array(
							array(
								'id'          => 'after_confirmation_type',
								'label'       => __( 'After Confirmation Type', 'wp-marketing-automations' ),
								'type'        => 'radio',
								'options'     => [
									[
										'label'   => __( "Show Message", 'wp-marketing-automations' ),
										'value'   => 'show_message',
										'tooltip' => __( "", 'wp-marketing-automations' )
									],
									[
										'label'   => __( "Redirect to an URL", 'wp-marketing-automations' ),
										'value'   => 'redirect',
										'tooltip' => __( "", 'wp-marketing-automations' )
									]
								],
								'hint'        => __( "Please Select what will happen after contact clicked on subscribe link", 'wp-marketing-automations' ),
								'class'       => 'bwfan_confirmation_type',
								'required'    => false,
								'wrap_before' => '',
							),
							array(
								'id'          => 'bwfan_confirmation_message',
								'label'       => __( 'After Confirmation Message', 'wp-marketing-automations' ),
								'type'        => 'richeditor',
								'class'       => 'bwfan_confirmation_message',
								'required'    => false,
								'wrap_before' => '<h3></h3>',
								'hint'        => __( '', 'wp-marketing-automations' ),
								'toggler'     => array(
									'fields'   => array(
										array(
											'id'    => 'after_confirmation_type',
											'value' => 'show_message',
										)
									),
									'relation' => 'OR',
								),
							),
							array(
								'id'          => 'bwfan_confirmation_redirect_url',
								'label'       => __( 'Redirect URL', 'wp-marketing-automations' ),
								'type'        => 'text',
								'class'       => 'bwfan_confirmation_redirect_url',
								'placeholder' => 'Redirect URL',
								'required'    => false,
								'hint'        => __( 'Please provide redirect URL after contact confirmation', 'wp-marketing-automations' ),
								'toggler'     => array(
									'fields'   => array(
										array(
											'id'    => 'after_confirmation_type',
											'value' => 'redirect',
										)
									),
									'relation' => 'OR',
								),
							),
						),
					),
					array(
						'key'     => 'advanced',
						'label'   => 'Advanced', // label is used for left side tab item
						'heading' => 'Advanced',
						'fields'  => array(
							array(
								'id'            => 'bwfan_sandbox_mode',
								'label'         => __( 'Sandbox Mode', 'wp-marketing-automations' ),
								'type'          => 'checkbox',
								'checkboxlabel' => __( 'Enable sandbox mode', 'wp-marketing-automations' ),
								'hint'          => __( "When sandbox mode is enabled, Automations won't create new tasks, and existing tasks won't run", 'wp-marketing-automations' ),
								'class'         => 'bwfan_sandbox_mode',
								'required'      => false,
								'wrap_before'   => '',
								'toggler'       => array(),
							),
							array(
								'id'            => 'bwfan_make_logs',
								'label'         => __( 'Logging', 'wp-marketing-automations' ),
								'type'          => 'checkbox',
								'checkboxlabel' => __( 'Enable logs creation for debugging', 'wp-marketing-automations' ),
								'hint'          => __( 'When enabled, logs will be saved to Funnelkit Automation > Settings > Logs. Disable this settings after debugging is finished.', 'wp-marketing-automations' ),
								'class'         => 'bwfan_make_logs',
								'required'      => false,
								'wrap_before'   => '',
								'toggler'       => array(),
							),
							array(
								'id'          => 'bwfan_delete_autonami_generated_coupons_time',
								'label'       => __( 'Delete Expired Coupons', 'wp-marketing-automations' ),
								'type'        => 'number',
								'class'       => 'bwfan_delete_autonami_generated_coupons_time',
								'placeholder' => '1',
								"isWooField"  => true,
								'wrap_before' => '<br/><h3>WooCommerce Coupons</h3>',
								'hint'        => __( 'Delete personalized coupons after expiry', 'wp-marketing-automations' ),
								'required'    => false,
								'toggler'     => array(),
							),
						),
					),
				),
			),
		);

		return apply_filters( 'bwfan_admin_settings_schema', $settings );
	}

	public static function get_bounce_settings_schema() {
		if ( ! bwfan_is_autonami_pro_active() ) {
			return array(
				array(
					'id'            => 'bwfan_enable_bounce_handling',
					'label'         => __( 'Bounce Tracking', 'wp-marketing-automations' ),
					'type'          => 'checkbox',
					'checkboxlabel' => __( 'Enable to capture bounced emails from the email service and mark Contact as Bounced', 'wp-marketing-automations' ),
					'class'         => 'bwfan_user_consent',
					'required'      => false,
					'wrap_before'   => '<h3>Email Service Provider</h3>',
					'disabled'      => true,
					'isProSetting'  => true,
				),
			);
		}
		$bounce_settings        = bwfan_is_autonami_pro_active() ? BWFCRM_Core()->email_webhooks->get_webhooks() : array();
		$bounce_options         = array(
			array(
				'label' => 'Select Email Service',
				'value' => '',
			),
		);
		$bounce_settings_schema = ! empty( $bounce_settings ) ? array_map( function ( $webhook, $slug ) use ( &$bounce_options ) {
			if ( empty( $slug ) || empty( $webhook ) || ! is_array( $webhook ) ) {
				return false;
			}
			$bounce_options[] = array(
				'label' => $webhook['name'],
				'value' => strtolower( $webhook['name'] ),
			);

			return array(
				'id'        => 'bwfan_email_webhook_' . $slug,
				'type'      => 'copier',
				'class'     => 'bwfan_email_webhook',
				'hint'      => __( "Paste this URL into your {$webhook['name']}'s Webhook settings to enable Bounce Handling", 'wp-marketing-automations' ),
				'required'  => false,
				'copy_text' => $webhook['link'],
				'toggler'   => array(
					'fields'   => array(
						array(
							'id'    => 'bwfan_bounce_select',
							'value' => strtolower( $webhook['name'] ),
						),
						array(
							'id'    => 'bwfan_enable_bounce_handling',
							'value' => true,
						),
					),
					'relation' => 'AND',
				),
			);
		}, $bounce_settings, array_keys( $bounce_settings ) ) : array();

		$bounce_settings_schema = array_merge( array(
			array(
				'id'            => 'bwfan_enable_bounce_handling',
				'label'         => __( 'Bounce Tracking', 'wp-marketing-automations' ),
				'type'          => 'checkbox',
				'checkboxlabel' => __( 'Enable to capture bounced emails from the email service and mark Contact as Bounced', 'wp-marketing-automations' ),
				'class'         => 'bwfan_user_consent',
				'required'      => false,
				'wrap_before'   => '<h3>Email Service Provider</h3>',
			),
			array(
				'id'       => 'bwfan_bounce_select',
				'type'     => 'select',
				'class'    => '',
				'options'  => $bounce_options,
				'required' => false,
				'multiple' => false,
				'toggler'  => array(
					'fields'   => array(
						array(
							'id'    => 'bwfan_enable_bounce_handling',
							'value' => true,
						),
					),
					'relation' => 'AND',
				),
			),
		), $bounce_settings_schema );

		return is_array( $bounce_settings_schema ) ? array_filter( $bounce_settings_schema ) : array();
	}

	public static function get_pro_license( $onlyKey = true ) {
		$bwf_licenses = get_option( 'woofunnels_plugins_info', false );
		if ( ! defined( 'BWFAN_PRO_ENCODE' ) || empty( $bwf_licenses ) || ! is_array( $bwf_licenses ) ) {
			return false;
		}
		if ( isset( $bwf_licenses[ BWFAN_PRO_ENCODE ] ) && isset( $bwf_licenses[ BWFAN_PRO_ENCODE ]['activated'] ) && $bwf_licenses[ BWFAN_PRO_ENCODE ]['activated'] ) {
			if ( $onlyKey ) {
				return $bwf_licenses[ BWFAN_PRO_ENCODE ]['data_extra']['api_key'];
			} else {
				$bwf_licenses[ BWFAN_PRO_ENCODE ]['data_extra']['manually_deactivated'] = isset( $bwf_licenses[ BWFAN_PRO_ENCODE ]['manually_deactivated'] ) ? 1 : 0;
				if ( isset( $bwf_licenses[ BWFAN_PRO_ENCODE ]['data_extra']['api_key'] ) ) {
					$bwf_licenses[ BWFAN_PRO_ENCODE ]['data_extra']['api_key'] = 'xxxxxxxxxxxxxxxxxxxxxxxxxx' . substr( $bwf_licenses[ BWFAN_PRO_ENCODE ]['data_extra']['api_key'], - 6 );
				}

				return $bwf_licenses[ BWFAN_PRO_ENCODE ]['data_extra'];
			}
		}

		return false;
	}

	/**
	 * @return array
	 */
	public static function get_lk_data() {
		$arr = [ 's' => 0, 'e' => '' ];
		if ( ! bwfan_is_autonami_pro_active() ) {
			return $arr;
		}
		$arr['s'] = 1;
		$l_data   = get_option( 'woofunnels_plugins_info', false );
		if ( ! defined( 'BWFAN_PRO_ENCODE' ) || empty( $l_data ) || ! is_array( $l_data ) || ! isset( $l_data[ BWFAN_PRO_ENCODE ] ) || ! isset( $l_data[ BWFAN_PRO_ENCODE ]['activated'] ) || 1 !== intval( $l_data[ BWFAN_PRO_ENCODE ]['activated'] ) ) {
			$arr['ad'] = bwf_options_get( 'fka_psd' );

			return $arr;
		}
		$arr['s'] = 2;
		$arr['e'] = $l_data[ BWFAN_PRO_ENCODE ]['data_extra']['expires'];

		return $arr;
	}

	public static function get_connector_license( $onlyKey = true ) {
		$bwf_licenses = get_option( 'woofunnels_plugins_info', false );
		if ( empty( $bwf_licenses ) || ! is_array( $bwf_licenses ) ) {
			return false;
		}

		if ( isset( $bwf_licenses[ WFCO_AUTONAMI_CONNECTORS_ENCODE ] ) && isset( $bwf_licenses[ WFCO_AUTONAMI_CONNECTORS_ENCODE ]['activated'] ) && $bwf_licenses[ WFCO_AUTONAMI_CONNECTORS_ENCODE ]['activated'] ) {
			if ( $onlyKey ) {
				return $bwf_licenses[ WFCO_AUTONAMI_CONNECTORS_ENCODE ]['data_extra']['api_key'];
			} else {
				$bwf_licenses[ WFCO_AUTONAMI_CONNECTORS_ENCODE ]['data_extra']['manually_deactivated'] = isset( $bwf_licenses[ WFCO_AUTONAMI_CONNECTORS_ENCODE ]['manually_deactivated'] ) ? 1 : 0;
				if ( isset( $bwf_licenses[ WFCO_AUTONAMI_CONNECTORS_ENCODE ]['data_extra']['api_key'] ) ) {
					$bwf_licenses[ WFCO_AUTONAMI_CONNECTORS_ENCODE ]['data_extra']['api_key'] = 'xxxxxxxxxxxxxxxxxxxxxxxxxx' . substr( $bwf_licenses[ WFCO_AUTONAMI_CONNECTORS_ENCODE ]['data_extra']['api_key'], - 6 );
				}

				return $bwf_licenses[ WFCO_AUTONAMI_CONNECTORS_ENCODE ]['data_extra'];
			}
		}

		return false;
	}

	/**
	 * Get settings fields for whatsapp services
	 *
	 * @return bool
	 */
	public static function get_whatsapp_services_fields() {
		$fields = array();
		if ( bwfan_is_autonami_pro_active() && class_exists( 'WFCO_Autonami_Connectors_Core' ) ) {
			$services = BWFCRM_Core()->conversation->get_whatsapp_services();
			if ( count( $services ) > 0 ) {
				$fields = array(
					array(
						"id"           => 'bwfan_whatsapp_gap_btw_message',
						"label"        => __( 'Time Between Each Message (secs)', 'wp-marketing-automations' ),
						"type"         => 'number',
						"class"        => 'bwfan_whatsapp_gap_btw_message',
						"placeholder"  => '1',
						"hint"         => __( "The time gap between messages in seconds", 'wp-marketing-automations' ),
						"required"     => false,
						"autocomplete" => 'off',
						"toggler"      => array(),
					)
				);

				if ( count( $services ) > 1 ) {
					$fields[] = array(
						"id"       => 'bwfan_primary_whats_app_service',
						"label"    => __( 'Select Service', 'wp-marketing-automations' ),
						"type"     => 'select',
						"class"    => '',
						"options"  => $services,
						"required" => false,
						'multiple' => false,
						"toggler"  => array(),
					);
				}
			} else {
				$fields = array(
					array(
						'id'       => 'whatsapp_notice',
						'type'     => 'notice',
						'class'    => '',
						'status'   => 'error',
						'message'  => 'WhatsApp service is not configured yet.',
						'dismiss'  => false,
						'required' => false,
						'toggler'  => array(),
					),
					array(
						'id'           => 'redirect_button',
						'label'        => __( 'Click to configure Whatsapp Connector', 'wp-marketing-automations-connectors' ),
						'type'         => 'redirect_button',
						'redirect_url' => ( 'admin.php?page=autonami&path=/connectors' ),
						'class'        => '',
						'newtab'       => '_self',
						'btntype'      => 'secondary',
						'required'     => false,
						'toggler'      => array(),
					)
				);
			}
		}

		return $fields;
	}

	/**
	 * Returns unsubscribe page hint text
	 *
	 * @return string
	 */
	public static function get_unsubscribe_page_hint_text() {
		$page_url = '';
		$html     = '';
		$setting  = self::get_global_settings();
		if ( isset( $setting['bwfan_unsubscribe_page'] ) && ! empty( $setting['bwfan_unsubscribe_page'] ) ) {
			$page_url = get_edit_post_link( $setting['bwfan_unsubscribe_page'] );
		}
		if ( $page_url ) {
			$html = '<a href="' . $page_url . '" target="_blank">' . __( 'Click here', 'wp-marketing-automations' ) . '</a> ' . __( 'to edit the page.', 'wp-marketing-automations' ) . '<br /> <br />';
		}

		$html .= __( 'Use shortcodes <b>[wfan_contact_name]</b> for contact\'s name', 'wp-marketing-automations' ) . ', ' . __( '<b>[wfan_contact_firstname]</b> for contact\'s first name', 'wp-marketing-automations' ) . ', ' . __( '<b>[wfan_contact_lastname]</b> for contact\'s last name', 'wp-marketing-automations' ) . ', ' . __( '<b>[wfan_contact_email]</b> for contact\'s email', 'wp-marketing-automations' ) . ' and ' . __( '<b>[wfan_unsubscribe_button label=\'Update my preference\']</b> for the unsubscribe button.', 'wp-marketing-automations' );

		return $html;
	}

	public static function get_lists_preference_schema() {
		if ( ! bwfan_is_autonami_pro_active() ) {
			return array();
		}

		return array(

			array(
				'id'            => 'bwfan_unsubscribe_lists_enable',
				'label'         => __( 'Manage Lists', 'wp-marketing-automations' ),
				'type'          => 'checkbox',
				'checkboxlabel' => __( 'Allow contacts to manage their lists', 'wp-marketing-automations' ),
				'class'         => 'bwfan_unsubscribe_lists_enable',
				'wrap_before'   => '<br/><h3>List Management</h3>',
				'required'      => false,
			),
			array(
				'id'       => 'bwfan_unsubscribe_public_lists',
				'label'    => __( 'Select Lists', 'wp-marketing-automations' ),
				'type'     => 'checkbox_grid',
				'hint'     => __( 'The selected lists will be shown to contacts for managing their preferences', 'wp-marketing-automations' ),
				'class'    => 'bwfan_unsubscribe_public_lists',
				'options'  => self::get_lists_for_preferences(),
				'required' => false,
				'toggler'  => array(
					'fields' => array(
						array(
							'id'    => 'bwfan_unsubscribe_lists_enable',
							'value' => true,
						),
					),
				),
			),
			array(
				'id'            => 'bwfan_unsubscribe_lists_visibility',
				'label'         => __( 'Filter List', 'wp-marketing-automations' ),
				'type'          => 'checkbox',
				'checkboxlabel' => __( 'Show contact their subscribed lists', 'wp-marketing-automations' ),
				'hint'          => __( 'If unchecked all the selected lists will be availabe to contacts', 'wp-marketing-automations' ),
				'class'         => 'bwfan_unsubscribe_lists_visibility',
				'required'      => false,
				'toggler'       => array(
					'fields' => array(
						array(
							'id'    => 'bwfan_unsubscribe_lists_enable',
							'value' => true,
						),
					),
				),
			),
		);
	}

	public static function get_lists_for_preferences() {
		if ( ! bwfan_is_autonami_pro_active() ) {
			return array();
		}

		$lists = BWFAN_Model_Terms::get_all( BWFCRM_Term_Type::$LIST );
		if ( empty( $lists ) ) {
			return array();
		}

		$lists_array = array();
		foreach ( $lists as $list ) {
			if ( empty( $list['name'] ) ) {
				continue;
			}
			$lists_array[ $list['ID'] ] = $list['name'];
		}

		return $lists_array;
	}

	/**
	 * checking if all table created or not
	 */
	public static function checking_all_tables_exists() {
		global $wpdb;
		$result                  = true;
		$not_created_tables      = array();
		$mytables                = $wpdb->get_results( 'SHOW TABLES', ARRAY_A );
		$tables_array            = empty( $mytables ) ? [] : array_column( $mytables, 'Tables_in_' . $wpdb->dbname );
		$automations_table_array = self::get_tables_array();

		foreach ( $automations_table_array as $table ) {
			if ( ! in_array( $table, $tables_array, true ) ) {
				$not_created_tables[] = $table;
			}
		}

		if ( ! empty( $not_created_tables ) ) {
			return $not_created_tables;
		}

		return $result;
	}

	/** return all autonami tables
	 *
	 * @return mixed|void
	 */
	public static function get_tables_array() {
		global $wpdb;
		$automations_table_array = apply_filters( 'bwfan_automation_tables', array(
			$wpdb->prefix . 'bwfan_automations',
			$wpdb->prefix . 'bwfan_automationmeta',
			$wpdb->prefix . 'bwfan_tasks',
			$wpdb->prefix . 'bwfan_taskmeta',
			$wpdb->prefix . 'bwfan_task_claim',
			$wpdb->prefix . 'bwfan_logs',
			$wpdb->prefix . 'bwfan_logmeta',
			$wpdb->prefix . 'bwfan_message_unsubscribe',
			$wpdb->prefix . 'bwfan_contact_automations',
			$wpdb->prefix . 'bwfan_abandonedcarts',
		) );

		sort( $automations_table_array );

		return $automations_table_array;
	}

	/**
	 * Get Format for Success Response
	 *
	 * @param $result_array
	 * @param string $message
	 * @param int $response_code
	 *
	 * @return array
	 */
	public static function format_success_response( $result_array, $message = '', $response_code = 200 ) {
		return array(
			'code'    => $response_code,
			'message' => $message,
			'result'  => $result_array,
		);
	}

	/** maybe create abandoned cart if enable
	 *
	 * @param $active_abandoned_cart
	 */
	public static function maybe_create_abandoned_contact( $active_abandoned_cart ) {
		$global_settings = self::get_global_settings();
		$abandoned_tag   = isset( $global_settings['bwfan_ab_tag_selector'] ) ? json_decode( $global_settings['bwfan_ab_tag_selector'], true ) : [];
		$abandoned_list  = isset( $global_settings['bwfan_ab_list_selector'] ) && is_array( $global_settings['bwfan_ab_list_selector'] ) ? $global_settings['bwfan_ab_list_selector'] : [];

		$abandoned_tag = array_map( function ( $tag ) {
			$tag['value'] = ! isset( $tag['value'] ) && isset( $tag['name'] ) ? $tag['name'] : '';
			unset( $tag['name'] );

			return $tag;
		}, $abandoned_tag );

		$abandoned_list = array_map( function ( $list ) {
			$list['value'] = ! isset( $list['value'] ) && isset( $list['name'] ) ? $list['name'] : '';
			unset( $list['name'] );

			return $list;
		}, $abandoned_list );

		if ( ! isset( $active_abandoned_cart['ID'] ) || empty( $active_abandoned_cart['ID'] ) ) {
			return;
		}

		$abandoned_id   = $active_abandoned_cart['ID'];
		$abandoned_data = BWFAN_Model_Abandonedcarts::get( $abandoned_id );
		if ( ! is_array( $abandoned_data ) ) {
			return;
		}

		$abandoned_user_id = $active_abandoned_cart['user_id'];
		$abandoned_email   = $abandoned_data['email'];
		if ( empty( $abandoned_email ) ) {
			return;
		}

		if ( empty( $abandoned_user_id ) ) {
			$abandoned_user_id = 0;
		}

		$contact = bwf_get_contact( $abandoned_user_id, $abandoned_email );
		if ( $contact instanceof WooFunnels_Contact && $contact->get_id() > 0 ) {
			if ( ! bwfan_is_autonami_pro_active() || ! class_exists( 'BWFCRM_Contact' ) ) {
				return;
			}
			$bwfcrm_contact = new BWFCRM_Contact( $contact ); // getting bwfcrm_contact object to add tags
			if ( ! empty( $abandoned_tag ) ) {
				$bwfcrm_contact->add_tags( $abandoned_tag );
			}

			if ( ! empty( $abandoned_list ) ) {
				$bwfcrm_contact->add_lists( $abandoned_list );
			}

			return;
		}

		if ( $abandoned_user_id > 0 ) {
			$wp_user         = get_user_by( 'id', $abandoned_user_id );
			$abandoned_email = $wp_user->user_email;
		}

		$abandoned_checkout_data = json_decode( $abandoned_data['checkout_data'], true );

		$f_name      = is_array( $abandoned_checkout_data ) && isset( $abandoned_checkout_data['fields'] ) && isset( $abandoned_checkout_data['fields']['billing_first_name'] ) ? $abandoned_checkout_data['fields']['billing_first_name'] : '';
		$l_name      = is_array( $abandoned_checkout_data ) && isset( $abandoned_checkout_data['fields'] ) && isset( $abandoned_checkout_data['fields']['billing_last_name'] ) ? $abandoned_checkout_data['fields']['billing_last_name'] : '';
		$contact_no  = is_array( $abandoned_checkout_data ) && isset( $abandoned_checkout_data['fields'] ) && isset( $abandoned_checkout_data['fields']['billing_phone'] ) ? $abandoned_checkout_data['fields']['billing_phone'] : '';
		$bwf_contact = new WooFunnels_Contact( $abandoned_user_id, $abandoned_email );

		$bwf_contact->set_email( $abandoned_email );
		$bwf_contact->set_f_name( $f_name );
		$bwf_contact->set_l_name( $l_name );
		$bwf_contact->set_contact_no( $contact_no );
		$bwf_contact->save();
		if ( bwfan_is_autonami_pro_active() && class_exists( 'BWFCRM_Contact' ) ) {
			$bwfcrm_contact = new BWFCRM_Contact( $bwf_contact );  // getting bwfcrm_contact object to add tags
			/** add tag in case of available in cart settings */
			if ( ! empty( $abandoned_tag ) ) {
				$bwfcrm_contact->set_tags( $abandoned_tag );
			}
			if ( ! empty( $abandoned_list ) ) {
				$bwfcrm_contact->set_lists( $abandoned_list );
			}
			$bwfcrm_contact->save();
		}
	}

	/**
	 * remove abandoned cart tags on cart recovered
	 *
	 * @param $order
	 */
	public static function bwfan_remove_abandoned_cart_tags( $order ) {
		if ( ! $order instanceof WC_Order ) {
			return;
		}

		/** If pro not found */
		if ( ! bwfan_is_autonami_pro_active() || ! class_exists( 'BWFCRM_Contact' ) ) {
			return;
		}

		$global_settings        = self::get_global_settings();
		$removed_abandoned_tags = isset( $global_settings['bwfan_ab_tag_selector'] ) ? json_decode( $global_settings['bwfan_ab_tag_selector'], true ) : [];
		$removed_abandoned_list = isset( $global_settings['bwfan_ab_list_selector'] ) ? $global_settings['bwfan_ab_list_selector'] : [];

		if ( empty( $removed_abandoned_tags ) && empty( $removed_abandoned_list ) ) {
			return;
		}

		$remove_tag_data = $remove_list_data = array();

		if ( ! empty( $removed_abandoned_tags ) ) {
			foreach ( $removed_abandoned_tags as $remove_tag ) {
				$tag_id = $remove_tag['id'];
				if ( 0 === absint( $tag_id ) && bwfan_is_autonami_pro_active() ) {
					$tag_id = empty( $remove_tag['id'] ) ? BWFCRM_Term::get_terms( 1, [], $remove_tag['name'], 0, 0, ARRAY_A, 'exact' ) : $remove_tag['id'];
				}
				$remove_tag_data[] = is_array( $tag_id ) && isset( $tag_id[0]['ID'] ) ? $tag_id[0]['ID'] : $tag_id;
			}
		}

		if ( ! empty( $removed_abandoned_list ) ) {
			foreach ( $removed_abandoned_list as $remove_tag ) {
				$remove_list_data[] = $remove_tag['id'];
			}
		}

		$cid = $order->get_meta( '_woofunnel_cid' );
		if ( empty( $cid ) ) {
			return;
		}

		$bwfcrm_contact = new BWFCRM_Contact( $cid );
		if ( ! $bwfcrm_contact->is_contact_exists() ) {
			return;
		}

		if ( ! empty( $remove_tag_data ) ) {
			$bwfcrm_contact->remove_tags( $remove_tag_data );
		}

		if ( ! empty( $remove_list_data ) ) {
			$bwfcrm_contact->remove_lists( $remove_list_data );
		}

		$bwfcrm_contact->save();
	}

	public static function get_carts_count() {
		$recoverable_count = BWFAN_Recoverable_Carts::get_abandoned_carts( '', '', '', '', '', true );
		$recovered_count   = BWFAN_Recoverable_Carts::get_recovered_carts( '', '', '', true );
		$lost_carts        = BWFAN_Recoverable_Carts::get_abandoned_carts( '', '', '', '', 2, true );

		return [
			'carts_recoverable' => absint( $recoverable_count['total_count'] ) > 0 ? absint( $recoverable_count['total_count'] ) : 0,
			'carts_recovered'   => absint( $recovered_count['total_count'] ) > 0 ? absint( $recovered_count['total_count'] ) : 0,
			'carts_lost'        => absint( $lost_carts['total_count'] ) > 0 ? absint( $lost_carts['total_count'] ) : 0,
		];
	}

	public static function get_automation_data_count( $version = 1 ) {
		$automation_count = self::get_all_automations( '', 'all', 0, 0, true, $version );
		$active           = self::get_all_automations( '', '1', 0, 0, true, $version );
		$inactive         = absint( $automation_count['total_records'] ) - absint( $active['total_records'] );

		if ( 2 === intval( $version ) ) {
			return [
				'automations'  => absint( $automation_count['total_records'] ),
				'status_count' => [
					'active'   => absint( $active['total_records'] ),
					'inactive' => $inactive,
				]
			];
		}

		$scheduled_count    = BWFAN_Core()->tasks->fetch_tasks_count( 0, 0 );
		$paused_count       = BWFAN_Core()->tasks->fetch_tasks_count( 0, 1 );
		$completed_count    = BWFAN_Core()->logs->fetch_logs_count( 1 );
		$failed_count       = BWFAN_Core()->logs->fetch_logs_count( 0 );
		$task_history_count = $scheduled_count + $paused_count + $completed_count + $failed_count;

		return [
			'automations'  => absint( $automation_count['total_records'] ),
			'status_count' => [
				'active'   => absint( $active['total_records'] ),
				'inactive' => $inactive,
			],
			'task_history' => $task_history_count
		];
	}

	/** get all automations data for the api
	 *
	 * @param string $status
	 * @param int $offset
	 * @param int $limit
	 *
	 * @return array
	 */
	public static function get_all_automations( $search, $status = 'all', $offset = 0, $limit = 25, $only_count = false, $version = 0 ) {
		global $wpdb;
		$automations_table = $wpdb->prefix . 'bwfan_automations';
		$base_query        = array();
		$count_query       = array();
		$base_query[]      = "SELECT  distinct a.* FROM $automations_table as a LEFT JOIN {$wpdb->prefix}bwfan_automationmeta as am ON a.ID = am.bwfan_automation_id where 1=1 ";
		$count_query[]     = "SELECT count(DISTINCT a.ID) FROM $automations_table as a LEFT JOIN {$wpdb->prefix}bwfan_automationmeta as am ON a.ID = am.bwfan_automation_id where 1=1 ";
		if ( ! empty( $search ) && $only_count === false ) {
			$search = "%$search%";
			if ( 2 === absint( $version ) ) {
				$search_query = $wpdb->prepare( " AND a.title LIKE %s", $search );
			} else {
				$search_query = $wpdb->prepare( " AND am.meta_key='title' AND am.meta_value like %s", $search );
			}
			$base_query[]  = $search_query;
			$count_query[] = $search_query;
		}

		if ( intval( $version ) !== 0 ) {
			$version_query = $wpdb->prepare( ' AND a.v = %d ', $version );
			$base_query[]  = $version_query;
			$count_query[] = $version_query;
		}

		if ( $status !== 'all' ) {
			$status_query  = $wpdb->prepare( ' AND a.status = %d', $status );
			$base_query[]  = $status_query;
			$count_query[] = $status_query;
		}

		$base_query[] = $wpdb->prepare( ' ORDER BY a.ID DESC LIMIT %d OFFSET %d', $limit, $offset );
		if ( $only_count === false ) {
			$all_automations = self::get_db_cache_data( implode( ' ', $base_query ) );
			if ( false === $all_automations ) {
				$all_automations = $wpdb->get_results( implode( ' ', $base_query ), ARRAY_A );
				self::set_db_cache_data( implode( ' ', $base_query ), $all_automations );
			}
		}

		$overall_total = self::get_db_cache_data( implode( ' ', $count_query ) );
		if ( false === $overall_total ) {
			$overall_total = $wpdb->get_var( implode( ' ', $count_query ) );
			self::set_db_cache_data( implode( ' ', $count_query ), $overall_total );
		}

		if ( $only_count === true ) {
			return array(
				'automations'   => array(),
				'total_records' => $overall_total,
			);
		}

		if ( empty( $all_automations ) ) {
			return array(
				'automations'   => array(),
				'total_records' => 0,
			);
		}

		$final_automation_data = array();
		$date_format           = self::get_date_format();

		$automation_ids = array_map( function ( $all_automation ) {
			return isset( $all_automation['ID'] ) ? absint( $all_automation['ID'] ) : false;
		}, $all_automations );
		$ids            = implode( ',', array_filter( $automation_ids ) );

		/** Get all automation revenue total if pro active */
		$conversion_data = array();
		if ( bwfan_is_autonami_pro_active() ) {
			$query = "SELECT bwc.oid, count(bwc.ID) as conversions, SUM(bwc.wctotal) as revenue FROM {$wpdb->prefix}bwfan_conversions as bwc JOIN {$wpdb->prefix}posts as p ON bwc.wcid=p.ID WHERE bwc.oid IN ($ids) AND bwc.otype=1 GROUP BY bwc.oid";

			$conversions = self::get_db_cache_data( $query );
			if ( false === $conversions ) {
				$conversions = $wpdb->get_results( $query, ARRAY_A );
				self::set_db_cache_data( $query, $conversions );
			}

			foreach ( $conversions as $conversion ) {
				if ( absint( $conversion['oid'] ) ) {
					$conversion_data[ absint( $conversion['oid'] ) ] = $conversion;
				}
			}
		}

		$automations_meta = BWFAN_Model_Automationmeta::get_automations_meta( $automation_ids );

		foreach ( $all_automations as $automation ) {
			$id                 = absint( $automation['ID'] );
			$automation_actions = isset( $automations_meta[ $id ]['actions'] ) ? self::get_automation_actions( $automations_meta[ $id ]['actions'] ) : array();
			$source             = self::get_automation_source_name( $automation['source'] );
			$event              = self::get_automation_event_name( $automation['event'] );
			$run_count          = ( isset( $automations_meta[ $id ]['run_count'] ) ) ? $automations_meta[ $id ]['run_count'] : 0;

			$data = array(
				'id'                 => $id,
				'source'             => empty( $source ) ? __( 'Not Found', 'wp-marketing-automations' ) : $source,
				'last_update'        => isset( $automations_meta[ $id ]['m_date'] ) ? get_date_from_gmt( $automations_meta[ $id ]['m_date'], $date_format ) : '',
				'name'               => 2 === absint( $automation['v'] ) && isset( $automation['title'] ) ? $automation['title'] : ( isset( $automations_meta[ $id ]['title'] ) ? $automations_meta[ $id ]['title'] : '' ),
				'event'              => $event,
				'status'             => $automation['status'],
				'priority'           => $automation['priority'],
				'automation_actions' => $automation_actions,
				'run_count'          => $run_count,
				'conversions'        => 0,
				'revenue'            => 0,
				'migrated'           => isset( $automations_meta[ $id ]['v1_migrate'] ) ? true : false,
				'v'                  => isset( $automation['v'] ) ? $automation['v'] : 1,
			);

			if ( isset( $conversion_data[ $id ] ) ) {
				$data = array_replace( $data, $conversion_data[ $id ] );
			}

			$final_automation_data[ $id ] = $data;
		}

		return array(
			'automations'   => ! empty( $final_automation_data ) && is_array( $final_automation_data ) ? array_values( $final_automation_data ) : array(),
			'total_records' => ! empty( $overall_total ) ? absint( $overall_total ) : 0,
		);
	}

	/**
	 * @param $automation_actions
	 *
	 * @return array
	 */
	public static function get_automation_actions( $automation_actions ) {
		$actions = array();
		if ( empty( $automation_actions ) ) {
			return array();
		}

		$integration_data = $automation_actions;
		$unique_actions   = BWFAN_Core()->automations->get_unique_automation_actions( $integration_data );

		foreach ( $unique_actions as $action => $integration ) {
			$action_obj      = BWFAN_Core()->integration->get_action( $action );
			$integration_obj = BWFAN_Core()->integration->get_integration( $integration );
			if ( $integration_obj instanceof BWFAN_Integration && $action_obj instanceof BWFAN_Action ) {
				$nice_name               = $integration_obj->get_name();
				$actions[ $nice_name ][] = $action_obj->get_name();
			} else {
				$integration_name = self::get_entity_nice_name( 'integration', $integration );
				$action_name      = self::get_entity_nice_name( 'action', $action );
				if ( ! empty( $integration_name ) && ! empty( $action_name ) ) {
					$actions[ $integration_name ][] = $action_name;
				}
			}
		}

		return $actions;
	}

	public static function get_entity_nice_name( $key = 'source', $slug = 'wc' ) {
		$nice_names = self::get_default_event_action_names();
		if ( empty( $key ) || empty( $slug ) ) {
			return '';
		}
		if ( ! isset( $nice_names[ $key ] ) || ! isset( $nice_names[ $key ][ $slug ] ) ) {
			return '';
		}

		return $nice_names[ $key ][ $slug ];
	}

	/**
	 * Return Sources, Events, Integrations, Actions nice names.
	 * Useful when respective entity not exist but data as task or automation available.
	 *
	 * @return array
	 */
	public static function get_default_event_action_names() {
		return array(
			'source'      => array(
				'wc'             => __( 'WooCommerce', 'wp-marketing-automations' ),
				'wp'             => __( 'WordPress', 'wp-marketing-automations' ),
				'wcs'            => __( 'WooCommerce Subscription', 'wp-marketing-automations' ),
				'upstroke'       => __( 'UpStroke', 'wp-marketing-automations' ),
				'activecampaign' => __( 'Active Campaign', 'wp-marketing-automations' ),
				'drip'           => __( 'Drip', 'wp-marketing-automations' ),
				'affwp'          => __( 'AffiliateWp', 'wp-marketing-automations' ),
				'gf'             => __( 'Gravity Forms', 'wp-marketing-automations' ),
			),
			'event'       => array(
				'wc_comment_post'          => __( 'New Review', 'wp-marketing-automations' ),
				'wc_new_order'             => __( 'Order Created', 'wp-marketing-automations' ),
				'wc_order_note_added'      => __( 'Order Note Added', 'wp-marketing-automations' ),
				'wc_order_status_change'   => __( 'Order Status Changed', 'wp-marketing-automations' ),
				'wc_product_purchased'     => __( 'Order Created - Per Item', 'wp-marketing-automations' ),
				'wc_product_refunded'      => __( 'Order Item Refunded', 'wp-marketing-automations' ),
				'wc_product_stock_reduced' => __( 'Order Item Stock Reduced', 'wp-marketing-automations' ),
				'wc_customer_win_back'     => __( 'Customer Win Back', 'wp-marketing-automations' ),
				'ab_cart_abandoned'        => __( 'Cart Abandoned', 'wp-marketing-automations' ),
				'ab_cart_recovered'        => __( 'Cart Recovered', 'wp-marketing-automations' ),
				'wp_user_creation'         => __( 'User Creation', 'wp-marketing-automations' ),
				'wp_user_login'            => __( 'User Login', 'wp-marketing-automations' ),

				'ac_webhook_received'   => __( 'Webhook Received', 'wp-marketing-automations' ),
				'drip_webhook_received' => __( 'Webhook Received', 'wp-marketing-automations' ),

				'upstroke_funnel_ended'         => __( 'Funnel Ended', 'wp-marketing-automations' ),
				'upstroke_offer_viewed'         => __( 'Offer Viewed', 'wp-marketing-automations' ),
				'upstroke_product_accepted'     => __( 'Offer Accepted', 'wp-marketing-automations' ),
				'upstroke_offer_rejected'       => __( 'Offer Rejected', 'wp-marketing-automations' ),
				'upstroke_offer_payment_failed' => __( 'Offer Payment Failed', 'wp-marketing-automations' ),

				'wcs_created'                  => __( 'Subscriptions Created', 'wp-marketing-automations' ),
				'wcs_status_changed'           => __( 'Subscriptions Status Changed', 'wp-marketing-automations' ),
				'wcs_trial_end'                => __( 'Subscriptions Trial End', 'wp-marketing-automations' ),
				'wcs_before_renewal'           => __( 'Subscriptions Before Renewal', 'wp-marketing-automations' ),
				'wcs_before_end'               => __( 'Subscriptions Before End', 'wp-marketing-automations' ),
				'wcs_renewal_payment_complete' => __( 'Subscriptions Renewal Payment Complete', 'wp-marketing-automations' ),
				'wcs_renewal_payment_failed'   => __( 'Subscriptions Renewal Payment Failed', 'wp-marketing-automations' ),
				'wcs_card_expiry'              => __( 'Customer Before Card Expiry', 'wp-marketing-automations' ),

				'affwp_affiliate_report'     => __( 'Affiliate Digests', 'wp-marketing-automations' ),
				'affwp_application_approved' => __( 'Application Approved', 'wp-marketing-automations' ),
				'affwp_application_rejected' => __( 'Application Rejected', 'wp-marketing-automations' ),
				'affwp_makes_sale'           => __( 'Affiliate Makes A Sale', 'wp-marketing-automations' ),
				'affwp_referral_rejected'    => __( 'Referral Rejected', 'wp-marketing-automations' ),
				'affwp_signup'               => __( 'Application Sign Up', 'wp-marketing-automations' ),

				'gf_form_submit' => __( 'Form Submits', 'wp-marketing-automations' ),

			),
			'integration' => array(
				'wc'             => __( 'WooCommerce', 'wp-marketing-automations' ),
				'wp'             => __( 'WordPress', 'wp-marketing-automations' ),
				'wp_adv'         => __( 'WordPress Advanced', 'wp-marketing-automations' ),
				'zapier'         => __( 'Zapier', 'wp-marketing-automations' ),
				'activecampaign' => __( 'ActiveCampaign', 'wp-marketing-automations' ),
				'convertkit'     => __( 'ConvertKit', 'wp-marketing-automations' ),
				'drip'           => __( 'Drip', 'wp-marketing-automations' ),
				'slack'          => __( 'Slack', 'wp-marketing-automations' ),
				'twilio'         => __( 'Twilio', 'wp-marketing-automations' ),
				'google_sheets'  => __( 'Google Sheets', 'wp-marketing-automations' ),
			),
			'action'      => array(
				'wc_create_coupon'       => __( 'Create Coupon', 'wp-marketing-automations' ),
				'wc_add_order_note'      => __( 'Add Order Note', 'wp-marketing-automations' ),
				'wc_change_order_status' => __( 'Change Order Status', 'wp-marketing-automations' ),
				'wc_remove_coupon'       => __( 'Delete Coupon', 'wp-marketing-automations' ),

				'wp_sendemail' => __( 'Send Email', 'wp-marketing-automations' ),

				'za_send_data' => __( 'Send Data To Zapier', 'wp-marketing-automations' ),

				'wp_custom_callback'  => __( 'Custom Callback', 'wp-marketing-automations' ),
				'wp_debug'            => __( 'Debug', 'wp-marketing-automations' ),
				'wp_http_post'        => __( 'HTTP Post', 'wp-marketing-automations' ),
				'wp_createuser'       => __( 'Create User', 'wp-marketing-automations' ),
				'wp_update_user_meta' => __( 'Update User Meta', 'wp-marketing-automations' ),

				'ac_add_tag'               => __( 'Add Tags', 'wp-marketing-automations' ),
				'ac_add_to_automation'     => __( 'Add Contact To Automation', 'wp-marketing-automations' ),
				'ac_add_to_list'           => __( 'Add Contact To List', 'wp-marketing-automations' ),
				'ac_create_abandoned_cart' => __( 'Create Abandoned Cart', 'wp-marketing-automations' ),
				'ac_create_deal_note'      => __( 'Create Deal Note', 'wp-marketing-automations' ),
				'ac_create_deal'           => __( 'Create Deal', 'wp-marketing-automations' ),
				'ac_create_order'          => __( 'Create Order', 'wp-marketing-automations' ),
				'ac_rmv_from_automation'   => __( 'End Automation', 'wp-marketing-automations' ),
				'ac_rmv_from_list'         => __( 'Remove Contact From List', 'wp-marketing-automations' ),
				'ac_rmv_tag'               => __( 'Remove Tags', 'wp-marketing-automations' ),
				'ac_update_customfields'   => __( 'Update Fields', 'wp-marketing-automations' ),
				'ac_update_deal'           => __( 'Update Deal', 'wp-marketing-automations' ),

				'ck_add_customfields'  => __( 'Update Custom Fields', 'wp-marketing-automations' ),
				'ck_add_order'         => __( 'Create A New Purchase', 'wp-marketing-automations' ),
				'ck_add_tags'          => __( 'Add Tags', 'wp-marketing-automations' ),
				'ck_add_to_sequence'   => __( 'Add Subscriber To Sequence', 'wp-marketing-automations' ),
				'ck_rmv_from_sequence' => __( 'Remove Subscriber from Sequence', 'wp-marketing-automations' ),
				'ck_rmv_tags'          => __( 'Remove Tags', 'wp-marketing-automations' ),

				'dr_add_cart'          => __( 'Cart Activity', 'wp-marketing-automations' ),
				'dr_add_customfields'  => __( 'Update Custom fields of Subscriber', 'wp-marketing-automations' ),
				'dr_add_order'         => __( 'Add A New Order', 'wp-marketing-automations' ),
				'dr_add_tags'          => __( 'Add Tags', 'wp-marketing-automations' ),
				'dr_add_to_campaign'   => __( 'Add Subscriber to Campaign', 'wp-marketing-automations' ),
				'dr_add_to_workflow'   => __( 'Add Subscriber to Workflow', 'wp-marketing-automations' ),
				'dr_rmv_from_campaign' => __( 'Remove Subscriber from Campaign', 'wp-marketing-automations' ),
				'dr_rmv_from_workflow' => __( 'Remove Subscriber from Workflow', 'wp-marketing-automations' ),
				'dr_rmv_tags'          => __( 'Remove Tags', 'wp-marketing-automations' ),

				'sl_message_user' => __( 'Sends a message to a user', 'wp-marketing-automations' ),
				'sl_message'      => __( 'Sends a message to a channel', 'wp-marketing-automations' ),

				'twilio_send_sms' => __( 'Send SMS', 'wp-marketing-automations' ),

				'gs_insert_data' => __( 'Insert Row', 'wp-marketing-automations' ),
				'gs_update_data' => __( 'Update Row', 'wp-marketing-automations' ),

				'wcs_change_subscription_status' => __( 'Change Subscription Status', 'wp-marketing-automations' ),
				'wcs_send_subscription_invoice'  => __( 'Send Subscription Invoice', 'wp-marketing-automations' ),

				'affwp_change_affiliate_rate'  => __( 'Change Affiliate Rate', 'wp-marketing-automations' ),
				'affwp_change_referral_status' => __( 'Change Referral Status', 'wp-marketing-automations' ),
			),
		);
	}

	/**get automation source name using source_slug
	 *
	 * @param $source
	 *
	 * @return mixed|string
	 */
	public static function get_automation_source_name( $source ) {
		if ( empty( $source ) ) {
			return '';
		}

		$source_name = '';

		$single_source = BWFAN_Core()->sources->get_source( $source );
		if ( $single_source instanceof BWFAN_Source ) {
			$source_name = $single_source->get_name();
		} else {
			$source_name = self::get_entity_nice_name( 'source', $source );
		}

		return $source_name;
	}

	/** get automation event name using event_slug
	 *
	 * @param $event
	 *
	 * @return mixed|string
	 */
	public static function get_automation_event_name( $event ) {
		if ( empty( $event ) ) {
			return '';
		}

		$event_name = '';

		$single_event = BWFAN_Core()->sources->get_event( $event );
		if ( $single_event instanceof BWFAN_Event ) {
			$event_name = $single_event->get_name();
		} else {
			$event_name = self::get_entity_nice_name( 'event', $event );
		}

		return $event_name;
	}

	public static function automation_status_count( $aid, $status_aids, $counts ) {
		$aid_index = array_search( $aid, $status_aids );
		if ( false === $aid_index ) {
			return 0;
		}

		return isset( $counts[ $aid_index ] ) ? $counts[ $aid_index ] : 0;
	}

	/**
	 * @param $automation_id
	 * @param $automation_tasks
	 *
	 * @return array
	 */
	public static function get_automation_task_details( $automation_id, $automation_tasks ) {
		$output = array();
		foreach ( $automation_tasks as $key => $count ) {
			$output[] = array(
				'count' => absint( $count ),
				'name'  => ucfirst( $key ),
			);
		}

		return $output;
	}

	/**
	 * Skip child order
	 *
	 * @param $id int order id
	 *
	 * @return bool
	 */
	public static function bwf_check_to_skip_child_order( $id ) {
		return ( apply_filters( 'bwf_skip_sub_order', false ) && wp_get_post_parent_id( $id ) );
	}

	/**
	 * Remove action for without instance method  class found and return object of class
	 *
	 * @param $hook
	 * @param $cls string
	 * @param string $function
	 *
	 * @return |null
	 */
	public static function remove_actions( $hook, $cls, $function = '' ) {

		global $wp_filter;
		$object = null;
		if ( class_exists( $cls ) && isset( $wp_filter[ $hook ] ) && ( $wp_filter[ $hook ] instanceof WP_Hook ) ) {
			$hooks = $wp_filter[ $hook ]->callbacks;
			foreach ( $hooks as $priority => $reference ) {
				if ( is_array( $reference ) && count( $reference ) > 0 ) {
					foreach ( $reference as $index => $calls ) {
						if ( isset( $calls['function'] ) && is_array( $calls['function'] ) && count( $calls['function'] ) > 0 ) {
							if ( is_object( $calls['function'][0] ) ) {
								$cls_name = get_class( $calls['function'][0] );
								if ( $cls_name == $cls && $calls['function'][1] == $function ) {
									$object = $calls['function'][0];
									unset( $wp_filter[ $hook ]->callbacks[ $priority ][ $index ] );
								}
							} elseif ( $index == $cls . '::' . $function ) {
								// For Static Classess
								$object = $cls;
								unset( $wp_filter[ $hook ]->callbacks[ $priority ][ $cls . '::' . $function ] );
							}
						}
					}
				}
			}
		} elseif ( function_exists( $cls ) && isset( $wp_filter[ $hook ] ) && ( $wp_filter[ $hook ] instanceof WP_Hook ) ) {

			$hooks = $wp_filter[ $hook ]->callbacks;
			foreach ( $hooks as $priority => $reference ) {
				if ( is_array( $reference ) && count( $reference ) > 0 ) {
					foreach ( $reference as $index => $calls ) {
						$remove = false;
						if ( $index == $cls ) {
							$remove = true;
						} elseif ( isset( $calls['function'] ) && $cls == $calls['function'] ) {
							$remove = true;
						}
						if ( true == $remove ) {
							unset( $wp_filter[ $hook ]->callbacks[ $priority ][ $cls ] );
						}
					}
				}
			}
		}

		return $object;

	}

	/**
	 * Get all possible merge tags from the given string
	 *
	 * @param $str
	 *
	 * @return array
	 */
	public static function fetch_merge_tags( $str ) {
		$count     = substr_count( $str, '{{' );
		$merge_tag = [];
		for ( $i = 0; $i < $count; $i ++ ) {
			$tag = self::fetch_inner_most_tag( $str );
			if ( empty( $tag ) ) {
				continue;
			}
			$new_tag = str_replace( '{{', '${$', $tag );
			$new_tag = str_replace( '}}', '$}$', $new_tag );
			$str     = str_replace( $tag, $new_tag, $str );

			$tag = str_replace( '${$', '{{', $tag );
			$tag = str_replace( '$}$', '}}', $tag );

			$merge_tag[] = $tag;
		}

		/** also check if the body contain shortcode other than autonami */
		$str = str_replace( '<!--[', '', $str );
		$str = str_replace( '<![', '', $str );
		preg_match_all( '/\[(.*?)\]/', $str, $more_merge_tags );

		$external_merge_tag = [];
		if ( is_array( $more_merge_tags[0] ) && count( $more_merge_tags[0] ) > 0 ) {
			$external_merge_tag = array_filter( array_values( $more_merge_tags[0] ) );
		}

		$merge_tag = array_merge( $merge_tag, $external_merge_tag );

		return $merge_tag;
	}

	/**
	 * Get inner most merge tag from the string
	 *
	 * @param $str
	 *
	 * @return string
	 */
	public static function fetch_inner_most_tag( $str ) {
		$exp       = explode( '}}', $str );
		$start_exp = explode( '{{', $exp[0] );
		if ( 1 === count( $start_exp ) ) {
			return '';
		}
		$start_exp = end( $start_exp );

		return '{{' . $start_exp . '}}';
	}

	/**
	 * Replace merge tags with their values in highest to lowest tags in a single string
	 *
	 * @param $body
	 * @param $tags
	 *
	 * @return array|mixed|string|string[]
	 */
	public static function replace_merge_tags( $body, $tags ) {
		if ( empty( $tags ) || ! is_array( $tags ) || 0 === count( $tags ) ) {
			return $body;
		}

		/** Sort the array from highest number of merge tags to lowest */
		$keys = array_keys( $tags );
		usort( $keys, function ( $l1, $l2 ) {
			return strcmp( substr_count( $l1, '{{' ), substr_count( $l2, '{{' ) );
		} );
		$keys = array_reverse( $keys );

		/** Replace merge tags with actual data in body */
		foreach ( $keys as $tag ) {
			$body = str_replace( $tag, $tags[ $tag ], $body );
		}

		return $body;
	}

	/**
	 * Get global data available for automation contact
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public static function get_global_data( $data ) {
		if ( ! isset( $data['global'] ) ) {
			$data['global'] = [];
		}

		/** Set user id if available */
		if ( ! isset( $data['global']['user_id'] ) && isset( BWFAN_Common::$events_async_data['user_id'] ) ) {
			$data['global']['user_id'] = absint( BWFAN_Common::$events_async_data['user_id'] );
		}

		/** Set language if available */
		if ( ! isset( $data['global']['language'] ) && isset( BWFAN_Common::$events_async_data['language'] ) ) {
			$data['global']['language'] = BWFAN_Common::$events_async_data['language'];
		}

		/** Set user_id, if email is available */
		if ( ( ! isset( $data['global']['user_id'] ) || empty( $data['global']['user_id'] ) ) && is_email( $data['global']['email'] ) ) {
			$user = get_user_by( 'email', $data['global']['email'] );
			if ( $user instanceof WP_User ) {
				$data['global']['user_id'] = $user->ID;
			}

			/** Get contact ID for all event if there email id available */
			$user_id = isset( $data['global']['user_id'] ) ? $data['global']['user_id'] : null;
			$contact = bwf_get_contact( $user_id, $data['global']['email'] );
			if ( $contact instanceof WooFunnels_Contact && absint( $contact->get_id() ) === 0 ) {
				/** Create contact if not exists */
				$user_id = empty( $user_id ) ? 0 : $user_id;
				$email   = $data['global']['email'];
				$contact = self::create_contact( $contact, $user_id, $email );
			}
			$contact_id                   = $contact instanceof WooFunnels_Contact ? absint( $contact->get_id() ) : 0;
			$data['global']['contact_id'] = $contact_id;
			$data['global']['cid']        = $contact_id;
		}

		/** Set Phone if User ID is available */
		if ( isset( $data['global']['user_id'] ) && ! empty( $data['global']['user_id'] ) && ( ! isset( $data['global']['phone'] ) || empty( $data['global']['phone'] ) ) ) {
			$phone = get_user_meta( $data['global']['user_id'], 'billing_phone', true );
			if ( ! empty( $phone ) ) {
				$country = get_user_meta( $data['global']['user_id'], 'billing_country', true );
				if ( ! empty( $country ) ) {
					$phone = BWFAN_Phone_Numbers::add_country_code( $phone, $country );
				}
				$data['global']['phone'] = $phone;
			}
		}

		/** Set Phone if no User ID is set, but email is set */
		if ( isset( $data['global']['email'] ) && is_email( $data['global']['email'] ) && ( ! isset( $data['global']['phone'] ) || empty( $data['global']['phone'] ) ) ) {
			$order = BWFAN_Common::get_latest_order_by_email( $data['global']['email'] );
			if ( $order instanceof WC_Order ) {
				$phone = $order->get_billing_phone();
				if ( ! empty( $phone ) ) {
					$country = $order->get_billing_country();
					if ( ! empty( $country ) ) {
						$phone = BWFAN_Phone_Numbers::add_country_code( $phone, $country );
					}
					$data['global']['phone'] = $phone;
				}
			}
		}

		if ( isset( $data['global']['contact_id'] ) && ! empty( $data['global']['contact_id'] ) ) {
			$data['global']['cid'] = $data['global']['contact_id'];
		}

		/** Set Contact ID */
		if ( ! isset( $data['global']['cid'] ) ) {
			$email   = isset( $data['global']['email'] ) ? $data['global']['email'] : '';
			$phone   = isset( $data['global']['phone'] ) ? $data['global']['phone'] : '';
			$user_id = isset( $data['global']['user_id'] ) ? $data['global']['user_id'] : '';
			if ( ! empty( $email ) || ! empty( $phone ) || ! empty( $user_id ) ) {
				$contact = new WooFunnels_Contact( $user_id, $email, $phone );
				if ( absint( $contact->get_id() ) === 0 ) {
					$contact = self::create_contact( $contact, $user_id, $email, $phone );
				}
				$data['global']['cid'] = $contact->get_id();
			}
		}

		if ( empty( $data['global']['contact_id'] ) && ! empty( $data['global']['cid'] ) ) {
			$data['global']['contact_id'] = $data['global']['cid'];
		}

		return $data;
	}

	/**
	 * Create contact
	 *
	 * @param $contact
	 * @param $user_id
	 * @param $email
	 *
	 * @return WooFunnels_Contact
	 */
	public static function create_contact( $contact, $user_id, $email, $phone = '' ) {
		if ( empty( $user_id ) && empty( $email ) ) {
			return $contact;
		}
		if ( ! $contact instanceof WooFunnels_Contact ) {
			return $contact;
		}

		! empty( $email ) && $contact->set_email( $email );
		! empty( $phone ) && $contact->set_contact_no( $phone );
		! empty( $user_id ) && $contact->set_wpid( absint( $user_id ) );
		$contact->save();

		return $contact;
	}

	public static function get_latest_order_by_email( $email ) {
		if ( ! is_email( $email ) ) {
			return false;
		}
		if ( ! bwfan_is_woocommerce_active() ) {
			return false;
		}

		global $wpdb;

		$order_statuses = implode( "','", array_map( 'esc_sql', array_keys( wc_get_order_statuses() ) ) );
		if ( BWF_WC_Compatibility::is_hpos_enabled() ) {
			$last_order = $wpdb->get_var( "SELECT `id`
            FROM {$wpdb->prefix}wc_orders
            WHERE `billing_email` = '" . $email . "'
            AND   `type` = 'shop_order'
            AND   `status` IN ( '" . $order_statuses . "' )
            ORDER BY `id` DESC LIMIT 0,1" );

			if ( $last_order && $last_order > 0 ) {
				return wc_get_order( intval( $last_order ) );
			}
		}

		$last_order = $wpdb->get_var( "SELECT posts.ID
            FROM $wpdb->posts AS posts
            LEFT JOIN {$wpdb->postmeta} AS meta on posts.ID = meta.post_id
            WHERE meta.meta_key = '_billing_email'
            AND   meta.meta_value = '" . $email . "'
            AND   posts.post_type = 'shop_order'
            AND   posts.post_status IN ( '" . $order_statuses . "' )
            ORDER BY posts.ID DESC LIMIT 0,1" );

		if ( ! $last_order ) {
			return false;
		}

		return wc_get_order( intval( $last_order ) );
	}

	/**
	 * v2 Method: Prepared options for field schema
	 *
	 * @param $options
	 *
	 * @return array
	 */
	public static function prepared_field_options( $options ) {
		if ( ! is_array( $options ) || empty( $options ) ) {
			return [];
		}
		$prepared_options = array_map( function ( $label, $value ) {
			return array(
				'label' => $label,
				'value' => $value,
			);
		}, $options, array_keys( $options ) );

		return $prepared_options;
	}

	/**
	 * API access capabilities for rest endpoints
	 * @return array
	 */
	public static function access_capabilities() {
		$default_capabilities = array( 'manage_options' );
		$capabilities         = apply_filters( 'bwfan_crm_api_access_caps', [] );

		return array_merge( $default_capabilities, is_array( $capabilities ) ? $capabilities : [] );
	}

	public static function bwf_has_action_scheduled( $hook = '' ) {
		/** If AJAX call then no need to check */
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return true;
		}

		/** If empty just return */
		if ( empty( $hook ) ) {
			return false;
		}

		/** Hard recurring actions list to avoid duplicate calls */
		$list = [
			'bwfan_delete_expired_autonami_coupons',
			'bwfan_mark_abandoned_lost_cart',
			'bwfan_run_midnight_cron',
			'bwfan_run_midnight_connectors_sync',
			'bwfcrm_broadcast_run_queue',
			'bwfan_check_abandoned_carts',
			'bwfan_run_queue',
			'bwfan_run_queue_v2',
			'bwfan_5_minute_worker',
			'bwfan_delete_old_abandoned_carts',
			'bwfan_delete_logs'
		];

		/**
		 * Recurring action from above list and DB called made already
		 */
		if ( in_array( $hook, $list, true ) && is_array( self::$recurring_actions_db ) && isset( self::$recurring_actions_db[ $hook ] ) && true === self::$recurring_actions_db[ $hook ] ) {
			return true;
		}

		/**
		 * Call all recurring actions status - DB call
		 */
		if ( in_array( $hook, $list, true ) ) {
			global $wpdb;

			$table = $wpdb->prefix . 'bwf_actions';
			$p_key = 'id';

			$placeholders = array_fill( 0, count( $list ), '%s' );
			$placeholders = implode( ', ', $placeholders );

			$query  = $wpdb->prepare( "SELECT `{$p_key}`, `hook` FROM $table WHERE `hook` IN ($placeholders) AND `status` IN (0,1)", $list );
			$result = $wpdb->get_results( $query, ARRAY_A );
			if ( is_array( $result ) && count( $result ) > 0 ) {
				foreach ( $result as $val ) {
					self::$recurring_actions_db[ $val['hook'] ] = true;
				}
			}
			foreach ( $list as $val ) {
				if ( ! isset( self::$recurring_actions_db[ $val ] ) ) {
					self::$recurring_actions_db[ $val ] = false;
				}
			}

			return self::$recurring_actions_db[ $hook ];
		}

		/**
		 * Hook not found in the above list then native DB call
		 */
		return bwf_has_action_scheduled( $hook );
	}

	/** send test email from autonami screen */
	public static function send_test_email( $args ) {
		if ( empty( $args ) || ! is_email( $args['email'] ) || empty( $args['body'] ) ) {
			return false;
		}
		$global_email_settings = self::get_global_settings();

		BWFAN_Merge_Tag_Loader::set_data( array(
			'is_preview' => true,
		) );

		/** Look for contact if it exists */
		$contact = new WooFunnels_Contact( '', $args['email'] );
		if ( $contact instanceof WooFunnels_Contact && intval( $contact->get_id() ) > 0 ) {
			BWFAN_Merge_Tag_Loader::set_data( array(
				'contact_id' => $contact->get_id()
			) );
		}

		$subject = self::decode_merge_tags( $args['subject'], false );

		/** Get send email object */
		$send_email_ins = BWFAN_Core()->integration->get_action( 'wp_sendemail' );

		/** Email Body */
		$body = self::decode_merge_tags( $args['body'], false );
		$body = self::bwfan_correct_protocol_url( $body );
		$type = $args['type'] ?? 'rich';
		$data = [];

		/** Getting the template type in id */
		switch ( $type ) {
			case 'rich':
				$data['template'] = 1;
				break;
			case 'wc':
				if ( class_exists( 'WooCommerce' ) ) {
					$data['template'] = 2;
				}
				break;
			case 'html':
				$data['template'] = 3;
				break;
			case 'editor':
				if ( bwfan_is_autonami_pro_active() ) {
					$data['template'] = 4;
				}
				break;
		}

		$data['body'] = $body;
		$body         = $send_email_ins->email_content_v2( $data );

		if ( bwfan_is_autonami_pro_active() ) {
			$body = self::add_test_utm_params( $body, $args );
		}

		/** Email Pre-header */
		$pre_header = $args['preheader'] ?? '';
		$body       = ! empty( $pre_header ) ? $send_email_ins->append_to_email_body( $body, $pre_header, '' ) : $body;

		/** Email Headers */
		$from_name      = $global_email_settings['bwfan_email_from_name'] ?? '';
		$from_email     = $global_email_settings['bwfan_email_from'] ?? '';
		$reply_to_email = $global_email_settings['bwfan_email_reply_to'] ?? '';

		/** Override from broadcast or forms */
		$from_name      = $args['senders_name'] ?? $from_name;
		$from_email     = $args['senders_email'] ?? $from_email;
		$reply_to_email = $args['reply_to'] ?? $reply_to_email;

		if ( isset( $args['overRideSenderInfo'] ) && 1 === absint( $args['overRideSenderInfo'] ) ) {
			$from_name      = isset( $args['overRideInfo']['from_name'] ) && ! empty( $args['overRideInfo']['from_name'] ) ? $args['overRideInfo']['from_name'] : $from_name;
			$from_email     = isset( $args['overRideInfo']['from_email'] ) && ! empty( $args['overRideInfo']['from_email'] ) ? $args['overRideInfo']['from_email'] : $from_email;
			$reply_to_email = isset( $args['overRideInfo']['reply_to_email'] ) && ! empty( $args['overRideInfo']['reply_to_email'] ) ? $args['overRideInfo']['reply_to_email'] : $reply_to_email;
		}

		$override_data = [
			'from_name'      => $from_name,
			'from_email'     => $from_email,
			'reply_to_email' => $reply_to_email,
		];
		do_action( 'bwfan_before_send_email', $override_data, $body );

		/** Setup Headers */
		$header   = array();
		$header[] = 'MIME-Version: 1.0';
		if ( ! empty( $from_email ) && ! empty( $from_name ) ) {
			$header[] = 'From: ' . $from_name . ' <' . $from_email . '>';
		}
		if ( ! empty( $reply_to_email ) ) {
			$header[] = 'Reply-To: ' . $reply_to_email;
		}
		$header[] = 'Content-type:text/html;charset=UTF-8';


		/** Set unsubscribe link in header */
		$unsubscribe_link = BWFAN_Common::get_unsubscribe_link( [ 'uid' => $contact->get_uid() ] );
		if ( ! empty( $unsubscribe_link ) ) {
			$header[] = "List-Unsubscribe: <$unsubscribe_link>";
			$header[] = "List-Unsubscribe-Post: List-Unsubscribe=One-Click";
		}

		/** Removed wp mail filters */
		$send_email_ins->before_executing_automation();

		return wp_mail( $args['email'], $subject, $body, $header );
	}

	/**
	 * Add UTM parameters in test email
	 *
	 * @param $body
	 * @param $args
	 *
	 * @return array|mixed|string|string[]|null
	 */
	public static function add_test_utm_params( $body, $args, $mode = 'email' ) {
		$utm_data = array();

		/** Normalise Data */
		if ( isset( $args['utmDetails']['content'] ) ) {
			$utm_data['utm_content'] = $args['utmDetails']['content'];
		}

		if ( isset( $args['utmDetails']['medium'] ) ) {
			$utm_data['utm_medium'] = $args['utmDetails']['medium'];
		}

		if ( isset( $args['utmDetails']['name'] ) ) {
			$utm_data['utm_name'] = $args['utmDetails']['name'];
		}

		if ( isset( $args['utmDetails']['source'] ) ) {
			$utm_data['utm_source'] = $args['utmDetails']['source'];
		}

		if ( isset( $args['utmDetails']['term'] ) ) {
			$utm_data['utm_term'] = $args['utmDetails']['term'];
		}

		if ( empty( $utm_data ) ) {
			return $body;
		}
		/** Add flag to append UTM Params */
		$utm_data['append_utm'] = isset( $args['utmEnabled'] ) ? $args['utmEnabled'] : 0;

		$ins = BWFAN_UTM_Tracking::get_instance();

		return $ins->maybe_add_utm_parameters( $body, $utm_data, $mode );
	}

	public static function decode_merge_tags( $string, $is_crm = false ) {
		if ( empty( $string ) ) {
			return '';
		}

		$string = self::strip_merge_tags( $string, $is_crm );
		$string = apply_filters( 'bwfan_pre_decode_merge_tags', $string );

		$string = str_replace( '[if', '[bwfno_if', $string );
		$string = str_replace( '[endif', '[bwfno_endif', $string );

		do_action( 'bwfan_before_decode_merge_tags', $string );

		$string = BWFAN_Merge_Tag::maybe_parse_nested_merge_tags( $string );

		do_action( 'bwfan_after_decode_merge_tags', $string );

		$string = str_replace( '[bwfno_if', '[if', $string );
		$string = str_replace( '[bwfno_endif', '[endif', $string );

		$string = apply_filters( 'bwfan_post_decode_merge_tags', $string );

		return $string;
	}

	public static function strip_merge_tags( $string, $is_crm = false ) {
		/** Don't strip from the style tag */
		$elements = explode( '</style>', $string );

		$shortcode_head = true === $is_crm ? '[bwfan_crm_' : '[bwfan_';

		$stripped_merge_tags = array();
		foreach ( $elements as $element ) {
			$strings               = explode( '<style', $element );
			$strings[0]            = str_replace( '{{', $shortcode_head, $strings[0] );
			$strings[0]            = str_replace( '}}', ']', $strings[0] );
			$stripped_merge_tags[] = implode( '<style', $strings );
		}

		return implode( '</style>', $stripped_merge_tags );
	}

	/**
	 * Replace the duplicate http from a string.
	 *
	 * @param $string
	 *
	 * @return mixed
	 */
	public static function bwfan_correct_protocol_url( $string ) {
		$string = trim( $string );
		$string = str_replace( 'http://https://', 'https://', $string );
		$string = str_replace( 'https://http://', 'http://', $string );
		$string = str_replace( 'https://https://', 'https://', $string );
		$string = str_replace( 'http://http://', 'http://', $string );

		return $string;
	}

	public static function get_completed_contacts( $aid, $sid, $type = '', $offset = 0, $limit = 25 ) {

		/**Get active automation contacts*/
		if ( 'active' === $type ) {
			return BWFAN_Model_Automation_Contact::get_contacts_journey( $aid, '', $limit, $offset, true, false, 'active' );
		}

		/**Get automation completed contacts*/
		if ( 0 === absint( $sid ) ) {
			return BWFAN_Model_Automation_Complete_Contact::get_automation_completed_contacts( $aid, $offset, $limit );
		}

		/**Get step completed contacts*/
		return BWFAN_Model_Automation_Contact_Trail::get_step_completed_contacts( $aid, $sid, $type, $offset, $limit );
	}

	public static function get_automation_contacts_journey( $aid, $search = '' ) {
		$automation_contacts = BWFAN_Model_Automation_Contact::get_contacts_journey( $aid, $search );

		$limit = 10;
		if ( is_array( $automation_contacts ) && count( $automation_contacts ) > 0 ) {
			$limit -= count( $automation_contacts );
		}

		/** if searchable limit, then search in automation contact table */
		$automation_complete_contacts = [];
		if ( $limit > 0 ) {
			$automation_complete_contacts = BWFAN_Model_Automation_Complete_Contact::get_contacts_journey( $aid, $search, $limit );
		}

		return array_merge( $automation_contacts, $automation_complete_contacts );
	}

	public static function get_automation_contacts( $aid, $search, $limit = 25, $offset = 0, $status = 'all' ) {

		/** Get completed automation contacts */
		if ( 'completed' === $status ) {
			$automation_complete_contacts = BWFAN_Model_Automation_Complete_Contact::get_contacts_journey( $aid, $search, $limit, $offset, true, true, $status );

			return [
				'contacts' => ! empty( $automation_complete_contacts['contacts'] ) ? $automation_complete_contacts['contacts'] : [],
				'total'    => isset( $automation_complete_contacts['total'] ) ? absint( $automation_complete_contacts['total'] ) : 0
			];
		}

		/** Get active, failed, paused automation contacts */
		$automation_contacts = BWFAN_Model_Automation_Contact::get_contacts_journey( $aid, $search, $limit, $offset, true, true, $status );

		return [
			'contacts' => ! empty( $automation_contacts['contacts'] ) ? $automation_contacts['contacts'] : [],
			'total'    => isset( $automation_contacts['total'] ) ? absint( $automation_contacts['total'] ) : 0
		];
	}

	public static function get_step_by_trail( $trail ) {
		$results = BWFAN_Model_Automation_Step::get_step_by_trail( $trail );
		if ( ! empty( $results ) ) {
			$results = array_map( function ( $data ) {
				$data['run_time'] = date( 'Y-m-d H:i:s', $data['run_time'] );
				$action           = ! empty( $data['action'] ) ? json_decode( $data['action'], true ) : [];
				$data['action']   = '';
				$nice_name        = '';
				if ( 2 === absint( $data['type'] ) ) {
					$nice_name = isset( $action['action'] ) && BWFAN_Core()->integration->get_action( $action['action'] ) instanceof BWFAN_Action ? BWFAN_Core()->integration->get_action( $action['action'] )->get_name() : '';
				} else if ( 3 === absint( $data['type'] ) ) {
					$nice_name = isset( $action['benchmark'] ) && BWFAN_Core()->sources->get_event( $action['benchmark'] ) instanceof BWFAN_Event ? BWFAN_Core()->sources->get_event( $action['benchmark'] )->get_name() : '';
				}
				$data['action'] = $nice_name;

				return $data;
			}, $results );
		}

		return isset( $results[0] ) ? $results[0] : $results;
	}

	public static function get_automations_for_contact( $cid, $limit, $offset ) {
		$automation_contacts = BWFAN_Model_Automation_Contact::get_automation_contacts( $cid, '', $limit, $offset, true, true );

		if ( isset( $automation_contacts['contacts'] ) && is_array( $automation_contacts['contacts'] ) && count( $automation_contacts['contacts'] ) > 0 ) {
			$limit -= count( $automation_contacts['contacts'] );
		}

		/**if no active contact found on other page( for pagination ) then subsctract total active count from offset*/
		if ( $offset > 0 && 0 === count( $automation_contacts['contacts'] ) && absint( $automation_contacts['total'] ) > 0 ) {
			$offset -= absint( $automation_contacts['total'] );
		}

		$automation_complete_contacts = [
			'contacts' => [],
			'total'    => 0
		];

		/** if searchable limit, then search in automation contact table */
		if ( $limit > 0 ) {
			$automation_complete_contacts = BWFAN_Model_Automation_Complete_Contact::get_automation_contacts( $cid, '', $limit, $offset, true, true );
		}

		$contacts = array_merge( $automation_contacts['contacts'], $automation_complete_contacts['contacts'] );

		return [
			'contacts' => $contacts,
			'total'    => absint( $automation_contacts['total'] ) + absint( $automation_complete_contacts['total'] )
		];
	}

	public static function get_dynamic_string( $count = 8 ) {
		$chars = apply_filters( 'bwfan_dynamic_string_chars', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789' );

		return substr( str_shuffle( $chars ), 0, $count );
	}

	/**
	 * Update the execution time of automation contacts if delay time changed
	 *
	 * @param $step_id
	 *
	 */
	public static function bwfan_delay_step_updated( $step_id ) {
		$option_key  = 'bwf_delay_automations_' . $step_id;
		$automations = get_option( $option_key, [] );
		if ( empty( $automations ) ) {
			delete_option( $option_key );
			bwf_unschedule_actions( 'bwfan_delay_step_updated', [ 'sid' => $step_id ] );

			return;
		}

		$updated_automations = $automations;
		$start_time          = time();
		foreach ( $automations as $key => $automation_contact_id ) {
			/** Checking 10 seconds of processing */
			if ( ( time() - $start_time ) > 10 ) {
				return;
			}

			$data = BWFAN_Model_Automation_Contact::get_data( $automation_contact_id );

			/** If automation contact is failed or last step was not the current step id */
			if ( empty( $data ) || ( 2 === intval( $data['status'] ) ) || intval( $step_id ) !== intval( $data['last'] ) ) {
				unset( $updated_automations[ $key ] );
				update_option( $option_key, $updated_automations, false );
				continue;
			}

			$ins                = new BWFAN_Delay_Controller();
			$ins->contact_id    = $data['cid'];
			$ins->automation_id = $data['aid'];
			$ins->step_id       = $step_id;
			$ins->populate_automation_contact_data();
			$ins->populate_step_data();

			$new_e_time = $ins->get_time( $data['last_time'] );
			$data       = [ 'e_time' => $new_e_time ];
			BWFAN_Model_Automation_Contact::update( $data, array(
				'ID' => absint( $automation_contact_id ),
			) );

			unset( $updated_automations[ $key ] );
			update_option( $option_key, $updated_automations, false );
		}
	}

	public static function bwfan_goal_step_updated( $step_id, $goal_run ) {
		$option_key = 'bwf_goal_automations_' . $step_id;

		$automations = get_option( $option_key, [] );
		if ( empty( $automations ) ) {
			delete_option( $option_key );
			bwf_unschedule_actions( 'bwfan_goal_step_updated', [ 'sid' => $step_id, 'goal_run' => $goal_run ] );

			return;
		}

		$updated_automations = $automations;
		$start_time          = time();
		foreach ( $automations as $key => $automation_contact_id ) {
			/** Checking 10 seconds of processing */
			if ( ( time() - $start_time ) > 10 ) {
				return;
			}

			$data = BWFAN_Model_Automation_Contact::get_data( $automation_contact_id );
			/** If automation contact is not active */
			if ( empty( $data ) || intval( $step_id ) !== intval( $data['last'] ) ) {
				unset( $updated_automations[ $key ] );
				update_option( $option_key, $updated_automations, false );
				continue;
			}
			$status = '';
			if ( 'wait' === $goal_run && 1 === absint( $data['status'] ) ) {
				$status = 4;
			}

			if ( 'continue' === $goal_run && 4 === absint( $data['status'] ) ) {
				$status = 1;
			}

			if ( ! empty( $status ) ) {
				$data = [ 'status' => $status ];
				BWFAN_Model_Automation_Contact::update( $data, array(
					'ID' => absint( $automation_contact_id ),
				) );
			} else {
				/** End Automation */
				self::end_v2_automation( 0, $data );
			}

			unset( $updated_automations[ $key ] );
			update_option( $option_key, $updated_automations, false );
		}

	}

	/**
	 * End v2 automation
	 *
	 * @param $a_cid
	 * @param $automation_contact
	 *
	 * @return bool
	 */
	public static function end_v2_automation( $a_cid, $automation_contact = [] ) {
		if ( empty( $automation_contact ) && ! empty( $a_cid ) ) {
			$automation_contact = BWFAN_Model_Automation_Contact::get( $a_cid );
		}

		if ( ! is_array( $automation_contact ) || 0 === count( $automation_contact ) ) {
			return false;
		}

		if ( absint( $automation_contact['last'] ) > 0 ) {
			/** set data for insert complete contact automation */
			$data = [
				'cid'    => $automation_contact['cid'],
				'aid'    => $automation_contact['aid'],
				'event'  => $automation_contact['event'],
				's_date' => $automation_contact['c_date'],
				'c_date' => current_time( 'mysql', 1 ),
				'data'   => $automation_contact['data'],
				'trail'  => $automation_contact['trail'],
			];

			BWFAN_Model_Automation_Complete_Contact::insert( $data );

			/** Update status as success for any step trail where status was waiting */
			BWFAN_Model_Automation_Contact_Trail::update_all_step_trail_status_complete( $automation_contact['trail'] );
		}

		/** Delete the automation contact entry */
		BWFAN_Model_Automation_Contact::delete( $automation_contact['ID'] );

		/** Update automation contact fields */
		self::update_automation_contact_fields( $automation_contact['cid'], $automation_contact['aid'] );

		return true;
	}

	/**
	 * Update automation contact fields
	 *
	 */
	public static function update_automation_contact_fields( $contact_id, $automation_id ) {
		if ( ! class_exists( 'BWFCRM_Contact' ) ) {
			return;
		}

		/** CRM contact object */
		$contact = new BWFCRM_Contact( $contact_id );

		if ( ! $contact->is_contact_exists() ) {
			return;
		}
		$automation_id = (string) $automation_id;

		/** Set automation id in active automation */
		$active_automations    = $contact->get_field_by_slug( 'automation-active' );
		$active_automation_ids = json_decode( $active_automations, true );
		$active_automation_ids = ( ! empty( $active_automation_ids ) && is_array( $active_automation_ids ) ) ? $active_automation_ids : array();

		/** Remove active automation from field */
		$aid_index = array_search( $automation_id, $active_automation_ids );
		if ( false !== $aid_index ) {
			unset( $active_automation_ids[ $aid_index ] );
		}

		$active_automation_ids = array_unique( $active_automation_ids );
		sort( $active_automation_ids );
		$active_automation_ids = wp_json_encode( $active_automation_ids );

		/** Set automation id in completed automation */
		$completed_automations    = $contact->get_field_by_slug( 'automation-completed' );
		$completed_automation_ids = json_decode( $completed_automations, true );
		$completed_automation_ids = ( ! empty( $completed_automation_ids ) && is_array( $completed_automation_ids ) ) ? $completed_automation_ids : array();

		$completed_automation_ids[] = $automation_id;

		$completed_automation_ids = array_unique( $completed_automation_ids );
		sort( $completed_automation_ids );
		$completed_automation_ids = wp_json_encode( $completed_automation_ids );

		/** Set updated automation active and completed automation value */
		$contact->set_field_by_slug( 'automation-active', $active_automation_ids );
		$contact->set_field_by_slug( 'automation-completed', $completed_automation_ids );
		$contact->save_fields();
	}


	public static function bwfan_automation_step_deleted( $sid ) {
		$option_key          = 'bwf_queued_automations_' . $sid;
		$automation_contacts = get_option( $option_key, [] );
		if ( empty( $automation_contacts ) ) {
			delete_option( $option_key );
			bwf_unschedule_actions( 'bwfan_automation_step_deleted', [ 'sid' => $sid ] );

			return;
		}
		$automation_contacts = json_decode( $automation_contacts, true );

		$updated_automations = $automation_contacts;
		$start_time          = time();
		$batch_size          = 20;
		while ( ( time() - $start_time ) < 10 ) {

			/** Get first 20 automation contacts */
			$automation_contacts = ( count( $automation_contacts ) > $batch_size ) ? array_slice( $automation_contacts, 0, $batch_size ) : $automation_contacts;
			$selected_ids        = implode( ', ', $automation_contacts );

			global $wpdb;
			$query  = " SELECT `ID`,`trail` FROM {$wpdb->prefix}bwfan_automation_contact WHERE `last` = $sid AND `ID` IN ($selected_ids) AND (`status` = 1 OR `status` = 4) ";
			$result = $wpdb->get_results( $query, ARRAY_A );
			if ( empty( $result ) ) {
				$updated_automations = array_diff( $updated_automations, $automation_contacts );
				sort( $updated_automations );
				update_option( $option_key, $updated_automations, false );
				$automation_contacts = $updated_automations;

				continue;
			}
			$ids = array_column( $result, 'ID' );

			/** Remove automation contact id if not in data */
			$remove_ids = array_diff( $automation_contacts, $ids );
			if ( ! empty( $remove_ids ) ) {
				$updated_automations = array_diff( $updated_automations, $remove_ids );
				sort( $updated_automations );
				update_option( $option_key, $updated_automations, false );
			}

			$trails = array_column( $result, 'trail' );
			$e_time = current_time( 'timestamp', 1 );
			BWFAN_Model_Automation_Contact::update_status_by_multiple_ids( $ids, 1, $e_time );
			BWFAN_Model_Automation_Contact_Trail::update_multiple_trail_status( $trails, $sid );

			/** Remove automation contact id from data after updating the status of automation and trail */
			$updated_automations = array_diff( $updated_automations, $ids );
			if ( empty( $updated_automations ) ) {
				delete_option( $option_key );

				return;
			}
			sort( $updated_automations );
			update_option( $option_key, $updated_automations, false );

			/** Assign updated automation contact */
			$automation_contacts = $updated_automations;
		}
	}

	/**
	 * Works with pro version only
	 *
	 * @return void
	 */
	public static function bwfan_store_automation_completed_ids() {
		$max_count = get_option( 'bwfan_max_automation_completed', 0 );
		$processed = get_option( 'bwfan_automation_completed_processed', 0 );

		global $wpdb;
		$query       = " SELECT `cid`, GROUP_CONCAT(`aid`) AS `aid` FROM `{$wpdb->prefix}bwfan_automation_complete_contact` WHERE `ID` <= {$max_count} GROUP BY `cid` ORDER BY `cid` ASC LIMIT {$processed},20";
		$automations = $wpdb->get_results( $query, ARRAY_A );
		if ( empty( $automations ) ) {
			delete_option( 'bwfan_max_automation_completed' );
			delete_option( 'bwfan_automation_completed_processed' );
			bwf_unschedule_actions( 'bwfan_store_automation_completed_ids' );

			return;
		}

		$start_time = time();
		foreach ( $automations as $data ) {
			if ( ( time() - $start_time ) > 10 ) {
				return;
			}
			$aids = explode( ',', $data['aid'] );
			$aids = array_unique( $aids );
			$cid  = $data['cid'];

			/** CRM contact object */
			$contact = new BWFCRM_Contact( $cid );
			if ( ! $contact->is_contact_exists() ) {
				$processed ++;
				update_option( 'bwfan_automation_completed_processed', $processed );
				continue;
			}

			/** Get automation id in completed automation */
			$completed_aids = self::get_automation_contact_field_value( $contact, $aids, 'automation-completed' );

			$entered_aids = self::get_automation_contact_field_value( $contact, $aids, 'automation-entered' );

			$contact->set_field_by_slug( 'automation-completed', $completed_aids );
			$contact->set_field_by_slug( 'automation-entered', $entered_aids );
			$contact->save_fields();
			$processed ++;
			update_option( 'bwfan_automation_completed_processed', $processed );
		}
	}

	/**
	 * @param $contact BWFCRM_Contact
	 * @param $new_aids
	 * @param $field_slug
	 *
	 * @return false|string
	 */
	public static function get_automation_contact_field_value( $contact, $new_aids, $field_slug ) {
		$automation_aids = $contact->get_field_by_slug( $field_slug );
		$automation_aids = json_decode( $automation_aids, true );
		$automation_aids = ( ! empty( $automation_aids ) && is_array( $automation_aids ) ) ? $automation_aids : array();

		$automation_aids = array_merge( $automation_aids, $new_aids );
		$automation_aids = array_unique( $automation_aids );
		sort( $automation_aids );
		$automation_aids = wp_json_encode( $automation_aids );

		return $automation_aids;
	}

	/**
	 * Works with pro version only
	 *
	 * @return void
	 */
	public static function bwfan_store_automation_active_ids() {
		$max_count = get_option( 'bwfan_max_active_automation', 0 );
		$processed = get_option( 'bwfan_active_automation_processed', 0 );

		global $wpdb;
		$query       = "SELECT `cid`, GROUP_CONCAT(`aid`) AS `aid` FROM `{$wpdb->prefix}bwfan_automation_contact` WHERE `ID` <= {$max_count} GROUP BY `cid` ORDER BY `cid` ASC LIMIT {$processed},20";
		$automations = $wpdb->get_results( $query, ARRAY_A );

		if ( empty( $automations ) ) {
			delete_option( 'bwfan_max_active_automation' );
			delete_option( 'bwfan_active_automation_processed' );
			bwf_unschedule_actions( 'bwfan_store_automation_active_ids' );

			return;
		}

		$start_time = time();
		foreach ( $automations as $data ) {
			if ( ( time() - $start_time ) > 10 ) {
				return;
			}
			$aids = explode( ',', $data['aid'] );
			$aids = array_unique( $aids );
			$cid  = $data['cid'];

			/** CRM contact object */
			$contact = new BWFCRM_Contact( $cid );
			if ( ! $contact->is_contact_exists() ) {
				$processed ++;
				update_option( 'bwfan_active_automation_processed', $processed );
				continue;
			}

			/** Get automation ids in entered automation */
			$entered_aids = self::get_automation_contact_field_value( $contact, $aids, 'automation-entered' );

			/** Get automation ids in active automation */
			$active_aids = self::get_automation_contact_field_value( $contact, $aids, 'automation-active' );

			$contact->set_field_by_slug( 'automation-active', $active_aids );
			$contact->set_field_by_slug( 'automation-entered', $entered_aids );
			$contact->save_fields();
			$processed ++;
			update_option( 'bwfan_active_automation_processed', $processed );
		}
	}

	public static function get_contact_notes_type() {
		return apply_filters( 'bwfcrm_contact_note_types', array(
			array(
				'value' => 'billing',
				'label' => __( 'Billing', 'wp-marketing-automations' ),
			),
			array(
				'value' => 'shipping',
				'label' => __( 'Shipping', 'wp-marketing-automations' ),
			),
			array(
				'value' => 'refund',
				'label' => __( 'Refund', 'wp-marketing-automations' ),
			),
			array(
				'value' => 'subscription',
				'label' => __( 'Subscription', 'wp-marketing-automations' ),
			),
			array(
				'value' => 'feedback',
				'label' => __( 'Feedback', 'wp-marketing-automations' ),
			),
			array(
				'value' => 'log',
				'label' => __( 'Log', 'wp-marketing-automations' ),
			),
			array(
				'value' => 'others',
				'label' => __( 'Others', 'wp-marketing-automations' ),
			),
		) );
	}

	/**
	 * Checks if v1 older automation is active or not for functioning
	 *
	 * @return bool
	 */
	public static function is_automation_v1_active() {
		if ( defined( 'BWFAN_AUTOMATION_V1_DISABLE' ) && true === BWFAN_AUTOMATION_V1_DISABLE ) {
			return false;
		}

		$active = get_option( 'bwfan_automation_v1', 1 );

		return ( 1 === intval( $active ) ) ? true : false;
	}

	public static function bulk_contact_automation_end( $dynamic_str, $aid, $action, $status ) {
		$option_key = "bwfan_bulk_automation_contact_end_{$dynamic_str}";
		$data       = get_option( $option_key, [] );

		if ( empty( $data ) ) {
			delete_option( $option_key );
			bwf_unschedule_actions( 'bwfan_automation_contact_bulk_action', [ 'key' => $dynamic_str, 'aid' => $aid, 'action' => $action, 'status' => $status ] );

			return;
		}

		$updated_data = $data;
		$start_time   = time();
		foreach ( $data as $key => $id ) {

			if ( ( time() - $start_time ) > 10 ) {
				return;
			}

			$automation_contact = BWFAN_Model_Automation_Contact::get( $id );
			/**End Automation */
			self::end_v2_automation( 0, $automation_contact );

			unset( $updated_data[ $key ] );
			update_option( $option_key, $updated_data, false );
		}

	}

	public static function bwfan_automation_contact_bulk_action( $dynamic_str, $aid, $action, $status ) {
		if ( 'end' === $action ) {
			self::bulk_contact_automation_end( $dynamic_str, $aid, $action, $status );

			return;
		}
		$option_key = "bwfan_bulk_automation_contact_{$action}_{$dynamic_str}";
		$a_cids     = get_option( $option_key, [] );
		if ( empty( $a_cids ) ) {
			delete_option( $option_key );
			bwf_unschedule_actions( 'bwfan_automation_contact_bulk_action', [ 'key' => $dynamic_str, 'aid' => $aid, 'action' => $action, 'status' => $status ] );

			return;
		}

		if ( 'completed' === $status ) {
			$action = 'delete_complete';
		}
		self::perform_bulk_action( $a_cids, $option_key, $action );
	}

	public static function perform_bulk_action( $a_cids, $option_key, $action = '' ) {
		$updated_a_cids = $a_cids;
		$start_time     = time();
		$batch_size     = 20;
		while ( ( time() - $start_time ) < 10 ) {
			/** Get first 20 automation contacts */
			$a_cids = ( count( $a_cids ) > $batch_size ) ? array_slice( $a_cids, 0, $batch_size ) : $a_cids;
			if ( empty( $a_cids ) ) {
				delete_option( $option_key );

				return;
			}

			$selected_ids = implode( ', ', $a_cids );

			global $wpdb;

			if ( 'delete_complete' === $action ) {
				$query = " SELECT `ID`,`cid`,`aid`,`trail` FROM {$wpdb->prefix}bwfan_automation_complete_contact WHERE `ID` IN ($selected_ids) ";
			} else {
				$query = " SELECT `ID`,`cid`,`aid`,`trail` FROM {$wpdb->prefix}bwfan_automation_contact WHERE `ID` IN ($selected_ids)";
			}

			try {
				BWFAN_Common::log_test_data( $option_key . ' - ' . $query, __FUNCTION__ );
				$result = $wpdb->get_results( $query, ARRAY_A );
			} catch ( Error $e ) {
				delete_option( $option_key );

				return;
			}

			if ( empty( $result ) ) {
				$updated_a_cids = array_diff( $updated_a_cids, $a_cids );
				sort( $updated_a_cids );
				update_option( $option_key, $updated_a_cids, false );
				$a_cids = $updated_a_cids;

				continue;
			}
			$ids = array_column( $result, 'ID' );

			/** Remove automation contact id if not in data */
			$remove_ids = array_diff( $a_cids, $ids );
			if ( ! empty( $remove_ids ) ) {
				$updated_a_cids = array_diff( $updated_a_cids, $remove_ids );
				sort( $updated_a_cids );
				update_option( $option_key, $updated_a_cids, false );
			}

			$trails = array_column( $result, 'trail' );
			switch ( true ) {
				case ( 'delete' === $action || 'delete_complete' === $action ):
					self::delete_automations( $ids, $trails, $action, $result );
					break;
				case ( 'paused' === $action ) :
					BWFAN_Model_Automation_Contact::update_status_by_multiple_ids( $ids, 3 );
					break;
				case ( 'unpaused' === $action ):
					BWFAN_Model_Automation_Contact::update_status_by_multiple_ids( $ids );
					break;
				case ( 'retry' === $action ):
					BWFAN_Model_Automation_Contact::update_status_by_multiple_ids( $ids, 6 );
					break;
				case ( 'run_now' === $action ):
					BWFAN_Model_Automation_Contact::update_e_time_col_of_ids( $ids );
					break;
			}

			/** Remove automation contact id from data after updating the status of automation and trail */
			$updated_a_cids = array_diff( $updated_a_cids, $ids );
			if ( empty( $updated_a_cids ) ) {
				delete_option( $option_key );

				return;
			}
			sort( $updated_a_cids );
			update_option( $option_key, $updated_a_cids, false );

			/** Assign updated automation contact */
			$a_cids = $updated_a_cids;
		}
	}

	public static function delete_automations( $ids, $trails, $action = '', $automation_data = [] ) {
		global $wpdb;

		$table_name = "{$wpdb->prefix}bwfan_automation_contact";
		if ( 'delete_complete' === $action ) {
			$table_name = "{$wpdb->prefix}bwfan_automation_complete_contact";
		}
		$ids   = implode( "', '", $ids );
		$query = "DELETE FROM $table_name WHERE ID IN ('$ids')";
		$wpdb->query( $query );

		$trails = implode( "', '", $trails );
		$query  = "DELETE FROM {$wpdb->prefix}bwfan_automation_contact_trail WHERE tid IN ('$trails')";
		$wpdb->query( $query );

		if ( ! class_exists( 'BWFCRM_Contact' ) ) {
			/** Works with pro version only */
			return;
		}

		foreach ( $automation_data as $data ) {
			self::maybe_remove_aid_from_contact_fields( $data['cid'], $data['aid'] );
		}
	}

	/**
	 * Get automation active and completed count then remove from contact fields
	 */
	public static function maybe_remove_aid_from_contact_fields( $cid, $aid ) {
		if ( ! class_exists( 'BWFCRM_Contact' ) ) {
			/** Works with pro version only */
			return;
		}

		/** Get active automation contact count */
		$active = BWFAN_Model_Automation_Contact::get_automation_count_by_cid( $cid, $aid );

		/** if  active count is 0 then remove automation id from automation completed field */
		if ( 0 === absint( $active ) ) {
			self::remove_aid_from_contact_field( $cid, $aid, 'automation-active' );
		}

		/**Get completed automation contact count */
		$completed = BWFAN_Model_Automation_Complete_Contact::get_automation_count_by_cid( $cid, $aid );

		/** if  active and completed count is 0 then remove automation id from automation entered field */
		$is_entered = absint( $active ) + absint( $completed );
		if ( 0 === $is_entered ) {
			self::remove_aid_from_contact_field( $cid, $aid, 'automation-entered' );
		}

		/** if  completed count is 0 then remove automation id from automation completed field */
		if ( 0 === absint( $completed ) ) {
			self::remove_aid_from_contact_field( $cid, $aid, 'automation-completed' );
		}
	}

	/**
	 * Remove automation id from contact fields
	 */
	public static function remove_aid_from_contact_field( $cid, $removed_aid, $field_slg ) {
		$contact = new BWFCRM_Contact( $cid );

		if ( ! $contact->is_contact_exists() ) {
			return;
		}

		$automations    = $contact->get_field_by_slug( $field_slg );
		$automation_ids = json_decode( $automations, true );
		$automation_ids = ( ! empty( $automation_ids ) && is_array( $automation_ids ) ) ? $automation_ids : array();

		if ( empty( $automation_ids ) ) {
			return;
		}
		$aid = (string) $removed_aid;
		/**remove automation from field */
		$aid_index = array_search( $aid, $automation_ids );
		if ( false !== $aid_index ) {
			unset( $automation_ids[ $aid_index ] );
		}
		sort( $automation_ids );
		$automation_ids = wp_json_encode( $automation_ids, true );
		$contact->set_field_by_slug( $field_slg, $automation_ids );
		$contact->save_fields();

	}

	public static function bwfan_bulk_action( $option_key, $type ) {
		$option_key = "bwfan_bulk_action_{$option_key}";

		$ids = get_option( $option_key, [] );

		if ( empty( $ids ) ) {
			delete_option( $option_key );
			bwf_unschedule_actions( "bwfan_bulk_action", [ 'key' => $option_key, 'type' => $type ] );

			return;
		}

		$updated_ids = $ids;
		$start_time  = time();
		$batch_size  = 20;
		while ( ( time() - $start_time ) < 15 ) {

			/** Get first 20 ids */
			$ids          = ( count( $ids ) > $batch_size ) ? array_slice( $ids, 0, $batch_size ) : $ids;
			$selected_ids = implode( ', ', $ids );

			global $wpdb;

			$e_time = '';
			$status = 0;
			switch ( true ) {
				case ( 'tag' === $type || 'list' === $type || 'audience' === $type ):
					$query = "DELETE FROM {$wpdb->prefix}bwfan_terms WHERE `ID` IN ($selected_ids)";
					$wpdb->query( $query );
					break;
				case ( 'automation_v1' === $type || 'automation_v2' === $type ):
					BWFAN_Core()->automations->delete_automation( $ids );
					BWFAN_Core()->automations->delete_automationmeta( $ids );
					if ( 'automation_v2' === $type ) {
						self::delete_automation_v2_migrations( $ids );
					}
					if ( 'automation_v1' === $type ) {
						self::delete_automation_v1_migrations( $ids );
					}
					break;
				case ( 'broadcast' === $type ):
					$query = "DELETE FROM {$wpdb->prefix}bwfan_broadcast WHERE `id` IN ($selected_ids)";
					$wpdb->query( $query );
					/** Remove parent from child broadcasts */
					if ( bwfan_is_autonami_pro_active() && method_exists( 'BWFAN_Model_Broadcast', 'remove_parent_from_child' ) ) {
						BWFAN_Model_Broadcast::remove_parent_from_child( $ids );
					}
					break;
				case ( 'template' === $type ):
					BWFAN_Model_Templates::bwf_delete_template( $ids );
					break;
				case ( 'form' === $type ):
					$query = "DELETE FROM {$wpdb->prefix}bwfan_form_feeds WHERE `ID` IN ($selected_ids)";
					$wpdb->query( $query );
					break;
				case ( 'cart' === $type ):
					$query = "DELETE FROM {$wpdb->prefix}bwfan_abandonedcarts WHERE `ID` IN ($selected_ids)";
					$wpdb->query( $query );
					break;
				case ( 'recovered-cart' === $type ):
					$query = "DELETE FROM {$wpdb->prefix}postmeta WHERE (`meta_key` = '_bwfan_recovered_ab_id' OR `meta_key` = '_bwfan_ab_cart_recovered_a_id') AND `post_id` IN ($selected_ids)";
					$wpdb->query( $query );
					break;
			}

			/** Remove id from data after deleting */
			$updated_ids = array_diff( $updated_ids, $ids );
			if ( empty( $updated_ids ) ) {
				delete_option( $option_key );

				return;
			}
			sort( $updated_ids );
			update_option( $option_key, $updated_ids, false );

			/** Assign updated ids */
			$ids = $updated_ids;
		}

		return $ids;
	}

	public static function delete_automation_v2_migrations( $aids ) {
		BWFAN_Model_Automation_Step::delete_steps_by_aid( $aids );
		BWFAN_Model_Automation_Contact::delete_automation_contact_by_aid( $aids );
		BWFAN_Model_Automation_Complete_Contact::delete_automation_contact_by_aid( $aids );
		BWFAN_Model_Automation_Contact_Trail::delete_automation_trail_by_id( $aids );
	}

	public static function delete_automation_v1_migrations( $aids ) {
		BWFAN_Core()->tasks->delete_tasks( array(), $aids );
		BWFAN_Core()->logs->delete_logs( array(), $aids );
		BWFAN_Core()->automations->set_automation_id( $aids );
	}

	/**
	 * @return void
	 */
	public static function delete_expired_dynamic_coupons() {
		global $wpdb;
		$coupons = $wpdb->get_results( $wpdb->prepare( "
                                                SELECT m1.post_id as id
                                                FROM {$wpdb->prefix}postmeta as m1
                                                LEFT JOIN {$wpdb->prefix}postmeta as m2
                                                ON m1.post_id = m2.post_id
                                                WHERE m1.meta_key = %s
                                                AND m1.meta_value = %d
                                                AND m2.meta_key = %s
                                                AND TIMESTAMPDIFF(MINUTE,m2.meta_value,UTC_TIMESTAMP) > %d
                                                LIMIT %d
                                                ", '_is_bwfan_coupon', 1, 'expiry_date', 0, 20 ) );

		if ( empty( $coupons ) ) {
			bwf_unschedule_actions( 'bwfan_delete_expired_coupons' );

			return;
		}

		foreach ( $coupons as $coupon ) {
			wp_delete_post( $coupon->id, true );
		}
	}

	public static function append_extra_url_arguments( $url, $url_utm_args = [] ) {
		$utm_campaign = filter_input( INPUT_GET, 'utm_campaign' );
		if ( ! is_null( $utm_campaign ) && ! empty( $utm_campaign ) ) {
			$url_utm_args['utm_campaign'] = $utm_campaign;
		}

		$utm_term = filter_input( INPUT_GET, 'utm_term' );
		if ( ! is_null( $utm_term ) && ! empty( $utm_term ) ) {
			$url_utm_args['utm_term'] = $utm_term;
		}

		$utm_source = filter_input( INPUT_GET, 'utm_source' );
		if ( ! is_null( $utm_source ) && ! empty( $utm_source ) ) {
			$url_utm_args['utm_source'] = $utm_source;
		}

		$utm_content = filter_input( INPUT_GET, 'utm_content' );
		if ( ! is_null( $utm_content ) && ! empty( $utm_content ) ) {
			$url_utm_args['utm_content'] = $utm_content;
		}

		$utm_medium = filter_input( INPUT_GET, 'utm_medium' );
		if ( ! is_null( $utm_medium ) && ! empty( $utm_medium ) ) {
			$url_utm_args['utm_medium'] = $utm_medium;
		}

		$url = add_query_arg( $url_utm_args, $url );

		return $url;
	}

	public static function get_contact_columns() {
		$columns = get_user_meta( get_current_user_id(), '_bwfan_contact_columns_v2', true );
		if ( is_array( $columns ) ) {
			return $columns;
		}
		$wc_columns      = [];
		$default_columns = [
			[
				'country'    => 'Country/Region',
				'label'      => 'Country/Region',
				'groupKey'   => 'geography',
				'groupLabel' => 'Geography',
			],
			[
				'state'      => 'State',
				'label'      => 'State',
				'groupKey'   => 'geography',
				'groupLabel' => 'Geography',
			],
			[
				'tags'       => 'Tags',
				'label'      => 'Tags',
				'groupKey'   => 'segments',
				'groupLabel' => 'Segments',
			],
			[
				'lists'      => 'Lists',
				'label'      => 'Lists',
				'groupKey'   => 'segments',
				'groupLabel' => 'Segments',
			]
		];

		if ( class_exists( 'WooCommerce' ) ) {
			$wc_columns = [
				[
					'total_order_count' => 'Total Orders Count',
					'label'             => 'Total Orders Count',
					'groupKey'          => 'woocommerce',
					'groupLabel'        => 'WooCommerce',
				],
				[
					'total_order_value' => 'Total Revenue',
					'label'             => 'Total Revenue',
					'groupKey'          => 'woocommerce',
					'groupLabel'        => 'WooCommerce',
				],
				[
					'l_order_date' => 'Last Order Date',
					'label'        => 'Last Order Date',
					'groupKey'     => 'woocommerce',
					'groupLabel'   => 'WooCommerce',
				],
			];
		}

		return array_merge( $default_columns, $wc_columns );
	}

	/**
	 * Check if contact row found in the unsubscribe table and remove them.
	 *
	 * @param $contact
	 *
	 * @return bool
	 */
	public static function maybe_delete_unsubscribe_rows( $contact ) {
		if ( ! $contact instanceof WooFunnels_Contact ) {
			return false;
		}

		$email      = $contact->get_email();
		$contact_no = $contact->get_contact_no();
		$data       = array(
			'recipient' => array( $email, $contact_no ),
		);

		$rows = BWFAN_Model_Message_Unsubscribe::get_message_unsubscribe_row( $data, false );
		if ( empty( $rows ) ) {
			return false;
		}

		$p_keys = array_column( $rows, 'ID' );
		foreach ( $p_keys as $key ) {
			BWFAN_Model_Message_Unsubscribe::delete( $key );
		}

		return true;
	}

	public static function get_admin_analytics_cache_lifespan() {
		$time = apply_filters( 'bwfan_admin_analytics_cache_lifespan', ( 1 * HOUR_IN_SECONDS ) );

		return ( intval( $time ) > 0 ) ? intval( $time ) : ( 1 * HOUR_IN_SECONDS );
	}

	/**
	 * @param $diff_time
	 *
	 * @return string
	 */
	public static function get_difference_string( $diff_time ) {
		if ( empty( $diff_time ) || ! is_object( $diff_time ) ) {
			return '';
		}
		if ( $diff_time->y > 0 ) {
			return $diff_time->y . ' year ago';
		}
		if ( $diff_time->m > 0 ) {
			return $diff_time->m . ' months ago';
		}
		if ( $diff_time->d > 0 ) {
			return $diff_time->d . ' days ago';
		}
		if ( $diff_time->h > 0 ) {
			return $diff_time->h . ' hours ago';
		}
		if ( $diff_time->i > 0 ) {
			return $diff_time->i . ' minutes ago';
		}
		if ( $diff_time->m > 0 ) {
			return $diff_time->s . ' seconds ago';
		}

		return '';
	}

	public static function ping_woofunnels_worker() {
		$url  = rest_url( '/woofunnels/v1/worker' ) . '?' . time();
		$args = array(
			'method'    => 'GET',
			'body'      => array(),
			'timeout'   => 0.01,
			'sslverify' => false,
		);

		wp_remote_post( $url, $args );
	}

	public static function get_formatted_price_wc( $price, $raw = true, $currency = '' ) {
		if ( true === $raw ) {
			return $price;
		}

		$decimal_separator  = wc_get_price_decimal_separator();
		$thousand_separator = wc_get_price_thousand_separator();
		$decimals           = wc_get_price_decimals();

		if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $decimals > 0 ) {
			$price = wc_trim_zeros( $price );
		}

		$output = number_format( $price, $decimals, $decimal_separator, $thousand_separator );
		if ( ! empty( $currency ) ) {
			$position = get_option( 'woocommerce_currency_pos' );
			$prefix   = '';
			$suffix   = '';

			switch ( $position ) {
				case 'left_space':
					$prefix = $currency . ' ';
					break;
				case 'left':
					$prefix = $currency;
					break;
				case 'right_space':
					$suffix = ' ' . $currency;
					break;
				case 'right':
					$suffix = $currency;
					break;
			}

			$output = $prefix . $output . $suffix;
		}

		return $output;
	}

	public static function get_formatting_for_wc_price( $attr, $order ) {
		$formatting      = isset( $attr['format'] ) && ! empty( $attr['format'] ) ? $attr['format'] : 'raw';
		$raw             = ( 'raw' === strval( $formatting ) ) ? true : false;
		$currency_format = '';
		if ( 'formatted-currency' === strval( $formatting ) ) {
			$currency        = ! empty( $order ) && ! is_null( $order->get_currency() ) ? $order->get_currency() : get_option( 'woocommerce_currency' );
			$symbols         = get_woocommerce_currency_symbols();
			$currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';
			$currency_format = html_entity_decode( $currency_symbol );
		}

		return array( 'raw' => $raw, 'currency' => $currency_format );
	}

	/**
	 * Advanced logging to check wp-json endpoint for event enabled or not
	 *
	 * @return bool
	 */
	public static function event_cb_advanced_log_enabled() {
		if ( defined( 'BWFAN_ALLOW_EVENT_ENDPOINT_LOGS' ) && true === BWFAN_ALLOW_EVENT_ENDPOINT_LOGS ) {
			return true;
		}
		if ( true === apply_filters( 'bwfan_allow_event_endpoint_logs', false ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Advanced logging for developers to check wp-json endpoint working or not
	 *
	 * @param $log
	 *
	 * @return void
	 */
	public static function event_advanced_logs( $log ) {
		if ( empty( $log ) || false === self::event_cb_advanced_log_enabled() ) {
			return;
		}
		$log = array(
			't' => microtime( true ),
			'm' => $log,
		);
		BWFAN_Common::log_test_data( $log, 'event-endpoint-check', true );
	}

	/**
	 * Update Contact WP User ID to 0 after a WP user is deleted
	 *
	 * @param $user_id
	 *
	 * @return void
	 */
	public static function update_contact_wp_id( $user_id ) {
		$bwf_contact = new WooFunnels_Contact( $user_id );
		if ( 0 === intval( $bwf_contact->get_id() ) ) {
			return;
		}

		$bwf_contact->set_wpid( 0 );
		$bwf_contact->save();
	}

	/**
	 * Get midnight time of tomorrow in store timezone
	 *
	 * @return int
	 */
	public static function get_midnight_store_time() {
		$timezone = new DateTimeZone( wp_timezone_string() );
		$date     = new DateTime();
		$date->modify( '+1 days' );
		$date->setTimezone( $timezone );
		$date->setTime( 0, 0, 0 );

		return $date->getTimestamp();
	}

	/**
	 * Fetch updated data
	 *
	 * @param $meta
	 *
	 * @return mixed
	 */
	public static function fetch_updated_data( $meta ) {
		if ( ! bwfan_is_autonami_pro_active() || ( ! isset( $meta['tags'] ) && ! isset( $meta['list'] ) ) ) {
			return $meta;
		}

		if ( isset( $meta['tags'] ) ) {
			$meta['tags'] = self::get_updated_tags_and_list( $meta['tags'] );
		}
		if ( isset( $meta['list'] ) ) {
			$meta['list'] = self::get_updated_tags_and_list( $meta['list'] );
		}

		return $meta;
	}

	/**
	 * Modify automation steps data, fetch latest names in admin
	 *
	 * @param $step_type
	 * @param $data
	 *
	 * @return mixed
	 */
	public static function modify_step_admin_data( $step_type, $data ) {
		if ( ! bwfan_is_autonami_pro_active() ) {
			return $data;
		}
		if ( defined( 'BWFAN_Disable_Automation_Fetch_Latest_Names' ) && 1 === intval( BWFAN_Disable_Automation_Fetch_Latest_Names ) ) {
			return $data;
		}

		if ( 'conditional' === $step_type ) {
			return BWFAN_Common::get_updated_tags_and_list_conditions( $data );
		}

		if ( 'benchmark' === $step_type ) {
			if ( isset( $data->list ) ) {
				$data->list = BWFAN_Common::get_updated_tags_and_list( (array) $data->list );
			}
			if ( isset( $data->tags ) ) {
				$data->tags = BWFAN_Common::get_updated_tags_and_list( (array) $data->tags );
			}

			return $data;
		}

		if ( 'action' === $step_type ) {
			if ( isset( $data->tags ) ) {
				$data->tags = BWFAN_Common::get_updated_tags_and_list( (array) $data->tags );
			}
			if ( isset( $data->list_id ) ) {
				$data->list_id = BWFAN_Common::get_updated_tags_and_list( (array) $data->list_id );
			}
		}

		return $data;
	}

	/**
	 * Get updated tags & lists names
	 *
	 * @param $terms
	 * @param $id_key
	 * @param $name_key
	 *
	 * @return array|array[]|mixed|object[]
	 */
	public static function get_updated_tags_and_list( $terms, $id_key = 'id', $name_key = 'name' ) {
		if ( ! bwfan_is_autonami_pro_active() ) {
			return $terms;
		}

		return array_map( function ( $term ) use ( $id_key, $name_key ) {
			$obj = false;
			/** If data is in object then convert into array */
			if ( is_object( $term ) ) {
				$term = (array) $term;
				$obj  = true;
			}

			$data = BWFAN_Model_Terms::get( $term[ $id_key ] );

			$term[ $name_key ] = is_array( $data ) && ! empty( $data['name'] ) ? $data['name'] : $term[ $name_key ];

			return ( true === $obj ) ? (object) $term : $term;
		}, $terms );
	}

	/**
	 * Get updated tags & lists names for conditional step
	 *
	 * @param $rules
	 *
	 * @return mixed
	 */
	public static function get_updated_tags_and_list_conditions( $rules ) {
		if ( empty( $rules ) ) {
			return $rules;
		}

		foreach ( $rules as $or_key => $or_rule ) {
			/** OR block */
			foreach ( $or_rule as $and_key => $rule ) {
				/** AND block */
				if ( ! isset( $rule->filter ) || ! in_array( $rule->filter, [ 'contact_tags', 'contact_lists' ] ) ) {
					continue;
				}

				$rules[ $or_key ][ $and_key ]->data = self::get_updated_tags_and_list( $rule->data, 'key', 'label' );
			}
		}

		return $rules;
	}

	/**
	 * Get contact marketing status
	 *
	 * @param $contact
	 *
	 * @return array
	 */
	public static function get_contact_status( $contact ) {
		if ( ! $contact instanceof WooFunnels_Contact ) {
			return [];
		}

		$data = array(
			'recipient' => array( $contact->get_email(), $contact->get_contact_no() ),
		);

		$unsubscribed_rows = BWFAN_Model_Message_Unsubscribe::get_message_unsubscribe_row( $data, false );
		if ( empty( $unsubscribed_rows ) || 0 === count( $unsubscribed_rows ) ) {
			return [
				'status'       => $contact->get_status(),
				'email_status' => 1,
				'sms_status'   => 1
			];
		}

		$unsubscribed_rows = array_column( $unsubscribed_rows, 'recipient' );
		$email_status      = in_array( $contact->get_email(), $unsubscribed_rows, true ) ? 0 : 1;
		$sms_status        = in_array( $contact->get_contact_no(), $unsubscribed_rows, true ) ? 0 : 1;

		return [
			'status'       => $contact->get_status(),
			'email_status' => $email_status,
			'sms_status'   => $sms_status
		];
	}

	/**
	 * Validate create new order settings
	 *
	 * @param $data
	 *
	 * @return bool
	 */
	public static function validate_create_order_event_setting( $data ) {
		/** Any product case */
		if ( ! isset( $data['event_meta'] ) || ! isset( $data['event_meta']['order-contains'] ) || 'any' === $data['event_meta']['order-contains'] ) {
			return true;
		}

		$order = wc_get_order( intval( $data['order_id'] ) );
		if ( ! $order instanceof WC_Order ) {
			return false;
		}

		/** Specific product case */
		$get_selected_product = isset( $data['event_meta']['products'] ) ? $data['event_meta']['products'] : [];
		$ordered_products     = array();
		foreach ( $order->get_items() as $item ) {
			$ordered_products[] = $item->get_product_id();

			/** In case variation */
			if ( $item->get_variation_id() ) {
				$ordered_products[] = $item->get_variation_id();
			}
		}

		/** Selected product and ordered products */
		$product_selected = array_column( $get_selected_product, 'id' );
		$ordered_products = array_unique( $ordered_products );
		sort( $ordered_products );

		return count( array_intersect( $product_selected, $ordered_products ) ) > 0;
	}

	/**
	 * Update Automation meta data
	 * @return void
	 */
	public static function update_meta_automations_v2() {
		$key       = 'bwfan_automation_v2_meta_normalize';
		$offset_id = get_option( $key );
		if ( intval( $offset_id ) < 1 ) {
			delete_option( $key );
			bwf_unschedule_actions( 'bwfan_update_meta_automations_v2' );

			return;
		}

		global $wpdb;
		$query = "SELECT am.meta_value, a.ID FROM `{$wpdb->prefix}bwfan_automationmeta` AS am JOIN `{$wpdb->prefix}bwfan_automations` AS a ON am.bwfan_automation_id = a.ID WHERE a.ID >= %d AND am.meta_key = %s AND a.v = %d LIMIT 20";
		$rows  = $wpdb->get_results( $wpdb->prepare( $query, $offset_id, 'steps', 2 ), ARRAY_A );
		if ( empty( $rows ) ) {
			delete_option( $key );
			bwf_unschedule_actions( 'bwfan_update_meta_automations_v2' );

			return;
		}

		$start_time = time();
		foreach ( $rows as $single ) {
			if ( ( time() - $start_time ) > 10 ) {
				return;
			}

			if ( ! isset( $single['meta_value'] ) ) {
				continue;
			}
			$meta_value = maybe_unserialize( $single['meta_value'] );

			if ( ! is_array( $meta_value ) ) {
				continue;
			}

			$meta_value = array_map( function ( $data ) {
				if ( isset( $data['data'] ) && 'yesNoNode' !== $data['type'] ) {
					$data['data'] = [];
				}

				return $data;
			}, $meta_value );

			BWFAN_Model_Automationmeta::update_automation_meta_values( $single['ID'], [ 'steps' => maybe_serialize( $meta_value ) ] );

			/** updating the option key for next call */
			update_option( 'bwfan_automation_v2_meta_normalize', ( intval( $single['ID'] ) + 1 ) );
		}
	}

	/**
	 * deleting logs older than 1 month
	 * scheduler runs every month
	 *
	 * @return void
	 */
	public static function delete_bwfan_logs() {
		$wp_dir           = wp_upload_dir();
		$autonami_log_dir = $wp_dir['basedir'] . '/funnelkit/autonami-logs/';
		$files            = @scandir( $autonami_log_dir ); // @codingStandardsIgnoreLine.

		if ( empty( $files ) ) {
			return;
		}

		$expire_time = strtotime( "-30 days" );
		foreach ( $files as $file ) {
			if ( in_array( $file, array( '.', '..' ), true ) ) {
				continue;
			}

			if ( is_dir( $file ) ) {
				continue;
			}

			if ( filemtime( $autonami_log_dir . $file ) > $expire_time ) {
				continue;
			}

			@unlink( trailingslashit( $autonami_log_dir ) . $file ); // @codingStandardsIgnoreLine.
		}
	}

	/**
	 * get desired store time
	 *
	 * @param $hours
	 * @param $mins
	 * @param $sec
	 *
	 * @return int
	 */
	public static function get_store_time( $hours = 0, $mins = 0, $sec = 0 ) {
		$timezone = new DateTimeZone( wp_timezone_string() );
		$date     = new DateTime();
		$date->modify( '+1 days' );
		$date->setTimezone( $timezone );
		$date->setTime( $hours, $mins, $sec );

		return $date->getTimestamp();
	}

	/**
	 * Get database query cached result
	 *
	 * @param $query
	 *
	 * @return false|mixed
	 */
	public static function get_db_cache_data( $query = '' ) {
		if ( empty( $query ) ) {
			return false;
		}
		$query = md5( $query );
		if ( ! is_array( self::$cached_db_data ) || ! isset( self::$cached_db_data[ $query ] ) ) {
			return false;
		}

		return self::$cached_db_data[ $query ];
	}

	/**
	 * Set database query cached result
	 *
	 * @param $query
	 * @param $result
	 *
	 * @return bool
	 */
	public static function set_db_cache_data( $query = '', $result = '' ) {
		if ( empty( $query ) ) {
			return false;
		}
		$query = md5( $query );

		self::$cached_db_data[ $query ] = $result;

		return true;
	}

	/**
	 * Unset database query cached result
	 *
	 * @param $query
	 *
	 * @return false|void
	 */
	public static function unset_db_cache_key( $query = '' ) {
		if ( empty( $query ) ) {
			return false;
		}
		$query = md5( $query );

		if ( isset( self::$cached_db_data[ $query ] ) ) {
			unset( self::$cached_db_data[ $query ] );
		}
	}

	/**
	 * Update automation contact's step trail status
	 *
	 * @return void
	 */
	public static function bwfan_update_contact_trail() {
		global $wpdb;
		$start_time = time();
		do {
			$query = "SELECT ct.ID FROM `{$wpdb->prefix}bwfan_automation_contact_trail` AS ct JOIN `{$wpdb->prefix}bwfan_automation_complete_contact` AS cc ON ct.tid = cc.trail WHERE ct.status = 2 LIMIT 0,20";
			$ids   = $wpdb->get_col( $query );
			if ( empty( $ids ) ) {
				bwf_unschedule_actions( 'bwfan_update_contact_trail' );

				break;
			}
			$ids   = implode( ',', $ids );
			$query = "UPDATE `{$wpdb->prefix}bwfan_automation_contact_trail` SET `status` = 1, `data` = NULL WHERE ID IN ($ids);";
			$wpdb->query( $query );
		} while ( ( time() - $start_time ) < 10 );
	}

	/**
	 * Get product price with tax
	 *
	 * @param $product
	 *
	 * @return float|mixed|string|null
	 */
	public static function get_prices_with_tax( $product ) {
		if ( ! wc_tax_enabled() ) {
			return $product instanceof WC_Product ? $product->get_price() : 0;
		}
		if ( ! $product instanceof WC_Product ) {
			return 0;
		}

		$tax_display_cart = get_option( 'woocommerce_tax_display_cart', '' );
		if ( 'incl' === $tax_display_cart ) {
			return wc_get_price_including_tax( $product );
		}
		if ( 'excl' === $tax_display_cart ) {
			return wc_get_price_excluding_tax( $product );
		}

		return 0;
	}

	/**
	 * Set headers to prevent caching
	 *
	 * @return void
	 */
	public static function nocache_headers() {
		if ( headers_sent() ) {
			return;
		}

		header( 'Cache-Control: no-cache, no-store, must-revalidate, max-age=0' );
		header( 'Pragma: no-cache' );
		header( 'Expires: Wed, 11 Jan 1984 05:00:00 GMT' );
		header( 'Last-Modified: false' );
	}

	/**
	 * Check if contact is in automation for a particular order related event
	 *
	 * @param $aid
	 * @param $cid
	 * @param $order_id
	 * @param $item_id
	 * @param $event
	 *
	 * @return true|void
	 */
	public static function is_contact_in_automation( $aid, $cid, $order_id, $item_id = 0, $event = 'wc_new_order' ) {
		$active_contact = BWFAN_Model_Automation_Contact::is_contact_with_same_order( $aid, $cid, $order_id, $item_id, $event );
		if ( ! empty( $active_contact ) ) {
			return true;
		}

		return BWFAN_Model_Automation_Complete_Contact::is_contact_with_same_order( $aid, $cid, $order_id, $item_id, $event );
	}

	/**
	 * @param $order_id
	 * @param $from
	 * @param $to
	 *
	 * @return void
	 */
	public static function order_status_change( $order_id, $from, $to ) {
		/** Avoid duplicate run on a single call */
		$arr = [ $order_id, $from, $to ];
		if ( isset( self::$order_status_change[ md5( json_encode( $arr ) ) ] ) ) {
			BWFAN_Common::log_test_data( $arr, 'duplicate-order-status-change', true );

			return;
		}

		$data = [
			'order_id'     => $order_id,
			'from'         => $from,
			'to'           => $to,
			'_nonce'       => WooFunnels_DB_Updater::create_nonce( 'wc_order_status_changed' ),
			'nonce_action' => 'wc_order_status_changed'
		];

		self::$order_status_change[ md5( json_encode( $arr ) ) ] = 1;

		$url  = rest_url( '/autonami/v2/order_status_change' );
		$args = bwf_get_remote_rest_args( $data );
		self::event_advanced_logs( 'Sending data for Order status change json endpoint' );
		self::event_advanced_logs( 'URL: ' . $url );
		self::event_advanced_logs( $args );

		$response = wp_remote_post( $url, $args );

		self::event_advanced_logs( "Order status change json endpoint's response" );
		self::event_advanced_logs( $response );
	}

	/**
	 * Order status change endpoint callback
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return void
	 */
	public static function capture_order_status_change( WP_REST_Request $request ) {
		$post_parameters = $request->get_body_params();
		self::event_advanced_logs( "Order status change endpoint data received" );
		self::event_advanced_logs( $post_parameters );
		$order = wc_get_order( $post_parameters['order_id'] );
		if ( ! $order instanceof WC_Order ) {
			return;
		}

		do_action( 'bwfan_wc_order_status_changed', $order, $post_parameters['from'], $post_parameters['to'] );
	}

	/**
	 * Set dynamic string prop
	 *
	 * @return void
	 */
	public static function set_dynamic_string() {
		self::$dynamic_str = self::get_dynamic_string( 10 );
	}

	/**
	 * Get email from order
	 *
	 * @param $order_id
	 * @param $order
	 *
	 * @return string|null
	 */
	public static function get_email_from_order( $order_id = '', $order = null ) {
		if ( empty( $order_id ) && ! $order instanceof WC_Order ) {
			return '';
		}

		if ( ! $order instanceof WC_Order ) {
			$order = wc_get_order( $order_id );
		}

		return $order instanceof WC_Order ? $order->get_billing_email() : '';
	}

	/**
	 * Get phone from order
	 *
	 * @param $order_id
	 * @param $order
	 *
	 * @return mixed|string|null
	 */
	public static function get_phone_from_order( $order_id = '', $order = null ) {
		if ( empty( $order_id ) && ! $order instanceof WC_Order ) {
			return '';
		}

		if ( ! $order instanceof WC_Order ) {
			$order = wc_get_order( $order_id );
		}

		if ( ! $order instanceof WC_Order ) {
			return '';
		}

		$phone = BWFAN_Woocommerce_Compatibility::get_order_data( $order, '_billing_phone' );
		if ( empty( $phone ) ) {
			$phone = BWFAN_Woocommerce_Compatibility::get_order_data( $order, '_shipping_phone' );
		}

		if ( empty( $phone ) || ! class_exists( 'BWFAN_Phone_Numbers' ) ) {
			return $phone;
		}

		$country = BWFAN_Woocommerce_Compatibility::get_order_data( $order, '_billing_country' );
		if ( empty( $country ) ) {
			$country = BWFAN_Woocommerce_Compatibility::get_order_data( $order, '_shipping_country' );
		}

		if ( ! empty( $country ) ) {
			$phone = BWFAN_Phone_Numbers::add_country_code( $phone, $country );
		}

		return $phone;
	}

	/**
	 * Get WP user id from order
	 *
	 * @param $order_id
	 * @param $order
	 *
	 * @return int|string
	 */
	public static function get_wp_user_id_from_order( $order_id = '', $order = null ) {
		if ( empty( $order_id ) && ! $order instanceof WC_Order ) {
			return '';
		}

		if ( ! $order instanceof WC_Order ) {
			$order = wc_get_order( $order_id );
		}

		return $order instanceof WC_Order ? $order->get_customer_id() : '';
	}

	/**
	 * Save meta in order
	 *
	 * @param $order_id
	 * @param $meta_key
	 * @param $meta_value
	 *
	 * @return void
	 */
	public static function save_order_meta( $order_id, $meta_key, $meta_value ) {
		global $wpdb;

		$hpos_enabled = BWF_WC_Compatibility::is_hpos_enabled();
		if ( $hpos_enabled ) {
			$table = "{$wpdb->prefix}wc_orders_meta";
			$query = " SELECT `id`,`meta_value` FROM {$table} WHERE `order_id` = %d AND `meta_key` = %s  ";
		} else {
			$table = "{$wpdb->prefix}postmeta";
			$query = " SELECT `meta_id`,`meta_value` FROM {$table} WHERE `post_id` = %d AND `meta_key` = %s  ";
		}
		$row = $wpdb->get_row( $wpdb->prepare( $query, $order_id, $meta_key ), ARRAY_A );

		/** If no data found */
		if ( empty( $row ) ) {
			$data = [
				'meta_key'   => $meta_key,
				'meta_value' => $meta_value,
			];

			if ( $hpos_enabled ) {
				$data['order_id'] = $order_id;
			} else {
				$data['post_id'] = $order_id;
			}
			$wpdb->insert( $table, $data );

			return;
		}

		/** If old value is equal to new value */
		if ( $meta_value === $row['meta_value'] ) {
			return;
		}

		/** update meta value */
		$column = ( $hpos_enabled ) ? 'id' : 'meta_id';

		$wpdb->update( $table, [ 'meta_value' => $meta_value ], [ $column => $row[ $column ] ] );
	}

	/**
	 * Get FK Automation links
	 *
	 * @return array
	 */
	public static function get_fk_site_links() {
		$default_args = [
			'utm_source'   => 'WordPress',
			'utm_campaign' => 'Lite+Plugin'
		];

		return [
			'upgrade'       => apply_filters( 'bwfan_upgrade_link_append_utm_args', add_query_arg( $default_args, 'https://funnelkit.com/autonami-lite-upgrade/' ) ),
			'offer'         => apply_filters( 'bwfan_offer_link_append_utm_args', add_query_arg( $default_args, 'https://funnelkit.com/exclusive-offer/' ) ),
			'autonami'      => apply_filters( 'bwfan_fka_sales_link_append_utm_args', add_query_arg( $default_args, 'https://funnelkit.com/wordpress-marketing-automation-autonami/' ) ),
			'support'       => apply_filters( 'bwfan_support_link_append_utm_args', add_query_arg( $default_args, 'https://funnelkit.com/support/' ) ),
			'nxtgenbuilder' => apply_filters( 'bwfan_fka_nextgen_link_append_utm_args', add_query_arg( $default_args, 'https://funnelkit.com/autonami-next-generation-automation-builder/' ) ),
			'migratefromv1' => apply_filters( 'bwfan_fka_migrate_link_append_utm_args', add_query_arg( $default_args, 'https://funnelkit.com/docs/autonami-2/automations/migrate-from-older-version' ) ),
		];
	}

	/**
	 * Get unsubscribe link
	 *
	 * @param $data
	 *
	 * @return string
	 */
	public static function get_unsubscribe_link( $data ) {
		if ( empty( self::$unsubscribe_page_link ) ) {
			$global_settings = self::get_global_settings();
			if ( ! isset( $global_settings['bwfan_unsubscribe_page'] ) || empty( $global_settings['bwfan_unsubscribe_page'] ) ) {
				return '';
			}
			$page = absint( $global_settings['bwfan_unsubscribe_page'] );

			self::$unsubscribe_page_link = get_permalink( $page );
		}

		if ( empty( $data ) || ! is_array( $data ) ) {
			return self::$unsubscribe_page_link;
		}

		if ( empty( $data['uid'] ) && isset( $data['contact_id'] ) ) {
			$contact = new WooFunnels_Contact( '', '', '', $data['contact_id'] );
			$uid     = ( $contact->get_id() > 0 ) ? $contact->get_uid() : '';

			if ( ! empty( $uid ) ) {
				$data['uid'] = $uid;
			}
			unset( $data['contact_id'] );
		}

		$data['bwfan-action'] = 'unsubscribe';

		return add_query_arg( $data, self::$unsubscribe_page_link );
	}

	/**
	 * Checking wc new order goal in automation
	 *
	 * @param $aid
	 *
	 * @return bool
	 */
	public static function is_wc_order_goal( $aid ) {
		global $wpdb;
		$query = $wpdb->prepare( "SELECT `ID` FROM {$wpdb->prefix}bwfan_automations WHERE ID = %d AND `benchmark` LIKE %s", $aid, '%"wc_new_order"%' );

		return ( ! empty( $wpdb->get_var( $query ) ) );
	}

	/**
	 * Remove duplicate actions if found
	 *
	 * @return void
	 */
	public static function remove_duplicate_actions() {
		global $wpdb;

		$hooks = [
			'bwfan_delete_logs',
			'bwfan_delete_expired_autonami_coupons',
			'bwfan_mark_abandoned_lost_cart',
			'bwfan_run_midnight_cron',
			'bwfan_run_midnight_connectors_sync',
			'bwfan_run_queue',
			'bwfan_run_queue_v2',
			'bwfcrm_broadcast_run_queue',
			'bwfan_check_abandoned_carts',
			'bwfan_5_minute_worker'
		];

		$placeholders       = array_fill( 0, count( $hooks ), '%s' );
		$placeholders_hooks = implode( ', ', $placeholders );

		$query = "SELECT `hook`, count(`id`) as `count` FROM `{$wpdb->prefix}bwf_actions` WHERE `hook` IN ($placeholders_hooks) AND `status` = 0 GROUP BY `hook`;";
		$query = $wpdb->prepare( $query, $hooks ); // WPCS: unprepared SQL OK

		$result = $wpdb->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( empty( $result ) ) {
			return;
		}
		$result = array_filter( $result, function ( $row ) {
			return $row['count'] > 1;
		} );
		if ( empty( $result ) ) {
			return;
		}

		$hooks = array_column( $result, 'hook' );
		sort( $hooks );

		$placeholders       = array_fill( 0, count( $hooks ), '%s' );
		$placeholders_hooks = implode( ', ', $placeholders );

		$query = "DELETE FROM `{$wpdb->prefix}bwf_actions` WHERE `hook` IN ($placeholders_hooks) AND `status` = 0";
		$query = $wpdb->prepare( $query, $hooks ); // WPCS: unprepared SQL OK
		$wpdb->query( $query ); // WPCS: unprepared SQL OK

		if ( true === bwfan_is_autonami_pro_active() && in_array( 'bwfcrm_broadcast_run_queue', $hooks, true ) ) {
			/** Reschedule broadcast */
			bwf_schedule_recurring_action( time(), 60, 'bwfcrm_broadcast_run_queue', array(), 'bwfcrm' );
		}
	}
}
