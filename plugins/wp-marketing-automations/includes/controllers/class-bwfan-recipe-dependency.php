<?php
#[AllowDynamicProperties]
class BWFAN_Recipe_Dependency {

	protected $data = "Required data is missing ";
	protected $err_msg = null;

	public function set_data( $data ) {
		$this->data = $data;
	}

	public function get_error_message() {

		return $this->err_msg;
	}

	public function validate() {
		$response = $this->check_version();

		if ( false === $response ) {
			return $this->get_error_message();
		}

		if ( ! isset( $this->data['function'] ) || empty( $this->data['function'] ) ) {
			return true;
		}

		$functions = $this->data['function'];
		foreach ( $functions as $function ) {
			$response = call_user_func( [ $this, $function ] );
			if ( false === $response ) {
				return $this->get_error_message();
			}
		}

		return true;
	}

	public function check_version() {
		$version = isset( $this->data['version'] ) ? $this->data['version'] : [];

		if ( empty( $version ) ) {
			return true;
		}

		if ( isset( $version['lite'] ) && version_compare( BWFAN_VERSION, $version['lite'], "<" ) ) {
			$this->err_msg = sprintf( __( 'FunnelKit Automations version should be greater than or equal to %s.', 'wp-marketing-automations' ), $version['lite'] );

			return false;
		}

		if ( ! isset( $version['pro'] ) ) {
			return true;
		}

		if ( ! bwfan_is_autonami_pro_active() ) {
			$this->err_msg = __( 'FunnelKit Automations Pro plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		if ( isset( $version['pro'] ) && version_compare( BWFAN_PRO_VERSION, $version['pro'], "<" ) ) {
			$this->err_msg = sprintf( __( 'FunnelKit Automations Pro version should be greater than or equal to %s.', 'wp-marketing-automations' ), $version['pro'] );

			return false;
		}

		return true;
	}

	/** Autonami Pro */
	public function plugin_autonami_pro_active() {
		if ( ! bwfan_is_autonami_pro_active() ) {
			$this->err_msg = __( 'FunnelKit Automations Pro plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** WooCommerce */
	public function plugin_wc_active() {
		if ( ! bwfan_is_woocommerce_active() ) {
			$this->err_msg = __( 'WooCommerce plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** WooCommerce Subscription */
	public function plugin_wcs_active() {
		if ( ! bwfan_is_woocommerce_subscriptions_active() ) {
			$this->err_msg = __( 'WooCommerce Subscription plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Learndash */
	public function plugin_ld_active() {
		if ( ! bwfan_is_learndash_active() ) {
			$this->err_msg = __( 'Learndash plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Paid Membership Pro */
	public function plugin_pm_pro_active() {
		if ( ! bwfan_is_paid_membership_pro_active() ) {
			$this->err_msg = __( 'Paid Membership Pro plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Tutor LMS */
	public function plugin_tutor_lms_active() {
		if ( ! bwfan_is_tutorlms_active() ) {
			$this->err_msg = __( 'Tutor LMS plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** MemberPress */
	public function plugin_mepr_active() {
		if ( ! bwfan_is_mepr_active() ) {
			$this->err_msg = __( 'MemberPress plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Wishlist Member */
	public function plugin_wlm_active() {
		if ( ! bwfan_is_wlm_active() ) {
			$this->err_msg = __( 'Wishlist Member plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** AffiliateWP */
	public function plugin_affwp_active() {
		if ( ! bwfan_is_affiliatewp_active() ) {
			$this->err_msg = __( 'AffiliateWP plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** WPForms */
	public function plugin_wpforms_active() {
		if ( ! bwfan_is_wpforms_active() ) {
			$this->err_msg = __( 'WPForms plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Elementor Forms */
	public function plugin_elementor_forms_active() {
		if ( ! bwfan_is_elementorpro_active() ) {
			$this->err_msg = __( 'Elementor plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Gravity Forms */
	public function plugin_gforms_active() {
		if ( ! bwfan_is_gforms_active() ) {
			$this->err_msg = __( 'Gravity Forms plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Thrive Leads */
	public function plugin_tve_active() {
		if ( ! bwfan_is_tve_active() ) {
			$this->err_msg = __( 'Thrive Leads plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Divi Forms */
	public function plugin_divi_forms_active() {
		if ( ! bwfan_is_divi_forms_active() ) {
			$this->err_msg = __( 'Divi theme or plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Formidable Forms */
	public function plugin_formidable_form_active() {
		if ( ! bwfan_is_formidable_forms_active() ) {
			$this->err_msg = __( 'Formidable Forms plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** WooCommerce Membership */
	public function plugin_wc_membership_active() {
		if ( ! bwfan_is_woocommerce_membership_active() ) {
			$this->err_msg = __( 'WooCommerce Membership plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** FunnelKit Funnel Builder */
	public function plugin_woofunnels_fb_active() {
		if ( ! function_exists( 'bwfan_is_funnel_optin_forms_active' ) || ! bwfan_is_funnel_optin_forms_active() ) {
			$this->err_msg = __( 'FunnelKit Funnel Builder plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** FunnelKit Funnel Builder Pro */
	public function plugin_woofunnels_fb_pro_active() {
		if ( ! defined( 'WFFN_PRO_BUILD_VERSION' ) ) {
			$this->err_msg = __( 'FunnelKit Funnel Builder Pro plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Autonami Cart tracking */
	public function setting_cart_tracking_enabled() {
		$global_settings = BWFAN_Common::get_global_settings();
		if ( ! isset( $global_settings['bwfan_ab_enable'] ) || empty( $global_settings['bwfan_ab_enable'] ) ) {
			$this->err_msg = __( 'Cart tracking is not active.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Slack Connector */
	public function connector_slack_active() {
		if ( ! bwfan_is_autonami_connector_active() ) {
			$this->err_msg = __( 'FunnelKit Automations Connector plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		$is_connected = BWFAN_Core()->connectors->is_connected( 'bwfco_slack' );
		if ( empty( $is_connected ) ) {
			$this->err_msg = __( 'Slack connector is not integrated.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** ActiveCampaign Connector */
	public function connector_ac_active() {
		if ( ! bwfan_is_autonami_connector_active() ) {
			$this->err_msg = __( 'FunnelKit Automations Connector plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		$is_connected = BWFAN_Core()->connectors->is_connected( 'bwfco_activecampaign' );
		if ( empty( $is_connected ) ) {
			$this->err_msg = __( 'ActiveCampaign connector is not integrated.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Mailchimp Connector */
	public function connector_mailchimp_active() {
		if ( ! bwfan_is_autonami_connector_active() ) {
			$this->err_msg = __( 'FunnelKit Automations Connector plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		$is_connected = BWFAN_Core()->connectors->is_connected( 'bwfco_mailchimp' );
		if ( empty( $is_connected ) ) {
			$this->err_msg = __( 'Mailchimp connector is not integrated.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** GetResponse Connector */
	public function connector_getresponse_active() {
		if ( ! bwfan_is_autonami_connector_active() ) {
			$this->err_msg = __( 'FunnelKit Automations Connector plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		$is_connected = BWFAN_Core()->connectors->is_connected( 'bwfco_getresponse' );
		if ( empty( $is_connected ) ) {
			$this->err_msg = __( 'GetResponse connector is not integrated.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Ontraport Connector */
	public function connector_ontraport_active() {
		if ( ! bwfan_is_autonami_connector_active() ) {
			$this->err_msg = __( 'FunnelKit Automations Connector plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		$is_connected = BWFAN_Core()->connectors->is_connected( 'bwfco_ontraport' );
		if ( empty( $is_connected ) ) {
			$this->err_msg = __( 'Ontraport connector is not integrated.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Hubspot Connector */
	public function connector_hubspot_active() {
		if ( ! bwfan_is_autonami_connector_active() ) {
			$this->err_msg = __( 'FunnelKit Automations Connector plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		$is_connected = BWFAN_Core()->connectors->is_connected( 'bwfco_hubspot' );
		if ( empty( $is_connected ) ) {
			$this->err_msg = __( 'Hubspot connector is not integrated.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Klaviyo Connector */
	public function connector_klaviyo_active() {
		if ( ! bwfan_is_autonami_connector_active() ) {
			$this->err_msg = __( 'FunnelKit Automations Connector plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		$is_connected = BWFAN_Core()->connectors->is_connected( 'bwfco_klaviyo' );
		if ( empty( $is_connected ) ) {
			$this->err_msg = __( 'Klaviyo connector is not integrated.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** ConvertKit Connector */
	public function connector_convertkit_active() {
		if ( ! bwfan_is_autonami_connector_active() ) {
			$this->err_msg = __( 'FunnelKit Automations Connector plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		$is_connected = BWFAN_Core()->connectors->is_connected( 'bwfco_convertkit' );
		if ( empty( $is_connected ) ) {
			$this->err_msg = __( 'ConvertKit connector is not integrated.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Drip Connector */
	public function connector_drip_active() {
		if ( ! bwfan_is_autonami_connector_active() ) {
			$this->err_msg = __( 'FunnelKit Automations Connector plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		$is_connected = BWFAN_Core()->connectors->is_connected( 'bwfco_drip' );
		if ( empty( $is_connected ) ) {
			$this->err_msg = __( 'Drip connector is not integrated.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Keap Connector */
	public function connector_keap_active() {
		if ( ! bwfan_is_autonami_connector_active() ) {
			$this->err_msg = __( 'FunnelKit Automations Connector plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		$is_connected = BWFAN_Core()->connectors->is_connected( 'bwfco_keap' );
		if ( empty( $is_connected ) ) {
			$this->err_msg = __( 'Infusionsoft by Keap connector is not integrated.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** MailerLite Connector */
	public function connector_mailerlite_active() {
		if ( ! bwfan_is_autonami_connector_active() ) {
			$this->err_msg = __( 'FunnelKit Automations Connector plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		$is_connected = BWFAN_Core()->connectors->is_connected( 'bwfco_mailerlite' );
		if ( empty( $is_connected ) ) {
			$this->err_msg = __( 'MailerLite connector is not integrated.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Mautic Connector */
	public function connector_mautic_active() {
		if ( ! bwfan_is_autonami_connector_active() ) {
			$this->err_msg = __( 'FunnelKit Automations Connector plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		$is_connected = BWFAN_Core()->connectors->is_connected( 'bwfco_mautic' );
		if ( empty( $is_connected ) ) {
			$this->err_msg = __( 'Mautic connector is not integrated.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Twilio Connector */
	public function connector_twilio_active() {
		if ( ! bwfan_is_autonami_connector_active() ) {
			$this->err_msg = __( 'FunnelKit Automations Connector plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		$is_connected = BWFAN_Core()->connectors->is_connected( 'bwfco_twilio' );
		if ( empty( $is_connected ) ) {
			$this->err_msg = __( 'Twilio connector is not integrated.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** Google Sheets Connector */
	public function connector_google_sheets_active() {
		if ( ! bwfan_is_autonami_connector_active() ) {
			$this->err_msg = __( 'FunnelKit Automations Connector plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		$is_connected = BWFAN_Core()->connectors->is_connected( 'bwfco_google_sheets' );
		if ( empty( $is_connected ) ) {
			$this->err_msg = __( 'Google Sheets connector is not integrated.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}

	/** BulkGate Connector */
	public function connector_bulkgate_active() {
		if ( ! bwfan_is_autonami_connector_active() ) {
			$this->err_msg = __( 'FunnelKit Automations Connector plugin is not active.', 'wp-marketing-automations' );

			return false;
		}

		$is_connected = BWFAN_Core()->connectors->is_connected( 'bwfco_bulkgate' );
		if ( empty( $is_connected ) ) {
			$this->err_msg = __( 'BulkGate connector is not integrated.', 'wp-marketing-automations' );

			return false;
		}

		return true;
	}
}
