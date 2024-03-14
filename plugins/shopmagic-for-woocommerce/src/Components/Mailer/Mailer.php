<?php

namespace WPDesk\ShopMagic\Components\Mailer;


/**
 * Object-oriented wp_mail wrapper.
 */
interface Mailer {
	/**
	 * @throws MailerException
	 */
	public function send( Email $message ): void;
}
