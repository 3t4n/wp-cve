<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Subscribers;

use ShopMagicVendor\WPDesk\View\Renderer\Renderer;
use WPDesk\ShopMagic\Components\Mailer\Email;
use WPDesk\ShopMagic\Components\Mailer\Mailer;
use WPDesk\ShopMagic\Components\Mailer\MailerException;
use WPDesk\ShopMagic\Components\Mailer\WPMailMailer;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Marketing\HookProviders\ConfirmedSubscriptionSaver;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceList;
use WPDesk\ShopMagic\Marketing\Util\EmailHasher;

/**
 * Dispatch email to customer with subscription confirmation content.
 */
final class ConfirmationDispatcher {

	/** @var Mailer */
	private $mailer;

	/** @var Renderer */
	private $renderer;

	/** @var EmailHasher */
	private $email_hasher;

	public function __construct(
		Renderer $renderer,
		EmailHasher $email_hasher,
		Mailer $mailer = null
	) {
		$this->renderer     = $renderer;
		$this->email_hasher = $email_hasher;
		$this->mailer       = $mailer ?? new WPMailMailer();
	}

	/**
	 * @throws \InvalidArgumentException
	 * @throws MailerException
	 */
	public function dispatch_confirmation_email( Customer $customer, AudienceList $target_list ): void {
		if ( empty( $customer->get_email() ) ) {
			throw new \InvalidArgumentException(
				esc_html__(
					'Your customer needs a valid email to request double opt in confirmation.',
					'shopmagic-for-woocommerce'
				)
			);
		}

		if ( ! $target_list->exists() ) {
			throw new \InvalidArgumentException(
				esc_html__(
					'You are probably trying to sign up your customer on a list which does not exists. Update the target list.',
					'shopmagic-for-woocommerce'
				)
			);
		}

		$email = ( new Email() )
			->to( $customer->get_email() )
			->subject( esc_html__( 'Confirm your sign up', 'shopmagic-for-woocommerce' ) )
			->message( $this->get_message_content( $customer, $target_list ) );
		$this->mailer->send( $email );
	}

	private function get_message_content( Customer $customer, AudienceList $target_list ): string {
		return $this->renderer->render(
			'emails/sign_up_confirmation',
			[
				'customer'          => $customer,
				'list_id'           => $target_list->get_id(),
				'list_title'        => $target_list->get_name(),
				'confirmation_link' => $this->get_confirmation_link( $customer, $target_list ),
			]
		);
	}

	private function get_confirmation_link( Customer $customer, AudienceList $target_list ): string {
		return admin_url( 'admin-post.php' ) . '?' . http_build_query(
			[
				'action'  => ConfirmedSubscriptionSaver::ACTION,
				'hash'    => $this->email_hasher->hash( $customer->get_email() ),
				'id'      => $customer->get_id(),
				'list_id' => $target_list->get_id(),
			]
		);
	}
}
