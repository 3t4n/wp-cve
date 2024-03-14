<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Mailer;

use WPDesk\ShopMagic\Exception\ShopMagicException;

class MailerException extends \RuntimeException implements ShopMagicException {
	public static function with_wp_error( \WP_Error $error ): self {
		$errors  = $error->get_error_messages( 'wp_mail_failed' );
		$message = implode( "\n", $errors );

		return new self( sprintf( 'wp_mail() failure. Message [%s]', $message ) );
	}
}
