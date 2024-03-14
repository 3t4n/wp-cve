<?php

/**
 * This class take care of ecommerce tracking setup
 * It renders necessary javascript code to fire events as well as creates dynamic data for the tracking
 * @author woofunnels.
 */
if ( ! class_exists( 'WFFN_Ecomm_Tracking_Optin' ) ) {
	#[AllowDynamicProperties]

  class WFFN_Ecomm_Tracking_Optin extends WFFN_Ecomm_Tracking_Common {
		private static $ins = null;

		public function __construct() {
			add_filter( 'wffn_localized_data', array( $this, 'lead_event_data' ) );
			add_action( 'wffn_optin_form_submit', array( $this, 'maybe_execute_lead_event_for_optin' ) );
			parent::__construct();
		}

		/**
		 * @return WFFN_Ecomm_Tracking_Optin|null
		 */
		public static function get_instance() {
			if ( self::$ins === null ) {
				self::$ins = new self();
			}

			return self::$ins;
		}


		public function should_render_view( $type ) {
			if ( $type === 'ga' ) {
				return $this->do_track_ga_view();
			} elseif ( $type === 'fb' ) {
				return $this->do_track_fb_view();
			} elseif ( $type === 'gad' ) {
				return $this->do_track_gad_view();
			} elseif ( $type === 'snapchat' ) {
				return $this->do_track_snapchat_view();
			} elseif ( $type === 'pint' ) {
				return $this->do_track_pint_view();
			} elseif ( $type === 'tiktok' ) {

				return $this->do_track_tiktok_view();
			}

			return false;
		}

		public function should_render_lead( $type ) {
			if ( $type === 'ga' ) {
				return $this->do_track_ga_lead();
			} elseif ( $type === 'gad' ) {
				return $this->do_track_gad_lead();
			}

			return false;

		}

		/**
		 * maybe render script to fire fb pixel view event
		 */
		public function do_track_snapchat_view() {
			if ( true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_snapchat_page_view_global' ) ) ) {
				return true;
			}

			$view_tracking = $this->admin_general_settings->get_option( 'is_snapchat_page_view_op' );
			if ( is_array( $view_tracking ) && count( $view_tracking ) > 0 && 'yes' === $view_tracking[0] ) {
				return true;
			}

			return false;

		}

		public function do_track_ga_view() {
			if ( true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_ga_page_view_global' ) ) ) {
				return true;
			}

			$ga_tracking = $this->admin_general_settings->get_option( 'is_ga_page_view_op' );
			if ( is_array( $ga_tracking ) && count( $ga_tracking ) > 0 && 'yes' === $ga_tracking[0] ) {
				return true;
			}

			return false;
		}

		public function do_track_ga_lead() {
			$ga_tracking = $this->admin_general_settings->get_option( 'is_ga_lead_op' );
			if ( is_array( $ga_tracking ) && count( $ga_tracking ) > 0 && 'yes' === $ga_tracking[0] ) {
				return true;
			}

			return false;
		}

		public function do_track_gad_lead() {
			$ga_tracking = $this->admin_general_settings->get_option( 'is_gad_lead_op' );
			if ( is_array( $ga_tracking ) && count( $ga_tracking ) > 0 && 'yes' === $ga_tracking[0] ) {
				return true;
			}

			return false;
		}

		/**
		 * maybe render script to fire fb pixel view event
		 */
		public function do_track_fb_view() {
			if ( true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_fb_page_view_global' ) ) ) {
				return true;
			}

			$fb_tracking = $this->admin_general_settings->get_option( 'is_fb_page_view_op' );
			if ( is_array( $fb_tracking ) && count( $fb_tracking ) > 0 && 'yes' === $fb_tracking[0] ) {
				return true;
			}

			return false;

		}

		/**
		 * maybe render script to fire pint pixel view event
		 */
		public function do_track_pint_view() {
			if ( true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_pint_page_view_global' ) ) ) {
				return true;
			}

			$view_tracking = $this->admin_general_settings->get_option( 'is_pint_page_view_op' );
			if ( is_array( $view_tracking ) && count( $view_tracking ) > 0 && 'yes' === $view_tracking[0] ) {
				return true;
			}

			return false;
		}

		/*
		 * maybe render script to fire fb pixel view event
		 */
		public function do_track_tiktok_view() {
			if ( true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_tiktok_page_view_global' ) ) ) {
				return true;
			}

			$fb_tracking = $this->admin_general_settings->get_option( 'is_tiktok_page_view_op' );
			if ( is_array( $fb_tracking ) && count( $fb_tracking ) > 0 && 'yes' === $fb_tracking[0] ) {
				return true;
			}

			return false;

		}

		public function should_render( $check_valid_session = false ) {

			if ( parent::should_render( $check_valid_session ) && ( WFOPP_Core()->optin_pages->is_wfop_page() || WFOPP_Core()->optin_ty_pages->is_wfoty_page() ) ) {
				return true;
			}

			return false;
		}

		/**
		 * maybe render script to fire fb pixel view event
		 */
		public function do_track_gad_view() {
			if ( true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_gad_page_view_global' ) ) ) {
				return true;
			}

			$view_tracking = $this->admin_general_settings->get_option( 'is_gad_page_view_op' );
			if ( is_array( $view_tracking ) && count( $view_tracking ) > 0 && 'yes' === $view_tracking[0] ) {
				return true;
			}

			return false;

		}

		public function get_custom_event_name() {
			if ( WFOPP_Core()->optin_pages->is_wfop_page() ) {
				return 'WooFunnels_Optin';
			} elseif ( WFOPP_Core()->optin_ty_pages->is_wfoty_page() ) {
				return 'WooFunnels_OptinConfirmation';
			}

		}

		public function lead_event_data( $localized ) {
			if ( WFOPP_Core()->optin_pages->is_wfop_page() ) {
				$localized['op_lead_tracking'] = array(
					'fb'   => array(
						'enable'    => BWF_Admin_General_Settings::get_instance()->get_option( 'is_fb_lead_op' ),
						'fb_pixels' => $this->is_fb_pixel(),
						'event_ID'  => "Lead" . "_" . time()
					),
					'ga'   => array(
						'enable' => BWF_Admin_General_Settings::get_instance()->get_option( 'is_ga_lead_op' ),
						'ids'    => $this->ga_code(),
					),
					'gad'  => array(
						'enable' => BWF_Admin_General_Settings::get_instance()->get_option( 'is_gad_lead_op' ),
						'ids'    => $this->gad_code(),
					),
					'pint' => array(
						'enable' => BWF_Admin_General_Settings::get_instance()->get_option( 'is_pint_lead_op' ),
						'pixels' => $this->is_pint_pixel(),
					)
				);
				$localized['op_should_render'] = apply_filters( 'wffn_allow_op_tracking_js', true );
			}

			return $localized;
		}

		public function maybe_execute_lead_event_for_optin() {
			if ( false === $this->is_conversion_api() ) {
				return false;
			}
			$get_all_fb_pixel  = $this->is_fb_pixel();
			$get_each_pixel_id = explode( ',', $get_all_fb_pixel );

			if ( is_array( $get_each_pixel_id ) && count( $get_each_pixel_id ) > 0 ) {

				foreach ( $get_each_pixel_id as $key => $pixel_id ) {


					$access_token = $this->get_conversion_api_access_token();
					$access_token = explode( ',', $access_token );

					if ( is_array( $access_token ) && count( $access_token ) > 0 ) {
						if ( isset( $access_token[ $key ] ) ) {

							BWF_Facebook_Sdk_Factory::setup( trim( $pixel_id ), trim( $access_token[ $key ] ) );
						}
					}

					$get_test      = $this->get_conversion_api_test_event_code();
					$get_test      = explode( ',', $get_test );
					$is_test_event = $this->admin_general_settings->get_option( 'is_fb_conv_enable_test' );
					if ( is_array( $is_test_event ) && count( $is_test_event ) > 0 && $is_test_event[0] === 'yes' && is_array( $get_test ) && count( $get_test ) > 0 ) {
						if ( isset( $get_test[ $key ] ) && ! empty( $get_test[ $key ] ) ) {
							BWF_Facebook_Sdk_Factory::set_test( trim( $get_test[ $key ] ) );
						}
					}

					BWF_Facebook_Sdk_Factory::set_partner( 'woofunnels' );
					$instance = BWF_Facebook_Sdk_Factory::create();
					if ( is_null( $instance ) ) {
						return null;
					}


					$instance->set_event_id( filter_input( INPUT_GET, 'lead_event_id' ) );
					$instance->set_user_data( $this->get_user_data( 'Lead' ) );
					$instance->set_event_source_url( $this->getRequestUri( true ) );
					$instance->set_event_data( 'Lead', [] );


					$response = $instance->execute();

					return $response;
				}
			}
		}

		public function get_custom_event_params() {
			$funnel = WFFN_Core()->data->get_session_funnel();
			if ( wffn_is_valid_funnel( $funnel ) ) {
				$params = [];
				if ( is_singular() ) {
					global $post;
					$params['page_title'] = $post->post_title;
					$params['post_id']    = $post->ID;

				}
				$params['funnel_id']    = $funnel->get_id();
				$params['funnel_title'] = $funnel->get_title();

				return wp_json_encode( $params );

			}

			return parent::get_custom_event_params();
		}


	}
}