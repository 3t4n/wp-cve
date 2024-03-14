<?php
/**
 * This file contains some functions to sync WordPress with Nelio’s cloud.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements some functions to sync WordPress with Nelio’s cloud.
 */
class Nelio_Content_Cloud {

	protected static $instance;
	private $are_site_options_updated = false;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	public function init() {

		if ( nc_use_editorial_calendar_only() ) {
			return;
		}//end if

		add_action( 'admin_init', array( $this, 'add_hooks_for_updating_site_in_cloud' ) );

		add_action( 'nelio_content_save_post', array( $this, 'maybe_sync_post' ) );
		add_action( 'nelio_content_update_post_in_cloud', array( $this, 'maybe_sync_post' ) );
		add_action( 'plugins_loaded', array( $this, 'add_hooks_for_updating_post_in_cloud_on_publish' ) );

		add_action( 'init', array( $this, 'maybe_add_profile_status_checker' ) );

	}//end init()

	public function add_hooks_for_updating_site_in_cloud() {

		add_filter( 'pre_update_option_gmt_offset', array( $this, 'on_site_option_updated' ), 10, 2 );
		add_filter( 'pre_update_option_timezone_string', array( $this, 'on_site_option_updated' ), 10, 2 );
		add_filter( 'pre_update_option_WPLANG', array( $this, 'on_site_option_updated' ), 10, 2 );
		add_filter( 'pre_update_option_home', array( $this, 'on_site_option_updated' ), 10, 2 );

		add_action( 'shutdown', array( $this, 'maybe_sync_site' ), 10, 2 );

	}//end add_hooks_for_updating_site_in_cloud()

	public function add_hooks_for_updating_post_in_cloud_on_publish() {

		$settings   = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types', array() );
		foreach ( $post_types as $post_type ) {
			add_action( "publish_{$post_type}", array( $this, 'maybe_sync_post' ) );
		}//end foreach

	}//end add_hooks_for_updating_post_in_cloud_on_publish()

	public function maybe_sync_post( $post_id ) {

		// If it's a revision or an autosave, do nothing.
		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}//end if

		// If it’s an auto-draft, do nothing.
		if ( 'auto-draft' === get_post_status( $post_id ) ) {
			return;
		}//end if

		// If we don't have social profiles, do nothing.
		if ( ! get_option( 'nc_has_social_profiles' ) ) {
			return;
		}//end if

		// If post type is not controlled by the plugin, do nothing.
		$settings             = Nelio_Content_Settings::instance();
		$supported_post_types = $settings->get( 'calendar_post_types', array() );
		if ( ! in_array( get_post_type( $post_id ), $supported_post_types, true ) ) {
			return;
		}//end if

		// If the post hasn’t changed since last time...
		$post_helper = Nelio_Content_Post_Helper::instance();
		if ( ! $post_helper->has_relevant_changes( $post_id ) ) {
			return;
		}//end if

		// Otherwise, synch the plugin.
		$attempts = get_post_meta( $post_id, '_nc_cloud_sync_attempts', true );
		if ( empty( $attempts ) ) {
			$attempts = 0;
		}//end if
		++$attempts;

		$post    = $post_helper->post_to_aws_json( $post_id );
		$synched = $this->sync_post( $post_id, $post );
		if ( ! $synched && 3 >= $attempts ) {
			update_post_meta( $post_id, '_nc_cloud_sync_attempts', $attempts );
			wp_schedule_single_event( time() + 30, 'nelio_content_update_post_in_cloud', array( $post_id ) );
		} else {
			delete_post_meta( $post_id, '_nc_cloud_sync_attempts' );
			$post_helper->mark_post_as_synched( $post_id );
		}//end if

	}//end maybe_sync_post()

	public function on_site_option_updated( $new_value, $old_value ) {
		if ( $new_value !== $old_value ) {
			$this->are_site_options_updated = true;
		}//end if
		return $new_value;
	}//end on_site_option_updated()

	public function maybe_sync_site() {

		if ( ! $this->are_site_options_updated ) {
			return;
		}//end if

		// Note. Use error_logs for logging this function or you won't see anything.
		$data = array(
			'method'  => 'PUT',
			'timeout' => apply_filters( 'nelio_content_request_timeout', 30 ),
			'headers' => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
			'body'    => wp_json_encode(
				array(
					'url'      => home_url(),
					'timezone' => nc_get_timezone(),
					'language' => nc_get_language(),
				)
			),
		);

		$url = nc_get_api_url( '/site/' . nc_get_site_id(), 'wp' );
		wp_remote_request( $url, $data );

	}//end maybe_sync_site()

	public function maybe_add_profile_status_checker() {

		$event = 'nelio_content_check_profile_status';

		/**
		 * Whether Nelio Content should warn users when there are profiles that need to be reauthenticated.
		 *
		 * @param boolean $warn Send emails with warning. Default: `true`.
		 *
		 * @since 2.0.7
		 */
		if ( ! apply_filters( 'nelio_content_warn_when_profile_reauth_is_required', true ) ) {
			$schedule = wp_next_scheduled( $event );
			wp_unschedule_event( $schedule, $event );
			return;
		}//end if

		add_action( $event, array( $this, 'check_profile_status' ) );

		$actual_recurrence   = wp_get_schedule( $event );
		$expected_recurrence = nc_is_subscribed() ? 'daily' : 'weekly';
		if ( $actual_recurrence !== $expected_recurrence ) {
			$schedule = wp_next_scheduled( $event );
			wp_unschedule_event( $schedule, $event );
			wp_schedule_event( time() + DAY_IN_SECONDS, $expected_recurrence, $event );
		}//end if

	}//end maybe_add_profile_status_checker()

	public function check_profile_status() {

		$data = array(
			'method'    => 'GET',
			'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$url = sprintf(
			nc_get_api_url( '/site/%s/profiles/renew', 'wp' ),
			nc_get_site_id()
		);

		$response = wp_remote_request( $url, $data );
		if ( is_wp_error( $response )
			|| ! isset( $response['response'] )
			|| ! isset( $response['response']['code'] )
			|| 200 !== $response['response']['code']
		) {
			return;
		}//end if

		$profiles = json_decode( $response['body'], true );
		if ( ! is_array( $profiles ) ) {
			return;
		}//end if

		$users  = array_values( array_unique( wp_list_pluck( $profiles, 'creatorId' ) ) );
		$emails = array_map(
			function( $user_id ) {
				$info = get_userdata( $user_id );
				if ( ! is_user_member_of_blog( $user_id ) || empty( $info ) ) {
					return false;
				}//end if
				return $info->user_email;
			},
			$users
		);
		$emails = array_values( array_unique( array_filter( $emails ) ) );

		if ( empty( $emails ) ) {
			return;
		}//end if

		$subject = sprintf(
			/* translators: blogname */
			_x( '[%s] Action Required: Re-Authenticate Social Profiles', 'text', 'nelio-content' ),
			get_option( 'blogname' )
		);

		$message = sprintf(
			/* translators: 1 -> website name, 2 -> website URL  */
			_x( 'One or more social profiles in %1$s need to be re-authenticated. Please go to Nelio Content’s Settings (%2$s) and re-authenticate them.', 'user', 'nelio-content' ),
			get_option( 'blogname' ),
			admin_url( 'admin.php?page=nelio-content-settings' )
		);

		// phpcs:ignore
		wp_mail( $emails, $subject, $message );

	}//end check_profile_status()

	private function sync_post( $post_id, $post ) {

		$data = array(
			'method'    => 'PUT',
			'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
			'body'      => wp_json_encode( $post ),
		);

		$url = sprintf(
			nc_get_api_url( '/site/%s/post/%s', 'wp' ),
			nc_get_site_id(),
			$post_id
		);

		$response = wp_remote_request( $url, $data );

		if ( is_wp_error( $response ) ) {
			return false;
		}//end if

		if ( ! isset( $response['response'] )
			|| ! isset( $response['response']['code'] )
			|| 200 !== $response['response']['code'] ) {
			return false;
		}//end if

		return true;

	}//end sync_post()

}//end class
