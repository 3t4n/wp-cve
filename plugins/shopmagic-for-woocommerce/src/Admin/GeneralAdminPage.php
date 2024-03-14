<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Admin;

use WPDesk\ShopMagic\Helper\CapabilitiesCheckTrait;
use WPDesk\ShopMagic\Helper\WordPressPluggableHelper;

class GeneralAdminPage implements \WPDesk\ShopMagic\Admin\AdminPage {
	use CapabilitiesCheckTrait;

	private const MENU_SLUG = 'shopmagic-admin';

	public function register(): void {
		$cap = $this->allowed_capability();
		if ( ! $cap ) {
			return;
		}

		add_menu_page(
			__( 'ShopMagic', 'shopmagic-for-woocommerce' ),
			__( 'ShopMagic', 'shopmagic-for-woocommerce' ),
			$cap,
			self::MENU_SLUG,
			function (): void {
				$this->render();
			},
			'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="black" d="M224 96l16-32 32-16-32-16-16-32-16 32-32 16 32 16 16 32zM80 160l26.66-53.33L160 80l-53.34-26.67L80 0 53.34 53.33 0 80l53.34 26.67L80 160zm352 128l-26.66 53.33L352 368l53.34 26.67L432 448l26.66-53.33L512 368l-53.34-26.67L432 288zm70.62-193.77L417.77 9.38C411.53 3.12 403.34 0 395.15 0c-8.19 0-16.38 3.12-22.63 9.38L9.38 372.52c-12.5 12.5-12.5 32.76 0 45.25l84.85 84.85c6.25 6.25 14.44 9.37 22.62 9.37 8.19 0 16.38-3.12 22.63-9.37l363.14-363.15c12.5-12.48 12.5-32.75 0-45.24zM359.45 203.46l-50.91-50.91 86.6-86.6 50.91 50.91-86.6 86.6z"/></svg>' ),
			25
		);

		add_submenu_page(
			self::MENU_SLUG,
			__( 'Dashboard', 'shopmagic-for-woocommerce' ),
			__( 'Dashboard', 'shopmagic-for-woocommerce' ),
			$cap,
			self::MENU_SLUG,
			function (): void {
				$this->render();
			}
		);
		add_submenu_page(
			self::MENU_SLUG,
			__( 'Automations', 'shopmagic-for-woocommerce' ),
			__( 'Automations', 'shopmagic-for-woocommerce' ),
			$cap,
			self::MENU_SLUG . '#/automations',
			function (): void {
				$this->render();
			}
		);
		add_submenu_page(
			self::MENU_SLUG,
			__( 'Marketing Lists', 'shopmagic-for-woocommerce' ),
			__( 'Marketing Lists', 'shopmagic-for-woocommerce' ),
			$cap,
			self::MENU_SLUG . '#/marketing-lists',
			function (): void {
				$this->render();
			}
		);
		add_submenu_page(
			self::MENU_SLUG,
			__( 'Logs', 'shopmagic-for-woocommerce' ),
			__( 'Logs', 'shopmagic-for-woocommerce' ),
			$cap,
			self::MENU_SLUG . '#/logs/outcomes',
			function (): void {
				$this->render();
			}
		);
		if (WordPressPluggableHelper::is_plugin_active('shopmagic-abandoned-carts/shopmagic-abandoned-carts.php')) {
			add_submenu_page(
				self::MENU_SLUG,
				__( 'Carts', 'shopmagic-for-woocommerce' ),
				__( 'Carts', 'shopmagic-for-woocommerce' ),
				$cap,
				self::MENU_SLUG . '#/carts',
				function (): void {
					$this->render();
				}
			);
		}
		add_submenu_page(
			self::MENU_SLUG,
			__( 'Guests', 'shopmagic-for-woocommerce' ),
			__( 'Guests', 'shopmagic-for-woocommerce' ),
			$cap,
			self::MENU_SLUG . '#/guests',
			function (): void {
				$this->render();
			}
		);
		add_submenu_page(
			self::MENU_SLUG,
			__( 'Settings', 'shopmagic-for-woocommerce' ),
			__( 'Settings', 'shopmagic-for-woocommerce' ),
			$cap,
			self::MENU_SLUG . '#/settings',
			function (): void {
				$this->render();
			}
		);
		add_submenu_page(
			self::MENU_SLUG,
			__( 'Start Here', 'shopmagic-for-woocommerce' ),
			__( 'Start Here', 'shopmagic-for-woocommerce' ),
			$cap,
			self::MENU_SLUG . '#/welcome',
			function (): void {
				$this->render();
			}
		);
	}

	public function render(): void {
		?>
		<div id="shopmagic-app"></div>
		<?php
	}
}
