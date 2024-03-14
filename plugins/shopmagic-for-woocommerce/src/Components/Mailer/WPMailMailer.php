<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Mailer;

class WPMailMailer implements Mailer {

	public function send( Email $message ): void {
		add_filter(
			'wp_mail_from',
			$from_cb = static function () use ( $message ) {
				return $message->from;
			}
		);
		add_filter(
			'wp_mail_from_name',
			$from_name_cb = static function () use ( $message ) {
				return $message->from_name;
			}
		);
		add_action( 'wp_mail_failed', [ $this, 'catch_error' ] );

		try {
			$success = wp_mail(
				$message->to,
				$message->subject,
				$message->message,
				$message->get_headers(),
				$message->attachments
			);
			if ( ! $success ) {
				throw new MailerException( 'Count not send the mail with wp_mail()' );
			}
		} catch ( \Exception $e ) {
			if ( $e instanceof MailerException ) {
				throw $e;
			}

			throw new MailerException( sprintf( 'wp_mail() failure. Original error: %s', $e->getMessage() ), 0, $e );
		} finally {
			remove_action( 'wp_mail_failed', [ $this, 'catch_error' ], 99999 );
			remove_filter( 'wp_mail_from', $from_cb );
			remove_filter( 'wp_mail_from_name', $from_name_cb );
		}
	}

	/** @return void */
	public function catch_error( \WP_Error $error ) {
		throw MailerException::with_wp_error( $error );
	}
}
