<?php
namespace WC_BPost_Shipping\Assets;

/**
 * Class WC_BPost_Shipping_Assets_Strings contains strings
 * The good way to do is to use only keys and write text into po files but no fallback possible in this case
 * @package WC_BPost_Shipping\Assets
 */
class WC_BPost_Shipping_Assets_Strings {

	/**
	 * Description used in admin side on settings page
	 * @return string
	 */
	public function get_description() {
		return sprintf(
			__(
				"<div id='bpost-description'>
%s
bpost Shipping Manager is a service offered by bpost, allowing your customer to choose their preferred delivery method when ordering in your webshop.

The following delivery methods are currently supported:
<ul>
	<li>Delivery at home or at the office (national or international)</li>
	<li>Delivery in a pick-up point or postal office</li>
	<li>Delivery in a parcel locker</li>
</ul>

The orders are automatically added on Woocommerce >> orders and on your bpost portal Shipping Manager Back-end.

No more hassle and 100%% transparent!

Furthermore, it's also possible to generate your labels directly on the Woocommerce order admin page.

More info regarding the required bpost account : %s

Documentation and first line support, please visit : %s
</div>",
				BPOST_PLUGIN_ID
			),
			"<div class='bpost-logo'><img style'max-height: 100px' src='" . BPOST_PLUGIN_URL . "public/images/bpost_logo_4c_c.png'></div>",
			"<a target='_blank' href='http://bpost.freshdesk.com/solution/articles/174847'>http://bpost.freshdesk.com/solution/articles/174847</a>",
			"<a target='_blank' href='http://bpost.freshdesk.com/support/solutions/folders/4000015009'>http://bpost.freshdesk.com/support/solutions/folders/4000015009</a>"
		);
	}



	/**
	 * Title for plugin
	 * @return string
	 */
	public function get_title() {
		return bpost__( 'bpost Shipping' );
	}
}
