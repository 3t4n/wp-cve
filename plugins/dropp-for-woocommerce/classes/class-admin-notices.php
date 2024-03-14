<?php

namespace Dropp;

use Dropp\Utility\Admin_Notice_Utility;

class Admin_Notices {
	public static function setup(): void
	{
		Admin_Notice_Utility::setup();
		Admin_Notice_Utility::register(
			'dropp_cost_tier_upgrade_notice',
			new Admin_Notice(
				__(
					'Attention: Update on Dropp shipping methods - Action required',
					'dropp-for-woocommerce'
				),
				__(
					"Starting from June 1st, Dropp's service will be extended and we will add a service for heavier shipments. Dropp customers will receive a notification about the service changes in an email in the coming days. The new Dropp price list is already available at %s.

In parallel with these changes, we have updated the settings in WooCommerce and it is now possible to enter different prices based on the weight of packages. We recommend that you review all prices before June 1st when the changes take effect. %s. Updated instructions will be available shortly at %s.

If you have any questions, do not hesitate to contact us at dropp@dropp.is or by phone at 546-6100.

Best regards,
Dropp",
					'dropp-for-woocommerce'
				),
				[
					new Admin_Notice_Link(
						'https://dropp.is/verdskra',
						'https://dropp.is/verdskra'
					),
					new Admin_Notice_Link(
						__('Click here to go to the settings', 'dropp-for-woocommerce'),
						admin_url('admin.php?page=wc-settings&tab=shipping')
					),
					new Admin_Notice_Link(
						'https://hjalp.dropp.is',
						'https://hjalp.dropp.is'
					),

				],
			)
		);
		Admin_Notice_Utility::load_options();
	}
}
