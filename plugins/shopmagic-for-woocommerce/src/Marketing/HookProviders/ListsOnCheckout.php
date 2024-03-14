<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\HookProviders;

use ShopMagicVendor\WPDesk\View\Renderer\Renderer;
use WPDesk\ShopMagic\Components\HookProvider\Conditional;
use WPDesk\ShopMagic\Components\HookProvider\HookProvider;
use WPDesk\ShopMagic\Components\HookProvider\HookTrait;
use WPDesk\ShopMagic\Helper\WordPressPluggableHelper;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListRepository;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SubscriberObjectRepository;

/**
 * Display marketing list agreements (checkboxs) on checkout page.
 */
final class ListsOnCheckout implements HookProvider, Conditional {
	use HookTrait;

	/** @var SubscriberObjectRepository */
	private $subscribers_repository;

	/** @var Renderer */
	private $renderer;

	/** @var AudienceListRepository */
	private $repository;

	public function __construct(
		AudienceListRepository $repository,
		SubscriberObjectRepository $subscriber_repository,
		Renderer $renderer
	) {
		$this->repository             = $repository;
		$this->subscribers_repository = $subscriber_repository;
		$this->renderer               = $renderer;
	}

	public function hooks(): void {
		$this->add_action(
			'woocommerce_checkout_after_terms_and_conditions',
			[ $this, 'render_after_terms_optins' ]
		);
	}

	private function render_after_terms_optins(): void {
		$viewable_items = $this->repository->find_checkout_viewable_items();
		foreach ( $viewable_items as $type ) {
			$this->renderer->output_render(
				'marketing-lists/checkout_optin',
				[
					'type'     => $type,
					'opted_in' => $this->subscribers_repository->is_subscribed_to_list(
						$this->get_email(),
						$type->get_id()
					),
				]
			);
		}
	}

	private function get_email(): string {
		$session_data = WC()->session->get( 'customer' );
		if ( isset( $session_data['email'] ) ) {
			return sanitize_email( $session_data['email'] );
		}

		return '';
	}

	public static function is_needed(): bool {
		return WordPressPluggableHelper::is_plugin_active( 'woocommerce/woocommerce.php' );
	}
}
