<?php
/**
 * Mailerlite handler of the plugin.
 *
 * @package    Blossomthemes_Email_Newsletter
 * @subpackage Blossomthemes_Email_Newsletter/includes
 * @author    blossomthemes
 */
class Blossomthemes_Email_Newsletter_Mailerlite {

	/*Function to add main mailchimp action*/
	function bten_mailerlite_action( $email, $sid, $fname ) {
		if ( ! empty( $email ) && ! filter_var( $email, FILTER_VALIDATE_EMAIL ) === false ) {
			// mailerlite API credentials
			$blossomthemes_email_newsletter_setting = get_option( 'blossomthemes_email_newsletter_settings', true );
			$apiKey                                 = $blossomthemes_email_newsletter_setting['mailerlite']['api-key'];

			if ( ! empty( $apiKey ) ) {
				$groupsApi = ( new \MailerLiteApi\MailerLite( $apiKey ) )->groups();

				$subscriber = array(
					'email' => $email,
					'name'  => $fname,
				);

				$obj  = new BlossomThemes_Email_Newsletter_Settings();
				$data = $obj->mailerlite_lists();

				if ( ! empty( $data ) ) {
					$listids = get_post_meta( $sid, 'blossomthemes_email_newsletter_setting', true );

					if ( ! isset( $listids['mailerlite']['list-id'] ) ) {
						$listid          = $blossomthemes_email_newsletter_setting['mailerlite']['list-id'];
						$addedSubscriber = $groupsApi->addSubscriber( $listid, $subscriber, 1 ); // returns added subscriber
						$response = isset( $addedSubscriber->error ) ? $addedSubscriber->error->message : '200';

					} else {
						foreach ( $listids['mailerlite']['list-id'] as $key => $value ) {
							$addedSubscriber = $groupsApi->addSubscriber( $key, $subscriber, 1 );
						}
						$response = isset( $addedSubscriber->error ) ? $addedSubscriber->error->message : '200';
					}
				}
			}
			return $response;
		}
	}
}
new Blossomthemes_Email_Newsletter_Mailerlite();
