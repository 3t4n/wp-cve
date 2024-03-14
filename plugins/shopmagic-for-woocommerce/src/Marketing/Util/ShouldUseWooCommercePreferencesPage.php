<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Util;

use WPDesk\ShopMagic\Helper\WordPressPluggableHelper;

class ShouldUseWooCommercePreferencesPage {

	public function should_use(): bool {
		if ( ! WordPressPluggableHelper::is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return false;
		}

		return (bool) apply_filters( 'shopmagic/core/communication_type/account_page_show', true );
	}

}
