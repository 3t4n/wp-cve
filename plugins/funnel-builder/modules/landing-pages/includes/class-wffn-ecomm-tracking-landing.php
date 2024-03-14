<?php

/**
 * This class take care of ecommerce tracking setup
 * It renders necessary javascript code to fire events as well as creates dynamic data for the tracking
 * @author woofunnels.
 */
if ( ! class_exists( 'WFFN_Ecomm_Tracking_Landing' ) ) {
	#[AllowDynamicProperties]

  class WFFN_Ecomm_Tracking_Landing extends WFFN_Ecomm_Tracking_Common {
		private static $ins = null;

		public function __construct() {
			parent::__construct();
		}

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

		public function do_track_ga_view() {
			if ( true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_ga_page_view_global' ) ) ) {
				return true;
			}

			$ga_tracking = $this->admin_general_settings->get_option( 'is_ga_page_view_lp' );
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

			$fb_tracking = $this->admin_general_settings->get_option( 'is_fb_page_view_lp' );
			if ( is_array( $fb_tracking ) && count( $fb_tracking ) > 0 && 'yes' === $fb_tracking[0] ) {
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

			$view_tracking = $this->admin_general_settings->get_option( 'is_gad_page_view_lp' );
			if ( is_array( $view_tracking ) && count( $view_tracking ) > 0 && 'yes' === $view_tracking[0] ) {
				return true;
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

			$view_tracking = $this->admin_general_settings->get_option( 'is_snapchat_page_view_lp' );
			if ( is_array( $view_tracking ) && count( $view_tracking ) > 0 && 'yes' === $view_tracking[0] ) {
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

			$view_tracking = $this->admin_general_settings->get_option( 'is_pint_page_view_lp' );

			if ( is_array( $view_tracking ) && count( $view_tracking ) > 0 && 'yes' === $view_tracking[0] ) {
				return true;
			}

			return false;
		}

		/*  maybe render script to fire fb pixel view event */
		public function do_track_tiktok_view() {
			if ( true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_tiktok_page_view_global' ) ) ) {
				return true;
			}

			$view_tracking = $this->admin_general_settings->get_option( 'is_tiktok_page_view_lp' );
			if ( is_array( $view_tracking ) && count( $view_tracking ) > 0 && 'yes' === $view_tracking[0] ) {
				return true;
			}

			return false;

		}

		public function should_render( $check_valid_session = false ) {

			if ( parent::should_render( $check_valid_session ) && WFFN_Core()->landing_pages->is_wflp_page() ) {
				return true;
			}

			return false;
		}

		public function get_custom_event_name() {
			return 'WooFunnels_Sales';
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