<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Subscribers;

use ShopMagicVendor\WPDesk\View\Renderer\Renderer;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SubscriberObjectRepository;
use WPDesk\ShopMagic\Marketing\Util\EmailObsufcator;

/**
 * Helper class responsible for rendering communication preferences page.
 */
class CommunicationPreferencesRenderer {

	/** @var Renderer */
	private $renderer;

	/** @var EmailObsufcator */
	private $obfuscator;

	/** @var SubscriberObjectRepository */
	private $subscribers_repository;

	public function __construct(
		Renderer $renderer,
		EmailObsufcator $obfuscator,
		SubscriberObjectRepository $subscribers_repository
	) {
		$this->renderer               = $renderer;
		$this->obfuscator             = $obfuscator;
		$this->subscribers_repository = $subscribers_repository;
	}

	public function render_wrap_start(): string {
		return $this->renderer->render( 'marketing-lists/communication_preferences_wrap_start' );
	}

	public function render_wrap_end(): string {
		return $this->renderer->render( 'marketing-lists/communication_preferences_wrap_end' );
	}

	/**
	 * @param Customer $customer
	 * @param array{
	 *     obfuscate?: bool,
	 *     success?: bool
	 * }               $params
	 *
	 * @return string
	 */
	public function render( Customer $customer, array $params = [] ): string {
		$params = array_merge(
			[
				'obfuscate' => true,
				'success'   => null,
			],
			$params
		);

		$email = $params['obfuscate'] === true
			? $this->obfuscator->obfuscate( $customer->get_email() )
			: $customer->get_email();

		return $this->renderer->render(
			'marketing-lists/communication_preferences',
			[
				'success'       => $params['success'],
				'email'         => $customer->get_email(),
				'email_display' => $email,
				'action'        => PreferencesRoute::get_slug(),
				'signed_ups'    => $this->subscribers_repository->find_by(
					[
						'email'  => $customer->get_email(),
						'active' => 1,
					]
				),
			]
		);
	}

}
