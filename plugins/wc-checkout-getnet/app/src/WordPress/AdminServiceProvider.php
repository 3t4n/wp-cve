<?php

namespace WcGetnet\WordPress;

use CoffeeCode\WPEmerge\ServiceProviders\ServiceProviderInterface;

/**
 * Register and enqueues assets.
 */
class AdminServiceProvider implements ServiceProviderInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function register( $container ) {
		// Nothing to register.
	}

	/**
	 * {@inheritDoc}
	 */	
	public function bootstrap( $container ) {
		add_action( 'admin_head', [$this, 'removeAdminMessages'] );
	}

    public function removeAdminMessages() {
		if(!$this->isGetnetSettingsTab()) {
			return;
		}

		remove_all_actions( 'admin_notices' );
	}
	
	public function isGetnetSettingsTab() {
		$current_screen = get_current_screen();

		return 'woocommerce_page_getnet-settings' === $current_screen->id || $this->isGetnetPeymentMethodSettingsTab();
	}

	public function isGetnetPeymentMethodSettingsTab() {
		$currentSection = isset( $_GET["section"] ) ? $_GET["section"] : '' ;

		$getnetSection = [
			"getnet-pix", 
			"getnet-creditcard", 
			"getnet-billet" 
		];

		return in_array($currentSection, $getnetSection);
	}

}