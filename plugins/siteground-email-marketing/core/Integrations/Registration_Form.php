<?php
namespace SG_Email_Marketing\Integrations;

/**
 * Class managing all Contact Forms integrations.
 */
class Registration_Form extends Integrations {

	/**
	 * The integration id.
	 *
	 * @var string
	 */
	public $id = 'registration_form';

	/**
	 * Get the integration data.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array containing integration data.
	 */
	public function fetch_settings() {
		$settings = get_option(
			$this->prefix . $this->id,
			array(
				'enabled'       => 0,
				'labels'        => array(),
				'checkbox_text' => __( 'Sign me up for the newsletter!', 'siteground-email-marketing' ),
				'system'        => 0,
				'name'          => $this->id,
			)
		);

		$settings['title']       = __( 'Default Registration Page', 'siteground-email-marketing' );
		$settings['description'] = __( 'Add an optional checkbox to the default WordPress registration page, enabling users to sign up for your mailing list.', 'siteground-email-marketing' );

		return $settings;
	}

	/**
	 * Add the comment form consent field.
	 *
	 * @since 1.0.0
	 */
	public function add_registration_form_consent() {
		// Get integration data.
		$integration = $this->fetch_settings();

		// Add the custom field.
		echo '<p><input id="sg-email-consent" name="sg-email-consent" type="checkbox" value="yes" /> <label for="sg-email-consent">' . ( ! empty( $integration['checkbox_text'] ) ? esc_attr( $integration['checkbox_text'] ) : esc_attr__( 'Sign-up for updates and special offers', 'siteground-email-marketing' ) ) . '</label></p>'; // phpcs:ignore
		\wp_nonce_field( 'sg-email-consent-comment' );
	}

	/**
	 * Handle the user registration data immediately after a new user is registered.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id User ID.
	 */
	public function handle_user_registration( $user_id ) {
		if (
			! isset( $_REQUEST['_wpnonce'] ) ||
			! wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), 'sg-email-consent-comment' )
			) {
			return;
		}

		// Bail if no consent.
		if ( ! isset( $_POST['sg-email-consent'] ) ) { // phpcs:ignore
			return;
		}
		// Get the user data.
		$user = get_userdata( $user_id );

		$data = array(
			'timestamp' => strtotime( $user->data->user_registered ),
			'ip'        => $this->get_current_user_ip(),
			'labels'    => $this->get_label_ids( $this->fetch_settings() ),
			'email'     => $user->data->user_email,
		);

		if ( ! empty( $user->data->display_name ) ) {
			$data = array_merge( $data, $this->split_names( $user->data->display_name ) );
		}

		if ( $this->helper->is_cron_disabled() ) {
			$this->mailer_api->send_data( $data );
			return;
		}

		add_user_meta( $user_id, 'sg_email_marketing_user_data', $data );
	}
}
