<?php
/**
 * Initialize AbsolutePlugins Services
 *
 * @version 1.0.0
 * @package AbsolutePluginsServices
 */

namespace AbsoluteAddons\AbsolutePluginsServices;

use AbsoluteAddons\Absp_Library_Source;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Absolute_Addons_Services {

	/**
	 * Singleton instance
	 *
	 * @var Absolute_Addons_Services
	 */
	protected static $instance;

	/**
	 * @var Client
	 */
	protected $client = null;

	/**
	 * @var Insights
	 */
	protected $insights = null;

	/**
	 * Promotions Class Instance
	 *
	 * @var Promotions
	 */
	public $promotion = null;

	/**
	 * Plugin License Manager
	 *
	 * @var License
	 */
	protected $license = null;

	/**
	 * Plugin Updater
	 *
	 * @var Updater
	 */
	protected $updater = null;

	/**
	 * Initialize
	 *
	 * @return Absolute_Addons_Services
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function __construct() {

		/** @define "ABSOLUTE_ADDONS_PATH" "./../" */

		if ( ! class_exists( 'Client' ) ) {
			require_once ABSOLUTE_ADDONS_PATH . 'includes/absolute-plugins-services/class-client.php';
		}

		// Load The Client.
		$this->client = new Client(
			'00a386f2-5ae3-4526-87d3-bf861b1d6d1b',
			__( 'Absolute Addons', 'absolute-addons' ),
			ABSOLUTE_ADDONS_FILE
		);

		/**
		 * Allow local request,
		 * so server behind reverse proxy can work too.
		 */
		$this->client->allow_local_request();

		$this->init_hooks();

		// Initialize All Services.
		$this->insights  = $this->client->insights();
		$this->promotion = $this->client->promotions();

		// Initialize.

		$this->insights->init();
		$this->promotion->init();
	}

	/**
	 * Initialize Insights
	 *
	 * @return void
	 */
	private function init_hooks() {

		$slug = $this->client->getSlug();

		add_action( 'admin_init', [ $this, 'admin_init' ] );
		add_filter( $slug . '_tracker_data', [ $this, 'track_extra_data' ] );
		add_filter( $slug . '_terms_url', [ $this, 'terms_url' ] );
		add_filter( $slug . '_privacy_policy_url', [ $this, 'privacy_policy_url' ] );
		add_filter( $slug . '_what_tracked', [ $this, 'data_we_collect' ] );
		add_filter( "absp_service_api_{$slug}_Support_Ticket_Recipient_Email", [ $this, 'support_email' ] );
		add_filter( "absp_service_api_{$slug}_Support_Ticket_Email_Template", [ $this, 'support_ticket_template' ], 10 );
		add_filter( "absp_service_api_{$slug}_Support_Request_Ajax_Success_Response", [ $this, 'support_response' ], 10 );
		add_filter( "absp_service_api_{$slug}_Support_Request_Ajax_Error_Response", [ $this, 'support_error_response' ], 10 );
		add_filter( "absp_service_api_{$slug}_Support_Page_URL", [ $this, 'support_url' ] );
	}

	public function admin_init() {
		if ( ! absp_has_pro() ) {
			$this->insights->add_action_links(
				'get_pro',
				[
					'href'   => 'https://absoluteplugins.com/wordpress-plugins/absolute-addons/pricing/?utm_source=plugin-installer&utm_medium=action-links&utm_campaign=get-pro&utm_content=get-pro',
					'class'  => 'absolute-addons-get-pro',
					'target' => '_blank',
					'rel'    => 'noopener',
					'label'  => esc_html__( 'Get Pro', 'absolute-addons' ),
				]
			);
		}
	}

	public function track_extra_data( $data ) {

		$data['extra']['favourite'] = get_option( Absp_Library_Source::LIBRARY_FAVORITE_KEY );

		return $data;
	}

	protected function enable_debug_mode() {
		add_filter( 'absp_service_api_is_debugging', '__return_true' );
		add_filter( 'https_local_ssl_verify', '__return_false' );
		add_filter( 'http_request_reject_unsafe_urls', '__return_false' );
		add_filter( 'absp_service_api_is_local', '__return_false' );
		add_filter( 'http_request_reject_unsafe_urls', '__return_false' );
	}

	/**
	 * Check license status
	 *
	 * @return bool
	 */
	public function is_active() {
		return $this->license->is_valid();
	}

	public function get_license_data() {
		return $this->license->get_license();
	}

	/**
	 * Set Data Collection description for the tracker
	 *
	 * @param $data
	 *
	 * @return array
	 */
	public function data_we_collect( $data ) {

		$data[999] = sprintf(
			/* translators: 1. Plugin Name. */
			esc_html__( 'And %s usage data.', 'absolute-addons' ),
			'<strong>' . esc_html__( 'Absolute Addon', 'absolute-addons' ) . '</strong>'
		);

		return $data;
	}

	public function get_data_we_collect() {
		return $this->insights->get_data_we_collect();
	}

	/**
	 * Update Tracker OptIn
	 *
	 * @param bool $override optional. ignore last send datetime settings if true.
	 * @return void
	 * @see Insights::send_tracking_data()
	 */
	public function tracker_opt_in( $override = false ) {
		$this->insights->optIn( $override );
	}

	/**
	 * Update Tracker OptOut
	 *
	 * @return void
	 */
	public function tracker_opt_out() {
		$this->insights->optOut();
	}

	/**
	 * Check if tracking is enable
	 *
	 * @return bool
	 */
	public function is_tracking_allowed() {
		return $this->insights->is_tracking_allowed();
	}

	public function opt_in_url() {
		return $this->insights->get_opt_in_url();
	}

	public function opt_out_url() {
		return $this->insights->get_opt_out_url();
	}

	public function support_email() {
		return 'support@absoluteplugins.com';
	}

	public function support_url() {
		return 'https://go.absoluteplugins.com/to/absolute-addons-support/';
	}

	public function terms_url() {
		return 'https://go.absoluteplugins.com/to/absolute-addons-terms/';
	}

	public function privacy_policy_url() {
		return 'https://go.absoluteplugins.com/to/absolute-addons-privacy/';
	}

	/**
	 * Generate Support Ticket Email Template
	 *
	 * @return string
	 */
	public function support_ticket_template() {
		// dynamic variable format __INPUT_NAME__

		$template  = sprintf(
			'<div style="margin: 10px auto;"><p>Website : <a href="__WEBSITE__">__WEBSITE__</a><br>Plugin : %s (v.%s)</p></div>',
			$this->client->getName(),
			$this->client->getProjectVersion()
		);
		$template .= '<div style="margin: 10px auto;"><hr></div>';
		$template .= '<div style="margin: 10px auto;"><h3>__SUBJECT__</h3></div>';
		$template .= '<div style="margin: 10px auto;">__MESSAGE__</div>';
		$template .= '<div style="margin: 10px auto;"><hr></div>';
		$template .= sprintf(
			'<div style="margin: 50px auto 10px auto;"><p style="font-size: 12px;color: #009688">%s</p></div>',
			'Message Processed With AbsolutePlugin Service Library (v.' . $this->client->getClientVersion() . ')'
		);
		return $template;
	}

	/**
	 * Generate Support Ticket Ajax Response
	 *
	 * @return string
	 */
	public function support_response() {
		$ticket_submitted = esc_html__( 'Your ticket has been successfully submitted.', 'absolute-addons' );
		$twenty4_hours    = '<strong>' . esc_html__( '24 hours', 'absolute-addons' ) . '</strong>';
		$notification     = sprintf(
			/* translators: 1. Support Response Time. */
			esc_html__( 'You will receive an email notification from "support@absoluteplugins.com" in your inbox within %1$s.', 'absolute-addons' ),
			$twenty4_hours
		);
		$follow_up        = esc_html__( 'Please Follow the email and AbsolutePlugins Support Team will get back with you shortly.', 'absolute-addons' );
		$doc_link         = sprintf( '<a class="button button-primary" href="https://absoluteplugins.com/docs/docs/absolute-addons/" target="_blank"><span class="dashicons dashicons-media-document" aria-hidden="true"></span> %s</a>', esc_html__( 'Documentation', 'absolute-addons' ) );
		$vid_link         = sprintf( '<a class="button button-primary" href="https://go.absoluteplugins.com/to/video-tutorial" target="_blank"><span class="dashicons dashicons-video-alt3" aria-hidden="true"></span> %s</a>', esc_html__( 'Video Tutorials', 'absolute-addons' ) );
		$policy           = sprintf(
			/* translators: 1: Privacy Policy Link, 2: Terms Links */
			esc_html__( 'Please read our %1$s and %2$s', 'absolute-addons' ),
			'<a href="' . esc_url( $this->terms_url() ) . '" target="_blank">' . esc_html__( 'Terms & Conditions', 'absolute-addons' ) . '</a>',
			'<a href="' . esc_url( $this->privacy_policy_url() ) . '" target="_blank">' . esc_html__( 'Privacy Policy', 'absolute-addons' ) . '</a>'
		);

		$response = '<h3>' . esc_html__( 'Thank you -- Support Ticket Submitted.', 'absolute-addons' ) . '</h3>';
		$response .= '<p>' . $ticket_submitted . ' ' . $notification . ' ' . $follow_up . '</p>';
		$response .= '<p>' . $doc_link . ' ' . $vid_link . '</p><br><br><br>';
		$response .= '<p style="font-size: 12px;">' . $policy . '</p>';

		return $response;
	}

	/**
	 * Set Error Response Message For Support Ticket Request
	 *
	 * @return string
	 */
	public function support_error_response() {
		return sprintf(
			'<div class="mui-error"><p>%s</p><p>%s</p><br><br><p style="font-size: 12px;">%s</p></div>',
			esc_html__( 'Something Went Wrong. Please Try The Support Ticket Form On Our Website.', 'absolute-addons' ),
			sprintf( '<a class="button button-primary" href="https://go.absoluteplugins.com/to/get-absolute-addons-support/" target="_blank">%s</a>', esc_html__( 'Get Support', 'absolute-addons' ) ),
			esc_html__( 'Support Ticket form will open in new tab in 5 seconds.', 'absolute-addons' )
		);
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cloning is forbidden.', 'absolute-addons' ), '1.0.2' );
	}
}

Absolute_Addons_Services::get_instance();

// End of file class-absolute-addons-services.php.
