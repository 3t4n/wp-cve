<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\HookProviders;

use WPDesk\ShopMagic\Components\HookProvider\Conditional;
use WPDesk\ShopMagic\Components\HookProvider\HookProvider;
use WPDesk\ShopMagic\Components\HookProvider\HookTrait;
use WPDesk\ShopMagic\Customer\CustomerRepository;
use WPDesk\ShopMagic\Helper\WordPressPluggableHelper;
use WPDesk\ShopMagic\Marketing\Subscribers\CommunicationPreferencesRenderer;
use WPDesk\ShopMagic\Marketing\Subscribers\PreferencesRoute;

/**
 * Attach marketing subscription preferences view to customer account page.
 */
final class WooCommerceAccountPreferences implements HookProvider, Conditional {
	use HookTrait;

	private const CUSTOMER_LOGOUT = 'customer-logout';
	/** @var CustomerRepository */
	private $customer_repository;
	/** @var CommunicationPreferencesRenderer */
	private $renderer;

	public function __construct(
		CustomerRepository $customer_repository,
		CommunicationPreferencesRenderer $renderer
	) {
		$this->customer_repository = $customer_repository;
		$this->renderer            = $renderer;
	}

	public function hooks(): void {
		$this->add_filter(
			'woocommerce_account_menu_items',
			[ $this, 'new_menu_items' ]
		);

		$this->add_action(
			'woocommerce_account_' . PreferencesRoute::get_slug() . '_endpoint',
			[ $this, 'nav_menu_content' ]
		);
	}

	public static function is_needed(): bool {
		if ( ! WordPressPluggableHelper::is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return false;
		}

		return (bool) apply_filters( 'shopmagic/core/communication_type/account_page_show', true );
	}

	/**
	 * Insert the new endpoint into the My Account menu.
	 *
	 * @param string[] $items
	 *
	 * @return string[]
	 */
	private function new_menu_items( array $items ): array {
		$logout_item = false;

		if ( isset( $items[ self::CUSTOMER_LOGOUT ] ) ) {
			$logout_item = $items[ self::CUSTOMER_LOGOUT ];
			unset( $items[ self::CUSTOMER_LOGOUT ] );
		}

		$items[ PreferencesRoute::get_slug() ] = $this->get_title();

		if ( $logout_item ) {
			$items[ self::CUSTOMER_LOGOUT ] = $logout_item;
		}

		return $items;
	}

	private function get_title(): string {
		return apply_filters(
			'shopmagic/core/communication_type/account_page_title',
			esc_html__( 'Communication', 'shopmagic-for-woocommerce' )
		);
	}

	private function nav_menu_content(): void {
		$customer = $this->customer_repository->find( get_current_user_id() );

		echo $this->renderer->render( $customer, [ 'success' => null, 'obfuscate' => false ] );
	}

}
