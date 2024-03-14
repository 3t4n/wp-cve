<?php
namespace SG_Email_Marketing\Integrations;

/**
 * Class managing all Contact Forms integrations.
 */
class Comment_Form extends Integrations {

	/**
	 * The integration id.
	 *
	 * @var string
	 */
	public $id = 'comment_form';

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

		$settings['title']       = __( 'Default Comment Form', 'siteground-email-marketing' );
		$settings['description'] = __( 'Add an optional checkbox to the default WordPress comment form, enabling visitors who comment on your site to subscribe.', 'siteground-email-marketing' );

		return $settings;
	}

	/**
	 * Add the comment form consent field.
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields The comment fields.
	 *
	 * @return array $fields The comment modified fields.
	 */
	public function add_comment_form_consent( $fields ) {
		// Get integration data.
		$integration = $this->fetch_settings();

		// Add the custom field.
		$fields['sg_email_consent'] = '<p class="comment-form-cookies-consent"><input id="sg-email-consent" name="sg-email-consent" type="checkbox" value="yes" /> <label for="sg-email-consent">' . ( ! empty( $integration['checkbox_text'] ) ? $integration['checkbox_text'] : __( 'Sign-up for updates and special offers', 'siteground-email-marketing' ) ) . '</label></p>';
		$fields['sg_email_consent_nonce'] = \wp_nonce_field( 'sg-email-consent-comment', '_wpnonce', true, false );
		return $fields;
	}

	/**
	 * Handle the comment data immediately after a comment is inserted into the database.
	 *
	 * @since 1.0.0
	 *
	 * @param  int        $comment_id  The comment ID.
	 * @param  int|string $approved    1 if the comment is approved, 0 if not, 'spam' if spam.
	 * @param  array      $commentdata Comment data.
	 */
	public function handle_comment_submission( $comment_id, $approved, $commentdata ) {

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

		$data = array(
			'timestamp' => strtotime( $commentdata['comment_date'] ),
			'ip'        => $commentdata['comment_author_IP'],
			'labels'    => $this->get_label_ids( $this->fetch_settings() ),
			'email'     => $commentdata['comment_author_email'],
		);

		if ( ! empty( $commentdata['comment_author'] ) ) {
			$data = array_merge( $data, $this->split_names( $commentdata['comment_author'] ) );
		}

		if ( $this->helper->is_cron_disabled() ) {
			$this->mailer_api->send_data( $data );
			return;
		}

		add_comment_meta( $comment_id, 'sg_email_marketing_user_data', $data );
	}
}
